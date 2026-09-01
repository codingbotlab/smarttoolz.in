<?php
declare(strict_types=1);
require_once __DIR__.'/includes/bootstrap.php';
require_once __DIR__.'/includes/media.php';

$slug=trim((string)($_GET['slug']??''));
$course=null;$lessons=[];$error=false;
try {
    if($slug==='') throw new RuntimeException('missing');
    $q=$db->prepare("SELECT c.*,lc.name category_name,lc.icon category_icon,lc.slug category_slug FROM learning_courses c LEFT JOIN learning_categories lc ON lc.id=c.category_id WHERE c.slug=? AND c.enabled=1 LIMIT 1");
    $q->execute([$slug]); $course=$q->fetch(PDO::FETCH_ASSOC)?:null;
    if(!$course) throw new RuntimeException('not-found');
    $hasChapter=false;
    $q=$db->query("SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='learning_lessons' AND column_name='chapter_id'");
    $hasChapter=(int)$q->fetchColumn()>0;
    if($hasChapter){
        $hasChapters=(int)$db->query("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name='learning_chapters'")->fetchColumn()>0;
        if($hasChapters){
            $q=$db->prepare("SELECT l.id,l.title,l.slug,l.sort_order,ch.title chapter_title,ch.sort_order chapter_sort FROM learning_lessons l JOIN learning_chapters ch ON ch.id=l.chapter_id WHERE ch.course_id=? AND l.enabled=1 ORDER BY ch.sort_order,ch.id,l.sort_order,l.id");
            try{$q->execute([(int)$course['id']]);$lessons=$q->fetchAll(PDO::FETCH_ASSOC);}catch(Throwable){$lessons=[];}
        }
    }
    if(!$lessons && (int)$db->query("SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name='learning_lessons' AND column_name='course_id'")->fetchColumn()>0){
        $q=$db->prepare("SELECT l.id,l.title,l.slug,l.sort_order,NULL chapter_title FROM learning_lessons l WHERE l.course_id=? AND l.enabled=1 ORDER BY l.sort_order,l.id");
        $q->execute([(int)$course['id']]); $lessons=$q->fetchAll(PDO::FETCH_ASSOC);
    }
} catch(Throwable){$error=true;}
if(!$course||$error){http_response_code($course?500:404);$lh_page_title=$course?(string)$course['title']:'Course not found';$lh_description='SmartToolz Learning Hub course';include __DIR__.'/includes/header.php';include __DIR__.'/includes/navbar.php';?><main class="container py-5"><div class="lh-empty p-5 text-center"><div class="fs-1 mb-2">📚</div><h1 class="h4"><?= $course?'Course temporarily unavailable':'Course not found' ?></h1><p class="text-secondary mb-3"><?= $course?'Please try again in a moment.':'The requested course could not be found.' ?></p><a class="btn btn-primary" href="/learning-hub/courses.php">Browse Courses</a></div></main><?php include __DIR__.'/includes/footer.php';exit;}
$lh_page_title=(string)$course['title'];$lh_description=(string)($course['description']??'SmartToolz practical course.');
include __DIR__.'/includes/header.php';include __DIR__.'/includes/navbar.php';
?><div class="container-fluid"><div class="row"><div class="col-lg-3 col-xl-2 p-0"><?php include __DIR__.'/includes/sidebar.php';?></div><main class="col-lg-9 col-xl-10 py-4 py-lg-5"><div class="container-fluid"><a class="small text-primary text-decoration-none" href="/learning-hub/courses.php">← All courses</a><section class="lh-hero overflow-hidden mt-3 mb-4"><div class="lh-course-image-wrap"><img src="<?=lh_h(lh_course_image((string)($course['category_slug']??'')))?>" class="w-100 lh-course-img" alt="<?=lh_h($course['title'])?> course artwork" loading="eager"></div><div class="p-4 p-md-5"><span class="badge text-bg-light border"><?=lh_h($course['category_name']??'Course')?> · <?=lh_h($course['level']??'Beginner')?></span><h1 class="display-6 fw-bold mt-3"><?=lh_h($course['title'])?></h1><p class="lead text-secondary mb-0"><?=lh_h($course['description']??'Follow the lessons and practice as you learn.')?></p></div></section><div class="row g-4"><div class="col-xl-8"><div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h4 fw-bold mb-0">Course lessons</h2><span class="small text-secondary"><?=number_format(count($lessons))?> lessons</span></div><div class="vstack gap-2"><?php $chapter='';foreach($lessons as $i=>$lesson){$ct=(string)($lesson['chapter_title']??'');if($ct!==''&&$ct!==$chapter){$chapter=$ct;?><h3 class="h6 text-uppercase text-secondary mt-3 mb-0"><?=lh_h($chapter)?></h3><?php }?><a class="lh-card p-3 p-md-4 d-flex align-items-center gap-3 text-decoration-none" href="/learning-hub/lesson.php?slug=<?=rawurlencode((string)$lesson['slug'])?>"><span class="badge rounded-pill text-bg-primary"><?=number_format($i+1)?></span><span class="flex-grow-1"><strong><?=lh_h($lesson['title'])?></strong><small class="d-block text-secondary mt-1">Open lesson →</small></span><i class="bi bi-chevron-right text-secondary"></i></a><?php } if(!$lessons){?><div class="lh-empty p-4 text-center"><h3 class="h6">Lessons are being prepared</h3><p class="small text-secondary mb-0">Please check back soon.</p></div><?php }?></div></div><div class="col-xl-4"><div class="lh-ad-slot mb-3">Advertisement</div><div class="lh-card p-4"><h3 class="h6 fw-bold">Learn by doing</h3><p class="small text-secondary">Use SmartToolz utilities alongside this course when a related tool is available.</p><a class="btn btn-outline-primary btn-sm" href="/smart-toolz/">Explore Tools</a></div></div></div></div></main></div></div><?php include __DIR__.'/includes/footer.php';?><script src="/learning-hub/assets/js/app.js"></script><script src="/learning-hub/assets/js/router.js"></script>