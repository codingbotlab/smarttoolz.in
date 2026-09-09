<?php
/**
 * SmartToolz Video frontend white-label branding.
 * The SmartToolz product name is kept for WordPress/admin surfaces only.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_frontend_branding_site_name() {
    $name = trim( (string) get_bloginfo( 'name' ) );
    return '' !== $name ? $name : 'Video';
}

function smarttoolz_video_frontend_branding_buffer_start() {
    if ( is_admin() || ! function_exists( 'smarttoolz_video_is_route' ) || ! smarttoolz_video_is_route() ) {
        return;
    }
    ob_start( 'smarttoolz_video_frontend_branding_buffer' );
}
add_action( 'template_redirect', 'smarttoolz_video_frontend_branding_buffer_start', 0 );

function smarttoolz_video_frontend_branding_buffer( $html ) {
    if ( ! is_string( $html ) || '' === $html ) { return $html; }
    $site_name = esc_html( smarttoolz_video_frontend_branding_site_name() );
    $replacements = array(
        'Sign in to SmartToolz' => 'Sign in to ' . $site_name,
        'Create your SmartToolz account' => 'Create your ' . $site_name . ' account',
        'Join SmartToolz, create your own channel and start sharing videos.' => 'Join ' . $site_name . ', create your own channel and start sharing videos.',
        'SmartToolz account • WordPress default login is not used.' => $site_name . ' account • WordPress default login is not used.',
        'Your SmartToolz account could not be created.' => 'Your ' . $site_name . ' account could not be created.',
        'SmartToolz Channel' => $site_name . ' Channel',
    );
    return str_replace( array_keys( $replacements ), array_values( $replacements ), $html );
}

/**
 * HTML5 ad compatibility layer.
 * The custom JS player already owns its ad lifecycle. This layer handles the
 * native HTML5 mode so the same Ads Setup creatives work in both player modes.
 */
