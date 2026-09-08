<?php get_header(); ?>
<main class="page-wrap"><div class="container single-grid"><article class="single-post">
<?php while (have_posts()) : the_post(); ?>
<h1 class="page-title"><?php the_title(); ?></h1>
<?php if (has_post_thumbnail()) : ?><figure class="featured"><?php the_post_thumbnail('full'); ?></figure><?php endif; ?>
<div class="entry-content"><?php the_content(); wp_link_pages(array('before'=>'<div class="page-links">Pages: ','after'=>'</div>')); ?></div>
<?php endwhile; ?>
</article><?php get_sidebar(); ?></div></main>
<?php get_footer(); ?>
