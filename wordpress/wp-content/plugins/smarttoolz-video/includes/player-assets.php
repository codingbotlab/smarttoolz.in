<?php
/**
 * SmartToolz Video player assets and frontend player bootstrap.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_should_load_player_assets() {
    if ( is_singular( 'st_video' ) || is_post_type_archive( 'st_video' ) || is_tax( 'st_video_category' ) ) { return true; }
    if ( is_singular() ) {
        $post = get_queried_object();
        if ( $post instanceof WP_Post ) {
            $video_shortcodes = array(
                'smarttoolz_video_platform','smarttoolz_video_feed','smarttoolz_video_home','smarttoolz_video_trending','smarttoolz_video_categories','smarttoolz_video_search','smarttoolz_video_subscriptions','smarttoolz_video_liked','smarttoolz_video_history','smarttoolz_video_upload','smarttoolz_channel','smarttoolz_creator_dashboard','smarttoolz_video_edit',
            );
            foreach ( $video_shortcodes as $shortcode ) { if ( has_shortcode( $post->post_content, $shortcode ) ) { return true; } }
        }
    }
    return false;
}

function smarttoolz_video_enqueue_player_assets() {
    if ( ! smarttoolz_video_should_load_player_assets() ) { return; }
    wp_enqueue_style( 'smarttoolz-video', SMARTTOOLZ_VIDEO_URL . 'assets/css/video.css', array(), SMARTTOOLZ_VIDEO_VERSION . '.player5' );
    wp_enqueue_style( 'smarttoolz-video-thumbnails', SMARTTOOLZ_VIDEO_URL . 'assets/css/thumbnails.css', array( 'smarttoolz-video' ), SMARTTOOLZ_VIDEO_VERSION . '.thumb3' );
    wp_enqueue_style( 'smarttoolz-video-youtube-ui', SMARTTOOLZ_VIDEO_URL . 'assets/css/youtube-ui.css', array( 'smarttoolz-video-thumbnails' ), SMARTTOOLZ_VIDEO_VERSION . '.yt2' );
    wp_enqueue_script( 'smarttoolz-video', SMARTTOOLZ_VIDEO_URL . 'assets/js/video.js', array(), SMARTTOOLZ_VIDEO_VERSION . '.player5', true );
    $settings = function_exists( 'smarttoolz_video_settings' ) ? smarttoolz_video_settings() : array();
    wp_localize_script( 'smarttoolz-video', 'stvPlatform', array(
        'ajax' => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce( 'stv_platform' ),
        'player' => array(
            'autoplay' => ! empty( $settings['autoplay'] ),
            'muted' => ! empty( $settings['muted'] ),
            'loop' => ! empty( $settings['loop'] ),
            'preload' => $settings['preload'] ?? 'metadata',
            'volume' => isset( $settings['volume'] ) ? (float) $settings['volume'] : 1,
            'speed' => isset( $settings['speed'] ) ? (float) $settings['speed'] : 1,
            'theater' => ! empty( $settings['theater'] ),
            'pip' => ! empty( $settings['pip'] ),
            'doubleClickFullscreen' => ! empty( $settings['double_click_fullscreen'] ),
        ),
    ) );
}
add_action( 'wp_enqueue_scripts', 'smarttoolz_video_enqueue_player_assets', 25 );
