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
<title>JPG to WebP Converter - SmartToolz</title>
<meta name="description" content="Convert JPG and JPEG images to WebP online for free with SmartToolz. Fast browser-based image conversion with quality control.">
<meta name="robots" content="index, follow">
<style>
*{margin:0;padding:0;box-sizing:border-box}html{scroll-behavior:smooth}body{font-family:Inter,-apple-system,BlinkMacSystemFont,"Segoe UI",Arial,sans-serif;background:#f6f8fc;color:#172033;line-height:1.5}a{text-decoration:none;color:inherit}button,input{font-family:inherit}.site-header{position:sticky;top:0;z-index:1000;background:rgba(255,255,255,.96);backdrop-filter:blur(14px);border-bottom:1px solid #e5e9f0}.navbar{width:calc(100% - 20px);max-width:1400px;min-height:72px;margin:auto;display:flex;align-items:center;justify-content:space-between}.logo{display:flex;align-items:center;gap:10px;color:#172033;font-size:21px;font-weight:800}.logo-icon{width:42px;height:42px;display:grid;place-items:center;border-radius:13px;color:white;background:linear-gradient(135deg,#635bff,#916cff);box-shadow:0 8px 22px rgba(99,91,255,.18)}.nav-links{display:flex;align-items:center;gap:28px}.nav-links a{color:#596477;font-size:14px;font-weight:600}.nav-links a:hover{color:#635bff}.menu-button{display:none;border:0;background:transparent;font-size:27px;cursor:pointer}.page-layout{width:min(1400px,calc(100% - 30px));margin:28px auto 60px;display:flex;flex-direction:row;align-items:flex-start;gap:24px}.tool-content{min-width:0;flex:1;order:1}.page-layout>.tools-sidebar{order:2}.ad-slot{width:100%;min-height:10px;margin:0 auto 24px;padding:5px;display:flex;align-items:center;justify-content:center;text-align:center;overflow:hidden}.ad-slot iframe{display:block;max-width:100%!important;border:0}.ad-slot img{max-width:100%;height:auto}.desktop-ad{display:flex;width:100%;justify-content:center;align-items:center}.mobile-ad{display:none;width:100%;justify-content:center;align-items:center}.tool-header{margin-bottom:22px;padding:34px 25px;background:#fff;border:1px solid #e5e9f0;border-radius:22px;text-align:center;box-shadow:0 8px 30px rgba(30,35,80,.035)}.tool-badge{display:inline-block;margin-bottom:12px;padding:7px 13px;border-radius:50px;background:#eeedff;color:#635bff;font-size:12px;font-weight:800}.tool-header h1{font-size:clamp(32px,5vw,48px);line-height:1.08;letter-spacing:-1.8px}.tool-header h1 span{color:#635bff}.tool-header p{max-width:680px;margin:13px auto 0;color:#707b8e;font-size:14px}.converter-card{padding:28px;background:#fff;border:1px solid #e5e9f0;border-radius:22px;box-shadow:0 15px 45px rgba(30,35,80,.06)}.upload-area{width:100%;padding:55px 20px;border:2px dashed #d7dbea;border-radius:19px;background:#fafbff;text-align:center;cursor:pointer;transition:border-color .2s,background .2s,transform .2s}.upload-area:hover,.upload-area.dragover{border-color:#635bff;background:#f5f3ff;transform:translateY(-1px)}.upload-icon{width:66px;height:66px;display:grid;place-items:center;margin:0 auto 15px;border-radius:18px;background:#eeedff;color:#635bff;font-size:30px}.upload-area h2{margin-bottom:6px;font-size:20px}.upload-area p{color:#707b8e;font-size:13px}.file-info{margin-top:10px;color:#635bff!important;font-weight:700}#fileInput{display:none}.settings{display:none;margin-top:22px;padding:21px;background:#fafbff;border:1px solid #e5e9f0;border-radius:17px}.settings-title{margin-bottom:17px;font-size:16px;font-weight:800}.quality-top{display:flex;align-items:center;justify-content:space-between}.quality-top label{color:#4d5768;font-size:12px;font-weight:700}.quality-value{color:#635bff;font-size:15px;font-weight:800}#quality{width:100%;margin-top:12px;accent-color:#635bff}.actions{display:flex;justify-content:center;align-items:center;flex-wrap:wrap;gap:10px;margin-top:21px}.btn{display:inline-flex;align-items:center;justify-content:center;min-height:45px;padding:11px 19px;border:0;border-radius:12px;cursor:pointer;font-size:13px;font-weight:800;transition:background .2s,transform .2s,opacity .2s}.btn-primary{background:#635bff;color:#fff}.btn-primary:hover{background:#5148e8;transform:translateY(-1px)}.btn-secondary{background:#eef0f5;color:#3f4858}.btn-secondary:hover{background:#e4e7ed}.btn:disabled{opacity:.6;cursor:not-allowed}.error{display:none;margin-top:15px;padding:12px 14px;border-radius:11px;background:#fff0f0;color:#c33;font-size:13px;text-align:center}.result{display:none;margin-top:28px}.preview-box{padding:15px;background:#fafbff;border:1px solid #e5e9f0;border-radius:17px;text-align:center}.preview-box h3{margin-bottom:12px;font-size:14px}.preview-box img{display:block;width:100%;max-height:500px;object-fit:contain;border-radius:11px;background:white}.result-info{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:16px}.result-stat{padding:15px;border-radius:13px;background:#f6f7fb;text-align:center}.result-stat strong{display:block;color:#635bff;font-size:18px}.result-stat span{color:#707b8e;font-size:11px}.info-box{margin-top:24px;padding:25px;background:#fff;border:1px solid #e5e9f0;border-radius:19px}.info-box h2{margin-bottom:9px;font-size:21px}.info-box h3{margin:18px 0 7px;font-size:16px}.info-box p,.info-box li{color:#707b8e;font-size:13px;line-height:1.7}.info-box ul{padding-left:20px}.tool-footer{margin-top:24px;text-align:center;color:#707b8e;font-size:12px}footer{padding:35px 20px;background:#151827;color:#fff;text-align:center}footer p{margin-top:6px;color:#aeb5c5;font-size:12px}@media(max-width:700px){.navbar{min-height:64px}.nav-links{display:none;position:absolute;top:64px;left:0;right:0;padding:16px;flex-direction:column;background:#fff;border-bottom:1px solid #e5e9f0}.nav-links.open{display:flex}.menu-button{display:block}.page-layout{width:calc(100% - 20px);margin:15px auto 40px;flex-direction:column;gap:18px}.tool-content{width:100%}.mobile-ad{display:flex}.desktop-ad{display:none}.tool-header{padding:28px 18px}.tool-header h1{font-size:36px}.converter-card{padding:15px;border-radius:18px}.upload-area{padding:42px 15px}.result-info{grid-template-columns:1fr}.tools-sidebar{width:100%!important;position:relative!important;top:auto!important;max-height:none!important}}
</style>
</head>
<body>
<?php require_once dirname(__DIR__) . '/header.php'; ?>





<div class="page-layout">
<main class="tool-content">
<div class="desktop-ad ad-slot"></div>
<div class="mobile-ad ad-slot"></div>

<section class="tool-header">
<span class="tool-badge">IMAGE CONVERTER</span>
<h1>JPG to <span>WebP</span> Converter</h1>
<p>Convert JPG and JPEG images to WebP online for free. Fast browser-based conversion with adjustable quality.</p>
</section>

<section class="converter-card">
<div class="upload-area" id="uploadArea">
<div class="upload-icon">🖼️</div>
<h2>Drop your JPG image here</h2>
<p>or click to choose a JPG or JPEG file</p>
<p class="file-info" id="fileInfo">Maximum browser-supported image size</p>
<input id="fileInput" type="file" accept="image/jpeg,.jpg,.jpeg">
</div>

<div class="error" id="errorBox"></div>

<div class="settings" id="settings">
<div class="settings-title">WebP Settings</div>
<div class="quality-top"><label for="quality">Output Quality</label><span class="quality-value" id="qualityValue">85%</span></div>
<input id="quality" type="range" min="10" max="100" value="85">
<div class="actions"><button class="btn btn-primary" id="convertBtn" type="button">Convert to WebP</button><button class="btn btn-secondary" id="resetBtn" type="button">Choose Another</button></div>
</div>

<div class="result" id="result">
<div class="preview-box"><h3>Converted WebP Preview</h3><img id="resultImage" alt="Converted WebP preview"></div>
<div class="result-info"><div class="result-stat"><strong id="originalSize">—</strong><span>Original JPG</span></div><div class="result-stat"><strong id="webpSize">—</strong><span>WebP Size</span></div><div class="result-stat"><strong id="saving">—</strong><span>Size Change</span></div></div>
<div class="actions"><button class="btn btn-primary" id="downloadBtn" type="button">Download WebP</button><button class="btn btn-secondary" id="resetBtn2" type="button">Convert Another</button></div>
</div>
</section>

<section class="info-box">
<h2>JPG to WebP Converter</h2>
<p>WebP is a modern image format that can provide smaller files while maintaining good visual quality. This SmartToolz converter processes your image in your browser, so the image does not need to be uploaded to a conversion server.</p>
<h3>How to convert JPG to WebP</h3>
<ul><li>Select or drag a JPG/JPEG image into the upload box.</li><li>Choose your preferred WebP quality.</li><li>Click Convert to WebP.</li><li>Preview the result and download your WebP image.</li></ul>
<h3>Why use WebP?</h3>
<p>WebP is widely supported by modern browsers and is useful when you want efficient image delivery for websites and online content.</p>
</section>
</main>

<?php require_once __DIR__ . '/tool-sidebar.php'; ?>
</div>


<script>
const uploadArea=document.getElementById('uploadArea');
const fileInput=document.getElementById('fileInput');
const fileInfo=document.getElementById('fileInfo');
const settings=document.getElementById('settings');
const quality=document.getElementById('quality');
const qualityValue=document.getElementById('qualityValue');
const convertBtn=document.getElementById('convertBtn');
const resetBtn=document.getElementById('resetBtn');
const resetBtn2=document.getElementById('resetBtn2');
const result=document.getElementById('result');
const resultImage=document.getElementById('resultImage');
const downloadBtn=document.getElementById('downloadBtn');
const errorBox=document.getElementById('errorBox');
let selectedFile=null, convertedUrl=null;

function showError(msg){errorBox.textContent=msg;errorBox.style.display='block'}
function clearError(){errorBox.textContent='';errorBox.style.display='none'}
function formatBytes(bytes){if(!bytes)return'0 B';const units=['B','KB','MB','GB'];const i=Math.min(Math.floor(Math.log(bytes)/Math.log(1024)),units.length-1);return(bytes/Math.pow(1024,i)).toFixed(i?1:0)+' '+units[i]}
function resetTool(){if(convertedUrl)URL.revokeObjectURL(convertedUrl);convertedUrl=null;selectedFile=null;fileInput.value='';fileInfo.textContent='Maximum browser-supported image size';settings.style.display='none';result.style.display='none';clearError()}
function selectFile(file){clearError();if(!file)return;if(!['image/jpeg','image/jpg'].includes(file.type)&&!/^image\/jpeg$/i.test(file.type)){showError('Please select a JPG or JPEG image.');return}selectedFile=file;fileInfo.textContent=file.name+' • '+formatBytes(file.size);settings.style.display='block';result.style.display='none'}

uploadArea.addEventListener('click',()=>fileInput.click());
fileInput.addEventListener('change',e=>selectFile(e.target.files[0]));
['dragenter','dragover'].forEach(ev=>uploadArea.addEventListener(ev,e=>{e.preventDefault();uploadArea.classList.add('dragover')}));
['dragleave','drop'].forEach(ev=>uploadArea.addEventListener(ev,e=>{e.preventDefault();uploadArea.classList.remove('dragover')}));
uploadArea.addEventListener('drop',e=>selectFile(e.dataTransfer.files[0]));
quality.addEventListener('input',()=>qualityValue.textContent=quality.value+'%');
resetBtn.addEventListener('click',resetTool);resetBtn2.addEventListener('click',resetTool);

convertBtn.addEventListener('click',()=>{
clearError();if(!selectedFile){showError('Please select a JPG image first.');return}
const reader=new FileReader();
reader.onload=e=>{const img=new Image();img.onload=()=>{const canvas=document.createElement('canvas');canvas.width=img.naturalWidth;canvas.height=img.naturalHeight;const ctx=canvas.getContext('2d');ctx.drawImage(img,0,0);canvas.toBlob(blob=>{if(!blob){showError('WebP conversion is not supported by this browser.');return}if(convertedUrl)URL.revokeObjectURL(convertedUrl);convertedUrl=URL.createObjectURL(blob);resultImage.src=convertedUrl;document.getElementById('originalSize').textContent=formatBytes(selectedFile.size);document.getElementById('webpSize').textContent=formatBytes(blob.size);const diff=((1-blob.size/selectedFile.size)*100);document.getElementById('saving').textContent=(diff>=0?diff.toFixed(1)+'% smaller':Math.abs(diff).toFixed(1)+'% larger');result.style.display='block';result.scrollIntoView({behavior:'smooth',block:'nearest'})},'image/webp',Number(quality.value)/100)};img.onerror=()=>showError('Unable to read this JPG image.');img.src=e.target.result};reader.readAsDataURL(selectedFile);
});

downloadBtn.addEventListener('click',()=>{if(!convertedUrl)return;const a=document.createElement('a');a.href=convertedUrl;const base=selectedFile?selectedFile.name.replace(/\.(jpe?g)$/i,''):'converted';a.download=base+'.webp';document.body.appendChild(a);a.click();a.remove()});

document.getElementById('menuButton').addEventListener('click',()=>document.getElementById('navLinks').classList.toggle('open'));
</script>





<?php require_once dirname(__DIR__) . '/footer.php'; ?>
</body>
</html>
