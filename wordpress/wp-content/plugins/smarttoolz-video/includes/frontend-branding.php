<?php
/**
 * SmartToolz Video frontend white-label branding.
 * The SmartToolz product name is kept for WordPress/admin surfaces only.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_frontend_branding_site_name() {
    $name = trim( (string) get_bloginfo( 'name' ) );
    return '' !== $name ? $name : 'Video';
}

function smarttoolz_video_frontend_branding_buffer_start() {
    if ( is_admin() || ! function_exists( 'smarttoolz_video_is_route' ) || ! smarttoolz_video_is_route() ) {
        return;
    }
    ob_start( 'smarttoolz_video_frontend_branding_buffer' );
}
add_action( 'template_redirect', 'smarttoolz_video_frontend_branding_buffer_start', 0 );

function smarttoolz_video_frontend_branding_buffer( $html ) {
    if ( ! is_string( $html ) || '' === $html ) { return $html; }
    $site_name = esc_html( smarttoolz_video_frontend_branding_site_name() );
    $replacements = array(
        'Sign in to SmartToolz' => 'Sign in to ' . $site_name,
        'Create your SmartToolz account' => 'Create your ' . $site_name . ' account',
        'Join SmartToolz, create your own channel and start sharing videos.' => 'Join ' . $site_name . ', create your own channel and start sharing videos.',
        'SmartToolz account • WordPress default login is not used.' => $site_name . ' account • WordPress default login is not used.',
        'Your SmartToolz account could not be created.' => 'Your ' . $site_name . ' account could not be created.',
        'SmartToolz Channel' => $site_name . ' Channel',
    );
    return str_replace( array_keys( $replacements ), array_values( $replacements ), $html );
}
