<?php
declare(strict_types=1);

/* Reddott Films — separate YouTube OAuth start flow. */
require_once __DIR__ . '/config.php';

reddottYoutubeRequireCredentials();

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$redirectUri = 'https://smarttoolz.in/Reddott-films/youtube/oauth.php';
$state = bin2hex(random_bytes(32));
$_SESSION['reddott_youtube_oauth_state'] = $state;

$params = [
    'client_id' => reddottYoutubeClientId(),
    'redirect_uri' => $redirectUri,
    'response_type' => 'code',
    'scope' => implode(' ', [
        'https://www.googleapis.com/auth/youtube.upload',
        'https://www.googleapis.com/auth/youtube.force-ssl',
    ]),
    'state' => $state,
    'access_type' => 'offline',
    'include_granted_scopes' => 'true',
    'prompt' => 'consent select_account',
];

header('Location: https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query($params, '', '&', PHP_QUERY_RFC3986));
exit;
