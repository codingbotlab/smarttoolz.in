<?php
/**
 * SmartToolz Video JS player powered by Plyr (MIT licensed).
 * Uses the existing HTML5 <video> source and Ads Setup lifecycle.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_js_player_assets() {
    if ( is_admin() || ! function_exists( 'smarttoolz_video_is_route' ) || ! smarttoolz_video_is_route() ) {
        return;
    }
    $settings = function_exists( 'smarttoolz_video_player_settings' ) ? smarttoolz_video_player_settings() : array( 'player_mode' => 'js' );
    if ( empty( $settings['player_mode'] ) || 'js' !== $settings['player_mode'] ) {
        return;
    }
    wp_enqueue_style( 'smarttoolz-plyr', 'https://cdn.plyr.io/3.7.8/plyr.css', array(), '3.7.8' );
    wp_enqueue_script( 'smarttoolz-plyr', 'https://cdn.plyr.io/3.7.8/plyr.polyfilled.js', array(), '3.7.8', true );
}
add_action( 'wp_enqueue_scripts', 'smarttoolz_video_js_player_assets', 40 );

function smarttoolz_video_js_player_boot() {
    if ( is_admin() || ! function_exists( 'smarttoolz_video_is_route' ) || ! smarttoolz_video_is_route() ) {
        return;
    }
    $settings = function_exists( 'smarttoolz_video_player_settings' ) ? smarttoolz_video_player_settings() : array( 'player_mode' => 'js' );
    if ( empty( $settings['player_mode'] ) || 'js' !== $settings['player_mode'] ) {
        return;
    }
    ?>
    <style id="smarttoolz-plyr-player">
        .stv-player.stv-player--js .stv-player__controls { display:none !important; }
        .stv-player.stv-player--js .plyr { width:100%; height:100%; position:absolute; inset:0; }
        .stv-player.stv-player--js .plyr__video-wrapper { height:100%; background:#000; }
        .stv-player.stv-player--js .plyr video.stv-player__video { width:100%; height:100%; }
        .stv-player.stv-player--js .plyr__controls { z-index:9; }
        .stv-player.stv-player--js.is-ad-playing .plyr__controls { display:none !important; }
    </style>
    <script id="smarttoolz-plyr-boot">
    (function(){
        'use strict';
        function boot(){
            if(typeof window.Plyr === 'undefined') return;
            document.querySelectorAll('.stv-player[data-stv-player]').forEach(function(root){
                if(root.getAttribute('data-plyr-ready')==='1') return;
                var settings={};
                try{ settings=JSON.parse(root.getAttribute('data-settings')||'{}'); }catch(e){}
                if(settings.playerMode!=='js') return;
                var video=root.querySelector('.stv-player__video');
                if(!video) return;
                root.setAttribute('data-plyr-ready','1');
                var speeds=Array.isArray(settings.speeds)&&settings.speeds.length ? settings.speeds : [0.5,0.75,1,1.25,1.5,1.75,2];
                var options={
                    controls:['play-large','play','rewind','fast-forward','progress','current-time','duration','mute','volume','settings','pip','fullscreen'],
                    settings:['quality','speed'],
                    speed:{selected:1,options:speeds},
                    seekTime:parseInt(settings.seekSeconds,10)||10,
                    autoplay:!!settings.autoplay,
                    muted:!!settings.mutedAutoplay,
                    loop:{active:!!settings.loop},
                    clickToPlay:settings.clickToPlay!==false,
                    hideControls:!!settings.autoHideControls,
                    keyboard:{focused:!!settings.keyboardShortcuts,global:!!settings.keyboardShortcuts},
                    doubleClickFullscreen:settings.doubleClickFullscreen!==false,
                    displayDuration:settings.timeDisplay!==false,
                    invertTime:false,
                    volume:typeof settings.defaultVolume==='number'?settings.defaultVolume:0.8,
                    fullscreen:{enabled:settings.fullscreen!==false,fallback:true,iosNative:false},
                    tooltips:{controls:true,seek:true}
                };
                if(settings.pictureInPicture===false) options.controls=options.controls.filter(function(x){return x!=='pip';});
                var player=new Plyr(video,options);
                root.__smarttoolzPlyr=player;
                player.on('ready',function(){
                    try{ if(settings.defaultVolume!==undefined) player.volume=settings.defaultVolume; }catch(e){}
                });
                player.on('error',function(){
                    var err=root.querySelector('.stv-player__error');
                    if(err) err.hidden=false;
                });
                root.addEventListener('dblclick',function(ev){
                    if(settings.doubleClickFullscreen!==false && ev.target===video){
                        try{player.fullscreen.toggle();}catch(e){}
                    }
                });
            });
        }
        function wait(){
            if(typeof window.Plyr!=='undefined') boot();
            else setTimeout(wait,100);
        }
        if(document.readyState==='loading') document.addEventListener('DOMContentLoaded',wait); else wait();
        window.addEventListener('pageshow',boot);
    }());
    </script>
    <?php
}
add_action( 'wp_footer', 'smarttoolz_video_js_player_boot', 120 );
