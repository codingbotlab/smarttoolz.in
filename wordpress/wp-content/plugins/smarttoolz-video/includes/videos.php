<?php
/**
 * SmartToolz video content and frontend creator tools.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_register_content() {
    register_post_type( 'stv_video', array(
        'labels' => array('name'=>'Videos','singular_name'=>'Video','add_new_item'=>'Add New Video','edit_item'=>'Edit Video','new_item'=>'New Video','view_item'=>'View Video','search_items'=>'Search Videos'),
        'public'=>true,'show_ui'=>true,'show_in_menu'=>false,'show_in_rest'=>true,'has_archive'=>false,
        'rewrite'=>array('slug'=>'video/watch'),'supports'=>array('title','editor','author','thumbnail'),
        'capability_type'=>'post','map_meta_cap'=>true,
    ) );
}
add_action( 'init', 'smarttoolz_video_register_content' );

function smarttoolz_video_get_source( $video_id ) { return (string) get_post_meta( $video_id, '_stv_video_source', true ); }
function smarttoolz_video_get_attachment_id( $video_id ) { return absint( get_post_meta( $video_id, '_stv_video_attachment_id', true ) ); }

function smarttoolz_video_delete_owned_video( $video_id, $permanent = true ) {
    $video = get_post( $video_id );
    if ( ! $video || 'stv_video' !== $video->post_type ) return new WP_Error( 'not_found', 'Video not found.' );
    if ( (int) $video->post_author !== get_current_user_id() && ! current_user_can( 'delete_post', $video_id ) ) return new WP_Error( 'forbidden', 'You can only delete your own videos.' );
    $attachment_id = smarttoolz_video_get_attachment_id( $video_id );
    $deleted = wp_delete_post( $video_id, $permanent );
    if ( ! $deleted ) return new WP_Error( 'delete_failed', 'The video could not be deleted.' );
    if ( $attachment_id ) wp_delete_attachment( $attachment_id, true );
    return true;
}

function smarttoolz_video_handle_uploaded_file( $field_name, $existing_attachment = 0 ) {
    if ( empty( $_FILES[ $field_name ]['name'] ) ) return array( 'url'=>'', 'attachment_id'=>0 );
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';
    $allowed = array('video/mp4'=>'mp4','video/webm'=>'webm','video/ogg'=>'ogv','video/quicktime'=>'mov','video/x-msvideo'=>'avi','video/x-matroska'=>'mkv');
    add_filter( 'upload_mimes', 'smarttoolz_video_upload_mimes' );
    $upload = wp_handle_upload( $_FILES[ $field_name ], array('test_form'=>false,'mimes'=>$allowed) );
    remove_filter( 'upload_mimes', 'smarttoolz_video_upload_mimes' );
    if ( isset( $upload['error'] ) ) return new WP_Error( 'upload_error', $upload['error'] );
    $filetype = wp_check_filetype( basename( $upload['file'] ), null );
    $attachment_id = wp_insert_attachment(array('post_mime_type'=>$filetype['type'] ?: 'video/mp4','post_title'=>sanitize_text_field(pathinfo($upload['file'],PATHINFO_FILENAME)),'post_content'=>'','post_status'=>'inherit'), $upload['file']);
    if ( is_wp_error( $attachment_id ) ) { @unlink( $upload['file'] ); return $attachment_id; }
    wp_update_attachment_metadata( $attachment_id, wp_generate_attachment_metadata( $attachment_id, $upload['file'] ) );
    if ( $existing_attachment && $existing_attachment !== $attachment_id ) wp_delete_attachment( $existing_attachment, true );
    return array('url'=>esc_url_raw($upload['url']),'attachment_id'=>absint($attachment_id));
}

function smarttoolz_video_upload_mimes( $mimes ) {
    $mimes['mp4']='video/mp4'; $mimes['webm']='video/webm'; $mimes['ogv']='video/ogg'; $mimes['mov']='video/quicktime'; $mimes['avi']='video/x-msvideo'; $mimes['mkv']='video/x-matroska'; return $mimes;
}

function smarttoolz_video_creator_styles() {
    $route = get_query_var( 'smarttoolz_video_route' );
    if ( ! in_array( $route, array('upload','your-videos'), true ) && 'watch' !== $route ) return;
    echo '<style>
    .stv-creator{max-width:1180px;margin:0 auto}.stv-notice{margin:0 0 18px;padding:12px 14px;border-radius:10px;font-size:14px}.stv-notice--success{background:#17331f;color:#a9efbd;border:1px solid #2d6b40}.stv-notice--error{background:#3a171b;color:#ffb5bd;border:1px solid #79333c}.stv-creator__panel{background:#181818;border:1px solid #303030;border-radius:14px;padding:22px;max-width:850px}.stv-creator__panel h2{margin:0 0 18px}.stv-form{display:grid;gap:15px}.stv-form label{display:grid;gap:7px;color:#ddd;font-size:13px;font-weight:600}.stv-form input[type=text],.stv-form textarea,.stv-form input[type=file]{width:100%;border:1px solid #3a3a3a;border-radius:9px;background:#111;color:#fff;padding:11px 12px}.stv-form textarea{resize:vertical}.stv-form__hint{font-size:12px;color:#929292;font-weight:400}.stv-form__preview{display:block;width:min(100%,720px);max-height:400px;background:#000;border-radius:10px}.stv-form__actions{display:flex;gap:10px;align-items:center}.stv-form__submit,.stv-form__cancel{border:0;border-radius:20px;padding:10px 17px;font-weight:700;cursor:pointer}.stv-form__submit{background:#fff;color:#111}.stv-form__cancel{border:1px solid #444;color:#fff}.stv-video-list{display:grid;gap:12px}.stv-video-list__item{display:grid;grid-template-columns:220px minmax(0,1fr) auto;gap:16px;align-items:center;padding:12px;border:1px solid #2b2b2b;border-radius:12px;background:#151515}.stv-video-list__thumb{aspect-ratio:16/9;border-radius:8px;overflow:hidden;background:#222}.stv-video-list__thumb video{width:100%;height:100%;object-fit:cover}.stv-video-list__info h3{margin:0 0 5px;font-size:16px}.stv-video-list__info p{margin:0 0 7px;color:#aaa;font-size:13px}.stv-video-list__info small{color:#777}.stv-video-list__actions{display:flex;gap:8px;align-items:center}.stv-video-list__actions a,.stv-video-list__actions button{border:1px solid #404040;border-radius:18px;background:transparent;color:#fff;padding:8px 12px;font-size:12px;text-decoration:none;cursor:pointer}.stv-video-list__actions a:hover,.stv-video-list__actions button:hover{background:#2a2a2a}.stv-watch{max-width:1000px}.stv-watch__player{width:100%;max-height:68vh;background:#000;border-radius:12px;display:block;margin-bottom:18px}.stv-watch__meta{color:#999;font-size:13px;margin:8px 0 18px}.stv-watch__description{color:#ccc;line-height:1.7}@media(max-width:760px){.stv-video-list__item{grid-template-columns:1fr}.stv-video-list__actions{flex-wrap:wrap}.stv-creator__panel{padding:16px}}
    </style>';
    if ( 'your-videos' === $route && empty( $_GET['edit'] ) ) {
        echo '<style>.stv-creator__panel{display:none!important}</style>';
    }
}
add_action( 'wp_head', 'smarttoolz_video_creator_styles', 30 );

function smarttoolz_video_manage_page() {
    if ( ! is_user_logged_in() ) { wp_safe_redirect( smarttoolz_video_route_url( 'login' ) ); exit; }
    $user_id=get_current_user_id(); $notice=''; $error='';
    if ( isset($_POST['stv_delete_video'],$_POST['stv_delete_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['stv_delete_nonce'])),'stv_delete_video') ) { $result=smarttoolz_video_delete_owned_video(absint($_POST['stv_delete_video'])); if(is_wp_error($result)){$error=$result->get_error_message();}else{$notice='Video deleted successfully.';} }
    if ( isset($_POST['stv_save_video'],$_POST['stv_video_nonce']) && wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['stv_video_nonce'])),'stv_save_video') ) {
        $video_id=absint($_POST['stv_save_video']); $existing=$video_id?get_post($video_id):null;
        if($existing && (int)$existing->post_author!==$user_id){$error='You can only edit your own videos.';} else {
            $title=sanitize_text_field(wp_unslash($_POST['stv_video_title']??'')); $description=wp_kses_post(wp_unslash($_POST['stv_video_description']??''));
            if(''===$title){$error='Please enter a video title.';}elseif($existing && 'stv_video'!==$existing->post_type){$error='Invalid video.';} else {
                $postarr=array('post_type'=>'stv_video','post_title'=>$title,'post_content'=>$description,'post_status'=>'publish','post_author'=>$user_id); if($existing)$postarr['ID']=$video_id;
                $saved_id=wp_insert_post($postarr,true);
                if(is_wp_error($saved_id)){$error=$saved_id->get_error_message();}else{
                    $video_id=absint($saved_id); $upload=smarttoolz_video_handle_uploaded_file('stv_video_file',$existing?smarttoolz_video_get_attachment_id($video_id):0);
                    if(is_wp_error($upload)){if(!$existing)wp_delete_post($video_id,true);$error=$upload->get_error_message();}else{if(!empty($upload['url'])){update_post_meta($video_id,'_stv_video_source',$upload['url']);update_post_meta($video_id,'_stv_video_attachment_id',$upload['attachment_id']);}$notice=$existing?'Video updated successfully.':'Video uploaded successfully.';}
                }
            }
        }
    }
    $edit_id=isset($_GET['edit'])?absint($_GET['edit']):0; $editing=$edit_id?get_post($edit_id):null; if($editing&&('stv_video'!==$editing->post_type||(int)$editing->post_author!==$user_id)){$editing=null;}
    $videos=get_posts(array('post_type'=>'stv_video','post_status'=>array('publish','draft','pending'),'author'=>$user_id,'posts_per_page'=>50,'orderby'=>'date','order'=>'DESC'));
    ?>
    <section class="stv-creator"><div class="stv-section-heading"><div><h1 class="stv-page-title">Your videos</h1><p class="stv-section-subtitle">Manage the videos on your SmartToolz channel.</p></div><a class="stv-view-link" href="<?php echo esc_url(smarttoolz_video_route_url('channel')); ?>">View channel</a></div>
    <?php if($notice):?><div class="stv-notice stv-notice--success"><?php echo esc_html($notice);?></div><?php endif;?><?php if($error):?><div class="stv-notice stv-notice--error"><?php echo esc_html($error);?></div><?php endif;?>
    <?php if ( 'upload' === get_query_var('smarttoolz_video_route') || $editing ) : ?>
    <div class="stv-creator__panel"><h2><?php echo $editing?'Edit video':'Upload a video';?></h2><form method="post" enctype="multipart/form-data" class="stv-form"><?php wp_nonce_field('stv_save_video','stv_video_nonce');?><input type="hidden" name="stv_save_video" value="<?php echo $editing?esc_attr($editing->ID):'0';?>"><label>Title<input type="text" name="stv_video_title" maxlength="180" required value="<?php echo $editing?esc_attr($editing->post_title):'';?>"></label><label>Description<textarea name="stv_video_description" rows="5"><?php echo $editing?esc_textarea($editing->post_content):'';?></textarea></label><label>Video file<?php if($editing&&smarttoolz_video_get_source($editing->ID)):?><span class="stv-form__hint">Upload a new file only when you want to replace the current video.</span><?php endif;?><input type="file" name="stv_video_file" accept="video/*" <?php echo $editing?'':'required';?>></label><?php if($editing&&smarttoolz_video_get_source($editing->ID)):?><video class="stv-form__preview" controls preload="metadata" src="<?php echo esc_url(smarttoolz_video_get_source($editing->ID));?>"></video><?php endif;?><div class="stv-form__actions"><button class="stv-form__submit" type="submit"><?php echo $editing?'Save changes':'Upload video';?></button><?php if($editing):?><a class="stv-form__cancel" href="<?php echo esc_url(smarttoolz_video_route_url('your-videos'));?>">Cancel</a><?php endif;?></div></form></div>
    <?php endif; ?>
    <div class="stv-section-heading" style="margin-top:28px"><div><h2 class="stv-page-title" style="font-size:22px">Your uploads</h2></div></div>
    <?php if(empty($videos)):?><div class="stv-empty">You have not uploaded any videos yet.</div><?php else:?><div class="stv-video-list"><?php foreach($videos as $video):?><article class="stv-video-list__item"><div class="stv-video-list__thumb"><video preload="metadata" src="<?php echo esc_url(smarttoolz_video_get_source($video->ID));?>"></video></div><div class="stv-video-list__info"><h3><?php echo esc_html($video->post_title);?></h3><p><?php echo esc_html(wp_trim_words(wp_strip_all_tags($video->post_content),24));?></p><small><?php echo esc_html(get_the_date('',$video));?></small></div><div class="stv-video-list__actions"><a href="<?php echo esc_url(smarttoolz_video_route_url('watch',$video->ID));?>">View</a><a href="<?php echo esc_url(add_query_arg('edit',$video->ID,smarttoolz_video_route_url('your-videos')));?>">Edit</a><form method="post" onsubmit="return confirm('Delete this video permanently?');"><?php wp_nonce_field('stv_delete_video','stv_delete_nonce');?><button type="submit" name="stv_delete_video" value="<?php echo esc_attr($video->ID);?>">Delete</button></form></div></article><?php endforeach;?></div><?php endif;?></section>
    <?php
}

function smarttoolz_video_upload_page(){return smarttoolz_video_manage_page();}

function smarttoolz_video_render_watch($video_id){$video=get_post($video_id);if(!$video||'stv_video'!==$video->post_type||'publish'!==$video->post_status){echo '<div class="stv-empty">Video not found.</div>';return;}$source=smarttoolz_video_get_source($video_id);?><article class="stv-watch"><video class="stv-watch__player" controls playsinline preload="metadata" src="<?php echo esc_url($source);?>"></video><h1 class="stv-page-title"><?php echo esc_html($video->post_title);?></h1><div class="stv-watch__meta">By <?php echo esc_html(get_the_author_meta('display_name',$video->post_author));?> · <?php echo esc_html(get_the_date('',$video));?></div><?php if($video->post_content):?><div class="stv-watch__description"><?php echo wp_kses_post(wpautop($video->post_content));?></div><?php endif;?></article><?php }
