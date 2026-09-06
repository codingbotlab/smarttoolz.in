<?php declare(strict_types=1); ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Smart3D — 3D Studio</title>
<style>
:root{--bg:#18191c;--panel:#222428;--panel2:#292b30;--line:#3b3e44;--text:#e4e5e7;--muted:#92969d;--blue:#5688bd;--accent:#e7a34c}
*{box-sizing:border-box}html,body{margin:0;width:100%;height:100%;overflow:hidden;background:var(--bg);color:var(--text);font:12px Arial,sans-serif}button,select,input{font:inherit;color:inherit}.app{height:100vh;display:grid;grid-template-rows:40px 1fr 168px 24px}.top{display:flex;align-items:center;gap:5px;padding:4px 7px;background:#111214;border-bottom:1px solid var(--line)}.brand{font-size:15px;font-weight:800;margin:0 14px 0 4px}.brand b{color:#69a4e0}.top button,.top select{height:31px;border:1px solid #41444b;background:var(--panel2);border-radius:3px;padding:0 9px;cursor:pointer}.top button:hover,.top select:hover{background:#34373d}.spacer{flex:1}.mode{min-width:112px}.main{min-height:0;display:grid;grid-template-columns:58px 1fr 265px}.toolbar{background:#1d1f22;border-right:1px solid var(--line);padding:6px 5px;display:flex;flex-direction:column;align-items:center;gap:5px}.tool{width:47px;height:43px;background:var(--panel2);border:1px solid #41444a;border-radius:4px;cursor:pointer;font-size:17px}.tool span{display:block;font-size:8px;color:#a7a9ae;margin-top:3px}.tool:hover,.tool.active{background:#3a526b;border-color:#638caf}.divider{width:40px;height:1px;background:#3b3d42;margin:3px 0}.viewport{position:relative;min-width:0;min-height:0;background:#303238}.canvas{position:absolute;inset:0}.hud{position:absolute;z-index:4;background:#111216dd;border:1px solid #4a4d54;border-radius:4px;padding:7px 9px;color:#a8abb0;pointer-events:none}.help{left:9px;top:9px}.view{right:9px;top:9px}.right{min-width:0;background:#202124;border-left:1px solid var(--line);overflow:auto}.tabs{display:flex;height:36px;border-bottom:1px solid var(--line)}.tabs button{flex:1;border:0;background:transparent;color:#999;cursor:pointer}.tabs .active{color:#fff;border-bottom:2px solid var(--blue)}.title{padding:10px 11px 6px;color:#8f9399;font-size:9px;font-weight:bold;letter-spacing:1px;text-transform:uppercase}.outliner{padding:5px 7px;border-bottom:1px solid var(--line)}.row{height:28px;padding:6px 8px;border-radius:3px;display:flex;gap:7px;align-items:center;cursor:pointer}.row:hover,.row.selected{background:#34373b}.row .type{margin-left:auto;color:#777;font-size:9px}.props{padding:9px 11px}.prophead{font-size:10px;font-weight:bold;color:#b8bbc0;padding-bottom:8px;border-bottom:1px solid #36383d}.group{padding:10px 0;border-bottom:1px solid #34363b}.group h4{margin:0 0 8px;font-size:10px;color:#b7bac0}.trip{display:grid;grid-template-columns:1fr 1fr 1fr;gap:5px}.input label{display:block;color:#858990;font-size:8px;margin-bottom:3px}.input input{width:100%;height:26px;background:#131417;border:1px solid #3b3e43;border-radius:3px;padding:0 5px}.addmenu{position:absolute;z-index:30;display:none;width:205px;background:#25272b;border:1px solid #4a4d53;border-radius:4px;box-shadow:0 12px 30px #0009;padding:5px}.addmenu button{display:block;width:100%;text-align:left;padding:8px;border:0;background:transparent;border-radius:3px;cursor:pointer}.addmenu button:hover{background:#3a3d42}.timeline{min-height:0;display:grid;grid-template-columns:190px 1fr;background:#191a1d;border-top:1px solid var(--line)}.tl-left{border-right:1px solid var(--line)}.tl-head,.tl-row{height:32px;padding:8px;border-bottom:1px solid #303237}.tl-head{font-size:9px;color:#90939a;font-weight:bold}.tl-row{color:#bbb}.tl-main{overflow:hidden;position:relative;background:repeating-linear-gradient(90deg,#191a1d 0,#191a1d 39px,#2b2e33 40px)}.frames{height:32px;display:flex;border-bottom:1px solid #303237;color:#777}.frames span{width:40px;flex:none;text-align:center;padding-top:8px;font-size:9px}.playhead{position:absolute;top:0;bottom:0;left:12px;width:1px;background:#e36d75;z-index:2}.footer{display:flex;align-items:center;justify-content:space-between;padding:0 9px;background:#111214;color:#73777e;border-top:1px solid var(--line);font-size:9px}@media(max-width:850px){.main{grid-template-columns:52px 1fr}.right{display:none}}
</style>
</head>
<body>
<div class="app">
<header class="top">
 <div class="brand">SMART<b>3D</b></div>
 <button id="new" title="New Scene">New</button><button id="undo" title="Undo">↶</button><button id="redo" title="Redo">↷</button>
 <button id="save" title="Save Scene">Save</button><button id="play" title="Play Animation">▶</button><button id="frame" title="Frame Selected">Frame</button>
 <div class="spacer"></div>
 <select id="modeSelect" class="mode"><option>Object Mode</option><option>Edit Mode</option></select>
</header>
<div class="main">
 <aside class="toolbar">
  <button class="tool active" id="select">↖<span>Select</span></button>
  <button class="tool" id="move">✥<span>Move</span></button>
  <button class="tool" id="rotate">↻<span>Rotate</span></button>
  <button class="tool" id="scale">⤢<span>Scale</span></button>
  <div class="divider"></div>
  <button class="tool" id="add">＋<span>Add</span></button>
  <button class="tool" id="duplicate">⧉<span>Duplicate</span></button>
  <button class="tool" id="delete">×<span>Delete</span></button>
 </aside>
 <main class="viewport">
  <div id="canvas" class="canvas"></div>
  <div class="hud help">LMB Select · G Move · R Rotate · S Scale · Shift+A Add · Delete Remove</div>
  <div class="hud view">Perspective · Grid · 3D</div>
  <div id="addMenu" class="addmenu">
   <button data-add="cube">▣ Cube</button><button data-add="sphere">● UV Sphere</button><button data-add="cylinder">⬢ Cylinder</button><button data-add="cone">△ Cone</button><button data-add="plane">▱ Plane</button><button data-add="torus">◉ Torus</button><button data-add="light">☀ Point Light</button><button data-add="camera">▣ Camera</button>
  </div>
 </main>
 <aside class="right">
  <div class="tabs"><button class="active">Scene</button><button>Properties</button></div>
  <div class="title">Scene Collection</div>
  <div id="outliner" class="outliner"></div>
  <div class="props"><div class="prophead">Object Properties</div><div id="props"></div></div>
 </aside>
</div>
<div class="timeline">
 <div class="tl-left"><div class="tl-head">TIMELINE</div><div class="tl-row">Cube</div><div class="tl-row">Camera</div><div class="tl-row">Light</div></div>
 <div class="tl-main"><div class="frames"><span>0</span><span>10</span><span>20</span><span>30</span><span>40</span><span>50</span><span>60</span><span>70</span><span>80</span><span>90</span><span>100</span></div><div id="playhead" class="playhead"></div></div>
</div>
<footer class="footer"><span>Smart3D</span><span id="status">Cube selected</span><span>Three.js WebGL</span></footer>
</div>
<script type="importmap">{"imports":{"three":"https://cdn.jsdelivr.net/npm/three@0.180.0/build/three.module.js","three/addons/":"https://cdn.jsdelivr.net/npm/three@0.180.0/examples/jsm/"}}</script>
<script type="module">
import * as THREE from 'three';
import {OrbitControls} from 'three/addons/controls/OrbitControls.js';
import {TransformControls} from 'three/addons/controls/TransformControls.js';

const host=document.getElementById('canvas');
const scene=new THREE.Scene();
scene.background=new THREE.Color(0x303238);
const camera=new THREE.PerspectiveCamera(50,1,.05,1000);
camera.position.set(7,5,8);
const renderer=new THREE.WebGLRenderer({antialias:true});
renderer.setPixelRatio(Math.min(devicePixelRatio,2));
renderer.outputColorSpace=THREE.SRGBColorSpace;
renderer.shadowMap.enabled=true;
host.appendChild(renderer.domElement);

const orbit=new OrbitControls(camera,renderer.domElement);
orbit.enableDamping=true; orbit.target.set(0,1,0);
const transform=new TransformControls(camera,renderer.domElement);
scene.add(transform.getHelper());
transform.addEventListener('dragging-changed',e=>orbit.enabled=!e.value);
transform.addEventListener('objectChange',()=>{renderUI()});

scene.add(new THREE.GridHelper(30,30,0x666a72,0x45484e));
const axes=new THREE.AxesHelper(3); scene.add(axes);
const hemi=new THREE.HemisphereLight(0xffffff,0x343434,1.7); scene.add(hemi);

const objects=[];
let selected=null;
let mode='select';
let playing=false;
let frame=0;
let history=[];
let historyIndex=-1;

function mesh(name,geometry){
 const m=new THREE.Mesh(geometry,new THREE.MeshStandardMaterial({color:0x8b8e94,roughness:.62,metalness:.04}));
 m.name=name;m.castShadow=true;m.receiveShadow=true;return m;
}
function uniqueName(base){let n=1,name=base;while(objects.some(o=>o.name===name)){n++;name=base+'.'+String(n).padStart(3,'0')}return name}
function addObject(kind,record=true){
 let o;
 if(kind==='cube'){o=mesh('Cube',new THREE.BoxGeometry(2,2,2));o.position.y=1}
 else if(kind==='sphere'){o=mesh('Sphere',new THREE.SphereGeometry(1,32,20));o.position.y=1}
 else if(kind==='cylinder'){o=mesh('Cylinder',new THREE.CylinderGeometry(1,1,2,32));o.position.y=1}
 else if(kind==='cone'){o=mesh('Cone',new THREE.ConeGeometry(1,2,32));o.position.y=1}
 else if(kind==='plane'){o=mesh('Plane',new THREE.PlaneGeometry(4,4));o.rotation.x=-Math.PI/2}
 else if(kind==='torus'){o=mesh('Torus',new THREE.TorusGeometry(1,.32,20,64));o.position.y=1}
 else if(kind==='light'){o=new THREE.PointLight(0xffffff,35,25);o.name='Point Light';o.position.set(4,5,3)}
 else if(kind==='camera'){o=new THREE.PerspectiveCamera(50,1,.1,1000);o.name='Camera';o.position.set(7,5,8)}
 if(!o)return null;
 o.name=uniqueName(o.name);
 scene.add(o);objects.push(o);selectObject(o);renderUI();if(record)pushHistory();return o;
}
function selectObject(o){
 selected=o||null;transform.detach();
 if(selected?.isMesh){transform.setMode(mode==='select'?'translate':mode);if(mode!=='select')transform.attach(selected)}
 document.getElementById('status').textContent=selected?selected.name+' selected':'Nothing selected';
 renderUI();
}
function renderUI(){
 const out=document.getElementById('outliner');
 out.innerHTML=objects.length?objects.map((o,i)=>`<div class="row ${o===selected?'selected':''}" data-i="${i}">◈ ${o.name}<span class="type">${o.type}</span></div>`).join(''):'<div style="padding:12px;color:#777">Scene is empty</div>';
 out.querySelectorAll('.row').forEach(r=>r.onclick=()=>selectObject(objects[Number(r.dataset.i)]));
 const p=document.getElementById('props');
 if(!selected){p.innerHTML='<div style="padding:14px 0;color:#777">Select an object</div>';return}
 p.innerHTML=`<div class="group"><h4>Transform</h4><div class="trip">${['x','y','z'].map(a=>`<div class="input"><label>Location ${a.toUpperCase()}</label><input data-p="${a}" type="number" step="0.1" value="${selected.position[a].toFixed(2)}"></div>`).join('')}</div><div class="trip" style="margin-top:8px">${['x','y','z'].map(a=>`<div class="input"><label>Rotation ${a.toUpperCase()}</label><input data-r="${a}" type="number" step="1" value="${THREE.MathUtils.radToDeg(selected.rotation[a]).toFixed(1)}"></div>`).join('')}</div><div class="trip" style="margin-top:8px">${['x','y','z'].map(a=>`<div class="input"><label>Scale ${a.toUpperCase()}</label><input data-s="${a}" type="number" step="0.1" value="${selected.scale[a].toFixed(2)}"></div>`).join('')}</div></div>`;
 p.querySelectorAll('[data-p]').forEach(i=>i.oninput=()=>{selected.position[i.dataset.p]=Number(i.value);renderUI()});
 p.querySelectorAll('[data-r]').forEach(i=>i.oninput=()=>{selected.rotation[i.dataset.r]=THREE.MathUtils.degToRad(Number(i.value));renderUI()});
 p.querySelectorAll('[data-s]').forEach(i=>i.oninput=()=>{selected.scale[i.dataset.s]=Number(i.value);renderUI()});
}
function snapshot(){return objects.map(o=>({name:o.name,type:o.type,pos:o.position.toArray(),rot:[o.rotation.x,o.rotation.y,o.rotation.z],scale:o.scale.toArray()}))}
function pushHistory(){history=history.slice(0,historyIndex+1);history.push(JSON.stringify(snapshot()));historyIndex=history.length-1}
function restore(data){
 for(const o of [...objects])scene.remove(o);objects.length=0;selected=null;transform.detach();
 for(const d of data){let o;if(d.type==='Mesh'){o=mesh(d.name,new THREE.BoxGeometry(2,2,2))}else if(d.type==='PointLight'){o=new THREE.PointLight(0xffffff,35,25);o.name=d.name}else if(d.type==='PerspectiveCamera'){o=new THREE.PerspectiveCamera(50,1,.1,1000);o.name=d.name}else continue;o.position.fromArray(d.pos);o.rotation.set(d.rot[0],d.rot[1],d.rot[2]);o.scale.fromArray(d.scale);scene.add(o);objects.push(o)}
 selectObject(objects[0]||null);renderUI();
}
function undo(){if(historyIndex<=0)return;historyIndex--;restore(JSON.parse(history[historyIndex]))}
function redo(){if(historyIndex>=history.length-1)return;historyIndex++;restore(JSON.parse(history[historyIndex]))}
function removeSelected(){if(!selected)return;const i=objects.indexOf(selected);if(i<0)return;scene.remove(selected);objects.splice(i,1);transform.detach();selectObject(objects[Math.max(0,i-1)]||null);pushHistory()}
function duplicateSelected(){if(!selected||!selected.isMesh)return;const g=selected.geometry.clone();const o=mesh(uniqueName(selected.name),g);o.material=selected.material.clone();o.position.copy(selected.position).add(new THREE.Vector3(1,0,1));o.rotation.copy(selected.rotation);o.scale.copy(selected.scale);scene.add(o);objects.push(o);selectObject(o);pushHistory()}
function newScene(){for(const o of [...objects])scene.remove(o);objects.length=0;transform.detach();selected=null;addObject('cube',false);addObject('light',false).position.set(4,5,3);addObject('camera',false).position.set(7,5,8);selectObject(objects[0]);pushHistory()}
function setMode(next){mode=next;document.querySelectorAll('.tool').forEach(x=>x.classList.remove('active'));const el=document.getElementById(next);if(el)el.classList.add('active');if(selected?.isMesh&&next!=='select'){transform.setMode(next==='move'?'translate':next==='rotate'?'rotate':'scale');transform.attach(selected)}else transform.detach();document.getElementById('status').textContent=selected?selected.name+' · '+next:'Nothing selected'}

for(const [id,m] of [['select','select'],['move','move'],['rotate','rotate'],['scale','scale']])document.getElementById(id).onclick=()=>setMode(m);
document.getElementById('add').onclick=e=>{const r=e.currentTarget.getBoundingClientRect();const menu=document.getElementById('addMenu');menu.style.left=(r.right+6)+'px';menu.style.top=r.top+'px';menu.style.display=menu.style.display==='block'?'none':'block'};
document.querySelectorAll('[data-add]').forEach(b=>b.onclick=()=>{addObject(b.dataset.add);document.getElementById('addMenu').style.display='none'});
document.addEventListener('click',e=>{if(e.target.id!=='add'&&!e.target.closest('#addMenu'))document.getElementById('addMenu').style.display='none'});
document.getElementById('delete').onclick=removeSelected;
document.getElementById('duplicate').onclick=duplicateSelected;
document.getElementById('new').onclick=newScene;
document.getElementById('undo').onclick=undo;
document.getElementById('redo').onclick=redo;
document.getElementById('frame').onclick=()=>{if(!selected)return;const box=new THREE.Box3().setFromObject(selected);const c=box.getCenter(new THREE.Vector3());const size=box.getSize(new THREE.Vector3());const dist=Math.max(size.x,size.y,size.z)*3+3;orbit.target.copy(c);camera.position.copy(c).add(new THREE.Vector3(dist*.7,dist*.5,dist))};
document.getElementById('save').onclick=()=>{const blob=new Blob([JSON.stringify({format:'Smart3D',version:1,objects:snapshot()},null,2)],{type:'application/json'});const a=document.createElement('a');a.href=URL.createObjectURL(blob);a.download='smart3d-scene.json';a.click();setTimeout(()=>URL.revokeObjectURL(a.href),1000)};
document.getElementById('play').onclick=()=>{playing=!playing;document.getElementById('play').textContent=playing?'⏸':'▶'};
document.getElementById('modeSelect').onchange=e=>{const v=e.target.value==='Edit Mode'?'edit':'object';document.getElementById('status').textContent=selected?(selected.name+' · '+v):v;};

const raycaster=new THREE.Raycaster(),mouse=new THREE.Vector2();
renderer.domElement.addEventListener('pointerdown',e=>{if(e.button!==0||transform.dragging)return;const r=renderer.domElement.getBoundingClientRect();mouse.x=((e.clientX-r.left)/r.width)*2-1;mouse.y=-((e.clientY-r.top)/r.height)*2+1;raycaster.setFromCamera(mouse,camera);const hits=raycaster.intersectObjects(objects.filter(o=>o.isMesh),true);if(hits.length){let o=hits[0].object;while(o.parent&&o.parent!==scene)o=o.parent;selectObject(o)}else selectObject(null)});
window.addEventListener('keydown',e=>{if(e.target.tagName==='INPUT'||e.target.tagName==='SELECT')return;const k=e.key.toLowerCase();if(k==='g'){e.preventDefault();setMode('move')}else if(k==='r'){e.preventDefault();setMode('rotate')}else if(k==='s'){e.preventDefault();setMode('scale')}else if(k==='escape'){e.preventDefault();setMode('select')}else if(k==='delete'){e.preventDefault();removeSelected()}else if(k==='d'&&e.ctrlKey){e.preventDefault();duplicateSelected()}else if(k==='z'&&e.ctrlKey&&!e.shiftKey){e.preventDefault();undo()}else if((k==='y'&&e.ctrlKey)||(k==='z'&&e.ctrlKey&&e.shiftKey)){e.preventDefault();redo()}else if(k==='a'&&e.shiftKey){e.preventDefault();const b=document.getElementById('add');b.click()}});

function resize(){const r=host.getBoundingClientRect();camera.aspect=r.width/r.height;camera.updateProjectionMatrix();renderer.setSize(r.width,r.height)}
new ResizeObserver(resize).observe(host);
newScene();resize();
let last=performance.now();
function animate(now){requestAnimationFrame(animate);const dt=(now-last)/1000;last=now;if(playing){frame=(frame+dt*24)%101;document.getElementById('playhead').style.left=(12+frame*4)+'px'}orbit.update();renderer.render(scene,camera)}
requestAnimationFrame(animate);
</script>
</body>
</html>