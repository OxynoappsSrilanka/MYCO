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
$cta1_url       = myco_get_field('hero_cta_primary_url', false, home_url('/donate/'));
$cta2_text      = myco_get_field('hero_cta_secondary_text', false, 'Explore Programs');
$cta2_url       = myco_get_field('hero_cta_secondary_url', false, home_url('/programs/'));
$hero_image     = myco_get_field('hero_image');
$hero_img_url   = $hero_image ? (is_array($hero_image) ? $hero_image['url'] : wp_get_attachment_url($hero_image)) : MYCO_URI . '/assets/images/hero-image.png';
?>

<section class="w-full bg-white" aria-labelledby="hero-heading">
    <div class="max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Text row: Headline (left) + Paragraph+CTA (right) -->
        <div class="flex flex-col md:flex-row md:items-start gap-8 md:gap-12 pt-8 pb-10 md:pt-10 md:pb-12">
            <!-- LEFT: Big headline -->
            <div class="flex-1 md:max-w-[55%]">
                <h1 id="hero-heading" class="font-inter font-extrabold leading-[1.08] tracking-tight text-navy"
                    style="font-size: clamp(2.6rem, 5.5vw, 4.2rem); font-weight: 800;">
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
            <div class="flex-1 flex flex-col justify-start md:pt-3 gap-7 md:max-w-[42%] hero-text-right">
                <p class="text-gray-600 leading-relaxed"
                    style="font-size: 1.0rem; max-width: 380px; line-height: 1.65;">
                    <?php echo esc_html($paragraph); ?>
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-wrap items-center gap-3">
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
                <img src="<?php echo esc_url($hero_img_url); ?>"
                     alt="<?php esc_attr_e('Muslim youth gathered together, laughing and building community', 'myco'); ?>"
                     class="w-full object-cover object-center"
                     style="height: clamp(240px, 42vw, 540px); display: block;"
                     loading="eager" />
            </div>
        </div>

    </div>
</section>
