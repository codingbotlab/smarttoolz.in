<?php
declare(strict_types=1);

/*
 * Reddott Films — separate YouTube OAuth configuration.
 *
 * Credentials are NEVER stored in GitHub. The code first checks server
 * environment variables, then an optional JSON file stored outside the
 * public web root. Google recommends keeping client_secret.json out of
 * publicly accessible locations and outside the source tree.
 */

function reddottYoutubeCredentialConfig(): array
{
    static $config = null;
    if (is_array($config)) {
        return $config;
    }

    $config = [];

    $jsonPath = getenv('REDDOTT_YOUTUBE_CLIENT_CONFIG');
    if (!is_string($jsonPath) || trim($jsonPath) === '') {
        $documentRoot = $_SERVER['DOCUMENT_ROOT'] ?? '';
        if (is_string($documentRoot) && $documentRoot !== '') {
            $jsonPath = dirname($documentRoot) . '/reddott-youtube-client.json';
        }
    }

    if (is_string($jsonPath) && trim($jsonPath) !== '' && is_file($jsonPath) && is_readable($jsonPath)) {
        $decoded = json_decode((string)file_get_contents($jsonPath), true);
        if (is_array($decoded['web'] ?? null)) {
            $config = $decoded['web'];
        }
    }

    return $config;
}

function reddottYoutubeClientId(): string
{
    $value = getenv('REDDOTT_YOUTUBE_CLIENT_ID');
    if (is_string($value) && trim($value) !== '') {
        return trim($value);
    }

    $config = reddottYoutubeCredentialConfig();
    return trim((string)($config['client_id'] ?? ''));
}

function reddottYoutubeClientSecret(): string
{
    $value = getenv('REDDOTT_YOUTUBE_CLIENT_SECRET');
    if (is_string($value) && trim($value) !== '') {
        return trim($value);
    }

    $config = reddottYoutubeCredentialConfig();
    return trim((string)($config['client_secret'] ?? ''));
}

function reddottYoutubeRequireCredentials(): void
{
    if (reddottYoutubeClientId() === '' || reddottYoutubeClientSecret() === '') {
        http_response_code(500);
        exit('Reddott Films YouTube OAuth is not configured on the server.');
    }
}
