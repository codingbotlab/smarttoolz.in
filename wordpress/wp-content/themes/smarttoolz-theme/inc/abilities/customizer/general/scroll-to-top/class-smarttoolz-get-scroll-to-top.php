<?php
/**
 * Get Scroll to Top Settings Ability
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.13.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Get_Scroll_To_Top
 */
class SmartToolz_Get_Scroll_To_Top extends SmartToolz_Abstract_Ability {
	/**
	 * Configure the ability.
	 *
	 * @return void
	 */
	public function configure() {
		$this->id          = 'smarttoolz/get-scroll-to-top';
		$this->label       = __( 'Get SmartToolz Scroll to Top Settings', 'smarttoolz' );
		$this->description = __( 'Retrieves the current SmartToolz theme scroll-to-top button settings including enable state, position, device visibility, icon size, colors, and border radius.', 'smarttoolz' );
		$this->category    = 'smarttoolz';
	}

	/**
	 * Get output schema.
	 *
	 * @return array
	 */
	public function get_output_schema() {
		return $this->build_output_schema(
			array(
				'enabled'             => array(
					'type'        => 'boolean',
					'description' => 'Whether the scroll-to-top button is enabled.',
				),
				'position'            => array(
					'type'        => 'string',
					'description' => 'Button position (left or right).',
				),
				'on_devices'          => array(
					'type'        => 'string',
					'description' => 'Device visibility (both, desktop, or mobile).',
				),
				'icon_size'           => array(
					'type'        => 'integer',
					'description' => 'Icon size in pixels.',
				),
				'colors'              => array(
					'type'        => 'object',
					'description' => 'Color settings (icon, background, hover variants).',
				),
				'border_radius'       => array(
					'type'        => 'object',
					'description' => 'Border radius settings.',
				),
				'available_positions' => array(
					'type'        => 'object',
					'description' => 'Available position options.',
				),
				'available_devices'   => array(
					'type'        => 'object',
					'description' => 'Available device visibility options.',
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
			'get scroll to top settings',
			'show scroll to top button configuration',
			'is scroll to top enabled',
			'get scroll to top colors',
			'show scroll to top position',
			'view back to top button settings',
		);
	}

	/**
	 * Execute the ability.
	 *
	 * @param array $args Input arguments.
	 * @return array Result array.
	 */
	public function execute( $args ) {
		if ( ! defined( 'SMARTTOOLZ_THEME_SETTINGS' ) ) {
			return SmartToolz_Abilities_Response::error(
				__( 'SmartToolz theme is not active.', 'smarttoolz' ),
				__( 'Please activate the SmartToolz theme to use this feature.', 'smarttoolz' )
			);
		}

		$colors = array(
			'icon_color'      => smarttoolz_get_option( 'scroll-to-top-icon-color', '' ),
			'icon_bg_color'   => smarttoolz_get_option( 'scroll-to-top-icon-bg-color', '' ),
			'icon_h_color'    => smarttoolz_get_option( 'scroll-to-top-icon-h-color', '' ),
			'icon_h_bg_color' => smarttoolz_get_option( 'scroll-to-top-icon-h-bg-color', '' ),
		);

		$position_labels = array(
			'right' => 'Right',
			'left'  => 'Left',
		);

		$device_labels = array(
			'both'    => 'Both (Desktop & Mobile)',
			'desktop' => 'Desktop Only',
			'mobile'  => 'Mobile Only',
		);

		return SmartToolz_Abilities_Response::success(
			__( 'Retrieved scroll-to-top settings successfully.', 'smarttoolz' ),
			array(
				'enabled'             => (bool) smarttoolz_get_option( 'scroll-to-top-enable', true ),
				'position'            => smarttoolz_get_option( 'scroll-to-top-icon-position', 'right' ),
				'on_devices'          => smarttoolz_get_option( 'scroll-to-top-on-devices', 'both' ),
				'icon_size'           => absint( smarttoolz_get_option( 'scroll-to-top-icon-size', 15 ) ),
				'colors'              => $colors,
				'border_radius'       => smarttoolz_get_option( 'scroll-to-top-icon-radius-fields', array() ),
				'available_positions' => $position_labels,
				'available_devices'   => $device_labels,
			)
		);
	}
}

SmartToolz_Get_Scroll_To_Top::register();
