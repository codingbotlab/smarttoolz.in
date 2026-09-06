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
<title>Free Image Background Remover Online | SmartToolz</title>
<meta name="description" content="Remove image backgrounds online for free with SmartToolz. Upload a PNG, JPG or WebP image and get a transparent PNG directly in your browser.">
<meta name="robots" content="index,follow">
<style>
:root{--brand:#635bff;--brand-dark:#5148e8;--ink:#172033;--muted:#667085;--line:#e5e7ef;--soft:#f7f8fc;--success:#16845b}
*{box-sizing:border-box}body{margin:0;background:#f7f8fc;color:var(--ink);font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",Arial,sans-serif}
.tool-page{width:min(1080px,calc(100% - 28px));margin:28px auto 64px}
.tool-hero{text-align:center;margin:0 auto 24px;max-width:780px}.badge{display:inline-flex;align-items:center;gap:7px;padding:7px 12px;border:1px solid #dedbff;background:#eeedff;color:var(--brand);border-radius:999px;font-size:11px;font-weight:900;letter-spacing:.8px}.badge-dot{width:6px;height:6px;border-radius:50%;background:var(--brand)}
.tool-hero h1{margin:15px 0 10px;font-size:clamp(30px,5vw,48px);line-height:1.08;letter-spacing:-1.8px}.tool-hero p{margin:0 auto;color:var(--muted);font-size:15px;line-height:1.7;max-width:700px}
.tool-card{background:#fff;border:1px solid var(--line);border-radius:24px;box-shadow:0 18px 55px rgba(23,32,51,.07);overflow:hidden}.tool-card-head{display:flex;align-items:center;justify-content:space-between;gap:15px;padding:18px 22px;border-bottom:1px solid var(--line)}.tool-title{display:flex;align-items:center;gap:11px;font-weight:850;font-size:14px}.tool-icon{width:38px;height:38px;display:grid;place-items:center;border-radius:11px;background:#eeedff;color:var(--brand);font-size:19px}.privacy-note{font-size:11px;color:var(--muted);white-space:nowrap}
.workspace{padding:24px}.dropzone{min-height:350px;border:2px dashed #cfd3e2;border-radius:20px;background:linear-gradient(180deg,#fbfcff,#f7f8fc);display:flex;align-items:center;justify-content:center;text-align:center;cursor:pointer;transition:border-color .2s,background .2s,transform .2s}.dropzone:hover,.dropzone.dragging{border-color:var(--brand);background:#f3f2ff;transform:translateY(-1px)}.drop-content{max-width:480px;padding:30px 18px}.upload-icon{width:70px;height:70px;margin:0 auto 18px;border-radius:20px;background:#eeedff;color:var(--brand);display:grid;place-items:center;font-size:32px}.dropzone h2{margin:0 0 7px;font-size:20px;letter-spacing:-.4px}.dropzone p{margin:0;color:var(--muted);font-size:13px;line-height:1.6}.file-types{display:inline-flex;margin-top:14px;padding:6px 10px;border-radius:8px;background:#fff;border:1px solid var(--line);font-size:10px;font-weight:800;color:#7b8495}.picker{display:none}
.result{display:none}.result-panel{border:1px solid var(--line);border-radius:18px;padding:16px;background:#fff}.preview-wrap{min-height:280px;border-radius:14px;display:flex;align-items:center;justify-content:center;padding:20px;background-color:#f8f9fc;background-image:linear-gradient(45deg,#e8ebf2 25%,transparent 25%),linear-gradient(-45deg,#e8ebf2 25%,transparent 25%),linear-gradient(45deg,transparent 75%,#e8ebf2 75%),linear-gradient(-45deg,transparent 75%,#e8ebf2 75%);background-size:24px 24px;background-position:0 0,0 12px,12px -12px,-12px 0}.preview-wrap img{display:block;max-width:100%;max-height:560px;height:auto;border-radius:10px}.status-row{display:flex;align-items:center;justify-content:center;gap:10px;min-height:45px;margin-top:10px;color:var(--brand);font-size:13px;font-weight:750}.spinner{width:17px;height:17px;border:2px solid #ddd9ff;border-top-color:var(--brand);border-radius:50%;animation:spin .8s linear infinite}@keyframes spin{to{transform:rotate(360deg)}}
.actions{display:flex;justify-content:center;gap:10px;flex-wrap:wrap;margin-top:8px}.download-btn,.again-btn{display:inline-flex!important;align-items:center!important;justify-content:center!important;gap:7px!important;min-width:170px!important;min-height:46px!important;border-radius:11px!important;padding:12px 20px!important;font-family:inherit!important;font-size:13px!important;font-weight:850!important;cursor:pointer!important;transition:.2s!important}.download-btn{border:0!important;background:var(--brand)!important;color:#fff!important;box-shadow:0 10px 24px rgba(99,91,255,.22)!important}.download-btn:hover:not(:disabled){background:var(--brand-dark)!important;transform:translateY(-1px)}.download-btn:disabled{opacity:.55!important;cursor:not-allowed!important}.again-btn{background:#fff!important;color:var(--ink)!important;border:1px solid var(--line)!important}.again-btn:hover{border-color:#c9c5ff!important;background:#faf9ff!important}.error{color:#c0392b!important}
.benefits{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:14px}.benefit{padding:16px;border:1px solid var(--line);border-radius:14px;background:#fff}.benefit strong{display:block;font-size:12px;margin-bottom:4px}.benefit span{font-size:11px;color:var(--muted);line-height:1.5}
.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:24px}.info-card{background:#fff;border:1px solid var(--line);border-radius:18px;padding:22px}.info-card h2{margin:0 0 12px;font-size:18px;letter-spacing:-.3px}.info-card p,.info-card li{font-size:12.5px;line-height:1.75;color:var(--muted)}.info-card ol{margin:0;padding-left:20px}.info-card li+li{margin-top:5px}.info-card strong{color:var(--ink)}
.faq{margin-top:16px}.faq details{border-top:1px solid var(--line);padding:13px 0}.faq details:last-child{border-bottom:1px solid var(--line)}.faq summary{cursor:pointer;font-size:12px;font-weight:800}.faq p{margin:9px 0 0;font-size:12px;color:var(--muted);line-height:1.7}
.related{margin-top:16px;background:#fff;border:1px solid var(--line);border-radius:18px;padding:22px}.related h2{margin:0 0 13px;font-size:18px}.related-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}.related a{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:13px;border:1px solid var(--line);border-radius:12px;background:var(--soft);font-size:12px;font-weight:800}.related a span:last-child{color:var(--brand)}.related a:hover{border-color:#c9c5ff;background:#faf9ff}
@media(max-width:720px){.workspace{padding:15px}.tool-card-head{padding:15px 16px}.privacy-note{display:none}.dropzone{min-height:300px}.benefits,.info-grid,.related-grid{grid-template-columns:1fr}.tool-page{margin-top:20px}}
@media(max-width:430px){.tool-hero h1{font-size:31px}.tool-hero p{font-size:13px}.drop-content{padding:25px 10px}.preview-wrap{min-height:220px}.download-btn,.again-btn{width:100%!important}}
</style>
</head>
<body>
<?php require_once dirname(__DIR__) . '/header.php'; ?>




<main class="tool-page">
<section class="tool-hero">
<span class="badge"><span class="badge-dot" aria-hidden="true"></span>FREE • NO SIGNUP</span>
<h1>Remove Image Background Online</h1>
<p>Remove the background from a JPG, PNG or WebP image and create a transparent PNG in your browser. No account is required.</p>
</section>

<section class="tool-card" aria-label="Free online image background remover">
<header class="tool-card-head">
<div class="tool-title"><span class="tool-icon" aria-hidden="true">✂</span>Image Background Remover</div>
<div class="privacy-note">🔒 Processed in your browser</div>
</header>
<div class="workspace">
<div class="dropzone" id="drop" tabindex="0" role="button" aria-label="Choose an image or drop it here">
<div class="drop-content">
<div class="upload-icon" aria-hidden="true">↑</div>
<h2>Drop your image here</h2>
<p>Drag & drop or click to choose a file from your device</p>
<span class="file-types">PNG • JPG • WEBP</span>
<input class="picker" id="picker" type="file" accept="image/png,image/jpeg,image/webp">
</div>
</div>
<div class="result" id="result" aria-live="polite">
<div class="result-panel">
<div class="preview-wrap" id="previewWrap"><img id="preview" alt="Transparent PNG preview with the image background removed"></div>
<div class="status-row"><span class="spinner" id="spinner" aria-hidden="true"></span><span id="status">Preparing image…</span></div>
<div class="actions"><button class="download-btn" id="download" type="button" disabled>⬇ Download PNG</button><button class="again-btn" id="again" type="button">Choose another image</button></div>
</div>
</div>
</div>
</section>

<section class="benefits" aria-label="Background remover benefits">
<div class="benefit"><strong>⚡ Fast & simple</strong><span>Upload one image, wait for processing and download the transparent result.</span></div>
<div class="benefit"><strong>🔒 Browser processing</strong><span>The selected image is processed locally in your browser for this tool.</span></div>
<div class="benefit"><strong>📥 Transparent PNG</strong><span>Download a PNG with the removed background and transparency preserved.</span></div>
</section>

<section class="info-grid">
<article class="info-card"><h2>How to remove a background from an image</h2><ol><li>Click the upload area and choose a PNG, JPG or WebP image.</li><li>Wait while SmartToolz processes the image and separates the main subject.</li><li>Check the transparent preview and click <strong>Download PNG</strong>.</li></ol></article>
<article class="info-card"><h2>Tips for better results</h2><ul><li>Use a clear image with the subject easy to distinguish from its background.</li><li>Avoid extremely low-resolution or heavily blurred images when possible.</li><li>For logos and graphics, PNG or WebP sources can preserve useful details.</li></ul></article>
</section>

<section class="info-card" aria-labelledby="about-title" style="margin-top:16px">
<h2 id="about-title">About SmartToolz Image Background Remover</h2>
<p>SmartToolz is a collection of simple browser-based utilities for everyday image, PDF, text and developer tasks. This background remover is designed for quick edits such as product photos, profile images, thumbnails, presentations and simple creative work.</p>
<p>The tool does not require a SmartToolz account. Processing is performed in the browser by the image-processing engine used by the tool. Because browser hardware and image complexity vary, processing time and results can differ between devices.</p>
</section>

<section class="info-card faq" aria-labelledby="faq-title">
<h2 id="faq-title">Frequently Asked Questions</h2>
<details><summary>Is the image background remover free?</summary><p>Yes. SmartToolz provides this tool free to use without requiring an account.</p></details>
<details><summary>Which image formats can I upload?</summary><p>PNG, JPG and WebP images are supported.</p></details>
<details><summary>What file will I receive?</summary><p>The tool creates a PNG result so transparency can be preserved after the background is removed.</p></details>
<details><summary>Do I need to install software?</summary><p>No installation is required. The tool is designed to run in a modern web browser.</p></details>
<details><summary>Why can processing take some time?</summary><p>Background removal uses an image-processing model in the browser, so speed depends on your device, browser and image complexity.</p></details>
</section>

<section class="related" aria-labelledby="related-title">
<h2 id="related-title">Related image tools</h2>
<div class="related-grid">
<a href="/tools/image-compressor/"><span>Image Compressor</span><span>→</span></a>
<a href="/tools/image-resizer/"><span>Image Resizer</span><span>→</span></a>
<a href="/tools/image-cropper/"><span>Image Cropper</span><span>→</span></a>
</div>
</section>
</main>



<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"FAQPage",
  "mainEntity":[
    {"@type":"Question","name":"Is the image background remover free?","acceptedAnswer":{"@type":"Answer","text":"Yes. SmartToolz provides this tool free to use without requiring an account."}},
    {"@type":"Question","name":"Which image formats can I upload?","acceptedAnswer":{"@type":"Answer","text":"PNG, JPG and WebP images are supported."}},
    {"@type":"Question","name":"What file will I receive?","acceptedAnswer":{"@type":"Answer","text":"The tool creates a PNG result so transparency can be preserved after the background is removed."}},
    {"@type":"Question","name":"Do I need to install software?","acceptedAnswer":{"@type":"Answer","text":"No installation is required. The tool is designed to run in a modern web browser."}},
    {"@type":"Question","name":"Why can processing take some time?","acceptedAnswer":{"@type":"Answer","text":"Background removal uses an image-processing model in the browser, so speed depends on your device, browser and image complexity."}}
  ]
}
</script>
<script type="module">
const drop=document.getElementById('drop');
const picker=document.getElementById('picker');
const result=document.getElementById('result');
const preview=document.getElementById('preview');
const previewWrap=document.getElementById('previewWrap');
const spinner=document.getElementById('spinner');
const status=document.getElementById('status');
const download=document.getElementById('download');
const again=document.getElementById('again');
let resultUrl='';
let removeBackground=null;

async function loadEngine(){
  if(removeBackground)return removeBackground;
  status.textContent='Loading background remover model…';
  const mod=await import('https://cdn.jsdelivr.net/npm/@imgly/background-removal@1.7.0/+esm');
  removeBackground=mod.removeBackground;
  return removeBackground;
}

async function run(file){
  if(!file)return;
  if(!['image/png','image/jpeg','image/webp'].includes(file.type)){
    status.className='error';status.textContent='Please choose a PNG, JPG or WebP image.';return;
  }
  drop.style.display='none';result.style.display='block';previewWrap.style.display='none';spinner.style.display='inline-block';download.disabled=true;status.className='';status.textContent='Preparing image…';
  try{
    const remove=await loadEngine();
    status.textContent='Removing background…';
    const blob=await remove(file,{model:'isnet_quint8',device:'cpu',proxyToWorker:false,progress:(key,current,total)=>{
      if(total>0){const pct=Math.round((current/total)*100);status.textContent=`Processing… ${pct}%`;}
    },output:{format:'image/png',quality:1}});
    if(!(blob instanceof Blob)||blob.size===0)throw new Error('Empty result');
    if(resultUrl)URL.revokeObjectURL(resultUrl);
    resultUrl=URL.createObjectURL(blob);
    preview.onload=()=>{previewWrap.style.display='flex';spinner.style.display='none';download.disabled=false;status.textContent='Background removed successfully — ready to download.'};
    preview.onerror=()=>{throw new Error('Preview failed to load')};
    preview.src=resultUrl;
  }catch(error){console.error('Background removal error:',error);spinner.style.display='none';status.className='error';status.textContent='Could not remove the background. Please try again with another image.';download.disabled=true;}
}
function reset(){if(resultUrl){URL.revokeObjectURL(resultUrl);resultUrl=''};picker.value='';result.style.display='none';drop.style.display='flex';status.className='';status.textContent='Preparing image…';download.disabled=true;preview.removeAttribute('src');previewWrap.style.display='none';spinner.style.display='inline-block'}
drop.onclick=()=>picker.click();
drop.onkeydown=e=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();picker.click()}};
picker.onchange=()=>run(picker.files?.[0]);
drop.ondragover=e=>{e.preventDefault();drop.classList.add('dragging')};
drop.ondragleave=()=>drop.classList.remove('dragging');
drop.ondrop=e=>{e.preventDefault();drop.classList.remove('dragging');run(e.dataTransfer.files?.[0])};
again.onclick=reset;
download.onclick=()=>{if(!resultUrl)return;const a=document.createElement('a');a.href=resultUrl;a.download='smarttoolz-background-removed.png';document.body.appendChild(a);a.click();a.remove()};
window.addEventListener('beforeunload',()=>{if(resultUrl)URL.revokeObjectURL(resultUrl)});
</script>




<?php require_once dirname(__DIR__) . '/footer.php'; ?>
</body>
</html>
