<?php
/**
 * Register customizer panels & sections.
 *
 * @package     SmartToolz\
 * @link        https://www.brainstormforce.com
 * @since       SmartToolz 1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'SmartToolz_Liferlms_Section_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class SmartToolz_Liferlms_Section_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register LearnDash Container settings.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				array(
					'name'     => 'section-lifterlms',
					'type'     => 'section',
					'priority' => 65,
					'title'    => __( 'LifterLMS', 'smarttoolz' ),
				),

				/**
				 * General Section
				 */
				array(
					'name'     => 'section-lifterlms-general',
					'type'     => 'section',
					'title'    => __( 'General', 'smarttoolz' ),
					'section'  => 'section-lifterlms',
					'priority' => 0,
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new SmartToolz_Liferlms_Section_Configs();
