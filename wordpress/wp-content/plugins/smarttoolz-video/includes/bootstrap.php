<?php
/**
 * SmartToolz Video bootstrap.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once SMARTTOOLZ_VIDEO_DIR . 'includes/videos.php';

function smarttoolz_video_page_definitions() {
    return array(
        'home' => array( 'title' => 'Home', 'slug' => 'video', 'parent' => '' ),
        'shorts' => array( 'title' => 'Shorts', 'slug' => 'shorts', 'parent' => 'home' ),
        'subscriptions' => array( 'title' => 'Subscriptions', 'slug' => 'subscriptions', 'parent' => 'home' ),
        'search' => array( 'title' => 'Search Results', 'slug' => 'search', 'parent' => 'home' ),
        'channel' => array( 'title' => 'Channel', 'slug' => 'channel', 'parent' => 'home' ),
        'channel-videos' => array( 'title' => 'Channel Videos', 'slug' => 'videos', 'parent' => 'channel' ),
        'channel-shorts' => array( 'title' => 'Channel Shorts', 'slug' => 'shorts', 'parent' => 'channel' ),
        'channel-live' => array( 'title' => 'Channel Live', 'slug' => 'live', 'parent' => 'channel' ),
        'playlists' => array( 'title' => 'Playlists', 'slug' => 'playlists', 'parent' => 'home' ),
        'watch-later' => array( 'title' => 'Watch Later', 'slug' => 'watch-later', 'parent' => 'home' ),
        'history' => array( 'title' => 'History', 'slug' => 'history', 'parent' => 'home' ),
        'liked-videos' => array( 'title' => 'Liked Videos', 'slug' => 'liked', 'parent' => 'home' ),
        'your-videos' => array( 'title' => 'Your Videos', 'slug' => 'your-videos', 'parent' => 'home' ),
        'trending' => array( 'title' => 'Trending', 'slug' => 'trending', 'parent' => 'home' ),
        'explore' => array( 'title' => 'Explore', 'slug' => 'explore', 'parent' => 'home' ),
        'live' => array( 'title' => 'Live', 'slug' => 'live', 'parent' => 'home' ),
        'memberships' => array( 'title' => 'Memberships', 'slug' => 'memberships', 'parent' => 'home' ),
        'purchases' => array( 'title' => 'Purchases', 'slug' => 'purchases', 'parent' => 'home' ),
        'account' => array( 'title' => 'Your Account', 'slug' => 'account', 'parent' => 'home' ),
        'edit-profile' => array( 'title' => 'Edit Profile', 'slug' => 'edit-profile', 'parent' => 'account' ),
        'comments' => array( 'title' => 'Comments & Activity', 'slug' => 'comments', 'parent' => 'account' ),
        'badges' => array( 'title' => 'Badges', 'slug' => 'badges', 'parent' => 'account' ),
        'podcasts' => array( 'title' => 'Your Podcasts', 'slug' => 'podcasts', 'parent' => 'account' ),
        'privacy' => array( 'title' => 'Privacy & Your Data', 'slug' => 'privacy', 'parent' => 'account' ),
        'login' => array( 'title' => 'Login', 'slug' => 'login', 'parent' => 'home' ),
        'signup' => array( 'title' => 'Sign Up', 'slug' => 'signup', 'parent' => 'home' ),
        'forgot-password' => array( 'title' => 'Forgot Password', 'slug' => 'forgot-password', 'parent' => 'home' ),
        'settings' => array( 'title' => 'Settings', 'slug' => 'settings', 'parent' => 'home' ),
        'notifications' => array( 'title' => 'Notifications', 'slug' => 'notifications', 'parent' => 'home' ),
        'upload' => array( 'title' => 'Upload Video', 'slug' => 'upload', 'parent' => 'home' ),
    );
}

function smarttoolz_video_page_path( $key, $definitions = null ) {
    $definitions = is_array( $definitions ) ? $definitions : smarttoolz_video_page_definitions();
    if ( ! isset( $definitions[ $key ] ) ) { return ''; }
    $page = $definitions[ $key ];
    if ( empty( $page['parent'] ) ) { return trim( $page['slug'], '/' ); }
    $parent_path = smarttoolz_video_page_path( $page['parent'], $definitions );
    return trim( $parent_path . '/' . trim( $page['slug'], '/' ), '/' );
}

function smarttoolz_video_sync_pages() {
    $pages = smarttoolz_video_page_definitions();
    $ids = (array) get_option( 'smarttoolz_video_page_ids', array() );
    $changed = false;
    foreach ( $pages as $key => $page ) {
        $page_id = isset( $ids[ $key ] ) ? absint( $ids[ $key ] ) : 0;
        $valid = $page_id && 'page' === get_post_type( $page_id ) && 'trash' !== get_post_status( $page_id );
        if ( ! $valid ) {
            $path = smarttoolz_video_page_path( $key, $pages );
            $existing = get_page_by_path( $path, OBJECT, 'page' );
            if ( $existing && 'trash' !== get_post_status( $existing->ID ) ) {
                $page_id = $existing->ID;
            } else {
                $parent_id = 0;
                if ( ! empty( $page['parent'] ) && isset( $ids[ $page['parent'] ] ) ) { $parent_id = absint( $ids[ $page['parent'] ] ); }
                $page_id = wp_insert_post(array('post_title'=>$page['title'],'post_name'=>$page['slug'],'post_parent'=>$parent_id,'post_status'=>'publish','post_type'=>'page','post_content'=>'<!-- SmartToolz Video page: ' . esc_html($key) . ' -->'), true);
                if ( is_wp_error( $page_id ) ) { continue; }
            }
            $ids[ $key ] = absint( $page_id );
            $changed = true;
        }
        $parent_id = 0;
        if ( ! empty( $page['parent'] ) && isset( $ids[ $page['parent'] ] ) ) { $parent_id = absint( $ids[ $page['parent'] ] ); }
        $updates = array();
        if ( absint( get_post_field( 'post_parent', $page_id ) ) !== $parent_id ) { $updates['ID'] = $page_id; $updates['post_parent'] = $parent_id; }
        if ( get_post_field( 'post_name', $page_id ) !== $page['slug'] ) { $updates['ID'] = $page_id; $updates['post_name'] = $page['slug']; }
        if ( get_the_title( $page_id ) !== $page['title'] ) { $updates['ID'] = $page_id; $updates['post_title'] = $page['title']; }
        if ( ! empty( $updates ) ) { wp_update_post( $updates ); $changed = true; }
    }
    if ( $changed || ! get_option( 'smarttoolz_video_page_ids', false ) ) { update_option( 'smarttoolz_video_page_ids', $ids, false ); }
    smarttoolz_video_set_static_homepage( false );
}

function smarttoolz_video_set_static_homepage( $sync = true ) {
    if ( $sync ) { smarttoolz_video_sync_pages(); }
    $ids = (array) get_option( 'smarttoolz_video_page_ids', array() );
    $home_id = isset( $ids['home'] ) ? absint( $ids['home'] ) : 0;
    if ( ! $home_id || 'page' !== get_post_type( $home_id ) || 'trash' === get_post_status( $home_id ) ) { return false; }
    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $home_id );
    return true;
}

function smarttoolz_video_page_sync_cron() {
    if ( ! wp_next_scheduled( 'smarttoolz_video_page_sync_event' ) ) { wp_schedule_event( time() + HOUR_IN_SECONDS, 'twicedaily', 'smarttoolz_video_page_sync_event' ); }
}
add_action( 'smarttoolz_video_page_sync_event', 'smarttoolz_video_sync_pages' );

function smarttoolz_video_register_routes() {
    add_rewrite_rule( '^video/?$', 'index.php?smarttoolz_video_route=home', 'top' );
    add_rewrite_rule( '^video/watch/([^/]+)/?$', 'index.php?smarttoolz_video_route=watch&smarttoolz_video_id=$matches[1]', 'top' );
    add_rewrite_rule( '^video/account/(edit-profile|comments|badges|podcasts|privacy)/?$', 'index.php?smarttoolz_video_route=account-$matches[1]', 'top' );
    add_rewrite_rule( '^video/(shorts|subscriptions|search|channel|playlists|watch-later|history|liked|your-videos|trending|explore|live|memberships|purchases|account|login|signup|forgot-password|settings|notifications|upload)/?$', 'index.php?smarttoolz_video_route=$matches[1]', 'top' );
    add_rewrite_rule( '^video/channel/(videos|shorts|live)/?$', 'index.php?smarttoolz_video_route=channel-$matches[1]', 'top' );
}
add_action( 'init', 'smarttoolz_video_register_routes' );

function smarttoolz_video_register_query_vars( $vars ) { $vars[] = 'smarttoolz_video_route'; $vars[] = 'smarttoolz_video_id'; return $vars; }
add_filter( 'query_vars', 'smarttoolz_video_register_query_vars' );
function smarttoolz_video_is_route() { return (bool) get_query_var( 'smarttoolz_video_route' ); }
function smarttoolz_video_route_url( $route = 'home', $id = '' ) {
    $base = home_url( '/video/' );
    if ( 'watch' === $route && '' !== $id ) { return trailingslashit( $base . 'watch/' . rawurlencode( (string) $id ) ); }
    if ( 'home' !== $route ) { return trailingslashit( $base . trim( str_replace( '-', '/', (string) $route ), '/' ) . '/' ); }
    return $base;
}

function smarttoolz_video_activate_plugin() {
    smarttoolz_video_register_routes();
    flush_rewrite_rules();
    smarttoolz_video_sync_pages();
    smarttoolz_video_page_sync_cron();
    smarttoolz_video_set_static_homepage( false );
}
function smarttoolz_video_deactivate_plugin() { wp_clear_scheduled_hook( 'smarttoolz_video_page_sync_event' ); flush_rewrite_rules(); }
add_action( 'init', 'smarttoolz_video_sync_pages', 20 );
add_action( 'init', 'smarttoolz_video_page_sync_cron', 21 );
