<?php
/**
 * Template Name: Contact
 * @package MYCO
 */
get_header();
?>

<!-- Hero Section -->
<?php get_template_part('template-parts/hero/hero-breadcrumb-dark', null, array(
    'title' => 'Get In Touch',
    'subtitle' => 'We\'d love to hear from you. Reach out with any questions about our programs or community initiatives',
)); ?>

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
        <p style="font-size: 15px; color: #5B6575; line-height: 1.7; margin-bottom: 16px;">
          <?php echo wp_kses_post(nl2br($card['details'])); ?>
        </p>
        <?php if (!empty($card['link_url'])) : ?>
        <a href="<?php echo esc_url($card['link_url']); ?>" style="font-size: 14px; font-weight: 600; color: #C8402E; text-decoration: none; margin-top: auto;">
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
        <p style="font-size: 15px; color: #5B6575; line-height: 1.7; margin-bottom: 16px;">
          123 Community Street<br>Columbus, OH 43215<br>United States
        </p>
        <a href="https://maps.google.com/?q=123+Community+Street+Columbus+OH+43215" target="_blank" style="font-size: 14px; font-weight: 600; color: #C8402E; text-decoration: none; margin-top: auto;">Get Directions &rarr;</a>
      </div>

      <div class="contact-card" style="background: #ffffff; border-radius: 20px; padding: 40px 32px; text-align: center; box-shadow: 0 8px 24px rgba(20, 25, 67, 0.08); border: 1px solid rgba(20, 25, 67, 0.05); transition: transform 0.25s, box-shadow 0.25s; display: flex; flex-direction: column; align-items: center;">
        <div class="contact-icon" style="width: 72px; height: 72px; border-radius: 18px; background: linear-gradient(135deg, #C8402E 0%, #e05040 100%); display: flex; align-items: center; justify-content: center; margin-bottom: 24px; box-shadow: 0 8px 20px rgba(200, 64, 46, 0.3); flex-shrink: 0;">
          <svg width="36" height="36" viewBox="0 0 36 36" fill="none">
            <path d="M33 25.5v4.5a3 3 0 0 1-3.27 3c-12.96-1.29-23.43-11.76-24.72-24.72A3 3 0 0 1 8.01 5h4.5a3 3 0 0 1 3 2.58c.19 1.424.54 2.82 1.05 4.17a3 3 0 0 1-.675 3.165l-1.905 1.905a24 24 0 0 0 9 9l1.905-1.905a3 3 0 0 1 3.165-.675c1.35.51 2.746.86 4.17 1.05A3 3 0 0 1 33 27.5Z" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <h3 style="font-size: 22px; font-weight: 800; color: #141943; margin-bottom: 12px;">Call Us</h3>
        <p style="font-size: 15px; color: #5B6575; line-height: 1.7; margin-bottom: 16px;">
          Main Office:<br>(614) 555-MYCO<br>Mon-Fri: 9am - 6pm
        </p>
        <a href="tel:+16145556926" style="font-size: 14px; font-weight: 600; color: #C8402E; text-decoration: none; margin-top: auto;">Call Now &rarr;</a>
      </div>

      <div class="contact-card" style="background: #ffffff; border-radius: 20px; padding: 40px 32px; text-align: center; box-shadow: 0 8px 24px rgba(20, 25, 67, 0.08); border: 1px solid rgba(20, 25, 67, 0.05); transition: transform 0.25s, box-shadow 0.25s; display: flex; flex-direction: column; align-items: center;">
        <div class="contact-icon" style="width: 72px; height: 72px; border-radius: 18px; background: linear-gradient(135deg, #C8402E 0%, #e05040 100%); display: flex; align-items: center; justify-content: center; margin-bottom: 24px; box-shadow: 0 8px 20px rgba(200, 64, 46, 0.3); flex-shrink: 0;">
          <svg width="36" height="36" viewBox="0 0 36 36" fill="none">
            <rect x="3" y="6" width="30" height="24" rx="4" stroke="#fff" stroke-width="2.5"/>
            <path d="M3 10l15 10 15-10" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </div>
        <h3 style="font-size: 22px; font-weight: 800; color: #141943; margin-bottom: 12px;">Email Us</h3>
        <p style="font-size: 15px; color: #5B6575; line-height: 1.7; margin-bottom: 16px;">
          General Inquiries:<br>info@mycohio.org<br>We reply within 24 hours
        </p>
        <a href="mailto:info@mycohio.org" style="font-size: 14px; font-weight: 600; color: #C8402E; text-decoration: none; margin-top: auto;">Send Email &rarr;</a>
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
            <input type="tel" id="phone" name="phone" class="form-input" placeholder="(614) 555-0123" />
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
      <div style="display: flex; flex-direction: column; height: 100%;">
        <div style="margin-bottom: 40px;">
          <p style="font-size: 15px; font-weight: 700; color: #C8402E; margin-bottom: 12px; letter-spacing: 0.02em;">Find Us</p>
          <h2 style="font-size: 48px; font-weight: 900; color: #141943; line-height: 1.1; letter-spacing: -0.02em; margin-bottom: 18px;">Our Location</h2>
          <p style="font-size: 17px; color: #5B6575; line-height: 1.65;">Visit our community center in the heart of Columbus</p>
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
          <div style="background: white; padding: 20px; text-align: center; border-top: 1px solid #E5E7EB;">
            <p style="font-size: 15px; color: #374151; font-weight: 600; margin-bottom: 8px;">
              <?php echo esc_html(myco_get_option('address') ?: '123 Community Street, Columbus, OH 43215'); ?>
            </p>
            <a href="https://www.google.com/maps/search/?api=1&query=123+Community+Street+Columbus+OH+43215" target="_blank" rel="noopener" style="display: inline-flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 600; color: #C8402E; text-decoration: none; padding: 8px 16px; border-radius: 8px; background: rgba(200, 64, 46, 0.08); transition: all 0.2s;">
              <svg width="18" height="18" viewBox="0 0 20 20" fill="none">
                <path d="M10 18c0 0-7-5-7-11a7 7 0 0 1 14 0c0 6-7 11-7 11Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="10" cy="7" r="2" stroke="currentColor" stroke-width="2"/>
              </svg>
              Open in Google Maps
            </a>
          </div>
          <?php endif; ?>
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

