<?php
/**
 * SmartToolz Video frontend application shell.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function smarttoolz_video_account_card_link( $route, $label, $description ) {
    $url = home_url( '/video/' . trim( str_replace( '-', '/', $route ), '/' ) . '/' );
    return '<a class="stv-account-link" href="' . esc_url( $url ) . '"><strong>' . esc_html( $label ) . '</strong><span>' . esc_html( $description ) . '</span></a>';
}

function smarttoolz_video_render_app() {
    $route = get_query_var( 'smarttoolz_video_route', 'home' );
    $id    = get_query_var( 'smarttoolz_video_id', '' );

    get_header();
    ?>
        <div class="stv-app" data-route="<?php echo esc_attr( $route ); ?>" data-video-id="<?php echo esc_attr( $id ); ?>">
            <div class="stv-chips">
                <a class="stv-chip stv-chip--active" href="<?php echo esc_url( home_url( '/video/' ) ); ?>">All</a>
                <a class="stv-chip" href="<?php echo esc_url( home_url( '/video/shorts/' ) ); ?>">Shorts</a>
                <a class="stv-chip" href="<?php echo esc_url( home_url( '/video/trending/' ) ); ?>">Trending</a>
                <a class="stv-chip" href="<?php echo esc_url( home_url( '/video/live/' ) ); ?>">Live</a>
                <a class="stv-chip" href="<?php echo esc_url( home_url( '/video/subscriptions/' ) ); ?>">Subscriptions</a>
            </div>

            <?php if ( 'home' === $route ) : ?>
                <h1 class="stv-page-title">SmartToolz Videos</h1>
                <div class="stv-hero__box">
                    <h2 style="margin-top:0">Your video-sharing home is ready.</h2>
                    <p style="color:#aaa">The SmartToolz interface is installed and ready for the video features we build next.</p>
                </div>
            <?php elseif ( 'watch' === $route ) : ?>
                <h1 class="stv-page-title">Watch</h1>
                <div class="stv-empty">Video ID: <?php echo esc_html( $id ); ?></div>
            <?php elseif ( 'upload' === $route ) : ?>
                <h1 class="stv-page-title">Upload Video</h1>
                <div class="stv-empty">Upload interface will be added here.</div>
            <?php elseif ( 'account' === $route ) : ?>
                <?php $user = wp_get_current_user(); ?>
                <section class="stv-account-hero">
                    <?php if ( is_user_logged_in() ) : ?>
                        <div class="stv-account-avatar"><?php echo esc_html( strtoupper( substr( $user->display_name ?: $user->user_login, 0, 1 ) ) ); ?></div>
                        <div>
                            <span class="stv-eyebrow">Your Account</span>
                            <h1 class="stv-page-title">Hi, <?php echo esc_html( $user->display_name ?: $user->user_login ); ?></h1>
                            <p><?php echo esc_html( $user->user_email ); ?></p>
                            <a class="stv-button" href="<?php echo esc_url( home_url( '/video/account/edit-profile/' ) ); ?>">Edit profile</a>
                        </div>
                    <?php else : ?>
                        <div class="stv-account-avatar">?</div>
                        <div>
                            <span class="stv-eyebrow">Your Account</span>
                            <h1 class="stv-page-title">Sign in to SmartToolz</h1>
                            <p>Access your channel, subscriptions, playlists, history and personal settings.</p>
                            <a class="stv-button" href="<?php echo esc_url( wp_login_url( home_url( '/video/account/' ) ) ); ?>">Sign in</a>
                        </div>
                    <?php endif; ?>
                </section>
                <section class="stv-account-grid">
                    <?php
                    echo smarttoolz_video_account_card_link( 'account/edit-profile', 'Edit Profile', 'Name, avatar and profile details' );
                    echo smarttoolz_video_account_card_link( 'channel', 'Your Channel', 'Channel home, videos and live content' );
                    echo smarttoolz_video_account_card_link( 'subscriptions', 'Subscriptions', 'Channels and creators you follow' );
                    echo smarttoolz_video_account_card_link( 'playlists', 'Playlists', 'Manage your saved collections' );
                    echo smarttoolz_video_account_card_link( 'history', 'History', 'Recently watched videos' );
                    echo smarttoolz_video_account_card_link( 'watch-later', 'Watch Later', 'Videos you saved for later' );
                    echo smarttoolz_video_account_card_link( 'liked-videos', 'Liked Videos', 'Videos you have liked' );
                    echo smarttoolz_video_account_card_link( 'your-videos', 'Your Videos', 'Manage the videos you publish' );
                    echo smarttoolz_video_account_card_link( 'account/comments', 'Comments & Activity', 'Your comments and account activity' );
                    echo smarttoolz_video_account_card_link( 'account/podcasts', 'Your Podcasts', 'Podcasts associated with your account' );
                    echo smarttoolz_video_account_card_link( 'account/badges', 'Badges', 'Achievements and earned badges' );
                    echo smarttoolz_video_account_card_link( 'account/privacy', 'Privacy & Your Data', 'Privacy and personal data controls' );
                    echo smarttoolz_video_account_card_link( 'settings', 'Settings', 'General SmartToolz preferences' );
                    echo smarttoolz_video_account_card_link( 'notifications', 'Notifications', 'Manage alerts and activity' );
                    ?>
                </section>
            <?php elseif ( 0 === strpos( $route, 'account-' ) ) : ?>
                <h1 class="stv-page-title"><?php echo esc_html( ucwords( str_replace( array( 'account-', '-' ), array( '', ' ' ), $route ) ) ); ?></h1>
                <div class="stv-empty">This account section is ready for its feature implementation.</div>
            <?php else : ?>
                <h1 class="stv-page-title"><?php echo esc_html( ucwords( str_replace( '-', ' ', $route ) ) ); ?></h1>
                <div class="stv-empty">This SmartToolz section is ready for its feature implementation.</div>
            <?php endif; ?>
        </div>
    <?php
    get_footer();
    exit;
}

function smarttoolz_video_route_template( $template ) {
    if ( smarttoolz_video_is_route() ) {
        smarttoolz_video_render_app();
    }

    return $template;
}
add_filter( 'template_include', 'smarttoolz_video_route_template', 99 );
