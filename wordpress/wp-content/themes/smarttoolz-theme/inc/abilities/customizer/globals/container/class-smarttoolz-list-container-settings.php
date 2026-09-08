<?php
/**
 * List Container Settings Ability
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.12.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_List_Container_Settings
 */
class SmartToolz_List_Container_Settings extends SmartToolz_Abstract_Ability {
	/**
	 * Configure the ability.
	 */
	public function configure() {
		$this->id          = 'smarttoolz/list-container-setting';
		$this->label       = __( 'List SmartToolz Container Settings', 'smarttoolz' );
		$this->description = __( 'Lists all available SmartToolz theme container and layout settings including container layout options, container styles, width configurations, background settings, and their current values. Provides comprehensive information about site layout structure.', 'smarttoolz' );
		$this->category    = 'smarttoolz';
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
				'detailed' => array(
					'type'        => 'boolean',
					'description' => __( 'Whether to include detailed descriptions and available options for each setting. Default is true.', 'smarttoolz' ),
					'default'     => true,
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
				'summary'        => array(
					'type'        => 'object',
					'description' => 'Quick overview of current container settings.',
				),
				'settings'       => array(
					'type'        => 'object',
					'description' => 'Detailed container settings with values, labels, and metadata.',
				),
				'total_settings' => array(
					'type'        => 'integer',
					'description' => 'Total number of settings returned.',
				),
				'detailed'       => array(
					'type'        => 'boolean',
					'description' => 'Whether detailed information was included.',
				),
				'notes'          => array(
					'type'        => 'array',
					'description' => 'Helpful notes about container settings.',
					'items'       => array( 'type' => 'string' ),
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
			'list all container settings',
			'show all container options',
			'display container configuration',
			'view all layout settings',
			'get container settings list',
			'show available container layouts',
			'list container layout options',
			'display all container styles',
			'view container width settings',
			'get complete container settings',
		);
	}

	/**
	 * Execute the ability.
	 *
	 * @param array $args Input arguments.
	 * @return array Result array.
	 */
	public function execute( $args ) {
		$detailed = isset( $args['detailed'] ) ? (bool) $args['detailed'] : true;

		// Get current values.
		$container_layout       = smarttoolz_get_option( 'ast-site-content-layout', 'normal-width-container' );
		$container_style        = smarttoolz_get_option( 'site-content-style', 'boxed' );
		$site_content_width     = smarttoolz_get_option( 'site-content-width', 1200 );
		$narrow_container_width = smarttoolz_get_option( 'narrow-container-max-width', 750 );
		$site_layout            = smarttoolz_get_option( 'site-layout', 'ast-full-width-layout' );
		$site_sidebar_width     = smarttoolz_get_option( 'site-sidebar-width', 30 );
		$site_bg                = smarttoolz_get_option( 'site-layout-outside-bg-obj-responsive' );
		$content_bg             = smarttoolz_get_option( 'content-bg-obj-responsive' );

		// Layout labels.
		$layout_labels = array(
			'normal-width-container' => __( 'Normal', 'smarttoolz' ),
			'narrow-width-container' => __( 'Narrow', 'smarttoolz' ),
			'full-width-container'   => __( 'Full Width', 'smarttoolz' ),
		);

		// Style labels.
		$style_labels = array(
			'boxed'   => __( 'Boxed', 'smarttoolz' ),
			'unboxed' => __( 'Unboxed', 'smarttoolz' ),
		);

		// Site layout labels.
		$site_layout_labels = array(
			'ast-full-width-layout' => __( 'Full Width', 'smarttoolz' ),
			'ast-padded-layout'     => __( 'Padded', 'smarttoolz' ),
			'ast-fluid-layout'      => __( 'Fluid', 'smarttoolz' ),
		);

		// Build settings array.
		$settings = array(
			'container_layout'       => array(
				'value'        => $container_layout,
				'label'        => isset( $layout_labels[ $container_layout ] ) ? $layout_labels[ $container_layout ] : $container_layout,
				'setting_name' => 'ast-site-content-layout',
				'type'         => 'layout',
			),
			'container_style'        => array(
				'value'        => $container_style,
				'label'        => isset( $style_labels[ $container_style ] ) ? $style_labels[ $container_style ] : $container_style,
				'setting_name' => 'site-content-style',
				'type'         => 'style',
			),
			'site_content_width'     => array(
				'value'        => $site_content_width,
				'label'        => $site_content_width . 'px',
				'setting_name' => 'site-content-width',
				'type'         => 'dimension',
			),
			'narrow_container_width' => array(
				'value'        => $narrow_container_width,
				'label'        => $narrow_container_width . 'px',
				'setting_name' => 'narrow-container-max-width',
				'type'         => 'dimension',
			),
			'site_layout'            => array(
				'value'        => $site_layout,
				'label'        => isset( $site_layout_labels[ $site_layout ] ) ? $site_layout_labels[ $site_layout ] : $site_layout,
				'setting_name' => 'site-layout',
				'type'         => 'layout',
			),
			'site_sidebar_width'     => array(
				'value'        => $site_sidebar_width,
				'label'        => $site_sidebar_width . '%',
				'setting_name' => 'site-sidebar-width',
				'type'         => 'dimension',
			),
			'site_background'        => array(
				'value'        => $site_bg,
				'setting_name' => 'site-layout-outside-bg-obj-responsive',
				'type'         => 'background',
			),
			'content_background'     => array(
				'value'        => $content_bg,
				'setting_name' => 'content-bg-obj-responsive',
				'type'         => 'background',
			),
		);

		// Add detailed information if requested.
		if ( $detailed ) {
			$settings['container_layout']['description']       = __( 'Controls the width of the content area across the site. Choose from Normal, Narrow, or Full Width layouts.', 'smarttoolz' );
			$settings['container_layout']['available_options'] = array(
				'normal-width-container' => array(
					'label'       => __( 'Normal', 'smarttoolz' ),
					'description' => __( 'Standard width container for general content.', 'smarttoolz' ),
				),
				'narrow-width-container' => array(
					'label'       => __( 'Narrow', 'smarttoolz' ),
					'description' => __( 'Narrower container ideal for blog posts and text-heavy content.', 'smarttoolz' ),
				),
				'full-width-container'   => array(
					'label'       => __( 'Full Width', 'smarttoolz' ),
					'description' => __( 'Full-width stretched container that spans the entire viewport.', 'smarttoolz' ),
				),
			);

			$settings['container_style']['description']       = __( 'Defines the visual style of the content container. Only applies when layout is Normal or Narrow.', 'smarttoolz' );
			$settings['container_style']['available_options'] = array(
				'boxed'   => array(
					'label'       => __( 'Boxed', 'smarttoolz' ),
					'description' => __( 'Content has padding and background, creating a boxed appearance.', 'smarttoolz' ),
				),
				'unboxed' => array(
					'label'       => __( 'Unboxed', 'smarttoolz' ),
					'description' => __( 'Plain style without padding, content flows naturally.', 'smarttoolz' ),
				),
			);

			$settings['site_content_width']['description']  = __( 'Maximum width of the normal container in pixels. Default is 1200px.', 'smarttoolz' );
			$settings['site_content_width']['default']      = 1200;
			$settings['site_content_width']['unit']         = 'px';
			$settings['site_content_width']['setting_type'] = 'numeric';

			$settings['narrow_container_width']['description']  = __( 'Maximum width of the narrow container in pixels. Default is 750px.', 'smarttoolz' );
			$settings['narrow_container_width']['default']      = 750;
			$settings['narrow_container_width']['unit']         = 'px';
			$settings['narrow_container_width']['setting_type'] = 'numeric';

			$settings['site_layout']['description']       = __( 'Site-wide layout style that affects the overall page structure.', 'smarttoolz' );
			$settings['site_layout']['available_options'] = array(
				'ast-full-width-layout' => array(
					'label'       => __( 'Full Width', 'smarttoolz' ),
					'description' => __( 'Content spans full width without outer spacing.', 'smarttoolz' ),
				),
				'ast-padded-layout'     => array(
					'label'       => __( 'Padded', 'smarttoolz' ),
					'description' => __( 'Adds padding around the entire site content (Requires SmartToolz Pro).', 'smarttoolz' ),
				),
				'ast-fluid-layout'      => array(
					'label'       => __( 'Fluid', 'smarttoolz' ),
					'description' => __( 'Container width adjusts fluidly to viewport (Requires SmartToolz Pro).', 'smarttoolz' ),
				),
			);

			$settings['site_sidebar_width']['description']  = __( 'Width of the sidebar as a percentage of the container. Default is 30%.', 'smarttoolz' );
			$settings['site_sidebar_width']['default']      = 30;
			$settings['site_sidebar_width']['unit']         = '%';
			$settings['site_sidebar_width']['setting_type'] = 'numeric';

			$settings['site_background']['description'] = __( 'Background styling for the area outside the content container.', 'smarttoolz' );
			$settings['site_background']['properties']  = array(
				__( 'Supports color, gradient, and image backgrounds', 'smarttoolz' ),
				__( 'Responsive settings available', 'smarttoolz' ),
				__( 'Affects the entire site background', 'smarttoolz' ),
			);

			$settings['content_background']['description'] = __( 'Background styling for the content area itself.', 'smarttoolz' );
			$settings['content_background']['properties']  = array(
				__( 'Supports color, gradient, and image backgrounds', 'smarttoolz' ),
				__( 'Responsive settings available', 'smarttoolz' ),
				__( 'Visible when container style is boxed', 'smarttoolz' ),
			);
		}

		// Prepare summary information.
		$summary = array(
			'current_layout' => isset( $layout_labels[ $container_layout ] ) ? $layout_labels[ $container_layout ] : $container_layout,
			'current_style'  => isset( $style_labels[ $container_style ] ) ? $style_labels[ $container_style ] : $container_style,
			'content_width'  => $site_content_width . 'px',
			'narrow_width'   => $narrow_container_width . 'px',
			'sidebar_width'  => $site_sidebar_width . '%',
			'style_applies'  => in_array( $container_layout, array( 'normal-width-container', 'narrow-width-container' ), true ),
			'style_note'     => __( 'Container style applies only when layout is Normal or Narrow', 'smarttoolz' ),
		);

		// Build response data.
		$response_data = array(
			'summary'        => $summary,
			'settings'       => $settings,
			'total_settings' => count( $settings ),
			'detailed'       => $detailed,
		);

		// Add helpful notes.
		if ( $detailed ) {
			$response_data['notes'] = array(
				__( 'Container style (boxed/unboxed) only takes effect when container layout is set to Normal or Narrow.', 'smarttoolz' ),
				__( 'Full Width layout ignores container style setting.', 'smarttoolz' ),
				__( 'Width values can be adjusted through the Customizer under Global > Container.', 'smarttoolz' ),
				__( 'Background settings support colors, gradients, and images with responsive options.', 'smarttoolz' ),
				__( 'Padded and Fluid layouts require SmartToolz Pro plugin.', 'smarttoolz' ),
			);
		}

		return SmartToolz_Abilities_Response::success(
			/* translators: %d: number of container settings */
			sprintf( __( 'Retrieved %d container settings successfully.', 'smarttoolz' ), count( $settings ) ),
			$response_data
		);
	}
}

SmartToolz_List_Container_Settings::register();
