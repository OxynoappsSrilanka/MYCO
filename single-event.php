<?php
/**
 * Single Event Template
 *
 * @package MYCO
 */

get_header();

$categories        = get_the_terms(get_the_ID(), 'event_category');
$cat_name          = $categories && !is_wp_error($categories) ? $categories[0]->name : '';
$event_date        = myco_get_field('event_date');
$date_parts        = myco_format_event_date($event_date);
$start_time        = myco_get_field('event_start_time');
$end_time          = myco_get_field('event_end_time');
$location_name     = myco_get_field('event_location_name', false, 'MYCO Community Space');
$location_address  = myco_get_field('event_location_address');
$maps_url          = myco_get_field('event_maps_url');
$age_group         = myco_get_field('event_age_group');
$cost              = myco_get_field('event_cost', false, 'Free');
$cost_note         = myco_get_field('event_cost_note');
$registration_url  = myco_get_field('event_registration_url');
$coordinator_name  = myco_get_field('event_coordinator_name');
$coordinator_title = myco_get_field('event_coordinator_title');
$coordinator_email = myco_get_field('event_coordinator_email');
$coordinator_phone = myco_get_field('event_coordinator_phone');
$what_to_expect    = myco_get_field('what_to_expect');
$what_to_bring     = myco_get_field('what_to_bring');
$event_image_url   = myco_get_image_url(get_the_ID(), 'large');
$event_content     = apply_filters('the_content', get_the_content());
$summary_source    = has_excerpt() ? get_the_excerpt() : wp_strip_all_tags(get_post_field('post_content', get_the_ID()));
$event_summary     = wp_trim_words($summary_source, 28, '...');
$event_time        = $start_time ? $start_time . ($end_time ? ' - ' . $end_time : '') : '';
$formatted_date    = $event_date ? wp_date('l, F j, Y', strtotime($event_date)) : '';
$events_url        = get_post_type_archive_link('event') ? get_post_type_archive_link('event') : home_url('/events/');
$display_cat_name  = $cat_name ? $cat_name . (stripos($cat_name, 'event') === false ? ' Event' : '') : __('Community Event', 'myco');
$display_address   = $location_address ? $location_address : __('Columbus, OH', 'myco');
$display_age_group = $age_group ? $age_group : __('MYCO Youth', 'myco');
$display_cost      = $cost ? $cost : __('Free', 'myco');
$register_url      = $registration_url ? $registration_url : myco_get_contact_page_url(['interest' => 'events']);
$register_title    = $registration_url ? __('Register for Event', 'myco') : __('Interested in This Event?', 'myco');
$register_button   = $registration_url ? __('Register Now', 'myco') : __('Contact Us', 'myco');
$register_copy     = $registration_url ? sprintf(
    /* translators: %s: event title */
    __('Secure your spot for %s.', 'myco'),
    get_the_title()
) : __('Questions or ready to join? Reach out to our team and we will help you reserve a spot.', 'myco');
$coordinator_name  = $coordinator_name ? $coordinator_name : __('MYCO Team', 'myco');
$coordinator_title = $coordinator_title ? $coordinator_title : __('Event Coordination', 'myco');
$coordinator_email = $coordinator_email ? $coordinator_email : myco_get_option('org_email', 'info@mycohio.org');
$coordinator_phone = $coordinator_phone ? $coordinator_phone : myco_get_option('org_phone', '(614) 555-0123');
$event_title_lower = strtolower(get_the_title());
$is_sports_event   = (false !== stripos($display_cat_name, 'sport')) || (false !== strpos($event_title_lower, 'basketball')) || (false !== strpos($event_title_lower, 'soccer'));

if (empty($what_to_expect) || !is_array($what_to_expect)) {
    $what_to_expect = $is_sports_event
        ? [
            [
                'title'       => __('Warm-Up & Skill Drills', 'myco'),
                'description' => __('Start with dynamic stretching and guided drills led by experienced mentors and coaches.', 'myco'),
            ],
            [
                'title'       => __('Team Formation & Group Activities', 'myco'),
                'description' => __('Join balanced teams and take part in friendly competition that emphasizes sportsmanship and growth.', 'myco'),
            ],
            [
                'title'       => __('Refreshments & Fellowship', 'myco'),
                'description' => __('Spend time connecting with other youth in a positive, faith-centered community environment.', 'myco'),
            ],
            [
                'title'       => __('All Skill Levels Welcome', 'myco'),
                'description' => __('Whether you are new or experienced, the event is designed so everyone can participate comfortably.', 'myco'),
            ],
        ]
        : [
            [
                'title'       => __('Guided Activities', 'myco'),
                'description' => __('Expect a structured experience with clear guidance, meaningful activities, and space to participate fully.', 'myco'),
            ],
            [
                'title'       => __('Community Connection', 'myco'),
                'description' => __('Meet other Muslim youth, build friendships, and strengthen your sense of belonging in the MYCO community.', 'myco'),
            ],
            [
                'title'       => __('Supportive Environment', 'myco'),
                'description' => __('Our team works to create a welcoming, organized, and encouraging space for every attendee.', 'myco'),
            ],
        ];
}

