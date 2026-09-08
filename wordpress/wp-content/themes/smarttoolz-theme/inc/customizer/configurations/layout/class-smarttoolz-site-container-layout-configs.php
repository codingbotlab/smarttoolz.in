<?php
/**
 * General Options for SmartToolz Theme.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SmartToolz_Site_Container_Layout_Configs' ) ) {

	/**
	 * Register SmartToolz Site Container Layout Customizer Configurations.
	 */
	class SmartToolz_Site_Container_Layout_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register SmartToolz Site Container Layout Customizer Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_section = 'section-colors-background';

			if ( class_exists( 'SmartToolz_Ext_Extension' ) && SmartToolz_Ext_Extension::is_active( 'colors-and-background' ) && ! smarttoolz_has_gcp_typo_preset_compatibility() ) {
				$_section = 'section-colors-body';
			}

			$_configs = array(

				/**
				 * Option: Global Revamped Container Layouts.
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[ast-site-content-layout]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'           => 'section-container-layout',
					'default'           => smarttoolz_get_option( 'ast-site-content-layout', 'normal-width-container' ),
					'priority'          => 9,
					'title'             => __( 'Container Layout', 'smarttoolz' ),
					'transport'         => 'refresh',
					'choices'           => array(
						'normal-width-container' => array(
							'label' => __( 'Normal', 'smarttoolz' ),
							'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'normal-width-container', false ) : '',
						),
						'narrow-width-container' => array(
							'label' => __( 'Narrow', 'smarttoolz' ),
							'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'narrow-width-container', false ) : '',
						),
						'full-width-container'   => array(
							'label' => __( 'Full Width', 'smarttoolz' ),
							'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'full-width-container', false ) : '',
						),
					),
					'divider'           => array( 'ast_class' => 'ast-section-spacing ast-bottom-divider' ),
				),

				/**
				 * Option: Global Content Style.
				 */
				array(
					'name'        => SMARTTOOLZ_THEME_SETTINGS . '[site-content-style]',
					'type'        => 'control',
					'control'     => 'ast-selector',
					'section'     => 'section-container-layout',
					'default'     => smarttoolz_get_option( 'site-content-style', 'boxed' ),
					'priority'    => 9,
					'description' => __( 'Container style will apply only when layout is set to either normal or narrow.', 'smarttoolz' ),
					'title'       => __( 'Container Style', 'smarttoolz' ),
					'choices'     => array(
						'unboxed' => __( 'Unboxed', 'smarttoolz' ),
						'boxed'   => __( 'Boxed', 'smarttoolz' ),
					),
					'responsive'  => false,
					'renderAs'    => 'text',
				),

				/**
				 * Option: Theme color heading
				 */
				array(
					'name'        => SMARTTOOLZ_THEME_SETTINGS . '[surface-colors-title]',
					'section'     => $_section,
					'title'       => __( 'Surface Color', 'smarttoolz' ),
					'type'        => 'control',
					'control'     => 'ast-group-title',
					'priority'    => 25,
					'responsive'  => true,
					'settings'    => array(),
					'input_attrs' => array(
						'reset_linked_controls' => array(
							'site-layout-outside-bg-obj-responsive',
							'content-bg-obj-responsive',
						),
					),
					'divider'     => array( 'ast_class' => 'ast-top-section-spacing' ),
				),

				/**
				 * Option: Body Background
				 */
				array(
					'name'        => SMARTTOOLZ_THEME_SETTINGS . '[site-layout-outside-bg-obj-responsive]',
					'type'        => 'control',
					'control'     => 'ast-responsive-background',
					'default'     => smarttoolz_get_option( 'site-layout-outside-bg-obj-responsive' ),
					'section'     => $_section,
					'transport'   => 'postMessage',
					'priority'    => 25,
					'input_attrs' => array(
						'ignore_responsive_btns' => true,
					),
					'title'       => __( 'Site Background', 'smarttoolz' ),
				),
			);

			if ( smarttoolz_has_gcp_typo_preset_compatibility() ) {

				$_configs[] = array(
					'name'        => SMARTTOOLZ_THEME_SETTINGS . '[content-bg-obj-responsive]',
					'default'     => smarttoolz_get_option( 'content-bg-obj-responsive' ),
					'type'        => 'control',
					'control'     => 'ast-responsive-background',
					'section'     => $_section,
					'title'       => __( 'Content Background', 'smarttoolz' ),
					'transport'   => 'postMessage',
					'input_attrs' => array(
						'ignore_responsive_btns' => true,
					),
					'priority'    => 25,
					'divider'     => defined( 'SMARTTOOLZ_EXT_VER' ) && SmartToolz_Ext_Extension::is_active( 'colors-and-background' ) ? array() : array(),
				);
			}

			$configurations = array_merge( $configurations, $_configs );

			// Learn More link if SmartToolz Pro is not activated.
			if ( smarttoolz_showcase_upgrade_notices() ) {
				$config = array(
					array(
						'name'     => SMARTTOOLZ_THEME_SETTINGS . '[ast-site-layout-button-link]',
						'type'     => 'control',
						'control'  => 'ast-upgrade',
						'campaign' => 'global',
						'choices'  => array(
							'one'   => array(
								'title' => __( 'Full Width layout', 'smarttoolz' ),
							),
							'two'   => array(
								'title' => __( 'Padded layout', 'smarttoolz' ),
							),
							'three' => array(
								'title' => __( 'Fluid layout', 'smarttoolz' ),
							),
							'four'  => array(
								'title' => __( 'Container spacings', 'smarttoolz' ),
							),
						),
						'section'  => 'section-container-layout',
						'default'  => '',
						'priority' => 999,
						'title'    => __( 'Use containers to their maximum potential', 'smarttoolz' ),
						'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
					),
				);

				$configurations = array_merge( $configurations, $config );
			}

			return $configurations;
		}
	}
}

new SmartToolz_Site_Container_Layout_Configs();
