<?php
/**
 * SmartToolz Video platform pages, routing and personal libraries.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_page_definitions() {
    return array(
        'video-home'       => array( 'title' => 'Video Home', 'content' => '[smarttoolz_video_home]' ),
        'video-library'    => array( 'title' => 'Videos', 'content' => '[smarttoolz_video_platform]' ),
        'trending-videos'  => array( 'title' => 'Trending Videos', 'content' => '[smarttoolz_video_trending]' ),
        'video-categories' => array( 'title' => 'Video Categories', 'content' => '[smarttoolz_video_categories]' ),
        'video-search'     => array( 'title' => 'Search Videos', 'content' => '[smarttoolz_video_search]' ),
        'subscriptions'   => array( 'title' => 'Subscriptions', 'content' => '[smarttoolz_video_subscriptions]' ),
        'liked-videos'    => array( 'title' => 'Liked Videos', 'content' => '[smarttoolz_video_liked]' ),
        'watch-history'   => array( 'title' => 'Watch History', 'content' => '[smarttoolz_video_history]' ),
        'video-upload'    => array( 'title' => 'Upload Video', 'content' => '[smarttoolz_video_upload]' ),
        'creator-studio'  => array( 'title' => 'Creator Studio', 'content' => '[smarttoolz_creator_dashboard]' ),
        'channel'         => array( 'title' => 'Channel', 'content' => '[smarttoolz_channel]' ),
    );
}

function smarttoolz_video_sync_pages( $force = false ) {
    $version = '3.2.0';
    if ( ! $force && get_option( 'smarttoolz_video_pages_version' ) === $version ) { return; }
    $ids = (array) get_option( 'smarttoolz_video_page_ids', array() );
    foreach ( smarttoolz_video_page_definitions() as $slug => $page ) {
        $page_obj = ! empty( $ids[ $slug ] ) ? get_post( (int) $ids[ $slug ] ) : get_page_by_path( $slug, OBJECT, 'page' );
        $data = array(
            'post_type'    => 'page',
            'post_status'  => 'publish',
            'post_title'   => $page['title'],
            'post_name'    => $slug,
            'post_content' => $page['content'],
        );
        if ( $page_obj && 'page' === $page_obj->post_type ) {
            $managed = (bool) get_post_meta( $page_obj->ID, '_smarttoolz_video_managed', true );
            $legacy_owned = (string) $page_obj->post_content === (string) $page['content'];
            if ( $managed || $legacy_owned ) {
                $data['ID'] = $page_obj->ID;
                wp_update_post( $data );
                update_post_meta( $page_obj->ID, '_smarttoolz_video_managed', 1 );
            }
            $ids[ $slug ] = (int) $page_obj->ID;
        } else {
            $new_id = wp_insert_post( $data, true );
            if ( ! is_wp_error( $new_id ) ) {
                $ids[ $slug ] = (int) $new_id;
                update_post_meta( $new_id, '_smarttoolz_video_managed', 1 );
            }
        }
    }
    update_option( 'smarttoolz_video_page_ids', $ids, false );
    update_option( 'smarttoolz_video_pages_version', $version, false );
}

function smarttoolz_video_activate_pages() {
    smarttoolz_video_sync_pages( true );
    flush_rewrite_rules( true );
}
if ( defined( 'SMARTTOOLZ_VIDEO_FILE' ) ) {
    register_activation_hook( SMARTTOOLZ_VIDEO_FILE, 'smarttoolz_video_activate_pages' );
}
add_action( 'plugins_loaded', 'smarttoolz_video_sync_pages', 30 );

function smarttoolz_video_page_link( $slug ) {
    $ids = (array) get_option( 'smarttoolz_video_page_ids', array() );
    if ( ! empty( $ids[ $slug ] ) ) {
        $url = get_permalink( (int) $ids[ $slug ] );
        if ( $url ) { return $url; }
    }
    if ( 'videos' === $slug ) { return get_post_type_archive_link( 'st_video' ) ?: home_url( '/videos/' ); }
    return home_url( '/' . trim( $slug, '/' ) . '/' );
}

function smarttoolz_video_pretty_channel_routes( $rules ) {
    $custom = array( '^channel/([^/]+)/?$' => 'index.php?pagename=channel&stv_channel=$matches[1]' );
    return $custom + $rules;
}
add_filter( 'rewrite_rules_array', 'smarttoolz_video_pretty_channel_routes', 30 );

function smarttoolz_video_query_vars( $vars ) {
    $vars[] = 'stv_channel';
    return $vars;
}
add_filter( 'query_vars', 'smarttoolz_video_query_vars' );

function smarttoolz_video_user_video_ids( $meta_key ) {
    if ( ! is_user_logged_in() ) { return array(); }
    $raw = (array) get_user_meta( get_current_user_id() );
    $ids = array();
    foreach ( $raw as $key => $values ) {
        if ( 0 !== strpos( $key, $meta_key ) ) { continue; }
        $id = absint( str_replace( $meta_key, '', $key ) );
        if ( $id ) { $ids[] = $id; }
    }
    return array_values( array_unique( $ids ) );
}

function smarttoolz_video_library_query( $ids ) {
    return array(
        'post_type'           => 'st_video',
        'post_status'         => 'publish',
        'posts_per_page'      => 24,
        'post__in'            => $ids ? $ids : array( 0 ),
        'orderby'             => $ids ? 'post__in' : 'date',
        'ignore_sticky_posts' => true,
    );
}

function smarttoolz_video_home_shortcode() {
    ob_start(); ?>
    <div class="stv-page-shell">
      <section class="stv-page-hero"><div><span class="stv-eyebrow">SmartToolz Video</span><h1>Watch. Share. Create.</h1><p>Discover videos, follow creators and build your own channel.</p></div><div class="stv-page-hero-actions"><a class="stv-upload-cta" href="<?php echo esc_url( smarttoolz_video_page_link( 'video-upload' ) ); ?>">Upload video</a></div></section>
      <div class="stv-chip-row"><a href="<?php echo esc_url( get_post_type_archive_link( 'st_video' ) ?: home_url( '/videos/' ) ); ?>">All videos</a><a href="<?php echo esc_url( smarttoolz_video_page_link( 'trending-videos' ) ); ?>">Trending</a><a href="<?php echo esc_url( smarttoolz_video_page_link( 'video-categories' ) ); ?>">Categories</a><?php if ( is_user_logged_in() ) : ?><a href="<?php echo esc_url( smarttoolz_video_page_link( 'subscriptions' ) ); ?>">Subscriptions</a><a href="<?php echo esc_url( smarttoolz_video_page_link( 'watch-history' ) ); ?>">History</a><?php endif; ?></div>
      <section class="stv-home-section"><div class="stv-home-section-head"><h2>Latest videos</h2><a href="<?php echo esc_url( get_post_type_archive_link( 'st_video' ) ?: home_url( '/videos/' ) ); ?>">View all →</a></div><?php echo stv_render_feed( array( 'per_page' => 12, 'orderby' => 'date' ) ); ?></section>
      <section class="stv-home-section"><div class="stv-home-section-head"><h2>Trending</h2><a href="<?php echo esc_url( smarttoolz_video_page_link( 'trending-videos' ) ); ?>">See all →</a></div><?php echo stv_render_feed( array( 'per_page' => 8, 'orderby' => 'views' ) ); ?></section>
    </div>
    <?php return ob_get_clean();
}
add_shortcode( 'smarttoolz_video_home', 'smarttoolz_video_home_shortcode' );

function smarttoolz_video_trending_shortcode() {
    ob_start(); ?><div class="stv-page-shell"><div class="stv-page-heading"><span class="stv-eyebrow">Explore</span><h1>Trending Videos</h1><p>Videos getting the most attention right now.</p></div><?php echo stv_render_feed( array( 'per_page' => 24, 'orderby' => 'views' ) ); ?></div><?php return ob_get_clean();
}
add_shortcode( 'smarttoolz_video_trending', 'smarttoolz_video_trending_shortcode' );

function smarttoolz_video_categories_shortcode() {
    $terms = get_terms( array( 'taxonomy' => 'st_video_category', 'hide_empty' => false, 'orderby' => 'count', 'order' => 'DESC' ) );
    ob_start(); ?><div class="stv-page-shell"><div class="stv-page-heading"><span class="stv-eyebrow">Explore</span><h1>Video Categories</h1><p>Browse videos by topic.</p></div><div class="stv-category-grid"><?php if ( ! is_wp_error( $terms ) && $terms ) : foreach ( $terms as $term ) : ?><a class="stv-category-card" href="<?php echo esc_url( get_term_link( $term ) ); ?>"><strong><?php echo esc_html( $term->name ); ?></strong><span><?php echo esc_html( number_format_i18n( $term->count ) ); ?> videos</span></a><?php endforeach; else : ?><div class="stv-empty">No video categories yet.</div><?php endif; ?></div></div><?php return ob_get_clean();
}
add_shortcode( 'smarttoolz_video_categories', 'smarttoolz_video_categories_shortcode' );

function smarttoolz_video_search_shortcode() {
    $term = isset( $_GET['stv_search'] ) ? sanitize_text_field( wp_unslash( $_GET['stv_search'] ) ) : '';
    ob_start(); ?><div class="stv-page-shell"><div class="stv-page-heading"><span class="stv-eyebrow">Search</span><h1><?php echo $term ? 'Search results' : 'Search videos'; ?></h1></div><form class="stv-search-big" method="get"><input type="search" name="stv_search" value="<?php echo esc_attr( $term ); ?>" placeholder="Search videos..."><button type="submit">Search</button></form><?php echo stv_render_feed( array( 'per_page' => 24, 'search' => $term ) ); ?></div><?php return ob_get_clean();
}
add_shortcode( 'smarttoolz_video_search', 'smarttoolz_video_search_shortcode' );

function smarttoolz_video_subscriptions_shortcode() {
    if ( ! is_user_logged_in() ) { return '<div class="stv-login"><h2>Sign in to see subscriptions</h2><p>Follow creators and their latest videos will appear here.</p></div>'; }
    $following = (array) get_user_meta( get_current_user_id() );
    $author_ids = array();
    foreach ( $following as $key => $values ) { if ( 0 === strpos( $key, 'stv_subscribed_' ) ) { $id = absint( str_replace( 'stv_subscribed_', '', $key ) ); if ( $id ) { $author_ids[] = $id; } } }
    $author_ids = array_values( array_unique( $author_ids ) );
    if ( ! $author_ids ) { return '<div class="stv-empty">You are not subscribed to any channels yet.</div>'; }
    $q = new WP_Query( array( 'post_type' => 'st_video', 'post_status' => 'publish', 'posts_per_page' => 24, 'author__in' => $author_ids, 'orderby' => 'date', 'order' => 'DESC' ) );
    ob_start(); ?><div class="stv-page-shell"><div class="stv-page-heading"><span class="stv-eyebrow">Your feed</span><h1>Subscriptions</h1><p>Latest uploads from creators you follow.</p></div><?php if ( $q->have_posts() ) { echo '<div class="stv-grid">'; while ( $q->have_posts() ) { $q->the_post(); echo stv_video_card( get_the_ID() ); } echo '</div>'; } else { echo '<div class="stv-empty">No new videos from your subscriptions.</div>'; } wp_reset_postdata(); ?></div><?php return ob_get_clean();
}
add_shortcode( 'smarttoolz_video_subscriptions', 'smarttoolz_video_subscriptions_shortcode' );

function smarttoolz_video_liked_shortcode() {
    if ( ! is_user_logged_in() ) { return '<div class="stv-login"><h2>Sign in to see liked videos</h2></div>'; }
    $ids = smarttoolz_video_user_video_ids( 'stv_liked_' );
    ob_start(); ?><div class="stv-page-shell"><div class="stv-page-heading"><span class="stv-eyebrow">Library</span><h1>Liked Videos</h1></div><?php echo stv_render_feed_from_query( smarttoolz_video_library_query( $ids ) ); ?></div><?php return ob_get_clean();
}
add_shortcode( 'smarttoolz_video_liked', 'smarttoolz_video_liked_shortcode' );

function smarttoolz_video_history_shortcode() {
    if ( ! is_user_logged_in() ) { return '<div class="stv-login"><h2>Sign in to see watch history</h2></div>'; }
    $ids = smarttoolz_video_user_video_ids( 'stv_history_' );
    ob_start(); ?><div class="stv-page-shell"><div class="stv-page-heading"><span class="stv-eyebrow">Library</span><h1>Watch History</h1></div><?php echo stv_render_feed_from_query( smarttoolz_video_library_query( $ids ) ); ?></div><?php return ob_get_clean();
}
add_shortcode( 'smarttoolz_video_history', 'smarttoolz_video_history_shortcode' );

function stv_render_feed_from_query( $args ) {
    $query = new WP_Query( $args );
    ob_start();
    if ( $query->have_posts() ) { echo '<div class="stv-grid">'; while ( $query->have_posts() ) { $query->the_post(); echo stv_video_card( get_the_ID() ); } echo '</div>'; }
    else { echo '<div class="stv-empty">Nothing here yet.</div>'; }
    wp_reset_postdata();
    return ob_get_clean();
}

function smarttoolz_video_record_history( $post_id ) {
    if ( ! is_user_logged_in() || 'st_video' !== get_post_type( $post_id ) ) { return; }
    update_user_meta( get_current_user_id(), 'stv_history_' . absint( $post_id ), time() );
}
add_action( 'wp', function() { if ( is_singular( 'st_video' ) ) { smarttoolz_video_record_history( get_queried_object_id() ); } }, 25 );

function smarttoolz_video_upgrade_sync() {
    if ( is_admin() && current_user_can( 'manage_options' ) ) { smarttoolz_video_sync_pages(); }
}
add_action( 'admin_init', 'smarttoolz_video_upgrade_sync' );
