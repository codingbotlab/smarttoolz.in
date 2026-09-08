<?php
/**
 * Get Preload Local Fonts Ability
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.12.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Get_Preload_Local_Fonts
 */
class SmartToolz_Get_Preload_Local_Fonts extends SmartToolz_Abstract_Ability {
	/**
	 * Configure the ability.
	 *
	 * @return void
	 */
	public function configure() {
		$this->id          = 'smarttoolz/get-font-preload-local';
		$this->label       = __( 'Get SmartToolz Preload Local Fonts Status', 'smarttoolz' );
		$this->description = __( 'Retrieves the Preload Local Fonts setting for the SmartToolz theme (enabled or disabled).', 'smarttoolz' );
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
			'get preload local fonts status',
			'show preload fonts setting',
			'view preload local fonts',
			'display fonts preloading status',
			'get local fonts preload setting',
			'show if fonts are preloaded',
			'view fonts preload configuration',
			'display preload fonts status',
			'get fonts preloading setting',
			'show local fonts preload status',
			'view preload setting for fonts',
			'display fonts eager loading',
			'get fonts preload on page load',
			'show fonts immediate loading',
			'view fonts preload optimization',
			'display preload local fonts setting',
			'get fonts instant loading status',
			'show fonts preload feature',
			'view local fonts eager load',
			'display fonts preload performance',
			'get fonts priority loading',
			'show preload fonts optimization',
			'view fonts fast loading setting',
			'display fonts preload behavior',
			'get fonts immediate load status',
			'show fonts preload configuration',
			'view fonts speed optimization',
			'display local fonts preload',
			'get fonts quick load setting',
			'show fonts preload on startup',
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

		$enabled              = SmartToolz_API_Init::get_admin_settings_option( 'preload_local_fonts', false );
		$load_locally_enabled = SmartToolz_API_Init::get_admin_settings_option( 'self_hosted_gfonts', false );

		return SmartToolz_Abilities_Response::success(
			__( 'Retrieved Preload Local Fonts status successfully.', 'smarttoolz' ),
			array(
				'enabled'               => (bool) $enabled,
				'enabled_label'         => $enabled ? 'Enabled' : 'Disabled',
				'load_locally_enabled'  => (bool) $load_locally_enabled,
				'load_locally_required' => ! $load_locally_enabled,
			)
		);
	}
}

SmartToolz_Get_Preload_Local_Fonts::register();
