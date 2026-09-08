<?php
/**
 * Content Spacing Options for our theme.
 *
 * @package     SmartToolz
 * @link        https://www.brainstormforce.com
 * @since       SmartToolz 1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SmartToolz_Lifter_Sidebar_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class SmartToolz_Lifter_Sidebar_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register SmartToolz-LifterLMS Sidebar Customizer Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {
			$common_title                    = __( 'Sidebar Layout', 'smarttoolz' );
			$common_section                  = 'section-lifterlms';
			$common_lifter_lms_sidebar_style = __( 'Sidebar Style', 'smarttoolz' );
			$lifter_lms_section_divider      = true;

			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			if ( defined( 'SMARTTOOLZ_EXT_VER' ) && SmartToolz_Ext_Extension::is_active( 'lifterlms' ) ) {
				$section_general                        = 'section-lifterlms-general';
				$section_courses                        = 'section-lifterlms-course-lesson';
				$title_lifter_lms                       = $common_title;
				$title_lifter_lms_courses               = $common_title;
				$title_lifter_lms_sidebar_style         = $common_lifter_lms_sidebar_style;
				$title_lifter_lms_courses_sidebar_style = $common_lifter_lms_sidebar_style;
				$lifter_lms_section_divider             = false;
			} else {
				$section_general                        = $common_section;
				$section_courses                        = $common_section;
				$title_lifter_lms                       = __( 'Global Sidebar Layout', 'smarttoolz' );
				$title_lifter_lms_courses               = __( 'Course/Lesson Sidebar Layout', 'smarttoolz' );
				$title_lifter_lms_sidebar_style         = __( 'Global Sidebar Style', 'smarttoolz' );
				$title_lifter_lms_courses_sidebar_style = __( 'Course/Lesson Sidebar Style', 'smarttoolz' );
			}

			$_configs = array(

				/**
				 * Option: Global Sidebar Layout.
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[lifterlms-sidebar-layout]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'           => $section_general,
					'default'           => smarttoolz_get_option( 'lifterlms-sidebar-layout' ),
					'priority'          => 1,
					'title'             => $title_lifter_lms,
					'choices'           => array(
						'default'       => array(
							'label' => __( 'Default', 'smarttoolz' ),
							'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'layout-default', false ) : '',
						),
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
					'description'       => __( 'Sidebar will only apply when container layout is set to normal.', 'smarttoolz' ),
					'divider'           => array( 'ast_class' => 'ast-top-section-divider' ),
				),

				/**
				 * Option: LifterLMS Sidebar Style.
				 */
				array(
					'name'       => SMARTTOOLZ_THEME_SETTINGS . '[lifterlms-sidebar-style]',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => $section_general,
					'default'    => smarttoolz_get_option( 'lifterlms-sidebar-style', 'default' ),
					'priority'   => 1,
					'title'      => $title_lifter_lms_sidebar_style,
					'choices'    => array(
						'default' => __( 'Default', 'smarttoolz' ),
						'unboxed' => __( 'Unboxed', 'smarttoolz' ),
						'boxed'   => __( 'Boxed', 'smarttoolz' ),
					),
					'responsive' => false,
					'renderAs'   => 'text',
					'divider'    => array( 'ast_class' => 'ast-top-divider ast-top-spacing' ),
				),

				/**
				 * Option: Course/Lesson Sidebar Layout.
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[lifterlms-course-lesson-sidebar-layout]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'           => $section_courses,
					'default'           => smarttoolz_get_option( 'lifterlms-course-lesson-sidebar-layout' ),
					'priority'          => 1,
					'title'             => $title_lifter_lms_courses,
					'choices'           => array(
						'default'       => array(
							'label' => __( 'Default', 'smarttoolz' ),
							'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'layout-default', false ) : '',
						),
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
					'description'       => __( 'Sidebar will only apply when container layout is set to normal.', 'smarttoolz' ),
					'divider'           => $lifter_lms_section_divider ? array( 'ast_class' => 'ast-section-spacing ast-top-section-divider' ) : array( 'ast_class' => 'ast-section-spacing' ),
				),

				/**
				 * Option: Course/Lesson Sidebar Style.
				 */
				array(
					'name'       => SMARTTOOLZ_THEME_SETTINGS . '[lifterlms-course-lesson-sidebar-style]',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => $section_courses,
					'default'    => smarttoolz_get_option( 'lifterlms-course-lesson-sidebar-style', 'default' ),
					'priority'   => 1,
					'title'      => $title_lifter_lms_courses_sidebar_style,
					'choices'    => array(
						'default' => __( 'Default', 'smarttoolz' ),
						'unboxed' => __( 'Unboxed', 'smarttoolz' ),
						'boxed'   => __( 'Boxed', 'smarttoolz' ),
					),
					'responsive' => false,
					'renderAs'   => 'text',
					'divider'    => array( 'ast_class' => 'ast-top-divider ast-top-spacing' ),
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new SmartToolz_Lifter_Sidebar_Configs();
