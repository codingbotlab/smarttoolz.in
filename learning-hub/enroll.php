<?php
declare(strict_types=1);
require_once __DIR__.'/includes/bootstrap.php';

$slug=trim((string)($_GET['slug']??''));
$user=lh_user();
if(!$user){
  header('Location:/creator-ai/auth/google-login.php?return='.rawurlencode('/learning-hub/course.php?slug='.$slug));
  exit;
}

try{
  if($slug==='') throw new RuntimeException('Course not specified.');
  $q=$db->prepare('SELECT id,slug,title FROM learning_courses WHERE slug=? AND enabled=1 LIMIT 1');
  $q->execute([$slug]);
  $course=$q->fetch(PDO::FETCH_ASSOC)?:null;
  if(!$course) throw new RuntimeException('Course not found.');

  $q=$db->prepare("INSERT INTO learning_enrollments(user_id,course_id,status,progress_percent,enrolled_at,last_accessed_at) VALUES(?,?, 'enrolled',0,CURRENT_TIMESTAMP,CURRENT_TIMESTAMP) ON DUPLICATE KEY UPDATE status='enrolled',last_accessed_at=CURRENT_TIMESTAMP");
  $q->execute([(int)$user['id'],(int)$course['id']]);

  $q=$db->prepare("INSERT INTO learning_events(user_id,event_name,entity_type,entity_id,metadata) VALUES(?,?,?,?,?)");
  $q->execute([(int)$user['id'],'course_enrolled','course',(int)$course['id'],json_encode(['course_slug'=>$course['slug'],'course_title'=>$course['title']],JSON_UNESCAPED_UNICODE)]);

  $lessonSlug='';
  $hasChapterCol=(int)$db->query("SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='learning_lessons' AND column_name='chapter_id'")->fetchColumn()>0;
  $hasChapters=(int)$db->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name='learning_chapters'")->fetchColumn()>0;
  if($hasChapterCol && $hasChapters){
    try{
      $q=$db->prepare("SELECT l.slug FROM learning_lessons l JOIN learning_chapters ch ON ch.id=l.chapter_id WHERE ch.course_id=? AND l.enabled=1 ORDER BY ch.sort_order,ch.id,l.sort_order,l.id LIMIT 1");
      $q->execute([(int)$course['id']]);
      $lessonSlug=(string)($q->fetchColumn()?:'');
    }catch(Throwable){}
  }
  if($lessonSlug===''){
    $q=$db->prepare('SELECT slug FROM learning_lessons WHERE course_id=? AND enabled=1 ORDER BY sort_order,id LIMIT 1');
    $q->execute([(int)$course['id']]);
    $lessonSlug=(string)($q->fetchColumn()?:'');
  }

  $target=$lessonSlug
    ? '/learning-hub/lesson.php?slug='.rawurlencode($lessonSlug)
    : '/learning-hub/course.php?slug='.rawurlencode($course['slug']);
  header('Location:'.$target);
  exit;
}catch(Throwable $e){
  http_response_code(500);
  $lh_page_title='Enrollment error';
  include __DIR__.'/includes/header.php';
  include __DIR__.'/includes/navbar.php';
  ?><main class="container py-5"><div class="alert alert-danger"><strong>Unable to start this course.</strong><div class="mt-1"><?=lh_h($e->getMessage())?></div></div><a class="btn btn-primary" href="/learning-hub/courses.php">Back to Courses</a></main><?php
  include __DIR__.'/includes/footer.php';
}
