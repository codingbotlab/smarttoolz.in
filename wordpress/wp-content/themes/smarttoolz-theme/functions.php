<?php
/**
 * SmartToolz production theme functions.
 *
 * @package SmartToolz
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

define( 'SMARTTOOLZ_VERSION', '3.1.0' );

function smarttoolz_setup() {
    load_theme_textdomain( 'smarttoolz', get_template_directory() . '/languages' );
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'custom-logo', array( 'height' => 80, 'width' => 280, 'flex-height' => true, 'flex-width' => true ) );
    add_theme_support( 'custom-background', array( 'default-color' => 'f5f7fb' ) );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
    add_theme_support( 'editor-styles' );
    add_post_type_support( 'post', 'post-formats' );
    add_image_size( 'smarttoolz-card', 640, 400, true );
    register_nav_menus( array( 'primary' => __( 'Primary Menu', 'smarttoolz' ), 'footer' => __( 'Footer Menu', 'smarttoolz' ) ) );
}
add_action( 'after_setup_theme', 'smarttoolz_setup' );

function smarttoolz_widgets() {
    register_sidebar( array( 'name' => __( 'Main Sidebar', 'smarttoolz' ), 'id' => 'sidebar-1', 'description' => __( 'Optional widgets for content templates.', 'smarttoolz' ), 'before_widget' => '<section id="%1$s" class="widget %2$s st-card">', 'after_widget' => '</section>', 'before_title' => '<h2 class="widget-title">', 'after_title' => '</h2>' ) );
    register_sidebar( array( 'name' => __( 'Footer Area', 'smarttoolz' ), 'id' => 'footer-1', 'description' => __( 'Optional footer widgets.', 'smarttoolz' ), 'before_widget' => '<section id="%1$s" class="widget %2$s">', 'after_widget' => '</section>', 'before_title' => '<h2 class="widget-title">', 'after_title' => '</h2>' ) );
}
add_action( 'widgets_init', 'smarttoolz_widgets' );

function smarttoolz_is_video_context() {
    return function_exists( 'smarttoolz_video_register_post_type' ) && ( is_singular( 'st_video' ) || is_post_type_archive( 'st_video' ) || is_tax( 'st_video_category' ) || is_page_template( 'template-video-platform.php' ) || ( is_page() && ( has_shortcode( get_post_field( 'post_content', get_queried_object_id() ), 'smarttoolz_video_platform' ) || has_shortcode( get_post_field( 'post_content', get_queried_object_id() ), 'smarttoolz_video_feed' ) || has_shortcode( get_post_field( 'post_content', get_queried_object_id() ), 'smarttoolz_channel' ) || has_shortcode( get_post_field( 'post_content', get_queried_object_id() ), 'smarttoolz_creator_dashboard' ) || has_shortcode( get_post_field( 'post_content', get_queried_object_id() ), 'smarttoolz_video_upload' ) ) ) );
}

function smarttoolz_assets() {
    $theme_uri = get_template_directory_uri();
    wp_enqueue_style( 'smarttoolz-style', get_stylesheet_uri(), array(), SMARTTOOLZ_VERSION );
    wp_enqueue_style( 'smarttoolz-enhancements', $theme_uri . '/assets/css/enhancements.css', array( 'smarttoolz-style' ), SMARTTOOLZ_VERSION );
    wp_enqueue_style( 'smarttoolz-final', $theme_uri . '/assets/css/final.css', array( 'smarttoolz-enhancements' ), SMARTTOOLZ_VERSION );
    wp_enqueue_script( 'smarttoolz-theme', $theme_uri . '/assets/js/theme.js', array(), SMARTTOOLZ_VERSION, true );
    if ( smarttoolz_is_video_context() ) {
        wp_enqueue_style( 'smarttoolz-video-platform', $theme_uri . '/assets/css/video-platform.css', array( 'smarttoolz-final' ), SMARTTOOLZ_VERSION );
    }
}
add_action( 'wp_enqueue_scripts', 'smarttoolz_assets' );

function smarttoolz_body_class( $classes ) {
    $classes[] = 'smarttoolz-theme';
    if ( is_singular( 'post' ) ) { $classes[] = 'smarttoolz-single-post'; }
    if ( smarttoolz_is_video_context() ) { $classes[] = 'smarttoolz-video-platform'; }
    return $classes;
}
add_filter( 'body_class', 'smarttoolz_body_class' );

function smarttoolz_excerpt_length() { return 28; }
add_filter( 'excerpt_length', 'smarttoolz_excerpt_length' );
function smarttoolz_excerpt_more() { return '…'; }
add_filter( 'excerpt_more', 'smarttoolz_excerpt_more' );
function smarttoolz_fallback_menu() {
    $items = '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'smarttoolz' ) . '</a></li>';
    if ( function_exists( 'smarttoolz_video_register_post_type' ) ) {
        $items .= '<li><a href="' . esc_url( get_post_type_archive_link( 'st_video' ) ) . '">' . esc_html__( 'Videos', 'smarttoolz' ) . '</a></li>';
        if ( is_user_logged_in() ) { $items .= '<li><a href="' . esc_url( home_url( '/video-upload/' ) ) . '">' . esc_html__( 'Upload', 'smarttoolz' ) . '</a></li>'; }
    }
    echo '<ul class="st-nav-list">' . $items . '</ul>';
}

function smarttoolz_customize_register( $wp_customize ) {
    $wp_customize->add_section( 'smarttoolz_theme_options', array( 'title' => __( 'SmartToolz Theme', 'smarttoolz' ), 'priority' => 30 ) );
    $settings = array( 'smarttoolz_accent' => array( '#4f46e5', 'sanitize_hex_color' ), 'smarttoolz_hero_title' => array( 'Useful ideas, guides and technology worth knowing.', 'sanitize_text_field' ), 'smarttoolz_hero_subtitle' => array( 'Practical tools, useful content and a clean experience built for everyday use.', 'sanitize_textarea_field' ) );
    foreach ( $settings as $id => $setting ) { $wp_customize->add_setting( $id, array( 'default' => $setting[0], 'sanitize_callback' => $setting[1], 'transport' => 'refresh' ) ); }
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'smarttoolz_accent', array( 'label' => __( 'Accent Color', 'smarttoolz' ), 'section' => 'smarttoolz_theme_options' ) ) );
    $wp_customize->add_control( 'smarttoolz_hero_title', array( 'label' => __( 'Homepage Hero Title', 'smarttoolz' ), 'section' => 'smarttoolz_theme_options', 'type' => 'text' ) );
    $wp_customize->add_control( 'smarttoolz_hero_subtitle', array( 'label' => __( 'Homepage Hero Subtitle', 'smarttoolz' ), 'section' => 'smarttoolz_theme_options', 'type' => 'textarea' ) );
    $wp_customize->add_setting( 'smarttoolz_dark_mode', array( 'default' => 0, 'sanitize_callback' => 'absint' ) );
    $wp_customize->add_control( 'smarttoolz_dark_mode', array( 'label' => __( 'Show Dark Mode Button', 'smarttoolz' ), 'section' => 'smarttoolz_theme_options', 'type' => 'checkbox' ) );
    $wp_customize->add_setting( 'smarttoolz_show_reading_progress', array( 'default' => 1, 'sanitize_callback' => 'absint' ) );
    $wp_customize->add_control( 'smarttoolz_show_reading_progress', array( 'label' => __( 'Show Reading Progress Bar', 'smarttoolz' ), 'section' => 'smarttoolz_theme_options', 'type' => 'checkbox' ) );
}
add_action( 'customize_register', 'smarttoolz_customize_register' );

function smarttoolz_customizer_css() {
    $accent = get_theme_mod( 'smarttoolz_accent', '#4f46e5' );
    if ( $accent ) { echo '<style id="smarttoolz-customizer-css">:root{--st-primary:' . esc_attr( $accent ) . ';}</style>'; }
}
add_action( 'wp_head', 'smarttoolz_customizer_css' );

function smarttoolz_reading_time() { $words = str_word_count( wp_strip_all_tags( get_the_content() ) ); $minutes = max( 1, (int) ceil( $words / 200 ) ); return sprintf( _n( '%d min read', '%d min read', $minutes, 'smarttoolz' ), $minutes ); }
function smarttoolz_breadcrumbs() {
    if ( is_front_page() ) { return; }
    echo '<nav class="st-breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumbs', 'smarttoolz' ) . '"><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'smarttoolz' ) . '</a><span aria-hidden="true">/</span>';
    if ( is_category() ) { echo '<span>' . esc_html( single_cat_title( '', false ) ) . '</span>'; }
    elseif ( is_tag() ) { echo '<span>' . esc_html( single_tag_title( '', false ) ) . '</span>'; }
    elseif ( is_author() ) { echo '<span>' . esc_html( get_the_author_meta( 'display_name' ) ) . '</span>'; }
    elseif ( is_tax( 'st_video_category' ) ) { echo '<span>' . esc_html( single_term_title( '', false ) ) . '</span>'; }
    elseif ( is_singular( 'st_video' ) ) { echo '<a href="' . esc_url( get_post_type_archive_link( 'st_video' ) ) . '">' . esc_html__( 'Videos', 'smarttoolz' ) . '</a><span aria-hidden="true">/</span><span>' . esc_html( get_the_title() ) . '</span>'; }
    elseif ( is_single() ) { $cats = get_the_category(); if ( ! empty( $cats ) ) { echo '<a href="' . esc_url( get_category_link( $cats[0]->term_id ) ) . '">' . esc_html( $cats[0]->name ) . '</a><span aria-hidden="true">/</span>'; } echo '<span>' . esc_html( get_the_title() ) . '</span>'; }
    else { echo '<span>' . esc_html( wp_get_document_title() ) . '</span>'; }
    echo '</nav>';
}
function smarttoolz_back_to_top() { echo '<button type="button" class="st-back-top" aria-label="' . esc_attr__( 'Back to top', 'smarttoolz' ) . '">↑</button>'; }
add_action( 'wp_footer', 'smarttoolz_back_to_top', 20 );
function smarttoolz_reading_progress() { if ( is_singular( 'post' ) && get_theme_mod( 'smarttoolz_show_reading_progress', 1 ) ) { echo '<div class="st-reading-progress" aria-hidden="true"><span></span></div>'; } }
add_action( 'wp_body_open', 'smarttoolz_reading_progress', 5 );
function smarttoolz_schema_meta() {
    if ( ! is_singular() ) { return; }
    $type = is_singular( 'st_video' ) ? 'VideoObject' : ( is_single() ? 'Article' : 'WebPage' );
    $schema = array( '@context'=>'https://schema.org', '@type'=>$type, 'headline'=>wp_strip_all_tags(get_the_title()), 'url'=>get_permalink(), 'datePublished'=>get_the_date('c'), 'dateModified'=>get_the_modified_date('c') );
    if ( is_singular( 'st_video' ) ) { $schema['description'] = wp_strip_all_tags( get_the_excerpt() ); $schema['uploadDate'] = get_the_date('c'); }
    if ( is_single() ) { $schema['author'] = array('@type'=>'Person','name'=>get_the_author()); }
    if ( has_post_thumbnail() ) { $image=wp_get_attachment_image_src(get_post_thumbnail_id(),'full'); if($image)$schema['image']=esc_url_raw($image[0]); }
    echo '<script type="application/ld+json">' . wp_json_encode($schema,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) . '</script>';
}
add_action( 'wp_head', 'smarttoolz_schema_meta', 20 );
function smarttoolz_related_posts( $post_id = 0 ) { $post_id=$post_id?absint($post_id):get_the_ID(); $cats=wp_get_post_categories($post_id); if(empty($cats))return array(); $query=new WP_Query(array('post_type'=>'post','post_status'=>'publish','posts_per_page'=>3,'post__not_in'=>array($post_id),'category__in'=>$cats,'ignore_sticky_posts'=>true,'no_found_rows'=>true)); return $query->posts; }
