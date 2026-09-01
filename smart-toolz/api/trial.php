<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/lib/trials.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
try{
 $tool=(string)($_REQUEST['tool']??'');
 $action=(string)($_REQUEST['action']??'status');
 $requestId=(string)($_REQUEST['request_id']??'');
 $userId=smarttoolz_current_user_id();
 if($userId){
  if($action==='consume'){
   echo json_encode(smarttoolz_trial_consume($userId,$tool,$requestId),JSON_UNESCAPED_UNICODE); exit;
  }
  echo json_encode(['ok'=>true,'logged_in'=>true,'credits_required'=>true]+smarttoolz_trial_status($userId,$tool),JSON_UNESCAPED_UNICODE); exit;
 }
 if($action==='consume'){
  echo json_encode(smarttoolz_guest_trial_consume($tool,$requestId),JSON_UNESCAPED_UNICODE); exit;
 }
 echo json_encode(['ok'=>true]+smarttoolz_guest_trial_status($tool),JSON_UNESCAPED_UNICODE);
}catch(Throwable $e){error_log('SmartToolz trial API: '.$e->getMessage());http_response_code(500);echo json_encode(['ok'=>false,'message'=>'Unable to check trial status right now.']);}
