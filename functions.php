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
define('MYCO_VERSION', '1.0.0');
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

// ACF includes (only if ACF is active)
if (class_exists('ACF')) {
    require_once MYCO_DIR . '/inc/acf-options-pages.php';
    require_once MYCO_DIR . '/inc/acf-field-groups.php';
}

// Stripe handler
require_once MYCO_DIR . '/inc/stripe-handler.php';

// Receipt handler v2 (no-dependency: HTML receipt + wp_mail)
require_once MYCO_DIR . '/inc/receipt-handler-v2.php';

// Form handler (Volunteer + Contact: AJAX, DB, email, admin pages)
require_once MYCO_DIR . '/inc/form-handler.php';

// AJAX handlers
require_once MYCO_DIR . '/inc/ajax-handlers.php';

// Auto-create pages, menus, and settings on theme activation
require_once MYCO_DIR . '/inc/auto-pages.php';
