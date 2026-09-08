<?php
/**
 * Contact Form 7 Compatibility File.
 *
 * @package SmartToolz
 */

// If plugin - 'Contact Form 7' not exist then return.
if ( ! class_exists( 'WPCF7' ) ) {
	return;
}

/**
 * SmartToolz Contact Form 7 Compatibility
 */
if ( ! class_exists( 'SmartToolz_Contact_Form_7' ) ) {

	/**
	 * SmartToolz Contact Form 7 Compatibility
	 *
	 * @since 1.0.0
	 */
	class SmartToolz_Contact_Form_7 {
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
			add_action( 'wpcf7_enqueue_styles', array( $this, 'add_styles' ) );
		}

		/**
		 * Add assets in theme
		 *
		 * @since 1.0.0
		 */
		public function add_styles() {
			$file_prefix = '.min';
			$dir_name    = 'minified';

			if ( is_rtl() ) {
				$file_prefix .= '-rtl';
			}

			$css_file = SMARTTOOLZ_THEME_URI . 'assets/css/' . $dir_name . '/compatibility/contact-form-7-main' . $file_prefix . '.css';

			wp_enqueue_style( 'smarttoolz-contact-form-7', $css_file, array(), SMARTTOOLZ_THEME_VERSION, 'all' );
		}

	}

}

/**
 * Kicking this off by calling 'get_instance()' method
 */
SmartToolz_Contact_Form_7::get_instance();
