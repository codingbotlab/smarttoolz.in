<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

const REDDOTT_YOUTUBE_REDIRECT_URI = 'https://smarttoolz.in/Reddott-films/youtube/oauth.php';

function reddottYoutubeEnsureTable(): void
{
    static $ready = false;
    if ($ready) {
        return;
    }

    db()->exec("CREATE TABLE IF NOT EXISTS reddott_youtube_connection (
        id TINYINT UNSIGNED NOT NULL PRIMARY KEY,
        channel_id VARCHAR(128) NOT NULL,
        channel_title VARCHAR(255) NOT NULL,
        access_token TEXT NOT NULL,
        refresh_token TEXT NULL,
        access_token_expires_at DATETIME NULL,
        created_at DATETIME NOT NULL,
        updated_at DATETIME NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $ready = true;
}

function reddottYoutubeCryptoKey(): string
{
    $secret = reddottYoutubeClientSecret();
    if ($secret === '') {
        throw new RuntimeException('Reddott Films YouTube client secret is not configured.');
    }

    return hash('sha256', 'ReddottFilms::YouTubeTokenStore::' . $secret, true);
}

function reddottYoutubeEncrypt(string $plaintext): string
{
    $iv = random_bytes(12);
    $tag = '';
    $ciphertext = openssl_encrypt(
        $plaintext,
        'aes-256-gcm',
        reddottYoutubeCryptoKey(),
        OPENSSL_RAW_DATA,
        $iv,
        $tag
    );

    if ($ciphertext === false) {
        throw new RuntimeException('Unable to encrypt credential.');
    }

    return base64_encode($iv . $tag . $ciphertext);
}

function reddottYoutubeDecrypt(string $encoded): string
{
    $raw = base64_decode($encoded, true);
    if ($raw === false || strlen($raw) < 28) {
        throw new RuntimeException('Invalid encrypted credential.');
    }

    $plain = openssl_decrypt(
        substr($raw, 28),
        'aes-256-gcm',
        reddottYoutubeCryptoKey(),
        OPENSSL_RAW_DATA,
        substr($raw, 0, 12),
        substr($raw, 12, 16)
    );

    if ($plain === false) {
        throw new RuntimeException('Unable to decrypt credential.');
    }

    return $plain;
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
    $expires = $expiresIn > 0
        ? (new DateTimeImmutable('now'))->modify('+' . $expiresIn . ' seconds')->format('Y-m-d H:i:s')
        : null;

    $old = db()->query(
        'SELECT refresh_token FROM reddott_youtube_connection WHERE id=1 LIMIT 1'
    )->fetchColumn();

    if ($refreshToken === '' && is_string($old) && $old !== '') {
        $refreshToken = reddottYoutubeDecrypt($old);
    }

    $statement = db()->prepare(
        'INSERT INTO reddott_youtube_connection
        (id,channel_id,channel_title,access_token,refresh_token,access_token_expires_at,created_at,updated_at)
        VALUES (1,?,?,?,?,?,?,?)
        ON DUPLICATE KEY UPDATE
        channel_id=VALUES(channel_id),
        channel_title=VALUES(channel_title),
        access_token=VALUES(access_token),
        refresh_token=VALUES(refresh_token),
        access_token_expires_at=VALUES(access_token_expires_at),
        updated_at=VALUES(updated_at)'
    );

    $statement->execute([
        $channelId,
        $channelTitle,
        reddottYoutubeEncrypt($accessToken),
        $refreshToken !== '' ? reddottYoutubeEncrypt($refreshToken) : null,
        $expires,
        $now,
        $now,
    ]);
}

function reddottYoutubeConnection(): ?array
{
    reddottYoutubeEnsureTable();
    $row = db()->query(
        'SELECT * FROM reddott_youtube_connection WHERE id=1 LIMIT 1'
    )->fetch();

    return $row ?: null;
}

function reddottYoutubeAccessToken(): string
{
    $row = reddottYoutubeConnection();
    if (!$row) {
        throw new RuntimeException('YouTube is not connected.');
    }

    $expires = !empty($row['access_token_expires_at'])
        ? new DateTimeImmutable((string)$row['access_token_expires_at'])
        : null;

    if ($expires && $expires > new DateTimeImmutable('+60 seconds')) {
        return reddottYoutubeDecrypt((string)$row['access_token']);
    }

    if (empty($row['refresh_token'])) {
        throw new RuntimeException('YouTube refresh token is missing. Reconnect YouTube.');
    }

    $r = reddottYoutubeHttpPost(
        'https://oauth2.googleapis.com/token',
        [
            'client_id' => reddottYoutubeClientId(),
            'client_secret' => reddottYoutubeClientSecret(),
            'refresh_token' => reddottYoutubeDecrypt((string)$row['refresh_token']),
            'grant_type' => 'refresh_token',
        ]
    );

    $token = json_decode($r, true);
    if (!is_array($token) || empty($token['access_token'])) {
        throw new RuntimeException('Unable to refresh YouTube access token.');
    }

    $accessToken = (string)$token['access_token'];
    $expiresIn = max(0, (int)($token['expires_in'] ?? 3600));
    $now = (new DateTimeImmutable('now'))->format('Y-m-d H:i:s');
    $until = (new DateTimeImmutable('now'))
        ->modify('+' . $expiresIn . ' seconds')
        ->format('Y-m-d H:i:s');

    $statement = db()->prepare(
        'UPDATE reddott_youtube_connection
         SET access_token=?, access_token_expires_at=?, updated_at=?
         WHERE id=1'
    );
    $statement->execute([
        reddottYoutubeEncrypt($accessToken),
        $until,
        $now,
    ]);

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

    $response = curl_exec($ch);
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new RuntimeException($error);
    }

    $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status < 200 || $status >= 300) {
        throw new RuntimeException('Google token endpoint returned HTTP ' . $status . '.');
    }

    return (string)$response;
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

    $response = curl_exec($ch);
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new RuntimeException($error);
    }

    $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($status < 200 || $status >= 300) {
        throw new RuntimeException('YouTube API returned HTTP ' . $status . '.');
    }

    return (string)$response;
}
