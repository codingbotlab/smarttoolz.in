<?php
/**
 * Bottom Footer Options for SmartToolz Theme.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SmartToolz_Sidebar_Layout_Configs' ) ) {

	/**
	 * Register SmartToolz Sidebar Layout Configurations.
	 */
	class SmartToolz_Sidebar_Layout_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register SmartToolz Sidebar Layout Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Default Sidebar Position
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[site-sidebar-layout]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'           => 'section-sidebars',
					'default'           => smarttoolz_get_option( 'site-sidebar-layout' ),
					'priority'          => 5,
					'description'       => __( 'Sidebar will only apply when container layout is set to normal.', 'smarttoolz' ),
					'title'             => __( 'Default Layout', 'smarttoolz' ),
					'choices'           => array(
						'no-sidebar'    => array(
							'label' => __( 'No Sidebar', 'smarttoolz' ),
							'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'no-sidebar', false ) : '',
						),
						'left-sidebar'  => array(
							'label' => __( 'Left Sidebar', 'smarttoolz' ),
							'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'left-sidebar', false ) : '',
						),
						'right-sidebar' => array(
							'label' => __( 'Right Sidebar', 'smarttoolz' ),
							'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'right-sidebar', false ) : '',
						),
					),
				),

				/**
				 * Option: Site Sidebar Style.
				 */
				array(
					'name'       => SMARTTOOLZ_THEME_SETTINGS . '[site-sidebar-style]',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => 'section-sidebars',
					'default'    => smarttoolz_get_option( 'site-sidebar-style', 'unboxed' ),
					'priority'   => 9,
					'title'      => __( 'Sidebar Style', 'smarttoolz' ),
					'choices'    => array(
						'unboxed' => __( 'Unboxed', 'smarttoolz' ),
						'boxed'   => __( 'Boxed', 'smarttoolz' ),
					),
					'responsive' => false,
					'renderAs'   => 'text',
					'divider'    => array( 'ast_class' => 'ast-top-divider ast-bottom-section-divider' ),
				),

				/**
				 * Option: Primary Content Width
				 */
				array(
					'name'        => SMARTTOOLZ_THEME_SETTINGS . '[site-sidebar-width]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'default'     => smarttoolz_get_option( 'site-sidebar-width' ),
					'section'     => 'section-sidebars',
					'priority'    => 15,
					'title'       => __( 'Sidebar Width', 'smarttoolz' ),
					'suffix'      => '%',
					'transport'   => 'postMessage',
					'input_attrs' => array(
						'min'  => 15,
						'step' => 1,
						'max'  => 50,
					),

				),

				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[site-sidebar-width-description]',
					'type'     => 'control',
					'control'  => 'ast-description',
					'section'  => 'section-sidebars',
					'priority' => 15,
					'title'    => '',
					'help'     => __( 'Sidebar width will apply only when one of the above sidebar is set.', 'smarttoolz' ),
					'divider'  => array( 'ast_class' => 'ast-bottom-section-divider' ),
					'settings' => array(),
				),

				/**
				 * Option: Sticky Sidebar
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[site-sticky-sidebar]',
					'default'  => smarttoolz_get_option( 'site-sticky-sidebar' ),
					'type'     => 'control',
					'section'  => 'section-sidebars',
					'title'    => __( 'Enable Sticky Sidebar', 'smarttoolz' ),
					'priority' => 15,
					'control'  => 'ast-toggle-control',
					'divider'  => array( 'ast_class' => 'ast-section-spacing' ),
				),
			);

			// Learn More link if SmartToolz Pro is not activated.
			if ( smarttoolz_showcase_upgrade_notices() ) {
				$_configs[] = array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[ast-sidebar-pro-items]',
					'type'     => 'control',
					'control'  => 'ast-upgrade',
					'campaign' => 'sidebar',
					'choices'  => array(
						'one'   => array(
							'title' => __( 'Sidebar spacing', 'smarttoolz' ),
						),
						'two'   => array(
							'title' => __( 'Sidebar color options', 'smarttoolz' ),
						),
						'three' => array(
							'title' => __( 'Widget color options', 'smarttoolz' ),
						),
						'four'  => array(
							'title' => __( 'Widget title typography', 'smarttoolz' ),
						),
						'five'  => array(
							'title' => __( 'Widget content typography', 'smarttoolz' ),
						),
					),
					'section'  => 'section-sidebars',
					'default'  => '',
					'priority' => 999,
					'title'    => __( 'Get advanced sidebar controls', 'smarttoolz' ),
					'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
				);
			}

			return array_merge( $configurations, $_configs );
		}
	}
}

new SmartToolz_Sidebar_Layout_Configs();
