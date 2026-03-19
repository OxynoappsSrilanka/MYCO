<?php
/**
 * Single Program Template
 *
 * @package MYCO
 */

get_header();

$program_id    = get_the_ID();
$current_title = get_the_title($program_id);
$current_slug  = get_post_field('post_name', $program_id) ?: sanitize_title($current_title);
$blueprint     = function_exists('myco_get_program_blueprint') ? myco_get_program_blueprint($current_slug ?: $current_title) : null;
$all_programs  = function_exists('myco_get_program_blueprints') ? myco_get_program_blueprints() : [];

if (!$blueprint) {
    $blueprint = [
        'slug'        => $current_slug,
        'title'       => $current_title,
        'category'    => 'Program Track',
        'summary'     => get_the_excerpt($program_id),
        'image'       => '',
        'accent'      => '#C8402E',
        'accent_soft' => 'rgba(200,64,46,0.10)',
        'overview'    => [],
        'stats'       => [],
        'experience'  => [],
        'journey'     => [],
        'outcomes'    => [],
        'schedule'    => '',
        'age_group'   => '',
        'location'    => '',
        'fee'         => '',
        'duration'    => '',
        'capacity'    => '',
        'skill_level' => '',
        'cta_title'   => 'Want to learn more about this program?',
        'cta_copy'    => 'Reach out to the MYCO team and we will help you find the right next step.',
        'coordinator' => [],
    ];
}

$program_slug = !empty($blueprint['slug']) ? $blueprint['slug'] : $current_slug;
$normalize    = static function ($value) {
    $value = trim((string) $value);
    return $value === '' ? '' : str_replace(['â€¢', '•'], ' | ', $value);
};

$category_terms = get_the_terms($program_id, 'program_category');
$category_name  = ($category_terms && !is_wp_error($category_terms)) ? $category_terms[0]->name : '';
$category_name  = $category_name ?: ($blueprint['category'] ?? 'Program Track');

$summary = get_the_excerpt($program_id) ?: ($blueprint['summary'] ?? '');
$image   = get_the_post_thumbnail_url($program_id, 'full') ?: ($blueprint['image'] ?? myco_theme_asset_url('assets/images/Galleries/myco-youth-team-award-check-winners.jpg'));
$accent  = '#C8402E';
$soft    = 'rgba(200,64,46,0.10)';
$navy    = '#141943';

$raw_content   = trim((string) get_post_field('post_content', $program_id));
$content_html  = $raw_content !== '' ? apply_filters('the_content', $raw_content) : '';
$content_plain = wp_strip_all_tags($raw_content);

$overview = !empty($blueprint['overview']) && is_array($blueprint['overview']) ? array_values(array_filter(array_map('trim', $blueprint['overview']))) : [];
if (empty($overview) && $content_plain !== '') {
    $overview = preg_split('/\R{2,}/', trim($content_plain));
}
if (empty($overview) && $summary) {
    $overview = [$summary];
}

$experience = [];
$acf_features = myco_get_field('program_features');
if (is_array($acf_features)) {
    foreach ($acf_features as $feature) {
        $title = trim((string) ($feature['title'] ?? ''));
        $desc  = trim((string) ($feature['description'] ?? ''));
        if ($title !== '' || $desc !== '') {
            $experience[] = ['title' => $title ?: 'Program Focus', 'description' => $desc];
        }
    }
}
if (empty($experience)) {
    $experience = (!empty($blueprint['experience']) && is_array($blueprint['experience'])) ? $blueprint['experience'] : [];
}

$journey  = (!empty($blueprint['journey']) && is_array($blueprint['journey'])) ? $blueprint['journey'] : [];
$outcomes = (!empty($blueprint['outcomes']) && is_array($blueprint['outcomes'])) ? $blueprint['outcomes'] : [];
$stats    = (!empty($blueprint['stats']) && is_array($blueprint['stats'])) ? array_slice($blueprint['stats'], 0, 3) : [];

