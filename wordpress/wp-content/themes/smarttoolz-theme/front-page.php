<?php
/** SmartToolz front page. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();

if ( function_exists( 'stv_platform_shortcode' ) ) {
    echo '<div class="stv-home-shell">';
    echo '<section class="stv-home-hero"><div><span class="stv-eyebrow">SmartToolz Video</span><h1>Watch. Share. Create.</h1><p>Your video community on SmartToolz. Discover creators, follow channels and publish your own videos.</p></div><div class="stv-home-actions"><a class="stv-upload-cta" href="' . esc_url( home_url( '/video-upload/' ) ) . '">Upload video</a>' . ( is_user_logged_in() ? '<a class="stv-home-secondary" href="' . esc_url( home_url( '/creator-studio/' ) ) . '">Creator Studio</a>' : '' ) . '</div></section>';
    echo '<section class="stv-home-section"><div class="stv-home-section-head"><h2>Latest videos</h2><a href="' . esc_url( get_post_type_archive_link( 'st_video' ) ) . '">View all →</a></div>';
    echo stv_render_feed( array( 'per_page' => 16, 'orderby' => 'date' ) );
    echo '</section><section class="stv-home-section"><div class="stv-home-section-head"><h2>Trending</h2><a href="' . esc_url( add_query_arg( 'stv_sort', 'views', home_url( '/' ) ) ) . '">See trending →</a></div>';
    echo stv_render_feed( array( 'per_page' => 8, 'orderby' => 'views' ) );
    echo '</section></div>';
    get_footer();
    return;
}

$hero_title = get_theme_mod( 'smarttoolz_hero_title', 'Useful ideas, guides and technology worth knowing.' );
$hero_subtitle = get_theme_mod( 'smarttoolz_hero_subtitle', 'Practical tools, useful content and a clean experience built for everyday use.' );
$categories = get_categories( array( 'hide_empty' => true, 'number' => 6, 'orderby' => 'count', 'order' => 'DESC' ) );
$featured = new WP_Query( array( 'posts_per_page' => 1, 'post_status' => 'publish', 'ignore_sticky_posts' => false ) );
$latest = new WP_Query( array( 'posts_per_page' => 6, 'post_status' => 'publish', 'offset' => 1 ) );
?>
<section class="st-hero"><div class="st-hero-grid"><div><span class="st-eyebrow">SmartToolz</span><h1><?php echo esc_html( $hero_title ); ?></h1><p><?php echo esc_html( $hero_subtitle ); ?></p><div class="st-actions"><a class="st-button" href="#latest"><?php esc_html_e( 'Explore content', 'smarttoolz' ); ?></a><a class="st-button st-secondary" href="#categories"><?php esc_html_e( 'Browse categories', 'smarttoolz' ); ?></a></div><div class="st-hero-search"><?php get_search_form(); ?></div></div><div class="st-hero-card"><strong><?php esc_html_e( 'Built for useful content', 'smarttoolz' ); ?></strong><p><?php esc_html_e( 'Fast pages, readable articles, focused navigation and a responsive experience.', 'smarttoolz' ); ?></p><div class="st-stat-row"><div class="st-stat"><b><?php echo esc_html( (string) wp_count_posts( 'post' )->publish ); ?></b><span><?php esc_html_e( 'Published posts', 'smarttoolz' ); ?></span></div><div class="st-stat"><b><?php echo esc_html( (string) count( $categories ) ); ?></b><span><?php esc_html_e( 'Top topics', 'smarttoolz' ); ?></span></div></div></div></div></section>
<?php if ( $featured->have_posts() ) : $featured->the_post(); ?><section class="st-section"><div class="st-section-head"><h2><?php esc_html_e( 'Featured article', 'smarttoolz' ); ?></h2></div><article class="st-featured-card st-card"><?php if ( has_post_thumbnail() ) : ?><a class="st-featured-media" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'large' ); ?></a><?php endif; ?><div class="st-featured-body"><div class="st-meta"><?php echo esc_html( get_the_date() ); ?> · <?php echo esc_html( smarttoolz_reading_time() ); ?></div><h2 class="st-featured-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 34 ) ); ?></p><a class="st-button" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read featured article', 'smarttoolz' ); ?></a></div></article></section><?php wp_reset_postdata(); endif; ?>
<section class="st-section" id="categories"><div class="st-section-head"><h2><?php esc_html_e( 'Explore categories', 'smarttoolz' ); ?></h2></div><div class="st-grid st-category-grid"><?php if ( $categories ) : foreach ( $categories as $category ) : ?><a class="st-card st-category-card" href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"><span class="st-category-icon">#</span><h3><?php echo esc_html( $category->name ); ?></h3><p><?php echo esc_html( sprintf( _n( '%d article', '%d articles', $category->count, 'smarttoolz' ), $category->count ) ); ?></p></a><?php endforeach; endif; ?></div></section>
<section class="st-section" id="latest"><div class="st-section-head"><h2><?php esc_html_e( 'Latest from SmartToolz', 'smarttoolz' ); ?></h2></div><div class="st-grid"><?php if ( $latest->have_posts() ) : while ( $latest->have_posts() ) : $latest->the_post(); ?><article class="st-card st-feature"><?php if ( has_post_thumbnail() ) : ?><a class="st-thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'smarttoolz-card' ); ?></a><?php endif; ?><div class="st-meta"><?php echo esc_html( get_the_date() ); ?> · <?php echo esc_html( smarttoolz_reading_time() ); ?></div><h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3><p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p><a class="st-button" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read article', 'smarttoolz' ); ?></a></article><?php endwhile; wp_reset_postdata(); endif; ?></div></section>
<?php get_footer(); ?>
