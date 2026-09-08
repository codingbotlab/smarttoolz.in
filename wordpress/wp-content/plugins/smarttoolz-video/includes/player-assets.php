<?php
/**
 * SmartToolz Video player assets and frontend player bootstrap.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_should_load_player_assets() {
    if ( is_singular( 'st_video' ) ) { return true; }
    if ( is_singular() ) {
        $post = get_queried_object();
        if ( $post instanceof WP_Post && has_shortcode( $post->post_content, 'smarttoolz_video_upload' ) ) {
            return true;
        }
    }
    return false;
}

function smarttoolz_video_enqueue_player_assets() {
    if ( ! smarttoolz_video_should_load_player_assets() ) { return; }

    wp_enqueue_style(
        'smarttoolz-video',
        SMARTTOOLZ_VIDEO_URL . 'assets/css/video.css',
        array(),
        SMARTTOOLZ_VIDEO_VERSION . '.player2'
    );

    wp_enqueue_style(
        'smarttoolz-video-thumbnails',
        SMARTTOOLZ_VIDEO_URL . 'assets/css/thumbnails.css',
        array( 'smarttoolz-video' ),
        SMARTTOOLZ_VIDEO_VERSION . '.thumb1'
    );

    wp_enqueue_script(
        'smarttoolz-video',
        SMARTTOOLZ_VIDEO_URL . 'assets/js/video.js',
        array(),
        SMARTTOOLZ_VIDEO_VERSION . '.player3',
        true
    );

    wp_localize_script(
        'smarttoolz-video',
        'stvPlatform',
        array(
            'ajax'  => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'stv_platform' ),
        )
    );
}
add_action( 'wp_enqueue_scripts', 'smarttoolz_video_enqueue_player_assets', 25 );
