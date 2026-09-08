<?php
/**
 * SmartToolz Video automatic platform pages and safe navigation helpers.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_auto_page_definitions() {
    return array(
        'video-home'       => array( 'title' => 'Video Home', 'content' => '[smarttoolz_video_home]' ),
        'video-library'    => array( 'title' => 'Videos', 'content' => '[smarttoolz_video_platform]' ),
        'trending-videos'  => array( 'title' => 'Trending Videos', 'content' => '[smarttoolz_video_trending]' ),
        'video-categories' => array( 'title' => 'Video Categories', 'content' => '[smarttoolz_video_categories]' ),
        'video-search'     => array( 'title' => 'Search Videos', 'content' => '[smarttoolz_video_search]' ),
        'subscriptions'   => array( 'title' => 'Subscriptions', 'content' => '[smarttoolz_video_subscriptions]' ),
        'liked-videos'     => array( 'title' => 'Liked Videos', 'content' => '[smarttoolz_video_liked]' ),
        'watch-history'    => array( 'title' => 'Watch History', 'content' => '[smarttoolz_video_history]' ),
        'video-upload'     => array( 'title' => 'Upload Video', 'content' => '[smarttoolz_video_upload]' ),
        'creator-studio'   => array( 'title' => 'Creator Studio', 'content' => '[smarttoolz_creator_dashboard]' ),
        'channel'          => array( 'title' => 'Channel', 'content' => '[smarttoolz_channel]' ),
    );
}

function smarttoolz_video_page_link( $slug ) {
    $ids = (array) get_option( 'smarttoolz_video_page_ids', array() );
    if ( ! empty( $ids[ $slug ] ) ) {
        $url = get_permalink( (int) $ids[ $slug ] );
        if ( $url ) { return $url; }
    }
    if ( 'video-library' === $slug ) {
        $archive = get_post_type_archive_link( 'st_video' );
        if ( $archive ) { return $archive; }
        return home_url( '/videos/' );
    }
    return home_url( '/' . trim( $slug, '/' ) . '/' );
}

function smarttoolz_video_menu_items() {
    $items = array(
        'Video Home'       => smarttoolz_video_page_link( 'video-home' ),
        'Videos'           => smarttoolz_video_page_link( 'video-library' ),
        'Trending'         => smarttoolz_video_page_link( 'trending-videos' ),
        'Categories'       => smarttoolz_video_page_link( 'video-categories' ),
        'Search'           => smarttoolz_video_page_link( 'video-search' ),
    );
    if ( is_user_logged_in() ) {
        $items['Subscriptions'] = smarttoolz_video_page_link( 'subscriptions' );
        $items['Liked']         = smarttoolz_video_page_link( 'liked-videos' );
        $items['History']       = smarttoolz_video_page_link( 'watch-history' );
        $items['Upload']        = smarttoolz_video_page_link( 'video-upload' );
        $items['Creator Studio'] = smarttoolz_video_page_link( 'creator-studio' );
        $items['My Channel']    = smarttoolz_video_page_link( 'channel' );
    }
    return $items;
}

function smarttoolz_video_create_managed_pages() {
    $ids = array();
    foreach ( smarttoolz_video_auto_page_definitions() as $slug => $page ) {
        $existing = get_page_by_path( $slug, OBJECT, 'page' );
        if ( $existing instanceof WP_Post ) {
            $ids[ $slug ] = (int) $existing->ID;
            update_post_meta( $existing->ID, '_smarttoolz_video_managed', 1 );
            continue;
        }
        $new_id = wp_insert_post(
            array(
                'post_type'    => 'page',
                'post_status'  => 'publish',
                'post_title'   => $page['title'],
                'post_name'    => $slug,
                'post_content' => $page['content'],
                'post_author'  => get_current_user_id() ?: 1,
            ),
            true
        );
        if ( ! is_wp_error( $new_id ) ) {
            $ids[ $slug ] = (int) $new_id;
            update_post_meta( $new_id, '_smarttoolz_video_managed', 1 );
        }
    }
    update_option( 'smarttoolz_video_page_ids', $ids, false );
    update_option( 'smarttoolz_video_pages_version', '4.2.0', false );
    flush_rewrite_rules( true );
    do_action( 'smarttoolz_video_after_pages_sync' );
}

register_activation_hook( SMARTTOOLZ_VIDEO_FILE, 'smarttoolz_video_create_managed_pages' );
