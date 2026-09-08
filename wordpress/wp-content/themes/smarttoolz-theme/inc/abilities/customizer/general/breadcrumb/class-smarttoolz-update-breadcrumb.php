<?php
/**
 * Update Breadcrumb Settings Ability
 *
 * @package SmartToolz
 * @subpackage Abilities
 * @since 4.12.6
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class SmartToolz_Update_Breadcrumb
 */
class SmartToolz_Update_Breadcrumb extends SmartToolz_Abstract_Ability {
	/**
	 * Configure the ability.
	 *
	 * @return void
	 */
	public function configure() {
		$this->id          = 'smarttoolz/update-breadcrumb';
		$this->category    = 'smarttoolz';
		$this->label       = __( 'Update SmartToolz Breadcrumb Settings', 'smarttoolz' );
		$this->description = __( 'Updates the SmartToolz theme breadcrumb settings including position, alignment, display settings, separator, typography, colors, and spacing.', 'smarttoolz' );

		$this->meta = array(
			'tool_type' => 'write',
		);
	}

	/**
	 * Get tool type.
	 *
	 * @return string
	 */
	public function get_tool_type() {
		return 'write';
	}

	/**
	 * Get input schema.
	 *
	 * @return array
	 */
	public function get_input_schema() {
		return array(
			'type'       => 'object',
			'properties' => array(
				'position'         => array(
					'type'        => 'string',
					'description' => 'Breadcrumb position. Options: "none" (None), "smarttoolz_header_primary_container_after" (Inside), "smarttoolz_header_after" (After), "smarttoolz_entry_top" (Before Title).',
					'enum'        => array( 'none', 'smarttoolz_header_primary_container_after', 'smarttoolz_header_after', 'smarttoolz_entry_top' ),
				),
				'alignment'        => array(
					'type'        => 'string',
					'description' => 'Breadcrumb alignment. Options: "left", "center", "right".',
					'enum'        => array( 'left', 'center', 'right' ),
				),
				'separator_type'   => array(
					'type'        => 'string',
					'description' => 'Breadcrumb separator type. Options: "\003E" (>), "\00BB" (»), "\002F" (/), "unicode" (Custom).',
					'enum'        => array( '\003E', '\00BB', '\002F', 'unicode' ),
				),
				'separator_custom' => array(
					'type'        => 'string',
					'description' => 'Custom breadcrumb separator text (only applies when separator_type is "unicode").',
				),
				'enable_on'        => array(
					'type'        => 'object',
					'description' => 'Enable breadcrumb on specific page types. Each property is a boolean.',
					'properties'  => array(
						'home_page'   => array(
							'type'        => 'boolean',
							'description' => 'Enable on home page',
						),
						'blog_page'   => array(
							'type'        => 'boolean',
							'description' => 'Enable on blog/posts page',
						),
						'search'      => array(
							'type'        => 'boolean',
							'description' => 'Enable on search results',
						),
						'archive'     => array(
							'type'        => 'boolean',
							'description' => 'Enable on archive pages',
						),
						'single_page' => array(
							'type'        => 'boolean',
							'description' => 'Enable on single pages',
						),
						'single_post' => array(
							'type'        => 'boolean',
							'description' => 'Enable on single posts',
						),
						'singular'    => array(
							'type'        => 'boolean',
							'description' => 'Enable on singular (all pages, posts, attachments)',
						),
						'404_page'    => array(
							'type'        => 'boolean',
							'description' => 'Enable on 404 page',
						),
					),
				),
				'typography'       => array(
					'type'        => 'object',
					'description' => 'Typography settings for breadcrumb.',
					'properties'  => array(
						'font_family' => array(
							'type'        => 'string',
							'description' => 'Font family',
						),
						'font_weight' => array(
							'type'        => 'string',
							'description' => 'Font weight',
						),
						'font_size'   => array(
							'type'        => 'object',
							'description' => 'Responsive font size with desktop, tablet, mobile keys',
						),
						'font_extras' => array(
							'type'        => 'object',
							'description' => 'Font extras with line-height, text-transform, letter-spacing',
						),
					),
				),
				'colors'           => array(
					'type'        => 'object',
					'description' => 'Color settings for breadcrumb.',
					'properties'  => array(
						'background'  => array(
							'type'        => 'object',
							'description' => 'Background color (responsive)',
						),
						'text'        => array(
							'type'        => 'object',
							'description' => 'Text color (responsive)',
						),
						'separator'   => array(
							'type'        => 'object',
							'description' => 'Separator color (responsive)',
						),
						'link_normal' => array(
							'type'        => 'object',
							'description' => 'Link normal color (responsive)',
						),
						'link_hover'  => array(
							'type'        => 'object',
							'description' => 'Link hover color (responsive)',
						),
					),
				),
				'spacing'          => array(
					'type'        => 'object',
					'description' => 'Spacing (padding/margin) with desktop, tablet, mobile keys. Each contains top, right, bottom, left values.',
				),
			),
		);
	}

