<?php
/**
 * Discover Our Story Section
 * Matches source index.html layout exactly
 *
 * @package MYCO
 */

$heading   = myco_get_field('story_heading', false, '');
$paragraph = myco_get_field('story_paragraph', false, 'Because a strong Muslim identity isn\'t built by accident—it\'s built with community.');
$image     = myco_get_field('story_image');
$heading_clean = trim(preg_replace('/\s+/', ' ', (string) $heading));
$heading_display = '';
if ($heading_clean !== '') {
    $heading_display = ucwords(strtolower($heading_clean));
}
$fallback_rel  = '/assets/images/about.png';
$fallback_path = MYCO_DIR . $fallback_rel;
$fallback_ver  = file_exists($fallback_path) ? filemtime($fallback_path) : MYCO_VERSION;
$fallback_url  = add_query_arg('v', (string) $fallback_ver, MYCO_URI . $fallback_rel);

$attachment_id = 0;
$img_url       = $fallback_url;

if ($image) {
    $attachment_id = is_array($image) ? (int) ($image['ID'] ?? 0) : (int) $image;
    $img_url       = is_array($image) ? ($image['url'] ?? $fallback_url) : wp_get_attachment_url($image);

    if (!$img_url) {
        $img_url = $fallback_url;
    }
}

if ($attachment_id > 0) {
    $image_ver = get_post_modified_time('U', true, $attachment_id);
    if ($image_ver) {
        $img_url = add_query_arg('v', (string) $image_ver, $img_url);
    }
}
$stats     = myco_get_field('story_stats');
$default_stats = [
    ['number' => '2,500+', 'label' => 'Youth Served'],
    ['number' => '500+', 'label' => 'Mentors & Volunteers'],
    ['number' => '150+', 'label' => 'Programs Hosted'],
];
if (!$stats) $stats = $default_stats;
?>

<section id="discover-story" class="story-section w-full bg-white" aria-labelledby="story-heading">
    <div class="section-container">

        <!-- Section Heading -->
        <h2 id="story-heading" class="story-heading">
            <?php if ($heading_display !== '') : ?>
                <?php echo esc_html($heading_display); ?>
            <?php else : ?>
                Why Dedicated Youth Centers?
            <?php endif; ?>
        </h2>

        <!-- Section Intro -->
        <p class="story-intro">
            <?php echo esc_html($paragraph); ?>
        </p>

        <!-- Two-column layout: text (55%) + images (45%) -->
        <div class="section-layout">

            <!-- LEFT: Text Content Column -->
            <div class="story-text-column">

                <!-- Three Points: Problem, Difference, Outcome -->
                <div class="story-points-stage">
                    <div class="story-points" role="list" aria-label="MYCO youth impact story points">
                        <div class="story-point-border story-point-first" role="listitem">
                            <div class="story-point">
                                <div class="story-point-content">
                                    <h3 class="story-point-title">The Problem</h3>
                                    <p class="story-point-text">
                                        Muslim youth in America need a welcoming space where they can feel a sense of belonging and understanding.
                                    </p>
                                </div>
                                <span class="story-point-number" aria-hidden="true">01</span>
                            </div>
                        </div>
                        <div class="story-point-border story-point-middle" role="listitem">
                            <div class="story-point">
                                <div class="story-point-content">
                                    <h3 class="story-point-title">The Difference</h3>
                                    <p class="story-point-text">
                                        MYCO meets Muslim youth where they're at today, providing a space to play, socialize, and meet mentors who guide them academically and spiritually.
                                    </p>
                                </div>
                                <span class="story-point-number" aria-hidden="true">02</span>
                            </div>
                        </div>
                        <div class="story-point-border story-point-last" role="listitem">
                            <div class="story-point">
                                <div class="story-point-content">
                                    <h3 class="story-point-title">The Outcome</h3>
                                    <p class="story-point-text">
                                        Our youth cultivate strong Islamic identities, lift each other up, and go on to become leaders of their own communities.
                                    </p>
                                </div>
                                <span class="story-point-number" aria-hidden="true">03</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Row -->
                <div class="stats-row">
                    <?php foreach ($stats as $stat) : ?>
                    <div class="story-stat">
                        <span class="story-stat-number"><?php echo esc_html($stat['number']); ?></span>
                        <span class="story-stat-label"><?php echo esc_html($stat['label']); ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- RIGHT: Single Image Column -->
            <div class="story-image-column">
                <div class="single-image-wrapper">
                    <img src="<?php echo esc_url($img_url); ?>" alt="<?php esc_attr_e('Diverse Muslim youth gathering community', 'myco'); ?>" loading="lazy" />
                </div>
            </div>

        </div>

    </div>
</section>
