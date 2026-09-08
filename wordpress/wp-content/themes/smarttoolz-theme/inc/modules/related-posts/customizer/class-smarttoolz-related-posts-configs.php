<?php
/**
 * Related Posts Options for SmartToolz Theme.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Bail if Customizer config base class does not exist.
if ( ! class_exists( 'SmartToolz_Customizer_Config_Base' ) ) {
	return;
}

/**
 * Register Related Posts Configurations.
 */
class SmartToolz_Related_Posts_Configs extends SmartToolz_Customizer_Config_Base {
	/**
	 * Register Related Posts Configurations.
	 *
	 * @param Array                $configurations SmartToolz Customizer Configurations.
	 * @param WP_Customize_Manager $wp_customize instance of WP_Customize_Manager.
	 * @since 3.5.0
	 * @return Array SmartToolz Customizer Configurations with updated configurations.
	 */
	public function register_configuration( $configurations, $wp_customize ) {
		$related_structure_sub_controls = array();
		$meta_config_options            = array();
		$parent_section                 = 'section-blog-single';

		$related_structure_sub_controls['featured-image'] = array(
			'clone'       => false,
			'is_parent'   => true,
			'main_index'  => 'featured-image',
			'clone_limit' => 2,
			'title'       => __( 'Featured Image', 'smarttoolz' ),
		);
		$related_structure_sub_controls['title-meta']     = array(
			'clone'       => false,
			'is_parent'   => true,
			'main_index'  => 'title-meta',
			'clone_limit' => 2,
			'title'       => __( 'Title & Post Meta', 'smarttoolz' ),
		);
		$meta_config_options['category']                  = array(
			'clone'       => false,
			'is_parent'   => true,
			'main_index'  => 'category',
			'clone_limit' => 1,
			'title'       => __( 'Category', 'smarttoolz' ),
		);
		$meta_config_options['author']                    = array(
			'clone'       => false,
			'is_parent'   => true,
			'main_index'  => 'author',
			'clone_limit' => 1,
			'title'       => __( 'Author', 'smarttoolz' ),
		);
		$meta_config_options['date']                      = array(
			'clone'       => false,
			'is_parent'   => true,
			'main_index'  => 'date',
			'clone_limit' => 1,
			'title'       => __( 'Date', 'smarttoolz' ),
		);
		$meta_config_options['tag']                       = array(
			'clone'       => false,
			'is_parent'   => true,
			'main_index'  => 'tag',
			'clone_limit' => 1,
			'title'       => __( 'Tag', 'smarttoolz' ),
		);

		$_configs = array(

			/**
			 * Option: Related Posts Query
			 */
			array(
				'name'     => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-section-heading]',
				'section'  => 'section-blog-single',
				'type'     => 'control',
				'control'  => 'ast-heading',
				'title'    => __( 'Related Posts', 'smarttoolz' ),
				'priority' => 10,
				'divider'  => array( 'ast_class' => 'ast-top-section-divider' ),
			),

			array(
				'name'        => 'related-posts-section-ast-context-tabs',
				'section'     => 'ast-sub-section-related-posts',
				'type'        => 'control',
				'control'     => 'ast-builder-header-control',
				'priority'    => 0,
				'description' => '',
				'context'     => array(),
			),

			array(
				'name'     => 'ast-sub-section-related-posts',
				'title'    => __( 'Related Posts', 'smarttoolz' ),
				'type'     => 'section',
				'section'  => $parent_section,
				'panel'    => '',
				'priority' => 1,
			),

			array(
				'name'     => SMARTTOOLZ_THEME_SETTINGS . '[enable-related-posts]',
				'type'     => 'control',
				'default'  => smarttoolz_get_option( 'enable-related-posts' ),
				'control'  => 'ast-section-toggle',
				'section'  => $parent_section,
				'priority' => 10,
				'linked'   => 'ast-sub-section-related-posts',
				'linkText' => __( 'Related Posts', 'smarttoolz' ),
				'divider'  => array( 'ast_class' => 'ast-bottom-divider ast-bottom-section-divider' ),
			),

			/**
			 * Option: Related Posts Title
			 */
			array(
				'name'      => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-title]',
				'default'   => smarttoolz_get_option( 'related-posts-title' ),
				'type'      => 'control',
				'section'   => 'ast-sub-section-related-posts',
				'priority'  => 11,
				'title'     => __( 'Title', 'smarttoolz' ),
				'control'   => 'ast-text-input',
				'divider'   => array( 'ast_class' => 'ast-section-spacing' ),
				'transport' => 'postMessage',
				'partial'   => array(
					'selector'            => '.ast-related-posts-title-section .ast-related-posts-title',
					'container_inclusive' => false,
					'render_callback'     => array( 'SmartToolz_Related_Posts_Loader', 'render_related_posts_title' ),
				),
				'context'   => array(
					SmartToolz_Builder_Helper::$general_tab_config,
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
				),
			),

