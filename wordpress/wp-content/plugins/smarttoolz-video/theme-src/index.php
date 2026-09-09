<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>
<div class="stv-content">
    <h1 class="stv-page-title"><?php echo esc_html( get_the_title() ?: 'SmartToolz' ); ?></h1>
    <div class="stv-empty">SmartToolz video platform is ready.</div>
</div>
<?php get_footer(); ?>
