<?php
/**
 * LifterLMS General Options for our theme.
 *
 * @package     SmartToolz
 * @link        https://www.brainstormforce.com
 * @since       1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SmartToolz_Learndash_General_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class SmartToolz_Learndash_General_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register LearnDash General Layout settings.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Display Serial Number
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[learndash-lesson-serial-number]',
					'section'  => 'section-leandash-general',
					'type'     => 'control',
					'control'  => 'ast-toggle-control',
					'default'  => smarttoolz_get_option( 'learndash-lesson-serial-number' ),
					'title'    => __( 'Display Serial Number', 'smarttoolz' ),
					'priority' => 25,
					'divider'  => array(
						'ast_class' => 'ast-top-divider',
						'ast_title' => __( 'Course Content Table', 'smarttoolz' ),
					),
				),

				/**
				 * Option: Differentiate Rows
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[learndash-differentiate-rows]',
					'default'  => smarttoolz_get_option( 'learndash-differentiate-rows' ),
					'type'     => 'control',
					'control'  => 'ast-toggle-control',
					'section'  => 'section-leandash-general',
					'title'    => __( 'Differentiate Rows', 'smarttoolz' ),
					'priority' => 30,
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new SmartToolz_Learndash_General_Configs();
