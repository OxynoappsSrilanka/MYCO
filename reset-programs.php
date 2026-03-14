<?php
/**
 * MYCO - Reset Programs Script
 * 
 * This file deletes all existing Program posts and recreates them with the correct images.
 * 
 * INSTRUCTIONS:
 * 1. Upload this file to your WordPress root directory (same level as wp-config.php)
 * 2. Visit: https://your-site.com/reset-programs.php in your browser
 * 3. Delete this file after running it once
 * 
 * @package MYCO
 */

// Load WordPress
require_once('wp-load.php');

// Security check - only allow admins
if (!current_user_can('manage_options')) {
    wp_die('Unauthorized access');
}

echo '<h1>MYCO Programs Reset</h1>';
echo '<p>Deleting old programs and creating new ones with correct images...</p>';

// Delete all existing programs
$existing_programs = get_posts([
    'post_type' => 'program',
    'posts_per_page' => -1,
    'post_status' => 'any',
]);

foreach ($existing_programs as $program) {
    wp_delete_post($program->ID, true);
    echo '<p>✓ Deleted: ' . esc_html($program->post_title) . '</p>';
}

// Create new programs with proper titles, descriptions, and images
$programs = [
    [
        'title'   => 'Youth Leadership Development',
        'excerpt' => 'Helping youth build confidence, communication skills, teamwork, responsibility, and leadership rooted in Islamic values.',
        'content' => '<p>Our Youth Leadership Development program empowers Muslim youth to become confident leaders in their communities. Through workshops, mentorship, and hands-on projects, participants develop essential skills in communication, teamwork, and decision-making—all grounded in Islamic values.</p><p>This program meets weekly and includes leadership training, public speaking practice, community service projects, and opportunities to lead youth initiatives. Students learn to balance Islamic principles with modern leadership techniques.</p><p>Past participants have gone on to lead community organizations, start youth groups, and become positive role models in their schools and neighborhoods.</p>',
        'image'   => 'myco-youth-team-award-check-winners.jpg',
        'category' => 'Leadership',
    ],
    [
        'title'   => 'Spiritual Development',
        'excerpt' => 'Lectures, youth halaqas, Islamic learning opportunities, and guidance that strengthens faith and identity.',
        'content' => '<p>Strengthen your connection with Allah through our Spiritual Development program. We offer youth halaqas, Islamic lectures, Quran study circles, and spiritual guidance sessions that help young Muslims deepen their faith and understanding of Islam.</p><p>Our program includes weekly halaqas led by knowledgeable scholars, monthly guest speakers from around the country, Quran memorization support, and special Ramadan programming. Youth learn to apply Islamic teachings to their daily lives while building a strong Muslim identity.</p><p>Sessions cover topics like prayer, character development, Islamic history, contemporary issues facing Muslim youth, and how to maintain faith in a modern world.</p>',
        'image'   => 'myco-youth-basketball-event-congregational-prayer.jpg',
        'category' => 'Spiritual',
    ],
    [
        'title'   => 'Education & Skill Building',
        'excerpt' => 'Support through educational initiatives such as computer literacy, counseling, learning support, and developmental programming.',
        'content' => '<p>Our Education & Skill Building program provides comprehensive academic support and life skills training. From homework help and tutoring to computer literacy and career counseling, we equip youth with the tools they need to succeed in school and beyond.</p><p>Services include free tutoring in math, science, English, and other core subjects, SAT/ACT prep courses, college application assistance, resume building workshops, interview preparation, and technology training including coding and digital literacy.</p><p>We also offer academic counseling, study skills workshops, and mentorship from professionals in various fields. Our goal is to help every student reach their full academic potential while preparing them for future careers.</p>',
        'image'   => 'myco-youth-community-center-groundbreaking-ceremony.jpg',
        'category' => 'Education',
    ],
    [
        'title'   => 'Athletics & Training',
        'excerpt' => 'Basketball, soccer, and other active programming that builds discipline, confidence, and brotherhood/sisterhood.',
        'content' => '<p>Stay active and build lasting friendships through our Athletics & Training program. We offer basketball leagues, soccer tournaments, fitness training, and other sports activities that promote physical health, teamwork, and Islamic brotherhood/sisterhood.</p><p>Our program includes weekly basketball nights with skill development sessions, seasonal soccer leagues for all ages, fitness and strength training classes, sports tournaments with prizes and recognition, and team-building activities that emphasize sportsmanship and Islamic values.</p><p>All skill levels are welcome, from beginners learning the basics to experienced athletes looking to compete. Our coaches emphasize character development, discipline, and respect alongside athletic skills.</p>',
        'image'   => 'myco-basketball-champions-team-with-trophy.jpg.jpg',
        'category' => 'Athletics',
    ],
    [
        'title'   => 'Social & Cultural Activities',
        'excerpt' => 'Gatherings that foster belonging, friendship, and community connection across backgrounds.',
        'content' => '<p>Build meaningful connections through our Social & Cultural Activities program. We host game nights, cultural celebrations, community dinners, and social events that bring Muslim youth together in a welcoming, faith-centered environment.</p><p>Our events include monthly game nights with board games and video game tournaments, Eid celebrations with food and entertainment, cultural heritage nights celebrating the diversity of the Muslim community, community iftars during Ramadan, youth talent shows, and social mixers for teens and young adults.</p><p>These gatherings provide a safe, halal space for youth to socialize, make friends, and build a sense of belonging within the Muslim community. All events are supervised and maintain Islamic guidelines.</p>',
        'image'   => 'myco-basketball-tournament-award-ceremony-team-celebration.jpg.JPG',
        'category' => 'Social',
    ],
    [
        'title'   => 'Community Service & Innovation',
        'excerpt' => 'Volunteer initiatives that teach youth to serve others and contribute meaningfully to their communities.',
        'content' => '<p>Make a real difference through our Community Service & Innovation program. Youth participate in volunteer projects, food drives, community clean-ups, and innovative service initiatives that embody Islamic values of compassion and social responsibility.</p><p>Our service projects include monthly food bank volunteering, neighborhood clean-up days, visiting nursing homes and hospitals, organizing charity drives for local families in need, tutoring younger students, and youth-led community improvement projects.</p><p>We partner with local organizations to maximize our impact and teach youth the importance of giving back. Students learn project management, teamwork, and leadership skills while making a tangible difference in their community. Service hours can be used for school requirements.</p>',
        'image'   => 'MCYC Groundbreaking_ Aatifa.jpg',
        'category' => 'Community',
    ],
];

