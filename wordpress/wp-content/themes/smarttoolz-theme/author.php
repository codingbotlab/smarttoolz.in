<?php
/** SmartToolz author archive. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$author = get_queried_object();
?>
<section class="st-card st-archive-header">
  <?php smarttoolz_breadcrumbs(); ?>
  <div class="st-author-head">
    <?php echo get_avatar( $author->ID, 80, '', '', array( 'class' => array( 'st-author-avatar' ) ) ); ?>
    <div><span class="st-meta"><?php esc_html_e( 'Author', 'smarttoolz' ); ?></span><h1 class="st-title"><?php echo esc_html( $author->display_name ); ?></h1><?php if ( ! empty( $author->description ) ) : ?><p><?php echo esc_html( $author->description ); ?></p><?php endif; ?></div>
  </div>
</section>
<?php if ( have_posts() ) : ?><div class="st-grid">
<?php while ( have_posts() ) : the_post(); ?><article <?php post_class( 'st-card st-feature' ); ?>><div class="st-meta"><?php echo esc_html( get_the_date() ); ?> · <?php echo esc_html( smarttoolz_reading_time() ); ?></div><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p><a class="st-button" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read article', 'smarttoolz' ); ?></a></article><?php endwhile; ?>
</div><?php the_posts_pagination( array( 'class' => 'st-pagination' ) ); ?>
<?php else : ?><section class="st-card"><h2><?php esc_html_e( 'No articles found', 'smarttoolz' ); ?></h2></section><?php endif; ?>
<?php get_footer(); ?>
