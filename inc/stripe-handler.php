<?php
/**
 * Stripe Payment Handler
 * Updated: calls myco_send_donation_receipt() after successful payment.
 *
 * @package MYCO
 */

if (!defined('ABSPATH')) {
    exit;
}

// Create donations table on theme activation
add_action('after_switch_theme', 'myco_create_donations_table');

function myco_create_donations_table(): void {
    global $wpdb;
    $table_name = $wpdb->prefix . 'myco_donations';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        stripe_session_id varchar(255) NOT NULL,
        stripe_payment_intent varchar(255) DEFAULT '',
        donor_email varchar(255) DEFAULT '',
        donor_name varchar(255) DEFAULT '',
        amount decimal(10,2) NOT NULL,
        currency varchar(3) DEFAULT 'usd',
        fund varchar(100) DEFAULT 'general',
        donation_type varchar(20) DEFAULT 'one-time',
        cover_fees tinyint(1) DEFAULT 0,
        status varchar(20) DEFAULT 'pending',
        receipt_path varchar(500) DEFAULT '',
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY stripe_session_id (stripe_session_id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// AJAX handler: Create Stripe Checkout Session
add_action('wp_ajax_create_stripe_session', 'myco_create_stripe_session');
add_action('wp_ajax_nopriv_create_stripe_session', 'myco_create_stripe_session');

function myco_create_stripe_session(): void {
    // Verify nonce
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce'] ?? ''), 'myco_donate_nonce')) {
        wp_send_json_error(['message' => 'Security check failed.']);
    }

    // Get and sanitize inputs
    $amount        = floatval($_POST['amount'] ?? 0);
    $fund          = sanitize_text_field($_POST['fund'] ?? 'general');
    $donation_type = sanitize_text_field($_POST['donation_type'] ?? 'one-time');
    $cover_fees    = intval($_POST['cover_fees'] ?? 0);

    if ($amount < 1) {
        wp_send_json_error(['message' => 'Minimum donation is $1.00.']);
    }

    // Get Stripe keys
    $secret_key = '';
    if (function_exists('get_field')) {
        $secret_key = get_field('stripe_secret_key', 'option');
    }

    if (empty($secret_key)) {
        $secret_key = get_option('myco_stripe_secret_key', '');
    }

    if (empty($secret_key)) {
        wp_send_json_error(['message' => 'Payment system is not configured. Please contact the administrator.']);
    }

    // Include Stripe PHP SDK
    $stripe_autoload = MYCO_DIR . '/vendor/autoload.php';
    if (file_exists($stripe_autoload)) {
        require_once $stripe_autoload;
    } else {
        wp_send_json_error(['message' => 'Payment library not installed. Please contact the administrator.']);
    }

    try {
        \Stripe\Stripe::setApiKey($secret_key);

        // Convert to cents
        $amount_cents = intval(round($amount * 100));

        // Build fund label
        $fund_labels = [
            'general'      => 'General Fund',
            'youth'        => 'Youth Programs',
            'athletics'    => 'Athletics',
            'scholarships' => 'Scholarships',
            'mcyc'         => 'MCYC Building',
        ];
        $fund_label = $fund_labels[$fund] ?? ucfirst($fund) . ' Fund';

        // Determine donate page URL for redirects
        $donate_page_url = home_url('/donate/');

        $session_params = [
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency'     => 'usd',
                    'unit_amount'  => $amount_cents,
                    'product_data' => [
                        'name'        => 'MYCO Donation - ' . $fund_label,
                        'description' => ($donation_type === 'monthly' ? 'Monthly' : 'One-time') . ' donation to ' . $fund_label,
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode'        => $donation_type === 'monthly' ? 'subscription' : 'payment',
            'success_url' => add_query_arg('donation', 'success', $donate_page_url),
            'cancel_url'  => add_query_arg('donation', 'cancelled', $donate_page_url),
            'metadata'    => [
                'fund'            => $fund,
                'donation_type'   => $donation_type,
                'cover_fees'      => $cover_fees,
                'original_amount' => $amount,
            ],
        ];

        // For subscriptions, wrap price_data in recurring
        if ($donation_type === 'monthly') {
            $session_params['line_items'][0]['price_data']['recurring'] = [
                'interval' => 'month',
            ];
        }

        $session = \Stripe\Checkout\Session::create($session_params);

        // Record donation in database
        global $wpdb;
        $wpdb->insert(
            $wpdb->prefix . 'myco_donations',
            [
                'stripe_session_id' => $session->id,
                'amount'            => $amount,
                'fund'              => $fund,
                'donation_type'     => $donation_type,
                'cover_fees'        => $cover_fees,
                'status'            => 'pending',
            ],
            ['%s', '%f', '%s', '%s', '%d', '%s']
        );

        wp_send_json_success([
            'checkout_url' => $session->url,
            'session_id'   => $session->id,
        ]);

    } catch (\Stripe\Exception\ApiErrorException $e) {
        wp_send_json_error(['message' => 'Payment error: ' . $e->getMessage()]);
    } catch (\Exception $e) {
        wp_send_json_error(['message' => 'An unexpected error occurred. Please try again.']);
    }
}

// ─────────────────────────────────────────────────────────────────────────────
// AJAX handler: Create Stripe PaymentIntent (for embedded Payment Element)
// ─────────────────────────────────────────────────────────────────────────────
add_action('wp_ajax_myco_create_payment_intent',        'myco_create_payment_intent');
add_action('wp_ajax_nopriv_myco_create_payment_intent', 'myco_create_payment_intent');

function myco_create_payment_intent(): void {
    if (!wp_verify_nonce(sanitize_text_field($_POST['nonce'] ?? ''), 'myco_donate_nonce')) {
        wp_send_json_error(['message' => 'Security check failed.']);
    }

    $amount        = floatval($_POST['amount'] ?? 0);
    $fund          = sanitize_text_field($_POST['fund'] ?? 'general');
    $donation_type = sanitize_text_field($_POST['donation_type'] ?? 'one-time');
    $cover_fees    = intval($_POST['cover_fees'] ?? 0);
    $donor_email   = sanitize_email($_POST['donor_email'] ?? '');

    if ($amount < 1) {
        wp_send_json_error(['message' => 'Minimum donation is $1.00.']);
    }

    // Get secret key (respects test/live mode setting)
    $keys       = myco_stripe_get_keys();
    $secret_key = $keys['secret'] ?? '';
    if (empty($secret_key)) {
        wp_send_json_error(['message' => 'Payment system is not configured. Please contact the administrator.']);
    }

    $amount_cents = intval(round($amount * 100));

    $fund_labels = [
        'general'           => 'General Fund',
        'youth-mentorship'  => 'Youth Mentorship Program',
        'athletics'         => 'Athletics & Sports',
        'academic'          => 'Academic Support',
        'leadership'        => 'Leadership Development',
        'community-service' => 'Community Service',
        'facility'          => 'Facility Fund',
    ];
    $fund_label     = $fund_labels[$fund] ?? ucfirst(str_replace('-', ' ', $fund)) . ' Fund';
    $stripe_headers = [
        'Authorization'  => 'Bearer ' . $secret_key,
        'Content-Type'   => 'application/x-www-form-urlencoded',
        'Stripe-Version' => '2024-06-20',
    ];

    global $wpdb;

    // ════════════════════════════════════════════════════════════════════
    // MONTHLY: Customer → Price → Subscription
    // ════════════════════════════════════════════════════════════════════
    if ($donation_type === 'monthly') {

        // 1. Create Customer
        $cust_body = ['metadata[source]' => 'myco_donate', 'metadata[fund]' => $fund];
        if (!empty($donor_email)) $cust_body['email'] = $donor_email;
        $cust_res = wp_remote_post('https://api.stripe.com/v1/customers', [
            'timeout' => 20, 'headers' => $stripe_headers, 'body' => $cust_body,
        ]);
        if (is_wp_error($cust_res)) wp_send_json_error(['message' => 'Could not reach Stripe.']);
        $cust = json_decode(wp_remote_retrieve_body($cust_res), true);
        if (empty($cust['id'])) wp_send_json_error(['message' => 'Stripe: ' . ($cust['error']['message'] ?? 'Could not create customer.')]);

        // 2. Create ad-hoc monthly Price (supports custom amounts)
        $price_res = wp_remote_post('https://api.stripe.com/v1/prices', [
            'timeout' => 20, 'headers' => $stripe_headers,
            'body' => [
                'currency'            => 'usd',
                'unit_amount'         => $amount_cents,
                'recurring[interval]' => 'month',
                'product_data[name]'  => 'Monthly Donation — ' . $fund_label,
            ],
        ]);
        if (is_wp_error($price_res)) wp_send_json_error(['message' => 'Could not create price.']);
        $price = json_decode(wp_remote_retrieve_body($price_res), true);
        if (empty($price['id'])) wp_send_json_error(['message' => 'Stripe: ' . ($price['error']['message'] ?? 'Could not create price.')]);

        // 3. Create Subscription — Payment Element finishes confirmation
        $sub_res = wp_remote_post('https://api.stripe.com/v1/subscriptions', [
            'timeout' => 20, 'headers' => $stripe_headers,
            'body' => [
                'customer'                                      => $cust['id'],
                'items[0][price]'                               => $price['id'],
                'payment_behavior'                              => 'default_incomplete',
                'payment_settings[save_default_payment_method]' => 'on_subscription',
                'expand[0]'                                     => 'latest_invoice.payment_intent',
                'metadata[fund]'                                => $fund,
                'metadata[donor_email]'                         => $donor_email,
                'metadata[cover_fees]'                          => $cover_fees,
            ],
        ]);
        if (is_wp_error($sub_res)) wp_send_json_error(['message' => 'Could not create subscription.']);
        $sub           = json_decode(wp_remote_retrieve_body($sub_res), true);
        $client_secret = $sub['latest_invoice']['payment_intent']['client_secret'] ?? '';
        $pi_id         = $sub['latest_invoice']['payment_intent']['id'] ?? '';
        if (empty($client_secret)) wp_send_json_error(['message' => 'Stripe: ' . ($sub['error']['message'] ?? 'Could not get payment secret.')]);

        $wpdb->insert($wpdb->prefix . 'myco_donations', [
            'stripe_session_id' => $pi_id, 'stripe_payment_intent' => $pi_id,
            'donor_email' => $donor_email, 'amount' => $amount,
            'fund' => $fund, 'donation_type' => 'monthly',
            'cover_fees' => $cover_fees, 'status' => 'pending',
        ], ['%s','%s','%s','%f','%s','%s','%d','%s']);

        // Generate cancel token and store subscription_id → token mapping
        $cancel_token = bin2hex(random_bytes(20));
        $sub_id       = $sub['id'] ?? '';
        update_option('myco_cancel_' . $cancel_token, [
            'subscription_id' => $sub_id,
            'donor_email'     => $donor_email,
            'amount'          => $amount,
            'fund'            => $fund,
            'created'         => current_time('mysql'),
        ], false);

        // Trigger subscription confirmation email with cancel link
        if (!empty($donor_email) && function_exists('myco_send_subscription_confirmation')) {
            myco_send_subscription_confirmation([
                'email'        => $donor_email,
                'amount'       => $amount,
                'fund'         => $fund,
                'cancel_token' => $cancel_token,
                'sub_id'       => $sub_id,
            ]);
        }

        wp_send_json_success(['client_secret' => $client_secret]);
    }

    // ════════════════════════════════════════════════════════════════════
    // ONE-TIME: PaymentIntent
    // ════════════════════════════════════════════════════════════════════
    $body_ot = [
        'amount'                             => $amount_cents,
        'currency'                           => 'usd',
        'description'                        => 'MYCO Donation – ' . $fund_label,
        'automatic_payment_methods[enabled]' => 'true',
        'metadata[fund]'                     => $fund,
        'metadata[donation_type]'            => $donation_type,
        'metadata[cover_fees]'               => $cover_fees,
        'metadata[donor_email]'              => $donor_email,
    ];
    if (!empty($donor_email)) $body_ot['receipt_email'] = $donor_email;

    // ── Create PaymentIntent via Stripe REST API (no Composer/SDK needed) ──
    $response = wp_remote_post('https://api.stripe.com/v1/payment_intents', [
        'timeout' => 20,
        'headers' => $stripe_headers,
        'body'    => $body_ot,
    ]);

    if (is_wp_error($response)) {
        wp_send_json_error(['message' => 'Could not connect to payment processor: ' . $response->get_error_message()]);
    }

    $body   = json_decode(wp_remote_retrieve_body($response), true);
    $status = wp_remote_retrieve_response_code($response);

    if ($status !== 200 || empty($body['client_secret'])) {
        $stripe_msg = $body['error']['message'] ?? 'Unknown Stripe error.';
        wp_send_json_error(['message' => 'Stripe error: ' . $stripe_msg]);
    }

    $wpdb->insert($wpdb->prefix . 'myco_donations', [
        'stripe_session_id' => $body['id'],
        'donor_email'       => $donor_email,
        'amount'            => $amount,
        'fund'              => $fund,
        'donation_type'     => $donation_type,
        'cover_fees'        => $cover_fees,
        'status'            => 'pending',
    ], ['%s','%s','%f','%s','%s','%d','%s']);

    wp_send_json_success(['client_secret' => $body['client_secret']]);
}

// ─────────────────────────────────────────────────────────────────────────────
// AJAX handler: Mark donation complete after Payment Element confirms
// Called by Stripe webhook (payment_intent.succeeded) or can be polled.
// ─────────────────────────────────────────────────────────────────────────────

// ─────────────────────────────────────────────────────────────────────────────
// Admin: Stripe Settings page
// ─────────────────────────────────────────────────────────────────────────────
add_action('admin_menu', 'myco_stripe_settings_menu');

function myco_stripe_settings_menu(): void {
    add_menu_page(
        __('MYCO Settings', 'myco'),
        __('MYCO Settings', 'myco'),
        'manage_options',
        'myco-settings',
        'myco_stripe_settings_page',
        'dashicons-heart',
        58
    );
    add_submenu_page(
        'myco-settings',
        __('Donations', 'myco'),
        __('📋 Donations', 'myco'),
        'manage_options',
        'myco-donations',
        'myco_admin_donations_page'
    );
}

// ─────────────────────────────────────────────────────────────────────────────
// Admin: Cancel subscription handler (admin-post)
// ─────────────────────────────────────────────────────────────────────────────
add_action('admin_post_myco_cancel_subscription', 'myco_admin_cancel_subscription');

function myco_admin_cancel_subscription(): void {
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized', 403);
    }
    check_admin_referer('myco_cancel_sub_admin');

    $cancel_token = sanitize_text_field($_POST['cancel_token'] ?? '');
    $sub_id       = sanitize_text_field($_POST['sub_id'] ?? '');
    $redirect     = admin_url('admin.php?page=myco-donations');

    if (empty($sub_id)) {
        wp_redirect($redirect . '&notice=no_sub_id');
        exit;
    }

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
        // Remove the cancel token so donor link is no longer valid
        if ($cancel_token) delete_option('myco_cancel_' . $cancel_token);
        // Update donations table
        global $wpdb;
        $wpdb->update(
            $wpdb->prefix . 'myco_donations',
            ['status' => 'cancelled'],
            ['stripe_session_id' => $sub_id],
            ['%s'], ['%s']
        );
        wp_redirect($redirect . '&notice=cancelled');
    } else {
        $err = urlencode($body['error']['message'] ?? 'Stripe error');
        wp_redirect($redirect . '&notice=error&msg=' . $err);
    }
    exit;
}

