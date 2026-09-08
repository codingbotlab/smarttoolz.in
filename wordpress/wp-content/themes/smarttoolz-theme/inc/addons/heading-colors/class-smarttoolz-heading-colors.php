<?php
/**
 * Heading Colors for SmartToolz theme.
 *
 * @package     SmartToolz
 * @link        https://www.brainstormforce.com
 * @since       SmartToolz 2.1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'SMARTTOOLZ_THEME_HEADING_COLORS_DIR', SMARTTOOLZ_THEME_DIR . 'inc/addons/heading-colors/' );
define( 'SMARTTOOLZ_THEME_HEADING_COLORS_URI', SMARTTOOLZ_THEME_URI . 'inc/addons/heading-colors/' );

if ( ! class_exists( 'SmartToolz_Heading_Colors' ) ) {

	/**
	 * Heading Initial Setup
	 *
	 * @since 2.1.4
	 */
	class SmartToolz_Heading_Colors {
		/**
		 * Constructor function that initializes required actions and hooks
		 */
		public function __construct() {

			require_once SMARTTOOLZ_THEME_HEADING_COLORS_DIR . 'class-smarttoolz-heading-colors-loader.php';// phpcs:ignore: WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

			// Include front end files.
			if ( ! is_admin() ) {
				require_once SMARTTOOLZ_THEME_HEADING_COLORS_DIR . 'dynamic-css/dynamic.css.php';// phpcs:ignore: WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			}
		}
	}

	/**
	 *  Kicking this off by creating an object.
	 */
	new SmartToolz_Heading_Colors();

}
