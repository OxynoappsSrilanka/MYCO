<?php
/**
 * News Card
 *
 * @package MYCO
 */

$img_url   = myco_get_image_url(get_the_ID(), 'myco-card');
$read_time = myco_get_field('article_read_time', false, '5 min read');
$cats      = get_the_terms(get_the_ID(), 'news_category');
$cat_name  = $cats && !is_wp_error($cats) ? $cats[0]->name : '';
?>

<div class="bg-white rounded-2xl overflow-hidden transition-all duration-200"
     style="box-shadow: 0 8px 24px rgba(20,25,67,0.08);"
     onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 36px rgba(20,25,67,0.14)'"
     onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 24px rgba(20,25,67,0.08)'">
    <div class="aspect-[16/10] overflow-hidden">
        <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="w-full h-full object-cover" loading="lazy" />
    </div>
    <div class="p-6">
        <?php if ($cat_name) : ?>
        <span class="inline-block text-xs font-semibold uppercase tracking-wider px-3 py-1 rounded-full mb-3" style="background: rgba(200,64,46,0.1); color: #C8402E;"><?php echo esc_html($cat_name); ?></span>
        <?php endif; ?>
        <h3 class="text-lg font-bold mb-2" style="color: #141943;">
            <a href="<?php the_permalink(); ?>" class="hover:text-myco-red transition-colors"><?php the_title(); ?></a>
        </h3>
        <p class="text-sm text-gray-500 leading-relaxed line-clamp-2 mb-4"><?php echo esc_html(get_the_excerpt()); ?></p>
        <div class="flex items-center gap-4 text-xs text-gray-400">
            <span><?php echo get_the_date(); ?></span>
            <span>&middot;</span>
            <span><?php echo esc_html($read_time); ?></span>
        </div>
    </div>
</div>
