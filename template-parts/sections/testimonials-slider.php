<?php
/**
 * Testimonials Slider Section
 * Updated with community leaders testimonials and round profile images
 * @package MYCO
 */
$testimonials = get_posts(['post_type' => 'testimonial', 'posts_per_page' => 12, 'orderby' => 'menu_order', 'order' => 'ASC']);
$defaults = [
    ['quote' => 'MYCO is building a space where Muslim youth can grow in faith, confidence, and strong community.', 'name' => 'Br. Abdurahman Abdala', 'role' => 'Community Leader', 'image' => 'Br_Abdurahman_Abdala.png'],
    ['quote' => 'MYCO is creating a powerful space where Muslim youth can grow in faith, leadership, and community.', 'name' => 'Sh. Nasir Jungda', 'role' => 'Islamic Scholar', 'image' => 'Sh_Nasir_Jungda.png'],
    ['quote' => 'MYCO is nurturing the next generation of Muslim leaders through guidance, mentorship, and community.', 'name' => 'Nasser Karimian', 'role' => 'Community Leader', 'image' => 'Nasser_Karimian.png'],
    ['quote' => 'MYCO is helping young Muslims strengthen their identity while staying connected to their faith and community.', 'name' => 'Suhaib Webb', 'role' => 'Islamic Scholar', 'image' => 'Suhaib_Webb.png'],
    ['quote' => 'Supporting MYCO means investing in a future where Muslim youth thrive spiritually and socially.', 'name' => 'Nasser Karimian', 'role' => 'Al Huda Center – Indiana', 'image' => 'Nasser_Karimian.png'],
    ['quote' => 'MYCO provides a welcoming environment where youth can learn, connect, and grow together.', 'name' => 'Mufti Kamani', 'role' => 'Islamic Scholar', 'image' => 'Mufti_Kamani.png'],
    ['quote' => 'MYCO is empowering Muslim youth with knowledge, leadership, and a strong sense of purpose.', 'name' => 'Mufti Kamani', 'role' => 'Islamic Scholar', 'image' => 'Mufti_Kamani.png'],
];
$use_defaults = empty($testimonials);
$items = $use_defaults ? $defaults : $testimonials;
$per_page = 2;
$pages = array_chunk($items, $per_page);
$star_svg = '<svg width="20" height="20" viewBox="0 0 20 20" fill="#C8402E" aria-hidden="true"><path d="M10 1l2.39 4.84 5.35.78-3.87 3.77.91 5.32L10 13.27l-4.78 2.44.91-5.32L2.26 6.62l5.35-.78z"/></svg>';
?>

<section id="testimonials" aria-labelledby="testimonials-heading"
    class="w-full bg-[#F3F4F6] pt-16 pb-20 md:pt-20 md:pb-24">
    <div class="max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Section Header (centered) -->
        <div class="flex flex-col items-center text-center mb-12 md:mb-14 gap-3">
            <span style="color: #C8402E; font-weight: 700; font-size: 0.88rem; letter-spacing: 0.05em;">
                <?php echo esc_html(myco_get_field('testimonials_label', false, 'Testimonials')); ?>
            </span>
            <h2 id="testimonials-heading" class="font-inter tracking-tight"
                style="color: #141943; font-weight: 800; font-size: clamp(1.9rem, 4.5vw, 3.0rem); line-height: 1.1; max-width: 680px;">
                <?php echo esc_html(myco_get_field('testimonials_heading', false, 'What Our Community Says')); ?>
            </h2>
        </div>

        <!-- Slider viewport -->
        <div class="overflow-hidden" id="testi-viewport" aria-live="polite">
            <div class="testi-track" id="testi-track">
                <?php foreach ($pages as $pi => $page) : ?>
                <div class="testi-page">
                    <?php foreach ($page as $item) :
                        if ($use_defaults) {
                            $quote = $item['quote'];
                            $name = $item['name'];
                            $role = $item['role'];
                            $image = $item['image'];
                        } else {
                            $quote = myco_get_field('testimonial_quote', $item->ID, '');
                            $name = get_the_title($item->ID);
                            $role = myco_get_field('testimonial_role', $item->ID, '');
                            $image = myco_get_field('testimonial_image', $item->ID, '');
                        }
                        $image_url = $image ? MYCO_URI . '/assets/images/Testimonials/' . $image : '';
                    ?>
                    <div class="testi-card">
                        <span class="testi-watermark" aria-hidden="true">&#8220;&#8221;</span>
                        <div class="testi-content">
                            <div class="testi-stars" aria-label="5 out of 5 stars">
                                <?php echo str_repeat($star_svg, 5); ?>
                            </div>
                            <p class="testi-quote">&ldquo;<?php echo esc_html($quote); ?>&rdquo;</p>
                            <div class="testi-author">
                                <?php if ($image_url) : ?>
                                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($name); ?>" class="testi-avatar" />
                                <?php endif; ?>
                                <div>
                                    <p class="testi-author-name"><?php echo esc_html($name); ?></p>
                                    <p class="testi-author-role"><?php echo esc_html($role); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Pagination dots (left-aligned) -->
        <div class="testi-dots" id="testi-dots" role="tablist" aria-label="<?php esc_attr_e('Testimonial pages', 'myco'); ?>">
            <?php foreach ($pages as $i => $page) : ?>
            <button class="testi-dot<?php echo $i === 0 ? ' active' : ''; ?>"
                    data-index="<?php echo $i; ?>"
                    aria-label="<?php printf(esc_attr__('Page %d', 'myco'), $i + 1); ?>"
                    aria-selected="<?php echo $i === 0 ? 'true' : 'false'; ?>"
                    role="tab"></button>
            <?php endforeach; ?>
        </div>

    </div>
</section>
