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
class SmartToolz_Posts_Single_Structures_Configs extends SmartToolz_Customizer_Config_Base {
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
	 * Register Single Post's Structures Customizer Configurations.
	 *
	 * @param string $parent_section Section of dynamic customizer.
	 * @param string $post_type Post Type.
	 * @since 4.0.0
	 *
	 * @return array Customizer Configurations.
	 */
	public function get_layout_configuration( $parent_section, $post_type ) {
		return array(

			/**
			 * Option: Revamped Single Container Layout.
			 */
			array(
				'name'              => SMARTTOOLZ_THEME_SETTINGS . '[single-' . $post_type . '-ast-content-layout]',
				'type'              => 'control',
				'control'           => 'ast-radio-image',
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_choices' ),
				'section'           => $parent_section,
				'default'           => smarttoolz_get_option( 'single-' . $post_type . '-ast-content-layout', 'default' ),
				'priority'          => 3,
				'title'             => __( 'Container Layout', 'smarttoolz' ),
				'choices'           => $this->get_new_content_layout_choices( $post_type ),
				'divider'           => array( 'ast_class' => 'ast-top-divider' ),
			),

			/**
			 * Option: Single Content Style.
			 */
			array(
				'name'        => SMARTTOOLZ_THEME_SETTINGS . '[single-' . $post_type . '-content-style]',
				'type'        => 'control',
				'control'     => 'ast-selector',
				'section'     => $parent_section,
				'default'     => smarttoolz_get_option( 'single-' . $post_type . '-content-style', 'default' ),
				'priority'    => 3,
				'title'       => __( 'Container Style', 'smarttoolz' ),
				'description' => __( 'Container style will apply only when layout is set to either normal or narrow.', 'smarttoolz' ),
				'choices'     => array(
					'default' => __( 'Default', 'smarttoolz' ),
					'unboxed' => __( 'Unboxed', 'smarttoolz' ),
					'boxed'   => __( 'Boxed', 'smarttoolz' ),
				),
				'renderAs'    => 'text',
				'responsive'  => false,
				'divider'     => array( 'ast_class' => 'ast-top-divider' ),
			),

			/**
			 * Option: Single Sidebar Layout.
			 */
			array(
				'name'              => SMARTTOOLZ_THEME_SETTINGS . '[single-' . $post_type . '-sidebar-layout]',
				'type'              => 'control',
				'control'           => 'ast-radio-image',
				'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_choices' ),
				'section'           => $parent_section,
				'default'           => smarttoolz_get_option( 'single-' . $post_type . '-sidebar-layout', 'default' ),
				'description'       => __( 'Sidebar will only apply when container layout is set to normal.', 'smarttoolz' ),
				'priority'          => 3,
				'title'             => __( 'Sidebar Layout', 'smarttoolz' ),
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
			 * Option: Single Sidebar Style.
			 */
			array(
				'name'       => SMARTTOOLZ_THEME_SETTINGS . '[single-' . $post_type . '-sidebar-style]',
				'type'       => 'control',
				'control'    => 'ast-selector',
				'section'    => $parent_section,
				'default'    => smarttoolz_get_option( 'single-' . $post_type . '-sidebar-style', 'default' ),
				'priority'   => 3,
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
			/**
			 * Option: Single Page Content Width
			 */
			array(
				'name'       => SMARTTOOLZ_THEME_SETTINGS . '[page-single-width]',
				'type'       => 'control',
				'control'    => 'ast-selector',
				'section'    => $parent_section,
				'default'    => smarttoolz_get_option( 'page-single-width' ),
				'priority'   => 6,
				'title'      => __( 'Content Width', 'smarttoolz' ),
				'choices'    => array(
					'default' => __( 'Default', 'smarttoolz' ),
					'custom'  => __( 'Custom', 'smarttoolz' ),
				),
				'transport'  => 'postMessage',
				'responsive' => false,
				'divider'    => array( 'ast_class' => 'ast-top-section-divider' ),
				'renderAs'   => 'text',
			),

			/**
			 * Option: Enter Width
			 */
			array(
				'name'        => SMARTTOOLZ_THEME_SETTINGS . '[page-single-max-width]',
				'type'        => 'control',
				'control'     => 'ast-slider',
				'section'     => $parent_section,
				'transport'   => 'postMessage',
				'default'     => smarttoolz_get_option( 'page-single-max-width' ),
				'context'     => array(
					SmartToolz_Builder_Helper::$general_tab_config,
					array(
						'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[page-single-width]',
						'operator' => '===',
						'value'    => 'custom',
					),
				),
				'priority'    => 6,
				'title'       => __( 'Custom Width', 'smarttoolz' ),
				'suffix'      => 'px',
				'input_attrs' => array(
					'min'  => 0,
					'step' => 1,
					'max'  => 1920,
				),
				'divider'     => array( 'ast_class' => 'ast-top-divider' ),
			),
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
		foreach ( $post_types as $index => $post_type ) {

			$raw_taxonomies     = array_diff(
				get_object_taxonomies( $post_type ),
				array( 'post_format' )
			);
			$raw_taxonomies[''] = __( 'Select', 'smarttoolz' );

			// Filter out taxonomies in index-value format.
			$taxonomies = array();
			foreach ( $raw_taxonomies as $index => $value ) {
				/** @psalm-suppress PossiblyInvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				$tax_object = get_taxonomy( $value );
				/** @psalm-suppress PossiblyInvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

				// @codingStandardsIgnoreStart
				$tax_val    = is_object( $tax_object ) && ! empty( $tax_object->label ) ? $tax_object->label : $value;
				// @codingStandardsIgnoreEnd

				if ( '' === $index ) {
					$taxonomies[''] = $tax_val;
				} else {
					$taxonomies[ $value ] = $tax_val;
				}
			}
			/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			$taxonomies = array_reverse( $taxonomies );
			/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort

			$section                    = 'single-posttype-' . $post_type;
			$title_section              = 'ast-dynamic-single-' . $post_type;
			$post_type_object           = get_post_type_object( $post_type );
			$_structure_defaults        = 'page' === $post_type ? array( $title_section . '-image', $title_section . '-title' ) : array( $title_section . '-title', $title_section . '-meta' );
			$default_edd_featured_image = true === smarttoolz_enable_edd_featured_image_defaults();

			if ( 'product' === $post_type ) {
				$parent_section = 'section-woo-shop-single';
			} elseif ( 'post' === $post_type ) {
				$parent_section = 'section-blog-single';
			} elseif ( 'page' === $post_type ) {
				$parent_section = 'section-single-page';
			} elseif ( 'download' === $post_type ) {
				$parent_section        = 'section-edd-single';
				$_structure_defaults[] = $default_edd_featured_image ? $title_section . '-image' : '';
			} else {
				$parent_section = $section;
			}

			$structure_defaults = smarttoolz_get_option( $title_section . '-structure', $_structure_defaults );

			$meta_config_options = array();
			$clone_limit         = 0;
			/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			if ( count( $taxonomies ) > 1 ) {
				/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				$clone_limit = 3;
				$to_clone    = true;
				if ( absint( smarttoolz_get_option( $title_section . '-taxonomy-clone-tracker', 1 ) ) === $clone_limit ) {
					$to_clone = false;
				}
				$meta_config_options[ $title_section . '-taxonomy' ]   = array(
					'clone'         => $to_clone,
					'is_parent'     => true,
					'main_index'    => $title_section . '-taxonomy',
					'clone_limit'   => $clone_limit,
					'clone_tracker' => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-taxonomy-clone-tracker]',
					'title'         => __( 'Taxonomies', 'smarttoolz' ),
				);
				$meta_config_options[ $title_section . '-taxonomy-1' ] = array(
					'clone'         => $to_clone,
					'is_parent'     => true,
					'main_index'    => $title_section . '-taxonomy',
					'clone_limit'   => $clone_limit,
					'clone_tracker' => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-taxonomy-clone-tracker]',
					'title'         => __( 'Taxonomies', 'smarttoolz' ),
				);
				$meta_config_options[ $title_section . '-taxonomy-2' ] = array(
					'clone'         => $to_clone,
					'is_parent'     => true,
					'main_index'    => $title_section . '-taxonomy',
					'clone_limit'   => $clone_limit,
					'clone_tracker' => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-taxonomy-clone-tracker]',
					'title'         => __( 'Taxonomies', 'smarttoolz' ),
				);
			}
			$meta_config_options['date']   = array(
				'clone'       => false,
				'is_parent'   => true,
				'main_index'  => 'date',
				'clone_limit' => 1,
				'title'       => __( 'Date', 'smarttoolz' ),
			);
			$meta_config_options['author'] = array(
				'clone'       => false,
				'is_parent'   => true,
				'main_index'  => 'author',
				'clone_limit' => 1,
				'title'       => __( 'Author', 'smarttoolz' ),
			);

			// Display Read Time option in Meta options only when SmartToolz Addon is activated.
			/** @psalm-suppress UndefinedClass */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			if ( defined( 'SMARTTOOLZ_EXT_VER' ) && SmartToolz_Ext_Extension::is_active( 'blog-pro' ) ) {
				$meta_config_options['read-time'] = __( 'Read Time', 'smarttoolz' );
			}

			$structure_sub_controls                             = array();
			$structure_sub_controls[ $title_section . '-meta' ] = array(
				'clone'       => false,
				'is_parent'   => true,
				'main_index'  => $title_section . '-meta',
				'clone_limit' => 2,
				'title'       => __( 'Meta', 'smarttoolz' ),
			);
			// Add featured as background sub-control.
			$structure_sub_controls[ $title_section . '-image' ] = array(
				'clone'       => false,
				'is_parent'   => true,
				'main_index'  => $title_section . '-image',
				'clone_limit' => 2,
				'title'       => __( 'Featured Image', 'smarttoolz' ),
			);
			// Add taxonomy in structural sub-control.
			$structure_sub_controls[ $title_section . '-str-taxonomy' ] = array(
				'clone'       => false,
				'is_parent'   => true,
				'main_index'  => $title_section . '-str-taxonomy',
				'clone_limit' => 2,
				'title'       => __( 'Taxonomies', 'smarttoolz' ),
			);

			$configurations = array_merge( $configurations, $this->get_layout_configuration( $parent_section, $post_type ) );

			// Conditional tooltip.
			$default_tooltip = __( "'None' respects hierarchy; 'Behind' positions the image under the article.", 'smarttoolz' );
			$tooltip_product = __( "'None' respects hierarchy; 'Behind' position is not applicable for single product page.", 'smarttoolz' );

			$second_layout_default_tooltip = __( "'None' respects hierarchy; 'Below' positions image on top of the article.", 'smarttoolz' );
			$second_layout_tooltip_product = __( "'None' respects hierarchy; 'Below' position is not applicable for single product page.", 'smarttoolz' );

			// Added check if current panel is for the single product option.
			$tooltip_description               = $parent_section === 'section-woo-shop-single' ? $tooltip_product : $default_tooltip;
			$second_layout_tooltip_description = $parent_section === 'section-woo-shop-single' ? $second_layout_tooltip_product : $second_layout_default_tooltip;

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
					'title'    => $this->get_dynamic_section_title( $post_type_object, $post_type ),
					// @codingStandardsIgnoreEnd
					'type'     => 'section',
					'section'  => $parent_section,
					'panel'    => 'product' === $post_type ? 'woocommerce' : '',
					'priority' => 1,
				),

				array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[ast-single-' . $post_type . '-title]',
					'type'     => 'control',
					'default'  => smarttoolz_get_option( 'ast-single-' . $post_type . '-title', class_exists( 'WooCommerce' ) && 'product' === $post_type ? false : true ),
					'control'  => 'ast-section-toggle',
					'section'  => $parent_section,
					'priority' => 2,
					'linked'   => $title_section,
					'linkText' => $this->get_dynamic_section_title( $post_type_object, $post_type ),
					'divider'  => array( 'ast_class' => 'ast-bottom-divider ast-bottom-section-divider' ),
				),

				/**
				 * Layout option.
				 */
				array(
					'name'                   => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-layout]',
					'type'                   => 'control',
					'control'                => 'ast-radio-image',
					'sanitize_callback'      => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_choices' ),
					'section'                => $title_section,
					'default'                => smarttoolz_get_option( $title_section . '-layout', 'layout-1' ),
					'priority'               => 5,
					'context'                => SmartToolz_Builder_Helper::$general_tab,
					'title'                  => __( 'Banner Layout', 'smarttoolz' ),
					'divider'                => array( 'ast_class' => 'ast-section-spacing' ),
					'choices'                => array(
						'layout-1' => array(
							'label' => __( 'Layout 1', 'smarttoolz' ),
							'path'  => SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'banner-layout-1' ),
						),
						'layout-2' => array(
							'label' => __( 'Layout 2', 'smarttoolz' ),
							'path'  => SmartToolz_Builder_UI_Controller::fetch_svg_icon( 'banner-layout-2' ),
						),
					),
					'contextual_sub_control' => true,
					'input_attrs'            => array(
						'dependents' => array(
							'layout-1' => array( $title_section . '-empty-layout-message', $title_section . '-article-featured-image-position-layout-1', $title_section . '-article-featured-image-width-type', $title_section . '-remove-featured-padding' ),
							'layout-2' => array( $title_section . '-featured-as-background', $title_section . '-banner-featured-overlay', $title_section . '-image-position', $title_section . '-featured-help-notice', $title_section . '-article-featured-image-position-layout-2' ),
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
					'default'           => $structure_defaults,
					'priority'          => 20,
					'title'             => __( 'Structure', 'smarttoolz' ),
					'divider'           => array( 'ast_class' => 'ast-top-divider' ),
					'choices'           => array_merge(
						array(
							$title_section . '-title'      => __( 'Title', 'smarttoolz' ),
							$title_section . '-breadcrumb' => __( 'Breadcrumb', 'smarttoolz' ),
							$title_section . '-excerpt'    => __( 'Excerpt', 'smarttoolz' ),
						),
						$structure_sub_controls
					),
				),

				array(
					'name'        => $title_section . '-article-featured-image-position-layout-1',
					'parent'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'type'        => 'sub-control',
					'control'     => 'ast-selector',
					'section'     => $title_section,
					'default'     => smarttoolz_get_option( $title_section . '-article-featured-image-position-layout-1', 'behind' ),
					'priority'    => 28,
					'linked'      => $title_section . '-image',
					'transport'   => 'postMessage',
					'title'       => __( 'Image Position', 'smarttoolz' ),
					'choices'     => array(
						'none'   => __( 'None', 'smarttoolz' ),
						'behind' => __( 'Behind', 'smarttoolz' ),
					),
					'description' => $tooltip_description,
					'responsive'  => false,
					'renderAs'    => 'text',
				),
				array(
					'name'        => $title_section . '-article-featured-image-position-layout-2',
					'type'        => 'sub-control',
					'control'     => 'ast-selector',
					'parent'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'linked'      => $title_section . '-image',
					'transport'   => 'postMessage',
					'section'     => $title_section,
					'default'     => smarttoolz_get_option( $title_section . '-article-featured-image-position-layout-2', 'none' ),
					'priority'    => 28,
					'title'       => __( 'Image Position', 'smarttoolz' ),
					'choices'     => array(
						'none'  => __( 'None', 'smarttoolz' ),
						'below' => __( 'Below', 'smarttoolz' ),
					),
					'description' => $second_layout_tooltip_description,
					'responsive'  => false,
					'renderAs'    => 'text',
				),
				array(
					'name'       => $title_section . '-article-featured-image-width-type',
					'type'       => 'sub-control',
					'control'    => 'ast-selector',
					'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'linked'     => $title_section . '-image',
					'transport'  => 'postMessage',
					'section'    => $title_section,
					'default'    => smarttoolz_get_option( $title_section . '-article-featured-image-width-type', 'wide' ),
					'priority'   => 28,
					'title'      => __( 'Behind Positioned Image Width', 'smarttoolz' ),
					'choices'    => array(
						'wide' => __( 'Wide', 'smarttoolz' ),
						'full' => __( 'Full Width', 'smarttoolz' ),
					),
					'responsive' => false,
					'divider'    => array( 'ast_class' => 'ast-section-spacing' ),
					'renderAs'   => 'text',
				),
				array(
					'name'                   => $title_section . '-article-featured-image-ratio-type',
					'default'                => smarttoolz_get_option( $title_section . '-article-featured-image-ratio-type', 'predefined' ),
					'type'                   => 'sub-control',
					'section'                => $title_section,
					'parent'                 => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'linked'                 => $title_section . '-image',
					'transport'              => 'postMessage',
					'priority'               => 28,
					'control'                => 'ast-selector',
					'title'                  => __( 'Image Ratio', 'smarttoolz' ),
					'choices'                => array(
						'default'    => __( 'Original', 'smarttoolz' ),
						'predefined' => __( 'Predefined', 'smarttoolz' ),
						'custom'     => __( 'Custom', 'smarttoolz' ),
					),
					'contextual_sub_control' => true,
					'input_attrs'            => array(
						'dependents' => array(
							'default'    => array(),
							'predefined' => array( $title_section . '-article-featured-image-ratio-pre-scale' ),
							'custom'     => array( $title_section . '-article-featured-image-custom-scale-width', $title_section . '-article-featured-image-custom-scale-height', $title_section . '-article-featured-image-custom-scale-description' ),
						),
					),
					'divider'                => array( 'ast_class' => 'ast-top-divider' ),
					'responsive'             => false,
					'renderAs'               => 'text',
				),
				array(
					'name'       => $title_section . '-article-featured-image-ratio-pre-scale',
					'default'    => smarttoolz_get_option( $title_section . '-article-featured-image-ratio-pre-scale', '16/9' ),
					'type'       => 'sub-control',
					'section'    => $title_section,
					'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'linked'     => $title_section . '-image',
					'transport'  => 'postMessage',
					'priority'   => 28,
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
					'name'              => $title_section . '-article-featured-image-custom-scale-width',
					'default'           => smarttoolz_get_option( $title_section . '-article-featured-image-custom-scale-width', 16 ),
					'type'              => 'sub-control',
					'control'           => 'ast-number',
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'linked'            => $title_section . '-image',
					'transport'         => 'postMessage',
					'section'           => $title_section,
					'priority'          => 28,
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
					'name'              => $title_section . '-article-featured-image-custom-scale-height',
					'default'           => smarttoolz_get_option( $title_section . '-article-featured-image-custom-scale-height', 9 ),
					'type'              => 'sub-control',
					'control'           => 'ast-number',
					'transport'         => 'postMessage',
					'section'           => $title_section,
					'priority'          => 28,
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'linked'            => $title_section . '-image',
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
					'name'      => $title_section . '-article-featured-image-custom-scale-description',
					'type'      => 'sub-control',
					'transport' => 'postMessage',
					'control'   => 'ast-description',
					'section'   => $title_section,
					'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'linked'    => $title_section . '-image',
					'priority'  => 28,
					'label'     => '',
					'help'      => sprintf( /* translators: 1: link open markup, 2: link close markup */ __( 'Calculate a personalized image ratio using this %1$s online tool %2$s for your image dimensions.', 'smarttoolz' ), '<a href="' . esc_url( 'https://www.digitalrebellion.com/webapps/aspectcalc' ) . '" target="_blank">', '</a>' ),
				),
				array(
					'name'        => $title_section . '-article-featured-image-size',
					'default'     => smarttoolz_get_option( $title_section . '-article-featured-image-size', 'large' ),
					'section'     => $title_section,
					'transport'   => 'postMessage',
					'type'        => 'sub-control',
					'parent'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'linked'      => $title_section . '-image',
					'priority'    => 28,
					'title'       => __( 'Image Size', 'smarttoolz' ),
					'divider'     => array( 'ast_class' => 'ast-top-divider' ),
					'control'     => 'ast-select',
					'choices'     => smarttoolz_get_site_image_sizes( true ),
					'description' => defined( 'SMARTTOOLZ_EXT_VER' ) ? __( "You can specify Custom image sizes from the Single Post's 'Featured Image Size' option.", 'smarttoolz' ) : '',
				),

				array(
					'name'        => $title_section . '-remove-featured-padding',
					'parent'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'default'     => smarttoolz_get_option( $title_section . '-remove-featured-padding', false ),
					'linked'      => $title_section . '-image',
					'type'        => 'sub-control',
					'control'     => 'ast-toggle',
					'section'     => $title_section,
					'divider'     => array( 'ast_class' => 'ast-section-spacing' ),
					'priority'    => 28,
					'title'       => __( 'Remove Image Padding', 'smarttoolz' ),
					'description' => __( 'Remove the padding around featured image when position is "None".', 'smarttoolz' ),
					'transport'   => 'postMessage',
				),

				array(
					'name'      => $title_section . '-featured-as-background',
					'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'default'   => smarttoolz_get_option( $title_section . '-featured-as-background', false ),
					'linked'    => $title_section . '-image',
					'type'      => 'sub-control',
					'control'   => 'ast-toggle',
					'section'   => $title_section,
					'divider'   => array( 'ast_class' => 'ast-section-spacing ast-top-divider' ),
					'priority'  => 28,
					'title'     => __( 'Use as Background', 'smarttoolz' ),
					'transport' => 'postMessage',
				),
				array(
					'name'     => $title_section . '-banner-featured-overlay',
					'parent'   => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'default'  => smarttoolz_get_option( $title_section . '-banner-featured-overlay', '' ),
					'linked'   => $title_section . '-image',
					'type'     => 'sub-control',
					'control'  => 'ast-color',
					'section'  => $title_section,
					'priority' => 28,
					'title'    => __( 'Overlay Color', 'smarttoolz' ),
					'context'  => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-layout]',
							'operator' => '===',
							'value'    => 'layout-2',
						),
					),
				),

				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-taxonomy-clone-tracker]',
					'section'   => $title_section,
					'type'      => 'control',
					'control'   => 'ast-hidden',
					'priority'  => 22,
					'transport' => 'postMessage',
					'partial'   => false,
					'default'   => smarttoolz_get_option( $title_section . '-taxonomy-clone-tracker', 1 ),
				),

				array(
					'name'      => $title_section . '-structural-taxonomy',
					'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'default'   => smarttoolz_get_option( $title_section . '-structural-taxonomy' ),
					'linked'    => $title_section . '-str-taxonomy',
					'type'      => 'sub-control',
					'control'   => 'ast-select',
					'transport' => 'refresh',
					'section'   => $title_section,
					'priority'  => 1,
					'title'     => __( 'Taxonomy', 'smarttoolz' ),
					'choices'   => $taxonomies,
				),

				array(
					'name'       => $title_section . '-structural-taxonomy-style',
					'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'type'       => 'sub-control',
					'control'    => 'ast-selector',
					'section'    => $title_section,
					'default'    => smarttoolz_get_option( $title_section . '-structural-taxonomy-style', '' ),
					'priority'   => 2,
					'linked'     => $title_section . '-str-taxonomy',
					'transport'  => 'refresh',
					'title'      => __( 'Style', 'smarttoolz' ),
					'choices'    => array(
						''          => __( 'Default', 'smarttoolz' ),
						'badge'     => __( 'Badge', 'smarttoolz' ),
						'underline' => __( 'Underline', 'smarttoolz' ),
					),
					'divider'    => array( 'ast_class' => 'ast-top-divider' ),
					'responsive' => false,
					'renderAs'   => 'text',
				),

				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-metadata]',
					'type'              => 'control',
					'control'           => 'ast-sortable',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_multi_choices' ),
					'default'           => smarttoolz_get_option( $title_section . '-metadata', array( 'comments', 'author', 'date' ) ),
					'context'           => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-structure]',
							'operator' => 'contains',
							'value'    => $title_section . '-meta',
						),
					),
					'section'           => $title_section,
					'priority'          => 25,
					'title'             => __( 'Meta', 'smarttoolz' ),
					'choices'           => array_merge(
						array(
							'comments' => __( 'Comments', 'smarttoolz' ),
						),
						$meta_config_options
					),
				),

				/**
				 * Option: Author Prefix Label.
				 */
				array(
					'name'      => $title_section . '-author-prefix-label',
					'default'   => smarttoolz_get_option( $title_section . '-author-prefix-label', smarttoolz_default_strings( 'string-blog-meta-author-by', false ) ),
					'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-metadata]',
					'linked'    => 'author',
					'type'      => 'sub-control',
					'control'   => 'ast-text-input',
					'section'   => $title_section,
					'divider'   => array( 'ast_class' => 'ast-bottom-divider ast-bottom-section-spacing' ),
					'title'     => __( 'Prefix Label', 'smarttoolz' ),
					'priority'  => 1,
					'transport' => 'postMessage',
				),

				/**
				 * Option: Author Avatar.
				 */
				array(
					'name'      => $title_section . '-author-avatar',
					'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-metadata]',
					'default'   => smarttoolz_get_option( $title_section . '-author-avatar', false ),
					'linked'    => 'author',
					'type'      => 'sub-control',
					'control'   => 'ast-toggle',
					'section'   => $title_section,
					'priority'  => 5,
					'title'     => __( 'Author Avatar', 'smarttoolz' ),
					'transport' => 'postMessage',
				),

				/**
				 * Option: Author Avatar Width.
				 */
				array(
					'name'        => $title_section . '-author-avatar-size',
					'parent'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-metadata]',
					'default'     => smarttoolz_get_option( $title_section . '-author-avatar-size', 30 ),
					'linked'      => 'author',
					'type'        => 'sub-control',
					'control'     => 'ast-slider',
					'transport'   => 'postMessage',
					'section'     => $title_section,
					'priority'    => 10,
					'title'       => __( 'Image Size', 'smarttoolz' ),
					'suffix'      => 'px',
					'input_attrs' => array(
						'min'  => 1,
						'step' => 1,
						'max'  => 200,
					),
				),

				/**
				 * Option: Date Meta Type.
				 */
				array(
					'name'       => $title_section . '-meta-date-type',
					'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-metadata]',
					'type'       => 'sub-control',
					'control'    => 'ast-selector',
					'section'    => $title_section,
					'default'    => smarttoolz_get_option( $title_section . '-meta-date-type', 'published' ),
					'priority'   => 1,
					'linked'     => 'date',
					'transport'  => 'refresh',
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
					'name'       => $title_section . '-date-format',
					'default'    => smarttoolz_get_option( $title_section . '-date-format', '' ),
					'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-metadata]',
					'linked'     => 'date',
					'type'       => 'sub-control',
					'control'    => 'ast-select',
					'section'    => $title_section,
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
				 * Option: Meta Data Separator.
				 */
				array(
					'name'              => $title_section . '-metadata-separator',
					'default'           => smarttoolz_get_option( $title_section . '-metadata-separator', '/' ),
					'type'              => 'sub-control',
					'transport'         => 'postMessage',
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-structure]',
					'linked'            => $title_section . '-meta',
					'section'           => $title_section,
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

				/**
				 * Option: Horizontal Alignment.
				 */
				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-horizontal-alignment]',
					'default'   => smarttoolz_get_option( $title_section . '-horizontal-alignment' ),
					'type'      => 'control',
					'control'   => 'ast-selector',
					'section'   => $title_section,
					'priority'  => 29,
					'title'     => __( 'Horizontal Alignment', 'smarttoolz' ),
					'context'   => SmartToolz_Builder_Helper::$general_tab,
					'transport' => 'postMessage',
					'choices'   => array(
						'left'   => 'align-left',
						'center' => 'align-center',
						'right'  => 'align-right',
					),
					'divider'   => array( 'ast_class' => 'ast-top-section-divider' ),
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
					'priority'   => 30,
					'title'      => __( 'Vertical Alignment', 'smarttoolz' ),
					'choices'    => array(
						'flex-start' => __( 'Top', 'smarttoolz' ),
						'center'     => __( 'Middle', 'smarttoolz' ),
						'flex-end'   => __( 'Bottom', 'smarttoolz' ),
					),
					'divider'    => array( 'ast_class' => 'ast-top-divider ast-section-spacing ast-bottom-section-divider' ),
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
					'priority'          => 2,
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
					'divider'     => array( 'ast_class' => 'ast-bottom-divider ast-bottom-spacing ast-section-spacing' ),
				),

				/**
				 * Option: Featured Image Custom Banner BG.
				 */
				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-background]',
					'type'      => 'control',
					'default'   => smarttoolz_get_option( $title_section . '-banner-background', SmartToolz_Posts_Structure_Loader::get_customizer_default( 'responsive-background' ) ),
					'section'   => $title_section,
					'control'   => 'ast-responsive-background',
					'title'     => __( 'Background', 'smarttoolz' ),
					'transport' => 'postMessage',
					'context'   => array(
						SmartToolz_Builder_Helper::$design_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-featured-as-background]',
							'operator' => '!=',
							'value'    => true,
						),
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-layout]',
							'operator' => '===',
							'value'    => 'layout-2',
						),
					),
					'priority'  => 5,
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
					'priority'  => 5,
					'title'     => __( 'Title Color', 'smarttoolz' ),
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
					'divider'   => array( 'ast_class' => 'ast-bottom-spacing' ),
					'context'   => SmartToolz_Builder_Helper::$design_tab,
				),

				array(
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-title-typography-group]',
					'type'      => 'control',
					'priority'  => 25,
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
					'priority'  => 30,
					'control'   => 'ast-settings-group',
					'context'   => SmartToolz_Builder_Helper::$design_tab,
					'title'     => __( 'Text Font', 'smarttoolz' ),
					'is_font'   => true,
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
				 * Option: Single Post Banner Text Font Extras
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
				 * Option: Single Post Banner Title Font Extras
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
					'name'      => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-meta-typography-group]',
					'type'      => 'control',
					'priority'  => 35,
					'control'   => 'ast-settings-group',
					'context'   => array(
						SmartToolz_Builder_Helper::$design_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-structure]',
							'operator' => 'contains',
							'value'    => $title_section . '-meta',
						),
					),
					'title'     => __( 'Meta Font', 'smarttoolz' ),
					'is_font'   => true,
					'divider'   => array( 'ast_class' => 'ast-bottom-spacing' ),
					'section'   => $title_section,
					'transport' => 'postMessage',
				),

				/**
				 * Option: Meta Font Family
				 */
				array(
					'name'      => $title_section . '-meta-font-family',
					'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-meta-typography-group]',
					'section'   => $title_section,
					'type'      => 'sub-control',
					'control'   => 'ast-font',
					'font_type' => 'ast-font-family',
					'default'   => smarttoolz_get_option( $title_section . '-meta-font-family', 'inherit' ),
					'title'     => __( 'Font Family', 'smarttoolz' ),
					'connect'   => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-meta-font-weight]',
					'divider'   => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				),

				/**
				 * Option: Meta Font Weight
				 */
				array(
					'name'              => $title_section . '-meta-font-weight',
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-meta-typography-group]',
					'section'           => $title_section,
					'type'              => 'sub-control',
					'control'           => 'ast-font',
					'font_type'         => 'ast-font-weight',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_font_weight' ),
					'default'           => smarttoolz_get_option( $title_section . '-meta-font-weight', 'inherit' ),
					'title'             => __( 'Font Weight', 'smarttoolz' ),
					'connect'           => $title_section . '-meta-font-family',
					'divider'           => array( 'ast_class' => 'ast-sub-bottom-divider' ),
				),

				/**
				 * Option: Meta Font Size
				 */
				array(
					'name'              => $title_section . '-meta-font-size',
					'parent'            => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-meta-typography-group]',
					'section'           => $title_section,
					'type'              => 'sub-control',
					'control'           => 'ast-responsive-slider',
					'default'           => smarttoolz_get_option( $title_section . '-meta-font-size', SmartToolz_Posts_Structure_Loader::get_customizer_default( 'font-size' ) ),
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
				 * Option: Single Post Banner Title Font Extras
				 */
				array(
					'name'    => $title_section . '-meta-font-extras',
					'parent'  => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-meta-typography-group]',
					'section' => $title_section,
					'type'    => 'sub-control',
					'control' => 'ast-font-extras',
					'default' => smarttoolz_get_option( $title_section . '-meta-font-extras', SmartToolz_Posts_Structure_Loader::get_customizer_default( 'font-extras' ) ),
					'title'   => __( 'Font Extras', 'smarttoolz' ),
				),

				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-margin]',
					'default'           => smarttoolz_get_option( $title_section . '-banner-margin', SmartToolz_Posts_Structure_Loader::get_customizer_default( 'responsive-spacing' ) ),
					'type'              => 'control',
					'control'           => 'ast-responsive-spacing',
					'sanitize_callback' => array( 'SmartToolz_Customizer_Sanitizes', 'sanitize_responsive_spacing' ),
					'section'           => $title_section,
					'title'             => __( 'Margin', 'smarttoolz' ),
					'linked_choices'    => true,
					'transport'         => 'postMessage',
					'divider'           => array( 'ast_class' => 'ast-top-divider' ),
					'unit_choices'      => array( 'px', 'em', '%' ),
					'choices'           => array(
						'top'    => __( 'Top', 'smarttoolz' ),
						'right'  => __( 'Right', 'smarttoolz' ),
						'bottom' => __( 'Bottom', 'smarttoolz' ),
						'left'   => __( 'Left', 'smarttoolz' ),
					),
					'context'           => array(
						SmartToolz_Builder_Helper::$design_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-layout]',
							'operator' => '===',
							'value'    => 'layout-2',
						),
					),
					'priority'          => 100,
					'connected'         => false,
				),

				array(
					'name'              => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-banner-padding]',
					'default'           => smarttoolz_get_option( $title_section . '-banner-padding', SmartToolz_Posts_Structure_Loader::get_customizer_default( 'responsive-padding' ) ),
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
					'context'           => array(
						SmartToolz_Builder_Helper::$design_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-layout]',
							'operator' => '===',
							'value'    => 'layout-2',
						),
					),
					'priority'          => 120,
					'connected'         => false,
				),
			);

			if ( 'page' === $post_type ) {

				/**
				 * Option: Disable structure and meta on the front page.
				 */
				$_configs[] = array(
					'name'     => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-disable-structure-meta-on-front-page]',
					'default'  => smarttoolz_get_option( $title_section . '-disable-structure-meta-on-front-page', false ),
					'type'     => 'control',
					'section'  => $title_section,
					'context'  => array(
						SmartToolz_Builder_Helper::$general_tab_config,
						'relation' => 'AND',
						array(
							'setting'  => 'show_on_front',
							'operator' => '===',
							'value'    => 'page',
						),
					),
					'title'    => __( 'Disable on Front Page?', 'smarttoolz' ),
					'priority' => 5,
					'control'  => 'ast-toggle-control',
					'divider'  => array( 'ast_class' => 'ast-top-divider' ),
				);
			}

			if ( 'post' !== $post_type && 'product' !== $post_type ) {
				$_configs[] = array(
					'name'        => $title_section . '-parent-ast-context-tabs',
					'section'     => $parent_section,
					'type'        => 'control',
					'control'     => 'ast-builder-header-control',
					'priority'    => 0,
					'description' => '',
				);
			}

			if ( 'post' !== $post_type && SmartToolz_Builder_Helper::$is_header_footer_builder_active ) {
				$class    = $post_type === 'product' ? 'ast-top-section-divider' : 'ast-top-section-spacing';
				$_configs = array_merge( $_configs, SmartToolz_Extended_Base_Configuration::prepare_advanced_tab( $parent_section, $class ) );
			}

			/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
			if ( count( $taxonomies ) > 1 ) {
				/** @psalm-suppress InvalidArgument */ // phpcs:ignore Generic.Commenting.DocComment.MissingShort
				for ( $index = 1; $index <= $clone_limit; $index++ ) {

					$control_suffix = 1 === $index ? '' : '-' . ( $index - 1 );

					/**
					 * Option: Taxonomy Selection.
					 */
					$_configs[] = array(
						'name'      => $title_section . '-taxonomy' . $control_suffix,
						'parent'    => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-metadata]',
						'default'   => smarttoolz_get_option( $title_section . '-taxonomy' . $control_suffix ),
						'linked'    => $title_section . '-taxonomy' . $control_suffix,
						'type'      => 'sub-control',
						'control'   => 'ast-select',
						'transport' => 'refresh',
						'section'   => $title_section,
						'priority'  => 5,
						'title'     => __( 'Taxonomy', 'smarttoolz' ),
						'choices'   => $taxonomies,
					);
					$_configs[] = array(
						'name'       => $title_section . '-taxonomy' . $control_suffix . '-style',
						'parent'     => SMARTTOOLZ_THEME_SETTINGS . '[' . $title_section . '-metadata]',
						'default'    => smarttoolz_get_option( $title_section . '-taxonomy' . $control_suffix . '-style', '' ),
						'linked'     => $title_section . '-taxonomy' . $control_suffix,
						'type'       => 'sub-control',
						'control'    => 'ast-selector',
						'section'    => $title_section,
						'priority'   => 10,
						'transport'  => 'refresh',
						'title'      => __( 'Style', 'smarttoolz' ),
						'choices'    => array(
							''          => __( 'Default', 'smarttoolz' ),
							'badge'     => __( 'Badge', 'smarttoolz' ),
							'underline' => __( 'Underline', 'smarttoolz' ),
						),
						'divider'    => array( 'ast_class' => 'ast-top-divider' ),
						'responsive' => false,
						'renderAs'   => 'text',
					);
				}
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
			$title = isset( $post_type_object->labels->singular_name ) ? ucfirst( $post_type_object->labels->singular_name ) : ucfirst( $post_type );
		} else {
			$title = __( 'Single Banner', 'smarttoolz' );
		}
		return apply_filters( 'smarttoolz_single_post_title', $title . __( ' Title Area', 'smarttoolz' ), $post_type );
	}
}

/**
 * Kicking this off by creating new object.
 */
new SmartToolz_Posts_Single_Structures_Configs();
