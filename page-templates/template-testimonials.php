<?php
/**
 * Template Name: Testimonials
 * @package MYCO
 */
get_header();

// Get all gallery videos
$video_query = new WP_Query(array(
    'post_type'      => 'gallery_video',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'meta_value_num',
    'meta_key'       => 'sort_order',
    'order'          => 'ASC',
));
?>

<!-- Hero Banner Section with Full Width Blurred Background -->
<section class="page-hero-bg" style="
  background: url('<?php echo esc_url(myco_get_field('testimonials_banner_image') ?: get_template_directory_uri() . '/assets/images/study.jpg'); ?>') center center / cover no-repeat;
  padding: 140px 0;
  position: relative;
  overflow: hidden;
  margin-bottom: 0;
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
      <a href="<?php echo esc_url(myco_get_page_url('about', '/about/')); ?>" style="font-size: 14px; font-weight: 500; color: rgba(255,255,255,0.75); text-decoration: none; transition: color .2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.75)'">About Us</a>
      <svg width="6" height="10" viewBox="0 0 6 10" fill="none">
        <path d="M1 1l4 4-4 4" stroke="rgba(255,255,255,0.6)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      <span style="font-size: 14px; font-weight: 600; color: #ffffff;">Testimonials</span>
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
      <?php echo esc_html(myco_get_field('testimonials_page_title') ?: 'Testimonials'); ?>
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
      <?php echo esc_html(myco_get_field('testimonials_page_subtitle') ?: 'Watch what our community, scholars, and supporters have to say about MYCO'); ?>
    </p>
  </div>
</section>

<?php
// Get text testimonials (testimonial CPT)
$text_query = new WP_Query(array(
    'post_type'      => 'testimonial',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
    'orderby'        => 'meta_value_num',
    'meta_key'       => 'sort_order',
    'order'          => 'ASC',
));
?>

<!-- Text Testimonials Section -->
<?php if ($text_query->have_posts()) : ?>
<section style="background: #ffffff; padding: 80px 0 60px; position: relative; margin-top: 0;">
  <div class="inner">

    <!-- Section Header -->
    <div style="text-align: center; margin-bottom: 56px;">
      <span style="color: #C8402E; font-weight: 700; font-size: 0.88rem; letter-spacing: 0.05em; display: block; margin-bottom: 12px;">
        <?php echo esc_html(myco_get_field('testimonials_text_label') ?: 'WHAT THEY SAY'); ?>
      </span>
      <h2 style="color: #141943; font-weight: 800; font-size: clamp(1.9rem, 4.5vw, 3.0rem); line-height: 1.1; letter-spacing: -0.01em; margin: 0 auto; max-width: 680px;">
        <?php echo esc_html(myco_get_field('testimonials_text_heading') ?: 'Words from Our Community'); ?>
      </h2>
    </div>

    <!-- Text Cards Grid -->
    <div class="testi-text-grid">
      <?php
      $star_svg = '<svg width="18" height="18" viewBox="0 0 20 20" fill="#C8402E" aria-hidden="true"><path d="M10 1l2.39 4.84 5.35.78-3.87 3.77.91 5.32L10 13.27l-4.78 2.44.91-5.32L2.26 6.62l5.35-.78z"/></svg>';
      while ($text_query->have_posts()) : $text_query->the_post();
        $t_name  = get_the_title();
        $t_quote = myco_get_field('testimonial_quote') ?: '';
        $t_role  = myco_get_field('testimonial_role') ?: '';
        $t_photo = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
      ?>
      <div class="testi-text-card">
        <span class="testi-watermark" aria-hidden="true">&#8220;&#8221;</span>
        <div class="testi-content">
          <div class="testi-stars" aria-label="5 out of 5 stars" style="display:flex;gap:2px;margin-bottom:14px;">
            <?php echo str_repeat($star_svg, 5); ?>
          </div>
          <p class="testi-quote" style="color:#141943;font-size:1rem;line-height:1.7;font-style:italic;flex:1;margin:0 0 20px;">&ldquo;<?php echo esc_html($t_quote); ?>&rdquo;</p>
          <div class="testi-author" style="display:flex;align-items:center;gap:14px;margin-top:auto;">
            <?php if ($t_photo) : ?>
              <img src="<?php echo esc_url($t_photo); ?>" alt="<?php echo esc_attr($t_name); ?>" class="testi-avatar" style="width:52px;height:52px;border-radius:50%;object-fit:cover;flex-shrink:0;border:3px solid #F5F6FA;" />
            <?php else : ?>
              <div style="width:52px;height:52px;border-radius:50%;background:#141943;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#fff;font-size:20px;font-weight:700;"><?php echo esc_html(mb_substr($t_name, 0, 1)); ?></div>
            <?php endif; ?>
            <div>
              <p class="testi-author-name" style="color:#141943;font-weight:700;font-size:0.95rem;margin:0 0 2px;"><?php echo esc_html($t_name); ?></p>
              <?php if ($t_role) : ?>
                <p class="testi-author-role" style="color:#C8402E;font-size:0.82rem;font-weight:600;margin:0;"><?php echo esc_html($t_role); ?></p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>

  </div>
