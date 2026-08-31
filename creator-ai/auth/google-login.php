<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

if (
    !defined('GOOGLE_CLIENT_ID') ||
    !defined('GOOGLE_CLIENT_SECRET') ||
    !defined('GOOGLE_REDIRECT_URI') ||
    GOOGLE_CLIENT_ID === '' ||
    GOOGLE_CLIENT_SECRET === '' ||
    GOOGLE_REDIRECT_URI === ''
) {
    http_response_code(500);
    exit('Google OAuth is not configured. Check config.php.');
}

$state = bin2hex(random_bytes(32));

$_SESSION['oauth_state'] = $state;

$params = [
    'client_id' => GOOGLE_CLIENT_ID,
    'redirect_uri' => GOOGLE_REDIRECT_URI,
    'response_type' => 'code',
    'scope' => 'openid email profile',
    'state' => $state,
    'access_type' => 'online',
    'prompt' => 'select_account',
];

$googleUrl =
    'https://accounts.google.com/o/oauth2/v2/auth?' .
    http_build_query($params, '', '&', PHP_QUERY_RFC3986);

header('Location: ' . $googleUrl);
exit;