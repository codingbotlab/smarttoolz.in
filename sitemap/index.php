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
<title>HTML Sitemap | SmartToolz</title>
<meta name="description" content="Explore the complete SmartToolz HTML sitemap with all tools, categories, topics, How To Use guides and helpful articles. Find every useful page in one place.">
<meta name="robots" content="index,follow,max-image-preview:large">
<link rel="canonical" href="https://smarttoolz.in/sitemap/">
<?php require dirname(__DIR__) . '/head.php'; ?>
</head>
<body>
<?php require dirname(__DIR__) . '/header.php'; ?>
<main class="sitemap-page">
  <section class="sitemap-hero">
    <span class="eyebrow">SMARTTOOLZ HTML SITEMAP</span>
    <h1>Find every useful page.</h1>
    <p>Browse SmartToolz by category, topic, tool, guide and article. This human-friendly sitemap keeps the site easy to explore on desktop and mobile.</p>
    <div class="sitemap-actions"><a class="primary" href="/tools/">Explore All Tools</a><a href="/blog/">Read the Blog</a><a href="/how-to/">Browse How-To Guides</a></div>
  </section>

  <section class="sitemap-stats" aria-label="Sitemap summary">
    <div><strong><?= count($tools) ?></strong><span>Tools</span></div>
    <div><strong><?= count($grouped) ?></strong><span>Categories</span></div>
    <div><strong><?= count($tagList) ?></strong><span>Topics</span></div>
    <div><strong><?= count($tools) ?></strong><span>How-To Guides</span></div>
    <div><strong><?= count($blogs) ?></strong><span>Articles</span></div>
  </section>

  <section class="sitemap-section">
    <div class="sitemap-section-title"><div><span class="eyebrow">CATEGORIES</span><h2>Browse tools by category</h2></div><a href="/tools/">All tools →</a></div>
    <div class="category-sitemap-grid">
      <?php foreach ($grouped as $category => $items): ?>
        <?php $categorySlug = smarttoolz_slug((string)$category); ?>
        <a class="category-sitemap-card" href="/tools/?category=<?= rawurlencode($categorySlug) ?>" title="Browse <?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?> tools">
          <span class="category-icon"><?= htmlspecialchars(mb_strtoupper(mb_substr((string)$category, 0, 1, 'UTF-8'), 'UTF-8'), ENT_QUOTES, 'UTF-8') ?></span>
          <span class="category-copy"><strong><?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?></strong><small><?= count($items) ?> tools</small></span>
          <b>→</b>
        </a>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="sitemap-section">
    <div class="sitemap-section-title"><div><span class="eyebrow">TOPICS &amp; TAGS</span><h2>Explore tools by topic</h2></div><a href="/tools/">Search tools →</a></div>
    <p class="section-intro">Jump directly to related tools using the site's complete topic index.</p>
    <div class="tag-sitemap-wrap">
      <button class="tag-arrow tag-prev" type="button" aria-label="Scroll topics left">‹</button>
      <div class="tag-sitemap-list" id="sitemapTags">
        <?php foreach ($tagList as $tag => $count): ?>
          <a href="/tools/?tag=<?= rawurlencode($tag) ?>" title="Browse <?= htmlspecialchars($tag, ENT_QUOTES, 'UTF-8') ?> tools"><span><?= htmlspecialchars($tag, ENT_QUOTES, 'UTF-8') ?></span><small><?= (int)$count ?></small></a>
        <?php endforeach; ?>
      </div>
      <button class="tag-arrow tag-next" type="button" aria-label="Scroll topics right">›</button>
    </div>
  </section>

  <?php if ($blogs): ?>
  <section class="sitemap-section">
    <div class="sitemap-section-title"><div><span class="eyebrow">BLOG</span><h2>Helpful guides &amp; articles</h2></div><a href="/blog/">View all →</a></div>
    <div class="blog-sitemap-grid">
      <?php foreach ($blogs as $blog): ?>
      <a class="blog-sitemap-card" href="/blog/<?= htmlspecialchars($blog['slug'], ENT_QUOTES, 'UTF-8') ?>/"><span class="blog-sitemap-icon">✦</span><span><?= htmlspecialchars($blog['title'], ENT_QUOTES, 'UTF-8') ?></span><b>→</b></a>
      <?php endforeach; ?>
    </div>
  </section>
  <?php endif; ?>

  <section class="sitemap-section">
    <div class="sitemap-section-title"><div><span class="eyebrow">HOW TO USE</span><h2>Step-by-step tool guides</h2></div><a href="/how-to/">View all guides →</a></div>
    <div class="how-sitemap-grid">
      <?php foreach ($grouped as $category => $items): ?>
        <?php foreach ($items as $tool): ?>
          <?php $slug = basename(trim((string)$tool['url'], '/')); ?>
          <a class="how-sitemap-card" href="/how-to/<?= htmlspecialchars($slug, ENT_QUOTES, 'UTF-8') ?>/" title="How to use <?= htmlspecialchars($tool['name'], ENT_QUOTES, 'UTF-8') ?>">
            <span class="how-sitemap-icon"><?= htmlspecialchars((string)$tool['icon'], ENT_QUOTES, 'UTF-8') ?></span>
            <span><strong>How to use <?= htmlspecialchars($tool['name'], ENT_QUOTES, 'UTF-8') ?></strong><small><?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?></small></span>
            <b>→</b>
          </a>
        <?php endforeach; ?>
      <?php endforeach; ?>
    </div>
  </section>

  <section class="sitemap-section sitemap-tools-section">
    <div class="sitemap-section-title"><div><span class="eyebrow">COMPLETE DIRECTORY</span><h2>All tools by category</h2></div><a href="/tools/">Open tools directory →</a></div>
    <p class="section-intro">Every published tool is linked below, grouped by its primary category.</p>
    <div class="sitemap-grid">
      <?php foreach ($grouped as $category => $items): ?>
        <section class="sitemap-card">
          <div class="sitemap-card-head"><h3><?= htmlspecialchars($category, ENT_QUOTES, 'UTF-8') ?></h3><span><?= count($items) ?></span></div>
          <ul>
          <?php foreach ($items as $tool): ?>
            <li><a href="<?= htmlspecialchars($tool['url'], ENT_QUOTES, 'UTF-8') ?>"><span><?= htmlspecialchars($tool['name'], ENT_QUOTES, 'UTF-8') ?></span><b>→</b></a></li>
          <?php endforeach; ?>
          </ul>
        </section>
      <?php endforeach; ?>
    </div>
  </section>
