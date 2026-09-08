<?php
/**
 * SmartToolz Theme Strings
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Default Strings
 */
if ( ! function_exists( 'smarttoolz_default_strings' ) ) {

	/**
	 * Default Strings
	 *
	 * @since 1.0.0
	 * @param  string $key  String key.
	 * @param  bool   $echo Print string.
	 * @return mixed        Return string or nothing.
	 */
	function smarttoolz_default_strings( $key, $echo = true ) {

		$post_comment_dynamic_string = true === SmartToolz_Dynamic_CSS::smarttoolz_core_form_btns_styling() ? __( 'Post Comment', 'smarttoolz' ) : __( 'Post Comment &raquo;', 'smarttoolz' );
		$defaults                    = apply_filters(
			'smarttoolz_default_strings',
			array(

				// Header.
				'string-header-skip-link'                => __( 'Skip to content', 'smarttoolz' ),

				// 404 Page Strings.
				'string-404-sub-title'                   => __( 'It looks like the link pointing here was faulty. Maybe try searching?', 'smarttoolz' ),

				// Search Page Strings.
				'string-search-nothing-found'            => __( 'Nothing Found', 'smarttoolz' ),
				'string-search-nothing-found-message'    => __( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'smarttoolz' ),
				'string-full-width-search-message'       => __( 'Start typing and press enter to search', 'smarttoolz' ),
				'string-full-width-search-placeholder'   => __( 'Search...', 'smarttoolz' ),
				'string-header-cover-search-placeholder' => __( 'Search...', 'smarttoolz' ),
				'string-search-input-placeholder'        => __( 'Search...', 'smarttoolz' ),

				// Comment Template Strings.
				'string-comment-reply-link'              => __( 'Reply', 'smarttoolz' ),
				'string-comment-edit-link'               => __( 'Edit', 'smarttoolz' ),
				'string-comment-awaiting-moderation'     => __( 'Your comment is awaiting moderation.', 'smarttoolz' ),
				'string-comment-title-reply'             => __( 'Leave a Comment', 'smarttoolz' ),
				'string-comment-cancel-reply-link'       => __( 'Cancel Reply', 'smarttoolz' ),
				'string-comment-label-submit'            => $post_comment_dynamic_string,
				'string-comment-label-message'           => __( 'Type here..', 'smarttoolz' ),
				'string-comment-label-name'              => __( 'Name', 'smarttoolz' ),
				'string-comment-label-email'             => __( 'Email', 'smarttoolz' ),
				'string-comment-label-website'           => __( 'Website', 'smarttoolz' ),
				'string-comment-closed'                  => __( 'Comments are closed.', 'smarttoolz' ),
				'string-comment-navigation-title'        => __( 'Comment navigation', 'smarttoolz' ),
				'string-comment-navigation-next'         => __( 'Newer Comments', 'smarttoolz' ),
				'string-comment-navigation-previous'     => __( 'Older Comments', 'smarttoolz' ),

				// Blog Default Strings.
				'string-blog-page-links-before'          => __( 'Pages:', 'smarttoolz' ),
				'string-blog-meta-author-by'             => __( 'By ', 'smarttoolz' ),
				'string-blog-meta-leave-a-comment'       => __( 'Leave a Comment', 'smarttoolz' ),
				'string-blog-meta-one-comment'           => __( '1 Comment', 'smarttoolz' ),
				'string-blog-meta-multiple-comment'      => __( '% Comments', 'smarttoolz' ),
				'string-blog-navigation-next'            => __( 'Next', 'smarttoolz' ) . ' <span class="ast-right-arrow" aria-hidden="true">&rarr;</span>',
				'string-blog-navigation-previous'        => '<span class="ast-left-arrow" aria-hidden="true">&larr;</span> ' . __( 'Previous', 'smarttoolz' ),
				'string-next-text'                       => __( 'Next', 'smarttoolz' ),
				'string-previous-text'                   => __( 'Previous', 'smarttoolz' ),

				// Single Post Default Strings.
				'string-single-page-links-before'        => __( 'Pages:', 'smarttoolz' ),
				/* translators: 1: Post type label */
				'string-single-navigation-next'          => __( 'Next %s', 'smarttoolz' ) . ' <span class="ast-right-arrow" aria-hidden="true">&rarr;</span>',
				/* translators: 1: Post type label */
				'string-single-navigation-previous'      => '<span class="ast-left-arrow" aria-hidden="true">&larr;</span> ' . __( 'Previous %s', 'smarttoolz' ),

				// Content None.
				'string-content-nothing-found-message'   => __( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'smarttoolz' ),

			)
		);

		if ( is_rtl() ) {
			$defaults['string-blog-navigation-next']     = __( 'Next', 'smarttoolz' ) . ' <span class="ast-left-arrow" aria-hidden="true">&larr;</span>';
			$defaults['string-blog-navigation-previous'] = '<span class="ast-right-arrow" aria-hidden="true">&rarr;</span> ' . __( 'Previous', 'smarttoolz' );

			/* translators: 1: Post type label */
			$defaults['string-single-navigation-next'] = __( 'Next %s', 'smarttoolz' ) . ' <span class="ast-left-arrow" aria-hidden="true">&larr;</span>';
			/* translators: 1: Post type label */
			$defaults['string-single-navigation-previous'] = '<span class="ast-right-arrow" aria-hidden="true">&rarr;</span> ' . __( 'Previous %s', 'smarttoolz' );
		}

		$output = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';

		/**
		 * Print or return
		 */
		if ( $echo ) {
			echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $output;
		}
	}
}
