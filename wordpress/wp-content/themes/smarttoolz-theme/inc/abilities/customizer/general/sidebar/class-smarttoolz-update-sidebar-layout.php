<?php
/**
 * Update Sidebar Layout Ability
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.12.7
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Update_Sidebar_Layout
 */
class SmartToolz_Update_Sidebar_Layout extends SmartToolz_Abstract_Ability {
	/**
	 * Configure the ability.
	 *
	 * @return void
	 */
	public function configure() {
		$this->id          = 'smarttoolz/update-sidebar-layout';
		$this->category    = 'smarttoolz';
		$this->label       = __( 'Update SmartToolz Sidebar Layout', 'smarttoolz' );
		$this->description = __( 'Updates the default sidebar layout setting for the SmartToolz theme (no sidebar, left sidebar, or right sidebar).', 'smarttoolz' );

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
				'layout' => array(
					'type'        => 'string',
					'description' => 'Sidebar layout. Options: "no-sidebar" (No Sidebar), "left-sidebar" (Left Sidebar), "right-sidebar" (Right Sidebar).',
					'enum'        => array( 'no-sidebar', 'left-sidebar', 'right-sidebar' ),
				),
			),
			'required'   => array( 'layout' ),
		);
	}

	/**
	 * Get examples.
	 *
	 * @return array
	 */
	public function get_examples() {
		return array(
			'set sidebar to right',
			'change sidebar to left',
			'disable sidebar',
			'enable right sidebar',
			'set left sidebar',
			'remove sidebar',
			'add right sidebar',
			'change sidebar position to right',
			'set no sidebar',
			'enable left sidebar',
			'switch to right sidebar',
			'change to no sidebar',
			'set sidebar layout to left',
			'update sidebar to right side',
			'change sidebar placement to left',
			'set sidebar on right',
			'move sidebar to left',
			'configure sidebar to right',
			'update sidebar position to left',
			'set default sidebar to right',
			'change sidebar to right side',
			'enable sidebar on left',
			'set sidebar layout right',
			'update to left sidebar',
			'change default sidebar position',
			'set site sidebar to right',
			'move sidebar right',
			'configure left sidebar',
			'update sidebar layout to no sidebar',
			'change to right sidebar layout',
		);
	}

	/**
	 * Execute the ability.
	 *
	 * @param array $args Input arguments.
	 * @return array Result array.
	 */
	public function execute( $args ) {
		if ( ! isset( $args['layout'] ) ) {
			return SmartToolz_Abilities_Response::error(
				__( 'Layout is required.', 'smarttoolz' ),
				__( 'Please provide a layout: no-sidebar, left-sidebar, or right-sidebar.', 'smarttoolz' )
			);
		}

		$layout        = sanitize_text_field( $args['layout'] );
		$valid_layouts = array( 'no-sidebar', 'left-sidebar', 'right-sidebar' );

		if ( ! in_array( $layout, $valid_layouts, true ) ) {
			return SmartToolz_Abilities_Response::error(
				sprintf(
					/* translators: %s: layout value */
					__( 'Invalid layout: %s.', 'smarttoolz' ),
					$layout
				),
				__( 'Valid options: no-sidebar, left-sidebar, right-sidebar', 'smarttoolz' )
			);
		}

		$layout_labels = array(
			'no-sidebar'    => 'No Sidebar',
			'left-sidebar'  => 'Left Sidebar',
			'right-sidebar' => 'Right Sidebar',
		);

		smarttoolz_update_option( 'site-sidebar-layout', $layout );

		$message = sprintf(
			/* translators: %s: layout label */
			__( 'Sidebar layout set to %s.', 'smarttoolz' ),
			$layout_labels[ $layout ]
		);

		return SmartToolz_Abilities_Response::success(
			$message,
			array(
				'updated' => true,
				'layout'  => $layout,
			)
		);
	}
}

SmartToolz_Update_Sidebar_Layout::register();
