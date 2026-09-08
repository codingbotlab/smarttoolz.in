<?php
/** SmartToolz front page. */
get_header();
?>
<section class="st-hero"><div class="st-hero-grid">
<div><span class="st-eyebrow">SmartToolz</span><h1><?php esc_html_e( 'Useful tools. Beautifully simple.', 'smarttoolz' ); ?></h1><p><?php esc_html_e( 'A fast, focused home for practical online tools, guides and helpful content.', 'smarttoolz' ); ?></p><div class="st-actions"><a class="st-button" href="#latest"><?php esc_html_e( 'Explore content', 'smarttoolz' ); ?></a><a class="st-button st-secondary" href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"><?php esc_html_e( 'Visit blog', 'smarttoolz' ); ?></a></div></div>
<div class="st-hero-card"><strong><?php esc_html_e( 'SmartToolz', 'smarttoolz' ); ?></strong><p><?php esc_html_e( 'Fast pages, clean design and a distraction-free experience across desktop and mobile.', 'smarttoolz' ); ?></p><div class="st-stat-row"><div class="st-stat"><b>Fast</b><span><?php esc_html_e( 'Lightweight theme', 'smarttoolz' ); ?></span></div><div class="st-stat"><b>Clean</b><span><?php esc_html_e( 'Simple interface', 'smarttoolz' ); ?></span></div></div></div>
</div></section>
<section class="st-section" id="latest"><div class="st-section-head"><h2><?php esc_html_e( 'Latest from SmartToolz', 'smarttoolz' ); ?></h2><a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>"><?php esc_html_e( 'View all', 'smarttoolz' ); ?></a></div>
<div class="st-grid">
<?php
$latest = new WP_Query( array( 'posts_per_page' => 6, 'post_status' => 'publish' ) );
if ( $latest->have_posts() ) : while ( $latest->have_posts() ) : $latest->the_post(); ?>
<article class="st-card st-feature"><div class="st-meta"><?php echo esc_html( get_the_date() ); ?></div><h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3><p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p><a class="st-button" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read article', 'smarttoolz' ); ?></a></article>
<?php endwhile; wp_reset_postdata(); else: ?>
<article class="st-card"><h3><?php esc_html_e( 'Your content starts here.', 'smarttoolz' ); ?></h3><p><?php esc_html_e( 'Create your first WordPress post and it will appear here automatically.', 'smarttoolz' ); ?></p></article>
<?php endif; ?></div></section>
<?php get_footer(); ?>
