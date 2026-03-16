<?php
/**
 * Header Template
 *
 * @package MYCO
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    
    <!-- Favicon - Logo in Browser Tab -->
    <link rel="icon" type="image/webp" href="<?php echo esc_url(MYCO_URI . '/assets/images/favicon.webp'); ?>" />
    <link rel="apple-touch-icon" href="<?php echo esc_url(MYCO_URI . '/assets/images/favicon.webp'); ?>" />
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="w-full bg-white">
    <div class="bismillah-top-bar" role="presentation" aria-hidden="true">
        <img src="<?php echo esc_url(MYCO_URI . '/assets/images/Bismillah.webp'); ?>"
             alt=""
             class="bismillah-top-image" />
    </div>

    <div class="site-header-inner w-full mx-auto px-8 sm:px-12 lg:px-16 flex items-center justify-between">
        <!-- Logo -->
        <a href="<?php echo esc_url(home_url('/')); ?>" class="flex-shrink-0 transition-opacity hover:opacity-80" aria-label="<?php esc_attr_e('MYCO Home', 'myco'); ?>">
            <img src="<?php echo esc_url(MYCO_URI . '/assets/images/myco-logo.png'); ?>"
                 alt="<?php bloginfo('name'); ?>"
                 class="h-20 sm:h-24 md:h-28 w-auto" />
        </a>

        <!-- Desktop pill nav -->
        <nav class="hidden md:flex items-center" aria-label="<?php esc_attr_e('Primary navigation', 'myco'); ?>">
            <div class="pill-nav flex items-center">
                <?php
                if (has_nav_menu('primary')) {
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'walker'         => new Walker_Pill_Nav(),
                        'container'      => false,
                        'items_wrap'     => '%3$s',
                    ]);
                } else {
                    myco_fallback_menu();
                }
                ?>

            </div>
        </nav>

        <!-- Hamburger (mobile only) -->
        <button id="hamburger"
            class="md:hidden flex flex-col gap-[5px] items-center justify-center w-10 h-10 rounded-full hover:bg-gray-100 transition-colors focus:outline-none focus:ring-2 focus:ring-navy-dark"
            aria-expanded="false"
            aria-controls="mobile-menu"
            aria-label="<?php esc_attr_e('Open navigation menu', 'myco'); ?>">
            <span class="ham-line"></span>
            <span class="ham-line"></span>
            <span class="ham-line"></span>
        </button>
    </div>

    <!-- Mobile dropdown menu -->
    <div id="mobile-menu" class="hidden md:hidden px-8 pb-4" role="navigation" aria-label="<?php esc_attr_e('Mobile navigation', 'myco'); ?>">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
            <nav class="flex flex-col p-2 gap-1">
                <?php
                if (has_nav_menu('primary')) {
                    wp_nav_menu([
                        'theme_location' => 'primary',
                        'walker'         => new Walker_Mobile_Nav(),
                        'container'      => false,
                        'items_wrap'     => '%3$s',
                    ]);
                } else {
                    myco_fallback_mobile_menu();
                }
                ?>
            </nav>
        </div>
    </div>
</header>

<!-- Sticky Donate FAB (Floating Action Button) - CRITICAL UX IMPROVEMENT -->
<?php if (!is_page_template('page-templates/template-donate.php')) : ?>
<button class="sticky-donate-fab" 
        onclick="window.location.href='<?php echo esc_url(home_url('/donate/')); ?>'"
        aria-label="<?php esc_attr_e('Donate to MYCO', 'myco'); ?>">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true">
        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78Z"/>
    </svg>
    <span><?php esc_html_e('Donate', 'myco'); ?></span>
</button>
<?php endif; ?>