if (empty($what_to_bring) || !is_array($what_to_bring)) {
    $what_to_bring = $is_sports_event
        ? [
            ['item' => __('Athletic clothing and comfortable sneakers', 'myco')],
            ['item' => __('A water bottle to stay hydrated', 'myco')],
            ['item' => __('A positive attitude and team spirit', 'myco')],
        ]
        : [
            ['item' => __('A notebook or device if you want to take notes', 'myco')],
            ['item' => __('Anything specific mentioned in the event description', 'myco')],
            ['item' => __('A positive attitude and readiness to participate', 'myco')],
        ];
}
?>

<section class="event-detail-hero">
    <div class="inner event-detail-hero-inner" style="padding-top: 80px; padding-bottom: 60px;">
        <div class="events-breadcrumb">
            <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'myco'); ?></a>
            <svg width="6" height="10" viewBox="0 0 6 10" fill="none" aria-hidden="true">
                <path d="M1 1l4 4-4 4" stroke="rgba(255,255,255,0.5)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <a href="<?php echo esc_url($events_url); ?>"><?php esc_html_e('Events', 'myco'); ?></a>
            <svg width="6" height="10" viewBox="0 0 6 10" fill="none" aria-hidden="true">
                <path d="M1 1l4 4-4 4" stroke="rgba(255,255,255,0.5)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span><?php the_title(); ?></span>
        </div>

        <div class="event-detail-badge"><?php echo esc_html($display_cat_name); ?></div>

        <h1 class="event-detail-title"><?php the_title(); ?></h1>

        <?php if ($event_summary) : ?>
            <p class="event-detail-subtitle"><?php echo esc_html($event_summary); ?></p>
        <?php endif; ?>
    </div>
</section>

