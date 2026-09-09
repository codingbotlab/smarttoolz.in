<?php
/**
 * SmartToolz video advertising setup and runtime configuration.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function smarttoolz_video_ads_defaults() {
    return array(
        'enabled' => 0,
        'pre_roll' => 1,
        'bumper' => 1,
        'midroll' => 1,
        'post_roll' => 1,
        'pause' => 1,
        'skip_after' => 5,
        'midroll_interval' => 300,
        'pause_cooldown' => 60000,
        'creatives' => array(
            'pre_roll' => array(),
            'bumper' => array(),
            'mid_roll' => array(),
            'post_roll' => array(),
            'pause' => array(),
        ),
    );
}

function smarttoolz_video_ads_settings() {
    return wp_parse_args(
        (array) get_option( 'smarttoolz_video_ads_settings', array() ),
        smarttoolz_video_ads_defaults()
    );
}

function smarttoolz_video_ads_register_settings() {
    register_setting(
        'smarttoolz_video_ads_group',
        'smarttoolz_video_ads_settings',
        array( 'sanitize_callback' => 'smarttoolz_video_ads_sanitize_settings' )
    );
}
add_action( 'admin_init', 'smarttoolz_video_ads_register_settings' );

function smarttoolz_video_ads_sanitize_settings( $input ) {
    $old = smarttoolz_video_ads_settings();
    $input = is_array( $input ) ? $input : array();

    return array(
        'enabled' => empty( $input['enabled'] ) ? 0 : 1,
        'pre_roll' => empty( $input['pre_roll'] ) ? 0 : 1,
        'bumper' => empty( $input['bumper'] ) ? 0 : 1,
        'midroll' => empty( $input['midroll'] ) ? 0 : 1,
        'post_roll' => empty( $input['post_roll'] ) ? 0 : 1,
        'pause' => empty( $input['pause'] ) ? 0 : 1,
        'skip_after' => max( 1, min( 15, absint( $input['skip_after'] ?? 5 ) ) ),
        'midroll_interval' => max( 60, min( 1800, absint( $input['midroll_interval'] ?? 300 ) ) ),
        'pause_cooldown' => max( 15000, min( 600000, absint( $input['pause_cooldown'] ?? 60000 ) ) ),
        'creatives' => isset( $old['creatives'] ) && is_array( $old['creatives'] )
            ? $old['creatives']
            : smarttoolz_video_ads_defaults()['creatives'],
    );
}

function smarttoolz_video_ads_admin_menu() {
    add_submenu_page(
        'smarttoolz',
        'Ads Setup',
        'Ads Setup',
        'manage_options',
        'smarttoolz-video-ads',
        'smarttoolz_video_ads_settings_page'
    );
}
add_action( 'admin_menu', 'smarttoolz_video_ads_admin_menu', 27 );

function smarttoolz_video_ads_upload_mimes( $mimes ) {
    $mimes['mp4'] = 'video/mp4';
    $mimes['webm'] = 'video/webm';
    $mimes['ogv'] = 'video/ogg';
    $mimes['mov'] = 'video/quicktime';
    $mimes['jpg'] = 'image/jpeg';
    $mimes['jpeg'] = 'image/jpeg';
    $mimes['png'] = 'image/png';
    $mimes['webp'] = 'image/webp';
    return $mimes;
}

function smarttoolz_video_ads_handle_upload( $field, $kind ) {
    if ( empty( $_FILES[ $field ]['name'] ) ) {
        return null;
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $allowed = array(
        'video/mp4' => 'mp4',
        'video/webm' => 'webm',
        'video/ogg' => 'ogv',
        'video/quicktime' => 'mov',
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    );

    add_filter( 'upload_mimes', 'smarttoolz_video_ads_upload_mimes' );
    $upload = wp_handle_upload(
        $_FILES[ $field ],
        array(
            'test_form' => false,
            'mimes' => $allowed,
        )
    );
    remove_filter( 'upload_mimes', 'smarttoolz_video_ads_upload_mimes' );

    if ( isset( $upload['error'] ) ) {
        return new WP_Error( 'ad_upload_error', $upload['error'] );
    }

    $file_type = wp_check_filetype( basename( $upload['file'] ), $allowed );
    $is_image = 0 === strpos( (string) $file_type['type'], 'image/' );

    $attachment = wp_insert_attachment(
        array(
            'post_mime_type' => $file_type['type'],
            'post_title' => sanitize_text_field( pathinfo( $upload['file'], PATHINFO_FILENAME ) ),
            'post_status' => 'inherit',
        ),
        $upload['file']
    );

    if ( is_wp_error( $attachment ) ) {
        @unlink( $upload['file'] );
        return $attachment;
    }

    if ( $is_image ) {
        wp_update_attachment_metadata(
            $attachment,
            wp_generate_attachment_metadata( $attachment, $upload['file'] )
        );
    }

    return array(
        'src' => esc_url_raw( $upload['url'] ),
        'type' => $is_image ? 'image' : 'video',
        'title' => sanitize_text_field( $_POST[ $kind . '_title' ] ?? '' ),
        'text' => sanitize_text_field( $_POST[ $kind . '_text' ] ?? '' ),
        'url' => esc_url_raw( $_POST[ $kind . '_url' ] ?? '' ),
        'duration' => max( 3, min( 120, absint( $_POST[ $kind . '_duration' ] ?? 10 ) ) ),
        'skippable' => ! empty( $_POST[ $kind . '_skippable' ] ),
        'attachment_id' => absint( $attachment ),
    );
}

function smarttoolz_video_ads_page_notice( $message, $error = false ) {
    return '<div class="notice ' . ( $error ? 'notice-error' : 'notice-success' ) . ' is-dismissible"><p>' . esc_html( $message ) . '</p></div>';
}

function smarttoolz_video_ads_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    $s = smarttoolz_video_ads_settings();
    $notice = '';

    if (
        isset( $_POST['stv_ads_nonce'] ) &&
        wp_verify_nonce(
            sanitize_text_field( wp_unslash( $_POST['stv_ads_nonce'] ) ),
            'stv_ads_save'
        )
    ) {
        $action = sanitize_key( $_POST['stv_ads_action'] ?? 'save' );

        if ( 'delete' === $action ) {
            $kind = sanitize_key( $_POST['stv_ad_kind'] ?? '' );
            $index = absint( $_POST['stv_ad_index'] ?? -1 );

            if ( isset( $s['creatives'][ $kind ][ $index ] ) ) {
                $ad = $s['creatives'][ $kind ][ $index ];
                if ( ! empty( $ad['attachment_id'] ) ) {
                    wp_delete_attachment( absint( $ad['attachment_id'] ), true );
                }
                array_splice( $s['creatives'][ $kind ], $index, 1 );
                update_option( 'smarttoolz_video_ads_settings', $s, false );
                $notice = smarttoolz_video_ads_page_notice( 'Ad creative deleted.' );
            }
        } elseif ( 'upload' === $action ) {
            $kind = sanitize_key( $_POST['stv_ad_kind'] ?? '' );

            if ( in_array( $kind, array( 'pre_roll', 'bumper', 'mid_roll', 'post_roll', 'pause' ), true ) ) {
                $new = smarttoolz_video_ads_handle_upload( 'stv_ad_file', $kind );

                if ( is_wp_error( $new ) ) {
                    $notice = smarttoolz_video_ads_page_notice( $new->get_error_message(), true );
                } elseif ( $new ) {
                    $s['creatives'][ $kind ][] = $new;
                    update_option( 'smarttoolz_video_ads_settings', $s, false );
                    $notice = smarttoolz_video_ads_page_notice( 'Ad creative uploaded.' );
                }
            }
        } else {
            $s = smarttoolz_video_ads_sanitize_settings(
                $_POST['smarttoolz_video_ads_settings'] ?? array()
            );
            update_option( 'smarttoolz_video_ads_settings', $s, false );
            $notice = smarttoolz_video_ads_page_notice( 'Ads settings saved.' );
        }
    }
    ?>
    <div class="wrap">
        <h1>Ads Setup</h1>
        <p>Manage first-party video ad creatives and player placements. This is your site's own ad system, not the YouTube/Google ad network.</p>
        <?php echo $notice; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

        <form method="post">
            <?php wp_nonce_field( 'stv_ads_save', 'stv_ads_nonce' ); ?>
            <input type="hidden" name="stv_ads_action" value="save">
            <table class="form-table" role="presentation">
                <tr><th>Video ads</th><td><label><input type="checkbox" name="smarttoolz_video_ads_settings[enabled]" value="1" <?php checked( $s['enabled'], 1 ); ?>> Enable ads in the player</label></td></tr>
                <tr><th>Pre-roll</th><td><label><input type="checkbox" name="smarttoolz_video_ads_settings[pre_roll]" value="1" <?php checked( $s['pre_roll'], 1 ); ?>> Enable pre-roll</label></td></tr>
                <tr><th>Bumper</th><td><label><input type="checkbox" name="smarttoolz_video_ads_settings[bumper]" value="1" <?php checked( $s['bumper'], 1 ); ?>> Enable short non-skippable bumper</label></td></tr>
                <tr><th>Mid-roll</th><td><label><input type="checkbox" name="smarttoolz_video_ads_settings[midroll]" value="1" <?php checked( $s['midroll'], 1 ); ?>> Enable automatic mid-roll breaks</label></td></tr>
                <tr><th>Post-roll</th><td><label><input type="checkbox" name="smarttoolz_video_ads_settings[post_roll]" value="1" <?php checked( $s['post_roll'], 1 ); ?>> Enable post-roll</label></td></tr>
                <tr><th>Pause ads</th><td><label><input type="checkbox" name="smarttoolz_video_ads_settings[pause]" value="1" <?php checked( $s['pause'], 1 ); ?>> Show an ad when a viewer pauses</label></td></tr>
                <tr><th>Skip after</th><td><input type="number" min="1" max="15" name="smarttoolz_video_ads_settings[skip_after]" value="<?php echo esc_attr( $s['skip_after'] ); ?>"> seconds</td></tr>
                <tr><th>Mid-roll interval</th><td><input type="number" min="60" max="1800" name="smarttoolz_video_ads_settings[midroll_interval]" value="<?php echo esc_attr( $s['midroll_interval'] ); ?>"> seconds</td></tr>
                <tr><th>Pause-ad cooldown</th><td><input type="number" min="15000" max="600000" step="1000" name="smarttoolz_video_ads_settings[pause_cooldown]" value="<?php echo esc_attr( $s['pause_cooldown'] ); ?>"> milliseconds</td></tr>
            </table>
            <?php submit_button( 'Save Ads Settings' ); ?>
        </form>

        <hr>
        <h2>Ad Creatives</h2>
        <p>Upload MP4/WebM/OGV/MOV video creatives or JPG/PNG/WebP image creatives.</p>

        <?php foreach ( array( 'pre_roll' => 'Pre-roll', 'bumper' => 'Bumper', 'mid_roll' => 'Mid-roll', 'post_roll' => 'Post-roll', 'pause' => 'Pause ad' ) as $kind => $label ) : ?>
            <div style="background:#fff;border:1px solid #dcdcde;border-radius:10px;padding:18px;margin:14px 0;max-width:1050px">
                <h3 style="margin-top:0"><?php echo esc_html( $label ); ?></h3>

                <?php if ( ! empty( $s['creatives'][ $kind ] ) ) : ?>
                    <ul>
                        <?php foreach ( $s['creatives'][ $kind ] as $i => $ad ) : ?>
                            <li>
                                <strong><?php echo esc_html( $ad['title'] ?: basename( parse_url( $ad['src'], PHP_URL_PATH ) ) ); ?></strong>
                                — <?php echo esc_html( $ad['type'] ); ?>
                                <?php if ( ! empty( $ad['skippable'] ) ) : ?> — skippable<?php endif; ?>
                                <form method="post" style="display:inline">
                                    <?php wp_nonce_field( 'stv_ads_save', 'stv_ads_nonce' ); ?>
                                    <input type="hidden" name="stv_ads_action" value="delete">
                                    <input type="hidden" name="stv_ad_kind" value="<?php echo esc_attr( $kind ); ?>">
                                    <input type="hidden" name="stv_ad_index" value="<?php echo esc_attr( $i ); ?>">
                                    <button class="button-link-delete" type="submit">Delete</button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else : ?>
                    <p>No creative uploaded yet.</p>
                <?php endif; ?>

                <form method="post" enctype="multipart/form-data" style="display:grid;gap:8px;max-width:700px">
                    <?php wp_nonce_field( 'stv_ads_save', 'stv_ads_nonce' ); ?>
                    <input type="hidden" name="stv_ads_action" value="upload">
                    <input type="hidden" name="stv_ad_kind" value="<?php echo esc_attr( $kind ); ?>">
                    <input type="file" name="stv_ad_file" accept="video/mp4,video/webm,video/ogg,video/quicktime,image/jpeg,image/png,image/webp" required>
                    <input type="text" name="<?php echo esc_attr( $kind ); ?>_title" placeholder="Ad title">
                    <input type="text" name="<?php echo esc_attr( $kind ); ?>_text" placeholder="Short ad text">
                    <input type="url" name="<?php echo esc_attr( $kind ); ?>_url" placeholder="CTA URL (optional)">
                    <label>Image duration <input type="number" min="3" max="120" name="<?php echo esc_attr( $kind ); ?>_duration" value="10"> sec</label>
                    <label><input type="checkbox" name="<?php echo esc_attr( $kind ); ?>_skippable" value="1"> Allow Skip after <?php echo esc_html( $s['skip_after'] ); ?> seconds</label>
                    <?php submit_button( 'Upload Creative', 'secondary', 'submit', false ); ?>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
    <?php
}

function smarttoolz_video_ads_runtime_config() {
    $s = smarttoolz_video_ads_settings();

    if ( empty( $s['enabled'] ) ) {
        return array( 'enabled' => false );
    }

    $creatives = array();

    foreach ( array( 'pre_roll', 'bumper', 'mid_roll', 'post_roll', 'pause' ) as $kind ) {
        $creatives[ $kind ] = array();

        if ( empty( $s[ $kind ] ) || empty( $s['creatives'][ $kind ] ) ) {
            continue;
        }

        foreach ( (array) $s['creatives'][ $kind ] as $ad ) {
            if ( empty( $ad['src'] ) ) {
                continue;
            }

            $creatives[ $kind ][] = array(
                'src' => esc_url_raw( $ad['src'] ),
                'type' => ( 'image' === ( $ad['type'] ?? '' ) ? 'image' : 'video' ),
                'title' => sanitize_text_field( $ad['title'] ?? '' ),
                'text' => sanitize_text_field( $ad['text'] ?? '' ),
                'url' => esc_url_raw( $ad['url'] ?? '' ),
                'duration' => absint( $ad['duration'] ?? 10 ),
                'skippable' => ! empty( $ad['skippable'] ) && 'bumper' !== $kind,
            );
        }
    }

    return array(
        'enabled' => true,
        'skip_after' => absint( $s['skip_after'] ),
        'midroll' => ! empty( $s['midroll'] ),
        'midroll_interval' => absint( $s['midroll_interval'] ),
        'pause' => ! empty( $s['pause'] ),
        'pause_cooldown' => absint( $s['pause_cooldown'] ),
        'creatives' => $creatives,
    );
}
