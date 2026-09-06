<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/lib/tool-page.php';

$tool = [
    'title' => 'Free Case Converter Online — Uppercase, Lowercase & More',
    'description' => 'Convert text to uppercase, lowercase, title case, sentence case and more online for free with SmartToolz.',
    'url' => 'https://smarttoolz.in/tools/case-converter/'
];
smarttoolz_tool_page_start($tool);
?>
<main class="case-page">
  <section class="case-head">
    <span class="eyebrow">CASE CONVERTER</span>
    <h1>Convert text case</h1>
    <p>Change your text to uppercase, lowercase, title case, sentence case and more instantly.</p>
  </section>

  <section class="case-card" aria-label="Case converter">
    <div class="toolbar">
      <button type="button" data-case="upper">UPPERCASE</button>
      <button type="button" data-case="lower">lowercase</button>
      <button type="button" data-case="title">Title Case</button>
      <button type="button" data-case="sentence">Sentence case</button>
      <button type="button" data-case="alternating">aLtErNaTiNg</button>
      <button type="button" data-case="inverse">iNVERSE</button>
      <button type="button" id="clear">Clear</button>
    </div>
    <textarea id="text" placeholder="Type or paste your text here..."></textarea>
    <div class="bottom-row">
      <div class="stats"><span id="words">0 words</span><span id="chars">0 characters</span><span id="lines">0 lines</span></div>
      <button class="copy-button" id="copy" type="button">Copy Text</button>
    </div>
    <p class="status" id="status" aria-live="polite"></p>
  </section>

  <section class="help-grid">
    <article><h2>How it works</h2><ol><li>Type or paste your text.</li><li>Choose the case you need.</li><li>Copy the converted text.</li></ol></article>
    <article><h2>Case options</h2><p><strong>UPPERCASE</strong> makes every letter capital. <strong>lowercase</strong> makes every letter small. <strong>Title Case</strong> capitalizes the first letter of each word. <strong>Sentence case</strong> capitalizes sentence beginnings.</p></article>
  </section>
</main>

<style>
.case-page{width:min(920px,calc(100% - 32px));margin:0 auto 70px}.case-head{text-align:center;padding:48px 0 24px}.case-head h1{margin:14px 0 8px;font-size:clamp(38px,6vw,56px);line-height:1;letter-spacing:-2.5px}.case-head p{max-width:680px;margin:0 auto;color:#667085;font-size:15px}.case-card{padding:22px;background:#fff;border:1px solid #e7eaf0;border-radius:24px;box-shadow:0 18px 50px rgba(16,24,40,.07)}.toolbar{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:14px}.toolbar button,.copy-button{padding:10px 13px;border:1px solid #dfe3eb;border-radius:10px;background:#fff;color:#344054;font-size:11px;font-weight:800;cursor:pointer}.toolbar button:hover{border-color:#a9a0ff;background:#f7f6ff;color:#5541ff}.toolbar button:last-child{margin-left:auto;color:#d04444}.toolbar button:last-child:hover{border-color:#f0b5b5;background:#fff7f7;color:#c13232}.case-card textarea{width:100%;min-height:360px;resize:vertical;padding:20px;border:1px solid #dfe3eb;border-radius:15px;outline:0;background:#fcfdff;color:#172033;font:16px/1.65 Arial,sans-serif}.case-card textarea:focus{border-color:#8d80ff;box-shadow:0 0 0 3px rgba(92,70,255,.08)}.bottom-row{display:flex;align-items:center;justify-content:space-between;gap:15px;margin-top:12px}.stats{display:flex;flex-wrap:wrap;gap:12px;color:#7b849d;font-size:11px}.copy-button{border:0;background:linear-gradient(90deg,#5d46ff,#3e7dff);color:#fff;padding:11px 18px}.status{min-height:15px;margin:8px 0 0;text-align:right;color:#5d46ff;font-size:10px}.help-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:18px}.help-grid article{padding:22px;border:1px solid #e7eaf0;border-radius:18px;background:#fff}.help-grid h2{margin:0 0 10px;font-size:17px}.help-grid p,.help-grid li{color:#667085;font-size:12px;line-height:1.75}.help-grid ol{margin:0;padding-left:20px}@media(max-width:650px){.case-page{width:calc(100% - 20px)}.case-card{padding:15px}.toolbar button{flex:1}.toolbar button:last-child{margin-left:0}.case-card textarea{min-height:300px;padding:15px;font-size:15px}.bottom-row{align-items:stretch;flex-direction:column}.copy-button{width:100%}.status{text-align:center}.help-grid{grid-template-columns:1fr}}
</style>

<script>
(()=>{
 const text=document.getElementById('text'),words=document.getElementById('words'),chars=document.getElementById('chars'),lines=document.getElementById('lines'),copy=document.getElementById('copy'),clear=document.getElementById('clear'),status=document.getElementById('status');
 const update=()=>{const v=text.value;const wordCount=v.trim()?v.trim().split(/\s+/u).length:0;words.textContent=wordCount+' '+(wordCount===1?'word':'words');chars.textContent=v.length+' '+(v.length===1?'character':'characters');lines.textContent=(v?v.split(/\r?\n/).length:0)+' '+((v.split(/\r?\n/).length===1)?'line':'lines')};
 const titleCase=v=>v.toLowerCase().replace(/(^|[\s\-])([^\s\-])/gu,(m,a,b)=>a+b.toUpperCase());
 const sentenceCase=v=>v.toLowerCase().replace(/(^|[.!?]\s+)([a-z])/giu,(m,a,b)=>a+b.toUpperCase());
 text.addEventListener('input',update);
 document.querySelectorAll('[data-case]').forEach(btn=>btn.addEventListener('click',()=>{const v=text.value;switch(btn.dataset.case){case'upper':text.value=v.toUpperCase();break;case'lower':text.value=v.toLowerCase();break;case'title':text.value=titleCase(v);break;case'sentence':text.value=sentenceCase(v);break;case'alternating':text.value=[...v].map((c,i)=>i%2?c.toLowerCase():c.toUpperCase()).join('');break;case'inverse':text.value=[...v].map(c=>c===c.toUpperCase()?c.toLowerCase():c.toUpperCase()).join('');break}update();text.focus()}));
 clear.addEventListener('click',()=>{text.value='';update();status.textContent='Text cleared.';text.focus()});
 copy.addEventListener('click',async()=>{if(!text.value){status.textContent='Nothing to copy.';return}try{await navigator.clipboard.writeText(text.value);status.textContent='Copied to clipboard.'}catch{ text.select();document.execCommand('copy');status.textContent='Copied to clipboard.'}});
 update();
})();
</script>
<?php smarttoolz_tool_page_end(); ?>
