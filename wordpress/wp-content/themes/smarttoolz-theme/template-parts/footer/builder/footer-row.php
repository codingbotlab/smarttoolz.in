<?php
/**
 * Template part for displaying the a row of the footer
 *
 * @package SmartToolz Builder
 */

$smarttoolz_footer_row = get_query_var( 'row' );
if ( smarttoolz_wp_version_compare( '5.4.99', '>=' ) ) {
	$smarttoolz_footer_row = wp_parse_args( $args, array( 'row' => '' ) );
	$smarttoolz_footer_row = isset( $smarttoolz_footer_row['row'] ) ? $smarttoolz_footer_row['row'] : '';
}

if ( SmartToolz_Builder_Helper::is_footer_row_empty( $smarttoolz_footer_row ) ) {

	$smarttoolz_footer_row_option = 'above' === $smarttoolz_footer_row ? 'hba' : ( 'below' === $smarttoolz_footer_row ? 'hbb' : 'hb' );
	$smarttoolz_footer_columns    = smarttoolz_get_option( $smarttoolz_footer_row_option . '-footer-column' );
	$smarttoolz_footer_layout     = smarttoolz_get_option( $smarttoolz_footer_row_option . '-footer-layout' );
	$smarttoolz_row_stack_layout  = smarttoolz_get_option( $smarttoolz_footer_row_option . '-stack' );

	$smarttoolz_row_desk_layout = isset( $smarttoolz_footer_layout['desktop'] ) ? $smarttoolz_footer_layout['desktop'] : 'full';
	$smarttoolz_tab_layout      = isset( $smarttoolz_footer_layout['tablet'] ) ? $smarttoolz_footer_layout['tablet'] : 'full';
	$smarttoolz_mob_layout      = isset( $smarttoolz_footer_layout['mobile'] ) ? $smarttoolz_footer_layout['mobile'] : 'full';

	$smarttoolz_desk_stack_layout = isset( $smarttoolz_row_stack_layout['desktop'] ) ? $smarttoolz_row_stack_layout['desktop'] : 'stack';
	$smarttoolz_tab_stack_layout  = isset( $smarttoolz_row_stack_layout['tablet'] ) ? $smarttoolz_row_stack_layout['tablet'] : 'stack';
	$smarttoolz_mob_stack_layout  = isset( $smarttoolz_row_stack_layout['mobile'] ) ? $smarttoolz_row_stack_layout['mobile'] : 'stack';

	$smarttoolz_footer_row_classes = array(
		'site-' . esc_attr( $smarttoolz_footer_row ) . '-footer-wrap',
		'ast-builder-grid-row-container',
		'site-footer-focus-item',
		'ast-builder-grid-row-' . esc_attr( $smarttoolz_row_desk_layout ),
		'ast-builder-grid-row-tablet-' . esc_attr( $smarttoolz_tab_layout ),
		'ast-builder-grid-row-mobile-' . esc_attr( $smarttoolz_mob_layout ),
		'ast-footer-row-' . esc_attr( $smarttoolz_desk_stack_layout ),
		'ast-footer-row-tablet-' . esc_attr( $smarttoolz_tab_stack_layout ),
		'ast-footer-row-mobile-' . esc_attr( $smarttoolz_mob_stack_layout ),
	);
	?>
<div class="<?php echo esc_attr( implode( ' ', $smarttoolz_footer_row_classes ) ); ?>" data-section="section-<?php echo esc_attr( $smarttoolz_footer_row ); ?>-footer-builder">
	<div class="ast-builder-grid-row-container-inner">
		<?php
		if ( is_customize_preview() ) {
			SmartToolz_Builder_UI_Controller::render_grid_row_customizer_edit_button( 'Footer', $smarttoolz_footer_row );
		}

		/**
		 * SmartToolz Render before Site container of Footer.
		 */
		do_action( "smarttoolz_footer_{$smarttoolz_footer_row}_container_before" );
		?>
			<div class="ast-builder-footer-grid-columns site-<?php echo esc_attr( $smarttoolz_footer_row ); ?>-footer-inner-wrap ast-builder-grid-row">
			<?php for ( $smarttoolz_builder_zones = 1; $smarttoolz_builder_zones <= SmartToolz_Builder_Helper::$num_of_footer_columns; $smarttoolz_builder_zones++ ) { ?>
				<?php
				if ( $smarttoolz_builder_zones > $smarttoolz_footer_columns ) {
					break;
				}
				?>
				<div class="site-footer-<?php echo esc_attr( $smarttoolz_footer_row ); ?>-section-<?php echo absint( $smarttoolz_builder_zones ); ?> site-footer-section site-footer-section-<?php echo absint( $smarttoolz_builder_zones ); ?>">
					<?php do_action( 'smarttoolz_render_footer_column', $smarttoolz_footer_row, $smarttoolz_builder_zones ); ?>
				</div>
			<?php } ?>
			</div>
		<?php
		/**
		 * SmartToolz Render before Site container of Footer.
		 */
		do_action( "smarttoolz_footer_{$smarttoolz_footer_row}_container_after" );
		?>
	</div>

</div>
<?php } ?>
