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
*{box-sizing:border-box}html,body{margin:0;width:100%;height:100%;overflow:hidden;background:#202124;color:#e8e8e8;font:12px Arial,sans-serif}button{font:inherit;color:#ddd;background:#292b2f;border:1px solid #3c3f44;border-radius:4px;cursor:pointer}button:hover{background:#35383d}.app{height:100vh;display:grid;grid-template-rows:44px 1fr 30px}.top{display:flex;align-items:center;gap:6px;padding:6px 8px;background:#18191c;border-bottom:1px solid #383a3f}.logo{font-size:15px;font-weight:800;margin:0 12px 0 5px}.logo b{color:#72adff}.top button{height:30px;min-width:30px}.cmd{height:30px;flex:1;max-width:480px;background:#101114;border:1px solid #3d4147;color:#ddd;padding:0 9px;border-radius:4px}.main{min-height:0;display:grid;grid-template-columns:58px minmax(0,1fr) 235px}.rail{background:#1b1c20;border-right:1px solid #37393e;padding:7px 4px;display:flex;flex-direction:column;gap:6px;align-items:center}.rail button{width:46px;height:43px;font-size:18px}.rail small{display:block;font-size:8px;color:#999;margin-top:2px}.rail .active{background:#3b536d;border-color:#6595c2}.viewport{position:relative;background:#303236;min-width:0;min-height:0}.canvas{position:absolute;inset:0}.hint,.status{position:absolute;top:9px;background:#17181bcc;border:1px solid #494c51;border-radius:4px;padding:7px 9px;color:#aaa;pointer-events:none}.hint{left:9px}.status{right:9px}.right{background:#1e2024;border-left:1px solid #383a3f;overflow:auto}.tabs{display:flex;border-bottom:1px solid #383a3f}.tabs button{flex:1;border:0;border-radius:0;background:none;padding:10px 3px;color:#999}.tabs .on{color:#fff;border-bottom:2px solid #72adff}.heading{padding:10px 11px 7px;text-transform:uppercase;letter-spacing:1px;font-weight:bold;color:#92969d;font-size:9px}.tree{padding:7px}.node{padding:8px;border-radius:4px;display:flex;gap:7px}.node.sel,.node:hover{background:#33363c}.node .muted{margin-left:auto;color:#777}.inspector{padding:10px;border-top:1px solid #33363b}.muted{color:#999;font-size:10px;line-height:1.5}.field{margin:8px 0}.field label{display:block;color:#999;font-size:9px;margin-bottom:4px}.trip{display:grid;grid-template-columns:1fr 1fr 1fr;gap:5px}.field input{width:100%;height:27px;background:#121316;border:1px solid #3b3e43;border-radius:4px;color:#ddd;padding:0 5px}.timeline{height:145px;background:#191b1e;border-top:1px solid #3b3d42;display:grid;grid-template-columns:180px 1fr}.tracks{border-right:1px solid #383a3f}.trackhead{height:29px;padding:8px;color:#999;border-bottom:1px solid #303238}.track{height:29px;padding:8px;border-bottom:1px solid #2c2f34}.sheet{overflow:hidden;position:relative;background:repeating-linear-gradient(90deg,#191b1e 0,#191b1e 39px,#27292e 40px)}.numbers{height:29px;border-bottom:1px solid #303238;display:flex;color:#777}.numbers span{width:40px;text-align:center;padding-top:8px}.playhead{position:absolute;left:12px;top:0;bottom:0;width:1px;background:#ef6570}.bottom{background:#18191c;border-top:1px solid #383a3f;display:flex;align-items:center;justify-content:space-between;padding:0 9px;color:#777;font-size:10px}@media(max-width:850px){.main{grid-template-columns:52px minmax(0,1fr)}.right{display:none}.cmd{max-width:none}}
</style>
</head>
<body>
<div class="app">
<header class="top">
 <div class="logo">SMART<b>3D</b></div><button id="new">＋</button><button id="undo">↶</button><button id="redo">↷</button><button id="save">▣</button><input id="cmd" class="cmd" placeholder="Describe a scene with NLP..." value="">
 <button id="build">BUILD</button><button id="play">▶</button><button id="export">EXPORT</button>
</header>
<div class="main">
 <aside class="rail">
  <button class="active" id="select" title="Select">↖<small>Select</small></button>
  <button id="move" title="Move">✥<small>Move</small></button>
  <button id="rotate" title="Rotate">↻<small>Rotate</small></button>
  <button id="scale" title="Scale">⤢<small>Scale</small></button>
  <button id="addcube" title="Add cube">■<small>Cube</small></button>
  <button id="addlight" title="Add light">☀<small>Light</small></button>
  <button id="addcamera" title="Add camera">▣<small>Camera</small></button>
 </aside>
 <main class="viewport"><div id="canvas" class="canvas"></div><div class="hint">LMB select · drag orbit · wheel zoom · RMB pan</div><div id="status" class="status">Cube selected</div></main>
 <aside class="right">
  <div class="tabs"><button class="on">Scene</button><button>Object</button></div>
  <div class="heading">Scene</div>
  <div class="tree" id="tree"></div>
  <div class="inspector"><div class="heading" style="padding:0 0 7px">Transform</div><div id="inspector"></div></div>
 </aside>
</div>
<div class="timeline">
 <div class="tracks"><div class="trackhead">TIMELINE</div><div class="track">Cube</div><div class="track">Camera</div><div class="track">Light</div></div>
 <div class="sheet"><div class="numbers"> <span>0</span><span>10</span><span>20</span><span>30</span><span>40</span><span>50</span><span>60</span><span>70</span><span>80</span><span>90</span><span>100</span></div><div class="playhead" id="playhead"></div></div>
</div>
<footer class="bottom"><span>Smart3D</span><span id="mode">SELECT</span><span>Three.js WebGL</span></footer>
</div>
<script type="importmap">{"imports":{"three":"https://cdn.jsdelivr.net/npm/three@0.180.0/build/three.module.js","three/addons/":"https://cdn.jsdelivr.net/npm/three@0.180.0/examples/jsm/"}}</script>
<script type="module">
import * as THREE from 'three';
import {OrbitControls} from 'three/addons/controls/OrbitControls.js';
import {TransformControls} from 'three/addons/controls/TransformControls.js';

const host=document.getElementById('canvas');
const scene=new THREE.Scene();scene.background=new THREE.Color(0x303236);
const camera=new THREE.PerspectiveCamera(50,1,.1,1000);camera.position.set(7,5,8);
const renderer=new THREE.WebGLRenderer({antialias:true});renderer.setPixelRatio(Math.min(devicePixelRatio,2));renderer.shadowMap.enabled=true;renderer.outputColorSpace=THREE.SRGBColorSpace;host.appendChild(renderer.domElement);
const orbit=new OrbitControls(camera,renderer.domElement);orbit.enableDamping=true;orbit.target.set(0,1,0);
const transform=new TransformControls(camera,renderer.domElement);scene.add(transform.getHelper());transform.addEventListener('dragging-changed',e=>orbit.enabled=!e.value);
const grid=new THREE.GridHelper(20,20,0x59606a,0x3d4249);scene.add(grid);
scene.add(new THREE.AmbientLight(0xffffff,.45));
const light=new THREE.DirectionalLight(0xffffff,2.5);light.position.set(4,7,5);light.castShadow=true;scene.add(light);light.name='Light';
const cube=new THREE.Mesh(new THREE.BoxGeometry(2,2,2),new THREE.MeshStandardMaterial({color:0x888888,roughness:.65}));cube.position.y=1;cube.castShadow=true;cube.name='Cube';scene.add(cube);
const camHelper=new THREE.CameraHelper(camera);camHelper.name='Camera';camHelper.visible=false;scene.add(camHelper);
const selectable=[cube,light,camHelper];let selected=cube;
function setSelected(o){selected=o;transform.detach();if(o&&o.isObject3D&&o!==light&&o!==camHelper)transform.attach(o);renderTree();renderInspector();document.getElementById('status').textContent=(o?o.name:'Nothing')+' selected'}
function renderTree(){document.getElementById('tree').innerHTML=selectable.map(o=>`<div class="node ${o===selected?'sel':''}" data-name="${o.name}">◈ ${o.name}<span class="muted">${o.type}</span></div>`).join('');document.querySelectorAll('.node').forEach(n=>n.onclick=()=>setSelected(selectable.find(o=>o.name===n.dataset.name)))}
function renderInspector(){const el=document.getElementById('inspector');if(!selected){el.innerHTML='<div class="muted">Select an object</div>';return}el.innerHTML='<div class="trip">'+['x','y','z'].map(a=>`<div class="field"><label>Position ${a.toUpperCase()}</label><input data-p="${a}" value="${selected.position[a].toFixed(2)}"></div>`).join('')+'</div><div class="field"><label>Rotation Y</label><input id="ry" value="'+THREE.MathUtils.radToDeg(selected.rotation.y).toFixed(1)+'"></div>';el.querySelectorAll('[data-p]').forEach(i=>i.oninput=()=>{selected.position[i.dataset.p]=Number(i.value)});document.getElementById('ry').oninput=e=>selected.rotation.y=THREE.MathUtils.degToRad(Number(e.target.value))}
['select','move','rotate','scale'].forEach(id=>document.getElementById(id).onclick=()=>{document.getElementById('mode').textContent=id.toUpperCase();if(id==='select')transform.detach();else transform.setMode({move:'translate',rotate:'rotate',scale:'scale'}[id]);if(id!=='select'&&selected!==light&&selected!==camHelper)transform.attach(selected)});
function add(type){let o;if(type==='cube'){o=new THREE.Mesh(new THREE.BoxGeometry(2,2,2),new THREE.MeshStandardMaterial({color:0x888888,roughness:.65}));o.position.set(Math.random()*4-2,1,Math.random()*4-2);o.name='Cube '+(selectable.filter(x=>x.name.startsWith('Cube')).length+1);o.castShadow=true;scene.add(o);selectable.push(o)}else if(type==='light'){o=new THREE.PointLight(0xffffff,4);o.position.set(3,4,2);o.name='Light '+(selectable.filter(x=>x.name.startsWith('Light')).length+1);scene.add(o);selectable.push(o)}else{o=new THREE.PerspectiveCamera(50,1,.1,1000);o.position.set(4,3,5);o.name='Camera '+(selectable.filter(x=>x.name.startsWith('Camera')).length+1);scene.add(o);selectable.push(o)}setSelected(o)}
document.getElementById('addcube').onclick=()=>add('cube');document.getElementById('addlight').onclick=()=>add('light');document.getElementById('addcamera').onclick=()=>add('camera');
document.getElementById('new').onclick=()=>{for(let i=selectable.length-1;i>0;i--){scene.remove(selectable[i]);selectable.pop()}cube.position.set(0,1,0);setSelected(cube)};
document.getElementById('play').onclick=()=>{let p=0;const h=document.getElementById('playhead');const timer=setInterval(()=>{p+=2;h.style.left=p+'px';if(p>420){clearInterval(timer);h.style.left='12px'}},100)};
document.getElementById('save').onclick=()=>{const data={scene:'Smart3D',objects:selectable.map(o=>({name:o.name,type:o.type,position:o.position.toArray(),rotation:o.rotation.toArray()}))};const a=document.createElement('a');a.href=URL.createObjectURL(new Blob([JSON.stringify(data,null,2)],{type:'application/json'}));a.download='smart3d-scene.json';a.click()};
document.getElementById('build').onclick=()=>{const text=document.getElementById('cmd').value.trim();if(text){document.getElementById('status').textContent='NLP command received: '+text}};
function resize(){const r=host.getBoundingClientRect();camera.aspect=r.width/r.height;camera.updateProjectionMatrix();renderer.setSize(r.width,r.height)}new ResizeObserver(resize).observe(host);resize();setSelected(cube);
(function loop(){requestAnimationFrame(loop);orbit.update();renderer.render(scene,camera)})();
</script>
</body></html>
