<?php
/**
 * Upcoming Events + Volunteer CTA Section
 * Matches source index.html - split left heading / right event list + volunteer card below
 * @package MYCO
 */
$today = date('Y-m-d');
$events = get_posts([
    'post_type'   => 'event',
    'post_status' => 'publish',
    'posts_per_page' => 3,
    'meta_key'    => 'event_date',
    'orderby'     => 'meta_value',
    'order'       => 'ASC',
    'meta_query'  => [[
        'key'     => 'event_date',
        'value'   => $today,
        'compare' => '>=',
        'type'    => 'DATE',
    ]],
]);
$has_upcoming = ! empty($events);
$vol_image = myco_get_field('volunteer_card_image', false, '');
$vol_img_url = $vol_image ? (is_array($vol_image) ? $vol_image['url'] : wp_get_attachment_url($vol_image)) : myco_theme_asset_url('assets/images/volunteers.jpg');
?>

<section id="upcoming-events" aria-labelledby="upcoming-events-heading"
    class="w-full bg-[#F3F4F6] pt-16 pb-0 md:pt-20">
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8">

        <!-- PART A: Upcoming Events -->
        <div class="flex flex-col lg:flex-row gap-12 lg:gap-28 mb-14 md:mb-16">

            <!-- LEFT: Big title -->
            <div class="lg:max-w-[38%] flex-shrink-0">
                <span style="color:#C8402E; font-weight:700; font-size:0.84rem; letter-spacing:0.04em; display:block; margin-bottom:12px;">
                    <?php echo esc_html(myco_get_field('events_label', false, 'Upcoming Events')); ?> &mdash;
                </span>
                <h2 id="upcoming-events-heading" class="font-inter tracking-tight"
                    style="color:#141943; font-weight:900; font-size:clamp(2.8rem, 7vw, 5rem); line-height:1.0; letter-spacing:-0.02em;">
                    <?php $h = myco_get_field('events_heading', false, ''); if ($h) { echo nl2br(esc_html($h)); } else { ?>Our<br />Upcoming<br />Events<?php } ?>
                </h2>
            </div>

            <!-- RIGHT: Event list -->
            <div class="flex-2 min-w-0">
                <style>
                    .ue-row-new {
                        display: flex;
                        align-items: flex-start;
                        padding: 1.5rem 0;
                        border-bottom: 1px solid rgba(20, 25, 67, 0.08);
                        gap: 2rem;
                        transition: all 0.2s ease;
                    }
                    .ue-row-new:hover {
                        background: rgba(255,255,255,0.4);
                        padding-left: 1rem;
                        padding-right: 1rem;
                        margin-left: -1rem;
                        margin-right: -1rem;
                        border-radius: 12px;
                    }
                    .ue-content-col { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 4px; }
                    .ue-pattern-title { color: #141943; font-weight: 700; font-size: 1.15rem; line-height: 1.3; }
                    .ue-meta-row { display: flex; align-items: center; gap: 1.5rem; color: #6B7280; font-size: 0.9rem; font-weight: 500; }
                    .ue-pattern-venue { color: #9CA3AF; }
                    @media (max-width: 768px) {
                        .ue-row-new { gap: 1rem; }
                        .ue-meta-row { flex-direction: column; align-items: flex-start; gap: 4px; }
                    }
                </style>

                <?php if ($has_upcoming) : ?>
                    <?php foreach ($events as $event) :
                        $date_raw = myco_get_field('event_date', $event->ID, '');
                        $date     = myco_format_event_date($date_raw);
                        $location = myco_get_field('event_location_name', $event->ID, '');
                        $time     = myco_get_field('event_start_time', $event->ID, '');
                        $end_time = myco_get_field('event_end_time', $event->ID, '');
                        if ($time && $end_time) $time .= ' – ' . $end_time;
                    ?>
                    <div class="ue-row-new">
                        <div class="ue-date-col">
                            <span class="ue-month"><?php echo esc_html($date ? $date['month'] : ''); ?></span>
                            <span class="ue-day"><?php echo esc_html($date ? $date['day'] : ''); ?></span>
                        </div>
                        <div class="ue-content-col">
                            <h3 class="ue-pattern-title"><?php echo esc_html(get_the_title($event->ID)); ?></h3>
                            <div class="ue-meta-row">
                                <?php if ($time) : ?>
                                    <span class="ue-pattern-time"><?php echo esc_html($time); ?></span>
                                <?php endif; ?>
                                <?php if ($location) : ?>
                                    <span class="ue-pattern-venue"><?php echo esc_html($location); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                <?php else : ?>

                    <!-- No upcoming events state -->
                    <div style="
                        display: flex;
                        flex-direction: column;
                        align-items: flex-start;
                        justify-content: center;
                        padding: 48px 0 32px;
                        gap: 16px;
                    ">
                        <div style="
                            width: 56px;
                            height: 56px;
                            border-radius: 14px;
                            background: rgba(20, 25, 67, 0.06);
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            flex-shrink: 0;
                        ">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#141943" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="4" width="18" height="18" rx="3"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                        </div>
                        <div>
                            <p style="color: #141943; font-weight: 700; font-size: 1.15rem; margin: 0 0 6px;">No upcoming events right now</p>
                            <p style="color: #6B7280; font-size: 0.95rem; margin: 0 0 20px; line-height: 1.6;">Check back soon — new events are added regularly.</p>
                            <a href="<?php echo esc_url(myco_get_page_url('events', '/events/')); ?>"
                               style="
                                display: inline-flex;
                                align-items: center;
                                gap: 6px;
                                color: #C8402E;
                                font-weight: 700;
                                font-size: 0.95rem;
                                text-decoration: none;
                                transition: gap 0.2s;
                               "
                               onmouseover="this.style.gap='10px'"
                               onmouseout="this.style.gap='6px'">
                                View all events
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path d="M3 8h10M9 4l4 4-4 4" stroke="#C8402E" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                <?php endif; ?>
            </div>

        </div>

    </div>

    <!-- PART B: Volunteer CTA Card -->
    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 pb-16 md:pb-20">
        <div class="vol-card">
            <!-- LEFT: Text + button + link -->
            <div class="vol-card-text">
                <h2 class="vol-headline" id="volunteer-heading">
                    <?php $vh = myco_get_field('volunteer_card_heading', false, ''); if ($vh) { echo nl2br(esc_html($vh)); } else { ?>Join Our<br />Volunteers<?php } ?>
                </h2>
                <div class="flex flex-col gap-3 items-start">
                    <a href="<?php echo esc_url(myco_get_field('volunteer_card_btn_url', false, myco_get_page_url('volunteer', '/volunteer/'))); ?>" class="vol-btn">
                        <?php echo esc_html(myco_get_field('volunteer_card_btn_text', false, 'Register to Volunteer')); ?>
                    </a>
                    <a href="<?php echo esc_url(myco_get_field('volunteer_card_link_url', false, myco_get_page_url('volunteer', '/volunteer/'))); ?>" class="vol-link">
                        <?php echo esc_html(myco_get_field('volunteer_card_link_text', false, 'Learn about volunteering')); ?> &rarr;
                    </a>
                </div>
            </div>
            <!-- RIGHT: Image -->
            <div class="vol-card-image">
                <img src="<?php echo esc_url($vol_img_url); ?>"
                     alt="<?php esc_attr_e('Smiling volunteers working together', 'myco'); ?>" loading="lazy" />
            </div>
        </div>
    </div>

</section>
