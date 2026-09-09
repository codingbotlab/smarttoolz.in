<?php
/** SmartToolz site header. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$is_video = function_exists( 'smarttoolz_is_video_context' ) && smarttoolz_is_video_context();
?><!doctype html>
<html <?php language_attributes(); ?>><head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
<?php if ( $is_video ) : ?><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6TaO0Kf7WJ3VnqWw2f8wN8FZ6rQ8hV2ZqN0s3Q9Gm8w8TQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style id="smarttoolz-video-account-inline-css">
body.smarttoolz-video-platform .stv-header-menu{position:relative;flex:0 0 auto}
body.smarttoolz-video-platform .stv-menu-trigger{display:inline-flex;align-items:center;justify-content:center;width:42px;height:42px;border:1px solid #e5e5e5;border-radius:50%;background:#fff;color:#0f0f0f;cursor:pointer}
body.smarttoolz-video-platform .stv-menu-trigger i{font-size:16px}
body.smarttoolz-video-platform .stv-header-menu[open] .stv-menu-trigger{background:#f2f2f2}
body.smarttoolz-video-platform .stv-header-menu summary::-webkit-details-marker,body.smarttoolz-video-platform .stv-account-menu summary::-webkit-details-marker{display:none}
body.smarttoolz-video-platform .stv-nav-dropdown{position:absolute;left:0;top:calc(100% + 8px);width:270px;padding:8px;background:#fff;border:1px solid #ddd;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,.16);z-index:1000}
body.smarttoolz-video-platform .stv-nav-dropdown a{display:flex;align-items:center;gap:12px;min-height:42px;padding:0 11px;border-radius:8px;color:#0f0f0f;text-decoration:none;font-size:.9rem;font-weight:600}
body.smarttoolz-video-platform .stv-nav-dropdown a:hover{background:#f2f2f2}
body.smarttoolz-video-platform .stv-nav-dropdown i{width:18px;text-align:center;color:#606060}
body.smarttoolz-video-platform .stv-nav-section{padding:7px 11px 5px;color:#606060;font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em}
body.smarttoolz-video-platform .stv-nav-separator{height:1px;background:#eee;margin:6px 4px}
body.smarttoolz-video-platform .stv-account-menu{position:relative;z-index:1000;flex:0 0 auto}
body.smarttoolz-video-platform .stv-account-trigger{display:inline-flex;align-items:center;gap:7px;min-height:42px;padding:3px 9px 3px 4px;border:1px solid #e5e5e5;border-radius:999px;background:#fff;color:#0f0f0f;cursor:pointer;list-style:none;white-space:nowrap;user-select:none}
body.smarttoolz-video-platform .stv-account-avatar{display:inline-grid;place-items:center;width:36px;height:36px;border-radius:50%;overflow:hidden;background:#f2f2f2;color:#0f0f0f;font-weight:800;flex:0 0 36px}
body.smarttoolz-video-platform .stv-account-avatar img{display:block;width:100%;height:100%;object-fit:cover}
body.smarttoolz-video-platform .stv-account-name{max-width:145px;overflow:hidden;text-overflow:ellipsis;font-size:.88rem;font-weight:700}
body.smarttoolz-video-platform .stv-account-chevron{font-size:16px;line-height:1;transform:translateY(-1px)}
body.smarttoolz-video-platform .stv-account-menu[open] .stv-account-chevron{transform:rotate(180deg)}
body.smarttoolz-video-platform .stv-account-dropdown{position:absolute;right:0;top:calc(100% + 8px);width:290px;padding:8px;background:#fff;border:1px solid #ddd;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,.16)}
body.smarttoolz-video-platform .stv-account-dropdown a{display:flex;align-items:center;gap:11px;min-height:40px;padding:0 11px;border-radius:8px;color:#0f0f0f;text-decoration:none;font-size:.9rem;font-weight:600}
body.smarttoolz-video-platform .stv-account-dropdown a:hover{background:#f2f2f2}
body.smarttoolz-video-platform .stv-account-dropdown i{width:18px;text-align:center;color:#606060}
body.smarttoolz-video-platform .stv-account-head{display:flex;align-items:center;gap:11px;padding:10px 9px 12px;border-bottom:1px solid #eee;margin-bottom:5px}
body.smarttoolz-video-platform .stv-account-head strong{display:block;font-size:.92rem}
body.smarttoolz-video-platform .stv-account-head small{display:block;margin-top:3px;color:#606060;font-size:.74rem;max-width:190px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
body.smarttoolz-video-platform .stv-account-separator{height:1px;background:#eee;margin:7px 4px}
body.smarttoolz-video-platform .stv-account-dropdown .stv-account-logout{color:#c00}
body.smarttoolz-video-platform .stv-auth-dropdown{padding:16px;width:285px}
body.smarttoolz-video-platform .stv-auth-title{font-size:1.05rem;font-weight:800;margin:2px 4px 5px}
body.smarttoolz-video-platform .stv-auth-dropdown p{margin:0 4px 13px;color:#606060;font-size:.82rem;line-height:1.45}
body.smarttoolz-video-platform .stv-auth-dropdown a.stv-auth-primary,body.smarttoolz-video-platform .stv-auth-dropdown a.stv-auth-secondary{justify-content:center;margin-top:7px;font-weight:800;text-align:center}
body.smarttoolz-video-platform .stv-auth-primary{background:#ff0000;color:#fff!important}
body.smarttoolz-video-platform .stv-auth-primary:hover{background:#cc0000!important}
body.smarttoolz-video-platform .stv-auth-secondary{border:1px solid #d5d5d5;background:#fff}
@media(max-width:1050px){body.smarttoolz-video-platform .st-video-header .st-header-inner{flex-wrap:nowrap}.st-video-header .st-header-controls{display:flex;align-items:center}.st-video-header .st-header-tools{margin-left:auto}}
@media(max-width:700px){body.smarttoolz-video-platform .stv-account-name{display:none}body.smarttoolz-video-platform .stv-nav-dropdown{position:fixed;left:12px;top:76px;width:min(270px,calc(100vw - 24px))}body.smarttoolz-video-platform .stv-account-dropdown{position:fixed;right:12px;top:76px;width:min(290px,calc(100vw - 24px))}.st-video-header .st-header-search{width:min(46vw,340px);min-width:0}}
</style><?php endif; ?></head>
<body <?php body_class(); ?>><?php wp_body_open(); ?>
<a class="st-skip-link" href="#st-main-content"><?php esc_html_e( 'Skip to content', 'smarttoolz' ); ?></a>
<header class="st-site-header<?php echo $is_video ? ' st-video-header' : ''; ?>">
  <div class="st-container st-header-inner">
    <a class="st-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="SmartToolz home">
      <?php if ( has_custom_logo() ) : the_custom_logo(); else : ?><span class="st-logo-mark" aria-hidden="true">S</span><span>SmartToolz</span><?php endif; ?>
    </a>
    <div class="st-header-controls" id="st-primary-menu">
      <?php if ( $is_video ) : ?>
        <details class="stv-header-menu">
          <summary class="stv-menu-trigger" aria-label="Open video menu"><i class="fa-solid fa-bars" aria-hidden="true"></i></summary>
          <div class="stv-nav-dropdown">
            <div class="stv-nav-section">SmartToolz Video</div>
            <?php $st_menu = function_exists( 'smarttoolz_video_menu_items' ) ? smarttoolz_video_menu_items() : array(); ?>
            <?php foreach ( $st_menu as $label => $url ) : if ( ! $url ) { continue; } ?>
              <?php
              $icon = 'fa-circle-play';
              if ( 'Videos' === $label ) { $icon = 'fa-video'; }
              elseif ( 'Trending' === $label ) { $icon = 'fa-fire'; }
              elseif ( 'Categories' === $label ) { $icon = 'fa-layer-group'; }
              elseif ( 'Search' === $label ) { $icon = 'fa-magnifying-glass'; }
              elseif ( 'Subscriptions' === $label ) { $icon = 'fa-bell'; }
              elseif ( 'Liked' === $label ) { $icon = 'fa-thumbs-up'; }
              elseif ( 'History' === $label ) { $icon = 'fa-clock-rotate-left'; }
              elseif ( 'Upload' === $label ) { $icon = 'fa-upload'; }
              elseif ( 'Creator Studio' === $label ) { $icon = 'fa-chart-line'; }
              elseif ( 'My Channel' === $label ) { $icon = 'fa-user'; }
              ?>
              <a href="<?php echo esc_url( $url ); ?>"><i class="fa-solid <?php echo esc_attr( $icon ); ?>" aria-hidden="true"></i><span><?php echo esc_html( $label ); ?></span></a>
            <?php endforeach; ?>
          </div>
        </details>
      <?php else : ?>
        <nav class="st-menu" aria-label="<?php esc_attr_e( 'Primary Menu', 'smarttoolz' ); ?>">
          <?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'fallback_cb' => 'smarttoolz_fallback_menu', 'items_wrap' => '<ul class="st-nav-list">%3$s</ul>' ) ); ?>
        </nav>
      <?php endif; ?>
      <div class="st-header-tools">
        <div class="st-header-search">
          <?php if ( $is_video ) : ?>
            <form class="st-search-form" method="get" action="<?php echo esc_url( smarttoolz_video_page_link( 'video-search' ) ); ?>">
              <label class="screen-reader-text" for="stv-header-search">Search videos</label>
              <input class="st-search-field" id="stv-header-search" name="stv_search" type="search" value="<?php echo isset( $_GET['stv_search'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['stv_search'] ) ) ) : ''; ?>" placeholder="Search videos...">
              <button class="st-search-submit" type="submit" aria-label="Search"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i></button>
            </form>
          <?php else : get_search_form(); endif; ?>
        </div>
        <?php if ( get_theme_mod( 'smarttoolz_dark_mode', 0 ) ) : ?><button type="button" class="st-theme-toggle" aria-pressed="false" aria-label="<?php esc_attr_e( 'Toggle dark mode', 'smarttoolz' ); ?>">◐ Dark</button><?php endif; ?>

        <?php if ( is_user_logged_in() ) : ?>
          <?php $st_current_user = wp_get_current_user(); ?>
          <details class="stv-account-menu">
            <summary class="stv-account-trigger" aria-label="Account menu">
              <span class="stv-account-avatar"><?php echo get_avatar( $st_current_user->ID, 36 ); ?></span>
              <span class="stv-account-name"><?php echo esc_html( $st_current_user->display_name ); ?></span>
              <span class="stv-account-chevron" aria-hidden="true">⌄</span>
            </summary>
            <div class="stv-account-dropdown">
              <div class="stv-account-head">
                <span class="stv-account-avatar stv-account-avatar-lg"><?php echo get_avatar( $st_current_user->ID, 52 ); ?></span>
                <div><strong><?php echo esc_html( $st_current_user->display_name ); ?></strong><small><?php echo esc_html( $st_current_user->user_email ); ?></small></div>
              </div>
              <a href="<?php echo esc_url( get_edit_profile_url( $st_current_user->ID ) ); ?>"><i class="fa-solid fa-user" aria-hidden="true"></i>Profile</a>
              <a href="<?php echo esc_url( smarttoolz_video_page_link( 'channel' ) ); ?>"><i class="fa-solid fa-tv" aria-hidden="true"></i>My Channel</a>
              <a href="<?php echo esc_url( smarttoolz_video_page_link( 'creator-studio' ) ); ?>"><i class="fa-solid fa-chart-line" aria-hidden="true"></i>Creator Studio</a>
              <a href="<?php echo esc_url( smarttoolz_video_page_link( 'video-upload' ) ); ?>"><i class="fa-solid fa-upload" aria-hidden="true"></i>Upload Video</a>
              <a href="<?php echo esc_url( smarttoolz_video_page_link( 'subscriptions' ) ); ?>"><i class="fa-solid fa-bell" aria-hidden="true"></i>Subscriptions</a>
              <a href="<?php echo esc_url( smarttoolz_video_page_link( 'liked-videos' ) ); ?>"><i class="fa-solid fa-thumbs-up" aria-hidden="true"></i>Liked Videos</a>
              <a href="<?php echo esc_url( smarttoolz_video_page_link( 'watch-history' ) ); ?>"><i class="fa-solid fa-clock-rotate-left" aria-hidden="true"></i>Watch History</a>
              <div class="stv-account-separator"></div>
              <a class="stv-account-logout" href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>"><i class="fa-solid fa-right-from-bracket" aria-hidden="true"></i>Log out</a>
            </div>
          </details>
        <?php else : ?>
          <details class="stv-account-menu stv-auth-menu">
            <summary class="stv-account-trigger stv-login-trigger">
              <span class="stv-account-avatar stv-guest-avatar"><i class="fa-regular fa-circle-user" aria-hidden="true"></i></span>
              <span class="stv-account-name">Sign in</span>
              <span class="stv-account-chevron" aria-hidden="true">⌄</span>
            </summary>
            <div class="stv-account-dropdown stv-auth-dropdown">
              <div class="stv-auth-title">Join SmartToolz</div>
              <p>Sign in to like videos, follow creators, keep watch history and upload.</p>
              <a class="stv-auth-primary" href="<?php echo esc_url( wp_login_url( $is_video ? get_permalink() : home_url( '/' ) ) ); ?>"><i class="fa-solid fa-right-to-bracket" aria-hidden="true"></i>Log in</a>
              <a class="stv-auth-secondary" href="<?php echo esc_url( wp_registration_url() ); ?>"><i class="fa-solid fa-user-plus" aria-hidden="true"></i>Create account</a>
            </div>
          </details>
        <?php endif; ?>
      </div>
    </div>
  </div>
</header>
<main id="st-main-content" class="st-main"><div class="st-container st-content">
