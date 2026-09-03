<?php
declare(strict_types=1);
session_start();
require __DIR__.'/bot.php';

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
    echo json_encode(['ok'=>true,'live'=>!empty($r['live']),'session'=>$r['session']],JSON_UNESCAPED_UNICODE).PHP_EOL;
}catch(Throwable$e){
    http_response_code(500);
    echo json_encode(['ok'=>false,'error'=>$e->getMessage()],JSON_UNESCAPED_UNICODE).PHP_EOL;
}
