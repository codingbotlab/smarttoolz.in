<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/* -------------------------------------------------------------------------
 * SmartToolz Blog theme setup
 * ---------------------------------------------------------------------- */
function smarttoolz_blog_setup() {
    load_theme_textdomain( 'smarttoolz-blog', get_template_directory() . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', array(
        'height'      => 80,
        'width'       => 280,
        'flex-height' => true,
        'flex-width'  => true,
    ) );
    add_theme_support( 'custom-background', array( 'default-color' => 'f6f7ff' ) );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'responsive-embeds' );
    add_theme_support( 'align-wide' );
    add_theme_support( 'editor-styles' );
    add_editor_style( 'style.css' );
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
    add_theme_support( 'post-formats', array( 'aside', 'image', 'quote', 'video', 'audio', 'link' ) );

    register_nav_menus( array(
        'primary' => __( 'Primary Menu', 'smarttoolz-blog' ),
        'footer'  => __( 'Footer Menu', 'smarttoolz-blog' ),
    ) );
}
add_action( 'after_setup_theme', 'smarttoolz_blog_setup' );

function smarttoolz_blog_widgets() {
    $common = array(
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    );

    register_sidebar( array_merge( $common, array(
        'name'        => __( 'Blog Sidebar', 'smarttoolz-blog' ),
        'id'          => 'sidebar-1',
        'description' => __( 'Main sidebar for posts, pages and archives.', 'smarttoolz-blog' ),
    ) ) );

    foreach ( array( '1' => 'Footer One', '2' => 'Footer Two', '3' => 'Footer Three' ) as $id => $name ) {
        register_sidebar( array_merge( $common, array(
            'name' => __( $name, 'smarttoolz-blog' ),
            'id'   => 'footer-' . $id,
        ) ) );
    }
}
add_action( 'widgets_init', 'smarttoolz_blog_widgets' );

function smarttoolz_blog_assets() {
    $version = wp_get_theme()->get( 'Version' );
    wp_enqueue_style( 'smarttoolz-blog-style', get_stylesheet_uri(), array(), $version );
    wp_enqueue_style( 'smarttoolz-blog-brand', get_template_directory_uri() . '/brand.css', array( 'smarttoolz-blog-style' ), $version . '.2' );
}
add_action( 'wp_enqueue_scripts', 'smarttoolz_blog_assets' );

function smarttoolz_blog_excerpt_length( $length ) {
    return 26;
}
add_filter( 'excerpt_length', 'smarttoolz_blog_excerpt_length', 999 );

function smarttoolz_blog_excerpt_more() {
    return '…';
}
add_filter( 'excerpt_more', 'smarttoolz_blog_excerpt_more' );

/* -------------------------------------------------------------------------
 * Safe demo content helpers
 * ---------------------------------------------------------------------- */
function smarttoolz_blog_demo_image( $slug ) {
    $images = array(
        'technology' => 'https://picsum.photos/seed/smarttoolz-technology/1200/800',
        'design'     => 'https://picsum.photos/seed/smarttoolz-design/1200/800',
        'culture'    => 'https://picsum.photos/seed/smarttoolz-culture/1200/800',
        'work'       => 'https://picsum.photos/seed/smarttoolz-work/1200/800',
    );

    return isset( $images[ $slug ] ) ? $images[ $slug ] : $images['technology'];
}

