<?php
/**
 * Flush Local Fonts Cache Ability
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.12.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Flush_Local_Fonts
 */
class SmartToolz_Flush_Local_Fonts extends SmartToolz_Abstract_Ability {
	/**
	 * Configure the ability.
	 *
	 * @return void
	 */
	public function configure() {
		$this->id          = 'smarttoolz/flush-font-local';
		$this->category    = 'smarttoolz';
		$this->label       = __( 'Flush SmartToolz Local Fonts Cache', 'smarttoolz' );
		$this->description = __( 'Flushes the local fonts cache and regenerates font files for the SmartToolz theme.', 'smarttoolz' );
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
		return array();
	}

	/**
	 * Get examples.
	 *
	 * @return array
	 */
	public function get_examples() {
		return array(
			'flush local fonts cache',
			'clear local fonts cache',
			'regenerate local fonts',
			'reset local fonts cache',
			'flush fonts cache',
			'clear fonts cache',
			'regenerate font files',
			'reset fonts cache',
			'flush local font files',
			'clear local font files',
			'regenerate local font assets',
			'reset local font files',
			'flush cached fonts',
			'clear cached fonts',
			'regenerate fonts folder',
			'reset fonts folder',
			'flush google fonts cache',
			'clear google fonts cache',
			'regenerate google fonts',
			'reset google fonts files',
			'flush self hosted fonts',
			'clear self hosted fonts',
			'regenerate self hosted fonts',
			'reset self hosted fonts cache',
			'flush downloaded fonts',
			'clear downloaded fonts',
			'regenerate downloaded fonts',
			'reset downloaded fonts cache',
			'rebuild local fonts',
			'rebuild fonts cache',
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

		$load_locally_enabled = SmartToolz_API_Init::get_admin_settings_option( 'self_hosted_gfonts', false );

		if ( ! $load_locally_enabled ) {
			return SmartToolz_Abilities_Response::error(
				__( 'Cannot flush local fonts cache.', 'smarttoolz' ),
				__( 'Load Google Fonts Locally must be enabled first.', 'smarttoolz' )
			);
		}

		// Reuse the dashboard's flush: deletes the correct fonts folder and clears the cached font options.
		$flushed = smarttoolz_webfont_loader_instance( '' )->smarttoolz_delete_fonts_folder();

		if ( ! $flushed ) {
			return SmartToolz_Abilities_Response::error(
				__( 'Failed to flush local fonts cache.', 'smarttoolz' ),
				__( 'Please try again later.', 'smarttoolz' )
			);
		}

		do_action( 'smarttoolz_regenerate_fonts_folder' );

		return SmartToolz_Abilities_Response::success(
			__( 'Local fonts cache flushed successfully.', 'smarttoolz' ),
			array(
				'flushed' => true,
			)
		);
	}
}

SmartToolz_Flush_Local_Fonts::register();
