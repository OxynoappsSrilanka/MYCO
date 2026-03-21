<?php
/**
 * Template Name: Gallery
 * @package MYCO
 */
get_header();

// Define default gallery items with categories and types
$default_gallery = array(
    array('image' => 'myco-basketball-champions-team-with-trophy.jpg.jpg', 'caption' => 'Basketball Champions Team with Trophy', 'category' => 'sports', 'type' => 'photo'),
    array('image' => 'myco-basketball-tournament-award-ceremony-team-celebration.jpg.JPG', 'caption' => 'Basketball Tournament Award Ceremony', 'category' => 'sports', 'type' => 'photo'),
    array('image' => 'myco-basketball-tournament-championship-trophies.jpg.JPG', 'caption' => 'Basketball Tournament Championship Trophies', 'category' => 'sports', 'type' => 'photo'),
    array('image' => 'myco-basketball-tournament-medals-awards-table.jpg.JPG', 'caption' => 'Basketball Tournament Medals and Awards', 'category' => 'sports', 'type' => 'photo'),
    array('image' => 'myco-youth-basketball-player-in-game-action.jpg.jpg', 'caption' => 'Youth Basketball Player in Game Action', 'category' => 'sports', 'type' => 'photo'),
    array('image' => 'myco-youth-basketball-event-congregational-prayer.jpg', 'caption' => 'Youth Basketball Event Congregational Prayer', 'category' => 'events', 'type' => 'photo'),
    array('image' => 'myco-youth-community-center-groundbreaking-ceremony.jpg', 'caption' => 'Youth Community Center Groundbreaking Ceremony', 'category' => 'events', 'type' => 'photo'),
    array('image' => 'myco-youth-community-groundbreaking-event-autograph.jpg', 'caption' => 'Community Groundbreaking Event', 'category' => 'events', 'type' => 'photo'),
    array('image' => 'MCYC Groundbreaking_ Aatifa.jpg', 'caption' => 'MCYC Groundbreaking Ceremony', 'category' => 'events', 'type' => 'photo'),
    array('image' => 'myco-youth-team-award-check-winners.jpg', 'caption' => 'Youth Team Award Winners', 'category' => 'sports', 'type' => 'photo'),
);

