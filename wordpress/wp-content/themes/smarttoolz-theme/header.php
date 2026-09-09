<?php
/** SmartToolz site header. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
$is_video = function_exists( 'smarttoolz_is_video_context' ) && smarttoolz_is_video_context();
?><!doctype html>
<html <?php language_attributes(); ?>><head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?></head>
<body <?php body_class(); ?>><?php wp_body_open(); ?>
<a class="st-skip-link" href="#st-main-content"><?php esc_html_e( 'Skip to content', 'smarttoolz' ); ?></a>
<header class="st-site-header<?php echo $is_video ? ' st-video-header' : ''; ?>">
  <div class="st-container st-header-inner">
    <a class="st-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="SmartToolz home">
      <?php if ( has_custom_logo() ) : the_custom_logo(); else : ?><span class="st-logo-mark" aria-hidden="true">S</span><span>SmartToolz</span><?php endif; ?>
    </a>
    <button type="button" class="st-menu-toggle" aria-expanded="false" aria-controls="st-primary-menu" aria-label="<?php esc_attr_e( 'Open menu', 'smarttoolz' ); ?>">☰</button>
    <div id="st-primary-menu" class="st-header-controls">
      <?php if ( $is_video ) : ?>
        <nav class="stv-header-nav stv-related-pages" aria-label="Video platform navigation">
          <?php
          if ( function_exists( 'smarttoolz_video_menu_items' ) ) {
              foreach ( smarttoolz_video_menu_items() as $label => $url ) {
                  if ( ! $url ) { continue; }
                  echo '<a href="' . esc_url( $url ) . '">' . esc_html( $label ) . '</a>';
              }
          } else {
              echo '<a href="' . esc_url( home_url( '/' ) ) . '">Video Home</a>';
          }
          ?>
        </nav>
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
              <button class="st-search-submit" type="submit">Search</button>
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
              <a href="<?php echo esc_url( get_edit_profile_url( $st_current_user->ID ) ); ?>">Profile</a>
              <a href="<?php echo esc_url( smarttoolz_video_page_link( 'channel' ) ); ?>">My Channel</a>
              <a href="<?php echo esc_url( smarttoolz_video_page_link( 'creator-studio' ) ); ?>">Creator Studio</a>
              <a href="<?php echo esc_url( smarttoolz_video_page_link( 'video-upload' ) ); ?>">Upload Video</a>
              <a href="<?php echo esc_url( smarttoolz_video_page_link( 'subscriptions' ) ); ?>">Subscriptions</a>
              <a href="<?php echo esc_url( smarttoolz_video_page_link( 'liked-videos' ) ); ?>">Liked Videos</a>
              <a href="<?php echo esc_url( smarttoolz_video_page_link( 'watch-history' ) ); ?>">Watch History</a>
              <div class="stv-account-separator"></div>
              <a class="stv-account-logout" href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>">Log out</a>
            </div>
          </details>
        <?php else : ?>
          <details class="stv-account-menu stv-auth-menu">
            <summary class="stv-account-trigger stv-login-trigger">
              <span class="stv-account-avatar stv-guest-avatar" aria-hidden="true">☺</span>
              <span class="stv-account-name">Sign in</span>
              <span class="stv-account-chevron" aria-hidden="true">⌄</span>
            </summary>
            <div class="stv-account-dropdown stv-auth-dropdown">
              <div class="stv-auth-title">Join SmartToolz</div>
              <p>Sign in to like videos, follow creators, keep watch history and upload.</p>
              <a class="stv-auth-primary" href="<?php echo esc_url( wp_login_url( $is_video ? get_permalink() : home_url( '/' ) ) ); ?>">Log in</a>
              <a class="stv-auth-secondary" href="<?php echo esc_url( wp_registration_url() ); ?>">Create account</a>
            </div>
          </details>
        <?php endif; ?>
      </div>
    </div>
  </div>
</header>
<main id="st-main-content" class="st-main"><div class="st-container st-content">
