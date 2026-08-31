<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

/* ============================================================
   ANALYTICS TRACKER
============================================================ */
require_once $_SERVER['DOCUMENT_ROOT'] . '/analytics/tracker.php';

/* ============================================================
   DATABASE / ADS
============================================================ */
require_once $_SERVER['DOCUMENT_ROOT'] . '/creator-ai/auth/config.php';

$pdo = db();
$smartToozAds = [];

try {
    $stmt = $pdo->query("SELECT ad_key, enabled, ad_code FROM ads_settings");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $smartToozAds[(string)$row['ad_key']] = [
            'enabled' => (int)($row['enabled'] ?? 0),
            'ad_code' => (string)($row['ad_code'] ?? '')
        ];
    }
} catch (Throwable $e) {
    $smartToozAds = [];
}

function smartToozAd(string $adKey): void
{
    global $smartToozAds;
    if (!isset($smartToozAds[$adKey])) return;
    if ((int)$smartToozAds[$adKey]['enabled'] !== 1) return;
    $code = trim((string)$smartToozAds[$adKey]['ad_code']);
    if ($code !== '') echo $code;
}

smartToozAd('popunder');
smartToozAd('socialbar');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PNG to WebP Converter - Smart-Tooz</title>
<meta name="description" content="Convert PNG images to WebP online for free. Fast browser-based PNG to WebP converter with quality control and instant download.">
<meta name="robots" content="index, follow">

