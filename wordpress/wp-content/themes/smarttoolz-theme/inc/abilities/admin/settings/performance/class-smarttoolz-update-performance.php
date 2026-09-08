<?php
/**
 * Update Performance Settings Ability
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.12.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Update_Performance
 */
class SmartToolz_Update_Performance extends SmartToolz_Abstract_Ability {
	/**
	 * Configure the ability.
	 *
	 * @return void
	 */
	public function configure() {
		$this->id          = 'smarttoolz/update-performance';
		$this->category    = 'smarttoolz';
		$this->label       = __( 'Update SmartToolz Performance Settings', 'smarttoolz' );
		$this->description = __( 'Updates performance settings for the SmartToolz theme including font loading options.', 'smarttoolz' );
	}

	/**
	 * Get tool type.
	 *
	 * @return string
	 */
	public function get_tool_type() {
		return 'write';
	}

	/**
	 * Get input schema.
	 *
	 * @return array
	 */
	public function get_input_schema() {
		return array(
			'type'       => 'object',
			'properties' => array(
				'load_google_fonts_locally' => array(
					'type'        => 'boolean',
					'description' => 'Enable or disable Load Google Fonts Locally.',
				),
				'preload_local_fonts'       => array(
					'type'        => 'boolean',
					'description' => 'Enable or disable Preload Local Fonts.',
				),
			),
		);
	}

	/**
	 * Get examples.
	 *
	 * @return array
	 */
	public function get_examples() {
		return array(
			'update performance settings',
			'configure performance options',
			'set performance preferences',
			'change performance configuration',
			'update performance layout',
			'set font loading options',
			'configure font optimization',
			'update font performance',
			'change font loading settings',
			'set fonts to load locally',
			'enable local fonts with preload',
			'configure fonts self hosting',
			'update fonts loading configuration',
			'set google fonts to local',
			'enable fonts preloading',
			'configure performance optimization',
			'update all performance settings',
			'change performance preferences',
			'set performance and font options',
			'update fonts hosting settings',
			'configure local fonts loading',
			'set fonts performance options',
			'enable performance features',
			'update font caching settings',
			'configure fonts download options',
			'set self hosted fonts',
			'enable GDPR compliant fonts',
			'update fonts storage settings',
			'configure offline fonts',
			'set performance customization',
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

		$updated         = false;
		$update_messages = array();

		if ( isset( $args['load_google_fonts_locally'] ) ) {
			$load_locally = (bool) $args['load_google_fonts_locally'];
			SmartToolz_API_Init::update_admin_settings_option( 'self_hosted_gfonts', $load_locally );
			$updated           = true;
			$update_messages[] = sprintf( 'Load Google Fonts Locally %s', $load_locally ? 'enabled' : 'disabled' );

			if ( ! $load_locally && isset( $args['preload_local_fonts'] ) && $args['preload_local_fonts'] ) {
				return SmartToolz_Abilities_Response::error(
					__( 'Cannot enable Preload Local Fonts when Load Google Fonts Locally is disabled.', 'smarttoolz' ),
					__( 'Please enable Load Google Fonts Locally first or set preload_local_fonts to false.', 'smarttoolz' )
				);
			}
		}

		if ( isset( $args['preload_local_fonts'] ) ) {
			$preload              = (bool) $args['preload_local_fonts'];
			$load_locally_enabled = SmartToolz_API_Init::get_admin_settings_option( 'self_hosted_gfonts', false );

			if ( $preload && ! $load_locally_enabled ) {
				return SmartToolz_Abilities_Response::error(
					__( 'Cannot enable Preload Local Fonts.', 'smarttoolz' ),
					__( 'Load Google Fonts Locally must be enabled first.', 'smarttoolz' )
				);
			}

			SmartToolz_API_Init::update_admin_settings_option( 'preload_local_fonts', $preload );
			$updated           = true;
			$update_messages[] = sprintf( 'Preload Local Fonts %s', $preload ? 'enabled' : 'disabled' );
		}

		if ( ! $updated ) {
			return SmartToolz_Abilities_Response::error(
				__( 'No changes specified.', 'smarttoolz' ),
				__( 'Please provide at least one setting to update.', 'smarttoolz' )
			);
		}

		$message = 'Performance settings updated: ' . implode( ', ', $update_messages ) . '.';

		return SmartToolz_Abilities_Response::success(
			$message,
			array( 'updated' => true )
		);
	}
}

SmartToolz_Update_Performance::register();
