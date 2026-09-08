<?php
/**
 * Get Global Buttons Ability
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.12.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Get_Global_Buttons
 */
class SmartToolz_Get_Global_Buttons extends SmartToolz_Abstract_Ability {
	/**
	 * Configure the ability.
	 *
	 * @return void
	 */
	public function configure() {
		$this->id          = 'smarttoolz/get-global-buttons';
		$this->label       = __( 'Get SmartToolz Global Buttons', 'smarttoolz' );
		$this->description = __( 'Retrieves the current SmartToolz theme global button settings for both primary and secondary buttons including text colors, background colors, border colors, padding, border width, and border radius.', 'smarttoolz' );
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
		$button_schema = array(
			'type'        => 'object',
			'description' => __( 'Button settings object.', 'smarttoolz' ),
			'properties'  => array(
				'preset'                 => array(
					'type'        => 'string',
					'description' => __( 'Button preset style key.', 'smarttoolz' ),
				),
				'preset_label'           => array(
					'type'        => 'string',
					'description' => __( 'Human-readable preset label.', 'smarttoolz' ),
				),
				'text_color'             => array(
					'type'        => 'string',
					'description' => __( 'Button text color.', 'smarttoolz' ),
				),
				'text_hover_color'       => array(
					'type'        => 'string',
					'description' => __( 'Button text hover color.', 'smarttoolz' ),
				),
				'background_color'       => array(
					'type'        => 'string',
					'description' => __( 'Button background color.', 'smarttoolz' ),
				),
				'background_hover_color' => array(
					'type'        => 'string',
					'description' => __( 'Button background hover color.', 'smarttoolz' ),
				),
				'border_color'           => array(
					'type'        => 'string',
					'description' => __( 'Button border color.', 'smarttoolz' ),
				),
				'border_hover_color'     => array(
					'type'        => 'string',
					'description' => __( 'Button border hover color.', 'smarttoolz' ),
				),
				'padding'                => array(
					'type'        => 'object',
					'description' => __( 'Button padding values.', 'smarttoolz' ),
				),
				'border_width'           => array(
					'type'        => 'object',
					'description' => __( 'Button border width values.', 'smarttoolz' ),
				),
				'border_radius'          => array(
					'type'        => 'object',
					'description' => __( 'Button border radius values.', 'smarttoolz' ),
				),
			),
		);

		return $this->build_output_schema(
			array(
				'primary_button'    => $button_schema,
				'secondary_button'  => $button_schema,
				'available_presets' => array(
					'type'        => 'object',
					'description' => __( 'Available button preset options.', 'smarttoolz' ),
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
			'get current button settings',
			'show global button styles',
			'view primary button colors',
			'display secondary button settings',
			'get button text colors',
			'show button background colors',
			'view button border colors',
			'display button padding settings',
			'get button border radius',
			'show button border width',
			'view current button preset',
			'display primary button configuration',
			'get secondary button colors',
			'show all button settings',
			'view button style configuration',
			'display global button options',
			'get primary and secondary buttons',
			'show button color scheme',
			'view button spacing settings',
			'display button border settings',
			'get current button styles',
			'show button design settings',
			'view button appearance',
			'display theme button settings',
			'get button preset style',
		);
	}

	/**
	 * Execute the ability.
	 *
	 * @param array $args Input arguments.
	 * @return array Result array.
	 */
	public function execute( $args ) {
		$preset_labels = array(
			'button_01' => 'Button 01 - Square',
			'button_02' => 'Button 02 - Rounded',
			'button_03' => 'Button 03 - Pill',
			'button_04' => 'Button 04 - Square Outline',
			'button_05' => 'Button 05 - Rounded Outline',
			'button_06' => 'Button 06 - Pill Outline',
		);

		$primary_preset   = smarttoolz_get_option( 'button-preset-style', '' );
		$secondary_preset = smarttoolz_get_option( 'secondary-button-preset-style', '' );

		return SmartToolz_Abilities_Response::success(
			__( 'Retrieved global button settings successfully.', 'smarttoolz' ),
			array(
				'primary_button'    => array(
					'preset'                 => $primary_preset,
					'preset_label'           => isset( $preset_labels[ $primary_preset ] ) ? $preset_labels[ $primary_preset ] : '',
					'text_color'             => smarttoolz_get_option( 'button-color', '' ),
					'text_hover_color'       => smarttoolz_get_option( 'button-h-color', '' ),
					'background_color'       => smarttoolz_get_option( 'button-bg-color', '' ),
					'background_hover_color' => smarttoolz_get_option( 'button-bg-h-color', '' ),
					'border_color'           => smarttoolz_get_option( 'theme-button-border-group-border-color', '' ),
					'border_hover_color'     => smarttoolz_get_option( 'theme-button-border-group-border-h-color', '' ),
					'padding'                => smarttoolz_get_option( 'theme-button-padding', array() ),
					'border_width'           => smarttoolz_get_option( 'theme-button-border-group-border-size', array() ),
					'border_radius'          => smarttoolz_get_option( 'button-radius-fields', array() ),
				),
				'secondary_button'  => array(
					'preset'                 => $secondary_preset,
					'preset_label'           => isset( $preset_labels[ $secondary_preset ] ) ? $preset_labels[ $secondary_preset ] : '',
					'text_color'             => smarttoolz_get_option( 'secondary-button-color', '' ),
					'text_hover_color'       => smarttoolz_get_option( 'secondary-button-h-color', '' ),
					'background_color'       => smarttoolz_get_option( 'secondary-button-bg-color', '' ),
					'background_hover_color' => smarttoolz_get_option( 'secondary-button-bg-h-color', '' ),
					'border_color'           => smarttoolz_get_option( 'secondary-theme-button-border-group-border-color', '' ),
					'border_hover_color'     => smarttoolz_get_option( 'secondary-theme-button-border-group-border-h-color', '' ),
					'padding'                => smarttoolz_get_option( 'secondary-theme-button-padding', array() ),
					'border_width'           => smarttoolz_get_option( 'secondary-theme-button-border-group-border-size', array() ),
					'border_radius'          => smarttoolz_get_option( 'secondary-button-radius-fields', array() ),
				),
				'available_presets' => $preset_labels,
			)
		);
	}
}

SmartToolz_Get_Global_Buttons::register();
