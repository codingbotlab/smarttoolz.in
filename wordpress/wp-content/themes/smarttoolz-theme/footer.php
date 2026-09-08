<?php
/**
 * Theme footer.
 *
 * @package SmartToolz
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
    </div>
</main>
<footer class="st-site-footer">
    <div class="st-container">
        <?php
        printf(
            esc_html__( '© %1$s %2$s. All rights reserved.', 'smarttoolz' ),
            esc_html( gmdate( 'Y' ) ),
            esc_html( get_bloginfo( 'name' ) )
        );
        ?>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
