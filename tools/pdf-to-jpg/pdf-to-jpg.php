<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/lib/tool-page.php';

$tool = [
    'title' => 'Free PDF to JPG Converter Online',
    'description' => 'Convert PDF pages to JPG images online for free. Preview pages and download JPG images directly in your browser with SmartToolz.',
    'url' => 'https://smarttoolz.in/tools/pdf-to-jpg/'
];
smarttoolz_tool_page_start($tool);
?>
<main class="pdf-page">
  <section class="tool-head">
    <span class="eyebrow">PDF TO JPG</span>
    <h1>Convert PDF to JPG</h1>
    <p>Turn PDF pages into high-quality JPG images directly in your browser. No server upload required.</p>
  </section>

  <section class="converter-card" aria-label="PDF to JPG converter">
    <div id="uploadStep">
      <label class="upload-box" for="file">
        <span class="upload-icon" aria-hidden="true">↑</span>
        <strong>Drop your PDF here</strong>
        <small>PDF · up to 20 MB</small>
        <span class="upload-button">Choose PDF</span>
      </label>
      <input id="file" type="file" accept="application/pdf" hidden>
    </div>

    <div id="workArea" hidden>
      <div class="file-row">
        <div class="pdf-icon">PDF</div>
        <div class="file-info"><strong id="fileName">Document.pdf</strong><span id="fileMeta">Ready</span></div>
        <button id="changeFile" type="button">Choose another</button>
      </div>

      <div class="options">
        <label>JPG quality
          <select id="quality"><option value="0.8">Standard</option><option value="0.92" selected>High</option><option value="0.98">Maximum</option></select>
        </label>
        <label>Scale
          <select id="scale"><option value="1">100%</option><option value="1.5" selected>150%</option><option value="2">200%</option></select>
        </label>
      </div>

      <button class="convert-button" id="convert" type="button">Convert PDF to JPG</button>
      <p class="status" id="status" aria-live="polite">Ready to convert.</p>
      <div class="pages" id="pages"></div>
      <button class="download-all" id="downloadAll" type="button" hidden>Download All JPGs</button>
    </div>
  </section>

  <section class="help-grid">
    <article><h2>How it works</h2><ol><li>Select or drop a PDF.</li><li>Choose quality and image scale.</li><li>Convert every PDF page to JPG.</li><li>Download individual images or all pages.</li></ol></article>
    <article><h2>Private conversion</h2><p>Your PDF is rendered locally in your browser using PDF.js. The document is not uploaded to SmartToolz.</p></article>
  </section>
</main>