// Define categories
$default_categories = array(
    array('slug' => 'sports', 'name' => 'Sports & Athletics'),
    array('slug' => 'events', 'name' => 'Community Events'),
    array('slug' => 'workshops', 'name' => 'Workshops'),
    array('slug' => 'service', 'name' => 'Community Service'),
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

<!-- Hero Banner Section with Full Width Blurred Background -->
<section style="
  background: url('<?php echo esc_url(myco_get_field('gallery_banner_image') ?: get_template_directory_uri() . '/assets/images/study.jpg'); ?>') center center / cover no-repeat;
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
      <span style="font-size: 14px; font-weight: 600; color: #ffffff;">Gallery</span>
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
      <?php echo esc_html(myco_get_field('gallery_title') ?: 'Photo Gallery'); ?>
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
      <?php echo esc_html(myco_get_field('gallery_subtitle') ?: 'Explore moments from our programs, events, and community activities'); ?>
    </p>
  </div>
</section>

<!-- Filter Tabs Section -->
<section style="background: #F5F6FA; padding: 60px 0 0; position: relative;">
  <div class="inner">
    <div style="display: flex; align-items: center; justify-content: space-between; gap: 24px; margin-bottom: 32px; flex-wrap: wrap;">
      <!-- Filter Tabs -->
      <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap; flex: 1;">
        <button class="album-tab active" data-filter="all" onclick="filterGallery('all', this)" style="
          height: 48px;
          padding: 0 28px;
          border-radius: 9999px;
          border: 2px solid #C8402E;
          background: #C8402E;
          color: #ffffff;
          font-size: 15px;
          font-weight: 600;
          cursor: pointer;
          transition: all .2s;
          white-space: nowrap;
        ">All Photos</button>
        <?php foreach ($albums as $album) : 
          $album_slug = $use_defaults ? $album['slug'] : $album->slug;
          $album_name = $use_defaults ? $album['name'] : $album->name;
        ?>
        <button class="album-tab" data-filter="<?php echo esc_attr($album_slug); ?>" onclick="filterGallery('<?php echo esc_attr($album_slug); ?>', this)" style="
          height: 48px;
          padding: 0 28px;
          border-radius: 9999px;
          border: 2px solid #E2E6ED;
          background: #ffffff;
          color: #4B5563;
          font-size: 15px;
          font-weight: 600;
          cursor: pointer;
          transition: all .2s;
          white-space: nowrap;
        "><?php echo esc_html($album_name); ?></button>
        <?php endforeach; ?>
      </div>
      
      <!-- Search Bar -->
      <div style="position: relative; width: 380px; flex-shrink: 0;">
        <input type="text" id="gallery-search" placeholder="Search photos..." style="
          width: 100%;
          height: 48px;
          padding: 0 50px 0 20px;
          border: 2px solid #E5E7EB;
          border-radius: 9999px;
          font-size: 15px;
          color: #141943;
          background: #ffffff;
          transition: all .2s;
          outline: none;
        " onfocus="this.style.borderColor='#C8402E'; this.style.boxShadow='0 0 0 3px rgba(200,64,46,0.1)'" onblur="this.style.borderColor='#E5E7EB'; this.style.boxShadow='none'" />
        <button style="
          position: absolute;
          right: 6px;
          top: 50%;
          transform: translateY(-50%);
          width: 36px;
          height: 36px;
          border-radius: 50%;
          background: #C8402E;
          border: none;
          cursor: pointer;
          display: flex;
          align-items: center;
          justify-content: center;
          transition: background .2s;
        " onmouseover="this.style.background='#b03426'" onmouseout="this.style.background='#C8402E'">
          <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
            <circle cx="8" cy="8" r="5.5" stroke="white" stroke-width="2"/>
            <path d="m15 15-3-3" stroke="white" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </button>
      </div>
    </div>
    
    <!-- Toggle Button and Results Count -->
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 32px; flex-wrap: wrap; gap: 16px;">
      <!-- Photo/Video Toggle -->
      <div style="display: inline-flex; background: #ffffff; border-radius: 9999px; padding: 4px; border: 2px solid #E5E7EB; box-shadow: 0 2px 8px rgba(20, 25, 67, 0.08);">
        <button id="toggle-photos" class="media-toggle active" onclick="toggleMediaType('photos')" style="
          display: flex;
          align-items: center;
          gap: 8px;
          height: 44px;
          padding: 0 24px;
          border-radius: 9999px;
          border: none;
          background: #C8402E;
          color: #ffffff;
          font-size: 15px;
          font-weight: 600;
          cursor: pointer;
          transition: all .2s;
        ">
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
            <rect x="2" y="4" width="16" height="12" rx="2" stroke="currentColor" stroke-width="1.8"/>
            <circle cx="7" cy="9" r="1.5" fill="currentColor"/>
            <path d="M2 13l4-4 3 3 5-5 4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          Photos
        </button>
        <button id="toggle-videos" class="media-toggle" onclick="toggleMediaType('videos')" style="
          display: flex;
          align-items: center;
          gap: 8px;
          height: 44px;
          padding: 0 24px;
          border-radius: 9999px;
          border: none;
          background: transparent;
          color: #6B7280;
          font-size: 15px;
          font-weight: 600;
          cursor: pointer;
          transition: all .2s;
        ">
          <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
            <rect x="2" y="4" width="16" height="12" rx="2" stroke="currentColor" stroke-width="1.8"/>
            <path d="M8 7l6 4-6 4V7z" fill="currentColor"/>
          </svg>
          Videos
        </button>
      </div>
      
      <!-- Results Count -->
      <p id="gallery-count" style="font-size: 15px; color: #6B7280; font-weight: 500; margin: 0;">
        Showing <span id="count-number">0</span> items
      </p>
    </div>
  </div>
</section>

<!-- Gallery Content Section -->
<section style="background: #F5F6FA; padding: 0 0 100px; position: relative;">
  <div class="inner">
    <!-- Photos Grid -->
    <div class="gallery-grid" id="gallery-photos" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px;">
      <?php if ($use_default_gallery) : ?>
        <?php foreach ($default_gallery as $index => $item) : ?>
        <div class="gallery-item loading" 
             data-album="<?php echo esc_attr($item['category']); ?>" 
             data-type="photo" 
             data-caption="<?php echo esc_attr($item['caption']); ?>" 
             data-src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/Galleries/' . $item['image']); ?>" 
             style="aspect-ratio: 1 / 1; cursor: pointer; position: relative;">
          <img data-src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/Galleries/' . $item['image']); ?>" 
               alt="<?php echo esc_attr($item['caption']); ?>" 
               class="lazy" 
               style="width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .4s; cursor: pointer;" />
          <div class="gallery-item-overlay" style="
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.7) 100%);
            opacity: 0;
            transition: opacity .3s;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 20px;
            pointer-events: none;
          ">
            <div class="gallery-item-title" style="color: #ffffff; font-size: 16px; font-weight: 700; margin-bottom: 4px;">
              <?php echo esc_html($item['caption']); ?>
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
        <div class="gallery-item loading" 
             data-album="<?php echo esc_attr($album_slugs); ?>" 
             data-type="photo" 
             data-caption="<?php echo esc_attr($caption); ?>" 
             data-src="<?php echo esc_url($image_full); ?>" 
             style="aspect-ratio: 1 / 1; cursor: pointer; position: relative;">
          <img data-src="<?php echo esc_url($image_url); ?>" 
               alt="<?php echo esc_attr($caption); ?>" 
               class="lazy" 
               style="width: 100%; height: 100%; object-fit: cover; display: block; transition: transform .4s; cursor: pointer;" />
          <div class="gallery-item-overlay" style="
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.7) 100%);
            opacity: 0;
            transition: opacity .3s;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 20px;
            pointer-events: none;
          ">
            <div class="gallery-item-title" style="color: #ffffff; font-size: 16px; font-weight: 700; margin-bottom: 4px;">
              <?php echo esc_html($caption); ?>
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
    
    <!-- Videos Grid -->
    <div class="gallery-grid" id="gallery-videos" style="display: none; grid-template-columns: repeat(3, 1fr); gap: 24px;">
      <!-- Video items will be populated here via JavaScript or PHP -->
      <div style="grid-column: 1 / -1; text-align: center; padding: 60px 20px; background: #ffffff; border-radius: 16px; border: 2px dashed #E5E7EB;">
        <svg width="64" height="64" viewBox="0 0 64 64" fill="none" style="margin: 0 auto 16px;">
          <circle cx="32" cy="32" r="30" fill="#F3F4F6"/>
          <path d="M26 22l16 10-16 10V22z" fill="#9CA3AF"/>
        </svg>
        <p style="font-size: 18px; color: #6B7280; font-weight: 500;">Video content coming soon!</p>
        <p style="font-size: 14px; color: #9CA3AF; margin-top: 8px;">Check back later for event highlights and program videos</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section style="background: linear-gradient(135deg, #141943 0%, #1e2a5a 100%); padding: 90px 0; position: relative; overflow: hidden;">
  <div aria-hidden="true" style="position: absolute; inset: 0; pointer-events: none; z-index: 0; opacity: 0.06; background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%271920%27 height=%27300%27 fill=%27none%27%3E%3Cpath d=%27M-60 80 C400 -20 800 180 1300 60 S1700 -40 1980 80%27 stroke=%27white%27 stroke-width=%271.2%27/%3E%3Cpath d=%27M-60 160 C400 60 800 260 1300 140 S1700 40 1980 160%27 stroke=%27white%27 stroke-width=%271.2%27/%3E%3Cpath d=%27M-60 240 C400 140 800 340 1300 220 S1700 120 1980 240%27 stroke=%27white%27 stroke-width=%271.2%27/%3E%3C/svg%3E'); background-size: 1920px 300px; background-repeat: no-repeat;"></div>
  <div class="inner" style="position: relative; z-index: 2; text-align: center;">
    <h2 style="font-size: 48px; font-weight: 900; color: #ffffff; line-height: 1.15; letter-spacing: -0.01em; margin-bottom: 20px;">
      <?php echo esc_html(myco_get_field('gallery_cta_heading') ?: 'Be Part of Our Story'); ?>
    </h2>
    <p style="font-size: 19px; color: #B8C8DC; line-height: 1.6; max-width: 680px; margin: 0 auto 40px; font-weight: 400;">
      <?php echo esc_html(myco_get_field('gallery_cta_text') ?: 'Join us in creating more memorable moments and empowering the next generation of Muslim youth'); ?>
    </p>
    <div style="display: flex; gap: 16px; align-items: center; justify-content: center; flex-wrap: wrap;">
      <a href="<?php echo esc_url(myco_get_page_url('donate', '/donate/')); ?>" style="
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #C8402E;
        color: #fff;
        height: 52px;
        padding: 0 32px;
        border-radius: 9999px;
        font-size: 16px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        transition: background .2s, transform .15s, box-shadow .2s;
        box-shadow: 0 4px 18px rgba(200, 64, 46, 0.45);
      " onmouseover="this.style.background='#b03426'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(200, 64, 46, 0.55)'" onmouseout="this.style.background='#C8402E'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 18px rgba(200, 64, 46, 0.45)'">Support Our Programs</a>
      <a href="<?php echo esc_url(home_url('/#volunteer')); ?>" style="
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        color: #fff;
        height: 52px;
        padding: 0 32px;
        border-radius: 9999px;
        border: 2px solid rgba(255, 255, 255, 0.85);
        font-size: 16px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        transition: background .2s, transform .15s;
      " onmouseover="this.style.background='rgba(255, 255, 255, 0.13)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='transparent'; this.style.transform='translateY(0)'">Get Involved</a>
    </div>
  </div>
