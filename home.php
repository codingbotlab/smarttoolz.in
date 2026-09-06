<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/lib/tools.php';

$tools = smarttoolz_tools();
$categories = smarttoolz_categories();
$featured = array_slice($tools, 0, 8);
$categoryMeta = [
    'Image Tools' => ['icon' => '▧', 'class' => 'pink', 'copy' => 'Resize, compress, convert and edit images.'],
    'PDF Tools' => ['icon' => '⌑', 'class' => 'red', 'copy' => 'Convert, merge, split and edit PDFs.'],
    'Text Tools' => ['icon' => 'T', 'class' => 'blue', 'copy' => 'Transform, format and analyze text.'],
    'Calculators' => ['icon' => '▦', 'class' => 'green', 'copy' => 'Solve everyday calculations instantly.'],
    'Developer Tools' => ['icon' => '</>', 'class' => 'purple', 'copy' => 'Encode, decode, format and generate.'],
    'Generators' => ['icon' => '⚙', 'class' => 'orange', 'copy' => 'Create useful random data and outputs.'],
];
$categoryFallbacks = [
    'Security' => ['icon' => 'KEY', 'class' => 'purple', 'copy' => 'Protect, generate and work with secure data.'],
    'Design Tools' => ['icon' => 'CLR', 'class' => 'pink', 'copy' => 'Pick, inspect and work with design values.'],
    'Other Tools' => ['icon' => 'TOOL', 'class' => 'blue', 'copy' => 'Useful browser-based utilities for everyday tasks.'],
];
$genericClasses = ['pink','red','blue','green','purple','orange'];
foreach (array_keys($categories) as $i => $category) {
    if (!isset($categoryMeta[$category])) {
        $categoryMeta[$category] = $categoryFallbacks[$category] ?? [
            'icon' => 'TOOL',
            'class' => $genericClasses[$i % count($genericClasses)],
            'copy' => 'Useful free online tools for '.strtolower($category).' tasks.'
        ];
    }
}
$heroCategories = array_keys($categories);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SmartToolz — Free Online Tools for Everyday Tasks</title>
<meta name="description" content="Free online tools for image, PDF, text, developer and calculator tasks. SmartToolz is simple, fast and easy to use with no signup required for everyday utilities.">
<meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
<link rel="canonical" href="https://smarttoolz.in/">
<meta property="og:type" content="website"><meta property="og:site_name" content="SmartToolz">
<meta property="og:title" content="SmartToolz — Free Online Tools for Everyday Tasks">
<meta property="og:description" content="Edit, convert, generate and solve everyday tasks with free online tools.">
<meta property="og:url" content="https://smarttoolz.in/"><meta property="og:image" content="https://smarttoolz.in/assets/home/hero-tools.svg">
<meta name="twitter:card" content="summary_large_image"><meta name="twitter:title" content="SmartToolz — Free Online Tools for Everyday Tasks"><meta name="twitter:description" content="Free, simple browser-based tools for common digital work."><meta name="twitter:image" content="https://smarttoolz.in/assets/home/hero-tools.svg">
<?php require __DIR__ . '/head.php'; ?>
<script type="application/ld+json"><?= json_encode(['@context'=>'https://schema.org','@graph'=>[
    ['@type'=>'WebSite','name'=>'SmartToolz','url'=>'https://smarttoolz.in/','description'=>'Free browser-based online tools for common digital tasks.'],
    ['@type'=>'Organization','name'=>'SmartToolz','url'=>'https://smarttoolz.in/']
]], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) ?></script>
</head>
<body>
<?php require __DIR__ . '/header.php'; ?>
<main>
<section class="home-hero">
  <div class="home-hero-glow home-hero-glow-left" aria-hidden="true"></div><div class="home-hero-glow home-hero-glow-right" aria-hidden="true"></div>
  <div class="page hero-inner">
    <div class="hero-copy-col">
      <span class="hero-badge">100% Free <b>•</b> No Signup <b>•</b> Fast &amp; Easy</span>
      <h1>Smart Tools for <span>Everyday Tasks</span></h1>
      <p>Edit, convert, generate, and solve — all in one place.<br>Free online tools to make your work simple, fast, and productive.</p>
      <form class="hero-search" action="/tools/" method="get" role="search">
        <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="7" fill="none" stroke="currentColor" stroke-width="2.4"/><path d="m16.2 16.2 4.8 4.8" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"/></svg>
        <label class="visually-hidden" for="heroToolSearch">Search any tool</label>
        <input id="heroToolSearch" name="q" type="search" placeholder="Search any tool...  (e.g. image compressor, pdf to word, qr code)" autocomplete="off">
        <button type="submit">Search</button>
      </form>
      <div class="hero-note"><span>✓ Works in your browser</span><span>✓ No software to install</span><span>✓ Mobile friendly</span></div>
    </div>
    <div class="hero-art-wrap">
      <div class="hero-orbit orbit-one" aria-hidden="true"></div><div class="hero-orbit orbit-two" aria-hidden="true"></div>
      <figure class="hero-art">
        <img src="/assets/home/hero-tools.svg" width="800" height="520" alt="SmartToolz collection of online tools shown on a modern dashboard" loading="eager" fetchpriority="high">
      </figure>
    </div>
  </div>
