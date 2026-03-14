<?php
/**
 * Theme Setup
 *
 * @package MYCO
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('after_setup_theme', 'myco_theme_setup');

function myco_theme_setup() {
    // Make theme available for translation
    load_theme_textdomain('myco', MYCO_DIR . '/languages');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Enable featured images
    add_theme_support('post-thumbnails');

    // Custom logo
    add_theme_support('custom-logo', [
        'height'      => 150,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    // HTML5 markup support
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);

    // Custom image sizes
    add_image_size('myco-card', 600, 400, true);        // Card thumbnails
    add_image_size('myco-hero', 1920, 800, true);       // Hero images
    add_image_size('myco-gallery', 800, 600, true);     // Gallery images
    add_image_size('myco-gallery-thumb', 400, 300, true); // Gallery thumbnails
    add_image_size('myco-testimonial', 100, 100, true); // Testimonial avatars

    // Remove default block patterns
    remove_theme_support('core-block-patterns');
}

// Add custom image sizes to media dropdown
add_filter('image_size_names_choose', 'myco_custom_image_sizes');

function myco_custom_image_sizes($sizes) {
    return array_merge($sizes, [
        'myco-card'          => __('Card Thumbnail', 'myco'),
        'myco-hero'          => __('Hero Image', 'myco'),
        'myco-gallery'       => __('Gallery Image', 'myco'),
        'myco-gallery-thumb' => __('Gallery Thumbnail', 'myco'),
    ]);
}
