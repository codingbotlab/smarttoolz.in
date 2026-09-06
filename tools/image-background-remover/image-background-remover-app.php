<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/header.php';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Free Image Background Remover Online | SmartToolz</title>
<meta name="description" content="Remove image backgrounds online for free with SmartToolz. Upload a PNG, JPG or WebP image and get a transparent PNG directly in your browser.">
<style>
:root{--brand:#635bff;--brand-dark:#5148e8;--ink:#172033;--muted:#667085;--line:#e5e7ef;--soft:#f7f8fc;--success:#16845b}
*{box-sizing:border-box}
body{margin:0;background:#f7f8fc;color:var(--ink);font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",Arial,sans-serif}
.tool-page{width:min(1080px,calc(100% - 28px));margin:28px auto 64px}
.tool-hero{text-align:center;margin:0 auto 24px;max-width:760px}
.badge{display:inline-flex;align-items:center;gap:7px;padding:7px 12px;border:1px solid #dedbff;background:#eeedff;color:var(--brand);border-radius:999px;font-size:11px;font-weight:900;letter-spacing:.8px}
.badge-dot{width:6px;height:6px;border-radius:50%;background:var(--brand)}
.tool-hero h1{margin:15px 0 10px;font-size:clamp(30px,5vw,48px);line-height:1.08;letter-spacing:-1.8px}
.tool-hero p{margin:0 auto;color:var(--muted);font-size:15px;line-height:1.7;max-width:650px}
.tool-card{background:#fff;border:1px solid var(--line);border-radius:24px;box-shadow:0 18px 55px rgba(23,32,51,.07);overflow:hidden}
.tool-card-head{display:flex;align-items:center;justify-content:space-between;gap:15px;padding:18px 22px;border-bottom:1px solid var(--line)}
.tool-title{display:flex;align-items:center;gap:11px;font-weight:850;font-size:14px}
.tool-icon{width:38px;height:38px;display:grid;place-items:center;border-radius:11px;background:#eeedff;color:var(--brand);font-size:19px}
.privacy-note{font-size:11px;color:var(--muted);white-space:nowrap}
.workspace{padding:24px}
.dropzone{min-height:350px;border:2px dashed #cfd3e2;border-radius:20px;background:linear-gradient(180deg,#fbfcff,#f7f8fc);display:flex;align-items:center;justify-content:center;text-align:center;cursor:pointer;transition:border-color .2s,background .2s,transform .2s}
.dropzone:hover,.dropzone.dragging{border-color:var(--brand);background:#f3f2ff;transform:translateY(-1px)}
.drop-content{max-width:480px;padding:30px 18px}
.upload-icon{width:70px;height:70px;margin:0 auto 18px;border-radius:20px;background:#eeedff;color:var(--brand);display:grid;place-items:center;font-size:32px}
.dropzone h2{margin:0 0 7px;font-size:20px;letter-spacing:-.4px}
.dropzone p{margin:0;color:var(--muted);font-size:13px;line-height:1.6}
.file-types{display:inline-flex;margin-top:14px;padding:6px 10px;border-radius:8px;background:#fff;border:1px solid var(--line);font-size:10px;font-weight:800;color:#7b8495}
.picker{display:none}
.result{display:none}
.result-panel{border:1px solid var(--line);border-radius:18px;padding:16px;background:#fff}
.preview-wrap{min-height:280px;border-radius:14px;display:flex;align-items:center;justify-content:center;padding:20px;background-color:#f8f9fc;background-image:linear-gradient(45deg,#e8ebf2 25%,transparent 25%),linear-gradient(-45deg,#e8ebf2 25%,transparent 25%),linear-gradient(45deg,transparent 75%,#e8ebf2 75%),linear-gradient(-45deg,transparent 75%,#e8ebf2 75%);background-size:24px 24px;background-position:0 0,0 12px,12px -12px,-12px 0}
.preview-wrap img{display:block;max-width:100%;max-height:560px;height:auto;border-radius:10px}
.status-row{display:flex;align-items:center;justify-content:center;gap:10px;min-height:45px;margin-top:10px;color:var(--brand);font-size:13px;font-weight:750}
.spinner{width:17px;height:17px;border:2px solid #ddd9ff;border-top-color:var(--brand);border-radius:50%;animation:spin .8s linear infinite}@keyframes spin{to{transform:rotate(360deg)}}
.actions{display:flex;justify-content:center;gap:10px;flex-wrap:wrap;margin-top:8px}
.btn{border:0;border-radius:11px;padding:12px 20px;background:var(--brand);color:#fff;font-weight:850;font-size:13px;cursor:pointer;transition:.2s}.btn:hover:not(:disabled){background:var(--brand-dark);transform:translateY(-1px)}.btn:disabled{opacity:.5;cursor:not-allowed}
.btn.secondary{background:#fff;color:var(--ink);border:1px solid var(--line)}
.error{color:#c0392b!important}
.benefits{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:14px}
.benefit{padding:16px;border:1px solid var(--line);border-radius:14px;background:#fff}.benefit strong{display:block;font-size:12px;margin-bottom:4px}.benefit span{font-size:11px;color:var(--muted);line-height:1.5}
.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:24px}
.info-card{background:#fff;border:1px solid var(--line);border-radius:18px;padding:22px}.info-card h2{margin:0 0 12px;font-size:17px;letter-spacing:-.3px}.info-card p,.info-card li{font-size:12px;line-height:1.7;color:var(--muted)}.info-card ol{margin:0;padding-left:20px}.info-card li+li{margin-top:5px}
.faq{margin-top:16px}.faq details{border-top:1px solid var(--line);padding:13px 0}.faq details:last-child{border-bottom:1px solid var(--line)}.faq summary{cursor:pointer;font-size:12px;font-weight:800}.faq p{margin:9px 0 0;font-size:12px;color:var(--muted);line-height:1.7}
@media(max-width:720px){.workspace{padding:15px}.tool-card-head{padding:15px 16px}.privacy-note{display:none}.dropzone{min-height:300px}.benefits,.info-grid{grid-template-columns:1fr}.tool-page{margin-top:20px}}
@media(max-width:430px){.tool-hero h1{font-size:31px}.tool-hero p{font-size:13px}.drop-content{padding:25px 10px}.preview-wrap{min-height:220px}}
</style>
</head>
<body>
<main class="tool-page">
<section class="tool-hero">
<span class="badge"><span class="badge-dot" aria-hidden="true"></span>FREE • NO SIGNUP</span>
<h1>Remove Image Background Online</h1>
<p>Upload your image and SmartToolz will remove the background in your browser. Get a clean transparent PNG without creating an account.</p>
</section>

<section class="tool-card" aria-label="Background remover tool">
<header class="tool-card-head">
<div class="tool-title"><span class="tool-icon" aria-hidden="true">✂</span>Image Background Remover</div>
<div class="privacy-note">🔒 Your image is processed in your browser</div>
</header>
<div class="workspace">
<div class="dropzone" id="drop" tabindex="0" role="button" aria-label="Choose an image or drop it here">
<div class="drop-content">
<div class="upload-icon" aria-hidden="true">↑</div>
<h2>Drop your image here</h2>
<p>or click to choose a file from your device</p>
<span class="file-types">PNG • JPG • WEBP</span>
<input class="picker" id="picker" type="file" accept="image/png,image/jpeg,image/webp">
</div>
</div>

<div class="result" id="result" aria-live="polite">
<div class="result-panel">
<div class="preview-wrap" id="previewWrap"><img id="preview" alt="Background removed transparent PNG preview"></div>
<div class="status-row"><span class="spinner" id="spinner" aria-hidden="true"></span><span id="status">Preparing image…</span></div>
<div class="actions"><button class="btn" id="download" type="button" disabled>Download PNG</button><button class="btn secondary" id="again" type="button">Choose another image</button></div>
</div>
</div>
</div>
</section>

<section class="benefits" aria-label="Tool benefits">
<div class="benefit"><strong>⚡ Fast & Simple</strong><span>Upload, wait for processing, then download your transparent PNG.</span></div>
<div class="benefit"><strong>🔒 Browser Processing</strong><span>Your selected image is processed locally in your browser.</span></div>
<div class="benefit"><strong>📥 PNG Output</strong><span>Download the result with transparency preserved.</span></div>
</section>

<section class="info-grid">
<article class="info-card"><h2>How to remove an image background</h2><ol><li>Click the upload area and choose a PNG, JPG or WebP image.</li><li>Wait while the background removal model processes the image.</li><li>Preview the transparent result and click <strong>Download PNG</strong>.</li></ol></article>
<article class="info-card"><h2>About this background remover</h2><p>SmartToolz provides a simple image background remover for everyday editing tasks. It is designed to be easy to use on desktop and mobile browsers, with no login required.</p><p>For best results, use a clear image with the main subject separated from the background.</p></article>
</section>

<section class="info-card faq" aria-labelledby="faq-title">
<h2 id="faq-title">Frequently Asked Questions</h2>
<details><summary>Is the background remover free?</summary><p>Yes. The tool is available to use for free without signing up.</p></details>
<details><summary>What image formats are supported?</summary><p>You can upload PNG, JPG or WebP images.</p></details>
<details><summary>What do I get after removing the background?</summary><p>The result is a PNG image with the background removed and transparency preserved.</p></details>
<details><summary>Do I need to create an account?</summary><p>No account or login is required to use this tool.</p></details>
</section>
</main>

<?php require_once dirname(__DIR__) . '/footer.php'; ?>
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
  status.textContent='Loading background remover…';
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
    const blob=await remove(file,{model:'isnet_quint8',device:'cpu',proxyToWorker:true,output:{format:'image/png',quality:1,type:'foreground'}});
    if(!(blob instanceof Blob)||blob.size===0)throw new Error('Empty result');
    if(resultUrl)URL.revokeObjectURL(resultUrl);
    resultUrl=URL.createObjectURL(blob);
    preview.onload=()=>{previewWrap.style.display='flex';spinner.style.display='none';download.disabled=false;status.textContent='Background removed successfully.'};
    preview.src=resultUrl;
    download.onclick=()=>{if(!resultUrl)return;const a=document.createElement('a');a.href=resultUrl;a.download='smarttoolz-background-removed.png';document.body.appendChild(a);a.click();a.remove()};
  }catch(error){console.error(error);spinner.style.display='none';status.className='error';status.textContent='Could not remove the background. Please try again with another image.';download.disabled=true;}
}
function reset(){if(resultUrl){URL.revokeObjectURL(resultUrl);resultUrl=''};picker.value='';result.style.display='none';drop.style.display='flex';status.className='';status.textContent='Preparing image…';download.disabled=true;preview.removeAttribute('src')}
drop.onclick=()=>picker.click();
drop.onkeydown=e=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();picker.click()}};
picker.onchange=()=>run(picker.files?.[0]);
drop.ondragover=e=>{e.preventDefault();drop.classList.add('dragging')};
drop.ondragleave=()=>drop.classList.remove('dragging');
drop.ondrop=e=>{e.preventDefault();drop.classList.remove('dragging');run(e.dataTransfer.files?.[0])};
again.onclick=reset;
window.addEventListener('beforeunload',()=>{if(resultUrl)URL.revokeObjectURL(resultUrl)});
</script>
</body>
</html>
