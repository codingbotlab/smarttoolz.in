<?php
/**
 * SmartToolz Extended Configuration.
 *
 * @package SmartToolz
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Extended_Base_Configuration.
 */
final class SmartToolz_Extended_Base_Configuration {
	/**
	 * Member Variable
	 *
	 * @var mixed instance
	 */
	private static $instance = null;

	/**
	 *  Initiator
	 */
	public static function get_instance() {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
	}

	/**
	 * Prepare Advance header configuration.
	 *
	 * @param string $section_id Section ID.
	 * @param string $heading_class Optional. Heading class. Defaults to 'ast-top-section-divider'.
	 * @return array
	 */
	public static function prepare_advanced_tab( $section_id, $heading_class = 'ast-top-section-divider' ) {

		return array(

			/**
			 * Option: Divider
			 */
			array(
				'name'     => SMARTTOOLZ_THEME_SETTINGS . '[' . $section_id . '-divider]',
				'section'  => $section_id,
				'title'    => __( 'Spacing', 'smarttoolz' ),
				'type'     => 'control',
				'control'  => 'ast-heading',
				'priority' => 210,
				'settings' => array(),
				'context'  => SmartToolz_Builder_Helper::$design_tab,
				'divider'  => array( 'ast_class' => $heading_class ),
			),

			/**
			 * Option: Padded Layout Custom Width
			 */
			array(
				'name'              => SMARTTOOLZ_THEME_SETTINGS . '[' . $section_id . '-padding]',
				'default'           => smarttoolz_get_option( $section_id . '-padding' ),
				'type'              => 'control',
				'transport'         => 'postMessage',
				'control'           => 'ast-responsive-spacing',
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
				'section'           => $section_id,
				'priority'          => 210,
				'title'             => __( 'Padding', 'smarttoolz' ),
				'linked_choices'    => true,
				'unit_choices'      => array( 'px', 'em', '%' ),
				'choices'           => array(
					'top'    => __( 'Top', 'smarttoolz' ),
					'right'  => __( 'Right', 'smarttoolz' ),
					'bottom' => __( 'Bottom', 'smarttoolz' ),
					'left'   => __( 'Left', 'smarttoolz' ),
				),
				'context'           => SmartToolz_Builder_Helper::$design_tab,
				'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
			),

			/**
			 * Option: Padded Layout Custom Width
			 */
			array(
				'name'              => SMARTTOOLZ_THEME_SETTINGS . '[' . $section_id . '-margin]',
				'default'           => smarttoolz_get_option( $section_id . '-margin' ),
				'type'              => 'control',
				'transport'         => 'postMessage',
				'control'           => 'ast-responsive-spacing',
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
				'section'           => $section_id,
				'priority'          => 220,
				'title'             => __( 'Margin', 'smarttoolz' ),
				'linked_choices'    => true,
				'unit_choices'      => array( 'px', 'em', '%' ),
				'choices'           => array(
					'top'    => __( 'Top', 'smarttoolz' ),
					'right'  => __( 'Right', 'smarttoolz' ),
					'bottom' => __( 'Bottom', 'smarttoolz' ),
					'left'   => __( 'Left', 'smarttoolz' ),
				),
				'context'           => SmartToolz_Builder_Helper::$design_tab,
				'divider'           => array( 'ast_class' => 'ast-top-section-divider' ),
			),
		);
	}

	/**
	 * Prepare Spacing & Border options.
	 *
	 * @param string $section_id section id.
	 * @param bool   $skip_border_divider Skip border control divider or not.
	 *
	 * @since 4.6.0
	 * @return array
	 */
	public static function prepare_section_spacing_border_options( $section_id, $skip_border_divider = false ) {
		$_configs        = array(
			array(
				'name'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $section_id . '-border-group]',
				'default'   => smarttoolz_get_option( $section_id . '-border-group' ),
				'type'      => 'control',
				'control'   => 'ast-settings-group',
				'title'     => __( 'Border', 'smarttoolz' ),
				'section'   => $section_id,
				'transport' => 'postMessage',
				'priority'  => 150,
				'divider'   => true === $skip_border_divider ? array( 'ast_class' => 'ast-top-section-spacing' ) : array( 'ast_class' => 'ast-top-divider' ),
				'context'   => SmartToolz_Builder_Helper::$design_tab,
			),
			array(
				'name'           => $section_id . '-border-width',
				'default'        => smarttoolz_get_option( $section_id . '-border-width' ),
				'parent'         => SMARTTOOLZ_THEME_SETTINGS . '[' . $section_id . '-border-group]',
				'type'           => 'sub-control',
				'transport'      => 'postMessage',
				'control'        => 'ast-border',
				'title'          => __( 'Border Width', 'smarttoolz' ),
				'divider'        => array( 'ast_class' => 'ast-bottom-divider' ),
				'section'        => $section_id,
				'linked_choices' => true,
				'priority'       => 1,
				'choices'        => array(
					'top'    => __( 'Top', 'smarttoolz' ),
					'right'  => __( 'Right', 'smarttoolz' ),
					'bottom' => __( 'Bottom', 'smarttoolz' ),
					'left'   => __( 'Left', 'smarttoolz' ),
				),
			),
			array(
				'name'              => $section_id . '-border-color',
				'default'           => smarttoolz_get_option( $section_id . '-border-color' ),
				'type'              => 'sub-control',
				'priority'          => 1,
				'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[' . $section_id . '-border-group]',
				'section'           => $section_id,
				'control'           => 'ast-color',
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
				'transport'         => 'postMessage',
				'title'             => __( 'Color', 'smarttoolz' ),
				'divider'           => array( 'ast_class' => 'ast-top-spacing ast-bottom-spacing' ),
			),
			array(
				'name'           => $section_id . '-border-radius',
				'default'        => smarttoolz_get_option( $section_id . '-border-radius' ),
				'parent'         => SMARTTOOLZ_THEME_SETTINGS . '[' . $section_id . '-border-group]',
				'type'           => 'sub-control',
				'transport'      => 'postMessage',
				'control'        => 'ast-border',
				'title'          => __( 'Border Radius', 'smarttoolz' ),
				'divider'        => array( 'ast_class' => 'ast-top-divider' ),
				'section'        => $section_id,
				'linked_choices' => true,
				'priority'       => 1,
				'choices'        => array(
					'top'    => __( 'Top', 'smarttoolz' ),
					'right'  => __( 'Right', 'smarttoolz' ),
					'bottom' => __( 'Bottom', 'smarttoolz' ),
					'left'   => __( 'Left', 'smarttoolz' ),
				),
			),
		);
		$spacing_configs = self::prepare_advanced_tab( $section_id );
		return array_merge( $_configs, $spacing_configs );
	}
}

/**
 *  Prepare if class 'SmartToolz_Extended_Base_Configuration' exist.
 *  Kicking this off by calling 'get_instance()' method
 */
SmartToolz_Extended_Base_Configuration::get_instance();
