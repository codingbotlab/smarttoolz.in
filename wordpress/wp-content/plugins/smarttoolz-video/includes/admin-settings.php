<?php
/**
 * SmartToolz Video platform-wide account/access settings.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_platform_settings_defaults() {
    return array(
        'custom_login_enabled'        => 1,
        'custom_registration_enabled' => 1,
        'google_login_enabled'        => 1,
        'default_role'                => 'subscriber',
        'minimum_password_length'     => 8,
        'allow_username_login'        => 1,
        'allow_email_login'           => 1,
        'show_admin_bar_for_users'    => 0,
    );
}

function smarttoolz_video_platform_settings() {
    return wp_parse_args( (array) get_option( 'smarttoolz_video_platform_settings', array() ), smarttoolz_video_platform_settings_defaults() );
}

function smarttoolz_video_platform_settings_sanitize( $input ) {
    $input = is_array( $input ) ? $input : array();
    $defaults = smarttoolz_video_platform_settings_defaults();
    $out = array();
    $out['custom_login_enabled'] = empty( $input['custom_login_enabled'] ) ? 0 : 1;
    $out['custom_registration_enabled'] = empty( $input['custom_registration_enabled'] ) ? 0 : 1;
    $out['google_login_enabled'] = empty( $input['google_login_enabled'] ) ? 0 : 1;
    $out['allow_username_login'] = empty( $input['allow_username_login'] ) ? 0 : 1;
    $out['allow_email_login'] = empty( $input['allow_email_login'] ) ? 0 : 1;
    $out['show_admin_bar_for_users'] = empty( $input['show_admin_bar_for_users'] ) ? 0 : 1;
    $role = isset( $input['default_role'] ) ? sanitize_key( $input['default_role'] ) : $defaults['default_role'];
    $roles = wp_roles()->roles;
    $out['default_role'] = isset( $roles[ $role ] ) && 'administrator' !== $role ? $role : $defaults['default_role'];
    $out['minimum_password_length'] = max( 6, min( 64, absint( isset( $input['minimum_password_length'] ) ? $input['minimum_password_length'] : $defaults['minimum_password_length'] ) ) );
    return $out;
}

function smarttoolz_video_register_platform_settings() {
    register_setting( 'smarttoolz_video_platform_settings_group', 'smarttoolz_video_platform_settings', array( 'sanitize_callback' => 'smarttoolz_video_platform_settings_sanitize' ) );
}
add_action( 'admin_init', 'smarttoolz_video_register_platform_settings' );

function smarttoolz_video_platform_settings_menu() {
    add_submenu_page( 'smarttoolz', 'Account & Access', 'Account & Access', 'manage_options', 'smarttoolz-account-access', 'smarttoolz_video_platform_settings_page' );
}
add_action( 'admin_menu', 'smarttoolz_video_platform_settings_menu', 30 );

function smarttoolz_video_platform_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) { return; }
    $s = smarttoolz_video_platform_settings();
    $roles = wp_roles()->roles;
    ?>
    <div class="wrap">
        <h1>Account &amp; Access</h1>
        <p>Control SmartToolz custom login, registration and account access. WordPress's native <code>/wp-login.php</code> remains untouched.</p>
        <form method="post" action="options.php">
            <?php settings_fields( 'smarttoolz_video_platform_settings_group' ); ?>
            <h2>Authentication</h2>
            <table class="form-table" role="presentation">
                <tr><th scope="row">Custom SmartToolz login</th><td><label><input type="checkbox" name="smarttoolz_video_platform_settings[custom_login_enabled]" value="1" <?php checked( $s['custom_login_enabled'], 1 ); ?>> Enable <code>/video/login/</code></label><p class="description">This controls the SmartToolz login page only.</p></td></tr>
                <tr><th scope="row">Custom registration</th><td><label><input type="checkbox" name="smarttoolz_video_platform_settings[custom_registration_enabled]" value="1" <?php checked( $s['custom_registration_enabled'], 1 ); ?>> Enable <code>/video/signup/</code></label></td></tr>
                <tr><th scope="row">Google sign-in</th><td><label><input type="checkbox" name="smarttoolz_video_platform_settings[google_login_enabled]" value="1" <?php checked( $s['google_login_enabled'], 1 ); ?>> Show Google sign-in on SmartToolz login/register</label><p class="description">Google OAuth credentials are configured separately under Google Login.</p></td></tr>
                <tr><th scope="row">Username login</th><td><label><input type="checkbox" name="smarttoolz_video_platform_settings[allow_username_login]" value="1" <?php checked( $s['allow_username_login'], 1 ); ?>> Allow username</label></td></tr>
                <tr><th scope="row">Email login</th><td><label><input type="checkbox" name="smarttoolz_video_platform_settings[allow_email_login]" value="1" <?php checked( $s['allow_email_login'], 1 ); ?>> Allow email address</label></td></tr>
                <tr><th scope="row">Minimum password length</th><td><input type="number" min="6" max="64" name="smarttoolz_video_platform_settings[minimum_password_length]" value="<?php echo esc_attr( $s['minimum_password_length'] ); ?>"> characters</td></tr>
                <tr><th scope="row">Default new-user role</th><td><select name="smarttoolz_video_platform_settings[default_role]"><?php foreach ( $roles as $role_key => $role_data ) : if ( 'administrator' === $role_key ) { continue; } ?><option value="<?php echo esc_attr( $role_key ); ?>" <?php selected( $s['default_role'], $role_key ); ?>><?php echo esc_html( translate_user_role( $role_data['name'] ) ); ?></option><?php endforeach; ?></select><p class="description">Administrator cannot be selected here.</p></td></tr>
                <tr><th scope="row">WordPress admin bar</th><td><label><input type="checkbox" name="smarttoolz_video_platform_settings[show_admin_bar_for_users]" value="1" <?php checked( $s['show_admin_bar_for_users'], 1 ); ?>> Show the native WordPress admin bar to non-administrators</label><p class="description">Recommended: disabled for normal SmartToolz users.</p></td></tr>
            </table>
            <?php submit_button( 'Save Account & Access Settings' ); ?>
        </form>
    </div>
    <?php
}

function smarttoolz_video_platform_hide_admin_bar_for_users() {
    $s = smarttoolz_video_platform_settings();
    if ( ! empty( $s['show_admin_bar_for_users'] ) || current_user_can( 'manage_options' ) ) { return; }
    show_admin_bar( false );
}
add_action( 'init', 'smarttoolz_video_platform_hide_admin_bar_for_users', 20 );

function smarttoolz_video_platform_auth_gate() {
    $route = get_query_var( 'smarttoolz_video_route', '' );
    $s = smarttoolz_video_platform_settings();
    if ( 'login' === $route && empty( $s['custom_login_enabled'] ) ) {
        wp_safe_redirect( home_url( '/' ) );
        exit;
    }
    if ( 'signup' === $route && empty( $s['custom_registration_enabled'] ) ) {
        wp_safe_redirect( home_url( '/' ) );
        exit;
    }
}
add_action( 'template_redirect', 'smarttoolz_video_platform_auth_gate', 1 );

function smarttoolz_video_platform_auth_form_gate() {
    if ( ! isset( $_POST['stv_auth_action'] ) ) { return; }
    $action = sanitize_key( wp_unslash( $_POST['stv_auth_action'] ) );
    $route = get_query_var( 'smarttoolz_video_route', '' );
    $s = smarttoolz_video_platform_settings();
    if ( 'login' === $action && 'login' === $route ) {
        $identifier = isset( $_POST['stv_login_identifier'] ) ? sanitize_text_field( wp_unslash( $_POST['stv_login_identifier'] ) ) : '';
        if ( is_email( $identifier ) && empty( $s['allow_email_login'] ) ) {
            wp_safe_redirect( add_query_arg( 'stv_auth_error', 'email_login_disabled', smarttoolz_video_route_url( 'login' ) ) );
            exit;
        }
        if ( ! is_email( $identifier ) && empty( $s['allow_username_login'] ) ) {
            wp_safe_redirect( add_query_arg( 'stv_auth_error', 'username_login_disabled', smarttoolz_video_route_url( 'login' ) ) );
            exit;
        }
    }
    if ( 'register' === $action && 'signup' === $route ) {
        $password = isset( $_POST['stv_register_password'] ) ? (string) wp_unslash( $_POST['stv_register_password'] ) : '';
        if ( strlen( $password ) < absint( $s['minimum_password_length'] ) ) {
            wp_safe_redirect( add_query_arg( 'stv_auth_error', 'weak_password', smarttoolz_video_route_url( 'signup' ) ) );
            exit;
        }
    }
}
add_action( 'init', 'smarttoolz_video_platform_auth_form_gate', 1 );

function smarttoolz_video_platform_filter_default_role( $value ) {
    $s = smarttoolz_video_platform_settings();
    return ! empty( $s['default_role'] ) ? $s['default_role'] : $value;
}
add_filter( 'option_default_role', 'smarttoolz_video_platform_filter_default_role' );

function smarttoolz_video_platform_filter_google_auth_settings( $value ) {
    $value = is_array( $value ) ? $value : array();
    $s = smarttoolz_video_platform_settings();
    $value['google_enabled'] = empty( $s['google_login_enabled'] ) ? 0 : 1;
    return $value;
}
add_filter( 'option_smarttoolz_video_auth_settings', 'smarttoolz_video_platform_filter_google_auth_settings' );
