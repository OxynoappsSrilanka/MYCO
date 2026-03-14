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
    if (file_exists($tailwind_file) && filesize($tailwind_file) > 0) {
        wp_enqueue_style(
            'myco-tailwind',
            MYCO_URI . '/assets/css/tailwind-output.css',
            ['google-fonts-inter'],
            MYCO_VERSION
        );
    }

    // Custom shared styles (pill-nav, buttons, containers, footers)
    wp_enqueue_style(
        'myco-custom',
        MYCO_URI . '/assets/css/custom.css',
        ['google-fonts-inter'],
        MYCO_VERSION
    );

    // Typography fix - Remove chromatic aberration effects
    wp_enqueue_style(
        'myco-typography-fix',
        MYCO_URI . '/assets/css/typography-fix.css',
        ['myco-custom'],
        MYCO_VERSION
    );

    // Page-specific styles (conditional loading)
    if (is_page_template('page-templates/template-donate.php')) {
        wp_enqueue_style('myco-donate', MYCO_URI . '/assets/css/donate.css', ['myco-custom'], MYCO_VERSION);
    }

    if (is_page_template('page-templates/template-gallery.php')) {
        wp_enqueue_style('myco-gallery', MYCO_URI . '/assets/css/gallery.css', ['myco-custom'], MYCO_VERSION);
    }

    if (is_page_template('page-templates/template-privacy.php') || is_page_template('page-templates/template-contact.php')) {
        wp_enqueue_style('myco-accordion', MYCO_URI . '/assets/css/accordion.css', ['myco-custom'], MYCO_VERSION);
    }
}

function myco_enqueue_scripts() {
    // Mobile navigation (all pages)
    wp_enqueue_script(
        'myco-navigation',
        MYCO_URI . '/assets/js/navigation.js',
        [],
        MYCO_VERSION,
        true
    );

    // Homepage: Testimonials slider
    if (is_front_page()) {
        wp_enqueue_script(
            'myco-testimonials',
            MYCO_URI . '/assets/js/testimonials.js',
            [],
            MYCO_VERSION,
            true
        );
    }

    // Gallery page: Filter + Lightbox
    if (is_page_template('page-templates/template-gallery.php')) {
        wp_enqueue_script(
            'myco-gallery',
            MYCO_URI . '/assets/js/gallery.js',
            [],
            MYCO_VERSION,
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
            MYCO_VERSION,
            true
        );
        $stripeKeys = function_exists('myco_stripe_get_keys') ? myco_stripe_get_keys() : [];
        wp_localize_script('myco-donate', 'myco_donate', [
            'ajax_url'       => admin_url('admin-ajax.php'),
            'nonce'          => wp_create_nonce('myco_donate_nonce'),
            'stripe_key'     => $stripeKeys['publishable'] ?? get_option('myco_stripe_publishable_key', ''),
            'fee_percentage' => get_option('myco_donate_fee_percentage', 3.5),
            'return_url'     => get_permalink(get_page_by_path('donate')) ?: home_url('/donate/'),
        ]);
    }

    // Accordion (Privacy Policy, Contact FAQ)
    if (is_page_template('page-templates/template-privacy.php') || is_page_template('page-templates/template-contact.php')) {
        wp_enqueue_script(
            'myco-accordion',
            MYCO_URI . '/assets/js/accordion.js',
            [],
            MYCO_VERSION,
            true
        );
    }

    // Newsletter signup AJAX
    wp_enqueue_script(
        'myco-newsletter',
        MYCO_URI . '/assets/js/newsletter.js',
        [],
        MYCO_VERSION,
        true
    );
    wp_localize_script('myco-newsletter', 'myco_ajax', [
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('myco_newsletter_nonce'),
    ]);
}

// Tailwind CDN fallback: inject script if compiled CSS doesn't exist
add_action('wp_head', 'myco_tailwind_cdn_fallback', 1);

function myco_tailwind_cdn_fallback() {
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
