<?php
/**
 * IMPROVED Homepage Hero Section
 * 
 * FIXES:
 * - Removed excessive padding (7rem → responsive)
 * - Better mobile layout
 * - Improved accessibility
 * - Better visual hierarchy
 * - Optimized image loading
 *
 * @package MYCO
 */

$headline       = myco_get_field('hero_headline', false, '');
$paragraph      = myco_get_field('hero_paragraph', false, 'Discover a welcoming community dedicated to building faith, fostering connections, and empowering Muslim youth with confidence for the future.');
$cta1_text      = myco_get_field('hero_cta_primary_text', false, 'Donate Today');
$cta1_url       = myco_get_field('hero_cta_primary_url', false, home_url('/donate/'));
$cta2_text      = myco_get_field('hero_cta_secondary_text', false, 'Explore Programs');
$cta2_url       = myco_get_field('hero_cta_secondary_url', false, home_url('/programs/'));
$hero_image     = myco_get_field('hero_image');
$hero_img_url   = $hero_image ? (is_array($hero_image) ? $hero_image['url'] : wp_get_attachment_url($hero_image)) : MYCO_URI . '/assets/images/hero-image.png';
?>

<section class="hero-section w-full bg-white" aria-labelledby="hero-heading">
    <div class="max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Hero Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 mb-12">
            
            <!-- LEFT: Headline (60% width on desktop) -->
            <div class="lg:col-span-7">
                <h1 id="hero-heading" 
                    class="hero-headline text-balance">
                    <?php if ($headline) : ?>
                        <?php echo nl2br(esc_html($headline)); ?>
                    <?php else : ?>
                        Welcome to the<br />
                        <span style="color: var(--red-600);">Future</span> for Muslim<br />
                        Youth in America.
                    <?php endif; ?>
                </h1>
            </div>

            <!-- RIGHT: Description + CTAs (40% width on desktop) -->
            <div class="lg:col-span-5 flex flex-col justify-center gap-6">
                <p class="text-gray-600 text-lg leading-relaxed max-w-md">
                    <?php echo esc_html($paragraph); ?>
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-wrap items-center gap-4">
                    <a href="<?php echo esc_url($cta1_url); ?>" 
                       class="btn-primary"
                       aria-label="<?php echo esc_attr($cta1_text); ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78Z"/>
                        </svg>
                        <?php echo esc_html($cta1_text); ?>
                    </a>
                    <a href="<?php echo esc_url($cta2_url); ?>" 
                       class="btn-secondary"
                       aria-label="<?php echo esc_attr($cta2_text); ?>">
                        <?php echo esc_html($cta2_text); ?>
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Hero Image -->
        <div class="pb-12 md:pb-16">
            <div class="hero-img-wrap w-full">
                <img src="<?php echo esc_url($hero_img_url); ?>"
                     alt="<?php esc_attr_e('Muslim youth gathered together, laughing and building community', 'myco'); ?>"
                     class="w-full object-cover object-center"
                     style="height: clamp(280px, 40vw, 540px);"
                     width="1380"
                     height="540"
                     loading="eager"
                     fetchpriority="high" />
            </div>
        </div>

    </div>
</section>
