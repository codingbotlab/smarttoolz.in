<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/lib/tool-page.php';

$tool = [
    'title' => 'Free Text to Slug Generator Online',
    'description' => 'Convert titles and text into clean, URL-friendly slugs instantly with SmartToolz.',
    'url' => 'https://smarttoolz.in/tools/text-to-slug/'
];
smarttoolz_tool_page_start($tool);
?>
<main class="slug-page">
  <section class="tool-head">
    <span class="eyebrow">TEXT TO SLUG</span>
    <h1>Generate a clean URL slug</h1>
    <p>Turn any title or phrase into a lowercase, URL-friendly slug instantly in your browser.</p>
  </section>

  <section class="slug-card" aria-label="Text to slug generator">
    <label class="field-title" for="input">Your text</label>
    <textarea id="input" placeholder="Example: 10 Best Free Tools for Students" spellcheck="false"></textarea>
    <div class="controls">
      <label><input id="unicode" type="checkbox"> Keep Unicode letters</label>
      <label><input id="trim" type="checkbox" checked> Remove extra words &amp; spaces</label>
    </div>
    <label class="field-title" for="output">Slug</label>
    <div class="output-wrap"><input id="output" readonly placeholder="your-clean-url-slug"><button id="copy" type="button">Copy</button></div>
    <div class="actions"><button class="primary" id="generate" type="button">Generate Slug</button><button id="clear" type="button">Clear</button></div>
    <p class="status" id="status" aria-live="polite">Type something to generate a slug.</p>
  </section>

  <section class="info-grid">
    <article><h2>How to use</h2><ol><li>Enter a title or phrase.</li><li>Choose whether to keep Unicode characters.</li><li>Click Generate Slug.</li><li>Copy the URL-ready result.</li></ol></article>
    <article><h2>Why use a slug?</h2><p>A clean slug makes a page URL easier to read, share and understand. Common punctuation and unnecessary spaces are converted into simple hyphens.</p></article>
  </section>
</main>
<style>
.slug-page{width:min(900px,calc(100% - 32px));margin:0 auto 70px}.tool-head{text-align:center;padding:48px 0 24px}.eyebrow{color:#5b43ff;font-size:10px;font-weight:900;letter-spacing:1.8px}.tool-head h1{margin:14px 0 8px;font-size:clamp(38px,6vw,56px);line-height:1;letter-spacing:-2.5px}.tool-head p{max-width:680px;margin:0 auto;color:#667085;font-size:15px;line-height:1.6}.slug-card{padding:24px;background:#fff;border:1px solid #e7eaf0;border-radius:24px;box-shadow:0 18px 50px rgba(16,24,40,.07)}.field-title{display:block;margin:0 0 7px;color:#344054;font-size:11px;font-weight:800}.slug-card textarea{box-sizing:border-box;width:100%;min-height:150px;resize:vertical;padding:15px;border:1px solid #dfe3eb;border-radius:13px;outline:0;background:#fcfdff;color:#172033;font:14px/1.6 Arial,sans-serif}.slug-card textarea:focus,.output-wrap input:focus{border-color:#8d80ff;box-shadow:0 0 0 3px rgba(92,70,255,.08)}.controls{display:flex;flex-wrap:wrap;gap:14px;margin:12px 0 20px}.controls label{display:flex;align-items:center;gap:7px;color:#667085;font-size:11px;font-weight:700}.controls input{accent-color:#5b43ff}.output-wrap{display:flex;gap:8px}.output-wrap input{box-sizing:border-box;min-width:0;flex:1;height:45px;padding:0 13px;border:1px solid #dfe3eb;border-radius:11px;outline:0;background:#f8f9fc;color:#172033;font:13px ui-monospace,SFMono-Regular,Menlo,monospace}.output-wrap button,.actions button{height:45px;padding:0 15px;border:1px solid #dfe3eb;border-radius:11px;background:#fff;color:#344054;font-size:10px;font-weight:900;cursor:pointer}.actions{display:flex;gap:9px;margin-top:12px}.actions button{flex:1}.actions .primary{border:0;background:linear-gradient(90deg,#5d46ff,#3e7dff);color:#fff}.status{min-height:16px;margin:10px 0 0;text-align:center;color:#69738e;font-size:10px}.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:18px}.info-grid article{padding:22px;border:1px solid #e7eaf0;border-radius:18px;background:#fff}.info-grid h2{margin:0 0 10px;font-size:17px}.info-grid p,.info-grid li{color:#667085;font-size:12px;line-height:1.75}.info-grid ol{margin:0;padding-left:20px}@media(max-width:650px){.slug-page{width:calc(100% - 20px)}.slug-card{padding:16px}.output-wrap{flex-direction:column}.output-wrap button{width:100%}.info-grid{grid-template-columns:1fr}.actions{flex-direction:column}}
</style>
<script>
(()=>{const input=document.getElementById('input'),output=document.getElementById('output'),unicode=document.getElementById('unicode'),status=document.getElementById('status');const makeSlug=()=>{let s=input.value.trim();if(!s){output.value='';status.textContent='Type something to generate a slug.';return}if(!unicode.checked){s=s.normalize('NFKD').replace(/[\u0300-\u036f]/g,'').replace(/[^\w\s-]/g,' ')}else{s=s.normalize('NFKC').replace(/[^\p{L}\p{N}\s-]/gu,' ')}s=s.replace(/[\s_-]+/g,'-').replace(/^-+|-+$/g,'').toLowerCase();output.value=s;status.textContent=s?'Slug generated successfully.':'No URL-friendly characters found.'};document.getElementById('generate').onclick=makeSlug;input.addEventListener('input',makeSlug);unicode.addEventListener('change',makeSlug);document.getElementById('clear').onclick=()=>{input.value='';output.value='';status.textContent='Cleared.';input.focus()};document.getElementById('copy').onclick=async()=>{if(!output.value){status.textContent='Nothing to copy.';return}try{await navigator.clipboard.writeText(output.value);status.textContent='Copied to clipboard.'}catch{output.select();document.execCommand('copy');status.textContent='Copied to clipboard.'}}})();
</script>
<?php smarttoolz_tool_page_end(); ?>
