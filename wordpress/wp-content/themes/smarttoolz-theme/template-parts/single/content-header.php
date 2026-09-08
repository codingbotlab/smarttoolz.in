<?php
/**
 * Template for Single Page
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 4.0.0
 */

if ( apply_filters( 'smarttoolz_single_layout_one_banner_visibility', true ) ) {

	if ( ! ( is_front_page() && 'page' === get_option( 'show_on_front' ) && smarttoolz_get_option( 'ast-dynamic-single-page-disable-structure-meta-on-front-page', false ) ) ) {
		?>
			<header class="entry-header <?php smarttoolz_entry_header_class(); ?>">
				<?php smarttoolz_banner_elements_order(); ?>
			</header> <!-- .entry-header -->
		<?php
	}
}
?>

<div class="entry-content clear"
	<?php
			echo wp_kses_post(
				smarttoolz_attr(
					'article-entry-content-page',
					array(
						'class' => '',
					)
				)
			);
			?>
>

	<?php smarttoolz_entry_content_before(); ?>

	<?php the_content(); ?>

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
