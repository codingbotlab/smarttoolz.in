<?php
/** SmartToolz single post template. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();

if ( have_posts() ) :
    while ( have_posts() ) :
        the_post();
        $post_id        = (int) get_the_ID();
        $share_url      = rawurlencode( get_permalink() );
        $share_title    = rawurlencode( get_the_title() );
        $thumbnail_id   = get_post_thumbnail_id();
        $thumbnail_file = $thumbnail_id ? get_attached_file( $thumbnail_id ) : '';
        $thumbnail_url  = $thumbnail_id ? wp_get_attachment_image_url( $thumbnail_id, 'large' ) : '';
        ?>
        <article class="st-entry">
            <?php if ( function_exists( 'smarttoolz_breadcrumbs' ) ) { smarttoolz_breadcrumbs(); } ?>

            <div class="st-card st-article-card">
                <div class="st-meta">
                    <?php echo esc_html( get_the_date() ); ?> ·
                    <?php echo esc_html( get_the_author() ); ?> ·
                    <?php echo esc_html( function_exists( 'smarttoolz_reading_time' ) ? smarttoolz_reading_time() : '1 min read' ); ?>
                </div>
                <h1 class="st-entry-title"><?php the_title(); ?></h1>
                <?php if ( has_excerpt() ) : ?><p class="st-entry-lead"><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>

                <?php if ( $thumbnail_url && $thumbnail_file && is_file( $thumbnail_file ) ) : ?>
                    <figure class="st-entry-image">
                        <img src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php the_title_attribute(); ?>" loading="eager" decoding="async">
                    </figure>
                <?php endif; ?>

                <div class="st-entry-content"><?php the_content(); ?></div>
                <?php wp_link_pages( array( 'before' => '<nav class="st-page-links"><strong>' . esc_html__( 'Pages:', 'smarttoolz' ) . '</strong>', 'after' => '</nav>' ) ); ?>

                <?php $tags = get_the_tags(); if ( $tags ) : ?>
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
                    <?php if ( get_the_author_meta( 'description' ) ) : ?><p><?php echo esc_html( get_the_author_meta( 'description' ) ); ?></p><?php endif; ?>
                    <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php esc_html_e( 'More articles', 'smarttoolz' ); ?> →</a>
                </div>
            </aside>

            <?php
            $category_ids = wp_get_post_categories( $post_id );
            if ( ! empty( $category_ids ) ) :
                $related = new WP_Query( array(
                    'post_type'           => 'post',
                    'post_status'         => 'publish',
                    'posts_per_page'      => 3,
                    'post__not_in'        => array( $post_id ),
                    'category__in'        => $category_ids,
                    'ignore_sticky_posts' => true,
                    'no_found_rows'       => true,
                ) );
                if ( $related->have_posts() ) : ?>
                    <section class="st-section st-related">
                        <div class="st-section-head"><h2><?php esc_html_e( 'Related articles', 'smarttoolz' ); ?></h2></div>
                        <div class="st-grid">
                            <?php while ( $related->have_posts() ) : $related->the_post(); ?>
                                <article class="st-card st-feature">
                                    <?php if ( has_post_thumbnail() ) : ?><a class="st-thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium_large' ); ?></a><?php endif; ?>
                                    <div class="st-meta"><?php echo esc_html( get_the_date() ); ?></div>
                                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                    <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>
                                    <a class="st-button" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read article', 'smarttoolz' ); ?></a>
                                </article>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </div>
                    </section>
                <?php endif;
            endif;
            ?>

            <?php if ( comments_open() || get_comments_number() ) : ?>
                <?php
                $comments = get_comments( array(
                    'post_id' => $post_id,
                    'status'  => 'approve',
                    'type'    => 'comment',
                    'order'   => 'ASC',
                ) );
                ?>
                <section class="st-section st-comments-section" aria-labelledby="st-comments-title">
                    <div class="st-section-head">
                        <h2 id="st-comments-title"><?php esc_html_e( 'Comments', 'smarttoolz' ); ?></h2>
                        <p class="st-meta"><?php esc_html_e( 'Join the conversation.', 'smarttoolz' ); ?></p>
                    </div>

                    <?php if ( ! empty( $comments ) ) : ?>
                        <ol class="st-comment-list">
                            <?php foreach ( $comments as $comment ) : ?>
                                <li class="st-comment-item" id="comment-<?php echo (int) $comment->comment_ID; ?>">
                                    <article class="st-comment-card">
                                        <div class="st-comment-author">
                                            <?php echo get_avatar( $comment->comment_author_email, 48 ); ?>
                                            <div>
                                                <strong><?php echo esc_html( $comment->comment_author ); ?></strong>
                                                <time datetime="<?php echo esc_attr( $comment->comment_date_gmt ); ?>"><?php echo esc_html( mysql2date( get_option( 'date_format' ), $comment->comment_date ) ); ?></time>
                                            </div>
                                        </div>
                                        <div class="st-comment-content"><?php echo wp_kses_post( wpautop( $comment->comment_content ) ); ?></div>
                                    </article>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    <?php else : ?>
                        <p class="st-no-comments"><?php esc_html_e( 'No comments yet. Be the first to share your thoughts.', 'smarttoolz' ); ?></p>
                    <?php endif; ?>

                    <?php if ( comments_open() ) : ?>
                        <div class="st-comment-form-wrap">
                            <h3><?php esc_html_e( 'Leave a comment', 'smarttoolz' ); ?></h3>
                            <p class="st-comment-notes"><?php esc_html_e( 'Your email address will not be published.', 'smarttoolz' ); ?></p>
                            <form action="<?php echo esc_url( site_url( '/wp-comments-post.php' ) ); ?>" method="post" class="st-comment-form">
                                <p>
                                    <label for="st-comment"><?php esc_html_e( 'Comment', 'smarttoolz' ); ?></label>
                                    <textarea id="st-comment" name="comment" rows="6" required></textarea>
                                </p>
                                <?php if ( ! is_user_logged_in() ) : ?>
                                    <div class="st-comment-fields">
                                        <p><label for="st-author"><?php esc_html_e( 'Name', 'smarttoolz' ); ?> *</label><input id="st-author" name="author" type="text" autocomplete="name" required></p>
                                        <p><label for="st-email"><?php esc_html_e( 'Email', 'smarttoolz' ); ?> *</label><input id="st-email" name="email" type="email" autocomplete="email" required></p>
                                    </div>
                                    <p><label for="st-url"><?php esc_html_e( 'Website', 'smarttoolz' ); ?></label><input id="st-url" name="url" type="url" autocomplete="url"></p>
                                <?php endif; ?>
                                <input type="hidden" name="comment_post_ID" value="<?php echo $post_id; ?>">
                                <input type="hidden" name="comment_parent" value="0">
                                <p class="form-submit"><button type="submit" class="st-button st-comment-submit"><?php esc_html_e( 'Post comment', 'smarttoolz' ); ?></button></p>
                            </form>
                        </div>
                    <?php elseif ( ! empty( $comments ) ) : ?>
                        <p class="st-no-comments"><?php esc_html_e( 'Comments are closed.', 'smarttoolz' ); ?></p>
                    <?php endif; ?>
                </section>
            <?php endif; ?>
        </article>
        <?php
    endwhile;
endif;

get_footer();
