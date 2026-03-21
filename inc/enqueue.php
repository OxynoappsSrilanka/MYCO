<?php
/**
 * Enqueue Styles and Scripts
 *
 * @package MYCO
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_enqueue_scripts', 'myco_enqueue_styles');
add_action('wp_enqueue_scripts', 'myco_enqueue_scripts');

function myco_enqueue_styles() {
    // Google Fonts: Inter
    wp_enqueue_style(
        'google-fonts-inter',
        'https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;0,800;0,900&display=swap',
        [],
        null
    );

    // Tailwind CSS: use compiled file if it exists, otherwise CDN
    $tailwind_file = MYCO_DIR . '/assets/css/tailwind-output.css';
    $tailwind_ver  = file_exists($tailwind_file) ? filemtime($tailwind_file) : MYCO_VERSION;
    $custom_file   = MYCO_DIR . '/assets/css/custom.css';
    $custom_ver    = file_exists($custom_file) ? filemtime($custom_file) : MYCO_VERSION;
    $typefix_file  = MYCO_DIR . '/assets/css/typography-fix.css';
    $typefix_ver   = file_exists($typefix_file) ? filemtime($typefix_file) : MYCO_VERSION;
    $events_file   = MYCO_DIR . '/assets/css/events.css';
    $events_ver    = file_exists($events_file) ? filemtime($events_file) : MYCO_VERSION;
    $donate_file   = MYCO_DIR . '/assets/css/donate.css';
    $donate_ver    = file_exists($donate_file) ? filemtime($donate_file) : MYCO_VERSION;
    $gallery_file  = MYCO_DIR . '/assets/css/gallery.css';
    $gallery_ver   = file_exists($gallery_file) ? filemtime($gallery_file) : MYCO_VERSION;
    $accordion_file = MYCO_DIR . '/assets/css/accordion.css';
    $accordion_ver  = file_exists($accordion_file) ? filemtime($accordion_file) : MYCO_VERSION;
    $mcyc_file      = MYCO_DIR . '/assets/css/mcyc.css';
    $mcyc_ver       = file_exists($mcyc_file) ? filemtime($mcyc_file) : MYCO_VERSION;
    $news_file      = MYCO_DIR . '/assets/css/news.css';
    $news_ver       = file_exists($news_file) ? filemtime($news_file) : MYCO_VERSION;

    if (file_exists($tailwind_file) && filesize($tailwind_file) > 0) {
        wp_enqueue_style(
            'myco-tailwind',
            MYCO_URI . '/assets/css/tailwind-output.css',
            ['google-fonts-inter'],
            $tailwind_ver
        );
    }

    // Custom shared styles (pill-nav, buttons, containers, footers)
    wp_enqueue_style(
        'myco-custom',
        MYCO_URI . '/assets/css/custom.css',
        ['google-fonts-inter'],
        $custom_ver
    );

    // Typography fix - Remove chromatic aberration effects
    wp_enqueue_style(
        'myco-typography-fix',
        MYCO_URI . '/assets/css/typography-fix.css',
        ['myco-custom'],
        $typefix_ver
    );

    // Page-specific styles (conditional loading)
    if (is_page_template('page-templates/template-donate.php')) {
        wp_enqueue_style('myco-donate', MYCO_URI . '/assets/css/donate.css', ['myco-custom'], $donate_ver);
    }

    if (is_page_template('page-templates/template-gallery.php')) {
        wp_enqueue_style('myco-gallery', MYCO_URI . '/assets/css/gallery.css', ['myco-custom'], $gallery_ver);
    }

    if (is_page_template('page-templates/template-privacy.php') || is_page_template('page-templates/template-contact.php')) {
        wp_enqueue_style('myco-accordion', MYCO_URI . '/assets/css/accordion.css', ['myco-custom'], $accordion_ver);
    }

    if (is_page_template('page-templates/template-events.php') || is_post_type_archive('event') || is_singular('event')) {
        wp_enqueue_style('myco-events', MYCO_URI . '/assets/css/events.css', ['myco-custom'], $events_ver);
    }

    if (is_page('mcyc')) {
        wp_enqueue_style('myco-mcyc', MYCO_URI . '/assets/css/mcyc.css', ['myco-custom'], $mcyc_ver);
    }

    if (is_page_template('page-templates/template-news.php') || is_post_type_archive('news_article') || is_singular('news_article')) {
        wp_enqueue_style('myco-news', MYCO_URI . '/assets/css/news.css', ['myco-custom'], $news_ver);
    }
}

function myco_enqueue_scripts() {
    $navigation_file = MYCO_DIR . '/assets/js/navigation.js';
    $navigation_ver  = file_exists($navigation_file) ? filemtime($navigation_file) : MYCO_VERSION;
    $testimonials_file = MYCO_DIR . '/assets/js/testimonials.js';
    $testimonials_ver  = file_exists($testimonials_file) ? filemtime($testimonials_file) : MYCO_VERSION;
    $gallery_file = MYCO_DIR . '/assets/js/gallery.js';
    $gallery_ver  = file_exists($gallery_file) ? filemtime($gallery_file) : MYCO_VERSION;
    $donate_file = MYCO_DIR . '/assets/js/donate.js';
    $donate_ver  = file_exists($donate_file) ? filemtime($donate_file) : MYCO_VERSION;
    $accordion_file = MYCO_DIR . '/assets/js/accordion.js';
    $accordion_ver  = file_exists($accordion_file) ? filemtime($accordion_file) : MYCO_VERSION;
    $newsletter_file = MYCO_DIR . '/assets/js/newsletter.js';
    $newsletter_ver  = file_exists($newsletter_file) ? filemtime($newsletter_file) : MYCO_VERSION;
    $mcyc_file       = MYCO_DIR . '/assets/js/mcyc.js';
    $mcyc_ver        = file_exists($mcyc_file) ? filemtime($mcyc_file) : MYCO_VERSION;

    // Mobile navigation (all pages)
    wp_enqueue_script(
        'myco-navigation',
        MYCO_URI . '/assets/js/navigation.js',
        [],
        $navigation_ver,
        true
    );

    // Homepage: Testimonials slider
    if (is_front_page()) {
        wp_enqueue_script(
            'myco-testimonials',
            MYCO_URI . '/assets/js/testimonials.js',
            [],
            $testimonials_ver,
            true
        );
    }

    // Gallery page: Filter + Lightbox
    if (is_page_template('page-templates/template-gallery.php')) {
        wp_enqueue_script(
            'myco-gallery',
            MYCO_URI . '/assets/js/gallery.js',
            [],
            $gallery_ver,
            true
        );
    }

    // Donate page: Stripe Payment Element + custom donate form
    if (is_page_template('page-templates/template-donate.php')) {
        wp_enqueue_script(
            'stripe-js',
            'https://js.stripe.com/v3/',
            [],
            null,
            true
        );
        wp_enqueue_script(
            'myco-donate',
            MYCO_URI . '/assets/js/donate.js',
            ['stripe-js'],
            $donate_ver,
            true
        );
        $stripeKeys = function_exists('myco_stripe_get_keys') ? myco_stripe_get_keys() : [];
        wp_localize_script('myco-donate', 'myco_donate', [
            'ajax_url'       => admin_url('admin-ajax.php'),
            'nonce'          => wp_create_nonce('myco_donate_nonce'),
            'stripe_key'     => $stripeKeys['publishable'] ?? get_option('myco_stripe_publishable_key', ''),
            'fee_percentage' => get_option('myco_donate_fee_percentage', 3.5),
            'return_url'     => myco_get_page_url('donate', '/donate/'),
        ]);
    }

    // Accordion (Privacy Policy, Contact FAQ)
    if (is_page_template('page-templates/template-privacy.php') || is_page_template('page-templates/template-contact.php')) {
        wp_enqueue_script(
            'myco-accordion',
            MYCO_URI . '/assets/js/accordion.js',
            [],
            $accordion_ver,
            true
        );
    }

    // Newsletter signup AJAX
    wp_enqueue_script(
        'myco-newsletter',
        MYCO_URI . '/assets/js/newsletter.js',
        [],
        $newsletter_ver,
        true
    );
    wp_localize_script('myco-newsletter', 'myco_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('myco_newsletter_nonce'),
    ]);

    if (is_page('mcyc')) {
        wp_register_script(
            'myco-mcyc-tailwind',
            'https://cdn.tailwindcss.com',
            [],
            null,
            false
        );
        wp_add_inline_script('myco-mcyc-tailwind', <<<'JS'
tailwind.config = {
  theme: {
    extend: {
      colors: {
        navy: "#141943",
        "navy-dark": "#0D1230",
        red: {
          DEFAULT: "#C8402E",
          hover: "#B03525"
        }
      },
      fontFamily: {
        inter: ["Inter", "sans-serif"]
      },
      boxShadow: {
        panel: "0 18px 52px rgba(20,25,67,0.10)",
        heavy: "0 28px 80px rgba(20,25,67,0.18)"
      }
    }
  }
};
JS, 'before');
        wp_enqueue_script('myco-mcyc-tailwind');

        wp_enqueue_script(
            'myco-mcyc',
            MYCO_URI . '/assets/js/mcyc.js',
            [],
            $mcyc_ver,
            true
        );
        wp_localize_script('myco-mcyc', 'myco_mcyc', [
            'donate_url'    => myco_get_page_url('donate', '/donate/'),
            'volunteer_url' => myco_get_page_url('volunteer', '/volunteer/'),
            'contact_url'   => myco_get_contact_page_url(),
            'gallery_url'   => myco_get_page_url('gallery', '/gallery/'),
            'fund'          => 'mcyc',
        ]);

        // Stripe integration for MCYC donation widget
        wp_enqueue_script(
            'stripe-js',
            'https://js.stripe.com/v3/',
            [],
            null,
            true
        );
        $donate_file = MYCO_DIR . '/assets/js/donate.js';
        $donate_ver  = file_exists($donate_file) ? filemtime($donate_file) : MYCO_VERSION;
        wp_enqueue_script(
            'myco-donate',
            MYCO_URI . '/assets/js/donate.js',
            ['stripe-js'],
            $donate_ver,
            true
        );
        $stripeKeys = function_exists('myco_stripe_get_keys') ? myco_stripe_get_keys() : [];
        wp_localize_script('myco-donate', 'myco_donate', [
            'ajax_url'       => admin_url('admin-ajax.php'),
            'nonce'          => wp_create_nonce('myco_donate_nonce'),
            'stripe_key'     => $stripeKeys['publishable'] ?? get_option('myco_stripe_publishable_key', ''),
            'fee_percentage' => get_option('myco_donate_fee_percentage', 3.5),
            'return_url'     => myco_get_page_url('mcyc', '/mcyc/'),
        ]);
    }
}

// Tailwind CDN fallback: inject script if compiled CSS doesn't exist
add_action('wp_head', 'myco_tailwind_cdn_fallback', 1);

function myco_tailwind_cdn_fallback() {
    if (is_page('mcyc')) {
        return;
    }

    $tailwind_file = MYCO_DIR . '/assets/css/tailwind-output.css';
    if (!file_exists($tailwind_file) || filesize($tailwind_file) === 0) {
        echo '<script src="https://cdn.tailwindcss.com"></script>' . "\n";
        echo '<script>
tailwind.config = {
  theme: {
    extend: {
      colors: {
        "navy": "#141943",
        "navy-dark": "#141943",
        "navy-mid": "#354968",
        "myco-red": "#C8402E",
        "red": {
          "DEFAULT": "#C8402E",
          "hover": "#b03525",
          "light": "#d94e3b"
        },
      },
      fontFamily: {
        inter: ["Inter", "sans-serif"],
      },
      boxShadow: {
        pill: "0 2px 12px 0 rgba(0,0,0,0.10)",
        "pill-sm": "0 1px 6px 0 rgba(0,0,0,0.08)",
        "btn-red": "0 4px 18px 0 rgba(200,64,46,0.35), 0 1px 4px 0 rgba(200,64,46,0.18)",
        "btn-red-hover": "0 6px 24px 0 rgba(200,64,46,0.45), 0 2px 6px 0 rgba(200,64,46,0.22)",
        hero: "0 8px 48px 0 rgba(20,25,67,0.10)",
      },
    }
  }
}
</script>' . "\n";
    }
}

// Preconnect for Google Fonts performance
add_filter('wp_resource_hints', 'myco_resource_hints', 10, 2);

function myco_resource_hints($urls, $relation_type) {
    if ($relation_type === 'preconnect') {
        $urls[] = [
            'href'        => 'https://fonts.googleapis.com',
            'crossorigin' => false,
        ];
        $urls[] = [
            'href'        => 'https://fonts.gstatic.com',
            'crossorigin' => 'anonymous',
        ];
    }
    return $urls;
}
