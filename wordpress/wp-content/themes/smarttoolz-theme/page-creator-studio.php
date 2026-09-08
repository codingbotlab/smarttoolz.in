<?php
/**
 * SmartToolz Creator Studio page template.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>
<main class="stv-studio-page">
  <div class="stv-studio-shell">
    <?php
    if ( function_exists( 'stv_creator_dashboard_shortcode' ) ) {
        echo stv_creator_dashboard_shortcode();
    } elseif ( shortcode_exists( 'smarttoolz_creator_dashboard' ) ) {
        echo do_shortcode( '[smarttoolz_creator_dashboard]' );
    } else {
        echo '<div class="stv-studio-empty"><h1>Creator Studio</h1><p>SmartToolz Video is not active.</p></div>';
    }
    ?>
  </div>
</main>
<?php get_footer();
