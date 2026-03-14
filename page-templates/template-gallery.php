<?php
/**
 * Template Name: Gallery
 * @package MYCO
 */
get_header();

// Define default gallery items with categories
$default_gallery = array(
    array('image' => 'myco-basketball-champions-team-with-trophy.jpg.jpg', 'caption' => 'Basketball Champions Team with Trophy', 'category' => 'athletics'),
    array('image' => 'myco-basketball-tournament-award-ceremony-team-celebration.jpg.JPG', 'caption' => 'Basketball Tournament Award Ceremony', 'category' => 'athletics'),
    array('image' => 'myco-basketball-tournament-championship-trophies.jpg.JPG', 'caption' => 'Basketball Tournament Championship Trophies', 'category' => 'athletics'),
    array('image' => 'myco-basketball-tournament-medals-awards-table.jpg.JPG', 'caption' => 'Basketball Tournament Medals and Awards', 'category' => 'athletics'),
    array('image' => 'myco-youth-basketball-player-in-game-action.jpg.jpg', 'caption' => 'Youth Basketball Player in Game Action', 'category' => 'athletics'),
    array('image' => 'myco-youth-basketball-event-congregational-prayer.jpg', 'caption' => 'Youth Basketball Event Congregational Prayer', 'category' => 'spiritual'),
    array('image' => 'myco-youth-community-center-groundbreaking-ceremony.jpg', 'caption' => 'Youth Community Center Groundbreaking Ceremony', 'category' => 'community'),
    array('image' => 'myco-youth-community-groundbreaking-event-autograph.jpg', 'caption' => 'Community Groundbreaking Event', 'category' => 'community'),
    array('image' => 'MCYC Groundbreaking_ Aatifa.jpg', 'caption' => 'MCYC Groundbreaking Ceremony', 'category' => 'community'),
    array('image' => 'myco-youth-team-award-check-winners.jpg', 'caption' => 'Youth Team Award Winners', 'category' => 'athletics'),
);

// Define categories
$default_categories = array(
    array('slug' => 'athletics', 'name' => 'Athletics & Sports'),
    array('slug' => 'spiritual', 'name' => 'Spiritual Development'),
    array('slug' => 'community', 'name' => 'Community Events'),
);

// Get all gallery albums for filter tabs
$albums = get_terms(array(
    'taxonomy' => 'gallery_album',
    'hide_empty' => true,
));

// Use default categories if no custom albums exist
if (empty($albums) || is_wp_error($albums)) {
    $albums = $default_categories;
    $use_defaults = true;
} else {
    $use_defaults = false;
}

// Get all gallery photos
$gallery_query = new WP_Query(array(
    'post_type' => 'gallery_photo',
    'posts_per_page' => -1,
    'orderby' => 'menu_order',
    'order' => 'ASC',
));

// Use default gallery if no posts
$use_default_gallery = !$gallery_query->have_posts();
?>

<!-- Hero Section -->
<?php get_template_part('template-parts/hero/hero-breadcrumb-dark', null, array(
    'title' => get_the_title(),
    'subtitle' => myco_get_field('gallery_subtitle') ?: 'Browse photos from MYCO events, programs, and community activities',
)); ?>

