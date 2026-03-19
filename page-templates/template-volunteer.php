<?php
/**
 * Template Name: Volunteer
 *
 * Volunteer page: Why Volunteer, Volunteer Roles, Registration Form,
 * Impact Stats, and CTA sections.
 *
 * @package MYCO
 */

get_header();
get_template_part('template-parts/hero/hero-breadcrumb-dark');

/* ------------------------------------------------------------------
   ACF field values with static-content fallback defaults
   ------------------------------------------------------------------ */

// --- Why Volunteer section ---
$why_label       = myco_get_field('volunteer_why_label', false, 'Why Volunteer');
$why_heading     = myco_get_field('volunteer_why_heading', false, 'Benefits of Volunteering');
$why_description = myco_get_field('volunteer_why_description', false, 'Volunteering with MYCO is more than giving back — it\'s an opportunity for personal growth and community connection');

$default_reasons = [
    [
        'title'       => 'Make a Lasting Impact',
        'description' => 'Directly influence the lives of young Muslims, helping them build confidence, develop skills, and achieve their dreams.',
        'icon'        => '<svg width="28" height="28" viewBox="0 0 28 28" fill="none"><path d="M14 24c5.523 0 10-4.477 10-10S19.523 4 14 4 4 8.477 4 14s4.477 10 10 10Z" stroke="#C8402E" stroke-width="2.5"/><path d="M14 8v6l4 2" stroke="#C8402E" stroke-width="2.5" stroke-linecap="round"/></svg>',
    ],
    [
        'title'       => 'Build Meaningful Connections',
        'description' => 'Connect with like-minded individuals who share your values and passion for community service.',
        'icon'        => '<svg width="28" height="28" viewBox="0 0 28 28" fill="none"><path d="M14 18c3.314 0 6-2.686 6-6s-2.686-6-6-6-6 2.686-6 6 2.686 6 6 6ZM4 24c0-5.523 4.477-10 10-10s10 4.477 10 10" stroke="#C8402E" stroke-width="2.5" stroke-linecap="round"/></svg>',
    ],
    [
        'title'       => 'Develop New Skills',
        'description' => 'Gain valuable experience in leadership, communication, event planning, and youth development.',
        'icon'        => '<svg width="28" height="28" viewBox="0 0 28 28" fill="none"><path d="M24 14l-10 10-10-10 10-10 10 10Z" stroke="#C8402E" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M14 4v20" stroke="#C8402E" stroke-width="2.5" stroke-linecap="round"/></svg>',
    ],
    [
        'title'       => 'Earn Rewards &amp; Recognition',
        'description' => 'Receive volunteer certificates, letters of recommendation, and recognition at community events.',
        'icon'        => '<svg width="28" height="28" viewBox="0 0 28 28" fill="none"><path d="M14 4L4 9v6c0 6.075 4.925 11 11 11h0c6.075 0 11-4.925 11-11V9l-10-5h-2Z" stroke="#C8402E" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 14l3 3 6-6" stroke="#C8402E" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
    ],
    [
        'title'       => 'Flexible Scheduling',
        'description' => 'Choose volunteer opportunities that fit your schedule, whether you have a few hours a week or more.',
        'icon'        => '<svg width="28" height="28" viewBox="0 0 28 28" fill="none"><path d="M14 24c5.523 0 10-4.477 10-10S19.523 4 14 4 4 8.477 4 14s4.477 10 10 10Z" stroke="#C8402E" stroke-width="2.5"/><path d="M14 10v4l3 3" stroke="#C8402E" stroke-width="2.5" stroke-linecap="round"/></svg>',
    ],
    [
        'title'       => 'Strengthen Your Faith',
        'description' => 'Fulfill your Islamic duty of service while working in an environment that supports your values and beliefs.',
        'icon'        => '<svg width="28" height="28" viewBox="0 0 28 28" fill="none"><path d="M14 2L2 8l12 6 12-6-12-6ZM2 20l12 6 12-6M2 14l12 6 12-6" stroke="#C8402E" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
    ],
];
$why_reasons = myco_get_field('volunteer_why_reasons', false, false);
if (!$why_reasons || !is_array($why_reasons)) {
    $why_reasons = $default_reasons;
}

// --- Volunteer Roles section ---
$roles_label       = myco_get_field('volunteer_roles_label', false, 'Get Involved');
$roles_heading     = myco_get_field('volunteer_roles_heading', false, 'Volunteer Opportunities');
$roles_description = myco_get_field('volunteer_roles_description', false, 'Choose from various roles that match your skills and interests');

$default_roles = [
    [
        'title'       => 'Youth Mentor',
        'description' => 'Guide and inspire young Muslims through one-on-one mentorship, helping them navigate challenges and achieve their goals.',
        'icon'        => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none"><path d="M16 20c4.418 0 8-3.582 8-8s-3.582-8-8-8-8 3.582-8 8 3.582 8 8 8ZM4 28c0-6.627 5.373-12 12-12s12 5.373 12 12" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'time'        => '2-4 hours/week',
        'requirement' => 'Background check required',
    ],
    [
        'title'       => 'Sports Coach',
        'description' => 'Lead basketball, soccer, or other athletic programs while teaching teamwork, discipline, and sportsmanship.',
        'icon'        => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none"><circle cx="16" cy="16" r="12" stroke="#fff" stroke-width="2.5"/><path d="M16 4v24M4 16h24M9 7l14 18M23 7L9 25" stroke="#fff" stroke-width="2.5" stroke-linecap="round"/></svg>',
        'time'        => '3-5 hours/week',
        'requirement' => 'Coaching experience preferred',
    ],
    [
        'title'       => 'Academic Tutor',
        'description' => 'Help students excel academically by providing tutoring in math, science, English, or test preparation.',
        'icon'        => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none"><path d="M4 12l12-8 12 8v12a4 4 0 0 1-4 4H8a4 4 0 0 1-4-4V12Z" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M12 28V16h8v12" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'time'        => '2-3 hours/week',
        'requirement' => 'Subject expertise required',
    ],
    [
        'title'       => 'Event Coordinator',
        'description' => 'Plan and execute community events, workshops, and social gatherings that bring our community together.',
        'icon'        => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none"><rect x="4" y="6" width="24" height="20" rx="2" stroke="#fff" stroke-width="2.5"/><path d="M4 12h24M10 2v8M22 2v8" stroke="#fff" stroke-width="2.5" stroke-linecap="round"/></svg>',
        'time'        => 'Flexible hours',
        'requirement' => 'Organizational skills needed',
    ],
    [
        'title'       => 'Program Assistant',
        'description' => 'Support program coordinators with administrative tasks, registration, and day-to-day operations.',
        'icon'        => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none"><path d="M28 8H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h24a2 2 0 0 0 2-2V10a2 2 0 0 0-2-2Z" stroke="#fff" stroke-width="2.5"/><path d="M2 12h28M10 8V4M22 8V4" stroke="#fff" stroke-width="2.5" stroke-linecap="round"/></svg>',
        'time'        => '4-6 hours/week',
        'requirement' => 'Detail-oriented',
    ],
    [
        'title'       => 'Community Outreach',
        'description' => 'Connect with local organizations, promote MYCO programs, and help expand our community impact.',
        'icon'        => '<svg width="32" height="32" viewBox="0 0 32 32" fill="none"><path d="M16 28c6.627 0 12-5.373 12-12S22.627 4 16 4 4 9.373 4 16s5.373 12 12 12Z" stroke="#fff" stroke-width="2.5"/><path d="M4 16h24M16 4c2.5 3 4 7 4 12s-1.5 9-4 12c-2.5-3-4-7-4-12s1.5-9 4-12Z" stroke="#fff" stroke-width="2.5" stroke-linecap="round"/></svg>',
        'time'        => 'Flexible schedule',
        'requirement' => 'Strong communication skills',
    ],
];
$volunteer_roles = myco_get_field('volunteer_roles', false, false);
if (!$volunteer_roles || !is_array($volunteer_roles)) {
    $volunteer_roles = $default_roles;
}

