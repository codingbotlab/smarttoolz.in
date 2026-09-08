<?php
/**
 * Deprecated Filters of SmartToolz Theme.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 1.0.23
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Deprecating smarttoolz_color_palletes filter.
add_filter( 'smarttoolz_color_palettes', 'smarttoolz_deprecated_color_palette', 10, 1 );

/**
 * SmartToolz Color Palettes
 *
 * @since 1.0.23
 * @param array $color_palette  customizer color palettes.
 * @return array  $color_palette updated customizer color palettes.
 */
function smarttoolz_deprecated_color_palette( $color_palette ) {
	return smarttoolz_apply_filters_deprecated( 'smarttoolz_color_palletes', array( $color_palette ), '1.0.22', 'smarttoolz_color_palettes', '' );
}

// Deprecating smarttoolz_sigle_post_navigation_enabled filter.
add_filter( 'smarttoolz_single_post_navigation_enabled', 'smarttoolz_deprecated_sigle_post_navigation_enabled', 10, 1 );

/**
 * SmartToolz Single Post Navigation
 *
 * @since 1.0.27
 * @param bool $post_nav true | false.
 * @return bool $post_nav true for enabled | false for disable.
 */
function smarttoolz_deprecated_sigle_post_navigation_enabled( $post_nav ) {
	return smarttoolz_apply_filters_deprecated( 'smarttoolz_sigle_post_navigation_enabled', array( $post_nav ), '1.0.27', 'smarttoolz_single_post_navigation_enabled', '' );
}

// Deprecating smarttoolz_primary_header_main_rt_section filter.
add_filter( 'smarttoolz_header_section_elements', 'smarttoolz_deprecated_primary_header_main_rt_section', 10, 2 );

/**
 * SmartToolz Header elements.
 *
 * @since 1.2.2
 * @param array  $elements List of elements.
 * @param string $header Header section type.
 * @return array
 */
function smarttoolz_deprecated_primary_header_main_rt_section( $elements, $header ) {
	return smarttoolz_apply_filters_deprecated( 'smarttoolz_primary_header_main_rt_section', array( $elements, $header ), '1.2.2', 'smarttoolz_header_section_elements', '' );
}

