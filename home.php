<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';

$tools = smarttoolz_tools();
$categories = smarttoolz_categories();
$popular = [];
$popularSlugs = ['image-compressor','image-resizer','jpg-to-png','png-to-jpg','pdf-to-jpg','image-cropper','gif-maker','meme-generator','color-picker','json-formatter','qr-code-generator','word-counter'];
foreach ($popularSlugs as $slug) {
    foreach ($tools as $tool) {
        if (smarttoolz_slug($tool['name']) === $slug) { $popular[] = $tool; break; }
    }
}
if (!$popular) $popular = array_slice($tools, 0, 12);

define('SMARTTOOLZ_RENDER_SHELL', true);
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>SmartToolz — Free Online Tools for Images, PDF, Text &amp; More</title>
<meta name="description" content="Free online tools for images, PDFs, text, developer tasks, calculators and everyday work. SmartToolz is fast, simple and built for your browser.">
<meta name="robots" content="index,follow,max-image-preview:large"><link rel="canonical" href="https://smarttoolz.in/">
<meta property="og:type" content="website"><meta property="og:site_name" content="SmartToolz"><meta property="og:title" content="SmartToolz — Free Online Tools for Images, PDF, Text &amp; More"><meta property="og:description" content="Free, fast and simple online tools that work in your browser."><meta property="og:url" content="https://smarttoolz.in/"><meta name="twitter:card" content="summary"><meta name="theme-color" content="#635bff">
<?php require __DIR__ . '/head.php'; ?>
</head><body>
<?php require __DIR__ . '/header.php'; ?>
<main>
<section class="hero full-section"><div class="container hero-grid"><div><span class="eyebrow">✦ FREE ONLINE TOOLS</span><h1>Get things done.<br><span class="gradient">Smarter &amp; faster.</span></h1><p class="hero-copy">A clean collection of practical browser tools for images, PDFs, text, developers, calculators and everyday tasks. Open a tool, do the job, move on.</p><div class="hero-actions"><a class="btn btn-primary" href="/tool.php">Explore all tools →</a><a class="btn" href="#popular">Popular tools</a></div><div class="search-box"><input id="homeSearch" type="search" placeholder="Search 50+ tools…" aria-label="Search SmartToolz tools" autocomplete="off"><div class="search-results" id="homeResults"></div></div><div class="trust"><span>✓ No signup</span><span>✓ Fast &amp; simple</span><span>✓ Works in your browser</span></div></div><div class="hero-art" aria-hidden="true"><svg viewBox="0 0 620 430" role="img"><defs><linearGradient id="heroGradient" x1="0" x2="1"><stop offset="0" stop-color="#635bff"/><stop offset="1" stop-color="#3b82f6"/></linearGradient></defs><rect x="35" y="35" width="550" height="360" rx="34" fill="#fff" stroke="#e7eaf0"/><rect x="72" y="76" width="476" height="62" rx="16" fill="#f5f4ff"/><circle cx="105" cy="107" r="14" fill="url(#heroGradient)"/><rect x="133" y="95" width="190" height="12" rx="6" fill="#d8dbe6"/><rect x="133" y="116" width="120" height="8" rx="4" fill="#e6e8ef"/><g fill="#f0efff" stroke="#dedbff"><rect x="72" y="164" width="145" height="92" rx="18"/><rect x="237" y="164" width="145" height="92" rx="18"/><rect x="402" y="164" width="146" height="92" rx="18"/><rect x="72" y="279" width="210" height="78" rx="18"/><rect x="302" y="279" width="246" height="78" rx="18"/></g><g fill="#635bff"><circle cx="102" cy="194" r="12"/><circle cx="267" cy="194" r="12"/><circle cx="432" cy="194" r="12"/></g><g fill="#fff"><rect x="93" y="216" width="83" height="8" rx="4"/><rect x="258" y="216" width="83" height="8" rx="4"/><rect x="423" y="216" width="83" height="8" rx="4"/><rect x="95" y="303" width="145" height="8" rx="4"/><rect x="95" y="320" width="110" height="7" rx="3.5"/><rect x="325" y="303" width="170" height="8" rx="4"/><rect x="325" y="320" width="135" height="7" rx="3.5"/></g></svg></div></div></section>
<div class="stats"><div class="stat"><strong><?= count($tools) ?>+</strong><span>Tools available</span></div><div class="stat"><strong><?= count($categories) ?></strong><span>Tool categories</span></div><div class="stat"><strong>Free</strong><span>Core tools</span></div><div class="stat"><strong>24/7</strong><span>Available online</span></div></div>
<section class="section" id="popular"><div class="container"><div class="section-head"><div><h2>Popular tools</h2><p>The tools people reach for first.</p></div><a href="/tool.php">View all →</a></div><div class="tool-grid"><?php foreach ($popular as $tool): ?><a class="tool-card" href="<?= smarttoolz_escape($tool['url']) ?>"><span class="tool-icon"><?= smarttoolz_escape($tool['icon']) ?></span><h3><?= smarttoolz_escape($tool['name']) ?></h3><p><?= smarttoolz_escape($tool['description']) ?></p><span class="tool-open">Open tool →</span></a><?php endforeach; ?></div></div></section>
<section class="section section-alt" id="categories"><div class="container"><div class="section-head"><div><h2>Browse by category</h2><p>Jump straight to the kind of tool you need.</p></div><a href="/tool.php">All tools →</a></div><div class="category-grid"><?php foreach ($categories as $category=>$count): ?><article class="category-card"><h3><?= smarttoolz_escape($category) ?></h3><p><?= $count ?> <?= $count===1?'tool':'tools' ?> ready to use.</p><a href="/category/<?= smarttoolz_escape(smarttoolz_slug($category)) ?>/">Explore category →</a></article><?php endforeach; ?></div></div></section>
<section class="section"><div class="container"><div class="info-panel"><div><h2>Simple tools. Zero learning curve.</h2><p>SmartToolz focuses on useful single-purpose utilities. Compress an image, convert a file, clean text, format code or calculate a result without installing another app.</p></div><div class="check-list"><div><b>✓</b><span>Image conversion, compression and editing</span></div><div><b>✓</b><span>PDF conversion, merging and splitting</span></div><div><b>✓</b><span>Text and developer utilities</span></div><div><b>✓</b><span>Calculators, generators and everyday tools</span></div></div></div></div></section>
<section class="section" style="padding-top:0"><div class="container"><div class="cta"><h2>Find a tool and finish the task.</h2><p>Browse the complete SmartToolz collection and get straight to the utility you need.</p><a class="btn btn-primary" href="/tool.php">Browse all tools →</a></div></div></section>
</main>
<?php require __DIR__ . '/footer.php'; ?>
<script>const data=<?= json_encode(array_map(static fn(array $t):array=>['name'=>$t['name'],'url'=>$t['url'],'description'=>$t['description']],$tools),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) ?>;const input=document.getElementById('homeSearch'),results=document.getElementById('homeResults');input.addEventListener('input',()=>{const q=input.value.trim().toLowerCase();if(!q){results.style.display='none';results.innerHTML='';return}const matches=data.filter(t=>(t.name+' '+t.description).toLowerCase().includes(q)).slice(0,7);results.innerHTML=matches.map(t=>`<a class="search-result" href="${t.url}"><strong>${t.name}</strong><span>${t.description}</span></a>`).join('')||'<div class="search-result">No matching tool found.</div>';results.style.display='block'});document.addEventListener('click',e=>{if(!e.target.closest('.search-box'))results.style.display='none'});</script>
</body></html>