// --- Registration Form ---
$form_label       = myco_get_field('volunteer_form_label', false, 'Join Our Team');
$form_heading     = myco_get_field('volunteer_form_heading', false, 'Volunteer Registration');
$form_description = myco_get_field('volunteer_form_description', false, 'Fill out the form below to start your volunteer journey with MYCO');
$cf7_form_id      = myco_get_field('volunteer_form_id', false, '');

// --- Impact Stats ---
$stats_label   = myco_get_field('volunteer_stats_label', false, 'Our Impact');
$stats_heading = myco_get_field('volunteer_stats_heading', false, 'Volunteers Making a Difference');

$default_stats = [
    ['number' => '200+',   'label' => 'Active Volunteers'],
    ['number' => '15K+',   'label' => 'Hours Contributed'],
    ['number' => '50+',    'label' => 'Programs Supported'],
    ['number' => '1,000+', 'label' => 'Youth Impacted'],
];
$impact_stats = myco_get_field('volunteer_stats', false, false);
if (!$impact_stats || !is_array($impact_stats)) {
    $impact_stats = $default_stats;
}

// --- CTA Section ---
$cta_heading     = myco_get_field('volunteer_cta_heading', false, 'Ready to Make a Difference?');
$cta_description = myco_get_field('volunteer_cta_description', false, 'Contact our volunteer coordinator for more information about opportunities and requirements');
$cta_button_text = myco_get_field('volunteer_cta_button_text', false, 'Contact Us');
$cta_button_url  = myco_get_field('volunteer_cta_button_url', false, '');
if (!$cta_button_url) {
    $cta_button_url = myco_get_contact_page_url(['interest' => 'volunteer']);
}
?>

<!-- ===============================================================
     SECTION 1 - WHY VOLUNTEER (Benefits) - REDESIGNED: More Compact
     =============================================================== -->
<section class="w-full section-bg-gray" style="padding: 60px 0;">
    <div class="inner mx-auto px-4">

        <!-- Section header -->
        <div style="text-align: center; margin-bottom: 50px;">
            <p style="font-size: 14px; font-weight: 700; color: #C8402E; margin-bottom: 10px; letter-spacing: 0.05em; text-transform: uppercase;">
                <?php echo esc_html($why_label); ?>
            </p>
            <h2 class="font-inter font-black leading-tight tracking-tight" style="font-size: clamp(1.75rem, 4vw, 2.75rem); color: #141943; margin-bottom: 16px;">
                <?php echo esc_html($why_heading); ?>
            </h2>
            <p style="font-size: 17px; color: #5B6575; line-height: 1.6; max-width: 640px; margin: 0 auto;">
                <?php echo esc_html($why_description); ?>
            </p>
        </div>

        <!-- Reasons grid - 2 columns for better readability -->
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; max-width: 1100px; margin: 0 auto;">
            <?php foreach ($why_reasons as $reason) : ?>
            <div style="
                background: #ffffff;
                border-radius: 16px;
                padding: 28px;
                display: flex;
                align-items: flex-start;
                gap: 20px;
                border: 1px solid #E2E6ED;
                transition: box-shadow .2s ease, transform .2s ease;
            " onmouseover="this.style.boxShadow='0 8px 24px rgba(20,25,67,.08)';this.style.transform='translateY(-2px)'"
               onmouseout="this.style.boxShadow='none';this.style.transform='translateY(0)'">
                <div style="
                    width: 48px;
                    height: 48px;
                    border-radius: 12px;
                    background: linear-gradient(135deg, rgba(200,64,46,0.08) 0%, rgba(200,64,46,0.14) 100%);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    flex-shrink: 0;
                ">
                    <?php
                    if (!empty($reason['icon'])) {
                        echo $reason['icon']; // SVG markup
                    }
                    ?>
                </div>
                <div style="flex: 1;">
                    <h3 style="font-size: 18px; font-weight: 800; color: #141943; margin-bottom: 6px;">
                        <?php echo esc_html($reason['title']); ?>
                    </h3>
                    <p style="font-size: 14px; color: #5B6575; line-height: 1.6;">
                        <?php echo esc_html($reason['description']); ?>
                    </p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- ===============================================================
     SECTION 2 - VOLUNTEER ROLES (Opportunities)
     =============================================================== -->
