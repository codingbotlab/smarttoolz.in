<?php
/** SmartToolz video home — YouTube-inspired layout. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();

if ( function_exists( 'stv_platform_shortcode' ) ) :
    $terms = get_terms( array(
        'taxonomy'   => 'st_video_category',
        'hide_empty' => true,
        'number'     => 18,
        'orderby'    => 'count',
        'order'      => 'DESC',
    ) );
    $home_url = home_url( '/' );
    $videos_url = function_exists( 'smarttoolz_video_page_link' ) ? smarttoolz_video_page_link( 'video-library' ) : get_post_type_archive_link( 'st_video' );
    $trending_url = function_exists( 'smarttoolz_video_page_link' ) ? smarttoolz_video_page_link( 'trending-videos' ) : add_query_arg( 'stv_sort', 'views', $home_url );
    $subscriptions_url = function_exists( 'smarttoolz_video_page_link' ) ? smarttoolz_video_page_link( 'subscriptions' ) : $home_url;
    $history_url = function_exists( 'smarttoolz_video_page_link' ) ? smarttoolz_video_page_link( 'watch-history' ) : $home_url;
    $liked_url = function_exists( 'smarttoolz_video_page_link' ) ? smarttoolz_video_page_link( 'liked-videos' ) : $home_url;
    $upload_url = function_exists( 'smarttoolz_video_page_link' ) ? smarttoolz_video_page_link( 'video-upload' ) : $home_url;
    $channel_url = function_exists( 'smarttoolz_video_page_link' ) ? smarttoolz_video_page_link( 'channel' ) : $home_url;
    ?>
    <style id="smarttoolz-youtube-home-css">
    body.smarttoolz-video-platform{--yt-bg:#0f0f0f;--yt-surface:#181818;--yt-surface-2:#272727;--yt-text:#f1f1f1;--yt-muted:#aaa;--yt-border:#303030;background:var(--yt-bg);color:var(--yt-text)}
    body.smarttoolz-video-platform .st-main,body.smarttoolz-video-platform .st-content{background:var(--yt-bg);color:var(--yt-text)}
    body.smarttoolz-video-platform .st-content{padding:0}
    body.smarttoolz-video-platform .st-site-header{background:#0f0f0f;border-bottom:1px solid #272727;color:#fff}
    body.smarttoolz-video-platform .st-video-header .st-header-inner{min-height:68px;padding-inline:22px;gap:16px}
    body.smarttoolz-video-platform .st-video-header .st-brand{color:#fff;font-size:1.12rem;letter-spacing:-.03em}
    body.smarttoolz-video-platform .st-video-header .st-brand::before{content:"☰";font-size:22px;font-weight:400;line-height:1;margin-right:8px;color:#fff}
    body.smarttoolz-video-platform .st-video-header .st-header-controls{justify-content:center}
    body.smarttoolz-video-platform .st-video-header .st-header-tools{width:100%;justify-content:center;gap:10px;margin:0}
    body.smarttoolz-video-platform .st-video-header .st-header-search{width:min(560px,52vw);min-width:260px}
    body.smarttoolz-video-platform .st-video-header .st-search-form{display:flex;align-items:center;gap:0}
    body.smarttoolz-video-platform .st-video-header .st-search-field{height:42px;border:1px solid #303030;border-right:0;border-radius:22px 0 0 22px;background:#121212;color:#fff;padding-inline:17px}
    body.smarttoolz-video-platform .st-video-header .st-search-field::placeholder{color:#888}
    body.smarttoolz-video-platform .st-video-header .st-search-submit{height:42px;width:52px;border:1px solid #303030;border-left:0;border-radius:0 22px 22px 0;background:#272727;color:#fff;padding:0;font-size:18px}
    body.smarttoolz-video-platform .st-video-header .st-theme-toggle{height:38px;border:1px solid #303030;background:#181818;color:#fff;border-radius:20px;padding:0 12px}
    body.smarttoolz-video-platform .stv-account-trigger{min-height:40px;border:0;background:transparent;color:#fff;padding:2px 5px}
    body.smarttoolz-video-platform .stv-account-name{color:#fff}
    body.smarttoolz-video-platform .stv-account-avatar{background:#272727;color:#fff}
    body.smarttoolz-video-platform .stv-account-dropdown{background:#212121;border-color:#383838;box-shadow:0 8px 32px rgba(0,0,0,.5)}
    body.smarttoolz-video-platform .stv-account-dropdown a{color:#f1f1f1}
    body.smarttoolz-video-platform .stv-account-dropdown a:hover{background:#303030}
    body.smarttoolz-video-platform .stv-account-dropdown i{color:#aaa}
    body.smarttoolz-video-platform .stv-account-section{color:#aaa}
    body.smarttoolz-video-platform .stv-account-head{border-color:#383838}
    body.smarttoolz-video-platform .stv-account-head small{color:#aaa}
    .stv-yt-home{display:grid;grid-template-columns:84px minmax(0,1fr);min-height:calc(100vh - 68px)}
    .stv-yt-sidebar{position:sticky;top:68px;height:calc(100vh - 68px);padding:12px 8px;overflow:auto;background:#0f0f0f}
    .stv-yt-side-link{display:flex;flex-direction:column;align-items:center;justify-content:center;gap:5px;min-height:68px;padding:8px 4px;border-radius:10px;color:#f1f1f1;text-decoration:none;font-size:10px;font-weight:600;text-align:center}
    .stv-yt-side-link:hover,.stv-yt-side-link.is-active{background:#272727;color:#fff}
    .stv-yt-side-link i{font-size:21px;font-style:normal;line-height:1;color:#fff}
    .stv-yt-divider{height:1px;background:#272727;margin:10px 6px}
    .stv-yt-main{min-width:0;padding:0 24px 50px}
    .stv-yt-chips{display:flex;gap:10px;overflow:auto;padding:16px 0 18px;scrollbar-width:none;position:sticky;top:68px;z-index:5;background:linear-gradient(#0f0f0f 82%,rgba(15,15,15,0))}
    .stv-yt-chips::-webkit-scrollbar{display:none}
    .stv-yt-chip{display:inline-flex;align-items:center;justify-content:center;flex:0 0 auto;height:34px;padding:0 14px;border-radius:8px;background:#272727;color:#f1f1f1;text-decoration:none;font-size:13px;font-weight:650;white-space:nowrap}
    .stv-yt-chip:hover{background:#3d3d3d;color:#fff}
    .stv-yt-chip.is-active{background:#f1f1f1;color:#111}
    .stv-yt-section{padding-top:4px}
    .stv-yt-section-head{display:flex;align-items:center;justify-content:space-between;gap:16px;margin:4px 0 16px}
    .stv-yt-section-head h1,.stv-yt-section-head h2{font-size:20px;line-height:1.2;margin:0;color:#fff;font-weight:700}
    .stv-yt-section-head a{color:#3ea6ff;font-size:14px;font-weight:700;text-decoration:none}
    .stv-yt-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:34px 18px}
    .stv-yt-card{min-width:0}
    .stv-yt-thumb{display:block;position:relative;aspect-ratio:16/9;overflow:hidden;border-radius:10px;background:#272727}
    .stv-yt-thumb img{display:block;width:100%;height:100%;object-fit:cover;transition:transform .18s ease}
    .stv-yt-card:hover .stv-yt-thumb img{transform:scale(1.02)}
    .stv-yt-duration{position:absolute;right:6px;bottom:6px;background:rgba(0,0,0,.86);color:#fff;padding:2px 5px;border-radius:3px;font-size:11px;font-weight:700}
    .stv-yt-card-body{display:grid;grid-template-columns:34px minmax(0,1fr);gap:10px;padding-top:10px}
    .stv-yt-avatar{width:34px;height:34px;border-radius:50%;object-fit:cover;background:#333}
    .stv-yt-card-title{display:-webkit-box;-webkit-box-orient:vertical;-webkit-line-clamp:2;overflow:hidden;color:#f1f1f1;font-size:15px;line-height:1.3;font-weight:650;text-decoration:none;margin:0 0 5px}
    .stv-yt-author,.stv-yt-meta{color:#aaa;font-size:12px;line-height:1.45}
    .stv-yt-meta{margin-top:1px}
    .stv-yt-empty{padding:70px 20px;border:1px dashed #3a3a3a;border-radius:10px;text-align:center;color:#aaa;background:#181818}
    .stv-yt-empty strong{color:#f1f1f1;display:block;font-size:17px;margin-bottom:6px}
    @media(max-width:1250px){.stv-yt-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}
    @media(max-width:900px){.stv-yt-home{grid-template-columns:72px minmax(0,1fr)}.stv-yt-main{padding-inline:16px}.stv-yt-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:28px 14px}.stv-yt-sidebar{padding-inline:5px}.stv-yt-side-link{font-size:9px}}
    @media(max-width:650px){.stv-yt-home{display:block}.stv-yt-sidebar{position:static;height:auto;display:flex;gap:3px;padding:4px 8px;overflow:auto;border-bottom:1px solid #272727}.stv-yt-side-link{min-width:70px;min-height:54px}.stv-yt-divider{display:none}.stv-yt-chips{top:68px}.stv-yt-grid{grid-template-columns:1fr}.stv-yt-main{padding-inline:12px}}
    </style>

    <div class="stv-yt-home">
      <aside class="stv-yt-sidebar" aria-label="Video navigation">
        <a class="stv-yt-side-link is-active" href="<?php echo esc_url( $home_url ); ?>"><i class="fa-solid fa-house" aria-hidden="true"></i><span>Home</span></a>
        <a class="stv-yt-side-link" href="<?php echo esc_url( $videos_url ); ?>"><i class="fa-solid fa-play" aria-hidden="true"></i><span>Videos</span></a>
        <a class="stv-yt-side-link" href="<?php echo esc_url( $subscriptions_url ); ?>"><i class="fa-solid fa-bell" aria-hidden="true"></i><span>Subscriptions</span></a>
        <div class="stv-yt-divider"></div>
        <a class="stv-yt-side-link" href="<?php echo esc_url( $channel_url ); ?>"><i class="fa-solid fa-user" aria-hidden="true"></i><span>You</span></a>
        <a class="stv-yt-side-link" href="<?php echo esc_url( $history_url ); ?>"><i class="fa-solid fa-clock-rotate-left" aria-hidden="true"></i><span>History</span></a>
        <a class="stv-yt-side-link" href="<?php echo esc_url( $liked_url ); ?>"><i class="fa-solid fa-thumbs-up" aria-hidden="true"></i><span>Liked</span></a>
        <?php if ( is_user_logged_in() ) : ?><div class="stv-yt-divider"></div><a class="stv-yt-side-link" href="<?php echo esc_url( $upload_url ); ?>"><i class="fa-solid fa-plus" aria-hidden="true"></i><span>Create</span></a><?php endif; ?>
      </aside>

      <main class="stv-yt-main">
        <nav class="stv-yt-chips" aria-label="Video categories">
          <a class="stv-yt-chip is-active" href="<?php echo esc_url( $home_url ); ?>">All</a>
          <?php if ( ! is_wp_error( $terms ) ) : foreach ( $terms as $term ) : ?><a class="stv-yt-chip" href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo esc_html( $term->name ); ?></a><?php endforeach; endif; ?>
        </nav>

        <section class="stv-yt-section">
          <div class="stv-yt-section-head"><h1>Recommended videos</h1><a href="<?php echo esc_url( $videos_url ); ?>">View all →</a></div>
          <?php
          $latest = new WP_Query( array(
              'post_type'           => 'st_video',
              'post_status'         => 'publish',
              'posts_per_page'      => 20,
              'ignore_sticky_posts' => true,
          ) );
          if ( $latest->have_posts() ) :
              echo '<div class="stv-yt-grid">';
              while ( $latest->have_posts() ) : $latest->the_post();
                  $vid = get_the_ID();
                  $author_id = (int) get_post_field( 'post_author', $vid );
                  $author = get_userdata( $author_id );
                  $thumb = get_the_post_thumbnail_url( $vid, 'medium_large' );
                  $duration = get_post_meta( $vid, '_st_video_duration', true );
                  $views = (int) get_post_meta( $vid, '_st_video_views', true );
                  $avatar = get_avatar_url( $author_id, array( 'size' => 68 ) );
                  ?>
                  <article class="stv-yt-card">
                    <a class="stv-yt-thumb" href="<?php the_permalink(); ?>">
                      <?php if ( $thumb ) : ?><img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( get_the_title( $vid ) ); ?>" loading="lazy"><?php else : ?><span style="display:grid;place-items:center;width:100%;height:100%;color:#777;font-size:42px">▶</span><?php endif; ?>
                      <?php if ( $duration ) : ?><b class="stv-yt-duration"><?php echo esc_html( $duration ); ?></b><?php endif; ?>
                    </a>
                    <div class="stv-yt-card-body">
                      <img class="stv-yt-avatar" src="<?php echo esc_url( $avatar ); ?>" alt="">
                      <div>
                        <a class="stv-yt-card-title" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        <div class="stv-yt-author"><?php echo esc_html( $author ? $author->display_name : 'Creator' ); ?></div>
                        <div class="stv-yt-meta"><?php echo esc_html( number_format_i18n( $views ) ); ?> views · <?php echo esc_html( human_time_diff( get_post_time( 'U', true, $vid ), current_time( 'timestamp' ) ) ); ?> ago</div>
                      </div>
                    </div>
                  </article>
                  <?php
              endwhile;
              echo '</div>';
              wp_reset_postdata();
          else :
              echo '<div class="stv-yt-empty"><strong>No videos yet</strong><span>Upload your first video to start building the feed.</span></div>';
          endif;
          ?>
        </section>

        <section class="stv-yt-section" style="padding-top:42px">
          <div class="stv-yt-section-head"><h2>Trending</h2><a href="<?php echo esc_url( $trending_url ); ?>">See trending →</a></div>
          <?php echo stv_render_feed( array( 'per_page' => 8, 'orderby' => 'views' ) ); ?>
        </section>
      </main>
    </div>
    <?php
    get_footer();
    return;
endif;

get_footer();
