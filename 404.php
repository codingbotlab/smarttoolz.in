<?php
declare(strict_types=1);
http_response_code(404);
require_once __DIR__ . '/header.php';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Page Not Found — SmartToolz</title>
<meta name="description" content="The SmartToolz page you requested could not be found. Browse our free online tools instead.">
<meta name="robots" content="noindex,follow">
<link rel="canonical" href="https://smarttoolz.in/404.php">
</head>
<body>
<main class="legal-page">
<section class="legal-hero"><span class="tag">404</span><h1>Page not found</h1><p>The page may have moved or the URL may be incorrect.</p></section>
<article class="legal-content" style="text-align:center">
<p>Use the links below to continue exploring SmartToolz.</p>
<p><a class="btn btn-primary" href="/tool.php">Browse all tools</a> <a class="btn" href="/">Go home</a></p>
</article>
</main>
</body>
</html>
<?php require_once __DIR__ . '/footer.php'; ?>
