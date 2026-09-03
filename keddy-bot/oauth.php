<?php
declare(strict_types=1);
session_start();
require __DIR__.'/bot.php';

try {
    // Use the exact credentials saved by setup.php. Do not hard-code a
    // different client ID here; the authorization request and token exchange
    // must use the same client ID, secret and redirect URI.
    $c=googleCredentials();
    $state=bin2hex(random_bytes(24));
    $_SESSION['keddy_oauth_state']=$state;
    $_SESSION['keddy_oauth_client_id']=$c['id'];
    $_SESSION['keddy_oauth_redirect_uri']=$c['redirect'];

    $url='https://accounts.google.com/o/oauth2/v2/auth?'.http_build_query([
        'client_id'=>$c['id'],
        'redirect_uri'=>$c['redirect'],
        'response_type'=>'code',
        'scope'=>'https://www.googleapis.com/auth/youtube.force-ssl',
        'access_type'=>'offline',
        'prompt'=>'consent select_account',
        'state'=>$state,
    ]);
    header('Location: '.$url);
    exit;
} catch (Throwable $e) {
    http_response_code(400);
    echo '<!doctype html><meta charset="utf-8"><title>Keddy OAuth Error</title>';
    echo '<h1>Keddy OAuth Error</h1><p>'.htmlspecialchars($e->getMessage(),ENT_QUOTES,'UTF-8').'</p>';
    echo '<p><a href="index.php">Back to Keddy</a></p>';
}
