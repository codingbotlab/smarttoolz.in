<?php
/**
 * Typography - Breadcrumbs Options for theme.
 *
 * @package     SmartToolz
 * @link        https://www.brainstormforce.com
 * @since       SmartToolz 1.7.0
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
 * Customizer Sanitizes
 *
 * @since 1.7.0
 */
if ( ! class_exists( 'SmartToolz_Breadcrumbs_Typo_Configs' ) ) {

	/**
	 * Register Colors and Background - Breadcrumbs Options Customizer Configurations.
	 */
	class SmartToolz_Breadcrumbs_Typo_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register Colors and Background - Breadcrumbs Options Customizer Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.7.0
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/*
				 * Breadcrumb Typography
				 */
				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[section-breadcrumb-typo]',
					'default'   => smarttoolz_get_option( 'section-breadcrumb-typo' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'is_font'   => true,
					'title'     => esc_html__( 'Content Font', 'smarttoolz' ),
					'section'   => 'section-breadcrumb',
					'transport' => 'postMessage',
					'priority'  => 71,
					'context'   => array(
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[breadcrumb-position]',
							'operator' => '!=',
							'value'    => 'none',
						),
						true === SmartToolz_Builder_Helper::$is_header_footer_builder_active ?
							SmartToolz_Builder_Helper::$design_tab_config : SmartToolz_Builder_Helper::$general_tab_config,
					),
					'divider'   => array( 'ast_class' => 'ast-section-spacing ast-bottom-section-divider' ),
				),

				/**
				 * Option: Font Family
				 */
				array(
					'name'      => 'breadcrumb-font-family',
					'default'   => smarttoolz_get_option( 'breadcrumb-font-family' ),
					'type'      => 'sub-control',
					'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[section-breadcrumb-typo]',
					'section'   => 'section-breadcrumb',
					'control'   => 'ast-font',
					'font_type' => 'ast-font-family',
					'title'     => esc_html__( 'Font Family', 'smarttoolz' ),
					'connect'   => 'breadcrumb-font-weight',
					'priority'  => 5,
					'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				),

				/**
				 * Option: Font Weight
				 */
				array(
					'name'              => 'breadcrumb-font-weight',
					'control'           => 'ast-font',
					'type'              => 'sub-control',
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[section-breadcrumb-typo]',
					'section'           => 'section-breadcrumb',
					'font_type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => smarttoolz_get_option( 'breadcrumb-font-weight' ),
					'title'             => esc_html__( 'Font Weight', 'smarttoolz' ),
					'connect'           => 'breadcrumb-font-family',
					'priority'          => 10,
					'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				),

				/**
				 * Option: Font Size
				 */

				array(
					'name'              => 'breadcrumb-font-size',
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[section-breadcrumb-typo]',
					'type'              => 'sub-control',
					'control'           => 'ast-responsive-slider',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
					'section'           => 'section-breadcrumb',
					'transport'         => 'postMessage',
					'title'             => esc_html__( 'Font Size', 'smarttoolz' ),
					'priority'          => 10,
					'default'           => smarttoolz_get_option( 'breadcrumb-font-size' ),
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
				 * Option: Breadcrumb Content Font Extras
				 */
				array(
					'name'     => 'breadcrumb-font-extras',
					'type'     => 'sub-control',
					'parent'   => SMARTTOOLZ_THEME_SETTINGS . '[section-breadcrumb-typo]',
					'control'  => 'ast-font-extras',
					'section'  => 'section-breadcrumb',
					'priority' => 25,
					'default'  => smarttoolz_get_option( 'breadcrumb-font-extras' ),
					'title'    => esc_html__( 'Line Height', 'smarttoolz' ),
				),

			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new SmartToolz_Breadcrumbs_Typo_Configs();
