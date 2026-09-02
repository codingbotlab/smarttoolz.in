<?php
declare(strict_types=1);
require_once __DIR__.'/includes/bootstrap.php';
require_once __DIR__.'/includes/premium_lessons.php';
require_once __DIR__.'/includes/lesson_overrides.php';
require_once __DIR__.'/includes/course4_lessons.php';
require_once __DIR__.'/includes/course16_lessons.php';
require_once __DIR__.'/includes/course16_lesson1.php';
require_once __DIR__.'/includes/course16_lesson2.php';
require_once __DIR__.'/includes/course16_lesson4.php';
require_once __DIR__.'/includes/course16_lesson5.php';
require_once __DIR__.'/includes/course16_lesson6.php';
require_once __DIR__.'/includes/course16_lesson7.php';
require_once __DIR__.'/includes/course16_lesson8.php';
require_once __DIR__.'/includes/course16_lesson9_computer_basics.php';
require_once __DIR__.'/includes/course16_lesson9.php';
require_once __DIR__.'/includes/course16_lesson10.php';
require_once __DIR__.'/includes/course16_lesson10_computer_basics.php';
require_once __DIR__.'/includes/course16_lesson11_computer_basics.php';
require_once __DIR__.'/includes/course16_lesson12_computer_basics.php';
require_once __DIR__.'/includes/course16_lesson13_computer_basics.php';
require_once __DIR__.'/includes/course16_lesson14_computer_basics.php';
require_once __DIR__.'/includes/course16_lesson15_computer_basics.php';
require_once __DIR__.'/includes/course16_lesson16_computer_basics.php';
require_once __DIR__.'/includes/course16_lesson17_computer_basics.php';

