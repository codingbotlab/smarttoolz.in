<?php
/**
 * Site Identity - Dynamic CSS
 *
 * @package SmartToolz
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Site Identity
 */
add_filter( 'smarttoolz_dynamic_theme_css', 'smarttoolz_hb_site_identity_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          SmartToolz Dynamic CSS.
 * @param  string $dynamic_css_filtered SmartToolz Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Site Identity.
 *
 * @since 3.0.0
 */
function smarttoolz_hb_site_identity_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	if ( ! SmartToolz_Builder_Helper::is_component_loaded( 'logo', 'header' ) ) {
		return $dynamic_css;
	}

	$_section            = 'title_tagline';
	$selector            = '.ast-builder-layout-element .ast-site-identity';
	$visibility_selector = '.ast-builder-layout-element[data-section="title_tagline"]';
	$margin              = smarttoolz_get_option( $_section . '-margin' );

	// Desktop CSS.
	$css_output_desktop = array(

		$selector => array(

			// Margin CSS.
			'margin-top'    => smarttoolz_responsive_spacing( $margin, 'top', 'desktop' ),
			'margin-bottom' => smarttoolz_responsive_spacing( $margin, 'bottom', 'desktop' ),
			'margin-left'   => smarttoolz_responsive_spacing( $margin, 'left', 'desktop' ),
			'margin-right'  => smarttoolz_responsive_spacing( $margin, 'right', 'desktop' ),
		),
	);

	// Tablet CSS.
	$css_output_tablet = array(

		$selector => array(

			// Margin CSS.
			'margin-top'    => smarttoolz_responsive_spacing( $margin, 'top', 'tablet' ),
			'margin-bottom' => smarttoolz_responsive_spacing( $margin, 'bottom', 'tablet' ),
			'margin-left'   => smarttoolz_responsive_spacing( $margin, 'left', 'tablet' ),
			'margin-right'  => smarttoolz_responsive_spacing( $margin, 'right', 'tablet' ),
		),
	);

	// Mobile CSS.
	$css_output_mobile = array(

		$selector => array(

			// Margin CSS.
			'margin-top'    => smarttoolz_responsive_spacing( $margin, 'top', 'mobile' ),
			'margin-bottom' => smarttoolz_responsive_spacing( $margin, 'bottom', 'mobile' ),
			'margin-left'   => smarttoolz_responsive_spacing( $margin, 'left', 'mobile' ),
			'margin-right'  => smarttoolz_responsive_spacing( $margin, 'right', 'mobile' ),
		),
	);

	$css_output  = smarttoolz_parse_css( $css_output_desktop );
	$css_output .= smarttoolz_parse_css( $css_output_tablet, '', smarttoolz_get_tablet_breakpoint() );
	$css_output .= smarttoolz_parse_css( $css_output_mobile, '', smarttoolz_get_mobile_breakpoint() );

	$dynamic_css .= $css_output;
	$dynamic_css .= SmartToolz_Builder_Base_Dynamic_CSS::prepare_visibility_css( $_section, $visibility_selector );

	return $dynamic_css;
}
