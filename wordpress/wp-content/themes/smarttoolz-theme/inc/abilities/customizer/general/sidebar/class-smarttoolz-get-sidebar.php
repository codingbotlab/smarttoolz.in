<?php
/**
 * Get Sidebar Settings Ability
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.12.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Get_Sidebar
 */
class SmartToolz_Get_Sidebar extends SmartToolz_Abstract_Ability {
	/**
	 * Configure the ability.
	 *
	 * @return void
	 */
	public function configure() {
		$this->id          = 'smarttoolz/get-sidebar';
		$this->label       = __( 'Get SmartToolz Sidebar Settings', 'smarttoolz' );
		$this->description = __( 'Retrieves all sidebar settings for the SmartToolz theme including layout, style, width, and sticky sidebar.', 'smarttoolz' );
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
	 * Get examples.
	 *
	 * @return array
	 */
	public function get_examples() {
		return array(
			'get sidebar settings',
			'show sidebar configuration',
			'view all sidebar options',
			'display sidebar settings',
			'get sidebar options',
			'show sidebar preferences',
			'view sidebar configuration',
			'display all sidebar settings',
			'get complete sidebar setup',
			'show sidebar layout and style',
			'view sidebar design settings',
			'display sidebar customization',
			'get sidebar display options',
			'show sidebar position and width',
			'view sidebar style and layout',
			'display sidebar configuration options',
			'get sidebar layout settings',
			'show all sidebar preferences',
			'view sidebar setup',
			'display sidebar design options',
			'get sidebar appearance settings',
			'show sidebar style options',
			'view sidebar layout options',
			'display complete sidebar config',
			'get site sidebar settings',
			'show default sidebar options',
			'view sidebar customization options',
			'display sidebar layout configuration',
			'get sidebar design preferences',
			'show sidebar appearance options',
		);
	}

	/**
	 * Execute the ability.
	 *
	 * @param array $args Input arguments.
	 * @return array Result array.
	 */
	public function execute( $args ) {
		$layout = smarttoolz_get_option( 'site-sidebar-layout', 'no-sidebar' );
		$style  = smarttoolz_get_option( 'site-sidebar-style', 'unboxed' );
		$width  = smarttoolz_get_option( 'site-sidebar-width', 30 );
		$sticky = smarttoolz_get_option( 'site-sticky-sidebar', false );

		$layout_labels = array(
			'no-sidebar'    => 'No Sidebar',
			'left-sidebar'  => 'Left Sidebar',
			'right-sidebar' => 'Right Sidebar',
		);

		$style_labels = array(
			'unboxed' => 'Unboxed',
			'boxed'   => 'Boxed',
		);

		return SmartToolz_Abilities_Response::success(
			__( 'Retrieved sidebar settings successfully.', 'smarttoolz' ),
			array(
				'layout'            => $layout,
				'layout_label'      => isset( $layout_labels[ $layout ] ) ? $layout_labels[ $layout ] : $layout,
				'style'             => $style,
				'style_label'       => isset( $style_labels[ $style ] ) ? $style_labels[ $style ] : $style,
				'width'             => (int) $width,
				'width_label'       => $width . '%',
				'sticky_enabled'    => (bool) $sticky,
				'sticky_label'      => $sticky ? 'Enabled' : 'Disabled',
				'available_layouts' => $layout_labels,
				'available_styles'  => $style_labels,
				'width_range'       => array(
					'min' => 15,
					'max' => 50,
				),
			)
		);
	}
}

SmartToolz_Get_Sidebar::register();
