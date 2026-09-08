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

if ( ! class_exists( 'SmartToolz_Builder_Header' ) ) {

	/**
	 * Class SmartToolz_Builder_Header.
	 */
	final class SmartToolz_Builder_Header {
		/**
		 * Member Variable
		 *
		 * @var mixed instance
		 */
		private static $instance = null;

		/**
		 * Dynamic Methods.
		 *
		 * @var array dynamic methods
		 */
		private static $methods = array();

		/**
		 *  Initiator
		 *
		 * @return object initialized SmartToolz_Builder_Header class
		 */
		public static function get_instance() {

			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Constructor
		 */
		public function __construct() {

			add_action( 'smarttoolz_header', array( $this, 'global_smarttoolz_header' ), 0 );

			if ( true === SmartToolz_Builder_Helper::$is_header_footer_builder_active ) {

				$this->remove_existing_actions();

				add_action( 'body_class', array( $this, 'add_body_class' ) );
				// Header Desktop Builder.
				add_action( 'smarttoolz_masthead', array( $this, 'desktop_header' ) );
				add_action( 'smarttoolz_above_header', array( $this, 'above_header' ) );
				add_action( 'smarttoolz_primary_header', array( $this, 'primary_header' ) );
				add_action( 'smarttoolz_below_header', array( $this, 'below_header' ) );
				add_action( 'smarttoolz_render_header_column', array( $this, 'render_column' ), 10, 2 );
				// Mobile Builder.
				add_action( 'smarttoolz_mobile_header', array( $this, 'mobile_header' ) );
				add_action( 'smarttoolz_mobile_above_header', array( $this, 'mobile_above_header' ) );
				add_action( 'smarttoolz_mobile_primary_header', array( $this, 'mobile_primary_header' ) );
				add_action( 'smarttoolz_mobile_below_header', array( $this, 'mobile_below_header' ) );
				add_action( 'smarttoolz_render_mobile_header_column', array( $this, 'render_mobile_column' ), 10, 2 );
				// Load Off-Canvas Markup on Footer.
				add_action( 'smarttoolz_footer', array( $this, 'mobile_popup' ) );
				add_action( 'smarttoolz_mobile_header_content', array( $this, 'render_mobile_column' ), 10, 2 );
				add_action( 'smarttoolz_render_mobile_popup', array( $this, 'render_mobile_column' ), 10, 2 );

				for ( $index = 1; $index <= SmartToolz_Builder_Helper::$component_limit; $index++ ) {
					// Buttons.
					add_action( 'smarttoolz_header_button_' . $index, array( $this, 'button_' . $index ) );
					self::$methods[] = 'button_' . $index;
					// Htmls.
					add_action( 'smarttoolz_header_html_' . $index, array( $this, 'header_html_' . $index ) );
					self::$methods[] = 'header_html_' . $index;
					// Social Icons.
					add_action( 'smarttoolz_header_social_' . $index, array( $this, 'header_social_' . $index ) );
					self::$methods[] = 'header_social_' . $index;
					// Menus.
					add_action( 'smarttoolz_header_menu_' . $index, array( $this, 'menu_' . $index ) );
					self::$methods[] = 'menu_' . $index;
				}

				add_action( 'smarttoolz_mobile_site_identity', self::class . '::site_identity' );
				add_action( 'smarttoolz_header_search', array( $this, 'header_search' ), 10, 1 );
				add_action( 'smarttoolz_header_woo_cart', array( $this, 'header_woo_cart' ), 10, 1 );
				add_action( 'smarttoolz_header_edd_cart', array( $this, 'header_edd_cart' ) );
				add_action( 'smarttoolz_header_account', array( $this, 'header_account' ) );
				add_action( 'smarttoolz_header_mobile_trigger', array( $this, 'header_mobile_trigger' ) );

				// Load Cart Flyout Markup on Footer.
				add_action( 'smarttoolz_footer', array( $this, 'mobile_cart_flyout' ) );
				add_action( 'smarttoolz_header_menu_mobile', array( $this, 'header_mobile_menu_markup' ) );
			}

			add_action( 'smarttoolz_site_identity', self::class . '::site_identity' );
		}

		/**
		 * Callback when method not exists.
		 *
		 * @param  string $func function name.
		 * @param array  $params function parameters.
		 */
		public function __call( $func, $params ) {

			if ( in_array( $func, self::$methods, true ) ) {
				if ( 0 === strpos( $func, 'header_html_' ) ) {
					SmartToolz_Builder_UI_Controller::render_html_markup( str_replace( '_', '-', $func ) );
				} elseif ( 0 === strpos( $func, 'button_' ) ) {
					$index = (int) substr( $func, strrpos( $func, '_' ) + 1 );
					if ( $index ) {
						SmartToolz_Builder_UI_Controller::render_button( $index, 'header' );
					}
				} elseif ( 0 === strpos( $func, 'menu_' ) ) {
					$index = (int) substr( $func, strrpos( $func, '_' ) + 1 );
					if ( $index ) {
						SmartToolz_Header_Menu_Component::menu_markup( $index, $params['0'] );
					}
				} elseif ( 0 === strpos( $func, 'header_social_' ) ) {
					$index = (int) substr( $func, strrpos( $func, '_' ) + 1 );
					if ( $index ) {
						SmartToolz_Builder_UI_Controller::render_social_icon( $index, 'header' );
					}
				}
			}
		}

		/**
		 * Remove complete header Support on basis of meta option.
		 *
		 * @since 3.8.0
		 * @return void
		 */
		public function global_smarttoolz_header() {
			$display = get_post_meta( absint( smarttoolz_get_post_id() ), 'ast-global-header-display', true );
			$display = apply_filters( 'smarttoolz_header_display', $display );
			if ( 'disabled' === $display ) {
				remove_action( 'smarttoolz_header', 'smarttoolz_header_markup' );
				/** @psalm-suppress DocblockTypeContradiction */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				if ( true === SmartToolz_Builder_Helper::$is_header_footer_builder_active ) { // phpcs:ignore PHPCompatibility.Keywords.NewKeywords.t_namespaceFound, PHPCompatibility.LanguageConstructs.NewLanguageConstructs.t_ns_separatorFound
					remove_action( 'smarttoolz_header', array( $this, 'header_builder_markup' ) ); // phpcs:ignore PHPCompatibility.Keywords.NewKeywords.t_namespaceFound, PHPCompatibility.LanguageConstructs.NewLanguageConstructs.t_ns_separatorFound
				}
			}
		}

		/**
		 * Inherit Header base layout.
		 * Do all actions for header.
		 */
		public function header_builder_markup() {
			do_action( 'smarttoolz_header' );
		}

		/**
		 * Remove existing Header to load Header Builder.
		 *
		 * @since 3.0.0
		 * @return void
		 */
		public function remove_existing_actions() {
			remove_action( 'smarttoolz_masthead', 'smarttoolz_masthead_primary_template' );
			remove_action( 'smarttoolz_masthead_content', 'smarttoolz_primary_navigation_markup', 10 );

			remove_filter( 'wp_page_menu_args', 'smarttoolz_masthead_custom_page_menu_items', 10 );
			remove_filter( 'wp_nav_menu_items', 'smarttoolz_masthead_custom_nav_menu_items' );
		}

		/**
		 * Header Mobile trigger
		 */
		public function header_mobile_trigger() {
			SmartToolz_Builder_UI_Controller::render_mobile_trigger();
		}

		/**
		 * Render WooCommerce Cart.
		 *
		 * @param string $device Either 'mobile' or 'desktop' option.
		 */
		public function header_woo_cart( $device = 'desktop' ) {
			if ( class_exists( 'SmartToolz_Woocommerce' ) ) {
				echo SmartToolz_Woocommerce::get_instance()->woo_mini_cart_markup( $device ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		/**
		 * Render EDD Cart.
		 */
		public function header_edd_cart() {
			if ( class_exists( 'Easy_Digital_Downloads' ) ) {
				echo SmartToolz_Edd::get_instance()->edd_mini_cart_markup(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		/**
		 * Render account icon.
		 */
		public function header_account() {
			SmartToolz_Builder_UI_Controller::render_account();
		}

		/**
		 * Render Search icon.
		 *
		 * @param  string $device   Device name.
		 */
		public function header_search( $device = 'desktop' ) {
			echo smarttoolz_get_search( '', $device ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		/**
		 * Render site logo.
		 *
		 * @param  string $device   Device name.
		 */
		public static function site_identity( $device = 'desktop' ) {
			SmartToolz_Builder_UI_Controller::render_site_identity( $device );
		}

		/**
		 * Call component header UI.
		 *
		 * @param string $row row.
		 * @param string $column column.
		 */
		public function render_column( $row, $column ) {
			SmartToolz_Builder_Helper::render_builder_markup( $row, $column, 'desktop', 'header' );
		}

		/**
		 * Render desktop header layout.
		 */
		public function desktop_header() {
			get_template_part( 'template-parts/header/builder/desktop-builder-layout' );
		}

		/**
		 *  Call above header UI.
		 */
		public function above_header() {

			$display = is_singular() ? get_post_meta( get_the_ID(), 'ast-hfb-above-header-display', true ) : true;
			$display = apply_filters( 'smarttoolz_above_header_display', $display );

			if ( 'disabled' !== $display ) {
				if ( smarttoolz_wp_version_compare( '5.4.99', '>=' ) ) {
					get_template_part(
						'template-parts/header/builder/header',
						'row',
						array(
							'row' => 'above',
						)
					);
				} else {
					set_query_var( 'row', 'above' );
					get_template_part( 'template-parts/header/builder/header', 'row' );
				}
			}
		}

		/**
		 *  Call primary header UI.
		 */
		public function primary_header() {

			$display = is_singular() ? get_post_meta( get_the_ID(), 'ast-main-header-display', true ) : true;
			$display = apply_filters( 'smarttoolz_main_header_display', $display );

			if ( 'disabled' !== $display ) {
				if ( smarttoolz_wp_version_compare( '5.4.99', '>=' ) ) {
					get_template_part(
						'template-parts/header/builder/header',
						'row',
						array(
							'row' => 'primary',
						)
					);
				} else {
					set_query_var( 'row', 'primary' );
					get_template_part( 'template-parts/header/builder/header', 'row' );
				}
			}
		}

		/**
		 *  Call below header UI.
		 */
		public function below_header() {

			$display = is_singular() ? get_post_meta( get_the_ID(), 'ast-hfb-below-header-display', true ) : true;
			$display = apply_filters( 'smarttoolz_below_header_display', $display );

			if ( 'disabled' !== $display ) {
				if ( smarttoolz_wp_version_compare( '5.4.99', '>=' ) ) {
					get_template_part(
						'template-parts/header/builder/header',
						'row',
						array(
							'row' => 'below',
						)
					);
				} else {
					set_query_var( 'row', 'below' );
					get_template_part( 'template-parts/header/builder/header', 'row' );
				}
			}
		}

		/**
		 * Call mobile component header UI.
		 *
		 * @param string $row row.
		 * @param string $column column.
		 */
		public function render_mobile_column( $row, $column ) {
			SmartToolz_Builder_Helper::render_builder_markup( $row, $column, 'mobile', 'header' );
		}

		/**
		 * Render Mobile header layout.
		 */
		public function mobile_header() {
			get_template_part( 'template-parts/header/builder/mobile-builder-layout' );
		}

		/**
		 *  Call Mobile above header UI.
		 */
		public function mobile_above_header() {

			$display = is_singular() ? get_post_meta( get_the_ID(), 'ast-hfb-mobile-header-display', true ) : true;
			$display = apply_filters( 'smarttoolz_above_mobile_header_display', $display );

			if ( 'disabled' !== $display ) {
				if ( smarttoolz_wp_version_compare( '5.4.99', '>=' ) ) {
					get_template_part(
						'template-parts/header/builder/mobile-header',
						'row',
						array(
							'row' => 'above',
						)
					);
				} else {
					set_query_var( 'row', 'above' );
					get_template_part( 'template-parts/header/builder/mobile-header', 'row' );
				}
			}
		}

		/**
		 *  Call Mobile primary header UI.
		 */
		public function mobile_primary_header() {

			$display = is_singular() ? get_post_meta( get_the_ID(), 'ast-hfb-mobile-header-display', true ) : true;
			$display = apply_filters( 'smarttoolz_primary_mobile_header_display', $display );

			if ( 'disabled' !== $display ) {
				if ( smarttoolz_wp_version_compare( '5.4.99', '>=' ) ) {
					get_template_part(
						'template-parts/header/builder/mobile-header',
						'row',
						array(
							'row' => 'primary',
						)
					);
				} else {
					set_query_var( 'row', 'primary' );
					get_template_part( 'template-parts/header/builder/mobile-header', 'row' );
				}
			}
		}

		/**
		 *  Call Mobile below header UI.
		 */
		public function mobile_below_header() {

			$display = is_singular() ? get_post_meta( absint( smarttoolz_get_post_id() ), 'ast-hfb-mobile-header-display', true ) : true;
			$display = apply_filters( 'smarttoolz_below_mobile_header_display', $display );

			if ( 'disabled' !== $display ) {
				if ( smarttoolz_wp_version_compare( '5.4.99', '>=' ) ) {
					get_template_part(
						'template-parts/header/builder/mobile-header',
						'row',
						array(
							'row' => 'below',
						)
					);
				} else {
					set_query_var( 'row', 'below' );
					get_template_part( 'template-parts/header/builder/mobile-header', 'row' );
				}
			}
		}
		/**
		 *  Call Mobile Popup UI.
		 */
		public function mobile_popup() {

			if ( apply_filters( 'smarttoolz_disable_mobile_popup_markup', false ) ) {
				return;
			}

			$mobile_header_type = smarttoolz_get_option( 'mobile-header-type' );

			if ( 'off-canvas' === $mobile_header_type || 'full-width' === $mobile_header_type || is_customize_preview() ) {
				SmartToolz_Builder_Helper::render_mobile_popup_markup();
			}
		}

		/**
		 *  Call Mobile Menu Markup.
		 *
		 * @param string $device Checking where mobile-menu is dropped.
		 */
		public function header_mobile_menu_markup( $device = '' ) {
			SmartToolz_Mobile_Menu_Component::menu_markup( $device );
		}

		/**
		 *  Call Mobile Cart Flyout UI.
		 */
		public function mobile_cart_flyout() {
			// Get the responsive cart click action setting.
			$responsive_cart_action = smarttoolz_get_option( 'responsive-cart-click-action' );
			$desktop_cart_action    = smarttoolz_get_option( 'woo-header-cart-click-action' );

			// Hide cart flyout only if current page is checkout/cart or if redirect option is selected.
			if (
				(
					SmartToolz_Builder_Helper::is_component_loaded( 'woo-cart', 'header' ) &&
					class_exists( 'WooCommerce' ) &&
					! is_cart() &&
					! is_checkout() &&
					( 'redirect' !== $responsive_cart_action || // Prevent flyout markup when 'redirect' option is selected.
					'redirect' !== $desktop_cart_action )
				) || ( SmartToolz_Builder_Helper::is_component_loaded( 'edd-cart', 'header' ) && class_exists( 'Easy_Digital_Downloads' ) )
			) {
				SmartToolz_Builder_UI_Controller::render_mobile_cart_flyout_markup();
			}
		}

		/**
		 * Add Body Classes
		 *
		 * @param array $classes Body Class Array.
		 * @return array
		 */
		public function add_body_class( $classes ) {
			$classes[] = 'ast-hfb-header';

			if ( defined( 'SMARTTOOLZ_EXT_VER' ) && version_compare( SMARTTOOLZ_EXT_VER, '3.2.0', '<' ) ) {
				$classes[] = 'smarttoolz-hfb-header';
			}
			return $classes;
		}

	}

	/**
	 *  Prepare if class 'SmartToolz_Builder_Header' exist.
	 *  Kicking this off by calling 'get_instance()' method
	 */
	SmartToolz_Builder_Header::get_instance();
}
