<?php
declare(strict_types=1);
?>
<!doctype html>
<html lang="en">
<head>
<?php require_once dirname(__DIR__) . '/head.php'; ?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Free Color Picker Online — HEX, RGB, HSL &amp; HSV | SmartToolz</title>
<meta name="description" content="Pick any color and instantly get HEX, RGB, HSL, HSV and CSS values with the free SmartToolz Color Picker. Everything runs in your browser.">
<meta name="robots" content="index,follow,max-image-preview:large">
<link rel="canonical" href="https://smarttoolz.in/tools/color-picker/">
<meta property="og:type" content="website">
<meta property="og:site_name" content="SmartToolz">
<meta property="og:title" content="Free Color Picker Online — HEX, RGB, HSL &amp; HSV">
<meta property="og:description" content="Pick a color and copy its HEX, RGB, HSL, HSV and CSS values instantly in your browser.">
<meta property="og:url" content="https://smarttoolz.in/tools/color-picker/">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Free Color Picker Online — SmartToolz">
<meta name="twitter:description" content="Free browser-based color picker with HEX, RGB, HSL and HSV values.">


<style>
.color-page{width:min(1240px,calc(100% - 32px));margin:0 auto 64px;padding-top:42px}.color-hero{text-align:center;margin-bottom:24px}.color-hero .eyebrow{display:inline-flex;padding:7px 11px;border:1px solid #ddd9ff;border-radius:999px;background:#f0efff;color:#635bff;font-size:10px;font-weight:900;letter-spacing:1px}.color-hero h1{margin:14px 0 9px;font-size:clamp(34px,6vw,58px);line-height:1.05;letter-spacing:-2.5px}.color-hero h1 span{color:#635bff}.color-hero p{max-width:720px;margin:0 auto;color:#667085;font-size:14px;line-height:1.75}.color-card{display:grid;grid-template-columns:minmax(280px,360px) minmax(0,1fr);gap:28px;padding:28px;background:#fff;border:1px solid #e7eaf0;border-radius:24px;box-shadow:0 18px 55px rgba(16,24,40,.08)}.preview-column{display:flex;flex-direction:column}.color-preview{width:100%;aspect-ratio:1;border-radius:22px;border:1px solid #e1e5ed;background:#635bff;box-shadow:inset 0 0 0 1px rgba(255,255,255,.18)}.picker-control{margin-top:14px;padding:10px;border:1px solid #e7eaf0;border-radius:14px;background:#f7f8fb}.picker-control input{width:100%;height:52px;padding:3px;border:0;background:transparent;cursor:pointer}.values{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;align-content:start}.value-box{padding:15px;background:#fafbff;border:1px solid #e7eaf0;border-radius:15px}.value-box.wide{grid-column:1/-1}.value-box label{display:block;margin-bottom:7px;color:#667085;font-size:10px;font-weight:900;letter-spacing:.7px;text-transform:uppercase}.value-row{display:flex;gap:8px}.value-row input{min-width:0;flex:1;height:42px;padding:8px 10px;border:1px solid #dde2eb;border-radius:10px;background:#fff;color:#101828;font-weight:750;outline:0}.value-box.wide input{font-size:18px;color:#635bff}.copy-btn{height:42px;padding:0 12px;border:0;border-radius:10px;background:#eef0f5;color:#344054;font-size:10px;font-weight:900;cursor:pointer}.copy-btn:hover{background:#e7e9ef;color:#635bff}.actions{display:flex;justify-content:center;gap:10px;flex-wrap:wrap;grid-column:1/-1;margin-top:2px}.action-btn{min-height:45px;padding:11px 18px;border:0;border-radius:12px;font-size:12px;font-weight:850;cursor:pointer}.primary-action{background:linear-gradient(135deg,#635bff,#3b82f6);color:#fff;box-shadow:0 11px 25px rgba(99,91,255,.2)}.secondary-action{background:#eef0f5;color:#344054}.color-info{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:20px}.info-card{padding:24px;background:#fff;border:1px solid #e7eaf0;border-radius:19px}.info-card h2{margin:0 0 9px;font-size:21px}.info-card h3{margin:18px 0 7px;font-size:15px}.info-card p,.info-card li{color:#667085;font-size:13px;line-height:1.75}.info-card ul{padding-left:20px;margin:9px 0 0}.swatches{display:grid;grid-template-columns:repeat(6,1fr);gap:9px;margin-top:15px}.swatch{aspect-ratio:1;border-radius:11px;border:1px solid #e1e5ed;cursor:pointer}.swatch:focus-visible{outline:3px solid rgba(99,91,255,.2);outline-offset:2px}@media(max-width:850px){.color-card{grid-template-columns:1fr}.preview-column{max-width:420px;margin:auto;width:100%}.values{grid-template-columns:1fr}.value-box.wide{grid-column:auto}.color-info{grid-template-columns:1fr}}@media(max-width:520px){.color-page{width:calc(100% - 16px);padding-top:28px}.color-card,.info-card{padding:16px}.swatches{grid-template-columns:repeat(3,1fr)}}
</style>
</head>
<body>
<?php require_once dirname(__DIR__) . '/header.php'; ?>



<main class="color-page">
<section class="color-hero"><span class="eyebrow">SMARTTOOLZ • DESIGN TOOL</span><h1>Free <span>Color Picker</span> Online</h1><p>Pick a color and instantly copy its HEX, RGB, HSL, HSV and CSS values. Fast, simple and processed directly in your browser.</p></section>
<section class="color-card" aria-label="Color picker">
<div class="preview-column"><div id="preview" class="color-preview" aria-label="Selected color preview"></div><div class="picker-control"><input id="picker" type="color" value="#635BFF" aria-label="Choose a color"></div><div id="swatches" class="swatches" aria-label="Quick color swatches"></div></div>
<div class="values">
<div class="value-box wide"><label>HEX</label><div class="value-row"><input id="hex" readonly aria-label="HEX value"><button class="copy-btn" data-copy="hex" type="button">COPY</button></div></div>
<div class="value-box"><label>RGB</label><div class="value-row"><input id="rgb" readonly aria-label="RGB value"><button class="copy-btn" data-copy="rgb" type="button">COPY</button></div></div>
<div class="value-box"><label>HSL</label><div class="value-row"><input id="hsl" readonly aria-label="HSL value"><button class="copy-btn" data-copy="hsl" type="button">COPY</button></div></div>
<div class="value-box"><label>HSV</label><div class="value-row"><input id="hsv" readonly aria-label="HSV value"><button class="copy-btn" data-copy="hsv" type="button">COPY</button></div></div>
<div class="value-box"><label>CSS</label><div class="value-row"><input id="css" readonly aria-label="CSS color value"><button class="copy-btn" data-copy="css" type="button">COPY</button></div></div>
<div class="actions"><button class="action-btn primary-action" id="random" type="button">🎲 Random Color</button><button class="action-btn secondary-action" id="reset" type="button">Reset</button></div>
</div>
</section>
<section class="color-info"><article class="info-card"><h2>Free Online Color Picker</h2><p>Use the native color selector to choose any color. SmartToolz instantly calculates common design and web formats so you can copy exactly what you need.</p><h3>How to use</h3><ul><li>Choose a color from the picker.</li><li>Copy the HEX, RGB, HSL, HSV or CSS value.</li><li>Use Random Color for a quick palette value.</li></ul></article><article class="info-card"><h2>Color Values Explained</h2><p><strong>HEX</strong> is a compact hexadecimal representation commonly used in CSS. <strong>RGB</strong> describes red, green and blue channels. <strong>HSL</strong> uses hue, saturation and lightness. <strong>HSV</strong> uses hue, saturation and value.</p><h3>Privacy</h3><p>The tool performs color conversion locally in your browser. No image or account is required.</p></article></section>
</main>


<script>
(() => {
  'use strict';
  const picker=document.getElementById('picker'), preview=document.getElementById('preview');
  const swatches=['#635BFF','#3B82F6','#10B981','#F59E0B','#EF4444','#EC4899','#8B5CF6','#14B8A6','#111827','#FFFFFF','#94A3B8','#F97316'];
  const hexRgb=h=>{h=h.replace('#','');if(h.length===3)h=h.split('').map(x=>x+x).join('');return[parseInt(h.slice(0,2),16),parseInt(h.slice(2,4),16),parseInt(h.slice(4,6),16)]};
  const hsl=(r,g,b)=>{r/=255;g/=255;b/=255;const mx=Math.max(r,g,b),mn=Math.min(r,g,b),d=mx-mn;let h=0,s=0,l=(mx+mn)/2;if(d){s=d/(1-Math.abs(2*l-1));if(mx===r)h=((g-b)/d)%6;else if(mx===g)h=(b-r)/d+2;else h=(r-g)/d+4;h*=60;if(h<0)h+=360}return[Math.round(h),Math.round(s*100),Math.round(l*100)]};
  const hsv=(r,g,b)=>{r/=255;g/=255;b/=255;const mx=Math.max(r,g,b),mn=Math.min(r,g,b),d=mx-mn;let h=0;if(d){if(mx===r)h=((g-b)/d)%6;else if(mx===g)h=(b-r)/d+2;else h=(r-g)/d+4;h*=60;if(h<0)h+=360}return[Math.round(h),Math.round(mx?d/mx*100:0),Math.round(mx*100)]};
  function update(hex){const[r,g,b]=hexRgb(hex),a=hsl(r,g,b),v=hsv(r,g,b),up=hex.toUpperCase();preview.style.background=up;picker.value=up;document.getElementById('hex').value=up;document.getElementById('rgb').value=`rgb(${r}, ${g}, ${b})`;document.getElementById('hsl').value=`hsl(${a[0]}, ${a[1]}%, ${a[2]}%)`;document.getElementById('hsv').value=`hsv(${v[0]}, ${v[1]}%, ${v[2]}%)`;document.getElementById('css').value=`color: ${up};`}
  const swatchBox=document.getElementById('swatches');swatches.forEach(color=>{const b=document.createElement('button');b.type='button';b.className='swatch';b.style.background=color;b.title=color;b.setAttribute('aria-label',`Use ${color}`);b.addEventListener('click',()=>update(color));swatchBox.appendChild(b)});
  picker.addEventListener('input',e=>update(e.target.value));
  document.getElementById('random').addEventListener('click',()=>update('#'+Math.floor(Math.random()*16777216).toString(16).padStart(6,'0')));
  document.getElementById('reset').addEventListener('click',()=>update('#635BFF'));
  document.querySelectorAll('.copy-btn').forEach(btn=>btn.addEventListener('click',async()=>{const field=document.getElementById(btn.dataset.copy);try{await navigator.clipboard.writeText(field.value)}catch(e){field.select();document.execCommand('copy')}btn.textContent='COPIED';setTimeout(()=>btn.textContent='COPY',900)}));
  update('#635BFF');
})();
</script>

<?php require_once dirname(__DIR__) . '/footer.php'; ?>
</body>
</html>
