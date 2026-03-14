<?php
/**
 * Default Page Template
 *
 * @package MYCO
 */

get_header();
?>

<main class="w-full bg-white py-16">
    <div class="inner mx-auto px-4">
        <?php while (have_posts()) : the_post(); ?>
            <h1 class="font-inter font-extrabold text-navy-dark mb-8" style="font-size: clamp(2.5rem, 5vw, 4rem);">
                <?php the_title(); ?>
            </h1>
            <div class="prose max-w-none text-gray-500 leading-relaxed" style="font-size: 1.05rem; line-height: 1.8;">
                <?php the_content(); ?>
            </div>
        <?php endwhile; ?>
    </div>
</main>

<?php get_footer(); ?>
