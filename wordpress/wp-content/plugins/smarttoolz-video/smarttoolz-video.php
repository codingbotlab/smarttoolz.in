<?php
/**
 * Plugin Name: SmartToolz Video
 * Plugin URI: https://smarttoolz.in/
 * Description: YouTube-style video sharing platform for SmartToolz WordPress.
 * Version: 2.4.0
 * Author: SmartToolz
 * License: GPL-2.0-or-later
 * Text Domain: smarttoolz-video
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'SMARTTOOLZ_VIDEO_VERSION', '2.4.0' );
define( 'SMARTTOOLZ_VIDEO_FILE', __FILE__ );
define( 'SMARTTOOLZ_VIDEO_DIR', plugin_dir_path( __FILE__ ) );
define( 'SMARTTOOLZ_VIDEO_URL', plugin_dir_url( __FILE__ ) );

function smarttoolz_video_register_post_type() {
    register_post_type( 'st_video', array(
        'labels' => array('name'=>__('Videos','smarttoolz-video'),'singular_name'=>__('Video','smarttoolz-video'),'add_new'=>__('Add Video','smarttoolz-video'),'add_new_item'=>__('Add New Video','smarttoolz-video'),'edit_item'=>__('Edit Video','smarttoolz-video'),'new_item'=>__('New Video','smarttoolz-video'),'view_item'=>__('View Video','smarttoolz-video'),'search_items'=>__('Search Videos','smarttoolz-video'),'not_found'=>__('No videos found.','smarttoolz-video'),'menu_name'=>__('SmartToolz Videos','smarttoolz-video')),
        'public'=>true,'publicly_queryable'=>true,'show_ui'=>true,'show_in_rest'=>true,'menu_icon'=>'dashicons-video-alt3','supports'=>array('title','editor','thumbnail','author','comments'),'has_archive'=>'videos','rewrite'=>array('slug'=>'videos','with_front'=>false,'feeds'=>true,'pages'=>true),'query_var'=>'st_video','capability_type'=>'post','map_meta_cap'=>true,
    ) );
}
add_action( 'init', 'smarttoolz_video_register_post_type' );

function smarttoolz_video_register_rewrites( $rules ) {
    $custom = array(
        '^videos/([^/]+)/?$'            => 'index.php?post_type=st_video&name=$matches[1]',
        '^index\\.php/videos/([^/]+)/?$' => 'index.php?post_type=st_video&name=$matches[1]',
    );
    return $custom + $rules;
}
add_filter( 'rewrite_rules_array', 'smarttoolz_video_register_rewrites', 20 );

function smarttoolz_video_filter_post_type_link( $post_link, $post ) {
    if ( 'st_video' === $post->post_type ) {
        return home_url( user_trailingslashit( 'videos/' . $post->post_name ) );
    }
    return $post_link;
}
add_filter( 'post_type_link', 'smarttoolz_video_filter_post_type_link', 10, 2 );

function smarttoolz_video_register_taxonomy() { register_taxonomy('st_video_category','st_video',array('labels'=>array('name'=>__('Video Categories','smarttoolz-video'),'singular_name'=>__('Video Category','smarttoolz-video')),'public'=>true,'show_ui'=>true,'show_in_rest'=>true,'hierarchical'=>true,'rewrite'=>array('slug'=>'video-category','with_front'=>false))); }
add_action( 'init', 'smarttoolz_video_register_taxonomy' );
function smarttoolz_video_register_meta() {
    register_post_meta('st_video','_st_video_source',array('type'=>'string','single'=>true,'show_in_rest'=>true,'sanitize_callback'=>'esc_url_raw','auth_callback'=>function(){return current_user_can('edit_posts');}));
    register_post_meta('st_video','_st_video_duration',array('type'=>'string','single'=>true,'show_in_rest'=>true,'sanitize_callback'=>'sanitize_text_field','auth_callback'=>function(){return current_user_can('edit_posts');}));
    register_post_meta('st_video','_st_video_views',array('type'=>'integer','single'=>true,'default'=>0,'show_in_rest'=>true,'sanitize_callback'=>'absint','auth_callback'=>function(){return current_user_can('edit_posts');}));
}
add_action( 'init', 'smarttoolz_video_register_meta' );
function smarttoolz_video_admin_box() { add_meta_box('st_video_details',__('Video Details','smarttoolz-video'),'smarttoolz_video_render_admin_box','st_video','normal','high'); }
add_action('add_meta_boxes','smarttoolz_video_admin_box');
function smarttoolz_video_render_admin_box($post){ wp_nonce_field('st_video_save','st_video_nonce'); $source=get_post_meta($post->ID,'_st_video_source',true); $duration=get_post_meta($post->ID,'_st_video_duration',true); ?>
<p><label for="st_video_source"><strong><?php esc_html_e('Video URL','smarttoolz-video'); ?></strong></label><input id="st_video_source" name="st_video_source" type="url" class="widefat" value="<?php echo esc_attr($source); ?>" placeholder="https://.../video.mp4 or embed URL"></p>
<p><label for="st_video_duration"><strong><?php esc_html_e('Duration','smarttoolz-video'); ?></strong></label><input id="st_video_duration" name="st_video_duration" type="text" class="widefat" value="<?php echo esc_attr($duration); ?>" placeholder="12:34"></p>
<p><strong><?php esc_html_e('Thumbnail','smarttoolz-video'); ?></strong></p>
<p><?php esc_html_e('When a video is uploaded without a featured image, SmartToolz can automatically use a frame from the video as the thumbnail. You can also set a featured image manually.','smarttoolz-video'); ?></p>
<?php }
function smarttoolz_video_save_meta($post_id){ if(!isset($_POST['st_video_nonce'])||!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['st_video_nonce'])),'st_video_save'))return; if(defined('DOING_AUTOSAVE')&&DOING_AUTOSAVE)return; if(!current_user_can('edit_post',$post_id)||'st_video'!==get_post_type($post_id))return; if(isset($_POST['st_video_source']))update_post_meta($post_id,'_st_video_source',esc_url_raw(wp_unslash($_POST['st_video_source']))); if(isset($_POST['st_video_duration']))update_post_meta($post_id,'_st_video_duration',sanitize_text_field(wp_unslash($_POST['st_video_duration']))); }
add_action('save_post_st_video','smarttoolz_video_save_meta');

function smarttoolz_video_create_thumbnail_attachment($post_id, $filename, $binary) {
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';
    $uploads = wp_upload_dir();
    if ( ! empty( $uploads['error'] ) ) { return 0; }
    $filename = sanitize_file_name( $filename );
    if ( ! $filename ) { $filename = 'smarttoolz-video-thumbnail.jpg'; }
    $saved = wp_upload_bits( $filename, null, $binary );
    if ( ! empty( $saved['error'] ) ) { return 0; }
    $filetype = wp_check_filetype( $saved['file'], null );
    $attachment_id = wp_insert_attachment(array(
        'post_mime_type' => $filetype['type'] ?: 'image/jpeg',
        'post_title' => sanitize_text_field( pathinfo($filename, PATHINFO_FILENAME) ),
        'post_content' => '',
        'post_status' => 'inherit',
    ), $saved['file'], $post_id, true);
    if ( is_wp_error( $attachment_id ) ) { return 0; }
    $metadata = wp_generate_attachment_metadata( $attachment_id, $saved['file'] );
    if ( $metadata ) { wp_update_attachment_metadata( $attachment_id, $metadata ); }
    set_post_thumbnail( $post_id, $attachment_id );
    return (int) $attachment_id;
}

function smarttoolz_video_attach_thumbnail_file( $post_id, $file ) {
    if ( empty($file['tmp_name']) || ! empty($file['error']) ) { return 0; }
    $allowed = array('image/jpeg','image/png','image/webp');
    $type = isset($file['type']) ? sanitize_mime_type($file['type']) : '';
    if ( ! in_array($type, $allowed, true) ) { return 0; }
    $binary = file_get_contents($file['tmp_name']);
    if ( false === $binary ) { return 0; }
    return smarttoolz_video_create_thumbnail_attachment($post_id, $file['name'], $binary);
}

function smarttoolz_video_attach_thumbnail_data( $post_id, $data_url ) {
    if ( ! is_string($data_url) || ! preg_match('#^data:image/(jpeg|jpg);base64,(.+)$#', $data_url, $m) ) { return 0; }
    $binary = base64_decode($m[2], true);
    if ( false === $binary || strlen($binary) < 100 ) { return 0; }
    return smarttoolz_video_create_thumbnail_attachment($post_id, 'smarttoolz-video-thumbnail-' . $post_id . '.jpg', $binary);
}

function smarttoolz_video_frontend_upload(){
    if(!is_user_logged_in()||!isset($_POST['st_video_frontend_action']))return;
    if(!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['st_video_frontend_nonce']??'')),'st_video_frontend_upload'))return;
    if(!current_user_can('publish_posts'))return;
    $title=isset($_POST['st_video_title'])?sanitize_text_field(wp_unslash($_POST['st_video_title'])):'';
    $description=isset($_POST['st_video_description'])?wp_kses_post(wp_unslash($_POST['st_video_description'])):'';
    $source_url=isset($_POST['st_video_url'])?esc_url_raw(wp_unslash($_POST['st_video_url'])):'';
    if(!$title){wp_safe_redirect(add_query_arg('st_video_upload','missing-title',wp_get_referer()?:home_url('/')));exit;}
    $post_id=wp_insert_post(array('post_type'=>'st_video','post_status'=>smarttoolz_video_setting('upload_status','publish'),'post_title'=>$title,'post_content'=>$description,'post_author'=>get_current_user_id()),true);
    if(is_wp_error($post_id)){wp_safe_redirect(add_query_arg('st_video_upload','failed',wp_get_referer()?:home_url('/')));exit;}
    $video_uploaded = false;
    if(!empty($_FILES['st_video_file']['name'])){
        require_once ABSPATH.'wp-admin/includes/file.php';
        $formats = array_filter( array_map( 'trim', explode( ',', smarttoolz_video_setting('allowed_formats','mp4,webm,ogg') ) ) );
        $mime_map = array('mp4'=>'video/mp4','webm'=>'video/webm','ogg'=>'video/ogg');
        $mimes = array(); foreach($formats as $ext){if(isset($mime_map[$ext]))$mimes[$ext]=$mime_map[$ext];}
        $upload=wp_handle_upload($_FILES['st_video_file'],array('test_form'=>false,'mimes'=>$mimes,'test_type'=>true));
        if(isset($upload['error'])){wp_delete_post($post_id,true);wp_safe_redirect(add_query_arg('st_video_upload','file-error',wp_get_referer()?:home_url('/')));exit;}
        update_post_meta($post_id,'_st_video_source',esc_url_raw($upload['url']));
        $video_uploaded = true;
    } elseif($source_url){
        update_post_meta($post_id,'_st_video_source',$source_url);
    } else {
        wp_delete_post($post_id,true);wp_safe_redirect(add_query_arg('st_video_upload','missing-video',wp_get_referer()?:home_url('/')));exit;
    }
    $thumb_attached = false;
    if ( ! empty($_FILES['st_video_thumbnail_file']['name']) ) {
        $thumb_attached = (bool) smarttoolz_video_attach_thumbnail_file( $post_id, $_FILES['st_video_thumbnail_file'] );
    }
    if ( ! $thumb_attached && ! empty($_POST['st_video_thumbnail']) ) {
        $thumb_attached = (bool) smarttoolz_video_attach_thumbnail_data( $post_id, wp_unslash($_POST['st_video_thumbnail']) );
    }
    if ( smarttoolz_video_setting('require_thumbnail',0) && ! $thumb_attached && ! has_post_thumbnail($post_id) ) {
        wp_delete_post($post_id,true);wp_safe_redirect(add_query_arg('st_video_upload','missing-thumbnail',wp_get_referer()?:home_url('/')));exit;
    }
    if ( ! $thumb_attached && ! has_post_thumbnail($post_id) ) {
        update_post_meta($post_id, '_st_video_thumbnail_pending', $video_uploaded ? '1' : '0');
    }
    wp_safe_redirect(add_query_arg('st_video_upload','success',get_permalink($post_id)));exit;
}
add_action('template_redirect','smarttoolz_video_frontend_upload',1);

function smarttoolz_video_auto_thumbnail_on_source_save( $post_id, $post, $update ) {
    if ( 'st_video' !== $post->post_type || wp_is_post_revision($post_id) || has_post_thumbnail($post_id) ) { return; }
    $source = get_post_meta($post_id, '_st_video_source', true);
    if ( ! $source ) { return; }
    if ( ! get_post_meta($post_id, '_st_video_thumbnail_pending', true) ) {
        update_post_meta($post_id, '_st_video_thumbnail_pending', '1');
    }
}
add_action('save_post_st_video','smarttoolz_video_auto_thumbnail_on_source_save',30,3);

function smarttoolz_video_upload_shortcode(){ if(!is_user_logged_in())return '<div class="stv-upload-page"><div class="stv-upload-card stv-upload-login"><div class="stv-upload-icon">↥</div><h1>Sign in to upload</h1><p>Please log in to publish videos on SmartToolz.</p></div></div>'; if(!current_user_can('publish_posts'))return '<div class="stv-upload-page"><div class="stv-upload-card stv-upload-login"><div class="stv-upload-icon">!</div><h1>Uploads are not enabled</h1><p>Your account is not allowed to publish videos yet.</p></div></div>'; $status=isset($_GET['st_video_upload'])?sanitize_key(wp_unslash($_GET['st_video_upload'])):''; ob_start(); ?>
<div class="stv-upload-page"><div class="stv-upload-head"><div><span class="stv-eyebrow">Creator</span><h1>Upload a video</h1><p>Share your next video with the SmartToolz community.</p></div><a class="stv-upload-back" href="<?php echo esc_url(home_url('/video-home/')); ?>">Back to videos</a></div><?php if('success'===$status): ?><div class="stv-upload-success"><strong>Video published.</strong> Your video is now live.</div><?php elseif(in_array($status,array('failed','file-error','missing-video','missing-title','missing-thumbnail'),true)): ?><div class="stv-upload-error">We could not publish that video. Please check the title, video file or URL, and thumbnail requirement.</div><?php elseif('disabled'===$status): ?><div class="stv-upload-error">Frontend video uploads are currently disabled by the site administrator.</div><?php endif; ?><form class="stv-video-upload-form" method="post" enctype="multipart/form-data"><?php wp_nonce_field('st_video_frontend_upload','st_video_frontend_nonce'); ?><input type="hidden" name="st_video_frontend_action" value="upload"><input type="hidden" name="st_video_thumbnail" value="" data-stv-thumbnail-data><div class="stv-upload-main"><div class="stv-upload-section"><label class="stv-field"><span>Title</span><input name="st_video_title" type="text" maxlength="180" placeholder="Add a title that describes your video" required></label><label class="stv-field"><span>Description</span><textarea name="st_video_description" rows="7" placeholder="Tell viewers what your video is about..."></textarea></label></div><div class="stv-upload-section"><div class="stv-dropzone"><div class="stv-upload-preview" data-stv-video-preview hidden><video muted playsinline preload="metadata"></video></div><div class="stv-drop-icon">▶</div><strong>Select a video to upload</strong><p>MP4, WebM or OGG · Generate three thumbnails and select your favorite.</p><label class="stv-file-button">Choose video<input name="st_video_file" type="file" accept="video/mp4,video/webm,video/ogg" data-stv-video-upload></label><span class="stv-selected-file" data-stv-file-name>No file selected</span><div class="stv-thumbnail-row"><label class="stv-file-button stv-thumbnail-button">Use custom thumbnail<input name="st_video_thumbnail_file" type="file" accept="image/jpeg,image/png,image/webp"></label><span>Optional unless the administrator requires a thumbnail.</span></div></div><div class="stv-or"><span>OR</span></div><label class="stv-field"><span>Video URL</span><input name="st_video_url" type="url" placeholder="https://example.com/video.mp4"></label><p class="stv-url-note">For external URLs, browser security may prevent automatic frame capture.</p></div></div><div class="stv-upload-footer"><span>Thumbnail and moderation rules are controlled in Video Settings.</span><button type="submit" class="stv-publish-button">Publish video</button></div></form></div>
<?php return ob_get_clean(); }
add_shortcode('smarttoolz_video_upload','smarttoolz_video_upload_shortcode');
require_once SMARTTOOLZ_VIDEO_DIR . 'includes/admin-settings.php';
require_once SMARTTOOLZ_VIDEO_DIR . 'includes/platform.php';
require_once SMARTTOOLZ_VIDEO_DIR . 'includes/auth.php';

function smarttoolz_video_create_page( $slug, $title, $content ) { $existing = get_page_by_path( $slug, OBJECT, 'page' ); if ( $existing ) { return (int) $existing->ID; } $page_id = wp_insert_post(array('post_type'=>'page','post_status'=>'publish','post_title'=>$title,'post_name'=>$slug,'post_content'=>$content,'post_author'=>get_current_user_id() ?: 1),true); return is_wp_error($page_id) ? 0 : (int)$page_id; }
function smarttoolz_video_create_platform_pages() { $pages=array('video-home'=>array('title'=>'Video Home','content'=>'[smarttoolz_video_platform]'),'video-upload'=>array('title'=>'Upload Video','content'=>'[smarttoolz_video_upload]'),'creator-studio'=>array('title'=>'Creator Studio','content'=>'[smarttoolz_creator_dashboard]'),'channel'=>array('title'=>'Channel','content'=>'[smarttoolz_channel]')); $page_ids=array(); foreach($pages as $slug=>$page){$page_ids[$slug]=smarttoolz_video_create_page($slug,$page['title'],$page['content']);} update_option('smarttoolz_video_page_ids',$page_ids,false); }
function smarttoolz_video_refresh_rewrites() { $version = '2.4.0'; if ( get_option( 'smarttoolz_video_rewrite_version' ) === $version ) { return; } smarttoolz_video_register_post_type(); smarttoolz_video_register_taxonomy(); flush_rewrite_rules( false ); update_option( 'smarttoolz_video_rewrite_version', $version, false ); }
add_action( 'init', 'smarttoolz_video_refresh_rewrites', 99 );
function smarttoolz_video_activation(){smarttoolz_video_register_post_type();smarttoolz_video_register_taxonomy();smarttoolz_video_create_platform_pages();flush_rewrite_rules(true);update_option('smarttoolz_video_rewrite_version','2.4.0',false);}
register_activation_hook(__FILE__,'smarttoolz_video_activation');
function smarttoolz_video_deactivation(){flush_rewrite_rules(true);delete_option('smarttoolz_video_rewrite_version');}
register_deactivation_hook(__FILE__,'smarttoolz_video_deactivation');
