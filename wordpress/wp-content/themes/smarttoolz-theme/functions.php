<?php
/**
 * SmartToolz functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package SmartToolz
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Define Constants
 */
define( 'SMARTTOOLZ_THEME_VERSION', '4.13.11' );
define( 'SMARTTOOLZ_THEME_SETTINGS', 'smarttoolz-settings' );
define( 'SMARTTOOLZ_THEME_DIR', trailingslashit( get_template_directory() ) );
define( 'SMARTTOOLZ_THEME_URI', trailingslashit( esc_url( get_template_directory_uri() ) ) );
define( 'SMARTTOOLZ_THEME_ORG_VERSION', file_exists( SMARTTOOLZ_THEME_DIR . 'inc/w-org-version.php' ) );

/**
 * Minimum Version requirement of the SmartToolz Pro addon.
 * This constant will be used to display the notice asking user to update the SmartToolz addon to the version defined below.
 */
define( 'SMARTTOOLZ_EXT_MIN_VER', '4.12.0' );

/**
 * Load in-house compatibility.
 */
if ( SMARTTOOLZ_THEME_ORG_VERSION ) {
	require_once SMARTTOOLZ_THEME_DIR . 'inc/w-org-version.php';
}

/**
 * Setup helper functions of SmartToolz.
 */
require_once SMARTTOOLZ_THEME_DIR . 'inc/core/class-smarttoolz-theme-options.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/core/class-theme-strings.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/core/common-functions.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/core/class-smarttoolz-icons.php';

define( 'SMARTTOOLZ_WEBSITE_BASE_URL', 'https://wpsmarttoolz.com' );

/**
 * Update theme
 */
require_once SMARTTOOLZ_THEME_DIR . 'inc/theme-update/smarttoolz-update-functions.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/theme-update/class-smarttoolz-theme-background-updater.php';

/**
 * Fonts Files
 */
require_once SMARTTOOLZ_THEME_DIR . 'inc/customizer/class-smarttoolz-font-families.php';
if ( is_admin() ) {
	require_once SMARTTOOLZ_THEME_DIR . 'inc/customizer/class-smarttoolz-fonts-data.php';
}

require_once SMARTTOOLZ_THEME_DIR . 'inc/lib/webfont/class-smarttoolz-webfont-loader.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/lib/docs/class-smarttoolz-docs-loader.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/customizer/class-smarttoolz-fonts.php';

require_once SMARTTOOLZ_THEME_DIR . 'inc/dynamic-css/custom-menu-old-header.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/dynamic-css/container-layouts.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/dynamic-css/smarttoolz-icons.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/core/class-smarttoolz-walker-page.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/core/class-smarttoolz-enqueue-scripts.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/core/class-gutenberg-editor-css.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/core/class-smarttoolz-wp-editor-css.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/core/class-smarttoolz-command-palette.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/dynamic-css/block-editor-compatibility.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/dynamic-css/inline-on-mobile.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/dynamic-css/content-background.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/dynamic-css/dark-mode.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/class-smarttoolz-dynamic-css.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/class-smarttoolz-global-palette.php';

// Enable NPS Survey only if the starter templates version is < 4.3.7 or > 4.4.4 to prevent fatal error.
if ( ! defined( 'SMARTTOOLZ_SITES_VER' ) || version_compare( SMARTTOOLZ_SITES_VER, '4.3.7', '<' ) || version_compare( SMARTTOOLZ_SITES_VER, '4.4.4', '>' ) ) {
	// NPS Survey Integration
	require_once SMARTTOOLZ_THEME_DIR . 'inc/lib/class-smarttoolz-nps-notice.php';
	require_once SMARTTOOLZ_THEME_DIR . 'inc/lib/class-smarttoolz-nps-survey.php';
}

/**
 * Custom template tags for this theme.
 */
require_once SMARTTOOLZ_THEME_DIR . 'inc/core/class-smarttoolz-attr.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/template-tags.php';

require_once SMARTTOOLZ_THEME_DIR . 'inc/widgets.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/core/theme-hooks.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/admin-functions.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/class-smarttoolz-memory-limit-notice.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/core/sidebar-manager.php';

/**
 * Markup Functions
 */
require_once SMARTTOOLZ_THEME_DIR . 'inc/markup-extras.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/extras.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/blog/blog-config.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/blog/blog.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/blog/single-blog.php';

/**
 * Markup Files
 */
require_once SMARTTOOLZ_THEME_DIR . 'inc/template-parts.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/class-smarttoolz-loop.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/class-smarttoolz-mobile-header.php';

/**
 * Functions and definitions.
 */
require_once SMARTTOOLZ_THEME_DIR . 'inc/class-smarttoolz-after-setup-theme.php';

