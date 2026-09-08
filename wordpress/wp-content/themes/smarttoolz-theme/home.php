<?php
/**
 * Blog home template.
 *
 * @package SmartToolz
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>
<section class="st-hero">
    <div class="st-hero-grid">
        <div>
            <span class="st-eyebrow"><?php esc_html_e( 'SmartToolz Blog', 'smarttoolz' ); ?></span>
            <h1><?php esc_html_e( 'Useful ideas, guides and technology worth knowing.', 'smarttoolz' ); ?></h1>
            <p><?php esc_html_e( 'A clean publishing space for tutorials, tools, productivity tips and practical technology stories.', 'smarttoolz' ); ?></p>
            <div class="st-actions">
                <a class="st-button" href="#latest"><?php esc_html_e( 'Explore latest posts', 'smarttoolz' ); ?></a>
                <a class="st-button st-secondary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Visit SmartToolz', 'smarttoolz' ); ?></a>
            </div>
        </div>
        <aside class="st-hero-card">
            <strong><?php echo esc_html( wp_count_posts( 'post' )->publish ); ?>+</strong>
            <p><?php esc_html_e( 'published stories and growing', 'smarttoolz' ); ?></p>
            <div class="st-stat-row">
                <div class="st-stat"><b><?php echo esc_html( wp_count_terms( array( 'taxonomy' => 'category', 'hide_empty' => true ) ) ); ?></b><span><?php esc_html_e( 'Topics', 'smarttoolz' ); ?></span></div>
                <div class="st-stat"><b><?php echo esc_html( wp_count_posts( 'page' )->publish ); ?></b><span><?php esc_html_e( 'Pages', 'smarttoolz' ); ?></span></div>
            </div>
        </aside>
    </div>
</section>

<section id="latest" class="st-section">
    <div class="st-section-head">
        <h2><?php esc_html_e( 'Latest articles', 'smarttoolz' ); ?></h2>
        <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"><?php esc_html_e( 'View all', 'smarttoolz' ); ?> →</a>
    </div>
    <?php if ( have_posts() ) : ?>
        <div class="st-grid">
            <?php while ( have_posts() ) : the_post(); ?>
                <article <?php post_class( 'st-card st-feature' ); ?>>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <a class="st-thumb" href="<?php echo esc_url( get_permalink() ); ?>" aria-label="<?php the_title_attribute(); ?>"><?php the_post_thumbnail( 'large' ); ?></a>
                    <?php endif; ?>
                    <div class="st-meta"><?php echo esc_html( get_the_date() ); ?> · <?php echo esc_html( get_the_category()[0]->name ?? 'SmartToolz' ); ?></div>
                    <h2><a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a></h2>
                    <div><?php the_excerpt(); ?></div>
                    <a class="st-button" href="<?php echo esc_url( get_permalink() ); ?>"><?php esc_html_e( 'Read article', 'smarttoolz' ); ?></a>
                </article>
            <?php endwhile; ?>
        </div>
        <nav class="st-pagination" aria-label="<?php esc_attr_e( 'Posts pagination', 'smarttoolz' ); ?>"><?php echo wp_kses_post( paginate_links( array( 'type' => 'list' ) ) ); ?></nav>
    <?php else : ?>
        <div class="st-card"><h2><?php esc_html_e( 'No posts yet', 'smarttoolz' ); ?></h2><p><?php esc_html_e( 'Publish your first article from WordPress and it will appear here.', 'smarttoolz' ); ?></p></div>
    <?php endif; ?>
</section>
<?php get_footer(); ?>
