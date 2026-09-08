<?php
/**
 * Plugin Name: SmartToolz Tools
 * Description: Core tool registry and rendering layer for the SmartToolz WordPress lab.
 * Version: 0.1.0
 * Author: SmartToolz
 */
if (!defined('ABSPATH')) exit;
function smarttoolz_register_tools(){
  register_post_type('smarttoolz_tool',[
    'labels'=>['name'=>'Tools','singular_name'=>'Tool','add_new_item'=>'Add New Tool'],
    'public'=>true,'show_in_rest'=>true,'menu_icon'=>'dashicons-admin-tools',
    'supports'=>['title','editor','excerpt','thumbnail'],
    'rewrite'=>['slug'=>'tools'],'has_archive'=>true,
  ]);
}
add_action('init','smarttoolz_register_tools');
function smarttoolz_tools_shortcode($atts=[]){
  $q=new WP_Query(['post_type'=>'smarttoolz_tool','post_status'=>'publish','posts_per_page'=>-1,'orderby'=>'menu_order title','order'=>'ASC']);
  ob_start(); echo '<div class="tools-grid">';
  if($q->have_posts()) while($q->have_posts()){ $q->the_post();
    echo '<article class="tool-card"><h2>'.esc_html(get_the_title()).'</h2>';
    $excerpt=get_the_excerpt(); if($excerpt) echo '<p>'.esc_html($excerpt).'</p>';
    echo '<a class="button" href="'.esc_url(get_permalink()).'">Open Tool</a></article>';
  } else echo '<div class="tool-card"><h2>No tools yet</h2><p>Create your first SmartToolz tool from WordPress Admin → Tools.</p></div>';
  echo '</div>'; wp_reset_postdata(); return ob_get_clean();
}
add_shortcode('smarttoolz_tools','smarttoolz_tools_shortcode');
