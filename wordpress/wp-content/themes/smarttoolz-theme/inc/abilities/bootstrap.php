<?php
/**
 * SmartToolz Abilities API Bootstrap
 *
 * Loads the Abilities API integration and initializes
 * the SmartToolz abilities registration.
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.12.6
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Initialize SmartToolz Abilities.
 *
 * Boots the SmartToolz_Abilities_Init class which registers all SmartToolz abilities
 * when the Abilities API is available (WordPress 6.9+ or via plugin polyfill).
 *
 * @return void
 */
function smarttoolz_abilities_init() {
	// Check if abilities are enabled in the dashboard settings.
	if ( ! SmartToolz_API_Init::get_admin_settings_option( 'enable_abilities', false ) ) {
		return;
	}

	$abilities_dir = SMARTTOOLZ_THEME_DIR . 'inc/abilities/';

	// Load base classes.
	require_once $abilities_dir . 'class-smarttoolz-abilities-response.php';
	require_once $abilities_dir . 'class-smarttoolz-abstract-ability.php';
	require_once $abilities_dir . 'class-smarttoolz-abilities-helper.php';
	require_once $abilities_dir . 'class-smarttoolz-abilities-init.php';

	// Initialize abilities registration.
	SmartToolz_Abilities_Init::get_instance();
}

// Initialize after theme setup so smarttoolz_get_option() is available.
add_action( 'after_setup_theme', 'smarttoolz_abilities_init' );

// Register WP-CLI commands unconditionally so they work regardless of abilities toggle state.
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	require_once SMARTTOOLZ_THEME_DIR . 'inc/abilities/class-smarttoolz-abilities-cli.php';
	WP_CLI::add_command( 'smarttoolz abilities', 'SmartToolz_Abilities_CLI' );
}
