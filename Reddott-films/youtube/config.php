<?php
declare(strict_types=1);

/*
 * Reddott Films — separate YouTube OAuth configuration.
 *
 * This file deliberately does NOT contain the OAuth client secret.
 * Set these server environment variables in the hosting environment:
 *   REDDOTT_YOUTUBE_CLIENT_ID
 *   REDDOTT_YOUTUBE_CLIENT_SECRET
 */

function reddottYoutubeClientId(): string
{
    $value = getenv('REDDOTT_YOUTUBE_CLIENT_ID');
    return is_string($value) ? trim($value) : '';
}

function reddottYoutubeClientSecret(): string
{
    $value = getenv('REDDOTT_YOUTUBE_CLIENT_SECRET');
    return is_string($value) ? trim($value) : '';
}

function reddottYoutubeRequireCredentials(): void
{
    if (reddottYoutubeClientId() === '' || reddottYoutubeClientSecret() === '') {
        http_response_code(500);
        exit('Reddott Films YouTube OAuth is not configured on the server.');
    }
}
