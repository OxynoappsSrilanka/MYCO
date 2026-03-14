<?php
/**
 * ACF Options Pages
 *
 * @package MYCO
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('acf_add_options_page')) {
    return;
}

// Main options page
acf_add_options_page([
    'page_title' => __('MYCO Settings', 'myco'),
    'menu_title' => __('MYCO Settings', 'myco'),
    'menu_slug'  => 'myco-settings',
    'capability' => 'manage_options',
    'icon_url'   => 'dashicons-admin-generic',
    'position'   => 2,
    'redirect'   => true,
]);

// Sub pages
acf_add_options_sub_page([
    'page_title'  => __('General Settings', 'myco'),
    'menu_title'  => __('General', 'myco'),
    'menu_slug'   => 'myco-general',
    'parent_slug' => 'myco-settings',
]);

acf_add_options_sub_page([
    'page_title'  => __('Donation Settings', 'myco'),
    'menu_title'  => __('Donations', 'myco'),
    'menu_slug'   => 'myco-donations',
    'parent_slug' => 'myco-settings',
]);

acf_add_options_sub_page([
    'page_title'  => __('Footer Settings', 'myco'),
    'menu_title'  => __('Footer', 'myco'),
    'menu_slug'   => 'myco-footer',
    'parent_slug' => 'myco-settings',
]);
