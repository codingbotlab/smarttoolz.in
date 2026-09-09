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
        'SmartToolz',
        'SmartToolz',
        'manage_options',
        'smarttoolz',
        'smarttoolz_video_dashboard_page',
        'dashicons-video-alt3',
        25
    );

    add_submenu_page(
        'smarttoolz',
        'Page Settings',
        'Page Settings',
        'manage_options',
        'smarttoolz-video-pages',
        'smarttoolz_video_page_settings'
    );
}
add_action( 'admin_menu', 'smarttoolz_video_admin_menu' );

function smarttoolz_video_dashboard_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
    ?>
    <div class="wrap">
        <h1>SmartToolz</h1>
        <p>SmartToolz Video platform dashboard.</p>
    </div>
    <?php
}

function smarttoolz_video_page_settings() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    if ( isset( $_POST['stv_sync_pages'], $_POST['stv_sync_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['stv_sync_nonce'] ) ), 'stv_sync_pages' ) ) {
        smarttoolz_video_sync_pages();
        echo '<div class="notice notice-success is-dismissible"><p>SmartToolz Video pages synced successfully.</p></div>';
    }

    $definitions = smarttoolz_video_page_definitions();
    $ids = (array) get_option( 'smarttoolz_video_page_ids', array() );
    ?>
    <div class="wrap">
        <h1>Page Settings</h1>
        <p>Manage and repair all SmartToolz Video pages. WordPress core files are not modified.</p>

        <form method="post">
            <?php wp_nonce_field( 'stv_sync_pages', 'stv_sync_nonce' ); ?>
            <input type="hidden" name="stv_sync_pages" value="1">
            <?php submit_button( 'Sync / Repair Pages', 'primary', 'submit', false ); ?>
        </form>

        <h2>Managed Pages</h2>
        <table class="widefat striped">
            <thead>
                <tr>
                    <th>Section</th>
                    <th>Page ID</th>
                    <th>Status</th>
                    <th>URL</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ( $definitions as $key => $page ) :
                $id = isset( $ids[ $key ] ) ? absint( $ids[ $key ] ) : 0;
                $valid = $id && 'page' === get_post_type( $id ) && 'trash' !== get_post_status( $id );
                $url = $valid ? get_permalink( $id ) : home_url( '/' . trim( $page['slug'], '/' ) . '/' );
                ?>
                <tr>
                    <td><?php echo esc_html( $page['title'] ); ?></td>
                    <td><?php echo $id ? esc_html( $id ) : '—'; ?></td>
                    <td><?php echo $valid ? '<span style="color:#008a20">Active</span>' : '<span style="color:#b32d2e">Missing</span>'; ?></td>
                    <td><a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $url ); ?></a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}

register_activation_hook( __FILE__, 'smarttoolz_video_activate_plugin' );
register_deactivation_hook( __FILE__, 'smarttoolz_video_deactivate_plugin' );
