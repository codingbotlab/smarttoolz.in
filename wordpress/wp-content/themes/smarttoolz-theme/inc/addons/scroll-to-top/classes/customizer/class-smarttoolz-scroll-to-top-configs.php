<?php
/**
 * Scroll To Top Options for our theme.
 *
 * @package     SmartToolz
 * @link        https://www.brainstormforce.com
 * @since       4.0.0
 */

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'SmartToolz_Customizer_Config_Base' ) ) {
	return;
}

/**
 * Register Scroll To Top Customizer Configurations.
 */
class SmartToolz_Scroll_To_Top_Configs extends SmartToolz_Customizer_Config_Base {
	/**
	 * Register Scroll To Top Customizer Configurations.
	 *
	 * @param Array                $configurations SmartToolz Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since 4.0.0
	 * @return Array SmartToolz Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {

		$_configs = array(

			/**
			 * Option: Enable Scroll To Top
			 */
			array(
				'name'     => SMARTTOOLZ_THEME_SETTINGS . '[scroll-to-top-enable]',
				'default'  => smarttoolz_get_option( 'scroll-to-top-enable' ),
				'type'     => 'control',
				'section'  => 'section-scroll-to-top',
				'title'    => __( 'Enable Scroll to Top', 'smarttoolz' ),
				'priority' => 1,
				'control'  => 'ast-toggle-control',
				'divider'  => array( 'ast_class' => 'ast-section-spacing' ),
			),

