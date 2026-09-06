<?php
declare(strict_types=1);
require_once __DIR__.'/bootstrap.php';
$admin=requireAdmin();
$db=adminDb();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
function jh($v):string{return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
try{
  $after=max(0,(int)($_GET['after']??0));
  $rows=[];
  $q=$db->prepare("SELECT id,created_at,event_type,tool_slug,target,event_value,page_path,visitor_id,metadata FROM smarttoolz_analytics_events WHERE id>? ORDER BY id ASC LIMIT 40");
  $q->execute([$after]);$rows=$q->fetchAll(PDO::FETCH_ASSOC);
  $last=$after;$items=[];
  $names=['page_view'=>'opened a page','tool_open'=>'opened a tool','tool_use'=>'used a tool control','download'=>'downloaded something','click'=>'clicked','input'=>'entered data','change'=>'changed a field','submit'=>'submitted a form','scroll'=>'scrolled','focus'=>'focused an input','heartbeat'=>'is still active'];
  foreach($rows as $r){$last=(int)$r['id'];$type=(string)$r['event_type'];$tool=(string)$r['tool_slug'];$target=trim((string)$r['target']);$value=trim((string)$r['event_value']);$page=(string)$r['page_path'];$where=$tool?' in '.$tool:($page?' on '.$page:'');$what=$names[$type]??('triggered '.$type);$text='Visitor '. $what .$where; if($target)$text.=' — “'.$target.'”'; if($value&&in_array($type,['download','change','input'],true))$text.=' → '.$value; if($type==='heartbeat')$text='Visitor is still active on '.$page; $items[]=['id'=>$last,'time'=>$r['created_at'],'type'=>$type,'tool'=>$tool,'text'=>$text,'visitor'=>substr((string)$r['visitor_id'],0,8),'page'=>$page];}
  $stats=[];
  $stats['live']=(int)$db->query("SELECT COUNT(*) FROM analytics_live WHERE last_seen>=DATE_SUB(NOW(),INTERVAL 2 MINUTE)")->fetchColumn();
  $stats['events_24h']=(int)$db->query("SELECT COUNT(*) FROM smarttoolz_analytics_events WHERE created_at>=DATE_SUB(NOW(),INTERVAL 24 HOUR)")->fetchColumn();
  $stats['uses_24h']=(int)$db->query("SELECT COUNT(*) FROM smarttoolz_analytics_events WHERE created_at>=DATE_SUB(NOW(),INTERVAL 24 HOUR) AND event_type='tool_use'")->fetchColumn();
  $stats['downloads_24h']=(int)$db->query("SELECT COUNT(*) FROM smarttoolz_analytics_events WHERE created_at>=DATE_SUB(NOW(),INTERVAL 24 HOUR) AND event_type='download'")->fetchColumn();
  echo json_encode(['ok'=>true,'last_id'=>$last,'events'=>$items,'stats'=>$stats],JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
}catch(Throwable $e){http_response_code(200);echo json_encode(['ok'=>false,'last_id'=>$after,'events'=>[],'stats'=>[]]);}
