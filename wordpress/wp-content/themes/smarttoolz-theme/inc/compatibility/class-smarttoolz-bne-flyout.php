<?php
/**
 * BNE Flyout Compatibility File.
 *
 * @package SmartToolz
 */

// If plugin - 'BNE Flyout' not exist then return.
if ( ! defined( 'BNE_FLYOUT_VERSION' ) ) {
	return;
}

/**
 * SmartToolz BNE Flyout Compatibility
 */
if ( ! class_exists( 'SmartToolz_BNE_Flyout' ) ) {

	/**
	 * SmartToolz BNE Flyout Compatibility
	 *
	 * @since 1.0.0
	 */
	class SmartToolz_BNE_Flyout {
		/**
		 * Member Variable
		 *
		 * @var object instance
		 */
		private static $instance;

		/**
		 * Initiator
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {
			add_filter( 'smarttoolz_theme_assets', array( $this, 'add_styles' ) );
		}

		/**
		 * Add assets in theme
		 *
		 * @param array $assets list of theme assets (JS & CSS).
		 * @return array List of updated assets.
		 * @since 1.0.0
		 */
		public function add_styles( $assets ) {
			$assets['css']['smarttoolz-bne-flyout'] = 'compatibility/bne-flyout';
			return $assets;
		}

	}

}

/**
 * Kicking this off by calling 'get_instance()' method
 */
SmartToolz_BNE_Flyout::get_instance();
