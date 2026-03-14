<?php
/**
 * Homepage Template
 *
 * @package MYCO
 */

get_header();

// Homepage sections
get_template_part('template-parts/hero/hero-home');
get_template_part('template-parts/sections/discover-story');
get_template_part('template-parts/sections/campaign-cta');
get_template_part('template-parts/sections/programs-grid');
get_template_part('template-parts/sections/outcomes');
get_template_part('template-parts/sections/testimonials-slider');
get_template_part('template-parts/sections/upcoming-events');
get_template_part('template-parts/sections/volunteer-cta');

get_footer();
