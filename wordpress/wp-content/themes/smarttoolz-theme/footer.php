<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package SmartToolz
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<?php smarttoolz_content_bottom(); ?>
	</div> <!-- ast-container -->
	</div><!-- #content -->
<?php
	smarttoolz_content_after();

	smarttoolz_footer_before();

	smarttoolz_footer();

	smarttoolz_footer_after();
?>
	</div><!-- #page -->
<?php
	smarttoolz_body_bottom();
	wp_footer();
?>
	</body>
</html>
