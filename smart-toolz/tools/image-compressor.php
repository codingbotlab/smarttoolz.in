<?php
declare(strict_types=1);
ini_set('display_errors','1');
ini_set('display_startup_errors','1');
error_reporting(E_ALL);
require_once $_SERVER['DOCUMENT_ROOT'].'/analytics/tracker.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/creator-ai/auth/config.php';
$pdo=db();
$ads=[];
try{$q=$pdo->query('SELECT ad_key,enabled,ad_code FROM ads_settings');while($r=$q->fetch(PDO::FETCH_ASSOC))$ads[(string)$r['ad_key']]=['enabled'=>(int)$r['enabled'],'ad_code'=>(string)($r['ad_code']??'');}catch(Throwable $e){}
function smartToozAd(string $key):void{global $ads;if(!isset($ads[$key])||$ads[$key]['enabled']!==1)return;$code=trim($ads[$key]['ad_code']);if($code!=='')echo $code;}
smartToozAd('popunder');smartToozAd('socialbar');
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Image Compressor - Smart-Tooz</title>
<meta name="description" content="Compress JPG, JPEG, PNG and WebP images online for free with Smart-Tooz.">
<meta name="robots" content="index,follow">
<style>
*{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;font-family:Inter,-apple-system,BlinkMacSystemFont,"Segoe UI",Arial,sans-serif;background:#f6f8fc;color:#172033;line-height:1.5}a{text-decoration:none;color:inherit}button,input{font:inherit}.page-layout{width:min(1400px,calc(100% - 30px));margin:28px auto 60px;display:flex;gap:24px;align-items:flex-start}.tool-content{min-width:0;flex:1}.tools-sidebar{order:2}.ad-slot{width:100%;min-height:10px;margin:0 auto 24px;padding:5px;display:flex;align-items:center;justify-content:center;text-align:center;overflow:hidden}.ad-slot iframe{max-width:100%!important;border:0}.ad-slot img{max-width:100%;height:auto}.desktop-ad{display:flex;width:100%;justify-content:center}.mobile-ad{display:none;width:100%;justify-content:center}.tool-header{margin-bottom:22px;padding:34px 25px;background:#fff;border:1px solid #e5e9f0;border-radius:22px;text-align:center;box-shadow:0 8px 30px rgba(30,35,80,.035)}.tool-badge{display:inline-block;margin-bottom:12px;padding:7px 13px;border-radius:50px;background:#eeedff;color:#635bff;font-size:12px;font-weight:800}.tool-header h1{margin:0;font-size:clamp(32px,5vw,48px);line-height:1.08;letter-spacing:-1.8px}.tool-header h1 span{color:#635bff}.tool-header p{max-width:650px;margin:13px auto 0;color:#707b8e;font-size:14px}.compressor-card{background:#fff;border:1px solid #e5e9f0;border-radius:22px;padding:28px;box-shadow:0 15px 45px rgba(30,35,80,.06)}.upload-area{width:100%;padding:55px 20px;border:2px dashed #d7dbea;border-radius:19px;background:#fafbff;text-align:center;cursor:pointer;transition:.2s}.upload-area:hover,.upload-area.dragover{border-color:#635bff;background:#f0eeff}.upload-icon{width:66px;height:66px;display:grid;place-items:center;margin:0 auto 15px;border-radius:18px;background:#eeedff;color:#635bff;font-size:30px}.upload-area h2{margin:0 0 6px;font-size:20px}.upload-area p{color:#707b8e;font-size:13px;margin:0}.file-info{margin-top:10px!important;color:#635bff!important;font-weight:700}#fileInput{display:none}.settings{display:none;margin-top:22px;padding:20px;background:#fafbff;border:1px solid #e5e9f0;border-radius:17px}.setting-header{display:flex;align-items:center;justify-content:space-between;gap:15px}.setting-title{font-size:14px;font-weight:800}.setting-description{margin-top:3px;color:#707b8e;font-size:12px}.quality-value{color:#635bff;font-size:17px;font-weight:800}#quality{width:100%;margin-top:15px;accent-color:#635bff}.actions{display:flex;justify-content:center;align-items:center;flex-wrap:wrap;gap:10px;margin-top:20px}.btn{display:inline-flex;align-items:center;justify-content:center;min-height:44px;padding:11px 18px;border:0;border-radius:12px;cursor:pointer;font-size:13px;font-weight:800}.btn-primary{background:#635bff;color:#fff}.btn-secondary{background:#eef0f5;color:#3f4858}.btn:disabled{opacity:.6;cursor:not-allowed}.error{display:none;margin-top:15px;padding:12px 14px;border-radius:11px;background:#fff0f0;color:#c33;font-size:13px;text-align:center}.result{display:none;margin-top:28px}.preview-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px}.preview{padding:14px;border:1px solid #e5e9f0;border-radius:17px;background:#fafbff}.preview h3{margin:0 0 10px;font-size:14px}.preview img{display:block;width:100%;max-height:390px;object-fit:contain;border-radius:11px;background:#fff}.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:16px}.stat{padding:15px;text-align:center;background:#f6f7fb;border-radius:13px}.stat strong{display:block;color:#635bff;font-size:19px}.stat span{color:#707b8e;font-size:11px}.info-box{margin-top:24px;padding:25px;background:#fff;border:1px solid #e5e9f0;border-radius:19px}.info-box h2{margin:0 0 9px;font-size:21px}.info-box p{margin:0;color:#707b8e;font-size:13px;line-height:1.7}footer{padding:35px 20px;background:#151827;color:#fff;text-align:center}footer p{margin:6px 0 0;color:#aeb5c5;font-size:12px}@media(max-width:700px){.page-layout{width:calc(100% - 20px);margin:15px auto 40px;flex-direction:column;gap:18px}.tool-content{width:100%;order:1}.tools-sidebar{width:100%;order:2}.tool-header{padding:28px 18px}.tool-header h1{font-size:36px}.compressor-card{padding:17px;border-radius:18px}.upload-area{padding:43px 15px}.preview-grid,.stats{grid-template-columns:1fr}.desktop-ad{display:none!important}.mobile-ad{display:flex!important}.actions{flex-direction:column}.actions .btn{width:100%}}
</style>
</head>
<body>
<?php require_once dirname(__DIR__) . '/header.php'; ?>
<?php require_once dirname(__DIR__).'/header.php'; ?>
<div class="page-layout">
<main class="tool-content">
<div class="ad-slot"><div class="desktop-ad"><?php smartToozAd('728x90'); ?></div><div class="mobile-ad"><?php smartToozAd('320x50'); ?></div></div>
<section class="tool-header"><div class="tool-badge">🖼️ Image Tool</div><h1>Image <span>Compressor</span></h1><p>Compress JPG, JPEG, PNG and WebP images directly in your browser while keeping excellent image quality.</p></section>
<section class="compressor-card">
<div class="upload-area" id="uploadArea"><div class="upload-icon">⬆</div><h2>Drop your image here</h2><p>or click to choose an image</p><p style="margin-top:8px;font-size:12px">JPG, JPEG, PNG, WebP • Maximum 20 MB</p><p class="file-info" id="fileInfo"></p></div>
<input type="file" id="fileInput" accept="image/jpeg,image/png,image/webp">
<div class="settings" id="settings"><div class="setting-header"><div><div class="setting-title">Compression Quality</div><div class="setting-description">Lower quality creates a smaller file.</div></div><div class="quality-value" id="qualityValue">80%</div></div><input type="range" id="quality" min="10" max="100" value="80"><div class="actions"><button type="button" class="btn btn-primary" id="compressBtn">Compress Image</button><button type="button" class="btn btn-secondary" id="resetBtn">Reset</button></div></div>
<div class="error" id="error"></div>
<div class="result" id="result"><div class="preview-grid"><div class="preview"><h3>Original Image</h3><img id="originalPreview" alt="Original image preview"></div><div class="preview"><h3>Compressed Image</h3><img id="compressedPreview" alt="Compressed image preview"></div></div><div class="stats"><div class="stat"><strong id="originalSize">-</strong><span>Original Size</span></div><div class="stat"><strong id="compressedSize">-</strong><span>Compressed Size</span></div><div class="stat"><strong id="savedSize">-</strong><span>Reduction</span></div></div><div class="actions"><a href="#" id="downloadBtn" class="btn btn-primary" download>⬇ Download Compressed Image</a></div></div>
</section>
<div class="ad-slot"><?php smartToozAd('native'); ?></div><div class="ad-slot"><?php smartToozAd('300x250'); ?></div>
<section class="info-box"><h2>Free Online Image Compressor</h2><p>Smart-Tooz Image Compressor helps reduce image file sizes quickly. Images are processed directly in your browser, so the selected image does not need to be uploaded to the server. Choose the compression quality, compress your image and download the optimized result.</p></section>
<div class="ad-slot"><div class="desktop-ad"><?php smartToozAd('468x60'); ?></div><div class="mobile-ad"><?php smartToozAd('320x50'); ?></div></div>
</main>
<?php include __DIR__.'/tool-sidebar.php'; ?>
</div>
<footer><strong>Smart-Tooz</strong><p>Free, fast and simple online tools.</p></footer>
<script src="/smart-toolz/assets/tools/image-compressor.js?v=20260901-1" defer></script>
</body>
</html>
