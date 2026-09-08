<?php
/**
 * Theme header.
 *
 * @package SmartToolz
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<header class="st-site-header">
    <div class="st-container st-header-inner">
        <a class="st-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
            <?php
            if ( has_custom_logo() ) {
                the_custom_logo();
            } else {
                echo esc_html( get_bloginfo( 'name' ) );
            }
            ?>
        </a>
        <nav class="st-menu" aria-label="<?php esc_attr_e( 'Primary Menu', 'smarttoolz' ); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'container'      => false,
                    'fallback_cb'    => false,
                    'items_wrap'     => '%3$s',
                )
            );
            ?>
        </nav>
    </div>
</header>
<main class="st-main">
    <div class="st-container st-content">
