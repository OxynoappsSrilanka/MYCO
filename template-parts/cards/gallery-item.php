<?php
/**
 * Gallery Item Card
 * @package MYCO
 */
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
