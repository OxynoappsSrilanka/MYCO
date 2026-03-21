<?php
/**
 * Template Name: News
 *
 * @package MYCO
 */

get_header();

$categories = get_terms(['taxonomy' => 'news_category', 'hide_empty' => true]);

// Define default categories if none exist
$default_categories = array(
    array('slug' => 'events', 'name' => 'Events'),
    array('slug' => 'community', 'name' => 'Community'),
    array('slug' => 'programs', 'name' => 'Programs'),
    array('slug' => 'education', 'name' => 'Education'),
    array('slug' => 'news', 'name' => 'News'),
);

// Use default categories if no custom categories exist
if (empty($categories) || is_wp_error($categories)) {
    $categories = $default_categories;
    $use_defaults = true;
} else {
    $use_defaults = false;
}

// Featured article
$featured = new WP_Query([
    'post_type'      => 'news_article',
    'posts_per_page' => 1,
    'meta_key'       => 'featured_article',
    'meta_value'     => '1',
]);

// All articles
$articles = new WP_Query([
    'post_type'      => 'news_article',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);
?>

<!-- Hero Banner Section with Full Width Blurred Background -->
<section style="
  background: url('<?php echo esc_url(get_template_directory_uri() . '/assets/images/about.png'); ?>') center center / cover no-repeat;
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
      <span style="font-size: 14px; font-weight: 600; color: #ffffff;">News</span>
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
      Latest News &amp; Updates
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
      Stay informed about MYCO programs, events, and community stories
    </p>
  </div>
</section>

<section class="w-full bg-white py-16 md:py-20">
    <div class="inner mx-auto px-4">

        <!-- Featured Article -->
        <?php if ($featured->have_posts()) : while ($featured->have_posts()) : $featured->the_post();
            $img_url = myco_get_image_url(get_the_ID(), 'large');
            $read_time = myco_get_field('article_read_time', false, '5 min read');
            $cats = get_the_terms(get_the_ID(), 'news_category');
            $cat_name = $cats && !is_wp_error($cats) ? $cats[0]->name : '';
        ?>
        <div class="mb-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center bg-gray-50 rounded-3xl overflow-hidden" style="box-shadow: 0 8px 32px rgba(20,25,67,0.08);">
                <div class="aspect-[16/10] overflow-hidden">
                    <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="w-full h-full object-cover" />
                </div>
                <div class="p-8 lg:p-10">
                    <?php if ($cat_name) : ?>
                    <span class="inline-block text-xs font-semibold uppercase tracking-wider px-3 py-1 rounded-full mb-4" style="background: rgba(200,64,46,0.1); color: #C8402E;"><?php echo esc_html($cat_name); ?></span>
                    <?php endif; ?>
                    <h2 class="text-2xl lg:text-3xl font-black mb-4" style="color: #141943;">
                        <a href="<?php the_permalink(); ?>" class="hover:text-myco-red transition-colors"><?php the_title(); ?></a>
                    </h2>
                    <p class="text-gray-500 leading-relaxed mb-6 line-clamp-3"><?php echo esc_html(get_the_excerpt()); ?></p>
                    <div class="flex items-center gap-4 text-sm text-gray-400">
                        <span><?php echo get_the_date(); ?></span>
                        <span>&middot;</span>
                        <span><?php echo esc_html($read_time); ?></span>
                    </div>
                </div>
            </div>
        </div>
        <?php endwhile; wp_reset_postdata(); endif; ?>

        <!-- Filter Tabs and Search Bar -->
        <div style="display: flex; align-items: center; justify-content: space-between; gap: 24px; margin-bottom: 32px; flex-wrap: wrap;">
            <!-- Category Filter Tabs -->
            <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap; flex: 1;">
                <button class="filter-tab active" onclick="filterNews('all', this)" style="
                    height: 48px;
                    padding: 0 28px;
                    border-radius: 9999px;
                    border: 2px solid #C8402E;
                    background: #C8402E;
                    color: #ffffff;
                    font-size: 15px;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all .2s;
                    white-space: nowrap;
                "><?php esc_html_e('All', 'myco'); ?></button>
                <?php if ($categories && !is_wp_error($categories)) : ?>
                    <?php foreach ($categories as $cat) : 
                        $cat_slug = $use_defaults ? $cat['slug'] : $cat->slug;
                        $cat_name = $use_defaults ? $cat['name'] : $cat->name;
                    ?>
                    <button class="filter-tab" onclick="filterNews('<?php echo esc_attr($cat_slug); ?>', this)" style="
                        height: 48px;
                        padding: 0 28px;
                        border-radius: 9999px;
                        border: 2px solid #E2E6ED;
                        background: #ffffff;
                        color: #4B5563;
                        font-size: 15px;
                        font-weight: 600;
                        cursor: pointer;
                        transition: all .2s;
                        white-space: nowrap;
                    "><?php echo esc_html($cat_name); ?></button>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <!-- Search Bar -->
            <div style="position: relative; width: 380px; flex-shrink: 0;">
                <input 
                    type="text" 
                    id="news-search" 
                    placeholder="Search articles..." 
                    onkeyup="searchNews()"
                    style="
                        width: 100%;
                        height: 48px;
                        padding: 0 50px 0 20px;
                        border: 2px solid #E5E7EB;
                        border-radius: 9999px;
                        font-size: 15px;
                        color: #141943;
                        background: #ffffff;
                        transition: all .2s;
                        outline: none;
                    "
                    onfocus="this.style.borderColor='#C8402E'; this.style.boxShadow='0 0 0 3px rgba(200,64,46,0.1)'"
                    onblur="this.style.borderColor='#E5E7EB'; this.style.boxShadow='none'"
                />
                <button style="
                    position: absolute;
                    right: 6px;
                    top: 50%;
                    transform: translateY(-50%);
                    width: 36px;
                    height: 36px;
                    border-radius: 50%;
                    background: #C8402E;
                    border: none;
                    cursor: pointer;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: background .2s;
                " onmouseover="this.style.background='#b03426'" onmouseout="this.style.background='#C8402E'">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                        <circle cx="8" cy="8" r="5.5" stroke="white" stroke-width="2"/>
                        <path d="m15 15-3-3" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Results Count -->
        <div style="margin-bottom: 32px;">
            <p id="news-count" style="font-size: 15px; color: #6B7280; font-weight: 500;">
                Showing <span id="count-number">0</span> articles
            </p>
        </div>

        <!-- News Grid -->
        <div class="grid grid-cols-4 gap-6" id="news-grid">
            <?php if ($articles->have_posts()) : while ($articles->have_posts()) : $articles->the_post();
                $cats = get_the_terms(get_the_ID(), 'news_category');
                $cat_slugs = $cats && !is_wp_error($cats) ? implode(' ', wp_list_pluck($cats, 'slug')) : '';
            ?>
            <div class="news-item" data-categories="<?php echo esc_attr($cat_slugs); ?>">
                <?php get_template_part('template-parts/cards/news-card'); ?>
            </div>
            <?php endwhile; wp_reset_postdata();
            else : 
                // Dummy data when no articles exist
                $dummy_articles = [
                    [
                        'title' => 'MYCO Youth Leadership Summit 2026',
                        'excerpt' => 'Join us for an inspiring day of workshops, networking, and skill-building designed to empower the next generation of Muslim leaders.',
                        'category' => 'Events',
                        'date' => 'March 15, 2026',
                        'read_time' => '4 min read',
                        'image' => get_template_directory_uri() . '/assets/images/leadership.png'
                    ],
                    [
                        'title' => 'Community Service Initiative Reaches 500 Volunteers',
                        'excerpt' => 'Our community service programs have grown tremendously, with over 500 active volunteers making a difference in local neighborhoods.',
                        'category' => 'Community',
                        'date' => 'March 10, 2026',
                        'read_time' => '5 min read',
                        'image' => get_template_directory_uri() . '/assets/images/volunteers.jpg'
                    ],
                    [
                        'title' => 'New Athletic Programs Launch This Spring',
                        'excerpt' => 'Exciting new sports and fitness programs are coming to MYCO, including basketball leagues, soccer training, and wellness workshops.',
                        'category' => 'Programs',
                        'date' => 'March 5, 2026',
                        'read_time' => '3 min read',
                        'image' => get_template_directory_uri() . '/assets/images/sports.jpg'
                    ],
                    [
                        'title' => 'Scholarship Program Awards $50,000 to Students',
                        'excerpt' => 'MYCO is proud to announce scholarship awards totaling $50,000 to support Muslim youth pursuing higher education and career development.',
                        'category' => 'Education',
                        'date' => 'February 28, 2026',
                        'read_time' => '6 min read',
                        'image' => get_template_directory_uri() . '/assets/images/study.jpg'
                    ],
                    [
                        'title' => 'Annual Fundraising Gala Exceeds Goals',
                        'excerpt' => 'Thanks to our generous community, this year\'s fundraising gala raised over $100,000 to support youth programs and facility improvements.',
                        'category' => 'Events',
                        'date' => 'February 20, 2026',
                        'read_time' => '4 min read',
                        'image' => get_template_directory_uri() . '/assets/images/meeting.jpg'
                    ],
                    [
                        'title' => 'Summer Camp Registration Now Open',
                        'excerpt' => 'Register now for MYCO\'s exciting summer camp programs featuring sports, arts, Islamic studies, and outdoor adventures for all ages.',
                        'category' => 'Programs',
                        'date' => 'February 15, 2026',
                        'read_time' => '3 min read',
                        'image' => get_template_directory_uri() . '/assets/images/about.png'
                    ],
                    [
                        'title' => 'Youth Center Construction Update',
                        'excerpt' => 'Construction of our new youth center is progressing on schedule. Take a look at the latest updates and what\'s coming next.',
                        'category' => 'News',
                        'date' => 'February 10, 2026',
                        'read_time' => '5 min read',
                        'image' => get_template_directory_uri() . '/assets/images/Construction/Construction Update 1.jpg'
                    ],
                    [
                        'title' => 'Mentorship Program Pairs 100 Youth with Leaders',
                        'excerpt' => 'Our mentorship initiative has successfully matched 100 young people with experienced professionals and community leaders.',
                        'category' => 'Community',
                        'date' => 'February 5, 2026',
                        'read_time' => '4 min read',
                        'image' => get_template_directory_uri() . '/assets/images/hero-image.png'
                    ],
                ];
                
                foreach ($dummy_articles as $article) :
            ?>
            <div class="news-item" data-categories="all">
                <article class="group bg-white rounded-2xl overflow-hidden transition-all duration-300 hover:shadow-2xl" style="box-shadow: 0 4px 20px rgba(20,25,67,0.06); border: 1px solid rgba(20,25,67,0.04);">
                    <div class="block">
                        <div class="aspect-[4/3] overflow-hidden bg-gray-100 relative">
                            <img src="<?php echo esc_url($article['image']); ?>" 
                                 alt="<?php echo esc_attr($article['title']); ?>" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" 
                                 loading="lazy" />
                            <span class="absolute top-4 left-4 text-xs font-bold uppercase tracking-wider px-4 py-2 rounded-full" 
                                  style="background: #C8402E; color: white; box-shadow: 0 2px 8px rgba(200,64,46,0.3);">
                                <?php echo esc_html($article['category']); ?>
                            </span>
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
                                    <span><?php echo esc_html($article['date']); ?></span>
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
                            <h3 class="text-lg font-extrabold mb-3 line-clamp-2 leading-tight" style="color: #141943; min-height: 3.5rem;">
                                <?php echo esc_html($article['title']); ?>
                            </h3>
                            
                            <!-- Excerpt -->
                            <p class="text-sm text-gray-600 leading-relaxed line-clamp-3 mb-4" style="min-height: 4rem;">
                                <?php echo esc_html($article['excerpt']); ?>
                            </p>
                            
                            <!-- Read More Link -->
                            <div class="flex items-center gap-2 font-bold text-sm" style="color: #9CA3AF;">
                                <span>Coming Soon</span>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                    <polyline points="12 5 19 12 12 19"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
            <?php endforeach; endif; ?>
        </div>
    </div>
</section>

<?php get_template_part('template-parts/sections/newsletter-signup'); ?>

<style>
.filter-tab:hover {
    border-color: #C8402E !important;
    color: #C8402E !important;
}
.filter-tab.active {
    background: #C8402E !important;
    border-color: #C8402E !important;
    color: #ffffff !important;
}
@media (max-width: 768px) {
    .grid.grid-cols-4 {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}
@media (max-width: 520px) {
    .grid.grid-cols-4 {
        grid-template-columns: 1fr !important;
    }
}
</style>

<script>
function filterNews(cat, btn) {
    document.querySelectorAll('.filter-tab').forEach(function(t) { 
        t.classList.remove('active');
        t.style.background = '#ffffff';
        t.style.borderColor = '#E2E6ED';
        t.style.color = '#4B5563';
    });
    
    btn.classList.add('active');
    btn.style.background = '#C8402E';
    btn.style.borderColor = '#C8402E';
    btn.style.color = '#ffffff';
    
    var searchTerm = document.getElementById('news-search').value.toLowerCase();
    
    document.querySelectorAll('.news-item').forEach(function(item) {
        var matchesCategory = cat === 'all' || item.dataset.categories.indexOf(cat) !== -1;
        var title = item.querySelector('h3').textContent.toLowerCase();
        var excerpt = item.querySelector('p').textContent.toLowerCase();
        var matchesSearch = searchTerm === '' || title.indexOf(searchTerm) !== -1 || excerpt.indexOf(searchTerm) !== -1;
        
        if (matchesCategory && matchesSearch) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
    
    updateNewsCount();
}

function searchNews() {
    var searchTerm = document.getElementById('news-search').value.toLowerCase();
    var activeTab = document.querySelector('.filter-tab.active');
    var activeCategory = 'all';
    
    if (activeTab && activeTab.getAttribute('onclick')) {
        var match = activeTab.getAttribute('onclick').match(/filterNews\('([^']+)'/);
        if (match) activeCategory = match[1];
    }
    
    document.querySelectorAll('.news-item').forEach(function(item) {
        var matchesCategory = activeCategory === 'all' || item.dataset.categories.indexOf(activeCategory) !== -1;
        var title = item.querySelector('h3').textContent.toLowerCase();
        var excerpt = item.querySelector('p').textContent.toLowerCase();
        var matchesSearch = searchTerm === '' || title.indexOf(searchTerm) !== -1 || excerpt.indexOf(searchTerm) !== -1;
        
        if (matchesCategory && matchesSearch) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
    
    updateNewsCount();
}

function updateNewsCount() {
    var visibleItems = Array.from(document.querySelectorAll('.news-item')).filter(function(item) {
        return item.style.display !== 'none';
    });
    document.getElementById('count-number').textContent = visibleItems.length;
}

// Initialize count on page load
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        updateNewsCount();
    }, 200);
});
</script>

<?php get_footer(); ?>
