<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/lib/trials.php';
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
try {
    $tool=(string)($_REQUEST['tool']??'');
    $action=(string)($_REQUEST['action']??'status');
    $userId=smarttoolz_current_user_id();
    if($userId){
        if($action==='consume'){
            // Logged-in users do not receive trials. Their normal tool/credit flow must handle billing.
            echo json_encode(['ok'=>false,'reason'=>'login_user','message'=>'Logged-in users use credits; no free trial applies.']); exit;
        }
        echo json_encode(['ok'=>true,'logged_in'=>true,'credits_required'=>true,'message'=>'Logged-in users use credits.']); exit;
    }
    if($action==='consume'){$result=smarttoolz_guest_trial_consume($tool);echo json_encode($result,JSON_UNESCAPED_UNICODE);exit;}
    echo json_encode(['ok'=>true]+smarttoolz_guest_trial_status($tool),JSON_UNESCAPED_UNICODE);
}catch(Throwable $e){error_log('SmartToolz trial API: '.$e->getMessage());http_response_code(500);echo json_encode(['ok'=>false,'message'=>'Unable to check trial status right now.']);}
