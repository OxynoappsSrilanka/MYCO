<?php
/**
 * Programs & Support Section
 * 6 core programs with icons in 3x2 grid layout
 * @package MYCO
 */
$programs = myco_get_field('homepage_programs');
$defaults = [
    [
        'title' => 'Youth Leadership Development',
        'desc' => 'Helping youth build confidence, communication skills, teamwork, responsibility, and leadership rooted in Islamic values.',
        'icon' => '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M24 44c11.046 0 20-8.954 20-20S35.046 4 24 4 4 12.954 4 24s8.954 20 20 20Z" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M24 16v8l5.5 5.5" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'image' => 'myco-youth-team-award-check-winners.jpg'
    ],
    [
        'title' => 'Spiritual Development',
        'desc' => 'Lectures, youth halaqas, Islamic learning opportunities, and guidance that strengthens faith and identity.',
        'icon' => '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M24 4L8 12v12c0 10 6.5 19.5 16 22 9.5-2.5 16-12 16-22V12L24 4Z" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><circle cx="24" cy="22" r="6" stroke="currentColor" stroke-width="3"/></svg>',
        'image' => 'myco-youth-basketball-event-congregational-prayer.jpg'
    ],
    [
        'title' => 'Education & Skill Building',
        'desc' => 'Support through educational initiatives such as computer literacy, counseling, learning support, and developmental programming.',
        'icon' => '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 18L24 8l20 10-20 10L4 18Z" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 22v10c0 4 6.268 8 14 8s14-4 14-8V22" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M40 20v14" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>',
        'image' => 'myco-youth-community-center-groundbreaking-ceremony.jpg'
    ],
    [
        'title' => 'Athletics & Training',
        'desc' => 'Basketball, soccer, and other active programming that builds discipline, confidence, and brotherhood/sisterhood.',
        'icon' => '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="24" cy="24" r="18" stroke="currentColor" stroke-width="3"/><path d="M24 6v36M6 24h36M12 10c4 4 4 10 4 14s0 10-4 14M36 10c-4 4-4 10-4 14s0 10 4 14" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>',
        'image' => 'myco-basketball-champions-team-with-trophy.jpg.jpg'
    ],
    [
        'title' => 'Social & Cultural Activities',
        'desc' => 'Gatherings that foster belonging, friendship, and community connection across backgrounds.',
        'icon' => '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M36 40v-4c0-4.418-3.582-8-8-8H20c-4.418 0-8 3.582-8 8v4" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><circle cx="24" cy="16" r="6" stroke="currentColor" stroke-width="3"/><path d="M42 40v-4c0-2.5-1.5-4.5-4-5.5M34 6c2.5 1 4 3 4 5.5s-1.5 4.5-4 5.5" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'image' => 'myco-basketball-tournament-award-ceremony-team-celebration.jpg.JPG'
    ],
    [
        'title' => 'Community Service & Innovation',
        'desc' => 'Volunteer initiatives that teach youth to serve others and contribute meaningfully to their communities.',
        'icon' => '<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M34 24c0-5.523-4.477-10-10-10s-10 4.477-10 10c0 8 10 18 10 18s10-10 10-18Z" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><circle cx="24" cy="24" r="3" stroke="currentColor" stroke-width="3"/><path d="M24 4v6M44 24h-6M24 44v-6M4 24h6" stroke="currentColor" stroke-width="3" stroke-linecap="round"/></svg>',
        'image' => 'MCYC Groundbreaking_ Aatifa.jpg'
    ],
];
if (!$programs) $programs = $defaults;
?>

<section id="programs-support" aria-labelledby="programs-heading"
    class="w-full bg-[#F3F4F6] pt-16 pb-20 md:pt-20 md:pb-24">
    <div class="max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Section Header -->
        <div class="flex flex-col items-center text-center mb-12 md:mb-14 gap-4">
            <span style="color: #C8402E; font-weight: 700; font-size: 0.82rem; letter-spacing: 0.08em; text-transform: uppercase;">
                <?php echo esc_html(myco_get_field('programs_label', false, 'Our Programs')); ?>
            </span>
            <h2 id="programs-heading" class="font-inter tracking-tight" style="color: #141943; font-weight: 800; font-size: clamp(2rem, 5vw, 3.4rem); line-height: 1.1; max-width: 780px;">
                <?php $h = myco_get_field('programs_heading', false, ''); if ($h) { echo esc_html($h); } else { ?>Empowering Youth Through<br />Comprehensive Programs<?php } ?>
            </h2>
            <p style="color: #5B6575; font-size: 1.1rem; line-height: 1.65; max-width: 680px; margin-top: 8px;">
                <?php echo esc_html(myco_get_field('programs_description', false, 'Building character, faith, and skills through diverse opportunities for growth and development')); ?>
            </p>
        </div>

        <!-- 3x2 Cards Grid with Background Images -->
        <div class="homepage-programs-grid">
            <?php foreach ($programs as $index => $p) :
                $bg_image = !empty($p['image']) ? MYCO_URI . '/assets/images/Galleries/' . $p['image'] : '';
                $card_number = str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
                $card_tag = strtok((string) ($p['title'] ?? ''), ' ');
                $program_url = function_exists('myco_get_program_detail_url')
                    ? myco_get_program_detail_url($p['title'] ?? '')
                    : home_url('/programs/' . sanitize_title($p['title'] ?? '') . '/');
            ?>
            <a href="<?php echo esc_url($program_url); ?>" class="homepage-program-card" aria-label="<?php echo esc_attr(sprintf(__('View details for %s', 'myco'), $p['title'] ?? __('program', 'myco'))); ?>">
                <div class="homepage-program-card-media"<?php if ($bg_image) : ?> style="background-image: linear-gradient(180deg, rgba(20, 25, 67, 0.16) 0%, rgba(20, 25, 67, 0.72) 100%), url('<?php echo esc_url($bg_image); ?>');"<?php endif; ?>>
                    <span class="homepage-program-card-tag"><?php echo esc_html($card_tag ?: 'MYCO'); ?></span>
                    <span class="homepage-program-card-index" aria-hidden="true"><?php echo esc_html($card_number); ?></span>
                </div>

                <div class="homepage-program-card-body">
                    <h3 class="homepage-program-card-title"><?php echo esc_html($p['title']); ?></h3>
                    <p class="homepage-program-card-description"><?php echo esc_html($p['desc'] ?? $p['description'] ?? ''); ?></p>
                    <div class="homepage-program-card-divider" aria-hidden="true"></div>
                    <div class="homepage-program-card-meta-row">
                        <span class="homepage-program-card-meta-label">Program track</span>
                        <span class="homepage-program-card-meta-icon" aria-hidden="true">&#8599;</span>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>
