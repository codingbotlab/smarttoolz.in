<?php
if (!defined('ABSPATH')) exit;

function smarttoolz_blog_assets() {
    wp_enqueue_style(
        'smarttoolz-blog-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'smarttoolz_blog_assets');