foreach ($programs as $p) {
    $id = wp_insert_post([
        'post_title'   => $p['title'],
        'post_content' => $p['content'],
        'post_excerpt' => $p['excerpt'],
        'post_status'  => 'publish',
        'post_type'    => 'program',
        'post_author'  => get_current_user_id(),
    ]);
    
    if ($id && !empty($p['image'])) {
        $image_path = get_template_directory() . '/assets/images/Galleries/' . $p['image'];
        
        if (file_exists($image_path)) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');
            
            $upload_dir = wp_upload_dir();
            $filename = basename($image_path);
            $new_file = $upload_dir['path'] . '/' . $filename;
            
            // Copy file to uploads
            copy($image_path, $new_file);
            
            $filetype = wp_check_filetype($filename);
            $attachment = [
                'guid'           => $upload_dir['url'] . '/' . $filename,
                'post_mime_type' => $filetype['type'],
                'post_title'     => preg_replace('/\.[^.]+$/', '', $filename),
                'post_content'   => '',
                'post_status'    => 'inherit'
            ];
            
            $attach_id = wp_insert_attachment($attachment, $new_file, $id);
            $attach_data = wp_generate_attachment_metadata($attach_id, $new_file);
            wp_update_attachment_metadata($attach_id, $attach_data);
            set_post_thumbnail($id, $attach_id);
            
            echo '<p>✓ Created: ' . esc_html($p['title']) . ' (with image)</p>';
        } else {
            echo '<p>⚠ Created: ' . esc_html($p['title']) . ' (image not found: ' . esc_html($p['image']) . ')</p>';
        }
    }
}

echo '<h2>Done!</h2>';
echo '<p><strong>Programs have been reset successfully with proper titles and descriptions!</strong></p>';
echo '<p><a href="' . home_url('/programs/') . '">View Programs Page</a></p>';
echo '<p style="color: red;"><strong>IMPORTANT: Delete this file (reset-programs.php) from your server now!</strong></p>';