// ─────────────────────────────────────────────────────────────────────────────
// Admin: Donations page callback
// ─────────────────────────────────────────────────────────────────────────────
function myco_admin_donations_page(): void {
    if (!current_user_can('manage_options')) return;

    global $wpdb;
    $table   = $wpdb->prefix . 'myco_donations';
    $filter  = sanitize_text_field($_GET['filter'] ?? 'all');
    $notice  = sanitize_text_field($_GET['notice'] ?? '');
    $msg     = sanitize_text_field($_GET['msg'] ?? '');

    // Build query
    $where = '';
    if ($filter === 'monthly')  $where = "WHERE donation_type = 'monthly'";
    if ($filter === 'onetime')  $where = "WHERE donation_type = 'one-time'";
    if ($filter === 'active')   $where = "WHERE donation_type = 'monthly' AND status != 'cancelled'";
    if ($filter === 'pending')  $where = "WHERE status = 'pending'";

    $donations = $wpdb->get_results("SELECT * FROM {$table} {$where} ORDER BY created_at DESC LIMIT 200");

    // Map cancel tokens for lookup (Optimized: fetch once)
    $cancel_tokens = [];
    $all_tokens = $wpdb->get_results("SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE 'myco_cancel_%' LIMIT 500");
    
    foreach ($donations as $dn) {
        if ($dn->donation_type === 'monthly' && $dn->status !== 'cancelled') {
            foreach ($all_tokens as $opt) {
                $val = maybe_unserialize($opt->option_value);
                if (is_array($val) && ($val['subscription_id'] ?? '') === $dn->stripe_session_id) {
                    $cancel_tokens[$dn->stripe_session_id] = str_replace('myco_cancel_', '', $opt->option_name);
                    break;
                }
            }
        }
    }

    $fund_labels = [
        'general'=>'General Fund','youth-mentorship'=>'Youth Mentorship',
        'athletics'=>'Athletics','academic'=>'Academic Support',
        'leadership'=>'Leadership','community-service'=>'Community Service',
        'facility'=>'Facility',
    ];

    $filter_url = fn($f) => admin_url('admin.php?page=myco-donations&filter=' . $f);
    ?>
    <div class="wrap">
        <h1 style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
            <span style="background:#C8402E;color:#fff;border-radius:8px;padding:6px 14px;">MYCO</span>
            Donations Manager
        </h1>

        <?php if ($notice === 'cancelled'): ?>
        <div class="notice notice-success is-dismissible"><p>✅ <strong>Subscription cancelled.</strong> The donor will not be charged again after the current period.</p></div>
        <?php elseif ($notice === 'error'): ?>
        <div class="notice notice-error is-dismissible"><p>❌ Error from Stripe: <?php echo esc_html($msg); ?></p></div>
        <?php elseif ($notice === 'no_sub_id'): ?>
        <div class="notice notice-warning is-dismissible"><p>⚠️ No subscription ID found for this record.</p></div>
        <?php endif; ?>

        <!-- Filter tabs -->
        <div style="margin-bottom:16px;display:flex;gap:8px;flex-wrap:wrap;">
            <?php
            $tabs = ['all' => 'All Donations', 'active' => '📅 Active Monthly', 'monthly' => 'All Monthly', 'onetime' => 'One-Time', 'pending' => 'Pending'];
            foreach ($tabs as $key => $label):
                $active_style = ($filter === $key) ? 'background:#C8402E;color:#fff;' : 'background:#f0f0f1;color:#1d2327;';
            ?>
            <a href="<?php echo esc_url($filter_url($key)); ?>"
               style="<?php echo $active_style; ?>padding:6px 14px;border-radius:6px;text-decoration:none;font-size:13px;font-weight:600;">
                <?php echo esc_html($label); ?>
            </a>
            <?php endforeach; ?>
            <span style="margin-left:auto;font-size:12px;color:#6B7280;align-self:center;">
                <?php echo count($donations); ?> record(s)
            </span>
        </div>

        <!-- Donations table -->
        <table class="wp-list-table widefat fixed striped" style="font-size:13px;">
            <thead>
                <tr>
                    <th style="width:60px;">ID</th>
                    <th style="width:120px;">Date</th>
                    <th style="width:130px;">Email</th>
                    <th style="width:80px;">Amount</th>
                    <th style="width:110px;">Fund</th>
                    <th style="width:80px;">Type</th>
                    <th style="width:90px;">Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($donations)): ?>
                <tr><td colspan="8" style="text-align:center;padding:28px;color:#6B7280;">No donations found.</td></tr>
            <?php else: ?>
                <?php foreach ($donations as $d):
                    $status_colors = [
                        'complete'  => '#16a34a',
                        'pending'   => '#ca8a04',
                        'cancelled' => '#dc2626',
                        'failed'    => '#dc2626',
                    ];
                    $sc   = $status_colors[$d->status] ?? '#6B7280';
                    $fl   = $fund_labels[$d->fund] ?? ucfirst($d->fund);
                    $date = date('M j, Y', strtotime($d->created_at));
                    $is_monthly_active = ($d->donation_type === 'monthly' && $d->status !== 'cancelled');
                    $ct     = $cancel_tokens[$d->stripe_session_id] ?? '';
                    $sub_id = $ct ? $d->stripe_session_id : '';
                    ?>
                    <tr>
                        <td><?php echo (int)$d->id; ?></td>
                        <td><?php echo esc_html($date); ?></td>
                        <td title="<?php echo esc_attr($d->donor_email ?: '—'); ?>">
                            <?php echo esc_html($d->donor_email ? substr($d->donor_email, 0, 18) . (strlen($d->donor_email) > 18 ? '…' : '') : '—'); ?>
                        </td>
                        <td><strong>$<?php echo number_format((float)$d->amount, 2); ?></strong></td>
                        <td><?php echo esc_html($fl); ?></td>
                        <td>
                            <span style="font-size:11px;background:<?php echo $d->donation_type === 'monthly' ? '#EFF6FF' : '#F0FDF4'; ?>;color:<?php echo $d->donation_type === 'monthly' ? '#1d4ed8' : '#15803d'; ?>;padding:2px 8px;border-radius:9999px;font-weight:600;">
                                <?php echo $d->donation_type === 'monthly' ? '📅 Monthly' : '💚 One-Time'; ?>
                            </span>
                        </td>
                        <td>
                            <span style="font-size:11px;background:<?php echo $sc; ?>20;color:<?php echo $sc; ?>;padding:2px 8px;border-radius:9999px;font-weight:700;">
                                <?php echo esc_html(ucfirst($d->status)); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($is_monthly_active && $d->stripe_session_id): ?>
                            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>"
                                  onsubmit="return confirm('Cancel this monthly subscription? The donor will not be charged again after their current billing period.');">
                                <?php wp_nonce_field('myco_cancel_sub_admin'); ?>
                                <input type="hidden" name="action"       value="myco_cancel_subscription">
                                <input type="hidden" name="sub_id"       value="<?php echo esc_attr($d->stripe_session_id); ?>">
                                <input type="hidden" name="cancel_token" value="<?php echo esc_attr($ct); ?>">
                                <button type="submit" class="button button-small"
                                        style="background:#dc2626;border-color:#b91c1c;color:#fff;font-weight:600;">
                                    ✕ Cancel Sub
                                </button>
                            </form>
                            <?php elseif ($d->status === 'cancelled'): ?>
                                <span style="color:#9CA3AF;font-size:12px;">Cancelled</span>
                            <?php elseif ($d->donation_type === 'one-time'): ?>
                                <span style="color:#9CA3AF;font-size:12px;">—</span>
                            <?php else: ?>
                                <span style="color:#9CA3AF;font-size:12px;">No sub ID</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>

        <p style="margin-top:12px;font-size:12px;color:#9CA3AF;">
            Cancelling a subscription sets it to cancel at the end of the current billing period. The donor keeps access until then and will not be charged again.
        </p>
    </div>
    <?php
}


