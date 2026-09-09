<?php
/**
 * SmartToolz Video channel image uploads.
 * Adds real multipart file inputs for creator profile/banner images and
 * processes them before the existing channel profile save handler runs.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_channel_upload_image( $file ) {
    if ( ! is_array( $file ) || empty( $file['name'] ) ) { return ''; }
    if ( ! empty( $file['error'] ) && UPLOAD_ERR_NO_FILE !== (int) $file['error'] ) { return ''; }
    if ( empty( $file['tmp_name'] ) || ! file_exists( $file['tmp_name'] ) ) { return ''; }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    $allowed = array(
        'jpg|jpeg|jpe' => 'image/jpeg',
        'png'          => 'image/png',
        'gif'          => 'image/gif',
        'webp'         => 'image/webp',
    );
    $check = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'] );
    if ( empty( $check['type'] ) || 0 !== strpos( $check['type'], 'image/' ) ) {
        $ext = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
        $mime_by_ext = array( 'jpg'=>'image/jpeg', 'jpeg'=>'image/jpeg', 'png'=>'image/png', 'gif'=>'image/gif', 'webp'=>'image/webp' );
        if ( empty( $mime_by_ext[ $ext ] ) ) { return ''; }
    }

    $result = wp_handle_upload( $file, array( 'test_form' => false, 'mimes' => $allowed ) );
    if ( ! empty( $result['error'] ) || empty( $result['url'] ) ) { return ''; }
    return esc_url_raw( $result['url'] );
}

function smarttoolz_video_channel_process_uploaded_images( $input, $files ) {
    if ( ! is_array( $input ) ) { $input = array(); }
    if ( ! is_array( $files ) ) { return $input; }

    $map = array(
        'stv_channel_avatar'       => 'stv_channel_avatar',
        'stv_channel_banner'       => 'stv_channel_banner',
        'stv_admin_channel_avatar' => 'stv_admin_channel_avatar',
        'stv_admin_channel_banner' => 'stv_admin_channel_banner',
    );
    foreach ( $map as $file_key => $post_key ) {
        if ( empty( $files[ $file_key ]['name'] ) ) { continue; }
        $url = smarttoolz_video_channel_upload_image( $files[ $file_key ] );
        if ( $url ) { $input[ $post_key ] = $url; }
    }
    return $input;
}

/** Process uploads before channel.php saves the profile. */
function smarttoolz_video_channel_frontend_uploads() {
    if ( ! is_user_logged_in() || 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) ) { return; }
    if ( empty( $_POST['stv_channel_settings_nonce'] ) ) { return; }
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['stv_channel_settings_nonce'] ) ), 'stv_channel_settings_save' ) ) { return; }
    if ( empty( smarttoolz_video_channel_settings()['allow_creator_customize'] ) ) { return; }
    $_POST = smarttoolz_video_channel_process_uploaded_images( $_POST, isset( $_FILES ) ? $_FILES : array() );
}
add_action( 'template_redirect', 'smarttoolz_video_channel_frontend_uploads', 0 );

function smarttoolz_video_channel_admin_uploads() {
    if ( empty( $_GET['page'] ) || 'smarttoolz-channel-settings' !== sanitize_key( wp_unslash( $_GET['page'] ) ) ) { return; }
    if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) || ! current_user_can( 'manage_options' ) ) { return; }
    if ( empty( $_POST['stv_admin_channel_nonce'] ) ) { return; }
    if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['stv_admin_channel_nonce'] ) ), 'stv_admin_channel_save' ) ) { return; }
    $_POST = smarttoolz_video_channel_process_uploaded_images( $_POST, isset( $_FILES ) ? $_FILES : array() );
}
add_action( 'admin_init', 'smarttoolz_video_channel_admin_uploads', 1 );

/**
 * Inject the file controls in the head rather than relying on wp_footer.
 * This also fixes custom themes that omit wp_footer().
 */
