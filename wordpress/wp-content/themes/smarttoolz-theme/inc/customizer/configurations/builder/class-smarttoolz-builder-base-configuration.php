<?php
/**
 * SmartToolz Builder Base Configuration.
 *
 * @package smarttoolz-builder
 */

// No direct access, please.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Builder_Base_Configuration.
 */
final class SmartToolz_Builder_Base_Configuration {
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
	 * Prepare Advance Typography configuration.
	 *
	 * @param string $section_id section id.
	 * @param array  $required_condition Required Condition.
	 * @param array  $divider_setup Required divider setup.
	 * @return array
	 */
	public static function prepare_typography_options( $section_id, $required_condition = array(), $divider_setup = array() ) {

		$parent = SMARTTOOLZ_THEME_SETTINGS . '[' . $section_id . '-typography]';

		if ( defined( 'SMARTTOOLZ_EXT_VER' ) && SmartToolz_Ext_Extension::is_active( 'typography' ) ) {

			$_configs = array(

				array(
					'name'      => $parent,
					'default'   => smarttoolz_get_option( $section_id . '-typography' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Text Font', 'smarttoolz' ),
					'is_font'   => true,
					'section'   => $section_id,
					'divider'   => $divider_setup,
					'transport' => 'postMessage',
					'priority'  => 16,
					'context'   => empty( $required_condition ) ? SmartToolz_Builder_Helper::$design_tab : $required_condition,
				),

				/**
				 * Option: Font Size
				 */

				array(
					'name'              => 'font-size-' . $section_id,
					'type'              => 'sub-control',
					'parent'            => $parent,
					'section'           => $section_id,
					'control'           => 'ast-responsive-slider',
					'default'           => smarttoolz_get_option( 'font-size-' . $section_id ),
					'transport'         => 'postMessage',
					'priority'          => 15,
					'title'             => __( 'Font Size', 'smarttoolz' ),
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
					'suffix'            => array( 'px', 'em', 'vw', 'rem' ),
					'input_attrs'       => array(
						'px'  => array(
							'min'  => 0,
							'step' => 1,
							'max'  => 200,
						),
						'em'  => array(
							'min'  => 0,
							'step' => 0.01,
							'max'  => 20,
						),
						'vw'  => array(
							'min'  => 0,
							'step' => 0.1,
							'max'  => 25,
						),
						'rem' => array(
							'min'  => 0,
							'step' => 0.1,
							'max'  => 20,
						),
					),
				),

			);
		} else {

			$_configs = array(

				/**
				 * Option: Font Size
				 */

				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[font-size-' . $section_id . ']',
					'section'           => $section_id,
					'default'           => smarttoolz_get_option( 'font-size-' . $section_id ),
					'type'              => 'control',
					'transport'         => 'postMessage',
					'control'           => 'ast-responsive-slider',
					'priority'          => 16,
					'title'             => __( 'Font Size', 'smarttoolz' ),
					'context'           => empty( $required_condition ) ? SmartToolz_Builder_Helper::$design_tab : $required_condition,
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
					'suffix'            => array( 'px', 'em', 'vw', 'rem' ),
					'input_attrs'       => array(
						'px'  => array(
							'min'  => 0,
							'step' => 1,
							'max'  => 200,
						),
						'em'  => array(
							'min'  => 0,
							'step' => 0.01,
							'max'  => 20,
						),
						'vw'  => array(
							'min'  => 0,
							'step' => 0.1,
							'max'  => 25,
						),
						'rem' => array(
							'min'  => 0,
							'step' => 0.1,
							'max'  => 20,
						),
					),
				),
			);
		}

		return $_configs;
	}

