<?php
/** SmartToolz public creator channel renderer. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_channel_user() {
    $handle = '';
    $route_handle = get_query_var( 'smarttoolz_video_channel', '' );
    if ( $route_handle ) {
        $handle = sanitize_user( $route_handle, true );
    }
    if ( ! $handle ) {
        $request_uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
        if ( preg_match( '#/@([A-Za-z0-9._-]+)(?:/|$)#', $request_uri, $matches ) ) {
            $handle = sanitize_user( $matches[1], true );
        }
    }
    if ( $handle ) {
        $user = get_user_by( 'login', $handle );
        if ( ! $user ) { $user = get_user_by( 'slug', $handle ); }
        if ( $user ) { return $user; }
    }
    return is_user_logged_in() ? wp_get_current_user() : false;
}

function smarttoolz_video_render_channel() {
    $user = smarttoolz_video_channel_user();
    if ( ! $user || empty( $user->ID ) ) {
        wp_safe_redirect( smarttoolz_video_route_url( 'login' ) );
        exit;
    }
    $handle = $user->user_nicename ?: $user->user_login;
    $videos = get_posts( array( 'post_type'=>'stv_video', 'post_status'=>'publish', 'author'=>absint($user->ID), 'posts_per_page'=>24, 'orderby'=>'date', 'order'=>'DESC' ) );
    get_header();
    ?>
    <div class="stv-channel-page">
        <section class="stv-channel-hero">
            <div class="stv-channel-avatar"><?php echo esc_html( strtoupper( substr( $user->display_name ?: $user->user_login, 0, 1 ) ) ); ?></div>
            <div class="stv-channel-info">
                <span class="stv-eyebrow">SmartToolz Channel</span>
                <h1 class="stv-page-title"><?php echo esc_html( $user->display_name ?: $user->user_login ); ?></h1>
                <p>@<?php echo esc_html( $handle ); ?> · <?php echo esc_html( count($videos) ); ?> videos</p>
                <?php $description = get_the_author_meta( 'description', $user->ID ); if ( $description ) : ?><div class="stv-channel-description"><?php echo esc_html( $description ); ?></div><?php endif; ?>
            </div>
        </section>
        <nav class="stv-channel-tabs" aria-label="Channel navigation">
            <a class="stv-channel-tab stv-channel-tab--active" href="<?php echo esc_url( smarttoolz_video_route_url('channel', $handle) ); ?>">Home</a>
            <a class="stv-channel-tab" href="<?php echo esc_url( smarttoolz_video_route_url('channel-videos', $handle) ); ?>">Videos</a>
            <?php if ( smarttoolz_video_route_enabled('channel-shorts') ) : ?><a class="stv-channel-tab" href="<?php echo esc_url( smarttoolz_video_route_url('channel-shorts', $handle) ); ?>">Shorts</a><?php endif; ?>
            <?php if ( smarttoolz_video_route_enabled('channel-live') ) : ?><a class="stv-channel-tab" href="<?php echo esc_url( smarttoolz_video_route_url('channel-live', $handle) ); ?>">Live</a><?php endif; ?>
        </nav>
        <section>
            <div class="stv-section-heading"><div><h2 class="stv-page-title">Videos</h2><p class="stv-section-subtitle">Published by <?php echo esc_html( $user->display_name ?: $user->user_login ); ?></p></div></div>
            <?php if ( empty($videos) ) : ?>
                <div class="stv-empty">This creator has not published any videos yet.</div>
            <?php else : ?>
                <div class="stv-video-grid">
                <?php foreach ( $videos as $video ) : ?>
                    <article class="stv-video-card">
                        <a class="stv-video-card__thumb" href="<?php echo esc_url( smarttoolz_video_route_url('watch',$video->ID) ); ?>"><?php if ( has_post_thumbnail($video->ID) ) { echo get_the_post_thumbnail($video->ID,'medium_large'); } ?><span class="stv-video-card__play">▶</span><span class="stv-video-card__duration">Video</span></a>
                        <div class="stv-video-card__body"><span class="stv-video-card__avatar"><?php echo esc_html( strtoupper(substr($user->display_name ?: $user->user_login,0,1)) ); ?></span><div><a class="stv-video-card__title" href="<?php echo esc_url(smarttoolz_video_route_url('watch',$video->ID)); ?>"><?php echo esc_html($video->post_title); ?></a><div class="stv-video-card__meta"><?php echo esc_html($user->display_name ?: $user->user_login); ?> · <?php echo esc_html(get_the_date('',$video)); ?></div></div></div>
                    </article>
                <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
    <?php
    get_footer();
    exit;
}
add_action( 'template_redirect', 'smarttoolz_video_channel_template_redirect', 100 );
function smarttoolz_video_channel_template_redirect() {
    if ( ! function_exists('smarttoolz_video_is_route') || ! smarttoolz_video_is_route() ) { return; }
    $route = get_query_var('smarttoolz_video_route','');
    if ( in_array($route,array('channel','channel-videos','channel-shorts','channel-live'),true) ) { smarttoolz_video_render_channel(); }
}
