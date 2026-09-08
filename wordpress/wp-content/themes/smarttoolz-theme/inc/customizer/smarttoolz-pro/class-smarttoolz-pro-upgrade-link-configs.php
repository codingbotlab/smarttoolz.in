<?php
/**
 * Register customizer SmartToolz Pro Section.
 *
 * @package   SmartToolz
 * @link      https://wpsmarttoolz.com/
 * @since     SmartToolz 1.0.10
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'SmartToolz_Pro_Upgrade_Link_Configs' ) ) {

	/**
	 * Register Button Customizer Configurations.
	 */
	class SmartToolz_Pro_Upgrade_Link_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register Button Customizer Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(
				array(
					'name'             => 'smarttoolz-pro',
					'type'             => 'section',
					'ast_type'         => 'smarttoolz-pro',
					'title'            => esc_html__( 'More Options Available in Toolkit', 'smarttoolz' ),
					'pro_url'          => smarttoolz_get_upgrade_url( 'pricing' ),
					'priority'         => 1,
					'section_callback' => 'SmartToolz_Pro_Customizer',
				),

				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[smarttoolz-pro-section-notice]',
					'type'      => 'control',
					'transport' => 'postMessage',
					'control'   => 'ast-hidden',
					'section'   => 'smarttoolz-pro',
					'priority'  => 0,
				),

			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new SmartToolz_Pro_Upgrade_Link_Configs();
