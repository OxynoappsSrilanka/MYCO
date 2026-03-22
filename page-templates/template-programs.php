<?php
/**
 * Template Name: Programs
 *
 * @package MYCO
 */

get_header();

// Fetch all program categories for filtering
$terms = get_terms([
    'taxonomy'   => 'program_category',
    'hide_empty' => false,
]);

// Main Query
$programs = new WP_Query([
    'post_type'      => 'program',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
]);
?>

<!-- Hero Banner Section with Full Width Blurred Background -->
<section class="page-hero-bg" style="
  background: url('<?php echo esc_url(myco_get_field('programs_banner_image') ?: get_template_directory_uri() . '/assets/images/volunteers.jpg'); ?>') center center / cover no-repeat;
  padding: 140px 0;
  position: relative;
  overflow: hidden;
">
  <!-- Blur Overlay -->
  <div style="
    position: absolute;
    inset: 0;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
    background: rgba(20, 25, 67, 0.75);
    z-index: 1;
  "></div>
  
  <!-- Content -->
  <div style="position: relative; z-index: 2; text-align: center; max-width: 1200px; margin: 0 auto; padding: 0 40px;">
    <!-- Breadcrumb -->
    <div style="display: flex; align-items: center; justify-content: center; gap: 10px; margin-bottom: 24px;">
      <a href="<?php echo esc_url(home_url('/')); ?>" style="font-size: 14px; font-weight: 500; color: rgba(255,255,255,0.75); text-decoration: none; transition: color .2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.75)'">Home</a>
      <svg width="6" height="10" viewBox="0 0 6 10" fill="none">
        <path d="M1 1l4 4-4 4" stroke="rgba(255,255,255,0.6)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      <span style="font-size: 14px; font-weight: 600; color: #ffffff;">Our Programs</span>
    </div>
    
    <!-- Page Title -->
    <h1 style="
      font-size: 72px;
      font-weight: 900;
      color: #ffffff;
      line-height: 1.1;
      letter-spacing: -0.02em;
      margin-bottom: 20px;
      text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    ">
      <?php echo esc_html(myco_get_field('programs_title') ?: 'Our Programs'); ?>
    </h1>
    
    <!-- Subtitle -->
    <p style="
      font-size: 20px;
      color: rgba(255, 255, 255, 0.95);
      line-height: 1.6;
      max-width: 700px;
      margin: 0 auto;
      font-weight: 400;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    ">
      <?php echo esc_html(myco_get_field('programs_subtitle') ?: 'Comprehensive youth development through mentorship, athletics, academics, and community service'); ?>
    </p>
  </div>
</section>

