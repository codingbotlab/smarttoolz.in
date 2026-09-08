<?php
/**
 * Heading Colors for SmartToolz theme.
 *
 * @package     SmartToolz
 * @link        https://www.brainstormforce.com
 * @since       SmartToolz 3.0.0.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'SMARTTOOLZ_PRIMARY_HEADER_DIR', SMARTTOOLZ_THEME_DIR . 'inc/builder/type/header/primary-header' );
define( 'SMARTTOOLZ_PRIMARY_HEADER_URI', SMARTTOOLZ_THEME_URI . 'inc/builder/type/header/primary-header' );

/**
 * Heading Initial Setup
 *
 * @since 3.0.0
 */
class SmartToolz_Primary_Header {
	/**
	 * Constructor function that initializes required actions and hooks
	 */
	public function __construct() {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once SMARTTOOLZ_PRIMARY_HEADER_DIR . '/class-smarttoolz-primary-header-loader.php';

		// Include front end files.
		if ( ! is_admin() || SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
			require_once SMARTTOOLZ_PRIMARY_HEADER_DIR . '/dynamic-css/dynamic.css.php';
			remove_filter( 'smarttoolz_dynamic_theme_css', 'smarttoolz_header_breakpoint_style' );
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
}

/**
 *  Kicking this off by creating an object.
 */
new SmartToolz_Primary_Header();
