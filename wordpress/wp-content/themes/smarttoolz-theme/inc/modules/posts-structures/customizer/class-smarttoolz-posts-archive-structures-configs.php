<?php
/**
 * Posts Structures Options for our theme.
 *
 * @package     SmartToolz
 * @link        https://www.brainstormforce.com
 * @since       SmartToolz 4.0.0
 */

// Block direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'SmartToolz_Customizer_Config_Base' ) ) {
	return;
}

/**
 * Register Posts Structures Customizer Configurations.
 *
 * @since 4.0.0
 */
class SmartToolz_Posts_Archive_Structures_Configs extends SmartToolz_Customizer_Config_Base {
	/**
	 * Getting new content layout options dynamically.
	 * Compatibility case: Narrow width + dynamic customizer controls.
	 *
	 * @param string $post_type On basis of this will decide to show narrow-width layout or not.
	 * @since 4.2.0
	 */
	public function get_new_content_layout_choices( $post_type ) {
		if ( ! in_array( $post_type, SmartToolz_Posts_Structures_Configs::get_narrow_width_exculde_cpts() ) ) {
			return array(
				'default'                => array(
					'label' => __( 'Default', 'smarttoolz' ),
					'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'layout-default', false ) : '',
				),
				'normal-width-container' => array(
					'label' => __( 'Normal', 'smarttoolz' ),
					'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'normal-width-container', false ) : '',
				),
				'narrow-width-container' => array(
					'label' => __( 'Narrow', 'smarttoolz' ),
					'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'narrow-width-container', false ) : '',
				),
				'full-width-container'   => array(
					'label' => __( 'Full Width', 'smarttoolz' ),
					'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'full-width-container', false ) : '',
				),
			);
		}
			return array(
				'default'                => array(
					'label' => __( 'Default', 'smarttoolz' ),
					'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'layout-default', false ) : '',
				),
				'normal-width-container' => array(
					'label' => __( 'Normal', 'smarttoolz' ),
					'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'normal-width-container', false ) : '',
				),
				'full-width-container'   => array(
					'label' => __( 'Full Width', 'smarttoolz' ),
					'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'full-width-container', false ) : '',
				),
			);
	}

