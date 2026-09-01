<?php
declare(strict_types=1);

ini_set('display_errors','1');
ini_set('display_startup_errors','1');
error_reporting(E_ALL);

require_once $_SERVER['DOCUMENT_ROOT'].'/creator-ai/auth/config.php';
$pdo=db();
if(!$pdo instanceof PDO){http_response_code(500);exit('Database connection failed.');}

function e(mixed $v):string{return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
function tableExists(PDO $pdo,string $table):bool{try{$s=$pdo->prepare("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name=?");$s->execute([$table]);return (int)$s->fetchColumn()>0;}catch(Throwable $e){return false;}}
function columnExists(PDO $pdo,string $table,string $col):bool{try{$s=$pdo->prepare("SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name=? AND column_name=?");$s->execute([$table,$col]);return (int)$s->fetchColumn()>0;}catch(Throwable $e){return false;}}
function rows(PDO $pdo,string $sql,array $p=[]):array{try{$s=$pdo->prepare($sql);$s->execute($p);return $s->fetchAll(PDO::FETCH_ASSOC);}catch(Throwable $e){return[];}}
function countv(PDO $pdo,string $sql,array $p=[]):int{try{$s=$pdo->prepare($sql);$s->execute($p);return (int)$s->fetchColumn();}catch(Throwable $e){return 0;}}

$hasV=tableExists($pdo,'analytics_visitors');
$hasP=tableExists($pdo,'analytics_pageviews');
$hasL=tableExists($pdo,'analytics_live');

/* JSON live endpoint: /analytics/advanced.php?live=1 */
if(isset($_GET['live'])&&$_GET['live']==='1'){
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    $live=$hasL?rows($pdo,"SELECT visitor_id,session_id,page_path,page_url,device,os,browser,country,country_code,city,traffic_source,last_seen FROM analytics_live WHERE last_seen>=DATE_SUB(NOW(),INTERVAL 5 MINUTE) ORDER BY last_seen DESC LIMIT 100"):[];
    echo json_encode(['ok'=>true,'server_time'=>date('Y-m-d H:i:s'),'count'=>count($live),'visitors'=>$live],JSON_UNESCAPED_SLASHES);exit;
}

/* CSV export: /analytics/advanced.php?export=pageviews&days=30 */
if(isset($_GET['export'])&&$_GET['export']==='pageviews'&&$hasP){
    $days=max(1,min(365,(int)($_GET['days']??30)));
    $data=rows($pdo,"SELECT viewed_at,visitor_id,session_id,page_path,page_url,referrer,traffic_source,device,os,browser,country,country_code,city,ip_address FROM analytics_pageviews WHERE viewed_at>=DATE_SUB(NOW(),INTERVAL {$days} DAY) ORDER BY viewed_at DESC LIMIT 50000");
    header('Content-Type: text/csv; charset=utf-8');header('Content-Disposition: attachment; filename=smarttoolz-pageviews-'.$days.'d.csv');
    $out=fopen('php://output','wb');fputcsv($out,['viewed_at','visitor_id','session_id','page_path','page_url','referrer','traffic_source','device','os','browser','country','country_code','city','ip_address']);foreach($data as $r)fputcsv($out,$r);fclose($out);exit;
}

$days=max(1,min(365,(int)($_GET['days']??7)));
$where="WHERE viewed_at>=DATE_SUB(NOW(),INTERVAL {$days} DAY)";

$pageViews=$hasP?countv($pdo,"SELECT COUNT(*) FROM analytics_pageviews {$where}"):0;
$uniqueVisitors=$hasP?countv($pdo,"SELECT COUNT(DISTINCT visitor_id) FROM analytics_pageviews {$where}"):0;
$sessions=$hasP?countv($pdo,"SELECT COUNT(DISTINCT session_id) FROM analytics_pageviews {$where}"):0;
$onePageSessions=$hasP?countv($pdo,"SELECT COUNT(*) FROM (SELECT session_id,COUNT(*) c FROM analytics_pageviews {$where} GROUP BY session_id HAVING c=1) x"):0;
$returning=$hasV?countv($pdo,"SELECT COUNT(*) FROM analytics_visitors WHERE last_seen>=DATE_SUB(NOW(),INTERVAL {$days} DAY) AND first_seen<DATE_SUB(NOW(),INTERVAL {$days} DAY)"):0;
$liveCount=$hasL?countv($pdo,"SELECT COUNT(*) FROM analytics_live WHERE last_seen>=DATE_SUB(NOW(),INTERVAL 5 MINUTE)"):0;

$avgPages=$sessions>0?round($pageViews/$sessions,2):0;
$bounce=$sessions>0?round($onePageSessions/$sessions*100,1):0;

$hourly=$hasP?rows($pdo,"SELECT DATE_FORMAT(viewed_at,'%Y-%m-%d %H:00:00') label,COUNT(*) total,COUNT(DISTINCT visitor_id) visitors FROM analytics_pageviews WHERE viewed_at>=DATE_SUB(NOW(),INTERVAL 24 HOUR) GROUP BY label ORDER BY label ASC"):[];
$topLanding=$hasV?rows($pdo,"SELECT first_page label,COUNT(*) total FROM analytics_visitors WHERE first_seen>=DATE_SUB(NOW(),INTERVAL {$days} DAY) AND first_page<>'' GROUP BY first_page ORDER BY total DESC LIMIT 12"):[];
$topExit=$hasV?rows($pdo,"SELECT last_page label,COUNT(*) total FROM analytics_visitors WHERE last_seen>=DATE_SUB(NOW(),INTERVAL {$days} DAY) AND last_page<>'' GROUP BY last_page ORDER BY total DESC LIMIT 12"):[];
$countries=$hasP?rows($pdo,"SELECT COALESCE(NULLIF(country_code,''),'Unknown') label,COUNT(*) total FROM analytics_pageviews {$where} GROUP BY label ORDER BY total DESC LIMIT 15"):[];
$cities=$hasP?rows($pdo,"SELECT COALESCE(NULLIF(city,''),'Unknown') label,COUNT(*) total FROM analytics_pageviews {$where} GROUP BY label ORDER BY total DESC LIMIT 15"):[];
$devices=$hasP?rows($pdo,"SELECT COALESCE(NULLIF(device,''),'Unknown') label,COUNT(*) total FROM analytics_pageviews {$where} GROUP BY label ORDER BY total DESC LIMIT 10"):[];
$sources=$hasP?rows($pdo,"SELECT COALESCE(NULLIF(traffic_source,''),'Direct') label,COUNT(*) total FROM analytics_pageviews {$where} GROUP BY label ORDER BY total DESC LIMIT 10"):[];

?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Advanced Analytics — Smart-Tooz</title><style>
*{box-sizing:border-box}body{margin:0;background:#f6f8fc;color:#172033;font-family:Inter,system-ui,-apple-system,"Segoe UI",Arial,sans-serif}.wrap{width:min(1450px,calc(100% - 24px));margin:22px auto 50px}.top{display:flex;align-items:center;justify-content:space-between;gap:15px;margin-bottom:14px}.title h1{margin:0;font-size:27px}.title p{margin:4px 0 0;color:#7a8496;font-size:12px}.actions{display:flex;gap:8px;flex-wrap:wrap}.btn{display:inline-flex;align-items:center;justify-content:center;padding:9px 12px;border-radius:10px;border:1px solid #e1e5ec;background:#fff;color:#414b5c;text-decoration:none;font-size:11px;font-weight:800}.btn.primary{background:#635bff;color:#fff;border-color:#635bff}.periods{display:flex;gap:5px;margin-bottom:14px;flex-wrap:wrap}.periods a.active{background:#eeedff;color:#635bff;border-color:#dcd8ff}.stats{display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:9px}.stat,.card{background:#fff;border:1px solid #e3e7ee;border-radius:15px;padding:14px}.stat .k{font-size:10px;color:#8992a2}.stat .v{font-size:24px;font-weight:850;margin-top:5px}.stat.live .v{color:#168b51}.grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;margin-top:12px}.card h2{font-size:14px;margin:0 0 10px}.muted{font-size:10px;color:#8a93a2}.bar{margin:9px 0}.barhead{display:flex;justify-content:space-between;gap:8px;font-size:10px;font-weight:700;margin-bottom:3px}.barlabel{overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.track{height:6px;background:#eef1f5;border-radius:10px;overflow:hidden}.fill{height:100%;background:#635bff;border-radius:10px}.tablewrap{overflow:auto}table{width:100%;border-collapse:collapse;min-width:550px}th,td{text-align:left;padding:7px;border-bottom:1px solid #eef0f3;font-size:9px;vertical-align:top}th{font-size:8px;text-transform:uppercase;color:#929baa}td.path{color:#635bff;font-weight:700;overflow-wrap:anywhere}.live-dot{color:#16a05b;font-weight:900}.toolbar{display:flex;gap:7px;margin-bottom:9px}.search{flex:1;min-width:120px;padding:9px 10px;border:1px solid #e0e4eb;border-radius:9px;outline:0;font-size:11px}.pulse{font-size:10px;color:#168b51;font-weight:800}.empty{padding:22px;text-align:center;color:#929baa;font-size:10px}.export{margin-left:auto}@media(max-width:1000px){.stats{grid-template-columns:repeat(3,1fr)}}@media(max-width:650px){.grid{grid-template-columns:1fr}.stats{grid-template-columns:repeat(2,1fr)}.top{align-items:flex-start;flex-direction:column}.stat .v{font-size:20px}}
</style></head><body><main class="wrap">
<div class="top"><div class="title"><h1>⚡ Advanced Analytics</h1><p>Deeper traffic, engagement, live activity and export tools.</p></div><div class="actions"><a class="btn" href="/analytics/">← Analytics</a><a class="btn primary" href="?export=pageviews&days=<?=$days?>">⬇ Export CSV</a></div></div>
<div class="periods"><?php foreach([1=>'24H',7=>'7D',30=>'30D',90=>'90D'] as $d=>$label):?><a class="btn <?=$days===$d?'active':''?>" href="?days=<?=$d?>"><?=$label?></a><?php endforeach;?></div>
<section class="stats"><div class="stat"><div class="k">Page Views</div><div class="v"><?=number_format($pageViews)?></div></div><div class="stat"><div class="k">Unique Visitors</div><div class="v"><?=number_format($uniqueVisitors)?></div></div><div class="stat"><div class="k">Sessions</div><div class="v"><?=number_format($sessions)?></div></div><div class="stat"><div class="k">Pages / Session</div><div class="v"><?=$avgPages?></div></div><div class="stat"><div class="k">Bounce Proxy</div><div class="v"><?=$bounce?>%</div></div><div class="stat live"><div class="k">Live Now</div><div class="v">● <?=number_format($liveCount)?></div></div></section>
<div class="grid"><section class="card"><h2>📈 Hourly Traffic — Last 24 Hours</h2><?php if(!$hourly):?><div class="empty">No hourly data yet.</div><?php else:$m=max(1,max(array_map(fn($r)=>(int)$r['total'],$hourly)));foreach($hourly as $r):$w=(int)$r['total']/$m*100;?><div class="bar"><div class="barhead"><span class="barlabel"><?=e($r['label'])?></span><span><?=number_format((int)$r['total'])?></span></div><div class="track"><div class="fill" style="width:<?=$w?>%"></div></div></div><?php endforeach;endif;?></section>
<section class="card"><h2>🎯 Engagement</h2><div class="bar"><div class="barhead"><span>Returning Visitors</span><span><?=number_format($returning)?></span></div><div class="track"><div class="fill" style="width:<?=$uniqueVisitors>0?min(100,$returning/$uniqueVisitors*100):0?>%"></div></div></div><div class="bar"><div class="barhead"><span>One-page Sessions</span><span><?=number_format($onePageSessions)?></span></div><div class="track"><div class="fill" style="width:<?=$sessions>0?min(100,$onePageSessions/$sessions*100):0?>%"></div></div></div><p class="muted">Bounce Proxy = sessions with exactly one recorded pageview ÷ sessions.</p></section></div>
<div class="grid"><section class="card"><h2>🚪 Top Landing Pages</h2><?php $m=max(1,max(array_map(fn($r)=>(int)$r['total'],$topLanding?:[['total'=>1]])));foreach($topLanding as $r):?><div class="bar"><div class="barhead"><span class="barlabel"><?=e($r['label'])?></span><span><?=number_format((int)$r['total'])?></span></div><div class="track"><div class="fill" style="width:<?=((int)$r['total']/$m*100)?>%"></div></div></div><?php endforeach;if(!$topLanding):?><div class="empty">No landing data.</div><?php endif;?></section>
<section class="card"><h2>🏁 Top Exit Pages</h2><?php $m=max(1,max(array_map(fn($r)=>(int)$r['total'],$topExit?:[['total'=>1]])));foreach($topExit as $r):?><div class="bar"><div class="barhead"><span class="barlabel"><?=e($r['label'])?></span><span><?=number_format((int)$r['total'])?></span></div><div class="track"><div class="fill" style="width:<?=((int)$r['total']/$m*100)?>%"></div></div></div><?php endforeach;if(!$topExit):?><div class="empty">No exit data.</div><?php endif;?></section></div>
<div class="grid"><section class="card"><h2>🌍 Countries</h2><?php $m=max(1,max(array_map(fn($r)=>(int)$r['total'],$countries?:[['total'=>1]])));foreach($countries as $r):?><div class="bar"><div class="barhead"><span><?=e($r['label'])?></span><span><?=number_format((int)$r['total'])?></span></div><div class="track"><div class="fill" style="width:<?=((int)$r['total']/$m*100)?>%"></div></div></div><?php endforeach;if(!$countries):?><div class="empty">No country data.</div><?php endif;?></section>
<section class="card"><h2>📍 Cities</h2><?php $m=max(1,max(array_map(fn($r)=>(int)$r['total'],$cities?:[['total'=>1]])));foreach($cities as $r):?><div class="bar"><div class="barhead"><span><?=e($r['label'])?></span><span><?=number_format((int)$r['total'])?></span></div><div class="track"><div class="fill" style="width:<?=((int)$r['total']/$m*100)?>%"></div></div></div><?php endforeach;if(!$cities):?><div class="empty">No city data.</div><?php endif;?></section></div>
<div class="grid"><section class="card"><h2>💻 Devices</h2><?php $m=max(1,max(array_map(fn($r)=>(int)$r['total'],$devices?:[['total'=>1]])));foreach($devices as $r):?><div class="bar"><div class="barhead"><span><?=e($r['label'])?></span><span><?=number_format((int)$r['total'])?></span></div><div class="track"><div class="fill" style="width:<?=((int)$r['total']/$m*100)?>%"></div></div></div><?php endforeach;if(!$devices):?><div class="empty">No device data.</div><?php endif;?></section>
<section class="card"><h2>🔗 Traffic Sources</h2><?php $m=max(1,max(array_map(fn($r)=>(int)$r['total'],$sources?:[['total'=>1]])));foreach($sources as $r):?><div class="bar"><div class="barhead"><span><?=e($r['label'])?></span><span><?=number_format((int)$r['total'])?></span></div><div class="track"><div class="fill" style="width:<?=((int)$r['total']/$m*100)?>%"></div></div></div><?php endforeach;if(!$sources):?><div class="empty">No traffic-source data.</div><?php endif;?></section></div>
<section class="card" style="margin-top:12px"><div class="toolbar"><h2 style="margin:4px 0">🟢 Live Visitors <span class="pulse" id="pulse">● updating every 1s</span></h2><input class="search" id="liveSearch" placeholder="Search page, country, device…"></div><div class="tablewrap"><table><thead><tr><th>Status</th><th>Location</th><th>Device</th><th>Browser</th><th>Current Page</th><th>Last Seen</th></tr></thead><tbody id="liveBody"><tr><td colspan="6" class="empty">Loading live visitors…</td></tr></tbody></table></div></section>
<section class="card" style="margin-top:12px"><h2>📦 Data Export</h2><p class="muted">Download up to 50,000 pageview records for the selected period. CSV opens directly in Excel or Google Sheets.</p><div class="actions"><a class="btn primary" href="?export=pageviews&days=<?=$days?>">Export <?=$days?> Day CSV</a><a class="btn" href="?export=pageviews&days=30">Export 30 Day CSV</a><a class="btn" href="?export=pageviews&days=90">Export 90 Day CSV</a></div></section>
</main><script>
let liveRows=[];const body=document.getElementById('liveBody'),search=document.getElementById('liveSearch'),pulse=document.getElementById('pulse');
function esc(v){return String(v??'').replace(/[&<>'"]/g,m=>({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;','\"':'&quot;'}[m]))}
function render(){const q=(search.value||'').toLowerCase().trim();const rows=liveRows.filter(r=>!q||JSON.stringify(r).toLowerCase().includes(q));if(!rows.length){body.innerHTML='<tr><td colspan="6" class="empty">No live visitors right now.</td></tr>';return}body.innerHTML=rows.map(r=>`<tr><td><span class="live-dot">● LIVE</span></td><td>${esc(r.country_code||'Unknown')}${r.city?'<br>'+esc(r.city):''}</td><td>${esc(r.device||'Unknown')}</td><td>${esc(r.browser||'Unknown')}</td><td class="path">${esc(r.page_path||'-')}</td><td>${esc(r.last_seen||'-')}</td></tr>`).join('')}
async function refresh(){try{const r=await fetch('/analytics/advanced.php?live=1&ts='+Date.now(),{cache:'no-store',credentials:'same-origin'});const d=await r.json();if(d.ok){liveRows=d.visitors||[];render();pulse.textContent='● '+new Date().toLocaleTimeString()}}catch(e){pulse.textContent='● live connection error'}}
search.addEventListener('input',render);refresh();setInterval(refresh,1000);
</script></body></html>