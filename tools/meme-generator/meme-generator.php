<?php
declare(strict_types=1);
function smartToolzAd(string $key): void {}
function smartToolzMemeAd(string $key): void {}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Meme Generator - Create Memes Online | SmartToolz</title>
<meta name="description" content="Create custom memes online for free. Upload an image, add top and bottom text, customize the style and download your meme.">
<meta name="robots" content="index,follow">
<style>
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{font-family:Inter,-apple-system,BlinkMacSystemFont,"Segoe UI",Arial,sans-serif;background:#f6f8fc;color:#172033;line-height:1.5}
a{text-decoration:none;color:inherit}
button,input,select{font:inherit}
.site-header{position:sticky;top:0;z-index:1000;background:rgba(255,255,255,.96);backdrop-filter:blur(14px);border-bottom:1px solid #e5e9f0}
.navbar{width:calc(100% - 20px);max-width:1400px;min-height:72px;margin:auto;display:flex;align-items:center;justify-content:space-between}
.logo{display:flex;align-items:center;gap:10px;font-size:21px;font-weight:800}
.logo-icon{width:42px;height:42px;display:grid;place-items:center;border-radius:13px;color:#fff;background:linear-gradient(135deg,#635bff,#916cff);box-shadow:0 8px 22px rgba(99,91,255,.18)}
.nav-links{display:flex;align-items:center;gap:28px}.nav-links a{color:#596477;font-size:14px;font-weight:600}.nav-links a:hover{color:#635bff}
.menu-button{display:none;border:0;background:transparent;font-size:27px;cursor:pointer}
.page-layout{width:min(1400px,calc(100% - 30px));margin:28px auto 60px;display:flex;flex-direction:row;align-items:flex-start;gap:24px}
.tool-content{min-width:0;flex:1;order:1}.page-layout>.tools-sidebar{order:2}
.ad-slot{width:100%;min-height:10px;margin:0 auto 24px;padding:5px;display:flex;align-items:center;justify-content:center;text-align:center;overflow:hidden}.desktop-ad{display:flex}.mobile-ad{display:none}
.tool-header{margin-bottom:22px;padding:34px 25px;background:#fff;border:1px solid #e5e9f0;border-radius:22px;text-align:center;box-shadow:0 8px 30px rgba(30,35,80,.035)}
.badge{display:inline-block;margin-bottom:12px;padding:7px 13px;border-radius:50px;background:#eeedff;color:#635bff;font-size:12px;font-weight:800}
.tool-header h1{font-size:clamp(32px,5vw,48px);line-height:1.08;letter-spacing:-1.8px}.tool-header h1 span{color:#635bff}.tool-header p{max-width:680px;margin:13px auto 0;color:#707b8e;font-size:14px}
.meme-card{padding:28px;background:#fff;border:1px solid #e5e9f0;border-radius:22px;box-shadow:0 15px 45px rgba(30,35,80,.06)}
.upload{padding:38px 20px;border:2px dashed #d7dbea;border-radius:18px;background:#fafbff;text-align:center;cursor:pointer;transition:.2s}.upload:hover,.upload.drag{border-color:#635bff;background:#f5f3ff}.upload-icon{width:62px;height:62px;margin:0 auto 12px;display:grid;place-items:center;border-radius:17px;background:#eeedff;font-size:29px}.upload h2{font-size:20px}.upload p{margin-top:5px;color:#707b8e;font-size:13px}.upload small{display:block;margin-top:8px;color:#635bff;font-weight:700}#file{display:none}
.editor{display:none;margin-top:22px}.editor-grid{display:grid;grid-template-columns:minmax(280px,1fr) minmax(280px,360px);gap:24px;align-items:start}
.canvas-box{padding:16px;background:#fafbff;border:1px solid #e5e9f0;border-radius:17px;text-align:center}.canvas-wrap{width:100%;overflow:hidden;border-radius:12px;background:#e9ecf2}.canvas-wrap canvas{display:block;width:100%;height:auto;max-height:620px;object-fit:contain}
.controls{padding:20px;background:#fafbff;border:1px solid #e5e9f0;border-radius:17px}.control{margin-bottom:17px}.control:last-child{margin-bottom:0}.control label{display:block;margin-bottom:7px;color:#4d5768;font-size:12px;font-weight:800}
.control input[type=text],.control select{width:100%;height:43px;padding:9px 11px;border:1px solid #d7dbea;border-radius:10px;background:#fff;color:#172033;outline:none}.control input[type=text]:focus,.control select:focus{border-color:#635bff}
.range-top{display:flex;justify-content:space-between;font-size:12px;font-weight:800;color:#4d5768}.range-value{color:#635bff}.control input[type=range]{width:100%;accent-color:#635bff}
.color-row{display:grid;grid-template-columns:1fr 1fr;gap:10px}.color-field{display:flex;align-items:center;gap:9px;padding:8px 10px;background:#fff;border:1px solid #d7dbea;border-radius:10px}.color-field input{width:34px;height:30px;border:0;padding:0;background:transparent}
.check{display:flex;align-items:center;gap:9px;font-size:12px;font-weight:700;color:#4d5768}.actions{display:flex;flex-wrap:wrap;justify-content:center;gap:10px;margin-top:20px}
.btn{min-height:44px;padding:11px 18px;border:0;border-radius:12px;cursor:pointer;font-size:13px;font-weight:800}.primary{background:#635bff;color:#fff}.primary:hover{background:#5148e8}.secondary{background:#eef0f5;color:#3f4858}.secondary:hover{background:#e4e7ed}
.error{display:none;margin-top:14px;padding:11px 13px;border-radius:10px;background:#fff0f0;color:#c33;text-align:center;font-size:13px}.result{display:none;margin-top:22px;padding:18px;background:#fafbff;border:1px solid #e5e9f0;border-radius:17px}.result img{display:block;max-width:100%;max-height:650px;margin:auto;border-radius:10px}
.info{margin-top:24px;padding:25px;background:#fff;border:1px solid #e5e9f0;border-radius:19px}.info h2{font-size:21px;margin-bottom:9px}.info h3{font-size:16px;margin:18px 0 7px}.info p,.info li{color:#707b8e;font-size:13px;line-height:1.7}.info ul{padding-left:20px}
footer{padding:35px 20px;background:#151827;color:#fff;text-align:center}footer p{margin-top:6px;color:#aeb5c5;font-size:12px}
@media(max-width:900px){.editor-grid{grid-template-columns:1fr}.canvas-box{max-width:650px;margin:auto}}
@media(max-width:700px){.navbar{min-height:64px}.nav-links{display:none;position:absolute;top:64px;left:0;right:0;padding:16px;flex-direction:column;background:#fff;border-bottom:1px solid #e5e9f0}.menu-button{display:block}.page-layout{width:calc(100% - 16px);margin:16px auto 40px;flex-direction:column;gap:18px}.tool-content{width:100%}.desktop-ad{display:none}.mobile-ad{display:flex}.tool-header{padding:27px 17px}.meme-card{padding:15px}.upload{padding:36px 12px}.color-row{grid-template-columns:1fr}.tools-sidebar{width:100%!important;position:relative!important;top:auto!important;max-height:none!important}}
</style>
</head>
<body>
<?php require_once dirname(__DIR__) . '/header.php'; ?>

<div class="page-layout">
<main class="tool-content">
<div class="desktop-ad ad-slot"><?php smartToolzMemeAd('top_desktop'); ?></div><div class="mobile-ad ad-slot"><?php smartToolzMemeAd('top_mobile'); ?></div>
<section class="tool-header"><span class="badge">MEME GENERATOR</span><h1>Create Your <span>Meme</span></h1><p>Upload an image, add custom text and create a share-ready meme directly in your browser.</p></section>
<section class="meme-card">
<div class="upload" id="upload"><div class="upload-icon">😂</div><h2>Upload Meme Image</h2><p>Click here or drag &amp; drop JPG, PNG or WebP</p><small id="fileName">No image selected</small><input id="file" type="file" accept="image/jpeg,image/png,image/webp"></div>
<div class="editor" id="editor"><div class="editor-grid">
<div class="canvas-box"><div class="canvas-wrap"><canvas id="canvas"></canvas></div></div>
<div class="controls">
<div class="control"><label for="topText">Top Text</label><input id="topText" type="text" maxlength="120" placeholder="TOP TEXT"></div>
<div class="control"><label for="bottomText">Bottom Text</label><input id="bottomText" type="text" maxlength="120" placeholder="BOTTOM TEXT"></div>
<div class="control"><div class="range-top"><span>Font Size</span><span class="range-value" id="fontValue">48 px</span></div><input id="fontSize" type="range" min="18" max="120" value="48"></div>
<div class="control"><div class="range-top"><span>Text Outline</span><span class="range-value" id="strokeValue">6 px</span></div><input id="stroke" type="range" min="0" max="16" value="6"></div>
<div class="control"><label>Text Colors</label><div class="color-row"><div class="color-field"><span>Fill</span><input id="fill" type="color" value="#ffffff"></div><div class="color-field"><span>Stroke</span><input id="strokeColor" type="color" value="#000000"></div></div></div>
<div class="control"><label for="font">Font</label><select id="font"><option value="Impact">Impact</option><option value="Arial Black">Arial Black</option><option value="Arial">Arial</option><option value="sans-serif">Sans Serif</option></select></div>
<label class="check"><input id="uppercase" type="checkbox" checked> Force uppercase text</label>
<div class="actions"><button class="btn primary" id="download" type="button">Download Meme</button><button class="btn secondary" id="reset" type="button">Start Over</button></div>
</div></div></div><div class="error" id="error"></div>
</section>
<section class="info"><h2>Free Meme Generator</h2><p>Make a custom meme from your own image without uploading it to a server. The meme is rendered locally in your browser and can be downloaded as a PNG.</p><h3>How to make a meme</h3><ul><li>Upload a JPG, PNG or WebP image.</li><li>Enter top and bottom text.</li><li>Adjust font size, outline, colors and font.</li><li>Click Download Meme to save the finished image.</li></ul><h3>Privacy</h3><p>Your selected image stays in your browser while you create the meme. No server-side image upload is required.</p></section>
<div class="desktop-ad ad-slot"><?php smartToolzMemeAd('bottom_desktop'); ?></div><div class="mobile-ad ad-slot"><?php smartToolzMemeAd('bottom_mobile'); ?></div>
</main>
<?php
$sidebarFile = __DIR__ . '/tool-sidebar.php';
if (is_file($sidebarFile)) {
    require $sidebarFile;
}
?>
</div>

<script>
(function(){
'use strict';
const $=id=>document.getElementById(id);
const file=$('file'),upload=$('upload'),editor=$('editor'),canvas=$('canvas'),ctx=canvas.getContext('2d'),fileName=$('fileName'),error=$('error');
let img=null;
function showError(t){error.textContent=t;error.style.display='block'}
function clearError(){error.textContent='';error.style.display='none'}
function loadFile(f){
 clearError(); if(!f)return;
 if(!['image/jpeg','image/png','image/webp'].includes(f.type)){showError('Please select a JPG, PNG or WebP image.');return}
 const r=new FileReader();
 r.onload=e=>{img=new Image();img.onload=()=>{canvas.width=img.naturalWidth;canvas.height=img.naturalHeight;fileName.textContent=f.name;editor.style.display='block';draw()};img.onerror=()=>showError('Unable to read this image.');img.src=e.target.result};
 r.onerror=()=>showError('Unable to read this file.');
 r.readAsDataURL(f);
}
function wrapText(text,maxWidth){const words=text.split(/\s+/).filter(Boolean),lines=[];let line='';for(const word of words){const test=line?line+' '+word:word;if(ctx.measureText(test).width>maxWidth&&line){lines.push(line);line=word}else{line=test}}if(line)lines.push(line);return lines}
function drawText(text,y){if(!text)return;const size=Number($('fontSize').value),font=$('font').value;ctx.font='900 '+size+'px "'+font+'",Arial,sans-serif';ctx.textAlign='center';ctx.textBaseline='middle';const max=canvas.width-40,lines=wrapText(text,max),lineHeight=size*1.08,total=lines.length*lineHeight;let yy=y-total/2+lineHeight/2;ctx.lineJoin='round';for(const line of lines){ctx.lineWidth=Number($('stroke').value)*2;ctx.strokeStyle=$('strokeColor').value;ctx.fillStyle=$('fill').value;ctx.strokeText(line,canvas.width/2,yy);ctx.fillText(line,canvas.width/2,yy);yy+=lineHeight}}
function draw(){if(!img)return;ctx.clearRect(0,0,canvas.width,canvas.height);ctx.drawImage(img,0,0,canvas.width,canvas.height);const upper=$('uppercase').checked;const top=(upper?$('topText').value.toUpperCase():$('topText').value).trim();const bottom=(upper?$('bottomText').value.toUpperCase():$('bottomText').value).trim();const margin=Math.max(30,Number($('fontSize').value)*1.3);drawText(top,margin);drawText(bottom,canvas.height-margin)}
['topText','bottomText','fontSize','stroke','fill','strokeColor','font','uppercase'].forEach(id=>$(id).addEventListener('input',()=>{if(id==='fontSize')$('fontValue').textContent=$('fontSize').value+' px';if(id==='stroke')$('strokeValue').textContent=$('stroke').value+' px';draw()}));
upload.addEventListener('click',()=>file.click());file.addEventListener('change',e=>loadFile(e.target.files[0]));
['dragenter','dragover'].forEach(t=>upload.addEventListener(t,e=>{e.preventDefault();upload.classList.add('drag')}));['dragleave','drop'].forEach(t=>upload.addEventListener(t,e=>{e.preventDefault();upload.classList.remove('drag')}));upload.addEventListener('drop',e=>loadFile(e.dataTransfer.files[0]));
$('download').onclick=()=>{if(!img){showError('Please upload an image first.');return}canvas.toBlob(b=>{if(!b){showError('Could not create the meme.');return}const url=URL.createObjectURL(b),a=document.createElement('a');a.href=url;a.download='smarttoolz-meme.png';document.body.appendChild(a);a.click();a.remove();setTimeout(()=>URL.revokeObjectURL(url),1000)},'image/png')};
$('reset').onclick=()=>{img=null;file.value='';fileName.textContent='No image selected';editor.style.display='none';clearError()};
const menu=$('menuButton'),nav=$('navLinks');if(menu&&nav)menu.addEventListener('click',()=>nav.classList.toggle('open'));
})();
</script>


<?php require_once dirname(__DIR__) . '/footer.php'; ?>
</body>
</html>