<?php
/**
 * SmartToolz Video frontend application shell.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function smarttoolz_video_account_card_link( $route, $label, $description ) {
    $url = smarttoolz_video_route_url( $route );
    return '<a class="stv-account-link" href="' . esc_url( $url ) . '"><strong>' . esc_html( $label ) . '</strong><span>' . esc_html( $description ) . '</span></a>';
}

function smarttoolz_video_render_app() {
    $route = get_query_var( 'smarttoolz_video_route', 'home' );
    $id    = get_query_var( 'smarttoolz_video_id', '' );

    get_header();
    ?>
        <div class="stv-app" data-route="<?php echo esc_attr( $route ); ?>" data-video-id="<?php echo esc_attr( $id ); ?>">
            <div class="stv-chips">
                <?php if ( smarttoolz_video_route_enabled( 'home' ) ) : ?><a class="stv-chip <?php echo 'home' === $route ? 'stv-chip--active' : ''; ?>" href="<?php echo esc_url( smarttoolz_video_route_url( 'home' ) ); ?>">All</a><?php endif; ?>
                <?php if ( smarttoolz_video_route_enabled( 'shorts' ) ) : ?><a class="stv-chip <?php echo 'shorts' === $route ? 'stv-chip--active' : ''; ?>" href="<?php echo esc_url( smarttoolz_video_route_url( 'shorts' ) ); ?>">Shorts</a><?php endif; ?>
                <?php if ( smarttoolz_video_route_enabled( 'trending' ) ) : ?><a class="stv-chip <?php echo 'trending' === $route ? 'stv-chip--active' : ''; ?>" href="<?php echo esc_url( smarttoolz_video_route_url( 'trending' ) ); ?>">Trending</a><?php endif; ?>
                <?php if ( smarttoolz_video_route_enabled( 'live' ) ) : ?><a class="stv-chip <?php echo 'live' === $route ? 'stv-chip--active' : ''; ?>" href="<?php echo esc_url( smarttoolz_video_route_url( 'live' ) ); ?>">Live</a><?php endif; ?>
                <?php if ( smarttoolz_video_route_enabled( 'subscriptions' ) ) : ?><a class="stv-chip <?php echo 'subscriptions' === $route ? 'stv-chip--active' : ''; ?>" href="<?php echo esc_url( smarttoolz_video_route_url( 'subscriptions' ) ); ?>">Subscriptions</a><?php endif; ?>
            </div>

            <?php if ( 'home' === $route ) : ?>
                <section class="stv-home">
                    <div class="stv-section-heading">
                        <div><h1 class="stv-page-title">SmartToolz Videos</h1><p class="stv-section-subtitle">Discover videos from creators on SmartToolz.</p></div>
                        <?php if ( is_user_logged_in() && smarttoolz_video_route_enabled( 'your-videos' ) ) : ?><a class="stv-view-link" href="<?php echo esc_url( smarttoolz_video_route_url( 'your-videos' ) ); ?>">Your uploads</a><?php endif; ?>
                    </div>
                    <div class="stv-video-grid">
                        <?php
                        $videos = get_posts( array( 'post_type' => 'stv_video', 'post_status' => 'publish', 'posts_per_page' => 12, 'orderby' => 'date', 'order' => 'DESC' ) );
                        if ( empty( $videos ) ) {
                            echo '<div class="stv-empty" style="grid-column:1/-1;">No videos have been published yet. Be the first creator to upload one.</div>';
                        } else {
                            foreach ( $videos as $video ) {
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
                <?php
                if ( ! is_user_logged_in() ) {
                    wp_safe_redirect( smarttoolz_video_route_url( 'login' ) );
                    exit;
                }
                $user = wp_get_current_user();
                $handle = get_user_meta( $user->ID, 'smarttoolz_channel_handle', true );
                if ( '' === $handle ) { $handle = $user->user_nicename; }
                $avatar = get_user_meta( $user->ID, 'smarttoolz_avatar_url', true );
                $initial = strtoupper( substr( $user->display_name ?: $user->user_login, 0, 1 ) );
                ?>
                <section class="stv-account-page">
                    <div class="stv-account-hero">
                        <?php if ( $avatar ) : ?>
                            <img class="stv-account-avatar" src="<?php echo esc_url( $avatar ); ?>" alt="<?php echo esc_attr( $user->display_name ); ?>">
                        <?php else : ?>
                            <div class="stv-account-avatar"><?php echo esc_html( $initial ); ?></div>
                        <?php endif; ?>
                        <div class="stv-account-identity">
                            <span class="stv-eyebrow">SmartToolz Account</span>
                            <h1 class="stv-page-title"><?php echo esc_html( $user->display_name ?: $user->user_login ); ?></h1>
                            <p>@<?php echo esc_html( $handle ); ?></p>
                            <div class="stv-account-actions">
                                <a class="stv-button" href="<?php echo esc_url( smarttoolz_video_route_url( 'channel', $handle ) ); ?>">View channel</a>
                                <a class="stv-button stv-button--secondary" href="<?php echo esc_url( smarttoolz_video_route_url( 'edit-profile' ) ); ?>">Edit profile</a>
                            </div>
                        </div>
                    </div>

                    <div class="stv-account-section-heading">
                        <h2>Your SmartToolz</h2>
                        <p>Manage your channel and personal video activity.</p>
                    </div>
                    <section class="stv-account-grid">
                        <?php
                        echo smarttoolz_video_account_card_link( 'channel', 'Your Channel', 'View and manage your creator channel' );
                        echo smarttoolz_video_account_card_link( 'your-videos', 'Your Videos', 'Manage videos you have published' );
                        echo smarttoolz_video_account_card_link( 'subscriptions', 'Subscriptions', 'Channels and creators you follow' );
                        echo smarttoolz_video_account_card_link( 'history', 'History', 'Videos you recently watched' );
                        echo smarttoolz_video_account_card_link( 'watch-later', 'Watch Later', 'Videos you saved to watch later' );
                        echo smarttoolz_video_account_card_link( 'liked-videos', 'Liked Videos', 'Videos you have liked' );
                        echo smarttoolz_video_account_card_link( 'playlists', 'Playlists', 'Manage your saved collections' );
                        echo smarttoolz_video_account_card_link( 'settings', 'Settings', 'Manage your SmartToolz preferences' );
                        ?>
                    </section>
                </section>
            <?php elseif ( 'login' === $route || 'signup' === $route ) : ?>
                <?php smarttoolz_video_render_login(); ?>
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