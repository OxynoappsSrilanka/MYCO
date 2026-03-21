<?php
/**
 * Single News Article Template
 *
 * @package MYCO
 */

get_header();

$author_name     = myco_get_field('article_author_name', false, get_the_author());
$author_title    = myco_get_field('article_author_title', false, __('MYCO Contributor', 'myco'));
$read_time       = myco_get_field('article_read_time', false, '5 min read');
$cats            = get_the_terms(get_the_ID(), 'news_category');
$cat_name        = $cats && !is_wp_error($cats) ? $cats[0]->name : '';
$category_ids    = $cats && !is_wp_error($cats) ? wp_list_pluck($cats, 'term_id') : [];
$share_url       = rawurlencode(get_permalink());
$share_title     = rawurlencode(get_the_title());
$author_label    = wp_strip_all_tags($author_name);
$author_initial  = $author_label !== ''
    ? (function_exists('mb_strtoupper') && function_exists('mb_substr')
        ? mb_strtoupper(mb_substr($author_label, 0, 1))
        : strtoupper(substr($author_label, 0, 1)))
    : 'M';

$related_args = [
    'post_type'      => 'news_article',
    'posts_per_page' => 3,
    'post__not_in'   => [get_the_ID()],
    'orderby'        => 'date',
    'order'          => 'DESC',
];

if (!empty($category_ids)) {
    $related_args['tax_query'] = [[
        'taxonomy' => 'news_category',
        'field'    => 'term_id',
        'terms'    => $category_ids,
    ]];
}

$related_articles = new WP_Query($related_args);

