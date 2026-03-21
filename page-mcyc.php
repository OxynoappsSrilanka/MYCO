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
$sports_image    = myco_theme_asset_url('assets/images/sports.jpg');
$video_poster    = myco_theme_asset_url('assets/images/about.png');
$video_src       = myco_theme_asset_url('assets/images/muslimyoungster.mp4');
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
    ['initial' => 'P', 'title' => 'Parent Voice', 'role' => 'Local family perspective', 'quote' => 'A permanent youth-centred space changes what is possible. It gives families continuity, trust, and a place to return to with confidence.'],
    ['initial' => 'D', 'title' => 'Donor Voice', 'role' => 'Campaign supporter', 'quote' => 'Supporting MCYC means investing in more than construction. It means building the environment that helps young people grow into strong, grounded adults.'],
    ['initial' => 'M', 'title' => 'Mentor Voice', 'role' => 'Community leader', 'quote' => 'Guidance works best when it has a home. A dedicated facility makes relationships and leadership development sustainable over time.'],
];

get_header();
?>

<main id="mcyc-top" class="mcyc-page">
    <section id="vision" class="mcyc-hero-stage pt-16 md:pt-20 pb-20 md:pb-28" style="--mcyc-hero-bg: url('<?php echo esc_url($hero_bg); ?>');">
        <div class="mcyc-hero-architectural-bg" aria-hidden="true"></div>
        <div class="mcyc-hero-grid-pattern" aria-hidden="true"></div>

        <div class="max-w-[1480px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-[1.02fr_0.98fr] gap-14 xl:gap-20 items-center">
                <div class="mcyc-fade-in">
                    <div class="flex items-center gap-4 mb-8">
                        <img src="<?php echo esc_url($mcyc_logo); ?>" alt="" class="mcyc-logo-mark" aria-hidden="true" />
                        <p class="mcyc-eyebrow mb-0">Capital Campaign for the Future Home of MCYC</p>
                    </div>

                    <h1 class="text-navy font-extrabold leading-[0.92] tracking-tight text-[3rem] sm:text-[4.8rem] lg:text-[6.2rem] mb-8">
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

                <div class="mcyc-fade-in" style="transition-delay: 0.18s;">
                    <div class="mcyc-hero-image-frame">
                        <img src="<?php echo esc_url($hero_image); ?>" alt="Community members gathered around the MCYC project vision" class="w-full h-[400px] sm:h-[520px] lg:h-[620px] object-cover object-center" />
                        <div class="absolute left-6 top-6 z-10">
                            <span class="inline-flex items-center px-4 py-2 rounded-full bg-red text-white text-[0.72rem] tracking-[0.18em] uppercase font-extrabold">Campaign Momentum</span>
                        </div>
                        <div class="absolute left-6 bottom-6 z-10 max-w-md text-white">
                            <p class="text-2xl font-extrabold leading-tight">The future home of MCYC is taking shape.</p>
                            <p class="mt-2 text-sm text-white/85 leading-6">A dedicated youth-centred facility creates room for mentorship, formation, and community that can last for generations.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 md:py-28 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-[1360px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-[1fr_1.08fr] gap-14 xl:gap-20 items-center">
                <div class="mcyc-fade-in">
                    <p class="text-sm font-bold uppercase tracking-[0.22em] text-red mb-6">Building Campaign</p>
                    <h2 class="text-navy font-extrabold text-[2.8rem] sm:text-[4.2rem] leading-[0.98] mb-8">Invest in a<span class="text-red block mt-3">Permanent Home</span></h2>
                    <p class="text-gray-600 text-xl leading-[1.8] mb-10 max-w-xl">Your gift supports a youth-centred facility designed to create consistency, mentorship, faith formation, leadership growth, and community connection for years to come.</p>

                    <div class="space-y-5">
                        <div class="mcyc-check-row"><div class="mcyc-check-icon"></div><div><p class="text-navy font-bold text-lg">A real project in motion</p><p class="text-gray-600 text-sm mt-1">Support a building effort that is already gathering momentum and community ownership.</p></div></div>
                        <div class="mcyc-check-row"><div class="mcyc-check-icon"></div><div><p class="text-navy font-bold text-lg">Built around youth development</p><p class="text-gray-600 text-sm mt-1">The facility is designed to support faith, mentorship, learning, athletics, and belonging under one roof.</p></div></div>
                        <div class="mcyc-check-row"><div class="mcyc-check-icon"></div><div><p class="text-navy font-bold text-lg">A place with lasting impact</p><p class="text-gray-600 text-sm mt-1">Permanent spaces make year-round support, stronger relationships, and deeper community formation possible.</p></div></div>
                    </div>
                </div>

                <div class="mcyc-fade-in" style="transition-delay: 0.18s;">
                    <div class="mcyc-donation-card p-8 sm:p-10 md:p-12">
                        <div class="flex items-center justify-between gap-4 mb-10">
                            <div><h3 class="text-3xl font-bold text-navy">Make a Gift</h3><p class="text-sm text-gray-500 mt-2">Your selection carries into the main donation flow.</p></div>
                            <div class="flex items-center gap-2 bg-gray-100 rounded-full p-1">
                                <button type="button" class="mcyc-gift-type-btn is-active" data-type="one-time">One-time</button>
                                <button type="button" class="mcyc-gift-type-btn" data-type="monthly">Monthly</button>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-7">
                            <?php foreach ([25, 50, 100, 250] as $amount) : ?>
                                <button type="button" class="mcyc-donation-btn<?php echo $amount === 50 ? ' is-selected' : ''; ?>" data-amount="<?php echo esc_attr($amount); ?>">$<?php echo esc_html(number_format($amount)); ?></button>
                            <?php endforeach; ?>
                        </div>

                        <div class="mb-7">
                            <label for="mcyc-custom-amount" class="sr-only">Custom amount</label>
                            <input id="mcyc-custom-amount" type="number" min="1" step="1" inputmode="numeric" placeholder="Other amount" class="w-full px-6 py-5 rounded-2xl border-2 border-gray-200 focus:outline-none focus:border-navy text-navy font-semibold text-lg transition-all bg-gray-50" />
                        </div>

                        <a id="mcyc-donate-link" href="<?php echo esc_url(add_query_arg(['fund' => 'mcyc', 'amount' => 50], $donate_url)); ?>" class="mcyc-donation-submit">Continue to Donate</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-24 md:py-32 bg-white">
        <div class="max-w-[1220px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mcyc-fade-in">
                <p class="mcyc-eyebrow justify-center mb-7">Why This Matters</p>
                <h2 class="text-navy font-extrabold tracking-tight text-[2.8rem] sm:text-[4.2rem] leading-[0.96] max-w-5xl mx-auto mb-12">Why Dedicated Youth<span class="text-red block mt-3">Spaces Matter</span></h2>
            </div>
            <div class="mt-14 max-w-5xl mx-auto space-y-8 text-[1.12rem] leading-[1.9] text-gray-600 mcyc-fade-in" style="transition-delay: 0.18s;">
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

    <section id="construction-update" class="py-24 md:py-32 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-[1480px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-[1.02fr_0.98fr] gap-14 xl:gap-18 items-start">
                <div class="mcyc-project-panel rounded-[32px] px-8 py-10 md:px-12 md:py-14 text-white mcyc-fade-in">
                    <p class="mcyc-eyebrow mb-7">Construction Update</p>
                    <h2 class="text-[2.8rem] sm:text-[4.2rem] font-extrabold leading-[0.96] tracking-tight mb-8">Progress you can<span class="text-red block mt-3">build on</span></h2>
                    <p class="text-lg md:text-xl leading-[1.9] text-white/84 max-w-2xl">The campaign is tied to a real project with a clear community purpose. Every contribution helps move the work from construction milestones toward a fully functioning youth-centred home.</p>
                    <div class="grid gap-4 mt-10">
                        <div class="rounded-[22px] border border-white/12 bg-white/8 px-5 py-4 backdrop-blur-sm"><p class="text-xs uppercase tracking-[0.16em] text-white/60 font-bold">Current Focus</p><p class="mt-2 text-lg font-bold">Converting construction momentum into long-term community capacity.</p></div>
                        <div class="rounded-[22px] border border-white/12 bg-white/8 px-5 py-4 backdrop-blur-sm"><p class="text-xs uppercase tracking-[0.16em] text-white/60 font-bold">Project Intention</p><p class="mt-2 text-lg font-bold">A permanent base for faith formation, mentorship, programs, and year-round youth engagement.</p></div>
                    </div>
                </div>

                <div class="space-y-6 mcyc-fade-in" style="transition-delay: 0.18s;">
                    <div class="mcyc-progress-card p-7 md:p-8">
                        <div class="flex flex-wrap items-center justify-between gap-4 mb-4"><div><p class="text-sm font-bold uppercase tracking-[0.18em] text-red">Milestone</p><h3 class="text-2xl font-extrabold text-navy mt-2">Structural steel completion</h3></div><span class="text-3xl font-extrabold text-red">100%</span></div>
                        <div class="mcyc-progress-bar" aria-hidden="true"><span style="width: 100%;"></span></div>
                        <p class="text-sm text-gray-600 mt-4">A visible sign that the project is moving from concept toward a lasting physical home.</p>
                    </div>
                    <div class="grid sm:grid-cols-3 gap-4">
                        <article class="mcyc-metric-card p-6"><p class="mcyc-metric-value">1</p><p class="text-sm font-semibold text-gray-500 mt-2">Permanent home for youth-centred programming</p></article>
                        <article class="mcyc-metric-card p-6"><p class="mcyc-metric-value">6</p><p class="text-sm font-semibold text-gray-500 mt-2">Core program pillars envisioned inside the facility</p></article>
                        <article class="mcyc-metric-card p-6"><p class="mcyc-metric-value">24/7</p><p class="text-sm font-semibold text-gray-500 mt-2">Long-term community value beyond one-time events</p></article>
                    </div>
                    <div id="building" class="grid md:grid-cols-[1.08fr_0.92fr] gap-4">
                        <figure class="mcyc-building-image-large min-h-[320px]"><img src="<?php echo esc_url($community_image); ?>" alt="Community volunteers supporting youth-centred programming" class="w-full h-full object-cover" /><figcaption class="mcyc-image-caption"><span>Community Energy</span><p>The project is rooted in real relationships, volunteer energy, and a shared commitment to the next generation.</p></figcaption></figure>
                        <div class="grid gap-4">
                            <figure class="mcyc-building-image-small min-h-[152px]"><img src="<?php echo esc_url($meeting_image); ?>" alt="Learning space inspiration for the future MCYC building" class="w-full h-full object-cover" /></figure>
                            <figure class="mcyc-construction-image min-h-[152px]"><img src="<?php echo esc_url($sports_image); ?>" alt="Active programming vision for the future MCYC building" class="w-full h-full object-cover" /></figure>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="programs" class="py-24 md:py-32 bg-white">
        <div class="max-w-[1480px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-4xl mx-auto mb-20 mcyc-fade-in">
                <p class="mcyc-eyebrow justify-center mb-7">Programs and Purpose</p>
                <h2 class="text-navy font-extrabold tracking-tight text-[2.8rem] sm:text-[4.2rem] leading-[1] mb-8">What Happens at <span class="text-red">MCYC</span></h2>
                <p class="text-xl leading-[1.8] text-gray-600">The building is meaningful because of what it makes possible. These are the experiences the facility is meant to hold, strengthen, and scale over time.</p>
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

    <section id="community-voices" class="py-24 md:py-32 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-[1480px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 mcyc-fade-in">
                <p class="mcyc-eyebrow justify-center mb-7">Community Stories</p>
                <h2 class="text-navy font-extrabold tracking-tight text-[2.8rem] sm:text-[4.2rem] leading-[1] mb-8">Hear From Our <span class="text-red">Community</span></h2>
                <p class="text-xl leading-[1.8] text-gray-600 max-w-3xl mx-auto">A dedicated youth centre matters because it gives real people a space they can count on. These perspectives reflect the kind of impact the project is meant to create.</p>
            </div>
            <div class="relative mcyc-fade-in" style="transition-delay: 0.18s;">
                <div class="mcyc-story-slider">
                    <div class="mcyc-story-track" id="mcyc-story-track">
                        <div class="mcyc-story-slide">
                            <?php foreach ($story_cards as $story) : ?>
                                <article class="mcyc-story-card">
                                    <div class="mcyc-story-thumbnail"><img src="<?php echo esc_url($story['image']); ?>" alt="<?php echo esc_attr($story['title']); ?>" /><span class="mcyc-story-badge">Community Voice</span></div>
                                    <div class="mcyc-story-content">
                                        <h3 class="mcyc-story-name"><?php echo esc_html($story['title']); ?></h3>
                                        <p class="mcyc-story-role"><?php echo esc_html($story['role']); ?></p>
                                        <p class="mcyc-story-quote"><?php echo esc_html($story['quote']); ?></p>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div id="mcyc-story-dots" class="mcyc-slider-dots" aria-label="Story slider pagination"></div>
            </div>
            <div class="grid lg:grid-cols-3 gap-7 mt-12 mcyc-fade-in" style="transition-delay: 0.24s;">
                <?php foreach ($voice_cards as $voice) : ?>
                    <article class="mcyc-voice-card<?php echo $voice['initial'] === 'D' ? ' is-featured' : ''; ?> p-8">
                        <p class="text-lg leading-[1.8]"><?php echo esc_html($voice['quote']); ?></p>
                        <div class="mt-8 flex items-center gap-4"><div class="mcyc-voice-avatar"><?php echo esc_html($voice['initial']); ?></div><div><p class="font-extrabold"><?php echo esc_html($voice['title']); ?></p><p class="mcyc-voice-role text-sm"><?php echo esc_html($voice['role']); ?></p></div></div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="py-24 md:py-32 bg-white">
        <div class="max-w-[1480px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-[1.08fr_0.92fr] gap-12 xl:gap-16 items-center">
                <div class="mcyc-fade-in">
                    <p class="mcyc-eyebrow mb-7">The Building</p>
                    <h2 class="text-navy font-extrabold tracking-tight text-[2.8rem] sm:text-[4rem] leading-[0.98] mb-8">A space designed for<span class="text-red block mt-2">formation and belonging</span></h2>
                    <p class="text-gray-600 text-xl leading-[1.85] max-w-2xl">MCYC is envisioned as a practical, beautiful, and deeply usable home for youth. The goal is not only to host programs, but to create an environment that supports consistency, dignity, and long-term relationships.</p>
                    <div class="space-y-6 mt-10">
                        <div class="mcyc-building-focus-card rounded-[24px] bg-gradient-to-br from-navy to-navy-dark text-white shadow-xl p-8"><p class="mcyc-building-focus-label text-sm uppercase tracking-[0.2em] text-white/70 font-bold mb-5">Facility Focus</p><ul class="space-y-4 text-lg leading-[1.7]"><li>Welcoming spaces for youth to gather regularly and safely</li><li>Flexible rooms for education, mentorship, and leadership development</li><li>Programming areas that support athletics, service, and community connection</li></ul></div>
                        <div class="rounded-[24px] bg-white border-2 border-gray-200 p-8"><p class="text-navy font-bold text-2xl mb-4">Location</p><p class="text-gray-600 leading-[1.75] text-lg">5509 Sunbury Road<br />Columbus, Ohio</p><div class="flex flex-wrap gap-3 mt-6"><a href="<?php echo esc_url($gallery_url); ?>" class="mcyc-btn-secondary">View Gallery</a><a href="<?php echo esc_url($contact_url); ?>" class="mcyc-btn-secondary">Contact the Team</a></div></div>
                    </div>
                </div>
                <div id="campaign-video" class="mcyc-fade-in" style="transition-delay: 0.18s;">
                    <div class="mcyc-video-card p-6 md:p-7">
                        <div class="mcyc-video-frame">
                            <video controls preload="metadata" poster="<?php echo esc_url($video_poster); ?>" class="w-full h-[320px] md:h-[420px] object-cover">
                                <source src="<?php echo esc_url($video_src); ?>" type="video/mp4" />
                            </video>
                            <div class="mcyc-video-copy absolute left-6 right-6 bottom-6 z-[3] text-white pointer-events-none"><p class="text-sm uppercase tracking-[0.18em] font-extrabold text-white/70">Story Video</p><p class="mt-2 text-2xl font-extrabold leading-tight">Building a place for the next generation</p><p class="mt-2 text-sm text-white/82 leading-6">A visual reminder of why youth-centred spaces deserve long-term community investment.</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="mentorship" class="mcyc-mentorship-stage py-28 md:py-36 relative overflow-hidden">
        <div class="mcyc-section-orb mcyc-section-orb--left" aria-hidden="true"></div>
        <div class="mcyc-section-orb mcyc-section-orb--right" aria-hidden="true"></div>
        <div class="mcyc-mentorship-inner max-w-[1480px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid lg:grid-cols-[1.1fr_0.9fr] gap-14 xl:gap-20 items-center">
                <div class="mcyc-fade-in">
                    <p class="text-sm font-bold uppercase tracking-[0.22em] text-red mb-6">Mentorship</p>
                    <h2 class="text-navy text-[3rem] sm:text-[4.2rem] lg:text-[5rem] font-extrabold leading-[0.96] tracking-tight mb-10">Mentorship gives<span class="text-red block mt-4">the building</span><span class="block mt-3">its deeper</span><span class="text-red block mt-4">meaning.</span></h2>
                    <div class="mcyc-mentorship-copy space-y-7 text-xl leading-[1.85] text-gray-600 mb-12"><p>The building creates the place, but mentorship gives the place its purpose. MCYC is meant to be a setting where guidance can happen consistently.</p><p>Inside a permanent home, mentorship becomes more than a single program. It becomes part of the culture of the space, woven into how young people learn, grow, connect, and lead.</p></div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12"><?php foreach ($focus_cards as $focus) : ?><div class="mcyc-focus-card"><p class="text-lg font-extrabold text-red mb-3"><?php echo esc_html($focus['title']); ?></p><p class="mcyc-focus-copy text-sm text-gray-600 leading-7"><?php echo esc_html($focus['description']); ?></p></div><?php endforeach; ?></div>
                    <div class="flex flex-wrap gap-5"><a href="<?php echo esc_url($volunteer_url); ?>" class="mcyc-btn-primary">Become a Mentor</a><a href="<?php echo esc_url($mcyc_donate_url); ?>" class="mcyc-btn-secondary">Support the Mission</a></div>
                </div>
                <div class="relative mcyc-fade-in" style="transition-delay: 0.18s;"><div class="mcyc-image-glow" aria-hidden="true"></div><img src="<?php echo esc_url($building_image); ?>" alt="Mentorship and community support connected to the MCYC vision" class="relative rounded-[36px] w-full h-[620px] md:h-[760px] object-cover shadow-2xl" /></div>
            </div>
        </div>
    </section>

    <section class="mcyc-final-cta py-28 md:py-40 text-white relative">
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
