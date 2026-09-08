<?php
/**
 * Register customizer panels & sections.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'SmartToolz_Customizer_Register_Sections_Panels' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class SmartToolz_Customizer_Register_Sections_Panels extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register Panels and Sections for Customizer.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$configs = array(

				/**
				 * Layout Panel
				 */

				array(
					'name'     => 'panel-global',
					'type'     => 'panel',
					'priority' => 10,
					'title'    => __( 'Global', 'smarttoolz' ),
				),

				array(
					'name'               => 'section-container-layout',
					'type'               => 'section',
					'priority'           => 17,
					'title'              => __( 'Container', 'smarttoolz' ),
					'panel'              => 'panel-global',
					'description_hidden' => true,
					'description'        => $this->section_get_description(
						array(
							'description' => '<p><b>' . __( 'Helpful Information', 'smarttoolz' ) . '</b></p>',
							'links'       => array(
								array(
									'text'  => __( 'Site Layout Overview', 'smarttoolz' ) . ' &#187;',
									'attrs' => array(
										'href' => smarttoolz_get_pro_url( '/docs/site-layout-overview/', 'free-theme', 'customizer', 'helpful_information' ),
									),
								),
								array(
									'text'  => __( 'Container Overview', 'smarttoolz' ) . ' &#187;',
									'attrs' => array(
										'href' => smarttoolz_get_pro_url( '/docs/container-overview/', 'free-theme', 'customizer', 'helpful_information' ),
									),
								),
							),
						)
					),
				),

				/*
				 * Header section
				 *
				 * @since 1.4.0
				 */
				array(
					'name'     => 'panel-header-group',
					'type'     => 'panel',
					'priority' => 20,
					'title'    => __( 'Header', 'smarttoolz' ),
				),

				/*
				 * Update the Site Identity section inside Layout -> Header
				 *
				 * @since 1.4.0
				 */
				array(
					'name'               => 'title_tagline',
					'type'               => 'section',
					'priority'           => 5,
					'title'              => __( 'Site Identity', 'smarttoolz' ),
					'panel'              => 'panel-header-group',
					'description_hidden' => true,
					'description'        => $this->section_get_description(
						array(
							'description' => '<p><b>' . __( 'Helpful Information', 'smarttoolz' ) . '</b></p>',
							'links'       => array(
								array(
									'text'  => __( 'Site Identity Overview', 'smarttoolz' ) . ' &#187;',
									'attrs' => array(
										'href' => smarttoolz_get_pro_url( '/docs/site-identity-free/', 'free-theme', 'customizer', 'helpful_information' ),
									),
								),
							),
						)
					),
				),

				/*
				 * Update the Primary Header section
				 *
				 * @since 1.4.0
				 */
				array(
					'name'               => 'section-header',
					'type'               => 'section',
					'priority'           => 15,
					'title'              => __( 'Primary Header', 'smarttoolz' ),
					'panel'              => 'panel-header-group',
					'description_hidden' => true,
					'description'        => $this->section_get_description(
						array(
							'description' => '<p><b>' . __( 'Helpful Information', 'smarttoolz' ) . '</b></p>',
							'links'       => array(
								array(
									'text'  => __( 'Primary Header Overview', 'smarttoolz' ) . ' &#187;',
									'attrs' => array(
										'href' => smarttoolz_get_pro_url( '/docs/header-overview/', 'free-theme', 'customizer', 'helpful_information' ),
									),
								),
							),
						)
					),
				),

				array(
					'name'     => 'section-primary-menu',
					'type'     => 'section',
					'priority' => 15,
					'title'    => __( 'Primary Menu', 'smarttoolz' ),
					'panel'    => 'panel-header-group',
				),
				array(
					'name'     => 'section-footer-group',
					'type'     => 'section',
					'title'    => __( 'Footer', 'smarttoolz' ),
					'priority' => 55,
				),

				array(
					'name'             => 'section-separator',
					'type'             => 'section',
					'ast_type'         => 'ast-section-separator',
					'priority'         => 70,
					'section_callback' => 'SmartToolz_WP_Customize_Separator',
				),

				/**
				 * Footer Widgets Section
				 */

				array(
					'name'     => 'section-footer-adv',
					'type'     => 'section',
					'title'    => __( 'Footer Widgets', 'smarttoolz' ),
					'section'  => 'section-footer-group',
					'priority' => 5,
				),

				array(
					'name'               => 'section-footer-small',
					'type'               => 'section',
					'title'              => __( 'Footer Bar', 'smarttoolz' ),
					'section'            => 'section-footer-group',
					'priority'           => 10,
					'description_hidden' => true,
					'description'        => $this->section_get_description(
						array(
							'description' => '<p><b>' . __( 'Helpful Information', 'smarttoolz' ) . '</b></p>',
							'links'       => array(
								array(
									'text'  => __( 'Footer Bar Overview', 'smarttoolz' ) . ' &#187;',
									'attrs' => array(
										'href' => smarttoolz_get_pro_url( '/docs/footer-bar/', 'free-theme', 'customizer', 'helpful_information' ),
									),
								),
							),
						)
					),
				),

				array(
					'name'     => 'section-blog-group',
					'type'     => 'section',
					'priority' => 20,
					'title'    => __( 'Post Types', 'smarttoolz' ),
				),
				array(
					'name'     => 'section-general-group',
					'type'     => 'section',
					'priority' => 20,
					'title'    => __( 'General', 'smarttoolz' ),
				),
				array(
					'name'     => 'section-blog',
					'type'     => 'section',
					'priority' => 5,
					'title'    => __( 'Blog / Archive', 'smarttoolz' ),
					'section'  => 'section-blog-group',
				),
				array(
					'name'     => 'section-blog-single',
					'type'     => 'section',
					'priority' => 10,
					'title'    => __( 'Single Post', 'smarttoolz' ),
					'section'  => 'section-blog-group',
				),

				array(
					'name'     => 'section-page-dynamic-group',
					'type'     => 'section',
					'priority' => 40,
					'title'    => __( 'Page', 'smarttoolz' ),
				),
				array(
					'name'     => 'section-single-page',
					'type'     => 'section',
					'priority' => 10,
					'title'    => __( 'Single Page', 'smarttoolz' ),
					'section'  => 'section-blog-group',
				),

				array(
					'name'               => 'section-sidebars',
					'type'               => 'section',
					'priority'           => 50,
					'title'              => __( 'Sidebar', 'smarttoolz' ),
					'description_hidden' => true,
					'section'            => 'section-general-group',
					'description'        => $this->section_get_description(
						array(
							'description' => '<p><b>' . __( 'Helpful Information', 'smarttoolz' ) . '</b></p>',
							'links'       => array(
								array(
									'text'  => __( 'Sidebar Overview', 'smarttoolz' ) . ' &#187;',
									'attrs' => array(
										'href' => smarttoolz_get_pro_url( '/docs/sidebar-free/', 'free-theme', 'customizer', 'helpful_information' ),
									),
								),
							),
						)
					),
				),

				/**
				 * Accessibility Panel
				 *
				 * @since 4.1.0
				 */
				array(
					'name'     => 'section-accessibility',
					'type'     => 'section',
					'priority' => 65,
					'title'    => __( 'Accessibility', 'smarttoolz' ),
					'section'  => 'section-general-group',
				),

				/**
				 * Colors Panel
				 */
				array(
					'name'               => 'section-colors-background',
					'type'               => 'section',
					'priority'           => 16,
					'title'              => __( 'Colors', 'smarttoolz' ),
					'description_hidden' => true,
					'description'        => $this->section_get_description(
						array(
							'description' => '<p><b>' . __( 'Helpful Information', 'smarttoolz' ) . '</b></p>',
							'links'       => array(
								array(
									'text'  => __( 'Colors & Background Overview', 'smarttoolz' ) . ' &#187;',
									'attrs' => array(
										'href' => smarttoolz_get_pro_url( '/docs/colors-background/', 'free-theme', 'customizer', 'helpful_information' ),
									),
								),
							),
						)
					),
					'panel'              => 'panel-global',
				),

				array(
					'name'     => 'section-colors-body',
					'type'     => 'section',
					'title'    => __( 'Base Colors', 'smarttoolz' ),
					'panel'    => 'panel-global',
					'priority' => 1,
					'section'  => 'section-colors-background',
				),

				array(
					'name'     => 'section-footer-adv-color-bg',
					'type'     => 'section',
					'title'    => __( 'Footer Widgets', 'smarttoolz' ),
					'panel'    => 'panel-colors-background',
					'priority' => 55,
				),

				/**
				 * Typography Panel
				 */
				array(
					'name'               => 'section-typography',
					'type'               => 'section',
					'title'              => __( 'Typography', 'smarttoolz' ),
					'priority'           => 15,
					'description_hidden' => true,
					'description'        => $this->section_get_description(
						array(
							'description' => '<p><b>' . __( 'Helpful Information', 'smarttoolz' ) . '</b></p>',
							'links'       => array(
								array(
									'text'  => __( 'Typography Overview', 'smarttoolz' ) . ' &#187;',
									'attrs' => array(
										'href' => smarttoolz_get_pro_url( '/docs/typography-free/', 'free-theme', 'customizer', 'helpful_information' ),
									),
								),
							),
						)
					),
					'panel'              => 'panel-global',
				),

				array(
					'name'     => 'section-body-typo',
					'type'     => 'section',
					'title'    => __( 'Base Typography', 'smarttoolz' ),
					'section'  => 'section-typography',
					'priority' => 1,
					'panel'    => 'panel-global',
				),

				array(
					'name'     => 'section-content-typo',
					'type'     => 'section',
					'title'    => __( 'Headings', 'smarttoolz' ),
					'section'  => 'section-typography',
					'priority' => 35,
					'panel'    => 'panel-global',
				),

				/**
				 * Buttons Section
				 */
				array(
					'name'     => 'section-buttons',
					'type'     => 'section',
					'priority' => 50,
					'title'    => __( 'Buttons', 'smarttoolz' ),
					'panel'    => 'panel-global',
				),

				/**
				 * Header Buttons
				 */
				array(
					'name'     => 'section-header-button',
					'type'     => 'section',
					'priority' => 10,
					'title'    => __( 'Header Button', 'smarttoolz' ),
					'section'  => 'section-buttons',
				),

				/**
				 * Header Button - Default
				 */
				array(
					'name'     => 'section-header-button-default',
					'type'     => 'section',
					'priority' => 10,
					'title'    => __( 'Primary Header Button', 'smarttoolz' ),
					'section'  => 'section-header-button',
				),

				/**
				 * Header Button - Transparent
				 */
				array(
					'name'     => 'section-header-button-transparent',
					'type'     => 'section',
					'priority' => 10,
					'title'    => __( 'Transparent Header Button', 'smarttoolz' ),
					'section'  => 'section-header-button',
				),

				/**
				 * Block Editor specific configs.
				 */
				array(
					'name'     => 'section-block-editor',
					'type'     => 'section',
					'priority' => 80,
					'title'    => __( 'Block Editor', 'smarttoolz' ),
					'section'  => 'section-general-group',
				),

				/**
				 * Global Misc specific configs.
				 */
				array(
					'name'     => 'section-global-misc',
					'type'     => 'section',
					'priority' => 80,
					'title'    => __( 'Misc', 'smarttoolz' ),
					'section'  => 'section-general-group',
				),

				/**
				 * Option: Scroll To Top
				 */
				array(
					'name'     => 'section-scroll-to-top',
					'title'    => __( 'Scroll To Top', 'smarttoolz' ),
					'type'     => 'section',
					'section'  => 'section-general-group',
					'priority' => 60,
				),
			);

			// Add spacial page section under page group.
			foreach ( SmartToolz_Posts_Structure_Loader::get_special_page_types() as $index => $special_type ) {
				$configs[] = array(
					'name'     => 'ast-section-' . $special_type . '-page',
					'type'     => 'section',
					'priority' => 10 + absint( $index ),
					'title'    => sprintf(
						/* translators: %s: Name of special page type */
						esc_html__( '%s Page', 'smarttoolz' ),
						ucfirst( $special_type )
					),
					'section'  => 'section-blog-group',
				);
			}

			return array_merge( $configurations, $configs );
		}
	}
}

/**
 * Kicking this off by calling 'get_instance()' method
 */
new SmartToolz_Customizer_Register_Sections_Panels();
