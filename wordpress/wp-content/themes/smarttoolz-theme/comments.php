<?php
/** SmartToolz standalone comments template. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( post_password_required() ) { return; }

$post_id  = (int) get_the_ID();
$comments = get_comments(
    array(
        'post_id' => $post_id,
        'status'  => 'approve',
        'type'    => 'comment',
        'order'   => 'ASC',
    )
);
?>
<section id="comments" class="st-comments" aria-labelledby="st-comments-title">
    <div class="st-comments-head">
        <h2 id="st-comments-title"><?php esc_html_e( 'Comments', 'smarttoolz' ); ?></h2>
        <p><?php esc_html_e( 'Join the conversation.', 'smarttoolz' ); ?></p>
    </div>

    <?php if ( ! empty( $comments ) ) : ?>
        <ol class="comment-list">
            <?php foreach ( $comments as $comment ) : ?>
                <li id="comment-<?php echo (int) $comment->comment_ID; ?>" class="comment">
                    <article class="comment-body">
                        <div class="comment-author">
                            <?php echo get_avatar( $comment->comment_author_email, 48 ); ?>
                            <div>
                                <strong class="fn"><?php echo esc_html( $comment->comment_author ); ?></strong>
                                <time datetime="<?php echo esc_attr( $comment->comment_date_gmt ); ?>">
                                    <?php echo esc_html( mysql2date( get_option( 'date_format' ), $comment->comment_date ) ); ?>
                                </time>
                            </div>
                        </div>
                        <div class="comment-content">
                            <?php echo wp_kses_post( wpautop( $comment->comment_content ) ); ?>
                        </div>
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
                <p class="comment-form-comment">
                    <label for="comment"><?php esc_html_e( 'Comment', 'smarttoolz' ); ?></label>
                    <textarea id="comment" name="comment" rows="8" required></textarea>
                </p>
                <?php if ( ! is_user_logged_in() ) : ?>
                    <div class="st-comment-fields">
                        <p class="comment-form-author">
                            <label for="author"><?php esc_html_e( 'Name', 'smarttoolz' ); ?> *</label>
                            <input id="author" name="author" type="text" autocomplete="name" required>
                        </p>
                        <p class="comment-form-email">
                            <label for="email"><?php esc_html_e( 'Email', 'smarttoolz' ); ?> *</label>
                            <input id="email" name="email" type="email" autocomplete="email" required>
                        </p>
                    </div>
                    <p class="comment-form-url">
                        <label for="url"><?php esc_html_e( 'Website', 'smarttoolz' ); ?></label>
                        <input id="url" name="url" type="url" autocomplete="url">
                    </p>
                <?php endif; ?>
                <?php wp_nonce_field( 'comment_form', '_wp_unfiltered_html_comment', true, true ); ?>
                <input type="hidden" name="comment_post_ID" value="<?php echo $post_id; ?>">
                <input type="hidden" name="comment_parent" value="0">
                <p class="form-submit">
                    <button type="submit" class="st-button st-comment-submit">
                        <?php esc_html_e( 'Post comment', 'smarttoolz' ); ?>
                    </button>
                </p>
            </form>
        </div>
    <?php elseif ( ! empty( $comments ) ) : ?>
        <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'smarttoolz' ); ?></p>
    <?php endif; ?>
</section>
