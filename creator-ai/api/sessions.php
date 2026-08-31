<?php
declare(strict_types=1);
require_once __DIR__ . '/../auth/config.php';
requireLogin();
header('Content-Type: application/json; charset=utf-8');
function out(array $d,int $s=200):never{http_response_code($s);echo json_encode($d,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);exit;}
$userId=(int)($_SESSION['user_id']??0);
if($userId<=0) out(['ok'=>false,'error'=>'Please login again.'],401);
try{
 $pdo=db();
 $st=$pdo->prepare("SELECT id,title,created_at,updated_at FROM creator_ai_sessions WHERE user_id=? AND is_archived=0 ORDER BY updated_at DESC,id DESC LIMIT 80");
 $st->execute([$userId]); out(['ok'=>true,'sessions'=>$st->fetchAll(PDO::FETCH_ASSOC)]);
}catch(Throwable $e){error_log('Sessions list: '.$e->getMessage());out(['ok'=>false,'error'=>'Could not load chat history.'],500);}
