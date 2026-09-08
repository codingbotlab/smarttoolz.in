<?php
/**
 * BuddyPress Compatibility File.
 *
 * @package SmartToolz
 * @since 4.11.18
 */

// If plugin - 'BuddyPress' not exist then return.
if ( ! class_exists( 'BuddyPress' ) ) {
	return;
}

/**
 * SmartToolz BuddyPress Compatibility
 *
 * @since 4.11.18
 */
class SmartToolz_BuddyPress {
	/**
	 * Constructor
	 *
	 * @since 4.11.18
	 */
	public function __construct() {
		add_filter( 'smarttoolz_page_layout', array( $this, 'buddypress_page_layout' ), 20 );
		add_filter( 'smarttoolz_get_content_layout', array( $this, 'buddypress_content_layout' ), 20 );
		add_filter( 'smarttoolz_is_content_layout_boxed', array( $this, 'buddypress_content_layout_boxed' ), 20 );
		add_filter( 'smarttoolz_is_sidebar_layout_boxed', array( $this, 'buddypress_sidebar_layout_boxed' ), 20 );
	}

	/**
	 * Check if current page is a BuddyPress directory page.
	 *
	 * @return bool
	 * @since 4.11.18
	 * @psalm-suppress UndefinedFunction
	 */
	private function is_bp_directory() {
		return function_exists( 'is_buddypress' ) && function_exists( 'bp_is_directory' ) && is_buddypress() && bp_is_directory();
	}

	/**
	 * Filter sidebar layout for BuddyPress pages.
	 *
	 * @param string $layout Layout.
	 * @return string
	 * @since 4.11.18
	 */
	public function buddypress_page_layout( $layout ) {
		if ( ! $this->is_bp_directory() ) {
			return $layout;
		}

		$sidebar_layout = smarttoolz_get_option( 'archive-buddypress-sidebar-layout', 'default' );
		$content_layout = smarttoolz_get_option( 'archive-buddypress-ast-content-layout', 'default' );

		if ( 'normal-width-container' === $content_layout && 'default' !== $sidebar_layout && ! empty( $sidebar_layout ) ) {
			return $sidebar_layout;
		}

		return 'no-sidebar';
	}

	/**
	 * Filter content layout for BuddyPress pages.
	 *
	 * @param string $layout Layout.
	 * @return string
	 * @since 4.11.18
	 */
	public function buddypress_content_layout( $layout ) {
		if ( ! $this->is_bp_directory() ) {
			return $layout;
		}

		$content_layout = smarttoolz_get_option( 'archive-buddypress-ast-content-layout', 'default' );

		if ( 'default' !== $content_layout && ! empty( $content_layout ) ) {
			return smarttoolz_toggle_layout( 'archive-buddypress-ast-content-layout', 'archive' );
		}

		return smarttoolz_toggle_layout( 'ast-site-content-layout', 'global', false );
	}

	/**
	 * Filter content layout boxed for BuddyPress pages.
	 *
	 * @param bool $is_boxed Is boxed layout.
	 * @return bool
	 * @since 4.11.18
	 */
	public function buddypress_content_layout_boxed( $is_boxed ) {
		if ( ! $this->is_bp_directory() ) {
			return $is_boxed;
		}

		$content_style = smarttoolz_get_option( 'archive-buddypress-content-style', 'default' );

		if ( 'default' !== $content_style && ! empty( $content_style ) ) {
			return 'boxed' === $content_style;
		}

		return 'boxed' === smarttoolz_get_option( 'site-content-style', 'unboxed' );
	}

	/**
	 * Filter sidebar layout boxed for BuddyPress pages.
	 *
	 * @param bool $is_sidebar_boxed Is sidebar boxed layout.
	 * @return bool
	 * @since 4.11.18
	 */
	public function buddypress_sidebar_layout_boxed( $is_sidebar_boxed ) {
		if ( ! $this->is_bp_directory() ) {
			return $is_sidebar_boxed;
		}

		$sidebar_style = smarttoolz_get_option( 'archive-buddypress-sidebar-style', 'default' );

		if ( 'default' !== $sidebar_style && ! empty( $sidebar_style ) ) {
			return 'boxed' === $sidebar_style;
		}

		return false;
	}
}

/**
 * Kicking this off by instantiating the class
 */
new SmartToolz_BuddyPress();
