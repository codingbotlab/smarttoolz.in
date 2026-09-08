<?php
/**
 * SmartToolz Builder Controller.
 *
 * @package smarttoolz-builder
 * @since 3.0.0
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Builder_Customizer.
 *
 * Customizer Configuration for Header Footer Builder.
 *
 * @since 3.0.0
 */
final class SmartToolz_Builder_Customizer {
	/**
	 * Constructor
	 *
	 * @since 3.0.0
	 */
	public function __construct() {

		add_action( 'customize_preview_init', array( $this, 'enqueue_customizer_preview_scripts' ) );
		add_action( 'customize_register', array( $this, 'woo_header_configs' ), 2 );

		$this->load_extended_components();

		if ( false === SmartToolz_Builder_Helper::$is_header_footer_builder_active ) {
			return;
		}

		require_once SMARTTOOLZ_THEME_DIR . 'inc/customizer/configurations/builder/class-smarttoolz-builder-base-configuration.php';
		// Base Config Files.
		require_once SMARTTOOLZ_THEME_DIR . 'inc/customizer/configurations/builder/base/class-smarttoolz-social-icon-component-configs.php';
		require_once SMARTTOOLZ_THEME_DIR . 'inc/customizer/configurations/builder/base/class-smarttoolz-html-component-configs.php';
		require_once SMARTTOOLZ_THEME_DIR . 'inc/customizer/configurations/builder/base/class-smarttoolz-button-component-configs.php';

		define( 'SMARTTOOLZ_HEADER_BUILDER_CONFIGS_DIR', SMARTTOOLZ_THEME_DIR . 'inc/customizer/configurations/builder/header/configs/' );
		foreach ( scandir( SMARTTOOLZ_HEADER_BUILDER_CONFIGS_DIR ) as $config_file ) {
			$path = SMARTTOOLZ_HEADER_BUILDER_CONFIGS_DIR . $config_file;
			if ( is_file( $path ) ) {
				require_once $path;
			}
		}

		define( 'SMARTTOOLZ_FOOTER_BUILDER_CONFIGS_DIR', SMARTTOOLZ_THEME_DIR . 'inc/customizer/configurations/builder/footer/configs/' );
		foreach ( scandir( SMARTTOOLZ_FOOTER_BUILDER_CONFIGS_DIR ) as $config_file ) {
			$path = SMARTTOOLZ_FOOTER_BUILDER_CONFIGS_DIR . $config_file;
			if ( is_file( $path ) ) {
				require_once $path;
			}
		}

		$this->load_base_components();

		add_action( 'customize_register', array( $this, 'builder_configs' ), 2 );
		add_action( 'customize_register', array( $this, 'header_configs' ), 2 );
		add_action( 'customize_register', array( $this, 'footer_configs' ), 2 );
		add_action( 'customize_register', array( $this, 'update_default_wp_configs' ) );
		add_action( 'init', array( $this, 'deregister_menu_locations_widgets' ), 999 );
		add_action( 'customize_controls_print_footer_scripts', array( $this, 'builder_customizer_preview_styles' ) );
	}

