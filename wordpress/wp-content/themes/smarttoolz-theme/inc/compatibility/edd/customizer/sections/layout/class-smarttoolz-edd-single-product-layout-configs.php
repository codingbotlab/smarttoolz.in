<?php
/**
 * Easy Digital Downloads Options for SmartToolz Theme.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 1.5.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SmartToolz_Edd_Single_Product_Layout_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class SmartToolz_Edd_Single_Product_Layout_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register SmartToolz-Easy Digital Downloads Shop Cart Layout Customizer Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.5.5
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Cart upsells
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[disable-edd-single-product-nav]',
					'section'  => 'section-edd-single',
					'type'     => 'control',
					'control'  => 'ast-toggle-control',
					'default'  => smarttoolz_get_option( 'disable-edd-single-product-nav' ),
					'title'    => __( 'Disable Product Navigation', 'smarttoolz' ),
					'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
					'priority' => 10,
				),
			);

			// Upgrade nudge if SmartToolz Pro is not activated.
			if ( smarttoolz_showcase_upgrade_notices() ) {
				$_configs[] = SmartToolz_Customizer_Register_Edd_Section::get_upgrade_nudge_config( 'ast-edd-single-pro-items', 'section-edd-single' );
			}

			return array_merge( $configurations, $_configs );
		}
	}
}

new SmartToolz_Edd_Single_Product_Layout_Configs();
