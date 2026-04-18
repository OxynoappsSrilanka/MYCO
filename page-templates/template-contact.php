<?php
/**
 * Template Name: Contact
 * @package MYCO
 */

$contact_locations = myco_get_contact_locations();
$myco_location     = $contact_locations['myco'];
$mcyc_location     = $contact_locations['mcyc'];
$shared_phone      = myco_get_shared_phone();

get_header();
?>

<!-- Hero Banner Section with Full Width Blurred Background -->
<section class="page-hero-bg" style="
  background: url('<?php echo esc_url(myco_get_field('contact_banner_image') ?: get_template_directory_uri() . '/assets/images/meeting.jpg'); ?>') center center / cover no-repeat;
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
      <span style="font-size: 14px; font-weight: 600; color: #ffffff;">Contact</span>
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
      <?php echo esc_html(myco_get_field('contact_title') ?: 'Get In Touch'); ?>
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
      <?php echo esc_html(myco_get_field('contact_subtitle') ?: "We'd love to hear from you. Reach out with any questions about our programs or community initiatives"); ?>
    </p>
  </div>
</section>

<!-- Contact Info Cards -->
<section style="background: #F5F6FA; padding: 90px 0 60px; position: relative;">
  <div class="inner">
    <div class="contact-cards" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; margin-bottom: 60px;">

      <?php
      $contact_cards = myco_get_field('contact_cards');
      if ($contact_cards) :
        foreach ($contact_cards as $card) :
      ?>
      <div class="contact-card" style="background: #ffffff; border-radius: 20px; padding: 40px 32px; text-align: center; box-shadow: 0 8px 24px rgba(20, 25, 67, 0.08); border: 1px solid rgba(20, 25, 67, 0.05); transition: transform 0.25s, box-shadow 0.25s; display: flex; flex-direction: column; align-items: center;">
        <div class="contact-icon" style="width: 72px; height: 72px; border-radius: 18px; background: linear-gradient(135deg, #C8402E 0%, #e05040 100%); display: flex; align-items: center; justify-content: center; margin-bottom: 24px; box-shadow: 0 8px 20px rgba(200, 64, 46, 0.3); flex-shrink: 0;">
          <?php echo wp_kses_post($card['icon_svg']); ?>
        </div>
        <h3 style="font-size: 22px; font-weight: 800; color: #141943; margin-bottom: 12px;"><?php echo esc_html($card['title']); ?></h3>
        <div class="contact-card-body">
          <p class="contact-card-details" style="font-size: 15px; color: #5B6575; line-height: 1.7; margin-bottom: 16px;">
            <?php echo wp_kses_post(nl2br($card['details'])); ?>
          </p>
        </div>
        <?php if (!empty($card['link_url'])) : ?>
        <a href="<?php echo esc_url($card['link_url']); ?>" class="contact-card-cta" style="font-size: 14px; font-weight: 600; color: #C8402E; text-decoration: none;">
          <?php echo esc_html($card['link_text']); ?> &rarr;
        </a>
        <?php endif; ?>
      </div>
      <?php
        endforeach;
      else :
      ?>
      <!-- Default cards when ACF not set -->
      <div class="contact-card" style="background: #ffffff; border-radius: 20px; padding: 40px 32px; text-align: center; box-shadow: 0 8px 24px rgba(20, 25, 67, 0.08); border: 1px solid rgba(20, 25, 67, 0.05); transition: transform 0.25s, box-shadow 0.25s; display: flex; flex-direction: column; align-items: center;">
        <div class="contact-icon" style="width: 72px; height: 72px; border-radius: 18px; background: linear-gradient(135deg, #C8402E 0%, #e05040 100%); display: flex; align-items: center; justify-content: center; margin-bottom: 24px; box-shadow: 0 8px 20px rgba(200, 64, 46, 0.3); flex-shrink: 0;">
          <svg width="36" height="36" viewBox="0 0 36 36" fill="none">
            <path d="M18 32c0 0-12-8-12-18a12 12 0 0 1 24 0c0 10-12 18-12 18Z" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            <circle cx="18" cy="14" r="4" stroke="#fff" stroke-width="2.5"/>
          </svg>
        </div>
        <h3 style="font-size: 22px; font-weight: 800; color: #141943; margin-bottom: 12px;">Visit Us</h3>
        <div class="contact-card-body">
          <p class="contact-card-details" style="font-size: 15px; color: #5B6575; line-height: 1.7; margin-bottom: 16px;">
            <span style="display: block; margin-bottom: 16px;">
              <span style="display: block; font-size: 12px; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: #C8402E; margin-bottom: 6px;">MYCO</span>
              <?php echo esc_html($myco_location['name']); ?><br>
              <?php echo esc_html($myco_location['street']); ?><br>
              <?php echo esc_html($myco_location['city_state_zip']); ?>
            </span>
            <span style="display: block;">
              <span style="display: block; font-size: 12px; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: #C8402E; margin-bottom: 6px;">MCYC</span>
              <?php echo esc_html($mcyc_location['name']); ?><br>
              <?php echo esc_html($mcyc_location['street']); ?><br>
              <?php echo esc_html($mcyc_location['city_state_zip']); ?>
            </span>
          </p>
        </div>
        <a href="#contact-location-list" class="contact-card-cta" style="font-size: 14px; font-weight: 600; color: #C8402E; text-decoration: none;">View Locations &rarr;</a>
      </div>

      <div class="contact-card" style="background: #ffffff; border-radius: 20px; padding: 40px 32px; text-align: center; box-shadow: 0 8px 24px rgba(20, 25, 67, 0.08); border: 1px solid rgba(20, 25, 67, 0.05); transition: transform 0.25s, box-shadow 0.25s; display: flex; flex-direction: column; align-items: center;">
        <div class="contact-icon" style="width: 72px; height: 72px; border-radius: 18px; background: linear-gradient(135deg, #C8402E 0%, #e05040 100%); display: flex; align-items: center; justify-content: center; margin-bottom: 24px; box-shadow: 0 8px 20px rgba(200, 64, 46, 0.3); flex-shrink: 0;">
          <svg width="36" height="36" viewBox="0 0 36 36" fill="none">
            <path d="M33 25.5v4.5a3 3 0 0 1-3.27 3c-12.96-1.29-23.43-11.76-24.72-24.72A3 3 0 0 1 8.01 5h4.5a3 3 0 0 1 3 2.58c.19 1.424.54 2.82 1.05 4.17a3 3 0 0 1-.675 3.165l-1.905 1.905a24 24 0 0 0 9 9l1.905-1.905a3 3 0 0 1 3.165-.675c1.35.51 2.746.86 4.17 1.05A3 3 0 0 1 33 27.5Z" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <h3 style="font-size: 22px; font-weight: 800; color: #141943; margin-bottom: 12px;">Call Us</h3>
        <div class="contact-card-body">
          <p class="contact-card-details" style="font-size: 15px; color: #5B6575; line-height: 1.7; margin-bottom: 16px;">
            Main Office:<br><?php echo esc_html($shared_phone); ?><br>Mon-Fri: 9am - 6pm
          </p>
        </div>
        <a href="tel:+1<?php echo esc_attr(preg_replace('/\D+/', '', $shared_phone)); ?>" class="contact-card-cta" style="font-size: 14px; font-weight: 600; color: #C8402E; text-decoration: none;">Call Now &rarr;</a>
      </div>

      <div class="contact-card" style="background: #ffffff; border-radius: 20px; padding: 40px 32px; text-align: center; box-shadow: 0 8px 24px rgba(20, 25, 67, 0.08); border: 1px solid rgba(20, 25, 67, 0.05); transition: transform 0.25s, box-shadow 0.25s; display: flex; flex-direction: column; align-items: center;">
        <div class="contact-icon" style="width: 72px; height: 72px; border-radius: 18px; background: linear-gradient(135deg, #C8402E 0%, #e05040 100%); display: flex; align-items: center; justify-content: center; margin-bottom: 24px; box-shadow: 0 8px 20px rgba(200, 64, 46, 0.3); flex-shrink: 0;">
          <svg width="36" height="36" viewBox="0 0 36 36" fill="none">
            <rect x="3" y="6" width="30" height="24" rx="4" stroke="#fff" stroke-width="2.5"/>
            <path d="M3 10l15 10 15-10" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <h3 style="font-size: 22px; font-weight: 800; color: #141943; margin-bottom: 12px;">Email Us</h3>
        <div class="contact-card-body">
          <p class="contact-card-details" style="font-size: 15px; color: #5B6575; line-height: 1.7; margin-bottom: 16px;">
            General Inquiries:<br>info@mycohio.org<br>We reply within 24 hours
          </p>
        </div>
        <a href="mailto:info@mycohio.org" class="contact-card-cta" style="font-size: 14px; font-weight: 600; color: #C8402E; text-decoration: none;">Send Email &rarr;</a>
      </div>
      <?php endif; ?>

    </div>
  </div>
