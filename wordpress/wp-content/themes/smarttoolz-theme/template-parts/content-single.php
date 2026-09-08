<?php
/**
 * Template part for displaying single posts.
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
				'article-single',
				array(
					'id'    => 'post-' . get_the_id(),
					'class' => join( ' ', get_post_class() ),
				)
			)
		);
		?>
>

	<?php smarttoolz_entry_top(); ?>

	<?php smarttoolz_entry_content_single(); ?>

	<?php smarttoolz_entry_bottom(); ?>

</article><!-- #post-## -->

<?php smarttoolz_entry_after(); ?>
