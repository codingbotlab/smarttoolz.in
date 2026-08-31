<?php
declare(strict_types=1);
require_once __DIR__ . '/../auth/config.php';
requireLogin();
header('Content-Type: application/json; charset=utf-8');
function out(array $d,int $s=200):never{http_response_code($s);echo json_encode($d,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);exit;}
$userId=(int)($_SESSION['user_id']??0); $id=(int)($_GET['id']??0);
if($userId<=0) out(['ok'=>false,'error'=>'Please login again.'],401);
if($id<=0) out(['ok'=>false,'error'=>'Invalid chat id.'],400);
try{
 $pdo=db();
 $st=$pdo->prepare("SELECT id,title,created_at,updated_at FROM creator_ai_sessions WHERE id=? AND user_id=? AND is_archived=0 LIMIT 1");
 $st->execute([$id,$userId]); $s=$st->fetch(PDO::FETCH_ASSOC);
 if(!$s) out(['ok'=>false,'error'=>'Chat not found.'],404);
 $st=$pdo->prepare("SELECT id,mode,prompt,response,credits_used,created_at FROM creator_ai_history WHERE user_id=? AND session_id=? ORDER BY id ASC LIMIT 200");
 $st->execute([$userId,$id]); $m=$st->fetchAll(PDO::FETCH_ASSOC);
 out(['ok'=>true,'session'=>$s,'messages'=>$m]);
}catch(Throwable $e){error_log('Session load: '.$e->getMessage());out(['ok'=>false,'error'=>'Could not load chat.'],500);}
