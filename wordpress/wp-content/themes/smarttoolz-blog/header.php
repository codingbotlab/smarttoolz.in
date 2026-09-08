<?php if (!defined('ABSPATH')) exit; ?><!doctype html>
<html <?php language_attributes(); ?>><head><meta charset="<?php bloginfo('charset'); ?>"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head(); ?></head>
<body <?php body_class(); ?>><?php wp_body_open(); ?>
<header class="header">
  <div class="container">
    <a class="brand" href="<?php echo esc_url(home_url('/')); ?>">
      <?php if (has_custom_logo()) { the_custom_logo(); } else { bloginfo('name'); } ?>
    </a>
    <nav class="nav" aria-label="Primary Navigation">
      <?php wp_nav_menu(array('theme_location'=>'primary','container'=>false,'fallback_cb'=>function(){ echo '<ul>'; echo '<li><a href="'.esc_url(home_url('/')).'">'.esc_html__('Home','smarttoolz-blog').'</a></li>'; wp_list_pages(array('title_li'=>'','depth'=>1)); echo '</ul>'; })); ?>
    </nav>
    <div class="header-search"><?php get_search_form(); ?></div>
  </div>
</header>
