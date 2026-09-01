<?php
/* SmartToolz Admin Analytics - compact GA-style panel */
declare(strict_types=1);

$anQ = function(string $sql, array $params = []) use ($db): array {
    try { $q=$db->prepare($sql); $q->execute($params); return $q->fetchAll(PDO::FETCH_ASSOC); }
    catch (Throwable $e) { return []; }
};
$anOne = function(string $sql, array $params = []) use ($db): int {
    try { $q=$db->prepare($sql); $q->execute($params); return (int)$q->fetchColumn(); }
    catch (Throwable $e) { return 0; }
};

$anStats = [
  'users' => $anOne("SELECT COUNT(*) FROM creator_users"),
  'visitors7' => $anOne("SELECT COUNT(*) FROM analytics_visitors WHERE first_seen >= DATE_SUB(NOW(),INTERVAL 7 DAY)"),
  'views7' => $anOne("SELECT COUNT(*) FROM analytics_pageviews WHERE viewed_at >= DATE_SUB(NOW(),INTERVAL 7 DAY)"),
  'live' => $anOne("SELECT COUNT(*) FROM analytics_live WHERE last_seen >= DATE_SUB(NOW(),INTERVAL 2 MINUTE)"),
  'events7' => $anOne("SELECT COUNT(*) FROM analytics_events WHERE viewed_at >= DATE_SUB(NOW(),INTERVAL 7 DAY)"),
  'uses24' => $anOne("SELECT COUNT(*) FROM tool_usage WHERE created_at >= DATE_SUB(NOW(),INTERVAL 24 HOUR)"),
  'downloads24' => $anOne("SELECT COUNT(*) FROM download_tracking WHERE created_at >= DATE_SUB(NOW(),INTERVAL 24 HOUR)"),
];

