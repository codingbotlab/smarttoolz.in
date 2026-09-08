<?php
/**
 * Comments options for SmartToolz Theme.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 4.6.0
 */

if ( ! class_exists( 'SmartToolz_Comments_Configs' ) ) {

	/**
	 * Register Comments Customizer Configurations.
	 */
	class SmartToolz_Comments_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register Comments Customizer Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 3.8.0
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {
			$parent_section = 'section-blog-single';
			$_configs       = array(
				array(
					'name'        => 'comments-section-ast-context-tabs',
					'section'     => 'ast-sub-section-comments',
					'type'        => 'control',
					'control'     => 'ast-builder-header-control',
					'priority'    => 0,
					'description' => '',
					'context'     => array(),
				),
				array(
					'name'     => 'ast-sub-section-comments',
					'title'    => __( 'Comments', 'smarttoolz' ),
					'type'     => 'section',
					'section'  => $parent_section,
					'panel'    => '',
					'priority' => 1,
				),
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[comments-single-section-heading]',
					'section'  => $parent_section,
					'type'     => 'control',
					'control'  => 'ast-heading',
					'title'    => __( 'Comments', 'smarttoolz' ),
					'priority' => 20,
					'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
				),
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[enable-comments-area]',
					'type'     => 'control',
					'default'  => smarttoolz_get_option( 'enable-comments-area' ),
					'control'  => 'ast-section-toggle',
					'section'  => $parent_section,
					'priority' => 20,
					'linked'   => 'ast-sub-section-comments',
					'linkText' => __( 'Comments', 'smarttoolz' ),
					'divider'  => array( 'ast_class' => 'ast-bottom-divider ast-bottom-section-divider' ),
				),
				array(
					'name'        => SMARTTOOLZ_THEME_SETTINGS . '[comments-box-placement]',
					'default'     => smarttoolz_get_option( 'comments-box-placement' ),
					'type'        => 'control',
					'section'     => 'ast-sub-section-comments',
					'priority'    => 20,
					'title'       => __( 'Section Placement', 'smarttoolz' ),
					'control'     => 'ast-selector',
					'description' => __( 'Decide whether to isolate or integrate the module with the entry content area.', 'smarttoolz' ),
					'choices'     => array(
						''        => __( 'Default', 'smarttoolz' ),
						'inside'  => __( 'Contained', 'smarttoolz' ),
						'outside' => __( 'Separated', 'smarttoolz' ),
					),
					'divider'     => array( 'ast_class' => 'ast-section-spacing' ),
					'context'     => SmartToolz_Builder_Helper::$general_tab,
					'responsive'  => false,
					'renderAs'    => 'text',
				),
				array(
					'name'       => SMARTTOOLZ_THEME_SETTINGS . '[comments-box-container-width]',
					'default'    => smarttoolz_get_option( 'comments-box-container-width' ),
					'type'       => 'control',
					'section'    => 'ast-sub-section-comments',
					'priority'   => 20,
					'title'      => __( 'Container Structure', 'smarttoolz' ),
					'control'    => 'ast-selector',
					'choices'    => array(
						'narrow' => __( 'Narrow', 'smarttoolz' ),
						''       => __( 'Full Width', 'smarttoolz' ),
					),
					'context'    => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[comments-box-placement]',
							'operator' => '==',
							'value'    => 'outside',
						),
					),
					'divider'    => array( 'ast_class' => 'ast-top-section-spacing' ),
					'responsive' => false,
					'renderAs'   => 'text',
				),
				array(
					'name'       => SMARTTOOLZ_THEME_SETTINGS . '[comment-form-position]',
					'default'    => smarttoolz_get_option( 'comment-form-position' ),
					'type'       => 'control',
					'section'    => 'ast-sub-section-comments',
					'priority'   => 20,
					'title'      => __( 'Form Position', 'smarttoolz' ),
					'control'    => 'ast-selector',
					'choices'    => array(
						'below' => __( 'Below Comments', 'smarttoolz' ),
						'above' => __( 'Above Comments', 'smarttoolz' ),
					),
					'context'    => SmartToolz_Builder_Helper::$general_tab,
					'divider'    => array( 'ast_class' => 'ast-top-section-divider' ),
					'responsive' => false,
					'renderAs'   => 'text',
				),
			);

			$_configs = array_merge( $_configs, SmartToolz_Extended_Base_Configuration::prepare_section_spacing_border_options( 'ast-sub-section-comments', true ) );

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by creating new instance.
 */
new SmartToolz_Comments_Configs();
