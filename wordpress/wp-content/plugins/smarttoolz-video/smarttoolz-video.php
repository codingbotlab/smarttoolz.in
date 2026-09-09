<?php
/**
 * Plugin Name: SmartToolz Video
 * Description: SmartToolz Video plugin.
 * Version: 1.0.0
 * Author: SmartToolz
 * Text Domain: smarttoolz-video
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'SMARTTOOLZ_VIDEO_VERSION', '1.0.0' );
define( 'SMARTTOOLZ_VIDEO_FILE', __FILE__ );
define( 'SMARTTOOLZ_VIDEO_DIR', plugin_dir_path( __FILE__ ) );
define( 'SMARTTOOLZ_VIDEO_URL', plugin_dir_url( __FILE__ ) );

function smarttoolz_video_admin_menu() {
    add_menu_page(
        'SmartToolz Video',
        'SmartToolz Video',
        'manage_options',
        'smarttoolz-video',
        'smarttoolz_video_admin_page',
        'dashicons-video-alt3',
        25
    );
}
add_action( 'admin_menu', 'smarttoolz_video_admin_menu' );

function smarttoolz_video_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <div class="wrap">
        <h1>SmartToolz Video</h1>
    </div>
    <?php
}
