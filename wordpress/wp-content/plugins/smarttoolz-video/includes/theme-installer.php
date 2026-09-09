<?php
/**
 * SmartToolz bundled theme installer and activator.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_theme_name() {
    return 'SmartToolz Video Theme';
}

function smarttoolz_video_theme_slug() {
    return 'smarttoolz-video-theme';
}

function smarttoolz_video_theme_source_dir() {
    return trailingslashit( SMARTTOOLZ_VIDEO_DIR . 'theme-src' );
}

function smarttoolz_video_theme_target_dir() {
    return trailingslashit( WP_CONTENT_DIR . '/themes/' . smarttoolz_video_theme_slug() );
}

function smarttoolz_video_theme_source_files() {
    return array( 'style.css', 'functions.php', 'header.php', 'footer.php', 'index.php', 'front-page.php', 'page.php' );
}

function smarttoolz_video_theme_installed() {
    return file_exists( smarttoolz_video_theme_target_dir() . 'style.css' )
        && file_exists( smarttoolz_video_theme_target_dir() . 'functions.php' )
        && file_exists( smarttoolz_video_theme_target_dir() . 'page.php' );
}

function smarttoolz_video_install_theme() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return new WP_Error( 'forbidden', 'You do not have permission to install the theme.' );
    }

    $source = smarttoolz_video_theme_source_dir();
    $target = smarttoolz_video_theme_target_dir();

    if ( ! is_dir( $source ) ) {
        return new WP_Error( 'missing_source', 'Bundled SmartToolz theme files are missing.' );
    }

    if ( ! wp_mkdir_p( $target ) ) {
        return new WP_Error( 'cannot_create_theme_dir', 'Could not create the SmartToolz theme directory.' );
    }

    foreach ( smarttoolz_video_theme_source_files() as $file ) {
        $from = $source . $file;
        $to   = $target . $file;
        if ( ! file_exists( $from ) || ! copy( $from, $to ) ) {
            return new WP_Error( 'copy_failed', 'Could not install theme file: ' . $file );
        }
    }

    update_option( 'smarttoolz_video_theme_installed', 1, false );
    return true;
}

function smarttoolz_video_activate_theme() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return new WP_Error( 'forbidden', 'You do not have permission to activate the theme.' );
    }

    if ( ! smarttoolz_video_theme_installed() ) {
        $installed = smarttoolz_video_install_theme();
        if ( is_wp_error( $installed ) ) {
            return $installed;
        }
    }

    switch_theme( smarttoolz_video_theme_slug() );

    if ( function_exists( 'smarttoolz_video_sync_pages' ) ) {
        smarttoolz_video_sync_pages();
    }
    if ( function_exists( 'smarttoolz_video_set_static_homepage' ) ) {
        smarttoolz_video_set_static_homepage( false );
    }

    return true;
}

function smarttoolz_video_theme_is_active() {
    return smarttoolz_video_theme_slug() === get_stylesheet();
}
