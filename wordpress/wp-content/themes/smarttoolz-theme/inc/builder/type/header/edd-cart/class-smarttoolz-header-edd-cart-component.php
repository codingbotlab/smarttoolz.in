<?php
/**
 * EDD Cart for SmartToolz theme.
 *
 * @package     smarttoolz-builder
 * @link        https://wpsmarttoolz.com/
 * @since       3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'SMARTTOOLZ_HEADER_EDD_CART_DIR', SMARTTOOLZ_THEME_DIR . 'inc/builder/type/header/edd-cart' );
define( 'SMARTTOOLZ_HEADER_EDD_CART_URI', SMARTTOOLZ_THEME_URI . 'inc/builder/type/header/edd-cart' );

if ( ! class_exists( 'SmartToolz_Header_Edd_Cart_Component' ) ) {

	/**
	 * Heading Initial Setup
	 *
	 * @since 3.0.0
	 */
	class SmartToolz_Header_Edd_Cart_Component {
		/**
		 * Constructor function that initializes required actions and hooks
		 */
		public function __construct() {

			// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			// Include front end files.
			if ( ! is_admin() || SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
				require_once SMARTTOOLZ_HEADER_EDD_CART_DIR . '/dynamic-css/dynamic.css.php';
			}
			// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
	}

	/**
	 *  Kicking this off by creating an object.
	 */
	new SmartToolz_Header_Edd_Cart_Component();

}