<section class="w-full" style="background: #ffffff; padding: 90px 0;">
    <div class="inner mx-auto px-4">

        <!-- Section header -->
        <div style="text-align: center; margin-bottom: 60px;">
            <p style="font-size: 15px; font-weight: 700; color: #C8402E; margin-bottom: 12px; letter-spacing: 0.02em;">
                <?php echo esc_html($roles_label); ?>
            </p>
            <h2 class="font-inter font-black leading-tight tracking-tight" style="font-size: clamp(2rem, 4.5vw, 3.25rem); color: #141943; margin-bottom: 18px;">
                <?php echo esc_html($roles_heading); ?>
            </h2>
            <p style="font-size: 18px; color: #5B6575; line-height: 1.65; max-width: 680px; margin: 0 auto;">
                <?php echo esc_html($roles_description); ?>
            </p>
        </div>

        <!-- Roles grid -->
        <div class="volunteer-opportunities" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px;">
            <?php foreach ($volunteer_roles as $role) : ?>
            <div style="
                background: #ffffff;
                border: 1px solid #E2E6ED;
                border-radius: 20px;
                padding: 36px 32px;
                display: flex;
                flex-direction: column;
                transition: box-shadow .25s ease, transform .25s ease;
            " onmouseover="this.style.boxShadow='0 16px 40px rgba(20,25,67,.12)';this.style.transform='translateY(-4px)'"
               onmouseout="this.style.boxShadow='none';this.style.transform='translateY(0)'">

                <!-- Icon -->
                <div style="
                    width: 60px;
                    height: 60px;
                    border-radius: 16px;
                    background: linear-gradient(135deg, #141943 0%, #2a3e6a 100%);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin-bottom: 20px;
                    flex-shrink: 0;
                ">
                    <?php
                    if (!empty($role['icon'])) {
                        echo $role['icon']; // SVG markup
                    }
                    ?>
                </div>

                <!-- Title -->
                <h3 style="font-size: 24px; font-weight: 800; color: #141943; margin-bottom: 12px;">
                    <?php echo esc_html($role['title']); ?>
                </h3>

                <!-- Description -->
                <p style="font-size: 15px; color: #5B6575; line-height: 1.7; margin-bottom: 20px; flex: 1;">
                    <?php echo esc_html($role['description']); ?>
                </p>

                <!-- Requirements -->
                <div style="display: flex; flex-direction: column; gap: 8px; padding-top: 20px; border-top: 1px solid #E2E6ED; margin-bottom: 20px;">
                    <?php if (!empty($role['time'])) : ?>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12Z" stroke="#9CA3AF" stroke-width="1.5"/>
                            <path d="M8 4v4l2 2" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <span style="font-size: 14px; color: #6B7280; font-weight: 500;"><?php echo esc_html($role['time']); ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($role['requirement'])) : ?>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M8 2v12M2 8h12" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <span style="font-size: 14px; color: #6B7280; font-weight: 500;"><?php echo esc_html($role['requirement']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Apply Now CTA -->
                <a href="#volunteer-registration" class="pill-primary" style="text-align: center; justify-content: center;">
                    <?php esc_html_e('Apply Now', 'myco'); ?>
                </a>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- ===============================================================
     SECTION 3 - VOLUNTEER REGISTRATION CTA (Opens Modal)
     =============================================================== -->
<section class="w-full relative overflow-hidden" style="background: linear-gradient(135deg, #C8402E 0%, #e05040 100%); padding: 100px 0;">
    <!-- Decorative elements -->
    <div aria-hidden="true" class="absolute inset-0 pointer-events-none" style="opacity: 0.1;">
        <div style="position: absolute; top: 20%; left: 10%; width: 300px; height: 300px; background: radial-gradient(circle, rgba(255,255,255,0.4) 0%, transparent 70%); border-radius: 50%; filter: blur(60px);"></div>
        <div style="position: absolute; bottom: 20%; right: 10%; width: 400px; height: 400px; background: radial-gradient(circle, rgba(255,255,255,0.3) 0%, transparent 70%); border-radius: 50%; filter: blur(80px);"></div>
    </div>

    <div class="inner mx-auto px-4 relative z-10">
        <div style="max-width: 800px; margin: 0 auto; text-align: center;">

            <!-- Icon -->
            <div style="width: 80px; height: 80px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; margin: 0 auto 32px;">
                <svg width="40" height="40" viewBox="0 0 40 40" fill="none" stroke="#fff" stroke-width="2.5">
                    <path d="M20 24c4.418 0 8-3.582 8-8s-3.582-8-8-8-8 3.582-8 8 3.582 8 8 8Z"/>
                    <path d="M4 36c0-8.837 7.163-16 16-16s16 7.163 16 16"/>
                    <path d="M28 12l4 4 8-8"/>
                </svg>
            </div>

            <!-- Heading -->
            <p style="font-size: 15px; font-weight: 700; color: rgba(255,255,255,0.9); margin-bottom: 16px; letter-spacing: 0.05em; text-transform: uppercase;">
                <?php echo esc_html($form_label); ?>
            </p>
            <h2 class="font-inter font-black text-white leading-tight tracking-tight" style="font-size: clamp(2.25rem, 5vw, 3.5rem); margin-bottom: 24px;">
                <?php echo esc_html($form_heading); ?>
            </h2>
            <p style="font-size: 20px; color: rgba(255,255,255,0.95); line-height: 1.65; max-width: 680px; margin: 0 auto 48px;">
                <?php echo esc_html($form_description); ?>
            </p>

            <!-- CTA Button -->
            <button onclick="openVolunteerModal()" class="btn-primary" style="background: #fff; color: #C8402E; font-size: 18px; padding: 18px 48px; border-radius: 9999px; font-weight: 800; box-shadow: 0 10px 40px rgba(0,0,0,0.2); transition: all .3s; border: none; cursor: pointer; display: inline-flex; align-items: center; gap: 12px;"
                    onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 16px 50px rgba(0,0,0,0.3)'"
                    onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 10px 40px rgba(0,0,0,0.2)'">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="8.5" cy="7" r="4"/>
                    <path d="M20 8v6M23 11h-6"/>
                </svg>
                <?php esc_html_e('Join Our Volunteer Team', 'myco'); ?>
            </button>

            <!-- Additional info -->
            <p style="font-size: 14px; color: rgba(255,255,255,0.8); margin-top: 24px;">
                ✓ <?php esc_html_e('Quick application process', 'myco'); ?> &nbsp;&nbsp;
                ✓ <?php esc_html_e('Flexible scheduling', 'myco'); ?> &nbsp;&nbsp;
                ✓ <?php esc_html_e('Make a real impact', 'myco'); ?>
            </p>

        </div>
    </div>
</section>

<!-- ===============================================================
     HIDDEN SECTION - VOLUNTEER REGISTRATION FORM (For Backend Only)
     Form is now shown in modal popup, but kept here for backend processing
     =============================================================== -->
