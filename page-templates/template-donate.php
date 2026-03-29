<?php
/**
 * Template Name: Donate
 *
 * REDESIGNED: Donate form appears in hero section (first view)
 * Two-column layout: Hero content (left) + Donation form (right)
 * Preserves all Stripe payment functionality
 *
 * @package MYCO
 */

get_header();

/* ------------------------------------------------------------------
   ACF field values with static-content fallback defaults
   ------------------------------------------------------------------ */

// --- Hero Section ---
$hero_badge    = myco_get_field('donate_hero_badge', false, '100% TAX-DEDUCTIBLE');
$hero_subtitle = myco_get_field('donate_hero_subtitle', false, 'Your generosity empowers the next generation of Muslim leaders through mentorship, education, and community programs');
$hero_bg_image = myco_theme_asset_url('assets/images/Construction/Construction Update 1.jpg');

$default_hero_stats = [
    ['number' => '$2.3M+', 'label' => 'Total Raised'],
    ['number' => '5,000+', 'label' => 'Donors'],
    ['number' => '98%',    'label' => 'Impact Score'],
];
$hero_stats = myco_get_field('donate_hero_stats', false, false);
if (!$hero_stats || !is_array($hero_stats)) {
    $hero_stats = $default_hero_stats;
}

// --- Donation Widget ---
$widget_title    = myco_get_field('donate_widget_title', false, 'Make Your Donation');
$widget_subtitle = myco_get_field('donate_widget_subtitle', false, 'Every contribution makes a difference in the lives of Muslim youth');
$fee_percentage  = floatval(myco_get_field('donate_fee_percentage', false, 3.5));

$default_funds = [
    ['value' => 'general',           'label' => 'General Fund - Where Most Needed'],
    ['value' => 'youth-mentorship',  'label' => 'Youth Mentorship Program'],
    ['value' => 'athletics',         'label' => 'Athletics &amp; Sports Programs'],
    ['value' => 'academic',          'label' => 'Academic Support &amp; Tutoring'],
    ['value' => 'leadership',        'label' => 'Leadership Development'],
    ['value' => 'community-service', 'label' => 'Community Service Initiatives'],
    ['value' => 'facility',          'label' => 'Facility Maintenance &amp; Expansion'],
];
$donate_funds = myco_get_field('donate_funds', false, false);
if (!$donate_funds || !is_array($donate_funds)) {
    $donate_funds = $default_funds;
}

$default_amounts = [
    ['amount' => 25],
    ['amount' => 50],
    ['amount' => 100],
    ['amount' => 250],
    ['amount' => 500],
    ['amount' => 1000],
];
$preset_amounts = myco_get_field('donate_preset_amounts', false, false);
if (!$preset_amounts || !is_array($preset_amounts)) {
    $preset_amounts = $default_amounts;
}

// --- Why Give Section ---
$why_label       = myco_get_field('donate_why_label', false, 'Your Impact');
$why_heading     = myco_get_field('donate_why_heading', false, 'Why Give to MYCO?');
$why_description = myco_get_field('donate_why_description', false, 'Your donation creates lasting change in the lives of Muslim youth and strengthens our community');

$default_impact_cards = [
    [
        'title'       => 'Make a Difference',
        'description' => 'Your contribution directly funds mentorship programs, educational resources, and activities that shape the character and future of Muslim youth in our community.',
        'icon'        => '<svg width="44" height="44" viewBox="0 0 44 44" fill="none"><path d="M22 40c9.941 0 18-8.059 18-18S31.941 4 22 4 4 12.059 4 22s8.059 18 18 18Z" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M22 14v8l5.5 5.5" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'stat_amount' => '$50',
        'stat_text'   => 'Provides tutoring for one student for a month',
    ],
    [
        'title'       => 'Be Part of the Solution',
        'description' => 'Join a community of supporters who believe in empowering the next generation. Together, we are building a stronger, more connected Muslim community in Central Ohio.',
        'icon'        => '<svg width="44" height="44" viewBox="0 0 44 44" fill="none"><path d="M22 28c5.523 0 10-4.477 10-10S27.523 8 22 8s-10 4.477-10 10 4.477 10 10 10Z" stroke="#fff" stroke-width="3"/><path d="M6 38c0-8.837 7.163-16 16-16s16 7.163 16 16" stroke="#fff" stroke-width="3" stroke-linecap="round"/><circle cx="34" cy="12" r="6" fill="#fff"/><path d="M34 9v3M34 15v0M31.5 12h5" stroke="#C8402E" stroke-width="2" stroke-linecap="round"/></svg>',
        'stat_amount' => '$100',
        'stat_text'   => 'Sponsors a youth for a sports program',
    ],
    [
        'title'       => 'Inspire Others',
        'description' => 'Your generosity sets an example of giving back and inspires others to support our mission. Every donation, no matter the size, creates a ripple effect of positive change.',
        'icon'        => '<svg width="44" height="44" viewBox="0 0 44 44" fill="none"><path d="M22 4L6 12v10c0 10.046 6.954 19.446 16 22 9.046-2.554 16-11.954 16-22V12L22 4Z" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M16 22l5 5 10-10" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'stat_amount' => '$250',
        'stat_text'   => 'Funds a leadership workshop for 20 youth',
    ],
];
$impact_cards = myco_get_field('donate_impact_cards', false, false);
if (!$impact_cards || !is_array($impact_cards)) {
    $impact_cards = $default_impact_cards;
}

