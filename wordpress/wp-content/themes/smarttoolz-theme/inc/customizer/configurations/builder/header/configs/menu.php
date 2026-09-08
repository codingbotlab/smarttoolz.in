<?php
/**
 * Menu Header Configuration.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register menu header builder Customizer Configurations.
 *
 * @since 4.5.2
 * @return array SmartToolz Customizer Configurations with updated configurations.
 */
function smarttoolz_header_menu_configuration() {
	$menu_configs = array();

	$component_limit = defined( 'SMARTTOOLZ_EXT_VER' ) ? SmartToolz_Builder_Helper::$component_limit : SmartToolz_Builder_Helper::$num_of_header_menu;

	for ( $index = 1; $index <= $component_limit; $index++ ) {

		$_section = 'section-hb-menu-' . $index;
		$_prefix  = 'menu' . $index;

		switch ( $index ) {
			case 1:
				$edit_menu_title = __( 'Primary Menu', 'smarttoolz' );
				break;
			case 2:
				$edit_menu_title = __( 'Secondary Menu', 'smarttoolz' );
				break;
			default:
				$edit_menu_title = __( 'Menu ', 'smarttoolz' ) . $index;
				break;
		}

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
				'name'        => $_section,
				'type'        => 'section',
				'title'       => $edit_menu_title,
				'panel'       => 'panel-header-builder-group',
				'priority'    => 40,
				'clone_index' => $index,
				'clone_type'  => 'header-menu',
			),

			/**
			 * Option: Theme Menu create link.
			 */
			array(
				'name'      => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-create-menu-link]',
				'default'   => smarttoolz_get_option( 'header-' . $_prefix . '-create-menu-link' ),
				'type'      => 'control',
				'control'   => 'ast-customizer-link',
				'section'   => $_section,
				'priority'  => 30,
				'link_type' => 'section',
				'linked'    => 'menu_locations',
				'link_text' => __( 'Configure Menu from Here.', 'smarttoolz' ),
				'context'   => SmartToolz_Builder_Helper::$general_tab,
			),

			/**
			 * Option: Menu hover style
			 */
			array(
				'name'       => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-menu-hover-animation]',
				'default'    => smarttoolz_get_option( 'header-' . $_prefix . '-menu-hover-animation' ),
				'type'       => 'control',
				'control'    => 'ast-select',
				'section'    => $_section,
				'priority'   => 10,
				'title'      => __( 'Menu Hover Style', 'smarttoolz' ),
				'choices'    => array(
					''          => __( 'None', 'smarttoolz' ),
					'zoom'      => __( 'Zoom In', 'smarttoolz' ),
					'underline' => __( 'Underline', 'smarttoolz' ),
					'overline'  => __( 'Overline', 'smarttoolz' ),
				),
				'context'    => SmartToolz_Builder_Helper::$design_tab,
				'transport'  => 'postMessage',
				'responsive' => false,
				'renderAs'   => 'text',
				'divider'    => array( 'ast_class' => 'ast-section-spacing' ),
			),

			/**
			 * Option: Submenu heading.
			 */
			array(
				'name'     => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-heading]',
				'type'     => 'control',
				'control'  => 'ast-heading',
				'section'  => $_section,
				'title'    => __( 'Submenu', 'smarttoolz' ),
				'settings' => array(),
				'priority' => 30,
				'context'  => SmartToolz_Builder_Helper::$general_tab,
				'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
			),

			/**
			 * Option: Submenu width
			 */
			array(
				'name'        => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-width]',
				'default'     => smarttoolz_get_option( 'header-' . $_prefix . '-submenu-width' ),
				'type'        => 'control',
				'context'     => SmartToolz_Builder_Helper::$general_tab,
				'section'     => $_section,
				'control'     => 'ast-slider',
				'priority'    => 30.5,
				'title'       => __( 'Width', 'smarttoolz' ),
				'suffix'      => 'px',
				'input_attrs' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 1920,
				),
				'transport'   => 'postMessage',
				'divider'     => array( 'ast_class' => 'ast-section-spacing' ),
			),

			/**
			 * Option: Submenu Animation
			 */
			array(
				'name'       => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-container-animation]',
				'default'    => smarttoolz_get_option( 'header-' . $_prefix . '-submenu-container-animation' ),
				'type'       => 'control',
				'control'    => 'ast-select',
				'section'    => $_section,
				'priority'   => 23,
				'title'      => __( 'Submenu Animation', 'smarttoolz' ),
				'choices'    => array(
					''           => __( 'None', 'smarttoolz' ),
					'slide-down' => __( 'Slide Down', 'smarttoolz' ),
					'slide-up'   => __( 'Slide Up', 'smarttoolz' ),
					'fade'       => __( 'Fade', 'smarttoolz' ),
				),
				'context'    => SmartToolz_Builder_Helper::$design_tab,
				'transport'  => 'postMessage',
				'responsive' => false,
				'renderAs'   => 'text',
				'divider'    => array( 'ast_class' => 'ast-bottom-divider ast-top-section-divider' ),
			),

			// Option: Submenu Container Divider.
			array(
				'name'     => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-container-divider]',
				'section'  => $_section,
				'type'     => 'control',
				'control'  => 'ast-heading',
				'title'    => __( 'Submenu Container', 'smarttoolz' ),
				'priority' => 20,
				'settings' => array(),
				'context'  => SmartToolz_Builder_Helper::$design_tab,
				'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
			),

			// Option: Submenu Divider Size.
			array(
				'name'        => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-item-b-size]',
				'type'        => 'control',
				'control'     => 'ast-slider',
				'default'     => smarttoolz_get_option( 'header-' . $_prefix . '-submenu-item-b-size' ),
				'section'     => $_section,
				'priority'    => 20.5,
				'transport'   => 'postMessage',
				'title'       => __( 'Divider Size', 'smarttoolz' ),
				'context'     => array(
					SmartToolz_Builder_Helper::$design_tab_config,
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-item-border]',
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
				'divider'     => array( 'ast_class' => 'ast-section-spacing' ),
			),

			// Option: Submenu item Border Color.
			array(
				'name'              => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-item-b-color]',
				'default'           => smarttoolz_get_option( 'header-' . $_prefix . '-submenu-item-b-color' ),
				'type'              => 'control',
				'control'           => 'ast-color',
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
				'transport'         => 'postMessage',
				'title'             => __( 'Divider Color', 'smarttoolz' ),
				'section'           => $_section,
				'priority'          => 21,
				'context'           => array(
					SmartToolz_Builder_Helper::$design_tab_config,
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-item-border]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'divider'           => array( 'ast_class' => 'ast-bottom-divider' ),
			),

			/**
			 * Option: Submenu Top Offset
			 */
			array(
				'name'        => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-top-offset]',
				'default'     => smarttoolz_get_option( 'header-' . $_prefix . '-submenu-top-offset' ),
				'type'        => 'control',
				'context'     => SmartToolz_Builder_Helper::$design_tab,
				'section'     => $_section,
				'control'     => 'ast-slider',
				'priority'    => 22,
				'title'       => __( 'Top Offset', 'smarttoolz' ),
				'suffix'      => 'px',
				'transport'   => 'postMessage',
				'input_attrs' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 200,
				),
			),

			// Option: Sub-Menu Border.
			array(
				'name'           => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-border]',
				'default'        => smarttoolz_get_option( 'header-' . $_prefix . '-submenu-border' ),
				'type'           => 'control',
				'control'        => 'ast-border',
				'transport'      => 'postMessage',
				'section'        => $_section,
				'linked_choices' => true,
				'context'        => SmartToolz_Builder_Helper::$design_tab,
				'priority'       => 23,
				'title'          => __( 'Border Width', 'smarttoolz' ),
				'choices'        => array(
					'top'    => __( 'Top', 'smarttoolz' ),
					'right'  => __( 'Right', 'smarttoolz' ),
					'bottom' => __( 'Bottom', 'smarttoolz' ),
					'left'   => __( 'Left', 'smarttoolz' ),
				),
				'divider'        => array( 'ast_class' => 'ast-bottom-divider' ),
			),

			// Option: Submenu Container Border Color.
			array(
				'name'              => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-b-color]',
				'default'           => smarttoolz_get_option( 'header-' . $_prefix . '-submenu-b-color' ),
				'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-border-group]',
				'type'              => 'control',
				'control'           => 'ast-color',
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
				'transport'         => 'postMessage',
				'title'             => __( 'Border Color', 'smarttoolz' ),
				'section'           => $_section,
				'priority'          => 23,
				'context'           => SmartToolz_Builder_Helper::$design_tab,
				'divider'           => array( 'ast_class' => 'ast-bottom-divider' ),
			),

			/**
			 * Option: Button Radius Fields
			 */
			array(
				'name'              => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-border-radius-fields]',
				'default'           => smarttoolz_get_option( 'header-' . $_prefix . '-submenu-border-radius-fields' ),
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
				'priority'          => 23,
				'connected'         => false,
				'divider'           => array( 'ast_class' => 'ast-bottom-section-divider' ),
				'context'           => SmartToolz_Builder_Helper::$design_tab,
			),

			// Option: Submenu Divider Checkbox.
			array(
				'name'      => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-submenu-item-border]',
				'default'   => smarttoolz_get_option( 'header-' . $_prefix . '-submenu-item-border' ),
				'type'      => 'control',
				'control'   => 'ast-toggle-control',
				'section'   => $_section,
				'priority'  => 35,
				'title'     => __( 'Item Divider', 'smarttoolz' ),
				'context'   => SmartToolz_Builder_Helper::$general_tab,
				'transport' => 'postMessage',
				'divider'   => array( 'ast_class' => 'ast-top-section-divider' ),

			),

			// Option: Menu Stack on Mobile Checkbox.
			array(
				'name'      => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-menu-stack-on-mobile]',
				'default'   => smarttoolz_get_option( 'header-' . $_prefix . '-menu-stack-on-mobile' ),
				'type'      => 'control',
				'control'   => 'ast-toggle-control',
				'section'   => $_section,
				'priority'  => 41,
				'divider'   => array( 'ast_class' => 'ast-top-section-divider' ),
				'title'     => __( 'Stack on Responsive', 'smarttoolz' ),
				'context'   => SmartToolz_Builder_Helper::$responsive_general_tab,
				'transport' => 'postMessage',
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
				'priority'          => 151,
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
				'divider'           => array( 'ast_class' => 'ast-top-divider' ),
			),

			// Option Group: Menu Color.
			array(
				'name'       => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-text-colors]',
				'type'       => 'control',
				'control'    => 'ast-color-group',
				'title'      => __( 'Text / Link', 'smarttoolz' ),
				'section'    => $_section,
				'transport'  => 'postMessage',
				'priority'   => 90,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
				'responsive' => true,
				'divider'    => array(
					'ast_title' => __( 'Menu Color', 'smarttoolz' ),
				),
			),
			array(
				'name'       => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-background-colors]',
				'type'       => 'control',
				'control'    => 'ast-color-group',
				'title'      => __( 'Background', 'smarttoolz' ),
				'section'    => $_section,
				'transport'  => 'postMessage',
				'priority'   => 90,
				'context'    => SmartToolz_Builder_Helper::$design_tab,
				'responsive' => true,
			),

			// Option: Menu Color.
			array(
				'name'       => 'header-' . $_prefix . '-color-responsive',
				'default'    => smarttoolz_get_option( 'header-' . $_prefix . '-color-responsive' ),
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-text-colors]',
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
				'name'       => 'header-' . $_prefix . '-bg-obj-responsive',
				'default'    => smarttoolz_get_option( 'header-' . $_prefix . '-bg-obj-responsive' ),
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-background-colors]',
				'type'       => 'sub-control',
				'control'    => 'ast-responsive-background',
				'section'    => $_section,
				'transport'  => 'postMessage',
				'tab'        => __( 'Normal', 'smarttoolz' ),
				'data_attrs' => array( 'name' => 'header-' . $_prefix . '-bg-obj-responsive' ),
				'title'      => __( 'Normal', 'smarttoolz' ),
				'priority'   => 9,
				'context'    => SmartToolz_Builder_Helper::$general_tab,
			),

			// Option: Menu Hover Color.
			array(
				'name'       => 'header-' . $_prefix . '-h-color-responsive',
				'default'    => smarttoolz_get_option( 'header-' . $_prefix . '-h-color-responsive' ),
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-text-colors]',
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
				'name'       => 'header-' . $_prefix . '-h-bg-color-responsive',
				'default'    => smarttoolz_get_option( 'header-' . $_prefix . '-h-bg-color-responsive' ),
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-background-colors]',
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
				'name'       => 'header-' . $_prefix . '-a-color-responsive',
				'default'    => smarttoolz_get_option( 'header-' . $_prefix . '-a-color-responsive' ),
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-text-colors]',
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
				'name'       => 'header-' . $_prefix . '-a-bg-color-responsive',
				'default'    => smarttoolz_get_option( 'header-' . $_prefix . '-a-bg-color-responsive' ),
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-background-colors]',
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

			// Font Divider.
			array(
				'name'     => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $index . '-font-divider]',
				'type'     => 'control',
				'control'  => 'ast-heading',
				'section'  => $_section,
				'title'    => __( 'Font', 'smarttoolz' ),
				'settings' => array(),
				'priority' => 120,
				'context'  => SmartToolz_Builder_Helper::$design_tab,
				'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
			),

			// Option Group: Menu Typography.
			array(
				'name'      => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-header-menu-typography]',
				'default'   => smarttoolz_get_option( 'header-' . $_prefix . '-header-menu-typography' ),
				'type'      => 'control',
				'control'   => 'ast-settings-group',
				'title'     => __( 'Menu Font', 'smarttoolz' ),
				'is_font'   => true,
				'section'   => $_section,
				'transport' => 'postMessage',
				'priority'  => 120,
				'context'   => SmartToolz_Builder_Helper::$design_tab,
			),

			// Option: Menu Font Family.
			array(
				'name'      => 'header-' . $_prefix . '-font-family',
				'default'   => smarttoolz_get_option( 'header-' . $_prefix . '-font-family' ),
				'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-header-menu-typography]',
				'type'      => 'sub-control',
				'section'   => $_section,
				'transport' => 'postMessage',
				'control'   => 'ast-font',
				'font_type' => 'ast-font-family',
				'title'     => __( 'Font Family', 'smarttoolz' ),
				'priority'  => 22,
				'connect'   => 'header-' . $_prefix . '-font-weight',
				'context'   => SmartToolz_Builder_Helper::$general_tab,
				'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			// Option: Menu Font Weight.
			array(
				'name'              => 'header-' . $_prefix . '-font-weight',
				'default'           => smarttoolz_get_option( 'header-' . $_prefix . '-font-weight' ),
				'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-header-menu-typography]',
				'section'           => $_section,
				'type'              => 'sub-control',
				'control'           => 'ast-font',
				'transport'         => 'postMessage',
				'font_type'         => 'ast-font-weight',
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_font_weight' ),
				'title'             => __( 'Font Weight', 'smarttoolz' ),
				'priority'          => 23,
				'connect'           => 'header-' . $_prefix . '-font-family',
				'context'           => SmartToolz_Builder_Helper::$general_tab,
				'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			// Option: Menu Font Size.
			array(
				'name'              => 'header-' . $_prefix . '-font-size',
				'default'           => smarttoolz_get_option( 'header-' . $_prefix . '-font-size' ),
				'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-header-menu-typography]',
				'section'           => $_section,
				'type'              => 'sub-control',
				'priority'          => 23,
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
			 * Option: Primary Menu Font Extras
			 */
			array(
				'name'     => 'header-' . $_prefix . '-font-extras',
				'parent'   => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-header-menu-typography]',
				'section'  => $_section,
				'type'     => 'sub-control',
				'control'  => 'ast-font-extras',
				'priority' => 26,
				'default'  => smarttoolz_get_option( 'header-' . $_prefix . '-font-extras' ),
				'title'    => __( 'Font Extras', 'smarttoolz' ),
			),

			/**
			 * Option: Spacing Divider
			 */
			array(
				'name'     => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $index . '-spacing-divider]',
				'type'     => 'control',
				'control'  => 'ast-heading',
				'section'  => $_section,
				'title'    => __( 'Spacing', 'smarttoolz' ),
				'settings' => array(),
				'priority' => 150,
				'context'  => SmartToolz_Builder_Helper::$design_tab,
				'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
			),

			// Option - Menu Space.
			array(
				'name'              => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-menu-spacing]',
				'default'           => smarttoolz_get_option( 'header-' . $_prefix . '-menu-spacing' ),
				'type'              => 'control',
				'control'           => 'ast-responsive-spacing',
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
				'transport'         => 'postMessage',
				'section'           => $_section,
				'priority'          => 150,
				'title'             => __( 'Menu', 'smarttoolz' ),
				'linked_choices'    => true,
				'unit_choices'      => array( 'px', 'em', '%' ),
				'choices'           => array(
					'top'    => __( 'Top', 'smarttoolz' ),
					'right'  => __( 'Right', 'smarttoolz' ),
					'bottom' => __( 'Bottom', 'smarttoolz' ),
					'left'   => __( 'Left', 'smarttoolz' ),
				),
				'context'           => SmartToolz_Builder_Helper::$design_tab,
				'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
			),
		);

		// Mega menu / custom dropdown upsell nudge (free only).
		if ( smarttoolz_showcase_upgrade_notices() ) {
			$_configs[] = array(
				'name'        => SMARTTOOLZ_THEME_SETTINGS . '[header-' . $_prefix . '-mega-menu-upsell]',
				'type'        => 'control',
				'control'     => 'ast-upgrade',
				'campaign'    => 'menus',
				'section'     => $_section,
				'default'     => '',
				'priority'    => 999,
				'title'       => __( 'Turn Menus Into Rich Dropdowns', 'smarttoolz' ),
				'description' => sprintf(
					/* translators: %s: Learn more documentation link. */
					__( 'Turn any menu item into a large, multi-column dropdown (a mega menu) — fully designed, any width. %s', 'smarttoolz' ),
					'<a href="' . esc_url( smarttoolz_get_pro_url( '/mega-menu/', 'free-theme', 'customizer-menu', 'mega-menu-learn' ) ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Learn more', 'smarttoolz' ) . '</a>'
				),
				'choices'     => array(
					'one'   => array( 'title' => __( 'Full-width or custom-width dropdowns', 'smarttoolz' ) ),
					'two'   => array( 'title' => __( 'Add templates, widgets, or custom content', 'smarttoolz' ) ),
					'three' => array( 'title' => __( 'Multi-column layouts with per-item styling', 'smarttoolz' ) ),
				),
				'context'     => SmartToolz_Builder_Helper::$general_tab,
				'divider'     => array( 'ast_class' => 'ast-top-section-divider' ),
			);
		}

		$menu_configs[] = SmartToolz_Builder_Base_Configuration::prepare_visibility_tab( $_section );
		$menu_configs[] = $_configs;
	}

	$menu_configs = call_user_func_array( 'array_merge', $menu_configs + array( array() ) );

	if ( SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
		array_map( 'smarttoolz_save_header_customizer_configs', $menu_configs );
	}

	return $menu_configs;
}

if ( SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
	add_action( 'init', 'smarttoolz_header_menu_configuration' );
}
