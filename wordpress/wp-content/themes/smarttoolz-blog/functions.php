<?php
if (!defined('ABSPATH')) exit;

function smarttoolz_blog_assets() {
    wp_enqueue_style('smarttoolz-blog-style', get_stylesheet_uri(), array(), wp_get_theme()->get('Version'));
}
add_action('wp_enqueue_scripts', 'smarttoolz_blog_assets');

function smarttoolz_blog_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
}
add_action('after_setup_theme', 'smarttoolz_blog_setup');

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
        $id = wp_insert_post(array(
            'post_title' => $post[0],
            'post_content' => '<p>' . esc_html($post[2]) . '</p><p>This demo article is editable from WordPress Admin. The theme renders posts, categories and tags dynamically.</p>',
            'post_status' => 'publish',
            'post_type' => 'post',
            'post_author' => get_current_user_id() ?: 1,
            'post_category' => isset($category_ids[$post[1]]) ? array($category_ids[$post[1]]) : array(),
        ));
        if ($id && !is_wp_error($id)) wp_set_post_tags($id, $post[3]);
    }

    $pages = array(
        'About' => '<h2>About SmartToolz Journal</h2><p>A clean editorial space for useful ideas, stories and practical knowledge.</p>',
        'Contact' => '<h2>Contact</h2><p>Add your email, social links or contact form here from WordPress.</p>',
        'Privacy Policy' => '<h2>Privacy Policy</h2><p>Replace this demo copy with your site privacy policy.</p>',
    );
    foreach ($pages as $title => $content) {
        if (!get_page_by_title($title, OBJECT, 'page')) {
            wp_insert_post(array('post_title'=>$title, 'post_content'=>$content, 'post_status'=>'publish', 'post_type'=>'page', 'post_author'=>get_current_user_id() ?: 1));
        }
    }

    update_option('smarttoolz_blog_demo_seeded', 1, false);
}
add_action('init', 'smarttoolz_blog_seed_demo_content', 20);
