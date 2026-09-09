<?php
/**
 * Plugin Name: SmartToolz Video
 * Description: SmartToolz video platform foundation with native WordPress users, video publishing, categories, search and configurable frontend/player settings.
 * Version: 1.0.0
 * Author: SmartToolz
 * Text Domain: smarttoolz-video
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'SMARTTOOLZ_VIDEO_VERSION', '1.0.0' );
define( 'SMARTTOOLZ_VIDEO_FILE', __FILE__ );
define( 'SMARTTOOLZ_VIDEO_DIR', plugin_dir_path( __FILE__ ) );
define( 'SMARTTOOLZ_VIDEO_URL', plugin_dir_url( __FILE__ ) );

require_once SMARTTOOLZ_VIDEO_DIR . 'includes/settings.php';
require_once SMARTTOOLZ_VIDEO_DIR . 'includes/videos.php';
require_once SMARTTOOLZ_VIDEO_DIR . 'includes/frontend.php';

function smarttoolz_video_activate() {
    smarttoolz_video_register_content();
    flush_rewrite_rules();

    $pages = array(
        'video-home' => array('Video Home', '[smarttoolz_video_home]'),
        'video-upload' => array('Upload Video', '[smarttoolz_video_upload]'),
    );
    foreach ( $pages as $slug => $page ) {
        if ( ! get_page_by_path( $slug ) ) {
            wp_insert_post( array(
                'post_title' => $page[0],
                'post_name' => $slug,
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_content' => $page[1],
            ) );
        }
    }
}
register_activation_hook( __FILE__, 'smarttoolz_video_activate' );

function smarttoolz_video_deactivate() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'smarttoolz_video_deactivate' );
