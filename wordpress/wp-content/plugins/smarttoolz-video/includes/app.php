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

    get_header();
    ?>
        <div class="stv-app" data-route="<?php echo esc_attr( $route ); ?>" data-video-id="<?php echo esc_attr( $id ); ?>">
            <div class="stv-chips">
                <a class="stv-chip stv-chip--active" href="<?php echo esc_url( home_url( '/video/' ) ); ?>">All</a>
                <a class="stv-chip" href="<?php echo esc_url( home_url( '/video/shorts/' ) ); ?>">Shorts</a>
                <a class="stv-chip" href="<?php echo esc_url( home_url( '/video/trending/' ) ); ?>">Trending</a>
                <a class="stv-chip" href="<?php echo esc_url( home_url( '/video/live/' ) ); ?>">Live</a>
                <a class="stv-chip" href="<?php echo esc_url( home_url( '/video/subscriptions/' ) ); ?>">Subscriptions</a>
            </div>

            <?php if ( 'home' === $route ) : ?>
                <h1 class="stv-page-title">SmartToolz Videos</h1>
                <div class="stv-hero__box">
                    <h2 style="margin-top:0">Your video-sharing home is ready.</h2>
                    <p style="color:#aaa">The SmartToolz interface is installed and ready for the video features we build next.</p>
                </div>
            <?php elseif ( 'watch' === $route ) : ?>
                <h1 class="stv-page-title">Watch</h1>
                <div class="stv-empty">Video ID: <?php echo esc_html( $id ); ?></div>
            <?php elseif ( 'upload' === $route ) : ?>
                <h1 class="stv-page-title">Upload Video</h1>
                <div class="stv-empty">Upload interface will be added here.</div>
            <?php else : ?>
                <h1 class="stv-page-title"><?php echo esc_html( ucwords( str_replace( '-', ' ', $route ) ) ); ?></h1>
                <div class="stv-empty">This SmartToolz section is ready for its feature implementation.</div>
            <?php endif; ?>
        </div>
    <?php
    get_footer();
    exit;
}

function smarttoolz_video_route_template( $template ) {
    if ( smarttoolz_video_is_route() ) {
        smarttoolz_video_render_app();
    }

    return $template;
}
add_filter( 'template_include', 'smarttoolz_video_route_template', 99 );
