<?php
/**
 * Abilities Init
 *
 * Main initialization class for SmartToolz Abilities API integration.
 * Registers the 'smarttoolz' category and loads all ability classes.
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.12.6
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Abilities_Init
 */
class SmartToolz_Abilities_Init {
	/**
	 * Instance of this class.
	 *
	 * @var self|null
	 */
	private static $instance = null;

	/**
	 * Whether abilities have been registered.
	 *
	 * @var bool
	 */
	private $registered = false;

	/**
	 * Whether categories have been registered.
	 *
	 * @var bool
	 */
	private $categories_registered = false;

	/**
	 * Get singleton instance.
	 *
	 * @return self
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Support both pre-6.9 and 6.9+ (core) action names.
		add_action( 'abilities_api_categories_init', array( $this, 'register_categories' ) );
		add_action( 'wp_abilities_api_categories_init', array( $this, 'register_categories' ) );

		add_action( 'abilities_api_init', array( $this, 'register_abilities' ) );
		add_action( 'wp_abilities_api_init', array( $this, 'register_abilities' ) );

		// Register dedicated SmartToolz MCP server when enabled and the MCP Adapter is active.
		if ( SmartToolz_API_Init::get_admin_settings_option( 'enable_mcp_server', false ) && function_exists( 'wp_register_ability' ) && class_exists( 'WP\MCP\Plugin' ) ) {
			add_action( 'mcp_adapter_init', array( $this, 'register_mcp_server' ) );
		}
	}

	/**
	 * Register ability categories.
	 *
	 * @return void
	 */
	public function register_categories() {
		if ( $this->categories_registered ) {
			return;
		}

		if ( ! function_exists( 'wp_register_ability_category' ) ) {
			return;
		}

		wp_register_ability_category(
			'smarttoolz',
			array(
				'label'       => __( 'SmartToolz Theme', 'smarttoolz' ),
				'description' => __( 'SmartToolz theme customization abilities for typography, colors, layout, and design settings.', 'smarttoolz' ),
			)
		);

		$this->categories_registered = true;
	}

