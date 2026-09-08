<?php
/**
 * SmartToolz standalone comments template.
 *
 * Intentionally avoids comment_form() and custom theme callbacks so the
 * comments area remains independent from optional plugins/theme extensions.
 *
 * @package SmartToolz
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( post_password_required() ) {
    return;
}

$comments_count = (int) get_comments_number();
?>
<section id="comments" class="st-comments" aria-labelledby="st-comments-title">
    <div class="st-comments-head">
        <h2 id="st-comments-title"><?php esc_html_e( 'Comments', 'smarttoolz' ); ?></h2>
        <p><?php esc_html_e( 'Join the conversation.', 'smarttoolz' ); ?></p>
    </div>

    <?php if ( $comments_count > 0 ) : ?>
        <ol class="comment-list">
            <?php
            $comment_items = get_comments(
                array(
                    'post_id' => get_the_ID(),
                    'status'  => 'approve',
                    'order'   => 'ASC',
                    'type'    => 'comment',
                )
            );

            foreach ( $comment_items as $comment ) :
                $author_name = get_comment_author( $comment );
                $author_link = get_comment_author_link( $comment );
                ?>
                <li id="comment-<?php echo esc_attr( $comment->comment_ID ); ?>" class="comment">
                    <article class="comment-body">
                        <div class="comment-author vcard">
                            <?php echo get_avatar( $comment, 48 ); ?>
                            <div>
                                <b class="fn"><?php echo wp_kses_post( $author_link ? $author_link : $author_name ); ?></b>
                                <time datetime="<?php echo esc_attr( get_comment_time( 'c', false, true, $comment ) ); ?>">
                                    <?php echo esc_html( get_comment_date( '', $comment ) ); ?>
                                </time>
                            </div>
                        </div>
                        <div class="comment-content">
                            <?php echo wp_kses_post( wpautop( get_comment_text( $comment ) ) ); ?>
                        </div>
                    </article>
                </li>
            <?php endforeach; ?>
        </ol>
    <?php endif; ?>

    <?php if ( comments_open() ) : ?>
        <div class="st-comment-form-wrap">
            <h3><?php esc_html_e( 'Leave a comment', 'smarttoolz' ); ?></h3>
            <p class="st-comment-notes"><?php esc_html_e( 'Your email address will not be published.', 'smarttoolz' ); ?></p>

            <form action="<?php echo esc_url( site_url( '/wp-comments-post.php' ) ); ?>" method="post" class="st-comment-form">
                <p class="comment-form-comment">
                    <label for="comment"><?php esc_html_e( 'Comment', 'smarttoolz' ); ?></label>
                    <textarea id="comment" name="comment" rows="6" required></textarea>
                </p>

                <?php if ( ! is_user_logged_in() ) : ?>
                    <p class="comment-form-author">
                        <label for="author"><?php esc_html_e( 'Name', 'smarttoolz' ); ?> <span aria-hidden="true">*</span></label>
                        <input id="author" name="author" type="text" autocomplete="name" required>
                    </p>
                    <p class="comment-form-email">
                        <label for="email"><?php esc_html_e( 'Email', 'smarttoolz' ); ?> <span aria-hidden="true">*</span></label>
                        <input id="email" name="email" type="email" autocomplete="email" required>
                    </p>
                    <p class="comment-form-url">
                        <label for="url"><?php esc_html_e( 'Website', 'smarttoolz' ); ?></label>
                        <input id="url" name="url" type="url" autocomplete="url">
                    </p>
                <?php endif; ?>

                <?php wp_nonce_field( 'comment-post', '_wp_unfiltered_html_comment' ); ?>
                <input type="hidden" name="comment_post_ID" value="<?php echo esc_attr( get_the_ID() ); ?>">
                <input type="hidden" name="comment_parent" value="0">

                <p class="form-submit">
                    <button type="submit" class="st-button st-comment-submit"><?php esc_html_e( 'Post comment', 'smarttoolz' ); ?></button>
                </p>
            </form>
        </div>
    <?php elseif ( $comments_count > 0 ) : ?>
        <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'smarttoolz' ); ?></p>
    <?php endif; ?>
</section>
