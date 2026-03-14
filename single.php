<?php
/**
 * Default Single Post Template
 *
 * @package MYCO
 */

get_header();
?>

<main class="w-full bg-white py-16">
    <div class="inner mx-auto px-4">
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class(); ?>>
                <h1 class="font-inter font-extrabold text-navy-dark mb-4" style="font-size: clamp(2rem, 4.5vw, 3.5rem);">
                    <?php the_title(); ?>
                </h1>
                <div class="text-sm text-gray-400 mb-8">
                    <?php echo get_the_date(); ?> &middot; <?php the_author(); ?>
                </div>
                <?php if (has_post_thumbnail()) : ?>
                    <div class="rounded-2xl overflow-hidden mb-10" style="box-shadow: 0 12px 48px rgba(20,25,67,0.12);">
                        <?php the_post_thumbnail('large', ['class' => 'w-full h-auto']); ?>
                    </div>
                <?php endif; ?>
                <div class="prose max-w-none text-gray-500 leading-relaxed" style="font-size: 1.05rem; line-height: 1.8;">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
