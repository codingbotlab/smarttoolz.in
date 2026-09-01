<?php
declare(strict_types=1);

/*
 * Reddott Films — YouTube OAuth callback
 *
 * Separate from /creator-ai/auth/google-callback.php.
 * Tokens are kept in the current server session for the initial connection
 * test. Persistent token storage will be added after the OAuth flow is
 * verified successfully, so no credential is written to the repository.
 */

require_once __DIR__ . '/creator-ai/auth/config.php';

$redirectUri = 'https://smarttoolz.in/reddott-films-youtube-oauth.php';

if (isset($_GET['error'])) {
    http_response_code(400);
    exit('YouTube authorization was cancelled or failed: ' . htmlspecialchars((string)$_GET['error'], ENT_QUOTES, 'UTF-8'));
}

$state = (string)($_GET['state'] ?? '');
$expectedState = (string)($_SESSION['reddott_youtube_oauth_state'] ?? '');

if ($state === '' || $expectedState === '' || !hash_equals($expectedState, $state)) {
    http_response_code(400);
    exit('Invalid Reddott Films OAuth state. Start the YouTube connection again.');
}

unset($_SESSION['reddott_youtube_oauth_state']);

$code = (string)($_GET['code'] ?? '');
if ($code === '') {
    http_response_code(400);
    exit('Missing Google authorization code.');
}

if (!defined('GOOGLE_CLIENT_ID') || !defined('GOOGLE_CLIENT_SECRET') || GOOGLE_CLIENT_ID === '' || GOOGLE_CLIENT_SECRET === '') {
    http_response_code(500);
    exit('Google OAuth client is not configured.');
}

try {
    $tokenResponse = httpPost(
        'https://oauth2.googleapis.com/token',
        [
            'code' => $code,
            'client_id' => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'redirect_uri' => $redirectUri,
            'grant_type' => 'authorization_code',
        ]
    );
} catch (Throwable $e) {
    error_log('Reddott Films YouTube token error: ' . $e->getMessage());
    http_response_code(500);
    exit('Unable to connect to Google.');
}

$token = json_decode($tokenResponse, true);
if (!is_array($token) || empty($token['access_token'])) {
    error_log('Reddott Films YouTube token response: ' . $tokenResponse);
    http_response_code(400);
    exit('Unable to obtain YouTube access token.');
}

$accessToken = (string)$token['access_token'];
$refreshToken = (string)($token['refresh_token'] ?? '');
$expiresIn = max(0, (int)($token['expires_in'] ?? 0));

try {
    $channelResponse = httpGet(
        'https://www.googleapis.com/youtube/v3/channels?' . http_build_query([
            'part' => 'snippet,contentDetails,statistics',
            'mine' => 'true',
        ], '', '&', PHP_QUERY_RFC3986),
        ['Authorization: Bearer ' . $accessToken]
    );
} catch (Throwable $e) {
    error_log('Reddott Films YouTube channel error: ' . $e->getMessage());
    http_response_code(500);
    exit('Google authorization succeeded, but the YouTube channel could not be read.');
}

$channels = json_decode($channelResponse, true);
if (!is_array($channels) || empty($channels['items'][0])) {
    error_log('Reddott Films YouTube channel response: ' . $channelResponse);
    http_response_code(400);
    exit('No YouTube channel was available for this Google account.');
}

$channel = $channels['items'][0];
$channelId = (string)($channel['id'] ?? '');
$channelTitle = (string)($channel['snippet']['title'] ?? 'YouTube Channel');

/*
 * Initial test storage only. Never write these credentials to GitHub.
 * Refresh token is retained in the server session when Google returns one.
 */
$_SESSION['reddott_youtube_connected'] = true;
$_SESSION['reddott_youtube_channel_id'] = $channelId;
$_SESSION['reddott_youtube_channel_title'] = $channelTitle;
$_SESSION['reddott_youtube_access_token'] = $accessToken;
$_SESSION['reddott_youtube_access_token_expires_at'] = time() + $expiresIn;

if ($refreshToken !== '') {
    $_SESSION['reddott_youtube_refresh_token'] = $refreshToken;
}

header('Content-Type: text/html; charset=UTF-8');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Reddott Films — YouTube Connected</title>
    <style>
        body{margin:0;background:#0b0d12;color:#f5f7fb;font-family:Arial,sans-serif;display:grid;place-items:center;min-height:100vh}
        .card{width:min(680px,calc(100% - 32px));box-sizing:border-box;background:#151922;border:1px solid #2a3140;border-radius:18px;padding:32px;box-shadow:0 18px 60px rgba(0,0,0,.35)}
        .ok{font-size:48px;margin-bottom:12px}
        h1{margin:0 0 10px;font-size:28px}
        p{color:#b9c1d0;line-height:1.6}
        .channel{margin:22px 0;padding:18px;border-radius:12px;background:#0e121a;border:1px solid #293142}
        .channel strong{display:block;font-size:18px;margin-bottom:6px}
        a{display:inline-block;margin-top:8px;color:#fff;background:#e11d48;text-decoration:none;padding:11px 16px;border-radius:10px}
    </style>
</head>
<body>
<main class="card">
    <div class="ok">✅</div>
    <h1>YouTube connected successfully</h1>
    <p>Reddott Films has successfully authorized access to the YouTube Data API.</p>
    <div class="channel">
        <strong><?= htmlspecialchars($channelTitle, ENT_QUOTES, 'UTF-8') ?></strong>
        <span>Channel ID: <?= htmlspecialchars($channelId, ENT_QUOTES, 'UTF-8') ?></span>
    </div>
    <p>The next step is to move the OAuth token from temporary session storage into secure persistent server-side storage for video management and automation.</p>
    <a href="/reddott-films.php">Back to Reddott Films</a>
</main>
</body>
</html>
<?php

function httpPost(string $url, array $data): string
{
    $ch = curl_init($url);
    if ($ch === false) {
        throw new RuntimeException('Unable to initialize cURL.');
    }

    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($data, '', '&', PHP_QUERY_RFC3986),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
        CURLOPT_TIMEOUT => 30,
    ]);

    $result = curl_exec($ch);
    if ($result === false) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new RuntimeException($error);
    }

    $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode < 200 || $httpCode >= 300) {
        throw new RuntimeException('Google token endpoint returned HTTP ' . $httpCode);
    }

    return (string)$result;
}

function httpGet(string $url, array $headers = []): string
{
    $ch = curl_init($url);
    if ($ch === false) {
        throw new RuntimeException('Unable to initialize cURL.');
    }

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 30,
    ]);

    $result = curl_exec($ch);
    if ($result === false) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new RuntimeException($error);
    }

    $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode < 200 || $httpCode >= 300) {
        throw new RuntimeException('YouTube API returned HTTP ' . $httpCode);
    }

    return (string)$result;
}
