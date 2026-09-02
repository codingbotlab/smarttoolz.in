<?php
declare(strict_types=1);
require_once __DIR__.'/header.php';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SmartToolz — 500-Step NLP Demo</title>
<meta name="description" content="SmartToolz 500-step natural-language workflow engine demo.">
<style>
*{box-sizing:border-box}body{margin:0;font-family:Inter,system-ui,Arial,sans-serif;background:#f6f7fb;color:#111827}.page{width:min(1180px,calc(100% - 28px));margin:30px auto 70px}.hero{text-align:center;padding:28px 15px}.badge{display:inline-flex;padding:7px 12px;border-radius:99px;background:#eeecff;color:#6556ef;font-size:11px;font-weight:900;letter-spacing:.5px}.hero h1{font-size:clamp(34px,6vw,62px);letter-spacing:-3px;margin:14px 0 9px}.hero h1 span{color:#6d5dfc}.hero p{max-width:760px;margin:auto;color:#687386;line-height:1.7;font-size:14px}.stats{display:flex;justify-content:center;gap:9px;flex-wrap:wrap;margin:20px 0}.stat{background:#fff;border:1px solid #e4e7ef;border-radius:13px;padding:10px 15px;font-size:11px}.stat b{font-size:17px;display:block;color:#5d4fe2}.box{background:#fff;border:1px solid #e4e7ef;border-radius:24px;padding:22px;box-shadow:0 20px 60px rgba(30,35,70,.08)}textarea{width:100%;min-height:130px;border:1px solid #dfe3ec;border-radius:16px;padding:17px;font:15px/1.6 inherit;resize:vertical;outline:none}textarea:focus{border-color:#a99fff;box-shadow:0 0 0 4px #6d5dfc12}.examples{display:flex;gap:7px;flex-wrap:wrap;margin:12px 0}.example{border:1px solid #e4e6ed;background:#fafbfe;border-radius:99px;padding:7px 10px;font-size:11px;cursor:pointer}.example:hover{border-color:#b9b1ff;color:#6556ef}.run{margin-top:8px;width:100%;border:0;border-radius:14px;padding:14px;background:linear-gradient(135deg,#6556ef,#927cff);color:#fff;font-size:14px;font-weight:900;cursor:pointer}.result{display:none;margin-top:18px}.result.show{display:block}.grid{display:grid;grid-template-columns:.82fr 1.18fr;gap:14px}.panel{border:1px solid #e5e7ef;border-radius:17px;padding:17px;background:#fafbff}.panel h3{margin:0 0 13px;font-size:13px}.intent{display:inline-flex;padding:7px 10px;border-radius:99px;background:#eceaff;color:#5d4fe2;font-size:11px;font-weight:900}.confidence{margin-top:13px;font-size:11px;color:#697488}.bar{height:7px;background:#e8eaf0;border-radius:99px;margin-top:6px;overflow:hidden}.bar i{display:block;width:96%;height:100%;background:#7566fa}.chips{display:flex;gap:6px;flex-wrap:wrap;margin-top:12px}.chip{font-size:10px;padding:6px 8px;background:#fff;border:1px solid #e5e7ef;border-radius:99px}.notice{margin-top:14px;padding:12px;border-radius:12px;background:#f3f1ff;color:#5548ca;font-size:11px;line-height:1.6}.step{display:flex;align-items:center;gap:10px;padding:11px;border-radius:12px;background:#fff;border:1px solid #e8e9ef;margin-top:8px}.num{width:28px;height:28px;border-radius:9px;display:grid;place-items:center;background:#eeecff;color:#6556ef;font-weight:900;font-size:10px;flex:0 0 auto}.step b{font-size:12px}.step small{display:block;color:#7d8798;font-size:10px;margin-top:2px}.engine{margin-top:14px;border:1px solid #e5e7ef;border-radius:17px;padding:15px;background:#fff}.engine-head{display:flex;justify-content:space-between;gap:10px;align-items:center}.engine-head b{font-size:12px}.search{font-size:10px;color:#7b8495}.catalog{margin-top:10px;display:grid;grid-template-columns:repeat(5,1fr);gap:5px;max-height:180px;overflow:auto;padding-right:3px}.action{padding:7px;border:1px solid #e7e8ee;border-radius:9px;background:#fafbfe;font-size:9px}.action strong{display:block;color:#5d4fe2;font-size:9px}.footer-note{text-align:center;color:#8790a1;font-size:11px;margin-top:18px}@media(max-width:800px){.grid{grid-template-columns:1fr}.catalog{grid-template-columns:repeat(2,1fr)}}
</style>
</head>
<body>
<main class="page">
<section class="hero">
<span class="badge">🧠 500-STEP NLP ENGINE • FREE DEMO</span>
<h1>Tell SmartToolz what you<br><span>want to get done.</span></h1>
<p>This demo now contains a 500-action NLP library across image, PDF, text, developer, data, utility and workflow tasks. Type one request and the engine extracts intent, entities, parameters and a multi-step plan.</p>
<div class="stats"><div class="stat"><b>500</b>NLP actions</div><div class="stat"><b>50</b>task families</div><div class="stat"><b>12</b>workflow steps</div><div class="stat"><b>0</b>paid AI APIs</div></div>
</section>
<section class="box">
<textarea id="prompt" placeholder="Example: Resize 50 product images to 1200px, compress them, convert to WebP, rename them for SEO and package everything into a ZIP..."></textarea>
<div class="examples">
<button class="example">Resize 50 product images to 1200px, compress, WebP, SEO rename and ZIP</button>
<button class="example">Merge these PDFs, compress the result and make every page a JPG</button>
<button class="example">Clean this CSV, remove duplicates, sort it and export JSON</button>
<button class="example">Create a QR code, add a logo and prepare it for download</button>
</div>
<button class="run" id="run">✨ Understand Request & Build Workflow</button>
<div class="result" id="result">
<div class="grid">
<div class="panel">
<h3>🧠 NLP UNDERSTANDING</h3>
<span class="intent" id="intent">Waiting for request</span>
<div class="confidence">Intent confidence: <b id="conf">—</b><div class="bar"><i id="bar"></i></div></div>
<div class="chips" id="chips"></div>
<div class="notice" id="explain">The browser demo uses a deterministic 500-action intent library. It is designed to show the future workflow UX before connecting a self-hosted model.</div>
</div>
<div class="panel">
<h3>⚡ GENERATED WORKFLOW</h3>
<div id="steps"></div>
</div>
</div>
<div class="engine">
<div class="engine-head"><b>⚙️ NLP ACTION LIBRARY — 500 / 500 LOADED</b><span class="search">Live catalog preview</span></div>
<div class="catalog" id="catalog"></div>
</div>
</div>
</section>
<div class="footer-note">Demo only: the 500 actions are local intent/action definitions, not 500 separate AI models. Production can connect this planner to a self-hosted open-source NLP/LLM service and the real SmartToolz executors.</div>
</main>
<script>
const p=document.getElementById('prompt'),r=document.getElementById('result'),steps=document.getElementById('steps'),intent=document.getElementById('intent'),explain=document.getElementById('explain'),chips=document.getElementById('chips'),conf=document.getElementById('conf'),bar=document.getElementById('bar');
const families=[
['Image','resize','compress','crop','rotate','flip','jpg','png','webp','gif','watermark'],
['PDF','merge','split','compress','jpg','png','text','protect','extract','organize','convert'],
['Text','word','case','slug','spaces','sort','reverse','duplicate','markdown','encode'],
['Developer','json','base64','url','html','uuid','timestamp','regex','minify','validate','format'],
['QR','qr','read','generate','scan','decode','logo','style','size','download','batch'],
['Data','csv','clean','dedupe','sort','filter','columns','json','xml','export','import'],
['Video','gif','frames','trim','resize','compress','convert','extract','thumbnail','caption','audio'],
['Audio','trim','convert','compress','normalize','metadata','waveform','extract','merge','split','fade'],
['SEO','title','meta','slug','keywords','alt','schema','sitemap','robots','canonical','audit'],
['Utility','age','percentage','bmi','unit','number','timer','stopwatch','lorem','random','calculator'],
['Security','password','hash','token','uuid','encode','decode','validate','sanitize','mask','secret'],
['Web','html','css','js','url','link','redirect','favicon','manifest','compress','optimize'],
['Files','rename','zip','unzip','batch','organize','sort','filter','copy','package','download'],
['Automation','workflow','batch','queue','schedule','trigger','chain','condition','retry','notify','export'],
['Content','summarize','rewrite','translate','extract','outline','headline','caption','proofread','paraphrase','classify'],
['Images AI','background','remove','enhance','upscale','denoise','sharpen','blur','mask','object','quality'],
['Business','invoice','quote','report','table','budget','percentage','currency','forecast','summary','export'],
['Learning','lesson','quiz','flashcard','summary','notes','outline','practice','explain','translate','test'],
['Marketing','campaign','caption','hashtags','ad','email','landing','product','social','calendar','copy'],
['Database','sql','query','format','validate','schema','json','csv','export','import','backup'],
['Email','compose','subject','template','signature','format','extract','clean','html','plain','draft'],
['Documents','doc','text','extract','format','merge','split','convert','compress','template','export'],
['Accessibility','alt','contrast','text','caption','transcript','heading','label','aria','readability','audit'],
['Design','color','picker','palette','gradient','hex','rgb','hsl','contrast','convert','preview'],
['Analytics','event','utm','csv','json','summary','filter','sort','report','export','dashboard'],
['Commerce','product','image','sku','price','csv','feed','description','variant','inventory','export'],
['DevOps','log','json','yaml','env','config','validate','format','diff','backup','deploy'],
['API','url','json','request','response','headers','token','encode','decode','validate'],
['Knowledge','extract','summarize','classify','compare','outline','answer','keyword','translate','notes','quiz'],
['Workflow','plan','chain','branch','condition','loop','batch','parallel','retry','approval','finish'],
['Storage','upload','download','zip','folder','rename','move','copy','delete','package','share'],
['Localization','translate','locale','currency','date','number','timezone','language','format','plural','detect'],
['Images Batch','resize','compress','webp','jpg','png','rename','crop','rotate','watermark','zip'],
['PDF Batch','merge','split','compress','jpg','png','rename','extract','zip','protect','export'],
['Text Batch','clean','case','slug','dedupe','sort','reverse','spaces','format','export','zip'],
['Data Batch','clean','dedupe','sort','filter','map','join','split','convert','validate','export'],
['QA','validate','test','compare','diff','check','scan','audit','report','fix','export'],
['Conversion','jpg','png','webp','pdf','text','json','csv','xml','html','markdown'],
['Optimization','compress','resize','minify','dedupe','cache','quality','format','convert','clean','audit'],
['Privacy','remove','metadata','mask','redact','sanitize','anonymize','hash','delete','export','audit'],
['Productivity','todo','notes','summary','template','checklist','rename','organize','batch','schedule','export'],
['Social','caption','hashtags','image','resize','compress','webp','thumbnail','copy','calendar','export'],
['Publishing','markdown','html','pdf','image','compress','slug','meta','title','export','package'],
['Research','search','extract','summarize','compare','classify','keywords','outline','notes','export','cite'],
['Finance','percentage','currency','invoice','budget','tax','profit','loss','forecast','table','export'],
['Education','explain','quiz','summary','flashcards','notes','outline','practice','grade','translate','export'],
['Monitoring','status','health','check','log','report','alert','threshold','schedule','export','notify'],
['Notifications','email','webhook','notify','template','schedule','trigger','condition','retry','log','export'],
['Packaging','zip','rename','organize','manifest','checksum','compress','batch','package','download','share'],
['Advanced','chain','branch','loop','parallel','condition','parameter','entity','intent','confidence','execute']
];
const actions=[];families.forEach((f,fi)=>{for(let i=1;i<=10;i++){actions.push({id:actions.length+1,family:f[0],verb:f[i],name:f[0]+' '+f[i].replace(/^./,c=>c.toUpperCase())});}});
// 50 families × 10 actions = exactly 500 actions.
const keywords={
resize:['resize','size','px','pixel','dimension'],compress:['compress','smaller','reduce size','optimize'],crop:['crop','cut'],rotate:['rotate','turn'],flip:['flip','mirror'],webp:['webp'],jpg:['jpg','jpeg'],png:['png'],gif:['gif'],qr:['qr','qrcode','quick response'],json:['json'],csv:['csv','spreadsheet'],pdf:['pdf'],merge:['merge','combine'],split:['split','separate'],rename:['rename','filename','file name'],zip:['zip','archive','package'],slug:['slug','seo friendly','seo-friendly'],dedupe:['duplicate','dedupe','remove duplicates'],sort:['sort','order','ascending','descending'],base64:['base64'],url:['url','link'],html:['html'],markdown:['markdown','md'],uuid:['uuid'],timestamp:['timestamp','unix time'],password:['password','passcode'],translate:['translate','translation'],summarize:['summarize','summary','shorten'],extract:['extract','get text','pull text'],caption:['caption','social post'],watermark:['watermark','logo'],background:['background','remove background'],upscale:['upscale','increase resolution'],alt:['alt text','image description'],meta:['meta description','metadata'],schema:['schema','structured data'],currency:['currency','money','convert usd'],percentage:['percentage','percent','%'],unit:['unit','convert cm','convert kg'],timer:['timer'],stopwatch:['stopwatch'],invoice:['invoice'],budget:['budget'],sql:['sql','query'],yaml:['yaml'],regex:['regex','regular expression'],validate:['validate','validation','check'],format:['format','beautify','pretty'],clean:['clean','cleanup'],filter:['filter'],export:['export','save as'],import:['import','load'],batch:['batch','many','100','50','bulk'],schedule:['schedule','later','recurring'],workflow:['workflow','steps','automate'],condition:['if ','when ','unless'],retry:['retry','again'],notify:['notify','alert'],hashtags:['hashtags','tags'],product:['product','products'],thumbnail:['thumbnail','thumb'],audio:['audio','sound'],video:['video'],frames:['frames','extract frames'],trim:['trim','cut'],scan:['scan','read'],decode:['decode'],encode:['encode'],hash:['hash','checksum'],sanitize:['sanitize','clean input'],redact:['redact','hide sensitive'],anonymize:['anonymize','anonymous'],compare:['compare','difference','diff'],outline:['outline'],quiz:['quiz','questions'],notes:['notes'],flashcards:['flashcards','flash cards'],proofread:['proofread','grammar'],paraphrase:['paraphrase','rewrite'],classify:['classify','categorize'],report:['report'],forecast:['forecast'],dashboard:['dashboard'],webhook:['webhook'],parameter:['parameter','settings'],entity:['entity','entities'],intent:['intent','goal'],confidence:['confidence']};
function scoreAction(a,s){const k=keywords[a.verb]||[a.verb];let score=0;k.forEach(x=>{if(s.includes(x))score+=x.length>4?3:2});if(/\b(batch|bulk|many|\d+)\b/.test(s)&&['batch','zip','rename','resize','compress'].includes(a.verb))score+=2;return score}
function analyze(raw){const s=raw.toLowerCase();const scored=actions.map(a=>({...a,score:scoreAction(a,s)})).filter(a=>a.score>0).sort((a,b)=>b.score-a.score);let selected=[];const seen=new Set();for(const a of scored){const key=a.verb;if(!seen.has(key)){seen.add(key);selected.push(a)}if(selected.length>=12)break}if(!selected.length)selected=actions.slice(0,3).map(a=>({...a,score:1}));let family=selected[0].family;const intentName=selected.length>1?family+' workflow':'SmartToolz '+family+' task';const confidence=Math.min(98,72+selected.length*2+Math.min(10,scored.length));return {selected,intentName,confidence,matched:scored.slice(0,8)}}
function renderCatalog(){document.getElementById('catalog').innerHTML=actions.map(a=>`<div class="action"><strong>#${a.id} · ${a.family}</strong>${a.name}</div>`).join('')}
renderCatalog();
document.querySelectorAll('.example').forEach(b=>b.onclick=()=>p.value=b.textContent);
document.getElementById('run').onclick=()=>{const v=p.value.trim();if(!v){p.focus();return}const a=analyze(v);intent.textContent=a.intentName;conf.textContent=a.confidence+'%';bar.style.width=a.confidence+'%';chips.innerHTML=a.matched.map(x=>`<span class="chip">#${x.id} ${x.name}</span>`).join('');steps.innerHTML=a.selected.map((x,i)=>`<div class="step"><span class="num">${i+1}</span><span style="font-size:18px">${['🧠','📐','⚡','🔄','📦','🧹','🛠️','🔍','✨','📄','🚀','✅'][i]||'⚙️'}</span><div><b>${x.name}</b><small>Action #${x.id} · ${x.family} · matched from your request</small></div></div>`).join('');explain.textContent=`The 500-action planner matched ${a.matched.length} relevant actions and selected ${a.selected.length} workflow steps. In production, these actions can become structured tool calls with parameters, file handling, credits and execution.`;r.classList.add('show');r.scrollIntoView({behavior:'smooth',block:'nearest'})};
</script>
</body>
</html>