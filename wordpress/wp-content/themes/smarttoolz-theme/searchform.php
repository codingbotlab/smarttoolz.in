<?php
/**
 * Search Form for SmartToolz theme.
 *
 * @package     SmartToolz
 * @link        https://www.brainstormforce.com
 * @since       SmartToolz 3.3.0
 */

/**
 * Adding argument checks to avoid rendering search-form markup from other places & to easily use get_search_form() function.
 *
 * @see https://themes.trac.wordpress.org/ticket/101061
 * @since 3.6.1
 */
$smarttoolz_search_input_placeholder = isset( $args['input_placeholder'] ) ? $args['input_placeholder'] : smarttoolz_default_strings( 'string-search-input-placeholder', false );
$smarttoolz_search_show_input_submit = isset( $args['show_input_submit'] ) ? $args['show_input_submit'] : true;
$smarttoolz_search_data_attrs        = isset( $args['data_attributes'] ) ? $args['data_attributes'] : '';
$smarttoolz_search_input_value       = isset( $args['input_value'] ) ? $args['input_value'] : '';
// Check if live search is enabled & accordingly disabling browser search suggestion.
$live_search  = smarttoolz_get_option( 'live-search' );
$autocomplete = $live_search ? 'off' : '';
$search_id    = 'search-field';
if ( did_action( 'smarttoolz_sticky_header_markup' ) > 0 ) {
	$search_id .= '-sticky';
}
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="search-field">
		<span class="screen-reader-text"><?php echo esc_html__( 'Search for:', 'smarttoolz' ); ?></span>
		<input
			type="search"
			id="<?php echo esc_attr( $search_id ); ?>"
			class="search-field"
			<?php echo $autocomplete ? 'autocomplete="' . esc_attr( $autocomplete ) . '"' : ''; ?>
			<?php echo $smarttoolz_search_data_attrs; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Full attribute string built via `smarttoolz_search_field_toggle_data_attrs` filter, escaping it whole breaks the markup. ?>
			placeholder="<?php echo esc_attr( $smarttoolz_search_input_placeholder ); ?>"
			value="<?php echo esc_attr( $smarttoolz_search_input_value ); ?>"
			name="s"
			tabindex="-1">
		<?php if ( class_exists( 'SmartToolz_Icons' ) && SmartToolz_Icons::is_svg_icons() ) { ?>
			<button class="search-submit ast-search-submit" aria-label="<?php echo esc_attr__( 'Search Submit', 'smarttoolz' ); ?>">
				<span hidden><?php echo esc_html__( 'Search', 'smarttoolz' ); ?></span>
				<i><?php SmartToolz_Icons::get_icons( 'search', true ); ?></i>
			</button>
		<?php } ?>
	</label>
	<?php if ( $smarttoolz_search_show_input_submit ) { ?>
		<input type="submit" class="search-submit" value="<?php echo esc_attr__( 'Search', 'smarttoolz' ); ?>">
	<?php } ?>
</form>
<?php
