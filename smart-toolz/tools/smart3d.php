<?php
declare(strict_types=1);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Smart3D — 3D Scene & Movie Editor</title>
<meta name="description" content="Smart3D browser-based 3D scene editor for characters, animals, props, nature, locations and HDRI environments.">
<style>
:root{--bg:#0a0d12;--panel:#10151d;--panel2:#151b24;--line:#27303d;--text:#e9eef6;--muted:#8995a7;--accent:#6da7ff;--accent2:#9b7cff;--danger:#e86d7a}*{box-sizing:border-box}html,body{height:100%;margin:0;overflow:hidden;background:var(--bg);color:var(--text);font:12px Inter,Arial,sans-serif}button,input,select{font:inherit}button{cursor:pointer}.app{height:100vh;display:grid;grid-template-rows:48px 1fr 30px}.top{display:flex;align-items:center;gap:7px;padding:7px 9px;background:#0d1118;border-bottom:1px solid var(--line)}.brand{font-weight:900;font-size:15px;letter-spacing:.2px;margin:0 8px}.brand b{color:var(--accent)}.iconbtn{height:32px;min-width:32px;padding:0 8px;background:#151b24;border:1px solid #2c3645;color:#dbe4f1;border-radius:6px}.iconbtn:hover{background:#202a39}.sep{width:1px;height:22px;background:var(--line);margin:0 3px}.command{flex:1;max-width:550px;height:32px;background:#090d13;border:1px solid #303b4d;color:#eef4fc;border-radius:7px;padding:0 11px;outline:none}.command:focus{border-color:#557bb1}.main{min-height:0;display:grid;grid-template-columns:210px minmax(0,1fr) 245px}.left,.right{background:var(--panel);min-width:0;overflow:auto}.left{border-right:1px solid var(--line)}.right{border-left:1px solid var(--line)}.section{padding:12px;border-bottom:1px solid var(--line)}.label{font-size:10px;text-transform:uppercase;letter-spacing:1px;color:var(--muted);font-weight:800;margin-bottom:9px}.assetgrid{display:grid;grid-template-columns:1fr 1fr;gap:6px}.asset{min-height:58px;border:1px solid #293443;background:var(--panel2);border-radius:7px;color:#dfe7f3;text-align:left;padding:8px}.asset:hover{border-color:#486384;background:#1a2330}.asset strong{display:block;font-size:18px;margin-bottom:4px}.asset span{font-size:10px;color:var(--muted)}.location{width:100%;padding:8px 9px;margin:3px 0;background:var(--panel2);border:1px solid #293443;border-radius:6px;color:#dfe7f3;text-align:left}.location:hover{border-color:#486384}.canvaswrap{position:relative;min-width:0;min-height:0;background:#171b22}.viewport{position:absolute;inset:0}.overlay{position:absolute;pointer-events:none}.hint{left:12px;top:10px;background:#090d13cc;border:1px solid #2d3746;padding:7px 9px;border-radius:6px;color:#b9c5d5}.stats{right:10px;top:10px;background:#090d13cc;border:1px solid #2d3746;padding:7px 9px;border-radius:6px;color:#aeb9c8}.bottom{background:#0d1118;border-top:1px solid var(--line);display:flex;align-items:center;justify-content:space-between;padding:0 10px;color:var(--muted);font-size:10px}.tabs{display:flex;border-bottom:1px solid var(--line)}.tab{flex:1;padding:9px 5px;text-align:center;color:var(--muted);background:transparent;border:0;border-bottom:2px solid transparent}.tab.active{color:#fff;border-bottom-color:var(--accent)}.tree{padding:10px}.node{display:flex;align-items:center;gap:7px;padding:7px 6px;border-radius:5px}.node:hover,.node.selected{background:#1b2533}.node small{color:var(--muted);margin-left:auto}.row{display:grid;grid-template-columns:1fr 1fr;gap:7px;margin:7px 0}.field label{display:block;color:var(--muted);font-size:9px;margin-bottom:4px}.field input,.field select{width:100%;height:28px;background:#090d13;border:1px solid #2c3746;border-radius:5px;color:#e7edf6;padding:0 6px}.bigbtn{width:100%;height:31px;background:#1a2637;border:1px solid #385170;color:#dfeaff;border-radius:6px;font-weight:700}.primary{background:#315f99;border-color:#5d8ac0}.danger{color:#ffabb3}.empty{color:var(--muted);text-align:center;padding:20px 8px}.drop{border:1px dashed #405067;border-radius:7px;padding:14px 8px;text-align:center;color:var(--muted);margin-top:7px}.file{display:none}.pill{display:inline-block;padding:3px 6px;border:1px solid #354256;border-radius:10px;color:#9eadc0;font-size:9px;margin:2px}.note{font-size:10px;color:var(--muted);line-height:1.5}.wide{grid-column:1/-1}
@media(max-width:850px){.main{grid-template-columns:165px minmax(0,1fr)}.right{display:none}.brand{font-size:13px}.command{max-width:none}}
</style>
</head>
<body>
<div class="app">
<header class="top">
  <div class="brand">SMART<b>3D</b></div>
  <button class="iconbtn" id="home" title="Reset view">⌂</button>
  <button class="iconbtn" id="focus" title="Focus selected">◎</button>
  <div class="sep"></div>
  <button class="iconbtn" id="select" title="Select">↖</button>
  <button class="iconbtn" id="move" title="Move">✥</button>
  <button class="iconbtn" id="rotate" title="Rotate">↻</button>
  <button class="iconbtn" id="scale" title="Scale">⤢</button>
  <div class="sep"></div>
  <input class="command" id="command" value="mountain forest with a man, dog and trees" aria-label="Scene command">
  <button class="iconbtn primary" id="build">Build</button>
  <button class="iconbtn" id="save">Save</button>
</header>
<div class="main">
<aside class="left">
  <div class="section"><div class="label">Characters</div><div class="assetgrid">
    <button class="asset" data-add="man"><strong>👨</strong><span>Man</span></button><button class="asset" data-add="woman"><strong>👩</strong><span>Woman</span></button>
    <button class="asset" data-add="child"><strong>🧒</strong><span>Child</span></button><button class="asset" data-add="character"><strong>🧍</strong><span>Character</span></button>
  </div></div>
  <div class="section"><div class="label">Animals</div><div class="assetgrid">
    <button class="asset" data-add="dog"><strong>🐕</strong><span>Dog</span></button><button class="asset" data-add="cat"><strong>🐈</strong><span>Cat</span></button>
    <button class="asset" data-add="horse"><strong>🐎</strong><span>Horse</span></button><button class="asset" data-add="bird"><strong>🦅</strong><span>Bird</span></button>
  </div></div>
  <div class="section"><div class="label">Nature</div><div class="assetgrid">
    <button class="asset" data-add="tree"><strong>🌳</strong><span>Tree</span></button><button class="asset" data-add="pine"><strong>🌲</strong><span>Pine</span></button>
    <button class="asset" data-add="rock"><strong>🪨</strong><span>Rock</span></button><button class="asset" data-add="mountain"><strong>🏔️</strong><span>Mountain</span></button>
  </div></div>
  <div class="section"><div class="label">Props</div><div class="assetgrid">
    <button class="asset" data-add="car"><strong>🚗</strong><span>Car</span></button><button class="asset" data-add="house"><strong>🏠</strong><span>House</span></button>
    <button class="asset" data-add="chair"><strong>🪑</strong><span>Chair</span></button><button class="asset" data-add="camp"><strong>⛺</strong><span>Camp</span></button>
  </div></div>
  <div class="section"><div class="label">Locations</div>
    <button class="location" data-location="mountain">🏔️ Mountain</button><button class="location" data-location="forest">🌲 Forest</button><button class="location" data-location="beach">🏖️ Beach</button><button class="location" data-location="desert">🏜️ Desert</button><button class="location" data-location="village">🏡 Village</button><button class="location" data-location="city">🏙️ City</button>
  </div>
  <div class="section"><div class="label">Real Assets</div><label class="bigbtn" style="display:block;text-align:center;padding-top:8px">Import GLB / GLTF<input class="file" id="modelFile" type="file" accept=".glb,.gltf"></label><div class="drop">Drop a 3D model here<br><span class="note">Local files only</span></div></div>
  <div class="section"><div class="label">Environment</div><label class="bigbtn" style="display:block;text-align:center;padding-top:8px">Load HDR / EXR<input class="file" id="hdrFile" type="file" accept=".hdr,.exr"></label><div class="note" style="margin-top:7px">Use free HDRIs from Poly Haven or ambientCG. Keep downloaded assets in your project assets folder for Git deployments.</div></div>
</aside>
<main class="canvaswrap"><div id="viewport" class="viewport"></div><div class="overlay hint">LMB select · drag orbit · wheel zoom · RMB pan</div><div id="stats" class="overlay stats">0 objects</div></main>
<aside class="right">
  <div class="tabs"><button class="tab active">Scene</button><button class="tab">Object</button><button class="tab">World</button></div>
  <div class="tree" id="sceneTree"></div>
  <div class="section"><div class="label">Transform</div><div id="transform" class="empty">Select an object</div></div>
  <div class="section"><div class="label">World</div><select id="environment" style="width:100%;height:31px;background:#090d13;border:1px solid #2c3746;color:#e7edf6;border-radius:6px;padding:0 7px"><option value="studio">Studio</option><option value="day">Daylight</option><option value="sunset">Sunset</option><option value="night">Night</option></select></div>
  <div class="section"><div class="label">Scene Data</div><div id="sceneData" class="note"></div></div>
  <div class="section"><button class="bigbtn danger" id="delete">Delete Selected</button></div>
</aside>
</div>
<footer class="bottom"><span>Smart3D · Real-world scene foundation</span><span id="mode">SELECT</span><span>WebGL</span></footer>
</div>
<script type="importmap">{"imports":{"three":"https://cdn.jsdelivr.net/npm/three@0.180.0/build/three.module.js","three/addons/":"https://cdn.jsdelivr.net/npm/three@0.180.0/examples/jsm/"}}</script>
<script type="module">
import * as THREE from 'three';
import {OrbitControls} from 'three/addons/controls/OrbitControls.js';
import {TransformControls} from 'three/addons/controls/TransformControls.js';
import {GLTFLoader} from 'three/addons/loaders/GLTFLoader.js';
import {RGBELoader} from 'three/addons/loaders/RGBELoader.js';

const host=document.getElementById('viewport'), scene=new THREE.Scene();
scene.background=new THREE.Color(0x1b2028);
const camera=new THREE.PerspectiveCamera(48,1,.05,1000); camera.position.set(12,9,16);
const renderer=new THREE.WebGLRenderer({antialias:true}); renderer.setPixelRatio(Math.min(devicePixelRatio,2)); renderer.outputColorSpace=THREE.SRGBColorSpace; renderer.shadowMap.enabled=true; renderer.shadowMap.type=THREE.PCFSoftShadowMap; host.appendChild(renderer.domElement);
const orbit=new OrbitControls(camera,renderer.domElement); orbit.enableDamping=true; orbit.target.set(0,2,0);
const transform=new TransformControls(camera,renderer.domElement); scene.add(transform.getHelper()); transform.addEventListener('dragging-changed',e=>orbit.enabled=!e.value); transform.addEventListener('objectChange',updateUI); scene.add(new THREE.GridHelper(60,60,0x35404e,0x202732));
const hemi=new THREE.HemisphereLight(0xd8e7ff,0x39432f,2.2); scene.add(hemi); const sun=new THREE.DirectionalLight(0xffffff,3.5); sun.position.set(12,22,8); sun.castShadow=true; sun.shadow.mapSize.set(1024,1024); scene.add(sun);
const root=new THREE.Group(); root.name='World'; scene.add(root); let selected=null, seq=1;
const objects=[];
const mat=c=>new THREE.MeshStandardMaterial({color:c,roughness:.82,metalness:.02});
function add(o,name){o.name=name||('Object '+seq++);o.userData.editable=true;root.add(o);objects.push(o);select(o);updateUI();return o}
function mesh(g,geo,m,p=[0,0,0],s=[1,1,1]){const x=new THREE.Mesh(geo,m);x.position.set(...p);x.scale.set(...s);x.castShadow=true;x.receiveShadow=true;g.add(x);return x}
function human(kind='man'){const g=new THREE.Group(), body=mat(kind==='woman'?0x7f4c9c:kind==='child'?0xd28b42:0x356fa8), skin=mat(0xb97855);mesh(g,new THREE.CapsuleGeometry(.58,1.45,7,16),body,[0,2.65,0]);mesh(g,new THREE.SphereGeometry(.48,24,16),skin,[0,4.15,0]);for(const x of[-.7,.7]){mesh(g,new THREE.CapsuleGeometry(.14,1.25,6,10),body,[x,2.7,0]);mesh(g,new THREE.CapsuleGeometry(.17,1.55,6,10),mat(0x2b3038),[x*.5,1.0,0])}return add(g,kind[0].toUpperCase()+kind.slice(1))}
function animal(kind){const g=new THREE.Group(), f=mat(kind==='dog'?0x865b3a:kind==='cat'?0xaaa29a:0x8e6b4d);mesh(g,new THREE.SphereGeometry(1,20,14),f,[0,1.15,0],[1.35,.75,.75]);mesh(g,new THREE.SphereGeometry(.55,20,14),f,[0,1.9,-.9]);for(const x of[-.65,.65])for(const z of[-.35,.35])mesh(g,new THREE.CapsuleGeometry(.12,.7,5,8),f,[x,.55,z]);return add(g,kind[0].toUpperCase()+kind.slice(1))}
function tree(kind='tree'){const g=new THREE.Group();mesh(g,new THREE.CylinderGeometry(.28,.48,3.2,14),mat(0x6c4931),[0,1.6,0]);mesh(g,kind==='pine'?new THREE.ConeGeometry(1.5,4,18):new THREE.SphereGeometry(1.65,20,14),mat(kind==='pine'?0x285d39:0x367442),[0,4,0]);return add(g,kind==='pine'?'Pine':'Tree')}
function rock(){const g=new THREE.Group();mesh(g,new THREE.DodecahedronGeometry(1.25,1),mat(0x777873),[0,1,0],[1.5,.8,1]);return add(g,'Rock')}
function mountain(){const g=new THREE.Group();[[-7,8,-8],[-2,11,-10],[4,9,-8],[9,7,-11]].forEach(([x,h,z])=>{const m=mesh(g,new THREE.ConeGeometry(5,h,7),mat(0x58636a),[x,h/2,z]);mesh(g,new THREE.ConeGeometry(1.8,2.5,7),mat(0xe2e8eb),[x,h-1.1,z])});return add(g,'Mountain Range')}
function car(){const g=new THREE.Group();mesh(g,new THREE.BoxGeometry(4.3,.8,1.8),mat(0x9b3438),[0,1,0]);mesh(g,new THREE.BoxGeometry(2,.75,1.5),mat(0x202b36),[.25,1.7,0]);for(const x of[-1.35,1.35])for(const z of[-1,1])mesh(g,new THREE.CylinderGeometry(.45,.45,.28,24),mat(0x16191d),[x,.52,z]);return add(g,'Car')}
function house(){const g=new THREE.Group();mesh(g,new THREE.BoxGeometry(5,3.3,4),mat(0xc3a47c),[0,1.65,0]);const r=mesh(g,new THREE.ConeGeometry(3.5,2.2,4),mat(0x684037),[0,4.4,0]);r.rotation.y=Math.PI/4;return add(g,'House')}
function chair(){const g=new THREE.Group();for(const x of[-.45,.45])for(const z of[-.45,.45])mesh(g,new THREE.BoxGeometry(.16,1.5,.16),mat(0x7c573d),[x,.75,z]);mesh(g,new THREE.BoxGeometry(1.1,.18,1.1),mat(0x7c573d),[0,1.5,0]);mesh(g,new THREE.BoxGeometry(1.1,1.25,.18),mat(0x7c573d),[0,2.1,.46]);return add(g,'Chair')}
function camp(){const g=new THREE.Group();mesh(g,new THREE.ConeGeometry(1.8,2.4,4),mat(0xb6a06c),[0,1.2,0]);return add(g,'Tent')}
function ground(color=0x465348){const g=new THREE.Group();mesh(g,new THREE.PlaneGeometry(70,70),mat(color),[0,0,0]);return g}
function clear(){for(const o of [...objects])root.remove(o);objects.length=0;selected=null;transform.detach();updateUI()}
function location(kind){clear();const colors={mountain:0x4b594b,forest:0x3b6a42,beach:0xc6b276,desert:0xc28f62,village:0x65795e,city:0x4a4e55};root.add(ground(colors[kind]||0x465348));if(kind==='mountain')mountain();if(kind==='forest'){for(let x=-12;x<=12;x+=4)for(let z=-10;z<=2;z+=5){const t=tree(Math.random()>.7?'pine':'tree');t.position.set(x+(Math.random()-.5)*2,0,z)}}if(kind==='village'){for(let x=-8;x<=8;x+=5){const h=house();h.position.set(x,0,-5)}}if(kind==='beach'){const w=new THREE.Mesh(new THREE.PlaneGeometry(70,30),new THREE.MeshStandardMaterial({color:0x3d7892,roughness:.2}));w.rotation.x=-Math.PI/2;w.position.set(0,.03,-18);root.add(w)};document.getElementById('sceneData').textContent='Location: '+kind+' · objects '+objects.length;updateUI()}
const makers={man:()=>human('man'),woman:()=>human('woman'),child:()=>human('child'),character:()=>human('man'),dog:()=>animal('dog'),cat:()=>animal('cat'),horse:()=>animal('horse'),bird:()=>animal('bird'),tree:()=>tree('tree'),pine:()=>tree('pine'),rock, mountain,car,house,chair,camp};
document.querySelectorAll('[data-add]').forEach(b=>b.onclick=()=>{const o=makers[b.dataset.add]?.();if(o){o.position.x=(objects.length%5)*2.7-5.4;o.position.z=-Math.floor(objects.length/5)*3;updateUI()}});
document.querySelectorAll('[data-location]').forEach(b=>b.onclick=()=>location(b.dataset.location));
function select(o){selected=o;transform.attach(o);updateUI()}
renderer.domElement.addEventListener('pointerdown',e=>{if(e.button!==0)return;const r=renderer.domElement.getBoundingClientRect(),m=new THREE.Vector2((e.clientX-r.left)/r.width*2-1,-(e.clientY-r.top)/r.height*2+1),ray=new THREE.Raycaster();ray.setFromCamera(m,camera);const hits=ray.intersectObjects(root.children,true);for(const h of hits){let o=h.object;while(o.parent&&o.parent!==root)o=o.parent;if(o.userData.editable){select(o);break}}});
function updateUI(){document.getElementById('stats').textContent=objects.length+' objects';const treeEl=document.getElementById('sceneTree');treeEl.innerHTML=objects.length?objects.map(o=>`<div class="node ${o===selected?'selected':''}" data-id="${objects.indexOf(o)}">◈ ${escapeHtml(o.name)} <small>3D</small></div>`).join(''):'<div class="empty">Scene is empty</div>';treeEl.querySelectorAll('.node').forEach(n=>n.onclick=()=>select(objects[+n.dataset.id]));const t=document.getElementById('transform');if(!selected){t.className='empty';t.textContent='Select an object';return}t.className='';t.innerHTML=['x','y','z'].map(a=>`<div class="field"><label>Position ${a.toUpperCase()}</label><input data-p="${a}" type="number" step="0.1" value="${selected.position[a].toFixed(2)}"></div>`).join('')+`<div class="row"><div class="field"><label>Rotation Y</label><input data-r="y" type="number" step="1" value="${THREE.MathUtils.radToDeg(selected.rotation.y).toFixed(1)}"></div><div class="field"><label>Scale</label><input data-s="x" type="number" step="0.1" value="${selected.scale.x.toFixed(2)}"></div></div>`;t.querySelectorAll('[data-p]').forEach(i=>i.oninput=()=>{selected.position[i.dataset.p]=+i.value});t.querySelector('[data-r]').oninput=e=>selected.rotation.y=THREE.MathUtils.degToRad(+e.target.value);t.querySelector('[data-s]').oninput=e=>selected.scale.setScalar(+e.target.value)}
function escapeHtml(s){return s.replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[c]))}
document.getElementById('delete').onclick=()=>{if(selected){root.remove(selected);objects.splice(objects.indexOf(selected),1);selected=null;transform.detach();updateUI()}};
['select','move','rotate','scale'].forEach(id=>document.getElementById(id).onclick=()=>{const mode={select:null,move:'translate',rotate:'rotate',scale:'scale'}[id];if(mode)transform.setMode(mode);document.getElementById('mode').textContent=id.toUpperCase()});
document.getElementById('home').onclick=()=>{camera.position.set(12,9,16);orbit.target.set(0,2,0)};document.getElementById('focus').onclick=()=>{if(selected){orbit.target.copy(selected.position);camera.position.copy(selected.position).add(new THREE.Vector3(6,4,8))}};
document.getElementById('environment').onchange=e=>{const v=e.target.value;if(v==='day'){scene.background=new THREE.Color(0x8ea9bf);hemi.intensity=2.5;sun.intensity=4}else if(v==='sunset'){scene.background=new THREE.Color(0xb77b62);hemi.intensity=1.6;sun.intensity=2.4}else if(v==='night'){scene.background=new THREE.Color(0x090f1d);hemi.intensity=.45;sun.intensity=.25}else{scene.background=new THREE.Color(0x1b2028);hemi.intensity=2.2;sun.intensity=3.5}};
async function importModel(file){const url=URL.createObjectURL(file);new GLTFLoader().load(url,g=>{const o=g.scene;o.traverse(x=>{if(x.isMesh){x.castShadow=true;x.receiveShadow=true}});add(o,file.name.replace(/\.(glb|gltf)$/i,''));},undefined,err=>alert('Could not load model: '+err.message))}
document.getElementById('modelFile').onchange=e=>{if(e.target.files[0])importModel(e.target.files[0])};
document.getElementById('hdrFile').onchange=e=>{const f=e.target.files[0];if(!f)return;new RGBELoader().load(URL.createObjectURL(f),tex=>{tex.mapping=THREE.EquirectangularReflectionMapping;scene.environment=tex;scene.background=tex;document.getElementById('sceneData').textContent='HDRI: '+f.name+' · objects '+objects.length})};
function parseCommand(){const s=document.getElementById('command').value.toLowerCase();const loc=['mountain','forest','beach','desert','village','city'].find(x=>s.includes(x))||'mountain';location(loc);const found=Object.keys(makers).filter(x=>new RegExp('\\b'+x+'\\b').test(s));(found.length?found:['man','dog','tree']).forEach((x,i)=>{const o=makers[x]();o.position.set((i%4)*3.2-4.8,0,Math.floor(i/4)*-3)});updateUI()}
document.getElementById('build').onclick=parseCommand;
document.getElementById('save').onclick=()=>{const data={name:'Smart3D Scene',location:document.getElementById('command').value,objects:objects.map(o=>({name:o.name,position:o.position.toArray(),rotation:o.rotation.toArray(),scale:o.scale.toArray()}))};const a=document.createElement('a');a.href=URL.createObjectURL(new Blob([JSON.stringify(data,null,2)],{type:'application/json'}));a.download='smart3d-scene.json';a.click()};
function resize(){const r=host.getBoundingClientRect();camera.aspect=r.width/r.height;camera.updateProjectionMatrix();renderer.setSize(r.width,r.height)}new ResizeObserver(resize).observe(host);resize();location('mountain');human('man').position.set(0,0,0);animal('dog').position.set(3,0,-2);tree('tree').position.set(-4,0,-2);updateUI();
(function loop(){requestAnimationFrame(loop);orbit.update();renderer.render(scene,camera)})();
</script>
</body>
</html>