// --- Testimonial ---
$testimonial_quote  = myco_get_field('donate_testimonial_quote', false, 'MYCO changed my life. The mentorship and support I received helped me get into my dream college and become a leader in my community. I\'m forever grateful to the donors who made this possible.');
$testimonial_name   = myco_get_field('donate_testimonial_name', false, 'Ahmad K.');
$testimonial_role   = myco_get_field('donate_testimonial_role', false, 'MYCO Alumni, Class of 2023');
$testimonial_avatar = myco_get_field('donate_testimonial_avatar', false, '');

// --- Other Ways to Give ---
$default_other_ways = [
    [
        'title'       => 'Check / Mail',
        'description' => 'Send a check payable to MYCO to our mailing address. Include your email for a tax receipt.',
        'icon'        => '<svg width="36" height="36" viewBox="0 0 36 36" fill="none"><rect x="3" y="8" width="30" height="20" rx="3" stroke="#C8402E" stroke-width="2.5"/><path d="M3 14h30" stroke="#C8402E" stroke-width="2.5"/><path d="M8 22h8" stroke="#C8402E" stroke-width="2.5" stroke-linecap="round"/></svg>',
    ],
    [
        'title'       => 'Corporate Matching',
        'description' => 'Many employers match charitable donations. Check with your HR department to double your impact.',
        'icon'        => '<svg width="36" height="36" viewBox="0 0 36 36" fill="none"><path d="M18 4L4 12v16h28V12L18 4Z" stroke="#C8402E" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 28V20h8v8" stroke="#C8402E" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
    ],
    [
        'title'       => 'Planned Giving',
        'description' => 'Include MYCO in your estate plan to leave a lasting legacy for future generations of Muslim youth.',
        'icon'        => '<svg width="36" height="36" viewBox="0 0 36 36" fill="none"><path d="M31.06 6.14a8.25 8.25 0 0 0-11.67 0L18 7.53l-1.39-1.39a8.25 8.25 0 1 0-11.67 11.67L6.33 19.2 18 30.87l11.67-11.67 1.39-1.39a8.25 8.25 0 0 0 0-11.67Z" stroke="#C8402E" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
    ],
];
$other_ways = myco_get_field('donate_other_ways', false, false);
if (!$other_ways || !is_array($other_ways)) {
    $other_ways = $default_other_ways;
}

// --- Trust Badges ---
$default_trust_badges = [
    [
        'label' => 'Secure Donation',
        'icon'  => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none"><path d="M16 4L4 10v8c0 7.732 5.268 14.964 12 16 6.732-1.036 12-8.268 12-16v-8L16 4Z" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 16l4 4 8-8" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
    ],
    [
        'label' => 'Registered Nonprofit',
        'icon'  => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none"><path d="M16 4L4 10v8c0 7.732 5.268 14.964 12 16 6.732-1.036 12-8.268 12-16v-8L16 4Z" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 16l4 4 8-8" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
    ],
    [
        'label' => 'Tax Receipt Provided',
        'icon'  => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none"><path d="M16 4L4 10v8c0 7.732 5.268 14.964 12 16 6.732-1.036 12-8.268 12-16v-8L16 4Z" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 16l4 4 8-8" stroke="#10b981" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
    ],
];
$trust_badges = myco_get_field('donate_trust_badges', false, false);
if (!$trust_badges || !is_array($trust_badges)) {
    $trust_badges = $default_trust_badges;
}

$display_trust_badges = array_values(array_filter($trust_badges, static function ($badge) {
    return !empty($badge['label']);
}));

