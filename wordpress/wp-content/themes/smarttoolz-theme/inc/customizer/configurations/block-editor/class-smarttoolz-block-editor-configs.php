<?php
/**
 * Entry Content options for SmartToolz Theme.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 3.8.0
 */

if ( ! class_exists( 'SmartToolz_Block_Editor_Configs' ) ) {

	/**
	 * Register Site Layout Customizer Configurations.
	 */
	class SmartToolz_Block_Editor_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register Site Layout Customizer Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 3.8.0
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$is_legacy_setup = 'legacy' === smarttoolz_get_option( 'wp-blocks-ui', 'comfort' ) || true === smarttoolz_get_option( 'blocks-legacy-setup', false ) ? true : false;

			$preset_options = array(
				'compact' => __( 'Compact', 'smarttoolz' ),
				'comfort' => __( 'Comfort', 'smarttoolz' ),
				'custom'  => __( 'Custom', 'smarttoolz' ),
			);
			if ( $is_legacy_setup ) {
				$preset_options = array(
					'legacy'  => __( 'Legacy', 'smarttoolz' ),
					'compact' => __( 'Compact', 'smarttoolz' ),
					'comfort' => __( 'Comfort', 'smarttoolz' ),
					'custom'  => __( 'Custom', 'smarttoolz' ),
				);
			}

			$_configs = array(
				/**
				 * Option: Presets for block editor padding.
				 */
				array(
					'name'       => SMARTTOOLZ_THEME_SETTINGS . '[wp-blocks-ui]',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => 'section-block-editor',
					'default'    => smarttoolz_get_option( 'wp-blocks-ui' ),
					'priority'   => 9,
					'title'      => __( 'Core Blocks Spacing', 'smarttoolz' ),
					'choices'    => $preset_options,
					'responsive' => false,
					'renderAs'   => 'text',
				),

				/**
				 * Option: Global Padding Option.
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[wp-blocks-global-padding]',
					'section'           => 'section-block-editor',
					'title'             => __( 'Size', 'smarttoolz' ),
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
					'default'           => smarttoolz_get_option( 'wp-blocks-global-padding' ),
					'type'              => 'control',
					'control'           => 'ast-responsive-spacing',
					'choices'           => array(
						'top'    => __( 'Top', 'smarttoolz' ),
						'right'  => __( 'Right', 'smarttoolz' ),
						'bottom' => __( 'Bottom', 'smarttoolz' ),
						'left'   => __( 'Left', 'smarttoolz' ),
					),
					'linked_choices'    => true,
					'priority'          => 10,
					'unit_choices'      => array( 'px', 'em', '%' ),
					'context'           => array(
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[wp-blocks-ui]',
							'operator' => '===',
							'value'    => 'custom',
						),
					),
					'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
				),
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[wp-blocks-ui-description]',
					'type'     => 'control',
					'control'  => 'ast-description',
					'section'  => 'section-block-editor',
					'priority' => 10,
					'help'     => '<span style="margin-top: -5px;">' . __( 'Global padding setting for WordPress Group, Column, Cover blocks, it can be overridden by respective block\'s Dimension setting.', 'smarttoolz' ) . '</span>',
					'settings' => array(),
				),
			);

			return array_merge( $configurations, $_configs );
		}
	}
}

/**
 * Kicking this off by creating new instance.
 */
new SmartToolz_Block_Editor_Configs();
