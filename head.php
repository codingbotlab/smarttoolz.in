<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';

// Version query strings prevent browsers/CDNs from serving an older cached stylesheet after deployment.
$assetVersion = '20260907';
?>
<link rel="preconnect" href="https://cdn.jsdelivr.net">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<link rel="stylesheet" href="/assets/css/smarttoolz.css?v=<?= $assetVersion ?>">
<link rel="stylesheet" href="/assets/css/tool-pages.css?v=<?= $assetVersion ?>">