<!-- Filter & Programs Section -->
<section style="background: #F8FAFC; padding: 60px 0 110px;">
    <div class="inner">
        <div class="filter-search-container" style="margin-bottom: 40px;">
            <!-- Category Filters -->
            <div class="filter-group">
                <button class="filter-btn active" data-filter="all">All Programs</button>
                <?php 
                $terms = get_terms(['taxonomy' => 'program_category', 'hide_empty' => false]);
                if ($terms && !is_wp_error($terms) && count($terms) > 0) :
                    foreach ($terms as $term) : ?>
                        <button class="filter-btn" data-filter="<?php echo esc_attr($term->slug); ?>"><?php echo esc_html($term->name); ?></button>
                    <?php endforeach;
                else : ?>
                    <button class="filter-btn" data-filter="athletics">Athletics</button>
                    <button class="filter-btn" data-filter="mentorship">Mentorship</button>
                    <button class="filter-btn" data-filter="academic">Academic</button>
                    <button class="filter-btn" data-filter="community">Community Service</button>
                    <button class="filter-btn" data-filter="leadership">Leadership</button>
                <?php endif; ?>
            </div>

            <!-- Search Wrapper -->
            <div class="search-wrapper" style="position: relative;">
                <div class="search-icon" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); z-index: 2;">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><circle cx="9" cy="9" r="6.5" stroke="#94A3B8" stroke-width="2"/><path d="M13.5 13.5L17 17" stroke="#94A3B8" stroke-width="2" stroke-linecap="round"/></svg>
                </div>
                <input type="text" id="program-search" class="program-search-input" placeholder="Search programs..." />
                <button class="programs-search-btn" aria-label="Search programs">
                    <svg width="15" height="15" viewBox="0 0 18 18" fill="none"><circle cx="8" cy="8" r="5.5" stroke="white" stroke-width="2"/><path d="M12 12l4 4" stroke="white" stroke-width="2" stroke-linecap="round"/></svg>
                </button>
            </div>
        </div>

        <div id="programs-grid" class="programs-grid">
            <?php 
            if ($programs->have_posts()) :
                while ($programs->have_posts()) : $programs->the_post();
                    $pcats = get_the_terms(get_the_ID(), 'program_category');
                    $cat_slugs = $pcats && !is_wp_error($pcats) ? wp_list_pluck($pcats, 'slug') : [];
                    $cat_data = implode(' ', $cat_slugs);
                    ?>
                    <div class="program-grid-item" data-category="<?php echo esc_attr($cat_data); ?>" data-title="<?php echo esc_attr(strtolower(get_the_title())); ?>">
                        <?php get_template_part('template-parts/cards/program-card'); ?>
                    </div>
                <?php endwhile;
                wp_reset_postdata();
            else :
                // --- STATIC FALLBACK GRID ---
                $demo_programs = [
                    [
                        'title' => 'Basketball & Fitness Nights',
                        'slug' => 'basketball-fitness-nights',
                        'category' => 'athletics',
                        'cat_name' => 'Athletics',
                        'schedule' => 'Weekly',
                        'age_group' => 'Ages 12–18',
                        'image' => MYCO_URI . '/assets/images/Galleries/myco-basketball-champions-team-with-trophy.jpg.jpg',
                        'excerpt' => 'Weekly basketball sessions promoting teamwork, physical fitness, and positive competition among youth.'
                    ],
                    [
                        'title' => 'Youth Leadership Mentorship',
                        'slug' => 'youth-leadership-mentorship',
                        'category' => 'mentorship',
                        'cat_name' => 'Mentorship',
                        'schedule' => 'Bi-weekly',
                        'age_group' => 'Ages 15–22',
                        'image' => MYCO_URI . '/assets/images/Galleries/myco-youth-team-award-check-winners.jpg',
                        'excerpt' => 'Empowering the next generation of community leaders through guidance and professional mentorship.'
                    ],
                    [
                        'title' => 'Spiritual Identity Program',
                        'slug' => 'spiritual-identity-program',
                        'category' => 'academic',
                        'cat_name' => 'Academic',
                        'schedule' => 'Weekly',
                        'age_group' => 'Ages 14–20',
                        'image' => MYCO_URI . '/assets/images/Galleries/myco-youth-basketball-event-congregational-prayer.jpg',
                        'excerpt' => 'Building a strong, confident spiritual identity through ethics, values, and community connection.'
                    ],
                    [
                        'title' => 'Community Development & Service',
                        'slug' => 'community-development-service',
                        'category' => 'community',
                        'cat_name' => 'Community',
                        'schedule' => 'Monthly',
                        'age_group' => 'All Ages',
                        'image' => MYCO_URI . '/assets/images/Galleries/myco-youth-community-center-groundbreaking-ceremony.jpg',
                        'excerpt' => 'Mobilizing youth to take active roles in improving their neighborhoods through service projects.'
                    ]
                ];

                foreach ($demo_programs as $dp) : ?>
                    <div class="program-grid-item" data-category="<?php echo esc_attr($dp['category']); ?>" data-title="<?php echo esc_attr(strtolower($dp['title'])); ?>">
                        <article class="program-card">
                            <div class="program-card-image">
                                <img src="<?php echo esc_url($dp['image']); ?>" alt="<?php echo esc_attr($dp['title']); ?>" loading="lazy">
                                <span class="program-category-badge"><?php echo esc_html(strtoupper($dp['cat_name'])); ?></span>
                            </div>
                            <div class="program-card-content">
                                <h3 class="program-card-title">
                                    <a href="<?php echo esc_url(home_url('/programs/' . $dp['slug'])); ?>"><?php echo esc_html($dp['title']); ?></a>
                                </h3>
                                <p class="program-card-excerpt"><?php echo esc_html($dp['excerpt']); ?></p>
                                <div class="program-card-meta">
                                    <div class="meta-item">
                                        <svg width="15" height="15" viewBox="0 0 16 16" fill="none"><rect x="2" y="3" width="12" height="10" rx="2" stroke="#94A3B8" stroke-width="1.5"/><path d="M2 6h12M5 1v4M11 1v4" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"/></svg>
                                        <span><?php echo esc_html($dp['schedule']); ?></span>
                                    </div>
                                    <div class="meta-item">
                                        <svg width="15" height="15" viewBox="0 0 16 16" fill="none"><circle cx="8" cy="8" r="6" stroke="#94A3B8" stroke-width="1.5"/><path d="M8 4v4l3 2" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"/></svg>
                                        <span><?php echo esc_html($dp['age_group']); ?></span>
                                    </div>
                                </div>
                                <a href="<?php echo esc_url(home_url('/programs/' . $dp['slug'])); ?>" class="program-card-btn">
                                    Learn More
                                    <svg width="14" height="14" viewBox="0 0 16 16" fill="none"><path d="M3 8H13M13 8L9 4M13 8L9 12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </a>
                            </div>
                        </article>
                    </div>
                <?php endforeach;
            endif; ?>
        </div>
    </div>
