<?php
/**
 * Single Program Template
 *
 * @package MYCO
 */

get_header();

$current_title = get_the_title();
$current_slug = sanitize_title($current_title);

// Also check post_name for accurate slug matching (used by static demo redirect)
global $post;
if ($post && !empty($post->post_name) && $post->post_name !== $current_slug) {
    $current_slug = $post->post_name;
}

// --- STATIC DATA BRIDGE ---
$static_program_data = [
    'basketball-fitness-nights' => [
        'category' => 'Athletics',
        'image' => MYCO_URI . '/assets/images/Galleries/myco-basketball-champions-team-with-trophy.jpg.jpg',
        'overview' => 'Our Basketball & Fitness Nights program provides a structured, supportive environment where Muslim youth develop athletic skills, build lasting friendships, and learn valuable life lessons through sports. Every week, participants engage in skill-building drills, team scrimmages, and fitness activities led by experienced coaches who emphasize sportsmanship and teamwork.',
        'content' => 'Beyond physical fitness, this program creates a positive space where youth develop confidence, leadership skills, and a sense of community while staying active and healthy. We focus on holisitic development, ensuring that every session contributes to both physical and mental well-being. Participants are encouraged to push their limits while respecting their peers and mentors.',
        'features' => [
            ['title' => 'Basketball Fundamentals', 'description' => 'Dribbling, shooting, passing, and defensive techniques taught by experienced coaches.'],
            ['title' => 'Teamwork & Communication', 'description' => 'Strategies for collaborative play and effective communication on and off the court.'],
            ['title' => 'Physical Conditioning', 'description' => 'Engaging in strength training and endurance building to develop healthy habits.'],
            ['title' => 'Sportsmanship & Character', 'description' => 'Practicing respect and integrity aligned with community and Islamic values.']
        ],
        'schedule' => 'Every Friday, 6:00 PM - 8:30 PM',
        'location' => 'MYCO Community Gym, 123 Main St, Columbus, OH',
        'age_group' => '12-18 years old',
        'fee' => 'Free for members',
        'duration' => '2.5 hours per session',
        'capacity' => '30 participants'
    ],
    'youth-leadership-mentorship' => [
        'category' => 'Mentorship',
        'image' => MYCO_URI . '/assets/images/Galleries/myco-youth-team-award-check-winners.jpg',
        'overview' => 'The Youth Leadership & Mentorship program is designed to empower the next generation of community leaders. Through one-on-one sessions and group workshops, youth are paired with experienced professionals and community elders who provide guidance, inspiration, and practical advice for navigating personal and professional challenges.',
        'content' => 'Our curriculum covers public speaking, project management, emotional intelligence, and community organizing. We believe that every young person has the potential to lead, and we provide the tools and support system necessary to turn that potential into action. The program concludes with a community project where participants apply their skills to solve local issues.',
        'features' => [
            ['title' => 'One-on-One Mentoring', 'description' => 'Personalized guidance from experienced mentors in various professional fields.'],
            ['title' => 'Leadership Workshops', 'description' => 'Hands-on training in public speaking, strategic planning, and team management.'],
            ['title' => 'Community Service Projects', 'description' => 'Practical experience leading initiatives that benefit the local community.'],
            ['title' => 'Networking Opportunities', 'description' => 'Building connections with community leaders and professionals.']
        ],
        'schedule' => 'Bi-weekly Saturdays, 10:00 AM - 1:00 PM',
        'location' => 'MYCO Conference Hall',
        'age_group' => '15-22 years old',
        'fee' => '$50 per semester',
        'duration' => '3 hours per session',
        'capacity' => '20 participants'
    ],
    'spiritual-identity-program' => [
        'category' => 'Academic',
        'image' => MYCO_URI . '/assets/images/Galleries/myco-youth-basketball-event-congregational-prayer.jpg',
        'overview' => 'This program explores the intersection of modern life and traditional values, helping youth build a strong, confident spiritual identity. We provide a safe space for open discussion about faith, ethics, and contemporary issues, guided by scholars and educators who understand the unique challenges faced by Muslim youth today.',
        'content' => 'Sessions include guided reflections, textual study, and interactive Q&A. We aim to foster a deep, intellectual, and emotional connection to faith that serves as a compass for navigating life. The program encourages critical thinking and provides a foundation for living a life of purpose, integrity, and social responsibility.',
        'features' => [
            ['title' => 'Theological Foundations', 'description' => 'In-depth study of core beliefs and their practical applications in daily life.'],
            ['title' => 'Contemporary Ethics', 'description' => 'Navigating modern social and moral dilemmas through a value-based lens.'],
            ['title' => 'Socratic Seminars', 'description' => 'Interactive discussions that encourage critical thinking and personal reflection.'],
            ['title' => 'Spiritual Retreats', 'description' => 'Periodic weekend retreats focused on mindfulness and communal bonding.']
        ],
        'schedule' => 'Every Wednesday evening, 7:00 PM - 9:00 PM',
        'location' => 'MYCO Prayer Center & Library',
        'age_group' => '14-20 years old',
        'fee' => 'Free',
        'duration' => '2 hours per session',
        'capacity' => '25 participants'
    ],
    'community-development-service' => [
        'category' => 'Community',
        'image' => MYCO_URI . '/assets/images/Galleries/myco-youth-community-center-groundbreaking-ceremony.jpg',
        'overview' => 'Our Community Development & Service program mobilizes youth to take active roles in improving their neighborhoods. From organizing food drives to participating in urban gardening, participants learn that small actions can lead to major community transformations. We emphasize the value of service as a pillar of personal growth.',
        'content' => 'Participants work directly with local NGOs and community groups to identify needs and execute solutions. This program builds practical skills in logistics, coordination, and empathy. It\'s not just about doing good; it\'s about understanding the systemic issues our community faces and being part of the long-term solution.',
        'features' => [
            ['title' => 'Service Learning', 'description' => 'Structured volunteer opportunities combined with reflection and education.'],
            ['title' => 'Social Advocacy', 'description' => 'Learning how to raise awareness for important community and social issues.'],
            ['title' => 'Environmental Stewardship', 'description' => 'Participating in local green initiatives and sustainability projects.'],
            ['title' => 'Crisis Response Training', 'description' => 'Basic training in helping the community during emergencies or hardships.']
        ],
        'schedule' => 'Monthly Sunday Service Days, 9:00 AM - 4:00 PM',
        'location' => 'Various Community Sites',
        'age_group' => 'All ages welcome (with supervision for under 12s)',
        'fee' => 'Free',
        'duration' => 'Full day event',
        'capacity' => 'Unlimited'
    ]
];

