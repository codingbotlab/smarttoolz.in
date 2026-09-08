<?php
/**
 * SmartToolz Pro Customizer Section
 *
 * @package   SmartToolz
 * @link      https://wpsmarttoolz.com/
 * @since     SmartToolz 1.0.10
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * SmartToolz_Pro_Customizer
 *
 * @since 1.0.10
 */
if ( ! class_exists( 'SmartToolz_Pro_Customizer' ) ) {

	/**
	 * SmartToolz_Pro_Customizer Initial setup
	 */
	class SmartToolz_Pro_Customizer extends WP_Customize_Section {
		/**
		 * The type of customize section being rendered.
		 *
		 * @since  1.0.10
		 * @var    string
		 */
		public $type = 'smarttoolz-pro';

		/**
		 * Custom pro button URL.
		 *
		 * @since  1.0.10
		 * @var    string
		 */
		public $pro_url = '';

		/**
		 * Add custom parameters to pass to the JS via JSON.
		 *
		 * @since  1.0.10
		 * @return string
		 */
		public function json() {
			$json            = parent::json();
			$json['pro_url'] = esc_url_raw( $this->pro_url );
			return $json;
		}

		/**
		 * Outputs the Underscore.js template.
		 *
		 * @since  1.0.10
		 * @return void
		 */
		protected function render_template() {
			?>
		<li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }} cannot-expand control-section-default">
			<h3 class="wp-ui-highlight">
				<# if ( data.title && data.pro_url ) { #>
				<a href="{{ data.pro_url }}" class="wp-ui-text-highlight" target="_blank" rel="noopener">{{ data.title }}</a>
				<# } #>
			</h3>
		</li>
			<?php
		}
	}

}
