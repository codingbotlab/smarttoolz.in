<?php
declare(strict_types=1);
require_once __DIR__.'/../includes/bootstrap.php';
$admin=lh_require_admin();$result=null;$error=null;
function lh_cols(PDO $db,string $table):array{$q=$db->prepare("SELECT column_name,is_nullable,column_default FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name=? ORDER BY ordinal_position");$q->execute([$table]);$r=[];foreach($q->fetchAll(PDO::FETCH_ASSOC) as $x)$r[$x['column_name']]=$x;return $r;}
if($_SERVER['REQUEST_METHOD']==='POST'){
 try{
  $cc=lh_cols($db,'learning_chapters');$lc=lh_cols($db,'learning_lessons');
  if(!$cc)throw new RuntimeException('learning_chapters table not found.');
  if(!isset($cc['id']))throw new RuntimeException('learning_chapters.id not found.');
  if(!isset($cc['course_id']))throw new RuntimeException('learning_chapters.course_id not found.');
  if(!isset($lc['chapter_id']))throw new RuntimeException('learning_lessons.chapter_id not found.');
  $courses=$db->query('SELECT id,title FROM learning_courses WHERE enabled=1 ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
  $findCh=$db->prepare('SELECT id FROM learning_chapters WHERE course_id=? ORDER BY id LIMIT 1');
  $findLs=$db->prepare('SELECT id FROM learning_lessons WHERE chapter_id=? ORDER BY sort_order,id');
  $insChCols=['course_id'];$insChVals=['?'];$chParams=[];
  if(isset($cc['title'])){$insChCols[]='title';$insChVals[]='?';}
  elseif(isset($cc['name'])){$insChCols[]='name';$insChVals[]='?';}
  if(isset($cc['description'])){$insChCols[]='description';$insChVals[]='?';}
  if(isset($cc['sort_order'])){$insChCols[]='sort_order';$insChVals[]='1';}
  if(isset($cc['enabled'])){$insChCols[]='enabled';$insChVals[]='1';}
  $stats=['courses'=>count($courses),'chapters'=>0,'lessons'=>0];
  foreach($courses as $course){
   $findCh->execute([(int)$course['id']]);$chapterId=(int)$findCh->fetchColumn();
   if(!$chapterId){$params=[(int)$course['id']];if(isset($cc['title'])||isset($cc['name']))$params[]='Course Lessons';if(isset($cc['description']))$params[]='Structured lessons for '.$course['title'].'.';$db->prepare('INSERT INTO learning_chapters('.implode(',',$insChCols).') VALUES('.implode(',',$insChVals).')')->execute($params);$chapterId=(int)$db->lastInsertId();$stats['chapters']++;}
   for($i=1;$i<=50;$i++){
    $q=$db->prepare('SELECT id FROM learning_lessons WHERE chapter_id=? AND sort_order=? LIMIT 1');$q->execute([$chapterId,$i]);if($q->fetchColumn())continue;
    $title='Lesson '.$i.' — '.$course['title'];$slug='course-'.$course['id'].'-lesson-'.$i;$content='<article class="lh-prose"><h2>'.htmlspecialchars($title,ENT_QUOTES,'UTF-8').'</h2><p>This original SmartToolz lesson explains an important concept through a practical, beginner-friendly learning path.</p><h3>Learning objective</h3><p>Understand the concept, follow the workflow and apply it to a small task.</p><h3>Step by step</h3><ol><li>Review the key idea.</li><li>Follow the example.</li><li>Try it yourself.</li><li>Test and review the result.</li></ol><h3>Practice</h3><p>Create a small exercise based on this topic and explain the result in your own words.</p><h3>Common mistakes</h3><p>Avoid skipping prerequisites, copying without understanding, and ignoring errors.</p><h3>Quick check</h3><p>Can you explain the main idea and name one practical use?</p></article>';
    $db->prepare('INSERT INTO learning_lessons(chapter_id,title,slug,content,sort_order,enabled) VALUES(?,?,?,?,?,1)')->execute([$chapterId,$title,$slug,$content,$i]);$stats['lessons']++;
   }
  }
  $result=$stats;
 }catch(Throwable $e){$error=$e->getMessage();}
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Repair Curriculum | SmartToolz</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="/learning-hub/assets/css/app.css" rel="stylesheet"></head><body><main class="container py-5"><div class="mx-auto" style="max-width:820px"><div class="lh-card p-4 p-md-5"><h1 class="h3 fw-bold">🔧 Repair Learning Curriculum</h1><p class="text-secondary">Signed in as <?=lh_h($admin['email']??'admin')?>.</p><?php if($result):?><div class="alert alert-success"><strong>Done.</strong> <?=number_format($result['courses'])?> courses checked · <?=number_format($result['chapters'])?> chapters created · <?=number_format($result['lessons'])?> lessons created.</div><?php endif;if($error):?><div class="alert alert-danger"><strong>Error:</strong><pre class="mb-0 mt-2 text-wrap"><?=lh_h($error)?></pre></div><?php endif;?><form method="post"><button class="btn btn-primary btn-lg">Repair & Build 50 Lessons Per Course</button></form><a class="btn btn-link px-0 mt-3" href="/learning-hub/courses.php">Open Courses →</a></div></div></main></body></html>