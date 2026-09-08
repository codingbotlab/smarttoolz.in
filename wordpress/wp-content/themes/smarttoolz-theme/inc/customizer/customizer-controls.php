<?php
/**
 * SmartToolz Theme Customizer Controls.
 *
 * @package     SmartToolz
 * @link        https://wpsmarttoolz.com/
 * @since       SmartToolz 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$smarttoolz_control_dir = SMARTTOOLZ_THEME_DIR . 'inc/customizer/custom-controls';

// @codingStandardsIgnoreStart WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
require $smarttoolz_control_dir . '/class-smarttoolz-customizer-control-base.php';
require $smarttoolz_control_dir . '/typography/class-smarttoolz-control-typography.php';
require_once $smarttoolz_control_dir . '/logo-svg-icon/class-smarttoolz-control-logo-svg-icon.php';
require $smarttoolz_control_dir . '/description/class-smarttoolz-control-description.php';
require $smarttoolz_control_dir . '/customizer-link/class-smarttoolz-control-customizer-link.php';
require $smarttoolz_control_dir . '/description-with-link/class-smarttoolz-control-description-with-link.php';
// @codingStandardsIgnoreEnd WPThemeReview.CoreFunctionality.FileInclude.FileIncludeFound
