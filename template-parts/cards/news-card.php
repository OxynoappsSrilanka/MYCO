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

<article class="group bg-white rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-2xl" style="box-shadow: 0 4px 20px rgba(20,25,67,0.06); border: 1px solid rgba(20,25,67,0.04);">
    <a href="<?php the_permalink(); ?>" class="block">
        <div class="aspect-[4/3] overflow-hidden bg-gray-100 relative">
            <img src="<?php echo esc_url($img_url); ?>" 
                 alt="<?php echo esc_attr(get_the_title()); ?>" 
                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                 loading="lazy" />
            <?php if ($cat_name) : ?>
            <span class="absolute top-4 left-4 text-xs font-bold uppercase tracking-wider px-4 py-2 rounded-full" 
                  style="background: #C8402E; color: white; box-shadow: 0 2px 8px rgba(200,64,46,0.3);">
                <?php echo esc_html($cat_name); ?>
            </span>
            <?php endif; ?>
        </div>
        <div class="p-6">
            <!-- Date and Author -->
            <div class="flex items-center gap-4 mb-3 text-sm text-gray-500">
                <div class="flex items-center gap-2">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <span><?php echo get_the_date('F j, Y'); ?></span>
                </div>
                <div class="flex items-center gap-2">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    <span>By MYCO Team</span>
                </div>
            </div>
            
            <!-- Title -->
            <h3 class="text-lg font-extrabold mb-3 line-clamp-2 leading-tight group-hover:text-myco-red transition-colors" style="color: #141943; min-height: 3.5rem;">
                <?php the_title(); ?>
            </h3>
            
            <!-- Excerpt -->
            <p class="text-sm text-gray-600 leading-relaxed line-clamp-3 mb-4" style="min-height: 4rem;">
                <?php echo esc_html(wp_trim_words(get_the_excerpt(), 20)); ?>
            </p>
            
            <!-- Read More Link -->
            <div class="flex items-center gap-2 text-myco-red font-bold text-sm group-hover:gap-3 transition-all">
                <span>Read More</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="transition-transform group-hover:translate-x-1">
                    <line x1="5" y1="12" x2="19" y2="12"/>
                    <polyline points="12 5 19 12 12 19"/>
                </svg>
            </div>
        </div>
    </a>
</article>
