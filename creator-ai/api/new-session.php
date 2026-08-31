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
 $st=$pdo->prepare("INSERT INTO creator_ai_sessions(user_id,title) VALUES(?,?)");
 $st->execute([$userId,'New chat']);
 out(['ok'=>true,'session_id'=>(int)$pdo->lastInsertId(),'title'=>'New chat']);
}catch(Throwable $e){error_log('New session: '.$e->getMessage());out(['ok'=>false,'error'=>'Could not create chat session.'],500);}
