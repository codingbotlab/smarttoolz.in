<?php
/**
 * Theme Hook Alliance hook stub list.
 *
 * @see  https://github.com/zamoose/themehookalliance
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Themes and Plugins can check for smarttoolz_hooks using current_theme_supports( 'smarttoolz_hooks', $hook )
 * to determine whether a theme declares itself to support this specific hook type.
 *
 * Example:
 * <code>
 *      // Declare support for all hook types
 *      add_theme_support( 'smarttoolz_hooks', array( 'all' ) );
 *
 *      // Declare support for certain hook types only
 *      add_theme_support( 'smarttoolz_hooks', array( 'header', 'content', 'footer' ) );
 * </code>
 */
add_theme_support(
	'smarttoolz_hooks',
	array(

		/**
		 * As a Theme developer, use the 'all' parameter, to declare support for all
		 * hook types.
		 * Please make sure you then actually reference all the hooks in this file,
		 * Plugin developers depend on it!
		 */
		'all',

		/**
		 * Themes can also choose to only support certain hook types.
		 * Please make sure you then actually reference all the hooks in this type
		 * family.
		 *
		 * When the 'all' parameter was set, specific hook types do not need to be
		 * added explicitly.
		 */
		'html',
		'body',
		'head',
		'header',
		'content',
		'entry',
		'comments',
		'sidebars',
		'sidebar',
		'footer',

	/**
	 * If/when WordPress Core implements similar methodology, Themes and Plugins
	 * will be able to check whether the version of THA supplied by the theme
	 * supports Core hooks.
	 */
	)
);

/**
 * Determines, whether the specific hook type is actually supported.
 *
 * Plugin developers should always check for the support of a <strong>specific</strong>
 * hook type before hooking a callback function to a hook of this type.
 *
 * Example:
 * <code>
 *      if ( current_theme_supports( 'smarttoolz_hooks', 'header' ) )
 *          add_action( 'smarttoolz_head_top', 'prefix_header_top' );
 * </code>
 *
 * @param bool  $bool true.
 * @param array $args The hook type being checked.
 * @param array $registered All registered hook types.
 *
 * @return bool
 */
function smarttoolz_current_theme_supports( $bool, $args, $registered ) {
	return in_array( $args[0], $registered[0] ) || in_array( 'all', $registered[0] );
}
add_filter( 'current_theme_supports-smarttoolz_hooks', 'smarttoolz_current_theme_supports', 10, 3 );

/**
 * HTML <html> hook
 * Special case, useful for <DOCTYPE>, etc.
 * $smarttoolz_supports[] = 'html;
 */
function smarttoolz_html_before() {
	do_action( 'smarttoolz_html_before' );
}
/**
 * HTML <body> hooks
 * $smarttoolz_supports[] = 'body';
 */
function smarttoolz_body_top() {
	do_action( 'smarttoolz_body_top' );
}

/**
 * Body Bottom
 */
function smarttoolz_body_bottom() {
	do_action( 'smarttoolz_body_bottom' );
}

/**
 * HTML <head> hooks
 *
 * $smarttoolz_supports[] = 'head';
 */
function smarttoolz_head_top() {
	do_action( 'smarttoolz_head_top' );
}

/**
 * Head Bottom
 */
function smarttoolz_head_bottom() {
	do_action( 'smarttoolz_head_bottom' );
}

/**
 * Semantic <header> hooks
 *
 * $smarttoolz_supports[] = 'header';
 */
function smarttoolz_header_before() {
	do_action( 'smarttoolz_header_before' );
}

/**
 * Site Header
 */
function smarttoolz_header() {
	do_action( 'smarttoolz_header' );
}

/**
 * Masthead Top
 */
function smarttoolz_masthead_top() {
	do_action( 'smarttoolz_masthead_top' );
}

/**
 * Masthead
 */
function smarttoolz_masthead() {
	do_action( 'smarttoolz_masthead' );
}

/**
 * Masthead Bottom
 */
function smarttoolz_masthead_bottom() {
	do_action( 'smarttoolz_masthead_bottom' );
}

/**
 * Header After
 */
function smarttoolz_header_after() {
	do_action( 'smarttoolz_header_after' );
}

/**
 * Main Header bar top
 */
function smarttoolz_main_header_bar_top() {
	do_action( 'smarttoolz_main_header_bar_top' );
}

/**
 * Main Header bar bottom
 */
function smarttoolz_main_header_bar_bottom() {
	do_action( 'smarttoolz_main_header_bar_bottom' );
}

/**
 * Main Header Content
 */
function smarttoolz_masthead_content() {
	do_action( 'smarttoolz_masthead_content' );
}
/**
 * Main toggle button before
 */
function smarttoolz_masthead_toggle_buttons_before() {
	do_action( 'smarttoolz_masthead_toggle_buttons_before' );
}

/**
 * Main toggle buttons
 */
function smarttoolz_masthead_toggle_buttons() {
	do_action( 'smarttoolz_masthead_toggle_buttons' );
}

/**
 * Main toggle button after
 */
function smarttoolz_masthead_toggle_buttons_after() {
	do_action( 'smarttoolz_masthead_toggle_buttons_after' );
}

/**
 * Semantic <content> hooks
 *
 * $smarttoolz_supports[] = 'content';
 */
function smarttoolz_content_before() {
	do_action( 'smarttoolz_content_before' );
}

/**
 * Content after
 */
function smarttoolz_content_after() {
	do_action( 'smarttoolz_content_after' );
}

/**
 * Content top
 */
function smarttoolz_content_top() {
	do_action( 'smarttoolz_content_top' );
}