function smarttoolz_blog_set_featured_image( $post_id, $url, $title ) {
    if ( ! $post_id || has_post_thumbnail( $post_id ) || empty( $url ) ) {
        return false;
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $tmp = download_url( $url, 15 );
    if ( is_wp_error( $tmp ) ) {
        return false;
    }

    $file = array(
        'name'     => sanitize_title( $title ) . '.jpg',
        'tmp_name' => $tmp,
    );

    $attachment = media_handle_sideload( $file, $post_id, $title );
    if ( is_wp_error( $attachment ) ) {
        @unlink( $tmp );
        return false;
    }

    set_post_thumbnail( $post_id, $attachment );
    return true;
}

/* Run once, and only from an admin request, so a public page can never be
 * taken down by a slow/failed remote demo-image download. */
function smarttoolz_blog_repair_featured_images() {
    if ( get_option( 'smarttoolz_blog_featured_images_repaired' ) || ! is_admin() || ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $posts = get_posts( array(
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
    ) );

    foreach ( $posts as $post ) {
        if ( has_post_thumbnail( $post->ID ) ) {
            continue;
        }

        $categories = get_the_category( $post->ID );
        $slug = ( ! empty( $categories ) && ! is_wp_error( $categories ) ) ? $categories[0]->slug : 'technology';
        smarttoolz_blog_set_featured_image( $post->ID, smarttoolz_blog_demo_image( $slug ), $post->post_title );
    }

    update_option( 'smarttoolz_blog_featured_images_repaired', 1, false );
}
add_action( 'admin_init', 'smarttoolz_blog_repair_featured_images', 25 );

function smarttoolz_blog_find_page( $title ) {
    $query = new WP_Query( array(
        'post_type'      => 'page',
        'post_status'    => array( 'publish', 'draft' ),
        'title'          => $title,
        'posts_per_page' => 1,
        'no_found_rows'  => true,
    ) );

    return $query->have_posts() ? $query->posts[0] : null;
}

function smarttoolz_blog_seed_demo_content() {
    if ( get_option( 'smarttoolz_blog_demo_seeded' ) ) {
        return;
    }

    $categories = array( 'Technology', 'Design', 'Culture', 'Work' );
    $category_ids = array();

    foreach ( $categories as $name ) {
        $term = term_exists( $name, 'category' );
        if ( ! $term ) {
            $term = wp_insert_term( $name, 'category' );
        }
        if ( ! is_wp_error( $term ) ) {
            $category_ids[ $name ] = (int) ( is_array( $term ) ? $term['term_id'] : $term );
        }
    }

    foreach ( array( 'WordPress', 'Productivity', 'Creativity', 'Business', 'Web Design', 'Habits' ) as $tag ) {
        if ( ! term_exists( $tag, 'post_tag' ) ) {
            wp_insert_term( $tag, 'post_tag' );
        }
    }

    $posts = array(
        array( 'Small tools, big impact', 'Technology', 'Practical workflows that help people work smarter without adding complexity.', array( 'WordPress', 'Productivity' ), 'technology' ),
        array( 'Designing for clarity', 'Design', 'Good editorial design gets out of the way and lets the story do the talking.', array( 'Web Design', 'Creativity' ), 'design' ),
        array( 'Why simple ideas travel further', 'Culture', 'A thoughtful look at making complex subjects approachable and memorable.', array( 'Creativity', 'Business' ), 'culture' ),
        array( 'A better way to build a habit', 'Work', 'Small systems, consistent practice and a little room for imperfection.', array( 'Productivity', 'Habits' ), 'work' ),
    );

    foreach ( $posts as $post_data ) {
        $title = $post_data[0];
        $existing = smarttoolz_blog_find_post( $title );

        if ( $existing ) {
            $post_id = $existing->ID;
        } else {
            $content = '<p>' . esc_html( $post_data[2] ) . '</p><p>This demo article is editable from WordPress Admin. The theme renders posts, categories, tags, images and widgets dynamically.</p>';
            $post_id = wp_insert_post( array(
                'post_title'    => $title,
                'post_content'  => $content,
                'post_status'   => 'publish',
                'post_type'     => 'post',
                'post_author'   => get_current_user_id() ? get_current_user_id() : 1,
                'post_category' => isset( $category_ids[ $post_data[1] ] ) ? array( $category_ids[ $post_data[1] ] ) : array(),
            ), true );
        }

        if ( $post_id && ! is_wp_error( $post_id ) ) {
            wp_set_post_tags( $post_id, $post_data[3] );
            if ( ! has_post_thumbnail( $post_id ) ) {
                smarttoolz_blog_set_featured_image( $post_id, smarttoolz_blog_demo_image( $post_data[4] ), $title );
            }
        }
    }

    $pages = array(
        'About'         => '<h2>About SmartToolz Journal</h2><p>A clean editorial space for useful ideas, stories and practical knowledge.</p>',
        'Contact'       => '<h2>Contact</h2><p>Add your email, social links or contact form here from WordPress.</p>',
        'Privacy Policy' => '<h2>Privacy Policy</h2><p>Replace this demo copy with your site privacy policy.</p>',
    );

    foreach ( $pages as $title => $content ) {
        if ( ! smarttoolz_blog_find_page( $title ) ) {
            wp_insert_post( array(
                'post_title'   => $title,
                'post_content' => $content,
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_author'  => get_current_user_id() ? get_current_user_id() : 1,
            ) );
        }
    }

    update_option( 'smarttoolz_blog_demo_seeded', 1, false );
}

function smarttoolz_blog_find_post( $title ) {
    $query = new WP_Query( array(
        'post_type'      => 'post',
        'post_status'    => array( 'publish', 'draft' ),
        'title'          => $title,
        'posts_per_page' => 1,
        'no_found_rows'  => true,
    ) );
    return $query->have_posts() ? $query->posts[0] : null;
}

/* Do not seed public requests if the site is already initialized. */
function smarttoolz_blog_maybe_seed_demo_content() {
    if ( ! get_option( 'smarttoolz_blog_demo_seeded' ) && is_admin() && current_user_can( 'manage_options' ) ) {
        smarttoolz_blog_seed_demo_content();
    }
}
add_action( 'admin_init', 'smarttoolz_blog_maybe_seed_demo_content', 20 );

/* -------------------------------------------------------------------------
 * Customizer
 * ---------------------------------------------------------------------- */
function smarttoolz_blog_customize_register( $wp_customize ) {
    $wp_customize->add_panel( 'smarttoolz_blog_design', array(
        'title'       => __( 'SmartToolz Blog Design', 'smarttoolz-blog' ),
        'priority'    => 30,
        'description' => __( 'Control the visual design and reader experience without editing code.', 'smarttoolz-blog' ),
    ) );

    $wp_customize->add_section( 'smarttoolz_blog_colors', array(
        'title' => __( 'Colors', 'smarttoolz-blog' ),
        'panel' => 'smarttoolz_blog_design',
    ) );

    $colors = array(
        'accent'      => array( 'Accent Color', 'e64b2e' ),
        'accent_soft' => array( 'Accent Soft', 'ffe9e2' ),
        'ink'         => array( 'Text / Ink', '111318' ),
        'paper'       => array( 'Page Background', 'f7f6f2' ),
        'surface'     => array( 'Card Background', 'ffffff' ),
        'dark'        => array( 'Dark Sections', '17191f' ),
        'line'        => array( 'Borders', 'deded8' ),
    );

    foreach ( $colors as $id => $data ) {
        $wp_customize->add_setting( 'smarttoolz_' . $id, array(
            'default'           => $data[1],
            'sanitize_callback' => 'sanitize_hex_color',
        ) );
        $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'smarttoolz_' . $id, array(
            'label'   => __( $data[0], 'smarttoolz-blog' ),
            'section' => 'smarttoolz_blog_colors',
        ) ) );
    }

    $wp_customize->add_section( 'smarttoolz_blog_layout', array(
        'title' => __( 'Layout & Header', 'smarttoolz-blog' ),
        'panel' => 'smarttoolz_blog_design',
    ) );

    smarttoolz_blog_checkbox( $wp_customize, 'header_sticky', 'Sticky Header', true, 'smarttoolz_blog_layout' );
    smarttoolz_blog_checkbox( $wp_customize, 'show_search', 'Show Header Search', true, 'smarttoolz_blog_layout' );
    smarttoolz_blog_range( $wp_customize, 'sidebar_width', 'Sidebar Width (px)', 300, 220, 420, 10, 'smarttoolz_blog_layout' );
    smarttoolz_blog_range( $wp_customize, 'content_gap', 'Content / Sidebar Gap (px)', 70, 20, 120, 5, 'smarttoolz_blog_layout' );

    $wp_customize->add_section( 'smarttoolz_blog_hero', array(
        'title' => __( 'Hero Section', 'smarttoolz-blog' ),
        'panel' => 'smarttoolz_blog_design',
    ) );

    smarttoolz_blog_text( $wp_customize, 'hero_label', 'Hero Label', 'SmartToolz Journal', 'smarttoolz_blog_hero' );
    smarttoolz_blog_textarea( $wp_customize, 'hero_title', 'Hero Title', 'Ideas for a smarter digital life.', 'smarttoolz_blog_hero' );
    smarttoolz_blog_textarea( $wp_customize, 'hero_text', 'Hero Description', 'A modern editorial theme for essays, guides, product stories and ideas — powered entirely by WordPress.', 'smarttoolz_blog_hero' );
    smarttoolz_blog_range( $wp_customize, 'hero_posts', 'Hero Featured Posts', 3, 1, 6, 1, 'smarttoolz_blog_hero' );

    $wp_customize->add_section( 'smarttoolz_blog_cards', array(
        'title' => __( 'Cards & Typography', 'smarttoolz-blog' ),
        'panel' => 'smarttoolz_blog_design',
    ) );
    smarttoolz_blog_range( $wp_customize, 'card_radius', 'Card Radius (px)', 18, 0, 40, 1, 'smarttoolz_blog_cards' );
    smarttoolz_blog_range( $wp_customize, 'story_image_height', 'Story Image Height (px)', 156, 100, 300, 5, 'smarttoolz_blog_cards' );
    smarttoolz_blog_text( $wp_customize, 'body_font', 'Body Font Stack', 'Inter,ui-sans-serif,system-ui,sans-serif', 'smarttoolz_blog_cards' );

    $wp_customize->add_section( 'smarttoolz_blog_footer', array(
        'title' => __( 'Footer & SmartToolz Branding', 'smarttoolz-blog' ),
        'panel' => 'smarttoolz_blog_design',
    ) );
    smarttoolz_blog_text( $wp_customize, 'footer_brand', 'Branding Text', 'SmartToolz', 'smarttoolz_blog_footer' );
    smarttoolz_blog_textarea( $wp_customize, 'footer_text', 'Footer Description', 'SmartToolz — useful tools, ideas and digital products.', 'smarttoolz_blog_footer' );
    smarttoolz_blog_textarea( $wp_customize, 'footer_copyright', 'Copyright Text', '© %year% SmartToolz. All rights reserved.', 'smarttoolz_blog_footer' );

    $wp_customize->add_section( 'smarttoolz_blog_social', array(
        'title' => __( 'Social Links', 'smarttoolz-blog' ),
        'panel' => 'smarttoolz_blog_design',
    ) );
    foreach ( array( 'facebook' => 'Facebook', 'instagram' => 'Instagram', 'youtube' => 'YouTube', 'twitter' => 'X / Twitter', 'linkedin' => 'LinkedIn' ) as $id => $label ) {
        $wp_customize->add_setting( 'smarttoolz_social_' . $id, array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
        $wp_customize->add_control( 'smarttoolz_social_' . $id, array(
            'label'   => $label . ' URL',
            'section' => 'smarttoolz_blog_social',
            'type'    => 'url',
        ) );
    }

    $wp_customize->add_section( 'smarttoolz_blog_engagement', array(
        'title'       => __( 'Engagement & Reader Experience', 'smarttoolz-blog' ),
        'panel'       => 'smarttoolz_blog_design',
        'description' => __( 'Reader-friendly engagement features.', 'smarttoolz-blog' ),
    ) );

    foreach ( array(
        'reading_progress' => 'Reading Progress Bar',
        'share_buttons'   => 'Social Share Buttons',
        'related_posts'   => 'Related Stories',
        'author_box'      => 'Author Box',
        'helpful_feedback'=> 'Was This Helpful?',
        'back_to_top'     => 'Back To Top Button',
        'newsletter_cta'  => 'Newsletter CTA',
        'show_breadcrumbs' => 'Breadcrumbs',
    ) as $id => $label ) {
        smarttoolz_blog_checkbox( $wp_customize, $id, $label, true, 'smarttoolz_blog_engagement' );
    }

    smarttoolz_blog_range( $wp_customize, 'related_count', 'Related Stories Count', 3, 2, 6, 1, 'smarttoolz_blog_engagement' );
    smarttoolz_blog_text( $wp_customize, 'share_label', 'Share Prompt', 'Enjoyed this story? Share it.', 'smarttoolz_blog_engagement' );

    $wp_customize->add_section( 'smarttoolz_blog_newsletter', array(
        'title' => __( 'Newsletter CTA', 'smarttoolz-blog' ),
        'panel' => 'smarttoolz_blog_design',
    ) );
    smarttoolz_blog_text( $wp_customize, 'newsletter_title', 'CTA Title', 'Get smarter stories in your inbox', 'smarttoolz_blog_newsletter' );
    smarttoolz_blog_text( $wp_customize, 'newsletter_text', 'CTA Description', 'Useful ideas, tools and fresh reads — delivered without the noise.', 'smarttoolz_blog_newsletter' );
    smarttoolz_blog_text( $wp_customize, 'newsletter_button', 'Button Text', 'Subscribe', 'smarttoolz_blog_newsletter' );
    $wp_customize->add_setting( 'smarttoolz_newsletter_url', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
    $wp_customize->add_control( 'smarttoolz_newsletter_url', array( 'label' => __( 'Subscription URL', 'smarttoolz-blog' ), 'section' => 'smarttoolz_blog_newsletter', 'type' => 'url' ) );

    $wp_customize->add_section( 'smarttoolz_blog_advanced', array(
        'title' => __( 'Advanced Experience', 'smarttoolz-blog' ),
        'panel' => 'smarttoolz_blog_design',
    ) );
    smarttoolz_blog_range( $wp_customize, 'hover_lift', 'Card Hover Lift (px)', 4, 0, 12, 1, 'smarttoolz_blog_advanced' );
    smarttoolz_blog_checkbox( $wp_customize, 'image_zoom', 'Image Hover Zoom', true, 'smarttoolz_blog_advanced' );
    smarttoolz_blog_checkbox( $wp_customize, 'show_post_meta', 'Show Post Meta', true, 'smarttoolz_blog_advanced' );
}
add_action( 'customize_register', 'smarttoolz_blog_customize_register' );

function smarttoolz_blog_checkbox( $wp_customize, $id, $label, $default, $section ) {
    $wp_customize->add_setting( 'smarttoolz_' . $id, array( 'default' => $default, 'sanitize_callback' => 'rest_sanitize_boolean' ) );
    $wp_customize->add_control( 'smarttoolz_' . $id, array( 'label' => __( $label, 'smarttoolz-blog' ), 'section' => $section, 'type' => 'checkbox' ) );
}

function smarttoolz_blog_range( $wp_customize, $id, $label, $default, $min, $max, $step, $section ) {
    $wp_customize->add_setting( 'smarttoolz_' . $id, array( 'default' => $default, 'sanitize_callback' => 'absint' ) );
    $wp_customize->add_control( 'smarttoolz_' . $id, array(
        'label'       => __( $label, 'smarttoolz-blog' ),
        'section'     => $section,
        'type'        => 'range',
        'input_attrs' => array( 'min' => $min, 'max' => $max, 'step' => $step ),
    ) );
}

function smarttoolz_blog_text( $wp_customize, $id, $label, $default, $section ) {
    $wp_customize->add_setting( 'smarttoolz_' . $id, array( 'default' => $default, 'sanitize_callback' => 'sanitize_text_field' ) );
    $wp_customize->add_control( 'smarttoolz_' . $id, array( 'label' => __( $label, 'smarttoolz-blog' ), 'section' => $section, 'type' => 'text' ) );
}

function smarttoolz_blog_textarea( $wp_customize, $id, $label, $default, $section ) {
    $wp_customize->add_setting( 'smarttoolz_' . $id, array( 'default' => $default, 'sanitize_callback' => 'sanitize_textarea_field' ) );
    $wp_customize->add_control( 'smarttoolz_' . $id, array( 'label' => __( $label, 'smarttoolz-blog' ), 'section' => $section, 'type' => 'textarea' ) );
}

/* Dynamic CSS is generated safely from Customizer values. */
function smarttoolz_blog_customizer_css() {
    $css = ':root{';
    foreach ( array( 'accent', 'accent_soft', 'ink', 'paper', 'surface', 'dark', 'line' ) as $key ) {
        $value = sanitize_hex_color( get_theme_mod( 'smarttoolz_' . $key, '' ) );
        if ( $value ) {
            $css .= '--' . str_replace( '_', '-', $key ) . ':' . $value . ';';
        }
    }
    $css .= '}';

    $sidebar = max( 220, min( 420, absint( get_theme_mod( 'smarttoolz_sidebar_width', 300 ) ) ) );
    $gap     = max( 20, min( 120, absint( get_theme_mod( 'smarttoolz_content_gap', 70 ) ) ) );
    $radius  = max( 0, min( 40, absint( get_theme_mod( 'smarttoolz_card_radius', 18 ) ) ) );
    $height  = max( 100, min( 300, absint( get_theme_mod( 'smarttoolz_story_image_height', 156 ) ) ) );
    $lift    = max( 0, min( 12, absint( get_theme_mod( 'smarttoolz_hover_lift', 4 ) ) ) );
    $font    = sanitize_text_field( get_theme_mod( 'smarttoolz_body_font', 'Inter,ui-sans-serif,system-ui,sans-serif' ) );

    $css .= '.content-with-sidebar,.archive-layout{grid-template-columns:minmax(0,1fr) ' . $sidebar . 'px;gap:' . $gap . 'px;}';
    $css .= '.single-grid{grid-template-columns:minmax(0,820px) ' . max( 220, min( 360, $sidebar - 40 ) ) . 'px;gap:' . $gap . 'px;}';
    $css .= '.story-media{height:' . $height . 'px;border-radius:' . $radius . 'px;}';
    $css .= '.hero-card,.post-nav a,.related-card{border-radius:' . $radius . 'px;}';
    $css .= '.story-card,.topic-card{transition:transform .22s ease,box-shadow .22s ease;}';
    $css .= '.story-card:hover,.topic-card:hover{transform:translateY(-' . $lift . 'px);}';
    $css .= 'body{font-family:' . esc_attr( $font ) . ';}';

    if ( ! get_theme_mod( 'smarttoolz_header_sticky', true ) ) {
        $css .= '.header{position:relative;}';
    }
    if ( ! get_theme_mod( 'smarttoolz_image_zoom', true ) ) {
        $css .= '.story-media img,.hero-card-media img{transition:none!important;}';
    }

    wp_add_inline_style( 'smarttoolz-blog-brand', $css );
}
add_action( 'wp_enqueue_scripts', 'smarttoolz_blog_customizer_css', 30 );

function smarttoolz_blog_footer_year_text( $text ) {
    return str_replace( '%year%', date_i18n( 'Y' ), $text );
}

/* Small client-side engagement helpers. No external library required. */
function smarttoolz_blog_engagement_script() {
    if ( ! is_singular() ) {
        return;
    }

    $progress = get_theme_mod( 'smarttoolz_reading_progress', true );
    $top      = get_theme_mod( 'smarttoolz_back_to_top', true );
    if ( ! $progress && ! $top ) {
        return;
    }
    ?>
    <script>
    document.addEventListener('DOMContentLoaded',function(){
      <?php if ( $progress ) : ?>
      (function(){var bar=document.querySelector('.reading-progress');if(!bar)return;var update=function(){var d=document.documentElement,b=document.body,max=Math.max(1,d.scrollHeight-d.clientHeight),p=Math.min(100,Math.max(0,(d.scrollTop||b.scrollTop)/max*100));bar.style.width=p+'%';};window.addEventListener('scroll',update,{passive:true});update();})();
      <?php endif; ?>
      <?php if ( $top ) : ?>
      (function(){var btn=document.querySelector('.back-to-top');if(!btn)return;var update=function(){btn.classList.toggle('is-visible',(window.scrollY||window.pageYOffset)>500);};window.addEventListener('scroll',update,{passive:true});btn.addEventListener('click',function(){window.scrollTo({top:0,behavior:'smooth'});});update();})();
      <?php endif; ?>
    });
    </script>
    <?php
}
add_action( 'wp_footer', 'smarttoolz_blog_engagement_script', 99 );

/* Preserve SmartToolz branding in the footer while keeping Site Identity
 * completely controlled by WordPress. */
function smarttoolz_blog_footer_branding() {
    return esc_html( get_theme_mod( 'smarttoolz_footer_brand', 'SmartToolz' ) );
}
