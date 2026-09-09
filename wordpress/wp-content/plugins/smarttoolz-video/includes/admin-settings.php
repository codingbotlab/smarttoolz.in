<?php
/**
 * SmartToolz Video administrator settings, feature controls and setup guide.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_default_settings() {
    return array(
        'uploads_enabled'        => 1,
        'upload_status'          => 'publish',
        'upload_max_mb'          => 1024,
        'allowed_formats'        => 'mp4,webm,ogg',
        'require_thumbnail'      => 0,
        'likes_enabled'          => 1,
        'dislikes_enabled'       => 1,
        'subscriptions_enabled'  => 1,
        'history_enabled'        => 1,
        'sharing_enabled'        => 1,
        'comments_enabled'       => 1,
        'autoplay'               => 0,
        'muted'                  => 0,
        'loop'                   => 0,
        'preload'                => 'metadata',
        'volume'                 => 1,
        'speed'                  => 1,
        'theater'                => 1,
        'pip'                    => 1,
        'double_click_fullscreen'=> 1,
        'accent_color'           => '#ff0000',
        'icon_enabled'           => 1,
        'icon_cdn_url'           => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css',
        'google_enabled'         => 1,
        'google_client_id'       => '',
        'frontend_profile'       => 1,
        'page_columns'           => 4,
    );
}

function smarttoolz_video_settings() {
    return wp_parse_args( (array) get_option( 'smarttoolz_video_settings', array() ), smarttoolz_video_default_settings() );
}

function smarttoolz_video_setting( $key, $default = null ) {
    $settings = smarttoolz_video_settings();
    return array_key_exists( $key, $settings ) ? $settings[ $key ] : $default;
}

function smarttoolz_video_sanitize_settings( $input ) {
    $defaults = smarttoolz_video_default_settings();
    $out = $defaults;
    $input = is_array( $input ) ? $input : array();
    $bools = array( 'uploads_enabled','require_thumbnail','likes_enabled','dislikes_enabled','subscriptions_enabled','history_enabled','sharing_enabled','comments_enabled','autoplay','muted','loop','theater','pip','double_click_fullscreen','icon_enabled','google_enabled','frontend_profile' );
    foreach ( $bools as $key ) { $out[ $key ] = empty( $input[ $key ] ) ? 0 : 1; }
    $out['upload_status'] = in_array( $input['upload_status'] ?? '', array( 'publish', 'pending', 'draft' ), true ) ? $input['upload_status'] : $defaults['upload_status'];
    $out['upload_max_mb'] = max( 1, min( 10240, absint( $input['upload_max_mb'] ?? $defaults['upload_max_mb'] ) ) );
    $formats = isset( $input['allowed_formats'] ) ? strtolower( sanitize_text_field( $input['allowed_formats'] ) ) : $defaults['allowed_formats'];
    $formats = preg_replace( '/[^a-z0-9,]/', '', $formats );
    $allowed = array_intersect( array( 'mp4', 'webm', 'ogg' ), array_filter( array_map( 'trim', explode( ',', $formats ) ) ) );
    $out['allowed_formats'] = implode( ',', $allowed ?: array( 'mp4', 'webm', 'ogg' ) );
    $out['preload'] = in_array( $input['preload'] ?? '', array( 'auto', 'metadata', 'none' ), true ) ? $input['preload'] : $defaults['preload'];
    $out['volume'] = max( 0, min( 1, (float) ( $input['volume'] ?? $defaults['volume'] ) ) );
    $out['speed'] = in_array( (string) ( $input['speed'] ?? '' ), array( '0.5','0.75','1','1.25','1.5','1.75','2' ), true ) ? (float) $input['speed'] : 1;
    $out['accent_color'] = sanitize_hex_color( $input['accent_color'] ?? $defaults['accent_color'] ) ?: $defaults['accent_color'];
    $out['icon_cdn_url'] = esc_url_raw( $input['icon_cdn_url'] ?? $defaults['icon_cdn_url'] );
    if ( ! $out['icon_cdn_url'] ) { $out['icon_cdn_url'] = $defaults['icon_cdn_url']; }
    $out['google_client_id'] = sanitize_text_field( $input['google_client_id'] ?? '' );
    $out['page_columns'] = in_array( absint( $input['page_columns'] ?? 4 ), array( 2,3,4,5 ), true ) ? absint( $input['page_columns'] ) : 4;
    return $out;
}

function smarttoolz_video_register_main_settings() {
    register_setting( 'smarttoolz_video_options', 'smarttoolz_video_settings', array( 'type' => 'array', 'sanitize_callback' => 'smarttoolz_video_sanitize_settings', 'default' => smarttoolz_video_default_settings() ) );
}
add_action( 'admin_init', 'smarttoolz_video_register_main_settings' );

function smarttoolz_video_settings_menu() {
    add_submenu_page( 'edit.php?post_type=st_video', 'Video Settings', 'Video Settings', 'manage_options', 'smarttoolz-video-settings', 'smarttoolz_video_settings_page' );
    add_submenu_page( 'edit.php?post_type=st_video', 'Setup Guide', 'Setup Guide', 'manage_options', 'smarttoolz-video-guide', 'smarttoolz_video_setup_guide_page' );
}
add_action( 'admin_menu', 'smarttoolz_video_settings_menu', 30 );

function smarttoolz_video_settings_action() {
    if ( ! current_user_can( 'manage_options' ) ) { wp_die( 'Permission denied.' ); }
    check_admin_referer( 'smarttoolz_video_sync_pages' );
    if ( function_exists( 'smarttoolz_video_create_managed_pages' ) ) { smarttoolz_video_create_managed_pages(); }
    wp_safe_redirect( add_query_arg( array( 'post_type' => 'st_video', 'page' => 'smarttoolz-video-settings', 'tab' => 'pages', 'stv_notice' => 'synced' ), admin_url( 'edit.php' ) ) );
    exit;
}
add_action( 'admin_post_smarttoolz_video_sync_pages', 'smarttoolz_video_settings_action' );

function smarttoolz_video_admin_help_card( $title, $text ) {
    echo '<div class="stv-admin-help"><strong>' . esc_html( $title ) . '</strong><p>' . esc_html( $text ) . '</p></div>';
}

function smarttoolz_video_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) { return; }
    $s = smarttoolz_video_settings();
    $tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'general';
    $tabs = array( 'general' => 'General', 'player' => 'Player', 'icons' => 'Icons', 'auth' => 'Login & Register', 'uploads' => 'Uploads', 'features' => 'Features', 'pages' => 'Pages' );
    ?>
    <div class="wrap stv-admin-wrap">
      <h1>SmartToolz Video Settings</h1>
      <p class="description">Control the video platform from the WordPress backend. Settings below are used by the frontend video pages, player, upload flow and authentication UI.</p>
      <nav class="nav-tab-wrapper">
        <?php foreach ( $tabs as $key => $label ) : ?><a class="nav-tab <?php echo $tab === $key ? 'nav-tab-active' : ''; ?>" href="<?php echo esc_url( admin_url( 'edit.php?post_type=st_video&page=smarttoolz-video-settings&tab=' . $key ) ); ?>"><?php echo esc_html( $label ); ?></a><?php endforeach; ?>
        <a class="nav-tab" href="<?php echo esc_url( admin_url( 'edit.php?post_type=st_video&page=smarttoolz-video-guide' ) ); ?>">Setup Guide</a>
      </nav>
      <?php if ( 'synced' === ( $_GET['stv_notice'] ?? '' ) ) : ?><div class="notice notice-success is-dismissible"><p>Managed video pages synchronized.</p></div><?php endif; ?>
      <form method="post" action="options.php">
        <?php settings_fields( 'smarttoolz_video_options' ); ?>
        <table class="form-table" role="presentation">
        <?php if ( 'general' === $tab ) : ?>
          <tr><th scope="row">Frontend profile menu</th><td><label><input type="checkbox" name="smarttoolz_video_settings[frontend_profile]" value="1" <?php checked( $s['frontend_profile'], 1 ); ?>> Show the account/profile dropdown on video pages.</label><p class="description">The dropdown contains profile, channel, upload, creator studio, subscriptions, liked videos and watch history links.</p></td></tr>
          <tr><th scope="row">Video grid columns</th><td><select name="smarttoolz_video_settings[page_columns]"><?php foreach ( array(2,3,4,5) as $n ) : ?><option value="<?php echo esc_attr($n); ?>" <?php selected($s['page_columns'],$n); ?>><?php echo esc_html($n); ?> columns</option><?php endforeach; ?></select><p class="description">Desktop card count used by the platform frontend. Mobile layouts remain responsive.</p></td></tr>
          <tr><th scope="row">Accent color</th><td><input type="color" name="smarttoolz_video_settings[accent_color]" value="<?php echo esc_attr($s['accent_color']); ?>"><p class="description">Used for player progress, upload buttons and active video accents.</p></td></tr>
          <?php smarttoolz_video_admin_help_card( 'What to change here', 'Start with the accent color and grid columns. Leave the defaults if you simply want a YouTube-style layout without custom branding.' ); ?>
        <?php elseif ( 'player' === $tab ) : ?>
          <tr><th scope="row">Autoplay</th><td><label><input type="checkbox" name="smarttoolz_video_settings[autoplay]" value="1" <?php checked($s['autoplay'],1); ?>> Start videos automatically.</label><p class="description">Browser policies can block autoplay, especially when audio is enabled.</p></td></tr>
          <tr><th scope="row">Start muted</th><td><label><input type="checkbox" name="smarttoolz_video_settings[muted]" value="1" <?php checked($s['muted'],1); ?>> Start videos muted.</label></td></tr>
          <tr><th scope="row">Loop</th><td><label><input type="checkbox" name="smarttoolz_video_settings[loop]" value="1" <?php checked($s['loop'],1); ?>> Repeat the video.</label></td></tr>
          <tr><th scope="row">Preload</th><td><select name="smarttoolz_video_settings[preload]"><option value="auto" <?php selected($s['preload'],'auto'); ?>>Auto</option><option value="metadata" <?php selected($s['preload'],'metadata'); ?>>Metadata</option><option value="none" <?php selected($s['preload'],'none'); ?>>None</option></select><p class="description">Metadata is the safer default for page performance.</p></td></tr>
          <tr><th scope="row">Default volume</th><td><input type="number" step="0.05" min="0" max="1" name="smarttoolz_video_settings[volume]" value="<?php echo esc_attr($s['volume']); ?>"><p class="description">0.0 to 1.0. This is applied when the browser allows script-controlled volume.</p></td></tr>
          <tr><th scope="row">Initial speed</th><td><select name="smarttoolz_video_settings[speed]"><?php foreach (array('0.5','0.75','1','1.25','1.5','1.75','2') as $v): ?><option value="<?php echo esc_attr($v); ?>" <?php selected((string)$s['speed'],$v); ?>><?php echo esc_html($v); ?>x</option><?php endforeach; ?></select></td></tr>
          <tr><th scope="row">Theater mode</th><td><label><input type="checkbox" name="smarttoolz_video_settings[theater]" value="1" <?php checked($s['theater'],1); ?>> Allow the theater button.</label></td></tr>
          <tr><th scope="row">Picture-in-picture</th><td><label><input type="checkbox" name="smarttoolz_video_settings[pip]" value="1" <?php checked($s['pip'],1); ?>> Allow PiP when the browser supports it.</label></td></tr>
          <tr><th scope="row">Double-click fullscreen</th><td><label><input type="checkbox" name="smarttoolz_video_settings[double_click_fullscreen]" value="1" <?php checked($s['double_click_fullscreen'],1); ?>> Double-click the player to enter fullscreen.</label></td></tr>
          <?php smarttoolz_video_admin_help_card( 'Player setup', 'Use Auto + muted when you want an autoplay experience. Use Metadata + autoplay off for a normal YouTube-style user-controlled player.' ); ?>
        <?php elseif ( 'icons' === $tab ) : ?>
          <tr><th scope="row">Icon library</th><td><label><input type="checkbox" name="smarttoolz_video_settings[icon_enabled]" value="1" <?php checked($s['icon_enabled'],1); ?>> Load the configured icon library on video pages.</label><p class="description">The current frontend uses Font Awesome Free class names such as fa-house, fa-video and fa-magnifying-glass.</p></td></tr>
          <tr><th scope="row">Icon library CSS URL</th><td><input type="url" class="regular-text code" name="smarttoolz_video_settings[icon_cdn_url]" value="<?php echo esc_attr($s['icon_cdn_url']); ?>"><p class="description">Default: Font Awesome Free CDN. Replace this only with a compatible CSS icon library that provides the same classes, or change the frontend icon markup too.</p></td></tr>
          <?php smarttoolz_video_admin_help_card( 'Third-party icon setup', 'The default URL is a public Font Awesome Free stylesheet. No API key is required. Keeping this enabled gives the video navigation and account dropdown their icons.' ); ?>
        <?php elseif ( 'auth' === $tab ) : ?>
          <tr><th scope="row">Google sign-in</th><td><label><input type="checkbox" name="smarttoolz_video_settings[google_enabled]" value="1" <?php checked($s['google_enabled'],1); ?>> Enable the Google sign-in UI.</label><p class="description">Google login also requires a valid Web OAuth Client ID below.</p></td></tr>
          <tr><th scope="row">Google OAuth Web Client ID</th><td><input type="text" class="regular-text code" name="smarttoolz_video_settings[google_client_id]" value="<?php echo esc_attr($s['google_client_id']); ?>" placeholder="1234567890-xxxxxxxx.apps.googleusercontent.com"><p class="description">Create a Google Cloud OAuth Client of type Web application. Add your site origin as an authorized JavaScript origin. Do not paste a Google client secret here.</p></td></tr>
          <tr><th scope="row">Registration</th><td><p class="description">WordPress registration is enabled by the authentication module while the SmartToolz login screen is active.</p></td></tr>
          <?php smarttoolz_video_admin_help_card( 'Google setup steps', '1) Google Cloud Console → APIs & Services → Credentials. 2) Create OAuth client → Web application. 3) Add https://smarttoolz.in as an authorized JavaScript origin. 4) Copy the Client ID here. 5) Save. 6) Open the SmartToolz login screen and test Continue with Google.' ); ?>
        <?php elseif ( 'uploads' === $tab ) : ?>
          <tr><th scope="row">Frontend uploads</th><td><label><input type="checkbox" name="smarttoolz_video_settings[uploads_enabled]" value="1" <?php checked($s['uploads_enabled'],1); ?>> Allow eligible logged-in users to publish videos from the frontend.</label></td></tr>
          <tr><th scope="row">Default upload status</th><td><select name="smarttoolz_video_settings[upload_status]"><option value="publish" <?php selected($s['upload_status'],'publish'); ?>>Published</option><option value="pending" <?php selected($s['upload_status'],'pending'); ?>>Pending review</option><option value="draft" <?php selected($s['upload_status'],'draft'); ?>>Draft</option></select><p class="description">The current uploader publishes by default; setting Pending or Draft is useful when moderation is required.</p></td></tr>
          <tr><th scope="row">Max upload size (MB)</th><td><input type="number" min="1" max="10240" name="smarttoolz_video_settings[upload_max_mb]" value="<?php echo esc_attr($s['upload_max_mb']); ?>"><p class="description">This cannot exceed the PHP/Hostinger server upload limits. Example: 1024 = 1 GB.</p></td></tr>
          <tr><th scope="row">Allowed formats</th><td><input type="text" class="regular-text" name="smarttoolz_video_settings[allowed_formats]" value="<?php echo esc_attr($s['allowed_formats']); ?>"><p class="description">Supported values are mp4, webm and ogg. Separate with commas.</p></td></tr>
          <tr><th scope="row">Require thumbnail</th><td><label><input type="checkbox" name="smarttoolz_video_settings[require_thumbnail]" value="1" <?php checked($s['require_thumbnail'],1); ?>> Require a custom or generated thumbnail before publishing.</label></td></tr>
          <?php smarttoolz_video_admin_help_card( 'Upload workflow', '1) User signs in. 2) Opens Upload Video. 3) Selects a video. 4) Generates three frames from the browser. 5) Chooses one frame or uploads a custom image. 6) Publishes according to the status setting.' ); ?>
        <?php elseif ( 'features' === $tab ) : ?>
          <tr><th scope="row">Likes</th><td><label><input type="checkbox" name="smarttoolz_video_settings[likes_enabled]" value="1" <?php checked($s['likes_enabled'],1); ?>> Enable likes.</label></td></tr>
          <tr><th scope="row">Dislikes</th><td><label><input type="checkbox" name="smarttoolz_video_settings[dislikes_enabled]" value="1" <?php checked($s['dislikes_enabled'],1); ?>> Enable dislikes.</label></td></tr>
          <tr><th scope="row">Subscriptions</th><td><label><input type="checkbox" name="smarttoolz_video_settings[subscriptions_enabled]" value="1" <?php checked($s['subscriptions_enabled'],1); ?>> Enable channel subscriptions.</label></td></tr>
          <tr><th scope="row">Watch history</th><td><label><input type="checkbox" name="smarttoolz_video_settings[history_enabled]" value="1" <?php checked($s['history_enabled'],1); ?>> Save watch history for logged-in users.</label></td></tr>
          <tr><th scope="row">Sharing</th><td><label><input type="checkbox" name="smarttoolz_video_settings[sharing_enabled]" value="1" <?php checked($s['sharing_enabled'],1); ?>> Show sharing/copy controls.</label></td></tr>
          <tr><th scope="row">Comments</th><td><label><input type="checkbox" name="smarttoolz_video_settings[comments_enabled]" value="1" <?php checked($s['comments_enabled'],1); ?>> Allow comments on videos.</label></td></tr>
          <?php smarttoolz_video_admin_help_card( 'Feature control', 'Turn a feature off here when you do not want it available to users. The setting is checked server-side for the major interaction endpoints and also reflected in the frontend UI.' ); ?>
        <?php elseif ( 'pages' === $tab ) : ?>
          <tr><th scope="row">Managed video pages</th><td><p>SmartToolz creates dedicated pages for Video Home, Videos, Trending, Categories, Search, Subscriptions, Liked Videos, Watch History, Upload Video, Creator Studio and Channel.</p><a class="button button-secondary" href="<?php echo esc_url( wp_nonce_url( admin_url('admin-post.php?action=smarttoolz_video_sync_pages'), 'smarttoolz_video_sync_pages' ) ); ?>">Sync / repair video pages</a></td></tr>
          <?php foreach ( function_exists('smarttoolz_video_auto_page_definitions') ? smarttoolz_video_auto_page_definitions() : array() as $slug => $definition ) : $url = function_exists('smarttoolz_video_page_link') ? smarttoolz_video_page_link($slug) : ''; ?><tr><th scope="row"><?php echo esc_html($definition['title']); ?></th><td><code><?php echo esc_html($slug); ?></code> <?php if($url): ?> — <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener">Open page</a><?php endif; ?></td></tr><?php endforeach; ?>
          <?php smarttoolz_video_admin_help_card( 'Do not delete these pages blindly', 'The platform navigation and profile dropdown depend on their page IDs. Use the Sync / repair button after moving a site, restoring a database, or accidentally deleting a managed page.' ); ?>
        <?php endif; ?>
        </table>
        <?php submit_button( 'Save Video Settings' ); ?>
      </form>
    </div>
    <?php
}

function smarttoolz_video_setup_guide_page() {
    if ( ! current_user_can( 'manage_options' ) ) { return; }
    ?>
    <div class="wrap stv-guide-wrap">
      <h1>SmartToolz Video — Complete Setup Guide</h1>
      <p class="description">Follow this order once after installing the plugin. After that, day-to-day control is done from SmartToolz Videos → Video Settings.</p>
      <div class="stv-guide-grid">
        <section><h2>1. General</h2><ol><li>Open <strong>SmartToolz Videos → Video Settings → General</strong>.</li><li>Choose your grid columns and accent color.</li><li>Keep Frontend profile menu enabled so users can access their video account tools from the header dropdown.</li></ol></section>
        <section><h2>2. Player</h2><ol><li>Open the <strong>Player</strong> tab.</li><li>Choose autoplay, muted, loop and preload behavior.</li><li>Set the starting volume and speed.</li><li>Enable or disable theater mode, PiP and double-click fullscreen.</li></ol></section>
        <section><h2>3. Icon library</h2><ol><li>Open <strong>Icons</strong>.</li><li>Keep Font Awesome Free enabled for the current frontend markup.</li><li>Only replace the URL when you know the replacement library provides the same class names, or after updating the frontend markup.</li></ol></section>
        <section><h2>4. Login / Register</h2><ol><li>Open <strong>Login & Register</strong>.</li><li>Enable Google sign-in.</li><li>Create a Google Cloud OAuth Web Client ID.</li><li>Add your SmartToolz site origin to Authorized JavaScript origins.</li><li>Paste the Client ID into the field and save.</li><li>Test both the WordPress login screen and SmartToolz profile dropdown.</li></ol></section>
        <section><h2>5. Uploads</h2><ol><li>Open <strong>Uploads</strong>.</li><li>Enable frontend uploads.</li><li>Set the moderation status: Published, Pending review or Draft.</li><li>Set an upload limit that does not exceed the host/PHP limit.</li><li>Choose supported formats.</li><li>Decide whether a thumbnail is mandatory.</li></ol></section>
        <section><h2>6. Features</h2><ol><li>Enable the interactions you want: Likes, Dislikes, Subscriptions, History, Sharing and Comments.</li><li>Disable anything you do not want exposed.</li><li>Test from a second user account so private libraries and subscription feeds are verified per user.</li></ol></section>
        <section><h2>7. Pages</h2><ol><li>Open <strong>Pages</strong>.</li><li>Confirm every managed page has an Open page link.</li><li>Use <strong>Sync / repair video pages</strong> after migration, restoration or accidental deletion.</li></ol></section>
        <section><h2>8. Daily creator workflow</h2><ol><li>Sign in.</li><li>Open Profile → Upload Video.</li><li>Select video → generate 3 thumbnails → choose one.</li><li>Add title, description and category in the creator editor.</li><li>Publish or send for review.</li><li>Open the video page and use Edit video later to replace the video or thumbnail.</li></ol></section>
      </div>
      <div class="stv-guide-note"><strong>Third-party notes</strong><p>Google sign-in needs a Google Cloud Web Client ID. Font Awesome Free is loaded as a public stylesheet and does not need an API key. Video thumbnail frame generation is performed in the visitor's browser for uploaded local video files; external video URLs may be restricted by browser cross-origin security.</p></div>
    </div>
    <?php
}

function smarttoolz_video_settings_frontend_css() {
    if ( ! function_exists( 'smarttoolz_video_should_load_player_assets' ) || ! smarttoolz_video_should_load_player_assets() ) { return; }
    $s = smarttoolz_video_settings();
    echo '<style id="smarttoolz-video-admin-settings-css">body.smarttoolz-video-platform{--stv-admin-accent:' . esc_attr( $s['accent_color'] ) . ';} body.smarttoolz-video-platform .stv-publish-button,body.smarttoolz-video-platform .stv-upload-cta{background:' . esc_attr( $s['accent_color'] ) . ';} body.smarttoolz-video-platform .stv-custom-player{--stv-progress:' . esc_attr( $s['accent_color'] ) . ';}</style>';
}
add_action( 'wp_head', 'smarttoolz_video_settings_frontend_css', 30 );

function smarttoolz_video_admin_settings_css() {
    $screen = function_exists('get_current_screen') ? get_current_screen() : null;
    if ( ! $screen || false === strpos( (string) $screen->id, 'smarttoolz-video' ) ) { return; }
    echo '<style>.stv-admin-wrap{max-width:1180px}.stv-admin-wrap .form-table th{width:260px}.stv-admin-help{margin:20px 0;padding:14px 16px;border-left:4px solid #ff0000;background:#fff;border:1px solid #e5e5e5}.stv-admin-help strong{display:block}.stv-admin-help p{margin:6px 0 0;color:#646970}.stv-guide-wrap{max-width:1180px}.stv-guide-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px;margin-top:24px}.stv-guide-grid section{background:#fff;border:1px solid #dcdcde;border-radius:6px;padding:20px}.stv-guide-grid h2{margin-top:0}.stv-guide-grid li{margin-bottom:7px}.stv-guide-note{margin-top:20px;padding:18px;background:#f6f7f7;border:1px solid #dcdcde}.stv-guide-note p{margin-bottom:0}@media(max-width:800px){.stv-guide-grid{grid-template-columns:1fr}}</style>';
}
add_action( 'admin_head', 'smarttoolz_video_admin_settings_css' );

/** Apply feature switches to existing platform callbacks. */
function smarttoolz_video_settings_feature_hooks() {
    if ( ! smarttoolz_video_setting( 'history_enabled', 1 ) ) { remove_action( 'template_redirect', 'stv_record_watch_history', 5 ); }
    if ( ! smarttoolz_video_setting( 'comments_enabled', 1 ) ) { add_filter( 'comments_open', function( $open, $post_id ) { return 'st_video' === get_post_type( $post_id ) ? false : $open; }, 20, 2 ); }
}
add_action( 'init', 'smarttoolz_video_settings_feature_hooks', 20 );

