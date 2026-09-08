<?php
/**
 * Register customizer panels & sections.
 *
 * @package     SmartToolz
 * @link        https://www.brainstormforce.com
 * @since       1.2.0
 * @since       1.4.6 Chnaged to using SmartToolz_Customizer API
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'SmartToolz_Customizer_Register_Learndash_Section' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class SmartToolz_Customizer_Register_Learndash_Section extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register Panels and Sections for Customizer.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.2.0
		 * @since 1.4.6 Chnaged to using SmartToolz_Customizer API
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$configs = array(
				array(
					'type'     => 'section',
					'name'     => 'section-learndash',
					'priority' => 65,
					'title'    => __( 'LearnDash', 'smarttoolz' ),

				),

				array(
					'name'     => 'section-leandash-general',
					'title'    => __( 'General', 'smarttoolz' ),
					'type'     => 'section',
					'section'  => 'section-learndash',
					'priority' => 10,
				),

			);

			return array_merge( $configurations, $configs );
		}
	}
}

new SmartToolz_Customizer_Register_Learndash_Section();
