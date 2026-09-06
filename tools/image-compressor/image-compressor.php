<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/lib/tool-page.php';

$tool = [
    'title' => 'Free Image Compressor Online — JPG, PNG & WebP',
    'description' => 'Compress JPG, PNG and WebP images online for free. Reduce image file size in your browser with SmartToolz.',
    'url' => 'https://smarttoolz.in/tools/image-compressor/'
];
smarttoolz_tool_page_start($tool);
?>
<main class="compressor-page">
  <section class="compressor-head">
    <span class="eyebrow">IMAGE COMPRESSOR</span>
    <h1>Compress your image</h1>
    <p>Choose an image, preview it, adjust quality only when needed, then download the smaller file.</p>
  </section>

  <section class="compressor-card" aria-label="Image compressor">
    <div class="upload-step" id="uploadStep">
      <label class="upload-box" for="file">
        <span class="upload-icon" aria-hidden="true">↑</span>
        <strong>Choose an image</strong>
        <small>JPG, PNG or WebP · up to 20 MB</small>
        <span class="upload-button">Select Image</span>
      </label>
      <input id="file" type="file" accept="image/jpeg,image/png,image/webp" hidden>
    </div>

    <div class="work-area" id="workArea" hidden>
      <div class="preview-panel">
        <div class="preview-title"><span>Preview</span><small id="dimensions">—</small></div>
        <div class="preview-large"><img id="preview" alt="Selected image preview"></div>
      </div>

      <div class="file-line">
        <div class="file-info">
          <strong id="fileName">Image</strong>
          <span id="fileMeta">Ready to compress</span>
          <button class="change-button" id="changeFile" type="button">Choose another</button>
        </div>
      </div>

      <div class="quality-control" id="qualityControl" hidden>
        <div class="control-head"><label for="quality">Quality</label><output id="qualityValue">75%</output></div>
        <input id="quality" type="range" min="10" max="100" value="75">
        <div class="range-labels"><span>Smaller file</span><span>Better quality</span></div>
      </div>

      <button id="compress" class="compress-button" type="button">Compress Image</button>
      <p id="status" class="status" aria-live="polite">Ready to compress.</p>

      <div class="result" id="result" hidden>
        <div class="result-preview"><img id="resultPreview" alt="Compressed image preview"></div>
        <div class="result-info">
          <span class="result-label">Compressed</span>
          <strong id="resultSize">—</strong>
          <small id="resultSaving">—</small>
        </div>
        <a id="download" class="download-button" download="smarttoolz-compressed-image.jpg">Download Image</a>
      </div>
    </div>
  </section>

  <section class="simple-help-grid">
    <article>
      <h2>How it works</h2>
      <ol>
        <li>Select your image.</li>
        <li>Preview it and set quality when needed.</li>
        <li>Press Compress Image.</li>
        <li>Preview and download the result.</li>
      </ol>
    </article>
    <article>
      <h2>Good to know</h2>
      <p>Your image is processed in the browser. The controls appear only when they are useful, keeping the main screen simple.</p>
    </article>
  </section>
</main>

