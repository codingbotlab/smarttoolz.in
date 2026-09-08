<?php
/** SmartToolz video watch page. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) : the_post();
$video_id     = (int) get_the_ID();
$author_id    = (int) get_the_author_meta( 'ID' );
$channel_url  = add_query_arg( 'stv_channel', get_the_author_meta( 'user_nicename', $author_id ), home_url( '/channel/' ) );
$views        = (int) get_post_meta( $video_id, '_st_video_views', true );
$likes        = (int) get_post_meta( $video_id, '_st_likes', true );
$dislikes     = (int) get_post_meta( $video_id, '_st_dislikes', true );
$video_source = function_exists( 'smarttoolz_video_render_player' ) ? get_post_meta( $video_id, '_st_video_source', true ) : '';
$video_path   = $video_source ? wp_parse_url( $video_source, PHP_URL_PATH ) : '';
$video_ext    = strtolower( pathinfo( (string) $video_path, PATHINFO_EXTENSION ) );
$native_video = $video_source && in_array( $video_ext, array( 'mp4', 'webm', 'ogg' ), true );
$poster       = has_post_thumbnail() ? wp_get_attachment_image_url( get_post_thumbnail_id(), 'large' ) : '';
if ( function_exists( 'smarttoolz_video_content_filter' ) ) { remove_filter( 'the_content', 'smarttoolz_video_content_filter', 20 ); }
?>
<div class="stv-watch-layout">
  <article class="stv-watch">
    <div class="stv-player-shell">
      <?php if ( $native_video ) : ?>
        <div class="stv-custom-player" data-stv-player tabindex="0">
          <video class="stv-video-element" preload="metadata" playsinline<?php echo $poster ? ' poster="' . esc_url( $poster ) . '"' : ''; ?> data-stv-video>
            <source src="<?php echo esc_url( $video_source ); ?>" type="<?php echo esc_attr( 'video/' . ( 'ogg' === $video_ext ? 'ogg' : $video_ext ) ); ?>">
          </video>
          <button type="button" class="stv-big-play" data-stv-action="play" aria-label="Play video">▶</button>
          <div class="stv-player-overlay" aria-hidden="true"></div>
          <div class="stv-controls">
            <div class="stv-progress-wrap">
              <input class="stv-progress" type="range" min="0" max="1000" value="0" step="1" aria-label="Video progress" data-stv-progress>
              <div class="stv-buffer" data-stv-buffer></div>
            </div>
            <div class="stv-control-row">
              <div class="stv-control-left">
                <button type="button" class="stv-control-btn" data-stv-action="play" aria-label="Play or pause">▶</button>
                <button type="button" class="stv-control-btn" data-stv-action="mute" aria-label="Mute or unmute">🔊</button>
                <input class="stv-volume" type="range" min="0" max="1" value="1" step="0.05" aria-label="Volume" data-stv-volume>
                <span class="stv-time" data-stv-time>0:00 / 0:00</span>
              </div>
              <div class="stv-control-right">
                <button type="button" class="stv-control-btn" data-stv-action="speed" aria-label="Playback speed">1x</button>
                <button type="button" class="stv-control-btn" data-stv-action="pip" aria-label="Picture in picture">▣</button>
                <button type="button" class="stv-control-btn" data-stv-action="theater" aria-label="Theater mode">▭</button>
                <button type="button" class="stv-control-btn" data-stv-action="fullscreen" aria-label="Fullscreen">⛶</button>
              </div>
            </div>
          </div>
        </div>
      <?php elseif ( $video_source ) : ?>
        <div class="stv-embed-player">
          <iframe src="<?php echo esc_url( $video_source ); ?>" loading="lazy" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen title="<?php the_title_attribute(); ?>"></iframe>
        </div>
      <?php else : ?>
        <div class="stv-no-video">Video source not available.</div>
      <?php endif; ?>
    </div>

    <h1 class="stv-watch-title"><?php the_title(); ?></h1>
    <div class="stv-watch-meta"><span><?php echo esc_html( number_format_i18n( $views ) ); ?> views</span><span><?php echo esc_html( get_the_date() ); ?></span></div>
    <div class="stv-video-actions stv-watch-actions">
      <?php if ( is_user_logged_in() ) : ?>
        <button class="stv-react" data-stv-reaction="like" data-video="<?php echo esc_attr( $video_id ); ?>" type="button">👍 <span data-stv-likes><?php echo esc_html( number_format_i18n( $likes ) ); ?></span></button>
        <button class="stv-react" data-stv-reaction="dislike" data-video="<?php echo esc_attr( $video_id ); ?>" type="button">👎 <span data-stv-dislikes><?php echo esc_html( number_format_i18n( $dislikes ) ); ?></span></button>
      <?php endif; ?>
      <button class="stv-share" data-st-video-share data-url="<?php echo esc_attr( get_permalink() ); ?>" data-title="<?php the_title_attribute(); ?>" type="button">↗ Share</button>
    </div>
    <div class="stv-channel-row">
      <a href="<?php echo esc_url( $channel_url ); ?>" class="stv-channel-mini"><?php echo get_avatar( $author_id, 52 ); ?><span><strong><?php echo esc_html( get_the_author() ); ?></strong><small class="stv-channel-mini-count"><?php echo esc_html( number_format_i18n( (int) get_user_meta( $author_id, 'stv_subscriber_count', true ) ) ); ?> subscribers</small></span></a>
      <?php if ( is_user_logged_in() && $author_id !== get_current_user_id() ) : ?><button class="stv-subscribe" data-author="<?php echo esc_attr( $author_id ); ?>" type="button"><?php echo function_exists( 'stv_subscribed' ) && stv_subscribed( $author_id ) ? 'Subscribed' : 'Subscribe'; ?></button><?php endif; ?>
    </div>
    <div class="stv-description"><?php the_content(); ?></div>
    <?php if ( comments_open() || get_comments_number() ) : comments_template(); endif; ?>
  </article>
  <aside class="stv-sidebar">
    <h2>Recommended</h2>
    <?php echo function_exists( 'stv_render_feed' ) ? stv_render_feed( array( 'per_page' => 6, 'orderby' => 'views' ) ) : ''; ?>
  </aside>
</div>
<?php endwhile; get_footer();