<?php
/**
 * Container Options for SmartToolz theme.
 *
 * @package     SmartToolz
 * @link        https://www.brainstormforce.com
 * @since       1.4.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SmartToolz_Lifter_Container_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 *
	 * @since 1.4.3
	 */
	class SmartToolz_Lifter_Container_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register LifterLMS Container Settings.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {
			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			if ( defined( 'SMARTTOOLZ_EXT_VER' ) && SmartToolz_Ext_Extension::is_active( 'lifterlms' ) ) {
				$section = 'section-lifterlms-general';
			} else {
				$section = 'section-lifterlms';
			}

			$_configs = array(

				/**
				 * Option: Revamped Container Layout.
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[lifterlms-ast-content-layout]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'           => $section,
					'default'           => smarttoolz_get_option( 'lifterlms-ast-content-layout' ),
					'priority'          => 1,
					'title'             => __( 'Container Layout', 'smarttoolz' ),
					'choices'           => array(
						'default'                => array(
							'label' => __( 'Default', 'smarttoolz' ),
							'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'layout-default', false ) : '',
						),
						'normal-width-container' => array(
							'label' => __( 'Normal', 'smarttoolz' ),
							'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'normal-width-container', false ) : '',
						),
						'full-width-container'   => array(
							'label' => __( 'Full Width', 'smarttoolz' ),
							'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'full-width-container', false ) : '',
						),
					),
					'divider'           => array( 'ast_class' => 'ast-section-spacing ast-bottom-divider' ),
				),

				/**
				 * Option: Content Style Option.
				 */
				array(
					'name'        => SMARTTOOLZ_THEME_SETTINGS . '[lifterlms-content-style]',
					'type'        => 'control',
					'control'     => 'ast-selector',
					'section'     => $section,
					'default'     => smarttoolz_get_option( 'lifterlms-content-style', 'default' ),
					'priority'    => 1,
					'title'       => __( 'Container Style', 'smarttoolz' ),
					'description' => __( 'Container style will apply only when layout is set to either normal or narrow.', 'smarttoolz' ),
					'choices'     => array(
						'default' => __( 'Default', 'smarttoolz' ),
						'unboxed' => __( 'Unboxed', 'smarttoolz' ),
						'boxed'   => __( 'Boxed', 'smarttoolz' ),
					),
					'renderAs'    => 'text',
					'responsive'  => false,
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new SmartToolz_Lifter_Container_Configs();
