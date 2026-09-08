<?php
/**
 * Primary footer Configuration.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register primary footer builder Customizer Configurations.
 *
 * @since 4.5.2
 * @return array SmartToolz Customizer Configurations with updated configurations.
 */
function smarttoolz_primary_footer_configuration() {
	$_section = 'section-primary-footer-builder';

	$column_count = range( 1, SmartToolz_Builder_Helper::$num_of_footer_columns );
	$column_count = array_combine( $column_count, $column_count );

	$_configs = array(

		// Section: Primary Footer.
		array(
			'name'     => $_section,
			'type'     => 'section',
			'title'    => __( 'Primary Footer', 'smarttoolz' ),
			'panel'    => 'panel-footer-builder-group',
			'priority' => 20,
		),

		/**
		 * Option: Footer Builder Tabs
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
		 * Option: Column count
		 */

		array(
			'name'       => SMARTTOOLZ_THEME_SETTINGS . '[hb-footer-column]',
			'default'    => smarttoolz_get_option( 'hb-footer-column' ),
			'type'       => 'control',
			'control'    => 'ast-selector',
			'section'    => $_section,
			'priority'   => 2,
			'title'      => __( 'Column', 'smarttoolz' ),
			'choices'    => $column_count,
			'context'    => SmartToolz_Builder_Helper::$general_tab,
			'transport'  => 'postMessage',
			'partial'    => array(
				'selector'            => '.site-primary-footer-wrap',
				'container_inclusive' => false,
				'render_callback'     => array( SmartToolz_Builder_Footer::get_instance(), 'primary_footer' ),
			),
			'renderAs'   => 'text',
			'responsive' => false,
			'divider'    => array( 'ast_class' => 'ast-section-spacing ast-bottom-divider' ),
		),

		/**
		 * Option: Row Layout
		 */
		array(
			'name'        => SMARTTOOLZ_THEME_SETTINGS . '[hb-footer-layout]',
			'section'     => $_section,
			'default'     => smarttoolz_get_option( 'hb-footer-layout' ),
			'priority'    => 3,
			'title'       => __( 'Layout', 'smarttoolz' ),
			'type'        => 'control',
			'control'     => 'ast-row-layout',
			'context'     => SmartToolz_Builder_Helper::$general_tab,
			'input_attrs' => array(
				'responsive' => true,
				'footer'     => 'primary',
				'layout'     => SmartToolz_Builder_Helper::$footer_row_layouts,
			),
			'divider'     => array( 'ast_class' => 'ast-bottom-section-divider' ),
			'transport'   => 'postMessage',
		),

		/**
		 * Option: Layout Width
		 */
		array(
			'name'       => SMARTTOOLZ_THEME_SETTINGS . '[hb-footer-layout-width]',
			'default'    => smarttoolz_get_option( 'hb-footer-layout-width' ),
			'type'       => 'control',
			'control'    => 'ast-selector',
			'section'    => $_section,
			'priority'   => 25,
			'title'      => __( 'Width', 'smarttoolz' ),
			'choices'    => array(
				'full'    => __( 'Full Width', 'smarttoolz' ),
				'content' => __( 'Content Width', 'smarttoolz' ),
			),
			'context'    => SmartToolz_Builder_Helper::$general_tab,
			'transport'  => 'postMessage',
			'renderAs'   => 'text',
			'responsive' => false,
			'divider'    => array( 'ast_class' => 'ast-bottom-section-divider' ),
		),

		// Section: Primary Footer Height.
		array(
			'name'        => SMARTTOOLZ_THEME_SETTINGS . '[hb-primary-footer-height]',
			'section'     => $_section,
			'transport'   => 'refresh',
			'default'     => smarttoolz_get_option( 'hb-primary-footer-height' ),
			'priority'    => 30,
			'title'       => __( 'Height', 'smarttoolz' ),
			'suffix'      => 'px',
			'type'        => 'control',
			'control'     => 'ast-slider',
			'input_attrs' => array(
				'min'  => 30,
				'step' => 1,
				'max'  => 600,
			),
			'divider'     => array( 'ast_class' => 'ast-bottom-section-divider' ),
			'context'     => SmartToolz_Builder_Helper::$general_tab,
		),

		/**
		 * Option: Vertical Alignment
		 */
		array(
			'name'       => SMARTTOOLZ_THEME_SETTINGS . '[hb-footer-vertical-alignment]',
			'default'    => smarttoolz_get_option( 'hb-footer-vertical-alignment' ),
			'type'       => 'control',
			'control'    => 'ast-selector',
			'section'    => $_section,
			'priority'   => 30,
			'title'      => __( 'Vertical Alignment', 'smarttoolz' ),
			'choices'    => array(
				'flex-start' => __( 'Top', 'smarttoolz' ),
				'center'     => __( 'Middle', 'smarttoolz' ),
				'flex-end'   => __( 'Bottom', 'smarttoolz' ),
			),
			'context'    => SmartToolz_Builder_Helper::$general_tab,
			'transport'  => 'postMessage',
			'renderAs'   => 'text',
			'responsive' => false,
		),

		array(
			'name'     => SMARTTOOLZ_THEME_SETTINGS . '[hb-stack]',
			'default'  => smarttoolz_get_option( 'hb-stack' ),
			'type'     => 'control',
			'control'  => 'ast-selector',
			'section'  => $_section,
			'priority' => 5,
			'title'    => __( 'Inner Elements Layout', 'smarttoolz' ),
			'choices'  => array(
				'stack'  => __( 'Stack', 'smarttoolz' ),
				'inline' => __( 'Inline', 'smarttoolz' ),
			),
			'context'  => SmartToolz_Builder_Helper::$general_tab,
			'renderAs' => 'text',
			'divider'  => array( 'ast_class' => 'ast-bottom-section-divider' ),
		),

		// Option: Footer Separator.
		array(
			'name'        => SMARTTOOLZ_THEME_SETTINGS . '[hb-footer-main-sep]',
			'transport'   => 'postMessage',
			'default'     => smarttoolz_get_option( 'hb-footer-main-sep' ),
			'type'        => 'control',
			'control'     => 'ast-slider',
			'section'     => $_section,
			'priority'    => 4,
			'title'       => __( 'Top Border Size', 'smarttoolz' ),
			'suffix'      => 'px',
			'input_attrs' => array(
				'min'  => 0,
				'step' => 1,
				'max'  => 600,
			),
			'context'     => SmartToolz_Builder_Helper::$design_tab,
			'divider'     => array( 'ast_class' => 'ast-section-spacing ast-bottom-divider' ),
		),

		// Option: Footer Top Boder Color.
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[hb-footer-main-sep-color]',
			'transport'         => 'postMessage',
			'default'           => smarttoolz_get_option( 'hb-footer-main-sep-color' ),
			'type'              => 'control',
			'control'           => 'ast-color',
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			'section'           => $_section,
			'priority'          => 5,
			'title'             => __( 'Border Color', 'smarttoolz' ),
			'context'           => array(
				SmartToolz_Builder_Helper::$design_tab_config,
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[hb-footer-main-sep]',
					'operator' => '>=',
					'value'    => 1,
				),
			),
			'divider'           => array( 'ast_class' => 'ast-bottom-section-divider' ),
		),

		// Sub Option: Footer Background.
		array(
			'name'       => SMARTTOOLZ_THEME_SETTINGS . '[hb-footer-bg-obj-responsive]',
			'section'    => $_section,
			'type'       => 'control',
			'control'    => 'ast-responsive-background',
			'transport'  => 'postMessage',
			'priority'   => 7,
			'data_attrs' => array(
				'name' => 'hb-footer-bg-obj-responsive',
			),
			'default'    => smarttoolz_get_option( 'hb-footer-bg-obj-responsive' ),
			'title'      => __( 'Background', 'smarttoolz' ),
			'context'    => SmartToolz_Builder_Helper::$design_tab,
			'divider'    => array( 'ast_class' => 'ast-bottom-section-divider' ),
		),

		/**
		 * Option: Inner Spacing
		 */
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[hb-inner-spacing]',
			'section'           => $_section,
			'priority'          => 205,
			'transport'         => 'postMessage',
			'default'           => smarttoolz_get_option( 'hb-inner-spacing' ),
			'title'             => __( 'Inner Column Spacing', 'smarttoolz' ),
			'suffix'            => 'px',
			'type'              => 'control',
			'control'           => 'ast-responsive-slider',
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
			'input_attrs'       => array(
				'min'  => 0,
				'step' => 1,
				'max'  => 200,
			),
			'context'           => SmartToolz_Builder_Helper::$design_tab,
		),

	);

	$_configs = array_merge( $_configs, SmartToolz_Extended_Base_Configuration::prepare_advanced_tab( $_section ) );

	$_configs = array_merge( $_configs, SmartToolz_Builder_Base_Configuration::prepare_visibility_tab( $_section, 'footer' ) );

	if ( SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
		array_map( 'smarttoolz_save_footer_customizer_configs', $_configs );
	}

	return $_configs;
}

if ( SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
	add_action( 'init', 'smarttoolz_primary_footer_configuration' );
}