</section>
<?php endif; ?>

<!-- Videos Section -->
<section style="background: #F5F6FA; padding: 60px 0 100px; position: relative; margin-top: 0;">
  <div class="inner">

    <!-- Section Header -->
    <div style="text-align: center; margin-bottom: 48px;">
      <span style="color: #C8402E; font-weight: 700; font-size: 0.88rem; letter-spacing: 0.05em; display: block; margin-bottom: 12px;">
        <?php echo esc_html(myco_get_field('testimonials_videos_label') ?: 'VIDEO TESTIMONIALS'); ?>
      </span>
      <h2 style="color: #141943; font-weight: 800; font-size: clamp(1.9rem, 4.5vw, 3.0rem); line-height: 1.1; letter-spacing: -0.01em; margin: 0 auto; max-width: 680px;">
        <?php echo esc_html(myco_get_field('testimonials_videos_heading') ?: 'Hear from Our Community'); ?>
      </h2>
    </div>

    <!-- Videos Grid -->
    <div class="testi-videos-grid" id="testimonials-videos" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 28px;">
      <?php if ($video_query->have_posts()) : ?>
        <?php while ($video_query->have_posts()) : $video_query->the_post();
          $caption = myco_get_field('video_caption') ?: get_the_title();
          $video_url = myco_get_field('video_url');
          $video_type = myco_get_field('video_type') ?: 'youtube';
          $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'myco-gallery');

          $video_id = '';
          $embed_url = '';
          if ($video_url) {
              if ($video_type === 'youtube' && preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $video_url, $match)) {
                  $video_id = $match[1];
                  $embed_url = 'https://www.youtube.com/embed/' . $video_id;
              } elseif ($video_type === 'vimeo' && preg_match('/vimeo\.com\/(\d+)/i', $video_url, $match)) {
                  $video_id = $match[1];
                  $embed_url = 'https://player.vimeo.com/video/' . $video_id;
              } else {
                  $embed_url = $video_url;
              }
          }
        ?>
        <div class="testi-video-item"
             data-caption="<?php echo esc_attr($caption); ?>"
             data-video-url="<?php echo esc_url($video_url); ?>"
             data-embed-url="<?php echo esc_url($embed_url); ?>"
             data-video-type="<?php echo esc_attr($video_type); ?>"
             data-video-id="<?php echo esc_attr($video_id); ?>"
             onclick="openVideoLightbox(this)"
             style="aspect-ratio: 16 / 9; cursor: pointer; position: relative; border-radius: 16px; overflow: hidden; background: #1a1a1a; box-shadow: 0 4px 20px rgba(20, 25, 67, 0.14);">
          <?php if ($thumbnail_url) : ?>
            <img src="<?php echo esc_url($thumbnail_url); ?>"
                 alt="<?php echo esc_attr($caption); ?>"
                 style="width: 100%; height: 100%; object-fit: cover; display: block;" />
          <?php else : ?>
            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); display: flex; align-items: center; justify-content: center;">
              <svg width="64" height="64" viewBox="0 0 64 64" fill="none">
                <circle cx="32" cy="32" r="30" fill="#2a2a2a"/>
                <path d="M26 22l16 10-16 10V22z" fill="#C8402E"/>
              </svg>
            </div>
          <?php endif; ?>
          <div class="testi-video-play-overlay" style="
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s;
          ">
            <div class="testi-play-button" style="
              width: 72px;
              height: 72px;
              border-radius: 50%;
              background: rgba(200, 64, 46, 0.95);
              display: flex;
              align-items: center;
              justify-content: center;
              transition: transform 0.3s, background 0.3s;
              box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
            ">
              <svg width="32" height="32" viewBox="0 0 24 24" fill="none" style="margin-left: 3px;">
                <path d="M8 5v14l11-7L8 5z" fill="white"/>
              </svg>
            </div>
          </div>
          <div class="testi-video-overlay" style="
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.8) 100%);
            opacity: 0;
            transition: opacity .3s;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 20px;
            pointer-events: none;
          ">
            <div style="color: #ffffff; font-size: 16px; font-weight: 700; transform: translateY(10px); transition: transform .3s ease-out;">
              <?php echo esc_html($caption); ?>
            </div>
          </div>
        </div>
        <?php endwhile; wp_reset_postdata(); ?>
      <?php else : ?>
        <div style="grid-column: 1 / -1; text-align: center; padding: 80px 20px; background: #ffffff; border-radius: 20px; border: 2px dashed #E5E7EB;">
          <svg width="64" height="64" viewBox="0 0 64 64" fill="none" style="margin: 0 auto 20px;">
            <circle cx="32" cy="32" r="30" fill="#F3F4F6"/>
            <path d="M26 22l16 10-16 10V22z" fill="#9CA3AF"/>
          </svg>
          <p style="font-size: 18px; color: #6B7280; font-weight: 500; margin-bottom: 8px;">No video testimonials yet.</p>
          <p style="font-size: 14px; color: #9CA3AF;">Check back soon for community testimonial videos.</p>
        </div>
      <?php endif; ?>
    </div>

  </div>
