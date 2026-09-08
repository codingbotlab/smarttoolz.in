<?php
/**
 * Template for 404
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 1.0.0
 */

$smarttoolz_404_subtitle_tag = true === smarttoolz_check_is_structural_setup() ? 'h3' : 'div';

?>
<div <?php echo wp_kses_post( smarttoolz_attr( '404_page', array( 'class' => 'ast-404-layout-1' ) ) ); ?> >

	<?php smarttoolz_the_title( '<header class="page-header"><h1 class="page-title">', '</h1></header><!-- .page-header -->' ); ?>

	<div class="page-content">

		<<?php echo esc_attr( $smarttoolz_404_subtitle_tag ); ?> class="page-sub-title">
			<?php echo esc_html( smarttoolz_default_strings( 'string-404-sub-title', false ) ); ?>
		</<?php echo esc_attr( $smarttoolz_404_subtitle_tag ); ?>>

		<div class="ast-404-search">
			<?php the_widget( 'WP_Widget_Search' ); ?>
		</div>

	</div><!-- .page-content -->
</div>