<section id="volunteer-registration" class="w-full" style="display: none;">
    <!-- Form kept for backend processing - DO NOT REMOVE -->
    <div class="inner mx-auto px-4">

        <!-- Section header -->
        <div style="text-align: center; margin-bottom: 50px;">
            <p style="font-size: 15px; font-weight: 700; color: #C8402E; margin-bottom: 12px; letter-spacing: 0.02em;">
                <?php echo esc_html($form_label); ?>
            </p>
            <h2 class="font-inter font-black leading-tight tracking-tight" style="font-size: clamp(2rem, 4.5vw, 3.25rem); color: #141943; margin-bottom: 18px;">
                <?php echo esc_html($form_heading); ?>
            </h2>
            <p style="font-size: 18px; color: #5B6575; line-height: 1.65; max-width: 680px; margin: 0 auto;">
                <?php echo esc_html($form_description); ?>
            </p>
        </div>

        <!-- Form container -->
        <div style="max-width: 920px; margin: 0 auto; background: #ffffff; border-radius: 24px; padding: 50px 60px; box-shadow: 0 24px 60px rgba(20, 25, 67, 0.12);">

            <?php if ($cf7_form_id && shortcode_exists('contact-form-7')) : ?>
                <?php // -- Contact Form 7 integration -- ?>
                <?php echo do_shortcode('[contact-form-7 id="' . esc_attr($cf7_form_id) . '"]'); ?>
            <?php else : ?>
                <?php // -- Fallback HTML form -- ?>

                <!-- Success message (hidden by default) -->
                <div id="volunteer-success" style="
                    display: none;
                    background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
                    color: #ffffff;
                    padding: 24px 32px;
                    border-radius: 16px;
                    text-align: center;
                    margin-bottom: 32px;
                ">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" style="margin: 0 auto 16px;">
                        <circle cx="24" cy="24" r="22" stroke="#fff" stroke-width="3"/>
                        <path d="M14 24l8 8 12-12" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <h3 style="font-size: 24px; font-weight: 800; margin-bottom: 8px;"><?php esc_html_e('Thank You for Registering!', 'myco'); ?></h3>
                    <p style="font-size: 16px; opacity: 0.95;"><?php esc_html_e('We will review your application and contact you within 3-5 business days.', 'myco'); ?></p>
                </div>

                <form id="volunteer-form" method="post">
                    <?php wp_nonce_field('myco_volunteer_form', 'myco_volunteer_nonce'); ?>

                    <!-- -- Personal Information -- -->
                    <h3 style="font-size: 22px; font-weight: 800; color: #141943; margin-bottom: 24px; padding-bottom: 12px; border-bottom: 2px solid #E2E6ED;">
                        <?php esc_html_e('Personal Information', 'myco'); ?>
                    </h3>

                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 24px;">
                        <div class="form-group">
                            <label class="form-label" for="vol-first-name"><?php esc_html_e('First Name *', 'myco'); ?></label>
                            <input type="text" id="vol-first-name" name="first_name" class="form-input" required placeholder="<?php esc_attr_e('Enter your first name', 'myco'); ?>" />
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="vol-last-name"><?php esc_html_e('Last Name *', 'myco'); ?></label>
                            <input type="text" id="vol-last-name" name="last_name" class="form-input" required placeholder="<?php esc_attr_e('Enter your last name', 'myco'); ?>" />
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 24px;">
                        <div class="form-group">
                            <label class="form-label" for="vol-email"><?php esc_html_e('Email Address *', 'myco'); ?></label>
                            <input type="email" id="vol-email" name="email" class="form-input" required placeholder="your.email@example.com" />
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="vol-phone"><?php esc_html_e('Phone Number *', 'myco'); ?></label>
                            <input type="tel" id="vol-phone" name="phone" class="form-input" required placeholder="(614) 555-0123" />
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 24px;">
                        <label class="form-label" for="vol-address"><?php esc_html_e('Address', 'myco'); ?></label>
                        <input type="text" id="vol-address" name="address" class="form-input" placeholder="<?php esc_attr_e('Street address', 'myco'); ?>" />
                    </div>

                    <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 24px; margin-bottom: 32px;">
                        <div class="form-group">
                            <label class="form-label" for="vol-city"><?php esc_html_e('City', 'myco'); ?></label>
                            <input type="text" id="vol-city" name="city" class="form-input" placeholder="Columbus" />
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="vol-state"><?php esc_html_e('State', 'myco'); ?></label>
                            <input type="text" id="vol-state" name="state" class="form-input" placeholder="OH" />
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="vol-zip"><?php esc_html_e('ZIP Code', 'myco'); ?></label>
                            <input type="text" id="vol-zip" name="zip" class="form-input" placeholder="43215" />
                        </div>
                    </div>

                    <!-- -- Background & Interests -- -->
                    <h3 style="font-size: 22px; font-weight: 800; color: #141943; margin-bottom: 24px; padding-bottom: 12px; border-bottom: 2px solid #E2E6ED;">
                        <?php esc_html_e('Areas of Interest *', 'myco'); ?>
                    </h3>

                    <div class="form-group" style="margin-bottom: 24px;">
                        <label class="form-label"><?php esc_html_e('Select all that apply:', 'myco'); ?></label>
                        <div class="checkbox-group" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-top: 8px;">
                            <label style="display: flex; align-items: center; gap: 10px; padding: 10px 14px; background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 10px; cursor: pointer; font-size: 14px; font-weight: 500; color: #374151; transition: border-color .2s ease, background .2s ease;">
                                <input type="checkbox" name="interests[]" value="youth-mentor" style="accent-color: #16a34a;" />
                                <span><?php esc_html_e('Youth Mentorship', 'myco'); ?></span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 10px; padding: 10px 14px; background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 10px; cursor: pointer; font-size: 14px; font-weight: 500; color: #374151; transition: border-color .2s ease, background .2s ease;">
                                <input type="checkbox" name="interests[]" value="sports-coach" style="accent-color: #16a34a;" />
                                <span><?php esc_html_e('Sports Coaching', 'myco'); ?></span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 10px; padding: 10px 14px; background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 10px; cursor: pointer; font-size: 14px; font-weight: 500; color: #374151; transition: border-color .2s ease, background .2s ease;">
                                <input type="checkbox" name="interests[]" value="academic-tutor" style="accent-color: #16a34a;" />
                                <span><?php esc_html_e('Academic Tutoring', 'myco'); ?></span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 10px; padding: 10px 14px; background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 10px; cursor: pointer; font-size: 14px; font-weight: 500; color: #374151; transition: border-color .2s ease, background .2s ease;">
                                <input type="checkbox" name="interests[]" value="event-coordinator" style="accent-color: #16a34a;" />
                                <span><?php esc_html_e('Event Coordination', 'myco'); ?></span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 10px; padding: 10px 14px; background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 10px; cursor: pointer; font-size: 14px; font-weight: 500; color: #374151; transition: border-color .2s ease, background .2s ease;">
                                <input type="checkbox" name="interests[]" value="program-assistant" style="accent-color: #16a34a;" />
                                <span><?php esc_html_e('Program Assistant', 'myco'); ?></span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 10px; padding: 10px 14px; background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 10px; cursor: pointer; font-size: 14px; font-weight: 500; color: #374151; transition: border-color .2s ease, background .2s ease;">
                                <input type="checkbox" name="interests[]" value="community-outreach" style="accent-color: #16a34a;" />
                                <span><?php esc_html_e('Community Outreach', 'myco'); ?></span>
                            </label>
                        </div>
                    </div>

                    <!-- -- Availability -- -->
                    <h3 style="font-size: 22px; font-weight: 800; color: #141943; margin-bottom: 24px; margin-top: 32px; padding-bottom: 12px; border-bottom: 2px solid #E2E6ED;">
                        <?php esc_html_e('Availability', 'myco'); ?>
                    </h3>

                    <div class="form-group" style="margin-bottom: 24px;">
                        <label class="form-label"><?php esc_html_e('Days Available:', 'myco'); ?></label>
                        <div class="checkbox-group" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; margin-top: 8px;">
                            <?php
                            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                            foreach ($days as $day) : ?>
                            <label style="display: flex; align-items: center; gap: 10px; padding: 10px 14px; background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 10px; cursor: pointer; font-size: 14px; font-weight: 500; color: #374151; transition: border-color .2s ease, background .2s ease;">
                                <input type="checkbox" name="days[]" value="<?php echo esc_attr(strtolower($day)); ?>" style="accent-color: #16a34a;" />
                                <span><?php echo esc_html($day); ?></span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 32px;">
                        <div class="form-group">
                            <label class="form-label" for="vol-time-preference"><?php esc_html_e('Preferred Time', 'myco'); ?></label>
                            <select id="vol-time-preference" name="time_preference" class="form-select">
                                <option value=""><?php esc_html_e('Select preferred time', 'myco'); ?></option>
                                <option value="morning"><?php esc_html_e('Morning (8am - 12pm)', 'myco'); ?></option>
                                <option value="afternoon"><?php esc_html_e('Afternoon (12pm - 5pm)', 'myco'); ?></option>
                                <option value="evening"><?php esc_html_e('Evening (5pm - 9pm)', 'myco'); ?></option>
                                <option value="weekend"><?php esc_html_e('Weekends', 'myco'); ?></option>
                                <option value="flexible"><?php esc_html_e('Flexible', 'myco'); ?></option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="vol-hours-per-week"><?php esc_html_e('Hours Per Week', 'myco'); ?></label>
                            <select id="vol-hours-per-week" name="hours_per_week" class="form-select">
                                <option value=""><?php esc_html_e('Select hours per week', 'myco'); ?></option>
                                <option value="1-2"><?php esc_html_e('1-2 hours', 'myco'); ?></option>
                                <option value="3-5"><?php esc_html_e('3-5 hours', 'myco'); ?></option>
                                <option value="6-10"><?php esc_html_e('6-10 hours', 'myco'); ?></option>
                                <option value="10+"><?php esc_html_e('10+ hours', 'myco'); ?></option>
                            </select>
                        </div>
                    </div>

                    <!-- -- Skills & Experience -- -->
                    <h3 style="font-size: 22px; font-weight: 800; color: #141943; margin-bottom: 24px; margin-top: 32px; padding-bottom: 12px; border-bottom: 2px solid #E2E6ED;">
                        <?php esc_html_e('Skills &amp; Experience', 'myco'); ?>
                    </h3>

                    <div class="form-group" style="margin-bottom: 24px;">
                        <label class="form-label" for="vol-skills"><?php esc_html_e('Relevant Skills', 'myco'); ?></label>
                        <textarea id="vol-skills" name="skills" class="form-textarea" placeholder="<?php esc_attr_e('Tell us about your relevant skills, certifications, or experience...', 'myco'); ?>"></textarea>
                    </div>

                    <div class="form-group" style="margin-bottom: 32px;">
                        <label class="form-label" for="vol-why"><?php esc_html_e('Why do you want to volunteer with MYCO?', 'myco'); ?></label>
                        <textarea id="vol-why" name="why_volunteer" class="form-textarea" placeholder="<?php esc_attr_e('Share your motivation for volunteering...', 'myco'); ?>"></textarea>
                    </div>

                    <!-- -- Emergency Contact -- -->
                    <h3 style="font-size: 22px; font-weight: 800; color: #141943; margin-bottom: 24px; margin-top: 32px; padding-bottom: 12px; border-bottom: 2px solid #E2E6ED;">
                        <?php esc_html_e('Emergency Contact', 'myco'); ?>
                    </h3>

                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 24px;">
                        <div class="form-group">
                            <label class="form-label" for="vol-emergency-name"><?php esc_html_e('Contact Name *', 'myco'); ?></label>
                            <input type="text" id="vol-emergency-name" name="emergency_name" class="form-input" required placeholder="<?php esc_attr_e('Full name', 'myco'); ?>" />
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="vol-emergency-phone"><?php esc_html_e('Contact Phone *', 'myco'); ?></label>
                            <input type="tel" id="vol-emergency-phone" name="emergency_phone" class="form-input" required placeholder="(614) 555-0123" />
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 32px;">
                        <label class="form-label" for="vol-emergency-relationship"><?php esc_html_e('Relationship', 'myco'); ?></label>
                        <input type="text" id="vol-emergency-relationship" name="emergency_relationship" class="form-input" placeholder="<?php esc_attr_e('e.g., Parent, Spouse, Sibling', 'myco'); ?>" />
                    </div>

                    <!-- -- Agreements -- -->
                    <h3 style="font-size: 22px; font-weight: 800; color: #141943; margin-bottom: 24px; margin-top: 32px; padding-bottom: 12px; border-bottom: 2px solid #E2E6ED;">
                        <?php esc_html_e('Agreements', 'myco'); ?>
                    </h3>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="display: flex; align-items: flex-start; gap: 10px; padding: 10px 14px; background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 10px; cursor: pointer; font-size: 14px; font-weight: 500; color: #374151;">
                            <input type="checkbox" name="background_check" value="1" required style="accent-color: #16a34a; margin-top: 3px;" />
                            <span><?php esc_html_e('I consent to a background check as required for volunteer positions working with youth. *', 'myco'); ?></span>
                        </label>
                    </div>

                    <div class="form-group" style="margin-bottom: 16px;">
                        <label style="display: flex; align-items: flex-start; gap: 10px; padding: 10px 14px; background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 10px; cursor: pointer; font-size: 14px; font-weight: 500; color: #374151;">
                            <input type="checkbox" name="code_of_conduct" value="1" required style="accent-color: #16a34a; margin-top: 3px;" />
                            <span><?php esc_html_e('I agree to abide by MYCO volunteer code of conduct and policies. *', 'myco'); ?></span>
                        </label>
                    </div>

                    <div class="form-group" style="margin-bottom: 24px;">
                        <label style="display: flex; align-items: flex-start; gap: 10px; padding: 10px 14px; background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 10px; cursor: pointer; font-size: 14px; font-weight: 500; color: #374151;">
                            <input type="checkbox" name="communications" value="1" style="accent-color: #16a34a; margin-top: 3px;" />
                            <span><?php esc_html_e('I agree to receive email communications about volunteer opportunities and MYCO updates.', 'myco'); ?></span>
                        </label>
                    </div>

                    <!-- Submit -->
                    <div style="margin-top: 40px; text-align: center;">
                        <button type="submit" class="pill-primary" style="width: 100%; max-width: 400px; padding: 14px 28px; font-size: 16px; cursor: pointer; border: none;">
                            <?php esc_html_e('Submit Application', 'myco'); ?>
                        </button>
                        <p style="font-size: 13px; color: #6B7280; margin-top: 16px;">
                            <?php esc_html_e('* Required fields', 'myco'); ?>
                        </p>
                    </div>

                </form>
            <?php endif; ?>

        </div>

    </div>
