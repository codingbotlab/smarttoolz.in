<?php
/** SmartToolz search template. */
get_header();
?>
<section class="st-entry"><div class="st-card"><span class="st-eyebrow">Search</span><h1 class="st-entry-title"><?php printf( esc_html__( 'Results for “%s”', 'smarttoolz' ), esc_html( get_search_query() ) ); ?></h1><?php get_search_form(); ?></div>
<div class="st-grid" style="margin-top:20px">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?><article class="st-card st-feature"><div class="st-meta"><?php echo esc_html( get_the_date() ); ?></div><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p><a class="st-button" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read article', 'smarttoolz' ); ?></a></article><?php endwhile; else: ?><article class="st-card"><h2><?php esc_html_e( 'No matches found.', 'smarttoolz' ); ?></h2><p><?php esc_html_e( 'Try another search term.', 'smarttoolz' ); ?></p></article><?php endif; ?></div><?php the_posts_pagination( array( 'mid_size'=>1 ) ); ?></section>
<?php get_footer(); ?>
