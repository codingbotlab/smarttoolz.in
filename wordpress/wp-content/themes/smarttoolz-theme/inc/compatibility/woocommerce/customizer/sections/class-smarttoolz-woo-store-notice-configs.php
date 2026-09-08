<?php
/**
 * Store Notice options for SmartToolz Theme.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 3.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer WooCommerece store notice - customizer config initial setup.
 */
class SmartToolz_Woo_Store_Notice_Configs extends SmartToolz_Customizer_Config_Base {
	/**
	 * Register SmartToolz-WooCommerce Shop Cart Layout Customizer Configurations.
	 *
	 * @param Array                $configurations SmartToolz Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since 3.9.0
	 * @return Array SmartToolz Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {

		$_configs = array(

			/**
			 * Option: Transparent Header Builder - HTML Elements configs.
			 */
			array(
				'name'      => SMARTTOOLZ_THEME_SETTINGS . '[woo-store-notice-colors-group]',
				'default'   => smarttoolz_get_option( 'woo-store-notice-colors-group' ),
				'type'      => 'control',
				'control'   => 'ast-color-group',
				'title'     => __( 'Color', 'smarttoolz' ),
				'section'   => 'woocommerce_store_notice',
				'transport' => 'postMessage',
				'priority'  => 50,
				'context'   => array(
					array(
						'setting'  => 'woocommerce_demo_store',
						'operator' => '==',
						'value'    => true,
					),
				),
				'divider'   => array( 'ast_class' => 'ast-top-divider ast-bottom-divider' ),
			),

			// Option: Text Color.
			array(
				'name'              => 'store-notice-text-color',
				'default'           => smarttoolz_get_option( 'store-notice-text-color' ),
				'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[woo-store-notice-colors-group]',
				'type'              => 'sub-control',
				'control'           => 'ast-color',
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
				'section'           => 'woocommerce_store_notice',
				'transport'         => 'postMessage',
				'priority'          => 1,
				'title'             => __( 'Text', 'smarttoolz' ),
			),

			// Option: Background Color.
			array(
				'name'              => 'store-notice-background-color',
				'default'           => smarttoolz_get_option( 'store-notice-background-color' ),
				'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[woo-store-notice-colors-group]',
				'type'              => 'sub-control',
				'control'           => 'ast-color',
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
				'section'           => 'woocommerce_store_notice',
				'transport'         => 'postMessage',
				'priority'          => 2,
				'title'             => __( 'Background', 'smarttoolz' ),
			),

			/**
			 * Option: Notice Position
			 */
			array(
				'name'       => SMARTTOOLZ_THEME_SETTINGS . '[store-notice-position]',
				'default'    => smarttoolz_get_option( 'store-notice-position' ),
				'type'       => 'control',
				'control'    => 'ast-selector',
				'section'    => 'woocommerce_store_notice',
				'transport'  => 'postMessage',
				'priority'   => 60,
				'title'      => __( 'Notice Position', 'smarttoolz' ),
				'choices'    => array(
					'hang-over-top' => __( 'Hang Over Top', 'smarttoolz' ),
					'top'           => __( 'Top', 'smarttoolz' ),
					'bottom'        => __( 'Bottom', 'smarttoolz' ),
				),
				'context'    => array(
					array(
						'setting'  => 'woocommerce_demo_store',
						'operator' => '==',
						'value'    => true,
					),
				),
				'renderAs'   => 'text',
				'responsive' => false,
			),
		);

		return array_merge( $configurations, $_configs );
	}
}

new SmartToolz_Woo_Store_Notice_Configs();
