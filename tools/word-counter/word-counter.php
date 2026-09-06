<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/header.php';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Free Word Counter Online — Word & Character Counter | SmartToolz</title>
<meta name="description" content="Use SmartToolz free word counter to count words, characters, sentences and paragraphs instantly. Check reading time and copy or clear your text directly in your browser.">
<meta name="robots" content="index,follow,max-image-preview:large">
<link rel="canonical" href="https://smarttoolz.in/tools/word-counter/">
<meta property="og:type" content="website">
<meta property="og:site_name" content="SmartToolz">
<meta property="og:title" content="Free Word Counter Online — SmartToolz">
<meta property="og:description" content="Count words, characters, sentences and paragraphs instantly with a free browser-based word counter.">
<meta property="og:url" content="https://smarttoolz.in/tools/word-counter/">
<meta name="twitter:card" content="summary">
<meta name="twitter:title" content="Free Word Counter Online — SmartToolz">
<meta name="twitter:description" content="Free online word and character counter with live statistics and reading time.">
<script type="application/ld+json">
{
  "@context":"https://schema.org",
  "@type":"WebApplication",
  "name":"SmartToolz Word Counter",
  "url":"https://smarttoolz.in/tools/word-counter/",
  "description":"A free browser-based tool for counting words, characters, sentences and paragraphs.",
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
    {"@type":"Question","name":"Is the SmartToolz Word Counter free?","acceptedAnswer":{"@type":"Answer","text":"Yes. The SmartToolz Word Counter is free to use without creating an account."}},
    {"@type":"Question","name":"What does the word counter count?","acceptedAnswer":{"@type":"Answer","text":"It counts words, total characters, characters without spaces, sentences, paragraphs and estimated reading time."}},
    {"@type":"Question","name":"Are my texts uploaded?","acceptedAnswer":{"@type":"Answer","text":"The counting is performed in your browser. The Word Counter does not need to upload the text for counting."}},
    {"@type":"Question","name":"How is reading time estimated?","acceptedAnswer":{"@type":"Answer","text":"Reading time is estimated from the word count using approximately 200 words per minute."}},
    {"@type":"Question","name":"Can I copy my text?","acceptedAnswer":{"@type":"Answer","text":"Yes. Use the Copy Text button to copy the current text to your clipboard."}}
  ]
}
</script>
<style>
:root{--brand:#635bff;--brand-dark:#5148ee;--ink:#172033;--muted:#667085;--line:#e4e8f0;--soft:#f7f8fc}
*{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;background:#f6f8fc;color:var(--ink);font-family:Inter,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}.word-counter-page{width:min(1080px,calc(100% - 24px));margin:0 auto 54px}.hero{text-align:center;padding:38px 12px 25px}.eyebrow{display:inline-flex;padding:7px 12px;border:1px solid #dedbff;border-radius:999px;background:#efedff;color:var(--brand);font-size:10px;font-weight:900;letter-spacing:1px}.hero h1{margin:14px 0 10px;font-size:clamp(32px,6vw,50px);line-height:1.08;letter-spacing:-2px}.hero p{max-width:720px;margin:0 auto;color:var(--muted);font-size:14px;line-height:1.75}.tool-card{background:#fff;border:1px solid var(--line);border-radius:22px;padding:22px;box-shadow:0 16px 45px rgba(25,35,70,.06)}.textarea-wrap{position:relative}.text-input{width:100%;min-height:360px;resize:vertical;padding:20px;border:1px solid #dfe3eb;border-radius:16px;outline:0;background:#fafbff;color:var(--ink);font-size:15px;line-height:1.7;transition:.2s}.text-input:focus{border-color:var(--brand);background:#fff;box-shadow:0 0 0 3px rgba(99,91,255,.08)}.text-input::placeholder{color:#9aa3b2}.stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-top:18px}.stat{padding:17px 10px;background:var(--soft);border:1px solid #e9ebf1;border-radius:14px;text-align:center}.stat strong{display:block;color:var(--brand);font-size:23px;line-height:1.2}.stat span{display:block;margin-top:4px;color:var(--muted);font-size:11px;font-weight:600}.extra-stats{display:grid;grid-template-columns:repeat(2,1fr);gap:12px;margin-top:12px}.extra-stat{padding:14px;border:1px solid #e9ebf1;border-radius:13px;background:#fafbff;text-align:center}.extra-stat strong{display:block;font-size:16px}.extra-stat span{color:var(--muted);font-size:11px}.actions{display:flex;justify-content:center;align-items:center;gap:10px;flex-wrap:wrap;margin-top:18px}.btn{border:0;border-radius:11px;padding:12px 18px;min-height:45px;font-weight:800;font-size:13px;cursor:pointer}.primary{background:var(--brand);color:#fff}.primary:hover{background:var(--brand-dark);transform:translateY(-1px)}.secondary{background:#edf0f5;color:#344054}.secondary:hover{background:#e3e7ee}.content{margin-top:18px;background:#fff;border:1px solid var(--line);border-radius:18px;padding:24px}.content h2{margin:0 0 10px;font-size:20px}.content h3{margin:22px 0 7px;font-size:16px}.content p,.content li{color:var(--muted);font-size:13px;line-height:1.75}.content p{margin:0 0 10px}.content ol,.content ul{padding-left:20px}.faq{margin-top:18px}.faq details{border-top:1px solid var(--line);padding:13px 0}.faq details:last-child{border-bottom:1px solid var(--line)}.faq summary{cursor:pointer;font-size:13px;font-weight:800}.faq details p{margin:9px 0 0}.related{margin-top:22px}.related-head{display:flex;align-items:end;justify-content:space-between;gap:15px;margin-bottom:12px}.related-head h2{margin:0;font-size:20px}.related-head a{color:var(--brand);font-size:12px;font-weight:850}.related-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:12px}.related-card{display:flex;flex-direction:column;min-height:135px;padding:16px;background:#fff;border:1px solid var(--line);border-radius:15px;transition:.2s}.related-card:hover{transform:translateY(-3px);border-color:#d8d4ff;box-shadow:0 15px 35px rgba(16,24,40,.08)}.related-icon{width:38px;height:38px;display:grid;place-items:center;border-radius:11px;background:#efedff;color:var(--brand);font-size:18px;margin-bottom:10px}.related-card h3{margin:0 0 4px;font-size:13px}.related-card p{margin:0;color:var(--muted);font-size:10.5px;line-height:1.5}.related-link{margin-top:auto;padding-top:10px;color:var(--brand);font-size:10.5px;font-weight:850}@media(max-width:800px){.word-counter-page{width:calc(100% - 16px)}.tool-card{padding:14px}.stats-grid{grid-template-columns:1fr 1fr}.extra-stats{grid-template-columns:1fr}.related-grid{grid-template-columns:1fr 1fr}.text-input{min-height:300px}}@media(max-width:520px){.related-grid{grid-template-columns:1fr}.related-head{align-items:flex-start;flex-direction:column;gap:4px}.hero h1{letter-spacing:-1.2px}}
</style>
</head>
<body>
<main class="word-counter-page">
<section class="hero"><span class="eyebrow">SMARTTOOLZ • FREE TEXT TOOL</span><h1>Free Word Counter Online</h1><p>Count words, characters, sentences and paragraphs instantly. Paste or type your text, check reading time, copy your text, or clear it in one click.</p></section>
<section class="tool-card" aria-label="Free word counter tool">
<div class="textarea-wrap"><label for="textInput" style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);">Enter text to count words and characters</label><textarea class="text-input" id="textInput" placeholder="Start typing or paste your text here..." spellcheck="true"></textarea></div>
<div class="stats-grid" aria-live="polite"><div class="stat"><strong id="wordCount">0</strong><span>Words</span></div><div class="stat"><strong id="characterCount">0</strong><span>Characters</span></div><div class="stat"><strong id="characterNoSpace">0</strong><span>Without Spaces</span></div><div class="stat"><strong id="sentenceCount">0</strong><span>Sentences</span></div></div>
<div class="extra-stats"><div class="extra-stat"><strong id="paragraphCount">0</strong><span>Paragraphs</span></div><div class="extra-stat"><strong id="readingTime">0 min</strong><span>Estimated Reading Time</span></div></div>
<div class="actions"><button class="btn secondary" id="copyBtn" type="button">📋 Copy Text</button><button class="btn secondary" id="clearBtn" type="button">Clear</button></div>
</section>
<section class="content"><h2>Free Online Word and Character Counter</h2><p>SmartToolz Word Counter is a browser-based text analysis tool for writers, students, bloggers, marketers and developers. It gives you live word and character counts while you type or paste content.</p><h3>How to use the Word Counter</h3><ol><li>Type or paste your text into the text box.</li><li>Read the live word, character, sentence and paragraph totals.</li><li>Use the estimated reading time to judge how long the text may take to read.</li><li>Copy the text when you are finished, or clear the box to start again.</li></ol><h3>Why check word count?</h3><p>Word counts are useful for essays, blog posts, social captions, content briefs, application responses and other writing with length requirements. Character counts are also helpful when a form or platform sets a character limit.</p><h3>Privacy-friendly text counting</h3><p>The counting logic runs in your browser, so the tool does not need to send your text to a server just to calculate the statistics.</p></section>
<section class="content faq"><h2>Word Counter FAQ</h2><details><summary>Is the SmartToolz Word Counter free?</summary><p>Yes. It is free to use without creating an account.</p></details><details><summary>What does the tool count?</summary><p>It counts words, total characters, characters without spaces, sentences, paragraphs and estimated reading time.</p></details><details><summary>Are my texts uploaded?</summary><p>The counting is performed locally in your browser.</p></details><details><summary>How is reading time estimated?</summary><p>The estimate uses approximately 200 words per minute.</p></details><details><summary>Can I copy my text?</summary><p>Yes. Use the Copy Text button to copy the current text to your clipboard.</p></details></section>
</main>
<script>
(() => {
  'use strict';
  const textInput=document.getElementById('textInput');
  const wordCount=document.getElementById('wordCount');
  const characterCount=document.getElementById('characterCount');
  const characterNoSpace=document.getElementById('characterNoSpace');
  const sentenceCount=document.getElementById('sentenceCount');
  const paragraphCount=document.getElementById('paragraphCount');
  const readingTime=document.getElementById('readingTime');
  const copyBtn=document.getElementById('copyBtn');
  const clearBtn=document.getElementById('clearBtn');
  function updateCounts(){
    const text=textInput.value;
    const trimmed=text.trim();
    const words=trimmed?trimmed.match(/\S+/g)||[]:[];
    characterCount.textContent=text.length;
    characterNoSpace.textContent=text.replace(/\s/g,'').length;
    wordCount.textContent=words.length;
    const matches=trimmed?trimmed.match(/[^.!?]+[.!?]+(?=\s|$)|[^.!?]+$/g):null;
    sentenceCount.textContent=matches?matches.length:0;
    const paragraphs=trimmed?trimmed.split(/\n\s*\n/).filter(item=>item.trim()!=='').length:0;
    paragraphCount.textContent=paragraphs;
    readingTime.textContent=words.length===0?'0 min':(words.length/200<1?'< 1 min':Math.ceil(words.length/200)+' min');
  }
  textInput.addEventListener('input',updateCounts);
  copyBtn.addEventListener('click',async()=>{
    if(textInput.value===''){copyBtn.textContent='Nothing to Copy';setTimeout(()=>copyBtn.textContent='📋 Copy Text',1500);return;}
    try{await navigator.clipboard.writeText(textInput.value);copyBtn.textContent='✓ Copied!';}
    catch(e){textInput.select();document.execCommand('copy');copyBtn.textContent='✓ Copied!';}
    setTimeout(()=>copyBtn.textContent='📋 Copy Text',1500);
  });
  clearBtn.addEventListener('click',()=>{textInput.value='';updateCounts();textInput.focus();});
  updateCounts();
})();
</script>
<?php require_once dirname(__DIR__) . '/footer.php'; ?>
</body>
</html>
