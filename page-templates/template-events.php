<?php
/**
 * Template Name: Events
 *
 * @package MYCO
 */

get_header();

$page_content = trim(wp_strip_all_tags(get_post_field('post_content', get_the_ID())));
$hero_title   = get_the_title();
$hero_copy    = has_excerpt()
    ? get_the_excerpt()
    : ($page_content ? wp_trim_words($page_content, 24, '...') : __('Join us for workshops, community service, sports activities, and youth gatherings.', 'myco'));

get_template_part(
    'template-parts/sections/events-listing',
    null,
    [
        'hero_title' => $hero_title,
        'hero_copy'  => $hero_copy,
        'breadcrumb' => [
            ['label' => __('Home', 'myco'), 'url' => home_url('/')],
            ['label' => $hero_title, 'url' => ''],
        ],
    ]
);

get_footer();
