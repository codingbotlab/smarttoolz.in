<?php
declare(strict_types=1);
session_start();
require __DIR__.'/bot.php';

// Always use Keddy's dedicated OAuth client and exact registered callback.
$c=googleCredentials();
$state=bin2hex(random_bytes(24));
$_SESSION['keddy_oauth_state']=$state;
$url='https://accounts.google.com/o/oauth2/v2/auth?'.http_build_query([
    'client_id'=>KEDDY_GOOGLE_CLIENT_ID,
    'redirect_uri'=>KEDDY_REDIRECT_URI,
    'response_type'=>'code',
    'scope'=>'https://www.googleapis.com/auth/youtube.force-ssl',
    'access_type'=>'offline',
    'prompt'=>'consent select_account',
    'state'=>$state,
]);
header('Location: '.$url);
exit;
