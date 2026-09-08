<?php
/**
 * Template for Small Footer Layout 2
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 1.0.0
 */

$smarttoolz_footer_section_1 = smarttoolz_get_small_footer( 'footer-sml-section-1' );
$smarttoolz_footer_section_2 = smarttoolz_get_small_footer( 'footer-sml-section-2' );
$smarttoolz_footer_sections  = 0;

if ( '' != $smarttoolz_footer_section_1 ) {
	$smarttoolz_footer_sections++;
}

if ( '' != $smarttoolz_footer_section_2 ) {
	$smarttoolz_footer_sections++;
}

switch ( $smarttoolz_footer_sections ) {

	case '2':
			$smarttoolz_footer_section_class = 'ast-small-footer-section-equally ' . smarttoolz_attr( 'ast-grid-col-6' );
		break;

	case '1':
	default:
			$smarttoolz_footer_section_class = 'ast-small-footer-section-equally ' . smarttoolz_attr( 'ast-grid-common-col' );
		break;
}
?>

<div class="ast-small-footer footer-sml-layout-2">
	<div class="ast-footer-overlay">
		<div class="ast-container">
			<div class="ast-small-footer-wrap" >
					<div class="ast-row ast-flex">

					<?php if ( $smarttoolz_footer_section_1 ) { ?>
						<div class="ast-small-footer-section ast-small-footer-section-1 <?php echo esc_attr( $smarttoolz_footer_section_class ); ?>" >
							<?php
								echo $smarttoolz_footer_section_1; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
						</div>
					<?php } ?>

					<?php if ( $smarttoolz_footer_section_2 ) { ?>
						<div class="ast-small-footer-section ast-small-footer-section-2 <?php echo esc_attr( $smarttoolz_footer_section_class ); ?>" >
							<?php
								echo $smarttoolz_footer_section_2; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							?>
						</div>
					<?php } ?>

					</div> <!-- .ast-row.ast-flex -->
			</div><!-- .ast-small-footer-wrap -->
		</div><!-- .ast-container -->
	</div><!-- .ast-footer-overlay -->
</div><!-- .ast-small-footer-->
