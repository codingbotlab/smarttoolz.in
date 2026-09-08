<?php
/**
 * Deprecated Functions of SmartToolz Theme.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 1.0.23
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Deprecating footer_menu_static_css function.
 *
 * Footer menu specific static CSS function.
 *
 * @since 3.7.4
 * @deprecated footer_menu_static_css() Use smarttoolz_footer_menu_static_css()
 * @see smarttoolz_footer_menu_static_css()
 *
 * @return string Parsed CSS
 */
function footer_menu_static_css() {
	_deprecated_function( __FUNCTION__, '3.7.4', 'smarttoolz_footer_menu_static_css()' );
	return smarttoolz_footer_menu_static_css();
}

/**
 * Deprecating is_support_footer_widget_right_margin function.
 *
 * Backward managing function based on flag - 'support-footer-widget-right-margin' which fixes right margin issue in builder widgets.
 *
 * @since 3.7.4
 * @deprecated is_support_footer_widget_right_margin() Use smarttoolz_support_footer_widget_right_margin()
 * @see smarttoolz_support_footer_widget_right_margin()
 *
 * @return bool true|false
 */
function is_support_footer_widget_right_margin() {
	_deprecated_function( __FUNCTION__, '3.7.4', 'smarttoolz_support_footer_widget_right_margin()' );
	return smarttoolz_support_footer_widget_right_margin();
}

/**
 * Deprecating prepare_button_defaults function.
 *
 * Default configurations for builder button components.
 *
 * @since 3.7.4
 * @deprecated prepare_button_defaults() Use smarttoolz_prepare_button_defaults()
 * @param array  $defaults Button default configs.
 * @param string $index builder button component index.
 * @see smarttoolz_prepare_button_defaults()
 *
 * @return array
 */
function prepare_button_defaults( $defaults, $index ) {
	_deprecated_function( __FUNCTION__, '3.7.4', 'smarttoolz_prepare_button_defaults()' );
	return smarttoolz_prepare_button_defaults( $defaults, absint( $index ) );
}

/**
 * Deprecating prepare_html_defaults function.
 *
 * Default configurations for builder HTML components.
 *
 * @since 3.7.4
 * @deprecated prepare_html_defaults() Use smarttoolz_prepare_html_defaults()
 * @param array  $defaults HTML default configs.
 * @param string $index builder HTML component index.
 * @see smarttoolz_prepare_html_defaults()
 *
 * @return array
 */
function prepare_html_defaults( $defaults, $index ) {
	_deprecated_function( __FUNCTION__, '3.7.4', 'smarttoolz_prepare_html_defaults()' );
	return smarttoolz_prepare_html_defaults( $defaults, absint( $index ) );
}

/**
 * Deprecating prepare_social_icon_defaults function.
 *
 * Default configurations for builder Social Icon components.
 *
 * @since 3.7.4
 * @deprecated prepare_social_icon_defaults() Use smarttoolz_prepare_social_icon_defaults()
 * @param array  $defaults Social Icon default configs.
 * @param string $index builder Social Icon component index.
 * @see smarttoolz_prepare_social_icon_defaults()
 *
 * @return array
 */
function prepare_social_icon_defaults( $defaults, $index ) {
	_deprecated_function( __FUNCTION__, '3.7.4', 'smarttoolz_prepare_social_icon_defaults()' );
	return smarttoolz_prepare_social_icon_defaults( $defaults, absint( $index ) );
}

/**
 * Deprecating prepare_widget_defaults function.
 *
 * Default configurations for builder Widget components.
 *
 * @since 3.7.4
 * @deprecated prepare_widget_defaults() Use smarttoolz_prepare_widget_defaults()
 * @param array  $defaults Widget default configs.
 * @param string $index builder Widget component index.
 * @see smarttoolz_prepare_widget_defaults()
 *
 * @return array
 */
function prepare_widget_defaults( $defaults, $index ) {
	_deprecated_function( __FUNCTION__, '3.7.4', 'smarttoolz_prepare_widget_defaults()' );
	return smarttoolz_prepare_widget_defaults( $defaults, absint( $index ) );
}

