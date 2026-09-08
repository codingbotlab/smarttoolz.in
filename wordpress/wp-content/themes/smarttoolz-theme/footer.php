<?php
/** SmartToolz footer. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
</div></main>
<footer class="st-site-footer"><div class="st-container st-footer-inner">
<div>© <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( get_bloginfo( 'name' ) ?: 'SmartToolz' ); ?></div>
<div><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Smart tools. Simple experience.', 'smarttoolz' ); ?></a></div>
</div></footer>
<?php wp_footer(); ?></body></html>