</section>

<!-- Lightbox Modal -->
<div id="lightbox" class="lightbox" role="dialog" aria-modal="true" aria-label="Image viewer" style="
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.95);
  z-index: 99999;
  display: none;
  align-items: center;
  justify-content: center;
  padding: 60px 40px 40px;
">
  <div class="lightbox-content" style="position: relative; max-width: 90vw; max-height: 85vh; display: flex; flex-direction: column; align-items: center;">
    
    <!-- Close Button - Top Right Corner -->
    <button class="lightbox-close" id="lightbox-close" onclick="closeLightbox()" aria-label="Close lightbox" style="
      position: fixed;
      top: 20px;
      right: 20px;
      width: 48px;
      height: 48px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.15);
      border: 2px solid rgba(255, 255, 255, 0.3);
      color: #ffffff;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all .2s;
      z-index: 100002;
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
    " onmouseover="this.style.background='#C8402E'; this.style.borderColor='#C8402E'; this.style.transform='rotate(90deg)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.15)'; this.style.borderColor='rgba(255, 255, 255, 0.3)'; this.style.transform='rotate(0)'">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
      </svg>
    </button>
    
    <!-- Navigation Buttons -->
    <button class="lightbox-nav prev" id="lightbox-prev" onclick="navigateLightbox(-1)" aria-label="Previous image" style="
      position: fixed;
      top: 50%;
      transform: translateY(-50%);
      left: 30px;
      width: 54px;
      height: 54px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.15);
      border: 2px solid rgba(255, 255, 255, 0.3);
      color: #ffffff;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all .2s;
      z-index: 100001;
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
    " onmouseover="this.style.background='#C8402E'; this.style.borderColor='#C8402E'; this.style.transform='translateY(-50%) scale(1.1)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.15)'; this.style.borderColor='rgba(255, 255, 255, 0.3)'; this.style.transform='translateY(-50%) scale(1)'">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
        <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>
    
    <!-- Image -->
    <img id="lightbox-img" class="lightbox-image" src="" alt="" style="
      max-width: 100%;
      max-height: 100%;
      object-fit: contain;
      border-radius: 12px;
      box-shadow: 0 25px 70px rgba(0, 0, 0, 0.6);
      display: block;
    " />
    
    <button class="lightbox-nav next" id="lightbox-next" onclick="navigateLightbox(1)" aria-label="Next image" style="
      position: fixed;
      top: 50%;
      transform: translateY(-50%);
      right: 30px;
      width: 54px;
      height: 54px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.15);
      border: 2px solid rgba(255, 255, 255, 0.3);
      color: #ffffff;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all .2s;
      z-index: 100001;
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
    " onmouseover="this.style.background='#C8402E'; this.style.borderColor='#C8402E'; this.style.transform='translateY(-50%) scale(1.1)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.15)'; this.style.borderColor='rgba(255, 255, 255, 0.3)'; this.style.transform='translateY(-50%) scale(1)'">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none">
        <path d="M9 18l6-6-6-6" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </button>
    
    <!-- Caption - Bottom Center -->
    <div id="lightbox-caption" class="lightbox-caption" style="
      position: fixed;
      bottom: 30px;
      left: 50%;
      transform: translateX(-50%);
      max-width: 800px;
      text-align: center;
      color: #ffffff;
      font-size: 18px;
      font-weight: 600;
      padding: 16px 32px;
      background: rgba(0, 0, 0, 0.7);
      border-radius: 12px;
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
      z-index: 100001;
    "></div>
  </div>
