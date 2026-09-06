<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/lib/tool-page.php';

$tool = [
    'title' => 'Free Image Compressor Online — JPG, PNG & WebP',
    'description' => 'Compress JPG, PNG and WebP images online for free. Reduce image file size quickly in your browser with SmartToolz.',
    'url' => 'https://smarttoolz.in/tools/image-compressor/'
];
smarttoolz_tool_page_start($tool);
?>
<main class="tool-page simple-tool-page">
  <section class="simple-tool-hero">
    <span class="eyebrow">IMAGE COMPRESSOR</span>
    <h1>Compress Image Online</h1>
    <p>Make your image smaller in a few clicks. No signup, no complicated settings.</p>
  </section>

  <section class="simple-tool-box" id="compressor">
    <label class="form-label fw-semibold" for="file">Choose an image</label>
    <input id="file" class="form-control form-control-lg mb-3" type="file" accept="image/jpeg,image/png,image/webp">

    <div class="quality-row">
      <label class="form-label fw-semibold mb-1" for="quality">Quality <span id="qualityValue">75%</span></label>
      <input id="quality" class="form-range" type="range" min="10" max="100" value="75">
    </div>

    <button id="compress" class="tool-btn simple-action" type="button">Compress Image</button>

    <div id="status" class="tool-result" aria-live="polite">Choose an image to get started.</div>
    <a id="download" class="tool-btn simple-action mt-3 d-none text-center" download="smarttoolz-compressed-image.jpg">Download Image</a>
  </section>

  <section class="simple-help-grid">
    <article>
      <h2>How to compress an image</h2>
      <ol>
        <li>Select your JPG, PNG or WebP image.</li>
        <li>Adjust quality when you need a smaller file.</li>
        <li>Click <strong>Compress Image</strong>.</li>
        <li>Download your compressed image.</li>
      </ol>
    </article>
    <article>
      <h2>Good to know</h2>
      <p>The image is processed in your browser. Very small or already optimized images may not become much smaller.</p>
    </article>
  </section>
</main>
<script>
(()=>{
 const file=document.getElementById('file'),quality=document.getElementById('quality'),qv=document.getElementById('qualityValue'),btn=document.getElementById('compress'),status=document.getElementById('status'),download=document.getElementById('download');
 quality.addEventListener('input',()=>qv.textContent=quality.value+'%');
 btn.addEventListener('click',()=>{
   const f=file.files[0];
   if(!f){status.textContent='Please choose an image first.';return;}
   status.textContent='Compressing…';
   const img=new Image(),reader=new FileReader();
   reader.onload=()=>{
     img.onload=()=>{
       const canvas=document.createElement('canvas'),max=2400,scale=Math.min(1,max/Math.max(img.width,img.height));
       canvas.width=Math.max(1,Math.round(img.width*scale));
       canvas.height=Math.max(1,Math.round(img.height*scale));
       const ctx=canvas.getContext('2d');
       if(!ctx){status.textContent='Your browser could not process this image.';return;}
       ctx.drawImage(img,0,0,canvas.width,canvas.height);
       canvas.toBlob(blob=>{
         if(!blob){status.textContent='This image could not be compressed.';return;}
         const url=URL.createObjectURL(blob),before=(f.size/1024).toFixed(1),after=(blob.size/1024).toFixed(1),saved=f.size?Math.max(0,100-(blob.size/f.size*100)):0;
         status.innerHTML='<strong>'+before+' KB → '+after+' KB</strong><br>'+saved.toFixed(0)+'% smaller';
         download.href=url;download.classList.remove('d-none');
       },'image/jpeg',Number(quality.value)/100);
     };
     img.onerror=()=>status.textContent='This file is not a supported image.';
     img.src=reader.result;
   };
   reader.onerror=()=>status.textContent='The image could not be read.';
   reader.readAsDataURL(f);
 });
})();
</script>
<?php smarttoolz_tool_page_end(); ?>
