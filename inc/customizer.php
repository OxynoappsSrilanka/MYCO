<?php
/**
 * Customizer Settings — Expanded
 * Provides page-by-page brand controls accessible from Appearance → Customize.
 * No ACF Pro required for these settings.
 *
 * @package MYCO
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('customize_register', 'myco_customize_register');

function myco_customize_register(WP_Customize_Manager $wp_customize): void {

    // ─────────────────────────────────────────────────────────────────────────
    // PANEL: MYCO Theme
    // ─────────────────────────────────────────────────────────────────────────
    $wp_customize->add_panel('myco_theme', [
        'title'    => __('MYCO Theme Settings', 'myco'),
        'priority' => 30,
    ]);

    // ─────────────────────────────────────────────────────────────────────────
    // SECTION 1: Global Colors
    // ─────────────────────────────────────────────────────────────────────────
    $wp_customize->add_section('myco_colors', [
        'title'  => __('Global Colors', 'myco'),
        'panel'  => 'myco_theme',
        'priority' => 10,
    ]);

    $color_settings = [
        'myco_color_primary'    => ['label' => 'Primary Color (Ochre)',      'default' => '#F0A020'],
        'myco_color_teal'       => ['label' => 'Secondary Color (Teal)',     'default' => '#1A4A48'],
        'myco_color_dark'       => ['label' => 'Dark Heading Color',         'default' => '#141943'],
        'myco_color_red'        => ['label' => 'Accent Red (Labels/Dates)',  'default' => '#C8402E'],
        'myco_color_bg_light'   => ['label' => 'Light Section Background',   'default' => '#F3F4F6'],
        'myco_color_bg_dark'    => ['label' => 'Dark Section Background',    'default' => '#141943'],
    ];

    foreach ($color_settings as $key => $data) {
        $wp_customize->add_setting($key, [
            'default'           => $data['default'],
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $key, [
            'label'   => __($data['label'], 'myco'),
            'section' => 'myco_colors',
        ]));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // SECTION 2: Typography
    // ─────────────────────────────────────────────────────────────────────────
    $wp_customize->add_section('myco_typography', [
        'title'    => __('Typography', 'myco'),
        'panel'    => 'myco_theme',
        'priority' => 20,
    ]);

    // Heading font
    $wp_customize->add_setting('myco_font_heading', [
        'default'           => 'Instrument Serif',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('myco_font_heading', [
        'label'       => __('Heading Font (Google Fonts name)', 'myco'),
        'description' => __('e.g. "Instrument Serif", "Playfair Display", "Merriweather"', 'myco'),
        'section'     => 'myco_typography',
        'type'        => 'text',
    ]);

    // Body font
    $wp_customize->add_setting('myco_font_body', [
        'default'           => 'Sora',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('myco_font_body', [
        'label'       => __('Body Font (Google Fonts name)', 'myco'),
        'description' => __('e.g. "Sora", "Inter", "DM Sans"', 'myco'),
        'section'     => 'myco_typography',
        'type'        => 'text',
    ]);

    // Base font size
    $wp_customize->add_setting('myco_font_size_base', [
        'default'           => '16',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('myco_font_size_base', [
        'label'       => __('Base Font Size (px)', 'myco'),
        'section'     => 'myco_typography',
        'type'        => 'number',
        'input_attrs' => ['min' => 14, 'max' => 20, 'step' => 1],
    ]);

    // ─────────────────────────────────────────────────────────────────────────
    // SECTION 3: Homepage Hero
    // ─────────────────────────────────────────────────────────────────────────
    $wp_customize->add_section('myco_hero', [
        'title'    => __('Homepage Hero', 'myco'),
        'panel'    => 'myco_theme',
        'priority' => 30,
    ]);

    // Hero overlay opacity
    $wp_customize->add_setting('myco_hero_overlay_opacity', [
        'default'           => '50',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('myco_hero_overlay_opacity', [
        'label'       => __('Hero Overlay Opacity (%)', 'myco'),
        'description' => __('Dark overlay on the hero background image. 0 = transparent, 100 = fully dark.', 'myco'),
        'section'     => 'myco_hero',
        'type'        => 'range',
        'input_attrs' => ['min' => 0, 'max' => 100, 'step' => 5],
    ]);

    // Hero CTA button style
    $wp_customize->add_setting('myco_hero_btn_style', [
        'default'           => 'filled',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('myco_hero_btn_style', [
        'label'   => __('Primary CTA Button Style', 'myco'),
        'section' => 'myco_hero',
        'type'    => 'radio',
        'choices' => [
            'filled'   => __('Filled (solid background)', 'myco'),
            'outline'  => __('Outline (transparent with border)', 'myco'),
        ],
    ]);

    // ─────────────────────────────────────────────────────────────────────────
    // SECTION 4: Navigation
    // ─────────────────────────────────────────────────────────────────────────
    $wp_customize->add_section('myco_nav_settings', [
        'title'    => __('Navigation Bar', 'myco'),
        'panel'    => 'myco_theme',
        'priority' => 40,
    ]);

    // Sticky nav
    $wp_customize->add_setting('myco_nav_sticky', [
        'default'           => '1',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('myco_nav_sticky', [
        'label'   => __('Sticky Navigation (scrolls with page)', 'myco'),
        'section' => 'myco_nav_settings',
        'type'    => 'checkbox',
    ]);

    // Nav background style
    $wp_customize->add_setting('myco_nav_bg_style', [
        'default'           => 'white',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('myco_nav_bg_style', [
        'label'   => __('Navigation Background', 'myco'),
        'section' => 'myco_nav_settings',
        'type'    => 'select',
        'choices' => [
            'white'       => __('White / Light', 'myco'),
            'transparent' => __('Transparent (over hero)', 'myco'),
            'dark'        => __('Dark (matching footer)', 'myco'),
        ],
    ]);

    // Donate button label in nav
    $wp_customize->add_setting('myco_nav_donate_label', [
        'default'           => 'Donate Now',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('myco_nav_donate_label', [
        'label'   => __('Donate Button Text in Nav', 'myco'),
        'section' => 'myco_nav_settings',
        'type'    => 'text',
    ]);

    // ─────────────────────────────────────────────────────────────────────────
    // SECTION 5: Donate Page
    // ─────────────────────────────────────────────────────────────────────────
    $wp_customize->add_section('myco_donate_page', [
        'title'    => __('Donate Page', 'myco'),
        'panel'    => 'myco_theme',
        'priority' => 50,
    ]);

    // Preset amounts
    $wp_customize->add_setting('myco_donate_preset_amounts', [
        'default'           => '25,50,100,250,500',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('myco_donate_preset_amounts', [
        'label'       => __('Preset Donation Amounts', 'myco'),
        'description' => __('Comma-separated values in USD. e.g. 25,50,100,250,500', 'myco'),
        'section'     => 'myco_donate_page',
        'type'        => 'text',
    ]);

    // Default fund
    $wp_customize->add_setting('myco_donate_default_fund', [
        'default'           => 'general',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('myco_donate_default_fund', [
        'label'   => __('Default Selected Fund', 'myco'),
        'section' => 'myco_donate_page',
        'type'    => 'select',
        'choices' => [
            'general'      => __('General Fund', 'myco'),
            'youth'        => __('Youth Programs', 'myco'),
            'athletics'    => __('Athletics', 'myco'),
            'scholarships' => __('Scholarships', 'myco'),
            'mcyc'         => __('MCYC Building Fund', 'myco'),
        ],
    ]);

    // Cover fees checkbox — default on/off
    $wp_customize->add_setting('myco_donate_cover_fees_default', [
        'default'           => '1',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('myco_donate_cover_fees_default', [
        'label'       => __('Cover Processing Fees — checked by default', 'myco'),
        'description' => __('If enabled, the "Cover fees" checkbox starts pre-checked on the donate form.', 'myco'),
        'section'     => 'myco_donate_page',
        'type'        => 'checkbox',
    ]);

    // Organization EIN (for receipts)
    $wp_customize->add_setting('myco_ein', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('myco_ein', [
        'label'       => __('Organization EIN', 'myco'),
        'description' => __('Your 501(c)(3) EIN number. Displayed on donation receipts.', 'myco'),
        'section'     => 'myco_donate_page',
        'type'        => 'text',
    ]);

    // ─────────────────────────────────────────────────────────────────────────
    // SECTION 6: Footer
    // ─────────────────────────────────────────────────────────────────────────
    $wp_customize->add_section('myco_footer_settings', [
        'title'    => __('Footer', 'myco'),
        'panel'    => 'myco_theme',
        'priority' => 60,
    ]);

    // Footer style
    $wp_customize->add_setting('myco_footer_style', [
        'default'           => 'dark',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('myco_footer_style', [
        'label'   => __('Footer Style', 'myco'),
        'section' => 'myco_footer_settings',
        'type'    => 'radio',
        'choices' => [
            'dark'  => __('Dark (default)', 'myco'),
            'light' => __('Light', 'myco'),
        ],
    ]);

    // Footer tagline
    $wp_customize->add_setting('myco_footer_tagline', [
        'default'           => 'Building bridges, empowering youth.',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('myco_footer_tagline', [
        'label'   => __('Footer Tagline', 'myco'),
        'section' => 'myco_footer_settings',
        'type'    => 'text',
    ]);

    // Footer copyright text
    $wp_customize->add_setting('myco_footer_copyright', [
        'default'           => '© ' . date('Y') . ' MYCO. All rights reserved.',
        'sanitize_callback' => 'wp_kses_post',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('myco_footer_copyright', [
        'label'   => __('Copyright Text', 'myco'),
        'section' => 'myco_footer_settings',
        'type'    => 'textarea',
    ]);

    // Show newsletter signup in footer
    $wp_customize->add_setting('myco_footer_show_newsletter', [
        'default'           => '1',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control('myco_footer_show_newsletter', [
        'label'   => __('Show Newsletter Signup in Footer', 'myco'),
        'section' => 'myco_footer_settings',
        'type'    => 'checkbox',
    ]);

    // ─────────────────────────────────────────────────────────────────────────
    // SECTION 7: Social Media (existing, moved into panel)
    // ─────────────────────────────────────────────────────────────────────────
    $wp_customize->add_section('myco_social', [
        'title'    => __('Social Media Links', 'myco'),
        'panel'    => 'myco_theme',
        'priority' => 70,
    ]);

    $social_platforms = [
        'myco_facebook_url'  => ['label' => 'Facebook URL',   'placeholder' => 'https://facebook.com/myco'],
        'myco_twitter_url'   => ['label' => 'Twitter / X URL', 'placeholder' => 'https://x.com/myco'],
        'myco_instagram_url' => ['label' => 'Instagram URL',   'placeholder' => 'https://instagram.com/myco'],
        'myco_youtube_url'   => ['label' => 'YouTube URL',     'placeholder' => 'https://youtube.com/@myco'],
        'myco_tiktok_url'    => ['label' => 'TikTok URL',      'placeholder' => 'https://tiktok.com/@myco'],
        'myco_linkedin_url'  => ['label' => 'LinkedIn URL',    'placeholder' => 'https://linkedin.com/company/myco'],
    ];

    foreach ($social_platforms as $key => $data) {
        $wp_customize->add_setting($key, [
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ]);
        $wp_customize->add_control($key, [
            'label'       => __($data['label'], 'myco'),
            'section'     => 'myco_social',
            'type'        => 'url',
            'input_attrs' => ['placeholder' => $data['placeholder']],
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // SECTION 8: Logos
    // ─────────────────────────────────────────────────────────────────────────
    $wp_customize->add_section('myco_logos', [
        'title'    => __('Additional Logos', 'myco'),
        'panel'    => 'myco_theme',
        'priority' => 80,
    ]);

    $wp_customize->add_setting('myco_logo_white', [
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'myco_logo_white', [
        'label'     => __('White Logo (for Dark Background)', 'myco'),
        'section'   => 'myco_logos',
        'mime_type' => 'image',
    ]));

    $wp_customize->add_setting('myco_favicon', [
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ]);
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'myco_favicon', [
        'label'     => __('Custom Favicon', 'myco'),
        'section'   => 'myco_logos',
        'mime_type' => 'image',
    ]));
}

// ─────────────────────────────────────────────────────────────────────────────
// OUTPUT CUSTOMIZER CSS — inlined into <head>
// ─────────────────────────────────────────────────────────────────────────────

add_action('wp_head', 'myco_customizer_css');

function myco_customizer_css(): void {
    $primary    = get_theme_mod('myco_color_primary', '#F0A020');
    $teal       = get_theme_mod('myco_color_teal', '#1A4A48');
    $dark       = get_theme_mod('myco_color_dark', '#141943');
    $red        = get_theme_mod('myco_color_red', '#C8402E');
    $bg_light   = get_theme_mod('myco_color_bg_light', '#F3F4F6');
    $bg_dark    = get_theme_mod('myco_color_bg_dark', '#141943');
    $font_body  = get_theme_mod('myco_font_body', 'Sora');
    $font_head  = get_theme_mod('myco_font_heading', 'Instrument Serif');
    $font_size  = absint(get_theme_mod('myco_font_size_base', '16'));
    $overlay    = absint(get_theme_mod('myco_hero_overlay_opacity', '50'));
    $overlay_dec = $overlay / 100;

    $css = "
    :root {
        --myco-primary: {$primary};
        --myco-teal: {$teal};
        --myco-dark: {$dark};
        --myco-red: {$red};
        --myco-bg-light: {$bg_light};
        --myco-bg-dark: {$bg_dark};
        --myco-font-body: '{$font_body}', sans-serif;
        --myco-font-heading: '{$font_head}', serif;
        --myco-font-size-base: {$font_size}px;
        --myco-hero-overlay: {$overlay_dec};
    }
    body { font-family: var(--myco-font-body); font-size: var(--myco-font-size-base); }
    h1, h2, h3 { font-family: var(--myco-font-heading); }
    .site-hero::before { opacity: var(--myco-hero-overlay); }
    ";

    // Nav donate button color
    $donate_label = sanitize_text_field(get_theme_mod('myco_nav_donate_label', 'Donate Now'));
    $nav_bg = sanitize_text_field(get_theme_mod('myco_nav_bg_style', 'white'));

    if ($nav_bg === 'dark') {
        $css .= '.site-header { background: var(--myco-bg-dark); }';
    } elseif ($nav_bg === 'transparent') {
        $css .= '.site-header { background: transparent; position: absolute; width: 100%; }';
    }

    echo '<style id="myco-customizer-css">' . wp_strip_all_tags($css) . '</style>' . "\n";

    // Dynamic Google Fonts
    $fonts = array_unique(array_filter([
        sanitize_text_field($font_head),
        sanitize_text_field($font_body),
    ]));
    if (!empty($fonts)) {
        $font_query = implode('&family=', array_map(fn($f) => urlencode($f) . ':wght@400;600;700;800;900', $fonts));
        echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
        echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
        echo '<link href="https://fonts.googleapis.com/css2?family=' . esc_attr($font_query) . '&display=swap" rel="stylesheet">' . "\n";
    }
}

// Store EIN in wp_options so receipt-handler.php can read it
add_action('customize_save_after', function (WP_Customize_Manager $manager): void {
    $ein = $manager->get_setting('myco_ein')->value();
    if ($ein !== null) {
        update_option('myco_ein', sanitize_text_field($ein));
    }
});
