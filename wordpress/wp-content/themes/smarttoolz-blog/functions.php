<?php
if (!defined('ABSPATH')) exit;

function smarttoolz_blog_assets() {
    wp_enqueue_style('smarttoolz-blog-style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version'));
}
add_action('wp_enqueue_scripts', 'smarttoolz_blog_assets');

function smarttoolz_blog_setup() {
    load_theme_textdomain('smarttoolz-blog', get_template_directory() . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array('height' => 80, 'width' => 280, 'flex-height' => true, 'flex-width' => true));
    add_theme_support('custom-background', array('default-color' => 'fbfaf6'));
    add_theme_support('automatic-feed-links');
    add_theme_support('responsive-embeds');
    add_theme_support('align-wide');
    add_theme_support('editor-styles');
    add_editor_style('style.css');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
    add_theme_support('post-formats', array('aside', 'image', 'quote', 'video', 'audio', 'link'));
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'smarttoolz-blog'),
        'footer' => __('Footer Menu', 'smarttoolz-blog'),
    ));
}
add_action('after_setup_theme', 'smarttoolz_blog_setup');

function smarttoolz_blog_widgets() {
    $common = array(
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    );
    register_sidebar(array_merge($common, array('name' => __('Blog Sidebar', 'smarttoolz-blog'), 'id' => 'sidebar-1', 'description' => __('Main sidebar for posts, pages and archives.', 'smarttoolz-blog'))));
    register_sidebar(array_merge($common, array('name' => __('Footer One', 'smarttoolz-blog'), 'id' => 'footer-1')));
    register_sidebar(array_merge($common, array('name' => __('Footer Two', 'smarttoolz-blog'), 'id' => 'footer-2')));
    register_sidebar(array_merge($common, array('name' => __('Footer Three', 'smarttoolz-blog'), 'id' => 'footer-3')));
}
add_action('widgets_init', 'smarttoolz_blog_widgets');

function smarttoolz_blog_excerpt_length($length) { return 26; }
add_filter('excerpt_length', 'smarttoolz_blog_excerpt_length', 999);
function smarttoolz_blog_excerpt_more($more) { return '…'; }
add_filter('excerpt_more', 'smarttoolz_blog_excerpt_more');

function smarttoolz_blog_seed_demo_content() {
    if (get_option('smarttoolz_blog_demo_seeded')) return;
    $categories = array('Technology', 'Design', 'Culture', 'Work');
    $category_ids = array();
    foreach ($categories as $name) {
        $term = term_exists($name, 'category');
        if (!$term) $term = wp_insert_term($name, 'category');
        if (!is_wp_error($term)) $category_ids[$name] = (int) (is_array($term) ? $term['term_id'] : $term);
    }
    foreach (array('WordPress', 'Productivity', 'Creativity', 'Business', 'Web Design', 'Habits') as $name) {
        if (!term_exists($name, 'post_tag')) wp_insert_term($name, 'post_tag');
    }
    $posts = array(
        array('Small tools, big impact', 'Technology', 'Practical workflows that help people work smarter without adding complexity.', array('WordPress','Productivity')),
        array('Designing for clarity', 'Design', 'Good editorial design gets out of the way and lets the story do the talking.', array('Web Design','Creativity')),
        array('Why simple ideas travel further', 'Culture', 'A thoughtful look at making complex subjects approachable and memorable.', array('Creativity','Business')),
        array('A better way to build a habit', 'Work', 'Small systems, consistent practice and a little room for imperfection.', array('Productivity','Habits')),
    );
    foreach ($posts as $post) {
        if (get_page_by_title($post[0], OBJECT, 'post')) continue;
        $id = wp_insert_post(array('post_title'=>$post[0], 'post_content'=>'<p>'.esc_html($post[2]).'</p><p>This demo article is editable from WordPress Admin. The theme renders posts, categories and tags dynamically.</p>', 'post_status'=>'publish', 'post_type'=>'post', 'post_author'=>get_current_user_id() ?: 1, 'post_category'=>isset($category_ids[$post[1]]) ? array($category_ids[$post[1]]) : array()));
        if ($id && !is_wp_error($id)) wp_set_post_tags($id, $post[3]);
    }
    $pages = array(
        'About' => '<h2>About SmartToolz Journal</h2><p>A clean editorial space for useful ideas, stories and practical knowledge.</p>',
        'Contact' => '<h2>Contact</h2><p>Add your email, social links or contact form here from WordPress.</p>',
        'Privacy Policy' => '<h2>Privacy Policy</h2><p>Replace this demo copy with your site privacy policy.</p>',
    );
    foreach ($pages as $title => $content) {
        if (!get_page_by_title($title, OBJECT, 'page')) wp_insert_post(array('post_title'=>$title, 'post_content'=>$content, 'post_status'=>'publish', 'post_type'=>'page', 'post_author'=>get_current_user_id() ?: 1));
    }
    update_option('smarttoolz_blog_demo_seeded', 1, false);
}
add_action('init', 'smarttoolz_blog_seed_demo_content', 20);

function smarttoolz_blog_seed_menu() {
    if (has_nav_menu('primary') || get_option('smarttoolz_blog_menu_seeded')) return;
    $menu = wp_get_nav_menu_object('SmartToolz Blog Menu');
    $menu_id = $menu ? $menu->term_id : wp_create_nav_menu('SmartToolz Blog Menu');
    if (is_wp_error($menu_id)) return;
    $items = array(home_url('/'));
    foreach (get_categories(array('hide_empty'=>true,'number'=>6)) as $cat) $items[] = get_category_link($cat->term_id);
    foreach (array('About','Contact') as $title) { $page = get_page_by_title($title, OBJECT, 'page'); if ($page) $items[] = get_permalink($page->ID); }
    foreach ($items as $url) {
        $exists = false;
        foreach (wp_get_nav_menu_items($menu_id) ?: array() as $item) if ($item->url === $url) $exists = true;
        if (!$exists) wp_update_nav_menu_item($menu_id, 0, array('menu-item-title'=>wp_strip_all_tags(urldecode(basename(trim($url,'/')) ?: 'Home')), 'menu-item-url'=>$url, 'menu-item-status'=>'publish'));
    }
    $locations = get_theme_mod('nav_menu_locations', array());
    $locations['primary'] = (int)$menu_id;
    set_theme_mod('nav_menu_locations', $locations);
    update_option('smarttoolz_blog_menu_seeded', 1, false);
}
add_action('init', 'smarttoolz_blog_seed_menu', 30);
