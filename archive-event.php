<?php
/**
 * Archive: Events
 * @package MYCO
 */
get_header();
?>

<?php get_template_part('template-parts/hero/hero-breadcrumb-dark', null, array(
    'title'    => 'Upcoming Events',
    'subtitle' => 'Join us for community events, workshops, and activities',
    'breadcrumb' => [
        ['label' => __('Home', 'myco'), 'url' => home_url('/')],
        ['label' => __('Events', 'myco'), 'url' => ''],
    ],
)); ?>

<section style="background: #F5F6FA; padding: 90px 0 110px;">
  <div class="inner">
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px;">
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php get_template_part('template-parts/cards/event-card'); ?>
      <?php endwhile; ?>
      <?php else : ?>
        <div style="grid-column: 1 / -1; text-align: center; padding: 80px 20px;">
          <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#D1D5DB" stroke-width="1.5" style="margin: 0 auto 20px;"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          <h3 style="font-size: 22px; font-weight: 800; color: #141943; margin-bottom: 12px;">No Events Scheduled Yet</h3>
          <p style="font-size: 16px; color: #6B7280; margin-bottom: 28px; max-width: 400px; margin-left: auto; margin-right: auto;">Check back soon — we're always planning exciting community events and activities for MYCO members.</p>
          <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="pill-primary"><?php esc_html_e('Get Notified', 'myco'); ?></a>
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
