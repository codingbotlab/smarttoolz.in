<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
get_header();
?>
<main class="page-wrap">
  <div class="container single-grid">
    <article class="single-post page-content">
      <?php while ( have_posts() ) : the_post(); ?>
        <header class="page-header">
          <span class="eyebrow">SmartToolz Journal</span>
          <h1 class="page-title"><?php the_title(); ?></h1>
          <?php if ( has_excerpt() ) : ?><p class="page-intro"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
        </header>
        <?php if ( has_post_thumbnail() ) : ?><figure class="featured page-featured"><?php the_post_thumbnail( 'full', array( 'loading' => 'eager' ) ); ?></figure><?php endif; ?>
        <div class="entry-content">
          <?php the_content(); ?>
          <?php wp_link_pages( array( 'before' => '<nav class="page-links">Pages: ', 'after' => '</nav>' ) ); ?>
        </div>
      <?php endwhile; ?>
    </article>
    <aside class="single-sidebar"><?php get_sidebar(); ?></aside>
  </div>
</main>
<?php get_footer(); ?>
