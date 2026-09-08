<?php
/** SmartToolz fallback archive. */
get_header();
?>
<section class="st-entry"><header class="st-card"><h1 class="st-entry-title"><?php echo esc_html( is_home() ? __( 'Latest articles', 'smarttoolz' ) : get_the_archive_title() ); ?></h1><?php if ( is_archive() ) { the_archive_description( '<div class="st-meta">', '</div>' ); } ?></header>
<div class="st-grid" style="margin-top:20px">
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<article class="st-card st-feature"><div class="st-meta"><?php echo esc_html( get_the_date() ); ?></div><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p><a class="st-button" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read article', 'smarttoolz' ); ?></a></article>
<?php endwhile; else: ?><article class="st-card"><h2><?php esc_html_e( 'Nothing found', 'smarttoolz' ); ?></h2><p><?php esc_html_e( 'There is no content to display yet.', 'smarttoolz' ); ?></p></article><?php endif; ?>
</div><?php the_posts_pagination( array( 'mid_size' => 1 ) ); ?></section>
<?php get_footer(); ?>
