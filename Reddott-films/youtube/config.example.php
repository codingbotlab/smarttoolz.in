<?php
declare(strict_types=1);

/*
 * Reddott Films — separate YouTube OAuth configuration.
 *
 * IMPORTANT:
 * Do NOT put the real client secret in GitHub.
 * Configure these values as server environment variables:
 *   REDDOTT_YOUTUBE_CLIENT_ID
 *   REDDOTT_YOUTUBE_CLIENT_SECRET
 */

const REDDOTT_YOUTUBE_CLIENT_ID = '';
const REDDOTT_YOUTUBE_CLIENT_SECRET = '';

function reddottYoutubeClientId(): string
{
    $value = getenv('REDDOTT_YOUTUBE_CLIENT_ID');
    return is_string($value) ? trim($value) : REDDOTT_YOUTUBE_CLIENT_ID;
}

function reddottYoutubeClientSecret(): string
{
    $value = getenv('REDDOTT_YOUTUBE_CLIENT_SECRET');
    return is_string($value) ? trim($value) : REDDOTT_YOUTUBE_CLIENT_SECRET;
}