</section>

<!-- Contact Form & Map Section -->
<section style="background: #ffffff; padding: 90px 0; position: relative;">
  <div class="inner">
    <div class="contact-layout" style="display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: flex-start;">

      <!-- Left Column: Contact Form -->
      <div style="display: flex; flex-direction: column; height: 100%;">
        <div style="margin-bottom: 40px;">
          <p style="font-size: 15px; font-weight: 700; color: #C8402E; margin-bottom: 12px; letter-spacing: 0.02em;">Send Us a Message</p>
          <h2 style="font-size: 48px; font-weight: 900; color: #141943; line-height: 1.1; letter-spacing: -0.02em; margin-bottom: 18px;">Contact Form</h2>
          <p style="font-size: 17px; color: #5B6575; line-height: 1.65;">
            Fill out the form below and we'll get back to you as soon as possible
          </p>
        </div>

        <?php
        // Use Contact Form 7 if available
        $cf7_id = myco_get_field('contact_form_id');
        if ($cf7_id && shortcode_exists('contact-form-7')) :
            echo do_shortcode('[contact-form-7 id="' . intval($cf7_id) . '"]');
        else :
        ?>
        <!-- Success message (hidden by default) -->
        <div id="contact-success" style="
            display: none;
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: #ffffff;
            padding: 24px 32px;
            border-radius: 16px;
            text-align: center;
            margin-bottom: 32px;
        ">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" style="margin: 0 auto 16px;">
                <circle cx="24" cy="24" r="22" stroke="#fff" stroke-width="3"/>
                <path d="M14 24l8 8 12-12" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <h3 style="font-size: 24px; font-weight: 800; margin-bottom: 8px;"><?php esc_html_e('Message Sent!', 'myco'); ?></h3>
            <p style="font-size: 16px; opacity: 0.95;"><?php esc_html_e('Thank you for reaching out. We will get back to you shortly.', 'myco'); ?></p>
        </div>

        <!-- Fallback Contact Form -->
        <form id="contact-form" style="background: #F5F6FA; border-radius: 20px; padding: 40px;" method="post">
          <input type="hidden" name="action" value="myco_contact_form" />
          <?php wp_nonce_field('myco_contact_nonce', 'contact_nonce'); ?>

          <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-bottom: 24px;">
            <div class="form-group">
              <label class="form-label" for="first-name">First Name *</label>
              <input type="text" id="first-name" name="first_name" class="form-input" required placeholder="John" />
            </div>
            <div class="form-group">
              <label class="form-label" for="last-name">Last Name *</label>
              <input type="text" id="last-name" name="last_name" class="form-input" required placeholder="Doe" />
            </div>
          </div>

          <div class="form-group">
            <label class="form-label" for="email">Email Address *</label>
            <input type="email" id="email" name="email" class="form-input" required placeholder="john.doe@example.com" />
          </div>

          <div class="form-group">
            <label class="form-label" for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone" class="form-input" placeholder="614 769 1949" />
          </div>

          <div class="form-group">
            <label class="form-label" for="subject">Subject *</label>
            <select id="subject" name="subject" class="form-select" required>
              <option value="">Select a subject</option>
              <option value="general">General Inquiry</option>
              <option value="programs">Programs Information</option>
              <option value="volunteer">Volunteer Opportunities</option>
              <option value="donation">Donation Questions</option>
              <option value="events">Events &amp; Activities</option>
              <option value="partnership">Partnership Opportunities</option>
              <option value="other">Other</option>
            </select>
          </div>

          <div class="form-group">
            <label class="form-label" for="message">Message *</label>
            <textarea id="message" name="message" class="form-textarea" required placeholder="Tell us how we can help you..."></textarea>
          </div>

          <button type="submit" class="pill-primary" style="width: 100%;">Send Message</button>
          <p style="font-size: 13px; color: #6B7280; margin-top: 16px; text-align: center;">* Required fields</p>
        </form>

        <script>
        (function() {
            var form = document.getElementById('contact-form');
            if (!form) return;
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(form);
                var btn = form.querySelector('button[type="submit"]');
                var originalText = btn.innerHTML;
                btn.innerHTML = 'Sending...';
                btn.disabled = true;

                fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin'
                })
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (data.success) {
                        document.getElementById('contact-success').style.display = 'block';
                        form.style.display = 'none';
                        document.getElementById('contact-success').scrollIntoView({ behavior: 'smooth', block: 'center' });
                    } else {
                        alert(data.data || 'Something went wrong. Please try again.');
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }
                })
                .catch(function() {
                    alert('Connection error. Please try again.');
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
            });
        })();
        </script>
        <?php endif; ?>
      </div>

      <!-- Right Column: Map & Office Hours -->
      <div id="contact-location-list" style="display: flex; flex-direction: column; height: 100%;">
        <div style="margin-bottom: 40px;">
          <p style="font-size: 15px; font-weight: 700; color: #C8402E; margin-bottom: 12px; letter-spacing: 0.02em;">Find Us</p>
          <h2 style="font-size: 48px; font-weight: 900; color: #141943; line-height: 1.1; letter-spacing: -0.02em; margin-bottom: 18px;">Our Location</h2>
          <p style="font-size: 17px; color: #5B6575; line-height: 1.65;">Visit MYCO and the future home of MCYC in Central Ohio.</p>
        </div>

        <!-- Map Container -->
        <div class="map-container" style="margin-bottom: 32px; border-radius: 16px; overflow: hidden; box-shadow: 0 8px 24px rgba(20, 25, 67, 0.12);">
          <?php
          $map_embed = myco_get_field('map_embed_url');
          if ($map_embed) :
          ?>
          <iframe src="<?php echo esc_url($map_embed); ?>" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
          <?php else : ?>
          <!-- Default Google Maps Embed for Columbus, OH -->
          <iframe 
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d196281.13149028436!2d-83.14284842968748!3d39.98355809999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x883889c1b990de71%3A0xe43266f8cfb1b533!2sColumbus%2C%20OH!5e0!3m2!1sen!2sus!4v1234567890123!5m2!1sen!2sus" 
            width="100%" 
            height="400" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
          </iframe>
          <?php endif; ?>
          <div style="background: white; padding: 20px; text-align: left; border-top: 1px solid #E5E7EB;">
            <?php
            $location_keys = array_keys($contact_locations);
            foreach ($location_keys as $position => $location_key) :
                $location = $contact_locations[$location_key];
                $is_last  = $position === array_key_last($location_keys);
            ?>
            <div style="<?php echo $is_last ? '' : 'padding-bottom: 18px; margin-bottom: 18px; border-bottom: 1px solid #E5E7EB;'; ?>">
              <p style="font-size: 12px; font-weight: 800; letter-spacing: 0.08em; text-transform: uppercase; color: #C8402E; margin-bottom: 8px;">
                <?php echo esc_html($location['short_label']); ?>
              </p>
              <p style="font-size: 15px; color: #374151; font-weight: 600; line-height: 1.7; margin-bottom: 12px;">
                <?php echo esc_html($location['name']); ?><br>
                <?php echo esc_html($location['street']); ?><br>
                <?php echo esc_html($location['city_state_zip']); ?>
              </p>
              <a href="<?php echo esc_url('https://www.google.com/maps/search/?api=1&query=' . rawurlencode($location['maps_query'])); ?>" target="_blank" rel="noopener" style="display: inline-flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; color: #C8402E; text-decoration: none; padding: 8px 16px; border-radius: 8px; background: rgba(200, 64, 46, 0.08); transition: all 0.2s;">
                <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
                  <path d="M10 18c0 0-7-5-7-11a7 7 0 0 1 14 0c0 6-7 11-7 11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <circle cx="10" cy="7" r="2" stroke="currentColor" stroke-width="2"/>
                </svg>
                Open in Google Maps
              </a>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Office Hours Card -->
        <div style="background: #F5F6FA; border-radius: 20px; padding: 36px; border: 1px solid rgba(20, 25, 67, 0.07);">
          <h3 style="font-size: 22px; font-weight: 800; color: #141943; margin-bottom: 24px;">Office Hours</h3>
          <div style="display: flex; flex-direction: column; gap: 16px;">
            <?php
            $office_hours = myco_get_option('office_hours');
            if ($office_hours) :
              foreach ($office_hours as $i => $hour) :
            ?>
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <span style="font-size: 15px; font-weight: 600; color: #374151;"><?php echo esc_html($hour['day']); ?></span>
              <span style="font-size: 15px; font-weight: 500; color: #6B7280;"><?php echo esc_html($hour['hours']); ?></span>
            </div>
            <?php if ($i < count($office_hours) - 1) : ?>
            <div style="height: 1px; background: #E2E6ED;"></div>
            <?php endif; ?>
            <?php
              endforeach;
            else :
            ?>
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <span style="font-size: 15px; font-weight: 600; color: #374151;">Monday - Friday</span>
              <span style="font-size: 15px; font-weight: 500; color: #6B7280;">9:00 AM - 6:00 PM</span>
            </div>
            <div style="height: 1px; background: #E2E6ED;"></div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <span style="font-size: 15px; font-weight: 600; color: #374151;">Saturday</span>
              <span style="font-size: 15px; font-weight: 500; color: #6B7280;">10:00 AM - 4:00 PM</span>
            </div>
            <div style="height: 1px; background: #E2E6ED;"></div>
            <div style="display: flex; justify-content: space-between; align-items: center;">
              <span style="font-size: 15px; font-weight: 600; color: #374151;">Sunday</span>
              <span style="font-size: 15px; font-weight: 500; color: #6B7280;">Closed</span>
            </div>
            <?php endif; ?>
          </div>

          <div style="margin-top: 24px; padding: 20px; background: rgba(200, 64, 46, 0.08); border-radius: 12px; border-left: 4px solid #C8402E;">
            <p style="font-size: 14px; color: #374151; line-height: 1.6;">
              <strong style="color: #C8402E;">Note:</strong> Program hours may vary. Please contact us for specific program schedules and availability.
            </p>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- <style>
  .faq-answer-custom p {
  margin: 0 0 14px;
  font-size: 18px;
  line-height: 1.6;
  color: #111;
}

