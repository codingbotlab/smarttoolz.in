<?php
/**
 * SmartToolz HTML Component Dynamic CSS.
 *
 * @package     smarttoolz-builder
 * @link        https://wpsmarttoolz.com/
 * @since       3.0.0
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Builder Dynamic CSS.
 *
 * @since 3.0.0
 */
class SmartToolz_Html_Component_Dynamic_CSS {
	/**
	 * Dynamic CSS
	 *
	 * @param string $builder_type Builder Type.
	 * @return String Generated dynamic CSS for Heading Colors.
	 *
	 * @since 3.0.0
	 */
	public static function smarttoolz_html_dynamic_css( $builder_type = 'header' ) {

		$generated_css  = '';
		$html_css_flag  = false;
		$number_of_html = 'header' === $builder_type ? SmartToolz_Builder_Helper::$num_of_header_html : SmartToolz_Builder_Helper::$num_of_footer_html;

		for ( $index = 1; $index <= $number_of_html; $index++ ) {

			if ( ! SmartToolz_Builder_Helper::is_component_loaded( 'html-' . $index, $builder_type ) ) {
				continue;
			}

			$html_css_flag = true;

			$_section = 'header' === $builder_type ? 'section-hb-html-' . $index : 'section-fb-html-' . $index;

			$margin    = smarttoolz_get_option( $_section . '-margin' );
			$font_size = smarttoolz_get_option( 'font-size-' . $_section );

			$text_color_desktop = smarttoolz_get_prop( smarttoolz_get_option( $builder_type . '-html-' . $index . 'color' ), 'desktop' );
			$text_color_tablet  = smarttoolz_get_prop( smarttoolz_get_option( $builder_type . '-html-' . $index . 'color' ), 'tablet' );
			$text_color_mobile  = smarttoolz_get_prop( smarttoolz_get_option( $builder_type . '-html-' . $index . 'color' ), 'mobile' );

			$link_color_desktop = smarttoolz_get_prop( smarttoolz_get_option( $builder_type . '-html-' . $index . 'link-color' ), 'desktop' );
			$link_color_tablet  = smarttoolz_get_prop( smarttoolz_get_option( $builder_type . '-html-' . $index . 'link-color' ), 'tablet' );
			$link_color_mobile  = smarttoolz_get_prop( smarttoolz_get_option( $builder_type . '-html-' . $index . 'link-color' ), 'mobile' );

			$link_h_color_desktop = smarttoolz_get_prop( smarttoolz_get_option( $builder_type . '-html-' . $index . 'link-h-color' ), 'desktop' );
			$link_h_color_tablet  = smarttoolz_get_prop( smarttoolz_get_option( $builder_type . '-html-' . $index . 'link-h-color' ), 'tablet' );
			$link_h_color_mobile  = smarttoolz_get_prop( smarttoolz_get_option( $builder_type . '-html-' . $index . 'link-h-color' ), 'mobile' );

			$selector = 'header' === $builder_type ? '.ast-header-html-' . $index : '.footer-widget-area[data-section="section-fb-html-' . $index . '"]';

			$display_prop = 'header' === $builder_type ? 'flex' : 'block';

			$css_output_desktop = array(

				$selector . ' .ast-builder-html-element' => array(
					'color'     => $text_color_desktop,
					// Typography.
					'font-size' => smarttoolz_responsive_font( $font_size, 'desktop' ),
				),

				$selector                                => array(
					// Margin.
					'margin-top'    => smarttoolz_responsive_spacing( $margin, 'top', 'desktop' ),
					'margin-bottom' => smarttoolz_responsive_spacing( $margin, 'bottom', 'desktop' ),
					'margin-left'   => smarttoolz_responsive_spacing( $margin, 'left', 'desktop' ),
					'margin-right'  => smarttoolz_responsive_spacing( $margin, 'right', 'desktop' ),
				),

				// Link Color.
				$selector . ' a'                         => array(
					'color' => $link_color_desktop,
				),

				// Link Hover Color.
				$selector . ' a:hover'                   => array(
					'color' => $link_h_color_desktop,
				),
			);

			/* Parse CSS from array() */
			$css_output = smarttoolz_parse_css( $css_output_desktop );

			// Tablet CSS.
			$css_output_tablet = array(

				$selector . ' .ast-builder-html-element' => array(
					'color'     => $text_color_tablet,
					// Typography.
					'font-size' => smarttoolz_responsive_font( $font_size, 'tablet' ),
				),

				$selector                                => array(
					// Margin CSS.
					'margin-top'    => smarttoolz_responsive_spacing( $margin, 'top', 'tablet' ),
					'margin-bottom' => smarttoolz_responsive_spacing( $margin, 'bottom', 'tablet' ),
					'margin-left'   => smarttoolz_responsive_spacing( $margin, 'left', 'tablet' ),
					'margin-right'  => smarttoolz_responsive_spacing( $margin, 'right', 'tablet' ),
				),

				// Link Color.
				$selector . ' a'                         => array(
					'color' => $link_color_tablet,
				),

				// Link Hover Color.
				$selector . ' a:hover'                   => array(
					'color' => $link_h_color_tablet,
				),
			);
			$css_output .= smarttoolz_parse_css( $css_output_tablet, '', smarttoolz_get_tablet_breakpoint() );

			// Mobile CSS.
			$css_output_mobile = array(

				$selector . ' .ast-builder-html-element' => array(
					'color'     => $text_color_mobile,
					// Typography.
					'font-size' => smarttoolz_responsive_font( $font_size, 'mobile' ),
				),

				$selector                                => array(
					// Margin CSS.
					'margin-top'    => smarttoolz_responsive_spacing( $margin, 'top', 'mobile' ),
					'margin-bottom' => smarttoolz_responsive_spacing( $margin, 'bottom', 'mobile' ),
					'margin-left'   => smarttoolz_responsive_spacing( $margin, 'left', 'mobile' ),
					'margin-right'  => smarttoolz_responsive_spacing( $margin, 'right', 'mobile' ),
				),

				// Link Color.
				$selector . ' a'                         => array(
					'color' => $link_color_mobile,
				),

				// Link Hover Color.
				$selector . ' a:hover'                   => array(
					'color' => $link_h_color_mobile,
				),
			);
			$css_output .= smarttoolz_parse_css( $css_output_mobile, '', smarttoolz_get_mobile_breakpoint() );

			$generated_css .= $css_output;

			$generated_css .= SmartToolz_Builder_Base_Dynamic_CSS::prepare_advanced_typography_css( $_section, $selector );

			$generated_css .= SmartToolz_Builder_Base_Dynamic_CSS::prepare_visibility_css( $_section, $selector, $display_prop );
		}
		if ( true === $html_css_flag ) {
			$html_static_css = array(
				'.ast-builder-html-element img.alignnone' => array(
					'display' => 'inline-block',
				),
				'.ast-builder-html-element p:first-child' => array(
					'margin-top' => '0',
				),
				'.ast-builder-html-element p:last-child'  => array(
					'margin-bottom' => '0',
				),
				'.ast-header-break-point .main-header-bar .ast-builder-html-element' => array(
					'line-height' => '1.85714285714286',
				),
			);
			return smarttoolz_parse_css( $html_static_css ) . $generated_css;
		}

		return $generated_css;
	}
}

/**
 * Kicking this off by creating object of this class.
 */

new SmartToolz_Html_Component_Dynamic_CSS();
