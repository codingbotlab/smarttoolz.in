<?php
/**
 * Template part for displaying page content in page.php.
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
				'article-page',
				array(
					'id'    => 'post-' . get_the_id(),
					'class' => join( ' ', get_post_class() ),
				)
			)
		);
		?>
>
	<?php smarttoolz_entry_top(); ?>

	<?php smarttoolz_entry_content_single_page(); ?>

	<?php
		smarttoolz_edit_post_link(
			sprintf(
				/* translators: %s: Name of current post */
				esc_html__( 'Edit %s', 'smarttoolz' ),
				the_title( '<span class="screen-reader-text">"', '"</span>', false )
			),
			'<footer class="entry-footer"><span class="edit-link">',
			'</span></footer><!-- .entry-footer -->'
		);
		?>

	<?php smarttoolz_entry_bottom(); ?>

</article><!-- #post-## -->

<?php smarttoolz_entry_after(); ?>
