<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/lib/tool-page.php';

$tool = [
    'title' => 'Free Image Resizer Online — Resize JPG, PNG & WebP',
    'description' => 'Resize JPG, PNG and WebP images online for free. Set exact dimensions in your browser with SmartToolz Image Resizer.',
    'url' => 'https://smarttoolz.in/tools/image-resizer/'
];
smarttoolz_tool_page_start($tool);
?>
<main class="tool-page">
  <div class="tool-hero-grid">
    <section class="tool-intro">
      <span class="eyebrow">IMAGE TOOLS • BROWSER BASED</span>
      <h1>Free Image Resizer Online</h1>
      <p>Resize images to exact dimensions for websites, forms, social media, documents and everyday design work.</p>
      <div class="tool-actions"><a class="btn btn-primary" href="#resizer">Resize an image</a><a class="btn" href="/tool.php">Browse all tools</a></div>
    </section>
    <figure class="tool-visual"><img src="/assets/home/hero-tools.svg" width="800" height="520" loading="eager" alt="SmartToolz online image tools interface illustration"><figcaption>Resize images directly in your browser.</figcaption></figure>
  </div>

  <div class="tool-workspace" id="resizer">
    <section class="tool-panel">
      <div class="tool-panel-head"><div><span class="tool-kicker">FREE IMAGE RESIZER</span><h2>Resize your image</h2><p>Choose an image, enter a target width and height, then download the resized file.</p></div></div>
      <label class="form-label fw-semibold" for="file">Choose image</label>
      <input id="file" class="form-control mb-3" type="file" accept="image/jpeg,image/png,image/webp">
      <div class="row g-3">
        <div class="col-md-6"><label class="form-label fw-semibold" for="width">Width (px)</label><input id="width" class="form-control" type="number" min="1" step="1" placeholder="1200"></div>
        <div class="col-md-6"><label class="form-label fw-semibold" for="height">Height (px)</label><input id="height" class="form-control" type="number" min="1" step="1" placeholder="800"></div>
      </div>
      <div class="form-check my-3"><input class="form-check-input" id="keep" type="checkbox" checked><label class="form-check-label" for="keep">Keep aspect ratio</label></div>
      <button id="resize" class="tool-btn" type="button">Resize Image</button>
      <div id="status" class="tool-result" aria-live="polite">Your resized image details will appear here.</div>
      <a id="download" class="tool-btn mt-3 d-none text-center" download="smarttoolz-resized-image.jpg">Download Resized Image</a>
    </section>
  </div>

  <section class="tool-content-grid">
    <article class="tool-content-card"><h2>How to resize an image online</h2><ol><li>Select a JPG, PNG or WebP image.</li><li>Enter the target width and height in pixels.</li><li>Keep aspect ratio enabled when you want to avoid stretching.</li><li>Click Resize Image and download the new file.</li></ol></article>
    <article class="tool-content-card"><h2>Choosing the right image dimensions</h2><p>For website images, use the dimensions your layout actually needs instead of keeping a very large original. For profile images, banners and forms, exact pixel dimensions can make cropping and placement easier.</p><p>Changing dimensions can affect image sharpness and file size. Check the result at the size where it will actually be displayed.</p></article>
  </section>

  <section class="tool-faq"><div class="tool-faq-head"><span class="tool-kicker">FAQ</span><h2>Image Resizer questions</h2></div><details><summary>Can I resize images without uploading them to a server?</summary><p>Yes. This tool uses browser image APIs, so the resize operation is designed to happen on your device.</p></details><details><summary>Which image formats are supported?</summary><p>The interface accepts JPG, PNG and WebP files supported by modern browsers.</p></details><details><summary>Why should I keep aspect ratio enabled?</summary><p>Keeping the aspect ratio helps prevent the image from looking stretched or squashed when only one dimension needs to change.</p></details></section>
</main>
<script>
(()=>{const file=document.getElementById('file'),w=document.getElementById('width'),h=document.getElementById('height'),keep=document.getElementById('keep'),btn=document.getElementById('resize'),status=document.getElementById('status'),download=document.getElementById('download');let ratio=1;file.addEventListener('change',()=>{const f=file.files[0];if(!f)return;const img=new Image(),r=new FileReader();r.onload=()=>{img.onload=()=>{ratio=img.width/img.height;if(!w.value)w.value=img.width;if(!h.value)h.value=img.height};img.src=r.result};r.readAsDataURL(f)});w.addEventListener('input',()=>{if(keep.checked&&ratio)h.value=Math.max(1,Math.round(Number(w.value)/ratio))});h.addEventListener('input',()=>{if(keep.checked&&ratio)w.value=Math.max(1,Math.round(Number(h.value)*ratio))});btn.addEventListener('click',()=>{const f=file.files[0],width=Math.max(1,Number(w.value)),height=Math.max(1,Number(h.value));if(!f||!width||!height){status.textContent='Choose an image and enter valid dimensions.';return}const img=new Image(),r=new FileReader();r.onload=()=>{img.onload=()=>{const c=document.createElement('canvas');c.width=width;c.height=height;c.getContext('2d').drawImage(img,0,0,width,height);c.toBlob(blob=>{if(!blob){status.textContent='This image could not be resized in your browser.';return}const url=URL.createObjectURL(blob);status.innerHTML='<strong>'+width+' × '+height+' px</strong><br>New file size: '+(blob.size/1024).toFixed(1)+' KB';download.href=url;download.classList.remove('d-none')},'image/jpeg',.92)};img.src=r.result};r.readAsDataURL(f)})})();
</script>
<?php smarttoolz_tool_page_end(); ?>
