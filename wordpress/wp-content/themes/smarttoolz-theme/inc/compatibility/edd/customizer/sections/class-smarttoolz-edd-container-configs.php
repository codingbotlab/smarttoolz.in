<?php
/**
 * Easy Digital Downloads Container Options for SmartToolz theme.
 *
 * @package     SmartToolz
 * @link        https://www.brainstormforce.com
 * @since       SmartToolz 1.5.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SmartToolz_Edd_Container_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class SmartToolz_Edd_Container_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register SmartToolz-Easy Digital Downloads Shop Container Settings.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.5.5
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Revamped Container Layout.
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[edd-ast-content-layout]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'           => 'section-edd-general',
					'default'           => smarttoolz_get_option( 'edd-ast-content-layout' ),
					'priority'          => 5,
					'title'             => __( 'Container Layout', 'smarttoolz' ),
					'choices'           => array(
						'default'                => array(
							'label' => __( 'Default', 'smarttoolz' ),
							'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'layout-default', false ) : '',
						),
						'normal-width-container' => array(
							'label' => __( 'Normal', 'smarttoolz' ),
							'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'normal-width-container', false ) : '',
						),
						'full-width-container'   => array(
							'label' => __( 'Full Width', 'smarttoolz' ),
							'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'full-width-container', false ) : '',
						),
					),
					'divider'           => array( 'ast_class' => 'ast-bottom-spacing ast-bottom-divider' ),
				),

				/**
				 * Option: Content Style Option.
				 */
				array(
					'name'        => SMARTTOOLZ_THEME_SETTINGS . '[edd-content-style]',
					'type'        => 'control',
					'control'     => 'ast-selector',
					'section'     => 'section-edd-general',
					'default'     => smarttoolz_get_option( 'edd-content-style', 'default' ),
					'description' => __( 'Container style will apply only when layout is set to either normal or narrow.', 'smarttoolz' ),
					'priority'    => 5,
					'title'       => __( 'Container Style', 'smarttoolz' ),
					'choices'     => array(
						'default' => __( 'Default', 'smarttoolz' ),
						'unboxed' => __( 'Unboxed', 'smarttoolz' ),
						'boxed'   => __( 'Boxed', 'smarttoolz' ),
					),
					'renderAs'    => 'text',
					'responsive'  => false,
				),
			);

			// Upgrade nudge if SmartToolz Pro is not activated.
			if ( smarttoolz_showcase_upgrade_notices() ) {
				$_configs[] = SmartToolz_Customizer_Register_Edd_Section::get_upgrade_nudge_config( 'ast-edd-general-pro-items', 'section-edd-general' );
			}

			return array_merge( $configurations, $_configs );
		}
	}
}

new SmartToolz_Edd_Container_Configs();
