<?php
/**
 * Archive template.
 *
 * @package SmartToolz
 */
get_header();

?><header class="st-card">
    <h1 class="st-title"><?php the_archive_title(); ?></h1>
    <?php the_archive_description( '<div class="st-meta">', '</div>' ); ?>
</header>
<?php

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();
        ?>
        <article <?php post_class( 'st-card' ); ?>>
            <h2 class="st-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <div class="st-meta"><?php echo esc_html( get_the_date() ); ?></div>
            <div><?php the_excerpt(); ?></div>
        </article>
        <?php
    endwhile;
    the_posts_pagination();
else :
    ?>
    <article class="st-card"><p><?php esc_html_e( 'Nothing found here.', 'smarttoolz' ); ?></p></article>
    <?php
endif;

get_footer();
