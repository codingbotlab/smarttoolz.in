<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/bootstrap.php';
require_once dirname(__DIR__) . '/lib/tools.php';

$pageTitle = 'How to Use SmartToolz Tools — Step-by-Step Guides';
$pageDescription = 'Learn how to use SmartToolz online tools with practical, tool-specific step-by-step guides, tips and answers to common questions.';
require_once dirname(__DIR__) . '/head.php';
?>
<title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
<meta name="description" content="<?= htmlspecialchars($pageDescription, ENT_QUOTES, 'UTF-8') ?>">
<link rel="canonical" href="https://smarttoolz.in/how-to/">
<link rel="stylesheet" href="/assets/css/how-to.css?v=2026090701">
</head>
<body>
<?php require_once dirname(__DIR__) . '/header.php'; ?>
<main class="howto-index page">
  <section class="howto-hero">
    <span class="eyebrow">SMARTTOOLZ GUIDES</span>
    <h1>How to Use Our Tools</h1>
    <p>Practical, tool-specific guides that explain what each tool does, when to use it, and how to get a reliable result.</p>
  </section>

  <?php
  $tools = smarttoolz_tools();
  $grouped = [];
  foreach ($tools as $tool) {
      $category = (string)($tool['category'] ?? 'Other Tools');
      $grouped[$category][] = $tool;
  }
  ksort($grouped, SORT_NATURAL | SORT_FLAG_CASE);
  ?>

  <div class="howto-groups">
    <?php foreach ($grouped as $category => $categoryTools): ?>
      <section class="howto-category" aria-labelledby="howto-<?= htmlspecialchars(smarttoolz_slug($category), ENT_QUOTES, 'UTF-8') ?>">
        <div class="howto-category-head">
          <h2 id="howto-<?= htmlspecialchars(smarttoolz_slug($category), ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?></h2>
          <span><?= count($categoryTools) ?> <?= count($categoryTools) === 1 ? 'guide' : 'guides' ?></span>
        </div>
        <div class="howto-grid">
          <?php foreach ($categoryTools as $tool):
            $name = (string)$tool['name'];
            $slug = smarttoolz_slug($name);
            $description = trim((string)($tool['description'] ?? ''));
            if ($description === '') $description = 'A practical guide for using this SmartToolz utility.';
          ?>
            <article class="howto-card">
              <div class="howto-card-icon" aria-hidden="true">HOW</div>
              <h3><a href="/how-to/<?= htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') ?>/">How to Use <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?></a></h3>
              <p><?= htmlspecialchars($description, ENT_QUOTES, 'UTF-8') ?></p>
              <a class="howto-read" href="/how-to/<?= htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') ?>/">Read Guide <span aria-hidden="true">→</span></a>
            </article>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endforeach; ?>
  </div>
</main>
<?php require_once dirname(__DIR__) . '/footer.php'; ?>
</body>
