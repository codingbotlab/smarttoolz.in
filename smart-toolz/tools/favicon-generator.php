<?php
declare(strict_types=1);
ini_set('display_errors','0');
error_reporting(0);

$tracker = $_SERVER['DOCUMENT_ROOT'].'/analytics/tracker.php';
if (is_file($tracker)) { require_once $tracker; }

function faviconGeneratorAd(string $key): void {
    try {
        if (function_exists('db')) {
            $pdo = db();
            $stmt = $pdo->prepare('SELECT enabled, ad_code FROM ads_settings WHERE ad_key = ? LIMIT 1');
            $stmt->execute([$key]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && (int)$row['enabled'] === 1) {
                $code = trim((string)($row['ad_code'] ?? ''));
                if ($code !== '') echo $code;
            }
        }
    } catch (Throwable $e) {}
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Favicon Generator Online - Create PNG Favicons | Smart-Tooz</title>
<meta name="description" content="Create favicons from an image, emoji or text. Generate multiple sizes and download individual PNG files or one ZIP package directly in your browser.">
<meta name="robots" content="index,follow">
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:Inter,-apple-system,BlinkMacSystemFont,"Segoe UI",Arial,sans-serif;background:#f6f8fc;color:#172033;line-height:1.5}
a{text-decoration:none;color:inherit}button,input,select,textarea{font:inherit}
.site-header{position:sticky;top:0;z-index:1000;background:rgba(255,255,255,.96);backdrop-filter:blur(14px);border-bottom:1px solid #e5e9f0}
.navbar{width:calc(100% - 20px);max-width:none;min-height:72px;margin:auto;display:flex;align-items:center;justify-content:space-between;padding:0 10px}
.logo{display:flex;align-items:center;gap:10px;font-size:21px;font-weight:800}.logo-icon{width:42px;height:42px;display:grid;place-items:center;border-radius:13px;color:#fff;background:linear-gradient(135deg,#635bff,#916cff)}.nav-links{display:flex;gap:28px}.nav-links a{color:#596477;font-size:14px;font-weight:600}
.page-layout{width:100%;max-width:none;margin:0;padding:24px 24px 60px;display:block}.tool-content{width:100%;min-width:0}.tools-sidebar{display:none}
.ad-slot{width:100%;min-height:10px;margin:0 auto 20px;padding:5px;display:flex;align-items:center;justify-content:center;text-align:center;overflow:hidden}.desktop-ad{display:flex}.mobile-ad{display:none}
.tool-header{width:100%;margin-bottom:18px;padding:28px 25px;background:#fff;border:1px solid #e5e9f0;border-radius:20px;text-align:left;box-shadow:0 8px 30px rgba(30,35,80,.035)}.header-inner{display:flex;align-items:center;gap:18px}.tool-icon{width:70px;height:70px;flex:0 0 70px;display:grid;place-items:center;border-radius:17px;background:linear-gradient(135deg,#7b3fe4,#8d5cf5);color:#fff;font-size:34px;box-shadow:0 10px 22px rgba(99,91,255,.18)}.tool-title{flex:1}.tool-title h1{font-size:clamp(28px,4vw,40px);line-height:1.08;letter-spacing:-1.5px}.tool-title h1 span{color:#635bff}.tool-title p{max-width:850px;margin:9px 0 0;color:#707b8e;font-size:14px}.header-badges{display:flex;gap:8px;flex-wrap:wrap;justify-content:flex-end}.pill{padding:7px 11px;border-radius:50px;background:#eef0f5;color:#635bff;font-size:11px;font-weight:800}.pill.green{background:#e5faef;color:#13a25b}
.generator-card{width:100%;padding:20px;background:#fff;border:1px solid #e5e9f0;border-radius:20px;box-shadow:0 15px 45px rgba(30,35,80,.055)}
.tabs{display:grid;grid-template-columns:repeat(3,1fr);gap:0;margin-bottom:18px;background:#eef0f5;border-radius:11px;padding:3px}.tab{height:44px;border:0;background:transparent;color:#4d5768;border-radius:9px;cursor:pointer;font-weight:800;font-size:13px}.tab.active{background:#fff;color:#635bff;box-shadow:0 2px 8px rgba(30,35,80,.08)}
.main-grid{display:grid;grid-template-columns:minmax(300px,380px) minmax(0,1fr);gap:22px;align-items:start}.preview-panel{padding:16px;background:#fafbff;border:1px solid #e5e9f0;border-radius:17px}.preview-heading{display:flex;align-items:center;justify-content:space-between;margin-bottom:11px}.preview-heading strong{font-size:15px}.preview-heading span{font-size:10px;color:#8a93a5}.checker{width:100%;aspect-ratio:1;border-radius:15px;border:1px solid #dfe3eb;background-color:#fff;background-image:linear-gradient(45deg,#eef0f5 25%,transparent 25%),linear-gradient(-45deg,#eef0f5 25%,transparent 25%),linear-gradient(45deg,transparent 75%,#eef0f5 75%),linear-gradient(-45deg,transparent 75%,#eef0f5 75%);background-size:28px 28px;background-position:0 0,0 14px,14px -14px,-14px 0;display:grid;place-items:center;overflow:hidden}.checker canvas{width:92%;height:92%;image-rendering:auto}.upload-row{margin-top:12px}.file-label{display:flex;align-items:center;justify-content:center;gap:8px;width:100%;min-height:44px;padding:10px 14px;border:1px dashed #cbd1dd;border-radius:10px;background:#fff;color:#4d5768;font-size:12px;font-weight:800;cursor:pointer}.file-label:hover{border-color:#635bff;color:#635bff;background:#f8f7ff}.file-label input{display:none}.file-name{margin-top:7px;text-align:center;color:#8a93a5;font-size:10px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
.controls-panel{padding:0}.source-card{padding:15px;background:#fafbff;border:1px solid #e5e9f0;border-radius:14px;margin-bottom:12px}.source-card>label,.field>label,.sizes-title{display:block;margin-bottom:7px;color:#707b8e;font-size:10px;font-weight:850;text-transform:uppercase;letter-spacing:.55px}.source-options{display:grid;grid-template-columns:repeat(3,1fr);gap:8px}.source-option{position:relative}.source-option input{position:absolute;opacity:0;pointer-events:none}.source-option span{display:flex;align-items:center;justify-content:center;gap:6px;height:42px;padding:7px;border:1px solid #dfe3eb;border-radius:9px;background:#fff;color:#4d5768;font-size:11px;font-weight:800;cursor:pointer}.source-option input:checked+span{border-color:#635bff;background:#eeedff;color:#635bff;box-shadow:0 0 0 2px rgba(99,91,255,.08)}
.fields{display:grid;grid-template-columns:repeat(2,1fr);gap:11px}.field{padding:13px;background:#fafbff;border:1px solid #e5e9f0;border-radius:13px}.field.full{grid-column:1/-1}.field input[type=text],.field input[type=color],.field select{width:100%;height:40px;padding:7px 10px;border:1px solid #d7dbea;border-radius:9px;background:#fff;color:#172033;outline:none}.field input[type=color]{padding:3px;cursor:pointer}.field input[type=text]:focus,.field select:focus{border-color:#8d87ff;box-shadow:0 0 0 3px rgba(99,91,255,.1)}.range-row{display:flex;align-items:center;gap:10px}.range-row input{width:100%;accent-color:#635bff}.range-value{min-width:43px;text-align:center;padding:6px 7px;border-radius:8px;background:#eef0f5;color:#635bff;font-size:10px;font-weight:800}.source-input{margin-top:10px}.hidden{display:none!important}
.sizes{padding:13px;background:#fafbff;border:1px solid #e5e9f0;border-radius:13px}.size-actions{display:flex;gap:6px;margin-bottom:9px}.mini{border:0;background:transparent;color:#635bff;font-size:10px;font-weight:800;cursor:pointer}.size-list{display:grid;grid-template-columns:repeat(4,1fr);gap:7px}.size-check{display:flex;align-items:center;justify-content:center;gap:5px;min-height:37px;padding:7px;border:1px solid #dfe3eb;border-radius:8px;background:#fff;color:#4d5768;font-size:10px;font-weight:750;cursor:pointer}.size-check:has(input:checked){border-color:#c9c5ff;background:#f4f3ff;color:#635bff}.size-check input{accent-color:#635bff;width:13px;height:13px}
.actions{display:flex;gap:9px;flex-wrap:wrap;margin-top:13px}.btn{min-height:45px;padding:10px 17px;border:0;border-radius:10px;cursor:pointer;font-size:12px;font-weight:850}.primary{flex:1;background:linear-gradient(135deg,#635bff,#7c6cf2);color:#fff;box-shadow:0 8px 18px rgba(99,91,255,.18)}.secondary{background:#eef0f5;color:#3f4858}.zip{background:#13a25b;color:#fff;min-width:170px}.btn:disabled{opacity:.55;cursor:not-allowed}.status{min-height:17px;margin-top:8px;text-align:center;color:#707b8e;font-size:10px}
.results{margin-top:18px;padding:18px;background:#fafbff;border:1px solid #e5e9f0;border-radius:17px}.results-head{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:12px}.results h2{font-size:17px}.results-head small{color:#8a93a5;font-size:10px}.result-grid{display:grid;grid-template-columns:repeat(8,1fr);gap:9px}.result-item{padding:10px 7px;background:#fff;border:1px solid #e5e9f0;border-radius:11px;text-align:center}.result-item canvas{display:block;width:58px;height:58px;margin:0 auto;border:1px solid #edf0f5;border-radius:8px;background-image:linear-gradient(45deg,#f0f2f6 25%,transparent 25%),linear-gradient(-45deg,#f0f2f6 25%,transparent 25%),linear-gradient(45deg,transparent 75%,#f0f2f6 75%),linear-gradient(-45deg,transparent 75%,#f0f2f6 75%);background-size:12px 12px;background-position:0 0,0 6px,6px -6px,-6px 0}.result-item strong{display:block;margin-top:7px;font-size:10px}.result-item em{display:block;margin-top:2px;color:#8a93a5;font-size:9px;font-style:normal}.download{display:block;margin-top:7px;padding:6px 5px;border-radius:7px;background:#eef0f5;color:#635bff;font-size:9px;font-weight:850}.download:hover{background:#eeedff}
.snippet{margin-top:14px;padding:13px;background:#fff;border:1px solid #e5e9f0;border-radius:12px}.snippet label{display:block;margin-bottom:6px;color:#707b8e;font-size:10px;font-weight:850;text-transform:uppercase}.snippet-row{display:flex;gap:8px}.snippet textarea{width:100%;min-height:76px;padding:9px;border:1px solid #d7dbea;border-radius:8px;background:#fafbff;color:#3f4858;font:10px/1.55 ui-monospace,SFMono-Regular,Consolas,monospace;resize:vertical}.copy{height:38px;padding:0 12px;border:0;border-radius:8px;background:#eef0f5;color:#4d5768;font-size:10px;font-weight:850;cursor:pointer;white-space:nowrap}.copy:hover{background:#e5e7ed;color:#635bff}
.info{width:100%;margin-top:18px;padding:22px;background:#fff;border:1px solid #e5e9f0;border-radius:17px}.info h2{font-size:20px;margin-bottom:7px}.info h3{font-size:15px;margin:16px 0 6px}.info p,.info li{color:#707b8e;font-size:12px;line-height:1.7}.info ul{padding-left:19px}.feature-row{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-top:13px}.feature{padding:12px;background:#fafbff;border:1px solid #e5e9f0;border-radius:11px}.feature strong{display:block;font-size:11px}.feature span{display:block;margin-top:3px;color:#8a93a5;font-size:10px}
footer{padding:32px 20px;background:#151827;color:#fff;text-align:center}footer p{margin-top:5px;color:#aeb5c5;font-size:11px}
@media(max-width:1000px){.main-grid{grid-template-columns:300px 1fr}.result-grid{grid-template-columns:repeat(6,1fr)}.size-list{grid-template-columns:repeat(3,1fr)}}
@media(max-width:760px){.header-inner{align-items:flex-start}.header-badges{display:none}.main-grid{grid-template-columns:1fr}.preview-panel{max-width:420px;margin:auto;width:100%}.result-grid{grid-template-columns:repeat(4,1fr)}.feature-row{grid-template-columns:repeat(2,1fr)}}
@media(max-width:600px){.navbar{min-height:64px}.nav-links{display:none}.page-layout{padding:15px 10px 40px}.desktop-ad{display:none}.mobile-ad{display:flex}.tool-header{padding:20px 16px}.header-inner{gap:12px}.tool-icon{width:54px;height:54px;flex-basis:54px;font-size:26px;border-radius:13px}.tool-title h1{font-size:27px}.tool-title p{font-size:11px}.generator-card{padding:12px}.tabs{margin-bottom:12px}.tab{font-size:11px;height:40px}.fields{grid-template-columns:1fr}.field.full{grid-column:auto}.source-options{grid-template-columns:1fr}.size-list{grid-template-columns:repeat(2,1fr)}.actions{flex-direction:column}.primary,.zip,.secondary{width:100%}.result-grid{grid-template-columns:repeat(2,1fr)}.snippet-row{flex-direction:column}.copy{width:100%}}
</style>
</head>
<body>
<?php require_once dirname(__DIR__) . '/header.php'; ?>
<div class="page-layout">
<main class="tool-content">
<div class="desktop-ad ad-slot"><?php faviconGeneratorAd('top_desktop'); ?></div>
<div class="mobile-ad ad-slot"><?php faviconGeneratorAd('top_mobile'); ?></div>

<section class="tool-header">
<div class="header-inner">
<div class="tool-icon">🖼️</div>
<div class="tool-title"><h1>Favicon <span>Generator</span></h1><p>Create professional favicons from image, emoji or text. Generate multiple sizes and download individually or as a ZIP file.</p></div>
<div class="header-badges"><span class="pill green">Free</span><span class="pill">Fast</span><span class="pill">Multiple Sizes</span><span class="pill">ZIP Download</span></div>
</div>
</section>

<section class="generator-card">
<div class="tabs" role="tablist" aria-label="Favicon source">
<button class="tab active" data-source="image" type="button">🖼️ From Image</button>
<button class="tab" data-source="emoji" type="button">☺️ From Emoji</button>
<button class="tab" data-source="text" type="button">T From Text</button>
</div>

<div class="main-grid">
<div class="preview-panel">
<div class="preview-heading"><strong>Preview</strong><span id="previewSize">512 × 512</span></div>
<div class="checker"><canvas id="preview" width="512" height="512" aria-label="Favicon preview"></canvas></div>
<div class="upload-row" id="uploadWrap"><label class="file-label">☁️ Upload Image<input id="file" type="file" accept="image/png,image/jpeg,image/webp,image/gif,image/svg+xml"></label><div class="file-name" id="fileName">PNG, JPG, JPEG, WEBP, GIF or SVG · max 5 MB</div></div>
</div>

<div class="controls-panel">
<div class="source-card">
<label>Favicon Source</label>
<div class="source-options">
<label class="source-option"><input type="radio" name="source" value="image" checked><span>🖼️ Image</span></label>
<label class="source-option"><input type="radio" name="source" value="emoji"><span>☺️ Emoji</span></label>
<label class="source-option"><input type="radio" name="source" value="text"><span> T Text</span></label>
</div>
<div class="source-input hidden" id="emojiInput"><div class="field"><label>Emoji</label><input id="emoji" type="text" value="🚀" maxlength="4" placeholder="🚀"></div></div>
<div class="source-input hidden" id="textInput"><div class="field"><label>Text / Letter</label><input id="text" type="text" value="S" maxlength="3" placeholder="S"></div></div>
</div>

<div class="fields">
<div class="field"><label>Background Color</label><input id="bg" type="color" value="#FFFFFF"></div>
<div class="field"><label>Text / Icon Color</label><input id="fg" type="color" value="#1537D4"></div>
<div class="field"><label>Corner Radius</label><div class="range-row"><input id="radius" type="range" min="0" max="50" value="0"><span class="range-value" id="radiusValue">0%</span></div></div>
<div class="field"><label>Padding</label><div class="range-row"><input id="padding" type="range" min="0" max="35" value="10"><span class="range-value" id="paddingValue">10%</span></div></div>
<div class="field full"><label>Image Fit</label><select id="fit"><option value="contain">Contain (Recommended)</option><option value="cover">Cover</option></select></div>
</div>

<div class="sizes" style="margin-top:11px">
<div class="sizes-title">Download Sizes</div>
<div class="size-actions"><button class="mini" id="selectAll" type="button">Select all</button><button class="mini" id="clearAll" type="button">Clear all</button></div>
<div class="size-list">
<label class="size-check"><input class="size" type="checkbox" value="16" checked>16×16</label>
<label class="size-check"><input class="size" type="checkbox" value="32" checked>32×32</label>
<label class="size-check"><input class="size" type="checkbox" value="48" checked>48×48</label>
<label class="size-check"><input class="size" type="checkbox" value="64" checked>64×64</label>
<label class="size-check"><input class="size" type="checkbox" value="128" checked>128×128</label>
<label class="size-check"><input class="size" type="checkbox" value="180" checked>180×180</label>
<label class="size-check"><input class="size" type="checkbox" value="192" checked>192×192</label>
<label class="size-check"><input class="size" type="checkbox" value="256" checked>256×256</label>
<label class="size-check"><input class="size" type="checkbox" value="512" checked>512×512</label>
</div>
</div>

<div class="actions"><button class="btn primary" id="generate" type="button">⚙ Generate Favicons</button><button class="btn zip" id="downloadZip" type="button" disabled>⬇ Download All as ZIP</button><button class="btn secondary" id="reset" type="button">Reset</button></div>
<div class="status" id="status">Choose an image, emoji or text, then generate your favicons.</div>
</div>
</div>

<div class="results hidden" id="results"><div class="results-head"><h2>Generated Favicons</h2><small>Click any size to download individually, or download all as ZIP.</small></div><div class="result-grid" id="resultGrid"></div><div class="snippet"><label>HTML Favicon Code</label><div class="snippet-row"><textarea id="snippet" readonly></textarea><button class="copy" id="copySnippet" type="button">Copy Code</button></div></div></div>
</section>

<section class="info">
<h2>Free Online Favicon Generator</h2>
<p>Create website favicon PNG files directly in your browser. Images are processed locally in your browser and are not uploaded to a server by this tool.</p>
<div class="feature-row"><div class="feature"><strong>🖼️ Image</strong><span>PNG, JPG, WEBP, GIF & SVG</span></div><div class="feature"><strong>☺️ Emoji</strong><span>Create an icon from any emoji</span></div><div class="feature"><strong>🔤 Text</strong><span>Use a letter or short text</span></div><div class="feature"><strong>📦 ZIP</strong><span>Download all selected sizes</span></div></div>
<h3>How to use</h3><ul><li>Select From Image, From Emoji, or From Text.</li><li>Upload an image or enter your emoji/text and customize the background, colors, radius and padding.</li><li>Select exactly the favicon sizes you need.</li><li>Click Generate Favicons, then download individual PNG files or one ZIP package.</li><li>Copy the generated HTML snippet into the head of your website.</li></ul>
<h3>Recommended favicon sizes</h3><p>16×16 and 32×32 are common browser icon sizes. 48×48 and 64×64 are useful for larger browser/UI contexts. 180×180 is commonly used for Apple touch icons, while 192×192 and 512×512 are useful for supported PWA/app icons.</p>
</section>

<div class="desktop-ad ad-slot"><?php faviconGeneratorAd('bottom_desktop'); ?></div>
<div class="mobile-ad ad-slot"><?php faviconGeneratorAd('bottom_mobile'); ?></div>
</main>
<?php require_once __DIR__.'/tool-sidebar.php'; ?>
</div>
<footer><strong>Smart-Tooz</strong><p>Free online tools for everyday tasks.</p></footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js" integrity="sha512-XMVd28F1oH/O71fzwBnV7HucLxVwtxf26XV8P4wPk26EDxuGZ91N8bsOttmnomcCD3CS5ZMRL50H0GgOHvegtg==" crossorigin="anonymous"></script>
<script>
(() => {
'use strict';
const $=id=>document.getElementById(id);
const preview=$('preview'), pctx=preview.getContext('2d');
let image=null, generated=new Map(), activeSource='image';
const sizes=[16,32,48,64,128,180,192,256,512];

function roundedRect(c,x,y,w,h,r){r=Math.max(0,Math.min(r,w/2,h/2));c.beginPath();c.moveTo(x+r,y);c.arcTo(x+w,y,x+w,y+h,r);c.arcTo(x+w,y+h,x,y+h,r);c.arcTo(x,y+h,x,y,r);c.arcTo(x,y,x+w,y,r);c.closePath();}
function drawFavicon(c,size){
 const bg=$('bg').value,fg=$('fg').value,r=+$('radius').value,p=+$('padding').value,fit=$('fit').value;
 c.clearRect(0,0,size,size);
 c.save();
 roundedRect(c,0,0,size,size,r*size/100);
 c.clip();
 c.fillStyle=bg;
 c.fillRect(0,0,size,size);
 const box=Math.max(1,size*(1-p/100));
 if(activeSource==='image' && image){
   const iw=image.naturalWidth||image.width, ih=image.naturalHeight||image.height;
   const scale=fit==='cover'?Math.max(box/iw,box/ih):Math.min(box/iw,box/ih);
   const w=iw*scale,h=ih*scale;
   c.drawImage(image,(size-w)/2,(size-h)/2,w,h);
 }else{
   const value=activeSource==='emoji'?($('emoji').value.trim()||'🚀'):($('text').value.trim()||'S');
   c.fillStyle=fg;c.textAlign='center';c.textBaseline='middle';
   const px=activeSource==='emoji'?Math.round(box*.76):Math.round(box*.68);
   c.font=(activeSource==='emoji'?px+'px':'800 '+px+'px')+' '+(activeSource==='emoji'? '"Apple Color Emoji","Segoe UI Emoji","Noto Color Emoji",sans-serif':'Arial,sans-serif');
   c.fillText(value.slice(0,3),size/2,size/2+size*.02);
 }
 c.restore();
}
function drawPreview(){drawFavicon(pctx,512);}
function selectedSizes(){return [...document.querySelectorAll('.size:checked')].map(x=>+x.value).sort((a,b)=>a-b);}
function updateRangeLabels(){$('radiusValue').textContent=$('radius').value+'%';$('paddingValue').textContent=$('padding').value+'%';}
function refreshPreview(){drawPreview();}
function setSource(source){
 activeSource=source;
 document.querySelectorAll('.tab').forEach(b=>b.classList.toggle('active',b.dataset.source===source));
 document.querySelectorAll('input[name="source"]').forEach(r=>r.checked=r.value===source);
 $('uploadWrap').classList.toggle('hidden',source!=='image');$('emojiInput').classList.toggle('hidden',source!=='emoji');$('textInput').classList.toggle('hidden',source!=='text');
 $('status').textContent=source==='image'?(image?'Image loaded. Adjust options and generate.':'Upload an image, then generate your favicons.'):source==='emoji'?'Enter an emoji, then generate your favicons.':'Enter a letter or short text, then generate your favicons.';
 refreshPreview();
}
function makeCanvas(size){const c=document.createElement('canvas');c.width=c.height=size;drawFavicon(c.getContext('2d'),size);return c;}
function canvasBlob(c){return new Promise((resolve,reject)=>c.toBlob(b=>b?resolve(b):reject(new Error('PNG creation failed')),'image/png'));}
function downloadBlob(blob,name){const a=document.createElement('a');const url=URL.createObjectURL(blob);a.href=url;a.download=name;document.body.appendChild(a);a.click();a.remove();setTimeout(()=>URL.revokeObjectURL(url),1500);}
async function generate(){
 const chosen=selectedSizes();if(!chosen.length){$('status').textContent='Select at least one download size.';return;}
 if(activeSource==='image'&&!image){$('status').textContent='Please upload an image first.';return;}
 const grid=$('resultGrid');grid.innerHTML='';generated.clear();$('status').textContent='Generating '+chosen.length+' favicon sizes...';$('generate').disabled=true;
 try{
   for(const size of chosen){
     const c=makeCanvas(size);const blob=await canvasBlob(c);const name='favicon-'+size+'x'+size+'.png';generated.set(size,{blob,name});
     const card=document.createElement('div');card.className='result-item';
     const display=document.createElement('canvas');display.width=display.height=size;display.style.width='58px';display.style.height='58px';display.getContext('2d').drawImage(c,0,0,58,58);
     const strong=document.createElement('strong');strong.textContent=size+' × '+size;const em=document.createElement('em');em.textContent='PNG';
     const link=document.createElement('button');link.type='button';link.className='download';link.textContent='Download';link.onclick=()=>downloadBlob(blob,name);
     card.append(display,strong,em,link);grid.appendChild(card);
   }
   const tags=chosen.map(size=>`<link rel="icon" type="image/png" sizes="${size}x${size}" href="/favicon-${size}x${size}.png">`).join('\n');
   $('snippet').value=tags;
   $('results').classList.remove('hidden');$('downloadZip').disabled=false;$('status').textContent=chosen.length+' favicon sizes generated successfully.';
   $('results').scrollIntoView({behavior:'smooth',block:'nearest'});
 }catch(e){$('status').textContent='Could not generate the favicons. Please try again.';}
 $('generate').disabled=false;
}
async function downloadZip(){
 if(!generated.size)return;
 if(typeof JSZip==='undefined'){$('status').textContent='ZIP library could not load. Individual downloads are still available.';return;}
 $('downloadZip').disabled=true;$('status').textContent='Creating ZIP package...';
 try{
   const zip=new JSZip();const folder=zip.folder('favicons');
   generated.forEach(item=>folder.file(item.name,item.blob));
   folder.file('favicon-html.txt',$('snippet').value);
   const blob=await zip.generateAsync({type:'blob',compression:'DEFLATE',compressionOptions:{level:6}});
   downloadBlob(blob,'favicons.zip');$('status').textContent='ZIP downloaded with '+generated.size+' favicon sizes.';
 }catch(e){$('status').textContent='Could not create ZIP. Please download the PNG files individually.';}
 $('downloadZip').disabled=false;
}
$('file').addEventListener('change',e=>{const f=e.target.files?.[0];if(!f)return;if(f.size>5*1024*1024){$('fileName').textContent='File is larger than 5 MB.';e.target.value='';return;}const url=URL.createObjectURL(f);const im=new Image();im.onload=()=>{image=im;URL.revokeObjectURL(url);$('fileName').textContent=f.name;setSource('image');};im.onerror=()=>{URL.revokeObjectURL(url);$('fileName').textContent='Unable to read this image.';};im.src=url;});
document.querySelectorAll('.tab').forEach(b=>b.addEventListener('click',()=>setSource(b.dataset.source)));
document.querySelectorAll('input[name="source"]').forEach(r=>r.addEventListener('change',()=>setSource(r.value)));
['emoji','text','bg','fg','radius','padding','fit'].forEach(id=>{const el=$(id);el.addEventListener('input',()=>{updateRangeLabels();refreshPreview();});el.addEventListener('change',()=>{updateRangeLabels();refreshPreview();});});
$('generate').addEventListener('click',generate);$('downloadZip').addEventListener('click',downloadZip);
$('selectAll').addEventListener('click',()=>document.querySelectorAll('.size').forEach(x=>x.checked=true));$('clearAll').addEventListener('click',()=>document.querySelectorAll('.size').forEach(x=>x.checked=false));
$('copySnippet').addEventListener('click',async()=>{try{await navigator.clipboard.writeText($('snippet').value);$('copySnippet').textContent='Copied!';setTimeout(()=>$('copySnippet').textContent='Copy Code',1200);}catch(e){$('snippet').select();document.execCommand('copy');$('copySnippet').textContent='Copied!';setTimeout(()=>$('copySnippet').textContent='Copy Code',1200);}});
$('reset').addEventListener('click',()=>{image=null;$('file').value='';$('fileName').textContent='PNG, JPG, JPEG, WEBP, GIF or SVG · max 5 MB';$('bg').value='#FFFFFF';$('fg').value='#1537D4';$('radius').value=0;$('padding').value=10;$('fit').value='contain';$('emoji').value='🚀';$('text').value='S';document.querySelectorAll('.size').forEach(x=>x.checked=true);generated.clear();$('results').classList.add('hidden');$('downloadZip').disabled=true;updateRangeLabels();setSource('image');});
updateRangeLabels();setSource('image');
})();
</script>
</body>
</html>
