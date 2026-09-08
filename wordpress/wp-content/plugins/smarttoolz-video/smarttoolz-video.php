<?php
/**
 * Plugin Name: SmartToolz Video
 * Plugin URI: https://smarttoolz.in/
 * Description: YouTube-style video sharing foundation for SmartToolz WordPress.
 * Version: 1.1.0
 * Author: SmartToolz
 * License: GPL-2.0-or-later
 * Text Domain: smarttoolz-video
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'SMARTTOOLZ_VIDEO_VERSION', '1.1.0' );
define( 'SMARTTOOLZ_VIDEO_FILE', __FILE__ );
define( 'SMARTTOOLZ_VIDEO_DIR', plugin_dir_path( __FILE__ ) );
define( 'SMARTTOOLZ_VIDEO_URL', plugin_dir_url( __FILE__ ) );

function smarttoolz_video_register_post_type() {
    $labels = array(
        'name'          => __( 'Videos', 'smarttoolz-video' ),
        'singular_name' => __( 'Video', 'smarttoolz-video' ),
        'add_new'       => __( 'Add Video', 'smarttoolz-video' ),
        'add_new_item'  => __( 'Add New Video', 'smarttoolz-video' ),
        'edit_item'     => __( 'Edit Video', 'smarttoolz-video' ),
        'new_item'      => __( 'New Video', 'smarttoolz-video' ),
        'view_item'     => __( 'View Video', 'smarttoolz-video' ),
        'search_items'  => __( 'Search Videos', 'smarttoolz-video' ),
        'not_found'     => __( 'No videos found.', 'smarttoolz-video' ),
        'menu_name'    => __( 'SmartToolz Videos', 'smarttoolz-video' ),
    );

    register_post_type( 'st_video', array(
        'labels'          => $labels,
        'public'          => true,
        'show_ui'         => true,
        'show_in_rest'    => true,
        'menu_icon'       => 'dashicons-video-alt3',
        'supports'        => array( 'title', 'editor', 'thumbnail', 'author', 'comments' ),
        'has_archive'     => true,
        'rewrite'         => array( 'slug' => 'videos' ),
        'capability_type' => 'post',
        'map_meta_cap'    => true,
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
    add_meta_box( 'st_video_details', __( 'Video Details', 'smarttoolz-video' ), 'smarttoolz_video_render_admin_box', 'st_video', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'smarttoolz_video_admin_box' );

function smarttoolz_video_render_admin_box( $post ) {
    wp_nonce_field( 'st_video_save', 'st_video_nonce' );
    $source   = get_post_meta( $post->ID, '_st_video_source', true );
    $duration = get_post_meta( $post->ID, '_st_video_duration', true );
    ?>
    <p><label for="st_video_source"><strong><?php esc_html_e( 'Video URL', 'smarttoolz-video' ); ?></strong></label>
    <input id="st_video_source" name="st_video_source" type="url" class="widefat" value="<?php echo esc_attr( $source ); ?>" placeholder="https://.../video.mp4 or YouTube/Vimeo URL" /></p>
    <p><label for="st_video_duration"><strong><?php esc_html_e( 'Duration', 'smarttoolz-video' ); ?></strong></label>
    <input id="st_video_duration" name="st_video_duration" type="text" class="widefat" value="<?php echo esc_attr( $duration ); ?>" placeholder="12:34" /></p>
    <p class="description"><?php esc_html_e( 'For self-hosted video, use a direct MP4/WebM URL. Frontend uploads are supported through the SmartToolz upload shortcode.', 'smarttoolz-video' ); ?></p>
    <?php
}

function smarttoolz_video_save_meta( $post_id ) {
    if ( ! isset( $_POST['st_video_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['st_video_nonce'] ) ), 'st_video_save' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( ! current_user_can( 'edit_post', $post_id ) || 'st_video' !== get_post_type( $post_id ) ) {
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
    if ( ! $post_id || ! empty( $_COOKIE[ 'st_video_viewed_' . $post_id ] ) ) {
        return;
    }
    $views = (int) get_post_meta( $post_id, '_st_video_views', true );
    update_post_meta( $post_id, '_st_video_views', $views + 1 );
    setcookie( 'st_video_viewed_' . $post_id, '1', time() + DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true );
}
add_action( 'wp', 'smarttoolz_video_increment_view' );

function smarttoolz_video_enqueue_assets() {
    if ( is_singular( 'st_video' ) || is_post_type_archive( 'st_video' ) || is_tax( 'st_video_category' ) || is_page() ) {
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
        $url    = get_permalink();
        $meta   = '<div class="st-video-meta"><span>' . esc_html( number_format_i18n( $views ) ) . ' views</span><span>' . esc_html( $author ) . '</span><span>' . esc_html( get_the_date() ) . '</span></div>';
        $actions = '<div class="st-video-actions"><button type="button" class="st-video-action" data-st-video-share data-url="' . esc_attr( $url ) . '" data-title="' . esc_attr( get_the_title() ) . '">' . esc_html__( 'Share', 'smarttoolz-video' ) . '</button></div>';
        return $player . $meta . $actions . $content;
    }
    return $content;
}
add_filter( 'the_content', 'smarttoolz_video_content_filter', 20 );

function smarttoolz_video_frontend_upload() {
    if ( ! is_user_logged_in() || ! isset( $_POST['st_video_frontend_action'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['st_video_frontend_nonce'] ?? '' ) ), 'st_video_frontend_upload' ) ) {
        return;
    }
    if ( ! current_user_can( 'publish_posts' ) ) {
        return;
    }

    $title = isset( $_POST['st_video_title'] ) ? sanitize_text_field( wp_unslash( $_POST['st_video_title'] ) ) : '';
    $description = isset( $_POST['st_video_description'] ) ? wp_kses_post( wp_unslash( $_POST['st_video_description'] ) ) : '';
    $source_url = isset( $_POST['st_video_url'] ) ? esc_url_raw( wp_unslash( $_POST['st_video_url'] ) ) : '';

    if ( '' === $title ) {
        wp_safe_redirect( add_query_arg( 'st_video_upload', 'missing-title', wp_get_referer() ?: home_url( '/' ) ) );
        exit;
    }

    $post_id = wp_insert_post( array(
        'post_type'    => 'st_video',
        'post_status'  => 'publish',
        'post_title'   => $title,
        'post_content' => $description,
        'post_author'  => get_current_user_id(),
    ), true );

    if ( is_wp_error( $post_id ) ) {
        wp_safe_redirect( add_query_arg( 'st_video_upload', 'failed', wp_get_referer() ?: home_url( '/' ) ) );
        exit;
    }

    if ( ! empty( $_FILES['st_video_file']['name'] ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        $upload = wp_handle_upload( $_FILES['st_video_file'], array( 'test_form' => false, 'mimes' => array( 'mp4' => 'video/mp4', 'webm' => 'video/webm', 'ogg' => 'video/ogg' ) ) );
        if ( isset( $upload['error'] ) ) {
            wp_delete_post( $post_id, true );
            wp_safe_redirect( add_query_arg( 'st_video_upload', 'file-error', wp_get_referer() ?: home_url( '/' ) ) );
            exit;
        }
        update_post_meta( $post_id, '_st_video_source', esc_url_raw( $upload['url'] ) );
    } elseif ( $source_url ) {
        update_post_meta( $post_id, '_st_video_source', $source_url );
    } else {
        wp_delete_post( $post_id, true );
        wp_safe_redirect( add_query_arg( 'st_video_upload', 'missing-video', wp_get_referer() ?: home_url( '/' ) ) );
        exit;
    }

    wp_safe_redirect( add_query_arg( 'st_video_upload', 'success', get_permalink( $post_id ) ) );
    exit;
}
add_action( 'template_redirect', 'smarttoolz_video_frontend_upload', 1 );

function smarttoolz_video_upload_shortcode() {
    if ( ! is_user_logged_in() ) {
        return '<div class="st-video-login-note">' . esc_html__( 'Please log in to upload a video.', 'smarttoolz-video' ) . '</div>';
    }
    if ( ! current_user_can( 'publish_posts' ) ) {
        return '<div class="st-video-login-note">' . esc_html__( 'Your account is not allowed to publish videos yet.', 'smarttoolz-video' ) . '</div>';
    }
    $notice = '';
    $status = isset( $_GET['st_video_upload'] ) ? sanitize_key( wp_unslash( $_GET['st_video_upload'] ) ) : '';
    if ( 'success' === $status ) $notice = '<div class="st-video-notice st-video-notice-success">' . esc_html__( 'Video published successfully.', 'smarttoolz-video' ) . '</div>';
    if ( in_array( $status, array( 'missing-title', 'missing-video', 'file-error', 'failed' ), true ) ) $notice = '<div class="st-video-notice st-video-notice-error">' . esc_html__( 'Video could not be published. Please check the title and video file/URL.', 'smarttoolz-video' ) . '</div>';
    ob_start();
    echo '<div class="st-video-upload-wrap">' . $notice . '<form class="st-video-upload-form" method="post" enctype="multipart/form-data">';
    wp_nonce_field( 'st_video_frontend_upload', 'st_video_frontend_nonce' );
    echo '<input type="hidden" name="st_video_frontend_action" value="upload">';
    echo '<p><label for="st-video-title">' . esc_html__( 'Video title', 'smarttoolz-video' ) . '</label><input id="st-video-title" name="st_video_title" type="text" maxlength="180" required></p>';
    echo '<p><label for="st-video-description">' . esc_html__( 'Description', 'smarttoolz-video' ) . '</label><textarea id="st-video-description" name="st_video_description"></textarea></p>';
    echo '<p><label for="st-video-file">' . esc_html__( 'Upload video', 'smarttoolz-video' ) . '</label><input id="st-video-file" name="st_video_file" type="file" accept="video/mp4,video/webm,video/ogg"></p>';
    echo '<p><label for="st-video-url">' . esc_html__( 'Or video URL', 'smarttoolz-video' ) . '</label><input id="st-video-url" name="st_video_url" type="url" placeholder="https://example.com/video.mp4"></p>';
    echo '<button type="submit">' . esc_html__( 'Publish Video', 'smarttoolz-video' ) . '</button></form></div>';
    return ob_get_clean();
}
add_shortcode( 'smarttoolz_video_upload', 'smarttoolz_video_upload_shortcode' );

function smarttoolz_video_feed_shortcode( $atts ) {
    $atts = shortcode_atts( array( 'limit' => 12, 'category' => '' ), $atts, 'smarttoolz_video_feed' );
    $args = array(
        'post_type'      => 'st_video',
        'post_status'    => 'publish',
        'posts_per_page' => min( 48, max( 1, absint( $atts['limit'] ) ) ),
        'orderby'        => 'date',
        'order'          => 'DESC',
    );
    if ( $atts['category'] ) $args['tax_query'] = array( array( 'taxonomy' => 'st_video_category', 'field' => 'slug', 'terms' => sanitize_title( $atts['category'] ) ) );
    $query = new WP_Query( $args );
    if ( ! $query->have_posts() ) return '<div class="st-video-empty">' . esc_html__( 'No videos available yet.', 'smarttoolz-video' ) . '</div>';
    ob_start();
    echo '<div class="st-video-grid">';
    while ( $query->have_posts() ) {
        $query->the_post();
        $source = get_post_meta( get_the_ID(), '_st_video_source', true );
        echo '<a class="st-video-card" href="' . esc_url( get_permalink() ) . '">';
        if ( has_post_thumbnail() ) {
            echo get_the_post_thumbnail( get_the_ID(), 'large', array( 'class' => 'st-video-thumb', 'loading' => 'lazy' ) );
        } else {
            echo '<span class="st-video-thumb-placeholder"><span>SmartToolz Video</span></span>';
        }
        echo '<span class="st-video-card-body"><span class="st-video-card-title">' . esc_html( get_the_title() ) . '</span><span class="st-video-card-meta">' . esc_html( get_the_author() ) . ' · ' . esc_html( number_format_i18n( (int) get_post_meta( get_the_ID(), '_st_video_views', true ) ) ) . ' views</span></span></a>';
    }
    echo '</div>';
    wp_reset_postdata();
    return ob_get_clean();
}
add_shortcode( 'smarttoolz_video_feed', 'smarttoolz_video_feed_shortcode' );

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