	/**
	 * Get output schema.
	 *
	 * @return array
	 */
	public function get_output_schema() {
		return $this->build_output_schema(
			array(
				'updated' => array(
					'type'        => 'boolean',
					'description' => 'Whether any settings were updated.',
				),
			)
		);
	}

	/**
	 * Get examples.
	 *
	 * @return array
	 */
	public function get_examples() {
		return array(
			'enable breadcrumb',
			'disable breadcrumb',
			'set breadcrumb position to after header',
			'change breadcrumb alignment to center',
			'update breadcrumb separator',
			'set breadcrumb to before title',
			'enable breadcrumb on home page',
			'disable breadcrumb on 404 page',
			'change breadcrumb font family',
			'update breadcrumb font size',
			'set breadcrumb background color',
			'change breadcrumb text color',
			'update breadcrumb separator color',
			'set breadcrumb link colors',
			'change breadcrumb padding',
			'update breadcrumb spacing',
			'set breadcrumb alignment to left',
			'enable breadcrumb on all pages',
			'disable breadcrumb on search',
			'change breadcrumb position to inside',
			'update breadcrumb design',
			'set breadcrumb font weight',
			'change breadcrumb link hover color',
			'update breadcrumb typography',
			'set custom breadcrumb separator',
			'enable breadcrumb on single posts',
			'change breadcrumb to right align',
			'update breadcrumb styling',
			'set breadcrumb appearance',
			'configure breadcrumb display',
		);
	}

