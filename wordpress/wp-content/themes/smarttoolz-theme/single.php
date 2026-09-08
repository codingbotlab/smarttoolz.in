<?php
/**
 * Single post template.
 *
 * @package SmartToolz
 */
get_header();

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();
        ?>
        <article <?php post_class( 'st-card' ); ?>>
            <h1 class="st-title"><?php the_title(); ?></h1>
            <div class="st-meta"><?php echo esc_html( get_the_date() ); ?></div>
            <?php if ( has_post_thumbnail() ) : ?>
                <div><?php the_post_thumbnail( 'large' ); ?></div>
            <?php endif; ?>
            <div><?php the_content(); ?></div>
        </article>
        <?php
    endwhile;
endif;

get_footer();
