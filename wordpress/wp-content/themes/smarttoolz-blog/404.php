<?php get_header(); ?>
<main class="not-found"><div class="container"><span class="kicker"><?php esc_html_e('Page not found', 'smarttoolz-blog'); ?></span><h1>404</h1><p><?php esc_html_e('The page you are looking for may have moved. Try searching the journal or return home.', 'smarttoolz-blog'); ?></p><?php get_search_form(); ?><p><a class="button" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Back home', 'smarttoolz-blog'); ?></a></p></div></main>
<?php get_footer(); ?>
