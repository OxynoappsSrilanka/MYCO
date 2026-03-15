<?php
/**
 * Program Card
 * Used in homepage grid and programs listing
 *
 * @package MYCO
 */

$categories = get_the_terms(get_the_ID(), 'program_category');
$cat_name = $categories && !is_wp_error($categories) ? $categories[0]->name : '';
$img_url = myco_get_image_url(get_the_ID(), 'myco-card');

$schedule = myco_get_field('program_schedule');
$age_group = myco_get_field('program_age_group');

// Fallback schedule/age based on title keywords
if (!$schedule || !$age_group) {
    $title = strtolower(get_the_title());
    if (!$schedule) {
        if (strpos($title, 'basketball') !== false || strpos($title, 'fitness') !== false) $schedule = 'Weekly';
        elseif (strpos($title, 'mentorship') !== false || strpos($title, 'leadership') !== false) $schedule = 'Bi-weekly';
        elseif (strpos($title, 'community') !== false) $schedule = 'Monthly';
        else $schedule = 'Weekly';
    }
    if (!$age_group) {
        if (strpos($title, 'basketball') !== false || strpos($title, 'fitness') !== false) $age_group = 'Ages 12–18';
        elseif (strpos($title, 'community') !== false) $age_group = 'All Ages';
        else $age_group = 'Ages 13–22';
    }
}

// Better fallback images based on program title
if (strpos($img_url, 'hero-image.png') !== false) {
    $title = strtolower(get_the_title());
    if (strpos($title, 'leadership') !== false) {
        $img_url = MYCO_URI . '/assets/images/Galleries/myco-youth-team-award-check-winners.jpg';
    } elseif (strpos($title, 'spiritual') !== false || strpos($title, 'islamic') !== false || strpos($title, 'identity') !== false) {
        $img_url = MYCO_URI . '/assets/images/Galleries/myco-youth-basketball-event-congregational-prayer.jpg';
    } elseif (strpos($title, 'education') !== false || strpos($title, 'tutoring') !== false || strpos($title, 'homework') !== false) {
        $img_url = MYCO_URI . '/assets/images/Galleries/myco-youth-community-center-groundbreaking-ceremony.jpg';
    } elseif (strpos($title, 'basketball') !== false || strpos($title, 'fitness') !== false || strpos($title, 'athletic') !== false || strpos($title, 'sport') !== false) {
        $img_url = MYCO_URI . '/assets/images/Galleries/myco-basketball-champions-team-with-trophy.jpg.jpg';
    } elseif (strpos($title, 'social') !== false || strpos($title, 'cultural') !== false) {
        $img_url = MYCO_URI . '/assets/images/Galleries/myco-basketball-tournament-award-ceremony-team-celebration.jpg.JPG';
    } elseif (strpos($title, 'mentorship') !== false || strpos($title, 'mentor') !== false) {
        $img_url = MYCO_URI . '/assets/images/Galleries/myco-youth-team-award-check-winners.jpg';
    } else {
        $img_url = MYCO_URI . '/assets/images/Galleries/myco-youth-community-center-groundbreaking-ceremony.jpg';
    }
}
?>

<article class="program-card">
    <div class="program-card-image">
        <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy" />
        <?php if ($cat_name) : ?>
            <span class="program-category-badge"><?php echo esc_html(strtoupper($cat_name)); ?></span>
        <?php endif; ?>
    </div>
    
    <div class="program-card-content">
        <h3 class="program-card-title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        
        <p class="program-card-excerpt">
            <?php echo esc_html(get_the_excerpt()); ?>
        </p>

        <!-- Program Meta -->
        <div class="program-card-meta">
            <?php if ($schedule) : ?>
                <div class="meta-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span><?php echo esc_html($schedule); ?></span>
                </div>
            <?php endif; ?>
            
            <?php if ($age_group) : ?>
                <div class="meta-item">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    <span><?php echo esc_html($age_group); ?></span>
                </div>
            <?php endif; ?>
        </div>

        <a href="<?php the_permalink(); ?>" class="program-card-btn">
            <?php esc_html_e('Learn More', 'myco'); ?>
            <svg width="14" height="14" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </a>
    </div>
</article>
