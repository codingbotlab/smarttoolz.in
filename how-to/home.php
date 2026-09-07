<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/bootstrap.php';
require_once dirname(__DIR__) . '/lib/tools.php';
require_once dirname(__DIR__) . '/header.php';
$tools = smarttoolz_tools();
?>
<main class="howto-index container py-5">
  <section class="text-center mb-5">
    <span class="eyebrow">SMARTTOOLZ GUIDES</span>
    <h1 class="display-5 fw-bold mt-2">How to Use Our Tools</h1>
    <p class="lead text-muted mx-auto" style="max-width:760px">Practical, tool-specific guides that explain what each tool does, when to use it, and how to get a reliable result.</p>
  </section>
  <div class="row g-4">
    <?php foreach ($tools as $tool):
      $name = (string)$tool['name'];
      $slug = smarttoolz_slug($name);
      $category = (string)($tool['category'] ?? 'Tools');
      $description = (string)($tool['description'] ?? '');
    ?>
    <div class="col-12 col-md-6 col-xl-4">
      <article class="card h-100 border-0 shadow-sm howto-card">
        <div class="card-body p-4">
          <span class="badge text-bg-light mb-3"><?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?></span>
          <h2 class="h5"><a class="text-decoration-none" href="/how-to/<?= htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') ?>/">How to Use <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></a></h2>
          <p class="text-muted small"><?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></p>
          <a class="btn btn-outline-primary btn-sm" href="/how-to/<?= htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') ?>/">Read Guide →</a>
        </div>
      </article>
    </div>
    <?php endforeach; ?>
  </div>
</main>
<?php require_once dirname(__DIR__) . '/footer.php'; ?>
<style>
.howto-index .eyebrow{font-size:.72rem;font-weight:800;letter-spacing:.12em;color:#5b4bdb}.howto-card{border:1px solid #e8eaf2!important;transition:transform .18s ease,box-shadow .18s ease}.howto-card:hover{transform:translateY(-3px);box-shadow:0 12px 28px rgba(20,25,50,.08)!important}.howto-card h2 a{color:inherit}.howto-card .btn{margin-top:.35rem}
</style>
