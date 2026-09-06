<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/lib/tool-page.php';

$tool = [
    'title' => 'JPG to PNG Converter Online — Free',
    'description' => 'Convert JPG and JPEG images to PNG online for free. Preview and download your converted image directly in your browser.',
    'url' => 'https://smarttoolz.in/tools/jpg-to-png/'
];
smarttoolz_tool_page_start($tool);
?>
<main class="converter-page">
  <section class="converter-head">
    <span class="eyebrow">JPG TO PNG</span>
    <h1>Convert JPG to PNG</h1>
    <p>Turn your JPG or JPEG image into a PNG in seconds. Everything happens in your browser.</p>
  </section>

  <section class="converter-card" aria-label="JPG to PNG converter">
    <div class="upload-step" id="uploadStep">
      <label class="upload-box" for="file">
        <span class="upload-icon" aria-hidden="true">↑</span>
        <strong>Drop your JPG here</strong>
        <small>JPG or JPEG · up to 20 MB</small>
        <span class="upload-button">Select Image</span>
      </label>
      <input id="file" type="file" accept="image/jpeg,.jpg,.jpeg" hidden>
    </div>

    <div class="work-area" id="workArea" hidden>
      <div class="preview-panel">
        <div class="preview-title"><span>Original preview</span><small id="dimensions">—</small></div>
        <div class="preview-large"><img id="preview" alt="Selected JPG preview"></div>
      </div>

      <div class="file-line">
        <div class="file-info">
          <strong id="fileName">Image</strong>
          <span id="fileMeta">Ready to convert</span>
          <button class="change-button" id="changeFile" type="button">Choose another</button>
        </div>
      </div>

      <button id="convert" class="convert-button" type="button">Convert to PNG</button>
      <p id="status" class="status" aria-live="polite">Ready to convert.</p>

      <div class="result" id="result" hidden>
        <div class="result-thumb"><img id="resultPreview" alt="Converted PNG preview"></div>
        <div class="result-info">
          <span class="result-label">PNG ready</span>
          <strong id="resultSize">—</strong>
          <small id="resultMeta">—</small>
        </div>
        <a id="download" class="download-button" download="smarttoolz-converted.png">Download PNG</a>
      </div>
    </div>
  </section>

  <section class="simple-help-grid">
    <article><h2>How to convert JPG to PNG</h2><ol><li>Select or drop your JPG/JPEG image.</li><li>Check the preview and image dimensions.</li><li>Click Convert to PNG.</li><li>Preview and download the PNG file.</li></ol></article>
    <article><h2>Private and browser-based</h2><p>The conversion is performed locally using your browser's image canvas. Your JPG does not need to be uploaded to a conversion server.</p></article>
  </section>
</main>

