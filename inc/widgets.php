<?php
/**
 * Widget Areas
 *
 * @package MYCO
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('widgets_init', 'myco_register_widgets');

function myco_register_widgets() {
    register_sidebar([
        'name'          => __('News Sidebar', 'myco'),
        'id'            => 'news-sidebar',
        'description'   => __('Sidebar widgets for news article pages.', 'myco'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title text-lg font-bold text-navy-dark mb-4">',
        'after_title'   => '</h3>',
    ]);

    register_sidebar([
        'name'          => __('Event Sidebar', 'myco'),
        'id'            => 'event-sidebar',
        'description'   => __('Sidebar widgets for event detail pages.', 'myco'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title text-lg font-bold text-navy-dark mb-4">',
        'after_title'   => '</h3>',
    ]);
}