<style>
*{margin:0;padding:0;box-sizing:border-box}
html{scroll-behavior:smooth}
body{font-family:Inter,-apple-system,BlinkMacSystemFont,"Segoe UI",Arial,sans-serif;background:#f6f8fc;color:#172033;line-height:1.5}
a{text-decoration:none;color:inherit}button,input{font-family:inherit}
.site-header{position:sticky;top:0;z-index:1000;background:rgba(255,255,255,.96);backdrop-filter:blur(14px);border-bottom:1px solid #e5e9f0}
.navbar{width:calc(100% - 20px);max-width:1400px;min-height:72px;margin:auto;display:flex;align-items:center;justify-content:space-between}
.logo{display:flex;align-items:center;gap:10px;color:#172033;font-size:21px;font-weight:800}
.logo-icon{width:42px;height:42px;display:grid;place-items:center;border-radius:13px;color:white;background:linear-gradient(135deg,#635bff,#916cff);box-shadow:0 8px 22px rgba(99,91,255,.18)}
.nav-links{display:flex;align-items:center;gap:28px}.nav-links a{color:#596477;font-size:14px;font-weight:600}.nav-links a:hover{color:#635bff}
.menu-button{display:none;border:0;background:transparent;font-size:27px;cursor:pointer}
.page-layout{width:min(1400px,calc(100% - 30px));margin:28px auto 60px;display:flex;flex-direction:row;align-items:flex-start;gap:24px}.tool-content{min-width:0;flex:1;order:1}.page-layout>.tools-sidebar{order:2}
.ad-slot{width:100%;min-height:10px;margin:0 auto 24px;padding:5px;display:flex;align-items:center;justify-content:center;text-align:center;overflow:hidden}.ad-slot iframe{display:block;max-width:100%!important;border:0}.ad-slot img{max-width:100%;height:auto}.desktop-ad{display:flex;width:100%;justify-content:center;align-items:center}.mobile-ad{display:none;width:100%;justify-content:center;align-items:center}
.tool-header{margin-bottom:22px;padding:34px 25px;background:#fff;border:1px solid #e5e9f0;border-radius:22px;text-align:center;box-shadow:0 8px 30px rgba(30,35,80,.035)}
.tool-badge{display:inline-block;margin-bottom:12px;padding:7px 13px;border-radius:50px;background:#eeedff;color:#635bff;font-size:12px;font-weight:800}.tool-header h1{font-size:clamp(32px,5vw,48px);line-height:1.08;letter-spacing:-1.8px}.tool-header h1 span{color:#635bff}.tool-header p{max-width:700px;margin:13px auto 0;color:#707b8e;font-size:14px}
.tool-card{padding:22px;background:#fff;border:1px solid #e5e9f0;border-radius:22px;box-shadow:0 15px 45px rgba(30,35,80,.06)}
.upload-area{min-height:210px;padding:30px 20px;display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;border:2px dashed #d8dce6;border-radius:18px;background:#fafbff;cursor:pointer;transition:border-color .2s,background .2s}.upload-area:hover,.upload-area.dragover{border-color:#635bff;background:#f5f3ff}
.upload-icon{width:62px;height:62px;display:grid;place-items:center;margin-bottom:13px;border-radius:17px;background:#eeedff;color:#635bff;font-size:28px;font-weight:900}.upload-area h3{margin-bottom:5px;font-size:18px}.upload-area p{color:#8a94a5;font-size:12px}#fileInput{display:none}
.editor{display:none;margin-top:20px}.preview{width:100%;min-height:390px;padding:20px;display:flex;align-items:center;justify-content:center;border-radius:17px;background:#151827;overflow:hidden}#previewImage{display:block;max-width:100%;max-height:520px;object-fit:contain;border-radius:5px}
.file-info{display:flex;align-items:center;justify-content:center;flex-wrap:wrap;gap:10px;margin-top:14px;color:#707b8e;font-size:12px;text-align:center}.file-info strong{color:#3f4858}
.quality-box{margin-top:17px;padding:16px;border:1px solid #e5e9f0;border-radius:14px;background:#fafbff}.quality-top{display:flex;justify-content:space-between;align-items:center;margin-bottom:9px}.quality-top label{color:#596477;font-size:12px;font-weight:800}#qualityValue{color:#635bff;font-size:12px;font-weight:900}#quality{width:100%;accent-color:#635bff}
.actions{display:flex;justify-content:center;flex-wrap:wrap;gap:10px;margin-top:18px}.btn{min-height:45px;padding:10px 19px;border:0;border-radius:11px;cursor:pointer;font-size:12px;font-weight:800;transition:background .2s,transform .2s}.btn:hover{transform:translateY(-1px)}.btn-primary{background:#635bff;color:#fff}.btn-primary:hover{background:#5148e8}.btn-secondary{background:#eef0f5;color:#3f4858}.btn-secondary:hover{background:#e4e7ed}.result-info{margin-top:12px;text-align:center;color:#707b8e;font-size:11px}
.info-box{margin-top:24px;padding:25px;background:#fff;border:1px solid #e5e9f0;border-radius:19px}.info-box h2{margin-bottom:9px;font-size:21px}.info-box p{color:#707b8e;font-size:13px;line-height:1.7}.info-box ul{margin:12px 0 0 20px;color:#707b8e;font-size:13px;line-height:1.8}
footer{padding:35px 20px;background:#151827;color:#fff;text-align:center}footer p{margin-top:6px;color:#aeb5c5;font-size:12px}

@media(max-width:700px){
.navbar{min-height:64px}.nav-links{display:none;position:absolute;top:64px;left:0;right:0;padding:16px;flex-direction:column;background:#fff;border-bottom:1px solid #e5e9f0}.nav-links.open{display:flex}.menu-button{display:block}
.page-layout{width:calc(100% - 20px);margin:15px auto 40px;flex-direction:column;gap:18px}.tool-content{width:100%;order:1}.page-layout>.tools-sidebar{width:100%;order:2;position:relative;top:auto;max-height:none;margin:0}.tool-header{padding:28px 18px}.tool-header h1{font-size:36px}.tool-card{padding:15px;border-radius:18px}.preview{min-height:290px;padding:10px}.desktop-ad{display:none}.mobile-ad{display:flex}
}
</style>
</head>
<body>

<header class="site-header">
    <nav class="navbar">
        <a class="logo" href="/smart-toolz/">
            <span class="logo-icon">🛠️</span>
            <span>Smart-Tooz</span>
        </a>
        <div class="nav-links" id="navLinks">
            <a href="/smart-toolz/">Home</a>
            <a href="/smart-toolz/tool.php">All Tools</a>
        </div>
        <button class="menu-button" id="menuButton" type="button" aria-label="Open menu">☰</button>
    </nav>
</header>

<main class="page-layout">
    <section class="tool-content">

        <div class="ad-slot desktop-ad">
            <?php smartToozAd('top_banner'); ?>
        </div>
        <div class="ad-slot mobile-ad">
            <?php smartToozAd('mobile_banner'); ?>
        </div>

        <section class="tool-header">
            <div class="tool-badge">IMAGE TOOL</div>
            <h1>PNG to <span>WebP</span> Converter</h1>
            <p>Convert PNG images to WebP online for free. Adjust quality and download your optimized WebP image instantly.</p>
        </section>

        <section class="tool-card">
            <div class="upload-area" id="uploadArea">
                <div class="upload-icon">↥</div>
                <h3>Drop your PNG image here</h3>
                <p>or click to browse • PNG files only</p>
                <input id="fileInput" type="file" accept="image/png,.png">
            </div>

            <div class="editor" id="editor">
                <div class="preview">
                    <img id="previewImage" alt="PNG preview">
                </div>

                <div class="file-info">
                    <span>File: <strong id="fileName">-</strong></span>
                    <span>Original: <strong id="originalSize">-</strong></span>
                </div>

                <div class="quality-box">
                    <div class="quality-top">
                        <label for="quality">WebP Quality</label>
                        <span id="qualityValue">85%</span>
                    </div>
                    <input id="quality" type="range" min="10" max="100" value="85">
                </div>

                <div class="actions">
                    <button class="btn btn-primary" id="convertButton" type="button">Convert &amp; Download</button>
                    <button class="btn btn-secondary" id="resetButton" type="button">Choose Another</button>
                </div>
                <div class="result-info" id="resultInfo"></div>
            </div>
        </section>

        <div class="ad-slot desktop-ad">
            <?php smartToozAd('in_content'); ?>
        </div>
        <div class="ad-slot mobile-ad">
            <?php smartToozAd('mobile_in_content'); ?>
        </div>

        <section class="info-box">
            <h2>How to Convert PNG to WebP</h2>
            <p>Smart-Tooz converts your PNG image directly in your browser using the Canvas API. Your original image does not need to be uploaded to a server for conversion.</p>
            <ul>
                <li>Choose a PNG image or drag it into the upload area.</li>
                <li>Adjust the WebP quality using the quality slider.</li>
                <li>Click Convert &amp; Download to save the WebP file.</li>
            </ul>
        </section>

        <section class="info-box">
            <h2>Why Convert PNG to WebP?</h2>
            <p>WebP can provide smaller image files while maintaining good visual quality, which can help reduce page weight and improve loading performance.</p>
        </section>
    </section>

    <?php require_once __DIR__ . '/tool-sidebar.php'; ?>
</main>

<footer>
    <strong>Smart-Tooz</strong>
    <p>Free online tools made simple, fast and useful.</p>
</footer>

<script>
const fileInput = document.getElementById('fileInput');
const uploadArea = document.getElementById('uploadArea');
const editor = document.getElementById('editor');
const previewImage = document.getElementById('previewImage');
const fileName = document.getElementById('fileName');
const originalSize = document.getElementById('originalSize');
const quality = document.getElementById('quality');
const qualityValue = document.getElementById('qualityValue');
const convertButton = document.getElementById('convertButton');
const resetButton = document.getElementById('resetButton');
const resultInfo = document.getElementById('resultInfo');
const menuButton = document.getElementById('menuButton');
const navLinks = document.getElementById('navLinks');

let selectedFile = null;
let previewUrl = null;

function formatBytes(bytes) {
    if (!bytes) return '0 B';
    const units = ['B','KB','MB','GB'];
    const index = Math.min(Math.floor(Math.log(bytes) / Math.log(1024)), units.length - 1);
    return (bytes / Math.pow(1024, index)).toFixed(index ? 2 : 0) + ' ' + units[index];
}

function selectFile(file) {
    if (!file) return;
    if (file.type !== 'image/png') {
        alert('Please select a PNG image.');
        return;
    }
    selectedFile = file;
    if (previewUrl) URL.revokeObjectURL(previewUrl);
    previewUrl = URL.createObjectURL(file);
    previewImage.src = previewUrl;
    fileName.textContent = file.name;
    originalSize.textContent = formatBytes(file.size);
    resultInfo.textContent = '';
    uploadArea.style.display = 'none';
    editor.style.display = 'block';
}

uploadArea.addEventListener('click', () => fileInput.click());
fileInput.addEventListener('change', () => selectFile(fileInput.files[0]));

['dragenter','dragover'].forEach(eventName => {
    uploadArea.addEventListener(eventName, e => {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });
});
['dragleave','drop'].forEach(eventName => {
    uploadArea.addEventListener(eventName, e => {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
    });
});
uploadArea.addEventListener('drop', e => selectFile(e.dataTransfer.files[0]));

quality.addEventListener('input', () => {
    qualityValue.textContent = quality.value + '%';
});

convertButton.addEventListener('click', () => {
    if (!selectedFile) return;
    convertButton.disabled = true;
    convertButton.textContent = 'Converting...';
    resultInfo.textContent = '';

    const img = new Image();
    img.onload = () => {
        const canvas = document.createElement('canvas');
        canvas.width = img.naturalWidth;
        canvas.height = img.naturalHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0);

        canvas.toBlob(blob => {
            if (!blob) {
                resultInfo.textContent = 'Conversion failed. Your browser may not support WebP.';
                convertButton.disabled = false;
                convertButton.textContent = 'Convert & Download';
                return;
            }

            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            const baseName = selectedFile.name.replace(/\.png$/i, '') || 'converted-image';
            link.href = url;
            link.download = baseName + '.webp';
            document.body.appendChild(link);
            link.click();
            link.remove();
            setTimeout(() => URL.revokeObjectURL(url), 1000);

            resultInfo.textContent = 'Converted WebP size: ' + formatBytes(blob.size);
            convertButton.disabled = false;
            convertButton.textContent = 'Convert & Download';
        }, 'image/webp', Number(quality.value) / 100);
    };
    img.onerror = () => {
        resultInfo.textContent = 'Unable to read this PNG image.';
        convertButton.disabled = false;
        convertButton.textContent = 'Convert & Download';
    };
    img.src = previewUrl;
});

resetButton.addEventListener('click', () => {
    selectedFile = null;
    fileInput.value = '';
    if (previewUrl) {
        URL.revokeObjectURL(previewUrl);
        previewUrl = null;
    }
    previewImage.removeAttribute('src');
    editor.style.display = 'none';
    uploadArea.style.display = 'flex';
    resultInfo.textContent = '';
});

menuButton.addEventListener('click', () => navLinks.classList.toggle('open'));
</script>
</body>
</html>
