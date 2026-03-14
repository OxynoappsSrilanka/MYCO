<?php
/**
 * About: Video Section
 * @package MYCO
 */
$youtube_url = myco_get_field('about_video_url') ?: 'https://www.youtube.com/embed/JoZgHXWELL0';
$caption_quote = myco_get_field('about_video_caption') ?: 'Our commitment is to create spaces where Muslim youth can grow, thrive, and lead with purpose.';
$caption_author = myco_get_field('about_video_caption_author') ?: 'MYCO Leadership Team';
?>
<section id="video" class="w-full bg-white py-16 md:py-20">
  <div class="max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Header -->
    <div class="text-center mb-12 md:mb-14">
      <div class="flex items-center justify-center gap-2 mb-4">
        <span class="text-xs font-semibold uppercase tracking-wider" style="color: #C8402E;">Leadership Message</span>
      </div>
      <h2 class="font-inter font-black leading-tight tracking-tight mb-5" style="font-size: clamp(2.5rem, 5vw, 4rem); color: #141943;">
        <?php echo esc_html(myco_get_field('about_video_heading') ?: 'Hear From Our Leadership'); ?>
      </h2>
      <p class="text-gray-500 leading-relaxed max-w-2xl mx-auto" style="font-size: 1.1rem; line-height: 1.7;">
        <?php echo esc_html(myco_get_field('about_video_description') ?: 'Watch our executive director share MYCO\'s vision and impact'); ?>
      </p>
    </div>

    <!-- Video Container -->
    <div class="max-w-4xl mx-auto">
      <div class="video-wrapper">
        <iframe src="<?php echo esc_url($youtube_url); ?>" title="MYCO Leadership Message" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>
      </div>
      <div class="text-center mt-6 px-4">
        <p class="text-sm text-gray-500 italic leading-relaxed">"<?php echo esc_html($caption_quote); ?>"</p>
        <p class="text-xs text-gray-400 mt-2 font-semibold">&mdash; <?php echo esc_html($caption_author); ?></p>
      </div>
    </div>

  </div>
</section>
