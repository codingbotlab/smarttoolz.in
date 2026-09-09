<?php
/**
 * SmartToolz HTML5 video player with YouTube-style controls and resilient ads.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_player_settings_defaults() {
    return array(
        'autoplay'          => 0,
        'muted_autoplay'    => 0,
        'loop'              => 0,
        'remember_position' => 1,
        'default_volume'    => 80,
        'playback_speeds'   => '0.5,0.75,1,1.25,1.5,1.75,2',
    );
}

function smarttoolz_video_player_settings() {
    return wp_parse_args( (array) get_option( 'smarttoolz_video_player_settings', array() ), smarttoolz_video_player_settings_defaults() );
}

function smarttoolz_video_player_sanitize_settings( $input ) {
    $d = smarttoolz_video_player_settings_defaults();
    $input = is_array( $input ) ? $input : array();
    $speeds = isset( $input['playback_speeds'] ) ? preg_replace( '/[^0-9.,]/', '', (string) $input['playback_speeds'] ) : $d['playback_speeds'];
    $valid = array();
    foreach ( array_filter( array_map( 'trim', explode( ',', $speeds ) ) ) as $speed ) {
        $n = (float) $speed;
        if ( $n >= 0.25 && $n <= 4 ) {
            $valid[] = rtrim( rtrim( number_format( $n, 2, '.', '' ), '0' ), '.' );
        }
    }
    if ( ! in_array( '1', $valid, true ) ) { $valid[] = '1'; }
    return array(
        'autoplay'          => empty( $input['autoplay'] ) ? 0 : 1,
        'muted_autoplay'    => empty( $input['muted_autoplay'] ) ? 0 : 1,
        'loop'              => empty( $input['loop'] ) ? 0 : 1,
        'remember_position' => empty( $input['remember_position'] ) ? 0 : 1,
        'default_volume'    => max( 0, min( 100, absint( $input['default_volume'] ?? $d['default_volume'] ) ) ),
        'playback_speeds'   => implode( ',', array_values( array_unique( $valid ) ) ),
    );
}

function smarttoolz_video_player_admin_menu() {
    add_submenu_page( 'smarttoolz', 'Player Settings', 'Player Settings', 'manage_options', 'smarttoolz-video-player', 'smarttoolz_video_player_settings_page' );
}
add_action( 'admin_menu', 'smarttoolz_video_player_admin_menu', 25 );

function smarttoolz_video_player_register_settings() {
    register_setting( 'smarttoolz_video_player_group', 'smarttoolz_video_player_settings', array( 'sanitize_callback' => 'smarttoolz_video_player_sanitize_settings' ) );
}
add_action( 'admin_init', 'smarttoolz_video_player_register_settings' );

function smarttoolz_video_player_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) { return; }
    $s = smarttoolz_video_player_settings();
    ?>
    <div class="wrap">
        <h1>Player Settings</h1>
        <p>Configure the custom HTML5 player used on public video watch pages.</p>
        <form method="post" action="options.php">
            <?php settings_fields( 'smarttoolz_video_player_group' ); ?>
            <table class="form-table" role="presentation">
                <tr><th scope="row">Autoplay</th><td><label><input type="checkbox" name="smarttoolz_video_player_settings[autoplay]" value="1" <?php checked( $s['autoplay'], 1 ); ?>> Start videos automatically when the browser permits it</label></td></tr>
                <tr><th scope="row">Muted autoplay</th><td><label><input type="checkbox" name="smarttoolz_video_player_settings[muted_autoplay]" value="1" <?php checked( $s['muted_autoplay'], 1 ); ?>> Use muted playback when autoplay is enabled</label></td></tr>
                <tr><th scope="row">Loop</th><td><label><input type="checkbox" name="smarttoolz_video_player_settings[loop]" value="1" <?php checked( $s['loop'], 1 ); ?>> Loop the video</label></td></tr>
                <tr><th scope="row">Remember position</th><td><label><input type="checkbox" name="smarttoolz_video_player_settings[remember_position]" value="1" <?php checked( $s['remember_position'], 1 ); ?>> Resume a video from the viewer's last saved position on this browser</label></td></tr>
                <tr><th scope="row">Default volume</th><td><input type="number" min="0" max="100" name="smarttoolz_video_player_settings[default_volume]" value="<?php echo esc_attr( $s['default_volume'] ); ?>"> %</td></tr>
                <tr><th scope="row">Playback speeds</th><td><input type="text" class="regular-text" name="smarttoolz_video_player_settings[playback_speeds]" value="<?php echo esc_attr( $s['playback_speeds'] ); ?>"><p class="description">Comma-separated speeds, for example 0.5,0.75,1,1.25,1.5,1.75,2.</p></td></tr>
            </table>
            <?php submit_button( 'Save Player Settings' ); ?>
        </form>
    </div>
    <?php
}

function smarttoolz_video_player_asset() {
    $s = smarttoolz_video_player_settings();
    $speeds = array();
    foreach ( array_filter( array_map( 'trim', explode( ',', (string) $s['playback_speeds'] ) ) ) as $speed ) {
        $n = (float) $speed;
        if ( $n >= 0.25 && $n <= 4 ) { $speeds[] = $n; }
    }
    return array(
        'autoplay' => ! empty( $s['autoplay'] ),
        'mutedAutoplay' => ! empty( $s['muted_autoplay'] ),
        'loop' => ! empty( $s['loop'] ),
        'rememberPosition' => ! empty( $s['remember_position'] ),
        'defaultVolume' => max( 0, min( 1, (float) $s['default_volume'] / 100 ) ),
        'speeds' => array_values( array_unique( $speeds ) ),
    );
}

function smarttoolz_video_player_render( $video_id ) {
    $source = smarttoolz_video_get_source( $video_id );
    if ( '' === $source ) {
        echo '<div class="stv-empty">This video does not have a playable file.</div>';
        return;
    }
    $settings = smarttoolz_video_player_asset();
    $ads = function_exists( 'smarttoolz_video_ads_runtime_config' ) ? smarttoolz_video_ads_runtime_config() : array( 'enabled' => false );
    $poster = get_the_post_thumbnail_url( $video_id, 'large' );
    ?>
    <div class="stv-player" data-stv-player data-video-id="<?php echo esc_attr( $video_id ); ?>" data-source="<?php echo esc_attr( $source ); ?>" data-settings="<?php echo esc_attr( wp_json_encode( $settings ) ); ?>" data-ads="<?php echo esc_attr( wp_json_encode( $ads ) ); ?>">
        <video class="stv-player__video" playsinline preload="metadata" <?php echo $poster ? 'poster="' . esc_url( $poster ) . '"' : ''; ?>></video>
        <div class="stv-player__loading" hidden aria-hidden="true">Loading…</div>
        <div class="stv-player__error" hidden aria-hidden="true">Unable to play this video. Please try again.</div>
        <div class="stv-player__ad" hidden aria-hidden="true">
            <div class="stv-player__ad-inner">
                <div class="stv-player__ad-label">Ad</div>
                <div class="stv-player__ad-media"></div>
                <div class="stv-player__ad-copy"><strong class="stv-player__ad-title"></strong><span class="stv-player__ad-text"></span><a class="stv-player__ad-cta" target="_blank" rel="noopener noreferrer" hidden>Learn more</a></div>
                <button type="button" class="stv-player__ad-skip" hidden>Skip ad</button>
            </div>
        </div>
        <div class="stv-player__controls">
            <button type="button" data-action="play" aria-label="Play">▶</button>
            <button type="button" data-action="mute" aria-label="Mute">🔊</button>
            <input class="stv-player__volume" data-action="volume" type="range" min="0" max="1" step="0.01" aria-label="Volume">
            <span class="stv-player__time"><span data-current>0:00</span> / <span data-duration>0:00</span></span>
            <div class="stv-player__spacer"></div>
            <button type="button" data-action="speed" aria-label="Playback speed">1×</button>
            <button type="button" data-action="theater" aria-label="Theater mode">▭</button>
            <button type="button" data-action="pip" aria-label="Picture in picture">▣</button>
            <button type="button" data-action="fullscreen" aria-label="Fullscreen">⛶</button>
        </div>
    </div>
    <?php
}

function smarttoolz_video_player_styles() {
    if ( 'watch' !== get_query_var( 'smarttoolz_video_route' ) ) { return; }
    echo '<style>
    [hidden]{display:none!important}.stv-watch__player-wrap{width:100%;max-width:none}.stv-player{position:relative;width:100%;background:#000;border-radius:12px;overflow:hidden;aspect-ratio:16/9;box-shadow:0 8px 30px rgba(0,0,0,.25)}.stv-player__video{width:100%;height:100%;display:block;object-fit:contain;background:#000}.stv-player__controls{position:absolute;left:0;right:0;bottom:0;display:flex;align-items:center;gap:7px;padding:42px 12px 10px;background:linear-gradient(transparent,rgba(0,0,0,.9));opacity:0;transition:opacity .2s;z-index:7}.stv-player:hover .stv-player__controls,.stv-player:focus-within .stv-player__controls,.stv-player.is-playing:hover .stv-player__controls{opacity:1}.stv-player__controls button{border:0;background:transparent;color:#fff;font-size:16px;line-height:1;padding:6px;cursor:pointer}.stv-player__controls button:hover{background:rgba(255,255,255,.14);border-radius:5px}.stv-player__volume{width:80px}.stv-player__time{color:#fff;font-size:12px;white-space:nowrap}.stv-player__spacer{flex:1}.stv-player__loading,.stv-player__error{position:absolute;inset:0;display:grid;place-items:center;color:#fff;background:rgba(0,0,0,.25);z-index:6}.stv-player__error{background:rgba(0,0,0,.78);font-size:14px;padding:20px;text-align:center}.stv-player__ad{position:absolute;inset:0;z-index:5;background:#000;display:grid;place-items:center}.stv-player__ad-inner{position:relative;width:100%;height:100%;display:grid;grid-template-rows:1fr auto;align-items:end}.stv-player__ad-media{position:absolute;inset:0;display:grid;place-items:center}.stv-player__ad-media video,.stv-player__ad-media img{max-width:100%;max-height:100%;width:100%;height:100%;object-fit:contain}.stv-player__ad-copy{position:relative;z-index:2;padding:16px 18px 52px;background:linear-gradient(transparent,rgba(0,0,0,.9));display:flex;flex-direction:column;gap:4px;color:#fff}.stv-player__ad-label{position:absolute;z-index:3;top:12px;left:12px;background:rgba(0,0,0,.7);padding:4px 7px;border-radius:4px;color:#fff;font-size:11px}.stv-player__ad-title{font-size:17px}.stv-player__ad-text{font-size:13px;color:#ddd}.stv-player__ad-cta{display:inline-flex;align-self:flex-start;margin-top:7px;padding:7px 12px;border-radius:6px;background:#fff;color:#111;text-decoration:none;font-size:12px;font-weight:700}.stv-player__ad-skip{position:absolute;right:14px;bottom:14px;z-index:4;padding:9px 13px;border:1px solid rgba(255,255,255,.5);border-radius:5px;background:rgba(0,0,0,.75);color:#fff;cursor:pointer}.stv-player.is-theater{position:relative;width:100vw;max-width:1400px;margin-left:50%;transform:translateX(-50%);border-radius:0}.stv-player.is-ad-playing .stv-player__controls{display:none}@media(max-width:700px){.stv-player{border-radius:0}.stv-player__controls{padding-left:7px;padding-right:7px}.stv-player__volume{display:none}.stv-player__controls button{font-size:14px}.stv-player__time{font-size:11px}}
    </style>';
}
add_action( 'wp_head', 'smarttoolz_video_player_styles', 31 );

function smarttoolz_video_player_scripts() {
    if ( 'watch' !== get_query_var( 'smarttoolz_video_route' ) ) { return; }
    ?>
    <script>
    (function(){
      function initPlayer(root){
        if(!root || root.dataset.ready==='1') return;
        root.dataset.ready='1';
        var video=root.querySelector('.stv-player__video'),s={},ads={};
        try{s=JSON.parse(root.dataset.settings||'{}')}catch(e){}
        try{ads=JSON.parse(root.dataset.ads||'{}')}catch(e){}
        var play=root.querySelector('[data-action="play"]'),mute=root.querySelector('[data-action="mute"]'),volume=root.querySelector('[data-action="volume"]'),speed=root.querySelector('[data-action="speed"]'),current=root.querySelector('[data-current]'),duration=root.querySelector('[data-duration]'),theater=root.querySelector('[data-action="theater"]'),pip=root.querySelector('[data-action="pip"]'),fs=root.querySelector('[data-action="fullscreen"]'),loading=root.querySelector('.stv-player__loading'),errorBox=root.querySelector('.stv-player__error');
        var ad=root.querySelector('.stv-player__ad'),adMedia=root.querySelector('.stv-player__ad-media'),adTitle=root.querySelector('.stv-player__ad-title'),adText=root.querySelector('.stv-player__ad-text'),adCta=root.querySelector('.stv-player__ad-cta'),adSkip=root.querySelector('.stv-player__ad-skip');
        var adState={active:false,kind:'',skipTimer:null,finishTimer:null,adVideo:null};
        var preShown=false,lastMid=-1,lastPause=0,loadTimer=null;

        function hideLoading(){if(loading){loading.hidden=true;loading.setAttribute('aria-hidden','true')}}
        function showLoading(){if(!adState.active&&loading){loading.hidden=false;loading.setAttribute('aria-hidden','false')}}
        function showError(){hideLoading();if(errorBox){errorBox.hidden=false;errorBox.setAttribute('aria-hidden','false')}}
        function hideError(){if(errorBox){errorBox.hidden=true;errorBox.setAttribute('aria-hidden','true')}}
        function fmt(t){if(!isFinite(t))return '0:00';t=Math.max(0,Math.floor(t));var h=Math.floor(t/3600),m=Math.floor((t%3600)/60),sec=t%60;return(h?h+':':'')+String(m).padStart(h?2:1,'0')+':'+String(sec).padStart(2,'0')}
        function sync(){if(play)play.textContent=video.paused?'▶':'❚❚';if(mute)mute.textContent=video.muted||video.volume===0?'🔇':'🔊';if(current)current.textContent=fmt(video.currentTime);if(duration)duration.textContent=fmt(video.duration);root.classList.toggle('is-playing',!video.paused)}
        function savePos(){if(s.rememberPosition&&root.dataset.videoId){try{localStorage.setItem('stv_pos_'+root.dataset.videoId,String(video.currentTime))}catch(e){}}}
        function restorePos(){if(!s.rememberPosition||!root.dataset.videoId)return;try{var p=parseFloat(localStorage.getItem('stv_pos_'+root.dataset.videoId)||'0');if(p>5&&isFinite(video.duration)&&p<video.duration-5)video.currentTime=p}catch(e){}}
        function clearAdTimers(){if(adState.skipTimer){clearInterval(adState.skipTimer);adState.skipTimer=null}if(adState.finishTimer){clearTimeout(adState.finishTimer);adState.finishTimer=null}}
        function cleanupAdMedia(){if(adState.adVideo){try{adState.adVideo.pause();adState.adVideo.removeAttribute('src');adState.adVideo.load()}catch(e){}}adState.adVideo=null;if(adMedia)adMedia.innerHTML=''}
        function endAd(resume){clearAdTimers();cleanupAdMedia();adState.active=false;adState.kind='';root.classList.remove('is-ad-playing');if(ad){ad.hidden=true;ad.setAttribute('aria-hidden','true')}hideLoading();if(adTitle)adTitle.textContent='';if(adText)adText.textContent='';if(adCta){adCta.hidden=true;adCta.removeAttribute('href')}if(adSkip){adSkip.hidden=true;adSkip.disabled=false}if(resume!==false){video.play().catch(function(){sync()})}sync()}
        function chooseAd(kind){var list=ads&&ads.creatives&&ads.creatives[kind];if(!Array.isArray(list)||!list.length)return null;return list[Math.floor(Math.random()*list.length)]}
        function showAd(kind,creative,skippable){if(!creative||!creative.src)return false;clearAdTimers();cleanupAdMedia();hideLoading();hideError();adState.active=true;adState.kind=kind;root.classList.add('is-ad-playing');if(ad){ad.hidden=false;ad.setAttribute('aria-hidden','false')}if(adTitle)adTitle.textContent=creative.title||'';if(adText)adText.textContent=creative.text||'';if(adCta){adCta.hidden=!creative.url;if(creative.url)adCta.href=creative.url}if(adSkip){adSkip.hidden=true;adSkip.disabled=true}
          var fallbackMs=Math.max(4000,(Number(creative.duration)||10)*1000);
          if(creative.type==='image'){
            var img=document.createElement('img');img.src=creative.src;img.alt='Advertisement';img.onload=function(){clearAdTimers();adState.finishTimer=setTimeout(function(){endAd(true)},Math.max(3000,(Number(creative.duration)||10)*1000))};img.onerror=function(){endAd(true)};if(adMedia)adMedia.appendChild(img);adState.finishTimer=setTimeout(function(){if(adState.active)endAd(true)},fallbackMs);
          }else{
            var av=document.createElement('video');av.src=creative.src;av.autoplay=true;av.playsInline=true;av.controls=false;av.preload='auto';if(adMedia)adMedia.appendChild(av);adState.adVideo=av;
            av.addEventListener('loadeddata',function(){hideLoading()});av.addEventListener('ended',function(){endAd(true)});av.addEventListener('error',function(){endAd(true)});av.addEventListener('abort',function(){if(adState.active)endAd(true)});
            if(skippable&&adSkip){adSkip.hidden=false;var remaining=Math.max(1,Number(ads.skip_after||5));adSkip.textContent='Skip ad in '+remaining+'s';adState.skipTimer=setInterval(function(){remaining--;if(remaining<=0){clearInterval(adState.skipTimer);adState.skipTimer=null;adSkip.textContent='Skip ad';adSkip.disabled=false}else{adSkip.textContent='Skip ad in '+remaining+'s'}},1000)}
            adState.finishTimer=setTimeout(function(){if(adState.active)endAd(true)},Math.max(15000,fallbackMs));var started=av.play();if(started&&typeof started.catch==='function')started.catch(function(){endAd(true)});
          }
          return true;
        }
        function playPreAd(){if(!ads||!ads.enabled)return false;var c=ads.pre_roll?chooseAd('pre_roll'):null;if(c)return showAd('pre_roll',c,!!c.skippable);var b=ads.bumper?chooseAd('bumper'):null;if(b)return showAd('bumper',b,false);return false}
        function runPostAd(){if(!ads||!ads.enabled||adState.active)return;var c=ads.post_roll?chooseAd('post_roll'):null;if(c){video.pause();showAd('post_roll',c,!!c.skippable)}}
        function runMidAd(){if(!ads||!ads.enabled||adState.active||!ads.midroll)return;var c=chooseAd('mid_roll');if(c){video.pause();showAd('mid_roll',c,!!c.skippable)}}
        function runPauseAd(){if(!ads||!ads.enabled||adState.active||!ads.pause)return;var c=chooseAd('pause');if(c)showAd('pause',c,!!c.skippable)}

        video.src=root.dataset.source||'';
        video.volume=typeof s.defaultVolume==='number'?s.defaultVolume:.8;
        if(s.loop)video.loop=true;
        if(volume)volume.value=video.volume;
        video.load();
        hideLoading();
        hideError();

        if(play)play.onclick=function(){hideError();if(adState.active)return;if(video.paused){showLoading();var p=video.play();if(p&&typeof p.catch==='function')p.catch(function(){hideLoading();showError();})}else video.pause()};
        if(mute)mute.onclick=function(){video.muted=!video.muted;sync()};
        if(volume)volume.oninput=function(){video.volume=parseFloat(volume.value);video.muted=video.volume===0;sync()};
        if(speed)speed.onclick=function(){var list=(s.speeds||[.5,.75,1,1.25,1.5,1.75,2]).map(Number),idx=list.indexOf(video.playbackRate);video.playbackRate=list[(idx+1)%list.length];speed.textContent=video.playbackRate+'×'};
        if(theater)theater.onclick=function(){root.classList.toggle('is-theater');document.body.classList.toggle('stv-theater-active',root.classList.contains('is-theater'))};
        if(pip)pip.onclick=function(){if(document.pictureInPictureEnabled&&video!==document.pictureInPictureElement){video.requestPictureInPicture().catch(function(){})}else if(document.exitPictureInPicture){document.exitPictureInPicture().catch(function(){})}};
        if(fs)fs.onclick=function(){if(document.fullscreenElement){document.exitFullscreen()}else if(root.requestFullscreen){root.requestFullscreen().catch(function(){})}};
        if(adSkip)adSkip.onclick=function(){if(adState.active&&!adSkip.disabled)endAd(true)};

        video.addEventListener('loadstart',function(){hideError();if(video.readyState<3)showLoading()});
        video.addEventListener('loadedmetadata',function(){if(loadTimer){clearTimeout(loadTimer);loadTimer=null}restorePos();hideLoading();sync()});
        video.addEventListener('loadeddata',function(){hideLoading();sync()});
        video.addEventListener('canplay',function(){if(loadTimer){clearTimeout(loadTimer);loadTimer=null}hideLoading();sync()});
        video.addEventListener('canplaythrough',function(){hideLoading()});
        video.addEventListener('progress',function(){if(video.readyState>=2)hideLoading()});
        video.addEventListener('timeupdate',sync);
        video.addEventListener('play',function(){hideLoading();sync();if(!preShown){preShown=true;if(playPreAd()){video.pause()}}});
        video.addEventListener('pause',function(){savePos();sync();if(video.ended||adState.active||video.currentTime<3)return;var now=Date.now();if(ads&&ads.enabled&&ads.pause&&now-lastPause>Number(ads.pause_cooldown||60000)){lastPause=now;runPauseAd()}});
        video.addEventListener('ended',function(){savePos();runPostAd()});
        video.addEventListener('waiting',function(){if(!adState.active)showLoading()});
        video.addEventListener('stalled',function(){if(adState.active)return;if(loadTimer)clearTimeout(loadTimer);loadTimer=setTimeout(function(){if(video.readyState<3){hideLoading();showError()}},12000)});
        video.addEventListener('error',function(){if(adState.active){endAd(true);return}hideLoading();showError()});
        video.addEventListener('timeupdate',function(){if(!ads||!ads.enabled||adState.active||!ads.midroll)return;var every=Number(ads.midroll_interval||300),bucket=Math.floor(video.currentTime/every);if(video.currentTime>30&&bucket!==lastMid){lastMid=bucket;runMidAd()}});

        if(s.autoplay){if(s.mutedAutoplay)video.muted=true;showLoading();video.play().catch(function(){hideLoading();sync()})}
        sync();
      }
      function boot(){document.querySelectorAll('[data-stv-player]').forEach(initPlayer)}
      if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',boot);else boot();
    })();
    </script>
    <?php
}
add_action( 'wp_footer', 'smarttoolz_video_player_scripts', 40 );
