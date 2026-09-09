<?php
/**
 * SmartToolz Video Theme functions.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_theme_assets() {
    wp_enqueue_style( 'smarttoolz-video-theme', get_stylesheet_uri(), array(), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'smarttoolz_video_theme_assets' );

function smarttoolz_video_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
}
add_action( 'after_setup_theme', 'smarttoolz_video_theme_setup' );
