<?php
/**
 * SmartToolz Video URL mapping and routing controls.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_route_defaults() {
    return array(
        'home' => array( 'label' => 'Video Home', 'slug' => 'video', 'enabled' => 1 ),
        'watch' => array( 'label' => 'Watch Video', 'slug' => 'watch', 'enabled' => 1 ),
        'shorts' => array( 'label' => 'Shorts', 'slug' => 'shorts', 'enabled' => 1 ),
        'subscriptions' => array( 'label' => 'Subscriptions', 'slug' => 'subscriptions', 'enabled' => 1 ),
        'search' => array( 'label' => 'Search', 'slug' => 'search', 'enabled' => 1 ),
        'channel' => array( 'label' => 'Channel', 'slug' => 'channel', 'enabled' => 1 ),
        'channel-videos' => array( 'label' => 'Channel Videos', 'slug' => 'videos', 'enabled' => 1 ),
        'channel-shorts' => array( 'label' => 'Channel Shorts', 'slug' => 'shorts', 'enabled' => 1 ),
        'channel-live' => array( 'label' => 'Channel Live', 'slug' => 'live', 'enabled' => 1 ),
        'playlists' => array( 'label' => 'Playlists', 'slug' => 'playlists', 'enabled' => 1 ),
        'watch-later' => array( 'label' => 'Watch Later', 'slug' => 'watch-later', 'enabled' => 1 ),
        'history' => array( 'label' => 'History', 'slug' => 'history', 'enabled' => 1 ),
        'liked-videos' => array( 'label' => 'Liked Videos', 'slug' => 'liked', 'enabled' => 1 ),
        'your-videos' => array( 'label' => 'Your Videos', 'slug' => 'your-videos', 'enabled' => 1 ),
        'trending' => array( 'label' => 'Trending', 'slug' => 'trending', 'enabled' => 1 ),
        'explore' => array( 'label' => 'Explore', 'slug' => 'explore', 'enabled' => 1 ),
        'live' => array( 'label' => 'Live', 'slug' => 'live', 'enabled' => 1 ),
        'memberships' => array( 'label' => 'Memberships', 'slug' => 'memberships', 'enabled' => 1 ),
        'purchases' => array( 'label' => 'Purchases', 'slug' => 'purchases', 'enabled' => 1 ),
        'account' => array( 'label' => 'Account', 'slug' => 'account', 'enabled' => 1 ),
        'edit-profile' => array( 'label' => 'Edit Profile', 'slug' => 'edit-profile', 'enabled' => 1 ),
        'comments' => array( 'label' => 'Comments & Activity', 'slug' => 'comments', 'enabled' => 1 ),
        'badges' => array( 'label' => 'Badges', 'slug' => 'badges', 'enabled' => 1 ),
        'podcasts' => array( 'label' => 'Podcasts', 'slug' => 'podcasts', 'enabled' => 1 ),
        'privacy' => array( 'label' => 'Privacy', 'slug' => 'privacy', 'enabled' => 1 ),
        'login' => array( 'label' => 'Login', 'slug' => 'login', 'enabled' => 1 ),
        'signup' => array( 'label' => 'Sign Up', 'slug' => 'signup', 'enabled' => 1 ),
        'forgot-password' => array( 'label' => 'Forgot Password', 'slug' => 'forgot-password', 'enabled' => 1 ),
        'settings' => array( 'label' => 'Settings', 'slug' => 'settings', 'enabled' => 1 ),
        'notifications' => array( 'label' => 'Notifications', 'slug' => 'notifications', 'enabled' => 1 ),
        'upload' => array( 'label' => 'Upload Video', 'slug' => 'upload', 'enabled' => 1 ),
    );
}

function smarttoolz_video_route_settings() {
    return wp_parse_args( (array) get_option( 'smarttoolz_video_route_settings', array() ), smarttoolz_video_route_defaults() );
}
function smarttoolz_video_route_mode() {
    return get_option( 'smarttoolz_video_route_mode', 'youtube' ) === 'legacy' ? 'legacy' : 'youtube';
}
function smarttoolz_video_route_slug( $key ) {
    $s = smarttoolz_video_route_settings(); $d = smarttoolz_video_route_defaults();
    return isset( $s[$key]['slug'] ) ? $s[$key]['slug'] : $d[$key]['slug'];
}
function smarttoolz_video_route_enabled( $key ) {
    $s = smarttoolz_video_route_settings(); $d = smarttoolz_video_route_defaults();
    return isset( $s[$key]['enabled'] ) ? ! empty( $s[$key]['enabled'] ) : ! empty( $d[$key]['enabled'] );
}
function smarttoolz_video_sanitize_route_settings( $input ) {
    $defaults = smarttoolz_video_route_defaults(); $input = is_array($input) ? $input : array(); $out = array(); $used = array();
    foreach ( $defaults as $key => $default ) {
        $slug = isset($input[$key]['slug']) ? sanitize_title($input[$key]['slug']) : $default['slug'];
        if ( '' === $slug ) { $slug = $default['slug']; }
        if ( 'home' !== $key && 'video' === $slug ) { $slug = $default['slug']; }
        if ( isset($used[$slug]) ) { $slug = $default['slug']; }
        $used[$slug] = true;
        $out[$key] = array('label'=>$default['label'],'slug'=>$slug,'enabled'=>empty($input[$key]['enabled']) ? 0 : 1);
    }
    $out['home']['enabled'] = 1;
    return $out;
}
function smarttoolz_video_sanitize_route_mode( $mode ) { return 'legacy' === $mode ? 'legacy' : 'youtube'; }
function smarttoolz_video_register_route_settings() {
    register_setting('smarttoolz_video_routing_group','smarttoolz_video_route_settings',array('sanitize_callback'=>'smarttoolz_video_sanitize_route_settings'));
    register_setting('smarttoolz_video_routing_group','smarttoolz_video_route_mode',array('sanitize_callback'=>'smarttoolz_video_sanitize_route_mode'));
}
add_action('admin_init','smarttoolz_video_register_route_settings');
function smarttoolz_video_routing_admin_menu() { add_submenu_page('smarttoolz','URL Mapping & Routing','URL Mapping & Routing','manage_options','smarttoolz-video-routing','smarttoolz_video_routing_settings_page'); }
add_action('admin_menu','smarttoolz_video_routing_admin_menu',26);

function smarttoolz_video_routing_settings_page() {
    if ( ! current_user_can('manage_options') ) { return; }
    $settings = smarttoolz_video_route_settings(); $defaults = smarttoolz_video_route_defaults(); $mode = smarttoolz_video_route_mode(); $notice = '';
    if ( isset($_POST['stv_routing_action'],$_POST['stv_routing_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['stv_routing_nonce'])),'stv_routing_save') ) {
        $action = sanitize_key(wp_unslash($_POST['stv_routing_action']));
        if ( 'reset' === $action ) { update_option('smarttoolz_video_route_settings',$defaults,false); update_option('smarttoolz_video_route_mode','youtube',false); delete_option('rewrite_rules'); $settings=$defaults; $mode='youtube'; $notice='<div class="notice notice-success is-dismissible"><p>SmartToolz URL mappings reset to YouTube-style defaults.</p></div>'; }
        elseif ( 'save' === $action ) { $raw=isset($_POST['stv_routes']) ? wp_unslash($_POST['stv_routes']) : array(); $settings=smarttoolz_video_sanitize_route_settings($raw); $mode=isset($_POST['stv_route_mode']) ? smarttoolz_video_sanitize_route_mode(wp_unslash($_POST['stv_route_mode'])) : 'youtube'; update_option('smarttoolz_video_route_settings',$settings,false); update_option('smarttoolz_video_route_mode',$mode,false); delete_option('rewrite_rules'); $notice='<div class="notice notice-success is-dismissible"><p>URL mapping, enable/disable and routing mode saved.</p></div>'; }
    }
    ?>
    <div class="wrap"><h1>URL Mapping &amp; Routing</h1><p>SmartToolz uses its own frontend routes. WordPress <code>/wp-login.php</code> is not modified.</p><?php echo $notice; ?>
    <div style="max-width:1100px;background:#fff;border:1px solid #dcdcde;border-radius:10px;padding:18px;margin-top:16px;">
      <form method="post"><?php wp_nonce_field('stv_routing_save','stv_routing_nonce'); ?><input type="hidden" name="stv_routing_action" value="save">
      <h2>Routing style</h2><label><input type="radio" name="stv_route_mode" value="youtube" <?php checked($mode,'youtube'); ?>> <strong>YouTube-style</strong> — watch uses <code>/watch?v=ID</code>, channels use <code>/@handle/</code>, search uses <code>/results?search_query=...</code>.</label><br><label><input type="radio" name="stv_route_mode" value="legacy" <?php checked($mode,'legacy'); ?>> Legacy nested SmartToolz routes.</label>
      <h2 style="margin-top:24px;">Routes</h2><table class="widefat striped"><thead><tr><th style="width:110px">Enabled</th><th>Route</th><th style="width:260px">URL Segment</th><th>Preview</th></tr></thead><tbody>
      <?php foreach($defaults as $key=>$default): $row=isset($settings[$key])?$settings[$key]:$default; $preview=smarttoolz_video_routing_preview_path($key,$settings); $sample='watch'===$key ? '/?v=123' : ('channel'===$key ? '/@creator' : '/'); ?>
      <tr><td><label><input type="checkbox" name="stv_routes[<?php echo esc_attr($key); ?>][enabled]" value="1" <?php checked(!empty($row['enabled'])); ?> <?php disabled('home',$key); ?>> <?php echo 'home'===$key?'Always':'On'; ?></label></td><td><strong><?php echo esc_html($default['label']); ?></strong><br><code><?php echo esc_html($key); ?></code></td><td><input type="text" class="regular-text" name="stv_routes[<?php echo esc_attr($key); ?>][slug]" value="<?php echo esc_attr($row['slug']); ?>" pattern="[A-Za-z0-9_-]+" <?php disabled('home',$key); ?>></td><td><code><?php echo esc_html(home_url('/'.$preview.$sample)); ?></code></td></tr>
      <?php endforeach; ?></tbody></table><p class="description" style="margin-top:12px;">YouTube-style routing examples: <code>/video/watch?v=123</code>, <code>/video/shorts/123</code>, <code>/video/@creator/</code>, <code>/video/@creator/videos/</code>, <code>/video/results?search_query=cats</code>, <code>/video/feed/subscriptions/</code>.</p>
      <?php submit_button('Save URL & Routing Settings','primary','submit',false); ?></form>
      <form method="post" style="display:inline-block;margin-left:8px;"><?php wp_nonce_field('stv_routing_save','stv_routing_nonce'); ?><input type="hidden" name="stv_routing_action" value="reset"><?php submit_button('Reset Defaults','secondary','submit',false,array('onclick'=>"return confirm('Reset all SmartToolz URL mappings and routing mode?');")); ?></form>
    </div></div><?php
}

function smarttoolz_video_routing_preview_path( $key, $settings = null ) {
    $settings=is_array($settings)?$settings:smarttoolz_video_route_settings(); $defaults=smarttoolz_video_route_defaults(); if(!isset($defaults[$key])) return '';
    if ( smarttoolz_video_route_mode() === 'youtube' ) {
        if('home'===$key) return trim($settings['home']['slug'],'/');
        $base=trim($settings['home']['slug'],'/');
        $map=array('watch'=>'watch','shorts'=>'shorts','subscriptions'=>'feed/subscriptions','search'=>'results','channel'=>'@{handle}','channel-videos'=>'@{handle}/videos','channel-shorts'=>'@{handle}/shorts','channel-live'=>'@{handle}/live','playlists'=>'playlists','watch-later'=>'feed/watch-later','history'=>'feed/history','liked-videos'=>'feed/liked','your-videos'=>'your-videos','trending'=>'trending','explore'=>'explore','live'=>'live','memberships'=>'memberships','purchases'=>'purchases','account'=>'account','edit-profile'=>'account/edit-profile','comments'=>'account/comments','badges'=>'account/badges','podcasts'=>'account/podcasts','privacy'=>'account/privacy','login'=>'login','signup'=>'signup','forgot-password'=>'forgot-password','settings'=>'settings','notifications'=>'notifications','upload'=>'upload');
        return trim($base.'/'.($map[$key] ?? $settings[$key]['slug']),'/');
    }
    if('home'===$key) return trim($settings['home']['slug'],'/'); if('watch'===$key) return trim($settings['home']['slug'].'/'.$settings['watch']['slug'],'/');
    $parents=array('channel-videos'=>'channel','channel-shorts'=>'channel','channel-live'=>'channel','edit-profile'=>'account','comments'=>'account','badges'=>'account','podcasts'=>'account','privacy'=>'account'); $parent=$parents[$key]??'home'; $parent_path=smarttoolz_video_routing_preview_path($parent,$settings); return trim($parent_path.'/'.$settings[$key]['slug'],'/');
}
