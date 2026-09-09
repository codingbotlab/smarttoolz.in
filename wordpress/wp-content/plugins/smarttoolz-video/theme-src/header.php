<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class( 'smarttoolz-video-theme' ); ?>>
<?php wp_body_open(); ?>
<div class="stv-site">
    <header class="stv-header">
        <div class="stv-header__inner">
            <a class="stv-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
                <span class="stv-brand__mark">▶</span>
                <span class="stv-brand__text">SmartToolz</span>
            </a>
            <form class="stv-search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="Search videos">
                <button type="submit" aria-label="Search">⌕</button>
            </form>
            <div class="stv-header__actions">
                <a class="stv-header__button" href="<?php echo esc_url( home_url( '/video/upload/' ) ); ?>">Create</a>
                <?php if ( is_user_logged_in() ) : ?>
                    <a class="stv-header__button stv-header__button--primary" href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>">Sign out</a>
                <?php else : ?>
                    <a class="stv-header__button stv-header__button--primary" href="<?php echo esc_url( wp_login_url( home_url( '/' ) ) ); ?>">Sign in</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <div class="stv-layout">
        <aside class="stv-sidebar">
            <a class="stv-sidebar__link stv-sidebar__link--active" href="<?php echo esc_url( home_url( '/video/' ) ); ?>">⌂ <span>Home</span></a>
            <a class="stv-sidebar__link" href="<?php echo esc_url( home_url( '/video/shorts/' ) ); ?>">◉ <span>Shorts</span></a>
            <a class="stv-sidebar__link" href="<?php echo esc_url( home_url( '/video/subscriptions/' ) ); ?>">▣ <span>Subscriptions</span></a>
            <a class="stv-sidebar__link" href="<?php echo esc_url( home_url( '/video/history/' ) ); ?>">◷ <span>History</span></a>
            <a class="stv-sidebar__link" href="<?php echo esc_url( home_url( '/video/liked/' ) ); ?>">♡ <span>Liked videos</span></a>
            <a class="stv-sidebar__link" href="<?php echo esc_url( home_url( '/video/playlists/' ) ); ?>">☷ <span>Playlists</span></a>
            <a class="stv-sidebar__link" href="<?php echo esc_url( home_url( '/video/your-videos/' ) ); ?>">▸ <span>Your videos</span></a>
        </aside>
        <main class="stv-main">
