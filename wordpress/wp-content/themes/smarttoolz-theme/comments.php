<?php
/** SmartToolz native comments template. */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( post_password_required() ) {
    return;
}
?>
<section id="comments" class="st-card st-comments">
    <?php if ( have_comments() ) : ?>
        <h2 class="st-comments-title">
            <?php
            $count = get_comments_number();
            echo esc_html(
                sprintf(
                    _n( '%s Comment', '%s Comments', $count, 'smarttoolz' ),
                    number_format_i18n( $count )
                )
            );
            ?>
        </h2>

        <ol class="comment-list">
            <?php
            wp_list_comments(
                array(
                    'style'      => 'ol',
                    'short_ping' => true,
                    'avatar_size'=> 48,
                )
            );
            ?>
        </ol>

        <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
            <nav class="st-comment-navigation" aria-label="<?php esc_attr_e( 'Comments navigation', 'smarttoolz' ); ?>">
                <div class="nav-previous"><?php previous_comments_link( esc_html__( '← Older comments', 'smarttoolz' ) ); ?></div>
                <div class="nav-next"><?php next_comments_link( esc_html__( 'Newer comments →', 'smarttoolz' ) ); ?></div>
            </nav>
        <?php endif; ?>
    <?php endif; ?>

    <?php
    if ( comments_open() ) {
        comment_form(
            array(
                'class_form'      => 'st-comment-form',
                'class_submit'    => 'st-comment-submit',
                'title_reply'     => esc_html__( 'Leave a comment', 'smarttoolz' ),
                'label_submit'    => esc_html__( 'Post comment', 'smarttoolz' ),
                'comment_notes_before' => '<p class="st-comment-notes">' . esc_html__( 'Your email address will not be published.', 'smarttoolz' ) . '</p>',
            )
        );
    elseif ( get_comments_number() ) :
        echo '<p class="no-comments">' . esc_html__( 'Comments are closed.', 'smarttoolz' ) . '</p>';
    endif;
    ?>
</section>
