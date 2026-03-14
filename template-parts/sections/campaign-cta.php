<?php
/**
 * Campaign CTA Section
 * Full-width background image with overlay card (right-aligned)
 * Matches source index.html exactly
 *
 * @package MYCO
 */

$heading  = myco_get_field('campaign_heading', false, '');
$text     = myco_get_field('campaign_text', false, 'We are on a mission to establish a permanent home for Muslim youth in Central Ohio. A place to learn, grow, and connect in a supportive community.');
$cta_text = myco_get_field('campaign_cta_text', false, 'Learn More');
$cta_url  = myco_get_field('campaign_cta_url', false, home_url('/about/'));
$bg_image = myco_get_field('campaign_bg_image');
$bg_url   = $bg_image ? (is_array($bg_image) ? $bg_image['url'] : wp_get_attachment_url($bg_image)) : 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=1600&h=900&fit=crop&auto=format&q=80';
?>

<section id="campaign-cta" aria-label="<?php esc_attr_e('Build the MCYC Campaign', 'myco'); ?>" style="padding: 0; overflow: hidden; margin-top: -75px;" class="w-full">
    <!-- Outer wrapper -->
    <div class="relative w-full flex items-center justify-end" style="min-height: clamp(380px, 52vw, 620px);">

        <!-- Background Photo -->
        <img src="<?php echo esc_url($bg_url); ?>"
             alt="<?php esc_attr_e('Muslim community members gathered and socializing', 'myco'); ?>"
             class="absolute inset-0 w-full h-full object-cover object-center" style="z-index: 0;" loading="lazy" />

        <!-- Subtle dark gradient overlay -->
        <div class="absolute inset-0"
             style="background: linear-gradient(to right, rgba(0,0,0,0.18) 0%, rgba(0,0,0,0.04) 55%, rgba(0,0,0,0.0) 100%); z-index: 1;"
             aria-hidden="true"></div>

        <!-- Overlay Card -->
        <div class="relative w-full max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 flex justify-center lg:justify-end items-center py-12 lg:py-16"
             style="z-index: 2;">
            <div class="bg-white rounded-3xl flex flex-col gap-5 w-full sm:w-auto" style="max-width: 680px; padding: clamp(36px, 5vw, 64px); box-shadow: 0 12px 60px 0 rgba(20,25,67,0.16), 0 2px 12px 0 rgba(20,25,67,0.08);">

                <!-- Headline -->
                <h2 class="font-inter tracking-tight" style="font-weight: 800; color: #141943; font-size: clamp(1.75rem, 3.5vw, 2.6rem); line-height: 1.12;">
                    <?php if ($heading) : ?>
                        <?php echo nl2br(esc_html($heading)); ?>
                    <?php else : ?>
                        The time is now.<br />
                        Build the MCYC.
                    <?php endif; ?>
                </h2>

                <!-- Body text -->
                <p style="color: #6B7280; font-size: clamp(0.9rem, 1.4vw, 1.02rem); line-height: 1.72; max-width: 420px;">
                    <?php echo esc_html($text); ?>
                </p>

                <!-- CTA text-link -->
                <a href="<?php echo esc_url($cta_url); ?>" style="color: #C8402E; font-weight: 700; font-size: 1.0rem; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; transition: color 0.18s, gap 0.18s;">
                    <?php echo esc_html($cta_text); ?>&nbsp;&rarr;
                </a>
            </div>
        </div>

    </div>
</section>
