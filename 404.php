<?php
/**
 * 404 Template
 *
 * @package MYCO
 */

get_header();
?>

<main class="w-full" style="background: #F5F6FA; min-height: 70vh;">

    <!-- Hero 404 -->
    <section class="w-full relative overflow-hidden" style="background: linear-gradient(135deg, #141943 0%, #1e2a5a 50%, #2a3e6a 100%); padding: 80px 0;">
        <div aria-hidden="true" class="absolute inset-0 pointer-events-none" style="opacity: 0.06; background-image: url(&quot;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='1920' height='300' fill='none'%3E%3Cpath d='M-60 80 C400 -20 800 180 1300 60 S1700 -40 1980 80' stroke='white' stroke-width='1.2'/%3E%3C/svg%3E&quot;); background-size: 1920px 300px; background-repeat: no-repeat;"></div>
        <div class="inner mx-auto px-4 text-center relative z-10">
            <div class="font-inter font-black text-white mb-4" style="font-size: clamp(5rem, 15vw, 10rem); line-height: 1; opacity: 0.15;">404</div>
            <h1 class="font-inter font-black text-white mb-4" style="font-size: clamp(2rem, 5vw, 3rem); margin-top: -2rem; position: relative; z-index: 1;">
                <?php esc_html_e('Page Not Found', 'myco'); ?>
            </h1>
            <p style="color: rgba(255,255,255,0.65); font-size: 1.1rem; max-width: 520px; margin: 0 auto 36px; line-height: 1.7;">
                <?php esc_html_e("We couldn't find the page you're looking for. It may have been moved, deleted, or the link may be incorrect.", 'myco'); ?>
            </p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="pill-primary">
                <?php esc_html_e('Return Home', 'myco'); ?>
            </a>
        </div>
    </section>

    <!-- Helpful Links -->
    <section class="w-full bg-white py-16">
        <div class="inner mx-auto px-4">
            <p class="text-center font-bold mb-10" style="color: #C8402E; font-size: 0.85rem; letter-spacing: 0.08em; text-transform: uppercase;">
                <?php esc_html_e('What were you looking for?', 'myco'); ?>
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-4xl mx-auto">

                <a href="<?php echo esc_url(home_url('/programs/')); ?>" class="group flex items-center gap-4 p-5 rounded-2xl bg-gray-50 hover:bg-red-50 transition-colors" style="text-decoration: none;">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(200,64,46,0.1);">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#C8402E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                    </div>
                    <div>
                        <div class="font-bold text-sm" style="color: #141943;"><?php esc_html_e('Programs', 'myco'); ?></div>
                        <div class="text-xs text-gray-500"><?php esc_html_e('Youth programs & activities', 'myco'); ?></div>
                    </div>
                </a>

                <a href="<?php echo esc_url(home_url('/events/')); ?>" class="group flex items-center gap-4 p-5 rounded-2xl bg-gray-50 hover:bg-red-50 transition-colors" style="text-decoration: none;">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(200,64,46,0.1);">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#C8402E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                    <div>
                        <div class="font-bold text-sm" style="color: #141943;"><?php esc_html_e('Events', 'myco'); ?></div>
                        <div class="text-xs text-gray-500"><?php esc_html_e('Upcoming community events', 'myco'); ?></div>
                    </div>
                </a>

                <a href="<?php echo esc_url(home_url('/news/')); ?>" class="group flex items-center gap-4 p-5 rounded-2xl bg-gray-50 hover:bg-red-50 transition-colors" style="text-decoration: none;">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(200,64,46,0.1);">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#C8402E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H8a2 2 0 0 0-2 2v16a2 2 0 0 1-2 2Zm0 0a2 2 0 0 1-2-2v-9c0-1.1.9-2 2-2h2"/><path d="M18 14h-8M15 18h-5M10 6h8v4h-8z"/></svg>
                    </div>
                    <div>
                        <div class="font-bold text-sm" style="color: #141943;"><?php esc_html_e('News', 'myco'); ?></div>
                        <div class="text-xs text-gray-500"><?php esc_html_e('Latest updates & stories', 'myco'); ?></div>
                    </div>
                </a>

                <a href="<?php echo esc_url(home_url('/about/')); ?>" class="group flex items-center gap-4 p-5 rounded-2xl bg-gray-50 hover:bg-red-50 transition-colors" style="text-decoration: none;">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(200,64,46,0.1);">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#C8402E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    </div>
                    <div>
                        <div class="font-bold text-sm" style="color: #141943;"><?php esc_html_e('About MYCO', 'myco'); ?></div>
                        <div class="text-xs text-gray-500"><?php esc_html_e('Our mission & story', 'myco'); ?></div>
                    </div>
                </a>

                <a href="<?php echo esc_url(home_url('/volunteer/')); ?>" class="group flex items-center gap-4 p-5 rounded-2xl bg-gray-50 hover:bg-red-50 transition-colors" style="text-decoration: none;">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(200,64,46,0.1);">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#C8402E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <div>
                        <div class="font-bold text-sm" style="color: #141943;"><?php esc_html_e('Volunteer', 'myco'); ?></div>
                        <div class="text-xs text-gray-500"><?php esc_html_e('Get involved with MYCO', 'myco'); ?></div>
                    </div>
                </a>

                <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="group flex items-center gap-4 p-5 rounded-2xl bg-gray-50 hover:bg-red-50 transition-colors" style="text-decoration: none;">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background: rgba(200,64,46,0.1);">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#C8402E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    </div>
                    <div>
                        <div class="font-bold text-sm" style="color: #141943;"><?php esc_html_e('Contact', 'myco'); ?></div>
                        <div class="text-xs text-gray-500"><?php esc_html_e('Reach out for help', 'myco'); ?></div>
                    </div>
                </a>

            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>
