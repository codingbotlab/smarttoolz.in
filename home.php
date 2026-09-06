<?php
declare(strict_types=1);

define('SMARTTOOLZ_HOME_REGISTRY', true);
require __DIR__ . '/tool.php';
require __DIR__ . '/header.php';

$tools = is_array($tools ?? null) ? $tools : [];
$popularNames = ['Image Compressor','Image Resizer','JPG to PNG','PNG to JPG','PDF to JPG','Image Cropper','GIF Maker','Meme Generator','Color Picker','JSON Formatter','QR Code Generator','Word Counter'];
$popular = [];
foreach ($popularNames as $wanted) {
    foreach ($tools as $tool) {
        if (strcasecmp((string)($tool['name'] ?? ''), $wanted) === 0) { $popular[] = $tool; break; }
    }
}
if (!$popular) $popular = array_slice($tools, 0, 12);
$categories = [];
foreach ($tools as $tool) {
    $category = trim((string)($tool['category'] ?? 'Other'));
    if ($category === '') $category = 'Other';
    $categories[$category] = ($categories[$category] ?? 0) + 1;
}
ksort($categories);
$categoryIcons = ['Image Tools'=>'image','PDF Tools'=>'picture_as_pdf','Text Tools'=>'text_fields','Developer Tools'=>'code','Calculators'=>'calculate','Utilities'=>'timer','Generators'=>'auto_awesome','Design Tools'=>'palette','Media Tools'=>'perm_media','Security'=>'security','Other'=>'build'];
function st_slug(string $value): string { return trim(strtolower((string)(preg_replace('/[^a-z0-9]+/i','-', $value) ?? '')), '-'); }
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SmartToolz — Free Online Tools for Images, PDF, Text & More</title>
<meta name="description" content="SmartToolz gives you fast, free online tools for images, PDF, text, developer tasks, calculators and everyday work. No signup required.">
<meta name="robots" content="index,follow">
<link rel="canonical" href="https://smarttoolz.in/">
</head>
<body>
<section class="home-hero">
  <div class="page-wrap hero-grid">
    <div>
      <span class="eyebrow"><span class="material-symbols-rounded">bolt</span> FREE ONLINE TOOLS</span>
      <h1>Get things done.<br><span class="gradient-text">Smarter & faster.</span></h1>
      <p class="hero-copy">A clean collection of practical tools for images, PDFs, text, developers and everyday tasks. Open a tool, do the job, and move on.</p>
      <div class="hero-actions">
        <a class="btn btn-primary" href="/tool.php"><span class="material-symbols-rounded">apps</span> Explore all tools</a>
        <a class="btn" href="#popular"><span class="material-symbols-rounded">star</span> Popular tools</a>
      </div>
      <div class="tool-search">
        <input data-tool-search type="search" placeholder="Search 50+ tools…" aria-label="Search SmartToolz tools" autocomplete="off">
        <span class="material-symbols-rounded">search</span>
        <div class="search-results" data-search-results></div>
      </div>
      <div class="trust-row">
        <span class="trust-pill"><span class="material-symbols-rounded">check_circle</span> No signup</span>
        <span class="trust-pill"><span class="material-symbols-rounded">speed</span> Fast & simple</span>
        <span class="trust-pill"><span class="material-symbols-rounded">devices</span> Works in browser</span>
      </div>
    </div>
    <div class="hero-art"><img src="/assets/home/hero-tools.svg" alt="SmartToolz online tools illustration" width="800" height="520"></div>
  </div>
</section>

<div class="stats" aria-label="SmartToolz highlights">
  <div class="stat"><strong><?=count($tools)?>+</strong><small>Tools available</small></div>
  <div class="stat"><strong><?=count($categories)?></strong><small>Tool categories</small></div>
  <div class="stat"><strong>Free</strong><small>Core tools</small></div>
  <div class="stat"><strong>24/7</strong><small>Available online</small></div>