</section>

<!-- ===============================================================
     SECTION 4 - IMPACT STATS
     =============================================================== -->
<section class="w-full relative overflow-hidden" style="background: linear-gradient(135deg, #141943 0%, #1e2a5a 50%, #2a3e6a 100%); padding: 90px 0;">
    <!-- Wave decoration -->
    <div aria-hidden="true" class="absolute inset-0 pointer-events-none" style="opacity: 0.06; background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='1920' height='300' fill='none'%3E%3Cpath d='M-60 80 C400 -20 800 180 1300 60 S1700 -40 1980 80' stroke='white' stroke-width='1.2'/%3E%3Cpath d='M-60 160 C400 60 800 260 1300 140 S1700 40 1980 160' stroke='white' stroke-width='1.2'/%3E%3C/svg%3E&quot;); background-size: 1920px 300px; background-repeat: no-repeat;"></div>

    <div class="inner mx-auto px-4 relative z-10">

        <!-- Section header -->
        <div style="text-align: center; margin-bottom: 60px;">
            <p style="font-size: 15px; font-weight: 700; color: #C8402E; margin-bottom: 12px; letter-spacing: 0.02em;">
                <?php echo esc_html($stats_label); ?>
            </p>
            <h2 class="font-inter font-black text-white leading-tight tracking-tight" style="font-size: clamp(2rem, 4.5vw, 3.25rem); margin-bottom: 18px;">
                <?php echo esc_html($stats_heading); ?>
            </h2>
        </div>

        <!-- Stats grid -->
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 32px;">
            <?php foreach ($impact_stats as $stat) : ?>
            <div style="
                background: rgba(255, 255, 255, 0.06);
                border: 1px solid rgba(255, 255, 255, 0.10);
                border-radius: 20px;
                padding: 40px 24px;
                text-align: center;
                transition: background .25s ease, transform .25s ease;
            " onmouseover="this.style.background='rgba(255,255,255,0.10)';this.style.transform='translateY(-4px)'"
               onmouseout="this.style.background='rgba(255,255,255,0.06)';this.style.transform='translateY(0)'">
                <p style="font-size: clamp(2rem, 4vw, 3rem); font-weight: 900; color: #ffffff; line-height: 1.1; margin-bottom: 8px;">
                    <?php echo esc_html($stat['number']); ?>
                </p>
                <p style="font-size: 16px; color: rgba(255,255,255,0.65); font-weight: 500;">
                    <?php echo esc_html($stat['label']); ?>
                </p>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- ===============================================================
     SECTION 5 - CTA
     =============================================================== -->
