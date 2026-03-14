<?php
/**
 * Light Footer (Home & About pages)
 *
 * @package MYCO
 */

$description = myco_get_option('footer_description', 'Empowering Muslim Youth of Central Ohio through education, leadership, and community service. Building a brighter future together.');
$address     = myco_get_option('org_address', '123 MYCO Way, Columbus, OH 43210');
$email       = myco_get_option('org_email', 'info@myco.org');
$phone       = myco_get_option('org_phone', '(614) 555-0123');
$copyright   = myco_get_option('copyright_text', '2026 MYCO. All rights reserved.');
$social      = myco_get_social_links();
?>

<footer class="w-full bg-white" aria-label="<?php esc_attr_e('Site footer', 'myco'); ?>">
    <div class="max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-8 md:pt-16 md:pb-10">

        <!-- 4-column grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10 md:gap-12 lg:gap-16">

            <!-- Col 1: Logo + Description + Social -->
            <div class="md:col-span-1">
                <div style="max-width:360px;">
                    <a href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('MYCO Home', 'myco'); ?>">
                        <img src="<?php echo esc_url(MYCO_URI . '/assets/images/myco-logo.png'); ?>"
                             alt="<?php bloginfo('name'); ?>"
                             style="height:150px; width:auto; display:block; margin-bottom:1px;" />
                    </a>
                    <p style="color:#6B7280; font-size:0.93rem; line-height:1.68; margin-bottom:22px;">
                        <?php echo esc_html($description); ?>
                    </p>
                    <div style="display:flex; gap:18px; align-items:center;">
                        <a href="<?php echo esc_url($social['facebook']); ?>" class="footer-social-icon" aria-label="<?php esc_attr_e('MYCO on Facebook', 'myco'); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>
                            </svg>
                        </a>
                        <a href="<?php echo esc_url($social['twitter']); ?>" class="footer-social-icon" aria-label="<?php esc_attr_e('MYCO on Twitter', 'myco'); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/>
                            </svg>
                        </a>
                        <a href="<?php echo esc_url($social['instagram']); ?>" class="footer-social-icon" aria-label="<?php esc_attr_e('MYCO on Instagram', 'myco'); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="2" y="2" width="20" height="20" rx="5" ry="5"/>
                                <circle cx="12" cy="12" r="4"/>
                                <circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Col 2: Navigation -->
            <div class="md:col-span-1">
                <p class="footer-nav-heading"><?php esc_html_e('Navigation', 'myco'); ?></p>
                <nav aria-label="<?php esc_attr_e('Footer navigation', 'myco'); ?>">
                    <?php
                    if (has_nav_menu('footer_nav')) {
                        wp_nav_menu([
                            'theme_location' => 'footer_nav',
                            'walker'         => new Walker_Footer_Nav(),
                            'container'      => false,
                            'items_wrap'     => '%3$s',
                        ]);
                    } else {
                        // Fallback footer nav links
                        $footer_links = [
                            'Home'        => home_url('/'),
                            'About Us'    => home_url('/about/'),
                            'Events'      => home_url('/events/'),
                            'Programs'    => home_url('/programs/'),
                            'Get Involved' => home_url('/volunteer/'),
                        ];
                        foreach ($footer_links as $label => $url) {
                            echo '<a href="' . esc_url($url) . '" class="footer-nav-link">' . esc_html($label) . '</a>';
                        }
                    }
                    ?>
                </nav>
            </div>

            <!-- Col 3: Resources -->
            <div class="md:col-span-1">
                <p class="footer-nav-heading"><?php esc_html_e('Resources', 'myco'); ?></p>
                <nav aria-label="<?php esc_attr_e('Footer resources', 'myco'); ?>">
                    <?php
                    if (has_nav_menu('footer_resources')) {
                        wp_nav_menu([
                            'theme_location' => 'footer_resources',
                            'walker'         => new Walker_Footer_Nav(),
                            'container'      => false,
                            'items_wrap'     => '%3$s',
                        ]);
                    } else {
                        $resource_links = [
                            'Gallery'      => home_url('/gallery/'),
                            'News'         => home_url('/news/'),
                            'Donate'       => home_url('/donate/'),
                            'Privacy Policy' => home_url('/privacy-policy/'),
                            'Contact Us'   => home_url('/contact/'),
                        ];
                        foreach ($resource_links as $label => $url) {
                            echo '<a href="' . esc_url($url) . '" class="footer-nav-link">' . esc_html($label) . '</a>';
                        }
                    }
                    ?>
                </nav>
            </div>

            <!-- Col 4: Contact -->
            <div class="md:col-span-1">
                <p class="footer-nav-heading"><?php esc_html_e('Contact', 'myco'); ?></p>
                <div class="footer-contact-row">
                    <span class="footer-contact-label"><?php esc_html_e('Address:', 'myco'); ?> </span>
                    <span class="footer-contact-value"><?php echo esc_html($address); ?></span>
                </div>
                <div class="footer-contact-row">
                    <span class="footer-contact-label"><?php esc_html_e('Email:', 'myco'); ?> </span>
                    <a href="mailto:<?php echo esc_attr($email); ?>" class="footer-contact-link footer-contact-value"><?php echo esc_html($email); ?></a>
                </div>
                <div class="footer-contact-row">
                    <span class="footer-contact-label"><?php esc_html_e('Phone:', 'myco'); ?> </span>
                    <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>" class="footer-contact-link footer-contact-value"><?php echo esc_html($phone); ?></a>
                </div>
            </div>

        </div>

        <!-- Divider -->
        <div class="border-t border-gray-200 mt-12 pt-6">
            <p style="color:#9CA3AF; font-size:0.85rem;">&copy; <?php echo esc_html($copyright); ?></p>
        </div>

    </div>
</footer>