</section>

<section class="page category-section" id="categories" aria-label="Tool categories">
  <div class="category-grid-home">
    <?php foreach ($heroCategories as $category): $meta = $categoryMeta[$category]; ?>
      <a class="category-card-home" href="/tools/?category=<?= rawurlencode(smarttoolz_slug($category)) ?>">
        <span class="category-icon <?= htmlspecialchars($meta['class']) ?>"><?= htmlspecialchars($meta['icon']) ?></span>
        <span class="category-copy"><strong><?= htmlspecialchars($category) ?></strong><small><?= htmlspecialchars($meta['copy']) ?></small><em><?= (int)$categories[$category] ?>+ Tools</em></span>
        <span class="category-arrow" aria-hidden="true">→</span>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<section class="page trust-strip" aria-label="Why use SmartToolz">
  <div class="trust-item"><span class="trust-symbol">ϟ</span><div><strong>Fast &amp; Reliable</strong><small>Get results in seconds</small></div></div>
  <div class="trust-item"><span class="trust-symbol">♢</span><div><strong>100% Free</strong><small>No signup required</small></div></div>
  <div class="trust-item"><span class="trust-symbol">♡</span><div><strong>User Friendly</strong><small>Simple and clean interface</small></div></div>
  <div class="trust-item"><span class="trust-symbol">♟</span><div><strong>All-in-One</strong><small><?= count($tools) ?>+ useful tools</small></div></div>
</section>

<section class="page py-5" id="popular">
  <div class="section-head home-section-head"><div><span class="eyebrow">POPULAR TOOLS</span><h2 class="mt-2">Start with a useful tool</h2><p>Focused utilities for common image, text, developer and everyday tasks.</p></div><a href="/tools/">View all tools →</a></div>
  <div class="tool-grid">
    <?php foreach ($featured as $tool): ?>
      <article class="tool-card"><div class="tool-icon" aria-hidden="true"><?= htmlspecialchars((string)$tool['icon']) ?></div><h2><?= htmlspecialchars((string)$tool['name']) ?></h2><p><?= htmlspecialchars((string)$tool['description']) ?></p><a class="tool-link" href="<?= htmlspecialchars((string)$tool['url']) ?>">Open tool →</a></article>
    <?php endforeach; ?>
  </div>
</section>

<section class="section section-alt" id="about">
  <div class="page">
    <div class="section-head home-section-head"><div><span class="eyebrow">WHY SMARTTOOLZ</span><h2 class="mt-2">Useful tools without the clutter.</h2><p>Each utility is designed around one clear task, with straightforward controls and practical guidance.</p></div></div>
    <div class="reason-grid"><article><b>01</b><h3>Simple by design</h3><p>Important controls stay visible so you can move from input to result without learning a complicated dashboard.</p></article><article><b>02</b><h3>Made for everyday work</h3><p>Use quick utilities while studying, creating content, building websites or preparing digital files.</p></article><article><b>03</b><h3>Easy to discover</h3><p>Consistent categories and page layouts make related tools easier to find when the next task comes up.</p></article></div>
  </div>
</section>

<section class="page py-5" id="resources"><div class="seo-section"><span class="eyebrow">LEARN BEFORE YOU USE</span><h2 class="mt-3">Free online tools for real-world digital tasks</h2><p>People often need a small utility at exactly the moment a larger application feels unnecessary. A student may need a word count, a developer may need a JSON formatter, a designer may need a color value, or a website owner may need to reduce an image before uploading it. SmartToolz keeps these practical tasks together while giving each tool its own focused workflow and helpful explanation.</p><div class="row g-4 mt-2"><div class="col-md-4"><h3>Work &amp; study</h3><p>Count, clean and transform text, calculate values and prepare common files quickly.</p></div><div class="col-md-4"><h3>Web &amp; development</h3><p>Format structured data, encode URLs, generate useful strings and simplify repetitive tasks.</p></div><div class="col-md-4"><h3>Images &amp; documents</h3><p>Compress, resize and convert images and handle common PDF workflows in the browser.</p></div></div></div></section>
<section class="page pb-5"><div class="seo-section"><span class="eyebrow">FAQ</span><h2 class="mt-3">Frequently asked questions</h2><div class="faq mt-3"><details><summary>Are SmartToolz tools free?</summary><p>The public SmartToolz collection is designed as free online utilities. Individual tools may have their own browser or technical limits.</p></details><details><summary>Do I need to install software?</summary><p>Most tools are designed to work directly in a modern browser, so everyday jobs can be handled without a separate desktop application.</p></details><details><summary>Can I use SmartToolz on a phone?</summary><p>Yes. The layouts are responsive so the site can be used across desktop and mobile screens.</p></details></div></div></section>
</main>
<?php require __DIR__ . '/footer.php'; ?>
</body>
</html>
