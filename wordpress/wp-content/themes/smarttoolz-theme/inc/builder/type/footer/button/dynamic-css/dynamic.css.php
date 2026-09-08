<?php
/**
 * Butons - Dynamic CSS
 *
 * @package SmartToolz
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Heading Colors
 */
add_filter( 'smarttoolz_dynamic_theme_css', 'smarttoolz_fb_button_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          SmartToolz Dynamic CSS.
 * @param  string $dynamic_css_filtered SmartToolz Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Heading Colors.
 *
 * @since 3.0.0
 */
function smarttoolz_fb_button_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	$dynamic_css   .= SmartToolz_Button_Component_Dynamic_CSS::smarttoolz_button_dynamic_css( 'footer' );
	$fb_button_flag = false;
	for ( $index = 1; $index <= SmartToolz_Builder_Helper::$num_of_footer_button; $index++ ) {

		if ( ! SmartToolz_Builder_Helper::is_component_loaded( 'button-' . $index, 'footer' ) ) {
			continue;
		}
		$fb_button_flag = true;

		$selector = '.ast-footer-button-' . $index . '[data-section="section-fb-button-' . $index . '"]';

		$alignment = smarttoolz_get_option( 'footer-button-' . $index . '-alignment' );

		$desktop_alignment = isset( $alignment['desktop'] ) ? $alignment['desktop'] : '';
		$tablet_alignment  = isset( $alignment['tablet'] ) ? $alignment['tablet'] : '';
		$mobile_alignment  = isset( $alignment['mobile'] ) ? $alignment['mobile'] : '';

		/**
		 * Copyright CSS.
		 */
		$css_output_desktop = array(
			$selector => array(
				'justify-content' => $desktop_alignment,
			),
		);

		$css_output_tablet = array(
			$selector => array(
				'justify-content' => $tablet_alignment,
			),
		);

		$css_output_mobile = array(
			$selector => array(
				'justify-content' => $mobile_alignment,
			),
		);

		/* Parse CSS from array() */
		$css_output  = smarttoolz_parse_css( $css_output_desktop );
		$css_output .= smarttoolz_parse_css( $css_output_tablet, '', smarttoolz_get_tablet_breakpoint() );
		$css_output .= smarttoolz_parse_css( $css_output_mobile, '', smarttoolz_get_mobile_breakpoint() );

		$dynamic_css .= $css_output;
	}
	if ( true === $fb_button_flag ) {
		$static_css = array(
			'[data-section*="section-fb-button-"] .menu-link' => array(
				'display' => 'none',
			),
			'[CLASS*="ast-footer-button-"][data-section^="section-fb-button-"]' => array(
				'justify-content' => 'center',
			),
			'.site-footer-focus-item[CLASS*="ast-footer-button-"]' => array(
				'display' => 'flex',
			),
		);
		return smarttoolz_parse_css( $static_css ) . $dynamic_css;
	}

	return $dynamic_css;
}
