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
                <a class="stv-chip <?php echo 'home' === $route ? 'stv-chip--active' : ''; ?>" href="<?php echo esc_url( home_url( '/video/' ) ); ?>">All</a>
                <a class="stv-chip <?php echo 'shorts' === $route ? 'stv-chip--active' : ''; ?>" href="<?php echo esc_url( home_url( '/video/shorts/' ) ); ?>">Shorts</a>
                <a class="stv-chip <?php echo 'trending' === $route ? 'stv-chip--active' : ''; ?>" href="<?php echo esc_url( home_url( '/video/trending/' ) ); ?>">Trending</a>
                <a class="stv-chip <?php echo 'live' === $route ? 'stv-chip--active' : ''; ?>" href="<?php echo esc_url( home_url( '/video/live/' ) ); ?>">Live</a>
                <a class="stv-chip <?php echo 'subscriptions' === $route ? 'stv-chip--active' : ''; ?>" href="<?php echo esc_url( home_url( '/video/subscriptions/' ) ); ?>">Subscriptions</a>
            </div>

            <?php if ( 'home' === $route ) : ?>
                <section class="stv-home">
                    <div class="stv-section-heading">
                        <div><h1 class="stv-page-title">SmartToolz Videos</h1><p class="stv-section-subtitle">Discover videos from creators on SmartToolz.</p></div>
                        <?php if ( is_user_logged_in() ) : ?><a class="stv-view-link" href="<?php echo esc_url( smarttoolz_video_route_url( 'your-videos' ) ); ?>">Your uploads</a><?php endif; ?>
                    </div>
                    <div class="stv-video-grid">
                        <?php
                        $videos = get_posts( array( 'post_type' => 'stv_video', 'post_status' => 'publish', 'posts_per_page' => 12, 'orderby' => 'date', 'order' => 'DESC' ) );
                        if ( empty( $videos ) ) {
                            echo '<div class="stv-empty" style="grid-column:1/-1;">No videos have been published yet. Be the first creator to upload one.</div>';
                        } else {
                            foreach ( $videos as $video ) {
                                $source = smarttoolz_video_get_source( $video->ID );
                                echo '<article class="stv-video-card">';
                                echo '<a class="stv-video-card__thumb" href="' . esc_url( smarttoolz_video_route_url( 'watch', $video->ID ) ) . '">';
                                if ( has_post_thumbnail( $video->ID ) ) { echo get_the_post_thumbnail( $video->ID, 'medium_large' ); }
                                echo '<span class="stv-video-card__play">▶</span><span class="stv-video-card__duration">Video</span></a>';
                                echo '<div class="stv-video-card__body"><span class="stv-video-card__avatar">' . esc_html( strtoupper( substr( get_the_author_meta( 'display_name', $video->post_author ) ?: 'S', 0, 1 ) ) ) . '</span><div><a class="stv-video-card__title" href="' . esc_url( smarttoolz_video_route_url( 'watch', $video->ID ) ) . '">' . esc_html( $video->post_title ) . '</a><div class="stv-video-card__meta">' . esc_html( get_the_author_meta( 'display_name', $video->post_author ) ) . ' · ' . esc_html( get_the_date( '', $video ) ) . '</div></div></div>';
                                echo '</article>';
                            }
                        }
                        ?>
                    </div>
                </section>
            <?php elseif ( 'watch' === $route ) : ?>
                <?php smarttoolz_video_render_watch( absint( $id ) ); ?>
            <?php elseif ( 'upload' === $route || 'your-videos' === $route ) : ?>
                <?php smarttoolz_video_manage_page(); ?>
            <?php elseif ( 'account' === $route ) : ?>
                <?php $user = wp_get_current_user(); ?>
                <section class="stv-account-hero">
                    <?php if ( is_user_logged_in() ) : ?>
                        <div class="stv-account-avatar"><?php echo esc_html( strtoupper( substr( $user->display_name ?: $user->user_login, 0, 1 ) ) ); ?></div>
                        <div><span class="stv-eyebrow">Your Account</span><h1 class="stv-page-title">Hi, <?php echo esc_html( $user->display_name ?: $user->user_login ); ?></h1><p><?php echo esc_html( $user->user_email ); ?></p><a class="stv-button" href="<?php echo esc_url( home_url( '/video/account/edit-profile/' ) ); ?>">Edit profile</a></div>
                    <?php else : ?><div class="stv-account-avatar">?</div><div><span class="stv-eyebrow">Your Account</span><h1 class="stv-page-title">Sign in to SmartToolz</h1><p>Access your channel, subscriptions, playlists, history and personal settings.</p><a class="stv-button" href="<?php echo esc_url( wp_login_url( home_url( '/video/account/' ) ) ); ?>">Sign in</a></div><?php endif; ?>
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
    if ( smarttoolz_video_is_route() ) { smarttoolz_video_render_app(); }
    return $template;
}
add_filter( 'template_include', 'smarttoolz_video_route_template', 99 );
