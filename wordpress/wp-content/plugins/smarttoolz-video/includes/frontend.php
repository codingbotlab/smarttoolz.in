<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_is_frontend() {
    return ! is_admin() && ( is_post_type_archive( 'st_video' ) || is_singular( 'st_video' ) || has_shortcode( get_post_field( 'post_content', get_queried_object_id() ), 'smarttoolz_video_home' ) || has_shortcode( get_post_field( 'post_content', get_queried_object_id() ), 'smarttoolz_video_upload' ) );
}

function smarttoolz_video_asset_style() {
    if ( ! smarttoolz_video_is_frontend() ) { return; }
    echo '<style id="smarttoolz-video-frontend">.smarttoolz-video-page{background:#0f0f0f;color:#f1f1f1;min-height:70vh;padding:28px 22px;font-family:Arial,Helvetica,sans-serif}.smarttoolz-video-page a{color:inherit}.stv-video-grid{display:grid;gap:22px}.stv-video-card{min-width:0}.stv-thumb{display:block;aspect-ratio:16/9;background:#222;border-radius:10px;overflow:hidden}.stv-thumb img{display:block;width:100%;height:100%;object-fit:cover}.stv-card-title{font-size:15px;font-weight:700;line-height:1.35;margin:10px 0 5px}.stv-card-meta{font-size:12px;color:#aaa}.stv-player{width:100%;background:#000;border-radius:10px;overflow:hidden}.stv-player video{display:block;width:100%;height:auto;max-height:75vh}.stv-empty{padding:40px 20px;text-align:center;background:#181818;border:1px solid #2a2a2a;border-radius:12px;color:#aaa}.stv-upload{max-width:760px;margin:0 auto;background:#181818;border:1px solid #2a2a2a;border-radius:14px;padding:24px}.stv-upload h1{margin-top:0}.stv-upload label{display:block;font-size:13px;font-weight:700;margin:0 0 7px}.stv-upload input,.stv-upload textarea,.stv-upload select{width:100%;box-sizing:border-box;margin-bottom:18px;padding:11px 12px;border:1px solid #383838;border-radius:8px;background:#111;color:#fff}.stv-upload button{border:0;border-radius:20px;padding:11px 18px;background:#f00;color:#fff;font-weight:700;cursor:pointer}</style>';
}
add_action( 'wp_head', 'smarttoolz_video_asset_style', 80 );

function smarttoolz_video_card( $post_id ) {
    $title = get_the_title( $post_id );
    $thumb = get_the_post_thumbnail_url( $post_id, 'medium_large' );
    $views = absint( get_post_meta( $post_id, '_st_video_views', true ) );
    $duration = get_post_meta( $post_id, '_st_video_duration', true );
    ob_start(); ?>
    <article class="stv-video-card">
        <a class="stv-thumb" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>">
            <?php if ( $thumb ) : ?><img loading="lazy" src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $title ); ?>"><?php else : ?><span aria-hidden="true"></span><?php endif; ?>
        </a>
        <a class="stv-card-title" href="<?php echo esc_url( get_permalink( $post_id ) ); ?>"><?php echo esc_html( $title ); ?></a>
        <div class="stv-card-meta"><?php echo esc_html( number_format_i18n( $views ) ); ?> views<?php echo $duration ? ' · ' . esc_html( $duration ) : ''; ?> · <?php echo esc_html( human_time_diff( get_post_time( 'U', true, $post_id ), current_time( 'timestamp', true ) ) ); ?> ago</div>
    </article>
    <?php return ob_get_clean();
}

function smarttoolz_video_home_shortcode() {
    $q = new WP_Query( array( 'post_type'=>'st_video', 'post_status'=>'publish', 'posts_per_page'=>absint( smarttoolz_video_setting( 'grid_columns', 4 ) ) * 3, 'orderby'=>'date', 'order'=>'DESC' ) );
    ob_start(); ?>
    <div class="smarttoolz-video-page"><h1>Videos</h1>
    <?php if ( $q->have_posts() ) : ?><div class="stv-video-grid"><?php while ( $q->have_posts() ) : $q->the_post(); echo smarttoolz_video_card( get_the_ID() ); endwhile; ?></div><?php else : ?><div class="stv-empty">No videos yet.</div><?php endif; wp_reset_postdata(); ?></div>
    <?php return ob_get_clean();
}
add_shortcode( 'smarttoolz_video_home', 'smarttoolz_video_home_shortcode' );

