<?php
/**
 * Program Detail Blueprints
 *
 * @package MYCO
 */

if (!defined('ABSPATH')) {
    exit;
}

function myco_get_program_blueprints() {
    static $blueprints = null;

    if ($blueprints !== null) {
        return $blueprints;
    }

    $blueprints = [
        'youth-leadership-development' => [
            'title'        => 'Youth Leadership Development',
            'category'     => 'Leadership Track',
            'summary'      => 'Helping youth build confidence, communication skills, teamwork, responsibility, and leadership rooted in Islamic values.',
            'image'        => MYCO_URI . '/assets/images/Galleries/myco-youth-team-award-check-winners.jpg',
            'accent'       => '#C8402E',
            'accent_soft'  => 'rgba(200, 64, 46, 0.10)',
            'overview'     => [
                'Youth Leadership Development is a high-trust program where Muslim youth practice leadership in real settings, not just in theory. Every cohort blends reflection, mentorship, communication training, and service so participants grow in both confidence and character.',
                'Students learn how to lead conversations, take initiative, manage team dynamics, and represent their values with sincerity. The goal is not only stronger student leaders, but stronger future mentors, organizers, and role models for the wider community.',
            ],
            'stats'        => [
                ['value' => '13-18', 'label' => 'ideal age'],
                ['value' => '8 weeks', 'label' => 'cohort cycle'],
                ['value' => '24 seats', 'label' => 'small-group format'],
            ],
            'experience'   => [
                ['title' => 'Confidence & Communication', 'description' => 'Public speaking labs, guided discussion circles, and storytelling exercises help youth express ideas clearly and with purpose.'],
                ['title' => 'Mentor-Guided Growth', 'description' => 'Participants receive practical guidance from mentors who model leadership with humility, faith, and consistency.'],
                ['title' => 'Team Projects', 'description' => 'Youth collaborate on initiatives that build planning, ownership, and healthy accountability within a team setting.'],
                ['title' => 'Service-Led Leadership', 'description' => 'Every cohort connects leadership to service so students learn to lead by benefiting others, not just by taking charge.'],
            ],
            'journey'      => [
                ['step' => '01', 'title' => 'Discover strengths', 'description' => 'Participants begin by identifying strengths, growth areas, and personal leadership goals grounded in Islamic character.'],
                ['step' => '02', 'title' => 'Practice in community', 'description' => 'Workshops and project-based activities give students repeated opportunities to lead, communicate, and respond under guidance.'],
                ['step' => '03', 'title' => 'Lead with purpose', 'description' => 'Students complete the cycle with stronger self-awareness, greater ownership, and a clearer sense of how to serve their communities.'],
            ],
            'outcomes'     => [
                'Stronger public speaking and team communication.',
                'Greater initiative in school, masjid, and community spaces.',
                'A clearer connection between leadership and Islamic character.',
                'Practical experience planning and delivering projects.',
            ],
            'schedule'     => 'Sundays • 4:30 PM - 6:30 PM',
            'age_group'    => 'Ages 13-18',
            'location'     => 'MYCO Leadership Studio',
            'fee'          => 'Free community program',
            'duration'     => '2-hour weekly sessions',
            'capacity'     => '24 participants per cohort',
            'skill_level'  => 'Open to emerging and experienced youth leaders',
            'cta_title'    => 'Ready to help a young leader grow?',
            'cta_copy'     => 'Reach out to our team to learn about the next cohort, mentor opportunities, and registration details.',
            'coordinator'  => [
                'name'  => 'Sr. Amina Rahman',
                'role'  => 'Leadership Programs Lead',
                'email' => 'leadership@myco.org',
                'phone' => '(614) 555-0141',
            ],
        ],
        'spiritual-development' => [
            'title'        => 'Spiritual Development',
            'category'     => 'Faith Formation',
            'summary'      => 'Lectures, youth halaqas, Islamic learning opportunities, and guidance that strengthens faith and identity.',
            'image'        => MYCO_URI . '/assets/images/Galleries/myco-youth-basketball-event-congregational-prayer.jpg',
            'accent'       => '#375A9E',
            'accent_soft'  => 'rgba(55, 90, 158, 0.10)',
            'overview'     => [
                'Spiritual Development is designed to help young Muslims deepen faith in a way that feels rooted, relevant, and sustaining. Sessions connect Quran, prophetic character, and lived experience so youth can build a confident, thoughtful relationship with Islam.',
                'Rather than treating spiritual growth as something separate from daily life, this program helps participants carry faith into school, friendships, family life, and personal decisions. The result is a stronger sense of identity, belonging, and purpose.',
            ],
            'stats'        => [
                ['value' => '14-22', 'label' => 'youth & young adults'],
                ['value' => 'Weekly', 'label' => 'faith circles'],
                ['value' => 'Year-round', 'label' => 'growth rhythm'],
            ],
            'experience'   => [
                ['title' => 'Youth Halaqas', 'description' => 'Small-group circles create a warm space for reflection, questions, and honest conversation about faith and life.'],
                ['title' => 'Applied Islamic Learning', 'description' => 'Topics are practical and timely, helping youth connect sacred knowledge to the pressures and opportunities of modern life.'],
                ['title' => 'Mentorship & Counsel', 'description' => 'Scholars and trusted mentors guide participants with compassion, clarity, and an understanding of youth realities.'],
                ['title' => 'Spiritual Habits', 'description' => 'Students are supported in building consistent practices around prayer, Quran, remembrance, and inward discipline.'],
            ],
            'journey'      => [
                ['step' => '01', 'title' => 'Reconnect', 'description' => 'Each cycle begins by helping youth name where they are spiritually and what they want to strengthen.'],
                ['step' => '02', 'title' => 'Learn with relevance', 'description' => 'Sessions unpack belief, identity, worship, and character through real questions youth are already navigating.'],
                ['step' => '03', 'title' => 'Carry it forward', 'description' => 'Participants leave with clearer habits, stronger faith literacy, and a community that helps them stay grounded.'],
            ],
            'outcomes'     => [
                'Greater confidence in Muslim identity.',
                'Healthier daily spiritual routines.',
                'Space to ask difficult questions with trusted guidance.',
                'A stronger sense of brotherhood and sisterhood in faith.',
            ],
            'schedule'     => 'Wednesdays • 7:00 PM - 9:00 PM',
            'age_group'    => 'Ages 14-22',
            'location'     => 'MYCO Prayer Center & Learning Lounge',
            'fee'          => 'Free',
            'duration'     => '2-hour weekly sessions',
            'capacity'     => 'Open enrollment with small group circles',
            'skill_level'  => 'Welcoming to all levels of Islamic learning',
            'cta_title'    => 'Looking for a faith-centered space to grow?',
            'cta_copy'     => 'Connect with our team to find the right halaqa, learning circle, or seasonal spiritual program.',
            'coordinator'  => [
                'name'  => 'Br. Yusuf Karim',
                'role'  => 'Faith & Learning Coordinator',
                'email' => 'spiritual@myco.org',
                'phone' => '(614) 555-0142',
            ],
        ],
        'education-skill-building' => [
            'title'        => 'Education & Skill Building',
            'category'     => 'Academic Growth',
            'summary'      => 'Support through educational initiatives such as computer literacy, counseling, learning support, and developmental programming.',
            'image'        => MYCO_URI . '/assets/images/Galleries/myco-youth-community-center-groundbreaking-ceremony.jpg',
            'accent'       => '#2F6B7B',
            'accent_soft'  => 'rgba(47, 107, 123, 0.10)',
            'overview'     => [
                'Education & Skill Building helps youth move from academic stress to steady progress. We combine tutoring, digital literacy, coaching, and practical life-skills support so students gain both confidence and capability.',
                'The program is designed for the whole student. That means helping with homework and study plans, but also building organization, communication, responsibility, and readiness for college, careers, and adult life.',
            ],
            'stats'        => [
                ['value' => '6-12', 'label' => 'grade support'],
                ['value' => 'STEM + life', 'label' => 'skill mix'],
                ['value' => 'Small groups', 'label' => 'personal attention'],
            ],
            'experience'   => [
                ['title' => 'Tutoring Support', 'description' => 'Students receive targeted help in core subjects with a focus on consistency, confidence, and clear academic habits.'],
                ['title' => 'Digital Literacy', 'description' => 'Youth build comfort with technology, productivity tools, research skills, and responsible digital citizenship.'],
                ['title' => 'Study Systems', 'description' => 'Workshops focus on time management, note-taking, organization, and test preparation that students can use right away.'],
                ['title' => 'Career & College Readiness', 'description' => 'Older youth explore pathways, build professional habits, and prepare for the next stage with practical guidance.'],
            ],
            'journey'      => [
                ['step' => '01', 'title' => 'Assess the need', 'description' => 'Students and families identify the academic or skill areas where support will make the biggest difference first.'],
                ['step' => '02', 'title' => 'Build momentum', 'description' => 'Through tutoring, coaching, and repetition, participants develop routines that make progress visible and sustainable.'],
                ['step' => '03', 'title' => 'Prepare for next steps', 'description' => 'Youth leave stronger academically and better equipped for high school, college, work, and life transitions.'],
            ],
            'outcomes'     => [
                'Stronger study habits and academic consistency.',
                'Better digital fluency and task management.',
                'More confidence asking for help and setting goals.',
                'Practical readiness for future education and career paths.',
            ],
            'schedule'     => 'Tuesdays & Thursdays • 5:00 PM - 7:00 PM',
            'age_group'    => 'Grades 6-12',
            'location'     => 'MYCO Learning Hub',
            'fee'          => 'Free tutoring with select premium workshops',
            'duration'     => '2-hour support blocks',
            'capacity'     => 'Rolling enrollment by session type',
            'skill_level'  => 'Open to students needing support or stretch opportunities',
            'cta_title'    => 'Need support for school, skills, or next steps?',
            'cta_copy'     => 'Our education team can help you choose the right tutoring, skills, or readiness pathway.',
            'coordinator'  => [
                'name'  => 'Sr. Mariam Siddiqi',
                'role'  => 'Education Programs Manager',
                'email' => 'education@myco.org',
                'phone' => '(614) 555-0143',
            ],
        ],
        'athletics-training' => [
            'title'        => 'Athletics & Training',
            'category'     => 'Active Wellness',
            'summary'      => 'Basketball, soccer, and other active programming that builds discipline, confidence, and brotherhood/sisterhood.',
            'image'        => MYCO_URI . '/assets/images/Galleries/myco-basketball-champions-team-with-trophy.jpg.jpg',
            'accent'       => '#C8402E',
            'accent_soft'  => 'rgba(200, 64, 46, 0.12)',
            'overview'     => [
                'Athletics & Training gives youth a place to move, compete, and build healthy discipline in an environment shaped by community values. Programs combine skill development, physical fitness, team culture, and character formation.',
                'Whether a participant is learning the fundamentals or already loves to compete, the emphasis stays the same: grow in strength, show respect, play with ihsan, and become the kind of teammate people trust.',
            ],
            'stats'        => [
                ['value' => 'Weekly', 'label' => 'training nights'],
                ['value' => 'All levels', 'label' => 'skill access'],
                ['value' => 'Team based', 'label' => 'growth culture'],
            ],
            'experience'   => [
                ['title' => 'Basketball & Court Skills', 'description' => 'Youth sharpen fundamentals, game awareness, and healthy competitive habits through coached sessions and structured play.'],
                ['title' => 'Fitness & Conditioning', 'description' => 'Movement, endurance, and strength training help participants build healthy routines and long-term discipline.'],
                ['title' => 'Team Culture', 'description' => 'Training spaces are designed to reinforce respect, accountability, effort, and encouragement among peers.'],
                ['title' => 'Confidence Through Action', 'description' => 'Athletics create a practical pathway for youth to develop resilience, consistency, and leadership under pressure.'],
            ],
            'journey'      => [
                ['step' => '01', 'title' => 'Train the basics', 'description' => 'Participants build a strong athletic foundation with repetition, coaching feedback, and good habits.'],
                ['step' => '02', 'title' => 'Compete with purpose', 'description' => 'Games and team sessions help youth apply discipline, communication, and emotional control in real time.'],
                ['step' => '03', 'title' => 'Grow beyond the court', 'description' => 'Students carry confidence, consistency, and teamwork into the rest of their lives.'],
            ],
            'outcomes'     => [
                'Improved athletic confidence and physical conditioning.',
                'Stronger teamwork, sportsmanship, and resilience.',
                'A healthier rhythm of movement and self-discipline.',
                'Positive peer connection in a supervised Muslim environment.',
            ],
            'schedule'     => 'Fridays • 6:00 PM - 8:30 PM',
            'age_group'    => 'Ages 12-18',
            'location'     => 'MYCO Community Gym',
            'fee'          => 'Free for members',
            'duration'     => '2.5-hour weekly sessions',
            'capacity'     => '30 athletes per training block',
            'skill_level'  => 'Beginner through advanced youth athletes',
            'cta_title'    => 'Want a strong, values-based place to train?',
            'cta_copy'     => 'Reach out for current athletics sessions, seasonal leagues, and registration support.',
            'coordinator'  => [
                'name'  => 'Coach Ahmed Farouq',
                'role'  => 'Athletics Director',
                'email' => 'athletics@myco.org',
                'phone' => '(614) 555-0144',
            ],
        ],
        'social-cultural-activities' => [
            'title'        => 'Social & Cultural Activities',
            'category'     => 'Belonging & Culture',
            'summary'      => 'Gatherings that foster belonging, friendship, and community connection across backgrounds.',
            'image'        => MYCO_URI . '/assets/images/Galleries/myco-basketball-tournament-award-ceremony-team-celebration.jpg.JPG',
            'accent'       => '#8C516E',
            'accent_soft'  => 'rgba(140, 81, 110, 0.10)',
            'overview'     => [
                'Social & Cultural Activities create the kind of environment where youth can relax, connect, celebrate, and feel like they belong. These gatherings are intentionally warm, supervised, and rooted in a confident Muslim identity.',
                'From game nights and creative gatherings to cultural celebrations and shared meals, the program gives youth a healthier social space and a stronger connection to community life. Friendship becomes something supported, not left to chance.',
            ],
            'stats'        => [
                ['value' => 'Monthly', 'label' => 'signature events'],
                ['value' => 'Inclusive', 'label' => 'community spaces'],
                ['value' => 'Faith-centered', 'label' => 'social setting'],
            ],
            'experience'   => [
                ['title' => 'Game & Social Nights', 'description' => 'Easy-entry gatherings help youth connect naturally through fun, conversation, and shared experiences.'],
                ['title' => 'Cultural Celebration', 'description' => 'Events honor the richness of Muslim backgrounds while reinforcing unity, curiosity, and mutual respect.'],
                ['title' => 'Creative Community', 'description' => 'Youth explore expression through art, performances, storytelling, and collaborative event moments.'],
                ['title' => 'Safe Belonging', 'description' => 'The program offers a supervised social environment where young people can feel seen, welcomed, and connected.'],
            ],
            'journey'      => [
                ['step' => '01', 'title' => 'Show up comfortably', 'description' => 'Events are designed to feel welcoming for both first-time participants and returning youth.'],
                ['step' => '02', 'title' => 'Build friendship', 'description' => 'Activities make it easier for youth to connect, collaborate, and enjoy halal social time together.'],
                ['step' => '03', 'title' => 'Belong more deeply', 'description' => 'Over time, participants build stronger peer networks and a more rooted connection to the MYCO community.'],
            ],
            'outcomes'     => [
                'More confidence in community spaces.',
                'Healthier friendships and stronger peer connection.',
                'A deeper sense of Muslim belonging and joy.',
                'Positive social memories tied to faith-centered spaces.',
            ],
            'schedule'     => 'Monthly evenings + seasonal special events',
            'age_group'    => 'Middle school through young adult',
            'location'     => 'MYCO Commons & Event Hall',
            'fee'          => 'Most events free or low-cost',
            'duration'     => 'Event-based programming',
            'capacity'     => 'Varies by event format',
            'skill_level'  => 'No experience needed, just come as you are',
            'cta_title'    => 'Looking for a place to belong and connect?',
            'cta_copy'     => 'Ask about our upcoming gatherings, family events, and youth community nights.',
            'coordinator'  => [
                'name'  => 'Sr. Layla Osman',
                'role'  => 'Community Life Coordinator',
                'email' => 'communitylife@myco.org',
                'phone' => '(614) 555-0145',
            ],
        ],
        'community-service-innovation' => [
            'title'        => 'Community Service & Innovation',
            'category'     => 'Service & Impact',
            'summary'      => 'Volunteer initiatives that teach youth to serve others and contribute meaningfully to their communities.',
            'image'        => MYCO_URI . '/assets/images/Galleries/MCYC Groundbreaking_ Aatifa.jpg',
            'accent'       => '#266A60',
            'accent_soft'  => 'rgba(38, 106, 96, 0.11)',
            'overview'     => [
                'Community Service & Innovation equips youth to notice needs, respond with compassion, and build ideas that improve the lives of others. Service projects are not treated as one-off tasks, but as a way of forming responsibility, empathy, and civic confidence.',
                'Participants learn how to organize, collaborate, and think creatively while serving families, neighbors, and local partners. It is a program for youth who want to be useful, thoughtful, and action-oriented in the best sense.',
            ],
            'stats'        => [
                ['value' => 'Monthly', 'label' => 'service days'],
                ['value' => 'Hands-on', 'label' => 'local impact'],
                ['value' => 'Youth-led', 'label' => 'project ownership'],
            ],
            'experience'   => [
                ['title' => 'Service Projects', 'description' => 'Youth participate in organized initiatives that directly support local families, nonprofits, and community needs.'],
                ['title' => 'Innovation Thinking', 'description' => 'Students are encouraged to identify problems, generate ideas, and test practical ways to make a difference.'],
                ['title' => 'Partnership & Outreach', 'description' => 'Participants build confidence through collaboration with community partners and service networks.'],
                ['title' => 'Reflection & Responsibility', 'description' => 'Each project helps youth connect action, intention, and long-term responsibility to their values.'],
            ],
            'journey'      => [
                ['step' => '01', 'title' => 'See the need', 'description' => 'Youth learn to look at their community with empathy, awareness, and a readiness to respond.'],
                ['step' => '02', 'title' => 'Serve together', 'description' => 'Projects are completed as teams so participants practice logistics, responsibility, and service-minded leadership.'],
                ['step' => '03', 'title' => 'Build lasting impact', 'description' => 'The program helps youth move from one-time volunteering to a deeper habit of meaningful contribution.'],
            ],
            'outcomes'     => [
                'Practical experience serving real community needs.',
                'Stronger empathy, initiative, and project ownership.',
                'A deeper sense of responsibility rooted in faith.',
                'Exposure to leadership through action and problem-solving.',
            ],
            'schedule'     => 'One major service Saturday each month + pop-up opportunities',
            'age_group'    => 'Ages 13+ with family-friendly projects available',
            'location'     => 'MYCO + partner community sites',
            'fee'          => 'Free',
            'duration'     => 'Project-based sessions',
            'capacity'     => 'Varies by service event',
            'skill_level'  => 'Open to all youth ready to serve and contribute',
            'cta_title'    => 'Want to turn compassion into action?',
            'cta_copy'     => 'Connect with our team for upcoming service projects, volunteer pathways, and family-friendly opportunities.',
            'coordinator'  => [
                'name'  => 'Br. Ibrahim Saleh',
                'role'  => 'Service & Outreach Lead',
                'email' => 'service@myco.org',
                'phone' => '(614) 555-0146',
            ],
        ],
    ];

    if (function_exists('myco_version_theme_url')) {
        foreach ($blueprints as $slug => $blueprint) {
            if (!empty($blueprint['image'])) {
                $blueprints[$slug]['image'] = myco_version_theme_url($blueprint['image']);
            }
        }
    }

    return $blueprints;
}

