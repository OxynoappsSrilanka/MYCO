<?php
/**
 * Default Template
 *
 * @package MYCO
 */

get_header();
?>

<main class="w-full bg-white py-16">
    <div class="inner mx-auto px-4">
        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post(); ?>
                <article <?php post_class('mb-12'); ?>>
                    <h2 class="text-3xl font-black text-navy-dark mb-4">
                        <a href="<?php the_permalink(); ?>" class="hover:text-myco-red transition-colors">
                            <?php the_title(); ?>
                        </a>
                    </h2>
                    <div class="text-gray-500 leading-relaxed">
                        <?php the_excerpt(); ?>
                    </div>
                </article>
            <?php endwhile; ?>
            <?php the_posts_pagination(); ?>
        <?php else : ?>
            <p class="text-gray-500"><?php esc_html_e('No content found.', 'myco'); ?></p>
        <?php endif; ?>
    </div>
</main>

<?php get_footer(); ?>
