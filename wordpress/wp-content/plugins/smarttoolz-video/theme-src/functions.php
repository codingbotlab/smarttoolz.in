<?php
/**
 * SmartToolz Video Theme functions.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_theme_assets() {
    wp_enqueue_style( 'smarttoolz-video-theme', get_stylesheet_uri(), array(), '1.2.7' );
}
add_action( 'wp_enqueue_scripts', 'smarttoolz_video_theme_assets' );

function smarttoolz_video_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 320, 'flex-height' => true, 'flex-width' => true ) );
    add_theme_support( 'site-icon' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
}
add_action( 'after_setup_theme', 'smarttoolz_video_theme_setup' );

/* Only WordPress administrators should see the native WP admin bar. */
function smarttoolz_video_admin_bar_visibility( $show ) {
    if ( is_admin() ) { return $show; }
    return current_user_can( 'manage_options' );
}
add_filter( 'show_admin_bar', 'smarttoolz_video_admin_bar_visibility', 999 );

/* Load the public creator-channel renderer bundled with the plugin. */
if ( defined( 'SMARTTOOLZ_VIDEO_DIR' ) ) {
    $smarttoolz_channel_file = SMARTTOOLZ_VIDEO_DIR . 'includes/channel.php';
    if ( file_exists( $smarttoolz_channel_file ) ) {
        require_once $smarttoolz_channel_file;
    }
    $smarttoolz_branding_file = SMARTTOOLZ_VIDEO_DIR . 'includes/frontend-branding.php';
    if ( file_exists( $smarttoolz_branding_file ) ) {
        require_once $smarttoolz_branding_file;
    }
}

/* Frontend branding always comes from the WordPress site owner. */
function smarttoolz_video_theme_site_name() {
    $name = trim( (string) get_bloginfo( 'name' ) );
    return '' !== $name ? $name : 'Video';
}

function smarttoolz_video_theme_brand_logo() {
    $custom_logo_id = absint( get_theme_mod( 'custom_logo' ) );
    if ( $custom_logo_id ) {
        $image = wp_get_attachment_image(
            $custom_logo_id,
            'full',
            false,
            array(
                'class' => 'stv-brand__logo',
                'alt' => smarttoolz_video_theme_site_name(),
                'loading' => false,
            )
        );
        if ( $image ) { return $image; }
    }

    $site_icon = get_site_icon_url( 96 );
    if ( $site_icon ) {
        return '<img class="stv-brand__logo stv-brand__logo--icon" src="' . esc_url( $site_icon ) . '" alt="' . esc_attr( smarttoolz_video_theme_site_name() ) . '">';
    }

    return '<span class="stv-brand__mark" aria-hidden="true">▶</span>';
}

/* Central URL helper: all frontend navigation follows routing settings. */
function smarttoolz_video_theme_page_url( $key, $fallback = '' ) {
    if ( function_exists( 'smarttoolz_video_route_url' ) ) {
        $url = smarttoolz_video_route_url( $key );
        if ( $url ) { return $url; }
    }

    $ids = (array) get_option( 'smarttoolz_video_page_ids', array() );
    $page_id = isset( $ids[ $key ] ) ? absint( $ids[ $key ] ) : 0;
    if ( $page_id && 'page' === get_post_type( $page_id ) && 'trash' !== get_post_status( $page_id ) ) {
        $url = get_permalink( $page_id );
        if ( $url ) { return $url; }
    }
    return $fallback ? $fallback : home_url( '/' );
}

function smarttoolz_video_theme_search_url() {
    if ( function_exists( 'smarttoolz_video_route_url' ) ) {
        $url = smarttoolz_video_route_url( 'search' );
        if ( $url ) { return $url; }
    }
    return home_url( '/video/results/' );
}

function smarttoolz_video_theme_channel_url( $user_id = 0 ) {
    $user_id = $user_id ? absint( $user_id ) : get_current_user_id();
    $user = $user_id ? get_userdata( $user_id ) : false;
    $handle = $user ? get_user_meta( $user_id, 'smarttoolz_channel_handle', true ) : '';
    if ( ! $handle && $user ) { $handle = $user->user_nicename; }

    if ( function_exists( 'smarttoolz_video_route_url' ) && $handle ) {
        $url = smarttoolz_video_route_url( 'channel', $handle );
        if ( $url ) { return $url; }
    }
    return home_url( '/video/@' . rawurlencode( (string) $handle ) . '/' );
}