	/**
	 * Prepare Visibility options.
	 *
	 * @param string $_section section id.
	 * @param string $builder_type Builder Type.
	 * @return array
	 */
	public static function prepare_visibility_tab( $_section, $builder_type = 'header' ) {
		$smarttoolz_options = SmartToolz_Theme_Options::get_smarttoolz_options();
		/**
		 * Option: Visibility
		 */
		return array(
			array(
				'name'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $_section . '-visibility-responsive]',
				'default'   => smarttoolz_get_option(
					'' . $_section . '-visibility-responsive',
					array(
						'desktop' => ! isset( $smarttoolz_options[ '' . $_section . '-visibility-responsive' ] ) && isset( $smarttoolz_options[ '' . $_section . '-hide-desktop' ] ) ? ( $smarttoolz_options[ '' . $_section . '-hide-desktop' ] ? 0 : 1 ) : 1,
						'tablet'  => ! isset( $smarttoolz_options[ '' . $_section . '-visibility-responsive' ] ) && isset( $smarttoolz_options[ '' . $_section . '-hide-tablet' ] ) ? ( $smarttoolz_options[ '' . $_section . '-hide-tablet' ] ? 0 : 1 ) : 1,
						'mobile'  => ! isset( $smarttoolz_options[ '' . $_section . '-visibility-responsive' ] ) && isset( $smarttoolz_options[ '' . $_section . '-hide-mobile' ] ) ? ( $smarttoolz_options[ '' . $_section . '-hide-mobile' ] ? 0 : 1 ) : 1,
					)
				),
				'type'      => 'control',
				'control'   => 'ast-multi-selector',
				'section'   => $_section,
				'priority'  => 320,
				'title'     => __( 'Visibility', 'smarttoolz' ),
				'context'   => SmartToolz_Builder_Helper::$general_tab,
				'transport' => 'refresh',
				'choices'   => array(
					'desktop' => 'customizer-desktop',
					'tablet'  => 'customizer-tablet',
					'mobile'  => 'customizer-mobile',
				),
				'divider'   => array( 'ast_class' => 'ast-top-section-divider' ),
			),
		);
	}

