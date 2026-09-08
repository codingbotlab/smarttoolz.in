<?php
/**
 * WooCommerce cart Header Configuration.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register woo-cart header builder Customizer Configurations.
 *
 * @param array $configurations SmartToolz Customizer Configurations.
 * @since 4.5.2
 * @return array SmartToolz Customizer Configurations with updated configurations.
 */
function smarttoolz_header_woo_cart_configuration( $configurations = array() ) {
	$_section                   = true === SmartToolz_Builder_Helper::$is_header_footer_builder_active ? 'section-header-woo-cart' : 'section-woo-shop-cart';
	$smarttoolz_hfb_enabled          = true === SmartToolz_Builder_Helper::$is_header_footer_builder_active ? true : false;
	$cart_outline_width_context = true === SmartToolz_Builder_Helper::$is_header_footer_builder_active ? SmartToolz_Builder_Helper::$design_tab_config : SmartToolz_Builder_Helper::$general_tab_config;

	$cart_icon_choices = array();

	$woo_cart_icon_new_user = smarttoolz_get_option( 'smarttoolz-woocommerce-cart-icons-flag', true );

	if ( apply_filters( 'smarttoolz_woocommerce_cart_icon', $woo_cart_icon_new_user ) ) {

		$default_icon_value = 'bag';

		if ( 'default' === smarttoolz_get_option( 'woo-header-cart-icon' ) ) {
			smarttoolz_update_option( 'woo-header-cart-icon', $default_icon_value );
		}

		$cart_icon_choices = array(
			'bag'    => 'shopping-bag',
			'cart'   => 'shopping-cart',
			'basket' => 'shopping-basket',
		);

	} else {

		$default_icon_value = 'default';

		$cart_icon_choices = array(
			'default' => 'shopping-default',
			'bag'     => 'shopping-bag',
			'cart'    => 'shopping-cart',
			'basket'  => 'shopping-basket',
		);
	}

	$_configs = array(

		/**
		 * Option: WOO cart General Section divider
		 */
		array(
			'name'     => SMARTTOOLZ_THEME_SETTINGS . '[section-woo-cart-label-divider]',
			'type'     => 'control',
			'control'  => 'ast-heading',
			'section'  => $_section,
			'title'    => __( 'Cart', 'smarttoolz' ),
			'priority' => 3,
			'settings' => array(),
			'context'  => SmartToolz_Builder_Helper::$general_tab,
			'divider'  => $smarttoolz_hfb_enabled ? array( 'ast_class' => 'ast-bottom-spacing' ) : array( 'ast_class' => 'ast-section-spacing' ),
		),

		/**
		 * Option: Header Cart Icon
		 */
		array(
			'name'       => SMARTTOOLZ_THEME_SETTINGS . '[woo-header-cart-icon]',
			'default'    => smarttoolz_get_option( 'woo-header-cart-icon', $default_icon_value ),
			'type'       => 'control',
			'control'    => 'ast-selector',
			'section'    => $_section,
			'priority'   => 3,
			'title'      => __( 'Select Cart Icon', 'smarttoolz' ),
			'choices'    => $cart_icon_choices,
			'transport'  => 'postMessage',
			'context'    => SmartToolz_Builder_Helper::$general_tab,
			'responsive' => false,
			'divider'    => true === SmartToolz_Builder_Helper::$is_header_footer_builder_active ? array( 'ast_class' => 'ast-top-spacing ast-bottom-section-divider' ) : array(),
		),

		/**
		 * Option: Cart Label
		 */
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[woo-header-cart-label-display]',
			'default'           => smarttoolz_get_option( 'woo-header-cart-label-display' ),
			'type'              => 'control',
			'section'           => $_section,
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_html' ),
			'partial'           => array(
				'selector'            => '.ast-header-woo-cart',
				'container_inclusive' => false,
				'render_callback'     => array( SmartToolz_Builder_Header::get_instance(), 'header_woo_cart' ),
			),
			'priority'          => $smarttoolz_hfb_enabled ? 50 : 3.5,
			'title'             => __( 'Cart Label', 'smarttoolz' ),
			'control'           => 'ast-input-with-dropdown',
			'choices'           => array(
				'{cart_currency_name}'         => __( 'Currency Name', 'smarttoolz' ),
				'{cart_total}'                 => __( 'Total amount', 'smarttoolz' ),
				'{cart_currency_symbol}'       => __( 'Currency Symbol', 'smarttoolz' ),
				'{cart_total_currency_symbol}' => __( 'Total + Currency symbol', 'smarttoolz' ),
			),
			'context'           => SmartToolz_Builder_Helper::$general_tab,
			'divider'           => $smarttoolz_hfb_enabled ? array( 'ast_class' => 'ast-top-spacing' ) : array( 'ast_class' => 'ast-section-spacing' ),
		),

		/**
		 * Notice for Display Cart label.
		 */
		array(
			'name'     => SMARTTOOLZ_THEME_SETTINGS . '[woo-header-cart-label-display-notice]',
			'type'     => 'control',
			'control'  => 'ast-description',
			'section'  => $_section,
			'priority' => $smarttoolz_hfb_enabled ? 50 : 3.5,
			'context'  => SmartToolz_Builder_Helper::$general_tab,
			'help'     => '<p>' . __( 'Note: The Cart Label on the header will be displayed by using shortcodes. Type any custom string in it or click on the plus icon above to add your desired shortcode.', 'smarttoolz' ) . '</p>',
		),

		/**
		 * Option: Cart product count badge.
		 */
		array(
			'name'      => SMARTTOOLZ_THEME_SETTINGS . '[woo-header-cart-badge-display]',
			'default'   => smarttoolz_get_option( 'woo-header-cart-badge-display' ),
			'type'      => 'control',
			'section'   => $_section,
			'title'     => __( 'Display Cart Count', 'smarttoolz' ),
			'priority'  => $smarttoolz_hfb_enabled ? 55 : 3.5,
			'transport' => 'postMessage',
			'control'   => 'ast-toggle-control',
			'context'   => SmartToolz_Builder_Helper::$general_tab,
			'divider'   => array( 'ast_class' => 'ast-top-section-divider' ),
		),

		/**
		 * Option: Cart product count badge.
		 */
		array(
			'name'        => SMARTTOOLZ_THEME_SETTINGS . '[woo-header-cart-total-label]',
			'default'     => smarttoolz_get_option( 'woo-header-cart-total-label' ),
			'type'        => 'control',
			'section'     => $_section,
			'title'       => __( 'Hide Cart Total Label', 'smarttoolz' ),
			'description' => __( 'Hide cart total label if cart is empty', 'smarttoolz' ),
			'priority'    => $smarttoolz_hfb_enabled ? 55 : 3.5,
			'transport'   => 'postMessage',
			'control'     => 'ast-toggle-control',
			'context'     => SmartToolz_Builder_Helper::$general_tab,
		),

		/**
		 * Option: WOO cart tray Section divider
		 */
		array(
			'name'     => SMARTTOOLZ_THEME_SETTINGS . '[section-woo-cart-click-divider]',
			'type'     => 'control',
			'control'  => 'ast-heading',
			'section'  => $_section,
			'title'    => __( 'Cart Click', 'smarttoolz' ),
			'priority' => 60,
			'settings' => array(),
			'context'  => array(
				SmartToolz_Builder_Helper::$desktop_general_tab,
				array(
					'setting' => 'ast_selected_tab',
					'value'   => 'general',
				),
			),
			'divider'  => array( 'ast_class' => 'ast-section-spacing' ),
		),

		/**
		 * Option: Cart icon click action.
		 */
		array(
			'name'       => SMARTTOOLZ_THEME_SETTINGS . '[woo-header-cart-click-action]',
			'default'    => smarttoolz_get_option( 'woo-header-cart-click-action' ),
			'type'       => 'control',
			'section'    => $_section,
			'title'      => __( 'Cart Click Action', 'smarttoolz' ),
			'control'    => 'ast-selector',
			'priority'   => 60,
			'choices'    => array(
				'default'  => __( 'Dropdown', 'smarttoolz' ),
				'flyout'   => __( 'Slide-In', 'smarttoolz' ),
				'redirect' => __( 'Cart Page', 'smarttoolz' ),
			),
			'responsive' => false,
			'renderAs'   => 'text',
			'context'    => SmartToolz_Builder_Helper::$desktop_general_tab,
			'transport'  => 'postMessage',
		),

		/**
		 * Option: Cart icon click action for responsive devices.
		 */
		array(
			'name'        => SMARTTOOLZ_THEME_SETTINGS . '[responsive-cart-click-action]',
			'default'     => smarttoolz_get_option( 'responsive-cart-click-action' ),
			'type'        => 'control',
			'section'     => $_section,
			'title'       => __( 'Responsive Cart Click Action', 'smarttoolz' ),
			'control'     => 'ast-selector',
			'priority'    => 65,
			'choices'     => array(
				'flyout'   => __( 'Slide-In', 'smarttoolz' ),
				'redirect' => __( 'Cart Page', 'smarttoolz' ),
			),
			'responsive'  => false,
			'renderAs'    => 'text',
			'description' => __( 'This responsive cart click option will work for tablet and mobile in same way', 'smarttoolz' ),
			'context'     => array(
				SmartToolz_Builder_Helper::$desktop_general_tab,
				array(
					'setting'  => 'ast_selected_device',
					'operator' => 'in',
					'value'    => array( 'tablet', 'mobile' ),
				),
			),
			'transport'   => 'refresh',
		),
		/**
		 * Option: Woo sidebar Off-Canvas Slide-Out.
		 */
		array(
			'name'       => SMARTTOOLZ_THEME_SETTINGS . '[woo-desktop-cart-flyout-direction]',
			'default'    => smarttoolz_get_option( 'woo-desktop-cart-flyout-direction' ),
			'type'       => 'control',
			'control'    => 'ast-selector',
			'section'    => $_section,
			'priority'   => 65,
			'title'      => __( 'Position', 'smarttoolz' ),
			'choices'    => array(
				'left'  => __( 'Left', 'smarttoolz' ),
				'right' => __( 'Right', 'smarttoolz' ),
			),
			'context'    => array(
				SmartToolz_Builder_Helper::$general_tab_config,
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[woo-header-cart-click-action]',
					'operator' => '==',
					'value'    => 'flyout',
				),
			),
			'renderAs'   => 'text',
			'responsive' => false,
			'divider'    => array( 'ast_class' => 'ast-top-divider' ),
		),

		/**
		 * Option: Slide In Cart Width.
		 */
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[woo-slide-in-cart-width]',
			'type'              => 'control',
			'context'           => SmartToolz_Builder_Helper::$general_tab_config,
			'control'           => 'ast-responsive-slider',
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
			'section'           => $_section,
			'transport'         => 'postMessage',
			'title'             => __( 'Slide in Cart Width', 'smarttoolz' ),
			'priority'          => 65,
			'default'           => smarttoolz_get_option( 'woo-slide-in-cart-width' ),
			'suffix'            => array( 'px', '%' ),
			'input_attrs'       => array(
				'px' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 1920,
				),
				'%'  => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
			),
			'divider'           => array( 'ast_class' => 'ast-top-divider' ),
		),

		/**
		 * Option: WOO cart Icon Design Section divider
		 */
		array(
			'name'     => SMARTTOOLZ_THEME_SETTINGS . '[section-woo-cart-icon-style-divider]',
			'type'     => 'control',
			'control'  => 'ast-heading',
			'section'  => $_section,
			'title'    => __( 'Cart Icon', 'smarttoolz' ),
			'priority' => 45,
			'settings' => array(),
			'context'  => SmartToolz_Builder_Helper::$design_tab,
		),

		/**
		 * Option: Icon Style
		 */
		array(
			'name'       => SMARTTOOLZ_THEME_SETTINGS . '[woo-header-cart-icon-style]',
			'default'    => smarttoolz_get_option( 'woo-header-cart-icon-style' ),
			'type'       => 'control',
			'transport'  => 'postMessage',
			'section'    => $_section,
			'title'      => __( 'Style', 'smarttoolz' ),
			'control'    => 'ast-selector',
			'priority'   => 45,
			'choices'    => array(
				'outline' => __( 'Outline', 'smarttoolz' ),
				'fill'    => __( 'Fill', 'smarttoolz' ),
			),
			'responsive' => false,
			'renderAs'   => 'text',
			'context'    => SmartToolz_Builder_Helper::$design_tab,
			'divider'    => array( 'ast_class' => 'ast-section-spacing ast-bottom-divider' ),
		),

		/**
		 * Option: Icon color
		 */
		array(
			'name'       => SMARTTOOLZ_THEME_SETTINGS . '[header-woo-cart-icon-colors]',
			'default'    => smarttoolz_get_option( 'header-woo-cart-icon-colors' ),
			'type'       => 'control',
			'control'    => 'ast-color-group',
			'title'      => __( 'Cart Color', 'smarttoolz' ),
			'section'    => $_section,
			'transport'  => 'postMessage',
			'priority'   => 45,
			'context'    => array(
				SmartToolz_Builder_Helper::$design_tab_config,
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[woo-header-cart-icon-style]',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
			'responsive' => false,
		),

		/**
		 * Option: Icon Normal Color section
		 */
		array(
			'type'       => 'sub-control',
			'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-woo-cart-icon-colors]',
			'section'    => $_section,
			'control'    => 'ast-responsive-color',
			'transport'  => 'postMessage',
			'name'       => 'header-woo-cart-icon-color',
			'default'    => smarttoolz_get_option( 'header-woo-cart-icon-color' ),
			'title'      => __( 'Normal', 'smarttoolz' ),
			'responsive' => false,
			'rgba'       => true,
			'priority'   => 65,
			'context'    => SmartToolz_Builder_Helper::$design_tab,
		),

		/**
		 * Option: Icon Hover Color section
		 */
		array(
			'type'       => 'sub-control',
			'control'    => 'ast-responsive-color',
			'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-woo-cart-icon-colors]',
			'section'    => $_section,
			'name'       => 'header-woo-cart-icon-hover-color',
			'default'    => smarttoolz_get_option( 'header-woo-cart-icon-hover-color' ),
			'title'      => __( 'Hover', 'smarttoolz' ),
			'responsive' => false,
			'rgba'       => true,
			'priority'   => 65,
			'context'    => SmartToolz_Builder_Helper::$design_tab,
		),

		array(
			'name'       => SMARTTOOLZ_THEME_SETTINGS . '[woo-header-cart-product-count-color-group]',
			'default'    => smarttoolz_get_option( 'woo-header-cart-product-count-color-group' ),
			'type'       => 'control',
			'control'    => 'ast-color-group',
			'title'      => __( 'Count Color', 'smarttoolz' ),
			'section'    => $_section,
			'transport'  => 'postMessage',
			'priority'   => 45,
			'context'    => array(
				SmartToolz_Builder_Helper::$design_tab_config,
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[woo-header-cart-icon-style]',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
			'responsive' => false,
			'divider'    => array( 'ast_class' => 'ast-bottom-divider' ),
		),

		array(
			'type'       => 'sub-control',
			'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[woo-header-cart-product-count-color-group]',
			'section'    => $_section,
			'control'    => 'ast-responsive-color',
			'transport'  => 'postMessage',
			'name'       => 'woo-header-cart-product-count-color',
			'default'    => smarttoolz_get_option( 'woo-header-cart-product-count-color' ),
			'title'      => __( 'Normal', 'smarttoolz' ),
			'responsive' => false,
			'rgba'       => true,
			'priority'   => 45,
			'context'    => SmartToolz_Builder_Helper::$design_tab,
		),

		/**
		 * Option: Icon Hover Color section
		 */
		array(
			'type'       => 'sub-control',
			'control'    => 'ast-responsive-color',
			'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[woo-header-cart-product-count-color-group]',
			'section'    => $_section,
			'transport'  => 'postMessage',
			'name'       => 'woo-header-cart-product-count-h-color',
			'default'    => smarttoolz_get_option( 'woo-header-cart-product-count-h-color' ),
			'title'      => __( 'Hover', 'smarttoolz' ),
			'responsive' => false,
			'rgba'       => true,
			'priority'   => 45,
			'context'    => SmartToolz_Builder_Helper::$design_tab,
		),

		/**
		 * Option: Border Width
		 */
		array(
			'name'        => SMARTTOOLZ_THEME_SETTINGS . '[woo-header-cart-border-width]',
			'default'     => smarttoolz_get_option( 'woo-header-cart-border-width' ),
			'type'        => 'control',
			'transport'   => 'postMessage',
			'section'     => $_section,
			'context'     => array(
				$cart_outline_width_context,
				'relation' => 'AND',
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[woo-header-cart-icon-style]',
					'operator' => '==',
					'value'    => 'outline',
				),
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[woo-header-cart-icon]',
					'operator' => '!=',
					'value'    => 'default',
				),
			),
			'title'       => __( 'Border Width', 'smarttoolz' ),
			'control'     => 'ast-slider',
			'suffix'      => 'px',
			'priority'    => 46,
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1,
				'max'  => 20,
			),
		),

		/**
		 * Option: Border Radius Fields
		 */
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[woo-header-cart-icon-radius-fields]',
			'default'           => smarttoolz_get_option( 'woo-header-cart-icon-radius-fields' ),
			'type'              => 'control',
			'control'           => 'ast-responsive-spacing',
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
			'section'           => $_section,
			'title'             => __( 'Border Radius', 'smarttoolz' ),
			'linked_choices'    => true,
			'transport'         => 'postMessage',
			'unit_choices'      => array( 'px', 'em', '%' ),
			'choices'           => array(
				'top'    => __( 'Top', 'smarttoolz' ),
				'right'  => __( 'Right', 'smarttoolz' ),
				'bottom' => __( 'Bottom', 'smarttoolz' ),
				'left'   => __( 'Left', 'smarttoolz' ),
			),
			'priority'          => 47,
			'connected'         => false,
			'divider'           => array( 'ast_class' => 'ast-bottom-section-divider' ),
			'context'           => array(
				SmartToolz_Builder_Helper::$design_tab_config,
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[woo-header-cart-icon-style]',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
		),

		/**
		 * Option: Icon total label position.
		 */
		array(
			'name'       => SMARTTOOLZ_THEME_SETTINGS . '[woo-header-cart-icon-total-label-position]',
			'default'    => smarttoolz_get_option( 'woo-header-cart-icon-total-label-position' ),
			'type'       => 'control',
			'transport'  => 'postMessage',
			'section'    => $_section,
			'title'      => __( 'Cart Label Position', 'smarttoolz' ),
			'control'    => 'ast-selector',
			'priority'   => 47,
			'choices'    => array(
				'left'   => __( 'Left', 'smarttoolz' ),
				'right'  => __( 'Right', 'smarttoolz' ),
				'bottom' => __( 'Bottom', 'smarttoolz' ),

			),
			'responsive' => true,
			'renderAs'   => 'text',
			'context'    => array(
				SmartToolz_Builder_Helper::$design_tab_config,
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[woo-header-cart-label-display]',
					'operator' => '!=',
					'value'    => '',
				),
			),
			'divider'    => array( 'ast_class' => 'ast-bottom-section-divider' ),
		),

		/**
		 * Option: Icon color
		 */
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[transparent-header-woo-cart-icon-color]',
			'default'           => smarttoolz_get_option( 'transparent-header-woo-cart-icon-color' ),
			'type'              => 'control',
			'control'           => 'ast-color',
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			'transport'         => 'postMessage',
			'title'             => __( 'Woo Cart Icon Color', 'smarttoolz' ),
			'context'           => array(
				SmartToolz_Builder_Helper::$design_tab_config,
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[woo-header-cart-icon-style]',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
			'section'           => 'section-transparent-header',
			'priority'          => 85,
			'divider'           => array( 'ast_class' => 'ast-top-divider ast-top-divider' ),
		),
	);

	/**
	 * Adding the Margin and Padding option.
	 * $_section: section-header-woo-cart.
	 */
	if ( true === SmartToolz_Builder_Helper::$is_header_footer_builder_active ) {
		$_configs = array_merge( $_configs, SmartToolz_Extended_Base_Configuration::prepare_advanced_tab( $_section ) );
	}

	$configurations                    = array_merge( $configurations, $_configs );
	$header_woo_cart_background_colors = 'header-woo-cart-background-colors';

	$_configs = array(
		/**
		 * Option: Divider
		 */
		array(
			'name'     => SMARTTOOLZ_THEME_SETTINGS . '[header-cart-icon-divider]',
			'section'  => $_section,
			'title'    => __( 'Header Cart Icon', 'smarttoolz' ),
			'type'     => 'control',
			'control'  => 'ast-heading',
			'priority' => $smarttoolz_hfb_enabled ? 30 : 20,
			'settings' => array(),
			'context'  => SmartToolz_Builder_Helper::$general_tab,
			'divider'  => $smarttoolz_hfb_enabled ? array() : array( 'ast_class' => 'ast-section-spacing' ),
		),
	);

	if ( true === SmartToolz_Builder_Helper::$is_header_footer_builder_active ) {
		$_configs = array(
			/**
			 * Woo Cart section
			 */
			array(
				'name'     => $_section,
				'type'     => 'section',
				'priority' => 5,
				'title'    => __( 'WooCommerce Cart', 'smarttoolz' ),
				'panel'    => 'panel-header-builder-group',
			),

			/**
			 * Option: Cart Icon Size
			 */
			array(
				'name'              => SMARTTOOLZ_THEME_SETTINGS . '[header-woo-cart-icon-size]',
				'section'           => $_section,
				'transport'         => 'postMessage',
				'default'           => smarttoolz_get_option( 'header-woo-cart-icon-size', 15 ),
				'title'             => __( 'Icon Size', 'smarttoolz' ),
				'type'              => 'control',
				'suffix'            => 'px',
				'control'           => 'ast-responsive-slider',
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
				'priority'          => 48,
				'input_attrs'       => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 100,
				),
				'context'           => array(
					SmartToolz_Builder_Helper::$design_tab_config,
				),
			),

			/**
			 * Woo Cart Tabs
			 */
			array(
				'name'        => $_section . '-ast-context-tabs',
				'section'     => $_section,
				'type'        => 'control',
				'control'     => 'ast-builder-header-control',
				'priority'    => 0,
				'description' => '',
			),

			/**
			 * Option: WOO cart tray Section divider
			 */
			array(
				'name'     => SMARTTOOLZ_THEME_SETTINGS . '[section-woo-cart-tray-divider]',
				'type'     => 'control',
				'control'  => 'ast-heading',
				'section'  => $_section,
				'title'    => __( 'Cart Tray', 'smarttoolz' ),
				'priority' => 60,
				'settings' => array(),
				'context'  => SmartToolz_Builder_Helper::$design_tab,
				'divider'  => array( 'ast_class' => 'ast-section-spacing' ),
			),

			// Option: Cart Link / Text Color.
			array(
				'type'       => 'sub-control',
				'control'    => 'ast-responsive-color',
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-woo-cart-colors]',
				'section'    => $_section,
				'transport'  => 'postMessage',
				'name'       => 'header-woo-cart-text-color',
				'default'    => smarttoolz_get_option( 'header-woo-cart-text-color' ),
				'title'      => __( 'Text Color', 'smarttoolz' ),
				'responsive' => true,
				'rgba'       => true,
				'priority'   => 65,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
			),
			// Option: Cart Background Color.
			array(
				'name'       => SMARTTOOLZ_THEME_SETTINGS . '[' . $header_woo_cart_background_colors . ']',
				'default'    => smarttoolz_get_option( 'header-woo-cart-background-colors' ),
				'type'       => 'control',
				'control'    => 'ast-color-group',
				'title'      => __( 'Background Color', 'smarttoolz' ),
				'section'    => $_section,
				'transport'  => 'postMessage',
				'priority'   => 65,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
				'responsive' => true,
				'divider'    => array( 'ast_class' => 'ast-section-spacing' ),
			),
			// Option: Cart Background Color.
			array(
				'type'       => 'sub-control',
				'control'    => 'ast-responsive-color',
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[' . $header_woo_cart_background_colors . ']',
				'section'    => $_section,
				'transport'  => 'postMessage',
				'name'       => 'header-woo-cart-background-color',
				'default'    => smarttoolz_get_option( 'header-woo-cart-background-color' ),
				'title'      => __( 'Normal', 'smarttoolz' ),
				'responsive' => true,
				'rgba'       => true,
				'priority'   => 65,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
			),

			// Option: Cart Background Hover Color.
			array(
				'type'       => 'sub-control',
				'control'    => 'ast-responsive-color',
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[' . $header_woo_cart_background_colors . ']',
				'section'    => $_section,
				'transport'  => 'postMessage',
				'name'       => 'header-woo-cart-background-hover-color',
				'default'    => smarttoolz_get_option( 'header-woo-cart-background-hover-color' ),
				'title'      => __( 'Hover', 'smarttoolz' ),
				'responsive' => true,
				'rgba'       => true,
				'priority'   => 65,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
			),

			// Option: Cart Separator Color.
			array(
				'type'       => 'control',
				'section'    => $_section,
				'control'    => 'ast-responsive-color',
				'transport'  => 'postMessage',
				'name'       => SMARTTOOLZ_THEME_SETTINGS . '[header-woo-cart-separator-color]',
				'default'    => smarttoolz_get_option( 'header-woo-cart-separator-color' ),
				'title'      => __( 'Separator Color', 'smarttoolz' ),
				'responsive' => true,
				'rgba'       => true,
				'priority'   => 65,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
			),

			array(
				'name'       => SMARTTOOLZ_THEME_SETTINGS . '[header-woo-cart-link-colors]',
				'default'    => smarttoolz_get_option( 'header-woo-cart-link-colors' ),
				'type'       => 'control',
				'control'    => 'ast-color-group',
				'title'      => __( 'Link Color', 'smarttoolz' ),
				'section'    => $_section,
				'transport'  => 'postMessage',
				'priority'   => 65,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
				'responsive' => true,
			),

			// Option: Cart Link / Text Color.
			array(
				'type'       => 'sub-control',
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-woo-cart-link-colors]',
				'section'    => $_section,
				'control'    => 'ast-responsive-color',
				'transport'  => 'postMessage',
				'name'       => 'header-woo-cart-link-color',
				'default'    => smarttoolz_get_option( 'header-woo-cart-link-color' ),
				'title'      => __( 'Normal', 'smarttoolz' ),
				'responsive' => true,
				'rgba'       => true,
				'priority'   => 65,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
			),

			// Option: Cart Link Color.
			array(
				'type'       => 'sub-control',
				'control'    => 'ast-responsive-color',
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-woo-cart-link-colors]',
				'section'    => $_section,
				'transport'  => 'postMessage',
				'name'       => 'header-woo-cart-link-hover-color',
				'default'    => smarttoolz_get_option( 'header-woo-cart-link-hover-color' ),
				'title'      => __( 'Hover', 'smarttoolz' ),
				'responsive' => true,
				'rgba'       => true,
				'priority'   => 65,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
			),

			/**
			 * Option: WOO cart button Section divider
			 */
			array(
				'name'     => SMARTTOOLZ_THEME_SETTINGS . '[section-woo-cart-button-color-divider]',
				'type'     => 'control',
				'control'  => 'ast-heading',
				'section'  => $_section,
				'title'    => __( 'Cart Button', 'smarttoolz' ),
				'priority' => 70,
				'settings' => array(),
				'context'  => SmartToolz_Builder_Helper::$design_tab,
				'divider'  => array( 'ast_class' => 'ast-section-spacing ast-top-divider' ),

			),

			array(
				'name'       => SMARTTOOLZ_THEME_SETTINGS . '[header-woo-cart-button-text-colors]',
				'default'    => smarttoolz_get_option( 'header-woo-cart-button-text-colors' ),
				'type'       => 'control',
				'control'    => 'ast-color-group',
				'title'      => __( 'Text', 'smarttoolz' ),
				'section'    => $_section,
				'transport'  => 'postMessage',
				'priority'   => 70,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
				'responsive' => true,
				'divider'    => array(
					'ast_class' => 'ast-section-spacing',
				),
			),
			array(
				'name'       => SMARTTOOLZ_THEME_SETTINGS . '[header-woo-cart-button-background-colors]',
				'default'    => smarttoolz_get_option( 'header-woo-cart-button-background-colors' ),
				'type'       => 'control',
				'control'    => 'ast-color-group',
				'title'      => __( 'Background', 'smarttoolz' ),
				'section'    => $_section,
				'transport'  => 'postMessage',
				'priority'   => 70,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
				'responsive' => true,
			),

			// Option: Cart Button Text Color.
			array(
				'type'       => 'sub-control',
				'control'    => 'ast-responsive-color',
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-woo-cart-button-text-colors]',
				'section'    => $_section,
				'transport'  => 'postMessage',
				'name'       => 'header-woo-cart-btn-text-color',
				'default'    => smarttoolz_get_option( 'header-woo-cart-btn-text-color' ),
				'title'      => __( 'Normal', 'smarttoolz' ),
				'responsive' => true,
				'rgba'       => true,
				'priority'   => 70,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
			),

			// Option: Cart Button Background Color.
			array(
				'type'       => 'sub-control',
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-woo-cart-button-background-colors]',
				'section'    => $_section,
				'control'    => 'ast-responsive-color',
				'transport'  => 'postMessage',
				'name'       => 'header-woo-cart-btn-background-color',
				'default'    => smarttoolz_get_option( 'header-woo-cart-btn-background-color' ),
				'title'      => __( 'Normal', 'smarttoolz' ),
				'responsive' => true,
				'rgba'       => true,
				'priority'   => 70,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
			),

			// Option: Cart Button Hover Text Color.
			array(
				'type'       => 'sub-control',
				'control'    => 'ast-responsive-color',
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-woo-cart-button-text-colors]',
				'section'    => $_section,
				'transport'  => 'postMessage',
				'name'       => 'header-woo-cart-btn-text-hover-color',
				'default'    => smarttoolz_get_option( 'header-woo-cart-btn-text-hover-color' ),
				'title'      => __( 'Hover', 'smarttoolz' ),
				'responsive' => true,
				'rgba'       => true,
				'priority'   => 70,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
			),

			// Option: Cart Button Hover Background Color.
			array(
				'type'       => 'sub-control',
				'control'    => 'ast-responsive-color',
				'transport'  => 'postMessage',
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-woo-cart-button-background-colors]',
				'section'    => $_section,
				'name'       => 'header-woo-cart-btn-bg-hover-color',
				'default'    => smarttoolz_get_option( 'header-woo-cart-btn-bg-hover-color' ),
				'title'      => __( 'Hover', 'smarttoolz' ),
				'responsive' => true,
				'rgba'       => true,
				'priority'   => 70,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
			),

			/**
			 * Option: WOO cart button Section divider
			 */
			array(
				'name'     => SMARTTOOLZ_THEME_SETTINGS . '[section-woo-checkout-button-color-divider]',
				'type'     => 'control',
				'control'  => 'ast-heading',
				'section'  => $_section,
				'title'    => __( 'Checkout Button', 'smarttoolz' ),
				'priority' => 75,
				'settings' => array(),
				'context'  => SmartToolz_Builder_Helper::$design_tab,
				'divider'  => array( 'ast_class' => 'ast-section-spacing' ),
			),

			array(
				'name'       => SMARTTOOLZ_THEME_SETTINGS . '[header-woo-checkout-button-text-colors]',
				'default'    => smarttoolz_get_option( 'header-woo-checkout-button-text-colors' ),
				'type'       => 'control',
				'control'    => 'ast-color-group',
				'title'      => __( 'Text', 'smarttoolz' ),
				'section'    => $_section,
				'transport'  => 'postMessage',
				'priority'   => 75,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
				'responsive' => true,
				'divider'    => array(
					'ast_class' => 'ast-section-spacing',
				),
			),
			array(
				'name'       => SMARTTOOLZ_THEME_SETTINGS . '[header-woo-checkout-button-background-colors]',
				'default'    => smarttoolz_get_option( 'header-woo-checkout-button-background-colors' ),
				'type'       => 'control',
				'control'    => 'ast-color-group',
				'title'      => __( 'Background', 'smarttoolz' ),
				'section'    => $_section,
				'transport'  => 'postMessage',
				'priority'   => 75,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
				'responsive' => true,
			),

			// Option: Checkout Button Text Color.
			array(
				'type'       => 'sub-control',
				'control'    => 'ast-responsive-color',
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-woo-checkout-button-text-colors]',
				'section'    => $_section,
				'transport'  => 'postMessage',
				'name'       => 'header-woo-checkout-btn-text-color',
				'default'    => smarttoolz_get_option( 'header-woo-checkout-btn-text-color' ),
				'title'      => __( 'Normal', 'smarttoolz' ),
				'responsive' => true,
				'rgba'       => true,
				'priority'   => 75,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
			),

			// Option: Checkout Button Background Color.
			array(
				'type'       => 'sub-control',
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-woo-checkout-button-background-colors]',
				'section'    => $_section,
				'control'    => 'ast-responsive-color',
				'transport'  => 'postMessage',
				'name'       => 'header-woo-checkout-btn-background-color',
				'default'    => smarttoolz_get_option( 'header-woo-checkout-btn-background-color' ),
				'title'      => __( 'Normal', 'smarttoolz' ),
				'responsive' => true,
				'rgba'       => true,
				'priority'   => 75,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
			),

			// Option: Checkout Button Hover Text Color.
			array(
				'type'       => 'sub-control',
				'control'    => 'ast-responsive-color',
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-woo-checkout-button-text-colors]',
				'section'    => $_section,
				'transport'  => 'postMessage',
				'name'       => 'header-woo-checkout-btn-text-hover-color',
				'default'    => smarttoolz_get_option( 'header-woo-checkout-btn-text-hover-color' ),
				'title'      => __( 'Hover', 'smarttoolz' ),
				'responsive' => true,
				'rgba'       => true,
				'priority'   => 75,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
			),

			// Option: Checkout Button Hover Background Color.
			array(
				'type'       => 'sub-control',
				'control'    => 'ast-responsive-color',
				'transport'  => 'postMessage',
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-woo-checkout-button-background-colors]',
				'section'    => $_section,
				'name'       => 'header-woo-checkout-btn-bg-hover-color',
				'default'    => smarttoolz_get_option( 'header-woo-checkout-btn-bg-hover-color' ),
				'title'      => __( 'Hover', 'smarttoolz' ),
				'responsive' => true,
				'rgba'       => true,
				'priority'   => 75,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
			),
		);

		$_configs = array_merge( $_configs, SmartToolz_Builder_Base_Configuration::prepare_visibility_tab( $_section ) );

	}

	// Learn More link if SmartToolz Pro is not activated.
	if ( smarttoolz_showcase_upgrade_notices() ) {

		$_configs[] = array(

			'name'     => SMARTTOOLZ_THEME_SETTINGS . '[ast-woo-cart-button-link]',
			'type'     => 'control',
			'control'  => 'ast-button-link',
			'section'  => $_section,
			'priority' => 999,
			'title'    => __( 'View SmartToolz Pro Features', 'smarttoolz' ),
			'url'      => smarttoolz_get_pro_url( '/pricing/', 'free-theme', 'customizer', 'header-builder' ),
			'settings' => array(),
			'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
			'context'  => array(),
		);
	}

	$_configs = array_merge( $_configs, $configurations );

	if ( SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
		array_map( 'smarttoolz_save_header_customizer_configs', $_configs );
	}

	return $_configs;
}

if ( SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
	add_action( 'init', 'smarttoolz_header_woo_cart_configuration', 10, 0 );
}
