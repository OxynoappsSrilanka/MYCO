<?php
/**
 * Ready to Build - Donation CTA Section
 *
 * @package MYCO
 */
?>
<section class="w-full py-20 md:py-24" style="background: linear-gradient(135deg, #141943 0%, #1e2a5a 50%, #2a3e6a 100%);">
    <div class="max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl md:text-5xl font-black text-white mb-6 tracking-tight">
            <?php esc_html_e('Ready to Build?', 'myco'); ?>
        </h2>
        <p class="text-xl text-white/80 mb-4 max-w-3xl mx-auto leading-relaxed">
            <?php esc_html_e('This visionary center will only be possible through the support of donors who believe in building something lasting for our youth.', 'myco'); ?>
        </p>
        <p class="text-lg text-white/70 mb-10 max-w-2xl mx-auto">
            <?php esc_html_e('Whether you choose to give once or become a recurring supporter, your donation plays a direct role in building MCYC.', 'myco'); ?>
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <a href="<?php echo esc_url(home_url('/donate/')); ?>" class="btn-primary text-lg px-10 py-4">
                <?php esc_html_e('Donate Now', 'myco'); ?>
            </a>
            <a href="<?php echo esc_url(home_url('/about/')); ?>" class="btn-secondary text-lg px-10 py-4" style="border-color: rgba(255,255,255,0.3); color: #fff;">
                <?php esc_html_e('Learn More', 'myco'); ?>
            </a>
        </div>
    </div>
</section>
