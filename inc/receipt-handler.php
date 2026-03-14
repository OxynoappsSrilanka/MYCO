<?php
/**
 * Donation Receipt Handler
 * Generates PDF receipts and sends them via email after a successful Stripe donation.
 * Also provides a secure download endpoint and admin resend functionality.
 *
 * @package MYCO
 */

if (!defined('ABSPATH')) {
    exit;
}

// ─────────────────────────────────────────────────────────────────────────────
// 1. MAIN HOOK — called from stripe-handler.php after webhook update
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Generate PDF receipt and send email for a completed donation.
 *
 * @param object $donation  Row from wp_myco_donations table (stdClass).
 * @return bool             True on success, false on failure.
 */
function myco_send_donation_receipt(object $donation): bool {
    // Require DOMPDF
    $autoload = get_template_directory() . '/vendor/autoload.php';
    if (!file_exists($autoload)) {
        error_log('MYCO Receipt: vendor/autoload.php not found. Run composer install.');
        return false;
    }
    require_once $autoload;

    if (empty($donation->donor_email) || !is_email($donation->donor_email)) {
        error_log('MYCO Receipt: No valid donor email for donation ID ' . $donation->id);
        return false;
    }

    // Build receipt dir
    $upload_dir = wp_upload_dir();
    $receipt_dir = trailingslashit($upload_dir['basedir']) . 'myco-receipts';
    if (!wp_mkdir_p($receipt_dir)) {
        error_log('MYCO Receipt: Cannot create directory ' . $receipt_dir);
        return false;
    }

    // Secure download token
    $token = myco_receipt_token($donation->stripe_session_id);

    // Generate HTML
    $html = myco_receipt_html($donation, $token);

    // Build PDF using DOMPDF
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
    $filename  = 'receipt-' . sanitize_file_name($donation->stripe_session_id) . '.pdf';
    $pdf_path  = trailingslashit($receipt_dir) . $filename;
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

    // Compose and send email
    $site_name  = get_bloginfo('name');
    $admin_email = get_option('admin_email');
    $subject    = sprintf('[%s] Donation Receipt – $%s', $site_name, number_format($donation->amount, 2));

    $fund_labels = [
        'general'      => 'General Fund',
        'youth'        => 'Youth Programs',
        'athletics'    => 'Athletics',
        'scholarships' => 'Scholarships',
        'mcyc'         => 'MCYC Building Fund',
    ];
    $fund_label = $fund_labels[$donation->fund] ?? ucfirst($donation->fund) . ' Fund';

    $download_url = add_query_arg([
        'myco_receipt' => rawurlencode($donation->stripe_session_id),
        'token'        => $token,
    ], home_url('/'));

    $body  = myco_receipt_email_body($donation, $fund_label, $download_url, $site_name);

    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $site_name . ' <' . $admin_email . '>',
    ];

    $sent = wp_mail(
        $donation->donor_email,
        $subject,
        $body,
        $headers,
        [$pdf_path]  // PDF attachment
    );

    if (!$sent) {
        error_log('MYCO Receipt: wp_mail() failed for ' . $donation->donor_email);
    }

    return $sent;
}

// ─────────────────────────────────────────────────────────────────────────────
// 2. SECURE DOWNLOAD ENDPOINT
//    URL: /?myco_receipt=STRIPE_SESSION_ID&token=HASH
// ─────────────────────────────────────────────────────────────────────────────

add_action('init', 'myco_handle_receipt_download');

