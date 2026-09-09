<?php
/**
 * SmartToolz Video authentication.
 * Server-side Google OAuth 2.0 login for WordPress users.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_auth_defaults() {
    return array(
        'google_enabled'    => 0,
        'google_client_id'  => '',
        'google_client_secret' => '',
    );
}

function smarttoolz_video_auth_settings() {
    return wp_parse_args( (array) get_option( 'smarttoolz_video_auth_settings', array() ), smarttoolz_video_auth_defaults() );
}

function smarttoolz_video_auth_sanitize_settings( $input ) {
    $input = is_array( $input ) ? $input : array();
    $old = smarttoolz_video_auth_settings();
    return array(
        'google_enabled' => empty( $input['google_enabled'] ) ? 0 : 1,
        'google_client_id' => sanitize_text_field( $input['google_client_id'] ?? '' ),
        'google_client_secret' => isset( $input['google_client_secret'] ) && '' !== trim( $input['google_client_secret'] )
            ? sanitize_text_field( $input['google_client_secret'] )
            : $old['google_client_secret'],
    );
}

function smarttoolz_video_auth_register_settings() {
    register_setting( 'smarttoolz_video_auth_settings_group', 'smarttoolz_video_auth_settings', array( 'sanitize_callback' => 'smarttoolz_video_auth_sanitize_settings' ) );
}
add_action( 'admin_init', 'smarttoolz_video_auth_register_settings' );

function smarttoolz_video_auth_admin_menu() {
    add_submenu_page( 'smarttoolz', 'Google Login', 'Google Login', 'manage_options', 'smarttoolz-google-auth', 'smarttoolz_video_auth_settings_page' );
}
add_action( 'admin_menu', 'smarttoolz_video_auth_admin_menu', 25 );

function smarttoolz_video_auth_redirect_uri() {
    return trailingslashit( home_url( '/video/login/' ) ) . '?stv_google_callback=1';
}

function smarttoolz_video_google_ready() {
    $s = smarttoolz_video_auth_settings();
    return ! empty( $s['google_enabled'] ) && '' !== trim( $s['google_client_id'] ) && '' !== trim( $s['google_client_secret'] );
}

function smarttoolz_video_google_login_url( $redirect_to = '' ) {
    $s = smarttoolz_video_auth_settings();
    if ( ! smarttoolz_video_google_ready() ) { return ''; }

    $state = wp_generate_password( 40, false, false );
    set_transient( 'smarttoolz_google_state_' . $state, array(
        'redirect_to' => $redirect_to ? esc_url_raw( $redirect_to ) : home_url( '/video/' ),
        'created' => time(),
    ), 10 * MINUTE_IN_SECONDS );

    return add_query_arg( array(
        'client_id' => $s['google_client_id'],
        'redirect_uri' => smarttoolz_video_auth_redirect_uri(),
        'response_type' => 'code',
        'scope' => 'openid email profile',
        'access_type' => 'online',
        'include_granted_scopes' => 'true',
        'state' => $state,
        'prompt' => 'select_account',
    ), 'https://accounts.google.com/o/oauth2/v2/auth' );
}

function smarttoolz_video_google_callback() {
    if ( ! isset( $_GET['stv_google_callback'] ) ) { return; }

    $error = isset( $_GET['error'] ) ? sanitize_key( wp_unslash( $_GET['error'] ) ) : '';
    if ( $error ) {
        wp_safe_redirect( add_query_arg( 'stv_auth_error', 'google_denied', home_url( '/video/login/' ) ) );
        exit;
    }

    $state = isset( $_GET['state'] ) ? sanitize_text_field( wp_unslash( $_GET['state'] ) ) : '';
    $state_data = $state ? get_transient( 'smarttoolz_google_state_' . $state ) : false;
    if ( ! $state_data || ! is_array( $state_data ) ) {
        wp_safe_redirect( add_query_arg( 'stv_auth_error', 'invalid_state', home_url( '/video/login/' ) ) );
        exit;
    }
    delete_transient( 'smarttoolz_google_state_' . $state );

    $code = isset( $_GET['code'] ) ? sanitize_text_field( wp_unslash( $_GET['code'] ) ) : '';
    if ( ! $code || ! smarttoolz_video_google_ready() ) {
        wp_safe_redirect( add_query_arg( 'stv_auth_error', 'google_not_configured', home_url( '/video/login/' ) ) );
        exit;
    }

    $s = smarttoolz_video_auth_settings();
    $token_response = wp_remote_post( 'https://oauth2.googleapis.com/token', array(
        'timeout' => 15,
        'body' => array(
            'code' => $code,
            'client_id' => $s['google_client_id'],
            'client_secret' => $s['google_client_secret'],
            'redirect_uri' => smarttoolz_video_auth_redirect_uri(),
            'grant_type' => 'authorization_code',
        ),
    ) );
    if ( is_wp_error( $token_response ) ) {
        wp_safe_redirect( add_query_arg( 'stv_auth_error', 'token_request', home_url( '/video/login/' ) ) );
        exit;
    }

    $token = json_decode( wp_remote_retrieve_body( $token_response ), true );
    $access_token = isset( $token['access_token'] ) ? sanitize_text_field( $token['access_token'] ) : '';
    if ( ! $access_token ) {
        wp_safe_redirect( add_query_arg( 'stv_auth_error', 'token_invalid', home_url( '/video/login/' ) ) );
        exit;
    }

    $profile_response = wp_remote_get( 'https://www.googleapis.com/oauth2/v3/userinfo', array(
        'timeout' => 15,
        'headers' => array( 'Authorization' => 'Bearer ' . $access_token ),
    ) );
    if ( is_wp_error( $profile_response ) ) {
        wp_safe_redirect( add_query_arg( 'stv_auth_error', 'profile_request', home_url( '/video/login/' ) ) );
        exit;
    }

    $profile = json_decode( wp_remote_retrieve_body( $profile_response ), true );
    $google_id = isset( $profile['sub'] ) ? sanitize_text_field( $profile['sub'] ) : '';
    $email = isset( $profile['email'] ) ? sanitize_email( $profile['email'] ) : '';
    $email_verified = ! empty( $profile['email_verified'] );
    if ( ! $google_id || ! is_email( $email ) || ! $email_verified ) {
        wp_safe_redirect( add_query_arg( 'stv_auth_error', 'email_not_verified', home_url( '/video/login/' ) ) );
        exit;
    }

    $user_id = smarttoolz_video_find_google_user( $google_id, $email );
    if ( ! $user_id ) {
        $display_name = isset( $profile['name'] ) ? sanitize_text_field( $profile['name'] ) : '';
        if ( '' === $display_name ) { $display_name = sanitize_user( current( explode( '@', $email ) ), true ); }
        $username = smarttoolz_video_unique_username( $display_name, $email );
        $user_id = wp_insert_user( array(
            'user_login' => $username,
            'user_pass' => wp_generate_password( 32, true, true ),
            'user_email' => $email,
            'display_name' => $display_name ?: $username,
            'first_name' => isset( $profile['given_name'] ) ? sanitize_text_field( $profile['given_name'] ) : '',
            'last_name' => isset( $profile['family_name'] ) ? sanitize_text_field( $profile['family_name'] ) : '',
            'role' => get_option( 'default_role', 'subscriber' ),
        ) );
        if ( is_wp_error( $user_id ) ) {
            wp_safe_redirect( add_query_arg( 'stv_auth_error', 'account_create', home_url( '/video/login/' ) ) );
            exit;
        }
    }

    update_user_meta( $user_id, '_smarttoolz_google_id', $google_id );
    if ( ! empty( $profile['picture'] ) ) { update_user_meta( $user_id, 'smarttoolz_avatar_url', esc_url_raw( $profile['picture'] ) ); }
    wp_set_auth_cookie( $user_id, true );
    wp_set_current_user( $user_id );

    $redirect_to = ! empty( $state_data['redirect_to'] ) ? $state_data['redirect_to'] : home_url( '/video/' );
    $redirect_to = wp_validate_redirect( $redirect_to, home_url( '/video/' ) );
    wp_safe_redirect( $redirect_to );
    exit;
}
add_action( 'init', 'smarttoolz_video_google_callback', 1 );

function smarttoolz_video_find_google_user( $google_id, $email ) {
    $users = get_users( array(
        'meta_key' => '_smarttoolz_google_id',
        'meta_value' => $google_id,
        'number' => 1,
        'fields' => 'ids',
    ) );
    if ( ! empty( $users ) ) { return absint( $users[0] ); }

    $user = get_user_by( 'email', $email );
    return $user ? absint( $user->ID ) : 0;
}

function smarttoolz_video_unique_username( $display_name, $email ) {
    $base = sanitize_user( $display_name, true );
    if ( '' === $base ) { $base = sanitize_user( current( explode( '@', $email ) ), true ); }
    if ( '' === $base ) { $base = 'creator'; }
    $base = substr( $base, 0, 50 );
    $username = $base;
    $i = 2;
    while ( username_exists( $username ) ) { $username = $base . $i; $i++; }
    return $username;
}

function smarttoolz_video_auth_error_message() {
    $error = isset( $_GET['stv_auth_error'] ) ? sanitize_key( wp_unslash( $_GET['stv_auth_error'] ) ) : '';
    $messages = array(
        'google_denied' => 'Google sign-in was cancelled.',
        'invalid_state' => 'The Google sign-in session expired. Please try again.',
        'google_not_configured' => 'Google sign-in is not configured yet.',
        'token_request' => 'Could not contact Google securely. Please try again.',
        'token_invalid' => 'Google did not return a valid sign-in token.',
        'profile_request' => 'Could not retrieve your Google profile. Please try again.',
        'email_not_verified' => 'Google must provide a verified email address.',
        'account_create' => 'Your SmartToolz account could not be created.',
    );
    return isset( $messages[ $error ] ) ? $messages[ $error ] : '';
}

function smarttoolz_video_render_login() {
    if ( is_user_logged_in() ) {
        wp_safe_redirect( home_url( '/video/' ) );
        exit;
    }
    $google_url = smarttoolz_video_google_login_url( wp_get_referer() ?: home_url( '/video/' ) );
    $error = smarttoolz_video_auth_error_message();
    ?>
    <section class="stv-auth">
        <div class="stv-auth__card">
            <span class="stv-auth__mark">▶</span>
            <h1>Sign in to SmartToolz</h1>
            <p>Sign in to create your channel, upload videos and manage your personal library.</p>
            <?php if ( $error ) : ?><div class="stv-notice stv-notice--error"><?php echo esc_html( $error ); ?></div><?php endif; ?>
            <?php if ( $google_url ) : ?>
                <a class="stv-google-button" href="<?php echo esc_url( $google_url ); ?>"><span class="stv-google-button__g">G</span><span>Continue with Google</span></a>
            <?php else : ?>
                <div class="stv-notice stv-notice--error">Google sign-in is currently unavailable. Please contact the site administrator.</div>
            <?php endif; ?>
            <div class="stv-auth__footer">By continuing, you agree to use SmartToolz responsibly.</div>
        </div>
    </section>
    <?php
}

function smarttoolz_video_auth_styles() {
    $route = get_query_var( 'smarttoolz_video_route' );
    if ( 'login' !== $route && 'signup' !== $route ) { return; }
    echo '<style>
    .stv-auth{max-width:520px;margin:40px auto}.stv-auth__card{background:#181818;border:1px solid #303030;border-radius:18px;padding:32px;box-shadow:0 12px 35px rgba(0,0,0,.25)}.stv-auth__mark{display:grid;place-items:center;width:48px;height:34px;border-radius:9px;background:#ff0033;color:#fff;font-weight:900;margin-bottom:20px}.stv-auth__card h1{margin:0 0 8px;font-size:28px}.stv-auth__card>p{color:#aaa;font-size:14px;margin:0 0 22px}.stv-google-button{display:flex;align-items:center;justify-content:center;gap:11px;width:100%;padding:13px 18px;border:1px solid #444;border-radius:9px;background:#fff;color:#111;font-weight:700;font-size:14px}.stv-google-button:hover{background:#eee}.stv-google-button__g{font-weight:900;font-size:18px}.stv-auth__footer{margin-top:20px;color:#777;font-size:11px;text-align:center}.stv-auth .stv-notice{margin-bottom:14px}
    </style>';
}
add_action( 'wp_head', 'smarttoolz_video_auth_styles', 31 );

function smarttoolz_video_auth_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) { return; }
    $s = smarttoolz_video_auth_settings();
    ?>
    <div class="wrap">
        <h1>Google Login</h1>
        <p>Enable server-side Google OAuth so each Google account gets its own WordPress/SmartToolz user and creator identity.</p>
        <form method="post" action="options.php">
            <?php settings_fields( 'smarttoolz_video_auth_settings_group' ); ?>
            <table class="form-table" role="presentation">
                <tr><th scope="row">Enable Google Login</th><td><label><input type="checkbox" name="smarttoolz_video_auth_settings[google_enabled]" value="1" <?php checked( $s['google_enabled'], 1 ); ?>> Allow users to sign in with Google</label></td></tr>
                <tr><th scope="row">Google Client ID</th><td><input type="text" class="regular-text" name="smarttoolz_video_auth_settings[google_client_id]" value="<?php echo esc_attr( $s['google_client_id'] ); ?>" autocomplete="off"></td></tr>
                <tr><th scope="row">Google Client Secret</th><td><input type="password" class="regular-text" name="smarttoolz_video_auth_settings[google_client_secret]" value="" autocomplete="new-password"><p class="description">Leave blank to keep the existing secret.</p></td></tr>
                <tr><th scope="row">Authorized redirect URI</th><td><code><?php echo esc_html( smarttoolz_video_auth_redirect_uri() ); ?></code><p class="description">Add this exact URI to your Google OAuth Web application credentials.</p></td></tr>
            </table>
            <?php submit_button( 'Save Google Login Settings' ); ?>
        </form>
        <hr>
        <h2>Status</h2>
        <p><strong><?php echo smarttoolz_video_google_ready() ? 'Configured and ready' : 'Not configured'; ?></strong></p>
    </div>
    <?php
}
