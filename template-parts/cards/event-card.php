<?php
/**
 * Event Card
 *
 * @package MYCO
 */

$event_date = myco_get_field('event_date');
$date_parts = myco_format_event_date($event_date);
$start_time = myco_get_field('event_start_time');
$location   = myco_get_field('event_location_name', false, 'MYCO Community Space');
$categories = get_the_terms(get_the_ID(), 'event_category');
$cat_name   = $categories && !is_wp_error($categories) ? $categories[0]->name : '';
$img_url    = myco_get_image_url(get_the_ID(), 'myco-card');
?>

<div class="bg-white rounded-2xl overflow-hidden transition-all duration-200"
     style="box-shadow: 0 8px 24px rgba(20,25,67,0.08);"
     onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 36px rgba(20,25,67,0.14)'"
     onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 24px rgba(20,25,67,0.08)'">
    <div class="relative aspect-[16/10] overflow-hidden">
        <img src="<?php echo esc_url($img_url); ?>"
             alt="<?php echo esc_attr(get_the_title()); ?>"
             class="w-full h-full object-cover" loading="lazy" />
        <?php if ($date_parts) : ?>
        <div class="absolute top-4 left-4 bg-white rounded-xl px-3 py-2 text-center" style="box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
            <div class="text-xs font-bold uppercase" style="color: #C8402E;"><?php echo esc_html($date_parts['month']); ?></div>
            <div class="text-xl font-black" style="color: #141943;"><?php echo esc_html($date_parts['day']); ?></div>
        </div>
        <?php endif; ?>
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
        <div class="flex items-center gap-4 text-sm text-gray-400 mt-3">
            <?php if ($start_time) : ?>
            <span class="flex items-center gap-1">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                <?php echo esc_html($start_time); ?>
            </span>
            <?php endif; ?>
            <span class="flex items-center gap-1">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <?php echo esc_html($location); ?>
            </span>
        </div>
    </div>
</div>
