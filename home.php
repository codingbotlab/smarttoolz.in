<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/lib/tools.php';

$tools = smarttoolz_tools();
$categories = smarttoolz_categories();
$featured = array_slice($tools, 0, 12);
$categoryDescriptions = [
    'Image Tools' => 'Compress, resize, crop, rotate and convert common image formats.',
    'Text Tools' => 'Count, clean, transform and organize text for everyday work.',
    'Developer Tools' => 'Format data, encode content, create slugs and simplify web tasks.',
    'PDF Tools' => 'Handle common PDF conversion, merging, splitting and compression jobs.',
    'Calculators' => 'Quick calculators for age, percentage, BMI and everyday conversions.',
    'Generators' => 'Create QR codes, passwords, placeholder text and other useful outputs.',
    'Design Tools' => 'Pick and convert colors for design, development and visual projects.',
    'Security' => 'Generate strong passwords and use practical privacy-minded utilities.',
    'Utilities' => 'Simple tools for timing, timestamps and other everyday tasks.',
];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Free Online Tools for Images, PDF, Text & More | SmartToolz</title>
<meta name="description" content="Use free online tools for image compression, resizing, PDF tasks, text editing, developer utilities, calculators and generators. SmartToolz keeps everyday tasks simple and fast.">
<meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">
<link rel="canonical" href="https://smarttoolz.in/">
<meta property="og:type" content="website">
<meta property="og:site_name" content="SmartToolz">
<meta property="og:title" content="Free Online Tools for Images, PDF, Text & More">
<meta property="og:description" content="Free browser-based tools for image, PDF, text, developer, calculator and everyday tasks.">
<meta property="og:url" content="https://smarttoolz.in/">
<meta property="og:image" content="https://smarttoolz.in/assets/home/hero-tools.svg">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="Free Online Tools for Images, PDF, Text & More">
<meta name="twitter:description" content="Simple browser-based utilities for common digital tasks.">
<meta name="twitter:image" content="https://smarttoolz.in/assets/home/hero-tools.svg">
<?php require __DIR__ . '/head.php'; ?>
<script type="application/ld+json">
<?= json_encode([
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'WebSite',
            'name' => 'SmartToolz',
            'url' => 'https://smarttoolz.in/',
            'description' => 'Free browser-based online tools for common digital tasks.'
        ],
        [
            '@type' => 'Organization',
            'name' => 'SmartToolz',
            'url' => 'https://smarttoolz.in/'
        ]
    ]
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) ?>
</script>
</head>
<body>
<?php require __DIR__ . '/header.php'; ?>

<main>
<section class="hero">
  <div class="page">
    <div class="row align-items-center g-5 py-4 py-lg-5">
      <div class="col-lg-7">
        <span class="eyebrow">FREE ONLINE TOOLS • NO INSTALLATION</span>
        <h1 class="display-2 fw-bold mt-3 mb-3">Get everyday digital tasks done <span class="gradient">faster.</span></h1>
        <p class="hero-copy mb-4">SmartToolz brings useful browser-based tools into one clean workspace. Compress images, convert files, format text, work with PDFs, calculate values and create simple outputs without installing desktop software.</p>
        <div class="search-box mb-3">
          <label class="visually-hidden" for="homeToolSearch">Search SmartToolz tools</label>
          <input id="homeToolSearch" type="search" data-tool-search placeholder="Search for a tool, e.g. image compressor or JSON formatter" autocomplete="off">
          <div class="search-results" data-search-results aria-live="polite"></div>
        </div>
        <div class="d-flex flex-wrap gap-2">
          <a class="tool-btn" href="/tools/">Browse All Tools →</a>
          <a class="btn btn-light border rounded-3 px-4" href="#popular">Popular tools</a>
        </div>
        <div class="trust mt-3">
          <span>✓ Browser-based</span><span>✓ Simple interfaces</span><span>✓ Helpful instructions</span><span>✓ Mobile friendly</span>
        </div>
      </div>
      <div class="col-lg-5">
        <figure class="hero-art mb-0">
          <img src="/assets/home/hero-tools.svg" width="800" height="520" alt="SmartToolz dashboard illustration showing a collection of online tools" loading="eager" fetchpriority="high">
        </figure>
      </div>
    </div>
  </div>
</section>

<section class="page py-4" aria-label="SmartToolz highlights">
  <div class="stats">
    <div class="stat"><strong><?= count($tools) ?>+</strong><span>Useful tools</span></div>
    <div class="stat"><strong><?= count($categories) ?></strong><span>Tool categories</span></div>
    <div class="stat"><strong>24/7</strong><span>Available online</span></div>
    <div class="stat"><strong>1 click</strong><span>From browse to tool</span></div>
  </div>
</section>

