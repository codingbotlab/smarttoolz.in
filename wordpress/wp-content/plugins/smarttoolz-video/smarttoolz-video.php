<?php
/**
 * Plugin Name: SmartToolz Video
 * Plugin URI: https://smarttoolz.in/
 * Description: YouTube-style video sharing foundation for SmartToolz WordPress.
 * Version: 1.0.0
 * Author: SmartToolz
 * License: GPL-2.0-or-later
 * Text Domain: smarttoolz-video
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'SMARTTOOLZ_VIDEO_VERSION', '1.0.0' );
define( 'SMARTTOOLZ_VIDEO_FILE', __FILE__ );
define( 'SMARTTOOLZ_VIDEO_DIR', plugin_dir_path( __FILE__ ) );
define( 'SMARTTOOLZ_VIDEO_URL', plugin_dir_url( __FILE__ ) );

function smarttoolz_video_register_post_type() {
    $labels = array(
        'name'               => __( 'Videos', 'smarttoolz-video' ),
        'singular_name'      => __( 'Video', 'smarttoolz-video' ),
        'add_new'            => __( 'Add Video', 'smarttoolz-video' ),
        'add_new_item'       => __( 'Add New Video', 'smarttoolz-video' ),
        'edit_item'          => __( 'Edit Video', 'smarttoolz-video' ),
        'new_item'           => __( 'New Video', 'smarttoolz-video' ),
        'view_item'          => __( 'View Video', 'smarttoolz-video' ),
        'search_items'       => __( 'Search Videos', 'smarttoolz-video' ),
        'not_found'          => __( 'No videos found.', 'smarttoolz-video' ),
        'menu_name'          => __( 'SmartToolz Videos', 'smarttoolz-video' ),
    );

    register_post_type( 'st_video', array(
        'labels'             => $labels,
        'public'             => true,
        'show_ui'            => true,
        'show_in_rest'       => true,
        'menu_icon'          => 'dashicons-video-alt3',
        'supports'           => array( 'title', 'editor', 'thumbnail', 'author', 'comments' ),
        'has_archive'        => true,
        'rewrite'            => array( 'slug' => 'videos' ),
        'capability_type'    => 'post',
        'map_meta_cap'       => true,
    ) );
}
add_action( 'init', 'smarttoolz_video_register_post_type' );

function smarttoolz_video_register_taxonomy() {
    register_taxonomy( 'st_video_category', 'st_video', array(
        'labels' => array(
            'name'          => __( 'Video Categories', 'smarttoolz-video' ),
            'singular_name' => __( 'Video Category', 'smarttoolz-video' ),
        ),
        'public'       => true,
        'show_ui'      => true,
        'show_in_rest' => true,
        'hierarchical' => true,
        'rewrite'      => array( 'slug' => 'video-category' ),
    ) );
}
add_action( 'init', 'smarttoolz_video_register_taxonomy' );

function smarttoolz_video_register_meta() {
    register_post_meta( 'st_video', '_st_video_source', array(
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'esc_url_raw',
        'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
    ) );
    register_post_meta( 'st_video', '_st_video_duration', array(
        'type'              => 'string',
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'sanitize_text_field',
        'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
    ) );
    register_post_meta( 'st_video', '_st_video_views', array(
        'type'              => 'integer',
        'single'            => true,
        'default'           => 0,
        'show_in_rest'      => true,
        'sanitize_callback' => 'absint',
        'auth_callback'     => function () { return current_user_can( 'edit_posts' ); },
    ) );
}
add_action( 'init', 'smarttoolz_video_register_meta' );

function smarttoolz_video_admin_box() {
    add_meta_box(
        'st_video_details',
        __( 'Video Details', 'smarttoolz-video' ),
        'smarttoolz_video_render_admin_box',
        'st_video',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'smarttoolz_video_admin_box' );

function smarttoolz_video_render_admin_box( $post ) {
    wp_nonce_field( 'st_video_save', 'st_video_nonce' );
    $source   = get_post_meta( $post->ID, '_st_video_source', true );
    $duration = get_post_meta( $post->ID, '_st_video_duration', true );
    ?>
    <p>
        <label for="st_video_source"><strong><?php esc_html_e( 'Video URL', 'smarttoolz-video' ); ?></strong></label>
        <input id="st_video_source" name="st_video_source" type="url" class="widefat" value="<?php echo esc_attr( $source ); ?>" placeholder="https://.../video.mp4 or YouTube/Vimeo URL" />
    </p>
    <p>
        <label for="st_video_duration"><strong><?php esc_html_e( 'Duration', 'smarttoolz-video' ); ?></strong></label>
        <input id="st_video_duration" name="st_video_duration" type="text" class="widefat" value="<?php echo esc_attr( $duration ); ?>" placeholder="12:34" />
    </p>
    <p class="description"><?php esc_html_e( 'For self-hosted video, use a direct MP4/WebM URL. Embeds can be added in the next platform phase.', 'smarttoolz-video' ); ?></p>
    <?php
}

function smarttoolz_video_save_meta( $post_id ) {
    if ( ! isset( $_POST['st_video_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['st_video_nonce'] ) ), 'st_video_save' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    if ( 'st_video' !== get_post_type( $post_id ) ) {
        return;
    }

    if ( isset( $_POST['st_video_source'] ) ) {
        update_post_meta( $post_id, '_st_video_source', esc_url_raw( wp_unslash( $_POST['st_video_source'] ) ) );
    }
    if ( isset( $_POST['st_video_duration'] ) ) {
        update_post_meta( $post_id, '_st_video_duration', sanitize_text_field( wp_unslash( $_POST['st_video_duration'] ) ) );
    }
}
add_action( 'save_post_st_video', 'smarttoolz_video_save_meta' );

function smarttoolz_video_increment_view() {
    if ( ! is_singular( 'st_video' ) ) {
        return;
    }
    $post_id = get_queried_object_id();
    if ( ! $post_id || ! empty( $_COOKIE['st_video_viewed_' . $post_id] ) ) {
        return;
    }
    $views = (int) get_post_meta( $post_id, '_st_video_views', true );
    update_post_meta( $post_id, '_st_video_views', $views + 1 );
    setcookie( 'st_video_viewed_' . $post_id, '1', time() + DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true );
}
add_action( 'wp', 'smarttoolz_video_increment_view' );

function smarttoolz_video_enqueue_assets() {
    if ( is_singular( 'st_video' ) || is_post_type_archive( 'st_video' ) || is_tax( 'st_video_category' ) ) {
        wp_enqueue_style( 'smarttoolz-video', SMARTTOOLZ_VIDEO_URL . 'assets/css/video.css', array(), SMARTTOOLZ_VIDEO_VERSION );
        wp_enqueue_script( 'smarttoolz-video', SMARTTOOLZ_VIDEO_URL . 'assets/js/video.js', array(), SMARTTOOLZ_VIDEO_VERSION, true );
    }
}
add_action( 'wp_enqueue_scripts', 'smarttoolz_video_enqueue_assets' );

function smarttoolz_video_render_player( $post_id = 0 ) {
    $post_id = $post_id ? absint( $post_id ) : get_the_ID();
    $source  = get_post_meta( $post_id, '_st_video_source', true );
    if ( ! $source ) {
        return '<div class="st-video-placeholder">' . esc_html__( 'Video source not added yet.', 'smarttoolz-video' ) . '</div>';
    }

    $path = wp_parse_url( $source, PHP_URL_PATH );
    $ext  = strtolower( pathinfo( (string) $path, PATHINFO_EXTENSION ) );
    if ( in_array( $ext, array( 'mp4', 'webm', 'ogg' ), true ) ) {
        return '<div class="st-video-player"><video controls playsinline preload="metadata" src="' . esc_url( $source ) . '"></video></div>';
    }

    return '<div class="st-video-embed"><iframe src="' . esc_url( $source ) . '" loading="lazy" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen title="' . esc_attr( get_the_title( $post_id ) ) . '"></iframe></div>';
}

function smarttoolz_video_shortcode( $atts ) {
    $atts = shortcode_atts( array( 'id' => get_the_ID() ), $atts, 'smarttoolz_video' );
    return smarttoolz_video_render_player( (int) $atts['id'] );
}
add_shortcode( 'smarttoolz_video', 'smarttoolz_video_shortcode' );

function smarttoolz_video_content_filter( $content ) {
    if ( is_singular( 'st_video' ) && in_the_loop() && is_main_query() ) {
        $player = smarttoolz_video_render_player();
        $views  = (int) get_post_meta( get_the_ID(), '_st_video_views', true );
        $author = get_the_author();
        $meta   = '<div class="st-video-meta"><span>' . esc_html( number_format_i18n( $views ) ) . ' views</span><span>' . esc_html( $author ) . '</span><span>' . esc_html( get_the_date() ) . '</span></div>';
        return $player . $meta . $content;
    }
    return $content;
}
add_filter( 'the_content', 'smarttoolz_video_content_filter', 20 );

function smarttoolz_video_activation() {
    smarttoolz_video_register_post_type();
    smarttoolz_video_register_taxonomy();
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'smarttoolz_video_activation' );

function smarttoolz_video_deactivation() {
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'smarttoolz_video_deactivation' );
