<?php
/**
 * SmartToolz Video frontend editor.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_handle_frontend_edit() {
    if ( ! is_user_logged_in() || empty( $_POST['stv_edit_video_action'] ) ) { return; }
    $video_id = isset( $_POST['stv_edit_video_id'] ) ? absint( $_POST['stv_edit_video_id'] ) : 0;
    $video    = $video_id ? get_post( $video_id ) : null;
    if ( ! $video || 'st_video' !== $video->post_type || (int) $video->post_author !== get_current_user_id() ) { return; }
    $nonce = isset( $_POST['stv_edit_video_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['stv_edit_video_nonce'] ) ) : '';
    if ( ! wp_verify_nonce( $nonce, 'stv_edit_video_' . $video_id ) ) { return; }

    $title       = isset( $_POST['stv_edit_title'] ) ? sanitize_text_field( wp_unslash( $_POST['stv_edit_title'] ) ) : '';
    $description = isset( $_POST['stv_edit_description'] ) ? wp_kses_post( wp_unslash( $_POST['stv_edit_description'] ) ) : '';
    $source_url  = isset( $_POST['stv_edit_url'] ) ? esc_url_raw( wp_unslash( $_POST['stv_edit_url'] ) ) : '';
    $duration    = isset( $_POST['stv_edit_duration'] ) ? sanitize_text_field( wp_unslash( $_POST['stv_edit_duration'] ) ) : '';
    $category    = isset( $_POST['stv_edit_category'] ) ? absint( $_POST['stv_edit_category'] ) : 0;
    $thumb_data  = isset( $_POST['stv_edit_thumbnail'] ) ? wp_unslash( $_POST['stv_edit_thumbnail'] ) : '';

    if ( '' === $title ) { $title = $video->post_title; }
    wp_update_post( array(
        'ID'           => $video_id,
        'post_title'   => $title,
        'post_content' => $description,
    ) );

    if ( '' !== $duration ) { update_post_meta( $video_id, '_st_video_duration', $duration ); }
    if ( '' !== $source_url ) { update_post_meta( $video_id, '_st_video_source', $source_url ); }

    if ( ! empty( $_FILES['stv_edit_video_file']['name'] ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
        $upload = wp_handle_upload( $_FILES['stv_edit_video_file'], array(
            'test_form' => false,
            'mimes' => array( 'mp4' => 'video/mp4', 'webm' => 'video/webm', 'ogg' => 'video/ogg' ),
        ) );
        if ( empty( $upload['error'] ) && ! empty( $upload['url'] ) ) {
            update_post_meta( $video_id, '_st_video_source', esc_url_raw( $upload['url'] ) );
        }
    }

    $thumb_attached = false;
    if ( ! empty( $_FILES['stv_edit_thumbnail_file']['name'] ) && function_exists( 'smarttoolz_video_attach_thumbnail_file' ) ) {
        $thumb_attached = (bool) smarttoolz_video_attach_thumbnail_file( $video_id, $_FILES['stv_edit_thumbnail_file'] );
    }
    if ( ! $thumb_attached && is_string( $thumb_data ) && '' !== $thumb_data && function_exists( 'smarttoolz_video_attach_thumbnail_data' ) ) {
        $thumb_attached = (bool) smarttoolz_video_attach_thumbnail_data( $video_id, $thumb_data );
    }

    if ( taxonomy_exists( 'st_video_category' ) ) {
        wp_set_post_terms( $video_id, $category ? array( $category ) : array(), 'st_video_category', false );
    }

    $redirect = remove_query_arg( 'stv_edit', get_permalink( $video_id ) );
    $redirect = add_query_arg( 'stv_updated', '1', $redirect );
    wp_safe_redirect( $redirect );
    exit;
}
add_action( 'template_redirect', 'smarttoolz_video_handle_frontend_edit', 2 );

function smarttoolz_video_edit_shortcode( $atts = array() ) {
    $video_id = isset( $atts['id'] ) ? absint( $atts['id'] ) : 0;
    if ( ! $video_id && isset( $_GET['stv_edit_video'] ) ) { $video_id = absint( $_GET['stv_edit_video'] ); }
    if ( ! $video_id ) { $video_id = get_queried_object_id(); }
    $video = $video_id ? get_post( $video_id ) : null;
    if ( ! $video || 'st_video' !== $video->post_type ) { return '<div class="stv-empty">Video not found.</div>'; }
    if ( ! is_user_logged_in() || (int) $video->post_author !== get_current_user_id() ) {
        return '<div class="stv-login"><h2>Sign in as the video owner</h2><p>Only the creator can edit this video.</p></div>';
    }

    $source      = (string) get_post_meta( $video_id, '_st_video_source', true );
    $duration    = (string) get_post_meta( $video_id, '_st_video_duration', true );
    $thumb       = has_post_thumbnail( $video_id ) ? wp_get_attachment_image_url( get_post_thumbnail_id( $video_id ), 'large' ) : '';
    $terms       = taxonomy_exists( 'st_video_category' ) ? get_terms( array( 'taxonomy' => 'st_video_category', 'hide_empty' => false ) ) : array();
    $selected    = wp_get_post_terms( $video_id, 'st_video_category', array( 'fields' => 'ids' ) );
    $selected_id = ! empty( $selected ) ? (int) $selected[0] : 0;

    ob_start(); ?>
    <div class="stv-edit-page">
      <div class="stv-edit-head">
        <div><span class="stv-eyebrow">Creator Studio</span><h1>Edit video</h1><p>Update your title, description, video, category or thumbnail.</p></div>
        <a class="stv-upload-back" href="<?php echo esc_url( get_permalink( $video_id ) ); ?>">Back to video</a>
      </div>
      <?php if ( isset( $_GET['stv_updated'] ) ) : ?><div class="stv-upload-success"><strong>Changes saved.</strong> Your video is updated.</div><?php endif; ?>
      <form class="stv-edit-form" method="post" enctype="multipart/form-data">
        <?php wp_nonce_field( 'stv_edit_video_' . $video_id, 'stv_edit_video_nonce' ); ?>
        <input type="hidden" name="stv_edit_video_action" value="save">
        <input type="hidden" name="stv_edit_video_id" value="<?php echo esc_attr( $video_id ); ?>">
        <input type="hidden" name="stv_edit_thumbnail" value="" data-stv-edit-thumbnail>
        <div class="stv-edit-grid">
          <section class="stv-edit-card">
            <h2>Details</h2>
            <label class="stv-field"><span>Title</span><input name="stv_edit_title" type="text" maxlength="180" required value="<?php echo esc_attr( $video->post_title ); ?>"></label>
            <label class="stv-field"><span>Description</span><textarea name="stv_edit_description" rows="9"><?php echo esc_textarea( $video->post_content ); ?></textarea></label>
            <label class="stv-field"><span>Category</span><select name="stv_edit_category"><option value="0">No category</option><?php if ( ! is_wp_error( $terms ) ) : foreach ( $terms as $term ) : ?><option value="<?php echo esc_attr( $term->term_id ); ?>" <?php selected( $selected_id, $term->term_id ); ?>><?php echo esc_html( $term->name ); ?></option><?php endforeach; endif; ?></select></label>
            <label class="stv-field"><span>Duration</span><input name="stv_edit_duration" type="text" value="<?php echo esc_attr( $duration ); ?>" placeholder="12:34"></label>
          </section>
          <section class="stv-edit-card">
            <h2>Video</h2>
            <div class="stv-edit-current-video"><?php if ( $source ) : ?><video controls preload="metadata" playsinline src="<?php echo esc_url( $source ); ?>"></video><?php else : ?><div class="stv-no-video">No video source</div><?php endif; ?></div>
            <label class="stv-field"><span>Replace video file</span><input name="stv_edit_video_file" type="file" accept="video/mp4,video/webm,video/ogg"></label>
            <label class="stv-field"><span>Or replace video URL</span><input name="stv_edit_url" type="url" value="<?php echo esc_attr( $source ); ?>" placeholder="https://example.com/video.mp4"></label>
          </section>
        </div>
        <section class="stv-edit-card stv-edit-thumbnail-card">
          <h2>Thumbnail</h2>
          <?php if ( $thumb ) : ?><div class="stv-current-thumb"><img src="<?php echo esc_url( $thumb ); ?>" alt="Current thumbnail"><span>Current thumbnail</span></div><?php endif; ?>
          <div class="stv-thumbnail-generator stv-edit-thumbnail-generator" data-stv-edit-generator data-source="<?php echo esc_attr( $source ); ?>">
            <div class="stv-thumbnail-generator-title">Generate 3 thumbnails from video</div>
            <button type="button" class="stv-publish-button stv-generate-thumbnails" data-stv-edit-generate>Generate 3 thumbnails</button>
            <div class="stv-edit-video-preview" data-stv-edit-video-preview hidden></div>
            <div class="stv-thumbnail-choices" data-stv-edit-choices></div>
          </div>
          <label class="stv-field"><span>Or upload custom thumbnail</span><input name="stv_edit_thumbnail_file" type="file" accept="image/jpeg,image/png,image/webp"></label>
        </section>
        <div class="stv-edit-footer"><a class="stv-upload-back" href="<?php echo esc_url( get_permalink( $video_id ) ); ?>">Cancel</a><button type="submit" class="stv-publish-button">Save changes</button></div>
      </form>
    </div>
    <?php return ob_get_clean();
}
add_shortcode( 'smarttoolz_video_edit', 'smarttoolz_video_edit_shortcode' );
