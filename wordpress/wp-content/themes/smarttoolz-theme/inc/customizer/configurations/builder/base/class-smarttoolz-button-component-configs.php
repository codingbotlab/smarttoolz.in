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
class SmartToolz_Button_Component_Configs {
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
	public static function register_configuration( $configurations, $builder_type = 'header', $section = 'section-hb-button-' ) {

		if ( 'footer' === $builder_type ) {
			$class_obj        = SmartToolz_Builder_Footer::get_instance();
			$number_of_button = SmartToolz_Builder_Helper::$num_of_footer_button;
			$component_limit  = defined( 'SMARTTOOLZ_EXT_VER' ) ? SmartToolz_Builder_Helper::$component_limit : SmartToolz_Builder_Helper::$num_of_footer_button;
		} else {
			$class_obj        = SmartToolz_Builder_Header::get_instance();
			$number_of_button = SmartToolz_Builder_Helper::$num_of_header_button;
			$component_limit  = defined( 'SMARTTOOLZ_EXT_VER' ) ? SmartToolz_Builder_Helper::$component_limit : SmartToolz_Builder_Helper::$num_of_header_button;
		}

		$button_config = array();

		for ( $index = 1; $index <= $component_limit; $index++ ) {

			$_section = $section . $index;
			$_prefix  = 'button' . $index;

			/**
			 * These options are related to Header Section - Button.
			 * Prefix hs represents - Header Section.
			 */
			$button_config[] = array(

				/*
					* Header Builder section - Button Component Configs.
					*/
				array(
					'name'        => $_section,
					'type'        => 'section',
					'priority'    => 50,
					/* translators: %s Index */
					'title'       => 1 === $number_of_button ? __( 'Button', 'smarttoolz' ) : sprintf( __( 'Button %s', 'smarttoolz' ), $index ),
					'panel'       => 'panel-' . $builder_type . '-builder-group',
					'clone_index' => $index,
					'clone_type'  => $builder_type . '-button',
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
				 * Option: Button Text
				 */
				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-text]',
					'default'   => smarttoolz_get_option( $builder_type . '-' . $_prefix . '-text' ),
					'type'      => 'control',
					'control'   => 'text',
					'section'   => $_section,
					'priority'  => 20,
					'title'     => __( 'Text', 'smarttoolz' ),
					'transport' => 'postMessage',
					'partial'   => array(
						'selector'            => '.ast-' . $builder_type . '-button-' . $index,
						'container_inclusive' => false,
						'render_callback'     => array( $class_obj, 'button_' . $index ),
						'fallback_refresh'    => false,
					),
					'context'   => SmartToolz_Builder_Helper::$general_tab,
				),

				/**
				 * Option: Button Link
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-link-option]',
					'default'           => smarttoolz_get_option( $builder_type . '-' . $_prefix . '-link-option' ),
					'type'              => 'control',
					'control'           => 'ast-link',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_link' ),
					'section'           => $_section,
					'priority'          => 30,
					'title'             => __( 'Link', 'smarttoolz' ),
					'transport'         => 'postMessage',
					'partial'           => array(
						'selector'            => '.ast-' . $builder_type . '-button-' . $index,
						'container_inclusive' => false,
						'render_callback'     => array( $class_obj, 'button_' . $index ),
					),
					'context'           => SmartToolz_Builder_Helper::$general_tab,
					'divider'           => array( 'ast_class' => 'ast-top-section-divider' ),
				),

				/**
				 * Group: Primary Header Button Colors Group
				 */
				array(
					'name'       => SMARTTOOLZ_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-text-color-group]',
					'default'    => smarttoolz_get_option( $builder_type . '-' . $_prefix . '-color-group' ),
					'type'       => 'control',
					'control'    => 'ast-color-group',
					'title'      => __( 'Text Color', 'smarttoolz' ),
					'section'    => $_section,
					'transport'  => 'postMessage',
					'priority'   => 70,
					'context'    => SmartToolz_Builder_Helper::$design_tab,
					'responsive' => true,
					'divider'    => array( 'ast_class' => 'ast-section-spacing' ),
				),
				array(
					'name'       => SMARTTOOLZ_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-background-color-group]',
					'default'    => smarttoolz_get_option( $builder_type . '-' . $_prefix . '-color-group' ),
					'type'       => 'control',
					'control'    => 'ast-color-group',
					'title'      => __( 'Background Color', 'smarttoolz' ),
					'section'    => $_section,
					'transport'  => 'postMessage',
					'priority'   => 70,
					'context'    => SmartToolz_Builder_Helper::$design_tab,
					'responsive' => true,
				),

