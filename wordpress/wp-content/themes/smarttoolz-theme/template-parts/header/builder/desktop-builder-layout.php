<?php
/**
 * Template part for displaying header row.
 *
 * @package SmartToolz Builder
 */

$smarttoolz_mobile_header_type = smarttoolz_get_option( 'mobile-header-type' );

if ( 'full-width' === $smarttoolz_mobile_header_type ) {

	$smarttoolz_mobile_header_type = 'off-canvas';
}
?>
<div id="ast-desktop-header" data-toggle-type="<?php echo esc_attr( $smarttoolz_mobile_header_type ); ?>">
	<?php
	smarttoolz_main_header_bar_top();

	/**
	 * SmartToolz Top Header
	 */
	do_action( 'smarttoolz_above_header' );

	/**
	 * SmartToolz Main Header
	 */
	do_action( 'smarttoolz_primary_header' );

	/**
	 * SmartToolz Bottom Header
	 */
	do_action( 'smarttoolz_below_header' );

	smarttoolz_main_header_bar_bottom();

	// Disable toggle menu if the toggle menu button is not exists in the desktop header items.
	$header_desktop_items = smarttoolz_get_option( 'header-desktop-items', array() );
	array_walk_recursive(
		$header_desktop_items,
		static function( string $value ) use ( &$show_desktop_toggle_menu ) {
			if ( 'mobile-trigger' === $value ) {
				$show_desktop_toggle_menu = true;
			}
		}
	);

	if ( $show_desktop_toggle_menu ) {
		if ( ( 'dropdown' === $smarttoolz_mobile_header_type && SmartToolz_Builder_Helper::is_component_loaded( 'mobile-trigger', 'header' ) ) || is_customize_preview() ) {
			$smarttoolz_content_alignment = smarttoolz_get_option( 'header-offcanvas-content-alignment', 'flex-start' );
			$smarttoolz_alignment_class   = 'content-align-' . $smarttoolz_content_alignment . ' ';
			?>
			<div class="ast-desktop-header-content <?php echo esc_attr( $smarttoolz_alignment_class ); ?>">
				<?php do_action( 'smarttoolz_desktop_header_content', 'popup', 'content' ); ?>
			</div>
			<?php
		}
	}
	?>
</div> <!-- Main Header Bar Wrap -->
<?php
/**
 * SmartToolz Mobile Header
 */
do_action( 'smarttoolz_mobile_header' );
?>