$schedule    = $normalize(myco_get_field('program_schedule', false, $blueprint['schedule'] ?? 'Contact us for current times'));
$age_group   = $normalize(myco_get_field('program_age_group', false, $blueprint['age_group'] ?? 'Open to youth participants'));
$location    = $normalize(myco_get_field('program_location', false, $blueprint['location'] ?? 'MYCO campus'));
$fee         = $normalize(myco_get_field('program_fee', false, $blueprint['fee'] ?? 'Contact us for details'));
$duration    = $normalize(myco_get_field('program_duration', false, $blueprint['duration'] ?? 'Program-specific timing'));
$capacity    = $normalize(myco_get_field('program_capacity', false, $blueprint['capacity'] ?? 'Limited seats'));
$skill_level = $normalize(myco_get_field('program_skill_level', false, $blueprint['skill_level'] ?? 'All levels welcome'));

if (empty($stats)) {
    $stats = [
        ['value' => $age_group ?: 'All youth', 'label' => 'age group'],
        ['value' => $duration ?: 'Seasonal format', 'label' => 'format'],
        ['value' => $capacity ?: 'Limited seats', 'label' => 'capacity'],
    ];
}

$quick_facts = array_values(array_filter([
    ['label' => 'Schedule', 'value' => $schedule],
    ['label' => 'Age Group', 'value' => $age_group],
    ['label' => 'Location', 'value' => $location],
    ['label' => 'Duration', 'value' => $duration],
    ['label' => 'Capacity', 'value' => $capacity],
    ['label' => 'Fee', 'value' => $fee],
    ['label' => 'Skill Level', 'value' => $skill_level],
], static function ($item) {
    return !empty($item['value']);
}));

$coordinator = !empty($blueprint['coordinator']) && is_array($blueprint['coordinator']) ? $blueprint['coordinator'] : [];
$contact_url = myco_get_contact_page_url(['program' => $program_slug]);
$programs_url = myco_get_page_url('programs', '/programs/');
$related = [];
foreach ($all_programs as $slug => $item) {
    if ($slug !== $program_slug) {
        $related[$slug] = $item + ['slug' => $slug];
    }
}
?>

