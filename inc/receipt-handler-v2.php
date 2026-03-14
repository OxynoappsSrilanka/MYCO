<?php
/**
 * MYCO Receipt Handler v2
 * - Verifies PaymentIntent via Stripe API
 * - Generates receipt (stored in WP options)
 * - Sends HTML email via wp_mail()
 * - Serves downloadable HTML receipt page
 *
 * @package MYCO
 */

if (!defined('ABSPATH')) {
    exit;
}

// ─────────────────────────────────────────────────────────────────────────────
// Register query var for receipt download
// ─────────────────────────────────────────────────────────────────────────────
add_filter('query_vars', function ($vars) {
    $vars[] = 'myco_receipt';
    return $vars;
});

// Intercept receipt download requests early
add_action('template_redirect', 'myco_maybe_serve_receipt');

function myco_maybe_serve_receipt(): void {
    $token = get_query_var('myco_receipt');
    if (empty($token)) return;

    $token = sanitize_text_field($token);
    $data  = get_option('myco_receipt_' . $token);

    if (!$data) {
        wp_die('Receipt not found or expired.', 'Receipt Not Found', ['response' => 404]);
    }

    myco_render_receipt_page($data);
    exit;
}

// ─────────────────────────────────────────────────────────────────────────────
// Main entry: process a successful PaymentIntent
// Called from template-donate.php on ?donation=success
// Returns receipt_token (string) or '' on failure / already processed
// ─────────────────────────────────────────────────────────────────────────────
function myco_process_successful_payment(string $payment_intent_id): string {
    if (empty($payment_intent_id)) return '';

    // Prevent duplicate processing
    $existing_token = get_option('myco_pi_token_' . $payment_intent_id);
    if ($existing_token) return $existing_token;

    // ── Fetch PaymentIntent from Stripe ──
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

    // ── Build receipt data ──
    $amount    = ($pi['amount'] ?? 0) / 100;  // cents → dollars
    $currency  = strtoupper($pi['currency'] ?? 'USD');
    $email     = $pi['receipt_email'] ?? ($pi['metadata']['donor_email'] ?? '');
    $fund      = $pi['metadata']['fund'] ?? 'general';
    $don_type  = $pi['metadata']['donation_type'] ?? 'one-time';
    $ein       = get_option('myco_ein', '');

    $fund_labels = [
        'general'           => 'General Fund',
        'youth-mentorship'  => 'Youth Mentorship Program',
        'athletics'         => 'Athletics & Sports',
        'academic'          => 'Academic Support',
        'leadership'        => 'Leadership Development',
        'community-service' => 'Community Service',
        'facility'          => 'Facility Fund',
    ];
    $fund_label = $fund_labels[$fund] ?? ucfirst(str_replace('-', ' ', $fund)) . ' Fund';

    $token = bin2hex(random_bytes(16));
    $date  = current_time('F j, Y');
    $time  = current_time('g:i A T');
    $org   = get_bloginfo('name');
    $site  = home_url();

    $receipt = [
        'token'         => $token,
        'payment_intent'=> $payment_intent_id,
        'amount'        => $amount,
        'currency'      => $currency,
        'fund'          => $fund,
        'fund_label'    => $fund_label,
        'donation_type' => $don_type,
        'email'         => $email,
        'ein'           => $ein,
        'org'           => $org,
        'site'          => $site,
        'date'          => $date,
        'time'          => $time,
        'receipt_no'    => strtoupper(substr($token, 0, 8)),
    ];

    // Store receipt (90 day expiry)
    update_option('myco_receipt_' . $token, $receipt, false);
    // Map pi → token (for dedup)
    update_option('myco_pi_token_' . $payment_intent_id, $token, false);

    // ── Update DB donation record ──
    global $wpdb;
    $wpdb->update(
        $wpdb->prefix . 'myco_donations',
        ['status' => 'complete', 'stripe_payment_intent' => $payment_intent_id, 'donor_email' => $email],
        ['stripe_session_id' => $payment_intent_id],
        ['%s', '%s', '%s'],
        ['%s']
    );

    // ── Send email receipt (if email provided) ──
    if (!empty($email) && is_email($email)) {
        myco_send_receipt_email($receipt);
    }

    return $token;
}