add_action('admin_init', 'myco_stripe_settings_register');

function myco_stripe_settings_register(): void {
    register_setting('myco_stripe_settings', 'myco_stripe_key_mode');
    register_setting('myco_stripe_settings', 'myco_stripe_publishable_key');
    register_setting('myco_stripe_settings', 'myco_stripe_secret_key');
    register_setting('myco_stripe_settings', 'myco_stripe_webhook_secret');
    register_setting('myco_stripe_settings', 'myco_ein');
    register_setting('myco_stripe_settings', 'myco_stripe_publishable_key_test');
    register_setting('myco_stripe_settings', 'myco_stripe_secret_key_test');
}

function myco_stripe_settings_page(): void {
    if (!current_user_can('manage_options')) return;

    $mode = get_option('myco_stripe_key_mode', 'test');
    $saved = isset($_GET['settings-updated']) ? true : false;
    ?>
    <div class="wrap" style="max-width:720px;">
        <h1 style="display:flex;align-items:center;gap:10px;">
            <span style="background:#C8402E;color:#fff;border-radius:8px;padding:6px 14px;font-size:18px;">MYCO</span>
            <?php esc_html_e('Stripe Settings', 'myco'); ?>
        </h1>

        <?php if ($saved): ?>
        <div class="notice notice-success is-dismissible"><p><?php esc_html_e('Settings saved.', 'myco'); ?></p></div>
        <?php endif; ?>

        <!-- How to get keys -->
        <div style="background:#fff8f0;border-left:4px solid #C8402E;border-radius:4px;padding:14px 18px;margin:18px 0 24px;">
            <strong>How to get your Stripe API keys:</strong><br>
            1. Login at <a href="https://dashboard.stripe.com/apikeys" target="_blank">dashboard.stripe.com/apikeys</a><br>
            2. Copy <strong>Publishable key</strong> (starts with <code>pk_</code>) and <strong>Secret key</strong> (starts with <code>sk_</code>)<br>
            3. Use <strong>Test keys</strong> (<code>pk_test_</code>) while testing, switch to <strong>Live keys</strong> when ready.
        </div>

        <form method="post" action="options.php">
            <?php settings_fields('myco_stripe_settings'); ?>

            <!-- Mode toggle -->
            <table class="form-table">
                <tr>
                    <th><?php esc_html_e('Mode', 'myco'); ?></th>
                    <td>
                        <label style="margin-right:20px;">
                            <input type="radio" name="myco_stripe_key_mode" value="test" <?php checked($mode, 'test'); ?>>
                            🧪 <?php esc_html_e('Test Mode', 'myco'); ?>
                        </label>
                        <label>
                            <input type="radio" name="myco_stripe_key_mode" value="live" <?php checked($mode, 'live'); ?>>
                            🚀 <?php esc_html_e('Live Mode', 'myco'); ?>
                        </label>
                        <p class="description">Use Test mode while developing. Switch to Live only on your production site.</p>
                    </td>
                </tr>

                <tr><th colspan="2"><hr style="margin:8px 0;"><h2 style="margin:0;">🧪 Test Keys</h2></th></tr>
                <tr>
                    <th><label for="pk_test"><?php esc_html_e('Test Publishable Key', 'myco'); ?></label></th>
                    <td>
                        <input type="text" id="pk_test" name="myco_stripe_publishable_key_test"
                               value="<?php echo esc_attr(get_option('myco_stripe_publishable_key_test', '')); ?>"
                               class="regular-text" placeholder="pk_test_...">
                    </td>
                </tr>
                <tr>
                    <th><label for="sk_test"><?php esc_html_e('Test Secret Key', 'myco'); ?></label></th>
                    <td>
                        <input type="password" id="sk_test" name="myco_stripe_secret_key_test"
                               value="<?php echo esc_attr(get_option('myco_stripe_secret_key_test', '')); ?>"
                               class="regular-text" placeholder="sk_test_...">
                    </td>
                </tr>

                <tr><th colspan="2"><hr style="margin:8px 0;"><h2 style="margin:0;">🚀 Live Keys</h2></th></tr>
                <tr>
                    <th><label for="pk_live"><?php esc_html_e('Live Publishable Key', 'myco'); ?></label></th>
                    <td>
                        <input type="text" id="pk_live" name="myco_stripe_publishable_key"
                               value="<?php echo esc_attr(get_option('myco_stripe_publishable_key', '')); ?>"
                               class="regular-text" placeholder="pk_live_...">
                    </td>
                </tr>
                <tr>
                    <th><label for="sk_live"><?php esc_html_e('Live Secret Key', 'myco'); ?></label></th>
                    <td>
                        <input type="password" id="sk_live" name="myco_stripe_secret_key"
                               value="<?php echo esc_attr(get_option('myco_stripe_secret_key', '')); ?>"
                               class="regular-text" placeholder="sk_live_...">
                    </td>
                </tr>

                <tr><th colspan="2"><hr style="margin:8px 0;"><h2 style="margin:0;">⚙️ Other Settings</h2></th></tr>
                <tr>
                    <th><label for="myco_ein"><?php esc_html_e('EIN (Tax ID)', 'myco'); ?></label></th>
                    <td>
                        <input type="text" id="myco_ein" name="myco_ein"
                               value="<?php echo esc_attr(get_option('myco_ein', '')); ?>"
                               class="regular-text" placeholder="XX-XXXXXXX">
                        <p class="description">Appears on donation receipts.</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="myco_webhook"><?php esc_html_e('Webhook Secret', 'myco'); ?></label></th>
                    <td>
                        <input type="password" id="myco_webhook" name="myco_stripe_webhook_secret"
                               value="<?php echo esc_attr(get_option('myco_stripe_webhook_secret', '')); ?>"
                               class="regular-text" placeholder="whsec_...">
                        <p class="description">
                            Webhook URL: <code><?php echo esc_html(home_url('/wp-json/myco/v1/stripe-webhook')); ?></code>
                        </p>
                    </td>
                </tr>
                <tr><th colspan="2"><hr style="margin:8px 0;"><h2 style="margin:0;">📧 Form Notifications</h2></th></tr>
                <tr>
                    <th><label for="vol_email"><?php esc_html_e('Volunteer Notification Email', 'myco'); ?></label></th>
                    <td>
                        <input type="email" id="vol_email" name="myco_volunteer_notification_email"
                               value="<?php echo esc_attr(get_option('myco_volunteer_notification_email', get_option('admin_email'))); ?>"
                               class="regular-text" placeholder="admin@example.com">
                        <p class="description">Email address to receive new volunteer registrations.</p>
                    </td>
                </tr>
                <tr>
                    <th><label for="contact_email"><?php esc_html_e('Contact Notification Email', 'myco'); ?></label></th>
                    <td>
                        <input type="email" id="contact_email" name="myco_contact_notification_email"
                               value="<?php echo esc_attr(get_option('myco_contact_notification_email', get_option('admin_email'))); ?>"
                               class="regular-text" placeholder="info@example.com">
                        <p class="description">Email address to receive messages from the contact form.</p>
                    </td>
                </tr>
            </table>

            <?php submit_button(__('Save Settings', 'myco')); ?>
        </form>
    </div>
    <?php
}

