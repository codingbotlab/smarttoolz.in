<?php
/**
 * Mobile Trigger Header Configuration.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Header Trigger header builder Customizer Configurations.
 *
 * @since 4.5.2
 * @return array SmartToolz Customizer Configurations with updated configurations.
 */
function smarttoolz_header_mobile_trigger_configuration() {
	$_section = 'section-header-mobile-trigger';

	$_configs = array(

		/*
		* Header Builder section
		*/
		array(
			'name'     => 'section-header-mobile-trigger',
			'type'     => 'section',
			'priority' => 70,
			'title'    => __( 'Toggle Button', 'smarttoolz' ),
			'panel'    => 'panel-header-builder-group',
		),

		/**
		 * Option: Header Builder Tabs
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
		 * Option: Header Html Editor.
		 */
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[header-trigger-icon]',
			'type'              => 'control',
			'control'           => 'ast-radio-image',
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_choices' ),
			'default'           => smarttoolz_get_option( 'header-trigger-icon' ),
			'title'             => __( 'Icons', 'smarttoolz' ),
			'section'           => $_section,
			'choices'           => array(
				'menu'  => array(
					'label' => __( 'Menu', 'smarttoolz' ),
					'path'  => SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'mobile_menu' ),
				),
				'menu2' => array(
					'label' => __( 'Menu 2', 'smarttoolz' ),
					'path'  => SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'mobile_menu2' ),
				),
				'menu3' => array(
					'label' => __( 'Menu 3', 'smarttoolz' ),
					'path'  => SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'mobile_menu3' ),
				),
			),
			'transport'         => 'postMessage',
			'partial'           => array(
				'selector'        => '.ast-button-wrap',
				'render_callback' => array( 'SmartToolz_Builder_UI_Controller', 'render_mobile_trigger' ),
			),
			'priority'          => 10,
			'context'           => SmartToolz_Builder_Helper::$general_tab,
			'divider'           => array( 'ast_class' => 'ast-section-spacing ast-bottom-section-divider ast-inline' ),
			'alt_layout'        => true,
		),

		/**
		 * Option: Toggle Button Style
		 */
		array(
			'name'       => SMARTTOOLZ_THEME_SETTINGS . '[mobile-header-toggle-btn-style]',
			'default'    => smarttoolz_get_option( 'mobile-header-toggle-btn-style' ),
			'section'    => $_section,
			'title'      => __( 'Toggle Button Style', 'smarttoolz' ),
			'type'       => 'control',
			'control'    => 'ast-selector',
			'priority'   => 11,
			'choices'    => array(
				'fill'    => __( 'Fill', 'smarttoolz' ),
				'outline' => __( 'Outline', 'smarttoolz' ),
				'minimal' => __( 'Minimal', 'smarttoolz' ),
			),
			'context'    => SmartToolz_Builder_Helper::$general_tab,
			'transport'  => 'postMessage',
			'partial'    => array(
				'selector'        => '.ast-button-wrap',
				'render_callback' => array( 'SmartToolz_Builder_UI_Controller', 'render_mobile_trigger' ),
			),
			'responsive' => false,
			'divider'    => array( 'ast_class' => 'ast-bottom-section-divider' ),
			'renderAs'   => 'text',
		),

		/**
		 * Option: Mobile Menu Label
		 */
		array(
			'name'      => SMARTTOOLZ_THEME_SETTINGS . '[mobile-header-menu-label]',
			'transport' => 'postMessage',
			'partial'   => array(
				'selector'        => '.ast-button-wrap',
				'render_callback' => array( 'SmartToolz_Builder_UI_Controller', 'render_mobile_trigger' ),
			),
			'default'   => smarttoolz_get_option( 'mobile-header-menu-label' ),
			'section'   => $_section,
			'priority'  => 20,
			'title'     => __( 'Menu Label', 'smarttoolz' ),
			'type'      => 'control',
			'control'   => 'text',
			'context'   => SmartToolz_Builder_Helper::$general_tab,
			'divider'   => array( 'ast_class' => 'ast-bottom-divider ast-top-divider' ),
		),

		/**
		 * Option: Toggle Button Color
		 */
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[mobile-header-toggle-btn-color]',
			'default'           => smarttoolz_get_option( 'mobile-header-toggle-btn-color' ),
			'type'              => 'control',
			'control'           => 'ast-color',
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			'title'             => __( 'Icon Color', 'smarttoolz' ),
			'section'           => $_section,
			'transport'         => 'postMessage',
			'priority'          => 40,
			'context'           => SmartToolz_Builder_Helper::$design_tab,
			'divider'           => array( 'ast_class' => 'ast-section-spacing' ),

		),

		/**
		 * Option: Icon Size
		 */
		array(
			'name'        => SMARTTOOLZ_THEME_SETTINGS . '[mobile-header-toggle-icon-size]',
			'default'     => smarttoolz_get_option( 'mobile-header-toggle-icon-size' ),
			'type'        => 'control',
			'control'     => 'ast-slider',
			'section'     => $_section,
			'title'       => __( 'Icon Size', 'smarttoolz' ),
			'priority'    => 50,
			'suffix'      => 'px',
			'transport'   => 'postMessage',
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1,
				'max'  => 100,
			),
			'context'     => SmartToolz_Builder_Helper::$design_tab,
			'divider'     => array( 'ast_class' => 'ast-top-section-divider' ),
		),

		/**
		 * Option: Toggle Button Bg Color
		 */
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[mobile-header-toggle-btn-bg-color]',
			'default'           => smarttoolz_get_option( 'mobile-header-toggle-btn-bg-color' ),
			'type'              => 'control',
			'control'           => 'ast-color',
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			'title'             => __( 'Background Color', 'smarttoolz' ),
			'section'           => $_section,
			'transport'         => 'postMessage',
			'priority'          => 40,
			'context'           => array(
				SmartToolz_Builder_Helper::$design_tab_config,
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[mobile-header-toggle-btn-style]',
					'operator' => '==',
					'value'    => 'fill',
				),
			),
		),

		/**
		 * Option: Toggle Button Border Size
		 */
		array(
			'name'           => SMARTTOOLZ_THEME_SETTINGS . '[mobile-header-toggle-btn-border-size]',
			'default'        => smarttoolz_get_option( 'mobile-header-toggle-btn-border-size' ),
			'type'           => 'control',
			'section'        => $_section,
			'control'        => 'ast-border',
			'transport'      => 'postMessage',
			'linked_choices' => true,
			'priority'       => 60,
			'title'          => __( 'Border Width', 'smarttoolz' ),
			'choices'        => array(
				'top'    => __( 'Top', 'smarttoolz' ),
				'right'  => __( 'Right', 'smarttoolz' ),
				'bottom' => __( 'Bottom', 'smarttoolz' ),
				'left'   => __( 'Left', 'smarttoolz' ),
			),
			'context'        => array(
				SmartToolz_Builder_Helper::$design_tab_config,
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[mobile-header-toggle-btn-style]',
					'operator' => '==',
					'value'    => 'outline',
				),
			),
			'divider'        => array( 'ast_class' => 'ast-top-section-divider' ),
		),

		/**
		 * Option: Toggle Button Border Color
		 */
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[mobile-header-toggle-border-color]',
			'default'           => smarttoolz_get_option( 'mobile-header-toggle-border-color' ),
			'type'              => 'control',
			'control'           => 'ast-color',
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			'title'             => __( 'Border Color', 'smarttoolz' ),
			'section'           => $_section,
			'transport'         => 'postMessage',
			'priority'          => 40,
			'context'           => array(
				SmartToolz_Builder_Helper::$design_tab_config,
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[mobile-header-toggle-btn-style]',
					'operator' => '==',
					'value'    => 'outline',
				),
			),
		),

		/**
		 * Option: Button Radius Fields
		 */
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[mobile-header-toggle-border-radius-fields]',
			'default'           => smarttoolz_get_option( 'mobile-header-toggle-border-radius-fields' ),
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
			'priority'          => 50,
			'connected'         => false,
			'divider'           => array( 'ast_class' => 'ast-top-section-divider' ),
			'context'           => array(
				SmartToolz_Builder_Helper::$design_tab_config,
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[mobile-header-toggle-btn-style]',
					'operator' => '!=',
					'value'    => 'minimal',
				),
			),
		),

		/**
		 * Option: Divider
		 */
		array(
			'name'     => SMARTTOOLZ_THEME_SETTINGS . '[' . $_section . '-margin-divider]',
			'section'  => $_section,
			'title'    => __( 'Spacing', 'smarttoolz' ),
			'type'     => 'control',
			'control'  => 'ast-heading',
			'priority' => 130,
			'settings' => array(),
			'context'  => SmartToolz_Builder_Helper::$design_tab,
			'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
		),

		/**
		 * Option: Margin Space
		 */
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[' . $_section . '-margin]',
			'default'           => smarttoolz_get_option( $_section . '-margin' ),
			'type'              => 'control',
			'transport'         => 'postMessage',
			'control'           => 'ast-responsive-spacing',
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
			'section'           => $_section,
			'priority'          => 130,
			'title'             => __( 'Margin', 'smarttoolz' ),
			'linked_choices'    => true,
			'unit_choices'      => array( 'px', 'em', '%' ),
			'choices'           => array(
				'top'    => __( 'Top', 'smarttoolz' ),
				'right'  => __( 'Right', 'smarttoolz' ),
				'bottom' => __( 'Bottom', 'smarttoolz' ),
				'left'   => __( 'Left', 'smarttoolz' ),
			),
			'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
			'context'           => SmartToolz_Builder_Helper::$design_tab,
		),
	);

	/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
	if ( defined( 'SMARTTOOLZ_EXT_VER' ) && SmartToolz_Ext_Extension::is_active( 'typography' ) ) {
		/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

		$typo_configs = array(

			// Option Group: Trigger Typography.
			array(
				'name'      => SMARTTOOLZ_THEME_SETTINGS . '[mobile-header-label-typography]',
				'default'   => smarttoolz_get_option( 'mobile-header-label-typography' ),
				'type'      => 'control',
				'control'   => 'ast-settings-group',
				'is_font'   => true,
				'title'     => __( 'Typography', 'smarttoolz' ),
				'section'   => $_section,
				'transport' => 'postMessage',
				'priority'  => 70,
				'context'   => array(
					SmartToolz_Builder_Helper::$design_tab_config,
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[mobile-header-menu-label]',
						'operator' => '!=',
						'value'    => '',
					),
				),
			),

			// Option: Trigger Font Size.
			array(
				'name'        => 'mobile-header-label-font-size',
				'default'     => smarttoolz_get_option( 'mobile-header-label-font-size' ),
				'parent'      => SMARTTOOLZ_THEME_SETTINGS . '[mobile-header-label-typography]',
				'section'     => $_section,
				'type'        => 'sub-control',
				'priority'    => 23,
				'suffix'      => 'px',
				'title'       => __( 'Font Size', 'smarttoolz' ),
				'control'     => 'ast-slider',
				'transport'   => 'postMessage',
				'input_attrs' => array(
					'min' => 0,
					'max' => 200,
				),
				'units'       => array(
					'px'  => 'px',
					'em'  => 'em',
					'vw'  => 'vw',
					'rem' => 'rem',
				),
				'context'     => SmartToolz_Builder_Helper::$design_tab,
			),
		);

	} else {

		$typo_configs = array(

			// Option: Trigger Font Size.
			array(
				'name'        => SMARTTOOLZ_THEME_SETTINGS . '[mobile-header-label-font-size]',
				'default'     => smarttoolz_get_option( 'mobile-header-label-font-size' ),
				'section'     => $_section,
				'type'        => 'control',
				'priority'    => 70,
				'suffix'      => 'px',
				'title'       => __( 'Font Size', 'smarttoolz' ),
				'control'     => 'ast-slider',
				'transport'   => 'postMessage',
				'input_attrs' => array(
					'min' => 0,
					'max' => 200,
				),
				'units'       => array(
					'px'  => 'px',
					'em'  => 'em',
					'vw'  => 'vw',
					'rem' => 'rem',
				),
				'context'     => SmartToolz_Builder_Helper::$design_tab,
			),
		);
	}

	$_configs = array_merge( $_configs, $typo_configs );

	if ( SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
		array_map( 'smarttoolz_save_header_customizer_configs', $_configs );
	}

	return $_configs;
}

if ( SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
	add_action( 'init', 'smarttoolz_header_mobile_trigger_configuration' );
}