<style>
.compressor-page{width:min(920px,calc(100% - 32px));margin:0 auto 70px}.compressor-head{text-align:center;padding:48px 0 24px}.compressor-head h1{margin:14px 0 8px;font-size:clamp(38px,6vw,56px);line-height:1;letter-spacing:-2.5px}.compressor-head p{max-width:650px;margin:0 auto;color:#667085;font-size:15px}.compressor-card{padding:26px;background:#fff;border:1px solid #e7eaf0;border-radius:24px;box-shadow:0 18px 50px rgba(16,24,40,.07)}.upload-box{min-height:280px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;border:2px dashed #d9def0;border-radius:20px;background:#fbfbff;text-align:center;cursor:pointer;transition:.2s}.upload-box:hover{border-color:#a9a0ff;background:#f7f6ff}.upload-icon{width:58px;height:58px;display:grid;place-items:center;margin-bottom:5px;border-radius:18px;background:#eeeaff;color:#5b43ff;font-size:30px;font-weight:900}.upload-box strong{font-size:21px;letter-spacing:-.4px}.upload-box small{color:#7b849d;font-size:12px}.upload-button{margin-top:8px;padding:11px 18px;border-radius:12px;background:#5c46ff;color:#fff;font-size:13px;font-weight:800}.work-area{display:block}.preview-panel{padding:12px;border:1px solid #e7eaf0;border-radius:18px;background:#f8f9fd}.preview-title{display:flex;align-items:center;justify-content:space-between;margin:2px 2px 10px;color:#1d2748;font-size:12px;font-weight:850}.preview-title small{color:#7b849d;font-size:10px;font-weight:600}.preview-large{height:min(430px,55vw);min-height:240px;display:grid;place-items:center;border-radius:13px;overflow:hidden;background:repeating-conic-gradient(#edf0f6 0 25%,#fff 0 50%) 50%/22px 22px}.preview-large img{display:block;width:100%;height:100%;object-fit:contain}.file-line{display:flex;align-items:center;gap:16px;margin-top:14px;padding:14px;border:1px solid #e7eaf0;border-radius:16px;background:#fafbff}.file-info{display:flex;flex-direction:column;min-width:0;gap:3px}.file-info strong{font-size:14px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:650px}.file-info span{color:#7b849d;font-size:11px}.change-button{width:max-content;margin-top:4px;padding:0;border:0;background:transparent;color:#5541ff;font-size:11px;font-weight:800;cursor:pointer}.quality-control{margin-top:16px;padding:18px;border:1px solid #e7eaf0;border-radius:16px;background:#fff}.control-head{display:flex;align-items:center;justify-content:space-between;gap:10px}.control-head label{font-size:13px;font-weight:800}.control-head output{padding:5px 9px;border-radius:999px;background:#f0efff;color:#5b43ff;font-size:11px;font-weight:900}.quality-control input{width:100%;margin:12px 0 0;accent-color:#5c46ff}.range-labels{display:flex;justify-content:space-between;margin-top:3px;color:#8a93aa;font-size:10px}.compress-button{width:100%;height:52px;margin-top:18px;border:0;border-radius:14px;background:linear-gradient(90deg,#5d46ff,#3e7dff);color:#fff;font-size:14px;font-weight:900;cursor:pointer;box-shadow:0 10px 22px rgba(80,69,255,.18)}.compress-button:disabled{opacity:.6;cursor:not-allowed}.status{margin:12px 0 0;color:#69738e;text-align:center;font-size:11px;min-height:18px}.result{display:grid;grid-template-columns:84px 1fr auto;align-items:center;gap:14px;margin-top:16px;padding:14px;border:1px solid #ddd9ff;border-radius:16px;background:#f8f7ff}.result-preview{width:84px;height:84px;border-radius:12px;overflow:hidden;background:#edf0f6}.result-preview img{display:block;width:100%;height:100%;object-fit:cover}.result-info{display:flex;flex-direction:column;gap:2px}.result-label{font-size:9px;font-weight:900;letter-spacing:.8px;text-transform:uppercase;color:#6658db}.result strong{font-size:18px;letter-spacing:-.3px}.result small{color:#667085;font-size:11px}.download-button{padding:11px 16px;border-radius:11px;background:#111936;color:#fff;font-size:12px;font-weight:800;text-decoration:none;white-space:nowrap}.simple-help-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:18px}.simple-help-grid article{padding:22px;border:1px solid #e7eaf0;border-radius:18px;background:#fff}.simple-help-grid h2{margin:0 0 10px;font-size:17px}.simple-help-grid p,.simple-help-grid li{color:#667085;font-size:12px;line-height:1.75}.simple-help-grid ol{margin:0;padding-left:20px}@media(max-width:650px){.compressor-page{width:calc(100% - 20px)}.compressor-card{padding:16px}.upload-box{min-height:230px}.preview-large{height:62vw;min-height:210px}.result{grid-template-columns:64px 1fr}.result-preview{width:64px;height:64px}.download-button{grid-column:1/-1;text-align:center}.simple-help-grid{grid-template-columns:1fr}}
</style>

<script>
(()=>{
 const file=document.getElementById('file');
 const uploadStep=document.getElementById('uploadStep');
 const workArea=document.getElementById('workArea');
 const preview=document.getElementById('preview');
 const resultPreview=document.getElementById('resultPreview');
 const dimensions=document.getElementById('dimensions');
 const fileName=document.getElementById('fileName');
 const fileMeta=document.getElementById('fileMeta');
 const changeFile=document.getElementById('changeFile');
 const qualityControl=document.getElementById('qualityControl');
 const quality=document.getElementById('quality');
 const qualityValue=document.getElementById('qualityValue');
 const compress=document.getElementById('compress');
 const status=document.getElementById('status');
 const result=document.getElementById('result');
 const resultSize=document.getElementById('resultSize');
 const resultSaving=document.getElementById('resultSaving');
 const download=document.getElementById('download');
 let selected=null;
 let previewUrl=null;
 let downloadUrl=null;

 const sizeText=n=>n<1024*1024?(n/1024).toFixed(1)+' KB':(n/1024/1024).toFixed(1)+' MB';
 quality.addEventListener('input',()=>qualityValue.textContent=quality.value+'%');
 file.addEventListener('change',()=>{
   const f=file.files[0];
   if(!f)return;
   if(!['image/jpeg','image/png','image/webp'].includes(f.type)){file.value='';status.textContent='Please choose a JPG, PNG or WebP image.';return}
   if(f.size>20*1024*1024){file.value='';status.textContent='Please choose an image under 20 MB.';return}
   selected=f;
   if(previewUrl)URL.revokeObjectURL(previewUrl);
   previewUrl=URL.createObjectURL(f);
   preview.src=previewUrl;
   fileName.textContent=f.name;
   fileMeta.textContent=sizeText(f.size)+' · Ready to compress';
   const img=new Image();
   img.onload=()=>dimensions.textContent=img.width+' × '+img.height+' px';
   img.src=previewUrl;
   uploadStep.hidden=true;
   workArea.hidden=false;
   qualityControl.hidden=false;
   result.hidden=true;
   status.textContent='Preview ready. Adjust quality or compress now.';
 });
 changeFile.addEventListener('click',()=>file.click());
 compress.addEventListener('click',()=>{
   if(!selected){status.textContent='Please choose an image first.';return}
   compress.disabled=true;
   result.hidden=true;
   status.textContent='Compressing…';
   const img=new Image();
   const sourceUrl=URL.createObjectURL(selected);
   img.onload=()=>{
     URL.revokeObjectURL(sourceUrl);
     const canvas=document.createElement('canvas');
     const max=2400;
     const scale=Math.min(1,max/Math.max(img.width,img.height));
     canvas.width=Math.max(1,Math.round(img.width*scale));
     canvas.height=Math.max(1,Math.round(img.height*scale));
     const ctx=canvas.getContext('2d');
     if(!ctx){status.textContent='Your browser could not process this image.';compress.disabled=false;return}
     ctx.drawImage(img,0,0,canvas.width,canvas.height);
     canvas.toBlob(blob=>{
       compress.disabled=false;
       if(!blob){status.textContent='This image could not be compressed.';return}
       if(downloadUrl)URL.revokeObjectURL(downloadUrl);
       downloadUrl=URL.createObjectURL(blob);
       download.href=downloadUrl;
       resultPreview.src=downloadUrl;
       resultSize.textContent=sizeText(blob.size);
       const saved=selected.size?Math.max(0,100-(blob.size/selected.size*100)):0;
       resultSaving.textContent=(saved.toFixed(0))+'% smaller · '+canvas.width+' × '+canvas.height+' px';
       result.hidden=false;
       status.textContent='Done. Preview the result or download it.';
     },'image/jpeg',Number(quality.value)/100);
   };
   img.onerror=()=>{URL.revokeObjectURL(sourceUrl);status.textContent='This file is not a supported image.';compress.disabled=false};
   img.src=sourceUrl;
 });
})();
</script>
<?php smarttoolz_tool_page_end(); ?>
