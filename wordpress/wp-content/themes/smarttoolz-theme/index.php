<?php
/**
 * Main template.
 *
 * @package SmartToolz
 */
get_header();

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();
        ?>
        <article <?php post_class( 'st-card' ); ?>>
            <h1 class="st-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
            <div class="st-meta"><?php echo esc_html( get_the_date() ); ?></div>
            <div><?php the_excerpt(); ?></div>
            <a class="st-button" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'smarttoolz' ); ?></a>
        </article>
        <?php
    endwhile;
    the_posts_pagination();
else :
    ?>
    <section class="st-card">
        <h1 class="st-title"><?php esc_html_e( 'Welcome to SmartToolz', 'smarttoolz' ); ?></h1>
        <p><?php esc_html_e( 'Your website is ready. Add pages and posts from WordPress.', 'smarttoolz' ); ?></p>
    </section>
    <?php
endif;

get_footer();
