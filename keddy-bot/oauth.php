<?php
declare(strict_types=1);
session_start();
require __DIR__.'/bot.php';
$c=cfg();
$c['google_client_id']='523738407628-o1c4t43j4vjriajnvojpt4cio4mktr01.apps.googleusercontent.com';
$c['redirect_uri']='https://smarttoolz.in/keddy-bot/oauth-callback.php';
if(empty($c['google_client_secret'])) die('Configure the Keddy Google Client Secret first.');
$state=bin2hex(random_bytes(24));
$_SESSION['keddy_oauth_state']=$state;
$url='https://accounts.google.com/o/oauth2/v2/auth?'.http_build_query([
    'client_id'=>$c['google_client_id'],
    'redirect_uri'=>$c['redirect_uri'],
    'response_type'=>'code',
    'scope'=>'https://www.googleapis.com/auth/youtube.force-ssl',
    'access_type'=>'offline',
    'prompt'=>'consent select_account',
    'state'=>$state,
]);
header('Location: '.$url);
exit;
