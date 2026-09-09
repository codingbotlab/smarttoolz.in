<?php
/**
 * Runtime bridge for SmartToolz Video administrator settings.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_runtime_frontend_css() {
    if ( ! function_exists( 'smarttoolz_video_should_load_player_assets' ) || ! smarttoolz_video_should_load_player_assets() ) { return; }
    $s = function_exists( 'smarttoolz_video_settings' ) ? smarttoolz_video_settings() : array();
    $cols = isset( $s['page_columns'] ) ? max( 2, min( 5, absint( $s['page_columns'] ) ) ) : 4;
    $rules = array(
        'body.smarttoolz-video-platform{--stv-runtime-accent:' . esc_attr( $s['accent_color'] ?? '#ff0000' ) . '}',
        'body.smarttoolz-video-platform .stv-grid,body.smarttoolz-video-platform .stv-platform-page .stv-grid,body.smarttoolz-video-platform .stv-home-shell .stv-grid{grid-template-columns:repeat(' . $cols . ',minmax(0,1fr))}',
    );
    if ( empty( $s['likes_enabled'] ) ) { $rules[] = 'body.smarttoolz-video-platform [data-stv-reaction="like"]{display:none!important}'; }
    if ( empty( $s['dislikes_enabled'] ) ) { $rules[] = 'body.smarttoolz-video-platform [data-stv-reaction="dislike"]{display:none!important}'; }
    if ( empty( $s['sharing_enabled'] ) ) { $rules[] = 'body.smarttoolz-video-platform [data-stv-share],body.smarttoolz-video-platform [data-st-video-share]{display:none!important}'; }
    if ( empty( $s['subscriptions_enabled'] ) ) { $rules[] = 'body.smarttoolz-video-platform .stv-subscribe{display:none!important}'; }
    if ( empty( $s['uploads_enabled'] ) ) { $rules[] = 'body.smarttoolz-video-platform a[href*="video-upload"]{display:none!important}'; }
    if ( empty( $s['theater'] ) ) { $rules[] = 'body.smarttoolz-video-platform [data-stv-action="theater"]{display:none!important}'; }
    if ( empty( $s['pip'] ) ) { $rules[] = 'body.smarttoolz-video-platform [data-stv-action="pip"]{display:none!important}'; }
    echo '<style id="smarttoolz-video-settings-runtime">' . implode( '', $rules ) . '@media(max-width:1050px){body.smarttoolz-video-platform .stv-grid,body.smarttoolz-video-platform .stv-platform-page .stv-grid,body.smarttoolz-video-platform .stv-home-shell .stv-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}@media(max-width:800px){body.smarttoolz-video-platform .stv-grid,body.smarttoolz-video-platform .stv-platform-page .stv-grid,body.smarttoolz-video-platform .stv-home-shell .stv-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:480px){body.smarttoolz-video-platform .stv-grid,body.smarttoolz-video-platform .stv-platform-page .stv-grid,body.smarttoolz-video-platform .stv-home-shell .stv-grid{grid-template-columns:1fr}}</style>';
}
add_action( 'wp_head', 'smarttoolz_video_runtime_frontend_css', 60 );

function smarttoolz_video_runtime_upload_guard() {
    if ( empty( $_POST['st_video_frontend_action'] ) || ! function_exists( 'smarttoolz_video_setting' ) ) { return; }
    $max_mb = absint( smarttoolz_video_setting( 'upload_max_mb', 1024 ) );
    if ( $max_mb > 0 && ! empty( $_FILES['st_video_file']['size'] ) && (int) $_FILES['st_video_file']['size'] > ( $max_mb * 1024 * 1024 ) ) {
        wp_safe_redirect( add_query_arg( 'st_video_upload', 'file-too-large', wp_get_referer() ?: home_url( '/' ) ) );
        exit;
    }
}
add_action( 'template_redirect', 'smarttoolz_video_runtime_upload_guard', 0 );

function smarttoolz_video_runtime_remove_legacy_auth_submenu() {
    remove_submenu_page( 'edit.php?post_type=st_video', 'smarttoolz-video-auth' );
}
add_action( 'admin_menu', 'smarttoolz_video_runtime_remove_legacy_auth_submenu', 99 );