			/**
			 * Option: Scroll to Top Display On
			 */
			array(
				'name'       => SMARTTOOLZ_THEME_SETTINGS . '[scroll-to-top-on-devices]',
				'default'    => smarttoolz_get_option( 'scroll-to-top-on-devices' ),
				'type'       => 'control',
				'control'    => 'ast-selector',
				'section'    => 'section-scroll-to-top',
				'priority'   => 10,
				'title'      => __( 'Display On', 'smarttoolz' ),
				'choices'    => array(
					'desktop' => __( 'Desktop', 'smarttoolz' ),
					'mobile'  => __( 'Mobile', 'smarttoolz' ),
					'both'    => __( 'Desktop + Mobile', 'smarttoolz' ),
				),
				'renderAs'   => 'text',
				'responsive' => false,
				'divider'    => array( 'ast_class' => 'ast-top-divider ast-bottom-divider' ),
				'context'    => array(
					'relation' => 'AND',
					SmartToolz_Builder_Helper::$general_tab_config,
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[scroll-to-top-enable]',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			/**
			 * Option: Scroll to Top Position
			 */
			array(
				'name'       => SMARTTOOLZ_THEME_SETTINGS . '[scroll-to-top-icon-position]',
				'default'    => smarttoolz_get_option( 'scroll-to-top-icon-position' ),
				'type'       => 'control',
				'control'    => 'ast-selector',
				'transport'  => 'postMessage',
				'section'    => 'section-scroll-to-top',
				'title'      => __( 'Position', 'smarttoolz' ),
				'choices'    => array(
					'left'  => __( 'Left', 'smarttoolz' ),
					'right' => __( 'Right', 'smarttoolz' ),
				),
				'priority'   => 11,
				'responsive' => false,
				'renderAs'   => 'text',
				'divider'    => array( 'ast_class' => 'ast-bottom-divider' ),
				'context'    => array(
					'relation' => 'AND',
					SmartToolz_Builder_Helper::$general_tab_config,
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[scroll-to-top-enable]',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			/**
			 * Option: Scroll To Top Icon Size
			 */
			array(
				'name'      => SMARTTOOLZ_THEME_SETTINGS . '[scroll-to-top-icon-size]',
				'default'   => smarttoolz_get_option( 'scroll-to-top-icon-size' ),
				'type'      => 'control',
				'control'   => 'ast-slider',
				'transport' => 'postMessage',
				'section'   => 'section-scroll-to-top',
				'title'     => __( 'Icon Size', 'smarttoolz' ),
				'suffix'    => 'px',
				'priority'  => 12,
				'context'   => array(
					'relation' => 'AND',
					SmartToolz_Builder_Helper::$general_tab_config,
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[scroll-to-top-enable]',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			array(
				'name'     => SMARTTOOLZ_THEME_SETTINGS . '[scroll-on-top-color-group]',
				'default'  => smarttoolz_get_option( 'scroll-on-top-color-group' ),
				'type'     => 'control',
				'control'  => 'ast-color-group',
				'title'    => __( 'Icon Color', 'smarttoolz' ),
				'section'  => 'section-scroll-to-top',
				'context'  => array(
					'relation' => 'AND',
					true === SmartToolz_Builder_Helper::$is_header_footer_builder_active ? SmartToolz_Builder_Helper::$design_tab_config : SmartToolz_Builder_Helper::$general_tab_config,
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[scroll-to-top-enable]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'priority' => 1,
				'divider'  => array( 'ast_class' => 'ast-section-spacing' ),
			),

			array(
				'name'      => SMARTTOOLZ_THEME_SETTINGS . '[scroll-on-top-bg-color-group]',
				'default'   => smarttoolz_get_option( 'scroll-on-top-bg-color-group' ),
				'type'      => 'control',
				'control'   => 'ast-color-group',
				'title'     => __( 'Background Color', 'smarttoolz' ),
				'section'   => 'section-scroll-to-top',
				'transport' => 'postMessage',
				'context'   => array(
					'relation' => 'AND',
					true === SmartToolz_Builder_Helper::$is_header_footer_builder_active ? SmartToolz_Builder_Helper::$design_tab_config : SmartToolz_Builder_Helper::$general_tab_config,
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[scroll-to-top-enable]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'priority'  => 1,
			),

			/**
			 * Option: Scroll To Top Radius
			 */
			array(
				'name'           => SMARTTOOLZ_THEME_SETTINGS . '[scroll-to-top-icon-radius-fields]',
				'default'        => smarttoolz_get_option( 'scroll-to-top-icon-radius-fields' ),
				'type'           => 'control',
				'control'        => 'ast-responsive-spacing',
				'transport'      => 'postMessage',
				'section'        => 'section-scroll-to-top',
				'title'          => __( 'Border Radius', 'smarttoolz' ),
				'suffix'         => 'px',
				'priority'       => 1,
				'divider'        => array( 'ast_class' => 'ast-top-section-divider' ),
				'context'        => array(
					'relation' => 'AND',
					true === SmartToolz_Builder_Helper::$is_header_footer_builder_active ? SmartToolz_Builder_Helper::$design_tab_config : SmartToolz_Builder_Helper::$general_tab_config,
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[scroll-to-top-enable]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'linked_choices' => true,
				'unit_choices'   => array( 'px', 'em', '%' ),
				'choices'        => array(
					'top'    => __( 'Top', 'smarttoolz' ),
					'right'  => __( 'Right', 'smarttoolz' ),
					'bottom' => __( 'Bottom', 'smarttoolz' ),
					'left'   => __( 'Left', 'smarttoolz' ),
				),
				'connected'      => false,
			),

			/**
			 * Option: Icon Color
			 */
			array(
				'name'              => 'scroll-to-top-icon-color',
				'default'           => smarttoolz_get_option( 'scroll-to-top-icon-color' ),
				'type'              => 'sub-control',
				'priority'          => 1,
				'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[scroll-on-top-color-group]',
				'section'           => 'section-scroll-to-top',
				'control'           => 'ast-color',
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
				'transport'         => 'postMessage',
				'title'             => __( 'Color', 'smarttoolz' ),
			),

			/**
			 * Option: Icon Background Color
			 */
			array(
				'name'              => 'scroll-to-top-icon-bg-color',
				'default'           => smarttoolz_get_option( 'scroll-to-top-icon-bg-color' ),
				'type'              => 'sub-control',
				'priority'          => 1,
				'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[scroll-on-top-bg-color-group]',
				'section'           => 'section-scroll-to-top',
				'transport'         => 'postMessage',
				'control'           => 'ast-color',
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
				'title'             => __( 'Color', 'smarttoolz' ),
			),

			/**
			 * Option: Icon Hover Color
			 */
			array(
				'name'              => 'scroll-to-top-icon-h-color',
				'default'           => smarttoolz_get_option( 'scroll-to-top-icon-h-color' ),
				'type'              => 'sub-control',
				'priority'          => 1,
				'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[scroll-on-top-color-group]',
				'section'           => 'section-scroll-to-top',
				'control'           => 'ast-color',
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
				'transport'         => 'postMessage',
				'title'             => __( 'Hover Color', 'smarttoolz' ),
			),

			/**
			 * Option: Link Hover Background Color
			 */
			array(
				'name'              => 'scroll-to-top-icon-h-bg-color',
				'default'           => smarttoolz_get_option( 'scroll-to-top-icon-h-bg-color' ),
				'type'              => 'sub-control',
				'priority'          => 1,
				'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[scroll-on-top-bg-color-group]',
				'section'           => 'section-scroll-to-top',
				'control'           => 'ast-color',
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
				'transport'         => 'postMessage',
				'title'             => __( 'Hover Color', 'smarttoolz' ),
			),
		);

		if ( true === SmartToolz_Builder_Helper::$is_header_footer_builder_active ) {
			$_configs[] = array(
				'name'        => 'section-scroll-to-top-ast-context-tabs',
				'section'     => 'section-scroll-to-top',
				'type'        => 'control',
				'control'     => 'ast-builder-header-control',
				'priority'    => 0,
				'description' => '',
			);
			$_configs[] = array(
				'name'     => SMARTTOOLZ_THEME_SETTINGS . '[enable-scroll-to-top-notice]',
				'type'     => 'control',
				'control'  => 'ast-description',
				'section'  => 'section-scroll-to-top',
				'priority' => 1,
				'label'    => '',
				'help'     => __( 'Note: To get design settings in action make sure to enable Scroll to Top.', 'smarttoolz' ),
				'context'  => array(
					'relation' => 'AND',
					SmartToolz_Builder_Helper::$design_tab_config,
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[scroll-to-top-enable]',
						'operator' => '!=',
						'value'    => true,
					),
				),
			);
		}

		return array_merge( $configurations, $_configs );
	}
}

/** Creating instance for getting customizer configs. */
new SmartToolz_Scroll_To_Top_Configs();
