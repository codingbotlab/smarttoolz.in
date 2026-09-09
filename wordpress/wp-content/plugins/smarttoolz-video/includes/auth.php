<?php
/**
 * SmartToolz Video authentication.
 * Custom frontend auth pages; WordPress default login remains untouched.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_auth_site_name() {
    $name = trim( (string) get_bloginfo( 'name' ) );
    return '' !== $name ? $name : 'Video';
}

function smarttoolz_video_auth_defaults() {
    return array(
        'google_enabled' => 0,
        'google_client_id' => '',
        'google_client_secret' => '',
    );
}
function smarttoolz_video_auth_settings() { return wp_parse_args( (array) get_option( 'smarttoolz_video_auth_settings', array() ), smarttoolz_video_auth_defaults() ); }
function smarttoolz_video_auth_sanitize_settings( $input ) {
    $input = is_array( $input ) ? $input : array(); $old = smarttoolz_video_auth_settings();
    return array(
        'google_enabled' => empty( $input['google_enabled'] ) ? 0 : 1,
        'google_client_id' => isset( $input['google_client_id'] ) ? sanitize_text_field( $input['google_client_id'] ) : '',
        'google_client_secret' => isset( $input['google_client_secret'] ) && '' !== trim( $input['google_client_secret'] ) ? sanitize_text_field( $input['google_client_secret'] ) : $old['google_client_secret'],
    );
}
add_action( 'admin_init', 'smarttoolz_video_auth_register_settings' );
function smarttoolz_video_auth_register_settings() { register_setting( 'smarttoolz_video_auth_settings_group', 'smarttoolz_video_auth_settings', array( 'sanitize_callback' => 'smarttoolz_video_auth_sanitize_settings' ) ); }
add_action( 'admin_menu', 'smarttoolz_video_auth_admin_menu', 25 );
function smarttoolz_video_auth_admin_menu() { add_submenu_page( 'smarttoolz', 'Google Login', 'Google Login', 'manage_options', 'smarttoolz-google-auth', 'smarttoolz_video_auth_settings_page' ); }
function smarttoolz_video_auth_redirect_uri() { return trailingslashit( home_url( '/video/login/' ) ) . '?stv_google_callback=1'; }
function smarttoolz_video_google_ready() { $s = smarttoolz_video_auth_settings(); return ! empty( $s['google_enabled'] ) && '' !== trim( $s['google_client_id'] ) && '' !== trim( $s['google_client_secret'] ); }
function smarttoolz_video_google_login_url( $redirect_to ) {
    if ( ! smarttoolz_video_google_ready() ) { return ''; }
    $s = smarttoolz_video_auth_settings(); $state = wp_generate_password( 40, false, false );
    set_transient( 'smarttoolz_google_state_' . $state, array( 'redirect_to' => $redirect_to ? esc_url_raw( $redirect_to ) : smarttoolz_video_route_url( 'home' ), 'created' => time() ), 10 * MINUTE_IN_SECONDS );
    return add_query_arg( array( 'client_id' => $s['google_client_id'], 'redirect_uri' => smarttoolz_video_auth_redirect_uri(), 'response_type' => 'code', 'scope' => 'openid email profile', 'access_type' => 'online', 'include_granted_scopes' => 'true', 'state' => $state, 'prompt' => 'select_account' ), 'https://accounts.google.com/o/oauth2/v2/auth' );
}
function smarttoolz_video_auth_post_redirect( $route, $error ) { $url = smarttoolz_video_route_url( $route ); wp_safe_redirect( add_query_arg( 'stv_auth_error', $error, $url ) ); exit; }

function smarttoolz_video_process_auth_forms() {
    $route = get_query_var( 'smarttoolz_video_route', '' ); if ( 'login' !== $route && 'signup' !== $route ) { return; }
    if ( 'GET' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) && ! isset( $_POST['stv_auth_action'] ) ) { return; }
    if ( ! isset( $_POST['stv_auth_action'], $_POST['stv_auth_nonce'] ) ) { return; }
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['stv_auth_nonce'] ) ), 'stv_auth_form' ) ) { smarttoolz_video_auth_post_redirect( $route, 'invalid_form' ); }
    $action = sanitize_key( wp_unslash( $_POST['stv_auth_action'] ) );
    if ( 'login' === $action && 'login' === $route ) {
        $identifier = isset( $_POST['stv_login_identifier'] ) ? sanitize_text_field( wp_unslash( $_POST['stv_login_identifier'] ) ) : '';
        $password = isset( $_POST['stv_login_password'] ) ? (string) wp_unslash( $_POST['stv_login_password'] ) : '';
        $remember = ! empty( $_POST['stv_login_remember'] );
        if ( '' === $identifier || '' === $password ) { smarttoolz_video_auth_post_redirect( 'login', 'missing_login' ); }
        $user = is_email( $identifier ) ? get_user_by( 'email', $identifier ) : get_user_by( 'login', $identifier );
        if ( ! $user ) { smarttoolz_video_auth_post_redirect( 'login', 'invalid_login' ); }
        $signed = wp_signon( array( 'user_login' => $user->user_login, 'user_password' => $password, 'remember' => $remember ), is_ssl() );
        if ( is_wp_error( $signed ) ) { smarttoolz_video_auth_post_redirect( 'login', 'invalid_login' ); }
        wp_set_current_user( $signed->ID ); wp_set_auth_cookie( $signed->ID, $remember );
        $redirect = isset( $_POST['stv_redirect_to'] ) ? wp_validate_redirect( esc_url_raw( wp_unslash( $_POST['stv_redirect_to'] ) ), smarttoolz_video_route_url( 'home' ) ) : smarttoolz_video_route_url( 'home' );
        wp_safe_redirect( $redirect ); exit;
    }
    if ( 'register' === $action && 'signup' === $route ) {
        $name = isset( $_POST['stv_register_name'] ) ? sanitize_text_field( wp_unslash( $_POST['stv_register_name'] ) ) : '';
        $username = isset( $_POST['stv_register_username'] ) ? sanitize_user( wp_unslash( $_POST['stv_register_username'] ), true ) : '';
        $email = isset( $_POST['stv_register_email'] ) ? sanitize_email( wp_unslash( $_POST['stv_register_email'] ) ) : '';
        $password = isset( $_POST['stv_register_password'] ) ? (string) wp_unslash( $_POST['stv_register_password'] ) : '';
        $confirm = isset( $_POST['stv_register_confirm'] ) ? (string) wp_unslash( $_POST['stv_register_confirm'] ) : '';
        if ( '' === $name || '' === $username || '' === $email || '' === $password || '' === $confirm ) { smarttoolz_video_auth_post_redirect( 'signup', 'missing_register' ); }
        if ( strlen( $username ) < 3 || ! preg_match( '/^[A-Za-z0-9_\-\.]+$/', $username ) ) { smarttoolz_video_auth_post_redirect( 'signup', 'invalid_username' ); }
        if ( username_exists( $username ) ) { smarttoolz_video_auth_post_redirect( 'signup', 'username_taken' ); }
        if ( email_exists( $email ) ) { smarttoolz_video_auth_post_redirect( 'signup', 'email_taken' ); }
        if ( ! is_email( $email ) ) { smarttoolz_video_auth_post_redirect( 'signup', 'invalid_email' ); }
        $platform = smarttoolz_video_platform_settings();
        $minimum = isset( $platform['minimum_password_length'] ) ? absint( $platform['minimum_password_length'] ) : 8;
        if ( strlen( $password ) < $minimum ) { smarttoolz_video_auth_post_redirect( 'signup', 'weak_password' ); }
        if ( $password !== $confirm ) { smarttoolz_video_auth_post_redirect( 'signup', 'password_mismatch' ); }
        $parts = preg_split( '/\s+/', trim( $name ) ); $first = isset( $parts[0] ) ? $parts[0] : ''; $last = count( $parts ) > 1 ? implode( ' ', array_slice( $parts, 1 ) ) : '';
        $user_id = wp_insert_user( array( 'user_login' => $username, 'user_pass' => $password, 'user_email' => $email, 'display_name' => $name, 'first_name' => $first, 'last_name' => $last, 'role' => get_option( 'default_role', 'subscriber' ) ) );
        if ( is_wp_error( $user_id ) ) { smarttoolz_video_auth_post_redirect( 'signup', 'account_create' ); }
        update_user_meta( $user_id, 'smarttoolz_channel_handle', $username );
        update_user_meta( $user_id, 'smarttoolz_channel_name', $name );
        wp_set_current_user( $user_id ); wp_set_auth_cookie( $user_id, true );
        wp_safe_redirect( smarttoolz_video_route_url( 'channel', $username ) ); exit;
    }
}
add_action( 'init', 'smarttoolz_video_process_auth_forms', 2 );

function smarttoolz_video_google_callback() {
    if ( ! isset( $_GET['stv_google_callback'] ) ) { return; }
    $error = isset( $_GET['error'] ) ? sanitize_key( wp_unslash( $_GET['error'] ) ) : '';
    if ( $error ) { smarttoolz_video_auth_post_redirect( 'login', 'google_denied' ); }
    $state = isset( $_GET['state'] ) ? sanitize_text_field( wp_unslash( $_GET['state'] ) ) : '';
    $state_data = $state ? get_transient( 'smarttoolz_google_state_' . $state ) : false;
    if ( ! $state_data || ! is_array( $state_data ) ) { smarttoolz_video_auth_post_redirect( 'login', 'invalid_state' ); }
    delete_transient( 'smarttoolz_google_state_' . $state );
    $code = isset( $_GET['code'] ) ? sanitize_text_field( wp_unslash( $_GET['code'] ) ) : '';
    if ( ! $code || ! smarttoolz_video_google_ready() ) { smarttoolz_video_auth_post_redirect( 'login', 'google_not_configured' ); }
    $s = smarttoolz_video_auth_settings();
    $token_response = wp_remote_post( 'https://oauth2.googleapis.com/token', array( 'timeout' => 15, 'body' => array( 'code' => $code, 'client_id' => $s['google_client_id'], 'client_secret' => $s['google_client_secret'], 'redirect_uri' => smarttoolz_video_auth_redirect_uri(), 'grant_type' => 'authorization_code' ) ) );
    if ( is_wp_error( $token_response ) ) { smarttoolz_video_auth_post_redirect( 'login', 'token_request' ); }
    $token = json_decode( wp_remote_retrieve_body( $token_response ), true ); $access_token = isset( $token['access_token'] ) ? sanitize_text_field( $token['access_token'] ) : '';
    if ( ! $access_token ) { smarttoolz_video_auth_post_redirect( 'login', 'token_invalid' ); }
    $profile_response = wp_remote_get( 'https://www.googleapis.com/oauth2/v3/userinfo', array( 'timeout' => 15, 'headers' => array( 'Authorization' => 'Bearer ' . $access_token ) ) );
    if ( is_wp_error( $profile_response ) ) { smarttoolz_video_auth_post_redirect( 'login', 'profile_request' ); }
    $profile = json_decode( wp_remote_retrieve_body( $profile_response ), true ); $google_id = isset( $profile['sub'] ) ? sanitize_text_field( $profile['sub'] ) : ''; $email = isset( $profile['email'] ) ? sanitize_email( $profile['email'] ) : '';
    if ( ! $google_id || ! is_email( $email ) || empty( $profile['email_verified'] ) ) { smarttoolz_video_auth_post_redirect( 'login', 'email_not_verified' ); }
    $user_id = smarttoolz_video_find_google_user( $google_id, $email );
    if ( ! $user_id ) {
        $display_name = isset( $profile['name'] ) ? sanitize_text_field( $profile['name'] ) : '';
        if ( '' === $display_name ) { $display_name = sanitize_user( current( explode( '@', $email ) ), true ); }
        $username = smarttoolz_video_unique_username( $display_name, $email );
        $user_id = wp_insert_user( array( 'user_login' => $username, 'user_pass' => wp_generate_password( 32, true, true ), 'user_email' => $email, 'display_name' => $display_name ? $display_name : $username, 'first_name' => isset( $profile['given_name'] ) ? sanitize_text_field( $profile['given_name'] ) : '', 'last_name' => isset( $profile['family_name'] ) ? sanitize_text_field( $profile['family_name'] ) : '', 'role' => get_option( 'default_role', 'subscriber' ) ) );
        if ( is_wp_error( $user_id ) ) { smarttoolz_video_auth_post_redirect( 'login', 'account_create' ); }
    }
    update_user_meta( $user_id, '_smarttoolz_google_id', $google_id );
    update_user_meta( $user_id, 'smarttoolz_channel_handle', get_user_by( 'id', $user_id )->user_nicename );
    if ( '' === get_user_meta( $user_id, 'smarttoolz_channel_name', true ) ) { update_user_meta( $user_id, 'smarttoolz_channel_name', get_user_by( 'id', $user_id )->display_name ); }
    if ( ! empty( $profile['picture'] ) ) { update_user_meta( $user_id, 'smarttoolz_avatar_url', esc_url_raw( $profile['picture'] ) ); }
    wp_set_current_user( $user_id ); wp_set_auth_cookie( $user_id, true );
    $redirect_to = isset( $state_data['redirect_to'] ) ? $state_data['redirect_to'] : smarttoolz_video_route_url( 'home' );
    wp_safe_redirect( wp_validate_redirect( $redirect_to, smarttoolz_video_route_url( 'home' ) ) ); exit;
}
add_action( 'init', 'smarttoolz_video_google_callback', 1 );
function smarttoolz_video_find_google_user( $google_id, $email ) { $users = get_users( array( 'meta_key' => '_smarttoolz_google_id', 'meta_value' => $google_id, 'number' => 1, 'fields' => 'ids' ) ); if ( ! empty( $users ) ) { return absint( $users[0] ); } $user = get_user_by( 'email', $email ); return $user ? absint( $user->ID ) : 0; }
function smarttoolz_video_unique_username( $display_name, $email ) { $base = sanitize_user( $display_name, true ); if ( '' === $base ) { $base = sanitize_user( current( explode( '@', $email ) ), true ); } if ( '' === $base ) { $base = 'creator'; } $base = substr( $base, 0, 50 ); $username = $base; $i = 2; while ( username_exists( $username ) ) { $username = $base . $i; $i++; } return $username; }
function smarttoolz_video_auth_error_message() {
    $error = isset( $_GET['stv_auth_error'] ) ? sanitize_key( wp_unslash( $_GET['stv_auth_error'] ) ) : '';
    $site = smarttoolz_video_auth_site_name();
    $messages = array(
        'google_denied' => 'Google sign-in was cancelled.', 'invalid_state' => 'The Google sign-in session expired. Please try again.', 'google_not_configured' => 'Google sign-in is not configured yet.', 'token_request' => 'Could not contact Google securely. Please try again.', 'token_invalid' => 'Google did not return a valid sign-in token.', 'profile_request' => 'Could not retrieve your Google profile. Please try again.', 'email_not_verified' => 'Google must provide a verified email address.', 'account_create' => 'Your ' . $site . ' account could not be created.', 'invalid_form' => 'Please try the form again.', 'missing_login' => 'Enter your email/username and password.', 'invalid_login' => 'Incorrect email/username or password.', 'missing_register' => 'Please fill in all registration fields.', 'invalid_username' => 'Username must be at least 3 characters and use letters, numbers, dots, hyphens or underscores.', 'username_taken' => 'That username is already taken.', 'email_taken' => 'An account with that email already exists. Please sign in.', 'invalid_email' => 'Please enter a valid email address.', 'weak_password' => 'Password does not meet the minimum password length.', 'password_mismatch' => 'Passwords do not match.',
    );
    return isset( $messages[ $error ] ) ? $messages[ $error ] : '';
}
function smarttoolz_video_render_login() {
    if ( is_user_logged_in() ) { wp_safe_redirect( smarttoolz_video_route_url( 'home' ) ); exit; }
    $route = get_query_var( 'smarttoolz_video_route', 'login' ); if ( 'signup' === $route ) { return smarttoolz_video_render_register(); }
    $redirect_to = wp_get_referer() ? wp_get_referer() : smarttoolz_video_route_url( 'home' ); $google_url = smarttoolz_video_google_login_url( $redirect_to ); $error = smarttoolz_video_auth_error_message(); $site = smarttoolz_video_auth_site_name();
    ?>
    <section class="stv-auth"><div class="stv-auth__card">
        <span class="stv-auth__mark">▶</span><h1>Sign in to <?php echo esc_html( $site ); ?></h1><p>Sign in to create your channel, upload videos and manage your personal library.</p>
        <?php if ( $error ) : ?><div class="stv-notice stv-notice--error"><?php echo esc_html( $error ); ?></div><?php endif; ?>
        <form method="post" class="stv-auth-form" action="<?php echo esc_url( smarttoolz_video_route_url( 'login' ) ); ?>">
            <?php wp_nonce_field( 'stv_auth_form', 'stv_auth_nonce' ); ?><input type="hidden" name="stv_auth_action" value="login"><input type="hidden" name="stv_redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>">
            <label>Email or username<input type="text" name="stv_login_identifier" autocomplete="username" required></label><label>Password<input type="password" name="stv_login_password" autocomplete="current-password" required></label><label class="stv-check"><input type="checkbox" name="stv_login_remember" value="1"> Remember me</label><button class="stv-auth-submit" type="submit">Sign in</button>
        </form>
        <?php if ( $google_url ) : ?><div class="stv-divider"><span>or</span></div><a class="stv-google-button" href="<?php echo esc_url( $google_url ); ?>"><span class="stv-google-button__g">G</span><span>Continue with Google</span></a><?php endif; ?>
        <div class="stv-auth-switch">Don't have an account? <a href="<?php echo esc_url( smarttoolz_video_route_url( 'signup' ) ); ?>">Create account</a></div><div class="stv-auth__footer"><?php echo esc_html( $site ); ?> account • WordPress default login is not used.</div>
    </div></section>
    <?php
}
function smarttoolz_video_render_register() {
    $error = smarttoolz_video_auth_error_message(); $site = smarttoolz_video_auth_site_name();
    ?>
    <section class="stv-auth"><div class="stv-auth__card">
        <span class="stv-auth__mark">▶</span><h1>Create your <?php echo esc_html( $site ); ?> account</h1><p>Join <?php echo esc_html( $site ); ?>, create your own channel and start sharing videos.</p>
        <?php if ( $error ) : ?><div class="stv-notice stv-notice--error"><?php echo esc_html( $error ); ?></div><?php endif; ?>
        <form method="post" class="stv-auth-form" action="<?php echo esc_url( smarttoolz_video_route_url( 'signup' ) ); ?>">
            <?php wp_nonce_field( 'stv_auth_form', 'stv_auth_nonce' ); ?><input type="hidden" name="stv_auth_action" value="register">
            <label>Full name<input type="text" name="stv_register_name" autocomplete="name" required></label><label>Username / channel handle<input type="text" name="stv_register_username" autocomplete="username" pattern="[A-Za-z0-9_.-]{3,}" required><small>Your channel URL will use @username.</small></label><label>Email<input type="email" name="stv_register_email" autocomplete="email" required></label><label>Password<input type="password" name="stv_register_password" autocomplete="new-password" required></label><label>Confirm password<input type="password" name="stv_register_confirm" autocomplete="new-password" required></label><button class="stv-auth-submit" type="submit">Create account</button>
        </form>
        <?php $google_url = smarttoolz_video_google_login_url( smarttoolz_video_route_url( 'home' ) ); if ( $google_url ) : ?><div class="stv-divider"><span>or</span></div><a class="stv-google-button" href="<?php echo esc_url( $google_url ); ?>"><span class="stv-google-button__g">G</span><span>Sign up with Google</span></a><?php endif; ?>
        <div class="stv-auth-switch">Already have an account? <a href="<?php echo esc_url( smarttoolz_video_route_url( 'login' ) ); ?>">Sign in</a></div>
    </div></section>
    <?php
}
function smarttoolz_video_auth_styles() { $route = get_query_var( 'smarttoolz_video_route' ); if ( 'login' !== $route && 'signup' !== $route ) { return; } echo '<style>.stv-auth{width:100%;max-width:560px;margin:40px auto}.stv-auth__card{background:#181818;border:1px solid #303030;border-radius:18px;padding:32px;box-shadow:0 12px 35px rgba(0,0,0,.25)}.stv-auth__mark{display:grid;place-items:center;width:48px;height:34px;border-radius:9px;background:#ff0033;color:#fff;font-weight:900;margin-bottom:20px}.stv-auth__card h1{margin:0 0 8px;font-size:28px}.stv-auth__card>p{color:#aaa;font-size:14px;margin:0 0 22px}.stv-auth-form label{display:block;color:#ddd;font-size:13px;font-weight:600;margin:0 0 14px}.stv-auth-form input[type=text],.stv-auth-form input[type=email],.stv-auth-form input[type=password]{display:block;box-sizing:border-box;width:100%;margin-top:7px;padding:12px 13px;background:#101010;border:1px solid #3d3d3d;border-radius:8px;color:#fff;outline:none}.stv-auth-form input:focus{border-color:#777}.stv-auth-form small{display:block;color:#777;font-weight:400;margin-top:5px}.stv-check{display:flex!important;align-items:center;gap:8px;font-weight:400!important}.stv-check input{margin:0}.stv-auth-submit{width:100%;border:0;border-radius:8px;padding:13px 18px;background:#ff0033;color:#fff;font-weight:800;cursor:pointer;font-size:14px}.stv-divider{display:flex;align-items:center;gap:12px;margin:20px 0;color:#777}.stv-divider:before,.stv-divider:after{content:"";height:1px;background:#333;flex:1}.stv-google-button{display:flex;align-items:center;justify-content:center;gap:11px;width:100%;box-sizing:border-box;padding:13px 18px;border:1px solid #444;border-radius:9px;background:#fff;color:#111!important;font-weight:700;font-size:14px;text-decoration:none}.stv-google-button:hover{background:#eee}.stv-google-button__g{font-weight:900;font-size:18px}.stv-auth-switch{text-align:center;margin-top:20px;color:#aaa;font-size:13px}.stv-auth-switch a{color:#fff;font-weight:700}.stv-auth__footer{margin-top:20px;color:#777;font-size:11px;text-align:center}.stv-auth .stv-notice{margin-bottom:14px;padding:10px 12px;border-radius:7px;background:#3a1717;color:#ffb4b4;font-size:13px}</style>'; }
add_action( 'wp_head', 'smarttoolz_video_auth_styles', 31 );

function smarttoolz_video_auth_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) { return; }
    $s = smarttoolz_video_auth_settings();
    ?>
    <div class="wrap"><h1>Google Login</h1><p>Configure Google OAuth for the custom frontend login and registration pages. WordPress <code>/wp-login.php</code> is not modified.</p>
    <form method="post" action="options.php"><?php settings_fields( 'smarttoolz_video_auth_settings_group' ); ?><table class="form-table" role="presentation">
    <tr><th>Enable Google Login</th><td><label><input type="checkbox" name="smarttoolz_video_auth_settings[google_enabled]" value="1" <?php checked( $s['google_enabled'], 1 ); ?>> Allow Google sign-in/sign-up</label></td></tr><tr><th>Google Client ID</th><td><input type="text" class="regular-text" name="smarttoolz_video_auth_settings[google_client_id]" value="<?php echo esc_attr( $s['google_client_id'] ); ?>" autocomplete="off"></td></tr><tr><th>Google Client Secret</th><td><input type="password" class="regular-text" name="smarttoolz_video_auth_settings[google_client_secret]" value="" autocomplete="new-password"><p class="description">Leave blank to keep the existing secret.</p></td></tr><tr><th>Authorized redirect URI</th><td><code><?php echo esc_html( smarttoolz_video_auth_redirect_uri() ); ?></code></td></tr></table><?php submit_button( 'Save Google Login Settings' ); ?></form><hr><h2>Status</h2><p><strong><?php echo smarttoolz_video_google_ready() ? 'Configured and ready' : 'Not configured'; ?></strong></p></div>
    <?php
}