	/**
	 * Register Archive Post's Structures Customizer Configurations.
	 *
	 * @param string $parent_section Section of dynamic customizer.
	 * @param string $post_type Post Type.
	 * @since 4.0.0
	 *
	 * @return array Customizer Configurations.
	 */
	public function get_layout_configuration( $parent_section, $post_type ) {
		if ( 'page' === $post_type ) {
			return array(); // Page archive not require.
		}

		$reveal_effect = array();
		/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
		if ( defined( 'SMARTTOOLZ_EXT_VER' ) && SmartToolz_Ext_Extension::is_active( 'blog-pro' ) && ! ( 'post' === $post_type || 'product' === $post_type || 'buddypress' === $post_type ) ) {
			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$reveal_effect = array(
				'name'     => SMARTTOOLZ_THEME_SETTINGS . '[archive-' . $post_type . '-ast-reveal-effect]',
				'section'  => $parent_section,
				'type'     => 'control',
				'control'  => 'ast-toggle-control',
				'default'  => smarttoolz_get_option( 'archive-' . $post_type . '-ast-reveal-effect', false ),
				'title'    => __( 'Reveal Effect', 'smarttoolz' ),
				'priority' => 5,
				'divider'  => array( 'ast_class' => 'ast-section-spacing ast-top-section-divider' ),
			);
		}
		return array(

			/**
			 * Option: Revamped Archive Container Layout.
			 */
			array(
				'name'              => SMARTTOOLZ_THEME_SETTINGS . '[archive-' . $post_type . '-ast-content-layout]',
				'type'              => 'control',
				'control'           => 'ast-radio-image',
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_choices' ),
				'section'           => $parent_section,
				'default'           => smarttoolz_get_option( 'archive-' . $post_type . '-ast-content-layout', 'default' ),
				'priority'          => 5,
				'title'             => __( 'Container Layout', 'smarttoolz' ),
				'choices'           => $this->get_new_content_layout_choices( $post_type ),
				'transport'         => 'refresh',
				'divider'           => array( 'ast_class' => 'ast-top-divider' ),
			),

			/**
			 * Option: Archive Content Style.
			 */
			array(
				'name'        => SMARTTOOLZ_THEME_SETTINGS . '[archive-' . $post_type . '-content-style]',
				'type'        => 'control',
				'control'     => 'ast-selector',
				'section'     => $parent_section,
				'default'     => smarttoolz_get_option( 'archive-' . $post_type . '-content-style', 'default' ),
				'priority'    => 5,
				'title'       => __( 'Container Style', 'smarttoolz' ),
				'choices'     => array(
					'default' => __( 'Default', 'smarttoolz' ),
					'unboxed' => __( 'Unboxed', 'smarttoolz' ),
					'boxed'   => __( 'Boxed', 'smarttoolz' ),
				),
				'responsive'  => false,
				'renderAs'    => 'text',
				'description' => __( 'Container style will apply only when layout is set to either normal or narrow.', 'smarttoolz' ),
				'divider'     => array( 'ast_class' => 'ast-top-divider' ),
			),

			/**
			 * Option: Archive Sidebar Layout.
			 */
			array(
				'name'              => SMARTTOOLZ_THEME_SETTINGS . '[archive-' . $post_type . '-sidebar-layout]',
				'type'              => 'control',
				'control'           => 'ast-radio-image',
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_choices' ),
				'section'           => $parent_section,
				'default'           => smarttoolz_get_option( 'archive-' . $post_type . '-sidebar-layout', 'default' ),
				'priority'          => 5,
				'title'             => __( 'Sidebar Layout', 'smarttoolz' ),
				'description'       => __( 'Sidebar will only apply when container layout is set to normal.', 'smarttoolz' ),
				'divider'           => array( 'ast_class' => 'ast-top-section-divider' ),
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
			),

			/**
			 * Option: Archive Sidebar Style.
			 */
			array(
				'name'       => SMARTTOOLZ_THEME_SETTINGS . '[archive-' . $post_type . '-sidebar-style]',
				'type'       => 'control',
				'control'    => 'ast-selector',
				'section'    => $parent_section,
				'default'    => smarttoolz_get_option( 'archive-' . $post_type . '-sidebar-style', 'default' ),
				'priority'   => 5,
				'title'      => __( 'Sidebar Style', 'smarttoolz' ),
				'choices'    => array(
					'default' => __( 'Default', 'smarttoolz' ),
					'unboxed' => __( 'Unboxed', 'smarttoolz' ),
					'boxed'   => __( 'Boxed', 'smarttoolz' ),
				),
				'responsive' => false,
				'renderAs'   => 'text',
				'divider'    => array( 'ast_class' => 'ast-top-divider' ),
			),

			$reveal_effect,
		);
	}

	/**
	 * Register Posts Structures Customizer Configurations.
	 *
	 * @param Array                $configurations SmartToolz Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since 4.0.0
	 * @return Array SmartToolz Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {

		$post_types = SmartToolz_Posts_Structure_Loader::get_supported_post_types();

		foreach ( $post_types as $post_type ) {

			if ( 'page' === $post_type ) {
				continue;
			}

			$section       = 'archive-posttype-' . $post_type;
			$title_section = 'ast-dynamic-archive-' . $post_type;

			$background_choices = array(
				'none'   => __( 'None', 'smarttoolz' ),
				'custom' => __( 'Custom', 'smarttoolz' ),
			);
			if ( 'product' === $post_type ) {
				$parent_section     = 'woocommerce_product_catalog';
				$background_choices = array(
					'none'     => __( 'None', 'smarttoolz' ),
					'custom'   => __( 'Custom', 'smarttoolz' ),
					'featured' => __( 'Featured', 'smarttoolz' ),
				);
			} elseif ( 'download' === $post_type ) {
				$parent_section = 'section-edd-archive';
			} elseif ( 'post' === $post_type ) {
				$parent_section = 'section-blog';
			} else {
				$parent_section = $section;
			}

			$configurations = array_merge( $configurations, $this->get_layout_configuration( $parent_section, $post_type ) );

			$archive_structure_choices = array();

			/**
			 * Archive sortable title control.
			 */
			$archive_structure_choices[ $title_section . '-title' ] = array(
				'clone'       => false,
				'is_parent'   => true,
				'main_index'  => $title_section . '-title',
				'clone_limit' => 2,
				'title'       => __( 'Title', 'smarttoolz' ),
			);