</section>

<!-- CTA Section -->
<section style="background: linear-gradient(135deg, #141943 0%, #1e2a5a 100%); padding: 90px 0; position: relative; overflow: hidden;">
  <div aria-hidden="true" style="position: absolute; inset: 0; pointer-events: none; z-index: 0; opacity: 0.06; background-image: url('data:image/svg+xml,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%271920%27 height=%27300%27 fill=%27none%27%3E%3Cpath d=%27M-60 80 C400 -20 800 180 1300 60 S1700 -40 1980 80%27 stroke=%27white%27 stroke-width=%271.2%27/%3E%3Cpath d=%27M-60 160 C400 60 800 260 1300 140 S1700 40 1980 160%27 stroke=%27white%27 stroke-width=%271.2%27/%3E%3Cpath d=%27M-60 240 C400 140 800 340 1300 220 S1700 120 1980 240%27 stroke=%27white%27 stroke-width=%271.2%27/%3E%3C/svg%3E'); background-size: 1920px 300px; background-repeat: no-repeat;"></div>
  <div class="inner" style="position: relative; z-index: 2; text-align: center;">
    <h2 style="font-size: 48px; font-weight: 900; color: #ffffff; line-height: 1.15; letter-spacing: -0.01em; margin-bottom: 20px;">
      <?php echo esc_html(myco_get_field('testimonials_cta_heading') ?: 'Be Part of Our Story'); ?>
    </h2>
    <p style="font-size: 19px; color: #B8C8DC; line-height: 1.6; max-width: 680px; margin: 0 auto 40px; font-weight: 400;">
      <?php echo esc_html(myco_get_field('testimonials_cta_text') ?: 'Join us in empowering the next generation of Muslim youth through faith, mentorship, and community'); ?>
    </p>
    <div style="display: flex; gap: 16px; align-items: center; justify-content: center; flex-wrap: wrap;">
      <a href="<?php echo esc_url(myco_get_page_url('donate', '/donate/')); ?>" style="
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #C8402E;
        color: #fff;
        height: 52px;
        padding: 0 32px;
        border-radius: 9999px;
        font-size: 16px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        transition: background .2s, transform .15s, box-shadow .2s;
        box-shadow: 0 4px 18px rgba(200, 64, 46, 0.45);
      " onmouseover="this.style.background='#b03426'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 24px rgba(200, 64, 46, 0.55)'" onmouseout="this.style.background='#C8402E'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 18px rgba(200, 64, 46, 0.45)'">Support Our Programs</a>
      <a href="<?php echo esc_url(home_url('/#volunteer')); ?>" style="
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        color: #fff;
        height: 52px;
        padding: 0 32px;
        border-radius: 9999px;
        border: 2px solid rgba(255, 255, 255, 0.85);
        font-size: 16px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        transition: background .2s, transform .15s;
      " onmouseover="this.style.background='rgba(255, 255, 255, 0.13)'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='transparent'; this.style.transform='translateY(0)'">Get Involved</a>
    </div>
  </div>
