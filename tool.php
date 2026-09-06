<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/lib/tools.php';
$tools = smarttoolz_tools();
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>All Online Tools — Free Tools for Everyday Tasks | SmartToolz</title>
<meta name="description" content="Explore free SmartToolz online tools for images, text, developer tasks, calculators, generators, security and PDFs. Fast, simple and browser-based.">
<link rel="canonical" href="https://smarttoolz.in/tools/">
<?php require __DIR__ . '/head.php'; ?>
</head><body>
<?php require __DIR__ . '/header.php'; ?>
<main class="page">
<section class="page-hero"><span class="eyebrow">SMARTTOOLZ • FREE ONLINE TOOLS</span><h1>All Tools</h1><p>Useful browser-based tools for common image, text, PDF, developer and everyday tasks. Choose a tool and get started instantly.</p></section>
<section aria-label="Tool categories">
<div class="mb-4"><input id="toolSearch" class="form-control form-control-lg" type="search" placeholder="Search tools..." aria-label="Search tools"></div>
<div class="tool-grid" id="toolGrid">
<?php foreach($tools as $tool): ?>
<article class="tool-card" data-tool data-name="<?= htmlspecialchars(strtolower($tool['name'].' '.$tool['category'])) ?>">
<div class="tool-icon"><?= htmlspecialchars($tool['icon']) ?></div><h2><?= htmlspecialchars($tool['name']) ?></h2><p><?= htmlspecialchars($tool['description']) ?></p><a class="tool-link" href="<?= htmlspecialchars($tool['url']) ?>">Open tool →</a>
</article>
<?php endforeach; ?>
</div>
</section>
<section class="seo-section"><h2>Free Online Tools in One Place</h2><p>SmartToolz brings focused online utilities together so you can complete everyday tasks without installing desktop software for every small job. Tools are designed around one clear task and run directly in the browser whenever practical.</p><div class="row g-3"><div class="col-md-6"><h3>Image and file tools</h3><p>Compress, resize and convert common image formats, or work with PDF files through simple web interfaces.</p></div><div class="col-md-6"><h3>Text and developer tools</h3><p>Format JSON, transform text, generate QR codes and handle common coding or content workflows quickly.</p></div></div></section>
</main>
<script>document.getElementById('toolSearch').addEventListener('input',e=>{const q=e.target.value.trim().toLowerCase();document.querySelectorAll('[data-tool]').forEach(c=>{c.hidden=q!==''&&!c.dataset.name.includes(q)})});</script>
<?php require __DIR__ . '/footer.php'; ?>
</body></html>
