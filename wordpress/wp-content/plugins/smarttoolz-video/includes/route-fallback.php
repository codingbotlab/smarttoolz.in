<?php
/** Direct URL fallback for SmartToolz YouTube-style routes. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
function smarttoolz_video_direct_route_fallback( $wp ) {
    if ( ! empty( $wp->query_vars['smarttoolz_video_route'] ) ) { return; }
    $settings = smarttoolz_video_route_settings();
    $base = trim( $settings['home']['slug'] ?? 'video', '/' );
    $watch = trim( $settings['watch']['slug'] ?? 'watch', '/' );
    $path = trim( (string) parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
    $home_path = trim( parse_url( home_url( '/' ), PHP_URL_PATH ), '/' );
    if ( '' !== $home_path && 0 === strpos( $path, $home_path . '/' ) ) { $path = trim( substr( $path, strlen( $home_path ) + 1 ), '/' ); }
    $watch_path = trim( $base . '/' . $watch, '/' );
    if ( $path === $watch_path || 0 === strpos( $path, $watch_path . '/' ) ) {
        $wp->query_vars['smarttoolz_video_route'] = 'watch';
        $tail = trim( substr( $path, strlen( $watch_path ) ), '/' );
        if ( '' !== $tail ) { $wp->query_vars['smarttoolz_video_id'] = absint( $tail ); }
        if ( empty( $wp->query_vars['smarttoolz_video_id'] ) && isset( $_GET['v'] ) ) { $wp->query_vars['v'] = absint( wp_unslash( $_GET['v'] ) ); }
    }
}
add_action( 'parse_request', 'smarttoolz_video_direct_route_fallback', 20 );
