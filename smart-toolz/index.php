<?php
declare(strict_types=1);

ob_start();
require_once __DIR__ . '/home.php';
$html = ob_get_clean();

$promoFile = __DIR__ . '/learning-hub-promo.php';
if (is_file($promoFile)) {
    ob_start();
    require $promoFile;
    $promo = ob_get_clean();
    $html = str_ireplace('</body>', $promo . "\n</body>", $html);
}

$youtubeUrl = getenv('SMARTTOOLZ_YOUTUBE_URL') ?: 'https://www.youtube.com/';
$quickLinks = '<div class="st-content-links" aria-label="SmartToolz content links"><a href="/knowledge-base/">📚 How-to Knowledge Base</a><a href="' . htmlspecialchars($youtubeUrl, ENT_QUOTES, 'UTF-8') . '" target="_blank" rel="noopener noreferrer">▶ YouTube</a></div><style>.st-content-links{max-width:1180px;margin:18px auto;padding:0 20px;display:flex;justify-content:flex-end;gap:10px;flex-wrap:wrap}.st-content-links a{display:inline-flex;align-items:center;gap:7px;padding:10px 14px;border:1px solid #e2e6ef;border-radius:12px;background:#fff;color:#172033;text-decoration:none;font-size:13px;font-weight:800;box-shadow:0 5px 18px rgba(20,30,60,.06)}.st-content-links a:hover{transform:translateY(-1px)}</style>';
$html = str_ireplace('</body>', $quickLinks . "\n</body>", $html);

$threeJs = <<<'HTML'
<style>
#st-three-hero{position:absolute;inset:0;z-index:0;pointer-events:none;overflow:hidden}
#st-three-hero canvas{display:block;width:100%;height:100%}
.hero{position:relative;overflow:hidden}
.hero-grid{position:relative;z-index:2}
.hero-art{position:relative;z-index:2;transition:transform .18s ease-out}
.st-three-label{position:absolute;right:6%;bottom:13%;z-index:3;padding:8px 12px;border:1px solid rgba(99,91,255,.22);border-radius:999px;background:rgba(255,255,255,.78);backdrop-filter:blur(10px);box-shadow:0 10px 30px rgba(40,50,100,.08);font-size:11px;font-weight:800;color:#635bff;pointer-events:none}
@media(max-width:950px){#st-three-hero{opacity:.55}.st-three-label{right:8%;bottom:5%}}
@media(prefers-reduced-motion:reduce){#st-three-hero{display:none}.hero-art{transform:none!important}}
</style>
<div id="st-three-hero" aria-hidden="true"></div>
<div class="st-three-label">✨ Interactive tools space</div>
<script type="module">
import * as THREE from 'https://cdn.jsdelivr.net/npm/three@0.180.0/build/three.module.min.js';
(() => {
  const host=document.getElementById('st-three-hero');
  if(!host||window.matchMedia('(prefers-reduced-motion: reduce)').matches)return;
  const scene=new THREE.Scene();
  const camera=new THREE.PerspectiveCamera(38,1,.1,100); camera.position.set(0,.2,8.5);
  const renderer=new THREE.WebGLRenderer({alpha:true,antialias:true,powerPreference:'high-performance'});
  renderer.setPixelRatio(Math.min(window.devicePixelRatio||1,1.6)); renderer.setClearAlpha(0); host.appendChild(renderer.domElement);
  const group=new THREE.Group(); group.position.set(2.1,.1,0); scene.add(group);
  const core=new THREE.Mesh(new THREE.IcosahedronGeometry(1.35,2),new THREE.MeshBasicMaterial({color:0x635bff,wireframe:true,transparent:true,opacity:.24})); group.add(core);
  const ring=new THREE.Mesh(new THREE.TorusGeometry(2,.018,10,120),new THREE.MeshBasicMaterial({color:0x635bff,transparent:true,opacity:.30})); ring.rotation.x=Math.PI*.48; group.add(ring);
  const ring2=new THREE.Mesh(new THREE.TorusGeometry(1.62,.012,8,100),new THREE.MeshBasicMaterial({color:0x8d86ff,transparent:true,opacity:.22})); ring2.rotation.y=Math.PI*.32; group.add(ring2);
  const nodeGroup=new THREE.Group(); group.add(nodeGroup);
  const nodes=[];
  for(let i=0;i<18;i++){const a=i/18*Math.PI*2,r=1.9+(i%3)*.23,node=new THREE.Mesh(new THREE.SphereGeometry(.045+(i%3)*.018,8,8),new THREE.MeshBasicMaterial({color:i%2?0x8d86ff:0x635bff,transparent:true,opacity:.72}));node.position.set(Math.cos(a)*r,Math.sin(a)*r*.62,Math.sin(a*2)*.55);nodeGroup.add(node);nodes.push({mesh:node,phase:i*.55});}
  const particleCount=260,positions=new Float32Array(particleCount*3);
  for(let i=0;i<particleCount;i++){const j=i*3;positions[j]=(Math.random()-.5)*11;positions[j+1]=(Math.random()-.5)*6.2;positions[j+2]=(Math.random()-.5)*3.5-1;}
  const particleGeo=new THREE.BufferGeometry(); particleGeo.setAttribute('position',new THREE.BufferAttribute(positions,3));
  const particles=new THREE.Points(particleGeo,new THREE.PointsMaterial({color:0x8d86ff,size:.018,transparent:true,opacity:.32,sizeAttenuation:true})); scene.add(particles);
  const target={x:0,y:0},mouse={x:0,y:0};
  window.addEventListener('pointermove',e=>{target.x=(e.clientX/window.innerWidth-.5)*2;target.y=(e.clientY/window.innerHeight-.5)*2},{passive:true});
  function resize(){const w=host.clientWidth||innerWidth,h=host.clientHeight||500;camera.aspect=w/h;camera.updateProjectionMatrix();renderer.setSize(w,h,false);} window.addEventListener('resize',resize,{passive:true}); resize();
  let t=0;
  function animate(){t+=.006;mouse.x+=(target.x-mouse.x)*.035;mouse.y+=(target.y-mouse.y)*.035;group.rotation.y=t*.48+mouse.x*.16;group.rotation.x=Math.sin(t*.55)*.10+mouse.y*.08;core.rotation.z=-t*.35;ring.rotation.z=t*.65;ring2.rotation.x=-t*.45;nodeGroup.rotation.z=-t*.30;nodes.forEach(n=>n.mesh.position.z+=Math.sin(t*1.4+n.phase)*.0012);particles.rotation.y=t*.035+mouse.x*.025;particles.rotation.x=mouse.y*.012;const art=document.querySelector('.hero-art');if(art)art.style.transform=`translate3d(${mouse.x*7}px,${mouse.y*5}px,0) rotate(${mouse.x*1.2}deg)`;renderer.render(scene,camera);requestAnimationFrame(animate);}
  animate();
})();
</script>
HTML;
$html = str_ireplace('</section>', $threeJs . "\n</section>", $html, 1);

echo $html;
