<?php
/**
 * SmartToolz Video header fallback.
 * Keeps the video header visually complete when the optional icon library is disabled.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'wp_head', function () {
    if ( ! function_exists( 'smarttoolz_is_video_context' ) || ! smarttoolz_is_video_context() ) {
        return;
    }
    if ( function_exists( 'smarttoolz_video_setting' ) && smarttoolz_video_setting( 'icon_enabled', 1 ) ) {
        return;
    }
    ?>
    <style id="smarttoolz-header-fallback">
    body.smarttoolz-video-platform .st-video-header .st-header-inner{min-height:64px;align-items:center}
    body.smarttoolz-video-platform .st-video-header .st-header-tools{align-items:center}
    body.smarttoolz-video-platform .stv-login-trigger{height:42px}
    body.smarttoolz-video-platform .stv-guest-avatar{position:relative;font-size:0}
    body.smarttoolz-video-platform .stv-guest-avatar i{display:block!important;width:18px;height:18px;position:relative;font-size:0}
    body.smarttoolz-video-platform .stv-guest-avatar i:before{content:"";position:absolute;left:6px;top:1px;width:6px;height:6px;border:1.7px solid #0f0f0f;border-radius:50%;background:transparent}
    body.smarttoolz-video-platform .stv-guest-avatar i:after{content:"";position:absolute;left:3px;bottom:1px;width:12px;height:7px;border:1.7px solid #0f0f0f;border-bottom:0;border-radius:9px 9px 0 0;background:transparent}
    body.smarttoolz-video-platform .st-search-submit i{font-size:0!important;position:relative;width:18px;height:18px;display:inline-block!important;font-family:inherit!important}
    body.smarttoolz-video-platform .st-search-submit i:before{content:"";position:absolute;left:2px;top:2px;width:9px;height:9px;border:2px solid #0f0f0f;border-radius:50%}
    body.smarttoolz-video-platform .st-search-submit i:after{content:"";position:absolute;left:12px;top:12px;width:6px;height:2px;background:#0f0f0f;transform:rotate(45deg);transform-origin:left center;border-radius:2px}
    body.smarttoolz-video-platform .stv-account-trigger{box-sizing:border-box}
    body.smarttoolz-video-platform .stv-auth-dropdown .stv-auth-primary i,
    body.smarttoolz-video-platform .stv-auth-dropdown .stv-auth-secondary i{font-size:0!important}
    @media(max-width:700px){body.smarttoolz-video-platform .st-video-header .st-header-inner{min-height:58px}}
    </style>
    <?php
}, 120 );
