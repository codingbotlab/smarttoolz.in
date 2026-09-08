/**
 * This file adds some LIVE to the Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and
 * then make any necessary changes to the page using jQuery.
 *
 * @package SmartToolz
 * @since 3.0.0
 */

( function( $ ) {

	var headingSelectors = 'h1, h2, h3, h4, h5, h6, .entry-content :where(h1, h2, h3, h4, h5, h6)';

	if( smarttoolzHeadingColorOptions.maybeApplyHeadingColorForTitle ) {
		headingSelectors += ',.ast-archive-title, .entry-title a';
	}

	/**
	 * Content <h1> to <h6> headings
	 */
	smarttoolz_css( 'smarttoolz-settings[heading-base-color]', 'color', headingSelectors );


	function headingDynamicCss(slug) {
		let anchorSupport = '';
		let WidthTitleSupport = '';

		// Check if anchors should be loaded in the CSS for headings.
		if( smarttoolzCustomizer.includeAnchorsInHeadindsCss ) {
			anchorSupport = ',.entry-content ' + slug + ' a';
		}

		// Add widget title support to font-weight preview CSS.
		if( smarttoolzCustomizer.font_weights_widget_title_support ) {
			WidthTitleSupport = ',' + slug + '.widget-title';
		}

		smarttoolz_generate_outside_font_family_css( 'smarttoolz-settings[font-family-'+ slug +']', slug + ', .entry-content ' + slug + anchorSupport, true, '.ast-sg-typo-field[data-for="' + slug + '"] .ast-sg-font-family' );
		smarttoolz_generate_font_weight_css( 'smarttoolz-settings[font-family-'+ slug +']', 'smarttoolz-settings[font-weight-'+ slug +']', 'font-weight', slug + ', .entry-content ' + slug + anchorSupport + WidthTitleSupport );

		wp.customize( 'smarttoolz-settings[font-extras-'+ slug +']', function( value ) {

			value.bind( function( data ) {
				let elementorSupport = '';
				let dynamicStyle = '';

				if ( smarttoolzCustomizer.page_builder_button_style_css ) {
					elementorSupport = ',.elementor-widget-heading '+ slug +'.elementor-heading-title';
				}

					// Line Height
					const globalSelectorLineHeight = slug + ', .entry-content '+ slug + elementorSupport + anchorSupport;

					if( data['line-height'] && data['line-height-unit'] ) {
						dynamicStyle += globalSelectorLineHeight + '{';
						dynamicStyle += 'line-height : ' + data['line-height'] + data['line-height-unit'] + ';' ;
						dynamicStyle += '}';

						let styleGuideUpdatedEvent = new CustomEvent('SmartToolzStyleGuideElementUpdated', {
							'detail': {
								'value': data['line-height'] + data['line-height-unit'],
								'selector': '.ast-sg-typo-field[data-for="' + slug + '"] .ast-sg-line-height'
							}
						});
						document.dispatchEvent(styleGuideUpdatedEvent);
					}

					const globalSelector = slug +', .entry-content ' + slug + anchorSupport;

					if( data['letter-spacing'] || data['text-decoration'] || data['text-transform'] ) {
						dynamicStyle += globalSelector + '{';
						if( data['letter-spacing'] && data['letter-spacing-unit'] ) {
							dynamicStyle += 'letter-spacing : ' + data['letter-spacing'] + data['letter-spacing-unit'] + ";" ;
						}
						if( data['text-decoration'] ) {
							dynamicStyle += 'text-decoration : ' + data['text-decoration'] + ";";
						}
						if( data['text-transform'] ) {
							dynamicStyle += 'text-transform : ' + data['text-transform']  + ';' ;
						}

						dynamicStyle += '}';
					}
					smarttoolz_add_dynamic_css( 'font-extras-'+ slug, dynamicStyle );
			});
		});
	}


	headingDynamicCss('h1');
	headingDynamicCss('h2');
	headingDynamicCss('h3');
	headingDynamicCss('h4');
	headingDynamicCss('h5');
	headingDynamicCss('h6');

	let woo_button_attr = '';

	// WooCommerce global button compatibility for new users only.
	if( smarttoolzCustomizer.smarttoolz_woo_btn_global_compatibility ) {
		woo_button_attr = ', .woocommerce a.button, .woocommerce button.button, .woocommerce .woocommerce-message a.button, .woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt, .woocommerce input.button,.woocommerce-cart table.cart td.actions .button, .woocommerce form.checkout_coupon .button, .woocommerce #respond input#submit, .wc-block-grid__products .wc-block-grid__product .wp-block-button__link';
	}

	if ( smarttoolzCustomizer.page_builder_button_style_css ) {

		var ele_btn_font_family = '';
		var ele_btn_font_weight = '';
		var ele_btn_font_size = '';
		var ele_btn_transform = '';
		var ele_btn_line_height = '';
		var ele_btn_letter_spacing = '';

		if ( 'color-typo' == smarttoolzCustomizer.elementor_default_color_font_setting || 'typo' == smarttoolzCustomizer.elementor_default_color_font_setting ) {
			// Button Typo
			ele_btn_font_family = ',.elementor-widget-button .elementor-button, .elementor-widget-button .elementor-button:visited';
			ele_btn_font_weight = ',.elementor-widget-button .elementor-button, .elementor-widget-button .elementor-button:visited';
			ele_btn_font_size = ',.elementor-widget-button .elementor-button.elementor-size-sm, .elementor-widget-button .elementor-button.elementor-size-xs, .elementor-widget-button .elementor-button.elementor-size-md, .elementor-widget-button .elementor-button.elementor-size-lg, .elementor-widget-button .elementor-button.elementor-size-xl, .elementor-widget-button .elementor-button';
			ele_btn_transform = ',.elementor-widget-button .elementor-button, .elementor-widget-button .elementor-button:visited';
			ele_btn_line_height = ',.elementor-widget-button .elementor-button, .elementor-widget-button .elementor-button:visited';
			ele_btn_letter_spacing = ',.elementor-widget-button .elementor-button, .elementor-widget-button .elementor-button:visited', 'px';
		}
		// Button Typo
		smarttoolz_generate_outside_font_family_css( 'smarttoolz-settings[font-family-button]', 'button, .ast-button, .ast-custom-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"], .wp-block-button .wp-block-button__link, form[CLASS*="wp-block-search__"].wp-block-search .wp-block-search__inside-wrapper .wp-block-search__button' + smarttoolzCustomizer.v4_2_2_core_form_btns_styling + ele_btn_font_family + woo_button_attr );
		smarttoolz_generate_font_weight_css( 'smarttoolz-settings[font-family-button]', 'smarttoolz-settings[font-weight-button]', 'font-weight', 'button, .ast-button, .ast-custom-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"], .wp-block-button .wp-block-button__link, form[CLASS*="wp-block-search__"].wp-block-search .wp-block-search__inside-wrapper .wp-block-search__button' + smarttoolzCustomizer.v4_2_2_core_form_btns_styling + ele_btn_font_weight + woo_button_attr );
		smarttoolz_font_extras_css( 'font-extras-button', 'button, .ast-button, .ast-custom-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"], body .wp-block-button .wp-block-button__link, form[CLASS*="wp-block-search__"].wp-block-search .wp-block-search__inside-wrapper .wp-block-search__button' + smarttoolzCustomizer.v4_2_2_core_form_btns_styling + ele_btn_transform + woo_button_attr + smarttoolzCustomizer.improved_button_selector );

		smarttoolz_responsive_font_size( 'smarttoolz-settings[font-size-button]', 'button, .ast-button, .ast-custom-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"], .wp-block-button .wp-block-button__link, form[CLASS*="wp-block-search__"].wp-block-search .wp-block-search__inside-wrapper .wp-block-search__button' + smarttoolzCustomizer.v4_2_2_core_form_btns_styling + ele_btn_font_size + woo_button_attr );
		smarttoolz_css( 'smarttoolz-settings[theme-btn-line-height]', 'line-height', 'button, .ast-button, .ast-custom-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"], .wp-block-button .wp-block-button__link, form[CLASS*="wp-block-search__"].wp-block-search .wp-block-search__inside-wrapper .wp-block-search__button' + smarttoolzCustomizer.v4_2_2_core_form_btns_styling + ele_btn_line_height + woo_button_attr );
		smarttoolz_css( 'smarttoolz-settings[theme-btn-letter-spacing]', 'letter-spacing', 'button, .ast-button, .ast-custom-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"], .wp-block-button .wp-block-button__link, form[CLASS*="wp-block-search__"].wp-block-search .wp-block-search__inside-wrapper .wp-block-search__button' + smarttoolzCustomizer.v4_2_2_core_form_btns_styling + ele_btn_letter_spacing + woo_button_attr, 'px' );

	} else {
		// Button Typo
		smarttoolz_generate_outside_font_family_css( 'smarttoolz-settings[font-family-button]', 'button, .ast-button, .ast-custom-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"]' + smarttoolzCustomizer.v4_2_2_core_form_btns_styling + woo_button_attr );
		smarttoolz_generate_font_weight_css( 'smarttoolz-settings[font-family-button]', 'smarttoolz-settings[font-weight-button]', 'font-weight', 'button, .ast-button, .ast-custom-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"]' + smarttoolzCustomizer.v4_2_2_core_form_btns_styling + woo_button_attr );
		smarttoolz_font_extras_css( 'font-extras-button', 'button, .ast-button, .ast-custom-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"]' + smarttoolzCustomizer.v4_2_2_core_form_btns_styling + woo_button_attr );
		smarttoolz_responsive_font_size( 'smarttoolz-settings[font-size-button]', 'button, .ast-button, .ast-custom-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"]' + smarttoolzCustomizer.v4_2_2_core_form_btns_styling + woo_button_attr );
		smarttoolz_css( 'smarttoolz-settings[theme-btn-line-height]', 'line-height', 'button, .ast-button, .ast-custom-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"]' + smarttoolzCustomizer.v4_2_2_core_form_btns_styling + woo_button_attr );
		smarttoolz_css( 'smarttoolz-settings[theme-btn-letter-spacing]', 'letter-spacing', 'button, .ast-button, .ast-custom-button, input#submit, input[type="button"], input[type="submit"], input[type="reset"]' + smarttoolzCustomizer.v4_2_2_core_form_btns_styling + woo_button_attr , 'px' );
	}

	// Secondary button typo.
	let outline_btn_selector = 'body .wp-block-buttons .wp-block-button.is-style-outline .wp-block-button__link.wp-element-button, body .ast-outline-button, body .wp-block-uagb-buttons-child .uagb-buttons-repeater.ast-outline-button';
	smarttoolz_generate_outside_font_family_css( 'smarttoolz-settings[secondary-font-family-button]', outline_btn_selector );
	smarttoolz_generate_font_weight_css( 'smarttoolz-settings[secondary-font-family-button]', 'smarttoolz-settings[secondary-font-weight-button]', 'font-weight', outline_btn_selector );
	smarttoolz_font_extras_css( 'secondary-font-extras-button', outline_btn_selector );
	smarttoolz_responsive_font_size( 'smarttoolz-settings[secondary-font-size-button]', outline_btn_selector );
	smarttoolz_css( 'smarttoolz-settings[secondary-theme-btn-line-height]', 'line-height', outline_btn_selector );
	smarttoolz_css( 'smarttoolz-settings[secondary-theme-btn-letter-spacing]', 'letter-spacing', outline_btn_selector, 'px' );

} )( jQuery );
