<?php
/**
 * SmartToolz Theme Customizer Configuration Builder.
 *
 * @package     smarttoolz-builder
 * @link        https://wpsmarttoolz.com/
 * @since       3.0.0
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SmartToolz_Customizer_Config_Base' ) ) {
	return;
}

/**
 * Register Builder Customizer Configurations.
 *
 * @since 3.0.0
 */
class SmartToolz_Customizer_Woo_Cart_Configs extends SmartToolz_Customizer_Config_Base {
	/**
	 * Register Builder Customizer Configurations.
	 *
	 * @param Array                $configurations SmartToolz Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since 3.0.0
	 * @return Array SmartToolz Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {
		if ( is_callable( 'smarttoolz_header_woo_cart_configuration' ) ) {
			$configurations = smarttoolz_header_woo_cart_configuration( $configurations );
		}
		return $configurations;
	}
}

/**
 * Kicking this off by creating object of this class.
 */

new SmartToolz_Customizer_Woo_Cart_Configs();
