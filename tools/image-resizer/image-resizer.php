<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/lib/tool-page.php';

$tool = [
    'title' => 'Free Image Resizer Online — Resize JPG, PNG & WebP',
    'description' => 'Resize JPG, PNG and WebP images online for free. Preview, set dimensions and download the resized image in your browser.',
    'url' => 'https://smarttoolz.in/tools/image-resizer/'
];
smarttoolz_tool_page_start($tool);
?>
<main class="resizer-page">
  <section class="resizer-head">
    <span class="eyebrow">IMAGE RESIZER</span>
    <h1>Resize your image</h1>
    <p>Select an image, choose the size you need, preview it, and download the result.</p>
  </section>

  <section class="resizer-card" aria-label="Image resizer">
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
          <span id="fileMeta">Ready to resize</span>
          <button class="change-button" id="changeFile" type="button">Choose another</button>
        </div>
      </div>

      <div class="size-control">
        <div class="size-title">New size</div>
        <div class="size-grid">
          <label>Width (px)<input id="width" type="number" min="1" step="1" placeholder="1200"></label>
          <label>Height (px)<input id="height" type="number" min="1" step="1" placeholder="800"></label>
        </div>
        <label class="ratio-check"><input id="keep" type="checkbox" checked> Keep aspect ratio</label>
      </div>

      <button id="resize" class="resize-button" type="button">Resize Image</button>
      <p id="status" class="status" aria-live="polite">Ready to resize.</p>

      <div class="result" id="result" hidden>
        <div class="result-preview"><img id="resultPreview" alt="Resized image preview"></div>
        <div class="result-info">
          <span class="result-label">Resized</span>
          <strong id="resultSize">—</strong>
          <small id="resultMeta">—</small>
        </div>
        <a id="download" class="download-button" download="smarttoolz-resized-image.jpg">Download Image</a>
      </div>
    </div>
  </section>

  <section class="simple-help-grid">
    <article>
      <h2>How it works</h2>
      <ol>
        <li>Select your image.</li>
        <li>Enter the new width and height.</li>
        <li>Keep aspect ratio on to avoid stretching.</li>
        <li>Resize, preview and download.</li>
      </ol>
    </article>
    <article>
      <h2>Good to know</h2>
      <p>The image is resized in your browser. Size controls appear after an image is selected, keeping the first screen simple.</p>
    </article>
  </section>
</main>