/**
 * Helper: get the active Stripe keys based on test/live mode.
 */
function myco_stripe_get_keys(): array {
    $mode = get_option('myco_stripe_key_mode', 'test');
    if ($mode === 'live') {
        return [
            'publishable' => get_option('myco_stripe_publishable_key', ''),
            'secret'      => get_option('myco_stripe_secret_key', ''),
        ];
    }
    return [
        'publishable' => get_option('myco_stripe_publishable_key_test', ''),
        'secret'      => get_option('myco_stripe_secret_key_test', ''),
    ];
}

// Stripe Webhook Handler
add_action('rest_api_init', 'myco_register_stripe_webhook');

function myco_register_stripe_webhook(): void {
    register_rest_route('myco/v1', '/stripe-webhook', [
        'methods'             => 'POST',
        'callback'            => 'myco_handle_stripe_webhook',
        'permission_callback' => '__return_true',
    ]);
}

function myco_handle_stripe_webhook(WP_REST_Request $request): WP_REST_Response {
    $payload    = $request->get_body();
    $sig_header = $request->get_header('stripe-signature');

    $webhook_secret = '';
    if (function_exists('get_field')) {
        $webhook_secret = get_field('stripe_webhook_secret', 'option');
    }

    if (empty($webhook_secret)) {
        return new WP_REST_Response(['error' => 'Webhook not configured'], 400);
    }

    $stripe_autoload = MYCO_DIR . '/vendor/autoload.php';
    if (file_exists($stripe_autoload)) {
        require_once $stripe_autoload;
    }

    try {
        $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $webhook_secret);
    } catch (\Exception $e) {
        return new WP_REST_Response(['error' => 'Webhook signature verification failed'], 400);
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'myco_donations';

    switch ($event->type) {
        case 'checkout.session.completed':
            $session = $event->data->object;

            $wpdb->update(
                $table_name,
                [
                    'status'                => 'completed',
                    'stripe_payment_intent' => $session->payment_intent ?? '',
                    'donor_email'           => sanitize_email($session->customer_details->email ?? ''),
                    'donor_name'            => sanitize_text_field($session->customer_details->name ?? ''),
                ],
                ['stripe_session_id' => $session->id],
                ['%s', '%s', '%s', '%s'],
                ['%s']
            );

            // ── Send PDF Receipt ──────────────────────────────────────────────
            // Re-fetch the updated row so receipt-handler.php has donor_email/name
            $donation = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM {$table_name} WHERE stripe_session_id = %s LIMIT 1",
                $session->id
            ));

            if ($donation && function_exists('myco_send_donation_receipt')) {
                myco_send_donation_receipt($donation);
            }
            // ─────────────────────────────────────────────────────────────────
            break;

        case 'checkout.session.expired':
            $session = $event->data->object;
            $wpdb->update(
                $table_name,
                ['status' => 'expired'],
                ['stripe_session_id' => $session->id],
                ['%s'],
                ['%s']
            );
            break;

        case 'invoice.payment_succeeded':
            // Handle recurring subscription renewals
            $invoice  = $event->data->object;
            $sub_id   = $invoice->subscription ?? '';
            if ($sub_id) {
                $donor_email = sanitize_email($invoice->customer_email ?? '');
                $amount      = floatval($invoice->amount_paid / 100);

                // Log the renewal
                $wpdb->insert(
                    $table_name,
                    [
                        'stripe_session_id'     => 'renewal_' . $invoice->id,
                        'stripe_payment_intent' => $invoice->payment_intent ?? '',
                        'donor_email'           => $donor_email,
                        'amount'                => $amount,
                        'donation_type'         => 'monthly',
                        'status'                => 'completed',
                    ],
                    ['%s', '%s', '%s', '%f', '%s', '%s']
                );

                // Send receipt for renewal
                $renewal_row = $wpdb->get_row($wpdb->prepare(
                    "SELECT * FROM {$table_name} WHERE stripe_session_id = %s LIMIT 1",
                    'renewal_' . $invoice->id
                ));
                if ($renewal_row && function_exists('myco_send_donation_receipt')) {
                    myco_send_donation_receipt($renewal_row);
                }
            }
            break;
    }

    return new WP_REST_Response(['received' => true], 200);
}

