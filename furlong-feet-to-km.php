<?php
// Standalone Furlong + Feet to Kilometer converter.
// Intentionally self-contained so existing tools/files are not modified.
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Furlong & Feet to Kilometer Converter</title>
<meta name="description" content="Convert furlongs and feet to kilometers instantly.">
<style>
*{box-sizing:border-box}body{margin:0;font-family:system-ui,-apple-system,Segoe UI,sans-serif;background:#f5f7fb;color:#18212f;min-height:100vh}.wrap{max-width:720px;margin:auto;padding:40px 18px}.card{background:#fff;border:1px solid #e1e5eb;border-radius:20px;padding:28px;box-shadow:0 12px 35px #10182814}h1{margin:0 0 10px;font-size:clamp(28px,6vw,42px)}p{color:#667085;line-height:1.6}.grid{display:grid;grid-template-columns:1fr 1fr;gap:15px;margin-top:24px}label{display:block;font-weight:700;margin-bottom:7px}input{width:100%;height:54px;border:1px solid #dfe4ea;border-radius:11px;padding:0 14px;font-size:18px}input:focus{outline:3px solid #2563eb22;border-color:#2563eb}.result{margin-top:22px;padding:20px;border-radius:15px;background:#eef5ff;border:1px solid #cfe0ff}.small{font-size:13px;color:#667085;font-weight:700;text-transform:uppercase}.value{font-size:44px;font-weight:800;margin-top:5px;word-break:break-word}.formula{font-size:13px;color:#667085;margin-top:8px}.buttons{display:flex;gap:10px;margin-top:17px}button{border:0;border-radius:10px;padding:12px 17px;font-weight:700;cursor:pointer}.copy{background:#2563eb;color:white}.reset{background:#eef0f3;color:#18212f}@media(max-width:560px){.grid{grid-template-columns:1fr}.card{padding:22px}}
</style>
</head>
<body><main class="wrap"><section class="card"><h1>Furlong + Feet to Kilometer</h1><p>Enter furlongs and feet. The kilometer result updates instantly.</p><div class="grid"><div><label for="f">Furlongs</label><input id="f" type="number" min="0" step="any" value="0"></div><div><label for="ft">Feet</label><input id="ft" type="number" min="0" step="any" value="0"></div></div><div class="result"><div class="small">Result</div><div class="value"><span id="km">0</span> km</div><div class="formula">1 furlong = 0.201168 km · 1 foot = 0.0003048 km</div></div><div class="buttons"><button class="copy" id="copy">Copy Result</button><button class="reset" id="reset">Reset</button></div></section></main><script>const f=document.getElementById('f'),ft=document.getElementById('ft'),km=document.getElementById('km');function calc(){const a=parseFloat(f.value)||0,b=parseFloat(ft.value)||0;km.textContent=(a*.201168+b*.0003048).toLocaleString('en-US',{maximumFractionDigits:12})}f.oninput=ft.oninput=calc;document.getElementById('reset').onclick=()=>{f.value=ft.value=0;calc()};document.getElementById('copy').onclick=async()=>{try{await navigator.clipboard.writeText(km.textContent+' km');document.getElementById('copy').textContent='Copied!';setTimeout(()=>document.getElementById('copy').textContent='Copy Result',1200)}catch(e){}};calc();</script></body></html>