			/**
			 * Archive sortable title control.
			 */
			$archive_structure_choices[ $title_section . '-description' ] = array(
				'clone'       => false,
				'is_parent'   => true,
				'main_index'  => $title_section . '-description',
				'clone_limit' => 2,
				'title'       => __( 'Description', 'smarttoolz' ),
			);

			$_configs = array(

				/**
				 * Option: Builder Tabs
				 */
				array(
					'name'        => $title_section . '-ast-context-tabs',
					'section'     => $title_section,
					'type'        => 'control',
					'control'     => 'ast-builder-header-control',
					'priority'    => 0,
					'description' => '',
					'context'     => array(),
				),

				array(
					'name'     => $title_section,
					// @codingStandardsIgnoreStart
					'title' => $this->get_dynamic_section_title( get_post_type_object( $post_type ), $post_type ),
					// @codingStandardsIgnoreEnd
					'type'     => 'section',
					'section'  => $parent_section,
					'panel'    => 'product' === $post_type ? 'woocommerce' : '',
					'priority' => 1,
				),

				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[ast-archive-' . $post_type . '-title]',
					'type'     => 'control',
					'default'  => smarttoolz_get_option( 'ast-archive-' . $post_type . '-title', class_exists( 'WooCommerce' ) && 'product' === $post_type ? false : true ),
					'control'  => 'ast-section-toggle',
					'section'  => $parent_section,
					'priority' => 2,
					'linked'   => $title_section,
					// @codingStandardsIgnoreStart
					'linkText' => $this->get_dynamic_section_title( get_post_type_object( $post_type ), $post_type ),
					// @codingStandardsIgnoreEnd
				),

