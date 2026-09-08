<?php
/**
 * Update Preload Local Fonts Ability
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.12.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Update_Preload_Local_Fonts
 */
class SmartToolz_Update_Preload_Local_Fonts extends SmartToolz_Abstract_Ability {
	/**
	 * Configure the ability.
	 *
	 * @return void
	 */
	public function configure() {
		$this->id          = 'smarttoolz/update-font-preload-local';
		$this->category    = 'smarttoolz';
		$this->label       = __( 'Update SmartToolz Preload Local Fonts Status', 'smarttoolz' );
		$this->description = __( 'Updates the Preload Local Fonts setting for the SmartToolz theme (enable or disable).', 'smarttoolz' );
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
				'enabled' => array(
					'type'        => 'boolean',
					'description' => 'Enable or disable Preload Local Fonts.',
				),
			),
			'required'   => array( 'enabled' ),
		);
	}

	/**
	 * Get examples.
	 *
	 * @return array
	 */
	public function get_examples() {
		return array(
			'enable preload local fonts',
			'disable preload local fonts',
			'turn on fonts preloading',
			'turn off fonts preloading',
			'activate preload local fonts',
			'deactivate preload local fonts',
			'enable fonts preload',
			'disable fonts preload',
			'turn on local fonts preload',
			'turn off local fonts preload',
			'enable fonts eager loading',
			'disable fonts eager loading',
			'activate fonts preloading',
			'deactivate fonts preloading',
			'enable fonts immediate load',
			'disable fonts immediate load',
			'turn on fonts priority loading',
			'turn off fonts priority loading',
			'enable preload fonts on page load',
			'disable preload fonts on page load',
			'activate fonts instant loading',
			'deactivate fonts instant loading',
			'enable fonts fast loading',
			'disable fonts fast loading',
			'turn on fonts quick load',
			'turn off fonts quick load',
			'enable fonts speed optimization',
			'disable fonts speed optimization',
			'activate fonts preload feature',
			'deactivate fonts preload feature',
			'make fonts load immediately',
			'stop fonts from preloading',
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

		if ( ! isset( $args['enabled'] ) ) {
			return SmartToolz_Abilities_Response::error(
				__( 'Enabled status is required.', 'smarttoolz' ),
				__( 'Please provide enabled as true or false.', 'smarttoolz' )
			);
		}

		$enabled              = (bool) $args['enabled'];
		$load_locally_enabled = SmartToolz_API_Init::get_admin_settings_option( 'self_hosted_gfonts', false );

		if ( $enabled && ! $load_locally_enabled ) {
			return SmartToolz_Abilities_Response::error(
				__( 'Cannot enable Preload Local Fonts.', 'smarttoolz' ),
				__( 'Load Google Fonts Locally must be enabled first.', 'smarttoolz' )
			);
		}

		SmartToolz_API_Init::update_admin_settings_option( 'preload_local_fonts', $enabled );

		/* translators: %s: enabled or disabled */
		$message = sprintf( __( 'Preload Local Fonts %s.', 'smarttoolz' ), $enabled ? 'enabled' : 'disabled' );

		return SmartToolz_Abilities_Response::success(
			$message,
			array(
				'updated' => true,
				'enabled' => $enabled,
			)
		);
	}
}

SmartToolz_Update_Preload_Local_Fonts::register();
