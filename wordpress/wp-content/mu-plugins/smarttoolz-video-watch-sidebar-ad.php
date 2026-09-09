<?php
/**
 * SmartToolz Video - watch sidebar ad bridge.
 *
 * Keeps sidebar ads independent from the player-wide "Video ads" switch.
 * The existing watch template already provides the sidebar/Next structure;
 * this bridge fills it with the active Sidebar ad creative.
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

    // Sidebar placement has its own switch. Do not require the player-wide
    // video-ad switch, because image/video sidebar ads are independent.
    if ( empty( $settings['sidebar'] ) ) {
        return $html;
    }

    $creative = null;
    if ( ! empty( $settings['creatives']['sidebar'] ) && is_array( $settings['creatives']['sidebar'] ) ) {
        foreach ( $settings['creatives']['sidebar'] as $ad ) {
            if ( ! empty( $ad['active'] ) && ! empty( $ad['src'] ) ) {
                $creative = $ad;
                break;
            }
        }
    }

    if ( ! is_array( $creative ) ) {
        return $html;
    }

    $src       = esc_url( $creative['src'] );
    $type      = ( 'image' === ( $creative['type'] ?? '' ) ) ? 'image' : 'video';
    $title     = sanitize_text_field( $creative['title'] ?? '' );
    $text      = sanitize_text_field( $creative['text'] ?? '' );
    $cta_url   = esc_url( $creative['url'] ?? '' );

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

    // Hide the old placeholder and insert the real active ad immediately
    // before the Next-video list.
    $html = preg_replace(
        '/(<div[^>]+class=["\'][^"\']*stv-watch-sidebar__next[^"\']*["\'][^>]*>)/i',
        '<style>.stv-watch-sidebar__ad--placeholder{display:none!important}.stv-watch-sidebar__ad--live{display:block!important}.stv-watch-sidebar__ad--live img,.stv-watch-sidebar__ad--live video{display:block;width:100%;aspect-ratio:16/9;object-fit:cover;background:#000}</style>' . $ad_html . '$1',
        $html,
        1
    );

    return is_string( $html ) ? $html : '';
}
