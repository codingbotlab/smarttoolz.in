<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package SmartToolz
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'smarttoolz_entry_footer' ) ) {
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function smarttoolz_entry_footer() {

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';

			/**
			 * Get default strings.
			 *
			 * @see smarttoolz_default_strings
			 */
			comments_popup_link( smarttoolz_default_strings( 'string-blog-meta-leave-a-comment', false ), smarttoolz_default_strings( 'string-blog-meta-one-comment', false ), smarttoolz_default_strings( 'string-blog-meta-multiple-comment', false ) );
			echo '</span>';
		}

		smarttoolz_edit_post_link(
			sprintf(
				/* translators: %s: Name of current post */
				esc_html__( 'Edit %s', 'smarttoolz' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
}
