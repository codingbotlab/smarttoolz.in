<?php
/**
 * SmartToolz admin menu.
 *
 * Lightweight replacement for the upstream admin menu so the renamed theme
 * never depends on removed upstream functions/classes during admin bootstrap.
 *
 * @package SmartToolz
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SmartToolz_Menu' ) ) {
	class SmartToolz_Menu {
		private static $instance = null;
		public static $page_title = 'SmartToolz';
		public static $plugin_slug = 'smarttoolz';

		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		public function __construct() {
			add_action( 'admin_menu', array( $this, 'setup_menu' ) );
		}

		public function setup_menu() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			add_menu_page(
				__( 'SmartToolz', 'smarttoolz' ),
				__( 'SmartToolz', 'smarttoolz' ),
				'manage_options',
				self::$plugin_slug,
				array( $this, 'render_dashboard' ),
				'dashicons-admin-generic',
				59
			);

			add_submenu_page(
				self::$plugin_slug,
				__( 'Customize', 'smarttoolz' ),
				__( 'Customize', 'smarttoolz' ),
				'manage_options',
				'customize.php'
			);
		}

		public function render_dashboard() {
			echo '<div class="wrap"><h1>' . esc_html__( 'SmartToolz Theme', 'smarttoolz' ) . '</h1>';
			echo '<p>' . esc_html__( 'Customize your SmartToolz website from Appearance → Customize.', 'smarttoolz' ) . '</p></div>';
		}
	}

	SmartToolz_Menu::get_instance();
}
