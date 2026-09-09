<?php
/**
 * SmartToolz authentication completion layer.
 *
 * Keeps WordPress native wp-login.php as the authentication engine while
 * providing consistent login/register/lost-password presentation and clear
 * Google Web Client setup guidance in the existing Login & Register settings.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_auth_completion_defaults() {
    return array(
        'google_redirect_uri' => '',
    );
}

function smarttoolz_auth_completion_settings() {
    return wp_parse_args( (array) get_option( 'smarttoolz_auth_completion_settings', array() ), smarttoolz_auth_completion_defaults() );
}

function smarttoolz_auth_completion_register_settings() {
    register_setting(
        'smarttoolz_video_options',
        'smarttoolz_auth_completion_settings',
        array(
            'type'              => 'array',
            'sanitize_callback' => function( $input ) {
                $d = smarttoolz_auth_completion_defaults();
                $input = is_array( $input ) ? $input : array();
                return array(
                    'google_redirect_uri' => esc_url_raw( $input['google_redirect_uri'] ?? $d['google_redirect_uri'] ),
                );
            },
            'default'           => smarttoolz_auth_completion_defaults(),
        )
    );
}
add_action( 'admin_init', 'smarttoolz_auth_completion_register_settings', 15 );

function smarttoolz_auth_completion_admin_fields() {
    $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
    if ( ! $screen || false === strpos( (string) $screen->id, 'smarttoolz-video' ) ) { return; }
    $tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'general';
    if ( 'auth' !== $tab ) { return; }

    $s = smarttoolz_auth_completion_settings();
    $login_uri = wp_login_url();
    $origin = home_url( '/' );
    $rows = '';
    $rows .= '<tr data-st-auth-completion><th scope="row">Google authorized JavaScript origin</th><td><input type="text" class="regular-text code" readonly value="' . esc_attr( $origin ) . '"><p class="description">For this SmartToolz site, use the origin only: <code>' . esc_html( untrailingslashit( $origin ) ) . '</code>. Do not add <code>/wordpress/</code> to the JavaScript origin.</p></td></tr>';
    $rows .= '<tr data-st-auth-completion><th scope="row">Google authorized redirect URI</th><td><input type="url" class="regular-text code" name="smarttoolz_auth_completion_settings[google_redirect_uri]" value="' . esc_attr( $s['google_redirect_uri'] ) . '" placeholder="' . esc_attr( $login_uri ) . '"><p class="description">Not required for the current SmartToolz popup JavaScript Sign in with Google flow. If you switch Google to redirect UX later, use exactly <code>' . esc_html( $login_uri ) . '</code> and enter the same value in Google Cloud.</p></td></tr>';
    $rows .= '<tr data-st-auth-completion><th scope="row">Google client secret</th><td><strong>Not used</strong><p class="description">This implementation uses Google's browser JavaScript Sign-In flow and verifies the returned ID token server-side. Do not paste a client secret into WordPress. Google documents that client secrets are not used for Web applications.</p></td></tr>';
    $rows .= '<tr data-st-auth-completion><th scope="row">Authentication screens</th><td><strong>WordPress native</strong><p class="description">Login, Create account and Forgot password all stay on <code>wp-login.php</code>, while SmartToolz supplies the visual shell and navigation.</p></td></tr>';
    $rows .= '<tr data-st-auth-completion><th scope="row">Google setup checklist</th><td><ol style="margin:0 0 0 18px"><li>Google Cloud → Google Auth Platform → Clients → Web application.</li><li>Authorized JavaScript origins: <code>' . esc_html( untrailingslashit( $origin ) ) . '</code>.</li><li>Copy the Web Client ID into the existing Google OAuth Web Client ID field above.</li><li>Leave Authorized redirect URIs empty for the current popup flow.</li><li>Save the SmartToolz settings and test Continue with Google.</li></ol></td></tr>';

    echo '<script id="smarttoolz-auth-completion-admin">document.addEventListener("DOMContentLoaded",function(){var table=document.querySelector("form[action*=options.php] table.form-table");if(!table||table.querySelector("[data-st-auth-completion]"))return;var rows=' . wp_json_encode( $rows ) . ';table.insertAdjacentHTML("beforeend",rows);var form=table.closest("form");if(form){form.addEventListener("submit",function(){form.querySelectorAll("input[type=checkbox][name^=\\"smarttoolz_auth_completion_settings\\"]").forEach(function(cb){if(cb.checked)return;var h=document.createElement("input");h.type="hidden";h.name=cb.name;h.value="0";form.appendChild(h);});});}});</script>';
}
add_action( 'admin_footer', 'smarttoolz_auth_completion_admin_fields', 99 );

function smarttoolz_auth_completion_action() {
    $action = isset( $_REQUEST['action'] ) ? sanitize_key( wp_unslash( $_REQUEST['action'] ) ) : 'login';
    return in_array( $action, array( 'register', 'lostpassword', 'rp', 'resetpass' ), true ) ? $action : 'login';
}

function smarttoolz_auth_completion_navigation() {
    $action = smarttoolz_auth_completion_action();
    $login = wp_login_url();
    $register = wp_registration_url();
    $lost = wp_lostpassword_url();
    echo '<nav class="st-auth-screen-nav" aria-label="Authentication navigation">';
    if ( 'login' === $action ) {
        echo '<a class="st-auth-nav-primary" href="' . esc_url( $register ) . '">Create account</a>';
        echo '<a class="st-auth-nav-secondary" href="' . esc_url( $lost ) . '">Forgot password?</a>';
    } elseif ( 'register' === $action ) {
        echo '<a class="st-auth-nav-primary" href="' . esc_url( $login ) . '">Back to Log In</a>';
    } else {
        echo '<a class="st-auth-nav-primary" href="' . esc_url( $login ) . '">Back to Log In</a>';
        if ( smarttoolz_auth_ui_setting( 'register_enabled', 1 ) ) {
            echo '<a class="st-auth-nav-secondary" href="' . esc_url( $register ) . '">Create account</a>';
        }
    }
    echo '</nav>';
}
add_action( 'login_footer', 'smarttoolz_auth_completion_navigation', 30 );

function smarttoolz_auth_completion_screen_message( $message ) {
    if ( ! isset( $GLOBALS['pagenow'] ) || 'wp-login.php' !== $GLOBALS['pagenow'] ) { return $message; }
    $action = smarttoolz_auth_completion_action();
    if ( 'register' === $action ) {
        $heading = 'Create your SmartToolz account';
        $sub = 'Join SmartToolz to like videos, follow creators and publish your own videos.';
    } elseif ( in_array( $action, array( 'lostpassword', 'rp', 'resetpass' ), true ) ) {
        $heading = 'Reset your password';
        $sub = 'Enter your account details and we will help you get back into SmartToolz.';
    } else {
        $heading = 'Welcome back';
        $sub = 'Sign in to like videos, follow creators, keep watch history and upload.';
    }
    return $message . '<div class="st-auth-screen-intro"><strong>' . esc_html( $heading ) . '</strong><span>' . esc_html( $sub ) . '</span></div>';
}
add_filter( 'login_message', 'smarttoolz_auth_completion_screen_message', 40 );

function smarttoolz_auth_completion_css() {
    if ( ! isset( $GLOBALS['pagenow'] ) || 'wp-login.php' !== $GLOBALS['pagenow'] ) { return; }
    echo '<style id="st-auth-completion-css">
    body.login .st-auth-screen-intro{margin:0 0 16px;text-align:left;color:#fff}
    body.login .st-auth-screen-intro strong{display:block;font-size:18px;line-height:1.25;margin-bottom:4px}
    body.login .st-auth-screen-intro span{display:block;color:#a9a9a9;font-size:12px;line-height:1.45}
    body.login .st-auth-screen-nav{display:flex;justify-content:center;align-items:center;gap:10px;flex-wrap:wrap;margin:16px 0 6px}
    body.login .st-auth-screen-nav a{display:inline-flex;align-items:center;justify-content:center;min-height:38px;padding:0 14px;border-radius:20px;text-decoration:none!important;font-size:13px;font-weight:700;box-sizing:border-box}
    body.login .st-auth-screen-nav .st-auth-nav-primary{background:#ff0000!important;color:#fff!important;border:1px solid #ff0000}
    body.login .st-auth-screen-nav .st-auth-nav-primary:hover{background:#cc0000!important;border-color:#cc0000}
    body.login .st-auth-screen-nav .st-auth-nav-secondary{background:#181818!important;color:#fff!important;border:1px solid #3a3a3a}
    body.login .st-auth-screen-nav .st-auth-nav-secondary:hover{background:#272727!important}
    body.login #nav{display:none!important}
    body.login #backtoblog{display:none!important}
    body.login .stv-login-brand{margin-bottom:12px!important}
    body.login #registerform,body.login #lostpasswordform,body.login #resetpassform{margin-top:0!important}
    </style>';
}
add_action( 'login_head', 'smarttoolz_auth_completion_css', 70 );
