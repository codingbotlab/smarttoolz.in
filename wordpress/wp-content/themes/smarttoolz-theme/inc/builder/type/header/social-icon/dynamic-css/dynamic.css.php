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
add_filter( 'smarttoolz_dynamic_theme_css', 'smarttoolz_hb_social_icon_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          SmartToolz Dynamic CSS.
 * @param  string $dynamic_css_filtered SmartToolz Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Heading Colors.
 *
 * @since 2.1.4
 */
function smarttoolz_hb_social_icon_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	$dynamic_css .= SmartToolz_Social_Component_Dynamic_CSS::smarttoolz_social_dynamic_css( 'header' );

	return $dynamic_css;
}
