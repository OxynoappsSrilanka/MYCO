<?php
/**
 * Template Name: News
 *
 * @package MYCO
 */

get_header();
get_template_part('template-parts/hero/hero-breadcrumb-dark');

$categories = get_terms(['taxonomy' => 'news_category', 'hide_empty' => true]);

// Featured article
$featured = new WP_Query([
    'post_type'      => 'news_article',
    'posts_per_page' => 1,
    'meta_key'       => 'featured_article',
    'meta_value'     => '1',
]);

// All articles
$articles = new WP_Query([
    'post_type'      => 'news_article',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);
?>

<section class="w-full bg-white py-16 md:py-20">
    <div class="inner mx-auto px-4">

        <!-- Featured Article -->
        <?php if ($featured->have_posts()) : while ($featured->have_posts()) : $featured->the_post();
            $img_url = myco_get_image_url(get_the_ID(), 'large');
            $read_time = myco_get_field('article_read_time', false, '5 min read');
            $cats = get_the_terms(get_the_ID(), 'news_category');
            $cat_name = $cats && !is_wp_error($cats) ? $cats[0]->name : '';
        ?>
        <div class="mb-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center bg-gray-50 rounded-3xl overflow-hidden" style="box-shadow: 0 8px 32px rgba(20,25,67,0.08);">
                <div class="aspect-[16/10] overflow-hidden">
                    <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="w-full h-full object-cover" />
                </div>
                <div class="p-8 lg:p-10">
                    <?php if ($cat_name) : ?>
                    <span class="inline-block text-xs font-semibold uppercase tracking-wider px-3 py-1 rounded-full mb-4" style="background: rgba(200,64,46,0.1); color: #C8402E;"><?php echo esc_html($cat_name); ?></span>
                    <?php endif; ?>
                    <h2 class="text-2xl lg:text-3xl font-black mb-4" style="color: #141943;">
                        <a href="<?php the_permalink(); ?>" class="hover:text-myco-red transition-colors"><?php the_title(); ?></a>
                    </h2>
                    <p class="text-gray-500 leading-relaxed mb-6 line-clamp-3"><?php echo esc_html(get_the_excerpt()); ?></p>
                    <div class="flex items-center gap-4 text-sm text-gray-400">
                        <span><?php echo get_the_date(); ?></span>
                        <span>&middot;</span>
                        <span><?php echo esc_html($read_time); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; wp_reset_postdata(); endif; ?>

        <!-- Filter Tabs -->
        <?php if ($categories && !is_wp_error($categories)) : ?>
        <div class="flex flex-wrap items-center gap-3 mb-10">
            <button class="filter-tab active" onclick="filterNews('all', this)"><?php esc_html_e('All', 'myco'); ?></button>
            <?php foreach ($categories as $cat) : ?>
            <button class="filter-tab" onclick="filterNews('<?php echo esc_attr($cat->slug); ?>', this)"><?php echo esc_html($cat->name); ?></button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- News Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="news-grid">
            <?php if ($articles->have_posts()) : while ($articles->have_posts()) : $articles->the_post();
                $cats = get_the_terms(get_the_ID(), 'news_category');
                $cat_slugs = $cats && !is_wp_error($cats) ? implode(' ', wp_list_pluck($cats, 'slug')) : '';
            ?>
            <div class="news-item" data-categories="<?php echo esc_attr($cat_slugs); ?>">
                <?php get_template_part('template-parts/cards/news-card'); ?>
            </div>
            <?php endwhile; wp_reset_postdata();
            else : ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 80px 20px; background: #F5F6FA; border-radius: 24px;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#D1D5DB" stroke-width="1.5" style="margin: 0 auto 20px; display: block;"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8M15 18h-5M10 6h8v4h-8z"/></svg>
                <h3 style="font-size: 22px; font-weight: 800; color: #141943; margin-bottom: 12px;"><?php esc_html_e('No Articles Yet', 'myco'); ?></h3>
                <p style="font-size: 16px; color: #6B7280; margin-bottom: 28px; max-width: 400px; margin-left: auto; margin-right: auto;"><?php esc_html_e("We're working on bringing you the latest MYCO news and community stories. Check back soon!", 'myco'); ?></p>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="pill-primary"><?php esc_html_e('Back to Home', 'myco'); ?></a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php get_template_part('template-parts/sections/newsletter-signup'); ?>

<script>
function filterNews(cat, btn) {
    document.querySelectorAll('.filter-tab').forEach(function(t) { t.classList.remove('active'); });
    if (btn) btn.classList.add('active');
    document.querySelectorAll('.news-item').forEach(function(item) {
        if (cat === 'all' || item.dataset.categories.indexOf(cat) !== -1) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}
</script>

<?php get_footer(); ?>
