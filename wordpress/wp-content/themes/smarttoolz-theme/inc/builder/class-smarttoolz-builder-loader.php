<?php
/**
 * SmartToolz Builder Loader.
 *
 * @package smarttoolz-builder
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SmartToolz_Builder_Loader' ) ) {

	/**
	 * Class SmartToolz_Builder_Loader.
	 */
	final class SmartToolz_Builder_Loader {
		/**
		 * Member Variable
		 *
		 * @var mixed instance
		 */
		private static $instance = null;

		/**
		 * Variable to hold menu locations rendered on the site.
		 *
		 * @var array Menu locations array
		 */
		private static $menu_locations = array();

		/**
		 *  Initiator
		 */
		public static function get_instance() {

			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
				do_action( 'smarttoolz_builder_loaded' );
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {

			// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
			/**
			 * Builder Core Files.
			 */
			require_once SMARTTOOLZ_THEME_DIR . 'inc/core/builder/class-smarttoolz-builder-helper.php';
			require_once SMARTTOOLZ_THEME_DIR . 'inc/core/builder/class-smarttoolz-builder-options.php';

			/**
			 * Builder - Header & Footer Markup.
			 */
			require_once SMARTTOOLZ_THEME_DIR . 'inc/builder/markup/class-smarttoolz-builder-header.php';
			require_once SMARTTOOLZ_THEME_DIR . 'inc/builder/markup/class-smarttoolz-builder-footer.php';

			/**
			 * Builder Controllers.
			 */
			require_once SMARTTOOLZ_THEME_DIR . 'inc/builder/controllers/class-smarttoolz-builder-widget-controller.php';
			require_once SMARTTOOLZ_THEME_DIR . 'inc/builder/controllers/class-smarttoolz-builder-ui-controller.php';
			/**
			 * Customizer - Configs.
			 */
			require_once SMARTTOOLZ_THEME_DIR . 'inc/customizer/class-smarttoolz-builder-customizer.php';

			/*DONE */

			if ( true === SmartToolz_Builder_Helper::$is_header_footer_builder_active ) {
				add_filter( 'smarttoolz_existing_header_footer_configs', '__return_false' );
				add_filter( 'smarttoolz_addon_existing_header_footer_configs', '__return_false' );
			}
			// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound

			add_action( 'customize_preview_init', array( $this, 'enqueue_preview_bundle' ), 110 );
			add_action( 'wp', array( $this, 'load_markup' ), 100 );

			add_filter( 'smarttoolz_quick_settings', array( $this, 'quick_settings' ) );
		}

		/**
		 * Update Quick Settings links.
		 *
		 * @param array $quick_settings Links to the Quick Settings in SmartToolz.
		 * @since 3.0.0
		 */
		public function quick_settings( $quick_settings ) {

			if ( false === SmartToolz_Builder_Helper::$is_header_footer_builder_active ) {
				return $quick_settings;
			}

			$quick_settings['header']['title']     = __( 'Header Builder', 'smarttoolz' );
			$quick_settings['header']['quick_url'] = admin_url( 'customize.php?autofocus[panel]=panel-header-builder-group' );

			$quick_settings['footer']['title']     = __( 'Footer Builder', 'smarttoolz' );
			$quick_settings['footer']['quick_url'] = admin_url( 'customize.php?autofocus[panel]=panel-footer-builder-group' );

			return $quick_settings;
		}

		/**
		 * Advanced Hooks markup loader
		 *
		 * Loads appropriate template file based on the style option selected in options panel.
		 *
		 * @since 3.0.0
		 */
		public function load_markup() {

			if ( ! defined( 'SMARTTOOLZ_ADVANCED_HOOKS_POST_TYPE' ) || false === SmartToolz_Builder_Helper::$is_header_footer_builder_active ) {
				return;
			}

			$option = array(
				'location'  => 'ast-advanced-hook-location',
				'exclusion' => 'ast-advanced-hook-exclusion',
				'users'     => 'ast-advanced-hook-users',
			);

			$result             = SmartToolz_Target_Rules_Fields::get_instance()->get_posts_by_conditions( SMARTTOOLZ_ADVANCED_HOOKS_POST_TYPE, $option );
			$header_counter     = 0;
			$footer_counter     = 0;
			$layout_404_counter = 0;

			foreach ( $result as $post_id => $post_data ) {
				$post_type = get_post_type();

				// Get the display devices condition for the post.
				$display_devices = get_post_meta( $post_id, 'ast-advanced-display-device', true );
				if ( ! is_array( $display_devices ) ) {
					$display_devices = array( 'desktop', 'tablet', 'mobile' );
				}

				if ( SMARTTOOLZ_ADVANCED_HOOKS_POST_TYPE !== $post_type ) {

					$layout = get_post_meta( $post_id, 'ast-advanced-hook-layout', false );

					if ( isset( $layout[0] ) && '404-page' === $layout[0] && 0 === $layout_404_counter ) {

						$layout_404_settings = get_post_meta( $post_id, 'ast-404-page', true );
						if ( isset( $layout_404_settings['disable_header'] ) && 'enabled' === $layout_404_settings['disable_header'] ) {
							remove_action( 'smarttoolz_header', array( SmartToolz_Builder_Header::get_instance(), 'header_builder_markup' ) );
						}

						if ( isset( $layout_404_settings['disable_footer'] ) && 'enabled' === $layout_404_settings['disable_footer'] ) {
							remove_action( 'smarttoolz_footer', array( SmartToolz_Builder_Footer::get_instance(), 'footer_markup' ) );
						}

						$layout_404_counter++;
					} elseif ( isset( $layout[0] ) && 'header' === $layout[0] && 0 === $header_counter ) {
						// Remove default site's header.
						remove_action( 'smarttoolz_header', array( SmartToolz_Builder_Header::get_instance(), 'header_builder_markup' ) );
						// Check if the post has 'ast-advanced-hook-enabled' meta key is not set to 'no'.
						$is_enabled = 'no' !== get_post_meta( $post_id, 'ast-advanced-hook-enabled', true );
						// Check if the custom header is enabled for all devices.
						$is_all_devices = 3 === count( $display_devices );

						if ( $is_enabled && $is_all_devices ) {
							// Prevent Off-Canvas markup on custom header rendering.
							add_filter( 'smarttoolz_disable_mobile_popup_markup', '__return_true' );
						}
						$header_counter++;
					} elseif ( isset( $layout[0] ) && 'footer' === $layout[0] && 0 === $footer_counter ) {
						// Remove default site's footer.
						remove_action( 'smarttoolz_footer', array( SmartToolz_Builder_Footer::get_instance(), 'footer_markup' ) );
						$footer_counter++;
					}
				}
			}
		}

		/**
		 * Enqueue the bundled builder components preview script and localize all data.
		 * Replaces 25 individual script enqueues with one HTTP request.
		 *
		 * @since 4.13.0
		 */
		public function enqueue_preview_bundle() {
			/** @psalm-suppress RedundantCondition */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$dir_name = SCRIPT_DEBUG ? 'unminified' : 'minified';
			/** @psalm-suppress RedundantCondition */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$file_prefix = SCRIPT_DEBUG ? '' : '.min';

			$bundle_handle   = 'ahfb-builder-customizer-preview';
			$component_limit = defined( 'SMARTTOOLZ_EXT_VER' ) ? SmartToolz_Builder_Helper::$component_limit : 0;

			wp_enqueue_script(
				$bundle_handle,
				SMARTTOOLZ_THEME_URI . 'inc/builder/type/assets/js/' . $dir_name . '/customizer-preview' . $file_prefix . '.js',
				array( 'customize-preview', 'smarttoolz-customizer-preview-js' ),
				SMARTTOOLZ_THEME_VERSION,
				true
			);

			// Breakpoints.
			wp_localize_script(
				$bundle_handle,
				'smarttoolzBuilderPreview',
				array(
					'tablet_break_point' => smarttoolz_get_tablet_breakpoint(),
					'mobile_break_point' => smarttoolz_get_mobile_breakpoint(),
				)
			);

			// Primary header.
			wp_localize_script(
				$bundle_handle,
				'SmartToolzBuilderPrimaryHeaderData',
				array(
					'header_break_point' => smarttoolz_header_break_point(),
				)
			);

			// Primary footer.
			wp_localize_script(
				$bundle_handle,
				'SmartToolzBuilderPrimaryFooterData',
				array(
					'footer_content_width' => smarttoolz_get_option( 'site-content-width' ),
				)
			);

			// Header button.
			wp_localize_script(
				$bundle_handle,
				'SmartToolzBuilderButtonData',
				array(
					'component_limit' => $component_limit ? $component_limit : SmartToolz_Builder_Helper::$num_of_header_button,
				)
			);

			// Footer button.
			wp_localize_script(
				$bundle_handle,
				'SmartToolzBuilderFooterButtonData',
				array(
					'component_limit' => $component_limit ? $component_limit : SmartToolz_Builder_Helper::$num_of_footer_button,
				)
			);

			// Header HTML.
			wp_localize_script(
				$bundle_handle,
				'SmartToolzBuilderHTMLData',
				array(
					'component_limit' => $component_limit ? $component_limit : SmartToolz_Builder_Helper::$num_of_header_html,
				)
			);

			// Footer HTML — uses a separate variable to avoid conflict with header HTML component_limit.
			wp_localize_script(
				$bundle_handle,
				'SmartToolzBuilderFooterHTMLData',
				array(
					'component_limit' => $component_limit ? $component_limit : SmartToolz_Builder_Helper::$num_of_footer_html,
				)
			);

			// Header menu.
			/** @psalm-suppress UndefinedClass */
			$nav_menu_enabled = defined( 'SMARTTOOLZ_EXT_VER' ) && SmartToolz_Ext_Extension::is_active( 'nav-menu' ) ? true : false;
			wp_localize_script(
				$bundle_handle,
				'SmartToolzBuilderMenuData',
				array(
					'component_limit'  => $component_limit ? $component_limit : SmartToolz_Builder_Helper::$num_of_header_menu,
					'nav_menu_enabled' => $nav_menu_enabled,
				)
			);

			// Widget (header + footer share variable).
			wp_localize_script(
				$bundle_handle,
				'SmartToolzBuilderWidgetData',
				array(
					'header_widget_count' => $component_limit ? $component_limit : SmartToolz_Builder_Helper::$num_of_header_widgets,
					'footer_widget_count' => $component_limit ? $component_limit : SmartToolz_Builder_Helper::$num_of_footer_widgets,
					'is_flex_based_css'   => SmartToolz_Builder_Helper::apply_flex_based_css(),
					'has_block_editor'    => smarttoolz_has_widgets_block_editor(),
				)
			);

			// Header social icons.
			wp_localize_script(
				$bundle_handle,
				'smarttoolzBuilderHeaderSocial',
				array(
					'component_limit' => $component_limit ? $component_limit : SmartToolz_Builder_Helper::$num_of_header_social_icons,
				)
			);

			// Footer social icons.
			wp_localize_script(
				$bundle_handle,
				'smarttoolzBuilderFooterSocial',
				array(
					'component_limit' => $component_limit ? $component_limit : SmartToolz_Builder_Helper::$num_of_footer_social_icons,
				)
			);
		}

		/**
		 * Method to add rel="nofollow" for markup
		 *
		 * @param string $theme_location Theme location for key.
		 * @param string $markup         Markup.
		 * @return string Menu markup with rel="nofollow".
		 * @since 4.6.14
		 */
		public function nofollow_markup( $theme_location, $markup ) {
			$nofollow_disabled = apply_filters( 'smarttoolz_disable_nofollow_markup', true );

			if ( $nofollow_disabled ) {
				return $markup;
			}

			if ( isset( self::$menu_locations[ $theme_location ] ) ) {
				$markup = str_replace( 'href="', 'rel="nofollow" href="', $markup );
			} else {
				self::$menu_locations[ $theme_location ] = true;
			}

			return $markup;
		}
	}

	/**
	 *  Prepare if class 'SmartToolz_Builder_Loader' exist.
	 *  Kicking this off by calling 'get_instance()' method
	 */
	SmartToolz_Builder_Loader::get_instance();
}

if ( ! function_exists( 'smarttoolz_builder' ) ) {
	/**
	 * Get global class.
	 *
	 * @return object
	 */
	function smarttoolz_builder() {
		return SmartToolz_Builder_Loader::get_instance();
	}
}
