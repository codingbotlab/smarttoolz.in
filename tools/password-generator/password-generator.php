<?php
declare(strict_types=1);

require_once dirname(__DIR__) . '/header.php';
?>
<!doctype html>
<html lang="en">
<head>
<?php require_once dirname(__DIR__) . '/head.php'; ?>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Free Password Generator Online | Strong Random Passwords | SmartToolz</title>
<meta name="description" content="Generate strong random passwords online for free with SmartToolz. Choose password length, uppercase letters, lowercase letters, numbers and symbols.">
<meta name="robots" content="index,follow,max-image-preview:large">
<link rel="canonical" href="https://smarttoolz.in/tools/password-generator/">
<meta property="og:type" content="website">
<meta property="og:site_name" content="SmartToolz">
<meta property="og:title" content="Free Password Generator Online | SmartToolz">
<meta property="og:description" content="Create strong random passwords with custom length, letters, numbers and symbols directly in your browser.">
<meta property="og:url" content="https://smarttoolz.in/tools/password-generator/">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Free Password Generator Online | SmartToolz">
<meta name="twitter:description" content="Generate strong random passwords directly in your browser with SmartToolz.">
<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"WebApplication",
  "name":"SmartToolz Password Generator",
  "url":"https://smarttoolz.in/tools/password-generator/",
  "description":"A free browser-based password generator with customizable length and character types.",
  "applicationCategory":"SecurityApplication",
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
    {"@type":"Question","name":"Is the SmartToolz password generator free?","acceptedAnswer":{"@type":"Answer","text":"Yes. The password generator is free to use without creating an account."}},
    {"@type":"Question","name":"Are generated passwords sent to a server?","acceptedAnswer":{"@type":"Answer","text":"The generator creates passwords in your browser and does not intentionally send the generated password to SmartToolz servers."}},
    {"@type":"Question","name":"Can I choose the password length?","acceptedAnswer":{"@type":"Answer","text":"Yes. You can choose a password length from 4 to 64 characters."}},
    {"@type":"Question","name":"Can I include symbols and numbers?","acceptedAnswer":{"@type":"Answer","text":"Yes. You can enable uppercase letters, lowercase letters, numbers and symbols independently."}}
  ]
}
</script>
<style>
:root{--brand:#635bff;--brand-dark:#5148e8;--ink:#172033;--muted:#667085;--line:#e5e9f0;--soft:#f6f8fc}
*{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;background:var(--soft);color:var(--ink);font-family:Inter,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;line-height:1.6}.password-page{width:min(1080px,calc(100% - 24px));margin:0 auto 54px}.hero{text-align:center;padding:38px 12px 24px}.eyebrow{display:inline-flex;padding:7px 11px;border:1px solid #dedbff;border-radius:999px;background:#efedff;color:var(--brand);font-size:10px;font-weight:900;letter-spacing:1px}.hero h1{margin:14px 0 9px;font-size:clamp(32px,6vw,50px);line-height:1.08;letter-spacing:-2px}.hero p{max-width:720px;margin:0 auto;color:var(--muted);font-size:14px}.card{background:#fff;border:1px solid var(--line);border-radius:22px;padding:24px;box-shadow:0 15px 45px rgba(30,35,80,.06)}.password-row{display:flex;gap:10px}.password-output{flex:1;min-width:0;height:56px;padding:0 16px;border:1px solid #dfe3eb;border-radius:13px;background:#fafbff;color:var(--ink);font:700 16px/1 Consolas,monospace;outline:none}.copy-btn,.generate-btn{border:0;border-radius:12px;cursor:pointer;font-weight:800}.copy-btn{min-width:110px;background:var(--brand);color:#fff}.copy-btn:hover,.generate-btn:hover{background:var(--brand-dark)}.strength{margin-top:18px}.strength-top{display:flex;justify-content:space-between;gap:12px;margin-bottom:8px;font-size:12px;font-weight:800}.strength-label{color:var(--muted)}.strength-text{color:var(--brand)}.strength-bar{height:8px;background:#eceef4;border-radius:999px;overflow:hidden}.strength-fill{width:0;height:100%;background:var(--brand);border-radius:999px;transition:width .25s}.settings{margin-top:20px;padding:19px;background:#fafbff;border:1px solid var(--line);border-radius:17px}.settings h2{margin:0 0 16px;font-size:16px}.length-row{display:grid;grid-template-columns:auto 1fr 58px;align-items:center;gap:14px}.length-row label{font-size:13px;font-weight:800;color:#4d5768}.length-row input{width:100%;accent-color:var(--brand)}.length-value{padding:7px 8px;text-align:center;border-radius:9px;background:#efedff;color:var(--brand);font-size:12px;font-weight:900}.options{display:grid;grid-template-columns:repeat(2,1fr);gap:10px;margin-top:16px}.option{display:flex;align-items:center;gap:9px;padding:12px;border:1px solid var(--line);border-radius:11px;background:#fff;cursor:pointer}.option input{width:17px;height:17px;accent-color:var(--brand)}.option span{font-size:12px;font-weight:750;color:#4d5768}.generate-wrap{display:flex;justify-content:center;margin-top:18px}.generate-btn{min-height:48px;padding:12px 24px;background:var(--brand);color:#fff;box-shadow:0 10px 25px rgba(99,91,255,.16)}.privacy-note{margin-top:14px;padding:13px 15px;border-left:3px solid var(--brand);border-radius:10px;background:#f8f8ff;color:var(--muted);font-size:12px}.content{margin-top:18px;padding:24px;background:#fff;border:1px solid var(--line);border-radius:18px}.content h2{margin:0 0 9px;font-size:21px}.content h3{margin:20px 0 7px;font-size:16px}.content p,.content li{color:var(--muted);font-size:13px;line-height:1.75}.content ol{margin:8px 0 0;padding-left:21px}.faq details{border-top:1px solid var(--line);padding:13px 0}.faq details:last-child{border-bottom:1px solid var(--line)}.faq summary{cursor:pointer;font-size:13px;font-weight:800}.faq details p{margin:8px 0 0}.benefits{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-top:18px}.benefit{padding:18px;background:#fff;border:1px solid var(--line);border-radius:16px}.benefit strong{display:block;margin-bottom:5px;font-size:14px}.benefit span{color:var(--muted);font-size:12px;line-height:1.6}@media(max-width:700px){.password-page{width:calc(100% - 16px)}.hero{padding:30px 8px 18px}.card,.content{padding:17px}.password-row{flex-direction:column}.copy-btn{height:45px}.length-row{grid-template-columns:1fr 58px}.length-row input{grid-column:1/-1;grid-row:2}.options,.benefits{grid-template-columns:1fr}.generate-btn{width:100%}}
</style>
</head>
<body>
<?php require_once dirname(__DIR__) . '/header.php'; ?>

<main class="password-page">
<section class="hero"><span class="eyebrow">SMARTTOOLZ • SECURITY TOOL</span><h1>Free Password Generator</h1><p>Create strong random passwords with a custom length and the character types you choose. Password generation happens directly in your browser.</p></section>
<section class="card" aria-label="Password generator">
<div class="password-row"><input class="password-output" id="password" type="text" readonly aria-label="Generated password"><button class="copy-btn" id="copyBtn" type="button">Copy</button></div>
<div class="strength"><div class="strength-top"><span class="strength-label">Password strength</span><span class="strength-text" id="strengthText">—</span></div><div class="strength-bar" aria-hidden="true"><div class="strength-fill" id="strengthFill"></div></div></div>
<div class="settings"><h2>Password Settings</h2><div class="length-row"><label for="length">Password length</label><input id="length" type="range" min="4" max="64" value="16"><span class="length-value" id="lengthValue">16</span></div><div class="options"><label class="option"><input id="uppercase" type="checkbox" checked><span>Uppercase (A-Z)</span></label><label class="option"><input id="lowercase" type="checkbox" checked><span>Lowercase (a-z)</span></label><label class="option"><input id="numbers" type="checkbox" checked><span>Numbers (0-9)</span></label><label class="option"><input id="symbols" type="checkbox" checked><span>Symbols (!@#$)</span></label></div><div class="generate-wrap"><button class="generate-btn" id="generateBtn" type="button">Generate New Password</button></div></div>
<div class="privacy-note">Privacy: generated passwords are created locally in your browser. This page does not intentionally upload the generated password to SmartToolz.</div>
</section>
<section class="benefits" aria-label="Password generator benefits"><div class="benefit"><strong>Custom length</strong><span>Choose a password from 4 to 64 characters.</span></div><div class="benefit"><strong>Flexible characters</strong><span>Control uppercase, lowercase, numbers and symbols.</span></div><div class="benefit"><strong>Browser based</strong><span>Generate passwords locally without an account.</span></div></section>
<section class="content"><h2>How to Use the Password Generator</h2><ol><li>Choose the password length you need.</li><li>Select the character types to include.</li><li>Click <strong>Generate New Password</strong>.</li><li>Use <strong>Copy</strong> to copy the generated password.</li></ol><h3>What makes a password stronger?</h3><p>Longer passwords generally provide more possible combinations. Using a mix of uppercase letters, lowercase letters, numbers and symbols can also increase the number of possible combinations. Avoid reusing important passwords across different services.</p><h3>Is the password stored?</h3><p>This generator creates the password in the browser and does not intentionally send the generated value to the SmartToolz server.</p></section>
<section class="content faq"><h2>Password Generator FAQ</h2><details><summary>Is this password generator free?</summary><p>Yes. It is free to use without an account.</p></details><details><summary>Can I change the password length?</summary><p>Yes. Use the length slider to choose from 4 to 64 characters.</p></details><details><summary>Can I include symbols and numbers?</summary><p>Yes. Enable or disable each character group in Password Settings.</p></details><details><summary>Is my generated password uploaded?</summary><p>The tool generates the password locally in your browser and does not intentionally upload it to SmartToolz.</p></details></section>
</main>


<script>
(() => {
'use strict';
const password=document.getElementById('password');
const copyBtn=document.getElementById('copyBtn');
const lengthInput=document.getElementById('length');
const lengthValue=document.getElementById('lengthValue');
const uppercase=document.getElementById('uppercase');
const lowercase=document.getElementById('lowercase');
const numbers=document.getElementById('numbers');
const symbols=document.getElementById('symbols');
const generateBtn=document.getElementById('generateBtn');
const strengthText=document.getElementById('strengthText');
const strengthFill=document.getElementById('strengthFill');
const sets={uppercase:'ABCDEFGHIJKLMNOPQRSTUVWXYZ',lowercase:'abcdefghijklmnopqrstuvwxyz',numbers:'0123456789',symbols:'!@#$%^&*()_+-=[]{}|;:,.<>?'};
function randomInt(max){if(max<=0)return 0;if(window.crypto&&window.crypto.getRandomValues){const range=0x100000000;const limit=range-(range%max);const a=new Uint32Array(1);let n;do{window.crypto.getRandomValues(a);n=a[0]}while(n>=limit);return n%max}return Math.floor(Math.random()*max)}
function pick(chars){return chars[randomInt(chars.length)]}
function shuffle(arr){for(let i=arr.length-1;i>0;i--){const j=randomInt(i+1);[arr[i],arr[j]]=[arr[j],arr[i]]}return arr}
function generatePassword(){const len=Number(lengthInput.value);const selected=[];if(uppercase.checked)selected.push(sets.uppercase);if(lowercase.checked)selected.push(sets.lowercase);if(numbers.checked)selected.push(sets.numbers);if(symbols.checked)selected.push(sets.symbols);if(!selected.length){lowercase.checked=true;selected.push(sets.lowercase)}const pool=selected.join('');const result=[];for(let i=0;i<Math.min(len,selected.length);i++)result.push(pick(selected[i]));while(result.length<len)result.push(pick(pool));password.value=shuffle(result).join('');updateStrength()}
function updateStrength(){const value=password.value;if(!value){strengthText.textContent='—';strengthFill.style.width='0';return}let score=0;if(value.length>=8)score++;if(value.length>=12)score++;if(value.length>=20)score++;if(/[A-Z]/.test(value))score++;if(/[a-z]/.test(value))score++;if(/\d/.test(value))score++;if(/[^A-Za-z0-9]/.test(value))score++;if(score>=7){strengthText.textContent='Very Strong';strengthFill.style.width='100%'}else if(score>=5){strengthText.textContent='Strong';strengthFill.style.width='80%'}else if(score>=3){strengthText.textContent='Medium';strengthFill.style.width='55%'}else{strengthText.textContent='Weak';strengthFill.style.width='25%'}}
lengthInput.addEventListener('input',()=>{lengthValue.textContent=lengthInput.value;generatePassword()});[uppercase,lowercase,numbers,symbols].forEach(el=>el.addEventListener('change',generatePassword));generateBtn.addEventListener('click',generatePassword);copyBtn.addEventListener('click',async()=>{if(!password.value)return;try{await navigator.clipboard.writeText(password.value)}catch(e){password.select();document.execCommand('copy')}copyBtn.textContent='Copied!';setTimeout(()=>copyBtn.textContent='Copy',1400)});generatePassword();
})();
</script>

<?php require_once dirname(__DIR__) . '/footer.php'; ?>
</body>
</html>
