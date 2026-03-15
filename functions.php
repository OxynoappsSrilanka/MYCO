<?php
/**
 * MYCO Theme Functions
 *
 * @package MYCO
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Theme constants
define('MYCO_VERSION', '1.0.1');
define('MYCO_DIR', get_template_directory());
define('MYCO_URI', get_template_directory_uri());

// Core includes
require_once MYCO_DIR . '/inc/theme-setup.php';
require_once MYCO_DIR . '/inc/enqueue.php';
require_once MYCO_DIR . '/inc/menus.php';
require_once MYCO_DIR . '/inc/widgets.php';
require_once MYCO_DIR . '/inc/custom-post-types.php';
require_once MYCO_DIR . '/inc/taxonomies.php';
require_once MYCO_DIR . '/inc/helpers.php';
require_once MYCO_DIR . '/inc/customizer.php';

// Auto-flush rewrite rules when theme version changes (ensures CPT slugs always work)
add_action('init', function () {
    $flush_version = '1.1'; // Bump this if CPT slugs change
    if (get_option('myco_rewrite_flush_version') !== $flush_version) {
        flush_rewrite_rules();
        update_option('myco_rewrite_flush_version', $flush_version);
    }
}, 999);

// Auto-fix page templates after reinstall (runs once when version changes)
add_action('init', function () {
    $template_fix_version = MYCO_VERSION;
    if (get_option('myco_template_fix_version') !== $template_fix_version) {
        if (function_exists('myco_get_required_pages')) {
            foreach (myco_get_required_pages() as $page_data) {
                if (empty($page_data['template'])) continue;
                $page = get_page_by_path($page_data['slug']);
                if ($page) {
                    update_post_meta($page->ID, '_wp_page_template', $page_data['template']);
                }
            }
        }
        update_option('myco_template_fix_version', $template_fix_version);
    }
}, 998);

// ACF includes (only if ACF is active)
if (class_exists('ACF')) {
    require_once MYCO_DIR . '/inc/acf-options-pages.php';
    require_once MYCO_DIR . '/inc/acf-field-groups.php';
}

// Stripe handler
require_once MYCO_DIR . '/inc/stripe-handler.php';

// Receipt handler v2 (no-dependency: HTML receipt + wp_mail)
require_once MYCO_DIR . '/inc/receipt-handler.php';

// Form handler (Volunteer + Contact: AJAX, DB, email, admin pages)
require_once MYCO_DIR . '/inc/form-handler.php';

// AJAX handlers
require_once MYCO_DIR . '/inc/ajax-handlers.php';

// Auto-create pages, menus, and settings on theme activation
require_once MYCO_DIR . '/inc/auto-pages.php';

// Handle static demo program pages to prevent 404s
add_action('template_redirect', function () {
    if (!is_404()) return;

    $demo_slugs = [
        'basketball-fitness-nights',
        'youth-leadership-mentorship',
        'spiritual-identity-program',
        'community-development-service',
    ];

    $request = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    // Strip WordPress subfolder prefix if present (e.g. "wordpress/")
    $base = trim(parse_url(home_url(), PHP_URL_PATH), '/');
    if ($base && strpos($request, $base . '/') === 0) {
        $request = substr($request, strlen($base) + 1);
    }
    $request = trim($request, '/');

    $matched_slug = null;
    foreach ($demo_slugs as $slug) {
        if ($request === 'programs/' . $slug || $request === $slug) {
            $matched_slug = $slug;
            break;
        }
    }

    if (!$matched_slug) return;

    // Build a real-looking transient post so body_class() and other WP functions work
    global $wp_query, $post;

    $post_data = [
        'ID'             => -1,
        'post_title'     => ucwords(str_replace('-', ' ', $matched_slug)),
        'post_name'      => $matched_slug,
        'post_type'      => 'program',
        'post_status'    => 'publish',
        'post_content'   => '',
        'post_excerpt'   => '',
        'post_author'    => 1,
        'post_date'      => current_time('mysql'),
        'post_date_gmt'  => current_time('mysql', 1),
        'comment_status' => 'closed',
        'ping_status'    => 'closed',
        'filter'         => 'raw',
    ];

    $post = new WP_Post((object) $post_data);

    $wp_query->post              = $post;
    $wp_query->posts             = [$post];
    $wp_query->queried_object    = $post;
    $wp_query->queried_object_id = -1;
    $wp_query->found_posts       = 1;
    $wp_query->post_count        = 1;
    $wp_query->is_404            = false;
    $wp_query->is_single         = true;
    $wp_query->is_singular       = true;
    $wp_query->is_page           = false;
    $wp_query->is_archive        = false;

    setup_postdata($post);
    status_header(200);

    include get_template_directory() . '/single-program.php';
    exit;
});
