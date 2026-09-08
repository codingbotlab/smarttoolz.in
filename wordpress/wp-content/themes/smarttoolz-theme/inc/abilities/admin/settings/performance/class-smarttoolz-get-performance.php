<?php
/**
 * Get Performance Settings Ability
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.12.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Get_Performance
 */
class SmartToolz_Get_Performance extends SmartToolz_Abstract_Ability {
	/**
	 * Configure the ability.
	 *
	 * @return void
	 */
	public function configure() {
		$this->id          = 'smarttoolz/get-performance';
		$this->label       = __( 'Get SmartToolz Performance Settings', 'smarttoolz' );
		$this->description = __( 'Retrieves all performance settings for the SmartToolz theme including font loading options.', 'smarttoolz' );
		$this->category    = 'smarttoolz';
	}

	/**
	 * Get input schema.
	 *
	 * @return array
	 */
	public function get_input_schema() {
		return array();
	}

	/**
	 * Get examples.
	 *
	 * @return array
	 */
	public function get_examples() {
		return array(
			'get performance settings',
			'show performance configuration',
			'view all performance options',
			'display performance settings',
			'get performance options',
			'show performance preferences',
			'view performance configuration',
			'display all performance settings',
			'get complete performance setup',
			'show performance optimization',
			'view performance design settings',
			'display performance customization',
			'get performance display options',
			'show font loading settings',
			'view font optimization options',
			'display font performance config',
			'get fonts loading configuration',
			'show all performance preferences',
			'view performance setup',
			'display performance design options',
			'get performance appearance settings',
			'show performance optimization options',
			'view performance layout options',
			'display complete performance config',
			'get site performance settings',
			'show default performance options',
			'view performance customization options',
			'display performance layout configuration',
			'get performance design preferences',
			'show performance appearance options',
		);
	}

	/**
	 * Execute the ability.
	 *
	 * @param array $args Input arguments.
	 * @return array Result array.
	 */
	public function execute( $args ) {
		if ( ! defined( 'SMARTTOOLZ_THEME_SETTINGS' ) ) {
			return SmartToolz_Abilities_Response::error(
				__( 'SmartToolz theme is not active.', 'smarttoolz' ),
				__( 'Please activate the SmartToolz theme to use this feature.', 'smarttoolz' )
			);
		}

		if ( ! class_exists( 'SmartToolz_API_Init' ) ) {
			return SmartToolz_Abilities_Response::error(
				__( 'SmartToolz API not available.', 'smarttoolz' ),
				__( 'Please ensure SmartToolz theme is properly loaded.', 'smarttoolz' )
			);
		}

		$load_fonts_locally  = SmartToolz_API_Init::get_admin_settings_option( 'self_hosted_gfonts', false );
		$preload_local_fonts = SmartToolz_API_Init::get_admin_settings_option( 'preload_local_fonts', false );

		return SmartToolz_Abilities_Response::success(
			__( 'Retrieved performance settings successfully.', 'smarttoolz' ),
			array(
				'load_google_fonts_locally'       => (bool) $load_fonts_locally,
				'load_google_fonts_locally_label' => $load_fonts_locally ? 'Enabled' : 'Disabled',
				'preload_local_fonts'             => (bool) $preload_local_fonts,
				'preload_local_fonts_label'       => $preload_local_fonts ? 'Enabled' : 'Disabled',
				'preload_local_fonts_available'   => (bool) $load_fonts_locally,
			)
		);
	}
}

SmartToolz_Get_Performance::register();
