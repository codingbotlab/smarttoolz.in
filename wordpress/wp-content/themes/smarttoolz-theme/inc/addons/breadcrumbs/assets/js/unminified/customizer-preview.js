/**
 * This file adds some LIVE to the Customizer live preview. To leverage
 * this, set your custom settings to 'postMessage' and then add your handling
 * here. Your javascript should grab settings from customizer controls, and
 * then make any necessary changes to the page using jQuery.
 *
 * @package SmartToolz
 * @since 1.7.0
 */

( function( $ ) {

	/* Breadcrumb Typography */
	smarttoolz_responsive_font_size(
		'smarttoolz-settings[breadcrumb-font-size]',
		'.ast-breadcrumbs-wrapper .trail-items span, .ast-breadcrumbs-wrapper a, .ast-breadcrumbs-wrapper .breadcrumb_last, .ast-breadcrumbs-wrapper span,  .ast-breadcrumbs-wrapper .breadcrumbs, .ast-breadcrumbs-wrapper .current-item, .ast-breadcrumbs-wrapper .last, .ast-breadcrumbs-wrapper .separator, .ast-breadcrumbs-wrapper .breadcrumb-item, .ast-breadcrumbs-wrapper .breadcrumb-item.active, .ast-breadcrumbs-wrapper .breadcrumb-item:after, .ast-breadcrumbs-inner nav, .ast-breadcrumbs-inner nav .breadcrumb-item, .ast-breadcrumbs-inner nav .breadcrumb-item:after'
	);
	smarttoolz_generate_outside_font_family_css(
		'smarttoolz-settings[breadcrumb-font-family]',
		'.ast-breadcrumbs-wrapper .trail-items span, .ast-breadcrumbs-wrapper a, .ast-breadcrumbs-wrapper .breadcrumb_last, .ast-breadcrumbs-wrapper span,  .ast-breadcrumbs-wrapper .breadcrumbs, .ast-breadcrumbs-wrapper .current-item, .ast-breadcrumbs-wrapper .last, .ast-breadcrumbs-wrapper .separator, .ast-breadcrumbs-wrapper .breadcrumb-item, .ast-breadcrumbs-wrapper .breadcrumb-item.active, .ast-breadcrumbs-wrapper .breadcrumb-item:after, .ast-breadcrumbs-inner nav, .ast-breadcrumbs-inner nav .breadcrumb-item, .ast-breadcrumbs-inner nav .breadcrumb-item:after'
	);
	smarttoolz_generate_font_weight_css( 'smarttoolz-settings[breadcrumb-font-family]', 'smarttoolz-settings[breadcrumb-font-weight]', 'font-weight', '.ast-breadcrumbs-wrapper .trail-items span, .ast-breadcrumbs-wrapper a, .ast-breadcrumbs-wrapper .breadcrumb_last, .ast-breadcrumbs-wrapper span,  .ast-breadcrumbs-wrapper .breadcrumbs, .ast-breadcrumbs-wrapper .current-item, .ast-breadcrumbs-wrapper .last, .ast-breadcrumbs-wrapper .separator, .ast-breadcrumbs-wrapper .breadcrumb-item, .ast-breadcrumbs-wrapper .breadcrumb-item.active, .ast-breadcrumbs-wrapper .breadcrumb-item:after, .ast-breadcrumbs-inner nav, .ast-breadcrumbs-inner nav .breadcrumb-item, .ast-breadcrumbs-inner nav .breadcrumb-item:after' );



	/* Breadcrumb default, Yoast SEO Breadcrumb, Breadcrumb NavXT, Ran Math Breadcrumb, SEOPress Breadcrumb - Line Height */
	smarttoolz_font_extras_css( 'breadcrumb-font-extras', '.ast-breadcrumbs-wrapper .ast-breadcrumbs-name, .ast-breadcrumbs-wrapper .ast-breadcrumbs-item, .ast-breadcrumbs-wrapper .ast-breadcrumbs .separator, .ast-breadcrumbs-wrapper a, .ast-breadcrumbs-wrapper .breadcrumb_last, .ast-breadcrumbs-wrapper span, .ast-breadcrumbs-wrapper a, .ast-breadcrumbs-wrapper .breadcrumbs, .ast-breadcrumbs-wrapper .current-item, .ast-breadcrumbs-wrapper a, .ast-breadcrumbs-wrapper .last, .ast-breadcrumbs-wrapper .separator, .ast-breadcrumbs-wrapper .breadcrumb-item, .ast-breadcrumbs-wrapper .breadcrumb-item.active, .ast-breadcrumbs-wrapper .breadcrumb-item:after, .ast-breadcrumbs-inner nav, .ast-breadcrumbs-inner nav .breadcrumb-item, .ast-breadcrumbs-inner nav .breadcrumb-item:after' );

	/* Breadcrumb default, Yoast SEO Breadcrumb, Breadcrumb NavXT, Ran Math Breadcrumb, SEOPress Breadcrumb - Text Color */
	smarttoolz_color_responsive_css(
		'breadcrumb',
		'smarttoolz-settings[breadcrumb-active-color-responsive]',
		'color',
		'.ast-breadcrumbs-wrapper .trail-items .trail-end, .ast-breadcrumbs-wrapper #ast-breadcrumbs-yoast .breadcrumb_last, .ast-breadcrumbs-wrapper .current-item, .ast-breadcrumbs-wrapper .last, .ast-breadcrumbs-inner, .ast-breadcrumbs-wrapper .breadcrumb-item.active'
	);

	/* Breadcrumb default, Yoast SEO Breadcrumb, Breadcrumb NavXT, Ran Math Breadcrumb, SEOPress Breadcrumb - Link Color */
	smarttoolz_color_responsive_css(
		'breadcrumb',
		'smarttoolz-settings[breadcrumb-text-color-responsive]',
		'color',
		'.ast-breadcrumbs-wrapper .trail-items a, .ast-breadcrumbs-wrapper #ast-breadcrumbs-yoast a, .ast-breadcrumbs-wrapper .breadcrumbs a, .ast-breadcrumbs-wrapper .rank-math-breadcrumb a, .ast-breadcrumbs-wrapper .breadcrumb-item a'
	);

	/* Breadcrumb default, Yoast SEO Breadcrumb, Breadcrumb NavXT, Ran Math Breadcrumb, SEOPress Breadcrumb - Hover Color */
	smarttoolz_color_responsive_css(
		'breadcrumb',
		'smarttoolz-settings[breadcrumb-hover-color-responsive]',
		'color',
		'.ast-breadcrumbs-wrapper .trail-items a:hover, .ast-breadcrumbs-wrapper #ast-breadcrumbs-yoast a:hover, .ast-breadcrumbs-wrapper .breadcrumbs a:hover, .ast-breadcrumbs-wrapper .rank-math-breadcrumb a:hover, .ast-breadcrumbs-wrapper .breadcrumb-item a:hover'
	);

	/* Breadcrumb default, Yoast SEO Breadcrumb, Breadcrumb NavXT, Ran Math Breadcrumb, SEOPress Breadcrumb - Separator Color */
	smarttoolz_color_responsive_css(
		'breadcrumb',
		'smarttoolz-settings[breadcrumb-separator-color]',
		'color',
		'.ast-breadcrumbs-wrapper .trail-items li::after, .ast-breadcrumbs-wrapper #ast-breadcrumbs-yoast, .ast-breadcrumbs-wrapper .breadcrumbs, .ast-breadcrumbs-wrapper .rank-math-breadcrumb .separator, .ast-breadcrumbs-wrapper .breadcrumb-item:after'
	);

	/* Breadcrumb default, Yoast SEO Breadcrumb, Breadcrumb NavXT, Ran Math Breadcrumb, SEOPress Breadcrumb - Background Color */
	smarttoolz_color_responsive_css(
		'breadcrumb',
		'smarttoolz-settings[breadcrumb-bg-color]',
		'background-color',
		'.ast-breadcrumbs-wrapper, .main-header-bar.ast-header-breadcrumb, .ast-primary-sticky-header-active .main-header-bar.ast-header-breadcrumb'
	);

	/* Breadcrumb default, Yoast SEO Breadcrumb, Breadcrumb NavXT, Ran Math Breadcrumb, SEOPress Breadcrumb - Alignment */
	smarttoolz_css(
		'smarttoolz-settings[breadcrumb-alignment]',
		'text-align',
		'.ast-breadcrumbs-wrapper'
	);

	/**
	 * Breadcrumb Spacing
	 */
	wp.customize( 'smarttoolz-settings[breadcrumb-spacing]', function( value ) {
		value.bind( function( padding ) {
			var spacing_value = wp.customize( 'smarttoolz-settings[breadcrumb-position]' ).get();
			if( 'smarttoolz_header_markup_after' == spacing_value || 'smarttoolz_header_after' == spacing_value ) {
				smarttoolz_responsive_spacing( 'smarttoolz-settings[breadcrumb-spacing]','.main-header-bar.ast-header-breadcrumb', 'padding',  ['top', 'right', 'bottom', 'left' ] );
			} else if( 'smarttoolz_masthead_content' == spacing_value ) {
				smarttoolz_responsive_spacing( 'smarttoolz-settings[breadcrumb-spacing]','.ast-breadcrumbs-wrapper .ast-breadcrumbs-inner #ast-breadcrumbs-yoast, .ast-breadcrumbs-wrapper .ast-breadcrumbs-inner .breadcrumbs, .ast-breadcrumbs-wrapper .ast-breadcrumbs-inner .rank-math-breadcrumb, .ast-breadcrumbs-wrapper .ast-breadcrumbs-inner .ast-breadcrumbs, .ast-breadcrumbs-wrapper .ast-breadcrumbs-inner', 'padding',  ['top', 'right', 'bottom', 'left' ] );
			} else {
				smarttoolz_responsive_spacing( 'smarttoolz-settings[breadcrumb-spacing]','.ast-breadcrumbs-wrapper #ast-breadcrumbs-yoast, .ast-breadcrumbs-wrapper .breadcrumbs, .ast-breadcrumbs-wrapper .rank-math-breadcrumb, .ast-breadcrumbs-wrapper .ast-breadcrumbs, .ast-breadcrumbs-wrapper .ast-breadcrumbs-inner', 'padding',  ['top', 'right', 'bottom', 'left' ] );
			}
		} );
	} );


	/**
	 * Breadcrumb Separator.
	 */
	wp.customize( 'smarttoolz-settings[breadcrumb-separator-selector]', function( value ) {
		value.bind( function( value ) {
			const customBreadcrumbValue = wp.customize.value( 'smarttoolz-settings[breadcrumb-separator]' )();
			const currentSelectedSeparator = 'unicode' !== value ? value : customBreadcrumbValue;
			let dynamicStyle = '';
				dynamicStyle += '.trail-items li::after {';
				dynamicStyle += 'content: "' + currentSelectedSeparator + '";';
				dynamicStyle += '} ';
				smarttoolz_add_dynamic_css( 'breadcrumb-separator-selector', dynamicStyle );
		} );
	} );

	wp.customize( 'smarttoolz-settings[breadcrumb-separator]', function( value ) {
		value.bind( function( value ) {
			let dynamicStyle = '';
                dynamicStyle += '.trail-items li::after {';
                dynamicStyle += 'content: "' + value + '";';
                dynamicStyle += '} ';
				smarttoolz_add_dynamic_css( 'breadcrumb-separator', dynamicStyle );
		} );
	} );

} )( jQuery );
