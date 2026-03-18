<?php
/**
 * Receipt Handler
 *
 * Handles all donation receipt functionality:
 *  - HTML receipt pages served at ?myco_receipt=TOKEN  (stored in WP options)
 *  - PDF receipt generation via DOMPDF + email attachment (requires vendor/autoload.php)
 *  - HTML email receipts via wp_mail()
 *  - Monthly subscription confirmation emails
 *  - Subscription cancellation page (?cancel_sub=TOKEN)
 *  - Admin AJAX: resend receipt
 *  - DB migration: receipt_path column
 *
 * @package MYCO
 */

if (!defined('ABSPATH')) {
    exit;
}

// ─────────────────────────────────────────────────────────────────────────────
// 1. QUERY VARS
// ─────────────────────────────────────────────────────────────────────────────

add_filter('query_vars', function (array $vars): array {
    $vars[] = 'myco_receipt';
    $vars[] = 'cancel_sub';
    return $vars;
});

// ─────────────────────────────────────────────────────────────────────────────
// 2. RECEIPT DOWNLOAD ENDPOINT
//    Handles two URL styles:
//    (a) ?myco_receipt=TOKEN            — option-based HTML receipt (primary)
//    (b) ?myco_receipt=SESSION_ID&token=HMAC — PDF file download (legacy/admin)
// ─────────────────────────────────────────────────────────────────────────────

add_action('template_redirect', 'myco_maybe_serve_receipt', 10);

function myco_maybe_serve_receipt(): void {
    $receipt_param = get_query_var('myco_receipt');
    if (empty($receipt_param)) return;

    $receipt_param = sanitize_text_field($receipt_param);

    // (a) Option-based HTML receipt
    $data = get_option('myco_receipt_' . $receipt_param);
    if ($data) {
        myco_render_receipt_page($data);
        exit;
    }

    // (b) Legacy PDF download: ?myco_receipt=SESSION_ID&token=HMAC
    $token = sanitize_text_field($_GET['token'] ?? '');
    if (!empty($token)) {
        $session_id = rawurldecode($receipt_param);

        if (!hash_equals(myco_receipt_token($session_id), $token)) {
            wp_die('Invalid or expired receipt link.', 'Receipt Error', ['response' => 403]);
        }

        global $wpdb;
        $donation = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}myco_donations WHERE stripe_session_id = %s AND status = 'completed' LIMIT 1",
            $session_id
        ));

        if (!$donation) {
            wp_die('Receipt not found.', 'Receipt Error', ['response' => 404]);
        }

        // Regenerate PDF on the fly if the file was deleted
        if (empty($donation->receipt_path) || !file_exists($donation->receipt_path)) {
            myco_send_donation_receipt($donation);
            $donation = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}myco_donations WHERE id = %d LIMIT 1",
                $donation->id
            ));
        }

        if (empty($donation->receipt_path) || !file_exists($donation->receipt_path)) {
            wp_die('Receipt file unavailable. Please contact us.', 'Receipt Error', ['response' => 500]);
        }

        $filename = 'MYCO-Donation-Receipt-' . date('Y-m-d', strtotime($donation->created_at)) . '.pdf';
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($donation->receipt_path));
        header('Cache-Control: private, no-cache, no-store');
        readfile($donation->receipt_path);
        exit;
    }

    wp_die('Receipt not found or expired.', 'Receipt Not Found', ['response' => 404]);
}

// ─────────────────────────────────────────────────────────────────────────────
// 3. PROCESS SUCCESSFUL PAYMENT INTENT
//    Called from template-donate.php on ?donation=success
//    Returns receipt token (string) or '' on failure / already processed.
// ─────────────────────────────────────────────────────────────────────────────

function myco_process_successful_payment(string $payment_intent_id): string {
    if (empty($payment_intent_id)) return '';

    // Prevent duplicate processing
    $existing_token = get_option('myco_pi_token_' . $payment_intent_id);
    if ($existing_token) return $existing_token;

    // Fetch PaymentIntent from Stripe
    $keys       = myco_stripe_get_keys();
    $secret_key = $keys['secret'] ?? '';
    if (empty($secret_key)) return '';

    $response = wp_remote_get(
        'https://api.stripe.com/v1/payment_intents/' . urlencode($payment_intent_id),
        [
            'timeout' => 15,
            'headers' => [
                'Authorization'  => 'Bearer ' . $secret_key,
                'Stripe-Version' => '2024-06-20',
            ],
        ]
    );

    if (is_wp_error($response)) return '';

    $pi     = json_decode(wp_remote_retrieve_body($response), true);
    $status = wp_remote_retrieve_response_code($response);

    if ($status !== 200 || ($pi['status'] ?? '') !== 'succeeded') return '';

    // Build receipt data
    $amount   = ($pi['amount'] ?? 0) / 100;
    $currency = strtoupper($pi['currency'] ?? 'USD');
    $email    = $pi['receipt_email'] ?? ($pi['metadata']['donor_email'] ?? '');
    $fund     = $pi['metadata']['fund'] ?? 'general';
    $don_type = $pi['metadata']['donation_type'] ?? 'one-time';
    $ein      = get_option('myco_ein', '');

    $fund_label = myco_receipt_fund_label($fund);

    $token = bin2hex(random_bytes(16));
    $date  = current_time('F j, Y');
    $time  = current_time('g:i A T');
    $org   = get_bloginfo('name');
    $site  = home_url();

    $receipt = [
        'token'          => $token,
        'payment_intent' => $payment_intent_id,
        'amount'         => $amount,
        'currency'       => $currency,
        'fund'           => $fund,
        'fund_label'     => $fund_label,
        'donation_type'  => $don_type,
        'email'          => $email,
        'ein'            => $ein,
        'org'            => $org,
        'site'           => $site,
        'date'           => $date,
        'time'           => $time,
        'receipt_no'     => strtoupper(substr($token, 0, 8)),
    ];

    // Store receipt (accessible for 90 days)
    update_option('myco_receipt_' . $token, $receipt, false);
    // Map payment_intent → token (for dedup)
    update_option('myco_pi_token_' . $payment_intent_id, $token, false);

    // Update DB donation record
    global $wpdb;
    $wpdb->update(
        $wpdb->prefix . 'myco_donations',
        ['status' => 'complete', 'stripe_payment_intent' => $payment_intent_id, 'donor_email' => $email],
        ['stripe_session_id' => $payment_intent_id],
        ['%s', '%s', '%s'],
        ['%s']
    );

    // Send HTML email receipt
    if (!empty($email) && is_email($email)) {
        myco_send_receipt_email($receipt);
    }

    return $token;
}

