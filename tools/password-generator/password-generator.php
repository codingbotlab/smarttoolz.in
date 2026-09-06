<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/lib/tool-page.php';

$tool = [
    'title' => 'Free Password Generator Online',
    'description' => 'Generate strong, secure random passwords online for free. Customize length, symbols, numbers and letters with SmartToolz.',
    'url' => 'https://smarttoolz.in/tools/password-generator/'
];
smarttoolz_tool_page_start($tool);
?>
<main class="password-page">
  <section class="password-head">
    <span class="eyebrow">PASSWORD GENERATOR</span>
    <h1>Generate a strong password</h1>
    <p>Create secure random passwords instantly. Customize the length and character types to match your needs.</p>
  </section>

  <section class="password-card" aria-label="Password generator">
    <div class="password-output">
      <input id="password" type="text" value="" readonly aria-label="Generated password">
      <button id="toggle" type="button" aria-label="Show password" title="Show password">◉</button>
      <button id="copy" type="button">Copy</button>
    </div>
    <div class="strength-row"><span>Password strength</span><strong id="strength">Strong</strong></div>
    <div class="strength-bar"><i id="strengthBar"></i></div>

    <div class="controls">
      <div class="control full"><div class="control-head"><label for="length">Password length</label><output id="lengthValue">16</output></div><input id="length" type="range" min="6" max="64" value="16"></div>
      <label class="check"><input id="upper" type="checkbox" checked><span>Uppercase letters <b>A-Z</b></span></label>
      <label class="check"><input id="lower" type="checkbox" checked><span>Lowercase letters <b>a-z</b></span></label>
      <label class="check"><input id="numbers" type="checkbox" checked><span>Numbers <b>0-9</b></span></label>
      <label class="check"><input id="symbols" type="checkbox" checked><span>Symbols <b>!@#$</b></span></label>
      <label class="check"><input id="exclude" type="checkbox"><span>Exclude ambiguous <b>Il1O0</b></span></label>
    </div>

    <button class="generate" id="generate" type="button">Generate New Password</button>
    <p class="status" id="status" aria-live="polite"></p>
  </section>

  <section class="help-grid">
    <article><h2>How it works</h2><ol><li>Choose your password length.</li><li>Select the character types you want.</li><li>Click Generate New Password.</li><li>Copy the result wherever you need it.</li></ol></article>
    <article><h2>Better password security</h2><p>Use a different long, random password for every important account. A password manager can help you store and manage unique passwords without reusing them.</p></article>
  </section>
</main>

