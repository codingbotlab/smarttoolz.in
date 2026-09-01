<?php
declare(strict_types=1);
require_once __DIR__.'/includes/bootstrap.php';

$slug=trim((string)($_GET['slug']??''));
$lesson=null;$course=null;$lessons=[];$previous=null;$next=null;$error=null;

try{
 $q=$db->prepare("SELECT l.* FROM learning_lessons l WHERE l.slug=? AND l.enabled=1 LIMIT 1");
 $q->execute([$slug]);$lesson=$q->fetch(PDO::FETCH_ASSOC)?:null;
}catch(Throwable $e){$error=$e->getMessage();}

if(!$lesson){
 http_response_code(404);$lh_page_title='Lesson not found';$lh_description='The requested lesson could not be found.';
 include __DIR__.'/includes/header.php';include __DIR__.'/includes/navbar.php';
 ?><main class="container py-5"><div class="lh-empty p-5 text-center"><div class="fs-1">📚</div><h1 class="h4 mt-3">Lesson not found</h1><p class="text-secondary">This lesson may have been moved or unpublished.</p><a class="btn btn-primary" href="/learning-hub/courses.php">Browse Courses</a></div></main><?php include __DIR__.'/includes/footer.php';exit;
}

$courseId=(int)($lesson['course_id']??0);
if(!$courseId && !empty($lesson['chapter_id'])){
 try{$q=$db->prepare('SELECT course_id FROM learning_chapters WHERE id=? LIMIT 1');$q->execute([(int)$lesson['chapter_id']]);$courseId=(int)$q->fetchColumn();}catch(Throwable){}
}
if($courseId){
 try{$q=$db->prepare("SELECT c.*,lc.name category_name,lc.icon category_icon,lc.slug category_slug FROM learning_courses c LEFT JOIN learning_categories lc ON lc.id=c.category_id WHERE c.id=? AND c.enabled=1 LIMIT 1");$q->execute([$courseId]);$course=$q->fetch(PDO::FETCH_ASSOC)?:null;}catch(Throwable){}
}
if(!$course && !empty($lesson['chapter_id'])){
 try{$q=$db->prepare("SELECT c.*,lc.name category_name,lc.icon category_icon,lc.slug category_slug FROM learning_lessons l JOIN learning_chapters ch ON ch.id=l.chapter_id JOIN learning_courses c ON c.id=ch.course_id LEFT JOIN learning_categories lc ON lc.id=c.category_id WHERE l.id=? LIMIT 1");$q->execute([(int)$lesson['id']]);$course=$q->fetch(PDO::FETCH_ASSOC)?:null;}catch(Throwable){}
}
if(!$course){
 http_response_code(404);exit('Course not found.');
}

try{
 $q=$db->prepare("SELECT l.id,l.title,l.slug,l.sort_order FROM learning_lessons l LEFT JOIN learning_chapters ch ON ch.id=l.chapter_id WHERE l.enabled=1 AND ((l.course_id=? ) OR (ch.course_id=?)) ORDER BY COALESCE(ch.sort_order,0),COALESCE(ch.id,0),l.sort_order,l.id");
 $q->execute([$courseId,$courseId]);$lessons=$q->fetchAll(PDO::FETCH_ASSOC);
}catch(Throwable){
 try{$q=$db->prepare('SELECT id,title,slug,sort_order FROM learning_lessons WHERE course_id=? AND enabled=1 ORDER BY sort_order,id');$q->execute([$courseId]);$lessons=$q->fetchAll(PDO::FETCH_ASSOC);}catch(Throwable){$lessons=[];}
}
foreach($lessons as $i=>$row){if((int)$row['id']===(int)$lesson['id']){$previous=$lessons[$i-1]??null;$next=$lessons[$i+1]??null;break;}}

$lh_page_title=(string)$lesson['title'];$lh_description='Learn '.(string)$lesson['title'].' in SmartToolz Learning Hub.';
include __DIR__.'/includes/header.php';include __DIR__.'/includes/navbar.php';
?><link rel="stylesheet" href="/learning-hub/assets/css/lesson.css">
<div class="container-fluid"><div class="row"><div class="col-lg-3 col-xl-2 p-0"><?php include __DIR__.'/includes/sidebar.php';?></div><main class="col-lg-9 col-xl-10 py-4 py-lg-5"><div class="container-fluid"><div class="lh-lesson-layout">
<aside class="lh-lesson-nav"><div class="fw-bold mb-2"><?=lh_h($course['title'])?></div><?php foreach($lessons as $row):?><a class="<?=((int)$row['id']===(int)$lesson['id'])?'active':''?>" href="/learning-hub/lesson.php?slug=<?=rawurlencode((string)$row['slug'])?>"><?=lh_h($row['title'])?></a><?php endforeach;?></aside>
<section class="lh-lesson-main"><div class="d-flex justify-content-between small mb-3"><a href="/learning-hub/course.php?slug=<?=rawurlencode((string)$course['slug'])?>" class="text-primary text-decoration-none">← <?=lh_h($course['title'])?></a><span class="text-secondary"><?=(int)$lesson['id']?><?=count($lessons)?' · '.number_format(array_search((int)$lesson['id'],array_column($lessons,'id'),true)+1).' / '.number_format(count($lessons)):''?></span></div><div class="progress lh-progress mb-4"><div class="progress-bar" style="width:<?=count($lessons)?round(((array_search((int)$lesson['id'],array_column($lessons,'id'),true)+1)/count($lessons))*100):0?>%"></div></div><article class="lh-card p-4 p-md-5"><span class="badge text-bg-primary mb-3"><?=lh_h($course['category_name']??'Learning Hub')?></span><h1 class="display-6 fw-bold"><?=lh_h($lesson['title'])?></h1><div class="lh-prose mt-4"><?=($lesson['content']??'')?:'<p class="text-secondary">This lesson is available and ready to study. Detailed content is being prepared.</p>'?></div></article><div class="lh-next mt-4 p-3 d-flex justify-content-between gap-2"><a class="btn btn-outline-primary <?=empty($previous)?'disabled':''?>" href="<?=empty($previous)?'#':'/learning-hub/lesson.php?slug='.rawurlencode((string)$previous['slug'])?>">← Previous</a><a class="btn btn-primary <?=empty($next)?'disabled':''?>" href="<?=empty($next)?'#':'/learning-hub/lesson.php?slug='.rawurlencode((string)$next['slug'])?>"><?=empty($next)?'Course Complete':'Next Lesson →'?></a></div><div class="lh-ad-slot my-4">Advertisement</div></section></div></div></main></div></div><?php include __DIR__.'/includes/footer.php';?><script src="/learning-hub/assets/js/app.js"></script>