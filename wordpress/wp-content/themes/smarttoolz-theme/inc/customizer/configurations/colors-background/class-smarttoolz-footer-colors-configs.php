<?php
/**
 * Styling Options for SmartToolz Theme.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       1.4.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SmartToolz_Footer_Colors_Configs' ) ) {

	/**
	 * Register Footer Color Configurations.
	 */
	class SmartToolz_Footer_Colors_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register Footer Color Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {
			$_configs = array(

				/**
				 * Option: Color
				 */
				array(
					'name'     => 'footer-color',
					'type'     => 'sub-control',
					'priority' => 5,
					'parent'   => SMARTTOOLZ_THEME_SETTINGS . '[footer-bar-content-group]',
					'section'  => 'section-footer-small',
					'control'  => 'ast-color',
					'title'    => __( 'Text Color', 'smarttoolz' ),
					'default'  => smarttoolz_get_option( 'footer-color' ),
				),

				/**
				 * Option: Link Color
				 */
				array(
					'name'     => 'footer-link-color',
					'type'     => 'sub-control',
					'priority' => 6,
					'parent'   => SMARTTOOLZ_THEME_SETTINGS . '[footer-bar-link-color-group]',
					'section'  => 'section-footer-small',
					'control'  => 'ast-color',
					'default'  => smarttoolz_get_option( 'footer-link-color' ),
					'title'    => __( 'Normal', 'smarttoolz' ),
				),

				/**
				 * Option: Link Hover Color
				 */
				array(
					'name'     => 'footer-link-h-color',
					'type'     => 'sub-control',
					'priority' => 5,
					'parent'   => SMARTTOOLZ_THEME_SETTINGS . '[footer-bar-link-color-group]',
					'section'  => 'section-footer-small',
					'control'  => 'ast-color',
					'title'    => __( 'Hover', 'smarttoolz' ),
					'default'  => smarttoolz_get_option( 'section-footer-small' ),
				),

				/**
				 * Option: Footer Background
				 */
				array(
					'name'              => 'footer-bg-obj',
					'type'              => 'sub-control',
					'priority'          => 7,
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[footer-bar-background-group]',
					'section'           => 'section-footer-small',
					'transport'         => 'postMessage',
					'control'           => 'ast-background',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_background_obj' ),
					'default'           => smarttoolz_get_option( 'footer-bg-obj' ),
					'label'             => __( 'Background', 'smarttoolz' ),
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new SmartToolz_Footer_Colors_Configs();
