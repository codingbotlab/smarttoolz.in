<?php
/**
 * SmartToolz Video bootstrap.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
require_once SMARTTOOLZ_VIDEO_DIR . 'includes/videos.php';
require_once SMARTTOOLZ_VIDEO_DIR . 'includes/routing.php';

function smarttoolz_video_page_definitions() {
    return array(
        'home'=>array('title'=>'Home','slug'=>'video','parent'=>''),'shorts'=>array('title'=>'Shorts','slug'=>'shorts','parent'=>'home'),'subscriptions'=>array('title'=>'Subscriptions','slug'=>'subscriptions','parent'=>'home'),'search'=>array('title'=>'Search Results','slug'=>'search','parent'=>'home'),'channel'=>array('title'=>'Channel','slug'=>'channel','parent'=>'home'),'channel-videos'=>array('title'=>'Channel Videos','slug'=>'videos','parent'=>'channel'),'channel-shorts'=>array('title'=>'Channel Shorts','slug'=>'shorts','parent'=>'channel'),'channel-live'=>array('title'=>'Channel Live','slug'=>'live','parent'=>'channel'),'playlists'=>array('title'=>'Playlists','slug'=>'playlists','parent'=>'home'),'watch-later'=>array('title'=>'Watch Later','slug'=>'watch-later','parent'=>'home'),'history'=>array('title'=>'History','slug'=>'history','parent'=>'home'),'liked-videos'=>array('title'=>'Liked Videos','slug'=>'liked','parent'=>'home'),'your-videos'=>array('title'=>'Your Videos','slug'=>'your-videos','parent'=>'home'),'trending'=>array('title'=>'Trending','slug'=>'trending','parent'=>'home'),'explore'=>array('title'=>'Explore','slug'=>'explore','parent'=>'home'),'live'=>array('title'=>'Live','slug'=>'live','parent'=>'home'),'memberships'=>array('title'=>'Memberships','slug'=>'memberships','parent'=>'home'),'purchases'=>array('title'=>'Purchases','slug'=>'purchases','parent'=>'home'),'account'=>array('title'=>'Your Account','slug'=>'account','parent'=>'home'),'edit-profile'=>array('title'=>'Edit Profile','slug'=>'edit-profile','parent'=>'account'),'comments'=>array('title'=>'Comments & Activity','slug'=>'comments','parent'=>'account'),'badges'=>array('title'=>'Badges','slug'=>'badges','parent'=>'account'),'podcasts'=>array('title'=>'Your Podcasts','slug'=>'podcasts','parent'=>'account'),'privacy'=>array('title'=>'Privacy & Your Data','slug'=>'privacy','parent'=>'account'),'login'=>array('title'=>'Login','slug'=>'login','parent'=>'home'),'signup'=>array('title'=>'Sign Up','slug'=>'signup','parent'=>'home'),'forgot-password'=>array('title'=>'Forgot Password','slug'=>'forgot-password','parent'=>'home'),'settings'=>array('title'=>'Settings','slug'=>'settings','parent'=>'home'),'notifications'=>array('title'=>'Notifications','slug'=>'notifications','parent'=>'home'),'upload'=>array('title'=>'Upload Video','slug'=>'upload','parent'=>'home'),
    );
}
function smarttoolz_video_page_path($key,$definitions=null){$definitions=is_array($definitions)?$definitions:smarttoolz_video_page_definitions();if(!isset($definitions[$key]))return ''; $page=$definitions[$key];if(empty($page['parent']))return trim($page['slug'],'/');$parent=smarttoolz_video_page_path($page['parent'],$definitions);return trim($parent.'/'.$page['slug'],'/');}
function smarttoolz_video_sync_pages(){ $pages=smarttoolz_video_page_definitions();$ids=(array)get_option('smarttoolz_video_page_ids',array());$changed=false;foreach($pages as $key=>$page){$page_id=isset($ids[$key])?absint($ids[$key]):0;$valid=$page_id&&'page'===get_post_type($page_id)&&'trash'!==get_post_status($page_id);if(!$valid){$path=smarttoolz_video_page_path($key,$pages);$existing=get_page_by_path($path,OBJECT,'page');if($existing&&'trash'!==get_post_status($existing->ID)){$page_id=$existing->ID;}else{$parent_id=0;if(!empty($page['parent'])&&isset($ids[$page['parent']]))$parent_id=absint($ids[$page['parent']]);$page_id=wp_insert_post(array('post_title'=>$page['title'],'post_name'=>$page['slug'],'post_parent'=>$parent_id,'post_status'=>'publish','post_type'=>'page','post_content'=>'<!-- SmartToolz Video page: '.esc_html($key).' -->'),true);if(is_wp_error($page_id))continue;}$ids[$key]=absint($page_id);$changed=true;}$parent_id=0;if(!empty($page['parent'])&&isset($ids[$page['parent']]))$parent_id=absint($ids[$page['parent']]);$updates=array();if(absint(get_post_field('post_parent',$page_id))!==$parent_id){$updates['ID']=$page_id;$updates['post_parent']=$parent_id;}if(get_post_field('post_name',$page_id)!==$page['slug']){$updates['ID']=$page_id;$updates['post_name']=$page['slug'];}if(get_the_title($page_id)!==$page['title']){$updates['ID']=$page_id;$updates['post_title']=$page['title'];}if(!empty($updates)){wp_update_post($updates);$changed=true;}}if($changed||!get_option('smarttoolz_video_page_ids',false))update_option('smarttoolz_video_page_ids',$ids,false);smarttoolz_video_set_static_homepage(false);}
function smarttoolz_video_set_static_homepage($sync=true){if($sync)smarttoolz_video_sync_pages();$ids=(array)get_option('smarttoolz_video_page_ids',array());$home_id=isset($ids['home'])?absint($ids['home']):0;if(!$home_id||'page'!==get_post_type($home_id)||'trash'===get_post_status($home_id))return false;update_option('show_on_front','page');update_option('page_on_front',$home_id);return true;}
function smarttoolz_video_page_sync_cron(){if(!wp_next_scheduled('smarttoolz_video_page_sync_event'))wp_schedule_event(time()+HOUR_IN_SECONDS,'twicedaily','smarttoolz_video_page_sync_event');}
add_action('smarttoolz_video_page_sync_event','smarttoolz_video_sync_pages');

function smarttoolz_video_register_routes(){
    $s=smarttoolz_video_route_settings(); $base=trim($s['home']['slug'],'/');
    if('youtube'===smarttoolz_video_route_mode()){
        if(smarttoolz_video_route_enabled('home')) add_rewrite_rule('^'.preg_quote($base,'/').'/?$','index.php?smarttoolz_video_route=home','top');
        if(smarttoolz_video_route_enabled('watch')){add_rewrite_rule('^'.preg_quote($base,'/').'/'.preg_quote($s['watch']['slug'],'/').'/?$','index.php?smarttoolz_video_route=watch','top');add_rewrite_rule('^'.preg_quote($base,'/').'/'.preg_quote($s['watch']['slug'],'/').'/([^/]+)/?$','index.php?smarttoolz_video_route=watch&smarttoolz_video_id=$matches[1]','top');}
        if(smarttoolz_video_route_enabled('shorts')) add_rewrite_rule('^'.preg_quote($base,'/').'/'.preg_quote($s['shorts']['slug'],'/').'(/([^/]+))?/?$','index.php?smarttoolz_video_route=shorts&smarttoolz_video_id=$matches[2]','top');
        if(smarttoolz_video_route_enabled('channel')){
            add_rewrite_rule('^'.preg_quote($base,'/').'/@([^/]+)/?$','index.php?smarttoolz_video_route=channel&smarttoolz_video_channel=$matches[1]','top');
            foreach(array('channel-videos'=>'videos','channel-shorts'=>'shorts','channel-live'=>'live') as $key=>$tab){if(!smarttoolz_video_route_enabled($key))continue;add_rewrite_rule('^'.preg_quote($base,'/').'/@([^/]+)/'.preg_quote($s[$key]['slug'],'/').'/?$','index.php?smarttoolz_video_route='.$key.'&smarttoolz_video_channel=$matches[1]','top');}
        }
        if(smarttoolz_video_route_enabled('search')) add_rewrite_rule('^'.preg_quote($base,'/').'/'.preg_quote($s['search']['slug'],'/').'/?$','index.php?smarttoolz_video_route=search','top');
        $simple=array('subscriptions'=>'feed/'.$s['subscriptions']['slug'],'watch-later'=>'feed/'.$s['watch-later']['slug'],'history'=>'feed/'.$s['history']['slug'],'liked-videos'=>'feed/'.$s['liked-videos']['slug']);
        foreach($simple as $key=>$route){if(smarttoolz_video_route_enabled($key))add_rewrite_rule('^'.preg_quote($base,'/').'/'.preg_quote($route,'/').'/?$','index.php?smarttoolz_video_route='.$key,'top');}
        foreach(array('playlists','your-videos','trending','explore','live','memberships','purchases','account','login','signup','forgot-password','settings','notifications','upload') as $key){if(smarttoolz_video_route_enabled($key))add_rewrite_rule('^'.preg_quote($base,'/').'/'.preg_quote($s[$key]['slug'],'/').'/?$','index.php?smarttoolz_video_route='.$key,'top');}
    } else {
        if(smarttoolz_video_route_enabled('home'))add_rewrite_rule('^'.preg_quote($base,'/').'/?$','index.php?smarttoolz_video_route=home','top');
        if(smarttoolz_video_route_enabled('watch'))add_rewrite_rule('^'.preg_quote($base,'/').'/'.preg_quote($s['watch']['slug'],'/').'/([^/]+)/?$','index.php?smarttoolz_video_route=watch&smarttoolz_video_id=$matches[1]','top');
        foreach(smarttoolz_video_route_defaults() as $key=>$route){if(in_array($key,array('home','watch','channel-videos','channel-shorts','channel-live','edit-profile','comments','badges','podcasts','privacy'),true)||!smarttoolz_video_route_enabled($key))continue;$p=smarttoolz_video_routing_preview_path($key,$s);if($p)add_rewrite_rule('^'.preg_quote($p,'/').'/?$','index.php?smarttoolz_video_route='.$key,'top');}
        if(smarttoolz_video_route_enabled('channel'))foreach(array('channel-videos','channel-shorts','channel-live') as $key){if(smarttoolz_video_route_enabled($key))add_rewrite_rule('^'.preg_quote(smarttoolz_video_routing_preview_path($key,$s),'/').'/?$','index.php?smarttoolz_video_route='.$key,'top');}
        if(smarttoolz_video_route_enabled('account'))foreach(array('edit-profile','comments','badges','podcasts','privacy') as $key){if(smarttoolz_video_route_enabled($key))add_rewrite_rule('^'.preg_quote(smarttoolz_video_routing_preview_path($key,$s),'/').'/?$','index.php?smarttoolz_video_route=account-'.$key,'top');}
    }
}
add_action('init','smarttoolz_video_register_routes');
function smarttoolz_video_register_query_vars($vars){$vars[]='smarttoolz_video_route';$vars[]='smarttoolz_video_id';$vars[]='smarttoolz_video_channel';$vars[]='v';$vars[]='search_query';$vars[]='list';return $vars;}
add_filter('query_vars','smarttoolz_video_register_query_vars');
function smarttoolz_video_is_route(){return(bool)get_query_var('smarttoolz_video_route');}
function smarttoolz_video_route_url($route='home',$id=''){
    $s=smarttoolz_video_route_settings();$base=home_url('/'.trim($s['home']['slug'],'/').'/');
    if('youtube'===smarttoolz_video_route_mode()){
        if('watch'===$route&&''!==$id)return add_query_arg('v',(string)$id,trailingslashit($base.trim($s['watch']['slug'],'/').'/'));
        if('shorts'===$route&&''!==$id)return trailingslashit($base.trim($s['shorts']['slug'],'/').'/'.rawurlencode((string)$id));
        if(in_array($route,array('channel','channel-videos','channel-shorts','channel-live'),true)){
            $handle='';
            if('channel'===$route && ''!==$id)$handle=(string)$id;
            if(''===$handle && is_user_logged_in())$handle=wp_get_current_user()->user_nicename;
            if(''!==$handle){$handle=ltrim($handle,'@');$suffix='';if('channel-videos'===$route)$suffix='/'.trim($s['channel-videos']['slug'],'/');elseif('channel-shorts'===$route)$suffix='/'.trim($s['channel-shorts']['slug'],'/');elseif('channel-live'===$route)$suffix='/'.trim($s['channel-live']['slug'],'/');return trailingslashit($base.'@'.sanitize_title($handle).$suffix);}
        }
        $map=array('home'=>'','subscriptions'=>'feed/'.$s['subscriptions']['slug'],'search'=>$s['search']['slug'],'playlists'=>$s['playlists']['slug'],'watch-later'=>'feed/'.$s['watch-later']['slug'],'history'=>'feed/'.$s['history']['slug'],'liked-videos'=>'feed/'.$s['liked-videos']['slug'],'your-videos'=>$s['your-videos']['slug'],'trending'=>$s['trending']['slug'],'explore'=>$s['explore']['slug'],'live'=>$s['live']['slug'],'memberships'=>$s['memberships']['slug'],'purchases'=>$s['purchases']['slug'],'account'=>$s['account']['slug'],'edit-profile'=>$s['account']['slug'].'/'.$s['edit-profile']['slug'],'comments'=>$s['account']['slug'].'/'.$s['comments']['slug'],'badges'=>$s['account']['slug'].'/'.$s['badges']['slug'],'podcasts'=>$s['account']['slug'].'/'.$s['podcasts']['slug'],'privacy'=>$s['account']['slug'].'/'.$s['privacy']['slug'],'login'=>$s['login']['slug'],'signup'=>$s['signup']['slug'],'forgot-password'=>$s['forgot-password']['slug'],'settings'=>$s['settings']['slug'],'notifications'=>$s['notifications']['slug'],'upload'=>$s['upload']['slug']);
        if(isset($map[$route]))return trailingslashit($base.trim($map[$route],'/').'/');
    }
    if('watch'===$route&&''!==$id)return trailingslashit($base.trim($s['watch']['slug'],'/').'/'.rawurlencode((string)$id));
    if(isset($s[$route]))return trailingslashit(home_url('/'.smarttoolz_video_routing_preview_path($route,$s).'/'));
    $legacy=array('account/edit-profile'=>'edit-profile','account/comments'=>'comments','account/badges'=>'badges','account/podcasts'=>'podcasts','account/privacy'=>'privacy');if(isset($legacy[$route])&&isset($s[$legacy[$route]]))return trailingslashit(home_url('/'.smarttoolz_video_routing_preview_path($legacy[$route],$s).'/'));return trailingslashit($base.trim(str_replace('-','/',(string)$route),'/').'/');
}
function smarttoolz_video_activate_plugin(){smarttoolz_video_register_routes();flush_rewrite_rules();smarttoolz_video_sync_pages();smarttoolz_video_page_sync_cron();smarttoolz_video_set_static_homepage(false);}
function smarttoolz_video_deactivate_plugin(){wp_clear_scheduled_hook('smarttoolz_video_page_sync_event');flush_rewrite_rules();}
add_action('init','smarttoolz_video_sync_pages',20);add_action('init','smarttoolz_video_page_sync_cron',21);
