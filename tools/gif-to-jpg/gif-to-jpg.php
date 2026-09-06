<?php
declare(strict_types=1);
function smartToolzAd(string $key): void {}
function smartToolzMemeAd(string $key): void {}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php require_once dirname(__DIR__) . '/head.php'; ?>




<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>GIF to JPG Converter - SmartToolz</title>
<meta name="description" content="Convert GIF images to JPG online for free with SmartToolz. Convert GIF files directly in your browser.">
<meta name="robots" content="index, follow">
<style>
*{margin:0;padding:0;box-sizing:border-box}html{scroll-behavior:smooth}body{font-family:Inter,-apple-system,BlinkMacSystemFont,"Segoe UI",Arial,sans-serif;background:#f6f8fc;color:#172033;line-height:1.5}a{text-decoration:none;color:inherit}button,input{font-family:inherit}.site-header{position:sticky;top:0;z-index:1000;background:rgba(255,255,255,.96);backdrop-filter:blur(14px);border-bottom:1px solid #e5e9f0}.navbar{width:calc(100% - 20px);max-width:1400px;min-height:72px;margin:auto;display:flex;align-items:center;justify-content:space-between}.logo{display:flex;align-items:center;gap:10px;color:#172033;font-size:21px;font-weight:800}.logo-icon{width:42px;height:42px;display:grid;place-items:center;border-radius:13px;color:white;background:linear-gradient(135deg,#635bff,#916cff);box-shadow:0 8px 22px rgba(99,91,255,.18)}.nav-links{display:flex;align-items:center;gap:28px}.nav-links a{color:#596477;font-size:14px;font-weight:600}.nav-links a:hover{color:#635bff}.menu-button{display:none;border:0;background:transparent;font-size:27px;cursor:pointer}.page-layout{width:min(1400px,calc(100% - 30px));margin:28px auto 60px;display:flex;flex-direction:row;align-items:flex-start;gap:24px}.tool-content{min-width:0;flex:1;order:1}.page-layout>.tools-sidebar{order:2}.ad-slot{width:100%;min-height:10px;margin:0 auto 24px;padding:5px;display:flex;align-items:center;justify-content:center;text-align:center;overflow:hidden}.ad-slot iframe{display:block;max-width:100%!important;border:0}.ad-slot img{max-width:100%;height:auto}.desktop-ad{display:flex;width:100%;justify-content:center;align-items:center}.mobile-ad{display:none;width:100%;justify-content:center;align-items:center}.tool-header{margin-bottom:22px;padding:34px 25px;background:#fff;border:1px solid #e5e9f0;border-radius:22px;text-align:center;box-shadow:0 8px 30px rgba(30,35,80,.035)}.tool-badge{display:inline-block;margin-bottom:12px;padding:7px 13px;border-radius:50px;background:#eeedff;color:#635bff;font-size:12px;font-weight:800}.tool-header h1{font-size:clamp(32px,5vw,48px);line-height:1.08;letter-spacing:-1.8px}.tool-header h1 span{color:#635bff}.tool-header p{max-width:680px;margin:13px auto 0;color:#707b8e;font-size:14px}.converter-card{padding:28px;background:#fff;border:1px solid #e5e9f0;border-radius:22px;box-shadow:0 15px 45px rgba(30,35,80,.06)}.upload-area{width:100%;padding:55px 20px;border:2px dashed #d7dbea;border-radius:19px;background:#fafbff;text-align:center;cursor:pointer;transition:border-color .2s,background .2s,transform .2s}.upload-area:hover,.upload-area.dragover{border-color:#635bff;background:#f5f3ff;transform:translateY(-1px)}.upload-icon{width:66px;height:66px;display:grid;place-items:center;margin:0 auto 15px;border-radius:18px;background:#eeedff;color:#635bff;font-size:30px}.upload-area h2{margin-bottom:6px;font-size:20px}.upload-area p{color:#707b8e;font-size:13px}.file-info{margin-top:10px;color:#635bff!important;font-weight:700}#fileInput{display:none}.settings{display:none;margin-top:22px;padding:21px;background:#fafbff;border:1px solid #e5e9f0;border-radius:17px}.settings-title{margin-bottom:17px;font-size:16px;font-weight:800}.quality-top{display:flex;align-items:center;justify-content:space-between}.quality-top label{color:#4d5768;font-size:12px;font-weight:700}.quality-value{color:#635bff;font-size:15px;font-weight:800}#quality{width:100%;margin-top:12px;accent-color:#635bff}.actions{display:flex;justify-content:center;align-items:center;flex-wrap:wrap;gap:10px;margin-top:21px}.btn{display:inline-flex;align-items:center;justify-content:center;min-height:45px;padding:11px 19px;border:0;border-radius:12px;cursor:pointer;font-size:13px;font-weight:800;transition:background .2s,transform .2s,opacity .2s}.btn-primary{background:#635bff;color:white}.btn-primary:hover{background:#5148e8;transform:translateY(-1px)}.btn-secondary{background:#eef0f5;color:#3f4858}.btn-secondary:hover{background:#e4e7ed}.btn:disabled{opacity:.6;cursor:not-allowed}.error{display:none;margin-top:15px;padding:12px 14px;border-radius:11px;background:#fff0f0;color:#c33;font-size:13px;text-align:center}.result{display:none;margin-top:28px}.preview-box{padding:15px;background:#fafbff;border:1px solid #e5e9f0;border-radius:17px;text-align:center}.preview-box h3{margin-bottom:12px;font-size:14px}.preview-box img{display:block;width:100%;max-height:500px;object-fit:contain;border-radius:11px;background:white}.result-info{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:16px}.result-stat{padding:15px;border-radius:13px;background:#f6f7fb;text-align:center}.result-stat strong{display:block;color:#635bff;font-size:18px}.result-stat span{color:#707b8e;font-size:11px}.info-box{margin-top:24px;padding:25px;background:#fff;border:1px solid #e5e9f0;border-radius:19px}.info-box h2{margin-bottom:9px;font-size:21px}.info-box p,.info-box li{color:#707b8e;font-size:13px;line-height:1.7}.info-box ul{padding-left:20px}footer{padding:35px 20px;background:#151827;color:white;text-align:center}footer p{margin-top:6px;color:#aeb5c5;font-size:12px}@media(max-width:700px){.navbar{min-height:64px}.nav-links{display:none;position:absolute;top:64px;left:0;right:0;padding:16px;flex-direction:column;background:#fff;border-bottom:1px solid #e5e9f0}.nav-links.open{display:flex}.menu-button{display:block}.page-layout{width:calc(100% - 20px);margin:15px auto 40px;flex-direction:column;gap:18px}.tool-content,.page-layout>.tools-sidebar{width:100%}.page-layout>.tools-sidebar{position:relative;top:auto;max-height:none}.tool-header{padding:28px 18px}.tool-header h1{font-size:36px}.converter-card{padding:15px;border-radius:18px}.upload-area{padding:40px 15px}.result-info{grid-template-columns:1fr}.desktop-ad{display:none}.mobile-ad{display:flex}}
</style>
</head>
<body>
<?php require_once dirname(__DIR__) . '/header.php'; ?>







<main class="page-layout">
<section class="tool-content">
<div class="desktop-ad ad-slot"><?php smartToolzAd('top'); ?></div>
<div class="mobile-ad ad-slot"><?php smartToolzAd('mobile'); ?></div>
<div class="tool-header"><span class="tool-badge">IMAGE CONVERTER</span><h1>GIF to <span>JPG</span> Converter</h1><p>Convert GIF images to JPG online for free. Fast, simple and easy to use.</p></div>
<div class="converter-card">
<div class="upload-area" id="uploadArea">
<div class="upload-icon">🖼️</div><h2>Drop your GIF here</h2><p>or click to browse from your device</p><p class="file-info" id="fileInfo">GIF files only</p>
<input id="fileInput" type="file" accept="image/gif">
</div>
<div class="settings" id="settings"><div class="settings-title">Conversion Settings</div><div class="quality-top"><label for="quality">JPG Quality</label><span class="quality-value" id="qualityValue">90%</span></div><input id="quality" type="range" min="10" max="100" value="90"></div>
<div class="error" id="error"></div>
<div class="result" id="result"><div class="preview-box"><h3>JPG Preview</h3><img id="previewImage" alt="Converted JPG preview"></div><div class="result-info"><div class="result-stat"><strong id="originalSize">—</strong><span>Original GIF</span></div><div class="result-stat"><strong id="outputSize">—</strong><span>JPG Size</span></div><div class="result-stat"><strong id="sizeChange">—</strong><span>Size Change</span></div></div></div>
<div class="actions"><button class="btn btn-primary" id="convertBtn" disabled>Convert to JPG</button><button class="btn btn-secondary" id="downloadBtn" disabled>Download JPG</button><button class="btn btn-secondary" id="resetBtn" type="button">Choose Another</button></div>
</div>
<div class="info-box"><h2>GIF to JPG Converter</h2><p>Convert a GIF image into JPG format directly in your browser. The first frame of an animated GIF is used for the JPG output because JPG does not support animation.</p><br><h2>How to convert GIF to JPG</h2><ul><li>Upload or drag and drop a GIF image.</li><li>Choose the JPG quality.</li><li>Click Convert to JPG.</li><li>Preview and download your converted JPG image.</li></ul></div>
</section>
<?php require_once __DIR__ . '/tool-sidebar.php'; ?>
</main>

<script>
const input=document.getElementById('fileInput'),area=document.getElementById('uploadArea'),info=document.getElementById('fileInfo'),settings=document.getElementById('settings'),quality=document.getElementById('quality'),qualityValue=document.getElementById('qualityValue'),error=document.getElementById('error'),result=document.getElementById('result'),preview=document.getElementById('previewImage'),convert=document.getElementById('convertBtn'),download=document.getElementById('downloadBtn'),reset=document.getElementById('resetBtn'),originalSize=document.getElementById('originalSize'),outputSize=document.getElementById('outputSize'),sizeChange=document.getElementById('sizeChange');let file=null,outputBlob=null,outputUrl=null;
function size(n){if(n<1024)return n+' B';if(n<1048576)return (n/1024).toFixed(1)+' KB';return (n/1048576).toFixed(2)+' MB'}
function showError(msg){error.textContent=msg;error.style.display='block'}
function clearError(){error.style.display='none';error.textContent=''}
function choose(f){clearError();if(!f)return;if(f.type!=='image/gif'){showError('Please select a GIF image.');return}file=f;info.textContent=f.name+' • '+size(f.size);settings.style.display='block';convert.disabled=false;result.style.display='none';download.disabled=true}
input.addEventListener('change',()=>choose(input.files[0]));area.addEventListener('click',()=>input.click());['dragenter','dragover'].forEach(e=>area.addEventListener(e,x=>{x.preventDefault();area.classList.add('dragover')}));['dragleave','drop'].forEach(e=>area.addEventListener(e,x=>{x.preventDefault();area.classList.remove('dragover')}));area.addEventListener('drop',e=>choose(e.dataTransfer.files[0]));quality.addEventListener('input',()=>qualityValue.textContent=quality.value+'%');
convert.addEventListener('click',()=>{if(!file)return;clearError();const reader=new FileReader();reader.onload=()=>{const img=new Image();img.onload=()=>{const canvas=document.createElement('canvas');canvas.width=img.naturalWidth;canvas.height=img.naturalHeight;const ctx=canvas.getContext('2d');ctx.fillStyle='#fff';ctx.fillRect(0,0,canvas.width,canvas.height);ctx.drawImage(img,0,0);canvas.toBlob(blob=>{if(!blob){showError('Conversion failed. Please try another GIF.');return}if(outputUrl)URL.revokeObjectURL(outputUrl);outputBlob=blob;outputUrl=URL.createObjectURL(blob);preview.src=outputUrl;originalSize.textContent=size(file.size);outputSize.textContent=size(blob.size);const diff=((blob.size-file.size)/file.size)*100;sizeChange.textContent=(diff<=0?'':'↑ ')+Math.abs(diff).toFixed(1)+'%';result.style.display='block';download.disabled=false},'image/jpeg',Number(quality.value)/100)};img.onerror=()=>showError('Unable to read this GIF image.');img.src=reader.result};reader.onerror=()=>showError('Unable to read the selected file.');reader.readAsDataURL(file)});
download.addEventListener('click',()=>{if(!outputUrl)return;const a=document.createElement('a');a.href=outputUrl;a.download=(file?.name.replace(/\.gif$/i,'')||'converted')+'.jpg';document.body.appendChild(a);a.click();a.remove()});reset.addEventListener('click',()=>{if(outputUrl)URL.revokeObjectURL(outputUrl);file=null;outputBlob=null;outputUrl=null;input.value='';info.textContent='GIF files only';settings.style.display='none';result.style.display='none';convert.disabled=true;download.disabled=true;clearError()});
</script>








<?php require_once dirname(__DIR__) . '/footer.php'; ?>
</body>
</html>
