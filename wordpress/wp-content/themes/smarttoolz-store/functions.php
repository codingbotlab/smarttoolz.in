<?php
if (!defined('ABSPATH')) exit;

function smarttoolz_store_assets() {
    wp_enqueue_style(
        'smarttoolz-store-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get('Version')
    );
}
add_action('wp_enqueue_scripts', 'smarttoolz_store_assets');
