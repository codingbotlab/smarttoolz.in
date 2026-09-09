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
        'sidebar' => 1,
        'skip_after' => 5,
        'midroll_interval' => 300,
        'pause_cooldown' => 60000,
        'creatives' => array(
            'pre_roll' => array(),
            'bumper' => array(),
            'mid_roll' => array(),
            'post_roll' => array(),
            'pause' => array(),
            'sidebar' => array(),
        ),
    );
}

function smarttoolz_video_ads_settings() {
    $defaults = smarttoolz_video_ads_defaults();
    $saved = get_option( 'smarttoolz_video_ads_settings', array() );
    $settings = wp_parse_args( is_array( $saved ) ? $saved : array(), $defaults );

    // Keep creatives in their own option so saving the settings form can never
    // accidentally replace or clear uploaded ads.
    $saved_creatives = get_option( 'smarttoolz_video_ads_creatives', null );
    if ( is_array( $saved_creatives ) ) {
        $settings['creatives'] = $saved_creatives;
    }

    foreach ( $defaults['creatives'] as $kind => $unused ) {
        if ( empty( $settings['creatives'][ $kind ] ) || ! is_array( $settings['creatives'][ $kind ] ) ) {
            $settings['creatives'][ $kind ] = array();
        }
    }

    return $settings;
}

function smarttoolz_video_ads_save_creatives( $creatives ) {
    if ( ! is_array( $creatives ) ) {
        return false;
    }
    return update_option( 'smarttoolz_video_ads_creatives', $creatives, false );
}

function smarttoolz_video_ads_register_settings() {
    register_setting( 'smarttoolz_video_ads_group', 'smarttoolz_video_ads_settings', array( 'sanitize_callback' => 'smarttoolz_video_ads_sanitize_settings' ) );
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
        'sidebar' => empty( $input['sidebar'] ) ? 0 : 1,
        'skip_after' => max( 1, min( 15, absint( $input['skip_after'] ?? 5 ) ) ),
        'midroll_interval' => max( 60, min( 1800, absint( $input['midroll_interval'] ?? 300 ) ) ),
        'pause_cooldown' => max( 15000, min( 600000, absint( $input['pause_cooldown'] ?? 60000 ) ) ),
        'creatives' => $old['creatives'],
    );
}

function smarttoolz_video_ads_admin_menu() {
    add_submenu_page( 'smarttoolz', 'Ads Setup', 'Ads Setup', 'manage_options', 'smarttoolz-video-ads', 'smarttoolz_video_ads_settings_page' );
}
add_action( 'admin_menu', 'smarttoolz_video_ads_admin_menu', 27 );

/** Allow ad formats in WordPress uploads and the ad uploader. */
function smarttoolz_video_ads_upload_mimes( $mimes ) {
    $mimes['mp4'] = 'video/mp4';
    $mimes['m4v'] = 'video/mp4';
    $mimes['webm'] = 'video/webm';
    $mimes['ogv'] = 'video/ogg';
    $mimes['ogg'] = 'video/ogg';
    $mimes['mov'] = 'video/quicktime';
    $mimes['avi'] = 'video/x-msvideo';
    $mimes['jpg'] = 'image/jpeg';
    $mimes['jpeg'] = 'image/jpeg';
    $mimes['png'] = 'image/png';
    $mimes['webp'] = 'image/webp';
    $mimes['gif'] = 'image/gif';
    return $mimes;
}
add_filter( 'upload_mimes', 'smarttoolz_video_ads_upload_mimes', 20 );

function smarttoolz_video_ads_check_filetype( $types, $file, $filename, $mimes, $real_mime ) {
    $extension = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );
    $allowed = array(
        'mp4' => 'video/mp4', 'm4v' => 'video/mp4', 'webm' => 'video/webm',
        'ogv' => 'video/ogg', 'ogg' => 'video/ogg', 'mov' => 'video/quicktime',
        'avi' => 'video/x-msvideo', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg',
        'png' => 'image/png', 'webp' => 'image/webp', 'gif' => 'image/gif',
    );
    if ( isset( $allowed[ $extension ] ) && ( empty( $types['type'] ) || empty( $types['ext'] ) ) ) {
        $types['ext'] = $extension;
        $types['type'] = $allowed[ $extension ];
    }
    return $types;
}
add_filter( 'wp_check_filetype_and_ext', 'smarttoolz_video_ads_check_filetype', 20, 5 );

