<?php
/**
 * WooCommerce Options for SmartToolz Theme.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 3.9.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SmartToolz_Woo_Shop_Misc_Layout_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class SmartToolz_Woo_Shop_Misc_Layout_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register SmartToolz-WooCommerce Misc Customizer Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 3.9.2
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Enable Quantity Plus and Minus.
				 */
				array(
					'name'        => SMARTTOOLZ_THEME_SETTINGS . '[single-product-plus-minus-button]',
					'default'     => smarttoolz_get_option( 'single-product-plus-minus-button' ),
					'type'        => 'control',
					'section'     => 'section-woo-misc',
					'title'       => __( 'Enable Quantity Plus and Minus', 'smarttoolz' ),
					'description' => __( 'Adds plus and minus buttons besides product quantity', 'smarttoolz' ),
					'priority'    => 59,
					'control'     => 'ast-toggle-control',
				),

			);

			/**
			 * Option: Adds tabs only if smarttoolz addons is enabled.
			 */
			if ( smarttoolz_has_pro_woocommerce_addon() ) {
				$_configs[] = array(
					'name'        => 'section-woo-general-tabs',
					'section'     => 'section-woo-misc',
					'type'        => 'control',
					'control'     => 'ast-builder-header-control',
					'priority'    => 0,
					'description' => '',
				);
			}

			if ( smarttoolz_showcase_upgrade_notices() ) {
				// Learn More link if SmartToolz Pro is not activated.
				$_configs[] = array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[ast-woo-misc-pro-items]',
					'type'     => 'control',
					'control'  => 'ast-upgrade',
					'campaign' => 'woocommerce',
					'choices'  => array(
						// 'two'   => array(
						// 'title' => __( 'Modern input style', 'smarttoolz' ),
						// ),
						// 'one'   => array(
						// 'title' => __( 'Sale badge modifications', 'smarttoolz' ),
						// ),
						// 'three' => array(
						// 'title' => __( 'Ecommerce steps navigation', 'smarttoolz' ),
						// ),
						// 'four'  => array(
						// 'title' => __( 'Quantity updater designs', 'smarttoolz' ),
						// ),
						// 'five'  => array(
						// 'title' => __( 'Modern my-account page', 'smarttoolz' ),
						// ),
						// 'six'   => array(
						// 'title' => __( 'Downloads, Orders grid view', 'smarttoolz' ),
						// ),
						// 'seven' => array(
						// 'title' => __( 'Modern thank-you page design', 'smarttoolz' ),
						// ),
						'one' => array(
							'title' => __( 'Advanced Input Field Styles & Border Radius', 'smarttoolz' ),
						),
						'two' => array(
							'title' => __( 'Custom Coupon Text & Step Navigation', 'smarttoolz' ),
						),
					),
					'section'  => 'section-woo-misc',
					'default'  => '',
					'priority' => 999,
					'title'    => __( 'Get Sleek Storefront. Better UX', 'smarttoolz' ),
					'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
					'context'  => array(),
				);
			}

			return array_merge( $configurations, $_configs );
		}
	}
}

new SmartToolz_Woo_Shop_Misc_Layout_Configs();
