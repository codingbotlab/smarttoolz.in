<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/lib/tool-page.php';

$tool = [
    'title' => 'Free Color Picker Online — HEX, RGB & HSL',
    'description' => 'Pick a color online and get HEX, RGB, HSL and HSB values instantly with SmartToolz.',
    'url' => 'https://smarttoolz.in/tools/color-picker/'
];
smarttoolz_tool_page_start($tool);
?>
<main class="color-page">
  <section class="color-head">
    <span class="eyebrow">COLOR PICKER</span>
    <h1>Pick a color & get its codes</h1>
    <p>Choose any color and instantly copy its HEX, RGB, HSL and HSB values.</p>
  </section>

  <section class="color-card" aria-label="Color picker">
    <div class="picker-grid">
      <div class="visual-panel">
        <div class="color-preview" id="preview"></div>
        <input id="color" type="color" value="#5C46FF" aria-label="Choose color">
        <label class="picker-label" for="color">Choose a color</label>
      </div>
      <div class="values-panel">
        <div class="value-row"><span>HEX</span><div><input id="hex" value="#5C46FF" maxlength="7" spellcheck="false"><button data-copy="hex">Copy</button></div></div>
        <div class="value-row"><span>RGB</span><div><input id="rgb" readonly><button data-copy="rgb">Copy</button></div></div>
        <div class="value-row"><span>HSL</span><div><input id="hsl" readonly><button data-copy="hsl">Copy</button></div></div>
        <div class="value-row"><span>HSB</span><div><input id="hsb" readonly><button data-copy="hsb">Copy</button></div></div>
        <button class="copy-all" id="copyAll" type="button">Copy All Color Values</button>
        <p class="status" id="status" aria-live="polite"></p>
      </div>
    </div>
  </section>

  <section class="help-grid">
    <article><h2>How to use</h2><ol><li>Click the color picker and choose a color.</li><li>Fine-tune the HEX value if you already know the color.</li><li>Copy the color format you need.</li></ol></article>
    <article><h2>Color formats</h2><p><strong>HEX</strong> is commonly used in CSS and design tools. <strong>RGB</strong> represents red, green and blue channels. <strong>HSL</strong> uses hue, saturation and lightness, while <strong>HSB</strong> uses brightness instead of lightness.</p></article>
  </section>
</main>