</section>

<!-- Register CTA Section -->
<section class="programs-register-cta" style="background: #141943; padding: 100px 0; position: relative; overflow: hidden;">
    <div style="position: absolute; right: 0; top: 0; bottom: 0; width: 40%; background: linear-gradient(90deg, transparent, rgba(200,64,46,0.1)); pointer-events: none;"></div>
    <div class="inner" style="position: relative; z-index: 2; text-align: center;">
        <h2 style="font-size: clamp(2.5rem, 5vw, 3.5rem); font-weight: 900; color: #fff; margin-bottom: 24px;">Ready to Empower Your Future?</h2>
        <p style="font-size: 19px; color: rgba(255,255,255,0.7); max-width: 650px; margin: 0 auto 40px; line-height: 1.6;">Join hundreds of youth building skills, confidence, and community at MYCO.</p>
        <div class="programs-register-cta-actions">
            <a href="<?php echo esc_url(myco_get_contact_page_url(['interest' => 'programs'])); ?>" class="pill-primary">Register For A Program</a>
            <a href="<?php echo esc_url(myco_get_contact_page_url(['interest' => 'support'])); ?>" class="pill-secondary" style="border-color: rgba(255,255,255,0.3); color: #fff;">Contact Support</a>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const searchInput = document.getElementById('program-search');
    const programItems = document.querySelectorAll('.program-grid-item');

    function filterItems() {
        const activeCategory = document.querySelector('.filter-btn.active').getAttribute('data-filter');
        const searchTerm = searchInput.value.toLowerCase();

        programItems.forEach(item => {
            const itemCategory = (item.getAttribute('data-category') || '').toLowerCase();
            const itemTitle = (item.getAttribute('data-title') || '').toLowerCase();
            
            const matchesCategory = activeCategory === 'all' || itemCategory.includes(activeCategory);
            const matchesSearch = itemTitle.includes(searchTerm);

            item.style.display = (matchesCategory && matchesSearch) ? 'block' : 'none';
        });
    }

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            filterItems();
        });
    });

    searchInput.addEventListener('input', filterItems);
});
</script>

<?php get_footer(); ?>
