<?php
/**
 * Archive: News
 * @package MYCO
 */
get_header();
?>

<?php get_template_part('template-parts/hero/hero-breadcrumb-dark', null, array(
    'title'    => 'MYCO News',
    'subtitle' => 'Stay updated with the latest stories, announcements, and community highlights',
    'breadcrumb' => [
        ['label' => __('Home', 'myco'), 'url' => home_url('/')],
        ['label' => __('News', 'myco'), 'url' => ''],
    ],
)); ?>

<section class="news-archive-shell">
  <div class="inner">
    <div class="news-grid">
      <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php get_template_part('template-parts/cards/news-card'); ?>
      <?php endwhile; ?>
      <?php else : ?>
        <div class="news-empty-state">
          <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#D1D5DB" stroke-width="1.5" style="margin: 0 auto 20px;"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8"/></svg>
          <h3 style="font-size: 22px; font-weight: 800; color: #141943; margin-bottom: 12px;">No Articles Yet</h3>
          <p style="font-size: 16px; color: #6B7280; margin-bottom: 28px; max-width: 400px; margin-left: auto; margin-right: auto;">We're working on bringing you the latest MYCO news and community stories. Check back soon!</p>
          <a href="<?php echo esc_url(myco_get_contact_page_url()); ?>" class="pill-primary"><?php esc_html_e('Stay Connected', 'myco'); ?></a>
        </div>
      <?php endif; ?>
    </div>

    <div class="news-pagination" style="margin-top: 60px; text-align: center;">
      <?php the_posts_pagination(array(
          'mid_size' => 2,
          'prev_text' => '&larr; Previous',
          'next_text' => 'Next &rarr;',
      )); ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
