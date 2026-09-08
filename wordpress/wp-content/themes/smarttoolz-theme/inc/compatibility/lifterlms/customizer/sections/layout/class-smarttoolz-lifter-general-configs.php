<?php
/**
 * LifterLMS General Options for our theme.
 *
 * @package     SmartToolz
 * @link        https://www.brainstormforce.com
 * @since       1.4.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SmartToolz_Lifter_General_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class SmartToolz_Lifter_General_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register SmartToolz-LifterLMS General Customizer Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			if ( defined( 'SMARTTOOLZ_EXT_VER' ) && SmartToolz_Ext_Extension::is_active( 'lifterlms' ) ) {
				$section = 'section-lifterlms-general';
			} else {
				$section = 'section-lifterlms';
			}

			$_configs = array(

				/**
				 * Option: Divider
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[llms-course-grid-divider]',
					'section'  => $section,
					'title'    => __( 'Columns', 'smarttoolz' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 1,
					'settings' => array(),
					'divider'  => array( 'ast_class' => 'ast-section-spacing' ),
				),

				/**
				 * Option: Course Columns
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[llms-course-grid]',
					'type'              => 'control',
					'control'           => 'ast-responsive-slider',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
					'section'           => $section,
					'default'           => smarttoolz_get_option(
						'llms-course-grid',
						array(
							'desktop' => 3,
							'tablet'  => 2,
							'mobile'  => 1,
						)
					),
					'title'             => __( 'Course Columns', 'smarttoolz' ),
					'priority'          => 1,
					'input_attrs'       => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 6,
					),
					'divider'           => array( 'ast_class' => 'ast-section-spacing ast-bottom-section-divider' ),
				),

				/**
				 * Option: Membership Columns
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[llms-membership-grid]',
					'type'              => 'control',
					'control'           => 'ast-responsive-slider',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
					'section'           => $section,
					'default'           => smarttoolz_get_option(
						'llms-membership-grid',
						array(
							'desktop' => 3,
							'tablet'  => 2,
							'mobile'  => 1,
						)
					),
					'title'             => __( 'Membership Columns', 'smarttoolz' ),
					'priority'          => 1,
					'input_attrs'       => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 6,
					),
				),
			);

			// Learn More link if SmartToolz Pro is not activated.
			if ( smarttoolz_showcase_upgrade_notices() ) {

				$_configs[] = array(
					'name'        => SMARTTOOLZ_THEME_SETTINGS . '[llms-upgrade-link]',
					'type'        => 'control',
					'control'     => 'ast-upgrade',
					'campaign'    => 'lifterlms',
					'section'     => $section,
					'priority'    => 999,
					'default'     => '',
					'context'     => array(),
					'title'       => __( 'Running Online Courses?', 'smarttoolz' ),
					'description' => __( 'Optimize your LMS for conversion & retention with Business Toolkit!', 'smarttoolz' ),
					'choices'     => array(
						'one'   => array(
							'title' => __( 'Automate management workflows with OttoKit', 'smarttoolz' ),
						),
						'two'   => array(
							'title' => __( 'Ready-to-use course website templates', 'smarttoolz' ),
						),
						'three' => array(
							'title' => __( 'Distraction-free high-converting checkout', 'smarttoolz' ),
						),
						'four'  => array(
							'title' => __( 'Structured course & lesson pages', 'smarttoolz' ),
						),
						'five'  => array(
							'title' => __( 'Improved student engagement', 'smarttoolz' ),
						),
					),
					'divider'     => array( 'ast_class' => 'ast-top-section-divider' ),
				);

			}

			return array_merge( $configurations, $_configs );
		}
	}
}

new SmartToolz_Lifter_General_Configs();
