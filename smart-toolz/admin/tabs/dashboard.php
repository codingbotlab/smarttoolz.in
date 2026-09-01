<?php
declare(strict_types=1);

function av4c(PDO $db,string $sql,array $p=[]):int{try{$q=$db->prepare($sql);$q->execute($p);return(int)$q->fetchColumn();}catch(Throwable){return 0;}}
function av4r(PDO $db,string $sql,array $p=[]):array{try{$q=$db->prepare($sql);$q->execute($p);return$q->fetchAll(PDO::FETCH_ASSOC);}catch(Throwable){return[];}}

$users=av4c($db,'SELECT COUNT(*) FROM creator_users');
$views=av4c($db,'SELECT COUNT(*) FROM analytics_pageviews');
$viewsToday=av4c($db,'SELECT COUNT(*) FROM analytics_pageviews WHERE viewed_at>=CURDATE()');
$visToday=av4c($db,'SELECT COUNT(DISTINCT visitor_id) FROM analytics_pageviews WHERE viewed_at>=CURDATE()');
$downloads=av4c($db,'SELECT COUNT(*) FROM smarttoolz_download_activity');
$downloadsToday=av4c($db,'SELECT COUNT(*) FROM smarttoolz_download_activity WHERE downloaded_at>=CURDATE()');
$toolUses=av4c($db,'SELECT COUNT(*) FROM tool_usage');
$events=av4c($db,'SELECT COUNT(*) FROM analytics_events');
$ads=av4c($db,'SELECT COUNT(*) FROM ads_settings WHERE enabled=1');
$liveRows=av4r($db,"SELECT visitor_id,country,city,device,browser,page_path,last_seen FROM analytics_live WHERE last_seen>=DATE_SUB(NOW(),INTERVAL 2 MINUTE) ORDER BY last_seen DESC LIMIT 40");
$pages=av4r($db,"SELECT page_path label,COUNT(*) total FROM analytics_pageviews WHERE viewed_at>=DATE_SUB(NOW(),INTERVAL 30 DAY) GROUP BY page_path ORDER BY total DESC LIMIT 8");
$tools=av4r($db,"SELECT tool_slug label,COUNT(*) total FROM tool_usage WHERE created_at>=DATE_SUB(NOW(),INTERVAL 30 DAY) GROUP BY tool_slug ORDER BY total DESC LIMIT 8");
$countries=av4r($db,"SELECT COALESCE(NULLIF(country,''),'Unknown') label,COUNT(*) total FROM analytics_pageviews WHERE viewed_at>=DATE_SUB(NOW(),INTERVAL 30 DAY) GROUP BY country ORDER BY total DESC LIMIT 8");
$devices=av4r($db,"SELECT COALESCE(NULLIF(device,''),'Other') label,COUNT(*) total FROM analytics_pageviews WHERE viewed_at>=DATE_SUB(NOW(),INTERVAL 30 DAY) GROUP BY device ORDER BY total DESC LIMIT 6");
$browsers=av4r($db,"SELECT COALESCE(NULLIF(browser,''),'Other') label,COUNT(*) total FROM analytics_pageviews WHERE viewed_at>=DATE_SUB(NOW(),INTERVAL 30 DAY) GROUP BY browser ORDER BY total DESC LIMIT 6");
function av4Bars(array $rows,string $cls=''):void{ $mx=max(1,(int)($rows[0]['total']??1)); echo '<div class="av4-bars '.$cls.'">'; foreach($rows as $r){$pct=min(100,(int)round(((int)$r['total']/$mx)*100)); echo '<div class="av4-bar-row"><span>'.av4h($r['label']).'</span><div class="av4-track"><i style="width:'.$pct.'%"></i></div><b>'.number_format((int)$r['total']).'</b></div>'; } if(!$rows)echo '<div class="av4-empty">No data yet.</div>'; echo '</div>'; }
?>
<style>
.av4-grid{display:grid;gap:10px}.av4-kpis{grid-template-columns:repeat(6,minmax(0,1fr))}.av4-card{background:#fff;border:1px solid #e5e9f0;border-radius:14px;box-shadow:0 8px 24px rgba(30,40,70,.035);padding:13px}.av4-kpi-label{font-size:8px;font-weight:900;color:#818b9d}.av4-kpi-value{font-size:22px;font-weight:950;margin-top:5px}.av4-kpi-sub{font-size:8px;color:#8993a3;margin-top:2px}.av4-cols2{grid-template-columns:1.35fr 1fr}.av4-cols3{grid-template-columns:repeat(3,1fr)}.av4-head{display:flex;justify-content:space-between;align-items:center;gap:8px;margin-bottom:10px}.av4-head h2{margin:0;font-size:13px}.av4-head p{margin:3px 0 0;font-size:8px;color:#8993a3}.av4-chip{padding:5px 7px;border-radius:999px;background:#eaf8f1;color:#14915e;font-size:8px;font-weight:900}.av4-bars{display:grid;gap:8px}.av4-bar-row{display:grid;grid-template-columns:100px 1fr 35px;gap:7px;align-items:center}.av4-bar-row span{font-size:9px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.av4-track{height:7px;background:#eef1f5;border-radius:99px;overflow:hidden}.av4-track i{display:block;height:100%;background:linear-gradient(90deg,#635bff,#9a90ff);border-radius:99px}.av4-bar-row b{font-size:9px;text-align:right}.av4-live{display:grid;gap:6px;max-height:270px;overflow:auto}.av4-live-row{display:grid;grid-template-columns:8px 1fr auto;gap:7px;padding:8px;border:1px solid #edf0f4;border-radius:10px;background:#fafbfe}.av4-live-dot{width:7px;height:7px;border-radius:50%;background:#16a36a;box-shadow:0 0 0 4px #16a36a18}.av4-live-page{font-size:9px;font-weight:900;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.av4-live-meta{font-size:8px;color:#8993a3;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}.av4-live-time{font-size:8px;color:#8792a3}.av4-empty{text-align:center;color:#8b95a5;font-size:9px;padding:18px}.av4-links{display:flex;gap:7px;flex-wrap:wrap}.av4-link{padding:8px 10px;border-radius:9px;background:#f2f4f8;border:1px solid #e6eaf1;font-size:9px;font-weight:900;color:#46536a}.av4-link.primary{background:#635bff;color:#fff;border-color:#635bff}.av4-note{padding:10px;background:#fafbfe;border:1px solid #edf0f4;border-radius:10px;font-size:9px;color:#687386;line-height:1.5}@media(max-width:1200px){.av4-kpis{grid-template-columns:repeat(3,1fr)}}@media(max-width:850px){.av4-cols2,.av4-cols3{grid-template-columns:1fr}.av4-kpis{grid-template-columns:repeat(2,1fr)}}
</style>
<div class="av4-grid">
<section class="av4-grid av4-kpis">
<?php foreach([
 ['Visitors Today',$visToday,'unique visitors'],['Page Views Today',$viewsToday,'page views'],['Downloads Today',$downloadsToday,'tracked downloads'],['Total Downloads',$downloads,'all time'],['Tool Uses',$toolUses,'all time'],['Users',$users,'registered users']
] as $k):?><div class="av4-card"><div class="av4-kpi-label"><?=av4h($k[0])?></div><div class="av4-kpi-value"><?=number_format((int)$k[1])?></div><div class="av4-kpi-sub"><?=av4h($k[2])?></div></div><?php endforeach; ?>
</section>
<div class="av4-grid av4-cols2">
<section class="av4-card"><div class="av4-head"><div><h2>Live Visitors</h2><p>2-minute activity window</p></div><span class="av4-chip">● LIVE</span></div><div class="av4-live"><?php foreach($liveRows as $r):?><div class="av4-live-row"><span class="av4-live-dot"></span><div><div class="av4-live-page"><?=av4h($r['page_path'])?></div><div class="av4-live-meta"><?=av4h(implode(' · ',array_filter([$r['country']??'Unknown',$r['city']??'',$r['device']??'',$r['browser']??''])))?></div></div><span class="av4-live-time">now</span></div><?php endforeach;if(!$liveRows):?><div class="av4-empty">No active visitors right now.</div><?php endif;?></div></section>
<section class="av4-card"><div class="av4-head"><div><h2>Quick Access</h2><p>Open management screens</p></div></div><div class="av4-links"><a class="av4-link primary" href="?tab=analytics">Analytics</a><a class="av4-link" href="?tab=downloads">Downloads</a><a class="av4-link" href="/smart-toolz/admin/db-browser.php">DB Explorer</a><a class="av4-link" href="/smart-toolz/admin/tracking.php">Download Tracking</a><a class="av4-link" href="/analytics/advanced.php">Advanced Analytics</a></div><div style="margin-top:10px" class="av4-note"><b>System totals:</b> <?=number_format($views)?> views · <?=number_format($downloads)?> downloads · <?=number_format($events)?> events · <?=number_format($ads)?> active ads.</div></section>
</div>
<div class="av4-grid av4-cols3">
<section class="av4-card"><div class="av4-head"><div><h2>Top Pages</h2><p>Last 30 days</p></div></div><?php av4Bars($pages);?></section>
<section class="av4-card"><div class="av4-head"><div><h2>Top Tools</h2><p>Last 30 days</p></div></div><?php av4Bars($tools);?></section>
<section class="av4-card"><div class="av4-head"><div><h2>Countries</h2><p>Last 30 days</p></div></div><?php av4Bars($countries);?></section>
</div>
<div class="av4-grid av4-cols2">
<section class="av4-card"><div class="av4-head"><div><h2>Devices</h2><p>Audience mix</p></div></div><?php av4Bars($devices);?></section>
<section class="av4-card"><div class="av4-head"><div><h2>Browsers</h2><p>Audience mix</p></div></div><?php av4Bars($browsers);?></section>
</div>
</div>
