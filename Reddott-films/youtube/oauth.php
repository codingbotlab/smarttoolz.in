<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/lib.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (isset($_GET['error'])) {
    http_response_code(400);
    exit('YouTube authorization was cancelled or failed.');
}

$state = (string)($_GET['state'] ?? '');
$expected = (string)($_SESSION['reddott_youtube_oauth_state'] ?? '');
if ($state === '' || $expected === '' || !hash_equals($expected, $state)) {
    http_response_code(400);
    exit('Invalid Reddott Films OAuth state. Start the YouTube connection again.');
}
unset($_SESSION['reddott_youtube_oauth_state']);

$code = (string)($_GET['code'] ?? '');
if ($code === '') {
    http_response_code(400);
    exit('Missing Google authorization code.');
}

reddottYoutubeRequireCredentials();

try {
    $response = reddottYoutubeHttpPost(
        'https://oauth2.googleapis.com/token',
        [
            'code' => $code,
            'client_id' => reddottYoutubeClientId(),
            'client_secret' => reddottYoutubeClientSecret(),
            'redirect_uri' => REDDOTT_YOUTUBE_REDIRECT_URI,
            'grant_type' => 'authorization_code',
        ]
    );

    $token = json_decode($response, true);
    if (!is_array($token) || empty($token['access_token'])) {
        throw new RuntimeException('Unable to obtain YouTube access token.');
    }

    $accessToken = (string)$token['access_token'];
    $refreshToken = (string)($token['refresh_token'] ?? '');
    $expiresIn = max(0, (int)($token['expires_in'] ?? 3600));

    $channelResponse = reddottYoutubeHttpGet(
        'https://www.googleapis.com/youtube/v3/channels?' . http_build_query(
            [
                'part' => 'snippet,contentDetails,statistics',
                'mine' => 'true',
            ],
            '',
            '&',
            PHP_QUERY_RFC3986
        ),
        $accessToken
    );

    $channels = json_decode($channelResponse, true);
    if (!is_array($channels) || empty($channels['items'][0])) {
        throw new RuntimeException('No YouTube channel was available for this Google account.');
    }

    $channel = $channels['items'][0];
    $channelId = (string)($channel['id'] ?? '');
    $channelTitle = (string)($channel['snippet']['title'] ?? 'YouTube Channel');
    if ($channelId === '') {
        throw new RuntimeException('YouTube returned an invalid channel.');
    }

    reddottYoutubeSaveConnection($channelId, $channelTitle, $accessToken, $refreshToken, $expiresIn);

    $_SESSION['reddott_youtube_connected'] = true;
    $_SESSION['reddott_youtube_channel_id'] = $channelId;
    $_SESSION['reddott_youtube_channel_title'] = $channelTitle;

    header('Location: /Reddott-films/youtube/dashboard.php?youtube=connected');
    exit;
} catch (Throwable $e) {
    error_log('Reddott Films YouTube OAuth error: ' . $e->getMessage());
    http_response_code(500);
    exit('YouTube authorization succeeded, but the connection could not be saved. Please try connecting again.');
}
