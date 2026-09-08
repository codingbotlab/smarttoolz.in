<?php
/**
 * Update Load Google Fonts Locally Ability
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.12.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Update_Load_Google_Fonts_Locally
 */
class SmartToolz_Update_Load_Google_Fonts_Locally extends SmartToolz_Abstract_Ability {
	/**
	 * Configure the ability.
	 *
	 * @return void
	 */
	public function configure() {
		$this->id          = 'smarttoolz/update-font-google-local';
		$this->category    = 'smarttoolz';
		$this->label       = __( 'Update SmartToolz Load Google Fonts Locally Status', 'smarttoolz' );
		$this->description = __( 'Updates the Load Google Fonts Locally setting for the SmartToolz theme (enable or disable).', 'smarttoolz' );
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
					'description' => 'Enable or disable Load Google Fonts Locally.',
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
			'enable load google fonts locally',
			'disable load google fonts locally',
			'turn on google fonts local loading',
			'turn off google fonts local loading',
			'activate self hosted google fonts',
			'deactivate self hosted google fonts',
			'enable local font loading',
			'disable local font loading',
			'turn on fonts self hosting',
			'turn off fonts self hosting',
			'enable google fonts download',
			'disable google fonts download',
			'activate local google fonts',
			'deactivate local google fonts',
			'enable fonts local storage',
			'disable fonts local storage',
			'turn on google fonts caching',
			'turn off google fonts caching',
			'enable GDPR compliant fonts',
			'disable GDPR compliant fonts',
			'activate fonts offline mode',
			'deactivate fonts offline mode',
			'enable server hosted fonts',
			'disable server hosted fonts',
			'turn on local font files',
			'turn off local font files',
			'enable google fonts on server',
			'disable google fonts on server',
			'activate fonts local hosting',
			'deactivate fonts local hosting',
			'make fonts load from server',
			'stop fonts loading from server',
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

		$enabled = (bool) $args['enabled'];

		SmartToolz_API_Init::update_admin_settings_option( 'self_hosted_gfonts', $enabled );

		/* translators: %s: enabled or disabled */
		$message = sprintf( __( 'Load Google Fonts Locally %s.', 'smarttoolz' ), $enabled ? 'enabled' : 'disabled' );

		return SmartToolz_Abilities_Response::success(
			$message,
			array(
				'updated' => true,
				'enabled' => $enabled,
			)
		);
	}
}

SmartToolz_Update_Load_Google_Fonts_Locally::register();
