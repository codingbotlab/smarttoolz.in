<?php
declare(strict_types=1);

// Reddott live preview launcher. The recording engine should open the real tool URL
// directly; this page is provided as a human-facing preview/diagnostic page.
$toolUrl = 'https://smarttoolz.in/smart-toolz/tools/qr-generator.php';
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Reddott Live Tool Preview — QR Generator</title>
<style>
body{margin:0;background:#f7f9fd;color:#172033;font-family:system-ui,-apple-system,Segoe UI,sans-serif}.bar{height:64px;background:#fff;border-bottom:1px solid #e5e8ef;display:flex;align-items:center;padding:0 20px;justify-content:space-between}.brand{font-weight:900;font-size:20px}.brand b{color:#635bff}.open{background:#635bff;color:#fff;text-decoration:none;padding:10px 15px;border-radius:10px;font-weight:800;font-size:13px}.wrap{max-width:1280px;margin:24px auto;padding:0 20px}.card{background:#fff;border:1px solid #e3e7ef;border-radius:18px;overflow:hidden}.head{padding:18px 20px;border-bottom:1px solid #e7eaf0}.head strong{display:block;font-size:18px}.head small{color:#6d7788}.frame{width:100%;height:calc(100vh - 180px);min-height:620px;border:0;display:block;background:#fff}
</style>
</head>
<body>
<header class="bar"><div class="brand"><b>Smart</b>Toolz <span style="color:#777;font-weight:700">× Reddott</span></div><a class="open" href="<?= htmlspecialchars($toolUrl, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">Open Real Tool ↗</a></header>
<main class="wrap"><section class="card"><div class="head"><strong>QR Generator — Live Preview</strong><small>The embedded page is the real SmartToolz URL. If browser security policy blocks embedding, use “Open Real Tool”.</small></div><iframe class="frame" src="<?= htmlspecialchars($toolUrl, ENT_QUOTES, 'UTF-8') ?>" title="SmartToolz QR Generator live preview"></iframe></section></main>
</body>
</html>