if (!$related_articles->have_posts()) {
    $related_articles = new WP_Query([
        'post_type'      => 'news_article',
        'posts_per_page' => 3,
        'post__not_in'   => [get_the_ID()],
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);
}
?>

<main class="nsd-page">

    <!-- ── HERO ── -->
    <section class="nsd-hero">
        <div class="nsd-hero__wave" aria-hidden="true"></div>
        <div class="inner nsd-hero__inner">

            <!-- Breadcrumb -->
            <nav class="nsd-breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb', 'myco'); ?>">
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'myco'); ?></a>
                <span aria-hidden="true">›</span>
                <a href="<?php echo esc_url(myco_get_page_url('news', '/news/')); ?>"><?php esc_html_e('News', 'myco'); ?></a>
                <span aria-hidden="true">›</span>
                <span><?php esc_html_e('Article', 'myco'); ?></span>
            </nav>

            <!-- Category chip -->
            <?php if ($cat_name) : ?>
                <span class="nsd-chip"><?php echo esc_html($cat_name); ?></span>
            <?php endif; ?>

            <!-- Title -->
            <h1 class="nsd-hero__title"><?php the_title(); ?></h1>

            <!-- Meta row -->
            <div class="nsd-hero__meta">
                <span class="nsd-hero__meta-item">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    <?php echo esc_html(get_the_date('F j, Y')); ?>
                </span>
                <span class="nsd-hero__meta-item">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <?php printf(esc_html__('By %s', 'myco'), esc_html($author_name)); ?>
                </span>
                <span class="nsd-hero__meta-item">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    <?php echo esc_html($read_time); ?>
                </span>
            </div>

        </div>
    </section>

    <!-- ── BODY ── -->
    <section class="nsd-body">
        <div class="inner nsd-layout">

            <!-- Main article -->
            <article class="nsd-article">
                <div class="nsd-article__prose">
                    <?php the_content(); ?>
                </div>

                <!-- Share row -->
                <div class="nsd-share-row">
                    <span class="nsd-share-row__label"><?php esc_html_e('Share', 'myco'); ?></span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_attr($share_url); ?>" target="_blank" rel="noopener" class="nsd-share-btn">Facebook</a>
                    <a href="https://twitter.com/intent/tweet?url=<?php echo esc_attr($share_url); ?>&text=<?php echo esc_attr($share_title); ?>" target="_blank" rel="noopener" class="nsd-share-btn">X / Twitter</a>
                    <a href="mailto:?subject=<?php echo esc_attr($share_title); ?>&body=<?php echo esc_attr($share_url); ?>" class="nsd-share-btn">Email</a>
                </div>
            </article>

            <!-- Sidebar -->
            <aside class="nsd-sidebar">

                <!-- Author -->
                <div class="nsd-sidebar-card">
                    <p class="nsd-sidebar-card__eyebrow"><?php esc_html_e('Written By', 'myco'); ?></p>
                    <div class="nsd-author">
                        <div class="nsd-author__avatar"><?php echo esc_html($author_initial); ?></div>
                        <div>
                            <div class="nsd-author__name"><?php echo esc_html($author_name); ?></div>
                            <div class="nsd-author__role"><?php echo esc_html($author_title); ?></div>
                        </div>
                    </div>
                    <p class="nsd-sidebar-card__copy" style="margin-top:16px;">
                        <?php esc_html_e('MYCO stories spotlight community momentum, youth leadership, and the people building lasting change together.', 'myco'); ?>
                    </p>
                </div>

                <!-- Share -->
                <div class="nsd-sidebar-card">
                    <p class="nsd-sidebar-card__eyebrow"><?php esc_html_e('Share This Story', 'myco'); ?></p>
                    <div class="nsd-sidebar-share">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_attr($share_url); ?>" target="_blank" rel="noopener" class="nsd-share-btn">Facebook</a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo esc_attr($share_url); ?>&text=<?php echo esc_attr($share_title); ?>" target="_blank" rel="noopener" class="nsd-share-btn nsd-share-btn--alt">X / Twitter</a>
                        <a href="mailto:?subject=<?php echo esc_attr($share_title); ?>&body=<?php echo esc_attr($share_url); ?>" class="nsd-share-btn nsd-share-btn--alt">Email</a>
                    </div>
                </div>

                <!-- Newsletter -->
                <div class="nsd-sidebar-card nsd-sidebar-card--dark">
                    <p class="nsd-sidebar-card__eyebrow nsd-sidebar-card__eyebrow--light"><?php esc_html_e('Stay Updated', 'myco'); ?></p>
                    <h3 class="nsd-sidebar-card__title nsd-sidebar-card__title--light"><?php esc_html_e('Get the latest from MYCO', 'myco'); ?></h3>
                    <p class="nsd-sidebar-card__copy nsd-sidebar-card__copy--light">
                        <?php esc_html_e('Subscribe for news, events, and community stories.', 'myco'); ?>
                    </p>
                    <form class="nsd-newsletter-form" action="#" method="post">
                        <input type="email" name="email" placeholder="<?php esc_attr_e('Your email address', 'myco'); ?>" required class="nsd-newsletter-form__input" />
                        <button type="submit" class="nsd-newsletter-form__btn"><?php esc_html_e('Subscribe', 'myco'); ?></button>
                    </form>
                </div>

                <!-- Back to news -->
                <a href="<?php echo esc_url(myco_get_page_url('news', '/news/')); ?>" class="nsd-back-link">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"/></svg>
                    <?php esc_html_e('Back to News Hub', 'myco'); ?>
                </a>

            </aside>
        </div>
    </section>

    <!-- ── RELATED ── -->
    <?php if ($related_articles->have_posts()) : ?>
    <section class="nsd-related">
        <div class="inner">
            <div class="nsd-related__header">
                <div>
                    <span class="nsd-chip"><?php esc_html_e('More Stories', 'myco'); ?></span>
                    <h2 class="nsd-related__title"><?php esc_html_e('Keep exploring the latest from MYCO', 'myco'); ?></h2>
                </div>
                <a href="<?php echo esc_url(myco_get_page_url('news', '/news/')); ?>" class="pill-secondary"><?php esc_html_e('View All News', 'myco'); ?></a>
            </div>
            <div class="news-grid">
                <?php while ($related_articles->have_posts()) : $related_articles->the_post(); ?>
                    <?php get_template_part('template-parts/cards/news-card'); ?>
                <?php endwhile; ?>
            </div>
        </div>
    </section>
    <?php wp_reset_postdata(); ?>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
