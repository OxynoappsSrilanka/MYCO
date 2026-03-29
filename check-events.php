<?php
/**
 * Temporary diagnostic script to check event dates
 * Access via: http://localhost/wordpress/wp-content/themes/MYCO/check-events.php
 */

// Load WordPress
require_once('../../../wp-load.php');

// Check if user is logged in
if (!is_user_logged_in()) {
    die('Please log in to WordPress first');
}

echo '<h1>Event Diagnostics</h1>';
echo '<style>
    body { font-family: Arial, sans-serif; padding: 20px; }
    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
    th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
    th { background-color: #141943; color: white; }
    .missing { background-color: #ffebee; color: #c62828; font-weight: bold; }
    .has-date { background-color: #e8f5e9; }
</style>';

// Get ALL published events (without meta_key filter)
$all_events = new WP_Query([
    'post_type'      => 'event',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'DESC',
]);

echo '<h2>All Published Events: ' . $all_events->found_posts . '</h2>';

if ($all_events->have_posts()) {
    echo '<table>';
    echo '<tr>
            <th>ID</th>
            <th>Title</th>
            <th>Status</th>
            <th>Event Date (ACF)</th>
            <th>Has Date?</th>
            <th>Published Date</th>
          </tr>';
    
    while ($all_events->have_posts()) {
        $all_events->the_post();
        $post_id = get_the_ID();
        
        // Try to get event_date using ACF function
        $event_date_acf = function_exists('get_field') ? get_field('event_date', $post_id) : null;
        
        // Also try direct meta query as fallback
        $event_date_meta = get_post_meta($post_id, 'event_date', true);
        
        $has_date = !empty($event_date_acf) || !empty($event_date_meta);
        $row_class = $has_date ? 'has-date' : 'missing';
        
        echo '<tr class="' . $row_class . '">';
        echo '<td>' . $post_id . '</td>';
        echo '<td>' . get_the_title() . '</td>';
        echo '<td>' . get_post_status() . '</td>';
        echo '<td>' . ($event_date_acf ?: $event_date_meta ?: '<span class="missing">MISSING</span>') . '</td>';
        echo '<td>' . ($has_date ? '✓ Yes' : '✗ NO') . '</td>';
        echo '<td>' . get_the_date('Y-m-d H:i:s') . '</td>';
        echo '</tr>';
    }
    
    echo '</table>';
    wp_reset_postdata();
} else {
    echo '<p>No published events found.</p>';
}

echo '<hr>';
echo '<h2>Events Query WITH meta_key Filter (Current Theme Query)</h2>';

// Get events WITH meta_key filter (current theme query)
$filtered_events = new WP_Query([
    'post_type'      => 'event',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'meta_key'       => 'event_date',
    'orderby'        => 'meta_value',
    'order'          => 'ASC',
]);

echo '<p>Events returned: ' . $filtered_events->found_posts . '</p>';

if ($filtered_events->have_posts()) {
    echo '<table>';
    echo '<tr><th>ID</th><th>Title</th><th>Event Date</th></tr>';
    
    while ($filtered_events->have_posts()) {
        $filtered_events->the_post();
        $event_date = function_exists('get_field') ? get_field('event_date', get_the_ID()) : get_post_meta(get_the_ID(), 'event_date', true);
        
        echo '<tr>';
        echo '<td>' . get_the_ID() . '</td>';
        echo '<td>' . get_the_title() . '</td>';
        echo '<td>' . $event_date . '</td>';
        echo '</tr>';
    }
    
    echo '</table>';
    wp_reset_postdata();
}

echo '<hr>';
echo '<h3>Conclusion:</h3>';
echo '<p>If you see 4 events in the first table but only 3 in the second table, then one event is missing the <code>event_date</code> ACF field.</p>';
echo '<p>The missing event will be highlighted in RED in the first table.</p>';
echo '<p><strong>Solution:</strong> Edit that event in MYCO Studio and make sure to fill in the "Event Date" field.</p>';
