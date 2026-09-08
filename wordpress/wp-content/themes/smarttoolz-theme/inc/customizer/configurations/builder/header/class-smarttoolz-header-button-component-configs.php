<?php
/**
 * [Header] options for smarttoolz theme.
 *
 * @package     SmartToolz Header Footer Builder
 * @link        https://www.brainstormforce.com
 * @since       3.0.0
 */

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'SmartToolz_Customizer_Config_Base' ) ) {

	/**
	 * Register below header Configurations.
	 */
	class SmartToolz_Header_Button_Component_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register Button control for Header/Footer Customizer Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 3.0.0
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {
			return smarttoolz_header_button_configuration( $configurations );
		}
	}

	new SmartToolz_Header_Button_Component_Configs();
}
