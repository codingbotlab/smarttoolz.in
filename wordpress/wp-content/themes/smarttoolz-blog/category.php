<?php
if ( ! defined( 'ABSPATH' ) ) exit;
get_header();
$cat = get_queried_object();
$description = ( is_object($cat) && isset($cat->description) ) ? $cat->description : '';
?>
<main id="primary" class="site-main category-archive">
<header class="archive-head"><div class="container">
<span class="kicker">Archive</span>
<h1><?php echo esc_html( single_cat_title('', false) ); ?></h1>
<?php if ($description) : ?><div class="archive-description"><?php echo wp_kses_post($description); ?></div><?php endif; ?>
</div></header>
<div class="container archive-layout"><div class="content-column">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<article id="post-<?php the_ID(); ?>" <?php post_class('story'); ?>>
<?php if (has_post_thumbnail()) : ?><a class="story-thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium_large', array('loading'=>'lazy')); ?></a><?php endif; ?>
<div class="story-body">
<div class="tag"><?php $cats=get_the_category(); if ($cats && !is_wp_error($cats)) { foreach($cats as $i=>$c) { if($i) echo ', '; echo '<a href="'.esc_url(get_category_link($c->term_id)).'">'.esc_html($c->name).'</a>'; } } ?></div>
<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
<p><?php echo esc_html(wp_trim_words(get_the_excerpt(),24)); ?></p>
<div class="story-meta"><span><?php echo esc_html(get_the_date('F j, Y')); ?></span><span><?php echo esc_html(get_the_author()); ?></span></div>
<?php $tags=get_the_tags(); if($tags && !is_wp_error($tags)) : ?><div class="post-tags"><?php foreach($tags as $i=>$tag){ if($i) echo ' &middot; '; echo '<a href="'.esc_url(get_tag_link($tag->term_id)).'">'.esc_html($tag->name).'</a>'; } ?></div><?php endif; ?>
</div></article>
<?php endwhile; the_posts_pagination(array('mid_size'=>1,'prev_text'=>'Newer','next_text'=>'Older')); else : ?>
<div class="empty"><strong>Nothing published in this category yet.</strong></div>
<?php endif; ?>
</div></div>
</main>
<?php get_footer(); ?>