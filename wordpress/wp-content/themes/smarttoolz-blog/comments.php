<?php if (post_password_required()) return; ?>
<section id="comments" class="comments-area">
<?php if (have_comments()) : ?>
  <h2><?php comments_number(__('Comments','smarttoolz-blog'), __('1 Comment','smarttoolz-blog'), __('% Comments','smarttoolz-blog')); ?></h2>
  <ol class="comment-list"><?php wp_list_comments(array('style'=>'ol','short_ping'=>true,'avatar_size'=>44)); ?></ol>
  <?php the_comments_navigation(); ?>
<?php endif; ?>
<?php if (comments_open()) : ?>
  <?php comment_form(array('title_reply'=>__('Join the conversation','smarttoolz-blog'),'class_submit'=>'submit')); ?>
<?php endif; ?>
</section>
