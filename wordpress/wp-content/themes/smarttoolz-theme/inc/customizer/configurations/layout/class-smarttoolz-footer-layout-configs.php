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

if ( ! class_exists( 'SmartToolz_Footer_Layout_Configs' ) ) {

	/**
	 * Register Footer Layout Configurations.
	 */
	class SmartToolz_Footer_Layout_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register Footer Layout Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$_configs = array(

				/**
				 * Option: Footer Bar Layout
				 */

				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[footer-sml-layout]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_choices' ),
					'default'           => smarttoolz_get_option( 'footer-sml-layout' ),
					'section'           => 'section-footer-small',
					'priority'          => 5,
					'title'             => __( 'Layout', 'smarttoolz' ),
					'choices'           => array(
						'disabled'            => array(
							'label' => __( 'Disabled', 'smarttoolz' ),
							'path'  => SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'disabled' ),
						),
						'footer-sml-layout-1' => array(
							'label' => __( 'Footer Bar Layout 1', 'smarttoolz' ),
							'path'  => SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'footer-layout-1' ),
						),
						'footer-sml-layout-2' => array(
							'label' => __( 'Footer Bar Layout 2', 'smarttoolz' ),
							'path'  => SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'footer-layout-2' ),
						),
					),
					'partial'           => array(
						'selector'            => '.ast-small-footer',
						'container_inclusive' => false,
					),
				),

				/**
				 *  Section: Section 1
				 */
				array(
					'name'       => SMARTTOOLZ_THEME_SETTINGS . '[footer-sml-section-1]',
					'control'    => 'ast-selector',
					'default'    => smarttoolz_get_option( 'footer-sml-section-1' ),
					'type'       => 'control',
					'context'    => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[footer-sml-layout]',
							'operator' => '!=',
							'value'    => 'disabled',
						),
					),
					'section'    => 'section-footer-small',
					'priority'   => 15,
					'title'      => __( 'Section 1', 'smarttoolz' ),
					'divider'    => array( 'ast_class' => 'ast-top-divider' ),
					'choices'    => array(
						''       => __( 'None', 'smarttoolz' ),
						'custom' => __( 'Text', 'smarttoolz' ),
						'widget' => __( 'Widget', 'smarttoolz' ),
						'menu'   => __( 'Footer Menu', 'smarttoolz' ),
					),
					'partial'    => array(
						'selector'            => '.ast-small-footer .ast-container .ast-footer-widget-1-area .ast-no-widget-row, .ast-small-footer .ast-container .ast-small-footer-section-1 .footer-primary-navigation .nav-menu',
						'container_inclusive' => false,
					),
					'responsive' => false,
					'renderAs'   => 'text',
				),
				/**
				 * Option: Section 1 Custom Text
				 */
				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[footer-sml-section-1-credit]',
					'default'   => smarttoolz_get_option( 'footer-sml-section-1-credit' ),
					'type'      => 'control',
					'control'   => 'textarea',
					'transport' => 'postMessage',
					'section'   => 'section-footer-small',
					'context'   => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[footer-sml-section-1]',
							'operator' => '==',
							'value'    => array( 'custom' ),
						),
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[footer-sml-layout]',
							'operator' => '!=',
							'value'    => 'disabled',
						),
					),
					'priority'  => 20,
					'title'     => __( 'Section 1 Custom Text', 'smarttoolz' ),
					'choices'   => array(
						''       => __( 'None', 'smarttoolz' ),
						'custom' => __( 'Custom Text', 'smarttoolz' ),
						'widget' => __( 'Widget', 'smarttoolz' ),
						'menu'   => __( 'Footer Menu', 'smarttoolz' ),
					),
					'partial'   => array(
						'selector'            => '.ast-small-footer .ast-container .ast-small-footer-section.ast-small-footer-section-1:has(> .ast-footer-site-title)',
						'container_inclusive' => false,
						'render_callback'     => 'SmartToolz_Customizer_Partials::render_footer_sml_section_1_credit',
					),
				),

				/**
				 * Option: Section 2
				 */
				array(
					'name'       => SMARTTOOLZ_THEME_SETTINGS . '[footer-sml-section-2]',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'default'    => smarttoolz_get_option( 'footer-sml-section-2' ),
					'context'    => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[footer-sml-layout]',
							'operator' => '!=',
							'value'    => 'disabled',
						),
					),
					'section'    => 'section-footer-small',
					'priority'   => 25,
					'title'      => __( 'Section 2', 'smarttoolz' ),
					'choices'    => array(
						''       => __( 'None', 'smarttoolz' ),
						'custom' => __( 'Text', 'smarttoolz' ),
						'widget' => __( 'Widget', 'smarttoolz' ),
						'menu'   => __( 'Footer Menu', 'smarttoolz' ),
					),
					'partial'    => array(
						'selector'            => '.ast-small-footer .ast-container .ast-footer-widget-2-area .ast-no-widget-row, .ast-small-footer .ast-container .ast-small-footer-section-2 .footer-primary-navigation .nav-menu',
						'container_inclusive' => false,
					),
					'responsive' => false,
					'renderAs'   => 'text',
				),

				/**
				 * Option: Section 2 Custom Text
				 */
				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[footer-sml-section-2-credit]',
					'type'      => 'control',
					'control'   => 'textarea',
					'transport' => 'postMessage',
					'default'   => smarttoolz_get_option( 'footer-sml-section-2-credit' ),
					'section'   => 'section-footer-small',
					'priority'  => 30,
					'context'   => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[footer-sml-section-2]',
							'operator' => '==',
							'value'    => 'custom',
						),
					),
					'title'     => __( 'Section 2 Custom Text', 'smarttoolz' ),
					'partial'   => array(
						'selector'            => '.ast-small-footer-section-2',
						'container_inclusive' => false,
						'render_callback'     => 'SmartToolz_Customizer_Partials::render_footer_sml_section_2_credit',
					),
					'partial'   => array(
						'selector'            => '.ast-small-footer .ast-container .ast-small-footer-section.ast-small-footer-section-2:has(> .ast-footer-site-title)',
						'container_inclusive' => false,
					),
				),

				/**
				 * Option: Footer Top Border
				 */
				array(
					'name'        => SMARTTOOLZ_THEME_SETTINGS . '[footer-sml-divider]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'default'     => smarttoolz_get_option( 'footer-sml-divider' ),
					'section'     => 'section-footer-small',
					'priority'    => 40,
					'suffix'      => 'px',
					'context'     => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[footer-sml-layout]',
							'operator' => '!=',
							'value'    => 'disabled',
						),
					),
					'title'       => __( 'Border Size', 'smarttoolz' ),
					'transport'   => 'postMessage',
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 600,
					),
				),

				/**
				 * Option: Footer Top Border Color
				 */

				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[footer-sml-divider-color]',
					'section'           => 'section-footer-small',
					'default'           => smarttoolz_get_option( 'footer-sml-divider-color', '#7a7a7a' ),
					'type'              => 'control',
					'control'           => 'ast-color',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'divider'           => array( 'ast_class' => 'ast-bottom-divider' ),
					'context'           => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[footer-sml-divider]',
							'operator' => '>=',
							'value'    => 1,
						),
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[footer-sml-layout]',
							'operator' => '!=',
							'value'    => 'disabled',
						),
					),
					'priority'          => 45,
					'title'             => __( 'Border Color', 'smarttoolz' ),
					'transport'         => 'postMessage',
				),

				/**
				 * Option: Footer Bar Content Group
				 */
				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[footer-bar-background-group]',
					'default'   => smarttoolz_get_option( 'footer-bar-background-group' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Background Color', 'smarttoolz' ),
					'section'   => 'section-footer-small',
					'transport' => 'postMessage',
					'divider'   => array( 'ast_class' => 'ast-bottom-divider' ),
					'priority'  => 47,
					'context'   => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[footer-sml-layout]',
							'operator' => '!=',
							'value'    => 'disabled',
						),
					),
				),

				/**
				 * Option: Footer Bar Content Group
				 */
				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[footer-bar-content-group]',
					'default'   => smarttoolz_get_option( 'footer-bar-content-group' ),
					'type'      => 'control',
					'control'   => 'ast-settings-group',
					'title'     => __( 'Content Colors', 'smarttoolz' ),
					'section'   => 'section-footer-small',
					'transport' => 'postMessage',
					'priority'  => 47,
					'context'   => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[footer-sml-layout]',
							'operator' => '!=',
							'value'    => 'disabled',
						),
					),
				),

				/**
				 * Option: Footer Bar Content Group
				 */
				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[footer-bar-link-color-group]',
					'default'   => smarttoolz_get_option( 'footer-bar-link-color-group' ),
					'type'      => 'control',
					'control'   => 'ast-color-group',
					'title'     => __( 'Link Color', 'smarttoolz' ),
					'section'   => 'section-footer-small',
					'transport' => 'postMessage',
					'priority'  => 47,
					'divider'   => array( 'ast_class' => 'ast-bottom-divider' ),
					'context'   => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[footer-sml-layout]',
							'operator' => '!=',
							'value'    => 'disabled',
						),
					),
				),

				/**
				 * Option: Header Width
				 */
				array(
					'name'       => SMARTTOOLZ_THEME_SETTINGS . '[footer-layout-width]',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'default'    => smarttoolz_get_option( 'footer-layout-width' ),
					'section'    => 'section-footer-small',
					'divider'    => array( 'ast_class' => 'ast-top-divider ast-bottom-divider' ),
					'context'    => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[site-layout]',
							'operator' => '!=',
							'value'    => 'ast-box-layout',
						),
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[site-layout]',
							'operator' => '!=',
							'value'    => 'ast-fluid-width-layout',
						),
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[footer-sml-layout]',
							'operator' => '!=',
							'value'    => 'disabled',
						),
					),
					'priority'   => 35,
					'title'      => __( 'Width', 'smarttoolz' ),
					'choices'    => array(
						'full'    => __( 'Full Width', 'smarttoolz' ),
						'content' => __( 'Content Width', 'smarttoolz' ),
					),
					'responsive' => false,
					'renderAs'   => 'text',
				),

				/**
				 * Option: Footer Top Border
				 */
				array(
					'name'        => SMARTTOOLZ_THEME_SETTINGS . '[footer-adv-border-width]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'transport'   => 'postMessage',
					'section'     => 'section-footer-adv',
					'default'     => smarttoolz_get_option( 'footer-adv-border-width' ),
					'priority'    => 40,
					'context'     => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[footer-adv]',
							'operator' => '!=',
							'value'    => 'disabled',
						),
					),
					'suffix'      => 'px',
					'title'       => __( 'Top Border Size', 'smarttoolz' ),
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 600,
					),
				),

				/**
				 * Option: Footer Top Border Color
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[footer-adv-border-color]',
					'section'           => 'section-footer-adv',
					'title'             => __( 'Top Border Color', 'smarttoolz' ),
					'type'              => 'control',
					'transport'         => 'postMessage',
					'control'           => 'ast-color',
					'divider'           => array( 'ast_class' => 'ast-bottom-divider' ),
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
					'default'           => smarttoolz_get_option( 'footer-adv-border-color' ),
					'context'           => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[footer-adv]',
							'operator' => '!=',
							'value'    => 'disabled',
						),
					),
					'priority'          => 45,
				),
			);

			$configurations = array_merge( $configurations, $_configs );

			// Learn More link if SmartToolz Pro is not activated.
			if ( ! defined( 'SMARTTOOLZ_EXT_VER' ) || ( defined( 'SMARTTOOLZ_EXT_VER' ) && false === SmartToolz_Ext_Extension::is_active( 'advanced-footer' ) ) ) {

				$config = array(

					/**
					 * Option: Footer Widgets Layout Layout
					 */
					array(
						'name'              => SMARTTOOLZ_THEME_SETTINGS . '[footer-adv]',
						'type'              => 'control',
						'priority'          => 0,
						'control'           => 'ast-radio-image',
						'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_choices' ),
						'default'           => smarttoolz_get_option( 'footer-adv' ),
						'title'             => __( 'Layout', 'smarttoolz' ),
						'section'           => 'section-footer-adv',
						'divider'           => array( 'ast_class' => 'ast-bottom-divider' ),
						'choices'           => array(
							'disabled' => array(
								'label' => __( 'Disable', 'smarttoolz' ),
								'path'  => SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'disabled' ),
							),
							'layout-4' => array(
								'label' => __( 'Layout 4', 'smarttoolz' ),
								'path'  => SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'footer-layout-4' ),
							),
						),
						'partial'           => array(
							'selector'            => '.footer-adv .ast-container',
							'container_inclusive' => false,
						),
					),

					/**
					 * Option: Learn More about Footer Widget
					 */
					array(
						'name'     => SMARTTOOLZ_THEME_SETTINGS . '[ast-footer-widget-more-feature-description]',
						'type'     => 'control',
						'control'  => 'ast-description',
						'section'  => 'section-footer-adv',
						'priority' => 999,
						'label'    => '',
						'help'     => '<span>' . __( 'More Options Available in Toolkit', 'smarttoolz' ) . '</span><a style="display: block;" href="' . smarttoolz_get_upgrade_url( 'customizer' ) . '" class="button button-secondary"  target="_blank" rel="noopener">' . __( 'Learn More', 'smarttoolz' ) . '</a>',
						'settings' => array(),
					),

				);

				$configurations = array_merge( $configurations, $config );
			}

			return $configurations;
		}
	}
}

new SmartToolz_Footer_Layout_Configs();
