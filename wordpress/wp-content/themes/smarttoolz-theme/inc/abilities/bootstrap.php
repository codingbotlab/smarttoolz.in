<?php
/**
 * SmartToolz Abilities API Bootstrap
 *
 * Optional abilities integration. It must never prevent the theme
 * from rendering when the API layer is unavailable or disabled.
 *
 * @package SmartToolz
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function smarttoolz_abilities_init() {
	if ( ! class_exists( 'SmartToolz_API_Init' ) || ! method_exists( 'SmartToolz_API_Init', 'get_admin_settings_option' ) ) {
		return;
	}

	if ( ! SmartToolz_API_Init::get_admin_settings_option( 'enable_abilities', false ) ) {
		return;
	}

	$abilities_dir = SMARTTOOLZ_THEME_DIR . 'inc/abilities/';
	$files = array(
		'class-smarttoolz-abilities-response.php',
		'class-smarttoolz-abstract-ability.php',
		'class-smarttoolz-abilities-helper.php',
		'class-smarttoolz-abilities-init.php',
	);

	foreach ( $files as $file ) {
		$path = $abilities_dir . $file;
		if ( ! is_file( $path ) ) {
			return;
		}
		require_once $path;
	}

	if ( class_exists( 'SmartToolz_Abilities_Init' ) ) {
		SmartToolz_Abilities_Init::get_instance();
	}
}

add_action( 'after_setup_theme', 'smarttoolz_abilities_init', 99 );

if ( defined( 'WP_CLI' ) && WP_CLI ) {
	$cli_file = SMARTTOOLZ_THEME_DIR . 'inc/abilities/class-smarttoolz-abilities-cli.php';
	if ( is_file( $cli_file ) ) {
		require_once $cli_file;
		if ( class_exists( 'SmartToolz_Abilities_CLI' ) ) {
			WP_CLI::add_command( 'smarttoolz abilities', 'SmartToolz_Abilities_CLI' );
		}
	}
}