usort($display_trust_badges, static function ($a, $b) {
    $label_a = strtolower((string) ($a['label'] ?? ''));
    $label_b = strtolower((string) ($b['label'] ?? ''));

    $priority = static function (string $label): int {
        if (strpos($label, 'ssl') !== false || strpos($label, 'encrypt') !== false || strpos($label, 'secure') !== false) {
            return 0;
        }
        if (strpos($label, 'tax') !== false || strpos($label, 'deduct') !== false) {
            return 1;
        }
        if (strpos($label, '501') !== false || strpos($label, 'nonprofit') !== false || strpos($label, 'charit') !== false) {
            return 2;
        }
        return 3;
    };

    return $priority($label_a) <=> $priority($label_b);
});

$trust_icon_allowed = [
    'svg'      => ['width' => [], 'height' => [], 'viewBox' => [], 'fill' => [], 'xmlns' => [], 'aria-hidden' => [], 'role' => [], 'class' => []],
    'path'     => ['d' => [], 'stroke' => [], 'stroke-width' => [], 'stroke-linecap' => [], 'stroke-linejoin' => [], 'fill' => []],
    'rect'     => ['x' => [], 'y' => [], 'width' => [], 'height' => [], 'rx' => [], 'fill' => [], 'stroke' => [], 'stroke-width' => []],
    'circle'   => ['cx' => [], 'cy' => [], 'r' => [], 'fill' => [], 'stroke' => [], 'stroke-width' => []],
    'line'     => ['x1' => [], 'y1' => [], 'x2' => [], 'y2' => [], 'stroke' => [], 'stroke-width' => [], 'stroke-linecap' => []],
    'polyline' => ['points' => [], 'stroke' => [], 'stroke-width' => [], 'stroke-linecap' => [], 'stroke-linejoin' => [], 'fill' => []],
];

$render_trust_badge_icon = static function (string $label): string {
    $label = strtolower(trim($label));

    // Tax Deductible — document with lines (matches reference)
    if (strpos($label, 'tax') !== false || strpos($label, 'deduct') !== false) {
        return '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true">'
             . '<path d="M7 3h7l5 5v13H7z" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>'
             . '<path d="M14 3v5h5" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>'
             . '<path d="M10 13h4" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/>'
             . '<path d="M10 17h4" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"/>'
             . '</svg>';
    }

    // 501(c)(3) Nonprofit — building/house icon (matches reference)
    if (strpos($label, '501') !== false || strpos($label, 'nonprofit') !== false || strpos($label, 'charit') !== false) {
        return '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true">'
             . '<path d="M3 10.5L12 4l9 6.5" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>'
             . '<path d="M5 9.5V20h14V9.5" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>'
             . '<path d="M9 20v-6h6v6" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>'
             . '</svg>';
    }

    // SSL Encrypted — padlock (matches reference)
    return '<svg viewBox="0 0 24 24" fill="none" aria-hidden="true">'
         . '<rect x="5" y="11" width="14" height="9" rx="2" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>'
         . '<path d="M8 11V8a4 4 0 1 1 8 0v3" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"/>'
         . '</svg>';
};

// --- Donation Status (from Stripe redirect) ---
$donation_status = isset($_GET['donation']) ? sanitize_text_field(wp_unslash($_GET['donation'])) : '';
?>

<!-- ===============================================================
     SECTION 1 - HERO WITH INTEGRATED DONATE FORM (First View)
     =============================================================== -->
