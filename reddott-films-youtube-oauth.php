<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$state = (string)($_GET['state'] ?? '');

// Keddy uses the same Google OAuth client as Reddott Films. Route its
// callback by the session state so both integrations can share the
// redirect URI already registered in Google Cloud.
$keddyState = (string)($_SESSION['keddy_oauth_state'] ?? '');
if ($state !== '' && $keddyState !== '' && hash_equals($keddyState, $state)) {
    require __DIR__ . '/keddy-bot/oauth-callback.php';
    exit;
}

$reddottState = (string)($_SESSION['reddott_youtube_oauth_state'] ?? '');
if ($state !== '' && $reddottState !== '' && hash_equals($reddottState, $state)) {
    require __DIR__ . '/Reddott-films/youtube/oauth.php';
    exit;
}

http_response_code(400);
echo '<!doctype html><meta charset="utf-8"><title>YouTube OAuth Error</title>';
echo '<h1>YouTube OAuth Error</h1><p>Invalid or expired OAuth state. Start the YouTube connection again.</p>';
