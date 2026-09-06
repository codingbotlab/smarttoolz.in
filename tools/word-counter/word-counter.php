<?php
declare(strict_types=1);
require_once dirname(__DIR__, 2) . '/lib/tool-page.php';

$tool = [
    'title' => 'Free Word Counter Online — Count Words, Characters & Sentences',
    'description' => 'Count words, characters, sentences, paragraphs and reading time online for free with SmartToolz.',
    'url' => 'https://smarttoolz.in/tools/word-counter/'
];
smarttoolz_tool_page_start($tool);
?>
<main class="word-counter-page">
  <section class="tool-head">
    <span class="eyebrow">WORD COUNTER</span>
    <h1>Count words and characters</h1>
    <p>Paste or type your text below to instantly count words, characters, sentences, paragraphs and reading time.</p>
  </section>

  <section class="counter-card" aria-label="Word counter">
    <div class="stats" aria-live="polite">
      <div class="stat"><strong id="words">0</strong><span>Words</span></div>
      <div class="stat"><strong id="characters">0</strong><span>Characters</span></div>
      <div class="stat"><strong id="charactersNoSpaces">0</strong><span>Characters without spaces</span></div>
      <div class="stat"><strong id="sentences">0</strong><span>Sentences</span></div>
      <div class="stat"><strong id="paragraphs">0</strong><span>Paragraphs</span></div>
      <div class="stat"><strong id="readingTime">0 min</strong><span>Reading time</span></div>
    </div>

    <div class="editor-wrap">
      <div class="editor-top"><span>Enter your text</span><button id="clear" type="button">Clear</button></div>
      <textarea id="text" placeholder="Start typing or paste your text here..." aria-label="Text to count"></textarea>
      <div class="editor-bottom"><span id="status">0 words</span><span>Updates automatically</span></div>
    </div>
  </section>

  <section class="simple-help-grid">
    <article><h2>How it works</h2><ol><li>Type or paste text into the editor.</li><li>See your word and character counts instantly.</li><li>Use the sentence, paragraph and reading-time stats for a quick overview.</li></ol></article>
    <article><h2>Useful for</h2><p>Check essays, articles, assignments, social posts, emails, scripts and other writing without uploading your text to a server.</p></article>
  </section>
</main>

<style>
.word-counter-page{width:min(920px,calc(100% - 32px));margin:0 auto 70px}.tool-head{text-align:center;padding:48px 0 24px}.tool-head h1{margin:14px 0 8px;font-size:clamp(38px,6vw,56px);line-height:1;letter-spacing:-2.5px}.tool-head p{max-width:680px;margin:0 auto;color:#667085;font-size:15px}.counter-card{padding:26px;background:#fff;border:1px solid #e7eaf0;border-radius:24px;box-shadow:0 18px 50px rgba(16,24,40,.07)}.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:18px}.stat{padding:16px 14px;border:1px solid #e7eaf0;border-radius:15px;background:#fafbff;text-align:center}.stat strong{display:block;font-size:23px;letter-spacing:-.7px}.stat span{display:block;margin-top:4px;color:#7b849d;font-size:10px;line-height:1.3}.editor-wrap{overflow:hidden;border:1px solid #dfe3eb;border-radius:16px;background:#fff}.editor-top,.editor-bottom{display:flex;justify-content:space-between;align-items:center;padding:11px 14px;color:#69738e;font-size:11px}.editor-top{border-bottom:1px solid #e7eaf0;font-weight:800}.editor-top button{border:0;background:transparent;color:#5541ff;font-size:11px;font-weight:800;cursor:pointer}.editor-wrap textarea{display:block;width:100%;min-height:330px;padding:18px;border:0;outline:0;resize:vertical;font:15px/1.7 Arial,sans-serif;color:#172033}.editor-wrap textarea::placeholder{color:#a1a9b8}.editor-bottom{border-top:1px solid #e7eaf0;font-size:10px}.simple-help-grid{display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-top:18px}.simple-help-grid article{padding:22px;border:1px solid #e7eaf0;border-radius:18px;background:#fff}.simple-help-grid h2{margin:0 0 10px;font-size:17px}.simple-help-grid p,.simple-help-grid li{color:#667085;font-size:12px;line-height:1.75}.simple-help-grid ol{margin:0;padding-left:20px}@media(max-width:650px){.word-counter-page{width:calc(100% - 20px)}.counter-card{padding:16px}.stats{grid-template-columns:repeat(2,1fr)}.editor-wrap textarea{min-height:280px}.simple-help-grid{grid-template-columns:1fr}}
</style>

<script>
(()=>{
 const text=document.getElementById('text'),clear=document.getElementById('clear'),words=document.getElementById('words'),characters=document.getElementById('characters'),charactersNoSpaces=document.getElementById('charactersNoSpaces'),sentences=document.getElementById('sentences'),paragraphs=document.getElementById('paragraphs'),readingTime=document.getElementById('readingTime'),status=document.getElementById('status');
 const count=()=>{
   const value=text.value;
   const wordList=value.trim()?value.trim().split(/\s+/u):[];
   const sentenceList=value.match(/[^.!?]+[.!?]+(?=\s|$)|[^.!?]+$/gu)||[];
   const paragraphList=value.trim()?value.trim().split(/\n\s*\n/u).filter(Boolean):[];
   const w=wordList.length;
   words.textContent=w.toLocaleString();
   characters.textContent=value.length.toLocaleString();
   charactersNoSpaces.textContent=value.replace(/\s/gu,'').length.toLocaleString();
   sentences.textContent=sentenceList.length.toLocaleString();
   paragraphs.textContent=paragraphList.length.toLocaleString();
   readingTime.textContent=w?Math.max(1,Math.ceil(w/200))+' min':'0 min';
   status.textContent=w.toLocaleString()+' '+(w===1?'word':'words');
 };
 text.addEventListener('input',count);clear.addEventListener('click',()=>{text.value='';count();text.focus()});count();
})();
</script>
<?php smarttoolz_tool_page_end(); ?>
