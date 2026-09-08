<?php
/**
 * Account Header Configuration.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register account header builder Customizer Configurations.
 *
 * @since 4.5.2
 * @return array SmartToolz Customizer Configurations with updated configurations.
 */
function smarttoolz_header_account_configuration() {
	$_section = 'section-header-account';

	$account_choices = array(
		'default' => __( 'Default', 'smarttoolz' ),
	);

	$login_link_context = SmartToolz_Builder_Helper::$general_tab;

	$logout_link_context = array(
		'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-account-logout-style]',
		'operator' => '!=',
		'value'    => 'none',
	);

	if ( defined( 'SMARTTOOLZ_EXT_VER' ) ) {

		$account_type_condition = array(
			'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-account-action-type]',
			'operator' => '==',
			'value'    => 'link',
		);

		if ( class_exists( 'LifterLMS' ) ) {
			$account_choices['lifterlms'] = __( 'LifterLMS', 'smarttoolz' );
		}

		if ( class_exists( 'WooCommerce' ) ) {
			$account_choices['woocommerce'] = __( 'WooCommerce', 'smarttoolz' );
		}

		if ( count( $account_choices ) > 1 ) {
			$account_type_condition = array(
				'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-account-type]',
				'operator' => '==',
				'value'    => 'default',
			);
		}

		$login_link_context = array(
			'relation' => 'AND',
			SmartToolz_Builder_Helper::$general_tab_config,
			array(
				'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-account-action-type]',
				'operator' => '==',
				'value'    => 'link',
			),
			array(
				'relation' => 'OR',
				$account_type_condition,
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-account-link-type]',
					'operator' => '==',
					'value'    => 'custom',
				),
			),
		);

		$logout_link_context = array(
			'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-account-logout-action]',
			'operator' => '==',
			'value'    => 'link',
		);

	}

	$_configs = array(

		/*
		* Header Builder section
		*/
		array(
			'name'     => $_section,
			'type'     => 'section',
			'priority' => 80,
			'title'    => __( 'Account', 'smarttoolz' ),
			'panel'    => 'panel-header-builder-group',
		),

		/**
		 * Option: Header Builder Tabs
		 */
		array(
			'name'        => SMARTTOOLZ_THEME_SETTINGS . '[header-account-tabs]',
			'section'     => $_section,
			'type'        => 'control',
			'control'     => 'ast-builder-header-control',
			'priority'    => 0,
			'description' => '',
			'divider'     => array( 'ast_class' => 'ast-bottom-spacing' ),
		),

		/**
		 * Option: Log In view
		 */
		array(
			'name'        => SMARTTOOLZ_THEME_SETTINGS . '[header-account-login-heading]',
			'type'        => 'control',
			'control'     => 'ast-heading',
			'section'     => $_section,
			'priority'    => 1,
			'title'       => __( 'Logged In View', 'smarttoolz' ),
			'settings'    => array(),
			'input_attrs' => array(
				'class' => 'ast-control-reduce-top-space',
			),
			'divider'     => array( 'ast_class' => 'ast-section-spacing' ),
		),

		/**
		 * Option: Style
		 */
		array(
			'name'       => SMARTTOOLZ_THEME_SETTINGS . '[header-account-login-style]',
			'default'    => smarttoolz_get_option( 'header-account-login-style' ),
			'type'       => 'control',
			'control'    => 'ast-selector',
			'section'    => $_section,
			'priority'   => 3,
			'title'      => __( 'Profile Type', 'smarttoolz' ),
			'choices'    => array(
				'icon'   => __( 'Icon', 'smarttoolz' ),
				'avatar' => __( 'Avatar', 'smarttoolz' ),
				'text'   => __( 'Text', 'smarttoolz' ),
			),
			'transport'  => 'postMessage',
			'partial'    => array(
				'selector'        => '.ast-header-account',
				'render_callback' => array( 'SmartToolz_Builder_UI_Controller', 'render_account' ),
			),
			'responsive' => false,
			'renderAs'   => 'text',
			'divider'    => array( 'ast_class' => 'ast-bottom-divider' ),
		),

		/**
		 * Option: Show Text with
		 *
		 * @since 4.6.15
		 */
		array(
			'name'        => SMARTTOOLZ_THEME_SETTINGS . '[header-account-login-style-extend-text-profile-type]',
			'default'     => smarttoolz_get_option( 'header-account-login-style-extend-text-profile-type' ),
			'type'        => 'control',
			'control'     => 'ast-selector',
			'section'     => $_section,
			'priority'    => 3,
			'description' => __( 'Choose if you want to display Icon or Avatar with the Text selected Profile Type.', 'smarttoolz' ),
			'title'       => __( 'Show Text with', 'smarttoolz' ),
			'choices'     => array(
				'default' => __( 'Default', 'smarttoolz' ),
				'avatar'  => __( 'Avatar', 'smarttoolz' ),
				'icon'    => __( 'Icon', 'smarttoolz' ),
			),
			'transport'   => 'postMessage',
			'partial'     => array(
				'selector'        => '.ast-header-account',
				'render_callback' => array( 'SmartToolz_Builder_UI_Controller', 'render_account' ),
			),
			'responsive'  => false,
			'renderAs'    => 'text',
			'divider'     => array( 'ast_class' => 'ast-bottom-divider ast-section-spacing' ),
			'context'     => array(
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-account-login-style]',
					'operator' => '==',
					'value'    => 'text',
				),
				SmartToolz_Builder_Helper::$general_tab_config,
			),
		),

		/**
		 * Option: Logged Out Text
		 */
		array(
			'name'      => SMARTTOOLZ_THEME_SETTINGS . '[header-account-logged-in-text]',
			'default'   => smarttoolz_get_option( 'header-account-logged-in-text' ),
			'type'      => 'control',
			'control'   => 'ast-text-input',
			'section'   => $_section,
			'title'     => __( 'Text', 'smarttoolz' ),
			'priority'  => 3,
			'transport' => 'postMessage',
			'context'   => array(
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-account-login-style]',
					'operator' => '==',
					'value'    => 'text',
				),
				SmartToolz_Builder_Helper::$general_tab_config,
			),
			'partial'   => array(
				'selector'        => '.ast-header-account',
				'render_callback' => array( 'SmartToolz_Builder_UI_Controller', 'render_account' ),
			),
		),

		/**
		 * Option: Account Log In Link
		 */
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[header-account-login-link]',
			'default'           => smarttoolz_get_option( 'header-account-login-link' ),
			'type'              => 'control',
			'control'           => 'ast-link',
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_link' ),
			'section'           => $_section,
			'title'             => __( 'Account URL', 'smarttoolz' ),
			'priority'          => 6,
			'transport'         => 'postMessage',
			'context'           => $login_link_context,
			'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
			'partial'           => array(
				'selector'        => '.ast-header-account',
				'render_callback' => array( 'SmartToolz_Builder_UI_Controller', 'render_account' ),
			),
		),

		/**
		 * Option: Log Out view
		 */
		array(
			'name'     => SMARTTOOLZ_THEME_SETTINGS . '[header-account-logout-heading]',
			'type'     => 'control',
			'control'  => 'ast-heading',
			'section'  => $_section,
			'title'    => __( 'Logged Out View', 'smarttoolz' ),
			'priority' => 200,
			'settings' => array(),
			'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
		),

		/**
		 * Option: Style
		 */
		array(
			'name'       => SMARTTOOLZ_THEME_SETTINGS . '[header-account-logout-style]',
			'default'    => smarttoolz_get_option( 'header-account-logout-style' ),
			'type'       => 'control',
			'control'    => 'ast-selector',
			'section'    => $_section,
			'title'      => __( 'Profile Type', 'smarttoolz' ),
			'priority'   => 201,
			'choices'    => array(
				'none' => __( 'None', 'smarttoolz' ),
				'icon' => __( 'Icon', 'smarttoolz' ),
				'text' => __( 'Text', 'smarttoolz' ),
			),
			'transport'  => 'postMessage',
			'partial'    => array(
				'selector'        => '.ast-header-account',
				'render_callback' => array( 'SmartToolz_Builder_UI_Controller', 'render_account' ),
			),
			'responsive' => false,
			'renderAs'   => 'text',
		),

		/**
		 * Option: Show Text with
		 *
		 * @since 4.6.15
		 */
		array(
			'name'        => SMARTTOOLZ_THEME_SETTINGS . '[header-account-logout-style-extend-text-profile-type]',
			'default'     => smarttoolz_get_option( 'header-account-logout-style-extend-text-profile-type' ),
			'type'        => 'control',
			'control'     => 'ast-selector',
			'section'     => $_section,
			'priority'    => 202,
			'description' => __( 'Choose if you want to display Icon with the Text selected Profile Type for logged out users.', 'smarttoolz' ),
			'title'       => __( 'Show Text with', 'smarttoolz' ),
			'choices'     => array(
				'default' => __( 'Default', 'smarttoolz' ),
				'icon'    => __( 'Icon', 'smarttoolz' ),
			),
			'transport'   => 'postMessage',
			'partial'     => array(
				'selector'        => '.ast-header-account',
				'render_callback' => array( 'SmartToolz_Builder_UI_Controller', 'render_account' ),
			),
			'responsive'  => false,
			'renderAs'    => 'text',
			'divider'     => array( 'ast_class' => 'ast-top-divider ast-section-spacing' ),
			'context'     => array(
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-account-logout-style]',
					'operator' => '==',
					'value'    => 'text',
				),
				SmartToolz_Builder_Helper::$general_tab_config,
			),
		),

		// Option: Logged out options preview.
		array(
			'name'      => SMARTTOOLZ_THEME_SETTINGS . '[header-account-logout-preview]',
			'default'   => smarttoolz_get_option( 'header-account-logout-preview' ),
			'type'      => 'control',
			'control'   => 'ast-toggle-control',
			'section'   => $_section,
			'title'     => __( 'Preview', 'smarttoolz' ),
			'priority'  => 206,
			'context'   => array(
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-account-logout-style]',
					'operator' => '!=',
					'value'    => 'none',
				),
				SmartToolz_Builder_Helper::$general_tab_config,
			),
			'transport' => 'postMessage',
			'partial'   => array(
				'selector'        => '.ast-header-account',
				'render_callback' => array( 'SmartToolz_Builder_UI_Controller', 'render_account' ),
			),
			'divider'   => array( 'ast_class' => 'ast-top-divider' ),
		),

		/**
		 * Option: Logged Out Text
		 */
		array(
			'name'      => SMARTTOOLZ_THEME_SETTINGS . '[header-account-logged-out-text]',
			'default'   => smarttoolz_get_option( 'header-account-logged-out-text' ),
			'type'      => 'control',
			'control'   => 'text',
			'section'   => $_section,
			'title'     => __( 'Text', 'smarttoolz' ),
			'priority'  => 203,
			'transport' => 'postMessage',
			'context'   => array(
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-account-logout-style]',
					'operator' => '==',
					'value'    => 'text',
				),
				SmartToolz_Builder_Helper::$general_tab_config,
			),
			'partial'   => array(
				'selector'        => '.ast-header-account',
				'render_callback' => array( 'SmartToolz_Builder_UI_Controller', 'render_account' ),
			),
		),

		/**
		 * Option: Account Log Out Link
		 */
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[header-account-logout-link]',
			'default'           => smarttoolz_get_option( 'header-account-logout-link' ),
			'type'              => 'control',
			'control'           => 'ast-link',
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_link' ),
			'section'           => $_section,
			'title'             => __( 'Login URL', 'smarttoolz' ),
			'priority'          => 205,
			'transport'         => 'postMessage',
			'divider'           => array( 'ast_class' => 'ast-top-divider' ),
			'context'           => array(
				array(
					'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-account-logout-style]',
					'operator' => '!=',
					'value'    => 'none',
				),
				$logout_link_context,
				SmartToolz_Builder_Helper::$general_tab_config,
			),
		),

		/**
		 * Option: Image Width
		 */
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[header-account-image-width]',
			'section'           => $_section,
			'priority'          => 2,
			'transport'         => 'postMessage',
			'default'           => smarttoolz_get_option( 'header-account-image-width' ),
			'title'             => __( 'Avatar Width', 'smarttoolz' ),
			'type'              => 'control',
			'divider'           => defined( 'SMARTTOOLZ_EXT_VER' ) ? array( 'ast_class' => 'ast-bottom-spacing' ) : array( 'ast_class' => 'ast-bottom-divider' ),
			'control'           => 'ast-responsive-slider',
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
			'input_attrs'       => array(
				'min'  => 0,
				'step' => 1,
				'max'  => 100,
			),
			'suffix'            => 'px',
			'context'           => array(
				array(
					'relation' => 'OR',
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-account-login-style]',
						'operator' => '==',
						'value'    => 'avatar',
					),
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-account-login-style-extend-text-profile-type]',
						'operator' => '==',
						'value'    => 'avatar',
					),
				),
				array(
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-account-login-style]',
						'operator' => '==',
						'value'    => 'text',
					),
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-account-login-style-extend-text-profile-type]',
						'operator' => '==',
						'value'    => 'avatar',
					),
				),
				SmartToolz_Builder_Helper::$design_tab_config,
			),
		),

		/**
		 * Option: account Size
		 */
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[header-account-icon-size]',
			'section'           => $_section,
			'priority'          => 4,
			'transport'         => 'postMessage',
			'default'           => smarttoolz_get_option( 'header-account-icon-size' ),
			'title'             => __( 'Icon Size', 'smarttoolz' ),
			'type'              => 'control',
			'suffix'            => 'px',
			'control'           => 'ast-responsive-slider',
			'divider'           => array( 'ast_class' => 'ast-bottom-divider' ),
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
			'input_attrs'       => array(
				'min'  => 0,
				'step' => 1,
				'max'  => 50,
			),
			'context'           => array(
				/**
				 * Other conditions are maintained from "inc/customizer/custom-controls/class-smarttoolz-customizer-control-base.php".
				 */
				SmartToolz_Builder_Helper::$design_tab_config,
				array(
					'relation' => 'OR',
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-account-login-style]',
						'operator' => '==',
						'value'    => 'icon',
					),
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-account-logout-style]',
						'operator' => '==',
						'value'    => 'icon',
					),
				),
			),
		),

		/**
		 * Option: account Color.
		 */
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[header-account-icon-color]',
			'default'           => smarttoolz_get_option( 'header-account-icon-color' ),
			'type'              => 'control',
			'section'           => $_section,
			'priority'          => 5,
			'transport'         => 'postMessage',
			'control'           => 'ast-color',
			'divider'           => array( 'ast_class' => defined( 'SMARTTOOLZ_EXT_VER' ) ? '' : 'ast-bottom-divider' ),
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			'title'             => __( 'Icon Color', 'smarttoolz' ),
			'context'           => array(
				/**
				 * Other conditions are maintained from "inc/customizer/custom-controls/class-smarttoolz-customizer-control-base.php".
				 */
				SmartToolz_Builder_Helper::$design_tab_config,
				array(
					'relation' => 'OR',
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-account-login-style]',
						'operator' => '==',
						'value'    => 'icon',
					),
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-account-logout-style]',
						'operator' => '==',
						'value'    => 'icon',
					),
				),
			),
		),

		/**
		 * Option: Text design options.
		 */
		array(
			'name'     => SMARTTOOLZ_THEME_SETTINGS . '[header-account-text-design-options]',
			'type'     => 'control',
			'control'  => 'ast-heading',
			'section'  => $_section,
			'priority' => 15,
			'title'    => __( 'Text Options', 'smarttoolz' ),
			'settings' => array(),
			'context'  => array(
				SmartToolz_Builder_Helper::$design_tab_config,
				array(
					'relation' => 'OR',
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-account-login-style]',
						'operator' => '==',
						'value'    => 'text',
					),
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-account-logout-style]',
						'operator' => '==',
						'value'    => 'text',
					),
				),
			),
			'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
		),

		/**
		 * Option: account Color.
		 */
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[header-account-type-text-color]',
			'default'           => smarttoolz_get_option( 'header-account-type-text-color' ),
			'type'              => 'control',
			'section'           => $_section,
			'priority'          => 18,
			'transport'         => 'postMessage',
			'control'           => 'ast-color',
			'divider'           => array( 'ast_class' => 'ast-top-section-spacing' ),
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
			'title'             => __( 'Profile Text Color', 'smarttoolz' ),
			'context'           => array(
				SmartToolz_Builder_Helper::$design_tab_config,
				array(
					'relation' => 'OR',
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-account-login-style]',
						'operator' => '==',
						'value'    => 'text',
					),
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-account-logout-style]',
						'operator' => '==',
						'value'    => 'text',
					),
				),
			),
		),

		/**
		 * Option: Divider
		 */
		array(
			'name'     => SMARTTOOLZ_THEME_SETTINGS . '[header-account-spacing-divider]',
			'section'  => 'section-header-account',
			'title'    => __( 'Spacing', 'smarttoolz' ),
			'type'     => 'control',
			'control'  => 'ast-heading',
			'priority' => 510,
			'settings' => array(),
			'context'  => SmartToolz_Builder_Helper::$design_tab,
			'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
		),

		/**
		 * Option: Margin Space
		 */
		array(
			'name'              => SMARTTOOLZ_THEME_SETTINGS . '[header-account-margin]',
			'default'           => smarttoolz_get_option( 'header-account-margin' ),
			'type'              => 'control',
			'transport'         => 'postMessage',
			'control'           => 'ast-responsive-spacing',
			'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
			'section'           => $_section,
			'priority'          => 511,
			'title'             => __( 'Margin', 'smarttoolz' ),
			'linked_choices'    => true,
			'unit_choices'      => array( 'px', 'em', '%' ),
			'choices'           => array(
				'top'    => __( 'Top', 'smarttoolz' ),
				'right'  => __( 'Right', 'smarttoolz' ),
				'bottom' => __( 'Bottom', 'smarttoolz' ),
				'left'   => __( 'Left', 'smarttoolz' ),
			),
			'context'           => SmartToolz_Builder_Helper::$design_tab,
			'divider'           => array( 'ast_class' => 'ast-top-section-spacing' ),
		),
	);

	$_configs = array_merge(
		$_configs,
		SmartToolz_Builder_Base_Configuration::prepare_typography_options(
			$_section,
			array(
				SmartToolz_Builder_Helper::$design_tab_config,
				array(
					'relation' => 'OR',
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-account-login-style]',
						'operator' => '==',
						'value'    => 'text',
					),
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[header-account-logout-style]',
						'operator' => '==',
						'value'    => 'text',
					),
				),
			),
			array( 'ast_class' => 'ast-top-section-spacing' )
		)
	);

	$_configs = array_merge( $_configs, SmartToolz_Builder_Base_Configuration::prepare_visibility_tab( $_section ) );

	if ( SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
		array_map( 'smarttoolz_save_header_customizer_configs', $_configs );
	}

	return $_configs;
}

if ( SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
	add_action( 'init', 'smarttoolz_header_account_configuration' );
}
