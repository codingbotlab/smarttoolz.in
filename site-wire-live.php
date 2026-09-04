<?php
/* SmartToolz — LIVE SITE REPOSITORY MAP
 * Reads the currently deployed filesystem under this file's directory.
 * No GitHub API is used. The browser receives only paths and detected local references.
 */
header('Content-Type: text/html; charset=UTF-8');
$ROOT = realpath(__DIR__);
$SKIP = ['.git','node_modules','vendor'];
$TEXT_EXT = '/\.(php|js|mjs|jsx|ts|tsx|css|scss|html|htm|json|md|txt|xml|svg|yml|yaml|ini|conf|htaccess)$/i';
function rel_path($path,$root){$r=realpath($path);if($r===false)return '';return ltrim(str_replace('\\','/',substr($r,strlen($root))),'/');}
function blocked($rel){global $SKIP;foreach($SKIP as $x){if(preg_match('#(^|/)'.preg_quote($x,'#').'(/|$)#',$rel))return true;}return false;}
$files=[];$dirs=[];
try{
  $it=new RecursiveIteratorIterator(new RecursiveDirectoryIterator($ROOT,FilesystemIterator::SKIP_DOTS),RecursiveIteratorIterator::SELF_FIRST);
  foreach($it as $f){$rel=rel_path($f->getPathname(),$ROOT);if($rel===''||blocked($rel))continue;if($f->isDir())$dirs[$rel]=true;elseif($f->isFile())$files[$rel]=['size'=>$f->getSize(),'text'=>(bool)preg_match($TEXT_EXT,$rel)];}
}catch(Throwable $e){}
ksort($files);ksort($dirs);
$refs=[];
foreach($files as $path=>$meta){
  if(!$meta['text']||$meta['size']>600000)continue;
  $src=@file_get_contents($ROOT.'/'.$path);if($src===false)continue;$found=[];
  $patterns=[
    '/\b(?:require|require_once|include|include_once)\s*\(?\s*[\'\"]([^\'\"]+)[\'\"]/i',
    '/\b(?:import|export)\s+(?:[^\'\";]+?\s+from\s+)?[\'\"]([^\'\"]+)[\'\"]/i',
    '/\b(?:src|href)\s*=\s*[\'\"]([^\'\"]+)[\'\"]/i',
    '/\burl\(\s*[\'\"]?([^\'\"\)]+)[\'\"]?\s*\)/i'
  ];
  foreach($patterns as $re){preg_match_all($re,$src,$mm);foreach($mm[1]??[] as $v){$v=trim($v);if($v!==''&&!preg_match('/^(https?:|data:|mailto:|javascript:|#|\/\/)/i',$v))$found[]=$v;}}
  foreach(array_unique($found) as $ref){
    $ref=preg_replace('/[#?].*$/','',$ref);$base=dirname($path);
    $candidate=ltrim(str_replace('\\','/',($ref[0]==='/'?$ref:$base.'/'.$ref)),'/');
    $variants=[$candidate,$candidate.'.php',$candidate.'.js',$candidate.'.css',$candidate.'.json',$candidate.'.html',$candidate.'/index.php',$candidate.'/index.html'];
    foreach($variants as $v){if(isset($files[$v])){$refs[]=['from'=>$path,'to'=>$v,'type'=>'dep'];break;}}
  }
}
$payload=['root'=>basename($ROOT),'files'=>array_keys($files),'dirs'=>array_keys($dirs),'refs'=>$refs,'stamp'=>date('c')];
if(isset($_GET['data'])){header('Cache-Control: no-store, no-cache, must-revalidate');header('Content-Type: application/json; charset=UTF-8');echo json_encode($payload,JSON_UNESCAPED_SLASHES);exit;}
?>
<!doctype html>
<html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>SmartToolz — Live File Neural Map</title>
<style>
:root{--bg:#02050b;--panel:#07111d;--cyan:#22dfff;--violet:#9274ff;--green:#45f3ad;--text:#edf6ff;--muted:#71819d;--grid:#102039}
*{box-sizing:border-box}html,body{margin:0;width:100%;height:100%;overflow:hidden;background:var(--bg);color:var(--text);font-family:Inter,system-ui,-apple-system,Segoe UI,Arial,sans-serif}
body{background:radial-gradient(circle at 50% 50%,#0a1830 0,#030811 42%,#02050b 75%)}
#c{position:fixed;inset:0;width:100%;height:100%;display:block;cursor:grab}.dragging{cursor:grabbing!important}
.header{position:fixed;z-index:5;top:0;left:0;right:0;height:82px;padding:18px 24px;display:flex;justify-content:space-between;align-items:flex-start;background:linear-gradient(180deg,rgba(2,5,11,.96),rgba(2,5,11,.35),transparent);pointer-events:none}.brand{pointer-events:auto}.ey{font:900 8px ui-monospace,monospace;letter-spacing:2px;color:#8191ad}.ey b{color:var(--green);margin-right:7px;text-shadow:0 0 12px var(--green)}h1{margin:5px 0 0;font-size:28px;line-height:1;letter-spacing:-1.4px}h1 span{color:var(--cyan);text-shadow:0 0 22px rgba(34,223,255,.28)}.sub{margin-top:5px;color:#5f6f8b;font-size:9px}.stats{display:flex;gap:7px;pointer-events:auto}.stat{min-width:76px;padding:8px 10px;border:1px solid rgba(130,170,220,.12);background:rgba(5,13,25,.72);border-radius:9px;backdrop-filter:blur(12px)}.stat small{display:block;color:#566783;font:800 7px ui-monospace;letter-spacing:1px}.stat strong{display:block;margin-top:3px;font:800 12px ui-monospace;color:#dfeaff}.live{color:var(--green)!important}
.left{position:fixed;z-index:5;left:18px;top:104px;width:230px;pointer-events:none}.card{pointer-events:auto;border:1px solid rgba(130,170,220,.12);background:rgba(5,13,25,.76);backdrop-filter:blur(15px);border-radius:12px;padding:12px}.label{font:900 7px ui-monospace;color:#647593;letter-spacing:1.3px}.search{width:100%;margin-top:8px;padding:9px 10px;border:1px solid rgba(130,170,220,.15);border-radius:8px;outline:0;background:#030912;color:#eaf4ff;font:700 9px ui-monospace}.search:focus{border-color:rgba(34,223,255,.55);box-shadow:0 0 18px rgba(34,223,255,.08)}.legend{margin-top:11px;color:#62718c;font:700 8px ui-monospace;line-height:2}.dot{display:inline-block;width:7px;height:7px;border-radius:50%;margin-right:6px}.dcyan{background:var(--cyan);box-shadow:0 0 9px var(--cyan)}.dv{background:var(--violet);box-shadow:0 0 9px var(--violet)}.dg{background:var(--green);box-shadow:0 0 9px var(--green)}
.bottom{position:fixed;z-index:6;left:18px;right:18px;bottom:15px;display:flex;justify-content:space-between;align-items:center;pointer-events:none}.controls{display:flex;gap:6px;pointer-events:auto}.btn{border:1px solid rgba(130,170,220,.15);background:rgba(5,13,25,.82);color:#b9c9e4;border-radius:8px;padding:8px 10px;font:800 8px ui-monospace;cursor:pointer}.btn:hover,.btn.active{border-color:rgba(34,223,255,.5);color:#fff}.hint{color:#4f607c;font:700 7px ui-monospace}
.panel{position:fixed;z-index:7;right:18px;top:104px;width:300px;display:none;border:1px solid rgba(130,170,220,.16);background:rgba(4,11,21,.9);backdrop-filter:blur(18px);border-radius:13px;padding:14px;box-shadow:0 18px 60px rgba(0,0,0,.35)}.panel.show{display:block}.panel .k{color:#62728f;font:900 7px ui-monospace;letter-spacing:1.2px}.panel h2{font-size:14px;margin:6px 0;word-break:break-word}.path{color:#9beaff;font:8px ui-monospace;line-height:1.5;word-break:break-all}.meta{margin-top:9px;color:#8090ab;font:8px ui-monospace;line-height:1.8}.close{float:right;border:0;background:none;color:#71819d;cursor:pointer;font-size:16px}
.loading{position:fixed;z-index:20;inset:0;display:grid;place-items:center;background:rgba(2,5,11,.97)}.loadbox{text-align:center}.ring{width:48px;height:48px;border-radius:50%;border:2px solid rgba(255,255,255,.08);border-top-color:var(--cyan);border-right-color:var(--violet);animation:spin .75s linear infinite;margin:auto}.loadbox strong{display:block;margin-top:13px;font-size:11px}.loadbox span{display:block;margin-top:5px;color:var(--muted);font:8px ui-monospace}@keyframes spin{to{transform:rotate(360deg)}}
@media(max-width:700px){.header{height:70px;padding:12px}.sub{display:none}h1{font-size:22px}.stats{gap:4px}.stat{min-width:50px;padding:6px}.stat small{font-size:6px}.stat strong{font-size:9px}.left{left:12px;top:82px;width:190px}.legend{display:none}.panel{left:12px;right:12px;top:82px;width:auto}.hint{display:none}.bottom{left:12px;right:12px}.btn{padding:8px}}
</style></head><body>
<div id="loading" class="loading"><div class="loadbox"><div class="ring"></div><strong>INITIALISING NEURAL MAP</strong><span id="loadmsg">Reading the deployed filesystem…</span></div></div>
<header class="header"><div class="brand"><div class="ey"><b>●</b>SMARTTOOLZ / LIVE SITE GRAPH</div><h1>Repository <span>Mind Map</span></h1><div class="sub">Deployed files • local dependencies • live filesystem scan</div></div><div class="stats"><div class="stat"><small>FILES</small><strong id="fc">0</strong></div><div class="stat"><small>LINKS</small><strong id="ec">0</strong></div><div class="stat"><small>ACTIVE</small><strong id="ac">0</strong></div><div class="stat"><small>STATE</small><strong id="st" class="live">LIVE</strong></div></div></header>
<aside class="left"><div class="card"><div class="label">NEURAL SEARCH</div><input id="q" class="search" placeholder="Search file or folder…" autocomplete="off"><div class="legend"><div><i class="dot dcyan"></i>file dependency</div><div><i class="dot dv"></i>folder / hierarchy</div><div><i class="dot dg"></i>active data pulse</div></div></div></aside>
<canvas id="c"></canvas>
<aside id="panel" class="panel"><button class="close" id="close">×</button><div class="k">SELECTED NODE</div><h2 id="pn"></h2><div class="path" id="pp"></div><div class="meta" id="pm"></div></aside>
<div class="bottom"><div class="controls"><button class="btn" id="fit">FIT MAP</button><button class="btn" id="pause">PAUSE FLOW</button><button class="btn" id="refresh">RESCAN</button></div><div class="hint">DRAG PAN • WHEEL ZOOM • CLICK NODE • R RESET</div></div>
<script>
let DATA=<?php echo json_encode($payload,JSON_UNESCAPED_SLASHES); ?>;
const c=document.getElementById('c'),ctx=c.getContext('2d');let W=0,H=0,dpr=1,zoom=1,ox=0,oy=0,drag=false,lx=0,ly=0,paused=false,nodes=[],edges=[],sel=null,hover=null,lastHash='';
function resize(){dpr=Math.min(devicePixelRatio||1,2);W=innerWidth;H=innerHeight;c.width=W*dpr;c.height=H*dpr;ctx.setTransform(dpr,0,0,dpr,0,0)}addEventListener('resize',resize);resize();
function hash(s){let h=2166136261;for(let i=0;i<s.length;i++)h=Math.imul(h^s.charCodeAt(i),16777619);return (h>>>0)/4294967296}
function build(data){DATA=data;nodes=[];edges=[];sel=null;const folders=new Map();
  data.dirs.forEach(p=>folders.set(p,{id:'d:'+p,path:p,name:p.split('/').pop()+'/',folder:true,x:0,y:0,r:8}));
  data.files.forEach(p=>nodes.push({id:'f:'+p,path:p,name:p.split('/').pop(),folder:false,x:0,y:0,r:3.5}));
  nodes=[...folders.values(),...nodes];const map=new Map(nodes.map(n=>[n.id,n]));
  for(const n of nodes){if(!n.folder)continue;const par=n.path.includes('/')?'d:'+n.path.split('/').slice(0,-1).join('/') :null;if(par&&map.has(par))edges.push({a:map.get(par),b:n,t:'tree',key:n.id})}
  for(const n of nodes){if(n.folder)continue;const parts=n.path.split('/');if(parts.length>1){const p=map.get('d:'+parts.slice(0,-1).join('/'));if(p)edges.push({a:p,b:n,t:'tree',key:n.id})}}
  const seen=new Set(edges.map(e=>e.a.id+'>'+e.b.id));for(const r of data.refs){const a=map.get('f:'+r.from),b=map.get('f:'+r.to);if(a&&b&&!seen.has(a.id+'>'+b.id)){edges.push({a,b,t:'dep',key:a.id+'>'+b.id});seen.add(a.id+'>'+b.id)}}
  document.getElementById('fc').textContent=data.files.length;document.getElementById('ec').textContent=edges.filter(e=>e.t==='dep').length;document.getElementById('st').textContent='LIVE';layout();
}
function layout(){const cx=W/2,cy=H/2;const top=[...new Set(nodes.filter(n=>n.path.includes('/')).map(n=>n.path.split('/')[0]))];const hubs=nodes.filter(n=>n.folder&&!n.path.includes('/'));const hubMap=new Map(hubs.map(n=>[n.path,n]));
  hubs.forEach((n,i)=>{const a=-Math.PI/2+(i/Math.max(1,hubs.length))*Math.PI*2;const rr=Math.min(W,H)*.25;n.x=cx+Math.cos(a)*rr;n.y=cy+Math.sin(a)*rr});
  const orphan=nodes.filter(n=>!n.folder&&!hubMap.has(n.path.split('/')[0]));orphan.forEach((n,i)=>{const a=i/Math.max(1,orphan.length)*Math.PI*2;n.x=cx+Math.cos(a)*Math.min(W,H)*.38;n.y=cy+Math.sin(a)*Math.min(W,H)*.38});
  hubs.forEach((hub,hi)=>{const children=nodes.filter(n=>!n.folder&&n.path.split('/')[0]===hub.path);const base=Math.atan2(hub.y-cy,hub.x-cx);children.forEach((n,i)=>{const span=Math.min(2.1,Math.max(.7,children.length*.045));const a=base-span/2+(i/Math.max(1,children.length-1))*span;const rr=Math.min(W,H)*(.13+Math.min(.18,children.length*.0015));n.x=hub.x+Math.cos(a)*rr;n.y=hub.y+Math.sin(a)*rr})});
  // Keep the map calm: a few deterministic relaxation passes, no chaotic force simulation.
  for(let k=0;k<35;k++){for(const e of edges){const a=e.a,b=e.b,dx=b.x-a.x,dy=b.y-a.y,d=Math.hypot(dx,dy)||1,target=e.t==='tree'?58:130;const f=(d-target)*.004;a.x+=dx/d*f;b.x-=dx/d*f;a.y+=dy/d*f;b.y-=dy/d*f}for(const n of nodes){n.x+=(cx-n.x)*.0005;n.y+=(cy-n.y)*.0005}}
  fit();
}
function fit(){if(!nodes.length)return;let minX=Infinity,maxX=-Infinity,minY=Infinity,maxY=-Infinity;nodes.forEach(n=>{minX=Math.min(minX,n.x);maxX=Math.max(maxX,n.x);minY=Math.min(minY,n.y);maxY=Math.max(maxY,n.y)});const pad=90,sx=(W-pad*2)/(maxX-minX||1),sy=(H-pad*2)/(maxY-minY||1);zoom=Math.max(.35,Math.min(1.35,sx,sy));ox=W/2-(minX+maxX)/2*zoom;oy=H/2-(minY+maxY)/2*zoom}
function P(n){return{x:n.x*zoom+ox,y:n.y*zoom+oy}}
function connected(n){return sel&&edges.some(e=>(e.a===n&&e.b===sel)||(e.b===n&&e.a===sel))}
function draw(t){ctx.clearRect(0,0,W,H);
  // subtle HUD grid
  ctx.save();ctx.globalAlpha=.16;ctx.strokeStyle='#13243d';ctx.lineWidth=1;const step=70;for(let gx=(ox%step)-step;gx<W;gx+=step){ctx.beginPath();ctx.moveTo(gx,0);ctx.lineTo(gx,H);ctx.stroke()}for(let gy=(oy%step)-step;gy<H;gy+=step){ctx.beginPath();ctx.moveTo(0,gy);ctx.lineTo(W,gy);ctx.stroke()}ctx.restore();
  let active=0;
  for(const e of edges){const a=P(e.a),b=P(e.b),hot=e.a===sel||e.b===sel||e.a===hover||e.b===hover;ctx.beginPath();ctx.moveTo(a.x,a.y);ctx.lineTo(b.x,b.y);ctx.strokeStyle=hot?'rgba(34,223,255,.68)':e.t==='dep'?'rgba(34,223,255,.18)':'rgba(146,116,255,.08)';ctx.lineWidth=hot?1.8:e.t==='dep'?1:.55;ctx.stroke();
    if(e.t==='dep'&&!paused){const q=(t/1550+hash(e.key))%1,px=a.x+(b.x-a.x)*q,py=a.y+(b.y-a.y)*q;ctx.beginPath();ctx.arc(px,py,2.2,0,Math.PI*2);ctx.fillStyle='#45f3ad';ctx.shadowBlur=12;ctx.shadowColor='#45f3ad';ctx.fill();ctx.shadowBlur=0}
  }
  for(const n of nodes){const p=P(n),hot=n===sel||n===hover||connected(n);if(hot)active++;const rr=(n.folder?7.5:3.1)+(hot?2:0);ctx.beginPath();ctx.arc(p.x,p.y,rr,0,Math.PI*2);ctx.fillStyle=n.folder?'#9274ff':n===sel?'#fff':n===hover?'#22dfff':'#1a2a43';ctx.shadowBlur=hot?18:5;ctx.shadowColor=n.folder?'#9274ff':'#22dfff';ctx.fill();ctx.shadowBlur=0;
    if(n.folder){ctx.beginPath();ctx.arc(p.x,p.y,rr+5,0,Math.PI*2);ctx.strokeStyle='rgba(146,116,255,.12)';ctx.stroke()}
    if(n.folder||hot||zoom>.88){ctx.font=(hot?'9':'7')+'px ui-monospace';ctx.fillStyle=hot?'#e9f6ff':n.folder?'#9b8cff':'#61718e';ctx.fillText(n.name,p.x+rr+5,p.y+3)}
  }
  document.getElementById('ac').textContent=active;
}
function loop(t){draw(t);requestAnimationFrame(loop)}
function hit(mx,my){let best=null,bd=18;for(const n of nodes){const p=P(n),d=Math.hypot(mx-p.x,my-p.y);if(d<bd){best=n;bd=d}}return best}
function inspect(n){if(!n)return;sel=n;const side=document.getElementById('panel');side.classList.add('show');document.getElementById('pn').textContent=n.name;document.getElementById('pp').textContent=n.path;const links=edges.filter(e=>e.a===n||e.b===n);const deps=links.filter(e=>e.t==='dep').length;document.getElementById('pm').textContent=(n.folder?'FOLDER':'FILE')+'  •  '+links.length+' connected nodes  •  '+deps+' code dependencies';}
c.addEventListener('pointerdown',e=>{drag=true;lx=e.clientX;ly=e.clientY;c.classList.add('dragging')});addEventListener('pointermove',e=>{if(drag){ox+=e.clientX-lx;oy+=e.clientY-ly;lx=e.clientX;ly=e.clientY}hover=hit(e.clientX,e.clientY)});addEventListener('pointerup',e=>{if(drag){drag=false;c.classList.remove('dragging')}const n=hit(e.clientX,e.clientY);if(n)inspect(n)});
c.addEventListener('wheel',e=>{e.preventDefault();const before={x:(e.clientX-ox)/zoom,y:(e.clientY-oy)/zoom};zoom=Math.max(.25,Math.min(3.5,zoom*Math.exp(-e.deltaY*.001)));ox=e.clientX-before.x*zoom;oy=e.clientY-before.y*zoom},{passive:false});
document.getElementById('pause').onclick=()=>{paused=!paused;document.getElementById('pause').textContent=paused?'PLAY FLOW':'PAUSE FLOW';document.getElementById('st').textContent=paused?'PAUSED':'LIVE'};
document.getElementById('fit').onclick=()=>fit();document.getElementById('close').onclick=()=>{sel=null;document.getElementById('panel').classList.remove('show')};
document.getElementById('q').oninput=e=>{const q=e.target.value.toLowerCase().trim();if(!q)return;const n=nodes.find(n=>n.path.toLowerCase().includes(q));if(n){inspect(n);ox=W/2-n.x*zoom;oy=H/2-n.y*zoom}};
addEventListener('keydown',e=>{if(e.key.toLowerCase()==='r'){zoom=1;ox=0;oy=0;fit()}if(e.key==='Escape'){sel=null;document.getElementById('panel').classList.remove('show')}});
async function rescan(){const m=document.getElementById('loadmsg');m.textContent='Scanning current server files…';try{const r=await fetch(location.pathname+'?data=1&t='+Date.now(),{cache:'no-store'});if(!r.ok)throw Error('HTTP '+r.status);const d=await r.json();const sig=JSON.stringify([d.files,d.dirs,d.refs]);if(sig!==lastHash){lastHash=sig;build(d)}m.textContent='Filesystem is current';}catch(e){m.textContent='Rescan failed: '+e.message}}
build(DATA);lastHash=JSON.stringify([DATA.files,DATA.dirs,DATA.refs]);document.getElementById('loading').style.display='none';requestAnimationFrame(loop);
document.getElementById('refresh').onclick=rescan;setInterval(rescan,15000);
</script></body></html>