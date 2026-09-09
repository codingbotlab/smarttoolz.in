<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
$stv_site_name = function_exists( 'smarttoolz_video_theme_site_name' ) ? smarttoolz_video_theme_site_name() : get_bloginfo( 'name' );
?>
<section class="stv-home">
    <div class="stv-chips">
        <a class="stv-chip stv-chip--active" href="<?php echo esc_url( smarttoolz_video_theme_page_url( 'home', home_url( '/video/' ) ) ); ?>">All</a>
        <a class="stv-chip" href="<?php echo esc_url( smarttoolz_video_theme_page_url( 'shorts', home_url( '/video/shorts/' ) ) ); ?>">Shorts</a>
        <a class="stv-chip" href="<?php echo esc_url( smarttoolz_video_theme_page_url( 'trending', home_url( '/video/trending/' ) ) ); ?>">Trending</a>
        <a class="stv-chip" href="<?php echo esc_url( smarttoolz_video_theme_page_url( 'live', home_url( '/video/live/' ) ) ); ?>">Live</a>
        <a class="stv-chip" href="<?php echo esc_url( smarttoolz_video_theme_page_url( 'subscriptions', home_url( '/video/subscriptions/' ) ) ); ?>">Subscriptions</a>
    </div>

    <div class="stv-section-heading">
        <div>
            <h1 class="stv-page-title"><?php echo esc_html( $stv_site_name ); ?> Videos</h1>
            <p class="stv-section-subtitle">Discover videos from creators on <?php echo esc_html( $stv_site_name ); ?>.</p>
        </div>
        <a class="stv-view-link" href="<?php echo esc_url( smarttoolz_video_theme_page_url( 'trending', home_url( '/video/trending/' ) ) ); ?>">Explore more</a>
    </div>

    <div class="stv-video-grid stv-video-grid--showcase">
        <?php
        $cards = array(
            array( 'title' => 'Your personalized video feed will appear here', 'meta' => $stv_site_name . ' • Start by adding your first videos', 'duration' => '—' ),
            array( 'title' => 'Create, share and grow your audience', 'meta' => $stv_site_name . ' Creator • New uploads', 'duration' => '—' ),
            array( 'title' => 'Explore Shorts, Live and Trending', 'meta' => $stv_site_name . ' • Explore the platform', 'duration' => '—' ),
            array( 'title' => 'Build your channel on ' . $stv_site_name, 'meta' => $stv_site_name . ' • Creator tools', 'duration' => '—' ),
            array( 'title' => 'Watch later and keep your library organized', 'meta' => $stv_site_name . ' • Your Library', 'duration' => '—' ),
            array( 'title' => 'Follow creators and never miss an upload', 'meta' => $stv_site_name . ' • Subscriptions', 'duration' => '—' ),
        );
        foreach ( $cards as $card ) :
            ?>
            <article class="stv-video-card stv-video-card--placeholder">
                <a class="stv-video-card__thumb" href="<?php echo esc_url( smarttoolz_video_theme_page_url( 'home', home_url( '/video/' ) ) ); ?>" aria-label="<?php echo esc_attr( $card['title'] ); ?>">
                    <span class="stv-video-card__play">▶</span>
                    <span class="stv-video-card__duration"><?php echo esc_html( $card['duration'] ); ?></span>
                </a>
                <div class="stv-video-card__body">
                    <div class="stv-video-card__avatar" aria-hidden="true"></div>
                    <div>
                        <div class="stv-video-card__title"><?php echo esc_html( $card['title'] ); ?></div>
                        <div class="stv-video-card__meta"><?php echo esc_html( $card['meta'] ); ?></div>
                    </div>
                </div>
            </article>
            <?php
        endforeach;
        ?>
    </div>
</section>
<?php get_footer(); ?>
