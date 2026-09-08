<?php
/**
 * Copyright control - Dynamic CSS
 *
 * @package SmartToolz Builder
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Copyright CSS
 */
add_filter( 'smarttoolz_dynamic_theme_css', 'smarttoolz_fb_copyright_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          SmartToolz Dynamic CSS.
 * @param  string $dynamic_css_filtered SmartToolz Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Heading Colors.
 *
 * @since 3.0.0
 */
function smarttoolz_fb_copyright_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	if ( ! SmartToolz_Builder_Helper::is_component_loaded( 'copyright', 'footer' ) ) {
		return $dynamic_css;
	}

	$_section = 'section-footer-copyright';

	$selector = '.ast-footer-copyright.site-footer-focus-item ';

	$visibility_selector = '.ast-footer-copyright.ast-builder-layout-element';

	$alignment = smarttoolz_get_option( 'footer-copyright-alignment' );

	$desktop_alignment = isset( $alignment['desktop'] ) ? $alignment['desktop'] : '';
	$tablet_alignment  = isset( $alignment['tablet'] ) ? $alignment['tablet'] : '';
	$mobile_alignment  = isset( $alignment['mobile'] ) ? $alignment['mobile'] : '';

	$margin = smarttoolz_get_option( $_section . '-margin' );

	/**
	 * Copyright CSS.
	 */
	$css_output_desktop = array(
		'.ast-footer-copyright' => array(
			'text-align' => $desktop_alignment,
		),
		$selector               => array(
			'color'         => smarttoolz_get_option( 'footer-copyright-color', smarttoolz_get_option( 'text-color' ) ),
			// Margin CSS.
			'margin-top'    => smarttoolz_responsive_spacing( $margin, 'top', 'desktop' ),
			'margin-bottom' => smarttoolz_responsive_spacing( $margin, 'bottom', 'desktop' ),
			'margin-left'   => smarttoolz_responsive_spacing( $margin, 'left', 'desktop' ),
			'margin-right'  => smarttoolz_responsive_spacing( $margin, 'right', 'desktop' ),
		),
	);

	$css_output_tablet = array(
		'.ast-footer-copyright' => array(
			'text-align' => $tablet_alignment,
		),
		$selector               => array(
			// Margin CSS.
			'margin-top'    => smarttoolz_responsive_spacing( $margin, 'top', 'tablet' ),
			'margin-bottom' => smarttoolz_responsive_spacing( $margin, 'bottom', 'tablet' ),
			'margin-left'   => smarttoolz_responsive_spacing( $margin, 'left', 'tablet' ),
			'margin-right'  => smarttoolz_responsive_spacing( $margin, 'right', 'tablet' ),
		),
	);

	$css_output_mobile = array(
		'.ast-footer-copyright' => array(
			'text-align' => $mobile_alignment,
		),
		$selector               => array(
			// Margin CSS.
			'margin-top'    => smarttoolz_responsive_spacing( $margin, 'top', 'mobile' ),
			'margin-bottom' => smarttoolz_responsive_spacing( $margin, 'bottom', 'mobile' ),
			'margin-left'   => smarttoolz_responsive_spacing( $margin, 'left', 'mobile' ),
			'margin-right'  => smarttoolz_responsive_spacing( $margin, 'right', 'mobile' ),
		),
	);

	/* Parse CSS from array() */
	$css_output  = smarttoolz_parse_css( $css_output_desktop );
	$css_output .= smarttoolz_parse_css( $css_output_tablet, '', smarttoolz_get_tablet_breakpoint() );
	$css_output .= smarttoolz_parse_css( $css_output_mobile, '', smarttoolz_get_mobile_breakpoint() );

	$dynamic_css .= $css_output;

	$dynamic_css .= SmartToolz_Builder_Base_Dynamic_CSS::prepare_advanced_typography_css( $_section, $selector );

	$dynamic_css .= SmartToolz_Builder_Base_Dynamic_CSS::prepare_visibility_css( $_section, $visibility_selector );

	return $dynamic_css;
}
