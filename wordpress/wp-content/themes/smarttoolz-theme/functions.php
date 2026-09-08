<?php
/**
 * SmartToolz theme functions.
 *
 * @package SmartToolz
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'SMARTTOOLZ_VERSION', '2.1.0' );

function smarttoolz_setup() {
    load_theme_textdomain( 'smarttoolz', get_template_directory() . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 280, 'flex-height' => true, 'flex-width' => true ) );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
    add_post_type_support( 'post', 'post-formats' );
    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'smarttoolz' ),
        'footer'  => __( 'Footer Menu', 'smarttoolz' ),
    ) );
}
add_action( 'after_setup_theme', 'smarttoolz_setup' );

function smarttoolz_widgets() {
    register_sidebar( array(
        'name'          => __( 'Main Sidebar', 'smarttoolz' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Widgets shown beside posts and pages.', 'smarttoolz' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s st-card">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
    register_sidebar( array(
        'name'          => __( 'Footer Area', 'smarttoolz' ),
        'id'            => 'footer-1',
        'description'   => __( 'Optional footer widgets.', 'smarttoolz' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'smarttoolz_widgets' );

function smarttoolz_assets() {
    wp_enqueue_style( 'smarttoolz-style', get_stylesheet_uri(), array(), SMARTTOOLZ_VERSION );
}
add_action( 'wp_enqueue_scripts', 'smarttoolz_assets' );

function smarttoolz_body_class( $classes ) {
    $classes[] = 'smarttoolz-theme';
    return $classes;
}
add_filter( 'body_class', 'smarttoolz_body_class' );

function smarttoolz_excerpt_length() { return 28; }
add_filter( 'excerpt_length', 'smarttoolz_excerpt_length' );

function smarttoolz_excerpt_more() { return '…'; }
add_filter( 'excerpt_more', 'smarttoolz_excerpt_more' );

function smarttoolz_fallback_menu() {
    echo '<ul class="st-nav-list">';
    echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'smarttoolz' ) . '</a></li>';
    echo '</ul>';
}

function smarttoolz_customize_register( $wp_customize ) {
    $wp_customize->add_section( 'smarttoolz_theme_options', array(
        'title'    => __( 'SmartToolz Theme', 'smarttoolz' ),
        'priority' => 30,
    ) );
    $wp_customize->add_setting( 'smarttoolz_accent', array(
        'default'           => '#4f46e5',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'smarttoolz_accent', array(
        'label'   => __( 'Accent Color', 'smarttoolz' ),
        'section' => 'smarttoolz_theme_options',
    ) ) );
    $wp_customize->add_setting( 'smarttoolz_hero_title', array(
        'default'           => __( 'Useful ideas, guides and technology worth knowing.', 'smarttoolz' ),
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'smarttoolz_hero_title', array(
        'label'   => __( 'Homepage Hero Title', 'smarttoolz' ),
        'section' => 'smarttoolz_theme_options',
        'type'    => 'text',
    ) );
}
add_action( 'customize_register', 'smarttoolz_customize_register' );

function smarttoolz_customizer_css() {
    $accent = get_theme_mod( 'smarttoolz_accent', '#4f46e5' );
    if ( ! $accent ) { return; }
    echo '<style id="smarttoolz-customizer-css">:root{--st-primary:' . esc_html( $accent ) . ';}</style>';
}
add_action( 'wp_head', 'smarttoolz_customizer_css' );
