<?php
declare(strict_types=1);
session_start();
require __DIR__.'/bot.php';
try{
    $c=googleCredentials();
    $state=bin2hex(random_bytes(24));
    $_SESSION['keddy_bot_oauth_state']=$state;
    $url='https://accounts.google.com/o/oauth2/v2/auth?'.http_build_query([
        'client_id'=>$c['id'],
        'redirect_uri'=>$c['bot_redirect'],
        'response_type'=>'code',
        'scope'=>'https://www.googleapis.com/auth/youtube.force-ssl',
        'access_type'=>'offline',
        'prompt'=>'consent select_account',
        'state'=>$state,
    ]);
    header('Location: '.$url);
    exit;
}catch(Throwable $e){
    http_response_code(400);
    echo '<!doctype html><meta charset="utf-8"><title>Keddy Bot BTS Connect</title><h1>Keddy Bot BTS Connect</h1><p>'.htmlspecialchars($e->getMessage(),ENT_QUOTES,'UTF-8').'</p>';
}
