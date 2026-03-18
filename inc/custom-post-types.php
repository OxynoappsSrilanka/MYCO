<?php
/**
 * Custom Post Types
 *
 * @package MYCO
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('init', 'myco_register_post_types');

function myco_register_post_types() {

    // Programs CPT
    register_post_type('program', [
        'labels' => [
            'name'               => __('Programs', 'myco'),
            'singular_name'      => __('Program', 'myco'),
            'add_new'            => __('Add New Program', 'myco'),
            'add_new_item'       => __('Add New Program', 'myco'),
            'edit_item'          => __('Edit Program', 'myco'),
            'new_item'           => __('New Program', 'myco'),
            'view_item'          => __('View Program', 'myco'),
            'search_items'       => __('Search Programs', 'myco'),
            'not_found'          => __('No programs found', 'myco'),
            'not_found_in_trash' => __('No programs found in trash', 'myco'),
            'all_items'          => __('All Programs', 'myco'),
            'menu_name'          => __('Programs', 'myco'),
        ],
        'public'        => true,
        'has_archive'   => false,
        'rewrite'       => ['slug' => 'programs', 'with_front' => false],
        'menu_icon'     => 'dashicons-welcome-learn-more',
        'menu_position' => 5,
        'supports'      => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest'  => true,
    ]);

    // Events CPT
    register_post_type('event', [
        'labels' => [
            'name'               => __('Events', 'myco'),
            'singular_name'      => __('Event', 'myco'),
            'add_new'            => __('Add New Event', 'myco'),
            'add_new_item'       => __('Add New Event', 'myco'),
            'edit_item'          => __('Edit Event', 'myco'),
            'new_item'           => __('New Event', 'myco'),
            'view_item'          => __('View Event', 'myco'),
            'search_items'       => __('Search Events', 'myco'),
            'not_found'          => __('No events found', 'myco'),
            'not_found_in_trash' => __('No events found in trash', 'myco'),
            'all_items'          => __('All Events', 'myco'),
            'menu_name'          => __('Events', 'myco'),
        ],
        'public'        => true,
        'has_archive'   => false,
        // Use a unique single-event base so it does not clash with the /events page.
        'rewrite'       => ['slug' => 'event', 'with_front' => false],
        'menu_icon'     => 'dashicons-calendar-alt',
        'menu_position' => 6,
        'supports'      => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest'  => true,
    ]);

    // News Articles CPT
    register_post_type('news_article', [
        'labels' => [
            'name'               => __('News', 'myco'),
            'singular_name'      => __('News Article', 'myco'),
            'add_new'            => __('Add New Article', 'myco'),
            'add_new_item'       => __('Add New Article', 'myco'),
            'edit_item'          => __('Edit Article', 'myco'),
            'new_item'           => __('New Article', 'myco'),
            'view_item'          => __('View Article', 'myco'),
            'search_items'       => __('Search Articles', 'myco'),
            'not_found'          => __('No articles found', 'myco'),
            'not_found_in_trash' => __('No articles found in trash', 'myco'),
            'all_items'          => __('All Articles', 'myco'),
            'menu_name'          => __('News', 'myco'),
        ],
        'public'        => true,
        'has_archive'   => false,
        'rewrite'       => ['slug' => 'news', 'with_front' => false],
        'menu_icon'     => 'dashicons-megaphone',
        'menu_position' => 7,
        'supports'      => ['title', 'editor', 'thumbnail', 'excerpt', 'author'],
        'show_in_rest'  => true,
    ]);

    // Gallery Photos CPT
    register_post_type('gallery_photo', [
        'labels' => [
            'name'               => __('Gallery', 'myco'),
            'singular_name'      => __('Gallery Photo', 'myco'),
            'add_new'            => __('Add New Photo', 'myco'),
            'add_new_item'       => __('Add New Photo', 'myco'),
            'edit_item'          => __('Edit Photo', 'myco'),
            'new_item'           => __('New Photo', 'myco'),
            'view_item'          => __('View Photo', 'myco'),
            'search_items'       => __('Search Photos', 'myco'),
            'not_found'          => __('No photos found', 'myco'),
            'not_found_in_trash' => __('No photos found in trash', 'myco'),
            'all_items'          => __('All Photos', 'myco'),
            'menu_name'          => __('Gallery', 'myco'),
        ],
        'public'        => true,
        'has_archive'   => false,
        'rewrite'       => ['slug' => 'gallery-photo', 'with_front' => false],
        'menu_icon'     => 'dashicons-format-gallery',
        'menu_position' => 8,
        'supports'      => ['title', 'thumbnail'],
        'show_in_rest'  => true,
    ]);

    // Testimonials CPT
    register_post_type('testimonial', [
        'labels' => [
            'name'               => __('Testimonials', 'myco'),
            'singular_name'      => __('Testimonial', 'myco'),
            'add_new'            => __('Add New Testimonial', 'myco'),
            'add_new_item'       => __('Add New Testimonial', 'myco'),
            'edit_item'          => __('Edit Testimonial', 'myco'),
            'new_item'           => __('New Testimonial', 'myco'),
            'view_item'          => __('View Testimonial', 'myco'),
            'search_items'       => __('Search Testimonials', 'myco'),
            'not_found'          => __('No testimonials found', 'myco'),
            'not_found_in_trash' => __('No testimonials found in trash', 'myco'),
            'all_items'          => __('All Testimonials', 'myco'),
            'menu_name'          => __('Testimonials', 'myco'),
        ],
        'public'        => false,
        'show_ui'       => true,
        'has_archive'   => false,
        'rewrite'       => false,
        'menu_icon'     => 'dashicons-format-quote',
        'menu_position' => 9,
        'supports'      => ['title'],
        'show_in_rest'  => true,
    ]);
}
