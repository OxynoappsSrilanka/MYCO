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
        'icon_svg' => '<svg width="62" height="62" viewBox="0 0 48 48" fill="none"><path d="M24 44c11.046 0 20-8.954 20-20S35.046 4 24 4 4 12.954 4 24s8.954 20 20 20Z" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/><path d="M24 16v8l5.5 5.5" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/></svg>',
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

$program_icon_dir = trailingslashit(get_theme_file_path('assets/images/Programs'));
$program_icon_uri = trailingslashit(MYCO_URI . '/assets/images/Programs');
$program_icon_map = array(
    'youth leadership' => 'Youth leadership.webp',
    'spiritual'        => 'Spiritual development.webp',
    'education'        => 'Education and skill.webp',
    'skill'            => 'Education and skill.webp',
    'athletics'        => 'Athletics and training.webp',
    'training'         => 'Athletics and training.webp',
    'social'           => 'Social and cultural.webp',
    'cultural'         => 'Social and cultural.webp',
    'community service'=> 'Community service.webp',
    'innovation'       => 'Community service.webp',
);

$get_program_icon_url = static function (array $program) use ($program_icon_dir, $program_icon_uri, $program_icon_map): string {
    $candidates = array();

    $title = strtolower(trim((string) ($program['title'] ?? '')));
    foreach ($program_icon_map as $keyword => $filename) {
        if (strpos($title, $keyword) !== false) {
            $candidates[] = $filename;
        }
    }

    foreach (array_unique(array_filter($candidates)) as $filename) {
        $icon_path = $program_icon_dir . $filename;
        if (file_exists($icon_path)) {
            return $program_icon_uri . rawurlencode($filename);
        }
    }

    return '';
};

$programs_page = get_page_by_path('programs');
$programs_url  = $programs_page ? get_permalink($programs_page) : home_url('/programs/');
?>
<style>
  #approach .about-program-grid {
    display: grid;
    grid-template-columns: repeat(1, minmax(0, 1fr));
    gap: 24px;
  }

  #approach .about-program-card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-height: 100%;
    overflow: hidden;
    border-radius: 28px;
    background: #fff;
    border: 1px solid rgba(210, 219, 237, 0.8);
    box-shadow: 0 16px 38px rgba(20, 25, 67, 0.08);
    transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
  }

  #approach .about-program-card:hover {
    transform: translateY(-8px);
    border-color: rgba(200, 64, 46, 0.18);
    box-shadow: 0 24px 52px rgba(20, 25, 67, 0.12);
  }

  #approach .about-program-visual {
    position: relative;
    height: 142px;
    overflow: hidden;
    background: linear-gradient(180deg, #eef2fb 0%, #f8faff 100%);
  }

  #approach .about-program-visual::after {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(255, 255, 255, 0.16) 0%, rgba(255, 255, 255, 0.5) 50%, rgba(255, 255, 255, 0.96) 100%);
  }

  #approach .about-program-visual-image {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    transform: scale(1.04);
    opacity: 0.42;
  }

  #approach .about-program-body {
    position: relative;
    z-index: 1;
    display: flex;
    flex: 1;
    flex-direction: column;
    align-items: center;
    padding: 0 24px 24px;
    margin-top: -54px;
  }

  #approach .about-program-icon {
    width: 108px;
    height: 108px;
    overflow: hidden;
    border-radius: 26px;
    background: #fff;
    border: 4px solid rgba(255, 255, 255, 0.96);
    box-shadow: 0 18px 34px rgba(20, 25, 67, 0.16);
  }

  #approach .about-program-icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transform: scale(1.04);
  }

  #approach .about-program-icon-fallback {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  #approach .about-program-title {
    margin-top: 14px;
    color: #141943;
    font-size: clamp(1.35rem, 1.8vw, 1.65rem);
    line-height: 1.1;
    text-align: center;
    letter-spacing: -0.03em;
    font-weight: 900;
    text-wrap: balance;
  }

  #approach .about-program-description {
    margin-top: 12px;
    max-width: 29ch;
    color: #5b6575;
    font-size: 14px;
    line-height: 1.65;
    text-align: center;
    text-wrap: pretty;
  }

  #approach .about-program-link {
    margin-top: auto;
    padding-top: 16px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #141943;
    font-size: 14px;
    font-weight: 800;
    text-decoration: none;
    transition: color 0.2s ease;
  }

  #approach .about-program-link:hover {
    color: #c8402e;
  }

  #approach .about-program-link-arrow {
    transition: transform 0.2s ease;
  }

  #approach .about-program-card:hover .about-program-link-arrow {
    transform: translateX(4px);
  }

  #approach .about-program-footer {
    margin-top: 44px;
    text-align: center;
  }

  @media (min-width: 640px) {
    #approach .about-program-grid {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }
  }

  @media (min-width: 1024px) {
    #approach .about-program-grid {
      grid-template-columns: repeat(3, minmax(0, 1fr));
    }
  }

  @media (max-width: 767px) {
    #approach .about-program-visual {
      height: 128px;
    }

    #approach .about-program-body {
      padding: 0 20px 22px;
      margin-top: -48px;
    }

    #approach .about-program-icon {
      width: 94px;
      height: 94px;
      border-radius: 24px;
    }

    #approach .about-program-title {
      font-size: 1.4rem;
    }
  }
</style>
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
    <div class="about-program-grid">
      <?php foreach ($programs as $program) :
        $bg_image = !empty($program['bg_image']) ? MYCO_URI . '/assets/images/Galleries/' . $program['bg_image'] : '';
        $program_icon = $get_program_icon_url($program);
      ?>
      <article class="about-program-card">
        <div class="about-program-visual">
          <?php if ($bg_image) : ?>
          <div class="about-program-visual-image" style="background-image: url('<?php echo esc_url($bg_image); ?>');"></div>
          <?php endif; ?>
        </div>

        <div class="about-program-body">
          <div class="about-program-icon">
            <?php if ($program_icon) : ?>
            <img src="<?php echo esc_url($program_icon); ?>"
                 alt="<?php echo esc_attr($program['title']); ?>"
                 loading="lazy" />
            <?php else : ?>
            <div class="about-program-icon-fallback" style="background: <?php echo esc_attr($program['icon_bg']); ?>;">
              <?php echo wp_kses($program['icon_svg'], [
                'svg' => ['width' => [], 'height' => [], 'viewBox' => [], 'fill' => [], 'xmlns' => []],
                'path' => ['d' => [], 'stroke' => [], 'stroke-width' => [], 'stroke-linecap' => [], 'stroke-linejoin' => [], 'fill' => []],
                'circle' => ['cx' => [], 'cy' => [], 'r' => [], 'stroke' => [], 'stroke-width' => [], 'fill' => []],
              ]); ?>
            </div>
            <?php endif; ?>
          </div>
          <h3 class="about-program-title"><?php echo esc_html($program['title']); ?></h3>
          <p class="about-program-description"><?php echo esc_html($program['description']); ?></p>
          <a href="<?php echo esc_url($programs_url); ?>" class="about-program-link">
            <?php esc_html_e('Explore Programs', 'myco'); ?>
            <span class="about-program-link-arrow" aria-hidden="true">&rarr;</span>
          </a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>

    <div class="about-program-footer">
      <a href="<?php echo esc_url($programs_url); ?>" class="btn-primary">
        <?php esc_html_e('View All Programs', 'myco'); ?>
      </a>
    </div>

  </div>
</section>
