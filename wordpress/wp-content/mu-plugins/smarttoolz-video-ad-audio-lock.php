<?php
/**
 * Plugin Name: SmartToolz Video Ad Audio Lock
 * Description: Prevents the main video/player from continuing underneath an active ad.
 * Version: 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'wp_footer', function () {
    if ( 'watch' !== get_query_var( 'smarttoolz_video_route' ) ) return;
    ?>
    <script>
    (function () {
        'use strict';
        function lock(root) {
            var video = root && root.querySelector('.stv-player__video');
            if (!video) return;
            try { if (root._stvPlyr && typeof root._stvPlyr.pause === 'function') root._stvPlyr.pause(); } catch(e) {}
            try { video.pause(); } catch(e) {}
        }
        function hasAd(root) { return root && root.querySelector('.stv-ad-overlay.is-active'); }
        function init(root) {
            if (!root || root.dataset.stvAdLock === '1') return;
            var video = root.querySelector('.stv-player__video');
            if (!video) return;
            root.dataset.stvAdLock = '1';
            video.addEventListener('play', function () { if (hasAd(root)) lock(root); }, true);
            var observer = new MutationObserver(function () { if (hasAd(root)) lock(root); });
            observer.observe(root, { attributes:true, attributeFilter:['class'], subtree:true });
            var timer = setInterval(function () { if (hasAd(root)) lock(root); }, 100);
            window.addEventListener('beforeunload', function () { clearInterval(timer); observer.disconnect(); }, {once:true});
        }
        function scan() { document.querySelectorAll('[data-stv-player]').forEach(init); }
        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', scan); else scan();
        setTimeout(scan,100); setTimeout(scan,500); setTimeout(scan,1500);
    }());
    </script>
    <?php
}, 120 );
