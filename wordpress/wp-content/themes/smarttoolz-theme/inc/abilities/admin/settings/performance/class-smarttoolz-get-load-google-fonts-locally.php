<?php
/**
 * Get Load Google Fonts Locally Ability
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.12.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Get_Load_Google_Fonts_Locally
 */
class SmartToolz_Get_Load_Google_Fonts_Locally extends SmartToolz_Abstract_Ability {
	/**
	 * Configure the ability.
	 *
	 * @return void
	 */
	public function configure() {
		$this->id          = 'smarttoolz/get-font-google-local';
		$this->label       = __( 'Get SmartToolz Load Google Fonts Locally Status', 'smarttoolz' );
		$this->description = __( 'Retrieves the Load Google Fonts Locally setting for the SmartToolz theme (enabled or disabled).', 'smarttoolz' );
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
			'get load google fonts locally status',
			'show google fonts local loading',
			'view load fonts locally setting',
			'display google fonts self hosting',
			'get self hosted fonts status',
			'show local fonts loading',
			'view google fonts local setting',
			'display load google fonts locally',
			'get local google fonts status',
			'show if fonts are loaded locally',
			'view self hosted google fonts',
			'display local font loading status',
			'get google fonts hosting setting',
			'show fonts local loading status',
			'view local fonts configuration',
			'display self hosted fonts setting',
			'get fonts locally status',
			'show google fonts download setting',
			'view fonts server hosting',
			'display fonts local loading',
			'get google fonts caching status',
			'show local font files setting',
			'view fonts self hosting status',
			'display google fonts local cache',
			'get fonts download status',
			'show fonts stored locally',
			'view google fonts offline status',
			'display fonts local storage setting',
			'get local fonts hosting',
			'show fonts GDPR compliance setting',
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

		$enabled = SmartToolz_API_Init::get_admin_settings_option( 'self_hosted_gfonts', false );

		return SmartToolz_Abilities_Response::success(
			__( 'Retrieved Load Google Fonts Locally status successfully.', 'smarttoolz' ),
			array(
				'enabled'       => (bool) $enabled,
				'enabled_label' => $enabled ? 'Enabled' : 'Disabled',
			)
		);
	}
}

SmartToolz_Get_Load_Google_Fonts_Locally::register();