</div>

<section class="section" id="popular">
  <div class="page-wrap">
    <div class="section-head"><div><h2>Popular tools</h2><p>The tools people reach for first.</p></div><a href="/tool.php">View all <span class="material-symbols-rounded">arrow_forward</span></a></div>
    <div class="tool-grid">
      <?php foreach ($popular as $tool): $name=(string)($tool['name']??'Tool'); $url=(string)($tool['url']??'#'); $icon=(string)($tool['icon']??'build'); $cat=(string)($tool['category']??'Tool'); $desc=(string)($tool['description']??''); ?>
      <a class="tool-card" data-tool-card data-search="<?=htmlspecialchars(strtolower($name.' '.$desc.' '.$cat),ENT_QUOTES,'UTF-8')?>" data-url="<?=htmlspecialchars($url,ENT_QUOTES,'UTF-8')?>" data-name="<?=htmlspecialchars($name,ENT_QUOTES,'UTF-8')?>" data-category="<?=htmlspecialchars($cat,ENT_QUOTES,'UTF-8')?>" data-icon="<?=htmlspecialchars($icon,ENT_QUOTES,'UTF-8')?>" href="<?=htmlspecialchars($url,ENT_QUOTES,'UTF-8')?>">
        <span class="tool-icon"><span class="material-symbols-rounded"><?=htmlspecialchars($icon,ENT_QUOTES,'UTF-8')?></span></span><h3><?=htmlspecialchars($name,ENT_QUOTES,'UTF-8')?></h3><p><?=htmlspecialchars($desc,ENT_QUOTES,'UTF-8')?></p><span class="tool-open">Open tool <span class="material-symbols-rounded">arrow_forward</span></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section section-white" id="categories">
  <div class="page-wrap">
    <div class="section-head"><div><h2>Browse by category</h2><p>Jump straight to the kind of tool you need.</p></div><a href="/tool.php">All tools <span class="material-symbols-rounded">arrow_forward</span></a></div>
    <div class="category-grid">
      <?php foreach ($categories as $category=>$count): $icon=$categoryIcons[$category]??'build'; $slug=st_slug($category); ?>
      <div class="category-card"><h3><span class="material-symbols-rounded"><?=htmlspecialchars($icon,ENT_QUOTES,'UTF-8')?></span><?=htmlspecialchars($category,ENT_QUOTES,'UTF-8')?></h3><p><?=$count?> <?=($count===1?'tool':'tools')?> ready to use.</p><a href="/category/<?=htmlspecialchars($slug,ENT_QUOTES,'UTF-8')?>/">Explore category <span class="material-symbols-rounded">arrow_forward</span></a></div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="section">
  <div class="page-wrap">
    <div class="seo-panel">
      <div class="seo-copy"><h2>Simple tools. Zero learning curve.</h2><p>SmartToolz is built around focused utilities that solve one job well. Compress an image, convert a file, clean text, generate a value or format code without installing another app.</p></div>
      <div class="seo-list">
        <div><span class="material-symbols-rounded">check_circle</span><span>Image conversion, compression and editing</span></div>
        <div><span class="material-symbols-rounded">check_circle</span><span>PDF conversion, merging and splitting</span></div>
        <div><span class="material-symbols-rounded">check_circle</span><span>Text and developer utilities</span></div>
        <div><span class="material-symbols-rounded">check_circle</span><span>Calculators, generators and everyday utilities</span></div>
      </div>
    </div>
  </div>
</section>

<section class="section" style="padding-top:0">
  <div class="page-wrap"><div class="cta-box"><h2>Find a tool and finish the task.</h2><p>Browse the complete SmartToolz collection and get straight to the utility you need.</p><a class="btn btn-primary" href="/tool.php"><span class="material-symbols-rounded">apps</span> Browse all tools</a></div></div>
</section>
<?php require __DIR__ . '/footer.php'; ?>
</body>
</html>
