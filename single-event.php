<?php
/**
 * Single Event Template
 *
 * @package MYCO
 */

get_header();

$categories = get_the_terms(get_the_ID(), 'event_category');
$cat_name = $categories && !is_wp_error($categories) ? $categories[0]->name : '';
$event_date = myco_get_field('event_date');
$date_parts = myco_format_event_date($event_date);
$start_time = myco_get_field('event_start_time');
$end_time = myco_get_field('event_end_time');
$location_name = myco_get_field('event_location_name', false, 'MYCO Community Space');
$location_address = myco_get_field('event_location_address');
$maps_url = myco_get_field('event_maps_url');
$age_group = myco_get_field('event_age_group');
$cost = myco_get_field('event_cost', false, 'Free');
$cost_note = myco_get_field('event_cost_note');
$registration_url = myco_get_field('event_registration_url');
$coordinator_name = myco_get_field('event_coordinator_name');
$coordinator_title = myco_get_field('event_coordinator_title');
$coordinator_email = myco_get_field('event_coordinator_email');
$what_to_expect = myco_get_field('what_to_expect');
$what_to_bring = myco_get_field('what_to_bring');
?>

<!-- Hero -->
<section class="w-full relative overflow-hidden" style="background: linear-gradient(135deg, #141943 0%, #1e2a5a 50%, #2a3e6a 100%);">
    <div class="inner mx-auto px-4 py-16 md:py-20 relative z-10">
        <?php myco_breadcrumb([
            ['label' => __('Home', 'myco'), 'url' => home_url('/')],
            ['label' => __('Events', 'myco'), 'url' => home_url('/events/')],
            ['label' => get_the_title(), 'url' => ''],
        ], 'dark'); ?>
        <?php if ($cat_name) : ?>
        <span class="inline-block text-xs font-semibold uppercase tracking-wider px-3 py-1 rounded-full mb-4" style="background: rgba(200,64,46,0.2); color: #ff8a7a;"><?php echo esc_html($cat_name); ?></span>
        <?php endif; ?>
        <h1 class="font-inter font-extrabold text-white leading-tight mb-4" style="font-size: clamp(2.2rem, 5vw, 3.5rem);">
            <?php the_title(); ?>
        </h1>
        <div class="flex flex-wrap items-center gap-4 mt-4" style="color: rgba(255,255,255,0.6); font-size: 0.95rem;">
            <?php if ($date_parts) : ?><span><?php echo esc_html($date_parts['full']); ?></span><?php endif; ?>
            <?php if ($start_time) : ?><span>&middot; <?php echo esc_html($start_time); ?><?php if ($end_time) echo ' - ' . esc_html($end_time); ?></span><?php endif; ?>
            <span>&middot; <?php echo esc_html($location_name); ?></span>
        </div>
    </div>
</section>

