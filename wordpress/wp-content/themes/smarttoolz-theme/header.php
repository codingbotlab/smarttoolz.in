<?php
/**
 * The header for SmartToolz Theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package SmartToolz
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?><!DOCTYPE html>
<?php smarttoolz_html_before(); ?>
<html <?php language_attributes(); ?>>
<head>
<?php smarttoolz_head_top(); ?>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php
if ( apply_filters( 'smarttoolz_header_profile_gmpg_link', true ) ) {
	?>
	<link rel="profile" href="https://gmpg.org/xfn/11"> 
	<?php
}
?>
<?php wp_head(); ?>
<?php smarttoolz_head_bottom(); ?>
</head>

<body <?php smarttoolz_schema_body(); ?> <?php body_class(); ?>>
<?php smarttoolz_body_top(); ?>
<?php wp_body_open(); ?>

<a
	class="skip-link screen-reader-text"
	href="#content">
		<?php echo esc_html( smarttoolz_default_strings( 'string-header-skip-link', false ) ); ?>
</a>

<div
<?php
	echo wp_kses_post(
		smarttoolz_attr(
			'site',
			array(
				'id'    => 'page',
				'class' => 'hfeed site',
			)
		)
	);
	?>
>
	<?php
	smarttoolz_header_before();

	smarttoolz_header();

	smarttoolz_header_after();

	smarttoolz_content_before();
	?>
	<div id="content" class="site-content">
		<div class="ast-container">
		<?php smarttoolz_content_top(); ?>