</div>

<style>
/* Gallery Item Styles */
.gallery-item {
  position: relative;
  border-radius: 16px;
  overflow: hidden;
  cursor: pointer !important;
  background: #E5E7EB;
  transition: transform .3s, box-shadow .3s;
  box-shadow: 0 4px 16px rgba(20, 25, 67, 0.12);
}
.gallery-item * {
  cursor: pointer !important;
}
.gallery-item:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 32px rgba(20, 25, 67, 0.20);
}
.gallery-item img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  transition: transform .4s;
  cursor: pointer !important;
}
.gallery-item:hover img {
  transform: scale(1.08);
}
.gallery-item:hover .gallery-item-overlay {
  opacity: 1;
}
.gallery-item.loading {
  background: linear-gradient(90deg, #E5E7EB 0%, #F3F4F6 50%, #E5E7EB 100%);
  background-size: 200% 100%;
  animation: shimmer 1.5s infinite;
}
@keyframes shimmer {
  0% { background-position: -200% 0; }
  100% { background-position: 200% 0; }
}
.lightbox.active {
  display: flex !important;
}
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
.album-tab:hover {
  border-color: #C8402E;
  color: #C8402E;
}
.album-tab.active {
  background: #C8402E;
  border-color: #C8402E;
  color: #ffffff;
}
/* Media Toggle Styles */
.media-toggle:hover {
  background: rgba(200, 64, 46, 0.1);
  color: #C8402E;
}
.media-toggle.active {
  background: #C8402E !important;
  color: #ffffff !important;
}
@media (max-width: 1100px) {
  .gallery-grid {
    grid-template-columns: repeat(3, 1fr) !important;
  }
}
@media (max-width: 768px) {
  .gallery-grid {
    grid-template-columns: repeat(2, 1fr) !important;
  }
  .lightbox-nav.prev {
    left: 10px !important;
  }
  .lightbox-nav.next {
    right: 10px !important;
  }
  .lightbox-caption {
    bottom: -50px !important;
    font-size: 14px !important;
  }
}
@media (max-width: 520px) {
  .gallery-grid {
    grid-template-columns: 1fr !important;
  }
}
</style>

<script>
// Lazy Loading Images
const lazyImages = document.querySelectorAll('img.lazy');
const imageObserver = new IntersectionObserver((entries, observer) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      const img = entry.target;
      const src = img.getAttribute('data-src');
      if (src) {
        img.src = src;
        img.classList.remove('lazy');
        img.parentElement.classList.remove('loading');
      }
      observer.unobserve(img);
    }
  });
});
lazyImages.forEach(img => imageObserver.observe(img));

