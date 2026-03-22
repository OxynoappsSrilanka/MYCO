<?php
/**
 * Custom Taxonomies
 *
 * @package MYCO
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('init', 'myco_register_taxonomies');

function myco_register_taxonomies() {

    // Program Categories
    register_taxonomy('program_category', 'program', [
        'labels' => [
            'name'          => __('Program Categories', 'myco'),
            'singular_name' => __('Program Category', 'myco'),
            'search_items'  => __('Search Categories', 'myco'),
            'all_items'     => __('All Categories', 'myco'),
            'edit_item'     => __('Edit Category', 'myco'),
            'add_new_item'  => __('Add New Category', 'myco'),
            'menu_name'     => __('Categories', 'myco'),
        ],
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => ['slug' => 'program-category', 'with_front' => false],
        'show_in_rest' => true,
    ]);

    // Event Categories
    register_taxonomy('event_category', 'event', [
        'labels' => [
            'name'          => __('Event Categories', 'myco'),
            'singular_name' => __('Event Category', 'myco'),
            'search_items'  => __('Search Categories', 'myco'),
            'all_items'     => __('All Categories', 'myco'),
            'edit_item'     => __('Edit Category', 'myco'),
            'add_new_item'  => __('Add New Category', 'myco'),
            'menu_name'     => __('Categories', 'myco'),
        ],
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => ['slug' => 'event-category', 'with_front' => false],
        'show_in_rest' => true,
    ]);

    // News Categories
    register_taxonomy('news_category', 'news_article', [
        'labels' => [
            'name'          => __('News Categories', 'myco'),
            'singular_name' => __('News Category', 'myco'),
            'search_items'  => __('Search Categories', 'myco'),
            'all_items'     => __('All Categories', 'myco'),
            'edit_item'     => __('Edit Category', 'myco'),
            'add_new_item'  => __('Add New Category', 'myco'),
            'menu_name'     => __('Categories', 'myco'),
        ],
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => ['slug' => 'news-category', 'with_front' => false],
        'show_in_rest' => true,
    ]);

    // Gallery Albums
    register_taxonomy('gallery_album', ['gallery_photo', 'gallery_video'], [
        'labels' => [
            'name'          => __('Albums', 'myco'),
            'singular_name' => __('Album', 'myco'),
            'search_items'  => __('Search Albums', 'myco'),
            'all_items'     => __('All Albums', 'myco'),
            'edit_item'     => __('Edit Album', 'myco'),
            'add_new_item'  => __('Add New Album', 'myco'),
            'menu_name'     => __('Albums', 'myco'),
        ],
        'hierarchical' => true,
        'public'       => true,
        'rewrite'      => ['slug' => 'gallery-album', 'with_front' => false],
        'show_in_rest' => true,
    ]);
}
