<?php
/**
 * Admin functions - Functions that add some functionality to WordPress admin panel
 *
 * @package SmartToolz
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Register menus
 */
if ( ! function_exists( 'smarttoolz_register_menu_locations' ) ) {

	/**
	 * Register menus
	 *
	 * @since 1.0.0
	 */
	function smarttoolz_register_menu_locations() {

		/**
		 * Primary Menus
		 */
		register_nav_menus(
			array(
				'primary' => esc_html__( 'Primary Menu', 'smarttoolz' ),
			)
		);

		if ( true === SmartToolz_Builder_Helper::$is_header_footer_builder_active ) {

			/**
			 * Register the Secondary & Mobile menus.
			 */
			register_nav_menus(
				array(
					'secondary_menu' => esc_html__( 'Secondary Menu', 'smarttoolz' ),
					'mobile_menu'    => esc_html__( 'Off-Canvas Menu', 'smarttoolz' ),
				)
			);

			$component_limit = defined( 'SMARTTOOLZ_EXT_VER' ) ? SmartToolz_Builder_Helper::$component_limit : SmartToolz_Builder_Helper::$num_of_header_menu;

			for ( $index = 3; $index <= $component_limit; $index++ ) {

				if ( ! is_customize_preview() && ! SmartToolz_Builder_Helper::is_component_loaded( 'menu-' . $index ) ) {
					continue;
				}

				register_nav_menus(
					array(
						'menu_' . $index => esc_html__( 'Menu ', 'smarttoolz' ) . $index,
					)
				);
			}

			/**
			 * Register the Account menus.
			 */
			register_nav_menus(
				array(
					'loggedin_account_menu' => esc_html__( 'Logged In Account Menu', 'smarttoolz' ),
				)
			);

		}

		/**
		 * Footer Menus
		 */
		register_nav_menus(
			array(
				'footer_menu' => esc_html__( 'Footer Menu', 'smarttoolz' ),
			)
		);
	}
}

add_action( 'init', 'smarttoolz_register_menu_locations' );