<section class="event-detail-content">
    <div class="inner">
        <div class="event-detail-grid">
            <div class="event-detail-main">
                <div class="event-detail-image">
                    <img src="<?php echo esc_url($event_image_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" loading="lazy" />
                </div>

                <section class="event-detail-section">
                    <h2><?php esc_html_e('Event Overview', 'myco'); ?></h2>
                    <div class="event-detail-richtext">
                        <?php echo $event_content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </div>
                </section>

                <?php if ($what_to_expect && is_array($what_to_expect)) : ?>
                    <section class="event-detail-section">
                        <h2><?php esc_html_e('What to Expect', 'myco'); ?></h2>
                        <div class="event-detail-expect-list">
                            <?php foreach ($what_to_expect as $item) : ?>
                                <?php
                                $item_title       = isset($item['title']) ? $item['title'] : '';
                                $item_description = isset($item['description']) ? $item['description'] : '';

                                if (!$item_title && !$item_description) {
                                    continue;
                                }
                                ?>
                                <div class="event-detail-expect-item">
                                    <div class="event-detail-expect-icon" aria-hidden="true">
                                        <svg width="16" height="14" viewBox="0 0 16 14" fill="none">
                                            <path d="M1.5 7L6 11.5L14.5 2.5" stroke="#C8402E" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                    <div>
                                        <?php if ($item_title) : ?>
                                            <h3><?php echo esc_html($item_title); ?></h3>
                                        <?php endif; ?>
                                        <?php if ($item_description) : ?>
                                            <p><?php echo esc_html($item_description); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </section>
                <?php endif; ?>

                <?php if ($what_to_bring && is_array($what_to_bring)) : ?>
                    <section class="event-detail-section">
                        <h2><?php esc_html_e('What to Bring', 'myco'); ?></h2>
                        <div class="event-detail-bring">
                            <ul class="event-detail-bring-list">
                                <?php foreach ($what_to_bring as $item) : ?>
                                    <?php
                                    $bring_item = isset($item['item']) ? $item['item'] : '';

                                    if (!$bring_item) {
                                        continue;
                                    }
                                    ?>
                                    <li>
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" aria-hidden="true">
                                            <circle cx="10" cy="10" r="9" fill="#C8402E" />
                                            <path d="M6 10l3 3 5-6" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <?php echo esc_html($bring_item); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </section>
                <?php endif; ?>
            </div>

            <aside class="event-detail-sidebar">
                <div class="event-detail-sidebar-stack">
                    <div class="event-detail-card">
                        <h3><?php esc_html_e('Event Details', 'myco'); ?></h3>
                        <div class="event-detail-card-items">
                            <?php if ($formatted_date) : ?>
                                <div class="event-detail-card-item">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <rect x="3" y="5" width="18" height="15" rx="3" stroke="#C8402E" stroke-width="2" />
                                        <path d="M3 9h18M7 2v6M17 2v6" stroke="#C8402E" stroke-width="2" stroke-linecap="round" />
                                    </svg>
                                    <div>
                                        <div class="event-detail-card-label"><?php esc_html_e('Date', 'myco'); ?></div>
                                        <div class="event-detail-card-value"><?php echo esc_html($formatted_date); ?></div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($event_time) : ?>
                                <div class="event-detail-card-item">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                        <circle cx="12" cy="12" r="9" stroke="#C8402E" stroke-width="2" />
                                        <path d="M12 6v6l4 3" stroke="#C8402E" stroke-width="2" stroke-linecap="round" />
                                    </svg>
                                    <div>
                                        <div class="event-detail-card-label"><?php esc_html_e('Time', 'myco'); ?></div>
                                        <div class="event-detail-card-value"><?php echo esc_html($event_time); ?></div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="event-detail-card-item">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" stroke="#C8402E" stroke-width="2" />
                                    <circle cx="12" cy="10" r="3" stroke="#C8402E" stroke-width="2" />
                                </svg>
                                <div>
                                    <div class="event-detail-card-label"><?php esc_html_e('Location', 'myco'); ?></div>
                                    <div class="event-detail-card-value"><?php echo esc_html($location_name); ?></div>
                                    <div class="event-detail-card-subvalue"><?php echo nl2br(esc_html($display_address)); ?></div>
                                    <?php if ($maps_url) : ?>
                                        <a class="event-detail-map-link" href="<?php echo esc_url($maps_url); ?>" target="_blank" rel="noopener noreferrer">
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                                                <path d="M7 1C4.24 1 2 3.24 2 6c0 3.75 5 7 5 7s5-3.25 5-7c0-2.76-2.24-5-5-5Z" stroke="currentColor" stroke-width="1.5" />
                                                <circle cx="7" cy="6" r="1.5" stroke="currentColor" stroke-width="1.5" />
                                            </svg>
                                            <?php esc_html_e('Get Directions', 'myco'); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="event-detail-card-item">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" stroke="#C8402E" stroke-width="2" stroke-linecap="round" />
                                    <circle cx="12" cy="7" r="4" stroke="#C8402E" stroke-width="2" />
                                </svg>
                                <div>
                                    <div class="event-detail-card-label"><?php esc_html_e('Age Group', 'myco'); ?></div>
                                    <div class="event-detail-card-value"><?php echo esc_html($display_age_group); ?></div>
                                </div>
                            </div>

                            <div class="event-detail-card-item">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <circle cx="12" cy="12" r="10" stroke="#C8402E" stroke-width="2" />
                                    <path d="M12 6v6l4 2" stroke="#C8402E" stroke-width="2" stroke-linecap="round" />
                                </svg>
                                <div>
                                    <div class="event-detail-card-label"><?php esc_html_e('Cost', 'myco'); ?></div>
                                    <div class="event-detail-card-value"><?php echo esc_html($display_cost); ?></div>
                                    <?php if ($cost_note) : ?>
                                        <div class="event-detail-card-subvalue"><?php echo esc_html($cost_note); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="event-register-card">
                        <h3><?php echo esc_html($register_title); ?></h3>
                        <p><?php echo esc_html($register_copy); ?></p>
                        <a class="event-register-btn" href="<?php echo esc_url($register_url); ?>" <?php echo $registration_url ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
                            <?php echo esc_html($register_button); ?>
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M3.5 9h11M10 4.5l4.5 4.5-4.5 4.5" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                        <p class="event-register-help">
                            <?php esc_html_e('Questions?', 'myco'); ?>
                            <a href="<?php echo esc_url(myco_get_contact_page_url(['interest' => 'events'])); ?>"><?php esc_html_e('Contact us', 'myco'); ?></a>
                        </p>
                    </div>

                    <div class="event-coordinator-card">
                        <h3><?php esc_html_e('Event Coordinator', 'myco'); ?></h3>
                        <div class="event-coordinator-name"><?php echo esc_html($coordinator_name); ?></div>
                        <div class="event-coordinator-title"><?php echo esc_html($coordinator_title); ?></div>

                        <div class="event-coordinator-links">
                            <a class="event-coordinator-link" href="mailto:<?php echo esc_attr($coordinator_email); ?>">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                                    <path d="M2 3.5h12v9H2v-9Z" stroke="currentColor" stroke-width="1.5" />
                                    <path d="m3.5 5 4.5 3.5L12.5 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <?php echo esc_html($coordinator_email); ?>
                            </a>

                            <a class="event-coordinator-link" href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $coordinator_phone)); ?>">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
                                    <path d="M3 2.5h2l1 3-1.5 1.5a11 11 0 0 0 4 4L10 9.5l3 1v2a1 1 0 0 1-1 1A10 10 0 0 1 2 4a1 1 0 0 1 1-1.5Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <?php echo esc_html($coordinator_phone); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>

<?php get_footer(); ?>
