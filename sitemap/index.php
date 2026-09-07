<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/bootstrap.php';
require_once dirname(__DIR__) . '/lib/tools.php';

$tools = smarttoolz_tools();
$grouped = [];
foreach ($tools as $tool) {
    $grouped[$tool['category']][] = $tool;
}
ksort($grouped, SORT_NATURAL | SORT_FLAG_CASE);

require_once dirname(__DIR__) . '/head.php';
?>
<main class="container py-5">
  <div class="text-center mb-5">
    <p class="text-uppercase small fw-semibold mb-2">Explore SmartToolz</p>
    <h1 class="display-5 fw-bold">Sitemap</h1>
    <p class="lead text-muted mx-auto" style="max-width:760px">Browse all SmartToolz tools by category. Every tool below is a real, working tool on the site.</p>
  </div>
  <div class="row g-4">
    <?php foreach ($grouped as $category => $items): ?>
      <section class="col-12 col-md-6 col-xl-4">
        <div class="card h-100 border-0 shadow-sm">
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h2 class="h5 mb-0"><?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?></h2>
              <span class="badge text-bg-light"><?= count($items) ?></span>
            </div>
            <ul class="list-unstyled mb-0">
              <?php foreach ($items as $tool): ?>
                <li class="mb-2"><a href="<?= htmlspecialchars($tool['url'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($tool['name'], ENT_QUOTES, 'UTF-8') ?></a></li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>
      </section>
    <?php endforeach; ?>
  </div>
</main>
<?php require_once dirname(__DIR__) . '/footer.php'; ?>