// ─────────────────────────────────────────────────────────────────────────────
// 4. SEND HTML EMAIL RECEIPT
// ─────────────────────────────────────────────────────────────────────────────

function myco_send_receipt_email(array $r): void {
    $subject     = sprintf('Your Donation Receipt — %s', $r['org']);
    $receipt_url = home_url('/?myco_receipt=' . $r['token']);
    $amount_fmt  = '$' . number_format($r['amount'], 2) . ' ' . $r['currency'];

    $html = '<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<style>
  body{margin:0;padding:0;font-family:\'Segoe UI\',Arial,sans-serif;background:#F5F6FA;color:#141943;}
  .wrap{max-width:580px;margin:40px auto;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.08);}
  .head{background:#141943;padding:36px 40px;text-align:center;}
  .head h1{color:#fff;font-size:22px;font-weight:900;margin:0;}
  .head p{color:rgba(255,255,255,.65);font-size:13px;margin:6px 0 0;}
  .body{padding:36px 40px;}
  .amount{text-align:center;background:#F5F6FA;border-radius:14px;padding:24px;margin-bottom:28px;}
  .amount span{font-size:40px;font-weight:900;color:#C8402E;}
  .amount small{display:block;color:#6B7280;font-size:13px;margin-top:4px;}
  table{width:100%;border-collapse:collapse;margin-bottom:28px;}
  td{padding:10px 0;border-bottom:1px solid #F3F4F6;font-size:14px;}
  td:first-child{color:#6B7280;font-weight:600;}
  td:last-child{text-align:right;color:#141943;font-weight:700;}
  .btn{display:block;background:#C8402E;color:#fff;text-align:center;padding:16px 28px;border-radius:9999px;font-weight:700;font-size:15px;text-decoration:none;margin:0 auto 28px;}
  .note{font-size:12px;color:#9CA3AF;text-align:center;line-height:1.6;}
  .foot{background:#F5F6FA;padding:20px 40px;text-align:center;font-size:12px;color:#9CA3AF;}
</style></head>
<body>
<div class="wrap">
  <div class="head">
    <h1>' . esc_html($r['org']) . '</h1>
    <p>Official Donation Receipt</p>
  </div>
  <div class="body">
    <div class="amount">
      <span>' . esc_html($amount_fmt) . '</span>
      <small>' . esc_html($r['donation_type'] === 'monthly' ? 'Monthly Recurring Donation' : 'One-Time Donation') . '</small>
    </div>
    <table>
      <tr><td>Receipt No.</td><td>#' . esc_html($r['receipt_no']) . '</td></tr>
      <tr><td>Date</td><td>' . esc_html($r['date']) . ' at ' . esc_html($r['time']) . '</td></tr>
      <tr><td>Fund</td><td>' . esc_html($r['fund_label']) . '</td></tr>
      <tr><td>Transaction ID</td><td><small>' . esc_html($r['payment_intent']) . '</small></td></tr>
      ' . ($r['ein'] ? '<tr><td>EIN (Tax ID)</td><td>' . esc_html($r['ein']) . '</td></tr>' : '') . '
    </table>
    <a href="' . esc_url($receipt_url) . '" class="btn">⬇ View / Print Receipt</a>
    <p class="note">
      ' . esc_html($r['org']) . ' is a 501(c)(3) nonprofit organization.' . ($r['ein'] ? ' Tax ID: ' . esc_html($r['ein']) . '.' : '') . '<br>
      No goods or services were provided in exchange for this contribution. This letter serves as your official receipt for tax purposes.
    </p>
  </div>
  <div class="foot">
    ' . esc_html($r['org']) . ' &bull; <a href="' . esc_url($r['site']) . '" style="color:#C8402E;">' . esc_url($r['site']) . '</a><br>
    Questions? Reply to this email or visit our website.
  </div>
</div>
</body></html>';

    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>',
    ];

    wp_mail($r['email'], $subject, $html, $headers);
}

function myco_receipt_logo_data_uri(string $variant = 'color'): string {
    $files = ('white' === $variant)
        ? ['myco-logo-white.png', 'myco-logo.png']
        : ['myco-logo.png', 'myco-logo-white.png'];

    foreach ($files as $file) {
        $path = trailingslashit(MYCO_DIR) . 'assets/images/' . $file;

        if (!file_exists($path) || !is_readable($path)) {
            continue;
        }

        $contents = file_get_contents($path);
        if (false === $contents) {
            continue;
        }

        $mime = function_exists('wp_get_image_mime') ? wp_get_image_mime($path) : '';
        if (!$mime && function_exists('mime_content_type')) {
            $mime = mime_content_type($path);
        }
        if (!$mime) {
            $mime = 'image/png';
        }

        return 'data:' . $mime . ';base64,' . base64_encode($contents);
    }

    return '';
}

// ─────────────────────────────────────────────────────────────────────────────
// 5. RENDER PRINTABLE HTML RECEIPT PAGE
//    Served at ?myco_receipt=TOKEN
// ─────────────────────────────────────────────────────────────────────────────

function myco_render_receipt_page(array $r): void {
    $amount_fmt = '$' . number_format($r['amount'], 2) . ' ' . $r['currency'];
    $org        = esc_html($r['org']);
    $site_url   = esc_url(home_url());
    $brand_short = 'MYCO';
    $brand_full  = 'Muslim Youth of Central Ohio';
    $logo_src    = myco_receipt_logo_data_uri('white');
    ?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Donation Receipt – <?php echo $org; ?></title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');
  *{box-sizing:border-box;margin:0;padding:0;}
  body{font-family:'Inter',sans-serif;background:#F5F6FA;color:#141943;padding:40px 20px;}
  .page{max-width:680px;margin:0 auto;background:#fff;border-radius:20px;box-shadow:0 8px 36px rgba(20,25,67,.12);overflow:hidden;}
  .header{background:#141943;padding:36px 44px;display:flex;justify-content:space-between;align-items:center;gap:24px;}
  .header .brand{display:flex;align-items:center;gap:18px;min-width:0;}
  .header .brand-logo{width:82px;height:82px;display:flex;align-items:center;justify-content:center;flex-shrink:0;border-radius:20px;padding:8px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);overflow:hidden;box-shadow:inset 0 1px 0 rgba(255,255,255,.18);}
  .header .brand-logo img{display:block;width:100%;height:100%;object-fit:contain;transform:scale(1.08);}
  .header .brand-copy{min-width:0;}
  .header .logo{font-size:30px;font-weight:900;color:#fff;letter-spacing:-.02em;line-height:1;}
  .header .brand-name{color:rgba(255,255,255,.78);font-size:15px;font-weight:600;margin-top:5px;line-height:1.35;}
  .header .meta{text-align:right;}
  .header .meta p{color:rgba(255,255,255,.55);font-size:13px;}
  .header .meta strong{color:#fff;font-size:16px;}
  .receipt-badge{background:#C8402E;color:#fff;font-size:11px;font-weight:700;letter-spacing:.1em;padding:4px 12px;border-radius:9999px;text-transform:uppercase;display:inline-block;margin-top:6px;}
  .body{padding:48px;}
  .amount-block{background:#F5F6FA;border-radius:16px;padding:32px;text-align:center;margin-bottom:36px;}
  .amount-block .big{font-size:52px;font-weight:900;color:#C8402E;line-height:1;}
  .amount-block .type{color:#6B7280;font-size:14px;margin-top:8px;font-weight:600;}
  .details{width:100%;margin-bottom:36px;border-collapse:collapse;}
  .details tr td{padding:14px 0;border-bottom:1px solid #F3F4F6;font-size:15px;}
  .details tr:last-child td{border-bottom:none;}
  .details td:first-child{color:#6B7280;font-weight:600;width:45%;}
  .details td:last-child{color:#141943;font-weight:700;text-align:right;}
  .notice{background:#F5F6FA;border-radius:12px;padding:20px 24px;font-size:13px;color:#4B5563;line-height:1.7;margin-bottom:32px;}
  .btn-print{display:inline-flex;align-items:center;gap:8px;background:#141943;color:#fff;padding:14px 28px;border-radius:9999px;font-weight:700;font-size:15px;cursor:pointer;border:none;font-family:inherit;transition:background .18s;}
  .btn-print:hover{background:#C8402E;}
  .footer{background:#F5F6FA;border-top:1px solid #E5E7EB;padding:20px 48px;display:flex;justify-content:space-between;font-size:12px;color:#9CA3AF;}
  .footer a{color:#C8402E;text-decoration:none;}
  @media print{
    body{background:#fff;padding:0;}
    .page{box-shadow:none;border-radius:0;max-width:100%;}
    .no-print{display:none!important;}
    .header{-webkit-print-color-adjust:exact;print-color-adjust:exact;}
    .amount-block{-webkit-print-color-adjust:exact;print-color-adjust:exact;}
  }
</style>
</head>
<body>
<div class="page">

  <div class="header">
    <div class="brand">
      <?php if ($logo_src) : ?>
      <div class="brand-logo">
        <img src="<?php echo esc_attr($logo_src); ?>" alt="MYCO logo">
      </div>
      <?php endif; ?>
      <div class="brand-copy">
        <div class="logo"><?php echo esc_html($brand_short); ?></div>
        <div class="brand-name"><?php echo esc_html($brand_full); ?></div>
        <div class="receipt-badge">Official Donation Receipt</div>
      </div>
    </div>
    <div class="meta">
      <p>Receipt No.</p>
      <strong>#<?php echo esc_html($r['receipt_no']); ?></strong>
      <br><p style="margin-top:4px;"><?php echo esc_html($r['date']); ?></p>
    </div>
  </div>

  <div class="body">

    <div class="amount-block">
      <div class="big"><?php echo esc_html($amount_fmt); ?></div>
      <div class="type"><?php echo esc_html($r['donation_type'] === 'monthly' ? '📅 Monthly Recurring Donation' : '💚 One-Time Donation'); ?></div>
    </div>

    <table class="details">
      <tr><td>Organization</td><td><?php echo $org; ?></td></tr>
      <tr><td>Fund</td><td><?php echo esc_html($r['fund_label']); ?></td></tr>
      <tr><td>Date</td><td><?php echo esc_html($r['date'] . ' at ' . $r['time']); ?></td></tr>
      <tr><td>Payment Method</td><td>Credit / Debit Card (Stripe)</td></tr>
      <tr><td>Transaction ID</td><td style="font-size:12px;word-break:break-all;"><?php echo esc_html($r['payment_intent']); ?></td></tr>
      <?php if ($r['ein']) : ?>
      <tr><td>EIN / Tax ID</td><td><?php echo esc_html($r['ein']); ?></td></tr>
      <?php endif; ?>
      <?php if (!empty($r['email'])) : ?>
      <tr><td>Donor Email</td><td><?php echo esc_html($r['email']); ?></td></tr>
      <?php endif; ?>
    </table>

    <div class="notice">
      <strong><?php echo $org; ?></strong> is a 501(c)(3) nonprofit charitable organization.
      <?php if ($r['ein']) echo 'Tax ID: ' . esc_html($r['ein']) . '.'; ?><br>
      No goods or services were provided in exchange for this donation. This document serves as your official receipt for income tax purposes.
    </div>

    <div class="no-print" style="text-align:center;">
      <button class="btn-print" onclick="window.print()">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M6 9V3h12v6M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2M6 14h12v7H6v-7Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Print / Download as PDF
      </button>
      <p style="font-size:12px;color:#9CA3AF;margin-top:10px;">Use your browser's Print → Save as PDF option</p>
    </div>

  </div>

  <div class="footer">
    <span><?php echo $org; ?> &bull; <a href="<?php echo $site_url; ?>"><?php echo $site_url; ?></a></span>
    <span>Thank you for your generosity!</span>
  </div>

</div>
<script>
  if (new URLSearchParams(location.search).get('print') === '1') {
    setTimeout(function(){ window.print(); }, 600);
  }
</script>
</body>
</html><?php
}

// ─────────────────────────────────────────────────────────────────────────────
// 6. SUBSCRIPTION CANCELLATION PAGE
//    Served at ?cancel_sub=TOKEN
// ─────────────────────────────────────────────────────────────────────────────

add_action('template_redirect', 'myco_maybe_handle_cancel_sub', 5);

function myco_maybe_handle_cancel_sub(): void {
    $token = get_query_var('cancel_sub');
    if (empty($token)) return;

    $token = sanitize_text_field($token);
    $data  = get_option('myco_cancel_' . $token);
    if (!$data) {
        wp_die('This cancellation link is invalid or has already been used.', 'Link Invalid', ['response' => 410]);
    }

    $sub_id      = $data['subscription_id'] ?? '';
    $org         = get_bloginfo('name');
    $admin_email = get_option('admin_email');
    $fund_label  = myco_receipt_fund_label($data['fund'] ?? 'general');
    $amount_fmt  = '$' . number_format(floatval($data['amount'] ?? 0), 2);

    $cancelled = false;
    $error_msg = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_cancel']) && $sub_id) {
        $keys = myco_stripe_get_keys();
        $res  = wp_remote_post('https://api.stripe.com/v1/subscriptions/' . urlencode($sub_id), [
            'timeout' => 15,
            'headers' => [
                'Authorization'  => 'Bearer ' . ($keys['secret'] ?? ''),
                'Content-Type'   => 'application/x-www-form-urlencoded',
                'Stripe-Version' => '2024-06-20',
            ],
            'body' => ['cancel_at_period_end' => 'true'],
        ]);
        $body = json_decode(wp_remote_retrieve_body($res), true);
        if (!is_wp_error($res) && wp_remote_retrieve_response_code($res) === 200) {
            $cancelled = true;
            delete_option('myco_cancel_' . $token);
        } else {
            $error_msg = $body['error']['message'] ?? 'Could not cancel. Please contact us directly.';
        }
    }

    header('Content-Type: text/html; charset=utf-8');
    ?><!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?php echo $cancelled ? 'Subscription Cancelled' : 'Cancel Subscription'; ?> — <?php echo esc_html($org); ?></title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap');
  *{box-sizing:border-box;margin:0;padding:0;}
  body{font-family:'Inter',sans-serif;background:#F5F6FA;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;}
  .card{background:#fff;border-radius:20px;box-shadow:0 8px 36px rgba(20,25,67,.12);max-width:500px;width:100%;overflow:hidden;}
  .head{background:#141943;padding:28px 32px;text-align:center;}
  .head h1{color:#fff;font-size:20px;font-weight:900;margin:0;}
  .body{padding:36px 32px;}
  h2{font-size:22px;font-weight:900;color:#141943;text-align:center;margin-bottom:10px;}
  p{font-size:14px;color:#4B5563;line-height:1.7;text-align:center;margin-bottom:14px;}
  .detail-box{background:#F5F6FA;border-radius:12px;padding:14px 18px;margin-bottom:18px;}
  .detail-box div{display:flex;justify-content:space-between;padding:5px 0;border-bottom:1px solid #E5E7EB;font-size:14px;}
  .detail-box div:last-child{border:none;}
  .detail-box span:first-child{color:#6B7280;font-weight:600;}
  .detail-box span:last-child{font-weight:700;}
  .btn-red{display:block;width:100%;background:#DC2626;color:#fff;border:none;padding:14px;border-radius:9999px;font-size:15px;font-weight:700;cursor:pointer;font-family:inherit;margin-bottom:10px;}
  .btn-red:hover{background:#B91C1C;}
  .btn-back{display:block;text-align:center;color:#6B7280;font-size:13px;text-decoration:none;padding:8px;}
  .contact-box{background:#FFF7ED;border:1px solid #FED7AA;border-radius:10px;padding:14px 16px;font-size:12px;color:#92400E;line-height:1.6;margin-top:16px;}
  .contact-box a{color:#C8402E;font-weight:600;}
  .error-box{background:#FEF2F2;border:1px solid #FECACA;border-radius:8px;padding:10px 14px;color:#DC2626;font-size:13px;text-align:center;margin-bottom:12px;}
</style>
</head><body>
<div class="card">
  <div class="head"><h1><?php echo esc_html($org); ?></h1></div>
  <div class="body">
    <?php if ($cancelled): ?>
      <p style="font-size:48px;margin-bottom:10px;">✅</p>
      <h2>Subscription Cancelled</h2>
      <p>Your <strong><?php echo esc_html($amount_fmt); ?>/month</strong> donation to <strong><?php echo esc_html($fund_label); ?></strong> has been cancelled. No further charges will be made. Thank you for your generosity.</p>
      <a href="<?php echo esc_url(home_url()); ?>" class="btn-back">← Return to <?php echo esc_html($org); ?></a>
    <?php else: ?>
      <p style="font-size:40px;margin-bottom:8px;">📅</p>
      <h2>Cancel Monthly Donation?</h2>
      <p>This will stop the following recurring donation:</p>
      <div class="detail-box">
        <div><span>Amount</span><span><?php echo esc_html($amount_fmt); ?>/month</span></div>
        <div><span>Fund</span><span><?php echo esc_html($fund_label); ?></span></div>
        <div><span>Email</span><span><?php echo esc_html($data['email'] ?? '—'); ?></span></div>
      </div>
      <?php if ($error_msg): ?><div class="error-box">⚠️ <?php echo esc_html($error_msg); ?></div><?php endif; ?>
      <form method="post">
        <?php wp_nonce_field('myco_cancel_sub'); ?>
        <button type="submit" name="confirm_cancel" value="1" class="btn-red">✕ Yes, Cancel My Monthly Donation</button>
      </form>
      <a href="<?php echo esc_url(home_url()); ?>" class="btn-back">← Keep my donation — go back</a>
      <div class="contact-box">
        <strong>Cancel button not working?</strong><br>
        Email <a href="mailto:<?php echo esc_attr($admin_email); ?>"><?php echo esc_html($admin_email); ?></a>
        with subject <em>"Cancel Subscription"</em> and your email. We cancel within 24 hours.
      </div>
    <?php endif; ?>
  </div>
</div>
</body></html>
<?php
    exit;
}

// ─────────────────────────────────────────────────────────────────────────────
// 7. MONTHLY SUBSCRIPTION CONFIRMATION EMAIL
// ─────────────────────────────────────────────────────────────────────────────

function myco_send_subscription_confirmation(array $d): void {
    if (empty($d['email']) || !is_email($d['email'])) return;

    $org         = get_bloginfo('name');
    $admin_email = get_option('admin_email');
    $cancel_url  = home_url('/?cancel_sub=' . ($d['cancel_token'] ?? ''));
    $amount_fmt  = '$' . number_format(floatval($d['amount']), 2);
    $fund_label  = myco_receipt_fund_label($d['fund'] ?? 'general');

    $html = '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">
<style>
body{margin:0;padding:0;font-family:\'Segoe UI\',Arial,sans-serif;background:#F5F6FA;}
.wrap{max-width:540px;margin:30px auto;background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.08);}
.head{background:#141943;padding:30px 34px;text-align:center;}
.head h1{color:#fff;font-size:20px;font-weight:900;margin:0;}
.head p{color:rgba(255,255,255,.55);font-size:12px;margin:5px 0 0;}
.body{padding:30px 34px;}
.amount{text-align:center;background:#F5F6FA;border-radius:12px;padding:18px;margin-bottom:22px;}
.amount span{font-size:34px;font-weight:900;color:#C8402E;}
.amount small{display:block;color:#6B7280;font-size:12px;margin-top:4px;}
table{width:100%;border-collapse:collapse;margin-bottom:18px;}
td{padding:8px 0;border-bottom:1px solid #F3F4F6;font-size:13px;}
td:first-child{color:#6B7280;font-weight:600;}
td:last-child{text-align:right;font-weight:700;}
.cancel-btn{display:block;background:#F3F4F6;color:#374151;text-align:center;padding:12px;border-radius:9999px;font-weight:600;font-size:13px;text-decoration:none;border:1px solid #E5E7EB;margin-bottom:18px;}
.contact{background:#FFF7ED;border:1px solid #FED7AA;border-radius:10px;padding:13px 15px;font-size:12px;color:#92400E;line-height:1.6;}
.contact a{color:#C8402E;font-weight:600;}
.foot{background:#F5F6FA;padding:14px 34px;text-align:center;font-size:11px;color:#9CA3AF;}
</style></head><body>
<div class="wrap">
  <div class="head"><h1>' . esc_html($org) . '</h1><p>Monthly Donation Confirmation</p></div>
  <div class="body">
    <p style="font-size:14px;text-align:center;color:#374151;margin-bottom:18px;">
      Thank you! Your monthly donation is now active. Your card will be charged automatically each month on today\'s date.</p>
    <div class="amount"><span>' . esc_html($amount_fmt) . '/mo</span><small>📅 Monthly — ' . esc_html($fund_label) . '</small></div>
    <table>
      <tr><td>Fund</td><td>' . esc_html($fund_label) . '</td></tr>
      <tr><td>Frequency</td><td>Monthly (auto-renews)</td></tr>
      <tr><td>Receipt email</td><td>' . esc_html($d['email']) . '</td></tr>
    </table>
    <p style="font-size:13px;font-weight:700;text-align:center;margin-bottom:10px;">Want to cancel? No login needed:</p>
    <a href="' . esc_url($cancel_url) . '" class="cancel-btn">✕ Cancel My Monthly Donation</a>
    <div class="contact">
      <strong>Cancel link not working?</strong><br>
      Email us at <a href="mailto:' . esc_attr($admin_email) . '">' . esc_html($admin_email) . '</a>
      with subject <em>"Cancel Subscription"</em> and your email address. We will cancel within 24 hours.
    </div>
    <p style="font-size:11px;color:#9CA3AF;text-align:center;margin-top:16px;">
      ' . esc_html($org) . ' is a 501(c)(3) nonprofit. Your donation is tax-deductible.</p>
  </div>
  <div class="foot">' . esc_html($org) . ' &bull; <a href="' . esc_url(home_url()) . '" style="color:#C8402E;">' . esc_url(home_url()) . '</a></div>
</div></body></html>';

    wp_mail(
        $d['email'],
        'Your Monthly Donation is Active — ' . $org,
        $html,
        ['Content-Type: text/html; charset=UTF-8', 'From: ' . $org . ' <' . $admin_email . '>']
    );
}

// ─────────────────────────────────────────────────────────────────────────────
// 8. PDF RECEIPT VIA DOMPDF
//    Called from stripe-handler.php after a successful webhook event.
//    Requires: vendor/autoload.php (run `composer require dompdf/dompdf`)
//    Falls back gracefully if DOMPDF is not installed.
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Generate a PDF receipt and send it as an email attachment.
 *
 * @param object $donation  Row from wp_myco_donations table (stdClass).
 * @return bool             True on success, false on failure.
 */
function myco_send_donation_receipt(object $donation): bool {
    $autoload = get_template_directory() . '/vendor/autoload.php';
    if (!file_exists($autoload)) {
        error_log('MYCO Receipt: vendor/autoload.php not found. Run composer install to enable PDF receipts.');
        return false;
    }
    require_once $autoload;

    if (empty($donation->donor_email) || !is_email($donation->donor_email)) {
        error_log('MYCO Receipt: No valid donor email for donation ID ' . ($donation->id ?? 'unknown'));
        return false;
    }

    // Ensure receipts directory exists
    $upload_dir  = wp_upload_dir();
    $receipt_dir = trailingslashit($upload_dir['basedir']) . 'myco-receipts';
    if (!wp_mkdir_p($receipt_dir)) {
        error_log('MYCO Receipt: Cannot create directory ' . $receipt_dir);
        return false;
    }

    $token = myco_receipt_token($donation->stripe_session_id);
    $html  = myco_receipt_html($donation, $token);

    // Generate PDF
    try {
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');
        $options->set('chroot', realpath(ABSPATH));

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $pdf_output = $dompdf->output();
    } catch (\Exception $e) {
        error_log('MYCO Receipt DOMPDF error: ' . $e->getMessage());
        return false;
    }

    // Save PDF to uploads
    $filename = 'receipt-' . sanitize_file_name($donation->stripe_session_id) . '.pdf';
    $pdf_path = trailingslashit($receipt_dir) . $filename;
    file_put_contents($pdf_path, $pdf_output);

    // Store path in DB
    global $wpdb;
    $wpdb->update(
        $wpdb->prefix . 'myco_donations',
        ['receipt_path' => $pdf_path],
        ['id' => $donation->id],
        ['%s'],
        ['%d']
    );

    // Build and send email with PDF attachment
    $site_name  = get_bloginfo('name');
    $admin_email = get_option('admin_email');
    $fund_label  = myco_receipt_fund_label($donation->fund ?? 'general');
    $download_url = add_query_arg([
        'myco_receipt' => rawurlencode($donation->stripe_session_id),
        'token'        => $token,
    ], home_url('/'));

    $subject = sprintf('[%s] Donation Receipt – $%s', $site_name, number_format($donation->amount, 2));
    $body    = myco_receipt_email_body($donation, $fund_label, $download_url, $site_name);
    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $site_name . ' <' . $admin_email . '>',
    ];

    $sent = wp_mail($donation->donor_email, $subject, $body, $headers, [$pdf_path]);

    if (!$sent) {
        error_log('MYCO Receipt: wp_mail() failed for ' . $donation->donor_email);
    }

    return $sent;
}

// ─────────────────────────────────────────────────────────────────────────────
// 9. HMAC TOKEN HELPER (used for PDF download URLs)
// ─────────────────────────────────────────────────────────────────────────────

function myco_receipt_token(string $session_id): string {
    $secret = defined('AUTH_KEY') ? AUTH_KEY : get_option('myco_receipt_secret', wp_generate_password(64, true, true));
    return hash_hmac('sha256', $session_id, $secret);
}

// ─────────────────────────────────────────────────────────────────────────────
// 10. FUND LABEL HELPER (shared across all receipt types)
// ─────────────────────────────────────────────────────────────────────────────

function myco_receipt_fund_label(string $fund): string {
    $labels = [
        'general'           => 'General Fund',
        'youth'             => 'Youth Programs',
        'youth-mentorship'  => 'Youth Mentorship Program',
        'athletics'         => 'Athletics & Sports',
        'academic'          => 'Academic Support',
        'scholarships'      => 'Scholarships',
        'leadership'        => 'Leadership Development',
        'community-service' => 'Community Service',
        'facility'          => 'Facility Fund',
        'mcyc'              => 'MCYC Building Fund',
    ];
    return $labels[$fund] ?? ucfirst(str_replace('-', ' ', $fund)) . ' Fund';
}

// ─────────────────────────────────────────────────────────────────────────────
// 11. PDF HTML TEMPLATE (for DOMPDF rendering)
// ─────────────────────────────────────────────────────────────────────────────

function myco_receipt_html(object $donation, string $token): string {
    $site_name      = get_bloginfo('name');
    $site_url       = home_url('/');
    $date           = date('F j, Y', strtotime($donation->created_at));
    $amount         = number_format($donation->amount, 2);
    $receipt_no     = 'MYCO-' . strtoupper(substr($donation->stripe_session_id, -8));
    $fund_label     = myco_receipt_fund_label($donation->fund ?? 'general');
    $type_label     = ($donation->donation_type === 'monthly') ? 'Monthly' : 'One-Time';
    $payment_intent = $donation->stripe_payment_intent ?: '—';

    $brand_short = 'MYCO';
    $brand_full  = 'Muslim Youth of Central Ohio';
    $logo_src    = myco_receipt_logo_data_uri('color');

    ob_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 13px; color: #1a1a2e; background: #fff; }
  .page { padding: 48px 56px; }
  .header { display: flex; justify-content: space-between; align-items: flex-start; gap: 18px; padding-bottom: 24px; border-bottom: 3px solid #F0A020; margin-bottom: 32px; }
  .org-brand { display: flex; align-items: center; gap: 16px; min-width: 0; }
  .org-logo { width: 72px; height: 72px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border-radius: 18px; padding: 8px; background: #f8fafc; border: 1px solid #e5e7eb; overflow: hidden; }
  .org-logo img { display: block; width: 100%; height: 100%; object-fit: contain; transform: scale(1.06); }
  .org-copy { min-width: 0; }
  .org-name { font-size: 24px; font-weight: 800; color: #141943; letter-spacing: -0.01em; line-height: 1; }
  .org-tagline { font-size: 11px; color: #6b7280; margin-top: 5px; line-height: 1.4; }
  .receipt-label { text-align: right; }
  .receipt-label h1 { font-size: 18px; font-weight: 700; color: #141943; }
  .receipt-label p { font-size: 11px; color: #6b7280; margin-top: 3px; }
  .banner { background: #141943; color: #fff; padding: 18px 24px; border-radius: 8px; margin-bottom: 28px; }
  .banner h2 { font-size: 16px; font-weight: 700; }
  .banner p { font-size: 12px; color: #d1fae5; margin-top: 4px; }
  .details-title { font-size: 11px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #F0A020; margin-bottom: 10px; }
  .details-table { width: 100%; border-collapse: collapse; margin-bottom: 28px; }
  .details-table tr { border-bottom: 1px solid #f3f4f6; }
  .details-table tr:last-child { border-bottom: none; }
  .details-table td { padding: 9px 4px; font-size: 13px; }
  .details-table td:first-child { color: #6b7280; width: 40%; }
  .details-table td:last-child { font-weight: 600; color: #141943; }
  .amount-box { background: #f9fafb; border: 2px solid #F0A020; border-radius: 8px; padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; }
  .amount-label { font-size: 12px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; }
  .amount-value { font-size: 28px; font-weight: 800; color: #141943; }
  .tax-notice { background: #fffbeb; border: 1px solid #fcd34d; border-radius: 6px; padding: 14px 18px; margin-bottom: 28px; font-size: 11.5px; color: #92400e; line-height: 1.6; }
  .tax-notice strong { color: #78350f; }
  .footer { border-top: 1px solid #e5e7eb; padding-top: 20px; text-align: center; font-size: 11px; color: #9ca3af; line-height: 1.8; }
  .footer a { color: #141943; text-decoration: none; }
</style>
</head>
<body>
<div class="page">

  <div class="header">
    <div class="org-brand">
      <?php if ($logo_src) : ?>
      <div class="org-logo">
        <img src="<?php echo esc_attr($logo_src); ?>" alt="MYCO logo">
      </div>
      <?php endif; ?>
      <div class="org-copy">
        <div class="org-name"><?php echo esc_html($brand_short); ?></div>
        <div class="org-tagline"><?php echo esc_html($brand_full); ?></div>
      </div>
    </div>
    <div class="receipt-label">
      <h1>Donation Receipt</h1>
      <p>Receipt #<?php echo esc_html($receipt_no); ?></p>
      <p><?php echo esc_html($date); ?></p>
    </div>
  </div>

  <div class="banner">
    <h2>Thank you, <?php echo esc_html($donation->donor_name ?: 'valued donor'); ?>!</h2>
    <p>Your generous contribution helps us build stronger, more connected communities.</p>
  </div>

  <div class="amount-box">
    <div>
      <div class="amount-label"><?php echo esc_html($type_label); ?> Donation</div>
      <div style="font-size:12px;color:#6b7280;margin-top:2px;"><?php echo esc_html($fund_label); ?></div>
    </div>
    <div class="amount-value">$<?php echo esc_html($amount); ?></div>
  </div>

  <div class="details-title">Donation Details</div>
  <table class="details-table">
    <tr><td>Donor Name</td><td><?php echo esc_html($donation->donor_name ?: '—'); ?></td></tr>
    <tr><td>Donor Email</td><td><?php echo esc_html($donation->donor_email); ?></td></tr>
    <tr><td>Donation Fund</td><td><?php echo esc_html($fund_label); ?></td></tr>
    <tr><td>Donation Type</td><td><?php echo esc_html($type_label); ?></td></tr>
    <tr><td>Payment Reference</td><td><?php echo esc_html(substr($payment_intent, 0, 32)); ?></td></tr>
    <tr><td>Transaction Date</td><td><?php echo esc_html($date); ?></td></tr>
    <tr><td>Status</td><td style="color:#16a34a;font-weight:700;">Completed ✓</td></tr>
  </table>

  <div class="tax-notice">
    <strong>Tax Deductibility Notice:</strong> <?php echo esc_html($site_name); ?> is a registered 501(c)(3) nonprofit organization.
    No goods or services were provided in exchange for this donation. This receipt may be used for tax purposes.
    Please retain this document for your records. EIN: <strong><?php echo esc_html(get_option('myco_ein', '[EIN NUMBER]')); ?></strong>
  </div>

  <div class="footer">
    <?php echo esc_html($site_name); ?> &bull; <a href="<?php echo esc_url($site_url); ?>"><?php echo esc_url($site_url); ?></a>
    &bull; <?php echo esc_html(get_option('admin_email')); ?>
  </div>

</div>
</body>
</html>
<?php
    return ob_get_clean();
}

// ─────────────────────────────────────────────────────────────────────────────
// 12. PDF EMAIL BODY TEMPLATE (for DOMPDF email attachment)
// ─────────────────────────────────────────────────────────────────────────────

function myco_receipt_email_body(object $donation, string $fund_label, string $download_url, string $site_name): string {
    $amount = number_format($donation->amount, 2);
    $date   = date('F j, Y', strtotime($donation->created_at));
    $name   = $donation->donor_name ?: 'Valued Donor';

    ob_start(); ?>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><style>
body{margin:0;padding:0;background:#f3f4f6;font-family:Arial,sans-serif;}
.wrapper{max-width:600px;margin:32px auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.08);}
.top-bar{background:#141943;padding:28px 36px;}
.top-bar h1{color:#fff;font-size:20px;margin:0 0 4px;}
.top-bar p{color:rgba(255,255,255,.65);font-size:13px;margin:0;}
.body{padding:32px 36px;}
.amount-row{background:#f0fdf4;border-left:4px solid #F0A020;padding:16px 20px;border-radius:6px;margin:20px 0;display:flex;justify-content:space-between;align-items:center;}
.amount-row .lbl{font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase;}
.amount-row .val{font-size:24px;font-weight:800;color:#141943;}
table.dt{width:100%;border-collapse:collapse;margin:20px 0;}
table.dt tr{border-bottom:1px solid #f3f4f6;}
table.dt td{padding:9px 4px;font-size:13px;color:#374151;}
table.dt td:first-child{color:#9ca3af;width:45%;}
.btn{display:inline-block;background:#C8402E;color:#fff;text-decoration:none;padding:12px 28px;border-radius:50px;font-size:14px;font-weight:700;margin:20px 0;}
.tax{background:#fffbeb;border:1px solid #fcd34d;border-radius:6px;padding:14px;font-size:12px;color:#92400e;margin-top:20px;line-height:1.7;}
.footer{background:#f9fafb;padding:20px 36px;text-align:center;font-size:11px;color:#9ca3af;border-top:1px solid #e5e7eb;}
</style></head>
<body>
<div class="wrapper">
  <div class="top-bar">
    <h1>Thank you, <?php echo esc_html($name); ?>!</h1>
    <p>Your donation to <?php echo esc_html($site_name); ?> has been received.</p>
  </div>
  <div class="body">
    <p style="font-size:15px;color:#374151;margin-bottom:20px;">
      We're grateful for your generosity. Here is a summary of your donation:
    </p>
    <div class="amount-row">
      <div><div class="lbl"><?php echo esc_html($fund_label); ?></div><div style="font-size:12px;color:#6b7280;margin-top:2px;"><?php echo esc_html($date); ?></div></div>
      <div class="val">$<?php echo esc_html($amount); ?></div>
    </div>
    <table class="dt">
      <tr><td>Donor Name</td><td><strong><?php echo esc_html($donation->donor_name ?: '—'); ?></strong></td></tr>
      <tr><td>Donation Type</td><td><?php echo esc_html(($donation->donation_type === 'monthly') ? 'Monthly Recurring' : 'One-Time'); ?></td></tr>
      <tr><td>Transaction Date</td><td><?php echo esc_html($date); ?></td></tr>
      <tr><td>Status</td><td style="color:#16a34a;font-weight:700;">Completed ✓</td></tr>
    </table>
    <p style="font-size:13px;color:#6b7280;margin-bottom:8px;">A PDF copy of your receipt is attached. You can also download it directly:</p>
    <a href="<?php echo esc_url($download_url); ?>" class="btn">Download PDF Receipt</a>
    <div class="tax">
      <strong>Tax Deductibility:</strong> <?php echo esc_html($site_name); ?> is a registered 501(c)(3) nonprofit.
      No goods or services were provided in exchange for this donation. Please retain this receipt for tax purposes.
      EIN: <strong><?php echo esc_html(get_option('myco_ein', '[EIN NUMBER]')); ?></strong>
    </div>
  </div>
  <div class="footer">
    <?php echo esc_html($site_name); ?> &bull;
    Questions? <a href="mailto:<?php echo esc_attr(get_option('admin_email')); ?>" style="color:#141943;"><?php echo esc_html(get_option('admin_email')); ?></a>
  </div>
</div>
</body>
</html>
<?php
    return ob_get_clean();
}

// ─────────────────────────────────────────────────────────────────────────────
// 13. ADMIN AJAX: RESEND RECEIPT
// ─────────────────────────────────────────────────────────────────────────────

add_action('wp_ajax_myco_resend_receipt', 'myco_ajax_resend_receipt');

function myco_ajax_resend_receipt(): void {
    check_ajax_referer('myco_admin_nonce', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Permission denied.']);
    }

    $donation_id = intval($_POST['donation_id'] ?? 0);
    if ($donation_id < 1) {
        wp_send_json_error(['message' => 'Invalid donation ID.']);
    }

    global $wpdb;
    $donation = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}myco_donations WHERE id = %d LIMIT 1",
        $donation_id
    ));

    if (!$donation) {
        wp_send_json_error(['message' => 'Donation not found.']);
    }

    $result = myco_send_donation_receipt($donation);

    if ($result) {
        wp_send_json_success(['message' => 'Receipt sent to ' . $donation->donor_email]);
    } else {
        wp_send_json_error(['message' => 'Failed to send receipt. Check error log.']);
    }
}

// ─────────────────────────────────────────────────────────────────────────────
// 14. DB MIGRATION: ADD receipt_path COLUMN
// ─────────────────────────────────────────────────────────────────────────────

add_action('after_switch_theme', 'myco_add_receipt_path_column');

function myco_add_receipt_path_column(): void {
    global $wpdb;
    $table = $wpdb->prefix . 'myco_donations';
    $col   = $wpdb->get_results("SHOW COLUMNS FROM {$table} LIKE 'receipt_path'");
    if (empty($col)) {
        $wpdb->query("ALTER TABLE {$table} ADD COLUMN receipt_path varchar(500) DEFAULT '' AFTER status");
    }
}

