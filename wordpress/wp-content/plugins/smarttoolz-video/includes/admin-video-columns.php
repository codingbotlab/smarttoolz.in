<?php
/** SmartToolz Video admin list enhancements. */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function smarttoolz_video_admin_columns( $columns ) {
    $new = array();
    foreach ( $columns as $key => $label ) {
        $new[ $key ] = $label;
        if ( 'title' === $key ) {
            $new['st_video_category'] = __( 'Category', 'smarttoolz-video' );
        }
    }
    if ( ! isset( $new['st_video_category'] ) ) {
        $new['st_video_category'] = __( 'Category', 'smarttoolz-video' );
    }
    return $new;
}
add_filter( 'manage_st_video_posts_columns', 'smarttoolz_video_admin_columns' );

function smarttoolz_video_admin_column_content( $column, $post_id ) {
    if ( 'st_video_category' !== $column ) { return; }
    $terms = get_the_terms( $post_id, 'st_video_category' );
    if ( empty( $terms ) || is_wp_error( $terms ) ) {
        echo '<span class="description">' . esc_html__( 'Uncategorized', 'smarttoolz-video' ) . '</span>';
        return;
    }
    $links = array();
    foreach ( $terms as $term ) {
        $url = get_edit_term_link( $term->term_id, 'st_video_category', 'st_video' );
        $links[] = $url ? '<a href="' . esc_url( $url ) . '">' . esc_html( $term->name ) . '</a>' : esc_html( $term->name );
    }
    echo implode( ', ', $links );
}
add_action( 'manage_st_video_posts_custom_column', 'smarttoolz_video_admin_column_content', 10, 2 );

function smarttoolz_video_admin_sortable_columns( $columns ) {
    $columns['st_video_category'] = 'st_video_category';
    return $columns;
}
add_filter( 'manage_edit-st_video_sortable_columns', 'smarttoolz_video_admin_sortable_columns' );
