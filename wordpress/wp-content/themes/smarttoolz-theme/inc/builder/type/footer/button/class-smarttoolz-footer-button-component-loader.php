<?php
/**
 * Button Styling Loader for SmartToolz theme.
 *
 * @package     SmartToolz
 * @link        https://www.brainstormforce.com
 * @since       SmartToolz 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Customizer Initialization
 *
 * @since 3.0.0
 */
class SmartToolz_Footer_Button_Component_Loader {
	/**
	 * Constructor
	 *
	 * @since 3.0.0
	 */
	public function __construct() {
		add_action( 'smarttoolz_get_fonts', array( $this, 'add_fonts' ), 1 );
	}

	/**
	 * Add Font Family Callback
	 *
	 * @return void
	 */
	public function add_fonts() {
		/**
		 * Footer - Button
		 */
		$num_of_footer_button = SmartToolz_Builder_Helper::$num_of_footer_button;
		for ( $index = 1; $index <= $num_of_footer_button; $index++ ) {
			if ( ! SmartToolz_Builder_Helper::is_component_loaded( 'button-' . $index, 'footer' ) ) {
				continue;
			}

			$_prefix = 'button' . $index;

			$btn_font_family = smarttoolz_get_option( 'footer-' . $_prefix . '-font-family' );
			$btn_font_weight = smarttoolz_get_option( 'footer-' . $_prefix . '-font-weight' );
			SmartToolz_Fonts::add_font( $btn_font_family, $btn_font_weight );
		}
	}

}

/**
 *  Kicking this off by creating the object of the class.
 */
new SmartToolz_Footer_Button_Component_Loader();
