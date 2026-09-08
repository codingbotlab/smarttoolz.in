<?php
/**
 * Button Header Configuration.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       4.5.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register button header builder Customizer Configurations.
 *
 * @param array $configurations SmartToolz Customizer Configurations.
 * @since 4.5.2
 * @return array SmartToolz Customizer Configurations with updated configurations.
 */
function smarttoolz_header_button_configuration( $configurations = array() ) {

	$_configs = SmartToolz_Button_Component_Configs::register_configuration( $configurations, 'header', 'section-hb-button-' );

	if ( SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
		array_map( 'smarttoolz_save_header_customizer_configs', $_configs );
	}

	return $_configs;
}

if ( SmartToolz_Builder_Customizer::smarttoolz_collect_customizer_builder_data() ) {
	add_action( 'init', 'smarttoolz_header_button_configuration', 10, 0 );
}
