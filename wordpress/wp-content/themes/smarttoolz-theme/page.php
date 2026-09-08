<?php
/** SmartToolz page template. */
get_header();
?>
<article class="st-entry">
<div class="st-card"><div class="st-meta"><?php echo esc_html( get_the_date() ); ?></div><h1 class="st-entry-title"><?php the_title(); ?></h1><div class="st-entry-content"><?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?></div></div>
</article>
<?php get_footer(); ?>
