<?php
declare(strict_types=1);

/*
 * Reddott Films — YouTube storage/API helper.
 *
 * Tokens are encrypted before they are written to the existing SmartToolz
 * database. No YouTube credential is stored in GitHub.
 */

require_once __DIR__ . '/creator-ai/auth/config.php';

const REDDOTT_YOUTUBE_REDIRECT_URI = 'https://smarttoolz.in/reddott-films-youtube-oauth.php';

function reddottYoutubeEnsureTable(): void
{
    static $ready = false;
    if ($ready) {
        return;
    }

    db()->exec(
        "CREATE TABLE IF NOT EXISTS reddott_youtube_connection (
            id TINYINT UNSIGNED NOT NULL PRIMARY KEY,
            channel_id VARCHAR(128) NOT NULL,
            channel_title VARCHAR(255) NOT NULL,
            access_token TEXT NOT NULL,
            refresh_token TEXT NULL,
            access_token_expires_at DATETIME NULL,
            created_at DATETIME NOT NULL,
            updated_at DATETIME NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
    );

    $ready = true;
}

function reddottYoutubeCryptoKey(): string
{
    if (!defined('GOOGLE_CLIENT_SECRET') || GOOGLE_CLIENT_SECRET === '') {
        throw new RuntimeException('Google OAuth client secret is not configured.');
    }

    return hash('sha256', 'ReddottFilms::YouTubeTokenStore::' . GOOGLE_CLIENT_SECRET, true);
}

function reddottYoutubeEncrypt(string $plaintext): string
{
    $key = reddottYoutubeCryptoKey();
    $iv = random_bytes(12);
    $tag = '';
    $ciphertext = openssl_encrypt($plaintext, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);

    if ($ciphertext === false) {
        throw new RuntimeException('Unable to encrypt YouTube credential.');
    }

    return base64_encode($iv . $tag . $ciphertext);
}

function reddottYoutubeDecrypt(string $encoded): string
{
    $raw = base64_decode($encoded, true);
    if ($raw === false || strlen($raw) < 28) {
        throw new RuntimeException('Invalid encrypted YouTube credential.');
    }

    $iv = substr($raw, 0, 12);
    $tag = substr($raw, 12, 16);
    $ciphertext = substr($raw, 28);
    $plaintext = openssl_decrypt($ciphertext, 'aes-256-gcm', reddottYoutubeCryptoKey(), OPENSSL_RAW_DATA, $iv, $tag);

    if ($plaintext === false) {
        throw new RuntimeException('Unable to decrypt YouTube credential.');
    }

    return $plaintext;
}

function reddottYoutubeSaveConnection(
    string $channelId,
    string $channelTitle,
    string $accessToken,
    string $refreshToken,
    int $expiresIn
): void {
    reddottYoutubeEnsureTable();

    $now = (new DateTimeImmutable('now'))->format('Y-m-d H:i:s');
    $expiresAt = $expiresIn > 0
        ? (new DateTimeImmutable('now'))->modify('+' . $expiresIn . ' seconds')->format('Y-m-d H:i:s')
        : null;

    $existing = db()->query('SELECT refresh_token FROM reddott_youtube_connection WHERE id = 1 LIMIT 1')->fetchColumn();
    if ($refreshToken === '' && is_string($existing) && $existing !== '') {
        $refreshToken = reddottYoutubeDecrypt($existing);
    }

    $stmt = db()->prepare(
        'INSERT INTO reddott_youtube_connection
            (id, channel_id, channel_title, access_token, refresh_token, access_token_expires_at, created_at, updated_at)
         VALUES (1, ?, ?, ?, ?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE
            channel_id = VALUES(channel_id),
            channel_title = VALUES(channel_title),
            access_token = VALUES(access_token),
            refresh_token = VALUES(refresh_token),
            access_token_expires_at = VALUES(access_token_expires_at),
            updated_at = VALUES(updated_at)'
    );

    $stmt->execute([
        $channelId,
        $channelTitle,
        reddottYoutubeEncrypt($accessToken),
        $refreshToken !== '' ? reddottYoutubeEncrypt($refreshToken) : null,
        $expiresAt,
        $now,
        $now,
    ]);
}

function reddottYoutubeConnection(): ?array
{
    reddottYoutubeEnsureTable();
    $row = db()->query('SELECT * FROM reddott_youtube_connection WHERE id = 1 LIMIT 1')->fetch();
    return $row ?: null;
}

function reddottYoutubeAccessToken(): string
{
    $row = reddottYoutubeConnection();
    if (!$row) {
        throw new RuntimeException('YouTube is not connected.');
    }

    $expiresAt = !empty($row['access_token_expires_at'])
        ? new DateTimeImmutable((string)$row['access_token_expires_at'])
        : null;

    if ($expiresAt && $expiresAt > new DateTimeImmutable('+60 seconds')) {
        return reddottYoutubeDecrypt((string)$row['access_token']);
    }

    if (empty($row['refresh_token'])) {
        throw new RuntimeException('YouTube refresh token is missing. Reconnect YouTube.');
    }

    $refreshToken = reddottYoutubeDecrypt((string)$row['refresh_token']);
    $response = reddottYoutubeHttpPost(
        'https://oauth2.googleapis.com/token',
        [
            'client_id' => GOOGLE_CLIENT_ID,
            'client_secret' => GOOGLE_CLIENT_SECRET,
            'refresh_token' => $refreshToken,
            'grant_type' => 'refresh_token',
        ]
    );

    $token = json_decode($response, true);
    if (!is_array($token) || empty($token['access_token'])) {
        throw new RuntimeException('Unable to refresh YouTube access token.');
    }

    $accessToken = (string)$token['access_token'];
    $expiresIn = max(0, (int)($token['expires_in'] ?? 3600));
    $now = (new DateTimeImmutable('now'))->format('Y-m-d H:i:s');
    $expiresAtNew = (new DateTimeImmutable('now'))->modify('+' . $expiresIn . ' seconds')->format('Y-m-d H:i:s');

    $stmt = db()->prepare('UPDATE reddott_youtube_connection SET access_token = ?, access_token_expires_at = ?, updated_at = ? WHERE id = 1');
    $stmt->execute([reddottYoutubeEncrypt($accessToken), $expiresAtNew, $now]);

    return $accessToken;
}

function reddottYoutubeHttpPost(string $url, array $data): string
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
        throw new RuntimeException('Google token endpoint returned HTTP ' . $httpCode . '.');
    }

    return (string)$result;
}

function reddottYoutubeHttpGet(string $url, string $accessToken): string
{
    $ch = curl_init($url);
    if ($ch === false) {
        throw new RuntimeException('Unable to initialize cURL.');
    }

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $accessToken],
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
        throw new RuntimeException('YouTube API returned HTTP ' . $httpCode . '.');
    }

    return (string)$result;
}