<section class="donate-hero-section w-full relative overflow-hidden" style="background: #141943; display: flex; align-items: center;">
    <div class="absolute inset-0 z-0 overflow-hidden" aria-hidden="true">
        <img src="<?php echo esc_url($hero_bg_image); ?>"
             alt=""
             class="w-full h-full object-cover"
             style="opacity: 0.38; object-position: center 50%; filter: saturate(0.9) contrast(1.05);" />
    </div>
    <div class="absolute inset-0 z-0" style="background: linear-gradient(135deg, rgba(15,21,53,0.78) 0%, rgba(26,37,85,0.72) 50%, rgba(42,62,106,0.68) 100%);" aria-hidden="true"></div>
    
    <!-- Background Decoration -->
    <div class="absolute inset-0 z-0" style="opacity: 0.15;">
        <div style="position: absolute; top: 20%; left: 10%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(200,64,46,0.3) 0%, transparent 70%); border-radius: 50%; filter: blur(60px);"></div>
        <div style="position: absolute; bottom: 20%; right: 10%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%); border-radius: 50%; filter: blur(80px);"></div>
    </div>

    <div class="max-w-[1380px] mx-auto relative z-10 w-full" style="padding-left: clamp(1.25rem, 4vw, 5rem); padding-right: clamp(1.25rem, 4vw, 5rem);">
        
        <!-- Two Column Layout: Content Left, Donate Form Right -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-10 items-start">
            
            <!-- LEFT COLUMN: Hero Content -->
            <div class="flex flex-col justify-center py-4">
                
                <!-- Badge -->
                <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.12); border: 1px solid rgba(255,255,255,0.2); border-radius: 9999px; padding: 8px 20px; margin-bottom: 20px; width: fit-content;">
                    <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
                        <path d="M10 2L2.5 6v5c0 4.694 3.194 9.088 7.5 10 4.306-.912 7.5-5.306 7.5-10V6L10 2Z" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M6 10l3 3 5-5" stroke="#fff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span style="font-size: 13px; font-weight: 600; color: #fff; letter-spacing: 0.02em;">
                        <?php echo esc_html($hero_badge); ?>
                    </span>
                </div>

                <!-- Page Title -->
                <h1 class="font-inter font-black leading-tight tracking-tight text-white mb-4"
                    style="font-size: clamp(2.25rem, 4.5vw, 4rem);">
                    <?php echo esc_html(get_the_title()); ?>
                </h1>

                <!-- Subtitle -->
                <p class="text-gray-300 text-base leading-relaxed mb-6 max-w-xl">
                    <?php echo esc_html($hero_subtitle); ?>
                </p>

                <!-- Stats Row -->
                <div class="donate-hero-stats grid grid-cols-3 gap-5 mb-6">
                    <?php foreach ($hero_stats as $stat) : ?>
                    <div class="text-center lg:text-left">
                        <div class="text-2xl md:text-3xl font-black text-white mb-1">
                            <?php echo esc_html($stat['number']); ?>
                        </div>
                        <div class="text-xs text-blue-200 font-medium">
                            <?php echo esc_html($stat['label']); ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <!-- Trust Indicators -->
                <div class="trust-strip hero-trust-strip">
                    <div class="trust-strip__inner">
                        <?php foreach ($display_trust_badges as $badge) : ?>
                        <div class="trust-item">
                            <span class="trust-icon" aria-hidden="true">
                                <?php echo wp_kses($render_trust_badge_icon($badge['label'] ?? ''), $trust_icon_allowed); ?>
                            </span>
                            <span class="trust-text">
                                <?php echo esc_html($badge['label']); ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN: Donate Form Widget -->
            <div class="flex items-center justify-center lg:justify-end">
                <div id="donation-widget" class="donation-widget w-full" style="max-width: 680px;">

                    <!-- ── Success State ── -->
                    <?php
                    $receipt_token = '';
                    if ($donation_status === 'success') {
                        $pi_id = sanitize_text_field($_GET['payment_intent'] ?? '');
                        if ($pi_id && function_exists('myco_process_successful_payment')) {
                            $receipt_token = myco_process_successful_payment($pi_id);
                        }
                    }
                    ?>
                    <div id="donation-success" class="donation-success" style="<?php echo $donation_status === 'success' ? '' : 'display: none;'; ?>">
                        <svg width="64" height="64" viewBox="0 0 64 64" fill="none" style="margin: 0 auto 24px; display:block;">
                            <circle cx="32" cy="32" r="30" stroke="#16a34a" stroke-width="3"/>
                            <path d="M20 32l10 10 14-14" stroke="#16a34a" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <h2 style="font-size: 24px; font-weight: 900; color: #141943; margin-bottom: 12px; text-align: center;">
                            <?php esc_html_e('Thank You!', 'myco'); ?>
                        </h2>
                        <p style="font-size: 15px; color: #5B6575; line-height: 1.65; text-align: center; margin-bottom: 20px;">
                            <?php esc_html_e('Your generous contribution will help empower Muslim youth.', 'myco'); ?>
                        </p>
                        <?php if ($receipt_token) : ?>
                        <a href="<?php echo esc_url(home_url('/?myco_receipt=' . $receipt_token)); ?>"
                           target="_blank"
                           class="btn-secondary" style="display:block;text-align:center;margin-bottom:12px;">
                            <?php esc_html_e('Download Receipt', 'myco'); ?>
                        </a>
                        <?php endif; ?>
                        <a href="<?php echo esc_url(get_permalink()); ?>" class="btn-primary" style="display:block;text-align:center;">
                            <?php esc_html_e('Make Another Donation', 'myco'); ?>
                        </a>
                    </div>

                    <!-- ── Cancel State ── -->
                    <div id="donation-cancel" class="donation-cancelled" style="<?php echo $donation_status === 'cancelled' ? '' : 'display: none;'; ?>">
                        <svg width="48" height="48" viewBox="0 0 48 48" fill="none" style="margin: 0 auto 20px; display:block;">
                            <circle cx="24" cy="24" r="22" stroke="#EF4444" stroke-width="3"/>
                            <path d="M16 16l16 16M32 16L16 32" stroke="#EF4444" stroke-width="3" stroke-linecap="round"/>
                        </svg>
                        <h3 style="font-size: 20px; font-weight: 800; color: #141943; margin-bottom: 10px; text-align: center;">
                            <?php esc_html_e('Donation Cancelled', 'myco'); ?>
                        </h3>
                        <p style="font-size: 14px; color: #5B6575; line-height: 1.65; margin-bottom: 24px; text-align: center;">
                            <?php esc_html_e('No donation was processed. You can update your details and try again below.', 'myco'); ?>
                        </p>
                    </div>

                    <!-- ── Main form ── -->
                    <div id="donation-form" style="<?php echo ($donation_status === 'success') ? 'display: none;' : ''; ?>">
                        <?php wp_nonce_field('myco_donate_nonce', 'donate_nonce'); ?>

                        <div id="donation-step-1">

                            <!-- Widget Header: Title left, Tabs right -->
                            <div class="dw-header">
                                <div>
                                    <h2 class="dw-title"><?php echo esc_html($widget_title); ?></h2>
                                    <p class="dw-subtitle"><?php echo esc_html($widget_subtitle); ?></p>
                                </div>
                                <div class="dw-tabs">
                                    <button type="button" class="dw-tab active" id="one-time-tab" data-type="one-time"><?php esc_html_e('One-time', 'myco'); ?></button>
                                    <button type="button" class="dw-tab" id="monthly-tab" data-type="monthly"><?php esc_html_e('Monthly', 'myco'); ?></button>
                                </div>
                            </div>

                            <!-- Two Column Body -->
                            <div class="donation-form-grid">

                                <!-- LEFT: Amounts + Fund + Fees -->
                                <div class="donation-form-panel">

                                    <p class="dw-label"><?php esc_html_e('SELECT AMOUNT', 'myco'); ?></p>
                                    <div class="dw-amounts">
                                        <?php foreach ($preset_amounts as $preset) :
                                            $raw = intval($preset['amount']); ?>
                                        <button type="button" class="amt-btn" data-amount="<?php echo esc_attr($raw); ?>">$<?php echo number_format($raw); ?></button>
                                        <?php endforeach; ?>
                                    </div>

                                    <p class="dw-label" style="margin-top:14px;"><?php esc_html_e('CUSTOM AMOUNT', 'myco'); ?></p>
                                    <div class="dw-custom-wrap">
                                        <span class="dw-dollar">$</span>
                                        <input type="number" id="custom-amount" name="custom_amount" class="dw-input"
                                               placeholder="<?php esc_attr_e('Enter amount', 'myco'); ?>"
                                               min="1" step="0.01" />
                                    </div>

                                    <p class="dw-label" style="margin-top:14px;"><?php esc_html_e('SELECT FUND', 'myco'); ?></p>
                                    <select id="fund-select" name="fund" class="dw-select">
                                        <?php foreach ($donate_funds as $fund_item) : ?>
                                        <option value="<?php echo esc_attr($fund_item['value']); ?>"><?php echo esc_html($fund_item['label']); ?></option>
                                        <?php endforeach; ?>
                                    </select>

                                    <div class="dw-fee-card">
                                        <label class="dw-fee-label">
                                            <input type="checkbox" id="cover-fees" name="cover_fees" value="1" checked />
                                            <span class="dw-fee-text">
                                                <strong><?php esc_html_e('Support processing costs', 'myco'); ?></strong>
                                                <span>(+<?php echo esc_html($fee_percentage); ?>%) — <?php esc_html_e('so 100% goes to MYCO', 'myco'); ?></span>
                                            </span>
                                        </label>
                                    </div>

                                </div><!-- /LEFT -->

                                <!-- RIGHT: Summary + Email + Button -->
                                <div class="donation-side-panel">

                                    <div id="donation-summary" class="dw-summary">
                                        <p class="dw-label"><?php esc_html_e('SUMMARY', 'myco'); ?></p>
                                        <div class="dw-summary-row">
                                            <span><?php esc_html_e('Donation:', 'myco'); ?></span>
                                            <strong id="summary-amount">$0.00</strong>
                                        </div>
                                        <div class="dw-summary-row" id="fee-row">
                                            <span><?php printf(esc_html__('Processing (%s%%):', 'myco'), esc_html($fee_percentage)); ?></span>
                                            <span id="summary-fees" class="dw-fee-val">$0.00</span>
                                        </div>
                                        <div class="dw-summary-total">
                                            <span><?php esc_html_e('Total:', 'myco'); ?></span>
                                            <span id="summary-total">$0.00</span>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="dw-email-label" for="donor-email">
                                            <?php esc_html_e('Email', 'myco'); ?> <span class="dw-optional">— <?php esc_html_e('optional', 'myco'); ?></span>
                                        </label>
                                        <input type="email" id="donor-email" name="donor_email" class="dw-input"
                                               placeholder="<?php esc_attr_e('your@email.com', 'myco'); ?>"
                                               autocomplete="email" />
                                        <p class="dw-hint"><?php esc_html_e('For your tax receipt', 'myco'); ?></p>
                                    </div>

                                    <div id="monthly-notice" class="dw-monthly-notice" style="display:none;">
                                        <strong><?php esc_html_e('Monthly', 'myco'); ?></strong> — <?php esc_html_e('Renews each month', 'myco'); ?>
                                    </div>

                                    <button type="button" id="complete-donation" class="dw-cta" disabled>
                                        <?php esc_html_e('Continue to Donate', 'myco'); ?>
                                    </button>

                                    <p class="dw-secure">🔒 <?php esc_html_e('Secure processing by Stripe', 'myco'); ?></p>

                                </div><!-- /RIGHT -->

                            </div><!-- /grid -->

                        </div><!-- /#donation-step-1 -->

                        <!-- ═══════════════════════════════════════════════
                             STEP 2 — Stripe Donation Processing
                        ═══════════════════════════════════════════════ -->
                        <div id="donation-step-2" class="donation-payment-step" style="display:none;">

                            <!-- Back link -->
                            <button type="button" id="back-to-form" style="background:none;border:none;cursor:pointer;color:#C8402E;font-size:13px;font-weight:600;margin-bottom:20px;padding:0;display:flex;align-items:center;gap:6px;">
                                <svg width="14" height="14" viewBox="0 0 20 20" fill="none"><path d="M15 10H5m0 0l4-4m-4 4l4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                <?php esc_html_e('Back to donation details', 'myco'); ?>
                            </button>

                            <!-- Donation summary pill -->
                            <div class="donation-payment-summary">
                                <div>
                                    <div style="font-size:11px;font-weight:600;color:#6B7280;text-transform:uppercase;letter-spacing:.06em;margin-bottom:2px;">
                                        <?php esc_html_e('Your Donation', 'myco'); ?>
                                    </div>
                                    <div id="s2-fund" style="font-size:13px;color:#374151;font-weight:600;"></div>
                                    <div id="s2-type" style="font-size:11px;color:#9CA3AF;"></div>
                                </div>
                                <div id="s2-amount" style="font-size:24px;font-weight:900;color:#C8402E;"></div>
                            </div>

                            <!-- Stripe Payment Element mounts here -->
                            <div class="donation-payment-frame">
                                <div id="payment-element" style="min-height:180px;"></div>
                            </div>

                            <!-- Error message -->
                            <div id="payment-error" style="display:none;background:#FEF2F2;border:1px solid #FECACA;border-radius:8px;padding:12px 14px;margin-bottom:16px;color:#DC2626;font-size:13px;font-weight:500;"></div>

                            <!-- Complete Donation Button -->
                            <button type="button" id="complete-donation-btn" class="pill-primary" style="width:100%;font-size:16px;padding:14px;">
                                <?php esc_html_e('Confirm Donation', 'myco'); ?>
                            </button>

                            <p style="font-size:11px;color:#9CA3AF;text-align:center;margin-top:12px;">
                                🔒 <?php esc_html_e('Encrypted and secured by Stripe', 'myco'); ?>
                            </p>

                        </div><!-- /#donation-step-2 -->

                    </div><!-- /#donation-form -->

                </div><!-- /#donation-widget -->
            </div>

        </div><!-- /grid -->

    </div>
