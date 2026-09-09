<?php
/**
 * SmartToolz registration settings sync.
 *
 * WordPress Settings > General > Membership (users_can_register) is the
 * authoritative registration switch. SmartToolz mirrors that value to its
 * own UI setting and never changes the WordPress option while rendering the
 * login screen.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_registration_sync_mirror( $option, $old_value, $value ) {
    if ( 'users_can_register' !== $option ) {
        return;
    }

    $auth = (array) get_option( 'smarttoolz_auth_ui_settings', array() );
    $auth['register_enabled'] = empty( $value ) ? 0 : 1;
    update_option( 'smarttoolz_auth_ui_settings', $auth, false );
}
add_action( 'updated_option', 'smarttoolz_registration_sync_mirror', 10, 3 );

function smarttoolz_registration_sync_admin_value( $value ) {
    if ( 'users_can_register' !== current_filter() ) {
        return $value;
    }
    return empty( $value ) ? 0 : 1;
}

// Keep the native login UI in agreement with the saved WordPress option.
// This runs after the older SmartToolz auth filter, so its stored value wins.
function smarttoolz_registration_sync_login_read( $value ) {
    if ( ! isset( $GLOBALS['pagenow'] ) || 'wp-login.php' !== $GLOBALS['pagenow'] ) {
        return $value;
    }

    global $wpdb;
    $raw = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT option_value FROM {$wpdb->options} WHERE option_name = %s LIMIT 1",
            'users_can_register'
        )
    );

    return (bool) maybe_unserialize( $raw );
}
add_filter( 'option_users_can_register', 'smarttoolz_registration_sync_login_read', 9999 );

// Keep SmartToolz's own registration toggle in sync for existing installations.
function smarttoolz_registration_sync_bootstrap() {
    $native = (bool) get_option( 'users_can_register', false );
    $auth   = (array) get_option( 'smarttoolz_auth_ui_settings', array() );
    $current = isset( $auth['register_enabled'] ) ? (bool) $auth['register_enabled'] : null;

    if ( null === $current || $current !== $native ) {
        $auth['register_enabled'] = $native ? 1 : 0;
        update_option( 'smarttoolz_auth_ui_settings', $auth, false );
    }
}
add_action( 'admin_init', 'smarttoolz_registration_sync_bootstrap', 1 );
