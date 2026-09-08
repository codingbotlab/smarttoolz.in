<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
get_header();
$cat = get_queried_object();
$description = ( is_object( $cat ) && ! empty( $cat->description ) ) ? $cat->description : '';
$category_id = ( is_object( $cat ) && isset( $cat->term_id ) ) ? absint( $cat->term_id ) : 0;
$count = ( is_object( $cat ) && isset( $cat->count ) ) ? absint( $cat->count ) : 0;
?>
<main id="primary" class="site-main category-archive">
<header class="archive-head category-hero">
  <div class="container">
    <span class="eyebrow">SmartToolz · Topic</span>
    <h1><?php echo esc_html( single_cat_title( '', false ) ); ?></h1>
    <?php if ( $description ) : ?><div class="archive-description"><?php echo wp_kses_post( $description ); ?></div><?php endif; ?>
    <div class="archive-meta-row">
      <span><?php echo esc_html( sprintf( _n( '%s story', '%s stories', $count, 'smarttoolz-blog' ), number_format_i18n( $count ) ) ); ?></span>
      <?php if ( $category_id ) : ?><a class="archive-pill" href="<?php echo esc_url( get_category_feed_link( $category_id ) ); ?>">RSS feed →</a><?php endif; ?>
    </div>
  </div>
</header>

<div class="container archive-layout">
  <div class="content-column">
    <?php if ( have_posts() ) : ?>
      <div class="archive-stories">
      <?php while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'story-card archive-story' ); ?>>
          <a class="story-media" href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
            <?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); else : ?><span class="story-placeholder"><span><?php echo esc_html( strtoupper( substr( get_the_title(), 0, 1 ) ) ); ?></span></span><?php endif; ?>
          </a>
          <div class="story-body">
            <div class="story-top">
              <span class="category-label"><?php the_category( ', ' ); ?></span>
              <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'M j, Y' ) ); ?></time>
            </div>
            <h2 class="archive-story-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 28 ) ); ?></p>
            <div class="story-bottom"><span>By <?php the_author(); ?></span><span><?php echo esc_html( max( 1, (int) ceil( str_word_count( wp_strip_all_tags( get_the_content() ) ) / 200 ) ) ); ?> min read</span></div>
            <?php $tags = get_the_tags(); if ( $tags && ! is_wp_error( $tags ) ) : ?><div class="post-tags"><?php foreach ( $tags as $tag ) : ?><a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>">#<?php echo esc_html( $tag->name ); ?></a><?php endforeach; ?></div><?php endif; ?>
          </div>
        </article>
      <?php endwhile; ?>
      </div>
      <div class="pagination-wrap"><?php the_posts_pagination( array( 'mid_size' => 1, 'prev_text' => '← Newer', 'next_text' => 'Older →' ) ); ?></div>
    <?php else : ?>
      <div class="empty-state"><strong>Nothing published in this category yet.</strong><p>Publish a post in WordPress and it will appear here automatically.</p></div>
    <?php endif; ?>
  </div>
  <?php get_sidebar(); ?>
</div>
</main>
<?php get_footer(); ?>
