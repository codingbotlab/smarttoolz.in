<?php
/**
 * EDD Cart Header Configuration.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register EDD Cart header builder Customizer Configurations.
 *
 * @param array $configurations SmartToolz Customizer Configurations.
 * @since 4.5.2
 * @return array SmartToolz Customizer Configurations with updated configurations.
 */
function smarttoolz_edd_cart_header_configuration( $configurations = array() ) {
	$_section = true === SmartToolz_Builder_Helper::$is_header_footer_builder_active ? 'section-header-edd-cart' : 'section-edd-general';

	/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
	$_cart_total_divider = array( 'ast_class' => defined( 'SMARTTOOLZ_EXT_VER' ) && SmartToolz_Ext_Extension::is_active( 'edd' ) ? 'ast-top-section-divider' : 'ast-section-spacing' );

	$_configs = array(

		/**
		 * EDD Cart section
		 */
		array(
			'name'     => $_section,
			'type'     => 'section',
			'priority' => 5,
			'title'    => __( 'EDD Cart', 'smarttoolz' ),
			'panel'    => 'panel-header-builder-group',
		),

		/**
		 * Option: Header cart total
		 */

		array(
			'name'      => SMARTTOOLZ_THEME_SETTINGS . '[edd-header-cart-total-display]',
			'default'   => smarttoolz_get_option( 'edd-header-cart-total-display' ),
			'type'      => 'control',
			'section'   => $_section,
			'title'     => __( 'Display Cart Total', 'smarttoolz' ),
			'priority'  => 50,
			'transport' => 'postMessage',
			'partial'   => array(
				'selector'            => '.ast-header-edd-cart',
				'container_inclusive' => false,
				'render_callback'     => array( 'SmartToolz_Builder_Header', 'header_edd_cart' ),
			),
			'divider'   => $_cart_total_divider,
			'control'   => 'ast-toggle-control',
			'context'   => SmartToolz_Builder_Helper::$general_tab,
		),

		/**
		 * Option: Cart Title
		 */
		array(
			'name'      => SMARTTOOLZ_THEME_SETTINGS . '[edd-header-cart-title-display]',
			'default'   => smarttoolz_get_option( 'edd-header-cart-title-display' ),
			'type'      => 'control',
			'section'   => $_section,
			'title'     => __( 'Display Cart Title', 'smarttoolz' ),
			'priority'  => 55,
			'transport' => 'postMessage',
			'partial'   => array(
				'selector'            => '.ast-header-edd-cart',
				'container_inclusive' => false,
				'render_callback'     => array( 'SmartToolz_Builder_Header', 'header_edd_cart' ),
			),
			'control'   => 'ast-toggle-control',
			'context'   => SmartToolz_Builder_Helper::$general_tab,
		),
		/**
		 * Option: Icon Style
		 */
		array(
			'name'       => SMARTTOOLZ_THEME_SETTINGS . '[edd-header-cart-icon-style]',
			'default'    => smarttoolz_get_option( 'edd-header-cart-icon-style' ),
			'type'       => 'control',
			'transport'  => 'postMessage',
			'section'    => $_section,
			'title'      => __( 'Style', 'smarttoolz' ),
			'control'    => 'ast-selector',
			'priority'   => 40,
			'choices'    => array(
				'outline' => __( 'Outline', 'smarttoolz' ),
				'fill'    => __( 'Fill', 'smarttoolz' ),
			),
			'divider'    => array( 'ast_class' => 'ast-bottom-divider' ),
			'responsive' => false,
			'renderAs'   => 'text',
			'context'    => SmartToolz_Builder_Helper::$design_tab,
		),

		/**
		 * Option: Background color
		 */
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[edd-header-cart-icon-color]',
			'default'           => smarttoolz_get_option( 'edd-header-cart-icon-color' ),
			'type'              => 'control',
			'control'           => 'ast-color',
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			'title'             => __( 'Color', 'smarttoolz' ),
			'transport'         => 'postMessage',
			'section'           => $_section,
			'context'           => array(
				SmartToolz_Builder_Helper::$design_tab_config,
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[edd-header-cart-icon-style]',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
			'priority'          => 45,
		),

		/**
		 * Option: Border Radius
		 */
		array(
			'name'        => SMARTTOOLZ_THEME_SETTINGS . '[edd-header-cart-icon-radius]',
			'default'     => smarttoolz_get_option( 'edd-header-cart-icon-radius' ),
			'type'        => 'control',
			'transport'   => 'postMessage',
			'section'     => $_section,
			'context'     => array(
				SmartToolz_Builder_Helper::$design_tab_config,
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[edd-header-cart-icon-style]',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
			'title'       => __( 'Border Radius', 'smarttoolz' ),
			'suffix'      => 'px',
			'control'     => 'ast-slider',
			'priority'    => 47,
			'divider'     => array( 'ast_class' => 'ast-top-section-divider' ),
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1,
				'max'  => 200,
			),
		),

		/**
		 * Option: Icon color
		 */
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[transparent-header-edd-cart-icon-color]',
			'default'           => smarttoolz_get_option( 'transparent-header-edd-cart-icon-color' ),
			'type'              => 'control',
			'control'           => 'ast-color',
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			'transport'         => 'postMessage',
			'title'             => __( 'EDD Cart Icon Color', 'smarttoolz' ),
			'context'           => array(
				SmartToolz_Builder_Helper::$design_tab_config,
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[edd-header-cart-icon-style]',
					'operator' => '!=',
					'value'    => 'none',
				),
			),
			'section'           => 'section-transparent-header',
			'priority'          => 95,
		),
	);

	if ( true === SmartToolz_Builder_Helper::$is_header_footer_builder_active ) {
		$_edd_configs = array(
			array(
				'name'        => $_section . '-ast-context-tabs',
				'section'     => $_section,
				'type'        => 'control',
				'control'     => 'ast-builder-header-control',
				'priority'    => 0,
				'description' => '',
			),

			/**
			 * Option: EDD cart tray Section divider
			 */
			array(
				'name'     => SMARTTOOLZ_THEME_SETTINGS . '[section-edd-cart-tray-divider]',
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
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-edd-cart-colors]',
				'section'    => $_section,
				'transport'  => 'postMessage',
				'name'       => 'header-edd-cart-text-color',
				'default'    => smarttoolz_get_option( 'header-edd-cart-text-color' ),
				'title'      => __( 'Text Color', 'smarttoolz' ),
				'responsive' => true,
				'rgba'       => true,
				'priority'   => 65,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
			),

			// Option: Cart Link / Text Color.
			array(
				'type'       => 'sub-control',
				'control'    => 'ast-responsive-color',
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-edd-cart-colors]',
				'section'    => $_section,
				'transport'  => 'postMessage',
				'name'       => 'header-edd-cart-link-color',
				'default'    => smarttoolz_get_option( 'header-edd-cart-link-color' ),
				'title'      => __( 'Link Color', 'smarttoolz' ),
				'responsive' => true,
				'rgba'       => true,
				'priority'   => 65,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
			),

			// Option: Cart Background Color.
			array(
				'type'       => 'control',
				'section'    => $_section,
				'control'    => 'ast-responsive-color',
				'transport'  => 'postMessage',
				'name'       => SMARTTOOLZ_THEME_SETTINGS . '[header-edd-cart-background-color]',
				'default'    => smarttoolz_get_option( 'header-edd-cart-background-color' ),
				'title'      => __( 'Background Color', 'smarttoolz' ),
				'responsive' => true,
				'rgba'       => true,
				'priority'   => 65,
				'divider'    => array( 'ast_class' => 'ast-section-spacing' ),
				'context'    => SmartToolz_Builder_Helper::$design_tab,
			),

			// Option: Cart Separator Color.
			array(
				'type'       => 'control',
				'section'    => $_section,
				'control'    => 'ast-responsive-color',
				'transport'  => 'postMessage',
				'name'       => SMARTTOOLZ_THEME_SETTINGS . '[header-edd-cart-separator-color]',
				'default'    => smarttoolz_get_option( 'header-edd-cart-separator-color' ),
				'title'      => __( 'Separator Color', 'smarttoolz' ),
				'responsive' => true,
				'rgba'       => true,
				'priority'   => 65,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
			),

			// Checkout Button colors.
			array(
				'name'       => SMARTTOOLZ_THEME_SETTINGS . '[header-edd-checkout-button-text-colors]',
				'default'    => smarttoolz_get_option( 'header-edd-checkout-button-text-colors' ),
				'type'       => 'control',
				'control'    => 'ast-color-group',
				'title'      => __( 'Button Text', 'smarttoolz' ),
				'section'    => $_section,
				'transport'  => 'postMessage',
				'priority'   => 75,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
				'responsive' => true,
				'divider'    => array(
					'ast_class' => 'ast-top-divider',
					'ast_title' => __( 'Checkout', 'smarttoolz' ),
				),
			),
			array(
				'name'       => SMARTTOOLZ_THEME_SETTINGS . '[header-edd-checkout-button-background-colors]',
				'default'    => smarttoolz_get_option( 'header-edd-checkout-button-background-colors' ),
				'type'       => 'control',
				'control'    => 'ast-color-group',
				'title'      => __( 'Button Background', 'smarttoolz' ),
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
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-edd-checkout-button-text-colors]',
				'section'    => $_section,
				'transport'  => 'postMessage',
				'name'       => 'header-edd-checkout-btn-text-color',
				'default'    => smarttoolz_get_option( 'header-edd-checkout-btn-text-color' ),
				'title'      => __( 'Normal', 'smarttoolz' ),
				'responsive' => true,
				'rgba'       => true,
				'priority'   => 75,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
			),

			// Option: Checkout Button Background Color.
			array(
				'type'       => 'sub-control',
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-edd-checkout-button-background-colors]',
				'section'    => $_section,
				'control'    => 'ast-responsive-color',
				'transport'  => 'postMessage',
				'name'       => 'header-edd-checkout-btn-background-color',
				'default'    => smarttoolz_get_option( 'header-edd-checkout-btn-background-color' ),
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
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-edd-checkout-button-text-colors]',
				'section'    => $_section,
				'transport'  => 'postMessage',
				'name'       => 'header-edd-checkout-btn-text-hover-color',
				'default'    => smarttoolz_get_option( 'header-edd-checkout-btn-text-hover-color' ),
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
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-edd-checkout-button-background-colors]',
				'section'    => $_section,
				'name'       => 'header-edd-checkout-btn-bg-hover-color',
				'default'    => smarttoolz_get_option( 'header-edd-checkout-btn-bg-hover-color' ),
				'title'      => __( 'Hover', 'smarttoolz' ),
				'responsive' => true,
				'rgba'       => true,
				'priority'   => 75,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
			),
		);

		$configurations = array_merge( $configurations, $_edd_configs );

		$configurations = array_merge( $configurations, SmartToolz_Builder_Base_Configuration::prepare_visibility_tab( $_section ) );

		$_configs = array_merge( $_configs, $configurations );
	}

	if ( SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
		array_map( 'smarttoolz_save_header_customizer_configs', $_configs );
	}

	return $_configs;
}

if ( SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
	add_action( 'init', 'smarttoolz_edd_cart_header_configuration', 10, 0 );
}
