<?php
/**
 * Template for Single post
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 1.0.0
 */

?>

<div <?php smarttoolz_blog_layout_class( 'single-layout-1' ); ?>>

	<?php smarttoolz_single_header_before(); ?>

	<?php if ( apply_filters( 'smarttoolz_single_layout_one_banner_visibility', true ) ) { ?>

		<header class="entry-header <?php smarttoolz_entry_header_class(); ?>">

			<?php smarttoolz_single_header_top(); ?>

			<?php smarttoolz_banner_elements_order(); ?>

			<?php smarttoolz_single_header_bottom(); ?>

		</header><!-- .entry-header -->

	<?php } ?>

	<?php smarttoolz_single_header_after(); ?>

	<div class="entry-content clear"
	<?php
				echo wp_kses_post(
					smarttoolz_attr(
						'article-entry-content-single-layout',
						array(
							'class' => '',
						)
					)
				);
				?>
	>

		<?php smarttoolz_entry_content_before(); ?>

		<?php the_content(); ?>

		<?php
			smarttoolz_edit_post_link(
				sprintf(
					/* translators: %s: Name of current post */
					esc_html__( 'Edit %s', 'smarttoolz' ),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				),
				'<span class="edit-link">',
				'</span>'
			);
			?>

		<?php smarttoolz_entry_content_after(); ?>

		<?php
			wp_link_pages(
				array(
					'before'      => '<div class="page-links">' . esc_html( smarttoolz_default_strings( 'string-single-page-links-before', false ) ),
					'after'       => '</div>',
					'link_before' => '<span class="page-link">',
					'link_after'  => '</span>',
				)
			);
			?>
	</div><!-- .entry-content .clear -->
</div>
