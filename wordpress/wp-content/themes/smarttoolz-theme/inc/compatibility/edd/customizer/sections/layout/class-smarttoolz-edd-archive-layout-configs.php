<?php
/**
 * Easy Digital Downloads Options for SmartToolz Theme.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 1.5.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'SmartToolz_Edd_Archive_Layout_Configs' ) ) {

	/**
	 * Customizer Sanitizes Initial setup
	 */
	class SmartToolz_Edd_Archive_Layout_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register SmartToolz-Easy Digital Downloads Shop Layout Customizer Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.5.5
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$grid_ast_divider = defined( 'SMARTTOOLZ_EXT_VER' ) && SmartToolz_Ext_Extension::is_active( 'edd' ) ? array() : array( 'ast_class' => 'ast-top-section-divider' );
			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

			$_configs = array(

				/**
				 * Option: Shop Columns
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[edd-archive-grids]',
					'type'              => 'control',
					'control'           => 'ast-responsive-slider',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
					'section'           => 'section-edd-archive',
					'default'           => smarttoolz_get_option(
						'edd-archive-grids',
						array(
							'desktop' => 4,
							'tablet'  => 3,
							'mobile'  => 2,
						)
					),
					'priority'          => 10,
					'title'             => __( 'Archive Columns', 'smarttoolz' ),
					'input_attrs'       => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 6,
					),
					'divider'           => $grid_ast_divider,
					'transport'         => 'postMessage',
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[edd-archive-product-structure-divider]',
					'section'  => 'section-edd-archive',
					'title'    => __( 'Product Structure', 'smarttoolz' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 30,
					'settings' => array(),
					'divider'  => array( 'ast_class' => 'ast-section-spacing' ),
				),

				/**
				 * Option: EDD Archive Post Meta
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[edd-archive-product-structure]',
					'type'              => 'control',
					'control'           => 'ast-sortable',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_multi_choices' ),
					'section'           => 'section-edd-archive',
					'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
					'default'           => smarttoolz_get_option( 'edd-archive-product-structure' ),
					'priority'          => 30,
					'title'             => __( 'Product Structure', 'smarttoolz' ),
					'description'       => __( 'The Image option cannot be sortable if the Product Style is selected to the List Style ', 'smarttoolz' ),
					'choices'           => array(
						'image'      => __( 'Image', 'smarttoolz' ),
						'category'   => __( 'Category', 'smarttoolz' ),
						'title'      => __( 'Title', 'smarttoolz' ),
						'price'      => __( 'Price', 'smarttoolz' ),
						'short_desc' => __( 'Short Description', 'smarttoolz' ),
						'add_cart'   => __( 'Add To Cart', 'smarttoolz' ),
					),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[edd-archive-button-divider]',
					'section'  => 'section-edd-archive',
					'title'    => __( 'Buttons', 'smarttoolz' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 31,
					'settings' => array(),
					'divider'  => array( 'ast_class' => 'ast-section-spacing ast-bottom-spacing' ),
				),

				/**
				 * Option: Add to Cart button text
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[edd-archive-add-to-cart-button-text]',
					'type'     => 'control',
					'control'  => 'text',
					'section'  => 'section-edd-archive',
					'default'  => smarttoolz_get_option( 'edd-archive-add-to-cart-button-text' ),
					'priority' => 31,
					'title'    => __( 'Cart Button Text', 'smarttoolz' ),
					'context'  => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[edd-archive-product-structure]',
							'operator' => 'contains',
							'value'    => 'add_cart',
						),
					),
					'divider'  => array( 'ast_class' => 'ast-top-spacing ast-bottom-section-divider' ),
				),

				/**
				 * Option: Variable product button
				 */

				array(
					'name'       => SMARTTOOLZ_THEME_SETTINGS . '[edd-archive-variable-button]',
					'default'    => smarttoolz_get_option( 'edd-archive-variable-button' ),
					'section'    => 'section-edd-archive',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'title'      => __( 'Variable Product Button', 'smarttoolz' ),
					'priority'   => 31,
					'choices'    => array(
						'button'  => __( 'Button', 'smarttoolz' ),
						'options' => __( 'Options', 'smarttoolz' ),
					),
					'transport'  => 'refresh',
					'renderAs'   => 'text',
					'responsive' => false,
					'context'    => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[edd-archive-product-structure]',
							'operator' => 'contains',
							'value'    => 'add_cart',
						),
					),
					'divider'    => array( 'ast_class' => 'ast-top-section-divider' ),
				),

				/**
				 * Option: Variable product button text
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[edd-archive-variable-button-text]',
					'type'     => 'control',
					'control'  => 'text',
					'divider'  => array( 'ast_class' => 'ast-bottom-divider' ),
					'section'  => 'section-edd-archive',
					'default'  => smarttoolz_get_option( 'edd-archive-variable-button-text' ),
					'context'  => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[edd-archive-variable-button]',
							'operator' => '==',
							'value'    => 'button',
						),
					),
					'priority' => 31,
					'title'    => __( 'Variable Product Button Text', 'smarttoolz' ),
				),

				/**
				 * Option: Archive Content Width
				 */
				array(
					'name'       => SMARTTOOLZ_THEME_SETTINGS . '[edd-archive-width]',
					'default'    => smarttoolz_get_option( 'edd-archive-width' ),
					'section'    => 'section-edd-archive',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'title'      => __( 'Archive Content Width', 'smarttoolz' ),
					'divider'    => array( 'ast_class' => 'ast-top-section-divider' ),
					'priority'   => 220,
					'choices'    => array(
						'default' => __( 'Default', 'smarttoolz' ),
						'custom'  => __( 'Custom', 'smarttoolz' ),
					),
					'transport'  => 'postMessage',
					'renderAs'   => 'text',
					'responsive' => false,
				),

				/**
				 * Option: Enter Width
				 */
				array(
					'name'        => SMARTTOOLZ_THEME_SETTINGS . '[edd-archive-max-width]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'section'     => 'section-edd-archive',
					'default'     => smarttoolz_get_option( 'edd-archive-max-width' ),
					'priority'    => 225,
					'context'     => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[edd-archive-width]',
							'operator' => '===',
							'value'    => 'custom',
						),
					),

					'title'       => __( 'Custom Width', 'smarttoolz' ),
					'transport'   => 'postMessage',
					'suffix'      => 'px',
					'input_attrs' => array(
						'min'  => 768,
						'step' => 1,
						'max'  => 1920,
					),
					'divider'     => array( 'ast_class' => 'ast-top-divider' ),
				),
			);

			// Upgrade nudge if SmartToolz Pro is not activated.
			if ( smarttoolz_showcase_upgrade_notices() ) {
				$_configs[] = SmartToolz_Customizer_Register_Edd_Section::get_upgrade_nudge_config( 'ast-edd-pro-items', 'section-edd-archive' );
			}

			return array_merge( $configurations, $_configs );
		}
	}
}

new SmartToolz_Edd_Archive_Layout_Configs();
