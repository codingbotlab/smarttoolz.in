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

if ( ! class_exists( 'SmartToolz_Blog_Layout_Configs' ) ) {

	/**
	 * Register Blog Layout Customizer Configurations.
	 */
	class SmartToolz_Blog_Layout_Configs extends SmartToolz_Customizer_Config_Base {
		/**
		 * Register Blog Layout Customizer Configurations.
		 *
		 * @param Array                $configurations SmartToolz Customizer Configurations.
		 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
		 * @since 1.4.3
		 * @return Array SmartToolz Customizer Configurations with updated configurations.
		 */
		public function register_configuration( $configurations, $wp_customize ) {

			$smarttoolz_backwards = SmartToolz_Dynamic_CSS::smarttoolz_4_6_0_compatibility();

			$old_blog_layouts      = array();
			$old_blog_layouts_free = array();
			$new_blog_layouts      = array();
			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$if_smarttoolz_addon = defined( 'SMARTTOOLZ_EXT_VER' ) && SmartToolz_Ext_Extension::is_active( 'blog-pro' );
			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

			if ( false === $smarttoolz_backwards ) {
				$old_blog_layouts = array(
					'blog-layout-1' => array(
						'label' => __( 'Layout 1', 'smarttoolz' ),
						'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'blog-layout-1', false ) : '',
					),
					'blog-layout-2' => array(
						'label' => __( 'Layout 2', 'smarttoolz' ),
						'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'blog-layout-2', false ) : '',
					),
					'blog-layout-3' => array(
						'label' => __( 'Layout 3', 'smarttoolz' ),
						'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'blog-layout-3', false ) : '',
					),
				);

				$old_blog_layouts_free = array(
					'blog-layout-classic' => array(
						'label' => __( 'Classic Layout', 'smarttoolz' ),
						'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'blog-layout-classic', false ) : '',
					),
				);
			}

			$new_blog_layouts = array(
				'blog-layout-4' => array(
					'label' => __( 'Grid', 'smarttoolz' ),
					'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'blog-layout-4', false ) : '',
				),
				'blog-layout-5' => array(
					'label' => __( 'List', 'smarttoolz' ),
					'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'blog-layout-5', false ) : '',
				),
				'blog-layout-6' => array(
					'label' => __( 'Cover', 'smarttoolz' ),
					'path'  => class_exists( 'SmartToolz_Builder_UI_Controller' ) ? SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'blog-layout-6', false ) : '',
				),
			);

			if ( $if_smarttoolz_addon ) {
				$blog_layout = array_merge(
					$old_blog_layouts,
					$new_blog_layouts
				);
			} else {
				$blog_layout = array_merge(
					$old_blog_layouts_free,
					$new_blog_layouts
				);
			}

			$_configs = array(

				/**
				 * Option: Blog Content Width
				 */
				array(
					'name'       => SMARTTOOLZ_THEME_SETTINGS . '[blog-width]',
					'default'    => smarttoolz_get_option( 'blog-width' ),
					'type'       => 'control',
					'control'    => 'ast-selector',
					'section'    => 'section-blog',
					'priority'   => 50,
					'transport'  => 'postMessage',
					'title'      => __( 'Content Width', 'smarttoolz' ),
					'choices'    => array(
						'default' => __( 'Default', 'smarttoolz' ),
						'custom'  => __( 'Custom', 'smarttoolz' ),
					),
					'responsive' => false,
					'renderAs'   => 'text',
					'divider'    => array( 'ast_class' => 'ast-top-section-divider' ),
				),

				/**
				 * Option: Enter Width
				 */
				array(
					'name'        => SMARTTOOLZ_THEME_SETTINGS . '[blog-max-width]',
					'type'        => 'control',
					'control'     => 'ast-slider',
					'section'     => 'section-blog',
					'transport'   => 'postMessage',
					'default'     => smarttoolz_get_option( 'blog-max-width' ),
					'priority'    => 50,
					'context'     => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[blog-width]',
							'operator' => '===',
							'value'    => 'custom',
						),
					),
					'title'       => __( 'Custom Width', 'smarttoolz' ),
					'suffix'      => 'px',
					'input_attrs' => array(
						'min'  => 768,
						'step' => 1,
						'max'  => 1920,
					),
					'divider'     => array( 'ast_class' => 'ast-top-divider' ),
				),

				/**
				 * Option: Blog Post Content
				 */
				array(
					'name'        => 'blog-post-content',
					'parent'      => SMARTTOOLZ_THEME_SETTINGS . '[blog-post-structure]',
					'section'     => 'section-blog',
					'title'       => __( 'Post Content', 'smarttoolz' ),
					'default'     => smarttoolz_get_option( 'blog-post-content' ),
					'type'        => 'sub-control',
					'control'     => 'ast-selector',
					'linked'      => 'excerpt',
					'priority'    => 75,
					'choices'     => array(
						'full-content' => __( 'Full Content', 'smarttoolz' ),
						'excerpt'      => __( 'Excerpt', 'smarttoolz' ),
					),
					'responsive'  => false,
					'renderAs'    => 'text',
					'input_attrs' => array(
						'dependents' => array(
							'excerpt' => array( 'blog-excerpt-count', 'blog-excerpt-marker', 'blog-excerpt-marker-description' ),
						),
					),
				),

				/**
				 * Option: Excerpt Truncation Marker
				 */
				array(
					'name'              => 'blog-excerpt-marker',
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[blog-post-structure]',
					'section'           => 'section-blog',
					'title'             => __( 'Truncation Marker', 'smarttoolz' ),
					'default'           => smarttoolz_get_option( 'blog-excerpt-marker' ),
					'type'              => 'sub-control',
					'control'           => 'ast-text-input',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_html' ),
					'linked'            => 'excerpt',
					'priority'          => 76,
					'divider'           => array( 'ast_class' => 'ast-top-divider' ),
				),

				/**
				 * Option: Excerpt Truncation Marker description
				 */
				array(
					'name'     => 'blog-excerpt-marker-description',
					'parent'   => SMARTTOOLZ_THEME_SETTINGS . '[blog-post-structure]',
					'section'  => 'section-blog',
					'type'     => 'sub-control',
					'control'  => 'ast-description',
					'linked'   => 'excerpt',
					'priority' => 77,
					'label'    => '',
					'help'     => __( 'Shown at the end of trimmed excerpts. Leave empty to hide it.', 'smarttoolz' ),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[blog-divider]',
					'section'  => 'section-blog',
					'title'    => __( 'Blog Layout', 'smarttoolz' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 14,
					'settings' => array(),
					'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
				),

				/**
				 * Option: Blog Layout
				 */
				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[blog-layout]',
					'type'              => 'control',
					'control'           => 'ast-radio-image',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'           => 'section-blog',
					'default'           => smarttoolz_get_option( 'blog-layout' ),
					'priority'          => 14,
					'title'             => __( 'Layout', 'smarttoolz' ),
					'choices'           => $blog_layout,
				),

				/**
				 * Option: Post Per Page
				 */
				array(
					'name'         => SMARTTOOLZ_THEME_SETTINGS . '[blog-post-per-page]',
					'default'      => smarttoolz_get_option( 'blog-post-per-page' ),
					'type'         => 'control',
					'control'      => 'ast-number',
					'qty_selector' => true,
					'section'      => 'section-blog',
					'title'        => __( 'Post Per Page', 'smarttoolz' ),
					'priority'     => 14,
					'responsive'   => false,
					'input_attrs'  => array(
						'min'  => 1,
						'step' => 1,
						'max'  => 500,
					),
					'divider'      => array( 'ast_class' => $if_smarttoolz_addon ? 'ast-sub-top-divider' : 'ast-top-section-divider' ),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[archive-post-content-structure-divider]',
					'section'  => 'section-blog',
					'title'    => __( 'Posts Structure', 'smarttoolz' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 51,
					'settings' => array(),
					'divider'  => array( 'ast_class' => 'ast-top-section-divider ast-bottom-spacing' ),
				),

				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[blog-post-structure]',
					'default'           => smarttoolz_get_option( 'blog-post-structure' ),
					'type'              => 'control',
					'control'           => 'ast-sortable',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_multi_choices' ),
					'section'           => 'section-blog',
					'priority'          => 52,
					'title'             => __( 'Post Elements', 'smarttoolz' ),
					'divider'           => array( 'ast_class' => 'ast-top-spacing' ),
					'choices'           => array(
						'image'      => array(
							'clone'       => false,
							'is_parent'   => true,
							'main_index'  => 'image',
							'clone_limit' => 1,
							'title'       => __( 'Featured Image', 'smarttoolz' ),
						),
						'category'   => array(
							'clone'       => false,
							'is_parent'   => true,
							'main_index'  => 'category',
							'clone_limit' => 1,
							'title'       => __( 'Categories', 'smarttoolz' ),
						),
						'tag'        => array(
							'clone'       => false,
							'is_parent'   => true,
							'main_index'  => 'tag',
							'clone_limit' => 1,
							'title'       => __( 'Tags', 'smarttoolz' ),
						),
						'title'      => __( 'Title', 'smarttoolz' ),
						'title-meta' => array(
							'clone'       => false,
							'is_parent'   => true,
							'main_index'  => 'title-meta',
							'clone_limit' => 1,
							'title'       => __( 'Post Meta', 'smarttoolz' ),
						),
						'excerpt'    => array(
							'clone'       => false,
							'is_parent'   => true,
							'main_index'  => 'excerpt',
							'clone_limit' => 1,
							'title'       => __( 'Excerpt', 'smarttoolz' ),
						),
						'read-more'  => array(
							'clone'       => false,
							'is_parent'   => $if_smarttoolz_addon ? true : false,
							'main_index'  => 'read-more',
							'clone_limit' => 1,
							'title'       => __( 'Read More', 'smarttoolz' ),
						),
					),
				),

				/**
				 * Option: Date Meta Type.
				 */
				array(
					'name'       => 'blog-meta-date-type',
					'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[blog-meta]',
					'type'       => 'sub-control',
					'control'    => 'ast-selector',
					'section'    => 'section-blog',
					'default'    => smarttoolz_get_option( 'blog-meta-date-type' ),
					'priority'   => 1,
					'linked'     => 'date',
					'transport'  => 'postMessage',
					'title'      => __( 'Type', 'smarttoolz' ),
					'choices'    => array(
						'published' => __( 'Published', 'smarttoolz' ),
						'updated'   => __( 'Last Updated', 'smarttoolz' ),
					),
					'divider'    => array( 'ast_class' => 'ast-bottom-spacing' ),
					'responsive' => false,
					'renderAs'   => 'text',
				),

				/**
				 * Date format support for meta field.
				 */
				array(
					'name'       => 'blog-meta-date-format',
					'default'    => smarttoolz_get_option( 'blog-meta-date-format' ),
					'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[blog-meta]',
					'linked'     => 'date',
					'type'       => 'sub-control',
					'control'    => 'ast-select',
					'transport'  => 'postMessage',
					'section'    => 'section-blog',
					'priority'   => 2,
					'responsive' => false,
					'renderAs'   => 'text',
					'title'      => __( 'Format', 'smarttoolz' ),
					'choices'    => array(
						''       => __( 'Default', 'smarttoolz' ),
						'F j, Y' => 'November 6, 2010',
						'Y-m-d'  => '2010-11-06',
						'm/d/Y'  => '11/06/2010',
						'd/m/Y'  => '06/11/2010',
					),
				),

				/**
				 * Option: Image Ratio Type.
				 */
				array(
					'name'                   => 'blog-image-ratio-type',
					'default'                => smarttoolz_get_option( 'blog-image-ratio-type' ),
					'type'                   => 'sub-control',
					'transport'              => 'postMessage',
					'parent'                 => SMARTTOOLZ_THEME_SETTINGS . '[blog-post-structure]',
					'section'                => 'section-blog',
					'linked'                 => 'image',
					'priority'               => 5,
					'control'                => 'ast-selector',
					'title'                  => __( 'Image Ratio', 'smarttoolz' ),
					'choices'                => array(
						''           => __( 'Original', 'smarttoolz' ),
						'predefined' => __( 'Predefined', 'smarttoolz' ),
						'custom'     => __( 'Custom', 'smarttoolz' ),
					),
					'responsive'             => false,
					'renderAs'               => 'text',
					'contextual_sub_control' => true,
					'input_attrs'            => array(
						'dependents' => array(
							''           => array( 'blog-original-image-scale-description' ),
							'predefined' => array( 'blog-image-ratio-pre-scale' ),
							'custom'     => array( 'blog-image-custom-scale-width', 'blog-image-custom-scale-height', 'blog-custom-image-scale-description' ),
						),
					),
				),

				/**
				 * Option: Image Ratio Scale.
				 */
				array(
					'name'       => 'blog-image-ratio-pre-scale',
					'default'    => smarttoolz_get_option( 'blog-image-ratio-pre-scale', '16/9' ),
					'type'       => 'sub-control',
					'transport'  => 'postMessage',
					'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[blog-post-structure]',
					'linked'     => 'image',
					'section'    => 'section-blog',
					'priority'   => 10,
					'control'    => 'ast-selector',
					'choices'    => array(
						'1/1'  => __( '1:1', 'smarttoolz' ),
						'4/3'  => __( '4:3', 'smarttoolz' ),
						'16/9' => __( '16:9', 'smarttoolz' ),
						'2/1'  => __( '2:1', 'smarttoolz' ),
					),
					'responsive' => false,
					'renderAs'   => 'text',
				),

				/**
				 * Option: Image Scale width.
				 */
				array(
					'name'              => 'blog-image-custom-scale-width',
					'default'           => smarttoolz_get_option( 'blog-image-custom-scale-width', 16 ),
					'type'              => 'sub-control',
					'control'           => 'ast-number',
					'transport'         => 'postMessage',
					'title'             => __( 'Width', 'smarttoolz' ),
					'qty_selector'      => true,
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[blog-post-structure]',
					'section'           => 'section-blog',
					'linked'            => 'image',
					'priority'          => 11,
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
				),

				/**
				 * Option: Image Scale height.
				 */
				array(
					'name'         => 'blog-image-custom-scale-height',
					'default'      => smarttoolz_get_option( 'blog-image-custom-scale-height', 9 ),
					'type'         => 'sub-control',
					'control'      => 'ast-number',
					'qty_selector' => true,
					'transport'    => 'postMessage',
					'title'        => __( 'Height', 'smarttoolz' ),
					'parent'       => SMARTTOOLZ_THEME_SETTINGS . '[blog-post-structure]',
					'section'      => 'section-blog',
					'linked'       => 'image',
					'priority'     => 12,
				),

				array(
					'name'     => 'blog-custom-image-scale-description',
					'parent'   => SMARTTOOLZ_THEME_SETTINGS . '[blog-post-structure]',
					'linked'   => 'image',
					'type'     => 'sub-control',
					'control'  => 'ast-description',
					'section'  => 'section-blog',
					'priority' => 14,
					'label'    => '',
					'help'     => sprintf( /* translators: 1: link open markup, 2: link close markup */ __( 'Calculate a personalized image ratio using this %1$s online tool %2$s for your image dimensions.', 'smarttoolz' ), '<a href="' . esc_url( 'https://www.digitalrebellion.com/webapps/aspectcalc' ) . '" target="_blank">', '</a>' ),
				),

				/**
				 * Option: Blog Hover Effect.
				 */
				array(
					'name'       => 'blog-hover-effect',
					'default'    => smarttoolz_get_option( 'blog-hover-effect' ),
					'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[blog-post-structure]',
					'section'    => 'section-blog',
					'linked'     => 'image',
					'type'       => 'sub-control',
					'priority'   => 17,
					'transport'  => 'postMessage',
					'title'      => __( 'Hover Effect', 'smarttoolz' ),
					'divider'    => array( 'ast_class' => 'ast-top-divider' ),
					'control'    => 'ast-selector',
					'responsive' => false,
					'renderAs'   => 'text',
					'choices'    => array(
						'none'     => __( 'None', 'smarttoolz' ),
						'zoom-in'  => __( 'Zoom In', 'smarttoolz' ),
						'zoom-out' => __( 'Zoom Out', 'smarttoolz' ),
					),
				),

				/**
				 * Option: Image Size.
				 */
				array(
					'name'      => 'blog-image-size',
					'default'   => smarttoolz_get_option( 'blog-image-size', 'large' ),
					'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[blog-post-structure]',
					'section'   => 'section-blog',
					'linked'    => 'image',
					'type'      => 'sub-control',
					'priority'  => 17,
					'transport' => 'postMessage',
					'title'     => __( 'Image Size', 'smarttoolz' ),
					'divider'   => array( 'ast_class' => 'ast-top-divider' ),
					'control'   => 'ast-select',
					'choices'   => smarttoolz_get_site_image_sizes(),
				),

				/**
				 * Option: Divider
				 */
				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[blog-post-color-divider]',
					'section'  => 'section-blog',
					'title'    => __( 'Post Cards', 'smarttoolz' ),
					'type'     => 'control',
					'control'  => 'ast-heading',
					'priority' => 1,
					'context'  => array(
						SmartToolz_Builder_Helper::$design_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[blog-layout]',
							'operator' => '===',
							'value'    => 'blog-layout-6',
						),
					),
				),

				/**
				 * Option: Background Overlay
				 * Location: Custom Post Types > Blog / Archive > Design
				 */
				array(
					'name'        => SMARTTOOLZ_THEME_SETTINGS . '[post-card-background-overlay]',
					'default'     => smarttoolz_get_option( 'post-card-background-overlay' ),
					'type'        => 'control',
					'control'     => 'ast-background',
					'allow_image' => false,
					'section'     => 'section-blog',
					'priority'    => 2.5,
					'title'       => __( 'Background Overlay', 'smarttoolz' ),
					'transport'   => 'refresh',
					'context'     => array(
						SmartToolz_Builder_Helper::$design_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[blog-layout]',
							'operator' => '===',
							'value'    => 'blog-layout-6',
						),
					),
				),

				/**
				 * Option: Card Radius
				 */
				array(
					'name'           => SMARTTOOLZ_THEME_SETTINGS . '[post-card-border-radius]',
					'default'        => smarttoolz_get_option( 'post-card-border-radius' ),
					'type'           => 'control',
					'control'        => 'ast-responsive-spacing',
					'transport'      => 'postMessage',
					'section'        => 'section-blog',
					'title'          => __( 'Border Radius', 'smarttoolz' ),
					'suffix'         => 'px',
					'priority'       => $if_smarttoolz_addon ? 144 : 2.5,
					'divider'        => array( 'ast_class' => 'ast-top-divider' ),
					'context'        => SmartToolz_Builder_Helper::$design_tab,
					'linked_choices' => true,
					'unit_choices'   => array( 'px', 'em', '%' ),
					'choices'        => array(
						'top'    => __( 'Top', 'smarttoolz' ),
						'right'  => __( 'Right', 'smarttoolz' ),
						'bottom' => __( 'Bottom', 'smarttoolz' ),
						'left'   => __( 'Left', 'smarttoolz' ),
					),
					'connected'      => false,
				),

				/**
				 * Option: Blog Category Style
				 */
				array(
					'name'       => 'blog-category-style',
					'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[blog-post-structure]',
					'section'    => 'section-blog',
					'title'      => __( 'Style', 'smarttoolz' ),
					'default'    => smarttoolz_get_option( 'blog-category-style' ),
					'type'       => 'sub-control',
					'control'    => 'ast-selector',
					'linked'     => 'category',
					'priority'   => 75,
					'transport'  => 'refresh',
					'choices'    => array(
						'default'   => __( 'Default', 'smarttoolz' ),
						'badge'     => __( 'Badge', 'smarttoolz' ),
						'underline' => __( 'Underline', 'smarttoolz' ),
					),
					'responsive' => false,
					'renderAs'   => 'text',
				),

				/**
				 * Option: Blog Tag Style
				 */
				array(
					'name'       => 'blog-tag-style',
					'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[blog-post-structure]',
					'section'    => 'section-blog',
					'title'      => __( 'Style', 'smarttoolz' ),
					'default'    => smarttoolz_get_option( 'blog-tag-style' ),
					'type'       => 'sub-control',
					'control'    => 'ast-selector',
					'transport'  => 'postMessage',
					'linked'     => 'tag',
					'priority'   => 75,
					'choices'    => array(
						'default'   => __( 'Default', 'smarttoolz' ),
						'badge'     => __( 'Badge', 'smarttoolz' ),
						'underline' => __( 'Underline', 'smarttoolz' ),
					),
					'responsive' => false,
					'renderAs'   => 'text',
				),

				/**
				 * Option: Blog Meta Category Divider Type
				 */
				array(
					'name'              => 'blog-post-meta-divider-type',
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[blog-post-structure]',
					'section'           => 'section-blog',
					'title'             => __( 'Divider Type', 'smarttoolz' ),
					'default'           => smarttoolz_get_option( 'blog-post-meta-divider-type' ),
					'type'              => 'sub-control',
					'transport'         => 'postMessage',
					'control'           => 'ast-selector',
					'linked'            => 'title-meta',
					'priority'          => 75,
					'choices'           => array(
						'/'    => '/',
						'-'    => '-',
						'|'    => '|',
						'•'    => '•',
						'none' => __( 'None', 'smarttoolz' ),
					),
					'responsive'        => false,
					'renderAs'          => 'text',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_meta_separator' ),
				),

				/**
				 * Option: Blog Meta Category Style
				 */
				array(
					'name'       => 'blog-meta-category-style',
					'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[blog-meta]',
					'section'    => 'section-blog',
					'title'      => __( 'Style', 'smarttoolz' ),
					'default'    => smarttoolz_get_option( 'blog-meta-category-style' ),
					'type'       => 'sub-control',
					'transport'  => 'postMessage',
					'control'    => 'ast-selector',
					'linked'     => 'category',
					'priority'   => 75,
					'choices'    => array(
						'default'   => __( 'Default', 'smarttoolz' ),
						'underline' => __( 'Underline', 'smarttoolz' ),
					),
					'responsive' => false,
					'renderAs'   => 'text',
				),

				/**
				 * Option: Blog Meta Tag Style
				 */
				array(
					'name'       => 'blog-meta-tag-style',
					'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[blog-meta]',
					'section'    => 'section-blog',
					'title'      => __( 'Style', 'smarttoolz' ),
					'default'    => smarttoolz_get_option( 'blog-meta-tag-style' ),
					'type'       => 'sub-control',
					'control'    => 'ast-selector',
					'transport'  => 'postMessage',
					'linked'     => 'tag',
					'priority'   => 75,
					'choices'    => array(
						'default'   => __( 'Default', 'smarttoolz' ),
						'underline' => __( 'Underline', 'smarttoolz' ),
					),
					'responsive' => false,
					'renderAs'   => 'text',
				),
			);

			$_configs[] = array(
				'name'        => 'section-blog-ast-context-tabs',
				'section'     => 'section-blog',
				'type'        => 'control',
				'control'     => 'ast-builder-header-control',
				'priority'    => 0,
				'description' => '',
			);

			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			if ( ! defined( 'SMARTTOOLZ_EXT_VER' ) || ( defined( 'SMARTTOOLZ_EXT_VER' ) && ! SmartToolz_Ext_Extension::is_active( 'blog-pro' ) ) ) {

				$_configs[] = array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[blog-meta]',
					'type'              => 'control',
					'control'           => 'ast-sortable',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_multi_choices' ),
					'section'           => 'section-blog',
					'default'           => smarttoolz_get_option( 'blog-meta' ),
					'priority'          => 52,
					'context'           => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[blog-post-structure]',
							'operator' => 'contains',
							'value'    => 'title-meta',
						),
					),
					'title'             => __( 'Meta', 'smarttoolz' ),
					'choices'           => array(
						'comments' => __( 'Comments', 'smarttoolz' ),
						'category' => __( 'Categories', 'smarttoolz' ),
						'author'   => __( 'Author', 'smarttoolz' ),
						'date'     => array(
							'clone'       => false,
							'is_parent'   => true,
							'main_index'  => 'date',
							'clone_limit' => 1,
							'title'       => __( 'Date', 'smarttoolz' ),
						),
						'tag'      => __( 'Tags', 'smarttoolz' ),
					),
					'divider'           => array( 'ast_class' => 'ast-top-section-divider' ),
				);
			}

			return array_merge( $configurations, $_configs );
		}
	}
}

new SmartToolz_Blog_Layout_Configs();
