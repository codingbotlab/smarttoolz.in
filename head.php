<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';

// Version query strings prevent browsers/CDNs from serving older cached styles after deployment.
$assetVersion = '2026090702';
?>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9562924671441246" crossorigin="anonymous"></script>
<link rel="preconnect" href="https://cdn.jsdelivr.net">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<link rel="stylesheet" href="/assets/css/smarttoolz.css?v=<?= $assetVersion ?>">
<link rel="stylesheet" href="/assets/css/site-polish.css?v=<?= $assetVersion ?>">
<link rel="stylesheet" href="/assets/css/tool-pages.css?v=<?= $assetVersion ?>">
<link rel="stylesheet" href="/assets/css/tools.css?v=<?= $assetVersion ?>">
