<?php
/**
 * Dark Gradient Hero with Breadcrumb
 *
 * Accepts $args: ['title' => '', 'subtitle' => '', 'breadcrumb' => []]
 *
 * @package MYCO
 */

// Support args passed via get_template_part( ..., null, $args )
$args = wp_parse_args(isset($args) ? $args : [], [
    'title'      => '',
    'subtitle'   => '',
    'breadcrumb' => [],
]);

// Resolve title: args first, then page title, then archive label
if (!empty($args['title'])) {
    $page_title = $args['title'];
} elseif (is_singular()) {
    $page_title = get_the_title();
} elseif (is_post_type_archive()) {
    $page_title = post_type_archive_title('', false);
} else {
    $page_title = get_the_title();
}

// Resolve subtitle: args first, then template-based fallbacks
if (!empty($args['subtitle'])) {
    $subtitle = $args['subtitle'];
} else {
    $subtitle = '';
    if (is_page_template('page-templates/template-programs.php')) {
        $subtitle = myco_get_field('programs_hero_subtitle', false, 'Comprehensive programs for Muslim youth development');
    } elseif (is_page_template('page-templates/template-events.php')) {
        $subtitle = myco_get_field('events_hero_subtitle', false, 'Join our community events and activities');
    } elseif (is_page_template('page-templates/template-news.php')) {
        $subtitle = myco_get_field('news_hero_subtitle', false, 'Latest updates from our community');
    } elseif (is_page_template('page-templates/template-gallery.php')) {
        $subtitle = myco_get_field('gallery_hero_subtitle', false, 'Capturing moments of growth, connection, and community');
    } elseif (is_page_template('page-templates/template-volunteer.php')) {
        $subtitle = myco_get_field('volunteer_hero_subtitle', false, 'Join our team and make a difference');
    } elseif (is_page_template('page-templates/template-contact.php')) {
        $subtitle = myco_get_field('contact_hero_subtitle', false, "We'd love to hear from you");
    } elseif (is_page_template('page-templates/template-donate.php')) {
        $subtitle = myco_get_field('donate_hero_subtitle', false, 'Your generosity empowers Muslim youth');
    } elseif (is_page_template('page-templates/template-privacy.php')) {
        $subtitle = 'Your privacy matters to us';
    } elseif (is_post_type_archive('event')) {
        $subtitle = 'Join us for community events, workshops, and activities';
    } elseif (is_post_type_archive('program')) {
        $subtitle = 'Discover programs designed to empower, educate, and inspire Muslim youth';
    } elseif (is_post_type_archive('news_article')) {
        $subtitle = 'Stay updated with the latest stories, announcements, and community highlights';
    }
}

// Build default breadcrumb if none passed
if (!empty($args['breadcrumb'])) {
    $breadcrumb_items = $args['breadcrumb'];
} else {
    $breadcrumb_items = [
        ['label' => __('Home', 'myco'), 'url' => home_url('/')],
        ['label' => $page_title, 'url' => ''],
    ];
}
?>

<section class="w-full relative overflow-hidden" style="background: linear-gradient(135deg, #141943 0%, #1e2a5a 50%, #2a3e6a 100%);">
    <!-- Wave decoration -->
    <div aria-hidden="true" class="absolute inset-0 pointer-events-none opacity-[0.06]"
         style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='1920' height='300' fill='none'%3E%3Cpath d='M-60 80 C400 -20 800 180 1300 60 S1700 -40 1980 80' stroke='white' stroke-width='1.2'/%3E%3Cpath d='M-60 160 C400 60 800 260 1300 140 S1700 40 1980 160' stroke='white' stroke-width='1.2'/%3E%3C/svg%3E&quot;); background-size: 1920px 300px; background-repeat: no-repeat;"></div>
    <div class="inner mx-auto px-4 py-12 md:py-16 relative z-10">
        <?php myco_breadcrumb($breadcrumb_items, 'dark'); ?>
        <h1 class="font-inter font-extrabold text-white leading-tight tracking-tight mb-4"
            style="font-size: clamp(2.5rem, 5.5vw, 4rem);">
            <?php echo esc_html($page_title); ?>
        </h1>
        <?php if ($subtitle) : ?>
        <p style="color: rgba(255,255,255,0.65); font-size: 1.1rem; line-height: 1.7; max-width: 600px;">
            <?php echo esc_html($subtitle); ?>
        </p>
        <?php endif; ?>
    </div>
</section>