				/**
				 * Option: Button Text Color
				 */
				array(
					'name'       => $builder_type . '-' . $_prefix . '-text-color',
					'transport'  => 'postMessage',
					'default'    => smarttoolz_get_option( $builder_type . '-' . $_prefix . '-text-color' ),
					'type'       => 'sub-control',
					'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-text-color-group]',
					'section'    => $_section,
					'tab'        => __( 'Normal', 'smarttoolz' ),
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 9,
					'context'    => SmartToolz_Builder_Helper::$design_tab,
					'title'      => __( 'Normal', 'smarttoolz' ),
				),

				/**
				 * Option: Button Text Hover Color
				 */
				array(
					'name'       => $builder_type . '-' . $_prefix . '-text-h-color',
					'default'    => smarttoolz_get_option( $builder_type . '-' . $_prefix . '-text-h-color' ),
					'transport'  => 'postMessage',
					'type'       => 'sub-control',
					'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-text-color-group]',
					'section'    => $_section,
					'tab'        => __( 'Hover', 'smarttoolz' ),
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 9,
					'context'    => SmartToolz_Builder_Helper::$design_tab,
					'title'      => __( 'Hover', 'smarttoolz' ),
				),

				/**
				 * Option: Button Background Color
				 */
				array(
					'name'       => $builder_type . '-' . $_prefix . '-back-color',
					'default'    => smarttoolz_get_option( $builder_type . '-' . $_prefix . '-back-color' ),
					'transport'  => 'postMessage',
					'type'       => 'sub-control',
					'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-background-color-group]',
					'section'    => $_section,
					'tab'        => __( 'Normal', 'smarttoolz' ),
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 10,
					'context'    => SmartToolz_Builder_Helper::$design_tab,
					'title'      => __( 'Normal', 'smarttoolz' ),
				),

				/**
				 * Option: Button Button Hover Color
				 */
				array(
					'name'       => $builder_type . '-' . $_prefix . '-back-h-color',
					'default'    => smarttoolz_get_option( $builder_type . '-' . $_prefix . '-back-h-color' ),
					'transport'  => 'postMessage',
					'type'       => 'sub-control',
					'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-background-color-group]',
					'section'    => $_section,
					'tab'        => __( 'Hover', 'smarttoolz' ),
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 10,
					'context'    => SmartToolz_Builder_Helper::$design_tab,
					'title'      => __( 'Hover', 'smarttoolz' ),
				),