	/**
	 * Update default WP configs.
	 *
	 * @param object $wp_customize customizer object.
	 */
	public function update_default_wp_configs( $wp_customize ) {

		$wp_customize->get_control( 'custom_logo' )->priority     = 2;
		$wp_customize->get_control( 'blogname' )->priority        = 8;
		$wp_customize->get_control( 'blogdescription' )->priority = 12;

		$wp_customize->get_setting( 'custom_logo' )->transport     = 'postMessage';
		$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

		$wp_customize->get_section( 'title_tagline' )->panel = 'panel-header-builder-group';

		$wp_customize->selective_refresh->add_partial(
			'custom_logo',
			array(
				'selector'            => '.site-branding',
				'container_inclusive' => true,
				'render_callback'     => 'SmartToolz_Builder_Header::site_identity',
			)
		);

		// @codingStandardsIgnoreStart PHPCompatibility.FunctionDeclarations.NewClosure.Found
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => static function() {
					bloginfo( 'description' );
				},
			)
		);

		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title',
				'render_callback' => static function() {
					bloginfo( 'name' );
				},
			)
		);

		// @codingStandardsIgnoreStart PHPCompatibility.FunctionDeclarations.NewClosure.Found
	}

	/**
	 * Function to remove old Header and Footer Menu location and widgets.
	 *
	 * @since 3.0.0
	 * @return void
	 */
	public function deregister_menu_locations_widgets() {

		// Remove Header Menus locations.
		unregister_nav_menu( 'above_header_menu' );
		unregister_nav_menu( 'below_header_menu' );

		// Remove Header Widgets.
		unregister_sidebar( 'above-header-widget-1' );
		unregister_sidebar( 'above-header-widget-2' );
		unregister_sidebar( 'below-header-widget-1' );
		unregister_sidebar( 'below-header-widget-2' );

		// Remove Footer Widgets.
		unregister_sidebar( 'advanced-footer-widget-1' );
		unregister_sidebar( 'advanced-footer-widget-2' );
		unregister_sidebar( 'advanced-footer-widget-3' );
		unregister_sidebar( 'advanced-footer-widget-4' );
		unregister_sidebar( 'advanced-footer-widget-5' );
	}

	/**
	 * Attach customize_controls_print_footer_scripts preview styles conditionally.
	 *
	 * @since 3.0.0
	 */
	public function builder_customizer_preview_styles() {
		/**
		 * Added SmartToolz Pro dependent customizer style.
		 */
		if ( is_customize_preview() ) {
			echo '<style type="text/css">
				.ahfb-builder-mode-header[data-row="above"] .ahfb-row-actions, .ahfb-builder-mode-header[data-row="below"] .ahfb-row-actions, .ahfb-builder-mode-footer[data-row="above"] .ahfb-row-actions, .ahfb-builder-mode-footer[data-row="primary"] .ahfb-row-actions {
					cursor: pointer;
				}
			</style>';
			if ( smarttoolz_wp_version_compare( '6.1', '<' ) ) {
				echo '<style type="text/css" class="smarttoolz-wp-6-0-builder-popover-compatibility">
					.components-popover.ahfb-popover-add-builder {
						left: 50% !important;
						top: 0 !important;
						position: absolute;
						bottom: auto;
					}
					.ahfb-builder-group .ahfb-builder-area:nth-child(3) .ahfb-builder-add-item.center-on-left .components-popover.ahfb-popover-add-builder, .ahfb-builder-group .ahfb-builder-area:nth-child(4) .ahfb-builder-add-item .components-popover.ahfb-popover-add-builder, .ahfb-builder-group .ahfb-builder-area:nth-child(5) .ahfb-builder-add-item .components-popover.ahfb-popover-add-builder, .ahfb-builder-group.ast-grid-row-layout-3-cwide .ahfb-builder-area-3 .ahfb-builder-add-item .components-popover.ahfb-popover-add-builder {
						left: -20% !important;
					}
					.ahfb-builder-group.ast-grid-row-layout-6-equal .ahfb-builder-area-6 .ahfb-builder-add-item .components-popover.ahfb-popover-add-builder {
						left: -35% !important;
					}
					.customize-control-ast-builder .components-popover.ahfb-popover-add-builder[data-x-axis="center"] {
						left: 160px !important;
					}
					.customize-control-ast-builder .components-popover.ahfb-popover-add-builder[data-x-axis="right"] {
						left: 0px !important;
					}
					.components-popover.ahfb-popover-add-builder .components-popover__content {
						bottom: 0;
					}
					</style>
				';
			}
			if ( smarttoolz_wp_version_compare( '6.2', '>=' ) ) {
				echo '<style type="text/css" class="smarttoolz-wp-6-2-builder-popover-compatibility">
					.popup-vertical-group .components-popover.ahfb-popover-add-builder {
						left: 18% !important;
					}
					</style>
				';
			}
		}
	}

	/**
	 * Add Customizer preview script.
	 *
	 * @since 3.0.0
	 */
	public function enqueue_customizer_preview_scripts() {
		// Bail early if it is not smarttoolz customizer.
		if ( ! SmartToolz_Customizer::is_smarttoolz_customizer() ) {
			return;
		}

		// Enqueue Builder CSS.
		wp_enqueue_style(
			'ahfb-customizer-preview-style',
			SMARTTOOLZ_THEME_URI . 'inc/assets/css/customizer-preview.css',
			null,
			SMARTTOOLZ_THEME_VERSION
		);

		// Advanced Dynamic CSS.
		$js_prefix = SCRIPT_DEBUG ? '' : 'minified/';
		$js_suffix = SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_script(
			'ahfb-customizer-preview',
			SMARTTOOLZ_THEME_URI . 'inc/assets/js/' . $js_prefix . 'customizer-preview' . $js_suffix . '.js',
			array( 'customize-preview' ),
			SMARTTOOLZ_THEME_VERSION,
			true
		);

		wp_localize_script(
			'ahfb-customizer-preview',
			'smarttoolzBuilderCustomizer',
			array(
				'ajaxurl'    => admin_url( 'admin-ajax.php' ),
				'ajax_nonce' => wp_create_nonce( 'smarttoolz-builder-customizer-nonce' ),
			)
		);
	}

	/**
	 * Register Some extended work for both old-new header footer layouts.
	 *
	 * @since 4.6.5
	 */
	public function load_extended_components() {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once SMARTTOOLZ_THEME_DIR . 'inc/customizer/class-smarttoolz-extended-base-configuration.php';
		require_once SMARTTOOLZ_THEME_DIR . 'inc/class-smarttoolz-extended-base-dynamic-css.php';
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Register Base Components for Builder.
	 */
	public function load_base_components() {

		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		require_once SMARTTOOLZ_THEME_DIR . 'inc/builder/type/class-smarttoolz-builder-base-dynamic-css.php';

		// Base Dynamic CSS Files.
		require_once SMARTTOOLZ_THEME_DIR . 'inc/builder/type/base/dynamic-css/html/class-smarttoolz-html-component-dynamic-css.php';
		require_once SMARTTOOLZ_THEME_DIR . 'inc/builder/type/base/dynamic-css/social/class-smarttoolz-social-component-dynamic-css.php';
		require_once SMARTTOOLZ_THEME_DIR . 'inc/builder/type/base/dynamic-css/button/class-smarttoolz-button-component-dynamic-css.php';
		require_once SMARTTOOLZ_THEME_DIR . 'inc/builder/type/base/dynamic-css/widget/class-smarttoolz-widget-component-dynamic-css.php';

		$this->load_header_components();
		$this->load_footer_components();
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Register Components for Header Builder.
	 *
	 * @since 3.0.0
	 */
	public function load_header_components() {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		$header_components_path = SMARTTOOLZ_THEME_DIR . 'inc/builder/type/header';
		require_once $header_components_path . '/site-identity/class-smarttoolz-header-site-identity-component.php';
		require_once $header_components_path . '/off-canvas/class-smarttoolz-off-canvas.php';
		require_once $header_components_path . '/primary-header/class-smarttoolz-primary-header.php';
		require_once $header_components_path . '/button/class-smarttoolz-header-button-component.php';
		require_once $header_components_path . '/menu/class-smarttoolz-header-menu-component.php';
		require_once $header_components_path . '/html/class-smarttoolz-header-html-component.php';
		require_once $header_components_path . '/search/class-smarttoolz-header-search-component.php';
		require_once $header_components_path . '/account/class-smarttoolz-header-account-component.php';
		require_once $header_components_path . '/social-icon/class-smarttoolz-header-social-icon-component.php';
		require_once $header_components_path . '/widget/class-smarttoolz-header-widget-component.php';
		require_once $header_components_path . '/mobile-trigger/class-smarttoolz-mobile-trigger.php';
		require_once $header_components_path . '/mobile-menu/class-smarttoolz-mobile-menu-component.php';

		require_once $header_components_path . '/above-header/class-smarttoolz-above-header.php';
		require_once $header_components_path . '/below-header/class-smarttoolz-below-header.php';

		if ( class_exists( 'SmartToolz_Woocommerce' ) ) {
			require_once $header_components_path . '/woo-cart/class-smarttoolz-header-woo-cart-component.php';
		}

		if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			require_once $header_components_path . '/edd-cart/class-smarttoolz-header-edd-cart-component.php';
		}

		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Register Components for Footer Builder.
	 *
	 * @since 3.0.0
	 */
	public function load_footer_components() {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		$footer_components_path = SMARTTOOLZ_THEME_DIR . 'inc/builder/type/footer';
		require_once $footer_components_path . '/below-footer/class-smarttoolz-below-footer.php';
		require_once $footer_components_path . '/menu/class-smarttoolz-footer-menu-component.php';
		require_once $footer_components_path . '/html/class-smarttoolz-footer-html-component.php';
		require_once $footer_components_path . '/button/class-smarttoolz-footer-button-component.php';
		require_once $footer_components_path . '/copyright/class-smarttoolz-footer-copyright-component.php';
		require_once $footer_components_path . '/social-icon/class-smarttoolz-footer-social-icons-component.php';
		require_once $footer_components_path . '/above-footer/class-smarttoolz-above-footer.php';
		require_once $footer_components_path . '/primary-footer/class-smarttoolz-primary-footer.php';
		require_once $footer_components_path . '/widget/class-smarttoolz-footer-widget-component.php';
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Register controls for Header/Footer Builder.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 * @since 3.0.0
	 */
	public function builder_configs( $wp_customize ) {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		$builder_config_path = SMARTTOOLZ_THEME_DIR . 'inc/customizer/configurations/builder/';
		// Header Builder.
		require_once $builder_config_path . '/header/class-smarttoolz-customizer-header-builder-configs.php';
		// Footer Builder.
		require_once $builder_config_path . '/footer/class-smarttoolz-customizer-footer-builder-configs.php';
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Register controls for Header Builder.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 * @since 3.0.0
	 */
	public function header_configs( $wp_customize ) {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		$header_config_path = SMARTTOOLZ_THEME_DIR . 'inc/customizer/configurations/builder/header';
		require_once $header_config_path . '/class-smarttoolz-customizer-above-header-configs.php';
		require_once $header_config_path . '/class-smarttoolz-customizer-below-header-configs.php';
		require_once $header_config_path . '/class-smarttoolz-customizer-header-builder-configs.php';
		require_once $header_config_path . '/class-smarttoolz-customizer-header-widget-configs.php';
		require_once $header_config_path . '/class-smarttoolz-customizer-mobile-trigger-configs.php';
		require_once $header_config_path . '/class-smarttoolz-customizer-off-canvas-configs.php';
		require_once $header_config_path . '/class-smarttoolz-customizer-primary-header-configs.php';
		require_once $header_config_path . '/class-smarttoolz-customizer-site-identity-configs.php';
		require_once $header_config_path . '/class-smarttoolz-header-button-component-configs.php';
		require_once $header_config_path . '/class-smarttoolz-header-html-component-configs.php';
		require_once $header_config_path . '/class-smarttoolz-header-menu-component-configs.php';
		require_once $header_config_path . '/class-smarttoolz-header-search-component-configs.php';
		require_once $header_config_path . '/class-smarttoolz-header-account-component-configs.php';
		require_once $header_config_path . '/class-smarttoolz-header-social-icon-component-configs.php';

		if ( class_exists( 'SmartToolz_Woocommerce' ) ) {
			require_once $header_config_path . '/class-smarttoolz-customizer-woo-cart-configs.php';
		}

		if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			require_once $header_config_path . '/class-smarttoolz-customizer-edd-cart-configs.php';
		}

		require_once $header_config_path . '/class-smarttoolz-mobile-menu-component-configs.php';
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Register controls for Footer Builder.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 * @since 3.0.0
	 */
	public function footer_configs( $wp_customize ) {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		$footer_config_path = SMARTTOOLZ_THEME_DIR . 'inc/customizer/configurations/builder/footer';
		require_once $footer_config_path . '/class-smarttoolz-customizer-above-footer-configs.php';
		require_once $footer_config_path . '/class-smarttoolz-customizer-below-footer-configs.php';
		require_once $footer_config_path . '/class-smarttoolz-customizer-copyright-configs.php';
		require_once $footer_config_path . '/class-smarttoolz-customizer-footer-builder-configs.php';
		require_once $footer_config_path . '/class-smarttoolz-customizer-footer-menu-configs.php';
		require_once $footer_config_path . '/class-smarttoolz-customizer-footer-social-icons-configs.php';
		require_once $footer_config_path . '/class-smarttoolz-customizer-primary-footer-configs.php';
		require_once $footer_config_path . '/class-smarttoolz-footer-html-component-configs.php';
		require_once $footer_config_path . '/class-smarttoolz-footer-button-component-configs.php';
		require_once $footer_config_path . '/class-smarttoolz-footer-widget-component-configs.php';
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Register Woocommerce controls for new and old Header Builder.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 * @since 3.0.0
	 */
	public function woo_header_configs( $wp_customize ) {
		// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
		$header_config_path = SMARTTOOLZ_THEME_DIR . 'inc/customizer/configurations/builder/header';

		if ( class_exists( 'SmartToolz_Woocommerce' ) ) {
			require_once $header_config_path . '/class-smarttoolz-customizer-woo-cart-configs.php';
		}

		if ( class_exists( 'Easy_Digital_Downloads' ) ) {
			require_once $header_config_path . '/class-smarttoolz-customizer-edd-cart-configs.php';
		}
		// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
	}

	/**
	 * Collect Customizer Builder Data to process further.
	 *
	 * @since 4.5.2
	 * @return bool
	 */
	public static function smarttoolz_collect_customizer_builder_data() {
		return ! is_customize_preview() && apply_filters( 'smarttoolz_collect_customizer_builder_data', false ) ? true : false;
	}
}

/**
 *  Prepare if class 'SmartToolz_Builder_Customizer' exist.
 *  Kicking this off by creating new object of the class.
 */
new SmartToolz_Builder_Customizer();
