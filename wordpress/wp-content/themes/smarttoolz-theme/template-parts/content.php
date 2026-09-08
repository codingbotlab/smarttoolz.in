<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package SmartToolz
 * @since 1.0.0
 */

?>

<?php smarttoolz_entry_before(); ?>

<article
<?php
		echo wp_kses_post(
			smarttoolz_attr(
				'article-content',
				array(
					'id'    => 'post-' . get_the_id(),
					'class' => join( ' ', get_post_class() ),
				)
			)
		);
		?>
>
	<?php smarttoolz_entry_top(); ?>

	<header class="entry-header <?php smarttoolz_entry_header_class(); ?>">

		<?php
		smarttoolz_the_title(
			sprintf(
				'<h2 class="entry-title" ' . smarttoolz_attr(
					'article-title-content',
					array(
						'class' => '',
					)
				) . '><a href="%s" rel="bookmark">',
				esc_url( get_permalink() )
			),
			'</a></h2>'
		);
		?>

	</header><!-- .entry-header -->

	<div class="entry-content clear"
	<?php
				echo wp_kses_post(
					smarttoolz_attr(
						'article-entry-content',
						array(
							'class' => '',
						)
					)
				);
				?>
	>

		<?php smarttoolz_entry_content_before(); ?>

		<?php
			the_content(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. */
						__( 'Continue reading %s', 'smarttoolz' ) . ' <span class="meta-nav">&rarr;</span>',
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					the_title( '<span class="screen-reader-text">"', '"</span>', false )
				)
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

	<footer class="entry-footer">
		<?php smarttoolz_entry_footer(); ?>
	</footer><!-- .entry-footer -->

	<?php smarttoolz_entry_bottom(); ?>

</article><!-- #post-## -->

<?php smarttoolz_entry_after(); ?>
