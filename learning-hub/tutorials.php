<?php
declare(strict_types=1);
require_once __DIR__.'/includes/bootstrap.php';
require_once __DIR__.'/includes/catalog.php';
require_once __DIR__.'/includes/media.php';

$lh_page_title='Tutorials';
$lh_description='Browse practical SmartToolz tutorials, lessons and step-by-step guides for real digital skills.';

$rows=[];
$categories=[];
$category=trim((string)($_GET['category']??''));
$q=trim((string)($_GET['q']??''));

try {
    $categories=$db->query("SELECT slug,name,icon FROM learning_categories WHERE enabled=1 ORDER BY sort_order,name")->fetchAll(PDO::FETCH_ASSOC);
} catch(Throwable) {}

try {
    $sql="SELECT l.id,l.title,l.slug,l.sort_order,l.chapter_id,l.course_id,
                  c.title course_title,c.slug course_slug,
                  lc.name category_name,lc.slug category_slug,lc.icon category_icon,
                  ch.title chapter_title
           FROM learning_lessons l
           LEFT JOIN learning_chapters ch ON ch.id=l.chapter_id
           LEFT JOIN learning_courses c ON c.id=COALESCE(l.course_id,ch.course_id)
           LEFT JOIN learning_categories lc ON lc.id=c.category_id
           WHERE l.enabled=1 AND (c.enabled=1 OR c.id IS NULL)";
    $params=[];
    if($category!==''){$sql.=" AND lc.slug=?";$params[]=$category;}
    if($q!==''){$sql.=" AND (l.title LIKE ? OR c.title LIKE ? OR ch.title LIKE ?)";$like='%'.$q.'%';array_push($params,$like,$like,$like);}
    $sql.=" ORDER BY l.id DESC LIMIT 60";
    $stmt=$db->prepare($sql);$stmt->execute($params);$rows=$stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(Throwable $e) {
    error_log('Learning Hub tutorials: '.$e->getMessage());
}

include __DIR__.'/includes/header.php';
include __DIR__.'/includes/navbar.php';
?>
<style>
.lh-tutorials-wrap{max-width:1400px}
.lh-tutorial-hero{position:relative;overflow:hidden;border:1px solid rgba(37,99,235,.12);border-radius:24px;background:linear-gradient(135deg,#eff6ff 0%,#fff 55%,#f8fafc 100%);box-shadow:0 12px 35px rgba(15,23,42,.06)}
.lh-tutorial-hero:after{content:"";position:absolute;width:280px;height:280px;border-radius:50%;background:rgba(37,99,235,.08);right:-90px;top:-110px}
.lh-tutorial-hero .hero-content{position:relative;z-index:1}
.lh-tutorial-icon{width:58px;height:58px;border-radius:16px;display:grid;place-items:center;background:#fff;color:#2563eb;font-size:25px;box-shadow:0 8px 24px rgba(37,99,235,.12);border:1px solid rgba(37,99,235,.1)}
.lh-tutorial-search{background:#fff;border:1px solid #dbe3ef;border-radius:14px;padding:7px;box-shadow:0 6px 20px rgba(15,23,42,.06)}
.lh-tutorial-search .form-control{border:0;box-shadow:none;padding-left:12px}
.lh-tutorial-card{border:1px solid #e5e7eb;border-radius:18px;background:#fff;transition:transform .2s ease,box-shadow .2s ease,border-color .2s ease;height:100%;overflow:hidden}
.lh-tutorial-card:hover{transform:translateY(-4px);box-shadow:0 14px 32px rgba(15,23,42,.10);border-color:rgba(37,99,235,.28)}
.lh-tutorial-number{width:42px;height:42px;border-radius:12px;display:grid;place-items:center;background:#eff6ff;color:#2563eb;font-weight:800;flex:0 0 auto}
.lh-tutorial-title{line-height:1.35;color:#111827}
.lh-tutorial-meta{font-size:.82rem;color:#64748b}
.lh-filter-pill{border-radius:999px!important;padding:.5rem .8rem!important;background:#fff!important;border:1px solid #e2e8f0!important;color:#475569!important}
.lh-filter-pill:hover,.lh-filter-pill.active{background:#2563eb!important;border-color:#2563eb!important;color:#fff!important}
.lh-tutorial-empty{border:1px dashed #cbd5e1;border-radius:18px;background:#f8fafc}
@media(max-width:575.98px){.lh-tutorial-hero{border-radius:18px}.lh-tutorial-hero h1{font-size:2rem}.lh-tutorial-search .btn{width:100%}}
</style>

<div class="container-fluid">
  <div class="row">
    <div class="col-lg-3 col-xl-2 p-0"><?php include __DIR__.'/includes/sidebar.php'; ?></div>
    <main class="col-lg-9 col-xl-10 py-4 py-lg-5">
      <div class="container-fluid lh-tutorials-wrap">
        <section class="lh-tutorial-hero p-4 p-md-5 mb-4">
          <div class="hero-content">
            <div class="d-flex align-items-center gap-3 mb-3">
              <div class="lh-tutorial-icon"><i class="bi bi-play-btn-fill"></i></div>
              <div><div class="small fw-bold text-primary text-uppercase">SmartToolz Learning Hub</div><div class="small text-secondary">Practical learning, one tutorial at a time</div></div>
            </div>
            <div class="row align-items-end g-4">
              <div class="col-xl-7">
                <h1 class="display-5 fw-bold mb-2">Tutorials that help you actually build.</h1>
                <p class="lead text-secondary mb-0">Step-by-step lessons you can follow, practice and continue into a complete course.</p>
              </div>
              <div class="col-xl-5">
                <form class="lh-tutorial-search d-flex flex-column flex-sm-row gap-2" method="get" action="/learning-hub/tutorials.php">
                  <?php if($category!==''): ?><input type="hidden" name="category" value="<?=lh_h($category)?>"><?php endif; ?>
                  <input class="form-control" type="search" name="q" value="<?=lh_h($q)?>" placeholder="Search tutorials or lessons…" aria-label="Search tutorials">
                  <button class="btn btn-primary px-4" type="submit"><i class="bi bi-search me-1"></i>Search</button>
                </form>
              </div>
            </div>
          </div>
        </section>

        <div class="d-flex flex-wrap gap-2 mb-4">
          <a class="btn btn-sm lh-filter-pill <?=$category===''?'active':''?>" href="/learning-hub/tutorials.php">All tutorials</a>
          <?php foreach($categories as $cat): ?>
            <a class="btn btn-sm lh-filter-pill <?=$category===$cat['slug']?'active':''?>" href="/learning-hub/tutorials.php?category=<?=rawurlencode((string)$cat['slug'])?>"><?=lh_h($cat['icon']??'📚')?> <?=lh_h($cat['name'])?></a>
          <?php endforeach; ?>
        </div>

        <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
          <div>
            <h2 class="h3 fw-bold mb-1"><?= $q!=='' ? 'Search results' : ($category!=='' ? 'Tutorials in '.lh_h($rows[0]['category_name']??ucwords(str_replace('-',' ',$category))) : 'Latest tutorials') ?></h2>
            <p class="text-secondary mb-0"><?=number_format(count($rows))?> practical lessons ready to explore.</p>
          </div>
          <?php if($q!=='' || $category!==''): ?><a class="small fw-semibold text-decoration-none" href="/learning-hub/tutorials.php">Clear filters <i class="bi bi-x-circle"></i></a><?php endif; ?>
        </div>

        <?php if($rows): ?>
          <div class="row g-3 g-xl-4">
            <?php foreach($rows as $i=>$t): ?>
              <div class="col-md-6 col-xl-4">
                <a class="lh-tutorial-card d-flex flex-column p-4 text-decoration-none" href="/learning-hub/lesson.php?slug=<?=rawurlencode((string)$t['slug'])?>">
                  <div class="d-flex align-items-start gap-3 mb-3">
                    <div class="lh-tutorial-number"><?=number_format($i+1)?></div>
                    <div class="min-w-0">
                      <div class="lh-tutorial-meta fw-semibold mb-1"><?=lh_h($t['category_name']??'Learning Hub')?></div>
                      <h3 class="h5 fw-bold lh-tutorial-title mb-0"><?=lh_h($t['title'])?></h3>
                    </div>
                  </div>
                  <div class="mt-auto pt-3 border-top d-flex justify-content-between align-items-center gap-2">
                    <span class="lh-tutorial-meta text-truncate"><i class="bi bi-book me-1"></i><?=lh_h($t['course_title']??'Tutorial')?></span>
                    <span class="small fw-bold text-primary text-nowrap">Open <i class="bi bi-arrow-right"></i></span>
                  </div>
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="lh-tutorial-empty p-5 text-center">
            <div class="fs-1 mb-2">🔎</div>
            <h3 class="h5 fw-bold">No tutorials found</h3>
            <p class="text-secondary mb-3">Try a different search or browse all tutorials.</p>
            <a class="btn btn-primary" href="/learning-hub/tutorials.php">View all tutorials</a>
          </div>
        <?php endif; ?>

        <div class="lh-ad-slot my-5">Advertisement</div>
      </div>
    </main>
  </div>
</div>
<?php include __DIR__.'/includes/footer.php'; ?>
<script src="/learning-hub/assets/js/app.js"></script>
<script src="/learning-hub/assets/js/router.js"></script>