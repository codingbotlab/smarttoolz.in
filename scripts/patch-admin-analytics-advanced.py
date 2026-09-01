from pathlib import Path
import re

p = Path('smart-toolz/admin/index.php')
s = p.read_text(encoding='utf-8')
marker = 'id="st-advanced-analytics"'
if marker in s:
    print('Advanced analytics already installed')
    raise SystemExit(0)

start = "<?php elseif($tab==='analytics'): ?>"
end = "<?php elseif($tab==='ads'): ?>"
a = s.find(start)
b = s.find(end, a)
if a < 0 or b < 0 or b <= a:
    raise SystemExit('Analytics section markers not found')

advanced = r'''<?php elseif($tab==='analytics'): ?>
<?php
$aaRows = function(string $sql, array $params = []) use ($db): array {
    try { $q=$db->prepare($sql); $q->execute($params); return $q->fetchAll(PDO::FETCH_ASSOC); }
    catch(Throwable $e){ return []; }
};
$aaCount = function(string $sql, array $params = []) use ($db): int {
    try { $q=$db->prepare($sql); $q->execute($params); return (int)$q->fetchColumn(); }
    catch(Throwable $e){ return 0; }
};
$aaPages = $aaRows("SELECT page_path, COUNT(*) total FROM analytics_pageviews WHERE viewed_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY page_path ORDER BY total DESC LIMIT 8");
$aaCountries = $aaRows("SELECT COALESCE(NULLIF(country,''),'Unknown') label, COUNT(*) total FROM analytics_pageviews WHERE viewed_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY country ORDER BY total DESC LIMIT 10");
$aaDevices = $aaRows("SELECT COALESCE(NULLIF(device,''),'Unknown') label, COUNT(*) total FROM analytics_pageviews WHERE viewed_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY device ORDER BY total DESC LIMIT 8");
$aaBrowsers = $aaRows("SELECT COALESCE(NULLIF(browser,''),'Unknown') label, COUNT(*) total FROM analytics_pageviews WHERE viewed_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY browser ORDER BY total DESC LIMIT 8");
$aaHourly = $aaRows("SELECT DATE_FORMAT(viewed_at,'%H:00') label, COUNT(*) total FROM analytics_pageviews WHERE viewed_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR) GROUP BY HOUR(viewed_at) ORDER BY HOUR(viewed_at)");
$aaLive = $aaRows("SELECT visitor_id,country_code,city,device,browser,page_path,last_seen FROM analytics_live WHERE last_seen >= DATE_SUB(NOW(),INTERVAL 5 MINUTE) ORDER BY last_seen DESC LIMIT 50");
$aaDownloads = $aaCount("SELECT COUNT(*) FROM download_tracking WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)");
$aaUses = $aaCount("SELECT COUNT(*) FROM tool_usage WHERE created_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR)");
$maxHour = max(1, max(array_map(fn($r)=>(int)$r['total'],$aaHourly ?: [['total'=>1]])));
?>
<style>
#st-advanced-analytics{margin-top:18px}.st-tabs{display:flex;gap:7px;flex-wrap:wrap;margin-bottom:14px}.st-tab{border:1px solid #e0e4ec;background:#fff;color:#596477;padding:9px 12px;border-radius:10px;font-size:11px;font-weight:800;cursor:pointer}.st-tab.active{background:#635bff;color:#fff;border-color:#635bff}.st-pane{display:none}.st-pane.active{display:block}.st-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:10px}.st-mini{padding:13px;border:1px solid #e5e9f0;background:#fff;border-radius:12px}.st-mini b{display:block;font-size:20px}.st-mini span{font-size:10px;color:#788397}.st-cols{display:grid;grid-template-columns:1.2fr 1fr;gap:14px;margin-top:14px}.st-chart{display:flex;align-items:end;gap:6px;height:150px;padding:12px 7px 4px;background:#f8f9fd;border:1px solid #e8ebf1;border-radius:12px}.st-col{flex:1;min-width:8px;display:flex;align-items:end;height:100%}.st-bar{width:100%;background:#635bff;border-radius:5px 5px 2px 2px;min-height:3px}.st-bars{display:grid;gap:7px}.st-line{display:flex;justify-content:space-between;font-size:10px;color:#596477}.st-track{height:7px;background:#edf0f4;border-radius:99px;overflow:hidden;margin-top:3px}.st-fill{height:100%;background:#635bff}.st-map{position:relative;min-height:230px;background:linear-gradient(180deg,#f7f9ff,#eef2fb);border:1px solid #e2e7f0;border-radius:14px;overflow:hidden}.st-map:before{content:'🌍';position:absolute;inset:0;display:grid;place-items:center;font-size:105px;opacity:.14}.st-map-inner{position:relative;z-index:1;padding:14px}.st-live{max-height:420px;overflow:auto}.st-live-row{display:grid;grid-template-columns:62px 1fr 1fr 1fr 1.4fr 120px;gap:7px;padding:8px 5px;border-bottom:1px solid #edf0f4;font-size:10px;align-items:center}.st-live-head{font-weight:900;color:#596477;background:#fafbfe;position:sticky;top:0}.st-dot{display:inline-flex;align-items:center;gap:5px;color:#168b51;font-weight:900}.st-dot:before{content:'';width:7px;height:7px;border-radius:50%;background:#19a463}.st-links{display:grid;grid-template-columns:repeat(4,1fr);gap:8px}.st-link{display:block;padding:11px;border:1px solid #e3e7ef;border-radius:10px;background:#fff;font-size:11px;font-weight:800;color:#475268}.st-link:hover{border-color:#635bff;color:#635bff}.st-note{padding:10px 12px;background:#f7f8fc;border:1px solid #e7eaf0;border-radius:11px;color:#677287;font-size:10px;line-height:1.5}.st-search{margin-bottom:8px}.st-search input{max-width:360px}.st-kicker{font-size:10px;color:#8992a2;margin-top:3px}@media(max-width:1000px){.st-grid{grid-template-columns:repeat(2,1fr)}.st-cols{grid-template-columns:1fr}.st-links{grid-template-columns:repeat(2,1fr)}}@media(max-width:600px){.st-grid{grid-template-columns:1fr}.st-links{grid-template-columns:1fr}.st-live-row{grid-template-columns:55px 1fr 1fr 1fr}.st-live-row>*:nth-child(n+5){display:none}}
</style>
<div id="st-advanced-analytics" class="panel">
  <div class="st-note"><b>Advanced Analytics</b> · compact admin view. Live visitors refresh every second without reloading this page.</div>
  <div class="st-tabs" role="tablist" aria-label="Analytics sections">
    <button class="st-tab active" data-st-tab="overview" type="button">Overview</button>
    <button class="st-tab" data-st-tab="traffic" type="button">Traffic</button>
    <button class="st-tab" data-st-tab="geo" type="button">Geo Map</button>
    <button class="st-tab" data-st-tab="tech" type="button">Technology</button>
    <button class="st-tab" data-st-tab="live" type="button">Live <span id="stLiveBadge"><?=count($aaLive)?></span></button>
    <button class="st-tab" data-st-tab="tools" type="button">Tools</button>
  </div>

  <section class="st-pane active" data-st-pane="overview">
    <div class="st-grid">
      <div class="st-mini"><b><?=$analytics['Visitors (7d)']??0?></b><span>Visitors · 7 days</span></div>
      <div class="st-mini"><b><?=$analytics['Page Views (7d)']??0?></b><span>Page views · 7 days</span></div>
      <div class="st-mini"><b><?=count($aaLive)?></b><span>Live right now</span></div>
      <div class="st-mini"><b><?=$aaUses?></b><span>Tool uses · 24h</span></div>
    </div>
    <div class="st-cols">
      <div class="panel" style="margin-top:0"><h2>Top Pages</h2><div class="st-bars"><?php $m=max(1,max(array_map(fn($r)=>(int)$r['total'],$aaPages ?: [['total'=>1]]))); foreach($aaPages as $r): ?><div><div class="st-line"><span><?=ae($r['page_path'])?></span><b><?=number_format((int)$r['total'])?></b></div><div class="st-track"><div class="st-fill" style="width:<?=((int)$r['total']/$m*100)?>%"></div></div></div><?php endforeach; ?></div></div>
      <div class="panel" style="margin-top:0"><h2>24h Snapshot</h2><div class="st-note">Downloads: <b><?=$aaDownloads?></b><br>Tool uses: <b><?=$aaUses?></b><br>Live window: <b>5 minutes</b></div></div>
    </div>
  </section>

  <section class="st-pane" data-st-pane="traffic">
    <div class="panel" style="margin-top:0"><h2>Hourly Traffic · Last 24 Hours</h2><div class="st-chart"><?php foreach($aaHourly as $r): ?><div class="st-col" title="<?=ae($r['label'])?> · <?=number_format((int)$r['total'])?>"><div class="st-bar" style="height:<?=max(3,((int)$r['total']/$maxHour)*100)?>%"></div></div><?php endforeach; ?></div><div class="st-kicker">Bar height = page views in that hour.</div></div>
  </section>

  <section class="st-pane" data-st-pane="geo">
    <div class="st-cols">
      <div class="panel" style="margin-top:0"><h2>Geo Map</h2><div class="st-map"><div class="st-map-inner"><b>Visitor distribution</b><div class="st-kicker">Top countries</div><?php foreach($aaCountries as $r): ?><div style="margin-top:8px"><div class="st-line"><span><?=ae($r['label'])?></span><b><?=number_format((int)$r['total'])?></b></div><div class="st-track"><div class="st-fill" style="width:<?=((int)$r['total']/max(1,(int)($aaCountries[0]['total']??1))*100)?>%"></div></div></div><?php endforeach; ?></div></div></div>
      <div class="panel" style="margin-top:0"><h2>Top Cities</h2><div class="st-bars"><?php $aaCities=$aaRows("SELECT COALESCE(NULLIF(city,''),'Unknown') label, COUNT(*) total FROM analytics_pageviews WHERE viewed_at >= DATE_SUB(NOW(),INTERVAL 30 DAY) GROUP BY city ORDER BY total DESC LIMIT 10"); foreach($aaCities as $r): ?><div class="st-line"><span><?=ae($r['label'])?></span><b><?=number_format((int)$r['total'])?></b></div><?php endforeach; ?></div></div>
    </div>
  </section>

  <section class="st-pane" data-st-pane="tech">
    <div class="st-cols">
      <div class="panel" style="margin-top:0"><h2>Devices</h2><div class="st-bars"><?php $m=max(1,(int)($aaDevices[0]['total']??1)); foreach($aaDevices as $r): ?><div><div class="st-line"><span><?=ae($r['label'])?></span><b><?=number_format((int)$r['total'])?></b></div><div class="st-track"><div class="st-fill" style="width:<?=((int)$r['total']/$m*100)?>%"></div></div></div><?php endforeach; ?></div></div>
      <div class="panel" style="margin-top:0"><h2>Browsers</h2><div class="st-bars"><?php foreach($aaBrowsers as $r): ?><div class="st-line"><span><?=ae($r['label'])?></span><b><?=number_format((int)$r['total'])?></b></div><?php endforeach; ?></div></div>
    </div>
  </section>

  <section class="st-pane" data-st-pane="live">
    <div class="st-search"><input id="stLiveSearch" type="search" placeholder="Search page, country, city, browser..."></div>
    <div class="st-live panel" style="margin-top:0"><div class="st-live-row st-live-head"><span>Status</span><span>Location</span><span>Device</span><span>Browser</span><span>Current Page</span><span>Last Seen</span></div><div id="stLiveRows"><?php foreach($aaLive as $r): ?><div class="st-live-row"><span class="st-dot">LIVE</span><span><?=ae(($r['country_code']??'Unknown').' '.($r['city']??''))?></span><span><?=ae($r['device']??'Unknown')?></span><span><?=ae($r['browser']??'Unknown')?></span><span><?=ae($r['page_path']??'-')?></span><span><?=ae($r['last_seen']??'-')?></span></div><?php endforeach; ?></div></div>
  </section>

  <section class="st-pane" data-st-pane="tools">
    <div class="st-links">
      <a class="st-link" href="/analytics/">📊 Full Analytics</a>
      <a class="st-link" href="/analytics/advanced.php">🚀 Advanced Reports</a>
      <a class="st-link" href="/analytics/live.php" target="_blank">🟢 Live API</a>
      <a class="st-link" href="/smart-toolz/admin/?tab=analytics">🔄 Refresh Analytics</a>
      <a class="st-link" href="/smart-toolz/admin/?tab=users">👥 Users & Roles</a>
      <a class="st-link" href="/smart-toolz/admin/?tab=settings">⚙️ DB Settings</a>
      <a class="st-link" href="/smart-toolz/admin/?tab=ads">📢 Ads Settings</a>
      <a class="st-link" href="/smart-toolz/admin/">🏠 Admin Dashboard</a>
    </div>
  </section>
</div>
<script>
(()=>{
 const root=document.getElementById('st-advanced-analytics'); if(!root)return;
 root.querySelectorAll('.st-tab').forEach(btn=>btn.addEventListener('click',()=>{root.querySelectorAll('.st-tab').forEach(x=>x.classList.remove('active'));root.querySelectorAll('.st-pane').forEach(x=>x.classList.remove('active'));btn.classList.add('active');root.querySelector('[data-st-pane="'+btn.dataset.stTab+'"]').classList.add('active')}));
 const search=document.getElementById('stLiveSearch'), rows=document.getElementById('stLiveRows');
 function render(list){rows.innerHTML=list.map(r=>`<div class="st-live-row"><span class="st-dot">LIVE</span><span>${esc((r.country_code||'Unknown')+' '+(r.city||''))}</span><span>${esc(r.device||'Unknown')}</span><span>${esc(r.browser||'Unknown')}</span><span>${esc(r.page_path||'-')}</span><span>${esc(r.last_seen||'-')}</span></div>`).join('')||'<div class="st-note">No live visitors.</div>';document.getElementById('stLiveBadge').textContent=list.length}
 const esc=s=>String(s??'').replace(/[&<>\"]/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;','\\':'&#92;'}[m]));
 let cache=[]; search?.addEventListener('input',()=>{const q=search.value.toLowerCase();render(cache.filter(r=>(JSON.stringify(r)).toLowerCase().includes(q)))});
 async function live(){try{const r=await fetch('/analytics/live.php?_='+Date.now(),{cache:'no-store'});const d=await r.json();if(d.ok){cache=d.visitors||[];if(!search.value)render(cache);else search.dispatchEvent(new Event('input'))}}catch(e){}}
 live();setInterval(live,1000);
})();
</script>
'''

s = s[:a] + advanced + s[b:]
p.write_text(s, encoding='utf-8')
print('Patched admin analytics')
