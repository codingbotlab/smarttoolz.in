<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$tool = strtolower(trim((string)($_GET['tool'] ?? '')));
$tool = preg_replace('/[^a-z0-9-]/', '', $tool) ?: 'tool';

$catalog = [
    'pdf-to-jpg'=>'PDF to JPG','pdf-merger'=>'PDF Merger','pdf-splitter'=>'PDF Splitter','pdf-compressor'=>'PDF Compressor',
    'pdf-to-png'=>'PDF to PNG','png-to-pdf'=>'PNG to PDF','text-to-pdf'=>'Text to PDF','qr-reader'=>'QR Code Reader',
    'random-number-generator'=>'Random Number Generator','uuid-generator'=>'UUID Generator','timestamp-converter'=>'Timestamp Converter',
    'unix-timestamp'=>'Unix Timestamp','lorem-ipsum-generator'=>'Lorem Ipsum Generator','age-calculator'=>'Age Calculator',
    'percentage-calculator'=>'Percentage Calculator','bmi-calculator'=>'BMI Calculator','unit-converter'=>'Unit Converter',
    'stopwatch-timer'=>'Stopwatch / Timer','color-converter'=>'Color Converter','base64-encoder'=>'Base64 Encoder',
    'base64-decoder'=>'Base64 Decoder','html-encoder'=>'HTML Encoder','html-decoder'=>'HTML Decoder',
    'markdown-to-html'=>'Markdown to HTML','text-to-slug'=>'Text to Slug','remove-extra-spaces'=>'Remove Extra Spaces',
    'sort-lines'=>'Sort Lines','reverse-text'=>'Reverse Text'
];
$title = $catalog[$tool] ?? ucwords(str_replace('-', ' ', $tool));

