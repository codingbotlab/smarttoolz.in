import * as THREE from 'https://cdn.jsdelivr.net/npm/three@0.180.0/build/three.module.min.js';

(() => {
  const host = document.querySelector('#st-three-hero');
  if (!host) return;

  const scene = new THREE.Scene();
  const camera = new THREE.PerspectiveCamera(42, 1, 0.1, 100);
  camera.position.z = 7;

  const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
  renderer.setSize(host.clientWidth || 520, host.clientHeight || 420, false);
  renderer.setClearColor(0x000000, 0);
  host.appendChild(renderer.domElement);

  const group = new THREE.Group();
  scene.add(group);

  const core = new THREE.Mesh(
    new THREE.IcosahedronGeometry(1.55, 2),
    new THREE.MeshBasicMaterial({ color: 0x2563eb, wireframe: true, transparent: true, opacity: 0.42 })
  );
  group.add(core);

  const ringMaterial = new THREE.MeshBasicMaterial({ color: 0x06b6d4, wireframe: true, transparent: true, opacity: 0.45 });
  const ring1 = new THREE.Mesh(new THREE.TorusGeometry(2.05, 0.018, 10, 100), ringMaterial);
  ring1.rotation.x = Math.PI * 0.35;
  group.add(ring1);

  const ring2 = new THREE.Mesh(new THREE.TorusGeometry(2.35, 0.014, 10, 100), ringMaterial);
  ring2.rotation.y = Math.PI * 0.48;
  group.add(ring2);

  const points = [];
  for (let i = 0; i < 260; i++) {
    const r = 2.2 + Math.random() * 2.0;
    const a = Math.random() * Math.PI * 2;
    const z = (Math.random() - 0.5) * 3.6;
    points.push(new THREE.Vector3(Math.cos(a) * r, Math.sin(a) * r, z));
  }
  const particleGeometry = new THREE.BufferGeometry().setFromPoints(points);
  const particles = new THREE.Points(
    particleGeometry,
    new THREE.PointsMaterial({ color: 0x60a5fa, size: 0.035, transparent: true, opacity: 0.75 })
  );
  group.add(particles);

  const nodes = [];
  for (let i = 0; i < 24; i++) {
    const a = (i / 24) * Math.PI * 2;
    const p = new THREE.Mesh(
      new THREE.SphereGeometry(0.055 + Math.random() * 0.035, 8, 8),
      new THREE.MeshBasicMaterial({ color: 0x22d3ee, transparent: true, opacity: 0.9 })
    );
    p.position.set(Math.cos(a) * 2.25, Math.sin(a) * 2.25, Math.sin(a * 2) * 0.55);
    nodes.push(p);
    group.add(p);
  }

  let targetX = 0;
  let targetY = 0;
  let raf = 0;
  const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  const resize = () => {
    const w = Math.max(280, host.clientWidth || 520);
    const h = Math.max(280, host.clientHeight || 420);
    camera.aspect = w / h;
    camera.updateProjectionMatrix();
    renderer.setSize(w, h, false);
  };
  window.addEventListener('resize', resize, { passive: true });
  resize();

  host.addEventListener('pointermove', (event) => {
    if (reduced) return;
    const rect = host.getBoundingClientRect();
    targetX = ((event.clientX - rect.left) / rect.width - 0.5) * 0.7;
    targetY = ((event.clientY - rect.top) / rect.height - 0.5) * 0.45;
  }, { passive: true });

  host.addEventListener('pointerleave', () => {
    targetX = 0;
    targetY = 0;
  }, { passive: true });

  const animate = (time) => {
    group.rotation.y += reduced ? 0 : 0.0025;
    group.rotation.x += reduced ? 0 : 0.0008;
    core.rotation.z += reduced ? 0 : 0.0015;
    ring1.rotation.z += reduced ? 0 : 0.002;
    ring2.rotation.x -= reduced ? 0 : 0.0015;
    particles.rotation.y -= reduced ? 0 : 0.0007;

    nodes.forEach((node, i) => {
      node.position.z += Math.sin(time * 0.001 + i) * 0.0008;
    });

    group.rotation.y += (targetX - group.rotation.y * 0.08) * 0.008;
    group.rotation.x += (targetY - group.rotation.x * 0.08) * 0.008;

    renderer.render(scene, camera);
    raf = requestAnimationFrame(animate);
  };

  raf = requestAnimationFrame(animate);

  window.addEventListener('pagehide', () => {
    cancelAnimationFrame(raf);
    renderer.dispose();
    particleGeometry.dispose();
    core.geometry.dispose();
    core.material.dispose();
    ring1.geometry.dispose();
    ring2.geometry.dispose();
    ringMaterial.dispose();
    particles.material.dispose();
    nodes.forEach((node) => { node.geometry.dispose(); node.material.dispose(); });
  }, { once: true });
})();
