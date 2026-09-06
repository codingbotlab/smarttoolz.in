<?php declare(strict_types=1); ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SmartToolz Domain Reseller</title>
<style>
*{box-sizing:border-box}body{margin:0;font-family:Arial,sans-serif;background:#f5f7fb;color:#20242b}.wrap{max-width:900px;margin:50px auto;padding:20px}.card{background:#fff;border:1px solid #e1e5ec;border-radius:12px;padding:24px;box-shadow:0 8px 30px #0000000d}h1{margin:0 0 8px}.muted{color:#687181}.search{display:flex;gap:10px;margin:25px 0}.search input{flex:1;padding:14px;border:1px solid #cfd5df;border-radius:8px;font-size:16px}.search button{padding:0 22px;border:0;border-radius:8px;background:#2563eb;color:white;font-weight:700;cursor:pointer}.result{display:none;margin-top:20px;padding:18px;border:1px solid #e1e5ec;border-radius:8px}.row{display:flex;justify-content:space-between;gap:20px;align-items:center}.price{font-size:20px;font-weight:700}.buy{padding:10px 18px;border:0;border-radius:7px;background:#16a34a;color:#fff;cursor:pointer}.notice{font-size:13px;color:#687181;margin-top:18px}
</style>
</head>
<body>
<div class="wrap"><div class="card">
<h1>Domain Reseller</h1><p class="muted">Search domains and connect this frontend to your reseller provider.</p>
<form class="search" id="searchForm"><input id="domain" placeholder="example.com" autocomplete="off" required><button>Search</button></form>
<div class="result" id="result"><div class="row"><div><strong id="domainName"></strong><div class="muted">Availability will come from the reseller API.</div></div><div><span class="price" id="price">—</span> <button class="buy" type="button" id="buy">Register</button></div></div></div>
<div class="notice">Provider credentials and registration calls belong on the server. Never expose API keys in this page.</div>
</div></div>
<script>
const form=document.getElementById('searchForm');const result=document.getElementById('result');const domain=document.getElementById('domain');const name=document.getElementById('domainName');
form.addEventListener('submit',async e=>{e.preventDefault();let d=domain.value.trim().toLowerCase();if(!d)return;name.textContent=d;document.getElementById('price').textContent='Checking…';result.style.display='block';try{const r=await fetch('api.php?action=check&domain='+encodeURIComponent(d));const x=await r.json();document.getElementById('price').textContent=x.price||'—';}catch(_){document.getElementById('price').textContent='API not connected';}});
document.getElementById('buy').onclick=()=>alert('Connect the payment + domain registration flow in api.php before enabling live orders.');
</script>
</body></html>