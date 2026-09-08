<?php
/**
 * Scroll To Top Template
 *
 * @package SmartToolz
 * @since 4.0.0
 */

// Bail early if it is not smarttoolz customizer.
if ( is_customize_preview() && ! SmartToolz_Customizer::is_smarttoolz_customizer() ) {
	return;
}

$smarttoolz_addon_scroll_top_alignment = smarttoolz_get_option( 'scroll-to-top-icon-position' );
$smarttoolz_addon_scroll_top_devices   = smarttoolz_get_option( 'scroll-to-top-on-devices' );
?>

<div id="ast-scroll-top" tabindex="0" class="<?php echo esc_attr( apply_filters( 'smarttoolz_scroll_top_icon', 'ast-scroll-top-icon' ) ); ?> ast-scroll-to-top-<?php echo esc_attr( $smarttoolz_addon_scroll_top_alignment ); ?>" data-on-devices="<?php echo esc_attr( $smarttoolz_addon_scroll_top_devices ); ?>">
	<?php
	if ( SmartToolz_Icons::is_svg_icons() ) {
		SmartToolz_Icons::get_icons( 'arrow', true );
	}
	?>
	<span class="screen-reader-text"><?php esc_html_e( 'Scroll to Top', 'smarttoolz' ); ?></span>
</div>
