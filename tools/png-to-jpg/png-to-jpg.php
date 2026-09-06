<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/header.php';
?>
<!doctype html>
<html lang="en">
<head>
<?php require_once dirname(__DIR__) . '/head.php'; ?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>PNG to JPG Converter Online Free | SmartToolz</title>
<meta name="description" content="Convert PNG images to JPG online for free with SmartToolz. Adjust JPG quality, preview the result and download your converted image directly in your browser.">
<meta name="robots" content="index,follow,max-image-preview:large">
<link rel="canonical" href="https://smarttoolz.in/tools/png-to-jpg/">
<meta property="og:type" content="website">
<meta property="og:site_name" content="SmartToolz">
<meta property="og:title" content="PNG to JPG Converter Online Free | SmartToolz">
<meta property="og:description" content="Convert PNG images to JPG in your browser. Choose quality, preview the result and download the JPG file.">
<meta property="og:url" content="https://smarttoolz.in/tools/png-to-jpg/">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="PNG to JPG Converter Online Free | SmartToolz">
<meta name="twitter:description" content="Free browser-based PNG to JPG conversion with adjustable quality and instant download.">
<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"WebApplication",
  "name":"SmartToolz PNG to JPG Converter",
  "url":"https://smarttoolz.in/tools/png-to-jpg/",
  "description":"A free browser-based tool for converting PNG images to JPG format.",
  "applicationCategory":"UtilitiesApplication",
  "operatingSystem":"Any",
  "browserRequirements":"Requires a modern web browser with JavaScript enabled",
  "offers":{"@type":"Offer","price":"0","priceCurrency":"USD"}
}
</script>
<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"FAQPage",
  "mainEntity":[
    {"@type":"Question","name":"Is the PNG to JPG converter free?","acceptedAnswer":{"@type":"Answer","text":"Yes. SmartToolz PNG to JPG conversion is free to use without an account."}},
    {"@type":"Question","name":"Can I convert a PNG to JPG in my browser?","acceptedAnswer":{"@type":"Answer","text":"Yes. The conversion is performed locally in your browser using standard web image and canvas features."}},
    {"@type":"Question","name":"Can I choose JPG quality?","acceptedAnswer":{"@type":"Answer","text":"Yes. Use the quality slider before conversion to balance image quality and file size."}},
    {"@type":"Question","name":"What happens to transparent PNG areas?","acceptedAnswer":{"@type":"Answer","text":"JPG does not support transparency, so transparent areas are filled with white during conversion."}},
    {"@type":"Question","name":"Are my images uploaded?","acceptedAnswer":{"@type":"Answer","text":"The converter processes the selected PNG in your browser and does not intentionally upload it to SmartToolz."}}
  ]
}
</script>
<style>
:root{--brand:#635bff;--brand-dark:#5148ee;--ink:#172033;--muted:#667085;--line:#e4e8f0;--soft:#f7f8fc;--green:#087443}
*{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;background:#f6f8fc;color:var(--ink);font-family:Inter,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}.page{width:min(1080px,calc(100% - 24px));margin:0 auto 54px}.hero{text-align:center;padding:38px 12px 25px}.eyebrow{display:inline-flex;padding:7px 12px;border:1px solid #dedbff;border-radius:999px;background:#efedff;color:var(--brand);font-size:10px;font-weight:900;letter-spacing:1px}.hero h1{margin:14px 0 10px;font-size:clamp(32px,6vw,50px);line-height:1.08;letter-spacing:-2px}.hero h1 span{color:var(--brand)}.hero p{max-width:720px;margin:0 auto;color:var(--muted);font-size:14px;line-height:1.75}.tool-card,.content,.related-card{background:#fff;border:1px solid var(--line);border-radius:18px}.tool-card{padding:22px;box-shadow:0 16px 45px rgba(25,35,70,.06)}.drop{min-height:270px;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:35px 20px;border:2px dashed #cfd4e3;border-radius:18px;background:#fafbff;text-align:center;cursor:pointer;transition:.2s}.drop:hover,.drop.drag{border-color:var(--brand);background:#f5f3ff;transform:translateY(-1px)}.icon{width:62px;height:62px;display:grid;place-items:center;border-radius:18px;background:#eeedff;color:var(--brand);font-size:28px;margin-bottom:14px}.drop h2{margin:0 0 7px;font-size:20px}.drop p{margin:0;color:var(--muted);font-size:13px}.formats{margin-top:11px;font-size:11px;color:#8992a3}#fileInput{display:none}.work{display:none;margin-top:18px}.file-row{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:13px 15px;background:var(--soft);border:1px solid var(--line);border-radius:13px}.file-name{min-width:0;font-size:13px;font-weight:800;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.file-size{font-size:11px;color:var(--muted);white-space:nowrap}.settings{margin-top:14px;padding:18px;background:#fff;border:1px solid var(--line);border-radius:15px}.setting-top{display:flex;justify-content:space-between;gap:10px;margin-bottom:10px;font-size:13px;font-weight:800}.quality{color:var(--brand)}input[type=range]{width:100%;accent-color:var(--brand);cursor:pointer}.quality-help{display:flex;justify-content:space-between;color:#9299a8;font-size:10px;margin-top:4px}.actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:16px}.btn{border:0;border-radius:11px;padding:12px 17px;font-weight:800;cursor:pointer;text-decoration:none;font-size:13px}.primary{background:var(--brand);color:#fff}.secondary{background:#edf0f5;color:#344054}.btn:disabled{opacity:.5;cursor:not-allowed}.error{display:none;margin-top:14px;padding:12px 14px;border-radius:11px;background:#fff1f1;color:#b4232d;font-size:12px}.result{display:none;margin-top:20px;padding-top:20px;border-top:1px solid var(--line)}.previews{display:grid;grid-template-columns:1fr 1fr;gap:14px}.preview{border:1px solid var(--line);border-radius:15px;padding:12px;background:#fafbff}.preview h3{margin:0 0 9px;font-size:12px}.preview-box{min-height:210px;display:flex;align-items:center;justify-content:center;border-radius:10px;overflow:hidden;background:#fff}.preview-box.checker{background-color:#fff;background-image:linear-gradient(45deg,#eef0f5 25%,transparent 25%),linear-gradient(-45deg,#eef0f5 25%,transparent 25%),linear-gradient(45deg,transparent 75%,#eef0f5 75%),linear-gradient(-45deg,transparent 75%,#eef0f5 75%);background-size:22px 22px;background-position:0 0,0 11px,11px -11px,-11px 0}.preview-box img{display:block;max-width:100%;max-height:360px;object-fit:contain}.result-actions{justify-content:center}.download{display:inline-flex;align-items:center;justify-content:center;min-width:220px;min-height:48px;padding:13px 20px;border-radius:12px;background:var(--brand);color:#fff;text-decoration:none;font-weight:900;font-size:14px;box-shadow:0 8px 20px rgba(99,91,255,.2)}.content{margin-top:18px;padding:24px}.content h2{margin:0 0 9px;font-size:20px}.content h3{margin:22px 0 7px;font-size:16px}.content p,.content li{color:var(--muted);font-size:13px;line-height:1.75}.content p{margin:0 0 10px}.content ol,.content ul{padding-left:20px;margin:8px 0 0}.faq{margin-top:20px}.faq details{border-top:1px solid var(--line);padding:13px 0}.faq details:last-child{border-bottom:1px solid var(--line)}.faq summary{cursor:pointer;font-size:13px;font-weight:800}.faq details p{margin:9px 0 0}.related{margin-top:22px}.related-head{display:flex;align-items:end;justify-content:space-between;gap:15px;margin-bottom:12px}.related-head h2{margin:0;font-size:20px}.related-head a{color:var(--brand);font-size:12px;font-weight:850}.related-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}.related-card{display:flex;flex-direction:column;min-height:145px;padding:16px;transition:.2s}.related-card:hover{transform:translateY(-3px);border-color:#d8d4ff;box-shadow:0 15px 35px rgba(16,24,40,.08)}.related-icon{width:38px;height:38px;display:grid;place-items:center;border-radius:11px;background:#efedff;color:var(--brand);font-size:18px;margin-bottom:10px}.related-card h3{margin:0 0 4px;font-size:13px}.related-card p{margin:0;color:var(--muted);font-size:10.5px;line-height:1.5}.related-link{margin-top:auto;padding-top:10px;color:var(--brand);font-size:10.5px;font-weight:850}
@media(max-width:800px){.page{width:calc(100% - 16px)}.tool-card{padding:14px}.previews,.related-grid{grid-template-columns:1fr 1fr}.file-row{align-items:flex-start;flex-direction:column;gap:4px}.hero h1{letter-spacing:-1.2px}.download{width:100%}}@media(max-width:520px){.previews,.related-grid{grid-template-columns:1fr}.related-head{align-items:flex-start;flex-direction:column;gap:4px}}
</style>
</head>
<body>
<?php require_once dirname(__DIR__) . '/header.php'; ?>

<main class="page">
<section class="hero"><span class="eyebrow">SMARTTOOLZ • FREE ONLINE TOOL</span><h1>PNG to <span>JPG Converter</span></h1><p>Convert PNG images to JPG online for free. Choose your preferred quality, preview the converted image, and download the JPG directly from your browser.</p></section>
<section class="tool-card" aria-label="Free PNG to JPG converter">
<div class="drop" id="drop" tabindex="0" role="button" aria-label="Choose a PNG image"><div class="icon" aria-hidden="true">🖼</div><h2>Drop your PNG image here</h2><p>Drag and drop a PNG file, or click to choose one from your device.</p><div class="formats">Supported format: PNG</div><input id="fileInput" type="file" accept="image/png"></div>
<div class="work" id="work">
<div class="file-row"><span class="file-name" id="fileName">Image</span><span class="file-size" id="fileSize">—</span></div>
<div class="settings"><div class="setting-top"><span>JPG quality</span><span class="quality" id="qualityValue">90%</span></div><label for="quality" style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);">JPG quality</label><input id="quality" type="range" min="10" max="100" value="90"><div class="quality-help"><span>Smaller file</span><span>Higher quality</span></div><div class="actions"><button class="btn primary" id="convert" type="button">Convert to JPG</button><button class="btn secondary" id="reset" type="button">Choose Another</button></div></div>
<div class="error" id="error" role="alert"></div>
<div class="result" id="result"><div class="previews"><div class="preview"><h3>Original PNG</h3><div class="preview-box checker"><img id="original" alt="Original PNG preview"></div></div><div class="preview"><h3>Converted JPG</h3><div class="preview-box"><img id="converted" alt="Converted JPG preview"></div></div></div><div class="actions result-actions"><a class="download" id="download" href="#" download="smarttoolz-converted.jpg">⬇ Download JPG</a></div></div>
</div></section>
<section class="content"><h2>Free PNG to JPG Converter Online</h2><p>PNG and JPG are common image formats, but they are useful for different purposes. PNG supports transparency and often works well for graphics, screenshots and interface assets. JPG is designed for photographic images and can produce smaller files with adjustable lossy compression.</p><p>SmartToolz lets you convert PNG to JPG directly in your browser. The image is decoded on your device, rendered to a canvas with a white background for transparency, and exported as a JPG at the quality level you select.</p><h3>How to convert PNG to JPG</h3><ol><li>Choose a PNG image or drag it into the upload area.</li><li>Adjust the JPG quality slider to balance quality and file size.</li><li>Select <strong>Convert to JPG</strong> to create the new image.</li><li>Preview the result and download the JPG file.</li></ol><h3>When should you use JPG?</h3><p>JPG is a practical choice for photos, web images and situations where a smaller file is more important than lossless image quality. Keep the original PNG when you need transparency or pixel-perfect graphics.</p><div class="faq"><h3>Frequently Asked Questions</h3><details><summary>Is this PNG to JPG converter free?</summary><p>Yes. It is free to use and does not require an account.</p></details><details><summary>Does the conversion happen online or on my device?</summary><p>The conversion is performed in your browser on your device. The converter itself does not intentionally upload your selected image to SmartToolz.</p></details><details><summary>Can I control JPG quality?</summary><p>Yes. Use the quality slider before conversion. Higher values generally preserve more visual detail, while lower values usually reduce file size more aggressively.</p></details><details><summary>What happens to transparent PNG backgrounds?</summary><p>Because JPG does not support transparency, transparent pixels are filled with white during conversion.</p></details></div></section>
<section class="related"><div class="related-head"><h2>Related Image Tools</h2><a href="/tool.php">View all tools →</a></div><div class="related-grid"><a class="related-card" href="/tools/jpg-to-png/"><span class="related-icon">↔</span><h3>JPG to PNG</h3><p>Convert JPG and JPEG images to PNG in your browser.</p><span class="related-link">Open tool →</span></a><a class="related-card" href="/tools/image-compressor/"><span class="related-icon">↘</span><h3>Image Compressor</h3><p>Reduce JPG, PNG and WebP file sizes quickly.</p><span class="related-link">Open tool →</span></a><a class="related-card" href="/tools/image-resizer/"><span class="related-icon">↔</span><h3>Image Resizer</h3><p>Resize images to exact width and height.</p><span class="related-link">Open tool →</span></a><a class="related-card" href="/tools/image-background-remover/"><span class="related-icon">✂</span><h3>Background Remover</h3><p>Remove image backgrounds and create transparent PNGs.</p><span class="related-link">Open tool →</span></a></div></section>
</main>


<script>
(() => {
'use strict';
const input=document.getElementById('fileInput');
const drop=document.getElementById('drop');
const work=document.getElementById('work');
const fileName=document.getElementById('fileName');
const fileSize=document.getElementById('fileSize');
const quality=document.getElementById('quality');
const qualityValue=document.getElementById('qualityValue');
const convert=document.getElementById('convert');
const reset=document.getElementById('reset');
const error=document.getElementById('error');
const result=document.getElementById('result');
const original=document.getElementById('original');
const converted=document.getElementById('converted');
const download=document.getElementById('download');
let file=null;
let sourceUrl='';
let resultUrl='';
function showError(message){error.textContent=message;error.style.display='block';}
function clearError(){error.textContent='';error.style.display='none';}
function formatSize(bytes){if(bytes<1024)return bytes+' B';if(bytes<1024*1024)return (bytes/1024).toFixed(1)+' KB';return (bytes/1024/1024).toFixed(2)+' MB';}
function selectFile(selected){clearError();if(!selected)return;if(selected.type!=='image/png'){showError('Please select a PNG image.');return;}file=selected;fileName.textContent=selected.name;fileSize.textContent=formatSize(selected.size);work.style.display='block';result.style.display='none';if(sourceUrl)URL.revokeObjectURL(sourceUrl);sourceUrl=URL.createObjectURL(selected);original.src=sourceUrl;if(resultUrl)URL.revokeObjectURL(resultUrl);resultUrl='';download.removeAttribute('href');}
drop.addEventListener('click',()=>input.click());
drop.addEventListener('keydown',e=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();input.click();}});
input.addEventListener('change',()=>selectFile(input.files[0]));
['dragenter','dragover'].forEach(type=>drop.addEventListener(type,e=>{e.preventDefault();drop.classList.add('drag');}));
['dragleave','drop'].forEach(type=>drop.addEventListener(type,e=>{e.preventDefault();drop.classList.remove('drag');}));
drop.addEventListener('drop',e=>selectFile(e.dataTransfer.files[0]));
quality.addEventListener('input',()=>{qualityValue.textContent=quality.value+'%';});
convert.addEventListener('click',()=>{if(!file)return;clearError();convert.disabled=true;convert.textContent='Converting…';const img=new Image();const objectUrl=URL.createObjectURL(file);img.onload=()=>{try{const canvas=document.createElement('canvas');canvas.width=img.naturalWidth;canvas.height=img.naturalHeight;const ctx=canvas.getContext('2d');if(!ctx)throw new Error('Canvas unavailable');ctx.fillStyle='#fff';ctx.fillRect(0,0,canvas.width,canvas.height);ctx.drawImage(img,0,0);canvas.toBlob(blob=>{if(!blob){showError('Conversion failed. Please try another PNG.');convert.disabled=false;convert.textContent='Convert to JPG';URL.revokeObjectURL(objectUrl);return;}if(resultUrl)URL.revokeObjectURL(resultUrl);resultUrl=URL.createObjectURL(blob);converted.src=resultUrl;download.href=resultUrl;download.download=(file.name||'smarttoolz-image').replace(/\.png$/i,'')+'.jpg';result.style.display='block';convert.disabled=false;convert.textContent='Convert to JPG';URL.revokeObjectURL(objectUrl);},'image/jpeg',Number(quality.value)/100);}catch(err){showError('Unable to convert this PNG image.');convert.disabled=false;convert.textContent='Convert to JPG';URL.revokeObjectURL(objectUrl);}};img.onerror=()=>{showError('Unable to read the PNG image.');convert.disabled=false;convert.textContent='Convert to JPG';URL.revokeObjectURL(objectUrl);};img.src=objectUrl;});
reset.addEventListener('click',()=>{input.value='';file=null;work.style.display='none';result.style.display='none';clearError();if(sourceUrl)URL.revokeObjectURL(sourceUrl);if(resultUrl)URL.revokeObjectURL(resultUrl);sourceUrl='';resultUrl='';download.removeAttribute('href');});
})();
</script>

<?php require_once dirname(__DIR__) . '/footer.php'; ?>
</body></html>