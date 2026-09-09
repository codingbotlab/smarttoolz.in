<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>
<section class="stv-hero">
    <div class="stv-hero__box">
        <h1>Watch, share and discover.</h1>
        <p>SmartToolz is your video-sharing home. Explore videos, follow creators and build your own library.</p>
        <a class="stv-button" href="<?php echo esc_url( smarttoolz_video_theme_page_url( 'home', home_url( '/video/' ) ) ); ?>">Open SmartToolz Videos</a>
    </div>
</section>
<section>
    <h2 class="stv-page-title">Latest videos</h2>
    <div class="stv-empty">Your video feed will appear here.</div>
</section>
<?php get_footer(); ?>
