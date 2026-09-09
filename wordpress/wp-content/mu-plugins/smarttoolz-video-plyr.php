<?php
/**
 * Plugin Name: SmartToolz Video - Plyr JS Player
 * Description: Uses the free/open-source Plyr library for the SmartToolz JS player mode.
 * Version: 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_plyr_is_watch_page() {
    return 'watch' === get_query_var( 'smarttoolz_video_route' );
}

function smarttoolz_video_plyr_assets() {
    if ( ! smarttoolz_video_plyr_is_watch_page() ) { return; }
    wp_enqueue_style( 'stv-plyr', 'https://cdn.jsdelivr.net/npm/plyr@3.7.8/dist/plyr.css', array(), '3.7.8' );
    wp_enqueue_script( 'stv-plyr', 'https://cdn.jsdelivr.net/npm/plyr@3.7.8/dist/plyr.min.js', array(), '3.7.8', true );
}
add_action( 'wp_enqueue_scripts', 'smarttoolz_video_plyr_assets', 30 );

function smarttoolz_video_plyr_bootstrap() {
    if ( ! smarttoolz_video_plyr_is_watch_page() ) { return; }
    ?>
    <style>
        .stv-player.stv-plyr-enabled .stv-player__controls { display:none !important; }
        .stv-player.stv-plyr-enabled .plyr { width:100%; height:100%; }
        .stv-player.stv-plyr-enabled .plyr__video-wrapper { height:100%; }
        .stv-player.stv-plyr-enabled .plyr video { width:100%; height:100%; object-fit:contain; }
        .stv-player.stv-plyr-enabled .stv-player__ad { z-index:20; }
    </style>
    <script>
    (function () {
        'use strict';
        function settings(root) {
            try { return JSON.parse(root.getAttribute('data-settings') || '{}') || {}; } catch (e) { return {}; }
        }
        function init(root) {
            if (!root || root.dataset.stvPlyrReady === '1') return;
            var s = settings(root);
            if (s.playerMode !== 'js' || typeof window.Plyr !== 'function') return;
            var video = root.querySelector('.stv-player__video');
            if (!video) return;
            root.dataset.stvPlyrReady = '1';
            root.classList.add('stv-plyr-enabled');
            var speeds = Array.isArray(s.speeds) && s.speeds.length ? s.speeds : [0.5,0.75,1,1.25,1.5,1.75,2];
            var controls = s.showControls === false ? [] : ['play-large','play','rewind','fast-forward','progress','current-time','duration','mute','volume','settings','pip','fullscreen'];
            var p = new window.Plyr(video, {
                controls: controls,
                clickToPlay: s.clickToPlay !== false,
                keyboard: { focused: s.keyboardShortcuts !== false, global: s.keyboardShortcuts !== false },
                seekTime: Number(s.seekSeconds || 10),
                hideControls: s.autoHideControls !== false,
                speed: { selected: 1, options: speeds },
                fullscreen: { enabled: s.fullscreen !== false, fallback: true, iosNative: true },
                displayDuration: s.timeDisplay !== false,
                invertTime: false,
                tooltips: { controls: true, seek: true },
                loop: { active: s.loop === true },
                volume: Number(s.defaultVolume == null ? 0.8 : s.defaultVolume),
                muted: s.mutedAutoplay === true
            });
            root._stvPlyr = p;
            p.on('ready', function () {
                p.volume = Number(s.defaultVolume == null ? 0.8 : s.defaultVolume);
                if (s.autoplay === true) { if (s.mutedAutoplay === true) p.muted = true; var play = p.play(); if (play && play.catch) play.catch(function(){}); }
            });
        }
        function scan() {
            if (typeof window.Plyr !== 'function') return;
            document.querySelectorAll('[data-stv-player]').forEach(init);
        }
        document.readyState === 'loading' ? document.addEventListener('DOMContentLoaded', function(){setTimeout(scan,50);setTimeout(scan,500);setTimeout(scan,1500);}) : (setTimeout(scan,50),setTimeout(scan,500),setTimeout(scan,1500));
        window.addEventListener('load', scan);
    }());
    </script>
    <?php
}
add_action( 'wp_footer', 'smarttoolz_video_plyr_bootstrap', 100 );
