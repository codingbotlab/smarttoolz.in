<?php
/**
 * SmartToolz Theme Customizer Configuration Base.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 1.4.3
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Customizer Sanitizes
 *
 * @since 1.0.0
 */
if ( ! class_exists( 'SmartToolz_Customizer_Config_Base' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class SmartToolz_Customizer_Config_Base {
		/**
		 * Constructor
		 */
		public function __construct() {
			// Bail early if it is not smarttoolz customizer.
			if ( ! SmartToolz_Customizer::is_smarttoolz_customizer() ) {
				return;
			}

			add_filter( 'smarttoolz_customizer_configurations', array( $this, 'register_configuration' ), 30, 2 );
		}

		/**
		 * Base Method for Registering Customizer Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {
			return $configurations;
		}

		/**
		 * Section Description
		 *
		 * @since 1.4.3
		 *
		 * @param  array $args Description arguments.
		 * @return mixed       Markup of the section description.
		 */
		public function section_get_description( $args ) {

			// Return if white labeled.
			if ( smarttoolz_is_white_labelled() ) {
				return '';
			}

			// Description.
			$content  = '<div class="smarttoolz-section-description">';
			$content .= wp_kses_post( smarttoolz_get_prop( $args, 'description' ) );

			// Links.
			if ( smarttoolz_get_prop( $args, 'links' ) ) {
				$content .= '<ul>';
				foreach ( $args['links'] as $link ) {

					if ( smarttoolz_get_prop( $link, 'attrs' ) ) {

						$content .= '<li>';

						// Attribute mapping.
						$attributes = ' target="_blank" ';
						foreach ( smarttoolz_get_prop( $link, 'attrs' ) as $attr => $attr_value ) {
							$attributes .= ' ' . $attr . '="' . esc_attr( $attr_value ) . '" ';
						}
						$content .= '<a ' . $attributes . '>' . esc_html( smarttoolz_get_prop( $link, 'text' ) ) . '</a></li>';

						$content .= '</li>';
					}
				}
				$content .= '</ul>';
			}

			$content .= '</div><!-- .smarttoolz-section-description -->';

			return $content;
		}

	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new SmartToolz_Customizer_Config_Base();
