<form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
<label class="screen-reader-text" for="smarttoolz-search"><?php esc_html_e('Search for:', 'smarttoolz-blog'); ?></label>
<input type="search" id="smarttoolz-search" class="search-field" placeholder="<?php echo esc_attr_x('Search…', 'placeholder', 'smarttoolz-blog'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
<button type="submit" class="search-submit"><?php esc_html_e('Search', 'smarttoolz-blog'); ?></button>
</form>
