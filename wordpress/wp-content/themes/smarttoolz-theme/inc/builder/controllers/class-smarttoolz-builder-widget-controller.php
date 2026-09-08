<?php
/**
 * SmartToolz Builder Widget Controller.
 *
 * @package smarttoolz-builder
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SmartToolz_Builder_Widget_Controller' ) ) {

	/**
	 * Class SmartToolz_Builder_Widget_Controller.
	 */
	final class SmartToolz_Builder_Widget_Controller {
		/**
		 * Member Variable
		 *
		 * @var mixed instance
		 */
		private static $instance = null;

		/**
		 *  Initiator
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

			add_action( 'widgets_init', array( $this, 'widget_init' ) );
			add_filter( 'customize_section_active', array( $this, 'display_sidebar' ), 99, 2 );
		}

		/**
		 * Display sidebar as section.
		 *
		 * @param bool   $active ios active.
		 * @param object $section section.
		 * @return bool
		 */
		public function display_sidebar( $active, $section ) {

			if ( false === SmartToolz_Builder_Helper::$is_header_footer_builder_active ) {
				return $active;
			}

			if ( strpos( $section->id, 'widgets-footer-widget-' ) || strpos( $section->id, 'widgets-header-widget-' ) ) {
				$active = true;
			}

			return $active;
		}

		/**
		 * Initiate SmartToolz Widgets.
		 */
		public function widget_init() {

			if ( false === SmartToolz_Builder_Helper::$is_header_footer_builder_active ) {
				return;
			}

			// Register Footer Widgets.
			$component_limit = defined( 'SMARTTOOLZ_EXT_VER' ) ? SmartToolz_Builder_Helper::$component_limit : SmartToolz_Builder_Helper::$num_of_footer_widgets;
			for ( $index = 1; $index <= $component_limit; $index++ ) {

				if ( ! is_customize_preview() && ! SmartToolz_Builder_Helper::is_component_loaded( 'widget-' . $index, 'footer' ) ) {
					continue;
				}

				$this->register_sidebar( $index, 'footer' );
			}

			$component_limit = defined( 'SMARTTOOLZ_EXT_VER' ) ? SmartToolz_Builder_Helper::$component_limit : SmartToolz_Builder_Helper::$num_of_header_widgets;
			for ( $index = 1; $index <= $component_limit; $index++ ) {

				if ( ! is_customize_preview() && ! SmartToolz_Builder_Helper::is_component_loaded( 'widget-' . $index, 'header' ) ) {
					continue;
				}

				$this->register_sidebar( $index, 'header' );
			}
		}

		/**
		 * Register widget for the builder.
		 *
		 * @param int    $index index of widget.
		 * @param string $builder_type builder type.
		 */
		public function register_sidebar( $index, $builder_type = 'header' ) {
			register_sidebar(
				apply_filters(
					'smarttoolz_' . $builder_type . '_widget_' . $index . 'args',
					array(
						'name'          => ucfirst( $builder_type ) . ' Builder Widget ' . $index,
						'id'            => $builder_type . '-widget-' . $index,
						'description'   => esc_html__( 'Add widgets here:', 'smarttoolz' ),
						'before_widget' => '<section id="%1$s" class="widget %2$s">',
						'after_widget'  => '</section>',
						'before_title'  => '<h2 class="widget-title">',
						'after_title'   => '</h2>',
					)
				)
			);
		}
	}

	/**
	 *  Prepare if class 'SmartToolz_Builder_Widget_Controller' exist.
	 *  Kicking this off by calling 'get_instance()' method
	 */
	SmartToolz_Builder_Widget_Controller::get_instance();
}
