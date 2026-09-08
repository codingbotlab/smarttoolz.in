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

if ( ! class_exists( 'SmartToolz_Blog_Single_Layout_Configs' ) ) {

	/**
	 * Register Blog Single Layout Configurations.
	 */
	class SmartToolz_Blog_Single_Layout_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register Blog Single Layout Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			/** @psalm-suppress DocblockTypeContradiction */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$tab_config = SmartToolz_Builder_Helper::$design_tab;

			$_configs = array(

				/**
				 * Option: Single Post Content Width
				 */
				array(
					'name'       => SMARTTOOLZ_THEME_SETTINGS . '[blog-single-width]',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => 'section-blog-single',
					'default'    => smarttoolz_get_option( 'blog-single-width' ),
					'priority'   => 6,
					'title'      => __( 'Content Width', 'smarttoolz' ),
					'choices'    => array(
						'default' => __( 'Default', 'smarttoolz' ),
						'custom'  => __( 'Custom', 'smarttoolz' ),
					),
					'transport'  => 'postMessage',
					'responsive' => false,
					'divider'    => array( 'ast_class' => 'ast-top-section-divider' ),
					'renderAs'   => 'text',
				),

				/**
				 * Option: Enter Width
				 */
				array(
					'name'        => SMARTTOOLZ_THEME_SETTINGS . '[blog-single-max-width]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'section'     => 'section-blog-single',
					'transport'   => 'postMessage',
					'default'     => smarttoolz_get_option( 'blog-single-max-width' ),
					'context'     => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[blog-single-width]',
							'operator' => '===',
							'value'    => 'custom',
						),
					),
					'priority'    => 6,
					'title'       => __( 'Custom Width', 'smarttoolz' ),
					'suffix'      => 'px',
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 1920,
					),
					'divider'     => array( 'ast_class' => 'ast-top-divider' ),
				),

				/**
				 * Option: Content images shadow
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[single-content-images-shadow]',
					'default'  => smarttoolz_get_option( 'single-content-images-shadow' ),
					'type'     => 'control',
					'section'  => 'section-blog-single',
					'title'    => __( 'Content Images Box Shadow', 'smarttoolz' ),
					'control'  => 'ast-toggle-control',
					'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
					'priority' => 9,
					'context'  => SmartToolz_Builder_Helper::$general_tab,
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[section-blog-single-spacing-divider]',
					'section'  => 'section-blog-single',
					'title'    => __( 'Post Spacing', 'smarttoolz' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 24,
					'context'  => $tab_config,
				),

				/**
				 * Option: Single Post Spacing
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[single-post-outside-spacing]',
					'default'           => smarttoolz_get_option( 'single-post-outside-spacing' ),
					'type'              => 'control',
					'control'           => 'ast-responsive-spacing',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
					'section'           => 'section-blog-single',
					'title'             => __( 'Outside', 'smarttoolz' ),
					'linked_choices'    => true,
					'unit_choices'      => array( 'px', 'em', '%' ),
					'choices'           => array(
						'top'    => __( 'Top', 'smarttoolz' ),
						'right'  => __( 'Right', 'smarttoolz' ),
						'bottom' => __( 'Bottom', 'smarttoolz' ),
						'left'   => __( 'Left', 'smarttoolz' ),
					),
					'priority'          => 25,
					'context'           => $tab_config,
					'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
				),

				/**
				 * Option: Single Post Margin
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[single-post-inside-spacing]',
					'default'           => smarttoolz_get_option( 'single-post-inside-spacing' ),
					'type'              => 'control',
					'control'           => 'ast-responsive-spacing',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
					'section'           => 'section-blog-single',
					'title'             => __( 'Inside', 'smarttoolz' ),
					'linked_choices'    => true,
					'transport'         => 'refresh',
					'unit_choices'      => array( 'px', 'em', '%' ),
					'choices'           => array(
						'top'    => __( 'Top', 'smarttoolz' ),
						'right'  => __( 'Right', 'smarttoolz' ),
						'bottom' => __( 'Bottom', 'smarttoolz' ),
						'left'   => __( 'Left', 'smarttoolz' ),
					),
					'priority'          => 30,
					'divider'           => array( 'ast_class' => 'ast-top-divider' ),
					'context'           => $tab_config,
				),
			);

			$_configs[] = array(
				'name'        => 'section-blog-single-ast-context-tabs',
				'section'     => 'section-blog-single',
				'type'        => 'control',
				'control'     => 'ast-builder-header-control',
				'priority'    => 0,
				'description' => '',
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

new SmartToolz_Blog_Single_Layout_Configs();
