<?php
/**
 * SmartToolz theme functions.
 *
 * @package SmartToolz
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'SMARTTOOLZ_VERSION', '2.0.0' );

function smarttoolz_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
    add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 280, 'flex-height' => true, 'flex-width' => true ) );
    register_nav_menus( array( 'primary' => __( 'Primary Menu', 'smarttoolz' ) ) );
}
add_action( 'after_setup_theme', 'smarttoolz_setup' );

function smarttoolz_assets() {
    wp_enqueue_style( 'smarttoolz-style', get_stylesheet_uri(), array(), SMARTTOOLZ_VERSION );
}
add_action( 'wp_enqueue_scripts', 'smarttoolz_assets' );

function smarttoolz_body_class( $classes ) {
    $classes[] = 'smarttoolz-theme';
    return $classes;
}
add_filter( 'body_class', 'smarttoolz_body_class' );

function smarttoolz_excerpt_length() {
    return 24;
}
add_filter( 'excerpt_length', 'smarttoolz_excerpt_length' );

function smarttoolz_excerpt_more() {
    return '…';
}
add_filter( 'excerpt_more', 'smarttoolz_excerpt_more' );

function smarttoolz_fallback_menu() {
    echo '<ul class="st-nav-list">';
    echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'smarttoolz' ) . '</a></li>';
    echo '</ul>';
}