// Determine if we show static data
$static_data = null;
foreach ($static_program_data as $slug => $data) {
    if (strpos($current_slug, $slug) !== false) {
        $static_data = $data;
        break;
    }
}

// Fallback values if no static data or database fields
$cat_name = ($static_data) ? $static_data['category'] : '';
if (!$cat_name) {
    $categories = get_the_terms(get_the_ID(), 'program_category');
    $cat_name = $categories && !is_wp_error($categories) ? $categories[0]->name : '';
}

$img_url = ($static_data) ? $static_data['image'] : get_the_post_thumbnail_url(get_the_ID(), 'large');
$schedule = ($static_data) ? $static_data['schedule'] : myco_get_field('program_schedule');
$age_group = ($static_data) ? $static_data['age_group'] : myco_get_field('program_age_group');
$location = ($static_data) ? $static_data['location'] : myco_get_field('program_location');
$fee = ($static_data) ? $static_data['fee'] : myco_get_field('program_fee', false, 'Free');
$features = ($static_data) ? $static_data['features'] : myco_get_field('program_features');
$overview = ($static_data) ? $static_data['overview'] : '';
$detail_content = ($static_data) ? $static_data['content'] : get_the_content();
$skill_level = ($static_data) ? ($static_data['skill_level'] ?? 'All levels welcome') : myco_get_field('program_skill_level', false, 'All levels welcome');
$duration = ($static_data) ? $static_data['duration'] : myco_get_field('program_duration', false, 'Various');
$capacity = ($static_data) ? $static_data['capacity'] : myco_get_field('program_capacity', false, 'Limited');
$is_completed = myco_get_field('program_is_completed', false, false);
?>

