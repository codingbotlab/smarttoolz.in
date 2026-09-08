<?php
/**
 * Site Layout Option for SmartToolz Theme.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SmartToolz_Site_Layout_Configs' ) ) {

	/**
	 * Register Site Layout Customizer Configurations.
	 */
	class SmartToolz_Site_Layout_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register Site Layout Customizer Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				array(
					'name'        => SMARTTOOLZ_THEME_SETTINGS . '[site-content-width]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'default'     => smarttoolz_get_option( 'site-content-width' ),
					'section'     => 'section-container-layout',
					'priority'    => 10,
					'title'       => __( 'Container Width', 'smarttoolz' ),
					'divider'     => array( 'ast_class' => 'ast-top-section-divider' ),
					'context'     => defined( 'SMARTTOOLZ_EXT_VER' ) && SmartToolz_Ext_Extension::is_active( 'site-layouts' ) ? array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[site-layout]',
							'operator' => '==',
							'value'    => 'ast-full-width-layout',
						),
					) : array(
						SmartToolz_Builder_Helper::$general_tab_config,
					),
					'suffix'      => 'px',
					'input_attrs' => array(
						'min'  => 768,
						'step' => 1,
						'max'  => 1920,
					),
				),
				array(
					'name'        => SMARTTOOLZ_THEME_SETTINGS . '[narrow-container-max-width]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'default'     => smarttoolz_get_option( 'narrow-container-max-width' ),
					'section'     => 'section-container-layout',
					'priority'    => 10,
					'title'       => __( 'Narrow Container Width', 'smarttoolz' ),
					'suffix'      => 'px',
					'divider'     => array( 'ast_class' => 'ast-top-section-spacing' ),
					'input_attrs' => array(
						'min'  => 400,
						'step' => 1,
						'max'  => 1000,
					),
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new SmartToolz_Site_Layout_Configs();
