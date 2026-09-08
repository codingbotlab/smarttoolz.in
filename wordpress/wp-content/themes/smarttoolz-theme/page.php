<?php
/**
 * Page template.
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
            <div><?php the_content(); ?></div>
        </article>
        <?php
    endwhile;
endif;

get_footer();
