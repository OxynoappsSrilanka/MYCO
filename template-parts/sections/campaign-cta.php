<?php
/**
 * Campaign CTA Section
 * Full-width background image with overlay card (right-aligned)
 * Matches source index.html exactly
 *
 * @package MYCO
 */

$heading  = myco_get_field('campaign_heading', false, '');
$text     = myco_get_field('campaign_text', false, 'This visionary center will only be possible through the support of donors who believe in building something lasting for our youth.Whether you choose to give once or become a recurring supporter, your donation plays a direct role in building MCYC.');
$cta_text = myco_get_field('campaign_cta_text', false, 'Learn More');
$cta_url  = myco_get_field('campaign_cta_url', false, home_url('/mcyc/'));
$bg_image = myco_get_field('campaign_bg_image');
$bg_url   = $bg_image ? (is_array($bg_image) ? $bg_image['url'] : wp_get_attachment_url($bg_image)) : 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=1600&h=900&fit=crop&auto=format&q=80';
?>

<section id="campaign-cta" aria-labelledby="campaign-cta-heading" style="padding: 0; overflow: hidden; margin-top: -75px;" class="w-full">
    <!-- Outer wrapper (mirrors outcomes: image + white atmosphere; card stays solid white) -->
    <div class="relative w-full flex items-center justify-end" style="min-height: clamp(380px, 52vw, 620px);">

        <!-- Background Photo -->
        <img src="<?php echo esc_url($bg_url); ?>"
             alt="<?php esc_attr_e('Muslim community members gathered and socializing', 'myco'); ?>"
             class="absolute inset-0 h-full w-full object-cover object-center"
             style="z-index: 0;"
             loading="lazy" />

        <!-- Right white gradient panel (outcomes uses left panel; card here is right-aligned) -->
        <div class="absolute inset-0"
             style="background: linear-gradient(270deg, rgba(255,255,255,1) 0%, rgba(255,255,255,0.95) 18%, rgba(255,255,255,0.32) 30%, rgba(255,255,255,0) 38%); z-index: 1;"
             aria-hidden="true"></div>

        <!-- Bottom white strip — same idea as outcomes section -->
        <div class="absolute bottom-0 left-0 w-full"
             style="height: 20%; background: rgba(255,255,255,0.80); z-index: 1;"
             aria-hidden="true"></div>

        <!-- Overlay Card — solid white, same shadow + border recipe as outcomes -->
        <div class="relative z-10 flex w-full max-w-[1200px] items-center justify-center px-4 py-12 sm:px-6 lg:justify-end lg:px-8 lg:py-16 mx-auto">
            <div class="bg-white rounded-3xl flex flex-col gap-4 w-full text-left items-start sm:w-auto"
                 style="max-width: 500px; padding: clamp(20px, 2.6vw, 34px); box-shadow: 0 18px 60px rgba(20,25,67,0.14), 0 2px 10px rgba(20,25,67,0.06); border: 1px solid rgba(20,25,67,0.05);">

                <!-- Eyebrow label -->
                <span style="color: rgba(200,64,46,0.88); font-weight: 700; font-size: 0.84rem; letter-spacing: 0.04em; display: block; width: 100%;">
                    <?php echo esc_html(myco_get_field('campaign_label', false, 'Campaign') . ' —'); ?>
                </span>

                <!-- Headline -->
                <h2 id="campaign-cta-heading" class="font-inter tracking-tight" style="color: #141943; font-weight: 900; font-size: clamp(1.75rem, 3.2vw, 2.5rem); line-height: 1.06; width: 100%; margin: 0;">
                    <?php if ($heading) : ?>
                        <?php echo nl2br(esc_html($heading)); ?>
                    <?php else : ?>
                        The time is now.<br />
                        Build the MCYC.
                    <?php endif; ?>
                </h2>

                <!-- Body text -->
                <p style="color: #6B7280; font-size: clamp(0.9rem, 1.3vw, 1.0rem); line-height: 1.72; width: 100%; margin: 0;">
                    <?php echo esc_html($text); ?>
                </p>

                <!-- CTA text-link -->
                <a href="<?php echo esc_url($cta_url); ?>" style="color: #C8402E; font-weight: 700; font-size: 1.0rem; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; transition: color 0.18s, gap 0.18s; align-self: flex-start;">
                    <?php echo esc_html($cta_text); ?>&nbsp;&rarr;
                </a>
            </div>
        </div>

    </div>
</section>
