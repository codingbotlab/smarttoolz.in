<?php
/**
 * Schema markup.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 2.1.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * SmartToolz Schema Markup.
 *
 * @since 2.1.3
 */
class SmartToolz_Schema {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->include_schemas();

		add_action( 'wp', array( $this, 'setup_schema' ) );
	}

	/**
	 * Setup schema
	 *
	 * @since 2.1.3
	 */
	public function setup_schema() {
	}

	/**
	 * Include schema files.
	 *
	 * @since 2.1.3
	 */
	private function include_schemas() {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once SMARTTOOLZ_THEME_DIR . 'inc/schema/class-smarttoolz-creativework-schema.php';
		require_once SMARTTOOLZ_THEME_DIR . 'inc/schema/class-smarttoolz-wpheader-schema.php';
		require_once SMARTTOOLZ_THEME_DIR . 'inc/schema/class-smarttoolz-wpfooter-schema.php';
		require_once SMARTTOOLZ_THEME_DIR . 'inc/schema/class-smarttoolz-wpsidebar-schema.php';
		require_once SMARTTOOLZ_THEME_DIR . 'inc/schema/class-smarttoolz-person-schema.php';
		require_once SMARTTOOLZ_THEME_DIR . 'inc/schema/class-smarttoolz-organization-schema.php';
		require_once SMARTTOOLZ_THEME_DIR . 'inc/schema/class-smarttoolz-site-navigation-schema.php';
		require_once SMARTTOOLZ_THEME_DIR . 'inc/schema/class-smarttoolz-breadcrumb-schema.php';
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Enabled schema
	 *
	 * @since 2.1.3
	 */
	protected function schema_enabled() {
		return apply_filters( 'smarttoolz_schema_enabled', true );
	}

}

new SmartToolz_Schema();
