<?php
/**
 * Auto-create pages, menus, and reading settings on theme activation
 *
 * @package MYCO
 */

if (!defined('ABSPATH')) {
    exit;
}

// Run on theme activation
add_action('after_switch_theme', 'myco_setup_pages_and_menus');
add_action('init', 'myco_sync_required_pages', 20);

// Also provide an admin button to re-run setup
add_action('admin_notices', 'myco_setup_admin_notice');
add_action('admin_init', 'myco_handle_setup_action');

/**
 * All pages the MYCO site needs
 */
function myco_get_required_pages() {
    return [
        'home' => [
            'title'    => 'Home',
            'slug'     => 'home',
            'template' => '', // Uses front-page.php automatically
            'content'  => 'Welcome to MYCO - Moving the Community Forward.',
        ],
        'about' => [
            'title'    => 'About',
            'slug'     => 'about',
            'template' => 'page-templates/template-about.php',
            'content'  => 'Learn about MYCO and our mission.',
        ],
        'programs' => [
            'title'    => 'Programs',
            'slug'     => 'programs',
            'template' => 'page-templates/template-programs.php',
            'content'  => 'Explore our programs for Muslim youth.',
        ],
        'events' => [
            'title'    => 'Events',
            'slug'     => 'events',
            'template' => 'page-templates/template-events.php',
            'content'  => 'Upcoming events and community gatherings.',
        ],
        'volunteer' => [
            'title'    => 'Volunteer',
            'slug'     => 'volunteer',
            'template' => 'page-templates/template-volunteer.php',
            'content'  => 'Join our volunteer team.',
        ],
        'news' => [
            'title'    => 'News',
            'slug'     => 'news',
            'template' => 'page-templates/template-news.php',
            'content'  => 'Latest news from MYCO.',
        ],
        'gallery' => [
            'title'    => 'Gallery',
            'slug'     => 'gallery',
            'template' => 'page-templates/template-gallery.php',
            'content'  => 'Photo gallery of MYCO events and community.',
        ],
        'contact' => [
            'title'    => 'Contact',
            'slug'     => 'contact',
            'template' => 'page-templates/template-contact.php',
            'content'  => 'Get in touch with MYCO.',
        ],
        'mcyc' => [
            'title'    => 'MCYC',
            'slug'     => 'mcyc',
            'template' => '',
            'content'  => 'Learn more about MCYC. Full page design coming soon.',
        ],
        'donate' => [
            'title'    => 'Donate',
            'slug'     => 'donate',
            'template' => 'page-templates/template-donate.php',
            'content'  => 'Support MYCO with a donation.',
        ],
        'privacy-policy' => [
            'title'    => 'Privacy Policy',
            'slug'     => 'privacy-policy',
            'template' => 'page-templates/template-privacy.php',
            'content'  => 'MYCO Privacy Policy.',
        ],
    ];
}

/**
 * Ensure newly introduced required pages exist on existing installs.
 */
function myco_sync_required_pages() {
    $sync_version = '1.1';

    if (get_option('myco_required_pages_sync_version') === $sync_version) {
        return;
    }

    $pages         = myco_get_required_pages();
    $created_pages = false;

    foreach ($pages as $page_data) {
        $existing = get_page_by_path($page_data['slug']);
        $page_id  = myco_create_page_if_not_exists($page_data);

        if (!$existing && !empty($page_id)) {
            $created_pages = true;
        }
    }

    if ($created_pages) {
        flush_rewrite_rules();
    }

    update_option('myco_required_pages_sync_version', $sync_version);
}

/**
 * Create all required pages, assign templates, build menus, set front page
 */
function myco_setup_pages_and_menus() {
    $pages = myco_get_required_pages();
    $page_ids = [];

    foreach ($pages as $key => $page_data) {
        $page_ids[$key] = myco_create_page_if_not_exists($page_data);
    }

    // Set static front page
    if (!empty($page_ids['home'])) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $page_ids['home']);
    }

    // Create primary navigation menu
    myco_create_primary_menu($page_ids);

    // Create footer navigation menus
    myco_create_footer_menus($page_ids);

    // Create sample content (events, programs, news) so listings aren't empty
    myco_create_sample_content();

    // Flush rewrite rules
    flush_rewrite_rules();
}

