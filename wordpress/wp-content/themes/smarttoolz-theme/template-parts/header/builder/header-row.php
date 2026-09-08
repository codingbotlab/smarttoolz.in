<?php
/**
 * Template part for displaying the a row of the header
 *
 * @package SmartToolz Builder
 */

$smarttoolz_header_row = get_query_var( 'row' );
if ( smarttoolz_wp_version_compare( '5.4.99', '>=' ) ) {
	$smarttoolz_header_row = wp_parse_args( $args, array( 'row' => '' ) );
	$smarttoolz_header_row = isset( $smarttoolz_header_row['row'] ) ? $smarttoolz_header_row['row'] : '';
}

if ( SmartToolz_Builder_Helper::is_row_empty( $smarttoolz_header_row, 'header', 'desktop' ) ) {

	$smarttoolz_customizer_editor_row = 'section-' . esc_attr( $smarttoolz_header_row ) . '-header-builder';

	$smarttoolz_row_label = 'primary' === $smarttoolz_header_row ? 'main' : $smarttoolz_header_row;

	?>
	<div class="ast-<?php echo esc_attr( $smarttoolz_row_label ); ?>-header-wrap <?php echo 'primary' === $smarttoolz_header_row ? 'main-header-bar-wrap' : ''; ?> ">
		<div class="<?php echo esc_attr( 'ast-' . $smarttoolz_header_row . '-header-bar ast-' . $smarttoolz_header_row . '-header' ); ?> <?php echo 'primary' === $smarttoolz_header_row ? 'main-header-bar' : ''; ?> site-header-focus-item" data-section="<?php echo esc_attr( $smarttoolz_customizer_editor_row ); ?>">
			<?php
			if ( is_customize_preview() ) {
				SmartToolz_Builder_UI_Controller::render_grid_row_customizer_edit_button( 'Header', $smarttoolz_header_row );
			}
			/**
			 * SmartToolz Render before Site Content.
			 */
			do_action( "smarttoolz_header_{$smarttoolz_header_row}_container_before" );
			?>
			<div class="site-<?php echo esc_attr( $smarttoolz_header_row ); ?>-header-wrap ast-builder-grid-row-container site-header-focus-item ast-container" data-section="<?php echo esc_attr( $smarttoolz_customizer_editor_row ); ?>">
				<div class="ast-builder-grid-row <?php echo SmartToolz_Builder_Helper::has_side_columns( $smarttoolz_header_row ) ? 'ast-builder-grid-row-has-sides' : 'ast-grid-center-col-layout-only ast-flex'; ?> <?php echo SmartToolz_Builder_Helper::has_center_column( $smarttoolz_header_row ) ? 'ast-grid-center-col-layout' : 'ast-builder-grid-row-no-center'; ?>">
					<?php if ( SmartToolz_Builder_Helper::has_side_columns( $smarttoolz_header_row ) ) { ?>
						<div class="site-header-<?php echo esc_attr( $smarttoolz_header_row ); ?>-section-left site-header-section ast-flex site-header-section-left">
							<?php
								/**
								 * SmartToolz Render Header Column
								 */
								do_action( 'smarttoolz_render_header_column', $smarttoolz_header_row, 'left' );
							if ( SmartToolz_Builder_Helper::has_center_column( $smarttoolz_header_row ) ) {
								?>
										<div class="site-header-<?php echo esc_attr( $smarttoolz_header_row ); ?>-section-left-center site-header-section ast-flex ast-grid-left-center-section">
									<?php
									/**
									 * SmartToolz Render Header Column
									 */
									do_action( 'smarttoolz_render_header_column', $smarttoolz_header_row, 'left_center' );
									?>
										</div>
									<?php
							}
							?>
						</div>
					<?php } ?>
						<?php if ( SmartToolz_Builder_Helper::has_center_column( $smarttoolz_header_row ) ) { ?>
							<div class="site-header-<?php echo esc_attr( $smarttoolz_header_row ); ?>-section-center site-header-section ast-flex ast-grid-section-center">
								<?php
								/**
								 * SmartToolz Render Header Column
								 */
								do_action( 'smarttoolz_render_header_column', $smarttoolz_header_row, 'center' );
								?>
							</div>
						<?php } ?>
						<?php if ( SmartToolz_Builder_Helper::has_side_columns( $smarttoolz_header_row ) ) { ?>
							<div class="site-header-<?php echo esc_attr( $smarttoolz_header_row ); ?>-section-right site-header-section ast-flex ast-grid-right-section">
								<?php
								if ( SmartToolz_Builder_Helper::has_center_column( $smarttoolz_header_row ) ) {
									?>
									<div class="site-header-<?php echo esc_attr( $smarttoolz_header_row ); ?>-section-right-center site-header-section ast-flex ast-grid-right-center-section">
										<?php
										/**
										 * SmartToolz Render Header Column
										 */
										do_action( 'smarttoolz_render_header_column', $smarttoolz_header_row, 'right_center' );
										?>
									</div>
									<?php
								}
								/**
								 * SmartToolz Render Header Column
								 */
								do_action( 'smarttoolz_render_header_column', $smarttoolz_header_row, 'right' );
								?>
							</div>
						<?php } ?>
						</div>
					</div>
					<?php
					/**
					 * SmartToolz Render after Site Content.
					 */
					do_action( "smarttoolz_header_{$smarttoolz_header_row}_container_after" );
					?>
			</div>
			</div>
	<?php
}
