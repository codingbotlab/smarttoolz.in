<?php
/**
 * SmartToolz authentication completion layer.
 * Keeps WordPress native wp-login.php as the authentication engine.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
function smarttoolz_auth_completion_defaults() { return array( 'google_redirect_uri' => '' ); }
function smarttoolz_auth_completion_settings() { return wp_parse_args( (array) get_option( 'smarttoolz_auth_completion_settings', array() ), smarttoolz_auth_completion_defaults() ); }
function smarttoolz_auth_completion_register_settings() {
    register_setting( 'smarttoolz_video_options', 'smarttoolz_auth_completion_settings', array(
        'type'=>'array',
        'sanitize_callback'=>function($input){ return array('google_redirect_uri'=>esc_url_raw(is_array($input)&&isset($input['google_redirect_uri'])?$input['google_redirect_uri']:'')); },
        'default'=>smarttoolz_auth_completion_defaults()
    ) );
}
add_action( 'admin_init', 'smarttoolz_auth_completion_register_settings', 15 );
function smarttoolz_auth_completion_google_origin() {
    $u=wp_parse_url(home_url('/'));
    if(empty($u['scheme'])||empty($u['host'])) return home_url('/');
    $o=$u['scheme'].'://'.$u['host'];
    if(!empty($u['port'])) $o.=':'.absint($u['port']);
    return $o;
}
function smarttoolz_auth_completion_admin_fields() {
    $screen=function_exists('get_current_screen')?get_current_screen():null;
    if(!$screen||false===strpos((string)$screen->id,'smarttoolz-video')) return;
    $tab=isset($_GET['tab'])?sanitize_key(wp_unslash($_GET['tab'])):'general';
    if('auth'!==$tab) return;
    $s=smarttoolz_auth_completion_settings();
    $login_uri=wp_login_url();
    $origin=smarttoolz_auth_completion_google_origin();
    $rows='';
    $rows.='<tr data-st-auth-completion><th scope="row">Google authorized JavaScript origin</th><td><input type="text" class="regular-text code" readonly value="'.esc_attr($origin).'"><p class="description">Put this exact origin in Google Cloud. Do not add the WordPress <code>/wordpress/</code> path.</p></td></tr>';
    $rows.='<tr data-st-auth-completion><th scope="row">Google authorized redirect URI</th><td><input type="url" class="regular-text code" name="smarttoolz_auth_completion_settings[google_redirect_uri]" value="'.esc_attr($s['google_redirect_uri']).'" placeholder="'.esc_attr($login_uri).'"><p class="description">Current SmartToolz popup Sign in with Google flow does not need a redirect URI. For redirect UX, use exactly <code>'.esc_html($login_uri).'</code> in Google Cloud and here.</p></td></tr>';
    $rows.='<tr data-st-auth-completion><th scope="row">Google client secret</th><td><strong>Not used</strong><p class="description">Do not paste the Google client secret into WordPress. This implementation uses the Web Sign-In JavaScript flow and server-side ID-token verification.</p></td></tr>';
    $rows.='<tr data-st-auth-completion><th scope="row">Authentication engine</th><td><strong>WordPress native</strong><p class="description">Login, Create account and Forgot password stay on <code>wp-login.php</code>. SmartToolz supplies the visual UI and navigation.</p></td></tr>';
    $rows.='<tr data-st-auth-completion><th scope="row">Google setup checklist</th><td><ol style="margin:0 0 0 18px"><li>Create a Web application client.</li><li>Authorized JavaScript origins: <code>'.esc_html($origin).'</code>.</li><li>Copy the Client ID into the existing Google OAuth Web Client ID field.</li><li>Popup flow: leave Authorized redirect URIs empty.</li><li>Redirect flow later: add <code>'.esc_html($login_uri).'</code> exactly.</li></ol></td></tr>';
    echo '<script id="smarttoolz-auth-completion-admin">document.addEventListener("DOMContentLoaded",function(){var t=document.querySelector("form[action*=options.php] table.form-table");if(!t||t.querySelector("[data-st-auth-completion]"))return;t.insertAdjacentHTML("beforeend",'.wp_json_encode($rows).');});</script>';
}
add_action( 'admin_footer', 'smarttoolz_auth_completion_admin_fields', 99 );
function smarttoolz_auth_completion_action(){
    $a=isset($_REQUEST['action'])?sanitize_key(wp_unslash($_REQUEST['action'])):'login';
    return in_array($a,array('register','lostpassword','rp','resetpass'),true)?$a:'login';
}
function smarttoolz_auth_completion_navigation(){
    $a=smarttoolz_auth_completion_action(); $login=wp_login_url(); $register=wp_registration_url(); $lost=wp_lostpassword_url();
    echo '<nav class="st-auth-screen-nav" aria-label="Authentication navigation">';
    if('login'===$a){
        if(smarttoolz_auth_ui_setting('register_enabled',1)) echo '<a class="st-auth-nav-primary" href="'.esc_url($register).'">Create account</a>';
        if(smarttoolz_auth_ui_setting('lost_password_enabled',1)) echo '<a class="st-auth-nav-secondary" href="'.esc_url($lost).'">Forgot password?</a>';
    } elseif('register'===$a) {
        echo '<a class="st-auth-nav-primary" href="'.esc_url($login).'">Back to Log In</a>';
    } else {
        echo '<a class="st-auth-nav-primary" href="'.esc_url($login).'">Back to Log In</a>';
        if(smarttoolz_auth_ui_setting('register_enabled',1)) echo '<a class="st-auth-nav-secondary" href="'.esc_url($register).'">Create account</a>';
    }
    echo '</nav>';
}
add_action( 'login_footer', 'smarttoolz_auth_completion_navigation', 30 );
function smarttoolz_auth_completion_screen_message($message){
    if(!isset($GLOBALS['pagenow'])||'wp-login.php'!==$GLOBALS['pagenow']) return $message;
    $a=smarttoolz_auth_completion_action();
    if('register'===$a){$h='Create your SmartToolz account';$s='Join SmartToolz to like videos, follow creators and publish your own videos.';}
    elseif(in_array($a,array('lostpassword','rp','resetpass'),true)){$h='Reset your password';$s='Enter your account details and get back into SmartToolz securely.';}
    else{$h='Welcome back';$s='Sign in to like videos, follow creators, keep watch history and upload.';}
    return $message.'<div class="st-auth-screen-intro"><strong>'.esc_html($h).'</strong><span>'.esc_html($s).'</span></div>';
}
add_filter( 'login_message', 'smarttoolz_auth_completion_screen_message', 40 );
function smarttoolz_auth_completion_css(){
    if(!isset($GLOBALS['pagenow'])||'wp-login.php'!==$GLOBALS['pagenow']) return;
    echo '<style id="st-auth-completion-css">body.login .st-auth-screen-intro{margin:0 0 16px;text-align:left;color:#fff}body.login .st-auth-screen-intro strong{display:block;font-size:18px;line-height:1.25;margin-bottom:4px}body.login .st-auth-screen-intro span{display:block;color:#a9a9a9;font-size:12px;line-height:1.45}body.login .st-auth-screen-nav{display:flex;justify-content:center;align-items:center;gap:10px;flex-wrap:wrap;margin:16px 0 6px}body.login .st-auth-screen-nav a{display:inline-flex;align-items:center;justify-content:center;min-height:38px;padding:0 14px;border-radius:20px;text-decoration:none!important;font-size:13px;font-weight:700;box-sizing:border-box}body.login .st-auth-screen-nav .st-auth-nav-primary{background:#ff0000!important;color:#fff!important;border:1px solid #ff0000}body.login .st-auth-screen-nav .st-auth-nav-primary:hover{background:#cc0000!important;border-color:#cc0000}body.login .st-auth-screen-nav .st-auth-nav-secondary{background:#181818!important;color:#fff!important;border:1px solid #3a3a3a}body.login .st-auth-screen-nav .st-auth-nav-secondary:hover{background:#272727!important}body.login #nav,body.login #backtoblog{display:none!important}body.login .stv-login-brand{margin-bottom:12px!important}body.login #registerform,body.login #lostpasswordform,body.login #resetpassform{margin-top:0!important}</style>';
}
add_action( 'login_head', 'smarttoolz_auth_completion_css', 70 );
