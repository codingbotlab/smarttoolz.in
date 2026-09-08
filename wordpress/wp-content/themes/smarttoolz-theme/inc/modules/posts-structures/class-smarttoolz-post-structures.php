<?php
/**
 * Post Structures Extension
 *
 * @package SmartToolz
 * @since 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'SMARTTOOLZ_THEME_POST_STRUCTURE_DIR', SMARTTOOLZ_THEME_DIR . 'inc/modules/posts-structures/' );
define( 'SMARTTOOLZ_THEME_POST_STRUCTURE_URI', SMARTTOOLZ_THEME_URI . 'inc/modules/posts-structures/' );

/**
 * Post Structures Initial Setup
 *
 * @since 4.0.0
 */
class SmartToolz_Post_Structures {
	/**
	 * Constructor function that loads require files.
	 */
	public function __construct() {

		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once SMARTTOOLZ_THEME_POST_STRUCTURE_DIR . 'class-smarttoolz-posts-structure-loader.php';
		require_once SMARTTOOLZ_THEME_POST_STRUCTURE_DIR . 'class-smarttoolz-posts-structure-markup.php';

		// Include front end files.
		if ( ! is_admin() ) {
			require_once SMARTTOOLZ_THEME_POST_STRUCTURE_DIR . 'css/single-dynamic.css.php';
			require_once SMARTTOOLZ_THEME_POST_STRUCTURE_DIR . 'css/archive-dynamic.css.php';
			require_once SMARTTOOLZ_THEME_POST_STRUCTURE_DIR . 'css/special-dynamic.css.php';
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}
}

/**
 *  Kicking this off by creating new object.
 */
new SmartToolz_Post_Structures();