<section class="page py-5" id="popular">
  <div class="section-head">
    <div><span class="eyebrow">POPULAR TOOLS</span><h2 class="mt-2">Start with the tools people use most</h2><p>Focused utilities for common image, text, developer and everyday tasks.</p></div>
    <a href="/tools/">View all tools →</a>
  </div>
  <div class="tool-grid">
    <?php foreach ($featured as $tool): ?>
      <article class="tool-card">
        <div class="tool-icon" aria-hidden="true"><?= htmlspecialchars((string)$tool['icon'], ENT_QUOTES, 'UTF-8') ?></div>
        <h2><?= htmlspecialchars((string)$tool['name'], ENT_QUOTES, 'UTF-8') ?></h2>
        <p><?= htmlspecialchars((string)$tool['description'], ENT_QUOTES, 'UTF-8') ?></p>
        <a class="tool-link" href="<?= htmlspecialchars((string)$tool['url'], ENT_QUOTES, 'UTF-8') ?>">Open tool →</a>
      </article>
    <?php endforeach; ?>
  </div>
</section>

<section class="section section-alt">
  <div class="page">
    <div class="section-head">
      <div><span class="eyebrow">TOOL CATEGORIES</span><h2 class="mt-2">Find the right tool by category</h2><p>Organized so you can reach a useful utility without digging through unrelated pages.</p></div>
      <a href="/tools/">Browse everything →</a>
    </div>
    <div class="category-grid">
      <?php foreach ($categories as $category => $count): ?>
        <article class="category-card">
          <h3><?= htmlspecialchars((string)$category, ENT_QUOTES, 'UTF-8') ?></h3>
          <p><?= htmlspecialchars($categoryDescriptions[$category] ?? 'Practical browser-based tools for everyday digital work.', ENT_QUOTES, 'UTF-8') ?></p>
          <a href="/tools/?category=<?= rawurlencode(smarttoolz_slug((string)$category)) ?>"><?= (int)$count ?> tools →</a>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="page py-5">
  <div class="info-panel">
    <div>
      <span class="eyebrow">WHY SMARTTOOLZ</span>
      <h2 class="mt-3">Useful tools should feel simple.</h2>
      <p>SmartToolz is built around one clear idea: when you need a quick online utility, the interface should explain itself. Each tool focuses on a specific task, keeps the main controls visible and includes practical guidance for understanding the result.</p>
    </div>
    <div class="check-list">
      <div><b>01</b><span>Clear task-focused interfaces instead of crowded dashboards.</span></div>
      <div><b>02</b><span>Helpful explanations for inputs, outputs and common use cases.</span></div>
      <div><b>03</b><span>Consistent navigation so related tools are easy to discover.</span></div>
      <div><b>04</b><span>Responsive layouts that work across desktop and mobile screens.</span></div>
    </div>
  </div>
</section>

<section class="page pb-5">
  <div class="seo-section">
    <span class="eyebrow">LEARN BEFORE YOU USE</span>
    <h2 class="mt-3">Free online tools for real-world tasks</h2>
    <p>People often need small utilities at exactly the moment a larger application feels unnecessary. A student may need a word count, a developer may need a JSON formatter, a designer may need a color value, or a website owner may need to reduce an image before uploading it. SmartToolz groups these practical jobs in one place so the tool can stay the main focus.</p>
    <div class="row g-4 mt-2">
      <div class="col-md-4"><h3>Work & study</h3><p>Count words, clean text, calculate values and prepare common files without adding another desktop application.</p></div>
      <div class="col-md-4"><h3>Web & development</h3><p>Format JSON, encode URLs, convert text and use small utilities that speed up repetitive web-development tasks.</p></div>
      <div class="col-md-4"><h3>Images & documents</h3><p>Compress, resize and convert images and handle common PDF workflows for sharing, publishing and storage.</p></div>
    </div>
  </div>
</section>

<section class="page pb-5">
  <div class="seo-section">
    <span class="eyebrow">FAQ</span>
    <h2 class="mt-3">Frequently asked questions</h2>
    <div class="faq mt-3">
      <details><summary>Are SmartToolz tools free to use?</summary><p>The public SmartToolz tools are designed as free online utilities. Individual tools may have their own usage limits or browser requirements where technically necessary.</p></details>
      <details><summary>Do I need to install software?</summary><p>Most SmartToolz tools are designed to work directly in a modern web browser, so everyday tasks can be handled without installing a separate desktop application.</p></details>
      <details><summary>Can I use SmartToolz on a phone?</summary><p>Yes. The site uses responsive layouts so the tools can be used on smaller screens as well as desktop browsers.</p></details>
      <details><summary>Where can I see all available tools?</summary><p>Visit the All Tools page to browse the complete collection by category and open each tool from its clean, dedicated URL.</p></details>
    </div>
  </div>
</section>

<section class="page pb-5">
  <div class="cta">
    <span class="eyebrow">READY WHEN YOU ARE</span>
    <h2 class="mt-3">Find a tool and get the job done.</h2>
    <p>Explore the collection and choose the focused utility that matches your task.</p>
    <a class="tool-btn" href="/tools/">Explore All Tools →</a>
  </div>
</section>
</main>

<?php require __DIR__ . '/footer.php'; ?>
</body>
</html>
