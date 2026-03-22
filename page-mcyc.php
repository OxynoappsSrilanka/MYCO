<?php
/**
 * MCYC landing page.
 *
 * @package MYCO
 */

if (!defined('ABSPATH')) {
    exit;
}

the_post();

$donate_url      = myco_get_page_url('donate', '/donate/');
$mcyc_donate_url = add_query_arg('fund', 'mcyc', $donate_url);
$volunteer_url   = myco_get_page_url('volunteer', '/volunteer/');
$programs_url    = myco_get_page_url('programs', '/programs/');
$contact_url     = myco_get_contact_page_url();
$gallery_url     = myco_get_page_url('gallery', '/gallery/');
$mcyc_logo       = myco_theme_asset_url('assets/images/mcyc-logo.png');
$hero_bg         = myco_theme_asset_url('assets/images/Galleries/myco-youth-community-groundbreaking-event-autograph.jpg');
$hero_image      = myco_theme_asset_url('assets/images/Galleries/myco-youth-community-center-groundbreaking-ceremony.jpg');
$community_image = myco_theme_asset_url('assets/images/volunteers.jpg');
$building_image  = myco_theme_asset_url('assets/images/study.jpg');
$meeting_image   = myco_theme_asset_url('assets/images/meeting.jpg');

$construction_images = [
    ['src' => myco_theme_asset_url('assets/images/Construction/Construction Update 1.jpg'),  'alt' => 'MCYC construction update — site progress'],
    ['src' => myco_theme_asset_url('assets/images/Construction/Construction (2).webp'),       'alt' => 'MCYC construction — structural work in progress'],
    ['src' => myco_theme_asset_url('assets/images/Construction/Construction (3).webp'),       'alt' => 'MCYC construction — building framework'],
    ['src' => myco_theme_asset_url('assets/images/Construction/Construction (4).webp'),       'alt' => 'MCYC construction — site development'],
    ['src' => myco_theme_asset_url('assets/images/Construction/Construction (5).webp'),       'alt' => 'MCYC construction — facility taking shape'],
];
$sports_image    = myco_theme_asset_url('assets/images/sports.jpg');
$video_poster    = myco_theme_asset_url('assets/images/Galleries/myco-youth-community-center-groundbreaking-ceremony.jpg');
$video_src       = myco_theme_asset_url('assets/images/construction.mp4');
$quote_image     = myco_theme_asset_url('assets/images/Galleries/myco-youth-team-award-check-winners.jpg');

$quick_links = [
    ['target' => 'vision', 'label' => 'Vision'],
    ['target' => 'construction-update', 'label' => 'Progress'],
    ['target' => 'building', 'label' => 'Building'],
    ['target' => 'programs', 'label' => 'Programs'],
    ['target' => 'community-voices', 'label' => 'Voices'],
    ['target' => 'campaign-video', 'label' => 'Video'],
];

$story_cards = [
    [
        'image' => myco_theme_asset_url('assets/images/Galleries/myco-youth-team-award-check-winners.jpg'),
        'title' => 'Youth Perspective',
        'role'  => 'Belonging and confidence',
        'quote' => 'A dedicated space gives young people consistency. It becomes the place they return to for support, friendship, and growth.',
    ],
    [
        'image' => myco_theme_asset_url('assets/images/Galleries/myco-youth-basketball-event-congregational-prayer.jpg'),
        'title' => 'Parent Perspective',
        'role'  => 'Trust and continuity',
        'quote' => 'Families benefit when youth have a place rooted in faith, guided by mentors, and designed for long-term development.',
    ],
    [
        'image' => myco_theme_asset_url('assets/images/Galleries/myco-youth-community-center-groundbreaking-ceremony.jpg'),
        'title' => 'Community Perspective',
        'role'  => 'Shared investment',
        'quote' => 'MCYC is not just about a building. It is about creating a home for the next generation to gather, lead, and belong.',
    ],
];

$program_cards = [
    ['number' => '01', 'title' => 'Leadership Development', 'description' => 'A place for youth to build responsibility, confidence, and dependable leadership habits.', 'featured' => true],
    ['number' => '02', 'title' => 'Spiritual Development', 'description' => 'Consistent programming that strengthens faith, identity, worship, and belonging.'],
    ['number' => '03', 'title' => 'Education and Skills', 'description' => 'Learning spaces that support tutoring, enrichment, and practical preparation.'],
    ['number' => '04', 'title' => 'Athletics and Training', 'description' => 'Room for recreation, healthy competition, physical discipline, and friendships.'],
    ['number' => '05', 'title' => 'Social and Cultural Life', 'description' => 'Gatherings that help youth feel seen, connected, and proud of their community.'],
    ['number' => '06', 'title' => 'Service and Innovation', 'description' => 'Programs that encourage youth to serve others and contribute with ihsan.'],
];

$focus_cards = [
    ['title' => 'Faith-centred culture', 'description' => 'Space for worship, reflection, and everyday reminders that faith belongs at the center of youth development.'],
    ['title' => 'Mentorship by design', 'description' => 'A home that supports natural connection between youth and mentors through repetition and visibility.'],
    ['title' => 'Leadership with continuity', 'description' => 'Programs and responsibility deepen when youth have a consistent home base instead of temporary venues.'],
];

