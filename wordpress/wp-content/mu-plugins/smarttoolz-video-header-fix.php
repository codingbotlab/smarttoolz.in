<?php
/**
 * SmartToolz Video — common YouTube-style header.
 *
 * Shared presentation for every SmartToolz Video page. Independent of the
 * optional icon library so the header structure remains stable.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_header_fix_assets() {
    if ( ! function_exists( 'smarttoolz_is_video_context' ) || ! smarttoolz_is_video_context() ) { return; }

    $upload = function_exists( 'smarttoolz_video_page_link' ) ? smarttoolz_video_page_link( 'video-upload' ) : home_url( '/video-upload/' );
    $subs   = function_exists( 'smarttoolz_video_page_link' ) ? smarttoolz_video_page_link( 'subscriptions' ) : home_url( '/subscriptions/' );

    echo '<style id="smarttoolz-video-common-header">
    body.smarttoolz-video-platform .st-site-header.st-video-header{position:sticky!important;top:0!important;z-index:1000!important;height:64px!important;background:#0f0f0f!important;border-bottom:1px solid #272727!important;box-shadow:none!important}
    body.smarttoolz-video-platform .st-video-header .st-header-inner{height:64px!important;min-height:64px!important;width:100%!important;padding:0 18px!important;display:flex!important;align-items:center!important;gap:18px!important;flex-wrap:nowrap!important}
    body.smarttoolz-video-platform .st-video-header .st-brand{height:40px!important;display:inline-flex!important;align-items:center!important;gap:8px!important;flex:0 0 auto!important;color:#fff!important;font-size:1.12rem!important;font-weight:800!important;letter-spacing:-.035em!important;white-space:nowrap!important}
    body.smarttoolz-video-platform .st-video-header .st-brand::before{content:"☰"!important;display:inline-flex!important;align-items:center!important;justify-content:center!important;width:34px!important;height:34px!important;color:#fff!important;font-size:22px!important;font-weight:400!important;line-height:1!important}
    body.smarttoolz-video-platform .st-video-header .st-logo-mark{width:30px!important;height:30px!important;border-radius:7px!important;background:#4f46e5!important;color:#fff!important;font-size:16px!important}
    body.smarttoolz-video-platform .st-video-header .st-header-controls{display:flex!important;align-items:center!important;justify-content:center!important;flex:1!important;min-width:0!important}
    body.smarttoolz-video-platform .st-video-header .st-menu{display:none!important}
    body.smarttoolz-video-platform .st-video-header .st-header-tools{display:flex!important;align-items:center!important;justify-content:center!important;gap:12px!important;width:100%!important;min-width:0!important;margin:0!important}
    body.smarttoolz-video-platform .st-video-header .st-header-search{width:min(600px,52vw)!important;min-width:260px!important;max-width:600px!important;flex:0 1 600px!important}
    body.smarttoolz-video-platform .st-video-header .st-search-form{display:flex!important;align-items:center!important;gap:0!important;margin:0!important;width:100%!important}
    body.smarttoolz-video-platform .st-video-header .st-search-field{height:40px!important;width:100%!important;border:1px solid #303030!important;border-right:0!important;border-radius:20px 0 0 20px!important;background:#121212!important;color:#fff!important;padding:0 16px!important;font-size:14px!important;box-shadow:none!important}
    body.smarttoolz-video-platform .st-video-header .st-search-field::placeholder{color:#888!important}
    body.smarttoolz-video-platform .st-video-header .st-search-submit{position:relative!important;width:54px!important;height:40px!important;border:1px solid #303030!important;border-left:0!important;border-radius:0 20px 20px 0!important;background:#272727!important;color:#fff!important;padding:0!important;display:inline-flex!important;align-items:center!important;justify-content:center!important;font-size:0!important;flex:0 0 54px!important}
    body.smarttoolz-video-platform .st-video-header .st-search-submit i{display:none!important}
    body.smarttoolz-video-platform .st-video-header .st-search-submit::before{content:"";width:13px;height:13px;border:2px solid currentColor;border-radius:50%;display:block;box-sizing:border-box}
    body.smarttoolz-video-platform .st-video-header .st-search-submit::after{content:"";position:absolute;width:7px;height:2px;background:currentColor;border-radius:2px;transform:rotate(45deg);margin:10px 0 0 10px}
    body.smarttoolz-video-platform .stv-head-mic{position:relative!important;width:40px!important;height:40px!important;flex:0 0 40px!important;display:inline-flex!important;align-items:center!important;justify-content:center!important;border:0!important;border-radius:50%!important;background:#272727!important;color:#fff!important;font-size:0!important;cursor:pointer!important}
    body.smarttoolz-video-platform .stv-head-mic:hover{background:#383838!important}
    body.smarttoolz-video-platform .stv-head-mic::before{content:"";width:10px;height:17px;border:2px solid currentColor;border-radius:7px;display:block;box-sizing:border-box;transform:translateY(-2px)}
    body.smarttoolz-video-platform .stv-head-mic::after{content:"";position:absolute;width:16px;height:10px;border:2px solid currentColor;border-top:0;border-radius:0 0 10px 10px;bottom:9px;box-sizing:border-box}
    body.smarttoolz-video-platform .stv-header-actions{display:flex!important;align-items:center!important;gap:4px!important;flex:0 0 auto!important}
    body.smarttoolz-video-platform .stv-head-action{display:inline-flex!important;align-items:center!important;justify-content:center!important;text-decoration:none!important;border:0!important;color:#fff!important;background:transparent!important;cursor:pointer!important}
    body.smarttoolz-video-platform .stv-head-create{height:40px!important;padding:0 15px!important;border-radius:20px!important;background:#272727!important;border:1px solid #383838!important;font-size:13px!important;font-weight:750!important;gap:7px!important;white-space:nowrap!important}
    body.smarttoolz-video-platform .stv-head-create:hover{background:#3a3a3a!important;color:#fff!important}
    body.smarttoolz-video-platform .stv-head-plus{font-size:23px!important;line-height:1!important;font-weight:300!important}
    body.smarttoolz-video-platform .stv-head-notify{width:40px!important;height:40px!important;border-radius:50%!important;position:relative!important;font-size:0!important}
    body.smarttoolz-video-platform .stv-head-notify:hover{background:#272727!important}
    body.smarttoolz-video-platform .stv-head-notify::before{content:"";width:13px;height:15px;border:2px solid currentColor;border-radius:8px 8px 5px 5px;display:block;box-sizing:border-box}
    body.smarttoolz-video-platform .stv-head-notify::after{content:"";position:absolute;width:7px;height:2px;background:currentColor;border-radius:2px;bottom:10px}
    body.smarttoolz-video-platform .stv-account-menu{flex:0 0 auto!important}
    body.smarttoolz-video-platform .stv-account-trigger{height:40px!important;min-height:40px!important;border:0!important;background:transparent!important;color:#fff!important;padding:2px 4px!important}
    body.smarttoolz-video-platform .stv-account-name{color:#fff!important}
    body.smarttoolz-video-platform .stv-account-avatar{background:#272727!important;color:#fff!important}
    body.smarttoolz-video-platform .st-video-header .st-theme-toggle{display:none!important}
    body.smarttoolz-video-platform .stv-account-dropdown{z-index:1100!important}
    @media(max-width:1100px){body.smarttoolz-video-platform .st-video-header .st-header-inner{gap:12px!important;padding-inline:14px!important}body.smarttoolz-video-platform .st-video-header .st-header-search{max-width:500px!important;min-width:220px!important}}
    @media(max-width:800px){body.smarttoolz-video-platform .st-video-header .st-header-inner{gap:8px!important;padding-inline:10px!important}.stv-head-mic{display:none!important}.stv-head-create{padding-inline:12px!important}.stv-head-create-text{display:none!important}}
    @media(max-width:620px){body.smarttoolz-video-platform .st-video-header .st-brand>span:not(.st-logo-mark){display:none!important}body.smarttoolz-video-platform .st-video-header .st-header-search{min-width:0!important;max-width:none!important;flex:1 1 auto!important;width:auto!important}.stv-head-actions{gap:0!important}.stv-head-create{display:none!important}}
    </style>';

    echo '<script id="smarttoolz-video-common-header-js">document.addEventListener("DOMContentLoaded",function(){
        var root=document.querySelector("body.smarttoolz-video-platform .st-video-header .st-header-tools");
        var account=document.querySelector("body.smarttoolz-video-platform .st-video-header .stv-account-menu");
        var search=root?root.querySelector(".st-header-search"):null;
        if(!root||!account)return;

        if(search&&!root.querySelector(".stv-head-mic")){
            var mic=document.createElement("button");
            mic.type="button";mic.className="stv-head-mic";mic.setAttribute("aria-label","Voice search");mic.title="Voice search";
            mic.addEventListener("click",function(){
                var field=search.querySelector("input[name=stv_search]");
                if(!field)return;
                var R=window.SpeechRecognition||window.webkitSpeechRecognition;
                if(!R){field.focus();return;}
                var rec=new R();rec.lang=document.documentElement.lang||"en-IN";rec.interimResults=false;rec.maxAlternatives=1;
                rec.onresult=function(e){if(e.results&&e.results[0]&&e.results[0][0]){field.value=e.results[0][0].transcript;if(field.form)field.form.submit();}};
                rec.start();
            });
            root.insertBefore(mic,account);
        }

        if(!root.querySelector(".stv-header-actions")){
            var actions=document.createElement("div");actions.className="stv-header-actions";
            var create=document.createElement("a");create.className="stv-head-action stv-head-create";create.href='.wp_json_encode($upload).';create.innerHTML="<span class=\"stv-head-plus\" aria-hidden=\"true\">＋</span><span class=\"stv-head-create-text\">Create</span>";
            var notify=document.createElement("a");notify.className="stv-head-action stv-head-notify";notify.href='.wp_json_encode($subs).';notify.setAttribute("aria-label","Notifications");notify.title="Notifications";
            actions.appendChild(create);actions.appendChild(notify);root.insertBefore(actions,account);
        }
    });</script>';
}
add_action( 'wp_head', 'smarttoolz_video_header_fix_assets', 90 );
