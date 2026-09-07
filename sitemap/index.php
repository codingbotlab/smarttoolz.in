<?php
require_once dirname(__DIR__) . '/bootstrap.php';
require_once dirname(__DIR__) . '/lib/tools.php';

$tools = smarttoolz_tools();
$grouped = [];
foreach ($tools as $tool) { $grouped[$tool['category']][] = $tool; }
ksort($grouped, SORT_NATURAL | SORT_FLAG_CASE);
$tagList = smarttoolz_tag_list();

$blogs = [];
$blogDir = dirname(__DIR__) . '/blog';
if (is_dir($blogDir)) {
    foreach (scandir($blogDir) ?: [] as $file) {
        if (!preg_match('/^([a-z0-9]+(?:-[a-z0-9]+)*)\.php$/', $file, $m)) continue;
        if (in_array($m[1], ['index', 'article'], true)) continue;
        $slug = $m[1];
        $content = @file_get_contents($blogDir . '/' . $file);
        $title = ucwords(str_replace('-', ' ', $slug));
        if ($content !== false && preg_match('/<title>(.*?)\s*\|\s*SmartToolz<\/title>/is', $content, $tm)) {
            $title = trim(strip_tags(html_entity_decode($tm[1], ENT_QUOTES, 'UTF-8')));
        }
        $blogs[] = ['slug' => $slug, 'title' => $title];
    }
}
usort($blogs, static fn($a, $b) => strcasecmp($a['title'], $b['title']));
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Sitemap | SmartToolz</title>
<meta name="description" content="Browse the complete SmartToolz website, free online tools, categories, tags, How To Use guides and helpful articles.">
<meta name="robots" content="index,follow,max-image-preview:large">
<link rel="canonical" href="https://smarttoolz.in/sitemap/">
<?php require dirname(__DIR__) . '/head.php'; ?>
</head>
<body>
<?php require dirname(__DIR__) . '/header.php'; ?>
<main class="sitemap-page">
  <section class="sitemap-hero"><span class="eyebrow">EXPLORE SMARTTOOLZ</span><h1>Everything in one place.</h1><p>Browse every SmartToolz page, category, tag, free online tool, How To Use guide and helpful article. Find what you need and get started in one click.</p></section>
  <section class="sitemap-toolbar"><div><strong><?= count($tools) ?></strong><span> tools</span></div><div><strong><?= count($grouped) ?></strong><span> categories</span></div><div><strong><?= count($tagList) ?></strong><span> tags</span></div><div><strong><?= count($tools) ?></strong><span> how-to guides</span></div><div><strong><?= count($blogs) ?></strong><span> articles</span></div><a href="/tools/">View All Tools →</a></section>

  <section class="sitemap-section">
    <div class="sitemap-section-title"><div><span class="eyebrow">CATEGORIES</span><h2>Browse tools by category</h2></div><a href="/tools/">View all tools →</a></div>
    <div class="category-sitemap-grid">
      <?php foreach ($grouped as $category => $items): ?>
        <?php $categorySlug = smarttoolz_slug((string)$category); ?>
        <a class="category-sitemap-card" href="/tools/?category=<?= rawurlencode($categorySlug) ?>" title="Browse <?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?> tools">
          <span><strong><?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?></strong><small><?= count($items) ?> tools</small></span><b>→</b>
        </a>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="sitemap-section">
    <div class="sitemap-section-title"><div><span class="eyebrow">TAGS</span><h2>Explore tools by topic</h2></div><a href="/tools/">Search tools →</a></div>
    <div class="tag-sitemap-list">
      <?php foreach ($tagList as $tag => $count): ?>
        <a href="/tools/?tag=<?= rawurlencode($tag) ?>" title="Tools tagged <?= htmlspecialchars($tag, ENT_QUOTES, 'UTF-8') ?>"><span><?= htmlspecialchars($tag, ENT_QUOTES, 'UTF-8') ?></span><small><?= (int)$count ?></small></a>
      <?php endforeach; ?>
    </div>
  </section>

  <?php if ($blogs): ?>
  <section class="sitemap-section">
    <div class="sitemap-section-title"><div><span class="eyebrow">BLOG</span><h2>Helpful guides</h2></div><a href="/blog/">View all articles →</a></div>
    <div class="blog-sitemap-grid">
      <?php foreach ($blogs as $blog): ?>
      <a class="blog-sitemap-card" href="/blog/<?= htmlspecialchars($blog['slug'], ENT_QUOTES, 'UTF-8') ?>/"><span class="blog-sitemap-icon">✦</span><span><?= htmlspecialchars($blog['title'], ENT_QUOTES, 'UTF-8') ?></span><b>→</b></a>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

  <section class="sitemap-section">
    <div class="sitemap-section-title"><div><span class="eyebrow">HOW TO USE</span><h2>Tool guides</h2></div><a href="/how-to/">View all guides →</a></div>
    <div class="how-sitemap-grid">
      <?php foreach ($grouped as $category => $items): ?>
        <?php foreach ($items as $tool): ?>
          <?php $slug = basename(trim((string)$tool['url'], '/')); ?>
          <a class="how-sitemap-card" href="/how-to/<?= htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') ?>/" title="How to use <?= htmlspecialchars($tool['name'], ENT_QUOTES, 'UTF-8') ?>">
            <span class="how-sitemap-icon"><?= htmlspecialchars((string)$tool['icon'], ENT_QUOTES, 'UTF-8') ?></span><span><strong>How to use <?= htmlspecialchars($tool['name'], ENT_QUOTES, 'UTF-8') ?></strong><small><?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?></small></span><b>→</b>
          </a>
        <?php endforeach; ?>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="sitemap-section"><div class="sitemap-section-title"><div><span class="eyebrow">TOOLS</span><h2>All tools by category</h2></div><a href="/tools/">View all tools →</a></div></section>
  <div class="sitemap-grid">
  <?php foreach ($grouped as $category => $items): ?>
    <section class="sitemap-card"><div class="sitemap-card-head"><h2><?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?></h2><span><?= count($items) ?></span></div><ul>
    <?php foreach ($items as $tool): ?><li><a href="<?= htmlspecialchars($tool['url'], ENT_QUOTES, 'UTF-8') ?>"><span><?= htmlspecialchars($tool['name'], ENT_QUOTES, 'UTF-8') ?></span><b>→</b></a></li><?php endforeach; ?>
    </ul></section>
  <?php endforeach; ?>
  </div>