	/**
	 * Prepare common options for the widgets by type.
	 *
	 * @param string $type type.
	 * @return array
	 */
	public static function prepare_widget_options( $type = 'header' ) {
		$html_config = array();

		if ( 'footer' === $type ) {
			$component_limit = defined( 'SMARTTOOLZ_EXT_VER' ) ?
				SmartToolz_Builder_Helper::$component_limit : SmartToolz_Builder_Helper::$num_of_footer_widgets;
		} else {
			$component_limit = defined( 'SMARTTOOLZ_EXT_VER' ) ?
				SmartToolz_Builder_Helper::$component_limit : SmartToolz_Builder_Helper::$num_of_header_widgets;
		}
		$smarttoolz_has_widgets_block_editor = smarttoolz_has_widgets_block_editor();
		for ( $index = 1; $index <= $component_limit; $index++ ) {

			$_section = ! $smarttoolz_has_widgets_block_editor ? 'sidebar-widgets-' . $type . '-widget-' . $index : 'smarttoolz-sidebar-widgets-' . $type . '-widget-' . $index;

			$html_config[] = array(

				array(
					'name'        => $_section,
					'type'        => 'section',
					'priority'    => 5,
					'title'       => __( 'Widget ', 'smarttoolz' ) . $index,
					'panel'       => 'panel-' . $type . '-builder-group',
					'clone_index' => $index,
					'clone_type'  => $type . '-widget',
					'divider'     => array( 'ast_class' => 'ast-bottom-divider' ),
				),

				/**
				 * Option: Margin
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[' . $_section . '-margin]',
					'default'           => smarttoolz_get_option( $_section . '-margin' ),
					'type'              => 'control',
					'transport'         => 'postMessage',
					'control'           => 'ast-responsive-spacing',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
					'section'           => $_section,
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
					'divider'           => array( 'ast_class' => ' ast-section-spacing ' ),
				),
			);

			if ( 'footer' === $type ) {
				$html_config [] = array(
					array(
						'name'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $type . '-widget-alignment-' . $index . ']',
						'default'   => smarttoolz_get_option( $type . '-widget-alignment-' . $index ),
						'type'      => 'control',
						'control'   => 'ast-selector',
						'section'   => $_section,
						'priority'  => 5,
						'title'     => __( 'Alignment', 'smarttoolz' ),
						'transport' => 'postMessage',
						'choices'   => array(
							'left'   => 'align-left',
							'center' => 'align-center',
							'right'  => 'align-right',
						),
						'divider'   => ! $smarttoolz_has_widgets_block_editor ? array( 'ast_class' => 'ast-top-divider' ) : array( 'ast_class' => 'ast-bottom-section-divider ast-section-spacing' ),
					),
				);
			}

				$html_config[] = array(

					/**
					 * Option: Widget title color.
					 */
					array(
						'name'       => SMARTTOOLZ_THEME_SETTINGS . '[' . $type . '-widget-' . $index . '-title-color]',
						'default'    => smarttoolz_get_option( $type . '-widget-' . $index . '-title-color' ),
						'title'      => __( 'Heading Color', 'smarttoolz' ),
						'type'       => 'control',
						'section'    => $_section,
						'priority'   => 7,
						'transport'  => 'postMessage',
						'control'    => 'ast-responsive-color',
						'responsive' => true,
						'divider'    => ! $smarttoolz_has_widgets_block_editor ? array( 'ast_class' => 'ast-top-divider' ) : array( 'ast_class' => 'ast-section-spacing' ),
						'rgba'       => true,
					),

					/**
					 * Option: Widget Color.
					 */
					array(
						'name'       => SMARTTOOLZ_THEME_SETTINGS . '[' . $type . '-widget-' . $index . '-color]',
						'default'    => smarttoolz_get_option( $type . '-widget-' . $index . '-color' ),
						'title'      => __( 'Content Color', 'smarttoolz' ),
						'type'       => 'control',
						'section'    => $_section,
						'priority'   => 7,
						'transport'  => 'postMessage',
						'control'    => 'ast-responsive-color',
						'responsive' => true,
						'rgba'       => true,
					),
					array(
						'name'       => SMARTTOOLZ_THEME_SETTINGS . '[' . $type . '-widget-' . $index . '-link-color-group]',
						'default'    => smarttoolz_get_option( $type . '-widget-' . $index . '-color-group' ),
						'type'       => 'control',
						'control'    => 'ast-color-group',
						'title'      => __( 'Link Color', 'smarttoolz' ),
						'section'    => $_section,
						'transport'  => 'postMessage',
						'priority'   => 7,
						'responsive' => true,
						'divider'    => array( 'ast_class' => 'ast-bottom-divider' ),
					),

					/**
					 * Option: Widget link color.
					 */
					array(
						'name'       => $type . '-widget-' . $index . '-link-color',
						'default'    => smarttoolz_get_option( $type . '-widget-' . $index . '-link-color' ),
						'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[' . $type . '-widget-' . $index . '-link-color-group]',
						'type'       => 'sub-control',
						'section'    => $_section,
						'priority'   => 3,
						'transport'  => 'postMessage',
						'control'    => 'ast-responsive-color',
						'responsive' => true,
						'rgba'       => true,
						'title'      => __( 'Normal', 'smarttoolz' ),
					),

					/**
					 * Option: Widget link color.
					 */
					array(
						'name'       => $type . '-widget-' . $index . '-link-h-color',
						'default'    => smarttoolz_get_option( $type . '-widget-' . $index . '-link-h-color' ),
						'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[' . $type . '-widget-' . $index . '-link-color-group]',
						'type'       => 'sub-control',
						'section'    => $_section,
						'priority'   => 1,
						'transport'  => 'postMessage',
						'control'    => 'ast-responsive-color',
						'responsive' => true,
						'rgba'       => true,
						'title'      => __( 'Hover', 'smarttoolz' ),
					),
				);

				if ( defined( 'SMARTTOOLZ_EXT_VER' ) && SmartToolz_Ext_Extension::is_active( 'typography' ) ) {
					$html_config[] = array(

						/**
						 * Option: Widget Title Typography
						 */
						array(
							'name'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $type . '-widget-' . $index . '-text-typography]',
							'default'   => smarttoolz_get_option( $type . '-widget-' . $index . '-text-typography' ),
							'type'      => 'control',
							'control'   => 'ast-settings-group',
							'is_font'   => true,
							'title'     => __( 'Heading Font', 'smarttoolz' ),
							'section'   => $_section,
							'transport' => 'postMessage',
							'priority'  => 90,
							'divider'   => array( 'ast_class' => 'ast-bottom-divider' ),
						),

						/**
						 * Option: Widget Title Font Size
						 */
						array(
							'name'        => $type . '-widget-' . $index . '-font-size',
							'default'     => smarttoolz_get_option( $type . '-widget-' . $index . '-font-size' ),
							'parent'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $type . '-widget-' . $index . '-text-typography]',
							'transport'   => 'postMessage',
							'title'       => __( 'Font Size', 'smarttoolz' ),
							'type'        => 'sub-control',
							'section'     => $_section,
							'control'     => 'ast-responsive-slider',
							'suffix'      => array( 'px', 'em', 'vw', 'rem' ),
							'input_attrs' => array(
								'px'  => array(
									'min'  => 0,
									'step' => 1,
									'max'  => 200,
								),
								'em'  => array(
									'min'  => 0,
									'step' => 0.01,
									'max'  => 20,
								),
								'vw'  => array(
									'min'  => 0,
									'step' => 0.1,
									'max'  => 25,
								),
								'rem' => array(
									'min'  => 0,
									'step' => 0.1,
									'max'  => 20,
								),
							),
							'priority'    => 2,
						),

						/**
						 * Option: Widget Content Typography
						 */
						array(
							'name'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $type . '-widget-' . $index . '-content-typography]',
							'default'   => smarttoolz_get_option( $type . '-widget-' . $index . '-content-typography' ),
							'type'      => 'control',
							'control'   => 'ast-settings-group',
							'is_font'   => true,
							'title'     => __( 'Content Font', 'smarttoolz' ),
							'section'   => $_section,
							'transport' => 'postMessage',
							'priority'  => 91,
						),

						/**
						 * Option: Widget Content Font Size
						 */
						array(
							'name'        => $type . '-widget-' . $index . '-content-font-size',
							'default'     => smarttoolz_get_option( $type . '-widget-' . $index . '-content-font-size' ),
							'parent'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $type . '-widget-' . $index . '-content-typography]',
							'transport'   => 'postMessage',
							'title'       => __( 'Font Size', 'smarttoolz' ),
							'type'        => 'sub-control',
							'section'     => $_section,
							'control'     => 'ast-responsive-slider',
							'suffix'      => array( 'px', 'em', 'vw', 'rem' ),
							'input_attrs' => array(
								'px'  => array(
									'min'  => 0,
									'step' => 1,
									'max'  => 200,
								),
								'em'  => array(
									'min'  => 0,
									'step' => 0.01,
									'max'  => 20,
								),
								'vw'  => array(
									'min'  => 0,
									'step' => 0.1,
									'max'  => 25,
								),
								'rem' => array(
									'min'  => 0,
									'step' => 0.1,
									'max'  => 20,
								),
							),
							'priority'    => 2,
						),
					);
				} else {
					$html_config[] = array(

						/**
						 * Option: Widget Title Font Size
						 */
						array(
							'name'        => SMARTTOOLZ_THEME_SETTINGS . '[' . $type . '-widget-' . $index . '-font-size]',
							'default'     => smarttoolz_get_option( $type . '-widget-' . $index . '-font-size' ),
							'transport'   => 'postMessage',
							'title'       => __( 'Title Font Size', 'smarttoolz' ),
							'type'        => 'control',
							'section'     => $_section,
							'control'     => 'ast-responsive-slider',
							'suffix'      => array( 'px', 'em', 'vw', 'rem' ),
							'input_attrs' => array(
								'px'  => array(
									'min'  => 0,
									'step' => 1,
									'max'  => 200,
								),
								'em'  => array(
									'min'  => 0,
									'step' => 0.01,
									'max'  => 20,
								),
								'vw'  => array(
									'min'  => 0,
									'step' => 0.1,
									'max'  => 25,
								),
								'rem' => array(
									'min'  => 0,
									'step' => 0.1,
									'max'  => 20,
								),
							),
							'priority'    => 90,
						),

						/**
						 * Option: Widget Content Font Size
						 */
						array(
							'name'        => SMARTTOOLZ_THEME_SETTINGS . '[' . $type . '-widget-' . $index . '-content-font-size]',
							'default'     => smarttoolz_get_option( $type . '-widget-' . $index . '-content-font-size' ),
							'transport'   => 'postMessage',
							'title'       => __( 'Content Font Size', 'smarttoolz' ),
							'type'        => 'control',
							'section'     => $_section,
							'control'     => 'ast-responsive-slider',
							'suffix'      => array( 'px', 'em', 'vw', 'rem' ),
							'input_attrs' => array(
								'px'  => array(
									'min'  => 0,
									'step' => 1,
									'max'  => 200,
								),
								'em'  => array(
									'min'  => 0,
									'step' => 0.01,
									'max'  => 20,
								),
								'vw'  => array(
									'min'  => 0,
									'step' => 0.1,
									'max'  => 25,
								),
								'rem' => array(
									'min'  => 0,
									'step' => 0.1,
									'max'  => 20,
								),
							),
							'priority'    => 91,
						),
					);
				}

				$html_config[] = self::prepare_visibility_tab( $_section, $type );

		}

		return call_user_func_array( 'array_merge', $html_config + array( array() ) );
	}

}

/**
 *  Prepare if class 'SmartToolz_Builder_Base_Configuration' exist.
 *  Kicking this off by calling 'get_instance()' method
 */
SmartToolz_Builder_Base_Configuration::get_instance();
