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

if ( ! class_exists( 'SmartToolz_Adv_Footer_Colors_Configs' ) ) {

	/**
	 * Register Advanced Footer Color Customizer Configurations.
	 */
	class SmartToolz_Advanced_Footer_Colors_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register Advanced Footer Color Customizer Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {
			$_configs = array(

				/**
				 * Option: Footer Bar Content Group
				 */
				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[footer-widget-background-group]',
					'default'   => smarttoolz_get_option( 'footer-widget-background-group' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Background Color', 'smarttoolz' ),
					'section'   => 'section-footer-adv',
					'transport' => 'postMessage',
					'divider'   => array( 'ast_class' => 'ast-bottom-divider' ),
					'priority'  => 47,
					'context'   => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[footer-adv]',
							'operator' => '!=',
							'value'    => 'disabled',
						),

					),
				),

				/**
				 * Option: Footer Bar Content Group
				 */
				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[footer-widget-content-group]',
					'default'   => smarttoolz_get_option( 'footer-widget-content-group' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Content Colors', 'smarttoolz' ),
					'section'   => 'section-footer-adv',
					'transport' => 'postMessage',
					'priority'  => 48,
					'context'   => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[footer-adv]',
							'operator' => '!=',
							'value'    => 'disabled',
						),
					),
				),

				/**
				 * Option: Footer Bar Content Group
				 */
				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[footer-widget-link-color-group]',
					'default'   => smarttoolz_get_option( 'footer-widget-link-color-group' ),
					'type'      => 'control',
					'control'   => 'ast-color-group',
					'title'     => __( 'Link Color', 'smarttoolz' ),
					'section'   => 'section-footer-adv',
					'transport' => 'postMessage',
					'priority'  => 48,
					'divider'   => array( 'ast_class' => 'ast-bottom-divider' ),
					'context'   => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[footer-adv]',
							'operator' => '!=',
							'value'    => 'disabled',
						),

					),
				),

				/**
				 * Option: Widget Title Color
				 */
				array(
					'name'    => 'footer-adv-wgt-title-color',
					'type'    => 'sub-control',
					'parent'  => SMARTTOOLZ_THEME_SETTINGS . '[footer-widget-content-group]',
					'section' => 'section-footer-adv',
					'control' => 'ast-color',
					'title'   => __( 'Title Color', 'smarttoolz' ),
					'default' => smarttoolz_get_option( 'footer-adv-wgt-title-color' ),
				),

				/**
				 * Option: Text Color
				 */
				array(
					'name'    => 'footer-adv-text-color',
					'type'    => 'sub-control',
					'parent'  => SMARTTOOLZ_THEME_SETTINGS . '[footer-widget-content-group]',
					'section' => 'section-footer-adv',
					'control' => 'ast-color',
					'title'   => __( 'Text Color', 'smarttoolz' ),
					'default' => smarttoolz_get_option( 'footer-adv-text-color' ),
				),

				/**
				 * Option: Link Color
				 */
				array(
					'name'    => 'footer-adv-link-color',
					'type'    => 'sub-control',
					'parent'  => SMARTTOOLZ_THEME_SETTINGS . '[footer-widget-link-color-group]',
					'section' => 'section-footer-adv',
					'control' => 'ast-color',
					'title'   => __( 'Normal', 'smarttoolz' ),
					'default' => smarttoolz_get_option( 'footer-adv-link-color' ),
				),

				/**
				 * Option: Link Hover Color
				 */
				array(
					'name'    => 'footer-adv-link-h-color',
					'type'    => 'sub-control',
					'parent'  => SMARTTOOLZ_THEME_SETTINGS . '[footer-widget-link-color-group]',
					'section' => 'section-footer-adv',
					'control' => 'ast-color',
					'title'   => __( 'Hover', 'smarttoolz' ),
					'default' => smarttoolz_get_option( 'footer-adv-link-h-color' ),
				),

				/**
				 * Option: Footer widget Background
				 */
				array(
					'name'    => 'footer-adv-bg-obj',
					'type'    => 'sub-control',
					'parent'  => SMARTTOOLZ_THEME_SETTINGS . '[footer-widget-background-group]',
					'section' => 'section-footer-adv',
					'control' => 'ast-background',
					'default' => smarttoolz_get_option( 'footer-adv-bg-obj' ),
					'label'   => __( 'Background', 'smarttoolz' ),
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new SmartToolz_Advanced_Footer_Colors_Configs();
