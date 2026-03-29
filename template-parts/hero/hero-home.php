<?php
/**
 * Homepage Hero Section
 * Matches source index.html hero layout exactly
 *
 * @package MYCO
 */

$headline       = myco_get_field('hero_headline', false, '');
$paragraph      = myco_get_field('hero_paragraph', false, 'Muslim Youth of Central Ohio (MYCO) is building a state of the art facility dedicated to supporting the next generation of Muslims through spiritual growth, leadership development, education, and athletics, providing a safe space for Muslim youth to thrive in Ohio for generations more to come.');
$cta1_text      = myco_get_field('hero_cta_primary_text', false, 'Donate Today');
$cta1_url       = myco_get_field('hero_cta_primary_url', false, myco_get_page_url('donate', '/donate/'));
$cta2_text      = myco_get_field('hero_cta_secondary_text', false, 'Explore Programs');
$cta2_url       = myco_get_field('hero_cta_secondary_url', false, myco_get_page_url('programs', '/programs/'));
$hero_image     = myco_get_field('hero_image');
$hero_img_url   = $hero_image ? (is_array($hero_image) ? $hero_image['url'] : wp_get_attachment_url($hero_image)) : myco_theme_asset_url('assets/images/hero_video_thumbnail.png');
$hero_video_rel = 'assets/images/herobanneryoung.mp4';
$hero_video_url = myco_theme_asset_url($hero_video_rel);
$hero_video_ok  = file_exists(get_theme_file_path($hero_video_rel));
?>

<section class="w-full bg-white" aria-labelledby="hero-heading">
    <div class="hero-home-container mx-auto">

        <!-- Text row: Headline (left) + Paragraph+CTA (right) -->
        <div class="hero-centerline-row flex flex-col gap-8 pt-0 pb-4 md:pt-0 md:pb-5">
            <!-- LEFT: Big headline -->
            <div class="hero-centerline-left">
                <h1 id="hero-heading" class="font-inter font-extrabold leading-[1.08] tracking-tight text-navy"
                    style="font-size: clamp(2.8rem, 6vw, 4.7rem); font-weight: 800;">
                    <?php if ($headline) : ?>
                        <?php echo nl2br(esc_html($headline)); ?>
                    <?php else : ?>
                        Welcome To The<br />
                        <span style="color: #c8402e;">Future</span> For Muslim<br />
                        Youth In America
                    <?php endif; ?>
                </h1>
            </div>

            <!-- RIGHT: Paragraph + CTA buttons -->
            <div class="hero-centerline-right flex flex-col justify-start gap-7">
                <p class="text-gray-600 leading-relaxed"
                    style="font-size: 1.1rem; max-width: 100%; line-height: 1.65; text-align: justify; text-align-last: left;">
                    <?php echo esc_html($paragraph); ?>
                </p>

                <!-- CTA Buttons -->
                <div class="hero-cta-group flex flex-wrap items-center gap-3">
                    <a href="<?php echo esc_url($cta1_url); ?>" class="btn-primary">
                        <?php echo esc_html($cta1_text); ?>
                    </a>
                    <a href="<?php echo esc_url($cta2_url); ?>" class="btn-secondary">
                        <?php echo esc_html($cta2_text); ?>
                    </a>
                </div>
            </div>
        </div>

        <!-- Hero Image -->
        <div class="pb-12 md:pb-16">
            <div class="hero-img-wrap w-full">
                <?php if ($hero_video_ok) : ?>
                    <video class="hero-home-media"
                           autoplay
                           muted
                           loop
                           playsinline
                           preload="metadata"
                           poster="<?php echo esc_url($hero_img_url); ?>"
                           aria-label="<?php esc_attr_e('Muslim youth gathered together, building community', 'myco'); ?>">
                        <source src="<?php echo esc_url($hero_video_url); ?>" type="video/mp4" />
                        <img src="<?php echo esc_url($hero_img_url); ?>"
                             alt="<?php esc_attr_e('Muslim youth gathered together, laughing and building community', 'myco'); ?>"
                             class="hero-home-media"
                             loading="eager" />
                    </video>
                <?php else : ?>
                    <img src="<?php echo esc_url($hero_img_url); ?>"
                         alt="<?php esc_attr_e('Muslim youth gathered together, laughing and building community', 'myco'); ?>"
                         class="hero-home-media"
                         loading="eager" />
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>
