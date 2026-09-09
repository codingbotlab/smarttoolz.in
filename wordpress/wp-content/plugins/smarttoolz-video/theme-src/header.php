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
            <div class="stv-brand-wrap">
                <button class="stv-brand__menu" type="button" aria-label="Open menu">☰</button>
                <a class="stv-brand" href="<?php echo esc_url( smarttoolz_video_theme_page_url( 'home', home_url( '/video/' ) ) ); ?>">
                    <span class="stv-brand__mark">▶</span>
                    <span class="stv-brand__text">SmartToolz</span>
                </a>
            </div>
            <form class="stv-search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="Search" aria-label="Search">
                <button type="submit" aria-label="Search">⌕</button>
            </form>
            <div class="stv-header__actions">
                <a class="stv-header__icon" href="<?php echo esc_url( smarttoolz_video_theme_page_url( 'notifications', home_url( '/video/notifications/' ) ) ); ?>" aria-label="Notifications">♢</a>
                <?php if ( is_user_logged_in() ) : ?>
                    <a class="stv-header__button" href="<?php echo esc_url( smarttoolz_video_theme_page_url( 'upload', home_url( '/video/upload/' ) ) ); ?>">＋ Create</a>
                    <?php $stv_user = wp_get_current_user(); ?>
                    <details class="stv-account-menu">
                        <summary class="stv-avatar" aria-label="Account">
                            <?php echo esc_html( strtoupper( substr( $stv_user->display_name ?: $stv_user->user_login, 0, 1 ) ) ); ?>
                        </summary>
                        <div class="stv-account-menu__panel">
                            <div class="stv-account-menu__identity">
                                <div class="stv-account-menu__avatar"><?php echo esc_html( strtoupper( substr( $stv_user->display_name ?: $stv_user->user_login, 0, 1 ) ) ); ?></div>
                                <div>
                                    <strong><?php echo esc_html( $stv_user->display_name ?: $stv_user->user_login ); ?></strong>
                                    <span><?php echo esc_html( $stv_user->user_email ); ?></span>
                                </div>
                            </div>
                            <a href="<?php echo esc_url( home_url( '/video/channel/' ) ); ?>">Your channel</a>
                            <a href="<?php echo esc_url( home_url( '/video/account/' ) ); ?>">Your account</a>
                            <a href="<?php echo esc_url( home_url( '/video/settings/' ) ); ?>">Settings</a>
                            <a href="<?php echo esc_url( home_url( '/video/notifications/' ) ); ?>">Notifications</a>
                            <a href="<?php echo esc_url( wp_logout_url( home_url( '/video/' ) ) ); ?>">Sign out</a>
                        </div>
                    </details>
                <?php else : ?>
                    <a class="stv-header__button stv-header__button--primary" href="<?php echo esc_url( home_url( '/video/login/' ) ); ?>">Sign in</a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <div class="stv-layout">
        <aside class="stv-sidebar">
            <div class="stv-sidebar__section">
                <a class="stv-sidebar__link stv-sidebar__link--active" href="<?php echo esc_url( smarttoolz_video_theme_page_url( 'home', home_url( '/video/' ) ) ); ?>"><span class="stv-sidebar__icon">⌂</span><span>Home</span></a>
                <a class="stv-sidebar__link" href="<?php echo esc_url( smarttoolz_video_theme_page_url( 'shorts', home_url( '/video/shorts/' ) ) ); ?>"><span class="stv-sidebar__icon">◉</span><span>Shorts</span></a>
                <a class="stv-sidebar__link" href="<?php echo esc_url( smarttoolz_video_theme_page_url( 'subscriptions', home_url( '/video/subscriptions/' ) ) ); ?>"><span class="stv-sidebar__icon">▣</span><span>Subscriptions</span></a>
            </div>
            <div class="stv-sidebar__section">
                <div class="stv-sidebar__label">You</div>
                <a class="stv-sidebar__link" href="<?php echo esc_url( smarttoolz_video_theme_page_url( 'account', home_url( '/video/account/' ) ) ); ?>"><span class="stv-sidebar__icon">◎</span><span>Your account</span></a>
                <a class="stv-sidebar__link" href="<?php echo esc_url( smarttoolz_video_theme_page_url( 'channel', home_url( '/video/channel/' ) ) ); ?>"><span class="stv-sidebar__icon">◉</span><span>Your channel</span></a>
                <a class="stv-sidebar__link" href="<?php echo esc_url( smarttoolz_video_theme_page_url( 'history', home_url( '/video/history/' ) ) ); ?>"><span class="stv-sidebar__icon">◷</span><span>History</span></a>
                <a class="stv-sidebar__link" href="<?php echo esc_url( smarttoolz_video_theme_page_url( 'liked-videos', home_url( '/video/liked/' ) ) ); ?>"><span class="stv-sidebar__icon">♡</span><span>Liked videos</span></a>
                <a class="stv-sidebar__link" href="<?php echo esc_url( smarttoolz_video_theme_page_url( 'playlists', home_url( '/video/playlists/' ) ) ); ?>"><span class="stv-sidebar__icon">☷</span><span>Playlists</span></a>
                <a class="stv-sidebar__link" href="<?php echo esc_url( smarttoolz_video_theme_page_url( 'watch-later', home_url( '/video/watch-later/' ) ) ); ?>"><span class="stv-sidebar__icon">◴</span><span>Watch later</span></a>
                <a class="stv-sidebar__link" href="<?php echo esc_url( smarttoolz_video_theme_page_url( 'your-videos', home_url( '/video/your-videos/' ) ) ); ?>"><span class="stv-sidebar__icon">▸</span><span>Your videos</span></a>
            </div>
            <div class="stv-sidebar__section">
                <div class="stv-sidebar__label">Explore</div>
                <a class="stv-sidebar__link" href="<?php echo esc_url( smarttoolz_video_theme_page_url( 'trending', home_url( '/video/trending/' ) ) ); ?>"><span class="stv-sidebar__icon">⌁</span><span>Trending</span></a>
                <a class="stv-sidebar__link" href="<?php echo esc_url( smarttoolz_video_theme_page_url( 'explore', home_url( '/video/explore/' ) ) ); ?>"><span class="stv-sidebar__icon">✦</span><span>Explore</span></a>
                <a class="stv-sidebar__link" href="<?php echo esc_url( smarttoolz_video_theme_page_url( 'live', home_url( '/video/live/' ) ) ); ?>"><span class="stv-sidebar__icon">●</span><span>Live</span></a>
                <a class="stv-sidebar__link" href="<?php echo esc_url( smarttoolz_video_theme_page_url( 'memberships', home_url( '/video/memberships/' ) ) ); ?>"><span class="stv-sidebar__icon">◆</span><span>Memberships</span></a>
                <a class="stv-sidebar__link" href="<?php echo esc_url( smarttoolz_video_theme_page_url( 'purchases', home_url( '/video/purchases/' ) ) ); ?>"><span class="stv-sidebar__icon">▱</span><span>Purchases</span></a>
            </div>
        </aside>
        <main class="stv-main">