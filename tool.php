<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';

$tools = smarttoolz_tools();
$categories = smarttoolz_categories();
$requestedCategory = trim((string)($_GET['category'] ?? ''));
$visible = array_values(array_filter($tools, static fn(array $tool): bool => $requestedCategory === '' || smarttoolz_slug($tool['category']) === $requestedCategory));
$heading = $requestedCategory === '' ? 'All Tools' : ucwords(str_replace('-', ' ', $requestedCategory));
$description = $requestedCategory === '' ? 'Browse every SmartToolz utility in one fast, searchable collection.' : 'Browse free ' . $heading . ' on SmartToolz.';
$canonical = $requestedCategory === '' ? 'https://smarttoolz.in/tool.php' : 'https://smarttoolz.in/category/' . smarttoolz_slug($heading) . '/';
define('SMARTTOOLZ_RENDER_SHELL', true);
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= smarttoolz_escape($heading . ' — Free Online Tools | SmartToolz') ?></title>
<meta name="description" content="<?= smarttoolz_escape($description) ?>">
<meta name="robots" content="index,follow,max-image-preview:large"><link rel="canonical" href="<?= smarttoolz_escape($canonical) ?>">
<meta property="og:type" content="website"><meta property="og:site_name" content="SmartToolz"><meta property="og:title" content="<?= smarttoolz_escape($heading . ' — Free Online Tools | SmartToolz') ?>"><meta property="og:description" content="<?= smarttoolz_escape($description) ?>"><meta property="og:url" content="<?= smarttoolz_escape($canonical) ?>"><meta name="twitter:card" content="summary"><meta name="theme-color" content="#635bff">
<?php require __DIR__ . '/head.php'; ?>
</head><body>
<?php require __DIR__ . '/header.php'; ?>
<main class="tools-page"><div class="container"><section class="tools-hero"><span class="tag">SMARTTOOLZ COLLECTION</span><h1><?= smarttoolz_escape($heading) ?></h1><p><?= count($visible) ?> free tools ready to use — search, filter and get to work.</p></section>
<div class="tools-layout" id="categories"><aside class="filters" aria-label="Tool categories"><h2>Categories</h2><a class="filter <?= $requestedCategory===''?'active':'' ?>" href="/tool.php">All Tools <span class="count"><?= count($tools) ?></span></a><?php foreach($categories as $category=>$count):$slug=smarttoolz_slug($category);?><a class="filter <?= $requestedCategory===$slug?'active':'' ?>" href="/category/<?= smarttoolz_escape($slug) ?>/"><?= smarttoolz_escape($category) ?><span class="count"><?= $count ?></span></a><?php endforeach;?></aside>
<section><input id="toolSearch" class="tool-search" type="search" placeholder="Search tools by name or description…" aria-label="Search tools"><div id="toolGrid" class="tool-grid"><?php foreach($visible as $tool):?><a class="tool-card" href="<?= smarttoolz_escape($tool['url']) ?>" data-search="<?= smarttoolz_escape(strtolower($tool['name'].' '.$tool['description'].' '.$tool['category'])) ?>"><span class="tool-icon"><?= smarttoolz_escape($tool['icon']) ?></span><h3><?= smarttoolz_escape($tool['name']) ?></h3><p><?= smarttoolz_escape($tool['description']) ?></p><span class="tool-open">Open tool →</span></a><?php endforeach;?></div><div id="empty" class="empty" hidden>No tools match your search.</div></section></div></div></main>
<?php require __DIR__ . '/footer.php'; ?>
<script>const input=document.getElementById('toolSearch'),cards=[...document.querySelectorAll('#toolGrid .tool-card')],empty=document.getElementById('empty');input.addEventListener('input',()=>{const q=input.value.trim().toLowerCase();let shown=0;for(const card of cards){const ok=!q||card.dataset.search.includes(q);card.hidden=!ok;if(ok)shown++}empty.hidden=shown!==0});</script>
</body></html>
