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
 * Get a versioned URL for a theme asset.
 */
function myco_theme_asset_url($relative_path = '') {
    $relative_path = ltrim((string) $relative_path, '/');

    if ($relative_path === '') {
        return MYCO_URI;
    }

    $file_path = MYCO_DIR . '/' . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relative_path);
    $asset_url = MYCO_URI . '/' . $relative_path;

    if (file_exists($file_path)) {
        return add_query_arg('ver', (string) filemtime($file_path), $asset_url);
    }

    return $asset_url;
}

/**
 * Add a filemtime version to theme asset URLs that already use MYCO_URI.
 */
function myco_version_theme_url($url = '') {
    $url = (string) $url;

    if ($url === '') {
        return '';
    }

    $theme_base = trailingslashit(MYCO_URI);

    if (strpos($url, $theme_base) !== 0) {
        return $url;
    }

    $relative_path = ltrim(substr($url, strlen($theme_base)), '/');
    $relative_path = strtok($relative_path, '?#');

    return myco_theme_asset_url($relative_path);
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
        return myco_theme_asset_url('assets/images/' . ltrim($post_id, '/'));
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

    return myco_theme_asset_url('assets/images/hero-image.png');
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
        $fallback = myco_theme_asset_url('assets/images/hero-image.png');
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
function myco_field_has_value($value) {
    if (is_array($value)) {
        return !empty($value);
    }

    return $value !== null && $value !== '';
}

/**
 * Get field value with ACF first, then raw post meta fallback.
 */
function myco_get_field($field_name, $post_id = false, $fallback = '') {
    $resolved_post_id = $post_id ? $post_id : get_the_ID();

    if (function_exists('get_field')) {
        $value = get_field($field_name, $post_id);
        if (myco_field_has_value($value)) {
            return $value;
        }
    }

    if ($resolved_post_id) {
        $meta_value = get_post_meta($resolved_post_id, $field_name, true);
        if (myco_field_has_value($meta_value)) {
            return $meta_value;
        }
    }

    return $fallback;
}

/**
 * Resolve a core site page URL from its MYCO page key or slug.
 */
function myco_get_page_url($key_or_slug = '', $fallback_path = '') {
    static $cache = [];

    $normalized = sanitize_title((string) $key_or_slug);
    $cache_key  = $normalized . '|' . (string) $fallback_path;

    if (isset($cache[$cache_key])) {
        return $cache[$cache_key];
    }

    $page_config = null;
    if (function_exists('myco_get_required_pages')) {
        $required_pages = myco_get_required_pages();
        $page_config    = $required_pages[$normalized] ?? null;
    }

    $candidate_slugs = [];

    if (!empty($page_config['slug'])) {
        $candidate_slugs[] = $page_config['slug'];
    }

    if ($normalized !== '') {
        $candidate_slugs[] = $normalized;
    }

    $aliases = [
        'contact'        => ['contact-us'],
        'privacy-policy' => ['privacy'],
    ];

    if (isset($aliases[$normalized])) {
        $candidate_slugs = array_merge($candidate_slugs, $aliases[$normalized]);
    }

    foreach (array_unique(array_filter($candidate_slugs)) as $slug) {
        $page = get_page_by_path($slug, OBJECT, 'page');
        if ($page instanceof WP_Post && $page->post_status === 'publish') {
            $cache[$cache_key] = get_permalink($page);
            return $cache[$cache_key];
        }
    }

    if (!empty($page_config['template'])) {
        $page_ids = get_posts([
            'post_type'      => 'page',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'fields'         => 'ids',
            'meta_key'       => '_wp_page_template',
            'meta_value'     => $page_config['template'],
        ]);

        if (!empty($page_ids[0])) {
            $cache[$cache_key] = get_permalink((int) $page_ids[0]);
            return $cache[$cache_key];
        }
    }

    if ($fallback_path === '') {
        $resolved_slug  = $page_config['slug'] ?? ($candidate_slugs[0] ?? '');
        $fallback_path  = $resolved_slug !== '' ? '/' . trim($resolved_slug, '/') . '/' : '/';
    }

    $cache[$cache_key] = home_url($fallback_path);
    return $cache[$cache_key];
}

/**
 * Resolve the contact page URL with optional query args.
 */
function myco_get_contact_page_url($args = []) {
    $url = myco_get_page_url('contact', '/contact/');

    if (!empty($args)) {
        $args = array_filter($args, static function ($value) {
            return $value !== null && $value !== '';
        });

        if (!empty($args)) {
            $url = add_query_arg($args, $url);
        }
    }

    return $url;
}

/**
 * Get the best available single-event URL.
 * Falls back to a query-string URL if a conflicting pretty permalink is generated.
 */
function myco_get_event_permalink($post_id = null) {
    $post_id = $post_id ? $post_id : get_the_ID();

    if (!$post_id) {
        return home_url('/');
    }

    $permalink = get_permalink($post_id);

    if (!$permalink) {
        return add_query_arg(
            [
                'post_type' => 'event',
                'p'         => $post_id,
            ],
            home_url('/')
        );
    }

    $relative_link = wp_make_link_relative($permalink);
    if (strpos($relative_link, '/events/') !== false) {
        return add_query_arg(
            [
                'post_type' => 'event',
                'p'         => $post_id,
            ],
            home_url('/')
        );
    }

    return $permalink;
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
 * Get the shared main contact phone number.
 */
function myco_get_shared_phone() {
    return '614 769 1949';
}

/**
 * Get core organization location details.
 */
function myco_get_contact_locations() {
    return [
        'myco' => [
            'short_label'    => 'MYCO',
            'name'           => 'Muslim Youth of Central Ohio',
            'street'         => '1255 N Hamilton Road Box 229',
            'city_state_zip' => 'Gahanna, Ohio 43230',
            'maps_query'     => 'Muslim Youth of Central Ohio 1255 N Hamilton Road Box 229 Gahanna Ohio 43230',
        ],
        'mcyc' => [
            'short_label'    => 'MCYC',
            'name'           => 'Muslim Community Youth Centre',
            'street'         => '5509 Sunbury Road',
            'city_state_zip' => 'Columbus, Ohio 43230',
            'maps_query'     => 'Muslim Community Youth Centre 5509 Sunbury Road Columbus Ohio 43230',
        ],
    ];
}

/**
 * Format a location for display.
 */
function myco_format_contact_location($location, $multiline = true) {
    if (!is_array($location)) {
        return '';
    }

    $second_line = trim(($location['street'] ?? '') . ', ' . ($location['city_state_zip'] ?? ''), ', ');

    if ($multiline) {
        return trim(($location['name'] ?? '') . "\n" . $second_line);
    }

    return trim(($location['name'] ?? '') . ', ' . $second_line, ', ');
}

/**
 * Get footer contact details for the current page context.
 */
function myco_get_footer_contact_details() {
    $locations = myco_get_contact_locations();
    $location  = is_page('mcyc') ? $locations['mcyc'] : $locations['myco'];

    return [
        'address' => myco_format_contact_location($location, false),
        'phone'   => myco_get_shared_phone(),
    ];
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
