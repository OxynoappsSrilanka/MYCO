<?php
/**
 * Homepage Hero Section with Integrated Donate Form
 * 
 * REDESIGNED: Donate form appears in first view alongside hero content
 *
 * @package MYCO
 */

$headline       = myco_get_field('hero_headline', false, '');
$paragraph      = myco_get_field('hero_paragraph', false, 'Muslim Youth of Central Ohio (MYCO) is building a state of the art facility dedicated to supporting the next generation of Muslims through spiritual growth, leadership development, education, and athletics, providing a safe space for Muslim youth to thrive in Ohio for generations more to come.');
$hero_image     = myco_get_field('hero_image');
$hero_img_url   = $hero_image ? (is_array($hero_image) ? $hero_image['url'] : wp_get_attachment_url($hero_image)) : MYCO_URI . '/assets/images/hero-image.png';
?>

<section class="w-full relative overflow-hidden" style="background: linear-gradient(135deg, #0f1535 0%, #1a2555 50%, #2a3e6a 100%); min-height: 100vh; display: flex; align-items: center; padding: 80px 0;">
    
    <!-- Background Image with Overlay -->
    <div class="absolute inset-0 z-0">
        <img src="<?php echo esc_url($hero_img_url); ?>"
             alt="<?php esc_attr_e('Muslim youth gathered together', 'myco'); ?>"
             class="w-full h-full object-cover opacity-40"
             loading="eager" />
        <div class="absolute inset-0 bg-gradient-to-r from-[#0f1535]/95 via-[#1a2555]/85 to-[#1a2555]/75"></div>
    </div>

    <div class="max-w-[1380px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full">
        
        <!-- Two Column Layout: Content Left, Donate Form Right -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-start">
            
            <!-- LEFT COLUMN: Hero Content -->
            <div class="flex flex-col justify-center py-8">
                
                <!-- Headline -->
                <h1 class="font-inter font-black leading-tight tracking-tight text-white mb-6"
                    style="font-size: clamp(2.5rem, 5vw, 4rem);">
                    <?php if ($headline) : ?>
                        <?php echo nl2br(esc_html($headline)); ?>
                    <?php else : ?>
                        WELCOME TO THE <span style="color: #C8402E;">FUTURE</span><br />
                        FOR MUSLIM YOUTH IN AMERICA
                    <?php endif; ?>
                </h1>

                <!-- Description -->
                <p class="text-gray-300 text-lg leading-relaxed mb-8 max-w-xl">
                    <?php echo esc_html($paragraph); ?>
                </p>

                <!-- CTA Buttons (Optional - can be removed if form is primary CTA) -->
                <div class="flex flex-wrap items-center gap-4">
                    <a href="#programs" class="btn-secondary-white">
                        Explore Programs
                    </a>
                    <a href="<?php echo esc_url(home_url('/about/')); ?>" class="text-white hover:text-gray-300 transition-colors font-semibold flex items-center gap-2">
                        Learn More
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M7 3l7 7-7 7"/>
                        </svg>
                    </a>
                </div>

            </div>

            <!-- RIGHT COLUMN: Donate Form Widget -->
            <div class="flex items-center justify-center lg:justify-end">
                <div class="w-full max-w-md bg-white/95 backdrop-blur-sm rounded-2xl shadow-2xl p-8" style="border: 1px solid rgba(255,255,255,0.2);">
                    
                    <!-- Form Header -->
                    <div class="text-center mb-6">
                        <p class="text-sm font-bold text-red-600 uppercase tracking-wide mb-2">
                            Contribute to
                        </p>
                        <h2 class="text-2xl font-black text-navy-800 mb-2">
                            Support MYCO
                        </h2>
                    </div>

                    <!-- Donation Form -->
                    <form id="hero-donate-form" class="space-y-5">
                        
                        <!-- Fund Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Contribute to
                            </label>
                            <select class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-600 focus:outline-none transition-colors text-gray-700 font-medium">
                                <option>MYCO Support Fund</option>
                                <option>Youth Mentorship Program</option>
                                <option>Athletics & Sports Programs</option>
                                <option>Academic Support</option>
                                <option>Leadership Development</option>
                                <option>Facility Expansion</option>
                            </select>
                        </div>

                        <!-- Amount Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                Select Amount
                            </label>
                            <div class="grid grid-cols-4 gap-2 mb-3">
                                <button type="button" class="donate-amount-btn" data-amount="10">$10.00</button>
                                <button type="button" class="donate-amount-btn" data-amount="20">$20.00</button>
                                <button type="button" class="donate-amount-btn" data-amount="30">$30.00</button>
                                <button type="button" class="donate-amount-btn active" data-amount="40">$40.00</button>
                            </div>
                            <button type="button" class="donate-amount-btn w-full" data-amount="custom">
                                CUSTOM AMOUNT
                            </button>
                        </div>

                        <!-- Donation Type -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Donation Type
                            </label>
                            <select class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-600 focus:outline-none transition-colors text-gray-700 font-medium">
                                <option>Monthly Donation</option>
                                <option>One-Time Donation</option>
                            </select>
                        </div>

                        <!-- Cover Fees Checkbox -->
                        <div class="bg-red-50 border border-red-100 rounded-xl p-4">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" checked class="mt-1 w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500" />
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-sm font-semibold text-gray-800">
                                            SUPPORT US BY COVERING THE FEES WE HAVE TO PAY
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-600">3% Cover the Fee</span>
                                        <span class="font-bold text-gray-800">$0.30</span>
                                    </div>
                                    <div class="flex items-center justify-between text-sm font-bold mt-1">
                                        <span class="text-gray-800">Total per month</span>
                                        <span class="text-gray-800">$10.30</span>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 px-6 rounded-xl transition-all transform hover:scale-[1.02] shadow-lg hover:shadow-xl">
                            SUPPORT MYCO!
                        </button>

                        <!-- Trust Badges -->
                        <div class="flex items-center justify-center gap-4 pt-2 text-xs text-gray-500">
                            <div class="flex items-center gap-1">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M8 2L2 5v4c0 3.5 2.5 6.5 6 7 3.5-.5 6-3.5 6-7V5l-6-3z"/>
                                </svg>
                                <span>Secure</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M8 2L2 5v4c0 3.5 2.5 6.5 6 7 3.5-.5 6-3.5 6-7V5l-6-3z"/>
                                    <path d="M5 8l2 2 4-4"/>
                                </svg>
                                <span>Tax Deductible</span>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>

    </div>
</section>

<style>
/* Donate Amount Buttons */
.donate-amount-btn {
    padding: 12px 8px;
    border: 2px solid #E5E7EB;
    border-radius: 10px;
    background: white;
    color: #374151;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 200ms;
    text-align: center;
}

.donate-amount-btn:hover {
    border-color: #C8402E;
    color: #C8402E;
    background: rgba(200, 64, 46, 0.05);
}

.donate-amount-btn.active {
    border-color: #C8402E;
    background: #C8402E;
    color: white;
}

/* Responsive adjustments */
@media (max-width: 1024px) {
    section {
        min-height: auto !important;
        padding: 60px 0 !important;
    }
}
</style>

<script>
// Amount button selection
document.querySelectorAll('.donate-amount-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.donate-amount-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
    });
});

// Form submission
document.getElementById('hero-donate-form').addEventListener('submit', function(e) {
    e.preventDefault();
    // Redirect to full donate page or process donation
    window.location.href = '<?php echo esc_url(home_url('/donate/')); ?>';
});
</script>
