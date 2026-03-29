<?php
/**
 * About: Right Space Section
 * @package MYCO
 */
$image     = myco_get_field('about_space_image');
$fallback  = ! empty( $fallback_image ) ? $fallback_image : myco_theme_asset_url( 'assets/images/right_space.png' );
$image_url = $image ? wp_get_attachment_image_url( $image, 'large' ) : $fallback;
$features = myco_get_field('about_space_features');
$defaults = array(
    'Multipurpose gym and wellness areas for physical health',
    'Full kitchen and café for shared meals and connection',
    'Co-working center and training rooms for career development',
    'Gaming room and sim racing studio for play and teamwork',
);
if (!$features) {
    $features = array();
    foreach ($defaults as $d) { $features[] = array('text' => $d); }
}
?>
<section id="right-space" class="w-full bg-gray-50 py-16 md:py-20">
  <div class="max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8">
    <div class="content-grid grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

      <!-- LEFT: Image -->
      <div class="relative">
        <div class="rounded-3xl overflow-hidden" style="box-shadow: 0 12px 48px rgba(20, 25, 67, 0.12);">
          <img src="<?php echo esc_url($image_url); ?>" alt="MYCO Community Space" class="w-full h-auto" />
        </div>
      </div>

      <!-- RIGHT: Content -->
      <div>
        <div class="flex items-center gap-2 mb-4">
          <span class="text-xs font-semibold uppercase tracking-wider" style="color: #C8402E;">The Right Space</span>
        </div>
        <h2 class="font-inter font-black leading-tight tracking-tight mb-6" style="font-size: clamp(2.5rem, 5vw, 4rem); color: #141943;">
          <?php echo esc_html(myco_get_field('about_space_heading') ?: 'The Right Space'); ?>
        </h2>
        <p class="text-gray-500 leading-relaxed mb-8" style="font-size: 1.05rem; line-height: 1.7;">
          <?php echo esc_html(myco_get_field('about_space_text_1') ?: 'MYCO\'s Capital Campaign Project of the Muslim Community Youth Center is intentionally designed to support holistic growth: mind, body and spirit. The multipurpose gym, full kitchen, and wellness areas promote health and connection through physical activity and shared meals. The café, co-working center, and training rooms create environments for career exploration, mentorship, and networking. The gaming room and sim racing studio invite play, creativity, and teamwork, ensuring that faith and friendship are experienced as part of everyday life.'); ?>
        </p>

        <!-- Features List -->
        <div class="flex flex-col gap-3 mb-8">
          <?php foreach ($features as $feature) :
            $text = is_array($feature) ? $feature['text'] : $feature;
          ?>
          <div class="flex items-center gap-3">
            <div class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0" style="background: rgba(200, 64, 46, 0.15);">
              <svg width="10" height="8" viewBox="0 0 10 8" fill="none">
                <path d="M1 4L3.5 6.5L9 1" stroke="#C8402E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <span class="text-sm font-semibold" style="color: #141943;"><?php echo esc_html($text); ?></span>
          </div>
          <?php endforeach; ?>
        </div>

        <a href="<?php echo esc_url(myco_get_page_url('volunteer', '/volunteer/')); ?>" class="btn-primary">Visit Our Space</a>
      </div>

    </div>
  </div>
</section>
