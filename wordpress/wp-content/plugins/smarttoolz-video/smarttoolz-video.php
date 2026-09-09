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
require_once SMARTTOOLZ_VIDEO_DIR . 'includes/theme-installer.php';
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
        'Dashboard',
        'Dashboard',
        'manage_options',
        'smarttoolz',
        'smarttoolz_video_dashboard_page'
    );

    add_submenu_page(
        'smarttoolz',
        'Page Settings',
        'Page Settings',
        'manage_options',
        'smarttoolz-video-pages',
        'smarttoolz_video_page_settings'
    );

    add_submenu_page(
        'smarttoolz',
        'Theme Setup',
        'Theme Setup',
        'manage_options',
        'smarttoolz-video-theme',
        'smarttoolz_video_theme_settings'
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

    $notice = '';
    if ( isset( $_POST['stv_sync_pages'], $_POST['stv_sync_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['stv_sync_nonce'] ) ), 'stv_sync_pages' ) ) {
        smarttoolz_video_sync_pages();
        $notice = '<div class="notice notice-success is-dismissible"><p>SmartToolz Video pages synced successfully.</p></div>';
    }

    if ( isset( $_POST['stv_set_home'], $_POST['stv_home_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['stv_home_nonce'] ) ), 'stv_set_home' ) ) {
        if ( smarttoolz_video_set_static_homepage() ) {
            $notice = '<div class="notice notice-success is-dismissible"><p>SmartToolz Home is now the static homepage.</p></div>';
        } else {
            $notice = '<div class="notice notice-error is-dismissible"><p>Could not set SmartToolz Home as the static homepage.</p></div>';
        }
    }

    $definitions = smarttoolz_video_page_definitions();
    $ids = (array) get_option( 'smarttoolz_video_page_ids', array() );
    $home_id = isset( $ids['home'] ) ? absint( $ids['home'] ) : 0;
    $is_static_home = 'page' === get_option( 'show_on_front', 'posts' ) && $home_id && absint( get_option( 'page_on_front', 0 ) ) === $home_id;
    ?>
    <div class="wrap">
        <h1>Page Settings</h1>
        <p>Manage and repair all SmartToolz pages. WordPress core files are not modified.</p>

        <?php echo $notice; ?>

        <div style="margin:16px 0;padding:16px 18px;background:#fff;border:1px solid #ccd0d4;max-width:900px;">
            <h2 style="margin-top:0;">Static Homepage</h2>
            <p style="margin-bottom:12px;">Set the managed <strong>Home</strong> page as the site's front page in one click.</p>
            <?php if ( $is_static_home ) : ?>
                <p><strong style="color:#008a20;">Active:</strong> SmartToolz Home is currently the static homepage.</p>
            <?php else : ?>
                <p><strong>Current:</strong> <?php echo esc_html( 'page' === get_option( 'show_on_front', 'posts' ) ? 'Another static page' : 'Latest posts' ); ?></p>
            <?php endif; ?>
            <form method="post">
                <?php wp_nonce_field( 'stv_set_home', 'stv_home_nonce' ); ?>
                <input type="hidden" name="stv_set_home" value="1">
                <?php submit_button( $is_static_home ? 'Home Page Already Set' : 'Set SmartToolz Home as Homepage', 'primary', 'submit', false, $is_static_home ? array( 'disabled' => 'disabled' ) : array() ); ?>
            </form>
        </div>

        <form method="post">
            <?php wp_nonce_field( 'stv_sync_pages', 'stv_sync_nonce' ); ?>
            <input type="hidden" name="stv_sync_pages" value="1">
            <?php submit_button( 'Sync / Repair Pages', 'secondary', 'submit', false ); ?>
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

function smarttoolz_video_theme_settings() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $notice = '';
    if ( isset( $_POST['stv_install_theme'], $_POST['stv_theme_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['stv_theme_nonce'] ) ), 'stv_theme_action' ) ) {
        $result = smarttoolz_video_install_theme();
        $notice = is_wp_error( $result )
            ? '<div class="notice notice-error is-dismissible"><p>' . esc_html( $result->get_error_message() ) . '</p></div>'
            : '<div class="notice notice-success is-dismissible"><p>SmartToolz Video Theme installed successfully.</p></div>';
    }

    if ( isset( $_POST['stv_activate_theme'], $_POST['stv_theme_activate_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['stv_theme_activate_nonce'] ) ), 'stv_activate_theme' ) ) {
        $result = smarttoolz_video_activate_theme();
        $notice = is_wp_error( $result )
            ? '<div class="notice notice-error is-dismissible"><p>' . esc_html( $result->get_error_message() ) . '</p></div>'
            : '<div class="notice notice-success is-dismissible"><p>SmartToolz Video Theme activated successfully.</p></div>';
    }

    $installed = smarttoolz_video_theme_installed();
    $active = smarttoolz_video_theme_is_active();
    ?>
    <div class="wrap">
        <h1>Theme Setup</h1>
        <p>Install and activate the SmartToolz video-sharing theme without changing any WordPress core file.</p>
        <?php echo $notice; ?>
        <table class="widefat striped" style="max-width:1000px;margin-top:16px;">
            <tbody>
                <tr><th style="width:220px;">Theme</th><td><?php echo esc_html( smarttoolz_video_theme_name() ); ?></td></tr>
                <tr><th>Installation</th><td><?php echo $installed ? '<span style="color:#008a20;font-weight:600">Installed</span>' : '<span style="color:#b32d2e;font-weight:600">Not installed</span>'; ?></td></tr>
                <tr><th>Activation</th><td><?php echo $active ? '<span style="color:#008a20;font-weight:600">Active</span>' : '<span style="color:#b32d2e;font-weight:600">Not active</span>'; ?></td></tr>
            </tbody>
        </table>
        <div style="display:flex;gap:10px;margin-top:16px;">
            <form method="post">
                <?php wp_nonce_field( 'stv_theme_action', 'stv_theme_nonce' ); ?>
                <input type="hidden" name="stv_install_theme" value="1">
                <?php submit_button( $installed ? 'Reinstall Theme' : 'Install Theme', 'secondary', 'submit', false ); ?>
            </form>
            <form method="post">
                <?php wp_nonce_field( 'stv_activate_theme', 'stv_theme_activate_nonce' ); ?>
                <input type="hidden" name="stv_activate_theme" value="1">
                <?php submit_button( $active ? 'Theme Already Active' : 'Activate Theme', 'primary', 'submit', false, $active ? array( 'disabled' => 'disabled' ) : array() ); ?>
            </form>
        </div>
    </div>
    <?php
}

register_activation_hook( __FILE__, 'smarttoolz_video_activate_plugin' );
register_deactivation_hook( __FILE__, 'smarttoolz_video_deactivate_plugin' );
