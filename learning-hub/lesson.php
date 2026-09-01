<?php
declare(strict_types=1);
ini_set('display_errors','0');
error_reporting(E_ALL);
require_once __DIR__.'/includes/bootstrap.php';
require_once __DIR__.'/includes/catalog.php';

$slug=trim((string)($_GET['slug']??''));
$lesson=$slug?lh_lesson($db,$slug):null;
if(!$lesson){http_response_code(404);exit('Lesson not found.');}

$courseId=0;
if(isset($lesson['course_id']))$courseId=(int)$lesson['course_id'];
if(!$courseId && isset($lesson['chapter_id']) && lh_has_table($db,'learning_chapters') && lh_has_column($db,'learning_chapters','course_id')){
    try{$q=$db->prepare('SELECT course_id FROM learning_chapters WHERE id=? LIMIT 1');$q->execute([(int)$lesson['chapter_id']]);$courseId=(int)$q->fetchColumn();}catch(Throwable){}
}
$course=$courseId?null:null;
if($courseId){try{$q=$db->prepare('SELECT c.*,lc.name category_name,lc.icon category_icon,lc.slug category_slug FROM learning_courses c LEFT JOIN learning_categories lc ON lc.id=c.category_id WHERE c.id=? LIMIT 1');$q->execute([$courseId]);$course=$q->fetch(PDO::FETCH_ASSOC)?:null;}catch(Throwable){}}
if(!$course && !empty($lesson['course_slug']))$course=lh_course($db,(string)$lesson['course_slug']);
if(!$course){http_response_code(404);exit('Course not found.');}

$lessonList=[];$position=0;$previous=null;$next=null;
try{
    $lessonList=lh_lessons($db,(int)$course['id']);
    foreach($lessonList as $i=>$row){if((int)$row['id']===(int)$lesson['id']){$position=$i+1;$previous=$lessonList[$i-1]??null;$next=$lessonList[$i+1]??null;break;}}
}catch(Throwable){}

$user=lh_user();$done=false;
if($user && lh_has_table($db,'learning_progress')){try{$q=$db->prepare('SELECT completed FROM learning_progress WHERE user_id=? AND lesson_id=? LIMIT 1');$q->execute([(int)$user['id'],(int)$lesson['id']);$done=(bool)$q->fetchColumn();}catch(Throwable){}}
if($_SERVER['REQUEST_METHOD']==='POST'&&$user&&lh_has_table($db,'learning_progress')){try{$q=$db->prepare('INSERT INTO learning_progress(user_id,lesson_id,completed,progress_percent) VALUES(?,?,1,100) ON DUPLICATE KEY UPDATE completed=1,progress_percent=100');$q->execute([(int)$user['id'],(int)$lesson['id']);$done=true;}catch(Throwable){}}

$lh_page_title=(string)$lesson['title'];$lh_description='Learn '.(string)$lesson['title'].' in SmartToolz Learning Hub.';
include __DIR__.'/includes/header.php';include __DIR__.'/includes/navbar.php';
?><link rel="stylesheet" href="/learning-hub/assets/css/lesson.css">
<div class="container-fluid"><div class="row"><div class="col-lg-3 col-xl-2 p-0"><?php include __DIR__.'/includes/sidebar.php';?></div><main class="col-lg-9 col-xl-10 py-4 py-lg-5"><div class="container-fluid"><div class="lh-lesson-layout">
<aside class="lh-lesson-nav"><div class="fw-bold mb-2"><?=lh_h($course['title'])?></div><?php foreach($lessonList as $item):?><a class="<?=((int)$item['id']===(int)$lesson['id'])?'active':''?>" href="/learning-hub/lesson.php?slug=<?=rawurlencode((string)$item['slug'])?>"><?=lh_h($item['title'])?></a><?php endforeach;?></aside>
<section class="lh-lesson-main"><div class="d-flex justify-content-between small mb-3"><a href="/learning-hub/course.php?slug=<?=rawurlencode((string)$course['slug'])?>" class="text-primary text-decoration-none">← <?=lh_h($course['title'])?></a><span class="text-secondary">Lesson <?=number_format($position)?> / <?=number_format(count($lessonList))?></span></div><div class="progress lh-progress mb-4"><div class="progress-bar" style="width:<?=count($lessonList)?round($position/count($lessonList)*100):0?>%"></div></div><article class="lh-card p-4 p-md-5"><span class="badge text-bg-primary mb-3"><?=lh_h($course['category_name']??'Learning Hub')?></span><h1 class="display-6 fw-bold"><?=lh_h($lesson['title'])?></h1><div class="lh-prose mt-4"><?=($lesson['content']??'')?:'<p class="text-secondary">Lesson content is being prepared.</p>'?></div><?php if($user):?><form method="post" class="mt-4"><button class="btn <?=$done?'btn-success':'btn-primary'?>" type="submit"><?=$done?'✓ Lesson completed':'Mark lesson complete'?></button></form><?php else:?><div class="alert alert-light border mt-4">Login to save your learning progress.</div><?php endif;?></article><div class="lh-next mt-4 p-3 d-flex justify-content-between gap-2"><a class="btn btn-outline-primary <?=empty($previous)?'disabled':''?>" href="<?=empty($previous)?'#':'/learning-hub/lesson.php?slug='.rawurlencode((string)$previous['slug'])?>">← Previous</a><a class="btn btn-primary <?=empty($next)?'disabled':''?>" href="<?=empty($next)?'#':'/learning-hub/lesson.php?slug='.rawurlencode((string)$next['slug'])?>"><?=empty($next)?'Course Complete':'Next Lesson →'?></a></div><div class="lh-ad-slot my-4">Advertisement</div></section></div></div></main></div></div><?php include __DIR__.'/includes/footer.php';?><script src="/learning-hub/assets/js/app.js"></script><script src="/learning-hub/assets/js/router.js"></script>