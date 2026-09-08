<?php
/**
 * Category archive template.
 *
 * @package SmartToolz
 * @since 2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<section class="st-card st-archive-header">
    <div class="st-meta"><?php esc_html_e( 'Category', 'smarttoolz' ); ?></div>
    <h1 class="st-title"><?php single_cat_title(); ?></h1>
    <?php the_archive_description( '<div class="st-meta">', '</div>' ); ?>
</section>

<?php if ( have_posts() ) : ?>
    <div class="st-post-list">
        <?php while ( have_posts() ) : the_post(); ?>
            <article <?php post_class( 'st-card st-post-card' ); ?>>
                <?php if ( has_post_thumbnail() ) : ?>
                    <a class="st-thumb" href="<?php echo esc_url( get_permalink() ); ?>" aria-label="<?php the_title_attribute(); ?>">
                        <?php the_post_thumbnail( 'large' ); ?>
                    </a>
                <?php endif; ?>
                <div class="st-meta">
                    <?php echo esc_html( get_the_date() ); ?>
                    <?php
                    $categories = get_the_category();
                    if ( ! empty( $categories ) ) {
                        echo ' · ' . esc_html( $categories[0]->name );
                    }
                    ?>
                </div>
                <h2 class="st-title">
                    <a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
                </h2>
                <div class="st-excerpt"><?php the_excerpt(); ?></div>
                <a class="st-button" href="<?php echo esc_url( get_permalink() ); ?>">
                    <?php esc_html_e( 'Read article', 'smarttoolz' ); ?>
                </a>
            </article>
        <?php endwhile; ?>
    </div>

    <nav class="st-pagination" aria-label="<?php esc_attr_e( 'Category pagination', 'smarttoolz' ); ?>">
        <?php
        echo wp_kses_post(
            paginate_links(
                array(
                    'type'      => 'list',
                    'prev_text' => esc_html__( '← Previous', 'smarttoolz' ),
                    'next_text' => esc_html__( 'Next →', 'smarttoolz' ),
                )
            )
        );
        ?>
    </nav>
<?php else : ?>
    <section class="st-card">
        <h2 class="st-title"><?php esc_html_e( 'No posts found', 'smarttoolz' ); ?></h2>
        <p><?php esc_html_e( 'There are no published posts in this category yet.', 'smarttoolz' ); ?></p>
        <a class="st-button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'smarttoolz' ); ?></a>
    </section>
<?php endif; ?>

<?php get_footer(); ?>
