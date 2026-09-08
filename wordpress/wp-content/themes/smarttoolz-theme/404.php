<?php
/**
 * 404 template.
 *
 * @package SmartToolz
 */
get_header();
?>
<section class="st-card">
    <h1 class="st-title"><?php esc_html_e( 'Page not found', 'smarttoolz' ); ?></h1>
    <p><?php esc_html_e( 'The page you are looking for does not exist or has moved.', 'smarttoolz' ); ?></p>
    <a class="st-button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'smarttoolz' ); ?></a>
</section>
<?php get_footer(); ?>
