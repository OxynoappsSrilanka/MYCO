<?php
/**
 * Template Helper Functions
 *
 * @package MYCO
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get featured image URL with fallback
 * Can be called as:
 *   myco_get_image_url()                   - current post, 'large' size
 *   myco_get_image_url($post_id, $size)    - specific post
 *   myco_get_image_url('filename.png')     - returns theme asset image URL
 */
function myco_get_image_url($post_id = null, $size = 'large', $fallback = '') {
    // If first argument is a string filename (not numeric), return theme asset URL
    if (is_string($post_id) && !is_numeric($post_id)) {
        return MYCO_URI . '/assets/images/' . $post_id;
    }

    if (!$post_id) {
        $post_id = get_the_ID();
    }

    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail_url($post_id, $size);
    }

    if ($fallback) {
        return $fallback;
    }

    return MYCO_URI . '/assets/images/hero-image.png';
}

/**
 * Output featured image with fallback
 */
function myco_featured_image($post_id = null, $size = 'large', $attrs = []) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    $defaults = [
        'class'   => 'w-full h-auto',
        'loading' => 'lazy',
    ];

    $attrs = wp_parse_args($attrs, $defaults);

    if (has_post_thumbnail($post_id)) {
        echo get_the_post_thumbnail($post_id, $size, $attrs);
    } else {
        $fallback = MYCO_URI . '/assets/images/hero-image.png';
        printf(
            '<img src="%s" alt="%s" class="%s" loading="%s" />',
            esc_url($fallback),
            esc_attr(get_the_title($post_id)),
            esc_attr($attrs['class']),
            esc_attr($attrs['loading'])
        );
    }
}

/**
 * Get ACF field with fallback
 */
function myco_get_field($field_name, $post_id = false, $fallback = '') {
    if (function_exists('get_field')) {
        $value = get_field($field_name, $post_id);
        return $value ? $value : $fallback;
    }
    return $fallback;
}

/**
 * Output ACF field with escaping
 */
function myco_field($field_name, $post_id = false, $fallback = '') {
    echo esc_html(myco_get_field($field_name, $post_id, $fallback));
}

/**
 * Output ACF field allowing HTML (for WYSIWYG fields)
 */
function myco_field_html($field_name, $post_id = false, $fallback = '') {
    echo wp_kses_post(myco_get_field($field_name, $post_id, $fallback));
}

/**
 * Get ACF option field with fallback
 */
function myco_get_option($field_name, $fallback = '') {
    if (function_exists('get_field')) {
        $value = get_field($field_name, 'option');
        return $value ? $value : $fallback;
    }
    return $fallback;
}

/**
 * Output breadcrumb navigation
 *
 * @param array  $items   Breadcrumb items: [['label' => '', 'url' => ''], ...]
 * @param string $style   'light' (dark text on white bg) or 'dark' (white text on dark bg)
 */
function myco_breadcrumb($items = [], $style = 'light') {
    if (empty($items)) {
        return;
    }

    echo '<div class="flex items-center gap-2 mb-6" style="flex-wrap: wrap;">';

    $last = count($items) - 1;
    foreach ($items as $i => $item) {
        if ($i === $last) {
            if ($style === 'dark') {
                echo '<span class="text-sm font-semibold" style="color: #ffffff;">' . esc_html($item['label']) . '</span>';
            } else {
                echo '<span class="text-sm font-semibold" style="color: #141943;">' . esc_html($item['label']) . '</span>';
            }
        } else {
            if ($style === 'dark') {
                echo '<a href="' . esc_url($item['url']) . '" class="text-sm font-medium transition-colors" style="color: rgba(255,255,255,0.65);" onmouseover="this.style.color=\'rgba(255,255,255,1)\'" onmouseout="this.style.color=\'rgba(255,255,255,0.65)\'">' . esc_html($item['label']) . '</a>';
                echo '<svg width="6" height="10" viewBox="0 0 6 10" fill="none"><path d="M1 1l4 4-4 4" stroke="rgba(255,255,255,0.4)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
            } else {
                echo '<a href="' . esc_url($item['url']) . '" class="text-sm font-medium text-gray-500 hover:text-navy-dark transition-colors">' . esc_html($item['label']) . '</a>';
                echo '<svg width="6" height="10" viewBox="0 0 6 10" fill="none"><path d="M1 1l4 4-4 4" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';
            }
        }
    }

    echo '</div>';
}

/**
 * Format event date for display
 */
function myco_format_event_date($date_string) {
    if (!$date_string) {
        return '';
    }
    $timestamp = strtotime($date_string);
    return [
        'day'   => date('d', $timestamp),
        'month' => strtoupper(date('M', $timestamp)),
        'full'  => date('F j, Y', $timestamp),
    ];
}

/**
 * Get social media URLs from theme options
 */
function myco_get_social_links() {
    return [
        'facebook'  => myco_get_option('social_facebook', 'https://www.facebook.com/MYCOYouth/'),
        'twitter'   => myco_get_option('social_twitter', 'https://x.com/mycohio_/'),
        'instagram' => myco_get_option('social_instagram', 'https://www.instagram.com/mycohio_/'),
        'email'     => myco_get_option('org_email', 'info@mycohio.org'),
    ];
}

/**
 * Determine which footer to use
 * Modified to always use dark footer for consistency
 */
function myco_get_footer_type() {
    // Always return 'dark' for consistent branding across all pages
    return 'dark';
    
    /* Original code (commented out):
    if (is_front_page() || is_page_template('page-templates/template-about.php')) {
        return 'light';
    }
    return 'dark';
    */
}
