<?php
declare(strict_types=1);
session_start();
require __DIR__.'/bot.php';
require_once __DIR__.'/comments-engine.php';
require_once __DIR__.'/growth-engine.php';

if (isset($_GET['heartbeat'])) {
    $expected = $_SESSION['keddy_heartbeat_token'] ?? '';
    $provided = (string)($_GET['token'] ?? '');
    if (!$expected || !$provided || !hash_equals($expected, $provided)) {
        http_response_code(403);
        echo json_encode(['ok'=>false,'error'=>'Forbidden'],JSON_UNESCAPED_UNICODE).PHP_EOL;
        exit;
    }
}

try{
    $r=tick();
    $growthRun=false;
    if(!empty($r['live']['chat'])){
        try{
            ensureBotModerator((string)$r['live']['chat']);
        }catch(Throwable $e){
            sv('cron_moderator_error',$e->getMessage());
            sv('cron_moderator_error_at',(string)time());
        }
        try{
            $growthRun=keddyGrowthTick((array)$r['live']);
        }catch(Throwable $e){
            sv('growth_tick_error',$e->getMessage());
            sv('growth_tick_error_at',(string)time());
        }
    }
    $commentRun=null;
    if(isset($_GET['comments'])&&$_GET['comments']==='1'){
        $commentRun=runViewerVideoCommentTick(true);
    }else{
        $commentRun=runViewerVideoCommentTick(false);
    }
    echo json_encode([
        'ok'=>true,
        'live'=>!empty($r['live']),
        'session'=>$r['session'],
        'growth'=>$growthRun,
        'growth_viewers'=>(int)gv('growth_viewer_count','0'),
        'viewer_comments'=>$commentRun
    ],JSON_UNESCAPED_UNICODE).PHP_EOL;
}catch(Throwable$e){
    http_response_code(500);
    echo json_encode(['ok'=>false,'error'=>$e->getMessage()],JSON_UNESCAPED_UNICODE).PHP_EOL;
}
