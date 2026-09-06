<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/lib/tool-page.php';

$tool = [
    'title' => 'Free Unit Converter Online — Length, Weight, Temperature & More',
    'description' => 'Convert common units instantly with SmartToolz. Supports length, weight, temperature, area, volume and speed conversions.',
    'url' => 'https://smarttoolz.in/tools/unit-converter/'
];
smarttoolz_tool_page_start($tool);
?>
<main class="unit-page">
<section class="unit-head"><span class="eyebrow">UNIT CONVERTER</span><h1>Convert units instantly</h1><p>Simple, fast conversions for everyday measurements. Everything runs directly in your browser.</p></section>
<section class="unit-card" aria-label="Unit converter">
  <div class="category-tabs" role="tablist">
    <button class="active" data-category="length">Length</button><button data-category="weight">Weight</button><button data-category="temperature">Temperature</button><button data-category="area">Area</button><button data-category="volume">Volume</button><button data-category="speed">Speed</button>
  </div>
  <div class="converter-grid">
    <label>From<input id="fromValue" type="number" inputmode="decimal" value="1" step="any"><select id="fromUnit"></select></label>
    <div class="swap-wrap"><button id="swap" type="button" aria-label="Swap units">⇄</button></div>
    <label>To<input id="toValue" type="number" inputmode="decimal" readonly><select id="toUnit"></select></label>
  </div>
  <div class="result"><span>RESULT</span><strong id="resultText">—</strong></div>
</section>
<section class="help-grid"><article><h2>How to use</h2><ol><li>Choose a conversion category.</li><li>Enter the value and select the source unit.</li><li>Select the unit you want to convert to.</li><li>Copy or use the result.</li></ol></article><article><h2>Supported conversions</h2><p>Length, weight, temperature, area, volume and speed are included with common everyday units.</p></article></section>
</main>
<style>
.unit-page{width:min(920px,calc(100% - 32px));margin:0 auto 70px}.unit-head{text-align:center;padding:48px 0 24px}.unit-head h1{margin:14px 0 8px;font-size:clamp(38px,6vw,56px);line-height:1;letter-spacing:-2.5px}.unit-head p{max-width:680px;margin:0 auto;color:#667085;font-size:15px}.unit-card{padding:22px;background:#fff;border:1px solid #e7eaf0;border-radius:24px;box-shadow:0 18px 50px rgba(16,24,40,.07)}.category-tabs{display:flex;flex-wrap:wrap;gap:8px;margin-bottom:20px}.category-tabs button{padding:10px 14px;border:1px solid #dfe3eb;border-radius:10px;background:#fff;color:#344054;font-size:11px;font-weight:800;cursor:pointer}.category-tabs button.active,.category-tabs button:hover{border-color:#5d46ff;background:#f4f2ff;color:#5541ff}.converter-grid{display:grid;grid-template-columns:1fr 55px 1fr;gap:14px;align-items:end}.converter-grid label{display:grid;gap:7px;color:#344054;font-size:11px;font-weight:800}.converter-grid input,.converter-grid select{width:100%;border:1px solid #dfe3eb;border-radius:11px;outline:0;background:#fcfdff;color:#172033;padding:13px;font:14px Arial,sans-serif}.converter-grid input{font-size:22px;font-weight:700}.converter-grid input:focus,.converter-grid select:focus{border-color:#8d80ff;box-shadow:0 0 0 3px rgba(92,70,255,.08)}.swap-wrap{display:flex;justify-content:center}.swap-wrap button{width:44px;height:44px;border:1px solid #dfe3eb;border-radius:12px;background:#f7f6ff;color:#5b43ff;font-size:20px;font-weight:800;cursor:pointer}.result{margin-top:18px;padding:19px;border-radius:15px;background:linear-gradient(135deg,#f7f5ff,#f8fbff);border:1px solid #e6e2ff}.result span{display:block;color:#69738e;font-size:9px;font-weight:900;letter-spacing:1.5px}.result strong{display:block;margin-top:5px;color:#172033;font-size:25px;word-break:break-word}.help-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:18px}.help-grid article{padding:22px;border:1px solid #e7eaf0;border-radius:18px;background:#fff}.help-grid h2{margin:0 0 10px;font-size:17px}.help-grid p,.help-grid li{color:#667085;font-size:12px;line-height:1.75}.help-grid ol{margin:0;padding-left:20px}@media(max-width:700px){.unit-page{width:calc(100% - 20px)}.unit-card{padding:15px}.converter-grid{grid-template-columns:1fr}.swap-wrap{order:2}.converter-grid label:last-child{order:3}.help-grid{grid-template-columns:1fr}}
</style>
<script>
(()=>{const sets={length:{'Meter':1,'Kilometer':1000,'Centimeter':.01,'Millimeter':.001,'Mile':1609.344,'Yard':.9144,'Foot':.3048,'Inch':.0254},weight:{'Kilogram':1,'Gram':.001,'Milligram':.000001,'Pound':.45359237,'Ounce':.028349523125,'Stone':6.35029318},temperature:{'Celsius':'C','Fahrenheit':'F','Kelvin':'K'},area:{'Square meter':1,'Square kilometer':1000000,'Square centimeter':.0001,'Square foot':.09290304,'Square yard':.83612736,'Acre':4046.8564224,'Hectare':10000},volume:{'Liter':1,'Milliliter':.001,'Cubic meter':1000,'Cubic centimeter':.001,'Gallon (US)':3.785411784,'Quart (US)':.946352946,'Pint (US)':.473176473},speed:{'Meters/second':1,'Kilometers/hour':.27777777777778,'Miles/hour':.44704,'Feet/second':.3048,'Knot':.51444444444444}};
const from=document.getElementById('fromUnit'),to=document.getElementById('toUnit'),value=document.getElementById('fromValue'),out=document.getElementById('toValue'),result=document.getElementById('resultText');let category='length';
function populate(){const names=Object.keys(sets[category]);from.innerHTML='';to.innerHTML='';names.forEach((n,i)=>{from.add(new Option(n,n));to.add(new Option(n,n))});to.selectedIndex=1;convert()}
function convert(){const v=Number(value.value);if(!Number.isFinite(v)){out.value='';result.textContent='—';return}let r;if(category==='temperature'){let c=from.value==='Celsius'?v:from.value==='Fahrenheit'?(v-32)*5/9:v-273.15;r=to.value==='Celsius'?c:to.value==='Fahrenheit'?c*9/5+32:c+273.15}else{r=v*sets[category][from.value]/sets[category][to.value]}out.value=String(Number(r.toFixed(10)));result.textContent=out.value+' '+to.value}
document.querySelectorAll('[data-category]').forEach(b=>b.onclick=()=>{category=b.dataset.category;document.querySelectorAll('[data-category]').forEach(x=>x.classList.remove('active'));b.classList.add('active');populate()});[value,from,to].forEach(x=>x.addEventListener('input',convert));document.getElementById('swap').onclick=()=>{const i=from.selectedIndex;from.selectedIndex=to.selectedIndex;to.selectedIndex=i;convert()};populate()})();
</script>
<?php smarttoolz_tool_page_end(); ?>
