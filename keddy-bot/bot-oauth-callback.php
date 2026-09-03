<?php
declare(strict_types=1);
session_start();
require __DIR__.'/bot.php';
try{
    if(!hash_equals($_SESSION['keddy_bot_oauth_state']??'',(string)($_GET['state']??'')))throw new RuntimeException('Invalid bot OAuth state. Start bot connection again.');
    $code=(string)($_GET['code']??'');
    if($code==='')throw new RuntimeException('Google authorization code missing.');
    $c=googleCredentials();
    $j=req('https://oauth2.googleapis.com/token','POST',['Content-Type: application/x-www-form-urlencoded'],http_build_query([
        'code'=>$code,'client_id'=>$c['id'],'client_secret'=>$c['secret'],'redirect_uri'=>$c['bot_redirect'],'grant_type'=>'authorization_code'
    ]));
    if(empty($j['access_token']))throw new RuntimeException('Google did not return a bot access token.');
    $j['expires_at']=time()+(int)($j['expires_in']??3600);
    $bot=channelForToken((string)$j['access_token']);
    if(!$bot||strcasecmp($bot['title'],KEDDY_BOT_CHANNEL_NAME)!==0){
        throw new RuntimeException('This Google account is not the Keddy Bot BTS YouTube channel.');
    }
    sj('bot_oauth',$j);
    unset($_SESSION['keddy_bot_oauth_state']);
    $welcomed=false;
    if(gv('oauth')){try{$welcomed=welcomeBotToLive();}catch(Throwable $e){}}
    header('Location: /keddy-bot/index.php?bot_connected=1&welcomed='.(int)$welcomed);
    exit;
}catch(Throwable $e){
    http_response_code(400);
    echo '<!doctype html><meta charset="utf-8"><title>Keddy Bot BTS OAuth Error</title><h1>Keddy Bot BTS OAuth Error</h1><p>'.htmlspecialchars($e->getMessage(),ENT_QUOTES,'UTF-8').'</p><p><a href="bot-oauth.php">Try again</a></p>';
}
