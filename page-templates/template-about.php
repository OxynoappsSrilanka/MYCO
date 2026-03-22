<?php
/**
 * Template Name: About
 * @package MYCO
 */
get_header();
?>

<!-- Hero Banner Section with Full Width Blurred Background -->
<section class="page-hero-bg" style="
  background: url('<?php echo esc_url(myco_get_field('about_banner_image') ?: get_template_directory_uri() . '/assets/images/about.png'); ?>') center center / cover no-repeat;
  padding: 140px 0;
  position: relative;
  overflow: hidden;
">
  <!-- Blur Overlay -->
  <div style="
    position: absolute;
    inset: 0;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    background: rgba(20, 25, 67, 0.75);
    z-index: 1;
  "></div>
  
  <!-- Content -->
  <div style="position: relative; z-index: 2; text-align: center; max-width: 1200px; margin: 0 auto; padding: 0 40px;">
    <!-- Breadcrumb -->
    <div style="display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 24px;">
      <a href="<?php echo esc_url(home_url('/')); ?>" style="font-size: 14px; font-weight: 500; color: rgba(255,255,255,0.75); text-decoration: none; transition: color .2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.75)'">Home</a>
      <svg width="6" height="10" viewBox="0 0 6 10" fill="none">
        <path d="M1 1l4 4-4 4" stroke="rgba(255,255,255,0.6)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      <span style="font-size: 14px; font-weight: 600; color: #ffffff;">About MYCO</span>
    </div>
    
    <!-- Page Title -->
    <h1 style="
      font-size: 72px;
      font-weight: 900;
      color: #ffffff;
      line-height: 1.1;
      letter-spacing: -0.02em;
      margin-bottom: 20px;
      text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    ">
      <?php echo esc_html(myco_get_field('about_title') ?: 'About MYCO'); ?>
    </h1>
    
    <!-- Subtitle -->
    <p style="
      font-size: 20px;
      color: rgba(255, 255, 255, 0.95);
      line-height: 1.6;
      max-width: 700px;
      margin: 0 auto;
      font-weight: 400;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    ">
      <?php echo esc_html(myco_get_field('about_subtitle') ?: 'Empowering Muslim youth through faith, mentorship, and community'); ?>
    </p>
  </div>
</section>

<?php get_template_part('template-parts/about/vision-section'); ?>
<?php get_template_part('template-parts/about/mission-section'); ?>
<?php get_template_part('template-parts/about/four-pillars'); ?>
<?php get_template_part('template-parts/about/video-section'); ?>
<?php get_template_part('template-parts/about/right-space'); ?>
<?php get_template_part('template-parts/about/right-people'); ?>

<?php get_footer(); ?>