function smarttoolz_video_settings_upload_guard() {
    if ( ! smarttoolz_video_setting( 'uploads_enabled', 1 ) && ! empty( $_POST['st_video_frontend_action'] ) ) {
        wp_safe_redirect( add_query_arg( 'st_video_upload', 'disabled', wp_get_referer() ?: home_url( '/' ) ) );
        exit;
    }
}
add_action( 'template_redirect', 'smarttoolz_video_settings_upload_guard', 0 );

function smarttoolz_video_settings_ajax_guards() {
    if ( ! smarttoolz_video_setting( 'likes_enabled', 1 ) && isset( $_POST['type'] ) && 'like' === sanitize_key( wp_unslash( $_POST['type'] ) ) ) { wp_send_json_error( array( 'message' => 'Likes are disabled.' ), 403 ); }
    if ( ! smarttoolz_video_setting( 'dislikes_enabled', 1 ) && isset( $_POST['type'] ) && 'dislike' === sanitize_key( wp_unslash( $_POST['type'] ) ) ) { wp_send_json_error( array( 'message' => 'Dislikes are disabled.' ), 403 ); }
}
add_action( 'wp_ajax_stv_reaction', 'smarttoolz_video_settings_ajax_guards', 1 );
add_action( 'wp_ajax_nopriv_stv_reaction', 'smarttoolz_video_settings_ajax_guards', 1 );

function smarttoolz_video_settings_subscribe_guard() {
    if ( ! smarttoolz_video_setting( 'subscriptions_enabled', 1 ) ) { wp_send_json_error( array( 'message' => 'Subscriptions are disabled.' ), 403 ); }
}
add_action( 'wp_ajax_stv_subscribe', 'smarttoolz_video_settings_subscribe_guard', 1 );
add_action( 'wp_ajax_nopriv_stv_subscribe', 'smarttoolz_video_settings_subscribe_guard', 1 );

function smarttoolz_video_settings_enqueue_icons() {
    if ( ! smarttoolz_video_setting( 'icon_enabled', 1 ) || ! function_exists( 'smarttoolz_video_should_load_player_assets' ) || ! smarttoolz_video_should_load_player_assets() ) { return; }
    $url = smarttoolz_video_setting( 'icon_cdn_url', '' );
    if ( $url ) { wp_enqueue_style( 'smarttoolz-video-icons', $url, array(), SMARTTOOLZ_VIDEO_VERSION . '.icons1' ); }
}
add_action( 'wp_enqueue_scripts', 'smarttoolz_video_settings_enqueue_icons', 8 );
