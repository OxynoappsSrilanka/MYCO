<?php
/**
 * About: Vision Section
 * @package MYCO
 */
$vision_image = myco_get_field('about_vision_image');
$fallback     = ! empty( $fallback_image ) ? $fallback_image : myco_theme_asset_url( 'assets/images/Galleries/myco-youth-community-center-groundbreaking-ceremony.jpg' );
$image_url    = $vision_image ? wp_get_attachment_image_url( $vision_image, 'large' ) : $fallback;
$badge_number = myco_get_field('about_vision_badge_number') ?: '10+';
$badge_text = myco_get_field('about_vision_badge_text') ?: "Years Serving\nOur Community";
?>
<section id="vision" class="w-full bg-gray-50 py-16 md:py-20">
  <div class="max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8">
    <div class="content-grid grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

      <!-- LEFT: Image -->
      <div class="relative">
        <div class="rounded-3xl overflow-hidden" style="box-shadow: 0 12px 48px rgba(20, 25, 67, 0.12);">
          <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr(myco_get_field('about_vision_image_alt') ?: 'MYCO Vision - Muslim youth community'); ?>" class="w-full h-auto" />
        </div>
        <!-- Floating badge -->
        <div class="absolute -bottom-6 right-8 rounded-2xl px-8 py-6" style="box-shadow: 0 12px 40px rgba(20, 25, 67, 0.25); border: 4px solid #fff; background: #fff;">
          <div class="text-center">
            <div class="text-5xl font-black leading-none mb-2" style="color: #C8402E;"><?php echo esc_html($badge_number); ?></div>
            <div class="text-sm font-bold leading-tight" style="color: #141943;"><?php echo nl2br(esc_html($badge_text)); ?></div>
          </div>
        </div>
      </div>

      <!-- RIGHT: Content -->
      <div>
        <div class="flex items-center gap-2 mb-4">
          <span class="text-xs font-semibold uppercase tracking-wider" style="color: #C8402E;">Our Vision</span>
        </div>
        <h2 class="font-inter font-black leading-tight tracking-tight mb-6" style="font-size: clamp(2.5rem, 5vw, 4rem); color: #141943;">
          <?php echo esc_html(myco_get_field('about_vision_heading') ?: 'A Future Where Muslim Youth Find Belonging, Balance, and Joy'); ?>
        </h2>
        <p class="text-gray-500 leading-relaxed mb-5" style="font-size: 1.05rem; line-height: 1.7;">
          <?php echo esc_html(myco_get_field('about_vision_text_1') ?: 'A future where Muslim youth find belonging, balance, and joy in living Islam, becoming a generation of faith-driven leaders and role models for society.'); ?>
        </p>
        <p class="text-gray-500 leading-relaxed mb-8" style="font-size: 1.05rem; line-height: 1.7;">
          <?php echo esc_html(myco_get_field('about_vision_text_2') ?: 'Muslim Youth of Central Ohio is building a space where Muslim youth can grow spiritually, socially, academically, and physically. Our inclusive and supportive approach blends engaging and immersive programs with strong Islamic values and mentorship.'); ?>
        </p>
        <div class="flex flex-wrap items-center gap-3">
          <a href="<?php echo esc_url(myco_get_page_url('donate', '/donate/')); ?>" class="btn-primary">Support Our Vision</a>
          <a href="#mission" class="btn-secondary">Our Mission</a>
        </div>
      </div>

    </div>
  </div>
</section>
