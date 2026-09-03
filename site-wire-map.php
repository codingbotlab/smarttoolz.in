<?php
/* SmartToolz — live site architecture / wire-flow page. */
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SmartToolz — Live Site Wire Map</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Roboto+Mono:wght@500;700&display=swap" rel="stylesheet">
<style>
:root{--bg:#070b16;--panel:#0d1425;--line:#4d5b86;--ink:#edf2ff;--muted:#8d98b7;--accent:#786cff;--cyan:#25d9e8;--green:#39e69c;--border:rgba(255,255,255,.1)}
*{box-sizing:border-box}html,body{margin:0;min-height:100%;background:var(--bg);color:var(--ink);font-family:Inter,Arial,sans-serif}body{overflow-x:hidden}
.page{min-height:100vh;padding:28px 28px 40px;background:radial-gradient(circle at 50% 20%,rgba(120,108,255,.13),transparent 36%),linear-gradient(180deg,#0a0f1e,#070b16)}
.header{max-width:1450px;margin:auto;display:flex;justify-content:space-between;align-items:flex-end;gap:20px}.eyebrow{font:800 10px Inter;letter-spacing:1.7px;color:#9ca7c5;display:flex;align-items:center;gap:8px}.dot{width:8px;height:8px;border-radius:50%;background:var(--green);box-shadow:0 0 0 4px rgba(57,230,156,.1),0 0 18px rgba(57,230,156,.8);animation:pulse 1.1s infinite}.header h1{font-size:clamp(28px,4vw,48px);letter-spacing:-2px;margin:8px 0 5px}.header h1 span{color:var(--accent)}.header p{margin:0;color:var(--muted);font-size:12px;line-height:1.6}.status{display:flex;align-items:center;gap:9px;border:1px solid var(--border);background:rgba(255,255,255,.04);border-radius:13px;padding:11px 14px;color:#b7c0d9;font:700 10px Inter;white-space:nowrap}.status strong{font:800 12px 'Roboto Mono';color:#fff}
.map{max-width:1450px;height:min(720px,calc(100vh - 190px));min-height:560px;margin:24px auto 0;border:1px solid var(--border);border-radius:24px;position:relative;overflow:hidden;background-image:linear-gradient(rgba(255,255,255,.035) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.035) 1px,transparent 1px);background-size:32px 32px;box-shadow:0 25px 80px rgba(0,0,0,.35)}
svg{position:absolute;inset:0;width:100%;height:100%;pointer-events:none}.path{fill:none;stroke:url(#g);stroke-width:2.2;stroke-dasharray:8 8;opacity:.55;animation:dash 1.35s linear infinite}.packet{fill:#fff;filter:url(#glow);opacity:.95}
.nodes{position:absolute;inset:0}.node{position:absolute;width:190px;min-height:92px;padding:14px 15px;border:1px solid rgba(120,108,255,.28);border-radius:16px;background:rgba(13,20,37,.94);box-shadow:0 15px 35px rgba(0,0,0,.25),inset 0 1px rgba(255,255,255,.04);transition:.2s;cursor:default}.node:hover,.node.active{border-color:rgba(120,108,255,.9);box-shadow:0 0 0 1px rgba(120,108,255,.2),0 0 35px rgba(120,108,255,.18)}.node .type{display:block;color:#8f99b8;font:800 8px Inter;letter-spacing:1.4px;margin-bottom:5px}.node b{display:block;font-size:13px}.node em{display:block;margin-top:5px;color:#7f8aa9;font:500 10px Inter;font-style:normal}.node .icon{position:absolute;right:12px;top:12px;color:var(--accent);font-size:17px}.start{left:3%;top:43%;border-color:rgba(57,230,156,.4)}.start .type,.start .icon{color:var(--green)}.intent{left:18%;top:43%}.router{left:33%;top:43%}.core{left:48%;top:43%}.tools{left:65%;top:13%}.knowledge{left:65%;top:43%}.learning{left:65%;top:73%}.end{right:3%;top:43%;border-color:rgba(37,217,232,.4)}.end .type,.end .icon{color:var(--cyan)}
.legend{position:absolute;left:24px;bottom:20px;right:24px;display:flex;justify-content:space-between;gap:10px;color:#7f8aa9;font-size:9px;font-weight:700}.legend span{display:flex;align-items:center;gap:7px}.legend i{display:inline-block;width:7px;height:7px;border-radius:50%;background:var(--cyan);box-shadow:0 0 10px rgba(37,217,232,.8)}
.details{max-width:1450px;margin:14px auto 0;display:grid;grid-template-columns:repeat(4,1fr);gap:10px}.detail{border:1px solid var(--border);border-radius:13px;background:rgba(255,255,255,.035);padding:12px}.detail small{display:block;color:#7f8aa9;font:800 8px Inter;letter-spacing:1px}.detail b{display:block;margin-top:4px;font-size:11px}.detail span{display:block;margin-top:3px;color:#8792b0;font-size:9px}
@keyframes dash{to{stroke-dashoffset:-32}}@keyframes pulse{50%{opacity:.45;transform:scale(.82)}}
@media(max-width:950px){.page{padding:18px 12px 28px}.header{align-items:flex-start;flex-direction:column}.map{height:auto;min-height:0;padding:22px}.nodes{position:relative;display:grid;grid-template-columns:1fr;gap:14px}.node{position:relative!important;left:auto!important;right:auto!important;top:auto!important;width:100%}.map svg{display:none}.legend{position:relative;left:auto;right:auto;bottom:auto;margin-top:18px;flex-wrap:wrap}.details{grid-template-columns:1fr 1fr}}
@media(max-width:520px){.details{grid-template-columns:1fr}.header h1{letter-spacing:-1px}.status{width:100%;justify-content:space-between}}
</style>
</head>
<body>
<main class="page">
<header class="header">
  <div>
    <div class="eyebrow"><i class="dot"></i> SMARTTOOLZ / LIVE ARCHITECTURE</div>
    <h1>Full Site <span>Wire Connection</span></h1>
    <p>Front-facing NLP-style flow showing the main request, routing layer, site modules, branches and final response.</p>
  </div>
  <div class="status"><i class="dot"></i> REALTIME FLOW <strong id="count">09</strong></div>
</header>

<section class="map" id="wireMap" aria-label="SmartToolz full site live wire connection">
<svg viewBox="0 0 1450 720" preserveAspectRatio="none" aria-hidden="true">
<defs>
  <linearGradient id="g"><stop offset="0" stop-color="#786cff"/><stop offset=".5" stop-color="#25d9e8"/><stop offset="1" stop-color="#786cff"/></linearGradient>
  <filter id="glow"><feGaussianBlur stdDeviation="4" result="b"/><feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge></filter>
</defs>
<g class="paths">
<path class="path" data-edge="1" d="M150 360 H330"/><path class="path" data-edge="2" d="M370 360 H550"/><path class="path" data-edge="3" d="M585 360 H770"/>
<path class="path" data-edge="4" d="M800 360 C850 360 850 160 960 160 H1020"/><path class="path" data-edge="5" d="M800 360 H1020"/><path class="path" data-edge="6" d="M800 360 C850 360 850 560 960 560 H1020"/>
<path class="path" data-edge="7" d="M1210 160 H1320 V360 H1370"/><path class="path" data-edge="8" d="M1210 360 H1370"/><path class="path" data-edge="9" d="M1210 560 H1320 V360 H1370"/>
</g>
<g filter="url(#glow)" class="packets">
<circle class="packet" r="5"><animateMotion dur="2.1s" repeatCount="indefinite" path="M150 360 H330"/></circle>
<circle class="packet" r="5"><animateMotion dur="2.2s" begin=".25s" repeatCount="indefinite" path="M370 360 H550"/></circle>
<circle class="packet" r="5"><animateMotion dur="2.4s" begin=".5s" repeatCount="indefinite" path="M585 360 H770"/></circle>
<circle class="packet" r="5"><animateMotion dur="2.9s" begin=".1s" repeatCount="indefinite" path="M800 360 C850 360 850 160 960 160 H1020"/></circle>
<circle class="packet" r="5"><animateMotion dur="2.5s" begin=".6s" repeatCount="indefinite" path="M800 360 H1020"/></circle>
<circle class="packet" r="5"><animateMotion dur="2.9s" begin="1s" repeatCount="indefinite" path="M800 360 C850 360 850 560 960 560 H1020"/></circle>
<circle class="packet" r="5"><animateMotion dur="2.7s" begin=".2s" repeatCount="indefinite" path="M1210 160 H1320 V360 H1370"/></circle>
<circle class="packet" r="5"><animateMotion dur="2.2s" begin=".8s" repeatCount="indefinite" path="M1210 360 H1370"/></circle>
<circle class="packet" r="5"><animateMotion dur="2.8s" begin="1.1s" repeatCount="indefinite" path="M1210 560 H1320 V360 H1370"/></circle>
</g>
</svg>
<div class="nodes">
  <div class="node start"><span class="type">START</span><b>User Request</b><em>Natural language input</em><span class="icon">▶</span></div>
  <div class="node intent"><span class="type">NLP / INTENT</span><b>Intent Detection</b><em>Understand request → action</em><span class="icon">⌁</span></div>
  <div class="node router"><span class="type">ROUTER</span><b>index.php</b><em>Entry point → site routing</em><span class="icon">↗</span></div>
  <div class="node core"><span class="type">CORE</span><b>SmartToolz</b><em>Tool registry + shared logic</em><span class="icon">◈</span></div>
  <div class="node tools"><span class="type">TOOLS</span><b>smart-toolz/</b><em>Image • PDF • Text • Developer • Utility</em><span class="icon">⚙</span></div>
  <div class="node knowledge"><span class="type">KNOWLEDGE</span><b>knowledge-base/</b><em>Guides • references • how-to content</em><span class="icon">▤</span></div>
  <div class="node learning"><span class="type">LEARNING</span><b>learning-hub/</b><em>Courses • lessons • practice</em><span class="icon">◆</span></div>
  <div class="node end"><span class="type">END</span><b>Response Delivered</b><em>Result → user interface</em><span class="icon">✓</span></div>
</div>
<div class="legend"><span><i></i> live packet / action flow</span><span>Hover a node to trace the active path</span><span>START → NLP → ROUTER → MODULE → END</span></div>
</section>

<section class="details">
  <div class="detail"><small>ENTRY</small><b>index.php</b><span>Root request handoff</span></div>
  <div class="detail"><small>APPLICATION</small><b>smart-toolz/</b><span>Main tools platform</span></div>
  <div class="detail"><small>CONTENT</small><b>knowledge-base/ + learning-hub/</b><span>Guides and learning</span></div>
  <div class="detail"><small>OUTPUT</small><b>END / Response</b><span>Action result returned to UI</span></div>
</section>
</main>
<script>
const nodes=[...document.querySelectorAll('.node')];
const paths=[...document.querySelectorAll('.path')];
let active=0;
function trace(i){nodes.forEach(n=>n.classList.remove('active'));paths.forEach(p=>p.style.opacity='.28');nodes[i%nodes.length].classList.add('active');paths[(i-1+paths.length)%paths.length].style.opacity='.95';}
setInterval(()=>{trace(active++);},900);
nodes.forEach((n,i)=>n.addEventListener('mouseenter',()=>trace(i)));
</script>
</body>
</html>
