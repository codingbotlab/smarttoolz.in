<?php
declare(strict_types=1);

/*
 * Reddott Films — YouTube OAuth start.
 * Kept inside the Reddott-films workspace.
 */
require_once dirname(__DIR__, 2) . '/creator-ai/auth/config.php';

if (!defined('GOOGLE_CLIENT_ID') || GOOGLE_CLIENT_ID === '') {
    http_response_code(500);
    exit('Google OAuth client is not configured.');
}

$redirectUri = 'https://smarttoolz.in/Reddott-films/youtube/oauth.php';
$state = bin2hex(random_bytes(32));
$_SESSION['reddott_youtube_oauth_state'] = $state;

$params = [
    'client_id' => GOOGLE_CLIENT_ID,
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
