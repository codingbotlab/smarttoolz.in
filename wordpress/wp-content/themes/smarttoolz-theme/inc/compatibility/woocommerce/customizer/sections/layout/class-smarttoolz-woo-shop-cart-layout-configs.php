<?php
/**
 * WooCommerce Options for SmartToolz Theme.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SmartToolz_Woo_Shop_Cart_Layout_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class SmartToolz_Woo_Shop_Cart_Layout_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register SmartToolz-WooCommerce Shop Cart Layout Customizer Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(
				/**
				 * Option: Enable checkout button text
				 */
				array(
					'name'        => SMARTTOOLZ_THEME_SETTINGS . '[woo-enable-cart-button-text]',
					'default'     => smarttoolz_get_option( 'woo-enable-cart-button-text' ),
					'type'        => 'control',
					'section'     => 'section-woo-shop-cart',
					'title'       => __( 'Change Cart Button Text', 'smarttoolz' ),
					'description' => __( 'Add custom text for cart button', 'smarttoolz' ),
					'control'     => 'ast-toggle-control',
					'priority'    => 2,
				),

				/**
				 * Option: Checkout
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[woo-cart-button-text]',
					'default'  => smarttoolz_get_option( 'woo-cart-button-text' ),
					'type'     => 'control',
					'section'  => 'section-woo-shop-cart',
					'title'    => __( 'Cart Button Text', 'smarttoolz' ),
					'context'  => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[woo-enable-cart-button-text]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'control'  => 'text',
					'priority' => 2,
				),

				/**
				 * Option: Cart upsells
				 *
				 * Enable Cross-sells - in the code it is refrenced as upsells rather than cross-sells.
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[enable-cart-upsells]',
					'section'  => 'section-woo-shop-cart',
					'type'     => 'control',
					'control'  => 'ast-toggle-control',
					'default'  => smarttoolz_get_option( 'enable-cart-upsells' ),
					'title'    => __( 'Enable Cross-sells', 'smarttoolz' ),
					'priority' => 2.7,
				),
			);

			if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '8.3', '>=' ) ) {
				$_configs[] = array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[woo-block-incompatible-cart-notice]',
					'type'     => 'control',
					'control'  => 'ast-description',
					'section'  => 'section-woo-shop-cart',
					'priority' => 1,
					'label'    => '',
					'help'     => '<strong>' . __( 'Note:', 'smarttoolz' ) . '</strong>' . __( ' Certain Cart page options may not work smoothly on the block editor based Cart page. For best results with these features, prefer using a shortcode based Cart page.', 'smarttoolz' ),
				);

				$_configs[] = array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[woo-block-incompatible-checkout-notice]',
					'type'     => 'control',
					'control'  => 'ast-description',
					'section'  => 'woocommerce_checkout',
					'priority' => 1,
					'label'    => '',
					'help'     => '<strong>' . __( 'Note:', 'smarttoolz' ) . '</strong>' . __( ' Certain Checkout page options may not work smoothly on the block editor based Checkout page. For best results with these features, prefer using a shortcode-based Checkout page.', 'smarttoolz' ),
				);
			}

			if ( smarttoolz_showcase_upgrade_notices() ) {
				// Learn More link if SmartToolz Pro is not activated.
				$_configs[] = array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[ast-woo-cart-pro-items]',
					'type'      => 'control',
					'control'   => 'ast-upgrade',
					'campaign'  => 'woocommerce',
					'choices'   => array(
						// 'two'   => array(
						// 'title' => __( 'Modern cart layout', 'smarttoolz' ),
						// ),
						// 'one'   => array(
						// 'title' => __( 'Sticky cart totals', 'smarttoolz' ),
						// ),
						// 'three' => array(
						// 'title' => __( 'Real-time quantity updater', 'smarttoolz' ),
						// ),
						'one'   => array(
							'title' => __( 'Real-Time Quantity Updates', 'smarttoolz' ),
						),
						'two'   => array(
							'title' => __( 'Sticky Cart Totals for Better UX', 'smarttoolz' ),
						),
						'three' => array(
							'title' => __( 'Modern, Clean Cart Layout', 'smarttoolz' ),
						),
					),
					'section'   => 'section-woo-shop-cart',
					'default'   => '',
					'priority'  => 999,
					'title'     => __( 'Optimize Your Cart for Sales', 'smarttoolz' ),
					'divider'   => array( 'ast_class' => 'ast-top-section-divider' ),
					'context'   => array(),
					'thumbnail' => SMARTTOOLZ_THEME_URI . 'inc/assets/images/customizer/woo-cart.png',
				);

				$_configs[] = array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[ast-woo-checkout-pro-items]',
					'type'      => 'control',
					'control'   => 'ast-upgrade',
					'campaign'  => 'woocommerce',
					'choices'   => array(
						// 'two'   => array(
						// 'title' => __( 'Modern layout', 'smarttoolz' ),
						// ),
						// 'one'   => array(
						// 'title' => __( 'Multi-column layouts', 'smarttoolz' ),
						// ),
						// 'three' => array(
						// 'title' => __( 'Modern order received layout', 'smarttoolz' ),
						// ),
						// 'four'  => array(
						// 'title' => __( 'Sticky order review', 'smarttoolz' ),
						// ),
						// 'five'  => array(
						// 'title' => __( 'Two-step checkout', 'smarttoolz' ),
						// ),
						// 'six'   => array(
						// 'title' => __( 'Order note, Coupon field control', 'smarttoolz' ),
						// ),
						// 'seven' => array(
						// 'title' => __( 'Distraction free checkout', 'smarttoolz' ),
						// ),
						// 'eight' => array(
						// 'title' => __( 'Persistent checkout form data', 'smarttoolz' ),
						// ),
						// 'nine'  => array(
						// 'title' => __( 'Text form options', 'smarttoolz' ),
						// ),
						// 'ten'   => array(
						// 'title' => __( 'Summary, Payment background', 'smarttoolz' ),
						// ),
						'one'   => array(
							'title' => __( 'Sticky Totals & Saved Form Data', 'smarttoolz' ),
						),
						'two'   => array(
							'title' => __( '2-Step & Distraction-Free Layouts', 'smarttoolz' ),
						),
						'three' => array(
							'title' => __( 'Full Control Over Notes, Coupons & Layouts', 'smarttoolz' ),
						),
					),
					'section'   => 'woocommerce_checkout',
					'default'   => '',
					'priority'  => 999,
					'title'     => __( 'Smarter Checkout. More Conversions', 'smarttoolz' ),
					'divider'   => array( 'ast_class' => 'ast-top-section-divider' ),
					'context'   => array(),
					'thumbnail' => SMARTTOOLZ_THEME_URI . 'inc/assets/images/customizer/woo-checkout.png',
				);
			}

			return array_merge( $configurations, $_configs );
		}
	}
}

new SmartToolz_Woo_Shop_Cart_Layout_Configs();
