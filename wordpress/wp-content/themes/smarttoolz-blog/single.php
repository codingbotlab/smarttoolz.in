<?php get_header(); ?>
<main class="single-wrap"><div class="container single-grid"><article class="single-post">
<?php while (have_posts()) : the_post(); ?>
<header class="single-header"><span class="kicker"><?php the_category(' · '); ?></span><h1 class="single-title"><?php the_title(); ?></h1><div class="single-meta"><span><?php echo esc_html(get_the_date('F j, Y')); ?></span><span><?php esc_html_e('By', 'smarttoolz-blog'); ?> <?php the_author_posts_link(); ?></span><span><?php comments_number(__('No comments','smarttoolz-blog'), __('1 comment','smarttoolz-blog'), __('% comments','smarttoolz-blog')); ?></span></div></header>
<?php if (has_post_thumbnail()) : ?><figure class="featured"><?php the_post_thumbnail('full'); ?></figure><?php endif; ?>
<div class="entry-content"><?php the_content(); wp_link_pages(array('before'=>'<div class="page-links">Pages: ','after'=>'</div>')); ?></div>
<?php if (get_the_tags()) : ?><div class="single-tags"><strong><?php esc_html_e('Tags:', 'smarttoolz-blog'); ?></strong> <?php the_tags('', ' ', ''); ?></div><?php endif; ?>
<nav class="post-nav" aria-label="Post navigation"><div><?php previous_post_link('%link', '<small>← Previous</small><strong>%title</strong>'); ?></div><div><?php next_post_link('%link', '<small>Next →</small><strong>%title</strong>'); ?></div></nav>
<?php if (comments_open() || get_comments_number()) comments_template(); ?>
<?php endwhile; ?>
</article><?php get_sidebar(); ?></div></main>
<?php get_footer(); ?>
