<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/lib/tools.php';

$tools = smarttoolz_tools();
$categories = smarttoolz_categories();
$tags = smarttoolz_tag_list();
$query = trim((string)($_GET['q'] ?? ''));
$category = trim((string)($_GET['category'] ?? ''));
$tag = trim((string)($_GET['tag'] ?? ''));
$filtered = array_values(array_filter($tools, static function (array $tool) use ($query, $category, $tag): bool {
    $haystack = $tool['name'].' '.$tool['description'].' '.$tool['category'].' '.implode(' ', $tool['tags'] ?? []);
    return ($query === '' || stripos($haystack, $query) !== false)
        && ($category === '' || smarttoolz_slug($tool['category']) === $category)
        && ($tag === '' || in_array($tag, $tool['tags'] ?? [], true));
}));
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>All Online Tools — Free Tools for Images, PDF, Text & More | SmartToolz</title>
<meta name="description" content="Browse SmartToolz free online tools for images, PDF, text, developer tasks, calculators, generators, security and everyday work. Search or filter by category.">
<meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
<link rel="canonical" href="https://smarttoolz.in/tools/">
<meta property="og:type" content="website">
<meta property="og:site_name" content="SmartToolz">
<meta property="og:title" content="All Online Tools — SmartToolz">
<meta property="og:description" content="Find free browser-based tools for common image, PDF, text, developer and everyday tasks.">
<meta property="og:url" content="https://smarttoolz.in/tools/">
<?php require __DIR__ . '/head.php'; ?>
<style>
.tools-library-grid{display:grid!important;grid-template-columns:repeat(4,minmax(0,1fr));gap:18px;width:100%;margin:0;padding:0 0 10px}
.tools-library-card{display:flex!important;flex-direction:column!important;min-height:235px;padding:22px!important;background:#fff!important;border:1px solid #e7eaf0!important;border-radius:20px!important;box-shadow:0 12px 34px rgba(16,24,40,.06)!important;visibility:visible!important;opacity:1!important;color:#101828!important}
.tools-library-card h2{margin:14px 0 8px!important;font-size:18px!important;line-height:1.25!important;color:#101828!important}
.tools-library-card p{margin:0!important;color:#667085!important;font-size:13px!important;line-height:1.7!important}
.tools-library-card .tool-card-top{display:flex!important;align-items:center!important;justify-content:space-between!important;gap:10px!important;margin:0!important}
.tools-library-card .tool-card-top>span{font-size:10px!important;font-weight:800!important;color:#7a85a1!important}
.tools-library-card .tool-icon{display:grid!important;place-items:center!important;width:52px!important;height:52px!important;margin:0!important;border-radius:15px!important;background:#f0efff!important;color:#4d3cff!important;font-size:13px!important;font-weight:900!important}
.tool-tags{display:flex!important;flex-wrap:wrap!important;gap:6px!important;margin-top:12px!important}
.tool-tag{display:inline-flex!important;align-items:center!important;padding:5px 9px!important;border-radius:999px!important;background:#f7f7fb!important;border:1px solid #e7eaf0!important;color:#667085!important;font-size:10px!important;font-weight:700!important;line-height:1.2!important;text-decoration:none!important}
.tool-tag:hover,.tool-tag.active{background:#eeecff!important;color:#4b3cff!important;border-color:#d9d4ff!important}
.tools-library-card .tool-link{display:block!important;margin-top:auto!important;padding-top:18px!important;color:#4b3cff!important;font-size:12px!important;font-weight:850!important}
.tags-panel{display:flex!important;flex-wrap:wrap!important;gap:8px!important;margin-top:14px!important}
.tags-panel .category-pill{font-size:11px!important}
@media(max-width:1000px){.tools-library-grid{grid-template-columns:repeat(3,minmax(0,1fr))}}
@media(max-width:760px){.tools-library-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(max-width:520px){.tools-library-grid{grid-template-columns:1fr}}
</style>
<script type="application/ld+json">
<?= json_encode(['@context'=>'https://schema.org','@type'=>'CollectionPage','name'=>'SmartToolz All Tools','url'=>'https://smarttoolz.in/tools/','description'=>'Free browser-based tools for common digital tasks.','isPartOf'=>['@type'=>'WebSite','name'=>'SmartToolz','url'=>'https://smarttoolz.in/']], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) ?>
</script>
</head>
<body>
<?php require __DIR__ . '/header.php'; ?>
<main>
<section class="tools-hero">
  <div class="page">
    <span class="eyebrow">SMARTTOOLZ • TOOL LIBRARY</span>
    <h1>All the tools. One simple place.</h1>
    <p>Search the collection or choose a category to find a focused utility for images, PDFs, text, development, calculations and more.</p>
    <form class="tools-search-panel" method="get" action="/tools/" role="search">
      <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6.5" fill="none" stroke="currentColor" stroke-width="2"/><path d="m16 16 5 5" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
      <label class="visually-hidden" for="toolSearch">Search all tools</label>
      <input id="toolSearch" name="q" value="<?= htmlspecialchars($query, ENT_QUOTES, 'UTF-8') ?>" type="search" placeholder="Search image compressor, JSON formatter, QR code..." autocomplete="off">
      <input type="hidden" name="category" value="<?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?>">
      <input type="hidden" name="tag" value="<?= htmlspecialchars($tag, ENT_QUOTES, 'UTF-8') ?>">
      <button type="submit">Search</button>
    </form>
    <div class="tool-count"><strong><?= count($filtered) ?></strong> of <?= count($tools) ?> tools shown</div>
  </div>
</section>
<section class="page py-4">
  <div class="category-filter" aria-label="Tool categories">
    <a class="category-pill<?= $category === '' ? ' active' : '' ?>" href="/tools/">All <span><?= count($tools) ?></span></a>
    <?php foreach ($categories as $name => $count): ?>
      <a class="category-pill<?= $category === smarttoolz_slug($name) ? ' active' : '' ?>" href="/tools/?category=<?= rawurlencode(smarttoolz_slug($name)) ?><?= $query !== '' ? '&q='.rawurlencode($query) : '' ?><?= $tag !== '' ? '&tag='.rawurlencode($tag) : '' ?>"><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?> <span><?= (int)$count ?></span></a>
    <?php endforeach; ?>
  </div>
  <div class="tags-panel" aria-label="Popular tool tags">
    <?php foreach ($tags as $name => $count): ?>
      <a class="category-pill<?= $tag === $name ? ' active' : '' ?>" href="/tools/?tag=<?= rawurlencode($name) ?><?= $category !== '' ? '&category='.rawurlencode($category) : '' ?><?= $query !== '' ? '&q='.rawurlencode($query) : '' ?>"><?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?> <span><?= (int)$count ?></span></a>
    <?php endforeach; ?>
  </div>
</section>
<section class="page pb-5">
  <?php if (!$filtered): ?>
    <div class="empty-tools"><div class="tool-icon">?</div><h2>No matching tools yet</h2><p>Try a broader search or clear the category/tag filter to see the complete collection.</p><a class="tool-btn" href="/tools/">Show all tools</a></div>
  <?php else: ?>
    <div class="tools-library-grid">
      <?php foreach ($filtered as $tool): ?>
        <article class="tools-library-card" data-tool data-name="<?= htmlspecialchars(strtolower($tool['name'].' '.$tool['description'].' '.$tool['category'].' '.implode(' ', $tool['tags'] ?? [])), ENT_QUOTES, 'UTF-8') ?>">
          <div class="tool-card-top"><div class="tool-icon" aria-hidden="true"><?= htmlspecialchars((string)$tool['icon'], ENT_QUOTES, 'UTF-8') ?></div><span><?= htmlspecialchars((string)$tool['category'], ENT_QUOTES, 'UTF-8') ?></span></div>
          <h2><?= htmlspecialchars((string)$tool['name'], ENT_QUOTES, 'UTF-8') ?></h2>
          <p><?= htmlspecialchars((string)$tool['description'], ENT_QUOTES, 'UTF-8') ?></p>
          <div class="tool-tags" aria-label="Tool tags">
            <?php foreach (($tool['tags'] ?? []) as $toolTag): ?>
              <a class="tool-tag<?= $tag === $toolTag ? ' active' : '' ?>" href="/tools/?tag=<?= rawurlencode($toolTag) ?>" aria-label="View tools tagged <?= htmlspecialchars($toolTag, ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($toolTag, ENT_QUOTES, 'UTF-8') ?></a>
            <?php endforeach; ?>
          </div>
          <a class="tool-link" href="<?= htmlspecialchars((string)$tool['url'], ENT_QUOTES, 'UTF-8') ?>">Open tool <span aria-hidden="true">→</span></a>
        </article>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>
<section class="section section-alt">
  <div class="page">
    <div class="section-head"><div><span class="eyebrow">HOW TO CHOOSE</span><h2 class="mt-2">Start with the task, not the software.</h2><p>Pick the utility that matches the job you need to finish right now.</p></div></div>
    <div class="reason-grid">
      <article><b>01</b><h3>Need an image changed?</h3><p>Start with compression, resizing or format conversion depending on the result you need.</p></article>
      <article><b>02</b><h3>Working with text or code?</h3><p>Use text and developer utilities for formatting, cleaning, counting, encoding and structured data tasks.</p></article>
      <article><b>03</b><h3>Need a quick calculation?</h3><p>Choose a calculator or utility for common values without opening a larger application for a small task.</p></article>
    </div>
  </div>
</section>
<section class="page py-5">
  <div class="seo-section">
    <h2>Free online tools for everyday digital work</h2>
    <p>SmartToolz organizes practical web utilities around the jobs people actually need to complete. A dedicated page makes each tool easier to understand, while the library above helps you discover related tools without leaving the site.</p>
    <div class="row g-4 mt-1">
      <?php foreach (array_slice($categories, 0, 6, true) as $name => $count): ?>
        <div class="col-md-4"><h3><?= htmlspecialchars((string)$name, ENT_QUOTES, 'UTF-8') ?></h3><p><?= (int)$count ?> tools available for common <?= htmlspecialchars(strtolower((string)$name), ENT_QUOTES, 'UTF-8') ?> tasks.</p></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
</main>
<?php require __DIR__ . '/footer.php'; ?>
</body>
</html>