</main>
<style>
.sitemap-page{width:min(1160px,calc(100% - 32px));margin:0 auto 80px}.sitemap-hero{text-align:center;padding:64px 0 34px}.eyebrow{color:#5b43ff;font-size:10px;font-weight:900;letter-spacing:1.8px}.sitemap-hero h1{margin:14px auto 12px;font-size:clamp(40px,6vw,64px);line-height:1;letter-spacing:-3px;color:#111936}.sitemap-hero p{max-width:760px;margin:auto;color:#667085;font-size:15px;line-height:1.75}.sitemap-actions{display:flex;justify-content:center;flex-wrap:wrap;gap:10px;margin-top:24px}.sitemap-actions a{padding:11px 16px;border:1px solid #e2e5ec;border-radius:11px;background:#fff;color:#344054;text-decoration:none;font-size:12px;font-weight:800}.sitemap-actions a.primary{background:#5b43ff;border-color:#5b43ff;color:#fff}.sitemap-stats{display:grid;grid-template-columns:repeat(5,1fr);border:1px solid #e7eaf0;border-radius:16px;background:#fff;box-shadow:0 10px 28px rgba(16,24,40,.04);margin-bottom:54px}.sitemap-stats div{text-align:center;padding:17px 10px}.sitemap-stats div+div{border-left:1px solid #eef0f4}.sitemap-stats strong{display:block;color:#111936;font-size:20px}.sitemap-stats span{display:block;margin-top:3px;color:#8a94a8;font-size:11px}.sitemap-section{margin-bottom:50px}.sitemap-section-title{display:flex;align-items:end;justify-content:space-between;gap:20px;margin-bottom:18px}.sitemap-section-title h2{margin:8px 0 0;font-size:27px;letter-spacing:-1px;color:#111936}.sitemap-section-title a{color:#5b43ff;text-decoration:none;font-size:12px;font-weight:800;white-space:nowrap}.section-intro{margin:-7px 0 16px;color:#667085;font-size:13px;line-height:1.6}.category-sitemap-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px}.category-sitemap-card{display:flex;align-items:center;gap:13px;padding:17px 18px;border:1px solid #e7eaf0;border-radius:15px;background:#fff;box-shadow:0 8px 24px rgba(16,24,40,.035);color:#17213f;text-decoration:none;transition:.18s ease}.category-sitemap-card:hover{color:#5b43ff;transform:translateY(-2px);border-color:#d9d4ff}.category-icon{display:grid;place-items:center;flex:0 0 38px;width:38px;height:38px;border-radius:11px;background:#f0edff;color:#5b43ff;font-size:13px;font-weight:900}.category-copy{display:flex;flex-direction:column;gap:4px}.category-copy small{color:#8a94a8;font-size:10px}.category-sitemap-card b{margin-left:auto;opacity:.45}.tag-sitemap-wrap{position:relative}.tag-sitemap-list{display:flex;gap:9px;overflow-x:auto;scroll-behavior:smooth;scrollbar-width:none;padding:3px 2px 9px;cursor:grab}.tag-sitemap-list::-webkit-scrollbar{display:none}.tag-sitemap-list a{flex:0 0 auto;display:inline-flex;align-items:center;gap:8px;padding:9px 12px;border:1px solid #e4e7ec;border-radius:999px;background:#fff;color:#344054;text-decoration:none;font-size:12px;font-weight:700}.tag-sitemap-list a:hover{color:#5b43ff;border-color:#d9d4ff;background:#f8f7ff}.tag-sitemap-list small{padding:2px 6px;border-radius:999px;background:#f0efff;color:#5b43ff;font-size:9px;font-weight:900}.tag-arrow{position:absolute;top:50%;z-index:2;transform:translateY(-65%);width:34px;height:34px;border:1px solid #e0e3ea;border-radius:50%;background:#fff;color:#344054;box-shadow:0 5px 18px rgba(16,24,40,.12);font-size:22px;line-height:1;cursor:pointer}.tag-prev{left:-15px}.tag-next{right:-15px}.blog-sitemap-grid,.how-sitemap-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.blog-sitemap-card,.how-sitemap-card{display:flex;align-items:center;gap:14px;padding:17px 18px;border:1px solid #e7eaf0;border-radius:15px;background:#fff;box-shadow:0 8px 24px rgba(16,24,40,.035);color:#17213f;text-decoration:none;transition:.18s ease}.blog-sitemap-card:hover,.how-sitemap-card:hover{transform:translateY(-2px);color:#5b43ff;border-color:#d9d4ff}.blog-sitemap-card{font-size:13px;font-weight:700}.how-sitemap-card{font-size:12px}.blog-sitemap-icon,.how-sitemap-icon{display:grid;place-items:center;flex:0 0 38px;width:38px;height:38px;border-radius:11px;background:#f0edff;color:#5b43ff;font-weight:900}.how-sitemap-card span:nth-child(2){display:flex;flex-direction:column;gap:4px}.how-sitemap-card small{color:#8a94a8;font-size:10px}.blog-sitemap-card b,.how-sitemap-card b{margin-left:auto;opacity:.45}.sitemap-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:15px}.sitemap-card{border:1px solid #e7eaf0;border-radius:16px;background:#fff;padding:19px;box-shadow:0 8px 24px rgba(16,24,40,.035)}.sitemap-card-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:10px}.sitemap-card h3{margin:0;font-size:16px;letter-spacing:-.3px;color:#17213f}.sitemap-card-head span{display:grid;place-items:center;min-width:27px;height:24px;padding:0 7px;border-radius:99px;background:#f1efff;color:#5b43ff;font-size:10px;font-weight:900}.sitemap-card ul{list-style:none;margin:0;padding:0}.sitemap-card li+li{border-top:1px solid #f0f1f4}.sitemap-card a{display:flex;justify-content:space-between;gap:10px;padding:9px 1px;color:#475467;text-decoration:none;font-size:12px}.sitemap-card a:hover{color:#5b43ff}.sitemap-card b{opacity:.4;font-weight:500}@media(max-width:850px){.sitemap-stats{grid-template-columns:repeat(3,1fr)}.sitemap-stats div:nth-child(4){border-left:0;border-top:1px solid #eef0f4}.sitemap-stats div:nth-child(5){border-top:1px solid #eef0f4}.category-sitemap-grid,.sitemap-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}@media(max-width:560px){.sitemap-page{width:calc(100% - 20px)}.sitemap-hero{padding:48px 0 28px}.sitemap-hero h1{letter-spacing:-2.2px}.sitemap-actions{display:grid;grid-template-columns:1fr}.sitemap-actions a{text-align:center}.sitemap-stats{grid-template-columns:repeat(2,1fr);margin-bottom:42px}.sitemap-stats div:nth-child(3){border-left:0;border-top:1px solid #eef0f4}.sitemap-stats div:nth-child(4){border-left:1px solid #eef0f4}.sitemap-stats div:nth-child(5){grid-column:1/-1;border-left:0}.category-sitemap-grid,.sitemap-grid,.blog-sitemap-grid,.how-sitemap-grid{grid-template-columns:1fr}.sitemap-section-title{align-items:flex-start}.sitemap-section-title h2{font-size:23px}.tag-arrow{display:none}.tag-sitemap-list{margin-right:0}.sitemap-card{padding:17px}}
</style>
<script>
(function(){
  const rail=document.getElementById('sitemapTags');
  if(!rail)return;
  const prev=document.querySelector('.tag-prev'), next=document.querySelector('.tag-next');
  const move=dir=>rail.scrollBy({left:dir*Math.min(520,rail.clientWidth*.8),behavior:'smooth'});
  prev?.addEventListener('click',()=>move(-1));
  next?.addEventListener('click',()=>move(1));
})();
</script>
<?php require dirname(__DIR__) . '/footer.php'; ?>
</body>
</html>