<style>
    .program-details-sidebar {
        background: #F0F4FF;
        border-radius: 24px;
        padding: 28px 24px 52px;
        border: 1px solid #C7D2F0;
        margin-bottom: 32px;
        box-shadow: 0 16px 40px rgba(20,25,67,0.07);
    }

    .program-details-heading {
        font-size: 22px;
        font-weight: 900;
        color: #141943;
        margin-bottom: 24px;
    }

    .program-details-meta-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .program-details-meta-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 12px 14px;
        border-left: 4px solid #141943;
    }

    .program-details-meta-label {
        font-size: 10px;
        font-weight: 800;
        color: #94A3B8;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 4px;
    }

    .program-details-meta-value {
        font-size: 14px;
        font-weight: 800;
        color: #141943;
        line-height: 1.35;
    }

    .program-details-divider {
        margin: 28px 0;
        border: none;
        border-top: 1px solid #EDF2F7;
    }

    .program-details-cta {
        height: auto;
        font-size: 15px;
        padding: 16px 22px;
    }

    .program-details-contact {
        text-align: center;
        font-size: 13px;
        color: #94A3B8;
        margin-top: 16px;
        line-height: 1.4;
    }

    .program-details-contact a {
        color: #141943;
        font-weight: 700;
    }

    .program-details-coordinator {
        background: #ffffff;
        border: 1px solid #C7D2F0;
        border-left: 4px solid #1e2d68;
        border-radius: 16px;
        padding: 18px;
        margin-top: 16px;
        margin-bottom: 10px;
    }

    .program-details-coordinator-title {
        font-size: 16px;
        font-weight: 900;
        color: #141943;
        margin-bottom: 12px;
    }

    .program-details-coordinator-name {
        font-size: 14px;
        font-weight: 800;
        color: #141943;
        margin-bottom: 3px;
    }

    .program-details-coordinator-role {
        font-size: 12px;
        color: #94A3B8;
        margin-bottom: 12px;
    }

    .program-details-coordinator-links {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .program-details-coordinator-link {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12.5px;
        text-decoration: none;
    }

    .program-details-coordinator-link.is-email {
        color: #374151;
    }

    .program-details-coordinator-link.is-phone {
        color: #C8402E;
        font-weight: 600;
    }

    @media (min-width: 1024px) {
        .program-details-sidebar {
            position: sticky;
            top: 40px;
        }
    }

    @media (min-width: 1024px) and (max-height: 920px) {
        .program-details-sidebar {
            top: 20px;
            padding: 20px 18px 40px;
        }

        .program-details-heading {
            font-size: 20px;
            margin-bottom: 18px;
        }

        .program-details-meta-list {
            gap: 8px;
        }

        .program-details-meta-card {
            padding: 10px 12px;
        }

        .program-details-meta-label {
            font-size: 9px;
            margin-bottom: 3px;
        }

        .program-details-meta-value {
            font-size: 13px;
        }

        .program-details-divider {
            margin: 20px 0;
        }

        .program-details-cta {
            font-size: 14px;
            padding: 14px 18px;
        }

        .program-details-contact {
            font-size: 12px;
            margin-top: 12px;
        }

        .program-details-coordinator {
            padding: 14px;
            margin-top: 14px;
            margin-bottom: 12px;
        }

        .program-details-coordinator-title {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .program-details-coordinator-name {
            font-size: 13px;
            margin-bottom: 2px;
        }

        .program-details-coordinator-role {
            font-size: 11px;
            margin-bottom: 10px;
        }

        .program-details-coordinator-links {
            gap: 6px;
        }

        .program-details-coordinator-link {
            gap: 6px;
            font-size: 11.5px;
        }

        .program-details-coordinator-link svg {
            width: 14px;
            height: 14px;
        }
    }
</style>

<!-- Hero Section -->
<section style="background: linear-gradient(130deg, #111640 0%, #182050 40%, #2a3e6a 100%); padding: 120px 0 80px; position: relative; overflow: hidden;">
    <div aria-hidden="true" style="position: absolute; inset: 0; pointer-events: none; z-index: 0; opacity: 0.09; background-image: url('data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'1920\' height=\'400\' fill=\'none\'%3E%3Cpath d=\'M-60 80 C400 -20 800 180 1300 60 S1700 -40 1980 80\' stroke=\'white\' stroke-width=\'1.2\'/%3E%3Cpath d=\'M-60 160 C400 60 800 260 1300 140 S1700 40 1980 160\' stroke=\'white\' stroke-width=\'1.2\'/%3E%3C/svg%3E'); background-size: 1920px 400px; background-repeat: no-repeat;"></div>
    <div class="inner" style="position: relative; z-index: 2;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 24px;">
            <a href="<?php echo esc_url(home_url('/')); ?>" style="font-size: 14px; font-weight: 500; color: rgba(255,255,255,0.68); text-decoration: none;">Home</a>
            <svg width="6" height="10" viewBox="0 0 6 10" fill="none"><path d="M1 1l4 4-4 4" stroke="rgba(255,255,255,0.5)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <a href="<?php echo esc_url(home_url('/programs/')); ?>" style="font-size: 14px; font-weight: 500; color: rgba(255,255,255,0.68); text-decoration: none;">Programs</a>
            <svg width="6" height="10" viewBox="0 0 6 10" fill="none"><path d="M1 1l4 4-4 4" stroke="rgba(255,255,255,0.5)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            <span style="font-size: 14px; font-weight: 600; color: #fff;"><?php the_title(); ?></span>
        </div>
        <?php if ($cat_name) : ?>
        <div style="margin-bottom: 20px;">
            <span style="display: inline-block; background: #C8402E; color: #fff; font-size: 12px; font-weight: 700; padding: 7px 16px; border-radius: 9999px; letter-spacing: 0.04em; text-transform: uppercase;"><?php echo esc_html($cat_name); ?></span>
        </div>
        <?php endif; ?>
        <h1 style="font-size: clamp(2.5rem, 5vw, 4rem); font-weight: 900; color: #ffffff; line-height: 1.1; letter-spacing: -0.02em; margin-bottom: 24px; max-width: 900px;"><?php the_title(); ?></h1>
        <div style="font-size: 20px; color: #B8C8DC; line-height: 1.6; max-width: 720px; font-weight: 400;"><?php echo get_the_excerpt(); ?></div>
    </div>
</section>

<!-- Content Section -->
<section style="background: #ffffff; padding: 90px 0 140px;">
    <div class="inner">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
            <div class="lg:col-span-2">
                <?php if ($img_url) : ?>
                <div style="border-radius: 24px; overflow: hidden; margin-bottom: 50px; box-shadow: 0 24px 60px rgba(20, 25, 67, 0.12);">
                    <img src="<?php echo esc_url($img_url); ?>" alt="<?php the_title(); ?>" style="width: 100%; height: auto; display: block;" />
                </div>
                <?php endif; ?>

                <div style="margin-bottom: 60px;">
                    <h2 style="font-size: 36px; font-weight: 900; color: #141943; margin-bottom: 24px;">Program Overview</h2>
                    <div class="prose max-w-none text-gray-500 leading-relaxed" style="font-size: 1.1rem; line-height: 1.8;">
                        <?php if ($overview) : ?>
                            <p style="margin-bottom: 24px; font-weight: 400;"><?php echo esc_html($overview); ?></p>
                        <?php endif; ?>
                        <?php echo $detail_content; ?>
                    </div>
                </div>

                <?php if ($features && is_array($features)) : ?>
                <div style="margin-bottom: 60px;">
                    <h2 style="font-size: 36px; font-weight: 900; color: #141943; margin-bottom: 32px;">What Participants Learn</h2>
                    <div class="flex flex-col gap-6">
                        <?php foreach ($features as $f) : ?>
                        <div class="flex items-start gap-5 p-6 rounded-2xl bg-gray-50 border border-gray-100 transition-all hover:shadow-md">
                            <div style="width: 38px; height: 38px; border-radius: 50%; background: rgba(200, 64, 46, 0.12); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg width="18" height="14" viewBox="0 0 16 14" fill="none"><path d="M1.5 7L6 11.5L14.5 2.5" stroke="#C8402E" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
                            </div>
                            <div>
                                <h3 style="font-size: 19px; font-weight: 800; color: #141943; margin-bottom: 8px;"><?php echo esc_html($f['title']); ?></h3>
                                <p style="font-size: 16px; color: #5B6575; line-height: 1.6;"><?php echo esc_html($f['description']); ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($schedule || $location) : ?>
                <div style="margin-bottom: 60px;">
                    <h2 style="font-size: 36px; font-weight: 900; color: #141943; margin-bottom: 32px;">Program Schedule & Location</h2>
                    <div style="background: #F8FAFC; border-radius: 24px; padding: 40px; border: 1px solid #E2E8F0;">
                        <div class="flex flex-col gap-8">
                            <?php if ($schedule) : ?>
                            <div class="flex items-center gap-5">
                                <div style="width: 52px; height: 52px; border-radius: 14px; background: white; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(20, 25, 67, 0.05); border: 1px solid #EDF2F7;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#141943" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 6v6l4 3"/></svg>
                                </div>
                                <div>
                                    <div style="font-size: 13px; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Time & Frequency</div>
                                    <div style="font-size: 18px; font-weight: 800; color: #141943;"><?php echo esc_html($schedule); ?></div>
                                </div>
                            </div>
                            <?php endif; ?>

                            <?php if ($location) : ?>
                            <div class="flex items-center gap-5">
                                <div style="width: 52px; height: 52px; border-radius: 14px; background: white; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(20, 25, 67, 0.05); border: 1px solid #EDF2F7;">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#141943" stroke-width="2"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                </div>
                                <div>
                                    <div style="font-size: 13px; font-weight: 700; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">Location</div>
                                    <div style="font-size: 18px; font-weight: 800; color: #141943;"><?php echo esc_html($location); ?></div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="lg:col-span-1">
                <div class="program-details-sidebar">
                    <h3 class="program-details-heading">Quick Info</h3>
                    <div class="program-details-meta-list">
                        <?php if ($age_group) : ?>
                        <div class="program-details-meta-card">
                            <div class="program-details-meta-label">Age Range</div>
                            <div class="program-details-meta-value"><?php echo esc_html($age_group); ?></div>
                        </div>
                        <?php endif; ?>

                        <div class="program-details-meta-card">
                            <div class="program-details-meta-label">Skill Level</div>
                            <div class="program-details-meta-value"><?php echo esc_html($skill_level); ?></div>
                        </div>

                        <div class="program-details-meta-card">
                            <div class="program-details-meta-label">Cost</div>
                            <div class="program-details-meta-value"><?php echo esc_html($fee); ?></div>
                        </div>

                        <div class="program-details-meta-card">
                            <div class="program-details-meta-label">Duration</div>
                            <div class="program-details-meta-value"><?php echo esc_html($duration); ?></div>
                        </div>

                        <div class="program-details-meta-card">
                            <div class="program-details-meta-label">Capacity</div>
                            <div class="program-details-meta-value"><?php echo esc_html($capacity); ?></div>
                        </div>
                    </div>
                    <hr class="program-details-divider">
                    <?php if ($is_completed) : ?>
                    <!-- Program Completed Card -->
                    <div style="background: #141943; border-radius: 16px; padding: 28px; margin-bottom: 20px;">
                        <div style="color: #ffffff; font-weight: 900; font-size: 18px; margin-bottom: 12px;">Program Completed</div>
                        <p style="font-size: 14px; color: rgba(255,255,255,0.72); line-height: 1.65; margin: 0 0 20px;">This program has successfully concluded. Check out our current programs for upcoming opportunities.</p>
                        <a href="<?php echo esc_url(home_url('/programs/')); ?>" style="display: flex; align-items: center; justify-content: center; gap: 8px; background: #C8402E; color: #fff; font-weight: 700; font-size: 15px; padding: 14px 24px; border-radius: 9999px; text-decoration: none;">View Current Programs &rarr;</a>
                        <p style="text-align: center; font-size: 13px; color: rgba(255,255,255,0.45); margin-top: 16px;">Questions? <a href="<?php echo esc_url(home_url('/contact/')); ?>" style="color: rgba(255,255,255,0.7); font-weight: 600;">Contact us</a></p>
                    </div>
                    <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="pill-primary w-full justify-center py-5 program-details-cta">Register Now</a>
                    <p class="program-details-contact">Questions? <a href="<?php echo esc_url(home_url('/contact/')); ?>">Contact us</a></p>
                    <?php endif; ?>

                    <!-- Program Coordinator Card -->
                    <div class="program-details-coordinator">
                        <div class="program-details-coordinator-title">Program Coordinator</div>
                        <?php
                        $coordinators = [
                            'basketball-fitness-nights'     => ['name' => 'Coach Ahmed', 'title' => 'Athletics Director',      'email' => 'athletics@myco.org',  'phone' => '(614) 555-9876'],
                            'youth-leadership-mentorship'   => ['name' => 'Sr. Fatima',  'title' => 'Youth Programs Director',  'email' => 'mentorship@myco.org', 'phone' => '(614) 555-2341'],
                            'spiritual-identity-program'    => ['name' => 'Br. Yusuf',   'title' => 'Islamic Studies Lead',     'email' => 'spiritual@myco.org',  'phone' => '(614) 555-3452'],
                            'community-development-service' => ['name' => 'Sr. Maryam',  'title' => 'Community Outreach Lead',  'email' => 'community@myco.org',  'phone' => '(614) 555-4563'],
                        ];
                        $coord = null;
                        foreach ($coordinators as $slug => $data) {
                            if (strpos($current_slug, $slug) !== false) { $coord = $data; break; }
                        }
                        if (!$coord) $coord = ['name' => 'Program Team', 'title' => 'MYCO Staff', 'email' => 'info@myco.org', 'phone' => '(614) 555-0123'];
                        ?>
                        <div class="program-details-coordinator-name"><?php echo esc_html($coord['name']); ?></div>
                        <div class="program-details-coordinator-role"><?php echo esc_html($coord['title']); ?></div>
                        <div class="program-details-coordinator-links">
                            <a href="mailto:<?php echo esc_attr($coord['email']); ?>" class="program-details-coordinator-link is-email">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
                                <?php echo esc_html($coord['email']); ?>
                            </a>
                            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $coord['phone'])); ?>" class="program-details-coordinator-link is-phone">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#C8402E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.1 10.77a19.79 19.79 0 01-3.07-8.67A2 2 0 012.11 0h3a2 2 0 012 1.72c.13.96.36 1.9.7 2.81a2 2 0 01-.45 2.11L6.09 7.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0122 16.92z"/></svg>
                                <?php echo esc_html($coord['phone']); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
