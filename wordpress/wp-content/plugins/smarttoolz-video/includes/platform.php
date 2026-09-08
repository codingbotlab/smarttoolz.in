<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Likes / dislikes */
function stv_platform_meta() {
    register_post_meta( 'st_video', '_st_likes', array( 'type'=>'integer','single'=>true,'default'=>0,'show_in_rest'=>true,'sanitize_callback'=>'absint','auth_callback'=>function(){return current_user_can('edit_posts');} ) );
    register_post_meta( 'st_video', '_st_dislikes', array( 'type'=>'integer','single'=>true,'default'=>0,'show_in_rest'=>true,'sanitize_callback'=>'absint','auth_callback'=>function(){return current_user_can('edit_posts');} ) );
}
add_action( 'init', 'stv_platform_meta' );

function stv_user_liked( $post_id ) { return is_user_logged_in() ? (bool) get_user_meta( get_current_user_id(), 'stv_liked_' . absint($post_id), true ) : false; }
function stv_user_disliked( $post_id ) { return is_user_logged_in() ? (bool) get_user_meta( get_current_user_id(), 'stv_disliked_' . absint($post_id), true ) : false; }

function stv_ajax_reaction() {
    check_ajax_referer( 'stv_platform', 'nonce' );
    if ( ! is_user_logged_in() ) wp_send_json_error( array('message'=>'login') , 401 );
    $id = isset($_POST['video_id']) ? absint($_POST['video_id']) : 0;
    $type = isset($_POST['type']) ? sanitize_key($_POST['type']) : '';
    if ( ! $id || get_post_type($id) !== 'st_video' || ! in_array($type,array('like','dislike'),true) ) wp_send_json_error(array('message'=>'invalid'),400);
    $liked = stv_user_liked($id); $disliked = stv_user_disliked($id);
    if ( $type === 'like' ) {
        if ($liked) { delete_user_meta(get_current_user_id(),'stv_liked_'.$id); update_post_meta($id,'_st_likes',max(0,(int)get_post_meta($id,'_st_likes',true)-1)); }
        else { update_user_meta(get_current_user_id(),'stv_liked_'.$id,1); if($disliked){delete_user_meta(get_current_user_id(),'stv_disliked_'.$id); update_post_meta($id,'_st_dislikes',max(0,(int)get_post_meta($id,'_st_dislikes',true)-1));} update_post_meta($id,'_st_likes',(int)get_post_meta($id,'_st_likes',true)+1); }
    } else {
        if ($disliked) { delete_user_meta(get_current_user_id(),'stv_disliked_'.$id); update_post_meta($id,'_st_dislikes',max(0,(int)get_post_meta($id,'_st_dislikes',true)-1)); }
        else { update_user_meta(get_current_user_id(),'stv_disliked_'.$id,1); if($liked){delete_user_meta(get_current_user_id(),'stv_liked_'.$id); update_post_meta($id,'_st_likes',max(0,(int)get_post_meta($id,'_st_likes',true)-1));} update_post_meta($id,'_st_dislikes',(int)get_post_meta($id,'_st_dislikes',true)+1); }
    }
    wp_send_json_success(array('likes'=>(int)get_post_meta($id,'_st_likes',true),'dislikes'=>(int)get_post_meta($id,'_st_dislikes',true),'liked'=>stv_user_liked($id),'disliked'=>stv_user_disliked($id)));
}
add_action('wp_ajax_stv_reaction','stv_ajax_reaction');

/* Subscriptions */
function stv_subscribed($author_id){ return is_user_logged_in() ? (bool)get_user_meta(get_current_user_id(),'stv_subscribed_'.$author_id,true) : false; }
function stv_ajax_subscribe(){
    check_ajax_referer('stv_platform','nonce');
    if(!is_user_logged_in()) wp_send_json_error(array('message'=>'login'),401);
    $author=isset($_POST['author_id'])?absint($_POST['author_id']):0;
    if(!$author || !get_user_by('id',$author) || $author===get_current_user_id()) wp_send_json_error(array('message'=>'invalid'),400);
    $key='stv_subscribed_'.$author; $is=stv_subscribed($author);
    if($is){delete_user_meta(get_current_user_id(),$key); $count=max(0,(int)get_user_meta($author,'stv_subscriber_count',true)-1); update_user_meta($author,'stv_subscriber_count',$count);}
    else{update_user_meta(get_current_user_id(),$key,1); $count=(int)get_user_meta($author,'stv_subscriber_count',true)+1; update_user_meta($author,'stv_subscriber_count',$count);}
    wp_send_json_success(array('subscribed'=>!$is,'count'=>(int)get_user_meta($author,'stv_subscriber_count',true)));
}
add_action('wp_ajax_stv_subscribe','stv_ajax_subscribe');

