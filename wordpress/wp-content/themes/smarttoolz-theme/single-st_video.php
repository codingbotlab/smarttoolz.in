<?php
/** SmartToolz video watch page. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
while ( have_posts() ) : the_post();
$author_id = (int) get_the_author_meta( 'ID' );
$channel_url = add_query_arg( 'stv_channel', get_the_author_meta( 'user_nicename', $author_id ), home_url( '/channel/' ) );
$views = (int) get_post_meta( get_the_ID(), '_st_video_views', true );
$likes = (int) get_post_meta( get_the_ID(), '_st_likes', true );
$dislikes = (int) get_post_meta( get_the_ID(), '_st_dislikes', true );
if ( function_exists( 'smarttoolz_video_content_filter' ) ) { remove_filter( 'the_content', 'smarttoolz_video_content_filter', 20 ); }
?>
<div class="stv-watch-layout">
  <article class="stv-watch">
    <div class="stv-player-shell"><?php echo function_exists( 'smarttoolz_video_render_player' ) ? smarttoolz_video_render_player( get_the_ID() ) : ''; ?></div>
    <h1 class="stv-watch-title"><?php the_title(); ?></h1>
    <div class="stv-watch-meta"><span><?php echo esc_html( number_format_i18n( $views ) ); ?> views</span><span><?php echo esc_html( get_the_date() ); ?></span></div>
    <div class="stv-video-actions stv-watch-actions">
      <?php if ( is_user_logged_in() ) : ?>
        <button class="stv-react" data-stv-reaction="like" data-video="<?php the_ID(); ?>" type="button">👍 <span data-stv-likes><?php echo esc_html( number_format_i18n( $likes ) ); ?></span></button>
        <button class="stv-react" data-stv-reaction="dislike" data-video="<?php the_ID(); ?>" type="button">👎 <span data-stv-dislikes><?php echo esc_html( number_format_i18n( $dislikes ) ); ?></span></button>
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
