<?php
/**
 * SmartToolz Theme Customizer Configuration Base.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 1.4.3
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Sanitizes
 *
 * @since 1.4.3
 */
if ( ! class_exists( 'SmartToolz_Customizer_Button_Configs' ) ) {

	/**
	 * Register Button Customizer Configurations.
	 */
	class SmartToolz_Customizer_Button_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register Button Customizer Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array();
			$id       = '';
			for ( $index = 0; $index < 2; $index++ ) {

				$id           = 1 === $index ? 'secondary-' : '';
				$_tab_configs = array(
					array(
						'name'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $id . 'button-preset-style]',
						'default'   => smarttoolz_get_option( $id . 'button-preset-style' ),
						'type'      => 'control',
						'control'   => 'ast-button-presets',
						'title'     => __( 'Button Presets', 'smarttoolz' ),
						'section'   => 'section-buttons',
						'options'   => array(
							'button_01' => array(
								'src'                  => 'btn-preset-01',
								'border-size'          => array(
									'top'    => 0,
									'right'  => 0,
									'bottom' => 0,
									'left'   => 0,
								),
								'button-radius-fields' => array(
									'desktop'      => array(
										'top'    => 0,
										'right'  => 0,
										'bottom' => 0,
										'left'   => 0,
									),
									'tablet'       => array(
										'top'    => '',
										'right'  => '',
										'bottom' => '',
										'left'   => '',
									),
									'mobile'       => array(
										'top'    => '',
										'right'  => '',
										'bottom' => '',
										'left'   => '',
									),
									'desktop-unit' => 'px',
									'tablet-unit'  => 'px',
									'mobile-unit'  => 'px',
								),
								'button-padding'       => array(
									'desktop'      => array(
										'top'    => 10,
										'right'  => 20,
										'bottom' => 10,
										'left'   => 20,
									),
									'tablet'       => array(
										'top'    => '',
										'right'  => '',
										'bottom' => '',
										'left'   => '',
									),
									'mobile'       => array(
										'top'    => '',
										'right'  => '',
										'bottom' => '',
										'left'   => '',
									),
									'desktop-unit' => 'px',
									'tablet-unit'  => 'px',
									'mobile-unit'  => 'px',
								),
								'button-bg-color'      => '',
								'button-bg-h-color'    => '',
								'button-color'         => '',
							),
							'button_02' => array(
								'src'                  => 'btn-preset-02',
								'border-size'          => array(
									'top'    => 0,
									'right'  => 0,
									'bottom' => 0,
									'left'   => 0,
								),
								'button-radius-fields' => array(
									'desktop'      => array(
										'top'    => 3,
										'right'  => 3,
										'bottom' => 3,
										'left'   => 3,
									),
									'tablet'       => array(
										'top'    => '',
										'right'  => '',
										'bottom' => '',
										'left'   => '',
									),
									'mobile'       => array(
										'top'    => '',
										'right'  => '',
										'bottom' => '',
										'left'   => '',
									),
									'desktop-unit' => 'px',
									'tablet-unit'  => 'px',
									'mobile-unit'  => 'px',
								),
								'button-padding'       => array(
									'desktop'      => array(
										'top'    => 10,
										'right'  => 20,
										'bottom' => 10,
										'left'   => 20,
									),
									'tablet'       => array(
										'top'    => '',
										'right'  => '',
										'bottom' => '',
										'left'   => '',
									),
									'mobile'       => array(
										'top'    => '',
										'right'  => '',
										'bottom' => '',
										'left'   => '',
									),
									'desktop-unit' => 'px',
									'tablet-unit'  => 'px',
									'mobile-unit'  => 'px',
								),
								'button-bg-color'      => '',
								'button-bg-h-color'    => '',
								'button-color'         => '',
							),
							'button_03' => array(
								'src'                  => 'btn-preset-03',
								'border-size'          => array(
									'top'    => 0,
									'right'  => 0,
									'bottom' => 0,
									'left'   => 0,
								),
								'button-radius-fields' => array(
									'desktop'      => array(
										'top'    => 30,
										'right'  => 30,
										'bottom' => 30,
										'left'   => 30,
									),
									'tablet'       => array(
										'top'    => '',
										'right'  => '',
										'bottom' => '',
										'left'   => '',
									),
									'mobile'       => array(
										'top'    => '',
										'right'  => '',
										'bottom' => '',
										'left'   => '',
									),
									'desktop-unit' => 'px',
									'tablet-unit'  => 'px',
									'mobile-unit'  => 'px',
								),
								'button-padding'       => array(
									'desktop'      => array(
										'top'    => 10,
										'right'  => 20,
										'bottom' => 10,
										'left'   => 20,
									),
									'tablet'       => array(
										'top'    => '',
										'right'  => '',
										'bottom' => '',
										'left'   => '',
									),
									'mobile'       => array(
										'top'    => '',
										'right'  => '',
										'bottom' => '',
										'left'   => '',
									),
									'desktop-unit' => 'px',
									'tablet-unit'  => 'px',
									'mobile-unit'  => 'px',
								),
								'button-bg-color'      => '',
								'button-bg-h-color'    => '',
								'button-color'         => '',
							),
							'button_04' => array(
								'src'                  => 'btn-preset-04',
								'border-size'          => array(
									'top'    => 1,
									'right'  => 1,
									'bottom' => 1,
									'left'   => 1,
								),
								'button-radius-fields' => array(
									'desktop'      => array(
										'top'    => 0,
										'right'  => 0,
										'bottom' => 0,
										'left'   => 0,
									),
									'tablet'       => array(
										'top'    => '',
										'right'  => '',
										'bottom' => '',
										'left'   => '',
									),
									'mobile'       => array(
										'top'    => '',
										'right'  => '',
										'bottom' => '',
										'left'   => '',
									),
									'desktop-unit' => 'px',
									'tablet-unit'  => 'px',
									'mobile-unit'  => 'px',
								),
								'button-padding'       => array(
									'desktop'      => array(
										'top'    => 10,
										'right'  => 20,
										'bottom' => 10,
										'left'   => 20,
									),
									'tablet'       => array(
										'top'    => '',
										'right'  => '',
										'bottom' => '',
										'left'   => '',
									),
									'mobile'       => array(
										'top'    => '',
										'right'  => '',
										'bottom' => '',
										'left'   => '',
									),
									'desktop-unit' => 'px',
									'tablet-unit'  => 'px',
									'mobile-unit'  => 'px',
								),
								'button-bg-color'      => 'rgba(0,0,0,0)',
								'button-bg-h-color'    => '',
								'button-color'         => '#0170B9',
							),
							'button_05' => array(
								'src'                  => 'btn-preset-05',
								'border-size'          => array(
									'top'    => 1,
									'right'  => 1,
									'bottom' => 1,
									'left'   => 1,
								),
								'button-radius-fields' => array(
									'desktop'      => array(
										'top'    => 3,
										'right'  => 3,
										'bottom' => 3,
										'left'   => 3,
									),
									'tablet'       => array(
										'top'    => '',
										'right'  => '',
										'bottom' => '',
										'left'   => '',
									),
									'mobile'       => array(
										'top'    => '',
										'right'  => '',
										'bottom' => '',
										'left'   => '',
									),
									'desktop-unit' => 'px',
									'tablet-unit'  => 'px',
									'mobile-unit'  => 'px',
								),
								'button-padding'       => array(
									'desktop'      => array(
										'top'    => 10,
										'right'  => 20,
										'bottom' => 10,
										'left'   => 20,
									),
									'tablet'       => array(
										'top'    => '',
										'right'  => '',
										'bottom' => '',
										'left'   => '',
									),
									'mobile'       => array(
										'top'    => '',
										'right'  => '',
										'bottom' => '',
										'left'   => '',
									),
									'desktop-unit' => 'px',
									'tablet-unit'  => 'px',
									'mobile-unit'  => 'px',
								),
								'button-bg-color'      => 'rgba(0,0,0,0)',
								'button-bg-h-color'    => '',
								'button-color'         => '#0170B9',
							),
							'button_06' => array(
								'src'                  => 'btn-preset-06',
								'border-size'          => array(
									'top'    => 1,
									'right'  => 1,
									'bottom' => 1,
									'left'   => 1,
								),
								'button-radius-fields' => array(
									'desktop'      => array(
										'top'    => 30,
										'right'  => 30,
										'bottom' => 30,
										'left'   => 30,
									),
									'tablet'       => array(
										'top'    => '',
										'right'  => '',
										'bottom' => '',
										'left'   => '',
									),
									'mobile'       => array(
										'top'    => '',
										'right'  => '',
										'bottom' => '',
										'left'   => '',
									),
									'desktop-unit' => 'px',
									'tablet-unit'  => 'px',
									'mobile-unit'  => 'px',
								),
								'button-padding'       => array(
									'desktop'      => array(
										'top'    => 10,
										'right'  => 20,
										'bottom' => 10,
										'left'   => 20,
									),
									'tablet'       => array(
										'top'    => '',
										'right'  => '',
										'bottom' => '',
										'left'   => '',
									),
									'mobile'       => array(
										'top'    => '',
										'right'  => '',
										'bottom' => '',
										'left'   => '',
									),
									'desktop-unit' => 'px',
									'tablet-unit'  => 'px',
									'mobile-unit'  => 'px',
								),
								'button-bg-color'      => 'rgba(0,0,0,0)',
								'button-bg-h-color'    => '',
								'button-color'         => '#0170B9',
							),
						),
						'priority'  => 18,
						'transport' => 'postMessage',
						'divider'   => array( 'ast_class' => 'ast-section-spacing ast-bottom-divider' ),
					),

					/**
					 * Group: Theme Button color Group
					 */
					array(
						'name'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $id . 'theme-button-color-group]',
						'default'   => smarttoolz_get_option( $id . 'theme-button-color-group' ),
						'type'      => 'control',
						'control'   => 'ast-color-group',
						'title'     => __( 'Text Color', 'smarttoolz' ),
						'section'   => 'section-buttons',
						'transport' => 'postMessage',
						'priority'  => 18,
					),

					/**
					 * Group: Theme Button background colors Group
					 */
					array(
						'name'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $id . 'theme-button-bg-color-group]',
						'default'   => smarttoolz_get_option( $id . 'theme-button-bg-color-group' ),
						'type'      => 'control',
						'control'   => 'ast-color-group',
						'title'     => __( 'Background Color', 'smarttoolz' ),
						'section'   => 'section-buttons',
						'transport' => 'postMessage',
						'priority'  => 18.5,
					),

					/**
					 * Group: Theme Button Border Group
					 */
					array(
						'name'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $id . 'theme-button-border-color-group]',
						'default'   => smarttoolz_get_option( $id . 'theme-button-border-color-group' ),
						'type'      => 'control',
						'control'   => 'ast-color-group',
						'title'     => __( 'Border Color', 'smarttoolz' ),
						'section'   => 'section-buttons',
						'transport' => 'postMessage',
						'priority'  => 18.5,
						'divider'   => array( 'ast_class' => 'ast-bottom-divider' ),
					),

					/**
					 * Option: Global Button Border Color
					 */
					array(
						'name'              => $id . 'theme-button-border-group-border-color',
						'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[' . $id . 'theme-button-border-color-group]',
						'default'           => smarttoolz_get_option( $id . 'theme-button-border-group-border-color' ),
						'transport'         => 'postMessage',
						'type'              => 'sub-control',
						'section'           => 'section-buttons',
						'control'           => 'ast-color',
						'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
						'priority'          => 18.5,
						'title'             => __( 'Normal', 'smarttoolz' ),
					),

					/**
					 * Option: Global Button Border Hover Color
					 */
					array(
						'name'              => $id . 'theme-button-border-group-border-h-color',
						'default'           => smarttoolz_get_option( $id . 'theme-button-border-group-border-h-color' ),
						'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[' . $id . 'theme-button-border-color-group]',
						'transport'         => 'postMessage',
						'type'              => 'sub-control',
						'section'           => 'section-buttons',
						'control'           => 'ast-color',
						'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
						'priority'          => 18.5,
						'title'             => __( 'Hover', 'smarttoolz' ),
					),

					/**
					 * Option: Button Color
					 */
					array(
						'name'    => $id . 'button-color',
						'default' => smarttoolz_get_option( $id . 'button-color' ),
						'type'    => 'sub-control',
						'parent'  => SMARTTOOLZ_THEME_SETTINGS . '[' . $id . 'theme-button-color-group]',
						'section' => 'section-buttons',
						'control' => 'ast-color',
						'title'   => __( 'Normal', 'smarttoolz' ),
					),

					/**
					 * Option: Button Hover Color
					 */
					array(
						'name'     => $id . 'button-h-color',
						'default'  => smarttoolz_get_option( $id . 'button-h-color' ),
						'type'     => 'sub-control',
						'parent'   => SMARTTOOLZ_THEME_SETTINGS . '[' . $id . 'theme-button-color-group]',
						'section'  => 'section-buttons',
						'control'  => 'ast-color',
						'title'    => __( 'Hover', 'smarttoolz' ),
						'priority' => 39,
					),

					/**
					 * Option: Button Background Color
					 */
					array(
						'name'    => $id . 'button-bg-color',
						'default' => smarttoolz_get_option( $id . 'button-bg-color' ),
						'type'    => 'sub-control',
						'parent'  => SMARTTOOLZ_THEME_SETTINGS . '[' . $id . 'theme-button-bg-color-group]',
						'section' => 'section-buttons',
						'control' => 'ast-color',
						'title'   => __( 'Normal', 'smarttoolz' ),
					),

					/**
					 * Option: Button Background Hover Color
					 */
					array(
						'name'     => $id . 'button-bg-h-color',
						'default'  => smarttoolz_get_option( $id . 'button-bg-h-color' ),
						'type'     => 'sub-control',
						'parent'   => SMARTTOOLZ_THEME_SETTINGS . '[' . $id . 'theme-button-bg-color-group]',
						'section'  => 'section-buttons',
						'control'  => 'ast-color',
						'title'    => __( 'Hover', 'smarttoolz' ),
						'priority' => 40,
					),

					/**
					 * Option: Theme Button Padding
					 */
					array(
						'name'              => SMARTTOOLZ_THEME_SETTINGS . '[' . $id . 'theme-button-padding]',
						'default'           => smarttoolz_get_option( $id . 'theme-button-padding' ),
						'type'              => 'control',
						'control'           => 'ast-responsive-spacing',
						'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
						'section'           => 'section-buttons',
						'title'             => __( 'Padding', 'smarttoolz' ),
						'linked_choices'    => true,
						'transport'         => 'postMessage',
						'unit_choices'      => array( 'px', 'em', '%' ),
						'choices'           => array(
							'top'    => __( 'Top', 'smarttoolz' ),
							'right'  => __( 'Right', 'smarttoolz' ),
							'bottom' => __( 'Bottom', 'smarttoolz' ),
							'left'   => __( 'Left', 'smarttoolz' ),
						),
						'priority'          => 19,
						'connected'         => false,
					),

					/**
					 * Option: Global Button Border Size
					 */
					array(
						'type'           => 'control',
						'section'        => 'section-buttons',
						'control'        => 'ast-border',
						'name'           => SMARTTOOLZ_THEME_SETTINGS . '[' . $id . 'theme-button-border-group-border-size]',
						'transport'      => 'postMessage',
						'linked_choices' => true,
						'suffix'         => 'px',
						'priority'       => 19,
						'default'        => smarttoolz_get_option( $id . 'theme-button-border-group-border-size' ),
						'title'          => __( 'Border Width', 'smarttoolz' ),
						'choices'        => array(
							'top'    => __( 'Top', 'smarttoolz' ),
							'right'  => __( 'Right', 'smarttoolz' ),
							'bottom' => __( 'Bottom', 'smarttoolz' ),
							'left'   => __( 'Left', 'smarttoolz' ),
						),
						'divider'        => array( 'ast_class' => 'ast-top-divider ast-bottom-divider' ),
					),

					/**
					 * Option: Global Button Radius Fields
					 */
					array(
						'name'              => SMARTTOOLZ_THEME_SETTINGS . '[' . $id . 'button-radius-fields]',
						'default'           => smarttoolz_get_option( $id . 'button-radius-fields' ),
						'type'              => 'control',
						'control'           => 'ast-responsive-spacing',
						'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
						'section'           => 'section-buttons',
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
						'priority'          => 19,
						'connected'         => false,
					),
				);

				$_configs = array_merge( $_configs, $_tab_configs );
			}

			// Secondary tab.
			$_configs[] = array(
				'name'        => 'section-secondary-ast-context-tabs',
				'section'     => 'section-buttons',
				'type'        => 'control',
				'control'     => 'ast-builder-header-control',
				'priority'    => 0,
				'description' => '',
			);

			// Only add outline presets to secondary button presets.
			$secondary_btn_preset_index = 13;

			$config_count = count( $_configs );
			// Add context & priority dynamically to secondary tab options.
			for ( $index = $secondary_btn_preset_index, $priority = 0; $index < $config_count - 1; $index++ ) {
				$_configs[ $index ]['context']  = SmartToolz_Builder_Helper::$design_tab;
				$_configs[ $index ]['priority'] = ++$priority;
			}

			if ( false === SmartToolz_Builder_Helper::$is_header_footer_builder_active ) {

				$_trans_config = array(
					/**
					 * Option: Transparent Header Button Colors Divider
					 */
					array(
						'name'     => SMARTTOOLZ_THEME_SETTINGS . '[transparent-header-button-color-divider]',
						'type'     => 'control',
						'control'  => 'ast-heading',
						'section'  => 'section-transparent-header',
						'title'    => __( 'Header Button', 'smarttoolz' ),
						'settings' => array(),
						'priority' => 40,
						'context'  => array(
							SmartToolz_Builder_Helper::$general_tab_config,
							array(
								'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-main-rt-section-button-style]',
								'operator' => '===',
								'value'    => 'custom-button',
							),
						),
					),
					/**
					 * Group: Transparent Header Button Colors Group
					 */
					array(
						'name'      => SMARTTOOLZ_THEME_SETTINGS . '[transparent-header-button-color-group]',
						'default'   => smarttoolz_get_option( 'transparent-header-button-color-group' ),
						'type'      => 'control',
						'control'   => 'ast-settings-group',
						'title'     => __( 'Colors', 'smarttoolz' ),
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 40,
						'context'   => array(
							SmartToolz_Builder_Helper::$general_tab_config,
							array(
								'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-main-rt-section-button-style]',
								'operator' => '===',
								'value'    => 'custom-button',
							),
						),
					),
					/**
					 * Group: Transparent Header Button Border Group
					 */
					array(
						'name'      => SMARTTOOLZ_THEME_SETTINGS . '[transparent-header-button-border-group]',
						'default'   => smarttoolz_get_option( 'transparent-header-button-border-group' ),
						'type'      => 'control',
						'control'   => 'ast-settings-group',
						'title'     => __( 'Border', 'smarttoolz' ),
						'section'   => 'section-transparent-header',
						'transport' => 'postMessage',
						'priority'  => 40,
						'context'   => array(
							SmartToolz_Builder_Helper::$general_tab_config,
							array(
								'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-main-rt-section-button-style]',
								'operator' => '===',
								'value'    => 'custom-button',
							),
						),
					),

					/**
					 * Option: Button Text Color
					 */
					array(
						'name'              => 'header-main-rt-trans-section-button-text-color',
						'transport'         => 'postMessage',
						'default'           => smarttoolz_get_option( 'header-main-rt-trans-section-button-text-color' ),
						'type'              => 'sub-control',
						'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[transparent-header-button-color-group]',
						'section'           => 'section-transparent-header',
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
						'name'              => 'header-main-rt-trans-section-button-text-h-color',
						'default'           => smarttoolz_get_option( 'header-main-rt-trans-section-button-text-h-color' ),
						'transport'         => 'postMessage',
						'type'              => 'sub-control',
						'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[transparent-header-button-color-group]',
						'section'           => 'section-transparent-header',
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
						'name'              => 'header-main-rt-trans-section-button-back-color',
						'default'           => smarttoolz_get_option( 'header-main-rt-trans-section-button-back-color' ),
						'transport'         => 'postMessage',
						'type'              => 'sub-control',
						'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[transparent-header-button-color-group]',
						'section'           => 'section-transparent-header',
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
						'name'              => 'header-main-rt-trans-section-button-back-h-color',
						'default'           => smarttoolz_get_option( 'header-main-rt-trans-section-button-back-h-color' ),
						'transport'         => 'postMessage',
						'type'              => 'sub-control',
						'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[transparent-header-button-color-group]',
						'section'           => 'section-transparent-header',
						'tab'               => __( 'Hover', 'smarttoolz' ),
						'control'           => 'ast-color',
						'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
						'priority'          => 10,
						'title'             => __( 'Background Color', 'smarttoolz' ),
					),

					// Option: Custom Menu Button Border.
					array(
						'type'              => 'control',
						'control'           => 'ast-responsive-spacing',
						'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
						'name'              => SMARTTOOLZ_THEME_SETTINGS . '[header-main-rt-trans-section-button-padding]',
						'section'           => 'section-transparent-header',
						'transport'         => 'postMessage',
						'linked_choices'    => true,
						'priority'          => 40,
						'context'           => array(
							SmartToolz_Builder_Helper::$general_tab_config,
							array(
								'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-main-rt-section-button-style]',
								'operator' => '===',
								'value'    => 'custom-button',
							),
						),
						'default'           => smarttoolz_get_option( 'header-main-rt-trans-section-button-padding' ),
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
						'parent'         => SMARTTOOLZ_THEME_SETTINGS . '[transparent-header-button-border-group]',
						'section'        => 'section-transparent-header',
						'control'        => 'ast-border',
						'name'           => 'header-main-rt-trans-section-button-border-size',
						'transport'      => 'postMessage',
						'linked_choices' => true,
						'priority'       => 10,
						'default'        => smarttoolz_get_option( 'header-main-rt-trans-section-button-border-size' ),
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
						'name'              => 'header-main-rt-trans-section-button-border-color',
						'default'           => smarttoolz_get_option( 'header-main-rt-trans-section-button-border-color' ),
						'type'              => 'sub-control',
						'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[transparent-header-button-border-group]',
						'section'           => 'section-transparent-header',
						'transport'         => 'postMessage',
						'control'           => 'ast-color',
						'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
						'priority'          => 12,
						'title'             => __( 'Color', 'smarttoolz' ),
					),

					/**
					 * Option: Button Border Hover Color
					 */
					array(
						'name'              => 'header-main-rt-trans-section-button-border-h-color',
						'default'           => smarttoolz_get_option( 'header-main-rt-trans-section-button-border-h-color' ),
						'transport'         => 'postMessage',
						'type'              => 'sub-control',
						'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[transparent-header-button-border-group]',
						'control'           => 'ast-color',
						'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
						'priority'          => 14,
						'title'             => __( 'Hover Color', 'smarttoolz' ),
					),

					/**
					 * Option: Button Border Radius
					 */
					array(
						'name'        => 'header-main-rt-trans-section-button-border-radius',
						'default'     => smarttoolz_get_option( 'header-main-rt-trans-section-button-border-radius' ),
						'type'        => 'sub-control',
						'parent'      => SMARTTOOLZ_THEME_SETTINGS . '[transparent-header-button-border-group]',
						'section'     => 'section-transparent-header',
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
				$_configs = array_merge( $_configs, $_trans_config );

			}

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new SmartToolz_Customizer_Button_Configs();