function myco_handle_receipt_download(): void {
    if (empty($_GET['myco_receipt'])) {
        return;
    }

    $session_id = sanitize_text_field(rawurldecode($_GET['myco_receipt']));
    $token      = sanitize_text_field($_GET['token'] ?? '');

    // Constant-time comparison
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

    // Regenerate PDF on the fly if file was deleted
    if (empty($donation->receipt_path) || !file_exists($donation->receipt_path)) {
        myco_send_donation_receipt($donation);
        // Re-fetch updated path
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

// ─────────────────────────────────────────────────────────────────────────────
// 3. ADMIN AJAX — Resend receipt
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
// 4. DB — Add receipt_path column if not exists
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

// ─────────────────────────────────────────────────────────────────────────────
// 5. HELPERS
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Generate a secure, reproducible token for a receipt download link.
 */
function myco_receipt_token(string $session_id): string {
    $secret = defined('AUTH_KEY') ? AUTH_KEY : get_option('myco_receipt_secret', wp_generate_password(64, true, true));
    return hash_hmac('sha256', $session_id, $secret);
}

/**
 * Build the styled HTML for the PDF receipt.
 */
function myco_receipt_html(object $donation, string $token): string {
    $site_name  = get_bloginfo('name');
    $site_url   = home_url('/');
    $date       = date('F j, Y', strtotime($donation->created_at));
    $amount     = number_format($donation->amount, 2);
    $receipt_no = 'MYCO-' . strtoupper(substr($donation->stripe_session_id, -8));

    $fund_labels = [
        'general'      => 'General Fund',
        'youth'        => 'Youth Programs',
        'athletics'    => 'Athletics',
        'scholarships' => 'Scholarships',
        'mcyc'         => 'MCYC Building Fund',
    ];
    $fund_label    = $fund_labels[$donation->fund] ?? ucfirst($donation->fund) . ' Fund';
    $type_label    = ($donation->donation_type === 'monthly') ? 'Monthly' : 'One-Time';
    $payment_intent = $donation->stripe_payment_intent ?: '—';

    ob_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 13px; color: #1a1a2e; background: #fff; }
  .page { padding: 48px 56px; }

  /* Header */
  .header { display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 24px; border-bottom: 3px solid #F0A020; margin-bottom: 32px; }
  .org-name { font-size: 22px; font-weight: 700; color: #141943; letter-spacing: -0.01em; }
  .org-tagline { font-size: 11px; color: #6b7280; margin-top: 2px; }
  .receipt-label { text-align: right; }
  .receipt-label h1 { font-size: 18px; font-weight: 700; color: #1A4A48; }
  .receipt-label p { font-size: 11px; color: #6b7280; margin-top: 3px; }

  /* Thank you banner */
  .banner { background: #1A4A48; color: #fff; padding: 18px 24px; border-radius: 8px; margin-bottom: 28px; }
  .banner h2 { font-size: 16px; font-weight: 700; }
  .banner p { font-size: 12px; color: #d1fae5; margin-top: 4px; }

  /* Details table */
  .details-title { font-size: 11px; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: #F0A020; margin-bottom: 10px; }
  .details-table { width: 100%; border-collapse: collapse; margin-bottom: 28px; }
  .details-table tr { border-bottom: 1px solid #f3f4f6; }
  .details-table tr:last-child { border-bottom: none; }
  .details-table td { padding: 9px 4px; font-size: 13px; }
  .details-table td:first-child { color: #6b7280; width: 40%; }
  .details-table td:last-child { font-weight: 600; color: #141943; }

  /* Amount box */
  .amount-box { background: #f9fafb; border: 2px solid #F0A020; border-radius: 8px; padding: 20px 24px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; }
  .amount-label { font-size: 12px; color: #6b7280; font-weight: 600; text-transform: uppercase; letter-spacing: 0.06em; }
  .amount-value { font-size: 28px; font-weight: 800; color: #1A4A48; }

  /* Tax notice */
  .tax-notice { background: #fffbeb; border: 1px solid #fcd34d; border-radius: 6px; padding: 14px 18px; margin-bottom: 28px; font-size: 11.5px; color: #92400e; line-height: 1.6; }
  .tax-notice strong { color: #78350f; }

  /* Footer */
  .footer { border-top: 1px solid #e5e7eb; padding-top: 20px; text-align: center; font-size: 11px; color: #9ca3af; line-height: 1.8; }
  .footer a { color: #1A4A48; text-decoration: none; }
</style>
</head>
<body>
<div class="page">

  <!-- Header -->
  <div class="header">
    <div>
      <div class="org-name"><?php echo esc_html($site_name); ?></div>
      <div class="org-tagline">Muslim Youth Community Organization</div>
    </div>
    <div class="receipt-label">
      <h1>Donation Receipt</h1>
      <p>Receipt #<?php echo esc_html($receipt_no); ?></p>
      <p><?php echo esc_html($date); ?></p>
    </div>
  </div>

  <!-- Thank you banner -->
  <div class="banner">
    <h2>Thank you, <?php echo esc_html($donation->donor_name ?: 'valued donor'); ?>!</h2>
    <p>Your generous contribution helps us build stronger, more connected communities.</p>
  </div>

  <!-- Amount -->
  <div class="amount-box">
    <div>
      <div class="amount-label"><?php echo esc_html($type_label); ?> Donation</div>
      <div style="font-size:12px;color:#6b7280;margin-top:2px;"><?php echo esc_html($fund_label); ?></div>
    </div>
    <div class="amount-value">$<?php echo esc_html($amount); ?></div>
  </div>

  <!-- Details -->
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

  <!-- Tax notice -->
  <div class="tax-notice">
    <strong>Tax Deductibility Notice:</strong> <?php echo esc_html($site_name); ?> is a registered 501(c)(3) nonprofit organization.
    No goods or services were provided in exchange for this donation. This receipt may be used for tax purposes.
    Please retain this document for your records. EIN: <strong><?php echo esc_html(get_option('myco_ein', '[EIN NUMBER]')); ?></strong>
  </div>

  <!-- Footer -->
  <div class="footer">
    <?php echo esc_html($site_name); ?> &bull; <a href="<?php echo esc_url($site_url); ?>"><?php echo esc_url($site_url); ?></a>
    &bull; <?php echo esc_html(get_option('admin_email')); ?><br>
    Questions? Contact us at <a href="mailto:<?php echo esc_attr(get_option('admin_email')); ?>"><?php echo esc_html(get_option('admin_email')); ?></a>
  </div>

</div>
</body>
</html>
<?php
    return ob_get_clean();
}

/**
 * Build the HTML email body sent to the donor.
 */
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
.top-bar{background:#1A4A48;padding:28px 36px;}
.top-bar h1{color:#fff;font-size:20px;margin:0 0 4px;}
.top-bar p{color:#d1fae5;font-size:13px;margin:0;}
.body{padding:32px 36px;}
.amount-row{background:#f0fdf4;border-left:4px solid #F0A020;padding:16px 20px;border-radius:6px;margin:20px 0;display:flex;justify-content:space-between;align-items:center;}
.amount-row .lbl{font-size:12px;color:#6b7280;font-weight:600;text-transform:uppercase;}
.amount-row .val{font-size:24px;font-weight:800;color:#1A4A48;}
table.dt{width:100%;border-collapse:collapse;margin:20px 0;}
table.dt tr{border-bottom:1px solid #f3f4f6;}
table.dt td{padding:9px 4px;font-size:13px;color:#374151;}
table.dt td:first-child{color:#9ca3af;width:45%;}
.btn{display:inline-block;background:#F0A020;color:#fff;text-decoration:none;padding:12px 28px;border-radius:50px;font-size:14px;font-weight:700;margin:20px 0;}
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

    <p style="font-size:13px;color:#6b7280;margin-bottom:8px;">A PDF copy of your receipt is attached to this email. You can also download it directly:</p>
    <a href="<?php echo esc_url($download_url); ?>" class="btn">Download PDF Receipt</a>

    <div class="tax">
      <strong>Tax Deductibility:</strong> <?php echo esc_html($site_name); ?> is a registered 501(c)(3) nonprofit.
      No goods or services were provided in exchange for this donation. Please retain this receipt for tax purposes.
      EIN: <strong><?php echo esc_html(get_option('myco_ein', '[EIN NUMBER]')); ?></strong>
    </div>
  </div>
  <div class="footer">
    <?php echo esc_html($site_name); ?> &bull;
    Questions? <a href="mailto:<?php echo esc_attr(get_option('admin_email')); ?>" style="color:#1A4A48;"><?php echo esc_html(get_option('admin_email')); ?></a>
  </div>
</div>
</body>
</html>
<?php
    return ob_get_clean();
}
