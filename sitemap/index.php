<?php
require_once dirname(__DIR__) . '/bootstrap.php';
require_once dirname(__DIR__) . '/lib/tools.php';
$tools = smarttoolz_tools();
$grouped = [];
foreach ($tools as $tool) { $grouped[$tool['category']][] = $tool; }
ksort($grouped, SORT_NATURAL | SORT_FLAG_CASE);
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Sitemap | SmartToolz</title>
<meta name="description" content="Browse the complete SmartToolz website and all free online tools by category.">
<meta name="robots" content="index,follow,max-image-preview:large">
<link rel="canonical" href="https://smarttoolz.in/sitemap/">
<?php require dirname(__DIR__) . '/head.php'; ?>
</head>
<body>
<?php require dirname(__DIR__) . '/header.php'; ?>
<main class="sitemap-page">
  <section class="sitemap-hero"><span class="eyebrow">EXPLORE SMARTTOOLZ</span><h1>Everything in one place.</h1><p>Browse every SmartToolz page and free online tool by category. Find the tool you need and get started in one click.</p></section>
  <section class="sitemap-toolbar"><div><strong><?= count($tools) ?></strong><span> tools available</span></div><div><strong><?= count($grouped) ?></strong><span> categories</span></div><a href="/tools/">View All Tools →</a></section>
  <div class="sitemap-grid">
  <?php foreach ($grouped as $category => $items): ?>
    <section class="sitemap-card"><div class="sitemap-card-head"><h2><?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?></h2><span><?= count($items) ?></span></div><ul>
    <?php foreach ($items as $tool): ?><li><a href="<?= htmlspecialchars($tool['url'], ENT_QUOTES, 'UTF-8') ?>"><span><?= htmlspecialchars($tool['name'], ENT_QUOTES, 'UTF-8') ?></span><b>→</b></a></li><?php endforeach; ?>
    </ul></section>
  <?php endforeach; ?>
  </div>
</main>
<style>
.sitemap-page{width:min(1120px,calc(100% - 32px));margin:0 auto 80px}.sitemap-hero{text-align:center;padding:70px 0 38px}.eyebrow{color:#5b43ff;font-size:10px;font-weight:900;letter-spacing:1.8px}.sitemap-hero h1{margin:14px auto 12px;font-size:clamp(42px,7vw,68px);line-height:.98;letter-spacing:-3.5px}.sitemap-hero p{max-width:700px;margin:auto;color:#667085;font-size:15px;line-height:1.75}.sitemap-toolbar{display:flex;align-items:center;gap:22px;margin-bottom:22px;padding:18px 20px;border:1px solid #e7eaf0;border-radius:16px;background:#fff;box-shadow:0 10px 28px rgba(16,24,40,.04);font-size:13px;color:#667085}.sitemap-toolbar strong{color:#111936;font-size:18px}.sitemap-toolbar a{margin-left:auto;text-decoration:none;color:#5b43ff;font-weight:800}.sitemap-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}.sitemap-card{border:1px solid #e7eaf0;border-radius:18px;background:#fff;padding:22px;box-shadow:0 10px 28px rgba(16,24,40,.04)}.sitemap-card-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px}.sitemap-card h2{margin:0;font-size:17px;letter-spacing:-.4px}.sitemap-card-head span{display:grid;place-items:center;min-width:28px;height:26px;padding:0 8px;border-radius:99px;background:#f1efff;color:#5b43ff;font-size:11px;font-weight:900}.sitemap-card ul{list-style:none;margin:0;padding:0}.sitemap-card li+li{border-top:1px solid #f0f1f4}.sitemap-card a{display:flex;justify-content:space-between;gap:12px;padding:10px 2px;color:#344054;text-decoration:none;font-size:13px}.sitemap-card a:hover{color:#5b43ff}.sitemap-card b{opacity:.45;font-weight:500}@media(max-width:850px){.sitemap-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:560px){.sitemap-page{width:calc(100% - 20px)}.sitemap-hero{padding:48px 0 28px}.sitemap-hero h1{letter-spacing:-2.3px}.sitemap-toolbar{flex-wrap:wrap;gap:10px}.sitemap-toolbar a{width:100%;margin-left:0;margin-top:5px}.sitemap-grid{grid-template-columns:1fr}.sitemap-card{padding:18px}}
</style>
<?php require dirname(__DIR__) . '/footer.php'; ?>
</body>
</html>
