<?php
/**
 * Styling Options for SmartToolz Theme.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 1.0.15
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SmartToolz_Archive_Typo_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class SmartToolz_Archive_Typo_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register Archive Typography Customizer Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array();

			// Learn More link if SmartToolz Pro is not activated.
			if ( smarttoolz_showcase_upgrade_notices() ) {

				$_configs = array(

					/**
					 * Option: SmartToolz Pro items for blog pro.
					 */
					array(
						'name'     => SMARTTOOLZ_THEME_SETTINGS . '[ast-blog-pro-items]',
						'type'     => 'control',
						'control'  => 'ast-upgrade',
						'campaign' => 'blog-archive',
						'choices'  => array(
							'one'    => array(
								'title' => __( 'Posts Filter', 'smarttoolz' ),
							),
							'eleven' => array(
								'title' => __( 'Posts Reveal Effect', 'smarttoolz' ),
							),
							'two'    => array(
								'title' => __( 'Grid, Masonry layout', 'smarttoolz' ),
							),
							'twelve' => array(
								'title' => __( 'Extended Meta Options', 'smarttoolz' ),
							),
							'three'  => array(
								'title' => __( 'Custom image size', 'smarttoolz' ),
							),
							'four'   => array(
								'title' => __( 'Archive pagination', 'smarttoolz' ),
							),
							'six'    => array(
								'title' => __( 'Extended typography', 'smarttoolz' ),
							),
							'seven'  => array(
								'title' => __( 'Extended spacing', 'smarttoolz' ),
							),
							'eight'  => array(
								'title' => __( 'Archive read time', 'smarttoolz' ),
							),
							'nine'   => array(
								'title' => __( 'Archive excerpt', 'smarttoolz' ),
							),
						),
						'section'  => 'section-blog',
						'default'  => '',
						'priority' => 999,
						'context'  => array(),
						'title'    => __( 'Take your blog to the next level with powerful design features.', 'smarttoolz' ),
						'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
					),
				);
			}

			if ( ! defined( 'SMARTTOOLZ_EXT_VER' ) || ( defined( 'SMARTTOOLZ_EXT_VER' ) && ! SmartToolz_Ext_Extension::is_active( 'typography' ) ) ) {
				$new_configs = array(
					/**
					 * Option: Blog - Post Title Font Size
					 */
					array(
						'name'              => SMARTTOOLZ_THEME_SETTINGS . '[font-size-page-title]',
						'control'           => 'ast-responsive-slider',
						'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
						'section'           => 'section-blog',
						'type'              => 'control',
						'transport'         => 'postMessage',
						'title'             => __( 'Post Title Size', 'smarttoolz' ),
						'priority'          => 140,
						'default'           => smarttoolz_get_option( 'font-size-page-title' ),
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
						'context'           => SmartToolz_Builder_Helper::$design_tab,
						'divider'           => array( 'ast_class' => 'ast-top-section-divider' ),
					),
					array(
						'name'              => SMARTTOOLZ_THEME_SETTINGS . '[font-size-post-meta]',
						'control'           => 'ast-responsive-slider',
						'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
						'section'           => 'section-blog',
						'type'              => 'control',
						'transport'         => 'postMessage',
						'title'             => __( 'Meta Font Size', 'smarttoolz' ),
						'is_font'           => true,
						'priority'          => 140,
						'default'           => smarttoolz_get_option( 'font-size-post-meta' ),
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
						'context'           => SmartToolz_Builder_Helper::$design_tab,
						'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
					),
					array(
						'name'              => SMARTTOOLZ_THEME_SETTINGS . '[font-size-post-tax]',
						'control'           => 'ast-responsive-slider',
						'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
						'section'           => 'section-blog',
						'type'              => 'control',
						'transport'         => 'postMessage',
						'title'             => __( 'Taxonomy Font', 'smarttoolz' ),
						'is_font'           => true,
						'priority'          => 140,
						'default'           => smarttoolz_get_option( 'font-size-post-tax' ),
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
						'context'           => array(
							SmartToolz_Builder_Helper::$design_tab_config,
							array(
								'relation' => 'OR',
								array(
									'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[blog-post-structure]',
									'operator' => 'contains',
									'value'    => 'category',
								),
								array(
									'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[blog-post-structure]',
									'operator' => 'contains',
									'value'    => 'tag',
								),
								array(
									'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[blog-meta]',
									'operator' => 'contains',
									'value'    => 'category',
								),
								array(
									'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[blog-meta]',
									'operator' => 'contains',
									'value'    => 'tag',
								),
							),
						),
						'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
					),
				);
				$_configs    = array_merge( $_configs, $new_configs );
			}

			return array_merge( $configurations, $_configs );
		}
	}
}

new SmartToolz_Archive_Typo_Configs();
