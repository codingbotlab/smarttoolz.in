<?php
/**
 * SmartToolz Video player assets and frontend player bootstrap.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_enqueue_player_assets() {
    if ( ! is_singular( 'st_video' ) ) { return; }

    wp_enqueue_style(
        'smarttoolz-video',
        SMARTTOOLZ_VIDEO_URL . 'assets/css/video.css',
        array(),
        SMARTTOOLZ_VIDEO_VERSION . '.player1'
    );

    wp_enqueue_script(
        'smarttoolz-video',
        SMARTTOOLZ_VIDEO_URL . 'assets/js/video.js',
        array(),
        SMARTTOOLZ_VIDEO_VERSION . '.player1',
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