/**
 * Create a page if it doesn't already exist (checks by slug)
 */
function myco_create_page_if_not_exists($page_data) {
    // Check if page with this slug already exists
    $existing = get_page_by_path($page_data['slug']);

    if ($existing) {
        $page_id = $existing->ID;

        // Always force-update the template on theme activation (handles reinstalls)
        if (!empty($page_data['template'])) {
            update_post_meta($page_id, '_wp_page_template', $page_data['template']);
        }

        // Make sure it's published
        if (get_post_status($page_id) !== 'publish') {
            wp_update_post([
                'ID'          => $page_id,
                'post_status' => 'publish',
            ]);
        }

        return $page_id;
    }

    // Create the page
    $page_id = wp_insert_post([
        'post_title'   => $page_data['title'],
        'post_name'    => $page_data['slug'],
        'post_content' => $page_data['content'],
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'post_author'  => get_current_user_id() ?: 1,
    ]);

    if ($page_id && !is_wp_error($page_id)) {
        // Assign page template
        if (!empty($page_data['template'])) {
            update_post_meta($page_id, '_wp_page_template', $page_data['template']);
        }
        return $page_id;
    }

    return 0;
}

/**
 * Create the primary (header) navigation menu
 */
function myco_create_primary_menu($page_ids) {
    $menu_name = 'MYCO Primary Navigation';
    $menu_exists = wp_get_nav_menu_object($menu_name);

    // Delete existing menu to rebuild fresh
    if ($menu_exists) {
        wp_delete_nav_menu($menu_exists->term_id);
    }

    $menu_id = wp_create_nav_menu($menu_name);

    if (is_wp_error($menu_id)) {
        return;
    }

    $position = 1;
    $top_level_items = [
        'home'      => 'Home',
        'programs'  => 'Programs',
        'events'    => 'Events',
        'volunteer' => 'Volunteer',
    ];

    foreach ($top_level_items as $key => $label) {
        if (empty($page_ids[$key])) {
            continue;
        }

        wp_update_nav_menu_item($menu_id, 0, [
            'menu-item-object-id' => $page_ids[$key],
            'menu-item-object'    => 'page',
            'menu-item-type'      => 'post_type',
            'menu-item-title'     => $label,
            'menu-item-status'    => 'publish',
            'menu-item-position'  => $position++,
        ]);
    }

    $about_parent_id = wp_update_nav_menu_item($menu_id, 0, [
        'menu-item-type'     => 'custom',
        'menu-item-url'      => '#',
        'menu-item-title'    => 'About Us',
        'menu-item-status'   => 'publish',
        'menu-item-position' => $position++,
    ]);

    if (!is_wp_error($about_parent_id) && !empty($about_parent_id)) {
        $about_children = [
            'about'   => 'About',
            'news'    => 'News',
            'gallery' => 'Gallery',
        ];

        foreach ($about_children as $key => $label) {
            if (empty($page_ids[$key])) {
                continue;
            }

            wp_update_nav_menu_item($menu_id, 0, [
                'menu-item-object-id' => $page_ids[$key],
                'menu-item-object'    => 'page',
                'menu-item-type'      => 'post_type',
                'menu-item-title'     => $label,
                'menu-item-status'    => 'publish',
                'menu-item-position'  => $position++,
                'menu-item-parent-id' => $about_parent_id,
            ]);
        }
    }

    $cta_items = [
        ['key' => 'contact', 'label' => 'Contact'],
        ['key' => 'mcyc', 'label' => 'MCYC', 'classes' => 'mcyc-btn'],
        ['key' => 'donate', 'label' => 'Donate', 'classes' => 'donate-btn'],
    ];

    foreach ($cta_items as $item) {
        if (empty($page_ids[$item['key']])) {
            continue;
        }

        $item_data = [
            'menu-item-object-id' => $page_ids[$item['key']],
            'menu-item-object'    => 'page',
            'menu-item-type'      => 'post_type',
            'menu-item-title'     => $item['label'],
            'menu-item-status'    => 'publish',
            'menu-item-position'  => $position++,
        ];

        if (!empty($item['classes'])) {
            $item_data['menu-item-classes'] = $item['classes'];
        }

        wp_update_nav_menu_item($menu_id, 0, $item_data);
    }

    // Assign menu to the primary location
    $locations = get_theme_mod('nav_menu_locations', []);
    $locations['primary'] = $menu_id;
    $locations['mobile'] = $menu_id;
    set_theme_mod('nav_menu_locations', $locations);
}

