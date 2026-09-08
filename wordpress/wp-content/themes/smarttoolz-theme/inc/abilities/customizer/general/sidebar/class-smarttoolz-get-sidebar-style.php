<?php
/**
 * Get Sidebar Style Ability
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.12.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Get_Sidebar_Style
 */
class SmartToolz_Get_Sidebar_Style extends SmartToolz_Abstract_Ability {
	/**
	 * Configure the ability.
	 *
	 * @return void
	 */
	public function configure() {
		$this->id          = 'smarttoolz/get-sidebar-style';
		$this->label       = __( 'Get SmartToolz Sidebar Style', 'smarttoolz' );
		$this->description = __( 'Retrieves the sidebar style setting for the SmartToolz theme (boxed or unboxed).', 'smarttoolz' );
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
			'get sidebar style',
			'show sidebar style',
			'view sidebar style',
			'display sidebar style',
			'get sidebar design',
			'show sidebar design style',
			'view sidebar appearance',
			'display sidebar box style',
			'get current sidebar style',
			'show sidebar style setting',
			'view sidebar style option',
			'display sidebar design setting',
			'get sidebar box setting',
			'show if sidebar is boxed',
			'view sidebar style configuration',
			'display sidebar visual style',
			'get sidebar container style',
			'show sidebar wrapper style',
			'view sidebar border style',
			'display sidebar style choice',
			'get sidebar styling option',
			'show sidebar design option',
			'view current sidebar style',
			'display sidebar style status',
			'get sidebar display style',
			'show sidebar presentation style',
			'view sidebar box option',
			'display sidebar style preference',
			'get active sidebar style',
			'show site sidebar style',
		);
	}

	/**
	 * Execute the ability.
	 *
	 * @param array $args Input arguments.
	 * @return array Result array.
	 */
	public function execute( $args ) {
		$style = smarttoolz_get_option( 'site-sidebar-style', 'unboxed' );

		$style_labels = array(
			'unboxed' => 'Unboxed',
			'boxed'   => 'Boxed',
		);

		return SmartToolz_Abilities_Response::success(
			__( 'Retrieved sidebar style successfully.', 'smarttoolz' ),
			array(
				'style'            => $style,
				'style_label'      => isset( $style_labels[ $style ] ) ? $style_labels[ $style ] : $style,
				'available_styles' => $style_labels,
			)
		);
	}
}

SmartToolz_Get_Sidebar_Style::register();
