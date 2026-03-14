<?php
/**
 * AJAX Handlers
 * - Newsletter signup + Admin subscriber view with CSV export
 * - Contact form submission with admin notification + auto-reply
 *
 * @package MYCO
 */

if (!defined('ABSPATH')) {
    exit;
}

// ─────────────────────────────────────────────────────────────────────────────
// 1. NEWSLETTER SIGNUP
// ─────────────────────────────────────────────────────────────────────────────

add_action('wp_ajax_myco_newsletter_signup', 'myco_newsletter_signup');
add_action('wp_ajax_nopriv_myco_newsletter_signup', 'myco_newsletter_signup');

function myco_newsletter_signup(): void {
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce'] ?? ''), 'myco_newsletter_nonce')) {
        wp_send_json_error(['message' => 'Security check failed.']);
    }

    $email = sanitize_email($_POST['email'] ?? '');

    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Please enter a valid email address.']);
    }

    // Rate limiting: max 3 attempts per IP per hour
    $ip        = sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? '');
    $rate_key  = 'myco_nl_rate_' . md5($ip);
    $attempts  = (int) get_transient($rate_key);
    if ($attempts >= 3) {
        wp_send_json_error(['message' => 'Too many attempts. Please try again later.']);
    }
    set_transient($rate_key, $attempts + 1, HOUR_IN_SECONDS);

    // Store subscriber with timestamp and source
    $subscribers = get_option('myco_newsletter_subscribers', []);
    $found = false;
    foreach ($subscribers as $sub) {
        if (is_array($sub) && ($sub['email'] ?? '') === $email) {
            $found = true;
            break;
        }
        // Legacy flat string support
        if ($sub === $email) {
            $found = true;
            break;
        }
    }

    if (!$found) {
        $subscribers[] = [
            'email'      => $email,
            'subscribed' => current_time('mysql'),
            'source'     => sanitize_text_field($_POST['source'] ?? 'footer'),
            'ip'         => md5($ip), // Store hashed IP for GDPR compliance
        ];
        update_option('myco_newsletter_subscribers', $subscribers);

        // Admin notification
        $site_name = get_bloginfo('name');
        wp_mail(
            get_option('admin_email'),
            '[' . $site_name . '] New Newsletter Subscriber',
            "New subscriber: {$email}\nSubscribed: " . current_time('mysql') . "\n\nView all subscribers in WP Admin → MYCO → Newsletter Subscribers."
        );
    }

    wp_send_json_success(['message' => 'Thank you for subscribing!']);
}

// ─────────────────────────────────────────────────────────────────────────────
// 3. ADMIN: NEWSLETTER SUBSCRIBERS PAGE
// ─────────────────────────────────────────────────────────────────────────────

add_action('admin_menu', 'myco_newsletter_admin_menu');

function myco_newsletter_admin_menu(): void {
    add_submenu_page(
        'myco-settings',
        __('Newsletter Subscribers', 'myco'),
        __('Newsletter', 'myco'),
        'manage_options',
        'myco-newsletter',
        'myco_newsletter_admin_page'
    );
}

function myco_newsletter_admin_page(): void {
    // Handle CSV export
    if (isset($_GET['export']) && $_GET['export'] === 'csv' && current_user_can('manage_options')) {
        check_admin_referer('myco_export_newsletter');
        myco_export_newsletter_csv();
        exit;
    }

    // Handle single delete
    if (isset($_POST['delete_email']) && current_user_can('manage_options')) {
        check_admin_referer('myco_delete_subscriber');
        $del_email = sanitize_email($_POST['delete_email']);
        $subscribers = get_option('myco_newsletter_subscribers', []);
        $subscribers = array_values(array_filter($subscribers, function ($sub) use ($del_email) {
            $e = is_array($sub) ? ($sub['email'] ?? '') : $sub;
            return $e !== $del_email;
        }));
        update_option('myco_newsletter_subscribers', $subscribers);
        echo '<div class="notice notice-success"><p>Subscriber removed.</p></div>';
    }

    $subscribers = get_option('myco_newsletter_subscribers', []);
    $total = count($subscribers);
    $export_url = wp_nonce_url(add_query_arg(['export' => 'csv'], admin_url('admin.php?page=myco-newsletter')), 'myco_export_newsletter');
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Newsletter Subscribers', 'myco'); ?>
            <a href="<?php echo esc_url($export_url); ?>" class="page-title-action">Export CSV</a>
        </h1>
        <p><?php echo esc_html($total); ?> subscriber<?php echo $total !== 1 ? 's' : ''; ?> total.</p>

        <table class="wp-list-table widefat fixed striped" style="max-width:900px;">
            <thead>
                <tr>
                    <th style="width:40%">Email</th>
                    <th>Source</th>
                    <th>Subscribed Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($subscribers)) : ?>
                <tr><td colspan="4">No subscribers yet.</td></tr>
                <?php else : ?>
                    <?php foreach (array_reverse($subscribers) as $sub) :
                        $email = is_array($sub) ? ($sub['email'] ?? '') : $sub;
                        $source = is_array($sub) ? ($sub['source'] ?? '—') : '—';
                        $date   = is_array($sub) ? ($sub['subscribed'] ?? '—') : '—';
                    ?>
                    <tr>
                        <td><?php echo esc_html($email); ?></td>
                        <td><?php echo esc_html(ucfirst($source)); ?></td>
                        <td><?php echo esc_html($date); ?></td>
                        <td>
                            <form method="post" style="display:inline;" onsubmit="return confirm('Remove this subscriber?');">
                                <?php wp_nonce_field('myco_delete_subscriber'); ?>
                                <input type="hidden" name="delete_email" value="<?php echo esc_attr($email); ?>">
                                <button type="submit" class="button button-small button-link-delete">Remove</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}

function myco_export_newsletter_csv(): void {
    $subscribers = get_option('myco_newsletter_subscribers', []);
    $filename = 'myco-newsletter-subscribers-' . date('Y-m-d') . '.csv';

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Email', 'Source', 'Subscribed Date']);

    foreach ($subscribers as $sub) {
        $email  = is_array($sub) ? ($sub['email'] ?? '') : $sub;
        $source = is_array($sub) ? ($sub['source'] ?? '') : '';
        $date   = is_array($sub) ? ($sub['subscribed'] ?? '') : '';
        fputcsv($output, [$email, $source, $date]);
    }

    fclose($output);
}

// ─────────────────────────────────────────────────────────────────────────────
// 5. ENQUEUE admin JS for "Resend Receipt" button (loaded on donation history page)
// ─────────────────────────────────────────────────────────────────────────────

add_action('admin_enqueue_scripts', 'myco_admin_receipt_scripts');

function myco_admin_receipt_scripts(string $hook): void {
    if (strpos($hook, 'myco-donation-history') === false) {
        return;
    }
    wp_add_inline_script('jquery', "
    jQuery(function($) {
        $(document).on('click', '.myco-resend-receipt', function(e) {
            e.preventDefault();
            var btn = $(this);
            var donationId = btn.data('id');
            btn.text('Sending…').prop('disabled', true);
            $.post(ajaxurl, {
                action: 'myco_resend_receipt',
                nonce: '" . wp_create_nonce('myco_admin_nonce') . "',
                donation_id: donationId
            }, function(res) {
                if (res.success) {
                    btn.text('Sent ✓').css('color', 'green');
                } else {
                    btn.text('Failed ✗').css('color', 'red').prop('disabled', false);
                    alert(res.data.message || 'Error sending receipt.');
                }
            });
        });
    });
    ");
}