<style>
.pdf-page{width:min(980px,calc(100% - 32px));margin:0 auto 70px}.tool-head{text-align:center;padding:48px 0 24px}.tool-head h1{margin:14px 0 8px;font-size:clamp(38px,6vw,56px);line-height:1;letter-spacing:-2.5px}.tool-head p{max-width:680px;margin:0 auto;color:#667085;font-size:15px}.converter-card{padding:26px;background:#fff;border:1px solid #e7eaf0;border-radius:24px;box-shadow:0 18px 50px rgba(16,24,40,.07)}.upload-box{min-height:250px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;border:2px dashed #d9def0;border-radius:20px;background:#fbfbff;text-align:center;cursor:pointer}.upload-box:hover{border-color:#a9a0ff;background:#f7f6ff}.upload-icon{width:54px;height:54px;display:grid;place-items:center;margin-bottom:5px;border-radius:16px;background:#eeeaff;color:#5b43ff;font-size:27px;font-weight:900}.upload-box strong{font-size:20px}.upload-box small{color:#7b849d;font-size:12px}.upload-button{margin-top:8px;padding:10px 17px;border-radius:11px;background:#5c46ff;color:#fff;font-size:12px;font-weight:800}.file-row{display:grid;grid-template-columns:58px 1fr auto;align-items:center;gap:12px;padding:12px;border:1px solid #e7eaf0;border-radius:15px;background:#fafbff}.pdf-icon{width:58px;height:58px;display:grid;place-items:center;border-radius:12px;background:#fff0f0;color:#d33b3b;font-size:12px;font-weight:900}.file-info{display:flex;flex-direction:column;gap:3px;min-width:0}.file-info strong{overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:13px}.file-info span{font-size:10px;color:#7b849d}.file-row button{border:0;background:transparent;color:#5541ff;font-size:10px;font-weight:800;cursor:pointer}.options{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:14px}.options label{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:13px;border:1px solid #e7eaf0;border-radius:13px;color:#344054;font-size:11px;font-weight:800}.options select{padding:8px 10px;border:1px solid #dfe3eb;border-radius:9px;background:#fff;font-size:10px}.convert-button,.download-all{width:100%;height:48px;margin-top:15px;border:0;border-radius:12px;background:linear-gradient(90deg,#5d46ff,#3e7dff);color:#fff;font-size:12px;font-weight:900;cursor:pointer}.convert-button:disabled{opacity:.6;cursor:not-allowed}.status{min-height:17px;text-align:center;color:#69738e;font-size:10px}.pages{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-top:14px}.page-card{padding:9px;border:1px solid #e7eaf0;border-radius:14px;background:#fafbff}.page-card img{display:block;width:100%;aspect-ratio:3/4;object-fit:contain;background:#fff;border-radius:9px}.page-foot{display:flex;align-items:center;justify-content:space-between;gap:8px;padding-top:8px}.page-foot span{font-size:10px;color:#69738e}.page-foot a{padding:7px 9px;border-radius:8px;background:#111936;color:#fff;text-decoration:none;font-size:9px;font-weight:800}.download-all{display:block}.help-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:18px}.help-grid article{padding:22px;border:1px solid #e7eaf0;border-radius:18px;background:#fff}.help-grid h2{margin:0 0 10px;font-size:17px}.help-grid p,.help-grid li{color:#667085;font-size:12px;line-height:1.75}.help-grid ol{margin:0;padding-left:20px}@media(max-width:700px){.pdf-page{width:calc(100% - 20px)}.converter-card{padding:16px}.options{grid-template-columns:1fr}.pages{grid-template-columns:1fr 1fr}.file-row{grid-template-columns:52px 1fr}.pdf-icon{width:52px;height:52px}.file-row button{grid-column:2;text-align:left;padding:0}.help-grid{grid-template-columns:1fr}}@media(max-width:430px){.pages{grid-template-columns:1fr}}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.10.38/pdf.min.mjs" type="module"></script>
<script type="module">
const pdfjs=await import('https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.10.38/pdf.min.mjs');
pdfjs.GlobalWorkerOptions.workerSrc='https://cdnjs.cloudflare.com/ajax/libs/pdf.js/4.10.38/pdf.worker.min.mjs';
const file=document.getElementById('file'),uploadStep=document.getElementById('uploadStep'),workArea=document.getElementById('workArea'),fileName=document.getElementById('fileName'),fileMeta=document.getElementById('fileMeta'),changeFile=document.getElementById('changeFile'),convert=document.getElementById('convert'),status=document.getElementById('status'),pages=document.getElementById('pages'),downloadAll=document.getElementById('downloadAll'),quality=document.getElementById('quality'),scale=document.getElementById('scale');
let selected=null,downloads=[];
const sizeText=n=>n<1048576?(n/1024).toFixed(1)+' KB':(n/1048576).toFixed(1)+' MB';
file.addEventListener('change',()=>{const f=file.files[0];if(!f)return;if(f.type!=='application/pdf'){status.textContent='Please choose a PDF file.';return}if(f.size>20*1024*1024){status.textContent='Please choose a PDF under 20 MB.';return}selected=f;fileName.textContent=f.name;fileMeta.textContent=sizeText(f.size)+' · Ready to convert';uploadStep.hidden=true;workArea.hidden=false;pages.innerHTML='';downloadAll.hidden=true;status.textContent='Ready to convert.'});
changeFile.addEventListener('click',()=>file.click());
convert.addEventListener('click',async()=>{if(!selected)return;convert.disabled=true;downloadAll.hidden=true;pages.innerHTML='';downloads.forEach(x=>URL.revokeObjectURL(x.url));downloads=[];status.textContent='Loading PDF…';try{const data=await selected.arrayBuffer();const pdf=await pdfjs.getDocument({data}).promise;for(let i=1;i<=pdf.numPages;i++){status.textContent=`Converting page ${i} of ${pdf.numPages}…`;const page=await pdf.getPage(i);const viewport=page.getViewport({scale:Number(scale.value)});const canvas=document.createElement('canvas');canvas.width=Math.ceil(viewport.width);canvas.height=Math.ceil(viewport.height);const ctx=canvas.getContext('2d',{alpha:false});await page.render({canvasContext:ctx,viewport,background:'#fff'}).promise;const blob=await new Promise(resolve=>canvas.toBlob(resolve,'image/jpeg',Number(quality.value)));if(!blob)throw new Error('Could not create JPG');const url=URL.createObjectURL(blob);downloads.push({url,name:(selected.name.replace(/\.pdf$/i,'')||'converted')+'-page-'+i+'.jpg'});const card=document.createElement('div');card.className='page-card';const image=document.createElement('img');image.src=url;image.alt='Converted page '+i;const foot=document.createElement('div');foot.className='page-foot';const label=document.createElement('span');label.textContent='Page '+i+' · '+sizeText(blob.size);const a=document.createElement('a');a.href=url;a.download=downloads[downloads.length-1].name;a.textContent='Download';foot.append(label,a);card.append(image,foot);pages.append(card)}status.textContent=`Done — ${pdf.numPages} page${pdf.numPages===1?'':'s'} converted to JPG.`;downloadAll.hidden=false}catch(e){status.textContent='Conversion failed: '+e.message}finally{convert.disabled=false}});
downloadAll.addEventListener('click',()=>{downloads.forEach((item,i)=>setTimeout(()=>{const a=document.createElement('a');a.href=item.url;a.download=item.name;a.click()},i*250))});
</script>
<?php smarttoolz_tool_page_end(); ?>
