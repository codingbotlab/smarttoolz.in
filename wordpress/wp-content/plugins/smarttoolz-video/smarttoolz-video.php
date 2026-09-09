<?php
/**
 * Plugin Name: SmartToolz Video
 * Description: SmartToolz Video platform. WordPress core remains untouched; application behavior is provided by this plugin.
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

require_once SMARTTOOLZ_VIDEO_DIR . 'includes/bootstrap.php';
require_once SMARTTOOLZ_VIDEO_DIR . 'includes/app.php';

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
        <p>Video platform is controlled from the SmartToolz Video plugin. WordPress core files are not modified.</p>
    </div>
    <?php
}

register_activation_hook( __FILE__, 'smarttoolz_video_activate_plugin' );
register_deactivation_hook( __FILE__, 'smarttoolz_video_deactivate_plugin' );
