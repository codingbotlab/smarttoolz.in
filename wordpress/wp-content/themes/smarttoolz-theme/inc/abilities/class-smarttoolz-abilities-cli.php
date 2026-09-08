<?php
/**
 * SmartToolz Abilities WP-CLI Command
 *
 * Provides WP-CLI commands to manage SmartToolz abilities settings programmatically.
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.13.2
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manages SmartToolz abilities settings via WP-CLI.
 *
 * @since 4.13.2
 */
class SmartToolz_Abilities_CLI {
	/**
	 * Enable SmartToolz abilities.
	 *
	 * Enables abilities and edit abilities by default. Use --readonly to keep
	 * abilities in read-only mode, or --with-mcp to also bring up the MCP Server.
	 *
	 * ## OPTIONS
	 *
	 * [--readonly]
	 * : Enable abilities in read-only mode (skip enabling edit abilities).
	 *
	 * [--with-mcp]
	 * : Also enable the SmartToolz MCP Server endpoint.
	 *
	 * ## EXAMPLES
	 *
	 *     wp smarttoolz abilities enable
	 *     wp smarttoolz abilities enable --readonly
	 *     wp smarttoolz abilities enable --with-mcp
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Associative arguments.
	 * @return void
	 * @since 4.13.2
	 */
	public function enable( $args, $assoc_args ) {
		SmartToolz_API_Init::update_admin_settings_option( 'enable_abilities', true );
		WP_CLI::success( 'SmartToolz abilities enabled.' );

		if ( ! \WP_CLI\Utils\get_flag_value( $assoc_args, 'readonly', false ) ) {
			SmartToolz_API_Init::update_admin_settings_option( 'enable_edit_abilities', true );
			WP_CLI::success( 'SmartToolz edit abilities enabled.' );
		}

		if ( \WP_CLI\Utils\get_flag_value( $assoc_args, 'with-mcp', false ) ) {
			SmartToolz_API_Init::update_admin_settings_option( 'enable_mcp_server', true );
			WP_CLI::success( 'SmartToolz MCP Server enabled.' );
		}
	}

	/**
	 * Disable SmartToolz abilities.
	 *
	 * Disables all abilities settings by default. Use --edit or --mcp to
	 * target a specific setting without touching the others.
	 *
	 * ## OPTIONS
	 *
	 * [--edit]
	 * : Only disable edit abilities, keeping read abilities active.
	 *
	 * [--mcp]
	 * : Only disable the SmartToolz MCP Server endpoint.
	 *
	 * ## EXAMPLES
	 *
	 *     wp smarttoolz abilities disable
	 *     wp smarttoolz abilities disable --edit
	 *     wp smarttoolz abilities disable --mcp
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Associative arguments.
	 * @return void
	 * @since 4.13.2
	 */
	public function disable( $args, $assoc_args ) {
		$only_edit = \WP_CLI\Utils\get_flag_value( $assoc_args, 'edit', false );
		$only_mcp  = \WP_CLI\Utils\get_flag_value( $assoc_args, 'mcp', false );

		if ( $only_edit ) {
			SmartToolz_API_Init::update_admin_settings_option( 'enable_edit_abilities', false );
			WP_CLI::success( 'SmartToolz edit abilities disabled.' );
			return;
		}

		if ( $only_mcp ) {
			SmartToolz_API_Init::update_admin_settings_option( 'enable_mcp_server', false );
			WP_CLI::success( 'SmartToolz MCP Server disabled.' );
			return;
		}

		SmartToolz_API_Init::update_admin_settings_option( 'enable_abilities', false );
		SmartToolz_API_Init::update_admin_settings_option( 'enable_edit_abilities', false );
		SmartToolz_API_Init::update_admin_settings_option( 'enable_mcp_server', false );
		WP_CLI::success( 'SmartToolz abilities disabled.' );
	}

	/**
	 * Show current abilities settings and whether each is functional.
	 *
	 * ## EXAMPLES
	 *
	 *     wp smarttoolz abilities status
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Associative arguments.
	 * @return void
	 * @since 4.13.2
	 */
	public function status( $args, $assoc_args ) {
		global $wp_version;

		$abilities_enabled  = SmartToolz_API_Init::get_admin_settings_option( 'enable_abilities', false );
		$edit_enabled       = SmartToolz_API_Init::get_admin_settings_option( 'enable_edit_abilities', false );
		$mcp_enabled        = SmartToolz_API_Init::get_admin_settings_option( 'enable_mcp_server', false );
		$api_available      = function_exists( 'wp_register_ability' );
		$mcp_adapter_active = class_exists( 'WP\MCP\Plugin' );

		$abilities_functional = $abilities_enabled && $api_available;
		$edit_functional      = $abilities_functional && $edit_enabled;
		$mcp_functional       = $mcp_enabled && $mcp_adapter_active;

		$wp_note = $api_available
			? sprintf( 'WP %s', $wp_version )
			: sprintf( 'WP %s — needs 6.9+ or polyfill', $wp_version );

		$mcp_functional_label = $mcp_functional ? 'yes' : 'no' . ( $mcp_enabled && ! $mcp_adapter_active ? ' (MCP Adapter not active)' : '' );

		$rows = array(
			array(
				'Setting'    => 'Enable Abilities',
				'Status'     => $abilities_enabled ? 'enabled' : 'disabled',
				'Functional' => $abilities_functional ? 'yes (' . $wp_note . ')' : 'no (' . $wp_note . ')',
			),
			array(
				'Setting'    => 'Enable Edit Abilities',
				'Status'     => $edit_enabled ? 'enabled' : 'disabled',
				'Functional' => $edit_functional ? 'yes' : 'no',
			),
			array(
				'Setting'    => 'Enable MCP Server',
				'Status'     => $mcp_enabled ? 'enabled' : 'disabled',
				'Functional' => $mcp_functional_label,
			),
		);

		\WP_CLI\Utils\format_items( 'table', $rows, array( 'Setting', 'Status', 'Functional' ) );
	}

	/**
	 * List all registered SmartToolz abilities.
	 *
	 * ## EXAMPLES
	 *
	 *     wp smarttoolz abilities list
	 *
	 * @param array $args       Positional arguments.
	 * @param array $assoc_args Associative arguments.
	 * @return void
	 * @since 4.13.2
	 */
	public function list( $args, $assoc_args ) {
		if ( ! function_exists( 'wp_get_abilities' ) ) {
			WP_CLI::error( 'WordPress Abilities API is not available. Requires WP 6.9+ or the abilities polyfill plugin.' );
			return;
		}

		$abilities = wp_get_abilities();
		$rows      = array();

		foreach ( $abilities as $ability ) {
			if ( 0 !== strpos( $ability->get_name(), 'smarttoolz/' ) ) {
				continue;
			}

			$rows[] = array(
				'Name'  => $ability->get_name(),
				'Label' => $ability->get_label(),
				'Type'  => $ability->get_meta_item( 'tool_type', 'read' ),
			);
		}

		if ( empty( $rows ) ) {
			WP_CLI::warning( 'No SmartToolz abilities registered. Make sure "Enable Abilities" is turned on.' );
			return;
		}

		\WP_CLI\Utils\format_items( 'table', $rows, array( 'Name', 'Label', 'Type' ) );
	}
}
