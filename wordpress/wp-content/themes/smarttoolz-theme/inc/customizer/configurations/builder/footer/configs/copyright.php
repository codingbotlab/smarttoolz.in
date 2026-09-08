<?php
/**
 * Copyright footer Configuration.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register copyright footer builder Customizer Configurations.
 *
 * @since 4.5.2
 * @return array SmartToolz Customizer Configurations with updated configurations.
 */
function smarttoolz_copyright_footer_configuration() {
	$_section = 'section-footer-copyright';
	$_configs = array(

		/*
		* Footer Builder section
		*/
		array(
			'name'     => $_section,
			'type'     => 'section',
			'priority' => 5,
			'title'    => __( 'Copyright', 'smarttoolz' ),
			'panel'    => 'panel-footer-builder-group',
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
		 * Option: Footer Copyright Html Editor.
		 */
		array(
			'name'        => SMARTTOOLZ_THEME_SETTINGS . '[footer-copyright-editor]',
			'type'        => 'control',
			'control'     => 'ast-html-editor',
			'section'     => $_section,
			'transport'   => 'postMessage',
			'priority'    => 4,
			'default'     => smarttoolz_get_option( 'footer-copyright-editor', 'Copyright [copyright] [current_year] [site_title] | Powered by [theme_author]' ),
			'input_attrs' => array(
				'id' => 'ast-footer-copyright',
			),
			'partial'     => array(
				'selector'            => '.ast-footer-copyright',
				'container_inclusive' => true,
				'render_callback'     => array( SmartToolz_Builder_Footer::get_instance(), 'footer_copyright' ),
			),
			'context'     => SmartToolz_Builder_Helper::$general_tab,
			'divider'     => array( 'ast_class' => 'ast-section-spacing' ),
		),

		/**
		 * Option: Column Alignment
		 */
		array(
			'name'      => SMARTTOOLZ_THEME_SETTINGS . '[footer-copyright-alignment]',
			'default'   => smarttoolz_get_option( 'footer-copyright-alignment' ),
			'type'      => 'control',
			'control'   => 'ast-selector',
			'section'   => $_section,
			'priority'  => 6,
			'title'     => __( 'Alignment', 'smarttoolz' ),
			'context'   => SmartToolz_Builder_Helper::$general_tab,
			'transport' => 'postMessage',
			'choices'   => array(
				'left'   => 'align-left',
				'center' => 'align-center',
				'right'  => 'align-right',
			),
			'divider'   => array( 'ast_class' => 'ast-top-section-divider' ),
		),

		/**
		 * Option: Text Color.
		 */
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[footer-copyright-color]',
			'default'           => smarttoolz_get_option( 'footer-copyright-color' ),
			'type'              => 'control',
			'section'           => $_section,
			'priority'          => 8,
			'transport'         => 'postMessage',
			'control'           => 'ast-color',
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			'title'             => __( 'Text Color', 'smarttoolz' ),
			'context'           => SmartToolz_Builder_Helper::$design_tab,
			'divider'           => array( 'ast_class' => 'ast-bottom-section-divider' ),

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
			'priority' => 99,
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
			'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
		),
	);

	$_configs = array_merge( $_configs, SmartToolz_Builder_Base_Configuration::prepare_typography_options( $_section ) );

	$_configs = array_merge( $_configs, SmartToolz_Builder_Base_Configuration::prepare_visibility_tab( $_section, 'footer' ) );

	if ( SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
		array_map( 'smarttoolz_save_footer_customizer_configs', $_configs );
	}

	return $_configs;
}

if ( SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
	add_action( 'init', 'smarttoolz_copyright_footer_configuration', 10, 0 );
}