$voice_cards = [
    ['initial' => 'P', 'title' => 'Parent Voice',  'role' => 'Local family perspective', 'image' => myco_theme_asset_url('assets/images/Testimonials/Br_Abdurahman_Abdala.png'), 'quote' => 'A permanent youth-centred space changes what is possible. It gives families continuity, trust, and a place to return to with confidence.'],
    ['initial' => 'D', 'title' => 'Donor Voice',   'role' => 'Campaign supporter',       'image' => myco_theme_asset_url('assets/images/Testimonials/Nasser_Karimian.png'),      'quote' => 'Supporting MCYC means investing in more than construction. It means building the environment that helps young people grow into strong, grounded adults.'],
    ['initial' => 'M', 'title' => 'Mentor Voice',  'role' => 'Community leader',         'image' => myco_theme_asset_url('assets/images/Testimonials/Sh_Nasir_Jungda.png'),      'quote' => 'Guidance works best when it has a home. A dedicated facility makes relationships and leadership development sustainable over time.'],
];

get_header();
?>

<main id="mcyc-top" class="mcyc-page">
    <section id="vision" class="mcyc-hero-stage" style="--mcyc-hero-bg: url('<?php echo esc_url($hero_bg); ?>');">
        <div class="mcyc-hero-architectural-bg" aria-hidden="true"></div>
        <div class="mcyc-hero-grid-pattern" aria-hidden="true"></div>

        <div class="mcyc-hero-inner relative z-10">
            <!-- Text column -->
            <div class="mcyc-hero-text mcyc-fade-in">
                <div class="flex items-center gap-4 mb-8">
                    <img src="<?php echo esc_url($mcyc_logo); ?>" alt="" class="mcyc-logo-mark" aria-hidden="true" />
                    <p class="mcyc-eyebrow mb-0">Capital Campaign for the Future Home of MCYC</p>
                </div>

                <h1 class="text-navy font-extrabold leading-[0.92] tracking-tight text-[2.6rem] sm:text-[4rem] lg:text-[5.2rem] mb-8">
                    Building a Place
                    <span class="text-red block mt-3">to Belong.</span>
                </h1>

                <div class="max-w-2xl space-y-7 text-[1.08rem] leading-[1.85] text-gray-600">
                    <p class="text-xl font-medium text-gray-700">MCYC is the vision for a dedicated youth centre where Muslim youth can return consistently for belonging, mentorship, faith, leadership, and growth.</p>
                    <p>This is more than a building project. It is a long-term investment in a permanent home where community can gather with intention and where guidance can happen with consistency.</p>
                </div>

                <div class="mt-10 flex flex-wrap gap-4">
                    <a href="<?php echo esc_url($mcyc_donate_url); ?>" class="mcyc-btn-primary">Help Build MCYC</a>
                    <a href="#construction-update" class="mcyc-btn-secondary">View Progress</a>
                </div>

                <div class="mt-10 flex flex-wrap gap-3">
                    <span class="mcyc-hero-info-chip">5509 Sunbury Road, Columbus, OH</span>
                    <span class="mcyc-hero-info-chip">Structural steel completion milestone reached</span>
                </div>
            </div>

            <!-- Image column — absolutely positioned right half -->
            <div class="mcyc-hero-video-col mcyc-fade-in" style="transition-delay: 0.18s; padding: 16px 48px 16px 0;">
                <div class="mcyc-hero-image-frame" style="border-radius: 28px; height: 100%; overflow: hidden;">
                    <img src="<?php echo esc_url($hero_image); ?>"
                         alt="MYCO youth community center groundbreaking ceremony"
                         style="width:100%; height:100%; object-fit:cover; object-position:center top; display:block;" />
                    <div class="absolute left-6 top-6 z-10 mcyc-hero-badge-overlay">
                        <span class="inline-flex items-center px-4 py-2 rounded-full bg-red text-white text-[0.72rem] tracking-[0.18em] uppercase font-extrabold">Building Momentum</span>
                    </div>
                    <div class="absolute left-6 bottom-6 z-10 max-w-md text-white mcyc-hero-text-overlay">
                        <p class="text-2xl font-extrabold leading-tight">The future home of MCYC is taking shape.</p>
                        <p class="mt-2 text-sm text-white/85 leading-6">A dedicated youth-centred facility creates room for mentorship, formation, and community that can last for generations.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 md:py-16 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-[1fr_1.08fr] gap-10 xl:gap-14 items-stretch">
                <div class="mcyc-fade-in">
                    <p class="text-sm font-bold uppercase tracking-[0.22em] text-red mb-4">Building Campaign</p>
                    <h2 class="text-navy font-extrabold text-[2.4rem] sm:text-[3.4rem] leading-[0.98] mb-6">Invest in a<span class="text-red block mt-2">Permanent Home</span></h2>
                    <p class="text-gray-600 text-lg leading-[1.8] mb-8 max-w-xl">Your gift supports a youth-centred facility designed to create consistency, mentorship, faith formation, leadership growth, and community connection for years to come.</p>

                    <div class="space-y-4">
                        <div class="mcyc-check-row"><div class="mcyc-check-icon"></div><div><p class="text-navy font-bold text-base">A real project in motion</p><p class="text-gray-600 text-sm mt-1">Support a building effort that is already gathering momentum and community ownership.</p></div></div>
                        <div class="mcyc-check-row"><div class="mcyc-check-icon"></div><div><p class="text-navy font-bold text-base">Built around youth development</p><p class="text-gray-600 text-sm mt-1">The facility is designed to support faith, mentorship, learning, athletics, and belonging under one roof.</p></div></div>
                        <div class="mcyc-check-row"><div class="mcyc-check-icon"></div><div><p class="text-navy font-bold text-base">A place with lasting impact</p><p class="text-gray-600 text-sm mt-1">Permanent spaces make year-round support, stronger relationships, and deeper community formation possible.</p></div></div>
                    </div>
                </div>

                <div class="mcyc-fade-in flex flex-col h-full" style="transition-delay: 0.18s;">
                    <div class="mcyc-donation-card p-6 sm:p-8 flex flex-col h-full">

                        <!-- Step 1: Amount selection -->
                        <div id="mcyc-donate-step-1" class="flex flex-col flex-1">
                            <div class="flex items-center justify-between gap-3 mb-6">
                                <div><h3 class="text-2xl font-bold text-navy">Make a Gift</h3><p class="text-xs text-gray-500 mt-1">Support the MCYC building campaign.</p></div>
                                <div class="flex items-center gap-1 bg-gray-100 rounded-full p-1 shrink-0">
                                    <button type="button" class="mcyc-gift-type-btn is-active" data-type="one-time">One-time</button>
                                    <button type="button" class="mcyc-gift-type-btn" data-type="monthly">Monthly</button>
                                </div>
                            </div>

                            <!-- Two-column layout: form left, summary right -->
                            <div class="mcyc-donate-inner-grid" style="display:grid; grid-template-columns:1fr 220px; gap:16px; align-items:stretch; flex:1; min-height:0;">
                                <div style="display:flex; flex-direction:column; gap:12px; height:100%;">

                                    <!-- Preset amounts: 3+3 grid -->
                                    <div style="display:flex; flex-direction:column; gap:6px;">
                                        <p style="font-size:11px; font-weight:600; color:#6B7280; margin-bottom:2px; text-transform:uppercase; letter-spacing:.06em;">Select Amount</p>
                                        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:6px;">
                                            <?php foreach ([25, 50, 100] as $amt) : ?>
                                                <button type="button" class="mcyc-donation-btn<?php echo $amt === 50 ? ' is-selected' : ''; ?>" data-amount="<?php echo esc_attr($amt); ?>" style="padding:10px 6px; border:2px solid #E5E7EB; border-radius:8px; background:white; color:#374151; font-weight:700; font-size:13px; cursor:pointer; transition:all .2s;">$<?php echo number_format($amt); ?></button>
                                            <?php endforeach; ?>
                                        </div>
                                        <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:6px;">
                                            <?php foreach ([250, 500, 1000] as $amt) : ?>
                                                <button type="button" class="mcyc-donation-btn" data-amount="<?php echo esc_attr($amt); ?>" style="padding:10px 6px; border:2px solid #E5E7EB; border-radius:8px; background:white; color:#374151; font-weight:700; font-size:13px; cursor:pointer; transition:all .2s;">$<?php echo number_format($amt); ?></button>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                    <!-- Custom amount — always visible, disabled when preset selected -->
                                    <div>
                                        <label for="mcyc-custom-amount" style="font-size:11px; font-weight:600; color:#6B7280; display:block; margin-bottom:5px; text-transform:uppercase; letter-spacing:.06em;">Custom Amount</label>
                                        <div style="position:relative;">
                                            <span style="position:absolute; left:12px; top:50%; transform:translateY(-50%); font-size:14px; font-weight:700; color:#9CA3AF;">$</span>
                                            <input id="mcyc-custom-amount" type="number" min="1" step="1" inputmode="numeric" placeholder="Enter amount" style="width:100%; padding:9px 9px 9px 26px; border:2px solid #E5E7EB; border-radius:8px; font-size:14px; font-weight:600; color:#141943; background:#F9FAFB; box-sizing:border-box; transition:all .2s;" />
                                        </div>
                                    </div>

                                    <!-- Fund selector -->
                                    <div>
                                        <label for="mcyc-fund-select" style="font-size:11px; font-weight:600; color:#6B7280; display:block; margin-bottom:5px; text-transform:uppercase; letter-spacing:.06em;">Select Fund</label>
                                        <select id="mcyc-fund-select" style="width:100%; padding:9px 12px; border:2px solid #E5E7EB; border-radius:8px; font-size:12px; font-weight:600; background:white; color:#374151;">
                                            <option value="mcyc" selected>MCYC Building Fund</option>
                                            <option value="general">General Fund</option>
                                            <option value="youth-mentorship">Youth Mentorship</option>
                                            <option value="athletics">Athletics &amp; Sports</option>
                                            <option value="academic">Academic Support</option>
                                        </select>
                                    </div>

                                    <!-- Cover fees -->
                                    <div style="padding:9px 12px; background:rgba(200,64,46,0.06); border-radius:8px; border:1px solid rgba(200,64,46,0.15);">
                                        <label style="display:flex; align-items:flex-start; gap:8px; cursor:pointer;">
                                            <input type="checkbox" id="mcyc-cover-fees" checked style="margin-top:2px; accent-color:#C8402E; width:13px; height:13px; shrink:0;" />
                                            <span style="font-size:11px; line-height:1.3; color:#374151;">
                                                <strong style="color:#141943; display:block; margin-bottom:1px;">Support processing costs</strong>
                                                <span style="color:#6B7280;">(+3.5%) — so 100% goes to MYCO</span>
                                            </span>
                                        </label>
                                    </div>

                                </div><!-- /left col -->

                                <div style="display:flex; flex-direction:column; gap:10px; height:100%; justify-content:space-between;">

                                    <!-- Donation summary -->
                                    <div>
                                        <p style="font-size:11px; font-weight:600; color:#6B7280; margin-bottom:5px; text-transform:uppercase; letter-spacing:.06em;">Summary</p>
                                        <div style="background:#F9FAFB; border:1px solid #E5E7EB; border-radius:10px; padding:12px 14px;">
                                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:5px;">
                                                <span style="font-size:11px; font-weight:600; color:#6B7280;">Donation:</span>
                                                <span id="mcyc-sum-amount" style="font-size:15px; font-weight:800; color:#141943;">$0.00</span>
                                            </div>
                                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                                                <span style="font-size:10px; color:#6B7280;">Processing (3.5%):</span>
                                                <span id="mcyc-sum-fees" style="font-size:11px; font-weight:700; color:#6B7280;">$0.00</span>
                                            </div>
                                            <div style="border-top:2px solid #E5E7EB; padding-top:8px; display:flex; justify-content:space-between; align-items:center;">
                                                <span style="font-size:12px; font-weight:700; color:#141943;">Total:</span>
                                                <span id="mcyc-sum-total" style="font-size:20px; font-weight:900; color:#C8402E; line-height:1;">$0.00</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label for="mcyc-donor-email" style="font-size:10px; font-weight:600; color:#374151; display:block; margin-bottom:4px;">
                                            Email <span style="color:#9CA3AF; font-weight:400;">— optional</span>
                                        </label>
                                        <input type="email" id="mcyc-donor-email" placeholder="your@email.com" autocomplete="email" style="width:100%; padding:8px 10px; border:2px solid #E5E7EB; border-radius:7px; font-size:12px; box-sizing:border-box;" />
                                        <p style="font-size:9px; color:#9CA3AF; margin-top:3px;">For your tax receipt</p>
                                    </div>

                                    <!-- Monthly notice -->
                                    <div id="mcyc-monthly-notice" style="display:none; background:rgba(200,64,46,.06); border:1px solid rgba(200,64,46,.18); border-radius:7px; padding:6px 8px;">
                                        <p style="font-size:9px; color:#374151; margin:0; line-height:1.3;"><strong style="color:#C8402E;">Monthly</strong> — Renews each month</p>
                                    </div>

                                    <!-- Error -->
                                    <div id="mcyc-donate-error" style="display:none; background:#FEF2F2; border:1px solid #FECACA; border-radius:8px; padding:8px 12px; color:#DC2626; font-size:12px; font-weight:500;"></div>

                                    <button type="button" id="mcyc-donate-submit" class="mcyc-donation-submit">Continue to Donate</button>

                                    <p style="font-size:9px; color:#9CA3AF; text-align:center; margin:0;">🔒 Secure processing by Stripe</p>

                                </div><!-- /right col -->
                            </div><!-- /grid -->

                        </div><!-- /step-1 -->

                        <!-- Step 2: Stripe Payment Element -->
                        <div id="mcyc-donate-step-2" style="display:none;">
                            <button type="button" id="mcyc-donate-back" style="background:none; border:none; cursor:pointer; color:#C8402E; font-size:13px; font-weight:600; margin-bottom:18px; padding:0; display:flex; align-items:center; gap:6px;">
                                <svg width="14" height="14" viewBox="0 0 20 20" fill="none"><path d="M15 10H5m0 0l4-4m-4 4l4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                Back to donation details
                            </button>
                            <div style="background:#F9FAFB; border-radius:12px; padding:14px 16px; margin-bottom:18px; display:flex; justify-content:space-between; align-items:center;">
                                <div>
                                    <p style="font-size:11px; font-weight:600; color:#6B7280; text-transform:uppercase; letter-spacing:.06em; margin:0 0 2px;">Your Donation</p>
                                    <p id="mcyc-s2-type" style="font-size:12px; color:#374151; font-weight:600; margin:0;"></p>
                                </div>
                                <p id="mcyc-s2-amount" style="font-size:24px; font-weight:900; color:#C8402E; margin:0;"></p>
                            </div>
                            <div id="mcyc-payment-element" style="min-height:180px; margin-bottom:16px;"></div>
                            <div id="mcyc-payment-error" style="display:none; background:#FEF2F2; border:1px solid #FECACA; border-radius:8px; padding:12px 14px; margin-bottom:16px; color:#DC2626; font-size:13px; font-weight:500;"></div>
                            <button type="button" id="mcyc-confirm-btn" class="mcyc-donation-submit">Confirm Donation</button>
                            <p style="font-size:11px; color:#9CA3AF; text-align:center; margin-top:10px; margin-bottom:0;">🔒 Encrypted and secured by Stripe</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 md:py-16 bg-white">
        <div class="max-w-[1220px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mcyc-fade-in">
                <p class="mcyc-eyebrow justify-center mb-5">Why This Matters</p>
                <h2 class="text-navy font-extrabold tracking-tight text-[2.4rem] sm:text-[3.6rem] leading-[0.96] max-w-5xl mx-auto mb-8">Why Dedicated Youth<span class="text-red block mt-2">Spaces Matter</span></h2>
            </div>
            <div class="mt-8 max-w-5xl mx-auto space-y-6 text-[1.08rem] leading-[1.9] text-gray-600 mcyc-fade-in" style="transition-delay: 0.18s;">
                <p class="text-xl font-medium text-gray-700">Young people need more than occasional events. They need a place they can return to consistently, a place built around belonging, discipline, growth, mentorship, and faith.</p>
                <p>MCYC responds to that need by creating a dedicated home for Muslim youth: a space where identity is strengthened, potential is nurtured, and community is lived out in practical ways. Here, youth are not treated as an afterthought. They are the focus.</p>
                <p>This building matters because consistent spaces create consistent impact. When young people have a place intentionally designed for their development, they gain direction, confidence, guidance, and the support to become grounded leaders.</p>
                <div class="mcyc-quote-panel mt-14 rounded-[28px] text-white shadow-2xl relative overflow-hidden" style="--mcyc-quote-image: url('<?php echo esc_url($quote_image); ?>');">
                    <div class="mcyc-quote-overlay" aria-hidden="true"></div>
                    <div class="mcyc-quote-content">
                        <p class="mcyc-quote-kicker">Community Investment</p>
                        <p class="text-2xl md:text-3xl font-bold leading-[1.4] relative z-10">Dedicated youth centres are not just helpful. They are an investment in the future strength and stability of a community.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="construction-update" class="py-14 md:py-20 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-[1480px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-10 xl:gap-14 items-start">
                <div class="mcyc-fade-in">
                    <div class="mcyc-project-panel rounded-[28px] px-7 py-8 md:px-10 md:py-10 text-white mb-4">
                        <p class="mcyc-eyebrow mb-5">Construction Update</p>
                        <h2 class="text-[2.2rem] sm:text-[3.2rem] font-extrabold leading-[1.0] tracking-tight mb-6">Progress you can <span class="text-red">build on</span></h2>
                        <p class="text-base md:text-lg leading-[1.8] text-white/84 max-w-2xl">The campaign is tied to a real project with a clear community purpose. Every contribution helps move the work from construction milestones toward a fully functioning youth-centred home.</p>
                        <div class="grid gap-3 mt-7">
                            <div class="rounded-[18px] border border-white/12 bg-white/8 px-4 py-3 backdrop-blur-sm"><p class="text-xs uppercase tracking-[0.16em] text-white/60 font-bold">Current Focus</p><p class="mt-1 text-base font-bold">Converting construction momentum into long-term community capacity.</p></div>
                            <div class="rounded-[18px] border border-white/12 bg-white/8 px-4 py-3 backdrop-blur-sm"><p class="text-xs uppercase tracking-[0.16em] text-white/60 font-bold">Project Intention</p><p class="mt-1 text-base font-bold">A permanent base for faith formation, mentorship, programs, and year-round youth engagement.</p></div>
                        </div>
                    </div>
                    <div class="grid sm:grid-cols-3 gap-3">
                        <article class="mcyc-metric-card p-4"><p class="mcyc-metric-value">1</p><p class="text-xs font-semibold text-gray-500 mt-1">Permanent home for youth-centred programming</p></article>
                        <article class="mcyc-metric-card p-4"><p class="mcyc-metric-value">6</p><p class="text-xs font-semibold text-gray-500 mt-1">Core program pillars envisioned inside the facility</p></article>
                        <article class="mcyc-metric-card p-4"><p class="mcyc-metric-value">24/7</p><p class="text-xs font-semibold text-gray-500 mt-1">Long-term community value beyond one-time events</p></article>
                    </div>
                </div>

                <div class="space-y-4 mcyc-fade-in" style="transition-delay: 0.18s;">
                    <div class="mcyc-progress-card p-5 md:p-6">
                        <div class="flex flex-wrap items-center justify-between gap-4 mb-3"><div><p class="text-sm font-bold uppercase tracking-[0.18em] text-red">Milestone</p><h3 class="text-xl font-extrabold text-navy mt-1">Structural steel completion</h3></div><span class="text-3xl font-extrabold text-red">100%</span></div>
                        <div class="mcyc-progress-bar" aria-hidden="true"><span style="width: 100%;"></span></div>
                        <p class="text-sm text-gray-600 mt-3">A visible sign that the project is moving from concept toward a lasting physical home.</p>
                    </div>
                    <div id="building" class="grid md:grid-cols-[1.08fr_0.92fr] gap-3">
                        <figure class="mcyc-building-image-large min-h-[220px]"><img src="<?php echo esc_url($community_image); ?>" alt="Community volunteers supporting youth-centred programming" class="w-full h-full object-cover" /><figcaption class="mcyc-image-caption"><span>Community Energy</span><p>The project is rooted in real relationships, volunteer energy, and a shared commitment to the next generation.</p></figcaption></figure>
                        <div class="grid gap-3">
                            <figure class="mcyc-building-image-small min-h-[106px]"><img src="<?php echo esc_url($meeting_image); ?>" alt="Learning space inspiration for the future MCYC building" class="w-full h-full object-cover" /></figure>
                            <figure class="mcyc-construction-image min-h-[106px]"><img src="<?php echo esc_url($sports_image); ?>" alt="Active programming vision for the future MCYC building" class="w-full h-full object-cover" /></figure>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Construction Photo Slider -->
            <div class="mt-10 mcyc-fade-in" style="transition-delay: 0.28s;">
                <div class="flex items-center gap-3 mb-6">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-red">Construction Photos</p>
                    <div style="flex:1; height:1px; background: linear-gradient(to right, rgba(200,64,46,0.3), transparent);"></div>
                </div>

                <div class="mcyc-cslider" id="mcyc-cslider">
                    <!-- Main featured image -->
                    <div class="mcyc-cslider-main">
                        <img id="mcyc-cslider-main-img"
                             src="<?php echo esc_url($construction_images[0]['src']); ?>"
                             alt="<?php echo esc_attr($construction_images[0]['alt']); ?>"
                             class="mcyc-cslider-main-img" />
                        <div class="mcyc-cslider-overlay">
                            <span class="mcyc-cslider-badge">Construction Update</span>
                            <p id="mcyc-cslider-caption" class="mcyc-cslider-caption"><?php echo esc_html($construction_images[0]['alt']); ?></p>
                        </div>
                        <!-- Prev / Next arrows -->
                        <button class="mcyc-cslider-arrow mcyc-cslider-arrow--prev" id="mcyc-cslider-prev" aria-label="Previous photo">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
                        </button>
                        <button class="mcyc-cslider-arrow mcyc-cslider-arrow--next" id="mcyc-cslider-next" aria-label="Next photo">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
                        </button>
                        <!-- Counter -->
                        <span class="mcyc-cslider-counter" id="mcyc-cslider-counter">1 / <?php echo count($construction_images); ?></span>
                    </div>

                    <!-- Thumbnail strip -->
                    <div class="mcyc-cslider-thumbs" id="mcyc-cslider-thumbs">
                        <?php foreach ($construction_images as $ci => $cimg) : ?>
                        <button class="mcyc-cslider-thumb<?php echo $ci === 0 ? ' is-active' : ''; ?>"
                                data-index="<?php echo $ci; ?>"
                                data-src="<?php echo esc_url($cimg['src']); ?>"
                                data-alt="<?php echo esc_attr($cimg['alt']); ?>"
                                aria-label="<?php echo esc_attr($cimg['alt']); ?>">
                            <img src="<?php echo esc_url($cimg['src']); ?>" alt="" loading="lazy" />
                        </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section id="programs" class="py-14 md:py-20 bg-white">
        <div class="max-w-[1480px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-4xl mx-auto mb-12 mcyc-fade-in">
                <p class="mcyc-eyebrow justify-center mb-5">Programs and Purpose</p>
                <h2 class="text-navy font-extrabold tracking-tight text-[2.4rem] sm:text-[3.6rem] leading-[1] mb-6">What Happens at <span class="text-red">MCYC</span></h2>
                <p class="text-lg leading-[1.8] text-gray-600">The building is meaningful because of what it makes possible. These are the experiences the facility is meant to hold, strengthen, and scale over time.</p>
            </div>
            <div class="grid sm:grid-cols-2 xl:grid-cols-3 gap-8 mcyc-fade-in" style="transition-delay: 0.18s;">
                <?php foreach ($program_cards as $program) : ?>
                    <article class="mcyc-program-card<?php echo !empty($program['featured']) ? ' is-featured' : ''; ?>">
                        <div class="mcyc-program-icon"><?php echo esc_html($program['number']); ?></div>
                        <h3 class="text-2xl font-bold mb-4"><?php echo esc_html($program['title']); ?></h3>
                        <p class="leading-[1.8] text-[1.05rem]"><?php echo esc_html($program['description']); ?></p>
                    </article>
                <?php endforeach; ?>
            </div>
            <div class="mt-12 text-center"><a href="<?php echo esc_url($programs_url); ?>" class="mcyc-btn-secondary">Explore Current MYCO Programs</a></div>
        </div>
    </section>

    <section id="community-voices" class="py-16 md:py-24 bg-white">
        <div class="max-w-[1480px] mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Section header -->
            <div class="text-center mb-12 mcyc-fade-in">
                <p class="mcyc-eyebrow mb-4">Construction Progress</p>
                <h2 class="text-navy font-extrabold tracking-tight text-[2.4rem] sm:text-[3.2rem] leading-[1.05] mb-5">
                    Watch the <span class="text-red">Building Rise</span>
                </h2>
                <p class="text-lg text-gray-600 leading-[1.8] max-w-2xl mx-auto">
                    From groundbreaking to structural steel — see the MCYC facility taking shape in real time. Every beam placed is a step closer to a permanent home for Muslim youth in Central Ohio.
                </p>
            </div>

            <!-- Video -->
            <div class="mcyc-fade-in" style="transition-delay:0.12s; border-radius:28px; overflow:hidden; box-shadow:0 24px 70px rgba(20,25,67,0.14); position:relative; background:#000;">
                <video autoplay muted loop playsinline
                       style="width:100%; display:block; max-height:700px; object-fit:cover;">
                    <source src="<?php echo esc_url($video_src); ?>" type="video/mp4" />
                </video>
                <!-- Bottom overlay -->
                <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(20,25,67,0.55) 0%, transparent 50%); pointer-events:none;"></div>
                <div style="position:absolute; bottom:32px; left:40px; right:40px; z-index:2; display:flex; align-items:flex-end; justify-content:space-between; flex-wrap:wrap; gap:16px;" class="mcyc-construction-overlay">
                    <div>
                        <p class="text-white font-extrabold text-2xl md:text-3xl leading-tight">MCYC — 5509 Sunbury Road, Columbus, OH</p>
                        <p class="text-white/80 text-sm mt-1">Structural steel completion milestone reached</p>
                    </div>
                    <a href="<?php echo esc_url($mcyc_donate_url); ?>" class="mcyc-btn-primary" style="flex-shrink:0;">Support the Build</a>
                </div>
            </div>

        </div>
    </section>

    <section class="py-16 md:py-20 bg-white">
        <div class="max-w-[1480px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8 mcyc-fade-in">
                <p class="mcyc-eyebrow mb-4">MYYC Videos</p>
                <h2 class="text-navy font-extrabold tracking-tight text-[2.4rem] sm:text-[3.2rem] leading-[0.98]">A space designed for <span class="text-red">formation and belonging</span></h2>
            </div>

            <!-- Video Player Card -->
            <div id="campaign-video" class="mcyc-video-player mcyc-fade-in" style="transition-delay:0.1s;">

                <!-- Main video display -->
                <div class="mcyc-vp-main">
                    <video id="mcyc-main-video"
                           src="<?php echo esc_url($video_src); ?>"
                           poster="<?php echo esc_url($video_poster); ?>"
                           preload="metadata"
                           playsinline
                           class="mcyc-vp-video"></video>

                    <!-- Play overlay -->
                    <button class="mcyc-vp-play-overlay" id="mcyc-vp-overlay-btn" aria-label="Play video">
                        <span class="mcyc-vp-big-play">
                            <svg id="mcyc-vp-big-play-icon" width="28" height="28" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                            <svg id="mcyc-vp-big-pause-icon" width="28" height="28" viewBox="0 0 24 24" fill="currentColor" style="display:none;"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                        </span>
                    </button>

                    <!-- Bottom bar -->
                    <div class="mcyc-vp-bar">
                        <div class="mcyc-vp-title-row">
                            <span class="mcyc-vp-badge">MYCO Stories</span>
                            <span id="mcyc-vp-title" class="mcyc-vp-title-text">MCYC Groundbreaking Ceremony</span>
                        </div>
                        <!-- Progress -->
                        <div class="mcyc-vp-progress-wrap" id="mcyc-vp-progress-wrap">
                            <div class="mcyc-vp-progress-bg">
                                <div class="mcyc-vp-progress-fill" id="mcyc-vp-fill"></div>
                                <div class="mcyc-vp-progress-thumb" id="mcyc-vp-thumb"></div>
                            </div>
                        </div>
                        <!-- Controls row -->
                        <div class="mcyc-vp-controls">
                            <div class="mcyc-vp-controls-left">
                                <button class="mcyc-vp-ctrl" id="mcyc-vp-play-btn" title="Play/Pause">
                                    <svg id="mcyc-vp-play-icon" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                    <svg id="mcyc-vp-pause-icon" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="display:none;"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg>
                                </button>
                                <span id="mcyc-vp-time" class="mcyc-vp-time">0:00 / 0:00</span>
                            </div>
                            <div class="mcyc-vp-controls-right">
                                <div class="mcyc-vp-vol-wrap">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" style="color:rgba(255,255,255,0.6);flex-shrink:0;"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3A4.5 4.5 0 0 0 14 7.97v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/></svg>
                                    <input type="range" id="mcyc-vp-vol" class="mcyc-vp-vol-slider" min="0" max="100" value="80" />
                                </div>
                                <button class="mcyc-vp-ctrl" id="mcyc-vp-fullscreen" title="Fullscreen">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Thumbnail slider -->
                <div class="mcyc-vp-slider-wrap">
                    <button class="mcyc-vp-slide-arrow mcyc-vp-slide-prev" id="mcyc-vp-prev" aria-label="Previous">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"/></svg>
                    </button>
                    <div class="mcyc-vp-slider" id="mcyc-vp-slider">
                        <?php
                        $gallery_images = [
                            myco_theme_asset_url('assets/images/Galleries/myco-youth-community-center-groundbreaking-ceremony.jpg'),
                            myco_theme_asset_url('assets/images/Galleries/myco-youth-community-groundbreaking-event-autograph.jpg'),
                            myco_theme_asset_url('assets/images/Galleries/myco-youth-basketball-event-congregational-prayer.jpg'),
                            myco_theme_asset_url('assets/images/Galleries/myco-basketball-champions-team-with-trophy.jpg.jpg'),
                            myco_theme_asset_url('assets/images/Galleries/myco-youth-team-award-check-winners.jpg'),
                            myco_theme_asset_url('assets/images/Galleries/myco-youth-basketball-player-in-game-action.jpg.jpg'),
                        ];
                        $video_titles = [
                            'MCYC Groundbreaking Ceremony',
                            'Groundbreaking Event — Autograph Signing',
                            'Community Prayer at Basketball Event',
                            'Basketball Champions — Trophy Presentation',
                            'Youth Team Award & Check Presentation',
                            'Basketball Tournament — Game Action',
                        ];
                        foreach ($gallery_images as $vi => $vimg) : ?>
                        <div class="mcyc-vp-thumb-item<?php echo $vi === 0 ? ' is-active' : ''; ?>"
                             data-src="<?php echo esc_url($video_src); ?>"
                             data-poster="<?php echo esc_url($vimg); ?>"
                             data-title="<?php echo esc_attr($video_titles[$vi]); ?>">
                            <div class="mcyc-vp-thumb-img">
                                <img src="<?php echo esc_url($vimg); ?>" alt="<?php echo esc_attr($video_titles[$vi]); ?>" />
                                <span class="mcyc-vp-thumb-play">
                                    <svg width="10" height="12" viewBox="0 0 10 12" fill="white"><path d="M1 1l8 5-8 5V1z"/></svg>
                                </span>
                            </div>
                            <p class="mcyc-vp-thumb-label"><?php echo esc_html($video_titles[$vi]); ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="mcyc-vp-slide-arrow mcyc-vp-slide-next" id="mcyc-vp-next" aria-label="Next">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"/></svg>
                    </button>
                </div>

            </div>
        </div>
    </section>

    <section id="mentorship" class="mcyc-mentorship-stage py-16 md:py-20 relative overflow-hidden">
        <div class="mcyc-section-orb mcyc-section-orb--left" aria-hidden="true"></div>
        <div class="mcyc-section-orb mcyc-section-orb--right" aria-hidden="true"></div>
        <div class="mcyc-mentorship-inner max-w-[1480px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-[1.1fr_0.9fr] gap-10 xl:gap-14 items-center">
                <div class="mcyc-fade-in">
                    <p class="text-sm font-bold uppercase tracking-[0.22em] text-red mb-4">Mentorship</p>
                    <h2 class="text-navy text-[2.4rem] sm:text-[3.2rem] lg:text-[3.8rem] font-extrabold leading-[1.0] tracking-tight mb-7">Mentorship gives the building<span class="text-red"> its deeper meaning.</span></h2>
                    <div class="mcyc-mentorship-copy space-y-5 text-lg leading-[1.8] text-gray-600 mb-8"><p>The building creates the place, but mentorship gives the place its purpose. MCYC is meant to be a setting where guidance can happen consistently.</p><p>Inside a permanent home, mentorship becomes part of the culture of the space, woven into how young people learn, grow, connect, and lead.</p></div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8"><?php foreach ($focus_cards as $focus) : ?><div class="mcyc-focus-card"><p class="text-base font-extrabold text-red mb-2"><?php echo esc_html($focus['title']); ?></p><p class="mcyc-focus-copy text-sm text-gray-600 leading-6"><?php echo esc_html($focus['description']); ?></p></div><?php endforeach; ?></div>
                    <div class="flex flex-wrap gap-4"><a href="<?php echo esc_url($volunteer_url); ?>" class="mcyc-btn-primary">Become a Mentor</a><a href="<?php echo esc_url($mcyc_donate_url); ?>" class="mcyc-btn-secondary">Support the Mission</a></div>
                </div>
                <div class="relative mcyc-fade-in" style="transition-delay: 0.18s;"><div class="mcyc-image-glow" aria-hidden="true"></div><img src="<?php echo esc_url($building_image); ?>" alt="Mentorship and community support connected to the MCYC vision" class="relative rounded-[28px] w-full h-[380px] md:h-[460px] object-cover shadow-2xl" /></div>
            </div>
        </div>
    </section>

    <section class="mcyc-final-cta py-16 md:py-24 text-white relative">
        <div class="mcyc-final-inner max-w-[1080px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="mcyc-final-card text-center mcyc-fade-in">
                <h2 class="mcyc-final-heading text-[2.6rem] sm:text-[4rem] lg:text-[4.8rem] font-extrabold tracking-tight leading-[0.94] mb-8">Support the<span class="text-red block mt-3">Build</span></h2>
                <p class="mcyc-final-copy max-w-3xl mx-auto text-lg md:text-[1.45rem] leading-[1.75] text-gray-200 mb-12">Help complete a permanent place where youth can belong, grow in faith, find mentorship, and build a stronger future together.</p>
                <div class="mcyc-final-actions flex flex-wrap justify-center gap-4"><a href="<?php echo esc_url($mcyc_donate_url); ?>" class="mcyc-btn-primary text-lg px-12 py-6">Donate Now</a><a href="#building" class="mcyc-btn-secondary mcyc-btn-secondary--light text-lg px-10 py-6">Learn More</a></div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
