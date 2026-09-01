<?php
declare(strict_types=1);

// Knowledge Base landing page. Generated guides are linked here automatically.
$root = __DIR__;
$entries = [];

foreach (glob($root . '/*', GLOB_ONLYDIR) ?: [] as $dir) {
    $slug = basename($dir);
    if ($slug === '_generated' || str_starts_with($slug, '.')) {
        continue;
    }
    $article = $dir . '/article/index.php';
    if (is_file($article)) {
        $entries[] = $slug;
    }
}
sort($entries, SORT_NATURAL | SORT_FLAG_CASE);
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SmartToolz How-to Knowledge Base</title>
<meta name="description" content="Step-by-step how-to guides, images and videos for SmartToolz.">
<style>
body{font-family:system-ui,-apple-system,Segoe UI,sans-serif;margin:0;background:#f7f8fc;color:#172033}main{max-width:1100px;margin:auto;padding:48px 20px}h1{margin:0 0 10px;font-size:36px}p{color:#657084}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:16px;margin-top:28px}.card{display:block;padding:22px;background:#fff;border:1px solid #e5e8ef;border-radius:16px;text-decoration:none;color:inherit}.card:hover{box-shadow:0 10px 28px rgba(20,30,60,.08)}.empty{padding:24px;background:#fff;border-radius:16px;border:1px dashed #ccd2df}
</style>
</head>
<body><main>
<h1>SmartToolz How-to Guides</h1>
<p>Learn how to use SmartToolz tools with step-by-step instructions, images and videos.</p>
<?php if (!$entries): ?>
<div class="empty">Guides are being prepared. Generated guides will appear here automatically.</div>
<?php else: ?>
<div class="grid">
<?php foreach ($entries as $slug): ?>
<a class="card" href="<?= htmlspecialchars('/knowledge-base/' . rawurlencode($slug) . '/article/', ENT_QUOTES, 'UTF-8') ?>"><strong><?= htmlspecialchars(ucwords(str_replace('-', ' ', $slug)), ENT_QUOTES, 'UTF-8') ?></strong><br><small>Open how-to guide →</small></a>
<?php endforeach; ?>
</div>
<?php endif; ?>
</main></body></html>
