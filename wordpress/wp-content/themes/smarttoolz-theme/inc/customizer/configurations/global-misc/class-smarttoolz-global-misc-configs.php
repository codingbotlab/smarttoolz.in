<?php
/**
 * Global Misc Options for SmartToolz Theme.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz  4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register SmartToolz Global Misc Configurations.
 */
class SmartToolz_Global_Misc_Configs extends SmartToolz_Customizer_Config_Base {
	/**
	 * Register SmartToolz Global Misc  Configurations.
	 *
	 * @param Array                $configurations SmartToolz Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since 4.0.0
	 * @return Array SmartToolz Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {

		$_configs = array(

			/**
			 * Option: Scroll to id.
			 */
			array(
				'name'     => SMARTTOOLZ_THEME_SETTINGS . '[enable-scroll-to-id]',
				'default'  => smarttoolz_get_option( 'enable-scroll-to-id' ),
				'type'     => 'control',
				'control'  => 'ast-toggle-control',
				'title'    => __( 'Enable Smooth Scroll to ID', 'smarttoolz' ),
				'section'  => 'section-global-misc',
				'divider'  => array( 'ast_class' => 'ast-section-spacing' ),
				'priority' => 10,
			),
		);

		return array_merge( $configurations, $_configs );
	}
}

new SmartToolz_Global_Misc_Configs();
