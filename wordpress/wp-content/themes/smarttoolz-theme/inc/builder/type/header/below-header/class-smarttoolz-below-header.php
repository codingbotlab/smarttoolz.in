<?php
/**
 * Below Header.
 *
 * @package     smarttoolz-builder
 * @link        https://www.brainstormforce.com
 * @since       3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'SMARTTOOLZ_BELOW_HEADER_DIR', SMARTTOOLZ_THEME_DIR . 'inc/builder/type/header/below-header' );
define( 'SMARTTOOLZ_BELOW_HEADER_URI', SMARTTOOLZ_THEME_URI . 'inc/builder/type/header/below-header' );

/**
 * Below Header Initial Setup
 *
 * @since 3.0.0
 */
class SmartToolz_Below_Header {
	/**
	 * Constructor function that initializes required actions and hooks.
	 */
	public function __construct() {

		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		// Include front end files.
		if ( ! is_admin() || SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
			require_once SMARTTOOLZ_BELOW_HEADER_DIR . '/dynamic-css/dynamic.css.php';
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
}

/**
 *  Kicking this off by creating an object.
 */
new SmartToolz_Below_Header();
