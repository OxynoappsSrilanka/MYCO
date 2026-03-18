<?php
/**
 * Event Card
 *
 * @package MYCO
 */

$event_date       = myco_get_field('event_date');
$date_parts       = myco_format_event_date($event_date);
$start_time       = myco_get_field('event_start_time');
$end_time         = myco_get_field('event_end_time');
$location_name    = myco_get_field('event_location_name', false, 'MYCO Community Space');
$location_address = myco_get_field('event_location_address');
$categories       = get_the_terms(get_the_ID(), 'event_category');
$category_slugs   = $categories && !is_wp_error($categories) ? wp_list_pluck($categories, 'slug') : [];
$category_names   = $categories && !is_wp_error($categories) ? wp_list_pluck($categories, 'name') : [];
$image_url        = myco_get_image_url(get_the_ID(), 'large');
$event_url        = myco_get_event_permalink(get_the_ID());
$content_source   = has_excerpt() ? get_the_excerpt() : wp_strip_all_tags(get_post_field('post_content', get_the_ID()));
$excerpt          = wp_trim_words($content_source, 22, '...');
$time_label       = $start_time ? $start_time . ($end_time ? ' - ' . $end_time : '') : '';
$event_month      = $event_date ? wp_date('Y-m', strtotime($event_date)) : '';
$badge_month      = $event_date ? strtoupper(wp_date('F', strtotime($event_date))) : '';
$address_lines    = $location_address ? preg_split('/\r\n|\r|\n/', wp_strip_all_tags($location_address)) : [];
$address_lines    = array_values(array_filter(array_map('trim', (array) $address_lines)));
$address_line     = '';

if (!empty($address_lines)) {
    $address_line = end($address_lines);
} elseif ($location_address) {
    $address_line = preg_replace('/\s+/', ' ', trim(str_replace(["\r\n", "\r", "\n"], ' ', wp_strip_all_tags($location_address))));
}

if ($address_line) {
    $address_line = preg_replace('/\s+\d{5}(?:-\d{4})?$/', '', $address_line);
    $address_line = '(' . trim($address_line, '() ') . ')';
}

$search_index = implode(
    ' ',
    array_filter(
        [
            get_the_title(),
            $excerpt,
            $time_label,
            $location_name,
            $location_address,
            $address_line,
            $event_date,
            $badge_month,
            implode(' ', $category_names),
        ]
    )
);

$search_index = function_exists('mb_strtolower') ? mb_strtolower($search_index) : strtolower($search_index);
?>

<article
    class="event-card event-item"
    data-categories="<?php echo esc_attr(implode(',', $category_slugs)); ?>"
    data-event-month="<?php echo esc_attr($event_month); ?>"
    data-event-date="<?php echo esc_attr($event_date); ?>"
    data-search="<?php echo esc_attr($search_index); ?>"
>
    <div class="event-card__poster">
        <img
            src="<?php echo esc_url($image_url); ?>"
            alt="<?php echo esc_attr(get_the_title()); ?>"
            loading="lazy"
        />
        <div class="event-card__poster-overlay"></div>
        <?php if ($date_parts) : ?>
            <div class="event-card__date">
                <div class="event-card__date-day"><?php echo esc_html($date_parts['day']); ?></div>
                <div class="event-card__date-month"><?php echo esc_html($badge_month ? $badge_month : $date_parts['month']); ?></div>
            </div>
        <?php endif; ?>
    </div>

    <div class="event-card__body">
        <h3 class="event-card__title">
            <a href="<?php echo esc_url($event_url); ?>"><?php the_title(); ?></a>
        </h3>

        <?php if ($excerpt) : ?>
            <p class="event-card__excerpt"><?php echo esc_html($excerpt); ?></p>
        <?php endif; ?>

        <div class="event-card__meta">
            <?php if ($time_label) : ?>
                <div class="event-card__meta-item">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                        <circle cx="9" cy="9" r="7" stroke="#6B7280" stroke-width="1.5" />
                        <path d="M9 5v4l3 2" stroke="#6B7280" stroke-width="1.5" stroke-linecap="round" />
                    </svg>
                    <span class="event-card__meta-label"><?php echo esc_html($time_label); ?></span>
                </div>
            <?php endif; ?>

            <div class="event-card__meta-item event-card__meta-item--location">
                <svg width="14" height="16" viewBox="0 0 14 16" fill="none" aria-hidden="true">
                    <path d="M7 1C4.24 1 2 3.24 2 6c0 3.75 5 9 5 9s5-5.25 5-9c0-2.76-2.24-5-5-5Z" fill="#C8402E" />
                    <circle cx="7" cy="6" r="1.8" fill="#fff" />
                </svg>
                <div>
                    <p class="event-card__location-primary"><?php echo esc_html($location_name); ?></p>
                    <?php if ($address_line) : ?>
                        <p class="event-card__location-secondary"><?php echo esc_html($address_line); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <a class="event-btn" href="<?php echo esc_url($event_url); ?>">
            <span class="event-btn-icon" aria-hidden="true">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none">
                    <path d="M2 6h8M6.5 2.5l3.5 3.5-3.5 3.5" stroke="#fff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
            <?php esc_html_e('Event Details', 'myco'); ?>
        </a>
    </div>
</article>