// Media Type Toggle
function toggleMediaType(type) {
  const photosGrid = document.getElementById('gallery-photos');
  const videosGrid = document.getElementById('gallery-videos');
  const photosToggle = document.getElementById('toggle-photos');
  const videosToggle = document.getElementById('toggle-videos');
  
  if (type === 'photos') {
    photosGrid.style.display = 'grid';
    videosGrid.style.display = 'none';
    photosToggle.classList.add('active');
    videosToggle.classList.remove('active');
    photosToggle.style.background = '#C8402E';
    photosToggle.style.color = '#ffffff';
    videosToggle.style.background = 'transparent';
    videosToggle.style.color = '#6B7280';
  } else {
    photosGrid.style.display = 'none';
    videosGrid.style.display = 'grid';
    photosToggle.classList.remove('active');
    videosToggle.classList.add('active');
    photosToggle.style.background = 'transparent';
    photosToggle.style.color = '#6B7280';
    videosToggle.style.background = '#C8402E';
    videosToggle.style.color = '#ffffff';
  }
  
  updateCount();
}

// Gallery Filter
function filterGallery(album, button) {
  const items = document.querySelectorAll('.gallery-item');
  const tabs = document.querySelectorAll('.album-tab');
  
  tabs.forEach(tab => {
    tab.classList.remove('active');
  });
  button.classList.add('active');
  
  items.forEach(item => {
    const itemAlbum = item.getAttribute('data-album');
    if (album === 'all' || itemAlbum.includes(album)) {
      item.style.display = 'block';
    } else {
      item.style.display = 'none';
    }
  });
  
  updateCount();
  updateGalleryImages();
}

