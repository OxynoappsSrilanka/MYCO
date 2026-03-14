<?php
/**
 * Template Name: Programs
 *
 * @package MYCO
 */

get_header();
get_template_part('template-parts/hero/hero-breadcrumb-dark');

$categories = get_terms(['taxonomy' => 'program_category', 'hide_empty' => true]);

$programs = new WP_Query([
    'post_type'      => 'program',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);

// Default programs with real images
$default_programs = array(
    array(
        'title' => 'Youth Leadership Development',
        'description' => 'Helping youth build confidence, communication skills, teamwork, responsibility, and leadership rooted in Islamic values.',
        'image' => MYCO_URI . '/assets/images/Galleries/myco-youth-team-award-check-winners.jpg',
        'category' => 'Leadership'
    ),
    array(
        'title' => 'Spiritual Development',
        'description' => 'Lectures, youth halaqas, Islamic learning opportunities, and guidance that strengthens faith and identity.',
        'image' => MYCO_URI . '/assets/images/Galleries/myco-youth-basketball-event-congregational-prayer.jpg',
        'category' => 'Spiritual'
    ),
    array(
        'title' => 'Education & Skill Building',
        'description' => 'Support through educational initiatives such as computer literacy, counseling, learning support, and developmental programming.',
        'image' => MYCO_URI . '/assets/images/Galleries/myco-youth-community-center-groundbreaking-ceremony.jpg',
        'category' => 'Education'
    ),
    array(
        'title' => 'Athletics & Training',
        'description' => 'Basketball, soccer, and other active programming that builds discipline, confidence, and brotherhood/sisterhood.',
        'image' => MYCO_URI . '/assets/images/Galleries/myco-basketball-champions-team-with-trophy.jpg.jpg',
        'category' => 'Athletics'
    ),
    array(
        'title' => 'Social & Cultural Activities',
        'description' => 'Gatherings that foster belonging, friendship, and community connection across backgrounds.',
        'image' => MYCO_URI . '/assets/images/Galleries/myco-basketball-tournament-award-ceremony-team-celebration.jpg.JPG',
        'category' => 'Social'
    ),
    array(
        'title' => 'Community Service & Innovation',
        'description' => 'Volunteer initiatives that teach youth to serve others and contribute meaningfully to their communities.',
        'image' => MYCO_URI . '/assets/images/Galleries/MCYC Groundbreaking_ Aatifa.jpg',
        'category' => 'Community'
    ),
);

// Force use of defaults if no custom programs exist
$use_defaults = !$programs->have_posts();
?>

<section class="w-full bg-white py-16 md:py-20">
    <div class="inner mx-auto px-4">
        <!-- Category Filter Tabs -->
        <?php if ($categories && !is_wp_error($categories)) : ?>
        <div class="flex flex-wrap items-center gap-3 mb-10">
            <button class="filter-tab active" onclick="filterPrograms('all', this)"><?php esc_html_e('All Programs', 'myco'); ?></button>
            <?php foreach ($categories as $cat) : ?>
            <button class="filter-tab" onclick="filterPrograms('<?php echo esc_attr($cat->slug); ?>', this)"><?php echo esc_html($cat->name); ?></button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Programs Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="programs-grid">
            <?php if ($use_defaults) : ?>
                <?php foreach ($default_programs as $program) : ?>
                <div class="program-item bg-white rounded-2xl overflow-hidden transition-all duration-200"
                     data-categories="<?php echo esc_attr(strtolower($program['category'])); ?>"
                     style="box-shadow: 0 8px 24px rgba(20,25,67,0.08);"
                     onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 36px rgba(20,25,67,0.14)'"
                     onmouseout="this.style.transform='translateY(0)';this.style.boxShadow='0 8px 24px rgba(20,25,67,0.08)'">
                    <div class="aspect-[16/10] overflow-hidden">
                        <img src="<?php echo esc_url($program['image']); ?>"
                             alt="<?php echo esc_attr($program['title']); ?>"
                             class="w-full h-full object-cover" loading="lazy" />
                    </div>
                    <div class="p-6">
                        <span class="inline-block text-xs font-semibold uppercase tracking-wider px-3 py-1 rounded-full mb-3"
                              style="background: rgba(200,64,46,0.1); color: #C8402E;">
                            <?php echo esc_html($program['category']); ?>
                        </span>
                        <h3 class="text-lg font-bold mb-2" style="color: #141943;">
                            <?php echo esc_html($program['title']); ?>
                        </h3>
                        <p class="text-sm text-gray-500 leading-relaxed">
                            <?php echo esc_html($program['description']); ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php elseif ($programs->have_posts()) : ?>
                <?php while ($programs->have_posts()) : $programs->the_post();
                $cats = get_the_terms(get_the_ID(), 'program_category');
                $cat_slugs = $cats && !is_wp_error($cats) ? implode(' ', wp_list_pluck($cats, 'slug')) : '';
            ?>
            <div class="program-item" data-categories="<?php echo esc_attr($cat_slugs); ?>">
                <?php get_template_part('template-parts/cards/program-card'); ?>
            </div>
            <?php endwhile; wp_reset_postdata();
            else : ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 80px 20px; background: #F5F6FA; border-radius: 24px;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#D1D5DB" stroke-width="1.5" style="margin: 0 auto 20px; display: block;"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                <h3 style="font-size: 22px; font-weight: 800; color: #141943; margin-bottom: 12px;"><?php esc_html_e('Programs Coming Soon', 'myco'); ?></h3>
                <p style="font-size: 16px; color: #6B7280; margin-bottom: 28px; max-width: 400px; margin-left: auto; margin-right: auto;"><?php esc_html_e("We're building a range of programs for Muslim youth. Check back soon or get in touch to learn more.", 'myco'); ?></p>
                <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="pill-primary"><?php esc_html_e('Contact Us', 'myco'); ?></a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="w-full relative overflow-hidden py-16 md:py-20" style="background: linear-gradient(135deg, #141943 0%, #1e2a5a 50%, #2a3e6a 100%);">
    <div class="inner mx-auto px-4 text-center relative z-10">
        <h2 class="font-inter font-black text-white leading-tight mb-6" style="font-size: clamp(2rem, 4.5vw, 3rem);">
            <?php esc_html_e('Join Our Programs Today', 'myco'); ?>
        </h2>
        <p class="text-white/70 leading-relaxed max-w-2xl mx-auto mb-10" style="font-size: 1.1rem;">
            <?php esc_html_e('Take the first step towards empowering your future. Our programs are designed for Muslim youth of all ages and backgrounds.', 'myco'); ?>
        </p>
        <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="pill-primary"><?php esc_html_e('Get Started', 'myco'); ?></a>
    </div>
</section>

<script>
function filterPrograms(cat, btn) {
    document.querySelectorAll('.filter-tab').forEach(function(t) { t.classList.remove('active'); });
    if (btn) btn.classList.add('active');
    document.querySelectorAll('.program-item').forEach(function(item) {
        if (cat === 'all' || item.dataset.categories.indexOf(cat) !== -1) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}
</script>

<?php get_footer(); ?>
