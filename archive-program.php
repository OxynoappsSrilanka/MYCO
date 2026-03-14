<?php
/**
 * Archive: Programs
 * Fallback if no page template is used
 * @package MYCO
 */
get_header();
?>

<?php get_template_part('template-parts/hero/hero-breadcrumb-dark', null, array(
    'title'    => 'Our Programs',
    'subtitle' => 'Discover programs designed to empower, educate, and inspire Muslim youth',
    'breadcrumb' => [
        ['label' => __('Home', 'myco'), 'url' => home_url('/')],
        ['label' => __('Programs', 'myco'), 'url' => ''],
    ],
)); ?>

<section style="background: #F5F6FA; padding: 90px 0 110px;">
  <div class="inner">
    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 40px;">
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php get_template_part('template-parts/cards/program-card'); ?>
      <?php endwhile; ?>
      <?php else : ?>
        <div style="grid-column: 1 / -1; text-align: center; padding: 80px 20px;">
          <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#D1D5DB" stroke-width="1.5" style="margin: 0 auto 20px;"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
          <h3 style="font-size: 22px; font-weight: 800; color: #141943; margin-bottom: 12px;">Programs Coming Soon</h3>
          <p style="font-size: 16px; color: #6B7280; margin-bottom: 28px; max-width: 400px; margin-left: auto; margin-right: auto;">We're building something great. Our programs for Muslim youth will be available here very soon.</p>
          <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="pill-primary"><?php esc_html_e('Contact Us', 'myco'); ?></a>
        </div>
      <?php endif; ?>
    </div>

    <div style="margin-top: 60px; text-align: center;">
      <?php the_posts_pagination(array(
          'mid_size' => 2,
          'prev_text' => '&larr; Previous',
          'next_text' => 'Next &rarr;',
      )); ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
