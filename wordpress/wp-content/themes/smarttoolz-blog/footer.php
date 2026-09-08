<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
$smarttoolz_brand = get_theme_mod( 'smarttoolz_footer_brand', 'SmartToolz' );
$smarttoolz_brand = $smarttoolz_brand ? $smarttoolz_brand : 'SmartToolz';
$smarttoolz_copyright = get_theme_mod( 'smarttoolz_footer_copyright', '© %year% SmartToolz. All rights reserved.' );
$smarttoolz_copyright = str_replace( '%year%', date_i18n( 'Y' ), $smarttoolz_copyright );
?>
<footer class="footer">
  <div class="container">
    <?php if ( is_active_sidebar( 'footer-1' ) || is_active_sidebar( 'footer-2' ) || is_active_sidebar( 'footer-3' ) ) : ?>
      <div class="footer-grid">
        <?php if ( is_active_sidebar( 'footer-1' ) ) dynamic_sidebar( 'footer-1' ); ?>
        <?php if ( is_active_sidebar( 'footer-2' ) ) dynamic_sidebar( 'footer-2' ); ?>
        <?php if ( is_active_sidebar( 'footer-3' ) ) dynamic_sidebar( 'footer-3' ); ?>
      </div>
    <?php endif; ?>
    <div class="footer-bottom">
      <?php wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'fallback_cb' => false ) ); ?>
      <div class="copyright"><?php echo esc_html( $smarttoolz_copyright ); ?> · <?php esc_html_e( 'Built for thoughtful publishing.', 'smarttoolz-blog' ); ?></div>
    </div>
    <div class="smarttoolz-footer-brand" aria-label="SmartToolz branding">
      <span class="smarttoolz-footer-spark">✦</span>
      <span>Powered by <strong><?php echo esc_html( $smarttoolz_brand ); ?></strong></span>
      <span class="smarttoolz-footer-dot">•</span>
      <span>Smart tools for a smarter web</span>
    </div>
  </div>
</footer>
<?php wp_footer(); ?></body></html>
