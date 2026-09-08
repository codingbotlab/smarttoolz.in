<?php
/** SmartToolz single post template. */
get_header();
if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<article class="st-entry">
<div class="st-card"><div class="st-meta"><?php echo esc_html( get_the_date() ); ?> · <?php the_category( ', ' ); ?></div><h1 class="st-entry-title"><?php the_title(); ?></h1><?php if ( has_post_thumbnail() ) : ?><div style="margin:20px 0;border-radius:16px;overflow:hidden"><?php the_post_thumbnail( 'large' ); ?></div><?php endif; ?><div class="st-entry-content"><?php the_content(); ?></div></div>
</article>
<?php endwhile; endif; get_footer(); ?>
