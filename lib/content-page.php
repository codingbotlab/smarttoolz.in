<?php
declare(strict_types=1);
require_once __DIR__ . '/../bootstrap.php';

function smarttoolz_render_content_page(string $title, string $description, string $bodyHtml): void
{
    define('SMARTTOOLZ_RENDER_SHELL', true);
    ?><!doctype html><html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= smarttoolz_escape($title) ?> | SmartToolz</title>
<meta name="description" content="<?= smarttoolz_escape($description) ?>"><meta name="robots" content="index,follow">
<link rel="canonical" href="<?= smarttoolz_escape('https://smarttoolz.in' . (parse_url((string)($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH) ?: '/')) ?>">
<link rel="stylesheet" href="/assets/css/smarttoolz.css">
<style>.content-page{padding:56px 0 80px}.content-hero{padding:38px 42px;margin-bottom:22px;border:1px solid #ddd9ff;border-radius:24px;background:linear-gradient(135deg,#fff,#f3f2ff 58%,#edf4ff)}.content-hero h1{margin:13px 0 7px;font-size:42px;letter-spacing:-2px}.content-hero p{margin:0;color:#667085;font-size:13px}.content-body{background:#fff;border:1px solid var(--line);border-radius:20px;padding:34px;box-shadow:var(--shadow)}.content-body h2{margin:26px 0 8px;font-size:19px}.content-body h2:first-child{margin-top:0}.content-body p,.content-body li{color:#526078;font-size:13px;line-height:1.8}.content-body ul{padding-left:20px}.content-body a{color:var(--brand);text-decoration:underline}@media(max-width:560px){.content-page{padding:38px 0 60px}.content-hero{padding:28px 21px}.content-hero h1{font-size:34px}.content-body{padding:24px 19px}}
</style></head><body><?php require __DIR__ . '/../header.php'; ?><main class="content-page"><div class="container"><section class="content-hero"><span class="tag">SMARTTOOLZ</span><h1><?= smarttoolz_escape($title) ?></h1><p><?= smarttoolz_escape($description) ?></p></section><article class="content-body"><?= $bodyHtml ?></article></div></main><?php require __DIR__ . '/../footer.php'; ?></body></html><?php
}