<!-- Gallery Section -->
<section style="background: #F5F6FA; padding: 90px 0 110px; position: relative;">
  <div class="inner">

    <!-- Section Header -->
    <div style="text-align: center; margin-bottom: 50px;">
      <p style="font-size: 15px; font-weight: 700; color: #C8402E; margin-bottom: 12px; letter-spacing: 0.02em;">
        <?php echo esc_html(myco_get_field('gallery_label') ?: 'Our Gallery'); ?>
      </p>
      <h2 style="font-size: 52px; font-weight: 900; color: #141943; line-height: 1.1; letter-spacing: -0.02em; margin-bottom: 18px;">
        <?php echo esc_html(myco_get_field('gallery_heading') ?: 'Capturing Our Community in Action'); ?>
      </h2>
      <p style="font-size: 18px; color: #5B6575; line-height: 1.65; max-width: 680px; margin: 0 auto;">
        <?php echo esc_html(myco_get_field('gallery_description') ?: 'See the moments that make MYCO special — from sports and mentorship to service and celebrations'); ?>
      </p>
    </div>

    <!-- Filter Tabs -->
    <div style="display: flex; justify-content: center; gap: 12px; margin-bottom: 50px; flex-wrap: wrap;" id="gallery-filters">
      <button class="album-tab active" data-filter="all" onclick="filterGallery('all', this)" style="
        padding: 10px 24px;
        border-radius: 9999px;
        font-size: 15px;
        font-weight: 600;
        border: 2px solid #C8402E;
        background: #C8402E;
        color: #ffffff;
        cursor: pointer;
        transition: all .2s;
      ">All Photos</button>
      <?php foreach ($albums as $album) : 
        $album_slug = $use_defaults ? $album['slug'] : $album->slug;
        $album_name = $use_defaults ? $album['name'] : $album->name;
      ?>
      <button class="album-tab" data-filter="<?php echo esc_attr($album_slug); ?>" onclick="filterGallery('<?php echo esc_attr($album_slug); ?>', this)" style="
        padding: 10px 24px;
        border-radius: 9999px;
        font-size: 15px;
        font-weight: 600;
        border: 2px solid #E5E7EB;
        background: #ffffff;
        color: #374151;
        cursor: pointer;
        transition: all .2s;
      "><?php echo esc_html($album_name); ?></button>
      <?php endforeach; ?>
    </div>

    <!-- Gallery Grid -->
    <div class="gallery-grid" id="gallery-grid">
      <?php if ($use_default_gallery) : ?>
        <?php foreach ($default_gallery as $item) : ?>
        <div class="gallery-item" data-album="<?php echo esc_attr($item['category']); ?>" onclick="openLightbox(this)">
          <img src="<?php echo esc_url(MYCO_URI . '/assets/images/galleries/' . $item['image']); ?>" alt="<?php echo esc_attr($item['caption']); ?>" loading="lazy" data-full="<?php echo esc_url(MYCO_URI . '/assets/images/galleries/' . $item['image']); ?>" />
          <div class="gallery-overlay">
            <div class="gallery-overlay-content">
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                <path d="M11 8v6M8 11h6"/>
              </svg>
              <p style="color: #fff; font-size: 14px; font-weight: 600; margin-top: 8px;"><?php echo esc_html($item['caption']); ?></p>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      <?php elseif ($gallery_query->have_posts()) : ?>
        <?php while ($gallery_query->have_posts()) : $gallery_query->the_post();
          $albums_list = get_the_terms(get_the_ID(), 'gallery_album');
          $album_slugs = '';
          if ($albums_list && !is_wp_error($albums_list)) {
              $album_slugs = implode(' ', wp_list_pluck($albums_list, 'slug'));
          }
          $caption = myco_get_field('photo_caption') ?: get_the_title();
          $image_url = get_the_post_thumbnail_url(get_the_ID(), 'myco-gallery');
          $image_full = get_the_post_thumbnail_url(get_the_ID(), 'full');
          if (!$image_url) {
              $image_url = 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=600&h=400&fit=crop';
              $image_full = $image_url;
          }
        ?>
        <div class="gallery-item" data-album="<?php echo esc_attr($album_slugs); ?>" onclick="openLightbox(this)">
          <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($caption); ?>" loading="lazy" data-full="<?php echo esc_url($image_full); ?>" />
          <div class="gallery-overlay">
            <div class="gallery-overlay-content">
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
                <path d="M11 8v6M8 11h6"/>
              </svg>
              <p style="color: #fff; font-size: 14px; font-weight: 600; margin-top: 8px;"><?php echo esc_html($caption); ?></p>
            </div>
          </div>
        </div>
        <?php endwhile; wp_reset_postdata(); ?>
      <?php else : ?>
        <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px;">
          <p style="font-size: 18px; color: #6B7280;">No photos have been added yet. Check back soon!</p>
        </div>
      <?php endif; ?>
    </div>

  </div>
</section>

<!-- CTA Section -->
<section style="background: linear-gradient(130deg, #111640 0%, #182050 40%, #2a3e6a 100%); padding: 100px 0; position: relative; overflow: hidden;">
  <div class="inner" style="position: relative; z-index: 2; text-align: center;">
    <h2 style="font-size: 48px; font-weight: 900; color: #ffffff; line-height: 1.1; letter-spacing: -0.02em; margin-bottom: 18px;">
      <?php echo esc_html(myco_get_field('gallery_cta_heading') ?: 'Want to Be Part of These Moments?'); ?>
    </h2>
    <p style="font-size: 20px; color: #B8C8DC; line-height: 1.6; max-width: 700px; margin: 0 auto 40px; font-weight: 400;">
      <?php echo esc_html(myco_get_field('gallery_cta_text') ?: 'Join our programs, attend our events, and become part of the MYCO family'); ?>
    </p>
    <div style="display: flex; gap: 16px; justify-content: center; flex-wrap: wrap;">
      <a href="<?php echo esc_url(get_permalink(get_page_by_path('programs'))); ?>" class="pill-primary">Explore Programs</a>
      <a href="<?php echo esc_url(get_permalink(get_page_by_path('volunteer'))); ?>" class="pill-secondary" style="border-color: rgba(255,255,255,0.3); color: #fff;">Volunteer With Us</a>
    </div>
  </div>
</section>

<!-- Lightbox -->
<div id="lightbox" class="lightbox" style="display: none;">
  <button class="lightbox-close" onclick="closeLightbox()" aria-label="Close lightbox">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round">
      <path d="M18 6L6 18M6 6l12 12"/>
    </svg>
  </button>
  <div class="lightbox-container">
    <button class="lightbox-nav prev" onclick="navigateLightbox(-1)" aria-label="Previous photo">
      <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="white" stroke-width="2" stroke-linecap="round">
        <path d="M12 4l-6 6 6 6"/>
      </svg>
    </button>
    <img id="lightbox-img" src="" alt="" />
    <button class="lightbox-nav next" onclick="navigateLightbox(1)" aria-label="Next photo">
      <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="white" stroke-width="2" stroke-linecap="round">
        <path d="M8 4l6 6-6 6"/>
      </svg>
    </button>
    <p class="lightbox-caption" id="lightbox-caption"></p>
  </div>
</div>

<?php get_footer(); ?>
