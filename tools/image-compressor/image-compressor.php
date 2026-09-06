<?php
declare(strict_types=1);
require_once dirname(__DIR__,2) . '/bootstrap.php';
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Free Image Compressor Online — JPG, PNG & WebP | SmartToolz</title>
<meta name="description" content="Compress JPG, PNG and WebP images online for free. Reduce image file size in your browser with a simple, private image compressor.">
<meta name="robots" content="index,follow,max-image-preview:large">
<link rel="canonical" href="https://smarttoolz.in/tools/image-compressor/">
<meta property="og:type" content="website"><meta property="og:site_name" content="SmartToolz"><meta property="og:title" content="Free Image Compressor Online"><meta property="og:description" content="Reduce JPG, PNG and WebP image file size in your browser."><meta property="og:url" content="https://smarttoolz.in/tools/image-compressor/">
<?php require dirname(__DIR__,2) . '/head.php'; ?>
<script type="application/ld+json">{"@context":"https://schema.org","@type":"WebApplication","name":"SmartToolz Image Compressor","url":"https://smarttoolz.in/tools/image-compressor/","applicationCategory":"MultimediaApplication","operatingSystem":"Web Browser","description":"Free browser-based image compressor for JPG, PNG and WebP images."}</script>
</head><body>
<?php require dirname(__DIR__,2) . '/header.php'; ?>
<main class="tool-shell">
<div class="tool-card-shell">
<section class="page-hero" style="padding-top:10px"><span class="eyebrow">IMAGE TOOLS • PRIVATE IN BROWSER</span><h1>Free Image Compressor Online</h1><p>Reduce JPG, PNG and WebP file size while keeping your images useful for websites, email, documents and social media.</p></section>
<div class="tool-layout">
<section class="tool-main">
<div class="tool-panel">
<h2>Compress an image</h2><p>Select an image, choose a quality level and download the compressed result.</p>
<label class="form-label fw-semibold" for="file">Choose image</label><input id="file" class="form-control mb-3" type="file" accept="image/jpeg,image/png,image/webp">
<label class="form-label fw-semibold" for="quality">Quality: <span id="qualityValue">75%</span></label><input id="quality" class="form-range mb-3" type="range" min="10" max="100" value="75">
<button id="compress" class="tool-btn" type="button">Compress Image</button>
<div id="status" class="tool-result" aria-live="polite">Your result will appear here.</div>
<a id="download" class="tool-btn mt-3 d-none text-center" download="smarttoolz-compressed-image.jpg">Download Compressed Image</a>
</div>
<section class="seo-section"><h2>How to compress an image online</h2><p>Image compression reduces the amount of data stored in an image file. Smaller files can be easier to upload, send by email and use on web pages. The best setting depends on the image and the amount of quality you need.</p><ol><li>Choose a JPG, PNG or WebP image from your device.</li><li>Adjust the quality slider. Higher quality usually creates a larger file.</li><li>Click Compress Image and compare the original and compressed size.</li><li>Download the result when the size and visual quality look right.</li></ol><h3>Why use a browser-based image compressor?</h3><p>SmartToolz processes the selected image in your browser using standard web image APIs. No account is required for the tool, and the workflow is designed for quick everyday compression jobs.</p></section>
<section class="seo-section"><h2>Image compression tips</h2><ul><li>Use moderate quality rather than the maximum setting when file size matters.</li><li>Resize very large images before compression when you do not need full resolution.</li><li>WebP can be useful when your target platform supports it.</li><li>Keep an original copy before making aggressive compression changes.</li></ul></section>
<section class="seo-section"><h2>Frequently asked questions</h2><div class="faq"><details><summary>Does the image compressor require an upload?</summary><p>The compression work is performed in the browser, so the tool is designed to avoid sending the selected image to a SmartToolz server.</p></details><details><summary>What image formats are supported?</summary><p>The interface accepts common JPG, PNG and WebP image files supported by modern browsers.</p></details><details><summary>Does higher quality always mean better results?</summary><p>Higher quality generally preserves more visual detail but usually produces a larger output file. The useful setting depends on your goal.</p></details></div></section>
</section>
<aside class="tool-sidebar"><h2>More Image Tools</h2><a href="/tools/image-compressor/">Image Compressor</a><a href="/tools/image-resizer/">Image Resizer</a><a href="/tools/jpg-to-png/">JPG to PNG</a><a href="/tools/png-to-jpg/">PNG to JPG</a><a href="/tools/">View all tools →</a></aside>
</div></div></main>
<script>
(()=>{const file=document.getElementById('file'),quality=document.getElementById('quality'),qv=document.getElementById('qualityValue'),btn=document.getElementById('compress'),status=document.getElementById('status'),download=document.getElementById('download');quality.addEventListener('input',()=>qv.textContent=quality.value+'%');btn.addEventListener('click',()=>{const f=file.files[0];if(!f){status.textContent='Please choose an image first.';return}const img=new Image();const reader=new FileReader();reader.onload=()=>{img.onload=()=>{const canvas=document.createElement('canvas'),max=2400,scale=Math.min(1,max/Math.max(img.width,img.height));canvas.width=Math.max(1,Math.round(img.width*scale));canvas.height=Math.max(1,Math.round(img.height*scale));canvas.getContext('2d').drawImage(img,0,0,canvas.width,canvas.height);canvas.toBlob(blob=>{if(!blob){status.textContent='This image could not be compressed in your browser.';return}const url=URL.createObjectURL(blob),before=(f.size/1024).toFixed(1),after=(blob.size/1024).toFixed(1),saved=f.size?Math.max(0,100-(blob.size/f.size*100)):0;status.innerHTML='<strong>'+before+' KB → '+after+' KB</strong><br>Approx. '+saved.toFixed(0)+'% smaller.';download.href=url;download.classList.remove('d-none')},'image/jpeg',Number(quality.value)/100)};img.src=reader.result};reader.readAsDataURL(f)})})();
</script>
<?php require dirname(__DIR__,2) . '/footer.php'; ?>
</body></html>
