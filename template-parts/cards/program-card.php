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

// Better fallback images based on program title
if (strpos($img_url, 'hero-image.png') !== false) {
    $title = strtolower(get_the_title());
    
    // Map program titles to appropriate gallery images
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
        // Default fallback for any other program
        $img_url = MYCO_URI . '/assets/images/Galleries/myco-youth-community-center-groundbreaking-ceremony.jpg';
    }
}
?>

<div class="bg-white rounded-2xl overflow-hidden transition-all duration-200"
     style="box-shadow: 0 8px 24px rgba(20,25,67,0.08);"
     onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 36px rgba(20,25,67,0.14)'"
     onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 24px rgba(20,25,67,0.08)'">
    <div class="aspect-[16/10] overflow-hidden">
        <img src="<?php echo esc_url($img_url); ?>"
             alt="<?php echo esc_attr(get_the_title()); ?>"
             class="w-full h-full object-cover" loading="lazy" />
    </div>
    <div class="p-6">
        <?php if ($cat_name) : ?>
            <span class="inline-block text-xs font-semibold uppercase tracking-wider px-3 py-1 rounded-full mb-3"
                  style="background: rgba(200,64,46,0.1); color: #C8402E;">
                <?php echo esc_html($cat_name); ?>
            </span>
        <?php endif; ?>
        <h3 class="text-lg font-bold mb-2" style="color: #141943;">
            <a href="<?php the_permalink(); ?>" class="hover:text-red transition-colors">
                <?php the_title(); ?>
            </a>
        </h3>
        <p class="text-sm text-gray-500 leading-relaxed line-clamp-2">
            <?php echo esc_html(get_the_excerpt()); ?>
        </p>
    </div>
</div>
