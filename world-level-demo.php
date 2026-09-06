<?php
declare(strict_types=1);
require_once __DIR__ . '/header.php';
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>SmartToolz — World-Level Workspace Demo</title>
<meta name="description" content="SmartToolz next-generation AI and productivity workspace demo.">
<style>
:root{--ink:#111827;--muted:#667085;--line:#e7eaf0;--soft:#f6f7fb;--purple:#635bff;--green:#12b76a;--dark:#101828}
*{box-sizing:border-box}body{margin:0;background:var(--soft);color:var(--ink);font-family:Inter,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif}.demo{width:min(1320px,calc(100% - 28px));margin:30px auto 70px}.hero{padding:42px;border:1px solid var(--line);border-radius:28px;background:radial-gradient(circle at 85% 10%,#e9e7ff 0,transparent 35%),linear-gradient(135deg,#fff,#f4f3ff);box-shadow:0 20px 60px #1822300d}.eyebrow{display:inline-flex;padding:7px 11px;border-radius:999px;background:#ecebff;color:var(--purple);font-size:11px;font-weight:900;letter-spacing:.7px}.hero h1{max-width:850px;font-size:clamp(40px,6vw,72px);line-height:1;letter-spacing:-4px;margin:18px 0 14px}.hero h1 span{color:var(--purple)}.hero p{max-width:760px;color:var(--muted);font-size:17px;line-height:1.7;margin:0}.prompt{margin-top:25px;display:flex;gap:10px;padding:10px;background:#fff;border:1px solid var(--line);border-radius:17px;box-shadow:0 12px 35px #1822300d}.prompt input{flex:1;border:0;outline:0;padding:10px;font-size:14px;min-width:0}.btn{border:0;border-radius:12px;padding:12px 17px;background:var(--purple);color:#fff;font-weight:850;cursor:pointer}.layout{display:grid;grid-template-columns:1.35fr .65fr;gap:18px;margin-top:18px}.panel{background:#fff;border:1px solid var(--line);border-radius:22px;padding:22px}.panel h2{font-size:20px;margin:0}.panel-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:17px}.panel-head small{color:var(--muted)}.workflow{display:grid;gap:10px}.step{display:flex;gap:13px;align-items:center;padding:14px;border:1px solid var(--line);border-radius:15px;background:#fbfcfe}.num{width:34px;height:34px;border-radius:10px;display:grid;place-items:center;background:#efefff;color:var(--purple);font-weight:900}.step strong{display:block;font-size:13px}.step span{display:block;color:var(--muted);font-size:11px;margin-top:3px}.status{margin-left:auto;padding:6px 9px;border-radius:999px;font-size:10px;font-weight:900;background:#eafaf2;color:#087443}.tools{display:grid;grid-template-columns:repeat(2,1fr);gap:10px}.tool{padding:15px;border:1px solid var(--line);border-radius:15px;text-decoration:none;color:var(--ink);transition:.18s}.tool:hover{transform:translateY(-2px);box-shadow:0 10px 25px #1822300d;border-color:#d8d4ff}.tool b{font-size:19px}.tool strong{display:block;font-size:13px;margin-top:9px}.tool small{display:block;color:var(--muted);font-size:10px;margin-top:3px}.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-top:18px}.stat{padding:16px;background:#fff;border:1px solid var(--line);border-radius:16px}.stat b{font-size:24px}.stat span{display:block;color:var(--muted);font-size:10px;margin-top:4px}.cta{margin-top:18px;padding:28px;border-radius:22px;background:var(--dark);color:#fff}.cta h2{margin:0 0 8px;font-size:25px}.cta p{color:#aeb8c8;font-size:12px;line-height:1.7;margin:0 0 16px}.pill{display:inline-flex;padding:8px 11px;border:1px solid #344054;border-radius:999px;font-size:10px;color:#d0d5dd;margin:4px 4px 0 0}@media(max-width:900px){.layout{grid-template-columns:1fr}.hero{padding:28px}.hero h1{letter-spacing:-2.5px}}@media(max-width:560px){.prompt{flex-direction:column}.prompt .btn{width:100%}.tools{grid-template-columns:1fr}.stats{grid-template-columns:1fr}.demo{width:min(100% - 18px,1320px)}}
</style>
</head><body>
<main class="demo">
<section class="hero">
<span class="eyebrow">SMARTTOOLZ 2.0 • WORKSPACE CONCEPT</span>
<h1>Tell SmartToolz the <span>job.</span><br>We build the workflow.</h1>
<p>One workspace for tools, AI, files, learning and repeatable workflows. This is the first visual direction for the world-level rebuild — designed to evolve beyond a collection of separate utilities.</p>
<div class="prompt"><input id="job" value="Optimize 100 product images for my website" aria-label="Describe a job"><button class="btn" onclick="runJob()">Build Workflow →</button></div>
</section>
<div class="layout">
<section class="panel"><div class="panel-head"><div><h2>AI Workflow</h2><small id="workflowNote">Ready to execute</small></div><span class="eyebrow">AUTOMATION</span></div>
<div class="workflow" id="workflow">
<div class="step"><div class="num">1</div><div><strong>Upload & inspect</strong><span>Detect dimensions, formats and file quality</span></div><span class="status">READY</span></div>
<div class="step"><div class="num">2</div><div><strong>Resize for web</strong><span>Apply responsive product-image dimensions</span></div><span class="status">READY</span></div>
<div class="step"><div class="num">3</div><div><strong>Compress & convert</strong><span>Optimize quality and generate modern WebP files</span></div><span class="status">READY</span></div>
<div class="step"><div class="num">4</div><div><strong>Package results</strong><span>Rename, organize and prepare a downloadable batch</span></div><span class="status">READY</span></div>
</div></section>
<section class="panel"><div class="panel-head"><div><h2>Quick Tools</h2><small>Jump into a task</small></div></div><div class="tools">
<a class="tool" href="/smart-toolz/tools/image-compressor.php"><b>🖼️</b><strong>Image Compressor</strong><small>Optimize images</small></a>
<a class="tool" href="/smart-toolz/tools/image-resizer.php"><b>📐</b><strong>Image Resizer</strong><small>Resize in seconds</small></a>
<a class="tool" href="/smart-toolz/tools/png-to-webp.php"><b>🔄</b><strong>PNG → WebP</strong><small>Modern formats</small></a>
<a class="tool" href="/smart-toolz/tool.php"><b>🛠️</b><strong>All Tools</strong><small>Browse the library</small></a>
</div></section>
</div>
<div class="stats"><div class="stat"><b>50+</b><span>Existing tools to migrate into the new engine</span></div><div class="stat"><b>1</b><span>Unified workspace instead of disconnected pages</span></div><div class="stat"><b>∞</b><span>Workflow combinations we can build over time</span></div></div>
<section class="cta"><h2>The rebuild direction</h2><p>Keep the working foundations — accounts, credits, referrals, analytics and existing tools — while introducing a stronger product layer around them.</p><span class="pill">AI Workflows</span><span class="pill">Bulk Processing</span><span class="pill">Projects & History</span><span class="pill">API + Webhooks</span><span class="pill">Learning Hub</span><span class="pill">Teams</span></section>
</main>
<script>function runJob(){const input=document.getElementById('job'),note=document.getElementById('workflowNote'),steps=[...document.querySelectorAll('.step')];note.textContent='Workflow generated from: '+input.value.trim();steps.forEach((s,i)=>{const st=s.querySelector('.status');st.textContent=i===0?'NEXT':'QUEUED';});}</script>
</body></html>