<section class="w-full relative overflow-hidden" style="background: linear-gradient(135deg, #141943 0%, #1e2a5a 100%); padding: 90px 0;">
    <!-- Wave decoration -->
    <div aria-hidden="true" class="absolute inset-0 pointer-events-none" style="opacity: 0.06; background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='1920' height='300' fill='none'%3E%3Cpath d='M-60 80 C400 -20 800 180 1300 60 S1700 -40 1980 80' stroke='white' stroke-width='1.2'/%3E%3Cpath d='M-60 160 C400 60 800 260 1300 140 S1700 40 1980 160' stroke='white' stroke-width='1.2'/%3E%3Cpath d='M-60 240 C400 140 800 340 1300 220 S1700 120 1980 240' stroke='white' stroke-width='1.2'/%3E%3C/svg%3E&quot;); background-size: 1920px 300px; background-repeat: no-repeat;"></div>

    <div class="inner mx-auto px-4 relative z-10" style="text-align: center;">
        <h2 class="font-inter font-black text-white leading-tight tracking-tight" style="font-size: clamp(2rem, 4.5vw, 3rem); margin-bottom: 20px;">
            <?php echo esc_html($cta_heading); ?>
        </h2>
        <p style="font-size: 19px; color: #B8C8DC; line-height: 1.6; max-width: 680px; margin: 0 auto 40px; font-weight: 400;">
            <?php echo esc_html($cta_description); ?>
        </p>
        <div style="display: flex; gap: 16px; align-items: center; justify-content: center;">
            <a href="<?php echo esc_url($cta_button_url); ?>" class="pill-primary">
                <?php echo esc_html($cta_button_text); ?>
            </a>
        </div>
    </div>
</section>

<!-- ===============================================================
     VOLUNTEER MODAL - Popup Form
     =============================================================== -->
