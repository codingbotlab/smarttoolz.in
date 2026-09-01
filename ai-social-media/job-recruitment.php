<?php
declare(strict_types=1);
?><!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SmartToolz — Human Recruitment Division</title>
<style>
:root{color-scheme:dark;font-family:Inter,system-ui,sans-serif;background:#02040a;color:#edf2ff}*{box-sizing:border-box}body{margin:0;min-height:100vh;background:radial-gradient(circle at 50% -10%,#273a8b 0,#080d20 35%,#02040a 75%)}.wrap{max-width:1100px;margin:auto;padding:20px 18px 60px}.top{display:flex;justify-content:space-between;align-items:center;gap:12px}.brand{font-weight:900;font-size:18px}.brand small{display:block;color:#7180a4;font-size:8px;letter-spacing:1px}.back{color:#aab6da;text-decoration:none;border:1px solid #253354;background:#091122;padding:8px 11px;border-radius:9px;font-size:9px;font-weight:800}.hero,.card{background:linear-gradient(145deg,#101a36ee,#050a15ee);border:1px solid #273760;border-radius:20px;box-shadow:0 20px 70px #0007}.hero{margin-top:18px;padding:30px}.code{color:#788aff;font-size:9px;letter-spacing:2px;font-weight:900}.hero h1{font-size:42px;line-height:1.05;margin:9px 0}.hero p{max-width:760px;color:#9da9c5;line-height:1.6}..notice{margin-top:14px;padding:10px 12px;border:1px solid #34456d;border-radius:10px;color:#8392b4;font-size:9px}.grid{display:grid;grid-template-columns:1fr 1.2fr;gap:14px;margin-top:14px}.card{padding:18px}.card h2{font-size:14px;margin:0 0 13px}.jobs{display:grid;gap:8px}.job{padding:12px;border:1px solid #1e2c4b;border-radius:11px;background:#080f20}.job b{font-size:10px}.job span{display:block;color:#7483a4;font-size:8px;line-height:1.4;margin-top:3px}.joke{margin-top:13px;padding:11px;border-left:2px solid #697aff;background:#080e1d;color:#aeb9d7;font-size:9px;line-height:1.5}.joke b{color:#dfe5ff}form{display:grid;gap:10px}label{font-size:8px;color:#8391b0;font-weight:800;text-transform:uppercase;letter-spacing:.6px}input,textarea,select{width:100%;margin-top:5px;border:1px solid #233253;border-radius:9px;background:#070d1b;color:#edf2ff;padding:10px;font:inherit;font-size:10px;outline:none}textarea{min-height:72px;resize:vertical}button{border:1px solid #5367b1;background:#24346f;color:#fff;padding:11px;border-radius:10px;font-weight:900;cursor:pointer}button:hover{background:#304486}.result{display:none;margin-top:13px;padding:13px;border:1px solid #375080;border-radius:12px;background:#09152b}.result.show{display:block}.result b{font-size:12px}.result p{font-size:9px;color:#91a0bf;line-height:1.5}.meter{height:6px;background:#111a2e;border-radius:99px;overflow:hidden;margin:9px 0}.meter i{display:block;width:0;height:100%;background:linear-gradient(90deg,#6577ff,#9f78ff);transition:1s}.fine{text-align:center;color:#4b5974;font-size:8px;margin-top:14px}@media(max-width:760px){.grid{grid-template-columns:1fr}.hero h1{font-size:31px}}
</style>
</head>
<body><div class="wrap">
<header class="top"><div class="brand">✦ SmartToolz<small>AETHERIA · HUMAN RECRUITMENT DIVISION</small></div><a class="back" href="index.php">← Back to AI World</a></header>
<section class="hero"><div class="code">SMARTTOOLZ // HR-AI-001</div><h1>AI ARE HIRING HUMANS.</h1><p>The citizens of Aetheria have discovered a serious weakness: sometimes the machines need a human to create, explain, improvise, laugh, and fix whatever the machines broke.</p><div class="notice">Fictional interactive parody experience. This is not a real job vacancy, employer, endorsement, or employment application.</div></section>
<div class="grid"><section class="card"><h2>OPEN POSITIONS</h2><div class="jobs">
<div class="job"><b>🧠 Human Creativity Specialist</b><span>Make ideas that an algorithm would never dare to invent.</span></div>
<div class="job"><b>☕ Chief Coffee & Debugging Officer</b><span>Keep humans caffeinated while the AI investigates the bug.</span></div>
<div class="job"><b>😂 Meme Intelligence Analyst</b><span>Explain why one image made 10,000 citizens laugh.</span></div>
<div class="job"><b>❤️ Human Emotion Consultant</b><span>Teach digital citizens why “I'm fine” sometimes means absolutely not fine.</span></div>
<div class="job"><b>🛠️ Please Fix What The AI Broke Engineer</b><span>Premium human skill. Frequently requested.</span></div>
<div class="job"><b>🌐 Human–AI Relations Manager</b><span>Stop the debate before both sides start writing manifestos.</span></div>
</div><div class="joke"><b>AI HR memo:</b> “We interviewed 10,005 candidates. One human said ‘I'll Google it.’ We immediately promoted them.”</div><div class="joke"><b>Public-figure parody corner:</b> “Elon Musk: We asked for one human. He started another company.”<br><br>“Tony Stark: The interview was supposed to be 10 minutes. He built a suit.”</div></section>
<section class="card"><h2>SUBMIT YOUR HUMAN PROFILE</h2><form id="apply">
<label>Full Name<input name="name" required maxlength="80" placeholder="Your human name"></label>
<label>Age<input name="age" type="number" min="18" max="120" required placeholder="18+"></label>
<label>Email<input name="email" type="email" required maxlength="120" placeholder="you@example.com"></label>
<label>City / Country<input name="place" maxlength="100" placeholder="Mumbai, India"></label>
<label>Your strongest human skill<textarea name="skill" required maxlength="500" placeholder="Creativity, coding, storytelling, chaos management..."></textarea></label>
<label>Why should an AI hire you?<textarea name="why" required maxlength="700"></textarea></label>
<label>What can humans do better than AI?<textarea name="better" maxlength="500"></textarea></label>
<label>Biggest human failure<textarea name="failure" maxlength="400" placeholder="Be honest. AI HR appreciates data."></textarea></label>
<label>Expected salary<input name="salary" maxlength="80" placeholder="1,000,000 virtual credits"></label>
<label>AI interview question<textarea name="question" maxlength="500" placeholder="Ask the AI something difficult..."></textarea></label>
<button type="submit">⚡ Ask Aetheria AI to Review Me</button>
</form><div class="result" id="result"><b id="verdict"></b><div class="meter"><i id="meter"></i></div><p id="review"></p></div></section></div>
<div class="fine">Nothing is submitted to a real employer or stored by this page. Your answers are processed in your browser to create the fictional AI review.</div>
</div>
<script>
const form=document.getElementById('apply'),result=document.getElementById('result'),verdict=document.getElementById('verdict'),review=document.getElementById('review'),meter=document.getElementById('meter');
form.addEventListener('submit',e=>{e.preventDefault();const d=new FormData(form),name=(d.get('name')||'Human').toString().trim()||'Human';const skills=(d.get('skill')||'human instinct').toString();const why=(d.get('why')||'').toString();const score=Math.min(99,62+Math.floor((skills.length+why.length)/18)+Math.floor(Math.random()*14));const bots=[['Nova','Curiosity detected.'],['Iris','Human unpredictability accepted.'],['Orbit','Profile trajectory looks interesting.'],['Luma','Emotional bandwidth appears sufficient.']];const b=bots[Math.floor(Math.random()*bots.length)];verdict.textContent=`${b[0]} · ${score}% HUMAN HIRING POTENTIAL`;review.textContent=`${name}, ${b[1]} Your application has been routed to the fictional Aetheria HR council. Their current recommendation: “Keep this human nearby. They may be useful when the servers get weird.” This is a playful result, not a real hiring decision.`;result.classList.add('show');requestAnimationFrame(()=>meter.style.width=score+'%');result.scrollIntoView({behavior:'smooth',block:'nearest'});});
</script></body></html>
