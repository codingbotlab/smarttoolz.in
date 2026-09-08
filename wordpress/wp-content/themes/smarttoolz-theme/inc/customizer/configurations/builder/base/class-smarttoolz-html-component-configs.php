<?php
/**
 * SmartToolz Theme Customizer Configuration Builder.
 *
 * @package     smarttoolz-builder
 * @link        https://wpsmarttoolz.com/
 * @since       3.0.0
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Builder Customizer Configurations.
 *
 * @since 3.0.0
 */
class SmartToolz_Html_Component_Configs {
	/**
	 * Register Builder Customizer Configurations.
	 *
	 * @param array  $configurations Configurations.
	 * @param string $builder_type Builder Type.
	 * @param string $section Section.
	 *
	 * @since 3.0.0
	 * @return array $configurations SmartToolz Customizer Configurations with updated configurations.
	 */
	public static function register_configuration( $configurations, $builder_type = 'header', $section = 'section-hb-html-' ) {

		$html_config = array();

		if ( 'footer' === $builder_type ) {
			$class_obj       = SmartToolz_Builder_Footer::get_instance();
			$component_limit = defined( 'SMARTTOOLZ_EXT_VER' ) ? SmartToolz_Builder_Helper::$component_limit : SmartToolz_Builder_Helper::$num_of_footer_html;
		} else {
			$class_obj       = SmartToolz_Builder_Header::get_instance();
			$component_limit = defined( 'SMARTTOOLZ_EXT_VER' ) ? SmartToolz_Builder_Helper::$component_limit : SmartToolz_Builder_Helper::$num_of_header_html;
		}

		for ( $index = 1; $index <= $component_limit; $index++ ) {

			$_section = $section . $index;

			$_configs = array(

				/**
				 * Option: Builder Tabs
				 */
				array(
					'name'        => $_section . '-ast-context-tabs',
					'section'     => $_section,
					'type'        => 'control',
					'control'     => 'ast-builder-header-control',
					'priority'    => 0,
					'description' => '',
				),

				/*
				 * Builder section
				 */
				array(
					'name'        => $_section,
					'type'        => 'section',
					'priority'    => 60,
					/* translators: %s Index */
					'title'       => sprintf( __( 'HTML %s', 'smarttoolz' ), $index ),
					'panel'       => 'panel-' . $builder_type . '-builder-group',
					'clone_index' => $index,
					'clone_type'  => $builder_type . '-html',
				),

				/**
				 * Option: Html Editor.
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[' . $builder_type . '-html-' . $index . ']',
					'type'              => 'control',
					'control'           => 'ast-html-editor',
					'section'           => $_section,
					'transport'         => 'postMessage',
					'priority'          => 4,
					'default'           => smarttoolz_get_option( $builder_type . '-html-' . $index ),
					'input_attrs'       => array(
						'id' => $builder_type . '-html-' . $index,
					),
					'partial'           => array(
						'selector'         => '.ast-' . $builder_type . '-html-' . $index,
						'render_callback'  => array( $class_obj, $builder_type . '_html_' . $index ),
						'fallback_refresh' => false,
					),
					'context'           => SmartToolz_Builder_Helper::$general_tab,
					'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_html_widget' ),
				),

				/**
				 * Option: HTML Color.
				 */

				array(
					'name'       => SMARTTOOLZ_THEME_SETTINGS . '[' . $builder_type . '-html-' . $index . 'color]',
					'default'    => smarttoolz_get_option( $builder_type . '-html-' . $index . 'color' ),
					'type'       => 'control',
					'section'    => $_section,
					'priority'   => 8,
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'title'      => __( 'Text Color', 'smarttoolz' ),
					'context'    => SmartToolz_Builder_Helper::$design_tab,
					'divider'    => array( 'ast_class' => 'ast-section-spacing' ),
				),
				array(
					'name'       => SMARTTOOLZ_THEME_SETTINGS . '[' . $builder_type . '-html-' . $index . '-link-group]',
					'default'    => smarttoolz_get_option( $builder_type . '-html-' . $index . '-color-group' ),
					'type'       => 'control',
					'control'    => 'ast-color-group',
					'title'      => __( 'Link Color', 'smarttoolz' ),
					'section'    => $_section,
					'transport'  => 'postMessage',
					'priority'   => 8,
					'context'    => SmartToolz_Builder_Helper::$design_tab,
					'responsive' => true,
					'divider'    => array( 'ast_class' => 'ast-bottom-section-divider' ),
				),

				/**
				 * Option: Link Color.
				 */
				array(
					'name'       => $builder_type . '-html-' . $index . 'link-color',
					'default'    => smarttoolz_get_option( $builder_type . '-html-' . $index . 'link-color' ),
					'type'       => 'sub-control',
					'section'    => $_section,
					'priority'   => 9,
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[' . $builder_type . '-html-' . $index . '-link-group]',
					'title'      => __( 'Normal', 'smarttoolz' ),
					'context'    => SmartToolz_Builder_Helper::$design_tab,
					'divider'    => array( 'ast_class' => 'ast-bottom-section-divider' ),
				),

				/**
				 * Option: Link Hover Color.
				 */
				array(
					'name'       => $builder_type . '-html-' . $index . 'link-h-color',
					'default'    => smarttoolz_get_option( $builder_type . '-html-' . $index . 'link-h-color' ),
					'type'       => 'sub-control',
					'section'    => $_section,
					'priority'   => 10,
					'transport'  => 'postMessage',
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[' . $builder_type . '-html-' . $index . '-link-group]',
					'title'      => __( 'Hover', 'smarttoolz' ),
					'context'    => SmartToolz_Builder_Helper::$design_tab,
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
					'priority' => 109,
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
					'priority'          => 109,
					'title'             => __( 'Margin', 'smarttoolz' ),
					'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
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

			if ( 'footer' === $builder_type ) {
				$_configs[] = array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[footer-html-' . $index . '-alignment]',
					'default'   => smarttoolz_get_option( 'footer-html-' . $index . '-alignment' ),
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
				);
			}

			$html_config[] = SmartToolz_Builder_Base_Configuration::prepare_visibility_tab( $_section, $builder_type );

			$html_config[] = SmartToolz_Builder_Base_Configuration::prepare_typography_options( $_section );

			$html_config[] = $_configs;
		}

		$html_config = call_user_func_array( 'array_merge', $html_config + array( array() ) );
		return array_merge( $configurations, $html_config );
	}
}

/**
 * Kicking this off by creating object of this class.
 */

new SmartToolz_Html_Component_Configs();
