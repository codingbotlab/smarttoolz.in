<?php
/**
 * Runtime bridge for SmartToolz Video administrator settings.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_runtime_frontend_css() {
    if ( ! function_exists( 'smarttoolz_video_should_load_player_assets' ) || ! smarttoolz_video_should_load_player_assets() ) { return; }
    $s = function_exists( 'smarttoolz_video_settings' ) ? smarttoolz_video_settings() : array();
    $cols = isset( $s['page_columns'] ) ? max( 2, min( 5, absint( $s['page_columns'] ) ) ) : 4;
    $rules = array(
        'body.smarttoolz-video-platform{--stv-runtime-accent:' . esc_attr( $s['accent_color'] ?? '#ff0000' ) . '}',
        'body.smarttoolz-video-platform .stv-grid,body.smarttoolz-video-platform .stv-platform-page .stv-grid,body.smarttoolz-video-platform .stv-home-shell .stv-grid{grid-template-columns:repeat(' . $cols . ',minmax(0,1fr))}',
    );
    if ( empty( $s['likes_enabled'] ) ) { $rules[] = 'body.smarttoolz-video-platform [data-stv-reaction="like"]{display:none!important}'; }
    if ( empty( $s['dislikes_enabled'] ) ) { $rules[] = 'body.smarttoolz-video-platform [data-stv-reaction="dislike"]{display:none!important}'; }
    if ( empty( $s['sharing_enabled'] ) ) { $rules[] = 'body.smarttoolz-video-platform [data-stv-share],body.smarttoolz-video-platform [data-st-video-share]{display:none!important}'; }
    if ( empty( $s['subscriptions_enabled'] ) ) { $rules[] = 'body.smarttoolz-video-platform .stv-subscribe{display:none!important}'; }
    if ( empty( $s['uploads_enabled'] ) ) { $rules[] = 'body.smarttoolz-video-platform a[href*="video-upload"]{display:none!important}'; }
    if ( empty( $s['theater'] ) ) { $rules[] = 'body.smarttoolz-video-platform [data-stv-action="theater"]{display:none!important}'; }
    if ( empty( $s['pip'] ) ) { $rules[] = 'body.smarttoolz-video-platform [data-stv-action="pip"]{display:none!important}'; }
    echo '<style id="smarttoolz-video-settings-runtime">' . implode( '', $rules ) . '@media(max-width:1050px){body.smarttoolz-video-platform .stv-grid,body.smarttoolz-video-platform .stv-platform-page .stv-grid,body.smarttoolz-video-platform .stv-home-shell .stv-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}@media(max-width:800px){body.smarttoolz-video-platform .stv-grid,body.smarttoolz-video-platform .stv-platform-page .stv-grid,body.smarttoolz-video-platform .stv-home-shell .stv-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:480px){body.smarttoolz-video-platform .stv-grid,body.smarttoolz-video-platform .stv-platform-page .stv-grid,body.smarttoolz-video-platform .stv-home-shell .stv-grid{grid-template-columns:1fr}}</style>';
}
add_action( 'wp_head', 'smarttoolz_video_runtime_frontend_css', 60 );

/**
 * When the optional icon library is disabled, keep the video account menu
 * fully styled and usable. The header's detailed account styles are normally
 * printed only when the icon library is enabled, so this lightweight fallback
 * owns the same layout without depending on Font Awesome.
 */
