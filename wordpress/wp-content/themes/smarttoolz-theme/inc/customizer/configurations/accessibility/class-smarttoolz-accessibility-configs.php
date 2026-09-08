<?php
/**
 * Accessibility Options for SmartToolz Theme.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 4.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register SmartToolz Accessibility Configurations.
 */
class SmartToolz_Accessibility_Configs extends SmartToolz_Customizer_Config_Base {
	/**
	 * Register SmartToolz Accessibility Configurations.
	 *
	 * @param Array                $configurations SmartToolz Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since 4.1.0
	 * @return Array SmartToolz Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {

		$_configs = array(

			/**
			 * Option: Toggle for accessibility.
			 */
			array(
				'name'     => SMARTTOOLZ_THEME_SETTINGS . '[site-accessibility-toggle]',
				'default'  => smarttoolz_get_option( 'site-accessibility-toggle' ),
				'type'     => 'control',
				'control'  => 'ast-toggle-control',
				'title'    => __( 'Site Accessibility', 'smarttoolz' ),
				'section'  => 'section-accessibility',
				'priority' => 1,
				'divider'  => array( 'ast_class' => 'ast-section-spacing' ),
			),

			/**
			 * Option: Highlight type.
			 */
			array(
				'name'     => SMARTTOOLZ_THEME_SETTINGS . '[site-accessibility-highlight-type]',
				'default'  => smarttoolz_get_option( 'site-accessibility-highlight-type' ),
				'type'     => 'control',
				'control'  => 'ast-radio-icon',
				'priority' => 1,
				'title'    => __( 'Global Highlight', 'smarttoolz' ),
				'section'  => 'section-accessibility',
				'choices'  => array(
					'dotted' => array(
						'label' => __( 'Dotted', 'smarttoolz' ),
						'path'  => 'ellipsis',
					),
					'solid'  => array(
						'label' => __( 'Solid', 'smarttoolz' ),
						'path'  => 'minus',
					),
				),
				'divider'  => array( 'ast_class' => 'ast-top-divider' ),
				'context'  => array(
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[site-accessibility-toggle]',
						'operator' => '===',
						'value'    => true,
					),
				),
			),

			/**
			 * Option: Highlight color.
			 */
			array(
				'name'     => SMARTTOOLZ_THEME_SETTINGS . '[site-accessibility-highlight-color]',
				'default'  => smarttoolz_get_option( 'site-accessibility-highlight-color' ),
				'type'     => 'control',
				'control'  => 'ast-color',
				'priority' => 1,
				'title'    => __( 'Color', 'smarttoolz' ),
				'section'  => 'section-accessibility',
				'context'  => array(
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[site-accessibility-toggle]',
						'operator' => '===',
						'value'    => true,
					),
				),
			),

			/**
			 * Option: Highlight type.
			 */
			array(
				'name'     => SMARTTOOLZ_THEME_SETTINGS . '[site-accessibility-highlight-input-type]',
				'default'  => smarttoolz_get_option( 'site-accessibility-highlight-input-type' ),
				'type'     => 'control',
				'control'  => 'ast-radio-icon',
				'priority' => 1,
				'title'    => __( 'Input Highlight', 'smarttoolz' ),
				'section'  => 'section-accessibility',
				'choices'  => array(
					'disable' => array(
						'label' => __( 'Disable', 'smarttoolz' ),
						'path'  => 'remove',
					),
					'dotted'  => array(
						'label' => __( 'Dotted', 'smarttoolz' ),
						'path'  => 'ellipsis',
					),
					'solid'   => array(
						'label' => __( 'Solid', 'smarttoolz' ),
						'path'  => 'minus',
					),
				),
				'divider'  => array( 'ast_class' => 'ast-top-divider' ),
				'context'  => array(
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[site-accessibility-toggle]',
						'operator' => '===',
						'value'    => true,
					),
				),
			),

			/**
			 * Option: Highlight color.
			 */
			array(
				'name'     => SMARTTOOLZ_THEME_SETTINGS . '[site-accessibility-highlight-input-color]',
				'default'  => smarttoolz_get_option( 'site-accessibility-highlight-input-color' ),
				'type'     => 'control',
				'control'  => 'ast-color',
				'priority' => 1,
				'title'    => __( 'Color', 'smarttoolz' ),
				'section'  => 'section-accessibility',
				'context'  => array(
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[site-accessibility-toggle]',
						'operator' => '===',
						'value'    => true,
					),
				),
			),
		);

		return array_merge( $configurations, $_configs );
	}
}

new SmartToolz_Accessibility_Configs();
