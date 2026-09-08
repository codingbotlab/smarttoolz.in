<?php
/**
 * Social Icons control - Dynamic CSS
 *
 * @package SmartToolz Builder
 * @since 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Social Icons Colors
 */
add_filter( 'smarttoolz_dynamic_theme_css', 'smarttoolz_fb_social_icon_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          SmartToolz Dynamic CSS.
 * @param  string $dynamic_css_filtered SmartToolz Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Social Icons Colors.
 *
 * @since 3.0.0
 */
function smarttoolz_fb_social_icon_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	$dynamic_css .= SmartToolz_Social_Component_Dynamic_CSS::smarttoolz_social_dynamic_css( 'footer' );

	return $dynamic_css;
}