.faq-answer-custom ul {
  margin: 0 0 18px 22px;
  padding: 0;
}

.faq-answer-custom li {
  margin: 0 0 10px;
  font-size: 18px;
  line-height: 1.6;
  color: #111;
}

.faq-answer-custom li strong {
  font-weight: 700;
}
</style> -->

<!-- FAQ Section -->
<section style="background: #F5F6FA; padding: 90px 0; position: relative;">
<div style="flex-direction: column; gap: 16px; max-width: 900px; margin: 0 auto; display: flex;">
  <?php
  $faqs = myco_get_field('faq_items');

  if ($faqs && is_array($faqs)) :
    foreach ($faqs as $faq) :
  ?>
    <div class="faq-item accordion-item">
      <button class="accordion-header">
        <span class="accordion-title"><?php echo esc_html($faq['question']); ?></span>
        <span class="accordion-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="6 9 12 15 18 9"></polyline>
          </svg>
        </span>
      </button>
      <div class="accordion-content">
        <?php echo wp_kses_post($faq['answer']); ?>
      </div>
    </div>
  <?php
    endforeach;
  else :
    $default_faqs = [
      [
        'question' => 'How would your donation help?',
        'answer'   => '<p>Your gift helps fund the costs required to build and establish the youth center, including planning, development, and campaign needs that move the project forward.</p>',
      ],
      [
        'question' => 'Will I receive a receipt for my donation?',
        'answer'   => '<p>Yes. A donation receipt will be provided for your records.</p>',
      ],
      [
        'question' => 'Are donations tax-deductible?',
        'answer'   => '<p>Yes. MYCO is a 501(c)(3) nonprofit organization, and donations made toward the MCYC Capital Campaign are tax-deductible to the extent permitted by law.</p>',
      ],
      [
        'question' => 'Is my payment secure?',
        'answer'   => '<p>Yes. Donations are processed through a secure online giving system. Payment information is encrypted and handled safely through the donation processor.</p>',
      ],
      [
        'question' => 'Can I make a pledge or commit to giving over time?',
        'answer'   => '
          <div class="faq-answer-custom">
            <p>Yes. Many donors choose to support the campaign through:</p>
            <ul>
              <li><strong> Monthly giving</strong></li>
              <li><strong> Multi-month or multi-year pledges</strong></li>
              <li><strong> One-time major gifts</strong></li>
            </ul>
            <p>If you’d like to set up a pledge or a custom giving plan, MYCO can assist you.</p>
          </div>
        ',
      ],
    ];

    foreach ($default_faqs as $faq) :
  ?>
    <div class="faq-item accordion-item">
      <button class="accordion-header">
        <span class="accordion-title"><?php echo esc_html($faq['question']); ?></span>
        <span class="accordion-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="6 9 12 15 18 9"></polyline>
          </svg>
        </span>
      </button>
      <div class="accordion-content">
        <?php echo wp_kses_post($faq['answer']); ?>
      </div>
    </div>
  <?php
    endforeach;
  endif;
  ?>
</div>
</section>

<?php get_footer(); ?>
