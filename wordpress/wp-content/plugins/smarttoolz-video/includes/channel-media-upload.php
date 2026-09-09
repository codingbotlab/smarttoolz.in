<?php
/** Frontend/admin channel avatar and banner uploads. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_channel_upload_image( $file ) {
    if ( ! is_array( $file ) || empty( $file['name'] ) ) {
        return '';
    }
    if ( ! empty( $file['error'] ) ) {
        return '';
    }
    if ( empty( $file['tmp_name'] ) || ! is_uploaded_file( $file['tmp_name'] ) ) {
        return '';
    }

    $check = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'] );
    $allowed = array( 'jpg', 'jpeg', 'png', 'gif', 'webp' );
    if ( empty( $check['ext'] ) || ! in_array( strtolower( $check['ext'] ), $allowed, true ) ) {
        return '';
    }
    if ( empty( $check['type'] ) || 0 !== strpos( $check['type'], 'image/' ) ) {
        return '';
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    $result = wp_handle_upload( $file, array(
        'test_form' => false,
        'mimes'     => array(
            'jpg|jpeg|jpe' => 'image/jpeg',
            'png'          => 'image/png',
            'gif'          => 'image/gif',
            'webp'         => 'image/webp',
        ),
    ) );

    if ( ! empty( $result['error'] ) || empty( $result['url'] ) ) {
        return '';
    }
    return esc_url_raw( $result['url'] );
}

function smarttoolz_video_channel_process_uploaded_images( $input, $files ) {
    if ( ! is_array( $input ) ) { $input = array(); }
    if ( ! is_array( $files ) ) { return $input; }

    if ( isset( $files['stv_channel_avatar'] ) ) {
        $url = smarttoolz_video_channel_upload_image( $files['stv_channel_avatar'] );
        if ( $url ) { $input['stv_channel_avatar'] = $url; }
    }
    if ( isset( $files['stv_channel_banner'] ) ) {
        $url = smarttoolz_video_channel_upload_image( $files['stv_channel_banner'] );
        if ( $url ) { $input['stv_channel_banner'] = $url; }
    }
    if ( isset( $files['stv_admin_channel_avatar'] ) ) {
        $url = smarttoolz_video_channel_upload_image( $files['stv_admin_channel_avatar'] );
        if ( $url ) { $input['stv_admin_channel_avatar'] = $url; }
    }
    if ( isset( $files['stv_admin_channel_banner'] ) ) {
        $url = smarttoolz_video_channel_upload_image( $files['stv_admin_channel_banner'] );
        if ( $url ) { $input['stv_admin_channel_banner'] = $url; }
    }
    return $input;
}

function smarttoolz_video_channel_frontend_uploads() {
    $route = get_query_var( 'smarttoolz_video_route', '' );
    if ( 'settings' !== $route || ! is_user_logged_in() || 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) ) { return; }
    if ( ! isset( $_POST['stv_channel_settings_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['stv_channel_settings_nonce'] ) ), 'stv_channel_settings_save' ) ) { return; }
    if ( empty( smarttoolz_video_channel_settings()['allow_creator_customize'] ) ) { return; }
    $_POST = smarttoolz_video_channel_process_uploaded_images( $_POST, isset( $_FILES ) ? $_FILES : array() );
}
add_action( 'template_redirect', 'smarttoolz_video_channel_frontend_uploads', 0 );

function smarttoolz_video_channel_admin_uploads() {
    if ( empty( $_GET['page'] ) || 'smarttoolz-channel-settings' !== sanitize_key( wp_unslash( $_GET['page'] ) ) ) { return; }
    if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) ) { return; }
    if ( ! current_user_can( 'manage_options' ) ) { return; }
    if ( empty( $_POST['stv_admin_channel_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['stv_admin_channel_nonce'] ) ), 'stv_admin_channel_save' ) ) { return; }
    $_POST = smarttoolz_video_channel_process_uploaded_images( $_POST, isset( $_FILES ) ? $_FILES : array() );
}
add_action( 'admin_init', 'smarttoolz_video_channel_admin_uploads', 1 );

function smarttoolz_video_channel_upload_fields_frontend() {
    if ( 'settings' !== get_query_var( 'smarttoolz_video_route', '' ) || ! is_user_logged_in() ) { return; }
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.querySelector('.stv-channel-settings__form');
        if (!form) return;
        form.setAttribute('enctype', 'multipart/form-data');
        var avatar = form.querySelector('input[name="stv_channel_avatar"]');
        var banner = form.querySelector('input[name="stv_channel_banner"]');
        function addUpload(urlInput, name, label) {
            if (!urlInput || form.querySelector('input[name="' + name + '"]')) return;
            var wrap = document.createElement('div');
            wrap.style.marginTop = '8px';
            var text = document.createElement('small');
            text.textContent = 'Or upload from your device:';
            text.style.display = 'block';
            text.style.marginBottom = '6px';
            text.style.color = '#aaa';
            var file = document.createElement('input');
            file.type = 'file';
            file.name = name;
            file.accept = 'image/jpeg,image/png,image/gif,image/webp';
            file.setAttribute('aria-label', label);
            file.style.width = '100%';
            file.style.boxSizing = 'border-box';
            file.style.padding = '8px';
            file.style.background = '#101010';
            file.style.border = '1px solid #3a3a3a';
            file.style.borderRadius = '9px';
            file.style.color = '#fff';
            wrap.appendChild(text);
            wrap.appendChild(file);
            urlInput.parentNode.appendChild(wrap);
        }
        addUpload(avatar, 'stv_channel_avatar', 'Upload profile picture');
        addUpload(banner, 'stv_channel_banner', 'Upload banner image');
    });
    </script>
    <?php
}
add_action( 'wp_footer', 'smarttoolz_video_channel_upload_fields_frontend', 40 );

function smarttoolz_video_channel_upload_fields_admin() {
    if ( empty( $_GET['page'] ) || 'smarttoolz-channel-settings' !== sanitize_key( wp_unslash( $_GET['page'] ) ) ) { return; }
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.querySelector('form[method="post"] input[name="stv_admin_channel_nonce"]');
        if (!form) return;
        var ownerForm = form.closest('form');
        ownerForm.setAttribute('enctype', 'multipart/form-data');
        function addUpload(name, label) {
            var urlInput = ownerForm.querySelector('input[name="' + name.replace('admin_', '') + '"]');
            if (!urlInput) urlInput = ownerForm.querySelector('input[name="stv_admin_channel_' + name + '"]');
            if (!urlInput || ownerForm.querySelector('input[type="file"][name="stv_admin_channel_' + name + '"]')) return;
            var wrap = document.createElement('p');
            var text = document.createElement('span');
            text.textContent = 'Upload from device: ';
            var file = document.createElement('input');
            file.type = 'file';
            file.name = 'stv_admin_channel_' + name;
            file.accept = 'image/jpeg,image/png,image/gif,image/webp';
            wrap.appendChild(text);
            wrap.appendChild(file);
            urlInput.parentNode.appendChild(wrap);
        }
        addUpload('avatar', 'Profile picture');
        addUpload('banner', 'Banner image');
    });
    </script>
    <?php
}
add_action( 'admin_footer', 'smarttoolz_video_channel_upload_fields_admin', 40 );
