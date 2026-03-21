<?php
/**
 * News Card
 *
 * @package MYCO
 */

$img_url     = myco_get_image_url(get_the_ID(), 'myco-card');
$read_time   = myco_get_field('article_read_time', false, '5 min read');
$cats        = get_the_terms(get_the_ID(), 'news_category');
$cat_name    = $cats && !is_wp_error($cats) ? $cats[0]->name : '';
$excerpt     = has_excerpt() ? get_the_excerpt() : wp_trim_words(wp_strip_all_tags(get_the_content()), 22);
$article_url = get_permalink();
?>

<article class="news-story-card">
    <a href="<?php echo esc_url($article_url); ?>" class="news-story-card__media" aria-label="<?php echo esc_attr(get_the_title()); ?>">
        <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="w-full h-full object-cover" loading="lazy" />
    </a>
    <div class="news-story-card__body">
        <?php if ($cat_name) : ?>
        <span class="news-story-chip"><?php echo esc_html($cat_name); ?></span>
        <?php endif; ?>
        <h3 class="news-story-card__title">
            <a href="<?php echo esc_url($article_url); ?>" class="transition-colors"><?php the_title(); ?></a>
        </h3>
        <p class="news-story-card__excerpt line-clamp-3"><?php echo esc_html($excerpt); ?></p>
        <div class="news-story-card__meta">
            <span><?php echo get_the_date(); ?></span>
            <span>&middot;</span>
            <span><?php echo esc_html($read_time); ?></span>
        </div>
        <a href="<?php echo esc_url($article_url); ?>" class="news-story-card__cta pill-primary"><?php esc_html_e('Read Full Story', 'myco'); ?></a>
    </div>
</article>
