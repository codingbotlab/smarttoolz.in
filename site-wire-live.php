<?php
/* SmartToolz — LIVE SITE FILE MAP.
 * The graph is built from the deployed site's own filesystem, not GitHub.
 * It exposes only file paths and inferred local references from readable text files.
 */
header('Content-Type: text/html; charset=UTF-8');
$ROOT = realpath(__DIR__);
$SKIP = ['.git','node_modules','vendor'];
$TEXT_EXT = '/\.(php|js|mjs|jsx|ts|tsx|css|scss|html|htm|json|md|txt|xml|svg|yml|yaml|ini|conf|htaccess)$/i';
function rel_path($path,$root){$r=realpath($path); if($r===false)return ''; return ltrim(str_replace('\\','/',substr($r,strlen($root))),'/');}
function blocked($rel){global $SKIP; foreach($SKIP as $x){if(preg_match('#(^|/)'.preg_quote($x,'#').'(/|$)#',$rel))return true;} return false;}
$files=[]; $dirs=[];
$it=new RecursiveIteratorIterator(new RecursiveDirectoryIterator($ROOT, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);
foreach($it as $f){$rel=rel_path($f->getPathname(),$ROOT); if($rel===''||blocked($rel))continue; if($f->isDir())$dirs[$rel]=true; elseif($f->isFile())$files[$rel]=['size'=>$f->getSize(),'ext'=>preg_match($TEXT_EXT,$rel)];}
ksort($files); ksort($dirs);
$refs=[];
foreach($files as $path=>$meta){ if(!$meta['ext'] || $meta['size']>500000)continue; $src=@file_get_contents($ROOT.'/'.$path); if($src===false)continue; $found=[]; $patterns=[
 '/\b(?:require|require_once|include|include_once)\s*\(?\s*[\'\"]([^\'\"]+)[\'\"]/i',
 '/\b(?:import|export)\s+(?:[^\'\";]+?\s+from\s+)?[\'\"]([^\'\"]+)[\'\"]/i',
 '/\b(?:src|href)\s*=\s*[\'\"]([^\'\"]+)[\'\"]/i',
 '/\burl\(\s*[\'\"]?([^\'\"\)]+)[\'\"]?\s*\)/i'
 ];
 foreach($patterns as $re){preg_match_all($re,$src,$mm); foreach($mm[1]??[] as $v){$v=trim($v); if($v===''||preg_match('/^(https?:|data:|mailto:|javascript:|#|\/\/)/i',$v))continue; $found[]=$v;}}
 foreach(array_unique($found) as $ref){
   $ref=preg_replace('/[#?].*$/','',$ref); $base=dirname($path); $candidate=ltrim(str_replace('\\','/',($ref[0]==='/'?$ref:ltrim($base.'/'.$ref,'./'))),'/');
   $variants=[$candidate,$candidate.'.php',$candidate.'.js',$candidate.'.css',$candidate.'.json',$candidate.'/index.php',$candidate.'/index.html'];
   foreach($variants as $v){if(isset($files[$v])){$refs[]=['from'=>$path,'to'=>$v,'type'=>'dep'];break;}}
 }
}
$payload=['root'=>basename($ROOT),'files'=>array_keys($files),'dirs'=>array_keys($dirs),'refs'=>$refs,'generated_at'=>date('c')];
if(isset($_GET['data'])){header('Content-Type: application/json; charset=UTF-8'); echo json_encode($payload,JSON_UNESCAPED_SLASHES); exit;}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>SmartToolz — Live Site Mind Map</title>
<style>:root{--bg:#02050b;--panel:#07111f;--cyan:#28dfff;--violet:#9b7cff;--green:#43f5aa;--red:#ff5d73;--text:#edf5ff;--muted:#71809d}*{box-sizing:border-box}html,body{margin:0;width:100%;height:100%;overflow:hidden;background:var(--bg);color:var(--text);font-family:Inter,system-ui,Arial}body{background:radial-gradient(circle at 50% 48%,#10234a 0,#02050b 55%)}#c{width:100%;height:100%;display:block;cursor:grab}.hud{position:fixed;z-index:3;top:18px;left:22px;right:22px;display:flex;justify-content:space-between;pointer-events:none}.title{pointer-events:auto}.ey{font:900 9px ui-monospace;letter-spacing:2px;color:#93a2c0}.ey i{display:inline-block;width:7px;height:7px;border-radius:50%;background:var(--green);box-shadow:0 0 16px var(--green);margin-right:7px}.title h1{margin:6px 0 2px;font-size:clamp(25px,3.2vw,42px);letter-spacing:-2px}.title h1 span{color:var(--cyan)}.title p{margin:0;color:var(--muted);font-size:10px}.stats{display:flex;gap:7px;flex-wrap:wrap;justify-content:flex-end}.pill{padding:9px 11px;border:1px solid #ffffff18;background:#07111ddd;border-radius:10px;font:800 9px ui-monospace;color:#8998b5}.pill b{color:#fff}.bar{position:fixed;z-index:4;bottom:18px;left:20px;display:flex;gap:7px}.bar input,.bar button{border:1px solid #ffffff1c;background:#07111ddd;color:#eaf2ff;border-radius:9px;padding:9px 11px;font-size:10px;font-weight:800}.bar input{width:240px;outline:0}.bar button{cursor:pointer}.side{position:fixed;z-index:5;display:none;right:18px;top:90px;width:min(360px,calc(100vw - 36px));padding:14px;border:1px solid #ffffff1b;border-radius:14px;background:#07111eef;backdrop-filter:blur(15px)}.side.show{display:block}.side small{color:#7584a1;font:900 8px ui-monospace;letter-spacing:1px}.side h2{font-size:14px;margin:7px 0;word-break:break-word}.side code{color:#a9edff;font:9px ui-monospace;word-break:break-all}.side p{color:#98a6c0;font-size:9px;line-height:1.6}.load{position:fixed;z-index:10;inset:0;display:grid;place-items:center;background:#02050bf2}.load div{text-align:center}.ring{width:52px;height:52px;border:2px solid #ffffff12;border-top-color:var(--cyan);border-right-color:var(--violet);border-radius:50%;animation:spin .8s linear infinite;margin:auto}.load b{display:block;margin-top:13px;font-size:12px}.load span{display:block;color:var(--muted);font-size:9px;margin-top:5px}@keyframes spin{to{transform:rotate(360deg)}}@media(max-width:700px){.hud{left:12px;right:12px;top:11px}.title p{display:none}.stats{max-width:48%}.pill{padding:7px}.bar{left:12px;right:12px;bottom:12px}.bar input{flex:1;width:auto}.side{right:12px;top:78px}}</style></head><body>
<div class="load" id="load"><div><div class="ring"></div><b>Reading the LIVE site filesystem…</b><span id="msg">Mapping folders and files</span></div></div>
<div class="hud"><div class="title"><div class="ey"><i></i>SMARTTOOLZ / LIVE SITE GRAPH</div><h1>Full File <span>Mind Map</span></h1><p>Actual deployed-site files and detected local connections.</p></div><div class="stats"><div class="pill">FILES <b id="fc">0</b></div><div class="pill">CONNECTIONS <b id="ec">0</b></div><div class="pill">ACTIVE <b id="ac">0</b></div><div class="pill">STATUS <b id="st">LIVE</b></div></div></div>
<canvas id="c"></canvas><div class="side" id="side"><small>LIVE SITE FILE</small><h2 id="sn"></h2><code id="sp"></code><p id="sr"></p></div><div class="bar"><input id="q" placeholder="Find file / folder…"><button id="fit">FIT</button><button id="pause">PAUSE</button></div>
<script>
const DATA=<?php echo json_encode($payload,JSON_UNESCAPED_SLASHES); ?>;
const c=document.getElementById('c'),x=c.getContext('2d');let W,H,dpr,zoom=1,ox=0,oy=0,drag=false,lx=0,ly=0,paused=false,nodes=[],edges=[],sel=null,hov=null;
function resize(){dpr=Math.min(devicePixelRatio||1,2);W=innerWidth;H=innerHeight;c.width=W*dpr;c.height=H*dpr;x.setTransform(dpr,0,0,dpr,0,0)}addEventListener('resize',resize);resize();
const map=new Map();function edge(a,b,t){if(!a||!b||a===b)return;let k=a.id+'>'+b.id;if(edges.some(e=>e.k===k))return;edges.push({a,b,t,k})}
DATA.dirs.forEach((p,i)=>{let n={id:'d:'+p,path:p,name:p.split('/').pop()+'/',folder:1,x:0,y:0,vx:0,vy:0,r:7};nodes.push(n);map.set(n.id,n);});
DATA.files.forEach(p=>{let n={id:'f:'+p,path:p,name:p.split('/').pop(),folder:0,x:0,y:0,vx:0,vy:0,r:3.5};nodes.push(n);map.set(n.id,n)});
for(const n of nodes){if(n.path.includes('/')){let parent=n.path.split('/').slice(0,-1).join('/');let p=map.get('d:'+parent);if(p)edge(p,n,'tree')}}
for(const r of DATA.refs)edge(map.get('f:'+r.from),map.get('f:'+r.to),'dep');
document.getElementById('fc').textContent=DATA.files.length+' + '+DATA.dirs.length+' dirs';document.getElementById('ec').textContent=edges.length;document.getElementById('load').style.display='none';
function hash(s){let h=0;for(let i=0;i<s.length;i++)h=(h*31+s.charCodeAt(i))>>>0;return h/4294967296}
function layout(){let cx=W/2,cy=H/2;nodes.forEach((n,i)=>{let a=i/nodes.length*Math.PI*2;let rr=n.folder?Math.min(W,H)*(.08+.10*(i%9)/9):Math.min(W,H)*(.18+.30*((i*37)%101)/100);n.x=cx+Math.cos(a)*rr;n.y=cy+Math.sin(a)*rr;n.vx=n.vy=0});for(let k=0;k<90;k++)sim(.6)}
function sim(dt){for(let i=0;i<nodes.length;i++){let a=nodes[i];a.vx+=(W/2-a.x)*.00025;a.vy+=(H/2-a.y)*.00025;for(let j=i+1;j<nodes.length;j++){let b=nodes[j],dx=b.x-a.x,dy=b.y-a.y,d2=dx*dx+dy*dy+1;if(d2>140000)continue;let d=Math.sqrt(d2),f=1200/d2;a.vx-=dx/d*f;a.vy-=dy/d*f;b.vx+=dx/d*f;b.vy+=dy/d*f}}for(let e of edges){let a=e.a,b=e.b,dx=b.x-a.x,dy=b.y-a.y,d=Math.hypot(dx,dy)||1,target=e.t==='tree'?55:105,f=(d-target)*.0018;a.vx+=dx/d*f;a.vy+=dy/d*f;b.vx-=dx/d*f;b.vy-=dy/d*f}nodes.forEach(n=>{n.vx*=.84;n.vy*=.84;n.x+=n.vx*dt;n.y+=n.vy*dt})}
function P(n){return{x:n.x*zoom+ox,y:n.y*zoom+oy}}function draw(t){x.clearRect(0,0,W,H);for(let e of edges){let a=P(e.a),b=P(e.b),active=sel&&(e.a===sel||e.b===sel);x.beginPath();x.moveTo(a.x,a.y);x.lineTo(b.x,b.y);x.strokeStyle=active?'rgba(67,245,170,.75)':e.t==='dep'?'rgba(40,223,255,.24)':'rgba(155,124,255,.11)';x.lineWidth=active?2:e.t==='dep'?1.1:.65;x.stroke();if(e.t==='dep'&&!paused){let q=(t/1700+hash(e.k))%1,px=a.x+(b.x-a.x)*q,py=a.y+(b.y-a.y)*q;x.beginPath();x.arc(px,py,2.7,0,7);x.fillStyle='#43f5aa';x.shadowBlur=14;x.shadowColor='#43f5aa';x.fill();x.shadowBlur=0}}
let activeCount=0;for(let n of nodes){let p=P(n),active=n===sel||n===hov||(sel&&connected(n,sel));if(active)activeCount++;let rr=(n.r+(active?2.4:0))*Math.max(.65,Math.min(1.7,zoom));x.beginPath();x.arc(p.x,p.y,rr,0,7);x.fillStyle=n.folder?'#9b7cff':n===sel?'#fff':n===hov?'#28dfff':'#15233b';x.shadowBlur=active?18:5;x.shadowColor=n.folder?'#9b7cff':'#28dfff';x.fill();x.shadowBlur=0;if(zoom>.72||active){x.fillStyle=active?'#eaf6ff':'#74829e';x.font=(active?'9':'7')+'px ui-monospace';x.fillText(n.name,p.x+rr+4,p.y+3)}}document.getElementById('ac').textContent=activeCount}
function connected(a,b){return edges.some(e=>(e.a===a&&e.b===b)||(e.b===a&&e.a===b))}
function loop(t){if(!paused)sim(.012);draw(t);requestAnimationFrame(loop)}layout();requestAnimationFrame(loop);
function hit(mx,my){let best=null,bd=Infinity;for(let n of nodes){let p=P(n),d=Math.hypot(mx-p.x,my-p.y);if(d<Math.max(12,n.r*3)&&d<bd){best=n;bd=d}}return best}
c.addEventListener('pointerdown',e=>{drag=true;lx=e.clientX;ly=e.clientY;c.classList.add('dragging')});addEventListener('pointermove',e=>{if(drag){ox+=e.clientX-lx;oy+=e.clientY-ly;lx=e.clientX;ly=e.clientY}hov=hit(e.clientX,e.clientY)});addEventListener('pointerup',e=>{if(drag){let moved=Math.hypot(e.clientX-lx,e.clientY-ly);drag=false;c.classList.remove('dragging')}let n=hit(e.clientX,e.clientY);if(n){sel=n;document.getElementById('side').classList.add('show');document.getElementById('sn').textContent=n.name;document.getElementById('sp').textContent=n.path;let rs=DATA.refs.filter(r=>r.from===n.path||r.to===n.path);document.getElementById('sr').textContent=(n.folder?'Folder node':'File node')+' • '+rs.length+' detected code connection'+(rs.length===1?'':'s')+'.'}});
c.addEventListener('wheel',e=>{e.preventDefault();let z=Math.exp(-e.deltaY*.001);zoom=Math.max(.2,Math.min(4,zoom*z))},{passive:false});
document.getElementById('pause').onclick=()=>{paused=!paused;document.getElementById('pause').textContent=paused?'PLAY':'PAUSE'};document.getElementById('fit').onclick=()=>{zoom=1;ox=0;oy=0;layout()};document.getElementById('q').oninput=e=>{let q=e.target.value.toLowerCase().trim();if(!q){sel=null;return}let n=nodes.find(n=>n.path.toLowerCase().includes(q));if(n){sel=n;ox=W/2-n.x*zoom;oy=H/2-n.y*zoom;document.getElementById('side').classList.add('show');document.getElementById('sn').textContent=n.name;document.getElementById('sp').textContent=n.path;}};addEventListener('keydown',e=>{if(e.key.toLowerCase()==='r'){zoom=1;ox=0;oy=0;layout()}});
</script></body></html>