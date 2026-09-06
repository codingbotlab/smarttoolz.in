<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/lib/tool-page.php';

$tool = [
    'title' => 'Free PNG to JPG Converter Online',
    'description' => 'Convert PNG images to JPG online for free. Preview, choose quality and download your JPG image directly in your browser.',
    'url' => 'https://smarttoolz.in/tools/png-to-jpg/'
];
smarttoolz_tool_page_start($tool);
?>
<main class="converter-page">
  <section class="converter-head">
    <span class="eyebrow">PNG TO JPG</span>
    <h1>Convert PNG to JPG</h1>
    <p>Turn PNG images into JPG files in your browser. Preview the image, choose quality, then download.</p>
  </section>

  <section class="converter-card" aria-label="PNG to JPG converter">
    <div class="upload-step" id="uploadStep">
      <label class="upload-box" for="file">
        <span class="upload-icon" aria-hidden="true">↑</span>
        <strong>Choose a PNG image</strong>
        <small>PNG · up to 20 MB</small>
        <span class="upload-button">Select Image</span>
      </label>
      <input id="file" type="file" accept="image/png" hidden>
    </div>

    <div class="work-area" id="workArea" hidden>
      <div class="selected-row">
        <div class="thumb"><img id="preview" alt="Selected PNG preview"></div>
        <div class="file-info">
          <strong id="fileName">Image</strong>
          <span id="fileMeta">Ready to convert</span>
          <button class="change-button" id="changeFile" type="button">Choose another</button>
        </div>
        <span class="dimensions" id="dimensions">—</span>
      </div>

      <div class="quality-control">
        <div class="control-head"><label for="quality">JPG Quality</label><output id="qualityValue">90%</output></div>
        <input id="quality" type="range" min="10" max="100" value="90">
        <div class="range-labels"><span>Smaller file</span><span>Better quality</span></div>
      </div>

      <div class="background-control">
        <label for="background">Transparent background</label>
        <select id="background">
          <option value="white">White</option>
          <option value="black">Black</option>
        </select>
      </div>

      <button id="convert" class="convert-button" type="button">Convert to JPG</button>
      <p id="status" class="status" aria-live="polite">Ready to convert.</p>

      <div class="result" id="result" hidden>
        <div class="thumb result-thumb"><img id="resultPreview" alt="Converted JPG preview"></div>
        <div class="result-info">
          <span class="result-label">JPG preview</span>
          <strong id="resultSize">—</strong>
          <small id="resultSaving">—</small>
        </div>
        <a id="download" class="download-button" download="smarttoolz-converted.jpg">Download JPG</a>
      </div>
    </div>
  </section>

  <section class="simple-help-grid">
    <article><h2>How it works</h2><ol><li>Select a PNG image.</li><li>Adjust JPG quality if needed.</li><li>Choose a background for transparent areas.</li><li>Convert and download your JPG.</li></ol></article>
    <article><h2>Private and simple</h2><p>Your image is processed locally in your browser. Nothing needs to be uploaded to a server.</p></article>
  </section>
</main>

