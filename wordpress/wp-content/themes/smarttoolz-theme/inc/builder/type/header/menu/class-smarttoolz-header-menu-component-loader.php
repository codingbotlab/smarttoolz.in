<?php
/**
 * Menu Styling Loader for SmartToolz theme.
 *
 * @package     smarttoolz-builder
 * @link        https://wpsmarttoolz.com/
 * @since       3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Customizer Initialization
 *
 * @since 3.0.0
 */
class SmartToolz_Header_Menu_Component_Loader {
	/**
	 * Constructor
	 *
	 * @since 3.0.0
	 */
	public function __construct() {
		// Load Google fonts.
		add_action( 'smarttoolz_get_fonts', array( $this, 'add_fonts' ), 1 );
	}

	/**
	 * Enqueue google fonts.
	 *
	 * @since 3.0.0
	 */
	public function add_fonts() {

		$component_limit = defined( 'SMARTTOOLZ_EXT_VER' ) ? SmartToolz_Builder_Helper::$component_limit : SmartToolz_Builder_Helper::$num_of_header_menu;
		for ( $index = 1; $index <= $component_limit; $index++ ) {

			$_prefix = 'menu' . $index;

			$menu_font_family = smarttoolz_get_option( 'header-' . $_prefix . '-font-family' );
			$menu_font_weight = smarttoolz_get_option( 'header-' . $_prefix . '-font-weight' );

			SmartToolz_Fonts::add_font( $menu_font_family, $menu_font_weight );
		}

		$mobile_menu_font_family = smarttoolz_get_option( 'header-mobile-menu-font-family' );
		$mobile_menu_font_weight = smarttoolz_get_option( 'header-mobile-menu-font-weight' );

		SmartToolz_Fonts::add_font( $mobile_menu_font_family, $mobile_menu_font_weight );
	}

}

/**
 *  Kicking this off by creating the object of the class.
 */
new SmartToolz_Header_Menu_Component_Loader();