</section>

<!-- Video Lightbox Modal -->
<div id="video-lightbox" class="video-lightbox" role="dialog" aria-modal="true" aria-label="Video player" style="
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.95);
  z-index: 99999;
  display: none;
  align-items: center;
  justify-content: center;
  padding: 40px;
">
  <div class="video-lightbox-content" style="position: relative; max-width: 1200px; width: 100%; aspect-ratio: 16 / 9; display: flex; flex-direction: column; align-items: center;">

    <!-- Close Button -->
    <button class="video-lightbox-close" onclick="closeVideoLightbox()" aria-label="Close video" style="
      position: fixed;
      top: 20px;
      right: 20px;
      width: 48px;
      height: 48px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.15);
      border: 2px solid rgba(255, 255, 255, 0.3);
      color: #ffffff;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all .2s;
      z-index: 100002;
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
    " onmouseover="this.style.background='#C8402E'; this.style.borderColor='#C8402E'; this.style.transform='rotate(90deg)'" onmouseout="this.style.background='rgba(255, 255, 255, 0.15)'; this.style.borderColor='rgba(255, 255, 255, 0.3)'; this.style.transform='rotate(0)'">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
        <path d="M18 6L6 18M6 6l12 12" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
      </svg>
    </button>

    <!-- Video Container -->
    <div id="video-container" style="
      width: 100%;
      height: 100%;
      background: #000;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 25px 70px rgba(0, 0, 0, 0.6);
    "></div>

    <!-- Video Caption -->
    <div id="video-caption" style="
      position: fixed;
      bottom: 30px;
      left: 50%;
      transform: translateX(-50%);
      max-width: 800px;
      text-align: center;
      color: #ffffff;
      font-size: 18px;
      font-weight: 600;
      padding: 16px 32px;
      background: rgba(0, 0, 0, 0.7);
      border-radius: 12px;
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
      z-index: 100001;
    "></div>
  </div>
</div>

<style>
.page-template-template-testimonials section {
  margin: 0 !important;
}
.page-template-template-testimonials .page-hero-bg + section {
  margin-top: 0 !important;
}

