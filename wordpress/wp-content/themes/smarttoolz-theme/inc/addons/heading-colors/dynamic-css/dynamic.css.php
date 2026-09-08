<?php
/**
 * Heading Colors - Dynamic CSS
 *
 * @package SmartToolz
 * @since 2.1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Heading Colors
 */
add_filter( 'smarttoolz_dynamic_theme_css', 'smarttoolz_heading_colors_section_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          SmartToolz Dynamic CSS.
 * @param  string $dynamic_css_filtered SmartToolz Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Heading Colors.
 *
 * @since 2.1.4
 */
function smarttoolz_heading_colors_section_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	/**
	 * Heading Colors - h1 - h6.
	 */
	$heading_base_color = smarttoolz_get_option( 'heading-base-color' );

	if ( empty( $heading_base_color ) ) {
		return $dynamic_css;
	}

	/**
	 * Normal Colors without reponsive option.
	 * [1]. Heading Colors
	 */
	$css_output = array(

		/**
		 * Content base heading color.
		 */
		'h1, h2, h3, h4, h5, h6, .entry-content :where(h1, h2, h3, h4, h5, h6)' => array(
			'color' => esc_attr( $heading_base_color ),
		),
	);

	if ( smarttoolz_has_global_color_format_support() ) {
		$css_output['.entry-title a'] = array(
			'color' => esc_attr( $heading_base_color ),
		);
	}

	/* Parse CSS from array() */
	$css_output = smarttoolz_parse_css( $css_output );

	$dynamic_css .= $css_output;

	return $dynamic_css;
}