<main class="program-detail" style="--program-accent: <?php echo esc_attr($accent); ?>; --program-soft: <?php echo esc_attr($soft); ?>; --program-navy: <?php echo esc_attr($navy); ?>;">
    <style>
        .program-detail{background:linear-gradient(180deg,#f7f9ff 0%,#eff3fb 30%,#f8fafc 100%);color:#141943}
        .program-detail img{display:block;max-width:100%}
        .program-detail__hero{position:relative;overflow:hidden;padding:32px 0 40px;background-color:#141943 !important;color:#ffffff}
        .program-detail__hero::before,.program-detail__hero::after{content:"";position:absolute;pointer-events:none}
        .program-detail__hero::before{z-index:0;width:420px;height:420px;left:-120px;top:-160px;border-radius:999px;background:radial-gradient(circle,rgba(200,64,46,.22) 0%,rgba(200,64,46,0) 72%)}
        .program-detail__hero::after{inset:0;z-index:0;background-color:#141943;background-image:radial-gradient(circle at 82% 24%,rgba(255,255,255,.14) 0%,rgba(255,255,255,0) 32%),linear-gradient(135deg,#141943 0%,#1c275b 52%,#243774 100%)}
        .program-detail__hero-grid,.program-detail__layout{position:relative;z-index:1;display:grid;gap:28px}
        .program-detail__hero-grid{grid-template-columns:minmax(0,1fr);align-items:start;min-height:340px}
        .program-detail__crumbs{display:inline-flex;flex-wrap:wrap;gap:10px;margin-bottom:14px;color:rgba(255,255,255,.72);font-size:.82rem;font-weight:700}
        .program-detail__crumbs a{color:inherit;text-decoration:none}
        .program-detail__eyebrow{display:inline-flex;align-items:center;min-height:38px;padding:0 16px;border-radius:999px;background:rgba(255,255,255,.96);border:1px solid rgba(255,255,255,.22);box-shadow:0 14px 30px rgba(9,13,34,.18);color:var(--program-accent);font-size:.74rem;font-weight:800;letter-spacing:.11em;text-transform:uppercase}
        .program-detail__title{margin:14px 0 10px;max-width:none;color:#ffffff;font-size:clamp(2.2rem,4vw,4rem);line-height:.98;letter-spacing:-.055em;font-weight:900}
        .program-detail__summary{max-width:600px;margin:0;color:rgba(255,255,255,.82);font-size:clamp(.95rem,1.25vw,1.04rem);line-height:1.64}
        .program-detail__actions{display:flex;flex-wrap:wrap;align-items:center;gap:12px;margin-top:20px}
        .program-detail__button{display:inline-flex;align-items:center;justify-content:center;min-height:50px;padding:0 22px;border-radius:999px;font-size:.92rem;font-weight:800;text-decoration:none;transition:transform .22s ease,box-shadow .22s ease,border-color .22s ease}
        .program-detail__button:hover,.program-detail__button:focus-visible{transform:translateY(-2px)}
        .program-detail__button--primary{background:var(--program-accent);color:#fff;box-shadow:0 18px 34px rgba(200,64,46,.24)}
        .program-detail__button--secondary{background:rgba(255,255,255,.08);color:#ffffff;border:1px solid rgba(255,255,255,.18);box-shadow:0 14px 28px rgba(9,13,34,.16)}
        .program-detail__stats{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;max-width:560px;margin-top:16px}
        .program-detail__stat,.program-detail__panel,.program-detail__sidecard,.program-detail__related-card{background:rgba(255,255,255,.92);border:1px solid rgba(20,25,67,.08);box-shadow:0 20px 48px rgba(20,25,67,.07)}
        .program-detail__stat{display:flex;flex-direction:column;justify-content:center;min-height:84px;padding:13px 14px 12px;border-radius:18px;border:1px solid rgba(255,255,255,.14);border-top:3px solid rgba(200,64,46,.42);background:linear-gradient(180deg,rgba(255,255,255,.14) 0%,rgba(255,255,255,.08) 100%);backdrop-filter:blur(8px);box-shadow:0 16px 34px rgba(9,13,34,.15)}
        .program-detail__stat strong{display:block;color:#ffffff;font-size:.98rem;line-height:1.06;font-weight:900}
        .program-detail__stat span{display:block;margin-top:4px;color:rgba(255,255,255,.72);font-size:.64rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase}
        .program-detail__body{padding:24px 0 88px}
        .program-detail__layout{grid-template-columns:minmax(0,1fr) minmax(275px,330px);align-items:start}
        .program-detail__stack,.program-detail__facts,.program-detail__links{display:grid;gap:22px}
        .program-detail__sidebar{display:grid;gap:16px;align-self:start}
        .program-detail__panel{position:relative;overflow:hidden;padding:34px;border-radius:32px}
        .program-detail__panel--accent::before{content:"";position:absolute;left:0;top:0;bottom:0;width:5px;background:linear-gradient(180deg,var(--program-accent) 0%,rgba(20,25,67,.18) 100%)}
        .program-detail__kicker{display:inline-flex;align-items:center;gap:10px;margin-bottom:14px;color:var(--program-accent);font-size:.77rem;font-weight:800;letter-spacing:.11em;text-transform:uppercase}
        .program-detail__kicker::before{content:"";width:34px;height:2px;border-radius:999px;background:currentColor}
        .program-detail__panel h2{margin:0;font-size:clamp(1.8rem,3vw,2.5rem);line-height:1.08;font-weight:900;letter-spacing:-.04em}
        .program-detail__panel-copy{margin:16px 0 0;color:#5a6579;font-size:1rem;line-height:1.8}
        .program-detail__overview{display:grid;gap:18px;margin-top:22px}
        .program-detail__overview p,.program-detail__richtext{color:#59657a;font-size:1rem;line-height:1.82}
        .program-detail__highlight{margin-top:24px;padding:22px 24px;border-radius:24px;background:linear-gradient(135deg,var(--program-soft) 0%,rgba(255,255,255,.95) 100%);border:1px solid rgba(20,25,67,.07)}
        .program-detail__highlight strong{display:block;margin-bottom:8px;color:var(--program-accent);font-size:.82rem;font-weight:800;letter-spacing:.11em;text-transform:uppercase}
        .program-detail__highlight p{margin:0;color:#243057;font-weight:700;line-height:1.7}
        .program-detail__cards,.program-detail__outcomes,.program-detail__related{display:grid;gap:20px}
        .program-detail__cards,.program-detail__outcomes{grid-template-columns:repeat(2,minmax(0,1fr));margin-top:26px}
        .program-detail__feature,.program-detail__outcome,.program-detail__fact{padding:24px;border-radius:24px;background:#fbfcff;border:1px solid rgba(20,25,67,.08)}
        .program-detail__feature span{display:inline-flex;align-items:center;justify-content:center;width:44px;height:44px;border-radius:14px;background:rgba(20,25,67,.08);color:var(--program-accent);font-size:.84rem;font-weight:900}
        .program-detail__feature h3,.program-detail__step h3,.program-detail__related-card h3{margin:14px 0 10px;font-size:1.16rem;line-height:1.24;font-weight:800}
        .program-detail__feature p,.program-detail__step p,.program-detail__outcome p,.program-detail__related-card p{margin:0;color:#607086;font-size:.97rem;line-height:1.72}
        .program-detail__timeline{display:grid;gap:18px;margin-top:26px}
        .program-detail__step{display:grid;grid-template-columns:88px minmax(0,1fr);gap:20px;align-items:start;padding:24px;border-radius:26px;background:#f9fbff;border:1px solid rgba(20,25,67,.08)}
        .program-detail__step strong{display:inline-flex;align-items:center;justify-content:center;min-height:66px;border-radius:18px;background:linear-gradient(135deg,var(--program-navy) 0%,#1e2d68 100%);color:#fff;font-size:1.02rem;font-weight:900;letter-spacing:.08em;box-shadow:0 18px 34px rgba(20,25,67,.14)}
        .program-detail__outcome{display:flex;gap:14px}
        .program-detail__outcome::before{content:"\2713";display:inline-flex;align-items:center;justify-content:center;flex:0 0 28px;width:28px;height:28px;margin-top:2px;border-radius:10px;background:rgba(200,64,46,.12);border:1px solid rgba(200,64,46,.22);color:var(--program-accent);font-size:.82rem;font-weight:900;box-shadow:0 10px 22px rgba(200,64,46,.12)}
        .program-detail__sidecard{padding:20px;border-radius:24px}
        .program-detail__sidecard--primary{background:linear-gradient(180deg,#edf2ff 0%,#f8faff 100%);border-color:rgba(20,25,67,.12);box-shadow:0 24px 54px rgba(20,25,67,.12)}
        .program-detail__sidecard--coordinator{background:linear-gradient(180deg,#ffffff 0%,#f8faff 100%)}
        .program-detail__sidecard h3{margin:0;font-size:1.08rem;line-height:1.2;font-weight:900}
        .program-detail__facts{margin-top:14px;gap:12px}
        .program-detail__fact{padding:16px 18px;border-radius:20px}
        .program-detail__fact strong{display:block;margin-bottom:5px;color:#74809a;font-size:.66rem;font-weight:800;letter-spacing:.11em;text-transform:uppercase}
        .program-detail__fact span{display:block;font-size:.94rem;line-height:1.45;font-weight:800;color:#141943}
        .program-detail__sidecard p{margin:10px 0 0;color:#5b677d;font-size:.92rem;line-height:1.62}
        .program-detail__coordinator-name{margin-top:12px !important;color:#141943;font-size:1rem !important;line-height:1.28 !important;font-weight:800}
        .program-detail__coordinator-role{margin-top:6px !important;color:#6a7388 !important;font-size:.9rem !important;line-height:1.55 !important}
        .program-detail__links{margin-top:14px;padding-top:14px;border-top:1px solid rgba(20,25,67,.08);gap:8px}
        .program-detail__links a{color:#141943;font-size:.92rem;line-height:1.45;font-weight:700;text-decoration:none;word-break:break-word}
        .program-detail__contact-card{position:relative;overflow:hidden;padding:22px 20px;border-radius:24px;background:linear-gradient(180deg,#141943 0%,#1b2556 100%) !important;border:1px solid rgba(20,25,67,.16);box-shadow:0 28px 56px rgba(20,25,67,.18)}
        .program-detail__contact-card::before{content:"";position:absolute;inset:auto -80px -90px auto;width:180px;height:180px;border-radius:999px;background:radial-gradient(circle,rgba(212,70,48,.34) 0%,rgba(212,70,48,0) 72%);pointer-events:none}
        .program-detail__contact-card h3{position:relative;z-index:1;color:#ffffff;font-size:1.14rem}
        .program-detail__contact-card-copy{position:relative;z-index:1;margin-top:12px !important;color:rgba(255,255,255,.78) !important;font-size:.95rem !important;line-height:1.7 !important}
        .program-detail__contact-card .program-detail__actions{position:relative;z-index:1;margin-top:18px}
        .program-detail__contact-card .program-detail__button--primary{width:100%;min-height:54px;background:#d44630;box-shadow:0 18px 36px rgba(212,70,48,.34)}
        .program-detail__cta-support{position:relative;z-index:1;margin-top:14px !important;color:rgba(255,255,255,.74) !important;font-size:.92rem !important;line-height:1.55 !important;text-align:center}
        .program-detail__cta-support a{color:#ff7b64;text-decoration:underline;font-weight:700}
        .program-detail__related-wrap{margin-top:20px}
        .program-detail__related-head{display:flex;flex-wrap:wrap;align-items:end;justify-content:space-between;gap:18px;margin-bottom:24px}
        .program-detail__related-head p{max-width:640px;margin:12px 0 0;color:#5c677d;font-size:1rem;line-height:1.72}
        .program-detail__related{grid-template-columns:repeat(3,minmax(0,1fr))}
        .program-detail__related-card{display:flex;flex-direction:column;height:100%;overflow:hidden;border-radius:26px;text-decoration:none;transition:transform .24s ease,box-shadow .24s ease,border-color .24s ease}
        .program-detail__related-card:hover,.program-detail__related-card:focus-visible{transform:translateY(-5px);border-color:rgba(20,25,67,.14);box-shadow:0 24px 48px rgba(20,25,67,.11)}
        .program-detail__related-media{position:relative;flex:0 0 210px;height:210px;min-height:0;background:#d8e0ed}
        .program-detail__related-media::after{content:"";position:absolute;inset:0;background:linear-gradient(180deg,rgba(20,25,67,.12) 0%,rgba(20,25,67,.46) 100%)}
        .program-detail__related-media img{width:100%;height:100%;object-fit:cover}
        .program-detail__related-tag{position:absolute;top:16px;left:16px;z-index:1;padding:8px 12px;border-radius:999px;background:rgba(255,255,255,.92);font-size:.68rem;font-weight:800;letter-spacing:.09em;text-transform:uppercase}
        .program-detail__related-body{display:flex;flex:1;flex-direction:column;gap:10px;padding:18px 18px 16px}
        .program-detail__related-body h3{font-size:1rem;line-height:1.26}
        .program-detail__related-body p{display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;font-size:.9rem;line-height:1.6}
        .program-detail__related-link{margin-top:auto;color:var(--program-accent);font-size:.8rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase}
        @media (min-width:1024px){.program-detail__sidebar{position:sticky;top:28px}}
        @media (max-width:1160px){.program-detail__related{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media (max-width:1024px){.program-detail__hero-grid,.program-detail__layout{grid-template-columns:1fr}.program-detail__title{max-width:none}.program-detail__sidebar{position:static}}
        @media (max-width:767px){.program-detail__hero{padding:28px 0 38px}.program-detail__hero::after{background-image:radial-gradient(circle at 78% 86%,rgba(255,255,255,.10) 0%,rgba(255,255,255,0) 34%),linear-gradient(135deg,#141943 0%,#1c275b 52%,#243774 100%)}.program-detail__hero-grid{min-height:auto}.program-detail__title{font-size:clamp(2.05rem,10vw,3.05rem)}.program-detail__summary{font-size:.95rem}.program-detail__button{width:100%}.program-detail__stats,.program-detail__cards,.program-detail__outcomes,.program-detail__related{grid-template-columns:1fr}.program-detail__stats{max-width:none}.program-detail__body{padding-top:18px}.program-detail__stat{min-height:auto;padding:16px 14px}.program-detail__panel,.program-detail__sidecard,.program-detail__contact-card{padding:18px 16px;border-radius:24px}.program-detail__fact{padding:14px 16px}.program-detail__related-media{flex-basis:190px;height:190px}}
    </style>

    <section class="program-detail__hero">
        <div class="inner program-detail__hero-grid">
            <div>
                <nav class="program-detail__crumbs" aria-label="Breadcrumb">
                    <a href="<?php echo esc_url(home_url('/')); ?>">Home</a>
                    <span aria-hidden="true">/</span>
                    <a href="<?php echo esc_url($programs_url); ?>">Programs</a>
                    <span aria-hidden="true">/</span>
                    <span><?php echo esc_html($current_title ?: ($blueprint['title'] ?? 'Program Detail')); ?></span>
                </nav>
                <span class="program-detail__eyebrow"><?php echo esc_html($category_name); ?></span>
                <h1 class="program-detail__title"><?php echo esc_html($current_title ?: ($blueprint['title'] ?? 'Program Detail')); ?></h1>
                <p class="program-detail__summary"><?php echo esc_html($summary); ?></p>
                <div class="program-detail__actions">
                    <a class="program-detail__button program-detail__button--primary" href="<?php echo esc_url($contact_url); ?>">Ask About Registration</a>
                    <a class="program-detail__button program-detail__button--secondary" href="<?php echo esc_url($programs_url); ?>">Explore All Programs</a>
                </div>
                <div class="program-detail__stats">
                    <?php foreach ($stats as $stat) : ?>
                        <div class="program-detail__stat">
                            <strong><?php echo esc_html($stat['value'] ?? ''); ?></strong>
                            <span><?php echo esc_html($stat['label'] ?? 'detail'); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </section>

    <section class="program-detail__body">
        <div class="inner program-detail__layout">
            <div class="program-detail__stack">
                <section class="program-detail__panel program-detail__panel--accent">
                    <span class="program-detail__kicker">Program Overview</span>
                    <h2>A richer path for growth, connection, and practical development.</h2>
                    <div class="program-detail__overview">
                        <?php foreach ($overview as $paragraph) : ?>
                            <p><?php echo esc_html(trim((string) $paragraph)); ?></p>
                        <?php endforeach; ?>
                    </div>
                    <div class="program-detail__highlight">
                        <strong>Why this matters</strong>
                        <p><?php echo esc_html($summary); ?></p>
                    </div>
                </section>

                <?php if (!empty($experience)) : ?>
                    <section class="program-detail__panel">
                        <span class="program-detail__kicker">Inside The Experience</span>
                        <h2>What participants do inside this track.</h2>
                        <p class="program-detail__panel-copy">Each part of the program is designed to move youth from passive attendance into real engagement, confidence, and community connection.</p>
                        <div class="program-detail__cards">
                            <?php foreach ($experience as $index => $item) : ?>
                                <article class="program-detail__feature">
                                    <span><?php echo esc_html(str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)); ?></span>
                                    <h3><?php echo esc_html($item['title'] ?? 'Program Focus'); ?></h3>
                                    <p><?php echo esc_html($item['description'] ?? ''); ?></p>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <?php if (!empty($journey)) : ?>
                    <section class="program-detail__panel">
                        <span class="program-detail__kicker">How It Flows</span>
                        <h2>A simple journey that builds momentum over time.</h2>
                        <p class="program-detail__panel-copy">This track is structured so growth happens in steps, with clear support and steady ownership along the way.</p>
                        <div class="program-detail__timeline">
                            <?php foreach ($journey as $step) : ?>
                                <article class="program-detail__step">
                                    <strong><?php echo esc_html($step['step'] ?? '01'); ?></strong>
                                    <div>
                                        <h3><?php echo esc_html($step['title'] ?? 'Program Step'); ?></h3>
                                        <p><?php echo esc_html($step['description'] ?? ''); ?></p>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <?php if (!empty($outcomes)) : ?>
                    <section class="program-detail__panel">
                        <span class="program-detail__kicker">Participant Outcomes</span>
                        <h2>What youth build through consistent participation.</h2>
                        <p class="program-detail__panel-copy">These outcomes reflect the kind of change MYCO is aiming for: stronger youth, healthier habits, and deeper belonging.</p>
                        <div class="program-detail__outcomes">
                            <?php foreach ($outcomes as $outcome) : ?>
                                <div class="program-detail__outcome">
                                    <p><?php echo esc_html($outcome); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <?php if ($content_html !== '') : ?>
                    <section class="program-detail__panel">
                        <span class="program-detail__kicker">Additional Details</span>
                        <h2>More information about this program.</h2>
                        <div class="program-detail__richtext"><?php echo wp_kses_post($content_html); ?></div>
                    </section>
                <?php endif; ?>
            </div>

            <aside class="program-detail__sidebar">
                <?php if (!empty($quick_facts)) : ?>
                    <section class="program-detail__sidecard program-detail__sidecard--primary">
                        <h3>Quick Facts</h3>
                        <div class="program-detail__facts">
                            <?php foreach ($quick_facts as $fact) : ?>
                                <div class="program-detail__fact">
                                    <strong><?php echo esc_html($fact['label']); ?></strong>
                                    <span><?php echo esc_html($fact['value']); ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <section class="program-detail__sidecard program-detail__sidecard--coordinator">
                    <h3>Program Coordinator</h3>
                    <p class="program-detail__coordinator-name"><?php echo esc_html($coordinator['name'] ?? 'MYCO Programs Team'); ?></p>
                    <p class="program-detail__coordinator-role"><?php echo esc_html($coordinator['role'] ?? 'Program Support'); ?></p>
                    <div class="program-detail__links">
                        <a href="mailto:<?php echo esc_attr(antispambot($coordinator['email'] ?? 'info@myco.org')); ?>"><?php echo esc_html(antispambot($coordinator['email'] ?? 'info@myco.org')); ?></a>
                        <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $coordinator['phone'] ?? '(614) 555-0100')); ?>"><?php echo esc_html($coordinator['phone'] ?? '(614) 555-0100'); ?></a>
                    </div>
                </section>

                <section class="program-detail__sidecard program-detail__contact-card">
                    <h3>Interested in This Program?</h3>
                    <p class="program-detail__contact-card-copy"><?php echo esc_html($blueprint['cta_copy'] ?? 'Reach out to the MYCO team and we will help you find the right next step.'); ?></p>
                    <div class="program-detail__actions">
                        <a class="program-detail__button program-detail__button--primary" href="<?php echo esc_url($contact_url); ?>">Contact Us&nbsp;&nbsp;&rarr;</a>
                    </div>
                    <p class="program-detail__cta-support">Questions? <a href="<?php echo esc_url($contact_url); ?>">Contact us</a></p>
                </section>
            </aside>
        </div>

        <?php if (!empty($related)) : ?>
            <div class="inner program-detail__related-wrap">
                <div class="program-detail__related-head">
                    <div>
                        <span class="program-detail__kicker">Explore More Tracks</span>
                        <h2>See how the full MYCO program journey fits together.</h2>
                        <p>Each track supports a different side of growth, from leadership and faith formation to education, athletics, belonging, and service.</p>
                    </div>
                    <a class="program-detail__button program-detail__button--secondary" href="<?php echo esc_url($programs_url); ?>">View all programs</a>
                </div>

                <div class="program-detail__related">
                    <?php foreach ($related as $slug => $item) : ?>
                        <a class="program-detail__related-card" href="<?php echo esc_url(myco_get_program_detail_url($slug)); ?>">
                            <div class="program-detail__related-media">
                                <img src="<?php echo esc_url($item['image'] ?? $image); ?>" alt="<?php echo esc_attr($item['title'] ?? 'Program'); ?>" loading="lazy">
                                <span class="program-detail__related-tag"><?php echo esc_html($item['category'] ?? 'Program'); ?></span>
                            </div>
                            <div class="program-detail__related-body">
                                <h3><?php echo esc_html($item['title'] ?? 'Program'); ?></h3>
                                <p><?php echo esc_html($item['summary'] ?? 'Learn more about this MYCO program track.'); ?></p>
                                <span class="program-detail__related-link">View details</span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php get_footer(); ?>