// Search Functionality
document.addEventListener('DOMContentLoaded', () => {
  const searchInput = document.getElementById('gallery-search');
  if (searchInput) {
    searchInput.addEventListener('input', function(e) {
      const searchTerm = e.target.value.toLowerCase();
      const items = document.querySelectorAll('.gallery-item');
      
      items.forEach(item => {
        const caption = (item.getAttribute('data-caption') || '').toLowerCase();
        if (caption.includes(searchTerm)) {
          item.style.display = 'block';
        } else {
          item.style.display = 'none';
        }
      });
      
      updateCount();
      updateGalleryImages();
    });
  }
});

// Lightbox Functionality
let currentImageIndex = 0;
let galleryImages = [];

function initLightbox() {
  updateGalleryImages();
  
  // Add click listeners using event delegation
  const galleryPhotos = document.getElementById('gallery-photos');
  const galleryVideos = document.getElementById('gallery-videos');
  
  if (galleryPhotos) {
    galleryPhotos.addEventListener('click', (e) => {
      console.log('Click detected on photos grid');
      console.log('Click target:', e.target);
      
      const galleryItem = e.target.closest('.gallery-item');
      console.log('Gallery item found:', galleryItem);
      
      if (galleryItem) {
        const dataSrc = galleryItem.getAttribute('data-src');
        console.log('Data-src:', dataSrc);
        
        if (dataSrc) {
          e.preventDefault();
          e.stopPropagation();
          
          // Get all visible photo items
          const allItems = Array.from(galleryPhotos.querySelectorAll('.gallery-item[data-type="photo"]')).filter(item => {
            return item.style.display !== 'none';
          });
          
          const index = allItems.indexOf(galleryItem);
          console.log('Clicked item index:', index, 'Total items:', allItems.length);
          
          if (index !== -1) {
            // Update gallery images with only visible items
            galleryImages = allItems.map(item => ({
              src: item.getAttribute('data-src'),
              caption: item.getAttribute('data-caption') || 'Gallery Image',
              element: item
            }));
            
            console.log('Gallery images array:', galleryImages);
            openLightbox(index);
          } else {
            console.error('Item not found in array');
          }
        } else {
          console.error('No data-src attribute found');
        }
      } else {
        console.log('No gallery item found');
      }
    });
  }
  
  if (galleryVideos) {
    galleryVideos.addEventListener('click', (e) => {
      const galleryItem = e.target.closest('.gallery-item');
      if (galleryItem && galleryItem.getAttribute('data-src')) {
        e.preventDefault();
        e.stopPropagation();
        
        // Get all visible video items
        const allItems = Array.from(galleryVideos.querySelectorAll('.gallery-item')).filter(item => {
          return item.style.display !== 'none';
        });
        
        const index = allItems.indexOf(galleryItem);
        console.log('Clicked video index:', index, 'Total items:', allItems.length);
        
        if (index !== -1) {
          // Update gallery images with only visible items
          galleryImages = allItems.map(item => ({
            src: item.getAttribute('data-src'),
            caption: item.getAttribute('data-caption') || 'Gallery Image',
            element: item
          }));
          openLightbox(index);
        }
      }
    });
  }
  
  // Keyboard navigation
  document.addEventListener('keydown', (e) => {
    const lightbox = document.getElementById('lightbox');
    if (lightbox && lightbox.classList.contains('active')) {
      if (e.key === 'Escape') closeLightbox();
      if (e.key === 'ArrowLeft') navigateLightbox(-1);
      if (e.key === 'ArrowRight') navigateLightbox(1);
    }
  });
  
  // Close on background click
  const lightbox = document.getElementById('lightbox');
  if (lightbox) {
    lightbox.addEventListener('click', (e) => {
      if (e.target.id === 'lightbox') {
        closeLightbox();
      }
    });
  }
  
  // Update count on load
  setTimeout(() => {
    updateCount();
  }, 200);
}

