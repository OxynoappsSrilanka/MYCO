<?php
/**
 * News Card
 *
 * Used in: archive-news_article.php, template-news.php, single-news_article.php (related)
 *
 * @package MYCO
 */

$img_url    = myco_get_image_url(get_the_ID(), 'myco-card');
$read_time  = myco_get_field('article_read_time', false, '5 min read');
$cats       = get_the_terms(get_the_ID(), 'news_category');
$cat_name   = $cats && !is_wp_error($cats) ? $cats[0]->name : '';
$excerpt    = has_excerpt() ? get_the_excerpt() : wp_trim_words(wp_strip_all_tags(get_the_content()), 20);
$permalink  = get_permalink();
?>

<article class="news-story-card">

    <!-- Image -->
    <a href="<?php echo esc_url($permalink); ?>"
       class="news-story-card__media"
       tabindex="-1"
       aria-hidden="true">
        <img
            src="<?php echo esc_url($img_url); ?>"
            alt="<?php echo esc_attr(get_the_title()); ?>"
            loading="lazy" />
        <?php if ($cat_name) : ?>
        <span class="news-story-card__badge"><?php echo esc_html($cat_name); ?></span>
        <?php endif; ?>
    </a>

    <!-- Body -->
    <div class="news-story-card__body">

        <?php if ($cat_name) : ?>
        <span class="news-story-chip"><?php echo esc_html($cat_name); ?></span>
        <?php endif; ?>

        <h3 class="news-story-card__title">
            <a href="<?php echo esc_url($permalink); ?>"><?php the_title(); ?></a>
        </h3>

        <?php if ($excerpt) : ?>
        <p class="news-story-card__excerpt"><?php echo esc_html($excerpt); ?></p>
        <?php endif; ?>

        <div class="news-story-card__meta">
            <span><?php echo esc_html(get_the_date()); ?></span>
            <span aria-hidden="true">&middot;</span>
            <span><?php echo esc_html($read_time); ?></span>
        </div>

        <a href="<?php echo esc_url($permalink); ?>"
           class="news-story-card__cta pill-primary"
           aria-label="<?php echo esc_attr(sprintf(__('Read full story: %s', 'myco'), get_the_title())); ?>">
            <?php esc_html_e('Read Full Story', 'myco'); ?>
        </a>

    </div>

</article>
