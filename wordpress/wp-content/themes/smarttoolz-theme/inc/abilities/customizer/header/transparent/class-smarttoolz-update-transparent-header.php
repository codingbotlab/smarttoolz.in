<?php
/**
 * Update Transparent Header Settings Ability
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.13.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Update_Transparent_Header
 */
class SmartToolz_Update_Transparent_Header extends SmartToolz_Abstract_Ability {
	/**
	 * Configure the ability.
	 *
	 * @return void
	 */
	public function configure() {
		$this->id          = 'smarttoolz/update-transparent-header';
		$this->category    = 'smarttoolz';
		$this->label       = __( 'Update SmartToolz Transparent Header Settings', 'smarttoolz' );
		$this->description = __( 'Updates the SmartToolz transparent header settings including enable state, device visibility, logo configuration, disable-on rules for specific page types, header border, and color settings for header backgrounds, site title, menus, submenus, and content links.', 'smarttoolz' );

		$this->meta = array(
			'tool_type' => 'write',
		);
	}

	/**
	 * Get tool type.
	 *
	 * @return string
	 */
	public function get_tool_type() {
		return 'write';
	}

	/**
	 * Get input schema.
	 *
	 * @return array
	 */
	public function get_input_schema() {
		return array(
			'type'       => 'object',
			'properties' => array(
				'enabled'    => array(
					'type'        => 'boolean',
					'description' => 'Enable or disable the transparent header.',
				),
				'on_devices' => array(
					'type'        => 'string',
					'description' => 'Show on which devices. Options: "both", "desktop", "mobile".',
					'enum'        => array( 'both', 'desktop', 'mobile' ),
				),
				'logo'       => array(
					'type'        => 'object',
					'description' => 'Logo configuration for transparent header.',
					'properties'  => array(
						'different_logo'   => array(
							'type'        => 'boolean',
							'description' => 'Use a different logo for transparent header.',
						),
						'logo_url'         => array(
							'type'        => 'string',
							'description' => 'Transparent header logo URL.',
						),
						'retina_logo_url'  => array(
							'type'        => 'string',
							'description' => 'Transparent header retina logo URL.',
						),
						'different_retina' => array(
							'type'        => 'boolean',
							'description' => 'Use a different retina logo for transparent header.',
						),
						'logo_width'       => array(
							'type'        => 'object',
							'description' => 'Logo width (responsive with desktop, tablet, mobile keys in px).',
						),
					),
				),
				'border'     => array(
					'type'        => 'object',
					'description' => 'Header bottom border settings.',
					'properties'  => array(
						'size'  => array(
							'type'        => 'string',
							'description' => 'Border size (e.g. "1" for 1px, "" for none).',
						),
						'color' => array(
							'type'        => 'string',
							'description' => 'Border color (hex value).',
						),
					),
				),
				'disable_on' => array(
					'type'        => 'object',
					'description' => 'Disable transparent header on specific page types. Each property is a boolean (true = disabled on that page type).',
					'properties'  => array(
						'404_page'           => array(
							'type'        => 'boolean',
							'description' => 'Disable on 404 page.',
						),
						'search_page'        => array(
							'type'        => 'boolean',
							'description' => 'Disable on search results.',
						),
						'archive_pages'      => array(
							'type'        => 'boolean',
							'description' => 'Disable on archive pages.',
						),
						'blog_index'         => array(
							'type'        => 'boolean',
							'description' => 'Disable on blog index page.',
						),
						'latest_posts_index' => array(
							'type'        => 'boolean',
							'description' => 'Disable on latest posts index.',
						),
						'pages'              => array(
							'type'        => 'boolean',
							'description' => 'Disable on all pages.',
						),
						'posts'              => array(
							'type'        => 'boolean',
							'description' => 'Disable on all single posts.',
						),
					),
				),
				'colors'     => array(
					'type'        => 'object',
					'description' => 'Color settings for transparent header. All color values are responsive objects with desktop, tablet, mobile keys unless noted.',
					'properties'  => array(
						'logo_color'               => array(
							'type'        => 'string',
							'description' => 'Logo color (hex, non-responsive).',
						),
						'header_bg'                => array(
							'type'        => 'object',
							'description' => 'Header background colors with above, primary, below keys. Each is a responsive color object.',
						),
						'site_title'               => array(
							'type'        => 'object',
							'description' => 'Site title color (responsive).',
						),
						'site_title_hover'         => array(
							'type'        => 'object',
							'description' => 'Site title hover color (responsive).',
						),
						'menu_color'               => array(
							'type'        => 'object',
							'description' => 'Menu link color (responsive).',
						),
						'menu_bg_color'            => array(
							'type'        => 'object',
							'description' => 'Menu background color (responsive).',
						),
						'menu_hover_color'         => array(
							'type'        => 'object',
							'description' => 'Menu link hover color (responsive).',
						),
						'submenu_color'            => array(
							'type'        => 'object',
							'description' => 'Submenu link color (responsive).',
						),
						'submenu_bg_color'         => array(
							'type'        => 'object',
							'description' => 'Submenu background color (responsive).',
						),
						'submenu_hover_color'      => array(
							'type'        => 'object',
							'description' => 'Submenu link hover color (responsive).',
						),
						'content_link_color'       => array(
							'type'        => 'object',
							'description' => 'Content section link color (responsive).',
						),
						'content_link_hover_color' => array(
							'type'        => 'object',
							'description' => 'Content section link hover color (responsive).',
						),
					),
				),
			),
		);
	}

