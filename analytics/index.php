<?php
declare(strict_types=1);
ini_set('display_errors','1');
ini_set('display_startup_errors','1');
error_reporting(E_ALL);
require_once $_SERVER['DOCUMENT_ROOT'].'/creator-ai/auth/config.php';
$pdo=db();
if(!$pdo instanceof PDO){http_response_code(500);exit('Database connection failed.');}
function e(mixed $v):string{return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
function safeCount(PDO $pdo,string $sql):int{try{return(int)$pdo->query($sql)->fetchColumn();}catch(Throwable $e){return 0;}}
$stats=[
 'visitors'=>safeCount($pdo,'SELECT COUNT(*) FROM analytics_visitors'),
 'pageviews'=>safeCount($pdo,'SELECT COUNT(*) FROM analytics_pageviews'),
 'live'=>safeCount($pdo,"SELECT COUNT(*) FROM analytics_live WHERE last_seen >= DATE_SUB(NOW(), INTERVAL 5 MINUTE)"),
 'events'=>safeCount($pdo,'SELECT COUNT(*) FROM analytics_events')
];
?><!doctype html>
<html lang="en"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Smart-Tooz Analytics</title><meta name="robots" content="noindex,nofollow"><style>*{box-sizing:border-box}body{margin:0;background:#f6f8fc;color:#172033;font-family:Inter,-apple-system,BlinkMacSystemFont,"Segoe UI",Arial,sans-serif}.wrap{width:min(1180px,calc(100% - 28px));margin:34px auto 70px}.hero{padding:28px;background:linear-gradient(135deg,#fff,#f0efff);border:1px solid #e5e9f0;border-radius:22px}.eyebrow{font-size:11px;font-weight:900;letter-spacing:1px;color:#635bff}.hero h1{margin:6px 0;font-size:clamp(30px,5vw,48px);letter-spacing:-1.8px}.hero p{margin:0;color:#707b8e;font-size:13px}.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin:18px 0}.stat{padding:16px;background:#fff;border:1px solid #e5e9f0;border-radius:15px}.stat small{display:block;color:#8a93a2;font-size:10px}.stat strong{display:block;margin-top:5px;color:#635bff;font-size:24px}.section-title{margin:24px 0 12px;font-size:16px}.links{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}.card{display:flex;align-items:center;gap:12px;padding:16px;background:#fff;border:1px solid #e5e9f0;border-radius:15px;text-decoration:none;color:#172033;transition:.18s}.card:hover{transform:translateY(-2px);border-color:#cfcaff;box-shadow:0 12px 28px rgba(30,35,80,.07)}.icon{width:44px;height:44px;flex:0 0 44px;display:grid;place-items:center;background:#f0efff;border-radius:11px;font-size:20px}.body{min-width:0;flex:1}.body strong{display:block;font-size:13px}.body span{display:block;margin-top:3px;color:#7b8493;font-size:10px;line-height:1.45}.arrow{color:#635bff;font-size:18px;font-weight:900}.footer{margin-top:22px;text-align:center;color:#9aa2af;font-size:10px}@media(max-width:700px){.stats{grid-template-columns:repeat(2,1fr)}.links{grid-template-columns:1fr}}@media(max-width:430px){.stats{grid-template-columns:1fr}}</style><link rel="stylesheet" href="/analytics/tabs.css?v=20260901-1">
</head><body><main class="wrap"><section class="hero"><div class="eyebrow">SMART-TOOZ ANALYTICS</div><h1>Analytics Control Center</h1><p>Quick access to every analytics module, tracker and realtime service.</p></section><section class="stats"><div class="stat"><small>Total Visitors</small><strong><?=number_format($stats['visitors'])?></strong></div><div class="stat"><small>Page Views</small><strong><?=number_format($stats['pageviews'])?></strong></div><div class="stat"><small>Live Now</small><strong><?=number_format($stats['live'])?></strong></div><div class="stat"><small>Events</small><strong><?=number_format($stats['events'])?></strong></div></section><h2 class="section-title">Analytics Modules</h2><?php require __DIR__.'/analytics-links.php'; ?><section class="links" style="margin-top:12px"><a class="card" href="/smart-toolz/admin/"><span class="icon">⚙️</span><span class="body"><strong>SmartToolz Admin</strong><span>Open the main administration panel.</span></span><span class="arrow">→</span></a><a class="card" href="/smart-toolz/ads.php"><span class="icon">📢</span><span class="body"><strong>Ads Settings</strong><span>Manage advertising settings and codes.</span></span><span class="arrow">→</span></a></section><div class="footer">Smart-Tooz Analytics · <?=date('Y')?></div></main><script src="/analytics/tabs.js?v=20260901-1"></script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
<script>
(function(){
  'use strict';
  const endpoint = '/analytics/live.php';
  const tableBody = document.querySelector('.live-table tbody');
  const liveStat = document.querySelector('.stat.live .stat-value');
  let busy = false;

  function esc(v){
    const d=document.createElement('div');
    d.textContent=v==null?'':String(v);
    return d.innerHTML;
  }

  function render(data){
    if(!data || !data.ok) return;
    if(liveStat) liveStat.textContent='● '+Number(data.count||0).toLocaleString();
    if(!tableBody) return;
    if(!data.visitors || !data.visitors.length){
      tableBody.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';
      return;
    }
    tableBody.innerHTML=data.visitors.map(function(row){
      const country=row.country_code||row.country||'Unknown';
      const city=row.city||'';
      return '<tr>'+
        '<td><span class="live-badge">LIVE</span></td>'+
        '<td>'+esc(country)+(city?'<br>'+esc(city):'')+'</td>'+
        '<td>'+esc(row.device||'Unknown')+'</td>'+
        '<td>'+esc(row.browser||'Unknown')+'</td>'+
        '<td class="path">'+esc(row.page_path||'-')+'</td>'+
        '<td>'+esc(row.last_seen||'-')+'</td>'+
      '</tr>';
    }).join('');
  }

  async function refresh(){
    if(busy) return;
    busy=true;
    try{
      const r=await fetch(endpoint+'?t='+Date.now(),{cache:'no-store',credentials:'same-origin',headers:{'Accept':'application/json'}});
      if(!r.ok) return;
      const data=await r.json();
      render(data);
    }catch(_){
      /* Keep the dashboard usable if the live endpoint is temporarily unavailable. */
    }finally{
      busy=false;
    }
  }

  refresh();
  setInterval(refresh,1000);
})();
</script>
</body></html>
