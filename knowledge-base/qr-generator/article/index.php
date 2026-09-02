<?php
declare(strict_types=1);

$toolUrl = '/smart-toolz/tools/qr-generator.php';
$metaPath = rtrim((string)($_SERVER['DOCUMENT_ROOT'] ?? ''), '/') . '/Reddott-films/knowledge-videos/qr-generator/published/youtube.json';
$youtubeId = '';
if (is_file($metaPath)) {
    $meta = json_decode((string)file_get_contents($metaPath), true);
    if (is_array($meta) && !empty($meta['video_id'])) {
        $youtubeId = (string)$meta['video_id'];
    }
}
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>How to Use QR Code Generator — SmartToolz</title>
<meta name="description" content="Learn how to create a QR code with SmartToolz QR Code Generator. Follow the video and step-by-step guide.">
<link rel="canonical" href="https://smarttoolz.in/knowledge-base/qr-generator/article/">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
<style>
*{box-sizing:border-box}body{margin:0;font-family:Inter,system-ui,-apple-system,Segoe UI,sans-serif;background:#f7f9fd;color:#172033;line-height:1.6}.fa-solid{display:inline-block;width:1em;text-align:center}.top{position:sticky;top:0;z-index:5;background:#fff;border-bottom:1px solid #e6e9f0}.nav{max-width:1180px;margin:auto;min-height:68px;padding:0 20px;display:flex;align-items:center;justify-content:space-between;gap:20px}.brand{font-weight:900;font-size:20px;text-decoration:none;color:#172033;display:flex;align-items:center;gap:8px}.brand b{color:#635bff}.links{display:flex;gap:18px;font-size:13px;font-weight:700;color:#596477}.links a{text-decoration:none;color:inherit;display:flex;align-items:center;gap:5px}.wrap{max-width:980px;margin:36px auto 70px;padding:0 20px}.hero{background:#fff;border:1px solid #e4e8f0;border-radius:22px;padding:34px;margin-bottom:24px}.badge{display:inline-flex;align-items:center;gap:7px;background:#eeedff;color:#635bff;padding:6px 11px;border-radius:999px;font-size:11px;font-weight:900}.hero h1{font-size:clamp(30px,5vw,48px);line-height:1.1;letter-spacing:-1.5px;margin:14px 0 8px}.hero p{color:#697487;margin:0}.video{background:#111827;border-radius:20px;overflow:hidden;box-shadow:0 18px 50px #17203318;margin:24px 0;aspect-ratio:16/9}.video iframe{display:block;width:100%;height:100%;border:0}.pending{height:100%;display:grid;place-items:center;text-align:center;padding:30px;color:#cbd5e1}.pending strong{display:block;color:#fff;font-size:20px;margin-bottom:6px}.content{background:#fff;border:1px solid #e4e8f0;border-radius:20px;padding:30px}.content h2{font-size:25px;margin:28px 0 10px;display:flex;align-items:center;gap:9px}.content h2:first-child{margin-top:0}.content p,.content li{color:#5f6b7d}.step{padding:16px 18px;margin:12px 0;border:1px solid #e8ebf1;border-radius:14px;background:#fafbfe}.step strong{color:#635bff;display:flex;align-items:center;gap:8px}.step strong i{font-size:14px}.cta{display:inline-flex;align-items:center;gap:8px;margin-top:18px;background:#635bff;color:#fff;padding:12px 18px;border-radius:12px;text-decoration:none;font-weight:800}footer{text-align:center;padding:30px;color:#7a8494;font-size:12px}
@media(max-width:700px){.links{display:none}.nav{min-height:60px}.hero,.content{padding:22px}.wrap{margin-top:20px}}
</style>
</head>
<body>
<header class="top"><nav class="nav"><a class="brand" href="/"><i class="fa-solid fa-screwdriver-wrench" aria-hidden="true"></i><span><b>Smart</b>Toolz</span></a><div class="links"><a href="/"><i class="fa-solid fa-house" aria-hidden="true"></i>Home</a><a href="/smart-toolz/"><i class="fa-solid fa-table-cells-large" aria-hidden="true"></i>All Tools</a><a href="/knowledge-base/"><i class="fa-solid fa-book" aria-hidden="true"></i>Knowledge Base</a><a href="/Reddott-films/blogs/"><i class="fa-solid fa-video" aria-hidden="true"></i>Video Blog</a></div></nav></header>
<main class="wrap">
<section class="hero"><span class="badge"><i class="fa-solid fa-qrcode" aria-hidden="true"></i>SMARTTOOLZ HOW-TO</span><h1>How to Use the QR Code Generator</h1><p>Create a QR code from your content, review the result, and test it before sharing or printing.</p></section>
<section class="video">
<?php if ($youtubeId !== ''): ?>
<iframe src="https://www.youtube.com/embed/<?= htmlspecialchars($youtubeId, ENT_QUOTES, 'UTF-8') ?>" title="How to Create a QR Code with SmartToolz" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
<?php else: ?>
<div class="pending"><div><strong><i class="fa-solid fa-spinner" aria-hidden="true"></i> Video is being generated</strong>The real SmartToolz tutorial will appear here automatically after the Reddott video pipeline publishes it to YouTube.</div></div>
<?php endif; ?>
</section>
<article class="content">
<h2><i class="fa-solid fa-circle-info" aria-hidden="true"></i>What is the SmartToolz QR Code Generator?</h2>
<p>The QR Code Generator lets you create a QR code from content entered into the tool. This guide shows the practical workflow from opening the tool through generating and checking the result.</p>
<h2><i class="fa-solid fa-list-check" aria-hidden="true"></i>How to create a QR code</h2>
<div class="step"><strong><i class="fa-solid fa-1" aria-hidden="true"></i>Step 1 — Open the tool</strong><br>Open the SmartToolz QR Code Generator and locate the main content input.</div>
<div class="step"><strong><i class="fa-solid fa-2" aria-hidden="true"></i>Step 2 — Enter your content</strong><br>Type or paste the text or URL you want to encode into the QR code.</div>
<div class="step"><strong><i class="fa-solid fa-3" aria-hidden="true"></i>Step 3 — Choose your options</strong><br>Review the available generator options and select the settings you need.</div>
<div class="step"><strong><i class="fa-solid fa-4" aria-hidden="true"></i>Step 4 — Generate</strong><br>Start the QR generation process and wait for the result preview.</div>
<div class="step"><strong><i class="fa-solid fa-5" aria-hidden="true"></i>Step 5 — Check the result</strong><br>Inspect the generated QR code and test it with a phone camera before publishing.</div>
<h2><i class="fa-solid fa-lightbulb" aria-hidden="true"></i>Best practices</h2>
<ul><li>Always test the generated QR code before distributing it.</li><li>Keep the code clear and large enough for the intended scanning distance.</li><li>Use real destination content and verify that the destination works.</li></ul>
<h2><i class="fa-solid fa-circle-question" aria-hidden="true"></i>Frequently asked questions</h2>
<p><strong>Can I use a URL?</strong><br>Yes. A URL can be entered as the content to encode.</p>
<p><strong>Should I test the QR code?</strong><br>Yes. Testing with a phone camera helps confirm that the final code scans correctly.</p>
<a class="cta" href="<?= htmlspecialchars($toolUrl,ENT_QUOTES,'UTF-8') ?>"><i class="fa-solid fa-qrcode" aria-hidden="true"></i>Open QR Code Generator <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></a>
</article>
</main>
<footer><i class="fa-solid fa-book-open" aria-hidden="true"></i> SmartToolz Knowledge Base · Practical guides for SmartToolz tools</footer>
</body></html>