</section>

<!-- ===============================================================
     SECTION 2 - WHY GIVE (Impact Cards)
     =============================================================== -->
<section class="donate-why-section w-full section-bg-gray" style="padding: 100px 0; position: relative;">
    <div class="inner mx-auto px-4">

        <!-- Section Header -->
        <div style="text-align: center; margin-bottom: 70px;">
            <p style="font-size: 15px; font-weight: 700; color: #C8402E; margin-bottom: 12px; letter-spacing: 0.02em;">
                <?php echo esc_html($why_label); ?>
            </p>
            <h2 class="font-inter font-black leading-tight tracking-tight" style="font-size: clamp(2rem, 4.5vw, 3.625rem); color: #141943; margin-bottom: 20px;">
                <?php echo esc_html($why_heading); ?>
            </h2>
            <p style="font-size: 19px; color: #5B6575; line-height: 1.65; max-width: 720px; margin: 0 auto;">
                <?php echo esc_html($why_description); ?>
            </p>
        </div>

        <!-- Impact Cards Grid -->
        <div class="impact-cards" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px;">
            <?php foreach ($impact_cards as $card) : ?>
            <div class="impact-card">

                <!-- Icon -->
                <div class="impact-icon" style="width: 80px; height: 80px; border-radius: 20px; background: linear-gradient(135deg, #C8402E 0%, #e05040 100%); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                    <?php
                    if (!empty($card['icon'])) {
                        echo wp_kses($card['icon'], [
                            'svg'    => ['width' => [], 'height' => [], 'viewBox' => [], 'fill' => [], 'class' => []],
                            'path'   => ['d' => [], 'stroke' => [], 'stroke-width' => [], 'stroke-linecap' => [], 'stroke-linejoin' => [], 'fill' => []],
                            'circle' => ['cx' => [], 'cy' => [], 'r' => [], 'stroke' => [], 'stroke-width' => [], 'fill' => []],
                            'rect'   => ['x' => [], 'y' => [], 'width' => [], 'height' => [], 'rx' => [], 'stroke' => [], 'stroke-width' => [], 'fill' => []],
                        ]);
                    }
                    ?>
                </div>

                <!-- Title -->
                <h3 style="font-size: 26px; font-weight: 800; color: #141943; margin-bottom: 16px; line-height: 1.2;">
                    <?php echo esc_html($card['title']); ?>
                </h3>

                <!-- Description -->
                <p style="font-size: 16px; color: #5B6575; line-height: 1.75; margin-bottom: 24px;">
                    <?php echo esc_html($card['description']); ?>
                </p>

                <!-- Impact Stat -->
                <!-- <?php if (!empty($card['stat_amount'])) : ?>
                <div style="padding: 20px; background: rgba(200,64,46,0.08); border-radius: 12px; text-align: left; margin-top: auto;">
                    <div style="font-size: 32px; font-weight: 900; color: #C8402E; margin-bottom: 6px;">
                        <?php echo esc_html($card['stat_amount']); ?>
                    </div>
                    <div style="font-size: 14px; color: #374151; font-weight: 600;">
                        <?php echo esc_html($card['stat_text']); ?>
                    </div>
                </div>
                <?php endif; ?> -->

            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- ===============================================================
     SECTION 3 - DONOR TESTIMONIAL
     =============================================================== -->
