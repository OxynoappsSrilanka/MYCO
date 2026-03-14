<?php
/**
 * Single News Article Template
 *
 * @package MYCO
 */

get_header();

$author_name = myco_get_field('article_author_name', false, get_the_author());
$author_title = myco_get_field('article_author_title');
$read_time = myco_get_field('article_read_time', false, '5 min read');
$cats = get_the_terms(get_the_ID(), 'news_category');
$cat_name = $cats && !is_wp_error($cats) ? $cats[0]->name : '';

$recent_articles = new WP_Query([
    'post_type'      => 'news_article',
    'posts_per_page' => 4,
    'post__not_in'   => [get_the_ID()],
    'orderby'        => 'date',
    'order'          => 'DESC',
]);
?>

<!-- Hero -->
<section class="w-full relative overflow-hidden" style="background: linear-gradient(135deg, #141943 0%, #1e2a5a 50%, #2a3e6a 100%);">
    <div class="inner mx-auto px-4 py-16 md:py-20 relative z-10">
        <?php myco_breadcrumb([
            ['label' => __('Home', 'myco'), 'url' => home_url('/')],
            ['label' => __('News', 'myco'), 'url' => home_url('/news/')],
            ['label' => get_the_title(), 'url' => ''],
        ], 'dark'); ?>
        <?php if ($cat_name) : ?>
        <span class="inline-block text-xs font-semibold uppercase tracking-wider px-3 py-1 rounded-full mb-4" style="background: rgba(200,64,46,0.2); color: #ff8a7a;"><?php echo esc_html($cat_name); ?></span>
        <?php endif; ?>
        <h1 class="font-inter font-extrabold text-white leading-tight mb-4" style="font-size: clamp(2rem, 4.5vw, 3rem); max-width: 800px;">
            <?php the_title(); ?>
        </h1>
        <div class="flex flex-wrap items-center gap-4 mt-4" style="color: rgba(255,255,255,0.6); font-size: 0.95rem;">
            <span><?php echo esc_html($author_name); ?></span>
            <span>&middot;</span>
            <span><?php echo get_the_date(); ?></span>
            <span>&middot;</span>
            <span><?php echo esc_html($read_time); ?></span>
        </div>
    </div>
</section>

<!-- Content -->
<section class="w-full bg-white py-16">
    <div class="inner mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2">
                <?php if (has_post_thumbnail()) : ?>
                <div class="rounded-2xl overflow-hidden mb-10" style="box-shadow: 0 12px 48px rgba(20,25,67,0.12);">
                    <?php the_post_thumbnail('large', ['class' => 'w-full h-auto']); ?>
                </div>
                <?php endif; ?>
                <div class="prose max-w-none text-gray-500 leading-relaxed" style="font-size: 1.05rem; line-height: 1.8;">
                    <?php the_content(); ?>
                </div>
                <!-- Share -->
                <div class="mt-10 pt-8 border-t border-gray-100">
                    <p class="text-sm font-bold mb-3" style="color: #141943;"><?php esc_html_e('Share this article', 'myco'); ?></p>
                    <div class="flex gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" target="_blank" rel="noopener" class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-sky-50 hover:text-sky-500 transition-colors">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="sticky top-8 flex flex-col gap-8">
                    <!-- Recent Articles -->
                    <?php if ($recent_articles->have_posts()) : ?>
                    <div class="bg-white rounded-2xl p-6" style="box-shadow: 0 8px 32px rgba(20,25,67,0.08);">
                        <h3 class="text-lg font-bold mb-6" style="color: #141943;"><?php esc_html_e('Recent Articles', 'myco'); ?></h3>
                        <div class="flex flex-col gap-4">
                            <?php while ($recent_articles->have_posts()) : $recent_articles->the_post(); ?>
                            <a href="<?php the_permalink(); ?>" class="flex items-start gap-3 group">
                                <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0">
                                    <img src="<?php echo esc_url(myco_get_image_url(get_the_ID(), 'thumbnail')); ?>" alt="" class="w-full h-full object-cover" />
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold group-hover:text-myco-red transition-colors line-clamp-2" style="color: #141943;"><?php the_title(); ?></h4>
                                    <span class="text-xs text-gray-400 mt-1"><?php echo get_the_date(); ?></span>
                                </div>
                            </a>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <!-- Newsletter -->
                    <div class="rounded-2xl p-6" style="background: #141943;">
                        <h3 class="text-lg font-bold text-white mb-3"><?php esc_html_e('Newsletter', 'myco'); ?></h3>
                        <p class="text-sm mb-4" style="color: rgba(255,255,255,0.6);"><?php esc_html_e('Get the latest updates delivered to your inbox.', 'myco'); ?></p>
                        <form class="newsletter-form flex flex-col gap-3">
                            <input type="email" placeholder="<?php esc_attr_e('Your email', 'myco'); ?>" required class="px-4 py-2.5 rounded-xl text-sm bg-white/10 border border-white/15 text-white placeholder-white/40 focus:outline-none focus:border-white/30" />
                            <button type="submit" class="pill-primary py-2.5 justify-center text-sm"><?php esc_html_e('Subscribe', 'myco'); ?></button>
                        </form>
                        <p class="newsletter-message text-xs mt-2 min-h-[1em]"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
