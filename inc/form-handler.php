<?php
/**
 * MYCO Form Handler
 * - Volunteer registration form: AJAX handler, DB storage, wp_mail notification
 * - Contact form: AJAX handler, DB storage, wp_mail notification
 * - Admin submenu pages for viewing submissions
 * - MYCO Settings: notification email fields
 *
 * @package MYCO
 */

if (!defined('ABSPATH')) exit;

// ═══════════════════════════════════════════════════════════════════════════════
// 1. Create DB tables on theme activation
// ═══════════════════════════════════════════════════════════════════════════════
add_action('after_switch_theme', 'myco_create_form_tables');

function myco_create_form_tables(): void {
    global $wpdb;
    $charset = $wpdb->get_charset_collate();

    $sql_vol = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}myco_volunteers (
        id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        first_name      VARCHAR(100) NOT NULL,
        last_name       VARCHAR(100) NOT NULL,
        email           VARCHAR(200) NOT NULL,
        phone           VARCHAR(50),
        address         TEXT,
        city            VARCHAR(100),
        state           VARCHAR(50),
        zip             VARCHAR(20),
        interests       TEXT,
        days_available  TEXT,
        time_preference VARCHAR(50),
        hours_per_week  VARCHAR(20),
        skills          TEXT,
        why_volunteer   TEXT,
        emergency_name  VARCHAR(200),
        emergency_phone VARCHAR(50),
        emergency_rel   VARCHAR(100),
        background_check TINYINT(1) DEFAULT 0,
        code_of_conduct  TINYINT(1) DEFAULT 0,
        communications   TINYINT(1) DEFAULT 0,
        status          VARCHAR(20) DEFAULT 'new',
        created_at      DATETIME DEFAULT CURRENT_TIMESTAMP
    ) {$charset};";

    $sql_contact = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}myco_contacts (
        id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        first_name VARCHAR(100) NOT NULL,
        last_name  VARCHAR(100) NOT NULL,
        email      VARCHAR(200) NOT NULL,
        phone      VARCHAR(50),
        subject    VARCHAR(100),
        message    TEXT NOT NULL,
        status     VARCHAR(20) DEFAULT 'new',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) {$charset};";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql_vol);
    dbDelta($sql_contact);
}

// Also run on admin_init if tables don't exist yet
add_action('admin_init', function () {
    global $wpdb;
    if ($wpdb->get_var("SHOW TABLES LIKE '{$wpdb->prefix}myco_volunteers'") !== $wpdb->prefix . 'myco_volunteers') {
        myco_create_form_tables();
    }
});

// ═══════════════════════════════════════════════════════════════════════════════
// 2. Register notification email settings in MYCO admin
// ═══════════════════════════════════════════════════════════════════════════════
add_action('admin_init', function () {
    register_setting('myco_stripe_settings', 'myco_volunteer_notification_email');
    register_setting('myco_stripe_settings', 'myco_contact_notification_email');
});

// ═══════════════════════════════════════════════════════════════════════════════
// 3. AJAX: Volunteer form submission
// ═══════════════════════════════════════════════════════════════════════════════
add_action('wp_ajax_myco_volunteer_submit',        'myco_handle_volunteer_submit');
add_action('wp_ajax_nopriv_myco_volunteer_submit', 'myco_handle_volunteer_submit');

