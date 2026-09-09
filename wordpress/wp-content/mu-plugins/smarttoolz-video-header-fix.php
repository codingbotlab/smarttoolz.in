<?php
/** SmartToolz Video header polish. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
function smarttoolz_video_header_fix_assets() {
    if ( ! function_exists( 'smarttoolz_is_video_context' ) || ! smarttoolz_is_video_context() ) { return; }
    $upload = function_exists( 'smarttoolz_video_page_link' ) ? smarttoolz_video_page_link( 'video-upload' ) : home_url( '/video-upload/' );
    $subs = function_exists( 'smarttoolz_video_page_link' ) ? smarttoolz_video_page_link( 'subscriptions' ) : home_url( '/subscriptions/' );
    echo '<style id="smarttoolz-video-header-fix">
    body.smarttoolz-video-platform .st-site-header.st-video-header{position:sticky!important;top:0!important;z-index:1000!important;background:#0f0f0f!important;border-bottom:1px solid #272727!important;box-shadow:none!important}
    body.smarttoolz-video-platform .st-video-header .st-header-inner{height:64px!important;min-height:64px!important;padding:0 20px!important;display:flex!important;align-items:center!important;gap:18px!important;flex-wrap:nowrap!important}
    body.smarttoolz-video-platform .st-video-header .st-brand{height:44px!important;display:inline-flex!important;align-items:center!important;gap:8px!important;flex:0 0 auto!important;position:relative!important;color:#fff!important;font-size:1.08rem!important;font-weight:800!important}
    body.smarttoolz-video-platform .st-video-header .st-brand::before{content:"☰"!important;display:inline-flex!important;align-items:center!important;justify-content:center!important;width:30px!important;height:30px!important;margin-right:2px!important;color:#fff!important;font-size:21px!important;font-weight:400!important;line-height:1!important}
    body.smarttoolz-video-platform .st-video-header .st-logo-mark{width:32px!important;height:32px!important;border-radius:9px!important;background:#4f46e5!important;color:#fff!important}
    body.smarttoolz-video-platform .st-video-header .st-header-controls{display:flex!important;align-items:center!important;justify-content:center!important;flex:1!important;min-width:0!important;gap:14px!important}
    body.smarttoolz-video-platform .st-video-header .st-menu{display:none!important}
    body.smarttoolz-video-platform .st-video-header .st-header-tools{display:flex!important;align-items:center!important;justify-content:center!important;gap:10px!important;flex:1!important;min-width:0!important;margin:0!important}
    body.smarttoolz-video-platform .st-video-header .st-header-search{width:min(560px,52vw)!important;min-width:280px!important;max-width:560px!important;flex:0 1 560px!important}
    body.smarttoolz-video-platform .st-video-header .st-search-form{display:flex!important;align-items:center!important;gap:0!important;margin:0!important;width:100%!important}
    body.smarttoolz-video-platform .st-video-header .st-search-field{height:40px!important;width:100%!important;border:1px solid #303030!important;border-right:0!important;border-radius:22px 0 0 22px!important;background:#121212!important;color:#fff!important;padding:0 16px!important;box-shadow:none!important}
    body.smarttoolz-video-platform .st-video-header .st-search-submit{position:relative!important;width:52px!important;height:40px!important;border:1px solid #303030!important;border-left:0!important;border-radius:0 22px 22px 0!important;background:#272727!important;color:#fff!important;padding:0!important;display:inline-flex!important;align-items:center!important;justify-content:center!important;font-size:0!important}
    body.smarttoolz-video-platform .st-video-header .st-search-submit i{display:none!important}
    body.smarttoolz-video-platform .st-video-header .st-search-submit::before{content:"";width:12px;height:12px;border:2px solid currentColor;border-radius:50%;display:block;box-sizing:border-box}
    body.smarttoolz-video-platform .st-video-header .st-search-submit::after{content:"";position:absolute;width:7px;height:2px;background:currentColor;border-radius:2px;transform:rotate(45deg);margin:9px 0 0 10px}
    body.smarttoolz-video-platform .stv-header-actions{display:flex!important;align-items:center!important;gap:8px!important;flex:0 0 auto!important}
    body.smarttoolz-video-platform .stv-head-create,body.smarttoolz-video-platform .stv-head-notify{display:inline-flex!important;align-items:center!important;justify-content:center!important;height:40px!important;border-radius:20px!important;text-decoration:none!important;white-space:nowrap!important;font-weight:750!important;line-height:1!important;cursor:pointer!important}
    body.smarttoolz-video-platform .stv-head-create{padding:0 15px!important;background:#272727!important;color:#fff!important;border:1px solid #383838!important;font-size:13px!important;gap:7px!important}
    body.smarttoolz-video-platform .stv-head-create:hover{background:#3a3a3a!important;color:#fff!important}
    body.smarttoolz-video-platform .stv-head-notify{position:relative!important;width:40px!important;padding:0!important;background:transparent!important;color:#fff!important;border:0!important;font-size:0!important}
    body.smarttoolz-video-platform .stv-head-notify:hover{background:#272727!important}
    body.smarttoolz-video-platform .stv-head-notify::before{content:"";width:13px;height:15px;border:2px solid currentColor;border-radius:8px 8px 5px 5px;display:block;box-sizing:border-box}
    body.smarttoolz-video-platform .stv-head-notify::after{content:"";position:absolute;width:7px;height:2px;background:currentColor;border-radius:2px;bottom:10px}
    body.smarttoolz-video-platform .stv-account-menu{flex:0 0 auto!important}
    body.smarttoolz-video-platform .stv-account-trigger{height:40px!important;min-height:40px!important;border:0!important;background:transparent!important;color:#fff!important;padding:2px 5px!important}
    body.smarttoolz-video-platform .stv-account-name{color:#fff!important}
    body.smarttoolz-video-platform .stv-account-avatar{background:#272727!important;color:#fff!important}
    body.smarttoolz-video-platform .st-video-header .st-theme-toggle{height:40px!important;background:#181818!important;border-color:#303030!important;color:#fff!important}
    @media(max-width:900px){body.smarttoolz-video-platform .st-video-header .st-header-inner{gap:10px!important;padding-inline:12px!important}body.smarttoolz-video-platform .st-video-header .st-header-search{min-width:210px!important;max-width:440px!important}}
    @media(max-width:700px){body.smarttoolz-video-platform .st-video-header .st-header-inner{height:auto!important;min-height:64px!important;padding-block:10px!important}body.smarttoolz-video-platform .stv-head-notify{display:none!important}.stv-head-create{padding-inline:10px!important}.stv-head-create span:last-child{display:none!important}}
    @media(max-width:520px){body.smarttoolz-video-platform .st-video-header .st-brand>span:not(.st-logo-mark){display:none!important}body.smarttoolz-video-platform .st-video-header .st-header-search{min-width:0!important;max-width:none!important;flex:1 1 auto!important;width:auto!important}.stv-head-create{display:none!important}}
    </style>';
    if ( is_user_logged_in() && function_exists( 'smarttoolz_video_ui_setting' ) && smarttoolz_video_ui_setting( 'create_button', 1 ) ) {
        echo '<script id="smarttoolz-video-header-actions">document.addEventListener("DOMContentLoaded",function(){var t=document.querySelector("body.smarttoolz-video-platform .st-video-header .st-header-tools"),a=document.querySelector("body.smarttoolz-video-platform .st-video-header .stv-account-menu");if(!t||!a||t.querySelector(".stv-header-actions"))return;var w=document.createElement("div");w.className="stv-header-actions";var c=document.createElement("a");c.className="stv-head-create";c.href='.wp_json_encode($upload).';c.innerHTML="<span aria-hidden=\"true\">＋</span><span>Create</span>";var n=document.createElement("a");n.className="stv-head-notify";n.href='.wp_json_encode($subs).';n.setAttribute("aria-label","Notifications");n.innerHTML="<span aria-hidden=\"true\"></span>";w.appendChild(c);w.appendChild(n);t.insertBefore(w,a);});</script>';
    }
}
add_action( 'wp_head', 'smarttoolz_video_header_fix_assets', 90 );
