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

if ( ! class_exists( 'SmartToolz_Woo_Shop_Layout_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class SmartToolz_Woo_Shop_Layout_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register SmartToolz-WooCommerce Shop Layout Customizer Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$smarttoolz_addon_with_woo = smarttoolz_has_pro_woocommerce_addon() ? true : false;
			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

			$add_to_cart_attr             = array();
			$ratings                      = array();
			$smarttoolz_shop_page_pro_features = array();

			if ( $smarttoolz_addon_with_woo ) {
				$smarttoolz_shop_page_pro_features = array(
					'redirect_cart_page'     => __( 'Redirect To Cart Page', 'smarttoolz' ),
					'redirect_checkout_page' => __( 'Redirect To Checkout Page', 'smarttoolz' ),
				);
			}

			/**
			 * Shop product add to cart control.
			 */
			$add_to_cart_attr['add_cart'] = array(
				'clone'       => false,
				'is_parent'   => true,
				'main_index'  => 'add_cart',
				'clone_limit' => 2,
				'title'       => __( 'Add To Cart', 'smarttoolz' ),
			);

			/**
			 * Shop product total review count.
			 */
			$ratings['ratings'] = array(
				'clone'       => false,
				'is_parent'   => true,
				'main_index'  => 'ratings',
				'clone_limit' => 2,
				'title'       => __( 'Ratings', 'smarttoolz' ),
			);

			if ( $smarttoolz_addon_with_woo ) {
				$current_shop_layouts = array(
					'shop-page-grid-style'   => array(
						'label' => __( 'Design 1', 'smarttoolz' ),
						'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'shop-grid-view', false ) : '',
					),
					'shop-page-modern-style' => array(
						'label' => __( 'Design 2', 'smarttoolz' ),
						'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'shop-modern-view', false ) : '',
					),
					'shop-page-list-style'   => array(
						'label' => __( 'Design 3', 'smarttoolz' ),
						'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'shop-list-view', false ) : '',
					),
				);
			} else {
				$current_shop_layouts = array(
					'shop-page-grid-style'   => array(
						'label' => __( 'Design 1', 'smarttoolz' ),
						'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'shop-grid-view', false ) : '',
					),
					'shop-page-modern-style' => array(
						'label' => __( 'Design 2', 'smarttoolz' ),
						'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'shop-modern-view', false ) : '',
					),
				);
			}

			$_configs = array(

				/**
				 * Option: Context for shop archive section.
				 */
				array(
					'name'        => 'section-woocommerce-shop-context-tabs',
					'section'     => 'woocommerce_product_catalog',
					'type'        => 'control',
					'control'     => 'ast-builder-header-control',
					'priority'    => 0,
					'description' => '',
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[shop-box-styling]',
					'section'  => 'woocommerce_product_catalog',
					'title'    => __( 'Shop Card Styling', 'smarttoolz' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 229,
					'settings' => array(),
					'context'  => array(
						SmartToolz_Builder_Helper::$design_tab_config,
					),
					'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
				),

				/**
				 * Option: Content Alignment
				 */
				array(
					'name'       => SMARTTOOLZ_THEME_SETTINGS . '[shop-product-align-responsive]',
					'default'    => smarttoolz_get_option( 'shop-product-align-responsive' ),
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => 'woocommerce_product_catalog',
					'priority'   => 229,
					'title'      => __( 'Horizontal Content Alignment', 'smarttoolz' ),
					'responsive' => true,
					'choices'    => array(
						'align-left'   => 'align-left',
						'align-center' => 'align-center',
						'align-right'  => 'align-right',
					),
					'context'    => array(
						SmartToolz_Builder_Helper::$design_tab_config,
					),
					'divider'    => ! defined( 'SMARTTOOLZ_EXT_VER' ) ? array() : array( 'ast_class' => 'ast-bottom-section-divider' ),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[woo-shop-structure-divider]',
					'section'  => 'woocommerce_product_catalog',
					'title'    => __( 'Shop Card Structure', 'smarttoolz' ),
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
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[shop-product-structure]',
					'type'              => 'control',
					'control'           => 'ast-sortable',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_multi_choices' ),
					'section'           => 'woocommerce_product_catalog',
					'default'           => smarttoolz_get_option( 'shop-product-structure' ),
					'priority'          => 15,
					'choices'           => array_merge(
						array(
							'title'      => __( 'Title', 'smarttoolz' ),
							'price'      => __( 'Price', 'smarttoolz' ),
							'short_desc' => __( 'Short Description', 'smarttoolz' ),
						),
						$add_to_cart_attr,
						array(
							'category' => __( 'Category', 'smarttoolz' ),
						),
						$ratings
					),
					'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[woo-shop-skin-divider]',
					'section'  => 'woocommerce_product_catalog',
					'title'    => __( 'Shop Layout', 'smarttoolz' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 7,
					'settings' => array(),
					'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
				),

				/**
				 * Option: Choose Product Style
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[shop-style]',
					'default'           => smarttoolz_get_option( 'shop-style' ),
					'type'              => 'control',
					'section'           => 'woocommerce_product_catalog',
					'title'             => __( 'Shop Card Design', 'smarttoolz' ),
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_choices' ),
					'priority'          => 8,
					'choices'           => $current_shop_layouts,
					'divider'           => array( 'ast_class' => 'ast-bottom-section-divider' ),
				),

				/**
				 * Option: Shop Columns
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[shop-grids]',
					'type'              => 'control',
					'control'           => 'ast-responsive-slider',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
					'section'           => 'woocommerce_product_catalog',
					'default'           => smarttoolz_get_option(
						'shop-grids',
						array(
							'desktop' => 4,
							'tablet'  => 3,
							'mobile'  => 2,
						)
					),
					'priority'          => 9,
					'title'             => __( 'Shop Columns', 'smarttoolz' ),
					'input_attrs'       => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 6,
					),
					'divider'           => array( 'ast_class' => 'ast-bottom-section-divider' ),
				),

				/**
				 * Option: Products Per Page
				 */
				array(
					'name'         => SMARTTOOLZ_THEME_SETTINGS . '[shop-no-of-products]',
					'type'         => 'control',
					'section'      => 'woocommerce_product_catalog',
					'title'        => __( 'Products Per Page', 'smarttoolz' ),
					'default'      => smarttoolz_get_option( 'shop-no-of-products' ),
					'control'      => 'ast-number',
					'qty_selector' => true,
					'priority'     => 9,
					'input_attrs'  => array(
						'min'  => 1,
						'step' => 1,
						'max'  => 100,
					),
				),

				/**
				 * Option: Shop Archive Content Width
				 */
				array(
					'name'       => SMARTTOOLZ_THEME_SETTINGS . '[shop-archive-width]',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => 'woocommerce_product_catalog',
					'default'    => smarttoolz_get_option( 'shop-archive-width' ),
					'priority'   => 9,
					'title'      => __( 'Shop Archive Content Width', 'smarttoolz' ),
					'choices'    => array(
						'default' => __( 'Default', 'smarttoolz' ),
						'custom'  => __( 'Custom', 'smarttoolz' ),
					),
					'transport'  => 'refresh',
					'renderAs'   => 'text',
					'responsive' => false,
					'divider'    => $smarttoolz_addon_with_woo ? array( 'ast_class' => 'ast-top-section-divider' ) : array( 'ast_class' => 'ast-section-spacing' ),
				),

				/**
				 * Option: Enter Width
				 */
				array(
					'name'        => SMARTTOOLZ_THEME_SETTINGS . '[shop-archive-max-width]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'section'     => 'woocommerce_product_catalog',
					'default'     => smarttoolz_get_option( 'shop-archive-max-width' ),
					'priority'    => 9,
					'title'       => __( 'Custom Width', 'smarttoolz' ),
					'transport'   => 'postMessage',
					'suffix'      => 'px',
					'input_attrs' => array(
						'min'  => 768,
						'step' => 1,
						'max'  => 1920,
					),
					'context'     => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[shop-archive-width]',
							'operator' => '===',
							'value'    => 'custom',
						),
					),
					'divider'     => array( 'ast_class' => 'ast-top-divider' ),
				),
			);

			/**
			 * Option: Shop add to cart action.
			 */
			$_configs[] = array(
				'name'       => 'shop-add-to-cart-action',
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[shop-product-structure]',
				'default'    => smarttoolz_get_option( 'shop-add-to-cart-action' ),
				'section'    => 'woocommerce_product_catalog',
				'title'      => __( 'Add To Cart Action', 'smarttoolz' ),
				'type'       => 'sub-control',
				'control'    => 'ast-select',
				'linked'     => 'add_cart',
				'priority'   => 10,
				'choices'    => array_merge(
					array(
						'default'       => __( 'Default', 'smarttoolz' ),
						'slide_in_cart' => __( 'Slide In Cart', 'smarttoolz' ),
					),
					$smarttoolz_shop_page_pro_features
				),
				'responsive' => false,
				'renderAs'   => 'text',
				'transport'  => 'postMessage',
			);

				/**
				 * Total Review count option config.
				 */
				$_configs[] = array(
					'name'       => 'shop-ratings-product-archive',
					'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[shop-product-structure]',
					'default'    => smarttoolz_get_option( 'shop-ratings-product-archive' ),
					'linked'     => 'ratings',
					'type'       => 'sub-control',
					'control'    => 'ast-selector',
					'section'    => 'woocommerce_product_catalog',
					'priority'   => 10,
					'title'      => __( 'Review Count', 'smarttoolz' ),
					'choices'    => array(
						'default'      => __( 'Default', 'smarttoolz' ),
						'count_string' => __( 'Count + Text', 'smarttoolz' ),
					),
					'transport'  => 'postMessage',
					'responsive' => false,
					'renderAs'   => 'text',
				);

				/**
				 * Option: Shop add to cart action notice.
				 */
				$_configs[] = array(
					'name'     => 'shop-add-to-cart-action-notice',
					'parent'   => SMARTTOOLZ_THEME_SETTINGS . '[shop-product-structure]',
					'type'     => 'sub-control',
					'control'  => 'ast-description',
					'section'  => 'woocommerce_product_catalog',
					'priority' => 10,
					'label'    => '',
					'linked'   => 'add_cart',
					'help'     => __( 'Please publish the changes and see result on the frontend.<br />[Slide in cart requires Cart added inside Header Builder]', 'smarttoolz' ),
				);

				// Learn More link if SmartToolz Pro is not activated.
				if ( smarttoolz_showcase_upgrade_notices() ) {
					$_configs[] = array(
						'name'      => SMARTTOOLZ_THEME_SETTINGS . '[ast-woo-shop-pro-items]',
						'type'      => 'control',
						'control'   => 'ast-upgrade',
						'campaign'  => 'woocommerce',
						'choices'   => array(
							// 'two'   => array(
							// 'title' => __( 'More shop design layouts', 'smarttoolz' ),
							// ),
							// 'three' => array(
							// 'title' => __( 'Shop toolbar structure', 'smarttoolz' ),
							// ),
							// 'five'  => array(
							// 'title' => __( 'Offcanvas product filters', 'smarttoolz' ),
							// ),
							// 'six'   => array(
							// 'title' => __( 'Products quick view', 'smarttoolz' ),
							// ),
							// 'seven' => array(
							// 'title' => __( 'Shop pagination', 'smarttoolz' ),
							// ),
							// 'eight' => array(
							// 'title' => __( 'More typography options', 'smarttoolz' ),
							// ),
							// 'nine'  => array(
							// 'title' => __( 'More color options', 'smarttoolz' ),
							// ),
							// 'ten'   => array(
							// 'title' => __( 'More spacing options', 'smarttoolz' ),
							// ),
							// 'four'  => array(
							// 'title' => __( 'Box shadow design options', 'smarttoolz' ),
							// ),
							// 'one'   => array(
							// 'title' => __( 'More design controls', 'smarttoolz' ),
							// ),
							'one'   => array(
								'title' => __( 'Multiple Shop Card Designs', 'smarttoolz' ),
							),
							'two'   => array(
								'title' => __( 'Flexible Toolbar & Filter Layouts', 'smarttoolz' ),
							),
							'three' => array(
								'title' => __( 'Customizable Shop Structure Options', 'smarttoolz' ),
							),
							'four'  => array(
								'title' => __( 'Stylish Pagination Options', 'smarttoolz' ),
							),
						),
						'section'   => 'woocommerce_product_catalog',
						'default'   => '',
						'priority'  => 999,
						'title'     => __( 'Get advanced product catalog controls', 'smarttoolz' ),
						'divider'   => array( 'ast_class' => 'ast-top-section-divider' ),
						'context'   => array(),
						'thumbnail' => SMARTTOOLZ_THEME_URI . 'inc/assets/images/customizer/woo-catalog.png',
					);
				}

				return array_merge( $configurations, $_configs );
		}
	}
}

new SmartToolz_Woo_Shop_Layout_Configs();