require_once __DIR__ . '/header.php';
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= htmlspecialchars($title) ?> — SmartToolz</title>
<meta name="description" content="<?= htmlspecialchars($title) ?> online tool from SmartToolz.">
<script src="https://cdn.jsdelivr.net/npm/pdf-lib@1.17.1/dist/pdf-lib.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
<style>
body{margin:0;background:#f6f8fc;color:#172033;font-family:Inter,system-ui,-apple-system,Segoe UI,Arial,sans-serif}.router-wrap{width:min(980px,calc(100% - 28px));margin:45px auto 70px}.tool-head{background:#fff;border:1px solid #e5e9f0;border-radius:24px;padding:34px;box-shadow:0 18px 50px rgba(28,37,65,.07);margin-bottom:18px}.tool-head .tag{color:#635bff;font-size:12px;font-weight:800;letter-spacing:.8px;text-transform:uppercase}.tool-head h1{font-size:clamp(30px,5vw,48px);letter-spacing:-1.5px;margin:8px 0}.tool-head p{color:#707b8e;margin:0}.panel{background:#fff;border:1px solid #e5e9f0;border-radius:20px;padding:24px;box-shadow:0 14px 35px rgba(28,37,65,.05)}textarea,input,select{width:100%;padding:13px;border:1px solid #dfe4ec;border-radius:12px;box-sizing:border-box;outline:0;background:#fff;margin:7px 0 12px}textarea{min-height:210px;resize:vertical}input[type=file],input[type=color]{padding:8px}.row{display:grid;grid-template-columns:repeat(2,1fr);gap:12px}.btn{border:0;border-radius:11px;padding:12px 17px;background:#635bff;color:#fff;font-weight:800;cursor:pointer;margin:4px}.btn.secondary{background:#edf0f6;color:#273147}.result{margin-top:15px;padding:15px;border-radius:12px;background:#f7f8fc;border:1px solid #e5e9f0;white-space:pre-wrap;overflow:auto}.preview{max-width:100%;margin-top:15px;border-radius:12px}.note{font-size:12px;color:#707b8e;margin-top:10px}.hidden{display:none}@media(max-width:650px){.row{grid-template-columns:1fr}.tool-head{padding:24px}}
</style>
</head>
<body>
<div class="router-wrap">
<section class="tool-head"><div class="tag">SMARTTOOLZ TOOL</div><h1><?= htmlspecialchars($title) ?></h1><p>Fast, browser-based utility. Your files stay in your browser wherever the operation can be completed locally.</p></section>
<section class="panel" id="app"></section>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
<script>
const tool=<?=json_encode($tool)?>, app=document.getElementById('app');
const esc=s=>String(s).replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
const download=(blob,name)=>{const a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download=name;a.click();setTimeout(()=>URL.revokeObjectURL(a.href),1000)};
const textTools={
'base64-encoder':['Text','<textarea id="in" placeholder="Enter text..."></textarea><button class="btn" onclick="out.value=btoa(unescape(encodeURIComponent(in.value)))">Encode</button><textarea id="out" placeholder="Result..."></textarea>'],
'base64-decoder':['Base64','<textarea id="in" placeholder="Paste Base64..."></textarea><button class="btn" onclick="try{out.value=decodeURIComponent(escape(atob(in.value)))}catch(e){out.value=\'Invalid Base64\'}">Decode</button><textarea id="out"></textarea>'],
'html-encoder':['HTML','<textarea id="in"></textarea><button class="btn" onclick="out.value=in.value.replace(/[&<>\"\']/g,c=>({\'&\':\'&amp;\',\'<\':\'&lt;\',\'>\':\'&gt;\',\'\"\':\'&quot;\',\'\'\':\'&#39;\'}[c]))">Encode</button><textarea id="out"></textarea>'],
'html-decoder':['HTML','<textarea id="in"></textarea><button class="btn" onclick="{const x=document.createElement(\'textarea\');x.innerHTML=in.value;out.value=x.value}">Decode</button><textarea id="out"></textarea>'],
'markdown-to-html':['Markdown','<textarea id="in" placeholder="# Heading\n\n**bold** and *italic*"></textarea><button class="btn" onclick="out.value=in.value.replace(/^### (.*)$/gm,\'<h3>$1</h3>\').replace(/^## (.*)$/gm,\'<h2>$1</h2>\').replace(/^# (.*)$/gm,\'<h1>$1</h1>\').replace(/\\*\\*(.*?)\\*\\*/g,\'<strong>$1</strong>\').replace(/\\*(.*?)\\*/g,\'<em>$1</em>\').replace(/\\n\\n/g,\'<br><br>\')">Convert</button><textarea id="out"></textarea>'],
'text-to-slug':['Text','<input id="in" placeholder="Hello World!"/><button class="btn" onclick="out.value=in.value.toLowerCase().trim().replace(/[^a-z0-9\\s-]/g,\'\').replace(/\\s+/g,\'-\').replace(/-+/g,\'-\')">Create Slug</button><input id="out"/>'],
'remove-extra-spaces':['Text','<textarea id="in"></textarea><button class="btn" onclick="out.value=in.value.replace(/^[ \\t]+|[ \\t]+$/gm,\'\').replace(/[ \\t]{2,}/g,\' \')">Clean</button><textarea id="out"></textarea>'],
'sort-lines':['Text','<textarea id="in"></textarea><select id="order"><option value="asc">A → Z</option><option value="desc">Z → A</option></select><button class="btn" onclick="{let a=in.value.split(/\\r?\\n/);a.sort((x,y)=>x.localeCompare(y,undefined,{numeric:true,sensitivity:\'base\'}));if(order.value===\'desc\')a.reverse();out.value=a.join(\'\\n\')}">Sort</button><textarea id="out"></textarea>'],
'reverse-text':['Text','<textarea id="in"></textarea><button class="btn" onclick="out.value=[...in.value].reverse().join(\'\')">Reverse</button><textarea id="out"></textarea>']
};
function basic(){const x=textTools[tool];app.innerHTML='<h2>'+x[0]+'</h2>'+x[1]}
function render(){
if(textTools[tool])return basic();
const A={
'color-converter':()=>app.innerHTML='<h2>Color Converter</h2><input id="c" type="color" value="#635bff"><input id="hex" placeholder="#635bff"><div class="result" id="r"></div><button class="btn" onclick="showColor()">Convert</button>',
'random-number-generator':()=>app.innerHTML='<h2>Random Number</h2><div class="row"><input id="min" type="number" value="1"><input id="max" type="number" value="100"></div><button class="btn" onclick="r.textContent=Math.floor(Math.random()*(+max.value-+min.value+1))+ +min.value">Generate</button><div class="result" id="r"></div>',
'uuid-generator':()=>app.innerHTML='<h2>UUID Generator</h2><button class="btn" onclick="r.textContent=crypto.randomUUID()">Generate UUID</button><div class="result" id="r"></div>',
'timestamp-converter':()=>app.innerHTML='<h2>Timestamp Converter</h2><input id="v" type="number" placeholder="Unix timestamp"><button class="btn" onclick="r.textContent=new Date(+v.value*1000).toString()">Convert</button><div class="result" id="r"></div>',
'unix-timestamp':()=>app.innerHTML='<h2>Unix Timestamp</h2><button class="btn" onclick="r.textContent=Math.floor(Date.now()/1000)">Current Timestamp</button><div class="result" id="r"></div>',
'lorem-ipsum-generator':()=>app.innerHTML='<h2>Lorem Ipsum</h2><input id="n" type="number" value="3" min="1" max="20"><button class="btn" onclick="genLorem()">Generate</button><textarea id="r"></textarea>',
'age-calculator':()=>app.innerHTML='<h2>Age Calculator</h2><input id="dob" type="date"><button class="btn" onclick="age()">Calculate</button><div class="result" id="r"></div>',
'percentage-calculator':()=>app.innerHTML='<h2>Percentage Calculator</h2><div class="row"><input id="a" type="number" placeholder="Percentage"><input id="b" type="number" placeholder="Number"></div><button class="btn" onclick="r.textContent=(+a.value*+b.value/100).toFixed(2)">Calculate</button><div class="result" id="r"></div>',
'bmi-calculator':()=>app.innerHTML='<h2>BMI Calculator</h2><div class="row"><input id="w" type="number" placeholder="Weight kg"><input id="h" type="number" placeholder="Height cm"></div><button class="btn" onclick="bmi()">Calculate</button><div class="result" id="r"></div>',
'unit-converter':()=>app.innerHTML='<h2>Unit Converter</h2><div class="row"><input id="u" type="number" value="1"><select id="from"><option value="m">Meters</option><option value="km">Kilometers</option><option value="ft">Feet</option><option value="mi">Miles</option><option value="kg">Kilograms</option><option value="lb">Pounds</option></select></div><select id="to"><option value="m">Meters</option><option value="km">Kilometers</option><option value="ft">Feet</option><option value="mi">Miles</option><option value="kg">Kilograms</option><option value="lb">Pounds</option></select><button class="btn" onclick="convertUnit()">Convert</button><div class="result" id="r"></div>',
'stopwatch-timer':()=>app.innerHTML='<h2>Stopwatch / Timer</h2><div class="result" id="r">00:00:00</div><button class="btn" onclick="startWatch()">Start</button><button class="btn secondary" onclick="stopWatch()">Stop</button><button class="btn secondary" onclick="resetWatch()">Reset</button>',
'qr-reader':()=>app.innerHTML='<h2>QR Code Reader</h2><input id="f" type="file" accept="image/*"><canvas id="cv" class="hidden"></canvas><div class="result" id="r">Choose a QR image.</div>',
'pdf-merger':()=>pdfUI('merge'),'pdf-splitter':()=>pdfUI('split'),'pdf-compressor':()=>pdfUI('compress'),'pdf-to-jpg':()=>pdfImageUI('jpg'),'pdf-to-png':()=>pdfImageUI('png'),'png-to-pdf':()=>imagePdfUI(),'text-to-pdf':()=>textPdfUI()
};
(A[tool]||(()=>app.innerHTML='<h2>Tool</h2><p>This tool route is available, but its implementation has not been added yet.</p>'))();
}
function showColor(){const x=hex.value||c.value;const m=x.match(/^#([0-9a-f]{6})$/i);if(!m){r.textContent='Use a 6-digit HEX value.';return}const q=m[1],R=parseInt(q.slice(0,2),16),G=parseInt(q.slice(2,4),16),B=parseInt(q.slice(4,6),16);r.textContent=`HEX: #${q.toUpperCase()}\nRGB: ${R}, ${G}, ${B}`}
function genLorem(){const p='lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua';let a=[];for(let i=0;i<+n.value;i++)a.push(p.split(' ').sort(()=>Math.random()-.5).slice(0,35).join(' ')+'.');r.value=a.join('\n\n')}
function age(){if(!dob.value)return;r.textContent='Age: '+(new Date().getFullYear()-new Date(dob.value).getFullYear())+' years (approx.)'}
function bmi(){const x=+h.value/100;r.textContent='BMI: '+(+w.value/(x*x)).toFixed(2)}
const factors={m:1,km:1000,ft:.3048,mi:1609.344,kg:1,lb:.453592};function convertUnit(){r.textContent=((+u.value*factors[from.value])/factors[to.value]).toFixed(6)+' '+to.value}
let wt=0,wi=0;function startWatch(){if(wi)return;let s=Date.now()-wt;wi=setInterval(()=>{let z=Date.now()-s;let h=String(Math.floor(z/3600000)).padStart(2,'0'),m=String(Math.floor(z/60000)%60).padStart(2,'0'),q=String(Math.floor(z/1000)%60).padStart(2,'0');r.textContent=`${h}:${m}:${q}`},200)}function stopWatch(){if(wi){clearInterval(wi);wi=0;wt=Date.now()-Date.now()}}function resetWatch(){stopWatch();wt=0;r.textContent='00:00:00'}
function pdfUI(mode){app.innerHTML='<h2>PDF '+mode+'</h2><input id="files" type="file" accept="application/pdf" multiple><button class="btn" onclick="runPdf(\''+mode+'\')">Process</button><div class="note">PDF operations run in your browser using PDF-LIB.</div><div class="result" id="r"></div>'}
async function runPdf(mode){const fs=[...files.files];if(!fs.length)return;r.textContent='Processing…';try{if(mode==='merge'){const out=await PDFLib.PDFDocument.create();for(const f of fs){const src=await PDFLib.PDFDocument.load(await f.arrayBuffer());const pages=await out.copyPages(src,src.getPageIndices());pages.forEach(p=>out.addPage(p))}download(new Blob([await out.save()],{type:'application/pdf'}),'merged.pdf');r.textContent='Done.'}else if(mode==='split'){const src=await PDFLib.PDFDocument.load(await fs[0].arrayBuffer());for(let i=0;i<src.getPageCount();i++){const out=await PDFLib.PDFDocument.create();const [p]=await out.copyPages(src,[i]);out.addPage(p);download(new Blob([await out.save()],{type:'application/pdf'}),`page-${i+1}.pdf`)}r.textContent='Pages downloaded.'}else{const src=await PDFLib.PDFDocument.load(await fs[0].arrayBuffer());download(new Blob([await src.save({useObjectStreams:true})],{type:'application/pdf'}),'optimized.pdf');r.textContent='PDF re-saved with object streams enabled.'}}catch(e){r.textContent=e.message}}
async function pdfImageUI(fmt){app.innerHTML='<h2>PDF to '+fmt.toUpperCase()+'</h2><input id="files" type="file" accept="application/pdf"><button class="btn" onclick="renderPdf(\''+fmt+'\')">Convert</button><div class="result" id="r"></div>'}
async function renderPdf(fmt){const f=files.files[0];if(!f)return;pdfjsLib.GlobalWorkerOptions.workerSrc='https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';const pdf=await pdfjsLib.getDocument({data:await f.arrayBuffer()}).promise;const zip=new JSZip();for(let i=1;i<=pdf.numPages;i++){const page=await pdf.getPage(i),vp=page.getViewport({scale:2}),cv=document.createElement('canvas');cv.width=vp.width;cv.height=vp.height;await page.render({canvasContext:cv.getContext('2d'),viewport:vp}).promise;const b=await new Promise(x=>cv.toBlob(x,'image/'+fmt));zip.file(`page-${i}.${fmt}`,await b.arrayBuffer())}download(await zip.generateAsync({type:'blob'}),`pdf-pages-${fmt}.zip`);r.textContent='Done.'}
function imagePdfUI(){app.innerHTML='<h2>PNG to PDF</h2><input id="files" type="file" accept="image/png,image/jpeg" multiple><button class="btn" onclick="makeImagePdf()">Create PDF</button>'}
async function makeImagePdf(){const {jsPDF}=window.jspdf;const out=new jsPDF();const fs=[...files.files];if(!fs.length)return;for(let i=0;i<fs.length;i++){if(i)out.addPage();const u=URL.createObjectURL(fs[i]);await new Promise(ok=>{const im=new Image();im.onload=()=>{const w=190,h=w*im.height/im.width;out.addImage(im,'JPEG',10,10,w,h);URL.revokeObjectURL(u);ok()};im.src=u})}out.save('images.pdf')}
function textPdfUI(){app.innerHTML='<h2>Text to PDF</h2><textarea id="in" placeholder="Enter text..."></textarea><button class="btn" onclick="makeTextPdf()">Create PDF</button>'}function makeTextPdf(){const {jsPDF}=window.jspdf,doc=new jsPDF();doc.text(doc.splitTextToSize(in.value,180),15,20);doc.save('text.pdf')}
if(tool==='qr-reader'){setTimeout(()=>f.addEventListener('change',async()=>{const im=new Image();im.onload=()=>{cv.width=im.width;cv.height=im.height;const x=cv.getContext('2d');x.drawImage(im,0,0);const d=x.getImageData(0,0,cv.width,cv.height);const q=jsQR(d.data,d.width,d.height);r.textContent=q?q.data:'No QR code detected.'};im.src=URL.createObjectURL(f.files[0])}),0)}
render();
</script>
</body></html>