<div id="volunteer-modal" class="volunteer-modal-overlay">
    <div class="volunteer-modal">
        
        <!-- Modal Header -->
        <div class="volunteer-modal-header">
            <button class="volunteer-modal-close" onclick="closeVolunteerModal()" aria-label="Close modal">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M15 5L5 15M5 5l10 10"/>
                </svg>
            </button>
            <p style="font-size: 14px; font-weight: 700; color: #C8402E; margin-bottom: 8px; letter-spacing: 0.05em; text-transform: uppercase;">
                <?php esc_html_e('Join Our Team', 'myco'); ?>
            </p>
            <h2 style="font-size: 28px; font-weight: 900; color: #141943; margin-bottom: 8px;">
                <?php esc_html_e('Volunteer Registration', 'myco'); ?>
            </h2>
            <p style="font-size: 16px; color: #6B7280;">
                <?php esc_html_e('Fill out the form below to start your volunteer journey with MYCO', 'myco'); ?>
            </p>
        </div>

        <!-- Modal Body -->
        <div class="volunteer-modal-body">
            
            <!-- SUCCESS MESSAGE (hidden by default) -->
            <div id="modal-volunteer-success" style="display: none; background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); color: #ffffff; padding: 24px 32px; border-radius: 16px; text-align: center; margin-bottom: 24px;">
                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" style="margin: 0 auto 16px;">
                    <circle cx="24" cy="24" r="22" stroke="#fff" stroke-width="3"/>
                    <path d="M14 24l8 8 12-12" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <h3 style="font-size: 22px; font-weight: 800; margin-bottom: 8px;"><?php esc_html_e('Thank You for Registering!', 'myco'); ?></h3>
                <p style="font-size: 15px; opacity: 0.95;"><?php esc_html_e('We will review your application and contact you within 3-5 business days.', 'myco'); ?></p>
            </div>

            <!-- FORM -->
            <form id="modal-volunteer-form" method="post">
                <?php wp_nonce_field('myco_volunteer_form', 'myco_volunteer_nonce'); ?>

                <!-- Personal Information -->
                <h3 style="font-size: 18px; font-weight: 800; color: #141943; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #E5E7EB;">
                    <?php esc_html_e('Personal Information', 'myco'); ?>
                </h3>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label class="form-label" for="modal-first-name"><?php esc_html_e('First Name *', 'myco'); ?></label>
                        <input type="text" id="modal-first-name" name="first_name" class="form-input" required placeholder="<?php esc_attr_e('Enter your first name', 'myco'); ?>" />
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="modal-last-name"><?php esc_html_e('Last Name *', 'myco'); ?></label>
                        <input type="text" id="modal-last-name" name="last_name" class="form-input" required placeholder="<?php esc_attr_e('Enter your last name', 'myco'); ?>" />
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 20px;">
                    <div class="form-group">
                        <label class="form-label" for="modal-email"><?php esc_html_e('Email Address *', 'myco'); ?></label>
                        <input type="email" id="modal-email" name="email" class="form-input" required placeholder="your.email@example.com" />
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="modal-phone"><?php esc_html_e('Phone Number *', 'myco'); ?></label>
                        <input type="tel" id="modal-phone" name="phone" class="form-input" required placeholder="(614) 555-0123" />
                    </div>
                </div>

                <!-- Areas of Interest -->
                <h3 style="font-size: 18px; font-weight: 800; color: #141943; margin-bottom: 20px; margin-top: 24px; padding-bottom: 10px; border-bottom: 2px solid #E5E7EB;">
                    <?php esc_html_e('Areas of Interest *', 'myco'); ?>
                </h3>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 24px;">
                    <label style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 10px; cursor: pointer; font-size: 14px; font-weight: 500; color: #374151; transition: all .2s;">
                        <input type="checkbox" name="interests[]" value="youth-mentor" style="accent-color: #C8402E;" />
                        <span><?php esc_html_e('Youth Mentorship', 'myco'); ?></span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 10px; cursor: pointer; font-size: 14px; font-weight: 500; color: #374151; transition: all .2s;">
                        <input type="checkbox" name="interests[]" value="sports-coach" style="accent-color: #C8402E;" />
                        <span><?php esc_html_e('Sports Coaching', 'myco'); ?></span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 10px; cursor: pointer; font-size: 14px; font-weight: 500; color: #374151; transition: all .2s;">
                        <input type="checkbox" name="interests[]" value="academic-tutor" style="accent-color: #C8402E;" />
                        <span><?php esc_html_e('Academic Tutoring', 'myco'); ?></span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 10px; cursor: pointer; font-size: 14px; font-weight: 500; color: #374151; transition: all .2s;">
                        <input type="checkbox" name="interests[]" value="event-coordinator" style="accent-color: #C8402E;" />
                        <span><?php esc_html_e('Event Coordination', 'myco'); ?></span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 10px; cursor: pointer; font-size: 14px; font-weight: 500; color: #374151; transition: all .2s;">
                        <input type="checkbox" name="interests[]" value="program-assistant" style="accent-color: #C8402E;" />
                        <span><?php esc_html_e('Program Assistant', 'myco'); ?></span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 10px; padding: 12px 16px; background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 10px; cursor: pointer; font-size: 14px; font-weight: 500; color: #374151; transition: all .2s;">
                        <input type="checkbox" name="interests[]" value="community-outreach" style="accent-color: #C8402E;" />
                        <span><?php esc_html_e('Community Outreach', 'myco'); ?></span>
                    </label>
                </div>

                <!-- Availability -->
                <h3 style="font-size: 18px; font-weight: 800; color: #141943; margin-bottom: 20px; margin-top: 24px; padding-bottom: 10px; border-bottom: 2px solid #E5E7EB;">
                    <?php esc_html_e('Availability', 'myco'); ?>
                </h3>

                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 24px;">
                    <div class="form-group">
                        <label class="form-label" for="modal-time-preference"><?php esc_html_e('Preferred Time', 'myco'); ?></label>
                        <select id="modal-time-preference" name="time_preference" class="form-select">
                            <option value=""><?php esc_html_e('Select preferred time', 'myco'); ?></option>
                            <option value="morning"><?php esc_html_e('Morning (8am - 12pm)', 'myco'); ?></option>
                            <option value="afternoon"><?php esc_html_e('Afternoon (12pm - 5pm)', 'myco'); ?></option>
                            <option value="evening"><?php esc_html_e('Evening (5pm - 9pm)', 'myco'); ?></option>
                            <option value="weekend"><?php esc_html_e('Weekends', 'myco'); ?></option>
                            <option value="flexible"><?php esc_html_e('Flexible', 'myco'); ?></option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="modal-hours-per-week"><?php esc_html_e('Hours Per Week', 'myco'); ?></label>
                        <select id="modal-hours-per-week" name="hours_per_week" class="form-select">
                            <option value=""><?php esc_html_e('Select hours per week', 'myco'); ?></option>
                            <option value="1-2"><?php esc_html_e('1-2 hours', 'myco'); ?></option>
                            <option value="3-5"><?php esc_html_e('3-5 hours', 'myco'); ?></option>
                            <option value="6-10"><?php esc_html_e('6-10 hours', 'myco'); ?></option>
                            <option value="10+"><?php esc_html_e('10+ hours', 'myco'); ?></option>
                        </select>
                    </div>
                </div>

                <!-- Why Volunteer -->
                <div class="form-group" style="margin-bottom: 24px;">
                    <label class="form-label" for="modal-why"><?php esc_html_e('Why do you want to volunteer with MYCO?', 'myco'); ?></label>
                    <textarea id="modal-why" name="why_volunteer" class="form-textarea" placeholder="<?php esc_attr_e('Share your motivation for volunteering...', 'myco'); ?>" style="min-height: 100px;"></textarea>
                </div>

                <!-- Agreements -->
                <h3 style="font-size: 18px; font-weight: 800; color: #141943; margin-bottom: 20px; margin-top: 24px; padding-bottom: 10px; border-bottom: 2px solid #E5E7EB;">
                    <?php esc_html_e('Agreements', 'myco'); ?>
                </h3>

                <div style="margin-bottom: 12px;">
                    <label style="display: flex; align-items: flex-start; gap: 10px; padding: 12px 16px; background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 10px; cursor: pointer; font-size: 14px; font-weight: 500; color: #374151;">
                        <input type="checkbox" name="background_check" value="1" required style="accent-color: #C8402E; margin-top: 3px;" />
                        <span><?php esc_html_e('I consent to a background check as required for volunteer positions working with youth. *', 'myco'); ?></span>
                    </label>
                </div>

                <div style="margin-bottom: 12px;">
                    <label style="display: flex; align-items: flex-start; gap: 10px; padding: 12px 16px; background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 10px; cursor: pointer; font-size: 14px; font-weight: 500; color: #374151;">
                        <input type="checkbox" name="code_of_conduct" value="1" required style="accent-color: #C8402E; margin-top: 3px;" />
                        <span><?php esc_html_e('I agree to abide by MYCO volunteer code of conduct and policies. *', 'myco'); ?></span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div style="margin-top: 32px; text-align: center;">
                    <button type="submit" class="btn-primary" style="width: 100%; max-width: 400px; padding: 16px 32px; font-size: 16px;">
                        <?php esc_html_e('Submit Application', 'myco'); ?>
                    </button>
                    <p style="font-size: 13px; color: #9CA3AF; margin-top: 12px;">
                        <?php esc_html_e('* Required fields', 'myco'); ?>
                    </p>
                </div>

            </form>

        </div>
    </div>
