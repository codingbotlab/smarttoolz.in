<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'] . '/analytics/tracker.php';

function h(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$input = '';
$output = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = (string)($_POST['input'] ?? '');
    if ($input === '') {
        $error = 'Please enter a URL or encoded text.';
    } else {
        $output = rawurldecode($input);
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>URL Decoder - Decode URL Online | SmartToolz</title>
    <meta name="description" content="Decode URL encoded text online with SmartToolz URL Decoder. Fast, free and easy to use.">
    <style>
        *{box-sizing:border-box}body{margin:0;font-family:Arial,sans-serif;background:#f5f7fb;color:#172033}.wrap{max-width:900px;margin:40px auto;padding:20px}.card{background:#fff;border:1px solid #e5e7eb;border-radius:16px;padding:28px;box-shadow:0 8px 30px rgba(0,0,0,.06)}h1{margin:0 0 10px;font-size:32px}p{color:#64748b;line-height:1.6}label{display:block;font-weight:700;margin:20px 0 8px}textarea{width:100%;min-height:180px;padding:14px;border:1px solid #cbd5e1;border-radius:10px;resize:vertical;font:inherit}button{margin-top:16px;padding:12px 22px;border:0;border-radius:9px;background:#111827;color:#fff;font-weight:700;cursor:pointer}.error{margin-top:15px;padding:12px;border-radius:8px;background:#fee2e2;color:#991b1b}.result{margin-top:22px}.result textarea{background:#f8fafc}.links{margin-top:28px}.links a{color:#2563eb;text-decoration:none}
    </style>
</head>
<body>
<?php require_once dirname(__DIR__) . '/header.php'; ?>
<div class="wrap">
    <div class="card">
        <h1>URL Decoder</h1>
        <p>Decode percent-encoded URLs and text instantly. Your input is processed directly by this tool.</p>
        <form method="post">
            <label for="input">Encoded URL or text</label>
            <textarea id="input" name="input" placeholder="Example: https%3A%2F%2Fexample.com%2Fhello%20world"><?= h($input) ?></textarea>
            <button type="submit">Decode URL</button>
        </form>
        <?php if ($error !== ''): ?>
            <div class="error"><?= h($error) ?></div>
        <?php endif; ?>
        <?php if ($output !== ''): ?>
            <div class="result">
                <label for="output">Decoded result</label>
                <textarea id="output" readonly><?= h($output) ?></textarea>
            </div>
        <?php endif; ?>
        <div class="links"><a href="/smart-toolz/">← Back to SmartToolz</a></div>
    </div>
</div>
<style id="smarttoolz-global-sidebar-css">.smarttoolz-global-sidebar{position:fixed;right:18px;top:88px;width:280px;max-height:calc(100vh - 108px);overflow:auto;z-index:9990;background:#fff;border:1px solid #e5e9f0;border-radius:18px;padding:14px;box-shadow:0 18px 50px rgba(20,30,70,.12)}.st-sidebar-head{display:flex;justify-content:space-between;align-items:center;padding:4px 7px 10px}.st-sidebar-head button{border:0;background:transparent;font-size:24px;cursor:pointer;color:#69758a}.st-sidebar-list a{display:block;padding:9px 10px;margin:2px 0;border-radius:9px;color:#536075;text-decoration:none;font:700 13px/1.25 Inter,system-ui,Arial,sans-serif}.st-sidebar-list a:hover,.st-sidebar-list a.active{background:#f0efff;color:#635bff}.st-sidebar-list .st-all{margin-top:9px;border-top:1px solid #e9ecf2;padding-top:13px}.smarttoolz-tools-toggle{display:none;position:fixed;right:14px;bottom:18px;z-index:9991;border:0;border-radius:12px;background:#635bff;color:#fff;padding:11px 14px;font-weight:800;box-shadow:0 10px 30px rgba(30,30,90,.2)}@media(max-width:900px){.smarttoolz-global-sidebar{right:12px;top:76px;width:min(310px,calc(100vw - 24px));max-height:calc(100vh - 94px);display:none}.smarttoolz-global-sidebar.open{display:block}.smarttoolz-tools-toggle{display:block}}body.smarttoolz-sidebar-page{padding-right:315px}@media(max-width:900px){body.smarttoolz-sidebar-page{padding-right:0}}</style><aside id="smarttoolz-global-sidebar" class="smarttoolz-global-sidebar" aria-label="All Tools"><div class="st-sidebar-head"><strong>All Tools</strong><button type="button" aria-label="Close tools">×</button></div><div class="st-sidebar-list"><a href="/smart-toolz/tools/image-compressor.php">Image Compressor</a><a href="/smart-toolz/tools/png-to-jpg.php">PNG to JPG</a><a href="/smart-toolz/tools/jpg-to-webp.php">JPG to WebP</a><a href="/smart-toolz/tools/png-to-webp.php">PNG to WebP</a><a href="/smart-toolz/tools/gif-maker.php">GIF Maker</a><a href="/smart-toolz/tools/gif-to-jpg.php">GIF to JPG</a><a href="/smart-toolz/tools/jpg-to-png.php">JPG to PNG</a><a href="/smart-toolz/tools/pdf-to-jpg.php">PDF to JPG</a><a href="/smart-toolz/tools/pdf-to-png.php">PDF to PNG</a><a href="/smart-toolz/tools/pdf-merger.php">PDF Merger</a><a href="/smart-toolz/tools/pdf-splitter.php">PDF Splitter</a><a href="/smart-toolz/tools/pdf-compressor.php">PDF Compressor</a><a href="/smart-toolz/tools/qr-generator.php">QR Generator</a><a href="/smart-toolz/tools/qr-reader.php">QR Reader</a><a href="/smart-toolz/tools/age-calculator.php">Age Calculator</a><a href="/smart-toolz/tools/percentage-calculator.php">Percentage Calculator</a><a href="/smart-toolz/tools/bmi-calculator.php">BMI Calculator</a><a href="/smart-toolz/tools/json-formatter.php">JSON Formatter</a><a href="/smart-toolz/tools/word-counter.php">Word Counter</a><a href="/smart-toolz/tools/case-converter.php">Case Converter</a><a href="/smart-toolz/tools/base64-encoder.php">Base64 Encoder</a><a href="/smart-toolz/tools/url-encoder.php">URL Encoder</a><a class="st-all" href="/smart-toolz/tool.php">View All Tools →</a></div></aside><button id="smarttoolz-tools-toggle" class="smarttoolz-tools-toggle" type="button">☰ Tools</button><script>(function(){const s=document.getElementById('smarttoolz-global-sidebar');if(!s)return;const current=location.pathname.split('/').pop();s.querySelectorAll('a').forEach(a=>{if(a.pathname.split('/').pop()===current)a.classList.add('active')});const toggle=document.getElementById('smarttoolz-tools-toggle'),close=s.querySelector('button');toggle?.addEventListener('click',()=>s.classList.toggle('open'));close?.addEventListener('click',()=>s.classList.remove('open'));if(window.innerWidth>900)document.body.classList.add('smarttoolz-sidebar-page')})();</script>
</body>
</html>