// ─────────────────────────────────────────────────────────────────────────────
// Send HTML email receipt
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
    <a href="' . esc_url($receipt_url) . '" class="btn">⬇ Download Receipt as PDF</a>
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

// ─────────────────────────────────────────────────────────────────────────────
// Render printable receipt page (served at ?myco_receipt=TOKEN)
// ─────────────────────────────────────────────────────────────────────────────
function myco_render_receipt_page(array $r): void {
    $amount_fmt = '$' . number_format($r['amount'], 2) . ' ' . $r['currency'];
    $org        = esc_html($r['org']);
    $logo_url   = esc_url(get_template_directory_uri() . '/assets/img/logo.png');
    $site_url   = esc_url(home_url());
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
  .header{background:#141943;padding:40px 48px;display:flex;justify-content:space-between;align-items:center;}
  .header .logo{font-size:28px;font-weight:900;color:#fff;letter-spacing:-.02em;}
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
    <div>
      <div class="logo"><?php echo $org; ?></div>
      <div class="receipt-badge">Official Donation Receipt</div>
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
      <tr>
        <td>Organization</td>
        <td><?php echo $org; ?></td>
      </tr>
      <tr>
        <td>Fund</td>
        <td><?php echo esc_html($r['fund_label']); ?></td>
      </tr>
      <tr>
        <td>Date</td>
        <td><?php echo esc_html($r['date'] . ' at ' . $r['time']); ?></td>
      </tr>
      <tr>
        <td>Payment Method</td>
        <td>Credit / Debit Card (Stripe)</td>
      </tr>
      <tr>
        <td>Transaction ID</td>
        <td style="font-size:12px;word-break:break-all;"><?php echo esc_html($r['payment_intent']); ?></td>
      </tr>
      <?php if ($r['ein']) : ?>
      <tr>
        <td>EIN / Tax ID</td>
        <td><?php echo esc_html($r['ein']); ?></td>
      </tr>
      <?php endif; ?>
      <?php if (!empty($r['email'])) : ?>
      <tr>
        <td>Donor Email</td>
        <td><?php echo esc_html($r['email']); ?></td>
      </tr>
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
  // Auto-prompt print if ?print=1 is in URL
  if (new URLSearchParams(location.search).get('print') === '1') {
    setTimeout(function(){ window.print(); }, 600);
  }
</script>
</body>
</html><?php
}

// ─────────────────────────────────────────────────────────────────────────────
// Register ?cancel_sub query var + cancel page
// ─────────────────────────────────────────────────────────────────────────────
add_filter('query_vars', function ($vars) { $vars[] = 'cancel_sub'; return $vars; }, 20);
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
    $fund_labels = [
        'general'=>'General Fund','youth-mentorship'=>'Youth Mentorship Program',
        'athletics'=>'Athletics & Sports','academic'=>'Academic Support',
        'leadership'=>'Leadership Development','community-service'=>'Community Service',
        'facility'=>'Facility Fund',
    ];
    $fund_label = $fund_labels[$data['fund'] ?? 'general'] ?? 'General Fund';
    $amount_fmt = '$' . number_format(floatval($data['amount'] ?? 0), 2);

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
// Monthly subscription welcome + cancel-link email
// ─────────────────────────────────────────────────────────────────────────────
function myco_send_subscription_confirmation(array $d): void {
    if (empty($d['email']) || !is_email($d['email'])) return;

    $org         = get_bloginfo('name');
    $admin_email = get_option('admin_email');
    $cancel_url  = home_url('/?cancel_sub=' . ($d['cancel_token'] ?? ''));
    $amount_fmt  = '$' . number_format(floatval($d['amount']), 2);
    $fund_labels = [
        'general'=>'General Fund','youth-mentorship'=>'Youth Mentorship Program',
        'athletics'=>'Athletics & Sports','academic'=>'Academic Support',
        'leadership'=>'Leadership Development','community-service'=>'Community Service',
        'facility'=>'Facility Fund',
    ];
    $fund_label = $fund_labels[$d['fund'] ?? 'general'] ?? 'General Fund';

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