function smarttoolz_video_channel_upload_fields_frontend() {
    if ( 'settings' !== get_query_var( 'smarttoolz_video_route', '' ) || ! is_user_logged_in() ) { return; }
    ?>
    <script id="stv-channel-media-upload">
    (function () {
        function setup() {
            var form = document.querySelector('.stv-channel-settings__form');
            if (!form) return;
            form.setAttribute('method', 'post');
            form.setAttribute('enctype', 'multipart/form-data');
            form.encoding = 'multipart/form-data';
            var nonce = form.querySelector('input[name="stv_channel_settings_nonce"]');
            if (!nonce) return;

            function addFileInput(urlName, fileName, label) {
                if (form.querySelector('input[type="file"][name="' + fileName + '"]')) return;
                var urlInput = form.querySelector('input[name="' + urlName + '"]');
                if (!urlInput) return;
                var wrap = document.createElement('div');
                wrap.className = 'stv-channel-settings__upload';
                wrap.style.marginTop = '10px';
                var title = document.createElement('div');
                title.textContent = label;
                title.style.fontSize = '12px';
                title.style.fontWeight = '600';
                title.style.marginBottom = '6px';
                title.style.color = '#aaa';
                var input = document.createElement('input');
                input.type = 'file';
                input.name = fileName;
                input.accept = '.jpg,.jpeg,.png,.gif,.webp,image/jpeg,image/png,image/gif,image/webp';
                input.style.width = '100%';
                input.style.boxSizing = 'border-box';
                input.style.padding = '10px';
                input.style.background = '#101010';
                input.style.border = '1px solid #3a3a3a';
                input.style.borderRadius = '9px';
                input.style.color = '#fff';
                wrap.appendChild(title);
                wrap.appendChild(input);
                urlInput.parentNode.appendChild(wrap);
            }
            addFileInput('stv_channel_avatar', 'stv_channel_avatar', 'Upload profile picture from device');
            addFileInput('stv_channel_banner', 'stv_channel_banner', 'Upload banner image from device');
        }
        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', setup);
        else setup();
        window.addEventListener('load', setup);
    }());
    </script>
    <?php
}
add_action( 'wp_head', 'smarttoolz_video_channel_upload_fields_frontend', 40 );

function smarttoolz_video_channel_upload_fields_admin() {
    if ( empty( $_GET['page'] ) || 'smarttoolz-channel-settings' !== sanitize_key( wp_unslash( $_GET['page'] ) ) ) { return; }
    ?>
    <script id="stv-admin-channel-media-upload">
    (function () {
        function setup() {
            var nonce = document.querySelector('form input[name="stv_admin_channel_nonce"]');
            if (!nonce) return;
            var form = nonce.closest('form');
            if (!form) return;
            form.setAttribute('enctype', 'multipart/form-data');
            form.encoding = 'multipart/form-data';
            function addFileInput(urlName, fileName, label) {
                if (form.querySelector('input[type="file"][name="' + fileName + '"]')) return;
                var urlInput = form.querySelector('input[name="' + urlName + '"]');
                if (!urlInput) return;
                var wrap = document.createElement('p');
                var text = document.createElement('strong');
                text.textContent = label + ': ';
                var input = document.createElement('input');
                input.type = 'file';
                input.name = fileName;
                input.accept = '.jpg,.jpeg,.png,.gif,.webp,image/jpeg,image/png,image/gif,image/webp';
                wrap.appendChild(text);
                wrap.appendChild(input);
                urlInput.parentNode.appendChild(wrap);
            }
            addFileInput('stv_admin_channel_avatar', 'stv_admin_channel_avatar', 'Upload profile picture');
            addFileInput('stv_admin_channel_banner', 'stv_admin_channel_banner', 'Upload banner image');
        }
        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', setup);
        else setup();
        window.addEventListener('load', setup);
    }());
    </script>
    <?php
}
add_action( 'admin_head', 'smarttoolz_video_channel_upload_fields_admin', 40 );
