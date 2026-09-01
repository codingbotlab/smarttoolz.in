<?php
declare(strict_types=1);
require_once __DIR__.'/../includes/bootstrap.php';
$user=lh_user();
if(!$user){header('Location:/creator-ai/auth/google-login.php?return='.rawurlencode('/learning-hub/dashboard/'));exit;}
$rows=[];
try{
    $q=$db->prepare("SELECT e.id,e.course_id,e.status,e.progress_percent,e.enrolled_at,e.last_accessed_at,e.completed_at,c.title course_title,c.slug course_slug,c.level,c.thumbnail,COALESCE((SELECT l.title FROM learning_lessons l WHERE l.course_id=c.id AND l.enabled=1 ORDER BY l.sort_order,l.id LIMIT 1),'') first_lesson_title,COALESCE((SELECT l.slug FROM learning_lessons l WHERE l.course_id=c.id AND l.enabled=1 ORDER BY l.sort_order,l.id LIMIT 1),'') first_lesson_slug FROM learning_enrollments e JOIN learning_courses c ON c.id=e.course_id WHERE e.user_id=? ORDER BY e.last_accessed_at DESC,e.enrolled_at DESC");
    $q->execute([(int)$user['id']]);
    $rows=$q->fetchAll(PDO::FETCH_ASSOC);
}catch(Throwable $e){error_log('My Learning: '.$e->getMessage());}
$lh_page_title='My Learning';
include __DIR__.'/../includes/header.php';
include __DIR__.'/../includes/navbar.php';
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-3 col-xl-2 p-0"><?php include __DIR__.'/../includes/sidebar.php';?></div>
    <main class="col-lg-9 col-xl-10 py-4 py-lg-5">
      <div class="container-fluid">
        <div class="lh-hero p-4 p-md-5 mb-4">
          <div class="small text-primary fw-bold">My Learning</div>
          <h1 class="h2 fw-bold mt-2">Welcome, <?=lh_h($user['name']??'Learner')?>.</h1>
          <p class="text-secondary mb-0">Your enrolled courses, progress and recently started learning paths.</p>
        </div>
        <?php if($rows): ?>
        <div class="row g-4">
          <?php foreach($rows as $r): $pct=max(0,min(100,(int)$r['progress_percent'])); ?>
          <div class="col-md-6 col-xl-4">
            <article class="lh-card h-100 overflow-hidden">
              <?php if(!empty($r['thumbnail'])): ?><img src="<?=lh_h($r['thumbnail'])?>" alt="" class="w-100" style="height:150px;object-fit:cover"><?php endif; ?>
              <div class="p-4">
                <span class="badge bg-primary-subtle text-primary mb-2"><?=lh_h($r['level']??'Beginner')?></span>
                <h2 class="h5 fw-bold mb-1"><?=lh_h($r['course_title'])?></h2>
                <div class="small text-secondary mb-3">Enrolled <?=lh_h(date('d M Y',strtotime((string)$r['enrolled_at'])))?></div>
                <div class="d-flex justify-content-between small fw-semibold mb-1"><span>Progress</span><span><?=$pct?>%</span></div>
                <div class="progress" style="height:8px"><div class="progress-bar" style="width:<?=$pct?>%"></div></div>
                <div class="mt-3">
                  <?php if($r['first_lesson_slug']!==''): ?>
                    <a class="btn btn-primary w-100" href="/learning-hub/lesson.php?slug=<?=rawurlencode($r['first_lesson_slug'])?>">Continue learning →</a>
                  <?php else: ?>
                    <a class="btn btn-outline-primary w-100" href="/learning-hub/course.php?slug=<?=rawurlencode($r['course_slug'])?>">Open course →</a>
                  <?php endif; ?>
                </div>
              </div>
            </article>
          </div>
          <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="lh-card p-5 text-center">
          <div class="fs-1">📚</div>
          <h2 class="h4 fw-bold mt-3">No courses enrolled yet</h2>
          <p class="text-secondary">Choose a course and start learning. It will appear here automatically.</p>
          <a class="btn btn-primary" href="/learning-hub/courses.php">Browse Courses →</a>
        </div>
        <?php endif; ?>
      </div>
    </main>
  </div>
</div>
<?php include __DIR__.'/../includes/footer.php'; ?>
<script src="/learning-hub/assets/js/app.js"></script>
<script src="/learning-hub/assets/js/router.js"></script>
