<?php
/**
 * About: Right People Section
 * @package MYCO
 */
$image     = myco_get_field('about_people_image');
$fallback  = ! empty( $fallback_image ) ? $fallback_image : myco_theme_asset_url( 'assets/images/volunteers.jpg' );
$image_url = $image ? wp_get_attachment_image_url( $image, 'large' ) : $fallback;
$stats = myco_get_field('about_people_stats');
$default_stats = array(
    array('number' => '110+', 'label' => 'Active Mentors'),
    array('number' => '560+', 'label' => 'Volunteer Hours'),
);
if (!$stats) $stats = $default_stats;
?>
<section id="right-people" class="w-full bg-white py-16 md:py-20">
  <div class="max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8">
    <div class="content-grid grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">

      <!-- LEFT: Content -->
      <div>
        <div class="flex items-center gap-2 mb-4">
          <span class="text-xs font-semibold uppercase tracking-wider" style="color: #C8402E;">The Right People</span>
        </div>
        <h2 class="font-inter font-black leading-tight tracking-tight mb-6" style="font-size: clamp(2.5rem, 5vw, 4rem); color: #141943;">
          <?php echo esc_html(myco_get_field('about_people_heading') ?: 'The Right People'); ?>
        </h2>
        <p class="text-gray-500 leading-relaxed mb-8" style="font-size: 1.05rem; line-height: 1.7;">
          <?php echo esc_html(myco_get_field('about_people_text_1') ?: 'Facilities alone don\'t transform lives. People do. MYCO\'s programs are led by mentors grounded in Islamic knowledge, who guide youth through each experience with wisdom and purpose. These mentors role model what it means to live Islam with confidence and balance, translating values into lived habits, from the court to the café.'); ?>
        </p>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 gap-4 mb-8">
          <?php foreach ($stats as $stat) : ?>
          <div class="bg-white rounded-2xl p-6" style="box-shadow: 0 4px 16px rgba(20, 25, 67, 0.08);">
            <div class="text-4xl font-black leading-none mb-2" style="color: #C8402E;"><?php echo esc_html($stat['number']); ?></div>
            <div class="text-sm font-semibold" style="color: #141943;"><?php echo esc_html($stat['label']); ?></div>
          </div>
          <?php endforeach; ?>
        </div>

        <div class="flex items-center gap-4 mt-8">
          <a href="<?php echo esc_url(myco_get_page_url('volunteer', '/volunteer/')); ?>" class="btn-primary">Become a Mentor</a>
          <a href="<?php echo esc_url(myco_get_contact_page_url()); ?>" class="btn-secondary">Meet Our Team</a>
        </div>
      </div>

      <!-- RIGHT: Image -->
      <div class="relative">
        <div class="rounded-3xl overflow-hidden" style="box-shadow: 0 24px 60px rgba(20, 25, 67, 0.18);">
          <img src="<?php echo esc_url($image_url); ?>" alt="MYCO Mentors and Volunteers" class="w-full h-auto" />
        </div>
        <div aria-hidden="true" class="absolute -top-8 -right-8 w-40 h-40 rounded-full -z-10" style="background: rgba(200, 64, 46, 0.12);"></div>
        <div aria-hidden="true" class="absolute -bottom-5 -left-5 w-30 h-30 rounded-full -z-10" style="background: rgba(20, 25, 67, 0.08);"></div>
      </div>

    </div>
  </div>
</section>