/* Platform feed / search / trending / channel / creator dashboard */
function stv_video_card( $post_id ) {
    $thumb=get_the_post_thumbnail_url($post_id,'medium_large'); $author=get_userdata(get_post_field('post_author',$post_id));
    $views=(int)get_post_meta($post_id,'_st_video_views',true); $duration=get_post_meta($post_id,'_st_video_duration',true);
    $name=$author ? $author->display_name : __('Creator','smarttoolz-video');
    ob_start(); ?>
    <article class="stv-card"><a class="stv-thumb" href="<?php echo esc_url(get_permalink($post_id)); ?>"><?php if($thumb): ?><img src="<?php echo esc_url($thumb); ?>" alt="<?php echo esc_attr(get_the_title($post_id)); ?>" loading="lazy"><?php else: ?><span class="stv-thumb-empty">▶</span><?php endif; ?><?php if($duration): ?><b class="stv-duration"><?php echo esc_html($duration); ?></b><?php endif; ?></a><div class="stv-card-body"><a class="stv-card-title" href="<?php echo esc_url(get_permalink($post_id)); ?>"><?php echo esc_html(get_the_title($post_id)); ?></a><div class="stv-card-author"><?php echo esc_html($name); ?></div><div class="stv-card-meta"><?php echo esc_html(number_format_i18n($views)); ?> views · <?php echo esc_html(get_the_date('', $post_id)); ?></div></div></article>
    <?php return ob_get_clean();
}

function stv_render_feed($args=array()){
    $args=wp_parse_args($args,array('search'=>'','author'=>0,'category'=>'','orderby'=>'date','paged'=>1,'per_page'=>12));
    $q=array('post_type'=>'st_video','post_status'=>'publish','posts_per_page'=>(int)$args['per_page'],'paged'=>(int)$args['paged'],'ignore_sticky_posts'=>true);
    if($args['search']!=='') $q['s']=sanitize_text_field($args['search']);
    if($args['author']) $q['author']=absint($args['author']);
    if($args['category']!=='') $q['tax_query']=array(array('taxonomy'=>'st_video_category','field'=>'slug','terms'=>sanitize_title($args['category'])));
    if($args['orderby']==='views'){$q['meta_key']='_st_video_views';$q['orderby']='meta_value_num';$q['order']='DESC';}
    elseif($args['orderby']==='likes'){$q['meta_key']='_st_likes';$q['orderby']='meta_value_num';$q['order']='DESC';}
    else{$q['orderby']='date';$q['order']='DESC';}
    $loop=new WP_Query($q); ob_start();
    if($loop->have_posts()){echo '<div class="stv-grid">';while($loop->have_posts()){$loop->the_post();echo stv_video_card(get_the_ID());}echo '</div>';
        echo '<div class="stv-pagination">'.paginate_links(array('total'=>$loop->max_num_pages,'current'=>max(1,(int)$args['paged']),'type'=>'list')).'</div>';
    }else{echo '<div class="stv-empty">'.esc_html__('No videos found.','smarttoolz-video').'</div>';}
    wp_reset_postdata(); return ob_get_clean();
}

function stv_platform_shortcode($atts=array()){
    $atts=shortcode_atts(array('search'=>'','category'=>'','orderby'=>'date'),$atts,'smarttoolz_video_platform');
    $search=isset($_GET['stv_search'])?sanitize_text_field(wp_unslash($_GET['stv_search'])):$atts['search']; $paged=max(1,get_query_var('paged'),get_query_var('page'));
    ob_start(); ?>
    <div class="stv-platform"><div class="stv-topbar"><form method="get" class="stv-search"><input type="search" name="stv_search" value="<?php echo esc_attr($search); ?>" placeholder="Search videos..." aria-label="Search videos"><button type="submit">Search</button></form><nav class="stv-tabs"><a href="<?php echo esc_url(remove_query_arg('stv_search')); ?>">Home</a><a href="<?php echo esc_url(add_query_arg('stv_sort','views')); ?>">Trending</a><?php if(is_user_logged_in()): ?><a href="<?php echo esc_url(add_query_arg('stv_page','dashboard')); ?>">Creator Studio</a><?php endif; ?><a href="<?php echo esc_url(add_query_arg('stv_page','upload')); ?>">Upload</a></nav></div><?php
    $sort=(isset($_GET['stv_sort'])&&$_GET['stv_sort']==='views')?'views':'date'; echo '<section class="stv-section"><h2>'.($search?esc_html__('Search results','smarttoolz-video'):($sort==='views'?esc_html__('Trending','smarttoolz-video'):esc_html__('Latest videos','smarttoolz-video'))).'</h2>'; echo stv_render_feed(array('search'=>$search,'orderby'=>$sort,'paged'=>$paged)); echo '</section></div>'; return ob_get_clean();
}
add_shortcode('smarttoolz_video_platform','stv_platform_shortcode');
add_shortcode('smarttoolz_video_feed','stv_platform_shortcode');

