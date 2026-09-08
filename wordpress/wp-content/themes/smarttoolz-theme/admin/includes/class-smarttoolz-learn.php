<?php
/**
 * SmartToolz Learn Helper Class
 *
 * @package SmartToolz
 * @since 4.12.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * SmartToolz_Learn class.
 *
 * @since 4.12.0
 */
class SmartToolz_Learn {
	/**
	 * Get default learn chapters structure.
	 *
	 * Returns the complete structure of all available chapters and their steps.
	 * This serves as the source of truth for chapter definitions used across
	 * the theme for both frontend display and analytics validation.
	 *
	 * @return array Array of chapter objects with their steps.
	 * @since 4.12.0
	 */
	public static function get_chapters_structure() {
		$chapters = array(
			array(
				'id'          => 'brand-basics',
				'title'       => __( 'Brand Basics', 'smarttoolz' ),
				'description' => __( 'Make your website instantly recognizable and aligned with your brand identity.', 'smarttoolz' ),
				'url'         => 'https://wpsmarttoolz.com/docs/style-guide/',
				'steps'       => array(
					array(
						'id'          => 'logo-tagline',
						'title'       => __( 'Add Logo, Tagline & Site Icon', 'smarttoolz' ),
						'description' => __( 'Help visitors identify your brand quickly by personalizing your core brand elements.', 'smarttoolz' ),
						'learn'       => array(
							'type'    => 'dialog',
							'content' => array(
								'type' => 'image',
								'data' => array(
									'src' => 'https://wpsmarttoolz.com/wp-content/uploads/2025/12/smarttoolz-learn-logo-tagline.png',
									'alt' => __( 'Add Logo, Tagline & Site Icon in SmartToolz', 'smarttoolz' ),
								),
							),
						),
						'action'      => array(
							'label'      => __( 'Add Branding', 'smarttoolz' ),
							'url'        => admin_url( 'customize.php?autofocus[section]=title_tagline' ),
							'isExternal' => true,
						),
						'completed'   => false,
					),
					array(
						'id'          => 'style-guide',
						'title'       => __( 'Update Brand Style Guide', 'smarttoolz' ),
						'description' => __( 'Bring consistency across your entire site by setting your brand colors, fonts, and design rules.', 'smarttoolz' ),
						'learn'       => array(
							'type'    => 'dialog',
							'content' => array(
								'type' => 'image',
								'data' => array(
									'src' => 'https://wpsmarttoolz.com/wp-content/uploads/2025/12/smarttoolz-learn-style-guide.png',
									'alt' => __( 'Update Brand Style Guide in SmartToolz', 'smarttoolz' ),
								),
							),
						),
						'action'      => array(
							'label'      => __( 'Update Style Guide', 'smarttoolz' ),
							'url'        => admin_url( 'customize.php?autofocus=smarttoolz-tour' ),
							'isExternal' => true,
						),
						'completed'   => false,
					),
				),
			),
			array(
				'id'          => 'navigation-header',
				'title'       => __( 'Navigation & Header', 'smarttoolz' ),
				'description' => __( 'Guide visitors effortlessly with a clear, modern, and intuitive header experience.', 'smarttoolz' ),
				'url'         => 'https://wpsmarttoolz.com/docs/header-builder-options/',
				'steps'       => array(
					array(
						'id'          => 'header-layout',
						'title'       => __( 'Customize Header Layout', 'smarttoolz' ),
						'description' => __( 'Adjust your header structure: placement of logo, site title, buttons, menu and other elements', 'smarttoolz' ),
						'learn'       => array(
							'type'    => 'dialog',
							'content' => array(
								'type' => 'image',
								'data' => array(
									'src' => 'https://wpsmarttoolz.com/wp-content/uploads/2025/12/smarttoolz-learn-navigation-header.png',
									'alt' => __( 'Customize Header Layout in SmartToolz', 'smarttoolz' ),
								),
							),
						),
						'action'      => array(
							'label'      => __( 'Customize Header', 'smarttoolz' ),
							'url'        => admin_url( 'customize.php?autofocus[panel]=panel-header-builder-group' ),
							'isExternal' => true,
						),
						'completed'   => false,
					),
					array(
						'id'          => 'organize-menu',
						'title'       => __( 'Organize Your Menu', 'smarttoolz' ),
						'description' => __( 'Create a simple, logical menu so visitors can find what they need without friction.', 'smarttoolz' ),
						'learn'       => array(
							'type'    => 'dialog',
							'content' => array(
								'type' => 'image',
								'data' => array(
									'src' => 'https://wpsmarttoolz.com/wp-content/uploads/2025/12/smarttoolz-learn-organize-menu.png',
									'alt' => __( 'Organize Your Menu in SmartToolz', 'smarttoolz' ),
								),
							),
						),
						'action'      => array(
							'label'      => __( 'Configure Menu', 'smarttoolz' ),
							'url'        => admin_url( 'customize.php?autofocus[section]=section-hb-menu-1' ),
							'isExternal' => true,
						),
						'completed'   => false,
					),
					array(
						'id'          => 'mobile-header',
						'title'       => __( 'Set Up Your Mobile Header', 'smarttoolz' ),
						'description' => __( 'Optimize the header experience for small screens to ensure a seamless mobile journey.', 'smarttoolz' ),
						'learn'       => array(
							'type'    => 'dialog',
							'content' => array(
								'type' => 'image',
								'data' => array(
									'src' => 'https://wpsmarttoolz.com/wp-content/uploads/2025/12/smarttoolz-learn-mobile-header.png',
									'alt' => __( 'Set Up Your Mobile Header in SmartToolz', 'smarttoolz' ),
								),
							),
						),
						'action'      => array(
							'label'      => __( 'Configure Mobile Menu', 'smarttoolz' ),
							'url'        => admin_url( 'customize.php?autofocus[section]=section-header-mobile-menu&preview-device=mobile' ),
							'isExternal' => true,
						),
						'isPro'       => false,
						'completed'   => false,
					),
				),
			),
			array(
				'id'          => 'footer-customization',
				'title'       => __( 'Footer Customization', 'smarttoolz' ),
				'description' => __( 'Create a clean, modern footer that builds trust and improves browsing.', 'smarttoolz' ),
				'url'         => 'https://wpsmarttoolz.com/docs/footer-builder/',
				'steps'       => array(
					array(
						'id'          => 'footer-layout',
						'title'       => __( 'Customize Footer Layout', 'smarttoolz' ),
						'description' => __( 'Add your social handles, links, contact info, copyrights, or widgets to create a professional closing section.', 'smarttoolz' ),
						'learn'       => array(
							'type'    => 'dialog',
							'content' => array(
								'type' => 'image',
								'data' => array(
									'src' => 'https://wpsmarttoolz.com/wp-content/uploads/2025/12/smarttoolz-learn-footer-layout.png',
									'alt' => __( 'Customize Footer Layout in SmartToolz', 'smarttoolz' ),
								),
							),
						),
						'action'      => array(
							'label'      => __( 'Customize Footer', 'smarttoolz' ),
							'url'        => admin_url( 'customize.php?autofocus[panel]=panel-footer-builder-group' ),
							'isExternal' => true,
						),
						'completed'   => false,
					),
				),
			),
			array(
				'id'          => 'page-layout-settings',
				'title'       => __( 'Page & Layout Settings', 'smarttoolz' ),
				'description' => __( 'Give your pages a clean, consistent visual flow that feels polished and professional.', 'smarttoolz' ),
				'url'         => 'https://wpsmarttoolz.com/docs/page-layout-settings-guide/',
				'steps'       => array(
					array(
						'id'          => 'sidebar-layout',
						'title'       => __( 'Choose default sidebar layout and style', 'smarttoolz' ),
						'description' => __( 'Select left, right, or no sidebar depending on your content needs.', 'smarttoolz' ),
						'learn'       => array(
							'type'    => 'dialog',
							'content' => array(
								'type' => 'image',
								'data' => array(
									'src' => 'https://wpsmarttoolz.com/wp-content/uploads/2025/12/smarttoolz-learn-sidebar-layout.png',
									'alt' => __( 'Customize Sidebar Layout in SmartToolz', 'smarttoolz' ),
								),
							),
						),
						'action'      => array(
							'label'      => __( 'Configure Sidebar', 'smarttoolz' ),
							'url'        => admin_url( 'customize.php?autofocus[section]=section-sidebars' ),
							'isExternal' => true,
						),
						'completed'   => false,
					),
					array(
						'id'          => 'blog-layout',
						'title'       => __( 'Customize Blog Layout', 'smarttoolz' ),
						'description' => __( 'Choose how your posts appear - customize everything like layout, style, width and much more', 'smarttoolz' ),
						'learn'       => array(
							'type'    => 'dialog',
							'content' => array(
								'type' => 'image',
								'data' => array(
									'src' => 'https://wpsmarttoolz.com/wp-content/uploads/2025/12/smarttoolz-learn-blog-layout.png',
									'alt' => __( 'Customize Blog Layout in SmartToolz', 'smarttoolz' ),
								),
							),
						),
						'action'      => array(
							'label'      => __( 'Customize Blog', 'smarttoolz' ),
							'url'        => admin_url( 'customize.php?autofocus[section]=section-blog' ),
							'isExternal' => true,
						),
						'completed'   => false,
					),
					array(
						'id'          => 'single-page-layout',
						'title'       => __( 'Customize Single Page Layout', 'smarttoolz' ),
						'description' => __( 'Fine-tune individual pages for layout, style to suite your storytelling, SEO, and user experience', 'smarttoolz' ),
						'learn'       => array(
							'type'    => 'dialog',
							'content' => array(
								'type' => 'image',
								'data' => array(
									'src' => 'https://wpsmarttoolz.com/wp-content/uploads/2025/12/smarttoolz-learn-single-page-layout.png',
									'alt' => __( 'Customize Single Page Layout in SmartToolz', 'smarttoolz' ),
								),
							),
						),
						'action'      => array(
							'label'      => __( 'Customize Page', 'smarttoolz' ),
							'url'        => admin_url( 'customize.php?autofocus[section]=section-single-page' ),
							'isExternal' => true,
						),
						'completed'   => false,
					),
				),
			),
		);

		// Add WooCommerce chapter if WooCommerce is active.
		if ( class_exists( 'WooCommerce' ) ) {
			$chapters[] = array(
				'id'          => 'woocommerce-essentials',
				'title'       => __( 'WooCommerce Essentials', 'smarttoolz' ),
				'description' => __( 'Create a clean, trustworthy shopping experience to maximize your sales', 'smarttoolz' ),
				'url'         => 'https://wpsmarttoolz.com/docs/woocommerce-integration-overview/',
				'steps'       => array(
					array(
						'id'          => 'shop-page',
						'title'       => __( 'Customize Shop Page', 'smarttoolz' ),
						'description' => __( 'Adjust product grid spacing, columns, and visual elements.', 'smarttoolz' ),
						'learn'       => array(
							'type'    => 'dialog',
							'content' => array(
								'type' => 'image',
								'data' => array(
									'src' => 'https://wpsmarttoolz.com/wp-content/uploads/2025/12/smarttoolz-learn-shop-page.png',
									'alt' => __( 'Customize Shop Page in SmartToolz', 'smarttoolz' ),
								),
							),
						),
						'action'      => array(
							'label'      => __( 'Customize Shop', 'smarttoolz' ),
							'url'        => admin_url( 'customize.php?autofocus[section]=woocommerce_product_catalog' ),
							'isExternal' => true,
						),
						'completed'   => false,
					),
					array(
						'id'          => 'product-page',
						'title'       => __( 'Customize Product Page', 'smarttoolz' ),
						'description' => __( 'Improve product presentation with better structure and clarity.', 'smarttoolz' ),
						'learn'       => array(
							'type'    => 'dialog',
							'content' => array(
								'type' => 'image',
								'data' => array(
									'src' => 'https://wpsmarttoolz.com/wp-content/uploads/2025/12/smarttoolz-learn-product-page.png',
									'alt' => __( 'Customize Product Page in SmartToolz', 'smarttoolz' ),
								),
							),
						),
						'action'      => array(
							'label'      => __( 'Customize Products', 'smarttoolz' ),
							'url'        => admin_url( 'customize.php?autofocus[section]=section-woo-shop-single' ),
							'isExternal' => true,
						),
						'completed'   => false,
					),
				),
			);
		}

		// Add Edit Your Homepage chapter as the last item.
		$homepage_id    = absint( get_option( 'page_on_front' ) );
		$homepage_url   = $homepage_id ? admin_url( 'post.php?post=' . $homepage_id . '&action=edit' ) : admin_url( 'options-reading.php' );
		$homepage_label = $homepage_id ? __( 'Edit Homepage', 'smarttoolz' ) : __( 'Set Homepage', 'smarttoolz' );

		$chapters[] = array(
			'id'          => 'edit-homepage',
			'title'       => __( 'Edit Your Homepage', 'smarttoolz' ),
			'description' => __( 'Add your own content and visuals to make your site feel authentic and trustworthy', 'smarttoolz' ),
			'url'         => 'https://wpsmarttoolz.com/guides-and-tutorials/set-your-homepage/',
			'steps'       => array(
				array(
					'id'          => 'homepage-editor',
					'title'       => __( 'Edit Your Homepage', 'smarttoolz' ),
					'description' => __( 'Add your own content and visuals to make your site feel authentic and trustworthy', 'smarttoolz' ),
					'action'      => array(
						'label'      => $homepage_label,
						'url'        => $homepage_url,
						'isExternal' => true,
					),
					'completed'   => false,
				),
			),
		);

		/**
		 * Filter learn chapters structure.
		 *
		 * @param array $chapters Learn chapters data.
		 * @since 4.12.0
		 */
		return apply_filters( 'smarttoolz_learn_chapters', $chapters );
	}