$anHourly = $anQ("SELECT DATE_FORMAT(viewed_at,'%H:00') label, COUNT(*) total FROM analytics_pageviews WHERE viewed_at >= DATE_SUB(NOW(),INTERVAL 24 HOUR) GROUP BY HOUR(viewed_at),DATE(viewed_at),DATE_FORMAT(viewed_at,'%H:00') ORDER BY MIN(viewed_at)");
$anPages = $anQ("SELECT COALESCE(NULLIF(page_path,''),'Unknown') label, COUNT(*) total FROM analytics_pageviews WHERE viewed_at >= DATE_SUB(NOW(),INTERVAL 30 DAY) GROUP BY page_path ORDER BY total DESC LIMIT 12");
$anCountries = $anQ("SELECT COALESCE(NULLIF(country,''),'Unknown') label, COUNT(*) total FROM analytics_pageviews WHERE viewed_at >= DATE_SUB(NOW(),INTERVAL 30 DAY) GROUP BY country ORDER BY total DESC LIMIT 12");
$anCities = $anQ("SELECT COALESCE(NULLIF(city,''),'Unknown') label, COUNT(*) total FROM analytics_pageviews WHERE viewed_at >= DATE_SUB(NOW(),INTERVAL 30 DAY) GROUP BY city ORDER BY total DESC LIMIT 10");
$anDevices = $anQ("SELECT COALESCE(NULLIF(device,''),'Unknown') label, COUNT(*) total FROM analytics_pageviews WHERE viewed_at >= DATE_SUB(NOW(),INTERVAL 30 DAY) GROUP BY device ORDER BY total DESC LIMIT 10");
$anBrowsers = $anQ("SELECT COALESCE(NULLIF(browser,''),'Unknown') label, COUNT(*) total FROM analytics_pageviews WHERE viewed_at >= DATE_SUB(NOW(),INTERVAL 30 DAY) GROUP BY browser ORDER BY total DESC LIMIT 10");
$anTools = $anQ("SELECT COALESCE(NULLIF(tool_name,''),'Unknown') label, COUNT(*) total FROM tool_usage WHERE created_at >= DATE_SUB(NOW(),INTERVAL 30 DAY) GROUP BY tool_name ORDER BY total DESC LIMIT 12");
$anDownloads = $anQ("SELECT COALESCE(NULLIF(tool_name,''),'Unknown') label, COUNT(*) total FROM download_tracking WHERE created_at >= DATE_SUB(NOW(),INTERVAL 30 DAY) GROUP BY tool_name ORDER BY total DESC LIMIT 12");
$anEvents = $anQ("SELECT COALESCE(NULLIF(event_name,''),'Unknown') label, COUNT(*) total FROM analytics_events WHERE viewed_at >= DATE_SUB(NOW(),INTERVAL 30 DAY) GROUP BY event_name ORDER BY total DESC LIMIT 12");
$anLive = $anQ("SELECT visitor_id,country_code,city,device,browser,page_path,last_seen FROM analytics_live WHERE last_seen >= DATE_SUB(NOW(),INTERVAL 2 MINUTE) ORDER BY last_seen DESC LIMIT 100");
$anMaxHour = max(1,max(array_map(static fn($r)=>(int)$r['total'],$anHourly ?: [['total'=>1]])));
$anMaxPage = max(1,max(array_map(static fn($r)=>(int)$r['total'],$anPages ?: [['total'=>1]])));
$anMaxCountry = max(1,max(array_map(static fn($r)=>(int)$r['total'],$anCountries ?: [['total'=>1]])));
$anMaxDevice = max(1,max(array_map(static fn($r)=>(int)$r['total'],$anDevices ?: [['total'=>1]])));
$anMaxTool = max(1,max(array_map(static fn($r)=>(int)$r['total'],$anTools ?: [['total'=>1]])));
?>
<style>
.sa-shell{margin-top:18px;background:#fff;border:1px solid #e3e7ef;border-radius:18px;padding:14px}.sa-head{display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:12px}.sa-title{font-size:20px;font-weight:900}.sa-sub{font-size:10px;color:#798397;margin-top:3px}.sa-live-pill{display:inline-flex;align-items:center;gap:6px;padding:7px 10px;border-radius:999px;background:#eafaf1;color:#137548;font-size:10px;font-weight:900}.sa-live-pill i{width:7px;height:7px;border-radius:50%;background:#17a45b;box-shadow:0 0 0 4px #17a45b22}.sa-tabs{display:flex;gap:6px;overflow:auto;padding-bottom:10px;border-bottom:1px solid #edf0f4}.sa-tab{border:1px solid #e0e4ec;background:#fff;color:#5b6679;border-radius:10px;padding:9px 11px;font-size:10px;font-weight:850;white-space:nowrap;cursor:pointer}.sa-tab.active{background:#635bff;border-color:#635bff;color:#fff}.sa-pane{display:none;padding-top:12px}.sa-pane.active{display:block}.sa-kpis{display:grid;grid-template-columns:repeat(6,1fr);gap:8px}.sa-kpi{border:1px solid #e8ebf1;border-radius:12px;padding:12px;background:linear-gradient(135deg,#fff,#fafbff)}.sa-kpi b{display:block;font-size:20px;line-height:1.1}.sa-kpi span{display:block;margin-top:5px;font-size:9px;color:#7c8698}.sa-grid2{display:grid;grid-template-columns:1.5fr 1fr;gap:10px;margin-top:10px}.sa-card{border:1px solid #e8ebf1;border-radius:14px;padding:13px;background:#fff}.sa-card h3{margin:0 0 10px;font-size:12px}.sa-chart{display:flex;align-items:flex-end;gap:5px;height:190px;padding:8px 5px 0;border:1px solid #edf0f4;border-radius:11px;background:#fafbfe}.sa-bar-col{flex:1;height:100%;display:flex;align-items:flex-end}.sa-bar{width:100%;min-height:3px;border-radius:5px 5px 1px 1px;background:#635bff}.sa-bars{display:grid;gap:7px}.sa-row{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:10px;align-items:center;font-size:10px}.sa-row .name{overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#566174}.sa-row b{font-size:10px}.sa-track{height:6px;border-radius:20px;background:#edf0f4;overflow:hidden;margin-top:4px}.sa-fill{height:100%;border-radius:20px;background:#635bff}.sa-multi{display:grid;grid-template-columns:repeat(2,1fr);gap:10px}.sa-map{height:245px;position:relative;border:1px solid #e5e9ef;border-radius:13px;background:radial-gradient(circle at 25% 36%,#dfe4ff 0 2px,transparent 3px),radial-gradient(circle at 51% 47%,#dfe4ff 0 2px,transparent 3px),radial-gradient(circle at 73% 34%,#dfe4ff 0 2px,transparent 3px),linear-gradient(180deg,#f8faff,#eef3fa);overflow:hidden}.sa-map:before{content:'🌍';position:absolute;inset:0;display:grid;place-items:center;font-size:98px;opacity:.14}.sa-map-label{position:absolute;left:10px;bottom:9px;padding:6px 8px;background:#ffffffdc;border:1px solid #e7ebf0;border-radius:8px;font-size:9px;color:#596477}.sa-table-wrap{max-height:380px;overflow:auto;border:1px solid #edf0f4;border-radius:11px}.sa-table{width:100%;border-collapse:collapse;font-size:10px}.sa-table th,.sa-table td{padding:8px;border-bottom:1px solid #edf0f4;text-align:left;vertical-align:top}.sa-table th{background:#fafbfe;position:sticky;top:0;color:#596477;z-index:1}.sa-dot{display:inline-flex;align-items:center;gap:5px;color:#168551;font-size:9px;font-weight:900}.sa-dot:before{content:'';width:6px;height:6px;border-radius:50%;background:#17a45b}.sa-actions{display:flex;gap:6px;flex-wrap:wrap;margin-top:10px}.sa-link{display:inline-flex;padding:8px 10px;border-radius:9px;background:#eef0f5;color:#465166;font-size:9px;font-weight:900}.sa-link.primary{background:#635bff;color:#fff}.sa-filter{display:flex;gap:7px;align-items:center;justify-content:space-between;margin-bottom:9px}.sa-filter select,.sa-filter input{width:auto;min-width:130px;padding:7px 9px;border:1px solid #dfe4eb;border-radius:9px;font-size:10px}.sa-empty{padding:20px;text-align:center;color:#8992a2;font-size:10px}.sa-funnel{display:grid;gap:7px}.sa-funnel-step{display:grid;grid-template-columns:110px 1fr 55px;gap:8px;align-items:center;font-size:9px}.sa-funnel-bar{height:17px;background:#eef0f5;border-radius:6px;overflow:hidden}.sa-funnel-fill{height:100%;background:#635bff}.sa-note{font-size:9px;color:#7b8697;line-height:1.55;padding:9px 10px;background:#f8f9fc;border:1px solid #e9ecf1;border-radius:10px}.sa-search{margin-bottom:8px}.sa-search input{width:100%;box-sizing:border-box}
@media(max-width:1100px){.sa-kpis{grid-template-columns:repeat(3,1fr)}.sa-grid2{grid-template-columns:1fr}.sa-multi{grid-template-columns:1fr}}@media(max-width:650px){.sa-kpis{grid-template-columns:repeat(2,1fr)}.sa-head{align-items:flex-start;flex-direction:column}.sa-table th:nth-child(3),.sa-table td:nth-child(3),.sa-table th:nth-child(4),.sa-table td:nth-child(4){display:none}}
</style>
<div id="smart-analytics" class="sa-shell">
  <div class="sa-head">
    <div><div class="sa-title">Analytics Command Center</div><div class="sa-sub">GA-style reporting for SmartToolz · realtime data without page reload</div></div>
    <span class="sa-live-pill"><i></i> LIVE <span id="saLiveCount"><?=number_format($anStats['live'])?></span></span>
  </div>
  <div class="sa-tabs" role="tablist">
    <button class="sa-tab active" type="button" data-sa="overview">Overview</button>
    <button class="sa-tab" type="button" data-sa="realtime">Realtime</button>
    <button class="sa-tab" type="button" data-sa="traffic">Traffic</button>
    <button class="sa-tab" type="button" data-sa="acquisition">Acquisition</button>
    <button class="sa-tab" type="button" data-sa="content">Content</button>
    <button class="sa-tab" type="button" data-sa="geo">Geo</button>
    <button class="sa-tab" type="button" data-sa="tech">Technology</button>
    <button class="sa-tab" type="button" data-sa="tools">Tools</button>
    <button class="sa-tab" type="button" data-sa="downloads">Downloads</button>
    <button class="sa-tab" type="button" data-sa="events">Events</button>
  </div>

  <section class="sa-pane active" data-sa-pane="overview">
    <div class="sa-kpis">
      <div class="sa-kpi"><b><?=number_format($anStats['live'])?></b><span>Live now</span></div>
      <div class="sa-kpi"><b><?=number_format($anStats['visitors7'])?></b><span>Visitors · 7 days</span></div>
      <div class="sa-kpi"><b><?=number_format($anStats['views7'])?></b><span>Page views · 7 days</span></div>
      <div class="sa-kpi"><b><?=number_format($anStats['uses24'])?></b><span>Tool uses · 24h</span></div>
      <div class="sa-kpi"><b><?=number_format($anStats['downloads24'])?></b><span>Downloads · 24h</span></div>
      <div class="sa-kpi"><b><?=number_format($anStats['events7'])?></b><span>Events · 7 days</span></div>
    </div>
    <div class="sa-grid2">
      <div class="sa-card"><h3>Traffic trend · last 24 hours</h3><div class="sa-chart"><?php foreach($anHourly as $r): ?><div class="sa-bar-col" title="<?=ae($r['label'])?> · <?=number_format((int)$r['total'])?>"><div class="sa-bar" style="height:<?=max(3,((int)$r['total']/$anMaxHour)*100)?>%"></div></div><?php endforeach; ?><?php if(!$anHourly): ?><div class="sa-empty">No traffic data yet.</div><?php endif; ?></div></div>
      <div class="sa-card"><h3>At a glance</h3><div class="sa-note">Users: <b><?=number_format($anStats['users'])?></b><br>Live window: <b>2 minutes</b><br>Auto refresh: <b>1 second</b><br>Data windows: <b>24h / 7d / 30d</b></div><div class="sa-actions"><a class="sa-link primary" href="/analytics/" target="_blank">Open Full Analytics</a><a class="sa-link" href="/analytics/advanced.php" target="_blank">Advanced Report</a></div></div>
    </div>
  </section>

  <section class="sa-pane" data-sa-pane="realtime">
    <div class="sa-card"><div class="sa-filter"><h3 style="margin:0">Realtime visitors</h3><span class="sa-sub">updated <b id="saLastUpdate">—</b></span></div><div class="sa-search"><input id="saSearch" type="search" placeholder="Search page, city, country, device or browser..."></div><div class="sa-table-wrap"><table class="sa-table"><thead><tr><th>Status</th><th>Location</th><th>Device</th><th>Browser</th><th>Current page</th><th>Last seen</th></tr></thead><tbody id="saLiveRows"><?php foreach($anLive as $r): ?><tr><td><span class="sa-dot">LIVE</span></td><td><?=ae(($r['country_code']??'Unknown').' '.($r['city']??''))?></td><td><?=ae($r['device']??'Unknown')?></td><td><?=ae($r['browser']??'Unknown')?></td><td><?=ae($r['page_path']??'-')?></td><td><?=ae($r['last_seen']??'-')?></td></tr><?php endforeach; ?></tbody></table><?php if(!$anLive): ?><div id="saEmpty" class="sa-empty">No live visitors right now.</div><?php endif; ?></div></div>
  </section>

  <section class="sa-pane" data-sa-pane="traffic"><div class="sa-grid2"><div class="sa-card"><h3>Hourly page views</h3><div class="sa-chart"><?php foreach($anHourly as $r): ?><div class="sa-bar-col" title="<?=ae($r['label'])?>"><div class="sa-bar" style="height:<?=max(3,((int)$r['total']/$anMaxHour)*100)?>%"></div></div><?php endforeach; ?></div></div><div class="sa-card"><h3>Traffic summary</h3><div class="sa-bars"><div class="sa-row"><span class="name">Page views · 7d</span><b><?=number_format($anStats['views7'])?></b></div><div class="sa-row"><span class="name">Visitors · 7d</span><b><?=number_format($anStats['visitors7'])?></b></div><div class="sa-row"><span class="name">Events · 7d</span><b><?=number_format($anStats['events7'])?></b></div><div class="sa-row"><span class="name">Downloads · 24h</span><b><?=number_format($anStats['downloads24'])?></b></div></div></div></div></section>

  <section class="sa-pane" data-sa-pane="acquisition"><div class="sa-grid2"><div class="sa-card"><h3>Top landing/content pages</h3><div class="sa-bars"><?php foreach($anPages as $r): ?><div><div class="sa-row"><span class="name"><?=ae($r['label'])?></span><b><?=number_format((int)$r['total'])?></b></div><div class="sa-track"><div class="sa-fill" style="width:<?=((int)$r['total']/$anMaxPage*100)?>%"></div></div></div><?php endforeach; ?><?php if(!$anPages): ?><div class="sa-empty">No page data.</div><?php endif; ?></div></div><div class="sa-card"><h3>Acquisition signals</h3><div class="sa-note">This view uses the traffic fields currently captured by SmartToolz. For source/medium/campaign reporting, the tracker must receive those values from the page URL or referrer.</div><div class="sa-actions"><a class="sa-link primary" href="/analytics/" target="_blank">Detailed reports</a></div></div></div></section>

  <section class="sa-pane" data-sa-pane="content"><div class="sa-card"><h3>Most viewed pages · 30 days</h3><div class="sa-bars"><?php foreach($anPages as $r): ?><div><div class="sa-row"><span class="name"><?=ae($r['label'])?></span><b><?=number_format((int)$r['total'])?></b></div><div class="sa-track"><div class="sa-fill" style="width:<?=((int)$r['total']/$anMaxPage*100)?>%"></div></div></div><?php endforeach; ?></div></div></section>

  <section class="sa-pane" data-sa-pane="geo"><div class="sa-grid2"><div class="sa-card"><h3>Visitor map</h3><div class="sa-map"><div class="sa-map-label">30-day distribution · live markers update separately</div></div></div><div class="sa-multi"><div class="sa-card"><h3>Countries</h3><div class="sa-bars"><?php foreach($anCountries as $r): ?><div><div class="sa-row"><span class="name"><?=ae($r['label'])?></span><b><?=number_format((int)$r['total'])?></b></div><div class="sa-track"><div class="sa-fill" style="width:<?=((int)$r['total']/$anMaxCountry*100)?>%"></div></div></div><?php endforeach; ?></div></div><div class="sa-card"><h3>Cities</h3><div class="sa-bars"><?php foreach($anCities as $r): ?><div class="sa-row"><span class="name"><?=ae($r['label'])?></span><b><?=number_format((int)$r['total'])?></b></div><?php endforeach; ?></div></div></div></div></section>

  <section class="sa-pane" data-sa-pane="tech"><div class="sa-multi"><div class="sa-card"><h3>Devices</h3><div class="sa-bars"><?php foreach($anDevices as $r): ?><div><div class="sa-row"><span class="name"><?=ae($r['label'])?></span><b><?=number_format((int)$r['total'])?></b></div><div class="sa-track"><div class="sa-fill" style="width:<?=((int)$r['total']/$anMaxDevice*100)?>%"></div></div></div><?php endforeach; ?></div></div><div class="sa-card"><h3>Browsers</h3><div class="sa-bars"><?php foreach($anBrowsers as $r): ?><div class="sa-row"><span class="name"><?=ae($r['label'])?></span><b><?=number_format((int)$r['total'])?></b></div><?php endforeach; ?></div></div></div></section>

  <section class="sa-pane" data-sa-pane="tools"><div class="sa-card"><h3>Tool usage · 30 days</h3><div class="sa-bars"><?php foreach($anTools as $r): ?><div><div class="sa-row"><span class="name"><?=ae($r['label'])?></span><b><?=number_format((int)$r['total'])?></b></div><div class="sa-track"><div class="sa-fill" style="width:<?=((int)$r['total']/$anMaxTool*100)?>%"></div></div></div><?php endforeach; ?><?php if(!$anTools): ?><div class="sa-empty">No tool usage data.</div><?php endif; ?></div></div></section>

  <section class="sa-pane" data-sa-pane="downloads"><div class="sa-card"><h3>Downloads · 30 days</h3><div class="sa-bars"><?php $anMaxDl=max(1,max(array_map(static fn($r)=>(int)$r['total'],$anDownloads ?: [['total'=>1]]))); foreach($anDownloads as $r): ?><div><div class="sa-row"><span class="name"><?=ae($r['label'])?></span><b><?=number_format((int)$r['total'])?></b></div><div class="sa-track"><div class="sa-fill" style="width:<?=((int)$r['total']/$anMaxDl*100)?>%"></div></div></div><?php endforeach; ?><?php if(!$anDownloads): ?><div class="sa-empty">No download data.</div><?php endif; ?></div><div class="sa-actions"><a class="sa-link" href="/smart-toolz/admin/tracking.php">Open Download Tracking</a></div></div></section>

  <section class="sa-pane" data-sa-pane="events"><div class="sa-card"><h3>Events · 30 days</h3><div class="sa-bars"><?php $anMaxEv=max(1,max(array_map(static fn($r)=>(int)$r['total'],$anEvents ?: [['total'=>1]]))); foreach($anEvents as $r): ?><div><div class="sa-row"><span class="name"><?=ae($r['label'])?></span><b><?=number_format((int)$r['total'])?></b></div><div class="sa-track"><div class="sa-fill" style="width:<?=((int)$r['total']/$anMaxEv*100)?>%"></div></div></div><?php endforeach; ?><?php if(!$anEvents): ?><div class="sa-empty">No event data.</div><?php endif; ?></div></div></section>

  <div class="sa-actions"><a class="sa-link primary" href="/analytics/">Full Analytics</a><a class="sa-link" href="/analytics/advanced.php">Advanced</a><a class="sa-link" href="/analytics/live.php" target="_blank">Realtime API</a><a class="sa-link" href="/analytics/resolve-geo.php">Resolve Geo</a><a class="sa-link" href="?tab=dashboard">Dashboard</a></div>
</div>
<script>
(()=>{
 const root=document.getElementById('smart-analytics');if(!root)return;
 const tabs=[...root.querySelectorAll('.sa-tab')],panes=[...root.querySelectorAll('.sa-pane')];
 tabs.forEach(t=>t.addEventListener('click',()=>{tabs.forEach(x=>x.classList.remove('active'));panes.forEach(x=>x.classList.remove('active'));t.classList.add('active');root.querySelector('[data-sa-pane="'+t.dataset.sa+'"]').classList.add('active')}));
 const rows=root.querySelector('#saLiveRows'), badge=root.querySelector('#saLiveCount'), updated=root.querySelector('#saLastUpdate'), search=root.querySelector('#saSearch');let liveCache=[];
 const esc=v=>String(v??'').replace(/[&<>"']/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[m]));
 const draw=list=>{rows.innerHTML=list.map(r=>`<tr><td><span class="sa-dot">LIVE</span></td><td>${esc((r.country_code||'Unknown')+' '+(r.city||''))}</td><td>${esc(r.device||'Unknown')}</td><td>${esc(r.browser||'Unknown')}</td><td>${esc(r.page_path||'-')}</td><td>${esc(r.last_seen||'-')}</td></tr>`).join('')||'';badge.textContent=list.length;updated.textContent=new Date().toLocaleTimeString();};
 const load=async()=>{try{const r=await fetch('/analytics/live.php?_='+Date.now(),{cache:'no-store',credentials:'same-origin'});const d=await r.json();if(!d.ok)return;liveCache=d.visitors||[];const q=(search?.value||'').trim().toLowerCase();draw(q?liveCache.filter(x=>JSON.stringify(x).toLowerCase().includes(q)):liveCache);}catch(e){}};
 search?.addEventListener('input',()=>{const q=search.value.trim().toLowerCase();draw(q?liveCache.filter(x=>JSON.stringify(x).toLowerCase().includes(q)):liveCache)});
 load();setInterval(load,1000);
})();
</script>
