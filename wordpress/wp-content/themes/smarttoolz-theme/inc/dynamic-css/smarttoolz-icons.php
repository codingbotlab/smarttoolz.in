<?php
/**
 * SmartToolz Icons - Dynamic CSS.
 *
 * @package smarttoolz
 * @since 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_filter( 'smarttoolz_dynamic_theme_css', 'smarttoolz_icons_static_css' );

/**
 * SmartToolz Icons - Dynamic CSS.
 *
 * @param string $dynamic_css Dynamic CSS.
 * @since 3.5.0
 */
function smarttoolz_icons_static_css( $dynamic_css ) {

	if ( false === SmartToolz_Icons::is_svg_icons() ) {
		$smarttoolz_icons         = '
        .smarttoolz-icon-down_arrow::after {
            content: "\e900";
            font-family: SmartToolz;
        }
        .smarttoolz-icon-close::after {
            content: "\e5cd";
            font-family: SmartToolz;
        }
        .smarttoolz-icon-drag_handle::after {
            content: "\e25d";
            font-family: SmartToolz;
        }
        .smarttoolz-icon-format_align_justify::after {
            content: "\e235";
            font-family: SmartToolz;
        }
        .smarttoolz-icon-menu::after {
            content: "\e5d2";
            font-family: SmartToolz;
        }
        .smarttoolz-icon-reorder::after {
            content: "\e8fe";
            font-family: SmartToolz;
        }
        .smarttoolz-icon-search::after {
            content: "\e8b6";
            font-family: SmartToolz;
        }
        .smarttoolz-icon-zoom_in::after {
            content: "\e56b";
            font-family: SmartToolz;
        }
        .smarttoolz-icon-check-circle::after {
            content: "\e901";
            font-family: SmartToolz;
        }
        .smarttoolz-icon-shopping-cart::after {
            content: "\f07a";
            font-family: SmartToolz;
        }
        .smarttoolz-icon-shopping-bag::after {
            content: "\f290";
            font-family: SmartToolz;
        }
        .smarttoolz-icon-shopping-basket::after {
            content: "\f291";
            font-family: SmartToolz;
        }
        .smarttoolz-icon-circle-o::after {
            content: "\e903";
            font-family: SmartToolz;
        }
        .smarttoolz-icon-certificate::after {
            content: "\e902";
            font-family: SmartToolz;
        }';
		return $dynamic_css .= SmartToolz_Enqueue_Scripts::trim_css( $smarttoolz_icons );
	}
	return $dynamic_css;
}
