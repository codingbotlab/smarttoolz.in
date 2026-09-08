<?php
/**
 * Update Single Page Settings Ability
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.12.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Update_Single_Page
 */
class SmartToolz_Update_Single_Page extends SmartToolz_Abstract_Ability {
	/**
	 * Configure the ability.
	 *
	 * @return void
	 */
	public function configure() {
		$this->id          = 'smarttoolz/update-single-page';
		$this->category    = 'smarttoolz';
		$this->label       = __( 'Update SmartToolz Single Page Settings', 'smarttoolz' );
		$this->description = __( 'Updates the SmartToolz theme single page settings including container layout, container style, sidebar layout, sidebar style, and content width.', 'smarttoolz' );

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
				'container_layout'  => array(
					'type'        => 'string',
					'description' => __( 'Container layout for single pages. Options: "default" (Default), "normal-width-container" (Normal), "narrow-width-container" (Narrow), "full-width-container" (Full Width).', 'smarttoolz' ),
					'enum'        => array( 'default', 'normal-width-container', 'narrow-width-container', 'full-width-container' ),
				),
				'container_style'   => array(
					'type'        => 'string',
					'description' => __( 'Container style. Options: "boxed" (Boxed), "unboxed" (Unboxed).', 'smarttoolz' ),
					'enum'        => array( 'boxed', 'unboxed' ),
				),
				'sidebar_layout'    => array(
					'type'        => 'string',
					'description' => __( 'Sidebar layout for single pages. Options: "default" (Default), "no-sidebar" (No Sidebar), "left-sidebar" (Left Sidebar), "right-sidebar" (Right Sidebar).', 'smarttoolz' ),
					'enum'        => array( 'default', 'no-sidebar', 'left-sidebar', 'right-sidebar' ),
				),
				'sidebar_style'     => array(
					'type'        => 'string',
					'description' => __( 'Sidebar style for single pages. Options: "default" (Default), "unboxed" (Unboxed), "boxed" (Boxed).', 'smarttoolz' ),
					'enum'        => array( 'default', 'unboxed', 'boxed' ),
				),
				'content_width'     => array(
					'type'        => 'string',
					'description' => __( 'Content width setting. Options: "default" (Default), "custom" (Custom).', 'smarttoolz' ),
					'enum'        => array( 'default', 'custom' ),
				),
				'content_max_width' => array(
					'type'        => 'integer',
					'description' => __( 'Custom content max width in pixels (0-1920). Only applies when content_width is set to "custom".', 'smarttoolz' ),
				),
			),
		);
	}

	/**
	 * Get examples.
	 *
	 * @return array
	 */
	public function get_examples() {
		return array(
			'set single page container to full width',
			'change page sidebar to left',
			'set page container to narrow',
			'update page sidebar style to boxed',
			'change page layout to normal width',
			'update single page to no sidebar',
			'set page container style to unboxed',
			'change page content width to custom',
			'set single page to right sidebar',
			'update page container to boxed',
			'change page layout to default',
			'set custom content width to 900px',
			'update sidebar to default style',
			'set page to full width no sidebar',
			'change single page container layout',
			'update page sidebar configuration',
			'set narrow width with left sidebar',
			'change page content max width',
			'update single page layout settings',
		);
	}

	/**
	 * Execute the ability.
	 *
	 * @param array $args Input arguments.
	 * @return array Result array.
	 */
	public function execute( $args ) {
		$updated         = false;
		$update_messages = array();

		if ( isset( $args['container_layout'] ) && ! empty( $args['container_layout'] ) ) {
			$container_layout = sanitize_text_field( $args['container_layout'] );
			$valid_layouts    = array( 'default', 'normal-width-container', 'narrow-width-container', 'full-width-container' );
			if ( ! in_array( $container_layout, $valid_layouts, true ) ) {
				return SmartToolz_Abilities_Response::error(
					/* translators: %s: container layout value */
					sprintf( __( 'Invalid container_layout: %s.', 'smarttoolz' ), $container_layout ),
					__( 'Valid options: default, normal-width-container, narrow-width-container, full-width-container', 'smarttoolz' )
				);
			}

			$layout_labels = array(
				'default'                => 'Default',
				'normal-width-container' => 'Normal',
				'narrow-width-container' => 'Narrow',
				'full-width-container'   => 'Full Width',
			);

			smarttoolz_update_option( 'single-page-ast-content-layout', $container_layout );
			$updated           = true;
			$update_messages[] = sprintf( 'Container layout set to %s', $layout_labels[ $container_layout ] );
		}

		if ( isset( $args['container_style'] ) && ! empty( $args['container_style'] ) ) {
			$container_style = sanitize_text_field( $args['container_style'] );
			$valid_styles    = array( 'boxed', 'unboxed' );
			if ( ! in_array( $container_style, $valid_styles, true ) ) {
				return SmartToolz_Abilities_Response::error(
					/* translators: %s: container style value */
					sprintf( __( 'Invalid container_style: %s.', 'smarttoolz' ), $container_style ),
					__( 'Valid options: boxed, unboxed', 'smarttoolz' )
				);
			}

			$style_labels = array(
				'boxed'   => 'Boxed',
				'unboxed' => 'Unboxed',
			);

			smarttoolz_update_option( 'site-content-style', $container_style );
			$updated           = true;
			$update_messages[] = sprintf( 'Container style set to %s', $style_labels[ $container_style ] );
		}

		if ( isset( $args['sidebar_layout'] ) && ! empty( $args['sidebar_layout'] ) ) {
			$sidebar_layout = sanitize_text_field( $args['sidebar_layout'] );
			$valid_sidebars = array( 'default', 'no-sidebar', 'left-sidebar', 'right-sidebar' );
			if ( ! in_array( $sidebar_layout, $valid_sidebars, true ) ) {
				return SmartToolz_Abilities_Response::error(
					/* translators: %s: sidebar layout value */
					sprintf( __( 'Invalid sidebar_layout: %s.', 'smarttoolz' ), $sidebar_layout ),
					__( 'Valid options: default, no-sidebar, left-sidebar, right-sidebar', 'smarttoolz' )
				);
			}

			$sidebar_labels = array(
				'default'       => 'Default',
				'no-sidebar'    => 'No Sidebar',
				'left-sidebar'  => 'Left Sidebar',
				'right-sidebar' => 'Right Sidebar',
			);

			smarttoolz_update_option( 'single-page-sidebar-layout', $sidebar_layout );
			$updated           = true;
			$update_messages[] = sprintf( 'Sidebar layout set to %s', $sidebar_labels[ $sidebar_layout ] );
		}

		if ( isset( $args['sidebar_style'] ) && ! empty( $args['sidebar_style'] ) ) {
			$sidebar_style = sanitize_text_field( $args['sidebar_style'] );
			$valid_styles  = array( 'default', 'unboxed', 'boxed' );
			if ( ! in_array( $sidebar_style, $valid_styles, true ) ) {
				return SmartToolz_Abilities_Response::error(
					/* translators: %s: sidebar style value */
					sprintf( __( 'Invalid sidebar_style: %s.', 'smarttoolz' ), $sidebar_style ),
					__( 'Valid options: default, unboxed, boxed', 'smarttoolz' )
				);
			}

			$style_labels = array(
				'default' => 'Default',
				'unboxed' => 'Unboxed',
				'boxed'   => 'Boxed',
			);

			smarttoolz_update_option( 'single-page-sidebar-style', $sidebar_style );
			$updated           = true;
			$update_messages[] = sprintf( 'Sidebar style set to %s', $style_labels[ $sidebar_style ] );
		}

		if ( isset( $args['content_width'] ) && ! empty( $args['content_width'] ) ) {
			$content_width = sanitize_text_field( $args['content_width'] );
			$valid_widths  = array( 'default', 'custom' );
			if ( ! in_array( $content_width, $valid_widths, true ) ) {
				return SmartToolz_Abilities_Response::error(
					/* translators: %s: content width value */
					sprintf( __( 'Invalid content_width: %s.', 'smarttoolz' ), $content_width ),
					__( 'Valid options: default, custom', 'smarttoolz' )
				);
			}

			smarttoolz_update_option( 'single-page-width', $content_width );
			$updated           = true;
			$update_messages[] = sprintf( 'Content width set to %s', ucfirst( $content_width ) );
		}

		if ( isset( $args['content_max_width'] ) ) {
			$content_max_width = absint( $args['content_max_width'] );
			if ( $content_max_width > 1920 ) {
				return SmartToolz_Abilities_Response::error(
					/* translators: %d: content max width value */
					sprintf( __( 'Invalid content_max_width: %d.', 'smarttoolz' ), $content_max_width ),
					__( 'Value must be between 0 and 1920 pixels.', 'smarttoolz' )
				);
			}

			smarttoolz_update_option( 'single-page-max-width', $content_max_width );
			$updated           = true;
			$update_messages[] = sprintf( 'Content max width set to %dpx', $content_max_width );
		}

		if ( ! $updated ) {
			return SmartToolz_Abilities_Response::error(
				__( 'No changes specified.', 'smarttoolz' ),
				__( 'Please provide at least one setting to update.', 'smarttoolz' )
			);
		}

		$message = implode( ', ', $update_messages ) . '.';

		return SmartToolz_Abilities_Response::success(
			$message,
			array(
				'updated' => true,
			)
		);
	}
}

SmartToolz_Update_Single_Page::register();
