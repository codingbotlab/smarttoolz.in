<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package SmartToolz
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>

<?php if ( smarttoolz_page_layout() === 'left-sidebar' ) { ?>

	<?php get_sidebar(); ?>

<?php } ?>

	<div id="primary" <?php smarttoolz_primary_class(); ?>>

		<?php smarttoolz_primary_content_top(); ?>

		<?php smarttoolz_archive_header(); ?>

		<?php smarttoolz_content_loop(); ?>

		<?php smarttoolz_pagination(); ?>

		<?php smarttoolz_primary_content_bottom(); ?>

	</div><!-- #primary -->

<?php if ( smarttoolz_page_layout() === 'right-sidebar' ) { ?>

	<?php get_sidebar(); ?>

<?php } ?>

<?php get_footer(); ?>