/**
 * Deprecating prepare_menu_defaults function.
 *
 * Default configurations for builder Menu components.
 *
 * @since 3.7.4
 * @deprecated prepare_menu_defaults() Use smarttoolz_prepare_menu_defaults()
 * @param array  $defaults Menu default configs.
 * @param string $index builder Menu component index.
 * @see smarttoolz_prepare_menu_defaults()
 *
 * @return array
 */
function prepare_menu_defaults( $defaults, $index ) {
	_deprecated_function( __FUNCTION__, '3.7.4', 'smarttoolz_prepare_menu_defaults()' );
	return smarttoolz_prepare_menu_defaults( $defaults, absint( $index ) );
}

/**
 * Deprecating prepare_divider_defaults function.
 *
 * Default configurations for builder Divider components.
 *
 * @since 3.7.4
 * @deprecated prepare_divider_defaults() Use smarttoolz_prepare_divider_defaults()
 * @param array  $defaults Divider default configs.
 * @param string $index builder Divider component index.
 * @see smarttoolz_prepare_divider_defaults()
 *
 * @return array
 */
function prepare_divider_defaults( $defaults, $index ) {
	_deprecated_function( __FUNCTION__, '3.7.4', 'smarttoolz_prepare_divider_defaults()' );
	return smarttoolz_prepare_divider_defaults( $defaults, absint( $index ) );
}

/**
 * Deprecating is_smarttoolz_pagination_enabled function.
 *
 * Checking if SmartToolz's pagination enabled.
 *
 * @since 3.7.4
 * @deprecated is_smarttoolz_pagination_enabled() Use smarttoolz_check_pagination_enabled()
 * @see smarttoolz_check_pagination_enabled()
 *
 * @return bool true|false
 */
function is_smarttoolz_pagination_enabled() {
	_deprecated_function( __FUNCTION__, '3.7.4', 'smarttoolz_check_pagination_enabled()' );
	return smarttoolz_check_pagination_enabled();
}

/**
 * Deprecating is_current_post_comment_enabled function.
 *
 * Checking if current post's comment enabled and comment section is open.
 *
 * @since 3.7.4
 * @deprecated is_current_post_comment_enabled() Use smarttoolz_check_current_post_comment_enabled()
 * @see smarttoolz_check_current_post_comment_enabled()
 *
 * @return bool true|false
 */
function is_current_post_comment_enabled() {
	_deprecated_function( __FUNCTION__, '3.7.4', 'smarttoolz_check_current_post_comment_enabled()' );
	return smarttoolz_check_current_post_comment_enabled();
}

/**
 * Deprecating ast_load_preload_local_fonts function.
 *
 * Preload Google Fonts - Feature of self-hosting font.
 *
 * @since 3.7.4
 * @deprecated ast_load_preload_local_fonts() Use smarttoolz_load_preload_local_fonts()
 * @param string $google_font_url Google Font URL generated by customizer config.
 * @see smarttoolz_load_preload_local_fonts()
 *
 * @return string
 */
function ast_load_preload_local_fonts( $google_font_url ) {
	_deprecated_function( __FUNCTION__, '3.7.4', 'smarttoolz_load_preload_local_fonts()' );
	return smarttoolz_load_preload_local_fonts( $google_font_url );
}

/**
 * Deprecating ast_get_webfont_url function.
 *
 * Getting webfont based Google font URL.
 *
 * @since 3.7.4
 * @deprecated ast_get_webfont_url() Use smarttoolz_get_webfont_url()
 * @param string $google_font_url Google Font URL generated by customizer config.
 * @see smarttoolz_get_webfont_url()
 *
 * @return string
 */
function ast_get_webfont_url( $google_font_url ) {
	_deprecated_function( __FUNCTION__, '3.7.4', 'smarttoolz_get_webfont_url()' );
	return smarttoolz_get_webfont_url( $google_font_url );
}
