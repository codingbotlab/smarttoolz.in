<?php
declare(strict_types=1);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Smart3D — 3D Studio</title>
<style>
*{box-sizing:border-box}html,body{margin:0;width:100%;height:100%;overflow:hidden;background:#202124;color:#e8e8e8;font:12px Arial,sans-serif}button{font:inherit;color:#ddd;background:#292b2f;border:1px solid #3c3f44;border-radius:4px;cursor:pointer}button:hover{background:#35383d}.app{height:100vh;display:grid;grid-template-rows:44px 1fr 30px}.top{display:flex;align-items:center;gap:6px;padding:6px 8px;background:#18191c;border-bottom:1px solid #383a3f}.logo{font-size:15px;font-weight:800;margin:0 12px 0 5px}.logo b{color:#72adff}.top button{height:30px;min-width:30px}.cmd{height:30px;flex:1;max-width:480px;background:#101114;border:1px solid #3d4147;color:#ddd;padding:0 9px;border-radius:4px}.main{min-height:0;display:grid;grid-template-columns:58px 215px minmax(0,1fr) 235px}.rail{background:#1b1c20;border-right:1px solid #37393e;padding:7px 4px;display:flex;flex-direction:column;gap:6px;align-items:center}.rail button{width:46px;height:43px;font-size:18px}.rail small{display:block;font-size:8px;color:#999;margin-top:2px}.rail .active{background:#3b536d;border-color:#6595c2}.panel{background:#1e2024;overflow:auto}.left{border-right:1px solid #383a3f}.right{border-left:1px solid #383a3f}.heading{padding:10px 11px 7px;text-transform:uppercase;letter-spacing:1px;font-weight:bold;color:#92969d;font-size:9px}.section{padding:9px 10px;border-bottom:1px solid #33363b}.grid{display:grid;grid-template-columns:1fr 1fr;gap:6px}.asset{min-height:55px;padding:7px}.asset .ico{display:block;font-size:18px;margin-bottom:4px}.asset small{color:#aaa}.viewport{position:relative;background:#303236;min-width:0;min-height:0}.canvas{position:absolute;inset:0}.hint,.status{position:absolute;top:9px;background:#17181bcc;border:1px solid #494c51;border-radius:4px;padding:7px 9px;color:#aaa;pointer-events:none}.hint{left:9px}.status{right:9px}.tabs{display:flex;border-bottom:1px solid #383a3f}.tabs button{flex:1;border:0;border-radius:0;background:none;padding:10px 3px;color:#999}.tabs .on{color:#fff;border-bottom:2px solid #72adff}.tree{padding:7px}.node{padding:8px;border-radius:4px;display:flex;gap:7px}.node.sel,.node:hover{background:#33363c}.node .muted{margin-left:auto;color:#777}.inspector{padding:10px}.muted{color:#999;font-size:10px;line-height:1.5}.field{margin:8px 0}.field label{display:block;color:#999;font-size:9px;margin-bottom:4px}.trip{display:grid;grid-template-columns:1fr 1fr 1fr;gap:5px}.field input{width:100%;height:27px;background:#121316;border:1px solid #3b3e43;border-radius:4px;color:#ddd;padding:0 5px}.timeline{height:145px;background:#191b1e;border-top:1px solid #3b3d42;display:grid;grid-template-columns:180px 1fr}.tracks{border-right:1px solid #383a3f}.trackhead{height:29px;padding:8px;color:#999;border-bottom:1px solid #303238}.track{height:29px;padding:8px;border-bottom:1px solid #2c2f34}.sheet{overflow:hidden;position:relative;background:repeating-linear-gradient(90deg,#191b1e 0,#191b1e 39px,#27292e 40px)}.numbers{height:29px;border-bottom:1px solid #303238;display:flex;color:#777}.numbers span{width:40px;text-align:center;padding-top:8px}.playhead{position:absolute;left:12px;top:0;bottom:0;width:1px;background:#ef6570}.bottom{background:#18191c;border-top:1px solid #383a3f;display:flex;align-items:center;justify-content:space-between;padding:0 9px;color:#777;font-size:10px}.drop{border:1px dashed #4a4d53;border-radius:4px;padding:12px;text-align:center;color:#888;display:block}.file{display:none}@media(max-width:850px){.main{grid-template-columns:52px 170px minmax(0,1fr)}.right{display:none}.cmd{max-width:none}}
</style>
</head>
<body>
<div class="app">
<header class="top">
 <div class="logo">SMART<b>3D</b></div><button id="new" title="New scene">＋</button><button id="undo">↶</button><button id="redo">↷</button><button id="save">▣</button><input id="cmd" class="cmd" placeholder="Describe a scene with NLP..." value="">
 <button id="build">BUILD</button><button id="play">▶</button><button id="export">EXPORT</button>
</header>
<div class="main">
<nav class="rail">
 <button class="active" data-mode="select">↖<small>Select</small></button><button data-mode="move">✥<small>Move</small></button><button data-mode="rotate">↻<small>Rotate</small></button><button data-mode="scale">⤢<small>Scale</small></button><button data-mode="model">◇<small>Model</small></button><button data-mode="animate">●<small>Animate</small></button><button data-mode="camera">▣<small>Camera</small></button><button data-mode="world">☼<small>World</small></button>
</nav>
<aside class="panel left">
 <div class="heading">Smart3D Library</div>
 <div class="section"><div class="heading">Characters</div><div class="grid"><button class="asset" data-add="man"><span class="ico">👨</span><small>Man</small></button><button class="asset" data-add="woman"><span class="ico">👩</span><small>Woman</small></button><button class="asset" data-add="child"><span class="ico">🧒</span><small>Child</small></button><button class="asset" data-add="character"><span class="ico">🧍</span><small>Character</small></button></div></div>
 <div class="section"><div class="heading">Animals</div><div class="grid"><button class="asset" data-add="dog"><span class="ico">🐕</span><small>Dog</small></button><button class="asset" data-add="cat"><span class="ico">🐈</span><small>Cat</small></button><button class="asset" data-add="horse"><span class="ico">🐎</span><small>Horse</small></button><button class="asset" data-add="bird"><span class="ico">🦅</span><small>Bird</small></button></div></div>
 <div class="section"><div class="heading">Nature</div><div class="grid"><button class="asset" data-add="tree"><span class="ico">🌳</span><small>Tree</small></button><button class="asset" data-add="pine"><span class="ico">🌲</span><small>Pine</small></button><button class="asset" data-add="rock"><span class="ico">🪨</span><small>Rock</small></button><button class="asset" data-add="mountain"><span class="ico">🏔️</span><small>Mountain</small></button></div></div>
 <div class="section"><div class="heading">Props</div><div class="grid"><button class="asset" data-add="car"><span class="ico">🚗</span><small>Car</small></button><button class="asset" data-add="house"><span class="ico">🏠</span><small>House</small></button><button class="asset" data-add="chair"><span class="ico">🪑</span><small>Chair</small></button><button class="asset" data-add="tent"><span class="ico">⛺</span><small>Tent</small></button></div></div>
 <div class="section"><div class="heading">Import</div><label class="drop">GLB / GLTF<input id="modelFile" class="file" type="file" accept=".glb,.gltf"></label><br><label class="drop">HDR / EXR<input id="hdrFile" class="file" type="file" accept=".hdr,.exr"></label></div>
</aside>
<main class="viewport"><div id="canvas" class="canvas"></div><div class="hint">LMB select · MMB orbit · wheel zoom · RMB pan</div><div id="status" class="status">Cube · 3 objects</div></main>
<aside class="panel right">
 <div class="tabs"><button class="on">SCENE</button><button>OBJECT</button><button>WORLD</button></div><div class="tree" id="tree"></div>
 <div class="section"><div class="heading">Inspector</div><div id="inspector" class="inspector muted">Select an object</div></div>
 <div class="section"><div class="heading">Transform</div><div id="transform" class="muted">Select an object</div></div>
 <div class="section"><div class="heading">Library</div><div class="muted">Characters · Animals · Nature · Props · HDRI</div></div>
 <div class="section"><button id="delete" style="width:100%;height:30px;color:#ef8c94">Delete Selected</button></div>
</aside>
</div>
<div class="timeline"><div class="tracks"><div class="trackhead">TIMELINE</div><div class="track">Camera</div><div class="track">Cube</div><div class="track">Light</div></div><div class="sheet"><div class="numbers">${Array.from({length:20},(_,i)=>`<span>${i*10}</span>`).join('')}</div><div class="playhead" id="playhead"></div></div></div>
<footer class="bottom"><span>Smart3D · Prisma-style workspace</span><span id="mode">SELECT</span><span>3D Scene</span></footer>
</div>
<script type="importmap">{"imports":{"three":"https://cdn.jsdelivr.net/npm/three@0.180.0/build/three.module.js","three/addons/":"https://cdn.jsdelivr.net/npm/three@0.180.0/examples/jsm/"}}</script>
<script type="module">
import * as THREE from 'three';
import {OrbitControls} from 'three/addons/controls/OrbitControls.js';
import {TransformControls} from 'three/addons/controls/TransformControls.js';
import {GLTFLoader} from 'three/addons/loaders/GLTFLoader.js';
import {RGBELoader} from 'three/addons/loaders/RGBELoader.js';
const $=id=>document.getElementById(id), host=$('canvas');
const scene=new THREE.Scene();scene.background=new THREE.Color(0x303236);
const camera=new THREE.PerspectiveCamera(50,1,.05,1000);camera.position.set(7,5,9);
const renderer=new THREE.WebGLRenderer({antialias:true});renderer.setPixelRatio(Math.min(devicePixelRatio,2));renderer.outputColorSpace=THREE.SRGBColorSpace;renderer.shadowMap.enabled=true;host.appendChild(renderer.domElement);
const orbit=new OrbitControls(camera,renderer.domElement);orbit.enableDamping=true;orbit.target.set(0,1,0);
const tc=new TransformControls(camera,renderer.domElement);scene.add(tc.getHelper());tc.addEventListener('dragging-changed',e=>orbit.enabled=!e.value);
const grid=new THREE.GridHelper(40,40,0x686b70,0x44474c);scene.add(grid);
const cube=new THREE.Mesh(new THREE.BoxGeometry(2,2,2),new THREE.MeshStandardMaterial({color:0x9a9da2,roughness:.72}));cube.name='Cube';cube.position.y=1;cube.userData.editable=true;cube.castShadow=cube.receiveShadow=true;scene.add(cube);
const light=new THREE.DirectionalLight(0xffffff,4);light.name='Light';light.position.set(4,7,4);light.castShadow=true;scene.add(light);
const cameraObj=new THREE.Group();cameraObj.name='Camera';cameraObj.userData.editable=true;cameraObj.position.copy(camera.position);scene.add(cameraObj);
const cameraBody=new THREE.Mesh(new THREE.BoxGeometry(.9,.55,.55),new THREE.MeshStandardMaterial({color:0x25272a}));cameraBody.position.set(0,0,0);cameraObj.add(cameraBody);const lens=new THREE.Mesh(new THREE.CylinderGeometry(.22,.22,.35,20),new THREE.MeshStandardMaterial({color:0x111214,metalness:.4}));lens.rotation.z=Math.PI/2;lens.position.x=-.55;cameraObj.add(lens);
let selected=cube;tc.attach(cube);let objects=[cube,light,cameraObj];
function esc(s){return s.replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c]))}
function refresh(){ $('status').textContent=(selected?.name||'Scene')+' · '+objects.length+' objects';$('tree').innerHTML=objects.map((o,i)=>`<div class="node ${o===selected?'sel':''}" data-i="${i}">◈ ${esc(o.name)}<span class="muted">${o.type==='Group'?'Camera':o.type==='DirectionalLight'?'Light':'Mesh'}</span></div>`).join('');document.querySelectorAll('.node').forEach(n=>n.onclick=()=>pick(objects[+n.dataset.i]));
if(!selected){$('inspector').textContent='Select an object';$('transform').textContent='Select an object';return} $('inspector').innerHTML=`<b>${esc(selected.name)}</b><br><span class="muted">${selected.type}</span>`;$('transform').innerHTML=['x','y','z'].map(a=>`<div class="field"><label>Position ${a.toUpperCase()}</label><input data-p="${a}" type="number" step=".1" value="${selected.position[a].toFixed(2)}"></div>`).join('')+`<div class="field"><label>Rotation Y</label><input id="ry" type="number" step="1" value="${THREE.MathUtils.radToDeg(selected.rotation.y).toFixed(1)}"></div>`;$('transform').querySelectorAll('[data-p]').forEach(i=>i.oninput=()=>selected.position[i.dataset.p]=+i.value);$('ry').oninput=e=>selected.rotation.y=THREE.MathUtils.degToRad(+e.target.value)}
function pick(o){selected=o;tc.attach(o);refresh()}
renderer.domElement.addEventListener('pointerdown',e=>{if(e.button!==0)return;const r=renderer.domElement.getBoundingClientRect(),v=new THREE.Vector2((e.clientX-r.left)/r.width*2-1,-(e.clientY-r.top)/r.height*2+1),ray=new THREE.Raycaster();ray.setFromCamera(v,camera);const hit=ray.intersectObjects(objects,true)[0];if(hit){let o=hit.object;while(o.parent&&o.parent!==scene)o=o.parent;if(objects.includes(o))pick(o)}});
document.querySelectorAll('[data-mode]').forEach(b=>b.onclick=()=>{const m=b.dataset.mode;document.querySelectorAll('[data-mode]').forEach(x=>x.classList.remove('active'));b.classList.add('active');$('mode').textContent=m.toUpperCase();if(['move','rotate','scale'].includes(m)){tc.setMode(m==='move'?'translate':m);if(selected)tc.attach(selected)}else tc.detach()});
$('delete').onclick=()=>{if(selected&&selected!==cube&&selected!==light&&selected!==cameraObj){scene.remove(selected);objects=objects.filter(x=>x!==selected);selected=cube;tc.attach(cube);refresh()}};
$('new').onclick=()=>{location.reload()};$('save').onclick=()=>{const data={scene:'Smart3D',objects:objects.map(o=>({name:o.name,type:o.type,position:o.position.toArray(),rotation:o.rotation.toArray(),scale:o.scale.toArray()}))};const a=document.createElement('a');a.href=URL.createObjectURL(new Blob([JSON.stringify(data,null,2)],{type:'application/json'}));a.download='smart3d-scene.json';a.click()};
$('export').onclick=()=>alert('Export pipeline ready for the next build: GLB/GLTF + movie render.');$('play').onclick=()=>alert('Animation timeline will be enabled in the animation stage.');
async function importModel(file){new GLTFLoader().load(URL.createObjectURL(file),g=>{const o=g.scene;o.name=file.name.replace(/\.(glb|gltf)$/i,'');o.userData.editable=true;o.traverse(x=>{if(x.isMesh){x.castShadow=x.receiveShadow=true}});scene.add(o);objects.push(o);pick(o)},undefined,e=>alert('GLB/GLTF load failed: '+e.message))}
$('modelFile').onchange=e=>{if(e.target.files[0])importModel(e.target.files[0])};$('hdrFile').onchange=e=>{const f=e.target.files[0];if(!f)return;new RGBELoader().load(URL.createObjectURL(f),t=>{t.mapping=THREE.EquirectangularReflectionMapping;scene.environment=t;scene.background=t})};
function makeSimple(name){const g=new THREE.Group();g.name=name;g.userData.editable=true;let m=new THREE.MeshStandardMaterial({color:0x8b8e93,roughness:.8});let geo=name==='Tree'?new THREE.ConeGeometry(1.2,3,16):name==='Rock'?new THREE.DodecahedronGeometry(1):new THREE.BoxGeometry(1.5,1.5,1.5);let x=new THREE.Mesh(geo,m);x.position.y=name==='Tree'?1.5:.75;x.castShadow=x.receiveShadow=true;g.add(x);scene.add(g);objects.push(g);g.position.set((objects.length%4)*2-4,0,-2);pick(g)}
document.querySelectorAll('[data-add]').forEach(b=>b.onclick=()=>makeSimple(b.dataset.add[0].toUpperCase()+b.dataset.add.slice(1)));
$('build').onclick=()=>{const s=$('cmd').value.toLowerCase();if(!s.trim())return;const names=[['man','Man'],['woman','Woman'],['child','Child'],['dog','Dog'],['cat','Cat'],['horse','Horse'],['tree','Tree'],['pine','Pine'],['rock','Rock'],['mountain','Mountain'],['car','Car'],['house','House'],['chair','Chair'],['tent','Tent']];names.filter(([k])=>s.includes(k)).forEach(([,n])=>makeSimple(n));};
function resize(){const r=host.getBoundingClientRect();camera.aspect=r.width/r.height;camera.updateProjectionMatrix();renderer.setSize(r.width,r.height)}new ResizeObserver(resize).observe(host);resize();refresh();
(function loop(){requestAnimationFrame(loop);orbit.update();renderer.render(scene,camera)})();
</script>
</body>
</html>