<section class="donate-testimonial-section w-full relative overflow-hidden" style="background: linear-gradient(135deg, #141943 0%, #1e2a5a 100%); padding: 100px 0;">
    <!-- Wave decoration -->
    <div aria-hidden="true" class="absolute inset-0 pointer-events-none" style="opacity: 0.06; background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='1920' height='400' fill='none'%3E%3Cpath d='M-60 80 C400 -20 800 180 1300 60 S1700 -40 1980 80' stroke='white' stroke-width='1.2'/%3E%3Cpath d='M-60 160 C400 60 800 260 1300 140 S1700 40 1980 160' stroke='white' stroke-width='1.2'/%3E%3Cpath d='M-60 240 C400 140 800 340 1300 220 S1700 120 1980 240' stroke='white' stroke-width='1.2'/%3E%3Cpath d='M-60 320 C400 220 800 420 1300 300 S1700 200 1980 320' stroke='white' stroke-width='1.2'/%3E%3C/svg%3E&quot;); background-size: 1920px 400px; background-repeat: no-repeat;"></div>

    <div class="inner mx-auto px-4 relative z-10">
        <div style="max-width: 900px; margin: 0 auto; text-align: center;">

            <!-- Quote Icon -->
            <svg width="64" height="64" viewBox="0 0 64 64" fill="none" style="margin: 0 auto 32px; opacity: 0.3;">
                <path d="M12 40c-4.418 0-8-3.582-8-8V16c0-4.418 3.582-8 8-8h8v16h-8v8h8v8h-8ZM44 40c-4.418 0-8-3.582-8-8V16c0-4.418 3.582-8 8-8h8v16h-8v8h8v8h-8Z" fill="#fff"/>
            </svg>

            <!-- Quote Text -->
            <p style="font-size: clamp(1.25rem, 2vw, 1.75rem); color: #ffffff; line-height: 1.6; font-weight: 500; margin-bottom: 32px; font-style: italic;">
                &ldquo;<?php echo esc_html($testimonial_quote); ?>&rdquo;
            </p>

            <!-- Author -->
            <div class="donate-testimonial-author" style="display: flex; align-items: center; justify-content: center; gap: 16px;">
                <?php if ($testimonial_avatar) : ?>
                <img src="<?php echo esc_url($testimonial_avatar); ?>"
                     alt="<?php echo esc_attr($testimonial_name); ?>"
                     style="width: 64px; height: 64px; border-radius: 50%; object-fit: cover; object-position: top; border: 2px solid rgba(255,255,255,0.35); flex-shrink: 0;" />
                <?php else : ?>
                <img src="<?php echo esc_url(myco_theme_asset_url('assets/images/volunteers.jpg')); ?>"
                     alt="<?php echo esc_attr($testimonial_name); ?>"
                     style="width: 64px; height: 64px; border-radius: 50%; object-fit: cover; object-position: top center; border: 2px solid rgba(255,255,255,0.35); flex-shrink: 0;" />
                <?php endif; ?>
                <div style="text-align: left;">
                    <div style="font-size: 18px; font-weight: 700; color: #fff; margin-bottom: 4px;">
                        <?php echo esc_html($testimonial_name); ?>
                    </div>
                    <div style="font-size: 14px; color: rgba(255,255,255,0.7); font-weight: 500;">
                        <?php echo esc_html($testimonial_role); ?>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ===============================================================
     SECTION 4 - OTHER WAYS TO GIVE
     =============================================================== -->