/**
 * Create footer navigation menus
 */
function myco_create_footer_menus($page_ids) {
    // Footer Navigation menu
    $footer_nav_name = 'MYCO Footer Navigation';
    $existing = wp_get_nav_menu_object($footer_nav_name);
    if ($existing) {
        wp_delete_nav_menu($existing->term_id);
    }

    $footer_nav_id = wp_create_nav_menu($footer_nav_name);
    if (!is_wp_error($footer_nav_id)) {
        $nav_items = ['home' => 'Home', 'about' => 'About Us', 'events' => 'Events', 'programs' => 'Programs', 'volunteer' => 'Get Involved'];
        $pos = 1;
        foreach ($nav_items as $key => $label) {
            if (!empty($page_ids[$key])) {
                wp_update_nav_menu_item($footer_nav_id, 0, [
                    'menu-item-object-id' => $page_ids[$key],
                    'menu-item-object'    => 'page',
                    'menu-item-type'      => 'post_type',
                    'menu-item-title'     => $label,
                    'menu-item-status'    => 'publish',
                    'menu-item-position'  => $pos++,
                ]);
            }
        }
        $locations = get_theme_mod('nav_menu_locations', []);
        $locations['footer_nav'] = $footer_nav_id;
        set_theme_mod('nav_menu_locations', $locations);
    }

    // Footer Resources menu
    $footer_res_name = 'MYCO Footer Resources';
    $existing = wp_get_nav_menu_object($footer_res_name);
    if ($existing) {
        wp_delete_nav_menu($existing->term_id);
    }

    $footer_res_id = wp_create_nav_menu($footer_res_name);
    if (!is_wp_error($footer_res_id)) {
        $res_items = ['gallery' => 'Gallery', 'news' => 'News', 'donate' => 'Donate', 'privacy-policy' => 'Privacy Policy', 'contact' => 'Contact Us'];
        $pos = 1;
        foreach ($res_items as $key => $label) {
            if (!empty($page_ids[$key])) {
                wp_update_nav_menu_item($footer_res_id, 0, [
                    'menu-item-object-id' => $page_ids[$key],
                    'menu-item-object'    => 'page',
                    'menu-item-type'      => 'post_type',
                    'menu-item-title'     => $label,
                    'menu-item-status'    => 'publish',
                    'menu-item-position'  => $pos++,
                ]);
            }
        }
        $locations = get_theme_mod('nav_menu_locations', []);
        $locations['footer_resources'] = $footer_res_id;
        set_theme_mod('nav_menu_locations', $locations);
    }

    // Footer Quick Links (dark footer)
    $footer_quick_name = 'MYCO Footer Quick Links';
    $existing = wp_get_nav_menu_object($footer_quick_name);
    if ($existing) {
        wp_delete_nav_menu($existing->term_id);
    }

    $footer_quick_id = wp_create_nav_menu($footer_quick_name);
    if (!is_wp_error($footer_quick_id)) {
        $quick_items = ['home' => 'Home', 'about' => 'About Us', 'programs' => 'Programs', 'events' => 'Events', 'news' => 'News'];
        $pos = 1;
        foreach ($quick_items as $key => $label) {
            if (!empty($page_ids[$key])) {
                wp_update_nav_menu_item($footer_quick_id, 0, [
                    'menu-item-object-id' => $page_ids[$key],
                    'menu-item-object'    => 'page',
                    'menu-item-type'      => 'post_type',
                    'menu-item-title'     => $label,
                    'menu-item-status'    => 'publish',
                    'menu-item-position'  => $pos++,
                ]);
            }
        }
        $locations = get_theme_mod('nav_menu_locations', []);
        $locations['footer_quick'] = $footer_quick_id;
        set_theme_mod('nav_menu_locations', $locations);
    }

    // Footer Get Involved (dark footer)
    $footer_inv_name = 'MYCO Footer Get Involved';
    $existing = wp_get_nav_menu_object($footer_inv_name);
    if ($existing) {
        wp_delete_nav_menu($existing->term_id);
    }

    $footer_inv_id = wp_create_nav_menu($footer_inv_name);
    if (!is_wp_error($footer_inv_id)) {
        $inv_items = ['volunteer' => 'Volunteer', 'donate' => 'Donate', 'contact' => 'Contact Us', 'gallery' => 'Gallery'];
        $pos = 1;
        foreach ($inv_items as $key => $label) {
            if (!empty($page_ids[$key])) {
                wp_update_nav_menu_item($footer_inv_id, 0, [
                    'menu-item-object-id' => $page_ids[$key],
                    'menu-item-object'    => 'page',
                    'menu-item-type'      => 'post_type',
                    'menu-item-title'     => $label,
                    'menu-item-status'    => 'publish',
                    'menu-item-position'  => $pos++,
                ]);
            }
        }
        $locations = get_theme_mod('nav_menu_locations', []);
        $locations['footer_involved'] = $footer_inv_id;
        set_theme_mod('nav_menu_locations', $locations);
    }
}

