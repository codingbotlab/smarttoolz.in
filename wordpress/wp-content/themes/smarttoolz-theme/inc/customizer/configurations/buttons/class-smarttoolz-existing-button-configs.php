<?php
/**
 * SmartToolz Theme Customizer Configuration Base.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 2.6.0
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Sanitizes
 *
 * @since 2.6.0
 */
if ( ! class_exists( 'SmartToolz_Existing_Button_Configs' ) ) {

	/**
	 * Register Button Customizer Configurations.
	 */
	class SmartToolz_Existing_Button_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register Button Customizer Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 2.6.0
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Primary Header Button Colors Divider
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[primary-header-button-color-divider]',
					'type'     => 'control',
					'control'  => 'ast-heading',
					'section'  => 'section-primary-menu',
					'title'    => __( 'Header Button', 'smarttoolz' ),
					'settings' => array(),
					'priority' => 17,
					'context'  => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-main-rt-section-button-style]',
							'operator' => '===',
							'value'    => 'custom-button',
						),
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-main-rt-section]',
							'operator' => '==',
							'value'    => 'button',
						),
					),

				),
				/**
				 * Group: Primary Header Button Colors Group
				 */
				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[primary-header-button-color-group]',
					'default'   => smarttoolz_get_option( 'primary-header-button-color-group' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Colors', 'smarttoolz' ),
					'section'   => 'section-primary-menu',
					'transport' => 'postMessage',
					'priority'  => 18,
					'context'   => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-main-rt-section-button-style]',
							'operator' => '===',
							'value'    => 'custom-button',
						),
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-main-rt-section]',
							'operator' => '==',
							'value'    => 'button',
						),
					),
				),
				/**
				 * Group: Primary Header Button Border Group
				 */
				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[primary-header-button-border-group]',
					'default'   => smarttoolz_get_option( 'primary-header-button-border-group' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Border', 'smarttoolz' ),
					'section'   => 'section-primary-menu',
					'transport' => 'postMessage',
					'priority'  => 19,
					'context'   => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-main-rt-section-button-style]',
							'operator' => '===',
							'value'    => 'custom-button',
						),
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-main-rt-section]',
							'operator' => '==',
							'value'    => 'button',
						),
					),
				),

				/**
				 * Option: Button Text Color
				 */
				array(
					'name'              => 'header-main-rt-section-button-text-color',
					'transport'         => 'postMessage',
					'default'           => smarttoolz_get_option( 'header-main-rt-section-button-text-color' ),
					'type'              => 'sub-control',
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[primary-header-button-color-group]',
					'section'           => 'section-primary-menu',
					'tab'               => __( 'Normal', 'smarttoolz' ),
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'priority'          => 10,
					'title'             => __( 'Text Color', 'smarttoolz' ),
				),

				/**
				 * Option: Button Text Hover Color
				 */
				array(
					'name'              => 'header-main-rt-section-button-text-h-color',
					'default'           => smarttoolz_get_option( 'header-main-rt-section-button-text-h-color' ),
					'transport'         => 'postMessage',
					'type'              => 'sub-control',
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[primary-header-button-color-group]',
					'section'           => 'section-primary-menu',
					'tab'               => __( 'Hover', 'smarttoolz' ),
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'priority'          => 10,
					'title'             => __( 'Text Color', 'smarttoolz' ),
				),

				/**
				 * Option: Button Background Color
				 */
				array(
					'name'              => 'header-main-rt-section-button-back-color',
					'default'           => smarttoolz_get_option( 'header-main-rt-section-button-back-color' ),
					'transport'         => 'postMessage',
					'type'              => 'sub-control',
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[primary-header-button-color-group]',
					'section'           => 'section-primary-menu',
					'tab'               => __( 'Normal', 'smarttoolz' ),
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'priority'          => 10,
					'title'             => __( 'Background Color', 'smarttoolz' ),
				),

				/**
				 * Option: Button Button Hover Color
				 */
				array(
					'name'              => 'header-main-rt-section-button-back-h-color',
					'default'           => smarttoolz_get_option( 'header-main-rt-section-button-back-h-color' ),
					'transport'         => 'postMessage',
					'type'              => 'sub-control',
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[primary-header-button-color-group]',
					'section'           => 'section-primary-menu',
					'tab'               => __( 'Hover', 'smarttoolz' ),
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'priority'          => 10,
					'title'             => __( 'Background Color', 'smarttoolz' ),
				),

				/**
				 * Option: Primary Header Button Typography
				 */
				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[primary-header-button-text-typography]',
					'default'   => smarttoolz_get_option( 'primary-header-button-text-typography' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'is_font'   => true,
					'title'     => __( 'Typography', 'smarttoolz' ),
					'section'   => 'section-primary-menu',
					'transport' => 'postMessage',
					'priority'  => 20,
					'context'   => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-main-rt-section-button-style]',
							'operator' => '===',
							'value'    => 'custom-button',
						),
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-main-rt-section]',
							'operator' => '==',
							'value'    => 'button',
						),
					),
				),

				/**
				 * Option: Primary Header Button Font Family
				 */
				array(
					'name'      => 'primary-header-button-font-family',
					'type'      => 'sub-control',
					'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[primary-header-button-text-typography]',
					'section'   => 'section-primary-menu',
					'control'   => 'ast-font',
					'font_type' => 'ast-font-family',
					'title'     => __( 'Font Family', 'smarttoolz' ),
					'default'   => smarttoolz_get_option( 'primary-header-button-font-family' ),
					'connect'   => SMARTTOOLZ_THEME_SETTINGS . '[primary-header-button-font-weight]',
					'priority'  => 1,
				),

				/**
				 * Option: Primary Header Button Font Size
				 */
				array(
					'name'        => 'primary-header-button-font-size',
					'transport'   => 'postMessage',
					'title'       => __( 'Font Size', 'smarttoolz' ),
					'type'        => 'sub-control',
					'parent'      => SMARTTOOLZ_THEME_SETTINGS . '[primary-header-button-text-typography]',
					'section'     => 'section-primary-menu',
					'default'     => smarttoolz_get_option( 'primary-header-button-font-size' ),
					'control'     => 'ast-responsive-slider',
					'suffix'      => array( 'px', 'em', 'vw', 'rem' ),
					'input_attrs' => array(
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
				 * Option: Primary Header Button Font Weight
				 */
				array(
					'name'              => 'primary-header-button-font-weight',
					'type'              => 'sub-control',
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[primary-header-button-text-typography]',
					'section'           => 'section-primary-menu',
					'control'           => 'ast-font',
					'font_type'         => 'ast-font-weight',
					'title'             => __( 'Font Weight', 'smarttoolz' ),
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => smarttoolz_get_option( 'primary-header-button-font-weight' ),
					'connect'           => 'primary-header-button-font-family',
					'priority'          => 2,
				),

				/**
				 * Option: Primary Header Button Text Transform
				 */
				array(
					'name'      => 'primary-header-button-text-transform',
					'transport' => 'postMessage',
					'default'   => smarttoolz_get_option( 'primary-header-button-text-transform' ),
					'title'     => __( 'Text Transform', 'smarttoolz' ),
					'type'      => 'sub-control',
					'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[primary-header-button-text-typography]',
					'section'   => 'section-primary-menu',
					'control'   => 'ast-select',
					'priority'  => 3,
					'choices'   => array(
						''           => __( 'Inherit', 'smarttoolz' ),
						'none'       => __( 'None', 'smarttoolz' ),
						'capitalize' => __( 'Capitalize', 'smarttoolz' ),
						'uppercase'  => __( 'Uppercase', 'smarttoolz' ),
						'lowercase'  => __( 'Lowercase', 'smarttoolz' ),
					),
				),

				/**
				 * Option: Primary Header Button Line Height
				 */
				array(
					'name'              => 'primary-header-button-line-height',
					'control'           => 'ast-slider',
					'transport'         => 'postMessage',
					'type'              => 'sub-control',
					'default'           => smarttoolz_get_option( 'primary-header-button-line-height' ),
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[primary-header-button-text-typography]',
					'section'           => 'section-primary-menu',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'title'             => __( 'Line Height', 'smarttoolz' ),
					'suffix'            => 'em',
					'priority'          => 4,
					'input_attrs'       => array(
						'min'  => 1,
						'step' => 0.01,
						'max'  => 5,
					),
				),

				/**
				 * Option: Primary Header Button Letter Spacing
				 */
				array(
					'name'              => 'primary-header-button-letter-spacing',
					'control'           => 'ast-slider',
					'transport'         => 'postMessage',
					'type'              => 'sub-control',
					'default'           => smarttoolz_get_option( 'primary-header-button-letter-spacing' ),
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[primary-header-button-text-typography]',
					'section'           => 'section-primary-menu',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
					'title'             => __( 'Letter Spacing', 'smarttoolz' ),
					'suffix'            => 'px',
					'priority'          => 5,
					'input_attrs'       => array(
						'min'  => 1,
						'step' => 1,
						'max'  => 100,
					),
				),

				// Option: Custom Menu Button Border.
				array(
					'type'              => 'control',
					'control'           => 'ast-responsive-spacing',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[header-main-rt-section-button-padding]',
					'section'           => 'section-primary-menu',
					'transport'         => 'postMessage',
					'linked_choices'    => true,
					'priority'          => 21,
					'context'           => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-main-rt-section-button-style]',
							'operator' => '===',
							'value'    => 'custom-button',
						),
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-main-rt-section]',
							'operator' => '==',
							'value'    => 'button',
						),
					),
					'default'           => smarttoolz_get_option( 'header-main-rt-section-button-padding' ),
					'title'             => __( 'Padding', 'smarttoolz' ),
					'choices'           => array(
						'top'    => __( 'Top', 'smarttoolz' ),
						'right'  => __( 'Right', 'smarttoolz' ),
						'bottom' => __( 'Bottom', 'smarttoolz' ),
						'left'   => __( 'Left', 'smarttoolz' ),
					),
				),

				/**
				 * Option: Button Border Size
				 */
				array(
					'type'           => 'sub-control',
					'parent'         => SMARTTOOLZ_THEME_SETTINGS . '[primary-header-button-border-group]',
					'section'        => 'section-primary-menu',
					'control'        => 'ast-border',
					'name'           => 'header-main-rt-section-button-border-size',
					'transport'      => 'postMessage',
					'linked_choices' => true,
					'priority'       => 10,
					'default'        => smarttoolz_get_option( 'header-main-rt-section-button-border-size' ),
					'title'          => __( 'Width', 'smarttoolz' ),
					'choices'        => array(
						'top'    => __( 'Top', 'smarttoolz' ),
						'right'  => __( 'Right', 'smarttoolz' ),
						'bottom' => __( 'Bottom', 'smarttoolz' ),
						'left'   => __( 'Left', 'smarttoolz' ),
					),
				),

				/**
				 * Option: Button Border Color
				 */
				array(
					'name'              => 'header-main-rt-section-button-border-color',
					'default'           => smarttoolz_get_option( 'header-main-rt-section-button-border-color' ),
					'transport'         => 'postMessage',
					'type'              => 'sub-control',
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[primary-header-button-border-group]',
					'section'           => 'section-primary-menu',
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'priority'          => 12,
					'title'             => __( 'Color', 'smarttoolz' ),
				),

				/**
				 * Option: Button Border Hover Color
				 */
				array(
					'name'              => 'header-main-rt-section-button-border-h-color',
					'default'           => smarttoolz_get_option( 'header-main-rt-section-button-border-h-color' ),
					'transport'         => 'postMessage',
					'type'              => 'sub-control',
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[primary-header-button-border-group]',
					'section'           => 'section-primary-menu',
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'priority'          => 14,
					'title'             => __( 'Hover Color', 'smarttoolz' ),
				),

				/**
				 * Option: Button Border Radius
				 */
				array(
					'name'        => 'header-main-rt-section-button-border-radius',
					'default'     => smarttoolz_get_option( 'header-main-rt-section-button-border-radius' ),
					'type'        => 'sub-control',
					'parent'      => SMARTTOOLZ_THEME_SETTINGS . '[primary-header-button-border-group]',
					'section'     => 'section-primary-menu',
					'control'     => 'ast-slider',
					'suffix'      => 'px',
					'transport'   => 'postMessage',
					'priority'    => 16,
					'title'       => __( 'Border Radius', 'smarttoolz' ),
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 100,
					),
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new SmartToolz_Existing_Button_Configs();
