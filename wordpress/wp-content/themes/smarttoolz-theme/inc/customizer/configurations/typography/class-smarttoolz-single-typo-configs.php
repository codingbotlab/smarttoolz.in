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

if ( ! class_exists( 'SmartToolz_Single_Typo_Configs' ) ) {

	/**
	 * Customizer Single Typography Configurations.
	 *
	 * @since 1.4.3
	 */
	class SmartToolz_Single_Typo_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register Single Typography configurations.
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
					 * Option: SmartToolz Pro blog single post's options.
					 */
					array(
						'name'     => SMARTTOOLZ_THEME_SETTINGS . '[ast-single-post-items]',
						'type'     => 'control',
						'control'  => 'ast-upgrade',
						'campaign' => 'blog-single',
						'choices'  => array(
							'one'   => array(
								'title' => __( 'Author Box with Social Share', 'smarttoolz' ),
							),
							'two'   => array(
								'title' => __( 'Auto load previous posts', 'smarttoolz' ),
							),
							'three' => array(
								'title' => __( 'Single post navigation control', 'smarttoolz' ),
							),
							'four'  => array(
								'title' => __( 'Custom featured images size', 'smarttoolz' ),
							),
							'seven' => array(
								'title' => __( 'Single post read time', 'smarttoolz' ),
							),
							'five'  => array(
								'title' => __( 'Extended typography options', 'smarttoolz' ),
							),
							'six'   => array(
								'title' => __( 'Extended spacing options', 'smarttoolz' ),
							),
							'eight' => array(
								'title' => __( 'Social sharing options', 'smarttoolz' ),
							),
						),
						'section'  => 'section-blog-single',
						'default'  => '',
						'priority' => 999,
						'context'  => array(),
						'title'    => __( 'Extensive range of tools to help blog pages stand out.', 'smarttoolz' ),
						'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
					),
				);
			}

			return array_merge( $configurations, $_configs );
		}
	}
}

new SmartToolz_Single_Typo_Configs();
