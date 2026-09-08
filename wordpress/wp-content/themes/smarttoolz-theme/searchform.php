<?php
/** SmartToolz search form. */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<form role="search" class="st-search-form" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
  <label class="screen-reader-text" for="st-search-field"><?php esc_html_e( 'Search for:', 'smarttoolz' ); ?></label>
  <input id="st-search-field" class="st-search-field" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search…', 'smarttoolz' ); ?>">
  <button class="st-search-submit" type="submit"><?php esc_html_e( 'Search', 'smarttoolz' ); ?></button>
</form>
