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
 * Normalize a URL to a path for active-state comparisons.
 */
function myco_get_nav_path($url = '') {
    $path = wp_parse_url((string) $url, PHP_URL_PATH);

    if (!$path) {
        return '/';
    }

    return trailingslashit('/' . ltrim($path, '/'));
}

/**
 * Check whether a navigation URL matches the current request path.
 */
function myco_nav_url_matches_current($url = '') {
    global $wp;

    if (empty($url)) {
        return false;
    }

    $current_path = '/';

    if (!empty($wp->request)) {
        $current_path = '/' . ltrim($wp->request, '/');
    }

    return myco_get_nav_path($url) === trailingslashit($current_path);
}

/**
 * Determine whether a primary navigation item should be highlighted.
 */
function myco_is_primary_nav_item_active($key, $url = '') {
    switch ($key) {
        case 'home':
            return is_front_page() || is_home();

        case 'programs':
            return is_page_template('page-templates/template-programs.php')
                || is_post_type_archive('program')
                || is_singular('program')
                || myco_nav_url_matches_current($url);

        case 'events':
            return is_page_template('page-templates/template-events.php')
                || is_post_type_archive('event')
                || is_singular('event')
                || myco_nav_url_matches_current($url);

        case 'volunteer':
            return is_page_template('page-templates/template-volunteer.php')
                || myco_nav_url_matches_current($url);

        case 'about':
            return is_page_template('page-templates/template-about.php')
                || myco_nav_url_matches_current($url);

        case 'news':
            return is_page_template('page-templates/template-news.php')
                || is_post_type_archive('news_article')
                || is_singular('news_article')
                || myco_nav_url_matches_current($url);

        case 'gallery':
            return is_page_template('page-templates/template-gallery.php')
                || is_post_type_archive('gallery_photo')
                || is_singular('gallery_photo')
                || is_tax('gallery_album')
                || myco_nav_url_matches_current($url);

        case 'contact':
            return is_page_template('page-templates/template-contact.php')
                || myco_nav_url_matches_current($url);

        case 'mcyc':
            return is_page('mcyc') || myco_nav_url_matches_current($url);

        case 'donate':
            return is_page_template('page-templates/template-donate.php')
                || myco_nav_url_matches_current($url);
    }

    return myco_nav_url_matches_current($url);
}

/**
 * Shared primary navigation structure used by the desktop and mobile headers.
 */
function myco_get_mcyc_section_nav_items() {
    $mcyc_url = myco_get_page_url('mcyc', '/mcyc/');

    return [
        ['label' => __('Vision', 'myco'), 'url' => $mcyc_url . '#vision'],
        ['label' => __('Progress', 'myco'), 'url' => $mcyc_url . '#construction-update'],
        ['label' => __('Building', 'myco'), 'url' => $mcyc_url . '#building'],
        ['label' => __('Programs', 'myco'), 'url' => $mcyc_url . '#programs'],
        ['label' => __('Voices', 'myco'), 'url' => $mcyc_url . '#community-voices'],
        ['label' => __('Video', 'myco'), 'url' => $mcyc_url . '#campaign-video'],
    ];
}

/**
 * Shared primary navigation structure used by the desktop and mobile headers.
 */
