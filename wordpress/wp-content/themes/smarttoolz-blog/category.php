<?php
/**
 * SmartToolz Blog — category archive.
 */
if (!defined('ABSPATH')) exit;
get_header();
?>
<main id="primary" class="site-main">
<header class="archive-head">
  <div class="container">
    <span class="kicker"><?php esc_html_e('Archive', 'smarttoolz-blog'); ?></span>
    <h1><?php single_cat_title(); ?></h1>
    <?php $description = category_description(); if ($description) : ?>
      <div class="archive-description"><?php echo wp_kses_post($description); ?></div>
    <?php endif; ?>
  </div>
</header>

<div class="container archive-layout">
  <div class="content-column">
    <?php if (have_posts()) : ?>
      <?php while (have_posts()) : the_post(); ?>
        <article <?php post_class('story'); ?>>
          <?php if (has_post_thumbnail()) : ?>
            <a class="story-thumb" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
              <?php the_post_thumbnail('medium_large', array('loading' => 'lazy')); ?>
            </a>
          <?php endif; ?>
          <div class="story-body">
            <div class="tag"><?php the_category(', '); ?></div>
            <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 24)); ?></p>
            <div class="story-meta">
              <span><?php echo esc_html(get_the_date('F j, Y')); ?></span>
              <span><?php echo esc_html(get_the_author()); ?></span>
            </div>
            <?php the_tags('<div class="post-tags">', ' · ', '</div>'); ?>
          </div>
        </article>
      <?php endwhile; ?>

      <div class="pagination-wrap">
        <?php
        echo wp_kses_post(paginate_links(array(
          'current' => max(1, (int) get_query_var('paged')),
          'total'   => max(1, (int) $GLOBALS['wp_query']->max_num_pages),
          'type'    => 'list',
          'prev_text' => '← Newer',
          'next_text' => 'Older →',
        )));
        ?>
      </div>
    <?php else : ?>
      <div class="empty"><strong><?php esc_html_e('Nothing published in this category yet.', 'smarttoolz-blog'); ?></strong></div>
    <?php endif; ?>
  </div>
  <?php get_sidebar(); ?>
</div>
</main>
<?php get_footer(); ?>
