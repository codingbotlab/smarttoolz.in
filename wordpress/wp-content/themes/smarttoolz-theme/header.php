<?php
/** SmartToolz site header. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?><!doctype html>
<html <?php language_attributes(); ?>><head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?></head>
<body <?php body_class(); ?>><?php wp_body_open(); ?>
<a class="st-skip-link" href="#st-main-content"><?php esc_html_e( 'Skip to content', 'smarttoolz' ); ?></a>
<header class="st-site-header">
  <div class="st-container st-header-inner">
    <a class="st-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="SmartToolz home">
      <?php if ( has_custom_logo() ) : the_custom_logo(); else : ?><span class="st-logo-mark" aria-hidden="true">S</span><span>SmartToolz</span><?php endif; ?>
    </a>
    <button type="button" class="st-menu-toggle" aria-expanded="false" aria-controls="st-primary-menu" aria-label="<?php esc_attr_e( 'Open menu', 'smarttoolz' ); ?>">☰</button>
    <div id="st-primary-menu" class="st-header-controls">
      <nav class="st-menu" aria-label="<?php esc_attr_e( 'Primary Menu', 'smarttoolz' ); ?>">
        <?php wp_nav_menu( array( 'theme_location' => 'primary', 'container' => false, 'fallback_cb' => 'smarttoolz_fallback_menu', 'items_wrap' => '<ul class="st-nav-list">%3$s</ul>' ) ); ?>
      </nav>
      <?php if ( function_exists( 'smarttoolz_video_register_post_type' ) ) : ?>
        <nav class="stv-header-nav" aria-label="Video navigation">
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
          <a href="<?php echo esc_url( get_post_type_archive_link( 'st_video' ) ); ?>">Videos</a>
          <?php if ( is_user_logged_in() ) : ?><a href="<?php echo esc_url( home_url( '/video-upload/' ) ); ?>">Upload</a><a href="<?php echo esc_url( home_url( '/creator-studio/' ) ); ?>">Studio</a><?php endif; ?>
        </nav>
      <?php endif; ?>
      <div class="st-header-tools">
        <div class="st-header-search">
          <?php if ( function_exists( 'smarttoolz_is_video_context' ) && smarttoolz_is_video_context() ) : ?>
            <form class="st-search-form" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>"><label class="screen-reader-text" for="stv-header-search">Search videos</label><input class="st-search-field" id="stv-header-search" name="stv_search" type="search" value="<?php echo isset( $_GET['stv_search'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['stv_search'] ) ) ) : ''; ?>" placeholder="Search videos..."><button class="st-search-submit" type="submit">Search</button></form>
          <?php else : get_search_form(); endif; ?>
        </div>
        <?php if ( get_theme_mod( 'smarttoolz_dark_mode', 0 ) ) : ?><button type="button" class="st-theme-toggle" aria-pressed="false" aria-label="<?php esc_attr_e( 'Toggle dark mode', 'smarttoolz' ); ?>">◐ Dark</button><?php endif; ?>
      </div>
    </div>
  </div>
</header>
<main id="st-main-content" class="st-main"><div class="st-container st-content">
