<?php
/**
 * About: What Happens at MYCO (6 Programs)
 * @package MYCO
 */
$programs = myco_get_field('about_programs');
$defaults = array(
    array(
        'title' => 'Youth Leadership Development',
        'description' => 'Helping youth build confidence, communication skills, teamwork, responsibility, and leadership rooted in Islamic values.',
        'icon_svg' => '<svg width="32" height="32" viewBox="0 0 48 48" fill="none"><path d="M24 44c11.046 0 20-8.954 20-20S35.046 4 24 4 4 12.954 4 24s8.954 20 20 20Z" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M24 16v8l5.5 5.5" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'icon_bg' => 'linear-gradient(135deg, #C8402E 0%, #e06050 100%)',
        'icon_shadow' => 'rgba(200, 64, 46, 0.3)',
        'bg_image' => 'myco-youth-team-award-check-winners.jpg'
    ),
    array(
        'title' => 'Spiritual Development',
        'description' => 'Lectures, youth halaqas, Islamic learning opportunities, and guidance that strengthens faith and identity.',
        'icon_svg' => '<svg width="32" height="32" viewBox="0 0 48 48" fill="none"><path d="M24 4L8 12v12c0 10 6.5 19.5 16 22 9.5-2.5 16-12 16-22V12L24 4Z" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><circle cx="24" cy="22" r="6" stroke="#fff" stroke-width="3"/></svg>',
        'icon_bg' => 'linear-gradient(135deg, #2563eb 0%, #60a5fa 100%)',
        'icon_shadow' => 'rgba(37, 99, 235, 0.3)',
        'bg_image' => 'myco-youth-basketball-event-congregational-prayer.jpg'
    ),
    array(
        'title' => 'Education & Skill Building',
        'description' => 'Support through educational initiatives such as computer literacy, counseling, learning support, and developmental programming.',
        'icon_svg' => '<svg width="32" height="32" viewBox="0 0 48 48" fill="none"><path d="M4 18L24 8l20 10-20 10L4 18Z" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 22v10c0 4 6.268 8 14 8s14-4 14-8V22" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M40 20v14" stroke="#fff" stroke-width="3" stroke-linecap="round"/></svg>',
        'icon_bg' => 'linear-gradient(135deg, #7c3aed 0%, #a78bfa 100%)',
        'icon_shadow' => 'rgba(124, 58, 237, 0.3)',
        'bg_image' => 'myco-youth-community-center-groundbreaking-ceremony.jpg'
    ),
    array(
        'title' => 'Athletics & Training',
        'description' => 'Basketball, soccer, and other active programming that builds discipline, confidence, and brotherhood/sisterhood.',
        'icon_svg' => '<svg width="32" height="32" viewBox="0 0 48 48" fill="none"><circle cx="24" cy="24" r="18" stroke="#fff" stroke-width="3"/><path d="M24 6v36M6 24h36M12 10c4 4 4 10 4 14s0 10-4 14M36 10c-4 4-4 10-4 14s0 10 4 14" stroke="#fff" stroke-width="3" stroke-linecap="round"/></svg>',
        'icon_bg' => 'linear-gradient(135deg, #16a34a 0%, #4ade80 100%)',
        'icon_shadow' => 'rgba(22, 163, 74, 0.3)',
        'bg_image' => 'myco-basketball-champions-team-with-trophy.jpg.jpg'
    ),
    array(
        'title' => 'Social & Cultural Activities',
        'description' => 'Gatherings that foster belonging, friendship, and community connection across backgrounds.',
        'icon_svg' => '<svg width="32" height="32" viewBox="0 0 48 48" fill="none"><path d="M36 40v-4c0-4.418-3.582-8-8-8H20c-4.418 0-8 3.582-8 8v4" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><circle cx="24" cy="16" r="6" stroke="#fff" stroke-width="3"/><path d="M42 40v-4c0-2.5-1.5-4.5-4-5.5M34 6c2.5 1 4 3 4 5.5s-1.5 4.5-4 5.5" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
        'icon_bg' => 'linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%)',
        'icon_shadow' => 'rgba(245, 158, 11, 0.3)',
        'bg_image' => 'myco-basketball-tournament-award-ceremony-team-celebration.jpg.JPG'
    ),
    array(
        'title' => 'Community Service & Innovation',
        'description' => 'Volunteer initiatives that teach youth to serve others and contribute meaningfully to their communities.',
        'icon_svg' => '<svg width="32" height="32" viewBox="0 0 48 48" fill="none"><path d="M34 24c0-5.523-4.477-10-10-10s-10 4.477-10 10c0 8 10 18 10 18s10-10 10-18Z" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><circle cx="24" cy="24" r="3" stroke="#fff" stroke-width="3"/><path d="M24 4v6M44 24h-6M24 44v-6M4 24h6" stroke="#fff" stroke-width="3" stroke-linecap="round"/></svg>',
        'icon_bg' => 'linear-gradient(135deg, #ec4899 0%, #f472b6 100%)',
        'icon_shadow' => 'rgba(236, 72, 153, 0.3)',
        'bg_image' => 'MCYC Groundbreaking_ Aatifa.jpg'
    ),
);
if (!$programs) $programs = $defaults;
?>
<section id="approach" class="w-full bg-gray-50 py-16 md:py-20">
  <div class="max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Header -->
    <div class="text-center mb-12 md:mb-16">
      <div class="flex items-center justify-center gap-2 mb-4">
        <span class="text-xs font-semibold uppercase tracking-wider" style="color: #C8402E;">Our Programs</span>
      </div>
      <h2 class="font-inter font-black leading-tight tracking-tight mb-5" style="font-size: clamp(2.5rem, 5vw, 4rem); color: #141943;">
        <?php echo esc_html(myco_get_field('about_pillars_heading') ?: 'What Happens at MYCO'); ?>
      </h2>
      <p class="text-gray-500 leading-relaxed max-w-2xl mx-auto" style="font-size: 1.1rem; line-height: 1.7;">
        <?php echo esc_html(myco_get_field('about_pillars_description') ?: 'Comprehensive programs that empower Muslim youth through faith, education, and community engagement'); ?>
      </p>
    </div>

    <!-- 6 Programs Grid (3x2) with Background Images -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
      <?php foreach ($programs as $program) : 
        $bg_image = !empty($program['bg_image']) ? MYCO_URI . '/assets/images/Galleries/' . $program['bg_image'] : '';
      ?>
      <div class="bg-white rounded-2xl p-7 transition-all duration-300 hover:shadow-2xl hover:-translate-y-2 overflow-hidden relative" style="box-shadow: 0 8px 24px rgba(20, 25, 67, 0.09);">
        
        <?php if ($bg_image) : ?>
        <!-- Background Image with Overlay -->
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 160px; background-image: url('<?php echo esc_url($bg_image); ?>'); background-size: cover; background-position: center; opacity: 0.12; border-radius: 16px 16px 0 0;"></div>
        <?php endif; ?>
        
        <div style="position: relative; z-index: 1;">
          <div class="w-20 h-20 rounded-2xl mx-auto mb-6 flex items-center justify-center" style="background: <?php echo esc_attr($program['icon_bg']); ?>; box-shadow: 0 6px 20px <?php echo esc_attr($program['icon_shadow']); ?>;">
            <?php echo wp_kses($program['icon_svg'], [
              'svg' => ['width' => [], 'height' => [], 'viewBox' => [], 'fill' => [], 'xmlns' => []],
              'path' => ['d' => [], 'stroke' => [], 'stroke-width' => [], 'stroke-linecap' => [], 'stroke-linejoin' => [], 'fill' => []],
              'circle' => ['cx' => [], 'cy' => [], 'r' => [], 'stroke' => [], 'stroke-width' => [], 'fill' => []],
            ]); ?>
          </div>
          <h3 class="text-xl font-black mb-3 text-center" style="color: #141943;"><?php echo esc_html($program['title']); ?></h3>
          <p class="text-sm text-gray-500 leading-relaxed text-center"><?php echo esc_html($program['description']); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>
