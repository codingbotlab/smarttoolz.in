<?php
/**
 * Menu footer Configuration.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register menu footer builder Customizer Configurations.
 *
 * @since 4.5.2
 * @return array SmartToolz Customizer Configurations with updated configurations.
 */
function smarttoolz_menu_footer_configuration() {
	$_section = 'section-footer-menu';

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
			'title'    => __( 'Footer Menu', 'smarttoolz' ),
			'panel'    => 'panel-footer-builder-group',
			'priority' => 50,
		),

		/**
		 * Option: Theme Menu create link
		 */
		array(
			'name'      => SMARTTOOLZ_THEME_SETTINGS . '[footer-create-menu-link]',
			'default'   => smarttoolz_get_option( 'footer-create-menu-link' ),
			'type'      => 'control',
			'control'   => 'ast-customizer-link',
			'section'   => $_section,
			'priority'  => 10,
			'link_type' => 'section',
			'linked'    => 'menu_locations',
			'link_text' => __( 'Configure Menu from Here.', 'smarttoolz' ),
			'context'   => SmartToolz_Builder_Helper::$general_tab,

		),

		// Option: Footer Menu Layout.
		array(
			'name'       => SMARTTOOLZ_THEME_SETTINGS . '[footer-menu-layout]',
			'default'    => smarttoolz_get_option( 'footer-menu-layout' ),
			'section'    => $_section,
			'priority'   => 20,
			'title'      => __( 'Layout', 'smarttoolz' ),
			'type'       => 'control',
			'control'    => 'ast-selector',
			'transport'  => 'postMessage',
			'partial'    => array(
				'selector'            => '.footer-widget-area[data-section="section-footer-menu"] nav',
				'container_inclusive' => true,
				'render_callback'     => array( SmartToolz_Builder_Footer::get_instance(), 'footer_menu' ),
			),
			'choices'    => array(
				'horizontal' => __( 'Inline', 'smarttoolz' ),
				'vertical'   => __( 'Stack', 'smarttoolz' ),
			),
			'context'    => SmartToolz_Builder_Helper::$general_tab,
			'responsive' => true,
			'renderAs'   => 'text',
			'divider'    => array( 'ast_class' => 'ast-top-section-divider ast-bottom-section-divider' ),
		),

		/**
		 * Option: Alignment
		 */
		array(
			'name'      => SMARTTOOLZ_THEME_SETTINGS . '[footer-menu-alignment]',
			'default'   => smarttoolz_get_option( 'footer-menu-alignment' ),
			'type'      => 'control',
			'control'   => 'ast-selector',
			'section'   => $_section,
			'priority'  => 21,
			'title'     => __( 'Alignment', 'smarttoolz' ),
			'context'   => SmartToolz_Builder_Helper::$general_tab,
			'transport' => 'postMessage',
			'choices'   => array(
				'flex-start' => 'align-left',
				'center'     => 'align-center',
				'flex-end'   => 'align-right',
			),
		),

		// Option Group: Menu Color.
		array(
			'name'       => SMARTTOOLZ_THEME_SETTINGS . '[footer-menu-link-colors]',
			'type'       => 'control',
			'control'    => 'ast-color-group',
			'context'    => SmartToolz_Builder_Helper::$design_tab,
			'title'      => __( 'Link / Text', 'smarttoolz' ),
			'section'    => $_section,
			'transport'  => 'postMessage',
			'priority'   => 90,
			'responsive' => true,
			'divider'    => array( 'ast_class' => 'ast-section-spacing' ),
		),
		array(
			'name'       => SMARTTOOLZ_THEME_SETTINGS . '[footer-menu-background-colors]',
			'type'       => 'control',
			'control'    => 'ast-color-group',
			'context'    => SmartToolz_Builder_Helper::$design_tab,
			'title'      => __( 'Background', 'smarttoolz' ),
			'section'    => $_section,
			'transport'  => 'postMessage',
			'priority'   => 90,
			'responsive' => true,
			'divider'    => array( 'ast_class' => 'ast-bottom-section-divider' ),
		),
		// Option: Menu Color.
		array(
			'name'       => 'footer-menu-color-responsive',
			'default'    => smarttoolz_get_option( 'footer-menu-color-responsive' ),
			'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[footer-menu-link-colors]',
			'type'       => 'sub-control',
			'control'    => 'ast-responsive-color',
			'transport'  => 'postMessage',
			'tab'        => __( 'Normal', 'smarttoolz' ),
			'section'    => $_section,
			'title'      => __( 'Normal', 'smarttoolz' ),
			'responsive' => true,
			'rgba'       => true,
			'priority'   => 7,
		),

		// Option: Menu Background image, color.
		array(
			'name'       => 'footer-menu-bg-obj-responsive',
			'default'    => smarttoolz_get_option( 'footer-menu-bg-obj-responsive' ),
			'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[footer-menu-background-colors]',
			'type'       => 'sub-control',
			'control'    => 'ast-responsive-background',
			'section'    => $_section,
			'transport'  => 'postMessage',
			'tab'        => __( 'Normal', 'smarttoolz' ),
			'data_attrs' => array( 'name' => 'footer-menu-bg-obj-responsive' ),
			'title'      => __( 'Normal', 'smarttoolz' ),
			'label'      => __( 'Normal', 'smarttoolz' ),
			'priority'   => 9,
		),

		// Option: Menu Hover Color.
		array(
			'name'       => 'footer-menu-h-color-responsive',
			'default'    => smarttoolz_get_option( 'footer-menu-h-color-responsive' ),
			'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[footer-menu-link-colors]',
			'tab'        => __( 'Hover', 'smarttoolz' ),
			'type'       => 'sub-control',
			'control'    => 'ast-responsive-color',
			'transport'  => 'postMessage',
			'title'      => __( 'Hover', 'smarttoolz' ),
			'section'    => $_section,
			'responsive' => true,
			'rgba'       => true,
			'priority'   => 19,
		),

		// Option: Menu Hover Background Color.
		array(
			'name'       => 'footer-menu-h-bg-color-responsive',
			'default'    => smarttoolz_get_option( 'footer-menu-h-bg-color-responsive' ),
			'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[footer-menu-background-colors]',
			'type'       => 'sub-control',
			'title'      => __( 'Hover', 'smarttoolz' ),
			'section'    => $_section,
			'control'    => 'ast-responsive-color',
			'transport'  => 'postMessage',
			'tab'        => __( 'Hover', 'smarttoolz' ),
			'responsive' => true,
			'rgba'       => true,
			'priority'   => 21,
		),

		// Option: Active Menu Color.
		array(
			'name'       => 'footer-menu-a-color-responsive',
			'default'    => smarttoolz_get_option( 'footer-menu-a-color-responsive' ),
			'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[footer-menu-link-colors]',
			'type'       => 'sub-control',
			'section'    => $_section,
			'control'    => 'ast-responsive-color',
			'transport'  => 'postMessage',
			'tab'        => __( 'Active', 'smarttoolz' ),
			'title'      => __( 'Active', 'smarttoolz' ),
			'responsive' => true,
			'rgba'       => true,
			'priority'   => 31,
		),

		// Option: Active Menu Background Color.
		array(
			'name'       => 'footer-menu-a-bg-color-responsive',
			'default'    => smarttoolz_get_option( 'footer-menu-a-bg-color-responsive' ),
			'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[footer-menu-background-colors]',
			'type'       => 'sub-control',
			'control'    => 'ast-responsive-color',
			'transport'  => 'postMessage',
			'section'    => $_section,
			'title'      => __( 'Active', 'smarttoolz' ),
			'tab'        => __( 'Active', 'smarttoolz' ),
			'responsive' => true,
			'rgba'       => true,
			'priority'   => 33,
		),

		/**
		 * Option: Divider
		 */
		array(
			'name'     => SMARTTOOLZ_THEME_SETTINGS . '[footer-main-menu-divider]',
			'section'  => $_section,
			'title'    => __( 'Spacing', 'smarttoolz' ),
			'type'     => 'control',
			'control'  => 'ast-heading',
			'priority' => 210,
			'settings' => array(),
			'context'  => SmartToolz_Builder_Helper::$design_tab,
			'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
		),

		// Option - Menu Space.
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[footer-main-menu-spacing]',
			'default'           => smarttoolz_get_option( 'footer-main-menu-spacing' ),
			'type'              => 'control',
			'control'           => 'ast-responsive-spacing',
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
			'transport'         => 'postMessage',
			'section'           => $_section,
			'context'           => SmartToolz_Builder_Helper::$design_tab,
			'priority'          => 210,
			'title'             => __( 'Menu Spacing', 'smarttoolz' ),
			'linked_choices'    => true,
			'unit_choices'      => array( 'px', 'em', '%' ),
			'choices'           => array(
				'top'    => __( 'Top', 'smarttoolz' ),
				'right'  => __( 'Right', 'smarttoolz' ),
				'bottom' => __( 'Bottom', 'smarttoolz' ),
				'left'   => __( 'Left', 'smarttoolz' ),
			),
			'divider'           => array( 'ast_class' => 'ast-bottom-section-divider ast-section-spacing' ),
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

	/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
	if ( defined( 'SMARTTOOLZ_EXT_VER' ) && SmartToolz_Ext_Extension::is_active( 'typography' ) ) {
		/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

		$new_configs = array(

			// Option Group: Menu Typography.
			array(
				'name'      => SMARTTOOLZ_THEME_SETTINGS . '[footer-menu-typography]',
				'default'   => smarttoolz_get_option( 'footer-menu-typography' ),
				'type'      => 'control',
				'control'   => 'ast-settings-group',
				'title'     => __( 'Menu Font', 'smarttoolz' ),
				'is_font'   => true,
				'section'   => $_section,
				'context'   => SmartToolz_Builder_Helper::$design_tab,
				'transport' => 'postMessage',
				'priority'  => 120,
			),

			// Option: Menu Font Size.

			array(
				'name'              => 'footer-menu-font-size',
				'default'           => smarttoolz_get_option( 'footer-menu-font-size' ),
				'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[footer-menu-typography]',
				'section'           => $_section,
				'type'              => 'sub-control',
				'priority'          => 23,
				'title'             => __( 'Font Size', 'smarttoolz' ),
				'transport'         => 'postMessage',
				'control'           => 'ast-responsive-slider',
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
		);
	} else {

		$new_configs = array(

			// Option: Menu Font Size.

			array(
				'name'              => SMARTTOOLZ_THEME_SETTINGS . '[footer-menu-font-size]',
				'default'           => smarttoolz_get_option( 'footer-menu-font-size' ),
				'section'           => $_section,
				'control'           => 'ast-responsive-slider',
				'context'           => SmartToolz_Builder_Helper::$design_tab,
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
				'type'              => 'control',
				'transport'         => 'postMessage',
				'title'             => __( 'Menu Font Size', 'smarttoolz' ),
				'priority'          => 120,
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
		);
	}

	$_configs = array_merge( $_configs, $new_configs );

	$_configs = array_merge( $_configs, SmartToolz_Builder_Base_Configuration::prepare_visibility_tab( $_section, 'footer' ) );

	if ( SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
		array_map( 'smarttoolz_save_footer_customizer_configs', $_configs );
	}

	return $_configs;
}

if ( SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
	add_action( 'init', 'smarttoolz_menu_footer_configuration' );
}
