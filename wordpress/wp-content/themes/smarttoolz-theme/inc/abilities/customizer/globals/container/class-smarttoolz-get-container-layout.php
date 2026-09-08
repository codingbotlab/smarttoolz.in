<?php
/**
 * Get Container Layout Ability
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.12.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Get_Container_Layout
 */
class SmartToolz_Get_Container_Layout extends SmartToolz_Abstract_Ability {
	/**
	 * Configure the ability.
	 */
	public function configure() {
		$this->id          = 'smarttoolz/get-container-layout';
		$this->label       = __( 'Get SmartToolz Container Layout', 'smarttoolz' );
		$this->description = __( 'Retrieves the current SmartToolz theme container layout settings including container layout, container style, container width, and narrow container width.', 'smarttoolz' );
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
				'container_layout'       => array(
					'type'        => 'string',
					'description' => 'Current container layout slug.',
				),
				'container_layout_label' => array(
					'type'        => 'string',
					'description' => 'Human-readable container layout name.',
				),
				'container_style'        => array(
					'type'        => 'string',
					'description' => 'Current container style slug.',
				),
				'container_style_label'  => array(
					'type'        => 'string',
					'description' => 'Human-readable container style name.',
				),
				'available_layouts'      => array(
					'type'        => 'object',
					'description' => 'Map of available layout slugs to labels.',
				),
				'available_styles'       => array(
					'type'        => 'object',
					'description' => 'Map of available style slugs to labels.',
				),
				'note'                   => array(
					'type'        => 'string',
					'description' => 'Additional context about settings.',
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
			'get current container layout',
			'show container layout settings',
			'view container style',
			'display current container width',
			'get site container settings',
			'show container layout configuration',
			'view container width values',
			'display container style settings',
			'get narrow container width',
			'show current site layout',
		);
	}

	/**
	 * Execute the ability.
	 *
	 * @param array $args Input arguments.
	 * @return array Result array.
	 */
	public function execute( $args ) {
		$container_layout = smarttoolz_get_option( 'ast-site-content-layout', 'normal-width-container' );
		$container_style  = smarttoolz_get_option( 'site-content-style', 'boxed' );

		$layout_labels = array(
			'normal-width-container' => __( 'Normal', 'smarttoolz' ),
			'narrow-width-container' => __( 'Narrow', 'smarttoolz' ),
			'full-width-container'   => __( 'Full Width', 'smarttoolz' ),
		);

		$style_labels = array(
			'boxed'   => __( 'Boxed', 'smarttoolz' ),
			'unboxed' => __( 'Unboxed', 'smarttoolz' ),
		);

		return SmartToolz_Abilities_Response::success(
			__( 'Retrieved container layout settings successfully.', 'smarttoolz' ),
			array(
				'container_layout'       => $container_layout,
				'container_layout_label' => isset( $layout_labels[ $container_layout ] ) ? $layout_labels[ $container_layout ] : $container_layout,
				'container_style'        => $container_style,
				'container_style_label'  => isset( $style_labels[ $container_style ] ) ? $style_labels[ $container_style ] : $container_style,
				'available_layouts'      => array(
					'normal-width-container' => __( 'Normal', 'smarttoolz' ),
					'narrow-width-container' => __( 'Narrow', 'smarttoolz' ),
					'full-width-container'   => __( 'Full Width', 'smarttoolz' ),
				),
				'available_styles'       => array(
					'boxed'   => __( 'Boxed', 'smarttoolz' ),
					'unboxed' => __( 'Unboxed', 'smarttoolz' ),
				),
				'note'                   => __( 'Container style applies only when layout is set to Normal or Narrow.', 'smarttoolz' ),
			)
		);
	}
}

SmartToolz_Get_Container_Layout::register();