// Admin page for viewing donations
add_action('admin_menu', 'myco_donations_admin_menu');

function myco_donations_admin_menu(): void {
    add_submenu_page(
        'myco-settings',
        __('Donations', 'myco'),
        __('Donation History', 'myco'),
        'manage_options',
        'myco-donation-history',
        'myco_donations_admin_page'
    );
}

function myco_donations_admin_page(): void {
    global $wpdb;
    $table_name = $wpdb->prefix . 'myco_donations';

    // Handle CSV export
    if (isset($_GET['export']) && $_GET['export'] === 'csv' && current_user_can('manage_options')) {
        check_admin_referer('myco_export_donations');
        $all = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");
        $filename = 'myco-donations-' . date('Y-m-d') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['Date', 'Donor Name', 'Email', 'Amount', 'Fund', 'Type', 'Status', 'Session ID']);
        foreach ($all as $d) {
            fputcsv($out, [$d->created_at, $d->donor_name, $d->donor_email, '$' . number_format($d->amount, 2), ucfirst($d->fund), ucfirst($d->donation_type), ucfirst($d->status), $d->stripe_session_id]);
        }
        fclose($out);
        exit;
    }

    $donations = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC LIMIT 200");

    // Summary stats
    $total_raised = (float) $wpdb->get_var("SELECT SUM(amount) FROM $table_name WHERE status = 'completed'");
    $total_count  = (int) $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 'completed'");

    $export_url = wp_nonce_url(add_query_arg(['export' => 'csv'], admin_url('admin.php?page=myco-donation-history')), 'myco_export_donations');
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Donation History', 'myco'); ?>
            <a href="<?php echo esc_url($export_url); ?>" class="page-title-action">Export CSV</a>
        </h1>

        <!-- Stats -->
        <div style="display:flex;gap:16px;margin:16px 0 24px;">
            <div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:16px 24px;min-width:160px;">
                <div style="font-size:11px;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Total Raised</div>
                <div style="font-size:26px;font-weight:800;color:#1A4A48;">$<?php echo number_format($total_raised, 2); ?></div>
            </div>
            <div style="background:#fff;border:1px solid #e5e7eb;border-radius:8px;padding:16px 24px;min-width:160px;">
                <div style="font-size:11px;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;font-weight:600;">Completed Donations</div>
                <div style="font-size:26px;font-weight:800;color:#1A4A48;"><?php echo number_format($total_count); ?></div>
            </div>
        </div>

        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Donor</th>
                    <th>Email</th>
                    <th>Amount</th>
                    <th>Fund</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Receipt</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($donations) : ?>
                    <?php foreach ($donations as $d) :
                        $has_receipt = !empty($d->receipt_path) && file_exists($d->receipt_path);
                        $token = function_exists('myco_receipt_token') ? myco_receipt_token($d->stripe_session_id) : '';
                        $dl_url = $has_receipt ? add_query_arg([
                            'myco_receipt' => rawurlencode($d->stripe_session_id),
                            'token'        => $token,
                        ], home_url('/')) : '';
                    ?>
                    <tr>
                        <td><?php echo esc_html($d->created_at); ?></td>
                        <td><?php echo esc_html($d->donor_name ?: '—'); ?></td>
                        <td><?php echo esc_html($d->donor_email ?: '—'); ?></td>
                        <td><strong>$<?php echo esc_html(number_format($d->amount, 2)); ?></strong></td>
                        <td><?php echo esc_html(ucfirst($d->fund)); ?></td>
                        <td><?php echo esc_html(ucfirst($d->donation_type)); ?></td>
                        <td>
                            <?php
                            $status_colors = ['completed' => '#16a34a', 'pending' => '#d97706', 'expired' => '#9ca3af', 'failed' => '#dc2626'];
                            $color = $status_colors[$d->status] ?? '#374151';
                            ?>
                            <span style="color:<?php echo esc_attr($color); ?>;font-weight:600;"><?php echo esc_html(ucfirst($d->status)); ?></span>
                        </td>
                        <td>
                            <?php if ($d->status === 'completed') : ?>
                                <?php if ($dl_url) : ?>
                                    <a href="<?php echo esc_url($dl_url); ?>" target="_blank" class="button button-small">↓ PDF</a>
                                <?php endif; ?>
                                <button class="button button-small myco-resend-receipt" data-id="<?php echo esc_attr($d->id); ?>"
                                    <?php echo !is_email($d->donor_email) ? 'disabled title="No donor email"' : ''; ?>>
                                    Resend Email
                                </button>
                            <?php else : ?>
                                <span style="color:#9ca3af;">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr><td colspan="8">No donations recorded yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}
