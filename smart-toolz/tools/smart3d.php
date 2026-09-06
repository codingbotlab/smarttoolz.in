<?php declare(strict_types=1); ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Smart3D — 3D Editor</title>
<style>
:root{--bg:#1b1c1f;--bar:#242528;--panel:#202124;--panel2:#292b2f;--line:#3a3c41;--text:#ddd;--muted:#92959b;--blue:#4f83b8;--orange:#d88a3d}
*{box-sizing:border-box}html,body{margin:0;width:100%;height:100%;overflow:hidden;background:var(--bg);color:var(--text);font:12px Arial,sans-serif}button{font:inherit;color:var(--text);background:var(--panel2);border:1px solid #41444a;border-radius:3px;cursor:pointer}button:hover{background:#35383d}.app{height:100vh;display:grid;grid-template-rows:42px 1fr 155px 25px}.top{display:flex;align-items:center;gap:5px;padding:5px 8px;background:#18191b;border-bottom:1px solid var(--line)}.brand{font-weight:800;font-size:15px;margin:0 16px 0 4px}.brand b{color:#70a8e5}.top button{height:31px;min-width:31px}.top .filebtn{padding:0 11px}.spacer{flex:1}.mode{height:31px;padding:0 10px;background:#292b2f;border:1px solid #41444a;color:#ddd}.main{min-height:0;display:grid;grid-template-columns:54px 1fr 255px}.toolbar{background:#1d1e21;border-right:1px solid var(--line);padding:7px 4px;display:flex;flex-direction:column;align-items:center;gap:5px}.tool{width:45px;height:43px;font-size:17px}.tool span{display:block;font-size:8px;color:#aaa;margin-top:3px}.tool.active{background:#3a536d;border-color:#668caf}.divider{height:1px;width:38px;background:#393b40;margin:3px 0}.viewport{position:relative;min-width:0;min-height:0;background:#303238}.canvas{position:absolute;inset:0}.hud{position:absolute;z-index:2;background:#18191ccc;border:1px solid #474a50;border-radius:3px;color:#aaa;padding:7px 9px;pointer-events:none}.help{left:9px;top:9px}.object-label{right:9px;top:9px}.right{background:#202124;border-left:1px solid var(--line);min-width:0;overflow:auto}.tabs{display:flex;height:35px;border-bottom:1px solid var(--line)}.tabs button{flex:1;border:0;border-radius:0;background:transparent;color:#999}.tabs .active{color:#fff;border-bottom:2px solid #70a8e5}.panel-title{padding:10px 11px 6px;text-transform:uppercase;font-size:9px;font-weight:bold;letter-spacing:1px;color:#8e9299}.outliner{padding:5px 7px;border-bottom:1px solid var(--line)}.row{height:27px;padding:6px 7px;border-radius:3px;display:flex;align-items:center;gap:7px}.row:hover,.row.selected{background:#34373c}.row .type{margin-left:auto;color:#777;font-size:9px}.properties{padding:9px 10px}.prop-title{font-weight:bold;color:#aaa;padding:5px 0 7px;border-bottom:1px solid #35373b}.group{border-bottom:1px solid #34363a;padding:8px 0}.group h4{margin:0 0 7px;font-size:10px;color:#b7bac0}.trip{display:grid;grid-template-columns:1fr 1fr 1fr;gap:4px}.input label{display:block;color:#858990;font-size:8px;margin-bottom:3px}.input input,.input select{width:100%;height:25px;background:#141518;color:#ddd;border:1px solid #3b3e43;border-radius:3px;padding:0 4px}.wide{grid-column:1/-1}.addmenu{position:absolute;z-index:20;display:none;background:#25272b;border:1px solid #484b50;box-shadow:0 8px 25px #0008;border-radius:4px;width:190px;padding:5px}.addmenu button{display:block;width:100%;text-align:left;border:0;background:transparent;padding:8px}.addmenu button:hover{background:#3a3d42}.timeline{min-height:0;display:grid;grid-template-columns:190px 1fr;background:#191a1d;border-top:1px solid var(--line)}.timeline-left{border-right:1px solid var(--line)}.tl-head,.tl-row{height:31px;padding:8px;border-bottom:1px solid #303237}.tl-head{color:#8d9096;font-weight:bold;font-size:9px}.timeline-main{overflow:hidden;position:relative;background:repeating-linear-gradient(90deg,#191a1d 0,#191a1d 39px,#2b2d32 40px)}.frames{height:31px;display:flex;border-bottom:1px solid #303237;color:#777}.frames span{width:40px;text-align:center;padding-top:8px;font-size:9px}.playhead{position:absolute;top:0;bottom:0;left:12px;width:1px;background:#e36b72}.footer{display:flex;align-items:center;justify-content:space-between;padding:0 9px;background:#18191b;color:#777;border-top:1px solid var(--line);font-size:9px}
@media(max-width:800px){.main{grid-template-columns:50px 1fr}.right{display:none}}
</style>
</head>
<body>
<div class="app">
<header class="top">
 <div class="brand">SMART<b>3D</b></div>
 <button id="new" title="New Scene">＋</button><button id="undo">↶</button><button id="redo">↷</button>
 <button id="save">Save</button><button id="play">▶</button><button id="frame">Frame</button>
 <div class="spacer"></div><select class="mode" id="modeSelect"><option>Object Mode</option><option>Edit Mode</option></select>
</header>
<div class="main">
 <aside class="toolbar">
  <button class="tool active" id="select">↖<span>Select</span></button>
  <button class="tool" id="move">✥<span>Move</span></button>
  <button class="tool" id="rotate">↻<span>Rotate</span></button>
  <button class="tool" id="scale">⤢<span>Scale</span></button>
  <div class="divider"></div>
  <button class="tool" id="add">＋<span>Add</span></button>
  <button class="tool" id="delete">×<span>Delete</span></button>
 </aside>
 <main class="viewport"><div id="canvas" class="canvas"></div><div class="hud help">LMB Select · M Move · R Rotate · S Scale · Wheel Zoom</div><div id="objectLabel" class="hud object-label">Cube</div><div id="addMenu" class="addmenu"><button data-add="cube">▣ Cube</button><button data-add="sphere">● UV Sphere</button><button data-add="cylinder">⬢ Cylinder</button><button data-add="cone">△ Cone</button><button data-add="plane">▱ Plane</button><button data-add="torus">◉ Torus</button><button data-add="light">☀ Point Light</button><button data-add="camera">▣ Camera</button></div></main>
 <aside class="right">
  <div class="tabs"><button class="active">Scene</button><button>Properties</button></div>
  <div class="panel-title">Scene Collection</div><div id="outliner" class="outliner"></div>
  <div class="properties"><div class="prop-title">Object Properties</div><div id="props"></div></div>
 </aside>
</div>
<div class="timeline"><div class="timeline-left"><div class="tl-head">TIMELINE</div><div class="tl-row">Cube</div><div class="tl-row">Camera</div><div class="tl-row">Light</div></div><div class="timeline-main"><div class="frames"> <span>0</span><span>10</span><span>20</span><span>30</span><span>40</span><span>50</span><span>60</span><span>70</span><span>80</span><span>90</span><span>100</span></div><div id="playhead" class="playhead"></div></div></div>
<footer class="footer"><span>Smart3D</span><span>Three.js WebGL</span><span id="status">Cube selected</span></footer>
</div>
<script type="importmap">{"imports":{"three":"https://cdn.jsdelivr.net/npm/three@0.180.0/build/three.module.js","three/addons/":"https://cdn.jsdelivr.net/npm/three@0.180.0/examples/jsm/"}}</script>
<script type="module">
import * as THREE from 'three';
import {OrbitControls} from 'three/addons/controls/OrbitControls.js';
import {TransformControls} from 'three/addons/controls/TransformControls.js';

const canvasHost=document.getElementById('canvas');
const scene=new THREE.Scene(); scene.background=new THREE.Color(0x303238);
const camera=new THREE.PerspectiveCamera(50,1,.05,1000); camera.position.set(7,5,8);
const renderer=new THREE.WebGLRenderer({antialias:true}); renderer.setPixelRatio(Math.min(devicePixelRatio,2)); renderer.outputColorSpace=THREE.SRGBColorSpace; renderer.shadowMap.enabled=true; canvasHost.appendChild(renderer.domElement);
const orbit=new OrbitControls(camera,renderer.domElement); orbit.enableDamping=true; orbit.target.set(0,1,0);
const transform=new TransformControls(camera,renderer.domElement); scene.add(transform.getHelper()); transform.addEventListener('dragging-changed',e=>orbit.enabled=!e.value);
const grid=new THREE.GridHelper(30,30,0x62666d,0x44474d); scene.add(grid);
const worldLight=new THREE.HemisphereLight(0xffffff,0x3a3a3a,1.5); scene.add(worldLight);
const objects=[]; let selected=null; let history=[]; let historyIndex=-1;
function makeMesh(name,geometry){const m=new THREE.Mesh(geometry,new THREE.MeshStandardMaterial({color:0x8b8d91,roughness:.62,metalness:.05}));m.name=name;m.castShadow=true;m.receiveShadow=true;return m}
function addObject(kind,record=true){let o;
 if(kind==='cube'){o=makeMesh('Cube',new THREE.BoxGeometry(2,2,2));o.position.y=1}
 if(kind==='sphere'){o=makeMesh('Sphere',new THREE.SphereGeometry(1,32,20));o.position.y=1}
 if(kind==='cylinder'){o=makeMesh('Cylinder',new THREE.CylinderGeometry(1,1,2,32));o.position.y=1}
 if(kind==='cone'){o=makeMesh('Cone',new THREE.ConeGeometry(1,2,32));o.position.y=1}
 if(kind==='plane'){o=makeMesh('Plane',new THREE.PlaneGeometry(4,4));o.rotation.x=-Math.PI/2}
 if(kind==='torus'){o=makeMesh('Torus',new THREE.TorusGeometry(1,.32,16,48));o.position.y=1}
 if(kind==='light'){o=new THREE.PointLight(0xffffff,40,25);o.name='Point Light';o.position.set(3,4,2)}
 if(kind==='camera'){o=new THREE.PerspectiveCamera(50,1,.1,1000);o.name='Camera';o.position.set(5,3,6)}
 if(!o)return;
 const base=o.name;let n=1;while(objects.some(x=>x.name===o.name)){n++;o.name=base+'.'+String(n).padStart(3,'0')}
 scene.add(o);objects.push(o);selectObject(o);if(record)pushHistory();renderUI();return o;
}
function removeSelected(){if(!selected)return;const i=objects.indexOf(selected);if(i<0)return;scene.remove(selected);objects.splice(i,1);transform.detach();selected=objects.find(o=>o.isMesh)||null;renderUI();pushHistory()}
function selectObject(o){selected=o;transform.detach();if(o?.isMesh){transform.attach(o)}document.getElementById('objectLabel').textContent=o?o.name:'Nothing selected';document.getElementById('status').textContent=o?o.name+' selected':'Nothing selected';renderUI()}
function renderUI(){const out=document.getElementById('outliner');out.innerHTML=objects.length?objects.map((o,i)=>`<div class="row ${o===selected?'selected':''}" data-i="${i}">◈ ${o.name}<span class="type">${o.type}</span></div>`).join(''):'<div style="padding:12px;color:#777">Scene is empty</div>';out.querySelectorAll('.row').forEach(r=>r.onclick=()=>selectObject(objects[+r.dataset.i]));
 const p=document.getElementById('props');if(!selected){p.innerHTML='<div style="padding:14px 0;color:#777">Select an object</div>';return}
 p.innerHTML=`<div class="group"><h4>Transform</h4><div class="trip">${['x','y','z'].map(a=>`<div class="input"><label>Location ${a.toUpperCase()}</label><input data-pos="${a}" type="number" step="0.1" value="${selected.position[a].toFixed(2)}"></div>`).join('')}</div><div class="trip" style="margin-top:7px">${['x','y','z'].map(a=>`<div class="input"><label>Rotation ${a.toUpperCase()}</label><input data-rot="${a}" type="number" step="1" value="${THREE.MathUtils.radToDeg(selected.rotation[a]).toFixed(1)}"></div>`).join('')}</div><div class="trip" style="margin-top:7px"><div class="input"><label>Scale X</label><input data-scale="x" type="number" step="0.1" value="${selected.scale.x.toFixed(2)}"></div><div class="input"><label>Scale Y</label><input data-scale="y" type="number" step="0.1" value="${selected.scale.y.toFixed(2)}"></div><div class="input"><label>Scale Z</label><input data-scale="z" type="number" step="0.1" value="${selected.scale.z.toFixed(2)}"></div></div></div>`;
 p.querySelectorAll('[data-pos]').forEach(i=>i.oninput=()=>selected.position[i.dataset.pos]=+i.value);p.querySelectorAll('[data-rot]').forEach(i=>i.oninput=()=>selected.rotation[i.dataset.rot]=THREE.MathUtils.degToRad(+i.value));p.querySelectorAll('[data-scale]').forEach(i=>i.oninput=()=>selected.scale[i.dataset.scale]=+i.value)
}
function pushHistory(){const snap=objects.map(o=>({name:o.name,type:o.type,position:o.position.toArray(),rotation:o.rotation.toArray(),scale:o.scale.toArray()}));history=history.slice(0,historyIndex+1);history.push(JSON.stringify(snap));historyIndex=history.length-1}
function resetScene(){for(const o of [...objects])scene.remove(o);objects.length=0;transform.detach();selected=null;addObject('cube',false);const l=addObject('light',false);l.position.set(4,5,3);const c=addObject('camera',false);c.position.set(7,5,8);pushHistory();selectObject(objects[0])}
const modes={select:'select',move:'translate',rotate:'rotate',scale:'scale'};
for(const id of Object.keys(modes)){document.getElementById(id).onclick=()=>{document.querySelectorAll('.tool').forEach(x=>x.classList.remove('active'));document.getElementById(id).classList.add('active');if(id==='select')transform.detach();else if(selected?.isMesh){transform.setMode(modes[id]);transform.attach(selected)}}}
const menu=document.getElementById('addMenu');document.getElementById('add').onclick=e=>{const b=e.currentTarget.getBoundingClientRect();menu.style.left=(b.right+6)+'px';menu.style.top=(b.top)+'px';menu.style.display=menu.style.display==='block'?'none':'block'};
document.querySelectorAll('[data-add]').forEach(b=>b.onclick=()=>{addObject(b.dataset.add);menu.style.display='none'});document.addEventListener('click',e=>{if(!menu.contains(e.target)&&e.target.id!=='add')menu.style.display='none'});
document.getElementById('delete').onclick=removeSelected;document.getElementById('new').onclick=resetScene;
document.getElementById('frame').onclick=()=>{if(!selected)return;orbit.target.copy(selected.position);camera.position.copy(selected.position).add(new THREE.Vector3(5,3,7))};
document.getElementById('save').onclick=()=>{const data={name:'Smart3D Scene',objects:objects.map(o=>({name:o.name,type:o.type,position:o.position.toArray(),rotation:o.rotation.toArray(),scale:o.scale.toArray()}))};const a=document.createElement('a');a.href=URL.createObjectURL(new Blob([JSON.stringify(data,null,2)],{type:'application/json'}));a.download='smart3d-scene.json';a.click()};
let playing=false;document.getElementById('play').onclick=()=>{playing=!playing;document.getElementById('play').textContent=playing?'⏸':'▶'};
renderer.domElement.addEventListener('pointerdown',e=>{if(e.button!==0)return;const r=renderer.domElement.getBoundingClientRect();const mouse=new THREE.Vector2((e.clientX-r.left)/r.width*2-1,-(e.clientY-r.top)/r.height*2+1);const ray=new THREE.Raycaster();ray.setFromCamera(mouse,camera);const hits=ray.intersectObjects(objects.filter(o=>o.isMesh),true);if(hits.length){let o=hits[0].object;while(o.parent&&o.parent!==scene)o=o.parent;selectObject(o)}});
window.addEventListener('keydown',e=>{if(e.target.tagName==='INPUT')return;if(e.key.toLowerCase()==='g')document.getElementById('move').click();if(e.key.toLowerCase()==='r')document.getElementById('rotate').click();if(e.key.toLowerCase()==='s')document.getElementById('scale').click();if(e.key==='Delete')removeSelected();if(e.key==='Escape'){transform.detach();document.getElementById('select').click()}});
function resize(){const r=canvasHost.getBoundingClientRect();camera.aspect=r.width/r.height;camera.updateProjectionMatrix();renderer.setSize(r.width,r.height)}new ResizeObserver(resize).observe(canvasHost);resetScene();resize();
let last=performance.now(),frame=0;(function loop(now){requestAnimationFrame(loop);const dt=(now-last)/1000;last=now;if(playing){frame+=dt*24;document.getElementById('playhead').style.left=(12+(frame%100)*4)+'px'}orbit.update();renderer.render(scene,camera)})(performance.now());
</script>
</body></html>