function smarttoolz_video_html5_ads_compat() {
    if ( is_admin() || ! function_exists( 'smarttoolz_video_is_route' ) || ! smarttoolz_video_is_route() ) {
        return;
    }
    ?>
    <script id="smarttoolz-html5-ads-compat">
    (function(){
      'use strict';
      function init(root){
        if(!root || root.getAttribute('data-stv-html5-ads')==='1') return;
        var settings={},ads={};
        try{settings=JSON.parse(root.getAttribute('data-settings')||'{}')}catch(e){}
        if(settings.playerMode!=='html') return;
        try{ads=JSON.parse(root.getAttribute('data-ads')||'{}')}catch(e){}
        if(!ads || !ads.enabled) return;
        var video=root.querySelector('.stv-player__video');
        var overlay=root.querySelector('.stv-player__ad');
        var media=root.querySelector('.stv-player__ad-media');
        var title=root.querySelector('.stv-player__ad-title');
        var text=root.querySelector('.stv-player__ad-text');
        var cta=root.querySelector('.stv-player__ad-cta');
        var skip=root.querySelector('.stv-player__ad-skip');
        if(!video||!overlay||!media) return;
        root.setAttribute('data-stv-html5-ads','1');

        var originalSource=root.getAttribute('data-source')||video.currentSrc||video.src||'';
        var adTimer=null,skipTimer=null,tickTimer=null;
        var busy=false,preDone=false,lastMid=-1,lastPause=0;
        var position=0,wasPlaying=false,currentKind='';

        function list(kind){
          return Array.isArray(ads.creatives&&ads.creatives[kind]) ? ads.creatives[kind].filter(function(a){return a&&a.active&&a.src;}) : [];
        }
        function pick(kind){
          var a=list(kind); return a.length ? a[Math.floor(Math.random()*a.length)] : null;
        }
        function clearTimers(){
          if(adTimer){clearTimeout(adTimer);adTimer=null;}
          if(skipTimer){clearTimeout(skipTimer);skipTimer=null;}
          if(tickTimer){clearInterval(tickTimer);tickTimer=null;}
        }
        function closeAd(){
          var resume='post_roll'!==currentKind;
          clearTimers();
          busy=false;
          overlay.hidden=true;
          root.classList.remove('is-ad-playing');
          media.innerHTML='';
          if(title) title.textContent='';
          if(text) text.textContent='';
          if(cta){cta.hidden=true;cta.removeAttribute('href');}
          if(skip){skip.hidden=true;skip.disabled=true;skip.textContent='';skip.onclick=null;}
          video.controls=true;
          try{
            video.src=originalSource;
            video.load();
          }catch(e){}
          currentKind='';
          if(resume&&wasPlaying){
            var restore=function(){
              try{if(isFinite(position))video.currentTime=Math.max(0,position);}catch(e){}
              var p=video.play();if(p&&p.catch)p.catch(function(){});
            };
            if(video.readyState>=1) restore(); else video.addEventListener('loadedmetadata',restore,{once:true});
          }
        }
        function showAd(kind,ad){
          if(busy||!ad) return false;
          busy=true;currentKind=kind;position=video.currentTime||0;wasPlaying=!video.paused;
          clearTimers();
          root.classList.add('is-ad-playing');
          overlay.hidden=false;
          video.pause();
          video.controls=false;
          media.innerHTML='';
          if(title) title.textContent=ad.title||'';
          if(text) text.textContent=ad.text||'';
          if(cta){
            if(ad.url){cta.href=ad.url;cta.hidden=false;}else{cta.hidden=true;cta.removeAttribute('href');}
          }
          if(skip){skip.hidden=!ad.skippable;skip.disabled=!ad.skippable;}

          var finish=function(){closeAd();};
          if(ad.type==='video'){
            var av=document.createElement('video');
            av.src=ad.src;av.autoplay=true;av.playsInline=true;av.preload='auto';
            av.style.width='100%';av.style.height='100%';av.style.objectFit='contain';
            media.appendChild(av);
            av.addEventListener('ended',finish,{once:true});
            av.addEventListener('error',finish,{once:true});
            var pp=av.play();if(pp&&pp.catch)pp.catch(function(){finish();});
          }else{
            var img=document.createElement('img');
            img.src=ad.src;img.alt=ad.title||'Advertisement';
            media.appendChild(img);
            adTimer=setTimeout(finish,Math.max(3,parseInt(ad.duration,10)||10)*1000);
          }

          if(ad.skippable){
            var wait=Math.max(1,parseInt(ads.skip_after,10)||5);
            if(skip) skip.textContent='Skip in '+wait;
            var started=Date.now();
            tickTimer=setInterval(function(){
              if(!busy){clearTimers();return;}
              var left=Math.max(0,wait-Math.floor((Date.now()-started)/1000));
              if(skip){
                skip.hidden=false;
                skip.disabled=left>0;
                skip.textContent=left>0?'Skip in '+left:'Skip ad';
                if(left<=0) skip.onclick=finish;
              }
              if(left<=0&&tickTimer){clearInterval(tickTimer);tickTimer=null;}
            },250);
          }
          return true;
        }
        function maybePre(){
          if(preDone||busy) return;
          preDone=true;
          if(ads.bumper){var b=pick('bumper');if(b&&showAd('bumper',b))return;}
          if(ads.pre_roll){var p=pick('pre_roll');if(p&&showAd('pre_roll',p))return;}
        }
        function maybeMid(){
          if(!ads.midroll||busy) return;
          var interval=Math.max(60,parseInt(ads.midroll_interval,10)||300);
          var mark=Math.floor((video.currentTime||0)/interval);
          if(mark>0&&mark!==lastMid){lastMid=mark;var m=pick('mid_roll');if(m)showAd('mid_roll',m);}
        }
        video.addEventListener('loadedmetadata',function(){
          if(!preDone) maybePre();
        });
        video.addEventListener('timeupdate',maybeMid);
        video.addEventListener('pause',function(){
          if(busy||!ads.pause) return;
          var now=Date.now(),cool=Math.max(15000,parseInt(ads.pause_cooldown,10)||60000);
          if(now-lastPause<cool) return;
          lastPause=now;
          var p=pick('pause');if(p)showAd('pause',p);
        });
        video.addEventListener('ended',function(){
          if(busy||!ads.post_roll) return;
          var p=pick('post_roll');if(p)showAd('post_roll',p);
        });

        if(!video.src) video.src=originalSource;
        if(video.readyState>=1&&!preDone) maybePre();
      }
      function boot(){document.querySelectorAll('[data-stv-player]').forEach(init);}
      if(document.readyState==='loading') document.addEventListener('DOMContentLoaded',boot); else boot();
      window.addEventListener('pageshow',boot);
    }());
    </script>
    <?php
}
add_action( 'wp_footer', 'smarttoolz_video_html5_ads_compat', 90 );
