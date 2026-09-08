<?php
/**
 * Footer Colors for SmartToolz theme Buttpn.
 *
 * @package     SmartToolz
 * @link        https://www.brainstormforce.com
 * @since       SmartToolz 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'SMARTTOOLZ_FOOTER_BUTTON_DIR', SMARTTOOLZ_THEME_DIR . 'inc/builder/type/footer/button' );
define( 'SMARTTOOLZ_FOOTER_BUTTON_URI', SMARTTOOLZ_THEME_URI . 'inc/builder/type/footer/button' );

/**
 * Heading Initial Setup
 *
 * @since 3.0.0
 */
class SmartToolz_Footer_Button_Component {
	/**
	 * Constructor function that initializes required actions and hooks
	 */
	public function __construct() {

		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once SMARTTOOLZ_FOOTER_BUTTON_DIR . '/class-smarttoolz-footer-button-component-loader.php';

		// Include front end files.
		if ( ! is_admin() || SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
			require_once SMARTTOOLZ_FOOTER_BUTTON_DIR . '/dynamic-css/dynamic.css.php';
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
}

/**
 *  Kicking this off by creating an object.
 */
new SmartToolz_Footer_Button_Component();