	/**
	 * Execute the ability.
	 *
	 * @param array $args Input arguments.
	 * @return array Result array.
	 */
	public function execute( $args ) {
		if ( ! defined( 'SMARTTOOLZ_THEME_SETTINGS' ) ) {
			return SmartToolz_Abilities_Response::error(
				__( 'SmartToolz theme is not active.', 'smarttoolz' ),
				__( 'Please activate the SmartToolz theme to use this feature.', 'smarttoolz' )
			);
		}

		$updated         = false;
		$update_messages = array();

		if ( isset( $args['position'] ) ) {
			$position        = sanitize_text_field( $args['position'] );
			$valid_positions = array( 'none', 'smarttoolz_header_primary_container_after', 'smarttoolz_header_after', 'smarttoolz_entry_top' );

			if ( ! in_array( $position, $valid_positions, true ) ) {
				return SmartToolz_Abilities_Response::error(
					/* translators: %s: invalid position value */
					sprintf( __( 'Invalid position: %s.', 'smarttoolz' ), $position ),
					__( 'Valid options: none, smarttoolz_header_primary_container_after, smarttoolz_header_after, smarttoolz_entry_top', 'smarttoolz' )
				);
			}

			smarttoolz_update_option( 'breadcrumb-position', $position );
			$updated = true;
			/* translators: %s: position value */
			$update_messages[] = sprintf( __( 'Position set to %s', 'smarttoolz' ), $position );
		}

		if ( isset( $args['alignment'] ) ) {
			$alignment        = sanitize_text_field( $args['alignment'] );
			$valid_alignments = array( 'left', 'center', 'right' );

			if ( ! in_array( $alignment, $valid_alignments, true ) ) {
				return SmartToolz_Abilities_Response::error(
					/* translators: %s: invalid alignment value */
					sprintf( __( 'Invalid alignment: %s.', 'smarttoolz' ), $alignment ),
					__( 'Valid options: left, center, right', 'smarttoolz' )
				);
			}

			smarttoolz_update_option( 'breadcrumb-alignment', $alignment );
			$updated = true;
			/* translators: %s: alignment value */
			$update_messages[] = sprintf( __( 'Alignment set to %s', 'smarttoolz' ), $alignment );
		}

		if ( isset( $args['separator_type'] ) ) {
			$separator_type   = sanitize_text_field( $args['separator_type'] );
			$valid_separators = array( '\003E', '\00BB', '\002F', 'unicode' );

			if ( ! in_array( $separator_type, $valid_separators, true ) ) {
				return SmartToolz_Abilities_Response::error(
					/* translators: %s: invalid separator type value */
					sprintf( __( 'Invalid separator_type: %s.', 'smarttoolz' ), $separator_type ),
					__( 'Valid options: \003E, \00BB, \002F, unicode', 'smarttoolz' )
				);
			}

			smarttoolz_update_option( 'breadcrumb-separator-selector', $separator_type );
			$updated           = true;
			$update_messages[] = __( 'Separator type updated', 'smarttoolz' );
		}

		if ( isset( $args['separator_custom'] ) ) {
			$separator_custom = sanitize_text_field( $args['separator_custom'] );
			smarttoolz_update_option( 'breadcrumb-separator', $separator_custom );
			$updated           = true;
			$update_messages[] = __( 'Custom separator updated', 'smarttoolz' );
		}

		if ( isset( $args['enable_on'] ) && is_array( $args['enable_on'] ) ) {
			$enable_map = array(
				'home_page'   => 'breadcrumb-disable-home-page',
				'blog_page'   => 'breadcrumb-disable-blog-posts-page',
				'search'      => 'breadcrumb-disable-search',
				'archive'     => 'breadcrumb-disable-archive',
				'single_page' => 'breadcrumb-disable-single-page',
				'single_post' => 'breadcrumb-disable-single-post',
				'singular'    => 'breadcrumb-disable-singular',
				'404_page'    => 'breadcrumb-disable-404-page',
			);

			foreach ( $enable_map as $key => $option_key ) {
				if ( isset( $args['enable_on'][ $key ] ) ) {
					$value = (bool) $args['enable_on'][ $key ] ? '1' : '0';
					smarttoolz_update_option( $option_key, $value );
					$updated = true;
				}
			}
			$update_messages[] = __( 'Display settings updated', 'smarttoolz' );
		}

		if ( isset( $args['typography'] ) && is_array( $args['typography'] ) ) {
			$typo = $args['typography'];

			if ( isset( $typo['font_family'] ) ) {
				smarttoolz_update_option( 'breadcrumb-font-family', sanitize_text_field( $typo['font_family'] ) );
				$updated = true;
			}

			if ( isset( $typo['font_weight'] ) ) {
				smarttoolz_update_option( 'breadcrumb-font-weight', sanitize_text_field( $typo['font_weight'] ) );
				$updated = true;
			}

			if ( isset( $typo['font_size'] ) && is_array( $typo['font_size'] ) ) {
				$sanitized_size = $this->sanitize_responsive_value( $typo['font_size'] );
				smarttoolz_update_option( 'breadcrumb-font-size', $sanitized_size );
				$updated = true;
			}

			if ( isset( $typo['font_extras'] ) && is_array( $typo['font_extras'] ) ) {
				$sanitized_extras = array();
				$allowed_keys     = array( 'line-height', 'text-transform', 'letter-spacing' );
				foreach ( $allowed_keys as $key ) {
					if ( isset( $typo['font_extras'][ $key ] ) ) {
						$sanitized_extras[ $key ] = sanitize_text_field( $typo['font_extras'][ $key ] );
					}
				}
				smarttoolz_update_option( 'breadcrumb-font-extras', $sanitized_extras );
				$updated = true;
			}

			if ( $updated ) {
				$update_messages[] = __( 'Typography updated', 'smarttoolz' );
			}
		}

		if ( isset( $args['colors'] ) && is_array( $args['colors'] ) ) {
			$colors = $args['colors'];

			if ( isset( $colors['background'] ) && is_array( $colors['background'] ) ) {
				$sanitized_bg = $this->sanitize_responsive_color( $colors['background'] );
				smarttoolz_update_option( 'breadcrumb-bg-color', $sanitized_bg );
				$updated = true;
			}

			if ( isset( $colors['text'] ) && is_array( $colors['text'] ) ) {
				$sanitized_text = $this->sanitize_responsive_color( $colors['text'] );
				smarttoolz_update_option( 'breadcrumb-active-color-responsive', $sanitized_text );
				$updated = true;
			}

			if ( isset( $colors['separator'] ) && is_array( $colors['separator'] ) ) {
				$sanitized_sep = $this->sanitize_responsive_color( $colors['separator'] );
				smarttoolz_update_option( 'breadcrumb-separator-color', $sanitized_sep );
				$updated = true;
			}

			if ( isset( $colors['link_normal'] ) && is_array( $colors['link_normal'] ) ) {
				$sanitized_link = $this->sanitize_responsive_color( $colors['link_normal'] );
				smarttoolz_update_option( 'breadcrumb-text-color-responsive', $sanitized_link );
				$updated = true;
			}

			if ( isset( $colors['link_hover'] ) && is_array( $colors['link_hover'] ) ) {
				$sanitized_hover = $this->sanitize_responsive_color( $colors['link_hover'] );
				smarttoolz_update_option( 'breadcrumb-hover-color-responsive', $sanitized_hover );
				$updated = true;
			}

			$update_messages[] = __( 'Colors updated', 'smarttoolz' );
		}

		if ( isset( $args['spacing'] ) && is_array( $args['spacing'] ) ) {
			$sanitized_spacing = $this->sanitize_spacing( $args['spacing'] );
			smarttoolz_update_option( 'breadcrumb-spacing', $sanitized_spacing );
			$updated           = true;
			$update_messages[] = __( 'Spacing updated', 'smarttoolz' );
		}

		if ( ! $updated ) {
			return SmartToolz_Abilities_Response::error(
				__( 'No changes specified.', 'smarttoolz' ),
				__( 'Please provide at least one setting to update.', 'smarttoolz' )
			);
		}

		/* translators: %s: comma-separated list of updated settings */
		$message = sprintf( __( 'Breadcrumb settings updated: %s.', 'smarttoolz' ), implode( ', ', $update_messages ) );

		return SmartToolz_Abilities_Response::success(
			$message,
			array( 'updated' => true )
		);
	}