function smarttoolz_video_runtime_no_icon_library_css() {
    if ( ! function_exists( 'smarttoolz_is_video_context' ) || ! smarttoolz_is_video_context() ) { return; }
    if ( function_exists( 'smarttoolz_video_setting' ) && smarttoolz_video_setting( 'icon_enabled', 1 ) ) { return; }
    echo '<style id="smarttoolz-video-no-icon-library-css">
    body.smarttoolz-video-platform .st-header-controls{flex:1;min-width:0;display:flex;align-items:center}
    body.smarttoolz-video-platform .st-video-header .st-header-tools{width:100%;display:flex;align-items:center;justify-content:center;gap:10px;margin-left:0}
    body.smarttoolz-video-platform .st-video-header .st-header-search{width:min(560px,52vw);min-width:260px}
    body.smarttoolz-video-platform .st-video-header .st-search-form{display:flex;align-items:center;gap:0;margin:0}
    body.smarttoolz-video-platform .st-video-header .st-search-field{height:42px;border:1px solid #d3d3d3;border-right:0;border-radius:999px 0 0 999px;padding:0 17px;background:#fff;color:#0f0f0f}
    body.smarttoolz-video-platform .st-video-header .st-search-submit{width:48px;height:42px;border:1px solid #d3d3d3;border-left:0;border-radius:0 999px 999px 0;background:#f8f8f8;color:#0f0f0f;cursor:pointer}
    body.smarttoolz-video-platform .st-video-header .st-search-submit i{display:inline-block;font-style:normal;font-family:inherit;font-size:21px;line-height:1}
    body.smarttoolz-video-platform .st-video-header .st-search-submit i::before{content:"⌕"}
    body.smarttoolz-video-platform .st-video-header .st-theme-toggle{height:42px;border:1px solid #ddd;border-radius:999px;padding:0 12px;background:#fff;color:#111;font-weight:700;white-space:nowrap;cursor:pointer}
    body.smarttoolz-video-platform .stv-account-menu{position:relative;z-index:1000;flex:0 0 auto}
    body.smarttoolz-video-platform .stv-account-trigger{display:inline-flex;align-items:center;gap:7px;min-height:42px;padding:3px 9px 3px 4px;border:1px solid #e5e5e5;border-radius:999px;background:#fff;color:#0f0f0f;cursor:pointer;list-style:none;white-space:nowrap;user-select:none}
    body.smarttoolz-video-platform .stv-account-trigger::-webkit-details-marker{display:none}
    body.smarttoolz-video-platform .stv-account-trigger:focus-visible{outline:2px solid #065fd4;outline-offset:2px}
    body.smarttoolz-video-platform .stv-account-avatar{display:inline-grid;place-items:center;width:36px;height:36px;border-radius:50%;overflow:hidden;background:#f2f2f2;color:#0f0f0f;font-weight:800;flex:0 0 36px}
    body.smarttoolz-video-platform .stv-account-avatar img{display:block;width:100%;height:100%;object-fit:cover}
    body.smarttoolz-video-platform .stv-account-avatar-lg{width:52px;height:52px;flex-basis:52px}
    body.smarttoolz-video-platform .stv-guest-avatar{font-size:17px}
    body.smarttoolz-video-platform .stv-guest-avatar i{font-style:normal;font-family:inherit;display:inline-block;line-height:1}
    body.smarttoolz-video-platform .stv-guest-avatar i::before{content:"○"}
    body.smarttoolz-video-platform .stv-account-name{max-width:145px;overflow:hidden;text-overflow:ellipsis;font-size:.88rem;font-weight:700}
    body.smarttoolz-video-platform .stv-account-chevron{font-size:18px;line-height:1;transform:translateY(-1px)}
    body.smarttoolz-video-platform .stv-account-menu[open] .stv-account-chevron{transform:rotate(180deg)}
    body.smarttoolz-video-platform .stv-account-dropdown{position:absolute;right:0;top:calc(100% + 8px);width:310px;max-height:min(78vh,680px);overflow:auto;padding:8px;background:#fff;border:1px solid #ddd;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,.16)}
    body.smarttoolz-video-platform .stv-account-dropdown a{display:flex;align-items:center;gap:11px;min-height:40px;padding:0 11px;border-radius:8px;color:#0f0f0f;text-decoration:none;font-size:.9rem;font-weight:600}
    body.smarttoolz-video-platform .stv-account-dropdown a:hover{background:#f2f2f2}
    body.smarttoolz-video-platform .stv-account-dropdown i{display:inline-flex;align-items:center;justify-content:center;width:18px;min-width:18px;height:18px;text-align:center;color:#606060;font-style:normal;font-family:inherit;font-size:16px;line-height:1}
    body.smarttoolz-video-platform .stv-account-dropdown .fa-user::before{content:"●"}
    body.smarttoolz-video-platform .stv-account-dropdown .fa-tv::before{content:"▣"}
    body.smarttoolz-video-platform .stv-account-dropdown .fa-house::before{content:"⌂"}
    body.smarttoolz-video-platform .stv-account-dropdown .fa-video::before{content:"▶"}
    body.smarttoolz-video-platform .stv-account-dropdown .fa-fire::before{content:"♨"}
    body.smarttoolz-video-platform .stv-account-dropdown .fa-layer-group::before{content:"▦"}
    body.smarttoolz-video-platform .stv-account-dropdown .fa-magnifying-glass::before{content:"⌕"}
    body.smarttoolz-video-platform .stv-account-dropdown .fa-bell::before{content:"♢"}
    body.smarttoolz-video-platform .stv-account-dropdown .fa-thumbs-up::before{content:"+"}
    body.smarttoolz-video-platform .stv-account-dropdown .fa-clock-rotate-left::before{content:"◷"}
    body.smarttoolz-video-platform .stv-account-dropdown .fa-upload::before{content:"↑"}
    body.smarttoolz-video-platform .stv-account-dropdown .fa-chart-line::before{content:"↗"}
    body.smarttoolz-video-platform .stv-account-dropdown .fa-right-from-bracket::before{content:"↪"}
    body.smarttoolz-video-platform .stv-account-head{display:flex;align-items:center;gap:11px;padding:10px 9px 12px;border-bottom:1px solid #eee;margin-bottom:5px}
    body.smarttoolz-video-platform .stv-account-head strong{display:block;font-size:.92rem}
    body.smarttoolz-video-platform .stv-account-head small{display:block;margin-top:3px;color:#606060;font-size:.74rem;max-width:205px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
    body.smarttoolz-video-platform .stv-account-section{padding:7px 11px 5px;color:#606060;font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em}
    body.smarttoolz-video-platform .stv-account-separator{height:1px;background:#eee;margin:7px 4px}
    body.smarttoolz-video-platform .stv-account-dropdown .stv-account-logout{color:#c00}
    body.smarttoolz-video-platform .stv-account-dropdown .stv-account-logout i{color:#c00}
    body.smarttoolz-video-platform .stv-auth-dropdown{padding:14px 8px 8px;width:310px}
    body.smarttoolz-video-platform .stv-auth-title{font-size:1.05rem;font-weight:800;margin:2px 4px 5px}
    body.smarttoolz-video-platform .stv-auth-dropdown p{margin:0 4px 10px;color:#606060;font-size:.82rem;line-height:1.45}
    body.smarttoolz-video-platform .stv-auth-dropdown a.stv-auth-primary,body.smarttoolz-video-platform .stv-auth-dropdown a.stv-auth-secondary{justify-content:flex-start;margin-top:5px;font-weight:800}
    body.smarttoolz-video-platform .stv-auth-primary{background:#ff0000;color:#fff!important}
    body.smarttoolz-video-platform .stv-auth-primary i,body.smarttoolz-video-platform .stv-auth-secondary i{color:inherit!important}
    body.smarttoolz-video-platform .stv-auth-primary:hover{background:#cc0000!important}
    body.smarttoolz-video-platform .stv-auth-secondary{border:1px solid #d5d5d5;background:#fff}
    @media(max-width:900px){body.smarttoolz-video-platform .st-video-header .st-header-inner{gap:10px}.st-video-header .st-header-search{width:min(52vw,420px);min-width:180px}}
    @media(max-width:700px){body.smarttoolz-video-platform .st-video-header .st-header-tools{justify-content:flex-end}.st-video-header .st-header-search{width:min(50vw,320px);min-width:0}.st-video-header .st-theme-toggle{display:none}body.smarttoolz-video-platform .stv-account-name{display:none}body.smarttoolz-video-platform .stv-account-dropdown,body.smarttoolz-video-platform .stv-auth-dropdown{position:fixed;right:12px;top:72px;width:min(310px,calc(100vw - 24px));max-height:calc(100vh - 90px)}}
    @media(max-width:460px){body.smarttoolz-video-platform .st-video-header .st-header-search{width:calc(100vw - 120px)}body.smarttoolz-video-platform .st-video-header .st-header-inner{padding-inline:10px}}
    </style>';
}
add_action( 'wp_head', 'smarttoolz_video_runtime_no_icon_library_css', 59 );

function smarttoolz_video_runtime_upload_guard() {
    if ( empty( $_POST['st_video_frontend_action'] ) || ! function_exists( 'smarttoolz_video_setting' ) ) { return; }
    $max_mb = absint( smarttoolz_video_setting( 'upload_max_mb', 1024 ) );
    if ( $max_mb > 0 && ! empty( $_FILES['st_video_file']['size'] ) && (int) $_FILES['st_video_file']['size'] > ( $max_mb * 1024 * 1024 ) ) {
        wp_safe_redirect( add_query_arg( 'st_video_upload', 'file-too-large', wp_get_referer() ?: home_url( '/' ) ) );
        exit;
    }
}
add_action( 'template_redirect', 'smarttoolz_video_runtime_upload_guard', 0 );

/**
 * Preserve values across tabbed settings and explicitly persist OFF states.
 * Each tab posts only its own controls, so merge the submitted tab into the
 * existing option rather than letting the absent controls reset to defaults.
 */
function smarttoolz_video_settings_preserve_tab_values( $value, $option, $original_value ) {
    if ( 'smarttoolz_video_settings' !== $option || ! is_array( $original_value ) ) { return $value; }

    $existing = (array) get_option( $option, array() );
    $defaults = function_exists( 'smarttoolz_video_default_settings' ) ? smarttoolz_video_default_settings() : array();
    $merged = wp_parse_args( $existing, $defaults );

    foreach ( $original_value as $key => $setting ) {
        if ( is_string( $key ) && 'smarttoolz_video_settings_tab' !== $key ) {
            $merged[ $key ] = $setting;
        }
    }

    // Determine the active tab from the fields that it necessarily submits.
    $tab_bools = array();
    if ( isset( $original_value['preload'] ) || isset( $original_value['volume'] ) || isset( $original_value['speed'] ) ) {
        $tab_bools = array( 'autoplay', 'muted', 'loop', 'theater', 'pip', 'double_click_fullscreen' );
    } elseif ( isset( $original_value['page_columns'] ) || isset( $original_value['accent_color'] ) || isset( $original_value['frontend_profile'] ) ) {
        $tab_bools = array( 'frontend_profile' );
    } elseif ( isset( $original_value['icon_cdn_url'] ) || isset( $original_value['icon_enabled'] ) ) {
        $tab_bools = array( 'icon_enabled' );
    } elseif ( isset( $original_value['google_client_id'] ) || isset( $original_value['google_enabled'] ) ) {
        $tab_bools = array( 'google_enabled' );
    } elseif ( isset( $original_value['upload_status'] ) || isset( $original_value['upload_max_mb'] ) || isset( $original_value['allowed_formats'] ) || isset( $original_value['uploads_enabled'] ) || isset( $original_value['require_thumbnail'] ) ) {
        $tab_bools = array( 'uploads_enabled', 'require_thumbnail' );
    } else {
        // Features tab contains only booleans.
        $tab_bools = array( 'likes_enabled', 'dislikes_enabled', 'subscriptions_enabled', 'history_enabled', 'sharing_enabled', 'comments_enabled' );
    }

    foreach ( $tab_bools as $key ) {
        if ( ! array_key_exists( $key, $original_value ) ) {
            $merged[ $key ] = 0;
        }
    }

    return smarttoolz_video_sanitize_settings( $merged );
}
add_filter( 'sanitize_option_smarttoolz_video_settings', 'smarttoolz_video_settings_preserve_tab_values', 10, 3 );

/** Add active tab marker to the form. */
function smarttoolz_video_settings_active_tab_field() {
    $screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
    if ( ! $screen || false === strpos( (string) $screen->id, 'smarttoolz-video' ) ) { return; }
    $tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'general';
    echo '<script>
    document.addEventListener("DOMContentLoaded",function(){
        var f=document.querySelector("form[action*=options.php]");
        if(!f)return;
        if(!f.querySelector("input[name=smarttoolz_video_settings_tab]")){
            var i=document.createElement("input");
            i.type="hidden";
            i.name="smarttoolz_video_settings_tab";
            i.value=' . wp_json_encode( $tab ) . ';
            f.appendChild(i);
        }
    });
    </script>';
}
add_action( 'admin_footer', 'smarttoolz_video_settings_active_tab_field', 99 );