function smarttoolz_video_ads_handle_upload( $field, $kind ) {
    if ( empty( $_FILES[ $field ]['name'] ) ) {
        return null;
    }
    if ( ! empty( $_FILES[ $field ]['error'] ) ) {
        return new WP_Error( 'ad_upload_error', 'Upload failed. Please choose a valid video or image file.' );
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    // wp_handle_upload() expects extension => MIME.
    $allowed = array(
        'mp4' => 'video/mp4',
        'm4v' => 'video/mp4',
        'webm' => 'video/webm',
        'ogv' => 'video/ogg',
        'ogg' => 'video/ogg',
        'mov' => 'video/quicktime',
        'avi' => 'video/x-msvideo',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'webp' => 'image/webp',
        'gif' => 'image/gif',
    );

    $filename = sanitize_file_name( $_FILES[ $field ]['name'] );
    $extension = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );
    if ( ! isset( $allowed[ $extension ] ) ) {
        return new WP_Error( 'ad_upload_type', 'Unsupported ad file type. Use MP4, M4V, WebM, OGV, OGG, MOV, AVI, JPG, PNG, WebP or GIF.' );
    }

    $upload = wp_handle_upload(
        $_FILES[ $field ],
        array(
            'test_form' => false,
            'mimes' => $allowed,
        )
    );

    if ( isset( $upload['error'] ) ) {
        return new WP_Error( 'ad_upload_error', $upload['error'] );
    }

    $mime = $allowed[ $extension ];
    $is_image = 0 === strpos( $mime, 'image/' );
    $attachment = wp_insert_attachment(
        array(
            'post_mime_type' => $mime,
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
        $metadata = wp_generate_attachment_metadata( $attachment, $upload['file'] );
        if ( ! is_wp_error( $metadata ) ) {
            wp_update_attachment_metadata( $attachment, $metadata );
        }
    }

    return array(
        'src' => esc_url_raw( $upload['url'] ),
        'type' => $is_image ? 'image' : 'video',
        'title' => sanitize_text_field( wp_unslash( $_POST[ $kind . '_title' ] ?? '' ) ),
        'text' => sanitize_text_field( wp_unslash( $_POST[ $kind . '_text' ] ?? '' ) ),
        'url' => esc_url_raw( wp_unslash( $_POST[ $kind . '_url' ] ?? '' ) ),
        'duration' => max( 3, min( 120, absint( $_POST[ $kind . '_duration' ] ?? 10 ) ) ),
        'skippable' => ! empty( $_POST[ $kind . '_skippable' ] ),
        'active' => 1,
        'attachment_id' => absint( $attachment ),
        'created_at' => current_time( 'mysql' ),
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
    $kinds = array(
        'pre_roll' => 'Pre-roll',
        'bumper' => 'Bumper',
        'mid_roll' => 'Mid-roll',
        'post_roll' => 'Post-roll',
        'pause' => 'Pause ad',
        'sidebar' => 'Sidebar ad',
    );

    if ( isset( $_POST['stv_ads_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['stv_ads_nonce'] ) ), 'stv_ads_save' ) ) {
        $action = sanitize_key( $_POST['stv_ads_action'] ?? 'save' );
        $kind = sanitize_key( $_POST['stv_ad_kind'] ?? '' );

        if ( 'delete' === $action ) {
            $index = absint( $_POST['stv_ad_index'] ?? -1 );
            if ( isset( $s['creatives'][ $kind ][ $index ] ) ) {
                $ad = $s['creatives'][ $kind ][ $index ];
                if ( ! empty( $ad['attachment_id'] ) ) {
                    wp_delete_attachment( absint( $ad['attachment_id'] ), true );
                }
                array_splice( $s['creatives'][ $kind ], $index, 1 );
                smarttoolz_video_ads_save_creatives( $s['creatives'] );
                update_option( 'smarttoolz_video_ads_settings', $s, false );
                $notice = smarttoolz_video_ads_page_notice( 'Ad creative deleted.' );
            }
        } elseif ( 'toggle' === $action ) {
            $index = absint( $_POST['stv_ad_index'] ?? -1 );
            if ( isset( $s['creatives'][ $kind ][ $index ] ) ) {
                $s['creatives'][ $kind ][ $index ]['active'] = empty( $s['creatives'][ $kind ][ $index ]['active'] ) ? 1 : 0;
                smarttoolz_video_ads_save_creatives( $s['creatives'] );
                update_option( 'smarttoolz_video_ads_settings', $s, false );
                $notice = smarttoolz_video_ads_page_notice( 'Ad status updated.' );
            }
        } elseif ( 'upload' === $action && isset( $kinds[ $kind ] ) ) {
            $new = smarttoolz_video_ads_handle_upload( 'stv_ad_file', $kind );
            if ( is_wp_error( $new ) ) {
                $notice = smarttoolz_video_ads_page_notice( $new->get_error_message(), true );
            } elseif ( $new ) {
                $s['creatives'][ $kind ][] = $new;
                smarttoolz_video_ads_save_creatives( $s['creatives'] );
                update_option( 'smarttoolz_video_ads_settings', $s, false );
                $notice = smarttoolz_video_ads_page_notice( 'Ad creative uploaded successfully.' );
            }
        } else {
            $s = smarttoolz_video_ads_sanitize_settings( $_POST['smarttoolz_video_ads_settings'] ?? array() );
            update_option( 'smarttoolz_video_ads_settings', $s, false );
            // Explicitly preserve creatives when settings are saved.
            smarttoolz_video_ads_save_creatives( $s['creatives'] );
            $notice = smarttoolz_video_ads_page_notice( 'Ads settings saved.' );
        }
    }
    ?>
    <div class="wrap">
        <h1>Ads Setup</h1>
        <p>Manage first-party ads for the video player and the watch-page sidebar.</p>
        <?php echo $notice; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

        <form method="post">
            <?php wp_nonce_field( 'stv_ads_save', 'stv_ads_nonce' ); ?>
            <input type="hidden" name="stv_ads_action" value="save">
            <table class="form-table" role="presentation">
                <tr><th>Video ads</th><td><label><input type="checkbox" name="smarttoolz_video_ads_settings[enabled]" value="1" <?php checked( $s['enabled'], 1 ); ?>> Enable ads</label></td></tr>
                <tr><th>Pre-roll</th><td><label><input type="checkbox" name="smarttoolz_video_ads_settings[pre_roll]" value="1" <?php checked( $s['pre_roll'], 1 ); ?>> Play before the main video</label></td></tr>
                <tr><th>Bumper</th><td><label><input type="checkbox" name="smarttoolz_video_ads_settings[bumper]" value="1" <?php checked( $s['bumper'], 1 ); ?>> Short non-skippable bumper</label></td></tr>
                <tr><th>Mid-roll</th><td><label><input type="checkbox" name="smarttoolz_video_ads_settings[midroll]" value="1" <?php checked( $s['midroll'], 1 ); ?>> Automatic mid-roll breaks</label></td></tr>
                <tr><th>Post-roll</th><td><label><input type="checkbox" name="smarttoolz_video_ads_settings[post_roll]" value="1" <?php checked( $s['post_roll'], 1 ); ?>> Play after the main video</label></td></tr>
                <tr><th>Pause ads</th><td><label><input type="checkbox" name="smarttoolz_video_ads_settings[pause]" value="1" <?php checked( $s['pause'], 1 ); ?>> Show an ad when viewer pauses</label></td></tr>
                <tr><th>Sidebar ads</th><td><label><input type="checkbox" name="smarttoolz_video_ads_settings[sidebar]" value="1" <?php checked( $s['sidebar'], 1 ); ?>> Show active sidebar ads above the Next video list</label></td></tr>
                <tr><th>Skip after</th><td><input type="number" min="1" max="15" name="smarttoolz_video_ads_settings[skip_after]" value="<?php echo esc_attr( $s['skip_after'] ); ?>"> seconds</td></tr>
                <tr><th>Mid-roll interval</th><td><input type="number" min="60" max="1800" name="smarttoolz_video_ads_settings[midroll_interval]" value="<?php echo esc_attr( $s['midroll_interval'] ); ?>"> seconds</td></tr>
                <tr><th>Pause-ad cooldown</th><td><input type="number" min="15000" max="600000" step="1000" name="smarttoolz_video_ads_settings[pause_cooldown]" value="<?php echo esc_attr( $s['pause_cooldown'] ); ?>"> milliseconds</td></tr>
            </table>
            <?php submit_button( 'Save Ads Settings' ); ?>
        </form>

        <hr>
        <h2>Active Ads</h2>
        <p>Currently active creatives that can be displayed to visitors.</p>
        <table class="widefat striped" style="margin-bottom:30px">
            <thead><tr><th>Ad</th><th>Placement</th><th>Type</th><th>Status</th><th>Duration</th><th>Action</th></tr></thead>
            <tbody>
            <?php
            $active_count = 0;
            foreach ( $kinds as $kind => $label ) :
                foreach ( $s['creatives'][ $kind ] as $i => $ad ) :
                    if ( empty( $ad['active'] ) ) {
                        continue;
                    }
                    $active_count++;
                    ?>
                    <tr>
                        <td><strong><?php echo esc_html( $ad['title'] ?: basename( parse_url( $ad['src'], PHP_URL_PATH ) ) ); ?></strong></td>
                        <td><?php echo esc_html( $label ); ?></td>
                        <td><?php echo esc_html( ucfirst( $ad['type'] ?? 'video' ) ); ?></td>
                        <td><strong>Active</strong></td>
                        <td><?php echo esc_html( absint( $ad['duration'] ?? 10 ) ); ?>s</td>
                        <td>
                            <form method="post" style="display:inline-block">
                                <?php wp_nonce_field( 'stv_ads_save', 'stv_ads_nonce' ); ?>
                                <input type="hidden" name="stv_ads_action" value="toggle">
                                <input type="hidden" name="stv_ad_kind" value="<?php echo esc_attr( $kind ); ?>">
                                <input type="hidden" name="stv_ad_index" value="<?php echo esc_attr( $i ); ?>">
                                <button class="button" type="submit">Deactivate</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach;
            endforeach;
            if ( ! $active_count ) : ?>
                <tr><td colspan="6">No active ads yet.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>

        <h2>Ad Creatives</h2>
        <p>Upload video or image creatives and choose where they will appear. No external ad URL is required.</p>

        <?php foreach ( $kinds as $kind => $label ) : ?>
            <div style="background:#fff;border:1px solid #dcdcde;border-radius:10px;padding:18px;margin:14px 0">
                <h3 style="margin-top:0"><?php echo esc_html( $label ); ?></h3>
                <form method="post" enctype="multipart/form-data" style="display:grid;gap:10px;max-width:760px">
                    <?php wp_nonce_field( 'stv_ads_save', 'stv_ads_nonce' ); ?>
                    <input type="hidden" name="stv_ads_action" value="upload">
                    <input type="hidden" name="stv_ad_kind" value="<?php echo esc_attr( $kind ); ?>">
                    <label><strong>Ad video / image</strong><br><input type="file" name="stv_ad_file" accept=".mp4,.m4v,.webm,.ogv,.ogg,.mov,.avi,.jpg,.jpeg,.png,.webp,.gif,video/*,image/*" required></label>
                    <input type="text" name="<?php echo esc_attr( $kind ); ?>_title" placeholder="Ad title">
                    <input type="text" name="<?php echo esc_attr( $kind ); ?>_text" placeholder="Short ad text">
                    <input type="url" name="<?php echo esc_attr( $kind ); ?>_url" placeholder="CTA URL (optional)">
                    <label>Display duration <input type="number" min="3" max="120" name="<?php echo esc_attr( $kind ); ?>_duration" value="10"> seconds (image ads)</label>
                    <label><input type="checkbox" name="<?php echo esc_attr( $kind ); ?>_skippable" value="1"> Allow skip after <?php echo esc_html( $s['skip_after'] ); ?> seconds</label>
                    <?php submit_button( 'Upload ' . $label, 'secondary', 'submit', false ); ?>
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
    foreach ( array( 'pre_roll', 'bumper', 'mid_roll', 'post_roll', 'pause', 'sidebar' ) as $kind ) {
        $creatives[ $kind ] = array();
        if ( empty( $s['creatives'][ $kind ] ) ) {
            continue;
        }
        foreach ( (array) $s['creatives'][ $kind ] as $ad ) {
            if ( empty( $ad['src'] ) || ( isset( $ad['active'] ) && ! $ad['active'] ) ) {
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
        'pre_roll' => ! empty( $s['pre_roll'] ),
        'bumper' => ! empty( $s['bumper'] ),
        'midroll' => ! empty( $s['midroll'] ),
        'post_roll' => ! empty( $s['post_roll'] ),
        'pause' => ! empty( $s['pause'] ),
        'sidebar' => ! empty( $s['sidebar'] ),
        'midroll_interval' => absint( $s['midroll_interval'] ),
        'pause_cooldown' => absint( $s['pause_cooldown'] ),
        'creatives' => $creatives,
    );
}