/**
 * Content bottom
 */
function smarttoolz_content_bottom() {
	do_action( 'smarttoolz_content_bottom' );
}

/**
 * Content while before
 */
function smarttoolz_content_while_before() {
	do_action( 'smarttoolz_content_while_before' );
}

/**
 * Content loop
 */
function smarttoolz_content_loop() {
	do_action( 'smarttoolz_content_loop' );
}

/**
 * Conten Page Loop.
 *
 * Called from page.php
 */
function smarttoolz_content_page_loop() {
	do_action( 'smarttoolz_content_page_loop' );
}

/**
 * Content while after
 */
function smarttoolz_content_while_after() {
	do_action( 'smarttoolz_content_while_after' );
}

/**
 * Semantic <entry> hooks
 *
 * $smarttoolz_supports[] = 'entry';
 */
function smarttoolz_entry_before() {
	do_action( 'smarttoolz_entry_before' );
}

/**
 * Entry after
 */
function smarttoolz_entry_after() {
	do_action( 'smarttoolz_entry_after' );
}

/**
 * Entry content before
 */
function smarttoolz_entry_content_before() {
	do_action( 'smarttoolz_entry_content_before' );
}

/**
 * Entry content after
 */
function smarttoolz_entry_content_after() {
	do_action( 'smarttoolz_entry_content_after' );
}

/**
 * Entry Top
 */
function smarttoolz_entry_top() {
	do_action( 'smarttoolz_entry_top' );
}

/**
 * Entry bottom
 */
function smarttoolz_entry_bottom() {
	do_action( 'smarttoolz_entry_bottom' );
}

/**
 * Single Post Header Before
 */
function smarttoolz_single_header_before() {
	do_action( 'smarttoolz_single_header_before' );
}

/**
 * Single Post Header After
 */
function smarttoolz_single_header_after() {
	do_action( 'smarttoolz_single_header_after' );
}

/**
 * Single Post Header Top
 */
function smarttoolz_single_header_top() {
	do_action( 'smarttoolz_single_header_top' );
}

/**
 * Single Post Header Bottom
 */
function smarttoolz_single_header_bottom() {
	do_action( 'smarttoolz_single_header_bottom' );
}

/**
 * Comments block hooks
 *
 * $smarttoolz_supports[] = 'comments';
 */
function smarttoolz_comments_before() {
	do_action( 'smarttoolz_comments_before' );
}

/**
 * Comments after.
 */
function smarttoolz_comments_after() {
	do_action( 'smarttoolz_comments_after' );
}

/**
 * Semantic <sidebar> hooks
 *
 * $smarttoolz_supports[] = 'sidebar';
 */
function smarttoolz_sidebars_before() {
	do_action( 'smarttoolz_sidebars_before' );
}

/**
 * Sidebars after
 */
function smarttoolz_sidebars_after() {
	do_action( 'smarttoolz_sidebars_after' );
}

/**
 * Semantic <footer> hooks
 *
 * $smarttoolz_supports[] = 'footer';
 */
function smarttoolz_footer() {
	do_action( 'smarttoolz_footer' );
}

/**
 * Footer before
 */
function smarttoolz_footer_before() {
	do_action( 'smarttoolz_footer_before' );
}

/**
 * Footer after
 */
function smarttoolz_footer_after() {
	do_action( 'smarttoolz_footer_after' );
}

/**
 * Footer top
 */
function smarttoolz_footer_content_top() {
	do_action( 'smarttoolz_footer_content_top' );
}

/**
 * Footer
 */
function smarttoolz_footer_content() {
	do_action( 'smarttoolz_footer_content' );
}

/**
 * Footer bottom
 */
function smarttoolz_footer_content_bottom() {
	do_action( 'smarttoolz_footer_content_bottom' );
}

/**
 * Archive header
 */
function smarttoolz_archive_header() {
	do_action( 'smarttoolz_archive_header' );
}

/**
 * Pagination
 */
function smarttoolz_pagination() {
	do_action( 'smarttoolz_pagination' );
}

/**
 * Entry content single
 */
function smarttoolz_entry_content_single() {
	do_action( 'smarttoolz_entry_content_single' );
}

/**
 * Entry content single-page.
 *
 * @since 4.0.0
 */
function smarttoolz_entry_content_single_page() {
	do_action( 'smarttoolz_entry_content_single_page' );
}

/**
 * 404
 */
function smarttoolz_entry_content_404_page() {
	do_action( 'smarttoolz_entry_content_404_page' );
}

/**
 * Entry content blog
 */
function smarttoolz_entry_content_blog() {
	do_action( 'smarttoolz_entry_content_blog' );
}

/**
 * Blog featured post section
 */
function smarttoolz_blog_post_featured_format() {
	do_action( 'smarttoolz_blog_post_featured_format' );
}

/**
 * Primary Content Top
 */
function smarttoolz_primary_content_top() {
	do_action( 'smarttoolz_primary_content_top' );
}

/**
 * Primary Content Bottom
 */
function smarttoolz_primary_content_bottom() {
	do_action( 'smarttoolz_primary_content_bottom' );
}

/**
 * 404 Page content template action.
 */
function smarttoolz_404_content_template() {
	do_action( 'smarttoolz_404_content_template' );
}

if ( ! function_exists( 'wp_body_open' ) ) {

	/**
	 * Fire the wp_body_open action.
	 * Adds backward compatibility for WordPress versions < 5.2
	 *
	 * @since 1.8.7
	 */
	function wp_body_open() { // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound
		do_action( 'wp_body_open' ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	}
}
