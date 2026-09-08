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

if ( ! class_exists( 'SmartToolz_Woo_Shop_Single_Layout_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class SmartToolz_Woo_Shop_Single_Layout_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register SmartToolz-WooCommerce Shop Single Layout Customizer Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$product_divider_title = smarttoolz_has_pro_woocommerce_addon() ? __( 'Product Structure Options', 'smarttoolz' ) : __( 'Product Options', 'smarttoolz' );

			$clonning_attr    = array();
			$add_to_cart_attr = array();

			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			if ( smarttoolz_has_pro_woocommerce_addon() ) {

				/**
				 * Single product extras control.
				 */
				$clonning_attr['summary-extras'] = array(
					'clone'       => false,
					'is_parent'   => true,
					'main_index'  => 'summary-extras',
					'clone_limit' => 2,
					'title'       => __( 'Extras', 'smarttoolz' ),
				);

			}

			/**
			 * Single product add to cart control.
			 */
			$add_to_cart_attr['add_cart'] = array(
				'clone'       => false,
				'is_parent'   => true,
				'main_index'  => 'add_cart',
				'clone_limit' => 2,
				'title'       => __( 'Add To Cart', 'smarttoolz' ),
			);

			/**
			 * Single product payment control.
			 */

			$clonning_attr['single-product-payments'] = array(
				'clone'       => false,
				'is_parent'   => true,
				'main_index'  => 'single-product-payments',
				'clone_limit' => 2,
				'title'       => __( 'Payments', 'smarttoolz' ),
			);

			$_configs = array(

				array(
					'name'        => 'section-woo-shop-single-ast-context-tabs',
					'section'     => 'section-woo-shop-single',
					'type'        => 'control',
					'control'     => 'ast-builder-header-control',
					'priority'    => 0,
					'description' => '',
				),

				/**
				 * Option: Divider.
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[woo-single-product-structure-divider]',
					'section'  => 'section-woo-shop-single',
					'title'    => __( 'Single Product Structure', 'smarttoolz' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 15,
					'settings' => array(),
					'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
				),

				/**
				 * Option: Single Post Meta
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[single-product-structure]',
					'default'           => smarttoolz_get_option( 'single-product-structure' ),
					'type'              => 'control',
					'control'           => 'ast-sortable',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_multi_choices' ),
					'section'           => 'section-woo-shop-single',
					'priority'          => 15,
					'choices'           => array_merge(
						array(
							'title'   => __( 'Title', 'smarttoolz' ),
							'price'   => __( 'Price', 'smarttoolz' ),
							'ratings' => __( 'Ratings', 'smarttoolz' ),
						),
						$add_to_cart_attr,
						array(
							'short_desc' => __( 'Short Description', 'smarttoolz' ),
							'meta'       => __( 'Meta', 'smarttoolz' ),
							'category'   => __( 'Category', 'smarttoolz' ),
						),
						$clonning_attr
					),
				),

				/**
				 * Option: Divider.
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[woo-single-product-structure-fields-divider]',
					'section'  => 'section-woo-shop-single',
					'title'    => $product_divider_title,
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 16,
					'settings' => array(),
					'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
				),

				/**
				 * Option: Disable Breadcrumb
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[single-product-breadcrumb-disable]',
					'section'  => 'section-woo-shop-single',
					'type'     => 'control',
					'control'  => 'ast-toggle-control',
					'default'  => smarttoolz_get_option( 'single-product-breadcrumb-disable' ),
					'title'    => __( 'Enable Breadcrumb', 'smarttoolz' ),
					'priority' => 16,
				),

				/**
				 * Option: Enable free shipping
				 */
				array(
					'name'        => SMARTTOOLZ_THEME_SETTINGS . '[single-product-enable-shipping]',
					'default'     => smarttoolz_get_option( 'single-product-enable-shipping' ),
					'type'        => 'control',
					'section'     => 'section-woo-shop-single',
					'title'       => __( 'Enable Shipping Text', 'smarttoolz' ),
					'description' => __( 'Adds shipping text next to the product price.', 'smarttoolz' ),
					'control'     => 'ast-toggle-control',
					'priority'    => 16,
				),

				/**
				 * Option: Single page variation tab layout.
				 */
				array(
					'name'        => SMARTTOOLZ_THEME_SETTINGS . '[single-product-variation-tabs-layout]',
					'default'     => smarttoolz_get_option( 'single-product-variation-tabs-layout' ),
					'type'        => 'control',
					'section'     => 'section-woo-shop-single',
					'title'       => __( 'Product Variation Layout', 'smarttoolz' ),
					'description' => __( 'Changes single product variation layout to be displayed inline or stacked.', 'smarttoolz' ),
					'context'     => array(
						SmartToolz_Builder_Helper::$general_tab_config,
					),
					'control'     => 'ast-selector',
					'priority'    => 17,
					'choices'     => array(
						'horizontal' => __( 'Inline', 'smarttoolz' ),
						'vertical'   => __( 'Stack', 'smarttoolz' ),
					),
					'renderAs'    => 'text',
					'responsive'  => false,
				),

				/**
				 * Option: Disable Transparent Header on WooCommerce Product pages
				 */
				array(
					'name'     => 'transparent-header-disable-woo-products',
					'parent'   => SMARTTOOLZ_THEME_SETTINGS . '[transparent-header-disable-on]',
					'default'  => smarttoolz_get_option( 'transparent-header-disable-woo-products' ),
					'type'     => 'sub-control',
					'section'  => 'section-transparent-header',
					'title'    => __( 'WooCommerce Product Pages', 'smarttoolz' ),
					'priority' => 26,
					'control'  => 'ast-toggle-control',
				),

				/**
				 * Option: Free shipping text
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[single-product-shipping-text]',
					'default'  => smarttoolz_get_option( 'single-product-shipping-text' ),
					'type'     => 'control',
					'section'  => 'section-woo-shop-single',
					'title'    => __( 'Shipping Text', 'smarttoolz' ),
					'context'  => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[single-product-enable-shipping]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'control'  => 'text',
					'priority' => 16,
					'divider'  => array( 'ast_class' => 'ast-bottom-spacing' ),
				),

				/**
				 * Option: Divider.
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[single-product-sticky-add-to-cart-divider]',
					'section'  => 'section-woo-shop-single',
					'title'    => __( 'Sticky Add To Cart', 'smarttoolz' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 76,
					'settings' => array(),
					'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
				),

				/**
				 * Option: Sticky add to cart.
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[single-product-sticky-add-to-cart]',
					'default'  => smarttoolz_get_option( 'single-product-sticky-add-to-cart' ),
					'type'     => 'control',
					'section'  => 'section-woo-shop-single',
					'title'    => __( 'Enable Sticky Add to Cart', 'smarttoolz' ),
					'control'  => 'ast-toggle-control',
					'priority' => 76,
				),

				/**
				 * Option: Sticky add to cart position.
				 */
				array(
					'name'       => SMARTTOOLZ_THEME_SETTINGS . '[single-product-sticky-add-to-cart-position]',
					'default'    => smarttoolz_get_option( 'single-product-sticky-add-to-cart-position' ),
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => 'section-woo-shop-single',
					'priority'   => 76,
					'title'      => __( 'Sticky Placement ', 'smarttoolz' ),
					'choices'    => array(
						'top'    => __( 'Top', 'smarttoolz' ),
						'bottom' => __( 'Bottom', 'smarttoolz' ),
					),
					'transport'  => 'postMessage',
					'renderAs'   => 'text',
					'responsive' => false,
					'context'    => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[single-product-sticky-add-to-cart]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'divider'    => array( 'ast_class' => 'ast-top-divider' ),
				),

				/**
				 * Option: Divider.
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[woo-single-product-sticky-color-divider]',
					'section'  => 'section-woo-shop-single',
					'title'    => __( 'Sticky Add To Cart Colors', 'smarttoolz' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 82,
					'settings' => array(),
					'context'  => array(
						SmartToolz_Builder_Helper::$design_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[single-product-sticky-add-to-cart]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'divider'  => array( 'ast_class' => 'ast-section-spacing' ),
				),

				/**
				 * Option: Sticky add to cart text color.
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[single-product-sticky-add-to-cart-text-color]',
					'default'           => smarttoolz_get_option( 'single-product-sticky-add-to-cart-text-color' ),
					'type'              => 'control',
					'section'           => 'section-woo-shop-single',
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'transport'         => 'postMessage',
					'title'             => __( 'Text Color', 'smarttoolz' ),
					'context'           => array(
						SmartToolz_Builder_Helper::$design_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[single-product-sticky-add-to-cart]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'priority'          => 82,
					'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
				),

				/**
				 * Option: Sticky add to cart background color.
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[single-product-sticky-add-to-cart-bg-color]',
					'default'           => smarttoolz_get_option( 'single-product-sticky-add-to-cart-bg-color' ),
					'type'              => 'control',
					'section'           => 'section-woo-shop-single',
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'transport'         => 'postMessage',
					'title'             => __( 'Background Color', 'smarttoolz' ),
					'context'           => array(
						SmartToolz_Builder_Helper::$design_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[single-product-sticky-add-to-cart]',
							'operator' => '==',
							'value'    => true,
						),
					),
					'priority'          => 82,
				),

				/**
				 * Option: Sticky add to cart button text color.
				 */
				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[single-product-sticky-add-to-cart-btn-color]',
					'default'   => smarttoolz_get_option( 'single-product-sticky-add-to-cart-btn-color' ),
					'type'      => 'control',
					'control'   => 'ast-color-group',
					'title'     => __( 'Button Text', 'smarttoolz' ),
					'section'   => 'section-woo-shop-single',
					'transport' => 'postMessage',
					'priority'  => 82,
					'context'   => array(
						SmartToolz_Builder_Helper::$design_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[single-product-sticky-add-to-cart]',
							'operator' => '==',
							'value'    => true,
						),
					),
				),

				/**
				 * Option: Link Color.
				 */
				array(
					'type'     => 'sub-control',
					'priority' => 76,
					'parent'   => SMARTTOOLZ_THEME_SETTINGS . '[single-product-sticky-add-to-cart-btn-color]',
					'section'  => 'section-woo-shop-single',
					'control'  => 'ast-color',
					'default'  => smarttoolz_get_option( 'single-product-sticky-add-to-cart-btn-n-color' ),
					'name'     => 'single-product-sticky-add-to-cart-btn-n-color',
					'title'    => __( 'Normal', 'smarttoolz' ),
					'tab'      => __( 'Normal', 'smarttoolz' ),
				),

				/**
				 * Option: Link Hover Color.
				 */
				array(
					'type'              => 'sub-control',
					'priority'          => 82,
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[single-product-sticky-add-to-cart-btn-color]',
					'section'           => 'section-woo-shop-single',
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'default'           => smarttoolz_get_option( 'single-product-sticky-add-to-cart-btn-h-color' ),
					'transport'         => 'postMessage',
					'name'              => 'single-product-sticky-add-to-cart-btn-h-color',
					'title'             => __( 'Hover', 'smarttoolz' ),
					'tab'               => __( 'Hover', 'smarttoolz' ),
				),

				/**
				 * Option: Sticky add to cart button background color.
				 */
				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[single-product-sticky-add-to-cart-btn-bg-color]',
					'default'   => smarttoolz_get_option( 'single-product-sticky-add-to-cart-btn-bg-color' ),
					'type'      => 'control',
					'control'   => 'ast-color-group',
					'title'     => __( 'Button Background', 'smarttoolz' ),
					'section'   => 'section-woo-shop-single',
					'transport' => 'postMessage',
					'priority'  => 82,
					'context'   => array(
						SmartToolz_Builder_Helper::$design_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[single-product-sticky-add-to-cart]',
							'operator' => '==',
							'value'    => true,
						),
					),
				),

				/**
				 * Option: Link Color.
				 */
				array(
					'type'     => 'sub-control',
					'priority' => 82,
					'parent'   => SMARTTOOLZ_THEME_SETTINGS . '[single-product-sticky-add-to-cart-btn-bg-color]',
					'section'  => 'section-woo-shop-single',
					'control'  => 'ast-color',
					'default'  => smarttoolz_get_option( 'single-product-sticky-add-to-cart-btn-bg-n-color' ),
					'name'     => 'single-product-sticky-add-to-cart-btn-bg-n-color',
					'title'    => __( 'Normal', 'smarttoolz' ),
					'tab'      => __( 'Normal', 'smarttoolz' ),
				),

				/**
				 * Option: Link Hover Color.
				 */
				array(
					'type'              => 'sub-control',
					'priority'          => 82,
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[single-product-sticky-add-to-cart-btn-bg-color]',
					'section'           => 'section-woo-shop-single',
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'default'           => smarttoolz_get_option( 'single-product-sticky-add-to-cart-btn-bg-h-color' ),
					'transport'         => 'postMessage',
					'name'              => 'single-product-sticky-add-to-cart-btn-bg-h-color',
					'title'             => __( 'Hover', 'smarttoolz' ),
					'tab'               => __( 'Hover', 'smarttoolz' ),
				),

				/**
				 * Single product payment icon color style.
				 */
				array(
					'name'       => 'single-product-payment-icon-color',
					'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[single-product-structure]',
					'default'    => smarttoolz_get_option( 'single-product-payment-icon-color' ),
					'linked'     => 'single-product-payments',
					'type'       => 'sub-control',
					'control'    => 'ast-selector',
					'section'    => 'section-woo-shop-single',
					'priority'   => 5,
					'title'      => __( 'Choose Icon Colors', 'smarttoolz' ),
					'choices'    => array(
						'inherit'            => __( 'Default', 'smarttoolz' ),
						'inherit_text_color' => __( 'Grayscale', 'smarttoolz' ),
					),
					'transport'  => 'postMessage',
					'responsive' => false,
					'renderAs'   => 'text',
				),

				/**
				 * Single product payment heading text.
				 */
				array(
					'name'      => 'single-product-payment-text',
					'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[single-product-structure]',
					'default'   => smarttoolz_get_option( 'single-product-payment-text' ),
					'linked'    => 'single-product-payments',
					'type'      => 'sub-control',
					'control'   => 'ast-text-input',
					'section'   => 'section-woo-shop-single',
					'priority'  => 5,
					'transport' => 'postMessage',
					'title'     => __( 'Payment Title', 'smarttoolz' ),
					'settings'  => array(),
				),

			);

			/**
			 * Single product extras list.
			 */
			$_configs[] = array(
				'name'        => 'single-product-payment-list',
				'parent'      => SMARTTOOLZ_THEME_SETTINGS . '[single-product-structure]',
				'default'     => smarttoolz_get_option( 'single-product-payment-list' ),
				'linked'      => 'single-product-payments',
				'type'        => 'sub-control',
				'control'     => 'ast-list-icons',
				'section'     => 'section-woo-shop-single',
				'priority'    => 10,
				'divider'     => array( 'ast_class' => 'ast-bottom-divider' ),
				'disable'     => false,
				'input_attrs' => array(
					'text_control_label'       => __( 'Payment Title', 'smarttoolz' ),
					'text_control_placeholder' => __( 'Add payment title', 'smarttoolz' ),
				),
			);

			/**
			 * Option: Button width option
			 */
			$_configs[] = array(
				'name'        => 'single-product-cart-button-width',
				'parent'      => SMARTTOOLZ_THEME_SETTINGS . '[single-product-structure]',
				'default'     => smarttoolz_get_option( 'single-product-cart-button-width' ),
				'linked'      => 'add_cart',
				'type'        => 'sub-control',
				'control'     => 'ast-responsive-slider',
				'responsive'  => true,
				'section'     => 'section-woo-shop-single',
				'priority'    => 11,
				'title'       => __( 'Button Width', 'smarttoolz' ),
				'transport'   => 'postMessage',
				'suffix'      => '%',
				'input_attrs' => array(
					'min'  => 1,
					'step' => 1,
					'max'  => 100,
				),
			);

			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			if ( smarttoolz_has_pro_woocommerce_addon() ) {
				/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				$_configs[] = array(
					'name'        => 'single-product-cart-button-width',
					'parent'      => SMARTTOOLZ_THEME_SETTINGS . '[single-product-structure]',
					'default'     => smarttoolz_get_option( 'single-product-cart-button-width' ),
					'linked'      => 'add_cart',
					'type'        => 'sub-control',
					'control'     => 'ast-responsive-slider',
					'responsive'  => true,
					'section'     => 'section-woo-shop-single',
					'priority'    => 11,
					'title'       => __( 'Button Width', 'smarttoolz' ),
					'transport'   => 'postMessage',
					'suffix'      => '%',
					'input_attrs' => array(
						'min'  => 1,
						'step' => 1,
						'max'  => 100,
					),
				);

			} else {
				$_configs[] = array(
					'name'        => SMARTTOOLZ_THEME_SETTINGS . '[single-product-cart-button-width]',
					'default'     => smarttoolz_get_option( 'single-product-cart-button-width' ),
					'type'        => 'control',
					'transport'   => 'postMessage',
					'responsive'  => true,
					'control'     => 'ast-responsive-slider',
					'section'     => 'section-woo-shop-single',
					'title'       => __( 'Button Width', 'smarttoolz' ),
					'suffix'      => '%',
					'priority'    => 16,
					'input_attrs' => array(
						'min'  => 1,
						'step' => 1,
						'max'  => 100,
					),
					'divider'     => array( 'ast_class' => 'ast-top-section-divider ast-bottom-section-divider' ),
				);
			}

			if ( ! defined( 'SMARTTOOLZ_EXT_VER' ) ) {
				$_configs[] = array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[sticky-add-to-cart-notice]',
					'type'     => 'control',
					'control'  => 'ast-description',
					'section'  => 'section-woo-shop-single',
					'priority' => 5,
					'label'    => '',
					'help'     => __( 'Note: To get design settings make sure to enable sticky add to cart.', 'smarttoolz' ),
					'context'  => array(
						SmartToolz_Builder_Helper::$design_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[single-product-sticky-add-to-cart]',
							'operator' => '==',
							'value'    => false,
						),
					),
				);

				if ( smarttoolz_showcase_upgrade_notices() ) {
					// Learn More link if SmartToolz Pro is not activated.
					$_configs[] = array(
						'name'      => SMARTTOOLZ_THEME_SETTINGS . '[ast-woo-single-product-pro-items]',
						'type'      => 'control',
						'control'   => 'ast-upgrade',
						'campaign'  => 'woocommerce',
						'choices'   => array(
							// 'two'   => array(
							// 'title' => __( 'More product galleries', 'smarttoolz' ),
							// ),
							// 'three' => array(
							// 'title' => __( 'Sticky product summary', 'smarttoolz' ),
							// ),
							// 'five'  => array(
							// 'title' => __( 'Product description layouts', 'smarttoolz' ),
							// ),
							// 'six'   => array(
							// 'title' => __( 'Related, Upsell product controls', 'smarttoolz' ),
							// ),
							// 'seven' => array(
							// 'title' => __( 'Extras option for product structure', 'smarttoolz' ),
							// ),
							// 'eight' => array(
							// 'title' => __( 'More typography options', 'smarttoolz' ),
							// ),
							// 'nine'  => array(
							// 'title' => __( 'More color options', 'smarttoolz' ),
							// ),
							// 'one'   => array(
							// 'title' => __( 'More design controls', 'smarttoolz' ),
							// ),
							'one'   => array(
								'title' => __( ' Advanced Gallery Layouts', 'smarttoolz' ),
							),
							'two'   => array(
								'title' => __( ' Smooth Zoom & Sticky Image Effects', 'smarttoolz' ),
							),
							'three' => array(
								'title' => __( ' Upsells, Related & Recently Viewed Products', 'smarttoolz' ),
							),
						),
						'section'   => 'section-woo-shop-single',
						'default'   => '',
						'priority'  => 999,
						'title'     => __( 'Get Full Control Over Product Pages', 'smarttoolz' ),
						'divider'   => array( 'ast_class' => 'ast-top-section-divider' ),
						'context'   => array(),
						'thumbnail' => SMARTTOOLZ_THEME_URI . 'inc/assets/images/customizer/woo-single.png',
					);
				}
			}

			return array_merge( $configurations, $_configs );
		}
	}
}

new SmartToolz_Woo_Shop_Single_Layout_Configs();
