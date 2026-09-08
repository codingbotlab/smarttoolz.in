<?php
/**
 * Below Footer component.
 *
 * @package     SmartToolz Builder
 * @link        https://www.brainstormforce.com
 * @since       SmartToolz 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'SMARTTOOLZ_BUILDER_FOOTER_BELOW_FOOTER_DIR', SMARTTOOLZ_THEME_DIR . 'inc/builder/type/footer/below-footer' );
define( 'SMARTTOOLZ_BUILDER_FOOTER_BELOW_FOOTER_URI', SMARTTOOLZ_THEME_URI . 'inc/builder/type/footer/below-footer' );

if ( ! class_exists( 'SmartToolz_Below_Footer' ) ) {

	/**
	 * Below Footer Initial Setup
	 *
	 * @since 3.0.0
	 */
	class SmartToolz_Below_Footer {
		/**
		 * Constructor function that initializes required actions and hooks
		 */
		public function __construct() {

			// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			// Include front end files.
			if ( ! is_admin() || SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
				require_once SMARTTOOLZ_BUILDER_FOOTER_BELOW_FOOTER_DIR . '/dynamic-css/dynamic.css.php';
			}
			// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		}
	}

	/**
	 *  Kicking this off by creating an object.
	 */
	new SmartToolz_Below_Footer();

}
