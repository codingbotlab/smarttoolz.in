<?php
/**
 * Template part for displaying a row of the mobile header
 *
 * @package SmartToolz Builder
 */

$smarttoolz_row = get_query_var( 'row' );
if ( smarttoolz_wp_version_compare( '5.4.99', '>=' ) ) {
	$smarttoolz_row = wp_parse_args( $args, array( 'row' => '' ) );
	$smarttoolz_row = isset( $smarttoolz_row['row'] ) ? $smarttoolz_row['row'] : '';
}

if ( SmartToolz_Builder_Helper::is_row_empty( $smarttoolz_row, 'header', 'mobile' ) ) {

	$smarttoolz_customizer_editor_row        = 'section-' . esc_attr( $smarttoolz_row ) . '-header-builder';
	$smarttoolz_is_transparent_header_enable = smarttoolz_get_option( 'transparent-header-enable' );

	if ( 'primary' === $smarttoolz_row && $smarttoolz_is_transparent_header_enable ) {
		$smarttoolz_customizer_editor_row = 'section-transparent-header';
	}

	$smarttoolz_row_label = 'primary' === $smarttoolz_row ? 'main' : $smarttoolz_row;
	?>
	<div class="ast-<?php echo esc_attr( $smarttoolz_row_label ); ?>-header-wrap <?php echo 'primary' === $smarttoolz_row ? 'main-header-bar-wrap' : ''; ?>" >
		<div class="<?php echo esc_attr( 'ast-' . $smarttoolz_row . '-header-bar ast-' . $smarttoolz_row . '-header ' ); ?><?php echo 'primary' === $smarttoolz_row ? 'main-header-bar ' : ''; ?>site-<?php echo esc_attr( $smarttoolz_row ); ?>-header-wrap site-header-focus-item ast-builder-grid-row-layout-default ast-builder-grid-row-tablet-layout-default ast-builder-grid-row-mobile-layout-default" data-section="<?php echo esc_attr( $smarttoolz_customizer_editor_row ); ?>">
				<?php
				if ( is_customize_preview() ) {
					SmartToolz_Builder_UI_Controller::render_grid_row_customizer_edit_button( 'Header', $smarttoolz_row );
				}
				/**
				 * SmartToolz Render before Site Content.
				 */
				do_action( "smarttoolz_header_{$smarttoolz_row}_container_before" );
				?>
					<div class="ast-builder-grid-row <?php echo SmartToolz_Builder_Helper::has_mobile_side_columns( $smarttoolz_row ) ? 'ast-builder-grid-row-has-sides' : 'ast-grid-center-col-layout-only ast-flex'; ?> <?php echo SmartToolz_Builder_Helper::has_mobile_center_column( $smarttoolz_row ) ? 'ast-grid-center-col-layout' : 'ast-builder-grid-row-no-center'; ?>">
						<?php if ( SmartToolz_Builder_Helper::has_mobile_side_columns( $smarttoolz_row ) ) { ?>
							<div class="site-header-<?php echo esc_attr( $smarttoolz_row ); ?>-section-left site-header-section ast-flex site-header-section-left">
								<?php
								/**
								 * SmartToolz Render Header Column
								 */
								do_action( 'smarttoolz_render_mobile_header_column', $smarttoolz_row, 'left' );

								if ( SmartToolz_Builder_Helper::has_mobile_center_column( $smarttoolz_row ) ) {
									/**
									 * SmartToolz Render Header Column
									 */
									do_action( 'smarttoolz_render_mobile_header_column', $smarttoolz_row, 'left_center' );
								}
								?>
							</div>
						<?php } ?>
						<?php if ( SmartToolz_Builder_Helper::has_mobile_center_column( $smarttoolz_row ) ) { ?>
							<div class="site-header-<?php echo esc_attr( $smarttoolz_row ); ?>-section-center site-header-section ast-flex ast-grid-section-center">
								<?php
								/**
								 * SmartToolz Render Header Column
								 */
								do_action( 'smarttoolz_render_mobile_header_column', $smarttoolz_row, 'center' );
								?>
							</div>
						<?php } ?>
						<?php if ( SmartToolz_Builder_Helper::has_mobile_side_columns( $smarttoolz_row ) ) { ?>
							<div class="site-header-<?php echo esc_attr( $smarttoolz_row ); ?>-section-right site-header-section ast-flex ast-grid-right-section">
								<?php
								if ( SmartToolz_Builder_Helper::has_mobile_center_column( $smarttoolz_row ) ) {
									/**
									 * SmartToolz Render Header Column
									 */
									do_action( 'smarttoolz_render_mobile_header_column', $smarttoolz_row, 'right_center' );
								}
								/**
								 * SmartToolz Render Header Column
								 */
								do_action( 'smarttoolz_render_mobile_header_column', $smarttoolz_row, 'right' );
								?>
							</div>
						<?php } ?>
					</div>
				<?php
				/**
				 * SmartToolz Render after Site Content.
				 */
				do_action( "smarttoolz_header_{$smarttoolz_row}_container_after" );
				?>
		</div>
	</div>
	<?php
}
