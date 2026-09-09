<?php
/** SmartToolz Video public channel pages and creator channel customization. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_channel_settings_defaults() {
    return array(
        'channels_enabled'        => 1,
        'allow_creator_customize' => 1,
        'show_subscriber_count'   => 1,
        'show_video_count'        => 1,
        'default_banner_url'      => '',
        'default_avatar_url'      => '',
        'default_description'     => '',
        'default_links'           => array(),
    );
}

function smarttoolz_video_channel_settings() {
    return wp_parse_args( (array) get_option( 'smarttoolz_video_channel_settings', array() ), smarttoolz_video_channel_settings_defaults() );
}

function smarttoolz_video_channel_settings_sanitize( $input ) {
    $input = is_array( $input ) ? $input : array();
    $out = array();
    $out['channels_enabled']        = empty( $input['channels_enabled'] ) ? 0 : 1;
    $out['allow_creator_customize'] = empty( $input['allow_creator_customize'] ) ? 0 : 1;
    $out['show_subscriber_count']   = empty( $input['show_subscriber_count'] ) ? 0 : 1;
    $out['show_video_count']        = empty( $input['show_video_count'] ) ? 0 : 1;
    $out['default_banner_url']      = isset( $input['default_banner_url'] ) ? esc_url_raw( $input['default_banner_url'] ) : '';
    $out['default_avatar_url']      = isset( $input['default_avatar_url'] ) ? esc_url_raw( $input['default_avatar_url'] ) : '';
    $out['default_description']     = isset( $input['default_description'] ) ? sanitize_textarea_field( $input['default_description'] ) : '';
    $out['default_links']           = smarttoolz_video_channel_sanitize_links( isset( $input['default_links'] ) ? $input['default_links'] : array() );
    return $out;
}

function smarttoolz_video_channel_sanitize_links( $links ) {
    $out = array();
    if ( ! is_array( $links ) ) { return $out; }
    foreach ( $links as $link ) {
        if ( ! is_array( $link ) ) { continue; }
        $title = isset( $link['title'] ) ? sanitize_text_field( $link['title'] ) : '';
        $url   = isset( $link['url'] ) ? esc_url_raw( $link['url'] ) : '';
        if ( '' !== $title && '' !== $url ) {
            $out[] = array( 'title' => $title, 'url' => $url );
        }
        if ( count( $out ) >= 5 ) { break; }
    }
    return $out;
}
add_action( 'admin_init', 'smarttoolz_video_channel_register_settings' );
function smarttoolz_video_channel_register_settings() {
    register_setting( 'smarttoolz_video_channel_settings_group', 'smarttoolz_video_channel_settings', array( 'sanitize_callback' => 'smarttoolz_video_channel_settings_sanitize' ) );
}
add_action( 'admin_menu', 'smarttoolz_video_channel_admin_menu', 35 );
function smarttoolz_video_channel_admin_menu() {
    add_submenu_page( 'smarttoolz', 'Channel Settings', 'Channel Settings', 'manage_options', 'smarttoolz-channel-settings', 'smarttoolz_video_channel_admin_page' );
}

function smarttoolz_video_channel_admin_page() {
    if ( ! current_user_can( 'manage_options' ) ) { return; }
    $s = smarttoolz_video_channel_settings();
    $notice = '';
    $selected = isset( $_POST['stv_admin_channel_user'] ) ? absint( $_POST['stv_admin_channel_user'] ) : 0;
    if ( isset( $_POST['stv_admin_channel_save'], $_POST['stv_admin_channel_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['stv_admin_channel_nonce'] ) ), 'stv_admin_channel_save' ) ) {
        $selected = absint( $_POST['stv_admin_channel_user'] );
        if ( $selected && get_userdata( $selected ) ) {
            smarttoolz_video_channel_save_user_profile( $selected, $_POST );
            $notice = '<div class="notice notice-success is-dismissible"><p>Creator channel profile saved.</p></div>';
        }
    }
    $profile = $selected ? smarttoolz_video_channel_user_profile( $selected ) : array();
    $users = get_users( array( 'orderby' => 'display_name', 'order' => 'ASC', 'number' => 200 ) );
    ?>
    <div class="wrap">
        <h1>Channel Settings</h1>
        <p>Control public creator channels and edit any creator's channel profile. SmartToolz branding is kept on the administrator side only; the public channel uses the WordPress site's name.</p>
        <form method="post" action="options.php">
            <?php settings_fields( 'smarttoolz_video_channel_settings_group' ); ?>
            <h2>Channel platform</h2>
            <table class="form-table" role="presentation">
                <tr><th scope="row">Creator channels</th><td><label><input type="checkbox" name="smarttoolz_video_channel_settings[channels_enabled]" value="1" <?php checked( $s['channels_enabled'], 1 ); ?>> Enable public channels</label></td></tr>
                <tr><th scope="row">Creator customization</th><td><label><input type="checkbox" name="smarttoolz_video_channel_settings[allow_creator_customize]" value="1" <?php checked( $s['allow_creator_customize'], 1 ); ?>> Allow users to customize their own channel</label></td></tr>
                <tr><th scope="row">Subscriber count</th><td><label><input type="checkbox" name="smarttoolz_video_channel_settings[show_subscriber_count]" value="1" <?php checked( $s['show_subscriber_count'], 1 ); ?>> Show subscriber count</label></td></tr>
                <tr><th scope="row">Video count</th><td><label><input type="checkbox" name="smarttoolz_video_channel_settings[show_video_count]" value="1" <?php checked( $s['show_video_count'], 1 ); ?>> Show published video count</label></td></tr>
                <tr><th scope="row">Default banner URL</th><td><input class="regular-text" type="url" name="smarttoolz_video_channel_settings[default_banner_url]" value="<?php echo esc_attr( $s['default_banner_url'] ); ?>" placeholder="https://example.com/banner.jpg"></td></tr>
                <tr><th scope="row">Default avatar URL</th><td><input class="regular-text" type="url" name="smarttoolz_video_channel_settings[default_avatar_url]" value="<?php echo esc_attr( $s['default_avatar_url'] ); ?>" placeholder="https://example.com/avatar.jpg"></td></tr>
                <tr><th scope="row">Default channel description</th><td><textarea class="large-text" rows="4" name="smarttoolz_video_channel_settings[default_description]" placeholder="Default text for new channels"><?php echo esc_textarea( $s['default_description'] ); ?></textarea></td></tr>
            </table>
            <h3>Default links</h3>
            <?php smarttoolz_video_channel_render_link_fields( $s['default_links'], 'smarttoolz_video_channel_settings[default_links]' ); ?>
            <?php submit_button( 'Save Channel Settings' ); ?>
        </form>

        <hr>
        <h2>Creator Channel Manager</h2>
        <p>Select a user to edit their public channel profile.</p>
        <form method="get" style="margin-bottom:18px;">
            <input type="hidden" name="page" value="smarttoolz-channel-settings">
            <select name="stv_admin_channel_user">
                <option value="0">Select creator</option>
                <?php foreach ( $users as $user ) : ?><option value="<?php echo esc_attr( $user->ID ); ?>" <?php selected( $selected, $user->ID ); ?>><?php echo esc_html( $user->display_name . ' — ' . $user->user_login ); ?></option><?php endforeach; ?>
            </select>
            <?php submit_button( 'Load Channel', 'secondary', 'submit', false ); ?>
        </form>
        <?php if ( $selected && ! empty( $profile ) ) : ?>
            <form method="post" enctype="multipart/form-data" style="max-width:900px;background:#fff;border:1px solid #dcdcde;border-radius:12px;padding:22px;">
                <?php wp_nonce_field( 'stv_admin_channel_save', 'stv_admin_channel_nonce' ); ?>
                <input type="hidden" name="stv_admin_channel_user" value="<?php echo esc_attr( $selected ); ?>">
                <h3 style="margin-top:0;">Public profile</h3>
                <?php smarttoolz_video_channel_render_profile_fields( $profile, 'stv_admin_' ); ?>
                <?php submit_button( 'Save Creator Channel', 'primary', 'stv_admin_channel_save' ); ?>
            </form>
        <?php endif; ?>
    </div>
    <?php
}

function smarttoolz_video_channel_user_profile( $user_id ) {
    $user = get_userdata( $user_id );
    $settings = smarttoolz_video_channel_settings();
    $name = get_user_meta( $user_id, 'smarttoolz_channel_name', true );
    $handle = get_user_meta( $user_id, 'smarttoolz_channel_handle', true );
    $description = get_user_meta( $user_id, 'smarttoolz_channel_description', true );
    $avatar = get_user_meta( $user_id, 'smarttoolz_avatar_url', true );
    $banner = get_user_meta( $user_id, 'smarttoolz_channel_banner_url', true );
    $links = get_user_meta( $user_id, 'smarttoolz_channel_links', true );
    $contact = get_user_meta( $user_id, 'smarttoolz_channel_contact_email', true );
    $watermark = get_user_meta( $user_id, 'smarttoolz_channel_watermark_url', true );
    if ( ! $user ) { return array(); }
    if ( '' === $name ) { $name = $user->display_name ? $user->display_name : $user->user_login; }
    if ( '' === $handle ) { $handle = $user->user_nicename; }
    if ( ! is_array( $links ) ) { $links = array(); }
    return array(
        'name' => $name,
        'handle' => $handle,
        'description' => '' !== $description ? $description : $settings['default_description'],
        'avatar' => '' !== $avatar ? $avatar : $settings['default_avatar_url'],
        'banner' => '' !== $banner ? $banner : $settings['default_banner_url'],
        'links' => $links,
        'contact' => $contact,
        'watermark' => $watermark,
    );
}

function smarttoolz_video_channel_save_user_profile( $user_id, $input ) {
    $user = get_userdata( $user_id );
    if ( ! $user ) { return false; }
    $name = isset( $input['stv_channel_name'] ) ? sanitize_text_field( wp_unslash( $input['stv_channel_name'] ) ) : ( isset( $input['stv_admin_channel_name'] ) ? sanitize_text_field( wp_unslash( $input['stv_admin_channel_name'] ) ) : '' );
    $handle = isset( $input['stv_channel_handle'] ) ? sanitize_user( wp_unslash( $input['stv_channel_handle'] ), true ) : ( isset( $input['stv_admin_channel_handle'] ) ? sanitize_user( wp_unslash( $input['stv_admin_channel_handle'] ), true ) : '' );
    $description = isset( $input['stv_channel_description'] ) ? sanitize_textarea_field( wp_unslash( $input['stv_channel_description'] ) ) : ( isset( $input['stv_admin_channel_description'] ) ? sanitize_textarea_field( wp_unslash( $input['stv_admin_channel_description'] ) ) : '' );
    $avatar = isset( $input['stv_channel_avatar'] ) ? esc_url_raw( wp_unslash( $input['stv_channel_avatar'] ) ) : ( isset( $input['stv_admin_channel_avatar'] ) ? esc_url_raw( wp_unslash( $input['stv_admin_channel_avatar'] ) ) : '' );
    $banner = isset( $input['stv_channel_banner'] ) ? esc_url_raw( wp_unslash( $input['stv_channel_banner'] ) ) : ( isset( $input['stv_admin_channel_banner'] ) ? esc_url_raw( wp_unslash( $input['stv_admin_channel_banner'] ) ) : '' );
    $contact = isset( $input['stv_channel_contact'] ) ? sanitize_email( wp_unslash( $input['stv_channel_contact'] ) ) : ( isset( $input['stv_admin_channel_contact'] ) ? sanitize_email( wp_unslash( $input['stv_admin_channel_contact'] ) ) : '' );
    $watermark = isset( $input['stv_channel_watermark'] ) ? esc_url_raw( wp_unslash( $input['stv_channel_watermark'] ) ) : ( isset( $input['stv_admin_channel_watermark'] ) ? esc_url_raw( wp_unslash( $input['stv_admin_channel_watermark'] ) ) : '' );
    $links_input = isset( $input['stv_channel_links'] ) ? $input['stv_channel_links'] : ( isset( $input['stv_admin_channel_links'] ) ? $input['stv_admin_channel_links'] : array() );
    $links = smarttoolz_video_channel_sanitize_links( wp_unslash( $links_input ) );
    if ( '' === $name ) { $name = $user->display_name ? $user->display_name : $user->user_login; }
    if ( '' === $handle ) { $handle = $user->user_nicename; }
    $handle = ltrim( $handle, '@' );
    update_user_meta( $user_id, 'smarttoolz_channel_name', $name );
    update_user_meta( $user_id, 'smarttoolz_channel_handle', $handle );
    update_user_meta( $user_id, 'smarttoolz_channel_description', $description );
    update_user_meta( $user_id, 'smarttoolz_avatar_url', $avatar );
    update_user_meta( $user_id, 'smarttoolz_channel_banner_url', $banner );
    update_user_meta( $user_id, 'smarttoolz_channel_links', $links );
    update_user_meta( $user_id, 'smarttoolz_channel_contact_email', $contact );
    update_user_meta( $user_id, 'smarttoolz_channel_watermark_url', $watermark );
    return true;
}

function smarttoolz_video_channel_render_link_fields( $links, $prefix ) {
    if ( ! is_array( $links ) ) { $links = array(); }
    for ( $i = 0; $i < 5; $i++ ) {
        $row = isset( $links[ $i ] ) && is_array( $links[ $i ] ) ? $links[ $i ] : array( 'title' => '', 'url' => '' );
        echo '<div style="display:flex;gap:8px;margin:0 0 8px;max-width:760px;"><input type="text" name="' . esc_attr( $prefix . '[' . $i . '][title]' ) . '" value="' . esc_attr( isset( $row['title'] ) ? $row['title'] : '' ) . '" placeholder="Link title" class="regular-text"><input type="url" name="' . esc_attr( $prefix . '[' . $i . '][url]' ) . '" value="' . esc_attr( isset( $row['url'] ) ? $row['url'] : '' ) . '" placeholder="https://example.com" class="regular-text"></div>';
    }
}

function smarttoolz_video_channel_render_profile_fields( $profile, $prefix ) {
    echo '<table class="form-table" role="presentation">';
    echo '<tr><th>Channel name</th><td><input class="regular-text" maxlength="80" required type="text" name="' . esc_attr( $prefix . 'channel_name' ) . '" value="' . esc_attr( $profile['name'] ) . '"></td></tr>';
    echo '<tr><th>Handle</th><td><input class="regular-text" maxlength="50" required type="text" name="' . esc_attr( $prefix . 'channel_handle' ) . '" value="' . esc_attr( ltrim( $profile['handle'], '@' ) ) . '"><p class="description">Public channel URL: /video/@handle/</p></td></tr>';
    echo '<tr><th>Description</th><td><textarea class="large-text" rows="5" maxlength="1000" name="' . esc_attr( $prefix . 'channel_description' ) . '">' . esc_textarea( $profile['description'] ) . '</textarea></td></tr>';
    echo '<tr><th>Profile picture URL</th><td><input class="regular-text" type="url" name="' . esc_attr( $prefix . 'channel_avatar' ) . '" value="' . esc_attr( $profile['avatar'] ) . '" placeholder="https://example.com/avatar.jpg"></td></tr>';
    echo '<tr><th>Banner image URL</th><td><input class="regular-text" type="url" name="' . esc_attr( $prefix . 'channel_banner' ) . '" value="' . esc_attr( $profile['banner'] ) . '" placeholder="https://example.com/banner.jpg"></td></tr>';
    echo '<tr><th>Channel links</th><td>'; smarttoolz_video_channel_render_link_fields( $profile['links'], $prefix . 'channel_links' ); echo '</td></tr>';
    echo '<tr><th>Business/contact email</th><td><input class="regular-text" type="email" name="' . esc_attr( $prefix . 'channel_contact' ) . '" value="' . esc_attr( $profile['contact'] ) . '" placeholder="contact@example.com"></td></tr>';
    echo '<tr><th>Video watermark URL</th><td><input class="regular-text" type="url" name="' . esc_attr( $prefix . 'channel_watermark' ) . '" value="' . esc_attr( $profile['watermark'] ) . '" placeholder="https://example.com/watermark.png"><p class="description">Reserved for the video player watermark.</p></td></tr>';
    echo '</table>';
}

function smarttoolz_video_channel_resolve_user() {
    $handle = get_query_var( 'smarttoolz_video_channel', '' );
    if ( '' === $handle ) {
        $uri = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
        if ( preg_match( '#/@([A-Za-z0-9._-]+)(?:/|$)#', $uri, $m ) ) { $handle = $m[1]; }
    }
    if ( '' === $handle && is_user_logged_in() ) { return wp_get_current_user(); }
    if ( '' === $handle ) { return false; }
    $handle = sanitize_user( $handle, true );
    $u = get_user_by( 'slug', $handle );
    if ( ! $u ) { $u = get_user_by( 'login', $handle ); }
    return $u;
}

function smarttoolz_video_channel_register_about_route() {
    if ( ! smarttoolz_video_route_enabled( 'channel-about' ) ) { return; }
    $settings = smarttoolz_video_route_settings();
    $base = trim( $settings['home']['slug'], '/' );
    $slug = trim( $settings['channel-about']['slug'], '/' );
    if ( '' === $base || '' === $slug ) { return; }
    add_rewrite_rule( '^' . preg_quote( $base, '/' ) . '/@([^/]+)/' . preg_quote( $slug, '/' ) . '/?$', 'index.php?smarttoolz_video_route=channel-about&smarttoolz_video_channel=$matches[1]', 'top' );
}
add_action( 'init', 'smarttoolz_video_channel_register_about_route', 11 );

function smarttoolz_video_channel_about_route_flush() {
    if ( get_option( 'smarttoolz_video_channel_about_route_version' ) !== '1' ) {
        flush_rewrite_rules( false );
        update_option( 'smarttoolz_video_channel_about_route_version', '1', false );
    }
}
add_action( 'init', 'smarttoolz_video_channel_about_route_flush', 20 );

function smarttoolz_video_channel_banner_url( $id ) {
    $u = get_user_meta( $id, 'smarttoolz_channel_banner_url', true );
    if ( $u ) { return esc_url( $u ); }
    $s = smarttoolz_video_channel_settings();
    return ! empty( $s['default_banner_url'] ) ? esc_url( $s['default_banner_url'] ) : '';
}
function smarttoolz_video_channel_avatar_url( $id ) {
    $u = get_user_meta( $id, 'smarttoolz_avatar_url', true );
    if ( $u ) { return esc_url( $u ); }
    $s = smarttoolz_video_channel_settings();
    if ( ! empty( $s['default_avatar_url'] ) ) { return esc_url( $s['default_avatar_url'] ); }
    return esc_url( get_avatar_url( $id, array( 'size' => 256 ) ) );
}
function smarttoolz_video_channel_subscribers( $id ) {
    $list = get_user_meta( $id, 'smarttoolz_subscribers', true );
    if ( is_array( $list ) ) { return count( array_unique( array_map( 'absint', $list ) ) ); }
    return absint( get_user_meta( $id, 'smarttoolz_subscriber_count', true ) );
}

function smarttoolz_video_channel_style() {
    $r = get_query_var( 'smarttoolz_video_route', '' );
    if ( ! in_array( $r, array( 'channel', 'channel-videos', 'channel-shorts', 'channel-live', 'channel-about', 'settings' ), true ) ) { return; }
    echo '<style id="stv-channel-style">.stv-channel{width:100%;max-width:none;margin:0 auto}.stv-channel__banner{height:220px;border-radius:12px;background:#252525 center/cover no-repeat}.stv-channel__identity{display:flex;gap:22px;align-items:flex-end;padding:0 28px;margin-top:-58px;position:relative}.stv-channel__avatar{width:132px;height:132px;border-radius:50%;border:5px solid #0f0f0f;background:#303030;display:flex;align-items:center;justify-content:center;color:#fff;font-size:48px;font-weight:800;object-fit:cover}.stv-channel__info{padding-bottom:12px;min-width:0}.stv-channel__eyebrow{font-size:12px;color:#aaa;text-transform:uppercase;letter-spacing:.08em}.stv-channel__name{font-size:34px;line-height:1.15;margin:5px 0 7px;color:#fff}.stv-channel__handle,.stv-channel__stats{color:#aaa;margin:0}.stv-channel__stats{margin-top:8px;font-size:14px}.stv-channel__actions{margin-left:auto;display:flex;gap:9px;align-items:center;padding-bottom:16px}.stv-channel__button{display:inline-flex;align-items:center;justify-content:center;border:0;border-radius:22px;padding:10px 18px;background:#fff;color:#111;text-decoration:none;font-weight:700;cursor:pointer}.stv-channel__button--secondary{background:#292929;color:#fff}.stv-channel__tabs{display:flex;gap:28px;border-bottom:1px solid #303030;margin-top:20px;padding:0 12px;overflow:auto}.stv-channel__tab{padding:15px 5px;color:#aaa;text-decoration:none;font-weight:600;white-space:nowrap;border-bottom:3px solid transparent}.stv-channel__tab--active,.stv-channel__tab:hover{color:#fff;border-bottom-color:#fff}.stv-channel__section{padding:24px 12px}.stv-channel__grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:22px 16px}.stv-channel-video__thumb{display:block;aspect-ratio:16/9;background:#202020;border-radius:10px;overflow:hidden;position:relative}.stv-channel-video__thumb img{width:100%;height:100%;object-fit:cover;display:block}.stv-channel-video__play{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:46px;height:46px;border-radius:50%;background:rgba(0,0,0,.8);color:#fff;display:flex;align-items:center;justify-content:center}.stv-channel-video__title{display:block;color:#fff;text-decoration:none;font-weight:700;margin-top:10px;line-height:1.35}.stv-channel-video__meta{color:#aaa;font-size:12px;margin-top:5px}.stv-channel__links{display:flex;gap:9px;flex-wrap:wrap;margin-top:14px}.stv-channel__link{display:inline-flex;padding:7px 11px;border-radius:18px;background:#292929;color:#fff;text-decoration:none;font-size:12px;font-weight:600}.stv-channel__contact{margin-top:14px;color:#aaa;font-size:13px}.stv-channel__watermark{max-width:90px;max-height:90px;display:block;margin-top:12px;object-fit:contain}.stv-channel__about{max-width:900px}.stv-channel__about-description{white-space:pre-line;color:#ddd;font-size:15px;line-height:1.7}.stv-channel__about-title{margin:0 0 12px}.stv-channel__about-row{padding:14px 0;border-bottom:1px solid #303030;color:#aaa}.stv-channel__about-row strong{display:block;color:#fff;margin-bottom:5px}.stv-channel__empty{padding:45px 20px;border:1px dashed #3a3a3a;border-radius:12px;color:#aaa;text-align:center}.stv-channel-settings{width:100%;max-width:1100px;margin:0 auto}.stv-channel-settings__panel{background:#181818;border:1px solid #303030;border-radius:14px;padding:24px}.stv-channel-settings__form{display:grid;gap:16px}.stv-channel-settings__form label{display:grid;gap:7px;color:#ddd;font-weight:600;font-size:13px}.stv-channel-settings__form input,.stv-channel-settings__form textarea{width:100%;box-sizing:border-box;background:#101010;border:1px solid #3a3a3a;border-radius:9px;color:#fff;padding:11px 12px}.stv-channel-settings__form textarea{min-height:130px}.stv-channel-settings__group{border:1px solid #303030;border-radius:12px;padding:18px}.stv-channel-settings__group h2{margin:0 0 12px}.stv-channel-settings__preview{display:grid;grid-template-columns:180px 1fr;gap:18px;align-items:center;margin-bottom:20px}.stv-channel-settings__preview-banner{height:90px;border-radius:10px;background:#252525 center/cover no-repeat}.stv-channel-settings__preview-avatar{width:90px;height:90px;border-radius:50%;object-fit:cover;background:#303030;border:3px solid #0f0f0f}.stv-channel-settings__notice{padding:11px 14px;margin:15px 0;border-radius:9px;background:#17331f;color:#a9efbd}.stv-channel-settings__links{display:grid;gap:9px}.stv-channel-settings__link-row{display:grid;grid-template-columns:1fr 2fr;gap:9px}.stv-channel-settings__link-row input{margin:0}@media(max-width:1000px){.stv-channel__grid{grid-template-columns:repeat(3,minmax(0,1fr))}}@media(max-width:760px){.stv-channel__banner{height:150px}.stv-channel__identity{padding:0 12px;gap:13px;margin-top:-35px;flex-wrap:wrap}.stv-channel__avatar{width:88px;height:88px;font-size:32px}.stv-channel__name{font-size:25px}.stv-channel__actions{margin-left:101px;padding-bottom:0}.stv-channel__grid{grid-template-columns:repeat(2,minmax(0,1fr))}.stv-channel-settings__preview{grid-template-columns:1fr}.stv-channel-settings__link-row{grid-template-columns:1fr}}@media(max-width:480px){.stv-channel__grid{grid-template-columns:1fr}.stv-channel__actions{margin-left:0}}</style>';
}
add_action( 'wp_head', 'smarttoolz_video_channel_style', 31 );

function smarttoolz_video_channel_render( $route ) {
    $s = smarttoolz_video_channel_settings();
    if ( empty( $s['channels_enabled'] ) ) { echo '<div class="stv-channel__empty">Channels are currently disabled.</div>'; return; }
    $u = smarttoolz_video_channel_resolve_user();
    if ( ! $u ) { echo '<div class="stv-channel__empty">Channel not found.</div>'; return; }
    $site = function_exists( 'smarttoolz_video_theme_site_name' ) ? smarttoolz_video_theme_site_name() : get_bloginfo( 'name' );
    $profile = smarttoolz_video_channel_user_profile( $u->ID );
    $videos = get_posts( array( 'post_type' => 'stv_video', 'post_status' => 'publish', 'author' => $u->ID, 'posts_per_page' => 24, 'orderby' => 'date', 'order' => 'DESC' ) );
    $count = count( get_posts( array( 'post_type' => 'stv_video', 'post_status' => 'publish', 'author' => $u->ID, 'posts_per_page' => -1, 'fields' => 'ids' ) ) );
    $subs = smarttoolz_video_channel_subscribers( $u->ID );
    $route_settings = smarttoolz_video_route_settings();
    $base = trim( $route_settings['home']['slug'], '/' );
    $about_slug = isset( $route_settings['channel-about']['slug'] ) ? trim( $route_settings['channel-about']['slug'], '/' ) : 'about';
    $handle = sanitize_title( ltrim( $profile['handle'], '@' ) );
    $tabs = array(
        'channel' => array( 'Home', smarttoolz_video_route_url( 'channel', $profile['handle'] ) ),
        'channel-videos' => array( 'Videos', smarttoolz_video_route_url( 'channel-videos', $profile['handle'] ) ),
        'channel-shorts' => array( 'Shorts', smarttoolz_video_route_url( 'channel-shorts', $profile['handle'] ) ),
        'channel-live' => array( 'Live', smarttoolz_video_route_url( 'channel-live', $profile['handle'] ) ),
        'channel-about' => array( 'About', trailingslashit( home_url( '/' . $base . '/@' . $handle . '/' . $about_slug ) ) ),
    );
    ?>
    <section class="stv-channel">
        <div class="stv-channel__banner"<?php if ( $profile['banner'] ) { echo ' style="background-image:url(' . esc_url( $profile['banner'] ) . ')"'; } ?>></div>
        <div class="stv-channel__identity">
            <img class="stv-channel__avatar" src="<?php echo esc_url( $profile['avatar'] ); ?>" alt="<?php echo esc_attr( $profile['name'] ); ?>">
            <div class="stv-channel__info">
                <span class="stv-channel__eyebrow"><?php echo esc_html( $site ); ?> Channel</span>
                <h1 class="stv-channel__name"><?php echo esc_html( $profile['name'] ); ?></h1>
                <p class="stv-channel__handle">@<?php echo esc_html( ltrim( $profile['handle'], '@' ) ); ?></p>
                <p class="stv-channel__stats"><?php if ( ! empty( $s['show_subscriber_count'] ) ) { echo esc_html( number_format_i18n( $subs ) ) . ' subscribers'; } ?><?php if ( ! empty( $s['show_subscriber_count'] ) && ! empty( $s['show_video_count'] ) ) { echo ' · '; } ?><?php if ( ! empty( $s['show_video_count'] ) ) { echo esc_html( number_format_i18n( $count ) ) . ' videos'; } ?></p>
            </div>
            <div class="stv-channel__actions">
                <?php if ( is_user_logged_in() && get_current_user_id() === (int) $u->ID ) : ?><a class="stv-channel__button" href="<?php echo esc_url( smarttoolz_video_route_url( 'settings' ) ); ?>">Customize channel</a><?php elseif ( is_user_logged_in() ) : ?><a class="stv-channel__button" href="#subscribe">Subscribe</a><?php endif; ?>
            </div>
        </div>
        <nav class="stv-channel__tabs" aria-label="Channel navigation">
            <?php foreach ( $tabs as $key => $tab ) : ?><?php if ( ! smarttoolz_video_route_enabled( $key ) ) { continue; } ?><a class="stv-channel__tab <?php echo $route === $key ? 'stv-channel__tab--active' : ''; ?>" href="<?php echo esc_url( $tab[1] ); ?>"><?php echo esc_html( $tab[0] ); ?></a><?php endforeach; ?>
        </nav>
        <div class="stv-channel__section">
            <?php if ( 'channel-about' === $route ) : ?>
                <div class="stv-channel__about">
                    <h2 class="stv-channel__about-title">About</h2>
                    <?php if ( $profile['description'] ) : ?><div class="stv-channel__about-description"><?php echo esc_html( $profile['description'] ); ?></div><?php else : ?><div class="stv-channel__empty">This channel hasn't added a description yet.</div><?php endif; ?>
                    <?php if ( ! empty( $profile['links'] ) ) : ?><div class="stv-channel__about-row"><strong>Links</strong><div class="stv-channel__links"><?php foreach ( $profile['links'] as $link ) : ?><a class="stv-channel__link" href="<?php echo esc_url( $link['url'] ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $link['title'] ); ?></a><?php endforeach; ?></div></div><?php endif; ?>
                    <?php if ( $profile['contact'] ) : ?><div class="stv-channel__about-row"><strong>Business inquiries</strong><?php echo esc_html( $profile['contact'] ); ?></div><?php endif; ?>
                    <div class="stv-channel__about-row"><strong>Channel details</strong>Joined <?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $u->user_registered ) ) ); ?></div>
                </div>
            <?php elseif ( 'channel' === $route ) : ?>
                <h2 style="margin-top:0;">For you</h2>
            <?php elseif ( 'channel-live' === $route ) : ?><h2>Live</h2><div class="stv-channel__empty">No live videos right now.</div>
            <?php elseif ( 'channel-shorts' === $route ) : ?><h2>Shorts</h2><div class="stv-channel__empty">No Shorts have been published yet.</div>
            <?php else : ?><h2>Videos</h2><?php endif; ?>
            <?php if ( 'channel' !== $route && 'channel-live' !== $route && 'channel-shorts' !== $route && 'channel-about' !== $route ) : ?>
                <?php if ( empty( $videos ) ) : ?><div class="stv-channel__empty">This channel has not published any videos yet.</div><?php else : ?><div class="stv-channel__grid"><?php foreach ( $videos as $video ) : ?><article class="stv-channel-video"><a class="stv-channel-video__thumb" href="<?php echo esc_url( smarttoolz_video_route_url( 'watch', $video->ID ) ); ?>"><?php if ( has_post_thumbnail( $video->ID ) ) { echo get_the_post_thumbnail( $video->ID, 'medium_large' ); } ?><span class="stv-channel-video__play">▶</span></a><a class="stv-channel-video__title" href="<?php echo esc_url( smarttoolz_video_route_url( 'watch', $video->ID ) ); ?>"><?php echo esc_html( $video->post_title ); ?></a><div class="stv-channel-video__meta"><?php echo esc_html( $profile['name'] ); ?> · <?php echo esc_html( get_the_date( '', $video ) ); ?></div></article><?php endforeach; ?></div><?php endif; ?>
            <?php elseif ( 'channel' === $route ) : ?>
                <?php if ( empty( $videos ) ) : ?><div class="stv-channel__empty">This channel has not published any videos yet.</div><?php else : ?><div class="stv-channel__grid"><?php foreach ( $videos as $video ) : ?><article class="stv-channel-video"><a class="stv-channel-video__thumb" href="<?php echo esc_url( smarttoolz_video_route_url( 'watch', $video->ID ) ); ?>"><?php if ( has_post_thumbnail( $video->ID ) ) { echo get_the_post_thumbnail( $video->ID, 'medium_large' ); } ?><span class="stv-channel-video__play">▶</span></a><a class="stv-channel-video__title" href="<?php echo esc_url( smarttoolz_video_route_url( 'watch', $video->ID ) ); ?>"><?php echo esc_html( $video->post_title ); ?></a><div class="stv-channel-video__meta"><?php echo esc_html( $profile['name'] ); ?> · <?php echo esc_html( get_the_date( '', $video ) ); ?></div></article><?php endforeach; ?></div><?php endif; ?>
            <?php endif; ?>
        </div>
    </section>
    <?php
}
function smarttoolz_video_channel_template_redirect() {
    $r = get_query_var( 'smarttoolz_video_route', '' );
    if ( ! in_array( $r, array( 'channel', 'channel-videos', 'channel-shorts', 'channel-live', 'channel-about' ), true ) ) { return; }
    get_header(); smarttoolz_video_channel_render( $r ); get_footer(); exit;
}
add_action( 'template_redirect', 'smarttoolz_video_channel_template_redirect', 99 );

function smarttoolz_video_channel_settings_save() {
    if ( 'settings' !== get_query_var( 'smarttoolz_video_route', '' ) || ! is_user_logged_in() || 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) ) { return; }
    if ( ! isset( $_POST['stv_channel_settings_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['stv_channel_settings_nonce'] ) ), 'stv_channel_settings_save' ) ) { return; }
    if ( empty( smarttoolz_video_channel_settings()['allow_creator_customize'] ) ) { return; }
    smarttoolz_video_channel_save_user_profile( get_current_user_id(), $_POST );
    wp_safe_redirect( add_query_arg( 'stv_channel_saved', '1', smarttoolz_video_route_url( 'settings' ) ) ); exit;
}
add_action( 'template_redirect', 'smarttoolz_video_channel_settings_save', 1 );

function smarttoolz_video_channel_settings_template_redirect() {
    if ( 'settings' !== get_query_var( 'smarttoolz_video_route', '' ) ) { return; }
    get_header(); smarttoolz_video_channel_settings_render(); get_footer(); exit;
}
function smarttoolz_video_channel_settings_render() {
    if ( ! is_user_logged_in() ) { wp_safe_redirect( smarttoolz_video_route_url( 'login' ) ); exit; }
    $s = smarttoolz_video_channel_settings();
    if ( empty( $s['allow_creator_customize'] ) ) { echo '<div class="stv-channel__empty">Channel customization is disabled by the site administrator.</div>'; return; }
    $u = wp_get_current_user();
    $profile = smarttoolz_video_channel_user_profile( $u->ID );
    $saved = isset( $_GET['stv_channel_saved'] );
    $site = function_exists( 'smarttoolz_video_theme_site_name' ) ? smarttoolz_video_theme_site_name() : get_bloginfo( 'name' );
    echo '<section class="stv-channel-settings"><div class="stv-channel-settings__panel"><span class="stv-channel__eyebrow">' . esc_html( $site ) . ' Channel</span><h1 class="stv-page-title">Customize your channel</h1><p class="stv-section-subtitle">Make your channel look like your own. These details are public to visitors.</p>';
    if ( $saved ) { echo '<div class="stv-channel-settings__notice">Channel settings saved.</div>'; }
    echo '<form method="post" enctype="multipart/form-data" class="stv-channel-settings__form">';
    wp_nonce_field( 'stv_channel_settings_save', 'stv_channel_settings_nonce' );
    echo '<div class="stv-channel-settings__preview">';
    if ( $profile['avatar'] ) { echo '<img class="stv-channel-settings__preview-avatar" src="' . esc_url( $profile['avatar'] ) . '" alt="">'; } else { echo '<div class="stv-channel-settings__preview-avatar"></div>'; }
    echo '<div class="stv-channel-settings__preview-banner"' . ( $profile['banner'] ? ' style="background-image:url(' . esc_url( $profile['banner'] ) . ')"' : '' ) . '></div></div>';
    echo '<div class="stv-channel-settings__group"><h2>Profile</h2><label>Channel name<input type="text" name="stv_channel_name" maxlength="80" required value="' . esc_attr( $profile['name'] ) . '"></label><label>Handle<input type="text" name="stv_channel_handle" maxlength="50" required value="' . esc_attr( ltrim( $profile['handle'], '@' ) ) . '"><small>Public URL: ' . esc_html( trailingslashit( home_url( '/' . trim( smarttoolz_video_route_settings()['home']['slug'], '/' ) . '/@' . ltrim( $profile['handle'], '@' ) ) ) ) . '</small></label><label>Description<textarea name="stv_channel_description" maxlength="1000">' . esc_textarea( $profile['description'] ) . '</textarea></label></div>';
    echo '<div class="stv-channel-settings__group"><h2>Brand images</h2><label>Profile picture<input type="file" name="stv_channel_avatar" accept=".jpg,.jpeg,.png,.gif,.webp,image/jpeg,image/png,image/gif,image/webp"></label><label>Banner image<input type="file" name="stv_channel_banner" accept=".jpg,.jpeg,.png,.gif,.webp,image/jpeg,image/png,image/gif,image/webp"></label><small>Upload JPG, PNG, GIF or WebP images from your device.</small></div>';
    echo '<div class="stv-channel-settings__group"><h2>Links</h2><div class="stv-channel-settings__links">';
    for ( $i = 0; $i < 5; $i++ ) { $row = isset( $profile['links'][$i] ) && is_array( $profile['links'][$i] ) ? $profile['links'][$i] : array( 'title' => '', 'url' => '' ); echo '<div class="stv-channel-settings__link-row"><input type="text" name="stv_channel_links[' . $i . '][title]" value="' . esc_attr( isset( $row['title'] ) ? $row['title'] : '' ) . '" placeholder="Link title"><input type="url" name="stv_channel_links[' . $i . '][url]" value="' . esc_attr( isset( $row['url'] ) ? $row['url'] : '' ) . '" placeholder="https://example.com"></div>'; }
    echo '</div></div>';
    echo '<div class="stv-channel-settings__group"><h2>Contact</h2><label>Business/contact email<input type="email" name="stv_channel_contact" value="' . esc_attr( $profile['contact'] ) . '" placeholder="contact@example.com"></label></div>';
    echo '<div class="stv-channel-settings__group"><h2>Video watermark</h2><label>Watermark image URL<input type="url" name="stv_channel_watermark" value="' . esc_attr( $profile['watermark'] ) . '" placeholder="https://example.com/watermark.png"></label><small>This stores the creator watermark for the video player integration.</small></div>';
    echo '<div><button type="submit" class="stv-channel__button">Save channel</button> <a class="stv-channel__button stv-channel__button--secondary" href="' . esc_url( smarttoolz_video_route_url( 'channel', $profile['handle'] ) ) . '">View channel</a></div></form></div></section>';
    return true;
}
add_action( 'template_redirect', 'smarttoolz_video_channel_settings_template_redirect', 99 );
