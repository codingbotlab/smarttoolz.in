<?php
/** SmartToolz single post template. */
get_header();
if ( have_posts() ) : while ( have_posts() ) : the_post();
$share_url   = rawurlencode( get_permalink() );
$share_title = rawurlencode( get_the_title() );
?>
<article class="st-entry">
<?php smarttoolz_breadcrumbs(); ?>
<div class="st-card">
  <div class="st-meta"><?php echo esc_html( get_the_date() ); ?> · <?php echo esc_html( get_the_author() ); ?> · <?php echo esc_html( smarttoolz_reading_time() ); ?></div>
  <h1 class="st-entry-title"><?php the_title(); ?></h1>
  <?php if ( has_excerpt() ) : ?><p class="st-entry-lead"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
  <?php if ( has_post_thumbnail() ) : ?><div class="st-entry-image"><?php the_post_thumbnail( 'large' ); ?></div><?php endif; ?>
  <div class="st-entry-content"><?php the_content(); ?></div>
  <?php $tags = get_the_tags(); if ( $tags ) : ?>
    <div class="st-tags" aria-label="<?php esc_attr_e( 'Tags', 'smarttoolz' ); ?>">
      <?php foreach ( $tags as $tag ) : ?><a class="st-tag" href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>">#<?php echo esc_html( $tag->name ); ?></a><?php endforeach; ?>
    </div>
  <?php endif; ?>
  <div class="st-share">
    <strong><?php esc_html_e( 'Share this article', 'smarttoolz' ); ?></strong>
    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_attr( $share_url ); ?>" target="_blank" rel="noopener noreferrer">Facebook</a>
    <a href="https://twitter.com/intent/tweet?url=<?php echo esc_attr( $share_url ); ?>&text=<?php echo esc_attr( $share_title ); ?>" target="_blank" rel="noopener noreferrer">X</a>
    <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo esc_attr( $share_url ); ?>" target="_blank" rel="noopener noreferrer">LinkedIn</a>
    <button type="button" class="st-copy-link" data-copy-url="<?php echo esc_attr( get_permalink() ); ?>"><?php esc_html_e( 'Copy link', 'smarttoolz' ); ?></button>
  </div>
</div>
<?php if ( comments_open() || get_comments_number() ) : ?>
  <section class="st-card st-comments"><?php comments_template(); ?></section>
<?php endif; ?>
</article>
<?php endwhile; endif; get_footer(); ?>
