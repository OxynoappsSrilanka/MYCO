<?php
/**
 * Hero: Light Breadcrumb (About page)
 * @package MYCO
 */
$args = wp_parse_args($args, array(
    'title' => get_the_title(),
    'subtitle' => '',
));
?>
<section class="page-inner-hero w-full relative overflow-hidden" style="background: linear-gradient(135deg, #141943 0%, #1e2a5a 50%, #2a3e6a 100%);" aria-labelledby="hero-heading">
    <!-- Wave decoration -->
    <div aria-hidden="true" class="absolute inset-0 pointer-events-none opacity-[0.06]"
         style="background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='1920' height='300' fill='none'%3E%3Cpath d='M-60 80 C400 -20 800 180 1300 60 S1700 -40 1980 80' stroke='white' stroke-width='1.2'/%3E%3Cpath d='M-60 160 C400 60 800 260 1300 140 S1700 40 1980 160' stroke='white' stroke-width='1.2'/%3E%3C/svg%3E&quot;); background-size: 1920px 300px; background-repeat: no-repeat;"></div>
  <div class="max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16 relative z-10">

    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 mb-6">
      <a href="<?php echo esc_url(home_url('/')); ?>" class="text-sm font-medium hover:text-white transition-colors" style="color: rgba(255,255,255,0.65);">Home</a>
      <svg width="6" height="10" viewBox="0 0 6 10" fill="none">
        <path d="M1 1l4 4-4 4" stroke="rgba(255,255,255,0.5)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      <span class="text-sm font-semibold text-white"><?php echo esc_html($args['title']); ?></span>
    </div>

    <!-- Page Title -->
    <h1 id="hero-heading" class="font-inter font-extrabold leading-tight tracking-tight mb-4 text-white" style="font-size: clamp(2.5rem, 5.5vw, 4rem);">
      <?php echo esc_html($args['title']); ?>
    </h1>

    <?php if (!empty($args['subtitle'])) : ?>
    <p class="leading-relaxed max-w-2xl" style="color: rgba(255,255,255,0.65); font-size: 1.1rem; line-height: 1.7;">
      <?php echo esc_html($args['subtitle']); ?>
    </p>
    <?php endif; ?>

  </div>
</section>
