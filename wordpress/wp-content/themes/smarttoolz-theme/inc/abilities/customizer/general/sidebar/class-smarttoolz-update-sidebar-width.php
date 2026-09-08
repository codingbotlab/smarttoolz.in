<?php
/**
 * Update Sidebar Width Ability
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.12.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Update_Sidebar_Width
 */
class SmartToolz_Update_Sidebar_Width extends SmartToolz_Abstract_Ability {
	/**
	 * Configure the ability.
	 *
	 * @return void
	 */
	public function configure() {
		$this->id          = 'smarttoolz/update-sidebar-width';
		$this->category    = 'smarttoolz';
		$this->label       = __( 'Update SmartToolz Sidebar Width', 'smarttoolz' );
		$this->description = __( 'Updates the sidebar width setting for the SmartToolz theme (15-50%).', 'smarttoolz' );

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
				'width' => array(
					'type'        => 'integer',
					'description' => 'Sidebar width in percentage (15-50).',
					'minimum'     => 15,
					'maximum'     => 50,
				),
			),
			'required'   => array( 'width' ),
		);
	}

	/**
	 * Get examples.
	 *
	 * @return array
	 */
	public function get_examples() {
		return array(
			'set sidebar width to 30%',
			'change sidebar width to 25%',
			'update sidebar width to 35%',
			'set sidebar to 20% width',
			'make sidebar 30 percent wide',
			'change sidebar width 25',
			'set sidebar size to 30%',
			'update sidebar to 40% width',
			'change sidebar to 35 percent',
			'set sidebar width 30',
			'make sidebar width 25%',
			'update sidebar size to 30',
			'change sidebar to 20% wide',
			'set sidebar column to 30%',
			'make sidebar 35% width',
			'update sidebar width 25',
			'change sidebar area to 30%',
			'set sidebar space to 25%',
			'make sidebar 40 percent',
			'update sidebar dimension to 30',
			'change sidebar to 30 percent wide',
			'set sidebar width percentage to 25',
			'make sidebar column 30%',
			'update sidebar area width to 35',
			'change sidebar space to 30%',
			'set sidebar to 25 percent width',
			'make sidebar size 30%',
			'update site sidebar width',
			'change default sidebar width',
			'set sidebar width value to 30',
		);
	}

	/**
	 * Execute the ability.
	 *
	 * @param array $args Input arguments.
	 * @return array Result array.
	 */
	public function execute( $args ) {
		if ( ! isset( $args['width'] ) ) {
			return SmartToolz_Abilities_Response::error(
				__( 'Width is required.', 'smarttoolz' ),
				__( 'Please provide a width value between 15 and 50.', 'smarttoolz' )
			);
		}

		$width = absint( $args['width'] );

		if ( $width < 15 || $width > 50 ) {
			return SmartToolz_Abilities_Response::error(
				sprintf(
					/* translators: %d: width value */
					__( 'Invalid width: %d.', 'smarttoolz' ),
					$width
				),
				__( 'Width must be between 15 and 50 percent.', 'smarttoolz' )
			);
		}

		smarttoolz_update_option( 'site-sidebar-width', $width );

		$message = sprintf(
			/* translators: %d: width percentage */
			__( 'Sidebar width set to %d%%.', 'smarttoolz' ),
			$width
		);

		return SmartToolz_Abilities_Response::success(
			$message,
			array(
				'updated' => true,
				'width'   => $width,
			)
		);
	}
}

SmartToolz_Update_Sidebar_Width::register();
