<?php
/**
 * Navigation Menus
 *
 * @package MYCO
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('after_setup_theme', 'myco_register_menus');

function myco_register_menus() {
    register_nav_menus([
        'primary'          => __('Primary Navigation (Pill Nav)', 'myco'),
        'mobile'           => __('Mobile Navigation', 'myco'),
        'footer_nav'       => __('Footer Navigation', 'myco'),
        'footer_resources' => __('Footer Resources', 'myco'),
        'footer_quick'     => __('Footer Quick Links', 'myco'),
        'footer_involved'  => __('Footer Get Involved', 'myco'),
    ]);
}

/**
 * Custom Walker for Pill Navigation
 * Outputs flat <a> tags inside pill-nav container (no <ul>/<li>)
 */
class Walker_Pill_Nav extends Walker_Nav_Menu {

    public function start_lvl(&$output, $depth = 0, $args = null) {
        // No nested lists
    }

    public function end_lvl(&$output, $depth = 0, $args = null) {
        // No nested lists
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $classes = [];

        // Check if current page
        if ($item->current || $item->current_item_ancestor || $item->current_item_parent) {
            $classes[] = 'active';
        }

        // Check for donate-btn class in menu item CSS classes
        if (in_array('donate-btn', $item->classes, true) || in_array('menu-donate', $item->classes, true)) {
            $classes[] = 'donate-btn';
        }

        $class_attr = !empty($classes) ? ' class="' . esc_attr(implode(' ', $classes)) . '"' : '';
        $aria_current = $item->current ? ' aria-current="page"' : '';

        $output .= '<a href="' . esc_url($item->url) . '"' . $class_attr . $aria_current . '>';
        $output .= esc_html($item->title);
    }

    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= '</a>';
    }
}

/**
 * Custom Walker for Mobile Menu
 * Outputs themed mobile menu links
 */
class Walker_Mobile_Nav extends Walker_Nav_Menu {

    public function start_lvl(&$output, $depth = 0, $args = null) {
        // No nested lists
    }

    public function end_lvl(&$output, $depth = 0, $args = null) {
        // No nested lists
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        // Check if donate button
        $is_donate = in_array('donate-btn', $item->classes, true) || in_array('menu-donate', $item->classes, true);
        $is_current = $item->current || $item->current_item_ancestor;

        if ($is_donate) {
            $output .= '<a href="' . esc_url($item->url) . '" class="mx-2 mb-1 mt-1 px-4 py-3 rounded-xl text-white font-semibold text-sm text-center" style="background: #c8402e; box-shadow: 0 3px 12px 0 rgba(200,64,46,0.35);">';
        } elseif ($is_current) {
            $output .= '<a href="' . esc_url($item->url) . '" class="px-4 py-3 rounded-xl text-navy-dark font-semibold bg-gray-100 text-sm" aria-current="page">';
        } else {
            $output .= '<a href="' . esc_url($item->url) . '" class="px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 text-sm font-medium transition-colors">';
        }

        $output .= esc_html($item->title);
    }

    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= '</a>';
    }
}

/**
 * Custom Walker for Footer Navigation
 * Outputs flat <a> tags with footer-nav-link class
 */
class Walker_Footer_Nav extends Walker_Nav_Menu {

    public function start_lvl(&$output, $depth = 0, $args = null) {
        // No nested lists
    }

    public function end_lvl(&$output, $depth = 0, $args = null) {
        // No nested lists
    }

    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $output .= '<a href="' . esc_url($item->url) . '" class="footer-nav-link">';
        $output .= esc_html($item->title);
    }

    public function end_el(&$output, $item, $depth = 0, $args = null) {
        $output .= '</a>';
    }
}

/**
 * Fallback menu for when no menu is assigned (desktop pill nav)
 */
function myco_fallback_menu() {
    $current_url = trailingslashit(home_url(add_query_arg([], false)));
    $links = [
        ['url' => home_url('/'), 'label' => 'Home'],
        ['url' => home_url('/about/'), 'label' => 'About'],
        ['url' => home_url('/programs/'), 'label' => 'Programs'],
        ['url' => home_url('/events/'), 'label' => 'Events'],
        ['url' => home_url('/volunteer/'), 'label' => 'Volunteer'],
        ['url' => home_url('/news/'), 'label' => 'News'],
        ['url' => home_url('/gallery/'), 'label' => 'Gallery'],
        ['url' => home_url('/contact/'), 'label' => 'Contact'],
    ];
    foreach ($links as $i => $link) {
        $is_active = (trailingslashit($link['url']) === $current_url) || (is_front_page() && $link['label'] === 'Home');
        $class = $is_active ? ' class="active" aria-current="page"' : '';
        echo '<a href="' . esc_url($link['url']) . '"' . $class . '>' . esc_html($link['label']) . '</a>';
    }
    echo '<a href="' . esc_url(home_url('/donate/')) . '" class="donate-btn">Donate</a>';
}

/**
 * Fallback menu for mobile navigation
 */
function myco_fallback_mobile_menu() {
    $current_url = trailingslashit(home_url(add_query_arg([], false)));
    $links = [
        ['url' => home_url('/'), 'label' => 'Home'],
        ['url' => home_url('/about/'), 'label' => 'About'],
        ['url' => home_url('/programs/'), 'label' => 'Programs'],
        ['url' => home_url('/events/'), 'label' => 'Events'],
        ['url' => home_url('/volunteer/'), 'label' => 'Volunteer'],
        ['url' => home_url('/news/'), 'label' => 'News'],
        ['url' => home_url('/gallery/'), 'label' => 'Gallery'],
        ['url' => home_url('/contact/'), 'label' => 'Contact'],
    ];
    foreach ($links as $link) {
        $is_active = (trailingslashit($link['url']) === $current_url) || (is_front_page() && $link['label'] === 'Home');
        if ($is_active) {
            echo '<a href="' . esc_url($link['url']) . '" class="px-4 py-3 rounded-xl text-navy-dark font-semibold bg-gray-100 text-sm" aria-current="page">' . esc_html($link['label']) . '</a>';
        } else {
            echo '<a href="' . esc_url($link['url']) . '" class="px-4 py-3 rounded-xl text-gray-600 hover:bg-gray-50 text-sm font-medium transition-colors">' . esc_html($link['label']) . '</a>';
        }
    }
    echo '<a href="' . esc_url(home_url('/donate/')) . '" class="mx-2 mb-1 mt-1 px-4 py-3 rounded-xl text-white font-semibold text-sm text-center" style="background: #c8402e; box-shadow: 0 3px 12px 0 rgba(200,64,46,0.35);">Donate</a>';
}
