<?php
/**
 * Get Sidebar Width Ability
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.12.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Get_Sidebar_Width
 */
class SmartToolz_Get_Sidebar_Width extends SmartToolz_Abstract_Ability {
	/**
	 * Configure the ability.
	 *
	 * @return void
	 */
	public function configure() {
		$this->id          = 'smarttoolz/get-sidebar-width';
		$this->label       = __( 'Get SmartToolz Sidebar Width', 'smarttoolz' );
		$this->description = __( 'Retrieves the sidebar width setting for the SmartToolz theme (15-50%).', 'smarttoolz' );
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
			'get sidebar width',
			'show sidebar width',
			'view sidebar width',
			'display sidebar width',
			'get sidebar size',
			'show sidebar size setting',
			'view sidebar width setting',
			'display sidebar width percentage',
			'get current sidebar width',
			'show sidebar width value',
			'view sidebar width option',
			'display sidebar size setting',
			'get sidebar width configuration',
			'show how wide sidebar is',
			'view sidebar width percent',
			'display sidebar width amount',
			'get sidebar column width',
			'show sidebar area width',
			'view sidebar space width',
			'display sidebar width number',
			'get sidebar width percentage',
			'show sidebar width in percent',
			'view current sidebar width',
			'display sidebar width status',
			'get sidebar dimension',
			'show sidebar width dimension',
			'view sidebar width measurement',
			'display sidebar width value',
			'get site sidebar width',
			'show default sidebar width',
		);
	}

	/**
	 * Execute the ability.
	 *
	 * @param array $args Input arguments.
	 * @return array Result array.
	 */
	public function execute( $args ) {
		$width = smarttoolz_get_option( 'site-sidebar-width', 30 );

		return SmartToolz_Abilities_Response::success(
			__( 'Retrieved sidebar width successfully.', 'smarttoolz' ),
			array(
				'width'       => (int) $width,
				'width_label' => $width . '%',
				'min_width'   => 15,
				'max_width'   => 50,
			)
		);
	}
}

SmartToolz_Get_Sidebar_Width::register();
