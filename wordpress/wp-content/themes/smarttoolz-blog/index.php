<?php get_header(); ?>

<main id="primary" class="site-main">
    <section class="hero">
        <div class="container hero-grid">
            <div class="hero-copy">
                <span class="kicker"><?php echo esc_html__('Blog & Magazine', 'smarttoolz-blog'); ?></span>
                <h1><?php echo esc_html(get_bloginfo('description') ?: __('Ideas worth reading. Stories worth sharing.', 'smarttoolz-blog')); ?></h1>
                <p><?php echo esc_html__('Publish useful stories, guides and ideas with a clean editorial layout. Everything below comes from your WordPress content.', 'smarttoolz-blog'); ?></p>
            </div>
            <div class="cover">
                <div>
                    <span class="kicker"><?php esc_html_e('Latest post', 'smarttoolz-blog'); ?></span>
                    <?php $featured = new WP_Query(array('posts_per_page'=>1,'post_status'=>'publish','ignore_sticky_posts'=>false)); ?>
                    <?php if ($featured->have_posts()) : while ($featured->have_posts()) : $featured->the_post(); ?>
                        <?php if (has_post_thumbnail()) : ?><a class="cover-image" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('large'); ?></a><?php endif; ?>
                        <strong><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></strong>
                        <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 22)); ?></p>
                    <?php endwhile; wp_reset_postdata(); else : ?>
                        <strong><?php esc_html_e('Add your first post', 'smarttoolz-blog'); ?></strong>
                        <p><?php esc_html_e('Your latest published article will appear here automatically.', 'smarttoolz-blog'); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <div class="container content-with-sidebar">
        <div class="content-column">
            <section class="stories" id="stories">
                <div class="section-title"><h2><?php esc_html_e('Latest stories', 'smarttoolz-blog'); ?></h2><span><?php echo esc_html(wp_count_posts()->publish); ?> <?php esc_html_e('published', 'smarttoolz-blog'); ?></span></div>
                <?php
                $posts = new WP_Query(array('posts_per_page'=>8,'post_status'=>'publish','ignore_sticky_posts'=>true,'paged'=>1));
                if ($posts->have_posts()) : while ($posts->have_posts()) : $posts->the_post();
                ?>
                    <article <?php post_class('story'); ?>>
                        <?php if (has_post_thumbnail()) : ?><a class="story-thumb" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('medium_large'); ?></a><?php endif; ?>
                        <div class="story-body">
                            <div class="tag"><?php the_category(', '); ?></div>
                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                            <p><?php echo esc_html(wp_trim_words(get_the_excerpt(), 24)); ?></p>
                            <div class="story-meta"><span><?php echo esc_html(get_the_date('F j, Y')); ?></span><span><?php echo esc_html(get_the_author()); ?></span></div>
                            <div class="post-tags"><?php the_tags('', ' · ', ''); ?></div>
                        </div>
                    </article>
                <?php endwhile; else : ?><div class="empty"><?php esc_html_e('No published posts yet. Create one from Posts → Add New.', 'smarttoolz-blog'); ?></div><?php endif; wp_reset_postdata(); ?>
                <?php if ($posts->max_num_pages > 1) : ?><div class="home-more"><a class="button" href="<?php echo esc_url(get_permalink(get_option('page_for_posts')) ?: home_url('/')); ?>"><?php esc_html_e('View all stories', 'smarttoolz-blog'); ?></a></div><?php endif; ?>
            </section>
        </div>
        <?php get_sidebar(); ?>
    </div>

    <section class="stories taxonomy-showcase">
        <div class="container">
            <div class="section-title"><h2><?php esc_html_e('Browse topics', 'smarttoolz-blog'); ?></h2><span><?php esc_html_e('Categories & tags', 'smarttoolz-blog'); ?></span></div>
            <div class="category-grid">
                <?php foreach (get_categories(array('hide_empty'=>true)) as $cat) : ?>
                    <a class="category-card" href="<?php echo esc_url(get_category_link($cat->term_id)); ?>"><strong><?php echo esc_html($cat->name); ?></strong><span><?php echo esc_html($cat->count); ?> <?php esc_html_e('posts', 'smarttoolz-blog'); ?></span></a>
                <?php endforeach; ?>
            </div>
            <div class="section-title tags-title"><h2><?php esc_html_e('Popular tags', 'smarttoolz-blog'); ?></h2><span><?php esc_html_e('Explore keywords', 'smarttoolz-blog'); ?></span></div>
            <div class="tag-cloud"><?php wp_tag_cloud(array('smallest'=>12,'largest'=>20,'unit'=>'px','number'=>30,'orderby'=>'count','order'=>'DESC')); ?></div>
        </div>
    </section>
</main>

<?php get_footer(); ?>