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

define( 'SMARTTOOLZ_HEADER_BUTTON_DIR', SMARTTOOLZ_THEME_DIR . 'inc/builder/type/header/button' );
define( 'SMARTTOOLZ_HEADER_BUTTON_URI', SMARTTOOLZ_THEME_URI . 'inc/builder/type/header/button' );

/**
 * Heading Initial Setup
 *
 * @since 2.1.4
 */
class SmartToolz_Header_Button_Component {
	/**
	 * Constructor function that initializes required actions and hooks
	 */
	public function __construct() {

		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once SMARTTOOLZ_HEADER_BUTTON_DIR . '/class-smarttoolz-header-button-component-loader.php';

		// Include front end files.
		if ( ! is_admin() || SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
			require_once SMARTTOOLZ_HEADER_BUTTON_DIR . '/dynamic-css/dynamic.css.php';
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

}

/**
 *  Kicking this off by creating an object.
 */
new SmartToolz_Header_Button_Component();