	/**
	 * Get output schema.
	 *
	 * @return array
	 */
	public function get_output_schema() {
		return $this->build_output_schema(
			array(
				'updated' => array(
					'type'        => 'boolean',
					'description' => 'Whether any settings were updated.',
				),
			)
		);
	}

	/**
	 * Get examples.
	 *
	 * @return array
	 */
	public function get_examples() {
		return array(
			'enable transparent header',
			'disable transparent header',
			'set transparent header on desktop only',
			'use different logo for transparent header',
			'change transparent header menu colors',
			'disable transparent header on 404 page',
			'update transparent header background color',
			'set transparent header border',
		);
	}

	/**
	 * Execute the ability.
	 *
	 * @param array $args Input arguments.
	 * @return array Result array.
	 */
	public function execute( $args ) {
		if ( ! defined( 'SMARTTOOLZ_THEME_SETTINGS' ) ) {
			return SmartToolz_Abilities_Response::error(
				__( 'SmartToolz theme is not active.', 'smarttoolz' ),
				__( 'Please activate the SmartToolz theme to use this feature.', 'smarttoolz' )
			);
		}

		$updated         = false;
		$update_messages = array();

		if ( isset( $args['enabled'] ) ) {
			smarttoolz_update_option( 'transparent-header-enable', (bool) $args['enabled'] ? 1 : 0 );
			$updated           = true;
			$update_messages[] = $args['enabled'] ? __( 'Transparent header enabled', 'smarttoolz' ) : __( 'Transparent header disabled', 'smarttoolz' );
		}

		if ( isset( $args['on_devices'] ) ) {
			$on_devices    = sanitize_text_field( $args['on_devices'] );
			$valid_devices = array( 'both', 'desktop', 'mobile' );

			if ( ! in_array( $on_devices, $valid_devices, true ) ) {
				return SmartToolz_Abilities_Response::error(
					/* translators: %s: invalid device value */
					sprintf( __( 'Invalid on_devices: %s.', 'smarttoolz' ), $on_devices ),
					__( 'Valid options: both, desktop, mobile', 'smarttoolz' )
				);
			}

			smarttoolz_update_option( 'transparent-header-on-devices', $on_devices );
			$updated = true;
			/* translators: %s: device visibility value */
			$update_messages[] = sprintf( __( 'Device visibility set to %s', 'smarttoolz' ), $on_devices );
		}

		if ( isset( $args['logo'] ) && is_array( $args['logo'] ) ) {
			$logo = $args['logo'];

			if ( isset( $logo['different_logo'] ) ) {
				smarttoolz_update_option( 'different-transparent-logo', (bool) $logo['different_logo'] ? 1 : 0 );
				$updated = true;
			}

			if ( isset( $logo['logo_url'] ) ) {
				smarttoolz_update_option( 'transparent-header-logo', esc_url_raw( $logo['logo_url'] ) );
				$updated = true;
			}

			if ( isset( $logo['retina_logo_url'] ) ) {
				smarttoolz_update_option( 'transparent-header-retina-logo', esc_url_raw( $logo['retina_logo_url'] ) );
				$updated = true;
			}

			if ( isset( $logo['different_retina'] ) ) {
				smarttoolz_update_option( 'different-transparent-retina-logo', (bool) $logo['different_retina'] ? 1 : 0 );
				$updated = true;
			}

			if ( isset( $logo['logo_width'] ) && is_array( $logo['logo_width'] ) ) {
				$sanitized_width = array();
				$devices         = array( 'desktop', 'tablet', 'mobile' );
				foreach ( $devices as $device ) {
					if ( isset( $logo['logo_width'][ $device ] ) ) {
						$sanitized_width[ $device ] = absint( $logo['logo_width'][ $device ] );
					}
				}
				smarttoolz_update_option( 'transparent-header-logo-width', $sanitized_width );
				$updated = true;
			}

			$update_messages[] = __( 'Logo settings updated', 'smarttoolz' );
		}

		if ( isset( $args['border'] ) && is_array( $args['border'] ) ) {
			if ( isset( $args['border']['size'] ) ) {
				smarttoolz_update_option( 'transparent-header-main-sep', sanitize_text_field( $args['border']['size'] ) );
				$updated = true;
			}

			if ( isset( $args['border']['color'] ) ) {
				smarttoolz_update_option( 'transparent-header-main-sep-color', sanitize_text_field( $args['border']['color'] ) );
				$updated = true;
			}

			$update_messages[] = __( 'Border settings updated', 'smarttoolz' );
		}

		if ( isset( $args['disable_on'] ) && is_array( $args['disable_on'] ) ) {
			$disable_map = array(
				'404_page'           => 'transparent-header-disable-404-page',
				'search_page'        => 'transparent-header-disable-search-page',
				'archive_pages'      => 'transparent-header-disable-archive-pages',
				'blog_index'         => 'transparent-header-disable-index',
				'latest_posts_index' => 'transparent-header-disable-latest-posts-index',
				'pages'              => 'transparent-header-disable-page',
				'posts'              => 'transparent-header-disable-posts',
			);

			foreach ( $disable_map as $key => $option_key ) {
				if ( isset( $args['disable_on'][ $key ] ) ) {
					$value = (bool) $args['disable_on'][ $key ] ? '1' : '0';
					smarttoolz_update_option( $option_key, $value );
					$updated = true;
				}
			}

			$update_messages[] = __( 'Disable-on rules updated', 'smarttoolz' );
		}

		if ( isset( $args['colors'] ) && is_array( $args['colors'] ) ) {
			$this->update_colors( $args['colors'] );
			$updated           = true;
			$update_messages[] = __( 'Colors updated', 'smarttoolz' );
		}

		if ( ! $updated ) {
			return SmartToolz_Abilities_Response::error(
				__( 'No changes specified.', 'smarttoolz' ),
				__( 'Please provide at least one setting to update.', 'smarttoolz' )
			);
		}

		/* translators: %s: comma-separated list of updated settings */
		$message = sprintf( __( 'Transparent header settings updated: %s.', 'smarttoolz' ), implode( ', ', $update_messages ) );

		return SmartToolz_Abilities_Response::success(
			$message,
			array( 'updated' => true )
		);
	}

