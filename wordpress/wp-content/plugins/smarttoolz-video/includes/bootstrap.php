<?php
/**
 * SmartToolz Video bootstrap.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function smarttoolz_video_register_routes() {
    add_rewrite_rule( '^video/?$', 'index.php?smarttoolz_video_route=home', 'top' );
    add_rewrite_rule( '^video/watch/([^/]+)/?$', 'index.php?smarttoolz_video_route=watch&smarttoolz_video_id=$matches[1]', 'top' );
    add_rewrite_rule( '^video/upload/?$', 'index.php?smarttoolz_video_route=upload', 'top' );
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

    if ( 'upload' === $route ) {
        return trailingslashit( $base . 'upload/' );
    }

    return $base;
}

function smarttoolz_video_activate_plugin() {
    smarttoolz_video_register_routes();
    flush_rewrite_rules();
}

function smarttoolz_video_deactivate_plugin() {
    flush_rewrite_rules();
}