	/**
	 * Register all SmartToolz abilities.
	 *
	 * @return void
	 */
	public function register_abilities() {
		if ( $this->registered ) {
			return;
		}

		if ( ! function_exists( 'wp_register_ability' ) ) {
			return;
		}

		$abilities_dir = SMARTTOOLZ_THEME_DIR . 'inc/abilities/';

		$ability_files = array(
			// Performance abilities.
			'admin/settings/performance/class-smarttoolz-get-performance',
			'admin/settings/performance/class-smarttoolz-update-performance',
			'admin/settings/performance/class-smarttoolz-flush-local-fonts',
			'admin/settings/performance/class-smarttoolz-get-load-google-fonts-locally',
			'admin/settings/performance/class-smarttoolz-get-preload-local-fonts',
			'admin/settings/performance/class-smarttoolz-update-load-google-fonts-locally',
			'admin/settings/performance/class-smarttoolz-update-preload-local-fonts',

			// Typography abilities.
			'customizer/globals/typography/class-smarttoolz-get-body-font',
			'customizer/globals/typography/class-smarttoolz-update-body-font',
			'customizer/globals/typography/class-smarttoolz-get-headings-font',
			'customizer/globals/typography/class-smarttoolz-update-headings-font',
			'customizer/globals/typography/class-smarttoolz-list-font-families',
			// Individual heading typography abilities (H1-H6) — registered via loop.
			'customizer/globals/typography/class-smarttoolz-get-heading-font',
			'customizer/globals/typography/class-smarttoolz-update-heading-font',
			// Paragraph margin and underline links abilities.
			'customizer/globals/typography/class-smarttoolz-get-paragraph-margin',
			'customizer/globals/typography/class-smarttoolz-update-paragraph-margin',
			'customizer/globals/typography/class-smarttoolz-get-underline-links-status',
			'customizer/globals/typography/class-smarttoolz-toggle-underline-links',

			// Colors abilities.
			'customizer/globals/colors/class-smarttoolz-get-global-palette',
			'customizer/globals/colors/class-smarttoolz-update-global-palette',
			'customizer/globals/colors/class-smarttoolz-get-background-colors',
			'customizer/globals/colors/class-smarttoolz-update-background-colors',
			'customizer/globals/colors/class-smarttoolz-update-theme-colors',

			// Container abilities.
			'customizer/globals/container/class-smarttoolz-get-container-layout',
			'customizer/globals/container/class-smarttoolz-update-container-layout',
			'customizer/globals/container/class-smarttoolz-list-container-settings',

			// Buttons abilities.
			'customizer/globals/buttons/class-smarttoolz-get-global-buttons',
			'customizer/globals/buttons/class-smarttoolz-update-global-buttons',

			// Header Builder abilities.
			'customizer/header/class-smarttoolz-get-header-builder',
			'customizer/header/class-smarttoolz-get-header-builder-design',
			'customizer/header/class-smarttoolz-update-header-builder',
			'customizer/header/class-smarttoolz-update-header-builder-design',
			'customizer/header/class-smarttoolz-migrate-header-components',
			'customizer/header/builder/class-smarttoolz-list-header-builder-settings',

			// Transparent Header abilities.
			'customizer/header/transparent/class-smarttoolz-get-transparent-header',
			'customizer/header/transparent/class-smarttoolz-update-transparent-header',

			// Footer Builder abilities.
			'customizer/footer/class-smarttoolz-get-footer-builder',
			'customizer/footer/class-smarttoolz-get-footer-builder-design',
			'customizer/footer/class-smarttoolz-update-footer-builder',
			'customizer/footer/class-smarttoolz-update-footer-builder-design',

			// Blogs / Post Types abilities.
			'customizer/posttypes/blog/class-smarttoolz-get-blog-archive',
			'customizer/posttypes/blog/class-smarttoolz-update-blog-archive',
			'customizer/posttypes/blog/class-smarttoolz-get-single-post',
			'customizer/posttypes/blog/class-smarttoolz-update-single-post',
			'customizer/posttypes/blog/class-smarttoolz-get-single-page',
			'customizer/posttypes/blog/class-smarttoolz-update-single-page',

			// Site Identity abilities.
			'customizer/siteidentity/class-smarttoolz-get-site-title-logo',
			'customizer/siteidentity/class-smarttoolz-update-site-title-logo',

			// Breadcrumb abilities.
			'customizer/general/breadcrumb/class-smarttoolz-get-breadcrumb',
			'customizer/general/breadcrumb/class-smarttoolz-update-breadcrumb',

			// Post Meta abilities.
			'admin/postmeta/class-smarttoolz-get-postmeta',
			'admin/postmeta/class-smarttoolz-update-postmeta',

			// Sidebar abilities.
			'customizer/general/sidebar/class-smarttoolz-get-sidebar',
			'customizer/general/sidebar/class-smarttoolz-get-sidebar-layout',
			'customizer/general/sidebar/class-smarttoolz-get-sidebar-style',
			'customizer/general/sidebar/class-smarttoolz-get-sidebar-width',
			'customizer/general/sidebar/class-smarttoolz-get-sticky-sidebar',
			'customizer/general/sidebar/class-smarttoolz-update-sidebar',
			'customizer/general/sidebar/class-smarttoolz-update-sidebar-layout',
			'customizer/general/sidebar/class-smarttoolz-update-sidebar-style',
			'customizer/general/sidebar/class-smarttoolz-update-sidebar-width',
			'customizer/general/sidebar/class-smarttoolz-update-sticky-sidebar',

			// Scroll to Top abilities.
			'customizer/general/scroll-to-top/class-smarttoolz-get-scroll-to-top',
			'customizer/general/scroll-to-top/class-smarttoolz-update-scroll-to-top',
		);

		foreach ( $ability_files as $file ) {
			require_once $abilities_dir . $file . '.php';
		}

		$this->registered = true;
	}

	/**
	 * Register a dedicated SmartToolz MCP server.
	 *
	 * Creates an MCP server endpoint at /wp-json/smarttoolz/v1/mcp
	 * that only includes smarttoolz/ prefixed abilities.
	 *
	 * @param object $adapter The MCP adapter instance.
	 * @return void
	 * @since 4.13.0
	 */
	public function register_mcp_server( $adapter ) {
		$abilities = wp_get_abilities();
		$tools     = array();

		foreach ( $abilities as $ability ) {
			if ( 0 === strpos( $ability->get_name(), 'smarttoolz/' ) ) {
				$tools[] = $ability->get_name();
			}
		}

		$transport_class = class_exists( '\WP\MCP\Transport\HttpTransport' )
			? \WP\MCP\Transport\HttpTransport::class
			: \WP\MCP\Transport\Http\RestTransport::class;

		$adapter->create_server(
			'smarttoolz',
			'smarttoolz/v1',
			'mcp',
			__( 'SmartToolz MCP Server', 'smarttoolz' ),
			__( 'SmartToolz MCP Server for theme customization and design settings.', 'smarttoolz' ),
			SMARTTOOLZ_THEME_VERSION,
			array( $transport_class ),
			\WP\MCP\Infrastructure\ErrorHandling\ErrorLogMcpErrorHandler::class,
			\WP\MCP\Infrastructure\Observability\NullMcpObservabilityHandler::class,
			$tools,
			array(),
			array()
		);
	}
}
