<?php
/**
 * Single Program Template
 *
 * @package MYCO
 */

get_header();

$categories = get_the_terms(get_the_ID(), 'program_category');
$cat_name = $categories && !is_wp_error($categories) ? $categories[0]->name : '';
$schedule = myco_get_field('program_schedule');
$age_group = myco_get_field('program_age_group');
$location = myco_get_field('program_location');
$fee = myco_get_field('program_fee', false, 'Free');
$features = myco_get_field('program_features');
$related = myco_get_field('related_programs');
$img_url = myco_get_image_url(get_the_ID(), 'large');
?>

<!-- Hero -->
<section class="w-full relative overflow-hidden" style="background: linear-gradient(135deg, #141943 0%, #1e2a5a 50%, #2a3e6a 100%);">
    <div class="inner mx-auto px-4 py-16 md:py-20 relative z-10">
        <?php myco_breadcrumb([
            ['label' => __('Home', 'myco'), 'url' => home_url('/')],
            ['label' => __('Programs', 'myco'), 'url' => home_url('/programs/')],
            ['label' => get_the_title(), 'url' => ''],
        ], 'dark'); ?>
        <?php if ($cat_name) : ?>
        <span class="inline-block text-xs font-semibold uppercase tracking-wider px-3 py-1 rounded-full mb-4" style="background: rgba(200,64,46,0.2); color: #ff8a7a;"><?php echo esc_html($cat_name); ?></span>
        <?php endif; ?>
        <h1 class="font-inter font-extrabold text-white leading-tight mb-4" style="font-size: clamp(2.2rem, 5vw, 3.5rem);">
            <?php the_title(); ?>
        </h1>
    </div>
</section>

<!-- Content -->
<section class="w-full bg-white py-16">
    <div class="inner mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <?php if (has_post_thumbnail()) : ?>
                <div class="rounded-2xl overflow-hidden mb-10" style="box-shadow: 0 12px 48px rgba(20,25,67,0.12);">
                    <?php the_post_thumbnail('large', ['class' => 'w-full h-auto']); ?>
                </div>
                <?php endif; ?>
                <div class="prose max-w-none text-gray-500 leading-relaxed" style="font-size: 1.05rem; line-height: 1.8;">
                    <?php the_content(); ?>
                </div>
                <?php if ($features && is_array($features)) : ?>
                <div class="mt-10">
                    <h3 class="text-xl font-bold mb-6" style="color: #141943;"><?php esc_html_e('Program Features', 'myco'); ?></h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($features as $f) : ?>
                        <div class="flex items-start gap-3 p-4 rounded-xl bg-gray-50">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5" style="background: #C8402E;">
                                <svg width="12" height="10" viewBox="0 0 12 10" fill="none"><path d="M1 5L4.5 8.5L11 1.5" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold" style="color: #141943;"><?php echo esc_html($f['title']); ?></h4>
                                <p class="text-sm text-gray-500 mt-1"><?php echo esc_html($f['description']); ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl p-6 sticky top-8" style="box-shadow: 0 8px 32px rgba(20,25,67,0.10);">
                    <h3 class="text-lg font-bold mb-6" style="color: #141943;"><?php esc_html_e('Program Details', 'myco'); ?></h3>
                    <div class="flex flex-col gap-4">
                        <?php if ($schedule) : ?>
                        <div class="flex items-center gap-3">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#C8402E" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                            <div><div class="text-xs text-gray-400 font-medium"><?php esc_html_e('Schedule', 'myco'); ?></div><div class="text-sm font-semibold" style="color: #141943;"><?php echo esc_html($schedule); ?></div></div>
                        </div>
                        <?php endif; ?>
                        <?php if ($age_group) : ?>
                        <div class="flex items-center gap-3">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#C8402E" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                            <div><div class="text-xs text-gray-400 font-medium"><?php esc_html_e('Age Group', 'myco'); ?></div><div class="text-sm font-semibold" style="color: #141943;"><?php echo esc_html($age_group); ?></div></div>
                        </div>
                        <?php endif; ?>
                        <?php if ($location) : ?>
                        <div class="flex items-center gap-3">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#C8402E" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            <div><div class="text-xs text-gray-400 font-medium"><?php esc_html_e('Location', 'myco'); ?></div><div class="text-sm font-semibold" style="color: #141943;"><?php echo esc_html($location); ?></div></div>
                        </div>
                        <?php endif; ?>
                        <div class="flex items-center gap-3">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#C8402E" stroke-width="2"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                            <div><div class="text-xs text-gray-400 font-medium"><?php esc_html_e('Fee', 'myco'); ?></div><div class="text-sm font-semibold" style="color: #141943;"><?php echo esc_html($fee); ?></div></div>
                        </div>
                    </div>
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="pill-primary w-full justify-center mt-6"><?php esc_html_e('Register Now', 'myco'); ?></a>
                </div>
            </div>
        </div>

        <!-- Related Programs -->
        <?php if ($related && is_array($related)) : ?>
        <div class="mt-16 pt-12 border-t border-gray-100">
            <h3 class="text-2xl font-black mb-8" style="color: #141943;"><?php esc_html_e('Related Programs', 'myco'); ?></h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($related as $rp) :
                    $GLOBALS['post'] = $rp;
                    setup_postdata($rp);
                    get_template_part('template-parts/cards/program-card');
                endforeach;
                wp_reset_postdata(); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
