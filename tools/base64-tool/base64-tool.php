<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/lib/tool-page.php';
$tool = [
    'title' => 'Free Base64 Encoder & Decoder Online',
    'description' => 'Encode text to Base64 or decode Base64 strings instantly in your browser with SmartToolz.',
    'url' => 'https://smarttoolz.in/tools/base64-tool/'
];
smarttoolz_tool_page_start($tool);
?>
<main class="b64-page">
  <section class="tool-head">
    <span class="eyebrow">BASE64 TOOL</span>
    <h1>Base64 Encoder &amp; Decoder</h1>
    <p>Encode text to Base64 or decode a Base64 string instantly. Everything runs in your browser.</p>
  </section>
  <section class="b64-card">
    <div class="tabs"><button class="tab active" data-mode="encode">Encode</button><button class="tab" data-mode="decode">Decode</button></div>
    <label class="field-title" id="inputLabel">Text to encode</label>
    <textarea id="input" placeholder="Enter or paste your text here..." spellcheck="false"></textarea>
    <div class="actions"><button class="primary" id="convert">Encode to Base64</button><button id="clear">Clear</button></div>
    <label class="field-title">Result</label>
    <textarea id="output" readonly placeholder="Your result will appear here..."></textarea>
    <div class="result-row"><span id="status">Ready</span><button id="copy">Copy Result</button></div>
  </section>
  <section class="info-grid"><article><h2>How to use</h2><ol><li>Choose Encode or Decode.</li><li>Enter your text or Base64 string.</li><li>Click the action button.</li><li>Copy the result.</li></ol></article><article><h2>Private by design</h2><p>Your text is processed locally in your browser. SmartToolz does not need to upload your content to perform the conversion.</p></article></section>
</main>
<style>
.b64-page{width:min(900px,calc(100% - 32px));margin:0 auto 70px}.tool-head{text-align:center;padding:48px 0 24px}.eyebrow{color:#5b43ff;font-size:10px;font-weight:900;letter-spacing:1.8px}.tool-head h1{margin:14px 0 8px;font-size:clamp(38px,6vw,56px);line-height:1;letter-spacing:-2.5px}.tool-head p{max-width:680px;margin:0 auto;color:#667085;font-size:15px}.b64-card{padding:24px;background:#fff;border:1px solid #e7eaf0;border-radius:24px;box-shadow:0 18px 50px rgba(16,24,40,.07)}.tabs{display:flex;gap:8px;padding:5px;margin-bottom:20px;border-radius:12px;background:#f4f5f9}.tab{flex:1;border:0;border-radius:9px;padding:11px;background:transparent;color:#667085;font-size:11px;font-weight:900;cursor:pointer}.tab.active{background:#fff;color:#5541ff;box-shadow:0 2px 8px rgba(16,24,40,.08)}.field-title{display:block;margin:0 0 7px;color:#344054;font-size:11px;font-weight:800}.b64-card textarea{box-sizing:border-box;width:100%;min-height:190px;resize:vertical;padding:15px;border:1px solid #dfe3eb;border-radius:13px;outline:0;background:#fcfdff;color:#172033;font:13px/1.6 ui-monospace,SFMono-Regular,Menlo,monospace}.b64-card textarea:focus{border-color:#8d80ff;box-shadow:0 0 0 3px rgba(92,70,255,.08)}.actions{display:flex;gap:9px;margin:12px 0 20px}.actions button,.result-row button{padding:10px 15px;border:1px solid #dfe3eb;border-radius:10px;background:#fff;color:#344054;font-size:10px;font-weight:900;cursor:pointer}.actions .primary{border:0;background:linear-gradient(90deg,#5d46ff,#3e7dff);color:#fff}.result-row{display:flex;align-items:center;justify-content:space-between;margin-top:8px}.result-row span{color:#69738e;font-size:10px}.result-row button{padding:8px 12px}.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:18px}.info-grid article{padding:22px;border:1px solid #e7eaf0;border-radius:18px;background:#fff}.info-grid h2{margin:0 0 10px;font-size:17px}.info-grid p,.info-grid li{color:#667085;font-size:12px;line-height:1.75}.info-grid ol{margin:0;padding-left:20px}@media(max-width:650px){.b64-page{width:calc(100% - 20px)}.b64-card{padding:16px}.info-grid{grid-template-columns:1fr}.actions{flex-direction:column}.actions button{width:100%}}
</style>
<script>
(()=>{let mode='encode';const input=document.getElementById('input'),output=document.getElementById('output'),label=document.getElementById('inputLabel'),convert=document.getElementById('convert'),status=document.getElementById('status');const setMode=m=>{mode=m;document.querySelectorAll('.tab').forEach(x=>x.classList.toggle('active',x.dataset.mode===m));label.textContent=m==='encode'?'Text to encode':'Base64 to decode';convert.textContent=m==='encode'?'Encode to Base64':'Decode Base64';input.placeholder=m==='encode'?'Enter or paste your text here...':'Paste Base64 here...';output.value='';status.textContent='Ready'};document.querySelectorAll('.tab').forEach(x=>x.onclick=()=>setMode(x.dataset.mode));convert.onclick=()=>{try{if(!input.value){status.textContent='Please enter some text.';return}if(mode==='encode'){output.value=btoa(unescape(encodeURIComponent(input.value)));status.textContent='Encoded successfully.'}else{output.value=decodeURIComponent(escape(atob(input.value.trim())));status.textContent='Decoded successfully.'}}catch(e){output.value='';status.textContent='Invalid Base64 string.'}};document.getElementById('clear').onclick=()=>{input.value='';output.value='';status.textContent='Cleared.'};document.getElementById('copy').onclick=async()=>{if(!output.value){status.textContent='Nothing to copy.';return}try{await navigator.clipboard.writeText(output.value);status.textContent='Copied to clipboard.'}catch{output.select();document.execCommand('copy');status.textContent='Copied to clipboard.'}}})();
</script>
<?php smarttoolz_tool_page_end(); ?>
