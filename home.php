<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/lib/tools.php';
$tools=smarttoolz_tools();$featured=array_slice($tools,0,8);
?><!doctype html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>SmartToolz — Free Online Tools for Everyday Tasks</title>
<meta name="description" content="SmartToolz offers free online tools for image compression, text, developer tasks, calculators, PDFs and more. Fast, simple and easy to use.">
<link rel="canonical" href="https://smarttoolz.in/">
<?php require __DIR__ . '/head.php'; ?>
</head><body><?php require __DIR__ . '/header.php'; ?>
<main>
<section class="page"><div class="row align-items-center g-5 py-5"><div class="col-lg-7"><span class="eyebrow">FREE • FAST • SIMPLE</span><h1 class="display-2 fw-bold mt-3">Smart tools for everyday work.</h1><p class="fs-5 text-secondary">Compress, convert, calculate, format and create with focused browser-based tools designed to get one job done quickly.</p><div class="d-flex flex-wrap gap-2 mt-4"><a class="tool-btn" href="/tools/">Explore All Tools →</a><a class="btn btn-light border rounded-3 px-4" href="#popular">Popular tools</a></div></div><div class="col-lg-5"><img src="/assets/home/hero-tools.svg" class="img-fluid rounded-4" width="800" height="520" alt="Illustration of SmartToolz online tools dashboard" loading="eager"></div></div></section>
<section class="page py-5" id="popular"><div class="d-flex justify-content-between align-items-end mb-4"><div><span class="eyebrow">POPULAR</span><h2 class="mt-2">Useful tools to start with</h2></div><a href="/tools/" class="text-decoration-none fw-bold">See all →</a></div><div class="tool-grid"><?php foreach($featured as $tool): ?><article class="tool-card"><div class="tool-icon"><?= htmlspecialchars($tool['icon']) ?></div><h2><?= htmlspecialchars($tool['name']) ?></h2><p><?= htmlspecialchars($tool['description']) ?></p><a class="tool-link" href="<?= htmlspecialchars($tool['url']) ?>">Open tool →</a></article><?php endforeach; ?></div></section>
<section class="page py-5"><div class="seo-section"><h2>One place for common online tasks</h2><p>SmartToolz focuses on practical utilities that people use while working, studying, building websites or preparing digital files. Each tool has a clear purpose, straightforward controls and helpful guidance so visitors can understand what the tool does before using it.</p><div class="row g-4 mt-1"><div class="col-md-4"><h3>Image tools</h3><p>Handle common image compression, resizing and format conversion tasks.</p></div><div class="col-md-4"><h3>Text and developer tools</h3><p>Format text and structured data, encode content and simplify common development workflows.</p></div><div class="col-md-4"><h3>Calculators and utilities</h3><p>Use simple calculators and everyday utilities without installing extra software.</p></div></div></div></section>
<section class="page py-5"><div class="seo-section"><h2>Built for a clear, useful experience</h2><p>Tools should be easy to understand and quick to use. SmartToolz keeps interfaces focused, explains important inputs and outputs, and provides related tools through consistent navigation.</p></div></section>
</main><?php require __DIR__ . '/footer.php'; ?></body></html>
