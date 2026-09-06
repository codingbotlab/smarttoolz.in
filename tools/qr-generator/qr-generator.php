<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/lib/tool-page.php';

$tool = [
    'title' => 'Free QR Code Generator Online — Create QR Codes',
    'description' => 'Create and download QR codes for URLs, text, email, phone numbers and more. Fast, free and easy to use.',
    'url' => 'https://smarttoolz.in/tools/qr-generator/'
];
smarttoolz_tool_page_start($tool);
?>
<main class="qr-page">
  <section class="qr-head">
    <span class="eyebrow">QR GENERATOR</span>
    <h1>Create a QR code</h1>
    <p>Generate a QR code from any text or URL instantly, then download it as an image.</p>
  </section>

  <section class="qr-card" aria-label="QR code generator">
    <div class="qr-layout">
      <div class="qr-form">
        <label for="qrText">Text or URL</label>
        <textarea id="qrText" maxlength="2000" placeholder="https://example.com&#10;&#10;or type any text you want to share..."></textarea>
        <div class="input-meta"><span id="count">0 / 2000</span></div>

        <div class="options">
          <div class="option"><label for="size">Size</label><select id="size"><option value="200">200 × 200</option><option value="300" selected>300 × 300</option><option value="500">500 × 500</option><option value="800">800 × 800</option></select></div>
          <div class="option"><label for="dark">Foreground</label><input id="dark" type="color" value="#111936"></div>
          <div class="option"><label for="light">Background</label><input id="light" type="color" value="#ffffff"></div>
        </div>

        <button class="generate" id="generate" type="button">Generate QR Code</button>
        <p class="status" id="status" aria-live="polite">Enter text or a URL to generate your QR code.</p>
      </div>

      <div class="qr-result">
        <div class="preview-title"><span>Your QR code</span><span id="ready">Ready</span></div>
        <div class="qr-box" id="qrBox"><div class="empty"><span>▦</span><small>Your QR code will appear here</small></div></div>
        <button class="download" id="download" type="button" disabled>Download PNG</button>
      </div>
    </div>
  </section>

  <section class="help-grid">
    <article><h2>How to create a QR code</h2><ol><li>Enter a URL, text, contact detail or other information.</li><li>Choose the size and colors if needed.</li><li>Click Generate QR Code.</li><li>Download the PNG image and share it anywhere.</li></ol></article>
    <article><h2>What can you encode?</h2><p>Use QR codes for websites, plain text, Wi‑Fi details, contact information, email addresses, phone numbers, promotions, menus and more. The content is generated in your browser.</p></article>
  </section>
</main>

