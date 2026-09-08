<?php
/**
 * Get Single Page Settings Ability
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.12.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Get_Single_Page
 */
class SmartToolz_Get_Single_Page extends SmartToolz_Abstract_Ability {
	/**
	 * Configure the ability.
	 *
	 * @return void
	 */
	public function configure() {
		$this->id          = 'smarttoolz/get-single-page';
		$this->label       = __( 'Get SmartToolz Single Page Settings', 'smarttoolz' );
		$this->description = __( 'Retrieves the current SmartToolz theme single page settings including container layout, container style, sidebar layout, sidebar style, and content width.', 'smarttoolz' );
		$this->category    = 'smarttoolz';
	}

	/**
	 * Get input schema.
	 *
	 * @return array
	 */
	public function get_input_schema() {
		return array();
	}

	/**
	 * Get output schema.
	 *
	 * @return array
	 */
	public function get_output_schema() {
		return $this->build_output_schema(
			array(
				'container_layout'            => array(
					'type'        => 'string',
					'description' => __( 'Current container layout key.', 'smarttoolz' ),
				),
				'container_layout_label'      => array(
					'type'        => 'string',
					'description' => __( 'Human-readable container layout name.', 'smarttoolz' ),
				),
				'container_style'             => array(
					'type'        => 'string',
					'description' => __( 'Current container style key.', 'smarttoolz' ),
				),
				'container_style_label'       => array(
					'type'        => 'string',
					'description' => __( 'Human-readable container style name.', 'smarttoolz' ),
				),
				'sidebar_layout'              => array(
					'type'        => 'string',
					'description' => __( 'Current sidebar layout key.', 'smarttoolz' ),
				),
				'sidebar_layout_label'        => array(
					'type'        => 'string',
					'description' => __( 'Human-readable sidebar layout name.', 'smarttoolz' ),
				),
				'sidebar_style'               => array(
					'type'        => 'string',
					'description' => __( 'Current sidebar style key.', 'smarttoolz' ),
				),
				'sidebar_style_label'         => array(
					'type'        => 'string',
					'description' => __( 'Human-readable sidebar style name.', 'smarttoolz' ),
				),
				'content_width'               => array(
					'type'        => 'string',
					'description' => __( 'Content width setting key.', 'smarttoolz' ),
				),
				'content_width_label'         => array(
					'type'        => 'string',
					'description' => __( 'Human-readable content width name.', 'smarttoolz' ),
				),
				'content_max_width'           => array(
					'type'        => 'integer',
					'description' => __( 'Custom content max width in pixels.', 'smarttoolz' ),
				),
				'available_container_layouts' => array(
					'type'        => 'object',
					'description' => __( 'Available container layout options.', 'smarttoolz' ),
				),
				'available_container_styles'  => array(
					'type'        => 'object',
					'description' => __( 'Available container style options.', 'smarttoolz' ),
				),
				'available_sidebar_layouts'   => array(
					'type'        => 'object',
					'description' => __( 'Available sidebar layout options.', 'smarttoolz' ),
				),
				'available_sidebar_styles'    => array(
					'type'        => 'object',
					'description' => __( 'Available sidebar style options.', 'smarttoolz' ),
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
			'get single page settings',
			'show single page layout',
			'view page container layout',
			'display single page sidebar',
			'get page content width',
			'show single page container',
			'view page layout options',
			'display page sidebar style',
			'get page container style',
			'show page sidebar layout',
			'view single page style',
			'display page container',
			'get page layout configuration',
			'show single page options',
			'view page settings',
			'display page layout',
			'get single page configuration',
			'show page detail settings',
			'view individual page layout',
			'display page display configuration',
		);
	}

	/**
	 * Execute the ability.
	 *
	 * @param array $args Input arguments.
	 * @return array Result array.
	 */
	public function execute( $args ) {
		$container_layout  = smarttoolz_get_option( 'single-page-ast-content-layout', 'default' );
		$container_style   = smarttoolz_get_option( 'site-content-style', 'unboxed' );
		$sidebar_layout    = smarttoolz_get_option( 'single-page-sidebar-layout', 'default' );
		$sidebar_style     = smarttoolz_get_option( 'single-page-sidebar-style', 'default' );
		$content_width     = smarttoolz_get_option( 'single-page-width', 'default' );
		$content_max_width = smarttoolz_get_option( 'single-page-max-width', 1200 );

		$container_layout_labels = array(
			'default'                => 'Default',
			'normal-width-container' => 'Normal',
			'narrow-width-container' => 'Narrow',
			'full-width-container'   => 'Full Width',
		);

		$container_style_labels = array(
			'boxed'   => 'Boxed',
			'unboxed' => 'Unboxed',
		);

		$sidebar_layout_labels = array(
			'default'       => 'Default',
			'no-sidebar'    => 'No Sidebar',
			'left-sidebar'  => 'Left Sidebar',
			'right-sidebar' => 'Right Sidebar',
		);

		$sidebar_style_labels = array(
			'default' => 'Default',
			'unboxed' => 'Unboxed',
			'boxed'   => 'Boxed',
		);

		$content_width_labels = array(
			'default' => 'Default',
			'custom'  => 'Custom',
		);

		return SmartToolz_Abilities_Response::success(
			__( 'Retrieved single page settings successfully.', 'smarttoolz' ),
			array(
				'container_layout'            => $container_layout,
				'container_layout_label'      => isset( $container_layout_labels[ $container_layout ] ) ? $container_layout_labels[ $container_layout ] : $container_layout,
				'container_style'             => $container_style,
				'container_style_label'       => isset( $container_style_labels[ $container_style ] ) ? $container_style_labels[ $container_style ] : $container_style,
				'sidebar_layout'              => $sidebar_layout,
				'sidebar_layout_label'        => isset( $sidebar_layout_labels[ $sidebar_layout ] ) ? $sidebar_layout_labels[ $sidebar_layout ] : $sidebar_layout,
				'sidebar_style'               => $sidebar_style,
				'sidebar_style_label'         => isset( $sidebar_style_labels[ $sidebar_style ] ) ? $sidebar_style_labels[ $sidebar_style ] : $sidebar_style,
				'content_width'               => $content_width,
				'content_width_label'         => isset( $content_width_labels[ $content_width ] ) ? $content_width_labels[ $content_width ] : $content_width,
				'content_max_width'           => (int) $content_max_width,
				'available_container_layouts' => $container_layout_labels,
				'available_container_styles'  => $container_style_labels,
				'available_sidebar_layouts'   => $sidebar_layout_labels,
				'available_sidebar_styles'    => $sidebar_style_labels,
			)
		);
	}
}

SmartToolz_Get_Single_Page::register();
