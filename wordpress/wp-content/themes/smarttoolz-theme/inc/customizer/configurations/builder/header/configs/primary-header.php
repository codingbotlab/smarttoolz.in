<?php
/**
 * Primary Header Configuration.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Primary header builder Customizer Configurations.
 *
 * @since 4.5.2
 * @return array SmartToolz Customizer Configurations with updated configurations.
 */
function smarttoolz_primary_header_configuration() {
	$_section = 'section-primary-header-builder';

	$_configs = array(

		/*
		 * Panel - New Header
		 *
		 * @since 3.0.0
		 */
		array(
			'name'     => 'panel-header-builder-group',
			'type'     => 'panel',
			'priority' => 20,
			'title'    => __( 'Header', 'smarttoolz' ),
		),

		// Section: Primary Header.
		array(
			'name'     => $_section,
			'type'     => 'section',
			'title'    => __( 'Primary Header', 'smarttoolz' ),
			'panel'    => 'panel-header-builder-group',
			'priority' => 20,
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

		// Section: Primary Header Height.
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[hb-header-height]',
			'section'           => $_section,
			'transport'         => 'postMessage',
			'default'           => smarttoolz_get_option( 'hb-header-height' ),
			'priority'          => 3,
			'title'             => __( 'Height', 'smarttoolz' ),
			'type'              => 'control',
			'control'           => 'ast-responsive-slider',
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
			'suffix'            => 'px',
			'input_attrs'       => array(
				'min'  => 30,
				'step' => 1,
				'max'  => 600,
			),
			'context'           => SmartToolz_Builder_Helper::$general_tab,
			'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
		),

		// Sub Option: Header Background.
		array(
			'name'        => SMARTTOOLZ_THEME_SETTINGS . '[hb-header-bg-obj-responsive]',
			'section'     => $_section,
			'type'        => 'control',
			'control'     => 'ast-responsive-background',
			'transport'   => 'postMessage',
			'context'     => SmartToolz_Builder_Helper::$design_tab,
			'priority'    => 5,
			'data_attrs'  => array(
				'name' => 'hb-header-bg-obj-responsive',
			),
			'default'     => smarttoolz_get_option( 'hb-header-bg-obj-responsive' ),
			'title'       => __( 'Background', 'smarttoolz' ),
			'description' => __( 'It would not be effective if transparent header is enabled.', 'smarttoolz' ),
			'divider'     => array( 'ast_class' => 'ast-section-spacing' ),
		),

		// Option: Header Bottom Boder Color.
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[hb-header-main-sep-color]',
			'transport'         => 'postMessage',
			'default'           => smarttoolz_get_option( 'hb-header-main-sep-color' ),
			'type'              => 'control',
			'control'           => 'ast-color',
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			'section'           => $_section,
			'priority'          => 5,
			'title'             => __( 'Bottom Border Color', 'smarttoolz' ),
			'context'           => array(
				SmartToolz_Builder_Helper::$design_tab_config,
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[hb-header-main-sep]',
					'operator' => '>=',
					'value'    => 1,
				),
			),
		),

		// Option: Header Separator.
		array(
			'name'        => SMARTTOOLZ_THEME_SETTINGS . '[hb-header-main-sep]',
			'transport'   => 'postMessage',
			'default'     => smarttoolz_get_option( 'hb-header-main-sep' ),
			'type'        => 'control',
			'control'     => 'ast-slider',
			'section'     => $_section,
			'priority'    => 5,
			'title'       => __( 'Bottom Border Size', 'smarttoolz' ),
			'suffix'      => 'px',
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1,
				'max'  => 10,
			),
			'context'     => SmartToolz_Builder_Helper::$design_tab,
			'divider'     => array( 'ast_class' => 'ast-top-section-divider' ),
		),
	);

	$_configs = array_merge( $_configs, SmartToolz_Extended_Base_Configuration::prepare_advanced_tab( $_section ) );

	$_configs = array_merge( $_configs, SmartToolz_Builder_Base_Configuration::prepare_visibility_tab( $_section ) );

	if ( SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
		array_map( 'smarttoolz_save_header_customizer_configs', $_configs );
	}

	return $_configs;
}

if ( SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
	add_action( 'init', 'smarttoolz_primary_header_configuration' );
}