function myco_get_program_slug_aliases() {
    return [
        'basketball-fitness-nights'      => 'athletics-training',
        'youth-leadership-mentorship'    => 'youth-leadership-development',
        'spiritual-identity-program'     => 'spiritual-development',
        'community-development-service'  => 'community-service-innovation',
    ];
}

function myco_normalize_program_slug($slug_or_title = '') {
    $normalized = sanitize_title((string) $slug_or_title);

    if ($normalized === '') {
        return '';
    }

    $aliases = myco_get_program_slug_aliases();

    return $aliases[$normalized] ?? $normalized;
}

function myco_get_program_blueprint($slug_or_title = '') {
    if (!$slug_or_title) {
        $slug_or_title = get_post_field('post_name', get_the_ID()) ?: get_the_title();
    }

    $normalized = sanitize_title((string) $slug_or_title);
    $blueprints = myco_get_program_blueprints();

    if (isset($blueprints[$normalized])) {
        return $blueprints[$normalized] + ['slug' => $normalized];
    }

    $canonical = myco_normalize_program_slug($normalized);

    if ($canonical !== '' && isset($blueprints[$canonical])) {
        return $blueprints[$canonical] + ['slug' => $canonical];
    }

    foreach ($blueprints as $slug => $blueprint) {
        if (sanitize_title($blueprint['title']) === $normalized) {
            return $blueprint + ['slug' => $slug];
        }
    }

    return null;
}

function myco_get_program_detail_url($title_or_slug = '') {
    $normalized = sanitize_title((string) $title_or_slug);

    if ($normalized === '') {
        return myco_get_page_url('programs', '/programs/');
    }

    $program_post = get_page_by_path($normalized, OBJECT, 'program');
    if ($program_post instanceof WP_Post) {
        return get_permalink($program_post);
    }

    $canonical = myco_normalize_program_slug($normalized);

    if ($canonical !== $normalized) {
        $canonical_post = get_page_by_path($canonical, OBJECT, 'program');
        if ($canonical_post instanceof WP_Post) {
            return get_permalink($canonical_post);
        }
    }

    return home_url('/programs/' . ($canonical ?: $normalized) . '/');
}
