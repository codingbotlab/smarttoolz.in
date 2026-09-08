<?php
/**
 * Get Body Font Ability
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.12.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Get_Body_Font
 */
class SmartToolz_Get_Body_Font extends SmartToolz_Abstract_Ability {
	/**
	 * Configure the ability.
	 */
	public function configure() {
		$this->id          = 'smarttoolz/get-font-body';
		$this->label       = __( 'Get SmartToolz Body Font', 'smarttoolz' );
		$this->description = __( 'Retrieves the current SmartToolz theme body font settings including font family, weight, size, line height, and other typography properties.', 'smarttoolz' );
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
				'font_family' => array(
					'type'        => 'string',
					'description' => 'Current body font family.',
				),
				'font_weight' => array(
					'type'        => 'string',
					'description' => 'Current body font weight.',
				),
				'font_size'   => array(
					'type'        => 'object',
					'description' => 'Responsive font size with desktop, tablet, mobile values and units.',
				),
				'font_extras' => array(
					'type'        => 'object',
					'description' => 'Additional typography settings (line height, text transform, letter spacing).',
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
			'get current body font',
			'show body font settings',
			'view body typography',
			'display body text font',
			'get body font family',
			'show body font weight',
			'view body font size',
			'display body text settings',
			'get body typography configuration',
			'show current body font values',
		);
	}

	/**
	 * Execute the ability.
	 *
	 * @param array $args Input arguments.
	 * @return array Result array.
	 */
	public function execute( $args ) {
		return SmartToolz_Abilities_Response::success(
			__( 'Retrieved body font settings successfully.', 'smarttoolz' ),
			array(
				'font_family' => smarttoolz_get_option( 'body-font-family', '' ),
				'font_weight' => smarttoolz_get_option( 'body-font-weight', '' ),
				'font_size'   => smarttoolz_get_option( 'font-size-body', array() ),
				'font_extras' => smarttoolz_get_option( 'body-font-extras', array() ),
			)
		);
	}
}

SmartToolz_Get_Body_Font::register();
