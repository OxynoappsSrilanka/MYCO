<?php
/**
 * Dark Footer (All pages except Home & About)
 *
 * @package MYCO
 */

$description = myco_get_option('footer_description', 'Empowering Muslim Youth of Central Ohio through education, leadership, and community service.');
$address     = myco_get_option('org_address', '123 MYCO Way, Columbus, OH 43210');
$email       = myco_get_option('org_email', 'info@myco.org');
$phone       = myco_get_option('org_phone', '(614) 555-0123');
$copyright   = myco_get_option('copyright_text', '2026 MYCO. All rights reserved.');
$social      = myco_get_social_links();
$newsletter_heading = myco_get_option('footer_newsletter_heading', 'Stay Connected');
?>

<footer class="dark-footer w-full" aria-label="<?php esc_attr_e('Site footer', 'myco'); ?>">

    <!-- Circle Photo Strip -->
    <?php
    $circle_images = myco_get_option('footer_circle_images');
    if ($circle_images && is_array($circle_images)) : ?>
    <div class="circle-strip">
        <?php foreach ($circle_images as $img) : ?>
            <img src="<?php echo esc_url($img['sizes']['thumbnail'] ?? $img['url']); ?>"
                 alt="<?php echo esc_attr($img['alt'] ?? 'MYCO Community'); ?>"
                 loading="lazy" />
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Contact Bar -->
    <div style="border-top: 1px solid rgba(255,255,255,0.08); border-bottom: 1px solid rgba(255,255,255,0.08);">
        <div class="inner mx-auto px-4 py-4 flex flex-wrap items-center justify-center gap-8 text-sm" style="color: rgba(255,255,255,0.6);">
            <a href="mailto:<?php echo esc_attr($email); ?>" class="flex items-center gap-2 hover:text-white transition-colors">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="4" width="20" height="16" rx="3"/>
                    <path d="M22 4L12 13 2 4"/>
                </svg>
                <?php echo esc_html($email); ?>
            </a>
            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>" class="flex items-center gap-2 hover:text-white transition-colors">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
                </svg>
                <?php echo esc_html($phone); ?>
            </a>
            <span class="flex items-center gap-2">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/>
                    <circle cx="12" cy="10" r="3"/>
                </svg>
                <?php echo esc_html($address); ?>
            </span>
        </div>
    </div>

    <!-- Main Footer Content -->
    <div class="inner mx-auto px-4 pt-14 pb-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10 md:gap-12 lg:gap-16">

            <!-- Col 1: Logo + Description -->
            <div class="md:col-span-1">
                <a href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('MYCO Home', 'myco'); ?>">
                    <img src="<?php echo esc_url(MYCO_URI . '/assets/images/myco-logo-white.png'); ?>"
                         alt="<?php bloginfo('name'); ?>"
                         style="height:60px; width:auto; display:block; margin-bottom:16px;" />
                </a>
                <p style="color: rgba(255,255,255,0.6); font-size:0.93rem; line-height:1.68; margin-bottom:24px;">
                    <?php echo esc_html($description); ?>
                </p>
                <div style="display:flex; gap:12px;">
                    <a href="<?php echo esc_url($social['facebook']); ?>" class="dark-footer-social" aria-label="<?php esc_attr_e('Facebook', 'myco'); ?>">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                    </a>
                    <a href="<?php echo esc_url($social['twitter']); ?>" class="dark-footer-social" aria-label="<?php esc_attr_e('Twitter', 'myco'); ?>">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/></svg>
                    </a>
                    <a href="<?php echo esc_url($social['instagram']); ?>" class="dark-footer-social" aria-label="<?php esc_attr_e('Instagram', 'myco'); ?>">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor" stroke="none"/></svg>
                    </a>
                </div>
            </div>

            <!-- Col 2: Quick Links -->
            <div class="md:col-span-1">
                <p class="dark-footer-heading"><?php esc_html_e('Quick Links', 'myco'); ?></p>
                <nav aria-label="<?php esc_attr_e('Footer quick links', 'myco'); ?>">
                    <?php
                    if (has_nav_menu('footer_quick')) {
                        wp_nav_menu([
                            'theme_location' => 'footer_quick',
                            'container'      => false,
                            'items_wrap'     => '%3$s',
                            'walker'         => new Walker_Footer_Nav(),
                        ]);
                    } else {
                        // Fallback
                        echo '<a href="' . esc_url(home_url('/')) . '" class="dark-footer-link">Home</a>';
                        echo '<a href="' . esc_url(home_url('/about/')) . '" class="dark-footer-link">About Us</a>';
                        echo '<a href="' . esc_url(home_url('/programs/')) . '" class="dark-footer-link">Programs</a>';
                        echo '<a href="' . esc_url(home_url('/events/')) . '" class="dark-footer-link">Events</a>';
                        echo '<a href="' . esc_url(home_url('/news/')) . '" class="dark-footer-link">News</a>';
                    }
                    ?>
                </nav>
            </div>

            <!-- Col 3: Get Involved -->
            <div class="md:col-span-1">
                <p class="dark-footer-heading"><?php esc_html_e('Get Involved', 'myco'); ?></p>
                <nav aria-label="<?php esc_attr_e('Footer get involved', 'myco'); ?>">
                    <?php
                    if (has_nav_menu('footer_involved')) {
                        wp_nav_menu([
                            'theme_location' => 'footer_involved',
                            'container'      => false,
                            'items_wrap'     => '%3$s',
                            'walker'         => new Walker_Footer_Nav(),
                        ]);
                    } else {
                        echo '<a href="' . esc_url(home_url('/volunteer/')) . '" class="dark-footer-link">Volunteer</a>';
                        echo '<a href="' . esc_url(home_url('/donate/')) . '" class="dark-footer-link">Donate</a>';
                        echo '<a href="' . esc_url(home_url('/contact/')) . '" class="dark-footer-link">Contact Us</a>';
                        echo '<a href="' . esc_url(home_url('/gallery/')) . '" class="dark-footer-link">Gallery</a>';
                    }
                    ?>
                </nav>
            </div>

            <!-- Col 4: Connect / Newsletter -->
            <div class="md:col-span-1">
                <p class="dark-footer-heading"><?php echo esc_html($newsletter_heading); ?></p>
                <p style="color: rgba(255,255,255,0.55); font-size:0.9rem; line-height:1.6; margin-bottom:16px;">
                    <?php esc_html_e('Subscribe to our newsletter for updates on events, programs, and community news.', 'myco'); ?>
                </p>
                <form class="newsletter-form flex gap-2" action="#" method="post">
                    <input type="email" placeholder="<?php esc_attr_e('Your email', 'myco'); ?>" required
                           class="flex-1 px-4 py-2.5 rounded-xl text-sm bg-white/10 border border-white/15 text-white placeholder-white/40 focus:outline-none focus:border-white/30" />
                    <button type="submit" class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white"
                            style="background: #C8402E; transition: background 0.18s;">
                        <?php esc_html_e('Join', 'myco'); ?>
                    </button>
                </form>
                <p class="newsletter-message" style="font-size:0.82rem; margin-top:8px; min-height:1.2em;"></p>
            </div>

        </div>

        <!-- Bottom bar -->
        <div style="border-top: 1px solid rgba(255,255,255,0.08); margin-top:48px; padding-top:24px;">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <p style="color: rgba(255,255,255,0.4); font-size:0.85rem;">&copy; <?php echo esc_html($copyright); ?></p>
                <div class="flex gap-6" style="color: rgba(255,255,255,0.4); font-size:0.82rem;">
                    <a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>" class="hover:text-white transition-colors"><?php esc_html_e('Privacy Policy', 'myco'); ?></a>
                    <a href="#" class="hover:text-white transition-colors"><?php esc_html_e('Terms of Use', 'myco'); ?></a>
                </div>
            </div>
        </div>

    </div>
</footer>