</main>
<style>
.sitemap-page{width:min(1120px,calc(100% - 32px));margin:0 auto 80px}.sitemap-hero{text-align:center;padding:70px 0 38px}.eyebrow{color:#5b43ff;font-size:10px;font-weight:900;letter-spacing:1.8px}.sitemap-hero h1{margin:14px auto 12px;font-size:clamp(42px,7vw,68px);line-height:.98;letter-spacing:-3.5px}.sitemap-hero p{max-width:720px;margin:auto;color:#667085;font-size:15px;line-height:1.75}.sitemap-toolbar{display:flex;align-items:center;gap:18px;flex-wrap:wrap;margin-bottom:52px;padding:18px 20px;border:1px solid #e7eaf0;border-radius:16px;background:#fff;box-shadow:0 10px 28px rgba(16,24,40,.04);font-size:13px;color:#667085}.sitemap-toolbar strong{color:#111936;font-size:18px}.sitemap-toolbar a{margin-left:auto;text-decoration:none;color:#5b43ff;font-weight:800}.sitemap-section{margin-bottom:44px}.sitemap-section-title{display:flex;align-items:end;justify-content:space-between;gap:20px;margin-bottom:18px}.sitemap-section-title h2{margin:8px 0 0;font-size:28px;letter-spacing:-1px}.sitemap-section-title a{color:#5b43ff;text-decoration:none;font-size:13px;font-weight:800}.category-sitemap-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px}.category-sitemap-card{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:18px 20px;border:1px solid #e7eaf0;border-radius:16px;background:#fff;box-shadow:0 8px 24px rgba(16,24,40,.035);color:#17213f;text-decoration:none}.category-sitemap-card:hover{color:#5b43ff;transform:translateY(-2px)}.category-sitemap-card span{display:flex;flex-direction:column;gap:5px}.category-sitemap-card small{color:#8a94a8;font-size:11px}.tag-sitemap-list{display:flex;flex-wrap:wrap;gap:9px}.tag-sitemap-list a{display:inline-flex;align-items:center;gap:8px;padding:9px 12px;border:1px solid #e7eaf0;border-radius:999px;background:#fff;color:#344054;text-decoration:none;font-size:12px;font-weight:700}.tag-sitemap-list a:hover{color:#5b43ff;border-color:#d9d4ff;background:#f8f7ff}.tag-sitemap-list small{padding:2px 6px;border-radius:999px;background:#f0efff;color:#5b43ff;font-size:9px;font-weight:900}.blog-sitemap-grid,.how-sitemap-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.blog-sitemap-card,.how-sitemap-card{display:flex;align-items:center;gap:14px;padding:18px 20px;border:1px solid #e7eaf0;border-radius:16px;background:#fff;box-shadow:0 8px 24px rgba(16,24,40,.035);color:#17213f;text-decoration:none;transition:transform .18s ease,box-shadow .18s ease}.blog-sitemap-card{font-size:14px;font-weight:700}.how-sitemap-card{font-size:13px}.blog-sitemap-card:hover,.how-sitemap-card:hover{transform:translateY(-2px);box-shadow:0 14px 30px rgba(16,24,40,.08);color:#5b43ff}.blog-sitemap-icon,.how-sitemap-icon{display:grid;place-items:center;flex:0 0 38px;width:38px;height:38px;border-radius:12px;background:#f0edff;color:#5b43ff;font-weight:900}.how-sitemap-card span:nth-child(2){display:flex;flex-direction:column;gap:4px}.how-sitemap-card small{color:#8a94a8;font-size:10px}.blog-sitemap-card b,.how-sitemap-card b,.category-sitemap-card b{margin-left:auto;opacity:.45}.sitemap-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:16px}.sitemap-card{border:1px solid #e7eaf0;border-radius:18px;background:#fff;padding:22px;box-shadow:0 10px 28px rgba(16,24,40,.04)}.sitemap-card-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:12px}.sitemap-card h2{margin:0;font-size:17px;letter-spacing:-.4px}.sitemap-card-head span{display:grid;place-items:center;min-width:28px;height:26px;padding:0 8px;border-radius:99px;background:#f1efff;color:#5b43ff;font-size:11px;font-weight:900}.sitemap-card ul{list-style:none;margin:0;padding:0}.sitemap-card li+li{border-top:1px solid #f0f1f4}.sitemap-card a{display:flex;justify-content:space-between;gap:12px;padding:10px 2px;color:#344054;text-decoration:none;font-size:13px}.sitemap-card a:hover{color:#5b43ff}.sitemap-card b{opacity:.45;font-weight:500}@media(max-width:850px){.sitemap-grid,.category-sitemap-grid,.blog-sitemap-grid,.how-sitemap-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:560px){.sitemap-page{width:calc(100% - 20px)}.sitemap-hero{padding:48px 0 28px}.sitemap-hero h1{letter-spacing:-2.3px}.sitemap-toolbar{margin-bottom:38px}.sitemap-toolbar a{width:100%;margin-left:0}.sitemap-grid,.category-sitemap-grid,.blog-sitemap-grid,.how-sitemap-grid{grid-template-columns:1fr}.sitemap-section-title{align-items:start;flex-direction:column}.sitemap-card{padding:18px}}
</style>
<?php require dirname(__DIR__) . '/footer.php'; ?>
</body>
</html>
