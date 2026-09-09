<?php
/** SmartToolz Video routing. */
if ( ! defined( 'ABSPATH' ) ) exit;

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
        'upload'=>array('label'=>'Upload Video','slug'=>'upload','enabled'=>1)
    );
}
function smarttoolz_video_route_settings() {
    $d=smarttoolz_video_route_defaults(); $x=get_option('smarttoolz_video_route_settings',array()); if(!is_array($x))$x=array();
    foreach($d as $k=>$v){$r=isset($x[$k])&&is_array($x[$k])?$x[$k]:array();$x[$k]=array_merge($v,$r);$x[$k]['slug']=sanitize_title((string)$x[$k]['slug'])?:$v['slug'];$x[$k]['enabled']=array_key_exists('enabled',$r)?(empty($r['enabled'])?0:1):$v['enabled'];} $x['home']['enabled']=1; return $x;
}
function smarttoolz_video_route_mode(){return get_option('smarttoolz_video_route_mode','youtube')==='legacy'?'legacy':'youtube';}
function smarttoolz_video_route_slug($k){$s=smarttoolz_video_route_settings();return isset($s[$k])?$s[$k]['slug']:'';}
function smarttoolz_video_route_enabled($k){$s=smarttoolz_video_route_settings();return isset($s[$k])?!empty($s[$k]['enabled']):false;}
function smarttoolz_video_sanitize_route_settings($in){$d=smarttoolz_video_route_defaults();$in=is_array($in)?$in:array();$o=array();$used=array();foreach($d as $k=>$v){$r=isset($in[$k])&&is_array($in[$k])?$in[$k]:array();$slug=isset($r['slug'])?sanitize_title((string)$r['slug']):$v['slug'];if(!$slug)$slug=$v['slug'];if(isset($used[$slug]))$slug=$v['slug'];$used[$slug]=1;$o[$k]=array('label'=>$v['label'],'slug'=>$slug,'enabled'=>empty($r['enabled'])?0:1);} $o['home']['enabled']=1;return $o;}
function smarttoolz_video_sanitize_route_mode($m){return $m==='legacy'?'legacy':'youtube';}
function smarttoolz_video_register_route_settings(){register_setting('smarttoolz_video_routing_group','smarttoolz_video_route_settings',array('sanitize_callback'=>'smarttoolz_video_sanitize_route_settings'));register_setting('smarttoolz_video_routing_group','smarttoolz_video_route_mode',array('sanitize_callback'=>'smarttoolz_video_sanitize_route_mode'));}
add_action('admin_init','smarttoolz_video_register_route_settings');
function smarttoolz_video_routing_admin_menu(){add_submenu_page('smarttoolz','URL Mapping & Routing','URL Mapping & Routing','manage_options','smarttoolz-video-routing','smarttoolz_video_routing_settings_page');}
add_action('admin_menu','smarttoolz_video_routing_admin_menu',26);
function smarttoolz_video_routing_settings_page(){if(!current_user_can('manage_options'))return;$s=smarttoolz_video_route_settings();$d=smarttoolz_video_route_defaults();$m=smarttoolz_video_route_mode();if(isset($_POST['stv_routing_action'],$_POST['stv_routing_nonce'])&&wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['stv_routing_nonce'])),'stv_routing_save')){if('reset'===sanitize_key(wp_unslash($_POST['stv_routing_action']))){$s=$d;$m='youtube';}else{$s=smarttoolz_video_sanitize_route_settings(isset($_POST['stv_routes'])?wp_unslash($_POST['stv_routes']):array());$m=smarttoolz_video_sanitize_route_mode(isset($_POST['stv_route_mode'])?wp_unslash($_POST['stv_route_mode']):'youtube');}update_option('smarttoolz_video_route_settings',$s,false);update_option('smarttoolz_video_route_mode',$m,false);flush_rewrite_rules(false);echo '<div class="notice notice-success"><p>URL mapping saved.</p></div>';}
?><div class="wrap"><h1>URL Mapping &amp; Routing</h1><p>SmartToolz routing only. WordPress <code>/wp-login.php</code> is untouched.</p><form method="post"><?php wp_nonce_field('stv_routing_save','stv_routing_nonce');?><input type="hidden" name="stv_routing_action" value="save"><p><label><input type="radio" name="stv_route_mode" value="youtube" <?php checked($m,'youtube');?>> YouTube-style</label> <label><input type="radio" name="stv_route_mode" value="legacy" <?php checked($m,'legacy');?>> Legacy</label></p><table class="widefat striped"><thead><tr><th>Enabled</th><th>Route</th><th>URL Segment</th></tr></thead><tbody><?php foreach($d as $k=>$v):?><tr><td><input type="checkbox" name="stv_routes[<?php echo esc_attr($k);?>][enabled]" value="1" <?php checked(!empty($s[$k]['enabled']));?> <?php disabled($k,'home');?>></td><td><?php echo esc_html($v['label']);?></td><td><input type="text" name="stv_routes[<?php echo esc_attr($k);?>][slug]" value="<?php echo esc_attr($s[$k]['slug']);?>" <?php disabled($k,'home');?>></td></tr><?php endforeach;?></tbody></table><?php submit_button('Save URL & Routing Settings');?><button type="submit" name="stv_routing_action" value="reset" class="button">Reset Defaults</button></form></div><?php }
function smarttoolz_video_routing_preview_path($k,$s=null){$s=is_array($s)?$s:smarttoolz_video_route_settings();if(!isset($s[$k]))return ''; $b=trim($s['home']['slug'],'/');if($k==='home')return $b;if(smarttoolz_video_route_mode()==='youtube'){$m=array('watch'=>'watch','shorts'=>'shorts','subscriptions'=>'feed/subscriptions','search'=>'results','channel'=>'@{handle}','channel-videos'=>'@{handle}/videos','channel-shorts'=>'@{handle}/shorts','channel-live'=>'@{handle}/live','watch-later'=>'feed/watch-later','history'=>'feed/history','liked-videos'=>'feed/liked','login'=>'login','signup'=>'signup','upload'=>'upload');return trim($b.'/'.(isset($m[$k])?$m[$k]:$s[$k]['slug']),'/');}return trim($b.'/'.$s[$k]['slug'],'/');}