/* Text testimonial cards grid */
.testi-text-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 28px;
}
.testi-text-card {
  position: relative;
  background: #ffffff;
  border: 1.5px solid rgba(20, 25, 67, 0.12);
  border-radius: 20px;
  box-shadow: 0 10px 30px rgba(20, 25, 67, 0.06);
  padding: 36px 30px 30px;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  transition: box-shadow 0.3s, transform 0.3s;
}
.testi-text-card:hover {
  box-shadow: 0 20px 50px rgba(20, 25, 67, 0.12);
  transform: translateY(-3px);
}
.testi-text-card .testi-watermark {
  position: absolute;
  top: -8px;
  left: 20px;
  font-size: 120px;
  line-height: 1;
  color: rgba(200, 64, 46, 0.08);
  font-family: Georgia, serif;
  pointer-events: none;
  user-select: none;
}
.testi-text-card .testi-content {
  position: relative;
  z-index: 1;
  display: flex;
  flex-direction: column;
  height: 100%;
}

@media (max-width: 1100px) {
  .testi-text-grid {
    grid-template-columns: repeat(2, 1fr) !important;
  }
}
@media (max-width: 640px) {
  .testi-text-grid {
    grid-template-columns: 1fr !important;
  }
}

/* Video card hover effects */
.testi-video-item:hover .testi-video-play-overlay {
  background: rgba(0, 0, 0, 0.5);
}
.testi-video-item:hover .testi-play-button {
  transform: scale(1.15);
}
.testi-video-item:hover .testi-video-overlay {
  opacity: 1 !important;
}
.testi-video-item:hover .testi-video-overlay div {
  transform: translateY(0) !important;
}

.video-lightbox.active {
  display: flex !important;
}

@media (max-width: 1100px) {
  .testi-videos-grid {
    grid-template-columns: repeat(2, 1fr) !important;
  }
}
@media (max-width: 640px) {
  .testi-videos-grid {
    grid-template-columns: 1fr !important;
  }
}
</style>

<script>
function openVideoLightbox(element) {
  const embedUrl = element.getAttribute('data-embed-url');
  const caption = element.getAttribute('data-caption');
  const videoType = element.getAttribute('data-video-type');

  if (!embedUrl) return;

  const videoLightbox = document.getElementById('video-lightbox');
  const videoContainer = document.getElementById('video-container');
  const videoCaption = document.getElementById('video-caption');

  if (!videoLightbox || !videoContainer) return;

  videoContainer.innerHTML = '';

  const iframe = document.createElement('iframe');
  iframe.style.width = '100%';
  iframe.style.height = '100%';
  iframe.style.border = 'none';
  iframe.setAttribute('allowfullscreen', '');
  iframe.setAttribute('allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture');

  if (videoType === 'youtube') {
    iframe.src = embedUrl + '?autoplay=1&rel=0';
  } else if (videoType === 'vimeo') {
    iframe.src = embedUrl + '?autoplay=1';
  } else {
    const video = document.createElement('video');
    video.style.width = '100%';
    video.style.height = '100%';
    video.controls = true;
    video.autoplay = true;
    video.src = embedUrl;
    videoContainer.appendChild(video);
    videoCaption.textContent = caption || '';
    videoLightbox.classList.add('active');
    videoLightbox.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    return;
  }

  videoContainer.appendChild(iframe);
  videoCaption.textContent = caption || '';
  videoLightbox.classList.add('active');
  videoLightbox.style.display = 'flex';
  document.body.style.overflow = 'hidden';
}

function closeVideoLightbox() {
  const videoLightbox = document.getElementById('video-lightbox');
  const videoContainer = document.getElementById('video-container');
  if (videoLightbox) {
    videoLightbox.classList.remove('active');
    videoLightbox.style.display = 'none';
    document.body.style.overflow = '';
    if (videoContainer) videoContainer.innerHTML = '';
  }
}

document.addEventListener('DOMContentLoaded', () => {
  const videoLightbox = document.getElementById('video-lightbox');
  if (videoLightbox) {
    videoLightbox.addEventListener('click', (e) => {
      if (e.target.id === 'video-lightbox') closeVideoLightbox();
    });
  }
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      const vl = document.getElementById('video-lightbox');
      if (vl && vl.classList.contains('active')) closeVideoLightbox();
    }
  });
});
</script>

<?php get_footer(); ?>
