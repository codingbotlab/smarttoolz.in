<?php
declare(strict_types=1);
session_start();
require __DIR__.'/bot.php';
require_once __DIR__.'/registered-users.php';

try{
    if(!hash_equals($_SESSION['keddy_oauth_state']??'',(string)($_GET['state']??'')))throw new RuntimeException('Invalid OAuth state. Please start Connect YouTube again.');
    $code=(string)($_GET['code']??'');
    if($code==='')throw new RuntimeException('Google authorization code missing.');
    $c=googleCredentials();
    $j=req('https://oauth2.googleapis.com/token','POST',['Content-Type: application/x-www-form-urlencoded'],http_build_query(['code'=>$code,'client_id'=>$c['id'],'client_secret'=>$c['secret'],'redirect_uri'=>$c['redirect'],'grant_type'=>'authorization_code']));
    if(empty($j['access_token']))throw new RuntimeException('Google did not return an access token.');
    $j['expires_at']=time()+(int)($j['expires_in']??3600);sj('oauth',$j);unset($_SESSION['keddy_oauth_state']);
    $channel=connectedChannel();
    if($channel){sv('oauth_channel_id',(string)$channel['id']);sv('oauth_channel_title',(string)$channel['title']);registerKeddyUser($channel);try{keddyDataCaptureChannelById((string)$channel['id'],'oauth',true,false,token());}catch(Throwable $e){}}
    $welcomed=false;if(gv('bot_oauth')){try{$welcomed=welcomeBotToLive();}catch(Throwable $e){}}
    header('Location: /keddy-bot/index.php?connected=1&welcomed='.(int)$welcomed);exit;
}catch(Throwable $e){http_response_code(400);echo '<!doctype html><meta charset="utf-8"><title>Keddy OAuth Error</title><h1>Keddy OAuth Error</h1><p>'.htmlspecialchars($e->getMessage(),ENT_QUOTES,'UTF-8').'</p><p><a href="/keddy-bot/index.php">Back to Keddy</a></p>';}