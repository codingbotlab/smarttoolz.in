<?php
/**
 * Heading Colors for SmartToolz theme.
 *
 * @package     smarttoolz-builder
 * @link        https://wpsmarttoolz.com/
 * @since       3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'SMARTTOOLZ_HEADER_SOCIAL_ICON_DIR', SMARTTOOLZ_THEME_DIR . 'inc/builder/type/header/social-icon' );
define( 'SMARTTOOLZ_HEADER_SOCIAL_ICON_URI', SMARTTOOLZ_THEME_URI . 'inc/builder/type/header/social-icon' );

/**
 * Heading Initial Setup
 *
 * @since 3.0.0
 */
class SmartToolz_Header_Social_Icon_Component {
	/**
	 * Constructor function that initializes required actions and hooks
	 */
	public function __construct() {

		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		// Include front end files.
		if ( ! is_admin() || SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
			require_once SMARTTOOLZ_HEADER_SOCIAL_ICON_DIR . '/dynamic-css/dynamic.css.php';
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
}

/**
 *  Kicking this off by creating an object.
 */
new SmartToolz_Header_Social_Icon_Component();