function stv_channel_shortcode($atts=array()){
    $atts=shortcode_atts(array('user'=>0),$atts,'smarttoolz_channel'); $user_id=absint($atts['user']);
    if(!$user_id && isset($_GET['stv_channel'])){ $u=get_user_by('slug',sanitize_title(wp_unslash($_GET['stv_channel']))); if($u)$user_id=$u->ID; }
    if(!$user_id)$user_id=is_user_logged_in()?get_current_user_id():0; if(!$user_id)return '<div class="stv-empty">Creator not found.</div>';
    $u=get_userdata($user_id); $count=(int)get_user_meta($user_id,'stv_subscriber_count',true); $avatar=get_avatar_url($user_id,array('size'=>128));
    ob_start(); ?><div class="stv-channel"><div class="stv-channel-head"><img src="<?php echo esc_url($avatar); ?>" alt=""><div><h1><?php echo esc_html($u->display_name); ?></h1><p>@<?php echo esc_html($u->user_nicename); ?> · <?php echo esc_html(number_format_i18n($count)); ?> subscribers</p></div><?php if(is_user_logged_in()&&$user_id!==get_current_user_id()): ?><button class="stv-subscribe" data-author="<?php echo esc_attr($user_id); ?>"><?php echo stv_subscribed($user_id)?'Subscribed':'Subscribe'; ?></button><?php endif; ?></div><div class="stv-channel-tabs"><strong>Videos</strong><a href="<?php echo esc_url(add_query_arg('stv_channel',$u->user_nicename)); ?>">Videos</a></div><?php echo stv_render_feed(array('author'=>$user_id)); ?></div><?php return ob_get_clean();
}
add_shortcode('smarttoolz_channel','stv_channel_shortcode');

function stv_creator_dashboard_shortcode(){
    if(!is_user_logged_in())return '<div class="stv-login">Please log in to open Creator Studio.</div>';
    $uid=get_current_user_id(); $videos=get_posts(array('post_type'=>'st_video','post_status'=>array('publish','draft','pending'),'author'=>$uid,'posts_per_page'=>50)); $total=0; foreach($videos as $v)$total+=(int)get_post_meta($v->ID,'_st_video_views',true);
    ob_start(); ?><div class="stv-dashboard"><div class="stv-dashboard-head"><div><span>Creator Studio</span><h1>Welcome, <?php echo esc_html(wp_get_current_user()->display_name); ?></h1></div><a class="stv-upload-cta" href="<?php echo esc_url(add_query_arg('stv_page','upload')); ?>">Upload video</a></div><div class="stv-stats"><div><b><?php echo esc_html(count($videos)); ?></b><span>Videos</span></div><div><b><?php echo esc_html(number_format_i18n($total)); ?></b><span>Views</span></div><div><b><?php echo esc_html(number_format_i18n((int)get_user_meta($uid,'stv_subscriber_count',true))); ?></b><span>Subscribers</span></div></div><h2>Your videos</h2><div class="stv-dashboard-list"><?php foreach($videos as $v): ?><a href="<?php echo esc_url(get_edit_post_link($v->ID)); ?>"><span><?php echo esc_html(get_the_title($v->ID)); ?></span><small><?php echo esc_html(ucfirst($v->post_status)); ?> · <?php echo esc_html(number_format_i18n((int)get_post_meta($v->ID,'_st_video_views',true))); ?> views</small></a><?php endforeach; if(!$videos)echo '<p>No videos yet.</p>'; ?></div></div><?php return ob_get_clean();
}
add_shortcode('smarttoolz_creator_dashboard','stv_creator_dashboard_shortcode');

function stv_upload_page_shortcode(){ return function_exists('smarttoolz_video_upload_shortcode') ? smarttoolz_video_upload_shortcode() : ''; }
add_shortcode('smarttoolz_video_upload','stv_upload_page_shortcode');

function stv_platform_assets(){
    if(is_singular('st_video')||is_post_type_archive('st_video')||is_tax('st_video_category')||is_page()){
        wp_localize_script('smarttoolz-video','stvPlatform',array('ajax'=>admin_url('admin-ajax.php'),'nonce'=>wp_create_nonce('stv_platform')));
    }
}
add_action('wp_enqueue_scripts','stv_platform_assets',20);
