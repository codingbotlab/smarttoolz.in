<?php
/**
 * SmartToolz video platform admin settings.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_get_settings() {
    $defaults = array(
        'uploads_enabled'      => 1,
        'default_status'       => 'publish',
        'max_upload_mb'        => 1024,
        'allowed_mimes'        => 'mp4,webm,ogv,mov,avi,mkv',
        'max_title_length'     => 180,
        'allow_user_delete'    => 1,
        'allow_user_edit'      => 1,
        'require_login_upload' => 1,
    );
    return wp_parse_args( (array) get_option( 'smarttoolz_video_settings', array() ), $defaults );
}

function smarttoolz_video_register_settings() {
    register_setting( 'smarttoolz_video_settings_group', 'smarttoolz_video_settings', array( 'sanitize_callback' => 'smarttoolz_video_sanitize_settings' ) );
}
add_action( 'admin_init', 'smarttoolz_video_register_settings' );

function smarttoolz_video_sanitize_settings( $input ) {
    $settings = smarttoolz_video_get_settings();
    $settings['uploads_enabled']      = empty( $input['uploads_enabled'] ) ? 0 : 1;
    $settings['require_login_upload'] = empty( $input['require_login_upload'] ) ? 0 : 1;
    $settings['allow_user_delete']    = empty( $input['allow_user_delete'] ) ? 0 : 1;
    $settings['allow_user_edit']      = empty( $input['allow_user_edit'] ) ? 0 : 1;
    $status = isset( $input['default_status'] ) ? sanitize_key( $input['default_status'] ) : 'publish';
    $settings['default_status'] = in_array( $status, array( 'publish', 'pending', 'draft' ), true ) ? $status : 'publish';
    $settings['max_upload_mb'] = max( 1, min( 10240, absint( $input['max_upload_mb'] ?? 1024 ) ) );
    $settings['max_title_length'] = max( 20, min( 300, absint( $input['max_title_length'] ?? 180 ) ) );
    $settings['allowed_mimes'] = preg_replace( '/[^a-z0-9,]/i', '', (string) ( $input['allowed_mimes'] ?? $settings['allowed_mimes'] ) );
    return $settings;
}

function smarttoolz_video_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) { return; }
    $settings = smarttoolz_video_get_settings();
    ?>
    <div class="wrap">
        <h1>Video Settings</h1>
        <p>Control how users upload and manage videos on SmartToolz.</p>
        <form method="post" action="options.php">
            <?php settings_fields( 'smarttoolz_video_settings_group' ); ?>
            <table class="form-table" role="presentation">
                <tr><th scope="row">Video uploads</th><td><label><input type="checkbox" name="smarttoolz_video_settings[uploads_enabled]" value="1" <?php checked( $settings['uploads_enabled'], 1 ); ?>> Allow video uploads</label></td></tr>
                <tr><th scope="row">Login required</th><td><label><input type="checkbox" name="smarttoolz_video_settings[require_login_upload]" value="1" <?php checked( $settings['require_login_upload'], 1 ); ?>> Require users to be logged in to upload</label></td></tr>
                <tr><th scope="row">Default publishing status</th><td><select name="smarttoolz_video_settings[default_status]"><option value="publish" <?php selected( $settings['default_status'], 'publish' ); ?>>Published</option><option value="pending" <?php selected( $settings['default_status'], 'pending' ); ?>>Pending review</option><option value="draft" <?php selected( $settings['default_status'], 'draft' ); ?>>Draft</option></select></td></tr>
                <tr><th scope="row">Maximum upload size</th><td><input type="number" min="1" max="10240" name="smarttoolz_video_settings[max_upload_mb]" value="<?php echo esc_attr( $settings['max_upload_mb'] ); ?>"> MB</td></tr>
                <tr><th scope="row">Allowed video types</th><td><input type="text" class="regular-text" name="smarttoolz_video_settings[allowed_mimes]" value="<?php echo esc_attr( $settings['allowed_mimes'] ); ?>"><p class="description">Comma-separated extensions.</p></td></tr>
                <tr><th scope="row">Maximum title length</th><td><input type="number" min="20" max="300" name="smarttoolz_video_settings[max_title_length]" value="<?php echo esc_attr( $settings['max_title_length'] ); ?>"> characters</td></tr>
                <tr><th scope="row">Creator permissions</th><td><label><input type="checkbox" name="smarttoolz_video_settings[allow_user_edit]" value="1" <?php checked( $settings['allow_user_edit'], 1 ); ?>> Users can edit their own videos</label><br><label><input type="checkbox" name="smarttoolz_video_settings[allow_user_delete]" value="1" <?php checked( $settings['allow_user_delete'], 1 ); ?>> Users can delete their own videos</label></td></tr>
            </table>
            <?php submit_button( 'Save Video Settings' ); ?>
        </form>
    </div>
    <?php
}
