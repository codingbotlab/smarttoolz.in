<?php
/** SmartToolz Video URL Mapping & Routing admin helpers. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_route_defaults() {
    return array(
        'home'=>array('label'=>'Video Home','slug'=>'video','enabled'=>1),
        'watch'=>array('label'=>'Watch Video','slug'=>'watch','enabled'=>1),
        'shorts'=>array('label'=>'Shorts','slug'=>'shorts','enabled'=>1),
        'subscriptions'=>array('label'=>'Subscriptions','slug'=>'subscriptions','enabled'=>1),
        'search'=>array('label'=>'Search','slug'=>'search','enabled'=>1),
        'channel'=>array('label'=>'Channel','slug'=>'channel','enabled'=>1),
        'channel-videos'=>array('label'=>'Channel Videos','slug'=>'videos','enabled'=>1),
        'channel-shorts'=>array('label'=>'Channel Shorts','slug'=>'shorts','enabled'=>1),
        'channel-live'=>array('label'=>'Channel Live','slug'=>'live','enabled'=>1),
        'playlists'=>array('label'=>'Playlists','slug'=>'playlists','enabled'=>1),
        'watch-later'=>array('label'=>'Watch Later','slug'=>'watch-later','enabled'=>1),
        'history'=>array('label'=>'History','slug'=>'history','enabled'=>1),
        'liked-videos'=>array('label'=>'Liked Videos','slug'=>'liked','enabled'=>1),
        'your-videos'=>array('label'=>'Your Videos','slug'=>'your-videos','enabled'=>1),
        'trending'=>array('label'=>'Trending','slug'=>'trending','enabled'=>1),
        'explore'=>array('label'=>'Explore','slug'=>'explore','enabled'=>1),
        'live'=>array('label'=>'Live','slug'=>'live','enabled'=>1),
        'memberships'=>array('label'=>'Memberships','slug'=>'memberships','enabled'=>1),
        'purchases'=>array('label'=>'Purchases','slug'=>'purchases','enabled'=>1),
        'account'=>array('label'=>'Account','slug'=>'account','enabled'=>1),
        'edit-profile'=>array('label'=>'Edit Profile','slug'=>'edit-profile','enabled'=>1),
        'comments'=>array('label'=>'Comments & Activity','slug'=>'comments','enabled'=>1),
        'badges'=>array('label'=>'Badges','slug'=>'badges','enabled'=>1),
        'podcasts'=>array('label'=>'Podcasts','slug'=>'podcasts','enabled'=>1),
        'privacy'=>array('label'=>'Privacy','slug'=>'privacy','enabled'=>1),
        'login'=>array('label'=>'Login','slug'=>'login','enabled'=>1),
        'signup'=>array('label'=>'Sign Up','slug'=>'signup','enabled'=>1),
        'forgot-password'=>array('label'=>'Forgot Password','slug'=>'forgot-password','enabled'=>1),
        'settings'=>array('label'=>'Settings','slug'=>'settings','enabled'=>1),
        'notifications'=>array('label'=>'Notifications','slug'=>'notifications','enabled'=>1),
        'upload'=>array('label'=>'Upload Video','slug'=>'upload','enabled'=>1),
    );
}

function smarttoolz_video_route_settings() {
    $defaults = smarttoolz_video_route_defaults();
    $saved = get_option('smarttoolz_video_route_settings', array());
    if ( ! is_array($saved) ) { $saved = array(); }
    $out = array();
    foreach ( $defaults as $key => $default ) {
        $row = isset($saved[$key]) && is_array($saved[$key]) ? $saved[$key] : array();
        $slug = isset($row['slug']) ? sanitize_title((string)$row['slug']) : $default['slug'];
        $out[$key] = array(
            'label' => $default['label'],
            'slug' => $slug ?: $default['slug'],
            'enabled' => array_key_exists('enabled',$row) ? (empty($row['enabled']) ? 0 : 1) : 1,
        );
    }
    $out['home']['enabled'] = 1;
    return $out;
}

function smarttoolz_video_route_mode() {
    return get_option('smarttoolz_video_route_mode','youtube') === 'legacy' ? 'legacy' : 'youtube';
}
function smarttoolz_video_route_slug($key) {
    $settings = smarttoolz_video_route_settings();
    return isset($settings[$key]) ? $settings[$key]['slug'] : '';
}
function smarttoolz_video_route_enabled($key) {
    $settings = smarttoolz_video_route_settings();
    return isset($settings[$key]) && ! empty($settings[$key]['enabled']);
}
function smarttoolz_video_sanitize_route_settings($input) {
    $defaults = smarttoolz_video_route_defaults();
    $input = is_array($input) ? $input : array();
    $out = array();
    $used = array();
    foreach ( $defaults as $key => $default ) {
        $row = isset($input[$key]) && is_array($input[$key]) ? $input[$key] : array();
        $slug = isset($row['slug']) ? sanitize_title((string)$row['slug']) : $default['slug'];
        if ( ! $slug ) { $slug = $default['slug']; }
        if ( isset($used[$slug]) ) { $slug = $default['slug']; }
        $used[$slug] = 1;
        $out[$key] = array('label'=>$default['label'],'slug'=>$slug,'enabled'=>empty($row['enabled']) ? 0 : 1);
    }
    $out['home']['enabled'] = 1;
    return $out;
}
function smarttoolz_video_sanitize_route_mode($mode) {
    return $mode === 'legacy' ? 'legacy' : 'youtube';
}
function smarttoolz_video_register_route_settings() {
    register_setting('smarttoolz_video_routing_group','smarttoolz_video_route_settings',array('sanitize_callback'=>'smarttoolz_video_sanitize_route_settings'));
    register_setting('smarttoolz_video_routing_group','smarttoolz_video_route_mode',array('sanitize_callback'=>'smarttoolz_video_sanitize_route_mode'));
}
add_action('admin_init','smarttoolz_video_register_route_settings');

function smarttoolz_video_routing_admin_menu() {
    add_submenu_page('smarttoolz','URL Mapping & Routing','URL Mapping & Routing','manage_options','smarttoolz-video-routing','smarttoolz_video_routing_settings_page');
}
add_action('admin_menu','smarttoolz_video_routing_admin_menu',26);

function smarttoolz_video_routing_settings_page() {
    if ( ! current_user_can('manage_options') ) { return; }
    $defaults = smarttoolz_video_route_defaults();
    $settings = smarttoolz_video_route_settings();
    $mode = smarttoolz_video_route_mode();
    if ( isset($_POST['stv_routing_action'],$_POST['stv_routing_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['stv_routing_nonce'])),'stv_routing_save') ) {
        if ( 'reset' === sanitize_key(wp_unslash($_POST['stv_routing_action'])) ) {
            $settings = $defaults;
            $mode = 'youtube';
        } else {
            $settings = smarttoolz_video_sanitize_route_settings(isset($_POST['stv_routes']) ? wp_unslash($_POST['stv_routes']) : array());
            $mode = smarttoolz_video_sanitize_route_mode(isset($_POST['stv_route_mode']) ? wp_unslash($_POST['stv_route_mode']) : 'youtube');
        }
        update_option('smarttoolz_video_route_settings',$settings,false);
        update_option('smarttoolz_video_route_mode',$mode,false);
        flush_rewrite_rules(false);
        echo '<div class="notice notice-success"><p>URL mapping saved.</p></div>';
    }
    echo '<div class="wrap"><h1>URL Mapping &amp; Routing</h1><p>SmartToolz routing only. WordPress <code>/wp-login.php</code> is untouched.</p>';
    echo '<form method="post">';
    wp_nonce_field('stv_routing_save','stv_routing_nonce');
    echo '<input type="hidden" name="stv_routing_action" value="save">';
    echo '<p><label><input type="radio" name="stv_route_mode" value="youtube" '.checked($mode,'youtube',false).'> YouTube-style</label> &nbsp; <label><input type="radio" name="stv_route_mode" value="legacy" '.checked($mode,'legacy',false).'> Legacy</label></p>';
    echo '<table class="widefat striped"><thead><tr><th>Enabled</th><th>Route</th><th>URL Segment</th></tr></thead><tbody>';
    foreach ( $defaults as $key => $route ) {
        echo '<tr><td><input type="checkbox" name="stv_routes['.esc_attr($key).'][enabled]" value="1" '.checked(!empty($settings[$key]['enabled']),true,false).' '.disabled($key,'home',false).'></td><td>'.esc_html($route['label']).'</td><td><input type="text" name="stv_routes['.esc_attr($key).'][slug]" value="'.esc_attr($settings[$key]['slug']).'" '.disabled($key,'home',false).'></td></tr>';
    }
    echo '</tbody></table>';
    submit_button('Save URL & Routing Settings');
    echo ' <button type="submit" name="stv_routing_action" value="reset" class="button">Reset Defaults</button></form></div>';
}

function smarttoolz_video_routing_preview_path($key,$settings=null) {
    $settings = is_array($settings) ? $settings : smarttoolz_video_route_settings();
    if ( ! isset($settings[$key]) ) { return ''; }
    $base = trim($settings['home']['slug'],'/');
    if ( 'home' === $key ) { return $base; }
    if ( 'youtube' === smarttoolz_video_route_mode() ) {
        $map = array(
            'watch'=>'watch','shorts'=>'shorts','subscriptions'=>'feed/subscriptions','search'=>'results',
            'channel'=>'@{handle}','channel-videos'=>'@{handle}/videos','channel-shorts'=>'@{handle}/shorts','channel-live'=>'@{handle}/live',
            'watch-later'=>'feed/watch-later','history'=>'feed/history','liked-videos'=>'feed/liked','login'=>'login','signup'=>'signup','upload'=>'upload'
        );
        return trim($base.'/'.(isset($map[$key]) ? $map[$key] : $settings[$key]['slug']),'/');
    }
    return trim($base.'/'.$settings[$key]['slug'],'/');
}
