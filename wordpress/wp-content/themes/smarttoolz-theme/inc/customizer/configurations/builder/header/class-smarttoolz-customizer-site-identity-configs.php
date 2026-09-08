<?php
/**
 * SmartToolz Theme Customizer Configuration Site Identity.
 *
 * @package     smarttoolz-builder
 * @link        https://wpsmarttoolz.com/
 * @since       3.0.0
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'SmartToolz_Customizer_Config_Base' ) ) {

	/**
	 * Register Site Identity Customizer Configurations.
	 *
	 * @since 3.0.0
	 */
	class SmartToolz_Customizer_Site_Identity_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register Builder Site Identity Customizer Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 3.0.0
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {
			$_configs = smarttoolz_header_site_identity_configuration();

			$wp_customize->remove_control( 'smarttoolz-settings[divider-section-site-identity-logo]' );

			return array_merge( $configurations, $_configs );
		}
	}

	/**
	 * Kicking this off by creating object of this class.
	 */
	new SmartToolz_Customizer_Site_Identity_Configs();
}