				/**
				 * Layout option.
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-layout]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'           => $title_section,
					'default'           => smarttoolz_get_option( $title_section . '-layout', 'layout-1' ),
					'priority'          => 5,
					'context'           => SmartToolz_Builder_Helper::$general_tab,
					'title'             => __( 'Banner Layout', 'smarttoolz' ),
					'divider'           => array( 'ast_class' => 'ast-section-spacing' ),
					'choices'           => array(
						'layout-1' => array(
							'label' => __( 'Layout 1', 'smarttoolz' ),
							'path'  => SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'banner-layout-1' ),
						),
						'layout-2' => array(
							'label' => __( 'Layout 2', 'smarttoolz' ),
							'path'  => SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'banner-layout-2' ),
						),
					),
				),

				/**
				 * Option: Banner Content Width.
				 */
				array(
					'name'       => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-width-type]',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => $title_section,
					'default'    => smarttoolz_get_option( $title_section . '-banner-width-type', 'fullwidth' ),
					'priority'   => 10,
					'title'      => __( 'Container Width', 'smarttoolz' ),
					'choices'    => array(
						'fullwidth' => __( 'Full Width', 'smarttoolz' ),
						'custom'    => __( 'Custom', 'smarttoolz' ),
					),
					'divider'    => array( 'ast_class' => 'ast-top-divider ast-bottom-spacing' ),
					'responsive' => false,
					'renderAs'   => 'text',
					'context'    => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-layout]',
							'operator' => '===',
							'value'    => 'layout-2',
						),
					),
				),

				/**
				 * Option: Enter Width
				 */
				array(
					'name'        => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-custom-width]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'section'     => $title_section,
					'transport'   => 'postMessage',
					'default'     => smarttoolz_get_option( $title_section . '-banner-custom-width', 1200 ),
					'context'     => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-layout]',
							'operator' => '===',
							'value'    => 'layout-2',
						),
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-width-type]',
							'operator' => '===',
							'value'    => 'custom',
						),
					),
					'priority'    => 15,
					'title'       => __( 'Custom Width', 'smarttoolz' ),
					'suffix'      => 'px',
					'input_attrs' => array(
						'min'  => 768,
						'step' => 1,
						'max'  => 1920,
					),
				),

				/**
				 * Option: Display Post Structure
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'type'              => 'control',
					'control'           => 'ast-sortable',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_multi_choices' ),
					'section'           => $title_section,
					'context'           => SmartToolz_Builder_Helper::$general_tab,
					'default'           => smarttoolz_get_option( $title_section . '-structure', array( $title_section . '-title', $title_section . '-description' ) ),
					'priority'          => 20,
					'title'             => __( 'Structure', 'smarttoolz' ),
					'divider'           => 'post' !== $post_type ? array( 'ast_class' => 'ast-top-divider' ) : array(),
					'choices'           => array_merge(
						array(
							$title_section . '-breadcrumb' => __( 'Breadcrumb', 'smarttoolz' ),
						),
						$archive_structure_choices
					),
				),

				/**
				 * Title support for archive.
				 */
				array(
					'name'     => $title_section . '-custom-title',
					'parent'   => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'default'  => smarttoolz_get_option( $title_section . '-custom-title', '' ),
					'linked'   => $title_section . '-title',
					'type'     => 'sub-control',
					'control'  => 'ast-text-input',
					'settings' => array(),
					'section'  => $title_section,
					'priority' => 1,
					'title'    => 'post' === $post_type ? __( 'Blog Title', 'smarttoolz' ) : __( 'Archive Title', 'smarttoolz' ),
				),

				/**
				 * Help description for title support.
				 */
				array(
					'name'     => $title_section . '-custom-title-support',
					'parent'   => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'linked'   => $title_section . '-title',
					'type'     => 'sub-control',
					'control'  => 'ast-description',
					'section'  => $title_section,
					'priority' => 2,
					'label'    => '',
					'help'     => esc_html( sprintf( /* translators: 1: post type */ __( 'Note: This title appear on %1$s archive for banner Layout 2.', 'smarttoolz' ), $post_type ) ),
				),

				/**
				 * Description support for archive.
				 */
				array(
					'name'        => $title_section . '-custom-description',
					'parent'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'default'     => smarttoolz_get_option( $title_section . '-custom-description', '' ),
					'linked'      => $title_section . '-description',
					'type'        => 'sub-control',
					'control'     => 'ast-text-input',
					'input_attrs' => array(
						'textarea' => true,
					),
					'section'     => $title_section,
					'priority'    => 1,
					'title'       => 'post' === $post_type ? __( 'Blog Description', 'smarttoolz' ) : __( 'Archive Description', 'smarttoolz' ),
				),

				/**
				 * Help description for description support.
				 */
				array(
					'name'     => $title_section . '-custom-description-support',
					'parent'   => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'linked'   => $title_section . '-description',
					'type'     => 'sub-control',
					'control'  => 'ast-description',
					'section'  => $title_section,
					'priority' => 2,
					'label'    => '',
					'help'     => esc_html( sprintf( /* translators: 1: post type */ __( 'Note: This description appear on %1$s archive for banner Layout 2.', 'smarttoolz' ), $post_type ) ),
				),

				/**
				 * Option: Horizontal Alignment.
				 */
				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-horizontal-alignment]',
					'default'   => smarttoolz_get_option( $title_section . '-horizontal-alignment' ),
					'type'      => 'control',
					'control'   => 'ast-selector',
					'section'   => $title_section,
					'priority'  => 21,
					'title'     => __( 'Horizontal Alignment', 'smarttoolz' ),
					'context'   => SmartToolz_Builder_Helper::$general_tab,
					'transport' => 'postMessage',
					'choices'   => array(
						'left'   => 'align-left',
						'center' => 'align-center',
						'right'  => 'align-right',
					),
					'divider'   => array( 'ast_class' => 'ast-top-divider' ),
				),

				/**
				 * Option: Vertical Alignment
				 */
				array(
					'name'       => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-vertical-alignment]',
					'default'    => smarttoolz_get_option( $title_section . '-vertical-alignment', 'center' ),
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => $title_section,
					'priority'   => 22,
					'title'      => __( 'Vertical Alignment', 'smarttoolz' ),
					'choices'    => array(
						'flex-start' => __( 'Top', 'smarttoolz' ),
						'center'     => __( 'Middle', 'smarttoolz' ),
						'flex-end'   => __( 'Bottom', 'smarttoolz' ),
					),
					'divider'    => array( 'ast_class' => 'ast-top-divider ast-section-spacing' ),
					'context'    => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-layout]',
							'operator' => '===',
							'value'    => 'layout-2',
						),
					),
					'transport'  => 'postMessage',
					'renderAs'   => 'text',
					'responsive' => false,
				),

				/**
				 * Option: Container min height.
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-height]',
					'type'              => 'control',
					'control'           => 'ast-responsive-slider',
					'section'           => $title_section,
					'transport'         => 'postMessage',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
					'default'           => smarttoolz_get_option( $title_section . '-banner-height', SmartToolz_Posts_Structure_Loader::get_customizer_default( 'responsive-slider' ) ),
					'context'           => array(
						SmartToolz_Builder_Helper::$design_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-layout]',
							'operator' => '===',
							'value'    => 'layout-2',
						),
					),
					'priority'          => 1,
					'title'             => __( 'Banner Min Height', 'smarttoolz' ),
					'suffix'            => 'px',
					'input_attrs'       => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 1000,
					),
					'divider'           => array( 'ast_class' => 'ast-bottom-divider ast-section-spacing' ),
				),

				/**
				 * Option: Elements gap.
				 */
				array(
					'name'        => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-elements-gap]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'section'     => $title_section,
					'transport'   => 'postMessage',
					'default'     => smarttoolz_get_option( $title_section . '-elements-gap', 10 ),
					'context'     => SmartToolz_Builder_Helper::$design_tab,
					'priority'    => 5,
					'title'       => __( 'Inner Elements Spacing', 'smarttoolz' ),
					'suffix'      => 'px',
					'input_attrs' => array(
						'min'  => 0,
						'step' => 1,
						'max'  => 100,
					),
					'divider'     => array( 'ast_class' => 'ast-bottom-divider ast-section-spacing' ),
				),

				/**
				 * Option: Banner Content Width.
				 */
				array(
					'name'       => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-image-type]',
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => $title_section,
					'default'    => smarttoolz_get_option( $title_section . '-banner-image-type', 'none' ),
					'priority'   => 5,
					'context'    => SmartToolz_Builder_Helper::$design_tab,
					'title'      => __( 'Container Background', 'smarttoolz' ),
					'choices'    => $background_choices,
					'divider'    => array( 'ast_class' => 'ast-section-spacing ast-bottom-spacing' ),
					'responsive' => false,
					'renderAs'   => 'text',
				),

				/**
				 * Option: Featured Image Custom Banner BG.
				 */
				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-custom-bg]',
					'default'   => smarttoolz_get_option( $title_section . '-banner-custom-bg', SmartToolz_Posts_Structure_Loader::get_customizer_default( 'responsive-background' ) ),
					'type'      => 'control',
					'control'   => 'ast-responsive-background',
					'section'   => $title_section,
					'title'     => __( 'Background', 'smarttoolz' ),
					'transport' => 'postMessage',
					'priority'  => 5,
					'context'   => array(
						SmartToolz_Builder_Helper::$design_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-image-type]',
							'operator' => '===',
							'value'    => 'custom',
						),
					),
				),

				/**
				 * Option: Title Color
				 */
				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-title-color]',
					'type'      => 'control',
					'control'   => 'ast-color',
					'section'   => $title_section,
					'default'   => smarttoolz_get_option( $title_section . '-banner-title-color' ),
					'transport' => 'postMessage',
					'priority'  => 9,
					'title'     => __( 'Title Color', 'smarttoolz' ),
					'divider'   => array( 'ast_class' => 'ast-top-divider ast-top-spacing' ),
					'context'   => SmartToolz_Builder_Helper::$design_tab,
				),

				/**
				 * Option: Text Color
				 */
				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-text-color]',
					'type'      => 'control',
					'control'   => 'ast-color',
					'section'   => $title_section,
					'default'   => smarttoolz_get_option( $title_section . '-banner-text-color' ),
					'priority'  => 10,
					'title'     => __( 'Text Color', 'smarttoolz' ),
					'transport' => 'postMessage',
					'context'   => SmartToolz_Builder_Helper::$design_tab,
				),

				/**
				 * Option: Link Color
				 */
				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-link-color]',
					'type'      => 'control',
					'control'   => 'ast-color',
					'section'   => $title_section,
					'default'   => smarttoolz_get_option( $title_section . '-banner-link-color' ),
					'transport' => 'postMessage',
					'priority'  => 15,
					'title'     => __( 'Link Color', 'smarttoolz' ),
					'context'   => SmartToolz_Builder_Helper::$design_tab,
				),

				/**
				 * Option: Link Hover Color
				 */
				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-link-hover-color]',
					'type'      => 'control',
					'control'   => 'ast-color',
					'section'   => $title_section,
					'default'   => smarttoolz_get_option( $title_section . '-banner-link-hover-color' ),
					'transport' => 'postMessage',
					'priority'  => 20,
					'title'     => __( 'Link Hover Color', 'smarttoolz' ),
					'context'   => SmartToolz_Builder_Helper::$design_tab,
					'divider'   => array( 'ast_class' => 'ast-bottom-spacing' ),
				),

				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-title-typography-group]',
					'type'      => 'control',
					'priority'  => 22,
					'control'   => 'ast-settings-group',
					'context'   => array(
						SmartToolz_Builder_Helper::$design_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-structure]',
							'operator' => 'contains',
							'value'    => $title_section . '-title',
						),
					),
					'divider'   => array( 'ast_class' => 'ast-top-divider' ),
					'title'     => __( 'Title Font', 'smarttoolz' ),
					'is_font'   => true,
					'section'   => $title_section,
					'transport' => 'postMessage',
				),

				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-text-typography-group]',
					'type'      => 'control',
					'priority'  => 25,
					'control'   => 'ast-settings-group',
					'context'   => SmartToolz_Builder_Helper::$design_tab,
					'title'     => __( 'Text Font', 'smarttoolz' ),
					'is_font'   => true,
					'divider'   => array( 'ast_class' => 'ast-bottom-spacing' ),
					'section'   => $title_section,
					'transport' => 'postMessage',
				),

				/**
				 * Option: Text Font Family
				 */
				array(
					'name'      => $title_section . '-text-font-family',
					'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-text-typography-group]',
					'section'   => $title_section,
					'type'      => 'sub-control',
					'control'   => 'ast-font',
					'font_type' => 'ast-font-family',
					'default'   => smarttoolz_get_option( $title_section . '-text-font-family', 'inherit' ),
					'title'     => __( 'Font Family', 'smarttoolz' ),
					'connect'   => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-text-font-weight]',
					'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				),

				/**
				 * Option: Text Font Weight
				 */
				array(
					'name'              => $title_section . '-text-font-weight',
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-text-typography-group]',
					'section'           => $title_section,
					'type'              => 'sub-control',
					'control'           => 'ast-font',
					'font_type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => smarttoolz_get_option( $title_section . '-text-font-weight', 'inherit' ),
					'title'             => __( 'Font Weight', 'smarttoolz' ),
					'connect'           => $title_section . '-text-font-family',
					'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				),

				/**
				 * Option: Text Font Size
				 */
				array(
					'name'              => $title_section . '-text-font-size',
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-text-typography-group]',
					'section'           => $title_section,
					'type'              => 'sub-control',
					'control'           => 'ast-responsive-slider',
					'default'           => smarttoolz_get_option( $title_section . '-text-font-size', SmartToolz_Posts_Structure_Loader::get_customizer_default( 'font-size' ) ),
					'transport'         => 'postMessage',
					'title'             => __( 'Font Size', 'smarttoolz' ),
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
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
				),

				/**
				 * Option: Archive Post Banner Text Font Extras
				 */
				array(
					'name'    => $title_section . '-text-font-extras',
					'parent'  => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-text-typography-group]',
					'section' => $title_section,
					'type'    => 'sub-control',
					'control' => 'ast-font-extras',
					'default' => smarttoolz_get_option( $title_section . '-text-font-extras', SmartToolz_Posts_Structure_Loader::get_customizer_default( 'font-extras' ) ),
					'title'   => __( 'Font Extras', 'smarttoolz' ),
				),

				/**
				 * Option: Title Font Family
				 */
				array(
					'name'      => $title_section . '-title-font-family',
					'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-title-typography-group]',
					'section'   => $title_section,
					'type'      => 'sub-control',
					'control'   => 'ast-font',
					'font_type' => 'ast-font-family',
					'default'   => smarttoolz_get_option( $title_section . '-title-font-family', 'inherit' ),
					'title'     => __( 'Font Family', 'smarttoolz' ),
					'connect'   => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-title-font-weight]',
					'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				),

				/**
				 * Option: Title Font Weight
				 */
				array(
					'name'              => $title_section . '-title-font-weight',
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-title-typography-group]',
					'section'           => $title_section,
					'type'              => 'sub-control',
					'control'           => 'ast-font',
					'font_type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => smarttoolz_get_option( $title_section . '-title-font-weight', SmartToolz_Posts_Structure_Loader::get_customizer_default( 'title-font-weight' ) ),
					'title'             => __( 'Font Weight', 'smarttoolz' ),
					'connect'           => $title_section . '-title-font-family',
					'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				),

				/**
				 * Option: Title Font Size
				 */

				array(
					'name'              => $title_section . '-title-font-size',
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-title-typography-group]',
					'section'           => $title_section,
					'type'              => 'sub-control',
					'control'           => 'ast-responsive-slider',
					'default'           => smarttoolz_get_option( $title_section . '-title-font-size', SmartToolz_Posts_Structure_Loader::get_customizer_default( 'title-font-size' ) ),
					'transport'         => 'postMessage',
					'title'             => __( 'Font Size', 'smarttoolz' ),
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_slider' ),
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
				),

				/**
				 * Option: Archive Post Banner Title Font Extras
				 */
				array(
					'name'    => $title_section . '-title-font-extras',
					'parent'  => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-title-typography-group]',
					'section' => $title_section,
					'type'    => 'sub-control',
					'control' => 'ast-font-extras',
					'default' => smarttoolz_get_option( $title_section . '-title-font-extras', SmartToolz_Posts_Structure_Loader::get_customizer_default( 'font-extras' ) ),
					'title'   => __( 'Font Extras', 'smarttoolz' ),
				),

				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-margin]',
					'default'           => smarttoolz_get_option( $title_section . '-banner-margin', SmartToolz_Posts_Structure_Loader::get_customizer_default( 'responsive-spacing' ) ),
					'type'              => 'control',
					'control'           => 'ast-responsive-spacing',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
					'section'           => $title_section,
					'divider'           => array( 'ast_class' => 'ast-top-divider' ),
					'title'             => __( 'Margin', 'smarttoolz' ),
					'linked_choices'    => true,
					'transport'         => 'postMessage',
					'unit_choices'      => array( 'px', 'em', '%' ),
					'choices'           => array(
						'top'    => __( 'Top', 'smarttoolz' ),
						'right'  => __( 'Right', 'smarttoolz' ),
						'bottom' => __( 'Bottom', 'smarttoolz' ),
						'left'   => __( 'Left', 'smarttoolz' ),
					),
					'context'           => SmartToolz_Builder_Helper::$design_tab,
					'priority'          => 100,
					'connected'         => false,
				),

				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-padding]',
					'default'           => smarttoolz_get_option( $title_section . '-banner-padding', class_exists( 'WooCommerce' ) && 'product' === $post_type ? SmartToolz_Posts_Structure_Loader::get_customizer_default( 'responsive-spacing' ) : SmartToolz_Posts_Structure_Loader::get_customizer_default( 'responsive-padding' ) ),
					'type'              => 'control',
					'control'           => 'ast-responsive-spacing',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
					'section'           => $title_section,
					'title'             => __( 'Padding', 'smarttoolz' ),
					'linked_choices'    => true,
					'transport'         => 'postMessage',
					'unit_choices'      => array( 'px', 'em', '%' ),
					'choices'           => array(
						'top'    => __( 'Top', 'smarttoolz' ),
						'right'  => __( 'Right', 'smarttoolz' ),
						'bottom' => __( 'Bottom', 'smarttoolz' ),
						'left'   => __( 'Left', 'smarttoolz' ),
					),
					'context'           => SmartToolz_Builder_Helper::$design_tab,
					'priority'          => 120,
					'connected'         => false,
				),
			);

			if ( 'post' === $post_type ) {
				/**
				 * Option: Disable Transparent Header on Your latest posts index Page
				 */
				$_configs[] = array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-on-blog]',
					'default'  => smarttoolz_get_option( $title_section . '-banner-on-blog', false ),
					'type'     => 'control',
					'section'  => $title_section,
					'context'  => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-layout]',
							'operator' => '!=',
							'value'    => 'layout-1',
						),
					),
					'title'    => __( 'Enable on Blog / Posts Page?', 'smarttoolz' ),
					'priority' => 7,
					'control'  => 'ast-toggle-control',
					'divider'  => array( 'ast_class' => 'ast-top-divider ast-bottom-spacing' ),
				);
			}

			if ( 'product' === $post_type ) {
				/**
				 * Option: Featured Image Overlay Color.
				 */
				$_configs[] = array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-featured-overlay]',
					'type'     => 'control',
					'control'  => 'ast-color',
					'section'  => $title_section,
					'default'  => smarttoolz_get_option( $title_section . '-banner-featured-overlay', '' ),
					'priority' => 6,
					'title'    => __( 'Overlay Color', 'smarttoolz' ),
					'context'  => array(
						SmartToolz_Builder_Helper::$design_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-image-type]',
							'operator' => '===',
							'value'    => 'featured',
						),
					),
				);
			}

			$configurations = array_merge( $configurations, $_configs );
		}

		return $configurations;
	}

	/**
	 * Get Dynamic Section Title.
	 *
	 * @since 4.4.0
	 * @param object|null $post_type_object Post type object.
	 * @param string      $post_type Post type.
	 * @return string
	 */
	public function get_dynamic_section_title( $post_type_object, $post_type ) {
		if ( ! is_null( $post_type_object ) ) {
			$title = 'post' === $post_type ? __( 'Blog', 'smarttoolz' ) : SmartToolz_Posts_Structures_Configs::smarttoolz_get_dynamic_section_title( get_post_type_object( $post_type ), $post_type );
		} else {
			$title = __( 'Archive Banner', 'smarttoolz' );
		}
		return apply_filters( 'smarttoolz_archive_post_title', $title . __( ' Title Area', 'smarttoolz' ), $post_type );
	}
}

/**
 * Kicking this off by creating new object.
 */
new SmartToolz_Posts_Archive_Structures_Configs();
