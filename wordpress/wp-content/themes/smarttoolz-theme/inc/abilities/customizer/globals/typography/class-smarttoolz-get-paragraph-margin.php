<?php
/**
 * Get Paragraph Margin Ability
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.12.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Get_Paragraph_Margin
 */
class SmartToolz_Get_Paragraph_Margin extends SmartToolz_Abstract_Ability {
	/**
	 * Configure the ability.
	 */
	public function configure() {
		$this->id          = 'smarttoolz/get-paragraph-margin';
		$this->label       = __( 'Get Paragraph Margin', 'smarttoolz' );
		$this->description = __( 'Retrieves the current paragraph margin bottom setting in the SmartToolz theme.', 'smarttoolz' );
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
				'margin_bottom' => array(
					'type'        => 'number',
					'description' => 'Current paragraph margin bottom value in em.',
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
			'get current paragraph margin',
			'show paragraph spacing',
			'view paragraph margin bottom',
			'display paragraph gap setting',
			'get paragraph bottom spacing',
			'show current paragraph margin value',
			'view paragraph vertical spacing',
			'display paragraph separation',
			'get space between paragraphs',
			'show paragraph margin settings',
		);
	}

	/**
	 * Execute the ability.
	 *
	 * @param array $args Input arguments.
	 * @return array Result array.
	 */
	public function execute( $args ) {
		$margin_bottom = floatval( smarttoolz_get_option( 'para-margin-bottom', 1.6 ) );

		return SmartToolz_Abilities_Response::success(
			/* translators: %s: margin bottom value */
			sprintf( __( 'Current paragraph margin bottom: %s em', 'smarttoolz' ), $margin_bottom ),
			array(
				'margin_bottom' => $margin_bottom,
			)
		);
	}
}

SmartToolz_Get_Paragraph_Margin::register();