	/**
	 * Get count of incomplete chapters.
	 *
	 * A chapter is considered incomplete if it has at least one incomplete step.
	 *
	 * @param int $user_id Optional. User ID to get progress for. Defaults to current user.
	 * @return int Number of incomplete chapters.
	 * @since 4.12.2
	 */
	public static function get_incomplete_chapters_count( $user_id = 0 ) {
		$chapters         = self::get_learn_chapters( $user_id );
		$incomplete_count = 0;

		foreach ( $chapters as $chapter ) {
			if ( ! isset( $chapter['steps'] ) || ! is_array( $chapter['steps'] ) ) {
				continue;
			}

			foreach ( $chapter['steps'] as $step ) {
				if ( empty( $step['completed'] ) ) {
					$incomplete_count++;
					break;
				}
			}
		}

		return $incomplete_count;
	}

	/**
	 * Get learn chapters with user progress merged.
	 *
	 * @param int $user_id Optional. User ID to get progress for. Defaults to current user.
	 * @return array Chapters array with progress data merged.
	 * @since 4.12.0
	 */
	public static function get_learn_chapters( $user_id = 0 ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		// Get chapters structure.
		$chapters = self::get_chapters_structure();

		// Get saved progress from user meta.
		$saved_progress = get_user_meta( $user_id, 'smarttoolz_learn_progress', true );
		if ( ! is_array( $saved_progress ) ) {
			$saved_progress = array();
		}

		// Merge saved progress with chapters.
		foreach ( $chapters as &$chapter ) {
			// Validate chapter structure.
			if ( ! isset( $chapter['id'], $chapter['steps'] ) || ! is_array( $chapter['steps'] ) ) {
				continue;
			}

			$chapter_id = $chapter['id'];

			foreach ( $chapter['steps'] as &$step ) {
				if ( ! isset( $step['id'] ) ) {
					continue;
				}

				$step_id = $step['id'];
				if ( isset( $saved_progress[ $chapter_id ][ $step_id ] ) ) {
					$step['completed'] = $saved_progress[ $chapter_id ][ $step_id ];
				}
			}
		}

		return $chapters;
	}
}