			/**
			 * Option: Related Posts Title Alignment
			 */
			array(
				'name'       => SMARTTOOLZ_THEME_SETTINGS . '[releted-posts-title-alignment]',
				'default'    => smarttoolz_get_option( 'releted-posts-title-alignment' ),
				'section'    => 'ast-sub-section-related-posts',
				'transport'  => 'postMessage',
				'title'      => __( 'Title Alignment', 'smarttoolz' ),
				'type'       => 'control',
				'control'    => 'ast-selector',
				'priority'   => 11,
				'responsive' => false,
				'divider'    => array( 'ast_class' => 'ast-top-divider' ),
				'context'    => array(
					SmartToolz_Builder_Helper::$general_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-title]',
						'operator' => '!=',
						'value'    => '',
					),
				),
				'choices'    => array(
					'left'   => 'align-left',
					'center' => 'align-center',
					'right'  => 'align-right',
				),
			),

			/**
			 * Option: Related Posts Structure
			 */
			array(
				'name'              => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-structure]',
				'type'              => 'control',
				'control'           => 'ast-sortable',
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_multi_choices' ),
				'section'           => 'ast-sub-section-related-posts',
				'default'           => smarttoolz_get_option( 'related-posts-structure' ),
				'priority'          => 12,
				'context'           => array(
					SmartToolz_Builder_Helper::$general_tab_config,
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'title'             => __( 'Posts Structure', 'smarttoolz' ),

				'choices'           => $related_structure_sub_controls,
				'divider'           => array( 'ast_class' => 'ast-top-divider' ),
			),
			/**
			 * Option: Meta Data Separator.
			 */
			array(
				'name'              => 'related-metadata-separator',
				'default'           => smarttoolz_get_option( 'related-metadata-separator', '/' ),
				'type'              => 'sub-control',
				'transport'         => 'postMessage',
				'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-structure]',
				'linked'            => 'title-meta',
				'section'           => 'ast-sub-section-related-posts',
				'priority'          => 10,
				'control'           => 'ast-selector',
				'title'             => __( 'Divider Type', 'smarttoolz' ),
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
			array(
				'name'              => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-meta-structure]',
				'type'              => 'control',
				'control'           => 'ast-sortable',
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_multi_choices' ),
				'default'           => smarttoolz_get_option( 'related-posts-meta-structure' ),
				'context'           => array(
					SmartToolz_Builder_Helper::$general_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-structure]',
						'operator' => 'contains',
						'value'    => 'title-meta',
					),
				),
				'section'           => 'ast-sub-section-related-posts',
				'priority'          => 12,
				'title'             => __( 'Meta', 'smarttoolz' ),
				'choices'           => array_merge(
					array(
						'comments' => __( 'Comments', 'smarttoolz' ),
					),
					$meta_config_options
				),
				'divider'           => array( 'ast_class' => 'ast-top-divider' ),
			),
			array(
				'name'                   => 'related-posts-image-ratio-type',
				'default'                => smarttoolz_get_option( 'related-posts-image-ratio-type', '' ),
				'type'                   => 'sub-control',
				'transport'              => 'postMessage',
				'parent'                 => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-structure]',
				'section'                => 'ast-sub-section-related-posts',
				'linked'                 => 'featured-image',
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
						''           => array( 'related-posts-original-image-scale-description' ),
						'predefined' => array( 'related-posts-image-ratio-pre-scale' ),
						'custom'     => array( 'related-posts-image-custom-scale-width', 'related-posts-image-custom-scale-height', 'related-posts-custom-image-scale-description' ),
					),
				),
			),
			array(
				'name'       => 'related-posts-image-ratio-pre-scale',
				'default'    => smarttoolz_get_option( 'related-posts-image-ratio-pre-scale' ),
				'type'       => 'sub-control',
				'transport'  => 'postMessage',
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-structure]',
				'linked'     => 'featured-image',
				'section'    => 'ast-sub-section-related-posts',
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
			array(
				'name'              => 'related-posts-image-custom-scale-width',
				'default'           => smarttoolz_get_option( 'related-posts-image-custom-scale-width', 16 ),
				'type'              => 'sub-control',
				'control'           => 'ast-number',
				'transport'         => 'postMessage',
				'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-structure]',
				'section'           => 'ast-sub-section-related-posts',
				'linked'            => 'featured-image',
				'priority'          => 11,
				'qty_selector'      => true,
				'title'             => __( 'Width', 'smarttoolz' ),
				'input_attrs'       => array(
					'style'       => 'text-align:center;',
					'placeholder' => __( 'Auto', 'smarttoolz' ),
					'min'         => 1,
					'max'         => 1000,
				),
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
			),
			array(
				'name'              => 'related-posts-image-custom-scale-height',
				'default'           => smarttoolz_get_option( 'related-posts-image-custom-scale-height', 9 ),
				'type'              => 'sub-control',
				'control'           => 'ast-number',
				'transport'         => 'postMessage',
				'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-structure]',
				'section'           => 'ast-sub-section-related-posts',
				'linked'            => 'featured-image',
				'priority'          => 12,
				'qty_selector'      => true,
				'title'             => __( 'Height', 'smarttoolz' ),
				'input_attrs'       => array(
					'style'       => 'text-align:center;',
					'placeholder' => __( 'Auto', 'smarttoolz' ),
					'min'         => 1,
					'max'         => 1000,
				),
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_number_n_blank' ),
			),
			array(
				'name'     => 'related-posts-custom-image-scale-description',
				'parent'   => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-structure]',
				'linked'   => 'featured-image',
				'type'     => 'sub-control',
				'control'  => 'ast-description',
				'section'  => 'ast-sub-section-related-posts',
				'priority' => 14,
				'label'    => '',
				'help'     => sprintf( /* translators: 1: link open markup, 2: link close markup */ __( 'Calculate a personalized image ratio using this %1$s online tool %2$s for your image dimensions.', 'smarttoolz' ), '<a href="' . esc_url( 'https://www.digitalrebellion.com/webapps/aspectcalc' ) . '" target="_blank">', '</a>' ),
			),
			array(
				'name'        => 'related-posts-image-size',
				'default'     => smarttoolz_get_option( 'related-posts-image-size', 'large' ),
				'parent'      => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-structure]',
				'section'     => 'ast-sub-section-related-posts',
				'linked'      => 'featured-image',
				'type'        => 'sub-control',
				'priority'    => 17,
				'transport'   => 'postMessage',
				'title'       => __( 'Image Size', 'smarttoolz' ),
				'divider'     => array( 'ast_class' => 'ast-top-divider' ),
				'control'     => 'ast-select',
				'choices'     => smarttoolz_get_site_image_sizes(),
				'description' => __( 'Note: Image Size & Ratio won\'t work if Image Position set as Background.', 'smarttoolz' ),
			),
			array(
				'name'      => 'related-posts-author-prefix-label',
				'default'   => smarttoolz_get_option( 'related-posts-author-prefix-label', smarttoolz_default_strings( 'string-blog-meta-author-by', false ) ),
				'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-meta-structure]',
				'linked'    => 'author',
				'type'      => 'sub-control',
				'control'   => 'ast-text-input',
				'section'   => 'ast-sub-section-related-posts',
				'divider'   => array( 'ast_class' => 'ast-bottom-divider ast-bottom-section-spacing' ),
				'title'     => __( 'Prefix Label', 'smarttoolz' ),
				'priority'  => 1,
				'transport' => 'postMessage',
			),
			array(
				'name'      => 'related-posts-author-avatar',
				'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-meta-structure]',
				'default'   => smarttoolz_get_option( 'related-posts-author-avatar' ),
				'linked'    => 'author',
				'type'      => 'sub-control',
				'control'   => 'ast-toggle',
				'section'   => 'ast-sub-section-related-posts',
				'priority'  => 5,
				'title'     => __( 'Author Avatar', 'smarttoolz' ),
				'transport' => 'postMessage',
			),
			array(
				'name'        => 'related-posts-author-avatar-size',
				'parent'      => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-meta-structure]',
				'default'     => smarttoolz_get_option( 'related-posts-author-avatar-size', 30 ),
				'linked'      => 'author',
				'type'        => 'sub-control',
				'control'     => 'ast-slider',
				'transport'   => 'postMessage',
				'section'     => 'ast-sub-section-related-posts',
				'priority'    => 10,
				'title'       => __( 'Image Size', 'smarttoolz' ),
				'suffix'      => 'px',
				'input_attrs' => array(
					'min'  => 1,
					'step' => 1,
					'max'  => 200,
				),
			),
			array(
				'name'       => 'related-posts-meta-date-type',
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-meta-structure]',
				'type'       => 'sub-control',
				'control'    => 'ast-selector',
				'section'    => 'ast-sub-section-related-posts',
				'default'    => smarttoolz_get_option( 'related-posts-meta-date-type', 'published' ),
				'priority'   => 1,
				'linked'     => 'date',
				'transport'  => 'refresh',
				'title'      => __( 'Type', 'smarttoolz' ),
				'choices'    => array(
					'published' => __( 'Published', 'smarttoolz' ),
					'updated'   => __( 'Last Updated', 'smarttoolz' ),
				),
				'divider'    => array( 'ast_class' => 'ast-bottom-divider ast-bottom-spacing' ),
				'responsive' => false,
				'renderAs'   => 'text',
			),
			array(
				'name'       => 'related-posts-date-format',
				'default'    => smarttoolz_get_option( 'related-posts-date-format', '' ),
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-meta-structure]',
				'linked'     => 'date',
				'type'       => 'sub-control',
				'control'    => 'ast-select',
				'section'    => 'ast-sub-section-related-posts',
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
			array(
				'name'       => 'related-posts-category-style',
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-meta-structure]',
				'type'       => 'sub-control',
				'control'    => 'ast-selector',
				'section'    => 'ast-sub-section-related-posts',
				'default'    => smarttoolz_get_option( 'related-posts-category-style', '' ),
				'priority'   => 2,
				'linked'     => 'category',
				'transport'  => 'refresh',
				'title'      => __( 'Style', 'smarttoolz' ),
				'choices'    => array(
					'none'      => __( 'Default', 'smarttoolz' ),
					'badge'     => __( 'Badge', 'smarttoolz' ),
					'underline' => __( 'Underline', 'smarttoolz' ),
				),
				'responsive' => false,
				'renderAs'   => 'text',
			),
			array(
				'name'       => 'related-posts-tag-style',
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-meta-structure]',
				'type'       => 'sub-control',
				'control'    => 'ast-selector',
				'section'    => 'ast-sub-section-related-posts',
				'default'    => smarttoolz_get_option( 'related-posts-tag-style', '' ),
				'priority'   => 2,
				'linked'     => 'tag',
				'transport'  => 'refresh',
				'title'      => __( 'Style', 'smarttoolz' ),
				'choices'    => array(
					'none'      => __( 'Default', 'smarttoolz' ),
					'badge'     => __( 'Badge', 'smarttoolz' ),
					'underline' => __( 'Underline', 'smarttoolz' ),
				),
				'responsive' => false,
				'renderAs'   => 'text',
			),

			/**
			 * Option: Enable excerpt for Related Posts.
			 */
			array(
				'name'     => SMARTTOOLZ_THEME_SETTINGS . '[enable-related-posts-excerpt]',
				'default'  => smarttoolz_get_option( 'enable-related-posts-excerpt' ),
				'type'     => 'control',
				'control'  => 'ast-toggle-control',
				'title'    => __( 'Enable Post Excerpt', 'smarttoolz' ),
				'section'  => 'ast-sub-section-related-posts',
				'priority' => 12,
				'context'  => array(
					SmartToolz_Builder_Helper::$general_tab_config,
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'divider'  => array( 'ast_class' => 'ast-top-divider' ),
			),

			/**
			 * Option: Excerpt word count for Related Posts
			 */
			array(
				'name'        => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-excerpt-count]',
				'default'     => smarttoolz_get_option( 'related-posts-excerpt-count' ),
				'type'        => 'control',
				'control'     => 'ast-slider',
				'context'     => array(
					SmartToolz_Builder_Helper::$general_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[enable-related-posts-excerpt]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'section'     => 'ast-sub-section-related-posts',
				'title'       => __( 'Excerpt Word Count', 'smarttoolz' ),
				'divider'     => array( 'ast_class' => 'ast-section-spacing' ),
				'priority'    => 12,
				'input_attrs' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 60,
				),
			),

			/**
			 * Option: No. of Related Posts
			 */
			array(
				'name'        => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-total-count]',
				'default'     => smarttoolz_get_option( 'related-posts-total-count' ),
				'type'        => 'control',
				'control'     => 'ast-slider',
				'context'     => array(
					SmartToolz_Builder_Helper::$general_tab_config,
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'section'     => 'ast-sub-section-related-posts',
				'title'       => __( 'Total Number of Related Posts', 'smarttoolz' ),
				'priority'    => 11,
				'input_attrs' => array(
					'min'  => 1,
					'step' => 1,
					'max'  => 20,
				),
				'divider'     => array( 'ast_class' => 'ast-top-divider ast-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Columns
			 */
			array(
				'name'       => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-grid-responsive]',
				'type'       => 'control',
				'control'    => 'ast-selector',
				'section'    => 'ast-sub-section-related-posts',
				'default'    => smarttoolz_get_option( 'related-posts-grid-responsive' ),
				'priority'   => 11,
				'context'    => array(
					SmartToolz_Builder_Helper::$general_tab_config,
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'title'      => __( 'Grid Column Layout', 'smarttoolz' ),
				'choices'    => array(
					'full'    => __( '1', 'smarttoolz' ),
					'2-equal' => __( '2', 'smarttoolz' ),
					'3-equal' => __( '3', 'smarttoolz' ),
					'4-equal' => __( '4', 'smarttoolz' ),
				),
				'responsive' => true,
				'renderAs'   => 'text',
				'divider'    => array( 'ast_class' => 'ast-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Query group setting
			 */
			array(
				'name'      => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-query-group]',
				'default'   => smarttoolz_get_option( 'related-posts-query-group' ),
				'type'      => 'control',
				'transport' => 'postMessage',
				'control'   => 'ast-settings-group',
				'context'   => array(
					SmartToolz_Builder_Helper::$general_tab_config,
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'title'     => __( 'Posts Query', 'smarttoolz' ),
				'section'   => 'ast-sub-section-related-posts',
				'priority'  => 11,
			),

			/**
			 * Option: Related Posts based on.
			 */
			array(
				'name'       => 'related-posts-based-on',
				'default'    => smarttoolz_get_option( 'related-posts-based-on' ),
				'type'       => 'sub-control',
				'transport'  => 'postMessage',
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-query-group]',
				'section'    => 'ast-sub-section-related-posts',
				'priority'   => 1,
				'control'    => 'ast-selector',
				'divider'    => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				'title'      => __( 'Related Posts by', 'smarttoolz' ),
				'choices'    => array(
					'categories' => __( 'Categories', 'smarttoolz' ),
					'tags'       => __( 'Tags', 'smarttoolz' ),
				),
				'responsive' => false,
				'renderAs'   => 'text',
			),

			/**
			 * Option: Display Post Structure
			 */
			array(
				'name'      => 'related-posts-order-by',
				'default'   => smarttoolz_get_option( 'related-posts-order-by' ),
				'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-query-group]',
				'section'   => 'ast-sub-section-related-posts',
				'type'      => 'sub-control',
				'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				'priority'  => 2,
				'transport' => 'postMessage',
				'title'     => __( 'Order by', 'smarttoolz' ),
				'control'   => 'ast-select',
				'choices'   => array(
					'date'          => __( 'Date', 'smarttoolz' ),
					'title'         => __( 'Title', 'smarttoolz' ),
					'menu_order'    => __( 'Post Order', 'smarttoolz' ),
					'rand'          => __( 'Random', 'smarttoolz' ),
					'comment_count' => __( 'Comment Counts', 'smarttoolz' ),
				),
			),

			/**
			 * Option: Display Post Structure
			 */
			array(
				'name'       => 'related-posts-order',
				'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-query-group]',
				'section'    => 'ast-sub-section-related-posts',
				'type'       => 'sub-control',
				'transport'  => 'postMessage',
				'title'      => __( 'Order', 'smarttoolz' ),
				'default'    => smarttoolz_get_option( 'related-posts-order' ),
				'control'    => 'ast-selector',
				'priority'   => 3,
				'choices'    => array(
					'asc'  => __( 'Ascending', 'smarttoolz' ),
					'desc' => __( 'Descending', 'smarttoolz' ),
				),
				'responsive' => false,
				'renderAs'   => 'text',
			),

			array(
				'name'        => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-box-placement]',
				'default'     => smarttoolz_get_option( 'related-posts-box-placement' ),
				'type'        => 'control',
				'section'     => 'ast-sub-section-related-posts',
				'priority'    => 12,
				'title'       => __( 'Section Placement', 'smarttoolz' ),
				'control'     => 'ast-selector',
				'description' => __( 'Decide whether to isolate or integrate the module with the entry content area.', 'smarttoolz' ),
				'divider'     => array( 'ast_class' => 'ast-top-divider' ),
				'choices'     => array(
					'default' => __( 'Default', 'smarttoolz' ),
					'inside'  => __( 'Contained', 'smarttoolz' ),
					'outside' => __( 'Separated', 'smarttoolz' ),
				),
				'context'     => array(
					SmartToolz_Builder_Helper::$general_tab_config,
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'responsive'  => false,
				'renderAs'    => 'text',
			),
			array(
				'name'        => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-outside-location]',
				'default'     => smarttoolz_get_option( 'related-posts-outside-location' ),
				'type'        => 'control',
				'section'     => 'ast-sub-section-related-posts',
				'priority'    => 12,
				'title'       => __( 'Location', 'smarttoolz' ),
				'control'     => 'ast-selector',
				'choices'     => array(
					'below' => __( 'Below Comments', 'smarttoolz' ),
					'above' => __( 'Above Comments', 'smarttoolz' ),
				),
				'description' => __( 'To sync this option with comments, use the same positioning for both sections: Contained or Separated.', 'smarttoolz' ),
				'divider'     => array( 'ast_class' => 'ast-top-section-spacing' ),
				'context'     => array(
					SmartToolz_Builder_Helper::$general_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-box-placement]',
						'operator' => '!=',
						'value'    => 'default',
					),
				),
				'responsive'  => false,
				'renderAs'    => 'text',
			),
			array(
				'name'       => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-container-width]',
				'default'    => smarttoolz_get_option( 'related-posts-container-width' ),
				'type'       => 'control',
				'section'    => 'ast-sub-section-related-posts',
				'priority'   => 12,
				'title'      => __( 'Container Structure', 'smarttoolz' ),
				'control'    => 'ast-selector',
				'choices'    => array(
					'narrow' => __( 'Narrow', 'smarttoolz' ),
					''       => __( 'Full Width', 'smarttoolz' ),
				),
				'divider'    => array( 'ast_class' => 'ast-top-section-spacing' ),
				'context'    => array(
					SmartToolz_Builder_Helper::$general_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-box-placement]',
						'operator' => '==',
						'value'    => 'outside',
					),
				),
				'responsive' => false,
				'renderAs'   => 'text',
			),

			/**
			 * Option: Related Posts colors setting group
			 */
			array(
				'name'      => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-colors-group]',
				'default'   => smarttoolz_get_option( 'related-posts-colors-group' ),
				'type'      => 'control',
				'transport' => 'postMessage',
				'control'   => 'ast-settings-group',
				'context'   => array(
					SmartToolz_Builder_Helper::$design_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-structure]',
						'operator' => 'contains',
						'value'    => 'title-meta',
					),
				),
				'title'     => __( 'Content Colors', 'smarttoolz' ),
				'section'   => 'ast-sub-section-related-posts',
				'priority'  => 15,
				'divider'   => array( 'ast_class' => 'ast-bottom-divider' ),
			),

			/**
			 * Option: Related Posts title typography setting group
			 */
			array(
				'name'      => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-section-title-typography-group]',
				'type'      => 'control',
				'priority'  => 16,
				'control'   => 'ast-settings-group',
				'is_font'   => true,
				'context'   => array(
					SmartToolz_Builder_Helper::$design_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-title]',
						'operator' => '!=',
						'value'    => '',
					),
				),
				'title'     => __( 'Section Title Font', 'smarttoolz' ),
				'section'   => 'ast-sub-section-related-posts',
				'transport' => 'postMessage',
			),

			/**
			 * Option: Related Posts title typography setting group
			 */
			array(
				'name'      => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-title-typography-group]',
				'type'      => 'control',
				'priority'  => 17,
				'control'   => 'ast-settings-group',
				'context'   => array(
					SmartToolz_Builder_Helper::$design_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-structure]',
						'operator' => 'contains',
						'value'    => 'title-meta',
					),
				),
				'title'     => __( 'Post Title Font', 'smarttoolz' ),
				'is_font'   => true,
				'section'   => 'ast-sub-section-related-posts',
				'transport' => 'postMessage',
			),

			/**
			 * Option: Related Posts meta typography setting group
			 */
			array(
				'name'      => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-meta-typography-group]',
				'type'      => 'control',
				'priority'  => 18,
				'control'   => 'ast-settings-group',
				'context'   => array(
					SmartToolz_Builder_Helper::$design_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-structure]',
						'operator' => 'contains',
						'value'    => 'title-meta',
					),
				),
				'title'     => __( 'Meta Font', 'smarttoolz' ),
				'is_font'   => true,
				'section'   => 'ast-sub-section-related-posts',
				'transport' => 'postMessage',
			),

			/**
			 * Option: Related Posts content typography setting group
			 */
			array(
				'name'      => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-content-typography-group]',
				'type'      => 'control',
				'priority'  => 21,
				'control'   => 'ast-settings-group',
				'is_font'   => true,
				'context'   => array(
					SmartToolz_Builder_Helper::$design_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[enable-related-posts-excerpt]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'title'     => __( 'Content Font', 'smarttoolz' ),
				'section'   => 'ast-sub-section-related-posts',
				'transport' => 'postMessage',
			),

			/**
			 * Option: Related post block text color
			 */
			array(
				'name'      => 'related-posts-text-color',
				'tab'       => __( 'Normal', 'smarttoolz' ),
				'type'      => 'sub-control',
				'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-colors-group]',
				'section'   => 'ast-sub-section-related-posts',
				'default'   => smarttoolz_get_option( 'related-posts-text-color' ),
				'transport' => 'postMessage',
				'control'   => 'ast-color',
				'title'     => __( 'Text Color', 'smarttoolz' ),
			),

			/**
			 * Option: Related post block CTA link color
			 */
			array(
				'name'      => 'related-posts-link-color',
				'tab'       => __( 'Normal', 'smarttoolz' ),
				'type'      => 'sub-control',
				'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-colors-group]',
				'section'   => 'ast-sub-section-related-posts',
				'default'   => smarttoolz_get_option( 'related-posts-link-color' ),
				'transport' => 'postMessage',
				'control'   => 'ast-color',
				'title'     => __( 'Link Color', 'smarttoolz' ),
			),

			/**
			 * Option: Related post block BG color
			 */
			array(
				'name'              => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-title-color]',
				'default'           => smarttoolz_get_option( 'related-posts-title-color' ),
				'type'              => 'control',
				'control'           => 'ast-color',
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
				'section'           => 'ast-sub-section-related-posts',
				'transport'         => 'postMessage',
				'priority'          => 14,
				'context'           => array(
					SmartToolz_Builder_Helper::$design_tab_config,
					'relation' => 'AND',
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-title]',
						'operator' => '!=',
						'value'    => '',
					),
				),
				'title'             => __( 'Section Title', 'smarttoolz' ),
				'divider'           => array( 'ast_class' => 'ast-top-section-spacing' ),
			),

			/**
			 * Option: Related post block BG color
			 */
			array(
				'name'              => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-background-color]',
				'default'           => smarttoolz_get_option( 'related-posts-background-color' ),
				'type'              => 'control',
				'control'           => 'ast-color',
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_alpha_color' ),
				'section'           => 'ast-sub-section-related-posts',
				'transport'         => 'postMessage',
				'priority'          => 14,
				'context'           => array(
					SmartToolz_Builder_Helper::$design_tab_config,
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[enable-related-posts]',
						'operator' => '==',
						'value'    => true,
					),
				),
				'title'             => __( 'Section Background', 'smarttoolz' ),
				'divider'           => array( 'ast_class' => 'ast-bottom-divider' ),
			),

			/**
			 * Option: Related post meta color
			 */
			array(
				'name'      => 'related-posts-meta-color',
				'default'   => smarttoolz_get_option( 'related-posts-meta-color' ),
				'tab'       => __( 'Normal', 'smarttoolz' ),
				'type'      => 'sub-control',
				'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-colors-group]',
				'section'   => 'ast-sub-section-related-posts',
				'transport' => 'postMessage',
				'control'   => 'ast-color',
				'title'     => __( 'Meta Color', 'smarttoolz' ),
			),

			/**
			 * Option: Related hover CTA link color
			 */
			array(
				'name'      => 'related-posts-link-hover-color',
				'type'      => 'sub-control',
				'tab'       => __( 'Hover', 'smarttoolz' ),
				'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-colors-group]',
				'section'   => 'ast-sub-section-related-posts',
				'control'   => 'ast-color',
				'default'   => smarttoolz_get_option( 'related-posts-link-hover-color' ),
				'transport' => 'postMessage',
				'title'     => __( 'Link Color', 'smarttoolz' ),
			),

			/**
			 * Option: Related hover meta link color
			 */
			array(
				'name'      => 'related-posts-meta-link-hover-color',
				'type'      => 'sub-control',
				'tab'       => __( 'Hover', 'smarttoolz' ),
				'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-colors-group]',
				'section'   => 'ast-sub-section-related-posts',
				'control'   => 'ast-color',
				'default'   => smarttoolz_get_option( 'related-posts-meta-link-hover-color' ),
				'transport' => 'postMessage',
				'title'     => __( 'Meta Link Color', 'smarttoolz' ),
			),

			/**
			 * Option: Related Posts Title Font Family
			 */
			array(
				'name'      => 'related-posts-title-font-family',
				'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-title-typography-group]',
				'section'   => 'ast-sub-section-related-posts',
				'type'      => 'sub-control',
				'control'   => 'ast-font',
				'font_type' => 'ast-font-family',
				'default'   => smarttoolz_get_option( 'related-posts-title-font-family' ),
				'title'     => __( 'Font Family', 'smarttoolz' ),
				'connect'   => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-title-font-weight]',
				'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Title Font Weight
			 */
			array(
				'name'              => 'related-posts-title-font-weight',
				'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-title-typography-group]',
				'section'           => 'ast-sub-section-related-posts',
				'type'              => 'sub-control',
				'control'           => 'ast-font',
				'font_type'         => 'ast-font-weight',
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_font_weight' ),
				'default'           => smarttoolz_get_option( 'related-posts-title-font-weight' ),
				'title'             => __( 'Font Weight', 'smarttoolz' ),
				'connect'           => 'related-posts-title-font-family',
				'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Title Font Size
			 */

			array(
				'name'              => 'related-posts-title-font-size',
				'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-title-typography-group]',
				'section'           => 'ast-sub-section-related-posts',
				'type'              => 'sub-control',
				'control'           => 'ast-responsive-slider',
				'default'           => smarttoolz_get_option( 'related-posts-title-font-size' ),
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
			 * Option: Related Posts Title Font Extras
			 */
			array(
				'name'    => 'related-posts-title-font-extras',
				'type'    => 'sub-control',
				'parent'  => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-title-typography-group]',
				'control' => 'ast-font-extras',
				'section' => 'ast-sub-section-related-posts',
				'default' => smarttoolz_get_option( 'related-posts-title-font-extras' ),
				'title'   => __( 'Font Extras', 'smarttoolz' ),
			),

			/**
			 * Option: Related Posts Title Font Family
			 */
			array(
				'name'      => 'related-posts-section-title-font-family',
				'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-section-title-typography-group]',
				'section'   => 'ast-sub-section-related-posts',
				'type'      => 'sub-control',
				'control'   => 'ast-font',
				'font_type' => 'ast-font-family',
				'default'   => smarttoolz_get_option( 'related-posts-section-title-font-family' ),
				'title'     => __( 'Font Family', 'smarttoolz' ),
				'connect'   => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-section-title-font-weight]',
				'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Title Font Weight
			 */
			array(
				'name'              => 'related-posts-section-title-font-weight',
				'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-section-title-typography-group]',
				'section'           => 'ast-sub-section-related-posts',
				'type'              => 'sub-control',
				'control'           => 'ast-font',
				'font_type'         => 'ast-font-weight',
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_font_weight' ),
				'default'           => smarttoolz_get_option( 'related-posts-section-title-font-weight' ),
				'title'             => __( 'Font Weight', 'smarttoolz' ),
				'connect'           => 'related-posts-section-title-font-family',
				'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Title Font Size
			 */

			array(
				'name'              => 'related-posts-section-title-font-size',
				'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-section-title-typography-group]',
				'section'           => 'ast-sub-section-related-posts',
				'type'              => 'sub-control',
				'control'           => 'ast-responsive-slider',
				'default'           => smarttoolz_get_option( 'related-posts-section-title-font-size' ),
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
			 * Option: Related Posts Title Font Extras
			 */
			array(
				'name'    => 'related-posts-section-title-font-extras',
				'type'    => 'sub-control',
				'parent'  => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-section-title-typography-group]',
				'control' => 'ast-font-extras',
				'section' => 'ast-sub-section-related-posts',
				'default' => smarttoolz_get_option( 'related-posts-section-title-font-extras' ),
				'title'   => __( 'Font Extras', 'smarttoolz' ),
			),

			/**
			 * Option: Related Posts Meta Font Family
			 */
			array(
				'name'      => 'related-posts-meta-font-family',
				'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-meta-typography-group]',
				'section'   => 'ast-sub-section-related-posts',
				'type'      => 'sub-control',
				'control'   => 'ast-font',
				'font_type' => 'ast-font-family',
				'default'   => smarttoolz_get_option( 'related-posts-meta-font-family' ),
				'title'     => __( 'Font Family', 'smarttoolz' ),
				'connect'   => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-meta-font-weight]',
				'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Meta Font Weight
			 */
			array(
				'name'              => 'related-posts-meta-font-weight',
				'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-meta-typography-group]',
				'section'           => 'ast-sub-section-related-posts',
				'type'              => 'sub-control',
				'control'           => 'ast-font',
				'font_type'         => 'ast-font-weight',
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_font_weight' ),
				'default'           => smarttoolz_get_option( 'related-posts-meta-font-weight' ),
				'title'             => __( 'Font Weight', 'smarttoolz' ),
				'connect'           => 'related-posts-meta-font-family',
				'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Meta Font Size
			 */

			array(
				'name'              => 'related-posts-meta-font-size',
				'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-meta-typography-group]',
				'section'           => 'ast-sub-section-related-posts',
				'type'              => 'sub-control',
				'control'           => 'ast-responsive-slider',
				'default'           => smarttoolz_get_option( 'related-posts-meta-font-size' ),
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
			 * Option: Related Posts Meta Font Extras
			 */
			array(
				'name'    => 'related-posts-meta-font-extras',
				'type'    => 'sub-control',
				'parent'  => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-meta-typography-group]',
				'control' => 'ast-font-extras',
				'section' => 'ast-sub-section-related-posts',
				'default' => smarttoolz_get_option( 'related-posts-meta-font-extras' ),
				'title'   => __( 'Font Extras', 'smarttoolz' ),
			),

			/**
			 * Option: Related Posts Content Font Family
			 */
			array(
				'name'      => 'related-posts-content-font-family',
				'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-content-typography-group]',
				'section'   => 'ast-sub-section-related-posts',
				'type'      => 'sub-control',
				'control'   => 'ast-font',
				'font_type' => 'ast-font-family',
				'default'   => smarttoolz_get_option( 'related-posts-content-font-family' ),
				'title'     => __( 'Font Family', 'smarttoolz' ),
				'connect'   => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-content-font-weight]',
				'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Content Font Weight
			 */
			array(
				'name'              => 'related-posts-content-font-weight',
				'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-content-typography-group]',
				'section'           => 'ast-sub-section-related-posts',
				'type'              => 'sub-control',
				'control'           => 'ast-font',
				'font_type'         => 'ast-font-weight',
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_font_weight' ),
				'default'           => smarttoolz_get_option( 'related-posts-content-font-weight' ),
				'title'             => __( 'Font Weight', 'smarttoolz' ),
				'connect'           => 'related-posts-content-font-family',
				'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),
			),

			/**
			 * Option: Related Posts Content Font Size
			 */
			array(
				'name'              => 'related-posts-content-font-size',
				'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-content-typography-group]',
				'section'           => 'ast-sub-section-related-posts',
				'type'              => 'sub-control',
				'control'           => 'ast-responsive-slider',
				'default'           => smarttoolz_get_option( 'related-posts-content-font-size' ),
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
			 * Option: Related Posts Content Font Extras.
			 */
			/**
			 * Option: Related Posts Meta Font Extras
			 */
			array(
				'name'    => 'related-posts-content-font-extras',
				'type'    => 'sub-control',
				'parent'  => SMARTTOOLZ_THEME_SETTINGS . '[related-posts-content-typography-group]',
				'control' => 'ast-font-extras',
				'section' => 'ast-sub-section-related-posts',
				'default' => smarttoolz_get_option( 'related-posts-content-font-extras' ),
				'title'   => __( 'Font Extras', 'smarttoolz' ),
			),
		);

		$_configs = array_merge( $_configs, SmartToolz_Extended_Base_Configuration::prepare_section_spacing_border_options( 'ast-sub-section-related-posts' ) );

		return array_merge( $configurations, $_configs );
	}
}

/**
 *  Kicking this off by creating NEW instance.
 */
new SmartToolz_Related_Posts_Configs();
