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

/**
 * Settings tabs submit only their own fields. WordPress sanitizes the submitted
 * array before updating the option, so a pre_update filter is too late to
 * reconstruct fields that were already replaced by defaults. This filter runs
 * with the original unsanitized value and merges it with the currently saved
 * option before applying the plugin's normal sanitizer.
 */
function smarttoolz_video_settings_preserve_tab_values( $value, $option, $original_value ) {
    if ( 'smarttoolz_video_settings' !== $option || ! is_array( $original_value ) ) { return $value; }

    $existing = (array) get_option( $option, array() );
    $defaults = function_exists( 'smarttoolz_video_default_settings' ) ? smarttoolz_video_default_settings() : array();
    $merged = wp_parse_args( $existing, $defaults );

    foreach ( $original_value as $key => $setting ) {
        if ( is_string( $key ) && 'smarttoolz_video_settings_tab' !== $key ) {
            $merged[ $key ] = $setting;
        }
    }

    $active = isset( $_POST['smarttoolz_video_settings_tab'] )
        ? sanitize_key( wp_unslash( $_POST['smarttoolz_video_settings_tab'] ) )
        : '';

    $tab_bools = array(
        'general'  => array( 'frontend_profile' ),
        'player'   => array( 'autoplay', 'muted', 'loop', 'theater', 'pip', 'double_click_fullscreen' ),
        'icons'    => array( 'icon_enabled' ),
        'auth'     => array( 'google_enabled' ),
        'uploads'  => array( 'uploads_enabled', 'require_thumbnail' ),
        'features' => array( 'likes_enabled', 'dislikes_enabled', 'subscriptions_enabled', 'history_enabled', 'sharing_enabled', 'comments_enabled' ),
        'pages'    => array(),
    );

    if ( isset( $tab_bools[ $active ] ) ) {
        foreach ( $tab_bools[ $active ] as $key ) {
            if ( ! array_key_exists( $key, $original_value ) ) {
                $merged[ $key ] = 0;
            }
        }
    }

    return smarttoolz_video_sanitize_settings( $merged );
}
add_filter( 'sanitize_option_smarttoolz_video_settings', 'smarttoolz_video_settings_preserve_tab_values', 10, 3 );

/** Add active tab marker to the existing Settings API form. */
function smarttoolz_video_settings_active_tab_field() {
    $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
    if ( ! $screen || false === strpos( (string) $screen->id, 'smarttoolz-video' ) ) { return; }
    $tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'general';
    echo '<script>document.addEventListener("DOMContentLoaded",function(){var f=document.querySelector("form[action*=options.php]");if(!f||f.querySelector("input[name=smarttoolz_video_settings_tab]"))return;var i=document.createElement("input");i.type="hidden";i.name="smarttoolz_video_settings_tab";i.value=' . wp_json_encode( $tab ) . ';f.appendChild(i);});</script>';
}
add_action( 'admin_footer', 'smarttoolz_video_settings_active_tab_field', 99 );