// Required files.
require_once SMARTTOOLZ_THEME_DIR . 'inc/core/class-smarttoolz-admin-helper.php';

require_once SMARTTOOLZ_THEME_DIR . 'inc/schema/class-smarttoolz-schema.php';

/* Setup API */
require_once SMARTTOOLZ_THEME_DIR . 'admin/includes/class-smarttoolz-learn.php';
require_once SMARTTOOLZ_THEME_DIR . 'admin/includes/class-smarttoolz-api-init.php';

if ( is_admin() ) {
	/**
	 * Admin Menu Settings
	 */
	require_once SMARTTOOLZ_THEME_DIR . 'inc/core/class-smarttoolz-admin-settings.php';
	require_once SMARTTOOLZ_THEME_DIR . 'admin/class-smarttoolz-admin-loader.php';
	require_once SMARTTOOLZ_THEME_DIR . 'inc/lib/smarttoolz-notices/class-bsf-admin-notices.php';
}

/**
 * BSF Analytics.
 */
require_once SMARTTOOLZ_THEME_DIR . 'admin/class-smarttoolz-bsf-analytics.php';

/**
 * Metabox additions.
 */
require_once SMARTTOOLZ_THEME_DIR . 'inc/metabox/class-smarttoolz-meta-boxes.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/metabox/class-smarttoolz-meta-box-operations.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/metabox/class-smarttoolz-elementor-editor-settings.php';

/**
 * Customizer additions.
 */
require_once SMARTTOOLZ_THEME_DIR . 'inc/customizer/class-smarttoolz-customizer.php';

/**
 * SmartToolz Modules.
 */
require_once SMARTTOOLZ_THEME_DIR . 'inc/modules/posts-structures/class-smarttoolz-post-structures.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/modules/related-posts/class-smarttoolz-related-posts.php';

/**
 * Compatibility
 */
require_once SMARTTOOLZ_THEME_DIR . 'inc/compatibility/class-smarttoolz-gutenberg.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/compatibility/class-smarttoolz-jetpack.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/compatibility/woocommerce/class-smarttoolz-woocommerce.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/compatibility/edd/class-smarttoolz-edd.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/compatibility/lifterlms/class-smarttoolz-lifterlms.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/compatibility/learndash/class-smarttoolz-learndash.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/compatibility/class-smarttoolz-beaver-builder.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/compatibility/class-smarttoolz-bb-ultimate-addon.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/compatibility/class-smarttoolz-contact-form-7.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/compatibility/class-smarttoolz-visual-composer.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/compatibility/class-smarttoolz-site-origin.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/compatibility/class-smarttoolz-gravity-forms.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/compatibility/class-smarttoolz-bne-flyout.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/compatibility/class-smarttoolz-ubermeu.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/compatibility/class-smarttoolz-divi-builder.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/compatibility/class-smarttoolz-amp.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/compatibility/class-smarttoolz-yoast-seo.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/compatibility/surecart/class-smarttoolz-surecart.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/compatibility/class-smarttoolz-starter-content.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/compatibility/class-smarttoolz-buddypress.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/addons/transparent-header/class-smarttoolz-ext-transparent-header.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/addons/breadcrumbs/class-smarttoolz-breadcrumbs.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/addons/scroll-to-top/class-smarttoolz-scroll-to-top.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/addons/heading-colors/class-smarttoolz-heading-colors.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/builder/class-smarttoolz-builder-loader.php';

// Elementor Compatibility requires PHP 5.4 for namespaces.
if ( version_compare( PHP_VERSION, '5.4', '>=' ) ) {
	require_once SMARTTOOLZ_THEME_DIR . 'inc/compatibility/class-smarttoolz-elementor.php';
	require_once SMARTTOOLZ_THEME_DIR . 'inc/compatibility/class-smarttoolz-elementor-pro.php';
	require_once SMARTTOOLZ_THEME_DIR . 'inc/compatibility/class-smarttoolz-web-stories.php';
}

// Beaver Themer compatibility requires PHP 5.3 for anonymous functions.
if ( version_compare( PHP_VERSION, '5.3', '>=' ) ) {
	require_once SMARTTOOLZ_THEME_DIR . 'inc/compatibility/class-smarttoolz-beaver-themer.php';
}

require_once SMARTTOOLZ_THEME_DIR . 'inc/core/markup/class-smarttoolz-markup.php';

/**
 * Abilities API integration.
 */
require_once SMARTTOOLZ_THEME_DIR . 'inc/abilities/bootstrap.php';

/**
 * Load deprecated functions
 */
require_once SMARTTOOLZ_THEME_DIR . 'inc/core/deprecated/deprecated-filters.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/core/deprecated/deprecated-hooks.php';
require_once SMARTTOOLZ_THEME_DIR . 'inc/core/deprecated/deprecated-functions.php';
