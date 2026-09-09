<?php
/**
 * Plugin Name: SmartToolz Video Player Mode
 * Description: Adds an HTML5/native vs JS player selector to SmartToolz Video Player Settings and applies the selected mode on watch pages.
 * Version: 1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_player_mode_defaults() {
    return array( 'mode' => 'js' );
}

function smarttoolz_video_player_mode_get() {
    $settings = wp_parse_args(
        (array) get_option( 'smarttoolz_video_player_mode_settings', array() ),
        smarttoolz_video_player_mode_defaults()
    );
    return in_array( $settings['mode'], array( 'html', 'js' ), true ) ? $settings['mode'] : 'js';
}

function smarttoolz_video_player_mode_sanitize( $input ) {
    $input = is_array( $input ) ? $input : array();
    return array(
        'mode' => ( isset( $input['mode'] ) && 'html' === sanitize_key( $input['mode'] ) ) ? 'html' : 'js',
    );
}

function smarttoolz_video_player_mode_register_setting() {
    register_setting(
        'smarttoolz_video_player_group',
        'smarttoolz_video_player_mode_settings',
        array( 'sanitize_callback' => 'smarttoolz_video_player_mode_sanitize' )
    );
}
add_action( 'admin_init', 'smarttoolz_video_player_mode_register_setting' );

function smarttoolz_video_player_mode_admin_ui() {
    if ( ! current_user_can( 'manage_options' ) || ! isset( $_GET['page'] ) || 'smarttoolz-video-player' !== sanitize_key( wp_unslash( $_GET['page'] ) ) ) {
        return;
    }
    $mode = smarttoolz_video_player_mode_get();
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.querySelector('form[action="options.php"]');
        if (!form) return;
        var table = form.querySelector('.form-table');
        if (!table || table.querySelector('.stv-player-mode-row')) return;

        var row = document.createElement('tr');
        row.className = 'stv-player-mode-row';
        row.innerHTML = '<th scope="row">Player type</th>' +
            '<td>' +
            '<label><input type="radio" name="smarttoolz_video_player_mode_settings[mode]" value="js" <?php echo checked( $mode, 'js', false ); ?>> JS Player</label> &nbsp; ' +
            '<label><input type="radio" name="smarttoolz_video_player_mode_settings[mode]" value="html" <?php echo checked( $mode, 'html', false ); ?>> HTML Player</label>' +
            '<p class="description">JS Player uses the YouTube-style SmartToolz controls. HTML Player uses the browser's native HTML5 video controls. Video ads remain supported by the SmartToolz ad system.</p>' +
            '</td>';
        table.insertBefore(row, table.firstElementChild);
    });
    </script>
    <?php
}
add_action( 'admin_footer', 'smarttoolz_video_player_mode_admin_ui', 20 );

function smarttoolz_video_player_mode_frontend() {
    if ( 'watch' !== get_query_var( 'smarttoolz_video_route' ) ) {
        return;
    }
    $mode = smarttoolz_video_player_mode_get();
    ?>
    <script>
    (function () {
        var selectedMode = <?php echo wp_json_encode( $mode ); ?>;
        function applyPlayerMode() {
            document.querySelectorAll('[data-stv-player]').forEach(function (root) {
                var video = root.querySelector('.stv-player__video');
                if (!video) return;
                var controls = root.querySelector('.stv-player__controls');
                var isHtml = selectedMode === 'html';
                video.controls = isHtml;
                root.classList.toggle('stv-player--html', isHtml);
                root.classList.toggle('stv-player--js', !isHtml);
                if (controls) controls.hidden = isHtml;
            });
        }
        function boot() {
            applyPlayerMode();
            setTimeout(applyPlayerMode, 100);
            setTimeout(applyPlayerMode, 500);
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', boot);
        } else {
            boot();
        }
    })();
    </script>
    <style>
        .stv-player--html .stv-player__controls { display:none !important; }
        .stv-player--html .stv-player__video { cursor:default; }
        .stv-player--html .stv-player__ad { z-index:5; }
    </style>
    <?php
}
add_action( 'wp_footer', 'smarttoolz_video_player_mode_frontend', 45 );
