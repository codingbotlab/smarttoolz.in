<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/bootstrap.php';
require_once dirname(__DIR__) . '/lib/blog.php';
$posts = smarttoolz_blogs();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SmartToolz Blog — Tips, Guides &amp; Useful Tools</title>
<meta name="description" content="Practical guides, tips and explanations for getting more from SmartToolz and everyday digital tools.">
<meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
<link rel="canonical" href="https://smarttoolz.in/blog/">
<?php require dirname(__DIR__) . '/head.php'; ?>
</head>
<body>
<?php require dirname(__DIR__) . '/header.php'; ?>
<main class="blog-page">
  <section class="blog-hero">
    <span class="eyebrow">SMARTTOOLZ BLOG</span>
    <h1>Tips, guides &amp; useful ideas.</h1>
    <p>Practical articles to help you work smarter with images, PDFs, text, development and everyday digital tasks.</p>
  </section>
  <section class="blog-list-wrap" aria-labelledby="all-articles">
    <div class="blog-section-head"><div><span class="eyebrow">LATEST ARTICLES</span><h2 id="all-articles">All articles</h2></div><span class="blog-count"><?= count($posts) ?> articles</span></div>
    <?php if ($posts): ?>
      <div class="blog-grid">
        <?php foreach ($posts as $post): ?>
          <article class="blog-card"><div class="blog-card-top"><span>GUIDE</span><span>SmartToolz</span></div><h3><a href="<?= htmlspecialchars($post['url'], ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></a></h3><p>Read this practical SmartToolz guide for useful tips and straightforward ways to handle everyday digital tasks.</p><a class="blog-read" href="<?= htmlspecialchars($post['url'], ENT_QUOTES, 'UTF-8') ?>">Read article →</a></article>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="blog-empty"><div class="blog-empty-icon">✦</div><h3>Articles are coming soon.</h3><p>New practical guides and useful tips will appear here as they are published.</p></div>
    <?php endif; ?>
  </section>
</main>
<style>
.blog-page{width:min(1100px,calc(100% - 32px));margin:0 auto 80px}.blog-hero{text-align:center;padding:70px 0 48px}.blog-hero h1{margin:14px auto 12px;font-size:clamp(42px,7vw,68px);line-height:1;letter-spacing:-3px}.blog-hero p{max-width:700px;margin:auto;color:#667085;line-height:1.75;font-size:15px}.blog-section-head{display:flex;justify-content:space-between;align-items:end;gap:20px;margin-bottom:20px}.blog-section-head h2{margin:8px 0 0;font-size:30px;letter-spacing:-1px}.blog-count{font-size:12px;color:#667085}.blog-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}.blog-card{padding:25px;border:1px solid #e7eaf0;border-radius:20px;background:#fff;box-shadow:0 12px 35px rgba(16,24,40,.05);transition:transform .2s ease,box-shadow .2s ease}.blog-card:hover{transform:translateY(-3px);box-shadow:0 16px 40px rgba(16,24,40,.09)}.blog-card-top{display:flex;justify-content:space-between;color:#6b5cff;font-size:9px;font-weight:900;letter-spacing:1.3px}.blog-card h3{font-size:20px;line-height:1.25;margin:25px 0 10px}.blog-card h3 a{color:inherit;text-decoration:none}.blog-card p{margin:0 0 20px;color:#667085;font-size:13px;line-height:1.75}.blog-read{font-size:12px;font-weight:800;text-decoration:none}.blog-empty{padding:55px 25px;text-align:center;border:1px dashed #d9dde7;border-radius:20px;background:#fafbff}.blog-empty-icon{width:48px;height:48px;margin:0 auto 15px;display:grid;place-items:center;border-radius:14px;background:#eeeaff;color:#5b43ff}.blog-empty h3{margin:0 0 8px;font-size:20px}.blog-empty p{margin:0;color:#667085;font-size:13px}@media(max-width:800px){.blog-grid{grid-template-columns:1fr 1fr}}@media(max-width:560px){.blog-page{width:calc(100% - 20px)}.blog-hero{padding:48px 0 35px}.blog-hero h1{letter-spacing:-2px}.blog-grid{grid-template-columns:1fr}.blog-section-head{align-items:start;flex-direction:column}}
</style>
<?php require dirname(__DIR__) . '/footer.php'; ?>
</body>
</html>
