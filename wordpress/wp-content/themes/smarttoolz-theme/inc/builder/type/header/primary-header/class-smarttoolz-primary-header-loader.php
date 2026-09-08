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
class SmartToolz_Primary_Header_Loader {
	/**
	 * Constructor
	 *
	 * @since 3.0.0
	 */
	public function __construct() {

		// Markup.
		add_filter( 'body_class', array( $this, 'smarttoolz_body_header_classes' ) );
	}

	/**
	 * Adds custom classes to the array of body classes.
	 *
	 * @since 1.0.0
	 * @param array $classes Classes for the body element.
	 * @return array
	 */
	public function smarttoolz_body_header_classes( $classes ) {
		/**
		 * Add class for header width
		 */
		$header_content_layout = smarttoolz_get_option( 'hb-header-main-layout-width' );

		if ( 'full' === $header_content_layout ) {
			$classes[] = 'ast-full-width-primary-header';
		}

		return $classes;
	}

}

/**
 *  Kicking this off by creating the object of the class.
 */
new SmartToolz_Primary_Header_Loader();