if ( ! function_exists( 'smarttoolz_apply_filters_deprecated' ) ) {
	/**
	 * SmartToolz Filter Deprecated
	 *
	 * @since 1.1.1
	 * @param string $tag         The name of the filter hook.
	 * @psalm-param non-empty-string $tag
	 * @param array  $args        Array of additional function arguments to be passed to apply_filters().
	 * @param string $version     The version of WordPress that deprecated the hook.
	 * @param string $replacement Optional. The hook that should have been used. Default false.
	 * @param string $message     Optional. A message regarding the change. Default null.
	 */
	function smarttoolz_apply_filters_deprecated( $tag, $args, $version, $replacement = false, $message = null ) {
		if ( function_exists( 'apply_filters_deprecated' ) ) { /* WP >= 4.6 */
			return apply_filters_deprecated( $tag, $args, $version, $replacement, $message );
		}
		return apply_filters_ref_array( $tag, $args ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound
	}
}

// Deprecating ast_footer_bar_display filter.
add_filter( 'smarttoolz_footer_bar_display', 'smarttoolz_deprecated_ast_footer_bar_display_filter', 10, 1 );

/**
 * Display footer builder markup.
 *
 * @since 3.7.4
 * @param bool $display_footer true | false.
 * @return bool true for enabled | false for disable.
 */
function smarttoolz_deprecated_ast_footer_bar_display_filter( $display_footer ) {
	return smarttoolz_apply_filters_deprecated( 'ast_footer_bar_display', array( $display_footer ), '3.7.4', 'smarttoolz_footer_bar_display', '' );
}

// Deprecating ast_main_header_display filter.
add_filter( 'smarttoolz_main_header_display', 'smarttoolz_deprecated_ast_main_header_display_filter', 10, 1 );

/**
 * Display header builder markup.
 *
 * @since 3.7.4
 * @param bool $display_header true | false.
 * @return bool true for enabled | false for disable.
 */
function smarttoolz_deprecated_ast_main_header_display_filter( $display_header ) {
	return smarttoolz_apply_filters_deprecated( 'ast_main_header_display', array( $display_header ), '3.7.4', 'smarttoolz_main_header_display', '' );
}

// Deprecating secondary_submenu_border_class filter.
add_filter( 'smarttoolz_secondary_submenu_border_class', 'smarttoolz_deprecated_secondary_submenu_border_class_filter', 10, 1 );

/**
 * Border class to secondary submenu
 *
 * @since 3.7.4
 * @param string $class_selector custom class assigned to secondary submenu.
 * @return string  $class_selector updated class selector.
 */
function smarttoolz_deprecated_secondary_submenu_border_class_filter( $class_selector ) {
	return smarttoolz_apply_filters_deprecated( 'secondary_submenu_border_class', array( $class_selector ), '3.7.4', 'smarttoolz_secondary_submenu_border_class', '' );
}

// Deprecating gtn_image_group_css_comp filter.
add_filter( 'smarttoolz_gutenberg_image_group_style_support', 'smarttoolz_deprecated_gtn_image_group_css_comp_filter', 10, 1 );

/**
 * Image, group compatibility support released in v2.4.4.
 *
 * @since 3.7.4
 * @param bool $block_support true | false.
 * @return bool true for enabled | false for disable.
 */
function smarttoolz_deprecated_gtn_image_group_css_comp_filter( $block_support ) {
	return smarttoolz_apply_filters_deprecated( 'gtn_image_group_css_comp', array( $block_support ), '3.7.4', 'smarttoolz_gutenberg_image_group_style_support', '' );
}

// Deprecating ast_footer_sml_layout filter.
add_filter( 'smarttoolz_footer_sml_layout', 'smarttoolz_deprecated_ast_footer_sml_layout_filter', 10, 1 );

/**
 * Footer bar meta setting option.
 *
 * @since 3.7.4
 * @param bool $display_footer_bar true | false.
 * @return bool true for enabled | false for disable.
 */
function smarttoolz_deprecated_ast_footer_sml_layout_filter( $display_footer_bar ) {
	return smarttoolz_apply_filters_deprecated( 'ast_footer_sml_layout', array( $display_footer_bar ), '3.7.4', 'smarttoolz_footer_sml_layout', '' );
}

// Deprecating primary_submenu_border_class filter.
add_filter( 'smarttoolz_primary_submenu_border_class', 'smarttoolz_deprecated_primary_submenu_border_class_filter', 10, 1 );

/**
 * Border class to primary submenu
 *
 * @since 3.7.4
 * @param string $class_selector custom class assigned to primary submenu.
 * @return string  $class_selector updated class selector.
 */
function smarttoolz_deprecated_primary_submenu_border_class_filter( $class_selector ) {
	return smarttoolz_apply_filters_deprecated( 'primary_submenu_border_class', array( $class_selector ), '3.7.4', 'smarttoolz_primary_submenu_border_class', '' );
}

// Deprecating smarttoolz_single_banner_post_meta filter.
add_filter( 'smarttoolz_single_post_meta', 'smarttoolz_deprecated_smarttoolz_single_banner_post_meta_filter', 10, 1 );

/**
 * Single meta markup filter.
 *
 * @since 4.0.2
 * @param string $meta_markup Markup of meta.
 * @return string  $meta_markup Markup of meta.
 */
function smarttoolz_deprecated_smarttoolz_single_banner_post_meta_filter( $meta_markup ) {
	return smarttoolz_apply_filters_deprecated( 'smarttoolz_single_banner_post_meta', array( $meta_markup ), '4.0.2', 'smarttoolz_single_post_meta', '' );
}

// Deprecating smarttoolz_get_option_dynamic-blog-layouts filter.
add_filter( 'smarttoolz_get_option_dynamic_blog_layouts', 'smarttoolz_deprecated_smarttoolz_get_option_dynamic_blog_layouts_filter', 10, 1 );

/**
 * Don't apply direct new layouts to legacy users.
 *
 * @since 4.1.0
 * @param string $dynamic_blog_layout false if it is an existing user , true if not.
 * @return bool  $dynamic_blog_layout false if it is an existing user , true if not.
 */
function smarttoolz_deprecated_smarttoolz_get_option_dynamic_blog_layouts_filter( $dynamic_blog_layout ) {
	return smarttoolz_apply_filters_deprecated( 'smarttoolz_get_option_dynamic-blog-layouts', array( $dynamic_blog_layout ), '4.1.0', 'smarttoolz_get_option_dynamic_blog_layouts', '' );
}