<style>
.color-page{width:min(920px,calc(100% - 32px));margin:0 auto 70px}.color-head{text-align:center;padding:48px 0 24px}.color-head h1{margin:14px 0 8px;font-size:clamp(38px,6vw,56px);line-height:1;letter-spacing:-2.5px}.color-head p{max-width:680px;margin:0 auto;color:#667085;font-size:15px}.color-card{padding:22px;background:#fff;border:1px solid #e7eaf0;border-radius:24px;box-shadow:0 18px 50px rgba(16,24,40,.07)}.picker-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px}.visual-panel{min-height:430px;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:25px;border:1px solid #e7eaf0;border-radius:18px;background:#fafbff}.color-preview{width:min(280px,70%);aspect-ratio:1;border-radius:28px;background:#5C46FF;box-shadow:0 20px 45px rgba(16,24,40,.16);border:8px solid #fff;outline:1px solid #e1e5ed}.visual-panel input[type=color]{position:absolute;width:1px;height:1px;opacity:0}.picker-label{margin-top:24px;padding:11px 18px;border-radius:11px;background:#111936;color:#fff;font-size:12px;font-weight:800;cursor:pointer}.values-panel{display:flex;flex-direction:column;justify-content:center;gap:11px}.value-row>span{display:block;margin:0 0 5px 2px;color:#667085;font-size:10px;font-weight:900;letter-spacing:.5px}.value-row>div{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:7px}.value-row input{width:100%;height:42px;padding:0 12px;border:1px solid #dfe3eb;border-radius:10px;outline:0;background:#fcfdff;color:#172033;font:12px ui-monospace,SFMono-Regular,Menlo,monospace}.value-row input:focus{border-color:#8d80ff}.value-row button{padding:0 13px;border:1px solid #dfe3eb;border-radius:10px;background:#fff;color:#5541ff;font-size:10px;font-weight:800;cursor:pointer}.value-row button:hover{background:#f7f6ff}.copy-all{height:45px;margin-top:5px;border:0;border-radius:11px;background:linear-gradient(90deg,#5d46ff,#3e7dff);color:#fff;font-size:12px;font-weight:900;cursor:pointer}.status{min-height:14px;margin:0;text-align:center;color:#5d46ff;font-size:10px}.help-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:18px}.help-grid article{padding:22px;border:1px solid #e7eaf0;border-radius:18px;background:#fff}.help-grid h2{margin:0 0 10px;font-size:17px}.help-grid p,.help-grid li{color:#667085;font-size:12px;line-height:1.75}.help-grid ol{margin:0;padding-left:20px}@media(max-width:650px){.color-page{width:calc(100% - 20px)}.color-card{padding:15px}.picker-grid{grid-template-columns:1fr}.visual-panel{min-height:320px}.color-preview{width:190px;height:190px}.help-grid{grid-template-columns:1fr}}
</style>

<script>
(()=>{
 const color=document.getElementById('color'),hex=document.getElementById('hex'),rgb=document.getElementById('rgb'),hsl=document.getElementById('hsl'),hsb=document.getElementById('hsb'),preview=document.getElementById('preview'),status=document.getElementById('status');
 const clamp=(n,min,max)=>Math.min(max,Math.max(min,n));
 const hexToRgb=h=>{h=h.replace('#','');if(h.length===3)h=h.split('').map(x=>x+x).join('');if(!/^[0-9a-f]{6}$/i.test(h))return null;return{r:parseInt(h.slice(0,2),16),g:parseInt(h.slice(2,4),16),b:parseInt(h.slice(4,6),16)}};
 const formats=({r,g,b})=>{const rf=r/255,gf=g/255,bf=b/255,max=Math.max(rf,gf,bf),min=Math.min(rf,gf,bf),d=max-min,l=(max+min)/2;let hue=0,s=0;if(d){s=d/(1-Math.abs(2*l-1));switch(max){case rf:hue=60*(((gf-bf)/d)%6);break;case gf:hue=60*((bf-rf)/d+2);break;default:hue=60*((rf-gf)/d+4)}}if(hue<0)hue+=360;const sat=d===0?0:d/max;const bright=max;return{rgb:`rgb(${r}, ${g}, ${b})`,hsl:`hsl(${Math.round(hue)}, ${Math.round(s*100)}%, ${Math.round(l*100)}%)`,hsb:`hsb(${Math.round(hue)}, ${Math.round(sat*100)}%, ${Math.round(bright*100)}%)`}};
 const update=h=>{const c=hexToRgb(h);if(!c){status.textContent='Enter a valid 6-digit HEX color.';return}const normalized='#'+h.replace('#','').toUpperCase();hex.value=normalized;color.value=normalized;preview.style.backgroundColor=normalized;const f=formats(c);rgb.value=f.rgb;hsl.value=f.hsl;hsb.value=f.hsb;status.textContent='Color updated.'};
 color.addEventListener('input',()=>update(color.value));
 hex.addEventListener('change',()=>update(hex.value));
 hex.addEventListener('input',()=>{if(hexToRgb(hex.value))update(hex.value)});
 document.querySelectorAll('[data-copy]').forEach(btn=>btn.addEventListener('click',async()=>{const value=document.getElementById(btn.dataset.copy).value;try{await navigator.clipboard.writeText(value);status.textContent=value+' copied.'}catch{status.textContent='Copy failed. Select the value manually.'}}));
 document.getElementById('copyAll').addEventListener('click',async()=>{const value=`HEX: ${hex.value}\nRGB: ${rgb.value}\nHSL: ${hsl.value}\nHSB: ${hsb.value}`;try{await navigator.clipboard.writeText(value);status.textContent='All color values copied.'}catch{status.textContent='Copy failed.'}});
 update(color.value);
})();
</script>
<?php smarttoolz_tool_page_end(); ?>
