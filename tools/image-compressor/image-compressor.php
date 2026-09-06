<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/lib/tool-page.php';

$tool = [
    'title' => 'Free Image Compressor Online — JPG, PNG & WebP',
    'description' => 'Compress JPG, PNG and WebP images online for free. Reduce image file size in your browser with SmartToolz Image Compressor.',
    'url' => 'https://smarttoolz.in/tools/image-compressor/'
];
smarttoolz_tool_page_start($tool);
?>
<main class="tool-page">
  <div class="tool-hero-grid">
    <section class="tool-intro">
      <span class="eyebrow">IMAGE TOOLS • BROWSER BASED</span>
      <h1>Free Image Compressor Online</h1>
      <p>Reduce JPG, PNG and WebP file size for websites, email, documents and social media while keeping the image useful and readable.</p>
      <div class="tool-actions"><a class="btn btn-primary" href="#compressor">Compress an image</a><a class="btn" href="/tool.php">Browse all tools</a></div>
    </section>
    <figure class="tool-visual"><img src="/assets/home/hero-tools.svg" width="800" height="520" loading="eager" alt="SmartToolz online image tools interface illustration"><figcaption>Compress images directly in your browser.</figcaption></figure>
  </div>

  <div class="tool-workspace" id="compressor">
    <section class="tool-panel">
      <div class="tool-panel-head"><div><span class="tool-kicker">FREE IMAGE COMPRESSOR</span><h2>Compress your image</h2><p>Choose an image, set the quality level and download the compressed JPG.</p></div></div>
      <label class="form-label fw-semibold" for="file">Choose image</label>
      <input id="file" class="form-control mb-3" type="file" accept="image/jpeg,image/png,image/webp">
      <label class="form-label fw-semibold" for="quality">Quality: <span id="qualityValue">75%</span></label>
      <input id="quality" class="form-range mb-3" type="range" min="10" max="100" value="75">
      <button id="compress" class="tool-btn" type="button">Compress Image</button>
      <div id="status" class="tool-result" aria-live="polite">Your compression result will appear here.</div>
      <a id="download" class="tool-btn mt-3 d-none text-center" download="smarttoolz-compressed-image.jpg">Download Compressed Image</a>
    </section>
  </div>

  <section class="tool-content-grid">
    <article class="tool-content-card"><h2>How to compress an image online</h2><ol><li>Select a JPG, PNG or WebP image.</li><li>Choose a quality level. Lower quality generally creates a smaller JPEG.</li><li>Click Compress Image and review the new file size.</li><li>Download the result when it looks good for your use case.</li></ol></article>
    <article class="tool-content-card"><h2>When should you compress an image?</h2><p>Compression is useful when an image is larger than needed for a website, email attachment, document or social post. Smaller images can be easier to store and transfer.</p><p>For very large images, resizing them before compression can reduce dimensions and file size together.</p></article>
  </section>

  <section class="tool-faq"><div class="tool-faq-head"><span class="tool-kicker">FAQ</span><h2>Image Compressor questions</h2></div><details><summary>Does SmartToolz upload my image?</summary><p>The compression operation is performed in your browser using standard image APIs, so the workflow is designed to avoid sending the selected image to a SmartToolz server.</p></details><details><summary>Which formats can I choose?</summary><p>The interface accepts JPG, PNG and WebP images supported by modern browsers.</p></details><details><summary>Will compression always make the file smaller?</summary><p>Not necessarily. Very small or already optimized images may not shrink much. The final size depends on the source image, dimensions and quality setting.</p></details></section>
</main>
<script>
(()=>{const file=document.getElementById('file'),quality=document.getElementById('quality'),qv=document.getElementById('qualityValue'),btn=document.getElementById('compress'),status=document.getElementById('status'),download=document.getElementById('download');quality.addEventListener('input',()=>qv.textContent=quality.value+'%');btn.addEventListener('click',()=>{const f=file.files[0];if(!f){status.textContent='Please choose an image first.';return}const img=new Image(),reader=new FileReader();reader.onload=()=>{img.onload=()=>{const canvas=document.createElement('canvas'),max=2400,scale=Math.min(1,max/Math.max(img.width,img.height));canvas.width=Math.max(1,Math.round(img.width*scale));canvas.height=Math.max(1,Math.round(img.height*scale));const ctx=canvas.getContext('2d');if(!ctx){status.textContent='Your browser could not prepare the image canvas.';return}ctx.drawImage(img,0,0,canvas.width,canvas.height);canvas.toBlob(blob=>{if(!blob){status.textContent='This image could not be compressed in your browser.';return}const url=URL.createObjectURL(blob),before=(f.size/1024).toFixed(1),after=(blob.size/1024).toFixed(1),saved=f.size?Math.max(0,100-(blob.size/f.size*100)):0;status.innerHTML='<strong>'+before+' KB → '+after+' KB</strong><br>Approx. '+saved.toFixed(0)+'% smaller.';download.href=url;download.classList.remove('d-none')},'image/jpeg',Number(quality.value)/100)};img.src=reader.result};reader.readAsDataURL(f)})})();
</script>
<?php smarttoolz_tool_page_end(); ?>
