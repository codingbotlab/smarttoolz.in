<?php
declare(strict_types=1);
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
require_once $_SERVER['DOCUMENT_ROOT'].'/creator-ai/auth/config.php';
try {
  $pdo=db(); if(!$pdo instanceof PDO) throw new RuntimeException('DB');
  if($_SERVER['REQUEST_METHOD']!=='POST'){ echo json_encode(['ok'=>true,'service'=>'SmartToolz Analytics Events']); exit; }
  $raw=file_get_contents('php://input'); $d=json_decode($raw,true); if(!is_array($d)) $d=$_POST;
  $type=preg_replace('/[^a-z0-9_.-]/i','',substr((string)($d['type']??'event'),0,40));
  $tool=preg_replace('/[^a-z0-9_-]/i','',substr((string)($d['tool']??''),0,120));
  $path=substr((string)($d['path']??($_SERVER['REQUEST_URI']??'')),0,1000);
  $target=substr((string)($d['target']??''),0,500);
  $value=substr((string)($d['value']??''),0,500);
  $meta=$d['meta']??[]; if(!is_array($meta)) $meta=[];
  $meta=json_encode($meta,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
  $visitor=(string)($_COOKIE['smarttooz_visitor']??''); $session=(string)($_COOKIE['smarttooz_session']??'');
  $uid=isset($_SESSION['user_id'])?(int)$_SESSION['user_id']:null;
  $pdo->exec("CREATE TABLE IF NOT EXISTS smarttoolz_analytics_events (id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,event_type VARCHAR(40) NOT NULL,tool_slug VARCHAR(120) NOT NULL DEFAULT '',page_path VARCHAR(1000) NOT NULL DEFAULT '',target VARCHAR(500) NOT NULL DEFAULT '',event_value VARCHAR(500) NOT NULL DEFAULT '',metadata JSON NULL,visitor_id CHAR(32) NOT NULL DEFAULT '',session_id CHAR(32) NOT NULL DEFAULT '',user_id BIGINT NULL,created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,INDEX idx_event_type(event_type),INDEX idx_tool(tool_slug),INDEX idx_created(created_at),INDEX idx_visitor(visitor_id),INDEX idx_session(session_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
  $s=$pdo->prepare('INSERT INTO smarttoolz_analytics_events(event_type,tool_slug,page_path,target,event_value,metadata,visitor_id,session_id,user_id) VALUES(?,?,?,?,?,?,?,?,?)');
  $s->execute([$type,$tool,$path,$target,$value,$meta,$visitor,$session,$uid]);
  echo json_encode(['ok'=>true]);
} catch(Throwable $e){ http_response_code(200); echo json_encode(['ok'=>false]); }
