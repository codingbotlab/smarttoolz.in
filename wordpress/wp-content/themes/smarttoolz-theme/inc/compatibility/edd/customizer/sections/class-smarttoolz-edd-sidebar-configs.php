<?php
/**
 * Easy Digital Downloads Sidebar Options for our theme.
 *
 * @package     SmartToolz
 * @link        https://www.brainstormforce.com
 * @since       SmartToolz 1.5.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SmartToolz_Edd_Sidebar_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class SmartToolz_Edd_Sidebar_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register SmartToolz Easy Digital Downloads Sidebar Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.5.5
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: General Sidebar Layout.
				 */

				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[edd-general-sidebar-layout]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'           => 'section-edd-general',
					'default'           => smarttoolz_get_option( 'edd-general-sidebar-layout' ),
					'priority'          => 6,
					'title'             => __( 'Sidebar Layout', 'smarttoolz' ),
					'choices'           => array(
						'default'       => array(
							'label' => __( 'Default', 'smarttoolz' ),
							'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'layout-default', false ) : '',
						),
						'no-sidebar'    => array(
							'label' => __( 'No Sidebar', 'smarttoolz' ),
							'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'no-sidebar', false ) : '',
						),
						'left-sidebar'  => array(
							'label' => __( 'Left Sidebar', 'smarttoolz' ),
							'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'left-sidebar', false ) : '',
						),
						'right-sidebar' => array(
							'label' => __( 'Right Sidebar', 'smarttoolz' ),
							'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'right-sidebar', false ) : '',
						),
					),
					'description'       => __( 'Sidebar will only apply when container layout is set to normal.', 'smarttoolz' ),
					'divider'           => array( 'ast_class' => 'ast-top-section-divider' ),
				),

				/**
				 * Option: EDD Sidebar Style.
				 */
				array(
					'name'       => SMARTTOOLZ_THEME_SETTINGS . '[edd-sidebar-style]',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => 'section-edd-general',
					'default'    => smarttoolz_get_option( 'edd-sidebar-style', 'default' ),
					'priority'   => 6,
					'title'      => __( 'Sidebar Style', 'smarttoolz' ),
					'choices'    => array(
						'default' => __( 'Default', 'smarttoolz' ),
						'unboxed' => __( 'Unboxed', 'smarttoolz' ),
						'boxed'   => __( 'Boxed', 'smarttoolz' ),
					),
					'responsive' => false,
					'renderAs'   => 'text',
					'divider'    => array( 'ast_class' => 'ast-top-divider ast-top-spacing' ),
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new SmartToolz_Edd_Sidebar_Configs();
