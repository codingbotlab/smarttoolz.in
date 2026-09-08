<?php
/**
 * Mobile Menu Header Configuration.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register mobile-menu header builder Customizer Configurations.
 *
 * @since 4.5.2
 * @return array SmartToolz Customizer Configurations with updated configurations.
 */
function smarttoolz_header_mobile_menu_configuration() {
	$_section = 'section-header-mobile-menu';

	$_configs = array(

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

		// Section: Primary Header.
		array(
			'name'     => $_section,
			'type'     => 'section',
			'title'    => __( 'Off-Canvas Menu', 'smarttoolz' ),
			'panel'    => 'panel-header-builder-group',
			'priority' => 40,
		),

		/**
		 * Option: Theme Menu create link
		 */
		array(
			'name'      => SMARTTOOLZ_THEME_SETTINGS . '[header-mobile-menu-create-menu-link]',
			'default'   => smarttoolz_get_option( 'header-mobile-menu-create-menu-link' ),
			'type'      => 'control',
			'control'   => 'ast-customizer-link',
			'section'   => $_section,
			'priority'  => 30,
			'link_type' => 'section',
			'linked'    => 'menu_locations',
			'link_text' => __( 'Configure Menu from Here.', 'smarttoolz' ),
			'context'   => SmartToolz_Builder_Helper::$general_tab,
			'divider'   => array( 'ast_class' => 'ast-section-spacing ast-bottom-section-divider' ),
		),

		// Option: Submenu Divider Checkbox.
		array(
			'name'      => SMARTTOOLZ_THEME_SETTINGS . '[header-mobile-menu-submenu-item-border]',
			'default'   => smarttoolz_get_option( 'header-mobile-menu-submenu-item-border' ),
			'type'      => 'control',
			'control'   => 'ast-toggle-control',
			'section'   => $_section,
			'priority'  => 150,
			'title'     => __( 'Item Divider', 'smarttoolz' ),
			'context'   => SmartToolz_Builder_Helper::$general_tab,
			'transport' => 'postMessage',
			'divider'   => array( 'ast_class' => 'ast-top-section-divider' ),
		),

		// Option: Menu Color Divider.
		array(
			'name'     => SMARTTOOLZ_THEME_SETTINGS . '[header-mobile-menu-divider-colors-divider]',
			'section'  => $_section,
			'type'     => 'control',
			'control'  => 'ast-heading',
			'title'    => __( 'Item Divider', 'smarttoolz' ),
			'priority' => 150,
			'settings' => array(),
			'context'  => array(
				SmartToolz_Builder_Helper::$design_tab_config,
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-mobile-menu-submenu-item-border]',
					'operator' => '==',
					'value'    => true,
				),
			),
			'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),

		),

		// Option: Submenu item Border Size.
		array(
			'name'        => SMARTTOOLZ_THEME_SETTINGS . '[header-mobile-menu-submenu-item-b-size]',
			'type'        => 'control',
			'control'     => 'ast-slider',
			'default'     => smarttoolz_get_option( 'header-mobile-menu-submenu-item-b-size' ),
			'section'     => $_section,
			'priority'    => 150,
			'transport'   => 'postMessage',
			'title'       => __( 'Divider Size', 'smarttoolz' ),
			'context'     => array(
				SmartToolz_Builder_Helper::$design_tab_config,
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-mobile-menu-submenu-item-border]',
					'operator' => '==',
					'value'    => true,
				),
			),
			'suffix'      => 'px',
			'input_attrs' => array(
				'min'  => 1,
				'step' => 1,
				'max'  => 10,
			),
			'divider'     => array( 'ast_class' => 'ast-bottom-divider ast-section-spacing' ),
		),

		// Option: Submenu item Border Color.
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[header-mobile-menu-submenu-item-b-color]',
			'default'           => smarttoolz_get_option( 'header-mobile-menu-submenu-item-b-color' ),
			'type'              => 'control',
			'control'           => 'ast-color',
			'transport'         => 'postMessage',
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			'title'             => __( 'Divider Color', 'smarttoolz' ),
			'section'           => $_section,
			'priority'          => 150,
			'context'           => array(
				SmartToolz_Builder_Helper::$design_tab_config,
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-mobile-menu-submenu-item-border]',
					'operator' => '==',
					'value'    => true,
				),
			),
		),

		// Option Group: Menu Color.
		array(
			'name'       => SMARTTOOLZ_THEME_SETTINGS . '[header-mobile-menu-link-colors]',
			'type'       => 'control',
			'control'    => 'ast-color-group',
			'title'      => __( 'Link', 'smarttoolz' ),
			'section'    => $_section,
			'transport'  => 'postMessage',
			'priority'   => 90,
			'context'    => SmartToolz_Builder_Helper::$design_tab,
			'responsive' => true,
			'divider'    => array(
				'ast_title' => __( 'Menu Color', 'smarttoolz' ),
				'ast_class' => 'ast-section-spacing',
			),
		),
		array(
			'name'       => SMARTTOOLZ_THEME_SETTINGS . '[header-mobile-menu-background-colors]',
			'type'       => 'control',
			'control'    => 'ast-color-group',
			'title'      => __( 'Background', 'smarttoolz' ),
			'section'    => $_section,
			'transport'  => 'postMessage',
			'priority'   => 90,
			'context'    => SmartToolz_Builder_Helper::$design_tab,
			'responsive' => true,
			'divider'    => array(
				'ast_title' => '',
				'ast_class' => class_exists( 'SmartToolz_Ext_Extension' ) && SmartToolz_Ext_Extension::is_active( 'colors-and-background' ) ? 'ast-bottom-divider' : '',
			),
		),
		// Option: Menu Color.
		array(
			'name'       => 'header-mobile-menu-color-responsive',
			'default'    => smarttoolz_get_option( 'header-mobile-menu-color-responsive' ),
			'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-mobile-menu-link-colors]',
			'type'       => 'sub-control',
			'control'    => 'ast-responsive-color',
			'transport'  => 'postMessage',
			'tab'        => __( 'Normal', 'smarttoolz' ),
			'section'    => $_section,
			'title'      => __( 'Normal', 'smarttoolz' ),
			'responsive' => true,
			'rgba'       => true,
			'priority'   => 7,
			'context'    => SmartToolz_Builder_Helper::$general_tab,
		),

		// Option: Menu Background image, color.
		array(
			'name'       => 'header-mobile-menu-bg-obj-responsive',
			'default'    => smarttoolz_get_option( 'header-mobile-menu-bg-obj-responsive' ),
			'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-mobile-menu-background-colors]',
			'type'       => 'sub-control',
			'control'    => 'ast-responsive-background',
			'section'    => $_section,
			'transport'  => 'postMessage',
			'tab'        => __( 'Normal', 'smarttoolz' ),
			'data_attrs' => array( 'name' => 'header-mobile-menu-bg-obj-responsive' ),
			'title'      => __( 'Normal', 'smarttoolz' ),
			'priority'   => 9,
			'context'    => SmartToolz_Builder_Helper::$general_tab,
		),

		// Option: Menu Hover Color.
		array(
			'name'       => 'header-mobile-menu-h-color-responsive',
			'default'    => smarttoolz_get_option( 'header-mobile-menu-h-color-responsive' ),
			'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-mobile-menu-link-colors]',
			'tab'        => __( 'Hover', 'smarttoolz' ),
			'type'       => 'sub-control',
			'control'    => 'ast-responsive-color',
			'transport'  => 'postMessage',
			'title'      => __( 'Hover', 'smarttoolz' ),
			'section'    => $_section,
			'responsive' => true,
			'rgba'       => true,
			'priority'   => 19,
			'context'    => SmartToolz_Builder_Helper::$general_tab,
		),

		// Option: Menu Hover Background Color.
		array(
			'name'       => 'header-mobile-menu-h-bg-color-responsive',
			'default'    => smarttoolz_get_option( 'header-mobile-menu-h-bg-color-responsive' ),
			'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-mobile-menu-background-colors]',
			'type'       => 'sub-control',
			'title'      => __( 'Hover', 'smarttoolz' ),
			'section'    => $_section,
			'control'    => 'ast-responsive-color',
			'transport'  => 'postMessage',
			'tab'        => __( 'Hover', 'smarttoolz' ),
			'responsive' => true,
			'rgba'       => true,
			'priority'   => 21,
			'context'    => SmartToolz_Builder_Helper::$general_tab,
		),

		// Option: Active Menu Color.
		array(
			'name'       => 'header-mobile-menu-a-color-responsive',
			'default'    => smarttoolz_get_option( 'header-mobile-menu-a-color-responsive' ),
			'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-mobile-menu-link-colors]',
			'type'       => 'sub-control',
			'section'    => $_section,
			'control'    => 'ast-responsive-color',
			'transport'  => 'postMessage',
			'tab'        => __( 'Active', 'smarttoolz' ),
			'title'      => __( 'Active', 'smarttoolz' ),
			'responsive' => true,
			'rgba'       => true,
			'priority'   => 31,
			'context'    => SmartToolz_Builder_Helper::$general_tab,
		),

		// Option: Active Menu Background Color.
		array(
			'name'       => 'header-mobile-menu-a-bg-color-responsive',
			'default'    => smarttoolz_get_option( 'header-mobile-menu-a-bg-color-responsive' ),
			'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-mobile-menu-background-colors]',
			'type'       => 'sub-control',
			'control'    => 'ast-responsive-color',
			'transport'  => 'postMessage',
			'section'    => $_section,
			'title'      => __( 'Active', 'smarttoolz' ),
			'tab'        => __( 'Active', 'smarttoolz' ),
			'responsive' => true,
			'rgba'       => true,
			'priority'   => 33,
			'context'    => SmartToolz_Builder_Helper::$general_tab,
		),

		/**
		 * Option: WOO Off Canvas Menu Submenu Color Section divider
		 */
		array(
			'name'     => SMARTTOOLZ_THEME_SETTINGS . '[header-mobile-menu-header-typo-divider]',
			'type'     => 'control',
			'control'  => 'ast-heading',
			'section'  => $_section,
			'title'    => __( 'Font', 'smarttoolz' ),
			'priority' => 120,
			'settings' => array(),
			'context'  => SmartToolz_Builder_Helper::$design_tab,
			'divider'  => array(
				'ast_class' => 'ast-section-spacing ast-top-section-divider',
			),
		),

		// Option Group: Menu Typography.
		array(
			'name'      => SMARTTOOLZ_THEME_SETTINGS . '[header-mobile-menu-header-menu-typography]',
			'default'   => smarttoolz_get_option( 'header-mobile-menu-header-menu-typography' ),
			'type'      => 'control',
			'control'   => 'ast-settings-group',
			'title'     => __( 'Menu Font', 'smarttoolz' ),
			'is_font'   => true,
			'section'   => $_section,
			'transport' => 'postMessage',
			'priority'  => 120,
			'context'   => SmartToolz_Builder_Helper::$design_tab,
			'divider'   => array(
				'ast_class' => 'ast-section-spacing',
			),
		),

		// Option: Menu Font Family.
		array(
			'name'      => 'header-mobile-menu-font-family',
			'default'   => smarttoolz_get_option( 'header-mobile-menu-font-family', 'inherit' ),
			'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[header-mobile-menu-header-menu-typography]',
			'type'      => 'sub-control',
			'section'   => $_section,
			'transport' => 'postMessage',
			'control'   => 'ast-font',
			'font_type' => 'ast-font-family',
			'title'     => __( 'Font Family', 'smarttoolz' ),
			'priority'  => 22,
			'connect'   => 'header-mobile-menu-font-weight',
			'context'   => SmartToolz_Builder_Helper::$general_tab,
			'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
		),

		// Option: Menu Font Weight.
		array(
			'name'              => 'header-mobile-menu-font-weight',
			'default'           => smarttoolz_get_option( 'header-mobile-menu-font-weight', 'inherit' ),
			'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[header-mobile-menu-header-menu-typography]',
			'section'           => $_section,
			'type'              => 'sub-control',
			'control'           => 'ast-font',
			'transport'         => 'postMessage',
			'font_type'         => 'ast-font-weight',
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_font_weight' ),
			'title'             => __( 'Font Weight', 'smarttoolz' ),
			'priority'          => 23,
			'connect'           => 'header-mobile-menu-font-family',
			'context'           => SmartToolz_Builder_Helper::$general_tab,
			'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),
		),

		// Option: Menu Font Size.
		array(
			'name'              => 'header-mobile-menu-font-size',
			'default'           => smarttoolz_get_option( 'header-mobile-menu-font-size' ),
			'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[header-mobile-menu-header-menu-typography]',
			'section'           => $_section,
			'type'              => 'sub-control',
			'priority'          => 24,
			'title'             => __( 'Font Size', 'smarttoolz' ),
			'control'           => 'ast-responsive-slider',
			'transport'         => 'postMessage',
			'context'           => SmartToolz_Builder_Helper::$general_tab,
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
			'suffix'            => array( 'px', 'em', 'vw', 'rem' ),
			'input_attrs'       => array(
				'px'  => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 200,
				),
				'em'  => array(
					'min'  => 0,
					'step' => 0.01,
					'max'  => 20,
				),
				'vw'  => array(
					'min'  => 0,
					'step' => 0.1,
					'max'  => 25,
				),
				'rem' => array(
					'min'  => 0,
					'step' => 0.1,
					'max'  => 20,
				),
			),
		),

		/**
		 * Option: Font Extras
		 */
		array(
			'name'     => 'font-extras-header-mobile-menu',
			'parent'   => SMARTTOOLZ_THEME_SETTINGS . '[header-mobile-menu-header-menu-typography]',
			'section'  => $_section,
			'type'     => 'sub-control',
			'control'  => 'ast-font-extras',
			'priority' => 24,
			'default'  => smarttoolz_get_option( 'font-extras-header-mobile-menu' ),
			'title'    => __( 'Font Extras', 'smarttoolz' ),
		),

		/**
		 * Option: Divider
		 */
		array(
			'name'     => SMARTTOOLZ_THEME_SETTINGS . '[header-mobile-menu-menu-spacing-divider]',
			'section'  => $_section,
			'title'    => __( 'Spacing', 'smarttoolz' ),
			'type'     => 'control',
			'control'  => 'ast-heading',
			'priority' => 150,
			'settings' => array(),
			'context'  => SmartToolz_Builder_Helper::$design_tab,
			'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
		),

		// Option - Menu Space.
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[header-mobile-menu-menu-spacing]',
			'default'           => smarttoolz_get_option( 'header-mobile-menu-menu-spacing' ),
			'type'              => 'control',
			'control'           => 'ast-responsive-spacing',
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
			'transport'         => 'postMessage',
			'section'           => $_section,
			'priority'          => 150,
			'title'             => __( 'Menu Spacing', 'smarttoolz' ),
			'linked_choices'    => true,
			'unit_choices'      => array( 'px', 'em', '%' ),
			'choices'           => array(
				'top'    => __( 'Top', 'smarttoolz' ),
				'right'  => __( 'Right', 'smarttoolz' ),
				'bottom' => __( 'Bottom', 'smarttoolz' ),
				'left'   => __( 'Left', 'smarttoolz' ),
			),
			'context'           => SmartToolz_Builder_Helper::$design_tab,
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
			'priority'          => 220,
			'title'             => __( 'Margin', 'smarttoolz' ),
			'linked_choices'    => true,
			'unit_choices'      => array( 'px', 'em', '%' ),
			'choices'           => array(
				'top'    => __( 'Top', 'smarttoolz' ),
				'right'  => __( 'Right', 'smarttoolz' ),
				'bottom' => __( 'Bottom', 'smarttoolz' ),
				'left'   => __( 'Left', 'smarttoolz' ),
			),
			'context'           => SmartToolz_Builder_Helper::$design_tab,

		),
	);

	$_configs = array_merge( $_configs, SmartToolz_Builder_Base_Configuration::prepare_visibility_tab( $_section ) );

	if ( SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
		array_map( 'smarttoolz_save_header_customizer_configs', $_configs );
	}

	return $_configs;
}

if ( SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
	add_action( 'init', 'smarttoolz_header_mobile_menu_configuration' );
}