function myco_get_primary_nav_items() {
    $donate_label = sanitize_text_field(get_theme_mod('myco_nav_donate_label', __('Donate', 'myco')));

    if ($donate_label === '') {
        $donate_label = __('Donate', 'myco');
    }

    $items = [
        [
            'type'  => 'link',
            'key'   => 'home',
            'label' => __('Home', 'myco'),
            'url'   => home_url('/'),
        ],
        [
            'type'     => 'dropdown',
            'key'      => 'about-group',
            'label'    => __('About Us', 'myco'),
            'children' => [
                [
                    'key'   => 'about',
                    'label' => __('About', 'myco'),
                    'url'   => myco_get_page_url('about', '/about/'),
                ],
                [
                    'key'   => 'news',
                    'label' => __('News', 'myco'),
                    'url'   => myco_get_page_url('news', '/news/'),
                ],
                [
                    'key'   => 'gallery',
                    'label' => __('Gallery', 'myco'),
                    'url'   => myco_get_page_url('gallery', '/gallery/'),
                ],
            ],
        ],
        [
            'type'  => 'link',
            'key'   => 'programs',
            'label' => __('Programs', 'myco'),
            'url'   => myco_get_page_url('programs', '/programs/'),
        ],
        [
            'type'  => 'link',
            'key'   => 'events',
            'label' => __('Events', 'myco'),
            'url'   => myco_get_page_url('events', '/events/'),
        ],
        [
            'type'  => 'link',
            'key'   => 'volunteer',
            'label' => __('Volunteer', 'myco'),
            'url'   => myco_get_page_url('volunteer', '/volunteer/'),
        ],
        [
            'type'  => 'link',
            'key'   => 'contact',
            'label' => __('Contact', 'myco'),
            'url'   => myco_get_contact_page_url(),
        ],
        [
            'type'    => 'link',
            'key'     => 'mcyc',
            'label'   => __('MCYC', 'myco'),
            'url'     => myco_get_page_url('mcyc', '/mcyc/'),
            'classes' => ['mcyc-btn'],
        ],
        [
            'type'    => 'link',
            'key'     => 'donate',
            'label'   => $donate_label,
            'url'     => myco_get_page_url('donate', '/donate/'),
            'classes' => ['donate-btn'],
        ],
    ];

    foreach ($items as &$item) {
        if (($item['type'] ?? '') === 'dropdown' || ($item['type'] ?? '') === 'link-dropdown') {
            $item['active'] = ($item['type'] ?? '') === 'link-dropdown'
                ? myco_is_primary_nav_item_active($item['key'], $item['url'])
                : false;

            foreach ($item['children'] as $index => &$child) {
                if (($item['type'] ?? '') === 'link-dropdown') {
                    $child['active'] = !empty($item['active']) && $index === 0;
                } else {
                    $child['active'] = myco_is_primary_nav_item_active($child['key'], $child['url']);
                }

                if ($child['active']) {
                    $item['active'] = true;
                }
            }
            unset($child);

            continue;
        }

        $item['active'] = myco_is_primary_nav_item_active($item['key'], $item['url']);
    }
    unset($item);

    return $items;
}

/**
 * Render the desktop primary navigation.
 */
function myco_render_primary_nav() {
    $items = myco_get_primary_nav_items();

    foreach ($items as $item) {
        if (($item['type'] ?? '') === 'dropdown' || ($item['type'] ?? '') === 'link-dropdown') {
            $wrapper_classes = ['pill-nav-dropdown'];
            $menu_classes    = ['pill-nav-dropdown-menu'];

            if (($item['type'] ?? '') === 'link-dropdown') {
                $wrapper_classes[] = 'pill-nav-dropdown--mcyc';
                $menu_classes[]    = 'pill-nav-dropdown-menu--mcyc';
            }

            if (!empty($item['active'])) {
                $wrapper_classes[] = 'is-active';
            }

            echo '<div class="' . esc_attr(implode(' ', $wrapper_classes)) . '">';

            if (($item['type'] ?? '') === 'link-dropdown') {
                $classes = ['pill-nav-link'];

                if (!empty($item['classes']) && is_array($item['classes'])) {
                    $classes = array_merge($classes, $item['classes']);
                }

                $aria_current = '';

                if (!empty($item['active'])) {
                    $classes[]    = 'active';
                    $aria_current = ' aria-current="page"';
                }

                echo '<a href="' . esc_url($item['url']) . '" class="' . esc_attr(implode(' ', array_unique($classes))) . '"' . $aria_current . '>';
                echo esc_html($item['label']);
                echo '</a>';
            } else {
                $toggle_classes  = ['pill-nav-dropdown-toggle'];

                if (!empty($item['active'])) {
                    $toggle_classes[]  = 'active';
                }

                echo '<button type="button" class="' . esc_attr(implode(' ', $toggle_classes)) . '" aria-haspopup="true">';
                echo esc_html($item['label']);
                echo '<span class="pill-nav-caret" aria-hidden="true"></span>';
                echo '</button>';
            }

            echo '<div class="' . esc_attr(implode(' ', $menu_classes)) . '">';

            foreach ($item['children'] as $child) {
                $child_classes = (($item['type'] ?? '') === 'link-dropdown')
                    ? ['pill-nav-dropdown-link', 'mcyc-section-dropdown-link']
                    : ['pill-nav-dropdown-link'];
                $aria_current  = '';

                if (!empty($child['active'])) {
                    $child_classes[] = 'active';
                    $aria_current    = ' aria-current="page"';
                }

                echo '<a href="' . esc_url($child['url']) . '" class="' . esc_attr(implode(' ', $child_classes)) . '"' . $aria_current . '>';
                echo esc_html($child['label']);
                echo '</a>';
            }

            echo '</div>';
            echo '</div>';
            continue;
        }

        $classes = ['pill-nav-link'];

        if (!empty($item['classes']) && is_array($item['classes'])) {
            $classes = array_merge($classes, $item['classes']);
        }

        $aria_current = '';

        if (!empty($item['active'])) {
            $classes[]    = 'active';
            $aria_current = ' aria-current="page"';
        }

        echo '<a href="' . esc_url($item['url']) . '" class="' . esc_attr(implode(' ', array_unique($classes))) . '"' . $aria_current . '>';
        echo esc_html($item['label']);
        if (in_array('mcyc-btn', $classes, true)) {
            echo '<span class="mcyc-btn-arrow" aria-hidden="true">›</span>';
        }
        echo '</a>';
    }
}