	/**
	 * Sanitize a responsive value array.
	 *
	 * @param array $value Responsive value with desktop, tablet, mobile keys.
	 * @return array Sanitized responsive value.
	 */
	private function sanitize_responsive_value( $value ) {
		$sanitized = array();
		$devices   = array( 'desktop', 'tablet', 'mobile' );

		foreach ( $devices as $device ) {
			if ( isset( $value[ $device ] ) ) {
				$sanitized[ $device ] = sanitize_text_field( $value[ $device ] );
			}

			$unit_key = $device . '-unit';
			if ( isset( $value[ $unit_key ] ) ) {
				$sanitized[ $unit_key ] = sanitize_text_field( $value[ $unit_key ] );
			}
		}

		return $sanitized;
	}

	/**
	 * Sanitize a responsive color array.
	 *
	 * @param array $color Responsive color with desktop, tablet, mobile keys.
	 * @return array Sanitized responsive color.
	 */
	private function sanitize_responsive_color( $color ) {
		$sanitized = array();
		$devices   = array( 'desktop', 'tablet', 'mobile' );

		foreach ( $devices as $device ) {
			if ( isset( $color[ $device ] ) ) {
				$sanitized[ $device ] = sanitize_text_field( $color[ $device ] );
			}
		}

		return $sanitized;
	}

	/**
	 * Sanitize a spacing array.
	 *
	 * @param array $spacing Spacing with desktop, tablet, mobile keys containing top, right, bottom, left.
	 * @return array Sanitized spacing.
	 */
	private function sanitize_spacing( $spacing ) {
		$sanitized = array();
		$devices   = array( 'desktop', 'tablet', 'mobile' );
		$sides     = array( 'top', 'right', 'bottom', 'left' );

		foreach ( $devices as $device ) {
			if ( isset( $spacing[ $device ] ) && is_array( $spacing[ $device ] ) ) {
				$sanitized[ $device ] = array();
				foreach ( $sides as $side ) {
					if ( isset( $spacing[ $device ][ $side ] ) ) {
						$sanitized[ $device ][ $side ] = sanitize_text_field( $spacing[ $device ][ $side ] );
					}
				}
			}

			$unit_key = $device . '-unit';
			if ( isset( $spacing[ $unit_key ] ) ) {
				$sanitized[ $unit_key ] = sanitize_text_field( $spacing[ $unit_key ] );
			}
		}

		return $sanitized;
	}
}

SmartToolz_Update_Breadcrumb::register();