/**
 * Show admin notice with setup button if pages are missing
 */
function myco_setup_admin_notice() {
    // Only show to admins
    if (!current_user_can('manage_options')) {
        return;
    }

    // Check if setup was just completed
    if (isset($_GET['myco-setup-done'])) {
        echo '<div class="notice notice-success is-dismissible"><p><strong>MYCO Setup Complete!</strong> All pages, menus, and settings have been configured.</p></div>';
        return;
    }

    // Check if any required pages are missing or have wrong templates
    $pages = myco_get_required_pages();
    $missing = [];
    $wrong_template = [];
    foreach ($pages as $key => $page_data) {
        $existing = get_page_by_path($page_data['slug']);
        if (!$existing || get_post_status($existing->ID) !== 'publish') {
            $missing[] = $page_data['title'];
        } elseif (!empty($page_data['template'])) {
            $current_template = get_post_meta($existing->ID, '_wp_page_template', true);
            if ($current_template !== $page_data['template']) {
                $wrong_template[] = $page_data['title'];
            }
        }
    }

    // Check if primary menu is set
    $has_menu = has_nav_menu('primary');

    if (!empty($missing) || !empty($wrong_template) || !$has_menu) {
        $nonce = wp_create_nonce('myco_setup_nonce');
        $url = admin_url('admin.php?myco-run-setup=1&_wpnonce=' . $nonce);
        echo '<div class="notice notice-warning">';
        echo '<p><strong>MYCO Theme:</strong> ';
        if (!empty($missing)) {
            echo 'Missing pages: ' . esc_html(implode(', ', $missing)) . '. ';
        }
        if (!empty($wrong_template)) {
            echo 'Pages with incorrect template (may show unstyled): ' . esc_html(implode(', ', $wrong_template)) . '. ';
        }
        if (!$has_menu) {
            echo 'Primary navigation menu is not configured. ';
        }
        echo '<a href="' . esc_url($url) . '" class="button button-primary" style="margin-left:10px;">Run MYCO Setup</a>';
        echo '</p></div>';
    }
}

/**
 * Handle the setup action from admin notice
 */
