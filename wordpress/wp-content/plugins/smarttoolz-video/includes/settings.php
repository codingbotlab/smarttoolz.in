<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_defaults() {
    return array(
        'autoplay' => 0,
        'muted' => 0,
        'loop' => 0,
        'controls' => 1,
        'preload' => 'metadata',
        'allow_comments' => 1,
        'allow_likes' => 1,
        'allow_sharing' => 1,
        'uploads_enabled' => 1,
        'max_upload_mb' => 1024,
        'accent_color' => '#ff0000',
        'grid_columns' => 4,
    );
}

function smarttoolz_video_get_settings() {
    return wp_parse_args( (array) get_option( 'smarttoolz_video_settings', array() ), smarttoolz_video_defaults() );
}

function smarttoolz_video_setting( $key, $default = null ) {
    $s = smarttoolz_video_get_settings();
    return array_key_exists( $key, $s ) ? $s[ $key ] : $default;
}

function smarttoolz_video_register_settings() {
    register_setting( 'smarttoolz_video_options', 'smarttoolz_video_settings', array(
        'type' => 'array',
        'sanitize_callback' => 'smarttoolz_video_sanitize_settings',
        'default' => smarttoolz_video_defaults(),
    ) );
}
add_action( 'admin_init', 'smarttoolz_video_register_settings' );

function smarttoolz_video_sanitize_settings( $input ) {
    $d = smarttoolz_video_defaults();
    $input = is_array( $input ) ? $input : array();
    $out = $d;
    foreach ( array( 'autoplay','muted','loop','controls','allow_comments','allow_likes','allow_sharing','uploads_enabled' ) as $key ) {
        $out[ $key ] = empty( $input[ $key ] ) ? 0 : 1;
    }
    $out['preload'] = in_array( $input['preload'] ?? '', array( 'none', 'metadata', 'auto' ), true ) ? $input['preload'] : $d['preload'];
    $out['max_upload_mb'] = max( 1, min( 4096, absint( $input['max_upload_mb'] ?? $d['max_upload_mb'] ) ) );
    $out['accent_color'] = sanitize_hex_color( $input['accent_color'] ?? $d['accent_color'] ) ?: $d['accent_color'];
    $out['grid_columns'] = max( 1, min( 6, absint( $input['grid_columns'] ?? $d['grid_columns'] ) ) );
    return $out;
}

function smarttoolz_video_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) { return; }
    $s = smarttoolz_video_get_settings();
    ?>
    <div class="wrap">
        <h1>SmartToolz Video Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields( 'smarttoolz_video_options' ); ?>
            <h2>General</h2>
            <table class="form-table" role="presentation">
                <tr><th scope="row">Uploads</th><td><label><input type="checkbox" name="smarttoolz_video_settings[uploads_enabled]" value="1" <?php checked( $s['uploads_enabled'], 1 ); ?>> Allow frontend video uploads.</label></td></tr>
                <tr><th scope="row">Maximum upload size</th><td><input type="number" min="1" max="4096" name="smarttoolz_video_settings[max_upload_mb]" value="<?php echo esc_attr( $s['max_upload_mb'] ); ?>"> MB</td></tr>
                <tr><th scope="row">Grid columns</th><td><input type="number" min="1" max="6" name="smarttoolz_video_settings[grid_columns]" value="<?php echo esc_attr( $s['grid_columns'] ); ?>"></td></tr>
                <tr><th scope="row">Accent color</th><td><input type="color" name="smarttoolz_video_settings[accent_color]" value="<?php echo esc_attr( $s['accent_color'] ); ?>"></td></tr>
            </table>
            <h2>Player</h2>
            <table class="form-table" role="presentation">
                <?php foreach ( array('autoplay'=>'Autoplay','muted'=>'Start muted','loop'=>'Loop','controls'=>'Player controls','allow_comments'=>'Comments','allow_likes'=>'Likes','allow_sharing'=>'Sharing') as $key => $label ) : ?>
                <tr><th scope="row"><?php echo esc_html( $label ); ?></th><td><input type="checkbox" name="smarttoolz_video_settings[<?php echo esc_attr( $key ); ?>]" value="1" <?php checked( $s[$key], 1 ); ?>></td></tr>
                <?php endforeach; ?>
                <tr><th scope="row">Preload</th><td><select name="smarttoolz_video_settings[preload]"><?php foreach ( array('none'=>'None','metadata'=>'Metadata','auto'=>'Auto') as $v => $label ) : ?><option value="<?php echo esc_attr($v); ?>" <?php selected($s['preload'],$v); ?>><?php echo esc_html($label); ?></option><?php endforeach; ?></select></td></tr>
            </table>
            <?php submit_button( 'Save Video Settings' ); ?>
        </form>
    </div>
    <?php
}

function smarttoolz_video_admin_menu() {
    add_submenu_page( 'edit.php?post_type=st_video', 'Video Settings', 'Video Settings', 'manage_options', 'smarttoolz-video-settings', 'smarttoolz_video_settings_page' );
}
add_action( 'admin_menu', 'smarttoolz_video_admin_menu', 30 );

function smarttoolz_video_settings_runtime_css() {
    if ( ! function_exists( 'smarttoolz_video_is_frontend' ) || ! smarttoolz_video_is_frontend() ) { return; }
    $accent = sanitize_hex_color( smarttoolz_video_setting( 'accent_color', '#ff0000' ) ) ?: '#ff0000';
    $cols = max( 1, min( 6, absint( smarttoolz_video_setting( 'grid_columns', 4 ) ) ) );
    echo '<style id="smarttoolz-video-runtime-settings">.smarttoolz-video-page{--stv-accent:' . esc_attr( $accent ) . ';--stv-cols:' . esc_attr( $cols ) . '}.stv-video-grid{grid-template-columns:repeat(var(--stv-cols),minmax(0,1fr))}@media(max-width:900px){.stv-video-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:520px){.stv-video-grid{grid-template-columns:1fr}}</style>';
}
add_action( 'wp_head', 'smarttoolz_video_settings_runtime_css', 90 );
