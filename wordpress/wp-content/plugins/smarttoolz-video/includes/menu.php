<?php
/** SmartToolz Video navigation menu integration. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_menu_items() {
    return array(
        'Video Home'      => smarttoolz_video_page_link( 'video-home' ),
        'Videos'          => get_post_type_archive_link( 'st_video' ) ?: smarttoolz_video_page_link( 'video-library' ),
        'Trending'        => smarttoolz_video_page_link( 'trending-videos' ),
        'Categories'      => smarttoolz_video_page_link( 'video-categories' ),
        'Search Videos'   => smarttoolz_video_page_link( 'video-search' ),
        'Subscriptions'   => smarttoolz_video_page_link( 'subscriptions' ),
        'Liked Videos'    => smarttoolz_video_page_link( 'liked-videos' ),
        'Watch History'   => smarttoolz_video_page_link( 'watch-history' ),
        'Upload Video'    => smarttoolz_video_page_link( 'video-upload' ),
        'Creator Studio' => smarttoolz_video_page_link( 'creator-studio' ),
        'My Channel'      => smarttoolz_video_page_link( 'channel' ),
    );
}

function smarttoolz_video_register_menu_location() {
    register_nav_menus( array( 'video_platform' => __( 'Video Platform Menu', 'smarttoolz-video' ) ) );
}
add_action( 'after_setup_theme', 'smarttoolz_video_register_menu_location', 20 );

function smarttoolz_video_create_menu() {
    if ( ! function_exists( 'wp_create_nav_menu' ) || ! function_exists( 'wp_update_nav_menu_item' ) ) { return; }
    $menu_name = 'SmartToolz Video';
    $menu      = wp_get_nav_menu_object( $menu_name );
    $menu_id   = $menu ? (int) $menu->term_id : (int) wp_create_nav_menu( $menu_name );
    if ( ! $menu_id ) { return; }

    $existing = wp_get_nav_menu_items( $menu_id );
    $existing_map = array();
    if ( $existing ) {
        foreach ( $existing as $item ) { $existing_map[ $item->url ] = true; }
    }
    foreach ( smarttoolz_video_menu_items() as $title => $url ) {
        if ( ! $url || isset( $existing_map[ $url ] ) ) { continue; }
        wp_update_nav_menu_item( $menu_id, 0, array(
            'menu-item-title'  => $title,
            'menu-item-url'    => $url,
            'menu-item-status' => 'publish',
        ) );
    }

    $locations = get_theme_mod( 'nav_menu_locations' );
    if ( ! is_array( $locations ) ) { $locations = array(); }
    if ( empty( $locations['video_platform'] ) ) {
        $locations['video_platform'] = $menu_id;
        set_theme_mod( 'nav_menu_locations', $locations );
    }
}

function smarttoolz_video_menu_activation() {
    smarttoolz_video_create_menu();
}
add_action( 'smarttoolz_video_after_pages_sync', 'smarttoolz_video_create_menu' );
