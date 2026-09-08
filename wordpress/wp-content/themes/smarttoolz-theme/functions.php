<?php
/**
 * SmartToolz theme functions.
 *
 * @package SmartToolz
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'SMARTTOOLZ_VERSION', '1.0.0' );

action_exists( 'wp_enqueue_scripts' );

add_action( 'after_setup_theme', 'smarttoolz_setup' );
function smarttoolz_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
    add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 280, 'flex-height' => true, 'flex-width' => true ) );
    register_nav_menus( array( 'primary' => __( 'Primary Menu', 'smarttoolz' ) ) );
}

add_action( 'wp_enqueue_scripts', 'smarttoolz_assets' );
function smarttoolz_assets() {
    wp_enqueue_style( 'smarttoolz-style', get_stylesheet_uri(), array(), SMARTTOOLZ_VERSION );
}

add_filter( 'body_class', 'smarttoolz_body_class' );
function smarttoolz_body_class( $classes ) {
    $classes[] = 'smarttoolz-theme';
    return $classes;
}