<style>
.converter-page{width:min(920px,calc(100% - 32px));margin:0 auto 70px}.converter-head{text-align:center;padding:48px 0 24px}.converter-head h1{margin:14px 0 8px;font-size:clamp(38px,6vw,56px);line-height:1;letter-spacing:-2.5px}.converter-head p{max-width:680px;margin:0 auto;color:#667085;font-size:15px}.converter-card{padding:26px;background:#fff;border:1px solid #e7eaf0;border-radius:24px;box-shadow:0 18px 50px rgba(16,24,40,.07)}.upload-box{min-height:240px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;border:2px dashed #d9def0;border-radius:20px;background:#fbfbff;text-align:center;cursor:pointer;transition:.2s}.upload-box:hover{border-color:#a9a0ff;background:#f7f6ff}.upload-icon{width:52px;height:52px;display:grid;place-items:center;margin-bottom:5px;border-radius:16px;background:#eeeaff;color:#5b43ff;font-size:27px;font-weight:900}.upload-box strong{font-size:20px;letter-spacing:-.4px}.upload-box small{color:#7b849d;font-size:12px}.upload-button{margin-top:8px;padding:10px 17px;border-radius:11px;background:#5c46ff;color:#fff;font-size:12px;font-weight:800}.selected-row{display:grid;grid-template-columns:64px minmax(0,1fr) auto;align-items:center;gap:13px;padding:10px;border:1px solid #e7eaf0;border-radius:15px;background:#fafbff}.thumb{width:64px;height:64px;border-radius:11px;overflow:hidden;background:repeating-conic-gradient(#edf0f6 0 25%,#fff 0 50%) 50%/12px 12px}.thumb img{display:block;width:100%;height:100%;object-fit:contain}.file-info{display:flex;flex-direction:column;min-width:0;gap:2px}.file-info strong{font-size:13px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.file-info span{color:#7b849d;font-size:10px}.change-button{width:max-content;margin-top:2px;padding:0;border:0;background:transparent;color:#5541ff;font-size:10px;font-weight:800;cursor:pointer}.dimensions{color:#7b849d;font-size:10px;white-space:nowrap}.quality-control,.background-control{margin-top:14px;padding:15px;border:1px solid #e7eaf0;border-radius:15px;background:#fff}.control-head{display:flex;align-items:center;justify-content:space-between}.control-head label{font-size:12px;font-weight:800}.control-head output{padding:4px 8px;border-radius:999px;background:#f0efff;color:#5b43ff;font-size:10px;font-weight:900}.quality-control input{width:100%;margin:10px 0 0;accent-color:#5c46ff}.range-labels{display:flex;justify-content:space-between;margin-top:2px;color:#8a93aa;font-size:9px}.background-control{display:flex;align-items:center;justify-content:space-between;gap:12px}.background-control label{font-size:12px;font-weight:800}.background-control select{padding:8px 10px;border:1px solid #dfe3eb;border-radius:9px;background:#fff;font-size:11px}.convert-button{width:100%;height:50px;margin-top:16px;border:0;border-radius:13px;background:linear-gradient(90deg,#5d46ff,#3e7dff);color:#fff;font-size:13px;font-weight:900;cursor:pointer;box-shadow:0 10px 22px rgba(80,69,255,.18)}.convert-button:disabled{opacity:.6;cursor:not-allowed}.status{margin:10px 0 0;color:#69738e;text-align:center;font-size:10px;min-height:16px}.result{display:grid;grid-template-columns:64px minmax(0,1fr) auto;align-items:center;gap:13px;margin-top:14px;padding:10px;border:1px solid #ddd9ff;border-radius:15px;background:#f8f7ff}.result-info{display:flex;flex-direction:column;gap:2px}.result-label{font-size:8px;font-weight:900;letter-spacing:.7px;text-transform:uppercase;color:#6658db}.result strong{font-size:16px}.result small{color:#667085;font-size:10px}.download-button{padding:10px 14px;border-radius:10px;background:#111936;color:#fff;font-size:11px;font-weight:800;text-decoration:none;white-space:nowrap}.simple-help-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:18px}.simple-help-grid article{padding:22px;border:1px solid #e7eaf0;border-radius:18px;background:#fff}.simple-help-grid h2{margin:0 0 10px;font-size:17px}.simple-help-grid p,.simple-help-grid li{color:#667085;font-size:12px;line-height:1.75}.simple-help-grid ol{margin:0;padding-left:20px}@media(max-width:650px){.converter-page{width:calc(100% - 20px)}.converter-card{padding:16px}.upload-box{min-height:220px}.selected-row{grid-template-columns:56px 1fr}.thumb{width:56px;height:56px}.dimensions{grid-column:2}.background-control{align-items:flex-start;flex-direction:column}.result{grid-template-columns:56px 1fr}.result-thumb{width:56px;height:56px}.download-button{grid-column:1/-1;text-align:center}.simple-help-grid{grid-template-columns:1fr}}
</style>

<script>
(()=>{
 const file=document.getElementById('file'),uploadStep=document.getElementById('uploadStep'),workArea=document.getElementById('workArea'),preview=document.getElementById('preview'),resultPreview=document.getElementById('resultPreview'),dimensions=document.getElementById('dimensions'),fileName=document.getElementById('fileName'),fileMeta=document.getElementById('fileMeta'),changeFile=document.getElementById('changeFile'),quality=document.getElementById('quality'),qualityValue=document.getElementById('qualityValue'),background=document.getElementById('background'),convert=document.getElementById('convert'),status=document.getElementById('status'),result=document.getElementById('result'),resultSize=document.getElementById('resultSize'),resultSaving=document.getElementById('resultSaving'),download=document.getElementById('download');
 let selected=null,previewUrl=null,downloadUrl=null;
 const sizeText=n=>n<1048576?(n/1024).toFixed(1)+' KB':(n/1048576).toFixed(1)+' MB';
 quality.addEventListener('input',()=>qualityValue.textContent=quality.value+'%');
 file.addEventListener('change',()=>{const f=file.files[0];if(!f)return;if(f.type!=='image/png'){file.value='';status.textContent='Please choose a PNG image.';return}if(f.size>20*1024*1024){file.value='';status.textContent='Please choose an image under 20 MB.';return}selected=f;if(previewUrl)URL.revokeObjectURL(previewUrl);previewUrl=URL.createObjectURL(f);preview.src=previewUrl;fileName.textContent=f.name;fileMeta.textContent=sizeText(f.size)+' · Ready to convert';const img=new Image();img.onload=()=>dimensions.textContent=img.width+' × '+img.height+' px';img.src=previewUrl;uploadStep.hidden=true;workArea.hidden=false;result.hidden=true;status.textContent='Preview ready. Adjust quality or convert now.';});
 changeFile.addEventListener('click',()=>file.click());
 convert.addEventListener('click',()=>{if(!selected)return;convert.disabled=true;result.hidden=true;status.textContent='Converting…';const img=new Image(),sourceUrl=URL.createObjectURL(selected);img.onload=()=>{URL.revokeObjectURL(sourceUrl);const canvas=document.createElement('canvas');canvas.width=img.naturalWidth;canvas.height=img.naturalHeight;const ctx=canvas.getContext('2d');if(!ctx){status.textContent='Your browser could not process this image.';convert.disabled=false;return}ctx.fillStyle=background.value==='black'?'#000':'#fff';ctx.fillRect(0,0,canvas.width,canvas.height);ctx.drawImage(img,0,0);canvas.toBlob(blob=>{convert.disabled=false;if(!blob){status.textContent='Conversion failed.';return}if(downloadUrl)URL.revokeObjectURL(downloadUrl);downloadUrl=URL.createObjectURL(blob);download.href=downloadUrl;download.download=(selected.name.replace(/\.[^.]+$/,'')||'converted')+'.jpg';resultPreview.src=downloadUrl;resultSize.textContent=sizeText(blob.size);const diff=selected.size?((blob.size/selected.size-1)*100):0;resultSaving.textContent=(diff<=0?Math.abs(diff).toFixed(0)+'% smaller':'Converted · '+Math.abs(diff).toFixed(0)+'% larger')+' · '+canvas.width+' × '+canvas.height+' px';result.hidden=false;status.textContent='Done. Preview the result or download your JPG.'},'image/jpeg',Number(quality.value)/100)};img.onerror=()=>{URL.revokeObjectURL(sourceUrl);status.textContent='This file could not be read.';convert.disabled=false};img.src=sourceUrl});
})();
</script>
<?php smarttoolz_tool_page_end(); ?>
