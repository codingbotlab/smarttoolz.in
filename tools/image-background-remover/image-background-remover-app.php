<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/header.php';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Background Remover — SmartToolz</title>
<meta name="description" content="Remove image backgrounds online for free with SmartToolz. Process images directly in your browser.">
<style>
*{box-sizing:border-box}
body{margin:0;background:#f6f8fc;color:#172033;font-family:Inter,system-ui,Arial,sans-serif}
.page{width:min(1000px,calc(100% - 24px));margin:30px auto 60px}
.card{background:#fff;border:1px solid #e5e9f0;border-radius:20px;padding:26px;box-shadow:0 14px 40px rgba(20,30,70,.05)}
h1{margin:0 0 8px;font-size:clamp(30px,5vw,46px)}
p{color:#707b8e}
.drop{margin-top:20px;padding:50px 20px;border:2px dashed #d7dbea;border-radius:16px;text-align:center;background:#fafbff;cursor:pointer;transition:.2s}
.drop:hover,.drop.dragging{border-color:#635bff;background:#f5f3ff}
.picker{display:none}
.btn{display:inline-block;border:0;border-radius:10px;padding:11px 16px;background:#635bff;color:#fff;font-weight:800;cursor:pointer;margin-top:12px}
.btn:disabled{opacity:.55;cursor:not-allowed}
.viewer{display:none;margin-top:20px;text-align:center}
.preview-wrap{display:none;margin:18px auto 0;width:min(760px,100%);min-height:220px;padding:20px;border:1px solid #e5e9f0;border-radius:16px;background-color:#f7f8fc;background-image:linear-gradient(45deg,#e9ecf3 25%,transparent 25%),linear-gradient(-45deg,#e9ecf3 25%,transparent 25%),linear-gradient(45deg,transparent 75%,#e9ecf3 75%),linear-gradient(-45deg,transparent 75%,#e9ecf3 75%);background-size:24px 24px;background-position:0 0,0 12px,12px -12px,-12px 0;align-items:center;justify-content:center}
.viewer img{display:block;max-width:100%;max-height:600px;height:auto;border-radius:8px}
.status{margin-top:14px;font-size:13px;font-weight:700;color:#635bff}
.error{color:#c0392b}
.loading{display:inline-flex;align-items:center;gap:9px}
.spinner{width:16px;height:16px;border:2px solid #dcd9ff;border-top-color:#635bff;border-radius:50%;animation:spin .8s linear infinite}
@keyframes spin{to{transform:rotate(360deg)}}
</style>
</head>
<body>
<main class="page">
<section class="card">
<h1>Background Remover</h1>
<p>Remove image backgrounds directly in your browser. No login, analytics or tracking.</p>
<div class="drop" id="drop" tabindex="0" role="button" aria-label="Choose or drop an image">
<div aria-hidden="true" style="font-size:28px">🖼️</div>
<strong>Choose or drop an image</strong><br><small>PNG, JPG or WebP</small>
<input class="picker" id="picker" type="file" accept="image/png,image/jpeg,image/webp">
</div>
<div class="viewer" id="viewer" aria-live="polite">
<div class="loading" id="loading"><span class="spinner" aria-hidden="true"></span><span class="status" id="status">Loading…</span></div>
<div class="preview-wrap" id="previewWrap"><img id="preview" alt="Background removed result"></div>
<button class="btn" id="download" type="button" disabled>Download PNG</button>
</div>
</section>
</main>
<?php require_once dirname(__DIR__) . '/footer.php'; ?>
<script type="module">
const drop=document.getElementById('drop');
const picker=document.getElementById('picker');
const viewer=document.getElementById('viewer');
const preview=document.getElementById('preview');
const previewWrap=document.getElementById('previewWrap');
const loading=document.getElementById('loading');
const status=document.getElementById('status');
const download=document.getElementById('download');
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
    alert('Please choose a PNG, JPG or WebP image.');
    return;
  }
  drop.style.display='none';
  viewer.style.display='block';
  previewWrap.style.display='none';
  loading.style.display='inline-flex';
  download.disabled=true;
  status.className='status';
  status.textContent='Preparing image…';
  try{
    const remove=await loadEngine();
    status.textContent='Removing background…';
    const blob=await remove(file,{
      model:'isnet_quint8',
      device:'cpu',
      proxyToWorker:true,
      output:{format:'image/png',quality:1,type:'foreground'}
    });
    if(!(blob instanceof Blob)||blob.size===0)throw new Error('The processor returned an empty image.');
    if(resultUrl)URL.revokeObjectURL(resultUrl);
    resultUrl=URL.createObjectURL(blob);
    preview.onload=()=>{
      previewWrap.style.display='flex';
      loading.style.display='none';
      download.disabled=false;
      status.textContent='Background removed successfully.';
    };
    preview.onerror=()=>{throw new Error('The processed image could not be displayed.');};
    preview.src=resultUrl;
    download.onclick=()=>{
      if(!resultUrl)return;
      const a=document.createElement('a');
      a.href=resultUrl;
      a.download='smarttoolz-background-removed.png';
      document.body.appendChild(a);
      a.click();
      a.remove();
    };
  }catch(error){
    console.error(error);
    loading.style.display='inline-flex';
    status.className='status error';
    status.textContent='Could not remove the background. Please try again or choose another image.';
    download.disabled=true;
  }
}

drop.onclick=()=>picker.click();
drop.onkeydown=e=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();picker.click()}};
picker.onchange=()=>run(picker.files?.[0]);
drop.ondragover=e=>{e.preventDefault();drop.classList.add('dragging')};
drop.ondragleave=()=>drop.classList.remove('dragging');
drop.ondrop=e=>{e.preventDefault();drop.classList.remove('dragging');run(e.dataTransfer.files?.[0])};
window.addEventListener('beforeunload',()=>resultUrl&&URL.revokeObjectURL(resultUrl));
</script>
</body>
</html>
