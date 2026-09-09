<?php
/**
 * SmartToolz creator/video settings.
 * Uses uniquely-prefixed functions so it cannot collide with unrelated plugins.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_creator_video_settings() {
    $defaults = array(
        'uploads_enabled'      => 1,
        'require_login_upload' => 1,
        'default_status'       => 'publish',
        'max_upload_mb'        => 1024,
        'max_title_length'     => 180,
        'allow_user_edit'      => 1,
        'allow_user_delete'    => 1,
    );

    return wp_parse_args( (array) get_option( 'smarttoolz_creator_video_settings', array() ), $defaults );
}

function smarttoolz_creator_video_settings_sanitize( $input ) {
    $input = is_array( $input ) ? $input : array();
    $settings = smarttoolz_creator_video_settings();

    $settings['uploads_enabled']      = empty( $input['uploads_enabled'] ) ? 0 : 1;
    $settings['require_login_upload'] = empty( $input['require_login_upload'] ) ? 0 : 1;
    $settings['allow_user_edit']      = empty( $input['allow_user_edit'] ) ? 0 : 1;
    $settings['allow_user_delete']    = empty( $input['allow_user_delete'] ) ? 0 : 1;

    $status = isset( $input['default_status'] ) ? sanitize_key( $input['default_status'] ) : 'publish';
    $settings['default_status'] = in_array( $status, array( 'publish', 'pending', 'draft' ), true ) ? $status : 'publish';
    $settings['max_upload_mb'] = max( 1, min( 10240, absint( $input['max_upload_mb'] ?? 1024 ) ) );
    $settings['max_title_length'] = max( 20, min( 300, absint( $input['max_title_length'] ?? 180 ) ) );

    return $settings;
}

function smarttoolz_creator_video_settings_register() {
    register_setting(
        'smarttoolz_creator_video_settings_group',
        'smarttoolz_creator_video_settings',
        array( 'sanitize_callback' => 'smarttoolz_creator_video_settings_sanitize' )
    );
}
add_action( 'admin_init', 'smarttoolz_creator_video_settings_register' );

function smarttoolz_creator_video_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) { return; }
    $settings = smarttoolz_creator_video_settings();
    ?>
    <div class="wrap">
        <h1>Video Settings</h1>
        <p>Control creator video uploads, publishing and ownership permissions.</p>
        <form method="post" action="options.php">
            <?php settings_fields( 'smarttoolz_creator_video_settings_group' ); ?>
            <table class="form-table" role="presentation">
                <tr><th scope="row">Video uploads</th><td><label><input type="checkbox" name="smarttoolz_creator_video_settings[uploads_enabled]" value="1" <?php checked( $settings['uploads_enabled'], 1 ); ?>> Allow users to upload videos</label></td></tr>
                <tr><th scope="row">Login required</th><td><label><input type="checkbox" name="smarttoolz_creator_video_settings[require_login_upload]" value="1" <?php checked( $settings['require_login_upload'], 1 ); ?>> Require login before upload</label></td></tr>
                <tr><th scope="row">Default publishing status</th><td><select name="smarttoolz_creator_video_settings[default_status]"><option value="publish" <?php selected( $settings['default_status'], 'publish' ); ?>>Published</option><option value="pending" <?php selected( $settings['default_status'], 'pending' ); ?>>Pending review</option><option value="draft" <?php selected( $settings['default_status'], 'draft' ); ?>>Draft</option></select></td></tr>
                <tr><th scope="row">Maximum upload size</th><td><input type="number" min="1" max="10240" name="smarttoolz_creator_video_settings[max_upload_mb]" value="<?php echo esc_attr( $settings['max_upload_mb'] ); ?>"> MB</td></tr>
                <tr><th scope="row">Maximum title length</th><td><input type="number" min="20" max="300" name="smarttoolz_creator_video_settings[max_title_length]" value="<?php echo esc_attr( $settings['max_title_length'] ); ?>"> characters</td></tr>
                <tr><th scope="row">Creator permissions</th><td><label><input type="checkbox" name="smarttoolz_creator_video_settings[allow_user_edit]" value="1" <?php checked( $settings['allow_user_edit'], 1 ); ?>> Users can edit their own videos</label><br><label><input type="checkbox" name="smarttoolz_creator_video_settings[allow_user_delete]" value="1" <?php checked( $settings['allow_user_delete'], 1 ); ?>> Users can delete their own videos</label></td></tr>
            </table>
            <?php submit_button( 'Save Video Settings' ); ?>
        </form>
    </div>
    <?php
}

function smarttoolz_creator_video_settings_admin_menu() {
    add_submenu_page(
        'smarttoolz',
        'Video Settings',
        'Video Settings',
        'manage_options',
        'smarttoolz-video-settings',
        'smarttoolz_creator_video_settings_page'
    );
}
add_action( 'admin_menu', 'smarttoolz_creator_video_settings_admin_menu', 20 );

// Load public channel UI and creator channel settings after the core creator settings module.
require_once SMARTTOOLZ_VIDEO_DIR . 'includes/channel.php';