$slug=trim((string)($_GET['slug']??''));$lesson=null;$course=null;$lessons=[];$previous=null;$next=null;
try{$q=$db->prepare("SELECT l.* FROM learning_lessons l WHERE l.slug=? AND l.enabled=1 LIMIT 1");$q->execute([$slug]);$lesson=$q->fetch(PDO::FETCH_ASSOC)?:null;}catch(Throwable){}
if(!$lesson){http_response_code(404);$lh_page_title='Lesson not found';$lh_description='The requested lesson could not be found.';include __DIR__.'/includes/header.php';include __DIR__.'/includes/navbar.php';?><main class="container py-5"><div class="lh-empty p-5 text-center"><div class="fs-1">📚</div><h1 class="h4 mt-3">Lesson not found</h1><p class="text-secondary">This lesson may have been moved or unpublished.</p><a class="btn btn-primary" href="/learning-hub/courses.php">Browse Courses</a></div></main><?php include __DIR__.'/includes/footer.php';exit;}
try{lh_apply_premium_lesson($db,$lesson);}catch(Throwable $e){error_log('Learning Hub premium lesson: '.$e->getMessage());}
$courseId=(int)($lesson['course_id']??0);
if(!$courseId&&!empty($lesson['chapter_id'])){try{$q=$db->prepare('SELECT course_id FROM learning_chapters WHERE id=? LIMIT 1');$q->execute([(int)$lesson['chapter_id']]);$courseId=(int)$q->fetchColumn();}catch(Throwable){}}
if($courseId){try{$q=$db->prepare("SELECT c.*,lc.name category_name,lc.icon category_icon,lc.slug category_slug FROM learning_courses c LEFT JOIN learning_categories lc ON lc.id=c.category_id WHERE c.id=? AND c.enabled=1 LIMIT 1");$q->execute([$courseId]);$course=$q->fetch(PDO::FETCH_ASSOC)?:null;}catch(Throwable){}}
if(!$course&&!empty($lesson['chapter_id'])){try{$q=$db->prepare("SELECT c.*,lc.name category_name,lc.icon category_icon,lc.slug category_slug FROM learning_lessons l JOIN learning_chapters ch ON ch.id=l.chapter_id JOIN learning_courses c ON c.id=ch.course_id LEFT JOIN learning_categories lc ON lc.id=c.category_id WHERE l.id=? LIMIT 1");$q->execute([(int)$lesson['id']]);$course=$q->fetch(PDO::FETCH_ASSOC)?:null;}catch(Throwable){}}
if(!$course){http_response_code(404);exit('Course not found.');}
try{$q=$db->prepare("SELECT l.id,l.title,l.slug,l.sort_order FROM learning_lessons l LEFT JOIN learning_chapters ch ON ch.id=l.chapter_id WHERE l.enabled=1 AND ((l.course_id=?) OR (ch.course_id=?)) ORDER BY COALESCE(ch.sort_order,0),COALESCE(ch.id,0),l.sort_order,l.id");$q->execute([$courseId,$courseId]);$lessons=$q->fetchAll(PDO::FETCH_ASSOC);}catch(Throwable){try{$q=$db->prepare('SELECT id,title,slug,sort_order FROM learning_lessons WHERE course_id=? AND enabled=1 ORDER BY sort_order,id');$q->execute([$courseId]);$lessons=$q->fetchAll(PDO::FETCH_ASSOC);}catch(Throwable){$lessons=[];}}
$position=0;foreach($lessons as $i=>$row){if((int)$row['id']===(int)$lesson['id']){$position=$i+1;$previous=$lessons[$i-1]??null;$next=$lessons[$i+1]??null;break;}}
$override=null;
if ($slug === 'course-16-lesson-1') {$override=lh_course16_lesson1_override($lesson,$position);}
if ($slug === 'course-16-lesson-2') {$override=lh_course16_lesson2_override($lesson,$position);}
if ($slug === 'course-16-lesson-4') {$override=lh_course16_lesson4_override($lesson,$position);}
if ($slug === 'course-16-lesson-5') {$override=lh_course16_lesson5_override($lesson,$position);}
if ($slug === 'course-16-lesson-6') {$override=lh_course16_lesson6_override($lesson,$position);}
if ($slug === 'course-16-lesson-7') {$override=lh_course16_lesson7_override($lesson,$position);}
if ($slug === 'course-16-lesson-8') {$override=lh_course16_lesson8_override($lesson,$position);}
if ($slug === 'course-16-lesson-9') {$override=lh_course16_lesson9_computer_basics_override($lesson,$position);}
if ($slug === 'course-16-lesson-10') {$override=lh_course16_lesson10_computer_basics_override($lesson,$position);}
if ($slug === 'course-16-lesson-11') {$override=lh_course16_lesson11_computer_basics_override($lesson,$position);}
if ($slug === 'course-16-lesson-12') {$override=lh_course16_lesson12_computer_basics_override($lesson,$position);}
if ($slug === 'course-16-lesson-13') {$override=lh_course16_lesson13_computer_basics_override($lesson,$position);}
if ($slug === 'course-16-lesson-14') {$override=lh_course16_lesson14_computer_basics_override($lesson,$position);}
if ($slug === 'course-16-lesson-15') {$override=lh_course16_lesson15_computer_basics_override($lesson,$position);}
if ($slug === 'course-16-lesson-16') {$override=lh_course16_lesson16_computer_basics_override($lesson,$position);}
if ($slug === 'course-16-lesson-17') {$override=lh_course16_lesson17_computer_basics_override($lesson,$position);}
if (($slug === 'course-16-lesson-10' || $position === 10) && $override===null) {$override=lh_course16_lesson10_override($lesson,$position);}
if (($slug === 'course-16-lesson-9' || $position === 9) && $override===null) {$override=lh_course16_lesson9_override($lesson,$position);}
if($override===null){$override=lh_course16_override($lesson,$position);}
if($override===null){$override=lh_lesson_override($course,$lesson,$position);}
if($override===null){$override=lh_course4_override($lesson);}
$content=$override!==null?$override:(string)($lesson['content']??'');
$lh_page_title=(string)$lesson['title'];$lh_description='Learn '.(string)$lesson['title'].' in SmartToolz Learning Hub.';include __DIR__.'/includes/header.php';include __DIR__.'/includes/navbar.php';
?><link rel="stylesheet" href="/learning-hub/assets/css/lesson.css"><div class="lh-lesson-shell"><aside class="lh-lesson-course-nav"><div class="lh-course-nav-head"><span class="lh-course-nav-icon">📚</span><div><small>COURSE</small><strong><?=lh_h($course['title'])?></strong></div></div><div class="lh-course-progress"><div><span>Progress</span><strong><?=count($lessons)?round(($position/count($lessons))*100):0?>%</strong></div><div class="progress"><div class="progress-bar" style="width:<?=count($lessons)?round(($position/count($lessons))*100):0?>%"></div></div></div><nav><?php $n=0;foreach($lessons as $row):$n++;?><a class="<?=((int)$row['id']===(int)$lesson['id'])?'active':''?>" href="/learning-hub/lesson.php?slug=<?=rawurlencode((string)$row['slug'])?>"><span><?=number_format($n)?></span><em><?=lh_h($row['title'])?></em></a><?php endforeach;?></nav></aside><main class="lh-lesson-page"><div class="lh-lesson-top"><a href="/learning-hub/course.php?slug=<?=rawurlencode((string)$course['slug'])?>">← <?=lh_h($course['title'])?></a><span>Lesson <?=number_format($position)?> of <?=number_format(count($lessons))?></span></div><div class="progress lh-progress"><div class="progress-bar" style="width:<?=count($lessons)?round(($position/count($lessons))*100):0?>%"></div></div><article class="lh-lesson-card"><div class="lh-lesson-kicker"><span><?=lh_h($course['category_name']??'Learning Hub')?></span><span>•</span><span>Lesson <?=number_format($position)?></span></div><h1><?=lh_h($lesson['title'])?></h1><p class="lh-lesson-intro">Learn the concept, follow the practical steps, and apply what you learn through a simple activity.</p><div class="lh-prose"><?=$content?:'<div class="lh-callout">Detailed lesson content is being prepared.</div>'?></div></div></article><div class="lh-lesson-nav-bottom"><a class="btn btn-outline-primary <?=empty($previous)?'disabled':''?>" href="<?=empty($previous)?'#':'/learning-hub/lesson.php?slug='.rawurlencode((string)$previous['slug'])?>">← Previous lesson</a><a class="btn btn-primary <?=empty($next)?'disabled':''?>" href="<?=empty($next)?'#':'/learning-hub/lesson.php?slug='.rawurlencode((string)$next['slug'])?>"><?=empty($next)?'Course complete':'Next lesson →'?></a></div><div class="lh-ad-slot my-4">Advertisement</div></main></div><?php include __DIR__.'/includes/footer.php';?><script src="/learning-hub/assets/js/app.js"></script>
