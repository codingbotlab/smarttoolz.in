<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/header.php';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Free Case Converter Online | Uppercase, Lowercase & Title Case | SmartToolz</title>
<meta name="description" content="Convert text to uppercase, lowercase, title case, sentence case, alternating case or inverse case online for free with SmartToolz.">
<meta name="robots" content="index,follow,max-image-preview:large">
<link rel="canonical" href="https://smarttoolz.in/tools/case-converter/">
<meta property="og:type" content="website">
<meta property="og:site_name" content="SmartToolz">
<meta property="og:title" content="Free Case Converter Online | SmartToolz">
<meta property="og:description" content="Change text capitalization instantly with a free browser-based case converter. No signup required.">
<meta property="og:url" content="https://smarttoolz.in/tools/case-converter/">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Free Case Converter Online | SmartToolz">
<meta name="twitter:description" content="Convert text to uppercase, lowercase, title case, sentence case and more in your browser.">
<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"WebApplication",
  "name":"SmartToolz Case Converter",
  "url":"https://smarttoolz.in/tools/case-converter/",
  "description":"Free browser-based tool for changing text capitalization.",
  "applicationCategory":"UtilitiesApplication",
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
    {"@type":"Question","name":"What is a case converter?","acceptedAnswer":{"@type":"Answer","text":"A case converter changes the capitalization of existing text, such as converting it to uppercase, lowercase, title case or sentence case."}},
    {"@type":"Question","name":"Is the SmartToolz Case Converter free?","acceptedAnswer":{"@type":"Answer","text":"Yes. The Case Converter is free to use online without creating an account."}},
    {"@type":"Question","name":"Are my texts uploaded?","acceptedAnswer":{"@type":"Answer","text":"The case conversion is performed in your browser. The tool does not need to upload the text to SmartToolz to change its capitalization."}},
    {"@type":"Question","name":"Which text cases are supported?","acceptedAnswer":{"@type":"Answer","text":"You can convert text to uppercase, lowercase, title case, sentence case, alternating case and inverse case."}},
    {"@type":"Question","name":"Can I copy the converted text?","acceptedAnswer":{"@type":"Answer","text":"Yes. Use the Copy Text button to copy the current text to your clipboard."}}
  ]
}
</script>
<style>
:root{--brand:#635bff;--brand-dark:#5148e8;--ink:#172033;--muted:#667085;--line:#e5e9f0;--soft:#fafbff}
*{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;background:#f6f8fc;color:var(--ink);font-family:Inter,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;line-height:1.5}a{text-decoration:none;color:inherit}button,textarea{font:inherit}.case-page{width:min(1080px,calc(100% - 24px));margin:0 auto 48px}.hero{text-align:center;padding:34px 10px 24px}.eyebrow{display:inline-flex;padding:7px 11px;border:1px solid #dedbff;border-radius:999px;background:#efedff;color:var(--brand);font-size:10px;font-weight:900;letter-spacing:1px}.hero h1{margin:14px 0 10px;font-size:clamp(32px,6vw,50px);line-height:1.08;letter-spacing:-2px}.hero h1 span{color:var(--brand)}.hero p{max-width:730px;margin:0 auto;color:var(--muted);font-size:14px;line-height:1.75}.tool-card,.content-section,.faq-section{background:#fff;border:1px solid var(--line);border-radius:20px;box-shadow:0 12px 35px rgba(16,24,40,.05)}.tool-card{padding:22px}.tool-card label{display:block;margin-bottom:8px;font-size:13px;font-weight:800}.textarea-wrap{position:relative}.text-input{width:100%;min-height:360px;resize:vertical;padding:18px;border:1px solid #dfe3eb;border-radius:16px;outline:none;background:var(--soft);color:var(--ink);font-size:15px;line-height:1.7}.text-input:focus{border-color:var(--brand);background:#fff;box-shadow:0 0 0 3px rgba(99,91,255,.08)}.text-input::placeholder{color:#9aa3b2}.counter{margin-top:8px;text-align:right;color:#8a93a2;font-size:11px}.case-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:16px}.btn{min-height:45px;padding:10px 14px;border:1px solid #e2e5ec;border-radius:11px;background:#f3f5f8;color:#344054;font-size:12px;font-weight:850;cursor:pointer;transition:.2s}.btn:hover{background:#e9ecf2;transform:translateY(-1px)}.btn-primary{background:var(--brand);border-color:var(--brand);color:#fff}.btn-primary:hover{background:var(--brand-dark);border-color:var(--brand-dark)}.actions{display:flex;justify-content:center;gap:10px;flex-wrap:wrap;margin-top:16px}.action-btn{min-width:140px}.copy-status{margin-top:10px;text-align:center;color:var(--brand);font-size:11px;font-weight:800;min-height:16px}.content-section{margin-top:18px;padding:24px}.content-section h2,.faq-section h2{margin:0 0 10px;font-size:22px;letter-spacing:-.6px}.content-section h3{margin:20px 0 7px;font-size:16px}.content-section p,.content-section li,.faq-section p{color:var(--muted);font-size:13px;line-height:1.75}.content-section p{margin:0 0 10px}.content-section ol,.content-section ul{padding-left:20px;margin:8px 0}.content-section li+li{margin-top:4px}.tip{margin-top:16px;padding:14px 16px;border-left:3px solid var(--brand);border-radius:10px;background:#f8f8ff;color:var(--muted);font-size:12px;line-height:1.65}.faq-section{margin-top:18px;padding:24px}.faq-section details{border-top:1px solid var(--line);padding:14px 0}.faq-section details:last-child{border-bottom:1px solid var(--line)}.faq-section summary{cursor:pointer;font-size:13px;font-weight:850}.faq-section details p{margin:9px 0 0}.trust-note{margin-top:14px;text-align:center;color:#7b8494;font-size:11px}.trust-note a{color:var(--brand);font-weight:700}@media(max-width:700px){.case-page{width:calc(100% - 16px)}.hero{padding-top:26px}.hero h1{letter-spacing:-1.2px}.tool-card,.content-section,.faq-section{padding:16px}.text-input{min-height:300px;font-size:14px}.case-grid{grid-template-columns:repeat(2,1fr)}.actions{flex-direction:column}.action-btn{width:100%}}@media(max-width:480px){.case-grid{grid-template-columns:1fr}}
</style>
</head>
<body>
<main class="case-page">
<section class="hero"><span class="eyebrow">SMARTTOOLZ • FREE TEXT TOOL</span><h1>Free <span>Case Converter</span> Online</h1><p>Change capitalization instantly with uppercase, lowercase, title case, sentence case, alternating case and inverse case tools. Everything runs directly in your browser.</p></section>
<section class="tool-card" aria-label="Case converter tool">
<div class="textarea-wrap"><label for="textInput">Enter your text</label><textarea class="text-input" id="textInput" placeholder="Type or paste text here..." spellcheck="true"></textarea></div>
<div class="counter" id="counter">0 characters</div>
<div class="case-grid" aria-label="Text case options"><button class="btn btn-primary" type="button" id="upperBtn">UPPERCASE</button><button class="btn" type="button" id="lowerBtn">lowercase</button><button class="btn" type="button" id="titleBtn">Title Case</button><button class="btn" type="button" id="sentenceBtn">Sentence case</button><button class="btn" type="button" id="alternateBtn">aLtErNaTiNg</button><button class="btn" type="button" id="inverseBtn">iNVERSE cASE</button></div>
<div class="actions"><button class="btn action-btn btn-primary" type="button" id="copyBtn">📋 Copy Text</button><button class="btn action-btn" type="button" id="clearBtn">Clear Text</button></div><div class="copy-status" id="copyStatus" aria-live="polite"></div>
</section>
<section class="content-section"><h2>Free Online Case Converter</h2><p>SmartToolz Case Converter helps writers, students, editors, developers and everyday users quickly change the capitalization of existing text. You can transform headings, notes, sentences and pasted text without manually retyping everything.</p><h3>How to use the Case Converter</h3><ol><li>Type or paste your text into the editor.</li><li>Choose the capitalization style you need.</li><li>Review the converted text and use Copy Text when you are ready.</li></ol><h3>When to use each case</h3><ul><li><strong>Uppercase:</strong> useful for labels, short headings and emphasis.</li><li><strong>Lowercase:</strong> useful when you need consistent lowercase text.</li><li><strong>Title Case:</strong> useful for headings and titles.</li><li><strong>Sentence Case:</strong> useful for normal readable sentences.</li><li><strong>Alternating Case:</strong> useful for playful or stylized text.</li><li><strong>Inverse Case:</strong> switches existing uppercase letters to lowercase and lowercase letters to uppercase.</li></ul><div class="tip">Your text is transformed locally in the browser by this page. No account is required for the case conversion itself.</div></section>
<section class="faq-section"><h2>Case Converter FAQs</h2><details><summary>Is this case converter free?</summary><p>Yes. SmartToolz provides this case converter online for free with no signup required.</p></details><details><summary>Does the tool upload my text?</summary><p>The capitalization changes are performed in your browser, so the converter does not need to send your text to a server.</p></details><details><summary>What case formats are available?</summary><p>You can use uppercase, lowercase, title case, sentence case, alternating case and inverse case.</p></details><details><summary>Can I copy the converted text?</summary><p>Yes. Click Copy Text after converting your content to place the current text on your clipboard.</p></details><details><summary>Can I use it on mobile?</summary><p>Yes. The page is responsive and the case conversion tools work in modern mobile browsers.</p></details></section>
<p class="trust-note">Need more text tools? Explore <a href="/tool.php">all SmartToolz tools</a>.</p>
</main>
<?php require_once dirname(__DIR__) . '/footer.php'; ?>
<script>
(() => {
  'use strict';
  const input=document.getElementById('textInput');
  const counter=document.getElementById('counter');
  const copyBtn=document.getElementById('copyBtn');
  const clearBtn=document.getElementById('clearBtn');
  const copyStatus=document.getElementById('copyStatus');
  const focus=()=>input.focus();
  const update=()=>{const n=input.value.length;counter.textContent=n+(n===1?' character':' characters');};
  const set=(value)=>{input.value=value;update();focus();};
  document.getElementById('upperBtn').addEventListener('click',()=>set(input.value.toUpperCase()));
  document.getElementById('lowerBtn').addEventListener('click',()=>set(input.value.toLowerCase()));
  document.getElementById('titleBtn').addEventListener('click',()=>set(input.value.toLowerCase().replace(/(^|\s)([a-z])/g,(m,s,l)=>s+l.toUpperCase())));
  document.getElementById('sentenceBtn').addEventListener('click',()=>{const text=input.value.toLowerCase();let out='',cap=true;for(const ch of text){if(cap&&/[a-z]/i.test(ch)){out+=ch.toUpperCase();cap=false}else{out+=ch}if(/[.!?]/.test(ch))cap=true}set(out);});
  document.getElementById('alternateBtn').addEventListener('click',()=>{let i=0,out='';for(const ch of input.value){if(/[a-z]/i.test(ch)){out+=i%2===0?ch.toLowerCase():ch.toUpperCase();i++}else out+=ch}set(out);});
  document.getElementById('inverseBtn').addEventListener('click',()=>{let out='';for(const ch of input.value){if(/[a-z]/.test(ch))out+=ch.toUpperCase();else if(/[A-Z]/.test(ch))out+=ch.toLowerCase();else out+=ch}set(out);});
  input.addEventListener('input',update);
  clearBtn.addEventListener('click',()=>{input.value='';update();copyStatus.textContent='';focus();});
  copyBtn.addEventListener('click',async()=>{if(input.value.trim()===''){copyStatus.textContent='Nothing to copy.';return}try{await navigator.clipboard.writeText(input.value);copyStatus.textContent='✓ Copied to clipboard.'}catch(e){const temp=document.createElement('textarea');temp.value=input.value;document.body.appendChild(temp);temp.select();try{document.execCommand('copy');copyStatus.textContent='✓ Copied to clipboard.'}catch(err){copyStatus.textContent='Copy failed. Please select the text manually.'}temp.remove()}setTimeout(()=>copyStatus.textContent='',1800);});
  update();
})();
</script>
</body>
</html>