<style>
.converter-page{width:min(920px,calc(100% - 32px));margin:0 auto 70px}.converter-head{text-align:center;padding:48px 0 24px}.converter-head h1{margin:14px 0 8px;font-size:clamp(38px,6vw,56px);line-height:1;letter-spacing:-2.5px}.converter-head p{max-width:650px;margin:0 auto;color:#667085;font-size:15px}.converter-card{padding:26px;background:#fff;border:1px solid #e7eaf0;border-radius:24px;box-shadow:0 18px 50px rgba(16,24,40,.07)}.upload-box{min-height:240px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;border:2px dashed #d9def0;border-radius:20px;background:#fbfbff;text-align:center;cursor:pointer;transition:.2s}.upload-box:hover,.upload-box.drag{border-color:#a9a0ff;background:#f7f6ff}.upload-icon{width:52px;height:52px;display:grid;place-items:center;margin-bottom:5px;border-radius:16px;background:#eeeaff;color:#5b43ff;font-size:27px;font-weight:900}.upload-box strong{font-size:20px;letter-spacing:-.4px}.upload-box small{color:#7b849d;font-size:12px}.upload-button{margin-top:8px;padding:10px 17px;border-radius:11px;background:#5c46ff;color:#fff;font-size:12px;font-weight:800}.preview-panel{margin-bottom:14px}.preview-title{display:flex;justify-content:space-between;align-items:center;margin:0 2px 8px;font-size:12px;font-weight:800}.preview-title small{color:#7b849d;font-size:10px;font-weight:500}.preview-large{height:310px;display:grid;place-items:center;padding:12px;border:1px solid #e7eaf0;border-radius:15px;background:repeating-conic-gradient(#edf0f6 0 25%,#fff 0 50%) 50%/18px 18px;overflow:hidden}.preview-large img{display:block;max-width:100%;max-height:100%;object-fit:contain;border-radius:8px}.file-line{padding:12px 14px;border:1px solid #e7eaf0;border-radius:15px;background:#fafbff}.file-info{display:flex;align-items:center;gap:10px;min-width:0}.file-info strong{font-size:13px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.file-info span{color:#7b849d;font-size:10px;white-space:nowrap}.change-button{margin-left:auto;padding:0;border:0;background:transparent;color:#5541ff;font-size:10px;font-weight:800;cursor:pointer;white-space:nowrap}.convert-button{width:100%;height:50px;margin-top:16px;border:0;border-radius:13px;background:linear-gradient(90deg,#5d46ff,#3e7dff);color:#fff;font-size:13px;font-weight:900;cursor:pointer;box-shadow:0 10px 22px rgba(80,69,255,.18)}.convert-button:disabled{opacity:.6;cursor:not-allowed}.status{margin:10px 0 0;color:#69738e;text-align:center;font-size:10px;min-height:16px}.result{display:grid;grid-template-columns:64px minmax(0,1fr) auto;align-items:center;gap:13px;margin-top:14px;padding:10px;border:1px solid #ddd9ff;border-radius:15px;background:#f8f7ff}.result-thumb{width:64px;height:64px;display:grid;place-items:center;overflow:hidden;border-radius:11px;background:repeating-conic-gradient(#edf0f6 0 25%,#fff 0 50%) 50%/12px 12px}.result-thumb img{display:block;width:100%;height:100%;object-fit:contain}.result-info{display:flex;flex-direction:column;gap:2px}.result-label{font-size:8px;font-weight:900;letter-spacing:.7px;text-transform:uppercase;color:#6658db}.result strong{font-size:16px;letter-spacing:-.2px}.result small{color:#667085;font-size:10px}.download-button{padding:10px 14px;border-radius:10px;background:#111936;color:#fff;font-size:11px;font-weight:800;text-decoration:none;white-space:nowrap}.simple-help-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:18px}.simple-help-grid article{padding:22px;border:1px solid #e7eaf0;border-radius:18px;background:#fff}.simple-help-grid h2{margin:0 0 10px;font-size:17px}.simple-help-grid p,.simple-help-grid li{color:#667085;font-size:12px;line-height:1.75}.simple-help-grid ol{margin:0;padding-left:20px}@media(max-width:650px){.converter-page{width:calc(100% - 20px)}.converter-card{padding:16px}.upload-box{min-height:220px}.preview-large{height:250px}.file-info{flex-wrap:wrap}.change-button{margin-left:0}.result{grid-template-columns:56px 1fr}.result-thumb{width:56px;height:56px}.download-button{grid-column:1/-1;text-align:center}}
</style>

<script>
(()=>{
 const file=document.getElementById('file'),uploadStep=document.getElementById('uploadStep'),uploadBox=document.querySelector('.upload-box'),workArea=document.getElementById('workArea'),preview=document.getElementById('preview'),resultPreview=document.getElementById('resultPreview'),dimensions=document.getElementById('dimensions'),fileName=document.getElementById('fileName'),fileMeta=document.getElementById('fileMeta'),changeFile=document.getElementById('changeFile'),convert=document.getElementById('convert'),status=document.getElementById('status'),result=document.getElementById('result'),resultSize=document.getElementById('resultSize'),resultMeta=document.getElementById('resultMeta'),download=document.getElementById('download');
 let selected=null,previewUrl=null,downloadUrl=null;
 const sizeText=n=>n<1048576?(n/1024).toFixed(1)+' KB':(n/1048576).toFixed(1)+' MB';
 function selectFile(f){
   if(!f)return;
   if(!['image/jpeg'].includes(f.type) && !/\.(jpe?g)$/i.test(f.name)){status.textContent='Please choose a JPG or JPEG image.';return}
   if(f.size>20*1024*1024){status.textContent='Please choose an image under 20 MB.';return}
   selected=f;if(previewUrl)URL.revokeObjectURL(previewUrl);previewUrl=URL.createObjectURL(f);preview.src=previewUrl;fileName.textContent=f.name;fileMeta.textContent=sizeText(f.size)+' · Ready to convert';
   const image=new Image();image.onload=()=>dimensions.textContent=image.width+' × '+image.height+' px';image.src=previewUrl;
   uploadStep.hidden=true;workArea.hidden=false;result.hidden=true;status.textContent='Preview ready. Click Convert to PNG.';
 }
 file.addEventListener('change',()=>selectFile(file.files[0]));
 changeFile.addEventListener('click',()=>file.click());
 ['dragenter','dragover'].forEach(e=>uploadBox.addEventListener(e,ev=>{ev.preventDefault();uploadBox.classList.add('drag')}));
 ['dragleave','drop'].forEach(e=>uploadBox.addEventListener(e,ev=>{ev.preventDefault();uploadBox.classList.remove('drag')}));
 uploadBox.addEventListener('drop',ev=>selectFile(ev.dataTransfer.files[0]));
 convert.addEventListener('click',()=>{
   if(!selected){status.textContent='Please choose a JPG image first.';return}
   convert.disabled=true;result.hidden=true;status.textContent='Converting…';
   const image=new Image(),sourceUrl=URL.createObjectURL(selected);
   image.onload=()=>{URL.revokeObjectURL(sourceUrl);const canvas=document.createElement('canvas');canvas.width=image.naturalWidth;canvas.height=image.naturalHeight;const ctx=canvas.getContext('2d');if(!ctx){status.textContent='Your browser could not process this image.';convert.disabled=false;return}ctx.clearRect(0,0,canvas.width,canvas.height);ctx.drawImage(image,0,0);canvas.toBlob(blob=>{convert.disabled=false;if(!blob){status.textContent='Conversion failed. Please try another JPG.';return}if(downloadUrl)URL.revokeObjectURL(downloadUrl);downloadUrl=URL.createObjectURL(blob);resultPreview.src=downloadUrl;download.href=downloadUrl;download.download=(selected.name.replace(/\.[^.]+$/,'')||'converted')+'.png';resultSize.textContent=sizeText(blob.size);resultMeta.textContent='PNG · '+canvas.width+' × '+canvas.height+' px';result.hidden=false;status.textContent='Converted successfully. Your PNG is ready.'},'image/png')};
   image.onerror=()=>{URL.revokeObjectURL(sourceUrl);status.textContent='This JPG could not be read.';convert.disabled=false};image.src=sourceUrl;
 });
})();
</script>
<?php smarttoolz_tool_page_end(); ?>