function smarttoolz_video_archive_template( $template ) {
    if ( ! is_post_type_archive( 'st_video' ) ) { return $template; }
    return $template;
}

function smarttoolz_video_single_content( $content ) {
    if ( ! is_singular( 'st_video' ) || ! in_the_loop() || ! is_main_query() ) { return $content; }
    $src = get_post_meta( get_the_ID(), '_st_video_source', true );
    if ( ! $src ) { return $content; }
    $player = '<div class="smarttoolz-video-page"><div class="stv-player"><video controls preload="' . esc_attr( smarttoolz_video_setting( 'preload', 'metadata' ) ) . '"' . ( smarttoolz_video_setting( 'autoplay', 0 ) ? ' autoplay' : '' ) . ( smarttoolz_video_setting( 'muted', 0 ) ? ' muted' : '' ) . ( smarttoolz_video_setting( 'loop', 0 ) ? ' loop' : '' ) . '><source src="' . esc_url( $src ) . '"></video></div><h1 style="margin:18px 0 8px">' . esc_html( get_the_title() ) . '</h1><div style="color:#aaa;font-size:13px;margin-bottom:20px">' . esc_html( absint( get_post_meta( get_the_ID(), '_st_video_views', true ) ) ) . ' views</div>' . apply_filters( 'the_content', $content ) . '</div>';
    return $player;
}
add_filter( 'the_content', 'smarttoolz_video_single_content', 9 );

function smarttoolz_video_upload_shortcode() {
    if ( ! is_user_logged_in() ) { return '<div class="smarttoolz-video-page"><div class="stv-empty">Please log in to upload a video.</div></div>'; }
    if ( ! smarttoolz_video_setting( 'uploads_enabled', 1 ) ) { return '<div class="smarttoolz-video-page"><div class="stv-empty">Video uploads are currently disabled.</div></div>'; }
    $message = '';
    if ( isset( $_POST['stv_upload_submit'], $_POST['stv_upload_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['stv_upload_nonce'] ) ), 'stv_upload' ) ) {
        $title = sanitize_text_field( wp_unslash( $_POST['stv_title'] ?? '' ) );
        $description = wp_kses_post( wp_unslash( $_POST['stv_description'] ?? '' ) );
        $src = esc_url_raw( $_POST['stv_source'] ?? '' );
        if ( $title && $src ) {
            $id = wp_insert_post( array( 'post_title'=>$title, 'post_content'=>$description, 'post_status'=>'publish', 'post_type'=>'st_video', 'post_author'=>get_current_user_id() ) );
            if ( ! is_wp_error( $id ) && $id ) { update_post_meta( $id, '_st_video_source', $src ); $message = '<p style="color:#61d47a">Video published successfully.</p>'; }
            else { $message = '<p style="color:#ff7777">Could not create the video.</p>'; }
        } else { $message = '<p style="color:#ff7777">Title and video URL are required.</p>'; }
    }
    ob_start(); ?><div class="smarttoolz-video-page"><div class="stv-upload"><h1>Upload Video</h1><?php echo $message; ?><form method="post"><?php wp_nonce_field( 'stv_upload', 'stv_upload_nonce' ); ?><input type="hidden" name="stv_upload_submit" value="1"><label for="stv-title">Title</label><input id="stv-title" name="stv_title" required><label for="stv-description">Description</label><textarea id="stv-description" name="stv_description" rows="6"></textarea><label for="stv-source">Video URL</label><input id="stv-source" name="stv_source" type="url" placeholder="https://example.com/video.mp4" required><button type="submit">Publish Video</button></form></div></div><?php return ob_get_clean();
}
add_shortcode( 'smarttoolz_video_upload', 'smarttoolz_video_upload_shortcode' );