function updateGalleryImages() {
  const galleryItems = document.querySelectorAll('.gallery-item[data-src]');
  galleryImages = Array.from(galleryItems).map(item => ({
    src: item.getAttribute('data-src'),
    caption: item.getAttribute('data-caption') || 'Gallery Image',
    element: item
  }));
  console.log('Gallery images updated:', galleryImages.length);
}

function updateCount() {
  const photosGrid = document.getElementById('gallery-photos');
  const videosGrid = document.getElementById('gallery-videos');
  const isPhotosVisible = photosGrid.style.display !== 'none';
  
  let visibleItems;
  if (isPhotosVisible) {
    visibleItems = Array.from(photosGrid.querySelectorAll('.gallery-item')).filter(item => {
      return item.style.display !== 'none';
    });
  } else {
    visibleItems = Array.from(videosGrid.querySelectorAll('.gallery-item')).filter(item => {
      return item.style.display !== 'none';
    });
  }
  
  document.getElementById('count-number').textContent = visibleItems.length;
}

function openLightbox(index) {
  console.log('openLightbox called with index:', index);
  console.log('galleryImages:', galleryImages);
  
  if (!galleryImages[index]) {
    console.error('No image at index:', index);
    return;
  }
  
  currentImageIndex = index;
  const lightbox = document.getElementById('lightbox');
  const lightboxImage = document.getElementById('lightbox-image');
  const lightboxCaption = document.getElementById('lightbox-caption');
  const image = galleryImages[index];
  
  console.log('Lightbox element:', lightbox);
  console.log('Lightbox image element:', lightboxImage);
  console.log('Opening lightbox with image:', image);
  
  if (!lightbox || !lightboxImage) {
    console.error('Lightbox elements not found!');
    return;
  }
  
  // Load the full resolution image
  lightboxImage.src = image.src;
  lightboxImage.alt = image.caption;
  lightboxCaption.textContent = image.caption;
  
  lightbox.classList.add('active');
  lightbox.style.display = 'flex';
  document.body.style.overflow = 'hidden';
  
  console.log('Lightbox display:', lightbox.style.display);
  console.log('Lightbox classes:', lightbox.className);
  console.log('Lightbox opened successfully');
}

function closeLightbox() {
  const lightbox = document.getElementById('lightbox');
  lightbox.classList.remove('active');
  lightbox.style.display = 'none';
  document.body.style.overflow = '';
}

function navigateLightbox(direction) {
  if (galleryImages.length === 0) return;
  
  currentImageIndex = currentImageIndex + direction;
  
  if (currentImageIndex < 0) {
    currentImageIndex = galleryImages.length - 1;
  } else if (currentImageIndex >= galleryImages.length) {
    currentImageIndex = 0;
  }
  
  const image = galleryImages[currentImageIndex];
  const lightboxImage = document.getElementById('lightbox-image');
  const lightboxCaption = document.getElementById('lightbox-caption');
  
  lightboxImage.src = image.src;
  lightboxImage.alt = image.caption;
  lightboxCaption.textContent = image.caption;
  
  console.log('Navigated to image:', currentImageIndex);
}

document.addEventListener('DOMContentLoaded', () => {
  console.log('Gallery page loaded');
  initLightbox();
  
  // Debug: Check if gallery items exist
  const items = document.querySelectorAll('.gallery-item');
  console.log('Total gallery items found:', items.length);
  
  // Ensure all gallery items are clickable
  items.forEach((item, index) => {
    console.log(`Item ${index}:`, {
      hasDataSrc: !!item.getAttribute('data-src'),
      dataSrc: item.getAttribute('data-src'),
      caption: item.getAttribute('data-caption')
    });
  });
});
</script>

<?php get_footer(); ?>
