<?php
/**
 * Video upload compatibility handling.
 *
 * @package MYCO
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('add_attachment', 'myco_make_uploaded_video_browser_safe', 20);

/**
 * Re-encode newly uploaded videos to a browser-safe MP4 when FFmpeg is available.
 */
function myco_make_uploaded_video_browser_safe($attachment_id) {
    $attachment_id = (int) $attachment_id;
    if ($attachment_id <= 0) {
        return;
    }

    $mime_type = get_post_mime_type($attachment_id);
    if (strpos((string) $mime_type, 'video/') !== 0) {
        return;
    }

    $source_path = get_attached_file($attachment_id);
    if (!$source_path || !file_exists($source_path)) {
        return;
    }

    $extension = strtolower(pathinfo($source_path, PATHINFO_EXTENSION));
    if (!in_array($extension, ['mp4', 'm4v', 'mov'], true)) {
        return;
    }

    if (preg_match('/-browser\.mp4$/i', basename($source_path))) {
        return;
    }

    if (!function_exists('myco_can_transcode_video_fallback') || !myco_can_transcode_video_fallback($source_path)) {
        update_post_meta($attachment_id, '_myco_video_transcode_status', 'ffmpeg_unavailable');
        return;
    }

    $info = pathinfo($source_path);
    $target_path = ($info['dirname'] ?? '') . DIRECTORY_SEPARATOR . ($info['filename'] ?? 'video') . '-browser.mp4';

    if (!myco_transcode_video_file($source_path, $target_path)) {
        update_post_meta($attachment_id, '_myco_video_transcode_status', 'failed');
        return;
    }

    $uploads = wp_upload_dir();
    $uploads_basedir = trailingslashit(wp_normalize_path($uploads['basedir']));
    $target_normalized = wp_normalize_path($target_path);

    if (strpos($target_normalized, $uploads_basedir) !== 0) {
        update_post_meta($attachment_id, '_myco_video_transcode_status', 'path_update_failed');
        return;
    }

    $target_relative = ltrim(substr($target_normalized, strlen($uploads_basedir)), '/');

    update_attached_file($attachment_id, $target_relative);
    update_post_meta($attachment_id, '_myco_original_video_file', wp_slash($source_path));
    update_post_meta($attachment_id, '_myco_video_transcode_status', 'complete');

    wp_update_post([
        'ID'             => $attachment_id,
        'post_mime_type' => 'video/mp4',
        'guid'           => trailingslashit($uploads['baseurl']) . str_replace('\\', '/', $target_relative),
    ]);

    if (function_exists('wp_update_attachment_metadata')) {
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
        wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $target_path));
    }
}

/**
 * Transcode one local video file into browser-safe MP4.
 */
function myco_transcode_video_file($source_path, $target_path) {
    $ffmpeg = function_exists('myco_get_ffmpeg_binary') ? myco_get_ffmpeg_binary() : '';
    if ($ffmpeg === '' || !is_readable($source_path)) {
        return false;
    }

    $video_filter = 'scale=trunc(iw/2)*2:trunc(ih/2)*2,setsar=1,format=yuv420p';
    $command = sprintf(
        '%s -y -hide_banner -loglevel error -i %s -vf %s -c:v libx264 -preset medium -crf 20 -profile:v main -level 3.1 -movflags +faststart -map_metadata -1 -metadata:s:v:0 rotate=0 -c:a aac -b:a 160k %s 2>&1',
        escapeshellarg($ffmpeg),
        escapeshellarg($source_path),
        escapeshellarg($video_filter),
        escapeshellarg($target_path)
    );

    shell_exec($command);

    return file_exists($target_path) && filesize($target_path) > 0;
}
