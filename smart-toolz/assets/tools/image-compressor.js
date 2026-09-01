/* SmartToolz Image Compressor - stable client controller */
(() => {
  'use strict';

  // Stop any parent/header/admin form from submitting when this tool is used.
  document.addEventListener('submit', (e) => {
    if (e.target && (e.target.closest('.compressor-card') || e.target.querySelector?.('#compressBtn'))) {
      e.preventDefault();
      e.stopPropagation();
    }
  }, true);

  const $ = id => document.getElementById(id);
  const uploadArea=$('uploadArea'), fileInput=$('fileInput'), fileInfo=$('fileInfo'), settings=$('settings'), quality=$('quality'), qualityValue=$('qualityValue'), compressBtn=$('compressBtn'), resetBtn=$('resetBtn'), errorBox=$('error'), result=$('result'), originalPreview=$('originalPreview'), compressedPreview=$('compressedPreview'), originalSize=$('originalSize'), compressedSize=$('compressedSize'), savedSize=$('savedSize'), downloadBtn=$('downloadBtn');
  if(!compressBtn || !fileInput) return;
  let selectedFile=null, originalUrl=null, compressedUrl=null, usageConsumed=false;

  function showError(m){errorBox.textContent=m;errorBox.style.display='block'}
  function clearError(){errorBox.textContent='';errorBox.style.display='none'}
  function formatBytes(bytes){if(bytes<=0)return '0 Bytes';const u=['Bytes','KB','MB','GB'],i=Math.min(Math.floor(Math.log(bytes)/Math.log(1024)),u.length-1),v=bytes/Math.pow(1024,i);return v.toFixed(i===0?0:2)+' '+u[i]}
  function filename(n){return n.replace(/\.[^/.]+$/,'').replace(/[^a-zA-Z0-9_-]/g,'-')+'-compressed.jpg'}
  function loadImage(file){return new Promise((resolve,reject)=>{const img=new Image(),u=URL.createObjectURL(file);img.onload=()=>{URL.revokeObjectURL(u);resolve(img)};img.onerror=()=>{URL.revokeObjectURL(u);reject(new Error('Unable to read this image.'))};img.src=u})}
  function makeBlob(canvas,q){return new Promise(resolve=>canvas.toBlob(resolve,'image/jpeg',q))}

  async function consumeUsage(){
    if(usageConsumed)return {ok:true};
    const r=await fetch('/smart-toolz/api/trial.php?action=consume&tool=image-compressor',{method:'POST',cache:'no-store',headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}});
    const text=await r.text();
    let d;
    try{d=JSON.parse(text)}catch(e){throw new Error('Usage service returned an invalid response. Please try again.')}
    if(!d.ok)throw new Error(d.message||'Usage limit reached.');
    usageConsumed=true;
    return d;
  }

  function handleFile(file){
    clearError();
    const allowed=['image/jpeg','image/png','image/webp'];
    if(!allowed.includes(file.type))return showError('Please choose a JPG, JPEG, PNG or WebP image.');
    if(file.size>20*1024*1024)return showError('Maximum file size is 20 MB.');
    selectedFile=file; usageConsumed=false;
    fileInfo.textContent=file.name+' • '+formatBytes(file.size);
    if(originalUrl)URL.revokeObjectURL(originalUrl);
    originalUrl=URL.createObjectURL(file); originalPreview.src=originalUrl;
    settings.style.display='block'; result.style.display='none';
  }

  uploadArea?.addEventListener('click',e=>{e.preventDefault();e.stopPropagation();fileInput.click()});
  fileInput.addEventListener('change',()=>{if(fileInput.files?.[0])handleFile(fileInput.files[0])});
  uploadArea?.addEventListener('dragover',e=>{e.preventDefault();e.stopPropagation();uploadArea.classList.add('dragover')});
  uploadArea?.addEventListener('dragleave',()=>uploadArea.classList.remove('dragover'));
  uploadArea?.addEventListener('drop',e=>{e.preventDefault();e.stopPropagation();uploadArea.classList.remove('dragover');if(e.dataTransfer.files?.[0])handleFile(e.dataTransfer.files[0])});
  quality?.addEventListener('input',()=>qualityValue.textContent=quality.value+'%');

  // Capture the tool button before any parent/admin handler can submit a form.
  document.addEventListener('click', e => {
    const btn=e.target?.closest?.('#compressBtn');
    if(btn){e.preventDefault();e.stopPropagation();}
  }, true);

  compressBtn.addEventListener('click',async e=>{
    e.preventDefault(); e.stopPropagation();
    if(!selectedFile)return showError('Please select an image first.');
    if(compressBtn.disabled)return;
    clearError(); compressBtn.disabled=true; compressBtn.textContent='Compressing...';
    try{
      const image=await loadImage(selectedFile), canvas=document.createElement('canvas');
      canvas.width=image.naturalWidth; canvas.height=image.naturalHeight;
      const ctx=canvas.getContext('2d');
      if(!ctx)throw new Error('Your browser does not support image processing.');
      ctx.fillStyle='#ffffff';ctx.fillRect(0,0,canvas.width,canvas.height);ctx.drawImage(image,0,0);
      const out=await makeBlob(canvas,parseInt(quality.value,10)/100);
      if(!out)throw new Error('Could not create compressed image.');
      await consumeUsage();
      if(compressedUrl)URL.revokeObjectURL(compressedUrl);
      compressedUrl=URL.createObjectURL(out); compressedPreview.src=compressedUrl;
      originalSize.textContent=formatBytes(selectedFile.size);
      compressedSize.textContent=formatBytes(out.size);
      savedSize.textContent=Math.max(0,(1-out.size/selectedFile.size)*100).toFixed(1)+'%';
      downloadBtn.href=compressedUrl; downloadBtn.download=filename(selectedFile.name);
      result.style.display='block';
      result.scrollIntoView({behavior:'smooth',block:'start'});
    }catch(err){showError(err.message||'Compression failed.')}
    finally{compressBtn.disabled=false;compressBtn.textContent='Compress Image'}
  }, false);

  resetBtn?.addEventListener('click',e=>{
    e.preventDefault();e.stopPropagation();selectedFile=null;usageConsumed=false;
    if(originalUrl)URL.revokeObjectURL(originalUrl);if(compressedUrl)URL.revokeObjectURL(compressedUrl);
    originalUrl=null;compressedUrl=null;fileInput.value='';fileInfo.textContent='';originalPreview.removeAttribute('src');compressedPreview.removeAttribute('src');settings.style.display='none';result.style.display='none';quality.value='80';qualityValue.textContent='80%';clearError();
  });
})();
