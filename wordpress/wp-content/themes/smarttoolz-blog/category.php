<?php
/**
 * SmartToolz Blog category archive.
 * Keep this template deliberately defensive so category pages cannot fail
 * because of optional engagement/customizer code.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main category-archive">
    <header class="archive-head">
        <div class="container">
            <span class="kicker"><?php esc_html_e( 'Archive', 'smarttoolz-blog' ); ?></span>
            <h1><?php single_cat_title(); ?></h1>
            <?php if ( category_description() ) : ?>
                <div class="archive-description">
                    <?php echo wp_kses_post( category_description() ); ?>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <div class="container archive-layout">
        <div class="content-column">
            <?php if ( have_posts() ) : ?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'story' ); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <a class="story-thumb" href="<?php echo esc_url( get_permalink() ); ?>">
                                <?php the_post_thumbnail( 'medium_large', array( 'loading' => 'lazy' ) ); ?>
                            </a>
                        <?php endif; ?>

                        <div class="story-body">
                            <div class="tag">
                                <?php the_category( ', ' ); ?>
                            </div>

                            <h2>
                                <a href="<?php echo esc_url( get_permalink() ); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>

                            <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>

                            <div class="story-meta">
                                <span><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></span>
                                <span><?php echo esc_html( get_the_author() ); ?></span>
                            </div>

                            <?php if ( get_the_tags() ) : ?>
                                <?php the_tags( '<div class="post-tags">', ' · ', '</div>' ); ?>
                            <?php endif; ?>
                        </div>
                    </article>
                <?php endwhile; ?>

                <?php the_posts_pagination( array(
                    'mid_size'  => 1,
                    'prev_text' => esc_html__( '← Newer', 'smarttoolz-blog' ),
                    'next_text' => esc_html__( 'Older →', 'smarttoolz-blog' ),
                ) ); ?>

            <?php else : ?>
                <div class="empty">
                    <strong><?php esc_html_e( 'Nothing published in this category yet.', 'smarttoolz-blog' ); ?></strong>
                </div>
            <?php endif; ?>
        </div>

        <?php get_sidebar(); ?>
    </div>
</main>

<?php get_footer(); ?>