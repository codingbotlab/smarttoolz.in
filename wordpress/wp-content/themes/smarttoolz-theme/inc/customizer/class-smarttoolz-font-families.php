<?php
/**
 * Helper class for font settings.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 1.0.19
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Font info class for System and Google fonts.
 */
if ( ! class_exists( 'SmartToolz_Font_Families' ) ) {

	/**
	 * Font info class for System and Google fonts.
	 */
	final class SmartToolz_Font_Families {
		/**
		 * System Fonts
		 *
		 * @since 1.0.19
		 * @var array
		 */
		public static $system_fonts = array();

		/**
		 * Google Fonts
		 *
		 * @since 1.0.19
		 * @var array
		 */
		public static $google_fonts = array();

		/**
		 * Get System Fonts
		 *
		 * @since 1.0.19
		 *
		 * @return Array All the system fonts in SmartToolz
		 */
		public static function get_system_fonts() {
			if ( empty( self::$system_fonts ) ) {
				self::$system_fonts = array(
					'Helvetica' => array(
						'fallback' => 'Verdana, Arial, sans-serif',
						'weights'  => array(
							'300',
							'400',
							'700',
						),
					),
					'Verdana'   => array(
						'fallback' => 'Helvetica, Arial, sans-serif',
						'weights'  => array(
							'300',
							'400',
							'700',
						),
					),
					'Arial'     => array(
						'fallback' => 'Helvetica, Verdana, sans-serif',
						'weights'  => array(
							'300',
							'400',
							'700',
						),
					),
					'Times'     => array(
						'fallback' => 'Georgia, serif',
						'weights'  => array(
							'300',
							'400',
							'700',
						),
					),
					'Georgia'   => array(
						'fallback' => 'Times, serif',
						'weights'  => array(
							'300',
							'400',
							'700',
						),
					),
					'Courier'   => array(
						'fallback' => 'monospace',
						'weights'  => array(
							'300',
							'400',
							'700',
						),
					),
				);
			}

			return apply_filters( 'smarttoolz_system_fonts', self::$system_fonts );
		}

		/**
		 * Custom Fonts
		 *
		 * @since 1.0.19
		 *
		 * @return Array All the custom fonts in SmartToolz
		 */
		public static function get_custom_fonts() {
			$custom_fonts = array();

			return apply_filters( 'smarttoolz_custom_fonts', $custom_fonts );
		}

		/**
		 * Variant labels.
		 *
		 * @since 3.8.0
		 * @return array
		 */
		public static function font_variant_labels() {
			return array(
				'100'       => __( 'Thin 100', 'smarttoolz' ),
				'200'       => __( 'Extra Light 200', 'smarttoolz' ),
				'300'       => __( 'Light 300', 'smarttoolz' ),
				'400'       => __( 'Regular 400', 'smarttoolz' ),
				'500'       => __( 'Medium 500', 'smarttoolz' ),
				'600'       => __( 'Semi-Bold 600', 'smarttoolz' ),
				'700'       => __( 'Bold 700', 'smarttoolz' ),
				'800'       => __( 'Extra-Bold 800', 'smarttoolz' ),
				'900'       => __( 'Ultra-Bold 900', 'smarttoolz' ),
				'100italic' => __( 'Thin 100 Italic', 'smarttoolz' ),
				'200italic' => __( 'Extra Light 200 Italic', 'smarttoolz' ),
				'300italic' => __( 'Light 300 Italic', 'smarttoolz' ),
				'400italic' => __( 'Regular 400 Italic', 'smarttoolz' ),
				'italic'    => __( 'Regular 400 Italic', 'smarttoolz' ),
				'500italic' => __( 'Medium 500 Italic', 'smarttoolz' ),
				'600italic' => __( 'Semi-Bold 600 Italic', 'smarttoolz' ),
				'700italic' => __( 'Bold 700 Italic', 'smarttoolz' ),
				'800italic' => __( 'Extra-Bold 800 Italic', 'smarttoolz' ),
				'900italic' => __( 'Ultra-Bold 900 Italic', 'smarttoolz' ),
			);
		}

		/**
		 * Google Fonts used in smarttoolz.
		 * Array is generated from the google-fonts.json file.
		 *
		 * @since  1.0.19
		 *
		 * @return Array Array of Google Fonts.
		 */
		public static function get_google_fonts() {

			if ( empty( self::$google_fonts ) ) {

				/**
				 * Deprecating the Filter to change the Google Fonts JSON file path.
				 *
				 * @since 2.5.0
				 * @param string $json_file File where google fonts json format added.
				 * @return array
				 */
				$google_fonts_file = apply_filters( 'smarttoolz_google_fonts_php_file', SMARTTOOLZ_THEME_DIR . 'inc/google-fonts.php' );

				if ( ! file_exists( $google_fonts_file ) ) {
					return array();
				}

				$google_fonts_arr = include $google_fonts_file;// phpcs:ignore: WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

				foreach ( $google_fonts_arr as $font ) {
					$name = key( $font );
					foreach ( $font[ $name ] as $font_key => $single_font ) {

						if ( 'variants' === $font_key ) {

							foreach ( $single_font as $variant_key => $variant ) {

								if ( 'regular' === $variant ) {
									$font[ $name ][ $font_key ][ $variant_key ] = '400';
								}
							}
						}

						self::$google_fonts[ $name ] = array_values( $font[ $name ] );
					}
				}
			}

			return apply_filters( 'smarttoolz_google_fonts', self::$google_fonts );
		}

	}

}
