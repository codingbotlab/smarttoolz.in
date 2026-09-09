<?php
/**
 * SmartToolz Video bootstrap.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function smarttoolz_video_page_definitions() {
    return array(
        'home' => array( 'title' => 'Home', 'slug' => 'video' ),
        'shorts' => array( 'title' => 'Shorts', 'slug' => 'video/shorts' ),
        'subscriptions' => array( 'title' => 'Subscriptions', 'slug' => 'video/subscriptions' ),
        'search' => array( 'title' => 'Search Results', 'slug' => 'video/search' ),
        'channel' => array( 'title' => 'Channel', 'slug' => 'video/channel' ),
        'channel-videos' => array( 'title' => 'Channel Videos', 'slug' => 'video/channel/videos' ),
        'channel-shorts' => array( 'title' => 'Channel Shorts', 'slug' => 'video/channel/shorts' ),
        'channel-live' => array( 'title' => 'Channel Live', 'slug' => 'video/channel/live' ),
        'playlists' => array( 'title' => 'Playlists', 'slug' => 'video/playlists' ),
        'watch-later' => array( 'title' => 'Watch Later', 'slug' => 'video/watch-later' ),
        'history' => array( 'title' => 'History', 'slug' => 'video/history' ),
        'liked-videos' => array( 'title' => 'Liked Videos', 'slug' => 'video/liked' ),
        'your-videos' => array( 'title' => 'Your Videos', 'slug' => 'video/your-videos' ),
        'trending' => array( 'title' => 'Trending', 'slug' => 'video/trending' ),
        'explore' => array( 'title' => 'Explore', 'slug' => 'video/explore' ),
        'live' => array( 'title' => 'Live', 'slug' => 'video/live' ),
        'memberships' => array( 'title' => 'Memberships', 'slug' => 'video/memberships' ),
        'purchases' => array( 'title' => 'Purchases', 'slug' => 'video/purchases' ),
        'login' => array( 'title' => 'Login', 'slug' => 'video/login' ),
        'signup' => array( 'title' => 'Sign Up', 'slug' => 'video/signup' ),
        'forgot-password' => array( 'title' => 'Forgot Password', 'slug' => 'video/forgot-password' ),
        'settings' => array( 'title' => 'Settings', 'slug' => 'video/settings' ),
        'notifications' => array( 'title' => 'Notifications', 'slug' => 'video/notifications' ),
        'upload' => array( 'title' => 'Upload Video', 'slug' => 'video/upload' ),
    );
}

function smarttoolz_video_sync_pages() {
    $pages = smarttoolz_video_page_definitions();
    $ids = (array) get_option( 'smarttoolz_video_page_ids', array() );
    $changed = false;

    foreach ( $pages as $key => $page ) {
        $page_id = isset( $ids[ $key ] ) ? absint( $ids[ $key ] ) : 0;
        $valid = $page_id && 'page' === get_post_type( $page_id ) && 'trash' !== get_post_status( $page_id );

        if ( ! $valid ) {
            $existing = get_page_by_path( $page['slug'], OBJECT, 'page' );
            if ( $existing && 'trash' !== get_post_status( $existing->ID ) ) {
                $page_id = $existing->ID;
            } else {
                $page_id = wp_insert_post(
                    array(
                        'post_title' => $page['title'],
                        'post_name' => basename( $page['slug'] ),
                        'post_status' => 'publish',
                        'post_type' => 'page',
                        'post_content' => '<!-- SmartToolz Video page: ' . esc_html( $key ) . ' -->',
                    ),
                    true
                );
                if ( is_wp_error( $page_id ) ) {
                    continue;
                }
            }

            $ids[ $key ] = absint( $page_id );
            $changed = true;
        }
    }

    if ( $changed || ! get_option( 'smarttoolz_video_page_ids', false ) ) {
        update_option( 'smarttoolz_video_page_ids', $ids, false );
    }
}

function smarttoolz_video_set_static_homepage() {
    smarttoolz_video_sync_pages();
    $ids = (array) get_option( 'smarttoolz_video_page_ids', array() );
    $home_id = isset( $ids['home'] ) ? absint( $ids['home'] ) : 0;

    if ( ! $home_id || 'page' !== get_post_type( $home_id ) || 'trash' === get_post_status( $home_id ) ) {
        return false;
    }

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $home_id );

    return true;
}

function smarttoolz_video_page_sync_cron() {
    if ( ! wp_next_scheduled( 'smarttoolz_video_page_sync_event' ) ) {
        wp_schedule_event( time() + HOUR_IN_SECONDS, 'twicedaily', 'smarttoolz_video_page_sync_event' );
    }
}
add_action( 'smarttoolz_video_page_sync_event', 'smarttoolz_video_sync_pages' );

function smarttoolz_video_register_routes() {
    add_rewrite_rule( '^video/?$', 'index.php?smarttoolz_video_route=home', 'top' );
    add_rewrite_rule( '^video/watch/([^/]+)/?$', 'index.php?smarttoolz_video_route=watch&smarttoolz_video_id=$matches[1]', 'top' );
    add_rewrite_rule( '^video/(shorts|subscriptions|search|channel|playlists|watch-later|history|liked|your-videos|trending|explore|live|memberships|purchases|login|signup|forgot-password|settings|notifications|upload)/?$', 'index.php?smarttoolz_video_route=$matches[1]', 'top' );
    add_rewrite_rule( '^video/channel/(videos|shorts|live)/?$', 'index.php?smarttoolz_video_route=channel-$matches[1]', 'top' );
}
add_action( 'init', 'smarttoolz_video_register_routes' );

function smarttoolz_video_register_query_vars( $vars ) {
    $vars[] = 'smarttoolz_video_route';
    $vars[] = 'smarttoolz_video_id';
    return $vars;
}
add_filter( 'query_vars', 'smarttoolz_video_register_query_vars' );

function smarttoolz_video_is_route() {
    return (bool) get_query_var( 'smarttoolz_video_route' );
}

function smarttoolz_video_route_url( $route = 'home', $id = '' ) {
    $base = home_url( '/video/' );

    if ( 'watch' === $route && '' !== $id ) {
        return trailingslashit( $base . 'watch/' . rawurlencode( (string) $id ) );
    }

    if ( 'home' !== $route ) {
        return trailingslashit( $base . trim( str_replace( '-', '/', (string) $route ), '/' ) . '/' );
    }

    return $base;
}

function smarttoolz_video_activate_plugin() {
    smarttoolz_video_register_routes();
    smarttoolz_video_sync_pages();
    smarttoolz_video_page_sync_cron();
    flush_rewrite_rules();
}

function smarttoolz_video_deactivate_plugin() {
    wp_clear_scheduled_hook( 'smarttoolz_video_page_sync_event' );
    flush_rewrite_rules();
}

add_action( 'init', 'smarttoolz_video_sync_pages', 20 );
add_action( 'init', 'smarttoolz_video_page_sync_cron', 21 );
