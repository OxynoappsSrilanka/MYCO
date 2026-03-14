<?php
/**
 * Template Name: Privacy Policy
 * @package MYCO
 */
get_header();
?>

<!-- Hero Section -->
<?php get_template_part('template-parts/hero/hero-breadcrumb-dark', null, array(
    'title' => 'Privacy Policy',
    'subtitle' => 'How we collect, use, and protect your personal information',
)); ?>

<!-- Effective Date Badge (below hero) -->
<section style="background: linear-gradient(130deg, #111640 0%, #182050 40%, #2a3e6a 100%); padding: 0 0 80px; margin-top: -80px; position: relative;">
  <div class="inner" style="position: relative; z-index: 2; text-align: center;">
    <div style="display: inline-block; background: rgba(255, 255, 255, 0.12); border: 1px solid rgba(255, 255, 255, 0.20); border-radius: 9999px; padding: 8px 20px;">
      <span style="font-size: 14px; font-weight: 600; color: #fff;">
        Effective Date: <?php echo esc_html(myco_get_field('effective_date') ?: 'January 1, 2024'); ?>
      </span>
    </div>
  </div>
</section>

<!-- Introduction Section -->
<section style="background: #ffffff; padding: 90px 0 60px; position: relative;">
  <div class="inner">
    <div style="max-width: 900px; margin: 0 auto;">
      <?php
      $intro = myco_get_field('privacy_introduction');
      if ($intro) :
        echo wp_kses_post($intro);
      else :
      ?>
      <p style="font-size: 18px; color: #5B6575; line-height: 1.75; margin-bottom: 20px; font-weight: 400;">
        At MYCO (Moving the Community Forward), we are committed to protecting your privacy and ensuring
        the security of your personal information. This Privacy Policy explains how we collect, use,
        disclose, and safeguard your information when you visit our website or participate in our programs.
      </p>
      <p style="font-size: 18px; color: #5B6575; line-height: 1.75; margin-bottom: 20px; font-weight: 400;">
        By using our website or services, you agree to the collection and use of information in accordance
        with this policy. If you do not agree with our policies and practices, please do not use our services.
      </p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Accordion Sections -->
<section style="background: #F5F6FA; padding: 60px 0 110px; position: relative;">
  <div class="inner">
    <div style="max-width: 1000px; margin: 0 auto;">
      <?php
      $sections = myco_get_field('privacy_sections');
      if ($sections) :
        foreach ($sections as $i => $section) :
      ?>
      <div class="accordion-item<?php echo $i === 0 ? ' active' : ''; ?>">
        <button class="accordion-header" onclick="toggleAccordion(this)">
          <h2 class="accordion-title"><?php echo esc_html(($i + 1) . '. ' . $section['title']); ?></h2>
          <div class="accordion-icon">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
              <path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
        </button>
        <div class="accordion-content">
          <div style="color: #5B6575; font-size: 16px; line-height: 1.7;">
            <?php echo wp_kses_post($section['content']); ?>
          </div>
        </div>
      </div>
      <?php
        endforeach;
      else :
      ?>
      <!-- Default accordion items -->
      <?php
      $default_sections = array(
        array('title' => 'Information We Collect', 'content' => '<h3 style="font-size: 18px; font-weight: 700; color: #141943; margin: 20px 0 12px;">Personal Information</h3><p style="margin-bottom: 14px;">We may collect personal information that you voluntarily provide to us when you register for programs, sign up for volunteer opportunities, make donations, subscribe to our newsletter, or contact us through our website.</p>'),
        array('title' => 'How We Use Your Information', 'content' => '<p style="margin-bottom: 14px;">We use the information we collect for program administration, communication, donation processing, safety and security, website improvement, volunteer coordination, and legal compliance.</p>'),
        array('title' => 'Information Sharing and Disclosure', 'content' => '<p style="margin-bottom: 14px;">MYCO does not sell, trade, or rent your personal information to third parties. We may share your information with service providers, when required by law, for safety and protection, or with your consent.</p>'),
        array('title' => 'Data Security', 'content' => '<p style="margin-bottom: 14px;">We implement appropriate technical and organizational security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>'),
        array('title' => 'Your Rights and Choices', 'content' => '<p style="margin-bottom: 14px;">You have the right to access, correct, delete, opt-out of marketing communications, and request data portability. Contact us at privacy@myco.org to exercise these rights.</p>'),
        array('title' => 'Children\'s Privacy', 'content' => '<p style="margin-bottom: 14px;">MYCO serves youth and minors through our programs. We take special care to protect the privacy of children under 18, requiring parental consent and limiting information collection.</p>'),
        array('title' => 'Changes to This Privacy Policy', 'content' => '<p style="margin-bottom: 14px;">We may update this Privacy Policy from time to time. We will notify you of material changes by posting the updated policy, sending email notifications, and displaying a prominent notice on our homepage.</p>'),
        array('title' => 'Contact Us', 'content' => '<p style="margin-bottom: 14px;">If you have questions about this Privacy Policy, contact us:</p><div style="background: #F5F6FA; border-radius: 12px; padding: 24px; margin-top: 20px;"><p style="margin-bottom: 12px;"><strong style="color: #141943;">MYCO – Moving the Community Forward</strong></p><p style="margin-bottom: 8px;">123 Community Street, Columbus, OH 43215</p><p style="margin-bottom: 8px;"><strong>Phone:</strong> (614) 555-MYCO</p><p style="margin-bottom: 8px;"><strong>Email:</strong> <a href="mailto:privacy@myco.org" style="color: #C8402E; text-decoration: underline;">privacy@myco.org</a></p></div>'),
      );
      foreach ($default_sections as $i => $section) :
      ?>
      <div class="accordion-item<?php echo $i === 0 ? ' active' : ''; ?>">
        <button class="accordion-header" onclick="toggleAccordion(this)">
          <h2 class="accordion-title"><?php echo esc_html(($i + 1) . '. ' . $section['title']); ?></h2>
          <div class="accordion-icon">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
              <path d="M4 6l4 4 4-4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
        </button>
        <div class="accordion-content">
          <div style="color: #5B6575; font-size: 16px; line-height: 1.7;">
            <?php echo wp_kses_post($section['content']); ?>
          </div>
        </div>
      </div>
      <?php
        endforeach;
      endif;
      ?>
    </div>
  </div>
</section>

<?php get_footer(); ?>
