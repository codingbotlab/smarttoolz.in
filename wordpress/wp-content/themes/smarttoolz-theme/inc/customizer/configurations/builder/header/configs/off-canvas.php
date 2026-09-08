<?php
/**
 * Off canvas Header Configuration.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register off-canvas header builder Customizer Configurations.
 *
 * @since 4.5.2
 * @return array SmartToolz Customizer Configurations with updated configurations.
 */
function smarttoolz_header_off_canvas_configuration() {
	$_section = 'section-popup-header-builder';

	$_configs = array(

		// Section: Off-Canvas.
		array(
			'name'     => $_section,
			'type'     => 'section',
			'title'    => __( 'Off-Canvas', 'smarttoolz' ),
			'panel'    => 'panel-header-builder-group',
			'priority' => 30,
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
		 * Option: Mobile Header Type.
		 */
		array(
			'name'       => SMARTTOOLZ_THEME_SETTINGS . '[mobile-header-type]',
			'default'    => smarttoolz_get_option( 'mobile-header-type' ),
			'type'       => 'control',
			'control'    => 'ast-selector',
			'section'    => $_section,
			'priority'   => 25,
			'title'      => __( 'Header Type', 'smarttoolz' ),
			'choices'    => array(
				'off-canvas' => __( 'Flyout', 'smarttoolz' ),
				'full-width' => __( 'Full-Screen', 'smarttoolz' ),
				'dropdown'   => __( 'Dropdown', 'smarttoolz' ),
			),
			'transport'  => 'refresh',
			'context'    => SmartToolz_Builder_Helper::$general_tab,
			'renderAs'   => 'text',
			'responsive' => false,
		),

		/**
		 * Option: Off-Canvas Move Body.
		 */
		array(
			'name'        => SMARTTOOLZ_THEME_SETTINGS . '[off-canvas-move-body]',
			'default'     => smarttoolz_get_option( 'off-canvas-move-body' ),
			'type'        => 'control',
			'control'     => 'ast-toggle-control',
			'section'     => $_section,
			'priority'    => 30,
			'title'       => __( 'Move Body', 'smarttoolz' ),
			'description' => __( 'Enable to shift the body content when the off-canvas menu opens.', 'smarttoolz' ),
			'context'     => array(
				SmartToolz_Builder_Helper::$general_tab_config,
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[mobile-header-type]',
					'operator' => '==',
					'value'    => 'dropdown',
				),
			),
			'divider'     => array( 'ast_class' => 'ast-top-divider ast-section-spacing' ),
		),

		array(
			'name'     => SMARTTOOLZ_THEME_SETTINGS . '[off-canvas-move-body-notice]',
			'type'     => 'control',
			'control'  => 'ast-description',
			'section'  => $_section,
			'priority' => 30,
			'help'     => esc_html__( 'Note: This is not applicable on Transparent and Sticky Headers!', 'smarttoolz' ),
			'context'  => array(
				SmartToolz_Builder_Helper::$general_tab_config,
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[mobile-header-type]',
					'operator' => '==',
					'value'    => 'dropdown',
				),
			),
		),

		/**
		 * Option: Off-Canvas Slide-Out.
		 */
		array(
			'name'       => SMARTTOOLZ_THEME_SETTINGS . '[off-canvas-slide]',
			'default'    => smarttoolz_get_option( 'off-canvas-slide' ),
			'type'       => 'control',
			'transport'  => 'postMessage',
			'control'    => 'ast-selector',
			'section'    => $_section,
			'priority'   => 30,
			'title'      => __( 'Position', 'smarttoolz' ),
			'choices'    => array(
				'left'  => __( 'Left', 'smarttoolz' ),
				'right' => __( 'Right', 'smarttoolz' ),
			),
			'context'    => array(
				SmartToolz_Builder_Helper::$general_tab_config,
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[mobile-header-type]',
					'operator' => '==',
					'value'    => 'off-canvas',
				),
			),
			'renderAs'   => 'text',
			'responsive' => false,
			'divider'    => array( 'ast_class' => 'ast-top-divider ast-bottom-divider' ),
		),

		/**
		 * Option: Toggle on click of button or link.
		 */
		array(
			'name'       => SMARTTOOLZ_THEME_SETTINGS . '[header-builder-menu-toggle-target]',
			'default'    => smarttoolz_get_option( 'header-builder-menu-toggle-target' ),
			'type'       => 'control',
			'control'    => 'ast-selector',
			'section'    => $_section,
			'context'    => SmartToolz_Builder_Helper::$general_tab,
			'priority'   => 40,
			'title'      => __( 'Dropdown Target', 'smarttoolz' ),
			'suffix'     => '',
			'choices'    => array(
				'icon' => __( 'Icon', 'smarttoolz' ),
				'link' => __( 'Link', 'smarttoolz' ),
			),
			'renderAs'   => 'text',
			'responsive' => false,
			'transport'  => 'postMessage',
			'divider'    => array( 'ast_class' => 'ast-bottom-section-divider ast-top-section-divider' ),
		),

		/**
		 * Option: Content alignment option for offcanvas
		 */
		array(
			'name'       => SMARTTOOLZ_THEME_SETTINGS . '[header-offcanvas-content-alignment]',
			'default'    => smarttoolz_get_option( 'header-offcanvas-content-alignment' ),
			'type'       => 'control',
			'control'    => 'ast-selector',
			'section'    => $_section,
			'context'    => SmartToolz_Builder_Helper::$general_tab,
			'priority'   => 40,
			'title'      => __( 'Content Alignment', 'smarttoolz' ),
			'suffix'     => '',
			'choices'    => array(
				'flex-start' => __( 'Left', 'smarttoolz' ),
				'center'     => __( 'Center', 'smarttoolz' ),
				'flex-end'   => __( 'Right', 'smarttoolz' ),
			),
			'renderAs'   => 'text',
			'responsive' => false,
			'transport'  => 'postMessage',
		),

		// Option Group: Off-Canvas Colors Group.
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[off-canvas-background]',
			'type'              => 'control',
			'control'           => 'ast-background',
			'title'             => __( 'Background', 'smarttoolz' ),
			'section'           => $_section,
			'transport'         => 'postMessage',
			'priority'          => 26,
			'context'           => SmartToolz_Builder_Helper::$design_tab,
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_background_obj' ),
			'default'           => smarttoolz_get_option( 'off-canvas-background' ),
			'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
		),

		// Option: Off-Canvas Close Icon Color.
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[off-canvas-close-color]',
			'transport'         => 'postMessage',
			'default'           => smarttoolz_get_option( 'off-canvas-close-color' ),
			'type'              => 'control',
			'control'           => 'ast-color',
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			'section'           => $_section,
			'priority'          => 27,
			'title'             => __( 'Close Icon Color', 'smarttoolz' ),
			'context'           => array(
				'relation' => 'AND',
				SmartToolz_Builder_Helper::$design_tab_config,
				array(
					'relation' => 'OR',
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[mobile-header-type]',
						'operator' => '==',
						'value'    => 'off-canvas',
					),
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[mobile-header-type]',
						'operator' => '==',
						'value'    => 'full-width',
					),
				),
			),
		),

		// Spacing Between every element in the flyout.
		array(
			'name'      => SMARTTOOLZ_THEME_SETTINGS . '[off-canvas-inner-spacing]',
			'default'   => smarttoolz_get_option( 'off-canvas-inner-spacing' ),
			'type'      => 'control',
			'control'   => 'ast-slider',
			'title'     => __( 'Inner Element Spacing', 'smarttoolz' ),
			'section'   => $_section,
			'transport' => 'postMessage',
			'priority'  => 28,
			'context'   => SmartToolz_Builder_Helper::$design_tab,
			'divider'   => array( 'ast_class' => 'ast-top-section-divider' ),
		),

		// Option Group: Off-Canvas Colors Group.
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[off-canvas-background]',
			'type'              => 'control',
			'control'           => 'ast-background',
			'title'             => __( 'Background', 'smarttoolz' ),
			'section'           => $_section,
			'transport'         => 'postMessage',
			'priority'          => 30,
			'context'           => SmartToolz_Builder_Helper::$design_tab,
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_background_obj' ),
			'default'           => smarttoolz_get_option( 'off-canvas-background' ),
			'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
		),

		/**
		 * Option: Popup Padding.
		 */

		array(
			'name'           => SMARTTOOLZ_THEME_SETTINGS . '[off-canvas-padding]',
			'default'        => smarttoolz_get_option( 'off-canvas-padding' ),
			'type'           => 'control',
			'transport'      => 'postMessage',
			'control'        => 'ast-responsive-spacing',
			'section'        => $_section,
			'priority'       => 210,
			'title'          => __( 'Popup Padding', 'smarttoolz' ),
			'linked_choices' => true,
			'unit_choices'   => array( 'px', 'em', '%' ),
			'choices'        => array(
				'top'    => __( 'Top', 'smarttoolz' ),
				'right'  => __( 'Right', 'smarttoolz' ),
				'bottom' => __( 'Bottom', 'smarttoolz' ),
				'left'   => __( 'Left', 'smarttoolz' ),
			),
			'context'        => array(
				'relation' => 'AND',
				SmartToolz_Builder_Helper::$design_tab_config,
				array(
					'relation' => 'OR',
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[mobile-header-type]',
						'operator' => '==',
						'value'    => 'off-canvas',
					),
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[mobile-header-type]',
						'operator' => '==',
						'value'    => 'full-width',
					),
				),
			),
			'divider'        => array( 'ast_class' => 'ast-top-section-divider' ),
		),

	);

	if ( SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
		array_map( 'smarttoolz_save_header_customizer_configs', $_configs );
	}

	return $_configs;
}

if ( SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
	add_action( 'init', 'smarttoolz_header_off_canvas_configuration' );
}
