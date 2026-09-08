<?php
/**
 * Template part for displaying single post's entry banner.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package SmartToolz
 * @since 4.0.0
 */

$smarttoolz_post_type      = strval( get_post_type() );
$smarttoolz_banner_control = 'ast-dynamic-single-' . esc_attr( $smarttoolz_post_type );

// If banner will be with empty markup then better to skip it.
if ( false !== strpos( smarttoolz_entry_header_class( false ), 'ast-header-without-markup' ) ) {
	return;
}

// Conditionally updating data section & class.
$smarttoolz_attr = 'class="ast-single-entry-banner"';
if ( is_customize_preview() ) {
	$smarttoolz_attr = 'class="ast-single-entry-banner ast-post-banner-highlight site-header-focus-item" data-section="' . esc_attr( $smarttoolz_banner_control ) . '"';
}

$smarttoolz_data_attrs = 'data-post-type="' . $smarttoolz_post_type . '"';

$smarttoolz_layout_type = smarttoolz_get_option( $smarttoolz_banner_control . '-layout', 'layout-1' );
$smarttoolz_data_attrs .= 'data-banner-layout="' . $smarttoolz_layout_type . '"';

if ( 'layout-2' === $smarttoolz_layout_type && 'custom' === smarttoolz_get_option( $smarttoolz_banner_control . '-banner-width-type', 'fullwidth' ) ) {
	$smarttoolz_data_attrs .= 'data-banner-width-type="custom"';
}

$smarttoolz_featured_background = smarttoolz_get_option( $smarttoolz_banner_control . '-featured-as-background', false );
if ( 'layout-2' === $smarttoolz_layout_type && $smarttoolz_featured_background ) {
	$smarttoolz_data_attrs .= 'data-banner-background-type="featured"';
}

?>
<section <?php echo wp_kses_post( $smarttoolz_attr . ' ' . $smarttoolz_data_attrs ); ?>>

<div class="ast-container">
		<?php
		if ( is_customize_preview() ) {
			SmartToolz_Builder_UI_Controller::render_banner_customizer_edit_button();
		}
			smarttoolz_banner_elements_order();
		?>
	</div>
</section>
