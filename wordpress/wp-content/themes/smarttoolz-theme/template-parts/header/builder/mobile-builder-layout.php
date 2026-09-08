<?php
/**
 * Template part for displaying the Mobile Header
 *
 * @package SmartToolz Builder
 */

$smarttoolz_mobile_header_type = smarttoolz_get_option( 'mobile-header-type' );

if ( 'full-width' === $smarttoolz_mobile_header_type ) {

	$smarttoolz_mobile_header_type = 'off-canvas';
}

?>
<div id="ast-mobile-header" class="ast-mobile-header-wrap " data-type="<?php echo esc_attr( $smarttoolz_mobile_header_type ); ?>">
	<?php
	do_action( 'smarttoolz_mobile_header_bar_top' );

	/**
	 * SmartToolz Top Header
	 */
	do_action( 'smarttoolz_mobile_above_header' );

	/**
	 * SmartToolz Main Header
	 */
	do_action( 'smarttoolz_mobile_primary_header' );

	/**
	 * SmartToolz Mobile Bottom Header
	 */
	do_action( 'smarttoolz_mobile_below_header' );

	smarttoolz_main_header_bar_bottom();

	// Disable toggle menu if the toggle menu button is not exists in the mobile header items.
	$header_mobile_items = smarttoolz_get_option( 'header-mobile-items', array() );
	array_walk_recursive(
		$header_mobile_items,
		static function( string $value ) use ( &$show_mobile_toggle_menu ) {
			if ( 'mobile-trigger' === $value ) {
				$show_mobile_toggle_menu = true;
			}
		}
	);

	if ( $show_mobile_toggle_menu ) {
		if ( ( 'dropdown' === smarttoolz_get_option( 'mobile-header-type' ) && SmartToolz_Builder_Helper::is_component_loaded( 'mobile-trigger', 'header' ) ) || is_customize_preview() ) {
			$smarttoolz_content_alignment = smarttoolz_get_option( 'header-offcanvas-content-alignment', 'flex-start' );
			$smarttoolz_alignment_class   = 'content-align-' . $smarttoolz_content_alignment . ' ';
			?>
			<div class="ast-mobile-header-content <?php echo esc_attr( $smarttoolz_alignment_class ); ?>">
				<?php do_action( 'smarttoolz_mobile_header_content', 'popup', 'content' ); ?>
			</div>
			<?php
		}
	}
	?>
</div>
