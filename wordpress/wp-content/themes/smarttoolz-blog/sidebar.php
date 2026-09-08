<?php if (!defined('ABSPATH')) exit; ?>
<aside id="secondary" class="sidebar" aria-label="Sidebar">
<?php if (is_active_sidebar('sidebar-1')) : ob_start(); dynamic_sidebar('sidebar-1'); $sidebar_output=ob_get_clean(); if (trim($sidebar_output)!=='') echo $sidebar_output; else : ?>
  <section class="widget widget_search"><h3 class="widget-title"><?php esc_html_e('Search the journal', 'smarttoolz-blog'); ?></h3><?php get_search_form(); ?></section>
  <section class="widget"><h3 class="widget-title"><?php esc_html_e('Explore Topics', 'smarttoolz-blog'); ?></h3><ul><?php wp_list_categories(array('title_li'=>'','show_count'=>true,'orderby'=>'count','order'=>'DESC','number'=>8)); ?></ul></section>
  <section class="widget"><h3 class="widget-title"><?php esc_html_e('Fresh Stories', 'smarttoolz-blog'); ?></h3><ul><?php wp_get_archives(array('type'=>'postbypost','limit'=>6)); ?></ul></section>
  <section class="widget"><h3 class="widget-title"><?php esc_html_e('Popular Tags', 'smarttoolz-blog'); ?></h3><div class="tagcloud"><?php wp_tag_cloud(array('smallest'=>11,'largest'=>18,'unit'=>'px','number'=>20,'orderby'=>'count','order'=>'DESC')); ?></div></section>
<?php endif; ?>
</aside>
