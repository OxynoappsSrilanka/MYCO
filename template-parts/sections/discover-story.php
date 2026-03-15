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
$img_url   = $image ? (is_array($image) ? $image['url'] : wp_get_attachment_url($image)) : MYCO_URI . '/assets/images/about.png';
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

        <!-- Two-column layout: text (55%) + images (45%) -->
        <div class="section-layout">

            <!-- LEFT: Text Content Column -->
            <div class="story-text-column">

                <!-- Heading -->
                <h2 id="story-heading" class="story-heading">
                    <?php if ($heading) : ?>
                        <?php echo nl2br(esc_html($heading)); ?>
                    <?php else : ?>
                        WHY DEDICATED<br />
                        YOUTH CENTERS?
                    <?php endif; ?>
                </h2>

                <!-- Intro Paragraph -->
                <p class="story-intro">
                    <?php echo esc_html($paragraph); ?>
                </p>

                <!-- Three Points: Problem, Difference, Outcome -->
                <div class="story-points">
                    <div class="story-point">
                        <h3 class="story-point-title">The Problem</h3>
                        <p class="story-point-text">
                            Muslim youth in America need a welcoming space where they can feel a sense of belonging and understanding.
                        </p>
                    </div>
                    <div class="story-point">
                        <h3 class="story-point-title">The Difference</h3>
                        <p class="story-point-text">
                            MYCO meets Muslim youth where they're at today, providing a space to play, socialize, and meet mentors who guide them academically and spiritually.
                        </p>
                    </div>
                    <div class="story-point">
                        <h3 class="story-point-title">The Outcome</h3>
                        <p class="story-point-text">
                            Our youth cultivate strong Islamic identities, lift each other up, and go on to become leaders of their own communities.
                        </p>
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
