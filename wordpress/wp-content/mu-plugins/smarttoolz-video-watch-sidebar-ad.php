<?php
/**
 * SmartToolz Video - watch sidebar ad bridge.
 *
 * The watch-page sidebar must render ONLY creatives assigned to the
 * "Sidebar ad" placement. Player placements such as pre-roll, bumper,
 * mid-roll, post-roll and pause ads must never be copied into the sidebar.
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_action(
    'template_redirect',
    function () {
        if ( 'watch' !== get_query_var( 'smarttoolz_video_route' ) ) {
            return;
        }

        ob_start( 'smarttoolz_video_watch_sidebar_ad_output' );
    },
    0
);

function smarttoolz_video_watch_sidebar_ad_output( $html ) {
    if ( ! function_exists( 'smarttoolz_video_ads_settings' ) ) {
        return $html;
    }

    $settings = smarttoolz_video_ads_settings();

    // Locate the watch sidebar and replace only its ad area. This removes any
    // ad that may have been rendered there by older watch-page code, including
    // pre-roll/bumpers/mid-roll/post-roll/pause creatives.
    $aside_open = '<aside class="stv-watch-sidebar">';
    $next_marker = '<div class="stv-watch-sidebar__heading"';
    $aside_pos = strpos( $html, $aside_open );

    if ( false === $aside_pos ) {
        return $html;
    }

    $next_pos = strpos( $html, $next_marker, $aside_pos + strlen( $aside_open ) );
    if ( false === $next_pos ) {
        return $html;
    }

    // If Sidebar ads are disabled, leave only the Next-video section.
    if ( empty( $settings['sidebar'] ) ) {
        $html = substr_replace(
            $html,
            '',
            $aside_pos + strlen( $aside_open ),
            $next_pos - ( $aside_pos + strlen( $aside_open ) )
        );
        return $html;
    }

    // IMPORTANT: only read from the sidebar placement. Never fall back to
    // another placement when no sidebar creative exists.
    $active_ads = array();
    $sidebar_ads = isset( $settings['creatives']['sidebar'] ) && is_array( $settings['creatives']['sidebar'] )
        ? $settings['creatives']['sidebar']
        : array();

    foreach ( $sidebar_ads as $ad ) {
        if ( ! empty( $ad['active'] ) && ! empty( $ad['src'] ) ) {
            $active_ads[] = $ad;
        }
    }

    // No active Sidebar ad: remove the ad area completely rather than showing
    // a creative belonging to another placement.
    if ( empty( $active_ads ) ) {
        $html = substr_replace(
            $html,
            '',
            $aside_pos + strlen( $aside_open ),
            $next_pos - ( $aside_pos + strlen( $aside_open ) )
        );
        return $html;
    }

    // Multiple Sidebar ads are rotated randomly, but only within this
    // placement.
    $creative = $active_ads[ wp_rand( 0, count( $active_ads ) - 1 ) ];

    $src     = esc_url( $creative['src'] );
    $type    = ( 'image' === ( $creative['type'] ?? '' ) ) ? 'image' : 'video';
    $title   = sanitize_text_field( $creative['title'] ?? '' );
    $text    = sanitize_text_field( $creative['text'] ?? '' );
    $cta_url = esc_url( $creative['url'] ?? '' );

    if ( 'image' === $type ) {
        $media = '<img src="' . $src . '" alt="' . esc_attr( $title ?: 'Advertisement' ) . '" loading="lazy">';
    } else {
        $media = '<video src="' . $src . '" autoplay muted loop playsinline preload="metadata"></video>';
    }

    $ad_html  = '<div class="stv-watch-sidebar__ad stv-watch-sidebar__ad--live">';
    $ad_html .= '<div class="stv-watch-sidebar__ad-label">Advertisement</div>';
    $ad_html .= '<div class="stv-watch-sidebar__ad-media">' . $media . '</div>';

    if ( '' !== $title || '' !== $text || '' !== $cta_url ) {
        $ad_html .= '<div class="stv-watch-sidebar__ad-body">';
        if ( '' !== $title ) {
            $ad_html .= '<strong class="stv-watch-sidebar__ad-title">' . esc_html( $title ) . '</strong>';
        }
        if ( '' !== $text ) {
            $ad_html .= '<span class="stv-watch-sidebar__ad-text">' . esc_html( $text ) . '</span>';
        }
        if ( '' !== $cta_url ) {
            $ad_html .= '<a class="stv-watch-sidebar__ad-link" href="' . $cta_url . '" target="_blank" rel="noopener noreferrer">Learn more</a>';
        }
        $ad_html .= '</div>';
    }

    $ad_html .= '</div>';

    $styles = '<style>
        .stv-watch-sidebar__ad--placeholder{display:none!important}
        .stv-watch-sidebar__ad--live{display:block!important}
        .stv-watch-sidebar__ad--live img,.stv-watch-sidebar__ad--live video{display:block;width:100%;aspect-ratio:16/9;object-fit:cover;background:#000}
    </style>';

    // Replace the complete existing sidebar-ad area immediately before Next.
    $replacement = $styles . $ad_html;
    $html = substr_replace(
        $html,
        $replacement,
        $aside_pos + strlen( $aside_open ),
        $next_pos - ( $aside_pos + strlen( $aside_open ) )
    );

    return is_string( $html ) ? $html : '';
}