function myco_handle_setup_action() {
    if (!isset($_GET['myco-run-setup'])) {
        return;
    }

    if (!current_user_can('manage_options')) {
        return;
    }

    if (!wp_verify_nonce(sanitize_text_field($_GET['_wpnonce'] ?? ''), 'myco_setup_nonce')) {
        return;
    }

    myco_setup_pages_and_menus();

    wp_safe_redirect(admin_url('index.php?myco-setup-done=1'));
    exit;
}

/**
 * Create sample CPT posts (events, programs, news_articles) so listings aren't empty
 * Only creates posts if none exist yet.
 */
function myco_create_sample_content() {
    $author_id = get_current_user_id() ?: 1;

    // ── Sample Programs ──────────────────────────────────────────
    $existing_programs = get_posts(['post_type' => 'program', 'posts_per_page' => 1, 'post_status' => 'publish']);
    if (empty($existing_programs)) {
        $programs = [
            [
                'title'    => 'Youth Leadership Development',
                'excerpt'  => 'Helping youth build confidence, communication skills, teamwork, responsibility, and leadership rooted in Islamic values.',
                'content'  => '<p>Our Youth Leadership Development program empowers Muslim youth to become confident leaders in their communities. Through workshops, mentorship, and hands-on projects, participants develop essential skills in communication, teamwork, and decision-making—all grounded in Islamic values.</p><p>This program meets weekly and includes leadership training, public speaking practice, and community service projects.</p>',
                'image'    => 'myco-youth-team-award-check-winners.jpg',
                'category' => 'Leadership',
            ],
            [
                'title'    => 'Spiritual Development',
                'excerpt'  => 'Lectures, youth halaqas, Islamic learning opportunities, and guidance that strengthens faith and identity.',
                'content'  => '<p>Strengthen your connection with Allah through our Spiritual Development program. We offer youth halaqas, Islamic lectures, Quran study circles, and spiritual guidance sessions that help young Muslims deepen their faith and understanding of Islam.</p><p>Sessions include weekly halaqas, monthly guest speakers, and Ramadan special programs.</p>',
                'image'    => 'myco-youth-basketball-event-congregational-prayer.jpg',
                'category' => 'Spiritual',
            ],
            [
                'title'    => 'Education & Skill Building',
                'excerpt'  => 'Support through educational initiatives such as computer literacy, counseling, learning support, and developmental programming.',
                'content'  => '<p>Our Education & Skill Building program provides comprehensive academic support and life skills training. From homework help and tutoring to computer literacy and career counseling, we equip youth with the tools they need to succeed.</p><p>Services include free tutoring, college prep workshops, resume building, and technology training.</p>',
                'image'    => 'myco-youth-community-center-groundbreaking-ceremony.jpg',
                'category' => 'Academic',
            ],
            [
                'title'    => 'Athletics & Training',
                'excerpt'  => 'Basketball, soccer, and other active programming that builds discipline, confidence, and brotherhood/sisterhood.',
                'content'  => '<p>Stay active and build lasting friendships through our Athletics & Training program. We offer basketball leagues, soccer tournaments, fitness training, and other sports activities that promote physical health, teamwork, and Islamic brotherhood/sisterhood.</p><p>Weekly basketball nights, seasonal soccer leagues, and fitness training sessions available for all skill levels.</p>',
                'image'    => 'myco-basketball-champions-team-with-trophy.jpg.jpg',
                'category' => 'Athletics',
            ],
            [
                'title'    => 'Social & Cultural Activities',
                'excerpt'  => 'Gatherings that foster belonging, friendship, and community connection across backgrounds.',
                'content'  => '<p>Build meaningful connections through our Social & Cultural Activities program. We host game nights, cultural celebrations, community dinners, and social events that bring Muslim youth together in a welcoming, faith-centered environment.</p><p>Monthly social events, holiday celebrations, and community gatherings throughout the year.</p>',
                'image'    => 'myco-basketball-tournament-award-ceremony-team-celebration.jpg.JPG',
                'category' => 'Social',
            ],
            [
                'title'    => 'Community Service & Innovation',
                'excerpt'  => 'Volunteer initiatives that teach youth to serve others and contribute meaningfully to their communities.',
                'content'  => '<p>Make a real difference through our Community Service & Innovation program. Youth participate in volunteer projects, food drives, community clean-ups, and innovative service initiatives that embody Islamic values of compassion and social responsibility.</p><p>Monthly service projects, partnership with local organizations, and youth-led community initiatives.</p>',
                'image'    => 'MCYC Groundbreaking_ Aatifa.jpg',
                'category' => 'Community Service',
            ],
        ];

        foreach ($programs as $p) {
            $id = wp_insert_post([
                'post_title'   => $p['title'],
                'post_content' => $p['content'],
                'post_excerpt' => $p['excerpt'],
                'post_status'  => 'publish',
                'post_type'    => 'program',
                'post_author'  => $author_id,
            ]);

            // Set category
            if ($id && !empty($p['category'])) {
                wp_set_object_terms($id, $p['category'], 'program_category');
            }
            
            // Set featured image if image filename provided
            if ($id && !empty($p['image'])) {
                $image_path = get_template_directory() . '/assets/images/Galleries/' . $p['image'];
                if (file_exists($image_path)) {
                    $upload_dir = wp_upload_dir();
                    $filename = basename($image_path);
                    
                    // Check if already in media library
                    $existing_attachment = get_posts([
                        'post_type' => 'attachment',
                        'meta_query' => [
                            ['key' => '_wp_attached_file', 'value' => $filename, 'compare' => 'LIKE']
                        ],
                        'posts_per_page' => 1,
                    ]);
                    
                    if (!empty($existing_attachment)) {
                        set_post_thumbnail($id, $existing_attachment[0]->ID);
                    } else {
                        // Upload to media library
                        require_once(ABSPATH . 'wp-admin/includes/file.php');
                        require_once(ABSPATH . 'wp-admin/includes/image.php');
                        require_once(ABSPATH . 'wp-admin/includes/media.php');
                        
                        $filetype = wp_check_filetype($filename);
                        $attachment = [
                            'guid'           => $upload_dir['url'] . '/' . $filename,
                            'post_mime_type' => $filetype['type'],
                            'post_title'     => preg_replace('/\.[^.]+$/', '', $filename),
                            'post_content'   => '',
                            'post_status'    => 'inherit'
                        ];
                        
                        $new_file = $upload_dir['path'] . '/' . $filename;
                        copy($image_path, $new_file);
                        
                        $attach_id = wp_insert_attachment($attachment, $new_file, $id);
                        $attach_data = wp_generate_attachment_metadata($attach_id, $new_file);
                        wp_update_attachment_metadata($attach_id, $attach_data);
                        set_post_thumbnail($id, $attach_id);
                    }
                }
            }
        }
    }

    // ── Sample Events ─────────────────────────────────────────────
    $existing_events = get_posts(['post_type' => 'event', 'posts_per_page' => 1, 'post_status' => 'publish']);
    if (empty($existing_events)) {
        $future = strtotime('+1 month');
        $events = [
            [
                'title'    => 'Youth Basketball Night',
                'excerpt'  => 'A friendly competitive evening of hoops and brotherhood for all skill levels.',
                'content'  => '<p>Get ready for an exciting evening of basketball at MYCO\'s Youth Basketball Night! This event brings together Muslim youth for a night of friendly competition, skill-building, and community bonding. Whether you\'re a seasoned player or just starting out, everyone is welcome.</p>',
                'date'     => date('Y-m-d', $future),
                'time'     => '6:00 PM',
                'location' => 'MYCO Community Center',
            ],
            [
                'title'    => 'Academic Tutoring: Math & Science',
                'excerpt'  => 'Free tutoring session for high school students in math and science.',
                'content'  => '<p>Join us for a free tutoring session focused on math and science. Our volunteer tutors will help you tackle challenging concepts, review homework, and prepare for upcoming exams. Open to all MYCO members.</p>',
                'date'     => date('Y-m-d', strtotime('+3 days', $future)),
                'time'     => '4:00 PM',
                'location' => 'MYCO Learning Center',
            ],
            [
                'title'    => 'Monthly Service Day: Food Pantry Volunteering',
                'excerpt'  => 'Join us to serve the community at the Central Ohio Food Bank.',
                'content'  => '<p>Give back to the community by volunteering at the Central Ohio Food Bank. We\'ll sort donations, pack food boxes, and help distribute meals to families in need. This is a great opportunity to fulfill the Islamic value of community service.</p>',
                'date'     => date('Y-m-d', strtotime('+2 weeks', $future)),
                'time'     => '9:00 AM',
                'location' => 'Central Ohio Food Bank',
            ],
        ];

        foreach ($events as $e) {
            $id = wp_insert_post([
                'post_title'   => $e['title'],
                'post_content' => $e['content'],
                'post_excerpt' => $e['excerpt'],
                'post_status'  => 'publish',
                'post_type'    => 'event',
                'post_author'  => $author_id,
            ]);
            if ($id && !is_wp_error($id)) {
                update_post_meta($id, 'event_date', $e['date']);
                update_post_meta($id, 'event_start_time', $e['time']);
                update_post_meta($id, 'event_location_name', $e['location']);
                update_post_meta($id, 'event_cost', 'Free');
            }
        }
    }

    // ── Sample News Articles ──────────────────────────────────────
    $existing_news = get_posts(['post_type' => 'news_article', 'posts_per_page' => 1, 'post_status' => 'publish']);
    if (empty($existing_news)) {
        $articles = [
            [
                'title'   => 'MYCO Launches New Mentorship Program for Muslim Youth',
                'excerpt' => 'We are proud to announce the expansion of our mentorship program, connecting youth with experienced community leaders.',
                'content' => '<p>MYCO is excited to announce the launch of our expanded Mentorship Circles program, designed to connect Muslim youth ages 13–18 with experienced community mentors. This initiative builds on our commitment to empowering the next generation of Muslim leaders in Central Ohio.</p><p>The program features bi-weekly one-on-one sessions, group workshops, and community service projects. Applications are now open for both mentors and mentees.</p><p>For more information, visit our Programs page or contact us at info@myco.org.</p>',
            ],
            [
                'title'   => 'Record Turnout at Annual Youth Basketball Tournament',
                'excerpt' => 'Over 200 young athletes participated in this year\'s basketball tournament, making it our most successful event yet.',
                'content' => '<p>This year\'s MYCO Youth Basketball Tournament was a tremendous success, drawing over 200 participants from across Central Ohio. The event featured teams from 12 different mosques and Islamic centers competing in a spirit of brotherhood and sportsmanship.</p><p>The tournament not only showcased athletic talent but also provided an opportunity for youth to strengthen friendships and build community bonds in a positive, faith-centered environment.</p>',
            ],
            [
                'title'   => 'MYCO Partners with Local Schools for After-School Tutoring',
                'excerpt' => 'Our new partnership brings free tutoring services to three Columbus area schools, benefiting over 150 students.',
                'content' => '<p>MYCO is proud to announce a new partnership with three Columbus area schools to provide free after-school tutoring services. Through this initiative, volunteer tutors will offer support in math, science, reading, and study skills to students in grades 6–12.</p><p>The program is funded through community donations and volunteer efforts. If you\'re interested in volunteering as a tutor or supporting this initiative financially, please visit our volunteer and donation pages.</p>',
            ],
        ];

        foreach ($articles as $a) {
            wp_insert_post([
                'post_title'   => $a['title'],
                'post_content' => $a['content'],
                'post_excerpt' => $a['excerpt'],
                'post_status'  => 'publish',
                'post_type'    => 'news_article',
                'post_author'  => $author_id,
            ]);
        }
    }
}
