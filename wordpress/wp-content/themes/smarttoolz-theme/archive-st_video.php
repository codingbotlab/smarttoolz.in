<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>
<section class="stv-platform-page">
  <div class="stv-page-head"><div><span class="stv-eyebrow">SmartToolz Video</span><h1>Explore Videos</h1><p>Watch, discover and share videos from SmartToolz creators.</p></div><a class="stv-upload-cta" href="<?php echo esc_url( home_url( '/video-upload/' ) ); ?>">Upload video</a></div>
  <?php if ( function_exists( 'stv_render_feed' ) ) : echo stv_render_feed( array( 'per_page' => 16 ) ); else : ?><p>No video engine is active.</p><?php endif; ?>
</section>
<?php get_footer();
