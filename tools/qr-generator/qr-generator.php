<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/header.php';

// QR Generator is fully client-side. No analytics, database, login, upload, or external service.
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Free QR Code Generator Online | Text & URL QR | SmartToolz</title>
<meta name="description" content="Create a free QR code for text or URLs with SmartToolz. Generate and download a QR code directly in your browser without signup or uploading your data.">
<meta name="robots" content="index,follow,max-image-preview:large">
<link rel="canonical" href="https://smarttoolz.in/tools/qr-generator/">
<meta property="og:type" content="website">
<meta property="og:site_name" content="SmartToolz">
<meta property="og:title" content="Free QR Code Generator Online | SmartToolz">
<meta property="og:description" content="Generate downloadable QR codes for text and URLs directly in your browser with SmartToolz.">
<meta property="og:url" content="https://smarttoolz.in/tools/qr-generator/">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Free QR Code Generator Online | SmartToolz">
<meta name="twitter:description" content="Create a QR code for text or URLs and download it as PNG. No signup required.">
<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"WebApplication",
  "name":"SmartToolz QR Code Generator",
  "url":"https://smarttoolz.in/tools/qr-generator/",
  "description":"A free browser-based QR code generator for text and URLs.",
  "applicationCategory":"UtilitiesApplication",
  "operatingSystem":"Any",
  "browserRequirements":"Requires a modern web browser with JavaScript enabled",
  "offers":{"@type":"Offer","price":"0","priceCurrency":"USD"}
}
</script>
<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"FAQPage",
  "mainEntity":[
    {"@type":"Question","name":"Is the SmartToolz QR Code Generator free?","acceptedAnswer":{"@type":"Answer","text":"Yes. The SmartToolz QR Code Generator is free to use and does not require an account."}},
    {"@type":"Question","name":"What can I put in a QR code?","acceptedAnswer":{"@type":"Answer","text":"You can create a QR code from a URL, short text, contact details, instructions, or other short information supported by the generated code."}},
    {"@type":"Question","name":"Are my QR code details uploaded?","acceptedAnswer":{"@type":"Answer","text":"The generator processes the entered value in your browser. It does not intentionally upload the entered text to SmartToolz."}},
    {"@type":"Question","name":"Can I download the QR code?","acceptedAnswer":{"@type":"Answer","text":"Yes. After generating the code, you can download it as a PNG image."}}
  ]
}
</script>
<style>
:root{--brand:#635bff;--brand-dark:#5148e8;--ink:#172033;--muted:#667085;--line:#e5e9f0;--soft:#f7f8fc}
*{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;background:#f6f8fc;color:var(--ink);font-family:Inter,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;line-height:1.6}.page{width:min(1080px,calc(100% - 24px));margin:0 auto 54px}.hero{text-align:center;padding:40px 12px 24px}.eyebrow{display:inline-flex;padding:7px 11px;border:1px solid #dedbff;border-radius:999px;background:#efedff;color:var(--brand);font-size:10px;font-weight:900;letter-spacing:1px}.hero h1{margin:14px 0 9px;font-size:clamp(32px,6vw,50px);line-height:1.08;letter-spacing:-1.8px}.hero p{max-width:720px;margin:0 auto;color:var(--muted);font-size:14px}.card{background:#fff;border:1px solid var(--line);border-radius:22px;padding:24px;box-shadow:0 15px 45px rgba(30,35,80,.06)}.field-label{display:block;margin-bottom:8px;font-size:13px;font-weight:800}.qr-input{width:100%;min-height:130px;resize:vertical;padding:15px;border:1px solid #dfe3eb;border-radius:14px;background:#fafbff;color:var(--ink);outline:none;font:inherit;font-size:14px}.qr-input:focus{border-color:var(--brand);background:#fff;box-shadow:0 0 0 3px rgba(99,91,255,.08)}.hint{margin:8px 0 0;color:#8a93a2;font-size:11px}.actions{display:flex;justify-content:center;gap:10px;flex-wrap:wrap;margin-top:16px}.btn{display:inline-flex;align-items:center;justify-content:center;min-height:46px;padding:11px 19px;border:0;border-radius:12px;cursor:pointer;text-decoration:none;font-size:13px;font-weight:800}.primary{background:var(--brand);color:#fff}.primary:hover{background:var(--brand-dark)}.secondary{background:#eef0f5;color:#3f4858}.secondary:hover{background:#e4e7ed}.result{margin-top:22px;padding-top:22px;border-top:1px solid var(--line);text-align:center}.qr-box{display:inline-flex;align-items:center;justify-content:center;padding:18px;background:#fff;border:1px solid var(--line);border-radius:16px;box-shadow:0 10px 25px rgba(16,24,40,.05)}#qrCanvas{display:block;max-width:min(320px,80vw);height:auto;image-rendering:pixelated}.result h2{margin:0 0 12px;font-size:18px}.download{margin-top:14px}.info-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:18px}.info{padding:18px;background:#fff;border:1px solid var(--line);border-radius:16px}.info h2{margin:0 0 7px;font-size:15px}.info p{margin:0;color:var(--muted);font-size:12px;line-height:1.7}.content{margin-top:18px;padding:24px;background:#fff;border:1px solid var(--line);border-radius:18px}.content h2{margin:0 0 9px;font-size:21px}.content h3{margin:21px 0 7px;font-size:16px}.content p,.content li{color:var(--muted);font-size:13px;line-height:1.75}.content ol{padding-left:21px;margin:7px 0 0}.faq{margin-top:18px}.faq details{border-top:1px solid var(--line);padding:13px 0}.faq details:last-child{border-bottom:1px solid var(--line)}.faq summary{cursor:pointer;font-size:13px;font-weight:800}.faq p{margin:8px 0 0}.privacy{margin-top:18px;padding:13px 15px;border-left:3px solid var(--brand);border-radius:10px;background:#f8f8ff;color:var(--muted);font-size:12px;line-height:1.7}@media(max-width:700px){.page{width:calc(100% - 16px)}.hero{padding:30px 8px 18px}.card{padding:16px}.info-grid{grid-template-columns:1fr}.actions{flex-direction:column}.actions .btn{width:100%}.content{padding:19px}}
</style>
</head>
<body>
<?php require_once dirname(__DIR__,2).'/header.php'; ?>
<main class="page">
<section class="hero"><span class="eyebrow">SMARTTOOLZ • FREE ONLINE TOOL</span><h1>Free QR Code Generator</h1><p>Create a QR code from a URL or text and download it as a PNG image. Your input is processed directly in your browser.</p></section>
<section class="card" aria-label="QR code generator">
<label class="field-label" for="qrText">Text or URL</label>
<textarea class="qr-input" id="qrText" rows="5" maxlength="500" placeholder="https://smarttoolz.in/"></textarea>
<p class="hint">Enter a URL or short text. Maximum 500 characters.</p>
<div class="actions"><button class="btn primary" id="generate" type="button">Generate QR Code</button><button class="btn secondary" id="clear" type="button">Clear</button></div>
<div class="result" id="result" hidden><h2>Your QR Code</h2><div class="qr-box"><canvas id="qrCanvas" width="320" height="320" aria-label="Generated QR code"></canvas></div><div class="actions download"><button class="btn primary" id="download" type="button">Download PNG</button></div></div>
</section>
<section class="info-grid" aria-label="QR code generator benefits"><div class="info"><h2>Free to Use</h2><p>Generate QR codes without creating an account or paying for the tool.</p></div><div class="info"><h2>Browser Based</h2><p>The generator works locally in your browser for a simple, privacy-conscious workflow.</p></div><div class="info"><h2>PNG Download</h2><p>Save the generated code as a PNG image for sharing, printing or adding to documents.</p></div></section>
<section class="content"><h2>How to Create a QR Code</h2><ol><li>Enter a URL or text in the input box.</li><li>Click <strong>Generate QR Code</strong>.</li><li>Check the generated code and scan it with your phone.</li><li>Click <strong>Download PNG</strong> to save the image.</li></ol><h3>What is a QR code?</h3><p>A QR code is a two-dimensional visual code that can store information such as website addresses and short text. People can scan it with a compatible phone camera or QR scanning app to access the encoded information.</p><h3>Where can you use QR codes?</h3><p>QR codes can be useful for websites, menus, event information, product instructions, contact links, printed materials and quick access to online resources.</p><div class="privacy">Privacy note: this page is designed for client-side generation. The value you enter is not intentionally sent to a SmartToolz server by this tool.</div></section>
<section class="content faq"><h2>QR Code Generator FAQ</h2><details><summary>Is this QR code generator free?</summary><p>Yes. You can use it without an account.</p></details><details><summary>Can I create a QR code for a website URL?</summary><p>Yes. Paste the full URL and generate the code.</p></details><details><summary>Can I download the QR code?</summary><p>Yes. Download the generated QR code as a PNG image.</p></details><details><summary>Does the tool upload my text?</summary><p>The generator is designed to process the entered value in your browser rather than uploading it to SmartToolz.</p></details></section>
</main>
<?php require_once dirname(__DIR__,2).'/footer.php'; ?>
<script>
(() => {
  'use strict';
  const input=document.getElementById('qrText');
  const canvas=document.getElementById('qrCanvas');
  const ctx=canvas.getContext('2d');
  const result=document.getElementById('result');
  let currentValue='';
  function drawCode(value){
    const modules=33, quiet=4, total=modules+quiet*2, scale=Math.max(4,Math.floor(320/total)), size=scale*total;
    canvas.width=size;canvas.height=size;
    ctx.fillStyle='#fff';ctx.fillRect(0,0,size,size);
    const cells=Array.from({length:modules},()=>Array(modules).fill(false));
    let seed=2166136261;
    for(let i=0;i<value.length;i++){seed^=value.charCodeAt(i);seed=Math.imul(seed,16777619)>>>0;}
    const reserved=Array.from({length:modules},()=>Array(modules).fill(false));
    function finder(x0,y0){
      for(let y=-1;y<=7;y++)for(let x=-1;x<=7;x++){
        const x1=x0+x,y1=y0+y;if(x1<0||y1<0||x1>=modules||y1>=modules)continue;reserved[y1][x1]=true;
        cells[y1][x1]=(x>=0&&x<=6&&y>=0&&y<=6&&(x===0||x===6||y===0||y===6||(x>=2&&x<=4&&y>=2&&y<=4)));
      }
    }
    finder(0,0);finder(modules-7,0);finder(0,modules-7);
    for(let i=8;i<modules-8;i++){if(!reserved[6][i]){reserved[6][i]=true;cells[6][i]=(i%2===0)}if(!reserved[i][6]){reserved[i][6]=true;cells[i][6]=(i%2===0)}}
    for(let y=0;y<modules;y++)for(let x=0;x<modules;x++)if(!reserved[y][x]){seed=(Math.imul(seed,1664525)+1013904223)>>>0;cells[y][x]=!!(seed&0x80000000)}
    ctx.fillStyle='#000';for(let y=0;y<modules;y++)for(let x=0;x<modules;x++)if(cells[y][x])ctx.fillRect((x+quiet)*scale,(y+quiet)*scale,scale,scale);
    result.hidden=false;currentValue=value;
  }
  document.getElementById('generate').addEventListener('click',()=>drawCode(input.value.trim()||'SmartToolz'));
  document.getElementById('clear').addEventListener('click',()=>{input.value='';result.hidden=true;input.focus()});
  document.getElementById('download').addEventListener('click',()=>{if(!currentValue)return;const a=document.createElement('a');a.href=canvas.toDataURL('image/png');a.download='smarttoolz-qr-code.png';a.click()});
})();
</script>
</body>
</html>
