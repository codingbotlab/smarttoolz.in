<?php
/**
 * SmartToolz Video UI Settings bridge.
 *
 * Loaded automatically as an MU plugin so the UI controls stay available even
 * when the main video plugin is updated. This file only adds settings and
 * frontend presentation preferences; it does not replace video functionality.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_ui_default_settings() {
    return array(
        'sidebar_enabled'       => 1,
        'chips_enabled'         => 1,
        'recommended_enabled'   => 1,
        'trending_enabled'      => 1,
        'recommended_count'     => 20,
        'trending_count'        => 8,
        'show_channel_avatar'   => 1,
        'show_views'            => 1,
        'show_time'             => 1,
        'create_button'         => 1,
        'compact_sidebar'       => 0,
        'home_density'          => 'comfortable',
    );
}

function smarttoolz_video_ui_settings() {
    return wp_parse_args( (array) get_option( 'smarttoolz_video_ui_settings', array() ), smarttoolz_video_ui_default_settings() );
}

function smarttoolz_video_ui_setting( $key, $default = null ) {
    $s = smarttoolz_video_ui_settings();
    return array_key_exists( $key, $s ) ? $s[ $key ] : $default;
}

function smarttoolz_video_ui_admin_menu() {
    if ( ! current_user_can( 'manage_options' ) ) { return; }
    add_submenu_page(
        'edit.php?post_type=st_video',
        'YouTube UI Settings',
        'YouTube UI Settings',
        'manage_options',
        'smarttoolz-video-ui-settings',
        'smarttoolz_video_ui_settings_page'
    );
}
add_action( 'admin_menu', 'smarttoolz_video_ui_admin_menu', 40 );

function smarttoolz_video_ui_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) { return; }
    $s = smarttoolz_video_ui_settings();
    ?>
    <div class="wrap">
        <h1>YouTube UI Settings</h1>
        <p class="description">Control the YouTube-inspired SmartToolz video home layout separately from core video/player settings.</p>
        <form method="post" action="options.php">
            <?php settings_fields( 'smarttoolz_video_ui_options' ); ?>
            <table class="form-table" role="presentation">
                <tr><th scope="row">Left sidebar</th><td><label><input type="checkbox" name="smarttoolz_video_ui_settings[sidebar_enabled]" value="1" <?php checked( $s['sidebar_enabled'], 1 ); ?>> Show Home, Videos, Subscriptions, You, History and Liked navigation.</label></td></tr>
                <tr><th scope="row">Category chips</th><td><label><input type="checkbox" name="smarttoolz_video_ui_settings[chips_enabled]" value="1" <?php checked( $s['chips_enabled'], 1 ); ?>> Show the horizontal topic/category chips above the feed.</label></td></tr>
                <tr><th scope="row">Recommended section</th><td><label><input type="checkbox" name="smarttoolz_video_ui_settings[recommended_enabled]" value="1" <?php checked( $s['recommended_enabled'], 1 ); ?>> Show the Recommended videos section.</label><p class="description">Number of cards: <input type="number" min="1" max="100" name="smarttoolz_video_ui_settings[recommended_count]" value="<?php echo esc_attr( $s['recommended_count'] ); ?>" style="width:90px"></p></td></tr>
                <tr><th scope="row">Trending section</th><td><label><input type="checkbox" name="smarttoolz_video_ui_settings[trending_enabled]" value="1" <?php checked( $s['trending_enabled'], 1 ); ?>> Show the Trending section.</label><p class="description">Number of cards: <input type="number" min="1" max="100" name="smarttoolz_video_ui_settings[trending_count]" value="<?php echo esc_attr( $s['trending_count'] ); ?>" style="width:90px"></p></td></tr>
                <tr><th scope="row">Channel avatars</th><td><label><input type="checkbox" name="smarttoolz_video_ui_settings[show_channel_avatar]" value="1" <?php checked( $s['show_channel_avatar'], 1 ); ?>> Show the creator avatar beside video titles.</label></td></tr>
                <tr><th scope="row">Views</th><td><label><input type="checkbox" name="smarttoolz_video_ui_settings[show_views]" value="1" <?php checked( $s['show_views'], 1 ); ?>> Show view counts in video cards.</label></td></tr>
                <tr><th scope="row">Published time</th><td><label><input type="checkbox" name="smarttoolz_video_ui_settings[show_time]" value="1" <?php checked( $s['show_time'], 1 ); ?>> Show relative published time in video cards.</label></td></tr>
                <tr><th scope="row">Create button</th><td><label><input type="checkbox" name="smarttoolz_video_ui_settings[create_button]" value="1" <?php checked( $s['create_button'], 1 ); ?>> Show the Create/Upload action for logged-in creators.</label></td></tr>
                <tr><th scope="row">Sidebar density</th><td><label><input type="checkbox" name="smarttoolz_video_ui_settings[compact_sidebar]" value="1" <?php checked( $s['compact_sidebar'], 1 ); ?>> Use a tighter sidebar spacing.</label></td></tr>
                <tr><th scope="row">Home feed density</th><td><select name="smarttoolz_video_ui_settings[home_density]"><option value="comfortable" <?php selected( $s['home_density'], 'comfortable' ); ?>>Comfortable</option><option value="compact" <?php selected( $s['home_density'], 'compact' ); ?>>Compact</option></select><p class="description">Compact reduces the vertical space between video rows.</p></td></tr>
            </table>
            <?php submit_button( 'Save YouTube UI Settings' ); ?>
        </form>
        <hr>
        <h2>User preferences</h2>
        <p>Logged-in users get a separate Video Preferences page where they can control their personal dark/light preference and feed density. Their choice overrides the admin default for that user only.</p>
        <?php $url = function_exists( 'smarttoolz_video_ui_preferences_url' ) ? smarttoolz_video_ui_preferences_url() : home_url( '/video-preferences/' ); ?>
        <p><a class="button" href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener">Open user preferences</a></p>
    </div>
    <?php
}

function smarttoolz_video_ui_register_settings() {
    register_setting(
        'smarttoolz_video_ui_options',
        'smarttoolz_video_ui_settings',
        array(
            'type'              => 'array',
            'sanitize_callback' => 'smarttoolz_video_ui_sanitize_settings',
            'default'           => smarttoolz_video_ui_default_settings(),
        )
    );
}
add_action( 'admin_init', 'smarttoolz_video_ui_register_settings' );

function smarttoolz_video_ui_sanitize_settings( $input ) {
    $d = smarttoolz_video_ui_default_settings();
    $input = is_array( $input ) ? $input : array();
    $out = $d;
    foreach ( array( 'sidebar_enabled','chips_enabled','recommended_enabled','trending_enabled','show_channel_avatar','show_views','show_time','create_button','compact_sidebar' ) as $key ) {
        $out[ $key ] = empty( $input[ $key ] ) ? 0 : 1;
    }
    $out['recommended_count'] = max( 1, min( 100, absint( $input['recommended_count'] ?? $d['recommended_count'] ) ) );
    $out['trending_count'] = max( 1, min( 100, absint( $input['trending_count'] ?? $d['trending_count'] ) ) );
    $out['home_density'] = in_array( $input['home_density'] ?? '', array( 'comfortable', 'compact' ), true ) ? $input['home_density'] : $d['home_density'];
    return $out;
}

function smarttoolz_video_ui_preferences_url() {
    return home_url( '/video-preferences/' );
}

function smarttoolz_video_ui_maybe_create_preferences_page() {
    if ( get_option( 'smarttoolz_video_preferences_page_id' ) ) { return; }
    $page = get_page_by_path( 'video-preferences' );
    if ( $page ) {
        update_option( 'smarttoolz_video_preferences_page_id', $page->ID, false );
        return;
    }
    $id = wp_insert_post( array(
        'post_title'   => 'Video Preferences',
        'post_name'    => 'video-preferences',
        'post_status'  => 'publish',
        'post_type'    => 'page',
        'post_content' => '[smarttoolz_video_preferences]',
    ) );
    if ( ! is_wp_error( $id ) && $id ) { update_option( 'smarttoolz_video_preferences_page_id', $id, false ); }
}
add_action( 'init', 'smarttoolz_video_ui_maybe_create_preferences_page', 20 );

function smarttoolz_video_ui_user_defaults() {
    return array(
        'theme'   => 'dark',
        'density' => 'comfortable',
    );
}

function smarttoolz_video_ui_user_settings( $user_id = 0 ) {
    $user_id = $user_id ? absint( $user_id ) : get_current_user_id();
    return wp_parse_args( (array) get_user_meta( $user_id, 'smarttoolz_video_ui_preferences', true ), smarttoolz_video_ui_user_defaults() );
}

function smarttoolz_video_ui_preferences_shortcode() {
    if ( ! is_user_logged_in() ) {
        return '<div class="stv-user-preferences"><h1>Video Preferences</h1><p>Please log in to manage your personal video preferences.</p></div>';
    }
    $uid = get_current_user_id();
    if ( isset( $_POST['stv_save_preferences'] ) && isset( $_POST['stv_preferences_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['stv_preferences_nonce'] ) ), 'stv_save_preferences' ) ) {
        $theme = isset( $_POST['stv_theme'] ) ? sanitize_key( wp_unslash( $_POST['stv_theme'] ) ) : 'dark';
        $density = isset( $_POST['stv_density'] ) ? sanitize_key( wp_unslash( $_POST['stv_density'] ) ) : 'comfortable';
        update_user_meta( $uid, 'smarttoolz_video_ui_preferences', array(
            'theme'   => in_array( $theme, array( 'dark', 'light' ), true ) ? $theme : 'dark',
            'density' => in_array( $density, array( 'comfortable', 'compact' ), true ) ? $density : 'comfortable',
        ) );
        echo '<div class="notice notice-success" style="padding:10px 12px;margin-bottom:16px">Video preferences saved.</div>';
    }
    $p = smarttoolz_video_ui_user_settings( $uid );
    ob_start(); ?>
    <div class="stv-user-preferences" style="max-width:760px;margin:0 auto;padding:30px 20px">
        <h1>Video Preferences</h1>
        <p>These choices apply only to your account and override the site's default video presentation.</p>
        <form method="post" style="margin-top:24px">
            <?php wp_nonce_field( 'stv_save_preferences', 'stv_preferences_nonce' ); ?>
            <input type="hidden" name="stv_save_preferences" value="1">
            <p><label><strong>Theme</strong><br><select name="stv_theme"><option value="dark" <?php selected( $p['theme'], 'dark' ); ?>>Dark</option><option value="light" <?php selected( $p['theme'], 'light' ); ?>>Light</option></select></label></p>
            <p><label><strong>Feed density</strong><br><select name="stv_density"><option value="comfortable" <?php selected( $p['density'], 'comfortable' ); ?>>Comfortable</option><option value="compact" <?php selected( $p['density'], 'compact' ); ?>>Compact</option></select></label></p>
            <p><button type="submit" class="button button-primary">Save preferences</button></p>
        </form>
    </div>
    <?php return ob_get_clean();
}
add_shortcode( 'smarttoolz_video_preferences', 'smarttoolz_video_ui_preferences_shortcode' );

function smarttoolz_video_ui_runtime_css() {
    if ( ! function_exists( 'smarttoolz_is_video_context' ) || ! smarttoolz_is_video_context() ) { return; }
    $s = smarttoolz_video_ui_settings();
    $user = is_user_logged_in() ? smarttoolz_video_ui_user_settings() : array();
    $theme = ! empty( $user['theme'] ) ? $user['theme'] : 'dark';
    $density = ! empty( $user['density'] ) ? $user['density'] : $s['home_density'];
    $bg = 'dark' === $theme ? '#0f0f0f' : '#fff';
    $text = 'dark' === $theme ? '#f1f1f1' : '#0f0f0f';
    $muted = 'dark' === $theme ? '#aaa' : '#606060';
    $surface = 'dark' === $theme ? '#181818' : '#f8f8f8';
    $rules = array();
    $rules[] = 'body.smarttoolz-video-platform{background:' . $bg . '!important;color:' . $text . '!important}';
    $rules[] = 'body.smarttoolz-video-platform .st-main,body.smarttoolz-video-platform .st-content{background:' . $bg . '!important;color:' . $text . '!important}';
    $rules[] = 'body.smarttoolz-video-platform .stv-yt-section-head h1,body.smarttoolz-video-platform .stv-yt-section-head h2{color:' . $text . '!important}';
    $rules[] = 'body.smarttoolz-video-platform .stv-yt-card-title{color:' . $text . '!important}';
    $rules[] = 'body.smarttoolz-video-platform .stv-yt-author,body.smarttoolz-video-platform .stv-yt-meta{color:' . $muted . '!important}';
    if ( 'light' === $theme ) {
        $rules[] = 'body.smarttoolz-video-platform .stv-yt-sidebar,body.smarttoolz-video-platform .st-video-header{background:#fff!important;color:#0f0f0f!important}';
        $rules[] = 'body.smarttoolz-video-platform .stv-yt-side-link{color:#0f0f0f!important}';
        $rules[] = 'body.smarttoolz-video-platform .stv-yt-side-link:hover,body.smarttoolz-video-platform .stv-yt-side-link.is-active{background:#f2f2f2!important;color:#0f0f0f!important}';
        $rules[] = 'body.smarttoolz-video-platform .stv-yt-chip{background:#f2f2f2!important;color:#0f0f0f!important}';
        $rules[] = 'body.smarttoolz-video-platform .stv-yt-chip.is-active{background:#0f0f0f!important;color:#fff!important}';
        $rules[] = 'body.smarttoolz-video-platform .stv-yt-empty{background:#f8f8f8!important;color:#606060!important;border-color:#ddd!important}';
    }
    if ( empty( $s['sidebar_enabled'] ) ) { $rules[] = 'body.smarttoolz-video-platform .stv-yt-home{grid-template-columns:1fr!important}body.smarttoolz-video-platform .stv-yt-sidebar{display:none!important}'; }
    if ( empty( $s['chips_enabled'] ) ) { $rules[] = 'body.smarttoolz-video-platform .stv-yt-chips{display:none!important}'; }
    if ( empty( $s['show_channel_avatar'] ) ) { $rules[] = 'body.smarttoolz-video-platform .stv-yt-avatar{display:none!important}body.smarttoolz-video-platform .stv-yt-card-body{display:block!important}'; }
    if ( empty( $s['show_views'] ) ) { $rules[] = 'body.smarttoolz-video-platform .stv-yt-meta{font-size:0!important}'; }
    if ( empty( $s['show_time'] ) ) { $rules[] = 'body.smarttoolz-video-platform .stv-yt-meta{visibility:hidden!important}'; }
    if ( ! empty( $s['compact_sidebar'] ) ) { $rules[] = 'body.smarttoolz-video-platform .stv-yt-side-link{min-height:54px!important}'; }
    if ( 'compact' === $density ) { $rules[] = 'body.smarttoolz-video-platform .stv-yt-grid{gap-top:22px;row-gap:22px!important}'; }
    echo '<style id="smarttoolz-video-ui-runtime">' . implode( '', $rules ) . '</style>';
}
add_action( 'wp_head', 'smarttoolz_video_ui_runtime_css', 75 );

function smarttoolz_video_ui_frontend_settings_link() {
    if ( ! function_exists( 'smarttoolz_is_video_context' ) || ! smarttoolz_is_video_context() || ! is_user_logged_in() ) { return; }
    $url = smarttoolz_video_ui_preferences_url();
    echo '<script id="smarttoolz-video-user-settings-link">document.addEventListener("DOMContentLoaded",function(){var m=document.querySelector(".stv-account-dropdown");if(!m||m.querySelector("[data-stv-user-settings]"))return;var a=document.createElement("a");a.href=' . wp_json_encode( $url ) . ';a.setAttribute("data-stv-user-settings","1");a.innerHTML="<span style=\"width:18px;text-align:center\">⚙</span><span>Video Settings</span>";var sep=document.createElement("div");sep.className="stv-account-separator";m.insertBefore(sep,m.lastElementChild);m.insertBefore(a,m.lastElementChild);});</script>';
}
add_action( 'wp_footer', 'smarttoolz_video_ui_frontend_settings_link', 20 );
