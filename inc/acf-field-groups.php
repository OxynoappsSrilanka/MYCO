<?php
/**
 * ACF Field Groups Configuration
 *
 * Field groups are managed via ACF GUI and synced to acf-json/ directory.
 * This file sets up the JSON save/load paths.
 *
 * @package MYCO
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Set ACF JSON save point
 */
add_filter('acf/settings/save_json', 'myco_acf_json_save_point');

function myco_acf_json_save_point($path) {
    return MYCO_DIR . '/acf-json';
}

/**
 * Set ACF JSON load point
 */
add_filter('acf/settings/load_json', 'myco_acf_json_load_point');

function myco_acf_json_load_point($paths) {
    unset($paths[0]); // Remove default
    $paths[] = MYCO_DIR . '/acf-json';
    return $paths;
}

/**
 * Register ACF field groups programmatically
 * These define all the admin editing fields for the theme.
 */
add_action('acf/include_fields', 'myco_register_acf_fields');

function myco_register_acf_fields() {
    if (!function_exists('acf_add_local_field_group')) {
        return;
    }

    // ─── GENERAL SETTINGS (Options Page) ────────────────────────
    acf_add_local_field_group([
        'key'      => 'group_myco_general',
        'title'    => 'General Settings',
        'fields'   => [
            [
                'key'   => 'field_site_tagline',
                'label' => 'Site Tagline',
                'name'  => 'site_tagline',
                'type'  => 'text',
                'default_value' => 'Moving the Community Forward',
            ],
            [
                'key'   => 'field_footer_description',
                'label' => 'Footer Description',
                'name'  => 'footer_description',
                'type'  => 'textarea',
                'rows'  => 3,
                'default_value' => 'Empowering Muslim Youth of Central Ohio through education, leadership, and community service. Building a brighter future together.',
            ],
            [
                'key'   => 'field_org_address',
                'label' => 'Address',
                'name'  => 'org_address',
                'type'  => 'text',
                'default_value' => '123 MYCO Way, Columbus, OH 43210',
            ],
            [
                'key'   => 'field_org_email',
                'label' => 'Email',
                'name'  => 'org_email',
                'type'  => 'email',
                'default_value' => 'info@myco.org',
            ],
            [
                'key'   => 'field_org_phone',
                'label' => 'Phone',
                'name'  => 'org_phone',
                'type'  => 'text',
                'default_value' => '(614) 555-0123',
            ],
            [
                'key'   => 'field_social_facebook',
                'label' => 'Facebook URL',
                'name'  => 'social_facebook',
                'type'  => 'url',
            ],
            [
                'key'   => 'field_social_twitter',
                'label' => 'Twitter / X URL',
                'name'  => 'social_twitter',
                'type'  => 'url',
            ],
            [
                'key'   => 'field_social_instagram',
                'label' => 'Instagram URL',
                'name'  => 'social_instagram',
                'type'  => 'url',
            ],
            [
                'key'   => 'field_tax_id',
                'label' => 'Tax ID (EIN)',
                'name'  => 'tax_id',
                'type'  => 'text',
                'instructions' => 'Displayed on donation pages',
            ],
            [
                'key'   => 'field_copyright_text',
                'label' => 'Copyright Text',
                'name'  => 'copyright_text',
                'type'  => 'text',
                'default_value' => '2026 MYCO. All rights reserved.',
            ],
            [
                'key'        => 'field_office_hours',
                'label'      => 'Office Hours',
                'name'       => 'office_hours',
                'type'       => 'repeater',
                'layout'     => 'table',
                'sub_fields' => [
                    [
                        'key'   => 'field_oh_day',
                        'label' => 'Day',
                        'name'  => 'day',
                        'type'  => 'text',
                    ],
                    [
                        'key'   => 'field_oh_hours',
                        'label' => 'Hours',
                        'name'  => 'hours',
                        'type'  => 'text',
                    ],
                ],
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'options_page',
                    'operator' => '==',
                    'value'    => 'myco-general',
                ],
            ],
        ],
    ]);

    // ─── DONATION SETTINGS (Options Page) ────────────────────────
    acf_add_local_field_group([
        'key'      => 'group_myco_donations',
        'title'    => 'Donation Settings',
        'fields'   => [
            [
                'key'          => 'field_stripe_publishable_key',
                'label'        => 'Stripe Publishable Key',
                'name'         => 'stripe_publishable_key',
                'type'         => 'text',
                'instructions' => 'Your Stripe publishable key (pk_live_... or pk_test_...)',
            ],
            [
                'key'          => 'field_stripe_secret_key',
                'label'        => 'Stripe Secret Key',
                'name'         => 'stripe_secret_key',
                'type'         => 'text',
                'instructions' => 'Your Stripe secret key (sk_live_... or sk_test_...). This is stored securely.',
            ],
            [
                'key'          => 'field_stripe_webhook_secret',
                'label'        => 'Stripe Webhook Secret',
                'name'         => 'stripe_webhook_secret',
                'type'         => 'text',
                'instructions' => 'Webhook signing secret (whsec_...)',
            ],
            [
                'key'        => 'field_donation_funds',
                'label'      => 'Donation Funds',
                'name'       => 'donation_funds',
                'type'       => 'repeater',
                'layout'     => 'table',
                'sub_fields' => [
                    [
                        'key'   => 'field_fund_name',
                        'label' => 'Fund Name',
                        'name'  => 'fund_name',
                        'type'  => 'text',
                    ],
                    [
                        'key'   => 'field_fund_slug',
                        'label' => 'Fund Slug',
                        'name'  => 'fund_slug',
                        'type'  => 'text',
                    ],
                ],
            ],
            [
                'key'        => 'field_preset_amounts',
                'label'      => 'Preset Donation Amounts',
                'name'       => 'preset_amounts',
                'type'       => 'repeater',
                'layout'     => 'table',
                'sub_fields' => [
                    [
                        'key'   => 'field_amount',
                        'label' => 'Amount ($)',
                        'name'  => 'amount',
                        'type'  => 'number',
                    ],
                ],
            ],
            [
                'key'           => 'field_processing_fee_pct',
                'label'         => 'Processing Fee Percentage',
                'name'          => 'processing_fee_pct',
                'type'          => 'number',
                'default_value' => 3.5,
                'step'          => 0.1,
                'instructions'  => 'Percentage to add when donors opt to cover processing fees',
            ],
        ],
        'location' => [
            [
                [
                    'param'    => 'options_page',
                    'operator' => '==',
                    'value'    => 'myco-donations',
                ],
            ],
        ],
    ]);

    // ─── HOMEPAGE FIELDS ────────────────────────────────────────
    acf_add_local_field_group([
        'key'      => 'group_myco_homepage',
        'title'    => 'Homepage Content',
        'fields'   => [
            // Hero Tab
            ['key' => 'field_hp_hero_tab', 'label' => 'Hero Section', 'name' => '', 'type' => 'tab'],
            ['key' => 'field_hp_hero_headline', 'label' => 'Hero Headline', 'name' => 'hero_headline', 'type' => 'textarea', 'rows' => 2, 'default_value' => "Moving The\nCommunity Forward"],
            ['key' => 'field_hp_hero_highlight', 'label' => 'Highlight Word (Red)', 'name' => 'hero_highlight_word', 'type' => 'text', 'default_value' => 'Future', 'instructions' => 'This word will appear in red in the headline'],
            ['key' => 'field_hp_hero_paragraph', 'label' => 'Hero Paragraph', 'name' => 'hero_paragraph', 'type' => 'textarea', 'rows' => 3],
            ['key' => 'field_hp_hero_cta1_text', 'label' => 'Primary CTA Text', 'name' => 'hero_cta_primary_text', 'type' => 'text', 'default_value' => 'Donate Today'],
            ['key' => 'field_hp_hero_cta1_url', 'label' => 'Primary CTA URL', 'name' => 'hero_cta_primary_url', 'type' => 'url'],
            ['key' => 'field_hp_hero_cta2_text', 'label' => 'Secondary CTA Text', 'name' => 'hero_cta_secondary_text', 'type' => 'text', 'default_value' => 'Explore Programs'],
            ['key' => 'field_hp_hero_cta2_url', 'label' => 'Secondary CTA URL', 'name' => 'hero_cta_secondary_url', 'type' => 'url'],
            ['key' => 'field_hp_hero_image', 'label' => 'Hero Image', 'name' => 'hero_image', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'medium'],

            // Story Tab
            ['key' => 'field_hp_story_tab', 'label' => 'Discover Our Story', 'name' => '', 'type' => 'tab'],
            ['key' => 'field_hp_story_heading', 'label' => 'Story Heading', 'name' => 'story_heading', 'type' => 'textarea', 'rows' => 2],
            ['key' => 'field_hp_story_paragraph', 'label' => 'Story Text', 'name' => 'story_paragraph', 'type' => 'textarea', 'rows' => 4],
            ['key' => 'field_hp_story_image', 'label' => 'Story Image', 'name' => 'story_image', 'type' => 'image', 'return_format' => 'array'],
            [
                'key' => 'field_hp_story_stats', 'label' => 'Story Stats', 'name' => 'story_stats', 'type' => 'repeater', 'layout' => 'table', 'max' => 4,
                'sub_fields' => [
                    ['key' => 'field_hp_stat_number', 'label' => 'Number', 'name' => 'number', 'type' => 'text'],
                    ['key' => 'field_hp_stat_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text'],
                ],
            ],

            // Campaign Tab
            ['key' => 'field_hp_campaign_tab', 'label' => 'Campaign CTA', 'name' => '', 'type' => 'tab'],
            ['key' => 'field_hp_campaign_heading', 'label' => 'Campaign Heading', 'name' => 'campaign_heading', 'type' => 'textarea', 'rows' => 2],
            ['key' => 'field_hp_campaign_paragraph', 'label' => 'Campaign Text', 'name' => 'campaign_paragraph', 'type' => 'textarea', 'rows' => 3],
            ['key' => 'field_hp_campaign_cta_text', 'label' => 'Campaign CTA Text', 'name' => 'campaign_cta_text', 'type' => 'text'],
            ['key' => 'field_hp_campaign_cta_url', 'label' => 'Campaign CTA URL', 'name' => 'campaign_cta_url', 'type' => 'url'],
            ['key' => 'field_hp_campaign_bg', 'label' => 'Campaign Background Image', 'name' => 'campaign_background_image', 'type' => 'image', 'return_format' => 'array'],

            // Programs Tab
            ['key' => 'field_hp_programs_tab', 'label' => 'Programs Section', 'name' => '', 'type' => 'tab'],
            ['key' => 'field_hp_programs_label', 'label' => 'Section Label', 'name' => 'programs_section_label', 'type' => 'text', 'default_value' => 'PROGRAMS & SUPPORT'],
            ['key' => 'field_hp_programs_heading', 'label' => 'Section Heading', 'name' => 'programs_section_heading', 'type' => 'textarea', 'rows' => 2],

            // Outcomes Tab
            ['key' => 'field_hp_outcomes_tab', 'label' => 'Outcomes Section', 'name' => '', 'type' => 'tab'],
            ['key' => 'field_hp_outcomes_label', 'label' => 'Section Label', 'name' => 'outcomes_label', 'type' => 'text'],
            ['key' => 'field_hp_outcomes_heading', 'label' => 'Heading', 'name' => 'outcomes_heading', 'type' => 'textarea', 'rows' => 2],
            ['key' => 'field_hp_outcomes_paragraph', 'label' => 'Text', 'name' => 'outcomes_paragraph', 'type' => 'textarea'],
            ['key' => 'field_hp_outcomes_cta_text', 'label' => 'CTA Text', 'name' => 'outcomes_cta_text', 'type' => 'text'],
            ['key' => 'field_hp_outcomes_cta_url', 'label' => 'CTA URL', 'name' => 'outcomes_cta_url', 'type' => 'url'],
            ['key' => 'field_hp_outcomes_bg', 'label' => 'Background Image', 'name' => 'outcomes_background_image', 'type' => 'image', 'return_format' => 'array'],

            // Testimonials Tab
            ['key' => 'field_hp_testi_tab', 'label' => 'Testimonials', 'name' => '', 'type' => 'tab'],
            ['key' => 'field_hp_testi_label', 'label' => 'Section Label', 'name' => 'testimonials_label', 'type' => 'text', 'default_value' => 'TESTIMONIALS'],
            ['key' => 'field_hp_testi_heading', 'label' => 'Section Heading', 'name' => 'testimonials_heading', 'type' => 'text', 'default_value' => 'What Our Community Says'],

            // Events Tab
            ['key' => 'field_hp_events_tab', 'label' => 'Events Section', 'name' => '', 'type' => 'tab'],
            ['key' => 'field_hp_events_label', 'label' => 'Section Label', 'name' => 'events_section_label', 'type' => 'text', 'default_value' => 'UPCOMING EVENTS'],
            ['key' => 'field_hp_events_heading', 'label' => 'Section Heading', 'name' => 'events_section_heading', 'type' => 'textarea', 'rows' => 2],

            // Volunteer CTA Tab
            ['key' => 'field_hp_vol_tab', 'label' => 'Volunteer CTA', 'name' => '', 'type' => 'tab'],
            ['key' => 'field_hp_vol_heading', 'label' => 'Heading', 'name' => 'volunteer_heading', 'type' => 'textarea', 'rows' => 2],
            ['key' => 'field_hp_vol_cta_text', 'label' => 'CTA Text', 'name' => 'volunteer_cta_text', 'type' => 'text'],
            ['key' => 'field_hp_vol_cta_url', 'label' => 'CTA URL', 'name' => 'volunteer_cta_url', 'type' => 'url'],
            ['key' => 'field_hp_vol_image', 'label' => 'Image', 'name' => 'volunteer_image', 'type' => 'image', 'return_format' => 'array'],
        ],
        'location' => [
            [
                [
                    'param'    => 'page_type',
                    'operator' => '==',
                    'value'    => 'front_page',
                ],
            ],
        ],
    ]);

    // ─── EVENT CPT FIELDS ──────────────────────────────────────
    acf_add_local_field_group([
        'key'      => 'group_myco_event',
        'title'    => 'Event Details',
        'fields'   => [
            ['key' => 'field_event_date', 'label' => 'Event Date', 'name' => 'event_date', 'type' => 'date_picker', 'display_format' => 'F j, Y', 'return_format' => 'Y-m-d', 'required' => 1],
            ['key' => 'field_event_start_time', 'label' => 'Start Time', 'name' => 'event_start_time', 'type' => 'time_picker', 'display_format' => 'g:i A', 'return_format' => 'g:i A'],
            ['key' => 'field_event_end_time', 'label' => 'End Time', 'name' => 'event_end_time', 'type' => 'time_picker', 'display_format' => 'g:i A', 'return_format' => 'g:i A'],
            ['key' => 'field_event_location_name', 'label' => 'Location Name', 'name' => 'event_location_name', 'type' => 'text', 'default_value' => 'MYCO Community Space'],
            ['key' => 'field_event_location_address', 'label' => 'Location Address', 'name' => 'event_location_address', 'type' => 'textarea', 'rows' => 2],
            ['key' => 'field_event_maps_url', 'label' => 'Google Maps URL', 'name' => 'event_maps_url', 'type' => 'url'],
            ['key' => 'field_event_age_group', 'label' => 'Age Group', 'name' => 'event_age_group', 'type' => 'text'],
            ['key' => 'field_event_cost', 'label' => 'Cost', 'name' => 'event_cost', 'type' => 'text', 'default_value' => 'Free'],
            ['key' => 'field_event_cost_note', 'label' => 'Cost Note', 'name' => 'event_cost_note', 'type' => 'text'],
            ['key' => 'field_event_registration_url', 'label' => 'Registration URL', 'name' => 'event_registration_url', 'type' => 'url'],
            ['key' => 'field_event_coordinator_name', 'label' => 'Coordinator Name', 'name' => 'event_coordinator_name', 'type' => 'text'],
            ['key' => 'field_event_coordinator_title', 'label' => 'Coordinator Title', 'name' => 'event_coordinator_title', 'type' => 'text'],
            ['key' => 'field_event_coordinator_email', 'label' => 'Coordinator Email', 'name' => 'event_coordinator_email', 'type' => 'email'],
            ['key' => 'field_event_coordinator_phone', 'label' => 'Coordinator Phone', 'name' => 'event_coordinator_phone', 'type' => 'text'],
            [
                'key' => 'field_event_what_to_expect', 'label' => 'What to Expect', 'name' => 'what_to_expect', 'type' => 'repeater', 'layout' => 'block',
                'sub_fields' => [
                    ['key' => 'field_wte_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'],
                    ['key' => 'field_wte_desc', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 2],
                ],
            ],
            [
                'key' => 'field_event_what_to_bring', 'label' => 'What to Bring', 'name' => 'what_to_bring', 'type' => 'repeater', 'layout' => 'table',
                'sub_fields' => [
                    ['key' => 'field_wtb_item', 'label' => 'Item', 'name' => 'item', 'type' => 'text'],
                ],
            ],
        ],
        'location' => [
            [['param' => 'post_type', 'operator' => '==', 'value' => 'event']],
        ],
    ]);

    // ─── PROGRAM CPT FIELDS ────────────────────────────────────
    acf_add_local_field_group([
        'key'      => 'group_myco_program',
        'title'    => 'Program Details',
        'fields'   => [
            ['key' => 'field_prog_schedule', 'label' => 'Schedule', 'name' => 'program_schedule', 'type' => 'text'],
            ['key' => 'field_prog_age', 'label' => 'Age Group', 'name' => 'program_age_group', 'type' => 'text'],
            ['key' => 'field_prog_location', 'label' => 'Location', 'name' => 'program_location', 'type' => 'text'],
            ['key' => 'field_prog_fee', 'label' => 'Fee', 'name' => 'program_fee', 'type' => 'text', 'default_value' => 'Free'],
            [
                'key' => 'field_prog_features', 'label' => 'Program Features', 'name' => 'program_features', 'type' => 'repeater', 'layout' => 'block',
                'sub_fields' => [
                    ['key' => 'field_pf_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'],
                    ['key' => 'field_pf_desc', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 2],
                ],
            ],
            ['key' => 'field_prog_gallery', 'label' => 'Program Gallery', 'name' => 'program_gallery', 'type' => 'gallery', 'return_format' => 'array'],
            ['key' => 'field_prog_related', 'label' => 'Related Programs', 'name' => 'related_programs', 'type' => 'relationship', 'post_type' => ['program'], 'max' => 3],
        ],
        'location' => [
            [['param' => 'post_type', 'operator' => '==', 'value' => 'program']],
        ],
    ]);

    // ─── NEWS ARTICLE CPT FIELDS ───────────────────────────────
    acf_add_local_field_group([
        'key'      => 'group_myco_news',
        'title'    => 'Article Details',
        'fields'   => [
            ['key' => 'field_news_read_time', 'label' => 'Read Time', 'name' => 'article_read_time', 'type' => 'text', 'default_value' => '5 min read'],
            ['key' => 'field_news_author_name', 'label' => 'Author Display Name', 'name' => 'article_author_name', 'type' => 'text'],
            ['key' => 'field_news_author_title', 'label' => 'Author Title', 'name' => 'article_author_title', 'type' => 'text'],
            ['key' => 'field_news_author_photo', 'label' => 'Author Photo', 'name' => 'article_author_photo', 'type' => 'image', 'return_format' => 'array', 'preview_size' => 'thumbnail'],
            ['key' => 'field_news_featured', 'label' => 'Featured Article', 'name' => 'featured_article', 'type' => 'true_false', 'default_value' => 0, 'ui' => 1, 'instructions' => 'Show as featured on the news listing page'],
        ],
        'location' => [
            [['param' => 'post_type', 'operator' => '==', 'value' => 'news_article']],
        ],
    ]);

    // ─── TESTIMONIAL CPT FIELDS ────────────────────────────────
    acf_add_local_field_group([
        'key'      => 'group_myco_testimonial',
        'title'    => 'Testimonial Details',
        'fields'   => [
            ['key' => 'field_testi_quote', 'label' => 'Quote', 'name' => 'testimonial_quote', 'type' => 'textarea', 'rows' => 4, 'required' => 1],
            ['key' => 'field_testi_name', 'label' => 'Author Name', 'name' => 'testimonial_author_name', 'type' => 'text', 'required' => 1],
            ['key' => 'field_testi_role', 'label' => 'Author Role', 'name' => 'testimonial_author_role', 'type' => 'text', 'instructions' => 'e.g. Parent, Youth Participant, Volunteer'],
            ['key' => 'field_testi_rating', 'label' => 'Rating (1-5)', 'name' => 'testimonial_rating', 'type' => 'number', 'min' => 1, 'max' => 5, 'default_value' => 5],
        ],
        'location' => [
            [['param' => 'post_type', 'operator' => '==', 'value' => 'testimonial']],
        ],
    ]);

    // ─── GALLERY PHOTO CPT FIELDS ──────────────────────────────
    acf_add_local_field_group([
        'key'      => 'group_myco_gallery_photo',
        'title'    => 'Photo Details',
        'fields'   => [
            ['key' => 'field_gallery_caption', 'label' => 'Caption', 'name' => 'photo_caption', 'type' => 'text'],
            ['key' => 'field_gallery_order', 'label' => 'Display Order', 'name' => 'sort_order', 'type' => 'number', 'default_value' => 0],
        ],
        'location' => [
            [['param' => 'post_type', 'operator' => '==', 'value' => 'gallery_photo']],
        ],
    ]);

    // ─── ABOUT PAGE FIELDS ─────────────────────────────────────
    acf_add_local_field_group([
        'key'      => 'group_myco_about',
        'title'    => 'About Page Content',
        'fields'   => [
            // Vision Tab
            ['key' => 'field_about_vision_tab', 'label' => 'Vision Section', 'name' => '', 'type' => 'tab'],
            ['key' => 'field_about_vision_label', 'label' => 'Label', 'name' => 'vision_label', 'type' => 'text', 'default_value' => 'Our Vision'],
            ['key' => 'field_about_vision_heading', 'label' => 'Heading', 'name' => 'vision_heading', 'type' => 'text', 'default_value' => 'A Future Where Every Muslim Youth Thrives'],
            ['key' => 'field_about_vision_text', 'label' => 'Content', 'name' => 'vision_paragraphs', 'type' => 'textarea', 'rows' => 6],
            ['key' => 'field_about_vision_image', 'label' => 'Image', 'name' => 'vision_image', 'type' => 'image', 'return_format' => 'array'],
            ['key' => 'field_about_vision_badge_num', 'label' => 'Badge Number', 'name' => 'vision_badge_number', 'type' => 'text', 'default_value' => '10+'],
            ['key' => 'field_about_vision_badge_text', 'label' => 'Badge Text', 'name' => 'vision_badge_text', 'type' => 'text', 'default_value' => 'Years Serving Our Community'],
            ['key' => 'field_about_vision_cta1_text', 'label' => 'Primary CTA Text', 'name' => 'vision_cta_primary_text', 'type' => 'text'],
            ['key' => 'field_about_vision_cta1_url', 'label' => 'Primary CTA URL', 'name' => 'vision_cta_primary_url', 'type' => 'url'],
            ['key' => 'field_about_vision_cta2_text', 'label' => 'Secondary CTA Text', 'name' => 'vision_cta_secondary_text', 'type' => 'text'],
            ['key' => 'field_about_vision_cta2_url', 'label' => 'Secondary CTA URL', 'name' => 'vision_cta_secondary_url', 'type' => 'url'],

            // Mission Tab
            ['key' => 'field_about_mission_tab', 'label' => 'Mission Section', 'name' => '', 'type' => 'tab'],
            ['key' => 'field_about_mission_label', 'label' => 'Label', 'name' => 'mission_label', 'type' => 'text', 'default_value' => 'Our Mission'],
            ['key' => 'field_about_mission_heading', 'label' => 'Heading', 'name' => 'mission_heading', 'type' => 'text'],
            ['key' => 'field_about_mission_text', 'label' => 'Content', 'name' => 'mission_paragraphs', 'type' => 'textarea', 'rows' => 6],
            ['key' => 'field_about_mission_image', 'label' => 'Image', 'name' => 'mission_image', 'type' => 'image', 'return_format' => 'array'],
            ['key' => 'field_about_mission_cta_text', 'label' => 'CTA Text', 'name' => 'mission_cta_text', 'type' => 'text'],
            ['key' => 'field_about_mission_cta_url', 'label' => 'CTA URL', 'name' => 'mission_cta_url', 'type' => 'url'],
            [
                'key' => 'field_about_mission_points', 'label' => 'Mission Points', 'name' => 'mission_points', 'type' => 'repeater', 'layout' => 'block', 'max' => 5,
                'sub_fields' => [
                    ['key' => 'field_mp_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'],
                    ['key' => 'field_mp_desc', 'label' => 'Description', 'name' => 'description', 'type' => 'text'],
                ],
            ],

            // Pillars Tab
            ['key' => 'field_about_pillars_tab', 'label' => 'Four Pillars', 'name' => '', 'type' => 'tab'],
            ['key' => 'field_about_pillars_label', 'label' => 'Label', 'name' => 'pillars_label', 'type' => 'text', 'default_value' => 'Our Approach'],
            ['key' => 'field_about_pillars_heading', 'label' => 'Heading', 'name' => 'pillars_heading', 'type' => 'text'],
            ['key' => 'field_about_pillars_subtitle', 'label' => 'Subtitle', 'name' => 'pillars_subtitle', 'type' => 'text'],
            [
                'key' => 'field_about_pillars', 'label' => 'Pillars', 'name' => 'pillars', 'type' => 'repeater', 'layout' => 'block', 'max' => 4,
                'sub_fields' => [
                    ['key' => 'field_pillar_icon', 'label' => 'Icon SVG Code', 'name' => 'icon_svg', 'type' => 'textarea', 'rows' => 3, 'instructions' => 'Paste the inner SVG markup'],
                    ['key' => 'field_pillar_gradient_start', 'label' => 'Gradient Start Color', 'name' => 'icon_gradient_start', 'type' => 'color_picker'],
                    ['key' => 'field_pillar_gradient_end', 'label' => 'Gradient End Color', 'name' => 'icon_gradient_end', 'type' => 'color_picker'],
                    ['key' => 'field_pillar_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'],
                    ['key' => 'field_pillar_desc', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 2],
                ],
            ],

            // Video Tab
            ['key' => 'field_about_video_tab', 'label' => 'Video Section', 'name' => '', 'type' => 'tab'],
            ['key' => 'field_about_video_label', 'label' => 'Label', 'name' => 'video_label', 'type' => 'text', 'default_value' => 'Leadership Message'],
            ['key' => 'field_about_video_heading', 'label' => 'Heading', 'name' => 'video_heading', 'type' => 'text'],
            ['key' => 'field_about_video_subtitle', 'label' => 'Subtitle', 'name' => 'video_subtitle', 'type' => 'text'],
            ['key' => 'field_about_video_url', 'label' => 'YouTube Video URL', 'name' => 'video_youtube_url', 'type' => 'url', 'instructions' => 'Full YouTube embed URL'],
            ['key' => 'field_about_video_quote', 'label' => 'Caption Quote', 'name' => 'video_caption_quote', 'type' => 'textarea', 'rows' => 2],
            ['key' => 'field_about_video_attr', 'label' => 'Caption Attribution', 'name' => 'video_caption_attribution', 'type' => 'text'],

            // Right Space Tab
            ['key' => 'field_about_space_tab', 'label' => 'The Right Space', 'name' => '', 'type' => 'tab'],
            ['key' => 'field_about_space_label', 'label' => 'Label', 'name' => 'right_space_label', 'type' => 'text', 'default_value' => 'The Right Space'],
            ['key' => 'field_about_space_heading', 'label' => 'Heading', 'name' => 'right_space_heading', 'type' => 'text'],
            ['key' => 'field_about_space_text', 'label' => 'Content', 'name' => 'right_space_paragraphs', 'type' => 'textarea', 'rows' => 6],
            ['key' => 'field_about_space_image', 'label' => 'Image', 'name' => 'right_space_image', 'type' => 'image', 'return_format' => 'array'],
            ['key' => 'field_about_space_cta_text', 'label' => 'CTA Text', 'name' => 'right_space_cta_text', 'type' => 'text'],
            ['key' => 'field_about_space_cta_url', 'label' => 'CTA URL', 'name' => 'right_space_cta_url', 'type' => 'url'],
            [
                'key' => 'field_about_space_features', 'label' => 'Features', 'name' => 'right_space_features', 'type' => 'repeater', 'layout' => 'table',
                'sub_fields' => [
                    ['key' => 'field_rsf_text', 'label' => 'Feature Text', 'name' => 'text', 'type' => 'text'],
                ],
            ],

            // Right People Tab
            ['key' => 'field_about_people_tab', 'label' => 'The Right People', 'name' => '', 'type' => 'tab'],
            ['key' => 'field_about_people_label', 'label' => 'Label', 'name' => 'right_people_label', 'type' => 'text', 'default_value' => 'The Right People'],
            ['key' => 'field_about_people_heading', 'label' => 'Heading', 'name' => 'right_people_heading', 'type' => 'text'],
            ['key' => 'field_about_people_text', 'label' => 'Content', 'name' => 'right_people_paragraphs', 'type' => 'textarea', 'rows' => 6],
            ['key' => 'field_about_people_image', 'label' => 'Image', 'name' => 'right_people_image', 'type' => 'image', 'return_format' => 'array'],
            ['key' => 'field_about_people_cta1_text', 'label' => 'Primary CTA Text', 'name' => 'right_people_cta_primary_text', 'type' => 'text'],
            ['key' => 'field_about_people_cta1_url', 'label' => 'Primary CTA URL', 'name' => 'right_people_cta_primary_url', 'type' => 'url'],
            ['key' => 'field_about_people_cta2_text', 'label' => 'Secondary CTA Text', 'name' => 'right_people_cta_secondary_text', 'type' => 'text'],
            ['key' => 'field_about_people_cta2_url', 'label' => 'Secondary CTA URL', 'name' => 'right_people_cta_secondary_url', 'type' => 'url'],
            [
                'key' => 'field_about_people_stats', 'label' => 'Stats', 'name' => 'right_people_stats', 'type' => 'repeater', 'layout' => 'table', 'max' => 4,
                'sub_fields' => [
                    ['key' => 'field_rps_number', 'label' => 'Number', 'name' => 'number', 'type' => 'text'],
                    ['key' => 'field_rps_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text'],
                ],
            ],
        ],
        'location' => [
            [['param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/template-about.php']],
        ],
    ]);

    // ─── DONATE PAGE FIELDS ────────────────────────────────────
    acf_add_local_field_group([
        'key'      => 'group_myco_donate',
        'title'    => 'Donate Page Content',
        'fields'   => [
            ['key' => 'field_donate_badge', 'label' => 'Hero Badge Text', 'name' => 'donate_hero_badge_text', 'type' => 'text', 'default_value' => '100% TAX-DEDUCTIBLE'],
            ['key' => 'field_donate_title', 'label' => 'Hero Title', 'name' => 'donate_hero_title', 'type' => 'text', 'default_value' => 'Support Our Mission'],
            ['key' => 'field_donate_subtitle', 'label' => 'Hero Subtitle', 'name' => 'donate_hero_subtitle', 'type' => 'textarea', 'rows' => 3],
            [
                'key' => 'field_donate_hero_stats', 'label' => 'Hero Stats', 'name' => 'donate_hero_stats', 'type' => 'repeater', 'layout' => 'table', 'max' => 4,
                'sub_fields' => [
                    ['key' => 'field_dhs_number', 'label' => 'Number', 'name' => 'number', 'type' => 'text'],
                    ['key' => 'field_dhs_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text'],
                ],
            ],
            ['key' => 'field_donate_form_heading', 'label' => 'Form Heading', 'name' => 'donation_form_heading', 'type' => 'text'],
            ['key' => 'field_donate_form_subtitle', 'label' => 'Form Subtitle', 'name' => 'donation_form_subtitle', 'type' => 'text'],
            ['key' => 'field_donate_why_label', 'label' => 'Why Give Label', 'name' => 'why_give_label', 'type' => 'text'],
            ['key' => 'field_donate_why_heading', 'label' => 'Why Give Heading', 'name' => 'why_give_heading', 'type' => 'text'],
            ['key' => 'field_donate_why_subtitle', 'label' => 'Why Give Subtitle', 'name' => 'why_give_subtitle', 'type' => 'textarea', 'rows' => 2],
            [
                'key' => 'field_donate_impact_cards', 'label' => 'Impact Cards', 'name' => 'impact_cards', 'type' => 'repeater', 'layout' => 'block', 'max' => 6,
                'sub_fields' => [
                    ['key' => 'field_dic_icon', 'label' => 'Icon SVG', 'name' => 'icon_svg', 'type' => 'textarea', 'rows' => 3],
                    ['key' => 'field_dic_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'],
                    ['key' => 'field_dic_desc', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 2],
                ],
            ],
            ['key' => 'field_donate_testimonial_quote', 'label' => 'Donor Testimonial Quote', 'name' => 'donor_testimonial_quote', 'type' => 'textarea', 'rows' => 3],
            ['key' => 'field_donate_testimonial_name', 'label' => 'Donor Name', 'name' => 'donor_testimonial_name', 'type' => 'text'],
            ['key' => 'field_donate_testimonial_title', 'label' => 'Donor Title', 'name' => 'donor_testimonial_title', 'type' => 'text'],
            ['key' => 'field_donate_final_heading', 'label' => 'Final CTA Heading', 'name' => 'donate_final_cta_heading', 'type' => 'text'],
            ['key' => 'field_donate_final_subtitle', 'label' => 'Final CTA Subtitle', 'name' => 'donate_final_cta_subtitle', 'type' => 'textarea', 'rows' => 2],
        ],
        'location' => [
            [['param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/template-donate.php']],
        ],
    ]);

    // ─── CONTACT PAGE FIELDS ───────────────────────────────────
    acf_add_local_field_group([
        'key'      => 'group_myco_contact',
        'title'    => 'Contact Page Content',
        'fields'   => [
            ['key' => 'field_contact_hero_title', 'label' => 'Hero Title', 'name' => 'contact_hero_title', 'type' => 'text', 'default_value' => 'Get In Touch'],
            ['key' => 'field_contact_hero_subtitle', 'label' => 'Hero Subtitle', 'name' => 'contact_hero_subtitle', 'type' => 'text'],
            ['key' => 'field_contact_map_url', 'label' => 'Google Maps Embed URL', 'name' => 'map_embed_url', 'type' => 'url'],
            [
                'key' => 'field_contact_faq', 'label' => 'FAQ Items', 'name' => 'faq_items', 'type' => 'repeater', 'layout' => 'block',
                'sub_fields' => [
                    ['key' => 'field_faq_q', 'label' => 'Question', 'name' => 'question', 'type' => 'text'],
                    ['key' => 'field_faq_a', 'label' => 'Answer', 'name' => 'answer', 'type' => 'wysiwyg', 'media_upload' => 0, 'tabs' => 'visual'],
                ],
            ],
        ],
        'location' => [
            [['param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/template-contact.php']],
        ],
    ]);

    // ─── VOLUNTEER PAGE FIELDS ─────────────────────────────────
    acf_add_local_field_group([
        'key'      => 'group_myco_volunteer',
        'title'    => 'Volunteer Page Content',
        'fields'   => [
            ['key' => 'field_vol_hero_title', 'label' => 'Hero Title', 'name' => 'volunteer_hero_title', 'type' => 'text', 'default_value' => 'Volunteer With Us'],
            ['key' => 'field_vol_hero_subtitle', 'label' => 'Hero Subtitle', 'name' => 'volunteer_hero_subtitle', 'type' => 'text'],
            ['key' => 'field_vol_why_label', 'label' => 'Why Volunteer Label', 'name' => 'why_volunteer_label', 'type' => 'text'],
            ['key' => 'field_vol_why_heading', 'label' => 'Why Volunteer Heading', 'name' => 'why_volunteer_heading', 'type' => 'text'],
            ['key' => 'field_vol_why_subtitle', 'label' => 'Why Volunteer Subtitle', 'name' => 'why_volunteer_subtitle', 'type' => 'textarea', 'rows' => 2],
            [
                'key' => 'field_vol_reasons', 'label' => 'Why Volunteer Reasons', 'name' => 'why_volunteer_reasons', 'type' => 'repeater', 'layout' => 'block',
                'sub_fields' => [
                    ['key' => 'field_vr_icon', 'label' => 'Icon SVG', 'name' => 'icon_svg', 'type' => 'textarea', 'rows' => 3],
                    ['key' => 'field_vr_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'],
                    ['key' => 'field_vr_desc', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 2],
                ],
            ],
            [
                'key' => 'field_vol_roles', 'label' => 'Volunteer Roles', 'name' => 'volunteer_roles', 'type' => 'repeater', 'layout' => 'block',
                'sub_fields' => [
                    ['key' => 'field_vrl_title', 'label' => 'Title', 'name' => 'title', 'type' => 'text'],
                    ['key' => 'field_vrl_desc', 'label' => 'Description', 'name' => 'description', 'type' => 'textarea', 'rows' => 2],
                    ['key' => 'field_vrl_commitment', 'label' => 'Time Commitment', 'name' => 'commitment', 'type' => 'text'],
                ],
            ],
            [
                'key' => 'field_vol_impact', 'label' => 'Impact Stats', 'name' => 'volunteer_impact_stats', 'type' => 'repeater', 'layout' => 'table', 'max' => 4,
                'sub_fields' => [
                    ['key' => 'field_vis_number', 'label' => 'Number', 'name' => 'number', 'type' => 'text'],
                    ['key' => 'field_vis_label', 'label' => 'Label', 'name' => 'label', 'type' => 'text'],
                ],
            ],
        ],
        'location' => [
            [['param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/template-volunteer.php']],
        ],
    ]);

    // ─── GALLERY PAGE FIELDS ───────────────────────────────────
    acf_add_local_field_group([
        'key'      => 'group_myco_gallery_page',
        'title'    => 'Gallery Page Content',
        'fields'   => [
            ['key' => 'field_gal_hero_title', 'label' => 'Hero Title', 'name' => 'gallery_hero_title', 'type' => 'text', 'default_value' => 'Photo Gallery'],
            ['key' => 'field_gal_hero_subtitle', 'label' => 'Hero Subtitle', 'name' => 'gallery_hero_subtitle', 'type' => 'text'],
            ['key' => 'field_gal_cta_heading', 'label' => 'CTA Heading', 'name' => 'gallery_cta_heading', 'type' => 'text'],
            ['key' => 'field_gal_cta_subtitle', 'label' => 'CTA Subtitle', 'name' => 'gallery_cta_subtitle', 'type' => 'text'],
        ],
        'location' => [
            [['param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/template-gallery.php']],
        ],
    ]);

    // ─── PRIVACY POLICY PAGE FIELDS ────────────────────────────
    acf_add_local_field_group([
        'key'      => 'group_myco_privacy',
        'title'    => 'Privacy Policy Content',
        'fields'   => [
            ['key' => 'field_priv_date', 'label' => 'Effective Date', 'name' => 'privacy_effective_date', 'type' => 'date_picker', 'display_format' => 'F j, Y', 'return_format' => 'F j, Y'],
            ['key' => 'field_priv_intro', 'label' => 'Introduction', 'name' => 'privacy_introduction', 'type' => 'textarea', 'rows' => 4],
            [
                'key' => 'field_priv_sections', 'label' => 'Policy Sections', 'name' => 'privacy_sections', 'type' => 'repeater', 'layout' => 'block',
                'sub_fields' => [
                    ['key' => 'field_ps_title', 'label' => 'Section Title', 'name' => 'title', 'type' => 'text'],
                    ['key' => 'field_ps_content', 'label' => 'Section Content', 'name' => 'content', 'type' => 'wysiwyg', 'tabs' => 'visual', 'media_upload' => 0],
                ],
            ],
        ],
        'location' => [
            [['param' => 'page_template', 'operator' => '==', 'value' => 'page-templates/template-privacy.php']],
        ],
    ]);

    // ─── FOOTER SETTINGS (Options Page) ────────────────────────
    acf_add_local_field_group([
        'key'      => 'group_myco_footer',
        'title'    => 'Footer Settings',
        'fields'   => [
            // Dark Footer Tab
            ['key' => 'field_footer_dark_tab', 'label' => 'Dark Footer', 'name' => '', 'type' => 'tab'],
            [
                'key' => 'field_footer_circle_images', 'label' => 'Circle Photo Strip', 'name' => 'footer_circle_images', 'type' => 'gallery', 'return_format' => 'array',
                'instructions' => 'Upload images for the circle photo strip in the dark footer (shown on most pages)',
                'max' => 20,
            ],
            ['key' => 'field_footer_contact_email', 'label' => 'Contact Bar Email Text', 'name' => 'footer_contact_email', 'type' => 'text', 'default_value' => 'info@myco.org'],
            ['key' => 'field_footer_contact_phone', 'label' => 'Contact Bar Phone Text', 'name' => 'footer_contact_phone', 'type' => 'text', 'default_value' => '(614) 555-0123'],
            ['key' => 'field_footer_newsletter_heading', 'label' => 'Newsletter Heading', 'name' => 'footer_newsletter_heading', 'type' => 'text', 'default_value' => 'Stay Connected'],
            ['key' => 'field_footer_newsletter_text', 'label' => 'Newsletter Text', 'name' => 'footer_newsletter_text', 'type' => 'text'],
        ],
        'location' => [
            [['param' => 'options_page', 'operator' => '==', 'value' => 'myco-footer']],
        ],
    ]);
}
