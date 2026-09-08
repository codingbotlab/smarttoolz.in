<?php
if (!defined('ABSPATH')) exit;
function smarttoolz_setup(){
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  register_nav_menus(['primary'=>'Primary Menu']);
}
add_action('after_setup_theme','smarttoolz_setup');
function smarttoolz_assets(){wp_enqueue_style('smarttoolz-style',get_stylesheet_uri(),[], '0.1.0');}
add_action('wp_enqueue_scripts','smarttoolz_assets');
function smarttoolz_excerpt($length=18){return wp_trim_words(get_the_excerpt(),$length);}
