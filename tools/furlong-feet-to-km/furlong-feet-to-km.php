<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/lib/tool-page.php';

$tool = [
    'title' => 'Furlong and Feet to Kilometer Converter',
    'description' => 'Convert furlongs and feet to kilometers instantly with a fast, simple online converter.',
    'url' => 'https://smarttoolz.in/tools/furlong-feet-to-km/'
];
smarttoolz_tool_page_start($tool);
?>
<main class="tool-page">
  <section class="tool-intro">
    <span class="tool-kicker">UNIT CONVERTER</span>
    <h1>Furlong + Feet to Kilometer</h1>
    <p>Enter furlongs and feet and get the kilometer value instantly.</p>
  </section>

  <section class="tool-workspace" aria-label="Furlong and feet to kilometer converter">
    <div class="tool-panel">
      <div class="input-grid">
        <label>Furlongs
          <input id="furlongs" type="number" inputmode="decimal" min="0" step="any" placeholder="e.g. 5">
        </label>
        <label>Feet
          <input id="feet" type="number" inputmode="decimal" min="0" step="any" placeholder="e.g. 10">
        </label>
      </div>

      <div class="result-box" aria-live="polite">
        <span>RESULT</span>
        <strong id="result">0</strong>
        <b>km</b>
      </div>

      <div class="button-row">
        <button class="tool-btn" id="copy" type="button">Copy Result</button>
        <button class="tool-btn secondary" id="reset" type="button">Reset</button>
      </div>
    </div>
  </section>

  <section class="tool-content-grid">
    <article class="tool-content-card">
      <h2>Conversion formula</h2>
      <p>Furlongs × 0.201168 + Feet × 0.0003048 = Kilometers.</p>
    </article>
    <article class="tool-content-card">
      <h2>Quick conversion</h2>
      <p>1 furlong = 0.201168 km and 1 foot = 0.0003048 km.</p>
    </article>
  </section>
</main>

<style>
.input-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}.input-grid label{display:flex;flex-direction:column;gap:7px;color:#344054;font-size:11px;font-weight:800}.input-grid input{height:50px;width:100%;padding:0 13px;border:1px solid #dfe3eb;border-radius:12px;background:#fcfdff;color:#172033;font:14px Arial,sans-serif;outline:0}.input-grid input:focus{border-color:#8d80ff;box-shadow:0 0 0 3px rgba(92,70,255,.08)}.result-box{margin-top:18px;padding:20px;text-align:center;border:1px solid #ddd9ff;border-radius:17px;background:#f8f7ff}.result-box span{display:block;color:#5b43f5;font-size:8px;font-weight:900;letter-spacing:1.3px}.result-box strong{display:inline-block;margin-top:5px;font-size:38px;line-height:1.1;letter-spacing:-1.5px}.result-box b{margin-left:5px;font-size:18px}.button-row{display:flex;gap:9px;margin-top:15px}.button-row .tool-btn{flex:1}.tool-btn.secondary{background:#fff;color:#5541ff;border:1px solid #dfe3eb}.tool-btn{cursor:pointer}@media(max-width:600px){.input-grid{grid-template-columns:1fr}.button-row{flex-direction:column}}
</style>

<script>
(()=>{
  const furlongs=document.getElementById('furlongs');
  const feet=document.getElementById('feet');
  const result=document.getElementById('result');
  const copy=document.getElementById('copy');
  const reset=document.getElementById('reset');

  const value=el=>{const n=parseFloat(el.value);return Number.isFinite(n)&&n>=0?n:0};
  const format=n=>n.toLocaleString('en-US',{maximumFractionDigits:12});
  const calculate=()=>{result.textContent=format(value(furlongs)*0.201168+value(feet)*0.0003048)};

  furlongs.addEventListener('input',calculate);
  feet.addEventListener('input',calculate);
  reset.addEventListener('click',()=>{furlongs.value='';feet.value='';calculate();furlongs.focus()});
  copy.addEventListener('click',async()=>{
    try{await navigator.clipboard.writeText(result.textContent+' km');copy.textContent='Copied!';setTimeout(()=>copy.textContent='Copy Result',1400)}
    catch(e){copy.textContent='Copy failed';setTimeout(()=>copy.textContent='Copy Result',1400)}
  });
  calculate();
})();
</script>
<?php smarttoolz_tool_page_end(); ?>
