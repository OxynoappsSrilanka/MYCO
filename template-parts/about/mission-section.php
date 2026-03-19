<?php
/**
 * About: Mission Section
 * @package MYCO
 */
$mission_image = myco_get_field('about_mission_image');
$image_url = $mission_image ? wp_get_attachment_image_url($mission_image, 'large') : myco_get_image_url('hero-image.png');
$mission_points = myco_get_field('about_mission_points');
$defaults = array(
    array('title' => 'Inclusive & Supportive Approach', 'description' => 'Blending engaging and immersive programs with strong Islamic values and mentorship'),
    array('title' => 'Holistic Growth', 'description' => 'Supporting spiritual, social, academic, and physical development'),
    array('title' => 'Faith & Friendship', 'description' => 'Creating spaces where Muslim youth find belonging, balance, and joy in living Islam'),
);
if (!$mission_points) $mission_points = $defaults;
?>
<section id="mission" class="w-full bg-white py-16 md:py-20">
  <div class="max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8">
    <div class="content-grid grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

      <!-- LEFT: Content -->
      <div>
        <div class="flex items-center gap-2 mb-4">
          <span class="text-xs font-semibold uppercase tracking-wider" style="color: #C8402E;">Our Mission</span>
        </div>
        <h2 class="font-inter font-black leading-tight tracking-tight mb-6" style="font-size: clamp(2.5rem, 5vw, 4rem); color: #141943;">
          <?php echo esc_html(myco_get_field('about_mission_heading') ?: 'MYCO\'s Mission'); ?>
        </h2>
        <p class="text-gray-500 leading-relaxed mb-8" style="font-size: 1.05rem; line-height: 1.7;">
          <?php echo esc_html(myco_get_field('about_mission_text_1') ?: 'MYCO aims to create experiences that help Muslim youth strengthen their faith, build lasting friendships, and develop the confidence and character to lead lives of faith and purpose.'); ?>
        </p>

        <!-- Mission Points -->
        <div class="flex flex-col gap-4 mb-8">
          <?php foreach ($mission_points as $point) : ?>
          <div class="flex items-start gap-3">
            <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0 mt-1" style="background: #C8402E;">
              <svg width="12" height="10" viewBox="0 0 12 10" fill="none">
                <path d="M1 5L4.5 8.5L11 1.5" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <div>
              <h3 class="text-base font-bold mb-1" style="color: #141943;"><?php echo esc_html($point['title']); ?></h3>
              <p class="text-sm text-gray-500"><?php echo esc_html($point['description']); ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <a href="<?php echo esc_url(myco_get_page_url('programs', '/programs/')); ?>" class="btn-primary">Explore Our Programs</a>
      </div>

      <!-- RIGHT: Image -->
      <div class="relative">
        <div class="rounded-3xl overflow-hidden" style="box-shadow: 0 12px 48px rgba(20, 25, 67, 0.12);">
          <img src="<?php echo esc_url($image_url); ?>" alt="MYCO Mission - Youth mentorship" class="w-full h-auto" />
        </div>
      </div>

    </div>
  </div>
</section>
