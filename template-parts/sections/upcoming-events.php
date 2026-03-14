<?php
/**
 * Upcoming Events + Volunteer CTA Section
 * Matches source index.html - split left heading / right event list + volunteer card below
 * @package MYCO
 */
$events = get_posts(['post_type' => 'event', 'posts_per_page' => 3, 'meta_key' => 'event_date', 'orderby' => 'meta_value', 'order' => 'ASC']);
$defaults = [
    ['month' => 'OCT', 'day' => '15', 'title' => 'Youth Basketball League: Kick-off Games & Registration', 'meta' => '6:00 PM – 9:00 PM &middot; MYCO Community Center'],
    ['month' => 'OCT', 'day' => '18', 'title' => 'Academic Tutoring: Math & Science Support for High School', 'meta' => '4:00 PM – 8:00 PM &middot; Online via Zoom'],
    ['month' => 'OCT', 'day' => '27', 'title' => 'Monthly Service Day: Food Pantry Volunteering', 'meta' => '9:00 AM – 12:00 PM &middot; Central Ohio Food Bank'],
];
$use_defaults = empty($events);
$vol_image = myco_get_field('volunteer_card_image', false, '');
$vol_img_url = $vol_image ? (is_array($vol_image) ? $vol_image['url'] : wp_get_attachment_url($vol_image)) : MYCO_URI . '/assets/images/volunteers.jpg';
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

                <?php if ($use_defaults) : ?>
                    <?php foreach ($defaults as $ev) : 
                        $parts = explode(' &middot; ', $ev['meta']);
                        $time = isset($parts[0]) ? $parts[0] : '';
                        $venue = isset($parts[1]) ? $parts[1] : '';
                    ?>
                    <div class="ue-row-new">
                        <div class="ue-date-col">
                            <span class="ue-month"><?php echo esc_html($ev['month']); ?></span>
                            <span class="ue-day"><?php echo esc_html($ev['day']); ?></span>
                        </div>
                        <div class="ue-content-col">
                            <h3 class="ue-pattern-title"><?php echo esc_html($ev['title']); ?></h3>
                            <div class="ue-meta-row">
                                <span class="ue-pattern-time"><?php echo esc_html($time); ?></span>
                                <span class="ue-pattern-venue"><?php echo esc_html($venue); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <?php foreach ($events as $event) :
                        $date_raw = myco_get_field('event_date', $event->ID, '');
                        $date = myco_format_event_date($date_raw);
                        $location = myco_get_field('event_location_name', $event->ID, 'Venue TBA');
                        $time = myco_get_field('event_start_time', $event->ID, 'Time TBA');
                        $end_time = myco_get_field('event_end_time', $event->ID, '');
                        if ($end_time) $time .= ' – ' . $end_time;
                    ?>
                    <div class="ue-row-new">
                        <div class="ue-date-col">
                            <span class="ue-month"><?php echo esc_html($date ? $date['month'] : 'OCT'); ?></span>
                            <span class="ue-day"><?php echo esc_html($date ? $date['day'] : '15'); ?></span>
                        </div>
                        <div class="ue-content-col">
                            <h3 class="ue-pattern-title"><?php echo get_the_title($event->ID); ?></h3>
                            <div class="ue-meta-row">
                                <span class="ue-pattern-time"><?php echo esc_html($time); ?></span>
                                <span class="ue-pattern-venue"><?php echo esc_html($location); ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
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
                    <a href="<?php echo esc_url(myco_get_field('volunteer_card_btn_url', false, home_url('/volunteer/'))); ?>" class="vol-btn">
                        <?php echo esc_html(myco_get_field('volunteer_card_btn_text', false, 'Register to Volunteer')); ?>
                    </a>
                    <a href="<?php echo esc_url(myco_get_field('volunteer_card_link_url', false, home_url('/volunteer/'))); ?>" class="vol-link">
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
