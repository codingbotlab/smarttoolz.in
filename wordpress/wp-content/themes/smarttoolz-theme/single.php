<?php
/** SmartToolz single post template. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();
        $share_url      = rawurlencode( get_permalink() );
        $share_title    = rawurlencode( get_the_title() );
        $thumbnail_id   = get_post_thumbnail_id();
        $thumbnail_file = $thumbnail_id ? get_attached_file( $thumbnail_id ) : '';
        $thumbnail_url  = $thumbnail_id ? wp_get_attachment_image_url( $thumbnail_id, 'large' ) : '';
        ?>
        <article class="st-entry">
            <?php if ( function_exists( 'smarttoolz_breadcrumbs' ) ) { smarttoolz_breadcrumbs(); } ?>

            <div class="st-card">
                <div class="st-meta">
                    <?php echo esc_html( get_the_date() ); ?> ·
                    <?php echo esc_html( get_the_author() ); ?> ·
                    <?php echo esc_html( function_exists( 'smarttoolz_reading_time' ) ? smarttoolz_reading_time() : '1 min read' ); ?>
                </div>
                <h1 class="st-entry-title"><?php the_title(); ?></h1>

                <?php if ( has_excerpt() ) : ?>
                    <p class="st-entry-lead"><?php echo esc_html( get_the_excerpt() ); ?></p>
                <?php endif; ?>

                <?php if ( $thumbnail_url && $thumbnail_file && is_file( $thumbnail_file ) ) : ?>
                    <div class="st-entry-image">
                        <img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php the_title_attribute(); ?>" loading="eager">
                    </div>
                <?php endif; ?>

                <div class="st-entry-content"><?php the_content(); ?></div>
                <?php wp_link_pages( array( 'before' => '<nav class="st-page-links"><strong>' . esc_html__( 'Pages:', 'smarttoolz' ) . '</strong>', 'after' => '</nav>' ) ); ?>

                <?php $tags = get_the_tags(); ?>
                <?php if ( $tags ) : ?>
                    <div class="st-tags" aria-label="<?php esc_attr_e( 'Tags', 'smarttoolz' ); ?>">
                        <?php foreach ( $tags as $tag ) : ?>
                            <a class="st-tag" href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>">#<?php echo esc_html( $tag->name ); ?></a>
                        <?php endforeach; ?>
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

            <aside class="st-author-card st-card">
                <?php echo get_avatar( get_the_author_meta( 'ID' ), 72 ); ?>
                <div>
                    <div class="st-meta"><?php esc_html_e( 'Written by', 'smarttoolz' ); ?></div>
                    <h2><?php the_author(); ?></h2>
                    <?php if ( get_the_author_meta( 'description' ) ) : ?>
                        <p><?php echo esc_html( get_the_author_meta( 'description' ) ); ?></p>
                    <?php endif; ?>
                    <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php esc_html_e( 'More articles', 'smarttoolz' ); ?> →</a>
                </div>
            </aside>

            <?php
            $category_ids = wp_get_post_categories( get_the_ID() );
            $related = false;
            if ( ! empty( $category_ids ) ) {
                $related = new WP_Query(
                    array(
                        'post_type'           => 'post',
                        'post_status'         => 'publish',
                        'posts_per_page'      => 3,
                        'post__not_in'        => array( get_the_ID() ),
                        'category__in'        => $category_ids,
                        'ignore_sticky_posts' => true,
                        'no_found_rows'       => true,
                    )
                );
            }
            ?>
            <?php if ( $related && $related->have_posts() ) : ?>
                <section class="st-section st-related">
                    <div class="st-section-head"><h2><?php esc_html_e( 'Related articles', 'smarttoolz' ); ?></h2></div>
                    <div class="st-grid">
                        <?php while ( $related->have_posts() ) : $related->the_post(); ?>
                            <article class="st-card st-feature">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <a class="st-thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium_large' ); ?></a>
                                <?php endif; ?>
                                <div class="st-meta"><?php echo esc_html( get_the_date() ); ?></div>
                                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>
                                <a class="st-button" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read article', 'smarttoolz' ); ?></a>
                            </article>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                </section>
            <?php endif; ?>
        </article>
        <?php
    endwhile;
endif;

get_footer();
