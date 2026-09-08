<?php
/**
 * SmartToolz Video player loader.
 * Loaded as a must-use plugin so the player assets are available even if the
 * regular video plugin bootstrap changes.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'plugins_loaded', function() {
    $asset_file = WP_PLUGIN_DIR . '/smarttoolz-video/includes/player-assets.php';
    if ( file_exists( $asset_file ) ) {
        require_once $asset_file;
    }
}, 30 );
