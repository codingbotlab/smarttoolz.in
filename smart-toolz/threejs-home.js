import * as THREE from 'https://cdn.jsdelivr.net/npm/three@0.180.0/build/three.module.min.js';

(() => {
  const host = document.createElement('div');
  host.id = 'st-three-world';
  host.setAttribute('aria-hidden', 'true');
  document.body.prepend(host);
  const canvas = document.createElement('canvas');
  host.appendChild(canvas);

  const renderer = new THREE.WebGLRenderer({canvas, antialias:true, alpha:true, powerPreference:'high-performance'});
  renderer.setPixelRatio(Math.min(devicePixelRatio || 1, 1.7));
  renderer.setClearColor(0x000000, 0);

  const scene = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(55, 1, .1, 120);
  camera.position.z = 14;

  const world = new THREE.Group();
  scene.add(world);

  const core = new THREE.Mesh(
    new THREE.IcosahedronGeometry(2.5, 2),
    new THREE.MeshBasicMaterial({color:0x635bff, wireframe:true, transparent:true, opacity:.18})
  );
  core.position.set(4.5, 2, -5);
  world.add(core);

  const core2 = new THREE.Mesh(
    new THREE.IcosahedronGeometry(1.5, 2),
    new THREE.MeshBasicMaterial({color:0x06b6d4, wireframe:true, transparent:true, opacity:.2})
  );
  core2.position.set(-5, -3, -6);
  world.add(core2);

  const rings=[];
  [[4.5,2,-5,3.15,.55],[4.5,2,-5,3.65,1.05],[-5,-3,-6,2,.7]].forEach(v=>{
    const r=new THREE.Mesh(new THREE.TorusGeometry(v[3],.018,8,96),new THREE.MeshBasicMaterial({color:0x22d3ee,transparent:true,opacity:.25}));
    r.position.set(v[0],v[1],v[2]); r.rotation.set(v[4],v[4]*.7,.2); world.add(r); rings.push(r);
  });

  const count=innerWidth<700?350:700;
  const pos=new Float32Array(count*3);
  for(let i=0;i<count;i++){
    pos[i*3]=(Math.random()-.5)*30;
    pos[i*3+1]=(Math.random()-.5)*26;
    pos[i*3+2]=-2-Math.random()*38;
  }
  const pg=new THREE.BufferGeometry();
  pg.setAttribute('position',new THREE.BufferAttribute(pos,3));
  const particles=new THREE.Points(pg,new THREE.PointsMaterial({color:0x64748b,size:.035,transparent:true,opacity:.42}));
  world.add(particles);

  const linePos=[];
  for(let i=0;i<65;i++){
    const a=new THREE.Vector3((Math.random()-.5)*22,(Math.random()-.5)*18,-5-Math.random()*18);
    const b=a.clone().add(new THREE.Vector3((Math.random()-.5)*3.5,(Math.random()-.5)*3.5,(Math.random()-.5)*2));
    linePos.push(a.x,a.y,a.z,b.x,b.y,b.z);
  }
  const lg=new THREE.BufferGeometry(); lg.setAttribute('position',new THREE.Float32BufferAttribute(linePos,3));
  world.add(new THREE.LineSegments(lg,new THREE.LineBasicMaterial({color:0x94a3b8,transparent:true,opacity:.1})));

  const orbs=new THREE.Group(); world.add(orbs);
  for(let i=0;i<20;i++){
    const o=new THREE.Mesh(new THREE.SphereGeometry(.045+Math.random()*.07,10,10),new THREE.MeshBasicMaterial({color:i%2?0x635bff:0x06b6d4,transparent:true,opacity:.55}));
    o.position.set((Math.random()-.5)*19,(Math.random()-.5)*15,-4-Math.random()*20);
    o.userData.y=o.position.y; o.userData.p=Math.random()*6.28; orbs.add(o);
  }

  let mx=0,my=0,scroll=0,targetScroll=0;
  const reduced=matchMedia('(prefers-reduced-motion: reduce)').matches;
  const resize=()=>{camera.aspect=innerWidth/innerHeight;camera.updateProjectionMatrix();renderer.setSize(innerWidth,innerHeight,false);};
  resize(); addEventListener('resize',resize,{passive:true});
  addEventListener('pointermove',e=>{mx=(e.clientX/innerWidth-.5)*2;my=(e.clientY/innerHeight-.5)*2},{passive:true});
  addEventListener('scroll',()=>{targetScroll=Math.min(scrollY/Math.max(document.body.scrollHeight-innerHeight,1),1)},{passive:true});

  let raf;
  const animate=t=>{
    const time=t*.001; scroll+=(targetScroll-scroll)*.035;
    if(!reduced){
      world.rotation.y+=.0007; core.rotation.x+=.0014; core.rotation.y+=.002; core2.rotation.y-=.0012;
      rings.forEach((r,i)=>{r.rotation.z+=(i?-1:1)*.0013;r.rotation.x+=.0005;});
      particles.rotation.y=time*.006;
      orbs.children.forEach(o=>o.position.y=o.userData.y+Math.sin(time*.8+o.userData.p)*.18);
    }
    world.position.x+=(-mx*.65-world.position.x)*.025;
    world.position.y+=(my*.4-scroll*1.5-world.position.y)*.025;
    camera.position.x+=(mx*.3-camera.position.x)*.02;
    camera.position.y+=(-my*.18-camera.position.y)*.02;
    camera.lookAt(0,-scroll*1.5,-7);
    renderer.render(scene,camera); raf=requestAnimationFrame(animate);
  };
  raf=requestAnimationFrame(animate);
})();