<style>
.resizer-page{width:min(920px,calc(100% - 32px));margin:0 auto 70px}.resizer-head{text-align:center;padding:48px 0 24px}.resizer-head h1{margin:14px 0 8px;font-size:clamp(38px,6vw,56px);line-height:1;letter-spacing:-2.5px}.resizer-head p{max-width:650px;margin:0 auto;color:#667085;font-size:15px}.resizer-card{padding:26px;background:#fff;border:1px solid #e7eaf0;border-radius:24px;box-shadow:0 18px 50px rgba(16,24,40,.07)}.upload-box{min-height:280px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;border:2px dashed #d9def0;border-radius:20px;background:#fbfbff;text-align:center;cursor:pointer;transition:.2s}.upload-box:hover{border-color:#a9a0ff;background:#f7f6ff}.upload-icon{width:58px;height:58px;display:grid;place-items:center;margin-bottom:5px;border-radius:18px;background:#eeeaff;color:#5b43ff;font-size:30px;font-weight:900}.upload-box strong{font-size:21px;letter-spacing:-.4px}.upload-box small{color:#7b849d;font-size:12px}.upload-button{margin-top:8px;padding:11px 18px;border-radius:12px;background:#5c46ff;color:#fff;font-size:13px;font-weight:800}.preview-panel{padding:12px;border:1px solid #e7eaf0;border-radius:18px;background:#f8f9fd}.preview-title{display:flex;align-items:center;justify-content:space-between;margin:2px 2px 10px;color:#1d2748;font-size:12px;font-weight:850}.preview-title small{color:#7b849d;font-size:10px;font-weight:600}.preview-large{height:min(390px,52vw);min-height:230px;display:grid;place-items:center;border-radius:13px;overflow:hidden;background:repeating-conic-gradient(#edf0f6 0 25%,#fff 0 50%) 50%/22px 22px}.preview-large img{display:block;width:100%;height:100%;object-fit:contain}.file-line{display:flex;align-items:center;gap:16px;margin-top:14px;padding:14px;border:1px solid #e7eaf0;border-radius:16px;background:#fafbff}.file-info{display:flex;flex-direction:column;min-width:0;gap:3px}.file-info strong{font-size:14px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:650px}.file-info span{color:#7b849d;font-size:11px}.change-button{width:max-content;margin-top:4px;padding:0;border:0;background:transparent;color:#5541ff;font-size:11px;font-weight:800;cursor:pointer}.size-control{margin-top:16px;padding:18px;border:1px solid #e7eaf0;border-radius:16px;background:#fff}.size-title{margin-bottom:10px;font-size:13px;font-weight:800}.size-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}.size-grid label{display:flex;flex-direction:column;gap:6px;color:#5f6a87;font-size:11px;font-weight:700}.size-grid input{height:42px;width:100%;padding:0 12px;border:1px solid #dfe4ec;border-radius:11px;outline:0;background:#fff;color:#263052}.size-grid input:focus{border-color:#aaa4ff;box-shadow:0 0 0 4px rgba(99,91,255,.08)}.ratio-check{display:flex;align-items:center;gap:8px;margin-top:12px;color:#667085;font-size:11px}.ratio-check input{accent-color:#5c46ff}.resize-button{width:100%;height:52px;margin-top:18px;border:0;border-radius:14px;background:linear-gradient(90deg,#5d46ff,#3e7dff);color:#fff;font-size:14px;font-weight:900;cursor:pointer;box-shadow:0 10px 22px rgba(80,69,255,.18)}.resize-button:disabled{opacity:.6;cursor:not-allowed}.status{margin:12px 0 0;color:#69738e;text-align:center;font-size:11px;min-height:18px}.result{display:grid;grid-template-columns:84px 1fr auto;align-items:center;gap:14px;margin-top:16px;padding:14px;border:1px solid #ddd9ff;border-radius:16px;background:#f8f7ff}.result-preview{width:84px;height:84px;border-radius:12px;overflow:hidden;background:#edf0f6}.result-preview img{display:block;width:100%;height:100%;object-fit:cover}.result-info{display:flex;flex-direction:column;gap:2px}.result-label{font-size:9px;font-weight:900;letter-spacing:.8px;text-transform:uppercase;color:#6658db}.result strong{font-size:18px;letter-spacing:-.3px}.result small{color:#667085;font-size:11px}.download-button{padding:11px 16px;border-radius:11px;background:#111936;color:#fff;font-size:12px;font-weight:800;text-decoration:none;white-space:nowrap}.simple-help-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:18px}.simple-help-grid article{padding:22px;border:1px solid #e7eaf0;border-radius:18px;background:#fff}.simple-help-grid h2{margin:0 0 10px;font-size:17px}.simple-help-grid p,.simple-help-grid li{color:#667085;font-size:12px;line-height:1.75}.simple-help-grid ol{margin:0;padding-left:20px}@media(max-width:650px){.resizer-page{width:calc(100% - 20px)}.resizer-card{padding:16px}.upload-box{min-height:230px}.preview-large{height:60vw;min-height:210px}.size-grid{grid-template-columns:1fr}.result{grid-template-columns:64px 1fr}.result-preview{width:64px;height:64px}.download-button{grid-column:1/-1;text-align:center}.simple-help-grid{grid-template-columns:1fr}}
</style>

<script>
(()=>{
 const file=document.getElementById('file'),uploadStep=document.getElementById('uploadStep'),workArea=document.getElementById('workArea'),preview=document.getElementById('preview'),resultPreview=document.getElementById('resultPreview'),dimensions=document.getElementById('dimensions'),fileName=document.getElementById('fileName'),fileMeta=document.getElementById('fileMeta'),changeFile=document.getElementById('changeFile'),w=document.getElementById('width'),h=document.getElementById('height'),keep=document.getElementById('keep'),resize=document.getElementById('resize'),status=document.getElementById('status'),result=document.getElementById('result'),resultSize=document.getElementById('resultSize'),resultMeta=document.getElementById('resultMeta'),download=document.getElementById('download');
 let selected=null,previewUrl=null,downloadUrl=null,sourceW=0,sourceH=0;
 const sizeText=n=>n<1024*1024?(n/1024).toFixed(1)+' KB':(n/1024/1024).toFixed(1)+' MB';
 const setDimensions=(width,height)=>{w.value=Math.max(1,Math.round(width));h.value=Math.max(1,Math.round(height));};
 file.addEventListener('change',()=>{
   const f=file.files[0];if(!f)return;
   if(!['image/jpeg','image/png','image/webp'].includes(f.type)){file.value='';status.textContent='Please choose a JPG, PNG or WebP image.';return}
   if(f.size>20*1024*1024){file.value='';status.textContent='Please choose an image under 20 MB.';return}
   selected=f;if(previewUrl)URL.revokeObjectURL(previewUrl);previewUrl=URL.createObjectURL(f);preview.src=previewUrl;fileName.textContent=f.name;fileMeta.textContent=sizeText(f.size)+' · Ready to resize';
   const img=new Image();img.onload=()=>{sourceW=img.width;sourceH=img.height;dimensions.textContent=img.width+' × '+img.height+' px';setDimensions(img.width,img.height)};img.src=previewUrl;
   uploadStep.hidden=true;workArea.hidden=false;result.hidden=true;status.textContent='Preview ready. Set the new size and resize.';
 });
 changeFile.addEventListener('click',()=>file.click());
 w.addEventListener('input',()=>{if(keep.checked&&sourceW>0&&sourceH>0){h.value=Math.max(1,Math.round(Number(w.value)*sourceH/sourceW))}});
 h.addEventListener('input',()=>{if(keep.checked&&sourceW>0&&sourceH>0){w.value=Math.max(1,Math.round(Number(h.value)*sourceW/sourceH))}});
 resize.addEventListener('click',()=>{
   if(!selected){status.textContent='Please choose an image first.';return}
   const width=Math.floor(Number(w.value)),height=Math.floor(Number(h.value));if(!width||!height||width<1||height<1){status.textContent='Enter a valid width and height.';return}
   resize.disabled=true;result.hidden=true;status.textContent='Resizing…';
   const img=new Image(),sourceUrl=URL.createObjectURL(selected);
   img.onload=()=>{URL.revokeObjectURL(sourceUrl);const canvas=document.createElement('canvas');canvas.width=width;canvas.height=height;const ctx=canvas.getContext('2d');if(!ctx){status.textContent='Your browser could not process this image.';resize.disabled=false;return}ctx.drawImage(img,0,0,width,height);canvas.toBlob(blob=>{resize.disabled=false;if(!blob){status.textContent='This image could not be resized.';return}if(downloadUrl)URL.revokeObjectURL(downloadUrl);downloadUrl=URL.createObjectURL(blob);download.href=downloadUrl;resultPreview.src=downloadUrl;resultSize.textContent=sizeText(blob.size);resultMeta.textContent=width+' × '+height+' px';result.hidden=false;status.textContent='Done. Preview the result or download it.'},'image/jpeg',.92)};
   img.onerror=()=>{URL.revokeObjectURL(sourceUrl);status.textContent='This file is not a supported image.';resize.disabled=false};img.src=sourceUrl;
 });
})();
</script>
<?php smarttoolz_tool_page_end(); ?>
