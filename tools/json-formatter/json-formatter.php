<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/lib/tool-page.php';

$tool = [
    'title' => 'Free JSON Formatter & Validator Online',
    'description' => 'Format, beautify, minify and validate JSON online for free. Process JSON instantly in your browser with SmartToolz.',
    'url' => 'https://smarttoolz.in/tools/json-formatter/'
];
smarttoolz_tool_page_start($tool);
?>
<main class="json-page">
  <section class="json-head">
    <span class="eyebrow">JSON FORMATTER</span>
    <h1>Format and validate JSON</h1>
    <p>Beautify, minify and validate JSON instantly. Your data stays in your browser.</p>
  </section>

  <section class="json-card" aria-label="JSON formatter">
    <div class="toolbar">
      <button type="button" id="format">Format JSON</button>
      <button type="button" id="minify">Minify</button>
      <button type="button" id="validate">Validate</button>
      <button type="button" id="copy">Copy</button>
      <button type="button" id="clear">Clear</button>
    </div>
    <div class="editor-grid">
      <div class="editor-wrap">
        <div class="panel-title"><span>Input JSON</span><span id="inputState">Ready</span></div>
        <textarea id="input" spellcheck="false" placeholder='Paste JSON here...&#10;&#10;Example:&#10;{"name":"SmartToolz","tools":10}'></textarea>
      </div>
      <div class="editor-wrap">
        <div class="panel-title"><span>Result</span><span id="resultState">—</span></div>
        <textarea id="output" spellcheck="false" readonly placeholder="Formatted JSON will appear here..."></textarea>
      </div>
    </div>
    <div class="status" id="status" aria-live="polite">Paste JSON to get started.</div>
  </section>

  <section class="help-grid">
    <article><h2>How to use</h2><ol><li>Paste or type JSON in the input box.</li><li>Click <strong>Format JSON</strong> to beautify it.</li><li>Use <strong>Minify</strong> for compact JSON.</li><li>Use <strong>Copy</strong> to copy the result.</li></ol></article>
    <article><h2>JSON validator</h2><p>Click <strong>Validate</strong> to check whether your JSON is valid. If there is an error, the tool shows the browser's parsing message and points to the relevant location when available.</p></article>
  </section>
</main>

<style>
.json-page{width:min(1080px,calc(100% - 32px));margin:0 auto 70px}.json-head{text-align:center;padding:48px 0 24px}.json-head h1{margin:14px 0 8px;font-size:clamp(38px,6vw,56px);line-height:1;letter-spacing:-2.5px}.json-head p{max-width:680px;margin:0 auto;color:#667085;font-size:15px}.json-card{padding:22px;background:#fff;border:1px solid #e7eaf0;border-radius:24px;box-shadow:0 18px 50px rgba(16,24,40,.07)}.toolbar{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:14px}.toolbar button{padding:10px 14px;border:1px solid #dfe3eb;border-radius:10px;background:#fff;color:#344054;font-size:11px;font-weight:800;cursor:pointer}.toolbar button:hover{border-color:#a9a0ff;background:#f7f6ff;color:#5541ff}.toolbar button:first-child{border:0;background:linear-gradient(90deg,#5d46ff,#3e7dff);color:#fff}.toolbar button:last-child{margin-left:auto;color:#d04444}.toolbar button:last-child:hover{border-color:#f0b5b5;background:#fff7f7;color:#c13232}.editor-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}.editor-wrap{min-width:0}.panel-title{display:flex;align-items:center;justify-content:space-between;margin:0 2px 7px;color:#344054;font-size:11px;font-weight:800}.panel-title span:last-child{color:#7b849d;font-size:9px;font-weight:700}.editor-wrap textarea{width:100%;height:440px;resize:vertical;padding:16px;border:1px solid #dfe3eb;border-radius:14px;outline:0;background:#fcfdff;color:#172033;font:13px/1.6 ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,monospace}.editor-wrap textarea:focus{border-color:#8d80ff;box-shadow:0 0 0 3px rgba(92,70,255,.08)}.editor-wrap textarea[readonly]{background:#f8f9fc}.status{min-height:20px;margin-top:10px;padding:8px 10px;border-radius:9px;background:#f7f8fb;color:#69738e;font-size:10px}.status.ok{background:#f0faf4;color:#18794e}.status.error{background:#fff4f4;color:#c13232}.help-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:18px}.help-grid article{padding:22px;border:1px solid #e7eaf0;border-radius:18px;background:#fff}.help-grid h2{margin:0 0 10px;font-size:17px}.help-grid p,.help-grid li{color:#667085;font-size:12px;line-height:1.75}.help-grid ol{margin:0;padding-left:20px}@media(max-width:700px){.json-page{width:calc(100% - 20px)}.json-card{padding:15px}.editor-grid{grid-template-columns:1fr}.editor-wrap textarea{height:300px}.toolbar button{flex:1}.toolbar button:last-child{margin-left:0}.help-grid{grid-template-columns:1fr}}
</style>

<script>
(()=>{
 const input=document.getElementById('input'),output=document.getElementById('output'),status=document.getElementById('status'),inputState=document.getElementById('inputState'),resultState=document.getElementById('resultState');
 const parse=()=>{try{return{value:JSON.parse(input.value),error:null}}catch(e){return{value:null,error:e}}};
 const showError=e=>{status.className='status error';status.textContent='Invalid JSON: '+e.message;inputState.textContent='Invalid';resultState.textContent='Error'};
 const showOk=msg=>{status.className='status ok';status.textContent=msg;inputState.textContent='Valid JSON';resultState.textContent='Ready'};
 document.getElementById('format').addEventListener('click',()=>{if(!input.value.trim()){status.className='status error';status.textContent='Please enter some JSON first.';return}const r=parse();if(r.error){showError(r.error);return}output.value=JSON.stringify(r.value,null,2);showOk('Valid JSON — formatted successfully.');resultState.textContent='Formatted'});
 document.getElementById('minify').addEventListener('click',()=>{if(!input.value.trim()){status.className='status error';status.textContent='Please enter some JSON first.';return}const r=parse();if(r.error){showError(r.error);return}output.value=JSON.stringify(r.value);showOk('Valid JSON — minified successfully.');resultState.textContent='Minified'});
 document.getElementById('validate').addEventListener('click',()=>{if(!input.value.trim()){status.className='status error';status.textContent='Please enter some JSON first.';return}const r=parse();if(r.error){showError(r.error);return}showOk('✓ Valid JSON.');resultState.textContent='Valid'});
 document.getElementById('copy').addEventListener('click',async()=>{const value=output.value||input.value;if(!value){status.className='status error';status.textContent='Nothing to copy.';return}try{await navigator.clipboard.writeText(value);status.className='status ok';status.textContent='Copied to clipboard.'}catch{output.value?output.select():input.select();document.execCommand('copy');status.className='status ok';status.textContent='Copied to clipboard.'}});
 document.getElementById('clear').addEventListener('click',()=>{input.value='';output.value='';inputState.textContent='Ready';resultState.textContent='—';status.className='status';status.textContent='Cleared.';input.focus()});
 input.addEventListener('input',()=>{if(!input.value.trim()){inputState.textContent='Ready';return}inputState.textContent='Not checked';status.className='status';status.textContent='Ready to format or validate.'});
})();
</script>
<?php smarttoolz_tool_page_end(); ?>
