<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/header.php';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Image Compressor — SmartToolz</title>
<meta name="description" content="Compress JPG, PNG and WebP images online for free with SmartToolz. Fast browser-based image compression.">
<meta name="robots" content="index,follow">
<style>
*{box-sizing:border-box}body{margin:0;background:#f6f8fc;color:#172033;font-family:Inter,system-ui,Arial,sans-serif}.page{width:min(1100px,calc(100% - 28px));margin:32px auto 70px}.hero,.card,.info{background:#fff;border:1px solid #e5e9f0;border-radius:20px;box-shadow:0 14px 40px rgba(20,30,70,.05)}.hero{padding:30px;margin-bottom:18px}.badge{display:inline-block;padding:6px 10px;border-radius:999px;background:#eeedff;color:#635bff;font-size:10px;font-weight:900}.hero h1{margin:10px 0 6px;font-size:clamp(32px,5vw,48px);letter-spacing:-1.5px}.muted{color:#707b8e;line-height:1.65}.card{padding:26px}.upload{padding:55px 20px;border:2px dashed #d7dbea;border-radius:18px;background:#fafbff;text-align:center;cursor:pointer}.upload:hover,.upload.dragover{border-color:#635bff;background:#f5f3ff}.upload-icon{font-size:34px;margin-bottom:10px}.upload h2{margin:0 0 6px;font-size:20px}.upload p{margin:0;color:#707b8e;font-size:13px}.file-info{margin-top:10px;color:#635bff;font-weight:700;font-size:12px}#fileInput{display:none}.settings{display:none;margin-top:18px;padding:18px;background:#fafbff;border:1px solid #e5e9f0;border-radius:15px}.setting-head{display:flex;justify-content:space-between;gap:10px}.setting-head strong{font-size:14px}.quality-value{color:#635bff;font-weight:800}#quality{width:100%;accent-color:#635bff}.actions{display:flex;gap:9px;flex-wrap:wrap;margin-top:18px}.btn{border:0;border-radius:10px;padding:11px 16px;background:#635bff;color:#fff;font-weight:800;cursor:pointer}.btn.secondary{background:#edf0f5;color:#344055}.error{display:none;margin-top:14px;padding:11px 13px;border-radius:10px;background:#fff0f0;color:#b4232d;font-size:12px}.result{display:none;margin-top:22px}.previews{display:grid;grid-template-columns:1fr 1fr;gap:14px}.preview{padding:13px;background:#fafbff;border:1px solid #e5e9f0;border-radius:15px}.preview h3{margin:0 0 9px;font-size:13px}.preview img{display:block;width:100%;max-height:380px;object-fit:contain;border-radius:10px;background:#fff}.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:12px}.stat{padding:12px;text-align:center;background:#f6f7fb;border-radius:11px}.stat strong{display:block;color:#635bff}.stat span{font-size:10px;color:#707b8e}.info{margin-top:18px;padding:22px}.info h2{margin:0 0 7px;font-size:20px}.info p{margin:0;color:#707b8e;font-size:13px;line-height:1.7}@media(max-width:650px){.page{width:calc(100% - 18px)}.card,.hero{padding:20px}.previews{grid-template-columns:1fr}.stats{grid-template-columns:1fr}}
</style>
</head>
<body>
<main class="page">
<section class="hero"><span class="badge">SMARTTOOLZ • FREE ONLINE TOOL</span><h1>Image Compressor</h1><p class="muted">Compress JPG, PNG and WebP images directly in your browser. No login, usage counter or download tracking.</p></section>
<section class="card">
<div class="upload" id="uploadArea"><div class="upload-icon">🖼️</div><h2>Drop an image here</h2><p>or click to choose a JPG, PNG or WebP image</p><div class="file-info" id="fileInfo"></div><input id="fileInput" type="file" accept="image/jpeg,image/png,image/webp"></div>
<div class="settings" id="settings"><div class="setting-head"><strong>Compression quality</strong><span class="quality-value" id="qualityValue">80%</span></div><input id="quality" type="range" min="10" max="100" value="80"><div class="actions"><button class="btn" id="compressBtn" type="button">Compress Image</button><button class="btn secondary" id="resetBtn" type="button">Reset</button></div></div>
<div class="error" id="error"></div>
<div class="result" id="result"><div class="previews"><div class="preview"><h3>Original</h3><img id="originalPreview" alt="Original image"></div><div class="preview"><h3>Compressed</h3><img id="compressedPreview" alt="Compressed image"></div></div><div class="stats"><div class="stat"><strong id="originalSize">—</strong><span>Original</span></div><div class="stat"><strong id="compressedSize">—</strong><span>Compressed</span></div><div class="stat"><strong id="savedSize">—</strong><span>Saved</span></div></div><div class="actions"><a class="btn" id="downloadBtn" href="#">Download JPG</a></div></div>
</section>
<section class="info"><h2>Private browser processing</h2><p>Your selected image is processed locally by your browser. SmartToolz does not send analytics, usage or download-tracking requests for this tool.</p></section>
</main>
<script src="/assets/tools/image-compressor.js"></script>
<?php require_once __DIR__ . '/../footer.php'; ?>
</body>
</html>
