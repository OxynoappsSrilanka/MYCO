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

    <!-- ── HERO BANNER WITH BLURRED BACKGROUND (Gallery Style) ── -->
    <section style="
        background: url('<?php echo esc_url(has_post_thumbnail() ? get_the_post_thumbnail_url(get_the_ID(), 'full') : get_template_directory_uri() . '/assets/images/hero-image.png'); ?>') center center / cover no-repeat;
        padding: 140px 0;
        position: relative;
        overflow: hidden;
    ">
        <!-- Blur Overlay -->
        <div style="
            position: absolute;
            inset: 0;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            background: rgba(20, 25, 67, 0.75);
            z-index: 1;
        "></div>
        
        <!-- Content -->
        <div style="position: relative; z-index: 2; text-align: center; max-width: 1200px; margin: 0 auto; padding: 0 40px;">
            <!-- Breadcrumb -->
            <nav class="nsd-breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb', 'myco'); ?>" style="display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 24px;">
                <a href="<?php echo esc_url(home_url('/')); ?>" style="font-size: 14px; font-weight: 500; color: rgba(255,255,255,0.75); text-decoration: none; transition: color .2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.75)'"><?php esc_html_e('Home', 'myco'); ?></a>
                <svg width="6" height="10" viewBox="0 0 6 10" fill="none" aria-hidden="true">
                    <path d="M1 1l4 4-4 4" stroke="rgba(255,255,255,0.6)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <a href="<?php echo esc_url(myco_get_page_url('news', '/news/')); ?>" style="font-size: 14px; font-weight: 500; color: rgba(255,255,255,0.75); text-decoration: none; transition: color .2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.75)'"><?php esc_html_e('News', 'myco'); ?></a>
                <svg width="6" height="10" viewBox="0 0 6 10" fill="none" aria-hidden="true">
                    <path d="M1 1l4 4-4 4" stroke="rgba(255,255,255,0.6)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span style="font-size: 14px; font-weight: 600; color: #ffffff;"><?php esc_html_e('Article', 'myco'); ?></span>
            </nav>

            <!-- Centered Content -->
            <div style="display: flex; flex-direction: column; align-items: center; gap: 20px; max-width: 1100px; margin: 0 auto;">
                <!-- Category chip -->
                <?php if ($cat_name) : ?>
                    <span style="
                        display: inline-block;
                        padding: 7px 16px;
                        border-radius: 9999px;
                        background: #C8402E;
                        color: #fff;
                        font-size: 12px;
                        font-weight: 700;
                        letter-spacing: 0.04em;
                        text-transform: uppercase;
                        box-shadow: 0 4px 16px rgba(200, 64, 46, 0.4);
                    "><?php echo esc_html($cat_name); ?></span>
                <?php endif; ?>

                <!-- Title -->
                <h1 style="
                    font-size: 72px;
                    font-weight: 900;
                    color: #ffffff;
                    line-height: 1.1;
                    letter-spacing: -0.02em;
                    margin: 0;
                    text-align: center;
                    max-width: 900px;
                    text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
                "><?php the_title(); ?></h1>

                <!-- Meta row -->
                <div style="
                    display: flex;
                    flex-wrap: wrap;
                    align-items: center;
                    justify-content: center;
                    gap: 24px;
                    color: rgba(255,255,255,0.95);
                    font-size: 15px;
                    font-weight: 500;
                ">
                    <span style="display: flex; align-items: center; gap: 8px;">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                            <rect x="2" y="3" width="14" height="12" rx="2" stroke="rgba(255,255,255,0.75)" stroke-width="1.5"/>
                            <path d="M2 6h14M5 1v4M13 1v4" stroke="rgba(255,255,255,0.75)" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <?php echo esc_html(get_the_date('F j, Y')); ?>
                    </span>
                    <span style="display: flex; align-items: center; gap: 8px;">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                            <circle cx="9" cy="6" r="3" stroke="rgba(255,255,255,0.75)" stroke-width="1.5"/>
                            <path d="M2 16c0-3.866 3.134-7 7-7s7 3.134 7 7" stroke="rgba(255,255,255,0.75)" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <?php printf(esc_html__('By %s', 'myco'), esc_html($author_name)); ?>
                    </span>
                    <span style="display: flex; align-items: center; gap: 8px;">
                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                            <circle cx="9" cy="9" r="7" stroke="rgba(255,255,255,0.75)" stroke-width="1.5"/>
                            <path d="M9 5v4l3 2" stroke="rgba(255,255,255,0.75)" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                        <?php echo esc_html($read_time); ?>
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- ── BODY ── -->
    <section class="nsd-body">
        <div class="inner nsd-layout">

            <!-- Main article -->
            <article class="nsd-article">
                
                <!-- Featured Image -->
                <?php if (has_post_thumbnail()) : ?>
                    <div class="nsd-article__featured-image">
                        <?php the_post_thumbnail('full', ['alt' => get_the_title()]); ?>
                    </div>
                <?php endif; ?>

                <!-- Article Body -->
                <div class="nsd-article__prose">
                    <?php the_content(); ?>
                </div>

                <!-- Share Section -->
                <div class="nsd-share-section">
                    <h3 class="nsd-share-section__title"><?php esc_html_e('Share This Article', 'myco'); ?></h3>
                    <div class="nsd-share-row">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_attr($share_url); ?>" target="_blank" rel="noopener" class="nsd-share-icon nsd-share-icon--facebook" aria-label="<?php esc_attr_e('Share on Facebook', 'myco'); ?>">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M13 7h2.5l-.5 2.5h-2V18h-3v-8.5H8V7h2V5.5c0-2.5 1.5-4 4-4 .7 0 1.5.1 2 .2V4h-1.5c-1 0-1.5.5-1.5 1.5V7z" fill="#fff"/>
                            </svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo esc_attr($share_url); ?>&text=<?php echo esc_attr($share_title); ?>" target="_blank" rel="noopener" class="nsd-share-icon nsd-share-icon--twitter" aria-label="<?php esc_attr_e('Share on Twitter', 'myco'); ?>">
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <path d="M14 2h2.5l-5.5 6.3L17 16h-5l-4-5.2L4 16H1.5l5.9-6.7L1 2h5.1l3.6 4.8L14 2zm-.9 12.5h1.4L5.6 3.4H4.1l9 11.1z" fill="#fff"/>
                            </svg>
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo esc_attr($share_url); ?>" target="_blank" rel="noopener" class="nsd-share-icon nsd-share-icon--linkedin" aria-label="<?php esc_attr_e('Share on LinkedIn', 'myco'); ?>">
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <path d="M4 2h3v12H4V2zm1.5 13c-1 0-1.5-.5-1.5-1.5S4.5 12 5.5 12 7 12.5 7 13.5 6.5 15 5.5 15zM10 6h2.5v1.5c.5-1 1.5-1.5 2.5-1.5 2 0 3 1.5 3 3.5V14h-3v-4c0-1-.5-1.5-1.5-1.5S12 9 12 10v4h-3V6z" fill="#fff"/>
                            </svg>
                        </a>
                        <a href="https://wa.me/?text=<?php echo esc_attr($share_title . ' ' . $share_url); ?>" target="_blank" rel="noopener" class="nsd-share-icon nsd-share-icon--whatsapp" aria-label="<?php esc_attr_e('Share on WhatsApp', 'myco'); ?>">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M10 2c-4.4 0-8 3.6-8 8 0 1.4.4 2.8 1 4l-1 3.7 3.8-1c1.2.6 2.5 1 3.9 1 4.4 0 8-3.6 8-8s-3.6-8-7.7-8zm4.5 11.3c-.2.5-1 1-1.4 1-.4 0-.8.2-2.7-.6-2.3-1-3.7-3.3-3.8-3.5-.1-.2-.9-1.2-.9-2.3s.6-1.6.8-1.8c.2-.2.4-.3.6-.3h.4c.1 0 .3 0 .5.4.2.4.6 1.5.7 1.6.1.1.1.3 0 .4-.1.2-.2.3-.3.4l-.3.4c-.1.1-.2.3-.1.5.1.2.6 1 1.3 1.6.9.8 1.6 1 1.8 1.1.2.1.4.1.5-.1.1-.2.5-.6.7-.8.2-.2.3-.1.5-.1.2.1 1.2.6 1.4.7.2.1.4.2.4.3.1.2.1.7-.1 1.2z" fill="#fff"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </article>

            <!-- Sidebar -->
            <aside class="nsd-sidebar">

                <!-- Author -->
                <div class="nsd-sidebar-card">
                    <h3 class="nsd-sidebar-card__heading"><?php esc_html_e('About the Author', 'myco'); ?></h3>
                    <div class="nsd-author__name"><?php echo esc_html($author_name); ?></div>
                    <div class="nsd-sidebar-card__copy">
                        <?php esc_html_e('The MYCO communications team shares stories of impact, community events, and youth success.', 'myco'); ?>
                    </div>
                    <a href="<?php echo esc_url(myco_get_page_url('contact', '/contact/')); ?>" class="nsd-sidebar-link">
                        <?php esc_html_e('Contact Us', 'myco'); ?>
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                            <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>

                <!-- Related Articles -->
                <?php if ($related_articles->have_posts()) : ?>
                <div class="nsd-sidebar-card">
                    <h3 class="nsd-sidebar-card__heading"><?php esc_html_e('Related Articles', 'myco'); ?></h3>
                    <div class="nsd-related-list">
                        <?php while ($related_articles->have_posts()) : $related_articles->the_post(); ?>
                            <?php
                            $rel_cats = get_the_terms(get_the_ID(), 'news_category');
                            $rel_cat_name = $rel_cats && !is_wp_error($rel_cats) ? $rel_cats[0]->name : '';
                            ?>
                            <a href="<?php the_permalink(); ?>" class="nsd-related-item">
                                <div class="nsd-related-item__category"><?php echo esc_html($rel_cat_name); ?></div>
                                <h4 class="nsd-related-item__title"><?php the_title(); ?></h4>
                                <p class="nsd-related-item__date"><?php echo esc_html(get_the_date('F j, Y')); ?></p>
                            </a>
                        <?php endwhile; ?>
                    </div>
                    <a href="<?php echo esc_url(myco_get_page_url('news', '/news/')); ?>" class="nsd-sidebar-link">
                        <?php esc_html_e('View All News', 'myco'); ?>
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none">
                            <path d="M2 7h10M8 3l4 4-4 4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
                <?php wp_reset_postdata(); ?>
                <?php endif; ?>

                <!-- Newsletter -->
                <div class="nsd-sidebar-card nsd-sidebar-card--dark">
                    <h3 class="nsd-sidebar-card__heading nsd-sidebar-card__heading--light"><?php esc_html_e('Stay Updated', 'myco'); ?></h3>
                    <p class="nsd-sidebar-card__copy nsd-sidebar-card__copy--light">
                        <?php esc_html_e('Subscribe to our newsletter for the latest news, events, and stories from MYCO.', 'myco'); ?>
                    </p>
                    <form class="nsd-newsletter-form" action="#" method="post">
                        <input type="email" name="email" placeholder="<?php esc_attr_e('Your email address', 'myco'); ?>" required class="nsd-newsletter-form__input" />
                        <button type="submit" class="nsd-newsletter-form__btn"><?php esc_html_e('Subscribe', 'myco'); ?></button>
                    </form>
                    <p class="nsd-newsletter-form__privacy">
                        <?php esc_html_e('We respect your privacy. Unsubscribe at any time.', 'myco'); ?>
                    </p>
                </div>

            </aside>
        </div>
    </section>



</main>

<?php get_footer(); ?>
