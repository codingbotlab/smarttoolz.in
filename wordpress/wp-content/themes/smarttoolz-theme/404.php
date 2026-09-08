<?php
/** SmartToolz 404 template. */
get_header();
?>
<section class="st-entry"><div class="st-card"><span class="st-eyebrow">404</span><h1 class="st-entry-title"><?php esc_html_e( 'Page not found.', 'smarttoolz' ); ?></h1><p><?php esc_html_e( 'The page you requested does not exist or may have moved.', 'smarttoolz' ); ?></p><div class="st-actions"><a class="st-button" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'smarttoolz' ); ?></a></div></div></section>
<?php get_footer(); ?>
