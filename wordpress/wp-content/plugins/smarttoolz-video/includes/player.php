<?php
/**
 * SmartToolz HTML5 video player with YouTube-style controls and admin settings.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_player_settings_defaults() {
    return array(
        'player_mode'          => 'js',
        'autoplay'             => 0,
        'muted_autoplay'       => 0,
        'loop'                 => 0,
        'remember_position'    => 1,
        'default_volume'       => 80,
        'playback_speeds'      => '0.5,0.75,1,1.25,1.5,1.75,2',
        'show_controls'        => 1,
        'auto_hide_controls'   => 1,
        'click_to_play'        => 1,
        'keyboard_shortcuts'   => 1,
        'double_click_fullscreen' => 1,
        'picture_in_picture'   => 1,
        'theater_mode'         => 1,
        'fullscreen'           => 1,
        'seek_buttons'         => 1,
        'seek_seconds'         => 10,
        'progress_bar'         => 1,
        'volume_control'       => 1,
        'speed_control'        => 1,
        'time_display'         => 1,
    );
}

function smarttoolz_video_player_settings() {
    return wp_parse_args( (array) get_option( 'smarttoolz_video_player_settings', array() ), smarttoolz_video_player_settings_defaults() );
}

function smarttoolz_video_player_sanitize_settings( $input ) {
    $d = smarttoolz_video_player_settings_defaults();
    $input = is_array( $input ) ? $input : array();
    $mode = isset( $input['player_mode'] ) ? sanitize_key( $input['player_mode'] ) : $d['player_mode'];
    if ( ! in_array( $mode, array( 'js', 'html' ), true ) ) { $mode = 'js'; }
    $speeds_raw = isset( $input['playback_speeds'] ) ? preg_replace( '/[^0-9.,]/', '', (string) $input['playback_speeds'] ) : $d['playback_speeds'];
    $valid = array();
    foreach ( array_filter( array_map( 'trim', explode( ',', $speeds_raw ) ) ) as $speed ) {
        $n = (float) $speed;
        if ( $n >= 0.25 && $n <= 4 ) {
            $valid[] = rtrim( rtrim( number_format( $n, 2, '.', '' ), '0' ), '.' );
        }
    }
    if ( ! in_array( '1', $valid, true ) ) { $valid[] = '1'; }
    $bools = array( 'autoplay','muted_autoplay','loop','remember_position','show_controls','auto_hide_controls','click_to_play','keyboard_shortcuts','double_click_fullscreen','picture_in_picture','theater_mode','fullscreen','seek_buttons','progress_bar','volume_control','speed_control','time_display' );
    $out = array( 'player_mode' => $mode, 'playback_speeds' => implode( ',', array_values( array_unique( $valid ) ) ) );
    foreach ( $bools as $key ) { $out[ $key ] = empty( $input[ $key ] ) ? 0 : 1; }
    $out['default_volume'] = max( 0, min( 100, absint( $input['default_volume'] ?? $d['default_volume'] ) ) );
    $out['seek_seconds'] = max( 1, min( 60, absint( $input['seek_seconds'] ?? $d['seek_seconds'] ) ) );
    return $out;
}

function smarttoolz_video_player_admin_menu() {
    add_submenu_page( 'smarttoolz', 'Player Settings', 'Player Settings', 'manage_options', 'smarttoolz-video-player', 'smarttoolz_video_player_settings_page' );
}
add_action( 'admin_menu', 'smarttoolz_video_player_admin_menu', 25 );

function smarttoolz_video_player_register_settings() {
    register_setting( 'smarttoolz_video_player_group', 'smarttoolz_video_player_settings', array( 'sanitize_callback' => 'smarttoolz_video_player_sanitize_settings' ) );
}
add_action( 'admin_init', 'smarttoolz_video_player_register_settings' );

function smarttoolz_video_player_settings_changed() {
    update_option( 'smarttoolz_video_player_settings_version', time(), false );
}
add_action( 'update_option_smarttoolz_video_player_settings', 'smarttoolz_video_player_settings_changed', 10, 3 );

function smarttoolz_video_player_asset() {
    $s = smarttoolz_video_player_settings();
    $speeds = array();
    foreach ( array_filter( array_map( 'trim', explode( ',', (string) $s['playback_speeds'] ) ) ) as $speed ) {
        $n = (float) $speed;
        if ( $n >= 0.25 && $n <= 4 ) { $speeds[] = $n; }
    }
    return array(
        'playerMode' => in_array( $s['player_mode'], array( 'html', 'js' ), true ) ? $s['player_mode'] : 'js',
        'autoplay' => ! empty( $s['autoplay'] ),
        'mutedAutoplay' => ! empty( $s['muted_autoplay'] ),
        'loop' => ! empty( $s['loop'] ),
        'rememberPosition' => ! empty( $s['remember_position'] ),
        'defaultVolume' => max( 0, min( 1, (float) $s['default_volume'] / 100 ) ),
        'speeds' => array_values( array_unique( $speeds ) ),
        'showControls' => ! empty( $s['show_controls'] ),
        'autoHideControls' => ! empty( $s['auto_hide_controls'] ),
        'clickToPlay' => ! empty( $s['click_to_play'] ),
        'keyboardShortcuts' => ! empty( $s['keyboard_shortcuts'] ),
        'doubleClickFullscreen' => ! empty( $s['double_click_fullscreen'] ),
        'pictureInPicture' => ! empty( $s['picture_in_picture'] ),
        'theaterMode' => ! empty( $s['theater_mode'] ),
        'fullscreen' => ! empty( $s['fullscreen'] ),
        'seekButtons' => ! empty( $s['seek_buttons'] ),
        'seekSeconds' => absint( $s['seek_seconds'] ),
        'progressBar' => ! empty( $s['progress_bar'] ),
        'volumeControl' => ! empty( $s['volume_control'] ),
        'speedControl' => ! empty( $s['speed_control'] ),
        'timeDisplay' => ! empty( $s['time_display'] ),
        'version' => (int) get_option( 'smarttoolz_video_player_settings_version', 0 ),
    );
}

function smarttoolz_video_player_ajax_settings() {
    nocache_headers();
    wp_send_json_success( smarttoolz_video_player_asset() );
}
add_action( 'wp_ajax_stv_player_settings', 'smarttoolz_video_player_ajax_settings' );
add_action( 'wp_ajax_nopriv_stv_player_settings', 'smarttoolz_video_player_ajax_settings' );

function smarttoolz_video_player_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) { return; }
    $s = smarttoolz_video_player_settings();
    ?>
    <div class="wrap">
        <h1>Player Settings</h1>
        <p>Configure the public SmartToolz video player. Changes are applied on watch pages without relying on cached page HTML.</p>
        <form method="post" action="options.php">
            <?php settings_fields( 'smarttoolz_video_player_group' ); ?>
            <table class="form-table" role="presentation">
                <tr><th scope="row">Player type</th><td><fieldset><label><input type="radio" name="smarttoolz_video_player_settings[player_mode]" value="js" <?php checked( $s['player_mode'], 'js' ); ?>> <strong>JS Player</strong></label><br><label><input type="radio" name="smarttoolz_video_player_settings[player_mode]" value="html" <?php checked( $s['player_mode'], 'html' ); ?>> <strong>HTML Player</strong></label><p class="description">JS Player = YouTube-style SmartToolz controls. HTML Player = browser native HTML5 controls.</p></fieldset></td></tr>
                <tr><th scope="row">Playback</th><td>
                    <label><input type="checkbox" name="smarttoolz_video_player_settings[autoplay]" value="1" <?php checked( $s['autoplay'],1 ); ?>> Autoplay</label><br>
                    <label><input type="checkbox" name="smarttoolz_video_player_settings[muted_autoplay]" value="1" <?php checked( $s['muted_autoplay'],1 ); ?>> Muted autoplay when autoplay is enabled</label><br>
                    <label><input type="checkbox" name="smarttoolz_video_player_settings[loop]" value="1" <?php checked( $s['loop'],1 ); ?>> Loop</label><br>
                    <label><input type="checkbox" name="smarttoolz_video_player_settings[remember_position]" value="1" <?php checked( $s['remember_position'],1 ); ?>> Remember playback position</label>
                </td></tr>
                <tr><th scope="row">Default volume</th><td><input type="number" min="0" max="100" name="smarttoolz_video_player_settings[default_volume]" value="<?php echo esc_attr($s['default_volume']); ?>"> %</td></tr>
                <tr><th scope="row">Playback speeds</th><td><input type="text" class="regular-text" name="smarttoolz_video_player_settings[playback_speeds]" value="<?php echo esc_attr($s['playback_speeds']); ?>"><p class="description">Comma-separated values from 0.25 to 4, e.g. 0.5,0.75,1,1.25,1.5,1.75,2.</p></td></tr>
                <tr><th scope="row">Controls</th><td>
                    <label><input type="checkbox" name="smarttoolz_video_player_settings[show_controls]" value="1" <?php checked($s['show_controls'],1); ?>> Show custom controls</label><br>
                    <label><input type="checkbox" name="smarttoolz_video_player_settings[auto_hide_controls]" value="1" <?php checked($s['auto_hide_controls'],1); ?>> Auto-hide controls while watching</label><br>
                    <label><input type="checkbox" name="smarttoolz_video_player_settings[click_to_play]" value="1" <?php checked($s['click_to_play'],1); ?>> Click video to play/pause</label><br>
                    <label><input type="checkbox" name="smarttoolz_video_player_settings[keyboard_shortcuts]" value="1" <?php checked($s['keyboard_shortcuts'],1); ?>> Keyboard shortcuts</label><br>
                    <label><input type="checkbox" name="smarttoolz_video_player_settings[double_click_fullscreen]" value="1" <?php checked($s['double_click_fullscreen'],1); ?>> Double-click for fullscreen</label>
                </td></tr>
                <tr><th scope="row">Player features</th><td>
                    <label><input type="checkbox" name="smarttoolz_video_player_settings[picture_in_picture]" value="1" <?php checked($s['picture_in_picture'],1); ?>> Picture-in-picture button</label><br>
                    <label><input type="checkbox" name="smarttoolz_video_player_settings[theater_mode]" value="1" <?php checked($s['theater_mode'],1); ?>> Theater mode button</label><br>
                    <label><input type="checkbox" name="smarttoolz_video_player_settings[fullscreen]" value="1" <?php checked($s['fullscreen'],1); ?>> Fullscreen button</label><br>
                    <label><input type="checkbox" name="smarttoolz_video_player_settings[seek_buttons]" value="1" <?php checked($s['seek_buttons'],1); ?>> Seek buttons</label>
                    <input type="number" min="1" max="60" name="smarttoolz_video_player_settings[seek_seconds]" value="<?php echo esc_attr($s['seek_seconds']); ?>"> seconds per seek
                </td></tr>
                <tr><th scope="row">Control items</th><td>
                    <label><input type="checkbox" name="smarttoolz_video_player_settings[progress_bar]" value="1" <?php checked($s['progress_bar'],1); ?>> Progress bar</label><br>
                    <label><input type="checkbox" name="smarttoolz_video_player_settings[volume_control]" value="1" <?php checked($s['volume_control'],1); ?>> Volume control</label><br>
                    <label><input type="checkbox" name="smarttoolz_video_player_settings[speed_control]" value="1" <?php checked($s['speed_control'],1); ?>> Speed control</label><br>
                    <label><input type="checkbox" name="smarttoolz_video_player_settings[time_display]" value="1" <?php checked($s['time_display'],1); ?>> Time display</label>
                </td></tr>
            </table>
            <?php submit_button( 'Save Player Settings' ); ?>
        </form>
    </div>
    <?php
}

function smarttoolz_video_player_render( $video_id ) {
    $source = smarttoolz_video_get_source( $video_id );
    if ( '' === $source ) { echo '<div class="stv-empty">This video does not have a playable file.</div>'; return; }
    $settings = smarttoolz_video_player_asset();
    $ads = function_exists( 'smarttoolz_video_ads_runtime_config' ) ? smarttoolz_video_ads_runtime_config() : array( 'enabled' => false );
    $poster = get_the_post_thumbnail_url( $video_id, 'large' );
    ?>
    <div class="stv-player" data-stv-player data-video-id="<?php echo esc_attr($video_id); ?>" data-source="<?php echo esc_attr($source); ?>" data-settings="<?php echo esc_attr(wp_json_encode($settings)); ?>" data-ads="<?php echo esc_attr(wp_json_encode($ads)); ?>" data-settings-url="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
        <video class="stv-player__video" playsinline preload="metadata" <?php echo $poster ? 'poster="'.esc_url($poster).'"' : ''; ?>></video>
        <div class="stv-player__loading" hidden>Loading…</div>
        <div class="stv-player__error" hidden>Unable to play this video. Please try again.</div>
        <div class="stv-player__ad" hidden><div class="stv-player__ad-inner"><div class="stv-player__ad-label">Ad</div><div class="stv-player__ad-media"></div><div class="stv-player__ad-copy"><strong class="stv-player__ad-title"></strong><span class="stv-player__ad-text"></span><a class="stv-player__ad-cta" target="_blank" rel="noopener noreferrer" hidden>Learn more</a></div><button type="button" class="stv-player__ad-skip" hidden>Skip ad</button></div></div>
        <div class="stv-player__controls">
            <input class="stv-player__progress" data-action="progress" type="range" min="0" max="1000" value="0" step="1" aria-label="Seek">
            <div class="stv-player__control-row">
                <button type="button" data-action="play" aria-label="Play">▶</button>
                <button type="button" data-action="back" aria-label="Back 10 seconds">↶</button>
                <button type="button" data-action="forward" aria-label="Forward 10 seconds">↷</button>
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
    </div>
    <?php
}

function smarttoolz_video_player_styles() {
    if ( 'watch' !== get_query_var( 'smarttoolz_video_route' ) ) { return; }
    echo '<style>
    [hidden]{display:none!important}.stv-watch__player-wrap{width:100%;max-width:none}.stv-player{position:relative;width:100%;background:#000;border-radius:12px;overflow:hidden;aspect-ratio:16/9;box-shadow:0 8px 30px rgba(0,0,0,.25)}.stv-player__video{width:100%;height:100%;display:block;object-fit:contain;background:#000}.stv-player__controls{position:absolute;left:0;right:0;bottom:0;padding:35px 12px 8px;background:linear-gradient(transparent,rgba(0,0,0,.92));z-index:7;opacity:1;transition:opacity .2s}.stv-player.auto-hide .stv-player__controls{opacity:0}.stv-player.auto-hide:hover .stv-player__controls,.stv-player.auto-hide:focus-within .stv-player__controls,.stv-player.auto-hide.is-paused .stv-player__controls,.stv-player.auto-hide.is-ad-playing .stv-player__controls{opacity:1}.stv-player__control-row{display:flex;align-items:center;gap:6px}.stv-player__controls button{border:0;background:transparent;color:#fff;font-size:16px;line-height:1;padding:6px;cursor:pointer}.stv-player__controls button:hover{background:rgba(255,255,255,.14);border-radius:5px}.stv-player__progress{display:block;width:100%;height:4px;margin:0 0 7px;accent-color:#fff}.stv-player__volume{width:80px}.stv-player__time{color:#fff;font-size:12px;white-space:nowrap}.stv-player__spacer{flex:1}.stv-player__loading,.stv-player__error{position:absolute;inset:0;display:grid;place-items:center;color:#fff;background:rgba(0,0,0,.25);z-index:6}.stv-player__error{background:rgba(0,0,0,.78);font-size:14px;padding:20px;text-align:center}.stv-player__ad{position:absolute;inset:0;z-index:8;background:#000;display:grid;place-items:center}.stv-player__ad-inner{position:relative;width:100%;height:100%;display:grid;grid-template-rows:1fr auto;align-items:end}.stv-player__ad-media{position:absolute;inset:0;display:grid;place-items:center}.stv-player__ad-media video,.stv-player__ad-media img{max-width:100%;max-height:100%;width:100%;height:100%;object-fit:contain}.stv-player__ad-copy{position:relative;z-index:2;padding:16px 18px 52px;background:linear-gradient(transparent,rgba(0,0,0,.9));display:flex;flex-direction:column;gap:4px;color:#fff}.stv-player__ad-label{position:absolute;z-index:3;top:12px;left:12px;background:rgba(0,0,0,.7);padding:4px 7px;border-radius:4px;color:#fff;font-size:11px}.stv-player__ad-title{font-size:17px}.stv-player__ad-text{font-size:13px;color:#ddd}.stv-player__ad-cta{display:inline-flex;align-self:flex-start;margin-top:7px;padding:7px 12px;border-radius:6px;background:#fff;color:#111;text-decoration:none;font-size:12px;font-weight:700}.stv-player__ad-skip{position:absolute;right:14px;bottom:14px;z-index:4;padding:9px 13px;border:1px solid rgba(255,255,255,.5);border-radius:5px;background:rgba(0,0,0,.75);color:#fff;cursor:pointer}.stv-player.is-theater{position:relative;width:100vw;max-width:1400px;margin-left:50%;transform:translateX(-50%);border-radius:0}.stv-player.is-ad-playing .stv-player__controls{display:none}@media(max-width:700px){.stv-player{border-radius:0}.stv-player__controls{padding-left:7px;padding-right:7px}.stv-player__volume{display:none}.stv-player__controls button{font-size:14px}.stv-player__time{font-size:11px}.stv-player__progress{height:3px}}
    </style>';
}
add_action( 'wp_head', 'smarttoolz_video_player_styles', 31 );

function smarttoolz_video_player_scripts() {
    if ( 'watch' !== get_query_var( 'smarttoolz_video_route' ) ) { return; }
    ?>
    <script>
    (function(){
      function initPlayer(root, fresh){
        if(!root) return;
        if(root.dataset.ready==='1' && !fresh) return;
        root.dataset.ready='1';
        var video=root.querySelector('.stv-player__video'),s={},ads={};
        try{s=JSON.parse(root.dataset.settings||'{}')}catch(e){}
        try{ads=JSON.parse(root.dataset.ads||'{}')}catch(e){}
        var play=root.querySelector('[data-action="play"]'),back=root.querySelector('[data-action="back"]'),forward=root.querySelector('[data-action="forward"]'),mute=root.querySelector('[data-action="mute"]'),volume=root.querySelector('[data-action="volume"]'),progress=root.querySelector('[data-action="progress"]'),speed=root.querySelector('[data-action="speed"]'),current=root.querySelector('[data-current]'),duration=root.querySelector('[data-duration]'),theater=root.querySelector('[data-action="theater"]'),pip=root.querySelector('[data-action="pip"]'),fs=root.querySelector('[data-action="fullscreen"]'),controls=root.querySelector('.stv-player__controls'),loading=root.querySelector('.stv-player__loading'),errorBox=root.querySelector('.stv-player__error');
        var ad=root.querySelector('.stv-player__ad'),adMedia=root.querySelector('.stv-player__ad-media'),adTitle=root.querySelector('.stv-player__ad-title'),adText=root.querySelector('.stv-player__ad-text'),adCta=root.querySelector('.stv-player__ad-cta'),adSkip=root.querySelector('.stv-player__ad-skip');
        var adState={active:false,skipTimer:null,finishTimer:null,adVideo:null};
        var preShown=false,lastMid=-1,lastPause=0;
        root.classList.toggle('stv-player--html',s.playerMode==='html');root.classList.toggle('stv-player--js',s.playerMode!=='html');root.classList.toggle('auto-hide',!!s.autoHideControls);root.classList.toggle('no-controls',!s.showControls);
        if(s.playerMode==='html'){video.controls=true}else{video.controls=false}
        if(controls&&!s.showControls)controls.style.display='none';
        if(progress)progress.style.display=s.progressBar?'block':'none';
        if(volume)volume.style.display=s.volumeControl?'block':'none';
        if(speed)speed.style.display=s.speedControl?'block':'none';
        if(current&&duration)current.parentElement.style.display=s.timeDisplay?'inline':'none';
        if(back)back.style.display=s.seekButtons?'block':'none';if(forward)forward.style.display=s.seekButtons?'block':'none';
        if(theater)theater.style.display=s.theaterMode?'block':'none';if(pip)pip.style.display=s.pictureInPicture&&document.pictureInPictureEnabled?'block':'none';if(fs)fs.style.display=s.fullscreen?'block':'none';
        video.src=root.dataset.source||'';video.volume=typeof s.defaultVolume==='number'?s.defaultVolume:.8;video.loop=!!s.loop;if(volume)volume.value=video.volume;video.load();
        function hideLoading(){if(loading)loading.hidden=true}function showLoading(){if(!adState.active&&loading)loading.hidden=false}function showError(){hideLoading();if(errorBox)errorBox.hidden=false}function hideError(){if(errorBox)errorBox.hidden=true}
        function fmt(t){if(!isFinite(t))return '0:00';t=Math.max(0,Math.floor(t));var h=Math.floor(t/3600),m=Math.floor((t%3600)/60),sec=t%60;return(h?h+':':'')+String(m).padStart(h?2:1,'0')+':'+String(sec).padStart(2,'0')}
        function sync(){if(play)play.textContent=video.paused?'▶':'❚❚';if(mute)mute.textContent=video.muted||video.volume===0?'🔇':'🔊';if(current)current.textContent=fmt(video.currentTime);if(duration)duration.textContent=fmt(video.duration);if(progress&&isFinite(video.duration)&&video.duration>0)progress.value=Math.round(video.currentTime/video.duration*1000);root.classList.toggle('is-playing',!video.paused);root.classList.toggle('is-paused',video.paused)}
        function savePos(){if(s.rememberPosition&&root.dataset.videoId){try{localStorage.setItem('stv_pos_'+root.dataset.videoId,String(video.currentTime))}catch(e){}}}
        function restorePos(){if(!s.rememberPosition||!root.dataset.videoId||!isFinite(video.duration))return;try{var p=parseFloat(localStorage.getItem('stv_pos_'+root.dataset.videoId)||'0');if(p>5&&p<video.duration-5)video.currentTime=p}catch(e){}}
        function clearAd(){if(adState.skipTimer)clearInterval(adState.skipTimer);if(adState.finishTimer)clearTimeout(adState.finishTimer);adState.skipTimer=null;adState.finishTimer=null;if(adState.adVideo){try{adState.adVideo.pause();adState.adVideo.removeAttribute('src');adState.adVideo.load()}catch(e){}}adState.adVideo=null;if(adMedia)adMedia.innerHTML=''}
        function endAd(resume){clearAd();adState.active=false;root.classList.remove('is-ad-playing');if(ad){ad.hidden=true}if(adSkip){adSkip.hidden=true;adSkip.disabled=false}hideLoading();if(resume!==false)video.play().catch(function(){});sync()}
        function chooseAd(kind){var list=ads&&ads.creatives&&ads.creatives[kind];return Array.isArray(list)&&list.length?list[Math.floor(Math.random()*list.length)]:null}
        function showAd(kind,c,canSkip){if(!c||!c.src)return false;clearAd();adState.active=true;root.classList.add('is-ad-playing');video.pause();if(ad)ad.hidden=false;if(adTitle)adTitle.textContent=c.title||'';if(adText)adText.textContent=c.text||'';if(adCta){adCta.hidden=!c.url;if(c.url)adCta.href=c.url}if(adSkip){adSkip.hidden=true;adSkip.disabled=true}var ms=Math.max(4000,(Number(c.duration)||10)*1000);
          if(c.type==='image'){var img=document.createElement('img');img.src=c.src;img.alt='Advertisement';if(adMedia)adMedia.appendChild(img);adState.finishTimer=setTimeout(function(){if(adState.active)endAd(true)},ms);img.onerror=function(){endAd(true)}}else{var av=document.createElement('video');av.src=c.src;av.autoplay=true;av.playsInline=true;av.controls=false;av.preload='auto';if(adMedia)adMedia.appendChild(av);adState.adVideo=av;av.onended=function(){endAd(true)};av.onerror=function(){endAd(true)};if(canSkip&&adSkip){adSkip.hidden=false;var left=Math.max(1,Number(ads.skip_after||5));adSkip.textContent='Skip ad in '+left+'s';adState.skipTimer=setInterval(function(){left--;if(left<=0){clearInterval(adState.skipTimer);adState.skipTimer=null;adSkip.textContent='Skip ad';adSkip.disabled=false}else adSkip.textContent='Skip ad in '+left+'s'},1000)}adState.finishTimer=setTimeout(function(){if(adState.active)endAd(true)},Math.max(15000,ms));av.play().catch(function(){endAd(true)})}return true}
        function preAd(){if(!ads||!ads.enabled)return false;var c=ads.pre_roll?chooseAd('pre_roll'):null;if(c)return showAd('pre_roll',c,!!c.skippable);c=ads.bumper?chooseAd('bumper'):null;if(c)return showAd('bumper',c,false);return false}
        function midAd(){if(!ads||!ads.enabled||!ads.midroll||adState.active)return;var c=chooseAd('mid_roll');if(c){lastMid=Math.floor(video.currentTime);showAd('mid_roll',c,!!c.skippable)}}
        function pauseAd(){if(!ads||!ads.enabled||!ads.pause||adState.active)return;var c=chooseAd('pause');if(c)showAd('pause',c,!!c.skippable)}
        function postAd(){if(!ads||!ads.enabled||adState.active)return;var c=ads.post_roll?chooseAd('post_roll'):null;if(c)showAd('post_roll',c,!!c.skippable)}
        if(play)play.onclick=function(){hideError();if(adState.active)return;if(video.paused){showLoading();video.play().catch(function(){showError()})}else video.pause()};
        if(back)back.onclick=function(){video.currentTime=Math.max(0,video.currentTime-(Number(s.seekSeconds)||10));sync()};if(forward)forward.onclick=function(){video.currentTime=Math.min(video.duration||Infinity,video.currentTime+(Number(s.seekSeconds)||10));sync()};
        if(mute)mute.onclick=function(){video.muted=!video.muted;sync()};if(volume)volume.oninput=function(){video.volume=parseFloat(volume.value);video.muted=video.volume===0;sync()};
        if(progress)progress.oninput=function(){if(isFinite(video.duration)&&video.duration>0)video.currentTime=(parseFloat(progress.value)/1000)*video.duration};
        if(speed)speed.onclick=function(){var list=(s.speeds||[.5,.75,1,1.25,1.5,1.75,2]).map(Number),idx=list.indexOf(video.playbackRate);video.playbackRate=list[(idx+1)%list.length];speed.textContent=video.playbackRate+'×'};
        if(theater)theater.onclick=function(){root.classList.toggle('is-theater')};if(pip)pip.onclick=function(){if(document.pictureInPictureEnabled&&document.pictureInPictureElement!==video)video.requestPictureInPicture().catch(function(){});else if(document.exitPictureInPicture)document.exitPictureInPicture().catch(function(){})};if(fs)fs.onclick=function(){if(!document.fullscreenElement){root.requestFullscreen&&root.requestFullscreen().catch(function(){});root.classList.add('is-fullscreen')}else{document.exitFullscreen&&document.exitFullscreen();root.classList.remove('is-fullscreen')}};
        if(s.clickToPlay)video.addEventListener('click',function(){if(adState.active)return;if(video.paused)video.play().catch(function(){});else video.pause()});
        if(s.doubleClickFullscreen)video.addEventListener('dblclick',function(){if(fs)fs.click()});
        if(s.keyboardShortcuts)root.tabIndex=0;root.addEventListener('keydown',function(e){if(!s.keyboardShortcuts||e.target!==root)return;var k=e.key.toLowerCase();if(k===' '||k==='k'){e.preventDefault();if(video.paused)video.play().catch(function(){});else video.pause()}else if(k==='m'){video.muted=!video.muted}else if(k==='f'&&fs){fs.click()}else if(k==='t'&&theater){theater.click()}else if(k==='arrowleft'){video.currentTime=Math.max(0,video.currentTime-(Number(s.seekSeconds)||10))}else if(k==='arrowright'){video.currentTime=Math.min(video.duration||Infinity,video.currentTime+(Number(s.seekSeconds)||10))}else if(k==='arrowup'){video.volume=Math.min(1,video.volume+.05);if(volume)volume.value=video.volume}else if(k==='arrowdown'){video.volume=Math.max(0,video.volume-.05);if(volume)volume.value=video.volume}sync()});
        ['loadstart','waiting'].forEach(function(ev){video.addEventListener(ev,showLoading)});video.addEventListener('canplay',hideLoading);video.addEventListener('loadedmetadata',function(){hideLoading();restorePos();sync()});video.addEventListener('durationchange',sync);video.addEventListener('timeupdate',function(){sync();savePos();if(s.midroll&&isFinite(video.duration)&&video.duration>0&&video.currentTime>0&&Math.floor(video.currentTime/(s.midrollInterval||300))>lastMid){midAd()}});video.addEventListener('play',function(){hideLoading();sync()});video.addEventListener('pause',function(){sync();if(video.currentTime>1&&video.currentTime<(video.duration||Infinity)-1&&Date.now()-lastPause>5000){lastPause=Date.now();pauseAd()}});video.addEventListener('ended',function(){savePos();postAd();sync()});video.addEventListener('error',function(){if(!adState.active)showError()});
        if(s.autoplay){if(s.mutedAutoplay)video.muted=true;video.play().catch(function(){})}
        if(!preShown){preShown=true;if(preAd())sync()}
        sync();
      }
      function boot(root){
        var fallback=root.dataset.settings||'{}';
        try{initPlayer(root,false)}catch(e){}
        var url=root.dataset.settingsUrl;
        if(!url)return;
        var fd=new FormData();fd.append('action','stv_player_settings');
        fetch(url,{method:'POST',credentials:'same-origin',cache:'no-store',body:fd}).then(function(r){return r.json()}).then(function(res){if(res&&res.success&&res.data){root.dataset.settings=JSON.stringify(res.data);root.dataset.ready='0';initPlayer(root,true)}}).catch(function(){});
      }
      function bootAll(){document.querySelectorAll('[data-stv-player]').forEach(boot)}
      if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',bootAll);else bootAll();
    })();
    </script>
    <?php
}
add_action( 'wp_footer', 'smarttoolz_video_player_scripts', 45 );
