<?php
/**
 * Post Structures - Dynamic CSS
 *
 * @package SmartToolz
 * @since 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Post Structures
 */
add_filter( 'smarttoolz_dynamic_theme_css', 'smarttoolz_post_single_structure_dynamic_css' );

/**
 * Dynamic CSS
 *
 * @param  string $dynamic_css          SmartToolz Dynamic CSS.
 * @param  string $dynamic_css_filtered SmartToolz Dynamic CSS Filters.
 * @return String Generated dynamic CSS for Post Structures.
 *
 * @since 4.0.0
 */
function smarttoolz_post_single_structure_dynamic_css( $dynamic_css, $dynamic_css_filtered = '' ) {

	$current_post_type    = strval( get_post_type() );
	$supported_post_types = SmartToolz_Posts_Structure_Loader::get_supported_post_types();

	if ( ! is_singular( $current_post_type ) ) {
		return $dynamic_css;
	}
	if ( ! in_array( $current_post_type, $supported_post_types ) ) {
		return $dynamic_css;
	}

	if ( 'product' === $current_post_type ) {
		$single_section_id = 'section-woo-shop-single';
	} elseif ( 'page' === $current_post_type ) {
		$single_section_id = 'section-single-page';
	} elseif ( 'download' === $current_post_type ) {
		$single_section_id = 'section-edd-single';
	} else {
		$single_section_id = 'single-posttype-' . $current_post_type;
	}

	$margin_option  = 'post' === $current_post_type ? 'single-post-outside-spacing' : $single_section_id . '-margin';
	$padding_option = 'post' === $current_post_type ? 'single-post-inside-spacing' : $single_section_id . '-padding';

	$padding = smarttoolz_get_option( $padding_option );
	$margin  = smarttoolz_get_option( $margin_option );

	$margin_selector  = '.site .site-content #primary';
	$padding_selector = '.site .site-content #primary .ast-article-single, .ast-separate-container .site-content #secondary .widget';

	if ( class_exists( 'WooCommerce' ) && 'product' === $current_post_type ) {
		$padding_selector = '.site .site-content #primary .ast-woocommerce-container';
	}

	// Desktop CSS.
	$css_output_desktop = array(
		$margin_selector  => array(
			// Margin CSS.
			'margin-top'    => smarttoolz_responsive_spacing( $margin, 'top', 'desktop' ),
			'margin-bottom' => smarttoolz_responsive_spacing( $margin, 'bottom', 'desktop' ),
			'margin-left'   => smarttoolz_responsive_spacing( $margin, 'left', 'desktop' ),
			'margin-right'  => smarttoolz_responsive_spacing( $margin, 'right', 'desktop' ),
		),
		$padding_selector => array(
			// Padding CSS.
			'padding-top'    => smarttoolz_responsive_spacing( $padding, 'top', 'desktop' ),
			'padding-bottom' => smarttoolz_responsive_spacing( $padding, 'bottom', 'desktop' ),
			'padding-left'   => smarttoolz_responsive_spacing( $padding, 'left', 'desktop' ),
			'padding-right'  => smarttoolz_responsive_spacing( $padding, 'right', 'desktop' ),
		),
	);

	// Tablet CSS.
	$css_output_tablet = array(
		$margin_selector  => array(
			// Margin CSS.
			'margin-top'    => smarttoolz_responsive_spacing( $margin, 'top', 'tablet' ),
			'margin-bottom' => smarttoolz_responsive_spacing( $margin, 'bottom', 'tablet' ),
			'margin-left'   => smarttoolz_responsive_spacing( $margin, 'left', 'tablet' ),
			'margin-right'  => smarttoolz_responsive_spacing( $margin, 'right', 'tablet' ),
		),
		$padding_selector => array(
			// Padding CSS.
			'padding-top'    => smarttoolz_responsive_spacing( $padding, 'top', 'tablet' ),
			'padding-bottom' => smarttoolz_responsive_spacing( $padding, 'bottom', 'tablet' ),
			'padding-left'   => smarttoolz_responsive_spacing( $padding, 'left', 'tablet' ),
			'padding-right'  => smarttoolz_responsive_spacing( $padding, 'right', 'tablet' ),
		),
	);

	// Mobile CSS.
	$css_output_mobile = array(
		$margin_selector  => array(
			// Margin CSS.
			'margin-top'    => smarttoolz_responsive_spacing( $margin, 'top', 'mobile' ),
			'margin-bottom' => smarttoolz_responsive_spacing( $margin, 'bottom', 'mobile' ),
			'margin-left'   => smarttoolz_responsive_spacing( $margin, 'left', 'mobile' ),
			'margin-right'  => smarttoolz_responsive_spacing( $margin, 'right', 'mobile' ),
		),
		$padding_selector => array(
			// Padding CSS.
			'padding-top'    => smarttoolz_responsive_spacing( $padding, 'top', 'mobile' ),
			'padding-bottom' => smarttoolz_responsive_spacing( $padding, 'bottom', 'mobile' ),
			'padding-left'   => smarttoolz_responsive_spacing( $padding, 'left', 'mobile' ),
			'padding-right'  => smarttoolz_responsive_spacing( $padding, 'right', 'mobile' ),
		),
	);

	$css_output  = smarttoolz_parse_css( $css_output_desktop );
	$css_output .= smarttoolz_parse_css( $css_output_tablet, '', smarttoolz_get_tablet_breakpoint() );
	$css_output .= smarttoolz_parse_css( $css_output_mobile, '', smarttoolz_get_mobile_breakpoint() );

	$dynamic_css .= $css_output;

	if ( false === smarttoolz_get_option( 'ast-single-' . $current_post_type . '-title', class_exists( 'WooCommerce' ) && 'product' === $current_post_type ? false : true ) ) {
		return $dynamic_css;
	}

	$layout_type     = smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-layout', 'layout-1' );
	$layout_2_active = 'layout-2' === $layout_type ? true : false;
	$exclude_attr    = smarttoolz_get_option( 'enable-related-posts', false ) ? ':not(.related-entry-header)' : '';

	if ( $layout_2_active ) {
		$selector = '.ast-single-entry-banner[data-post-type="' . $current_post_type . '"]';
	} else {
		$selector = 'header.entry-header' . $exclude_attr;
	}

	$site_content_width = smarttoolz_get_option( 'site-content-width', 1200 );
	$horz_alignment     = smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-horizontal-alignment' );
	$desk_h_alignment   = isset( $horz_alignment['desktop'] ) ? $horz_alignment['desktop'] : '';
	$tab_h_alignment    = isset( $horz_alignment['tablet'] ) ? $horz_alignment['tablet'] : '';
	$mob_h_alignment    = isset( $horz_alignment['mobile'] ) ? $horz_alignment['mobile'] : '';

	// Default alignment for layout-1 when not set.
	if ( 'layout-1' === $layout_type ) {
		$desk_h_alignment = '' !== $desk_h_alignment ? $desk_h_alignment : 'left';
		$tab_h_alignment  = '' !== $tab_h_alignment ? $tab_h_alignment : 'left';
		$mob_h_alignment  = '' !== $mob_h_alignment ? $mob_h_alignment : 'left';
	}

	// Flip horizontal alignment for RTL so saved left/right mirror automatically.
	$desk_h_alignment = smarttoolz_flip_rtl_alignment( $desk_h_alignment );
	$tab_h_alignment  = smarttoolz_flip_rtl_alignment( $tab_h_alignment );
	$mob_h_alignment  = smarttoolz_flip_rtl_alignment( $mob_h_alignment );

	$banner_padding = smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-banner-padding', SmartToolz_Posts_Structure_Loader::get_customizer_default( 'responsive-padding' ) );
	$banner_margin  = smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-banner-margin' );

	$text_color       = smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-banner-text-color' );
	$title_color      = smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-banner-title-color' );
	$link_color       = smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-banner-link-color' );
	$link_hover_color = smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-banner-link-hover-color' );

	$elements_gap       = smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-elements-gap', 10 );
	$banner_height      = smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-banner-height' );
	$desk_banner_height = $layout_2_active && isset( $banner_height['desktop'] ) ? smarttoolz_get_css_value( $banner_height['desktop'], 'px' ) : '';
	$tab_banner_height  = $layout_2_active && isset( $banner_height['tablet'] ) ? smarttoolz_get_css_value( $banner_height['tablet'], 'px' ) : '';
	$mob_banner_height  = $layout_2_active && isset( $banner_height['mobile'] ) ? smarttoolz_get_css_value( $banner_height['mobile'], 'px' ) : '';

	$vert_alignment = $layout_2_active ? smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-vertical-alignment', 'center' ) : 'center';
	$width_type     = smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-banner-width-type', 'fullwidth' );
	$custom_width   = smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-banner-custom-width', 1200 );

	$single_structure = smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-structure', 'page' === $current_post_type ? array( 'ast-dynamic-single-' . $current_post_type . '-image', 'ast-dynamic-single-' . $current_post_type . '-title' ) : array( 'ast-dynamic-single-' . $current_post_type . '-title', 'ast-dynamic-single-' . $current_post_type . '-meta' ) );

	// Banner Text typography dynamic stylings.
	$banner_text_font_size = smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-text-font-size' );

	// Banner Title typography dynamic stylings.
	$banner_title_font_size = smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-title-font-size', SmartToolz_Posts_Structure_Loader::get_customizer_default( 'title-font-size' ) );

	// Banner Meta typography dynamic stylings.
	$banner_meta_font_size = smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-meta-font-size' );

	$css_output_min_tablet  = array();
	$narrow_container_width = smarttoolz_get_option( 'narrow-container-max-width', apply_filters( 'smarttoolz_narrow_container_width', 750 ) );
	$author_avatar_size     = smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-author-avatar-size' );

	// Aspect ratio.
	$aspect_ratio_type   = smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-article-featured-image-ratio-type', 'predefined' );
	$predefined_scale    = smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-article-featured-image-ratio-pre-scale', '16/9' );
	$custom_scale_width  = smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-article-featured-image-custom-scale-width', 16 );
	$custom_scale_height = smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-article-featured-image-custom-scale-height', 9 );
	$aspect_ratio        = smarttoolz_get_dynamic_image_aspect_ratio( $aspect_ratio_type, $predefined_scale, $custom_scale_width, $custom_scale_height );
	$object_fit_style    = 'custom' === $aspect_ratio_type ? 'cover' : '';

	// Remove featured image padding.
	$remove_featured_image_padding = smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-remove-featured-padding', false ) && 'layout-1' === $layout_type && 'none' === smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-article-featured-image-position-layout-1' ) ? true : false;

	// Few settings from banner section are also applicable to 'layout-1' so adding this condition & compatibility.
	if ( 'layout-1' === $layout_type ) {
		$image_wrap_alignment = SmartToolz_Dynamic_CSS::smarttoolz_4_4_0_compatibility() ? 'center' : '';
		/**
		 * Desktop CSS.
		 */
		$css_output_desktop = array(
			$selector                               => array(
				'text-align' => $desk_h_alignment,
			),
			$selector . ' *'                        => smarttoolz_get_font_array_css( smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-text-font-family' ), smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-text-font-weight' ), $banner_text_font_size, 'ast-dynamic-single-' . $current_post_type . '-text-font-extras', $text_color ),
			$selector . ' .entry-title'             => smarttoolz_get_font_array_css( smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-title-font-family' ), smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-title-font-weight', SmartToolz_Posts_Structure_Loader::get_customizer_default( 'title-font-weight' ) ), $banner_title_font_size, 'ast-dynamic-single-' . $current_post_type . '-title-font-extras', $title_color ),
			$selector . ' .entry-meta, ' . $selector . ' .entry-meta *' => smarttoolz_get_font_array_css( smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-meta-font-family' ), smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-meta-font-weight' ), $banner_meta_font_size, 'ast-dynamic-single-' . $current_post_type . '-meta-font-extras' ),
			$selector . ' a, ' . $selector . ' a *' => array(
				'color' => esc_attr( $link_color ),
			),
			$selector . ' a:hover, ' . $selector . ' a:hover *' => array(
				'color' => esc_attr( $link_hover_color ),
			),
			$selector . ' > *:not(:last-child)'     => array(
				'margin-bottom' => $elements_gap . 'px',
			),
			$selector . ' .post-thumb-img-content'  => array(
				'text-align' => $image_wrap_alignment,
			),
			$selector . ' .post-thumb img, .ast-single-post-featured-section.post-thumb img' => array(
				'aspect-ratio' => $aspect_ratio,
				'width'        => SmartToolz_Dynamic_CSS::smarttoolz_4_6_0_compatibility() && 'default' !== $aspect_ratio_type ? '100%' : '',
				'height'       => SmartToolz_Dynamic_CSS::smarttoolz_4_6_0_compatibility() && 'default' !== $aspect_ratio_type ? '100%' : '',
				'object-fit'   => $object_fit_style,
			),
		);

		/**
		 * Tablet CSS.
		 */
		$css_output_tablet = array(
			$selector                   => array(
				'text-align' => $tab_h_alignment,
			),
			$selector . ' .entry-title' => array(
				'font-size' => smarttoolz_responsive_font( $banner_title_font_size, 'tablet' ),
			),
			$selector . ' *'            => array(
				'font-size' => smarttoolz_responsive_font( $banner_text_font_size, 'tablet' ),
			),
			$selector . ' .entry-meta, ' . $selector . ' .entry-meta *' => array(
				'font-size' => smarttoolz_responsive_font( $banner_meta_font_size, 'tablet' ),
			),
		);

		/**
		 * Mobile CSS.
		 */
		$css_output_mobile = array(
			$selector                   => array(
				'text-align' => $mob_h_alignment,
			),
			$selector . ' .entry-title' => array(
				'font-size' => smarttoolz_responsive_font( $banner_title_font_size, 'mobile' ),
			),
			$selector . ' *'            => array(
				'font-size' => smarttoolz_responsive_font( $banner_text_font_size, 'mobile' ),
			),
			$selector . ' .entry-meta, ' . $selector . ' .entry-meta *' => array(
				'font-size' => smarttoolz_responsive_font( $banner_meta_font_size, 'mobile' ),
			),
		);

		if ( $remove_featured_image_padding ) {

			$single_post_container_spacing = smarttoolz_get_option( 'single-post-inside-spacing' );
			$container_padding_defaults    = SmartToolz_Dynamic_CSS::smarttoolz_4_6_0_compatibility() && is_single() ? '2.5em' : '3em';

			$container_lg_horz_spacing = true === smarttoolz_check_is_structural_setup() ? $container_padding_defaults : '6.67';
			$container_lg_vert_spacing = true === smarttoolz_check_is_structural_setup() ? $container_padding_defaults : '5.34';

			$smarttoolz_desktop_container_left_spacing  = defined( 'SMARTTOOLZ_EXT_VER' ) && smarttoolz_responsive_spacing( $single_post_container_spacing, 'left', 'desktop' ) ? smarttoolz_responsive_spacing( $single_post_container_spacing, 'left', 'desktop', $container_lg_horz_spacing ) : 'var(--ast-container-default-xlg-padding)';
			$smarttoolz_desktop_container_right_spacing = defined( 'SMARTTOOLZ_EXT_VER' ) && smarttoolz_responsive_spacing( $single_post_container_spacing, 'right', 'desktop' ) ? smarttoolz_responsive_spacing( $single_post_container_spacing, 'right', 'desktop', $container_lg_horz_spacing ) : 'var(--ast-container-default-xlg-padding)';
			$smarttoolz_desktop_container_top_spacing   = defined( 'SMARTTOOLZ_EXT_VER' ) && smarttoolz_responsive_spacing( $single_post_container_spacing, 'right', 'desktop' ) ? smarttoolz_responsive_spacing( $single_post_container_spacing, 'right', 'desktop', $container_lg_vert_spacing ) : 'var(--ast-container-default-xlg-padding)';

			$smarttoolz_tablet_container_left_spacing  = defined( 'SMARTTOOLZ_EXT_VER' ) && smarttoolz_responsive_spacing( $single_post_container_spacing, 'left', 'tablet' ) ? smarttoolz_responsive_spacing( $single_post_container_spacing, 'left', 'tablet', $container_lg_horz_spacing ) : 'var(--ast-container-default-xlg-padding)';
			$smarttoolz_tablet_container_right_spacing = defined( 'SMARTTOOLZ_EXT_VER' ) && smarttoolz_responsive_spacing( $single_post_container_spacing, 'right', 'tablet' ) ? smarttoolz_responsive_spacing( $single_post_container_spacing, 'right', 'tablet', $container_lg_horz_spacing ) : 'var(--ast-container-default-xlg-padding)';
			$smarttoolz_tablet_container_top_spacing   = defined( 'SMARTTOOLZ_EXT_VER' ) && smarttoolz_responsive_spacing( $single_post_container_spacing, 'right', 'tablet' ) ? smarttoolz_responsive_spacing( $single_post_container_spacing, 'right', 'tablet', $container_lg_vert_spacing ) : 'var(--ast-container-default-xlg-padding)';

			$smarttoolz_mobile_container_left_spacing  = defined( 'SMARTTOOLZ_EXT_VER' ) && smarttoolz_responsive_spacing( $single_post_container_spacing, 'left', 'mobile' ) ? smarttoolz_responsive_spacing( $single_post_container_spacing, 'left', 'mobile', $container_lg_horz_spacing ) : '1em';
			$smarttoolz_mobile_container_right_spacing = defined( 'SMARTTOOLZ_EXT_VER' ) && smarttoolz_responsive_spacing( $single_post_container_spacing, 'right', 'mobile' ) ? smarttoolz_responsive_spacing( $single_post_container_spacing, 'right', 'mobile', $container_lg_horz_spacing ) : '1em';
			$smarttoolz_mobile_container_top_spacing   = defined( 'SMARTTOOLZ_EXT_VER' ) && smarttoolz_responsive_spacing( $single_post_container_spacing, 'right', 'mobile' ) ? smarttoolz_responsive_spacing( $single_post_container_spacing, 'right', 'mobile', $container_lg_vert_spacing ) : '1.5em';

			$css_output_desktop[ '.ast-separate-container ' . $selector . ' .post-thumb' ]['margin-left']               = $smarttoolz_desktop_container_left_spacing ? 'calc( -1 * ' . $smarttoolz_desktop_container_left_spacing . ' )' : '';
			$css_output_desktop[ '.ast-separate-container ' . $selector . ' > *:first-child.post-thumb' ]['margin-top'] = $smarttoolz_desktop_container_top_spacing ? 'calc( -1 * ' . $smarttoolz_desktop_container_top_spacing . ' )' : '';
			$css_output_desktop[ '.ast-separate-container ' . $selector . ' .post-thumb' ]['margin-right']              = $smarttoolz_desktop_container_right_spacing ? 'calc( -1 * ' . $smarttoolz_desktop_container_right_spacing . ' )' : '';

			$css_output_tablet[ '.ast-separate-container ' . $selector . ' .post-thumb' ]['margin-left']               = $smarttoolz_tablet_container_left_spacing ? 'calc( -1 * ' . $smarttoolz_tablet_container_left_spacing . ' )' : '';
			$css_output_tablet[ '.ast-separate-container ' . $selector . ' > *:first-child.post-thumb' ]['margin-top'] = $smarttoolz_tablet_container_top_spacing ? 'calc( -1 * ' . $smarttoolz_tablet_container_top_spacing . ' )' : '';
			$css_output_tablet[ '.ast-separate-container ' . $selector . ' .post-thumb' ]['margin-right']              = $smarttoolz_tablet_container_right_spacing ? 'calc( -1 * ' . $smarttoolz_tablet_container_right_spacing . ' )' : '';

			$css_output_mobile[ '.ast-separate-container ' . $selector . ' .post-thumb' ]['margin-left']               = $smarttoolz_mobile_container_left_spacing ? 'calc( -1 * ' . $smarttoolz_mobile_container_left_spacing . ' )' : '';
			$css_output_mobile[ '.ast-separate-container ' . $selector . ' > *:first-child.post-thumb' ]['margin-top'] = $smarttoolz_mobile_container_top_spacing ? 'calc( -1 * ' . $smarttoolz_mobile_container_top_spacing . ' )' : '';
			$css_output_mobile[ '.ast-separate-container ' . $selector . ' .post-thumb' ]['margin-right']              = $smarttoolz_mobile_container_right_spacing ? 'calc( -1 * ' . $smarttoolz_mobile_container_right_spacing . ' )' : '';
		}
	} else {
		$entry_title_selector    = is_customize_preview() ? $selector . ' .ast-container .entry-title' : $selector . ' .entry-title';
		$image_position          = smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-image-position', 'inside' );
		$use_featured_background = smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-featured-as-background', false );
		$custom_background       = smarttoolz_get_option(
			'ast-dynamic-single-' . $current_post_type . '-banner-background',
			SmartToolz_Posts_Structure_Loader::get_customizer_default( 'responsive-background' )
		);

		/**
		 * Desktop CSS.
		 */
		$css_output_desktop = array(
			$selector                                     => array(
				'text-align'      => $desk_h_alignment,
				'justify-content' => $vert_alignment,
				'min-height'      => $desk_banner_height,
				'margin-top'      => smarttoolz_responsive_spacing( $banner_margin, 'top', 'desktop' ),
				'margin-right'    => smarttoolz_responsive_spacing( $banner_margin, 'right', 'desktop' ),
				'margin-bottom'   => smarttoolz_responsive_spacing( $banner_margin, 'bottom', 'desktop' ),
				'margin-left'     => smarttoolz_responsive_spacing( $banner_margin, 'left', 'desktop' ),
				'width'           => '100%',
				'padding-top'     => smarttoolz_responsive_spacing( $banner_padding, 'top', 'desktop' ),
				'padding-right'   => smarttoolz_responsive_spacing( $banner_padding, 'right', 'desktop' ),
				'padding-bottom'  => smarttoolz_responsive_spacing( $banner_padding, 'bottom', 'desktop' ),
				'padding-left'    => smarttoolz_responsive_spacing( $banner_padding, 'left', 'desktop' ),
			),
			$selector . '[data-banner-layout="layout-2"]' => smarttoolz_get_responsive_background_obj( $custom_background, 'desktop' ),
			$selector . ' .ast-container *'               => smarttoolz_get_font_array_css( smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-text-font-family' ), smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-text-font-weight' ), $banner_text_font_size, 'ast-dynamic-single-' . $current_post_type . '-text-font-extras', $text_color ),
			$selector . ' .ast-container > *:not(:last-child), ' . $selector . ' .read-more' => array(
				'margin-bottom' => $elements_gap . 'px',
			),
			$selector . ' .ast-container'                 => array(
				'width' => '100%',
			),
			$entry_title_selector                         => smarttoolz_get_font_array_css( smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-title-font-family' ), smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-title-font-weight', SmartToolz_Posts_Structure_Loader::get_customizer_default( 'title-font-weight' ) ), $banner_title_font_size, 'ast-dynamic-single-' . $current_post_type . '-title-font-extras', $title_color ),
			$selector . ' > .entry-title'                 => array(
				'margin-bottom' => '0',
			),
			$selector . ' .entry-meta, ' . $selector . ' .entry-meta *' => smarttoolz_get_font_array_css( smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-meta-font-family' ), smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-meta-font-weight' ), $banner_meta_font_size, 'ast-dynamic-single-' . $current_post_type . '-meta-font-extras' ),
			$selector . ' .ast-container a, ' . $selector . ' .ast-container a *' => array(
				'color' => esc_attr( $link_color ),
			),
			$selector . ' .ast-container a:hover, ' . $selector . ' .ast-container a:hover *' => array(
				'color' => esc_attr( $link_hover_color ),
			),
			'.ast-single-entry-banner .read-more .ast-button' => array(
				'margin-top' => '0.5em',
				'display'    => 'inline-block',
			),
			$selector . ' .post-thumb img, .ast-single-post-featured-section img' => array(
				'aspect-ratio' => $aspect_ratio,
				'width'        => SmartToolz_Dynamic_CSS::smarttoolz_4_6_0_compatibility() && 'default' !== $aspect_ratio_type ? '100%' : '',
				'height'       => SmartToolz_Dynamic_CSS::smarttoolz_4_6_0_compatibility() && 'default' !== $aspect_ratio_type ? '100%' : '',
				'object-fit'   => $object_fit_style,
			),
			$selector . ' .ast-container > *:last-child'  => array(
				'margin-bottom' => '0',
			),
		);

		/**
		 * Min tablet width CSS.
		 */
		$css_output_min_tablet = array(
			'.ast-narrow-container ' . $selector . ' .ast-container' => array(
				'max-width'     => $narrow_container_width . 'px',
				'padding-left'  => '0',
				'padding-right' => '0',
			),
		);

		/**
		 * Tablet CSS.
		 */
		$css_output_tablet = array(
			$selector                                     => array(
				'text-align'     => $tab_h_alignment,
				'min-height'     => $tab_banner_height,
				'padding-top'    => smarttoolz_responsive_spacing( $banner_padding, 'top', 'tablet' ),
				'padding-right'  => smarttoolz_responsive_spacing( $banner_padding, 'right', 'tablet' ),
				'padding-bottom' => smarttoolz_responsive_spacing( $banner_padding, 'bottom', 'tablet' ),
				'padding-left'   => smarttoolz_responsive_spacing( $banner_padding, 'left', 'tablet' ),
				'margin-top'     => smarttoolz_responsive_spacing( $banner_margin, 'top', 'tablet' ),
				'margin-right'   => smarttoolz_responsive_spacing( $banner_margin, 'right', 'tablet' ),
				'margin-bottom'  => smarttoolz_responsive_spacing( $banner_margin, 'bottom', 'tablet' ),
				'margin-left'    => smarttoolz_responsive_spacing( $banner_margin, 'left', 'tablet' ),
			),
			$selector . '[data-banner-layout="layout-2"]' => smarttoolz_get_responsive_background_obj( $custom_background, 'tablet' ),
			$selector . ' .entry-title'                   => array(
				'font-size' => smarttoolz_responsive_font( $banner_title_font_size, 'tablet' ),
			),
			$selector . ' .ast-container'                 => array(
				'padding-left'  => '0',
				'padding-right' => '0',
			),
			$selector . ' *'                              => array(
				'font-size' => smarttoolz_responsive_font( $banner_text_font_size, 'tablet' ),
			),
			$selector . ' .entry-meta, ' . $selector . ' .entry-meta *' => array(
				'font-size' => smarttoolz_responsive_font( $banner_meta_font_size, 'tablet' ),
			),
		);

		/**
		 * Mobile CSS.
		 */
		$css_output_mobile = array(
			$selector                                     => array(
				'text-align'     => $mob_h_alignment,
				'min-height'     => $mob_banner_height,
				'padding-top'    => smarttoolz_responsive_spacing( $banner_padding, 'top', 'mobile' ),
				'padding-right'  => smarttoolz_responsive_spacing( $banner_padding, 'right', 'mobile' ),
				'padding-bottom' => smarttoolz_responsive_spacing( $banner_padding, 'bottom', 'mobile' ),
				'padding-left'   => smarttoolz_responsive_spacing( $banner_padding, 'left', 'mobile' ),
				'margin-top'     => smarttoolz_responsive_spacing( $banner_margin, 'top', 'mobile' ),
				'margin-right'   => smarttoolz_responsive_spacing( $banner_margin, 'right', 'mobile' ),
				'margin-bottom'  => smarttoolz_responsive_spacing( $banner_margin, 'bottom', 'mobile' ),
				'margin-left'    => smarttoolz_responsive_spacing( $banner_margin, 'left', 'mobile' ),
			),
			$selector . '[data-banner-layout="layout-2"]' => smarttoolz_get_responsive_background_obj( $custom_background, 'mobile' ),
			$selector . ' .entry-title'                   => array(
				'font-size' => smarttoolz_responsive_font( $banner_title_font_size, 'mobile' ),
			),
			$selector . ' *'                              => array(
				'font-size' => smarttoolz_responsive_font( $banner_text_font_size, 'mobile' ),
			),
			$selector . ' .entry-meta, ' . $selector . ' .entry-meta *' => array(
				'font-size' => smarttoolz_responsive_font( $banner_meta_font_size, 'mobile' ),
			),
		);

		if ( ( $layout_2_active && 'custom' === $width_type ) || is_customize_preview() ) {
			$css_output_desktop[ $selector . '[data-banner-width-type="custom"]' ]['max-width'] = $custom_width . 'px';
		}

		if ( 'outside' !== $image_position && in_array( 'ast-dynamic-single-' . $current_post_type . '-image', $single_structure ) && $use_featured_background ) {
			/** @psalm-suppress PossiblyFalseArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$feat_image_src = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
			/** @psalm-suppress PossiblyFalseArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			if ( $feat_image_src ) {
				$css_output_desktop[ $selector . '[data-banner-background-type="featured"]' ] = array(
					'background'            => 'url( ' . esc_url( $feat_image_src ) . ' )',
					'background-repeat'     => 'no-repeat',
					'background-attachment' => 'scroll',
					'background-position'   => 'center center',
					'background-size'       => 'cover',
				);
				$overlay_color = smarttoolz_get_option( 'ast-dynamic-single-' . $current_post_type . '-banner-featured-overlay', '' );
				if ( '' !== $overlay_color && 'unset' !== $overlay_color ) {
					$css_output_desktop[ $selector . '[data-banner-background-type="featured"]' ]['background']            = 'url( ' . esc_url( $feat_image_src ) . ' ) ' . $overlay_color;
					$css_output_desktop[ $selector . '[data-banner-background-type="featured"]' ]['background-blend-mode'] = 'multiply';
				}
			}
		}

		if ( 'outside' === $image_position ) {
			$css_output_desktop['.single article .post-thumb'] = array(
				'margin-bottom' => '2em',
			);
		}
	}

	$dynamic_css .= '
		.ast-single-entry-banner {
			-js-display: flex;
			display: flex;
			flex-direction: column;
			justify-content: center;
			text-align: center;
			position: relative;
			background: var(--ast-title-layout-bg);
		}
		.ast-single-entry-banner[data-banner-layout="layout-1"] {
			max-width: ' . smarttoolz_get_css_value( $site_content_width, 'px' ) . ';
			background: inherit;
			padding: 20px 0;
		}
		.ast-single-entry-banner[data-banner-width-type="custom"] {
			margin: 0 auto;
			width: 100%;
		}
		.ast-single-entry-banner + .site-content .entry-header {
			margin-bottom: 0;
		}
		.site .ast-author-avatar {
			--ast-author-avatar-size: ' . smarttoolz_get_css_value( $author_avatar_size, 'px' ) . ';
		}
		a.ast-underline-text {
			text-decoration: underline;
		}
		.ast-container > .ast-terms-link {
			position: relative;
			display: block;
		}
		a.ast-button.ast-badge-tax {
			padding: 4px 8px;
			border-radius: 3px;
			font-size: inherit;
		}
	';

	if ( is_customize_preview() ) {
		$dynamic_css .= '
			.site-header-focus-item .ast-container div.customize-partial-edit-shortcut,
			.site-header-focus-item .ast-container button.item-customizer-focus {
				font-size: inherit;
			}
		';
	}

	$margin_top = smarttoolz_responsive_spacing( $margin, 'top', 'desktop' );

	// To add top spacing for SureCart shop page default title.
	if ( class_exists( 'SureCart' ) && $margin_top && 0 === intval( $margin_top ) && get_the_ID() === intval( get_option( 'surecart_shop_page_id' ) ) ) {
		$dynamic_css .= '
			.page .entry-header {
				margin-top: 3em;
			}
		';
	}

	/* Parse CSS from array() */
	$dynamic_css .= smarttoolz_parse_css( $css_output_desktop );
	$dynamic_css .= smarttoolz_parse_css( $css_output_min_tablet, smarttoolz_get_tablet_breakpoint( '', 1 ) );
	$dynamic_css .= smarttoolz_parse_css( $css_output_tablet, '', smarttoolz_get_tablet_breakpoint() );
	$dynamic_css .= smarttoolz_parse_css( $css_output_mobile, '', smarttoolz_get_mobile_breakpoint() );

	return $dynamic_css;
}
