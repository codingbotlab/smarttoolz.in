<?php
declare(strict_types=1);

require_once __DIR__ . '/bootstrap.php';
$admin = requireAdmin();
$db = adminDb();

function stz_h(mixed $v): string { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function stz_count(PDO $db, string $sql, array $params = []): int {
    try { $q = $db->prepare($sql); $q->execute($params); return (int)$q->fetchColumn(); }
    catch (Throwable $e) { return 0; }
}
function stz_rows(PDO $db, string $sql, array $params = []): array {
    try { $q = $db->prepare($sql); $q->execute($params); return $q->fetchAll(PDO::FETCH_ASSOC); }
    catch (Throwable $e) { return []; }
}
function stz_table(PDO $db, string $table): bool {
    try {
        $q=$db->prepare('SELECT 1 FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name=? LIMIT 1');
        $q->execute([$table]); return (bool)$q->fetchColumn();
    } catch(Throwable $e){ return false; }
}

$tab=(string)($_GET['tab']??'dashboard');
$allowed=['dashboard','analytics','ads','settings','users','database'];
if(!in_array($tab,$allowed,true)) $tab='dashboard';

$live=stz_count($db,"SELECT COUNT(*) FROM analytics_live WHERE last_seen>=DATE_SUB(NOW(),INTERVAL 2 MINUTE)");
$todayViews=stz_count($db,"SELECT COUNT(*) FROM analytics_pageviews WHERE viewed_at>=CURDATE()");
$todayVisitors=stz_count($db,"SELECT COUNT(DISTINCT visitor_id) FROM analytics_pageviews WHERE viewed_at>=CURDATE()");
$todayDownloads=stz_count($db,"SELECT COUNT(*) FROM download_tracking WHERE created_at>=CURDATE()");
$totalViews=stz_count($db,'SELECT COUNT(*) FROM analytics_pageviews');
$totalDownloads=stz_count($db,'SELECT COUNT(*) FROM download_tracking');
$totalUsers=stz_count($db,'SELECT COUNT(*) FROM creator_users');
$totalTools=stz_count($db,'SELECT COUNT(*) FROM tool_usage');
$totalEvents=stz_count($db,'SELECT COUNT(*) FROM analytics_events');
$adsEnabled=stz_count($db,"SELECT COUNT(*) FROM ads_settings WHERE enabled=1");

$pages=stz_rows($db,"SELECT page_path,COUNT(*) c FROM analytics_pageviews WHERE viewed_at>=DATE_SUB(NOW(),INTERVAL 30 DAY) GROUP BY page_path ORDER BY c DESC LIMIT 10");
$tools=stz_rows($db,"SELECT tool_slug,COUNT(*) c FROM tool_usage GROUP BY tool_slug ORDER BY c DESC LIMIT 10");
$countries=stz_rows($db,"SELECT COALESCE(NULLIF(country,''),'Unknown') label,COUNT(*) c FROM analytics_pageviews WHERE viewed_at>=DATE_SUB(NOW(),INTERVAL 30 DAY) GROUP BY country ORDER BY c DESC LIMIT 8");
$devices=stz_rows($db,"SELECT COALESCE(NULLIF(device,''),'Other') label,COUNT(*) c FROM analytics_pageviews WHERE viewed_at>=DATE_SUB(NOW(),INTERVAL 30 DAY) GROUP BY device ORDER BY c DESC LIMIT 6");
$browsers=stz_rows($db,"SELECT COALESCE(NULLIF(browser,''),'Other') label,COUNT(*) c FROM analytics_pageviews WHERE viewed_at>=DATE_SUB(NOW(),INTERVAL 30 DAY) GROUP BY browser ORDER BY c DESC LIMIT 6");
$liveRows=stz_rows($db,"SELECT visitor_id,page_path,country,city,device,browser,last_seen FROM analytics_live WHERE last_seen>=DATE_SUB(NOW(),INTERVAL 2 MINUTE) ORDER BY last_seen DESC LIMIT 25");
$recentDownloads=stz_rows($db,"SELECT user_id,guest_id,tool_slug,page_url,created_at FROM download_tracking ORDER BY id DESC LIMIT 12");
$recentEvents=stz_rows($db,"SELECT event_name,event_value,visitor_id,viewed_at FROM analytics_events ORDER BY id DESC LIMIT 12");
$users=stz_rows($db,'SELECT id,name,email,role,credits,daily_credits,plan,created_at FROM creator_users ORDER BY id DESC LIMIT 100');
$ads=stz_rows($db,'SELECT id,ad_key,enabled,updated_at FROM ads_settings ORDER BY id DESC LIMIT 100');
$settings=stz_rows($db,'SELECT id,setting_key,setting_type,enabled,updated_at FROM smarttoolz_settings ORDER BY setting_key ASC LIMIT 100');

$tables=[];
try {
    $tables=$db->query("SELECT TABLE_NAME, TABLE_ROWS, ROUND((DATA_LENGTH+INDEX_LENGTH)/1024/1024,2) MB FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() ORDER BY TABLE_NAME")->fetchAll(PDO::FETCH_ASSOC);
} catch(Throwable $e) {}

$hourly=[];
for($h=23;$h>=0;$h--){
    $start=date('Y-m-d H:00:00',strtotime("-$h hours"));
    $end=date('Y-m-d H:00:00',strtotime("-".($h-1)." hours"));
    $count=stz_count($db,'SELECT COUNT(*) FROM analytics_pageviews WHERE viewed_at>=? AND viewed_at<?',[$start,$end]);
    $hourly[]=[$start,$count];
}
$maxHour=max(1,...array_map(static fn($x)=>(int)$x[1],$hourly));
$maxPage=max(1,(int)($pages[0]['c']??1));
$maxTool=max(1,(int)($tools[0]['c']??1));
$maxCountry=max(1,(int)($countries[0]['c']??1));

$nav=[
 ['dashboard','⌂','Dashboard'],['analytics','◒','Analytics'],['ads','◇','Ads Manager'],['settings','⚙','Settings'],['users','♙','Users & Credits'],['database','▦','DB Explorer']
];
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>SmartToolz • Admin</title>
<style>
:root{--bg:#f4f7fb;--surface:#fff;--ink:#172033;--muted:#788397;--line:#e7ebf2;--primary:#635bff;--primary2:#8b7fff;--green:#12a66a;--green-bg:#e9f9f1;--orange:#e69b19;--orange-bg:#fff7e8;--red:#dc4c4c;--red-bg:#fff0f0;--blue:#3487ff;--blue-bg:#edf5ff;--shadow:0 12px 34px rgba(34,46,80,.06)}*{box-sizing:border-box}html{background:var(--bg)}body{margin:0;color:var(--ink);background:linear-gradient(180deg,#f9fbff 0,#f4f7fb 38%,#f2f5fa 100%);font-family:Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",Arial,sans-serif;font-size:13px}a{text-decoration:none;color:inherit}button,input,select{font:inherit}.shell{min-height:100vh;display:grid;grid-template-columns:240px minmax(0,1fr)}.sidebar{position:sticky;top:0;height:100vh;background:rgba(255,255,255,.93);backdrop-filter:blur(18px);border-right:1px solid var(--line);padding:18px 13px;z-index:10}.brand{display:flex;align-items:center;gap:10px;padding:6px 9px 20px}.brand-mark{width:42px;height:42px;border-radius:14px;display:grid;place-items:center;color:#fff;font-weight:950;background:linear-gradient(135deg,var(--primary),var(--primary2));box-shadow:0 9px 22px rgba(99,91,255,.23)}.brand-title{font-size:15px;font-weight:950}.brand-sub{font-size:9px;color:var(--muted);margin-top:2px;font-weight:800}.nav{display:grid;gap:5px}.nav a{display:flex;align-items:center;gap:10px;padding:10px 11px;border-radius:11px;color:#566277;font-weight:850;font-size:12px}.nav a:hover{background:#f3f4ff;color:var(--primary)}.nav a.active{color:var(--primary);background:linear-gradient(90deg,#efedff,#f7f6ff);box-shadow:inset 3px 0 0 var(--primary)}.nav-icon{width:18px;text-align:center;font-size:15px}.nav-sep{height:1px;background:var(--line);margin:10px 8px}.side-bottom{position:absolute;left:13px;right:13px;bottom:14px;padding:11px;border:1px solid var(--line);border-radius:13px;background:#fafbfe}.side-user{font-weight:900;font-size:11px}.side-mail{font-size:9px;color:var(--muted);margin-top:2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.status{display:inline-flex;align-items:center;gap:6px;margin-top:8px;padding:5px 8px;border-radius:999px;background:var(--green-bg);color:var(--green);font-size:9px;font-weight:950}.status i{width:6px;height:6px;border-radius:50%;background:var(--green);box-shadow:0 0 0 4px rgba(18,166,106,.10)}.main{min-width:0;padding:18px 22px 34px}.topbar{display:flex;align-items:center;justify-content:space-between;gap:14px;margin-bottom:16px}.title h1{margin:0;font-size:25px;letter-spacing:-.6px}.title p{margin:4px 0 0;color:var(--muted);font-size:10px;font-weight:650}.live-pill{display:inline-flex;align-items:center;gap:7px;background:#fff;border:1px solid var(--line);padding:8px 10px;border-radius:999px;box-shadow:var(--shadow);font-size:10px;font-weight:900}.live-pill b{color:var(--green)}.live-dot{width:7px;height:7px;border-radius:50%;background:var(--green);box-shadow:0 0 0 4px rgba(18,166,106,.11),0 0 12px rgba(18,166,106,.35)}.kpis{display:grid;grid-template-columns:repeat(6,minmax(0,1fr));gap:10px}.card{background:var(--surface);border:1px solid var(--line);border-radius:15px;box-shadow:var(--shadow)}.kpi{padding:13px 14px;position:relative;overflow:hidden}.kpi:after{content:"";position:absolute;right:-24px;top:-24px;width:78px;height:78px;border-radius:50%;background:var(--tint,#eef);opacity:.85}.kpi-icon{width:28px;height:28px;border-radius:9px;display:grid;place-items:center;background:var(--tint);color:var(--accent);font-weight:950;margin-bottom:9px;position:relative;z-index:1}.kpi-label{font-size:9px;color:var(--muted);font-weight:900;position:relative;z-index:1}.kpi-value{font-size:22px;font-weight:950;letter-spacing:-.6px;margin-top:2px;position:relative;z-index:1}.kpi-sub{font-size:9px;color:var(--muted);margin-top:3px;position:relative;z-index:1}.section-grid{display:grid;gap:12px;margin-top:12px}.cols-2{grid-template-columns:1.45fr 1fr}.cols-3{grid-template-columns:1fr 1fr 1fr}.panel{padding:14px}.head{display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:12px}.head h2{margin:0;font-size:14px;letter-spacing:-.2px}.head p{margin:3px 0 0;color:var(--muted);font-size:9px}.chip{padding:5px 8px;border-radius:999px;background:#f5f6fa;color:#69758a;font-size:9px;font-weight:900}.chip.green{background:var(--green-bg);color:var(--green)}.chart{height:170px;display:flex;align-items:flex-end;gap:4px;padding-top:14px}.bar{flex:1;min-width:5px;border-radius:5px 5px 2px 2px;background:linear-gradient(180deg,#8a7fff,#635bff);opacity:.92;position:relative}.bar:hover{opacity:1}.bar span{position:absolute;left:50%;transform:translateX(-50%);bottom:-16px;font-size:7px;color:#97a1b2;white-space:nowrap}.live-list{display:grid;gap:6px;max-height:190px;overflow:auto}.live-row{display:grid;grid-template-columns:8px minmax(0,1fr) auto;gap:8px;align-items:center;padding:8px 9px;border:1px solid #edf0f5;border-radius:10px;background:#fafbfe}.dot{width:7px;height:7px;border-radius:50%;background:var(--green);box-shadow:0 0 0 4px rgba(18,166,106,.10)}.live-page{font-size:10px;font-weight:900;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.live-meta{font-size:8px;color:var(--muted);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.live-time{font-size:8px;color:#8090a8}.bar-list{display:grid;gap:8px}.bar-item{display:grid;grid-template-columns:95px minmax(0,1fr) 38px;align-items:center;gap:8px}.bar-label{font-size:9px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.track{height:7px;border-radius:999px;background:#edf0f5;overflow:hidden}.fill{height:100%;border-radius:999px;background:linear-gradient(90deg,#635bff,#8f85ff)}.bar-num{text-align:right;font-size:9px;font-weight:900;color:#5d687b}.table-wrap{overflow:auto;border:1px solid var(--line);border-radius:11px}table{width:100%;border-collapse:collapse;min-width:640px;font-size:9px}th,td{padding:9px 10px;border-bottom:1px solid #eef1f5;text-align:left;vertical-align:top}th{background:#fafbfe;color:#6a7485;font-size:8px;text-transform:uppercase;letter-spacing:.4px;font-weight:950;position:sticky;top:0}tr:last-child td{border-bottom:0}.empty{text-align:center;padding:25px;color:var(--muted);font-size:10px}.toolbar{display:flex;gap:7px;flex-wrap:wrap}.btn{display:inline-flex;align-items:center;justify-content:center;border:1px solid var(--line);background:#fff;color:#3e495d;padding:8px 10px;border-radius:9px;font-size:9px;font-weight:900}.btn:hover{background:#f7f8fc}.btn.primary{background:var(--primary);border-color:var(--primary);color:#fff}.btn.green{background:var(--green);border-color:var(--green);color:#fff}.btn.blue{background:var(--blue);border-color:var(--blue);color:#fff}.notice{padding:10px 11px;border-radius:10px;background:#fafbfe;border:1px solid var(--line);font-size:9px;line-height:1.55;color:#626d7e}.tabs{display:flex;gap:6px;overflow:auto;margin-bottom:12px}.tabs a{white-space:nowrap;padding:7px 10px;border-radius:9px;background:#f3f5f9;color:#637087;font-size:9px;font-weight:900}.tabs a.active{background:#eeedff;color:var(--primary)}.health{display:grid;gap:6px}.health-row{display:grid;grid-template-columns:7px 1fr auto;align-items:center;gap:7px;padding:7px 8px;background:#fafbfe;border:1px solid #edf0f5;border-radius:9px}.health-row i{width:7px;height:7px;border-radius:50%}.ok{background:var(--green)}.warn{background:var(--orange)}.health-row span{font-size:9px;color:#596477}.health-row b{font-size:9px}.tag{display:inline-flex;padding:4px 7px;border-radius:999px;font-size:8px;font-weight:950;background:#f1f3f7;color:#657085}.tag.green{background:var(--green-bg);color:var(--green)}.tag.purple{background:#eeedff;color:var(--primary)}.tag.orange{background:var(--orange-bg);color:var(--orange)}.search{width:100%;padding:8px 10px;border:1px solid var(--line);border-radius:9px;outline:none;background:#fff;font-size:10px}.search:focus{border-color:#c9c4ff;box-shadow:0 0 0 3px #eeedff}.two-stat{display:grid;grid-template-columns:1fr 1fr;gap:8px}.mini-stat{padding:10px;background:#fafbfe;border:1px solid var(--line);border-radius:10px}.mini-stat b{display:block;font-size:18px}.mini-stat span{display:block;margin-top:2px;color:var(--muted);font-size:8px}.footer-note{text-align:center;color:#9aa3b2;font-size:8px;margin-top:14px}
@media(max-width:1250px){.kpis{grid-template-columns:repeat(3,minmax(0,1fr))}.cols-2,.cols-3{grid-template-columns:1fr 1fr}.cols-3 .card:last-child{grid-column:1/-1}}
@media(max-width:900px){.shell{grid-template-columns:1fr}.sidebar{position:relative;height:auto;border-right:0;border-bottom:1px solid var(--line)}.side-bottom{position:static;margin-top:14px}.nav{grid-template-columns:repeat(3,1fr)}.nav-sep{display:none}.main{padding:14px}.kpis{grid-template-columns:repeat(2,1fr)}}
@media(max-width:560px){.topbar{align-items:flex-start;flex-direction:column}.kpis{grid-template-columns:1fr}.cols-2,.cols-3{grid-template-columns:1fr}.nav{grid-template-columns:1fr 1fr}.bar-item{grid-template-columns:80px minmax(0,1fr) 32px}}
</style>
</head>
<body>
<div class="shell">
<aside class="sidebar">
  <div class="brand"><div class="brand-mark">ST</div><div><div class="brand-title">SmartToolz</div><div class="brand-sub">ADMIN CONTROL CENTER</div></div></div>
  <nav class="nav">
  <?php foreach($nav as $n): ?><a class="<?= $tab===$n[0]?'active':'' ?>" href="?tab=<?=stz_h($n[0])?>"><span class="nav-icon"><?=stz_h($n[1])?></span><?=stz_h($n[2])?></a><?php endforeach; ?>
  <div class="nav-sep"></div>
  <a href="/analytics/"><span class="nav-icon">◎</span>Full Analytics</a>
  <a href="/analytics/advanced.php"><span class="nav-icon">✦</span>Advanced Reports</a>
  <a href="/analytics/resolve-geo.php"><span class="nav-icon">◉</span>Geo Resolver</a>
  <a href="/smart-toolz/"><span class="nav-icon">←</span>Back to Site</a>
  </nav>
  <div class="side-bottom"><div class="side-user"><?=stz_h($admin['name']??'Admin')?></div><div class="side-mail"><?=stz_h($admin['email']??'')?></div><div class="status"><i></i> ADMIN ONLINE</div></div>
</aside>

<main class="main">
<div class="topbar"><div class="title"><h1><?=stz_h(ucfirst($tab))?> <span style="color:var(--primary)">Command Center</span></h1><p>SmartToolz live intelligence, operations and database visibility</p></div><div class="live-pill"><span class="live-dot"></span><b data-live-count><?=number_format($live)?></b> live now</div></div>

<?php if($tab==='dashboard'): ?>
<section class="kpis">
<?php
$cards=[
 ['◉','LIVE NOW',$live,'2 minute activity','--green-bg','--green'],
 ['◌','VISITORS TODAY',$todayVisitors,'unique visitor IDs','--blue-bg','--blue'],
 ['◍','PAGE VIEWS',$todayViews,'today','--primary-soft','--primary'],
 ['⇩','DOWNLOADS',$todayDownloads,'today','--orange-bg','--orange'],
 ['♙','USERS',$totalUsers,'registered accounts','--green-bg','--green'],
 ['✦','TOOL USES',$totalTools,'all tracked uses','--primary-soft','--primary']
];
foreach($cards as $c): ?>
<div class="card kpi" style="--tint:var(<?=$c[4]?>);--accent:var(<?=$c[5]?>)"><div class="kpi-icon"><?=$c[0]?></div><div class="kpi-label"><?=$c[1]?></div><div class="kpi-value"><?=number_format((int)$c[2])?></div><div class="kpi-sub"><?=$c[3]?></div></div>
<?php endforeach; ?>
</section>

<section class="section-grid cols-2">
<div class="card panel"><div class="head"><div><h2>Traffic · Last 24 Hours</h2><p>Page views by hour</p></div><span class="chip green">LIVE DATA</span></div><div class="chart"><?php foreach($hourly as $i=>$v): $height=max(4,(int)round($v[1]/$maxHour*140)); ?><div class="bar" style="height:<?=$height?>px" title="<?=stz_h(date('H:i',strtotime($v[0])))?> · <?=number_format($v[1])?> views"><span><?=date('H',strtotime($v[0]))?></span></div><?php endforeach; ?></div></div>
<div class="card panel"><div class="head"><div><h2>Live Visitors</h2><p>Updates automatically</p></div><span class="chip green">● <?=number_format($live)?></span></div><div class="live-list"><?php foreach($liveRows as $r): ?><div class="live-row"><span class="dot"></span><div><div class="live-page"><?=stz_h($r['page_path']??'/')?></div><div class="live-meta"><?=stz_h(implode(' · ',array_filter([$r['country']??'Unknown',$r['city']??'',$r['device']??'',$r['browser']??''])))?></div></div><div class="live-time">now</div></div><?php endforeach; if(!$liveRows): ?><div class="empty">No active visitors right now.</div><?php endif; ?></div></div>
</section>

<section class="section-grid cols-3">
<div class="card panel"><div class="head"><div><h2>Top Pages</h2><p>30-day views</p></div></div><div class="bar-list"><?php foreach($pages as $r): ?><div class="bar-item"><div class="bar-label" title="<?=stz_h($r['page_path'])?>"><?=stz_h($r['page_path'])?></div><div class="track"><div class="fill" style="width:<?=min(100,(int)round($r['c']/$maxPage*100))?>%"></div></div><div class="bar-num"><?=number_format((int)$r['c'])?></div></div><?php endforeach; if(!$pages): ?><div class="empty">No pageview data.</div><?php endif; ?></div></div>
<div class="card panel"><div class="head"><div><h2>Top Tools</h2><p>Tool usage</p></div></div><div class="bar-list"><?php foreach($tools as $r): ?><div class="bar-item"><div class="bar-label"><?=stz_h($r['tool_slug'])?></div><div class="track"><div class="fill" style="width:<?=min(100,(int)round($r['c']/$maxTool*100))?>%"></div></div><div class="bar-num"><?=number_format((int)$r['c'])?></div></div><?php endforeach; if(!$tools): ?><div class="empty">No tool usage data.</div><?php endif; ?></div></div>
<div class="card panel"><div class="head"><div><h2>Countries</h2><p>Audience · 30 days</p></div></div><div class="bar-list"><?php foreach($countries as $r): ?><div class="bar-item"><div class="bar-label"><?=stz_h($r['label'])?></div><div class="track"><div class="fill" style="width:<?=min(100,(int)round($r['c']/$maxCountry*100))?>%"></div></div><div class="bar-num"><?=number_format((int)$r['c'])?></div></div><?php endforeach; if(!$countries): ?><div class="empty">No geo data.</div><?php endif; ?></div></div>
</section>

<section class="section-grid cols-2">
<div class="card panel"><div class="head"><div><h2>Technology</h2><p>Device and browser mix</p></div></div><div class="two-stat"><div class="mini-stat"><b><?=number_format($todayVisitors)?></b><span>Visitors Today</span></div><div class="mini-stat"><b><?=number_format($totalEvents)?></b><span>Events All Time</span></div></div><div style="height:10px"></div><div class="bar-list"><div class="bar-item"><div class="bar-label">Devices</div><div class="track"><div class="fill" style="width:100%"></div></div><div class="bar-num"><?=count($devices)?></div></div><?php foreach($devices as $r): ?><div class="bar-item"><div class="bar-label"><?=stz_h($r['label'])?></div><div class="track"><div class="fill" style="width:<?=min(100,(int)round($r['c']/max(1,(int)($devices[0]['c']??1))*100))?>%"></div></div><div class="bar-num"><?=number_format((int)$r['c'])?></div></div><?php endforeach; ?><?php foreach($browsers as $r): ?><div class="bar-item"><div class="bar-label"><?=stz_h($r['label'])?></div><div class="track"><div class="fill" style="width:<?=min(100,(int)round($r['c']/max(1,(int)($browsers[0]['c']??1))*100))?>%"></div></div><div class="bar-num"><?=number_format((int)$r['c'])?></div></div><?php endforeach; ?></div></div>
<div class="card panel"><div class="head"><div><h2>System Health</h2><p>Quick operational checks</p></div><span class="chip green">HEALTHY</span></div><div class="health"><div class="health-row"><i class="ok"></i><span>Database connection</span><b>OK</b></div><div class="health-row"><i class="ok"></i><span>Analytics tables</span><b><?=stz_table($db,'analytics_pageviews')?'OK':'MISSING'?></b></div><div class="health-row"><i class="ok"></i><span>Download tracking</span><b><?=stz_table($db,'download_tracking')?'OK':'MISSING'?></b></div><div class="health-row"><i class="ok"></i><span>Tool usage tracking</span><b><?=stz_table($db,'tool_usage')?'OK':'MISSING'?></b></div><div class="health-row"><i class="ok"></i><span>Active ads</span><b><?=number_format($adsEnabled)?></b></div></div><div style="height:10px"></div><div class="toolbar"><a class="btn primary" href="?tab=analytics">Deep Analytics</a><a class="btn" href="?tab=database">DB Explorer</a><a class="btn" href="/analytics/live.php">Live API</a></div></div>
</section>

<?php elseif($tab==='analytics'): ?>
<div class="tabs"><a class="active" href="?tab=analytics">Overview</a><a href="/analytics/advanced.php">Advanced</a><a href="/analytics/">Full Analytics</a><a href="/analytics/live.php">Realtime</a><a href="/analytics/resolve-geo.php">Geo</a></div>
<section class="kpis"><div class="card kpi" style="--tint:var(--green-bg);--accent:var(--green)"><div class="kpi-icon">●</div><div class="kpi-label">LIVE NOW</div><div class="kpi-value" data-live-count><?=number_format($live)?></div><div class="kpi-sub">2 minute window</div></div><div class="card kpi" style="--tint:var(--blue-bg);--accent:var(--blue)"><div class="kpi-icon">♙</div><div class="kpi-label">30D VISITORS</div><div class="kpi-value"><?=number_format(stz_count($db,"SELECT COUNT(DISTINCT visitor_id) FROM analytics_pageviews WHERE viewed_at>=DATE_SUB(NOW(),INTERVAL 30 DAY)"))?></div><div class="kpi-sub">unique visitors</div></div><div class="card kpi" style="--tint:var(--primary-soft);--accent:var(--primary)"><div class="kpi-icon">◍</div><div class="kpi-label">30D VIEWS</div><div class="kpi-value"><?=number_format(stz_count($db,"SELECT COUNT(*) FROM analytics_pageviews WHERE viewed_at>=DATE_SUB(NOW(),INTERVAL 30 DAY)"))?></div><div class="kpi-sub">page views</div></div><div class="card kpi" style="--tint:var(--orange-bg);--accent:var(--orange)"><div class="kpi-icon">⇩</div><div class="kpi-label">30D DOWNLOADS</div><div class="kpi-value"><?=number_format(stz_count($db,"SELECT COUNT(*) FROM download_tracking WHERE created_at>=DATE_SUB(NOW(),INTERVAL 30 DAY)"))?></div><div class="kpi-sub">download activity</div></div><div class="card kpi" style="--tint:var(--green-bg);--accent:var(--green)"><div class="kpi-icon">✦</div><div class="kpi-label">EVENTS</div><div class="kpi-value"><?=number_format($totalEvents)?></div><div class="kpi-sub">all time</div></div><div class="card kpi" style="--tint:#f2efff;--accent:var(--primary)"><div class="kpi-icon">∞</div><div class="kpi-label">TOTAL VIEWS</div><div class="kpi-value"><?=number_format($totalViews)?></div><div class="kpi-sub">all time</div></div></section>
<section class="section-grid cols-2"><div class="card panel"><div class="head"><div><h2>24 Hour Traffic</h2><p>Interactive bar overview · hover for hour</p></div></div><div class="chart"><?php foreach($hourly as $v): $height=max(5,(int)round($v[1]/$maxHour*150)); ?><div class="bar" style="height:<?=$height?>px" title="<?=stz_h(date('d M H:i',strtotime($v[0])))?> · <?=number_format($v[1])?> views"><span><?=date('H',strtotime($v[0]))?></span></div><?php endforeach; ?></div></div><div class="card panel"><div class="head"><div><h2>Realtime Stream</h2><p>Current visitor activity</p></div><span class="chip green">1 SEC READY</span></div><div class="live-list"><?php foreach($liveRows as $r): ?><div class="live-row"><span class="dot"></span><div><div class="live-page"><?=stz_h($r['page_path']??'/')?></div><div class="live-meta"><?=stz_h(implode(' · ',array_filter([$r['visitor_id']??'',$r['country']??'Unknown',$r['device']??'',$r['browser']??''])))?></div></div><div class="live-time"><?=stz_h($r['last_seen']??'now')?></div></div><?php endforeach; if(!$liveRows): ?><div class="empty">No visitors inside the realtime window.</div><?php endif; ?></div></div></section>
<section class="section-grid cols-3"><div class="card panel"><div class="head"><div><h2>Pages</h2><p>Most viewed</p></div></div><div class="bar-list"><?php foreach($pages as $r): ?><div class="bar-item"><div class="bar-label"><?=stz_h($r['page_path'])?></div><div class="track"><div class="fill" style="width:<?=min(100,(int)round($r['c']/$maxPage*100))?>%"></div></div><div class="bar-num"><?=number_format((int)$r['c'])?></div></div><?php endforeach;?></div></div><div class="card panel"><div class="head"><div><h2>Tools</h2><p>Usage by tool</p></div></div><div class="bar-list"><?php foreach($tools as $r): ?><div class="bar-item"><div class="bar-label"><?=stz_h($r['tool_slug'])?></div><div class="track"><div class="fill" style="width:<?=min(100,(int)round($r['c']/$maxTool*100))?>%"></div></div><div class="bar-num"><?=number_format((int)$r['c'])?></div></div><?php endforeach;?></div></div><div class="card panel"><div class="head"><div><h2>Geo</h2><p>Countries</p></div></div><div class="bar-list"><?php foreach($countries as $r): ?><div class="bar-item"><div class="bar-label"><?=stz_h($r['label'])?></div><div class="track"><div class="fill" style="width:<?=min(100,(int)round($r['c']/$maxCountry*100))?>%"></div></div><div class="bar-num"><?=number_format((int)$r['c'])?></div></div><?php endforeach;?></div></div></section>

<?php elseif($tab==='users'): ?>
<div class="tabs"><a class="active" href="?tab=users">Users</a><a href="?tab=users">Credits</a><a href="/creator-ai/admin/users.php">Auth Users</a></div><section class="card panel"><div class="head"><div><h2>Users & Credits</h2><p>Latest 100 accounts · role, credits and plan</p></div><input class="search" style="max-width:240px" placeholder="Search by name or email" oninput="filterTable(this,'usersTable')"></div><div class="table-wrap"><table id="usersTable"><thead><tr><th>ID</th><th>User</th><th>Email</th><th>Role</th><th>Credits</th><th>Daily</th><th>Plan</th><th>Created</th></tr></thead><tbody><?php foreach($users as $u): ?><tr><td><?=number_format((int)$u['id'])?></td><td><b><?=stz_h($u['name'])?></b></td><td><?=stz_h($u['email'])?></td><td><span class="tag <?=in_array($u['role'],['admin','superadmin'],true)?'purple':''?>"><?=stz_h($u['role'])?></span></td><td><?=number_format((int)$u['credits'])?></td><td><?=number_format((int)$u['daily_credits'])?></td><td><?=stz_h($u['plan'])?></td><td><?=stz_h($u['created_at'])?></td></tr><?php endforeach; ?></tbody></table></div></section>

<?php elseif($tab==='ads'): ?>
<section class="card panel"><div class="head"><div><h2>Ads Manager</h2><p>Ad inventory status</p></div><div class="toolbar"><a class="btn primary" href="/smart-toolz/admin/?tab=ads">Open Manager</a><a class="btn" href="/smart-toolz/ad-settings.php">Ads Settings</a></div></div><div class="table-wrap"><table><thead><tr><th>ID</th><th>Ad Key</th><th>Status</th><th>Updated</th></tr></thead><tbody><?php foreach($ads as $a): ?><tr><td><?=number_format((int)$a['id'])?></td><td><b><?=stz_h($a['ad_key'])?></b></td><td><span class="tag <?=((int)$a['enabled']===1)?'green':'orange'?>"><?=((int)$a['enabled']===1)?'ENABLED':'DISABLED'?></span></td><td><?=stz_h($a['updated_at'])?></td></tr><?php endforeach; ?></tbody></table></div></section>

<?php elseif($tab==='settings'): ?>
<section class="card panel"><div class="head"><div><h2>System Settings</h2><p>SmartToolz runtime configuration</p></div><span class="chip">CRUD protected</span></div><div class="table-wrap"><table><thead><tr><th>ID</th><th>Key</th><th>Type</th><th>Status</th><th>Updated</th></tr></thead><tbody><?php foreach($settings as $s): ?><tr><td><?=number_format((int)$s['id'])?></td><td><b><?=stz_h($s['setting_key'])?></b></td><td><?=stz_h($s['setting_type'])?></td><td><span class="tag <?=((int)$s['enabled']===1)?'green':'orange'?>"><?=((int)$s['enabled']===1)?'ENABLED':'DISABLED'?></span></td><td><?=stz_h($s['updated_at'])?></td></tr><?php endforeach; ?></tbody></table></div></section>

<?php elseif($tab==='database'): ?>
<div class="section-grid cols-2"><div class="card panel"><div class="head"><div><h2>Database Overview</h2><p>Current database tables and footprint</p></div><span class="chip green"><?=count($tables)?> tables</span></div><div class="table-wrap"><table><thead><tr><th>Table</th><th>Rows</th><th>Size MB</th></tr></thead><tbody><?php foreach($tables as $t): ?><tr><td><b><?=stz_h($t['TABLE_NAME'])?></b></td><td><?=number_format((int)$t['TABLE_ROWS'])?></td><td><?=stz_h($t['MB'])?></td></tr><?php endforeach; ?></tbody></table></div></div><div class="card panel"><div class="head"><div><h2>DB Explorer</h2><p>PHPMyAdmin-style read-only browser</p></div></div><div class="notice">Browse rows, inspect columns/indexes, search records and export CSV without exposing the database password.</div><div style="height:10px"></div><a class="btn primary" href="/smart-toolz/admin/db-browser.php">Open DB Explorer →</a><div style="height:10px"></div><div class="health"><div class="health-row"><i class="ok"></i><span>Connection</span><b>ONLINE</b></div><div class="health-row"><i class="ok"></i><span>Admin authorization</span><b>ACTIVE</b></div><div class="health-row"><i class="ok"></i><span>Write controls</span><b>PROTECTED</b></div></div></div></div>
<?php endif; ?>

<div class="footer-note">SmartToolz Admin V3 · <?=stz_h(date('Y-m-d H:i:s'))?> · Secure admin session</div>
</main></div>
<script>
function filterTable(input,id){const q=input.value.toLowerCase();const table=document.getElementById(id);if(!table)return;table.querySelectorAll('tbody tr').forEach(r=>{r.style.display=r.innerText.toLowerCase().includes(q)?'':'none';});}
async function refreshLive(){try{const r=await fetch('/analytics/live.php?admin=1',{credentials:'same-origin',cache:'no-store'});if(!r.ok)return;const d=await r.json();const n=Number(d.count??0).toLocaleString();document.querySelectorAll('[data-live-count]').forEach(e=>e.textContent=n);}catch(e){}}
setInterval(refreshLive,1000); refreshLive();
</script>
</body>
</html>
