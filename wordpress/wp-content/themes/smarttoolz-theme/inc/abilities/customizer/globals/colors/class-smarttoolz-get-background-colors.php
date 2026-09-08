<?php
/**
 * Get Background Colors Ability
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.12.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Get_Background_Colors
 */
class SmartToolz_Get_Background_Colors extends SmartToolz_Abstract_Ability {
	/**
	 * Configure the ability.
	 */
	public function configure() {
		$this->id          = 'smarttoolz/get-color-background';
		$this->label       = __( 'Get SmartToolz Background Colors', 'smarttoolz' );
		$this->description = __( 'Retrieves the current SmartToolz theme background color settings including site background and content background configurations.', 'smarttoolz' );
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
				'site_background'    => array(
					'type'        => 'object',
					'description' => 'Site background configuration (responsive with desktop, tablet, mobile).',
				),
				'content_background' => array(
					'type'        => 'object',
					'description' => 'Content area background configuration (responsive with desktop, tablet, mobile).',
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
			'get current background colors',
			'show site background settings',
			'view content background configuration',
			'display background color values',
			'get site and content backgrounds',
			'show current background setup',
			'view background settings',
			'get background image settings',
			'show background repeat and position',
			'get complete background configuration',
		);
	}

	/**
	 * Execute the ability.
	 *
	 * @param array $args Input arguments.
	 * @return array Result array.
	 */
	public function execute( $args ) {
		$site_background    = smarttoolz_get_option( 'site-layout-outside-bg-obj-responsive', array() );
		$content_background = smarttoolz_get_option( 'content-bg-obj-responsive', array() );

		return SmartToolz_Abilities_Response::success(
			__( 'Retrieved background color settings successfully.', 'smarttoolz' ),
			array(
				'site_background'    => $site_background,
				'content_background' => $content_background,
			)
		);
	}
}

SmartToolz_Get_Background_Colors::register();
