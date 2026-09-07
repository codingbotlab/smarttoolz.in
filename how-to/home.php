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
<style>
.howto-index{padding:48px 0 72px}.howto-hero{text-align:center;padding:18px 0 44px}.howto-hero h1{margin:16px 0 10px;font-size:clamp(40px,5vw,62px);line-height:1.02;letter-spacing:-3px;font-weight:900}.howto-hero p{max-width:780px;margin:0 auto;color:#667085;font-size:15px;line-height:1.7}.howto-groups{display:grid;gap:46px}.howto-category-head{display:flex;align-items:end;justify-content:space-between;gap:16px;margin-bottom:17px}.howto-category-head h2{margin:0;font-size:25px;letter-spacing:-1px}.howto-category-head span{color:#667085;font-size:11px;font-weight:700}.howto-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}.howto-card{display:flex;flex-direction:column;min-height:220px;padding:24px;border:1px solid #e7eaf0;border-radius:19px;background:#fff;box-shadow:0 10px 30px rgba(16,24,40,.045);transition:transform .18s ease,box-shadow .18s ease,border-color .18s ease}.howto-card:hover{transform:translateY(-3px);border-color:#d7d0ff;box-shadow:0 17px 38px rgba(54,63,135,.09)}.howto-card-icon{width:38px;height:38px;display:grid;place-items:center;margin-bottom:17px;border-radius:11px;background:#f0efff;color:#5f49ff;font-size:9px;font-weight:900;letter-spacing:.4px}.howto-card h3{margin:0 0 8px;font-size:17px;line-height:1.35;letter-spacing:-.25px}.howto-card h3 a{color:#101638}.howto-card p{margin:0;color:#667085;font-size:12px;line-height:1.7}.howto-read{margin-top:auto;padding-top:18px;color:#5b45ff;font-size:11px;font-weight:850}.howto-read span{margin-left:3px}@media(max-width:900px){.howto-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:600px){.howto-index{padding-top:30px}.howto-hero{padding-bottom:32px}.howto-hero h1{letter-spacing:-2px}.howto-grid{grid-template-columns:1fr}.howto-card{min-height:195px}}
</style>
</body>
