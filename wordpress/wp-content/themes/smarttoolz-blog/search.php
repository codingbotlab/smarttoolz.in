<?php get_header(); ?>
<main id="primary" class="site-main">
<header class="search-head"><div class="container"><span class="kicker"><?php esc_html_e('Search', 'smarttoolz-blog'); ?></span><h1><?php printf(esc_html__('Results for: %s', 'smarttoolz-blog'), esc_html(get_search_query())); ?></h1></div></header>
<div class="container archive-layout"><div class="content-column">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<article <?php post_class('story'); ?>>
<?php if (has_post_thumbnail()) : ?><a class="story-thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium_large'); ?></a><?php endif; ?>
<div class="story-body"><div class="tag"><?php the_category(', '); ?></div><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2><p><?php echo esc_html(wp_trim_words(get_the_excerpt(),24)); ?></p><div class="story-meta"><span><?php echo esc_html(get_the_date('F j, Y')); ?></span><span><?php echo esc_html(get_the_author()); ?></span></div></div>
</article>
<?php endwhile; the_posts_pagination(array('mid_size'=>1,'prev_text'=>'← Newer','next_text'=>'Older →','class'=>'pagination')); else: ?><div class="empty"><p><?php esc_html_e('No results found.', 'smarttoolz-blog'); ?></p><?php get_search_form(); ?></div><?php endif; ?>
</div><?php get_sidebar(); ?></div></main>
<?php get_footer(); ?>
