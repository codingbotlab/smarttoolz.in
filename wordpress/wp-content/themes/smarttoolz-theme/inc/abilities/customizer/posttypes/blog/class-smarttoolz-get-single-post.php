<?php
/**
 * Get Single Post Settings Ability
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.12.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Get_Single_Post
 */
class SmartToolz_Get_Single_Post extends SmartToolz_Abstract_Ability {
	/**
	 * Configure the ability.
	 *
	 * @return void
	 */
	public function configure() {
		$this->id          = 'smarttoolz/get-single-post';
		$this->label       = __( 'Get SmartToolz Single Post Settings', 'smarttoolz' );
		$this->description = __( 'Retrieves the current SmartToolz theme single post settings including container layout, container style, sidebar layout, sidebar style, content width, and related posts settings.', 'smarttoolz' );
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
				'related_posts_enabled'       => array(
					'type'        => 'boolean',
					'description' => __( 'Whether related posts are enabled.', 'smarttoolz' ),
				),
				'related_posts_count'         => array(
					'type'        => 'integer',
					'description' => __( 'Number of related posts to display.', 'smarttoolz' ),
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
			'get single post settings',
			'show single post layout',
			'view post container layout',
			'display single post sidebar',
			'get post content width',
			'show related posts settings',
			'view single post configuration',
			'display post layout options',
			'get post sidebar style',
			'show single post container',
			'view post page settings',
			'display related posts status',
			'get single blog post settings',
			'show post sidebar layout',
			'view single post style',
			'display post container style',
			'get post layout configuration',
			'show single post options',
			'view blog post settings',
			'display post page layout',
			'get single entry settings',
			'show post detail settings',
			'view individual post layout',
			'display single article settings',
			'get post display configuration',
		);
	}

	/**
	 * Execute the ability.
	 *
	 * @param array $args Input arguments.
	 * @return array Result array.
	 */
	public function execute( $args ) {
		$container_layout      = smarttoolz_get_option( 'single-post-ast-content-layout', 'default' );
		$container_style       = smarttoolz_get_option( 'site-content-style', 'unboxed' );
		$sidebar_layout        = smarttoolz_get_option( 'single-post-sidebar-layout', 'default' );
		$sidebar_style         = smarttoolz_get_option( 'single-post-sidebar-style', 'default' );
		$content_width         = smarttoolz_get_option( 'blog-single-width', 'default' );
		$content_max_width     = smarttoolz_get_option( 'blog-single-max-width', 1200 );
		$related_posts_enabled = smarttoolz_get_option( 'enable-related-posts', false );
		$related_posts_count   = smarttoolz_get_option( 'related-posts-total-count', 2 );

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
			__( 'Retrieved single post settings successfully.', 'smarttoolz' ),
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
				'related_posts_enabled'       => (bool) $related_posts_enabled,
				'related_posts_count'         => (int) $related_posts_count,
				'available_container_layouts' => $container_layout_labels,
				'available_container_styles'  => $container_style_labels,
				'available_sidebar_layouts'   => $sidebar_layout_labels,
				'available_sidebar_styles'    => $sidebar_style_labels,
			)
		);
	}
}

SmartToolz_Get_Single_Post::register();
