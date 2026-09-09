<?php
/**
 * SmartToolz Video authentication UI and Google sign-in integration.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_google_client_id() {
    if ( function_exists( 'smarttoolz_video_setting' ) ) {
        return trim( (string) smarttoolz_video_setting( 'google_client_id', '' ) );
    }
    return trim( (string) get_option( 'smarttoolz_google_client_id', '' ) );
}

function smarttoolz_auth_settings() {
    register_setting( 'smarttoolz_video_settings', 'smarttoolz_google_client_id', array( 'type' => 'string', 'sanitize_callback' => 'sanitize_text_field', 'default' => '' ) );
}
add_action( 'admin_init', 'smarttoolz_auth_settings' );

function smarttoolz_auth_settings_menu() {
    add_submenu_page( 'edit.php?post_type=st_video', 'Authentication', 'Authentication', 'manage_options', 'smarttoolz-video-auth', 'smarttoolz_auth_settings_page' );
}
add_action( 'admin_menu', 'smarttoolz_auth_settings_menu' );

function smarttoolz_auth_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) { return; }
    $client_id = smarttoolz_google_client_id();
    ?>
    <div class="wrap">
        <h1>SmartToolz Authentication</h1>
        <p>Use <strong>SmartToolz Videos → Video Settings → Login & Register</strong> for the main authentication configuration.</p>
        <p><a class="button button-primary" href="<?php echo esc_url( admin_url( 'edit.php?post_type=st_video&page=smarttoolz-video-settings&tab=auth' ) ); ?>">Open Login & Register Settings</a></p>
        <?php if ( $client_id ) : ?><p class="notice notice-success inline"><strong>Google Client ID configured.</strong> <code><?php echo esc_html( $client_id ); ?></code></p><?php else : ?><p class="notice notice-warning inline">Google sign-in is not configured yet.</p><?php endif; ?>
    </div>
    <?php
}

function smarttoolz_auth_assets() {
    $pagenow = isset( $GLOBALS['pagenow'] ) ? $GLOBALS['pagenow'] : '';
    if ( 'wp-login.php' !== $pagenow ) { return; }
    wp_enqueue_script( 'smarttoolz-google-gsi', 'https://accounts.google.com/gsi/client', array(), null, true );
    wp_enqueue_style( 'smarttoolz-auth', SMARTTOOLZ_VIDEO_URL . 'assets/css/auth.css', array(), SMARTTOOLZ_VIDEO_VERSION . '.auth4' );
}
add_action( 'login_enqueue_scripts', 'smarttoolz_auth_assets' );

function smarttoolz_auth_enable_registration_on_login() {
    return isset( $GLOBALS['pagenow'] ) && 'wp-login.php' === $GLOBALS['pagenow'] ? true : false;
}
add_filter( 'option_users_can_register', 'smarttoolz_auth_enable_registration_on_login' );

function smarttoolz_auth_header_text( $message ) {
    $message .= '<p class="stv-login-brand"><span class="stv-login-logo">S</span><span><strong>SmartToolz</strong><small>Watch. Share. Create.</small></span></p>';
    return $message;
}
add_filter( 'login_message', 'smarttoolz_auth_header_text' );

function smarttoolz_auth_google_button_markup() {
    if ( function_exists( 'smarttoolz_video_setting' ) && ! smarttoolz_video_setting( 'google_enabled', 1 ) ) { return ''; }
    $client_id = smarttoolz_google_client_id();
    if ( ! $client_id ) { return '<div class="stv-google-setup-note">Google sign-in is not configured yet. Ask the site administrator to add a Google OAuth Web Client ID.</div>'; }
    $nonce = wp_create_nonce( 'smarttoolz_google_login' );
    $redirect = wp_get_referer() ? wp_get_referer() : home_url( '/wordpress/' );
    ob_start(); ?>
    <div class="stv-auth-divider"><span>OR</span></div>
    <div class="stv-google-wrap">
        <div id="g_id_onload" data-client_id="<?php echo esc_attr( $client_id ); ?>" data-callback="smarttoolzGoogleCredential" data-auto_prompt="false" data-cancel_on_tap_outside="true"></div>
        <div class="stv-google-button" data-stv-google-button></div>
    </div>
    <script>
    window.smarttoolzGoogleCredential=function(response){if(!response||!response.credential)return;var data=new URLSearchParams();data.append('action','smarttoolz_google_login');data.append('nonce',<?php echo wp_json_encode($nonce); ?>);data.append('credential',response.credential);data.append('redirect_to',<?php echo wp_json_encode($redirect); ?>);fetch(<?php echo wp_json_encode(admin_url('admin-ajax.php')); ?>,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8'},body:data.toString(),credentials:'same-origin'}).then(function(r){return r.json();}).then(function(result){if(result&&result.success&&result.data&&result.data.redirect)window.location.href=result.data.redirect;else alert((result&&result.data&&result.data.message)||'Google sign-in failed.');}).catch(function(){alert('Google sign-in failed. Please try again.');});};
    window.addEventListener('load',function(){if(!window.google||!google.accounts||!google.accounts.id)return;var target=document.querySelector('[data-stv-google-button]');if(!target)return;google.accounts.id.initialize({client_id:<?php echo wp_json_encode($client_id); ?>,callback:smarttoolzGoogleCredential});google.accounts.id.renderButton(target,{type:'standard',theme:'outline',size:'large',text:'continue_with',shape:'rectangular',width:310,logo_alignment:'left'});});
    </script>
    <?php return ob_get_clean();
}
add_action( 'login_form', function() { echo smarttoolz_auth_google_button_markup(); } );
add_action( 'register_form', function() { echo smarttoolz_auth_google_button_markup(); } );

function smarttoolz_google_base64url_decode( $value ) { $value=strtr($value,'-_','+/');$pad=strlen($value)%4;if($pad)$value.=str_repeat('=',4-$pad);return base64_decode($value,true); }
function smarttoolz_google_verify_id_token( $token ) {
    $parts=explode('.',(string)$token);if(3!==count($parts))return new WP_Error('invalid_google_token','Invalid Google credential.');
    $header=json_decode(smarttoolz_google_base64url_decode($parts[0]),true);$claims=json_decode(smarttoolz_google_base64url_decode($parts[1]),true);$signature=smarttoolz_google_base64url_decode($parts[2]);
    if(!is_array($header)||!is_array($claims)||false===$signature)return new WP_Error('invalid_google_token','Invalid Google credential.');
    if('RS256'!==($header['alg']??'')||empty($header['kid']))return new WP_Error('invalid_google_token','Unsupported Google credential.');
    if(empty($claims['iss'])||!in_array($claims['iss'],array('https://accounts.google.com','accounts.google.com'),true))return new WP_Error('invalid_google_issuer','Invalid Google issuer.');
    if(empty($claims['aud'])||!hash_equals(smarttoolz_google_client_id(),(string)$claims['aud']))return new WP_Error('invalid_google_audience','Google client ID does not match.');
    if(empty($claims['sub'])||empty($claims['email'])||empty($claims['email_verified']))return new WP_Error('invalid_google_claims','Google account verification failed.');
    if(empty($claims['exp'])||(int)$claims['exp']<time())return new WP_Error('expired_google_token','Google credential has expired.');
    $keys=get_transient('smarttoolz_google_signing_keys');
    if(false===$keys){$response=wp_remote_get('https://www.googleapis.com/oauth2/v3/certs',array('timeout'=>8));if(is_wp_error($response))return new WP_Error('google_keys_error','Could not verify Google credential.');$keys=json_decode(wp_remote_retrieve_body($response),true);if(!is_array($keys)||empty($keys['keys']))return new WP_Error('google_keys_error','Could not verify Google credential.');set_transient('smarttoolz_google_signing_keys',$keys,HOUR_IN_SECONDS);}
    $cert=null;foreach($keys['keys'] as $key){if(isset($key['kid'],$key['x509c'][0])&&$key['kid']===$header['kid']){$cert=$key['x509c'][0];break;}}if(!$cert)return new WP_Error('google_key_missing','Could not verify Google credential.');
    $pem="-----BEGIN CERTIFICATE-----\n".chunk_split($cert,64,"\n")."-----END CERTIFICATE-----\n";$public_key=openssl_get_publickey($pem);if(!$public_key)return new WP_Error('google_key_invalid','Could not verify Google credential.');
    $valid=1===openssl_verify($parts[0].'.'.$parts[1],$signature,$public_key,OPENSSL_ALGO_SHA256);if(function_exists('openssl_free_key'))openssl_free_key($public_key);if(!$valid)return new WP_Error('invalid_google_signature','Google credential signature is invalid.');return $claims;
}
function smarttoolz_google_unique_username( $email, $sub ) { $base=sanitize_user(preg_replace('/@.*$/','',$email),true);if(!$base)$base='googleuser';$candidate=$base;$i=1;while(username_exists($candidate)){$candidate=$base.$i;$i++;if($i>9999){$candidate='google'.substr(preg_replace('/[^a-zA-Z0-9]/','',$sub),0,16);break;}}return $candidate; }
function smarttoolz_google_login_ajax() {
    check_ajax_referer('smarttoolz_google_login','nonce');$credential=isset($_POST['credential'])?trim(wp_unslash($_POST['credential'])):'';if(!$credential)wp_send_json_error(array('message'=>'Google credential missing.'),400);
    $claims=smarttoolz_google_verify_id_token($credential);if(is_wp_error($claims))wp_send_json_error(array('message'=>$claims->get_error_message()),401);
    $email=sanitize_email($claims['email']);$sub=sanitize_text_field($claims['sub']);$name=!empty($claims['name'])?sanitize_text_field($claims['name']):$email;
    $user=get_users(array('meta_key'=>'_smarttoolz_google_sub','meta_value'=>$sub,'number'=>1));$user=$user?$user[0]:null;
    if(!$user){$existing_id=email_exists($email);if($existing_id){$user=get_user_by('id',$existing_id);update_user_meta($user->ID,'_smarttoolz_google_sub',$sub);}else{$user_id=wp_create_user(smarttoolz_google_unique_username($email,$sub),wp_generate_password(32,true,true),$email);if(is_wp_error($user_id))wp_send_json_error(array('message'=>'Could not create your SmartToolz account.'),500);wp_update_user(array('ID'=>$user_id,'display_name'=>$name,'nickname'=>$name));update_user_meta($user_id,'_smarttoolz_google_sub',$sub);$user=get_user_by('id',$user_id);}}
    wp_set_current_user($user->ID);wp_set_auth_cookie($user->ID,true);do_action('wp_login',$user->user_login,$user);
    $redirect=isset($_POST['redirect_to'])?wp_validate_redirect(wp_unslash($_POST['redirect_to']),home_url('/wordpress/')):home_url('/wordpress/');wp_send_json_success(array('redirect'=>$redirect));
}
add_action('wp_ajax_nopriv_smarttoolz_google_login','smarttoolz_google_login_ajax');add_action('wp_ajax_smarttoolz_google_login','smarttoolz_google_login_ajax');