<section class="donate-other-ways-section w-full" style="background: #ffffff; padding: 100px 0; position: relative;">
    <div class="inner mx-auto px-4">

        <!-- Section Header -->
        <div style="text-align: center; margin-bottom: 60px;">
            <p style="font-size: 15px; font-weight: 700; color: #C8402E; margin-bottom: 12px; letter-spacing: 0.02em;">
                <?php esc_html_e('More Options', 'myco'); ?>
            </p>
            <h2 class="font-inter font-black leading-tight tracking-tight" style="font-size: clamp(2rem, 4.5vw, 3.25rem); color: #141943; margin-bottom: 18px;">
                <?php esc_html_e('Other Ways to Give', 'myco'); ?>
            </h2>
            <p style="font-size: 18px; color: #5B6575; line-height: 1.65; max-width: 680px; margin: 0 auto;">
                <?php esc_html_e('Prefer a different way to support our mission? Here are additional options to make your impact.', 'myco'); ?>
            </p>
        </div>

        <!-- Method Cards -->
        <div class="donate-other-ways-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; max-width: 1200px; margin: 0 auto;">
            <?php foreach ($other_ways as $way) : ?>
            <div style="background: #ffffff; border-radius: 20px; padding: 40px 32px; box-shadow: 0 8px 24px rgba(20,25,67,0.08); border: 1px solid rgba(20,25,67,0.07); transition: transform .25s, box-shadow .25s; text-align: center;"
                 onmouseover="this.style.transform='translateY(-6px)';this.style.boxShadow='0 16px 40px rgba(20,25,67,0.14)'"
                 onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 24px rgba(20,25,67,0.08)'">

                <!-- Icon -->
                <div style="width: 72px; height: 72px; border-radius: 18px; background: linear-gradient(135deg, rgba(200,64,46,0.08) 0%, rgba(200,64,46,0.16) 100%); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                    <?php
                    if (!empty($way['icon'])) {
                        echo wp_kses($way['icon'], [
                            'svg'    => ['width' => [], 'height' => [], 'viewBox' => [], 'fill' => [], 'class' => []],
                            'path'   => ['d' => [], 'stroke' => [], 'stroke-width' => [], 'stroke-linecap' => [], 'stroke-linejoin' => [], 'fill' => []],
                            'circle' => ['cx' => [], 'cy' => [], 'r' => [], 'stroke' => [], 'stroke-width' => [], 'fill' => []],
                            'rect'   => ['x' => [], 'y' => [], 'width' => [], 'height' => [], 'rx' => [], 'stroke' => [], 'stroke-width' => [], 'fill' => []],
                        ]);
                    }
                    ?>
                </div>

                <!-- Title -->
                <h3 style="font-size: 22px; font-weight: 800; color: #141943; margin-bottom: 14px;">
                    <?php echo esc_html($way['title']); ?>
                </h3>

                <!-- Description -->
                <p style="font-size: 15px; color: #5B6575; line-height: 1.7;">
                    <?php echo esc_html($way['description']); ?>
                </p>

            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- ===============================================================
     SECTION 5 - TRUST BADGES
     =============================================================== -->
<!-- <section class="donate-trust-section">
    <div class="inner">
        <div class="trust-strip">
            <div class="trust-strip__inner">
                <?php foreach ($display_trust_badges as $badge) : ?>
                <div class="trust-item">
                    <span class="trust-icon" aria-hidden="true">
                        <?php echo wp_kses($render_trust_badge_icon($badge['label'] ?? ''), $trust_icon_allowed); ?>
                    </span>
                    <span class="trust-text">
                        <?php echo esc_html($badge['label']); ?>
                    </span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section> -->

<?php get_footer(); ?>