<!-- Content -->
<section class="w-full bg-white py-16">
    <div class="inner mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Main -->
            <div class="lg:col-span-2">
                <?php if (has_post_thumbnail()) : ?>
                <div class="rounded-2xl overflow-hidden mb-10" style="box-shadow: 0 12px 48px rgba(20,25,67,0.12);">
                    <?php the_post_thumbnail('large', ['class' => 'w-full h-auto']); ?>
                </div>
                <?php endif; ?>
                <div class="prose max-w-none text-gray-500 leading-relaxed" style="font-size: 1.05rem; line-height: 1.8;">
                    <?php the_content(); ?>
                </div>
                <?php if ($what_to_expect && is_array($what_to_expect)) : ?>
                <div class="mt-10">
                    <h3 class="text-xl font-bold mb-6" style="color: #141943;"><?php esc_html_e('What to Expect', 'myco'); ?></h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($what_to_expect as $item) : ?>
                        <div class="p-5 rounded-xl bg-gray-50">
                            <h4 class="text-sm font-bold mb-1" style="color: #141943;"><?php echo esc_html($item['title']); ?></h4>
                            <p class="text-sm text-gray-500"><?php echo esc_html($item['description']); ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ($what_to_bring && is_array($what_to_bring)) : ?>
                <div class="mt-8">
                    <h3 class="text-xl font-bold mb-4" style="color: #141943;"><?php esc_html_e('What to Bring', 'myco'); ?></h3>
                    <ul class="flex flex-col gap-2">
                        <?php foreach ($what_to_bring as $item) : ?>
                        <li class="flex items-center gap-3 text-sm text-gray-500">
                            <div class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0" style="background: rgba(200,64,46,0.15);">
                                <svg width="10" height="8" viewBox="0 0 10 8" fill="none"><path d="M1 4L3.5 6.5L9 1" stroke="#C8402E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <?php echo esc_html($item['item']); ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php endif; ?>
            </div>
            <!-- Sidebar -->
            <div class="lg:col-span-1 flex flex-col gap-6">
                <!-- Event Details Card -->
                <div class="bg-white rounded-2xl p-6" style="box-shadow: 0 8px 32px rgba(20,25,67,0.10);">
                    <h3 class="text-lg font-bold mb-6" style="color: #141943;"><?php esc_html_e('Event Details', 'myco'); ?></h3>
                    <div class="flex flex-col gap-4 text-sm">
                        <?php if ($date_parts) : ?><div><span class="text-gray-400"><?php esc_html_e('Date', 'myco'); ?></span><div class="font-semibold" style="color: #141943;"><?php echo esc_html($date_parts['full']); ?></div></div><?php endif; ?>
                        <?php if ($start_time) : ?><div><span class="text-gray-400"><?php esc_html_e('Time', 'myco'); ?></span><div class="font-semibold" style="color: #141943;"><?php echo esc_html($start_time); ?><?php if ($end_time) echo ' - ' . esc_html($end_time); ?></div></div><?php endif; ?>
                        <div><span class="text-gray-400"><?php esc_html_e('Location', 'myco'); ?></span><div class="font-semibold" style="color: #141943;"><?php echo esc_html($location_name); ?></div><?php if ($location_address) : ?><div class="text-gray-400 mt-1"><?php echo esc_html($location_address); ?></div><?php endif; ?></div>
                        <?php if ($age_group) : ?><div><span class="text-gray-400"><?php esc_html_e('Age Group', 'myco'); ?></span><div class="font-semibold" style="color: #141943;"><?php echo esc_html($age_group); ?></div></div><?php endif; ?>
                        <div><span class="text-gray-400"><?php esc_html_e('Cost', 'myco'); ?></span><div class="font-semibold" style="color: #141943;"><?php echo esc_html($cost); ?></div><?php if ($cost_note) : ?><div class="text-gray-400 mt-1"><?php echo esc_html($cost_note); ?></div><?php endif; ?></div>
                    </div>
                </div>
                <!-- Registration Card -->
                <?php if ($registration_url) : ?>
                <div class="bg-gray-50 rounded-2xl p-6 text-center">
                    <h3 class="text-lg font-bold mb-3" style="color: #141943;"><?php esc_html_e('Register Now', 'myco'); ?></h3>
                    <p class="text-sm text-gray-500 mb-4"><?php esc_html_e('Secure your spot for this event', 'myco'); ?></p>
                    <a href="<?php echo esc_url($registration_url); ?>" class="pill-primary w-full justify-center"><?php esc_html_e('Register', 'myco'); ?></a>
                </div>
                <?php endif; ?>
                <!-- Coordinator Card -->
                <?php if ($coordinator_name) : ?>
                <div class="bg-white rounded-2xl p-6" style="box-shadow: 0 4px 16px rgba(20,25,67,0.06);">
                    <h3 class="text-sm font-bold mb-3" style="color: #141943;"><?php esc_html_e('Event Coordinator', 'myco'); ?></h3>
                    <div class="font-semibold" style="color: #141943;"><?php echo esc_html($coordinator_name); ?></div>
                    <?php if ($coordinator_title) : ?><div class="text-sm text-gray-400"><?php echo esc_html($coordinator_title); ?></div><?php endif; ?>
                    <?php if ($coordinator_email) : ?><a href="mailto:<?php echo esc_attr($coordinator_email); ?>" class="text-sm font-medium mt-2 inline-block" style="color: #C8402E;"><?php echo esc_html($coordinator_email); ?></a><?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
