/* SmartToolz Image Compressor - single controller */
(() => {
  'use strict';
  const $ = id => document.getElementById(id);
  const uploadArea=$('uploadArea'), fileInput=$('fileInput'), fileInfo=$('fileInfo'), settings=$('settings'), quality=$('quality'), qualityValue=$('qualityValue'), compressBtn=$('compressBtn'), resetBtn=$('resetBtn'), errorBox=$('error'), result=$('result'), originalPreview=$('originalPreview'), compressedPreview=$('compressedPreview'), originalSize=$('originalSize'), compressedSize=$('compressedSize'), savedSize=$('savedSize'), downloadBtn=$('downloadBtn');
  if(!compressBtn || !fileInput) return;
  let selectedFile=null, originalUrl=null, compressedUrl=null, busy=false;
  const tool='image-compressor';
  const uuid=()=>crypto?.randomUUID?crypto.randomUUID():Date.now().toString(36)+'-'+Math.random().toString(36).slice(2,14);
  const showError=m=>{if(errorBox){errorBox.textContent=m;errorBox.style.display='block'}};
  const clearError=()=>{if(errorBox){errorBox.textContent='';errorBox.style.display='none'}};
  const formatBytes=b=>{if(b<=0)return'0 Bytes';const u=['Bytes','KB','MB','GB'],i=Math.min(Math.floor(Math.log(b)/Math.log(1024)),3),v=b/Math.pow(1024,i);return v.toFixed(i?2:0)+' '+u[i]};
  const filename=n=>n.replace(/\.[^/.]+$/,'').replace(/[^a-zA-Z0-9_-]/g,'-')+'-compressed.jpg';
  const loadImage=f=>new Promise((ok,no)=>{const i=new Image(),u=URL.createObjectURL(f);i.onload=()=>{URL.revokeObjectURL(u);ok(i)};i.onerror=()=>{URL.revokeObjectURL(u);no(new Error('Unable to read this image.'))};i.src=u});
  const makeBlob=(c,q)=>new Promise(r=>c.toBlob(r,'image/jpeg',q));
  async function consumeUsage(requestId){
    const body=new URLSearchParams({action:'consume',tool,request_id:requestId});
    const r=await fetch('/smart-toolz/api/trial.php',{method:'POST',body,cache:'no-store',credentials:'same-origin',headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}});
    const text=await r.text();let d;try{d=JSON.parse(text)}catch(_){throw new Error('Usage service returned an invalid response. Please try again.')}
    if(!r.ok||!d.ok)throw new Error(d.message||'You cannot use this tool right now.');
    const hc=$('headerCredits');if(hc&&typeof d.credits==='number')hc.textContent=Number(d.credits).toLocaleString();
    const note=document.querySelector('.trial-note');if(note&&d.guest){note.innerHTML='🎁 <b>'+d.used+' / '+d.limit+' used</b> — '+d.remaining+' free trials remaining for this tool.';}
    return d;
  }
  function handleFile(file){
    clearError();const allowed=['image/jpeg','image/png','image/webp'];
    if(!allowed.includes(file.type))return showError('Please choose a JPG, JPEG, PNG or WebP image.');
    if(file.size>20*1024*1024)return showError('Maximum file size is 20 MB.');
    selectedFile=file;fileInfo.textContent=file.name+' • '+formatBytes(file.size);
    if(originalUrl)URL.revokeObjectURL(originalUrl);originalUrl=URL.createObjectURL(file);originalPreview.src=originalUrl;settings.style.display='block';result.style.display='none';
  }
  uploadArea?.addEventListener('click',e=>{e.preventDefault();e.stopPropagation();fileInput.click()});
  fileInput.addEventListener('change',()=>{if(fileInput.files?.[0])handleFile(fileInput.files[0])});
  uploadArea?.addEventListener('dragover',e=>{e.preventDefault();e.stopPropagation();uploadArea.classList.add('dragover')});
  uploadArea?.addEventListener('dragleave',()=>uploadArea.classList.remove('dragover'));
  uploadArea?.addEventListener('drop',e=>{e.preventDefault();e.stopPropagation();uploadArea.classList.remove('dragover');if(e.dataTransfer.files?.[0])handleFile(e.dataTransfer.files[0])});
  quality?.addEventListener('input',()=>qualityValue.textContent=quality.value+'%');

  /* One and only one compression click handler. Capture phase prevents any parent/header/admin navigation handler. */
  document.addEventListener('click',e=>{
    const btn=e.target?.closest?.('#compressBtn');
    if(!btn)return;
    e.preventDefault();e.stopPropagation();e.stopImmediatePropagation();
    if(busy)return;
    busy=true;
    compressImage().finally(()=>{busy=false});
  },true);

  async function compressImage(){
    if(!selectedFile)return showError('Please select an image first.');
    clearError();compressBtn.disabled=true;compressBtn.textContent='Compressing...';
    try{
      const image=await loadImage(selectedFile),canvas=document.createElement('canvas');canvas.width=image.naturalWidth;canvas.height=image.naturalHeight;
      const ctx=canvas.getContext('2d');if(!ctx)throw new Error('Your browser does not support image processing.');
      ctx.fillStyle='#ffffff';ctx.fillRect(0,0,canvas.width,canvas.height);ctx.drawImage(image,0,0);
      const out=await makeBlob(canvas,parseInt(quality.value,10)/100);if(!out)throw new Error('Could not create compressed image.');
      await consumeUsage(uuid());
      if(compressedUrl)URL.revokeObjectURL(compressedUrl);compressedUrl=URL.createObjectURL(out);compressedPreview.src=compressedUrl;
      originalSize.textContent=formatBytes(selectedFile.size);compressedSize.textContent=formatBytes(out.size);savedSize.textContent=Math.max(0,(1-out.size/selectedFile.size)*100).toFixed(1)+'%';downloadBtn.href=compressedUrl;downloadBtn.download=filename(selectedFile.name);result.style.display='block';result.scrollIntoView({behavior:'smooth',block:'start'});
    }catch(err){showError(err.message||'Compression failed.')}
    finally{compressBtn.disabled=false;compressBtn.textContent='Compress Image'}
  }

  downloadBtn?.addEventListener('click',()=>{
    try{
      const data=new URLSearchParams({tool,page:location.pathname});
      if(navigator.sendBeacon){navigator.sendBeacon('/smart-toolz/api/download-track.php',new Blob([data.toString()],{type:'application/x-www-form-urlencoded;charset=UTF-8'}));}
      else fetch('/smart-toolz/api/download-track.php',{method:'POST',body:data,keepalive:true,credentials:'same-origin'}).catch(()=>{});
    }catch(_){/* download itself must not be blocked */}
  });
  resetBtn?.addEventListener('click',e=>{e.preventDefault();e.stopPropagation();selectedFile=null;if(originalUrl)URL.revokeObjectURL(originalUrl);if(compressedUrl)URL.revokeObjectURL(compressedUrl);originalUrl=compressedUrl=null;fileInput.value='';fileInfo.textContent='';originalPreview.removeAttribute('src');compressedPreview.removeAttribute('src');settings.style.display='none';result.style.display='none';quality.value=80;qualityValue.textContent='80%';clearError()});
})();
