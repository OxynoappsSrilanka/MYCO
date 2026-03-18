<?php
/**
 * Archive: Events
 *
 * @package MYCO
 */

get_header();

$archive_title       = post_type_archive_title('', false);
$archive_description = trim(wp_strip_all_tags(get_the_archive_description()));
$hero_copy           = $archive_description ? $archive_description : __('Join us for workshops, community service, sports activities, and youth gatherings.', 'myco');

get_template_part(
    'template-parts/sections/events-listing',
    null,
    [
        'hero_title' => $archive_title ? $archive_title : __('Upcoming Events', 'myco'),
        'hero_copy'  => $hero_copy,
        'breadcrumb' => [
            ['label' => __('Home', 'myco'), 'url' => home_url('/')],
            ['label' => __('Events', 'myco'), 'url' => ''],
        ],
    ]
);

get_footer();
