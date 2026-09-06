<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/header.php';
?>
<!doctype html>
<html lang="en">
<head>
<?php require_once dirname(__DIR__) . '/head.php'; ?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Free Image Resizer Online — Resize JPG, PNG & WebP | SmartToolz</title>
<meta name="description" content="Resize JPG, PNG and WebP images online for free. Set exact width and height, keep the aspect ratio and download the resized image directly in your browser with SmartToolz.">
<meta name="robots" content="index,follow,max-image-preview:large">
<link rel="canonical" href="https://smarttoolz.in/tools/image-resizer/">
<meta property="og:type" content="website">
<meta property="og:site_name" content="SmartToolz">
<meta property="og:title" content="Free Image Resizer Online — SmartToolz">
<meta property="og:description" content="Resize JPG, PNG and WebP images online for free with exact dimensions and browser-based processing.">
<meta property="og:url" content="https://smarttoolz.in/tools/image-resizer/">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Free Image Resizer Online — SmartToolz">
<meta name="twitter:description" content="Resize JPG, PNG and WebP images online for free directly in your browser.">
<meta name="theme-color" content="#635bff">
<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"WebApplication",
  "name":"Image Resizer - SmartToolz",
  "url":"https://smarttoolz.in/tools/image-resizer/",
  "description":"Resize JPG, PNG and WebP images online for free with browser-based processing.",
  "applicationCategory":"MultimediaApplication",
  "operatingSystem":"Any",
  "offers":{"@type":"Offer","price":"0","priceCurrency":"USD"}
}
</script>
<style>
:root{--brand:#635bff;--brand-dark:#5148e8;--ink:#172033;--muted:#69758a;--line:#e3e7ef;--soft:#f7f8fc;--green:#087443;--danger:#b4232d}
*{box-sizing:border-box}
body{margin:0;background:var(--soft);color:var(--ink);font-family:Inter,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}
.resizer-page{width:min(1100px,calc(100% - 28px));margin:30px auto 70px}
.hero{text-align:center;max-width:800px;margin:0 auto 24px;padding:20px 8px}
.eyebrow{display:inline-flex;align-items:center;padding:7px 12px;border:1px solid #ddd9ff;border-radius:999px;background:#efedff;color:var(--brand);font-size:10px;font-weight:900;letter-spacing:1px}
.hero h1{margin:14px 0 9px;font-size:clamp(32px,5vw,52px);line-height:1.08;letter-spacing:-2px}
.hero p{margin:0 auto;color:var(--muted);font-size:14px;line-height:1.75;max-width:690px}
.tool-shell{background:#fff;border:1px solid var(--line);border-radius:24px;box-shadow:0 18px 55px rgba(23,32,51,.07);overflow:hidden}
.tool-head{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:18px 22px;border-bottom:1px solid var(--line)}
.tool-name{display:flex;align-items:center;gap:11px;font-weight:900;font-size:14px}.tool-icon{width:40px;height:40px;display:grid;place-items:center;border-radius:12px;background:#efedff;color:var(--brand);font-size:19px}
.privacy{font-size:11px;color:var(--muted)}
.workspace{padding:24px}
.dropzone{min-height:310px;border:2px dashed #cfd4e3;border-radius:20px;background:linear-gradient(180deg,#fbfcff,#f7f8fc);display:flex;align-items:center;justify-content:center;text-align:center;cursor:pointer;transition:.2s}
.dropzone:hover,.dropzone.dragover{border-color:var(--brand);background:#f4f2ff;transform:translateY(-1px)}
.drop-inner{padding:28px 18px}.upload-icon{width:72px;height:72px;margin:0 auto 16px;border-radius:21px;background:#efedff;color:var(--brand);display:grid;place-items:center;font-size:32px}
.dropzone h2{margin:0 0 7px;font-size:21px;letter-spacing:-.4px}.dropzone p{margin:0;color:var(--muted);font-size:13px}.formats{display:inline-flex;margin-top:14px;padding:6px 10px;border:1px solid var(--line);border-radius:8px;background:#fff;color:#7c8798;font-size:10px;font-weight:800}
#fileInput{display:none}
.editor{display:none;margin-top:18px}.file-bar{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:13px 15px;border:1px solid var(--line);background:var(--soft);border-radius:13px}.file-name{font-size:13px;font-weight:850;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.file-size{font-size:11px;color:var(--muted);white-space:nowrap}
.controls{margin-top:15px;padding:20px;border:1px solid var(--line);border-radius:17px;background:#fff}.controls h2{margin:0 0 16px;font-size:16px}
.dimensions{display:grid;grid-template-columns:1fr 1fr;gap:13px}.field label{display:block;margin-bottom:7px;font-size:12px;font-weight:800;color:#4b5668}.field input,.field select{width:100%;height:46px;padding:0 12px;border:1px solid #dfe3ea;border-radius:10px;background:#fff;color:var(--ink);font-size:13px;outline:none}.field input:focus,.field select:focus{border-color:var(--brand);box-shadow:0 0 0 3px rgba(99,91,255,.09)}
.lock{display:flex;align-items:center;gap:8px;margin-top:15px;font-size:12px;color:#566174;font-weight:700}.lock input{width:17px;height:17px;accent-color:var(--brand)}
.format-row{margin-top:15px}.hint{margin:7px 0 0;color:#8a94a5;font-size:10px}
.actions{display:flex;flex-wrap:wrap;gap:10px;margin-top:18px}.btn{display:inline-flex;align-items:center;justify-content:center;min-height:45px;padding:11px 18px;border:0;border-radius:11px;font-size:13px;font-weight:850;cursor:pointer;text-decoration:none;transition:.2s}.primary{background:var(--brand);color:#fff;box-shadow:0 9px 22px rgba(99,91,255,.18)}.primary:hover{background:var(--brand-dark);transform:translateY(-1px)}.secondary{background:#edf0f5;color:#344054}.btn:disabled{opacity:.55;cursor:not-allowed;transform:none}
.error{display:none;margin-top:13px;padding:11px 13px;border-radius:10px;background:#fff0f0;color:var(--danger);font-size:12px}
.result{display:none;margin-top:20px;padding-top:20px;border-top:1px solid var(--line)}.result-head{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:13px}.result-head h2{margin:0;font-size:17px}.badge{padding:6px 9px;border-radius:999px;background:#eaf8f0;color:var(--green);font-size:10px;font-weight:900}
.preview-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}.preview{padding:12px;border:1px solid var(--line);border-radius:15px;background:#fafbff}.preview h3{margin:0 0 9px;font-size:12px}.preview-box{min-height:260px;display:flex;align-items:center;justify-content:center;border-radius:10px;background:#fff;overflow:hidden}.preview-box img{display:block;max-width:100%;max-height:400px;object-fit:contain}
.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:13px}.stat{padding:12px;background:var(--soft);border-radius:11px;text-align:center}.stat strong{display:block;color:var(--brand);font-size:13px}.stat span{font-size:10px;color:var(--muted)}.result-actions{justify-content:center}
.features{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:16px}.feature,.content-card{background:#fff;border:1px solid var(--line);border-radius:16px;padding:18px}.feature-icon{font-size:20px}.feature h3{margin:8px 0 5px;font-size:13px}.feature p{margin:0;color:var(--muted);font-size:11.5px;line-height:1.6}
.content-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:16px}.content-card h2{margin:0 0 9px;font-size:18px}.content-card p,.content-card li{color:#5f6b7e;font-size:12.5px;line-height:1.75}.content-card ol,.content-card ul{padding-left:20px;margin-bottom:0}.faq{margin-top:16px}.faq details{border-top:1px solid var(--line);padding:13px 0}.faq details:last-child{border-bottom:1px solid var(--line)}.faq summary{cursor:pointer;font-size:12.5px;font-weight:850}.faq p{margin:8px 0 0;color:var(--muted);font-size:12px;line-height:1.7}
@media(max-width:700px){.resizer-page{width:calc(100% - 16px);margin-top:20px}.workspace{padding:14px}.tool-head{padding:15px 16px}.privacy{display:none}.dimensions,.preview-grid,.features,.content-grid{grid-template-columns:1fr}.dropzone{min-height:260px}.stats{grid-template-columns:1fr 1fr 1fr}.file-bar{align-items:flex-start;flex-direction:column;gap:4px}.hero h1{letter-spacing:-1.2px}.btn{width:100%}}
</style>
</head>
<body>
<?php require_once dirname(__DIR__) . '/header.php'; ?>

<main class="resizer-page">
<section class="hero">
<span class="eyebrow">FREE • NO SIGNUP • BROWSER BASED</span>
<h1>Resize Images Online</h1>
<p>Resize JPG, PNG and WebP images to exact dimensions in seconds. Keep the aspect ratio, preview the result and download the resized image directly from your browser.</p>
</section>

<section class="tool-shell" aria-label="Image resizer tool">
<header class="tool-head"><div class="tool-name"><span class="tool-icon" aria-hidden="true">↔</span>Image Resizer</div><div class="privacy">🔒 Your image stays in your browser</div></header>
<div class="workspace">
<div class="dropzone" id="dropzone" tabindex="0" role="button" aria-label="Choose an image to resize">
<div class="drop-inner"><div class="upload-icon" aria-hidden="true">↑</div><h2>Drop an image here</h2><p>or click to choose a file from your device</p><span class="formats">JPG • PNG • WebP</span><input id="fileInput" type="file" accept="image/jpeg,image/png,image/webp"></div>
</div>

<div class="editor" id="editor">
<div class="file-bar"><span class="file-name" id="fileName">Image</span><span class="file-size" id="fileSize">—</span></div>
<div class="controls">
<h2>Choose your new size</h2>
<div class="dimensions">
<div class="field"><label for="width">Width (px)</label><input id="width" type="number" min="1" max="10000" inputmode="numeric"></div>
<div class="field"><label for="height">Height (px)</label><input id="height" type="number" min="1" max="10000" inputmode="numeric"></div>
</div>
<label class="lock"><input id="keepRatio" type="checkbox" checked> Keep aspect ratio</label>
<div class="format-row field"><label for="outputFormat">Output format</label><select id="outputFormat"><option value="original">Keep original format where possible</option><option value="jpeg">JPG</option><option value="png">PNG</option><option value="webp">WebP</option></select><p class="hint">Choose WebP or JPG when you need a smaller web-friendly file.</p></div>
<div class="actions"><button class="btn primary" id="resizeBtn" type="button">↔ Resize Image</button><button class="btn secondary" id="resetBtn" type="button">Choose Another</button></div>
<div class="error" id="error" role="alert"></div>
</div>

<section class="result" id="result" aria-live="polite">
<div class="result-head"><h2>Resized image</h2><span class="badge" id="resultBadge">Ready</span></div>
<div class="preview-grid">
<div class="preview"><h3>Original</h3><div class="preview-box"><img id="originalPreview" alt="Original image preview"></div></div>
<div class="preview"><h3>Resized</h3><div class="preview-box"><img id="resizedPreview" alt="Resized image preview"></div></div>
</div>
<div class="stats"><div class="stat"><strong id="originalDimensions">—</strong><span>Original dimensions</span></div><div class="stat"><strong id="newDimensions">—</strong><span>New dimensions</span></div><div class="stat"><strong id="outputType">—</strong><span>Output format</span></div></div>
<div class="actions result-actions"><a class="btn primary" id="downloadBtn" href="#" download="smarttoolz-resized-image.jpg">⬇ Download Resized Image</a></div>
</section>
</div>
</div>
</section>

<section class="features" aria-label="Image resizer benefits">
<div class="feature"><div class="feature-icon">⚡</div><h3>Simple controls</h3><p>Enter the exact width and height you need without complicated editing software.</p></div>
<div class="feature"><div class="feature-icon">🔒</div><h3>Browser-based</h3><p>The selected image is resized locally in your browser for this tool.</p></div>
<div class="feature"><div class="feature-icon">📐</div><h3>Keep proportions</h3><p>Lock the aspect ratio to avoid stretching or squashing your image.</p></div>
</section>

<section class="content-grid">
<article class="content-card"><h2>How to resize an image</h2><ol><li>Choose or drag a JPG, PNG or WebP image into the upload box.</li><li>Enter the new width and height in pixels.</li><li>Keep the aspect ratio enabled when you want to preserve the image proportions.</li><li>Click <strong>Resize Image</strong>, preview the result and download it.</li></ol></article>
<article class="content-card"><h2>When to resize images</h2><p>Resizing can help prepare images for websites, social posts, documents, email attachments and profile pictures. Pick dimensions that match the destination instead of stretching the original manually.</p><ul><li>Website and blog images</li><li>Social media graphics</li><li>Profile and thumbnail images</li><li>Documents and presentations</li></ul></article>
</section>

<section class="content-card faq" aria-labelledby="faq-title">
<h2 id="faq-title">Frequently Asked Questions</h2>
<details><summary>Is this image resizer free?</summary><p>Yes. SmartToolz Image Resizer is available to use for free without creating an account.</p></details>
<details><summary>Which image formats are supported?</summary><p>You can upload JPG, PNG and WebP images. You can also choose JPG, PNG or WebP as the output format.</p></details>
<details><summary>Will my image keep its quality?</summary><p>Resizing changes the pixel dimensions. Increasing dimensions cannot create missing detail, while reducing dimensions generally creates a smaller image with less detail than the original.</p></details>
<details><summary>Can I keep the original aspect ratio?</summary><p>Yes. Keep aspect ratio is enabled by default and updates the second dimension when you change the first.</p></details>
</section>
</main>
<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"FAQPage",
  "mainEntity":[
    {"@type":"Question","name":"Is this image resizer free?","acceptedAnswer":{"@type":"Answer","text":"Yes. SmartToolz Image Resizer is available to use for free without creating an account."}},
    {"@type":"Question","name":"Which image formats are supported?","acceptedAnswer":{"@type":"Answer","text":"You can upload JPG, PNG and WebP images and choose JPG, PNG or WebP as the output format."}},
    {"@type":"Question","name":"Can I keep the original aspect ratio?","acceptedAnswer":{"@type":"Answer","text":"Yes. Keep aspect ratio is enabled by default and updates the second dimension when you change the first."}}
  ]
}
</script>
<script>
(function(){
const $=id=>document.getElementById(id);
const dropzone=$('dropzone'),input=$('fileInput'),editor=$('editor'),fileName=$('fileName'),fileSize=$('fileSize'),width=$('width'),height=$('height'),keepRatio=$('keepRatio'),format=$('outputFormat'),resizeBtn=$('resizeBtn'),resetBtn=$('resetBtn'),error=$('error'),result=$('result'),originalPreview=$('originalPreview'),resizedPreview=$('resizedPreview'),originalDimensions=$('originalDimensions'),newDimensions=$('newDimensions'),outputType=$('outputType'),resultBadge=$('resultBadge'),download=$('downloadBtn');
let file=null,originalUrl='',resizedUrl='',ratio=1;
function fmt(bytes){if(bytes<1024)return bytes+' B';if(bytes<1048576)return (bytes/1024).toFixed(1)+' KB';return (bytes/1048576).toFixed(2)+' MB'}
function showError(msg){error.textContent=msg;error.style.display='block'}
function clearError(){error.style.display='none'}
function chooseFile(f){if(!f)return;if(!['image/jpeg','image/png','image/webp'].includes(f.type)){showError('Please choose a JPG, PNG or WebP image.');return}clearError();file=f;editor.style.display='block';fileName.textContent=f.name;fileSize.textContent=fmt(f.size);if(originalUrl)URL.revokeObjectURL(originalUrl);originalUrl=URL.createObjectURL(f);originalPreview.src=originalUrl;result.style.display='none';download.removeAttribute('href');const img=new Image();img.onload=function(){width.value=img.naturalWidth;height.value=img.naturalHeight;ratio=img.naturalWidth/img.naturalHeight;originalDimensions.textContent=img.naturalWidth+' × '+img.naturalHeight};img.src=originalUrl}
function syncHeight(){if(!keepRatio.checked)return;const w=parseInt(width.value,10);if(w>0)height.value=Math.max(1,Math.round(w/ratio))}
function syncWidth(){if(!keepRatio.checked)return;const h=parseInt(height.value,10);if(h>0)width.value=Math.max(1,Math.round(h*ratio))}
function canvasBlob(canvas,mime,quality){return new Promise((resolve,reject)=>canvas.toBlob(b=>b?resolve(b):reject(new Error('Could not create the resized image.')),mime,quality))}
async function resize(){clearError();if(!file){showError('Choose an image first.');return}const w=parseInt(width.value,10),h=parseInt(height.value,10);if(!w||!h||w<1||h<1||w>10000||h>10000){showError('Enter a valid width and height between 1 and 10,000 pixels.');return}resizeBtn.disabled=true;resizeBtn.textContent='Resizing…';try{const img=new Image();img.decoding='async';await new Promise((resolve,reject)=>{img.onload=resolve;img.onerror=()=>reject(new Error('Image could not be loaded.'));img.src=originalUrl});const canvas=document.createElement('canvas');canvas.width=w;canvas.height=h;const ctx=canvas.getContext('2d');if(!ctx)throw new Error('Canvas is not available in this browser.');ctx.imageSmoothingEnabled=true;ctx.imageSmoothingQuality='high';ctx.drawImage(img,0,0,w,h);let mime=file.type;const selected=format.value;if(selected==='jpeg')mime='image/jpeg';else if(selected==='png')mime='image/png';else if(selected==='webp')mime='image/webp';if(!['image/jpeg','image/png','image/webp'].includes(mime))mime='image/jpeg';const blob=await canvasBlob(canvas,mime,mime==='image/png'?undefined:.9);if(resizedUrl)URL.revokeObjectURL(resizedUrl);resizedUrl=URL.createObjectURL(blob);resizedPreview.src=resizedUrl;newDimensions.textContent=w+' × '+h;outputType.textContent=mime==='image/jpeg'?'JPG':mime==='image/png'?'PNG':'WebP';resultBadge.textContent='Ready to download';resultBadge.style.background='#eaf8f0';resultBadge.style.color='#087443';download.href=resizedUrl;download.download='smarttoolz-resized-image.'+(mime==='image/png'?'png':mime==='image/webp'?'webp':'jpg');result.style.display='block';}catch(e){console.error(e);showError('We could not resize this image. Please try another image or different dimensions.')}finally{resizeBtn.disabled=false;resizeBtn.textContent='↔ Resize Image'}}
function reset(){if(originalUrl)URL.revokeObjectURL(originalUrl);if(resizedUrl)URL.revokeObjectURL(resizedUrl);file=null;input.value='';originalUrl='';resizedUrl='';editor.style.display='none';result.style.display='none';clearError();download.removeAttribute('href')}
dropzone.addEventListener('click',()=>input.click());dropzone.addEventListener('keydown',e=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();input.click()}});dropzone.addEventListener('dragover',e=>{e.preventDefault();dropzone.classList.add('dragover')});dropzone.addEventListener('dragleave',()=>dropzone.classList.remove('dragover'));dropzone.addEventListener('drop',e=>{e.preventDefault();dropzone.classList.remove('dragover');chooseFile(e.dataTransfer.files&&e.dataTransfer.files[0])});input.addEventListener('change',()=>chooseFile(input.files&&input.files[0]));width.addEventListener('input',syncHeight);height.addEventListener('input',syncWidth);resizeBtn.addEventListener('click',resize);resetBtn.addEventListener('click',reset);window.addEventListener('beforeunload',()=>{if(originalUrl)URL.revokeObjectURL(originalUrl);if(resizedUrl)URL.revokeObjectURL(resizedUrl)});
})();
</script>



<?php require_once dirname(__DIR__) . '/footer.php'; ?>
</body>
</html>
