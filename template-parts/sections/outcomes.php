<?php
/**
 * Outcomes Section
 * Matches source index.html - rounded image stage with floating white card
 * @package MYCO
 */
$heading = myco_get_field('outcomes_heading', false, '');
$text = myco_get_field('outcomes_text', false, 'Designed to support mind, body and spirit. Our multipurpose gym, cafe, co-working center, and training rooms create environments for growth, mentorship, and connection—ensuring faith and friendship are part of everyday life.');
$cta_text = myco_get_field('outcomes_cta_text', false, 'Learn More');
$cta_url = myco_get_field('outcomes_cta_url', false, home_url('/about/'));
$bg_image = myco_get_field('outcomes_bg_image');
$bg_url = $bg_image ? (is_array($bg_image) ? $bg_image['url'] : wp_get_attachment_url($bg_image)) : 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=1400&h=700&fit=crop&auto=format&q=95';
?>

<section id="outcomes" aria-labelledby="outcomes-heading"
    class="w-full bg-[#ffffff] pt-16 pb-20 md:pt-20 md:pb-24">
    <div class="outcomes-container mx-auto">

        <!-- Big Rounded Stage Wrapper -->
        <div class="relative w-full overflow-hidden rounded-3xl" style="min-height: clamp(300px, 38vw, 460px); box-shadow: 0 14px 44px rgba(20,25,67,0.12), 0 2px 8px rgba(20,25,67,0.06); background: #FAFAFB;">

            <!-- Background image -->
            <img src="<?php echo esc_url($bg_url); ?>"
                 alt="<?php esc_attr_e('Muslim youth students studying and collaborating', 'myco'); ?>"
                 class="absolute inset-0 w-full h-full object-cover"
                 style="object-position: 70% center; z-index: 0;" loading="lazy" />

            <!-- Left white gradient panel -->
            <div class="absolute inset-0" style="background: linear-gradient(90deg, rgba(255,255,255,1) 0%, rgba(255,255,255,0.95) 20%, rgba(255,255,255,0.3) 28%, rgba(255,255,255,0.0) 35%); z-index: 1;" aria-hidden="true"></div>

            <!-- Bottom white strip -->
            <div class="absolute bottom-0 left-0 w-full" style="height: 20%; background: rgba(255,255,255,0.80); z-index: 1;" aria-hidden="true"></div>

            <!-- Overlay Card -->
            <div class="relative z-10 flex items-center h-full p-4 sm:p-6 md:p-7 lg:p-8">
                <div class="bg-white rounded-3xl flex flex-col gap-4 w-full" style="max-width: 660px; padding: clamp(20px, 2.6vw, 34px); box-shadow: 0 18px 60px rgba(20,25,67,0.14), 0 2px 10px rgba(20,25,67,0.06); border: 1px solid rgba(20,25,67,0.05); text-align: left; align-items: flex-start;">

                    <span style="color: rgba(200,64,46,0.88); font-weight: 700; font-size: 0.84rem; letter-spacing: 0.04em; display: block; width: 100%;">
                        <?php echo esc_html(myco_get_field('outcomes_label', false, 'Outcomes') . ' —'); ?>
                    </span>

                    <h2 id="outcomes-heading" class="font-inter tracking-tight" style="color: #141943; font-weight: 900; font-size: clamp(1.9rem, 3.6vw, 2.9rem); line-height: 1.06; width: 100%; margin: 0;">
                        <?php if ($heading) { echo nl2br(esc_html($heading)); } else { ?>This space is built to<br />shape habits,<br />relationships, and identity<?php } ?>
                    </h2>

                    <p style="color: #6B7280; font-size: clamp(0.9rem, 1.3vw, 1.0rem); line-height: 1.72; width: 100%; margin: 0;">
                        <?php echo esc_html($text); ?>
                    </p>

                    <a href="<?php echo esc_url($cta_url); ?>" style="color: #C8402E; font-weight: 700; font-size: 1.0rem; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; transition: color 0.18s, gap 0.18s; align-self: flex-start;">
                        <?php echo esc_html($cta_text); ?>&nbsp;&rarr;
                    </a>
                </div>
            </div>

        </div>

    </div>
</section>
