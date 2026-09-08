<?php
/**
 * SmartToolz Video library/explore page shortcodes.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_user_meta_ids( $prefix ) {
    if ( ! is_user_logged_in() ) { return array(); }
    $raw = (array) get_user_meta( get_current_user_id() );
    $ids = array();
    foreach ( $raw as $key => $values ) {
        if ( strpos( $key, $prefix ) !== 0 ) { continue; }
        $id = absint( substr( $key, strlen( $prefix ) ) );
        if ( $id ) { $ids[] = $id; }
    }
    return array_values( array_unique( $ids ) );
}

function stv_library_feed( $args = array() ) {
    $args = wp_parse_args( $args, array(
        'post__in' => array(),
        'author__in' => array(),
        'orderby' => 'date',
        'order' => 'DESC',
        'per_page' => 24,
        'search' => '',
    ) );
    $query = array(
        'post_type' => 'st_video',
        'post_status' => 'publish',
        'posts_per_page' => (int) $args['per_page'],
        'ignore_sticky_posts' => true,
        'orderby' => $args['orderby'],
        'order' => $args['order'],
    );
    if ( $args['post__in'] ) { $query['post__in'] = array_map( 'absint', $args['post__in'] ); }
    else { $query['post__in'] = array( 0 ); }
    if ( $args['author__in'] ) { $query['author__in'] = array_map( 'absint', $args['author__in'] ); unset( $query['post__in'] ); }
    if ( $args['search'] !== '' ) { $query['s'] = sanitize_text_field( $args['search'] ); unset( $query['post__in'] ); }
    $loop = new WP_Query( $query );
    ob_start();
    if ( $loop->have_posts() ) {
        echo '<div class="stv-grid">';
        while ( $loop->have_posts() ) { $loop->the_post(); echo stv_video_card( get_the_ID() ); }
        echo '</div>';
    } else {
        echo '<div class="stv-empty">Nothing here yet.</div>';
    }
    wp_reset_postdata();
    return ob_get_clean();
}

function smarttoolz_video_home_shortcode() {
    ob_start(); ?>
    <div class="stv-page-shell">
        <section class="stv-page-hero"><div><span class="stv-eyebrow">SmartToolz Video</span><h1>Watch. Share. Create.</h1><p>Discover videos, follow creators and build your own channel.</p></div><div class="stv-page-hero-actions"><a class="stv-upload-cta" href="<?php echo esc_url( smarttoolz_video_page_link( 'video-upload' ) ); ?>">Upload video</a></div></section>
        <div class="stv-chip-row"><a href="<?php echo esc_url( smarttoolz_video_page_link( 'video-library' ) ); ?>">All videos</a><a href="<?php echo esc_url( smarttoolz_video_page_link( 'trending-videos' ) ); ?>">Trending</a><a href="<?php echo esc_url( smarttoolz_video_page_link( 'video-categories' ) ); ?>">Categories</a><?php if ( is_user_logged_in() ) : ?><a href="<?php echo esc_url( smarttoolz_video_page_link( 'subscriptions' ) ); ?>">Subscriptions</a><a href="<?php echo esc_url( smarttoolz_video_page_link( 'watch-history' ) ); ?>">History</a><a href="<?php echo esc_url( smarttoolz_video_page_link( 'liked-videos' ) ); ?>">Liked</a><?php endif; ?></div>
        <section class="stv-home-section"><div class="stv-home-section-head"><h2>Latest videos</h2><a href="<?php echo esc_url( smarttoolz_video_page_link( 'video-library' ) ); ?>">View all →</a></div><?php echo stv_render_feed( array( 'per_page' => 12, 'orderby' => 'date' ) ); ?></section>
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
    ob_start(); ?><div class="stv-page-shell"><div class="stv-page-heading"><span class="stv-eyebrow">Search</span><h1><?php echo $term ? 'Search results' : 'Search videos'; ?></h1></div><form class="stv-search-big" method="get"><input type="search" name="stv_search" value="<?php echo esc_attr( $term ); ?>" placeholder="Search videos..."><button type="submit">Search</button></form><?php echo stv_library_feed( array( 'search' => $term, 'per_page' => 24 ) ); ?></div><?php return ob_get_clean();
}
add_shortcode( 'smarttoolz_video_search', 'smarttoolz_video_search_shortcode' );

function smarttoolz_video_subscriptions_shortcode() {
    if ( ! is_user_logged_in() ) { return '<div class="stv-login"><h2>Sign in to see subscriptions</h2><p>Follow creators and their latest videos will appear here.</p></div>'; }
    $all = (array) get_user_meta( get_current_user_id() );
    $author_ids = array();
    foreach ( $all as $key => $values ) { if ( strpos( $key, 'stv_subscribed_' ) === 0 ) { $id = absint( substr( $key, strlen( 'stv_subscribed_' ) ) ); if ( $id ) { $author_ids[] = $id; } } }
    $author_ids = array_values( array_unique( $author_ids ) );
    ob_start(); ?><div class="stv-page-shell"><div class="stv-page-heading"><span class="stv-eyebrow">Your feed</span><h1>Subscriptions</h1><p>Latest uploads from creators you follow.</p></div><?php if ( $author_ids ) { echo stv_library_feed( array( 'author__in' => $author_ids, 'per_page' => 24 ) ); } else { echo '<div class="stv-empty">You are not subscribed to any channels yet.</div>'; } ?></div><?php return ob_get_clean();
}
add_shortcode( 'smarttoolz_video_subscriptions', 'smarttoolz_video_subscriptions_shortcode' );

function smarttoolz_video_liked_shortcode() {
    if ( ! is_user_logged_in() ) { return '<div class="stv-login"><h2>Sign in to see liked videos</h2></div>'; }
    $ids = smarttoolz_video_user_meta_ids( 'stv_liked_' );
    ob_start(); ?><div class="stv-page-shell"><div class="stv-page-heading"><span class="stv-eyebrow">Library</span><h1>Liked Videos</h1><p>Videos you have liked.</p></div><?php echo stv_library_feed( array( 'post__in' => $ids, 'per_page' => 24, 'orderby' => 'post__in' ) ); ?></div><?php return ob_get_clean();
}
add_shortcode( 'smarttoolz_video_liked', 'smarttoolz_video_liked_shortcode' );
