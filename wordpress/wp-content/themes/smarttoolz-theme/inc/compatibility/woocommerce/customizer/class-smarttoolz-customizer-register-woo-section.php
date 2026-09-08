<?php
/**
 * Register customizer panels & sections fro Woocommerce.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 1.1.0
 * @since       1.4.6 Chnaged to using SmartToolz_Customizer API
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'SmartToolz_Customizer_Register_Woo_Section' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class SmartToolz_Customizer_Register_Woo_Section extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register Panels and Sections for Customizer.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$configs = array(

				array(
					'name'     => 'section-woo-shop',
					'title'    => __( 'Shop', 'smarttoolz' ),
					'type'     => 'section',
					'priority' => 20,
					'panel'    => 'woocommerce',
				),

				array(
					'name'     => 'section-woo-shop-single',
					'type'     => 'section',
					'title'    => __( 'Single Product', 'smarttoolz' ),
					'priority' => 12,
					'panel'    => 'woocommerce',
				),

				array(
					'name'     => 'section-woo-shop-cart',
					'type'     => 'section',
					'title'    => __( 'Cart', 'smarttoolz' ),
					'priority' => 20,
					'panel'    => 'woocommerce',
				),

				array(
					'name'     => 'section-woo-general',
					'title'    => __( 'General', 'smarttoolz' ),
					'type'     => 'section',
					'priority' => 10,
					'panel'    => 'woocommerce',
				),

				array(
					'name'     => 'section-woo-misc',
					'title'    => __( 'Misc', 'smarttoolz' ),
					'type'     => 'section',
					'priority' => 24.5,
					'panel'    => 'woocommerce',
				),
			);

			return array_merge( $configurations, $configs );
		}
	}
}

new SmartToolz_Customizer_Register_Woo_Section();
