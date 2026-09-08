<?php
/**
 * Init
 *
 * @since 1.0.0
 * @package NPS Survey
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SmartToolz_Nps_Notice' ) ) {

	/**
	 * Admin
	 */
	class SmartToolz_Nps_Notice {
		/**
		 * Instance
		 *
		 * @since 1.0.0
		 * @var (Object) SmartToolz_Nps_Notice
		 */
		private static $instance = null;

		/**
		 * Constructor.
		 *
		 * @since 1.0.0
		 */
		private function __construct() {
			// Bail early if not in admin area.
			if ( ! is_admin() ) {
				return;
			}

			// Allow users to disable NPS survey via a filter && return early if the user does not have admin access.
			if ( ! current_user_can( 'manage_options' ) || apply_filters( 'smarttoolz_nps_survey_disable', false ) ) {
				return;
			}

			// Added filter to allow overriding the URL externally.
			add_filter( 'nps_survey_build_url', static function( $url ) {
				return get_template_directory_uri() . '/inc/lib/nps-survey/dist/';
			} );

			// Bail early if soft while labeling is enabled.
			if (
				defined( 'SMARTTOOLZ_EXT_VER' ) &&
				is_callable( 'SmartToolz_Ext_White_Label_Markup::get_whitelabel_string' ) &&
				'smarttoolz' !== strtolower( SmartToolz_Ext_White_Label_Markup::get_whitelabel_string( 'smarttoolz', 'name', 'smarttoolz' ) )
			) {
				return;
			}

			// Return if white labelled is enabled.
			if ( smarttoolz_is_white_labelled() ) {
				return;
			}

			add_action( 'admin_footer', array( $this, 'render_smarttoolz_nps_survey' ), 999 );
		}

		/**
		 * Get Instance
		 *
		 * @since 1.0.0
		 *
		 * @return object Class object.
		 */
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Render NPS Survey
		 *
		 * @return void
		 */
		public function render_smarttoolz_nps_survey() {

			$current_screen = get_current_screen();

			// Defining the smarttoolz allowed screens.
			$allowed_screens = [
				'toplevel_page_smarttoolz',
				'smarttoolz_page_theme-builder-free',
				'smarttoolz_page_theme-builder',
			];

			// Checking if we're on one of the specified screens
			if ( ! in_array( $current_screen->id, $allowed_screens ) ) {
				return;
			}

			Nps_Survey::show_nps_notice(
				'nps-survey-smarttoolz',
				array(
					'show_if' => defined( 'SMARTTOOLZ_THEME_VERSION' ),
					'dismiss_timespan' => 2 * WEEK_IN_SECONDS,
					'display_after' => get_option('smarttoolz_nps_show') ? 0 : 2 * WEEK_IN_SECONDS,
					'plugin_slug' => 'smarttoolz',
					'show_on_screens' => $allowed_screens,
					'message' => array(
						// Step 1 i.e rating input.
						'logo' => esc_url( SMARTTOOLZ_THEME_URI . 'inc/assets/images/smarttoolz-logo.svg'),
						'plugin_name' => __( 'SmartToolz', 'smarttoolz' ),
						'nps_rating_title' => __( 'Quick Question', 'smarttoolz' ),
						'nps_rating_message' => __( 'How would you rate SmartToolz? Love it, hate it, or somewhere in between? Your honest answer helps us understand how were doing.', 'smarttoolz' ),
						'rating_min_label' => __( 'Hate it', 'smarttoolz' ),
						'rating_max_label' => __( 'Love it', 'smarttoolz' ),

						// Step 2A i.e. positive.
						'feedback_title' => __( 'Thanks a lot for your feedback! 😍', 'smarttoolz' ),
						'feedback_content' => __( 'Thanks for using SmartToolz! Got feedback or suggestions to make it even better? We’d love to hear from you.', 'smarttoolz' ),
						'plugin_rating_link' => esc_url( 'https://wordpress.org/support/theme/smarttoolz/reviews/#new-post' ),
						'plugin_rating_button_string' => __( 'Rate the Theme', 'smarttoolz' ),

						// Step 2B i.e. negative.
						'plugin_rating_title' => __( 'Thank you for your feedback', 'smarttoolz' ),
						'plugin_rating_content' => __( 'We value your input. How can we improve your experience?', 'smarttoolz' ),
					),
					'privacy_policy'  => array(
						'disable' => true, // Enable when we have a privacy policy url.
					),
				)
			);
		}

	}

	/**
	 * Kicking this off by calling 'get_instance()' method
	 */
	SmartToolz_Nps_Notice::get_instance();

}
