<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$term = get_queried_object();
?>
<section class="stv-platform-page">
  <div class="stv-page-head"><div><span class="stv-eyebrow">Video Category</span><h1><?php echo esc_html( single_term_title( '', false ) ); ?></h1><p><?php echo esc_html( term_description( $term->term_id ) ? wp_strip_all_tags( term_description( $term->term_id ) ) : 'Videos in this category.' ); ?></p></div></div>
  <?php if ( function_exists( 'stv_render_feed' ) ) : echo stv_render_feed( array( 'category' => $term->slug, 'per_page' => 16 ) ); endif; ?>
</section>
<?php get_footer();
