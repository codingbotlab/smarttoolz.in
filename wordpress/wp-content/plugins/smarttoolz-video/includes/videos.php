<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_register_content() {
    register_post_type( 'st_video', array(
        'labels' => array(
            'name' => 'Videos',
            'singular_name' => 'Video',
            'add_new_item' => 'Add New Video',
            'edit_item' => 'Edit Video',
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'comments' ),
        'has_archive' => 'videos',
        'rewrite' => array( 'slug' => 'videos', 'with_front' => false ),
        'show_in_rest' => true,
        'menu_icon' => 'dashicons-video-alt3',
    ) );

    register_taxonomy( 'st_video_category', 'st_video', array(
        'labels' => array(
            'name' => 'Video Categories',
            'singular_name' => 'Video Category',
        ),
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'hierarchical' => true,
        'rewrite' => array( 'slug' => 'video-categories', 'with_front' => false ),
        'show_in_rest' => true,
    ) );

    register_post_meta( 'st_video', '_st_video_source', array( 'type'=>'string', 'single'=>true, 'show_in_rest'=>true, 'sanitize_callback'=>'esc_url_raw' ) );
    register_post_meta( 'st_video', '_st_video_duration', array( 'type'=>'string', 'single'=>true, 'show_in_rest'=>true, 'sanitize_callback'=>'sanitize_text_field' ) );
    register_post_meta( 'st_video', '_st_video_views', array( 'type'=>'integer', 'single'=>true, 'show_in_rest'=>true, 'sanitize_callback'=>'absint', 'default'=>0 ) );
}
add_action( 'init', 'smarttoolz_video_register_content' );

function smarttoolz_video_meta_box() {
    add_meta_box( 'smarttoolz_video_details', 'SmartToolz Video Details', 'smarttoolz_video_meta_box_render', 'st_video', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'smarttoolz_video_meta_box' );

function smarttoolz_video_meta_box_render( $post ) {
    wp_nonce_field( 'smarttoolz_video_meta', 'smarttoolz_video_meta_nonce' );
    $source = get_post_meta( $post->ID, '_st_video_source', true );
    $duration = get_post_meta( $post->ID, '_st_video_duration', true );
    ?>
    <p><label><strong>Video URL</strong><br><input type="url" class="widefat" name="smarttoolz_video_source" value="<?php echo esc_attr( $source ); ?>" placeholder="https://example.com/video.mp4"></label></p>
    <p><label><strong>Duration</strong><br><input type="text" name="smarttoolz_video_duration" value="<?php echo esc_attr( $duration ); ?>" placeholder="02:34"></label></p>
    <?php
}

function smarttoolz_video_save_meta( $post_id ) {
    if ( ! isset( $_POST['smarttoolz_video_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['smarttoolz_video_meta_nonce'] ) ), 'smarttoolz_video_meta' ) ) { return; }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
    if ( ! current_user_can( 'edit_post', $post_id ) || 'st_video' !== get_post_type( $post_id ) ) { return; }
    update_post_meta( $post_id, '_st_video_source', esc_url_raw( $_POST['smarttoolz_video_source'] ?? '' ) );
    update_post_meta( $post_id, '_st_video_duration', sanitize_text_field( $_POST['smarttoolz_video_duration'] ?? '' ) );
}
add_action( 'save_post_st_video', 'smarttoolz_video_save_meta' );

function smarttoolz_video_views_increment( $post_id ) {
    if ( ! $post_id || 'st_video' !== get_post_type( $post_id ) ) { return; }
    if ( is_admin() ) { return; }
    $key = 'stv_viewed_' . $post_id;
    if ( isset( $_COOKIE[ $key ] ) ) { return; }
    $views = absint( get_post_meta( $post_id, '_st_video_views', true ) ) + 1;
    update_post_meta( $post_id, '_st_video_views', $views );
    setcookie( $key, '1', time() + HOUR_IN_SECONDS, COOKIEPATH ?: '/', COOKIE_DOMAIN, is_ssl(), true );
}
add_action( 'template_redirect', function(){ if ( is_singular( 'st_video' ) ) { smarttoolz_video_views_increment( get_queried_object_id() ); } } );
