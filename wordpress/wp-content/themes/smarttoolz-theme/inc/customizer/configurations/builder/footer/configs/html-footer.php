<?php
/**
 * HTML footer Configuration.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register html footer builder Customizer Configurations.
 *
 * @param array $configurations SmartToolz Customizer Configurations.
 * @since 4.5.2
 * @return array SmartToolz Customizer Configurations with updated configurations.
 */
function smarttoolz_html_footer_configuration( $configurations = array() ) {
	$_configs = SmartToolz_Html_Component_Configs::register_configuration( $configurations, 'footer', 'section-fb-html-' );

	if ( SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
		array_map( 'smarttoolz_save_footer_customizer_configs', $_configs );
	}

	return $_configs;
}

if ( SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
	add_action( 'init', 'smarttoolz_html_footer_configuration', 10, 0 );
}