<!-- FAQ Section -->
<section style="background: #F5F6FA; padding: 90px 0; position: relative;">
  <div class="inner">
    <div style="text-align: center; margin-bottom: 60px;">
      <p style="font-size: 15px; font-weight: 700; color: #C8402E; margin-bottom: 12px; letter-spacing: 0.02em;">Quick Answers</p>
      <h2 style="font-size: 52px; font-weight: 900; color: #141943; line-height: 1.1; letter-spacing: -0.02em; margin-bottom: 18px;">Frequently Asked Questions</h2>
      <p style="font-size: 18px; color: #5B6575; line-height: 1.65; max-width: 680px; margin: 0 auto;">
        Find answers to common questions about MYCO programs and services
      </p>
    </div>

    <div style="flex-direction: column; gap: 16px; max-width: 900px; margin: 0 auto; display: flex;">
      <?php
      $faqs = myco_get_field('faq_items');
      if ($faqs) :
        foreach ($faqs as $faq) :
      ?>
      <div class="faq-item accordion-item">
        <button class="accordion-header">
          <span class="accordion-title"><?php echo esc_html($faq['question']); ?></span>
          <span class="accordion-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
          </span>
        </button>
        <div class="accordion-content">
          <p><?php echo esc_html($faq['answer']); ?></p>
        </div>
      </div>
      <?php
        endforeach;
      else :
      ?>
      <!-- Default FAQs -->
      <div class="faq-item accordion-item">
        <button class="accordion-header">
          <span class="accordion-title">What age groups do you serve?</span>
          <span class="accordion-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
          </span>
        </button>
        <div class="accordion-content">
          <p>MYCO serves youth ages 6-18 with age-appropriate programs for elementary, middle school, and high school students.</p>
        </div>
      </div>
      <div class="faq-item accordion-item">
        <button class="accordion-header">
          <span class="accordion-title">How do I register my child?</span>
          <span class="accordion-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
          </span>
        </button>
        <div class="accordion-content">
          <p>Visit our Programs page to browse available programs and click the registration link, or contact us directly for assistance.</p>
        </div>
      </div>
      <div class="faq-item accordion-item">
        <button class="accordion-header">
          <span class="accordion-title">Are programs free?</span>
          <span class="accordion-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
          </span>
        </button>
        <div class="accordion-content">
          <p>Many programs are free or low-cost. Financial assistance is available for families in need. Contact us to learn more.</p>
        </div>
      </div>
      <div class="faq-item accordion-item">
        <button class="accordion-header">
          <span class="accordion-title">Can I volunteer at MYCO?</span>
          <span class="accordion-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
          </span>
        </button>
        <div class="accordion-content">
          <p>Yes! We welcome volunteers. Visit our Volunteer page to learn about opportunities and complete the registration form.</p>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
