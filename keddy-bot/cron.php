<?php
declare(strict_types=1);require __DIR__.'/bot.php';try{$r=tick();echo json_encode(['ok'=>true,'live'=>!empty($r['live']),'session'=>$r['session']],JSON_UNESCAPED_UNICODE).PHP_EOL;}catch(Throwable$e){http_response_code(500);echo json_encode(['ok'=>false,'error'=>$e->getMessage()],JSON_UNESCAPED_UNICODE).PHP_EOL;}