/**
 * Render the mobile primary navigation.
 */
function myco_render_mobile_nav() {
    $items = myco_get_primary_nav_items();

    foreach ($items as $item) {
        if (($item['type'] ?? '') === 'dropdown' || ($item['type'] ?? '') === 'link-dropdown') {
            if (($item['type'] ?? '') === 'link-dropdown') {
                $classes = ['mobile-menu-link'];

                if (!empty($item['classes']) && is_array($item['classes'])) {
                    $classes = array_merge($classes, $item['classes']);
                }

                $aria_current = '';

                if (!empty($item['active'])) {
                    $classes[]    = 'active';
                    $aria_current = ' aria-current="page"';
                }

                echo '<div class="mobile-menu-group mobile-menu-group--mcyc">';
                echo '<a href="' . esc_url($item['url']) . '" class="' . esc_attr(implode(' ', array_unique($classes))) . '"' . $aria_current . '>';
                echo esc_html($item['label']);
                echo '</a>';
                echo '<div class="mobile-menu-children">';

                foreach ($item['children'] as $child) {
                    $child_classes = ['mobile-menu-link', 'mobile-submenu-link'];
                    $child_current = '';

                    if (!empty($child['active'])) {
                        $child_classes[] = 'active';
                        $child_current   = ' aria-current="page"';
                    }

                    echo '<a href="' . esc_url($child['url']) . '" class="' . esc_attr(implode(' ', $child_classes)) . '"' . $child_current . '>';
                    echo esc_html($child['label']);
                    echo '</a>';
                }

                echo '</div>';
                echo '</div>';
                continue;
            }

            $heading_classes = ['mobile-menu-heading'];

            if (!empty($item['active'])) {
                $heading_classes[] = 'active';
            }

            echo '<div class="mobile-menu-group">';
            echo '<div class="' . esc_attr(implode(' ', $heading_classes)) . '">';
            echo esc_html($item['label']);
            echo '</div>';
            echo '<div class="mobile-menu-children">';

            foreach ($item['children'] as $child) {
                $child_classes = ['mobile-menu-link', 'mobile-submenu-link'];
                $aria_current  = '';

                if (!empty($child['active'])) {
                    $child_classes[] = 'active';
                    $aria_current    = ' aria-current="page"';
                }

                echo '<a href="' . esc_url($child['url']) . '" class="' . esc_attr(implode(' ', $child_classes)) . '"' . $aria_current . '>';
                echo esc_html($child['label']);
                echo '</a>';
            }

            echo '</div>';
            echo '</div>';
            continue;
        }

        $classes = ['mobile-menu-link'];

        if (!empty($item['classes']) && is_array($item['classes'])) {
            $classes = array_merge($classes, $item['classes']);
        }

        $aria_current = '';

        if (!empty($item['active'])) {
            $classes[]    = 'active';
            $aria_current = ' aria-current="page"';
        }

        echo '<a href="' . esc_url($item['url']) . '" class="' . esc_attr(implode(' ', array_unique($classes))) . '"' . $aria_current . '>';
        echo esc_html($item['label']);
        echo '</a>';
    }
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
    myco_render_primary_nav();
}

/**
 * Fallback menu for mobile navigation
 */
function myco_fallback_mobile_menu() {
    myco_render_mobile_nav();
}
