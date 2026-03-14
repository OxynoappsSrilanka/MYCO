<?php
/**
 * Template Name: About
 * @package MYCO
 */
get_header();
?>

<?php get_template_part('template-parts/hero/hero-breadcrumb-light', null, array(
    'title' => 'About MYCO',
    'subtitle' => 'Empowering Muslim youth through faith, mentorship, and community',
)); ?>

<?php get_template_part('template-parts/about/vision-section'); ?>
<?php get_template_part('template-parts/about/mission-section'); ?>
<?php get_template_part('template-parts/about/four-pillars'); ?>
<?php get_template_part('template-parts/about/video-section'); ?>
<?php get_template_part('template-parts/about/right-space'); ?>
<?php get_template_part('template-parts/about/right-people'); ?>

<?php get_footer(); ?>