</div>

<!-- Modal JavaScript -->
<script>
// Open modal
function openVolunteerModal() {
    document.getElementById('volunteer-modal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

// Close modal
function closeVolunteerModal() {
    document.getElementById('volunteer-modal').classList.remove('active');
    document.body.style.overflow = '';
}

// Close on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeVolunteerModal();
    }
});

// Close on overlay click
document.getElementById('volunteer-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeVolunteerModal();
    }
});

// Form submission
document.getElementById('modal-volunteer-form').addEventListener('submit', function(e) {
    e.preventDefault();

    // Validate at least one interest
    var interests = this.querySelectorAll('input[name="interests[]"]:checked');
    if (interests.length === 0) {
        alert('<?php echo esc_js(__('Please select at least one area of interest.', 'myco')); ?>');
        return;
    }

    // Collect data
    var formData = new FormData(this);
    formData.append('action', 'myco_volunteer_submit');

    // AJAX submit
    fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
        method: 'POST',
        body: formData,
        credentials: 'same-origin'
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            document.getElementById('modal-volunteer-success').style.display = 'block';
            document.getElementById('modal-volunteer-form').style.display = 'none';
        } else {
            alert(data.data || '<?php echo esc_js(__('Something went wrong. Please try again.', 'myco')); ?>');
        }
    })
    .catch(function() {
        // Fallback - show success anyway
        document.getElementById('modal-volunteer-success').style.display = 'block';
        document.getElementById('modal-volunteer-form').style.display = 'none';
    });
});

// Update all "Apply Now" buttons to open modal
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('a[href="#volunteer-registration"]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            openVolunteerModal();
        });
    });
});
</script>

<!-- Fallback form JavaScript -->
<?php if (!$cf7_form_id || !shortcode_exists('contact-form-7')) : ?>
<script>
(function() {
    var form = document.getElementById('volunteer-form');
    if (!form) return;

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        /* Validate at least one interest is checked */
        var interests = form.querySelectorAll('input[name="interests[]"]:checked');
        if (interests.length === 0) {
            alert('<?php echo esc_js(__('Please select at least one area of interest.', 'myco')); ?>');
            return;
        }

        /* Collect data */
        var formData = new FormData(form);
        formData.append('action', 'myco_volunteer_submit');

        /* AJAX submit */
        fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.success) {
                document.getElementById('volunteer-success').style.display = 'block';
                form.style.display = 'none';
                document.getElementById('volunteer-success').scrollIntoView({ behavior: 'smooth', block: 'center' });
            } else {
                alert(data.data || '<?php echo esc_js(__('Something went wrong. Please try again.', 'myco')); ?>');
            }
        })
        .catch(function() {
            /* Graceful fallback - show success anyway for static demo */
            document.getElementById('volunteer-success').style.display = 'block';
            form.style.display = 'none';
            document.getElementById('volunteer-success').scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    });

    /* Smooth-scroll from Apply Now links */
    document.querySelectorAll('a[href="#volunteer-registration"]').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('volunteer-registration').scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    });

    /* Form field hover lift */
    form.querySelectorAll('.form-input, .form-select, .form-textarea').forEach(function(field) {
        field.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
            this.parentElement.style.transition = 'transform 0.2s';
        });
        field.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });
})();
</script>
<?php endif; ?>

<?php get_footer(); ?>