<style>
.password-page{width:min(820px,calc(100% - 32px));margin:0 auto 70px}.password-head{text-align:center;padding:48px 0 24px}.password-head h1{margin:14px 0 8px;font-size:clamp(38px,6vw,56px);line-height:1;letter-spacing:-2.5px}.password-head p{max-width:680px;margin:0 auto;color:#667085;font-size:15px}.password-card{padding:24px;background:#fff;border:1px solid #e7eaf0;border-radius:24px;box-shadow:0 18px 50px rgba(16,24,40,.07)}.password-output{display:grid;grid-template-columns:minmax(0,1fr) 42px 74px;gap:8px}.password-output input{width:100%;height:52px;padding:0 16px;border:1px solid #dfe3eb;border-radius:12px;background:#f8f9fc;color:#172033;font:600 15px ui-monospace,SFMono-Regular,Menlo,monospace;outline:0}.password-output button{border:1px solid #dfe3eb;border-radius:12px;background:#fff;color:#344054;font-size:11px;font-weight:800;cursor:pointer}.password-output button:hover{border-color:#a9a0ff;background:#f7f6ff;color:#5541ff}.password-output button:last-child{border:0;background:#111936;color:#fff}.strength-row{display:flex;justify-content:space-between;margin:12px 2px 7px;font-size:10px;color:#7b849d}.strength-row strong{color:#18794e}.strength-bar{height:5px;border-radius:99px;background:#eceef3;overflow:hidden}.strength-bar i{display:block;width:85%;height:100%;border-radius:inherit;background:#22a06b;transition:.2s}.controls{display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:20px}.control.full{grid-column:1/-1;padding:15px;border:1px solid #e7eaf0;border-radius:14px}.control-head{display:flex;justify-content:space-between;align-items:center}.control-head label{font-size:12px;font-weight:800}.control-head output{padding:4px 8px;border-radius:999px;background:#f0efff;color:#5b43ff;font-size:10px;font-weight:900}.control input[type=range]{width:100%;margin-top:10px;accent-color:#5c46ff}.check{display:flex;align-items:center;gap:10px;padding:13px;border:1px solid #e7eaf0;border-radius:14px;background:#fcfdff;cursor:pointer}.check input{width:16px;height:16px;accent-color:#5c46ff}.check span{font-size:11px;color:#344054}.check b{margin-left:4px;color:#8a93aa;font:700 9px ui-monospace,monospace}.generate{width:100%;height:52px;margin-top:18px;border:0;border-radius:13px;background:linear-gradient(90deg,#5d46ff,#3e7dff);color:#fff;font-size:13px;font-weight:900;cursor:pointer;box-shadow:0 10px 22px rgba(80,69,255,.18)}.status{min-height:15px;margin:9px 0 0;text-align:center;color:#5d46ff;font-size:10px}.help-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:18px}.help-grid article{padding:22px;border:1px solid #e7eaf0;border-radius:18px;background:#fff}.help-grid h2{margin:0 0 10px;font-size:17px}.help-grid p,.help-grid li{color:#667085;font-size:12px;line-height:1.75}.help-grid ol{margin:0;padding-left:20px}@media(max-width:650px){.password-page{width:calc(100% - 20px)}.password-card{padding:15px}.password-output{grid-template-columns:minmax(0,1fr) 42px}.password-output button:last-child{grid-column:1/-1;height:44px}.controls{grid-template-columns:1fr}.control.full{grid-column:auto}.help-grid{grid-template-columns:1fr}}
</style>

<script>
(()=>{
 const password=document.getElementById('password'),length=document.getElementById('length'),lengthValue=document.getElementById('lengthValue'),upper=document.getElementById('upper'),lower=document.getElementById('lower'),numbers=document.getElementById('numbers'),symbols=document.getElementById('symbols'),exclude=document.getElementById('exclude'),strength=document.getElementById('strength'),strengthBar=document.getElementById('strengthBar'),status=document.getElementById('status');
 const sets={upper:'ABCDEFGHIJKLMNOPQRSTUVWXYZ',lower:'abcdefghijklmnopqrstuvwxyz',numbers:'0123456789',symbols:'!@#$%^&*()-_=+[]{};:,.?'};
 const randomIndex=max=>{const a=new Uint32Array(1);crypto.getRandomValues(a);return a[0]%max};
 function generate(){let chars='',selected=[];if(upper.checked)selected.push(sets.upper);if(lower.checked)selected.push(sets.lower);if(numbers.checked)selected.push(sets.numbers);if(symbols.checked)selected.push(sets.symbols);if(!selected.length){password.value='';strength.textContent='Choose a type';strengthBar.style.width='0%';status.textContent='Select at least one character type.';return}let pool=selected.join('');if(exclude.checked)pool=[...pool].filter(c=>!'Il1O0'.includes(c)).join('');const n=Number(length.value),out=[];selected.forEach(s=>{const filtered=exclude.checked?[...s].filter(c=>!'Il1O0'.includes(c)).join(''):s;if(filtered)out.push(filtered[randomIndex(filtered.length)]);});while(out.length<n)out.push(pool[randomIndex(pool.length)]);for(let i=out.length-1;i>0;i--){const j=randomIndex(i+1);[out[i],out[j]]=[out[j],out[i]]}password.value=out.join('');updateStrength()}
 function updateStrength(){const n=Number(length.value),types=[upper,lower,numbers,symbols].filter(x=>x.checked).length;const score=n+types*5;let label='Weak',pct=25;if(score>=35){label='Strong';pct=85}else if(score>=22){label='Medium';pct=55}else{pct=Math.max(15,score*2)}if(n>=20&&types>=3){label='Very strong';pct=100}strength.textContent=label;strengthBar.style.width=pct+'%';strength.style.color=label==='Weak'?'#c13232':label==='Medium'?'#b7791f':'#18794e'}
 length.addEventListener('input',()=>{lengthValue.textContent=length.value;generate()});[upper,lower,numbers,symbols,exclude].forEach(x=>x.addEventListener('change',generate));document.getElementById('generate').addEventListener('click',()=>{generate();status.textContent='New password generated.'});document.getElementById('copy').addEventListener('click',async()=>{if(!password.value)return;try{await navigator.clipboard.writeText(password.value);status.textContent='Password copied to clipboard.'}catch{password.select();document.execCommand('copy');status.textContent='Password copied to clipboard.'}});document.getElementById('toggle').addEventListener('click',e=>{password.type=password.type==='text'?'password':'text';e.currentTarget.textContent=password.type==='password'?'◉':'◌'});generate();
})();
</script>
<?php smarttoolz_tool_page_end(); ?>