	/**
	 * Update color settings.
	 *
	 * @param array $colors Color settings array.
	 * @return void
	 */
	private function update_colors( $colors ) {
		if ( isset( $colors['logo_color'] ) ) {
			smarttoolz_update_option( 'transparent-header-logo-color', sanitize_text_field( $colors['logo_color'] ) );
		}

		// Responsive color options mapping.
		$responsive_color_map = array(
			'site_title'               => 'transparent-header-color-site-title-responsive',
			'site_title_hover'         => 'transparent-header-color-h-site-title-responsive',
			'menu_color'               => 'transparent-menu-color-responsive',
			'menu_bg_color'            => 'transparent-menu-bg-color-responsive',
			'menu_hover_color'         => 'transparent-menu-h-color-responsive',
			'submenu_color'            => 'transparent-submenu-color-responsive',
			'submenu_bg_color'         => 'transparent-submenu-bg-color-responsive',
			'submenu_hover_color'      => 'transparent-submenu-h-color-responsive',
			'content_link_color'       => 'transparent-content-section-link-color-responsive',
			'content_link_hover_color' => 'transparent-content-section-link-h-color-responsive',
		);

		foreach ( $responsive_color_map as $key => $option_key ) {
			if ( isset( $colors[ $key ] ) && is_array( $colors[ $key ] ) ) {
				$sanitized = $this->sanitize_responsive_color( $colors[ $key ] );
				smarttoolz_update_option( $option_key, $sanitized );
			}
		}

		// Header background colors (nested: above, primary, below).
		if ( isset( $colors['header_bg'] ) && is_array( $colors['header_bg'] ) ) {
			$bg_map = array(
				'above'   => 'hba-transparent-header-bg-color-responsive',
				'primary' => 'transparent-header-bg-color-responsive',
				'below'   => 'hbb-transparent-header-bg-color-responsive',
			);

			foreach ( $bg_map as $key => $option_key ) {
				if ( isset( $colors['header_bg'][ $key ] ) && is_array( $colors['header_bg'][ $key ] ) ) {
					$sanitized = $this->sanitize_responsive_color( $colors['header_bg'][ $key ] );
					smarttoolz_update_option( $option_key, $sanitized );
				}
			}
		}
	}

	/**
	 * Sanitize a responsive color array.
	 *
	 * @param array $color Responsive color with desktop, tablet, mobile keys.
	 * @return array Sanitized responsive color.
	 */
	private function sanitize_responsive_color( $color ) {
		$sanitized = array();
		$devices   = array( 'desktop', 'tablet', 'mobile' );

		foreach ( $devices as $device ) {
			if ( isset( $color[ $device ] ) ) {
				$sanitized[ $device ] = sanitize_text_field( $color[ $device ] );
			}
		}

		return $sanitized;
	}
}

SmartToolz_Update_Transparent_Header::register();
