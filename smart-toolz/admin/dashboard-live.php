<?php
declare(strict_types=1);
require_once __DIR__ . '/bootstrap.php';
$admin = requireAdmin();
$db = adminDb();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

function tExists(PDO $db,string $t):bool{try{$q=$db->prepare("SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name=?");$q->execute([$t]);return (int)$q->fetchColumn()>0;}catch(Throwable $e){return false;}}
function cExists(PDO $db,string $t,string $c):bool{try{$q=$db->prepare("SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name=? AND column_name=?");$q->execute([$t,$c]);return (int)$q->fetchColumn()>0;}catch(Throwable $e){return false;}}
function countQ(PDO $db,string $sql):int{try{return (int)$db->query($sql)->fetchColumn();}catch(Throwable $e){return 0;}}
function rowsQ(PDO $db,string $sql):array{try{return $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);}catch(Throwable $e){return [];}}

$tables=['creator_users','ads_settings','smarttoolz_settings','tool_usage','download_tracking','analytics_visitors','analytics_pageviews','analytics_live','analytics_events'];
foreach($tables as $t){$exists[$t]=tExists($db,$t);}

$data=[
 'ok'=>true,
 'generated_at'=>date('Y-m-d H:i:s'),
 'health'=>[
   'database'=>true,
   'php'=>PHP_VERSION,
   'admin'=>true,
 ],
 'counts'=>[
   'users'=>$exists['creator_users']?countQ($db,'SELECT COUNT(*) FROM creator_users'):0,
   'admins'=>$exists['creator_users']?countQ($db,"SELECT COUNT(*) FROM creator_users WHERE role IN ('admin','superadmin')"):0,
   'credits'=>$exists['creator_users']?countQ($db,'SELECT COALESCE(SUM(credits),0) FROM creator_users'):0,
   'tools_used'=>$exists['tool_usage']?countQ($db,'SELECT COUNT(*) FROM tool_usage'):0,
   'tools_today'=>$exists['tool_usage']?countQ($db,"SELECT COUNT(*) FROM tool_usage WHERE created_at>=CURDATE()"):0,
   'downloads'=>$exists['download_tracking']?countQ($db,'SELECT COUNT(*) FROM download_tracking'):0,
   'downloads_today'=>$exists['download_tracking']?countQ($db,"SELECT COUNT(*) FROM download_tracking WHERE created_at>=CURDATE()"):0,
   'visitors'=>$exists['analytics_visitors']?countQ($db,'SELECT COUNT(*) FROM analytics_visitors'):0,
   'visitors_today'=>$exists['analytics_visitors']?countQ($db,'SELECT COUNT(*) FROM analytics_visitors WHERE first_seen>=CURDATE()'):0,
   'pageviews'=>$exists['analytics_pageviews']?countQ($db,'SELECT COUNT(*) FROM analytics_pageviews'):0,
   'pageviews_today'=>$exists['analytics_pageviews']?countQ($db,'SELECT COUNT(*) FROM analytics_pageviews WHERE viewed_at>=CURDATE()'):0,
   'live'=>$exists['analytics_live']?countQ($db,"SELECT COUNT(*) FROM analytics_live WHERE last_seen>=DATE_SUB(NOW(),INTERVAL 2 MINUTE)"):0,
   'events_today'=>$exists['analytics_events']?countQ($db,'SELECT COUNT(*) FROM analytics_events WHERE viewed_at>=CURDATE()'):0,
   'ads'=>$exists['ads_settings']?countQ($db,'SELECT COUNT(*) FROM ads_settings'):0,
   'ads_enabled'=>$exists['ads_settings']?countQ($db,'SELECT COUNT(*) FROM ads_settings WHERE enabled=1'):0,
   'settings'=>$exists['smarttoolz_settings']?countQ($db,'SELECT COUNT(*) FROM smarttoolz_settings'):0,
 ],
];

if($exists['analytics_pageviews']){
 $data['traffic']=rowsQ($db,"SELECT DATE_FORMAT(viewed_at,'%H:00') label,COUNT(*) total FROM analytics_pageviews WHERE viewed_at>=DATE_SUB(NOW(),INTERVAL 24 HOUR) GROUP BY HOUR(viewed_at) ORDER BY HOUR(viewed_at)");
 $data['countries']=rowsQ($db,"SELECT COALESCE(NULLIF(country,''),'Unknown') label,COUNT(*) total FROM analytics_pageviews WHERE viewed_at>=DATE_SUB(NOW(),INTERVAL 30 DAY) GROUP BY country ORDER BY total DESC LIMIT 10");
 $data['pages']=rowsQ($db,"SELECT page_path label,COUNT(*) total FROM analytics_pageviews WHERE viewed_at>=DATE_SUB(NOW(),INTERVAL 7 DAY) GROUP BY page_path ORDER BY total DESC LIMIT 8");
 $data['devices']=rowsQ($db,"SELECT COALESCE(NULLIF(device,''),'Unknown') label,COUNT(*) total FROM analytics_pageviews WHERE viewed_at>=DATE_SUB(NOW(),INTERVAL 30 DAY) GROUP BY device ORDER BY total DESC LIMIT 6");
 $data['browsers']=rowsQ($db,"SELECT COALESCE(NULLIF(browser,''),'Unknown') label,COUNT(*) total FROM analytics_pageviews WHERE viewed_at>=DATE_SUB(NOW(),INTERVAL 30 DAY) GROUP BY browser ORDER BY total DESC LIMIT 6");
}else{$data['traffic']=$data['countries']=$data['pages']=$data['devices']=$data['browsers']=[];}

if($exists['analytics_live']){$data['live_rows']=rowsQ($db,"SELECT visitor_id,country_code,city,device,browser,page_path,last_seen FROM analytics_live WHERE last_seen>=DATE_SUB(NOW(),INTERVAL 2 MINUTE) ORDER BY last_seen DESC LIMIT 40");}else{$data['live_rows']=[];}

if($exists['tool_usage']){
 $data['recent_tools']=rowsQ($db,'SELECT * FROM tool_usage ORDER BY created_at DESC LIMIT 15');
}else{$data['recent_tools']=[];}

if($exists['download_tracking']){
 $data['recent_downloads']=rowsQ($db,'SELECT * FROM download_tracking ORDER BY created_at DESC LIMIT 15');
}else{$data['recent_downloads']=[];}

echo json_encode($data,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