function myco_handle_volunteer_submit(): void {
    if (!wp_verify_nonce(sanitize_text_field($_POST['myco_volunteer_nonce'] ?? ''), 'myco_volunteer_form')) {
        wp_send_json_error('Security check failed.');
    }

    $first_name = sanitize_text_field($_POST['first_name'] ?? '');
    $last_name  = sanitize_text_field($_POST['last_name'] ?? '');
    $email      = sanitize_email($_POST['email'] ?? '');
    $phone      = sanitize_text_field($_POST['phone'] ?? '');

    if (empty($first_name) || empty($last_name) || empty($email)) {
        wp_send_json_error('Please fill in all required fields.');
    }

    $interests  = isset($_POST['interests']) && is_array($_POST['interests'])
                  ? array_map('sanitize_text_field', $_POST['interests']) : [];
    $days       = isset($_POST['days']) && is_array($_POST['days'])
                  ? array_map('sanitize_text_field', $_POST['days']) : [];

    $data = [
        'first_name'       => $first_name,
        'last_name'        => $last_name,
        'email'            => $email,
        'phone'            => $phone,
        'address'          => sanitize_text_field($_POST['address'] ?? ''),
        'city'             => sanitize_text_field($_POST['city'] ?? ''),
        'state'            => sanitize_text_field($_POST['state'] ?? ''),
        'zip'              => sanitize_text_field($_POST['zip'] ?? ''),
        'interests'        => implode(', ', $interests),
        'days_available'   => implode(', ', $days),
        'time_preference'  => sanitize_text_field($_POST['time_preference'] ?? ''),
        'hours_per_week'   => sanitize_text_field($_POST['hours_per_week'] ?? ''),
        'skills'           => sanitize_textarea_field($_POST['skills'] ?? ''),
        'why_volunteer'    => sanitize_textarea_field($_POST['why_volunteer'] ?? ''),
        'emergency_name'   => sanitize_text_field($_POST['emergency_name'] ?? ''),
        'emergency_phone'  => sanitize_text_field($_POST['emergency_phone'] ?? ''),
        'emergency_rel'    => sanitize_text_field($_POST['emergency_relationship'] ?? ''),
        'background_check' => intval($_POST['background_check'] ?? 0),
        'code_of_conduct'  => intval($_POST['code_of_conduct'] ?? 0),
        'communications'   => intval($_POST['communications'] ?? 0),
        'status'           => 'new',
    ];

    global $wpdb;
    $wpdb->insert($wpdb->prefix . 'myco_volunteers', $data);

    // Send email notification to admin
    $notify_email = get_option('myco_volunteer_notification_email', get_option('admin_email'));
    $org          = get_bloginfo('name');

    $interest_labels = [
        'youth-mentor'        => 'Youth Mentorship',
        'sports-coach'        => 'Sports Coaching',
        'academic-tutor'      => 'Academic Tutoring',
        'event-coordinator'   => 'Event Coordination',
        'program-assistant'   => 'Program Assistant',
        'community-outreach'  => 'Community Outreach',
    ];
    $interests_nice = array_map(function($i) use ($interest_labels) {
        return $interest_labels[$i] ?? ucfirst($i);
    }, $interests);

    $html = '<!DOCTYPE html><html><head><meta charset="UTF-8">
<style>
body{margin:0;padding:0;font-family:\'Segoe UI\',Arial,sans-serif;background:#F5F6FA;}
.wrap{max-width:580px;margin:30px auto;background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.08);}
.head{background:#141943;padding:28px 34px;text-align:center;}
.head h1{color:#fff;font-size:20px;font-weight:900;margin:0;}
.head p{color:rgba(255,255,255,.55);font-size:12px;margin:5px 0 0;}
.body{padding:30px 34px;}
.badge{display:inline-block;background:#16a34a;color:#fff;font-size:11px;font-weight:700;padding:4px 12px;border-radius:9999px;text-transform:uppercase;letter-spacing:.08em;margin-bottom:18px;}
h2{font-size:18px;font-weight:800;color:#141943;margin-bottom:16px;}
table{width:100%;border-collapse:collapse;margin-bottom:20px;}
td{padding:8px 0;border-bottom:1px solid #F3F4F6;font-size:13px;vertical-align:top;}
td:first-child{color:#6B7280;font-weight:600;width:40%;}
td:last-child{font-weight:600;color:#141943;}
.foot{background:#F5F6FA;padding:14px 34px;text-align:center;font-size:11px;color:#9CA3AF;}
</style></head><body>
<div class="wrap">
<div class="head"><h1>' . esc_html($org) . '</h1><p>New Volunteer Registration</p></div>
<div class="body">
<span class="badge">New Application</span>
<h2>' . esc_html($first_name . ' ' . $last_name) . '</h2>
<table>
<tr><td>Email</td><td><a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a></td></tr>
<tr><td>Phone</td><td>' . esc_html($phone ?: '—') . '</td></tr>
<tr><td>Address</td><td>' . esc_html(trim(implode(', ', array_filter([$data['address'], $data['city'], $data['state'], $data['zip']]))) ?: '—') . '</td></tr>
<tr><td>Interests</td><td>' . esc_html(implode(', ', $interests_nice) ?: '—') . '</td></tr>
<tr><td>Available Days</td><td>' . esc_html($data['days_available'] ?: '—') . '</td></tr>
<tr><td>Preferred Time</td><td>' . esc_html($data['time_preference'] ?: '—') . '</td></tr>
<tr><td>Hours/Week</td><td>' . esc_html($data['hours_per_week'] ?: '—') . '</td></tr>
</table>';

    if (!empty($data['skills'])) {
        $html .= '<h2>Skills & Experience</h2><p style="font-size:13px;color:#374151;line-height:1.6;">' . nl2br(esc_html($data['skills'])) . '</p>';
    }
    if (!empty($data['why_volunteer'])) {
        $html .= '<h2>Why They Want to Volunteer</h2><p style="font-size:13px;color:#374151;line-height:1.6;">' . nl2br(esc_html($data['why_volunteer'])) . '</p>';
    }

    $html .= '<h2>Emergency Contact</h2>
<table>
<tr><td>Name</td><td>' . esc_html($data['emergency_name'] ?: '—') . '</td></tr>
<tr><td>Phone</td><td>' . esc_html($data['emergency_phone'] ?: '—') . '</td></tr>
<tr><td>Relationship</td><td>' . esc_html($data['emergency_rel'] ?: '—') . '</td></tr>
</table>
<p style="font-size:12px;color:#9CA3AF;text-align:center;">View all submissions at WP Admin → MYCO Settings → Volunteers</p>
</div>
<div class="foot">' . esc_html($org) . ' Volunteer System</div>
</div></body></html>';

    wp_mail(
        $notify_email,
        '🙋 New Volunteer Registration — ' . $first_name . ' ' . $last_name,
        $html,
        ['Content-Type: text/html; charset=UTF-8', 'From: ' . $org . ' <' . get_option('admin_email') . '>',
         'Reply-To: ' . $first_name . ' ' . $last_name . ' <' . $email . '>']
    );

    wp_send_json_success('Application submitted successfully!');
}

// ═══════════════════════════════════════════════════════════════════════════════
// 4. AJAX: Contact form submission
// ═══════════════════════════════════════════════════════════════════════════════
add_action('wp_ajax_myco_contact_form',        'myco_handle_contact_form');
add_action('wp_ajax_nopriv_myco_contact_form', 'myco_handle_contact_form');

function myco_handle_contact_form(): void {
    if (!wp_verify_nonce(sanitize_text_field($_POST['contact_nonce'] ?? ''), 'myco_contact_nonce')) {
        wp_send_json_error('Security check failed.');
    }

    $first_name = sanitize_text_field($_POST['first_name'] ?? '');
    $last_name  = sanitize_text_field($_POST['last_name'] ?? '');
    $email      = sanitize_email($_POST['email'] ?? '');
    $phone      = sanitize_text_field($_POST['phone'] ?? '');
    $subject    = sanitize_text_field($_POST['subject'] ?? '');
    $message    = sanitize_textarea_field($_POST['message'] ?? '');

    if (empty($first_name) || empty($last_name) || empty($email) || empty($message)) {
        wp_send_json_error('Please fill in all required fields.');
    }

    global $wpdb;
    $wpdb->insert($wpdb->prefix . 'myco_contacts', [
        'first_name' => $first_name,
        'last_name'  => $last_name,
        'email'      => $email,
        'phone'      => $phone,
        'subject'    => $subject,
        'message'    => $message,
        'status'     => 'new',
    ]);

    $notify_email = get_option('myco_contact_notification_email', get_option('admin_email'));
    $org          = get_bloginfo('name');

    $subject_labels = [
        'general'     => 'General Inquiry',
        'programs'    => 'Programs Information',
        'volunteer'   => 'Volunteer Opportunities',
        'donation'    => 'Donation Questions',
        'events'      => 'Events & Activities',
        'partnership' => 'Partnership Opportunities',
        'other'       => 'Other',
    ];
    $subject_nice = $subject_labels[$subject] ?? ucfirst($subject ?: 'General');

    $html = '<!DOCTYPE html><html><head><meta charset="UTF-8">
<style>
body{margin:0;padding:0;font-family:\'Segoe UI\',Arial,sans-serif;background:#F5F6FA;}
.wrap{max-width:560px;margin:30px auto;background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.08);}
.head{background:#141943;padding:28px 34px;text-align:center;}
.head h1{color:#fff;font-size:20px;font-weight:900;margin:0;}
.head p{color:rgba(255,255,255,.55);font-size:12px;margin:5px 0 0;}
.body{padding:30px 34px;}
.badge{display:inline-block;background:#C8402E;color:#fff;font-size:11px;font-weight:700;padding:4px 12px;border-radius:9999px;text-transform:uppercase;letter-spacing:.08em;margin-bottom:14px;}
table{width:100%;border-collapse:collapse;margin-bottom:20px;}
td{padding:8px 0;border-bottom:1px solid #F3F4F6;font-size:13px;vertical-align:top;}
td:first-child{color:#6B7280;font-weight:600;width:35%;}
td:last-child{font-weight:600;color:#141943;}
.msg{background:#F5F6FA;border-radius:12px;padding:18px;font-size:14px;color:#374151;line-height:1.7;margin-bottom:20px;}
.foot{background:#F5F6FA;padding:14px 34px;text-align:center;font-size:11px;color:#9CA3AF;}
</style></head><body>
<div class="wrap">
<div class="head"><h1>' . esc_html($org) . '</h1><p>New Contact Form Message</p></div>
<div class="body">
<span class="badge">' . esc_html($subject_nice) . '</span>
<table>
<tr><td>From</td><td>' . esc_html($first_name . ' ' . $last_name) . '</td></tr>
<tr><td>Email</td><td><a href="mailto:' . esc_attr($email) . '">' . esc_html($email) . '</a></td></tr>
<tr><td>Phone</td><td>' . esc_html($phone ?: '—') . '</td></tr>
</table>
<p style="font-size:12px;color:#6B7280;font-weight:600;margin-bottom:8px;">Message:</p>
<div class="msg">' . nl2br(esc_html($message)) . '</div>
<p style="font-size:12px;color:#9CA3AF;text-align:center;">Reply directly to this email to respond to the sender.</p>
</div>
<div class="foot">' . esc_html($org) . ' Contact System</div>
</div></body></html>';

    wp_mail(
        $notify_email,
        '📩 ' . $subject_nice . ' — from ' . $first_name . ' ' . $last_name,
        $html,
        ['Content-Type: text/html; charset=UTF-8',
         'From: ' . $org . ' <' . get_option('admin_email') . '>',
         'Reply-To: ' . $first_name . ' ' . $last_name . ' <' . $email . '>']
    );

    wp_send_json_success('Message sent successfully!');
}

// ═══════════════════════════════════════════════════════════════════════════════
// 5. Admin submenu: Volunteers
// ═══════════════════════════════════════════════════════════════════════════════
add_action('admin_menu', function () {
    add_submenu_page(
        'myco-settings',
        __('Volunteers', 'myco'),
        __('🙋 Volunteers', 'myco'),
        'manage_options',
        'myco-volunteers',
        'myco_admin_volunteers_page'
    );
    add_submenu_page(
        'myco-settings',
        __('Messages', 'myco'),
        __('📩 Messages', 'myco'),
        'manage_options',
        'myco-messages',
        'myco_admin_messages_page'
    );
});

function myco_admin_volunteers_page(): void {
    if (!current_user_can('manage_options')) return;

    global $wpdb;
    $vols   = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}myco_volunteers ORDER BY created_at DESC LIMIT 200");
    $count  = count($vols);
    ?>
    <div class="wrap">
        <h1 style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
            <span style="background:#C8402E;color:#fff;border-radius:8px;padding:6px 14px;">MYCO</span>
            Volunteer Registrations
            <span style="background:#F0FDF4;color:#16a34a;font-size:13px;padding:4px 12px;border-radius:9999px;font-weight:700;"><?php echo $count; ?> total</span>
        </h1>

        <table class="wp-list-table widefat fixed striped" style="font-size:13px;">
            <thead>
                <tr>
                    <th style="width:50px;">ID</th>
                    <th style="width:110px;">Date</th>
                    <th style="width:140px;">Name</th>
                    <th style="width:160px;">Email</th>
                    <th style="width:100px;">Phone</th>
                    <th>Interests</th>
                    <th style="width:80px;">Hours/Wk</th>
                    <th style="width:70px;">Status</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($vols)): ?>
                <tr><td colspan="8" style="text-align:center;padding:28px;color:#6B7280;">No volunteer registrations yet.</td></tr>
            <?php else: ?>
                <?php foreach ($vols as $v):
                    $date = date('M j, Y', strtotime($v->created_at));
                    $sc   = $v->status === 'new' ? '#16a34a' : ($v->status === 'contacted' ? '#2563eb' : '#6B7280');
                ?>
                <tr>
                    <td><?php echo (int)$v->id; ?></td>
                    <td><?php echo esc_html($date); ?></td>
                    <td><strong><?php echo esc_html($v->first_name . ' ' . $v->last_name); ?></strong></td>
                    <td><a href="mailto:<?php echo esc_attr($v->email); ?>"><?php echo esc_html($v->email); ?></a></td>
                    <td><?php echo esc_html($v->phone ?: '—'); ?></td>
                    <td><?php echo esc_html($v->interests ?: '—'); ?></td>
                    <td><?php echo esc_html($v->hours_per_week ?: '—'); ?></td>
                    <td>
                        <span style="font-size:11px;background:<?php echo $sc; ?>18;color:<?php echo $sc; ?>;padding:2px 8px;border-radius:9999px;font-weight:700;">
                            <?php echo esc_html(ucfirst($v->status)); ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}

// ═══════════════════════════════════════════════════════════════════════════════
// 6. Admin submenu: Messages (Contact form submissions)
// ═══════════════════════════════════════════════════════════════════════════════
function myco_admin_messages_page(): void {
    if (!current_user_can('manage_options')) return;

    global $wpdb;
    $msgs  = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}myco_contacts ORDER BY created_at DESC LIMIT 200");
    $count = count($msgs);

    $subject_labels = [
        'general'=>'General','programs'=>'Programs','volunteer'=>'Volunteer',
        'donation'=>'Donation','events'=>'Events','partnership'=>'Partnership','other'=>'Other',
    ];
    ?>
    <div class="wrap">
        <h1 style="display:flex;align-items:center;gap:10px;margin-bottom:16px;">
            <span style="background:#C8402E;color:#fff;border-radius:8px;padding:6px 14px;">MYCO</span>
            Contact Messages
            <span style="background:#EFF6FF;color:#2563eb;font-size:13px;padding:4px 12px;border-radius:9999px;font-weight:700;"><?php echo $count; ?> total</span>
        </h1>

        <table class="wp-list-table widefat fixed striped" style="font-size:13px;">
            <thead>
                <tr>
                    <th style="width:50px;">ID</th>
                    <th style="width:110px;">Date</th>
                    <th style="width:140px;">From</th>
                    <th style="width:160px;">Email</th>
                    <th style="width:100px;">Subject</th>
                    <th>Message</th>
                    <th style="width:70px;">Status</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($msgs)): ?>
                <tr><td colspan="7" style="text-align:center;padding:28px;color:#6B7280;">No messages yet.</td></tr>
            <?php else: ?>
                <?php foreach ($msgs as $m):
                    $date = date('M j, Y', strtotime($m->created_at));
                    $sc   = $m->status === 'new' ? '#C8402E' : ($m->status === 'replied' ? '#16a34a' : '#6B7280');
                    $subj = $subject_labels[$m->subject] ?? ucfirst($m->subject ?: 'General');
                ?>
                <tr>
                    <td><?php echo (int)$m->id; ?></td>
                    <td><?php echo esc_html($date); ?></td>
                    <td><strong><?php echo esc_html($m->first_name . ' ' . $m->last_name); ?></strong></td>
                    <td><a href="mailto:<?php echo esc_attr($m->email); ?>"><?php echo esc_html($m->email); ?></a></td>
                    <td><span style="font-size:11px;background:#EFF6FF;color:#1d4ed8;padding:2px 8px;border-radius:9999px;font-weight:600;"><?php echo esc_html($subj); ?></span></td>
                    <td title="<?php echo esc_attr($m->message); ?>"><?php echo esc_html(mb_substr($m->message, 0, 80) . (mb_strlen($m->message) > 80 ? '…' : '')); ?></td>
                    <td>
                        <span style="font-size:11px;background:<?php echo $sc; ?>18;color:<?php echo $sc; ?>;padding:2px 8px;border-radius:9999px;font-weight:700;">
                            <?php echo esc_html(ucfirst($m->status)); ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php
}