				array(
					'name'       => SMARTTOOLZ_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-builder-button-border-colors-group]',
					'type'       => 'control',
					'control'    => 'ast-color-group',
					'title'      => __( 'Border Color', 'smarttoolz' ),
					'section'    => $_section,
					'priority'   => 70,
					'transport'  => 'postMessage',
					'context'    => SmartToolz_Builder_Helper::$design_tab,
					'responsive' => true,
					'divider'    => array( 'ast_class' => 'ast-bottom-divider' ),
				),

				/**
				 * Option: Button Border Color
				 */
				array(
					'name'       => $builder_type . '-' . $_prefix . '-border-color',
					'default'    => smarttoolz_get_option( $builder_type . '-' . $_prefix . '-border-color' ),
					'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-builder-button-border-colors-group]',
					'transport'  => 'postMessage',
					'type'       => 'sub-control',
					'section'    => $_section,
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 70,
					'context'    => SmartToolz_Builder_Helper::$design_tab,
					'title'      => __( 'Normal', 'smarttoolz' ),
				),

				/**
				 * Option: Button Border Hover Color
				 */
				array(
					'name'       => $builder_type . '-' . $_prefix . '-border-h-color',
					'default'    => smarttoolz_get_option( $builder_type . '-' . $_prefix . '-border-h-color' ),
					'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-builder-button-border-colors-group]',
					'transport'  => 'postMessage',
					'type'       => 'sub-control',
					'section'    => $_section,
					'control'    => 'ast-responsive-color',
					'responsive' => true,
					'rgba'       => true,
					'priority'   => 70,
					'context'    => SmartToolz_Builder_Helper::$design_tab,
					'title'      => __( 'Hover', 'smarttoolz' ),
				),

				/**
				 * Option: Button Border Size
				 */
				array(
					'name'           => SMARTTOOLZ_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-border-size]',
					'default'        => smarttoolz_get_option( $builder_type . '-' . $_prefix . '-border-size' ),
					'type'           => 'control',
					'section'        => $_section,
					'control'        => 'ast-border',
					'transport'      => 'postMessage',
					'linked_choices' => true,
					'priority'       => 99,
					'title'          => __( 'Border Width', 'smarttoolz' ),
					'context'        => SmartToolz_Builder_Helper::$design_tab,
					'choices'        => array(
						'top'    => __( 'Top', 'smarttoolz' ),
						'right'  => __( 'Right', 'smarttoolz' ),
						'bottom' => __( 'Bottom', 'smarttoolz' ),
						'left'   => __( 'Left', 'smarttoolz' ),
					),
					'divider'        => array( 'ast_class' => 'ast-top-section-divider' ),
				),

				/**
				 * Option: Button Radius Fields
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-border-radius-fields]',
					'default'           => smarttoolz_get_option( $builder_type . '-' . $_prefix . '-border-radius-fields' ),
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
					'priority'          => 99,
					'context'           => SmartToolz_Builder_Helper::$design_tab,
					'connected'         => false,
					'divider'           => array( 'ast_class' => 'ast-top-section-divider' ),
				),

				/**
				 * Option: Primary Header Button Typography
				 */
				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-text-typography]',
					'default'   => smarttoolz_get_option( $builder_type . '-' . $_prefix . '-text-typography' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'is_font'   => true,
					'title'     => __( 'Font', 'smarttoolz' ),
					'section'   => $_section,
					'transport' => 'postMessage',
					'context'   => SmartToolz_Builder_Helper::$design_tab,
					'priority'  => 90,
				),

				/**
				 * Option: Primary Header Button Font Family
				 */
				array(
					'name'      => $builder_type . '-' . $_prefix . '-font-family',
					'default'   => smarttoolz_get_option( $builder_type . '-' . $_prefix . '-font-family' ),
					'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-text-typography]',
					'type'      => 'sub-control',
					'section'   => $_section,
					'control'   => 'ast-font',
					'font_type' => 'ast-font-family',
					'title'     => __( 'Font Family', 'smarttoolz' ),
					'context'   => SmartToolz_Builder_Helper::$general_tab,
					'connect'   => $builder_type . '-' . $_prefix . '-font-weight',
					'priority'  => 1,
					'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				),

				/**
				 * Option: Primary Footer Button Font Weight
				 */
				array(
					'name'              => $builder_type . '-' . $_prefix . '-font-weight',
					'default'           => smarttoolz_get_option( $builder_type . '-' . $_prefix . '-font-weight' ),
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-text-typography]',
					'type'              => 'sub-control',
					'section'           => $_section,
					'control'           => 'ast-font',
					'font_type'         => 'ast-font-weight',
					'title'             => __( 'Font Weight', 'smarttoolz' ),
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'connect'           => $builder_type . '-' . $_prefix . '-font-family',
					'priority'          => 2,
					'context'           => SmartToolz_Builder_Helper::$general_tab,
					'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				),

				/**
				 * Option: Primary Header Button Font Size
				 */

				array(
					'name'              => $builder_type . '-' . $_prefix . '-font-size',
					'default'           => smarttoolz_get_option( $builder_type . '-' . $_prefix . '-font-size' ),
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-text-typography]',
					'transport'         => 'postMessage',
					'title'             => __( 'Font Size', 'smarttoolz' ),
					'type'              => 'sub-control',
					'section'           => $_section,
					'control'           => 'ast-responsive-slider',
					'priority'          => 3,
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
				 * Option: Primary Footer Button Font Extras
				 */
				array(
					'name'     => $builder_type . '-' . $_prefix . '-font-extras',
					'parent'   => SMARTTOOLZ_THEME_SETTINGS . '[' . $builder_type . '-' . $_prefix . '-text-typography]',
					'section'  => $_section,
					'type'     => 'sub-control',
					'control'  => 'ast-font-extras',
					'priority' => 5,
					'default'  => smarttoolz_get_option( 'breadcrumb-font-extras' ),
					'context'  => SmartToolz_Builder_Helper::$general_tab,
					'title'    => __( 'Font Extras', 'smarttoolz' ),
				),
			);

			if ( 'footer' === $builder_type ) {
				$button_config[] = array(

					array(
						'name'      => SMARTTOOLZ_THEME_SETTINGS . '[footer-button-' . $index . '-alignment]',
						'default'   => smarttoolz_get_option( 'footer-button-' . $index . '-alignment' ),
						'type'      => 'control',
						'control'   => 'ast-selector',
						'section'   => $_section,
						'priority'  => 35,
						'title'     => __( 'Alignment', 'smarttoolz' ),
						'context'   => SmartToolz_Builder_Helper::$general_tab,
						'transport' => 'postMessage',
						'choices'   => array(
							'flex-start' => 'align-left',
							'center'     => 'align-center',
							'flex-end'   => 'align-right',
						),
						'divider'   => array( 'ast_class' => 'ast-top-section-divider' ),
					),
				);
			}

			$button_config[] = SmartToolz_Builder_Base_Configuration::prepare_visibility_tab( $_section, $builder_type );

			$button_config[] = SmartToolz_Extended_Base_Configuration::prepare_advanced_tab( $_section );

		}

		$button_config = call_user_func_array( 'array_merge', $button_config + array( array() ) );

		return array_merge( $configurations, $button_config );
	}
}

/**
 * Kicking this off by creating object of this class.
 */

new SmartToolz_Button_Component_Configs();
