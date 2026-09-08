<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package SmartToolz
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$smarttoolz_sidebar = apply_filters( 'smarttoolz_get_sidebar', 'sidebar-1' );

echo '<div ';
	echo wp_kses_post(
		smarttoolz_attr(
			'sidebar',
			array(
				'id'    => 'secondary',
				'class' => join( ' ', smarttoolz_get_secondary_class() ),
			)
		)
	);
	echo '>';
	?>

	<div class="sidebar-main" <?php echo apply_filters( 'smarttoolz_sidebar_data_attrs', '', $smarttoolz_sidebar ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped, Generic.Commenting.DocComment.MissingShort ?>>
		<?php smarttoolz_sidebars_before(); ?>

		<?php

		if ( is_active_sidebar( $smarttoolz_sidebar ) ) {
				dynamic_sidebar( $smarttoolz_sidebar );
		}

		smarttoolz_sidebars_after();
		?>

	</div><!-- .sidebar-main -->
</div><!-- #secondary -->
