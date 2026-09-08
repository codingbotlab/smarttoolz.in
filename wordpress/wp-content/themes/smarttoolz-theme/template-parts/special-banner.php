<?php
/**
 * Template part for displaying archive post's entry banner.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package SmartToolz
 * @since 4.0.0
 */

$special_type         = ! empty( $args ) && ! empty( $args['type'] ) ? $args['type'] : 'search';
$smarttoolz_banner_control = 'section-' . esc_attr( $special_type ) . '-page-title';

// If description is the only meta available in structure & its blank then no need to render banner markup.
$smarttoolz_archive_structure = smarttoolz_get_option( $smarttoolz_banner_control . '-structure', array( $smarttoolz_banner_control . '-title' ) );

if ( empty( $smarttoolz_archive_structure ) ) {
	return;
}

$smarttoolz_get_archive_description = smarttoolz_get_archive_description( $special_type );
if ( 1 === count( $smarttoolz_archive_structure ) && in_array( $smarttoolz_banner_control . '-description', $smarttoolz_archive_structure ) && empty( $smarttoolz_get_archive_description ) ) {
	return;
}

// Conditionally updating data section & class.
$smarttoolz_attr = 'class="ast-archive-entry-banner"';
if ( is_customize_preview() ) {
	$smarttoolz_attr = 'class="ast-archive-entry-banner ast-post-banner-highlight site-header-focus-item" data-section="' . esc_attr( $smarttoolz_banner_control ) . '"';
}

$smarttoolz_layout_type = smarttoolz_get_option( $smarttoolz_banner_control . '-layout' );
$smarttoolz_data_attrs  = 'data-post-type="' . $special_type . '" data-banner-layout="' . $smarttoolz_layout_type . '"';

if ( 'layout-2' === $smarttoolz_layout_type && 'custom' === smarttoolz_get_option( $smarttoolz_banner_control . '-banner-width-type', 'fullwidth' ) ) {
	$smarttoolz_data_attrs .= 'data-banner-width-type="custom"';
}

$smarttoolz_background_type = smarttoolz_get_option( $smarttoolz_banner_control . '-banner-image-type', 'none' );
if ( 'layout-2' === $smarttoolz_layout_type && 'none' !== $smarttoolz_background_type ) {
	$smarttoolz_data_attrs .= 'data-banner-background-type="' . $smarttoolz_background_type . '"';
}

?>

<section <?php echo wp_kses_post( $smarttoolz_attr . ' ' . $smarttoolz_data_attrs ); ?>>
	<div class="ast-container">
		<?php
		if ( is_customize_preview() ) {
			SmartToolz_Builder_UI_Controller::render_banner_customizer_edit_button();
		}
			smarttoolz_banner_elements_order( $smarttoolz_archive_structure );
		?>
	</div>
</section>
