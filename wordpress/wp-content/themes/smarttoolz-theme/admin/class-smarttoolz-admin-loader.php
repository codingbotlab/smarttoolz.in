<?php
/**
 * SmartToolz Admin Loader
 *
 * @package SmartToolz
 * @since 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'SmartToolz_Admin_Loader' ) ) {
	/**
	 * SmartToolz_Admin_Loader
	 *
	 * @since 4.0.0
	 */
	class SmartToolz_Admin_Loader {
		/**
		 * Instance
		 *
		 * @var null $instance
		 * @since 4.0.0
		 */
		private static $instance;

		/**
		 * Initiator
		 *
		 * @since 4.0.0
		 * @return object initialized object of class.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				/** @psalm-suppress InvalidPropertyAssignmentValue */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				self::$instance = new self();
				/** @psalm-suppress InvalidPropertyAssignmentValue */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 *
		 * @since 4.0.0
		 */
		public function __construct() {
			define( 'SMARTTOOLZ_THEME_ADMIN_DIR', SMARTTOOLZ_THEME_DIR . 'admin/' );
			define( 'SMARTTOOLZ_THEME_ADMIN_URL', SMARTTOOLZ_THEME_URI . 'admin/' );

			$this->includes();
		}

		/**
		 * Include required classes.
		 *
		 * @since 4.0.0
		 */
		public function includes() {
			/* Ajax init */
			require_once SMARTTOOLZ_THEME_ADMIN_DIR . 'includes/class-smarttoolz-admin-ajax.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound -- Not a template file so loading in a normal way.

			/* Setup Menu */
			require_once SMARTTOOLZ_THEME_ADMIN_DIR . 'includes/class-smarttoolz-menu.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound -- Not a template file so loading in a normal way.

			require_once SMARTTOOLZ_THEME_ADMIN_DIR . 'includes/class-smarttoolz-theme-builder-free.php'; // phpcs:ignore WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound -- Not a template file so loading in a normal way.
		}
	}
}

SmartToolz_Admin_Loader::get_instance();