<style>
.qr-page{width:min(920px,calc(100% - 32px));margin:0 auto 70px}.qr-head{text-align:center;padding:48px 0 24px}.qr-head h1{margin:14px 0 8px;font-size:clamp(38px,6vw,56px);line-height:1;letter-spacing:-2.5px}.qr-head p{max-width:680px;margin:0 auto;color:#667085;font-size:15px}.qr-card{padding:22px;background:#fff;border:1px solid #e7eaf0;border-radius:24px;box-shadow:0 18px 50px rgba(16,24,40,.07)}.qr-layout{display:grid;grid-template-columns:minmax(0,1fr) 350px;gap:22px}.qr-form{min-width:0;padding:5px}.qr-form>label{display:block;margin-bottom:8px;color:#344054;font-size:12px;font-weight:800}.qr-form textarea{width:100%;height:190px;resize:vertical;padding:15px;border:1px solid #dfe3eb;border-radius:14px;outline:0;background:#fcfdff;color:#172033;font:14px/1.6 Arial,sans-serif}.qr-form textarea:focus{border-color:#8d80ff;box-shadow:0 0 0 3px rgba(92,70,255,.08)}.input-meta{display:flex;justify-content:flex-end;margin-top:4px;color:#8a93aa;font-size:9px}.options{display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px;margin-top:15px}.option{display:flex;flex-direction:column;gap:6px}.option label{color:#667085;font-size:10px;font-weight:800}.option select{height:38px;padding:0 9px;border:1px solid #dfe3eb;border-radius:9px;background:#fff;color:#344054;font-size:10px}.option input[type=color]{width:100%;height:38px;padding:3px;border:1px solid #dfe3eb;border-radius:9px;background:#fff;cursor:pointer}.generate,.download{width:100%;height:46px;margin-top:16px;border:0;border-radius:12px;background:linear-gradient(90deg,#5d46ff,#3e7dff);color:#fff;font-size:12px;font-weight:900;cursor:pointer;box-shadow:0 10px 22px rgba(80,69,255,.18)}.generate:disabled,.download:disabled{opacity:.55;cursor:not-allowed}.status{min-height:16px;margin:9px 0 0;color:#69738e;text-align:center;font-size:10px}.qr-result{padding:15px;border:1px solid #e7eaf0;border-radius:17px;background:#fafbff;display:flex;flex-direction:column}.preview-title{display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;color:#344054;font-size:11px;font-weight:800}.preview-title span:last-child{color:#7b849d;font-size:9px}.qr-box{min-height:350px;display:grid;place-items:center;border:1px dashed #d9def0;border-radius:13px;background:#fff;padding:18px}.qr-box canvas,.qr-box img{display:block;max-width:100%;height:auto}.empty{display:flex;flex-direction:column;align-items:center;gap:8px;color:#a0a8b8;text-align:center}.empty span{font-size:54px;line-height:1}.empty small{font-size:10px}.download{margin-top:14px;background:#111936;box-shadow:none}.help-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:18px}.help-grid article{padding:22px;border:1px solid #e7eaf0;border-radius:18px;background:#fff}.help-grid h2{margin:0 0 10px;font-size:17px}.help-grid p,.help-grid li{color:#667085;font-size:12px;line-height:1.75}.help-grid ol{margin:0;padding-left:20px}@media(max-width:760px){.qr-page{width:calc(100% - 20px)}.qr-card{padding:15px}.qr-layout{grid-template-columns:1fr}.qr-result{order:-1}.qr-box{min-height:300px}.options{grid-template-columns:1fr}.help-grid{grid-template-columns:1fr}}
</style>

<script src="https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js"></script>
<script>
(()=>{
 const input=document.getElementById('qrText'),count=document.getElementById('count'),size=document.getElementById('size'),dark=document.getElementById('dark'),light=document.getElementById('light'),generate=document.getElementById('generate'),download=document.getElementById('download'),box=document.getElementById('qrBox'),status=document.getElementById('status'),ready=document.getElementById('ready');
 let canvas=null;
 const updateCount=()=>count.textContent=input.value.length+' / 2000';
 const make=()=>{
   const value=input.value.trim();
   if(!value){status.textContent='Enter text or a URL first.';ready.textContent='Waiting';download.disabled=true;box.innerHTML='<div class="empty"><span>▦</span><small>Your QR code will appear here</small></div>';canvas=null;return}
   if(typeof QRious==='undefined'){status.textContent='QR generator is still loading. Please try again.';return}
   canvas=document.createElement('canvas');
   const qr=new QRious({element:canvas,value:value,size:Number(size.value),foreground:dark.value,background:light.value,level:'H'});
   box.replaceChildren(canvas);ready.textContent='Generated';download.disabled=false;status.textContent='QR code generated successfully.';
   download.onclick=()=>{const link=document.createElement('a');link.download='smarttoolz-qr-code.png';link.href=canvas.toDataURL('image/png');link.click()};
 };
 input.addEventListener('input',updateCount);generate.addEventListener('click',make);size.addEventListener('change',make);dark.addEventListener('input',make);light.addEventListener('input',make);updateCount();
})();
</script>
<?php smarttoolz_tool_page_end(); ?>
