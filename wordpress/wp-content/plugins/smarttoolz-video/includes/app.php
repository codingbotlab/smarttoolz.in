<?php
/**
 * SmartToolz Video frontend application shell.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function smarttoolz_video_render_app() {
    $route = get_query_var( 'smarttoolz_video_route', 'home' );
    $id    = get_query_var( 'smarttoolz_video_id', '' );
    ?>
    <!doctype html>
    <html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo esc_html( get_bloginfo( 'name' ) . ' — Video' ); ?></title>
        <?php wp_head(); ?>
        <style>
            html,body{margin:0;padding:0;background:#0f0f0f;color:#fff}
            body{font-family:Arial,Helvetica,sans-serif}
            .stv-app{min-height:100vh}
            .stv-app__header{height:64px;display:flex;align-items:center;padding:0 24px;border-bottom:1px solid #262626;background:#111}
            .stv-app__brand{font-size:20px;font-weight:700}
            .stv-app__main{padding:32px}
        </style>
    </head>
    <body <?php body_class( 'smarttoolz-video-app' ); ?>>
        <?php wp_body_open(); ?>
        <div class="stv-app">
            <header class="stv-app__header">
                <div class="stv-app__brand"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></div>
            </header>
            <main class="stv-app__main" data-route="<?php echo esc_attr( $route ); ?>" data-video-id="<?php echo esc_attr( $id ); ?>">
                <?php
                if ( 'home' === $route ) {
                    echo '<h1>Video</h1>';
                } elseif ( 'watch' === $route ) {
                    echo '<h1>Watch</h1>';
                } elseif ( 'upload' === $route ) {
                    echo '<h1>Upload</h1>';
                }
                ?>
            </main>
        </div>
        <?php wp_footer(); ?>
    </body>
    </html>
    <?php
    exit;
}

function smarttoolz_video_route_template( $template ) {
    if ( smarttoolz_video_is_route() ) {
        smarttoolz_video_render_app();
    }

    return $template;
}
add_filter( 'template_include', 'smarttoolz_video_route_template', 99 );
