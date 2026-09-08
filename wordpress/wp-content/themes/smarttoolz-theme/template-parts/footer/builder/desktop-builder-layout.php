<?php
/**
 * Template part for displaying the footer info.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package SmartToolz
 * @since 1.0.0
 */

?>
<footer
<?php
echo wp_kses_post(
	smarttoolz_attr(
		'footer',
		array(
			'id'    => 'colophon',
			'class' => join(
				' ',
				smarttoolz_get_footer_classes()
			),
		)
	)
);
?>
>
	<?php
		smarttoolz_footer_content_top();
	?>
		<?php
		/**
		 * SmartToolz Top footer
		 */
		do_action( 'smarttoolz_above_footer' );
		/**
		 * SmartToolz Middle footer
		 */
		do_action( 'smarttoolz_primary_footer' );
		/**
		 * SmartToolz Bottom footer
		 */
		do_action( 'smarttoolz_below_footer' );
		?>
	<?php
		smarttoolz_footer_content_bottom();
	?>
</footer><!-- #colophon -->
