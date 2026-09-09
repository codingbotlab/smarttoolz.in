<?php
/**
 * Plugin Name: SmartToolz Video - Player & Ads Fix
 * Description: Makes the selected player engine actually apply on the watch page and provides reliable pre-roll, bumper, mid-roll, post-roll and pause ad playback.
 * Version: 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function stv_fix_watch_page() {
    return 'watch' === get_query_var( 'smarttoolz_video_route' );
}

function stv_fix_settings() {
    if ( function_exists( 'smarttoolz_video_player_settings' ) ) {
        return smarttoolz_video_player_settings();
    }
    return array(
        'player_mode' => 'js', 'autoplay' => 0, 'muted_autoplay' => 0, 'loop' => 0,
        'remember_position' => 1, 'default_volume' => 80,
        'playback_speeds' => '0.5,0.75,1,1.25,1.5,1.75,2', 'show_controls' => 1,
        'auto_hide_controls' => 1, 'click_to_play' => 1, 'keyboard_shortcuts' => 1,
        'double_click_fullscreen' => 1, 'picture_in_picture' => 1, 'theater_mode' => 1,
        'fullscreen' => 1, 'seek_buttons' => 1, 'seek_seconds' => 10,
        'progress_bar' => 1, 'volume_control' => 1, 'speed_control' => 1, 'time_display' => 1,
    );
}

function stv_fix_ads() {
    if ( function_exists( 'smarttoolz_video_ads_runtime_config' ) ) {
        return smarttoolz_video_ads_runtime_config();
    }
    return array( 'enabled' => false );
}

function stv_fix_assets() {
    if ( ! stv_fix_watch_page() ) { return; }
    wp_enqueue_style( 'stv-fix-plyr', 'https://cdn.jsdelivr.net/npm/plyr@3.7.8/dist/plyr.css', array(), '3.7.8' );
    wp_enqueue_script( 'stv-fix-plyr', 'https://cdn.jsdelivr.net/npm/plyr@3.7.8/dist/plyr.min.js', array(), '3.7.8', true );
}
add_action( 'wp_enqueue_scripts', 'stv_fix_assets', 100 );

function stv_fix_output_start() {
    if ( stv_fix_watch_page() ) { ob_start( 'stv_fix_output_filter' ); }
}
add_action( 'template_redirect', 'stv_fix_output_start', 999 );

function stv_fix_output_filter( $html ) {
    // Disable the old inline ad engine only. The new engine below owns all
    // video-page ad playback, preventing two engines from closing the same ad.
    $html = preg_replace(
        '/data-ads=["\'][^"\']*["\']/i',
        'data-ads="{&quot;enabled&quot;:false}"',
        $html
    );
    return $html;
}

function stv_fix_footer() {
    if ( ! stv_fix_watch_page() ) { return; }
    $settings = stv_fix_settings();
    $ads = stv_fix_ads();
    ?>
    <style>
        .stv-player .stv-player__controls { display:none !important; }
        .stv-player .plyr { width:100%; height:100%; }
        .stv-player .plyr__video-wrapper { height:100%; }
        .stv-player .plyr video { width:100%; height:100%; object-fit:contain; }
        .stv-fix-ad { position:absolute; inset:0; z-index:999; display:flex; align-items:center; justify-content:center; background:rgba(0,0,0,.96); }
        .stv-fix-ad[hidden] { display:none !important; }
        .stv-fix-ad__box { position:relative; width:100%; height:100%; display:flex; flex-direction:column; align-items:center; justify-content:center; }
        .stv-fix-ad__media { width:100%; height:100%; display:flex; align-items:center; justify-content:center; }
        .stv-fix-ad__media img, .stv-fix-ad__media video { max-width:100%; max-height:100%; width:100%; height:100%; object-fit:contain; }
        .stv-fix-ad__label { position:absolute; left:12px; top:12px; z-index:3; padding:4px 8px; border-radius:4px; background:rgba(0,0,0,.72); color:#fff; font-size:12px; }
        .stv-fix-ad__skip { position:absolute; right:12px; bottom:12px; z-index:4; padding:8px 13px; border:0; border-radius:4px; background:rgba(0,0,0,.8); color:#fff; cursor:pointer; }
        .stv-fix-ad__copy { position:absolute; left:12px; right:12px; bottom:12px; z-index:3; color:#fff; pointer-events:none; }
        .stv-fix-ad__title { display:block; font-size:15px; }
        .stv-fix-ad__text { display:block; font-size:13px; margin-top:3px; }
        .stv-fix-ad__cta { pointer-events:auto; display:inline-block; margin-top:7px; padding:6px 10px; border-radius:4px; background:#fff; color:#111; text-decoration:none; font-size:12px; }
    </style>
    <script>
    (function(){
        'use strict';
        var cfg = <?php echo wp_json_encode( $settings ); ?> || {};
        var ads = <?php echo wp_json_encode( $ads ); ?> || {};

        function bool(v, fallback) { return v === undefined ? fallback : !!v; }
        function pick(list) {
            list = Array.isArray(list) ? list.filter(function(a){ return a && a.src; }) : [];
            if (!list.length) return null;
            return list[Math.floor(Math.random() * list.length)];
        }
        function secs(ad, fallback) {
            var n = Number(ad && ad.duration);
            return isFinite(n) && n > 0 ? n : fallback;
        }
        function init(root) {
            if (!root || root.dataset.stvFixReady === '1') return;
            var video = root.querySelector('.stv-player__video');
            if (!video) return;
            root.dataset.stvFixReady = '1';

            var old = root.querySelector('.stv-player__controls');
            if (old) old.remove();

            var mode = cfg.player_mode === 'html' ? 'html' : 'js';
            var player = null;
            if (mode === 'js' && window.Plyr) {
                player = new window.Plyr(video, {
                    controls: bool(cfg.show_controls, true) ? ['play-large','play','rewind','fast-forward','progress','current-time','duration','mute','volume','settings','pip','fullscreen'] : [],
                    clickToPlay: bool(cfg.click_to_play, true),
                    keyboard: { focused: bool(cfg.keyboard_shortcuts, true), global: bool(cfg.keyboard_shortcuts, true) },
                    seekTime: Number(cfg.seek_seconds || 10),
                    hideControls: bool(cfg.auto_hide_controls, true),
                    speed: { selected: 1, options: (String(cfg.playback_speeds || '0.5,0.75,1,1.25,1.5,1.75,2').split(',').map(Number).filter(function(n){return n>=.25&&n<=4;})) },
                    fullscreen: { enabled: bool(cfg.fullscreen, true), fallback: true, iosNative: true },
                    displayDuration: bool(cfg.time_display, true),
                    tooltips: { controls: true, seek: true },
                    loop: { active: bool(cfg.loop, false) },
                    volume: Math.max(0, Math.min(1, Number(cfg.default_volume == null ? 80 : cfg.default_volume) / 100)),
                    muted: bool(cfg.muted_autoplay, false),
                    ratio: '16:9'
                });
            } else {
                video.controls = bool(cfg.show_controls, true);
                video.loop = bool(cfg.loop, false);
                video.volume = Math.max(0, Math.min(1, Number(cfg.default_volume == null ? 80 : cfg.default_volume) / 100));
                video.muted = bool(cfg.muted_autoplay, false);
            }

            root.classList.toggle('stv-player--js', mode === 'js');
            root.classList.toggle('stv-player--html', mode === 'html');
            root._stvFixPlayer = player || video;

            var src = root.getAttribute('data-source');
            if (src && video.src !== src) video.src = src;

            var adLayer = document.createElement('div');
            adLayer.className = 'stv-fix-ad';
            adLayer.hidden = true;
            adLayer.innerHTML = '<div class="stv-fix-ad__box"><div class="stv-fix-ad__label">Advertisement</div><div class="stv-fix-ad__media"></div><div class="stv-fix-ad__copy"><strong class="stv-fix-ad__title"></strong><span class="stv-fix-ad__text"></span><a class="stv-fix-ad__cta" target="_blank" rel="noopener noreferrer" hidden>Learn more</a></div><button class="stv-fix-ad__skip" type="button" hidden>Skip ad</button></div>';
            root.appendChild(adLayer);
            var media = adLayer.querySelector('.stv-fix-ad__media');
            var skip = adLayer.querySelector('.stv-fix-ad__skip');
            var title = adLayer.querySelector('.stv-fix-ad__title');
            var text = adLayer.querySelector('.stv-fix-ad__text');
            var cta = adLayer.querySelector('.stv-fix-ad__cta');
            var adVideo = null;
            var adTimer = null;
            var mainWasPlaying = false;
            var midTimer = null;
            var pauseLock = false;
            var preShown = false;
            var bumperShown = false;
            var postShown = false;

            function mainPause(){ if(player) player.pause(); else video.pause(); }
            function mainPlay(){ var p = player ? player.play() : video.play(); if(p && p.catch) p.catch(function(){}); }
            function clearAd(){ if(adTimer){clearInterval(adTimer);adTimer=null;} if(adVideo){adVideo.pause();adVideo.removeAttribute('src');adVideo.load();adVideo=null;} media.innerHTML=''; adLayer.hidden=true; skip.hidden=true; }
            function finishAd(){ clearAd(); if(mainWasPlaying){ mainWasPlaying=false; mainPlay(); } }
            function showAd(kind, forceNoSkip){
                if(!ads.enabled) return false;
                var list = ads.creatives && ads.creatives[kind] ? ads.creatives[kind] : [];
                var ad = pick(list);
                if(!ad) return false;
                mainWasPlaying = player ? !player.paused : !video.paused;
                mainPause();
                title.textContent = ad.title || '';
                text.textContent = ad.text || '';
                cta.hidden = !ad.url; if(ad.url) cta.href = ad.url;
                media.innerHTML='';
                var duration = secs(ad, kind === 'bumper' ? 6 : 10);
                var elapsed = 0;
                var canSkip = !forceNoSkip && ad.skippable;
                if(ad.type === 'image'){
                    var img=document.createElement('img'); img.src=ad.src; img.alt=ad.title||'Advertisement'; media.appendChild(img);
                } else {
                    adVideo=document.createElement('video'); adVideo.src=ad.src; adVideo.autoplay=true; adVideo.muted=true; adVideo.playsInline=true; adVideo.controls=false; media.appendChild(adVideo);
                    adVideo.addEventListener('ended', finishAd, {once:true});
                    var ap=adVideo.play(); if(ap&&ap.catch) ap.catch(function(){});
                }
                skip.hidden=!canSkip;
                skip.textContent='Skip ad';
                adLayer.hidden=false;
                adTimer=setInterval(function(){
                    elapsed++;
                    if(canSkip && elapsed >= Math.max(1, Number(ads.skip_after || 5))) skip.hidden=false;
                    if(ad.type === 'image' && elapsed >= duration) finishAd();
                },1000);
                return true;
            }
            skip.addEventListener('click', function(){ finishAd(); });

            function adEnabled(kind){ return !!ads[kind] && !!(ads.creatives && ads.creatives[kind] && ads.creatives[kind].length); }
            function startPre(){
                if(preShown) return false;
                preShown=true;
                if(adEnabled('pre_roll')) return showAd('pre_roll', false);
                if(adEnabled('bumper') && !bumperShown){ bumperShown=true; return showAd('bumper', true); }
                return false;
            }
            function scheduleMid(){
                if(midTimer) clearTimeout(midTimer);
                var interval=Number(ads.midroll_interval||300);
                if(!ads.midroll || !adEnabled('mid_roll') || interval<=0) return;
                midTimer=setTimeout(function(){ if(!(player?player.ended:video.ended) && (player? !player.paused:!video.paused)) showAd('mid_roll', false); scheduleMid(); }, interval*1000);
            }
            function onPlay(){ scheduleMid(); }
            function onPause(){
                if(pauseLock || (adLayer && !adLayer.hidden) || !ads.pause || !adEnabled('pause')) return;
                pauseLock=true;
                setTimeout(function(){ if(player ? player.paused : video.paused){ showAd('pause', false); } }, 50);
                setTimeout(function(){ pauseLock=false; }, Number(ads.pause_cooldown||60000));
            }
            function onEnded(){
                if(postShown || !ads.post_roll || !adEnabled('post_roll')) return;
                postShown=true;
                showAd('post_roll', false);
            }

            if(player){
                player.on('play', onPlay); player.on('pause', onPause); player.on('ended', onEnded);
                player.on('ready', function(){ if(bool(cfg.autoplay,false)){ if(bool(cfg.muted_autoplay,false)) player.muted=true; mainPlay(); } });
            } else {
                video.addEventListener('play', onPlay); video.addEventListener('pause', onPause); video.addEventListener('ended', onEnded);
                video.addEventListener('loadedmetadata', function(){ if(bool(cfg.autoplay,false)){ if(bool(cfg.muted_autoplay,false)) video.muted=true; mainPlay(); } });
            }

            // Pre-roll must start before the main video. Use the media element's
            // first metadata event so the ad cannot disappear immediately.
            var started=false;
            function bootAd(){ if(started)return; started=true; startPre(); scheduleMid(); }
            video.addEventListener('loadedmetadata', bootAd, {once:true});
            setTimeout(bootAd, 1200);
        }

        function scan(){ document.querySelectorAll('[data-stv-player]').forEach(init); }
        if(document.readyState==='loading') document.addEventListener('DOMContentLoaded',scan); else scan();
        window.addEventListener('load',scan);
    }());
    </script>
    <?php
}
add_action( 'wp_footer', 'stv_fix_footer', 110 );
