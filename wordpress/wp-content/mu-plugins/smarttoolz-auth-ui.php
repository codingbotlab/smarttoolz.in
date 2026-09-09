<?php
/**
 * SmartToolz native WordPress authentication UI.
 *
 * Extends the existing Login & Register settings tab and keeps authentication
 * on WordPress' native wp-login.php flow. No separate authentication page or
 * user/session system is created.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_auth_ui_defaults() {
    return array(
        'header_enabled'       => 1,
        'footer_enabled'       => 1,
        'remember_enabled'     => 1,
        'lost_password_enabled'=> 1,
        'register_enabled'     => 1,
        'brand_name'           => 'SmartToolz',
        'tagline'              => 'Watch. Share. Create.',
        'login_button_text'    => 'Log In',
        'register_button_text' => 'Register',
        'footer_text'          => 'SmartToolz Video · Watch. Share. Create.',
        'default_redirect'     => '',
    );
}

function smarttoolz_auth_ui_settings() {
    return wp_parse_args( (array) get_option( 'smarttoolz_auth_ui_settings', array() ), smarttoolz_auth_ui_defaults() );
}

function smarttoolz_auth_ui_setting( $key, $default = null ) {
    $s = smarttoolz_auth_ui_settings();
    return array_key_exists( $key, $s ) ? $s[ $key ] : $default;
}

function smarttoolz_auth_ui_register_settings() {
    register_setting(
        'smarttoolz_video_options',
        'smarttoolz_auth_ui_settings',
        array(
            'type'              => 'array',
            'sanitize_callback' => 'smarttoolz_auth_ui_sanitize',
            'default'           => smarttoolz_auth_ui_defaults(),
        )
    );
}
add_action( 'admin_init', 'smarttoolz_auth_ui_register_settings', 12 );

function smarttoolz_auth_ui_sanitize( $input ) {
    $d = smarttoolz_auth_ui_defaults();
    $input = is_array( $input ) ? $input : array();
    $out = $d;
    foreach ( array( 'header_enabled','footer_enabled','remember_enabled','lost_password_enabled','register_enabled' ) as $key ) {
        $out[ $key ] = empty( $input[ $key ] ) ? 0 : 1;
    }
    $out['brand_name'] = sanitize_text_field( $input['brand_name'] ?? $d['brand_name'] );
    $out['tagline'] = sanitize_text_field( $input['tagline'] ?? $d['tagline'] );
    $out['login_button_text'] = sanitize_text_field( $input['login_button_text'] ?? $d['login_button_text'] );
    $out['register_button_text'] = sanitize_text_field( $input['register_button_text'] ?? $d['register_button_text'] );
    $out['footer_text'] = sanitize_text_field( $input['footer_text'] ?? $d['footer_text'] );
    $out['default_redirect'] = esc_url_raw( $input['default_redirect'] ?? '' );
    return $out;
}

function smarttoolz_auth_ui_admin_fields() {
    $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
    if ( ! $screen || false === strpos( (string) $screen->id, 'smarttoolz-video' ) ) { return; }
    $tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'general';
    if ( 'auth' !== $tab ) { return; }
    $s = smarttoolz_auth_ui_settings();
    echo '<script id="smarttoolz-auth-admin-fields">
    document.addEventListener("DOMContentLoaded",function(){
      var table=document.querySelector("form[action*=options.php] table.form-table");
      if(!table || table.querySelector("[data-st-auth-ui]")) return;
      var rows=`
        <tr data-st-auth-ui><th scope="row">Authentication engine</th><td><strong>WordPress native authentication</strong><p class="description">SmartToolz uses WordPress <code>wp-login.php</code>, native users, sessions, password reset and registration. No separate login system is used.</p></td></tr>
        <tr data-st-auth-ui><th scope="row">Common login header</th><td><label><input type="hidden" name="smarttoolz_auth_ui_settings[header_enabled]" value="0"><input type="checkbox" name="smarttoolz_auth_ui_settings[header_enabled]" value="1" <?php echo checked($s['header_enabled'],1,false); ?>> Show the same SmartToolz/YouTube-style header on wp-login.php.</label></td></tr>
        <tr data-st-auth-ui><th scope="row">Common login footer</th><td><label><input type="hidden" name="smarttoolz_auth_ui_settings[footer_enabled]" value="0"><input type="checkbox" name="smarttoolz_auth_ui_settings[footer_enabled]" value="1" <?php echo checked($s['footer_enabled'],1,false); ?>> Show the site footer on login/register/password-reset screens.</label></td></tr>
        <tr data-st-auth-ui><th scope="row">Remember Me</th><td><label><input type="hidden" name="smarttoolz_auth_ui_settings[remember_enabled]" value="0"><input type="checkbox" name="smarttoolz_auth_ui_settings[remember_enabled]" value="1" <?php echo checked($s['remember_enabled'],1,false); ?>> Show the Remember Me control.</label></td></tr>
        <tr data-st-auth-ui><th scope="row">Lost password link</th><td><label><input type="hidden" name="smarttoolz_auth_ui_settings[lost_password_enabled]" value="0"><input type="checkbox" name="smarttoolz_auth_ui_settings[lost_password_enabled]" value="1" <?php echo checked($s['lost_password_enabled'],1,false); ?>> Show password-reset links.</label></td></tr>
        <tr data-st-auth-ui><th scope="row">Registration</th><td><label><input type="hidden" name="smarttoolz_auth_ui_settings[register_enabled]" value="0"><input type="checkbox" name="smarttoolz_auth_ui_settings[register_enabled]" value="1" <?php echo checked($s['register_enabled'],1,false); ?>> Allow the native WordPress registration link and registration screen.</label></td></tr>
        <tr data-st-auth-ui><th scope="row">Brand name</th><td><input type="text" class="regular-text" name="smarttoolz_auth_ui_settings[brand_name]" value="<?php echo esc_attr($s['brand_name']); ?>"></td></tr>
        <tr data-st-auth-ui><th scope="row">Tagline</th><td><input type="text" class="regular-text" name="smarttoolz_auth_ui_settings[tagline]" value="<?php echo esc_attr($s['tagline']); ?>"></td></tr>
        <tr data-st-auth-ui><th scope="row">Login button text</th><td><input type="text" class="regular-text" name="smarttoolz_auth_ui_settings[login_button_text]" value="<?php echo esc_attr($s['login_button_text']); ?>"></td></tr>
        <tr data-st-auth-ui><th scope="row">Register button text</th><td><input type="text" class="regular-text" name="smarttoolz_auth_ui_settings[register_button_text]" value="<?php echo esc_attr($s['register_button_text']); ?>"></td></tr>
        <tr data-st-auth-ui><th scope="row">Footer text</th><td><input type="text" class="regular-text" name="smarttoolz_auth_ui_settings[footer_text]" value="<?php echo esc_attr($s['footer_text']); ?>"></td></tr>
        <tr data-st-auth-ui><th scope="row">Default login redirect</th><td><input type="url" class="regular-text code" name="smarttoolz_auth_ui_settings[default_redirect]" value="<?php echo esc_attr($s['default_redirect']); ?>"><p class="description">Optional full URL. The redirect_to URL supplied by WordPress remains higher priority.</p></td></tr>`;
      table.insertAdjacentHTML("beforeend",rows);
      var form=document.querySelector("form[action*=options.php]");
      if(form && !form.querySelector("input[name=smarttoolz_auth_ui_settings_nonce]")){
        var n=document.createElement("input"); n.type="hidden"; n.name="smarttoolz_auth_ui_settings_nonce"; n.value=' . wp_json_encode( wp_create_nonce( 'smarttoolz_auth_ui_settings' ) ) . '; form.appendChild(n);
      }
    });</script>';
}
add_action( 'admin_footer', 'smarttoolz_auth_ui_admin_fields', 98 );

function smarttoolz_auth_ui_login_filters() {
    $pagenow = isset( $GLOBALS['pagenow'] ) ? $GLOBALS['pagenow'] : '';
    if ( 'wp-login.php' !== $pagenow ) { return; }
    if ( ! smarttoolz_auth_ui_setting( 'register_enabled', 1 ) ) {
        add_filter( 'option_users_can_register', '__return_false', 99 );
    } else {
        add_filter( 'option_users_can_register', 'smarttoolz_auth_enable_registration_on_login', 99 );
    }
    add_filter( 'login_form_defaults', 'smarttoolz_auth_ui_form_defaults', 20 );
    add_filter( 'login_message', 'smarttoolz_auth_ui_login_message', 25 );
    add_filter( 'login_headerurl', function() { return home_url( '/' ); } );
    add_filter( 'login_headertext', function() { return smarttoolz_auth_ui_setting( 'brand_name', 'SmartToolz' ); } );
    add_filter( 'login_redirect', 'smarttoolz_auth_ui_login_redirect', 20, 3 );
}
add_action( 'init', 'smarttoolz_auth_ui_login_filters', 20 );

function smarttoolz_auth_ui_form_defaults( $defaults ) {
    if ( ! smarttoolz_auth_ui_setting( 'remember_enabled', 1 ) ) { $defaults['remember'] = false; }
    return $defaults;
}

function smarttoolz_auth_ui_login_message( $message ) {
    return $message;
}

function smarttoolz_auth_ui_login_redirect( $redirect_to, $requested, $user ) {
    if ( ! empty( $requested ) ) { return $redirect_to; }
    if ( ! empty( $user ) && ! is_wp_error( $user ) ) {
        $custom = smarttoolz_auth_ui_setting( 'default_redirect', '' );
        if ( $custom ) { return wp_validate_redirect( $custom, home_url( '/' ) ); }
    }
    return $redirect_to;
}

function smarttoolz_auth_ui_login_header() {
    if ( ! smarttoolz_auth_ui_setting( 'header_enabled', 1 ) ) { return; }
    $brand = smarttoolz_auth_ui_setting( 'brand_name', 'SmartToolz' );
    echo '<header id="st-login-site-header" class="st-login-site-header"><div class="st-login-header-brand"><a href="' . esc_url( home_url( '/' ) ) . '" aria-label="' . esc_attr( $brand ) . '"><span class="st-login-menu" aria-hidden="true">☰</span><span class="st-login-mark">S</span><strong>' . esc_html( $brand ) . '</strong></a></div><a class="st-login-back" href="' . esc_url( home_url( '/' ) ) . '">Back to SmartToolz</a></header>';
}
add_action( 'login_header', 'smarttoolz_auth_ui_login_header', 1 );

function smarttoolz_auth_ui_login_footer() {
    if ( ! smarttoolz_auth_ui_setting( 'footer_enabled', 1 ) ) { return; }
    $text = smarttoolz_auth_ui_setting( 'footer_text', 'SmartToolz Video · Watch. Share. Create.' );
    echo '<footer id="st-login-site-footer" class="st-login-site-footer"><div class="st-login-footer-inner"><strong>SmartToolz Video</strong><span>' . esc_html( $text ) . '</span><nav><a href="' . esc_url( home_url( '/' ) ) . '">Home</a><a href="' . esc_url( home_url( '/videos/' ) ) . '">Videos</a><a href="' . esc_url( home_url( '/video-categories/' ) ) . '">Categories</a></nav></div></footer>';
}
add_action( 'login_footer', 'smarttoolz_auth_ui_login_footer', 99 );

function smarttoolz_auth_ui_hide_links() {
    if ( ! smarttoolz_auth_ui_setting( 'lost_password_enabled', 1 ) ) {
        echo '<style id="st-auth-hide-lost">body.login #nav a[href*="lostpassword"],body.login #nav a[href*="?action=lostpassword"]{display:none!important}</style>';
    }
}
add_action( 'login_footer', 'smarttoolz_auth_ui_hide_links', 110 );

function smarttoolz_auth_ui_css() {
    $pagenow = isset( $GLOBALS['pagenow'] ) ? $GLOBALS['pagenow'] : '';
    if ( 'wp-login.php' !== $pagenow ) { return; }
    $tagline = smarttoolz_auth_ui_setting( 'tagline', 'Watch. Share. Create.' );
    echo '<style id="st-login-native-shell">html,body.login{min-height:100%;background:#0f0f0f!important}body.login{display:flex!important;flex-direction:column!important;margin:0!important;color:#f1f1f1!important;font-family:Arial,Helvetica,sans-serif!important;padding:0!important}body.login #st-login-site-header{flex:0 0 64px;display:flex;align-items:center;justify-content:space-between;padding:0 28px;background:#0f0f0f;border-bottom:1px solid #272727;color:#fff}body.login .st-login-header-brand a{display:flex;align-items:center;gap:9px;color:#fff;text-decoration:none;font-size:18px}body.login .st-login-menu{font-size:24px;line-height:1;margin-right:3px}body.login .st-login-mark{display:grid;place-items:center;width:32px;height:32px;border-radius:8px;background:#ff0000;color:#fff;font-weight:800}body.login .st-login-back{display:inline-flex;align-items:center;height:38px;padding:0 14px;border:1px solid #333;border-radius:20px;color:#fff;text-decoration:none;font-size:13px;font-weight:700}body.login .st-login-back:hover{background:#272727}body.login #login{flex:1 0 auto;width:420px;max-width:calc(100vw - 32px);margin:0 auto;padding:44px 0 70px}body.login #login .stv-login-brand{margin-bottom:20px}body.login #st-login-site-footer{margin-top:auto;flex:0 0 auto;border-top:1px solid #272727;background:#0f0f0f;color:#aaa}body.login .st-login-footer-inner{min-height:50px;display:flex;align-items:center;gap:12px;padding:0 20px;font-size:12px}body.login .st-login-footer-inner strong{color:#fff}body.login .st-login-footer-inner nav{margin-left:auto;display:flex;gap:18px}body.login .st-login-footer-inner a{color:#aaa!important;text-decoration:none!important}body.login .st-login-footer-inner a:hover{color:#fff!important}.st-login-tagline{display:block;color:#aaa;font-size:12px;margin-top:3px}body.login .stv-login-brand small{display:none!important}body.login #loginform,body.login #registerform,body.login #lostpasswordform{background:#181818!important;border-color:#333!important;color:#f1f1f1!important}body.login label{color:#f1f1f1!important}body.login .forgetmenot label{color:#f1f1f1!important}body.login #nav a,body.login #backtoblog a{color:#3ea6ff!important}@media(max-width:520px){body.login #st-login-site-header{padding:0 12px}body.login .st-login-back{padding-inline:10px;font-size:12px}body.login #login{width:calc(100vw - 24px);padding-top:28px}.st-login-footer-inner{padding-inline:12px!important}.st-login-footer-inner nav{gap:10px!important}}
    </style>';
}
add_action( 'login_head', 'smarttoolz_auth_ui_css', 50 );
