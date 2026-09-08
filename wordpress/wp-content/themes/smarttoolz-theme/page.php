<?php
/** SmartToolz page template. */
get_header();

$video_managed = (bool) get_post_meta( get_the_ID(), '_smarttoolz_video_managed', true );
?>
<article class="st-entry<?php echo $video_managed ? ' st-video-managed-page' : ''; ?>">
<?php if ( $video_managed ) : ?>
    <div class="st-entry-content st-video-page-content">
        <?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?>
    </div>
<?php else : ?>
    <div class="st-card">
        <div class="st-meta"><?php echo esc_html( get_the_date() ); ?></div>
        <h1 class="st-entry-title"><?php the_title(); ?></h1>
        <div class="st-entry-content">
            <?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?>
        </div>
    </div>
<?php endif; ?>
</article>
<?php get_footer(); ?>
