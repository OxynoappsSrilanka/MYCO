<?php
/**
 * Template Name: Events
 *
 * @package MYCO
 */

get_header();
get_template_part('template-parts/hero/hero-breadcrumb-dark');

$categories = get_terms(['taxonomy' => 'event_category', 'hide_empty' => true]);

$events = new WP_Query([
    'post_type'      => 'event',
    'posts_per_page' => -1,
    'meta_key'       => 'event_date',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
]);
?>

<section class="w-full bg-white py-16 md:py-20">
    <div class="inner mx-auto px-4">
        <?php if ($categories && !is_wp_error($categories)) : ?>
        <div class="flex flex-wrap items-center gap-3 mb-10">
            <button class="filter-tab active" onclick="filterEvents('all', this)"><?php esc_html_e('All Events', 'myco'); ?></button>
            <?php foreach ($categories as $cat) : ?>
            <button class="filter-tab" onclick="filterEvents('<?php echo esc_attr($cat->slug); ?>', this)"><?php echo esc_html($cat->name); ?></button>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="events-grid">
            <?php if ($events->have_posts()) : while ($events->have_posts()) : $events->the_post();
                $cats = get_the_terms(get_the_ID(), 'event_category');
                $cat_slugs = $cats && !is_wp_error($cats) ? implode(' ', wp_list_pluck($cats, 'slug')) : '';
            ?>
            <div class="event-item" data-categories="<?php echo esc_attr($cat_slugs); ?>">
                <?php get_template_part('template-parts/cards/event-card'); ?>
            </div>
            <?php endwhile; wp_reset_postdata();
            else : ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 80px 20px; background: #F5F6FA; border-radius: 24px;">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="#D1D5DB" stroke-width="1.5" style="margin: 0 auto 20px; display: block;"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                <h3 style="font-size: 22px; font-weight: 800; color: #141943; margin-bottom: 12px;"><?php esc_html_e('No Events Scheduled Yet', 'myco'); ?></h3>
                <p style="font-size: 16px; color: #6B7280; margin-bottom: 28px; max-width: 400px; margin-left: auto; margin-right: auto;"><?php esc_html_e("We're always planning new events. Check back soon or contact us to be notified.", 'myco'); ?></p>
                <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="pill-primary"><?php esc_html_e('Get Notified', 'myco'); ?></a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Newsletter -->
<?php get_template_part('template-parts/sections/newsletter-signup'); ?>

<script>
function filterEvents(cat, btn) {
    document.querySelectorAll('.filter-tab').forEach(function(t) { t.classList.remove('active'); });
    if (btn) btn.classList.add('active');
    document.querySelectorAll('.event-item').forEach(function(item) {
        if (cat === 'all' || item.dataset.categories.indexOf(cat) !== -1) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}
</script>

<?php get_footer(); ?>
