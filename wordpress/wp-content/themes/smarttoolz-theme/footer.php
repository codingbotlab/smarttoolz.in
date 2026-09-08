<?php
/** SmartToolz footer. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
</div></main>
<footer class="st-site-footer">
  <div class="st-container st-footer-grid">
    <div>
      <div class="st-footer-brand">SmartToolz</div>
      <p><?php esc_html_e( 'Useful tools, practical guides and a faster web experience.', 'smarttoolz' ); ?></p>
    </div>
    <div>
      <?php if ( has_nav_menu( 'footer' ) ) : ?>
        <?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => 'nav', 'container_class' => 'st-footer-menu', 'fallback_cb' => false ) ); ?>
      <?php endif; ?>
      <?php if ( is_active_sidebar( 'footer-1' ) ) : dynamic_sidebar( 'footer-1' ); endif; ?>
    </div>
  </div>
  <div class="st-container st-footer-bottom">
    <span>© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ?: 'SmartToolz' ); ?></span>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Smart tools. Simple experience.', 'smarttoolz' ); ?></a>
  </div>
</footer>
<?php wp_footer(); ?></body></html>
